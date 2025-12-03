<?php
// /views/auth/register.php
// Các biến $title, $error, $success được truyền từ AuthController::register()
?>
<div classs="row">
    <div class="col-lg-6 col-md-9 mx-auto">
        <div class="card shadow border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h2 class="text-uppercase fw-bold">
                        <strong class="fs-2">PHONE</strong><span class="fs-2 fw-light">HUB</span>
                    </h2>
                    <h5 class="text-muted">Tạo tài khoản mới</h5>
                </div>
                
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <form action="<?= BASE_URL ?>/auth/register" method="POST">
                    <div class="mb-3">
                        <label for="ho_ten" class="form-label">Họ và tên</label>
                       <input type="text" class="form-control" id="ho_ten" name="ho_ten" 
                       required 
                       pattern="[^\d]+"
                       title="Họ và tên không được chứa số">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" 
                                required 
                                pattern="[0-9]{10}" 
                                title="Số điện thoại phải là 10 chữ số (ví dụ: 0912345678)">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="mat_khau" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="mat_khau" name="mat_khau" required>
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
                        <button type="submit" class="btn btn-primary btn-lg">Đăng ký</button>
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