<?php
// views/product/list.php

// 1. Xử lý logic lọc & phân trang
$current_params = $_GET;
unset($current_params['page']);
$base_query = http_build_query($current_params);
if (!empty($base_query)) $base_query .= '&';

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$rating = $_GET['rating'] ?? null;

// 2. Kiểm tra quyền Admin
$is_admin = isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'; 
?>

<style>
    .carousel-item img {
        height: 350px; 
        object-fit: cover;
        object-position: center; 
    }
    .text-shadow {
        text-shadow: 0 2px 10px rgba(0,0,0,0.7);
    }
    .policy-icon {
        width: 50px; height: 50px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        background: rgba(var(--bs-primary-rgb), 0.1);
        font-size: 1.5rem;
    }
    .rating-bar-5 { background: linear-gradient(90deg, #ffc107 100%, #ddd 0%); }
    .rating-bar-4 { background: linear-gradient(90deg, #ffc107 80%, #ddd 20%); }
    .rating-bar-3 { background: linear-gradient(90deg, #ffc107 60%, #ddd 40%); }
    .product-card:hover { transform: translateY(-5px); transition: 0.3s; }
    
    /* Style riêng cho nút danh mục cha để trông giống link nhưng không phải link */
    .btn-parent-cat {
        text-align: left;
        border: none;
        background: none;
        width: 100%;
        padding: 1rem;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .btn-parent-cat:hover {
        background-color: #f8f9fa;
        color: var(--bs-primary);
    }
    .btn-parent-cat[aria-expanded="true"] {
        color: var(--bs-primary);
        background-color: #e9ecef;
        font-weight: bold;
    }
</style>

<div class="mb-5">
    <div class="row">
        <div class="col-12">
            <div id="mainCarousel" class="carousel slide shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="4000">
                        <img src="https://images.unsplash.com/photo-1695048133142-1a20484d2569?q=80&w=1600&fit=crop" class="d-block w-100" alt="iPhone 15">
                        <div class="carousel-caption d-none d-md-block text-start pb-5 text-shadow">
                            <span class="badge bg-danger mb-2">HOT SALE</span>
                            <h2 class="display-4 fw-bold">iPhone 15 Pro Max</h2>
                            <p class="fs-4">Titan tự nhiên. Chip A17 Pro mạnh mẽ nhất.</p>
                            <a href="#" class="btn btn-light fw-bold rounded-pill px-5 py-2">Mua ngay</a>
                        </div>
                    </div>
                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?q=80&w=1600&fit=crop" class="d-block w-100" alt="Samsung">
                        <div class="carousel-caption d-none d-md-block text-start pb-5 text-shadow">
                            <span class="badge bg-primary mb-2">NEW ARRIVAL</span>
                            <h2 class="display-4 fw-bold">Galaxy Z Flip5</h2>
                            <p class="fs-4">Nhập hội linh hoạt. Gập mở tương lai.</p>
                        </div>
                    </div>
                    <div class="carousel-item" data-bs-interval="4000">
                        <img src="https://images.unsplash.com/photo-1661961110671-77b71b929d52?q=80&w=1600&fit=crop" class="d-block w-100" alt="Macbook">
                        <div class="carousel-caption d-none d-md-block text-end pb-5 text-shadow">
                            <h2 class="display-4 fw-bold">MacBook Air M2</h2>
                            <p class="fs-4">Mỏng nhẹ. Hiệu năng vượt trội.</p>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
            </div>
        </div>
    </div>

    <div class="row mt-4 g-3">
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center bg-white p-3 rounded-3 shadow-sm border h-100">
                <div class="policy-icon me-3 text-primary"><i class="fas fa-truck-fast"></i></div>
                <div><h6 class="fw-bold mb-0">Freeship</h6><small class="text-muted">Đơn từ 500k</small></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center bg-white p-3 rounded-3 shadow-sm border h-100">
                <div class="policy-icon me-3 text-success"><i class="fas fa-shield-alt"></i></div>
                <div><h6 class="fw-bold mb-0">Bảo hành</h6><small class="text-muted">1 đổi 1 30 ngày</small></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="d-flex align-items-center bg-white p-3 rounded-3 shadow-sm border h-100">
                <div class="policy-icon me-3 text-warning"><i class="fas fa-headset"></i></div>
                <div><h6 class="fw-bold mb-0">Hỗ trợ 24/7</h6><small class="text-muted">Tư vấn online</small></div>
            </div>
        </div>
         <div class="col-6 col-md-3">
            <div class="d-flex align-items-center bg-white p-3 rounded-3 shadow-sm border h-100">
                <div class="policy-icon me-3 text-danger"><i class="fas fa-tag"></i></div>
                <div><h6 class="fw-bold mb-0">Giá rẻ</h6><small class="text-muted">Cam kết tốt nhất</small></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 mb-4">
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white"><h5 class="mb-0"><i class="fas fa-bars"></i> Danh mục</h5></div>
            <div class="list-group list-group-flush">
                <a href="<?= BASE_URL ?>/product/list" class="list-group-item list-group-item-action fw-bold <?= !$category_id ? 'active' : '' ?>">
                    <i class="fas fa-th-large me-2"></i> Tất cả sản phẩm
                </a>

                <?php 
                if (isset($categories) && is_array($categories)) {
                    foreach ($categories as $parent) {
                        // Chỉ lấy danh mục Cha (parent_id = null)
                        if (empty($parent['parent_id'])) {
                            
                            // Tìm con của cha này
                            $children = [];
                            $is_expanded = false; // Trạng thái mở của menu cha

                            foreach ($categories as $child) {
                                if ($child['parent_id'] == $parent['id']) {
                                    $children[] = $child;
                                    // Nếu đang chọn con thì cha phải mở ra
                                    if ($category_id == $child['id']) $is_expanded = true;
                                }
                            }
                            
                            $collapse_id = "cat-" . $parent['id'];
                            $show_class = $is_expanded ? 'show' : '';
                ?>
                            <div class="list-group-item p-0 border-bottom">
                                <button class="btn-parent-cat" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#<?= $collapse_id ?>" 
                                        aria-expanded="<?= $is_expanded ? 'true' : 'false' ?>">
                                    <span><?= htmlspecialchars($parent['ten_danhmuc']) ?></span>
                                    <i class="fas fa-chevron-down small"></i>
                                </button>

                                <?php if (!empty($children)): ?>
                                    <div class="collapse <?= $show_class ?>" id="<?= $collapse_id ?>">
                                        <div class="bg-light pb-2">
                                            <?php foreach ($children as $sub): ?>
                                                <a href="?category=<?= $sub['id'] ?>" 
                                                   class="list-group-item list-group-item-action border-0 bg-transparent py-2 ps-4 small <?= ($category_id == $sub['id']) ? 'text-primary fw-bold' : '' ?>">
                                                    <i class="fas fa-angle-right me-2"></i> <?= htmlspecialchars($sub['ten_danhmuc']) ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                <?php 
                        } 
                    } 
                }
                ?>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light"><h6 class="mb-0 fw-bold">Lọc theo giá</h6></div>
            <div class="card-body">
                <form method="GET" action="">
                    <?php if($category_id): ?><input type="hidden" name="category" value="<?= $category_id ?>"><?php endif; ?>
                    <div class="input-group input-group-sm mb-2">
                        <input type="number" class="form-control" name="min_price" placeholder="Từ" value="<?= $min_price ?>">
                        <span class="input-group-text">-</span>
                        <input type="number" class="form-control" name="max_price" placeholder="Đến" value="<?= $max_price ?>">
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">Áp dụng</button>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light"><h6 class="mb-0 fw-bold">Đánh giá</h6></div>
            <div class="list-group list-group-flush">
                <?php for($s=5; $s>=1; $s--): // Hiển thị từ 5 xuống 1 sao
                    $r_params = $current_params; $r_params['rating'] = $s;
                ?>
                <a href="?<?= http_build_query($r_params) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?= ($rating == $s) ? 'bg-light text-primary fw-bold' : '' ?>">
                    <span class="text-warning">
                        <?= str_repeat('<i class="fas fa-star"></i>', $s) . str_repeat('<i class="far fa-star"></i>', 5-$s) ?>
                    </span>
                    <span class="small text-muted">trở lên</span>
                </a>
                <?php endfor; ?>
                
                <?php if ($rating): 
                    $clear_r = $current_params; unset($clear_r['rating']);
                ?>
                <a href="?<?= http_build_query($clear_r) ?>" class="list-group-item list-group-item-action text-danger text-center small mt-2 border-top">
                    <i class="fas fa-times me-1"></i> Bỏ lọc đánh giá
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded shadow-sm border">
            <h5 class="mb-0 fw-bold"></h5>
            
            <div class="d-flex align-items-center gap-2">
                <select class="form-select form-select-sm" style="width: 150px;" onchange="location = this.value;">
                    <option value="?<?= $base_query ?>sort=new" <?= (!isset($_GET['sort']) || $_GET['sort']=='new')?'selected':'' ?>>Mới nhất</option>
                    <option value="?<?= $base_query ?>sort=price_asc" <?= (isset($_GET['sort']) && $_GET['sort']=='price_asc')?'selected':'' ?>>Giá tăng dần</option>
                    <option value="?<?= $base_query ?>sort=price_desc" <?= (isset($_GET['sort']) && $_GET['sort']=='price_desc')?'selected':'' ?>>Giá giảm dần</option>
                </select>

                <?php if ($is_admin): ?>
                    <a href="<?= BASE_URL ?>/product/add" class="btn btn-success btn-sm fw-bold text-nowrap">
                        <i class="fas fa-plus"></i> Thêm SP
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($products)): ?>
             <div class="alert alert-warning text-center py-5">
                 <h4><i class="fas fa-search"></i> Không tìm thấy sản phẩm!</h4>
                 <p class="text-muted">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                 <a href="<?= BASE_URL ?>/product/list" class="btn btn-outline-primary mt-2">Xem tất cả</a>
            </div>
        <?php else: ?>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <?php foreach ($products as $row): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 product-card position-relative">
                        
                        <a href="<?= BASE_URL ?>/product/detail/<?= $row['id'] ?>">
                            <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($row['hinhanh']) ?>" 
                                 class="card-img-top" style="height: 200px; object-fit: cover;" 
                                 onerror="this.src='https://via.placeholder.com/300x300?text=No+Image'">
                        </a>
                        
                        <?php if ($is_admin): ?>
                            <div class="position-absolute top-0 end-0 p-2">
                                <a href="<?= BASE_URL ?>/product/edit/<?= $row['id'] ?>" class="btn btn-light btn-sm shadow-sm text-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                <a href="<?= BASE_URL ?>/product/delete/<?= $row['id'] ?>" class="btn btn-light btn-sm shadow-sm text-danger" onclick="return confirm('Xóa?')" title="Xóa"><i class="fas fa-trash"></i></a>
                            </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="card-title text-truncate mb-1">
                                <a href="<?= BASE_URL ?>/product/detail/<?= $row['id'] ?>" class="text-decoration-none text-dark fw-bold">
                                    <?= htmlspecialchars($row['ten_sanpham']) ?>
                                </a>
                            </h6>
                            
                            <div class="mb-2 small text-warning" title="Đánh giá: <?= $row['avg_rating'] ?> sao">
                                <?php 
                                $stars = (float)$row['avg_rating'];
                                for($i=1; $i<=5; $i++) {
                                    if($stars >= $i) echo '<i class="fas fa-star"></i>'; // Sao đầy
                                    elseif($stars >= $i - 0.5) echo '<i class="fas fa-star-half-alt"></i>'; // Sao rưỡi
                                    else echo '<i class="far fa-star"></i>'; // Sao rỗng
                                }
                                ?>
                                <span class="text-muted ms-1">(<?= $row['review_count'] ?? 0 ?>)</span>
                            </div>
                            
                            <p class="card-text text-danger fw-bold fs-5 mb-3">
                                <?= number_format($row['gia'], 0, ',', '.') ?>₫
                            </p>
                            
                            <div class="mt-auto">
                                <a href="<?= BASE_URL ?>/cart/add/<?= $row['id'] ?>" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <nav class="mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?= $base_query ?>page=<?= $page - 1 ?>">«</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= $base_query ?>page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?= $base_query ?>page=<?= $page + 1 ?>">»</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>