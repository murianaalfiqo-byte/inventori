<?php /* Filename: application/views/transaksi/index.php */ ?>
<div class="card">
  <div class="card-header">Riwayat Transaksi Penjualan</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No Transaksi</th>
            <th>Tanggal</th>
            <th>Kasir</th>
            <th>Total Tagihan</th>
            <th width="180" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($transaksi as $t): ?>
          <tr>
            <td><code><?= $t['no_transaksi'] ?></code></td>
            <td><?= tgl_indo($t['tanggal']) ?></td>
            <td><?= $t['nama_kasir'] ?></td>
            <td class="font-weight-600 text-success"><?= rupiah($t['total']) ?></td>
            <td class="text-center">
              <a href="<?= site_url('transaksi/detail/'.$t['id']) ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
              <a href="<?= site_url('transaksi/cetak/'.$t['id']) ?>" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-print"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>