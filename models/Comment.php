<?php
// /models/Comment.php

class Comment extends Database {

    /**
     * Lấy tất cả bình luận cho một sản phẩm (kèm tên người dùng)
     */
    public function getByProductId($product_id) {
        $sql = "SELECT bl.*, nd.ho_ten 
                FROM binhluan AS bl
                JOIN nguoidung AS nd ON bl.id_nguoidung = nd.id
                WHERE bl.id_sanpham = :product_id
                ORDER BY bl.ngay_tao DESC";
        
        $params = [':product_id' => (int)$product_id];
        return self::query($sql, $params, true); 
    }

    /**
     * [QUAN TRỌNG] Tạo bình luận mới VÀ CẬP NHẬT ĐIỂM TRUNG BÌNH
     * @param array $data Dữ liệu bình luận
     * @return bool True nếu thành công
     */
    public function create($data) {
        $conn = self::getConnection();
        
        try {
            // Bắt đầu giao dịch (Transaction) để đảm bảo dữ liệu nhất quán
            $conn->beginTransaction();

            // 1. Thêm bình luận mới vào bảng `binhluan`
            $sql_insert = "INSERT INTO binhluan (id_sanpham, id_nguoidung, noi_dung, danh_gia) 
                           VALUES (:id_sanpham, :id_nguoidung, :noi_dung, :danh_gia)";
            
            $params_insert = [
                ':id_sanpham'   => (int)$data['id_sanpham'],
                ':id_nguoidung' => (int)$data['id_nguoidung'],
                ':noi_dung'     => $data['noi_dung'],
                ':danh_gia'     => (int)$data['danh_gia']
            ];
            
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->execute($params_insert);

            // 2. Tính lại trung bình cộng và Cập nhật ngay vào bảng `sanpham`
            // Lệnh này sẽ lấy trung bình cộng tất cả bình luận của sản phẩm đó và update vào cột avg_rating
            $sql_update_rating = "
                UPDATE sanpham
                SET avg_rating = (
                    SELECT COALESCE(AVG(danh_gia), 0)
                    FROM binhluan 
                    WHERE id_sanpham = :product_id
                )
                WHERE id = :product_id_main
            ";
            
            // Truyền tham số (dùng 2 tên khác nhau để tránh lỗi PDO ở một số phiên bản)
            $params_update = [
                ':product_id'      => (int)$data['id_sanpham'],
                ':product_id_main' => (int)$data['id_sanpham']
            ];

            $stmt_update = $conn->prepare($sql_update_rating);
            $stmt_update->execute($params_update);

            // 3. Nếu mọi thứ thành công, lưu lại (Commit)
            $conn->commit();
            return true;

        } catch (Exception $e) {
            // 4. Nếu có lỗi, hoàn tác (Rollback)
            $conn->rollBack();
            // Nếu đang bật chế độ debug thì hiện lỗi
            if (defined('DEBUG_MODE') && DEBUG_MODE) {
                echo "Lỗi Model Comment: " . $e->getMessage();
            }
            return false;
        }
    }
}
?>