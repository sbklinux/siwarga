<?php
$pageTitle='Buku Tamu Digital';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$rows=$pdo->query("SELECT * FROM buku_tamu ORDER BY jam_masuk DESC")->fetchAll();
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-user-clock me-1"></i> Buku Tamu</span>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Catat Tamu</button>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>Nama Tamu</th><th>No Identitas</th><th>Asal</th><th>Tujuan</th><th>Dikunjungi</th><th>Masuk</th><th>Keluar</th><th>Aksi</th></tr></thead>
      <tbody><?php foreach($rows as $i=>$r): ?>
      <tr>
        <td><?= $i+1 ?></td><td><?= e($r['nama_tamu']) ?></td><td><?= e($r['no_identitas']) ?></td>
        <td><?= e($r['asal']) ?></td><td><?= e($r['tujuan']) ?></td><td><?= e($r['nama_dikunjungi']) ?></td>
        <td><?= e($r['jam_masuk']) ?></td>
        <td><?= $r['jam_keluar'] ? e($r['jam_keluar']) : '<span class="badge bg-warning">Di dalam</span>' ?></td>
        <td>
          <?php if(!$r['jam_keluar']): ?>
            <a class="btn btn-sm btn-success" href="action.php?act=keluar&id=<?= $r['id'] ?>"><i class="fa-solid fa-sign-out-alt"></i> Out</a>
          <?php endif; ?>
          <button class="btn btn-sm btn-warning" onclick='editRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-edit"></i></button>
          <a class="btn btn-sm btn-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus?')"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?></tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="mForm" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="post" action="action.php"><?= csrf_field() ?>
<input type="hidden" name="id" id="f_id"><input type="hidden" name="act" id="f_act" value="add">
<div class="modal-header"><h5 class="modal-title" id="mTitle">Catat Tamu</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label required">Nama Tamu</label><input name="nama_tamu" id="f_nama" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">No Identitas</label><input name="no_identitas" id="f_id2" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Asal</label><input name="asal" id="f_asal" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Tujuan</label><input name="tujuan" id="f_tuj" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Nama yang Dikunjungi</label><input name="nama_dikunjungi" id="f_dik" class="form-control"></div>
  <div class="col-md-6"><label class="form-label">Jam Masuk</label><input type="datetime-local" name="jam_masuk" id="f_in" class="form-control" value="<?= date('Y-m-d\TH:i') ?>"></div>
  <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" id="f_ket" class="form-control" rows="2"></textarea></div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form></div></div></div>
<script>
function resetForm(){['f_id','f_nama','f_id2','f_asal','f_tuj','f_dik','f_ket'].forEach(id=>document.getElementById(id).value='');document.getElementById('f_act').value='add';document.getElementById('mTitle').innerText='Catat Tamu';document.getElementById('f_in').value='<?= date('Y-m-d\TH:i') ?>';}
function setV(id,v){document.getElementById(id).value=v??'';}
function editRow(r){document.getElementById('f_act').value='edit';document.getElementById('mTitle').innerText='Edit Tamu';
  setV('f_id',r.id);setV('f_nama',r.nama_tamu);setV('f_id2',r.no_identitas);setV('f_asal',r.asal);setV('f_tuj',r.tujuan);setV('f_dik',r.nama_dikunjungi);setV('f_in',(r.jam_masuk||'').replace(' ','T').slice(0,16));setV('f_ket',r.keterangan);
  new bootstrap.Modal(document.getElementById('mForm')).show();}
</script>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
