<?php $pageTitle = 'Chi Tiết Đơn Hàng #' . $order['id']; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="order-detail-page">
        <h1>Chi Tiết Đơn Hàng #<?php echo $order['id']; ?></h1>

        <div class="order-detail-container">
            <div class="order-info">
                <h2>Thông Tin Đơn Hàng</h2>
                <div class="info-row">
                    <span>Mã Đơn:</span>
                    <strong>#<?php echo $order['id']; ?></strong>
                </div>
                <div class="info-row">
                    <span>Ngày Đặt:</span>
                    <strong><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></strong>
                </div>
                <div class="info-row">
                    <span>Trạng Thái:</span>
                    <strong>
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
                    </strong>
                </div>
            </div>

            <div class="shipping-info">
                <h2>Thông Tin Giao Hàng</h2>
                <div class="info-row">
                    <span>Người Nhận:</span>
                    <strong><?php echo htmlspecialchars($user['fullname']); ?></strong>
                </div>
                <div class="info-row">
                    <span>Địa Chỉ:</span>
                    <strong><?php echo htmlspecialchars($order['shipping_address']); ?></strong>
                </div>
                <div class="info-row">
                    <span>Điện Thoại:</span>
                    <strong><?php echo htmlspecialchars($order['phone']); ?></strong>
                </div>
                <?php if ($order['note']): ?>
                    <div class="info-row">
                        <span>Ghi Chú:</span>
                        <strong><?php echo htmlspecialchars($order['note']); ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <div class="order-items">
                <h2>Sản Phẩm Trong Đơn</h2>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Sách</th>
                            <th>Giá</th>
                            <th>Số Lượng</th>
                            <th>Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderDetails as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['title']); ?></td>
                                <td><?php echo number_format($item['unit_price'], 0, ',', '.'); ?>₫</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['subtotal'], 0, ',', '.'); ?>₫</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="order-total">
                <h3>Tổng Cộng: <strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</strong></h3>
            </div>

            <div class="order-actions">
                <a href="?page=order_history" class="btn btn-secondary">Quay Lại</a>
            </div>
        </div>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
