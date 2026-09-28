<?php /* Filename: application/views/laporan/penjualan.php */ ?>
<div class="card mb-4">
  <div class="card-body">
    <form action="" method="post" class="form-inline flex-wrap gap-2">
      <label class="mr-2">Periode Filter:</label>
      <input type="date" name="tgl_awal" class="form-control mr-2 mb-2" value="<?= $tgl_awal ?>">
      <label class="mr-2 mb-2">s/d</label>
      <input type="date" name="tgl_akhir" class="form-control mr-2 mb-2" value="<?= $tgl_akhir ?>">
      <button type="submit" class="btn btn-primary mr-2 mb-2"><i class="fas fa-filter mr-1"></i> Filter Data</button>
      <a href="<?= site_url('laporan/export_pdf_penjualan/'.$tgl_awal.'/'.$tgl_akhir) ?>" class="btn btn-danger mr-2 mb-2" target="_blank"><i class="fas fa-print mr-1"></i> Cetak / PDF</a>
      <a href="<?= site_url('laporan/export_excel_penjualan/'.$tgl_awal.'/'.$tgl_akhir) ?>" class="btn btn-success mb-2" target="_blank"><i class="fas fa-file-excel mr-1"></i> Export Excel</a>
    </form>
  </div>
</div>
<div class="card">
  <div class="card-header">Rekapitulasi Penjualan</div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="50">No</th>
            <th>No Transaksi</th>
            <th>Tanggal</th>
            <th>Kasir</th>
            <th>Total Pendapatan</th>
          </tr>
        </thead>
        <tbody> 
          <?php $no=1; $total_semua=0; foreach($penjualan as $p): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><code><?= $p['no_transaksi'] ?></code></td>
            <td><?= tgl_indo($p['tanggal']) ?></td>
            <td><?= $p['nama_kasir'] ?></td>
            <td class="font-weight-600 text-success"><?= rupiah($p['total']) ?></td>
          </tr>
          <?php $total_semua += $p['total']; endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4" class="text-right">Total Keseluruhan Pendapatan</th>
            <th class="text-success font-weight-bold" style="font-size: 16px;"><?= rupiah($total_semua) ?></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>