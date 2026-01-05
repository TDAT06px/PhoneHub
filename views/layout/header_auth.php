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
        /* Force auth background in case external CSS is cached/overridden */
        body.auth-page {
            background: radial-gradient(circle, rgba(255,130,168,0.9) 10%, #ffffff 23%, rgba(255,158,203,0.9) 36%, #ffffff 52%, rgba(255,105,157,0.95) 68%, rgba(255,182,193,0.95) 85%, #ff1493 100%) !important;
            background-attachment: fixed !important;
            background-size: cover !important;
            background-position: center !important;
            background-repeat: no-repeat !important;
        }
    </style>
</head>
<body class="auth-page d-flex flex-column min-vh-100">
    <main class="container py-5 flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="auth-container">
