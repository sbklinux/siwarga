<?php
$current = basename($_SERVER['PHP_SELF']);
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
function nav_active($files, $cur) { return in_array($cur, (array)$files, true) ? ' active' : ''; }
function dir_active($d, $curDir) { return $d === $curDir ? ' active' : ''; }
$r = role();
?>
<aside class="app-sidebar">
  <div class="brand">
    <i class="fa-solid fa-house-user"></i>
    <div>
      <div class="brand-name"><?= APP_NAME ?></div>
      <small>Sistem Warga Terpadu</small>
    </div>
  </div>
  <nav class="nav flex-column px-2 pb-4">
    <a class="nav-link<?= nav_active(['dashboard.php'], $current) ?>" href="<?= url('dashboard.php') ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>

    <?php if (in_array($r, ['super_admin','admin_rtrw'])): ?>
      <div class="nav-section">Master Data</div>
      <a class="nav-link<?= dir_active('kk',$currentDir) ?>" href="<?= url('modules/kk/index.php') ?>"><i class="fa-solid fa-id-card"></i> Kartu Keluarga</a>
      <a class="nav-link<?= dir_active('warga',$currentDir) ?>" href="<?= url('modules/warga/index.php') ?>"><i class="fa-solid fa-users"></i> Warga</a>
      <a class="nav-link<?= dir_active('pendatang',$currentDir) ?>" href="<?= url('modules/pendatang/index.php') ?>"><i class="fa-solid fa-person-walking-arrow-right"></i> Pendatang</a>
      <a class="nav-link<?= dir_active('kelahiran',$currentDir) ?>" href="<?= url('modules/kelahiran/index.php') ?>"><i class="fa-solid fa-baby"></i> Kelahiran</a>
      <a class="nav-link<?= dir_active('kematian',$currentDir) ?>" href="<?= url('modules/kematian/index.php') ?>"><i class="fa-solid fa-cross"></i> Kematian</a>
      <a class="nav-link<?= dir_active('pindah',$currentDir) ?>" href="<?= url('modules/pindah/index.php') ?>"><i class="fa-solid fa-truck-moving"></i> Pindah / Keluar</a>
    <?php endif; ?>

    <div class="nav-section">Administrasi</div>
    <a class="nav-link<?= dir_active('surat',$currentDir) ?>" href="<?= url('modules/surat/index.php') ?>"><i class="fa-solid fa-file-lines"></i> Pengajuan Surat</a>

    <?php if (in_array($r, ['super_admin','admin_rtrw','ketua_rtrw'])): ?>
      <div class="nav-section">Keuangan</div>
      <a class="nav-link<?= dir_active('iuran',$currentDir) ?>" href="<?= url('modules/iuran/index.php') ?>"><i class="fa-solid fa-money-bill-wave"></i> Iuran Warga</a>
      <a class="nav-link<?= dir_active('keuangan',$currentDir) ?>" href="<?= url('modules/keuangan/index.php') ?>"><i class="fa-solid fa-chart-line"></i> Laporan Keuangan</a>

      <div class="nav-section">Keamanan</div>
      <a class="nav-link<?= dir_active('tamu',$currentDir) ?>" href="<?= url('modules/tamu/index.php') ?>"><i class="fa-solid fa-user-clock"></i> Buku Tamu</a>
      <a class="nav-link<?= dir_active('keamanan',$currentDir) ?>" href="<?= url('modules/keamanan/index.php') ?>"><i class="fa-solid fa-shield-halved"></i> Laporan Keamanan</a>
    <?php endif; ?>

    <?php if (in_array($r, ['super_admin','admin_rtrw','ketua_rtrw'])): ?>
      <div class="nav-section">Laporan</div>
      <a class="nav-link<?= dir_active('laporan',$currentDir) ?>" href="<?= url('modules/laporan/index.php') ?>"><i class="fa-solid fa-file-export"></i> Export Data</a>
    <?php endif; ?>

    <?php if ($r === 'super_admin'): ?>
      <div class="nav-section">Sistem</div>
      <a class="nav-link<?= dir_active('users',$currentDir) ?>" href="<?= url('modules/users/index.php') ?>"><i class="fa-solid fa-user-shield"></i> Manajemen User</a>
      <a class="nav-link<?= dir_active('audit',$currentDir) ?>" href="<?= url('modules/audit/index.php') ?>"><i class="fa-solid fa-clipboard-list"></i> Audit Log</a>
    <?php endif; ?>

    <div class="nav-section">Akun</div>
    <a class="nav-link<?= dir_active('profil',$currentDir) ?>" href="<?= url('modules/profil/index.php') ?>"><i class="fa-solid fa-user"></i> Profil Saya</a>
    <a class="nav-link" href="<?= url('logout.php') ?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </nav>
</aside>
