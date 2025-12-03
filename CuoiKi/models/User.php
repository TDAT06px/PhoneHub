<?php
// /models/User.php

class User extends Database {

    /**
     * Tìm người dùng bằng email
     */
    public function findByEmail($email) {
        $sql = "SELECT * FROM nguoidung WHERE email = :email";
        return self::query($sql, [':email' => $email], false);
    }

    /**
     * Lấy thông tin người dùng theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM nguoidung WHERE id = :id";
        return self::query($sql, [':id' => $id], false);
    }

    /**
     * Tạo người dùng mới (Đăng ký)
     */
    public function create($data) {
        // Băm mật khẩu
        $hashed_password = password_hash($data['mat_khau'], PASSWORD_DEFAULT);

        $sql = "INSERT INTO nguoidung (ho_ten, email, so_dien_thoai, mat_khau, gioi_tinh, ngay_sinh, role)
                VALUES (:ho_ten, :email, :so_dien_thoai, :mat_khau, :gioi_tinh, :ngay_sinh, :role)";
        
        $params = [
            ':ho_ten'        => $data['ho_ten'],
            ':email'         => $data['email'],
            ':so_dien_thoai' => $data['so_dien_thoai'] ?? null, // Thêm ?? null để tránh lỗi nếu thiếu
            ':mat_khau'      => $hashed_password,
            ':gioi_tinh'     => $data['gioi_tinh'] ?? 'Khác',
            ':ngay_sinh'     => $data['ngay_sinh'] ?? null,
            ':role'          => $data['role'] ?? 'user'
        ];

        return self::execute($sql, $params);
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function update($id, $data) {
        $sql = "UPDATE nguoidung 
                SET ho_ten = :ho_ten, 
                    email = :email, 
                    so_dien_thoai = :so_dien_thoai, 
                    gioi_tinh = :gioi_tinh, 
                    ngay_sinh = :ngay_sinh 
                WHERE id = :id";
        
        $params = [
            ':ho_ten'        => $data['ho_ten'],
            ':email'         => $data['email'],
            ':so_dien_thoai' => $data['so_dien_thoai'] ?? null,
            ':gioi_tinh'     => $data['gioi_tinh'] ?? 'Khác',
            ':ngay_sinh'     => $data['ngay_sinh'] ?? null,
            ':id'            => $id
        ];
        
        return self::execute($sql, $params);
    }

    /**
     * Kiểm tra đăng nhập
     */
    public function login($email, $password) {
        $user = $this->findByEmail($email);

        if ($user) {
            // So sánh mật khẩu nhập vào với mật khẩu đã mã hóa trong DB
            if (password_verify($password, $user['mat_khau'])) {
                return $user;
            }
        }
        return false;
    }
}
?>