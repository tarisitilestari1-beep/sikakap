<nav class="main-header navbar navbar-expand navbar-white navbar-light border-0 shadow-sm">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars text-primary"></i>
      </a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <span class="nav-link font-weight-bold text-dark text-uppercase">
        SIKAKAP <span class="text-primary">- UPT PPP BULU</span>
      </span>
    </li>
  </ul>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown user-menu">
      <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <i class="fas fa-user-circle text-gray mr-1"></i>
        <span class="d-none d-md-inline font-weight-bold text-muted">
            <?= isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Administrator'; ?>
        </span>
      </a>
      <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow">
        <li class="user-header bg-primary">
          <img src="dist/img/user-default.png" class="img-circle elevation-2" alt="User Image" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=random'">
          <p>
            <?= isset($_SESSION['nama']) ? $_SESSION['nama'] : 'Admin SIKAKAP'; ?>
            <small>UPT PPP Bulu - Tuban</small>
          </p>
        </li>
        <li class="user-footer bg-light d-flex justify-content-between">
          <a href="profil.php" class="btn btn-default btn-flat rounded">Profil</a>
          <a href="logout.php" class="btn btn-danger btn-flat rounded text-white" onclick="return confirm('Yakin ingin keluar?')">
            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
          </a>
        </li>
      </ul>
    </li>
  </ul>
</nav>