<?php

class Cart {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add($id, $qty = 1) {
        $id = (int)$id;
        $qty = (int)$qty;
        
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] += $qty;
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }

    public function update($id, $qty) {
        $id = (int)$id;
        $qty = (int)$qty;
        
        if ($qty <= 0) {
            $this->remove($id);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }

    public function remove($id) {
        $id = (int)$id;
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }

    public function clear() {
        $_SESSION['cart'] = [];
    }

    public function getContents() {
        return $_SESSION['cart'];
    }

    public function getTotalItems() {
        return array_sum($_SESSION['cart']);
    }
}
?>