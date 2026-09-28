<?php /* Filename: application/views/barang/edit.php */ ?>
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">Form Edit Barang</div>
      <div class="card-body">
        <?= form_open_multipart('barang/update') ?>
          <input type="hidden" name="id" value="<?= $barang['id'] ?>">
          <div class="form-group">
            <label>Kategori</label>
            <select name="id_kategori" class="form-control" required>
              <?php foreach($kategori as $k): ?>
                <option value="<?= $k['id'] ?>" <?= ($k['id'] == $barang['id_kategori']) ? 'selected' : '' ?>><?= $k['nama_kategori'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" class="form-control" value="<?= $barang['kode_barang'] ?>" readonly style="opacity: 0.7;">
          </div>
          <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" class="form-control" value="<?= $barang['nama_barang'] ?>" required>
          </div>
          <div class="row">
            <div class="col-md-6 form-group">
              <label>Harga (Rp)</label>
              <input type="number" name="harga" class="form-control" value="<?= $barang['harga'] ?>" required>
            </div>
            <div class="col-md-6 form-group">
              <label>Stok</label>
              <input type="number" name="stok" class="form-control" value="<?= $barang['stok'] ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label>Ganti Gambar Baru (Opsional)</label>
            <input type="file" name="gambar" class="form-control" style="padding: 6px;">
          </div>
          <div class="d-flex justify-content-between mt-4">
            <a href="<?= site_url('barang') ?>" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Perbarui Barang</button>
          </div>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</div>