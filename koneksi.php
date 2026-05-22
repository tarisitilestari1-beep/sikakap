<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_sikakap_v2";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}
?>