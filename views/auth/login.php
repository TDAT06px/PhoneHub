<?php
// /views/auth/login.php
// Các biến $title, $error được truyền từ AuthController::login()
?>
<div class="auth-card">
    <div class="card-header">
        <h2>
            <strong>PHONE</strong><span>HUB</span>
        </h2>
        <div class="subtitle">
            <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập tài khoản
        </div>
    </div>
    
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i><?= $error ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/login" method="POST" novalidate>
            <div class="mb-4">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2" style="color: #E91E63;"></i>Email
                </label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    name="email" 
                    placeholder="example@email.com"
                    required>
            </div>

            <div class="mb-4">
                <label for="mat_khau" class="form-label">
                    <i class="fas fa-lock me-2" style="color: #E91E63;"></i>Mật khẩu
                </label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="mat_khau" 
                    name="mat_khau" 
                    placeholder="Nhập mật khẩu của bạn"
                    required>
            </div>

            <button type="submit" class="btn btn-auth w-100 mb-4">
                <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
            </button>
        </form>
    </div>

    <div class="auth-footer">
        <p>
            Chưa có tài khoản? 
            <a href="<?= BASE_URL ?>/auth/register">Đăng ký ngay</a>
        </p>
    </div>
</div>