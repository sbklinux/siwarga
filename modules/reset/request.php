<?php
require_once __DIR__ . '/../../config/config.php';
// Tidak pakai require_login (user belum bisa login)
csrf_check();

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$no_hp    = trim($_POST['no_hp'] ?? '');
$alasan   = trim($_POST['alasan'] ?? '');

if (!$username) {
    redirect('login.php');
}

try {
    // Cek user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
    $stmt->execute([$username]);
    // Tetap insert request meski user tidak ditemukan (biar admin bisa lihat attempt mencurigakan)
    $ins = $pdo->prepare("INSERT INTO password_reset_requests (username, email, no_hp, alasan, ip_address) VALUES (?,?,?,?,?)");
    $ins->execute([$username, $email ?: null, $no_hp ?: null, $alasan ?: null, $_SERVER['REMOTE_ADDR'] ?? '']);
    audit_log($pdo, null, 'reset_request', 'auth', 'username='.$username);
} catch (Exception $e) {
    // Silent fail agar tidak leak info
}

header('Location: ' . url('login.php?reset=sent'));
exit;
