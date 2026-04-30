<?php
require_once __DIR__.'/../../includes/auth.php';
require_login();
$act=$_GET['act']??$_POST['act']??'';
try{
  if($act==='ajukan'){
    csrf_check();
    $no='SK-'.date('Ymd-His').'-'.rand(10,99);
    $stmt=$pdo->prepare("INSERT INTO pengajuan_surat (nomor_surat,nik,nama,jenis_surat,keperluan,keterangan,status,user_id) VALUES (?,?,?,?,?,?,'pending',?)");
    $stmt->execute([$no,$_POST['nik'],$_POST['nama'],$_POST['jenis_surat'],$_POST['keperluan']??null,$_POST['keterangan']??null,$_SESSION['user_id']]);
    audit_log($pdo,$_SESSION['user_id'],'create','surat',$no);
    set_flash('success','Pengajuan terkirim. Tunggu verifikasi admin.');
  } elseif($act==='verif' && can('verifikasi_surat')){
    $pdo->prepare("UPDATE pengajuan_surat SET status='verifikasi' WHERE id=?")->execute([(int)$_GET['id']]);
    set_flash('success','Surat diverifikasi, menunggu persetujuan Ketua RT/RW.');
  } elseif($act==='approve' && can('approve_surat')){
    $pdo->prepare("UPDATE pengajuan_surat SET status='approved', tanggal_approve=NOW() WHERE id=?")->execute([(int)$_GET['id']]);
    set_flash('success','Surat disetujui.');
  } elseif($act==='tolak' && can('approve_surat')){
    $pdo->prepare("UPDATE pengajuan_surat SET status='ditolak' WHERE id=?")->execute([(int)$_GET['id']]);
    set_flash('warning','Surat ditolak.');
  } elseif($act==='delete' && can('manage_master')){
    $pdo->prepare("DELETE FROM pengajuan_surat WHERE id=?")->execute([(int)$_GET['id']]);
    set_flash('success','Dihapus.');
  }
}catch(Exception $e){set_flash('error',$e->getMessage());}
redirect('modules/surat/index.php');
