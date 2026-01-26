<?php $pageTitle = 'Giỏ Hàng'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="cart-page">
        <h1>Giỏ Hàng</h1>

        <?php if (!empty($cartItems)): ?>
            <div class="cart-container">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sách</th>
                            <th>Giá</th>
                            <th>Số Lượng</th>
                            <th>Thành Tiền</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td>
                                    <a href="?page=product_detail&id=<?php echo $item['book_id']; ?>">
                                        <?php echo htmlspecialchars($item['title']); ?>
                                    </a>
                                </td>
                                <td><?php echo number_format($item['price'], 0, ',', '.'); ?>₫</td>
                                <td>
                                    <form method="post" action="?page=update_cart_quantity" class="quantity-form">
                                        <input type="hidden" name="cart_item_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                                        <button type="submit" class="btn btn-small">Cập Nhật</button>
                                    </form>
                                </td>
                                <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫</td>
                                <td>
                                    <a href="?page=remove_from_cart&id=<?php echo $item['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?');">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="cart-summary">
                    <h3>Tóm Tắt Đơn Hàng</h3>
                    <div class="summary-row">
                        <span>Tổng Tiền:</span>
                        <strong><?php echo number_format($totalPrice, 0, ',', '.'); ?>₫</strong>
                    </div>
                    <div class="summary-actions">
                        <a href="?page=products" class="btn btn-secondary">Tiếp Tục Mua Sắm</a>
                        <a href="?page=checkout" class="btn btn-primary">Thanh Toán</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <p><i class="fas fa-shopping-cart"></i></p>
                <h2>Giỏ Hàng Của Bạn Trống</h2>
                <a href="?page=products" class="btn btn-primary">Mua Sách Ngay</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
