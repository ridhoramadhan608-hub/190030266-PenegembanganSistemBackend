<?php
/**
 * Toggle a task's status between 'pending' and 'completed'.
 * Expects POST: id
 */
require_once __DIR__ . '/../../config/db_connection.php';
require_once __DIR__ . '/../../helpers/auth_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/dashboard.php');
    exit;
}

require_login();

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header('Location: /public/dashboard.php');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT status, user_id FROM tasks WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$task = $stmt->fetch();
if (!$task || $task['user_id'] != $_SESSION['user_id']) {
    header('Location: /public/dashboard.php');
    exit;
}

$newStatus = $task['status'] === 'pending' ? 'completed' : 'pending';
$update = $pdo->prepare('UPDATE tasks SET status = ? WHERE id = ?');
$update->execute([$newStatus, $id]);

header('Location: /public/dashboard.php');
exit;
