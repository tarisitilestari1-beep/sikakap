<?php
include "koneksi.php";

$id_kontrak = $_GET['id'];
$id_aset    = $_GET['id_aset'];

if (mysqli_query($koneksi, "DELETE FROM tr_kontrak WHERE id_kontrak = '$id_kontrak'")) {
    // Balikkan status aset jadi Tersedia
    mysqli_query($koneksi, "UPDATE ms_aset SET status_ketersediaan = 'Tersedia' WHERE id_aset = '$id_aset'");
    header("Location: kontrak.php?status=hapus_sukses");
}
?>