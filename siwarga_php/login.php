<?php
require_once __DIR__ . '/includes/auth.php';
if (is_login()) redirect('dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND aktif = 1 LIMIT 1");
        $stmt->execute([$username]);
        $u = $stmt->fetch();
        if ($u && password_verify($password, $u['password'])) {
            $_SESSION['user_id'] = $u['id'];
            unset($u['password']);
            $_SESSION['user'] = $u;
            audit_log($pdo, $u['id'], 'login', 'auth', 'User login');
            redirect('dashboard.php');
        } else {
            $error = 'Username atau password salah.';
        }
    } else {
        $error = 'Mohon isi username dan password.';
    }
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Login - <?= APP_NAME ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="<?= url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="login-bg">
<div class="login-wrapper">
  <div class="login-card shadow-lg">
    <div class="login-left">
      <div class="brand-large">
        <i class="fa-solid fa-house-user"></i>
        <h1><?= APP_NAME ?></h1>
        <p><?= APP_FULL ?></p>
      </div>
      <ul class="feature-list">
        <li><i class="fa-solid fa-circle-check"></i> Pendataan KK & Warga digital</li>
        <li><i class="fa-solid fa-circle-check"></i> Pengajuan surat online</li>
        <li><i class="fa-solid fa-circle-check"></i> Iuran & laporan keuangan</li>
        <li><i class="fa-solid fa-circle-check"></i> Buku tamu digital</li>
        <li><i class="fa-solid fa-circle-check"></i> Dashboard statistik real-time</li>
      </ul>
    </div>
    <div class="login-right">
      <h3 class="mb-1">Selamat Datang</h3>
      <p class="text-muted mb-4">Silakan masuk untuk melanjutkan.</p>
      <?php if ($error): ?>
        <div class="alert alert-danger py-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> <?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" autocomplete="off">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" name="username" class="form-control" required autofocus>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" name="password" class="form-control" required>
          </div>
        </div>
        <button class="btn btn-primary w-100 py-2"><i class="fa-solid fa-right-to-bracket me-1"></i> Masuk</button>
      </form>
      <hr class="my-4">
      <details>
        <summary class="text-muted small">Akun demo (klik untuk lihat)</summary>
        <ul class="small text-muted mt-2 mb-0">
          <li><b>superadmin</b> / admin123 (Super Admin)</li>
          <li><b>adminrtrw</b> / admin123 (Admin RT/RW)</li>
          <li><b>ketua</b> / ketua123 (Ketua RT/RW)</li>
          <li><b>warga01</b> / warga123 (Warga)</li>
        </ul>
      </details>
    </div>
  </div>
  <p class="text-center text-white-50 mt-3 small">© <?= date('Y') ?> <?= APP_NAME ?> v<?= APP_VERSION ?></p>
</div>
</body>
</html>
