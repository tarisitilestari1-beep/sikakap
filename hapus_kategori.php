<?php
include "koneksi.php";

if(isset($_GET['id'])){
    $id = $_GET['id'];
    
    // Hapus data berdasarkan ID
    $hapus = mysqli_query($koneksi, "DELETE FROM ms_kategori_aset WHERE id_kategori = '$id'");

    if($hapus){
        echo "<script>alert('Data berhasil dihapus!'); window.location='kategori_aset.php';</script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($koneksi);
    }
} else {
    header("location:kategori_aset.php");
}
?>