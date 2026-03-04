<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query Hapus
    $query = "DELETE FROM ms_aset WHERE id_aset = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: master_aset.php?status=hapus_sukses");
    } else {
        echo "Gagal menghapus: " . mysqli_error($koneksi);
    }
} else {
    header("Location: master_aset.php");
}
?>