<?php

class Database {
    private static $conn = null;

    public static function getConnection() {
        if (self::$conn === null) {
            $host = DB_HOST;
            $db_name = DB_NAME;
            $username = DB_USER;
            $password = DB_PASS;
            $charset = DB_CHARSET;

            $dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
            
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$conn = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("Lỗi kết nối CSDL: " . $e->getMessage());
            }
        }
        
        return self::$conn;
    }

    /**
     * Logic thực thi query (private để tránh conflict)
     * @param string $sql Câu lệnh SQL
     * @param array $params Các tham số (để chống SQL Injection)
     * @param bool $fetchAll true để lấy tất cả, false để lấy 1 hàng
     * @return mixed
     */
    private static function _query($sql, $params = [], $fetchAll = true) {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            
            if ($fetchAll) {
                return $stmt->fetchAll();
            }
            return $stmt->fetch();
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                echo "Lỗi truy vấn: " . $e->getMessage();
            }
            return null;
        }
    }
    
    /**
     * Logic thực thi execute (private để tránh conflict)
     * @param string $sql Câu lệnh SQL
     * @param array $params Các tham số
     * @return bool
     */
    private static function _execute($sql, $params = []) {
        try {
            $stmt = self::getConnection()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                echo "Lỗi thực thi: " . $e->getMessage();
            }
            return false;
        }
    }

    /**
     * Phương thức trợ giúp để thực thi câu lệnh SQL (SELECT) - Static
     * @param string $sql Câu lệnh SQL
     * @param array $params Các tham số (để chống SQL Injection)
     * @param bool $fetchAll true để lấy tất cả, false để lấy 1 hàng
     * @return mixed
     */
    public static function query($sql, $params = [], $fetchAll = true) {
        return self::_query($sql, $params, $fetchAll);
    }
    
    /**
     * Phương thức trợ giúp để thực thi (INSERT, UPDATE, DELETE) - Static
     * @param string $sql Câu lệnh SQL
     * @param array $params Các tham số
     * @return bool
     */
    public static function execute($sql, $params = []) {
        return self::_execute($sql, $params);
    }

    /**
     * Lấy ID của bản ghi cuối cùng vừa INSERT
     * @return string
     */
    public static function lastInsertId() {
        return self::getConnection()->lastInsertId();
    }

    public function __call($method, $args) {
        if (in_array($method, ['query', 'execute'])) {
            return call_user_func_array([self::class, $method], $args);
        }
        throw new BadMethodCallException("Method $method không tồn tại trong class Database");
    }
}
?>