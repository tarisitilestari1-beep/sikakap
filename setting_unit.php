<?php
include "koneksi.php";

// Ambil data unit dengan JOIN ke kategori agar nama kategorinya muncul
$sql = "SELECT ms_unit_setting.*, ms_kategori_aset.nama_kategori 
        FROM ms_unit_setting 
        JOIN ms_kategori_aset ON ms_unit_setting.id_kategori = ms_kategori_aset.id_kategori 
        ORDER BY ms_unit_setting.id_unit_setting DESC";
$query = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Setting Unit</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .card { border-radius: 12px; }
    .table td { vertical-align: middle !important; padding: 15px !important; }
    .badge-kat { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border-radius: 4px; padding: 5px 8px; }
    .unit-name { font-weight: 700; color: #333; font-size: 1rem; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  
  <?php include "navbar.php"; ?>

  <?php include "sidebar.php"; ?> 

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-3 mt-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold text-dark">Setting Nomor Unit</h1>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-info shadow-sm px-4" data-toggle="modal" data-target="#modal-tambah" style="border-radius: 10px;">
              <i class="fas fa-plus-circle mr-1"></i> Tambah Unit Baru
            </button>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card shadow-sm border-0">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover m-0">
                <thead class="bg-light">
                  <tr>
                    <th width="8%" class="text-center">No</th>
                    <th>Kategori Induk</th>
                    <th>Nama / Nomor Unit</th>
                    <th class="text-center" width="15%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  while($d = mysqli_fetch_array($query)){ 
                  ?>
                  <tr>
                    <td class="text-center text-muted"><?= $no++; ?></td>
                    <td><span class="badge badge-light border badge-kat text-primary"><?= $d['nama_kategori']; ?></span></td>
                    <td><span class="unit-name"><?= $d['nama_unit_setting']; ?></span></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button class="btn btn-sm btn-outline-warning mr-1" data-toggle="modal" data-target="#modal-edit<?= $d['id_unit_setting']; ?>" title="Edit">
                          <i class="fas fa-edit"></i>
                        </button>
                        <a href="proses_unit_setting.php?hapus=<?= $d['id_unit_setting']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus unit <?= $d['nama_unit_setting']; ?>?')" title="Hapus">
                          <i class="fas fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <div class="modal fade" id="modal-edit<?= $d['id_unit_setting']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
                        <form action="proses_unit_setting.php" method="POST">
                          <div class="modal-header bg-warning border-0">
                            <h4 class="modal-title text-white font-weight-bold">Update Data Unit</h4>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body p-4">
                            <input type="hidden" name="id_unit_setting" value="<?= $d['id_unit_setting']; ?>">
                            
                            <div class="form-group">
                              <label>Kategori Induk</label>
                              <select name="id_kategori" class="form-control" required>
                                <?php
                                $kat = mysqli_query($koneksi, "SELECT * FROM ms_kategori_aset ORDER BY nama_kategori ASC");
                                while($k = mysqli_fetch_array($kat)){
                                    $selected = ($k['id_kategori'] == $d['id_kategori']) ? 'selected' : '';
                                    echo "<option value='".$k['id_kategori']."' $selected>".$k['nama_kategori']."</option>";
                                }
                                ?>
                              </select>
                            </div>
                            
                            <div class="form-group">
                              <label>Nama / Nomor Unit</label>
                              <input type="text" name="nama_unit_setting" class="form-control" value="<?= $d['nama_unit_setting']; ?>" required>
                            </div>
                          </div>
                          <div class="modal-footer bg-light justify-content-between border-0">
                            <button type="button" class="btn btn-default shadow-sm" data-dismiss="modal">Batal</button>
                            <button type="submit" name="update" class="btn btn-warning text-white px-4 shadow">Simpan Perubahan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
      <form action="proses_unit_setting.php" method="POST">
        <div class="modal-header bg-info text-white border-0">
          <h4 class="modal-title font-weight-bold">Tambah Unit Baru</h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-4">
          <div class="form-group">
            <label>Pilih Kategori Induk</label>
            <select name="id_kategori" class="form-control" required>
              <option value="">-- Pilih Kategori --</option>
              <?php
              $kat_tambah = mysqli_query($koneksi, "SELECT * FROM ms_kategori_aset ORDER BY nama_kategori ASC");
              while($kt = mysqli_fetch_array($kat_tambah)){
                  echo "<option value='".$kt['id_kategori']."'>".$kt['nama_kategori']."</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label>Nama / Nomor Unit</label>
            <input type="text" name="nama_unit_setting" class="form-control" placeholder="Contoh: Kamar 01 atau Kios A-01" required>
          </div>
        </div>
        <div class="modal-footer bg-light justify-content-between border-0">
          <button type="button" class="btn btn-default shadow-sm" data-dismiss="modal">Batal</button>
          <button type="submit" name="simpan" class="btn btn-info px-4 shadow text-white">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>