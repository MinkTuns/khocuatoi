<?php
/**
 * Auth Controller
 * Quản lý đăng nhập, đăng ký, đăng xuất
 */
class AuthController {
    private $userModel;

    public function __construct($userModel) {
        $this->userModel = $userModel;
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function showLoginPage() {
        include 'app/Views/auth/login.php';
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function showRegisterPage() {
        include 'app/Views/auth/register.php';
    }

    /**
     * Xử lý đăng nhập
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            // Validate dữ liệu
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng nhập tên đăng nhập và mật khẩu';
                header('Location: ?page=login');
                exit;
            }

            // Lấy user từ database
            $user = $this->userModel->getByUsername($username);

            if (!$user) {
                $_SESSION['error'] = 'Tài khoản không tồn tại';
                header('Location: ?page=login');
                exit;
            }

            // Kiểm tra tài khoản có hoạt động không
            if (!$user['is_active']) {
                $_SESSION['error'] = 'Tài khoản của bạn đã bị khóa';
                header('Location: ?page=login');
                exit;
            }

            // Kiểm tra mật khẩu
            if (!$this->userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['error'] = 'Mật khẩu không chính xác';
                header('Location: ?page=login');
                exit;
            }

            // Lưu session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];

            $_SESSION['success'] = 'Đăng nhập thành công!';

            // Redirect dựa trên role
            if ($user['role'] === 'admin') {
                header('Location: ?page=admin_dashboard');
            } else {
                header('Location: ?page=home');
            }
            exit;
        }
    }

    /**
     * Xử lý đăng ký
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $fullname = trim($_POST['fullname'] ?? '');

            // Validate dữ liệu
            $errors = [];

            if (empty($username) || strlen($username) < 3) {
                $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }

            if (empty($password) || strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }

            if ($password !== $confirmPassword) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }

            if (empty($fullname)) {
                $errors[] = 'Vui lòng nhập họ và tên';
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header('Location: ?page=register');
                exit;
            }

            // Đăng ký
            if ($this->userModel->register($username, $email, $password, $fullname)) {
                $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập';
                header('Location: ?page=login');
                exit;
            } else {
                $_SESSION['error'] = 'Tên đăng nhập hoặc email đã tồn tại';
                header('Location: ?page=register');
                exit;
            }
        }
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        session_destroy();
        $_SESSION = [];
        header('Location: ?page=home');
        exit;
    }
}
