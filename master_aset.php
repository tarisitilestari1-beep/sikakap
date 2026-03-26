<?php
include "koneksi.php";

// Query Data Aset lengkap dengan Kategori dan Nama Unit
$sql = "SELECT a.*, u.nama_unit_setting, k.nama_kategori 
        FROM master_aset a
        JOIN ms_unit_setting u ON a.id_unit_setting = u.id_unit_setting
        JOIN ms_kategori_aset k ON u.id_kategori = k.id_kategori
        ORDER BY a.id_aset DESC";
$query = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Master Aset</title>
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .table td { vertical-align: middle !important; padding: 12px !important; }
    .nama-unit { font-size: 1.05rem; font-weight: 800; color: #1a1a1a; display: block; line-height: 1.2; }
    .nama-kategori { font-size: 0.7rem; font-weight: 700; color: #007bff; text-transform: uppercase; letter-spacing: 0.5px; }
    .img-aset { width: 65px; height: 65px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .badge-status { padding: 6px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: 700; }
    .btn-group .btn { margin: 0 2px; border-radius: 4px !important; }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <?php include "sidebar.php"; ?> 

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-3 mt-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold text-dark">Data Master Aset</h1>
          </div>
          <div class="col-sm-6 text-right">
            <button class="btn btn-primary shadow-sm px-4" data-toggle="modal" data-target="#modal-tambah">
              <i class="fas fa-plus-circle mr-1"></i> Tambah Aset
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
                    <th width="50" class="text-center">No</th>
                    <th width="80">Foto</th>
                    <th>Unit & Kategori</th>
                    <th>Luas & Harga</th>
                    <th class="text-center">Status & Kondisi</th>
                    <th class="text-center" width="160">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $no = 1;
                  while($d = mysqli_fetch_array($query)){ 
                    // Logika Warna Badge Status
                    $st_color = ($d['status_aset'] == 'Tersedia') ? 'badge-success' : (($d['status_aset'] == 'Tersewa') ? 'badge-danger' : 'badge-warning');
                  ?>
                  <tr>
                    <td class="text-center text-muted"><?= $no++; ?></td>
                    <td>
                      <?php if(!empty($d['foto_aset']) && file_exists("uploads/".$d['foto_aset'])): ?>
                        <img src="uploads/<?= $d['foto_aset']; ?>" class="img-aset">
                      <?php else: ?>
                        <div class="bg-light text-center rounded border" style="width:65px; height:65px; line-height:65px;"><i class="fas fa-image text-muted"></i></div>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="nama-kategori"><?= $d['nama_kategori']; ?></span>
                      <span class="nama-unit"><?= $d['nama_unit_setting']; ?></span>
                    </td>
                    <td>
                      <span class="text-dark font-weight-bold"><?= $d['luas_m2']; ?> m²</span><br>
                      <span class="text-success small font-weight-bold">Rp <?= number_format($d['harga_sewa'], 0, ',', '.'); ?></span>
                    </td>
                    <td class="text-center">
                      <span class="badge badge-status <?= $st_color; ?>"><?= strtoupper($d['status_aset']); ?></span><br>
                      <small class="text-muted font-italic"><?= $d['kondisi']; ?></small>
                    </td>
                    <td class="text-center">
                      <div class="btn-group">
                        <button class="btn btn-sm btn-warning text-white btn-edit" 
                                title="Edit Data"
                                data-toggle="modal" data-target="#modal-edit"
                                data-id="<?= $d['id_aset']; ?>"
                                data-unit="<?= $d['id_unit_setting']; ?>"
                                data-luas="<?= $d['luas_m2']; ?>"
                                data-harga="<?= $d['harga_sewa']; ?>"
                                data-kondisi="<?= $d['kondisi']; ?>"
                                data-status="<?= $d['status_aset']; ?>"
                                data-ket="<?= $d['keterangan']; ?>">
                          <i class="fas fa-edit"></i>
                        </button>

                        <a href="plotting_gis.php?id=<?= $d['id_aset']; ?>" class="btn btn-sm btn-info shadow-sm" title="Plotting Poligon">
                          <i class="fas fa-map-marker-alt"></i>
                        </a>

                        <a href="proses_aset.php?hapus=<?= $d['id_aset']; ?>" class="btn btn-sm btn-danger shadow-sm" title="Hapus" onclick="return confirm('Yakin ingin menghapus aset ini?')">
                          <i class="fas fa-trash"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="proses_aset.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header bg-primary text-white border-0">
          <h4 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Tambah Aset Baru</h4>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body px-4">
          <div class="form-group">
            <label>Nomor Unit / Area</label>
            <select name="id_unit_setting" class="form-control select2" required>
              <option value="">-- Pilih Unit --</option>
              <?php
              $u_query = mysqli_query($koneksi, "SELECT u.*, k.nama_kategori FROM ms_unit_setting u JOIN ms_kategori_aset k ON u.id_kategori = k.id_kategori ORDER BY u.nama_unit_setting ASC");
              while($u = mysqli_fetch_array($u_query)){
                  echo "<option value='".$u['id_unit_setting']."'>[".$u['nama_kategori']."] - ".$u['nama_unit_setting']."</option>";
              }
              ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Luas (m²)</label>
              <input type="number" step="0.01" name="luas_m2" class="form-control" placeholder="Contoh: 150.50" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Harga Sewa (Rp)</label>
              <input type="number" name="harga_sewa" class="form-control" placeholder="Nilai angka saja" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Kondisi</label>
              <select name="kondisi" class="form-control">
                <option value="Baik">Baik</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Status Ketersediaan</label>
              <select name="status_aset" class="form-control">
                <option value="Tersedia">Tersedia</option>
                <option value="Tersewa">Tersewa</option>
                <option value="Perbaikan">Perbaikan</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Unggah Foto Aset</label>
            <input type="file" name="foto_aset" class="form-control-file border p-1 rounded w-100">
          </div>
          <div class="form-group">
            <label>Keterangan Tambahan</label>
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan lokasi atau detail aset..."></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" name="simpan_aset" class="btn btn-primary px-4 shadow">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit">
  <div class="modal-dialog modal-lg">
    <div class="modal-content shadow-lg border-0">
      <form action="proses_aset.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header bg-warning border-0">
          <h4 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i>Update Data Master Aset</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body px-4">
          <input type="hidden" name="id_aset" id="edit_id">
          
          <div class="form-group">
            <label>Nomor Unit / Area</label>
            <select name="id_unit_setting" id="edit_unit" class="form-control" required>
              <?php
              $u_edit = mysqli_query($koneksi, "SELECT u.*, k.nama_kategori FROM ms_unit_setting u JOIN ms_kategori_aset k ON u.id_kategori = k.id_kategori");
              while($ue = mysqli_fetch_array($u_edit)){
                  echo "<option value='".$ue['id_unit_setting']."'>[".$ue['nama_kategori']."] - ".$ue['nama_unit_setting']."</option>";
              }
              ?>
            </select>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Luas (m²)</label>
              <input type="number" step="0.01" name="luas_m2" id="edit_luas" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Harga Sewa (Rp)</label>
              <input type="number" name="harga_sewa" id="edit_harga" class="form-control" required>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Kondisi</label>
              <select name="kondisi" id="edit_kondisi" class="form-control">
                <option value="Baik">Baik</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
              </select>
            </div>
            <div class="col-md-6 form-group">
              <label>Status</label>
              <select name="status_aset" id="edit_status" class="form-control">
                <option value="Tersedia">Tersedia</option>
                <option value="Tersewa">Tersewa</option>
                <option value="Perbaikan">Perbaikan</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label>Ganti Foto <small class="text-danger">(Kosongkan jika tidak ingin mengubah foto)</small></label>
            <input type="file" name="foto_aset" class="form-control-file border p-1 rounded w-100">
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" id="edit_ket" class="form-control" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light justify-content-between">
          <button type="button" class="btn btn-default shadow-sm" data-dismiss="modal">Batal</button>
          <button type="submit" name="update_aset" class="btn btn-warning px-4 font-weight-bold shadow">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<script>
// LOGIKA JAVASCRIPT UNTUK MENGAMBIL DATA KE MODAL EDIT
$(document).on("click", ".btn-edit", function () {
     // Ambil data dari atribut tombol yang diklik
     var id      = $(this).data('id');
     var unit    = $(this).data('unit');
     var luas    = $(this).data('luas');
     var harga   = $(this).data('harga');
     var kondisi = $(this).data('kondisi');
     var status  = $(this).data('status');
     var ket     = $(this).data('ket');
     
     // Masukkan data ke dalam inputan di Modal Edit
     $("#edit_id").val(id);
     $("#edit_unit").val(unit);
     $("#edit_luas").val(luas);
     $("#edit_harga").val(harga);
     $("#edit_kondisi").val(kondisi);
     $("#edit_status").val(status);
     $("#edit_ket").val(ket);
});
</script>
</body>
</html>