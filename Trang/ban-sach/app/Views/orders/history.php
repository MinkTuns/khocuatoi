<?php $pageTitle = 'Lịch Sử Đơn Hàng'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="orders-page">
        <h1>Lịch Sử Đơn Hàng</h1>

        <?php if (!empty($orders)): ?>
            <div class="orders-list">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
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
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
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
                                    <a href="?page=order_detail&id=<?php echo $order['id']; ?>" class="btn btn-small">Chi Tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-orders">
                <p><i class="fas fa-box"></i></p>
                <h2>Chưa Có Đơn Hàng Nào</h2>
                <a href="?page=products" class="btn btn-primary">Mua Sách Ngay</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
