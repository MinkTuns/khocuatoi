<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Cửa Hàng Bán Sách Online'; ?></title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-top">
                <div class="logo">
                    <h1><i class="fas fa-book"></i> BookStore</h1>
                </div>
                <div class="header-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="user-info">
                            <span class="welcome">Xin chào, <?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="?page=admin_dashboard" class="btn-admin"><i class="fas fa-cog"></i> Quản Trị</a>
                            <?php endif; ?>
                            <a href="?page=profile" class="btn-profile"><i class="fas fa-user"></i> Tài Khoản</a>
                            <a href="?page=logout" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a>
                        </div>
                    <?php else: ?>
                        <div class="auth-links">
                            <a href="?page=login" class="btn-login"><i class="fas fa-sign-in-alt"></i> Đăng Nhập</a>
                            <a href="?page=register" class="btn-register"><i class="fas fa-user-plus"></i> Đăng Ký</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="navbar">
                <ul>
                    <li><a href="?page=home">Trang Chủ</a></li>
                    <li><a href="?page=products">Sản Phẩm</a></li>
                    <li><a href="?page=contact">Liên Hệ</a></li>
                    <li><a href="?page=about">Về Chúng Tôi</a></li>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin'): ?>
                        <li><a href="?page=order_history">Lịch Sử</a></li>
                        <li><a href="?page=cart"><i class="fas fa-shopping-cart"></i> Giỏ Hàng</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Messages -->
    <div class="container mt-3">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <i class="fas fa-times-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <i class="fas fa-times-circle"></i>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="close-btn" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>
    </div>
</body>
</html>
