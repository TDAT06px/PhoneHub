<?php
// controllers/AdminController.php

class AdminController extends Controller {

    public function __construct() {
        // Bắt buộc phải là Admin mới được vào các trang này
        $this->checkAdmin();
    }

    // Trang tổng quan
    public function dashboard() {
        $db = new Database(); 
        
        // Thống kê số liệu với xử lý lỗi an toàn
        $products_result = $db->query("SELECT COUNT(*) as c FROM sanpham", [], false);
        $orders_result = $db->query("SELECT COUNT(*) as c FROM donhang", [], false);
        $revenue_result = $db->query("SELECT SUM(tong_tien) as s FROM donhang WHERE trang_thai = 'Đã giao'", [], false);
        $users_result = $db->query("SELECT COUNT(*) as c FROM nguoidung WHERE role='user'", [], false);
        
        $stats = [
            'products' => $products_result['c'] ?? 0,
            'orders'   => $orders_result['c'] ?? 0,
            'revenue'  => $revenue_result['s'] ?? 0,
            'users'    => $users_result['c'] ?? 0
        ];

        // Lấy đơn hàng gần đây (5 đơn mới nhất)
        $orderModel = $this->loadModel('Order');
        $recent_orders = $orderModel->getAll();
        // Đảm bảo recent_orders là array
        if (!is_array($recent_orders)) {
            $recent_orders = [];
        }
        $recent_orders = array_slice($recent_orders, 0, 5);

        // Tính toán dữ liệu biểu đồ doanh thu 6 tháng gần nhất
        $chart_data = [];
        $chart_labels = [];
        
        // Lấy doanh thu theo tháng (6 tháng gần nhất)
        $monthly_revenue = $db->query("
            SELECT 
                DATE_FORMAT(ngay_tao, '%Y-%m') as month,
                SUM(tong_tien) as revenue
            FROM donhang 
            WHERE trang_thai = 'Đã giao'
            GROUP BY DATE_FORMAT(ngay_tao, '%Y-%m')
            ORDER BY month DESC
            LIMIT 6
        ");
        
        // Xử lý dữ liệu nếu có kết quả
        if (!empty($monthly_revenue) && is_array($monthly_revenue)) {
            // Đảo ngược để hiển thị từ cũ đến mới
            $monthly_revenue = array_reverse($monthly_revenue);
            
            // Tạo mảng dữ liệu và nhãn
            foreach ($monthly_revenue as $row) {
                $chart_data[] = (float)($row['revenue'] ?? 0);
                // Chuyển đổi tháng thành định dạng tiếng Việt
                $month_num = (int)substr($row['month'], 5, 2);
                $chart_labels[] = 'Tháng ' . $month_num;
            }
        }
        
        // Nếu không có dữ liệu, tạo mảng rỗng với 6 tháng mặc định
        if (empty($chart_labels)) {
            $current_month = (int)date('m');
            for ($i = 5; $i >= 0; $i--) {
                $month = $current_month - $i;
                if ($month <= 0) $month += 12;
                $chart_labels[] = 'Tháng ' . $month;
                $chart_data[] = 0;
            }
        }
        
        $this->loadView('admin/dashboard', [
            'title' => 'Tổng quan', 
            'stats' => $stats,
            'recent_orders' => $recent_orders,
            'chart_data' => json_encode($chart_data),
            'chart_labels' => json_encode($chart_labels)
        ], 'admin');
    }

    // Quản lý đơn hàng (MỚI)
    public function orders() {
        $orderModel = $this->loadModel('Order');

        // Xử lý cập nhật trạng thái
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['trang_thai'])) {
            $order_id = (int)$_POST['order_id'];
            $status = trim($_POST['trang_thai']);
            
            // Validate trạng thái hợp lệ
            $valid_statuses = ['Chờ xử lý', 'Đã xác nhận', 'Đang giao', 'Đã giao', 'Đã hủy'];
            if (in_array($status, $valid_statuses) && $order_id > 0) {
                $orderModel->updateStatus($order_id, $status);
            }
            $this->redirect('admin/orders');
        }

