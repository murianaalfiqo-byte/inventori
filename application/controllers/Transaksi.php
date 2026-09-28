<?php
/* Filename: Transaksi.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends CI_Controller {
    public function __construct() {
        parent::__construct();
        cek_login();
    }
    public function index() {
        $data['title'] = "Data Transaksi";
        $data['transaksi'] = $this->M_transaksi->get_all();
        $this->template->load('transaksi/index', $data);
    }
    public function tambah() {
        $data['title'] = "POS Transaksi";
        $data['barang'] = $this->M_barang->get_all();
        $data['no_transaksi'] = $this->M_transaksi->get_no_transaksi();
        $this->template->load('transaksi/tambah', $data);
    }
    public function get_barang() {
        $kode = $this->input->post('kode');
        $barang = $this->M_barang->get_by_kode($kode);
        if($barang) {
            echo json_encode(['status' => 'ok', 'data' => $barang]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
    public function simpan() {
        $cart = $this->input->post('cart');
        $bayar = $this->input->post('bayar');
        $total = $this->input->post('total');
        $kembalian = $this->input->post('kembalian');
        
        $data_transaksi = array(
            'no_transaksi' => $this->M_transaksi->get_no_transaksi(),
            'tanggal' => date('Y-m-d'),
            'id_user' => $this->session->userdata('id_user'),
            'total' => $total,
            'bayar' => $bayar,
            'kembalian' => $kembalian
        );
        $id_transaksi = $this->M_transaksi->insert_transaksi($data_transaksi, $cart);
        if($id_transaksi) {
            echo json_encode(['status' => 'ok', 'id' => $id_transaksi]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
    public function detail($id) {
        $data['title'] = "Detail Transaksi";
        $data['transaksi'] = $this->M_transaksi->get_by_id($id);
        $data['detail'] = $this->M_transaksi->get_detail($id);
        $this->template->load('transaksi/detail', $data);
    }
    public function cetak($id) {
        $data['transaksi'] = $this->M_transaksi->get_by_id($id);
        $data['detail'] = $this->M_transaksi->get_detail($id);
        $this->load->view('transaksi/struk', $data);
    }
}