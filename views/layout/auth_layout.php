<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['title'] ?? 'Xác thực') ?> | ShopPhoneHub</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
    
    <style>
        /* ========== AUTH LAYOUT STYLING ========== */
        body.auth-page {
            background: linear-gradient(135deg, #E91E63 0%, #FF4081 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .auth-card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            border: none;
            overflow: hidden;
            background: white;
        }

        .auth-card .card-header {
            background: linear-gradient(135deg, #E91E63 0%, #FF4081 100%);
            border: none;
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .auth-card .card-header h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .auth-card .card-header h2 strong {
            color: #fff;
        }

        .auth-card .card-header h2 span {
            font-weight: 300;
            opacity: 0.9;
        }

        .auth-card .card-header .subtitle {
            font-size: 1rem;
            opacity: 0.95;
            margin-top: 10px;
            font-weight: 500;
        }

        .auth-card .card-body {
            padding: 40px 30px;
        }

        .auth-card .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .auth-card .form-control,
        .auth-card .form-select {
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .auth-card .form-control:focus,
        .auth-card .form-select:focus {
            border-color: #E91E63;
            box-shadow: 0 0 0 3px rgba(233, 30, 99, 0.1);
            color: #333;
        }

        .auth-card .form-control::placeholder {
            color: #999;
        }

        .btn-auth {
            background: linear-gradient(135deg, #E91E63 0%, #FF4081 100%);
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
            color: white;
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .auth-card .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 25px;
        }

        .auth-card .alert-danger {
            background-color: #fff5f5;
            color: #c53030;
            padding: 12px 15px;
        }

        .auth-card .alert-success {
            background-color: #f0fdf4;
            color: #166534;
            padding: 12px 15px;
        }

        .auth-card .alert-danger i,
        .auth-card .alert-success i {
            margin-right: 8px;
        }

        .auth-footer {
            text-align: center;
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
        }

        .auth-footer p {
            margin: 0;
            color: #666;
            font-size: 0.95rem;
        }

        .auth-footer a {
            color: #E91E63;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-footer a:hover {
            color: #FF4081;
            text-decoration: underline;
        }

        .form-row-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 576px) {
            .auth-card .card-header {
                padding: 30px 20px;
            }

            .auth-card .card-body {
                padding: 30px 20px;
            }

            .auth-footer {
                padding: 15px 20px;
            }

            .form-row-group {
                grid-template-columns: 1fr;
            }

            .auth-card .card-header h2 {
                font-size: 1.8rem;
            }
        }

        /* ========== ANIMATION ========== */
        .auth-card {
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="auth-page">
    <div class="auth-container">
