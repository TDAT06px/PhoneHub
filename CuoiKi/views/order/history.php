<?php
// /views/order/history.php
// Biến $orders được truyền từ OrderController::history()
?>
<h2 class="mb-4">📅 Lịch sử mua hàng</h2>

<div class="card shadow border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="text-center">
                    <th scope="col">Mã Đơn Hàng</th>
                    <th scope="col">Ngày Đặt</th>
                    <th scope="col">Tổng Tiền</th>
                    <th scope="col">Trạng Thái</th>
                    <th scope="col">Chi Tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="5" class="text-center p-5">
                            <p class="h5 text-muted">Bạn chưa có đơn hàng nào.</p>
                        </td>
                    </tr>
                <?php endif; ?>
                
                <?php foreach ($orders as $order): ?>
                <tr class="text-center">
                    <td class="fw-bold">#<?= $order['id'] ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($order['ngay_tao'])) ?></td>
                    <td class="text-danger fw-bold">
                        <?= number_format($order['tong_tien'], 0, ',', '.') ?>₫
                    </td>
                    <td>
                        <span class="badge bg-warning text-dark">
                            <?= htmlspecialchars($order['trang_thai']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/order/detail/<?= $order['id'] ?>" 
                           class="btn btn-outline-primary btn-sm">
                           Xem chi tiết
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>