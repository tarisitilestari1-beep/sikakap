<?php
include "koneksi.php";

// Query JOIN sesuai struktur database tr_kontrak
$sql = "SELECT tr_kontrak.*, ms_unit_setting.nama_unit_setting, ms_pengguna_jasa.nama_pengguna_jasa 
        FROM tr_kontrak 
        JOIN ms_unit_setting ON tr_kontrak.id_unit_setting = ms_unit_setting.id_unit_setting
        JOIN ms_pengguna_jasa ON tr_kontrak.id_pengguna_jasa = ms_pengguna_jasa.id_pengguna_jasa
        ORDER BY tr_kontrak.id_kontrak DESC";
$query = mysqli_query($koneksi, $sql);

// Data pendukung untuk Modal
$data_aset = mysqli_query($koneksi, "SELECT * FROM ms_unit_setting ");

$data_pengguna = mysqli_query($koneksi, "SELECT * FROM ms_pengguna_jasa");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Administrasi Kontrak</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; }
    .card { border-radius: 15px; border: none; }
    .table thead th { border-top: 0; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; color: #888; }
    .table td { vertical-align: middle !important; padding: 15px !important; }
    .badge-pill { padding: 0.5em 1em; font-weight: 600; }
    .modal-content { border-radius: 20px; border: none; }
    .form-control { border-radius: 8px; }
    .btn-rounded { border-radius: 10px; }
    .bg-navy-custom { background-color: #001f3f; color: white; }
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
            <h1 class="m-0 font-weight-bold text-dark">Administrasi Kontrak</h1>
            <p class="text-muted small">Kelola dokumen perjanjian dan masa sewa aset</p>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn bg-navy-custom btn-rounded shadow-sm px-4" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-file-contract mr-2"></i> Buat Kontrak Baru
            </button>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card shadow-sm">
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover m-0">
                <thead class="bg-light">
                  <tr>
                    <th class="pl-4">Info Dokumen</th>
                    <th>Objek Aset & Penyewa</th>
                    <th>Detail Finansial</th>
                    <th>Masa Berlaku</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = mysqli_fetch_assoc($query)) : 
                    $status = $row['status_sewa'];
                    $badge_class = ($status == 'Aktif') ? 'badge-success' : (($status == 'Berakhir') ? 'badge-secondary' : 'badge-danger');
                    
                    // Logika Warning jika tgl_selesai terlewati
                    $is_expired = (date('Y-m-d') > $row['tgl_selesai'] && $status == 'Aktif');
                  ?>
                  <tr>
                    <td class="pl-4">
                      <span class="text-dark font-weight-bold d-block"><?= $row['no_surat_kontrak']; ?></span>
                      <small class="text-muted">TTD: <?= $row['tgl_ttd'] ? date('d/m/Y', strtotime($row['tgl_ttd'])) : '-'; ?></small>
                    </td>
                    <td>
                      <span class="font-weight-bold"><?= $row['nama_aset']; ?></span><br>
                      <small class="text-muted"><i class="fas fa-user-tie mr-1"></i> <?= $row['nama_pengguna_jasa']; ?></small>
                    </td>
                    <td>
                      <span class="text-dark">Rp <?= number_format($row['harga_sewa'], 0, ',', '.'); ?></span>
                      <small class="text-muted">/ <?= $row['siklus_sewa']; ?></small><br>
                      <small class="badge badge-light border">Luas: <?= $row['luas_pakai']; ?> m²</small>
                    </td>
                    <td>
                      <div class="small">
                        <span class="text-success"><i class="fas fa-sign-in-alt mr-1"></i> <?= date('d M Y', strtotime($row['tgl_mulai'])); ?></span><br>
                        <span class="<?= $is_expired ? 'text-danger font-weight-bold' : 'text-muted'; ?>">
                          <i class="fas fa-sign-out-alt mr-1"></i> <?= date('d M Y', strtotime($row['tgl_selesai'])); ?>
                        </span>
                      </div>
                    </td>
                    <td>
                      <?php if($is_expired): ?>
                        <span class="badge badge-pill badge-warning animated flash infinite">Perlu Update</span>
                      <?php else: ?>
                        <span class="badge badge-pill <?= $badge_class; ?>"><?= $status; ?></span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <?php if(!empty($row['file_pdf_kontrak'])): ?>
                          <a href="uploads/<?= $row['file_pdf_kontrak']; ?>" target="_blank" class="btn btn-sm btn-outline-info mr-1 btn-rounded" title="Lihat PDF">
                            <i class="fas fa-file-pdf"></i>
                          </a>
                        <?php endif; ?>
                        <a href="hapus_kontrak.php?id=<?= $row['id_kontrak']; ?>&id_aset=<?= $row['id_aset']; ?>" 
                           class="btn btn-sm btn-outline-danger btn-rounded" onclick="return confirm('Hapus kontrak ini?')">
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

  <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="proses_kontrak.php" method="POST" enctype="multipart/form-data">
          <div class="modal-header bg-navy-custom border-0">
            <h4 class="modal-title font-weight-bold">Form Perjanjian Baru</h4>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body p-4">
            <div class="row">
              <div class="col-md-6 border-right">
                <h6 class="text-uppercase text-muted font-weight-bold mb-3 small">Detail Dokumen & Objek</h6>
                <div class="form-group">
                  <label>Nomor Surat Kontrak</label>
                  <input type="text" name="no_surat_kontrak" class="form-control shadow-sm" required>
                </div>
                <div class="form-group">
                  <label>Pilih Aset</label>
                  <select name="id_aset" class="form-control shadow-sm" required>
                    <option value="">-- Pilih Aset --</option>
                    <?php while($a = mysqli_fetch_assoc($data_aset)) : ?>
                      <option value="<?= $a['id_unit_setting']; ?>"><?= $a['nama_unit_setting']; ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Luas Pemanfaatan (m²)</label>
                  <input type="number" step="any" name="luas_pakai" class="form-control shadow-sm" required>
                </div>
              </div>
              <div class="col-md-6 pl-md-4">
                <h6 class="text-uppercase text-muted font-weight-bold mb-3 small">Penyewa & Finansial</h6>
                <div class="form-group">
                  <label>Nama Penyewa</label>
                  <select name="id_pengguna_jasa" class="form-control shadow-sm" required>
                    <option value="">-- Pilih Penyewa --</option>
                    <?php while($p = mysqli_fetch_assoc($data_pengguna)) : ?>
                      <option value="<?= $p['id_pengguna_jasa']; ?>"><?= $p['nama_pengguna_jasa']; ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="row">
                  <div class="col-7">
                    <label>Harga Sewa (Rp)</label>
                    <input type="number" name="harga_sewa" class="form-control shadow-sm" required>
                  </div>
                  <div class="col-5">
                    <label>Siklus</label>
                    <select name="siklus_sewa" class="form-control shadow-sm">
                      <option value="Tahun">Tahun</option>
                      <option value="Bulan">Bulan</option>
                    </select>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-6"><label>Tgl Mulai</label><input type="date" name="tgl_mulai" class="form-control shadow-sm" required></div>
                  <div class="col-6"><label>Tgl Selesai</label><input type="date" name="tgl_selesai" class="form-control shadow-sm" required></div>
                </div>
              </div>
            </div>
            <hr class="my-4">
            <div class="row">
              <div class="col-md-6">
                <label>Tanggal TTD Kontrak</label>
                <input type="date" name="tgl_ttd" class="form-control shadow-sm">
              </div>
              <div class="col-md-6">
                <label>File Scan Kontrak (PDF)</label>
                <div class="custom-file">
                  <input type="file" name="file_pdf_kontrak" class="custom-file-input" id="customFile" accept=".pdf">
                  <label class="custom-file-label" for="customFile">Pilih file...</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer bg-light border-0">
            <button type="button" class="btn btn-link text-muted" data-dismiss="modal">Batal</button>
            <button type="submit" name="simpan" class="btn bg-navy-custom btn-rounded px-5 shadow">Simpan Kontrak</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script>
  // Menampilkan nama file di input custom file
  $(".custom-file-input").on("change", function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });
</script>
</body>
</html>