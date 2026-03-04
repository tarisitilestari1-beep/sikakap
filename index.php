<?php
include "koneksi.php";


session_start();
if($_SESSION['status'] != "login"){
    header("location:login.php?pesan=belum_login");
    exit();
}


// Hitung Total Aset
$query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM ms_aset");
$data_total = mysqli_fetch_assoc($query_total);

// Hitung Aset Terisi
$query_terisi = mysqli_query($koneksi, "SELECT COUNT(DISTINCT id_aset) as total FROM tr_kontrak WHERE status_sewa = 'Aktif'");
$data_terisi = mysqli_fetch_assoc($query_terisi);

// Hitung Aset Kosong
$aset_kosong = $data_total['total'] - $data_terisi['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIKAKAP | Dashboard</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  
  <style>
    .main-sidebar { background-color: #1e272e !important; }
    .content-wrapper { background-color: #f4f6f9; }
    .small-box { border-radius: 15px !important; border: none; transition: 0.3s; }
    .small-box:hover { transform: translateY(-5px); }
    .bg-custom-info { background: linear-gradient(45deg, #0984e3, #74b9ff) !important; color: white; }
    .bg-custom-success { background: linear-gradient(45deg, #00b894, #55efc4) !important; color: white; }
    .bg-custom-warning { background: linear-gradient(45deg, #f39c12, #f1c40f) !important; color: white !important; }
    .denah-container { position: relative; width: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); background: #fff; }
    .denah-img { width: 100%; height: auto; display: block; margin: 0 auto; }
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
        <h1 class="m-0 font-weight-bold text-gray-dark">Ringkasan Aset</h1>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        
        <div class="row">
          <div class="col-lg-4 col-6">
            <div class="small-box bg-custom-info shadow">
              <div class="inner">
                <h3><?php echo $data_total['total']; ?></h3>
                <p>Total Aset</p>
              </div>
              <div class="icon"><i class="fas fa-database"></i></div>
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="small-box bg-custom-success shadow">
              <div class="inner">
                <h3><?php echo $data_terisi['total']; ?></h3>
                <p>Aset Terisi</p>
              </div>
              <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
            </div>
          </div>
          <div class="col-lg-4 col-12">
            <div class="small-box bg-custom-warning shadow">
              <div class="inner">
                <h3><?php echo $aset_kosong; ?></h3>
                <p>Aset Kosong</p>
              </div>
              <div class="icon"><i class="fas fa-door-open"></i></div>
            </div>
          </div>
        </div> 

        <div class="row mt-3">
          <div class="col-md-12">
            <div class="card card-outline card-primary shadow">
              <div class="card-header border-0 bg-transparent">
                <h3 class="card-title font-weight-bold"><i class="fas fa-map-marked-alt mr-2 text-primary"></i>Visualisasi Denah Kawasan</h3>
              </div>
              <div class="card-body p-3 text-center">
                <div class="denah-container">
                  <img src="dist/img/denah_sikakap.JPEG" class="denah-img" alt="Denah Kawasan">
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
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