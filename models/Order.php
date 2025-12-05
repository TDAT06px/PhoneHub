<?php
// models/Order.php

class Order extends Database {

    /**
     * Tạo đơn hàng mới từ giỏ hàng
     */
    public function create($user_id, $cart, $total_price) {
        $conn = self::getConnection();
        try {
            $conn->beginTransaction();

            // 1. Thêm vào bảng donhang
            $sql_order = "INSERT INTO donhang (id_nguoidung, tong_tien, trang_thai) VALUES (:id_nguoidung, :tong_tien, 'Chờ xử lý')";
            $stmt_order = $conn->prepare($sql_order);
            $stmt_order->execute([
                ':id_nguoidung' => $user_id,
                ':tong_tien'    => $total_price
            ]);
            $order_id = $conn->lastInsertId();

            // 2. Thêm vào bảng donhang_chitiet
            $sql_detail = "INSERT INTO donhang_chitiet (id_donhang, id_sanpham, so_luong, don_gia_luc_mua) VALUES (:id_donhang, :id_sanpham, :so_luong, :don_gia)";
            $stmt_detail = $conn->prepare($sql_detail);

            // Hỗ trợ 2 dạng $cart:
            // - assoc: [product_id => qty, ...]
            // - list: [['id'=>..., 'qty'=>..., 'gia'=>...], ...]
            foreach ($cart as $key => $val) {
                if (is_array($val) && isset($val['id'])) {
                    $pid = (int)$val['id'];
                    $qty = (int)($val['qty'] ?? ($val['so_luong'] ?? 0));
                    $price = isset($val['gia']) ? $val['gia'] : null;
                } else {
                    // associative format
                    $pid = (int)$key;
                    $qty = (int)$val;
                    $price = null;
                }

                if ($pid <= 0 || $qty <= 0) continue;

                // Lấy thông tin giá hiện tại nếu không có
                $productModel = new Product();
                $product = $productModel->getById($pid);
                if (!$product) continue;

                $unitPrice = $price ?? $product['gia'];

                $stmt_detail->execute([
                    ':id_donhang' => $order_id,
                    ':id_sanpham' => $pid,
                    ':so_luong'   => $qty,
                    ':don_gia'    => $unitPrice
                ]);
                // Giảm tồn kho ngay khi tạo chi tiết đơn
                $upd = $conn->prepare("UPDATE sanpham SET so_luong_ton = GREATEST(so_luong_ton - :qty, 0) WHERE id = :pid");
                $upd->execute([':qty' => $qty, ':pid' => $pid]);
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }

    /**
     * Lấy tất cả đơn hàng của 1 người dùng (Cho User xem lịch sử)
     */
    public function getOrdersByUserId($user_id) {
        $sql = "SELECT * FROM donhang WHERE id_nguoidung = :user_id ORDER BY ngay_tao DESC";
        return self::query($sql, [':user_id' => $user_id]);
    }
    
    /**
     * Lấy chi tiết đơn hàng
     */
    public function getOrderDetails($order_id) {
        $sql = "SELECT dc.*, sp.ten_sanpham, sp.hinhanh 
                FROM donhang_chitiet AS dc
                JOIN sanpham AS sp ON dc.id_sanpham = sp.id
                WHERE dc.id_donhang = :order_id";
        return self::query($sql, [':order_id' => $order_id]);
    }
    
    /**
     * Lấy thông tin 1 đơn hàng cụ thể
     */
    public function getOrderById($order_id, $user_id = null) {
        if ($user_id) {
            $sql = "SELECT * FROM donhang WHERE id = :id AND id_nguoidung = :user_id";
            return self::query($sql, [':id' => $order_id, ':user_id' => $user_id], false);
        } else {
            $sql = "SELECT * FROM donhang WHERE id = :id";
            return self::query($sql, [':id' => $order_id], false);
        }
    }

    // --- CÁC HÀM DÀNH CHO ADMIN ---

    /**
     * [ADMIN] Lấy TẤT CẢ đơn hàng
     */
    public function getAll() {
        $sql = "SELECT dh.*, nd.ho_ten, nd.email 
                FROM donhang dh 
                JOIN nguoidung nd ON dh.id_nguoidung = nd.id 
                ORDER BY dh.ngay_tao DESC";
        return self::query($sql);
    }

    /**
     * [ADMIN] Cập nhật trạng thái đơn hàng
     */
    public function updateStatus($id, $status) {
        $conn = self::getConnection();
        try {
            $conn->beginTransaction();

            // Lấy trạng thái hiện tại của đơn
            $stmt = $conn->prepare("SELECT trang_thai FROM donhang WHERE id = :id FOR UPDATE");
            $stmt->execute([':id' => $id]);
            $current = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$current) {
                $conn->rollBack();
                return false;
            }
            $prevStatus = $current['trang_thai'];

            // Sau này ta giảm kho ngay khi tạo đơn (Order::create) để phản ánh tức thì.
            // Ở đây chỉ cần xử lý hoàn kho khi đơn bị hủy (restore), tránh giảm đôi.
            $shouldRestore = $status === 'Đã hủy' && $prevStatus !== 'Đã hủy';

            if ($shouldRestore) {
                // Lấy chi tiết đơn
                $details = self::query("SELECT id_sanpham, so_luong FROM donhang_chitiet WHERE id_donhang = :id", [':id' => $id]);
                if (!empty($details) && is_array($details)) {
                    $upd = $conn->prepare("UPDATE sanpham SET so_luong_ton = so_luong_ton + :qty WHERE id = :pid");
                    foreach ($details as $d) {
                        $upd->execute([':qty' => (int)$d['so_luong'], ':pid' => (int)$d['id_sanpham']]);
                    }
                }
            }

            // Cập nhật trạng thái đơn
            $stmtUpd = $conn->prepare("UPDATE donhang SET trang_thai = :status WHERE id = :id");
            $stmtUpd->execute([':status' => $status, ':id' => $id]);

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollBack();
            return false;
        }
    }
}
?>