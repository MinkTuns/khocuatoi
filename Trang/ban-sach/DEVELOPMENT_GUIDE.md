/**
 * HƯỚNG DẪN XÂY DỰNG VÀ TRIỂN KHAI
 * 
 * ============================================
 * 1. SETUP INITIAL
 * ============================================
 */

// Step 1: Nhập SQL vào MySQL
- Dùng PHPMyAdmin hoặc MySQL Command Line
- Paste nội dung từ database.sql
- Hoặc chạy: mysql -u root -p < database.sql

// Step 2: Kiểm tra cấu hình Database
- app/Models/Database.php
- Kiểm tra: host, db_name, user, pass

// Step 3: Chạy ứng dụng
- Truy cập: http://localhost/duanmau/ban-sach
- Login với admin/123456


/**
 * ============================================
 * 2. KIẾN TRÚC & LUỒNG MVC
 * ============================================
 */

// REQUEST FLOW:
// 1. Browser gửi request → index.php
// 2. index.php lấy tham số 'page' từ URL
// 3. Dựa vào page, gọi Controller tương ứng
// 4. Controller gọi Model để lấy dữ liệu
// 5. Controller include View để render HTML
// 6. View hiển thị dữ liệu lên browser

// VÍ DỤ: Xem danh sách sách
URL: http://localhost/duanmau/ban-sach?page=products

Luồng:
1. index.php nhận page=products
2. Tạo ProductController
3. Gọi $controller->list()
4. ProductController gọi $bookModel->search()
5. Model truy vấn database
6. Controller include app/Views/products/list.php
7. View render với dữ liệu từ controller


/**
 * ============================================
 * 3. ROUTING MAP
 * ============================================
 */

// PUBLIC ROUTES (Không cần login)
GET  ?page=home           → HomeController->index()
GET  ?page=about          → HomeController->about()
GET  ?page=contact        → HomeController->contact()
GET  ?page=login          → AuthController->showLoginPage()
POST ?page=login_process  → AuthController->login()
GET  ?page=register       → AuthController->showRegisterPage()
POST ?page=register_process → AuthController->register()
GET  ?page=logout         → AuthController->logout()
GET  ?page=products       → ProductController->list()
GET  ?page=product_detail → ProductController->detail()

// CUSTOMER ROUTES (Cần login)
GET  ?page=cart                  → CartController->view()
POST ?page=add_to_cart           → CartController->add()
POST ?page=update_cart_quantity  → CartController->updateQuantity()
GET  ?page=remove_from_cart      → CartController->remove()
GET  ?page=checkout              → CartController->checkout()
POST ?page=process_checkout      → CartController->processCheckout()
GET  ?page=order_history         → OrderController->history()
GET  ?page=order_detail          → OrderController->detail()
GET  ?page=profile               → OrderController->profile()

// ADMIN ROUTES (Cần role=admin)
GET  ?page=admin_dashboard       → AdminController->dashboard()
GET  ?page=admin_books           → AdminController->listBooks()
GET  ?page=admin_add_book        → AdminController->addBookForm()
POST ?page=add_book_process      → AdminController->addBook()
GET  ?page=admin_edit_book       → AdminController->editBookForm()
POST ?page=edit_book_process     → AdminController->editBook()
GET  ?page=delete_book           → AdminController->deleteBook()
GET  ?page=admin_categories      → AdminController->listCategories()
GET  ?page=admin_add_category    → AdminController->addCategoryForm()
POST ?page=add_category_process  → AdminController->addCategory()
GET  ?page=admin_edit_category   → AdminController->editCategoryForm()
POST ?page=edit_category_process → AdminController->editCategory()
GET  ?page=delete_category       → AdminController->deleteCategory()
GET  ?page=admin_customers       → AdminController->listCustomers()
GET  ?page=admin_customer_detail → AdminController->customerDetail()
GET  ?page=toggle_customer_status → AdminController->toggleCustomerStatus()
GET  ?page=admin_orders          → AdminController->listOrders()
GET  ?page=admin_order_detail    → AdminController->orderDetail()
POST ?page=update_order_status   → AdminController->updateOrderStatus()


/**
 * ============================================
 * 4. DATABASE SCHEMA
 * ============================================
 */

TABLES:
- users (id, username, email, password, fullname, phone, address, role, is_active)
- categories (id, name, description)
- books (id, title, author, category_id, price, quantity, description, image_url, published_year, is_featured)
- carts (id, user_id, book_id, quantity)
- orders (id, user_id, total_price, status, shipping_address, phone, note)
- order_details (id, order_id, book_id, quantity, unit_price, subtotal)

RELATIONSHIPS:
- users.role: admin | customer
- books.category_id → categories.id
- carts.user_id → users.id
- carts.book_id → books.id
- orders.user_id → users.id
- order_details.order_id → orders.id
- order_details.book_id → books.id


/**
 * ============================================
 * 5. PHÂN QUYỀN VÀ BẢO MẬT
 * ============================================
 */

// PHÂN QUYỀN:
Role Admin:
- Quản lý sách: CRUD
- Quản lý danh mục: CRUD
- Quản lý khách hàng: View, toggle status
- Quản lý đơn hàng: View, update status
- Dashboard: View thống kê

Role Customer:
- Xem sách: Được
- Mua sách: Được
- Xem lịch sử: Được
- Sửa thông tin: Được cá nhân của mình
- Quản trị: Không được

