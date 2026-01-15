<?php

/**
 * Front Controller
 * All requests are routed through this file
 */

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Load configuration
require_once '../config/database.php';

// Load helpers
require_once '../core/helpers.php';

// Load and initialize the application
require_once '../core/App.php';

$app = new App();
