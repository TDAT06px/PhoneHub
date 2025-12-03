<?php


define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '12345'); 
define('DB_NAME', 'DoAnCoSo2');
define('DB_CHARSET', 'utf8mb4');

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