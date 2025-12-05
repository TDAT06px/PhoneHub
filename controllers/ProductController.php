<?php

class ProductController extends Controller {

    private $productModel;

    public function __construct() {
       
        $this->productModel = $this->loadModel('Product');
    }

    /**
     * Hiển thị danh sách sản phẩm (Trang chủ/Shop)
     */
    public function list() {
        $filters = [];
        
        // 1. Lấy tham số lọc
        if (!empty($_GET['category'])) { $filters['category_id'] = (int)$_GET['category']; }
        if (!empty($_GET['min_price'])) { $filters['min_price'] = (float)$_GET['min_price']; }
        if (!empty($_GET['max_price'])) { $filters['max_price'] = (float)$_GET['max_price']; }
        if (!empty($_GET['rating'])) { $filters['rating'] = (int)$_GET['rating']; }
        if (!empty($_GET['keyword'])) { $filters['keyword'] = trim($_GET['keyword']); }
        
        // [MỚI] Lấy tham số sắp xếp (Sort)
        $filters['sort'] = $_GET['sort'] ?? 'new';

        // 2. Phân trang
        $limit = 12; // Số lượng SP mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $total_products = $this->productModel->countAll($filters);
        $total_pages = ceil($total_products / $limit);
        if ($page > $total_pages && $total_pages > 0) $page = $total_pages;
        
        $offset = ($page - 1) * $limit;
        
        // 3. Gọi Model
        $products = $this->productModel->getAll($limit, $offset, $filters);
        $categoryModel = $this->loadModel('Category');
        
        // Sử dụng getAll() thay vì getCategoryTree() nếu view chưa hỗ trợ đệ quy
        // Hoặc giữ getCategoryTree() nếu view list.php đã xử lý
        $categories = $categoryModel->getAll(); 

        $data = [
            'title' => 'Danh sách sản phẩm',
            'products' => $products,
            'page' => $page,
            'total_pages' => $total_pages,
            'total_products' => $total_products,
            'categories' => $categories,
            'filters' => $filters
        ];
        
        $this->loadView('product/list', $data);
    }

    /**
     * Xem chi tiết sản phẩm
     */
    public function detail($id = 0) {
        $id = (int)$id;
        $product = $this->productModel->getById($id);
        
        if (!$product) {
            $this->error404(); 
            return;
        }

        $categoryModel = $this->loadModel('Category');
        $categories = $categoryModel->getAll();
        
        // Lấy bình luận
        $commentModel = $this->loadModel('Comment');
        $all_comments = $commentModel->getByProductId($id);

        // Tính toán thống kê sao
        $rating_counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($all_comments as $cmt) {
            if (isset($rating_counts[$cmt['danh_gia']])) {
                $rating_counts[$cmt['danh_gia']]++;
            }
        }

        // Lọc bình luận theo sao (nếu có chọn filter)
        $rating_filter = isset($_GET['rating_filter']) ? (int)$_GET['rating_filter'] : 0;
        $display_comments = $all_comments;
        
        if ($rating_filter > 0 && $rating_filter <= 5) {
            $display_comments = array_filter($all_comments, function($cmt) use ($rating_filter) {
                return $cmt['danh_gia'] == $rating_filter;
            });
        }

        // Sản phẩm liên quan
        $related_products = $this->productModel->getRelated($product['id_danhmuc'], $id);

        $this->loadView('product/detail', [
            'title' => $product['ten_sanpham'],
            'product' => $product,
            'categories' => $categories,
            'comments' => $display_comments,
            'total_comments' => count($all_comments),
            'rating_counts' => $rating_counts,
            'current_filter' => $rating_filter,
            'related_products' => $related_products
        ]);
    }

    /**
     * Thêm sản phẩm (Chỉ Admin)
     */
    public function add() {
        $this->checkAdmin(); // Bắt buộc là Admin
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_file_anh = ''; 
            
            // Xử lý Upload Ảnh
            if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
                $target_dir = "assets/images/";
                // Tạo thư mục nếu chưa có
                if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

                $original_filename = basename($_FILES["hinhanh"]["name"]);
                $target_filename = time() . "_" . $original_filename;
                $target_file_path = $target_dir . $target_filename;
                
                $imageFileType = strtolower(pathinfo($target_filename, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($imageFileType, $allowed_types)) {
                    if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file_path)) {
                        $ten_file_anh = $target_filename;
                    } else {
                        echo "<script>alert('Lỗi: Không thể lưu ảnh.');</script>";
                    }
                } else {
                    echo "<script>alert('Lỗi: Sai định dạng ảnh.');</script>";
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
            }
        }
        
        $categoryModel = $this->loadModel('Category');
        $this->loadView('product/add', [
            'title' => 'Thêm sản phẩm',
            'categories' => $categoryModel->getAll()
        ]);
    }
    
    /**
     * Sửa sản phẩm (Chỉ Admin)
     */
    public function edit($id = 0) {
        $this->checkAdmin(); // Bắt buộc là Admin
        $id = (int)$id;

        $currentProduct = $this->productModel->getById($id);
        if (!$currentProduct) { $this->error404(); return; }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_file_anh = $currentProduct['hinhanh'];

            // Nếu có chọn ảnh mới thì xử lý
            if (isset($_FILES['hinhanh']) && $_FILES['hinhanh']['error'] == 0) {
                $target_dir = "assets/images/";
                $target_filename = time() . "_" . basename($_FILES["hinhanh"]["name"]);
                $target_file_path = $target_dir . $target_filename;
                $imageFileType = strtolower(pathinfo($target_filename, PATHINFO_EXTENSION));
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (in_array($imageFileType, $allowed_types)) {
                    if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file_path)) {
                        $ten_file_anh = $target_filename;
                    }
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

    /**
     * Xóa sản phẩm (Chỉ Admin)
     */
    public function delete($id = 0) {
        $this->checkAdmin();
        
        $id = (int)$id;
        if ($id > 0) {
            $this->productModel->delete($id);
        }
        $this->redirect('product/list');
    }

    /**
     * Tìm kiếm (Gọi về list để tận dụng giao diện bộ lọc)
     */
    public function search() {
        $this->list();
    }
}
?>