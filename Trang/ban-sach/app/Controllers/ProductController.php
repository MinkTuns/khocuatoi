<?php
/**
 * Product Controller
 * Quản lý sản phẩm
 */
class ProductController {
    private $bookModel;
    private $categoryModel;

    public function __construct($bookModel, $categoryModel) {
        $this->bookModel = $bookModel;
        $this->categoryModel = $categoryModel;
    }

    /**
     * Danh sách sản phẩm
     */
    public function list() {
        $keyword = $_GET['keyword'] ?? '';
        $categoryId = $_GET['category'] ?? '';
        $minPrice = $_GET['min_price'] ?? '';
        $maxPrice = $_GET['max_price'] ?? '';

        // Tìm kiếm
        $books = $this->bookModel->search($keyword, $categoryId, $minPrice, $maxPrice);
        $categories = $this->categoryModel->getAll();

        include 'app/Views/products/list.php';
    }

    /**
     * Chi tiết sản phẩm
     */
    public function detail() {
        if (!isset($_GET['id'])) {
            header('Location: ?page=products');
            exit;
        }

        $bookId = (int)$_GET['id'];
        $book = $this->bookModel->getById($bookId);

        if (!$book) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại';
            header('Location: ?page=products');
            exit;
        }

        include 'app/Views/products/detail.php';
    }
}
