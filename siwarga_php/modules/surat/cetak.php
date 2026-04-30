<?php
require_once __DIR__.'/../../includes/auth.php';
require_login();
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT * FROM pengajuan_surat WHERE id=?"); $stmt->execute([$id]); $s=$stmt->fetch();
if(!$s){die('Surat tidak ditemukan.');}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Cetak <?= e($s['nomor_surat']) ?></title>
<style>
body{font-family:'Times New Roman',serif;max-width:800px;margin:30px auto;padding:30px;color:#000;}
.kop{border-bottom:3px double #000;padding-bottom:14px;text-align:center;}
.kop h2{margin:0;font-size:22px;letter-spacing:1px;}
.kop p{margin:2px 0;font-size:13px;}
h3{text-align:center;margin:30px 0 6px;text-decoration:underline;letter-spacing:2px;}
.no{text-align:center;margin-bottom:30px;font-size:14px;}
table.detail{margin:20px 0;}
table.detail td{padding:4px 8px;font-size:14px;vertical-align:top;}
.ttd{margin-top:60px;display:flex;justify-content:flex-end;}
.ttd .box{text-align:center;width:240px;}
.ttd .box .nama{margin-top:80px;font-weight:bold;text-decoration:underline;}
@media print{.no-print{display:none;}}
.no-print{margin:20px 0;text-align:center;}
.no-print button{padding:8px 24px;background:#0f4c81;color:#fff;border:0;border-radius:6px;cursor:pointer;}
</style></head><body>
<div class="no-print">
  <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
  <button onclick="window.close()" style="background:#64748b;margin-left:8px;">Tutup</button>
</div>
<div class="kop">
  <h2>PEMERINTAH KELURAHAN SUKAMAJU</h2>
  <h2 style="font-size:18px;">RUKUN TETANGGA / RUKUN WARGA</h2>
  <p>Jl. Mawar Raya, Kota Bogor, Jawa Barat - Telp. (0251) 123456</p>
</div>
<h3><?= strtoupper(e($s['jenis_surat'])) ?></h3>
<p class="no">Nomor: <?= e($s['nomor_surat']) ?></p>
<p style="text-align:justify;">Yang bertanda tangan di bawah ini, Ketua RT/RW Kelurahan Sukamaju, dengan ini menerangkan bahwa:</p>
<table class="detail">
  <tr><td>Nama</td><td>:</td><td><b><?= e($s['nama']) ?></b></td></tr>
  <tr><td>NIK</td><td>:</td><td><?= e($s['nik']) ?></td></tr>
  <tr><td>Keperluan</td><td>:</td><td><?= e($s['keperluan']) ?></td></tr>
  <?php if($s['keterangan']): ?><tr><td>Keterangan</td><td>:</td><td><?= nl2br(e($s['keterangan'])) ?></td></tr><?php endif; ?>
</table>
<p style="text-align:justify;">Adalah benar warga di lingkungan kami. Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
<div class="ttd"><div class="box">
  Bogor, <?= tanggal_id(date('Y-m-d')) ?><br>
  Ketua RT/RW
  <div class="nama">( ___________________ )</div>
</div></div>
<?php if($s['status']!=='approved'): ?>
<p style="margin-top:30px;color:#b91c1c;text-align:center;font-style:italic;">[ DRAFT - Surat ini belum disetujui Ketua RT/RW (status: <?= e($s['status']) ?>) ]</p>
<?php endif; ?>
</body></html>
