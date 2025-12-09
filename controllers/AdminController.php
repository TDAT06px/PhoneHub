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
        
        // Thống kê số liệu
        $stats = [
            'products' => $db->query("SELECT COUNT(*) as c FROM sanpham", [], false)['c'],
            'orders'   => $db->query("SELECT COUNT(*) as c FROM donhang", [], false)['c'],
            'revenue'  => $db->query("SELECT SUM(tong_tien) as s FROM donhang WHERE trang_thai = 'Đã giao'", [], false)['s'] ?? 0,
            'users'    => $db->query("SELECT COUNT(*) as c FROM nguoidung WHERE role='user'", [], false)['c']
        ];

        // Biểu đồ doanh thu (đơn giản hóa)
        // Biểu đồ doanh thu: tổng doanh thu của 6 tháng gần nhất (theo tháng)
        $months_to_show = 6;
        $start_date = date('Y-m-01', strtotime("-" . ($months_to_show - 1) . " months"));

        $sql = "SELECT DATE_FORMAT(ngay_tao, '%Y-%m') AS ym, SUM(tong_tien) AS s
                FROM donhang
                WHERE trang_thai = 'Đã giao' AND ngay_tao >= :start_date
                GROUP BY ym
                ORDER BY ym ASC";

        $rows = $db->query($sql, [':start_date' => $start_date]);
        $map = [];
        if (is_array($rows)) {
            foreach ($rows as $r) {
                $map[$r['ym']] = (float)($r['s'] ?? 0);
            }
        }

        $chart_labels = [];
        $chart_values = [];
        for ($i = $months_to_show - 1; $i >= 0; $i--) {
            $ym = date('Y-m', strtotime("-{$i} months"));
            $label = date('m/Y', strtotime("-{$i} months"));
            $chart_labels[] = $label;
            $chart_values[] = isset($map[$ym]) ? (float)$map[$ym] : 0;
        }

        // Lấy đơn hàng mới nhất để hiển thị ở bên dưới
        $recent_orders = $db->query("SELECT * FROM donhang ORDER BY ngay_tao DESC LIMIT 6");

        $this->loadView('admin/dashboard', [
            'title' => 'Tổng quan',
            'stats' => $stats,
            'chart_data' => json_encode($chart_values),
            'chart_labels' => json_encode($chart_labels),
            'recent_orders' => $recent_orders
        ], 'admin');
    }

    // Quản lý đơn hàng (MỚI)
    public function orders() {
        $orderModel = $this->loadModel('Order');

        // Xử lý cập nhật trạng thái
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
            $order_id = (int)$_POST['order_id'];
            $status = $_POST['trang_thai'];
            
            $orderModel->updateStatus($order_id, $status);
            $this->redirect('admin/orders');
        }

        $orders = $orderModel->getAll();
        $this->loadView('admin/orders', ['title' => 'Quản lý Đơn hàng', 'orders' => $orders], 'admin');
    }

    /**
     * Hiển thị chi tiết đơn hàng cho Admin
     * URL: /admin/orderDetail/{id}
     */
    public function orderDetail($order_id = 0) {
        $order_id = (int)$order_id;
        $orderModel = $this->loadModel('Order');

        $order = $orderModel->getOrderById($order_id); // Không truyền user_id -> admin xem được
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng.';
            $this->redirect('admin/orders');
            return;
        }

        $details = $orderModel->getOrderDetails($order_id);

        $this->loadView('admin/order_detail', [
            'title' => 'Chi tiết đơn #' . $order['id'],
            'order' => $order,
            'details' => $details
        ], 'admin');
    }

    // Quản lý nhân sự
    public function users() {
        $userModel = $this->loadModel('User');

        // Xử lý POST từ form trên trang Quản lý Nhân sự
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Loại hành động: create_user | change_password | update_status
            $action = $_POST['action'] ?? '';

            if ($action === 'create_user') {
                // Tạo người dùng mới (role bắt buộc là 'user' theo yêu cầu)
                $ho_ten = trim($_POST['ho_ten'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
                $mat_khau = $_POST['mat_khau'] ?? '';

                // Basic validation
                if (empty($ho_ten) || empty($email) || empty($mat_khau) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin hợp lệ để tạo tài khoản.';
                    $this->redirect('admin/users');
                }

                // Kiểm tra email đã tồn tại
                if ($userModel->findByEmail($email)) {
                    $_SESSION['error'] = 'Email đã tồn tại.';
                    $this->redirect('admin/users');
                }

                $data = [
                    'ho_ten' => $ho_ten,
                    'email' => $email,
                    'so_dien_thoai' => $so_dien_thoai ?: null,
                    'mat_khau' => $mat_khau,
                    'gioi_tinh' => 'Khác',
                    'ngay_sinh' => null,
                    'role' => 'user'
                ];

                $res = $userModel->create($data);
                if ($res) {
                    $_SESSION['success'] = 'Tạo tài khoản thành công.';
                } else {
                    $_SESSION['error'] = 'Tạo tài khoản thất bại.';
                }

                $this->redirect('admin/users');
                return;
            }

            if ($action === 'change_password') {
                $user_id = (int)($_POST['user_id'] ?? 0);
                $new_pass = $_POST['new_password'] ?? '';
                if ($user_id <= 0 || strlen($new_pass) < 6) {
                    $_SESSION['error'] = 'Mật khẩu mới không hợp lệ (ít nhất 6 ký tự).';
                    $this->redirect('admin/users');
                }

                $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $updated = $userModel->update($user_id, ['mat_khau' => $hashed]);
                if ($updated) $_SESSION['success'] = 'Đổi mật khẩu thành công.'; else $_SESSION['error'] = 'Đổi mật khẩu thất bại.';
                $this->redirect('admin/users');
                return;
            }

            if ($action === 'update_status') {
                $user_id = (int)($_POST['user_id'] ?? 0);
                $status = $_POST['trang_thai'] ?? '0';
                $role = $_POST['role'] ?? null; // We will not allow role escalation from this page (optional)

                // Normalize status
                $status_val = ($status === '1' || $status === 'active' || $status === 'Đã kích hoạt') ? 1 : 0;

                // Use model helper if exists
                if (method_exists($userModel, 'updateRoleAndStatus') && $role !== null) {
                    $userModel->updateRoleAndStatus($user_id, $role, $status_val);
                } else {
                    $db = new Database();
                    $db->execute("UPDATE nguoidung SET trang_thai = ? WHERE id = ?", [$status_val, $user_id]);
                }

                $_SESSION['success'] = 'Cập nhật thành công.';
                $this->redirect('admin/users');
                return;
            }
        }

        $users = $userModel->getAllUsers($_SESSION['user']['id']);
        $this->loadView('admin/users', ['title' => 'Quản lý Nhân sự', 'users' => $users], 'admin');
    }

    // Quản lý kho
    public function inventory() {
        $db = new Database();
        $products = $db->query("SELECT * FROM sanpham ORDER BY so_luong_ton ASC");
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
        $this->loadView('admin/revenue', ['title' => 'Doanh thu', 'stats' => $stats], 'admin');
    }
}
?>