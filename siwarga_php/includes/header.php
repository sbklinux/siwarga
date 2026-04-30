<?php
require_once __DIR__ . '/auth.php';
require_login();
$pageTitle = $pageTitle ?? 'Dashboard';
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title><?= e($pageTitle) ?> - <?= APP_NAME ?></title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="<?= url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body>
<div class="app-shell">
<?php include __DIR__ . '/sidebar.php'; ?>
<main class="app-main">
  <header class="topbar">
    <button class="btn btn-light btn-sm d-md-none" id="btnSidebar"><i class="fa-solid fa-bars"></i></button>
    <h5 class="mb-0"><?= e($pageTitle) ?></h5>
    <div class="ms-auto d-flex align-items-center gap-3">
      <span class="d-none d-md-inline small text-muted"><i class="fa-regular fa-clock me-1"></i><?= date('d M Y, H:i') ?></span>
      <div class="dropdown">
        <a class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
          <i class="fa-solid fa-circle-user me-1"></i><?= e(user()['nama']) ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li class="dropdown-header"><?= e(role_label(role())) ?></li>
          <li><a class="dropdown-item" href="<?= url('modules/profil/index.php') ?>"><i class="fa-solid fa-user me-1"></i> Profil Saya</a></li>
          <li><a class="dropdown-item" href="<?= url('modules/profil/index.php#tab2') ?>"><i class="fa-solid fa-key me-1"></i> Ganti Password</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="<?= url('logout.php') ?>"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </header>
  <div class="content">
    <?php show_flash(); ?>
