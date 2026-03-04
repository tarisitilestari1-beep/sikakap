<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    // Ambil data dari form modal
    $kode_notasi       = $_POST['kode_notasi'];
    $nama_aset         = $_POST['nama_aset'];
    $jenis_aset        = $_POST['jenis_aset'];
    $luas_m2           = $_POST['luas_m2'];
    $kondisi           = $_POST['kondisi'];
    $link_google_earth = $_POST['link_google_earth'];
    $keterangan        = $_POST['keterangan'];
    $status_ketersediaan        = $_POST['status_ketersediaan'];

    // Query Insert sesuai struktur tabel Anda
    $query = "INSERT INTO ms_aset (kode_notasi, nama_aset, jenis_aset, luas_m2, kondisi, link_google_earth, keterangan, status_ketersediaan) 
              VALUES ('$kode_notasi', '$nama_aset', '$jenis_aset', '$luas_m2', '$kondisi', '$link_google_earth', '$keterangan', '$status_ketersediaan')";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, balik ke halaman master_aset dengan status sukses
        header("Location: master_aset.php?status=tambah_sukses");
    } else {
        // Jika gagal, tampilkan error
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>