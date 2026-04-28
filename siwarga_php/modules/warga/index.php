<?php
$pageTitle = 'Data Warga';
require_once __DIR__ . '/../../includes/header.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$rows = $pdo->query("SELECT * FROM warga ORDER BY id DESC")->fetchAll();
$kkList = $pdo->query("SELECT no_kk, kepala_keluarga FROM kartu_keluarga ORDER BY kepala_keluarga")->fetchAll();
$canEdit = can('manage_master');
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-users me-1"></i> Daftar Warga</span>
    <?php if ($canEdit): ?><button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Tambah Warga</button><?php endif; ?>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>No</th><th>NIK</th><th>Nama</th><th>JK</th><th>Tgl Lahir</th><th>Pekerjaan</th><th>RT/RW</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php foreach ($rows as $i=>$r): ?>
        <tr>
          <td><?= $i+1 ?></td><td><?= e($r['nik']) ?></td><td><?= e($r['nama']) ?></td>
          <td><?= $r['jenis_kelamin']=='L'?'Laki-laki':'Perempuan' ?></td>
          <td><?= tanggal_id($r['tanggal_lahir']) ?></td>
          <td><?= e($r['pekerjaan']) ?></td>
          <td><?= e($r['rt']) ?>/<?= e($r['rw']) ?></td>
          <td><?= $r['status_aktif']?'<span class="badge bg-success">Aktif</span>':'<span class="badge bg-secondary">Nonaktif</span>' ?></td>
          <td>
            <button class="btn btn-sm btn-info" onclick='viewRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-eye"></i></button>
            <?php if ($canEdit): ?>
              <button class="btn btn-sm btn-warning" onclick='editRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-edit"></i></button>
              <a class="btn btn-sm btn-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus data warga ini?')"><i class="fa-solid fa-trash"></i></a>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- View modal -->
<div class="modal fade" id="mView" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Detail Warga</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body" id="vBody"></div>
</div></div></div>

<!-- Form modal -->
<div class="modal fade" id="mForm" tabindex="-1"><div class="modal-dialog modal-xl"><div class="modal-content">
<form method="post" action="action.php" enctype="multipart/form-data">
  <?= csrf_field() ?>
  <input type="hidden" name="id" id="f_id"><input type="hidden" name="act" id="f_act" value="add">
  <div class="modal-header"><h5 class="modal-title" id="mTitle">Tambah Warga</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
  <div class="modal-body"><div class="row g-3">
    <div class="col-md-4"><label class="form-label required">NIK</label><input name="nik" id="f_nik" class="form-control" required maxlength="20"></div>
    <div class="col-md-4"><label class="form-label">No KK</label>
      <select name="no_kk" id="f_kk" class="form-select"><option value="">-- pilih --</option>
        <?php foreach($kkList as $k): ?><option value="<?= e($k['no_kk']) ?>"><?= e($k['no_kk']) ?> - <?= e($k['kepala_keluarga']) ?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4"><label class="form-label required">Nama Lengkap</label><input name="nama" id="f_nama" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Tempat Lahir</label><input name="tempat_lahir" id="f_tl" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Tanggal Lahir</label><input type="date" name="tanggal_lahir" id="f_tgl" class="form-control"></div>
    <div class="col-md-4"><label class="form-label required">Jenis Kelamin</label>
      <select name="jenis_kelamin" id="f_jk" class="form-select"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select>
    </div>
    <div class="col-md-3"><label class="form-label">Agama</label>
      <select name="agama" id="f_agama" class="form-select"><option>Islam</option><option>Kristen</option><option>Katolik</option><option>Hindu</option><option>Buddha</option><option>Konghucu</option></select>
    </div>
    <div class="col-md-3"><label class="form-label">Pendidikan</label>
      <select name="pendidikan" id="f_pend" class="form-select"><option>Tidak Sekolah</option><option>TK</option><option>SD</option><option>SMP</option><option>SMA</option><option>D3</option><option>S1</option><option>S2</option><option>S3</option></select>
    </div>
    <div class="col-md-3"><label class="form-label">Pekerjaan</label><input name="pekerjaan" id="f_pek" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Status Perkawinan</label>
      <select name="status_perkawinan" id="f_sp" class="form-select"><option>Belum Kawin</option><option>Kawin</option><option>Cerai Hidup</option><option>Cerai Mati</option></select>
    </div>
    <div class="col-md-3"><label class="form-label">Status Keluarga</label>
      <select name="status_keluarga" id="f_sk" class="form-select"><option>Kepala Keluarga</option><option>Istri</option><option>Suami</option><option>Anak</option><option>Mertua</option><option>Lainnya</option></select>
    </div>
    <div class="col-md-3"><label class="form-label">No HP</label><input name="no_hp" id="f_hp" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Email</label><input type="email" name="email" id="f_email" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">Status</label>
      <select name="status_aktif" id="f_sa" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
    </div>
    <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" id="f_alamat" class="form-control" rows="2"></textarea></div>
    <div class="col-md-3"><label class="form-label">RT</label><input name="rt" id="f_rt" class="form-control"></div>
    <div class="col-md-3"><label class="form-label">RW</label><input name="rw" id="f_rw" class="form-control"></div>
    <div class="col-12"><hr class="my-2"><h6 class="text-muted"><i class="fa-solid fa-camera me-1"></i> Upload Dokumen (opsional)</h6></div>
    <div class="col-md-6">
      <label class="form-label"><i class="fa-solid fa-id-card me-1"></i> Foto KTP <small class="text-muted">(jpg/png/pdf, max 5MB)</small></label>
      <input type="file" name="foto_ktp" class="form-control" accept="image/*,.pdf">
      <div id="prev_ktp" class="mt-2"></div>
    </div>
    <div class="col-md-6">
      <label class="form-label"><i class="fa-solid fa-file-image me-1"></i> Foto KK <small class="text-muted">(jpg/png/pdf, max 5MB)</small></label>
      <input type="file" name="foto_kk" class="form-control" accept="image/*,.pdf">
      <div id="prev_kk" class="mt-2"></div>
    </div>
  </div></div>
  <div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form>
