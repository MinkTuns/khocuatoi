<?php $pageTitle = 'Quản Lý Khách Hàng'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Danh Sách Khách Hàng</h1>

    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Điện Thoại</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?php echo $customer['id']; ?></td>
                    <td><?php echo htmlspecialchars($customer['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                    <td>
                        <span class="badge <?php echo $customer['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo $customer['is_active'] ? 'Hoạt Động' : 'Bị Khóa'; ?>
                        </span>
                    </td>
                    <td>
                        <a href="?page=admin_customer_detail&id=<?php echo $customer['id']; ?>" class="btn btn-small btn-info"><i class="fas fa-eye"></i> Xem</a>
                        <a href="?page=toggle_customer_status&id=<?php echo $customer['id']; ?>" class="btn btn-small btn-warning" onclick="return confirm('Xác nhận?');">
                            <?php echo $customer['is_active'] ? 'Khóa' : 'Mở'; ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
