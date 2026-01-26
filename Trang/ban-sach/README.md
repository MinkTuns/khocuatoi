# BookStore - Cửa Hàng Bán Sách Online

## Giới Thiệu
Đây là một website bán sách hoàn chỉnh được xây dựng theo kiến trúc MVC sử dụng PHP thuần (OOP), MySQL, và HTML5/CSS3.

## Yêu Cầu Hệ Thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Apache (hoặc Nginx)
- Laragon/XAMPP/WAMP

## Cài Đặt

### 1. Tạo Database
```bash
# Mở MySQL Command Line hoặc PHPMyAdmin
# Chạy file database.sql để tạo bảng và dữ liệu mẫu
```

### 2. Cấu Hình
- Mở file `app/Models/Database.php`
- Thay đổi thông tin kết nối database nếu cần:
  - Host: `localhost`
  - Username: `root`
  - Password: (để trống hoặc nhập password của bạn)
  - Database: `bookstore_db`

### 3. Chạy Ứng Dụng
```bash
# Trên Laragon: Start All
# Hoặc mở browser và truy cập:
http://localhost/duanmau/ban-sach
```

## Tài Khoản Demo

### Admin
- Username: `admin`
- Password: `123456`

### Khách Hàng
- Bạn có thể đăng ký tài khoản mới từ trang đăng ký

## Cấu Trúc Thư Mục

```
ban-sach/
├── app/
│   ├── Controllers/      # Các controller xử lý logic
│   ├── Models/          # Các model tương tác database
│   └── Views/           # Các view hiển thị giao diện
│       ├── layouts/     # Header và Footer
│       ├── home/        # Trang chủ
│       ├── auth/        # Đăng nhập/Đăng ký
│       ├── products/    # Danh sách và chi tiết sản phẩm
│       ├── cart/        # Giỏ hàng
│       ├── orders/      # Lịch sử đơn hàng
│       └── admin/       # Trang quản trị
├── public/
│   ├── css/             # File CSS
│   ├── js/              # File JavaScript
│   └── images/          # Hình ảnh
├── database.sql         # Cấu trúc database
└── index.php            # File router chính
```

## Chức Năng Chính

### Khách Hàng
- ✅ Xem trang chủ và danh sách sách
- ✅ Tìm kiếm sách theo tên, danh mục, giá
- ✅ Xem chi tiết sản phẩm
- ✅ Thêm sách vào giỏ hàng
- ✅ Quản lý giỏ hàng (thêm, sửa, xóa)
- ✅ Thanh toán và đặt hàng
- ✅ Xem lịch sử đơn hàng
- ✅ Quản lý thông tin cá nhân

### Admin
- ✅ Quản lý sách (thêm, sửa, xóa)
- ✅ Quản lý danh mục
- ✅ Quản lý khách hàng
- ✅ Quản lý đơn hàng
- ✅ Cập nhật trạng thái đơn hàng
- ✅ Xem dashboard thống kê

## Quy Trình Đăng Nhập & Phân Quyền

### Trang Không Cần Đăng Nhập
- Trang chủ (Home)
- Danh sách sản phẩm (Products)
- Chi tiết sản phẩm (Product Detail)
- Đăng nhập (Login)
- Đăng ký (Register)

### Cần Đăng Nhập (Customer)
- Giỏ hàng (Cart)
- Thanh toán (Checkout)
- Lịch sử đơn hàng (Order History)
- Thông tin cá nhân (Profile)

### Cần Quyền Admin
- Dashboard quản trị
- Quản lý sách
- Quản lý danh mục
- Quản lý khách hàng
- Quản lý đơn hàng

## Mô Hình MVC

### Model (app/Models/)
- **Database.php**: Quản lý kết nối PDO
- **User.php**: CRUD người dùng
- **Book.php**: CRUD sách, tìm kiếm
- **Category.php**: CRUD danh mục
- **Cart.php**: Quản lý giỏ hàng
- **Order.php**: CRUD đơn hàng

### View (app/Views/)
- Tách layout chung (header/footer)
- Tổ chức theo chức năng (home, products, cart, orders, admin)
- Sử dụng PHP thuần với htmlspecialchars() để bảo mật

### Controller (app/Controllers/)
- **AuthController.php**: Xử lý login/logout
- **HomeController.php**: Trang chủ
- **ProductController.php**: Danh sách & chi tiết sản phẩm
- **CartController.php**: Giỏ hàng & checkout
- **OrderController.php**: Lịch sử & thông tin khách hàng
- **AdminController.php**: Trang quản trị

## Bảo Mật

### Bảo Mật Triển Khai
- ✅ **Prepared Statement**: Sử dụng PDO binding để chống SQL Injection
- ✅ **Password Hashing**: Dùng `password_hash()` với BCRYPT
- ✅ **Input Validation**: Kiểm tra và làm sạch dữ liệu đầu vào
- ✅ **Session Security**: Kiểm tra session để phân quyền
- ✅ **XSS Protection**: Dùng `htmlspecialchars()` khi xuất dữ liệu
- ✅ **CSRF Protection**: Có thể thêm token nếu cần (được bỏ qua ở đây)

## Cách Sử Dụng API

### Thêm Sách Vào Giỏ Hàng
```
POST ?page=add_to_cart
Parameters:
- book_id: ID sách
- quantity: Số lượng
```

### Đặt Hàng
```
POST ?page=process_checkout
Parameters:
- shipping_address: Địa chỉ giao hàng
- phone: Số điện thoại
- note: Ghi chú (tùy chọn)
```

### Admin - Thêm Sách
```
POST ?page=add_book_process
Parameters:
- title: Tiêu đề
- author: Tác giả
- category_id: ID danh mục
- price: Giá
- quantity: Số lượng
- description: Mô tả
- image: File hình ảnh
- published_year: Năm xuất bản
- is_featured: Sách nổi bật (checkbox)
```

## Mở Rộng Ứng Dụng

### Tính Năng Có Thể Thêm
1. **Hệ Thống Thanh Toán**: Tích hợp VNPay, PayPal
2. **Review & Rating**: Cho phép khách hàng đánh giá sách
3. **Wishlist**: Lưu sách yêu thích
4. **Discount Code**: Mã giảm giá
5. **Email Notification**: Gửi thông báo qua email
6. **Analytics**: Thống kê sales, trending products
7. **API REST**: Tạo API cho mobile app
8. **Caching**: Redis cache cho hiệu năng tốt hơn

## Troubleshooting

### Lỗi: Database Connection Failed
- Kiểm tra thông tin kết nối ở `app/Models/Database.php`
- Đảm bảo MySQL đang chạy
- Kiểm tra xem database `bookstore_db` đã được tạo

### Lỗi: File Not Found (404)
- Kiểm tra URL routing ở `index.php`
- Đảm bảo thư mục `app/Views` có tệp phù hợp

### Lỗi: Upload Hình Ảnh
- Kiểm tra quyền thư mục `public/images/books/`
- Đảm bảo server cho phép upload file

## Liên Hệ & Hỗ Trợ

Nếu có bất kỳ câu hỏi hoặc vấn đề, vui lòng liên hệ:
- Email: info@bookstore.com
- Điện thoại: (84) 123 456 789

## License

Dự án này được phát hành dưới giấy phép MIT.

---

**Cảm ơn bạn đã sử dụng BookStore!**
