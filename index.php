<?php

session_start();

if (file_exists('config/db.php')) {
    require_once 'config/db.php';
} elseif (file_exists('db.php')) {
    require_once 'db.php';
} else {
    die("Lỗi: Không tìm thấy file 'db.php'. Hãy kiểm tra lại thư mục config.");
}

if (file_exists('models/Database.php')) {
    require_once 'models/Database.php';
}
if (file_exists('controllers/Controller.php')) {
    require_once 'controllers/Controller.php';
} else {
    die("Lỗi: Không tìm thấy file 'controllers/Controller.php'");
}

spl_autoload_register(function ($className) {
    if (file_exists('controllers/' . $className . '.php')) {
        require_once 'controllers/' . $className . '.php';
    } elseif (file_exists('models/' . $className . '.php')) {
        require_once 'models/' . $className . '.php';
    }
});

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$urlParts = explode('/', $url);

if (!empty($urlParts[0]) && $urlParts[0] === 'admin') {
    $controllerName = 'AdminController';
    $actionName = isset($urlParts[1]) && !empty($urlParts[1]) ? $urlParts[1] : 'dashboard';
} else {
    $controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'ProductController';
    
    if (isset($urlParts[1]) && $urlParts[1] != '') {
        $actionName = $urlParts[1];
    } else {
        if ($controllerName == 'CartController') $actionName = 'view';
        elseif ($controllerName == 'AuthController') $actionName = 'login';
        else $actionName = 'list';
    }
}

$params = array_slice($urlParts, 2);

$found = false;

if (file_exists('controllers/' . $controllerName . '.php')) {
    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $actionName)) {
            $found = true;
            try {
                call_user_func_array([$controller, $actionName], $params);
            } catch (Exception $e) {
                if (method_exists($controller, 'error404')) {
                    $controller->error404();
                } else {
                    echo "Lỗi hệ thống: " . $e->getMessage();
                }
            }
        }
    }
}

if (!$found) {
    if (class_exists('ProductController')) {
        $fallback = new ProductController();
        $fallback->error404();
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 - Không tìm thấy trang</h1>";
        echo "<p>Đường dẫn không hợp lệ.</p>";
    }
}
?>