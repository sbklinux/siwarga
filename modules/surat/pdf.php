<?php
require_once __DIR__.'/../../includes/auth.php';
require_once __DIR__.'/../../includes/pengaturan.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM pengajuan_surat WHERE id=?");
$stmt->execute([$id]);
$s = $stmt->fetch();
if (!$s) die('Surat tidak ditemukan.');

$p = get_pengaturan($pdo);
$img = __DIR__ . '/../../assets/img/';
$upload = __DIR__ . '/../../uploads/';

//$isApproved = ($s['status'] === 'approved');
// Warga tidak boleh akses PDF sebelum surat disetujui Ketua RT/RW
//if (role() === 'warga' && !$isApproved && $s['user_id'] == ($_SESSION['user_id'] ?? 0)) {
//    header('Location: '.url('modules/surat/index.php'));
//    exit;
//}
$isApproved = ($s['status'] === 'approved');
// Warga hanya boleh mengakses surat MILIKNYA SENDIRI
if (role() === 'warga' && (int)$s['user_id'] !== (int)($_SESSION['user_id'] ?? 0)) {
    http_response_code(403);
    die('<div style="font-family:sans-serif;padding:40px;max-width:560px;margin:60px auto;color:#991b1b;background:#fee2e2;border-radius:10px;">
          <h3 style="margin:0 0 8px;">403 - Akses Ditolak</h3>
          <p style="margin:0 0 14px;">Anda hanya dapat mengakses surat yang Anda ajukan sendiri.</p>
          <a href="'.url('modules/surat/index.php').'" style="color:#0f4c81;">&larr; Kembali ke Daftar Surat</a>
        </div>');
}
// Warga tidak boleh akses PDF sebelum surat disetujui Ketua RT/RW (meski milik sendiri)
if (role() === 'warga' && !$isApproved) {
    header('Location: '.url('modules/surat/index.php'));
    exit;
}
// Convert images to base64 untuk Dompdf (supaya embedded di PDF)
function img_base64($path) {
    if (!file_exists($path)) return '';
    $mime = mime_content_type($path) ?: 'image/png';
    return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
}

$rtPath = file_exists($img.'logo_rt.png') ? $img.'logo_rt.png' : (file_exists($img.'logo_rt.jpg') ? $img.'logo_rt.jpg' : '');
$rwPath = file_exists($img.'logo_rw.png') ? $img.'logo_rw.png' : (file_exists($img.'logo_rw.jpg') ? $img.'logo_rw.jpg' : '');
// TTD hanya disertakan jika surat sudah di-approve
$ttdPath = ($isApproved && !empty($p['ttd_file']) && file_exists($upload.'ttd/'.$p['ttd_file'])) ? $upload.'ttd/'.$p['ttd_file'] : '';

$rtImg  = $rtPath ? img_base64($rtPath) : '';
$rwImg  = $rwPath ? img_base64($rwPath) : '';
$ttdImg = $ttdPath ? img_base64($ttdPath) : '';

// Build HTML untuk Dompdf
ob_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style>
@page { margin: 20mm 20mm; }
body { font-family: 'Times New Roman', Times, serif; color:#000; font-size:12pt; }
table.kop { width:100%; border-collapse:collapse; margin-bottom:8px; }
table.kop td { vertical-align:middle; padding:0; }
table.kop .logo-cell { width:95px; text-align:center; }
table.kop img.logo { width:90px; height:90px; }
table.kop .center { text-align:center; }
table.kop h2 { margin:0; font-size:16pt; font-weight:bold; letter-spacing:0.5px; }
table.kop h3 { margin:2px 0 0; font-size:13pt; font-weight:bold; }
table.kop p { margin:3px 0 0; font-size:10pt; font-style:italic; }
.divider { border-bottom: 3px double #000; margin: 6px 0 20px 0; height:4px; }
h3.judul { text-align:center; margin:16px 0 4px; text-decoration:underline; letter-spacing:2px; font-size:13pt; }
.no { text-align:center; margin-bottom:18px; font-size:11pt; }
.isi { text-align:justify; font-size:12pt; line-height:1.5; }
table.detail { width:100%; margin:14px 0; border-collapse:collapse; }
table.detail td { padding:3px 4px; font-size:12pt; vertical-align:top; }
table.detail td.lbl { width:130px; }
table.detail td.sep { width:12px; }
.ttd-table { width:100%; margin-top:35px; border-collapse:collapse; }
.ttd-table td { vertical-align:top; padding:0; }
.ttd-table td.empty { width:58%; }
.ttd-table td.ttd-cell { width:42%; text-align:center; font-size:12pt; line-height:1.6; }
.ttd-table td.ttd-cell p { text-align:center; margin:0; padding:0; }
.ttd-table td.ttd-cell img.ttd { width:130px; height:auto; max-height:75px; }
.status-draft { margin-top:30px; color:#b91c1c; text-align:center; font-style:italic; }
</style>
</head>
<body>

<table class="kop">
  <tr>
    <td class="logo-cell"><?php if ($rtImg): ?><img class="logo" src="<?= $rtImg ?>"><?php endif; ?></td>
    <td class="center">
      <h2><?= e($p['nama_kabupaten']) ?></h2>
      <h3><?= e($p['nama_perumahan']) ?> - <?= e($p['nama_rt_rw']) ?></h3>
      <p><?= e($p['alamat_lengkap']) ?></p>
      <?php if ($p['no_telp'] || $p['email_resmi']): ?>
        <p>Telp: <?= e($p['no_telp']) ?> · Email: <?= e($p['email_resmi']) ?></p>
      <?php endif; ?>
    </td>
    <td class="logo-cell"><?php if ($rwImg): ?><img class="logo" src="<?= $rwImg ?>"><?php endif; ?></td>
  </tr>
</table>
<div class="divider"></div>

<h3 class="judul"><?= strtoupper(e($s['jenis_surat'])) ?></h3>
<p class="no">Nomor: <?= e($s['nomor_surat']) ?></p>

<p class="isi">Yang bertanda tangan di bawah ini, <?= e($p['jabatan_ketua']) ?> <?= e($p['nama_perumahan']) ?>, <?= e($p['alamat_lengkap']) ?>, dengan ini menerangkan bahwa:</p>

<table class="detail">
  <tr><td class="lbl">Nama</td><td class="sep">:</td><td><b><?= e($s['nama']) ?></b></td></tr>
  <tr><td class="lbl">NIK</td><td class="sep">:</td><td><?= e($s['nik']) ?></td></tr>
  <tr><td class="lbl">Keperluan</td><td class="sep">:</td><td><?= e($s['keperluan']) ?></td></tr>
  <?php if ($s['keterangan']): ?>
    <tr><td class="lbl">Keterangan</td><td class="sep">:</td><td><?= nl2br(e($s['keterangan'])) ?></td></tr>
  <?php endif; ?>
</table>

<p class="isi">Adalah benar warga di lingkungan kami. <?= e($p['kop_footer'] ?: 'Surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.') ?></p>

<table class="ttd-table">
  <tr>
    <td class="empty">&nbsp;</td>
    <td class="ttd-cell" align="center">
      <p align="center"><?= e($p['nama_kota_ttd']) ?>, <?= tanggal_id(date('Y-m-d')) ?></p>
      <p align="center"><?= e($p['jabatan_ketua']) ?></p>
      <p align="center">
      <?php if ($ttdImg): ?>
        <img class="ttd" src="<?= $ttdImg ?>">
      <?php else: ?>
        <br><br><br><br>
      <?php endif; ?>
      </p>
      <p align="center"><u><b>( <?= e($p['nama_ketua'] ?: '_______________') ?> )</b></u></p>
    </td>
  </tr>
</table>

<?php if ($s['status'] !== 'approved'): ?>
<p class="status-draft">[ DRAFT - Surat belum disetujui Ketua RT/RW (status: <?= e($s['status']) ?>) ]</p>
<?php endif; ?>

</body>
</html>
<?php
$html = ob_get_clean();

// Generate PDF dengan Dompdf
require_once __DIR__.'/../../vendor/autoload.php';
$options = new \Dompdf\Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Times');

$dompdf = new \Dompdf\Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = 'surat-'.preg_replace('/[^a-z0-9]/i','-', $s['nomor_surat']).'-'.date('Ymd').'.pdf';
$mode = $_GET['mode'] ?? 'view'; // 'view' = inline, 'download' = attachment
$dompdf->stream($filename, ['Attachment' => $mode === 'download']);
exit;
