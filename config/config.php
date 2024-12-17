<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Database configuration
define('MONGODB_URI', $_ENV['MONGODB_URI']);
define('MONGODB_DB', $_ENV['MONGODB_DB']);

// Application configuration
define('APP_NAME', $_ENV['APP_NAME']);
define('APP_ENV', $_ENV['APP_ENV']);
define('APP_URL', $_ENV['APP_URL']);

// JWT configuration
define('JWT_SECRET', $_ENV['JWT_SECRET']);
define('JWT_EXPIRATION', $_ENV['JWT_EXPIRATION']);

// API Keys
define('OPENROUTE_API_KEY', $_ENV['OPENROUTE_API_KEY']);

// Error reporting
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Session configuration
session_start();

// Timezone
date_default_timezone_set('Asia/Jakarta'); 