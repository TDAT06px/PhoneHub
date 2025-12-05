<?php
// /views/order/detail.php
// Biến $order (thông tin chung) và $details (sản phẩm) được truyền từ Controller
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Chi tiết đơn hàng #<?= $order['id'] ?></h2>
    <a href="<?= BASE_URL ?>/order/history" class="btn btn-outline-secondary">
        ← Quay lại lịch sử
    </a>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow border-0 h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thông tin chung</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Mã đơn:</strong>
                        <span>#<?= $order['id'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Ngày đặt:</strong>
                        <span><?= date("d/m/Y H:i", strtotime($order['ngay_tao'])) ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Trạng thái:</strong>
                        <span class="badge bg-warning text-dark">
                            <?= htmlspecialchars($order['trang_thai']) ?>
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between fs-5">
                        <strong class="text-danger">Tổng tiền:</strong>
                        <strong class="text-danger">
                            <?= number_format($order['tong_tien'], 0, ',', '.') ?>₫
                        </strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-8 mb-4">
        <div class="card shadow border-0">
             <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Các sản phẩm đã mua</h5>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <tbody>
                    <?php foreach ($details as $item): ?>
                        <tr>
                            <td class="text-center" style="width: 100px;">
                                <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($item['hinhanh']) ?>" 
                                     width="80" height="80" class="rounded object-fit-cover">
                            </td>
                            <td>
                                <?= htmlspecialchars($item['ten_sanpham']) ?>
                            </td>
                            <td class="text-end text-muted">
                                SL: <?= $item['so_luong'] ?>
                            </td>
                            <td class="text-end">
                                <?= number_format($item['don_gia_luc_mua'], 0, ',', '.') ?>₫
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>