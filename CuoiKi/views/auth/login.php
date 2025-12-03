<?php
// /views/auth/login.php
// Các biến $title, $error được truyền từ AuthController::login()
?>
<div classs="row">
    <div class="col-lg-5 col-md-8 mx-auto">
        <div class="card shadow border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h2 class="text-uppercase fw-bold">
                        <strong class="fs-2">PHONE</strong><span class="fs-2 fw-light">HUB</span>
                    </h2>
                    <h5 class="text-muted">Đăng nhập tài khoản</h5>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/auth/login" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="mat_khau" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="mat_khau" name="mat_khau" required>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">Đăng nhập</button>
                    </div>
                    <p class="text-center">
                        Chưa có tài khoản? 
                        <a href="<?= BASE_URL ?>/auth/register">Đăng ký ngay</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>