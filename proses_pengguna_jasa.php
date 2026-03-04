<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    // Ambil data dari form modal (Tanpa Perusahaan)
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama_pengguna_jasa']);
    $ktp     = mysqli_real_escape_string($koneksi, $_POST['no_identitas']);
    $hp      = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat  = mysqli_real_escape_string($koneksi, $_POST['alamat_domisili']);

    // Query INSERT hanya untuk 4 kolom data
    $sql = "INSERT INTO ms_pengguna_jasa (nama_pengguna_jasa, no_identitas, no_hp, alamat_domisili) 
            VALUES ('$nama', '$ktp', '$hp', '$alamat')";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: pengguna_jasa.php?status=sukses");
        exit();
    } else {
        die("Error Database: " . mysqli_error($koneksi));
    }
} else {
    header("Location: pengguna_jasa.php");
    exit();
}
?>