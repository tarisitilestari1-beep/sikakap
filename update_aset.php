<?php
include "koneksi.php";

if (isset($_POST['update'])) {
    // Ambil data dari form modal edit
    $id          = $_POST['id_aset'];
    $notasi      = mysqli_real_escape_string($koneksi, $_POST['kode_notasi']);
    $nama        = mysqli_real_escape_string($koneksi, $_POST['nama_aset']);
    $kondisi     = mysqli_real_escape_string($koneksi, $_POST['kondisi']);
    $status      = mysqli_real_escape_string($koneksi, $_POST['status_ketersediaan']); // Kolom baru
    $earth       = mysqli_real_escape_string($koneksi, $_POST['link_google_earth']);
    $keterangan  = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Query UPDATE
    $sql = "UPDATE ms_aset SET 
            kode_notasi = '$notasi', 
            nama_aset = '$nama', 
            kondisi = '$kondisi', 
            status_ketersediaan = '$status', 
            link_google_earth = '$earth', 
            keterangan = '$keterangan' 
            WHERE id_aset = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        // Jika berhasil, kembali ke master_aset.php dengan notif sukses
        header("Location: master_aset.php?status=update_sukses");
        exit();
    } else {
        // Jika gagal, tampilkan error
        die("Gagal Update Database: " . mysqli_error($koneksi));
    }
} else {
    // Jika akses tanpa melalui tombol update
    header("Location: master_aset.php");
    exit();
}
?>