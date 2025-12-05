<?php
// /controllers/CartController.php

class CartController extends Controller {

    private $cartModel;
    private $productModel;

    public function __construct() {
        // Tải các model cần thiết
        $this->checkAuth();
        $this->cartModel = $this->loadModel('Cart');
        $this->productModel = $this->loadModel('Product');
    }

    /**
     * Thêm sản phẩm vào giỏ
     */
    public function add($id = 0) {
        $id = (int)$id;
        if ($id > 0) {
            $this->cartModel->add($id, 1);
        }
        $this->redirect('cart/view');
    }

    /**
     * Xóa 1 sản phẩm
     */
    public function remove($id = 0) {
        $id = (int)$id;
        if ($id > 0) {
            $this->cartModel->remove($id);
        }
        $this->redirect('cart/view');
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear() {
        $this->cartModel->clear();
        $this->redirect('cart/view');
    }
    
    /**
     * Cập nhật số lượng (từ form giỏ hàng)
     */
    public function update() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['qty'])) {
            foreach ($_POST['qty'] as $id => $qty) {
                $this->cartModel->update($id, $qty);
            }
        }
        $this->redirect('cart/view');
    }

    /**
     * Hiển thị trang giỏ hàng
     */
    public function view() {
        $cartItems = $this->cartModel->getContents();

        if (empty($cartItems)) {
            $data = ['title' => 'Giỏ hàng trống'];
            $this->loadView('cart/empty', $data);
            return;
        }

        $ids = array_keys($cartItems);
        $productsInCart = $this->productModel->getByIds($ids);

        $total_price = 0;
        $detailed_cart = [];
        
        foreach ($productsInCart as $product) {
            $id = $product['id'];
            $qty = $cartItems[$id];
            $subtotal = $product['gia'] * $qty;
            $total_price += $subtotal;
            
            $detailed_cart[] = [
                'id' => $id,
                'hinhanh' => $product['hinhanh'],
                'ten_sanpham' => $product['ten_sanpham'],
                'gia' => $product['gia'],
                'qty' => $qty,
                'subtotal' => $subtotal
            ];
        }

        $data = [
            'title' => 'Giỏ hàng của bạn',
            'cart'  => $detailed_cart,
            'total_price' => $total_price
        ];

        $this->loadView('cart/view', $data);
    }

    /**
     * [CẬP NHẬT] Xử lý Đặt hàng & Phân loại thanh toán (COD/QR)
     * URL: /cart/checkout
     */
    public function checkout() {
        // Chỉ xử lý khi submit form POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('cart/view');
            return;
        }

        $user_id = $_SESSION['user']['id'];
        $payment_method = $_POST['payment_method'] ?? 'cod'; // Lấy phương thức thanh toán
        
        $cartItems = $this->cartModel->getContents();
        if (empty($cartItems)) {
            $this->redirect('cart/view');
            return;
        }

        // Tính toán lại tổng tiền để lưu vào DB
        $ids = array_keys($cartItems);
        $productsInCart = $this->productModel->getByIds($ids);
        $total_price = 0;
        $detailed_cart_for_order = [];
        
        foreach ($productsInCart as $product) {
            $id = $product['id'];
            $qty = $cartItems[$id];
            $total_price += $product['gia'] * $qty;
            
            $detailed_cart_for_order[] = [
                'id' => $id,
                'gia' => $product['gia'],
                'qty' => $qty
            ];
        }

        $orderModel = $this->loadModel('Order');
        
        // Tạo đơn hàng
        if ($orderModel->create($user_id, $detailed_cart_for_order, $total_price)) {
            
            // Lấy ID đơn hàng vừa tạo 
            // (Lấy đơn mới nhất của user này để làm ID tạm thời nếu hàm create không trả về ID)
            $latestOrders = $orderModel->getOrdersByUserId($user_id);
            $latestOrder = $latestOrders[0] ?? null; 
            $order_id = $latestOrder['id'] ?? 0;

            // Xóa giỏ hàng sau khi đặt thành công
            $this->cartModel->clear(); 

            // ĐIỀU HƯỚNG DỰA TRÊN PHƯƠNG THỨC THANH TOÁN
            if ($payment_method === 'qr') {
                // Nếu chọn QR -> Chuyển sang trang quét mã
                $this->redirect("cart/payment/$order_id");
            } else {
                // Nếu chọn COD -> Về trang lịch sử
                $this->redirect('order/history');
            }

        } else {
            echo "Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại.";
        }
    }

    /**
     * [MỚI] Hiển thị trang thanh toán QR
     * URL: /cart/payment/5
     */
    public function payment($order_id = 0) {
        $order_id = (int)$order_id;
        $this->checkAuth();
        
        $orderModel = $this->loadModel('Order');
        $user_id = $_SESSION['user']['id'];
        
        $orders = $orderModel->getOrdersByUserId($user_id);
        $order = null;
        foreach ($orders as $o) {
            if ($o['id'] == $order_id) {
                $order = $o;
                break;
            }
        }

        if (!$order) {
            $this->redirect('order/history'); // Không tìm thấy đơn thì đá về lịch sử
            return;
        }

        $this->loadView('cart/payment_qr', [
            'title' => 'Thanh toán đơn hàng #' . $order_id,
            'order' => $order
        ]);
    }

} 
?>