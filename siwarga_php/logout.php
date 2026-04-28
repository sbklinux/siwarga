<?php
require_once __DIR__ . '/includes/auth.php';
if (is_login()) audit_log($pdo, $_SESSION['user_id'], 'logout', 'auth', 'User logout');
session_destroy();
redirect('login.php');
