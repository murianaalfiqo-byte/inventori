<?php
/* Filename: Laporan.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan extends CI_Controller {
    public function __construct() {
        parent::__construct();
        cek_login();
    }
    public function stok() {
        $data['title'] = "Laporan Stok Barang";
        $data['barang'] = $this->M_barang->get_all();
        $this->template->load('laporan/stok', $data);
    }
    public function penjualan() {
        $data['title'] = "Laporan Penjualan";
        $tgl_awal = $this->input->post('tgl_awal') ? $this->input->post('tgl_awal') : date('Y-m-01');
        $tgl_akhir = $this->input->post('tgl_akhir') ? $this->input->post('tgl_akhir') : date('Y-m-d');
        $data['tgl_awal'] = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        $data['penjualan'] = $this->M_laporan->get_penjualan($tgl_awal, $tgl_akhir);
        $this->template->load('laporan/penjualan', $data);
    }
    public function export_pdf_penjualan($tgl_awal, $tgl_akhir) {
        $data['penjualan'] = $this->M_laporan->get_penjualan($tgl_awal, $tgl_akhir);
        $data['tgl_awal'] = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        $this->load->view('laporan/pdf_penjualan', $data);
    }
    public function export_excel_penjualan($tgl_awal, $tgl_akhir) {
        $penjualan = $this->M_laporan->get_penjualan($tgl_awal, $tgl_akhir);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Laporan_Penjualan.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array('No Transaksi', 'Tanggal', 'Kasir', 'Total'));
        foreach ($penjualan as $p) {
            fputcsv($output, array($p['no_transaksi'], $p['tanggal'], $p['nama_kasir'], $p['total']));
        }
        fclose($output);
    }
}   