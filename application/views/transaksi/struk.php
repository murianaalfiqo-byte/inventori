<?php /* Filename: application/views/transaksi/struk.php */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Struk - <?= $transaksi['no_transaksi'] ?></title>
    <style>
        body { font-family: monospace; font-size: 11px; width: 58mm; margin: 0 auto; padding: 5px; }
        .center { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 3px 0; }
        .right { text-align: right; }
        @media print {
            body { width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="center">
        <h4>INVENTORY POS</h4>
        <p>No: <?= $transaksi['no_transaksi'] ?><br>Tgl: <?= $transaksi['tanggal'] ?></p>
    </div>
    <hr>
    <table>
        <?php foreach($detail as $d): ?>
        <tr>
            <td colspan="3"><?= $d['nama_barang'] ?></td>
        </tr>
        <tr>
            <td><?= $d['qty'] ?>x @<?= number_format($d['harga'],0,',','.') ?></td>
            <td></td>
            <td class="right"><?= number_format($d['subtotal'],0,',','.') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <hr>
    <table>
        <tr><td>Total</td><td class="right"><?= number_format($transaksi['total'],0,',','.') ?></td></tr>
        <tr><td>Bayar</td><td class="right"><?= number_format($transaksi['bayar'],0,',','.') ?></td></tr>
        <tr><td>Kembali</td><td class="right"><?= number_format($transaksi['kembalian'],0,',','.') ?></td></tr>
    </table>
    <hr>
    <div class="center"><p>Terima Kasih</p></div>
    <div class="center no-print" style="margin-top: 15px;">
        <button onclick="window.print()" style="padding: 5px 15px; cursor: pointer;">Cetak Ulang</button>
    </div>
</body>
</html>