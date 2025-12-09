<?php
// views/admin/order_detail.php
// Variables: $order, $details
?>
<div class="card shadow border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-receipt me-2"></i> Chi tiết đơn hàng #<?= htmlspecialchars($order['id'] ?? '') ?></h5>
        <div>
            <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm btn-outline-secondary me-2"><i class="fas fa-arrow-left"></i> Quay lại</a>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-dark">Trang trước</a>
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <strong>Khách hàng:</strong> <?= htmlspecialchars($order['ho_ten'] ?? '') ?> &nbsp; 
            <small class="text-muted"><?= htmlspecialchars($order['email'] ?? '') ?></small>
        </div>
        <div class="mb-3">
            <strong>Ngày tạo:</strong> <?= htmlspecialchars($order['ngay_tao'] ?? '') ?> &nbsp; 
            <strong>Trạng thái:</strong> <?= htmlspecialchars($order['trang_thai'] ?? '') ?>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($details)): ?>
                        <tr><td colspan="4" class="text-center text-muted">Không có sản phẩm trong đơn</td></tr>
                    <?php else: foreach ($details as $d): ?>
                        <tr>
                            <td>
                                <a href="<?= BASE_URL ?>/product/detail/<?= htmlspecialchars($d['id_sanpham']) ?>" target="_blank">
                                    <?= htmlspecialchars($d['ten_sanpham']) ?>
                                </a>
                            </td>
                            <td><?= number_format($d['don_gia_luc_mua'] ?? 0, 0, ',', '.') ?>đ</td>
                            <td><?= (int)($d['so_luong'] ?? 0) ?></td>
                            <td class="text-danger fw-bold"><?= number_format((($d['don_gia_luc_mua'] ?? 0) * ($d['so_luong'] ?? 0)), 0, ',', '.') ?>đ</td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <h5 class="fw-bold text-danger">Tổng: <?= number_format($order['tong_tien'] ?? 0, 0, ',', '.') ?>đ</h5>
        </div>
    </div>
</div>