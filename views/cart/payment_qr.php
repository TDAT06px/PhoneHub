<?php

$BANK_ID = 'MB'; 
$ACCOUNT_NO = '0000123456789'; 
$ACCOUNT_NAME = 'Lê Công Tiến Đạt'; 

$description = "DH" . $order['id'];
$amount = $order['tong_tien'];

// Link tạo QR của VietQR
$qr_url = "https://img.vietqr.io/image/{$BANK_ID}-{$ACCOUNT_NO}-compact2.png?amount={$amount}&addInfo={$description}&accountName={$ACCOUNT_NAME}";
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thanh toán chuyển khoản</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted mb-4">Vui lòng quét mã QR bên dưới để thanh toán cho đơn hàng <strong>#<?= $order['id'] ?></strong></p>
                
                <div class="mb-4 border rounded p-2 d-inline-block bg-white shadow-sm">
                    <img src="<?= $qr_url ?>" alt="Mã QR Thanh Toán" class="img-fluid" style="max-width: 300px;">
                </div>

                <div class="alert alert-info small text-start">
                    <ul class="mb-0">
                        <li><strong>Ngân hàng:</strong> <?= $BANK_ID ?></li>
                        <li><strong>Số tài khoản:</strong> <?= $ACCOUNT_NO ?></li>
                        <li><strong>Chủ tài khoản:</strong> <?= $ACCOUNT_NAME ?></li>
                        <li><strong>Số tiền:</strong> <?= number_format($amount, 0, ',', '.') ?>₫</li>
                        <li><strong>Nội dung:</strong> <?= $description ?></li>
                    </ul>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?= BASE_URL ?>/order/history" class="btn btn-success btn-lg">
                        <i class="fas fa-check-circle me-2"></i> Tôi đã thanh toán xong
                    </a>
                    <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">
                        Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>