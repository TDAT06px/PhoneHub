<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->loadModel('User');
    }

    public function login() {
        // Nếu đã đăng nhập, kiểm tra role và redirect đúng
        if (isset($_SESSION['user'])) {
            $userRole = trim($_SESSION['user']['role'] ?? '');
            // CHỈ admin mới vào admin dashboard (không phải user hay staff)
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
                // KIỂM TRA TRẠNG THÁI TÀI KHOẢN
                if ($user['trang_thai'] == 0) {
                    $error_message = 'Tài khoản của bạn đang chờ duyệt hoặc bị khóa!';
                } else {
                    // Đăng nhập thành công - Lưu đầy đủ thông tin vào session
                    $userRole = trim($user['role'] ?? '');
                    $_SESSION['user'] = [
                        'id' => (int)$user['id'],
                        'ho_ten' => $user['ho_ten'],
                        'email' => $user['email'],
                        'role' => $userRole // Đảm bảo role được lưu đúng và trim
                    ];
                    
                    // Phân hướng theo role: CHỈ admin mới vào admin dashboard
                    if ($userRole === 'admin') {
                        header("Location: " . BASE_URL . "/admin/dashboard");
                    } else {
                        // User và staff không được vào admin - chuyển về trang chủ
                        header("Location: " . BASE_URL);
                    }
                    exit;
                }
            } else {
                $error_message = 'Email hoặc mật khẩu không đúng!';
            }
        }
        // Dùng layout auth_layout thay vì 'auth'
        require_once 'views/layout/auth_layout.php';
        require_once 'views/auth/login.php';
        require_once 'views/layout/auth_layout_footer.php';
    }

    public function logout() {
        session_destroy();
        header("Location: " . BASE_URL . "/auth/login");
        exit;
    }
    
    public function register() {
        // Nếu đã đăng nhập thì chuyển về trang chủ
        if (isset($_SESSION['user'])) { 
            header("Location: " . BASE_URL); 
            exit; 
        }
        
        $error_message = '';
        $success_message = '';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy dữ liệu từ form
            $ho_ten = trim($_POST['ho_ten'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $so_dien_thoai = trim($_POST['so_dien_thoai'] ?? '');
            $mat_khau = $_POST['mat_khau'] ?? '';
            $gioi_tinh = $_POST['gioi_tinh'] ?? 'Khác';
            $ngay_sinh = $_POST['ngay_sinh'] ?? null;
            
            // Validation
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
            } else {
                // Kiểm tra email đã tồn tại chưa
                $existing_user = $this->userModel->findByEmail($email);
                if ($existing_user) {
                    $error_message = 'Email này đã được sử dụng! Vui lòng chọn email khác.';
                } else {
                    // Tạo tài khoản mới
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
                        // Xóa dữ liệu form sau khi đăng ký thành công
                        $_POST = [];
                    } else {
                        $error_message = 'Đăng ký thất bại! Vui lòng thử lại sau.';
                    }
                }
            }
        }
        
        // Dùng layout auth_layout thay vì 'auth'
        require_once 'views/layout/auth_layout.php';
        require_once 'views/auth/register.php';
        require_once 'views/layout/auth_layout_footer.php';
    }

    public function profile() {
        // Bắt buộc đăng nhập để xem profile
        $this->checkAuth();
        
        $user_id = $_SESSION['user']['id'];
        $user = $this->userModel->getById($user_id);
        
        if (!$user) {
            $this->redirect('auth/login');
            return;
        }
        
        $success = '';
        $error = '';
        
        // Xử lý update thông tin
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'ho_ten' => trim($_POST['ho_ten'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'so_dien_thoai' => trim($_POST['so_dien_thoai'] ?? ''),
                'ngay_sinh' => $_POST['ngay_sinh'] ?? null,
                'gioi_tinh' => $_POST['gioi_tinh'] ?? 'Khác'
            ];
            
            // Validate dữ liệu
            if (empty($data['ho_ten'])) {
                $error = 'Họ và tên không được để trống!';
            } elseif (empty($data['email'])) {
                $error = 'Email không được để trống!';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'Email không hợp lệ!';
            } else {
                // Kiểm tra email không trùng với người khác
                $check_email = $this->userModel->findByEmail($data['email']);
                if ($check_email && $check_email['id'] != $user_id) {
                    $error = 'Email này đã được sử dụng!';
                } else {
                    if ($this->userModel->update($user_id, $data)) {
                        $success = 'Cập nhật thông tin thành công!';
                        // Cập nhật session
                        $_SESSION['user']['ho_ten'] = $data['ho_ten'];
                        $_SESSION['user']['email'] = $data['email'];
                        // Refresh user data
                        $user = $this->userModel->getById($user_id);
                    } else {
                        $error = 'Cập nhật thất bại! Vui lòng thử lại.';
                    }
                }
            }
        }
        
        $this->loadView('auth/profile', [
            'title' => 'Hồ sơ cá nhân',
            'user' => $user,
            'success' => $success,
            'error' => $error
        ]);
    }
}