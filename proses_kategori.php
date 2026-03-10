<?php
include "koneksi.php";

// 1. LOGIKA UNTUK UPDATE (SIMPAN PERUBAHAN)
if(isset($_POST['update'])){
    $id     = $_POST['id_kategori'];
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    $jenis  = $_POST['jenis_aset'];
    $ket    = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // Sesuaikan nama kolom dengan struktur database kamu
    $sql_update = "UPDATE ms_kategori_aset SET 
                   nama_kategori = '$nama', 
                   jenis_aset = '$jenis', 
                   keterangan = '$ket' 
                   WHERE id_kategori = '$id'";
    
    $query = mysqli_query($koneksi, $sql_update);

    if($query){
        echo "<script>alert('Perubahan berhasil disimpan!'); window.location='kategori_aset.php';</script>";
    } else {
        // Jika error, tampilkan pesan errornya agar tidak kosong
        die("Error update: " . mysqli_error($koneksi));
    }
}

// 2. LOGIKA UNTUK SIMPAN BARU
if(isset($_POST['save'])){
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_kategori']);
    $jenis  = $_POST['jenis_aset'];
    $ket    = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    $sql_save = "INSERT INTO ms_kategori_aset (nama_kategori, jenis_aset, keterangan) 
                 VALUES ('$nama', '$jenis', '$ket')";
    
    $query = mysqli_query($koneksi, $sql_save);

    if($query){
        echo "<script>alert('Data kategori berhasil ditambah!'); window.location='kategori_aset.php';</script>";
    } else {
        die("Error simpan: " . mysqli_error($koneksi));
    }
}
?>