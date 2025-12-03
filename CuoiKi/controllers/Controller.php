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
     * Tải View (Giao diện)
     * @param string $viewName Tên file view (ví dụ: 'product/list')
     * @param array $data Mảng dữ liệu truyền sang view
     * @param string $layout Layout sử dụng ('main', 'auth', hoặc 'none')
     */
    protected function loadView($viewName, $data = [], $layout = 'main') {
        // Giải nén mảng data thành các biến riêng lẻ
        extract($data);
        
        $viewPath = 'views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            
            // 1. Nếu layout là 'none' -> Chỉ tải view, không Header/Footer
            // (Dùng cho trang 404, Ajax, hoặc trang Landing page riêng biệt)
            if ($layout === 'none') {
                require_once $viewPath;
            } 
            // 2. Các trường hợp còn lại -> Tải Header + View + Footer
            else {
                // Chuẩn hóa tên layout
                $layout = ($layout == 'main') ? 'default' : $layout;

                // Tải Header
                if ($layout == 'default') {
                    require_once 'views/layout/header.php';
                } elseif ($layout == 'auth') {
                    require_once 'views/layout/header_auth.php';
                }
                
                // Tải nội dung chính
                require_once $viewPath;
                
                // Tải Footer
                if ($layout == 'default') {
                    require_once 'views/layout/footer.php';
                } elseif ($layout == 'auth') {
                    require_once 'views/layout/footer_auth.php';
                }
            }
        } else {
            // Nếu không tìm thấy file view, dừng và báo lỗi
            die("Lỗi hệ thống: Không tìm thấy file view '$viewPath'");
        }
    }

    /**
     * Chuyển hướng trang (Redirect)
     */
    protected function redirect($url) {
        // Nếu $url rỗng, về trang chủ. Nếu không, nối vào BASE_URL
        $target = ($url === '') ? BASE_URL : BASE_URL . '/' . ltrim($url, '/');
        header('Location: ' . $target);
        exit; // Quan trọng: Dừng code ngay sau khi redirect
    }

    /**
     * Hàm hiển thị lỗi 404
     * Sẽ gọi view 'error/404' với layout 'none' để hiển thị toàn màn hình
     */
    public function error404() {
        // Gửi mã lỗi 404 về trình duyệt
        http_response_code(404);
        
        // Gọi view 404 đẹp, không dùng header/footer chung
        $this->loadView('error/404', [], 'none'); 
        exit;
    }

    /**
     * Kiểm tra đăng nhập (Bảo vệ các trang cần User)
     */
    protected function checkAuth() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Kiểm tra quyền Admin (Bảo vệ trang quản trị)
     */
    protected function checkAdmin() {
        // 1. Phải đăng nhập
        if (!isset($_SESSION['user'])) {
            $this->redirect('auth/login'); 
        }
        
        // 2. Phải là role admin
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            // Nếu cố tình vào, cho ra đảo (404) để giấu trang admin
            $this->error404(); 
        }
    }
}