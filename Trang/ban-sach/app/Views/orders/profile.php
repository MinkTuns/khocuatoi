<?php $pageTitle = 'Thông Tin Cá Nhân'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="profile-page">
        <h1>Thông Tin Cá Nhân</h1>

        <div class="profile-container">
            <form method="post" action="?page=profile" class="profile-form">
                <div class="form-group">
                    <label for="fullname">Họ Và Tên</label>
                    <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Số Điện Thoại</label>
                    <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="address">Địa Chỉ</label>
                    <textarea id="address" name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label>Tên Đăng Nhập</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    <small>Không thể thay đổi</small>
                </div>

                <div class="form-group">
                    <label>Ngày Tạo Tài Khoản</label>
                    <input type="text" class="form-control" value="<?php echo date('d/m/Y', strtotime($user['created_at'])); ?>" disabled>
                </div>

                <button type="submit" class="btn btn-primary">Cập Nhật Thông Tin</button>
            </form>
        </div>

        <div class="profile-links">
            <h2>Liên Kết Nhanh</h2>
            <ul>
                <li><a href="?page=order_history">Xem Lịch Sử Đơn Hàng</a></li>
                <li><a href="?page=products">Tiếp Tục Mua Sắm</a></li>
            </ul>
        </div>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
