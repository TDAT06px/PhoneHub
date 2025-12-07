<div class="card"><div class="card-body">

<?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($success) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($error) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="mb-4">
    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="fas fa-user-plus me-2"></i> Tạo tài khoản mới
    </button>
</div>

<table class="table table-hover align-middle">
    <thead class="table-dark"><tr><th>Tên & Email</th><th>Quyền hạn</th><th>Trạng thái</th><th>Cập nhật</th><th>Thao tác</th></tr></thead>
    <tbody>
        <?php if(empty($users) || !is_array($users)): ?>
            <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có người dùng nào</td></tr>
        <?php else: foreach($users as $u): ?>
        <tr>
            <td>
                <strong><?= htmlspecialchars($u['ho_ten'] ?? 'N/A') ?></strong><br><small><?= htmlspecialchars($u['email'] ?? 'N/A') ?></small>
            </td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($u['id'] ?? '') ?>">
                    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php $current_role = $u['role'] ?? 'user'; ?>
                        <option value="user" <?= $current_role=='user'?'selected':'' ?>>👤 User</option>
                        <option value="admin" <?= $current_role=='admin'?'selected':'' ?>>👨‍💼 Admin</option>
                    </select>
                </form>
            </td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($u['id'] ?? '') ?>">
                    <select name="trang_thai" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php $current_status = (int)($u['trang_thai'] ?? 0); ?>
                        <option value="0" <?= $current_status==0?'selected':'' ?>>🔴 Khóa / Chờ</option>
                        <option value="1" <?= $current_status==1?'selected':'' ?>>🟢 Hoạt động</option>
                    </select>
                </form>
            </td>
            <td>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#collapseUpdate<?= $u['id'] ?>"><i class="fas fa-sync me-1"></i> Làm mới</button>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $u['id'] ?>"><i class="fas fa-trash me-1"></i> Xóa</button>
            </td>
        </tr>
        <!-- Modal xác nhận xóa -->
        <div class="modal fade" id="deleteUserModal<?= $u['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-trash me-2"></i> Xác nhận xóa tài khoản</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc muốn xóa tài khoản <strong><?= htmlspecialchars($u['ho_ten'] ?? 'N/A') ?></strong> (<em><?= htmlspecialchars($u['email'] ?? 'N/A') ?></em>)?</p>
                        <p class="text-danger small"><i class="fas fa-exclamation-triangle me-1"></i> Hành động này không thể hoàn tác!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= htmlspecialchars($u['id'] ?? '') ?>">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Xóa tài khoản</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; endif; ?>
    </tbody>
</table>

</div></div>

<!-- Modal Tạo Tài Khoản -->
<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i> Tạo tài khoản mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="create">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user me-2"></i>Họ và tên *</label>
                        <input type="text" class="form-control" name="ho_ten" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-envelope me-2"></i>Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-lock me-2"></i>Mật khẩu (tối thiểu 6 ký tự) *</label>
                        <input type="password" class="form-control" name="mat_khau" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><i class="fas fa-user-tie me-2"></i>Vai trò *</label>
                        <select name="role" class="form-select" required>
                            <option value="user">👤 User (Người dùng thường)</option>
                            <option value="admin">👨‍💼 Admin (Quản trị viên)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-2"></i> Tạo tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>