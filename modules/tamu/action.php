<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['super_admin','admin_rtrw','ketua_rtrw']);
$act=$_GET['act']??$_POST['act']??'';
try{
  if($act==='delete'){$pdo->prepare("DELETE FROM buku_tamu WHERE id=?")->execute([(int)$_GET['id']]);set_flash('success','Dihapus.');redirect('modules/tamu/index.php');}
  if($act==='keluar'){$pdo->prepare("UPDATE buku_tamu SET jam_keluar=NOW() WHERE id=?")->execute([(int)$_GET['id']]);set_flash('success','Tamu check-out.');redirect('modules/tamu/index.php');}
  csrf_check();
  $f=['nama_tamu','no_identitas','asal','tujuan','nama_dikunjungi','jam_masuk','keterangan'];
  $v=array_map(fn($k)=>$_POST[$k]??null,$f);if($v[5]) $v[5]=str_replace('T',' ',$v[5]);
  if($act==='add'){$pdo->prepare("INSERT INTO buku_tamu (".implode(',',$f).") VALUES (".rtrim(str_repeat('?,',count($f)),',').")")->execute($v);set_flash('success','Tamu tercatat.');}
  elseif($act==='edit'){$v[]=(int)$_POST['id'];$pdo->prepare("UPDATE buku_tamu SET ".implode('=?,',$f)."=? WHERE id=?")->execute($v);set_flash('success','Diperbarui.');}
}catch(Exception $e){set_flash('error',$e->getMessage());}
redirect('modules/tamu/index.php');
