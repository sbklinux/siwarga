<?php
require_once __DIR__.'/../../includes/auth.php';
require_once __DIR__.'/../../includes/pengaturan.php';
require_login();
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT * FROM pengajuan_surat WHERE id=?"); $stmt->execute([$id]); $s=$stmt->fetch();
if(!$s){die('Surat tidak ditemukan.');}

$p = get_pengaturan($pdo);
$img = __DIR__ . '/../../assets/img/';
$rtFile = file_exists($img.'logo_rt.png') ? 'logo_rt.png' : (file_exists($img.'logo_rt.jpg') ? 'logo_rt.jpg' : '');
$rwFile = file_exists($img.'logo_rw.png') ? 'logo_rw.png' : (file_exists($img.'logo_rw.jpg') ? 'logo_rw.jpg' : '');
// TTD hanya ditampilkan bila surat SUDAH di-approve
$isApproved = ($s['status'] === 'approved');
$ttdFile = ($isApproved && !empty($p['ttd_file']) && file_exists(__DIR__.'/../../uploads/ttd/'.$p['ttd_file'])) ? $p['ttd_file'] : '';
// Warga tidak boleh buka preview jika surat belum di-approve
//if (role() === 'warga' && !$isApproved && $s['user_id'] == ($_SESSION['user_id'] ?? 0)) {
//  header('Location: '.url('modules/surat/index.php'));
//  exit;
//}
// Warga hanya boleh mengakses surat MILIKNYA SENDIRI
if (role() === 'warga' && (int)$s['user_id'] !== (int)($_SESSION['user_id'] ?? 0)) {
  http_response_code(403);
  die('<div style="font-family:sans-serif;padding:40px;max-width:560px;margin:60px auto;color:#991b1b;background:#fee2e2;border-radius:10px;">
        <h3 style="margin:0 0 8px;">403 - Akses Ditolak</h3>
        <p style="margin:0 0 14px;">Anda hanya dapat mengakses surat yang Anda ajukan sendiri.</p>
        <a href="'.url('modules/surat/index.php').'" style="color:#0f4c81;">&larr; Kembali ke Daftar Surat</a>
      </div>');
}
// Warga tidak boleh buka preview jika surat belum di-approve (meski milik sendiri)
if (role() === 'warga' && !$isApproved) {
  header('Location: '.url('modules/surat/index.php'));
  exit;
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Preview <?= e($s['nomor_surat']) ?></title>
<style>
*{box-sizing:border-box;}
body{font-family:'Times New Roman',serif;max-width:780px;margin:30px auto;padding:25px 30px;color:#000;}
.kop{border-bottom:3px double #000;padding-bottom:14px;margin-bottom:10px;display:flex;align-items:center;justify-content:center;gap:18px;}
.kop .logo{flex:0 0 90px;height:90px;display:flex;align-items:center;justify-content:center;}
.kop .logo img{max-width:90px;max-height:90px;object-fit:contain;mix-blend-mode:multiply;}
.kop .center{flex:1;text-align:center;line-height:1.3;}
.kop h2{margin:0;font-size:20px;letter-spacing:.5px;font-weight:bold;}
.kop h3{margin:3px 0 0;font-size:16px;font-weight:bold;}
.kop p{margin:4px 0 0;font-size:12px;font-style:italic;}
h3.judul{text-align:center;margin:30px 0 6px;text-decoration:underline;letter-spacing:2px;font-size:16px;}
.no{text-align:center;margin-bottom:25px;font-size:13px;}
.isi{text-align:justify;font-size:14px;line-height:1.6;}
table.detail{margin:18px 0;width:100%;}
table.detail td{padding:4px 6px;font-size:14px;vertical-align:top;}
table.detail td:nth-child(1){width:140px;}
table.detail td:nth-child(2){width:15px;}
.ttd{margin-top:50px;display:flex;justify-content:flex-end;}
.ttd .box{text-align:center;width:260px;font-size:14px;}
.ttd .box .jabatan{margin-bottom:6px;}
.ttd .box img.ttd-img{max-height:80px;max-width:180px;margin:4px auto;display:block;mix-blend-mode:multiply;}
.ttd .box .spacer{height:80px;}
.ttd .box .nama{margin-top:8px;font-weight:bold;text-decoration:underline;}
@media print{
  .no-print{display:none;}
  body{margin:0;padding:15px 20px;}
}
.no-print{margin:0 0 20px;text-align:center;display:flex;gap:8px;justify-content:center;flex-wrap:wrap;}
.no-print button, .no-print a.btn{padding:8px 20px;background:#0f4c81;color:#fff;border:0;border-radius:6px;cursor:pointer;font-size:14px;text-decoration:none;display:inline-block;}
.no-print a.btn-pdf{background:#dc2626;}
.no-print a.btn-dl{background:#16a34a;}
.no-print button.sec{background:#64748b;}
@media (max-width:600px){
  body{padding:12px;margin:5px;}
  .kop{gap:10px;padding-bottom:10px;}
  .kop .logo{flex:0 0 55px;height:55px;}
  .kop .logo img{max-width:55px;max-height:55px;}
  .kop h2{font-size:14px;letter-spacing:0;}
  .kop h3{font-size:12px;}
  .kop p{font-size:9.5px;}
  h3.judul{font-size:13px;letter-spacing:1px;}
  .isi, table.detail td{font-size:12px;}
}
</style></head><body>
<div class="kop">
  <div class="logo"><?php if ($rtFile): ?><img src="<?= url('assets/img/'.$rtFile) ?>" alt="Logo RT"><?php endif; ?></div>
  <div class="center">
    <h2><?= e($p['nama_kabupaten']) ?></h2>
    <h3><?= e($p['nama_perumahan']) ?> - <?= e($p['nama_rt_rw']) ?></h3>
    <p><?= e($p['alamat_lengkap']) ?></p>
    <?php if ($p['no_telp'] || $p['email_resmi']): ?>
      <p style="font-style:normal;font-size:11px;">Telp: <?= e($p['no_telp']) ?> · Email: <?= e($p['email_resmi']) ?></p>
    <?php endif; ?>
  </div>
  <div class="logo"><?php if ($rwFile): ?><img src="<?= url('assets/img/'.$rwFile) ?>" alt="Logo RW"><?php endif; ?></div>
</div>
<h3 class="judul"><?= strtoupper(e($s['jenis_surat'])) ?></h3>
<p class="no">Nomor: <?= e($s['nomor_surat']) ?></p>
<p class="isi">Yang bertanda tangan di bawah ini, <?= e($p['jabatan_ketua']) ?> <?= e($p['nama_perumahan']) ?>, <?= e($p['alamat_lengkap']) ?>, dengan ini menerangkan bahwa:</p>
<table class="detail">
  <tr><td>Nama</td><td>:</td><td><b><?= e($s['nama']) ?></b></td></tr>
  <tr><td>NIK</td><td>:</td><td><?= e($s['nik']) ?></td></tr>
  <tr><td>Keperluan</td><td>:</td><td><?= e($s['keperluan']) ?></td></tr>
  <?php if($s['keterangan']): ?><tr><td>Keterangan</td><td>:</td><td><?= nl2br(e($s['keterangan'])) ?></td></tr><?php endif; ?>
</table>
<p class="isi">Adalah benar warga di lingkungan kami. <?= e($p['kop_footer'] ?: 'Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.') ?></p>
<div class="ttd"><div class="box">
  <?= e($p['nama_kota_ttd']) ?>, <?= tanggal_id(date('Y-m-d')) ?><br>
  <div class="jabatan"><?= e($p['jabatan_ketua']) ?></div>
  <?php if ($ttdFile): ?>
    <img class="ttd-img" src="<?= url('uploads/ttd/'.$ttdFile) ?>" alt="TTD">
  <?php else: ?>
    <div class="spacer"></div>
  <?php endif; ?>
  <div class="nama">( <?= e($p['nama_ketua'] ?: '___________________') ?> )</div>
</div></div>
<?php if($s['status']!=='approved'): ?>
<p style="margin-top:30px;color:#b91c1c;text-align:center;font-style:italic;">[ DRAFT - Surat ini belum disetujui Ketua RT/RW (status: <?= e($s['status']) ?>) ]</p>
<?php endif; ?>
</body></html>
