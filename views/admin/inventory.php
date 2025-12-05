<div class="card"><div class="card-body">
<table class="table table-bordered">
    <thead class="table-dark"><tr><th>Sản phẩm</th><th>Giá</th><th>Tồn kho</th><th>Trạng thái</th></tr></thead>
    <tbody>
        <?php if(empty($products) || !is_array($products)): ?>
            <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có sản phẩm nào</td></tr>
        <?php else: foreach($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['ten_sanpham'] ?? 'N/A') ?></td>
            <td><?= number_format($p['gia'] ?? 0, 0, ',', '.') ?>đ</td>
            <td class="fw-bold fs-5 text-center"><?= $p['so_luong_ton'] ?? 0 ?></td>
            <td>
                <?php 
                $so_luong = (int)($p['so_luong_ton'] ?? 0);
                if($so_luong == 0): ?><span class="badge bg-danger">Hết hàng</span>
                <?php elseif($so_luong < 10): ?><span class="badge bg-warning text-dark">Sắp hết</span>
                <?php else: ?><span class="badge bg-success">Còn hàng</span><?php endif; ?>
            </td>
        </tr>
        <?php endforeach; endif; ?>
    </tbody>
</table></div></div>