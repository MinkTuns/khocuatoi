<?php $pageTitle = 'Đăng Ký'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="auth-page">
        <div class="auth-form">
            <h2>Đăng Ký Tài Khoản Mới</h2>
            
            <form method="post" action="?page=register_process">
                <div class="form-group">
                    <label for="fullname">Họ Và Tên</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="username">Tên Đăng Nhập</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật Khẩu</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <small>Ít nhất 6 ký tự</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Xác Nhận Mật Khẩu</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Đăng Ký</button>
            </form>

            <p class="auth-link">
                Đã có tài khoản? <a href="?page=login">Đăng nhập tại đây</a>
            </p>
        </div>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
