<?php
require_once __DIR__.'/../../includes/auth.php';
require_login();
csrf_check();
$act=$_POST['act']??'';
$uid=(int)$_SESSION['user_id'];

function safe_upload($field, $subdir) {
  if (empty($_FILES[$field]['name']) || $_FILES[$field]['error']!==UPLOAD_ERR_OK) return null;
  $allowed=['jpg','jpeg','png','webp','pdf'];
  $ext=strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
  if (!in_array($ext,$allowed,true)) return null;
  if ($_FILES[$field]['size'] > 5*1024*1024) return null;
  $dir = __DIR__ . '/../../uploads/' . $subdir;
  if (!is_dir($dir)) @mkdir($dir, 0775, true);
  $name = uniqid($subdir.'_').'.'.$ext;
  if (move_uploaded_file($_FILES[$field]['tmp_name'], $dir.'/'.$name)) return $name;
  return null;
}

try {
  if ($act==='profil') {
    $stmt=$pdo->prepare("UPDATE users SET nama=?, email=? WHERE id=?");
    $stmt->execute([$_POST['nama'], $_POST['email']??null, $uid]);
    $_SESSION['user']['nama']=$_POST['nama']; $_SESSION['user']['email']=$_POST['email']??null;
    audit_log($pdo,$uid,'update','profil','self');
    set_flash('success','Profil berhasil diperbarui.');
  } elseif ($act==='password') {
    $u=$pdo->prepare("SELECT password FROM users WHERE id=?"); $u->execute([$uid]); $row=$u->fetch();
    if (!password_verify($_POST['old'], $row['password'])) { set_flash('error','Password lama salah.'); redirect('modules/profil/index.php'); }
    if ($_POST['new']!==$_POST['confirm']) { set_flash('error','Konfirmasi password tidak cocok.'); redirect('modules/profil/index.php'); }
    if (strlen($_POST['new'])<6) { set_flash('error','Password baru minimal 6 karakter.'); redirect('modules/profil/index.php'); }
    $pwd=password_hash($_POST['new'],PASSWORD_BCRYPT);
    $pdo->prepare("UPDATE users SET password=? WHERE id=?")->execute([$pwd,$uid]);
    audit_log($pdo,$uid,'change_password','profil','self');
    set_flash('success','Password berhasil diubah.');
  } elseif ($act==='warga') {
    $nik = $_SESSION['user']['nik'] ?? '';
    if (!$nik) { set_flash('error','User ini tidak terkait dengan data warga.'); redirect('modules/profil/index.php'); }
    $sets = ['no_hp=?','email=?','pekerjaan=?','status_perkawinan=?','alamat=?'];
    $vals = [$_POST['no_hp']??null, $_POST['email_warga']??null, $_POST['pekerjaan']??null, $_POST['status_perkawinan']??null, $_POST['alamat']??null];
    $ktp = safe_upload('foto_ktp','ktp'); if ($ktp){$sets[]='foto_ktp=?';$vals[]=$ktp;}
    $kk  = safe_upload('foto_kk','kk');  if ($kk){$sets[]='foto_kk=?'; $vals[]=$kk;}
    $vals[]=$nik;
    $pdo->prepare("UPDATE warga SET ".implode(',',$sets)." WHERE nik=?")->execute($vals);
    audit_log($pdo,$uid,'update','warga','self nik='.$nik);
    set_flash('success','Data warga berhasil diperbarui.');
  }
} catch (Exception $e) { set_flash('error',$e->getMessage()); }
redirect('modules/profil/index.php');
