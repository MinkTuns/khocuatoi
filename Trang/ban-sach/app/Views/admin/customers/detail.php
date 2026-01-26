<?php $pageTitle = 'Chi Tiết Khách Hàng'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Chi Tiết Khách Hàng</h1>

    <div class="customer-info">
        <h2><?php echo htmlspecialchars($customer['fullname']); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($customer['username']); ?></p>
        <p><strong>Điện Thoại:</strong> <?php echo htmlspecialchars($customer['phone']); ?></p>
        <p><strong>Địa Chỉ:</strong> <?php echo htmlspecialchars($customer['address']); ?></p>
        <p><strong>Trạng Thái:</strong> 
            <span class="badge <?php echo $customer['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                <?php echo $customer['is_active'] ? 'Hoạt Động' : 'Bị Khóa'; ?>
            </span>
        </p>
        <p><strong>Ngày Tạo:</strong> <?php echo date('d/m/Y', strtotime($customer['created_at'])); ?></p>
    </div>

    <h2>Lịch Sử Đơn Hàng</h2>
    <?php if (!empty($orders)): ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Ngày Đặt</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?php echo $order['id']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                        <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</td>
                        <td>
                            <span class="badge badge-<?php echo $order['status']; ?>">
                                <?php echo $order['status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Chưa có đơn hàng</p>
    <?php endif; ?>

    <div class="form-actions">
        <a href="?page=admin_customers" class="btn btn-secondary">Quay Lại</a>
    </div>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
