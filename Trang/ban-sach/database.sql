-- ============================================
-- DATABASE FOR BOOKSTORE
-- ============================================

-- Tạo database
CREATE DATABASE IF NOT EXISTS `bookstore_db`;
USE `bookstore_db`;

-- ============================================
-- TABLE: users
-- ============================================
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(100),
  `phone` VARCHAR(20),
  `address` TEXT,
  `role` ENUM('admin', 'customer') DEFAULT 'customer',
  `is_active` TINYINT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert admin mặc định
INSERT INTO `users` (`username`, `email`, `password`, `fullname`, `role`) 
VALUES ('tcdemon06', 'tcdemon06@gmail.com', '$2y$10$tQyve0LflxrFDcxWDZLCf.aAE0/xW/5ZWU4W5ub4z5v79RJOAx/lu', 'Phạm Minh Tuấn', 'admin');

-- ============================================
-- TABLE: categories
-- ============================================
CREATE TABLE `categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert categories mặc định
INSERT INTO `categories` (`name`, `description`) VALUES
('Kinh Tế', 'Sách về kinh tế, kinh doanh'),
('Lịch Sử', 'Sách về lịch sử, địa lý'),
('Văn Học', 'Sách văn học, tiểu thuyết'),
('Kỹ Thuật', 'Sách về công nghệ, lập trình'),
('Tâm Lý', 'Sách tâm lý, self-help');

-- ============================================
-- TABLE: books
-- ============================================
CREATE TABLE `books` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(100),
  `category_id` INT NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `quantity` INT DEFAULT 0,
  `description` LONGTEXT,
  `image_url` VARCHAR(255),
  `published_year` YEAR,
  `is_featured` TINYINT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample books
INSERT INTO `books` (`title`, `author`, `category_id`, `price`, `quantity`, `description`, `published_year`, `is_featured`) VALUES
('Kinh Tế Vĩ Mô', 'Paul Samuelson', 1, 150000, 50, 'Cuốn sách kinh điển về kinh tế vĩ mô', 2020, 1),
('Lịch Sử Việt Nam', 'Trần Trọng Huyền', 2, 120000, 40, 'Tổng quan lịch sử Việt Nam từ xưa đến nay', 2019, 1),
('Tôi Là Ai', 'Nguyễn Minh Châu', 3, 85000, 30, 'Tiểu thuyết tâm lý về tìm tòi bản thân', 2021, 0),
('Lập Trình PHP', 'Nils Barr', 4, 200000, 25, 'Hướng dẫn lập trình PHP từ cơ bản đến nâng cao', 2022, 1),
('Tư Duy Tích Cực', 'Norman Vincent Peale', 5, 95000, 60, 'Cách thay đổi tư duy để thành công', 2020, 1),
('Bộ Sưu Tập Truyện Ngắn', 'Nguyễn Thúy Khanh', 3, 65000, 45, 'Tuyển tập truyện ngắn hay của tác giả', 2021, 0),
('Hóa Học Đại Cương', 'Đặng Tài Quân', 4, 180000, 35, 'Giáo trình hóa học cho sinh viên', 2020, 0),
('Con Đường Tới Thành Công', 'Stephen Covey', 5, 125000, 50, 'Bí quyết thành công trong cuộc sống', 2019, 1);

-- ============================================
-- TABLE: carts (Session Cart)
-- ============================================
CREATE TABLE `carts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `book_id` INT NOT NULL,
  `quantity` INT DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `user_book` (`user_id`, `book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: orders
-- ============================================
CREATE TABLE `orders` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `total_price` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  `shipping_address` TEXT NOT NULL,
  `phone` VARCHAR(20),
  `note` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TABLE: order_details
-- ============================================
CREATE TABLE `order_details` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `book_id` INT NOT NULL,
  `quantity` INT NOT NULL,
  `unit_price` DECIMAL(10, 2) NOT NULL,
  `subtotal` DECIMAL(10, 2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- INDEXES để tối ưu query
-- ============================================
CREATE INDEX idx_books_category ON books(category_id);
CREATE INDEX idx_books_featured ON books(is_featured);
CREATE INDEX idx_carts_user ON carts(user_id);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_order_details_order ON order_details(order_id);
CREATE INDEX idx_users_role ON users(role);
