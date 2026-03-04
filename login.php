<?php 
session_start();
if(isset($_SESSION['status']) && $_SESSION['status'] == "login"){
    header("location:index.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | SIKAKAP</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <style>
    .login-page {
      background: #f4f6f9; /* Warna abu-abu soft khas AdminLTE */
    }
    .login-box {
      width: 450px; /* Sedikit diperlebar agar logo besar tetap proporsional */
    }
    .card {
      border-top: 5px solid #ffc107; /* Aksen kuning mengikuti warna logo */
      border-radius: 12px;
    }
    .login-logo img {
      max-width: 250px; /* Ukuran logo diperbesar */
      height: auto;
      margin-bottom: 10px;
      /* Efek bayangan halus agar logo lebih 'muncul' */
      filter: drop-shadow(0px 6px 8px rgba(0,0,0,0.15));
    }
    .btn-warning {
      font-weight: bold;
      letter-spacing: 0.5px;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  
  <div class="login-logo text-center">
    <img src="dist/img/LOGO_SIKAKAP.PNG" alt="Logo SIKAKAP">
  </div>
  
  <div class="card shadow-lg">
    <div class="card-body login-card-body">
      <p class="login-box-msg text-bold">LOGIN PETUGAS</p>

      <?php if(isset($_GET['pesan']) && $_GET['pesan']=="gagal"): ?>
        <div class="alert alert-danger text-center text-sm">Username atau password salah!</div>
      <?php endif; ?>

      <form action="proses_login.php" method="post">
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-4">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" name="login" class="btn btn-warning btn-block py-2">MASUK KE SISTEM</button>
          </div>
        </div>
      </form>
    </div>
    </div>
  <p class="text-center mt-4 text-muted text-sm">UPT PPP BULU - Dinas Kelautan dan Perikanan</p>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>