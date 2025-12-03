<?php
// /controllers/OrderController.php

class OrderController extends Controller {

    private $orderModel;

    public function __construct() {
        $this->checkAuth(); // Bắt buộc đăng nhập
        $this->orderModel = $this->loadModel('Order');
    }

    /**
     * Hiển thị trang Lịch sử mua hàng
     * URL: /order/history
     */
    public function history() {
        $user_id = $_SESSION['user']['id'];
        
        // Lấy tất cả đơn hàng của user
        $orders = $this->orderModel->getOrdersByUserId($user_id);

        $data = [
            'title' => 'Lịch sử mua hàng',
            'orders' => $orders
        ];
        
        $this->loadView('order/history', $data);
    }

    /**
     * Hiển thị chi tiết 1 đơn hàng
     * URL: /order/detail/5 (ví dụ)
     */
    public function detail($order_id = 0) {
        $order_id = (int)$order_id;
        $user_id = $_SESSION['user']['id'];
        
        // Lấy thông tin chung của đơn hàng (để kiểm tra đúng chủ)
        $order_info = $this->orderModel->getOrderById($order_id, $user_id);
        
        // Nếu không tìm thấy đơn hoặc không đúng chủ
        if (!$order_info) {
            $this->redirect('order/history');
            return;
        }
        
        // Lấy các sản phẩm chi tiết của đơn
        $order_details = $this->orderModel->getOrderDetails($order_id);
        
        $data = [
            'title' => 'Chi tiết đơn hàng #' . $order_info['id'],
            'order' => $order_info,
            'details' => $order_details
        ];
        
        $this->loadView('order/detail', $data);
    }
}
?>