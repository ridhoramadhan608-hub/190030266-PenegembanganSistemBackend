<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

// Perform logout and redirect to login
logout_user();
header('Location: ' . BASE_URL . 'public/login.php');
exit;
