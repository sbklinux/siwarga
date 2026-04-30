<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$act=$_GET['act']??$_POST['act']??'';
try{
  if($act==='delete'){$pdo->prepare("DELETE FROM laporan_keamanan WHERE id=?")->execute([(int)$_GET['id']]);set_flash('success','Dihapus.');redirect('modules/keamanan/index.php');}
  csrf_check();
  $f=['pelapor','jenis_laporan','judul','isi','tanggal_kejadian','status','tindak_lanjut'];
  $v=array_map(fn($k)=>$_POST[$k]??null,$f);if($v[4]==='')$v[4]=null;
  if($act==='add'){$pdo->prepare("INSERT INTO laporan_keamanan (".implode(',',$f).") VALUES (".rtrim(str_repeat('?,',count($f)),',').")")->execute($v);set_flash('success','Laporan dibuat.');}
  elseif($act==='edit'){$v[]=(int)$_POST['id'];$pdo->prepare("UPDATE laporan_keamanan SET ".implode('=?,',$f)."=? WHERE id=?")->execute($v);set_flash('success','Diperbarui.');}
}catch(Exception $e){set_flash('error',$e->getMessage());}
redirect('modules/keamanan/index.php');
