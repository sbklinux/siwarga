<?php
require_once __DIR__ . '/../../includes/auth.php';
require_role(['super_admin','admin_rtrw']);
$act = $_GET['act'] ?? $_POST['act'] ?? '';
try {
  if ($act === 'delete') { $pdo->prepare("DELETE FROM pendatang WHERE id=?")->execute([(int)$_GET['id']]); set_flash('success','Dihapus.'); redirect('modules/pendatang/index.php'); }
  csrf_check();
  $f = ['nama','nik','asal_daerah','tujuan_tinggal','lama_tinggal','penjamin','alamat_tinggal','rt','rw','tanggal_datang','status'];
  $vals = array_map(fn($k)=>$_POST[$k] ?? null, $f);
  if ($vals[9]==='') $vals[9]=null;
  if ($act==='add') { $pdo->prepare("INSERT INTO pendatang (".implode(',',$f).") VALUES (".rtrim(str_repeat('?,',count($f)),',').")")->execute($vals); set_flash('success','Pendatang ditambahkan.'); }
  elseif ($act==='edit') { $vals[]=(int)$_POST['id']; $pdo->prepare("UPDATE pendatang SET ".implode('=?,',$f)."=? WHERE id=?")->execute($vals); set_flash('success','Pendatang diperbarui.'); }
} catch (Exception $e) { set_flash('error',$e->getMessage()); }
redirect('modules/pendatang/index.php');
