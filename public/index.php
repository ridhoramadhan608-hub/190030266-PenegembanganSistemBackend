<?php
// Simple index: redirect to dashboard or login
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (!empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'public/dashboard.php');
    exit;
}

header('Location: ' . BASE_URL . 'public/login.php');
exit;
