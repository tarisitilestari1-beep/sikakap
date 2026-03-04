<?php
include "koneksi.php";

// Ambil ID dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query hapus data berdasarkan ID
    // Pastikan nama tabelnya ms_pengguna_jasa
    $query = "DELETE FROM ms_pengguna_jasa WHERE id_pengguna_jasa = '$id'";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, kembali ke halaman utama dengan status hapus_sukses
        header("Location: pengguna_jasa.php?status=hapus_sukses");
        exit();
    } else {
        // Jika gagal karena relasi database atau hal lain
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
} else {
    // Jika tidak ada ID di URL, langsung balik ke halaman utama
    header("Location: pengguna_jasa.php");
    exit();
}
?>