<?php
/**
 * Cart Model
 * Quản lý giỏ hàng của người dùng
 */
class Cart {
    private $pdo;
    private $table = 'carts';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả item trong giỏ của user
     */
    public function getByUserId($userId) {
        $sql = "SELECT c.*, b.title, b.price, b.image_url 
                FROM {$this->table} c
                JOIN books b ON c.book_id = b.id
                WHERE c.user_id = :user_id
                ORDER BY c.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy item giỏ theo ID
     */
    public function getItemById($id) {
        $sql = "SELECT c.*, b.title, b.price 
                FROM {$this->table} c
                JOIN books b ON c.book_id = b.id
                WHERE c.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Kiểm tra sách có trong giỏ không
     */
    public function hasBook($userId, $bookId) {
        $sql = "SELECT id FROM {$this->table} WHERE user_id = :user_id AND book_id = :book_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':book_id' => $bookId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm sách vào giỏ
     */
    public function addItem($userId, $bookId, $quantity = 1) {
        // Kiểm tra sách đã có trong giỏ chưa
        $existing = $this->hasBook($userId, $bookId);

        if ($existing) {
            // Cập nhật số lượng
            return $this->updateQuantity($existing['id'], $quantity, true);
        } else {
            // Thêm mới
            $sql = "INSERT INTO {$this->table} (user_id, book_id, quantity) 
                    VALUES (:user_id, :book_id, :quantity)";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':book_id' => $bookId,
                ':quantity' => $quantity
            ]);
        }
    }

    /**
     * Cập nhật số lượng
     */
    public function updateQuantity($cartItemId, $quantity, $increment = false) {
        if ($increment) {
            $sql = "UPDATE {$this->table} SET quantity = quantity + :quantity WHERE id = :id";
        } else {
            $sql = "UPDATE {$this->table} SET quantity = :quantity WHERE id = :id";
        }
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $cartItemId,
            ':quantity' => $quantity
        ]);
    }

    /**
     * Xóa item khỏi giỏ
     */
    public function removeItem($cartItemId) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $cartItemId]);
    }

    /**
     * Xóa giỏ hàng (sau khi checkout)
     */
    public function clearCart($userId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':user_id' => $userId]);
    }

    /**
     * Tính tổng tiền giỏ hàng
     */
    public function getTotalPrice($userId) {
        $sql = "SELECT SUM(c.quantity * b.price) as total 
                FROM {$this->table} c
                JOIN books b ON c.book_id = b.id
                WHERE c.user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy số lượng item trong giỏ
     */
    public function getItemCount($userId) {
        $sql = "SELECT SUM(quantity) as count FROM {$this->table} WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
