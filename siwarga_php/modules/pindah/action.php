<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['super_admin','admin_rtrw']);
$act=$_GET['act']??$_POST['act']??'';
try{
  if($act==='delete'){$pdo->prepare("DELETE FROM pindah WHERE id=?")->execute([(int)$_GET['id']]);set_flash('success','Dihapus.');redirect('modules/pindah/index.php');}
  csrf_check();
  $f=['nik','nama','tujuan_pindah','tanggal_pindah','alasan'];
  $v=array_map(fn($k)=>$_POST[$k]??null,$f);if($v[3]==='')$v[3]=null;
  if($act==='add'){$pdo->prepare("INSERT INTO pindah (".implode(',',$f).") VALUES (".rtrim(str_repeat('?,',count($f)),',').")")->execute($v);set_flash('success','Ditambahkan.');}
  elseif($act==='edit'){$v[]=(int)$_POST['id'];$pdo->prepare("UPDATE pindah SET ".implode('=?,',$f)."=? WHERE id=?")->execute($v);set_flash('success','Diperbarui.');}
}catch(Exception $e){set_flash('error',$e->getMessage());}
redirect('modules/pindah/index.php');
