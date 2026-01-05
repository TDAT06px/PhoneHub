<?php
abstract class Controller {

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

    protected function loadView($viewName, $data = [], $layout = 'main') {
        extract($data);
        $viewPath = 'views/' . $viewName . '.php';

        if (file_exists($viewPath)) {
            
            if ($layout === 'none') {
                require_once $viewPath;
            } 
            elseif ($layout === 'admin') {
                $child_view = $viewPath;
                
                if (file_exists('views/layouts/admin.php')) {
                    require_once 'views/layouts/admin.php';      
                } 
                elseif (file_exists('views/layout/admin.php')) {
                    require_once 'views/layout/admin.php';   
                } 
                else {
                    die("<h3>Lỗi cấu trúc thư mục:</h3>
                         <p>Hệ thống không tìm thấy file giao diện Admin.</p>
                         <p>Vui lòng tạo file <b>admin.php</b> và đặt vào một trong các đường dẫn sau:</p>
                         <ul>
                            <li>views/layouts/admin.php</li>
                            <li>views/layout/admin.php</li>
                         </ul>");
                }
            }

            else {
                if ($layout === 'auth') {
                    if (file_exists('views/layout/header_auth.php')) require_once 'views/layout/header_auth.php';
                } else {
                    if (file_exists('views/layout/header.php')) require_once 'views/layout/header.php';
                }
                require_once $viewPath;
                
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
    protected function redirect($url) {
        $target = ($url === '') ? BASE_URL : BASE_URL . '/' . ltrim($url, '/');
        header('Location: ' . $target);
        exit; 
    }

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

    protected function checkAuth() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('auth/login');
        }
    }
 
    protected function checkAdmin() {
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '';
            $this->redirect('auth/login');
            exit;
        }
        if (empty($_SESSION['user']['id']) || empty($_SESSION['user']['email'])) {
            session_destroy();
            $this->redirect('auth/login');
            exit;
        }
        if (!isset($_SESSION['user']['role'])) {
        $userRole = trim($_SESSION['user']['role'] ?? '');
        if ($userRole !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang quản trị!';
            $this->redirect('');
            exit;
        }
    }
    }
}


?>