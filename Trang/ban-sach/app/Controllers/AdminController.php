<?php
/**
 * Admin Controller
 * Quản lý trang quản trị
 */
class AdminController {
    private $bookModel;
    private $categoryModel;
    private $userModel;
    private $orderModel;

    public function __construct($bookModel, $categoryModel, $userModel, $orderModel) {
        $this->bookModel = $bookModel;
        $this->categoryModel = $categoryModel;
        $this->userModel = $userModel;
        $this->orderModel = $orderModel;
    }

    /**
     * Kiểm tra quyền admin
     */
    private function requireAdmin() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: ?page=home');
            exit;
        }
    }

    /**
     * Dashboard
     */
    public function dashboard() {
        $this->requireAdmin();

        $totalOrders = $this->orderModel->count();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $recentOrders = $this->orderModel->getRecent(10);
        $totalCustomers = count($this->userModel->getCustomers());
        $totalBooks = count($this->bookModel->getAll());

        include 'app/Views/admin/dashboard.php';
    }

    // ============ BOOKS MANAGEMENT ============

    /**
     * Danh sách sách
     */
    public function listBooks() {
        $this->requireAdmin();

        $books = $this->bookModel->getAll();
        $categories = $this->categoryModel->getAll();

        include 'app/Views/admin/books/list.php';
    }

    /**
     * Form thêm sách
     */
    public function addBookForm() {
        $this->requireAdmin();

        $categories = $this->categoryModel->getAll();
        include 'app/Views/admin/books/add.php';
    }

    /**
     * Xử lý thêm sách
     */
    public function addBook() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=admin_add_book');
            exit;
        }

        // Validate dữ liệu
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $publishedYear = trim($_POST['published_year'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

        $errors = [];
        if (empty($title)) $errors[] = 'Tiêu đề sách không được trống';
        if (empty($author)) $errors[] = 'Tác giả không được trống';
        if ($categoryId <= 0) $errors[] = 'Vui lòng chọn danh mục';
        if ($price <= 0) $errors[] = 'Giá sách phải lớn hơn 0';
        if ($quantity < 0) $errors[] = 'Số lượng không hợp lệ';

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ?page=admin_add_book');
            exit;
        }

        // Xử lý upload hình ảnh
        $imageUrl = null;
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $imageUrl = $this->uploadImage($_FILES['image']);
            if (!$imageUrl) {
                $_SESSION['error'] = 'Lỗi upload hình ảnh';
                header('Location: ?page=admin_add_book');
                exit;
            }
        }

        // Thêm sách
        $data = [
            'title' => $title,
            'author' => $author,
            'category_id' => $categoryId,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description,
            'image_url' => $imageUrl,
            'published_year' => $publishedYear,
            'is_featured' => $isFeatured
        ];

        if ($this->bookModel->create($data)) {
            $_SESSION['success'] = 'Thêm sách thành công!';
            header('Location: ?page=admin_books');
        } else {
            $_SESSION['error'] = 'Lỗi khi thêm sách';
            header('Location: ?page=admin_add_book');
        }
        exit;
    }

    /**
     * Form sửa sách
     */
    public function editBookForm() {
        $this->requireAdmin();

        if (!isset($_GET['id'])) {
            header('Location: ?page=admin_books');
            exit;
        }

        $book = $this->bookModel->getById((int)$_GET['id']);
        if (!$book) {
            $_SESSION['error'] = 'Sách không tồn tại';
            header('Location: ?page=admin_books');
            exit;
        }

        $categories = $this->categoryModel->getAll();
        include 'app/Views/admin/books/edit.php';
    }

    /**
     * Xử lý sửa sách
     */
    public function editBook() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=admin_books');
            exit;
        }

        $bookId = (int)($_POST['id'] ?? 0);
        $book = $this->bookModel->getById($bookId);

        if (!$book) {
            $_SESSION['error'] = 'Sách không tồn tại';
            header('Location: ?page=admin_books');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $price = (float)($_POST['price'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        $description = trim($_POST['description'] ?? '');
        $publishedYear = trim($_POST['published_year'] ?? '');
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;

        // Xử lý upload hình ảnh mới
        $imageUrl = $book['image_url'];
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            $newImageUrl = $this->uploadImage($_FILES['image']);
            if ($newImageUrl) {
                $imageUrl = $newImageUrl;
            }
        }

        $data = [
            'title' => $title,
            'author' => $author,
            'category_id' => $categoryId,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description,
            'image_url' => $imageUrl,
            'published_year' => $publishedYear,
            'is_featured' => $isFeatured
        ];

        if ($this->bookModel->update($bookId, $data)) {
            $_SESSION['success'] = 'Cập nhật sách thành công!';
            header('Location: ?page=admin_books');
        } else {
            $_SESSION['error'] = 'Lỗi khi cập nhật sách';
            header('Location: ?page=admin_edit_book&id=' . $bookId);
        }
        exit;
    }

    /**
     * Xóa sách
     */
    public function deleteBook() {
        $this->requireAdmin();

        $bookId = (int)($_GET['id'] ?? 0);
        if ($bookId <= 0) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?page=admin_books');
            exit;
        }

        if ($this->bookModel->delete($bookId)) {
            $_SESSION['success'] = 'Xóa sách thành công!';
        } else {
            $_SESSION['error'] = 'Lỗi khi xóa sách';
        }

        header('Location: ?page=admin_books');
        exit;
    }

    // ============ CATEGORIES MANAGEMENT ============

    /**
     * Danh sách danh mục
     */
    public function listCategories() {
        $this->requireAdmin();

        $categories = $this->categoryModel->getAll();
        include 'app/Views/admin/categories/list.php';
    }

    /**
     * Form thêm danh mục
     */
    public function addCategoryForm() {
        $this->requireAdmin();
        include 'app/Views/admin/categories/add.php';
    }

    /**
     * Xử lý thêm danh mục
     */
    public function addCategory() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=admin_categories');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Tên danh mục không được trống';
            header('Location: ?page=admin_add_category');
            exit;
        }

        if ($this->categoryModel->create($name, $description)) {
            $_SESSION['success'] = 'Thêm danh mục thành công!';
            header('Location: ?page=admin_categories');
        } else {
            $_SESSION['error'] = 'Lỗi khi thêm danh mục';
            header('Location: ?page=admin_add_category');
        }
        exit;
    }

    /**
     * Form sửa danh mục
     */
    public function editCategoryForm() {
        $this->requireAdmin();

        if (!isset($_GET['id'])) {
            header('Location: ?page=admin_categories');
            exit;
        }

        $category = $this->categoryModel->getById((int)$_GET['id']);
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại';
            header('Location: ?page=admin_categories');
            exit;
        }

        include 'app/Views/admin/categories/edit.php';
    }

    /**
     * Xử lý sửa danh mục
     */
    public function editCategory() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=admin_categories');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($name)) {
            $_SESSION['error'] = 'Tên danh mục không được trống';
            header('Location: ?page=admin_edit_category&id=' . $id);
            exit;
        }

        if ($this->categoryModel->update($id, $name, $description)) {
            $_SESSION['success'] = 'Cập nhật danh mục thành công!';
            header('Location: ?page=admin_categories');
        } else {
            $_SESSION['error'] = 'Lỗi khi cập nhật danh mục';
            header('Location: ?page=admin_edit_category&id=' . $id);
        }
        exit;
    }

    /**
     * Xóa danh mục
     */
    public function deleteCategory() {
        $this->requireAdmin();

        $categoryId = (int)($_GET['id'] ?? 0);
        if ($categoryId <= 0) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?page=admin_categories');
            exit;
        }

        if ($this->categoryModel->hasBooks($categoryId)) {
            $_SESSION['error'] = 'Không thể xóa danh mục có sách';
            header('Location: ?page=admin_categories');
            exit;
        }

        if ($this->categoryModel->delete($categoryId)) {
            $_SESSION['success'] = 'Xóa danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Lỗi khi xóa danh mục';
        }

        header('Location: ?page=admin_categories');
        exit;
    }

    // ============ CUSTOMERS MANAGEMENT ============

    /**
     * Danh sách khách hàng
     */
    public function listCustomers() {
        $this->requireAdmin();

        $customers = $this->userModel->getCustomers();
        include 'app/Views/admin/customers/list.php';
    }

    /**
     * Chi tiết khách hàng
     */
    public function customerDetail() {
        $this->requireAdmin();

        if (!isset($_GET['id'])) {
            header('Location: ?page=admin_customers');
            exit;
        }

        $customer = $this->userModel->getById((int)$_GET['id']);
        if (!$customer || $customer['role'] !== 'customer') {
            $_SESSION['error'] = 'Khách hàng không tồn tại';
            header('Location: ?page=admin_customers');
            exit;
        }

        $orders = $this->orderModel->getByUserId($customer['id']);
        include 'app/Views/admin/customers/detail.php';
    }

    /**
     * Khóa/Mở tài khoản
     */
    public function toggleCustomerStatus() {
        $this->requireAdmin();

        $customerId = (int)($_GET['id'] ?? 0);
        if ($customerId <= 0) {
            $_SESSION['error'] = 'ID không hợp lệ';
            header('Location: ?page=admin_customers');
            exit;
        }

        $customer = $this->userModel->getById($customerId);
        if (!$customer) {
            $_SESSION['error'] = 'Khách hàng không tồn tại';
            header('Location: ?page=admin_customers');
            exit;
        }

        $newStatus = $customer['is_active'] ? 0 : 1;
        if ($this->userModel->toggleActive($customerId, $newStatus)) {
            $_SESSION['success'] = $newStatus ? 'Mở khóa thành công!' : 'Khóa tài khoản thành công!';
        } else {
            $_SESSION['error'] = 'Lỗi khi cập nhật';
        }

        header('Location: ?page=admin_customers');
        exit;
    }

    // ============ ORDERS MANAGEMENT ============

    /**
     * Danh sách đơn hàng
     */
    public function listOrders() {
        $this->requireAdmin();

        $orders = $this->orderModel->getAll();
        include 'app/Views/admin/orders/list.php';
    }

    /**
     * Chi tiết đơn hàng
     */
    public function orderDetail() {
        $this->requireAdmin();

        if (!isset($_GET['id'])) {
            header('Location: ?page=admin_orders');
            exit;
        }

        $order = $this->orderModel->getById((int)$_GET['id']);
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ?page=admin_orders');
            exit;
        }

        $orderDetails = $this->orderModel->getDetails($order['id']);
        $customer = $this->userModel->getById($order['user_id']);

        include 'app/Views/admin/orders/detail.php';
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=admin_orders');
            exit;
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $status = trim($_POST['status'] ?? '');

        if ($this->orderModel->updateStatus($orderId, $status)) {
            $_SESSION['success'] = 'Cập nhật trạng thái thành công!';
        } else {
            $_SESSION['error'] = 'Lỗi khi cập nhật';
        }

        header('Location: ?page=admin_order_detail&id=' . $orderId);
        exit;
    }

    // ============ UTILITIES ============

    /**
     * Upload hình ảnh
     */
    private function uploadImage($file) {
        $uploadDir = 'public/images/books/';
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        if ($file['size'] > $maxSize) {
            return false;
        }

        $fileName = time() . '_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return $filePath;
        }

        return false;
    }
}
