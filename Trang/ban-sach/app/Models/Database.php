<?php
/**
 * Database Connection Class
 * Quản lý kết nối với MySQL sử dụng PDO
 */
class Database {
    private $host = 'localhost';
    private $db_name = 'bookstore_db';
    private $user = 'tuans';
    private $pass = 'Popopoq2106@';
    private $pdo;

    /**
     * Hàm khởi tạo kết nối database
     */
    public function connect() {
        $this->pdo = null;

        try {
            // Tạo kết nối PDO
            $this->pdo = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4',
                $this->user,
                $this->pass,
                array(
                    PDO::ATTR_PERSISTENT => false,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );
        } catch (PDOException $e) {
            echo 'Lỗi kết nối: ' . $e->getMessage();
            exit;
        }

        return $this->pdo;
    }

    /**
     * Lấy instance PDO
     */
    public function getPDO() {
        return $this->pdo;
    }
}
