<?php
/**
 * CONFIG.PHP - Cấu Hình Ứng Dụng
 * File này chứa các hằng số cấu hình chung
 */

// ================================================
// DATABASE CONFIGURATION
// ================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'tuans');
define('DB_PASS', 'Popopoq2106@');
define('DB_NAME', 'bookstore_db');
define('DB_CHARSET', 'utf8mb4');

// ================================================
// APPLICATION CONFIGURATION
// ================================================
define('APP_NAME', 'BookStore');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost/duanmau/ban-sach');
define('APP_ENV', 'development'); // development | production

// ================================================
// PATHS
// ================================================
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/images/books/');

// ================================================
// SECURITY
// ================================================
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_ATTEMPT_TIMEOUT', 15 * 60); // 15 minutes
define('SESSION_TIMEOUT', 30 * 60); // 30 minutes
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_UPLOAD_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// ================================================
// PAGINATION
// ================================================
define('ITEMS_PER_PAGE', 20);

// ================================================
// EMAIL CONFIGURATION (Optional)
// ================================================
define('MAIL_FROM', 'noreply@bookstore.com');
define('MAIL_FROM_NAME', 'BookStore');

// ================================================
// API CONFIGURATION
// ================================================
define('API_ENABLED', false);
define('API_VERSION', 'v1');

// ================================================
// ERROR HANDLING
// ================================================
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// ================================================
// TIMEZONE
// ================================================
date_default_timezone_set('Asia/Ho_Chi_Minh');

// ================================================
// USEFUL FUNCTIONS
// ================================================

/**
 * Kiểm tra ứng dụng ở chế độ development
 */
function isDevelopment() {
    return APP_ENV === 'development';
}

/**
 * Kiểm tra ứng dụng ở chế độ production
 */
function isProduction() {
    return APP_ENV === 'production';
}

/**
 * Redirect với thông báo
 */
function redirectWithMessage($url, $message, $type = 'success') {
    $_SESSION[$type] = $message;
    header("Location: $url");
    exit;
}

/**
 * Kiểm tra user đã đăng nhập
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Kiểm tra user là admin
 */
function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

/**
 * Kiểm tra user là customer
 */
function isCustomer() {
    return isLoggedIn() && $_SESSION['role'] === 'customer';
}

/**
 * Get current user ID
 */
function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 */
function getCurrentUserRole() {
    return $_SESSION['role'] ?? null;
}

/**
 * Format price to VND currency
 */
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . '₫';
}

/**
 * Slugify string (convert to URL-friendly)
 */
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = preg_replace('~-+~', '-', $text);
    $text = trim($text, '-');
    return strtolower($text);
}

/**
 * Generate random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Get current URL
 */
function getCurrentUrl() {
    return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') .
           "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Vietnam)
 */
function validatePhone($phone) {
    return preg_match('/^(\+84|0)[0-9]{9,10}$/', $phone);
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Truncate text
 */
function truncate($text, $length = 100) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

/**
 * Convert bytes to human readable format
 */
function bytesToHuman($bytes) {
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Get status badge CSS class
 */
function getStatusBadgeClass($status) {
    $classes = [
        'pending' => 'badge-warning',
        'confirmed' => 'badge-info',
        'shipped' => 'badge-primary',
        'delivered' => 'badge-success',
        'cancelled' => 'badge-danger'
    ];
    return $classes[$status] ?? 'badge-secondary';
}

/**
 * Get status label
 */
function getStatusLabel($status) {
    $labels = [
        'pending' => 'Chờ Xác Nhận',
        'confirmed' => 'Đã Xác Nhận',
        'shipped' => 'Đang Giao',
        'delivered' => 'Đã Giao',
        'cancelled' => 'Đã Hủy'
    ];
    return $labels[$status] ?? $status;
}
