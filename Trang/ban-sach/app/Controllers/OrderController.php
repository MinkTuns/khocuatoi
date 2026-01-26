<?php
/**
 * Order Controller
 * Quản lý đơn hàng khách hàng
 */
class OrderController {
    private $orderModel;
    private $userModel;

    public function __construct($orderModel, $userModel) {
        $this->orderModel = $orderModel;
        $this->userModel = $userModel;
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
     * Lịch sử đơn hàng
     */
    public function history() {
        $this->requireLogin();

        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getByUserId($userId);

        include 'app/Views/orders/history.php';
    }

    /**
     * Chi tiết đơn hàng
     */
    public function detail() {
        $this->requireLogin();

        if (!isset($_GET['id'])) {
            header('Location: ?page=order_history');
            exit;
        }

        $orderId = (int)$_GET['id'];
        $order = $this->orderModel->getById($orderId);

        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ?page=order_history');
            exit;
        }

        // Kiểm tra quyền xem
        if ($order['user_id'] !== $_SESSION['user_id']) {
            $_SESSION['error'] = 'Bạn không có quyền xem đơn hàng này';
            header('Location: ?page=order_history');
            exit;
        }

        $orderDetails = $this->orderModel->getDetails($orderId);
        $user = $this->userModel->getById($order['user_id']);

        include 'app/Views/orders/detail.php';
    }

    /**
     * Thông tin cá nhân
     */
    public function profile() {
        $this->requireLogin();

        $user = $this->userModel->getById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $address = trim($_POST['address'] ?? '');

            if (empty($fullname) || empty($email)) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin';
            } else {
                $updateData = [
                    'fullname' => $fullname,
                    'email' => $email,
                    'phone' => $phone,
                    'address' => $address
                ];

                if ($this->userModel->update($_SESSION['user_id'], $updateData)) {
                    $_SESSION['success'] = 'Cập nhật thông tin thành công!';
                    $_SESSION['fullname'] = $fullname;
                    header('Location: ?page=profile');
                    exit;
                } else {
                    $_SESSION['error'] = 'Lỗi khi cập nhật thông tin';
                }
            }
        }

        $user = $this->userModel->getById($_SESSION['user_id']);
        include 'app/Views/orders/profile.php';
    }
}
