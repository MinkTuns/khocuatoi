<?php $pageTitle = 'Sửa Sách'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Sửa Sách: <?php echo htmlspecialchars($book['title']); ?></h1>

    <form method="post" action="?page=edit_book_process" enctype="multipart/form-data" class="admin-form">
        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">

        <div class="form-group">
            <label for="title">Tiêu Đề Sách</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="author">Tác Giả</label>
            <input type="text" id="author" name="author" class="form-control" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        </div>

        <div class="form-group">
            <label for="category_id">Danh Mục</label>
            <select id="category_id" name="category_id" class="form-control" required>
                <option value="">-- Chọn Danh Mục --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $book['category_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo $book['price']; ?>" required>
        </div>

        <div class="form-group">
            <label for="quantity">Số Lượng</label>
            <input type="number" id="quantity" name="quantity" class="form-control" value="<?php echo $book['quantity']; ?>" required>
        </div>

        <div class="form-group">
            <label for="published_year">Năm Xuất Bản</label>
            <input type="number" id="published_year" name="published_year" class="form-control" min="1900" max="9999" value="<?php echo $book['published_year']; ?>">
        </div>

        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?php echo htmlspecialchars($book['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="image">Hình Ảnh</label>
            <?php if ($book['image_url']): ?>
                <div class="current-image">
                    <img src="<?php echo htmlspecialchars($book['image_url']); ?>" style="max-width: 200px;">
                    <p>Hình ảnh hiện tại</p>
                </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label><input type="checkbox" name="is_featured" <?php echo $book['is_featured'] ? 'checked' : ''; ?>> Sách Nổi Bật</label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="?page=admin_books" class="btn btn-secondary">Quay Lại</a>
        </div>
    </form>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
