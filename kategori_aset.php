<?php
include "koneksi.php";

// Ambil data dari database
$query = mysqli_query($koneksi, "SELECT * FROM ms_kategori_aset ORDER BY id_kategori DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Kategori Aset</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .card { border-radius: 12px; }
    .table thead th { border-top: 0; border-bottom: 2px solid #eee; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
    .table td { vertical-align: middle !important; padding: 15px !important; }
    .badge { padding: 5px 10px; border-radius: 6px; font-weight: 600; }
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
            <h1 class="m-0 font-weight-bold text-dark">Manajemen Kategori Aset</h1>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-primary shadow-sm px-4" data-toggle="modal" data-target="#modal-tambah" style="border-radius: 10px;">
              <i class="fas fa-plus-circle mr-1"></i> Tambah Kategori
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
                    <th width="8%" class="text-center">ID</th>
                    <th>Nama Kategori</th>
                    <th>Jenis Aset</th>
                    <th>Keterangan</th>
                    <th class="text-center" width="15%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($d = mysqli_fetch_array($query)){ ?>
                  <tr>
                    <td class="text-center text-muted font-weight-bold"><?= $d['id_kategori']; ?></td>
                    <td><span class="text-dark font-weight-bold"><?= $d['nama_kategori']; ?></span></td>
                    <td>
                      <span class="badge <?= ($d['jenis_aset'] == 'Lahan') ? 'badge-success' : 'badge-info'; ?>">
                        <i class="fas <?= ($d['jenis_aset'] == 'Lahan') ? 'fa-map' : 'fa-building'; ?> mr-1"></i>
                        <?= $d['jenis_aset']; ?>
                      </span>
                    </td>
                    <td class="text-muted small"><?= $d['keterangan'] ? $d['keterangan'] : '-'; ?></td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button class="btn btn-sm btn-outline-warning mr-1" data-toggle="modal" data-target="#modal-edit<?= $d['id_kategori']; ?>" title="Edit">
                          <i class="fas fa-edit"></i>
                        </button>
                        <a href="hapus_kategori.php?id=<?= $d['id_kategori']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kategori <?= $d['nama_kategori']; ?>?')" title="Hapus">
                          <i class="fas fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>

                  <div class="modal fade" id="modal-edit<?= $d['id_kategori']; ?>">
                    <div class="modal-dialog">
                      <div class="modal-content shadow-lg border-0" style="border-radius: 15px;">
                        <form action="proses_kategori.php" method="POST">
                          <div class="modal-header bg-warning border-0">
                            <h4 class="modal-title text-white font-weight-bold">Update Kategori</h4>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body p-4">
                            <input type="hidden" name="id_kategori" value="<?= $d['id_kategori']; ?>">
                            <div class="form-group">
                              <label>Nama Kategori</label>
                              <input type="text" name="nama_kategori" class="form-control" value="<?= $d['nama_kategori']; ?>" required>
                            </div>
                            <div class="form-group">
                              <label>Jenis Aset</label>
                              <select name="jenis_aset" class="form-control">
                                <option value="Lahan" <?= ($d['jenis_aset'] == 'Lahan') ? 'selected' : ''; ?>>Lahan</option>
                                <option value="Bangunan" <?= ($d['jenis_aset'] == 'Bangunan') ? 'selected' : ''; ?>>Bangunan</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Keterangan</label>
                              <textarea name="keterangan" class="form-control" rows="3"><?= $d['keterangan']; ?></textarea>
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
      <form action="proses_kategori.php" method="POST">
        <div class="modal-header bg-primary text-white border-0">
          <h4 class="modal-title font-weight-bold">Tambah Kategori Baru</h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-4">
          <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Gedung Guest House" required>
          </div>
          <div class="form-group">
            <label>Jenis Aset</label>
            <select name="jenis_aset" class="form-control">
              <option value="Lahan">Lahan</option>
              <option value="Bangunan">Bangunan</option>
            </select>
          </div>
          <div class="form-group">
            <label>Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan catatan tambahan..."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light justify-content-between border-0">
          <button type="button" class="btn btn-default shadow-sm" data-dismiss="modal">Batal</button>
          <button type="submit" name="save" class="btn btn-primary px-4 shadow">Simpan Data</button>
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