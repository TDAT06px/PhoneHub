<?php
// index.php - Front Controller

// 1. Khởi động Session
session_start();

// 2. Tải cấu hình (Thử cả 2 đường dẫn để chắc chắn tìm thấy)
if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} elseif (file_exists('db.php')) {
    require_once 'db.php';
} else {
    die("Lỗi: Không tìm thấy file 'db.php'. Hãy kiểm tra lại thư mục config.");
}

// 3. [QUAN TRỌNG] Tải thủ công các Class Cha trước (để tránh lỗi 'Class not found')
if (file_exists('models/Database.php')) {
    require_once 'models/Database.php';
}
if (file_exists('controllers/Controller.php')) {
    require_once 'controllers/Controller.php';
} else {
    die("Lỗi: Không tìm thấy file 'controllers/Controller.php'");
}

// 4. Tự động tải các file còn lại (Model con, Controller con)
spl_autoload_register(function ($className) {
    if (file_exists('controllers/' . $className . '.php')) {
        require_once 'controllers/' . $className . '.php';
    } elseif (file_exists('models/' . $className . '.php')) {
        require_once 'models/' . $className . '.php';
    }
});

/**
 * --- PHÂN TÍCH URL & ĐIỀU HƯỚNG ---
 */

// Lấy tham số 'url' từ .htaccess
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$urlParts = explode('/', $url);

// A. Xác định Controller
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'ProductController';

// B. Xác định Action
if (isset($urlParts[1]) && $urlParts[1] != '') {
    $actionName = $urlParts[1];
} else {
    // Action mặc định
    if ($controllerName == 'CartController') $actionName = 'view';
    elseif ($controllerName == 'AuthController') $actionName = 'login';
    else $actionName = 'list';
}

// C. Tham số
$params = array_slice($urlParts, 2);

/**
 * --- KHỞI CHẠY ---
 */
$found = false;

// Kiểm tra file và class tồn tại
if (file_exists('controllers/' . $controllerName . '.php')) {
    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $actionName)) {
            $found = true;
            try {
                call_user_func_array([$controller, $actionName], $params);
            } catch (Exception $e) {
                // Nếu class Controller đã tải, ta có thể dùng hàm error404
                if (method_exists($controller, 'error404')) {
                    $controller->error404();
                } else {
                    echo "Lỗi hệ thống: " . $e->getMessage();
                }
            }
        }
    }
}

// Nếu không tìm thấy trang -> Gọi 404
if (!$found) {
    // Vì ta đã require Controller.php ở trên, nên ta có thể tạo nhanh ProductController để gọi 404
    if (class_exists('ProductController')) {
        $fallback = new ProductController();
        $fallback->error404();
    } else {
        // Fallback cuối cùng nếu mọi thứ đều lỗi
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Không tìm thấy trang</h1>";
        echo "<p>Đường dẫn không hợp lệ.</p>";
    }
}
?>