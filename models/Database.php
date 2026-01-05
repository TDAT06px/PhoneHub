<?php


class Database
{
    private static $conn = null;

    public static function getConnection()
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

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
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                die('Database Error: ' . $e->getMessage());
            }
            die('Database connection error. Please try again later.');
        }

        return self::$conn;
    }

    private static function _query($sql, $params = [], $fetchAll = true)
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);

            if ($fetchAll) {
                return $stmt->fetchAll();
            }

            return $stmt->fetch();
        } catch (PDOException $e) {
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                echo 'Query Error: ' . $e->getMessage();
            }

            return null;
        }
    }
    private static function _execute($sql, $params = [])
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                echo 'Execute Error: ' . $e->getMessage();
            }

            return false;
        }
    }

    public static function query($sql, $params = [], $fetchAll = true)
    {
        return self::_query($sql, $params, $fetchAll);
    }

    public static function execute($sql, $params = [])
    {
        return self::_execute($sql, $params);
    }
    public static function lastInsertId()
    {
        return self::getConnection()->lastInsertId();
    }
    public function __call($method, $args)
    {
        if (in_array($method, ['query', 'execute'])) {
            return call_user_func_array([self::class, $method], $args);
        }

        throw new BadMethodCallException("Method '$method' does not exist in class Database");
    }
}