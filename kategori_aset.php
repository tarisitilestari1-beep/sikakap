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
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <?php include "sidebar.php"; ?> 

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold text-dark">Manajemen Kategori Aset</h1>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-plus-circle mr-1"></i> Tambah Kategori
            </button>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card shadow border-0">
          <div class="card-body p-0">
            <table class="table table-striped table-hover m-0">
              <thead class="bg-dark text-white">
                <tr>
                  <th width="5%">ID</th>
                  <th>Nama Kategori</th>
                  <th>Jenis</th>
                  <th>Keterangan</th>
                  <th class="text-center" width="15%">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php while($d = mysqli_fetch_array($query)){ ?>
                <tr>
                  <td><?= $d['id_kategori']; ?></td>
                  <td><strong><?= $d['nama_kategori']; ?></strong></td>
                  <td>
                    <span class="badge <?= ($d['jenis_aset'] == 'Lahan') ? 'badge-success' : 'badge-info'; ?>">
                      <?= $d['jenis_aset']; ?>
                    </span>
                  </td>
                  <td><?= $d['keterangan'] ? $d['keterangan'] : '-'; ?></td>
                  <td class="text-center">
                    <button class="btn btn-sm btn-warning text-white" data-toggle="modal" data-target="#modal-edit<?= $d['id_kategori']; ?>" title="Edit Data">
                      <i class="fas fa-edit"></i>
                    </button>
                    
                    <a href="hapus_kategori.php?id=<?= $d['id_kategori']; ?>" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori <?= $d['nama_kategori']; ?>?')" title="Hapus Data">
                      <i class="fas fa-trash"></i>
                    </a>
                  </td>
                </tr>

                <div class="modal fade" id="modal-edit<?= $d['id_kategori']; ?>">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form action="proses_kategori.php" method="POST">
                        <div class="modal-header bg-warning">
                          <h4 class="modal-title text-white font-weight-bold">Update Kategori</h4>
                          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body text-left">
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
                        <div class="modal-footer justify-content-between border-top-0">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                          <button type="submit" name="update" class="btn btn-warning text-white px-4 shadow-sm">Simpan Perubahan</button>
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
    </section>
  </div>
</div>

<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="proses_kategori.php" method="POST">
        <div class="modal-header bg-primary text-white border-0">
          <h4 class="modal-title font-weight-bold">Tambah Kategori Baru</h4>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
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
        <div class="modal-footer justify-content-between border-top-0">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="save" class="btn btn-primary px-4 shadow-sm">Simpan Data</button>
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