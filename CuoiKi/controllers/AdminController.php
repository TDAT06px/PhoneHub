<?php
// controllers/AdminController.php

class AdminController extends Controller {

    public function __construct() {
        $this->checkAdmin(); // Bảo vệ trang này
    }

    public function dashboard() {
        // Tải các model
        $productModel = $this->loadModel('Product');
        $orderModel = $this->loadModel('Order');
        // $userModel = $this->loadModel('User'); // Nếu có model User thì mở ra

        // 1. Đếm tổng sản phẩm
        $total_products = $productModel->countAll();

        $db = new Database();
        $orders = $db->query("SELECT COUNT(*) as total FROM donhang", [], false);
        $total_orders = $orders['total'];

        // 3. Đếm tổng thành viên
        $users = $db->query("SELECT COUNT(*) as total FROM nguoidung WHERE role = 'user'", [], false);
        $total_users = $users['total'];

        $data = [
            'title' => 'Dashboard Quản Trị',
            'total_products' => $total_products,
            'total_orders' => $total_orders,
            'total_users' => $total_users
        ];

        $this->loadView('admin/dashboard', $data, 'auth'); 
    }
}