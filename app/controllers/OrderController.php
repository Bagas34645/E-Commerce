<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Cart.php';

class OrderController extends Controller
{

  public function index()
  {
    $this->requireAuth();

    $orderModel = new Order();
    $cartModel = new Cart();
    $userId = $this->getUserId();

    $orders = $orderModel->getByUserId($userId);
    $cartCount = $cartModel->getItemCount($userId);

    $this->view('orders/index', [
      'orders' => $orders,
      'cartCount' => $cartCount,
      'title' => 'My Orders - ' . APP_NAME,
      'currentPage' => 'orders'
    ]);
  }
}
