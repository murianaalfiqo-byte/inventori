<?php /* Filename: laporan/stok.php */ ?>
<div class="card">
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th><th>Harga</th><th>Stok Tersedia</th></tr>
      </thead>
      <tbody>
        <?php $no=1; foreach($barang as $b): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $b['kode_barang'] ?></td>
          <td><?= $b['nama_barang'] ?></td>
          <td><?= $b['nama_kategori'] ?></td>
          <td><?= rupiah($b['harga']) ?></td>
          <td><?= $b['stok'] ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table><?php /* Filename: application/views/laporan/stok.php */ ?>
<div class="card">
  <div class="card-header">Laporan Stok Gudang Barang</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="50">No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Harga Satuan</th>
            <th>Stok Tersedia</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; foreach($barang as $b): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><code><?= $b['kode_barang'] ?></code></td>
            <td class="font-weight-500"><?= $b['nama_barang'] ?></td>
            <td><span class="badge badge-secondary"><?= $b['nama_kategori'] ?></span></td>
            <td><?= rupiah($b['harga']) ?></td>
            <td><span class="badge <?= $b['stok'] > 5 ? 'badge-success' : 'badge-danger' ?>"><?= $b['stok'] ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
  </div>
</div>