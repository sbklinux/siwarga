<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role(['super_admin','admin_rtrw']);
$act = $_GET['act'] ?? $_POST['act'] ?? '';

try {
  if ($act === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM kartu_keluarga WHERE id=?");
    $stmt->execute([(int)$_GET['id']]);
    audit_log($pdo, $_SESSION['user_id'], 'delete', 'kk', 'id='.$_GET['id']);
    set_flash('success','KK berhasil dihapus.');
    redirect('modules/kk/index.php');
  }
  csrf_check();
  $data = [
    $_POST['no_kk'], $_POST['kepala_keluarga'], $_POST['alamat'] ?? null,
    $_POST['rt'] ?? null, $_POST['rw'] ?? null,
    $_POST['kelurahan'] ?? null, $_POST['kecamatan'] ?? null,
    $_POST['kota'] ?? null, $_POST['provinsi'] ?? null, $_POST['status_rumah'] ?? 'milik',
  ];
  if ($act === 'add') {
    $stmt = $pdo->prepare("INSERT INTO kartu_keluarga (no_kk,kepala_keluarga,alamat,rt,rw,kelurahan,kecamatan,kota,provinsi,status_rumah) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute($data);
    audit_log($pdo, $_SESSION['user_id'], 'create', 'kk', $_POST['no_kk']);
    set_flash('success','KK berhasil ditambahkan.');
  } elseif ($act === 'edit') {
    $data[] = (int)$_POST['id'];
    $stmt = $pdo->prepare("UPDATE kartu_keluarga SET no_kk=?,kepala_keluarga=?,alamat=?,rt=?,rw=?,kelurahan=?,kecamatan=?,kota=?,provinsi=?,status_rumah=? WHERE id=?");
    $stmt->execute($data);
    audit_log($pdo, $_SESSION['user_id'], 'update', 'kk', 'id='.$_POST['id']);
    set_flash('success','KK berhasil diperbarui.');
  }
} catch (Exception $e) {
  set_flash('error','Error: '.$e->getMessage());
}
redirect('modules/kk/index.php');
