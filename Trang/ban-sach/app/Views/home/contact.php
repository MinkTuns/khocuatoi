<?php $pageTitle = 'Liên Hệ'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="contact-page">
        <h1>Liên Hệ Chúng Tôi</h1>
        
        <div class="contact-content">
            <div class="contact-info">
                <h3>Thông Tin Liên Hệ</h3>
                <p>
                    <strong><i class="fas fa-envelope"></i> Email:</strong><br>
                    info@bookstore.com
                </p>
                <p>
                    <strong><i class="fas fa-phone"></i> Điện Thoại:</strong><br>
                    (84) 123 456 789
                </p>
                <p>
                    <strong><i class="fas fa-map-marker-alt"></i> Địa Chỉ:</strong><br>
                    123 Đường ABC, Quận 1<br>
                    TP. Hồ Chí Minh, Việt Nam
                </p>
                <p>
                    <strong><i class="fas fa-clock"></i> Giờ Hoạt Động:</strong><br>
                    Thứ 2 - Thứ 6: 8:00 - 17:00<br>
                    Thứ 7 - Chủ Nhật: 9:00 - 16:00
                </p>
            </div>

            <div class="contact-form">
                <h3>Gửi Tin Nhắn Cho Chúng Tôi</h3>
                <form method="post">
                    <div class="form-group">
                        <label for="name">Họ Và Tên</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Chủ Đề</label>
                        <input type="text" id="subject" name="subject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Nội Dung</label>
                        <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi Tin Nhắn</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
