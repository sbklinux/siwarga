<?php
$pageTitle='Laporan Keuangan';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$rows=$pdo->query("SELECT * FROM keuangan ORDER BY tanggal DESC")->fetchAll();
$totalIn=(float)$pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM keuangan WHERE tipe='pemasukan'")->fetchColumn();
$totalOut=(float)$pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM keuangan WHERE tipe='pengeluaran'")->fetchColumn();
$saldo=$totalIn-$totalOut;
$canEdit=can('manage_keuangan');
?>
<div class="row g-3 mb-3">
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c2"><i class="fa-solid fa-arrow-down"></i></div><div><div class="label">Total Pemasukan</div><div class="value" style="font-size:20px"><?= rupiah($totalIn) ?></div></div></div></div>
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c4"><i class="fa-solid fa-arrow-up"></i></div><div><div class="label">Total Pengeluaran</div><div class="value" style="font-size:20px"><?= rupiah($totalOut) ?></div></div></div></div>
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c1"><i class="fa-solid fa-wallet"></i></div><div><div class="label">Saldo Kas</div><div class="value" style="font-size:20px"><?= rupiah($saldo) ?></div></div></div></div>
</div>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-chart-line me-1"></i> Buku Kas</span>
    <?php if($canEdit): ?><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Tambah Transaksi</button><?php endif; ?>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Jumlah</th><th>Keterangan</th><th>Aksi</th></tr></thead>
      <tbody><?php foreach($rows as $i=>$r): ?>
      <tr>
        <td><?= $i+1 ?></td><td><?= tanggal_id($r['tanggal']) ?></td>
        <td><span class="badge bg-<?= $r['tipe']==='pemasukan'?'success':'danger' ?>"><?= ucfirst($r['tipe']) ?></span></td>
        <td><?= e($r['kategori']) ?></td><td><?= rupiah($r['jumlah']) ?></td><td><?= e($r['keterangan']) ?></td>
        <td><?php if($canEdit): ?>
          <button class="btn btn-sm btn-warning" onclick='editRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-edit"></i></button>
          <a class="btn btn-sm btn-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="fa-solid fa-trash"></i></a>
        <?php endif; ?></td>
      </tr>
      <?php endforeach; ?></tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="mForm" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<form method="post" action="action.php"><?= csrf_field() ?>
<input type="hidden" name="id" id="f_id"><input type="hidden" name="act" id="f_act" value="add">
<div class="modal-header"><h5 class="modal-title" id="mTitle">Tambah Transaksi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label">Tipe</label>
    <select name="tipe" id="f_tipe" class="form-select"><option value="pemasukan">Pemasukan</option><option value="pengeluaran">Pengeluaran</option></select>
  </div>
  <div class="col-md-6"><label class="form-label">Tanggal</label><input type="date" name="tanggal" id="f_tgl" class="form-control" value="<?= date('Y-m-d') ?>"></div>
  <div class="col-md-6"><label class="form-label required">Kategori</label><input name="kategori" id="f_kat" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label required">Jumlah</label><input type="number" name="jumlah" id="f_jml" class="form-control" required></div>
  <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" id="f_ket" class="form-control" rows="2"></textarea></div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form></div></div></div>
<script>
function resetForm(){['f_id','f_kat','f_jml','f_ket'].forEach(id=>document.getElementById(id).value='');document.getElementById('f_act').value='add';document.getElementById('f_tipe').value='pemasukan';document.getElementById('f_tgl').value='<?= date('Y-m-d') ?>';document.getElementById('mTitle').innerText='Tambah Transaksi';}
function setV(id,v){document.getElementById(id).value=v??'';}
function editRow(r){document.getElementById('f_act').value='edit';document.getElementById('mTitle').innerText='Edit Transaksi';
  setV('f_id',r.id);setV('f_tipe',r.tipe);setV('f_tgl',r.tanggal);setV('f_kat',r.kategori);setV('f_jml',r.jumlah);setV('f_ket',r.keterangan);
  new bootstrap.Modal(document.getElementById('mForm')).show();}
</script>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
