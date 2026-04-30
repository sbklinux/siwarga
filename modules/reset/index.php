<?php
$pageTitle = 'Permintaan Reset Password';
require_once __DIR__ . '/../../includes/header.php';
require_role(['super_admin','ketua_rtrw']);
$rows = $pdo->query("SELECT r.*, u.nama AS processed_by_nama FROM password_reset_requests r LEFT JOIN users u ON u.id=r.processed_by ORDER BY r.id DESC")->fetchAll();
$pending = (int)$pdo->query("SELECT COUNT(*) FROM password_reset_requests WHERE status='pending'")->fetchColumn();
?>
<div class="row g-3 mb-3">
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c3"><i class="fa-solid fa-hourglass-half"></i></div><div><div class="label">Permintaan Pending</div><div class="value"><?= $pending ?></div></div></div></div>
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c2"><i class="fa-solid fa-check-double"></i></div><div><div class="label">Sudah Diproses</div><div class="value"><?= count($rows)-$pending ?></div></div></div></div>
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c1"><i class="fa-solid fa-inbox"></i></div><div><div class="label">Total Permintaan</div><div class="value"><?= count($rows) ?></div></div></div></div>
</div>
<div class="card">
  <div class="card-header"><i class="fa-solid fa-key me-1"></i> Daftar Permintaan Reset Password</div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>Tgl</th><th>Username</th><th>Email</th><th>No HP</th><th>Alasan</th><th>Status</th><th>Password Baru</th><th>Diproses oleh</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach($rows as $r): ?>
        <tr>
          <td><?= e($r['created_at']) ?></td>
          <td><b><?= e($r['username']) ?></b></td>
          <td><?= e($r['email']) ?></td>
          <td><?= e($r['no_hp']) ?></td>
          <td><?= e($r['alasan']) ?></td>
          <td><?= badge_status($r['status']) ?></td>
          <td>
            <?php if ($r['status']==='approved' && $r['new_password_plain']): ?>
              <code class="text-danger"><?= e($r['new_password_plain']) ?></code>
              <br><small class="text-muted">Sampaikan ke user ybs</small>
            <?php else: ?><span class="text-muted">-</span><?php endif; ?>
          </td>
          <td><?= e($r['processed_by_nama']) ?> <br><small class="text-muted"><?= e($r['processed_at']) ?></small></td>
          <td>
            <?php if ($r['status']==='pending'): ?>
              <a class="btn btn-sm btn-success" href="action.php?act=approve&id=<?= $r['id'] ?>" onclick="return confirm('Reset password user ini? Sistem akan generate password baru.')"><i class="fa-solid fa-check"></i> Approve</a>
              <a class="btn btn-sm btn-danger" href="action.php?act=tolak&id=<?= $r['id'] ?>" onclick="return confirm('Tolak permintaan ini?')"><i class="fa-solid fa-times"></i> Tolak</a>
            <?php endif; ?>
            <a class="btn btn-sm btn-outline-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus record?')"><i class="fa-solid fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
