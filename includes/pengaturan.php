<?php
/**
 * Helper pengaturan kop surat
 */

function get_pengaturan($pdo) {
    static $cache = null;
    if ($cache !== null) return $cache;
    try {
        $rows = $pdo->query("SELECT key_name, value FROM pengaturan")->fetchAll();
        $cache = [];
        foreach ($rows as $r) $cache[$r['key_name']] = $r['value'];
        return $cache;
    } catch (Exception $e) {
        // fallback default jika tabel belum ada
        return [
            'nama_kabupaten' => 'PEMERINTAH KABUPATEN',
            'nama_rt_rw' => 'RT / RW',
            'nama_perumahan' => 'PERUMAHAN',
            'alamat_lengkap' => '',
            'nama_kota_ttd' => 'Kota',
            'nama_ketua' => '',
            'jabatan_ketua' => 'Ketua RT/RW',
            'no_telp' => '',
            'email_resmi' => '',
            'ttd_file' => '',
            'kop_footer' => '',
        ];
    }
}

function set_pengaturan($pdo, $key, $value) {
    $stmt = $pdo->prepare("INSERT INTO pengaturan (key_name, value) VALUES (?,?) ON DUPLICATE KEY UPDATE value=?");
    $stmt->execute([$key, $value, $value]);
}
