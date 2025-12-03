<?php
// controllers/AuthController.php

class AuthController extends Controller {

    private $userModel;

    public function __construct() {
        $this->userModel = $this->loadModel('User');
    }

    public function login() {
        // Nếu đã đăng nhập thì đá về trang chủ luôn (bất kể là admin hay user)
        if (isset($_SESSION['user'])) {
            header("Location: " . BASE_URL);
            exit;
        }

        $error_message = '';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST['email']);
            $password = $_POST['mat_khau'];

            $user = $this->userModel->login($email, $password);

            if ($user) {
                // Lưu session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'ho_ten' => $user['ho_ten'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                
                // [QUAN TRỌNG] Tất cả đều về Trang chủ để quản lý
                header("Location: " . BASE_URL);
                exit;
            } else {
                $error_message = 'Email hoặc mật khẩu không chính xác!';
            }
        }

        $this->loadView('auth/login', ['title' => 'Đăng nhập', 'error' => $error_message], 'auth');
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL);
        exit;
    }

    public function register() {
        if (isset($_SESSION['user'])) { header("Location: " . BASE_URL); exit; }
        $error = ''; $success = '';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $ho_ten = trim($_POST['ho_ten']);
            $email = trim($_POST['email']);
            $mat_khau = $_POST['mat_khau'];
            $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';

            if (empty($ho_ten) || empty($email) || empty($mat_khau)) {
                $error = 'Vui lòng điền đầy đủ thông tin!';
            } elseif ($this->userModel->findByEmail($email)) {
                $error = 'Email này đã tồn tại!';
            } else {
                $data = [
                    'ho_ten' => $ho_ten, 'email' => $email, 'mat_khau' => $mat_khau,
                    'so_dien_thoai' => $so_dien_thoai, 'gioi_tinh' => 'Khác', 'ngay_sinh' => null
                ];
                if ($this->userModel->create($data)) {
                    $success = 'Đăng ký thành công! Bạn có thể đăng nhập.';
                } else {
                    $error = 'Lỗi hệ thống.';
                }
            }
        }
        $this->loadView('auth/register', ['error'=>$error, 'success'=>$success], 'auth');
    }

    public function profile() {
        $this->checkAuth();
        $user = $this->userModel->getById($_SESSION['user']['id']);
        $this->loadView('auth/profile', ['user'=>$user]);
    }
}
?>