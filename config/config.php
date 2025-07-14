<?php

/**
 * Application Configuration
 * Sentra Durian Tegal E-Commerce System
 */

// Load environment configuration
require_once __DIR__ . '/env.php';

// Database Configuration
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_NAME', env('DB_NAME', 'food_db'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_CHARSET', env('DB_CHARSET', 'utf8mb4'));

// Application Configuration
define('APP_NAME', env('APP_NAME', 'Sentra Durian Tegal'));
define('BASE_URL', env('BASE_URL', 'http://localhost/E-Commerce'));
define('APP_VERSION', env('APP_VERSION', '1.0.0'));

// Environment Configuration
define('APP_ENV', env('APP_ENV', 'development')); // production, development
define('APP_DEBUG', env('APP_DEBUG', true));

// Path Configuration
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/views');
define('UPLOAD_PATH', ROOT_PATH . '/uploaded_img');

// Security Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
