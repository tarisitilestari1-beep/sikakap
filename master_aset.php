<?php
include "koneksi.php";
$query = mysqli_query($koneksi, "SELECT * FROM ms_aset ORDER BY kode_notasi ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Master Aset</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include "sidebar.php"; ?> 

  <nav class="main-header navbar navbar-expand navbar-white navbar-light border-0 shadow-sm">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars text-primary"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <span class="nav-link font-weight-bold text-dark text-uppercase">SIKAKAP - UPT PPP BULU</span>
      </li>
    </ul>
  </nav>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold">Manajemen Master Aset</h1>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-primary shadow" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-plus-circle mr-1"></i> Tambah Aset
            </button>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-<?php echo ($_GET['status'] == 'hapus_sukses') ? 'danger' : 'success'; ?> alert-dismissible fade show shadow-sm">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon fas fa-info-circle"></i>
                <?php 
                  if($_GET['status'] == 'tambah_sukses') echo "Data aset berhasil ditambahkan!";
                  if($_GET['status'] == 'hapus_sukses') echo "Data aset telah dihapus.";
                  if($_GET['status'] == 'update_sukses') echo "Data aset berhasil diperbarui!";
                ?>
            </div>
        <?php endif; ?>

        <div class="card shadow border-0">
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead class="bg-light">
                <tr>
                  <th>Notasi</th>
                  <th>Nama Aset</th>
                  <th>Jenis</th>
                  <th>Kondisi</th>
                  <th>Status Sewa</th> <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                  <td><span class="badge badge-dark"><?php echo $row['kode_notasi']; ?></span></td>
                  <td><strong><?php echo $row['nama_aset']; ?></strong></td>
                  <td><?php echo $row['jenis_aset']; ?></td>
                  <td>
                    <?php 
                      $cls = ($row['kondisi'] == 'Baik') ? 'success' : (($row['kondisi'] == 'Rusak Ringan') ? 'warning' : 'danger');
                      echo "<span class='badge badge-$cls'>".$row['kondisi']."</span>";
                    ?>
                  </td>
                  <td> <?php if($row['status_ketersediaan'] == 'Tersedia') : ?>
                        <span class="badge badge-pill badge-outline-success" style="border: 1px solid #28a745; color: #28a745;">
                            <i class="fas fa-check-circle mr-1"></i> Tersedia
                        </span>
                    <?php else : ?>
                        <span class="badge badge-pill badge-danger shadow-sm">
                            <i class="fas fa-handshake mr-1"></i> Sedang Disewa
                        </span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <a href="<?php echo $row['link_google_earth']; ?>" target="_blank" class="btn btn-xs btn-info" title="Buka Google Earth"><i class="fas fa-globe"></i></a>
                    
                    <button class="btn btn-xs btn-warning btn-edit" 
                            data-id="<?php echo $row['id_aset']; ?>"
                            data-notasi="<?php echo $row['kode_notasi']; ?>"
                            data-nama="<?php echo $row['nama_aset']; ?>"
                            data-jenis="<?php echo $row['jenis_aset']; ?>"
                            data-luas="<?php echo $row['luas_m2']; ?>"
                            data-kondisi="<?php echo $row['kondisi']; ?>"
                            data-status="<?php echo $row['status_ketersediaan']; ?>"
                            data-earth="<?php echo $row['link_google_earth']; ?>"
                            data-ket="<?php echo $row['keterangan']; ?>">
                        <i class="fas fa-edit"></i>
                    </button>

                    <a href="hapus_aset.php?id=<?php echo $row['id_aset']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('Yakin hapus data ini?')"><i class="fas fa-trash"></i></a>
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
    <div class="modal-content">
      <form action="proses_aset.php" method="POST">
        <div class="modal-header bg-primary text-white border-0">
          <h4 class="modal-title font-weight-bold">Tambah Data Aset</h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4"><label>Notasi</label><input type="text" name="kode_notasi" class="form-control" required></div>
            <div class="col-md-8"><label>Nama Aset</label><input type="text" name="nama_aset" class="form-control" required></div>
          </div>
          <div class="row mt-3">
            <div class="col-md-6">
              <label>Jenis</label>
              <select name="jenis_aset" class="form-control">
                <option value="Lahan">Lahan</option>
                <option value="Bangunan">Bangunan</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Kondisi</label>
              <select name="kondisi" class="form-control">
                <option value="Baik">Baik</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
              </select>
            </div>
          </div>
          <div class="form-group mt-3"><label>Luas (m²)</label><input type="number" step="0.01" name="luas_m2" class="form-control"></div>
          <div class="form-group mt-3"><label>Link Google Earth</label><input type="url" name="link_google_earth" class="form-control"></div>
          <div class="form-group mt-3"><label>Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
        </div>
        <div class="modal-footer justify-content-between border-0">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="simpan" class="btn btn-primary px-4">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit">
  <div class="modal-dialog border-0">
    <div class="modal-content shadow-lg">
      <form action="update_aset.php" method="POST">
        <div class="modal-header bg-warning border-0">
          <h4 class="modal-title font-weight-bold">Edit Data Aset</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_aset" id="edit-id">
          <div class="row">
            <div class="col-md-4"><label>Notasi</label><input type="text" name="kode_notasi" id="edit-notasi" class="form-control" required></div>
            <div class="col-md-8"><label>Nama Aset</label><input type="text" name="nama_aset" id="edit-nama" class="form-control" required></div>
          </div>
          <div class="row mt-3">
            <div class="col-md-6">
              <label>Kondisi</label>
              <select name="kondisi" id="edit-kondisi" class="form-control">
                <option value="Baik">Baik</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
              </select>
            </div>
            <div class="col-md-6">
              <label>Status Ketersediaan</label>
              <select name="status_ketersediaan" id="edit-status" class="form-control">
                <option value="Tersedia">Tersedia</option>
                <option value="Disewa">Disewa</option>
              </select>
            </div>
          </div>
          <div class="form-group mt-3"><label>Link Google Earth</label><input type="url" name="link_google_earth" id="edit-earth" class="form-control"></div>
          <div class="form-group mt-3"><label>Keterangan</label><textarea name="keterangan" id="edit-ket" class="form-control" rows="2"></textarea></div>
        </div>
        <div class="modal-footer justify-content-between border-0">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="update" class="btn btn-warning px-4 font-weight-bold">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="main-footer text-sm">
    <div class="float-right d-none d-sm-block">UPT PPP BULU</div>
    <strong>Copyright &copy; 2026 SIKAKAP.</strong>
</footer>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
$(document).on("click", ".btn-edit", function () {
     $("#edit-id").val($(this).data('id'));
     $("#edit-notasi").val($(this).data('notasi'));
     $("#edit-nama").val($(this).data('nama'));
     $("#edit-kondisi").val($(this).data('kondisi'));
     $("#edit-status").val($(this).data('status'));
     $("#edit-earth").val($(this).data('earth'));
     $("#edit-ket").val($(this).data('ket'));
     $('#modal-edit').modal('show');
});
</script>
</body>
</html>