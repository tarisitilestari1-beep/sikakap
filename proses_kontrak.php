<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    // 1. PENANGANAN WARNING (Undefined Array Key)
    // Gunakan isset() atau ?? agar tidak error jika input kosong
    $id_unit_setting = $_POST['id_unit_setting'] ?? null;
    $id_pengguna     = $_POST['id_pengguna_jasa'] ?? null;
    $no_surat        = mysqli_real_escape_string($koneksi, $_POST['no_surat_kontrak'] ?? '');
    $tgl_mulai       = $_POST['tgl_mulai'] ?? '';
    $tgl_selesai     = $_POST['tgl_selesai'] ?? '';
    
    // Perbaikan baris 10: Sesuaikan dengan nama name="catatan_tambahan" di form
    $catatan         = mysqli_real_escape_string($koneksi, $_POST['catatan_tambahan'] ?? '');
    $status_sewa     = "Aktif";

    // Validasi Dasar: Jangan biarkan ID Aset kosong
    if (empty($id_unit_setting)) {
        echo "<script>alert('Error: ID Aset tidak ditemukan. Pastikan Anda memilih aset!'); window.history.back();</script>";
        exit;
    }

    $nama_file_pdf = NULL;

    // 2. PROSES UPLOAD FILE
    if (!empty($_FILES['file_pdf_kontrak']['name'])) {
        $folder_tujuan = "uploads/";
        
        if (!is_dir($folder_tujuan)) {
            mkdir($folder_tujuan, 0777, true);
        }

        $nama_asal      = $_FILES['file_pdf_kontrak']['name'];
        $x              = explode('.', $nama_asal);
        $ekstensi       = strtolower(end($x));
        $file_tmp       = $_FILES['file_pdf_kontrak']['tmp_name'];

        // Cek Ekstensi
        if ($ekstensi != 'pdf') {
            echo "<script>alert('Hanya file PDF yang diperbolehkan!'); window.history.back();</script>";
            exit;
        }

        $nama_file_pdf = "KONTRAK_" . date('Ymd_His') . ".pdf";
        $path_lengkap  = $folder_tujuan . $nama_file_pdf;

        if (!move_uploaded_file($file_tmp, $path_lengkap)) {
            echo "Gagal mengunggah file. Cek izin folder uploads.";
            exit;
        }
    }

    // 3. SIMPAN KE DATABASE
    // Pastikan kolom 'catatan_tambahan' ada di tabel tr_kontrak
    $query_simpan = "INSERT INTO tr_kontrak (id_unit_setting, id_pengguna_jasa, no_surat_kontrak, tgl_mulai, tgl_selesai, file_pdf_kontrak, status_sewa, catatan_tambahan) 
                     VALUES ('$id_unit_setting', '$id_pengguna', '$no_surat', '$tgl_mulai', '$tgl_selesai', '$nama_file_pdf', '$status_sewa', '$catatan')";

    if (mysqli_query($koneksi, $query_simpan)) {
        // 4. UPDATE STATUS ASET
        // Sesuai error sebelumnya, pastikan nama kolom adalah 'status_ketersediaan' atau 'status_aset'
        mysqli_query($koneksi, "UPDATE ms_unit_setting SET status_ketersediaan = 'Disewa' WHERE id_unit_setting = '$id_unit_setting'");
        
        echo "<script>alert('Kontrak Berhasil Disimpan!'); window.location='kontrak.php';</script>";
        exit();
    } else {
        // Jika masih error Foreign Key, ini akan memunculkan pesan yang jelas
        echo "<b>Gagal Simpan Database:</b> <br>" . mysqli_error($koneksi);
        echo "<br><br><b>Analisa:</b> Pastikan ID Aset (<b>$id_unit_setting</b>) benar-benar ada di tabel ms_unit_setting.";
    }
}
?>