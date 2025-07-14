<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';

class ProductController extends Controller
{

  public function index()
  {
    $productModel = new Product();
    $cartModel = new Cart();

    // Handle add to cart
    if ($this->isPost() && isset($_POST['add_to_cart'])) {
      $this->handleAddToCart($cartModel);
    }

    $products = $productModel->getAll();
    $categories = $productModel->getCategories();

    // Get cart count if user is logged in
    $cartCount = 0;
    $userId = $this->getUserId();
    if ($userId) {
      $cartCount = $cartModel->getItemCount($userId);
    }

    $this->view('products/index', [
      'products' => $products,
      'categories' => $categories,
      'cartCount' => $cartCount,
      'title' => 'Menu - ' . APP_NAME,
      'currentPage' => 'menu'
    ]);
  }

  public function show($id)
  {
    $productModel = new Product();
    $cartModel = new Cart();

    $product = $productModel->findById($id);

    if (!$product) {
      http_response_code(404);
      $this->view('errors/404');
      return;
    }

    // Get related products from same category
    $relatedProducts = $productModel->getByCategory($product['category']);
    // Remove current product from related products
    $relatedProducts = array_filter($relatedProducts, function ($p) use ($id) {
      return $p['id'] != $id;
    });
    $relatedProducts = array_slice($relatedProducts, 0, 4);

    // Get cart count if user is logged in
    $cartCount = 0;
    $userId = $this->getUserId();
    if ($userId) {
      $cartCount = $cartModel->getItemCount($userId);
    }

    $this->view('products/detail', [
      'product' => $product,
      'relatedProducts' => $relatedProducts,
      'cartCount' => $cartCount,
      'title' => $product['name'] . ' - ' . APP_NAME,
      'currentPage' => 'menu'
    ]);
  }

  public function category($category)
  {
    $productModel = new Product();
    $cartModel = new Cart();

    $products = $productModel->getByCategory($category);
    $categories = $productModel->getCategories();

    // Get cart count if user is logged in
    $cartCount = 0;
    $userId = $this->getUserId();
    if ($userId) {
      $cartCount = $cartModel->getItemCount($userId);
    }

    $this->view('products/category', [
      'products' => $products,
      'categories' => $categories,
      'currentCategory' => $category,
      'cartCount' => $cartCount,
      'title' => ucfirst($category) . ' - ' . APP_NAME,
      'currentPage' => 'menu'
    ]);
  }

  public function search()
  {
    $productModel = new Product();
    $cartModel = new Cart();

    $keyword = $_GET['search'] ?? '';
    $products = [];

    if (!empty($keyword)) {
      $products = $productModel->search($keyword);
    }

    // Get cart count if user is logged in
    $cartCount = 0;
    $userId = $this->getUserId();
    if ($userId) {
      $cartCount = $cartModel->getItemCount($userId);
    }

    $this->view('products/search', [
      'products' => $products,
      'keyword' => $keyword,
      'cartCount' => $cartCount,
      'title' => 'Search Results - ' . APP_NAME,
      'currentPage' => 'menu'
    ]);
  }

  private function handleAddToCart($cartModel)
  {
    $userId = $this->getUserId();
    if (!$userId) {
      $this->redirect('/login.php');
      return;
    }

    $pid = filter_var($_POST['pid'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_var($_POST['price'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $image = filter_var($_POST['image'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
    $qty = filter_var($_POST['qty'] ?? 1, FILTER_SANITIZE_NUMBER_INT);

    if (empty($pid) || empty($name) || empty($price)) {
      $_SESSION['error'] = 'Invalid product data';
      return;
    }

    // Check if item already exists in cart
    if ($cartModel->itemExists($userId, $name)) {
      $_SESSION['error'] = 'Already added to cart!';
    } else {
      // Add to cart
      if ($cartModel->add($userId, $pid, $name, $price, $qty, $image)) {
        $_SESSION['success'] = 'Added to cart!';
      } else {
        $_SESSION['error'] = 'Failed to add to cart';
      }
    }
  }
}
