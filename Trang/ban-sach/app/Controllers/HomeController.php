<?php
/**
 * Home Controller
 * Quản lý trang chủ
 */
class HomeController {
    private $bookModel;
    private $categoryModel;

    public function __construct($bookModel, $categoryModel) {
        $this->bookModel = $bookModel;
        $this->categoryModel = $categoryModel;
    }

    /**
     * Trang chủ
     */
    public function index() {
        $featuredBooks = $this->bookModel->getFeatured(8);
        $categories = $this->categoryModel->getAll();
        
        include 'app/Views/home/index.php';
    }

    /**
     * Trang về chúng tôi
     */
    public function about() {
        include 'app/Views/home/about.php';
    }

    /**
     * Trang liên hệ
     */
    public function contact() {
        include 'app/Views/home/contact.php';
    }
}
