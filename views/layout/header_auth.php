<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Xác thực') ?> | ShopPhoneHub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">

    <style>
        /* Auth header styles */
        .auth-hero {
            background: linear-gradient(135deg, var(--ah-gradient-1), var(--ah-gradient-2));
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .auth-hero .brand { color: var(--bs-primary, #E91E63); font-weight:700; letter-spacing:0.4px; }
        body.auth-page { background: #fafafa; }
        .auth-container { width: 520px; max-width: 96%; }
        .auth-card { width: 100%; }
        .auth-footer-note { font-size:0.9rem; color: #6b7280; }
        :root {
            --ah-gradient-1: rgba(233,30,99,0.08);
            --ah-gradient-2: rgba(255,64,129,0.06);
        }
        .auth-card .card { border: none; box-shadow: 0 8px 30px rgba(0,0,0,0.06); }
        .auth-card .card-header { background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); color: #fff; border-radius: .5rem .5rem 0 0; }
        .auth-card .form-control:focus { box-shadow: 0 0 0 0.1rem rgba(233,30,99,0.12); border-color: var(--primary-color); }
        .btn-auth-primary { background: linear-gradient(90deg, var(--primary-color), var(--accent-color)); border: none; color: #fff; }
        .btn-auth-primary:hover { filter: brightness(0.95); }
        @media (max-width: 576px) { .auth-container { width: 92%; } .auth-hero .brand { font-size: 1rem; } }
    </style>
</head>
<body class="auth-page d-flex flex-column min-vh-100">

    <header class="auth-hero py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="d-flex align-items-center text-decoration-none brand" href="<?= BASE_URL ?>">
                <i class="fas fa-mobile-alt fa-lg me-2"></i>
                <span class="fs-5">PHONE</span><span class="fs-5 text-muted ms-1">HUB</span>
            </a>

            <div class="d-none d-sm-flex align-items-center gap-3">
                <?php if (!empty($_SESSION['user'])): ?>
                    <span class="text-muted small">Xin chào, <?= htmlspecialchars($_SESSION['user']['ho_ten'] ?? $_SESSION['user']['email'] ?? '') ?></span>
                    <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-outline-secondary btn-sm">Đăng xuất</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container py-5 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="auth-container">
<!-- auth view will be included here -->