<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

// Stats
$totalKK     = (int)$pdo->query("SELECT COUNT(*) FROM kartu_keluarga")->fetchColumn();
$totalWarga  = (int)$pdo->query("SELECT COUNT(*) FROM warga WHERE status_aktif=1")->fetchColumn();
$totalL      = (int)$pdo->query("SELECT COUNT(*) FROM warga WHERE jenis_kelamin='L' AND status_aktif=1")->fetchColumn();
$totalP      = (int)$pdo->query("SELECT COUNT(*) FROM warga WHERE jenis_kelamin='P' AND status_aktif=1")->fetchColumn();
$pendatang   = (int)$pdo->query("SELECT COUNT(*) FROM pendatang WHERE status='aktif'")->fetchColumn();
$pindah      = (int)$pdo->query("SELECT COUNT(*) FROM pindah")->fetchColumn();
$kelahiran   = (int)$pdo->query("SELECT COUNT(*) FROM kelahiran")->fetchColumn();
$kematian    = (int)$pdo->query("SELECT COUNT(*) FROM kematian")->fetchColumn();
$suratPending = (int)$pdo->query("SELECT COUNT(*) FROM pengajuan_surat WHERE status IN ('pending','verifikasi')")->fetchColumn();
$tamuHariIni = (int)$pdo->query("SELECT COUNT(*) FROM buku_tamu WHERE DATE(jam_masuk)=CURDATE()")->fetchColumn();

// Iuran bulan ini
$bulanIni = date('Y-m');
$stmt = $pdo->prepare("SELECT COALESCE(SUM(nominal),0) FROM iuran WHERE bulan=?");
$stmt->execute([$bulanIni]);
$iuranBulanIni = (float)$stmt->fetchColumn();

// Chart: usia
$ageGroups = ['0-5','6-12','13-17','18-25','26-40','41-60','60+'];
$ageData = [0,0,0,0,0,0,0];
foreach ($pdo->query("SELECT TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) AS umur FROM warga WHERE status_aktif=1 AND tanggal_lahir IS NOT NULL")->fetchAll() as $r) {
    $u = (int)$r['umur'];
    if ($u<=5) $ageData[0]++;
    elseif ($u<=12) $ageData[1]++;
    elseif ($u<=17) $ageData[2]++;
    elseif ($u<=25) $ageData[3]++;
    elseif ($u<=40) $ageData[4]++;
    elseif ($u<=60) $ageData[5]++;
    else $ageData[6]++;
}

// Chart: pekerjaan top 6
$pekerjaan = $pdo->query("SELECT pekerjaan, COUNT(*) c FROM warga WHERE status_aktif=1 AND pekerjaan IS NOT NULL AND pekerjaan<>'' GROUP BY pekerjaan ORDER BY c DESC LIMIT 6")->fetchAll();

// Iuran 6 bulan
$iuranTrend = $pdo->query("SELECT bulan, SUM(nominal) total FROM iuran WHERE bulan >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 5 MONTH),'%Y-%m') GROUP BY bulan ORDER BY bulan")->fetchAll();

// Recent
$recentSurat = $pdo->query("SELECT * FROM pengajuan_surat ORDER BY tanggal_pengajuan DESC LIMIT 5")->fetchAll();
$recentTamu  = $pdo->query("SELECT * FROM buku_tamu ORDER BY jam_masuk DESC LIMIT 5")->fetchAll();
?>

<div class="row g-3">
  <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="icon bg-c1"><i class="fa-solid fa-users"></i></div><div><div class="label">Total Warga</div><div class="value"><?= $totalWarga ?></div></div></div></div>
  <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="icon bg-c2"><i class="fa-solid fa-id-card"></i></div><div><div class="label">Total KK</div><div class="value"><?= $totalKK ?></div></div></div></div>
  <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="icon bg-c5"><i class="fa-solid fa-mars"></i></div><div><div class="label">Laki-laki / Perempuan</div><div class="value"><?= $totalL ?> / <?= $totalP ?></div></div></div></div>
  <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="icon bg-c3"><i class="fa-solid fa-file-lines"></i></div><div><div class="label">Surat Pending</div><div class="value"><?= $suratPending ?></div></div></div></div>

  <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="icon bg-c6"><i class="fa-solid fa-person-walking-arrow-right"></i></div><div><div class="label">Pendatang Aktif</div><div class="value"><?= $pendatang ?></div></div></div></div>
  <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="icon bg-c4"><i class="fa-solid fa-truck-moving"></i></div><div><div class="label">Pindah / Keluar</div><div class="value"><?= $pindah ?></div></div></div></div>
  <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="icon bg-c2"><i class="fa-solid fa-baby"></i></div><div><div class="label">Kelahiran</div><div class="value"><?= $kelahiran ?></div></div></div></div>
  <div class="col-md-6 col-lg-3"><div class="stat-card"><div class="icon bg-c1"><i class="fa-solid fa-money-bill-wave"></i></div><div><div class="label">Iuran <?= date('M Y') ?></div><div class="value" style="font-size:18px"><?= rupiah($iuranBulanIni) ?></div></div></div></div>
