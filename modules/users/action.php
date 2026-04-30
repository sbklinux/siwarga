<?php
require_once __DIR__.'/../../includes/auth.php';
require_role(['super_admin']);
$act=$_GET['act']??$_POST['act']??'';
try{
  if($act==='delete'){
    $id=(int)$_GET['id']; if($id===(int)$_SESSION['user_id']){set_flash('error','Tidak bisa menghapus akun sendiri.');redirect('modules/users/index.php');}
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]); set_flash('success','User dihapus.'); redirect('modules/users/index.php');
  }
  csrf_check();
  if($act==='add'){
    $pwd=password_hash($_POST['password'],PASSWORD_BCRYPT);
    $stmt=$pdo->prepare("INSERT INTO users (username,password,nama,email,role,rt,rw,aktif) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->execute([$_POST['username'],$pwd,$_POST['nama'],$_POST['email']??null,$_POST['role'],$_POST['rt']??null,$_POST['rw']??null,(int)$_POST['aktif']]);
    set_flash('success','User ditambahkan.');
  } elseif($act==='edit'){
    if(!empty($_POST['password'])){
      $pwd=password_hash($_POST['password'],PASSWORD_BCRYPT);
      $stmt=$pdo->prepare("UPDATE users SET username=?,password=?,nama=?,email=?,role=?,rt=?,rw=?,aktif=? WHERE id=?");
      $stmt->execute([$_POST['username'],$pwd,$_POST['nama'],$_POST['email']??null,$_POST['role'],$_POST['rt']??null,$_POST['rw']??null,(int)$_POST['aktif'],(int)$_POST['id']]);
    } else {
      $stmt=$pdo->prepare("UPDATE users SET username=?,nama=?,email=?,role=?,rt=?,rw=?,aktif=? WHERE id=?");
      $stmt->execute([$_POST['username'],$_POST['nama'],$_POST['email']??null,$_POST['role'],$_POST['rt']??null,$_POST['rw']??null,(int)$_POST['aktif'],(int)$_POST['id']]);
    }
    set_flash('success','User diperbarui.');
  }
}catch(Exception $e){set_flash('error',$e->getMessage());}
redirect('modules/users/index.php');
