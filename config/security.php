<?php

/**
 * Security Headers Configuration
 * Include this file in your main index.php for additional security
 */

// Only set headers if not already sent
if (!headers_sent()) {
  // Prevent clickjacking
  header('X-Frame-Options: DENY');

  // Prevent MIME type sniffing
  header('X-Content-Type-Options: nosniff');

  // Enable XSS filtering
  header('X-XSS-Protection: 1; mode=block');

  // Referrer Policy
  header('Referrer-Policy: strict-origin-when-cross-origin');

  // Content Security Policy
  $csp = "default-src 'self'; " .
    "script-src 'self' 'unsafe-inline'; " .
    "style-src 'self' 'unsafe-inline'; " .
    "img-src 'self' data:; " .
    "font-src 'self';";
  header('Content-Security-Policy: ' . $csp);

  // HTTPS redirect (uncomment for production with SSL)
  /*
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
        $redirectURL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: $redirectURL");
        exit();
    }
    */

  // Prevent caching of sensitive pages
  if (
    strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ||
    strpos($_SERVER['REQUEST_URI'], '/profile') !== false ||
    strpos($_SERVER['REQUEST_URI'], '/orders') !== false
  ) {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
  }
}
