<?php
$pageTitle='Pengajuan Surat';
require_once __DIR__.'/../../includes/header.php';
require_login();
$r=role(); $uid=$_SESSION['user_id'];
if($r==='warga'){
  $stmt=$pdo->prepare("SELECT * FROM pengajuan_surat WHERE user_id=? ORDER BY id DESC"); $stmt->execute([$uid]); $rows=$stmt->fetchAll();
}else{
  $rows=$pdo->query("SELECT * FROM pengajuan_surat ORDER BY id DESC")->fetchAll();
}
$jenis=['Surat Domisili','Surat Pengantar SKCK','Surat Usaha','Surat Tidak Mampu','Surat Pindah','Surat Keterangan Lainnya'];
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-file-lines me-1"></i> Daftar Pengajuan Surat</span>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mAjukan"><i class="fa-solid fa-plus"></i> Ajukan Surat</button>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>Nomor Surat</th><th>NIK</th><th>Nama</th><th>Jenis</th><th>Keperluan</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody><?php foreach($rows as $i=>$x): ?>
      <tr>
        <td><?= $i+1 ?></td><td><?= e($x['nomor_surat']) ?></td><td><?= e($x['nik']) ?></td><td><?= e($x['nama']) ?></td>
        <td><?= e($x['jenis_surat']) ?></td><td><?= e($x['keperluan']) ?></td>
        <td><?= tanggal_id($x['tanggal_pengajuan']) ?></td><td><?= badge_status($x['status']) ?></td>
        <td>
          <?php if ($r !== 'warga' || $x['status'] === 'approved'): ?>
            <a class="btn btn-sm btn-info" href="cetak.php?id=<?= $x['id'] ?>" target="_blank" title="Preview (HTML)"><i class="fa-solid fa-eye"></i></a>
            <a class="btn btn-sm btn-success" href="pdf.php?id=<?= $x['id'] ?>&mode=download" title="Download PDF"><i class="fa-solid fa-download"></i></a>
          <?php else: ?>
          <span class="badge bg-warning text-red"><i class="fa-solid fa-hourglass-half me-1"></i>Waiting Approval</span>
          <?php endif; ?>
          <?php if(can('verifikasi_surat') && $x['status']==='pending'): ?>
            <a class="btn btn-sm btn-primary" href="action.php?act=verif&id=<?= $x['id'] ?>" onclick="return confirm('Verifikasi surat?')"><i class="fa-solid fa-check"></i> Verif</a>
          <?php endif; ?>
          <?php if(can('approve_surat') && $x['status']==='verifikasi'): ?>
            <a class="btn btn-sm btn-success" href="action.php?act=approve&id=<?= $x['id'] ?>" onclick="return confirm('Setujui surat?')"><i class="fa-solid fa-check-double"></i> Setujui</a>
            <a class="btn btn-sm btn-danger" href="action.php?act=tolak&id=<?= $x['id'] ?>" onclick="return confirm('Tolak surat?')"><i class="fa-solid fa-times"></i></a>
          <?php endif; ?>
          <?php if(can('manage_master')): ?>
            <a class="btn btn-sm btn-outline-danger" href="action.php?act=delete&id=<?= $x['id'] ?>" onclick="return confirm('Hapus?')"><i class="fa-solid fa-trash"></i></a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?></tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="mAjukan" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="post" action="action.php"><?= csrf_field() ?>
<input type="hidden" name="act" value="ajukan">
<div class="modal-header"><h5 class="modal-title">Ajukan Surat Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label required">NIK</label><input name="nik" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label required">Nama</label><input name="nama" class="form-control" required value="<?= e(user()['nama']) ?>"></div>
  <div class="col-md-6"><label class="form-label required">Jenis Surat</label>
    <select name="jenis_surat" class="form-select" required>
      <?php foreach($jenis as $j): ?><option><?= e($j) ?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-6"><label class="form-label">Keperluan</label><input name="keperluan" class="form-control"></div>
  <div class="col-12"><label class="form-label">Keterangan Tambahan</label><textarea name="keterangan" class="form-control" rows="3"></textarea></div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Ajukan</button></div>
</form></div></div></div>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>