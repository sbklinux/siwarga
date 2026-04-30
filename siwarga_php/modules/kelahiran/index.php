<?php
$pageTitle='Data Kelahiran';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$rows=$pdo->query("SELECT * FROM kelahiran ORDER BY tanggal_lahir DESC")->fetchAll();
$canEdit=can('manage_master');
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-baby me-1"></i> Daftar Kelahiran</span>
    <?php if($canEdit): ?><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Tambah</button><?php endif; ?>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>Nama Bayi</th><th>JK</th><th>Tempat/Tgl Lahir</th><th>Ayah</th><th>Ibu</th><th>No KK</th><th>RT/RW</th><th>Aksi</th></tr></thead>
      <tbody><?php foreach($rows as $i=>$r): ?>
      <tr>
        <td><?= $i+1 ?></td><td><?= e($r['nama_bayi']) ?></td>
        <td><?= $r['jenis_kelamin']=='L'?'Laki-laki':'Perempuan' ?></td>
        <td><?= e($r['tempat_lahir']).', '.tanggal_id($r['tanggal_lahir']) ?></td>
        <td><?= e($r['nama_ayah']) ?></td><td><?= e($r['nama_ibu']) ?></td>
        <td><?= e($r['no_kk']) ?></td><td><?= e($r['rt']).'/'.e($r['rw']) ?></td>
        <td><?php if($canEdit): ?>
          <button class="btn btn-sm btn-warning" onclick='editRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-edit"></i></button>
          <a class="btn btn-sm btn-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="fa-solid fa-trash"></i></a>
        <?php endif; ?></td>
      </tr>
      <?php endforeach; ?></tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="mForm" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="post" action="action.php"><?= csrf_field() ?>
<input type="hidden" name="id" id="f_id"><input type="hidden" name="act" id="f_act" value="add">
<div class="modal-header"><h5 class="modal-title" id="mTitle">Tambah Kelahiran</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label required">Nama Bayi</label><input name="nama_bayi" id="f_nama" class="form-control" required></div>
  <div class="col-md-3"><label class="form-label">Jenis Kelamin</label><select name="jenis_kelamin" id="f_jk" class="form-select"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
  <div class="col-md-3"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" id="f_tgl" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Tempat Lahir</label><input name="tempat_lahir" id="f_tl" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">No KK</label><input name="no_kk" id="f_kk" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Nama Ayah</label><input name="nama_ayah" id="f_ayah" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Nama Ibu</label><input name="nama_ibu" id="f_ibu" class="form-control"></div>
  <div class="col-md-3"><label class="form-label">RT</label><input name="rt" id="f_rt" class="form-control"></div>
  <div class="col-md-3"><label class="form-label">RW</label><input name="rw" id="f_rw" class="form-control"></div>
  <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" id="f_ket" class="form-control" rows="2"></textarea></div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form></div></div></div>
<script>
function resetForm(){['f_id','f_nama','f_tgl','f_tl','f_kk','f_ayah','f_ibu','f_rt','f_rw','f_ket'].forEach(id=>document.getElementById(id).value='');document.getElementById('f_act').value='add';document.getElementById('f_jk').value='L';document.getElementById('mTitle').innerText='Tambah Kelahiran';}
function setV(id,v){document.getElementById(id).value=v??'';}
function editRow(r){document.getElementById('f_act').value='edit';document.getElementById('mTitle').innerText='Edit Kelahiran';
  setV('f_id',r.id);setV('f_nama',r.nama_bayi);setV('f_jk',r.jenis_kelamin);setV('f_tgl',r.tanggal_lahir);setV('f_tl',r.tempat_lahir);setV('f_kk',r.no_kk);setV('f_ayah',r.nama_ayah);setV('f_ibu',r.nama_ibu);setV('f_rt',r.rt);setV('f_rw',r.rw);setV('f_ket',r.keterangan);
  new bootstrap.Modal(document.getElementById('mForm')).show();}
</script>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
