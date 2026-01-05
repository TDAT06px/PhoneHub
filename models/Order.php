<?php

class Order extends Database {

    public function create($user_id, $cart, $total_price) {
        $conn = self::getConnection();
        try {
            $conn->beginTransaction();

            $sql_order = "INSERT INTO donhang (id_nguoidung, tong_tien, trang_thai) VALUES (:id_nguoidung, :tong_tien, 'Chờ xử lý')";
            $stmt_order = $conn->prepare($sql_order);
            $stmt_order->execute([
                ':id_nguoidung' => $user_id,
                ':tong_tien'    => $total_price
            ]);
            $order_id = $conn->lastInsertId();

            $sql_detail = "INSERT INTO donhang_chitiet (id_donhang, id_sanpham, so_luong, don_gia_luc_mua) VALUES (:id_donhang, :id_sanpham, :so_luong, :don_gia)";
            $stmt_detail = $conn->prepare($sql_detail);

            foreach ($cart as $key => $val) {
                if (is_array($val) && isset($val['id'])) {
                    $pid = (int)$val['id'];
                    $qty = (int)($val['qty'] ?? ($val['so_luong'] ?? 0));
                    $price = isset($val['gia']) ? $val['gia'] : null;
                } else {
                    $pid = (int)$key;
                    $qty = (int)$val;
                    $price = null;
                }

                if ($pid <= 0 || $qty <= 0) continue;

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

    public function getOrdersByUserId($user_id) {
        $sql = "SELECT * FROM donhang WHERE id_nguoidung = :user_id ORDER BY ngay_tao DESC";
        return self::query($sql, [':user_id' => $user_id]);
    }
    

    public function getOrderDetails($order_id) {
        $sql = "SELECT dc.*, sp.ten_sanpham, sp.hinhanh 
                FROM donhang_chitiet AS dc
                JOIN sanpham AS sp ON dc.id_sanpham = sp.id
                WHERE dc.id_donhang = :order_id";
        return self::query($sql, [':order_id' => $order_id]);
    }
    

    public function getOrderById($order_id, $user_id = null) {
        if ($user_id) {
            $sql = "SELECT * FROM donhang WHERE id = :id AND id_nguoidung = :user_id";
            return self::query($sql, [':id' => $order_id, ':user_id' => $user_id], false);
        } else {
            $sql = "SELECT * FROM donhang WHERE id = :id";
            return self::query($sql, [':id' => $order_id], false);
        }
    }

    public function getAll() {
        $sql = "SELECT dh.*, nd.ho_ten, nd.email 
                FROM donhang dh 
                JOIN nguoidung nd ON dh.id_nguoidung = nd.id 
                ORDER BY dh.ngay_tao DESC";
        return self::query($sql);
    }


    public function updateStatus($id, $status) {
        $conn = self::getConnection();
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("SELECT trang_thai FROM donhang WHERE id = :id FOR UPDATE");
            $stmt->execute([':id' => $id]);
            $current = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$current) {
                $conn->rollBack();
                return false;
            }
            $prevStatus = $current['trang_thai'];

            $shouldRestore = $status === 'Đã hủy' && $prevStatus !== 'Đã hủy';

            if ($shouldRestore) {
                $details = self::query("SELECT id_sanpham, so_luong FROM donhang_chitiet WHERE id_donhang = :id", [':id' => $id]);
                if (!empty($details) && is_array($details)) {
                    $upd = $conn->prepare("UPDATE sanpham SET so_luong_ton = so_luong_ton + :qty WHERE id = :pid");
                    foreach ($details as $d) {
                        $upd->execute([':qty' => (int)$d['so_luong'], ':pid' => (int)$d['id_sanpham']]);
                    }
                }
            }

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