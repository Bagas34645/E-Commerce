<?php

/**
 * Legacy Database Connection
 * Used by non-MVC files for backward compatibility
 * @deprecated Use Model class for new features
 */

// Include configuration
require_once dirname(__DIR__) . '/config/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $conn = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    if (APP_DEBUG) {
        echo "Connection failed: " . $e->getMessage();
    } else {
        echo "Database connection error";
    }
    exit;
}
