<?php
require_once __DIR__ . '/includes/auth.php';
if (is_login()) redirect('dashboard.php');
redirect('login.php');
