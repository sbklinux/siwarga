<?php
/**
 * Konfigurasi koneksi database SIWARGA
 * Edit nilai-nilai di bawah sesuai konfigurasi XAMPP Anda.
 */
$DB_HOST = 'localhost';
$DB_NAME = 'siwarga';
$DB_USER = 'root';
$DB_PASS = '';

try {
    $pdo = new PDO(
        "mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
        $DB_USER,
        $DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:30px;color:#b91c1c;background:#fee2e2;border-radius:8px;margin:40px;">
            <h3>Koneksi Database Gagal</h3>
            <p>'.htmlspecialchars($e->getMessage()).'</p>
            <p>Pastikan MySQL XAMPP aktif dan database <b>siwarga</b> sudah diimpor.</p>
         </div>');
}
