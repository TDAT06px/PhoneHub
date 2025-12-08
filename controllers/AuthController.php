<?php
// File: controllers/AuthController.php

class AuthController extends Controller {

    private $userModel;

    public function __construct() {
        // Load Model User ngay khi khởi tạo
        $this->userModel = $this->loadModel('User');
    }

    /**
     * =========================================================================
     * CHỨC NĂNG ĐĂNG NHẬP
     * =========================================================================
     */
    public function login() {
        // 1. Nếu đã đăng nhập thì chuyển hướng
        if (isset($_SESSION['user'])) {
            $userRole = trim($_SESSION['user']['role'] ?? '');
            if ($userRole === 'admin') {
                header("Location: " . BASE_URL . "/admin/dashboard");
            } else {
                header("Location: " . BASE_URL);
            }
            exit;
        }
        
        $error_message = '';
        
        // 2. Xử lý khi submit form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = $_POST['mat_khau'];

            // Gọi model kiểm tra đăng nhập
            $user = $this->userModel->login($email, $password);

            if ($user) {
                // Kiểm tra trạng thái (0: Khóa, 1: Hoạt động)
                if ($user['trang_thai'] == 0) {
                    $error_message = 'Tài khoản của bạn đang bị khóa!';
                } else {
                    // Đăng nhập thành công -> Lưu Session
                    $userRole = trim($user['role'] ?? '');
                    $_SESSION['user'] = [
                        'id' => (int)$user['id'],
                        'ho_ten' => $user['ho_ten'],
                        'email' => $user['email'],
                        'role' => $userRole
                    ];
                    
                    // Chuyển hướng theo quyền
                    if ($userRole === 'admin') {
                        header("Location: " . BASE_URL . "/admin/dashboard");
                    } else {
                        header("Location: " . BASE_URL);
                    }
                    exit;
                }
            } else {
                $error_message = 'Email hoặc mật khẩu không đúng!';
            }
        }
        
        // 3. Load View (Sử dụng Layout Auth riêng biệt)
        $title = 'Đăng nhập';
        $error = $error_message;
        require_once 'views/layout/auth_layout.php';
        require_once 'views/auth/login.php';
        require_once 'views/layout/auth_layout_footer.php';
    }

    /**
     * =========================================================================
     * CHỨC NĂNG ĐĂNG XUẤT
     * =========================================================================
     */
    public function logout() {
        // Chỉ xóa session user, giữ lại giỏ hàng (nếu muốn)
        unset($_SESSION['user']);
        // Hoặc xóa sạch: session_destroy();
        
        header("Location: " . BASE_URL . "/auth/login");
        exit;
    }
    
    /**
     * =========================================================================
     * CHỨC NĂNG ĐĂNG KÝ
     * =========================================================================
     */
    public function register() {
        if (isset($_SESSION['user'])) { 
            header("Location: " . BASE_URL); 
            exit; 
        }
        
        $error_message = '';
        $success_message = '';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';
            $mat_khau_confirm = $_POST['mat_khau_confirm'] ?? '';
            $gioi_tinh = $_POST['gioi_tinh'] ?? 'Khác';
            $ngay_sinh = $_POST['ngay_sinh'] ?? null;
            
            // Validate cơ bản
            if (empty($ho_ten) || empty($email) || empty($mat_khau) || empty($mat_khau_confirm)) {
                $error_message = 'Vui lòng điền đầy đủ các trường bắt buộc!';
            } elseif (strlen($mat_khau) < 6) {
                $error_message = 'Mật khẩu phải có ít nhất 6 ký tự!';
            } elseif ($mat_khau !== $mat_khau_confirm) {
                $error_message = 'Mật khẩu nhập lại không khớp!';
            } else {
                // Kiểm tra email trùng
                $existing_user = $this->userModel->findByEmail($email);
                if ($existing_user) {
                    $error_message = 'Email này đã được sử dụng!';
                } else {
                    $data = [
                        'ho_ten' => $ho_ten,
                        'email' => $email,
                        'so_dien_thoai' => $so_dien_thoai,
                        'mat_khau' => $mat_khau,
                        'gioi_tinh' => $gioi_tinh,
                        'ngay_sinh' => $ngay_sinh,
                        'role' => 'user'
                    ];
                    
                    if ($this->userModel->create($data)) {
                        $success_message = 'Đăng ký thành công! Vui lòng đăng nhập.';
                        $_POST = []; // Reset form
                    } else {
                        $error_message = 'Đăng ký thất bại! Lỗi hệ thống.';
                    }
                }
            }
        }
        
        // Load View
        $title = 'Đăng ký';
        $error = $error_message;
        $success = $success_message;
        require_once 'views/layout/auth_layout.php';
        require_once 'views/auth/register.php';
        require_once 'views/layout/auth_layout_footer.php';
    }



    /**
     * =========================================================================
     * CHỨC NĂNG HỒ SƠ CÁ NHÂN (Yêu cầu đăng nhập)
     * =========================================================================
     */
    public function profile() {
        // Bắt buộc đăng nhập mới xem được
        $this->checkAuth();
        
        $user_id = $_SESSION['user']['id'];
        $user = $this->userModel->getById($user_id);
        
        if (!$user) {
            $this->logout(); // Nếu không tìm thấy user trong DB (đã bị xóa), logout luôn
            return;
        }
        
        $success = '';
        $error = '';
        
        // Xử lý cập nhật thông tin
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'ho_ten' => trim($_POST['ho_ten'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
                'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                'gioi_tinh' => $_POST['gioi_tinh'] ?? 'Khác'
            ];
            
            // Validate
            if (empty($data['ho_ten']) || empty($data['email'])) {
                $error = 'Họ tên và Email không được để trống!';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'Email không hợp lệ!';
            } else {
                // Check trùng email với người khác
                $check_email = $this->userModel->findByEmail($data['email']);
                if ($check_email && $check_email['id'] != $user_id) {
                    $error = 'Email này đã được tài khoản khác sử dụng!';
                } else {
                    if ($this->userModel->update($user_id, $data)) {
                        $success = 'Cập nhật thông tin thành công!';
                        
                        // Cập nhật lại Session
                        $_SESSION['user']['ho_ten'] = $data['ho_ten'];
                        $_SESSION['user']['email'] = $data['email'];
                        
                        // Lấy lại dữ liệu mới nhất để hiển thị
                        $user = $this->userModel->getById($user_id);
                    } else {
                        $error = 'Cập nhật thất bại. Vui lòng thử lại.';
                    }
                }
            }
        }
        
        // Load View (Dùng view chính có Header/Footer vì đã login)
        $this->loadView('auth/profile', [
            'title' => 'Hồ sơ cá nhân',
            'user' => $user,
            'success' => $success,
            'error' => $error
        ]);
    }
}
?>