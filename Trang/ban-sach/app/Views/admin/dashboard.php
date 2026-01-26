<?php 
$pageTitle = 'Quản Trị Viên'; 
include 'app/Views/layouts/header.php'; 
if ($_SESSION['role'] !== 'admin') {
    header('Location: ?page=home');
    exit;
}
?>

<main class="container admin-container">
    <div class="admin-sidebar">
        <h3>Menu Quản Trị</h3>
        <ul>
            <li><a href="?page=admin_dashboard" class="active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li>
                <a href="#"><i class="fas fa-book"></i> Sách</a>
                <ul>
                    <li><a href="?page=admin_books">Danh Sách Sách</a></li>
                    <li><a href="?page=admin_add_book">Thêm Sách Mới</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fas fa-list"></i> Danh Mục</a>
                <ul>
                    <li><a href="?page=admin_categories">Danh Sách Danh Mục</a></li>
                    <li><a href="?page=admin_add_category">Thêm Danh Mục</a></li>
                </ul>
            </li>
            <li><a href="?page=admin_customers"><i class="fas fa-users"></i> Khách Hàng</a></li>
            <li><a href="?page=admin_orders"><i class="fas fa-shopping-bag"></i> Đơn Hàng</a></li>
            <li><a href="?page=home"><i class="fas fa-arrow-left"></i> Về Trang Chủ</a></li>
        </ul>
    </div>

    <div class="admin-content">
        <!-- Content will be included here -->
    </div>
</main>

<?php include 'app/Views/layouts/footer.php'; ?>
