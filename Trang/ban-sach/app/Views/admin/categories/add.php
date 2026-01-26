<?php $pageTitle = 'Thêm Danh Mục'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Thêm Danh Mục Mới</h1>

    <form method="post" action="?page=add_category_process" class="admin-form">
        <div class="form-group">
            <label for="name">Tên Danh Mục</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Thêm Danh Mục</button>
            <a href="?page=admin_categories" class="btn btn-secondary">Quay Lại</a>
        </div>
    </form>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
