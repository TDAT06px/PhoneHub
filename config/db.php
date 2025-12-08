<?php
// File: db.php

// 1. Cấu hình Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '12345');      // Pass của XAMPP thường để trống
define('DB_NAME', 'DoAnCoSo2'); // Đảm bảo đúng tên CSDL của bạn
define('DB_CHARSET', 'utf8mb4');

// 2. Cấu hình Đường dẫn (URL)
// Hãy sửa 'CuoiKi' thành tên thư mục dự án của bạn nếu khác
define('BASE_URL', 'http://localhost/CuoiKi'); 

define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}
?>