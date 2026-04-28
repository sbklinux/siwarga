<?php
/**
 * Helper umum SIWARGA
 */

function e($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }

function url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirect($path) {
    header('Location: ' . url($path));
    exit;
}

function flash($key, $msg = null) {
    if ($msg === null) {
        $v = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $v;
    }
    $_SESSION['flash'][$key] = $msg;
}

function set_flash($type, $msg) { flash($type, $msg); }

function show_flash() {
    foreach (['success','error','warning','info'] as $t) {
        $msg = flash($t);
        if ($msg) {
            $cls = ['success'=>'success','error'=>'danger','warning'=>'warning','info'=>'info'][$t];
            echo '<div class="alert alert-'.$cls.' alert-dismissible fade show" role="alert">'
                . e($msg)
                . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
    }
}

function rupiah($n) {
    return 'Rp ' . number_format((float)$n, 0, ',', '.');
}

function tanggal_id($d) {
    if (!$d) return '-';
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $t = strtotime($d);
    return date('d', $t) . ' ' . $bulan[(int)date('m', $t)] . ' ' . date('Y', $t);
}

function audit_log($pdo, $userId, $aksi, $modul, $detail = '') {
    try {
        $stmt = $pdo->prepare("INSERT INTO audit_log (user_id, aksi, modul, detail, ip_address) VALUES (?,?,?,?,?)");
        $stmt->execute([$userId, $aksi, $modul, $detail, $_SERVER['REMOTE_ADDR'] ?? '']);
    } catch (Exception $e) { /* skip */ }
}

function role_label($r) {
    return [
        'super_admin'  => 'Super Admin',
        'admin_rtrw'   => 'Admin RT/RW',
        'ketua_rtrw'   => 'Ketua RT/RW',
        'warga'        => 'Warga',
    ][$r] ?? $r;
}

function badge_status($status) {
    $map = [
        'pending'=>'warning','verifikasi'=>'info','approved'=>'success','ditolak'=>'danger',
        'baru'=>'secondary','proses'=>'warning','selesai'=>'success','aktif'=>'success',
    ];
    $cls = $map[$status] ?? 'secondary';
    return '<span class="badge bg-'.$cls.'">'.e(ucfirst($status)).'</span>';
}

function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="_csrf" value="'.csrf_token().'">';
}

function csrf_check() {
    if (($_POST['_csrf'] ?? '') !== ($_SESSION['csrf'] ?? '')) {
        die('CSRF token tidak valid.');
    }
}
