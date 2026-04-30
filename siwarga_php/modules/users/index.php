<?php
$pageTitle='Manajemen User';
require_once __DIR__.'/../../includes/header.php';
require_role(['super_admin']);
$rows=$pdo->query("SELECT id,username,nama,email,role,rt,rw,aktif,created_at FROM users ORDER BY id ASC")->fetchAll();
?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fa-solid fa-user-shield me-1"></i> Manajemen User</span>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#mForm" onclick="resetForm()"><i class="fa-solid fa-plus"></i> Tambah User</button>
  </div>
  <div class="card-body">
    <table class="table table-hover dt">
      <thead><tr><th>ID</th><th>Username</th><th>Nama</th><th>Email</th><th>Role</th><th>RT/RW</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody><?php foreach($rows as $r): ?>
      <tr>
        <td><?= $r['id'] ?></td><td><?= e($r['username']) ?></td><td><?= e($r['nama']) ?></td>
        <td><?= e($r['email']) ?></td><td><span class="badge bg-primary"><?= e(role_label($r['role'])) ?></span></td>
        <td><?= e($r['rt']) ?>/<?= e($r['rw']) ?></td>
        <td><?= $r['aktif']?'<span class="badge bg-success">Aktif</span>':'<span class="badge bg-secondary">Nonaktif</span>' ?></td>
        <td>
          <button class="btn btn-sm btn-warning" onclick='editRow(<?= json_encode($r) ?>)'><i class="fa-solid fa-edit"></i></button>
          <a class="btn btn-sm btn-danger" href="action.php?act=delete&id=<?= $r['id'] ?>" onclick="return confirm('Hapus user?')"><i class="fa-solid fa-trash"></i></a>
        </td>
      </tr>
      <?php endforeach; ?></tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="mForm" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
<form method="post" action="action.php"><?= csrf_field() ?>
<input type="hidden" name="id" id="f_id"><input type="hidden" name="act" id="f_act" value="add">
<div class="modal-header"><h5 class="modal-title" id="mTitle">Tambah User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="row g-3">
  <div class="col-md-6"><label class="form-label required">Username</label><input name="username" id="f_user" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label" id="lbl_pwd">Password (kosongkan jika tidak diubah)</label><input type="text" name="password" id="f_pwd" class="form-control"></div>
  <div class="col-md-6"><label class="form-label required">Nama</label><input name="nama" id="f_nama" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" id="f_email" class="form-control"></div>
  <div class="col-md-4"><label class="form-label required">Role</label>
    <select name="role" id="f_role" class="form-select">
      <option value="super_admin">Super Admin</option><option value="admin_rtrw">Admin RT/RW</option><option value="ketua_rtrw">Ketua RT/RW</option><option value="warga">Warga</option>
    </select>
  </div>
  <div class="col-md-3"><label class="form-label">RT</label><input name="rt" id="f_rt" class="form-control"></div>
  <div class="col-md-3"><label class="form-label">RW</label><input name="rw" id="f_rw" class="form-control"></div>
  <div class="col-md-2"><label class="form-label">Status</label>
    <select name="aktif" id="f_aktif" class="form-select"><option value="1">Aktif</option><option value="0">Nonaktif</option></select>
  </div>
</div></div>
<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Batal</button><button class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan</button></div>
</form></div></div></div>
<script>
function resetForm(){['f_id','f_user','f_pwd','f_nama','f_email','f_rt','f_rw'].forEach(id=>document.getElementById(id).value='');document.getElementById('f_act').value='add';document.getElementById('f_role').value='warga';document.getElementById('f_aktif').value='1';document.getElementById('mTitle').innerText='Tambah User';document.getElementById('lbl_pwd').innerText='Password';document.getElementById('f_pwd').required=true;}
function setV(id,v){document.getElementById(id).value=v??'';}
function editRow(r){document.getElementById('f_act').value='edit';document.getElementById('mTitle').innerText='Edit User';
  setV('f_id',r.id);setV('f_user',r.username);setV('f_nama',r.nama);setV('f_email',r.email);setV('f_role',r.role);setV('f_rt',r.rt);setV('f_rw',r.rw);setV('f_aktif',r.aktif);setV('f_pwd','');
  document.getElementById('lbl_pwd').innerText='Password (kosongkan jika tidak diubah)';document.getElementById('f_pwd').required=false;
  new bootstrap.Modal(document.getElementById('mForm')).show();}
</script>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
