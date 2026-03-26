<?php
include "koneksi.php";

// Query dengan JOIN yang presisi sesuai struktur database kamu
$sql = "SELECT tr_kontrak.*, ms_aset.nama_aset, ms_aset.kode_notasi, ms_pengguna_jasa.nama_pengguna_jasa 
        FROM tr_kontrak 
        JOIN ms_aset ON tr_kontrak.id_aset = ms_aset.id_aset
        JOIN ms_pengguna_jasa ON tr_kontrak.id_pengguna_jasa = ms_pengguna_jasa.id_pengguna_jasa
        ORDER BY tr_kontrak.id_kontrak DESC";
$query = mysqli_query($koneksi, $sql);

// Dropdown Aset: Hanya yang Tersedia
$data_aset = mysqli_query($koneksi, "SELECT * FROM ms_aset WHERE status_ketersediaan = 'Tersedia'");
// Dropdown Pengguna
$data_pengguna = mysqli_query($koneksi, "SELECT * FROM ms_pengguna_jasa");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>SIKAKAP | Administrasi Kontrak</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .card { border-radius: 12px; }
    .table thead th { border-top: 0; }
    .badge-expired { background-color: #f8d7da; color: #721c24; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include "sidebar.php"; ?>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1>Dokumen Perjanjian</h1></div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-primary shadow" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-plus"></i> Buat Kontrak Baru
            </button>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        <div class="card shadow-sm">
          <div class="card-body p-0">
            <table class="table table-hover">
              <thead class="bg-light">
                <tr>
                  <th>No. Kontrak</th>
                  <th>Aset & Penyewa</th>
                  <th>Masa Berlaku</th>
                  <th>Status Sewa</th>
                  <th class="text-center">Berkas & Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                while($row = mysqli_fetch_assoc($query)) : 
                  // LOGIKA OTOMATIS STATUS
                  $tgl_skrg = date('Y-m-d');
                  $status_display = $row['status_sewa'];
                  $warna_badge = "success";

                  if ($tgl_skrg > $row['tgl_selesai']) {
                      $status_display = "Berakhir";
                      $warna_badge = "secondary";
                  }
                ?>
                <tr>
                  <td><b class="text-primary"><?php echo $row['no_surat_kontrak']; ?></b></td>
                  <td>
                    <?php echo $row['nama_aset']; ?> <br>
                    <small class="text-muted"><i class="fas fa-user border-right pr-2 mr-2"></i><?php echo $row['nama_pengguna_jasa']; ?></small>
                  </td>
                  <td>
                    <?php echo date('d M Y', strtotime($row['tgl_mulai'])); ?> - <br>
                    <span class="<?php echo ($status_display == 'Berakhir') ? 'text-danger font-weight-bold' : ''; ?>">
                        <?php echo date('d M Y', strtotime($row['tgl_selesai'])); ?>
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-<?php echo $warna_badge; ?> px-3 py-2" style="border-radius:20px;">
                        <?php echo $status_display; ?>
                    </span>
                  </td>
                  <td class="text-center">
                    <?php if(!empty($row['file_pdf_kontrak'])): ?>
                      <a href="uploads/<?php echo $row['file_pdf_kontrak']; ?>" target="_blank" class="btn btn-sm btn-outline-info mr-1">
                        <i class="fas fa-file-pdf"></i>
                      </a>
                    <?php endif; ?>
                    <a href="hapus_kontrak.php?id=<?php echo $row['id_kontrak']; ?>&id_aset=<?php echo $row['id_aset']; ?>" 
                       class="btn btn-sm btn-danger" onclick="return confirm('Hapus kontrak? Aset akan kembali Tersedia.')">
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
          <div class="modal-content">
              <form action="proses_kontrak.php" method="POST" enctype="multipart/form-data">
                  <div class="modal-header"><h4>Form Perjanjian Baru</h4></div>
                  <div class="modal-body">
                      <div class="form-group">
                          <label>Nomor Surat Kontrak</label>
                          <input type="text" name="no_surat_kontrak" class="form-control" required>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                              <label>Aset</label>
                              <select name="id_aset" class="form-control" required>
                                  <?php while($a = mysqli_fetch_assoc($data_aset)) : ?>
                                      <option value="<?php echo $a['id_aset']; ?>"><?php echo $a['nama_aset']; ?></option>
                                  <?php endwhile; ?>
                              </select>
                          </div>
                          <div class="col-md-6">
                              <label>Penyewa</label>
                              <select name="id_pengguna_jasa" class="form-control" required>
                                  <?php while($p = mysqli_fetch_assoc($data_pengguna)) : ?>
                                      <option value="<?php echo $p['id_pengguna_jasa']; ?>"><?php echo $p['nama_pengguna_jasa']; ?></option>
                                  <?php endwhile; ?>
                              </select>
                          </div>
                      </div>
                      <div class="row mt-3">
                          <div class="col-md-6"><label>Tgl Mulai</label><input type="date" name="tgl_mulai" class="form-control" required></div>
                          <div class="col-md-6"><label>Tgl Selesai</label><input type="date" name="tgl_selesai" class="form-control" required></div>
                      </div>
                      <div class="form-group mt-3">
                          <label>Upload PDF Kontrak</label>
                          <input type="file" name="file_pdf_kontrak" class="form-control-file" accept=".pdf">
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" name="simpan" class="btn btn-primary btn-block">Simpan Kontrak & Update Status Aset</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>