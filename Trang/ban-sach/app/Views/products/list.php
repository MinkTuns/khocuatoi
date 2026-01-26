<?php $pageTitle = 'Sản Phẩm'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="products-page">
        <h1>Danh Sách Sản Phẩm</h1>

        <div class="products-container">
            <!-- Sidebar Filters -->
            <aside class="products-sidebar">
                <h3>Lọc Sản Phẩm</h3>

                <form method="get" action="?page=products">
                    <!-- Search -->
                    <div class="filter-group">
                        <label>Tìm Kiếm</label>
                        <input type="text" name="keyword" class="form-control" placeholder="Nhập tên sách hoặc tác giả" value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
                    </div>

                    <!-- Category -->
                    <div class="filter-group">
                        <label>Danh Mục</label>
                        <select name="category" class="form-control">
                            <option value="">Tất Cả</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($_GET['category'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="filter-group">
                        <label>Khoảng Giá</label>
                        <div class="price-range">
                            <input type="number" name="min_price" class="form-control" placeholder="Từ" value="<?php echo htmlspecialchars($_GET['min_price'] ?? ''); ?>">
                            <span>-</span>
                            <input type="number" name="max_price" class="form-control" placeholder="Đến" value="<?php echo htmlspecialchars($_GET['max_price'] ?? ''); ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Tìm Kiếm</button>
                    <a href="?page=products" class="btn btn-secondary btn-block">Xóa Bộ Lọc</a>
                </form>
            </aside>

            <!-- Products List -->
            <section class="products-content">
                <?php if (!empty($books)): ?>
                    <div class="books-grid">
                        <?php foreach ($books as $book): ?>
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
                                    <p class="quantity">
                                        <?php if ($book['quantity'] > 0): ?>
                                            <span class="in-stock">Còn hàng</span>
                                        <?php else: ?>
                                            <span class="out-of-stock">Hết hàng</span>
                                        <?php endif; ?>
                                    </p>
                                    <div class="book-footer">
                                        <span class="price"><?php echo number_format($book['price'], 0, ',', '.'); ?>₫</span>
                                        <a href="?page=product_detail&id=<?php echo $book['id']; ?>" class="btn btn-small">Chi Tiết</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <p>Không tìm thấy sản phẩm nào</p>
                        <a href="?page=products" class="btn btn-primary">Xem tất cả sản phẩm</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
