<?php
require_once __DIR__.'/app/core/Database.php';
$db=Database::getConnection();
$msg='';
$accountExists=(int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn() > 0;
if($_SERVER['REQUEST_METHOD']==='POST' && !$accountExists){
  $nama=trim($_POST['nama']??'');
  $nim=trim($_POST['nim']??'');
  $username=trim($_POST['username']??'');
  $password=$_POST['password']??'';
  if($nama && $nim && $username && strlen($password)>=8){
    try{
      $db->beginTransaction();
      $st=$db->prepare("INSERT INTO pengurus (nama_pengurus,nim,jabatan,status_aktif) VALUES (?,?, 'Akun Bersama Dagri','Aktif')");
      $st->execute([$nama,$nim]);
      $id=(int)$db->lastInsertId();
      $st=$db->prepare("INSERT INTO users (id_pengurus,username,password_hash) VALUES (?,?,?)");
      $st->execute([$id,$username,password_hash($password,PASSWORD_DEFAULT)]);
      $db->commit();
      $accountExists=true;
      $msg='Akun bersama berhasil dibuat. Hapus setup_admin.php sekarang.';
    }catch(Throwable $e){
      if($db->inTransaction()) $db->rollBack();
      $msg='Gagal membuat akun. Pastikan NIM dan username belum digunakan.';
    }
  }else{$msg='Lengkapi seluruh data. Password minimal 8 karakter.';}
}
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Setup Akun Bersama Dagri</title></head>
<body style="font:16px Arial;max-width:540px;margin:50px auto;padding:0 16px;line-height:1.5">
<h2>Buat Satu Akun Bersama Dagri</h2>
<p>Akun ini digunakan bersama oleh Kepala Departemen dan seluruh anggota Dagri HMSI ITK. Semua pengguna yang login memiliki hak akses yang sama.</p>
<?php if($msg): ?><p><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<?php if($accountExists): ?><p><strong>Akun sudah tersedia.</strong> Silakan login melalui aplikasi. Demi keamanan, hapus file <code>setup_admin.php</code>.</p>
<?php else: ?><form method="post">
<p>Nama penanggung jawab akun<br><input name="nama" required style="width:100%;padding:8px"></p>
<p>NIM penanggung jawab akun<br><input name="nim" required style="width:100%;padding:8px"></p>
<p>Username bersama<br><input name="username" required autocomplete="username" style="width:100%;padding:8px"></p>
<p>Password bersama (minimal 8 karakter)<br><input type="password" name="password" required minlength="8" autocomplete="new-password" style="width:100%;padding:8px"></p>
<button type="submit">Buat Akun Bersama</button></form><?php endif; ?></body></html>