        $orders = $orderModel->getAll();
        // Đảm bảo orders là array ngay cả khi null
        if (!is_array($orders)) {
            $orders = [];
        }
        $this->loadView('admin/orders', ['title' => 'Quản lý Đơn hàng', 'orders' => $orders], 'admin');
    }

    /**
     * Hiển thị chi tiết đơn hàng cho Admin
     * URL: /admin/orderDetail/{id}
     */
    public function orderDetail($order_id = 0) {
        $orderModel = $this->loadModel('Order');
        $order_id = (int)$order_id;
        if ($order_id <= 0) {
            $this->redirect('admin/orders');
            return;
        }

        $order = $orderModel->getOrderById($order_id); // admin: no user filter
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng.';
            $this->redirect('admin/orders');
            return;
        }

        $details = $orderModel->getOrderDetails($order_id);

        $data = [
            'title' => 'Chi tiết đơn hàng #' . $order['id'],
            'order' => $order,
            'details' => $details,
            // back_url để view biết quay về trang nào (admin sẽ về danh sách đơn hàng admin)
            'back_url' => BASE_URL . '/admin/orders'
        ];

        // Reuse the existing order detail view but render inside admin layout
        $this->loadView('order/detail', $data, 'admin');
    }

    // Quản lý nhân sự
    public function users() {
        $userModel = $this->loadModel('User');
        
        // Xử lý tạo tài khoản mới
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
            
            if ($action === 'create') {
                // Tạo tài khoản mới
                $ho_ten = trim($_POST['ho_ten'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $mat_khau = $_POST['mat_khau'] ?? '';
                $role = $_POST['role'] ?? 'user';
                
                // Validate
                $error = '';
                if (empty($ho_ten)) $error = 'Tên không được để trống!';
                elseif (empty($email)) $error = 'Email không được để trống!';
                elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Email không hợp lệ!';
                elseif (empty($mat_khau)) $error = 'Mật khẩu không được để trống!';
                elseif (strlen($mat_khau) < 6) $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
                elseif (!in_array($role, ['user', 'admin'])) $error = 'Vai trò không hợp lệ!';
                else {
                    // Kiểm tra email đã tồn tại
                    $existing = $userModel->findByEmail($email);
                    if ($existing) $error = 'Email này đã được sử dụng!';
                }
                
                if (!$error) {
                    // Tạo tài khoản
                    $userModel->create([
                        'ho_ten' => $ho_ten,
                        'email' => $email,
                        'mat_khau' => $mat_khau,
                        'role' => $role,
                        'so_dien_thoai' => null,
                        'gioi_tinh' => 'Khác',
                        'ngay_sinh' => null
                    ]);
                    $_SESSION['success'] = 'Tạo tài khoản thành công!';
                    $this->redirect('admin/users');
                } else {
                    $_SESSION['error'] = $error;
                    $this->redirect('admin/users');
                }
            } elseif ($action === 'update') {
                // Cập nhật role và trạng thái người dùng
                $db = new Database();
                $db->execute("UPDATE nguoidung SET role=:role, trang_thai=:trang_thai WHERE id=:id", [
                    ':role' => $_POST['role'], 
                    ':trang_thai' => $_POST['trang_thai'], 
                    ':id' => (int)$_POST['user_id']
                ]);
                $_SESSION['success'] = 'Cập nhật thông tin thành công!';
                $this->redirect('admin/users');
            } elseif ($action === 'delete') {
                // Xóa tài khoản người dùng
                $user_id = (int)$_POST['user_id'];
                // Không cho phép xóa tài khoản admin hiện tại
                if ($user_id === $_SESSION['user']['id']) {
                    $_SESSION['error'] = 'Không thể xóa tài khoản của chính mình!';
                } else {
                    $db = new Database();
                    $db->execute("DELETE FROM nguoidung WHERE id=:id", [':id' => $user_id]);
                    $_SESSION['success'] = 'Xóa tài khoản thành công!';
                }
                $this->redirect('admin/users');
            }
        }

        $users = $userModel->getAllUsers($_SESSION['user']['id']);
        // Đảm bảo users là array ngay cả khi null
        if (!is_array($users)) {
            $users = [];
        }
        
        $success = $_SESSION['success'] ?? '';
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['success'], $_SESSION['error']);
        
        $this->loadView('admin/users', [
            'title' => 'Quản lý Nhân sự', 
            'users' => $users,
            'success' => $success,
            'error' => $error
        ], 'admin');
    }
    
        // Admin: Reset password for a user by email
        public function resetPassword() {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->redirect('admin/users');
            }

            $email = trim($_POST['email'] ?? '');
            $newPassword = $_POST['new_password'] ?? '';

            if (empty($email) || empty($newPassword)) {
                $this->redirect('admin/users?error=' . rawurlencode('Email hoặc mật khẩu mới trống'));
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->redirect('admin/users?error=' . rawurlencode('Email không hợp lệ'));
            }

            if (strlen($newPassword) < 6) {
                $this->redirect('admin/users?error=' . rawurlencode('Mật khẩu phải có ít nhất 6 ký tự'));
            }

            // Tải model User và cập nhật mật khẩu
            $userModel = $this->loadModel('User');
            $user = $userModel->findByEmail($email);
            if (!$user) {
                $this->redirect('admin/users?error=' . rawurlencode('Không tìm thấy người dùng với email này'));
            }

            $ok = $userModel->setPassword($user['id'], $newPassword);
            if ($ok) {
                $this->redirect('admin/users?success=' . rawurlencode('Đã đặt lại mật khẩu cho ' . $email));
            } else {
                $this->redirect('admin/users?error=' . rawurlencode('Cập nhật mật khẩu thất bại'));
            }
        }

    // Quản lý kho
    public function inventory() {
        $db = new Database();
        $products = $db->query("SELECT * FROM sanpham ORDER BY so_luong_ton ASC");
        // Đảm bảo products là array ngay cả khi null
        if (!is_array($products)) {
            $products = [];
        }
        $this->loadView('admin/inventory', ['title' => 'Quản lý Kho', 'products' => $products], 'admin');
    }

    // Báo cáo doanh thu
    public function revenue() {
        $db = new Database();
        $stats = $db->query("
            SELECT DATE(ngay_tao) as date, COUNT(*) as don_hang, SUM(tong_tien) as doanh_thu 
            FROM donhang 
            WHERE trang_thai != 'Đã hủy'
            GROUP BY DATE(ngay_tao) 
            ORDER BY date DESC LIMIT 30
        ");
        // Đảm bảo stats là array ngay cả khi null
        if (!is_array($stats)) {
            $stats = [];
        }
        $this->loadView('admin/revenue', ['title' => 'Doanh thu', 'stats' => $stats], 'admin');
    }
}
?>