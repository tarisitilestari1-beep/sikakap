<?php
include "koneksi.php";

// 1. PROSES TAMBAH ASET (DARI MODAL)
if (isset($_POST['simpan_aset'])) {
    $id_unit_setting = $_POST['id_unit_setting'];
    $luas_m2         = $_POST['luas_m2'];
    $harga_sewa      = $_POST['harga_sewa'];
    $kondisi         = $_POST['kondisi'];
    $status_aset     = $_POST['status_aset'];
    $keterangan      = $_POST['keterangan'];

    // Proses Upload Foto
    $foto_name = $_FILES['foto_aset']['name'];
    $tmp_name  = $_FILES['foto_aset']['tmp_name'];
    
    if ($foto_name != "") {
        $ekstensi = pathinfo($foto_name, PATHINFO_EXTENSION);
        $foto_baru = "aset_" . time() . "." . $ekstensi;
        move_uploaded_file($tmp_name, "uploads/" . $foto_baru);
    } else {
        $foto_baru = NULL;
    }

    $query = mysqli_query($koneksi, "INSERT INTO master_aset (id_unit_setting, luas_m2, harga_sewa, kondisi, status_aset, foto_aset, keterangan) 
              VALUES ('$id_unit_setting', '$luas_m2', '$harga_sewa', '$kondisi', '$status_aset', '$foto_baru', '$keterangan')");

    if ($query) {
        echo "<script>alert('Data Berhasil Disimpan!'); window.location='master_aset.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}

// 2. PROSES UPDATE DATA ASET (DARI HALAMAN EDIT) --> INI YANG TADI KURANG
if (isset($_POST['update_aset'])) {
    $id_aset         = $_POST['id_aset'];
    $id_unit_setting = $_POST['id_unit_setting'];
    $luas_m2         = $_POST['luas_m2'];
    $harga_sewa      = $_POST['harga_sewa'];
    $kondisi         = $_POST['kondisi'];
    $status_aset     = $_POST['status_aset'];
    $keterangan      = $_POST['keterangan'];

    $foto_name = $_FILES['foto_aset']['name'];
    $tmp_name  = $_FILES['foto_aset']['tmp_name'];

    // Jika user upload foto baru
    if ($foto_name != "") {
        // Hapus foto lama agar tidak nyampah di folder
        $cek = mysqli_query($koneksi, "SELECT foto_aset FROM master_aset WHERE id_aset='$id_aset'");
        $data = mysqli_fetch_array($cek);
        if ($data['foto_aset'] != "" && file_exists("uploads/" . $data['foto_aset'])) { 
            unlink("uploads/" . $data['foto_aset']); 
        }

        $ekstensi = pathinfo($foto_name, PATHINFO_EXTENSION);
        $foto_baru = "aset_" . time() . "." . $ekstensi;
        move_uploaded_file($tmp_name, "uploads/" . $foto_baru);

        $sql = "UPDATE master_aset SET id_unit_setting='$id_unit_setting', luas_m2='$luas_m2', harga_sewa='$harga_sewa', kondisi='$kondisi', status_aset='$status_aset', foto_aset='$foto_baru', keterangan='$keterangan' WHERE id_aset='$id_aset'";
    } else {
        // Update data saja tanpa ganti foto
        $sql = "UPDATE master_aset SET id_unit_setting='$id_unit_setting', luas_m2='$luas_m2', harga_sewa='$harga_sewa', kondisi='$kondisi', status_aset='$status_aset', keterangan='$keterangan' WHERE id_aset='$id_aset'";
    }

    $query = mysqli_query($koneksi, $sql);
    if ($query) {
        echo "<script>alert('Data Berhasil Diperbarui!'); window.location='master_aset.php';</script>";
    } else {
        echo "Gagal Update: " . mysqli_error($koneksi);
    }
}

// 3. PROSES UPDATE KOORDINAT GIS
if (isset($_POST['update_gis'])) {
    $id_aset   = $_POST['id_aset'];
    $koordinat = $_POST['koordinat_polygon'];

    $query = mysqli_query($koneksi, "UPDATE master_aset SET koordinat_polygon = '$koordinat' WHERE id_aset = '$id_aset'");

    if ($query) {
        echo "<script>alert('Lokasi Berhasil Di-plot!'); window.location='master_aset.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}

// 4. PROSES HAPUS ASET
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    $cek = mysqli_query($koneksi, "SELECT foto_aset FROM master_aset WHERE id_aset='$id'");
    $data = mysqli_fetch_array($cek);
    
    if ($data['foto_aset'] != "" && file_exists("uploads/" . $data['foto_aset'])) {
        unlink("uploads/" . $data['foto_aset']);
    }

    $query = mysqli_query($koneksi, "DELETE FROM master_aset WHERE id_aset = '$id'");
    
    if ($query) {
        echo "<script>alert('Data Aset Berhasil Dihapus!'); window.location='master_aset.php';</script>";
    } else {
        echo "Gagal menghapus: " . mysqli_error($koneksi);
    }
}

// Cek jika folder uploads belum ada, maka buat otomatis
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}
?>