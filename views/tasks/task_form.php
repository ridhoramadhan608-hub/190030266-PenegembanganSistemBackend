<?php
/**
 * Reusable task form.
 * Expects:
 * - $actionUrl (string) the form action
 * - $task (array|null) optional existing task with keys id,title,description,due_date
 */
$task = $task ?? null;
$title = $task['title'] ?? '';
$description = $task['description'] ?? '';
$due_date = $task['due_date'] ?? '';
?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <form method="post" action="<?= htmlspecialchars($actionUrl) ?>">
      <?php if (!empty($task['id'])): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>">
      <?php endif; ?>

      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required minlength="1" value="<?= htmlspecialchars($title) ?>">
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="4"><?= htmlspecialchars($description) ?></textarea>
      </div>

      <div class="mb-3">
        <label for="due_date" class="form-label">Due Date</label>
        <input type="date" class="form-control" id="due_date" name="due_date" value="<?= htmlspecialchars($due_date) ?>">
      </div>

      <button type="submit" class="btn btn-primary">Save</button>
      <a href="/public/dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>