</div></div></div>

<script>
const BASE_UPLOAD = '<?= url('uploads') ?>';
function resetForm(){
  document.getElementById('f_act').value='add';
  document.getElementById('mTitle').innerText='Tambah Warga';
  ['f_id','f_nik','f_nama','f_tl','f_tgl','f_pek','f_hp','f_email','f_alamat','f_rt','f_rw'].forEach(id=>document.getElementById(id).value='');
  document.getElementById('prev_ktp').innerHTML=''; document.getElementById('prev_kk').innerHTML='';
  document.getElementById('f_kk').value=''; document.getElementById('f_jk').value='L';
  document.getElementById('f_agama').value='Islam'; document.getElementById('f_pend').value='SMA';
  document.getElementById('f_sp').value='Belum Kawin'; document.getElementById('f_sk').value='Anak';
  document.getElementById('f_sa').value='1';
}
function setVal(id,v){ document.getElementById(id).value = v ?? ''; }
function editRow(r){
  document.getElementById('f_act').value='edit'; document.getElementById('mTitle').innerText='Edit Warga';
  setVal('f_id',r.id);setVal('f_nik',r.nik);setVal('f_kk',r.no_kk);setVal('f_nama',r.nama);
  setVal('f_tl',r.tempat_lahir);setVal('f_tgl',r.tanggal_lahir);setVal('f_jk',r.jenis_kelamin);
  setVal('f_agama',r.agama);setVal('f_pend',r.pendidikan);setVal('f_pek',r.pekerjaan);
  setVal('f_sp',r.status_perkawinan);setVal('f_sk',r.status_keluarga);
  setVal('f_hp',r.no_hp);setVal('f_email',r.email);setVal('f_alamat',r.alamat);
  setVal('f_rt',r.rt);setVal('f_rw',r.rw);setVal('f_sa',r.status_aktif);
  document.getElementById('prev_ktp').innerHTML = r.foto_ktp ? `<a href="${BASE_UPLOAD}/ktp/${r.foto_ktp}" target="_blank" class="small"><i class="fa-solid fa-image"></i> Lihat KTP saat ini</a>` : '';
  document.getElementById('prev_kk').innerHTML  = r.foto_kk  ? `<a href="${BASE_UPLOAD}/kk/${r.foto_kk}" target="_blank" class="small"><i class="fa-solid fa-image"></i> Lihat KK saat ini</a>` : '';
  new bootstrap.Modal(document.getElementById('mForm')).show();
}
function viewRow(r){
  const html = `
   <table class="table table-sm"><tbody>
    <tr><th width="35%">NIK</th><td>${r.nik||'-'}</td></tr>
    <tr><th>No KK</th><td>${r.no_kk||'-'}</td></tr>
    <tr><th>Nama</th><td>${r.nama||'-'}</td></tr>
    <tr><th>Tempat / Tgl Lahir</th><td>${(r.tempat_lahir||'-')+', '+(r.tanggal_lahir||'-')}</td></tr>
    <tr><th>Jenis Kelamin</th><td>${r.jenis_kelamin=='L'?'Laki-laki':'Perempuan'}</td></tr>
    <tr><th>Agama</th><td>${r.agama||'-'}</td></tr>
    <tr><th>Pendidikan</th><td>${r.pendidikan||'-'}</td></tr>
    <tr><th>Pekerjaan</th><td>${r.pekerjaan||'-'}</td></tr>
    <tr><th>Status Perkawinan</th><td>${r.status_perkawinan||'-'}</td></tr>
    <tr><th>Status Keluarga</th><td>${r.status_keluarga||'-'}</td></tr>
    <tr><th>No HP / Email</th><td>${(r.no_hp||'-')+' / '+(r.email||'-')}</td></tr>
    <tr><th>Alamat</th><td>${r.alamat||'-'} (RT ${r.rt||'-'}/RW ${r.rw||'-'})</td></tr>
   </tbody></table>`;
  document.getElementById('vBody').innerHTML=html;
  new bootstrap.Modal(document.getElementById('mView')).show();
}
</script>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
