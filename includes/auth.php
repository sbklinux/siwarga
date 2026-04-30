<?php
require_once __DIR__ . '/../config/config.php';

function is_login() {
    return !empty($_SESSION['user_id']);
}

function user() {
    return $_SESSION['user'] ?? null;
}

function role() {
    return $_SESSION['user']['role'] ?? null;
}

function require_login() {
    if (!is_login()) {
        set_flash('warning', 'Silakan login terlebih dahulu.');
        redirect('login.php');
    }
}

function require_role(array $roles) {
    require_login();
    if (!in_array(role(), $roles, true)) {
        http_response_code(403);
        die('<div style="font-family:sans-serif;padding:40px;color:#991b1b;">
              <h3>403 - Akses Ditolak</h3>
              <p>Anda tidak memiliki hak akses pada halaman ini.</p>
              <a href="'.url('dashboard.php').'">&larr; Kembali ke Dashboard</a>
            </div>');
    }
}

function can($action) {
    $r = role();
    $matrix = [
        'manage_users'    => ['super_admin'],
        'manage_master'   => ['super_admin','admin_rtrw'],
        'approve_surat'   => ['super_admin','ketua_rtrw'],
        'verifikasi_surat'=> ['super_admin','admin_rtrw'],
        'manage_keuangan' => ['super_admin','admin_rtrw'],
        'manage_keamanan' => ['super_admin','admin_rtrw','ketua_rtrw'],
        'view_laporan'    => ['super_admin','admin_rtrw','ketua_rtrw'],
        'ajukan_surat'    => ['super_admin','admin_rtrw','ketua_rtrw','warga'],
    ];
    return in_array($r, $matrix[$action] ?? [], true);
}
