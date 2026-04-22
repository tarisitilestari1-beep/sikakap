<?php
include "koneksi.php";
// Query ambil data terbaru
$query = mysqli_query($koneksi, "SELECT * FROM ms_pengguna_jasa ORDER BY id_pengguna_jasa DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Pengguna Jasa</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
    .card { border-radius: 12px; }
    .table td { vertical-align: middle !important; padding: 12px 15px !important; }
    .modal-content { border-radius: 15px; border: none; }
    .btn-action { border-radius: 8px; margin: 2px; }
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
            <h1 class="m-0 font-weight-bold text-dark">Data Pengguna Jasa</h1>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-success shadow-sm px-4" data-toggle="modal" data-target="#modal-tambah" style="border-radius: 10px;">
              <i class="fas fa-user-plus mr-1"></i> Tambah Pengguna
            </button>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" style="border-radius: 10px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon fas fa-check-circle mr-2"></i> 
                <?php 
                    if($_GET['status'] == 'sukses') echo 'Data pengguna berhasil ditambahkan!';
                    if($_GET['status'] == 'update_sukses') echo 'Data pengguna berhasil diperbarui!';
                    if($_GET['status'] == 'hapus_sukses') echo 'Data pengguna berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover m-0">
                <thead class="bg-light text-muted small uppercase">
                  <tr>
                    <th class="pl-4">Nama Pengguna</th>
                    <th>No. Identitas (KTP)</th>
                    <th>No. HP / WhatsApp</th>
                    <th>Alamat Domisili</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = mysqli_fetch_assoc($query)) : ?>
                  <tr>
                    <td class="pl-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-success-light p-2 rounded-circle mr-3 d-none d-md-block" style="background: #e8f5e9; width: 35px; height: 35px; text-align: center;">
                                <i class="fas fa-user text-success"></i>
                            </div>
                            <span class="font-weight-bold text-dark"><?= htmlspecialchars($row['nama_pengguna_jasa']); ?></span>
                        </div>
                    </td>
                    <td class="text-muted"><?= htmlspecialchars($row['no_identitas']) ?: '-'; ?></td>
                    <td>
                        <a href="https://wa.me/<?= str_replace(['+', '-', ' '], '', $row['no_hp']); ?>" target="_blank" class="text-info">
                            <i class="fab fa-whatsapp mr-1"></i> <?= htmlspecialchars($row['no_hp']) ?: '-'; ?>
                        </a>
                    </td>
                    <td><small class="text-muted"><?= htmlspecialchars($row['alamat_domisili']); ?></small></td>
                    <td class="text-center">
                      <button class="btn btn-sm btn-outline-warning btn-edit btn-action" 
                              data-id="<?= $row['id_pengguna_jasa']; ?>"
                              data-nama="<?= $row['nama_pengguna_jasa']; ?>"
                              data-ktp="<?= $row['no_identitas']; ?>"
                              data-hp="<?= $row['no_hp']; ?>"
                              data-alamat="<?= $row['alamat_domisili']; ?>">
                        <i class="fas fa-edit"></i>
                      </button>
                      <a href="hapus_pengguna.php?id=<?= $row['id_pengguna_jasa']; ?>" 
                         class="btn btn-sm btn-outline-danger btn-action" 
                         onclick="return confirm('Hapus data <?= $row['nama_pengguna_jasa']; ?>?')">
                         <i class="fas fa-trash text-danger"></i>
                      </a>
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
</div>

<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg">
      <form action="proses_pengguna_jasa.php" method="POST">
        <div class="modal-header bg-success border-0">
          <h4 class="modal-title text-white font-weight-bold">Tambah Pengguna Jasa</h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-4">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_pengguna_jasa" class="form-control" required placeholder="Nama sesuai identitas...">
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>No. Identitas (KTP)</label>
                <input type="text" name="no_identitas" class="form-control" placeholder="16 Digit NIK">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No. HP / WhatsApp</label>
                <input type="text" name="no_hp" class="form-control" placeholder="0812...">
              </div>
            </div>
          </div>
          <div class="form-group mt-2">
            <label>Alamat Domisili</label>
            <textarea name="alamat_domisili" class="form-control" rows="3" placeholder="Alamat lengkap saat ini..."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light justify-content-between border-0">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="simpan" class="btn btn-success px-4 shadow-sm">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit">
  <div class="modal-dialog">
    <div class="modal-content shadow-lg">
      <form action="update_pengguna_jasa.php" method="POST">
        <div class="modal-header bg-warning border-0">
          <h4 class="modal-title text-dark font-weight-bold">Update Data Pengguna</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body p-4">
          <input type="hidden" name="id_pengguna_jasa" id="edit-id">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_pengguna_jasa" id="edit-nama" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>No. Identitas</label>
                <input type="text" name="no_identitas" id="edit-ktp" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>No. HP</label>
                <input type="text" name="no_hp" id="edit-hp" class="form-control">
              </div>
            </div>
          </div>
          <div class="form-group mt-2">
            <label>Alamat Domisili</label>
            <textarea name="alamat_domisili" id="edit-alamat" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light justify-content-between border-0">
          <button type="button" class="btn btn-default shadow-sm" data-dismiss="modal">Batal</button>
          <button type="submit" name="update" class="btn btn-warning px-4 font-weight-bold shadow-sm">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
$(document).on("click", ".btn-edit", function () {
     $("#edit-id").val($(this).data('id'));
     $("#edit-nama").val($(this).data('nama'));
     $("#edit-ktp").val($(this).data('ktp'));
     $("#edit-hp").val($(this).data('hp'));
     $("#edit-alamat").val($(this).data('alamat'));
     $('#modal-edit').modal('show');
});
</script>
</body>
</html>