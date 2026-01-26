<?php
/**
 * Book Model
 * Quản lý sản phẩm sách
 */
class Book {
    private $pdo;
    private $table = 'books';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả sách
     */
    public function getAll() {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                ORDER BY b.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sách theo ID
     */
    public function getById($id) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sách nổi bật
     */
    public function getFeatured($limit = 6) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.is_featured = 1
                ORDER BY b.created_at DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy sách theo danh mục
     */
    public function getByCategory($categoryId) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.category_id = :categoryId
                ORDER BY b.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':categoryId' => $categoryId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm kiếm sách
     */
    public function search($keyword = '', $categoryId = null, $minPrice = null, $maxPrice = null) {
        $sql = "SELECT b.*, c.name as category_name 
                FROM {$this->table} b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (b.title LIKE :keyword OR b.author LIKE :keyword)";
            $params[':keyword'] = "%$keyword%";
        }

        if ($categoryId) {
            $sql .= " AND b.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }

        if ($minPrice !== null) {
            $sql .= " AND b.price >= :minPrice";
            $params[':minPrice'] = $minPrice;
        }

        if ($maxPrice !== null) {
            $sql .= " AND b.price <= :maxPrice";
            $params[':maxPrice'] = $maxPrice;
        }

        $sql .= " ORDER BY b.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm sách mới
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, author, category_id, price, quantity, description, image_url, published_year, is_featured)
                VALUES (:title, :author, :category_id, :price, :quantity, :description, :image_url, :published_year, :is_featured)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':author' => $data['author'],
            ':category_id' => $data['category_id'],
            ':price' => $data['price'],
            ':quantity' => $data['quantity'],
            ':description' => $data['description'],
            ':image_url' => $data['image_url'] ?? null,
            ':published_year' => $data['published_year'] ?? null,
            ':is_featured' => $data['is_featured'] ?? 0
        ]);
    }

    /**
     * Cập nhật thông tin sách
     */
    public function update($id, $data) {
        $updateFields = [];
        $params = [':id' => $id];

        $allowedFields = ['title', 'author', 'category_id', 'price', 'quantity', 'description', 'image_url', 'published_year', 'is_featured'];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $updateFields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }

        if (empty($updateFields)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $updateFields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Xóa sách
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Kiểm tra sách còn hàng
     */
    public function checkStock($id, $quantity = 1) {
        $book = $this->getById($id);
        return $book && $book['quantity'] >= $quantity;
    }

    /**
     * Giảm số lượng hàng (khi đặt hàng)
     */
    public function decreaseStock($id, $quantity) {
        $sql = "UPDATE {$this->table} SET quantity = quantity - :quantity WHERE id = :id AND quantity >= :quantity";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':quantity' => $quantity
        ]);
    }

    /**
     * Tăng số lượng hàng (khi hủy đơn)
     */
    public function increaseStock($id, $quantity) {
        $sql = "UPDATE {$this->table} SET quantity = quantity + :quantity WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':quantity' => $quantity
        ]);
    }
}
