<?php

/**
 * Helper Functions
 */

/**
 * Escape output for XSS prevention
 */
function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token field
 */
function csrf_field()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * Get CSRF token
 */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Format price with Naira symbol
 */
function format_price($amount)
{
    return '₦' . number_format($amount, 0) . '/-';
}

/**
 * Generate URL
 */
function url($path = '')
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Asset URL
 */
function asset($path)
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Check if user is logged in
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

/**
 * Check if admin is logged in
 */
function is_admin()
{
    return isset($_SESSION['admin_id']);
}

/**
 * Get session user data
 */
function auth($key = null)
{
    if ($key) {
        return $_SESSION['user_' . $key] ?? $_SESSION['admin_' . $key] ?? null;
    }
    return [
        'id' => $_SESSION['user_id'] ?? $_SESSION['admin_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? $_SESSION['admin_name'] ?? null,
        'email' => $_SESSION['user_email'] ?? $_SESSION['admin_email'] ?? null,
    ];
}

/**
 * Display toast if exists
 */
function show_toast()
{
    if (isset($_SESSION['toast'])) {
        $message = e($_SESSION['toast']);
        unset($_SESSION['toast']);
        return $message;
    }
    return null;
}

/**
 * Old input value (for form repopulation)
 */
function old($key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

/**
 * Debug helper
 */
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