// BẢO MẬT:
✅ Password Hash: password_hash() + PASSWORD_BCRYPT
✅ SQL Injection: Prepared Statement (PDO)
✅ XSS: htmlspecialchars() khi echo dữ liệu
✅ Session: $_SESSION['user_id'], $_SESSION['role']
✅ Validation: Check isset, !empty, filter_var()
✅ File Upload: Check type, size, move_uploaded_file()


/**
 * ============================================
 * 6. CÁC HÀNG SỬ DỤNG MƠN HỌC
 * ============================================
 */

// Khi thêm/sửa/xóa → Kiểm tra quyen:
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = 'Bạn không có quyền';
    header('Location: ?page=home');
    exit;
}

// Khi lấy dữ liệu → Dùng prepared statement:
$sql = "SELECT * FROM books WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $bookId]);

// Khi hiển thị dữ liệu → Dùng htmlspecialchars:
echo htmlspecialchars($book['title']);

// Khi upload ảnh → Validate type & size:
if (!in_array($file['type'], $allowedTypes)) return false;
if ($file['size'] > $maxSize) return false;

// Khi hash password → Dùng password_hash:
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Khi kiểm tra password → Dùng password_verify:
if (password_verify($password, $user['password'])) { ... }


/**
 * ============================================
 * 7. CÁCH MỞ RỘNG
 * ============================================
 */

// Thêm feature mới:
1. Tạo Model nếu cần (app/Models/NewModel.php)
2. Tạo Controller (app/Controllers/NewController.php)
3. Tạo Views (app/Views/new/)
4. Thêm route ở index.php
5. Link menu nếu cần

// Ví dụ: Thêm feature "Review"
1. Tạo bảng reviews: (id, user_id, book_id, rating, comment, created_at)
2. Tạo Review.php Model
3. Tạo ReviewController
4. Thêm route: ?page=add_review
5. Update UI ở product detail


/**
 * ============================================
 * 8. TESTING & DEBUGGING
 * ============================================
 */

// Bật error reporting:
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log lỗi:
error_log('Message', 3, '/path/to/error.log');

// Debug query:
echo '<pre>';
echo $sql;
var_dump($params);
echo '</pre>';

// Kiểm tra session:
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';


/**
 * ============================================
 * 9. OPTIMIZATION TIPS
 * ============================================
 */

1. Caching:
   - Cache danh mục ở session
   - Cache sách nổi bật
   
2. Database:
   - Dùng INDEX trên: id, user_id, category_id, status
   - Query: Dùng JOIN thay vì multiple queries
   
3. Frontend:
   - Minify CSS/JS
   - Lazy load images
   - Async load scripts

4. Performance:
   - Giới hạn results: LIMIT 20
   - Pagination cho large datasets
   - Gzip compression


/**
 * ============================================
 * 10. DEPLOYMENT
 * ============================================
 */

// Trước khi lên production:
1. Thay đổi DB credentials
2. Tắt error display: ini_set('display_errors', 0)
3. HTTPS: Redirect http → https
4. Database: Regular backup
5. Security: Update PHP/MySQL
6. Permissions: 755 cho folders, 644 cho files
7. .htaccess: Protect sensitive files

// .htaccess example:
<FilesMatch "\.php$">
    Deny from all
</FilesMatch>

<FilesMatch "(index|view)\.php$">
    Allow from all
</FilesMatch>


/**
 * ============================================
 * 11. THÊM TÍNH NĂNG THANH TOÁN (VNPay)
 * ============================================
 */

1. Tạo PaymentController
2. Tạo Payment Model (lưu transaction)
3. Tích hợp VNPay SDK
4. Thêm route: ?page=create_payment
5. Webhook: ?page=payment_callback

$vnp_Url = "https://sandbox.vnpayment.vn/paymen/v2/vpcpay.html";
$vnp_SecureHash = hash_hmac('sha512', $inputData, $vnp_HashSecret);


/**
 * ============================================
 * 12. GỬI EMAIL THÔNG BÁO
 * ============================================
 */

// Khi đặt hàng thành công:
$to = $customer_email;
$subject = 'Đặt hàng thành công - Order #' . $order_id;
$message = 'Cảm ơn bạn đã đặt hàng...';
$headers = "From: noreply@bookstore.com";
mail($to, $subject, $message, $headers);


/**
 * ============================================
 * 13. CHECKLIST HOÀN THIỆN
 * ============================================
 */

✅ Database schema & relationships
✅ Models (CRUD, validation, business logic)
✅ Controllers (routing, permissions, data passing)
✅ Views (HTML, form, display data)
✅ Authentication (login/register/logout)
✅ Authorization (role-based access)
✅ Input validation & sanitization
✅ Error handling & messaging
✅ Responsive design (CSS)
✅ JavaScript functionality
✅ File upload handling
✅ Session management
✅ Database security (prepared statements)
✅ Frontend security (XSS protection)
✅ User-friendly UI/UX
✅ Documentation & README
✅ Test all features
✅ Backup strategy
✅ Performance optimization
✅ Code comments & documentation


/**
 * ============================================
 * 14. LIÊN HỆ & HỖ TRỢ
 * ============================================
 */

Nếu gặp vấn đề:
1. Kiểm tra error_log
2. Bật display_errors để xem chi tiết lỗi
3. Kiểm tra database connection
4. Kiểm tra file permissions
5. Xem README.md

Phát triển thêm:
- GitHub: Create repository
- Version control: Git
- Code review: Pull requests
- Testing: Unit tests, Integration tests

Happy Coding! 🚀
