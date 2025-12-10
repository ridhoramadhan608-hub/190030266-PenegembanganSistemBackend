<?php
/**
 * Delete a task. Expects POST: id
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

// Delete only if belongs to user
$del = $pdo->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
$del->execute([$id, $_SESSION['user_id']]);

header('Location: /public/dashboard.php');
exit;
