<?php
$pageTitle = 'Pengaturan Kop Surat';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/pengaturan.php';
require_role(['super_admin']);
$p = get_pengaturan($pdo);
?>
<div class="card">
  <div class="card-header"><i class="fa-solid fa-gear me-1"></i> Pengaturan Kop Surat & Identitas RT/RW</div>
  <div class="card-body">
    <form method="post" action="action.php" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="alert alert-info small"><i class="fa-solid fa-info-circle me-1"></i> Pengaturan ini akan tampil di <b>kop semua surat yang dicetak</b>, <b>export PDF laporan</b>, dan <b>tanda tangan ketua</b>. Ubah sesuai data RT/RW Anda.</div>

      <h6 class="text-primary mt-3 mb-2"><i class="fa-solid fa-building-columns me-1"></i> Identitas Wilayah</h6>
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Nama Kabupaten / Kota</label>
          <input name="nama_kabupaten" class="form-control" value="<?= e($p['nama_kabupaten']) ?>" placeholder="Contoh: PEMERINTAH KABUPATEN BEKASI">
        </div>
        <div class="col-md-6"><label class="form-label">Nama Perumahan / Kelurahan</label>
          <input name="nama_perumahan" class="form-control" value="<?= e($p['nama_perumahan']) ?>" placeholder="Contoh: PERUMAHAN KIRANA CIBITUNG">
        </div>
        <div class="col-md-6"><label class="form-label">Nama RT / RW</label>
          <input name="nama_rt_rw" class="form-control" value="<?= e($p['nama_rt_rw']) ?>" placeholder="Contoh: RT 01 / RW 19">
        </div>
        <div class="col-md-6"><label class="form-label">Kota / Tempat Tanda Tangan</label>
          <input name="nama_kota_ttd" class="form-control" value="<?= e($p['nama_kota_ttd']) ?>" placeholder="Contoh: Cibitung">
        </div>
        <div class="col-12"><label class="form-label">Alamat Lengkap</label>
          <textarea name="alamat_lengkap" class="form-control" rows="2" placeholder="Desa, Kecamatan, Kabupaten, Provinsi"><?= e($p['alamat_lengkap']) ?></textarea>
        </div>
        <div class="col-md-6"><label class="form-label">No Telepon</label>
          <input name="no_telp" class="form-control" value="<?= e($p['no_telp']) ?>">
        </div>
        <div class="col-md-6"><label class="form-label">Email Resmi</label>
          <input type="email" name="email_resmi" class="form-control" value="<?= e($p['email_resmi']) ?>">
        </div>
      </div>

      <h6 class="text-primary mt-4 mb-2"><i class="fa-solid fa-user-tie me-1"></i> Data Ketua RT/RW</h6>
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Nama Lengkap Ketua</label>
          <input name="nama_ketua" class="form-control" value="<?= e($p['nama_ketua']) ?>" placeholder="Nama yang akan muncul di bawah TTD">
        </div>
        <div class="col-md-6"><label class="form-label">Jabatan Ketua</label>
          <input name="jabatan_ketua" class="form-control" value="<?= e($p['jabatan_ketua']) ?>" placeholder="Contoh: Ketua RT 01 / RW 19">
        </div>
        <div class="col-md-6">
          <label class="form-label">Upload Tanda Tangan Digital <small class="text-muted">(PNG transparan, max 2MB)</small></label>
          <input type="file" name="ttd_file" class="form-control" accept="image/png,image/jpeg">
          <small class="text-muted">Rekomendasi: scan TTD asli dengan background putih, lalu hapus background jadi PNG transparan.</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">Preview TTD Saat Ini</label>
          <?php if (!empty($p['ttd_file']) && file_exists(__DIR__.'/../../uploads/ttd/'.$p['ttd_file'])): ?>
            <div style="background:#f8fafc;border:1px dashed #cbd5e1;padding:10px;border-radius:6px;text-align:center;">
              <img src="<?= url('uploads/ttd/'.$p['ttd_file']) ?>" style="max-height:80px;max-width:200px;mix-blend-mode:multiply;">
              <div class="small mt-1">
                <a href="action.php?act=hapus_ttd" class="text-danger" onclick="return confirm('Hapus TTD yang ada?')"><i class="fa-solid fa-trash"></i> Hapus TTD</a>
              </div>
            </div>
          <?php else: ?>
            <div class="text-muted small border rounded p-3 text-center" style="background:#f8fafc;">
              <i class="fa-solid fa-signature fa-2x mb-1 d-block"></i> Belum ada TTD diupload
            </div>
          <?php endif; ?>
        </div>
      </div>

      <h6 class="text-primary mt-4 mb-2"><i class="fa-solid fa-file-lines me-1"></i> Teks Tambahan</h6>
      <div class="row g-3">
        <div class="col-12"><label class="form-label">Kalimat Penutup Default Surat</label>
          <textarea name="kop_footer" class="form-control" rows="2"><?= e($p['kop_footer']) ?></textarea>
        </div>
      </div>

      <div class="mt-4 pt-3 border-top d-flex gap-2">
        <button class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Simpan Pengaturan</button>
        <a href="<?= url('modules/surat/cetak.php?id=1') ?>" target="_blank" class="btn btn-outline-secondary"><i class="fa-solid fa-eye me-1"></i> Preview Kop Surat</a>
      </div>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
