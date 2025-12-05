<div class="card"><div class="card-body">
<table class="table table-striped">
    <thead><tr><th>Ngày</th><th>Số đơn</th><th>Doanh thu</th></tr></thead>
    <tbody>
        <?php if(empty($stats) || !is_array($stats)): ?>
            <tr><td colspan="3" class="text-center py-4 text-muted">Chưa có dữ liệu doanh thu</td></tr>
        <?php else: foreach($stats as $row): ?>
        <tr>
            <td>
                <?php 
                $date = $row['date'] ?? null;
                if ($date && strtotime($date) !== false) {
                    echo htmlspecialchars(date('d/m/Y', strtotime($date)));
                } else {
                    echo 'N/A';
                }
                ?>
            </td>
            <td><?= number_format($row['don_hang'] ?? 0, 0, ',', '.') ?></td>
            <td class="fw-bold text-success"><?= number_format($row['doanh_thu'] ?? 0, 0, ',', '.') ?> đ</td>
        </tr>
        <?php endforeach; endif; ?>
    </tbody>
</table></div></div>