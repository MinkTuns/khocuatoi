<?php
/**
 * Order Model
 * Quản lý đơn hàng
 */
class Order {
    private $pdo;
    private $orderTable = 'orders';
    private $orderDetailsTable = 'order_details';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả đơn hàng
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->orderTable} ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy đơn hàng theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->orderTable} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy chi tiết đơn hàng
     */
    public function getDetails($orderId) {
        $sql = "SELECT od.*, b.title, b.author 
                FROM {$this->orderDetailsTable} od
                JOIN books b ON od.book_id = b.id
                WHERE od.order_id = :order_id
                ORDER BY od.id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy đơn hàng của user
     */
    public function getByUserId($userId) {
        $sql = "SELECT * FROM {$this->orderTable} 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo đơn hàng mới
     */
    public function create($userId, $totalPrice, $shippingAddress, $phone, $note = '') {
        try {
            $this->pdo->beginTransaction();

            // Tạo order
            $sql = "INSERT INTO {$this->orderTable} (user_id, total_price, shipping_address, phone, note) 
                    VALUES (:user_id, :total_price, :shipping_address, :phone, :note)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':total_price' => $totalPrice,
                ':shipping_address' => $shippingAddress,
                ':phone' => $phone,
                ':note' => $note
            ]);

            $orderId = $this->pdo->lastInsertId();

            $this->pdo->commit();
            return $orderId;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    /**
     * Thêm chi tiết vào đơn hàng
     */
    public function addDetail($orderId, $bookId, $quantity, $unitPrice) {
        $subtotal = $quantity * $unitPrice;
        $sql = "INSERT INTO {$this->orderDetailsTable} (order_id, book_id, quantity, unit_price, subtotal) 
                VALUES (:order_id, :book_id, :quantity, :unit_price, :subtotal)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':order_id' => $orderId,
            ':book_id' => $bookId,
            ':quantity' => $quantity,
            ':unit_price' => $unitPrice,
            ':subtotal' => $subtotal
        ]);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus($id, $status) {
        $allowedStatus = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $allowedStatus)) {
            return false;
        }

        $sql = "UPDATE {$this->orderTable} SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status
        ]);
    }

    /**
     * Hủy đơn hàng
     */
    public function cancel($id) {
        return $this->updateStatus($id, 'cancelled');
    }

    /**
     * Lấy số lượng đơn hàng
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM {$this->orderTable}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Lấy doanh thu
     */
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total_price) as revenue FROM {$this->orderTable} WHERE status != 'cancelled'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['revenue'] ?? 0;
    }

    /**
     * Lấy đơn hàng gần đây
     */
    public function getRecent($limit = 10) {
        $sql = "SELECT * FROM {$this->orderTable} ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
