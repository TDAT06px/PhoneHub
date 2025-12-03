<?php
// views/product/detail.php
?>

<div class="row mb-5">
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm border-0">
            <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($product['hinhanh']) ?>" 
                 class="card-img-top object-fit-cover" alt="<?= htmlspecialchars($product['ten_sanpham']) ?>">
        </div>
    </div>

    <div class="col-md-7">
        <h2 class="fw-bold mb-3"><?= htmlspecialchars($product['ten_sanpham']) ?></h2>
        
        <div class="mb-3 d-flex align-items-center">
            <span class="text-warning fs-5 me-2">
                <span class="fw-bold border-bottom border-warning"><?= number_format($product['avg_rating'], 1) ?></span>
                <?php 
                $rating = $product['avg_rating'];
                for($i=1; $i<=5; $i++) {
                    if ($rating >= $i) echo '<i class="fas fa-star"></i>';
                    elseif ($rating >= $i - 0.5) echo '<i class="fas fa-star-half-alt"></i>';
                    else echo '<i class="far fa-star"></i>';
                }
                ?>
            </span>
            <span class="text-muted border-start ps-2 ms-2"><?= $total_comments ?> Đánh giá</span>
            <span class="text-muted border-start ps-2 ms-2"><?= number_format($product['luot_xem']) ?> Lượt xem</span>
        </div>

        <h3 class="text-danger fw-bold mb-4 display-6"><?= number_format($product['gia'], 0, ',', '.') ?>₫</h3>
        
        <p class="mb-4 text-secondary"><?= nl2br(htmlspecialchars($product['mo_ta'])) ?></p>

        <div class="d-flex gap-3 mb-4">
            <a href="<?= BASE_URL ?>/cart/add/<?= $product['id'] ?>" class="btn btn-primary btn-lg flex-grow-1 shadow-sm">
                <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ hàng
            </a>
            <button class="btn btn-outline-danger btn-lg"><i class="far fa-heart"></i></button>
        </div>

        <?php if (!empty($product['thong_so_ky_thuat'])): ?>
        <div class="card bg-light border-0">
            <div class="card-body">
                <h6 class="fw-bold text-dark"><i class="fas fa-cog me-2"></i>Thông số kỹ thuật:</h6>
                <div class="small text-secondary" style="white-space: pre-line; line-height: 1.8;">
                    <?= htmlspecialchars($product['thong_so_ky_thuat']) ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <h4 class="fw-bold text-uppercase">Đánh giá sản phẩm</h4>
            </div>
            <div class="card-body px-4">
                
                <div class="bg-light p-4 rounded mb-4 border border-secondary-subtle">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center border-end">
                            <div class="text-warning mb-2">
                                <span class="display-4 fw-bold"><?= number_format($product['avg_rating'], 1) ?></span>
                                <span class="fs-4">/ 5</span>
                            </div>
                            <div class="text-warning fs-5 mb-2">
                                <?php 
                                for($i=1; $i<=5; $i++) {
                                    if ($rating >= $i) echo '<i class="fas fa-star"></i>';
                                    elseif ($rating >= $i - 0.5) echo '<i class="fas fa-star-half-alt"></i>';
                                    else echo '<i class="far fa-star"></i>';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="col-md-9 ps-md-4">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <a href="?rating_filter=0" class="btn <?= ($current_filter == 0) ? 'btn-outline-primary active' : 'btn-outline-secondary bg-white' ?>">
                                    Tất cả (<?= $total_comments ?>)
                                </a>
                                
                                <?php for($s=5; $s>=1; $s--): ?>
                                    <a href="?rating_filter=<?= $s ?>" class="btn <?= ($current_filter == $s) ? 'btn-outline-primary active' : 'btn-outline-secondary bg-white' ?>">
                                        <?= $s ?> Sao (<?= $rating_counts[$s] ?>)
                                    </a>
                                <?php endfor; ?>
                            </div>

                            <?php for($star=5; $star>=1; $star--): 
                                $percent = ($total_comments > 0) ? ($rating_counts[$star] / $total_comments) * 100 : 0;
                            ?>
                            <a href="?rating_filter=<?= $star ?>" class="text-decoration-none text-dark d-block mb-1">
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2 small fw-bold" style="width: 35px;"><?= $star ?> sao</span>
                                    <div class="progress flex-grow-1" style="height: 6px; cursor: pointer;">
                                        <div class="progress-bar bg-warning" style="width: <?= $percent ?>%"></div>
                                    </div>
                                    <span class="text-muted ms-2 small" style="width: 30px; text-align: right;"><?= $rating_counts[$star] ?></span>
                                </div>
                            </a>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <?php if (isset($_SESSION['user'])): ?>
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#reviewForm">
                            <i class="fas fa-pen me-2"></i>Viết đánh giá của bạn
                        </button>
                    </div>
                    <div class="collapse mb-4" id="reviewForm">
                        <div class="card card-body bg-light border-0">
                            <form action="<?= BASE_URL ?>/comment/add" method="POST">
                                <input type="hidden" name="id_sanpham" value="<?= $product['id'] ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Chất lượng sản phẩm:</label>
                                    <div class="rating-css">
                                        <div class="btn-group" role="group">
                                            <?php for($s=5; $s>=1; $s--): ?>
                                            <input type="radio" class="btn-check" name="danh_gia" id="rating<?= $s ?>" value="<?= $s ?>" <?= $s==5?'checked':'' ?>>
                                            <label class="btn btn-outline-warning" for="rating<?= $s ?>"><?= $s ?> <i class="fas fa-star"></i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nội dung đánh giá:</label>
                                    <textarea name="noi_dung" class="form-control" rows="3" placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm này nhé..." required></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-danger px-4">Gửi Đánh Giá</button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <hr class="text-muted">

                <?php if (empty($comments)): ?>
                    <div class="text-center py-5">
                        <i class="far fa-comment-dots fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Chưa có đánh giá nào phù hợp với bộ lọc này.</p>
                    </div>
                <?php else: ?>
                    <div class="review-list">
                        <?php foreach ($comments as $cmt): ?>
                            <div class="d-flex mb-4 border-bottom pb-3">
                                <div class="flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($cmt['ho_ten']) ?>&background=random&color=fff" 
                                         class="rounded-circle shadow-sm" width="50" height="50" alt="User">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($cmt['ho_ten']) ?></h6>
                                        <small class="text-muted fst-italic"><?= date("d/m/Y H:i", strtotime($cmt['ngay_tao'])) ?></small>
                                    </div>
                                    <div class="text-warning small mb-2">
                                        <?php for($k=1; $k<=5; $k++) echo ($k <= $cmt['danh_gia']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-secondary opacity-25"></i>'; ?>
                                        <span class="text-dark ms-2 fw-semibold" style="font-size: 0.9em;">
                                            <?php 
                                            $labels = [1=>'Rất Tệ', 2=>'Tệ', 3=>'Bình Thường', 4=>'Tốt', 5=>'Tuyệt Vời'];
                                            echo $labels[$cmt['danh_gia']] ?? ''; 
                                            ?>
                                        </span>
                                    </div>
                                    <p class="mb-0 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
                                        <?= nl2br(htmlspecialchars($cmt['noi_dung'])) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php if(!empty($related_products)): ?>
<h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
<div class="row row-cols-1 row-cols-md-4 g-4">
    <?php foreach ($related_products as $rel): ?>
    <div class="col">
        <div class="card h-100 shadow-sm border-0 product-card">
            <a href="<?= BASE_URL ?>/product/detail/<?= $rel['id'] ?>">
                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($rel['hinhanh']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
            </a>
            <div class="card-body">
                <h6 class="card-title text-truncate">
                    <a href="<?= BASE_URL ?>/product/detail/<?= $rel['id'] ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($rel['ten_sanpham']) ?></a>
                </h6>
                <div class="small text-warning mb-2">
                    <?php 
                    $rel_stars = round($rel['avg_rating']); 
                    for($r=1; $r<=5; $r++) echo ($r <= $rel_stars) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star text-secondary opacity-25"></i>';
                    ?>
                </div>
                <p class="card-text text-danger fw-bold"><?= number_format($rel['gia'], 0, ',', '.') ?>₫</p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>