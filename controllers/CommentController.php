<?php
class CommentController extends Controller {

    private $commentModel;

    public function __construct() {
        $this->commentModel = $this->loadModel('Comment');
    }

    public function add() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('auth/login');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = (int)$_POST['id_sanpham'];
            $noi_dung = trim($_POST['noi_dung']);
            $danh_gia = (int)$_POST['danh_gia'];
          
            if (!empty($noi_dung) && $danh_gia >= 1 && $danh_gia <= 5) {
                
                $data = [
                    'id_sanpham' => $product_id,
                    'id_nguoidung' => $_SESSION['user']['id'],
                    'noi_dung' => $noi_dung,
                    'danh_gia' => $danh_gia
                ];

                $this->commentModel->create($data);
            }
            $this->redirect("product/detail/$product_id");
        } else {
            $this->redirect('');
        }
    }
}
?>