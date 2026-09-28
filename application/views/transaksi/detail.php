<?php /* Filename: application/views/transaksi/detail.php */ ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Detail Transaksi: <b><?= $transaksi['no_transaksi'] ?></b></span>
        <span class="text-muted small"><?= tgl_indo($transaksi['tanggal']) ?></span>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($detail as $d): ?>
              <tr>
                <td><code><?= $d['kode_barang'] ?></code></td>
                <td><?= $d['nama_barang'] ?></td>
                <td><?= rupiah($d['harga']) ?></td>
                <td><?= $d['qty'] ?></td>
                <td><?= rupiah($d['subtotal']) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr><th colspan="4" class="text-right">Total Tagihan</th><th><?= rupiah($transaksi['total']) ?></th></tr>
              <tr><th colspan="4" class="text-right">Bayar</th><th><?= rupiah($transaksi['bayar']) ?></th></tr>
              <tr><th colspan="4" class="text-right">Kembalian</th><th><?= rupiah($transaksi['kembalian']) ?></th></tr>
            </tfoot>
          </table>
        </div>
        <div class="mt-4">
          <a href="<?= site_url('transaksi') ?>" class="btn btn-secondary btn-sm">Kembali ke Riwayat</a>
        </div>
      </div>
    </div>
  </div>
</div>