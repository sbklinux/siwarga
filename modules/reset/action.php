<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role(['super_admin','ketua_rtrw']);
$act = $_GET['act'] ?? '';
$id  = (int)($_GET['id'] ?? 0);

try {
  $stmt = $pdo->prepare("SELECT * FROM password_reset_requests WHERE id=?");
  $stmt->execute([$id]);
  $r = $stmt->fetch();
  if (!$r && $act !== 'delete') { set_flash('error','Record tidak ditemukan.'); redirect('modules/reset/index.php'); }

  if ($act === 'approve') {
    // Cek user masih ada
    $u = $pdo->prepare("SELECT id FROM users WHERE username=?"); $u->execute([$r['username']]); $user=$u->fetch();
    if (!$user) { set_flash('error','User "'.$r['username'].'" tidak ditemukan di sistem.'); redirect('modules/reset/index.php'); }
    // Generate random temp password
    $newPlain = strtolower(substr(md5(random_bytes(8)),0,8));
    $newHash = password_hash($newPlain, PASSWORD_BCRYPT);
    $pdo->prepare("UPDATE users SET password=? WHERE id=?")->execute([$newHash, $user['id']]);
    $pdo->prepare("UPDATE password_reset_requests SET status='approved', new_password_plain=?, processed_by=?, processed_at=NOW() WHERE id=?")
        ->execute([$newPlain, $_SESSION['user_id'], $id]);
    audit_log($pdo, $_SESSION['user_id'], 'reset_approve', 'auth', 'user='.$r['username']);
    set_flash('success','Password user "'.$r['username'].'" berhasil direset. Password baru: '.$newPlain.' — catat & sampaikan ke user.');
  } elseif ($act === 'tolak') {
    $pdo->prepare("UPDATE password_reset_requests SET status='ditolak', processed_by=?, processed_at=NOW() WHERE id=?")
        ->execute([$_SESSION['user_id'], $id]);
    audit_log($pdo, $_SESSION['user_id'], 'reset_reject', 'auth', 'id='.$id);
    set_flash('warning','Permintaan reset password ditolak.');
  } elseif ($act === 'delete') {
    $pdo->prepare("DELETE FROM password_reset_requests WHERE id=?")->execute([$id]);
    set_flash('success','Record dihapus.');
  }
} catch (Exception $e) { set_flash('error',$e->getMessage()); }
redirect('modules/reset/index.php');
