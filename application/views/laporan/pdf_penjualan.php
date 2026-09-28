<?php /* Filename: laporan/pdf_penjualan.php */ ?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body onload="window.print()">
    <h2 style="text-align:center;">Laporan Penjualan</h2>
    <p style="text-align:center;">Periode: <?= $tgl_awal ?> s/d <?= $tgl_akhir ?></p>
    <table>
        <tr><th>No</th><th>No Transaksi</th><th>Tanggal</th><th>Kasir</th><th>Total</th></tr>
        <?php $no=1; $total_semua=0; foreach($penjualan as $p): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $p['no_transaksi'] ?></td>
            <td><?= $p['tanggal'] ?></td>
            <td><?= $p['nama_kasir'] ?></td>
            <td><?= $p['total'] ?></td>
        </tr>
        <?php $total_semua += $p['total']; endforeach; ?>
        <tr><th colspan="4" style="text-align:right;">Total Keseluruhan</th><th><?= $total_semua ?></th></tr>
    </table>
</body>
</html>