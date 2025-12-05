<?php
// controllers/Controller.php

abstract class Controller {

    /**
     * Tải Model
     */
    protected function loadModel($modelName) {
        $modelPath = 'models/' . ucfirst($modelName) . '.php';
        if (file_exists($modelPath)) {
            $className = ucfirst($modelName);
            if (class_exists($className)) {
                return new $className();
            }
        }
        return null;
    }

    /**
     * Tải View (Giao diện) - Đã nâng cấp để tự tìm file Admin
     */
    protected function loadView($viewName, $data = [], $layout = 'main') {
        // Giải nén dữ liệu
        extract($data);
        
        // Đường dẫn file nội dung (ví dụ: views/admin/orders.php)
        $viewPath = 'views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            
            // 1. TRƯỜNG HỢP: Không dùng layout
            if ($layout === 'none') {
                require_once $viewPath;
            } 
            
            // 2. TRƯỜNG HỢP: Layout ADMIN
            elseif ($layout === 'admin') {
                // Set biến $child_view để admin layout có thể include view con
                $child_view = $viewPath;
                
                // Code này sẽ thử tìm file admin.php ở 3 chỗ khác nhau
                // Chỗ nào có thì nó sẽ lấy, bạn không lo sai tên thư mục nữa.
                
                if (file_exists('views/layouts/admin.php')) {
                    require_once 'views/layouts/admin.php';      // Ưu tiên 1: Thư mục layouts (có s)
                } 
                elseif (file_exists('views/layout/admin.php')) {
                    require_once 'views/layout/admin.php';       // Ưu tiên 2: Thư mục layout (không s)
                } 
                else {
                    // Nếu tìm cả 3 chỗ đều không thấy thì mới báo lỗi
                    die("<h3>Lỗi cấu trúc thư mục:</h3>
                         <p>Hệ thống không tìm thấy file giao diện Admin.</p>
                         <p>Vui lòng tạo file <b>admin.php</b> và đặt vào một trong các đường dẫn sau:</p>
                         <ul>
                            <li>views/layouts/admin.php</li>
                            <li>views/layout/admin.php</li>
                         </ul>");
                }
            }

            // 3. TRƯỜNG HỢP: Layout Trang chủ & Auth
            else {
                // Tự động tìm Header
                if ($layout === 'auth') {
                    // Tìm header cho Auth
                    if (file_exists('views/layout/header_auth.php')) require_once 'views/layout/header_auth.php';
                } else {
                    // Tìm header mặc định (Trang chủ)
                    if (file_exists('views/layout/header.php')) require_once 'views/layout/header.php';
                }
                
                // Tải nội dung chính
                require_once $viewPath;
                
                // Tự động tìm Footer
                if ($layout === 'auth') {
                    if (file_exists('views/layout/footer_auth.php')) require_once 'views/layout/footer_auth.php';
                } else {
                    if (file_exists('views/layout/footer.php')) require_once 'views/layout/footer.php';
                }
            }

        } else {
            die("Lỗi hệ thống: Không tìm thấy file view nội dung tại '$viewPath'");
        }
    }

    /**
     * Chuyển hướng
     */
    protected function redirect($url) {
        $target = ($url === '') ? BASE_URL : BASE_URL . '/' . ltrim($url, '/');
        header('Location: ' . $target);
        exit; 
    }

    /**
     * Báo lỗi 404
     */
    public function error404() {
        http_response_code(404);
        if (file_exists('views/404.php')) {
            $this->loadView('404', [], 'none');
        } elseif (file_exists('views/error/404.php')) {
             $this->loadView('error/404', [], 'none');
        } else {
            echo "404 Not Found";
        }
        exit;
    }

    /**
     * Kiểm tra Auth
     */
    protected function checkAuth() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Kiểm tra Admin - Cải thiện để chắc chắn chỉ admin mới vào được
     */
    protected function checkAdmin() {
        // Kiểm tra session tồn tại
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
            $this->redirect('auth/login');
            exit;
        }
        
        // Kiểm tra thêm: đảm bảo session không bị giả mạo
        if (empty($_SESSION['user']['id']) || empty($_SESSION['user']['email'])) {
            session_destroy();
            $this->redirect('auth/login');
            exit;
        }
        
        // Kiểm tra role phải chính xác là 'admin' (không phải 'user' hay 'staff')
        $userRole = trim($_SESSION['user']['role'] ?? '');
        if ($userRole !== 'admin') {
            // Nếu không phải admin, chuyển về trang chủ và dừng
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang quản trị!';
            $this->redirect('');
            exit;
        }
    }
}
?>