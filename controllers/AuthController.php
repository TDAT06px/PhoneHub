<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->loadModel('User');
    }

    public function login() {
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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = $_POST['mat_khau'];

            $user = $this->userModel->login($email, $password);

            if ($user) {
                if ($user['trang_thai'] == 0) {
                    $error_message = 'Tài khoản của bạn đang chờ duyệt hoặc bị khóa!';
                } else {
                    $userRole = trim($user['role'] ?? '');
                    $_SESSION['user'] = [
                        'id' => (int)$user['id'],
                        'ho_ten' => $user['ho_ten'],
                        'email' => $user['email'],
                        'role' => $userRole 
                    ];
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
        $this->loadView('auth/login', ['title' => 'Đăng nhập', 'error' => $error_message], 'auth');
    }

    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "/auth/login");
        exit;
    }

    public function profile() {
        if (!isset($_SESSION['user'])) {
            header("Location: " . BASE_URL . "/auth/login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $user = $this->userModel->getById($user_id);
        
        $error_message = '';
        $success_message = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
            $ngay_sinh = $_POST['ngay_sinh'] ?? null;
            $gioi_tinh = $_POST['gioi_tinh'] ?? 'Khác';

            if (empty($ho_ten)) {
                $error_message = 'Vui lòng nhập họ và tên!';
            } elseif (empty($email)) {
                $error_message = 'Vui lòng nhập email!';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = 'Email không hợp lệ!';
            } else {
                if ($email !== $user['email']) {
                    $existing = $this->userModel->findByEmail($email);
                    if ($existing) {
                        $error_message = 'Email này đã được sử dụng! Vui lòng chọn email khác.';
                    }
                }

                if (empty($error_message)) {
                    $data = [
                        'ho_ten' => $ho_ten,
                        'email' => $email,
                        'so_dien_thoai' => !empty($so_dien_thoai) ? $so_dien_thoai : null,
                        'ngay_sinh' => !empty($ngay_sinh) ? $ngay_sinh : null,
                        'gioi_tinh' => $gioi_tinh
                    ];

                    if ($this->userModel->update($user_id, $data)) {
                        $success_message = 'Cập nhật hồ sơ thành công!';
                        $_SESSION['user']['ho_ten'] = $ho_ten;
                        $_SESSION['user']['email'] = $email;
                        $user = $this->userModel->getById($user_id);
                    } else {
                        $error_message = 'Cập nhật thất bại! Vui lòng thử lại sau.';
                    }
                }
            }
        }

        $this->loadView('auth/profile', [
            'title' => 'Hồ sơ cá nhân',
            'user' => $user,
            'error' => $error_message,
            'success' => $success_message
        ]);
    }
    
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
            
            if (empty($ho_ten)) {
                $error_message = 'Vui lòng nhập họ và tên!';
            } elseif (empty($email)) {
                $error_message = 'Vui lòng nhập email!';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = 'Email không hợp lệ!';
            } elseif (empty($mat_khau)) {
                $error_message = 'Vui lòng nhập mật khẩu!';
            } elseif (strlen($mat_khau) < 6) {
                $error_message = 'Mật khẩu phải có ít nhất 6 ký tự!';
            } elseif ($mat_khau !== $mat_khau_confirm) {
                $error_message = 'Mật khẩu xác nhận không khớp!';
            } else {
                $existing_user = $this->userModel->findByEmail($email);
                if ($existing_user) {
                    $error_message = 'Email này đã được sử dụng! Vui lòng chọn email khác.';
                } else {
                    $data = [
                        'ho_ten' => $ho_ten,
                        'email' => $email,
                        'so_dien_thoai' => !empty($so_dien_thoai) ? $so_dien_thoai : null,
                        'mat_khau' => $mat_khau,
                        'gioi_tinh' => $gioi_tinh,
                        'ngay_sinh' => !empty($ngay_sinh) ? $ngay_sinh : null,
                        'role' => 'user'
                    ];
                    
                    $result = $this->userModel->create($data);
                    
                    if ($result) {
                        $success_message = 'Đăng ký thành công! Tài khoản của bạn đang chờ duyệt. Vui lòng đăng nhập sau khi được duyệt.';
                        $_POST = [];
                    } else {
                        $error_message = 'Đăng ký thất bại! Vui lòng thử lại sau.';
                    }
                }
            }
        }
        
        $this->loadView('auth/register', [
            'title' => 'Đăng ký', 
            'error' => $error_message,
            'success' => $success_message
        ], 'auth');
    }
}