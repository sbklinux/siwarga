<?php
$pageTitle = 'Kartu Keluarga';
require_once __DIR__ . '/../../includes/header.php';
require_role(['super_admin','admin_rtrw']);
$rows = $pdo->query("SELECT k.*, (SELECT COUNT(*) FROM warga w WHERE w.no_kk=k.no_kk) AS jml FROM kartu_keluarga k ORDER BY k.id DESC")->fetchAll();
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-id-card me-1"></i> Daftar Kartu Keluarga</span>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Tambah KK</button>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>No KK</th><th>Kepala Keluarga</th><th>Alamat</th><th>RT/RW</th><th>Status Rumah</th><th>Anggota</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $i => $r): ?>
        <tr>
          <td><?= $i+1 ?></td>
          <td><?= e($r['no_kk']) ?></td>
          <td><?= e($r['kepala_keluarga']) ?></td>
          <td><?= e($r['alamat']) ?></td>
          <td><?= e($r['rt']) ?>/<?= e($r['rw']) ?></td>
          <td><span class="badge bg-secondary"><?= e(ucfirst($r['status_rumah'])) ?></span></td>
          <td><?= (int)$r['jml'] ?></td>
          <td>
            <button class="btn btn-sm btn-warning" onclick='editRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-edit"></i></button>
            <a class="btn btn-sm btn-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus KK ini?')"><i class="fa-solid fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="mForm" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="post" action="action.php">
  <?= csrf_field() ?>
  <input type="hidden" name="id" id="f_id">
  <input type="hidden" name="act" id="f_act" value="add">
  <div class="modal-header"><h5 class="modal-title" id="mTitle">Tambah KK</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body">
    <div class="row g-3">
      <div class="col-md-6"><label class="form-label required">No KK</label><input name="no_kk" id="f_no_kk" class="form-control" required></div>
      <div class="col-md-6"><label class="form-label required">Kepala Keluarga</label><input name="kepala_keluarga" id="f_kepala" class="form-control" required></div>
      <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" id="f_alamat" class="form-control" rows="2"></textarea></div>
      <div class="col-md-3"><label class="form-label">RT</label><input name="rt" id="f_rt" class="form-control"></div>
      <div class="col-md-3"><label class="form-label">RW</label><input name="rw" id="f_rw" class="form-control"></div>
      <div class="col-md-3"><label class="form-label">Kelurahan</label><input name="kelurahan" id="f_kel" class="form-control"></div>
      <div class="col-md-3"><label class="form-label">Kecamatan</label><input name="kecamatan" id="f_kec" class="form-control"></div>
      <div class="col-md-4"><label class="form-label">Kota</label><input name="kota" id="f_kota" class="form-control"></div>
      <div class="col-md-4"><label class="form-label">Provinsi</label><input name="provinsi" id="f_prov" class="form-control"></div>
      <div class="col-md-4"><label class="form-label">Status Rumah</label>
        <select name="status_rumah" id="f_status" class="form-select">
          <option value="milik">Milik Sendiri</option><option value="sewa">Sewa</option><option value="kontrak">Kontrak</option><option value="menumpang">Menumpang</option>
        </select>
      </div>
    </div>
  </div>
  <div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form>
</div></div></div>

<script>
function resetForm(){
  document.getElementById('f_act').value='add';
  document.getElementById('mTitle').innerText='Tambah KK';
  ['f_id','f_no_kk','f_kepala','f_alamat','f_rt','f_rw','f_kel','f_kec','f_kota','f_prov'].forEach(id=>document.getElementById(id).value='');
  document.getElementById('f_status').value='milik';
}
function editRow(r){
  document.getElementById('f_act').value='edit';
  document.getElementById('mTitle').innerText='Edit KK';
  document.getElementById('f_id').value=r.id;
  document.getElementById('f_no_kk').value=r.no_kk;
  document.getElementById('f_kepala').value=r.kepala_keluarga;
  document.getElementById('f_alamat').value=r.alamat||'';
  document.getElementById('f_rt').value=r.rt||''; document.getElementById('f_rw').value=r.rw||'';
  document.getElementById('f_kel').value=r.kelurahan||''; document.getElementById('f_kec').value=r.kecamatan||'';
  document.getElementById('f_kota').value=r.kota||''; document.getElementById('f_prov').value=r.provinsi||'';
  document.getElementById('f_status').value=r.status_rumah||'milik';
  new bootstrap.Modal(document.getElementById('mForm')).show();
}
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
