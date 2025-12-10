<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_login();

// show create form
$actionUrl = '/public/actions/task_create_action.php';
$task = null;
require_once __DIR__ . '/../views/header.php';
require_once __DIR__ . '/../views/tasks/task_form.php';
require_once __DIR__ . '/../views/footer.php';
