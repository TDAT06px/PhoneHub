<?php
// views/auth/profile.php
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> Hồ sơ của bạn</h5>
                <span class="badge bg-light text-primary"><?= strtoupper($user['role']) ?></span>
            </div>
            
            <div class="card-body p-4">
                
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?= $success ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-4 text-center mb-4 mb-md-0">
                        <div class="mb-3 position-relative d-inline-block">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['ho_ten']) ?>&background=E91E63&color=fff&size=150" 
                                 class="rounded-circle shadow img-thumbnail" alt="Avatar">
                        </div>
                        <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['ho_ten']) ?></h5>
                        <p class="text-muted small"><?= htmlspecialchars($user['email']) ?></p>
                        <p class="text-muted small">Ngày tham gia: <?= date('d/m/Y', strtotime($user['ngay_tao'] ?? 'now')) ?></p>
                    </div>

                    <div class="col-md-8">
                        <form action="<?= BASE_URL ?>/auth/profile" method="POST">
                            
                            <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Thông tin chi tiết</h6>

                            <div class="mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="ho_ten" value="<?= htmlspecialchars($user['ho_ten']) ?>" required>
                            </div>

                         <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                         </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" name="so_dien_thoai" value="<?= htmlspecialchars($user['so_dien_thoai'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày sinh</label>
                                    <input type="date" class="form-control" name="ngay_sinh" value="<?= $user['ngay_sinh'] ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Giới tính</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gioi_tinh" id="nam" value="Nam" <?= ($user['gioi_tinh'] == 'Nam') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="nam">Nam</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gioi_tinh" id="nu" value="Nữ" <?= ($user['gioi_tinh'] == 'Nữ') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="nu">Nữ</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gioi_tinh" id="khac" value="Khác" <?= ($user['gioi_tinh'] == 'Khác') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="khac">Khác</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">Quay lại</a>
                                <button type="submit" class="btn btn-primary px-4">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>