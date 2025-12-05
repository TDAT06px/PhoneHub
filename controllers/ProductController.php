<?php
// controllers/ProductController.php

class ProductController extends Controller {

    private $productModel;

    public function __construct() {
        $this->productModel = $this->loadModel('Product');
    }

    // Xem danh sách sản phẩm
    public function list() {
        $filters = [];
        if (!empty($_GET['category'])) { $filters['category_id'] = (int)$_GET['category']; }
        if (!empty($_GET['min_price'])) { $filters['min_price'] = (float)$_GET['min_price']; }
        if (!empty($_GET['max_price'])) { $filters['max_price'] = (float)$_GET['max_price']; }
        if (!empty($_GET['keyword'])) { $filters['keyword'] = trim($_GET['keyword']); }
        // Xử lý rating theo khoảng
        if (!empty($_GET['rating_min']) && !empty($_GET['rating_max'])) {
            $filters['rating'] = [
                'min' => (float)$_GET['rating_min'],
                'max' => (float)$_GET['rating_max']
            ];
        }
        $filters['sort'] = $_GET['sort'] ?? 'new';

        $limit = 12; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $total_products = $this->productModel->countAll($filters);
        $total_pages = ceil($total_products / $limit);
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->getAll($limit, $offset, $filters);
        $categoryModel = $this->loadModel('Category');
        
        $this->loadView('product/list', [
            'title' => 'Danh sách sản phẩm',
            'products' => $products,
            'categories' => $categoryModel->getAll(),
            'total_pages' => $total_pages,
            'page' => $page
        ]);
    }

    // --- [HÀM CẦN SỬA LÀ HÀM NÀY] ---
    public function detail($id = 0) {
        $id = (int)$id;
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            $this->error404();
        }

        $categoryModel = $this->loadModel('Category');
        $relatedProducts = $this->productModel->getRelated($product['id_danhmuc'], $id);
        
        // Lấy danh sách bình luận
        $commentModel = $this->loadModel('Comment');
        $comments = $commentModel->getByProductId($id);

        // --- [ĐOẠN CODE MỚI THÊM ĐỂ SỬA LỖI] ---
        // Tính toán thống kê sao (5 sao, 4 sao...)
        $total_comments = count($comments);
        $rating_counts = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];

        foreach ($comments as $cmt) {
            $star = (int)$cmt['danh_gia'];
            if (isset($rating_counts[$star])) {
                $rating_counts[$star]++;
            }
        }
        // --- [KẾT THÚC ĐOẠN MỚI] ---

        $this->loadView('product/detail', [
            'title' => $product['ten_sanpham'],
            'product' => $product,
            'related_products' => $relatedProducts,
            'comments' => $comments,
            
            // Truyền thêm 2 biến này sang View để hết lỗi
            'total_comments' => $total_comments, 
            'rating_counts' => $rating_counts    
        ]);
    }

    // --- CÁC HÀM ADMIN GIỮ NGUYÊN ---

    public function add() {
        $this->checkAdmin(); 
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_file_anh = "";
            if (isset($_FILES["hinhanh"]) && $_FILES["hinhanh"]["error"] == 0) {
                $target_dir = "assets/images/";
                $target_filename = time() . "_" . basename($_FILES["hinhanh"]["name"]);
                $target_file_path = $target_dir . $target_filename;
                
                if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file_path)) {
                    $ten_file_anh = $target_filename;
                }
            }

            $data = [
                'ten_sanpham' => $_POST['ten_sanpham'],
                'gia' => $_POST['gia'],
                'hinhanh' => $ten_file_anh,
                'id_danhmuc' => $_POST['id_danhmuc'],
                'mo_ta' => $_POST['mo_ta'],
                'thong_so_ky_thuat' => $_POST['thong_so_ky_thuat'],
                'so_luong_ton' => $_POST['so_luong_ton']
            ];

            if ($this->productModel->create($data)) {
                $this->redirect('product/list');
            } else {
                echo "Lỗi thêm sản phẩm.";
            }
        } else {
            $categoryModel = $this->loadModel('Category');
            $this->loadView('product/add', [
                'title' => 'Thêm sản phẩm',
                'categories' => $categoryModel->getAll()
            ]);
        }
    }

    public function edit($id = 0) {
        $this->checkAdmin();
        
        $id = (int)$id;
        $currentProduct = $this->productModel->getById($id);
        if (!$currentProduct) { $this->redirect('product/list'); }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_file_anh = $currentProduct['hinhanh']; 
            
            if (isset($_FILES["hinhanh"]) && $_FILES["hinhanh"]["error"] == 0) {
                $target_dir = "assets/images/";
                $target_filename = time() . "_" . basename($_FILES["hinhanh"]["name"]);
                $target_file_path = $target_dir . $target_filename;
                
                if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file_path)) {
                    $ten_file_anh = $target_filename;
                }
            }

            $data = [
                'ten_sanpham' => $_POST['ten_sanpham'],
                'gia' => $_POST['gia'],
                'hinhanh' => $ten_file_anh,
                'id_danhmuc' => $_POST['id_danhmuc'],
                'mo_ta' => $_POST['mo_ta'],
                'thong_so_ky_thuat' => $_POST['thong_so_ky_thuat'],
                'so_luong_ton' => $_POST['so_luong_ton']
            ];

            if ($this->productModel->update($id, $data)) {
                $this->redirect('product/list');
            } else {
                echo "Lỗi update.";
            }
        } else {
            $categoryModel = $this->loadModel('Category');
            $this->loadView('product/edit', [
                'title' => 'Sửa sản phẩm',
                'product' => $currentProduct,
                'categories' => $categoryModel->getAll()
            ]);
        }
    }

    public function delete($id = 0) {
        $this->checkAdmin(); 
        $id = (int)$id;
        if ($id > 0) {
            $this->productModel->delete($id);
        }
        $this->redirect('product/list');
    }
    
    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productModel->searchByName($keyword);
        
        $this->loadView('product/search_results', [
            'title' => 'Tìm kiếm: ' . $keyword,
            'products' => $products,
            'keyword' => $keyword
        ]);
    }
}
?>