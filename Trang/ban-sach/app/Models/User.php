<?php
/**
 * User Model
 * Quản lý dữ liệu người dùng
 */
class User {
    private $pdo;
    private $table = 'users';

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Lấy tất cả người dùng
     */
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo username
     */
    public function getByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy người dùng theo email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Đăng ký người dùng mới
     */
    public function register($username, $email, $password, $fullname) {
        // Kiểm tra username và email đã tồn tại
        if ($this->getByUsername($username) || $this->getByEmail($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO {$this->table} (username, email, password, fullname, role) 
                VALUES (:username, :email, :password, :fullname, 'customer')";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':fullname' => $fullname
        ]);
    }

    /**
     * Cập nhật thông tin người dùng
     */
    public function update($id, $data) {
        $updateFields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, ['fullname', 'email', 'phone', 'address'])) {
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
     * Xóa người dùng
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Kích hoạt/vô hiệu hóa tài khoản
     */
    public function toggleActive($id, $is_active) {
        $sql = "UPDATE {$this->table} SET is_active = :is_active WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':is_active' => (int)$is_active
        ]);
    }

    /**
     * Kiểm tra mật khẩu
     */
    public function verifyPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }

    /**
     * Lấy danh sách khách hàng (không phải admin)
     */
    public function getCustomers() {
        $sql = "SELECT * FROM {$this->table} WHERE role = 'customer' ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
