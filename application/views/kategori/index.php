<?php /* Filename: application/views/kategori/index.php */ ?>
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>Daftar Kategori Produk</span>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus mr-1"></i> Tambah Kategori</button>
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
            <th>Nama Kategori</th>
            <th width="120" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; foreach($kategori as $k): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td class="font-weight-500"><?= $k['nama_kategori'] ?></td>
            <td class="text-center">
              <a href="<?= site_url('kategori/hapus/'.$k['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus kategori ini?')"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="modalTambah">
  <div class="modal-dialog">
    <div class="modal-content" style="background: #1e293b; color: #fff; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px;">
      <form action="<?= site_url('kategori/simpan') ?>" method="post">
        <div class="modal-header border-0"><h4 class="modal-title font-weight-600">Tambah Kategori</h4></div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control" required autocomplete="off">
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>