<?php $pageTitle = 'Thanh Toán'; include 'app/Views/layouts/header.php'; ?>

<main class="container">
    <section class="checkout-page">
        <h1>Thanh Toán</h1>

        <div class="checkout-container">
            <form method="post" action="?page=process_checkout" class="checkout-form">
                <div class="form-section">
                    <h2>Thông Tin Giao Hàng</h2>

                    <div class="form-group">
                        <label for="shipping_address">Địa Chỉ Giao Hàng</label>
                        <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="phone">Số Điện Thoại</label>
                        <input type="tel" id="phone" name="phone" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="note">Ghi Chú (Tùy Chọn)</label>
                        <textarea id="note" name="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="order-summary">
                    <h2>Tóm Tắt Đơn Hàng</h2>
                    
                    <div class="order-items">
                        <?php $total = 0; ?>
                        <?php foreach ($cartItems as $item): ?>
                            <div class="order-item">
                                <span><?php echo htmlspecialchars($item['title']); ?> x<?php echo $item['quantity']; ?></span>
                                <span><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>₫</span>
                            </div>
                            <?php $total += $item['price'] * $item['quantity']; ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-total">
                        <h3>Tổng Cộng: <strong><?php echo number_format($totalPrice, 0, ',', '.'); ?>₫</strong></h3>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">Xác Nhận Đặt Hàng</button>
                </div>
            </form>
        </div>
    </section>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
