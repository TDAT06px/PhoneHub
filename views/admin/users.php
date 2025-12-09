<div class="card">
        <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Quản lý Nhân sự</h4>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                                <i class="fas fa-user-plus"></i> Tạo tài khoản
                        </button>
                </div>

                <table class="table table-hover align-middle">
                        <thead class="table-dark"><tr><th>Tên & Email</th><th>Quyền hạn</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
                        <tbody>
                                <?php if(empty($users) || !is_array($users)): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có người dùng nào</td></tr>
                                <?php else: foreach($users as $u): ?>
                                <tr>
                                        <td>
                                                <strong><?= htmlspecialchars($u['ho_ten'] ?? 'N/A') ?></strong><br>
                                                <small><?= htmlspecialchars($u['email'] ?? 'N/A') ?></small>
                                        </td>
                                        <td>
                                                <?php $current_role = $u['role'] ?? 'user'; ?>
                                                <span class="badge bg-<?= $current_role=='admin' ? 'danger' : ($current_role=='staff'?'secondary':'primary') ?>"><?= htmlspecialchars(strtoupper($current_role)) ?></span>
                                        </td>
                                        <td>
                                                <?php $st = (int)($u['trang_thai'] ?? 0); ?>
                                                <form method="POST" class="d-flex align-items-center gap-2">
                                                        <input type="hidden" name="action" value="update_status">
                                                        <input type="hidden" name="user_id" value="<?= htmlspecialchars($u['id'] ?? '') ?>">
                                                        <select name="trang_thai" class="form-select form-select-sm" style="width:140px;">
                                                                <option value="1" <?= $st==1 ? 'selected' : '' ?>>Kích hoạt</option>
                                                                <option value="0" <?= $st==0 ? 'selected' : '' ?>>Chờ duyệt / Khóa</option>
                                                        </select>
                                                        <button class="btn btn-sm btn-primary" type="submit">Lưu</button>
                                                </form>
                                        </td>
                                        <td>
                                                <button class="btn btn-outline-warning btn-sm btn-change-pass" data-user-id="<?= htmlspecialchars($u['id'] ?? '') ?>" data-user-name="<?= htmlspecialchars($u['ho_ten'] ?? '') ?>">
                                                        <i class="fas fa-key"></i> Đổi mật khẩu
                                                </button>
                                        </td>
                                </tr>
                                <?php endforeach; endif; ?>
                        </tbody>
                </table>
        </div>
</div>


<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tạo tài khoản mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="create_user">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" name="ho_ten" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" name="mat_khau" class="form-control" minlength="6" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Tạo tài khoản</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal (single, populated by JS) -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="change_password">
                <input type="hidden" name="user_id" id="cp_user_id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Người dùng</label>
                        <input type="text" id="cp_user_name" class="form-control" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="form-control" minlength="6" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-change-pass').forEach(function(btn) {
                btn.addEventListener('click', function() {
                        var id = this.getAttribute('data-user-id');
                        var name = this.getAttribute('data-user-name');
                        document.getElementById('cp_user_id').value = id;
                        document.getElementById('cp_user_name').value = name;
                        var modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
                        modal.show();
                });
        });
});
</script>