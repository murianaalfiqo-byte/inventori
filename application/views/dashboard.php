<?php /* Filename: application/views/dashboard.php */ ?>
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box">
      <div class="inner">
        <h3><?= $total_barang; ?></h3>
        <p>Total Barang</p>
      </div>
      <div class="icon"><i class="fas fa-boxes"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box">
      <div class="inner">
        <h3><?= rupiah($pendapatan_hari_ini); ?></h3>
        <p>Pendapatan Hari Ini</p>
      </div>
      <div class="icon"><i class="fas fa-wallet"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box">
      <div class="inner">
        <h3><?= $total_transaksi; ?></h3>
        <p>Total Transaksi</p>
      </div>
      <div class="icon"><i class="fas fa-shopping-cart"></i></div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box">
      <div class="inner">
        <h3><?= $total_kategori; ?></h3>
        <p>Kategori Barang</p>
      </div>
      <div class="icon"><i class="fas fa-tags"></i></div>
    </div>
  </div>
</div>