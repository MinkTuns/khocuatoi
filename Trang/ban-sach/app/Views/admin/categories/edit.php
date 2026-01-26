<?php $pageTitle = 'Sửa Danh Mục'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Sửa Danh Mục: <?php echo htmlspecialchars($category['name']); ?></h1>

    <form method="post" action="?page=edit_category_process" class="admin-form">
        <input type="hidden" name="id" value="<?php echo $category['id']; ?>">

        <div class="form-group">
            <label for="name">Tên Danh Mục</label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($category['name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea id="description" name="description" class="form-control" rows="3"><?php echo htmlspecialchars($category['description']); ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
            <a href="?page=admin_categories" class="btn btn-secondary">Quay Lại</a>
        </div>
    </form>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
