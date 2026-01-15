<?php
// Start session at the very beginning
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't show errors to users
ini_set('log_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bookwave');

// Create connection using mysqli with error handling
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed. Please try again later.");
}

// Set charset to prevent encoding issues
$conn->set_charset("utf8mb4");

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Sanitize output to prevent XSS attacks
 */
function e($string)
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Verify CSRF token
 */
function verify_csrf($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF token input field
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * Safe redirect with exit
 */
function redirect($url)
{
    header('Location: ' . $url);
    exit();
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
function is_admin_logged_in()
{
    return isset($_SESSION['admin_id']);
}

/**
 * Require user login or redirect
 */
function require_login()
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

/**
 * Require admin login or redirect
 */
function require_admin()
{
    if (!is_admin_logged_in()) {
        redirect('login.php');
    }
}

/**
 * Display messages with proper styling
 */
function display_messages($messages, $type = 'info')
{
    if (!empty($messages)) {
        foreach ($messages as $msg) {
            $class = $type === 'success' ? 'message success' : ($type === 'error' ? 'message error' : 'message');
            echo '<div class="' . $class . '">
                <span>' . e($msg) . '</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>';
        }
    }
}

/**
 * Format price with Naira symbol
 */
function format_price($amount)
{
    return '₦' . number_format($amount, 0) . '/-';
}
