<?php
// /views/product/search_results.php
// Các biến $title, $products, $keyword được truyền từ Controller
?>

<h2 class="mb-4">
    Kết quả tìm kiếm cho: "<?= $keyword ?>"
</h2>

<?php if (empty($products)): ?>
    
    <div class="alert alert-warning text-center" role="alert">
        <h4 class="alert-heading">Không tìm thấy!</h4>
        <p>Không có sản phẩm nào phù hợp với từ khóa "<?= $keyword ?>".</p>
        <hr>
        <p class="mb-0">Vui lòng thử lại với từ khóa khác hoặc quay về trang chủ.</p>
        <a href="<?= BASE_URL ?>/product/list" class="btn btn-primary mt-3">Quay về trang chủ</a>
    </div>

<?php else: ?>

    <p class="text-muted mb-3">Tìm thấy <?= count($products) ?> sản phẩm.</p>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($products as $row): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($row['hinhanh']) ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($row['ten_sanpham']) ?>" 
                     style="height: 250px; object-fit: cover;">
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-truncate">
                        <?= htmlspecialchars($row['ten_sanpham']) ?>
                    </h5>
                    <p class="card-text text-danger fw-bold fs-5">
                        <?= number_format($row['gia'], 0, ',', '.') ?>₫
                    </p>
                    
                    <div class="mt-auto">
                        <a href="<?= BASE_URL ?>/product/detail/<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>

                        <a href="<?= BASE_URL ?>/cart/add/<?= $row['id'] ?>" class="btn btn-success w-100">
                            <i class="fas fa-shopping-cart me-1"></i>Thêm vào giỏ hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>