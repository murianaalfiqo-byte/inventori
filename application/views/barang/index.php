<?php /* Filename: application/views/barang/index.php */ ?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Manajemen Data Barang</span>
    <a href="<?= site_url('barang/tambah') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus mr-1"></i> Tambah Barang</a>
  </div>
  <div class="card-body">
    <?php if($this->session->flashdata('success')): ?>
      <div class="alert alert-success border-0 bg-success text-white"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th width="50">No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th width="80">Gambar</th>
            <th width="150" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = $page + 1; foreach($barang as $b): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><code><?= $b['kode_barang'] ?></code></td>
            <td class="font-weight-500"><?= $b['nama_barang'] ?></td>
            <td><span class="badge badge-secondary"><?= $b['nama_kategori'] ?></span></td>
            <td><?= rupiah($b['harga']) ?></td>
            <td><span class="badge <?= $b['stok'] > 5 ? 'badge-success' : 'badge-danger' ?>"><?= $b['stok'] ?></span></td>
            <td>
              <?php if($b['gambar']): ?>
                <img src="<?= base_url('assets/uploads/barang/'.$b['gambar']) ?>" width="40" class="rounded" style="object-fit:cover; height:40px;">
              <?php else: ?>
                <span class="text-muted small">-</span>
              <?php endif; ?>
            </td>
            <td class="text-center">
              <a href="<?= site_url('barang/edit/'.$b['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-pen"></i></a>
              <a href="<?= site_url('barang/hapus/'.$b['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus barang ini?')"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="mt-4 d-flex justify-content-end">
        <?= $pagination ?>
    </div>
  </div>
</div>