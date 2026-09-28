<?php /* Filename: application/views/template/sidebar.php */ ?>
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #0b0f19 !important; border-right: 1px solid rgba(255,255,255,0.08);">
    <a href="#" class="brand-link" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding: 20px;">
      <i class="fas fa-layer-group text-primary mr-2"></i>
      <span class="brand-text font-weight-bold" style="font-size: 16px; letter-spacing: -0.5px;">Inventory POS</span>
    </a>
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div class="info px-3">
          <a href="#" class="d-block text-white font-weight-500" style="font-size: 14px;"><?= $this->session->userdata('nama'); ?></a>
          <span class="badge badge-primary mt-1" style="font-size: 11px;"><?= $this->session->userdata('role'); ?></span>
        </div>
      </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?= site_url('dashboard') ?>" class="nav-link">
              <i class="nav-icon fas fa-grid-2"></i><p>Dashboard</p>
            </a>
          </li>
          <?php if($this->session->userdata('role') == 'Admin'): ?>
          <li class="nav-item">
            <a href="<?= site_url('kategori') ?>" class="nav-link">
              <i class="nav-icon fas fa-folder"></i><p>Kategori</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('barang') ?>" class="nav-link">
              <i class="nav-icon fas fa-boxes-stacked"></i><p>Barang</p>
            </a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a href="<?= site_url('transaksi/tambah') ?>" class="nav-link">
              <i class="nav-icon fas fa-cash-register"></i><p>POS / Kasir</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('transaksi') ?>" class="nav-link">
              <i class="nav-icon fas fa-clock-rotate-left"></i><p>Riwayat Transaksi</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('laporan/stok') ?>" class="nav-link">
              <i class="nav-icon fas fa-warehouse"></i><p>Laporan Stok</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= site_url('laporan/penjualan') ?>" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i><p>Laporan Penjualan</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-sm-6">
            <h1 class="m-0"><?= $title; ?></h1>
          </div>
        </div>
      </div>
    </div>
    <div class="content">
      <div class="container-fluid">