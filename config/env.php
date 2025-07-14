<?php

/**
 * Environment Configuration Loader
 * Load configuration from .env file if exists
 */

if (!function_exists('loadEnvFile')) {
  function loadEnvFile($filePath)
  {
    if (!file_exists($filePath)) {
      return false;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
      // Skip comments
      if (strpos(trim($line), '#') === 0) {
        continue;
      }

      // Parse KEY=VALUE
      if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);

        // Remove quotes if present
        if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
          (substr($value, 0, 1) === "'" && substr($value, -1) === "'")
        ) {
          $value = substr($value, 1, -1);
        }

        // Set environment variable
        $_ENV[$key] = $value;
        putenv("$key=$value");
      }
    }

    return true;
  }
}

// Load .env file if exists
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
  loadEnvFile($envFile);
}

// Helper function to get environment variable with default
if (!function_exists('env')) {
  function env($key, $default = null)
  {
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

    if ($value === false) {
      return $default;
    }

    // Convert string boolean to actual boolean
    if (strtolower($value) === 'true') {
      return true;
    } elseif (strtolower($value) === 'false') {
      return false;
    }

    return $value;
  }
}
