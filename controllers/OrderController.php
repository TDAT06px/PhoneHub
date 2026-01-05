<?php

class OrderController extends Controller {

    private $orderModel;

    public function __construct() {
        $this->checkAuth(); // Bắt buộc đăng nhập
        $this->orderModel = $this->loadModel('Order');
    }

    public function history() {
        $user_id = $_SESSION['user']['id'];
        
        $orders = $this->orderModel->getOrdersByUserId($user_id);

        $data = [
            'title' => 'Lịch sử mua hàng',
            'orders' => $orders
        ];
        
        $this->loadView('order/history', $data);
    }
    public function detail($order_id = 0) {
        $order_id = (int)$order_id;
        $user_id = $_SESSION['user']['id'];
        
        $order_info = $this->orderModel->getOrderById($order_id, $user_id);
        
        if (!$order_info) {
            $this->redirect('order/history');
            return;
        }
        
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