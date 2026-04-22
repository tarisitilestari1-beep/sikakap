<?php
include "koneksi.php";

// 1. QUERY DATA UNTUK TABEL
$sql = "SELECT a.*, u.nama_unit_setting, k.nama_kategori 
        FROM master_aset a
        JOIN ms_unit_setting u ON a.id_unit_setting = u.id_unit_setting
        JOIN ms_kategori_aset k ON u.id_kategori = k.id_kategori
        ORDER BY a.id_aset DESC";
$query = mysqli_query($koneksi, $sql);

// 2. QUERY DATA UNIT UNTUK DROPDOWN
$sql_unit = "SELECT u.*, k.nama_kategori 
             FROM ms_unit_setting u 
             JOIN ms_kategori_aset k ON u.id_kategori = k.id_kategori";
$data_unit = mysqli_query($koneksi, $sql_unit);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Master Aset</title>
  
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>

  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; }
    .table td { vertical-align: middle !important; padding: 12px !important; }
    .img-aset { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
    .nama-unit { font-weight: 800; display: block; color: #333; }
    .nama-kategori { font-size: 0.7rem; font-weight: 700; color: #007bff; text-transform: uppercase; }
    .btn-rounded { border-radius: 8px !important; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php include "navbar.php"; ?>
  <?php include "sidebar.php"; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 mt-2">
          <div class="col-sm-6">
            <h1 class="font-weight-bold">Data Master Aset</h1>
          </div>
          <div class="col-sm-6 text-right">
            <button type="button" class="btn btn-primary btn-rounded shadow-sm px-4" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-plus-circle mr-1"></i> Tambah Aset
            </button>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card shadow-sm border-0">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover m-0">
                <thead class="bg-light">
                  <tr>
                    <th class="text-center" width="50">No</th>
                    <th width="80">Foto</th>
                    <th>Unit & Kategori</th>
                    <th>Luas & Harga</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" width="180">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  while($row = mysqli_fetch_assoc($query)) : 
                    $color = ($row['status_aset'] == 'Tersedia') ? 'badge-success' : 'badge-danger';
                  ?>
                  <tr>
                    <td class="text-center text-muted"><?= $no++; ?></td>
                    <td>
                        <img src="uploads/<?= $row['foto_aset'] ? $row['foto_aset'] : 'default.png'; ?>" class="img-aset">
                    </td>
                    <td>
                      <span class="nama-kategori"><?= $row['nama_kategori']; ?></span>
                      <span class="nama-unit"><?= $row['nama_unit_setting']; ?></span>
                    </td>
                    <td>
                      <span class="text-dark font-weight-bold"><?= $row['luas_m2']; ?> m²</span><br>
                      <span class="text-success small">Rp <?= number_format($row['harga_sewa'], 0, ',', '.'); ?></span>
                    </td>
                    <td class="text-center">
                      <span class="badge <?= $color; ?> shadow-sm" style="padding: 5px 10px;"><?= $row['status_aset']; ?></span>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button class="btn btn-sm btn-warning text-white btn-edit btn-rounded" 
                                data-toggle="modal" data-target="#modal-edit"
                                data-id="<?= $row['id_aset']; ?>"
                                data-unit="<?= $row['id_unit_setting']; ?>"
                                data-luas="<?= $row['luas_m2']; ?>"
                                data-harga="<?= $row['harga_sewa']; ?>"
                                data-kondisi="<?= $row['kondisi']; ?>"
                                data-status="<?= $row['status_aset']; ?>"
                                data-ket="<?= $row['keterangan']; ?>">
                          <i class="fas fa-edit"></i>
                        </button>

                        <a href="plotting_gis.php?id=<?= $row['id_aset']; ?>" class="btn btn-sm btn-info btn-rounded mx-1 shadow-sm">
                          <i class="fas fa-map-marker-alt"></i>
                        </a>

                        <a href="proses_aset.php?hapus=<?= $row['id_aset']; ?>" class="btn btn-sm btn-danger btn-rounded shadow-sm" onclick="return confirm('Yakin ingin menghapus?')">
                          <i class="fas fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div> <div class="modal fade" id="modal-tambah" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 15px;">
      <form action="proses_aset.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header border-0 bg-primary text-white">
          <h5 class="modal-title font-weight-bold">Tambah Aset Baru</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-4">
          <div class="form-group">
            <label>Pilih Unit Master</label>
            <select name="id_unit_setting" class="form-control" required>
              <option value="">-- Pilih Unit --</option>
              <?php mysqli_data_seek($data_unit, 0); while($u = mysqli_fetch_assoc($data_unit)) : ?>
                <option value="<?= $u['id_unit_setting']; ?>">[<?= $u['nama_kategori']; ?>] <?= $u['nama_unit_setting']; ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="row">
            <div class="col-6">
              <label>Luas (m²)</label>
              <input type="number" step="any" name="luas_m2" class="form-control" required>
            </div>
            <div class="col-6">
              <label>Harga Sewa (Rp)</label>
              <input type="number" name="harga_sewa" class="form-control" required>
            </div>
          </div>
          <div class="form-group mt-3">
            <label>Kondisi</label>
            <select name="kondisi" class="form-control">
              <option value="Baik">Baik</option>
              <option value="Rusak Ringan">Rusak Ringan</option>
              <option value="Rusak Berat">Rusak Berat</option>
            </select>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status_aset" class="form-control">
              <option value="Tersedia">Tersedia</option>
              <option value="Tersewa">Tersewa</option>
            </select>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="2"></textarea>
          </div>
          <div class="form-group">
            <label>Upload Foto</label>
            <input type="file" name="foto_aset" class="form-control-file">
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" name="simpan_aset" class="btn btn-primary btn-block btn-rounded">Simpan Data Aset</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 15px;">
      <form action="proses_aset.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header border-0 bg-warning text-white">
          <h5 class="modal-title font-weight-bold">Edit Data Aset</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-4">
          <input type="hidden" name="id_aset" id="edit_id">
          <div class="form-group">
            <label>Pilih Unit</label>
            <select name="id_unit_setting" id="edit_unit" class="form-control" required>
              <?php mysqli_data_seek($data_unit, 0); while($u = mysqli_fetch_assoc($data_unit)) : ?>
                <option value="<?= $u['id_unit_setting']; ?>">[<?= $u['nama_kategori']; ?>] <?= $u['nama_unit_setting']; ?></option>
              <?php endwhile; ?>
            </select>
          </div>
          <div class="row">
            <div class="col-6">
              <label>Luas (m²)</label>
              <input type="number" step="any" name="luas_m2" id="edit_luas" class="form-control" required>
            </div>
            <div class="col-6">
              <label>Harga Sewa (Rp)</label>
              <input type="number" name="harga_sewa" id="edit_harga" class="form-control" required>
            </div>
          </div>
          <div class="form-group mt-3">
            <label>Kondisi</label>
            <select name="kondisi" id="edit_kondisi" class="form-control">
              <option value="Baik">Baik</option>
              <option value="Rusak Ringan">Rusak Ringan</option>
              <option value="Rusak Berat">Rusak Berat</option>
            </select>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select name="status_aset" id="edit_status" class="form-control">
              <option value="Tersedia">Tersedia</option>
              <option value="Tersewa">Tersewa</option>
            </select>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" id="edit_ket" class="form-control" rows="2"></textarea>
          </div>
          <div class="form-group">
            <label>Ganti Foto</label>
            <input type="file" name="foto_aset" class="form-control-file">
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="submit" name="update_aset" class="btn btn-warning btn-block btn-rounded text-white">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Memastikan pengisian data edit bekerja
    $(document).on("click", ".btn-edit", function () {
         $("#edit_id").val($(this).data('id'));
         $("#edit_unit").val($(this).data('unit'));
         $("#edit_luas").val($(this).data('luas'));
         $("#edit_harga").val($(this).data('harga'));
         $("#edit_kondisi").val($(this).data('kondisi'));
         $("#edit_status").val($(this).data('status'));
         $("#edit_ket").val($(this).data('ket'));
    });
});
</script>
</body>
</html>