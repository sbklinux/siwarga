<?php
$pageTitle='Data Pindah / Keluar';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$rows=$pdo->query("SELECT * FROM pindah ORDER BY tanggal_pindah DESC")->fetchAll();
$canEdit=can('manage_master');
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-truck-moving me-1"></i> Daftar Pindah / Keluar</span>
    <?php if($canEdit): ?><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Tambah</button><?php endif; ?>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>NIK</th><th>Nama</th><th>Tujuan</th><th>Tgl Pindah</th><th>Alasan</th><th>Aksi</th></tr></thead>
      <tbody><?php foreach($rows as $i=>$r): ?>
      <tr>
        <td><?= $i+1 ?></td><td><?= e($r['nik']) ?></td><td><?= e($r['nama']) ?></td>
        <td><?= e($r['tujuan_pindah']) ?></td><td><?= tanggal_id($r['tanggal_pindah']) ?></td>
        <td><?= e($r['alasan']) ?></td>
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
<div class="modal-header"><h5 class="modal-title" id="mTitle">Tambah Pindah</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label">NIK</label><input name="nik" id="f_nik" class="form-control"></div>
  <div class="col-md-6"><label class="form-label required">Nama</label><input name="nama" id="f_nama" class="form-control" required></div>
  <div class="col-md-8"><label class="form-label">Tujuan Pindah</label><input name="tujuan_pindah" id="f_tuj" class="form-control"></div>
  <div class="col-md-4"><label class="form-label">Tanggal Pindah</label><input type="date" name="tanggal_pindah" id="f_tgl" class="form-control"></div>
  <div class="col-12"><label class="form-label">Alasan</label><textarea name="alasan" id="f_alasan" class="form-control" rows="2"></textarea></div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form></div></div></div>
<script>
function resetForm(){['f_id','f_nik','f_nama','f_tuj','f_tgl','f_alasan'].forEach(id=>document.getElementById(id).value='');document.getElementById('f_act').value='add';document.getElementById('mTitle').innerText='Tambah Pindah';}
function setV(id,v){document.getElementById(id).value=v??'';}
function editRow(r){document.getElementById('f_act').value='edit';document.getElementById('mTitle').innerText='Edit Pindah';
  setV('f_id',r.id);setV('f_nik',r.nik);setV('f_nama',r.nama);setV('f_tuj',r.tujuan_pindah);setV('f_tgl',r.tanggal_pindah);setV('f_alasan',r.alasan);
  new bootstrap.Modal(document.getElementById('mForm')).show();}
</script>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
