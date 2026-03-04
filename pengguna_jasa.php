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
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include "sidebar.php"; ?> 

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1 class="m-0 font-weight-bold text-dark">Data Pengguna Jasa</h1></div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-success shadow" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-user-plus mr-1"></i> Tambah Pengguna
            </button>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon fas fa-check"></i> 
                <?php 
                    if($_GET['status'] == 'sukses') echo 'Data berhasil disimpan!';
                    if($_GET['status'] == 'update_sukses') echo 'Data berhasil diperbarui!';
                    if($_GET['status'] == 'hapus_sukses') echo 'Data berhasil dihapus!';
                ?>
            </div>
        <?php endif; ?>

        <div class="card shadow border-0">
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead class="bg-light">
                <tr>
                  <th>Nama Pengguna</th>
                  <th>No. Identitas (KTP)</th>
                  <th>No. HP</th>
                  <th>Alamat Domisili</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                  <td><strong><?php echo htmlspecialchars($row['nama_pengguna_jasa']); ?></strong></td>
                  <td><?php echo htmlspecialchars($row['no_identitas']) ?: '-'; ?></td>
                  <td><?php echo htmlspecialchars($row['no_hp']) ?: '-'; ?></td>
                  <td><small><?php echo htmlspecialchars($row['alamat_domisili']); ?></small></td>
                  <td class="text-center">
                    <button class="btn btn-xs btn-warning btn-edit" 
                            data-id="<?php echo $row['id_pengguna_jasa']; ?>"
                            data-nama="<?php echo $row['nama_pengguna_jasa']; ?>"
                            data-ktp="<?php echo $row['no_identitas']; ?>"
                            data-hp="<?php echo $row['no_hp']; ?>"
                            data-alamat="<?php echo $row['alamat_domisili']; ?>">
                      <i class="fas fa-edit"></i>
                    </button>
                    <a href="hapus_pengguna.php?id=<?php echo $row['id_pengguna_jasa']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('Hapus data ini?')"><i class="fas fa-trash"></i></a>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog border-0">
    <div class="modal-content shadow-lg">
      <form action="proses_pengguna_jasa.php" method="POST">
        <div class="modal-header bg-success text-white border-0">
          <h4 class="modal-title font-weight-bold">Tambah Pengguna Jasa</h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_pengguna_jasa" class="form-control" required placeholder="Masukkan nama...">
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>No. Identitas</label>
              <input type="text" name="no_identitas" class="form-control" placeholder="No. KTP">
            </div>
            <div class="col-md-6">
              <label>No. HP</label>
              <input type="text" name="no_hp" class="form-control" placeholder="0812...">
            </div>
          </div>
          <div class="form-group mt-3">
            <label>Alamat Domisili</label>
            <textarea name="alamat_domisili" class="form-control" rows="3" placeholder="Alamat lengkap..."></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between border-0">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="simpan" class="btn btn-success px-4">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit">
  <div class="modal-dialog border-0">
    <div class="modal-content shadow-lg">
      <form action="update_pengguna_jasa.php" method="POST">
        <div class="modal-header bg-warning border-0">
          <h4 class="modal-title font-weight-bold">Edit Pengguna Jasa</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_pengguna_jasa" id="edit-id">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_pengguna_jasa" id="edit-nama" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>No. Identitas</label>
              <input type="text" name="no_identitas" id="edit-ktp" class="form-control">
            </div>
            <div class="col-md-6">
              <label>No. HP</label>
              <input type="text" name="no_hp" id="edit-hp" class="form-control">
            </div>
          </div>
          <div class="form-group mt-3">
            <label>Alamat Domisili</label>
            <textarea name="alamat_domisili" id="edit-alamat" class="form-control" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer justify-content-between border-0">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="update" class="btn btn-warning px-4 font-weight-bold">Update Data</button>
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