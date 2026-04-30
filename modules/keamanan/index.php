<?php
$pageTitle='Laporan Keamanan';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$rows=$pdo->query("SELECT * FROM laporan_keamanan ORDER BY id DESC")->fetchAll();
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-shield-halved me-1"></i> Laporan Keamanan</span>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Buat Laporan</button>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>Tgl</th><th>Pelapor</th><th>Jenis</th><th>Judul</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody><?php foreach($rows as $i=>$r): ?>
      <tr>
        <td><?= $i+1 ?></td><td><?= tanggal_id($r['tanggal_kejadian']) ?></td>
        <td><?= e($r['pelapor']) ?></td><td><span class="badge bg-info"><?= ucfirst($r['jenis_laporan']) ?></span></td>
        <td><?= e($r['judul']) ?></td><td><?= badge_status($r['status']) ?></td>
        <td>
          <button class="btn btn-sm btn-info" onclick='viewRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-eye"></i></button>
          <button class="btn btn-sm btn-warning" onclick='editRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-edit"></i></button>
          <a class="btn btn-sm btn-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?></tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="mView" tabindex="-1"><div class="modal-dialog"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Detail Laporan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body" id="vBody"></div></div></div></div>

<div class="modal fade" id="mForm" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="post" action="action.php"><?= csrf_field() ?>
<input type="hidden" name="id" id="f_id"><input type="hidden" name="act" id="f_act" value="add">
<div class="modal-header"><h5 class="modal-title" id="mTitle">Buat Laporan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label required">Pelapor</label><input name="pelapor" id="f_pelapor" class="form-control" required value="<?= e(user()['nama']) ?>"></div>
  <div class="col-md-6"><label class="form-label">Jenis</label>
    <select name="jenis_laporan" id="f_jenis" class="form-select"><option value="kehilangan">Kehilangan</option><option value="gangguan">Gangguan Lingkungan</option><option value="keluhan">Keluhan</option></select>
  </div>
  <div class="col-md-6"><label class="form-label required">Judul</label><input name="judul" id="f_judul" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">Tanggal Kejadian</label><input type="date" name="tanggal_kejadian" id="f_tgl" class="form-control" value="<?= date('Y-m-d') ?>"></div>
  <div class="col-12"><label class="form-label">Isi Laporan</label><textarea name="isi" id="f_isi" class="form-control" rows="3"></textarea></div>
  <div class="col-md-6"><label class="form-label">Status</label>
    <select name="status" id="f_status" class="form-select"><option value="baru">Baru</option><option value="proses">Proses</option><option value="selesai">Selesai</option></select>
  </div>
  <div class="col-12"><label class="form-label">Tindak Lanjut</label><textarea name="tindak_lanjut" id="f_tl" class="form-control" rows="2"></textarea></div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form></div></div></div>
<script>
function resetForm(){['f_id','f_judul','f_isi','f_tl'].forEach(id=>document.getElementById(id).value='');document.getElementById('f_act').value='add';document.getElementById('f_jenis').value='keluhan';document.getElementById('f_status').value='baru';document.getElementById('f_tgl').value='<?= date('Y-m-d') ?>';document.getElementById('mTitle').innerText='Buat Laporan';}
function setV(id,v){document.getElementById(id).value=v??'';}
function editRow(r){document.getElementById('f_act').value='edit';document.getElementById('mTitle').innerText='Edit Laporan';
  setV('f_id',r.id);setV('f_pelapor',r.pelapor);setV('f_jenis',r.jenis_laporan);setV('f_judul',r.judul);setV('f_isi',r.isi);setV('f_tgl',r.tanggal_kejadian);setV('f_status',r.status);setV('f_tl',r.tindak_lanjut);
  new bootstrap.Modal(document.getElementById('mForm')).show();}
function viewRow(r){
  document.getElementById('vBody').innerHTML=`<table class="table table-sm"><tr><th>Pelapor</th><td>${r.pelapor}</td></tr><tr><th>Jenis</th><td>${r.jenis_laporan}</td></tr><tr><th>Judul</th><td>${r.judul}</td></tr><tr><th>Tanggal</th><td>${r.tanggal_kejadian}</td></tr><tr><th>Isi</th><td>${(r.isi||'-').replace(/\n/g,'<br>')}</td></tr><tr><th>Status</th><td>${r.status}</td></tr><tr><th>Tindak Lanjut</th><td>${(r.tindak_lanjut||'-').replace(/\n/g,'<br>')}</td></tr></table>`;
  new bootstrap.Modal(document.getElementById('mView')).show();}
</script>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
