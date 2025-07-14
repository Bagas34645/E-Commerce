<?php

/**
 * Application Configuration
 * Sentra Durian Tegal E-Commerce System
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'food_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'Sentra Durian Tegal');
define('BASE_URL', 'http://localhost/E-Commerce');
define('APP_VERSION', '1.0.0');

// Environment Configuration
define('APP_ENV', 'production'); // production, development
define('APP_DEBUG', false);

// Path Configuration
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/views');
define('UPLOAD_PATH', ROOT_PATH . '/uploaded_img');

// Security Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
