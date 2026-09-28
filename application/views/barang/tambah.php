<?php /* Filename: application/views/barang/tambah.php */ ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">Form Tambah Barang Baru</div>
      <div class="card-body">
        <?= validation_errors('<div class="alert alert-danger border-0 bg-danger text-white">','</div>') ?>
        <?= form_open_multipart('barang/simpan') ?>
          <div class="form-group">
            <label>Kategori</label>
            <select name="id_kategori" class="form-control" required>
              <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id'] ?>"><?= $k['nama_kategori'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" class="form-control" required autocomplete="off">
          </div>
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" required autocomplete="off">
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Harga (Rp)</label>
              <input type="number" name="harga" class="form-control" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Stok Awal</label>
              <input type="number" name="stok" class="form-control" required>
            </div>
          </div>
          <div class="form-group">
            <label>Gambar Produk</label>
            <input type="file" name="gambar" class="form-control" style="padding: 6px;">
          </div>
          <div class="d-flex justify-content-between mt-4">
            <a href="<?= site_url('barang') ?>" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Barang</button>
          </div>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</div>