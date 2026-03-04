<?php
include "koneksi.php";

// Query JOIN untuk mengambil data lengkap sesuai struktur database kamu
$sql = "SELECT tr_kontrak.*, ms_aset.nama_aset, ms_aset.kode_notasi, ms_pengguna_jasa.nama_pengguna_jasa 
        FROM tr_kontrak 
        JOIN ms_aset ON tr_kontrak.id_aset = ms_aset.id_aset
        JOIN ms_pengguna_jasa ON tr_kontrak.id_pengguna_jasa = ms_pengguna_jasa.id_pengguna_jasa
        ORDER BY tr_kontrak.id_kontrak DESC";
$query = mysqli_query($koneksi, $sql);

// Dropdown Aset: Hanya tampilkan yang status_ketersediaan = 'Tersedia'
$data_aset = mysqli_query($koneksi, "SELECT * FROM ms_aset WHERE status_ketersediaan = 'Tersedia'");

// Dropdown Pengguna Jasa
$data_pengguna = mysqli_query($koneksi, "SELECT * FROM ms_pengguna_jasa");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Dokumen Perjanjian</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .main-sidebar { background-color: #1e272e !important; }
    .content-wrapper { background-color: #f4f6f9; }
    .card { border-radius: 15px !important; }
    .badge-pill { padding-left: 10px; padding-right: 10px; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

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

  <?php include "sidebar.php"; ?>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold text-gray-dark">Dokumen Perjanjian</h1>
          </div>
          <div class="col-sm-6 text-right">
             <button class="btn btn-primary shadow-sm" style="border-radius: 10px;" data-toggle="modal" data-target="#modal-tambah">
                <i class="fas fa-plus-circle mr-1"></i> Buat Kontrak Baru
             </button>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <?php if(isset($_GET['status'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" style="border-radius: 10px;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon fas fa-check"></i> Kontrak berhasil diproses dan status aset telah diperbarui!
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead class="bg-light">
                <tr>
                  <th>No. Surat Kontrak</th>
                  <th>Aset</th>
                  <th>Penyewa</th>
                  <th>Masa Berlaku</th>
                  <th>Status</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php while($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                  <td><span class="text-primary font-weight-bold"><?php echo $row['no_surat_kontrak']; ?></span></td>
                  <td><?php echo $row['nama_aset']; ?> <small class="text-muted">(<?php echo $row['kode_notasi']; ?>)</small></td>
                  <td><?php echo $row['nama_pengguna_jasa']; ?></td>
                  <td>
                    <small>
                      <i class="far fa-calendar-alt text-muted"></i> 
                      <?php echo date('d/m/y', strtotime($row['tgl_mulai'])); ?> - <?php echo date('d/m/y', strtotime($row['tgl_selesai'])); ?>
                    </small>
                  </td>
                  <td>
                    <?php 
                      $badge = ($row['status_sewa'] == 'Aktif') ? 'success' : (($row['status_sewa'] == 'Berakhir') ? 'warning' : 'danger');
                      echo "<span class='badge badge-pill badge-$badge'>".$row['status_sewa']."</span>";
                    ?>
                  </td>
                  <td class="text-center">
                    <?php if(!empty($row['file_pdf_kontrak'])): ?>
                        <a href="uploads/<?php echo $row['file_pdf_kontrak']; ?>" target="_blank" class="btn btn-xs btn-info shadow-sm" title="Lihat PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    <?php endif; ?>

                    <a href="hapus_kontrak.php?id=<?php echo $row['id_kontrak']; ?>&id_aset=<?php echo $row['id_aset']; ?>" 
                       class="btn btn-xs btn-danger shadow-sm" onclick="return confirm('Yakin hapus kontrak ini? Status aset akan kembali Tersedia.')" title="Hapus">
                       <i class="fas fa-trash"></i>
                    </a>
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

  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="border-radius: 15px;">
        <form action="proses_kontrak.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header border-0">
            <h4 class="modal-title font-weight-bold">Form Perjanjian Baru</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label>Nomor Surat Kontrak</label>
              <input type="text" name="no_surat_kontrak" class="form-control" placeholder="Masukan nomor surat..." required>
            </div>
            <div class="row">
              <div class="col-md-6">
                <label>Pilih Aset (Yang Tersedia)</label>
                <select name="id_aset" class="form-control" required>
                  <option value="">-- Pilih Aset --</option>
                  <?php while($a = mysqli_fetch_assoc($data_aset)) : ?>
                    <option value="<?php echo $a['id_aset']; ?>"><?php echo $a['nama_aset']; ?> (<?php echo $a['kode_notasi']; ?>)</option>
                  <?php endwhile; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label>Pilih Penyewa</label>
                <select name="id_pengguna_jasa" class="form-control" required>
                  <option value="">-- Pilih Penyewa --</option>
                  <?php while($p = mysqli_fetch_assoc($data_pengguna)) : ?>
                    <option value="<?php echo $p['id_pengguna_jasa']; ?>"><?php echo $p['nama_pengguna_jasa']; ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-6">
                <label>Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label>Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" class="form-control" required>
              </div>
            </div>
            <div class="form-group mt-3">
              <label>Upload Berkas PDF <small class="text-danger">*Opsional</small></label>
              <input type="file" name="file_pdf_kontrak" class="form-control-file" accept=".pdf">
            </div>
            <div class="form-group mt-3">
              <label>Catatan Tambahan</label>
              <textarea name="catatan_tambahan" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer border-0 justify-content-between">
            <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
            <button type="submit" name="simpan" class="btn btn-primary px-4 shadow-sm" style="border-radius: 10px;">Simpan Kontrak</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <footer class="main-footer text-sm">
    <div class="float-right d-none d-sm-block">UPT PPP BULU</div>
    <strong>Copyright &copy; 2026 SIKAKAP.</strong>
  </footer>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>