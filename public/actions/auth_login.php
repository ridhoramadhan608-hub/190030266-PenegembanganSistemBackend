<?php
/**
 * Handle login form submission.
 * Expects POST: username, password
 */
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../helpers/sanitize.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . '/public/login.php');
    exit;
}

$username = sanitize_text($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $error = 'Please provide username and password.';
    require_once __DIR__ . '/../../views/header.php';
    require_once __DIR__ . '/../../views/auth/login_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = ? LIMIT 1');
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    // Authentication failed
    $error = 'Invalid credentials.';
    require_once __DIR__ . '/../../views/header.php';
    require_once __DIR__ . '/../../views/auth/login_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}

// Successful login
login_user($user);
header('Location: ' . '/public/dashboard.php');
exit;
