<div class="card"><div class="card-body">
<table class="table table-hover align-middle">
    <thead class="table-dark"><tr><th>Tên & Email</th><th>Quyền hạn</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
    <tbody>
        <?php if(empty($users) || !is_array($users)): ?>
            <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có người dùng nào</td></tr>
        <?php else: foreach($users as $u): ?>
        <tr><form method="POST">
            <td><input type="hidden" name="user_id" value="<?= htmlspecialchars($u['id'] ?? '') ?>">
                <strong><?= htmlspecialchars($u['ho_ten'] ?? 'N/A') ?></strong><br><small><?= htmlspecialchars($u['email'] ?? 'N/A') ?></small></td>
            <td><select name="role" class="form-select form-select-sm">
                <?php $current_role = $u['role'] ?? 'user'; ?>
                <option value="user" <?= $current_role=='user'?'selected':'' ?>>User</option>
                <option value="staff" <?= $current_role=='staff'?'selected':'' ?>>Staff</option>
                <option value="admin" <?= $current_role=='admin'?'selected':'' ?>>Admin</option>
            </select></td>
            <td><select name="trang_thai" class="form-select form-select-sm">
                <?php $current_status = (int)($u['trang_thai'] ?? 0); ?>
                <option value="0" <?= $current_status==0?'selected':'' ?>>🔴 Khóa / Chờ</option>
                <option value="1" <?= $current_status==1?'selected':'' ?>>🟢 Hoạt động</option>
            </select></td>
            <td><button type="submit" class="btn btn-primary btn-sm">Lưu</button></td>
        </form></tr>
        <?php endforeach; endif; ?>
    </tbody>
</table></div></div>