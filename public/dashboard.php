<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_login();

require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../config/db_connection.php';
require_once __DIR__ . '/../helpers/sanitize.php';

$user_id = $_SESSION['user_id'];

// Search and filter parameters (via GET)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';
if (!in_array($statusFilter, ['all', 'pending', 'completed'], true)) {
  $statusFilter = 'all';
}

// Build query with prepared statements. We always filter by user_id to ensure isolation.
$pdo = getPDO();
$sql = 'SELECT id, title, status, due_date, created_at FROM tasks WHERE user_id = ?';
$params = [$user_id];

if ($statusFilter !== 'all') {
  $sql .= ' AND status = ?';
  $params[] = $statusFilter;
}

if ($search !== '') {
  // Use LIKE for portability. For better performance, add a FULLTEXT index (see sql/optional_indexes.sql).
  $sql .= ' AND (title LIKE ? OR description LIKE ?)';
  $like = '%' . $search . '%';
  $params[] = $like;
  $params[] = $like;
}

$sql .= ' ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tasks = $stmt->fetchAll();
?>
<div class="row">
  <div class="col-md-12">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></h1>
    <p>This is your dashboard. Manage your tasks below.</p>
    <form class="row g-2 mb-3" method="get" action="/public/dashboard.php">
      <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Search title or description" value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-auto">
        <select name="status" class="form-select">
          <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All</option>
          <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
          <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>Completed</option>
        </select>
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-outline-primary">Filter</button>
        <a href="/public/dashboard.php" class="btn btn-outline-secondary">Reset</a>
      </div>
      <div class="col text-end">
        <a href="/public/task_create.php" class="btn btn-primary">Add Task</a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Title</th>
            <th>Status</th>
            <th>Due Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($tasks)): ?>
            <tr><td colspan="4">No tasks yet. Create your first task.</td></tr>
          <?php else: ?>
            <?php foreach ($tasks as $task): ?>
              <tr>
                <td><?= htmlspecialchars($task['title']) ?></td>
                <td><?= htmlspecialchars(ucfirst($task['status'])) ?></td>
                <td><?= $task['due_date'] ? htmlspecialchars($task['due_date']) : '-' ?></td>
                <td>
                  <a href="/public/task_edit.php?id=<?= urlencode($task['id']) ?>" class="btn btn-sm btn-secondary">Edit</a>

                  <form method="post" action="/public/actions/task_toggle_status_action.php" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>">
                    <button type="submit" class="btn btn-sm btn-info">
                      <?= $task['status'] === 'pending' ? 'Mark Completed' : 'Mark Pending' ?>
                    </button>
                  </form>

                  <form method="post" action="/public/actions/task_delete_action.php" style="display:inline-block;" onsubmit="return confirm('Delete this task?');">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php
require_once __DIR__ . '/../views/footer.php';
