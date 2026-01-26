# HƯỚNG DẪN NHANH - QUICK START

## 🚀 BẮT ĐẦU TRONG 5 PHÚT

### BƯỚC 1: Import Database
1. Mở **phpMyAdmin** (http://localhost/phpmyadmin)
2. Click **Import**
3. Chọn file **database.sql** từ thư mục gốc
4. Click **Go**

✅ Database **bookstore_db** sẽ được tạo với dữ liệu mẫu

### BƯỚC 2: Chạy Ứng Dụng
1. Mở browser
2. Truy cập: **http://localhost/ban-sach**

✅ Trang chủ BookStore sẽ hiển thị

---

## 👤 Đăng Nhập Thử

### Admin Account
```
Username: admin
Password: 123456
```

### Làm gì khi đăng nhập admin?
- Quản lý sách (Thêm, Sửa, Xóa)
- Quản lý danh mục
- Xem danh sách khách hàng
- Quản lý đơn hàng

---

## 🛍️ Test Chức Năng Khách Hàng

### Đăng Ký Tài Khoản Mới
1. Click **Đăng Ký** ở header
2. Nhập thông tin
3. Click **Đăng Ký**

### Mua Sách
1. Click **Sản Phẩm** ở menu
2. Chọn sách yêu thích
3. Click **Chi Tiết**
4. Nhập số lượng, click **Thêm Vào Giỏ Hàng**
5. Click **Giỏ Hàng** ở header
6. Click **Thanh Toán**
7. Nhập thông tin giao hàng
8. Click **Xác Nhận Đặt Hàng**

### Xem Lịch Sử Đơn Hàng
1. Click **Lịch Sử** ở menu
2. Xem danh sách đơn đã mua

---

## 📁 Cấu Trúc Thư Mục Chính

```
ban-sach/
├── app/              # Code chính (Controllers, Models, Views)
├── public/           # CSS, JS, Images
├── index.php         # File router chính
├── database.sql      # Schema & dữ liệu mẫu
└── README.md         # Tài liệu đầy đủ
```

---

## ⚙️ Cấu Hình Database

Nếu không dùng default (root/password trống), sửa file:

**app/Models/Database.php**

```php
private $host = 'localhost';      // Thay thành host của bạn
private $db_name = 'bookstore_db'; // Tên database
private $user = 'root';            // Username MySQL
private $pass = '';                // Password MySQL
```

---

## 🔑 Các Chức Năng Chính

### 👥 Khách Hàng (Role: customer)
- ✅ Xem trang chủ
- ✅ Tìm kiếm sách
- ✅ Thêm vào giỏ hàng
- ✅ Thanh toán
- ✅ Xem lịch sử đơn hàng
- ✅ Quản lý thông tin cá nhân

### 🔧 Admin (Role: admin)
- ✅ Thêm/Sửa/Xóa sách
- ✅ Quản lý danh mục
- ✅ Quản lý khách hàng
- ✅ Quản lý đơn hàng
- ✅ Cập nhật trạng thái đơn hàng
- ✅ Xem thống kê

---

## 🐛 Troubleshooting

### Lỗi: "Lỗi kết nối database"
- Kiểm tra MySQL đã start chưa
- Kiểm tra thông tin DB trong `app/Models/Database.php`
- Kiểm tra database `bookstore_db` đã được tạo

### Lỗi: "Trang không tồn tại (404)"
- Kiểm tra URL có đúng không
- Đảm bảo thư mục `app/Views` có các file PHP

### Ảnh không hiển thị
- Kiểm tra đường dẫn ảnh
- Đảm bảo thư mục `public/images/` tồn tại
- Upload lại ảnh ở admin

---

## 📞 Cần Giúp?

### Xem Tài Liệu Đầy Đủ
- **README.md** - Tài liệu chi tiết
- **DEVELOPMENT_GUIDE.md** - Hướng dẫn phát triển

### Kiểm Tra Error Log
Bật error display trong `index.php`:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

---

## 🎯 Tiếp Theo

### Tùy Chỉnh Giao Diện
- Sửa CSS ở `public/css/style.css`
- Sửa logo ở header
- Thay đổi màu sắc

### Thêm Tính Năng
- Xem **DEVELOPMENT_GUIDE.md**
- Tạo Model, Controller, View mới
- Thêm route ở `index.php`

### Deploy Lên Server
- Sao chép tất cả files lên server
- Sửa cấu hình database
- Đảm bảo file permissions (755 cho folders)

---

## 💡 Tips

1. **Tảo dữ liệu mẫu**: Dữ liệu mẫu đã có sẵn ở database.sql
2. **Upload ảnh**: Ảnh tự động lưu vào `public/images/books/`
3. **Session timeout**: Mặc định 30 phút (có thể thay ở config.php)
4. **Pagination**: Giới hạn 20 items/page (có thể sửa ở config.php)

---

**Chúc bạn sử dụng BookStore vui vẻ! 🎉**

Nếu có vấn đề, vui lòng liên hệ: info@bookstore.com
