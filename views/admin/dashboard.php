<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-stat border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 fw-bold text-uppercase" style="font-size: 0.8rem; color: rgba(255,255,255,0.9);">Tổng doanh thu</p>
                        <h3 class="fw-bold mb-0 text-white"><?= number_format($stats['revenue'] ?? 0, 0, ',', '.') ?>đ</h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2);">
                        <i class="fas fa-wallet fa-lg text-white"></i>
                    </div>
                </div>
                <small class="mt-3 d-block text-white" style="opacity: 0.9;"><i class="fas fa-arrow-up"></i> Tăng trưởng tháng này</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stat border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 fw-bold text-uppercase" style="font-size: 0.8rem; color: rgba(255,255,255,0.9);">Đơn hàng mới</p>
                        <h3 class="fw-bold mb-0 text-white"><?= number_format($stats['orders'] ?? 0, 0, ',', '.') ?></h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2);">
                        <i class="fas fa-shopping-bag fa-lg text-white"></i>
                    </div>
                </div>
                <small class="mt-3 d-block text-white" style="opacity: 0.9;">Đang xử lý tích cực</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stat border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 fw-bold text-uppercase" style="font-size: 0.8rem; color: rgba(255,255,255,0.9);">Kho hàng</p>
                        <h3 class="fw-bold mb-0 text-white"><?= number_format($stats['products'] ?? 0, 0, ',', '.') ?></h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2);">
                        <i class="fas fa-box fa-lg text-white"></i>
                    </div>
                </div>
                <small class="mt-3 d-block text-white" style="opacity: 0.9;">Sản phẩm đang kinh doanh</small>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card card-stat border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1 fw-bold text-uppercase" style="font-size: 0.8rem; color: rgba(255,255,255,0.9);">Khách hàng</p>
                        <h3 class="fw-bold mb-0 text-white"><?= number_format($stats['users'] ?? 0, 0, ',', '.') ?></h3>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: rgba(255,255,255,0.2);">
                        <i class="fas fa-users fa-lg text-white"></i>
                    </div>
                </div>
                <small class="mt-3 d-block text-white" style="opacity: 0.9;">Người dùng hệ thống</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0"><i class="fas fa-chart-line me-2 text-primary"></i> Biểu đồ doanh thu 6 tháng</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" style="max-height: 350px;"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold m-0"><i class="fas fa-bolt me-2 text-warning"></i> Thao tác nhanh</h6>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?= BASE_URL ?>/admin/users" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 text-primary"><i class="fas fa-user-check"></i></div>
                    <div>
                        <h6 class="m-0 text-dark">Duyệt thành viên</h6>
                        <small class="text-muted">Kiểm tra tài khoản mới</small>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>/product/add" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-2 me-3 text-success"><i class="fas fa-plus"></i></div>
                    <div>
                        <h6 class="m-0 text-dark">Thêm sản phẩm</h6>
                        <small class="text-muted">Nhập hàng mới vào kho</small>
                    </div>
                </a>
                <a href="<?= BASE_URL ?>/admin/inventory" class="list-group-item list-group-item-action py-3 d-flex align-items-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-2 me-3 text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                    <div>
                        <h6 class="m-0 text-dark">Cảnh báo kho</h6>
                        <small class="text-muted">Xem sản phẩm sắp hết</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4 mt-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold m-0"><i class="fas fa-receipt me-2 text-secondary"></i> Đơn hàng vừa đặt</h6>
        <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm btn-outline-primary rounded-pill px-3">Xem tất cả</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($recent_orders) && is_array($recent_orders)): foreach($recent_orders as $order): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-primary">#<?= htmlspecialchars($order['id'] ?? 'N/A') ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php 
                                $ho_ten = $order['ho_ten'] ?? 'Khách hàng';
                                $first_char = !empty($ho_ten) ? strtoupper(mb_substr($ho_ten, 0, 1, 'UTF-8')) : 'K';
                                ?>
                                <div class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                    <?= htmlspecialchars($first_char) ?>
                                </div>
                                <?= htmlspecialchars($ho_ten) ?>
                            </div>
                        </td>
                        <td class="text-muted small">
                            <?php 
                            $ngay_tao = $order['ngay_tao'] ?? null;
                            if ($ngay_tao && strtotime($ngay_tao) !== false) {
                                echo htmlspecialchars(date('d/m/Y H:i', strtotime($ngay_tao)));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td class="fw-bold text-dark"><?= number_format($order['tong_tien'] ?? 0, 0, ',', '.') ?>đ</td>
                        <td>
                            <?php 
                            $trang_thai = $order['trang_thai'] ?? 'Chưa xác định';
                            // Xác định màu badge dựa trên trạng thái
                            $badge_class = 'bg-soft-warning text-warning border-warning';
                            if ($trang_thai === 'Đã giao') {
                                $badge_class = 'bg-soft-success text-success border-success';
                            } elseif ($trang_thai === 'Đã hủy') {
                                $badge_class = 'bg-soft-danger text-danger border-danger';
                            } elseif ($trang_thai === 'Đang giao') {
                                $badge_class = 'bg-soft-info text-info border-info';
                            }
                            ?>
                            <span class="badge rounded-pill border <?= $badge_class ?>">
                                <?= htmlspecialchars($trang_thai) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center py-4 text-muted">Chưa có đơn hàng nào</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Kiểm tra Chart.js đã được tải chưa
    if (typeof Chart === 'undefined') {
        console.error('Chart.js library chưa được tải. Vui lòng kiểm tra lại.');
        return;
    }

    const chartElement = document.getElementById('revenueChart');
    if (!chartElement) {
        console.error('Không tìm thấy phần tử biểu đồ.');
        return;
    }

    try {
        const ctx = chartElement.getContext('2d');
        
        // Dữ liệu từ PHP truyền sang
        const chartData = <?= $chart_data ?? '[]' ?>;
        const chartLabels = <?= $chart_labels ?? '[]' ?>;

        // Kiểm tra dữ liệu hợp lệ
        if (!Array.isArray(chartData) || !Array.isArray(chartLabels)) {
            console.error('Dữ liệu biểu đồ không hợp lệ.');
            return;
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels.length > 0 ? chartLabels : ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: chartData,
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    tension: 0.3, // Đường cong mềm mại
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: false 
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { 
                            borderDash: [2, 2],
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    } catch (error) {
        console.error('Lỗi khi khởi tạo biểu đồ:', error);
    }
});
</script>