<?php
$pageTitle='Audit Log';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin']);
$rows=$pdo->query("SELECT a.*, u.username FROM audit_log a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.id DESC LIMIT 500")->fetchAll();
?>
<div class="card">
  <div class="card-header"><i class="fa-solid fa-clipboard-list me-1"></i> Audit Log (500 terbaru)</div>
  <div class="card-body">
    <table class="table table-sm dt">
      <thead><tr><th>Waktu</th><th>User</th><th>Aksi</th><th>Modul</th><th>Detail</th><th>IP</th></tr></thead>
      <tbody><?php foreach($rows as $r): ?>
      <tr>
        <td><?= e($r['created_at']) ?></td><td><?= e($r['username']) ?></td>
        <td><span class="badge bg-secondary"><?= e($r['aksi']) ?></span></td>
        <td><?= e($r['modul']) ?></td><td><?= e($r['detail']) ?></td><td><?= e($r['ip_address']) ?></td>
      </tr>
      <?php endforeach; ?></tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
