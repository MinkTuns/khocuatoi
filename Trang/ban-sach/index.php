<?php
/**
 * ROUTER CHÍNH - index.php
 * File này xử lý routing cho toàn bộ ứng dụng
 */

// Bắt đầu session
session_start();

// Set timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Định nghĩa các đường dẫn
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');

// Autoload Models
require_once APP_PATH . '/Models/Database.php';
require_once APP_PATH . '/Models/User.php';
require_once APP_PATH . '/Models/Book.php';
require_once APP_PATH . '/Models/Category.php';
require_once APP_PATH . '/Models/Cart.php';
require_once APP_PATH . '/Models/Order.php';

// Autoload Controllers
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/HomeController.php';
require_once APP_PATH . '/Controllers/ProductController.php';
require_once APP_PATH . '/Controllers/CartController.php';
require_once APP_PATH . '/Controllers/OrderController.php';
require_once APP_PATH . '/Controllers/AdminController.php';

// Khởi tạo database
$db = new Database();
$pdo = $db->connect();

// Khởi tạo models
$userModel = new User($pdo);
$bookModel = new Book($pdo);
$categoryModel = new Category($pdo);
$cartModel = new Cart($pdo);
$orderModel = new Order($pdo);

// Lưu models vào global để dùng ở views
$GLOBALS['userModel'] = $userModel;
$GLOBALS['bookModel'] = $bookModel;
$GLOBALS['categoryModel'] = $categoryModel;
$GLOBALS['cartModel'] = $cartModel;
$GLOBALS['orderModel'] = $orderModel;

// Lấy tham số page từ URL
$page = $_GET['page'] ?? 'home';

// ============================================
// ROUTING
// ============================================

try {
    switch ($page) {
        // ============ HOME ============
        case 'home':
            $controller = new HomeController($bookModel, $categoryModel);
            $controller->index();
            break;

        case 'about':
            $controller = new HomeController($bookModel, $categoryModel);
            $controller->about();
            break;

        case 'contact':
            $controller = new HomeController($bookModel, $categoryModel);
            $controller->contact();
            break;

        // ============ AUTH ============
        case 'login':
            $controller = new AuthController($userModel);
            $controller->showLoginPage();
            break;

        case 'login_process':
            $controller = new AuthController($userModel);
            $controller->login();
            break;

        case 'register':
            $controller = new AuthController($userModel);
            $controller->showRegisterPage();
            break;

        case 'register_process':
            $controller = new AuthController($userModel);
            $controller->register();
            break;

        case 'logout':
            $controller = new AuthController($userModel);
            $controller->logout();
            break;

        // ============ PRODUCTS ============
        case 'products':
            $controller = new ProductController($bookModel, $categoryModel);
            $controller->list();
            break;

        case 'product_detail':
            $controller = new ProductController($bookModel, $categoryModel);
            $controller->detail();
            break;

        // ============ CART ============
        case 'cart':
            $controller = new CartController($cartModel, $bookModel, $orderModel);
            $controller->view();
            break;

        case 'add_to_cart':
            $controller = new CartController($cartModel, $bookModel, $orderModel);
            $controller->add();
            break;

        case 'update_cart_quantity':
            $controller = new CartController($cartModel, $bookModel, $orderModel);
            $controller->updateQuantity();
            break;

        case 'remove_from_cart':
            $controller = new CartController($cartModel, $bookModel, $orderModel);
            $controller->remove();
            break;

        case 'checkout':
            $controller = new CartController($cartModel, $bookModel, $orderModel);
            $controller->checkout();
            break;

        case 'process_checkout':
            $controller = new CartController($cartModel, $bookModel, $orderModel);
            $controller->processCheckout();
            break;

        // ============ ORDERS ============
        case 'order_history':
            $controller = new OrderController($orderModel, $userModel);
            $controller->history();
            break;

        case 'order_detail':
            $controller = new OrderController($orderModel, $userModel);
            $controller->detail();
            break;

        case 'profile':
            $controller = new OrderController($orderModel, $userModel);
            $controller->profile();
            break;

        // ============ ADMIN ============
        case 'admin_dashboard':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->dashboard();
            break;

        // Books
        case 'admin_books':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->listBooks();
            break;

        case 'admin_add_book':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->addBookForm();
            break;

        case 'add_book_process':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->addBook();
            break;

        case 'admin_edit_book':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->editBookForm();
            break;

        case 'edit_book_process':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->editBook();
            break;

        case 'delete_book':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->deleteBook();
            break;

        // Categories
        case 'admin_categories':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->listCategories();
            break;

        case 'admin_add_category':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->addCategoryForm();
            break;

        case 'add_category_process':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->addCategory();
            break;

        case 'admin_edit_category':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->editCategoryForm();
            break;

        case 'edit_category_process':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->editCategory();
            break;

        case 'delete_category':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->deleteCategory();
            break;

        // Customers
        case 'admin_customers':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->listCustomers();
            break;

        case 'admin_customer_detail':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->customerDetail();
            break;

        case 'toggle_customer_status':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->toggleCustomerStatus();
            break;

        // Orders
        case 'admin_orders':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->listOrders();
            break;

        case 'admin_order_detail':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->orderDetail();
            break;

        case 'update_order_status':
            $controller = new AdminController($bookModel, $categoryModel, $userModel, $orderModel);
            $controller->updateOrderStatus();
            break;

        default:
            $_SESSION['error'] = 'Trang không tồn tại';
            header('Location: ?page=home');
            exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'Lỗi: ' . $e->getMessage();
    header('Location: ?page=home');
    exit;
}
