<?php
$pageTitle = 'Data Pendatang';
require_once __DIR__ . '/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$rows = $pdo->query("SELECT * FROM pendatang ORDER BY id DESC")->fetchAll();
$canEdit = can('manage_master');
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-person-walking-arrow-right me-1"></i> Daftar Pendatang</span>
    <?php if($canEdit): ?><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Tambah</button><?php endif; ?>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>Nama</th><th>NIK</th><th>Asal</th><th>Tujuan</th><th>Lama</th><th>Penjamin</th><th>Tgl Datang</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach($rows as $i=>$r): ?>
      <tr>
        <td><?= $i+1 ?></td><td><?= e($r['nama']) ?></td><td><?= e($r['nik']) ?></td>
        <td><?= e($r['asal_daerah']) ?></td><td><?= e($r['tujuan_tinggal']) ?></td>
        <td><?= e($r['lama_tinggal']) ?></td><td><?= e($r['penjamin']) ?></td>
        <td><?= tanggal_id($r['tanggal_datang']) ?></td><td><?= badge_status($r['status']) ?></td>
        <td>
          <?php if($canEdit): ?>
          <button class="btn btn-sm btn-warning" onclick='editRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-edit"></i></button>
          <a class="btn btn-sm btn-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="fa-solid fa-trash"></i></a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="mForm" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="post" action="action.php"><?= csrf_field() ?>
<input type="hidden" name="id" id="f_id"><input type="hidden" name="act" id="f_act" value="add">
<div class="modal-header"><h5 class="modal-title" id="mTitle">Tambah Pendatang</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label required">Nama</label><input name="nama" id="f_nama" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">NIK</label><input name="nik" id="f_nik" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Asal Daerah</label><input name="asal_daerah" id="f_asal" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Tujuan Tinggal</label><input name="tujuan_tinggal" id="f_tuj" class="form-control" placeholder="Bekerja, Kuliah, dll"></div>
  <div class="col-md-4"><label class="form-label">Lama Tinggal</label><input name="lama_tinggal" id="f_lama" class="form-control"></div>
  <div class="col-md-4"><label class="form-label">Penjamin</label><input name="penjamin" id="f_jamin" class="form-control"></div>
  <div class="col-md-4"><label class="form-label">Tanggal Datang</label><input type="date" name="tanggal_datang" id="f_tgl" class="form-control"></div>
  <div class="col-12"><label class="form-label">Alamat Tinggal</label><textarea name="alamat_tinggal" id="f_alm" class="form-control" rows="2"></textarea></div>
  <div class="col-md-3"><label class="form-label">RT</label><input name="rt" id="f_rt" class="form-control"></div>
  <div class="col-md-3"><label class="form-label">RW</label><input name="rw" id="f_rw" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Status</label>
    <select name="status" id="f_st" class="form-select"><option value="aktif">Aktif</option><option value="selesai">Selesai</option></select>
  </div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form></div></div></div>
<script>
function resetForm(){document.getElementById('f_act').value='add';document.getElementById('mTitle').innerText='Tambah Pendatang';['f_id','f_nama','f_nik','f_asal','f_tuj','f_lama','f_jamin','f_tgl','f_alm','f_rt','f_rw'].forEach(id=>document.getElementById(id).value='');document.getElementById('f_st').value='aktif';}
function setVal(id,v){document.getElementById(id).value=v??'';}
function editRow(r){document.getElementById('f_act').value='edit';document.getElementById('mTitle').innerText='Edit Pendatang';
  setVal('f_id',r.id);setVal('f_nama',r.nama);setVal('f_nik',r.nik);setVal('f_asal',r.asal_daerah);setVal('f_tuj',r.tujuan_tinggal);setVal('f_lama',r.lama_tinggal);setVal('f_jamin',r.penjamin);setVal('f_tgl',r.tanggal_datang);setVal('f_alm',r.alamat_tinggal);setVal('f_rt',r.rt);setVal('f_rw',r.rw);setVal('f_st',r.status);
  new bootstrap.Modal(document.getElementById('mForm')).show();}
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
