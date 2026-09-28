<?php
/* Filename: fungsi_helper.php */
defined('BASEPATH') OR exit('No direct script access allowed');

function rupiah($angka) {
    // Jika nilai null atau kosong, set menjadi 0
    $angka = !empty($angka) ? $angka : 0;
    return "Rp " . number_format($angka, 0, ',', '.');
}

function tgl_indo($tanggal) {
    $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}