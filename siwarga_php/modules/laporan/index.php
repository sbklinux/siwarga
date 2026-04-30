<?php
$pageTitle='Export Laporan';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$tables=[
  'warga'=>['Data Warga','users'],
  'kartu_keluarga'=>['Kartu Keluarga','id-card'],
  'pendatang'=>['Pendatang','person-walking-arrow-right'],
  'kelahiran'=>['Kelahiran','baby'],
  'kematian'=>['Kematian','cross'],
  'pindah'=>['Pindah','truck-moving'],
  'pengajuan_surat'=>['Pengajuan Surat','file-lines'],
  'iuran'=>['Iuran Warga','money-bill-wave'],
  'keuangan'=>['Keuangan','chart-line'],
  'buku_tamu'=>['Buku Tamu','user-clock'],
  'laporan_keamanan'=>['Laporan Keamanan','shield-halved'],
];
?>
<div class="card mb-3"><div class="card-body">
  <h5 class="mb-3"><i class="fa-solid fa-file-export me-1"></i> Export Laporan</h5>
  <p class="text-muted">Pilih jenis data dan format export. CSV bisa dibuka langsung di Excel. PDF dicetak via browser.</p>
</div></div>
<div class="row g-3">
<?php foreach($tables as $t=>$info): ?>
  <div class="col-md-6 col-lg-4">
    <div class="card h-100"><div class="card-body">
      <h6><i class="fa-solid fa-<?= e($info[1]) ?> me-1 text-primary"></i> <?= e($info[0]) ?></h6>
      <p class="small text-muted mb-3">Jumlah data: <b><?= (int)$pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn() ?></b></p>
      <div class="d-flex gap-2">
        <a class="btn btn-success btn-sm" href="export.php?type=csv&table=<?= e($t) ?>"><i class="fa-solid fa-file-csv me-1"></i> Excel/CSV</a>
        <a class="btn btn-danger btn-sm" href="export.php?type=pdf&table=<?= e($t) ?>" target="_blank"><i class="fa-solid fa-file-pdf me-1"></i> PDF</a>
      </div>
    </div></div>
  </div>
<?php endforeach; ?>
</div>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
