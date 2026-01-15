<?php

/**
 * Database Configuration
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bookwave');

// Dynamic Base URL
$host = $_SERVER['HTTP_HOST'];

if (strpos($host, 'localhost') !== false) {

    define('BASE_URL', '/bookwave/public');
} else {
    
    define('BASE_URL', '/public');
}

define('APP_NAME', 'BookWave');
