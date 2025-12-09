<?php
// Minimal login card only — header_auth opens the surrounding <main>
// Variables: $title, $error
?>
<div class="auth-card">
    <div class="card shadow-sm border-0 rounded-4 w-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="mb-0 text-white"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</h4>
                <small class="text-white-50">Tiếp tục mua sắm cùng ShopPhoneHub</small>
            </div>
        </div>
        <div class="card-body">
            <div class="text-center mb-4">
                <h3 class="fw-bold"><strong>PHONE</strong><span class="text-muted">HUB</span></h3>
                <p class="text-muted">Đăng nhập để tiếp tục</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/auth/login" method="POST" novalidate>
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="example@email.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="mat_khau">Mật khẩu</label>
                    <input id="mat_khau" name="mat_khau" type="password" class="form-control" placeholder="Mật khẩu" required>
                </div>

                <div class="d-grid mb-3">
                    <button class="btn btn-primary btn-lg" type="submit"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</button>
                </div>

                <div class="text-center small text-muted">
                    Chưa có tài khoản? <a href="<?= BASE_URL ?>/auth/register">Đăng ký</a>
                </div>
            </form>
        </div>
        <div class="card-footer text-center auth-footer-note">
            <small>Hoặc quay lại <a href="<?= BASE_URL ?>">Trang chủ</a></small>
        </div>
    </div>
</div>