<?php

/**
 * MVC Bootstrap File
 * Handles routing and controller instantiation
 */

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Configuration is already included from index.php
// Set error reporting based on environment
if (APP_DEBUG && APP_ENV === 'development') {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
} else {
  error_reporting(0);
  ini_set('display_errors', 0);
}

// Include core classes
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';

// Include controllers
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/ProductController.php';
require_once __DIR__ . '/app/controllers/CartController.php';
require_once __DIR__ . '/app/controllers/OrderController.php';

// Create router instance
$router = new Router();

// Define routes
// Home routes
$router->get('/', 'HomeController', 'index');
$router->get('/home', 'HomeController', 'index');
$router->get('/about', 'HomeController', 'about');
$router->get('/tentang', 'HomeController', 'about');
$router->get('/contact', 'HomeController', 'contact');
$router->get('/kontak', 'HomeController', 'contact');
$router->post('/contact', 'HomeController', 'contact');
$router->post('/kontak', 'HomeController', 'contact');

// Product routes
$router->get('/menu', 'ProductController', 'index');
$router->get('/produk', 'ProductController', 'index');
$router->get('/product/{id}', 'ProductController', 'show');
$router->get('/category/{category}', 'ProductController', 'category');
$router->get('/search', 'ProductController', 'search');
$router->post('/search', 'ProductController', 'search');

// Cart routes
$router->get('/cart', 'CartController', 'index');
$router->get('/cart/items', 'CartController', 'items');
$router->post('/cart/add', 'CartController', 'add');
$router->post('/cart/update', 'CartController', 'update');
$router->post('/cart/remove', 'CartController', 'remove');
$router->post('/cart/clear', 'CartController', 'clear');
$router->get('/checkout', 'CartController', 'checkout');
$router->post('/checkout', 'CartController', 'checkout');

// Order routes
$router->get('/orders', 'OrderController', 'index');

// Dispatch the request
try {
  $router->dispatch();
} catch (Exception $e) {
  // Display error for debugging
  echo "<!DOCTYPE html><html><head><title>MVC Error</title></head><body>";
  echo "<h1>MVC System Error</h1>";
  echo "<div style='background: #f8d7da; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin: 10px;'>";
  echo "<strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
  echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . "<br>";
  echo "<strong>Line:</strong> " . $e->getLine() . "<br>";
  echo "<strong>Trace:</strong><br><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
  echo "</div>";
  echo "<p><a href='/E-Commerce/test_routing.php'>← Back to Test Page</a></p>";
  echo "</body></html>";
}
