<?php $pageTitle = 'Chi Tiết Đơn Hàng'; include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') { header('Location: ?page=home'); exit; }
?>

<main class="container admin-page">
    <h1>Chi Tiết Đơn Hàng #<?php echo $order['id']; ?></h1>

    <div class="order-info">
        <h2>Thông Tin Đơn Hàng</h2>
        <p><strong>Mã Đơn:</strong> #<?php echo $order['id']; ?></p>
        <p><strong>Khách Hàng:</strong> <?php echo htmlspecialchars($customer['fullname']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
        <p><strong>Ngày Đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
        <p><strong>Địa Chỉ Giao:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
        <p><strong>Điện Thoại:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
    </div>

    <h2>Sản Phẩm</h2>
    <table class="admin-table">
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

    <div class="order-total">
        <h3>Tổng Cộng: <strong><?php echo number_format($order['total_price'], 0, ',', '.'); ?>₫</strong></h3>
    </div>

    <div class="status-update">
        <h2>Cập Nhật Trạng Thái</h2>
        <form method="post" action="?page=update_order_status">
            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
            <div class="form-group">
                <label for="status">Trạng Thái</label>
                <select id="status" name="status" class="form-control">
                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Chờ Xác Nhận</option>
                    <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'selected' : ''; ?>>Đã Xác Nhận</option>
                    <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Đang Giao</option>
                    <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Đã Giao</option>
                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Đã Hủy</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>

    <div class="form-actions">
        <a href="?page=admin_orders" class="btn btn-secondary">Quay Lại</a>
    </div>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
