<?php
$pageTitle='Iuran Warga';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$rows=$pdo->query("SELECT * FROM iuran ORDER BY tanggal_bayar DESC")->fetchAll();
$total=(float)$pdo->query("SELECT COALESCE(SUM(nominal),0) FROM iuran WHERE bulan='".date('Y-m')."'")->fetchColumn();
$kkList=$pdo->query("SELECT no_kk, kepala_keluarga FROM kartu_keluarga ORDER BY kepala_keluarga")->fetchAll();
$canEdit=can('manage_keuangan');
?>
<div class="row g-3 mb-3">
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c2"><i class="fa-solid fa-money-bill-wave"></i></div><div><div class="label">Total Iuran <?= date('M Y') ?></div><div class="value" style="font-size:20px"><?= rupiah($total) ?></div></div></div></div>
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c1"><i class="fa-solid fa-receipt"></i></div><div><div class="label">Jumlah Transaksi</div><div class="value"><?= count($rows) ?></div></div></div></div>
  <div class="col-md-4"><div class="stat-card"><div class="icon bg-c3"><i class="fa-solid fa-id-card"></i></div><div><div class="label">KK Membayar Bulan Ini</div><div class="value"><?= (int)$pdo->query("SELECT COUNT(DISTINCT no_kk) FROM iuran WHERE bulan='".date('Y-m')."'")->fetchColumn() ?></div></div></div></div>
</div>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-money-bill-wave me-1"></i> Riwayat Iuran</span>
    <?php if($canEdit): ?><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Catat Iuran</button><?php endif; ?>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>No KK</th><th>Nama KK</th><th>Jenis</th><th>Bulan</th><th>Nominal</th><th>Tgl Bayar</th><th>Aksi</th></tr></thead>
      <tbody><?php foreach($rows as $i=>$r): ?>
      <tr>
        <td><?= $i+1 ?></td><td><?= e($r['no_kk']) ?></td><td><?= e($r['nama_kk']) ?></td>
        <td><span class="badge bg-info"><?= e(ucfirst($r['jenis_iuran'])) ?></span></td>
        <td><?= e($r['bulan']) ?></td><td><?= rupiah($r['nominal']) ?></td><td><?= tanggal_id($r['tanggal_bayar']) ?></td>
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
<div class="modal-header"><h5 class="modal-title" id="mTitle">Catat Iuran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-12"><label class="form-label">KK</label>
    <select name="no_kk" id="f_kk" class="form-select" onchange="setNama(this)">
      <option value="">-- pilih --</option>
      <?php foreach($kkList as $k): ?><option value="<?= e($k['no_kk']) ?>" data-nama="<?= e($k['kepala_keluarga']) ?>"><?= e($k['no_kk']) ?> - <?= e($k['kepala_keluarga']) ?></option><?php endforeach; ?>
    </select>
  </div>
  <div class="col-12"><label class="form-label required">Nama KK</label><input name="nama_kk" id="f_nama" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">Jenis</label>
    <select name="jenis_iuran" id="f_jenis" class="form-select"><option value="bulanan">Bulanan</option><option value="keamanan">Keamanan</option><option value="kebersihan">Kebersihan</option><option value="sosial">Sosial</option></select>
  </div>
  <div class="col-md-6"><label class="form-label">Bulan</label><input type="month" name="bulan" id="f_bulan" class="form-control" value="<?= date('Y-m') ?>"></div>
  <div class="col-md-6"><label class="form-label required">Nominal</label><input type="number" name="nominal" id="f_nominal" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">Tanggal Bayar</label><input type="date" name="tanggal_bayar" id="f_tgl" class="form-control" value="<?= date('Y-m-d') ?>"></div>
  <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" id="f_ket" class="form-control" rows="2"></textarea></div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form></div></div></div>
<script>
function setNama(s){const o=s.options[s.selectedIndex];document.getElementById('f_nama').value=o.dataset.nama||'';}
function resetForm(){['f_id','f_kk','f_nama','f_nominal','f_ket'].forEach(id=>document.getElementById(id).value='');document.getElementById('f_act').value='add';document.getElementById('mTitle').innerText='Catat Iuran';document.getElementById('f_jenis').value='bulanan';document.getElementById('f_bulan').value='<?= date('Y-m') ?>';document.getElementById('f_tgl').value='<?= date('Y-m-d') ?>';}
function setV(id,v){document.getElementById(id).value=v??'';}
function editRow(r){document.getElementById('f_act').value='edit';document.getElementById('mTitle').innerText='Edit Iuran';
  setV('f_id',r.id);setV('f_kk',r.no_kk);setV('f_nama',r.nama_kk);setV('f_jenis',r.jenis_iuran);setV('f_bulan',r.bulan);setV('f_nominal',r.nominal);setV('f_tgl',r.tanggal_bayar);setV('f_ket',r.keterangan);
  new bootstrap.Modal(document.getElementById('mForm')).show();}
</script>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
