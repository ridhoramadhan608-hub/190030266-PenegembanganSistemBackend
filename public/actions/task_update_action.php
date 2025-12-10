<?php
/**
 * Handle task update. Expects POST: id, title, description, due_date
 */
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';
require_once __DIR__ . '/../../helpers/sanitize.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/dashboard.php');
    exit;
}

require_login();

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = sanitize_text($_POST['title'] ?? '');
$description = sanitize_text($_POST['description'] ?? '');
$due_date = sanitize_date($_POST['due_date'] ?? '');

if ($id <= 0 || $title === '') {
    header('Location: /public/dashboard.php');
    exit;
}

$pdo = getPDO();

// Ensure the task belongs to the current user
$check = $pdo->prepare('SELECT user_id FROM tasks WHERE id = ? LIMIT 1');
$check->execute([$id]);
$row = $check->fetch();
if (!$row || $row['user_id'] != $_SESSION['user_id']) {
    header('Location: /public/dashboard.php');
    exit;
}

$update = $pdo->prepare('UPDATE tasks SET title = ?, description = ?, due_date = ? WHERE id = ?');
try {
    $update->execute([$title, $description, $due_date, $id]);
    header('Location: /public/dashboard.php');
    exit;
} catch (PDOException $e) {
    $error = 'Failed to update task.';
    require_once __DIR__ . '/../../views/header.php';
    // Re-render form with current values
    $task = ['id' => $id, 'title' => $title, 'description' => $description, 'due_date' => $due_date];
    $actionUrl = '/public/actions/task_update_action.php';
    require_once __DIR__ . '/../../views/tasks/task_form.php';
    require_once __DIR__ . '/../../views/footer.php';
    exit;
}
