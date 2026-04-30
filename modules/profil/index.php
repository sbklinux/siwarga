<?php
$pageTitle='Profil Saya';
require_once __DIR__.'/../../includes/header.php';
require_login();
$u = $_SESSION['user'];
$warga = null;
if ($u['role']==='warga' && !empty($u['nik'])) {
  $stmt=$pdo->prepare("SELECT * FROM warga WHERE nik=? LIMIT 1"); $stmt->execute([$u['nik']]); $warga=$stmt->fetch();
}
?>
<div class="row g-3">
  <div class="col-lg-4">
    <div class="card text-center"><div class="card-body p-4">
      <div style="width:110px;height:110px;border-radius:50%;background:linear-gradient(135deg,#0f4c81,#1e6bb8);color:#fff;display:flex;align-items:center;justify-content:center;font-size:46px;margin:0 auto 16px;">
        <i class="fa-solid fa-user"></i>
      </div>
      <h5 class="mb-1"><?= e($u['nama']) ?></h5>
      <p class="text-muted mb-2">@<?= e($u['username']) ?></p>
      <span class="badge bg-primary"><?= e(role_label($u['role'])) ?></span>
      <?php if($u['rt']||$u['rw']): ?><div class="small text-muted mt-2">RT <?= e($u['rt']) ?> / RW <?= e($u['rw']) ?></div><?php endif; ?>
      <hr>
      <div class="text-start small">
        <div><i class="fa-solid fa-envelope me-2 text-muted"></i><?= e($u['email'] ?: '-') ?></div>
        <?php if(!empty($u['nik'])): ?><div class="mt-2"><i class="fa-solid fa-id-card me-2 text-muted"></i><?= e($u['nik']) ?></div><?php endif; ?>
        <div class="mt-2"><i class="fa-solid fa-clock me-2 text-muted"></i>Bergabung <?= tanggal_id($u['created_at']) ?></div>
      </div>
    </div></div>
  </div>

  <div class="col-lg-8">
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab1"><i class="fa-solid fa-user-pen me-1"></i> Edit Profil</a></li>
      <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab2"><i class="fa-solid fa-key me-1"></i> Ganti Password</a></li>
      <?php if($warga): ?><li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab3"><i class="fa-solid fa-house me-1"></i> Data Warga</a></li><?php endif; ?>
    </ul>
    <div class="tab-content border border-top-0 bg-white p-4 rounded-bottom">
      <div class="tab-pane fade show active" id="tab1">
        <form method="post" action="action.php">
          <?= csrf_field() ?><input type="hidden" name="act" value="profil">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Username</label><input class="form-control" value="<?= e($u['username']) ?>" disabled></div>
            <div class="col-md-6"><label class="form-label required">Nama Lengkap</label><input name="nama" class="form-control" required value="<?= e($u['nama']) ?>"></div>
            <div class="col-md-12"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= e($u['email']) ?>"></div>
          </div>
          <button class="btn btn-primary mt-3"><i class="fa-solid fa-save me-1"></i> Simpan Profil</button>
        </form>
      </div>

      <div class="tab-pane fade" id="tab2">
        <form method="post" action="action.php">
          <?= csrf_field() ?><input type="hidden" name="act" value="password">
          <div class="row g-3">
            <div class="col-md-12"><label class="form-label required">Password Lama</label><input type="password" name="old" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label required">Password Baru</label><input type="password" name="new" class="form-control" required minlength="6"></div>
            <div class="col-md-6"><label class="form-label required">Konfirmasi Password</label><input type="password" name="confirm" class="form-control" required minlength="6"></div>
          </div>
          <button class="btn btn-warning mt-3"><i class="fa-solid fa-key me-1"></i> Ganti Password</button>
        </form>
      </div>

      <?php if($warga): ?>
      <div class="tab-pane fade" id="tab3">
        <form method="post" action="action.php" enctype="multipart/form-data">
          <?= csrf_field() ?><input type="hidden" name="act" value="warga">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label">NIK</label><input class="form-control" value="<?= e($warga['nik']) ?>" disabled></div>
            <div class="col-md-6"><label class="form-label">No KK</label><input class="form-control" value="<?= e($warga['no_kk']) ?>" disabled></div>
            <div class="col-md-6"><label class="form-label">No HP</label><input name="no_hp" class="form-control" value="<?= e($warga['no_hp']) ?>"></div>
            <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email_warga" class="form-control" value="<?= e($warga['email']) ?>"></div>
            <div class="col-md-6"><label class="form-label">Pekerjaan</label><input name="pekerjaan" class="form-control" value="<?= e($warga['pekerjaan']) ?>"></div>
            <div class="col-md-6"><label class="form-label">Status Perkawinan</label>
              <select name="status_perkawinan" class="form-select">
                <?php foreach(['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $s): ?>
                  <option <?= $warga['status_perkawinan']===$s?'selected':'' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12"><label class="form-label">Alamat</label><textarea name="alamat" class="form-control" rows="2"><?= e($warga['alamat']) ?></textarea></div>
            <div class="col-md-6"><label class="form-label">Upload Foto KTP</label>
              <input type="file" name="foto_ktp" class="form-control" accept="image/*">
              <?php if($warga['foto_ktp']): ?><a href="<?= url('uploads/ktp/'.$warga['foto_ktp']) ?>" target="_blank" class="small mt-1 d-inline-block"><i class="fa-solid fa-image"></i> Lihat foto saat ini</a><?php endif; ?>
            </div>
            <div class="col-md-6"><label class="form-label">Upload Foto KK</label>
              <input type="file" name="foto_kk" class="form-control" accept="image/*">
              <?php if($warga['foto_kk']): ?><a href="<?= url('uploads/kk/'.$warga['foto_kk']) ?>" target="_blank" class="small mt-1 d-inline-block"><i class="fa-solid fa-image"></i> Lihat foto saat ini</a><?php endif; ?>
            </div>
          </div>
          <div class="alert alert-info mt-3 small mb-0"><i class="fa-solid fa-info-circle me-1"></i> Perubahan data warga akan disimpan langsung. Untuk perubahan data fundamental (NIK, nama, dll), silakan hubungi Admin RT/RW.</div>
          <button class="btn btn-primary mt-3"><i class="fa-solid fa-save me-1"></i> Simpan Data Warga</button>
        </form>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require_once __DIR__.'/../../includes/footer.php'; ?>
