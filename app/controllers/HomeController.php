<?php

/**
 * Home Controller
 * Handles home page, about, and contact page requests
 */

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Cart.php';

class HomeController extends Controller
{
  /**
   * Display home page with featured products
   */
  public function index()
  {
    try {
      $productModel = new Product();
      $cartModel = new Cart();

      // Handle add to cart
      if ($this->isPost() && isset($_POST['add_to_cart'])) {
        $this->handleAddToCart($cartModel);
      }

      // Get featured products (limit to 6)
      $products = $productModel->getAll(6);

      // Get cart count if user is logged in
      $cartCount = 0;
      $userId = $this->getUserId();
      if ($userId) {
        $cartCount = $cartModel->getItemCount($userId);
      }

      $this->view('home/index', [
        'products' => $products,
        'cartCount' => $cartCount,
        'title' => 'Home - ' . APP_NAME,
        'currentPage' => 'home'
      ]);
    } catch (Exception $e) {
      $this->handleError($e, 'Error loading home page');
    }
  }

  /**
   * Display about page
   */
  public function about()
  {
    try {
      $cartModel = new Cart();
      $cartCount = 0;
      $userId = $this->getUserId();
      if ($userId) {
        $cartCount = $cartModel->getItemCount($userId);
      }

      $this->view('home/about', [
        'cartCount' => $cartCount,
        'title' => 'About - ' . APP_NAME,
        'currentPage' => 'about'
      ]);
    } catch (Exception $e) {
      $this->handleError($e, 'Error loading about page');
    }
  }

  /**
   * Display contact page and handle contact form submission
   */
  public function contact()
  {
    try {
      $cartModel = new Cart();
      $cartCount = 0;
      $userId = $this->getUserId();
      if ($userId) {
        $cartCount = $cartModel->getItemCount($userId);
      }

      if ($this->isPost()) {
        $this->handleContactForm();
      }

      $this->view('home/contact', [
        'cartCount' => $cartCount,
        'title' => 'Contact - ' . APP_NAME,
        'currentPage' => 'contact'
      ]);
    } catch (Exception $e) {
      $this->handleError($e, 'Error loading contact page');
    }
  }

  /**
   * Handle contact form submission
   */
  private function handleContactForm()
  {
    try {
      // Handle contact form submission
      $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
      $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
      $number = filter_var($_POST['number'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
      $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

      if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = 'Harap isi semua field yang diperlukan';
        return;
      }

      $userId = $this->getUserId() ?: '';

      // Use config database connection instead of hardcoded
      require_once __DIR__ . '/../../components/connect.php';

      // Check if message already exists
      $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
      $select_message->execute([$name, $email, $number, $message]);

      if ($select_message->rowCount() > 0) {
        $_SESSION['error'] = 'Pesan sudah pernah dikirim!';
      } else {
        // Insert new message
        $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
        $insert_message->execute([$userId, $name, $email, $number, $message]);

        $_SESSION['success'] = 'Pesan berhasil dikirim! Kami akan segera menghubungi Anda.';
      }
    } catch (Exception $e) {
      $_SESSION['error'] = 'Terjadi kesalahan saat mengirim pesan';
      if (APP_DEBUG) {
        error_log("Contact form error: " . $e->getMessage());
      }
    }
  }

  /**
   * Handle add to cart functionality
   */
  private function handleAddToCart($cartModel)
  {
    try {
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
    } catch (Exception $e) {
      $_SESSION['error'] = 'Error adding to cart';
      if (APP_DEBUG) {
        error_log("Add to cart error: " . $e->getMessage());
      }
    }
  }

  /**
   * Handle errors gracefully
   */
  private function handleError($e, $message = 'An error occurred')
  {
    if (APP_DEBUG) {
      error_log($message . ": " . $e->getMessage());
      $_SESSION['error'] = $message . ": " . $e->getMessage();
    } else {
      $_SESSION['error'] = $message;
    }
    $this->redirect('/');
  }
}
