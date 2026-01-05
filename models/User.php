<?php

class User extends Database {

    public function findByEmail($email) {
        $sql = "SELECT * FROM nguoidung WHERE email = :email";
        return self::query($sql, [':email' => $email], false);
    }

    public function getById($id) {
        $sql = "SELECT * FROM nguoidung WHERE id = :id";
        return self::query($sql, [':id' => $id], false);
    }


    public function create($data) {
        $hashed_password = password_hash($data['mat_khau'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO nguoidung (ho_ten, email, so_dien_thoai, mat_khau, gioi_tinh, ngay_sinh, role)
                VALUES (:ho_ten, :email, :so_dien_thoai, :mat_khau, :gioi_tinh, :ngay_sinh, :role)";
        
        $params = [
            ':ho_ten'        => $data['ho_ten'],
            ':email'         => $data['email'],
            ':so_dien_thoai' => $data['so_dien_thoai'] ?? null,
            ':mat_khau'      => $hashed_password,
            ':gioi_tinh'     => $data['gioi_tinh'] ?? 'Khác',
            ':ngay_sinh'     => $data['ngay_sinh'] ?? null,
            ':role'          => $data['role'] ?? 'user'
        ];

        return self::execute($sql, $params);
    }

    public function update($id, $data) {
        // Allow partial updates. Whitelist columns that may be updated.
        $allowed = ['ho_ten','email','so_dien_thoai','gioi_tinh','ngay_sinh','mat_khau','role','trang_thai'];
        $sets = [];
        $params = [];

        foreach ($allowed as $col) {
            if (array_key_exists($col, $data)) {
                $sets[] = "$col = :$col";
                $params[":$col"] = $data[$col];
            }
        }

        if (empty($sets)) return false;

        $sql = "UPDATE nguoidung SET " . implode(', ', $sets) . " WHERE id = :id";
        $params[':id'] = $id;

        return self::execute($sql, $params);
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM nguoidung WHERE email = :email";
        $user = self::query($sql, [':email' => $email], false);
        
        if ($user && password_verify($password, $user['mat_khau'])) {
            return $user;
        }
        return false;
    }

    public function getAllUsers($exclude_id) {
        $sql = "SELECT * FROM nguoidung WHERE id != :id ORDER BY id DESC";
        return self::query($sql, [':id' => $exclude_id]);
    }

    public function updateRoleAndStatus($id, $role, $status) {
        $sql = "UPDATE nguoidung SET role = :role, trang_thai = :status WHERE id = :id";
        return self::execute($sql, [
            ':role' => $role,
            ':status' => $status, 
            ':id' => $id
        ]);
    }
}
?>