<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Order.php';

class CartController extends Controller
{

  public function index()
  {
    $this->requireAuth();

    $cartModel = new Cart();
    $userId = $this->getUserId();

    $cartSummary = $cartModel->getCartSummary($userId);

    $this->view('cart/index', [
      'cartItems' => $cartSummary['items'],
      'total' => $cartSummary['total'],
      'cartCount' => $cartSummary['count'],
      'title' => 'Shopping Cart - ' . APP_NAME,
      'currentPage' => 'cart'
    ]);
  }

  public function add()
  {
    if (!$this->isPost()) {
      $this->json(['success' => false, 'message' => 'Invalid request method']);
      return;
    }

    $userId = $this->getUserId();
    if (!$userId) {
      $this->json(['success' => false, 'message' => 'Please login first']);
      return;
    }

    $productId = $_POST['pid'] ?? '';
    $quantity = (int)($_POST['qty'] ?? 1);

    if (empty($productId) || $quantity <= 0) {
      $this->json(['success' => false, 'message' => 'Invalid product or quantity']);
      return;
    }

    // Get product details
    $productModel = new Product();
    $product = $productModel->findById($productId);

    if (!$product) {
      $this->json(['success' => false, 'message' => 'Product not found']);
      return;
    }

    // Add to cart
    $cartModel = new Cart();
    $result = $cartModel->addItem(
      $userId,
      $productId,
      $product['name'],
      $product['price'],
      $quantity,
      $product['image']
    );

    if ($result) {
      $cartCount = $cartModel->getItemCount($userId);
      $this->json([
        'success' => true,
        'message' => 'Item added to cart successfully',
        'cartCount' => $cartCount
      ]);
    } else {
      $this->json(['success' => false, 'message' => 'Failed to add item to cart']);
    }
  }

  public function update()
  {
    $this->requireAuth();

    if (!$this->isPost()) {
      $this->redirect('/cart');
      return;
    }

    $cartId = $_POST['cart_id'] ?? '';
    $quantity = (int)($_POST['qty'] ?? 1);

    if (empty($cartId)) {
      $_SESSION['error'] = 'Invalid cart item';
      $this->redirect('/cart');
      return;
    }

    $cartModel = new Cart();
    $result = $cartModel->updateQuantity($cartId, $quantity);

    if ($result) {
      $_SESSION['success'] = 'Cart updated successfully';
    } else {
      $_SESSION['error'] = 'Failed to update cart';
    }

    $this->redirect('/cart');
  }

  public function remove()
  {
    $this->requireAuth();

    if (!$this->isPost()) {
      $this->redirect('/cart');
      return;
    }

    $cartId = $_POST['cart_id'] ?? '';

    if (empty($cartId)) {
      $_SESSION['error'] = 'Invalid cart item';
      $this->redirect('/cart');
      return;
    }

    $cartModel = new Cart();
    $result = $cartModel->removeItem($cartId);

    if ($result) {
      $_SESSION['success'] = 'Item removed from cart';
    } else {
      $_SESSION['error'] = 'Failed to remove item';
    }

    $this->redirect('/cart');
  }

  public function clear()
  {
    $this->requireAuth();

    $cartModel = new Cart();
    $userId = $this->getUserId();
    $result = $cartModel->clearByUserId($userId);

    if ($result) {
      $_SESSION['success'] = 'Cart cleared successfully';
    } else {
      $_SESSION['error'] = 'Failed to clear cart';
    }

    $this->redirect('/cart');
  }

  public function checkout()
  {
    $this->requireAuth();

    $cartModel = new Cart();
    $userId = $this->getUserId();

    $cartSummary = $cartModel->getCartSummary($userId);

    if (empty($cartSummary['items'])) {
      $_SESSION['error'] = 'Your cart is empty';
      $this->redirect('/cart');
      return;
    }

    if ($this->isPost()) {
      $this->processCheckout($cartSummary);
      return;
    }

    $this->view('cart/checkout', [
      'cartItems' => $cartSummary['items'],
      'total' => $cartSummary['total'],
      'cartCount' => $cartSummary['count'],
      'title' => 'Checkout - ' . APP_NAME,
      'currentPage' => 'checkout'
    ]);
  }

  private function processCheckout($cartSummary)
  {
    $name = $_POST['name'] ?? '';
    $number = $_POST['number'] ?? '';
    $email = $_POST['email'] ?? '';
    $method = $_POST['method'] ?? '';
    $address = $_POST['address'] ?? '';

    if (empty($name) || empty($number) || empty($email) || empty($method) || empty($address)) {
      $_SESSION['error'] = 'Please fill all required fields';
      $this->redirect('/checkout');
      return;
    }

    // Prepare order data
    $totalProducts = '';
    foreach ($cartSummary['items'] as $item) {
      $totalProducts .= $item['name'] . ' (' . $item['quantity'] . '), ';
    }
    $totalProducts = rtrim($totalProducts, ', ');

    $orderData = [
      'user_id' => $this->getUserId(),
      'name' => $name,
      'number' => $number,
      'email' => $email,
      'method' => $method,
      'address' => $address,
      'total_products' => $totalProducts,
      'total_price' => $cartSummary['total']
    ];

    // Create order
    $orderModel = new Order();
    $result = $orderModel->create($orderData);

    if ($result) {
      // Clear cart after successful order
      $cartModel = new Cart();
      $cartModel->clearByUserId($this->getUserId());

      $_SESSION['success'] = 'Order placed successfully! We will contact you soon.';
      $this->redirect('/orders');
    } else {
      $_SESSION['error'] = 'Failed to place order. Please try again.';
      $this->redirect('/checkout');
    }
  }

  public function items()
  {
    $userId = $this->getUserId();
    if (!$userId) {
      $this->json(['success' => false, 'message' => 'Please login first']);
      return;
    }

    $cartModel = new Cart();
    $cartSummary = $cartModel->getCartSummary($userId);

    $this->json([
      'success' => true,
      'items' => $cartSummary['items'],
      'total' => $cartSummary['total'],
      'count' => $cartSummary['count']
    ]);
  }
}
