<?php

/**
 * Session Helper Functions
 * Provides safe session management across the application
 */

if (!function_exists('startSession')) {
  /**
   * Start session safely without warnings
   */
  function startSession()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
  }
}

if (!function_exists('getSessionValue')) {
  /**
   * Get session value safely
   */
  function getSessionValue($key, $default = null)
  {
    startSession();
    return $_SESSION[$key] ?? $default;
  }
}

if (!function_exists('setSessionValue')) {
  /**
   * Set session value safely
   */
  function setSessionValue($key, $value)
  {
    startSession();
    $_SESSION[$key] = $value;
  }
}

if (!function_exists('isLoggedIn')) {
  /**
   * Check if user is logged in
   */
  function isLoggedIn()
  {
    return getSessionValue('user_id') !== null;
  }
}

if (!function_exists('getUserId')) {
  /**
   * Get current user ID
   */
  function getUserId()
  {
    return getSessionValue('user_id');
  }
}

if (!function_exists('destroySession')) {
  /**
   * Destroy session safely
   */
  function destroySession()
  {
    if (session_status() !== PHP_SESSION_NONE) {
      session_destroy();
    }
  }
}
