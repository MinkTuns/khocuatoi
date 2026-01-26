<?php
/**
 * Cart Controller
 * Quản lý giỏ hàng
 */
class CartController {
    private $cartModel;
    private $bookModel;
    private $orderModel;

    public function __construct($cartModel, $bookModel, $orderModel) {
        $this->cartModel = $cartModel;
        $this->bookModel = $bookModel;
        $this->orderModel = $orderModel;
    }

    /**
     * Kiểm tra đã đăng nhập
     */
    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập trước';
            header('Location: ?page=login');
            exit;
        }
    }

    /**
     * Hiển thị giỏ hàng
     */
    public function view() {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];

        $cartItems = $this->cartModel->getByUserId($userId);
        $totalPrice = $this->cartModel->getTotalPrice($userId);

        include 'app/Views/cart/view.php';
    }

    /**
     * Thêm vào giỏ hàng
     */
    public function add() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Request không hợp lệ';
            header('Location: ?page=cart');
            exit;
        }

        $bookId = (int)($_POST['book_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($bookId <= 0 || $quantity <= 0) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: ?page=products');
            exit;
        }

        // Kiểm tra sách tồn tại
        $book = $this->bookModel->getById($bookId);
        if (!$book) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ?page=products');
            exit;
        }

        // Kiểm tra số lượng
        if (!$this->bookModel->checkStock($bookId, $quantity)) {
            $_SESSION['error'] = 'Số lượng hàng không đủ';
            header('Location: ?page=product_detail&id=' . $bookId);
            exit;
        }

        // Thêm vào giỏ
        $userId = $_SESSION['user_id'];
        if ($this->cartModel->addItem($userId, $bookId, $quantity)) {
            $_SESSION['success'] = 'Thêm vào giỏ hàng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi khi thêm vào giỏ hàng';
        }

        header('Location: ?page=product_detail&id=' . $bookId);
        exit;
    }

    /**
     * Cập nhật số lượng item
     */
    public function updateQuantity() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Request không hợp lệ';
            header('Location: ?page=cart');
            exit;
        }

        $cartItemId = (int)($_POST['cart_item_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($cartItemId <= 0 || $quantity <= 0) {
            $_SESSION['error'] = 'Dữ liệu không hợp lệ';
            header('Location: ?page=cart');
            exit;
        }

        $this->cartModel->updateQuantity($cartItemId, $quantity);
        $_SESSION['success'] = 'Cập nhật số lượng thành công!';

        header('Location: ?page=cart');
        exit;
    }

    /**
     * Xóa item khỏi giỏ
     */
    public function remove() {
        $this->requireLogin();

        $cartItemId = (int)($_GET['id'] ?? 0);
        if ($cartItemId <= 0) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?page=cart');
            exit;
        }

        if ($this->cartModel->removeItem($cartItemId)) {
            $_SESSION['success'] = 'Xóa sản phẩm thành công!';
        }

        header('Location: ?page=cart');
        exit;
    }

    /**
     * Trang thanh toán
     */
    public function checkout() {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getByUserId($userId);

        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng trống';
            header('Location: ?page=cart');
            exit;
        }

        $totalPrice = $this->cartModel->getTotalPrice($userId);

        include 'app/Views/cart/checkout.php';
    }

    /**
     * Xử lý thanh toán
     */
    public function processCheckout() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=checkout');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getByUserId($userId);

        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng trống';
            header('Location: ?page=cart');
            exit;
        }

        // Validate dữ liệu
        $shippingAddress = trim($_POST['shipping_address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $note = trim($_POST['note'] ?? '');

        if (empty($shippingAddress) || empty($phone)) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin giao hàng';
            header('Location: ?page=checkout');
            exit;
        }

        try {
            // Tính tổng tiền
            $totalPrice = $this->cartModel->getTotalPrice($userId);

            // Tạo đơn hàng
            $orderId = $this->orderModel->create($userId, $totalPrice, $shippingAddress, $phone, $note);

            if (!$orderId) {
                throw new Exception('Không thể tạo đơn hàng');
            }

            // Thêm chi tiết đơn hàng và giảm stock
            foreach ($cartItems as $item) {
                $this->orderModel->addDetail($orderId, $item['book_id'], $item['quantity'], $item['price']);
                $this->bookModel->decreaseStock($item['book_id'], $item['quantity']);
            }

            // Xóa giỏ hàng
            $this->cartModel->clearCart($userId);

            $_SESSION['success'] = 'Đặt hàng thành công! Mã đơn hàng: #' . $orderId;
            header('Location: ?page=order_detail&id=' . $orderId);
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi: ' . $e->getMessage();
            header('Location: ?page=checkout');
            exit;
        }
    }
}
