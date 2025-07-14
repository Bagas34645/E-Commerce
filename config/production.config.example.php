<?php

/**
 * Production Database Configuration Template
 * Copy this file as production.config.php and update with your production values
 */

// Production Database Configuration
define('PROD_DB_HOST', 'your-production-host');
define('PROD_DB_NAME', 'your-production-database');
define('PROD_DB_USER', 'your-production-username');
define('PROD_DB_PASS', 'your-production-password');
define('PROD_DB_CHARSET', 'utf8mb4');

// Production Application Configuration
define('PROD_APP_NAME', 'Sentra Durian Tegal');
define('PROD_BASE_URL', 'https://yourdomain.com');
define('PROD_APP_VERSION', '1.0.0');

// Production Environment Configuration
define('PROD_APP_ENV', 'production');
define('PROD_APP_DEBUG', false);

// Production Security Configuration
define('PROD_SESSION_TIMEOUT', 1800); // 30 minutes
define('PROD_COOKIE_SECURE', true); // Only over HTTPS
define('PROD_COOKIE_HTTPONLY', true);

// Production Email Configuration (if needed)
define('PROD_MAIL_HOST', 'your-smtp-host');
define('PROD_MAIL_PORT', 587);
define('PROD_MAIL_USERNAME', 'your-email@yourdomain.com');
define('PROD_MAIL_PASSWORD', 'your-email-password');

/*
 * Usage Instructions:
 * 1. Copy this file as production.config.php
 * 2. Update all values with your production settings
 * 3. In your main config.php, include this file for production:
 * 
 * if (APP_ENV === 'production') {
 *     require_once __DIR__ . '/production.config.php';
 * }
 */
