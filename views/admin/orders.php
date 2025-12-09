<div class="card shadow border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-shopping-cart me-2"></i> Quản lý Đơn hàng</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($orders) || !is_array($orders)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Chưa có đơn hàng nào</td></tr>
                    <?php else: foreach($orders as $o): ?>
                    <tr>
                        <td class="fw-bold">#<?= htmlspecialchars($o['id'] ?? 'N/A') ?></td>
                        <td>
                            <div class="fw-bold"><?= htmlspecialchars($o['ho_ten'] ?? 'Khách hàng') ?></div>
                            <small class="text-muted"><?= htmlspecialchars($o['email'] ?? 'N/A') ?></small>
                        </td>
                        <td>
                            <?php 
                            $ngay_tao = $o['ngay_tao'] ?? null;
                            if ($ngay_tao && strtotime($ngay_tao) !== false) {
                                echo htmlspecialchars(date('d/m/Y H:i', strtotime($ngay_tao)));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td class="text-danger fw-bold"><?= number_format($o['tong_tien'] ?? 0, 0, ',', '.') ?>đ</td>
                        
                        <td>
                            <form action="" method="POST" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($o['id'] ?? '') ?>">
                                <?php 
                                $trang_thai = $o['trang_thai'] ?? 'Chờ xử lý';
                                $status_class = ($trang_thai=='Đã giao')?'text-success':(($trang_thai=='Đã hủy')?'text-danger':'text-warning');
                                ?>
                                <select name="trang_thai" class="form-select form-select-sm fw-bold <?= $status_class ?>" 
                                    style="width: 140px;" onchange="this.form.submit()">
                                    
                                    <?php 
                                        $statuses = ['Chờ xử lý', 'Đã xác nhận', 'Đang giao', 'Đã giao', 'Đã hủy'];
                                        foreach($statuses as $st): 
                                    ?>
                                        <option value="<?= htmlspecialchars($st) ?>" <?= $trang_thai == $st ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($st) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        
                        <td>
                            <a href="<?= BASE_URL ?>/admin/orderDetail/<?= htmlspecialchars($o['id'] ?? '') ?>" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>