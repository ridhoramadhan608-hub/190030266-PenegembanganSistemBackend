<?php
/**
 * Handle registration form submission.
 * Expects POST: username, password, password_confirm
 */
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../helpers/sanitize.php';

// Simple POST guard
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . '/public/register.php');
    exit;
}

$username = sanitize_text($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

// Basic validation
if (strlen($username) < 3 || strlen($password) < 6) {
    $error = 'Username must be at least 3 chars and password at least 6 chars.';
    // simple feedback: show register page with error
    require_once __DIR__ . '/../../views/header.php';
    require_once __DIR__ . '/../../views/auth/register_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}

if ($password !== $password_confirm) {
    $error = 'Passwords do not match.';
    require_once __DIR__ . '/../../views/header.php';
    require_once __DIR__ . '/../../views/auth/register_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}

$pdo = getPDO();

// Check if username exists
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetch()) {
    $error = 'Username already taken.';
    require_once __DIR__ . '/../../views/header.php';
    require_once __DIR__ . '/../../views/auth/register_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$insert = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
try {
    $insert->execute([$username, $password_hash]);
    // Redirect to login
    header('Location: ' . '/public/login.php?registered=1');
    exit;
} catch (PDOException $e) {
    // In production, log the error
    $error = 'Registration failed. Please try again later.';
    require_once __DIR__ . '/../../views/header.php';
    require_once __DIR__ . '/../../views/auth/register_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}
