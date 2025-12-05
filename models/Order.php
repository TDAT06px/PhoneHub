<?php
// /models/Order.php

class Order extends Database {

    /**
     * (QUAN TRỌNG) Tạo đơn hàng mới từ giỏ hàng
     * Hàm này sử dụng Transaction để đảm bảo tính toàn vẹn dữ liệu
     * @param int $user_id ID người dùng
     * @param array $cart Giỏ hàng (lấy từ ProductModel)
     * @param float $total_price Tổng tiền
     * @return bool True nếu thành công, false nếu thất bại
     */
    public function create($user_id, $cart, $total_price) {
        // Lấy kết nối CSDL
        $conn = self::getConnection();
        
        try {
            // Bắt đầu Transaction
            $conn->beginTransaction();

            // 1. Thêm vào bảng `donhang`
            $sql_order = "INSERT INTO donhang (id_nguoidung, tong_tien) VALUES (:id_nguoidung, :tong_tien)";
            $stmt_order = $conn->prepare($sql_order);
            $stmt_order->execute([
                ':id_nguoidung' => $user_id,
                ':tong_tien'    => $total_price
            ]);
            
            // Lấy ID của đơn hàng vừa tạo
            $order_id = $conn->lastInsertId();

            // 2. Thêm vào bảng `donhang_chitiet`
            $sql_detail = "INSERT INTO donhang_chitiet (id_donhang, id_sanpham, so_luong, don_gia_luc_mua)
                           VALUES (:id_donhang, :id_sanpham, :so_luong, :don_gia_luc_mua)";
            $stmt_detail = $conn->prepare($sql_detail);

            foreach ($cart as $item) {
                $stmt_detail->execute([
                    ':id_donhang'       => $order_id,
                    ':id_sanpham'       => $item['id'],
                    ':so_luong'         => $item['qty'],
                    ':don_gia_luc_mua'  => $item['gia']
                ]);
            }
            
            // 3. Nếu mọi thứ thành công, commit transaction
            $conn->commit();
            return true;

        } catch (Exception $e) {
            // 4. Nếu có lỗi, rollback
            $conn->rollBack();
            if (DEBUG_MODE) {
                echo "Lỗi khi tạo đơn hàng: " . $e->getMessage();
            }
            return false;
        }
    }

    /**
     * Lấy tất cả đơn hàng của 1 người dùng
     * @param int $user_id
     * @return array
     */
    public function getOrdersByUserId($user_id) {
        $sql = "SELECT * FROM donhang 
                WHERE id_nguoidung = :user_id 
                ORDER BY ngay_tao DESC";
        return self::query($sql, [':user_id' => $user_id]);
    }
    
    /**
     * Lấy chi tiết 1 đơn hàng (kèm thông tin sản phẩm)
     * @param int $order_id
     * @return array
     */
    public function getOrderDetails($order_id) {
        $sql = "SELECT dc.*, sp.ten_sanpham, sp.hinhanh 
                FROM donhang_chitiet AS dc
                JOIN sanpham AS sp ON dc.id_sanpham = sp.id
                WHERE dc.id_donhang = :order_id";
        return self::query($sql, [':order_id' => $order_id]);
    }
    
    /**
     * Lấy thông tin 1 đơn hàng
     * @param int $order_id
     * @param int $user_id (Để bảo mật, đảm bảo đúng chủ)
     * @return mixed
     */
    public function getOrderById($order_id, $user_id) {
        $sql = "SELECT * FROM donhang 
                WHERE id = :order_id AND id_nguoidung = :user_id";
        return self::query($sql, [':order_id' => $order_id, ':user_id' => $user_id], false);
    }
}
?>