<?php 
session_start();
include 'koneksi.php';

// Menangkap data dari form login
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = md5($_POST['password']); 

// Query menyesuaikan tabel ms_user kamu
$query = mysqli_query($koneksi, "SELECT * FROM ms_user WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);
    
    // Set Session berdasarkan nama kolom di database kamu
    $_SESSION['id_user']  = $data['id_user'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['nama']     = $data['nama_lengkap'];
    $_SESSION['level']    = $data['level'];
    $_SESSION['status']   = "login";
    
    header("location:index.php");
} else {
    // Jika salah, balik ke login.php
    header("location:login.php?pesan=gagal");
}
?>