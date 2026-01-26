<?php $pageTitle = 'Quản Lý Danh Mục'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Danh Sách Danh Mục</h1>

    <div class="admin-actions">
        <a href="?page=admin_add_category" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm Danh Mục</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Danh Mục</th>
                <th>Mô Tả</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?php echo $cat['id']; ?></td>
                    <td><?php echo htmlspecialchars($cat['name']); ?></td>
                    <td><?php echo htmlspecialchars($cat['description']); ?></td>
                    <td>
                        <a href="?page=admin_edit_category&id=<?php echo $cat['id']; ?>" class="btn btn-small btn-info"><i class="fas fa-edit"></i> Sửa</a>
                        <a href="?page=delete_category&id=<?php echo $cat['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Xác nhận xóa?');"><i class="fas fa-trash"></i> Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
