<?php
// /views/auth/register.php
// Các biến $title, $error, $success được truyền từ AuthController::register()
?>
<style>
/* Slightly enlarge register layout specifically for register page */
.auth-container { max-width: 880px; width: 100%; }
.auth-card h2 { font-size: 2.2rem; }
.auth-card .card-header h4 { font-size: 1.05rem; }
.auth-card .btn-lg { padding: .85rem 1rem; font-size: 1.05rem; }
@media (min-width: 1200px) {
    .auth-container { max-width: 960px; }
    .auth-card h2 { font-size: 2.6rem; }
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card auth-card shadow border-0 rounded-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-0 text-white"><i class="fas fa-user-plus me-2"></i>Đăng ký</h4>
                    <small class="text-white-50">Tạo tài khoản để nhận ưu đãi</small>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h2 class="text-uppercase fw-bold">
                        <strong class="fs-2 text-primary">PHONE</strong><span class="fs-2 fw-light text-primary">HUB</span>
                    </h2>
                    <h5 class="text-muted">Tạo tài khoản mới</h5>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/auth/register" method="POST">
                    <div class="mb-3">
                        <label for="ho_ten" class="form-label">Họ và tên</label>
                       <input type="text" class="form-control" id="ho_ten" name="ho_ten" required pattern="[^\d]+" title="Họ và tên không được chứa số">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" required pattern="[0-9]{10}" title="Số điện thoại phải là 10 chữ số (ví dụ: 0912345678)">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="mat_khau" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="mat_khau" name="mat_khau" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="mat_khau_confirm" class="form-label">Nhập lại mật khẩu</label>
                        <input type="password" class="form-control" id="mat_khau_confirm" name="mat_khau_confirm" required minlength="6">
                        <div class="form-text text-danger" id="pw_match_msg" style="display:none;">Mật khẩu không khớp.</div>
                    </div>
                    <div class="row">
                         <div class="col-md-6 mb-3">
                            <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gioi_tinh" class="form-label">Giới tính</label>
                            <select class="form-select" id="gioi_tinh" name="gioi_tinh">
                                <option value="Nam">Nam</option>
                                <option value="Nữ">Nữ</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <button type="submit" id="register_submit" class="btn btn-primary btn-lg">Đăng ký</button>
                    </div>
                    <p class="text-center">
                        Đã có tài khoản? 
                        <a href="<?= BASE_URL ?>/auth/login">Đăng nhập</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var pw = document.getElementById('mat_khau');
    var pwc = document.getElementById('mat_khau_confirm');
    var msg = document.getElementById('pw_match_msg');
    var submit = document.getElementById('register_submit');

    function checkMatch() {
        if (!pw || !pwc) return;
        if (pwc.value === '') { msg.style.display = 'none'; submit.disabled = false; return; }
        if (pw.value !== pwc.value) {
            msg.style.display = 'block';
            submit.disabled = true;
        } else {
            msg.style.display = 'none';
            submit.disabled = false;
        }
    }

    if (pw && pwc) {
        pw.addEventListener('input', checkMatch);
        pwc.addEventListener('input', checkMatch);
    }
});
</script>