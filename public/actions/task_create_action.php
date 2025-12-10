<?php
/**
 * Handle task creation for the logged-in user.
 * Expects POST: title, description, due_date
 */
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';
require_once __DIR__ . '/../../helpers/sanitize.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/task_create.php');
    exit;
}

require_login();

$title = sanitize_text($_POST['title'] ?? '');
$description = sanitize_text($_POST['description'] ?? '');
$due_date = sanitize_date($_POST['due_date'] ?? '');

if ($title === '') {
    $error = 'Title is required.';
    require_once __DIR__ . '/../../views/header.php';
    require_once __DIR__ . '/../../views/tasks/task_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('INSERT INTO tasks (user_id, title, description, due_date) VALUES (?, ?, ?, ?)');
try {
    $stmt->execute([$_SESSION['user_id'], $title, $description, $due_date]);
    header('Location: /public/dashboard.php');
    exit;
} catch (PDOException $e) {
    $error = 'Failed to create task.';
    require_once __DIR__ . '/../../views/header.php';
    require_once __DIR__ . '/../../views/tasks/task_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}
