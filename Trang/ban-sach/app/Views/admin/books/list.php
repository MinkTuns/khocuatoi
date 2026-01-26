<?php $pageTitle = 'Quản Lý Sách'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Danh Sách Sách</h1>

    <div class="admin-actions">
        <a href="?page=admin_add_book" class="btn btn-primary"><i class="fas fa-plus"></i> Thêm Sách Mới</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu Đề</th>
                <th>Tác Giả</th>
                <th>Danh Mục</th>
                <th>Giá</th>
                <th>Số Lượng</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo $book['id']; ?></td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['category_name']); ?></td>
                    <td><?php echo number_format($book['price'], 0, ',', '.'); ?>₫</td>
                    <td><?php echo $book['quantity']; ?></td>
                    <td>
                        <a href="?page=admin_edit_book&id=<?php echo $book['id']; ?>" class="btn btn-small btn-info"><i class="fas fa-edit"></i> Sửa</a>
                        <a href="?page=delete_book&id=<?php echo $book['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Xác nhận xóa?');"><i class="fas fa-trash"></i> Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
