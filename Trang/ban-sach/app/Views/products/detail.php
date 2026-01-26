<?php $pageTitle = $book['title'] . ' - Chi Tiết Sản Phẩm'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="product-detail">
        <div class="breadcrumb">
            <a href="?page=home">Trang Chủ</a> / 
            <a href="?page=products">Sản Phẩm</a> / 
            <span><?php echo htmlspecialchars($book['title']); ?></span>
        </div>

        <div class="product-detail-container">
            <div class="product-image">
                <?php if ($book['image_url']): ?>
                    <img src="<?php echo htmlspecialchars($book['image_url']); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
                <?php else: ?>
                    <img src="public/images/no-image.png" alt="No Image">
                <?php endif; ?>
            </div>

            <div class="product-info">
                <h1><?php echo htmlspecialchars($book['title']); ?></h1>
                
                <div class="product-meta">
                    <p><strong>Tác Giả:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
                    <p><strong>Danh Mục:</strong> <span class="badge"><?php echo htmlspecialchars($book['category_name']); ?></span></p>
                    <p><strong>Năm Xuất Bản:</strong> <?php echo $book['published_year'] ?? 'N/A'; ?></p>
                </div>

                <div class="product-price">
                    <h2><?php echo number_format($book['price'], 0, ',', '.'); ?>₫</h2>
                </div>

                <div class="product-status">
                    <?php if ($book['quantity'] > 0): ?>
                        <span class="in-stock"><i class="fas fa-check-circle"></i> Còn hàng (<?php echo $book['quantity']; ?> cuốn)</span>
                    <?php else: ?>
                        <span class="out-of-stock"><i class="fas fa-times-circle"></i> Hết hàng</span>
                    <?php endif; ?>
                </div>

                <div class="product-description">
                    <h3>Mô Tả Sản Phẩm</h3>
                    <p><?php echo nl2br(htmlspecialchars($book['description'])); ?></p>
                </div>

                <?php if ($book['quantity'] > 0): ?>
                    <form method="post" action="?page=add_to_cart" class="add-to-cart-form">
                        <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                        
                        <div class="quantity-selector">
                            <label for="quantity">Số Lượng:</label>
                            <select name="quantity" id="quantity" class="form-control">
                                <?php for ($i = 1; $i <= min(10, $book['quantity']); $i++): ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-shopping-cart"></i> Thêm Vào Giỏ Hàng
                            </button>
                        <?php else: ?>
                            <a href="?page=login" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt"></i> Đăng Nhập Để Mua
                            </a>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Related Products -->
        <section class="related-products">
            <h2>Sách Cùng Danh Mục</h2>
            <!-- Load related books here -->
        </section>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
