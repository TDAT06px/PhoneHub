<?php
// controllers/CommentController.php

class CommentController extends Controller {

    private $commentModel;

    public function __construct() {
        $this->commentModel = $this->loadModel('Comment');
    }

    public function add() {
        // 1. Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            // Lưu lại trang hiện tại để login xong quay lại (nếu muốn làm nâng cao)
            $this->redirect('auth/login');
        }

        // 2. Chỉ xử lý POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = (int)$_POST['id_sanpham'];
            $noi_dung = trim($_POST['noi_dung']);
            $danh_gia = (int)$_POST['danh_gia'];
            
            // Validate cơ bản
            if (!empty($noi_dung) && $danh_gia >= 1 && $danh_gia <= 5) {
                
                $data = [
                    'id_sanpham' => $product_id,
                    'id_nguoidung' => $_SESSION['user']['id'],
                    'noi_dung' => $noi_dung,
                    'danh_gia' => $danh_gia
                ];

                // Gọi Model để lưu (Model này đã tự update avg_rating cho sản phẩm)
                $this->commentModel->create($data);
            }
            
            // 3. Quay lại trang chi tiết sản phẩm
            $this->redirect("product/detail/$product_id");
        } else {
            $this->redirect('');
        }
    }
}
?>