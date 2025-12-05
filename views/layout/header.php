<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title'] ?? 'Trang chủ') ?> | ShopPhoneHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css"> 
</head>
<body class="d-flex flex-column min-vh-100">
    
    <header class="main-header sticky-top bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand fw-bold text-primary" href="<?= BASE_URL ?>">
                <i class="fas fa-mobile-alt me-2"></i> ShopPhoneHub
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/product/list">Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/page/about">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/page/contact">Liên hệ</a>
                    </li>
                </ul>

                <form class="d-flex me-3" action="<?= BASE_URL ?>/product/search" method="GET">
                    <div class="input-group">
                        <input class="form-control border-end-0" type="search" name="keyword" placeholder="Tìm kiếm..." required>
                        <button class="btn btn-outline-secondary border-start-0" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <div class="d-flex align-items-center">
                    
                    <?php
                    $cart_count = 0;
                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $qty) {
                            $cart_count += $qty;
                        }
                    }
                    ?>
                    <a href="<?= BASE_URL ?>/cart/view" class="btn btn-outline-primary position-relative me-3 border-0">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <?php if ($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $cart_count ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <div class="dropdown">
                        <?php if (isset($_SESSION['user'])): ?>
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center border-0" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fs-4 me-2 text-primary"></i> 
                                <span class="fw-bold text-dark"><?= htmlspecialchars($_SESSION['user']['ho_ten']) ?></span>
                            </button>
                            
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><h6 class="dropdown-header">Tài khoản</h6></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/auth/profile"><i class="fas fa-id-card me-2"></i> Hồ sơ cá nhân</a></li>
                                <li><a class="dropdown-item" href="<?= BASE_URL ?>/order/history"><i class="fas fa-history me-2"></i> Lịch sử đơn hàng</a></li>
                                
                                <?php if (($_SESSION['user']['role'] ?? '') === 'admin'): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header text-danger"><i class="fas fa-shield-alt me-2"></i> Quản trị viên</h6></li>
                                    <li><a class="dropdown-item text-danger fw-bold" href="<?= BASE_URL ?>/admin/dashboard"><i class="fas fa-tachometer-alt me-2"></i> Quản trị viên</a></li>
                                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/product/add"><i class="fas fa-plus-circle me-2"></i> Thêm sản phẩm mới</a></li>
                                    <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/product/list"><i class="fas fa-list me-2"></i> Quản lý sản phẩm</a></li>
                                <?php endif; ?>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/auth/logout"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li> 
                            </ul>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/auth/login" class="btn btn-primary px-4 rounded-pill">
                                <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                            </a>
                        <?php endif; ?>
                    </div>

                </div> 
            </div> 
        </nav>
    </header>
    
    <main class="container py-4 flex-grow-1">