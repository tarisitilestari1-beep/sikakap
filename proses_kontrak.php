<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $id_aset         = $_POST['id_aset'];
    $id_pengguna     = $_POST['id_pengguna_jasa'];
    $no_surat        = mysqli_real_escape_string($koneksi, $_POST['no_surat_kontrak']);
    $tgl_mulai       = $_POST['tgl_mulai'];
    $tgl_selesai     = $_POST['tgl_selesai'];
    $catatan         = mysqli_real_escape_string($koneksi, $_POST['catatan_tambahan']);
    $status_sewa     = "Aktif";

    $nama_file_pdf = NULL;

    // Cek apakah user memilih file
    if (!empty($_FILES['file_pdf_kontrak']['name'])) {
        $folder_tujuan = "uploads/";
        
        // CEK 1: Apakah folder uploads benar-benar ada?
        if (!is_dir($folder_tujuan)) {
            mkdir($folder_tujuan, 0777, true); // Buat otomatis jika belum ada
        }

        $nama_asal      = $_FILES['file_pdf_kontrak']['name'];
        $x              = explode('.', $nama_asal);
        $ekstensi       = strtolower(end($x));
        $file_tmp       = $_FILES['file_pdf_kontrak']['tmp_name'];

        // Buat nama unik
        $nama_file_pdf = "KONTRAK_" . date('Ymd_His') . ".pdf";
        $path_lengkap  = $folder_tujuan . $nama_file_pdf;

        // Proses pindah file dari RAM ke Folder Uploads
        if (!move_uploaded_file($file_tmp, $path_lengkap)) {
            echo "Gagal mengunggah file ke folder. Cek izin folder uploads.";
            exit;
        }
    }

    // Simpan ke Database
    $query_simpan = "INSERT INTO tr_kontrak (id_aset, id_pengguna_jasa, no_surat_kontrak, tgl_mulai, tgl_selesai, file_pdf_kontrak, status_sewa, catatan_tambahan) 
                     VALUES ('$id_aset', '$id_pengguna', '$no_surat', '$tgl_mulai', '$tgl_selesai', '$nama_file_pdf', '$status_sewa', '$catatan')";

    if (mysqli_query($koneksi, $query_simpan)) {
        // Update status aset
        mysqli_query($koneksi, "UPDATE ms_aset SET status_ketersediaan = 'Disewa' WHERE id_aset = '$id_aset'");
        header("Location: kontrak.php?status=sukses");
        exit();
    } else {
        echo "Error Database: " . mysqli_error($koneksi);
    }
}