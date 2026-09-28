<?php
/* Filename: Dashboard.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        cek_login();
    }

    public function index() {
        $data['total_barang'] = $this->db->count_all('barang');
        $data['total_kategori'] = $this->db->count_all('kategori');
        $data['total_transaksi'] = $this->db->count_all('transaksi');
        $this->db->select_sum('total');
        $this->db->where('tanggal', date('Y-m-d'));
        $data['pendapatan_hari_ini'] = $this->db->get('transaksi')->row()->total;
        $data['title'] = "Dashboard";
        $this->template->load('dashboard', $data);
    }
}