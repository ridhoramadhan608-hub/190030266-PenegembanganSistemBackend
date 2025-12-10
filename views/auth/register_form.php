<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Register</h2>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" action="/public/actions/auth_register.php">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required minlength="3">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required minlength="6">
      </div>
      <div class="mb-3">
        <label for="password_confirm" class="form-label">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required minlength="6">
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
  </div>
</div>
