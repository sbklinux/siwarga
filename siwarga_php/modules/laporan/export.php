<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);

$allowed=['warga','kartu_keluarga','pendatang','kelahiran','kematian','pindah','pengajuan_surat','iuran','keuangan','buku_tamu','laporan_keamanan'];
$table=$_GET['table']??'';
$type=$_GET['type']??'csv';
if(!in_array($table,$allowed,true)){die('Tabel tidak valid.');}
$rows=$pdo->query("SELECT * FROM `$table` ORDER BY id DESC")->fetchAll();
$cols = $rows ? array_keys($rows[0]) : [];

if ($type==='csv') {
  header('Content-Type: text/csv; charset=utf-8');
  header('Content-Disposition: attachment; filename="'.$table.'-'.date('Ymd-His').'.csv"');
  $out = fopen('php://output','w');
  fwrite($out, "\xEF\xBB\xBF");
  if ($cols) fputcsv($out, $cols);
  foreach ($rows as $r) fputcsv($out, $r);
  fclose($out); exit;
}

// PDF -> via browser print
$title = strtoupper(str_replace('_',' ',$table));
?>
<!doctype html><html><head><meta charset="utf-8"><title>Export <?= e($title) ?></title>
<style>
body{font-family:'Segoe UI',sans-serif;margin:30px;color:#000;}
h2{margin:0;}.kop{border-bottom:3px double #000;padding-bottom:10px;margin-bottom:18px;text-align:center;}
table{width:100%;border-collapse:collapse;font-size:11px;}
table th,table td{border:1px solid #999;padding:5px 7px;text-align:left;vertical-align:top;}
table th{background:#0f4c81;color:#fff;}
@media print{.no-print{display:none;}}
.no-print{margin-bottom:14px;}
.no-print button{padding:8px 18px;background:#0f4c81;color:#fff;border:0;border-radius:6px;cursor:pointer;}
</style></head><body>
<div class="no-print">
  <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
  <button onclick="window.close()" style="background:#64748b;margin-left:6px;">Tutup</button>
</div>
<div class="kop">
  <h2>SIWARGA - LAPORAN <?= e($title) ?></h2>
  <small>Dicetak: <?= date('d M Y H:i') ?> · Total: <?= count($rows) ?> data</small>
</div>
<table>
  <thead><tr><?php foreach($cols as $c): ?><th><?= e($c) ?></th><?php endforeach; ?></tr></thead>
  <tbody>
  <?php foreach($rows as $r): ?>
    <tr><?php foreach($cols as $c): ?><td><?= e($r[$c]) ?></td><?php endforeach; ?></tr>
  <?php endforeach; ?>
  <?php if(!$rows): ?><tr><td colspan="<?= count($cols)?:1 ?>" style="text-align:center;color:#666;">Tidak ada data</td></tr><?php endif; ?>
  </tbody>
</table>
</body></html>
