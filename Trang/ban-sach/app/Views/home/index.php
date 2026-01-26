<?php $pageTitle = 'Trang Chủ - Cửa Hàng Bán Sách'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <!-- Banner -->
    <section class="banner">
        <div class="banner-content">
            <h1>Chào Mừng Bạn Đến Cửa Hàng Bán Sách Online</h1>
            <p>Khám phá kho sách phong phú với hàng ngàn đầu sách hay từ các tác giả nổi tiếng</p>
            <a href="?page=products" class="btn btn-primary btn-lg">Mua Sắm Ngay</a>
        </div>
    </section>

    <!-- Featured Books -->
    <section class="featured-books">
        <h2>Sách Nổi Bật</h2>
        <div class="books-grid">
            <?php if (!empty($featuredBooks)): ?>
                <?php foreach ($featuredBooks as $book): ?>
                    <div class="book-card">
                        <div class="book-image">
                            <?php if ($book['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                            <?php else: ?>
                                <img src="public/images/no-image.png" alt="No Image">
                            <?php endif; ?>
                        </div>
                        <div class="book-info">
                            <h3><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p class="author">Tác giả: <?php echo htmlspecialchars($book['author']); ?></p>
                            <p class="category"><span class="badge"><?php echo htmlspecialchars($book['category_name']); ?></span></p>
                            <p class="description"><?php echo substr($book['description'], 0, 100) . '...'; ?></p>
                            <div class="book-footer">
                                <span class="price"><?php echo number_format($book['price'], 0, ',', '.'); ?>₫</span>
                                <a href="?page=product_detail&id=<?php echo $book['id']; ?>" class="btn btn-small">Chi Tiết</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Chưa có sách nổi bật</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories">
        <h2>Danh Mục Sách</h2>
        <div class="categories-list">
            <?php foreach ($categories as $cat): ?>
                <a href="?page=products&category=<?php echo $cat['id']; ?>" class="category-card">
                    <h3><?php echo htmlspecialchars($cat['name']); ?></h3>
                    <p><?php echo htmlspecialchars($cat['description']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <h2>Tại Sao Chọn Chúng Tôi?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-truck"></i>
                <h3>Giao Hàng Nhanh</h3>
                <p>Giao hàng toàn quốc trong 3-5 ngày làm việc</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Thanh Toán An Toàn</h3>
                <p>Các phương thức thanh toán an toàn và bảo mật</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-redo"></i>
                <h3>Hoàn Trả Dễ Dàng</h3>
                <p>Chính sách hoàn trả linh hoạt trong 30 ngày</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-headset"></i>
                <h3>Hỗ Trợ 24/7</h3>
                <p>Đội ngũ hỗ trợ khách hàng luôn sẵn sàng giúp</p>
            </div>
        </div>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
