<?php
/**
 * Application configuration.
 * NOTE: For production, override these with environment variables
 * and do NOT commit secrets to version control.
 */
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'todo_app');
define('DB_PORT', '3307');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Base URL (adjust if your app is served from a subdirectory)
define('BASE_URL', '/');

// Session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
