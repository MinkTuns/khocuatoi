<?php $pageTitle = 'Đăng Nhập'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="auth-page">
        <div class="auth-form">
            <h2>Đăng Nhập</h2>
            
            <form method="post" action="?page=login_process">
                <div class="form-group">
                    <label for="username">Tên Đăng Nhập</label>
                    <input type="text" id="username" name="username" class="form-control" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Mật Khẩu</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>
            </form>

            <p class="auth-link">
                Chưa có tài khoản? <a href="?page=register">Đăng ký tại đây</a>
            </p>

            <div class="auth-info">
                <p><strong>Tài khoản Demo:</strong></p>
                <p>Username: <code>admin</code></p>
                <p>Password: <code>123456</code></p>
            </div>
        </div>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
