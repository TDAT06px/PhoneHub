<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title'] ?? 'Quản trị') ?> | Admin Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css"> 

    <style>
        /* CSS cụ thể cho Admin/Auth: Dùng màu Tối/Đen */
        .admin-header {
            background-color: var(--dark-color, #343a40) !important; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    
    <header class="admin-header sticky-top">
        <nav class="navbar navbar-expand-lg container">
            <a class="navbar-brand text-white fw-bold" href="<?= BASE_URL ?>/admin/dashboard">
                <i class="fas fa-tools me-2"></i> ADMIN PANEL
            </a>

            <div class="ms-auto d-flex align-items-center">
                <span class="text-white-50 small me-3">Xin chào, <?= htmlspecialchars($_SESSION['user']['ho_ten'] ?? 'Admin') ?></span>
                
                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                </a>
            </div>
        </nav>
    </header>
    
    <main class="container content-area flex-grow-1">
</body>