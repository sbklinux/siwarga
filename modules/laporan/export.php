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

$title = strtoupper(str_replace('_',' ',$table));
$img = __DIR__ . '/../../assets/img/';
$rtFile = file_exists($img.'logo_rt.png') ? 'logo_rt.png' : (file_exists($img.'logo_rt.jpg') ? 'logo_rt.jpg' : '');
$rwFile = file_exists($img.'logo_rw.png') ? 'logo_rw.png' : (file_exists($img.'logo_rw.jpg') ? 'logo_rw.jpg' : '');
?>
<!doctype html><html><head><meta charset="utf-8"><title>Export <?= e($title) ?></title>
<style>
*{box-sizing:border-box;}
body{font-family:'Segoe UI',sans-serif;margin:25px 30px;color:#000;}
.kop{border-bottom:3px double #000;padding-bottom:10px;margin-bottom:18px;display:flex;align-items:center;justify-content:center;gap:15px;}
.kop .logo{flex:0 0 70px;height:70px;display:flex;align-items:center;justify-content:center;}
.kop .logo img{max-width:70px;max-height:70px;object-fit:contain;mix-blend-mode:multiply;}
.kop .center{flex:1;text-align:center;line-height:1.3;}
.kop h2{margin:0;font-size:17px;font-weight:bold;}
.kop h3{margin:3px 0 0;font-size:13px;font-weight:600;}
.kop p{margin:3px 0 0;font-size:11px;color:#333;}
table{width:100%;border-collapse:collapse;font-size:11px;}
table th,table td{border:1px solid #999;padding:5px 7px;text-align:left;vertical-align:top;word-break:break-word;}
table th{background:#0f4c81;color:#fff;}
@media print{.no-print{display:none;}body{margin:15px;}}
.no-print{margin-bottom:14px;text-align:center;}
.no-print button{padding:8px 18px;background:#0f4c81;color:#fff;border:0;border-radius:6px;cursor:pointer;margin-right:4px;font-size:13px;}
@media (max-width:600px){
  body{margin:10px;}
  .kop{gap:8px;}
  .kop .logo{flex:0 0 45px;height:45px;}
  .kop .logo img{max-width:45px;max-height:45px;}
  .kop h2{font-size:13px;}
  .kop h3{font-size:10px;}
  .kop p{font-size:9px;}
  table{font-size:10px;}
}
</style></head><body>
<div class="no-print">
  <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
  <button onclick="window.close()" style="background:#64748b;">Tutup</button>
</div>
<div class="kop">
  <div class="logo"><?php if ($rtFile): ?><img src="<?= url('assets/img/'.$rtFile) ?>" alt="Logo RT"><?php endif; ?></div>
  <div class="center">
    <h2>SIWARGA - RT 01 / RW 19 PERUMAHAN KIRANA CIBITUNG</h2>
    <h3>LAPORAN <?= e($title) ?></h3>
    <p>Dicetak: <?= date('d M Y H:i') ?> · Total: <?= count($rows) ?> data</p>
  </div>
  <div class="logo"><?php if ($rwFile): ?><img src="<?= url('assets/img/'.$rwFile) ?>" alt="Logo RW"><?php endif; ?></div>
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
