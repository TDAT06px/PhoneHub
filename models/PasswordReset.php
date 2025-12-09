<?php
// /models/PasswordReset.php

class PasswordReset extends Database {

    // Tạo bảng nếu chưa tồn tại (để tiện cho môi trường dev)
    private static function ensureTable() {
        $sql = "CREATE TABLE IF NOT EXISTS password_resets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL,
            token VARCHAR(255) NOT NULL,
            expires_at DATETIME NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX (token),
            INDEX (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        try {
            self::execute($sql, []);
        } catch (Exception $e) {
            // ignore errors for environments where privileges are limited
        }
    }

    public function createToken($email, $ttlMinutes = 60) {
        self::ensureTable();
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', time() + $ttlMinutes * 60);
        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)";
        $ok = self::execute($sql, [
            ':email' => $email,
            ':token' => $token,
            ':expires_at' => $expires
        ]);
        return $ok ? $token : false;
    }

    public function findByToken($token) {
        self::ensureTable();
        $sql = "SELECT * FROM password_resets WHERE token = :token LIMIT 1";
        $row = self::query($sql, [':token' => $token], false);
        if (!$row) return false;
        if (strtotime($row['expires_at']) < time()) return false;
        return $row;
    }

    public function deleteByToken($token) {
        self::ensureTable();
        $sql = "DELETE FROM password_resets WHERE token = :token";
        return self::execute($sql, [':token' => $token]);
    }
}
