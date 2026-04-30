<?php
require_once __DIR__.'/../../includes/auth.php';
require_once __DIR__.'/../../includes/pengaturan.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);

$allowed = ['warga','kartu_keluarga','pendatang','kelahiran','kematian','pindah','pengajuan_surat','iuran','keuangan','buku_tamu','laporan_keamanan'];
$table = $_GET['table'] ?? '';
if (!in_array($table, $allowed, true)) die('Tabel tidak valid.');

$rows = $pdo->query("SELECT * FROM `$table` ORDER BY id DESC")->fetchAll();
$cols = $rows ? array_keys($rows[0]) : [];
$p = get_pengaturan($pdo);
$img = __DIR__ . '/../../assets/img/';

function img_base64_pdf($path) {
    if (!file_exists($path)) return '';
    $mime = mime_content_type($path) ?: 'image/png';
    return 'data:'.$mime.';base64,'.base64_encode(file_get_contents($path));
}

$rtPath = file_exists($img.'logo_rt.png') ? $img.'logo_rt.png' : '';
$rwPath = file_exists($img.'logo_rw.png') ? $img.'logo_rw.png' : '';
$rtImg = $rtPath ? img_base64_pdf($rtPath) : '';
$rwImg = $rwPath ? img_base64_pdf($rwPath) : '';

$title = strtoupper(str_replace('_',' ', $table));

ob_start();
?>
<!doctype html>
<html><head><meta charset="utf-8">
<style>
@page { margin: 12mm; }
body { font-family: 'DejaVu Sans', sans-serif; font-size:9pt; color:#000; }
table.kop { width:100%; border-collapse:collapse; margin-bottom:5px; }
table.kop td { vertical-align:middle; padding:0; }
table.kop .logo-cell { width:75px; text-align:center; }
table.kop img.logo { width:70px; height:70px; }
table.kop .center { text-align:center; }
table.kop h2 { margin:0; font-size:13pt; font-weight:bold; }
table.kop h3 { margin:2px 0 0; font-size:10pt; font-weight:600; }
table.kop p { margin:2px 0 0; font-size:8.5pt; color:#333; }
.divider { border-bottom:3px double #000; height:4px; margin:4px 0 12px; }
table.data { width:100%; border-collapse:collapse; font-size:8pt; }
table.data th, table.data td { border:1px solid #999; padding:4px 5px; text-align:left; vertical-align:top; word-wrap:break-word; }
table.data th { background:#0f4c81; color:#fff; font-weight:bold; }
</style></head><body>

<table class="kop">
  <tr>
    <td class="logo-cell"><?php if ($rtImg): ?><img class="logo" src="<?= $rtImg ?>"><?php endif; ?></td>
    <td class="center">
      <h2><?= e($p['nama_kabupaten']) ?></h2>
      <h3><?= e($p['nama_perumahan']) ?> - <?= e($p['nama_rt_rw']) ?></h3>
      <p>LAPORAN <?= e($title) ?> · Dicetak: <?= date('d M Y H:i') ?> · Total: <?= count($rows) ?> data</p>
    </td>
    <td class="logo-cell"><?php if ($rwImg): ?><img class="logo" src="<?= $rwImg ?>"><?php endif; ?></td>
  </tr>
</table>
<div class="divider"></div>

<table class="data">
  <thead><tr><?php foreach ($cols as $c): ?><th><?= e($c) ?></th><?php endforeach; ?></tr></thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr><?php foreach ($cols as $c): ?><td><?= e($r[$c]) ?></td><?php endforeach; ?></tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?><tr><td colspan="<?= count($cols)?:1 ?>" style="text-align:center;color:#666;">Tidak ada data</td></tr><?php endif; ?>
  </tbody>
</table>

</body></html>
<?php
$html = ob_get_clean();

require_once __DIR__.'/../../vendor/autoload.php';
$options = new \Dompdf\Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new \Dompdf\Dompdf($options);
$dompdf->loadHtml($html);
$orientation = count($cols) > 6 ? 'landscape' : 'portrait';
$dompdf->setPaper('A4', $orientation);
$dompdf->render();

$filename = 'laporan-'.$table.'-'.date('Ymd-His').'.pdf';
$mode = $_GET['mode'] ?? 'view';
$dompdf->stream($filename, ['Attachment' => $mode === 'download']);
exit;
