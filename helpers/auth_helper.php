<?php
/**
 * Authentication helper functions.
 * - session handling
 * - simple login/logout helpers
 * - require_login() to protect pages
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in()
{
    return !empty($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . 'public/login.php');
        exit;
    }
}

function login_user(array $user)
{
    // stores minimal user data in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
}

function logout_user()
{
    // clear session securely
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
