<?php /* Filename: transaksi/tambah.php */ ?>
<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5>Input Barang</h5>
        <div class="form-group">
          <label>Cari Kode / Scan Barcode</label>
          <input type="text" id="kode_barang" class="form-control" autofocus>
        </div>
        <div class="form-group">
          <label>Atau Pilih Barang</label>
          <select id="pilih_barang" class="form-control">
            <option value="">- Pilih -</option>
            <?php foreach($barang as$b): ?>
              <option value="<?= $b['kode_barang'] ?>"><?= $b['kode_barang'] ?> - <?= $b['nama_barang'] ?> (Sisa: <?=$b['stok'] ?>)</option>
            <?php endforeach; ?>
          </select>
        </div>
        <button id="btn-tambah" class="btn btn-primary btn-block">Tambah ke Keranjang</button>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <h5>No Transaksi: <b><?= $no_transaksi ?></b></h5>
        <table class="table table-bordered" id="tabel-keranjang">
          <thead><tr><th>Kode</th><th>Nama</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th>Aksi</th></tr></thead>
          <tbody></tbody>
        </table>
        <div class="row mt-3">
          <div class="col-md-6 offset-md-6">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Total</label>
              <div class="col-sm-8"><input type="number" id="grand_total" class="form-control" readonly value="0"></div>
            </div>
            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Bayar</label>
              <div class="col-sm-8"><input type="number" id="bayar" class="form-control" value="0"></div>
            </div>
            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Kembali</label>
              <div class="col-sm-8"><input type="number" id="kembali" class="form-control" readonly value="0"></div>
            </div>
            <button id="btn-simpan" class="btn btn-success btn-block">Simpan Transaksi</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let cart = [];

$('#pilih_barang').change(function(){
    $('#kode_barang').val($(this).val());
});

$('#btn-tambah').click(function(){
    let kode = $('#kode_barang').val();
    if(kode == '') return;
    $.ajax({
        url: '<?= site_url("transaksi/get_barang") ?>',
        type: 'POST',
        data: {kode: kode},
        dataType: 'json',
        success: function(res) {
            if(res.status == 'ok') {
                let barang = res.data;
                if(barang.stok < 1) {
                    alert('Stok habis!');
                    return;
                }
                let existIndex = cart.findIndex(x => x.id_barang == barang.id);
                if(existIndex !== -1) {
                    if(cart[existIndex].qty + 1 > barang.stok) {
                        alert('Stok tidak cukup!');
                        return;
                    }
                    cart[existIndex].qty += 1;
                    cart[existIndex].subtotal = cart[existIndex].qty * cart[existIndex].harga;
                } else {
                    cart.push({
                        id_barang: barang.id,
                        kode_barang: barang.kode_barang,
                        nama_barang: barang.nama_barang,
                        harga: parseFloat(barang.harga),
                        qty: 1,
                        subtotal: parseFloat(barang.harga)
                    });
                }
                renderCart();
                $('#kode_barang').val('').focus();
                $('#pilih_barang').val('');
            } else {
                alert('Barang tidak ditemukan!');
            }
        }
    });
});

function renderCart() {
    let tbody = '';
    let total = 0;
    cart.forEach((item, index) => {
        total += item.subtotal;
        tbody += `<tr>
            <td>${item.kode_barang}</td>
            <td>${item.nama_barang}</td>
            <td>${item.harga}</td>
            <td>${item.qty}</td>
            <td>${item.subtotal}</td>
            <td><button class="btn btn-danger btn-sm" onclick="removeItem(${index})">X</button></td>
        </tr>`;
    });
    $('#tabel-keranjang tbody').html(tbody);
    $('#grand_total').val(total);
    kalkulasiKembali();
}

function removeItem(index) {
    cart.splice(index, 1);
    renderCart();
}

$('#bayar').keyup(function(){
    kalkulasiKembali();
});

function kalkulasiKembali() {
    let total = parseFloat($('#grand_total').val());
    let bayar = parseFloat($('#bayar').val());
    let kembali = bayar - total;
    $('#kembali').val(kembali > 0 ? kembali : 0);
}

$('#btn-simpan').click(function(){
    if(cart.length === 0) {
        alert('Keranjang kosong!');
        return;
    }
    let total = parseFloat($('#grand_total').val());
    let bayar = parseFloat($('#bayar').val());
    if(bayar < total) {
        alert('Uang bayar kurang!');
        return;
    }
    $.ajax({
        url: '<?= site_url("transaksi/simpan") ?>',
        type: 'POST',
        data: {
            cart: cart,
            total: total,
            bayar: bayar,
            kembalian: $('#kembali').val()
        },
        dataType: 'json',
        success: function(res) {
            if(res.status == 'ok') {
                alert('Transaksi berhasil disimpan!');
                window.open('<?= site_url("transaksi/cetak/") ?>' + res.id, '_blank');
                location.reload();
            } else {
                alert('Gagal simpan!');
            }
        }
    });
});
</script>