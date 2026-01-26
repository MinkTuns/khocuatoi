<?php $pageTitle = 'Quản Lý Đơn Hàng'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Danh Sách Đơn Hàng</h1>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Mã Đơn</th>
                <th>Khách Hàng</th>
                <th>Ngày Đặt</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php 
                        $customer = $GLOBALS['userModel']->getById($order['user_id']);
                        echo htmlspecialchars($customer['fullname']); 
                    ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                    <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</td>
                    <td>
                        <span class="badge status-<?php echo $order['status']; ?>">
                            <?php 
                            $statusLabels = [
                                'pending' => 'Chờ Xác Nhận',
                                'confirmed' => 'Đã Xác Nhận',
                                'shipped' => 'Đang Giao',
                                'delivered' => 'Đã Giao',
                                'cancelled' => 'Đã Hủy'
                            ];
                            echo $statusLabels[$order['status']] ?? $order['status'];
                            ?>
                        </span>
                    </td>
                    <td>
                        <a href="?page=admin_order_detail&id=<?php echo $order['id']; ?>" class="btn btn-small btn-info"><i class="fas fa-eye"></i> Chi Tiết</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
