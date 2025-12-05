<?php
// views/cart/empty.php
?>

<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm text-center py-5 rounded-4">
                <div class="card-body">
                    <div class="mb-4 text-muted opacity-25">
                        <i class="fas fa-shopping-cart fa-6x"></i>
                    </div>
                    
                    <h3 class="fw-bold mb-3">Giỏ hàng của bạn đang trống</h3>
                    <p class="text-muted mb-4">
                        Có vẻ như bạn chưa thêm sản phẩm nào vào giỏ hàng.<br>
                        Hãy dạo một vòng và chọn những món đồ yêu thích nhé!
                    </p>
                    
                    <a href="<?= BASE_URL ?>/product/list" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                        <i class="fas fa-arrow-left me-2"></i> Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>