<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; min-height: 100vh; display: flex; }
        
        #sidebar { 
            width: 260px; min-width: 260px;
            background: #212529; color: #fff; 
            min-height: 100vh; display: flex; flex-direction: column;
        }
        #sidebar .sidebar-header { padding: 20px; background: #1a1e21; border-bottom: 1px solid #32383e; }
        #sidebar ul.components { padding: 20px 0; list-style: none; margin: 0; }
        #sidebar ul li a { 
            padding: 12px 20px; display: block; color: #aab0bc; text-decoration: none; font-weight: 500; transition: 0.3s; border-left: 3px solid transparent;
        }
        #sidebar ul li a:hover, #sidebar ul li a.active { color: #fff; background: #2c3034; border-left: 3px solid #0d6efd; }
        
        #content { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .top-bar { background: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .main-content { padding: 30px; flex: 1; overflow-y: auto; }
    </style>
</head>
<body>

    <nav id="sidebar">
        <div class="sidebar-header">
            <h4 class="fw-bold m-0"><i class="fas fa-user-shield me-2"></i>ADMIN</h4>
        </div>
        <ul class="components">
            <li><a href="<?= BASE_URL ?>/admin/dashboard" class="<?= (strpos($_SERVER['REQUEST_URI'], 'dashboard')!==false)?'active':'' ?>"><i class="fas fa-chart-pie me-2"></i> Tổng quan</a></li>
            <li><a href="<?= BASE_URL ?>/admin/orders" class="<?= (strpos($_SERVER['REQUEST_URI'], 'orders')!==false)?'active':'' ?>"><i class="fas fa-shopping-cart me-2"></i> Đơn hàng</a></li>
            <!-- 'Xem trang Sản phẩm' removed as requested -->
            <li><a href="<?= BASE_URL ?>/admin/users" class="<?= (strpos($_SERVER['REQUEST_URI'], 'users')!==false)?'active':'' ?>"><i class="fas fa-users me-2"></i> Nhân sự</a></li>
            <li><a href="<?= BASE_URL ?>/admin/inventory" class="<?= (strpos($_SERVER['REQUEST_URI'], 'inventory')!==false)?'active':'' ?>"><i class="fas fa-warehouse me-2"></i> Kho hàng</a></li>
            <li><a href="<?= BASE_URL ?>/admin/revenue" class="<?= (strpos($_SERVER['REQUEST_URI'], 'revenue')!==false)?'active':'' ?>"><i class="fas fa-coins me-2"></i> Doanh thu</a></li>
            <li class="mt-4 border-top border-secondary pt-3 mx-3"></li>
            <li><a href="<?= BASE_URL ?>" class="text-info"><i class="fas fa-home me-2"></i> Về Shop</a></li>
            <li><a href="<?= BASE_URL ?>/auth/logout" class="text-danger"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
        </ul>
    </nav>

    <div id="content">
        <div class="top-bar">
            <h5 class="m-0 fw-bold text-uppercase text-secondary"><?= $title ?? '' ?></h5>
            <div class="d-flex align-items-center">
                <div class="me-3 text-end">
                    <small class="d-block text-muted">Xin chào,</small>
                    <span class="fw-bold"><?= $_SESSION['user']['ho_ten'] ?? 'Admin' ?></span>
                </div>
                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div class="main-content">
            <?php 
                // Phần này sẽ hiển thị nội dung của các trang con (dashboard, orders...)
                if (isset($child_view) && file_exists($child_view)) {
                    require_once $child_view;
                } else {
                    echo "<div class='alert alert-danger'>Không tìm thấy nội dung view con.</div>";
                }
            ?>
        </div>
        
        <footer class="bg-white p-3 text-center text-muted small border-top">
            &copy; <?= date('Y') ?> Admin Management System
        </footer>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>