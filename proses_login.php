<?php
//memulai session untuk login user
session_start();
//memanggil file koneksi ke database
include "koneksi.php";
//menangkap data yang dikirim dari form login.php
$username=$_POST['username'];
$password=$_POST['password'];
//format acak password harus sama dengan proses_register.php
$pengacak="p3ng4c4k";
$password_acak=md5($pengacak.md5($password).$pengacak);
$kirim=$_POST['kirim'];
//menyeleksi data user dengan username dan password acak yang sesuai
$query="SELECT*FROM tb_user WHERE username='$username' and password='$password_acak'";
//menjalankan query ditampung dlm variabel $hasil
$hasil=mysqli_query($conn,$query);
//menangkap data dari hasil perintah query sql
$data=mysqli_fetch_array($hasil);
//menghitung jumlah data yang ditemukan
$cek = mysqli_num_rows($hasil);
//cek apakah username dan password di temukan pada database
if($cek > 0){
//cek jika user login sebagai admin
if($data['level']=="admin"){
// buat session login dan username
$_SESSION['username'] = $username
$_SESSION['level'] = "admin";
//alihkan ke halaman dashboard admin
header("location:hal_admin.php");
//cek jika user login sebagai user
}else if($data['level']=="user"){
//buat session login dan username
$_SESSION['username'] = $username;
$_SESSION['level'] = "user";
//alihkan ke halaman dashboard user
header("location:user.html");
}else{
echo "Anda Bukan Admin dan Bukan User";
//alihkan ke halaman login kembali
//header("location:login.php");
}
}else{
//jika username dan password tdk ditemukan pada database
echo "GAGAL LOGIN!!!, Username dan Password tidak ditemukan";
}
?>