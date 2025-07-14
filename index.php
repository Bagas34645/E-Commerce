<?php

/**
 * Main Entry Point - E-Commerce MVC System
 * Sentra Durian Tegal
 * All requests are routed through this file
 */

// Include configuration first
require_once __DIR__ . '/config/config.php';

// Set error reporting based on environment
if (APP_DEBUG && APP_ENV === 'development') {
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
} else {
  error_reporting(0);
  ini_set('display_errors', 0);
}

// Include and run MVC system
if (file_exists(__DIR__ . '/index_mvc.php')) {
  require_once __DIR__ . '/index_mvc.php';
} else {
  // Show user-friendly error for production
  if (APP_ENV === 'production') {
    echo "<h1>System Maintenance</h1><p>The system is currently under maintenance. Please try again later.</p>";
  } else {
    echo "System Error: MVC files not found.";
  }
  exit;
}
