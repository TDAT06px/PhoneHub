<?php
// /models/Cart.php

class Cart {

    public function __construct() {
        // Đảm bảo session đã được khởi tạo
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Khởi tạo giỏ hàng nếu chưa có
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    /**
     * Thêm sản phẩm vào giỏ
     * @param int $id ID sản phẩm
     * @param int $qty Số lượng
     */
    public function add($id, $qty = 1) {
        $id = (int)$id;
        $qty = (int)$qty;
        
        if (isset($_SESSION['cart'][$id])) {
            // Nếu đã có, tăng số lượng
            $_SESSION['cart'][$id] += $qty;
        } else {
            // Nếu chưa có, thêm mới
            $_SESSION['cart'][$id] = $qty;
        }
    }

    /**
     * Cập nhật số lượng sản phẩm
     * @param int $id ID sản phẩm
     * @param int $qty Số lượng mới (nếu = 0 thì xóa)
     */
    public function update($id, $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        
        if ($qty <= 0) {
            $this->remove($id);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }

    /**
     * Xóa 1 sản phẩm khỏi giỏ
     * @param int $id ID sản phẩm
     */
    public function remove($id) {
        $id = (int)$id;
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear() {
        $_SESSION['cart'] = [];
    }

    /**
     * Lấy toàn bộ sản phẩm trong giỏ (chỉ ID và số lượng)
     * @return array Ví dụ: [ '5' => 2, '1' => 1 ]
     */
    public function getContents() {
        return $_SESSION['cart'];
    }

    /**
     * Đếm tổng số lượng SẢN PHẨM (không phải số loại)
     * @return int
     */
    public function getTotalItems() {
        return array_sum($_SESSION['cart']);
    }
}
?>