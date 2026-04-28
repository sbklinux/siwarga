<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role(['super_admin','admin_rtrw']);
$act = $_GET['act'] ?? $_POST['act'] ?? '';

function safe_upload_warga($field, $subdir) {
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
  if ($act === 'delete') {
    $stmt = $pdo->prepare("DELETE FROM warga WHERE id=?"); $stmt->execute([(int)$_GET['id']]);
    audit_log($pdo, $_SESSION['user_id'], 'delete', 'warga', 'id='.$_GET['id']);
    set_flash('success','Data warga dihapus.'); redirect('modules/warga/index.php');
  }
  csrf_check();
  $f = ['nik','no_kk','nama','tempat_lahir','tanggal_lahir','jenis_kelamin','agama','pendidikan','pekerjaan','status_perkawinan','status_keluarga','no_hp','email','alamat','rt','rw','status_aktif'];
  $vals = []; foreach ($f as $k) $vals[] = $_POST[$k] ?? null;
  if ($vals[4] === '') $vals[4] = null;

  $ktp = safe_upload_warga('foto_ktp','ktp');
  $kk  = safe_upload_warga('foto_kk','kk');

  if ($act === 'add') {
    $cols = $f; $v = $vals;
    if ($ktp){$cols[]='foto_ktp'; $v[]=$ktp;}
    if ($kk){$cols[]='foto_kk'; $v[]=$kk;}
    $stmt = $pdo->prepare("INSERT INTO warga (".implode(',',$cols).") VALUES (".rtrim(str_repeat('?,',count($cols)),',').")");
    $stmt->execute($v);
    audit_log($pdo, $_SESSION['user_id'], 'create', 'warga', $_POST['nik']);
    set_flash('success','Warga ditambahkan.');
  } elseif ($act === 'edit') {
    $sets = []; $v = [];
    foreach ($f as $i=>$k) { $sets[] = "$k=?"; $v[] = $vals[$i]; }
    if ($ktp){$sets[]='foto_ktp=?'; $v[]=$ktp;}
    if ($kk){$sets[]='foto_kk=?'; $v[]=$kk;}
    $v[] = (int)$_POST['id'];
    $stmt = $pdo->prepare("UPDATE warga SET ".implode(',',$sets)." WHERE id=?");
    $stmt->execute($v);
    audit_log($pdo, $_SESSION['user_id'], 'update', 'warga', 'id='.$_POST['id']);
    set_flash('success','Warga diperbarui.');
  }
} catch (Exception $e) { set_flash('error','Error: '.$e->getMessage()); }
redirect('modules/warga/index.php');
