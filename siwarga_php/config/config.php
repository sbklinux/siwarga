<?php
/**
 * Konfigurasi global aplikasi SIWARGA
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'SIWARGA');
define('APP_FULL', 'Sistem Informasi Warga Terpadu');
define('APP_VERSION', '1.0.0');

// BASE_URL otomatis (mendukung folder /siwarga_php/ di htdocs)
$proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host  = $_SERVER['HTTP_HOST'] ?? 'localhost';
$script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
// turun ke root project (jika file di /modules/xxx/index.php => naik 2 level)
$rootPath = '/' . trim(explode('/modules', $script)[0], '/');
$rootPath = rtrim($rootPath, '/');
define('BASE_URL', $proto . '://' . $host . $rootPath);

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
