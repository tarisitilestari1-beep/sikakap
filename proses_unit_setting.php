<?php
include "koneksi.php";

// 1. SIMPAN DATA
if(isset($_POST['simpan'])){
    $id_kategori = $_POST['id_kategori'];
    $nama_unit   = mysqli_real_escape_string($koneksi, $_POST['nama_unit_setting']);

    $query = mysqli_query($koneksi, "INSERT INTO ms_unit_setting (id_kategori, nama_unit_setting) VALUES ('$id_kategori', '$nama_unit')");
    if($query){
        echo "<script>alert('Data berhasil disimpan!'); window.location='setting_unit.php';</script>";
    }
}

// 2. UPDATE DATA
if(isset($_POST['update'])){
    $id_unit     = $_POST['id_unit_setting'];
    $id_kategori = $_POST['id_kategori'];
    $nama_unit   = mysqli_real_escape_string($koneksi, $_POST['nama_unit_setting']);

    $query = mysqli_query($koneksi, "UPDATE ms_unit_setting SET id_kategori='$id_kategori', nama_unit_setting='$nama_unit' WHERE id_unit_setting='$id_unit'");
    if($query){
        echo "<script>alert('Data berhasil diupdate!'); window.location='setting_unit.php';</script>";
    }
}

// 3. HAPUS DATA
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    $query = mysqli_query($koneksi, "DELETE FROM ms_unit_setting WHERE id_unit_setting='$id'");
    if($query){
        header("location:setting_unit.php");
    }
}
?>