<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['super_admin','admin_rtrw']);
$act=$_GET['act']??$_POST['act']??'';
try{
  if($act==='delete'){$pdo->prepare("DELETE FROM iuran WHERE id=?")->execute([(int)$_GET['id']]);set_flash('success','Dihapus.');redirect('modules/iuran/index.php');}
  csrf_check();
  $f=['no_kk','nama_kk','jenis_iuran','bulan','nominal','tanggal_bayar','keterangan'];
  $v=array_map(fn($k)=>$_POST[$k]??null,$f);if($v[5]==='')$v[5]=null;
  if($act==='add'){$pdo->prepare("INSERT INTO iuran (".implode(',',$f).") VALUES (".rtrim(str_repeat('?,',count($f)),',').")")->execute($v);set_flash('success','Iuran tercatat.');}
  elseif($act==='edit'){$v[]=(int)$_POST['id'];$pdo->prepare("UPDATE iuran SET ".implode('=?,',$f)."=? WHERE id=?")->execute($v);set_flash('success','Diperbarui.');}
}catch(Exception $e){set_flash('error',$e->getMessage());}
redirect('modules/iuran/index.php');
