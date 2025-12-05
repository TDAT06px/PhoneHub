<?php
// /views/auth/register.php
// Các biến $title, $error, $success được truyền từ AuthController::register()
?>
<div class="auth-card">
    <div class="card-header">
        <h2>
            <strong>PHONE</strong><span>HUB</span>
        </h2>
        <div class="subtitle">
            <i class="fas fa-user-plus me-2"></i>Tạo tài khoản mới
        </div>
    </div>
    
    <div class="card-body">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i><?= $error ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i><?= $success ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/auth/register" method="POST" novalidate>
            <!-- Họ và tên -->
            <div class="mb-4">
                <label for="ho_ten" class="form-label">
                    <i class="fas fa-user me-2" style="color: #E91E63;"></i>Họ và tên
                </label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="ho_ten" 
                    name="ho_ten" 
                    placeholder="Ví dụ: Nguyễn Văn A"
                    required 
                    pattern="[^\d]+"
                    title="Họ và tên không được chứa số">
            </div>

            <!-- Email và Số điện thoại -->
            <div class="form-row-group mb-4">
                <div>
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
                <div>
                    <label for="so_dien_thoai" class="form-label">
                        <i class="fas fa-phone me-2" style="color: #E91E63;"></i>Số điện thoại
                    </label>
                    <input 
                        type="tel" 
                        class="form-control" 
                        id="so_dien_thoai" 
                        name="so_dien_thoai" 
                        placeholder="0912345678"
                        required 
                        pattern="[0-9]{10}" 
                        title="Số điện thoại phải là 10 chữ số">
                </div>
            </div>

            <!-- Mật khẩu -->
            <div class="mb-4">
                <label for="mat_khau" class="form-label">
                    <i class="fas fa-lock me-2" style="color: #E91E63;"></i>Mật khẩu
                </label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="mat_khau" 
                    name="mat_khau" 
                    placeholder="Tối thiểu 6 ký tự"
                    required>
            </div>

            <!-- Ngày sinh và Giới tính -->
            <div class="form-row-group mb-4">
                <div>
                    <label for="ngay_sinh" class="form-label">
                        <i class="fas fa-calendar me-2" style="color: #E91E63;"></i>Ngày sinh
                    </label>
                    <input 
                        type="date" 
                        class="form-control" 
                        id="ngay_sinh" 
                        name="ngay_sinh">
                </div>
                <div>
                    <label for="gioi_tinh" class="form-label">
                        <i class="fas fa-venus-mars me-2" style="color: #E91E63;"></i>Giới tính
                    </label>
                    <select class="form-select" id="gioi_tinh" name="gioi_tinh">
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-auth w-100 mb-4">
                <i class="fas fa-user-plus me-2"></i>Đăng ký
            </button>
        </form>
    </div>

    <div class="auth-footer">
        <p>
            Đã có tài khoản? 
            <a href="<?= BASE_URL ?>/auth/login">Đăng nhập</a>
        </p>
    </div>
</div>