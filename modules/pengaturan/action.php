<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/pengaturan.php';
require_role(['super_admin']);
$act = $_GET['act'] ?? $_POST['act'] ?? '';

try {
    if ($act === 'hapus_ttd') {
        $p = get_pengaturan($pdo);
        if (!empty($p['ttd_file'])) {
            @unlink(__DIR__ . '/../../uploads/ttd/' . $p['ttd_file']);
            set_pengaturan($pdo, 'ttd_file', '');
        }
        set_flash('success', 'Tanda tangan dihapus.');
        redirect('modules/pengaturan/index.php');
    }

    csrf_check();
    $fields = ['nama_kabupaten','nama_rt_rw','nama_perumahan','nama_kota_ttd','alamat_lengkap','no_telp','email_resmi','nama_ketua','jabatan_ketua','kop_footer'];
    foreach ($fields as $f) {
        if (isset($_POST[$f])) set_pengaturan($pdo, $f, trim($_POST[$f]));
    }

    // Upload TTD
    if (!empty($_FILES['ttd_file']['name']) && $_FILES['ttd_file']['error']===UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['ttd_file']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['png','jpg','jpeg'], true)) {
            set_flash('error','Format TTD harus PNG atau JPG.'); redirect('modules/pengaturan/index.php');
        }
        if ($_FILES['ttd_file']['size'] > 2*1024*1024) {
            set_flash('error','Ukuran TTD maksimal 2MB.'); redirect('modules/pengaturan/index.php');
        }
        $dir = __DIR__ . '/../../uploads/ttd';
        if (!is_dir($dir)) @mkdir($dir, 0775, true);
        $name = 'ttd_'.time().'.'.$ext;
        if (move_uploaded_file($_FILES['ttd_file']['tmp_name'], $dir.'/'.$name)) {
            // Hapus TTD lama jika ada
            $p = get_pengaturan($pdo);
            if (!empty($p['ttd_file'])) @unlink($dir.'/'.$p['ttd_file']);
            set_pengaturan($pdo, 'ttd_file', $name);
        }
    }

    audit_log($pdo, $_SESSION['user_id'], 'update', 'pengaturan', 'kop surat');
    set_flash('success', 'Pengaturan berhasil disimpan. Kop surat otomatis ter-update.');
} catch (Exception $e) {
    set_flash('error', $e->getMessage());
}
redirect('modules/pengaturan/index.php');
