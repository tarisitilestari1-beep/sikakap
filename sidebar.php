<?php
// Logika untuk mendeteksi halaman aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index.php" class="brand-link text-center" style="background: #1a2a3a; border-bottom: 1px solid #2c3e50;">
      <span class="brand-text font-weight-bold text-warning">SIKAKAP <small class="text-white">v1.0</small></span>
    </a>

    <div class="sidebar text-sm">
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu">
          
          <li class="nav-item">
            <a href="index.php" class="nav-link <?= ($current_page == 'index.php') ? 'active rounded-pill' : ''; ?>">
              <i class="nav-icon fas fa-th-large"></i>
              <p>Dashboard Center</p>
            </a>
          </li>

          <li class="nav-header">MANAJEMEN DATA</li>
          
          <li class="nav-item">
            <a href="kategori_aset.php" class="nav-link <?= ($current_page == 'kategori_aset.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-building text-info"></i>
              <p>Kategori Aset (Induk)</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="setting_unit.php" class="nav-link <?= ($current_page == 'setting_unit.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-list-ol text-info"></i>
              <p>Setting Nomor Unit</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="master_aset.php" class="nav-link <?= ($current_page == 'master_aset.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-boxes text-info"></i>
              <p>Master Unit Aset</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="pengguna_jasa.php" class="nav-link <?= ($current_page == 'pengguna_jasa.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-user-tag text-success"></i>
              <p>Pengguna Jasa</p>
            </a>
          </li>

          <li class="nav-header">ADMINISTRASI</li>
          
          <li class="nav-item">
            <a href="kontrak.php" class="nav-link <?= ($current_page == 'kontrak.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-file-contract text-warning"></i>
              <p>Dokumen Perjanjian</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="pembayaran.php" class="nav-link <?= ($current_page == 'pembayaran.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-money-bill-wave text-warning"></i>
              <p>Retribusi & Tagihan</p>
            </a>
          </li>

          <li class="nav-header">MONITORING & SISTEM</li>

          <li class="nav-item">
            <a href="peta_spasial.php" class="nav-link <?= ($current_page == 'peta_spasial.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-map-marked-alt text-danger"></i>
              <p>Peta Spasial (GIS)</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="manajemen_user.php" class="nav-link <?= ($current_page == 'manajemen_user.php') ? 'active' : ''; ?>">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Manajemen User</p>
            </a>
          </li>

          <li class="nav-item mt-3">
            <a href="logout.php" class="nav-link bg-danger shadow-sm">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Keluar Aplikasi</p>
            </a>
          </li>

        </ul>
      </nav>
    </div>
</aside>