</div>

<div class="row g-3 mt-1">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header"><i class="fa-solid fa-chart-column me-1"></i> Distribusi Usia Warga</div>
      <div class="card-body"><canvas id="chartUsia" height="120"></canvas></div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card">
      <div class="card-header"><i class="fa-solid fa-briefcase me-1"></i> Top 6 Pekerjaan</div>
      <div class="card-body"><canvas id="chartPekerjaan" height="160"></canvas></div>
    </div>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header"><i class="fa-solid fa-coins me-1"></i> Trend Iuran 6 Bulan Terakhir</div>
      <div class="card-body"><canvas id="chartIuran" height="120"></canvas></div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card h-100">
      <div class="card-header"><i class="fa-solid fa-file-lines me-1"></i> Pengajuan Surat Terbaru</div>
      <ul class="list-group list-group-flush">
        <?php foreach ($recentSurat as $s): ?>
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div>
              <div class="fw-semibold"><?= e($s['nama']) ?></div>
              <small class="text-muted"><?= e($s['jenis_surat']) ?> · <?= tanggal_id($s['tanggal_pengajuan']) ?></small>
            </div>
            <?= badge_status($s['status']) ?>
          </li>
        <?php endforeach; if (!$recentSurat): ?>
          <li class="list-group-item text-center text-muted">Belum ada pengajuan</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-12">
    <div class="card">
      <div class="card-header"><i class="fa-solid fa-user-clock me-1"></i> Tamu Hari Ini (<?= $tamuHariIni ?>)</div>
      <div class="table-responsive">
        <table class="table mb-0">
          <thead><tr><th>Nama</th><th>Asal</th><th>Tujuan</th><th>Dikunjungi</th><th>Masuk</th><th>Keluar</th></tr></thead>
          <tbody>
            <?php foreach ($recentTamu as $t): ?>
              <tr>
                <td><?= e($t['nama_tamu']) ?></td>
                <td><?= e($t['asal']) ?></td>
                <td><?= e($t['tujuan']) ?></td>
                <td><?= e($t['nama_dikunjungi']) ?></td>
                <td><?= e($t['jam_masuk']) ?></td>
                <td><?= $t['jam_keluar'] ? e($t['jam_keluar']) : '<span class="text-warning">Masih di dalam</span>' ?></td>
              </tr>
            <?php endforeach; if (!$recentTamu): ?>
              <tr><td colspan="6" class="text-center text-muted">Belum ada tamu</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
const ageData = <?= json_encode($ageData) ?>;
new Chart(document.getElementById('chartUsia'), {
  type:'bar',
  data:{ labels: <?= json_encode($ageGroups) ?>, datasets:[{label:'Jumlah', data:ageData, backgroundColor:'#0f4c81', borderRadius:6}] },
  options:{ plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true,ticks:{precision:0}}} }
});
new Chart(document.getElementById('chartPekerjaan'), {
  type:'doughnut',
  data:{ labels: <?= json_encode(array_column($pekerjaan,'pekerjaan')) ?>,
         datasets:[{ data: <?= json_encode(array_map('intval', array_column($pekerjaan,'c'))) ?>,
            backgroundColor:['#0f4c81','#16a34a','#f59e0b','#dc2626','#7c3aed','#06b6d4']}]},
  options:{ plugins:{legend:{position:'bottom'}} }
});
new Chart(document.getElementById('chartIuran'), {
  type:'line',
  data:{ labels: <?= json_encode(array_column($iuranTrend,'bulan')) ?>,
         datasets:[{label:'Total Iuran (Rp)', data: <?= json_encode(array_map('floatval', array_column($iuranTrend,'total'))) ?>, borderColor:'#0f4c81', backgroundColor:'rgba(15,76,129,.15)', fill:true, tension:.3 }]},
  options:{ plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
