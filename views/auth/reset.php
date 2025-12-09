<?php
// views/auth/reset.php
?>
<div class="auth-card">
    <div class="card-header">
        <h2><strong>PHONE</strong><span>HUB</span></h2>
        <div class="subtitle"><i class="fas fa-key me-2"></i>Đặt lại mật khẩu</div>
    </div>
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <?php if (empty($success)): ?>
        <form action="<?= BASE_URL ?>/auth/reset/<?= htmlspecialchars($token) ?>" method="POST">
            <div class="mb-4">
                <label class="form-label"><i class="fas fa-lock me-2" style="color: #E91E63;"></i>Mật khẩu mới</label>
                <input type="password" name="mat_khau" class="form-control" minlength="6" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Nhập lại mật khẩu</label>
                <input type="password" name="mat_khau_confirm" class="form-control" minlength="6" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Đặt lại mật khẩu</button>
        </form>
        <?php endif; ?>
    </div>
</div>
