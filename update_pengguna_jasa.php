<?php
include "koneksi.php";
if (isset($_POST['update'])) {
    $id      = $_POST['id_pengguna_jasa'];
    $nama    = $_POST['nama_pengguna_jasa'];
    $ktp     = $_POST['no_identitas'];
    $hp      = $_POST['no_hp'];
    $alamat  = $_POST['alamat_domisili'];

    $sql = "UPDATE ms_pengguna_jasa SET 
            nama_pengguna_jasa='$nama', 
            no_identitas='$ktp', 
            no_hp='$hp', 
            alamat_domisili='$alamat' 
            WHERE id_pengguna_jasa='$id'";

    if (mysqli_query($koneksi, $sql)) {
        header("Location: pengguna_jasa.php?status=update_sukses");
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>