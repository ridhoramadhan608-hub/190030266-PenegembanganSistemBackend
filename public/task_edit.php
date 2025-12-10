<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_login();
require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../helpers/sanitize.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: /public/dashboard.php');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT id, title, description, due_date FROM tasks WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->execute([$id, $_SESSION['user_id']]);
$task = $stmt->fetch();
if (!$task) {
    // Task not found or not owned by user
    header('Location: /public/dashboard.php');
    exit;
}

$actionUrl = '/public/actions/task_update_action.php';
require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/tasks/task_form.php';
require_once __DIR__ . '/../views/footer.php';
