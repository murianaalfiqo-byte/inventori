<?php
/* Filename: M_transaksi.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class M_transaksi extends CI_Model {
    public function get_all() {
        $this->db->select('transaksi.*, users.nama as nama_kasir');
        $this->db->from('transaksi');
        $this->db->join('users', 'users.id = transaksi.id_user');
        $this->db->order_by('transaksi.id', 'DESC');
        return $this->db->get()->result_array();
    }
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('transaksi')->row_array();
    }
    public function get_detail($id_transaksi) {
        $this->db->select('detail_transaksi.*, barang.nama_barang, barang.kode_barang');
        $this->db->from('detail_transaksi');
        $this->db->join('barang', 'barang.id = detail_transaksi.id_barang');
        $this->db->where('id_transaksi', $id_transaksi);
        return $this->db->get()->result_array();
    }
    public function insert_transaksi($data_transaksi, $data_detail) {
        $this->db->trans_start();
        $this->db->insert('transaksi', $data_transaksi);
        $id_transaksi = $this->db->insert_id();
        
        foreach ($data_detail as $detail) {
            $detail['id_transaksi'] = $id_transaksi;
            $this->db->insert('detail_transaksi', $detail);
            
            $this->db->set('stok', 'stok-'.$detail['qty'], FALSE);
            $this->db->where('id', $detail['id_barang']);
            $this->db->update('barang');
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE ? $id_transaksi : FALSE;
    }
    public function get_no_transaksi() {
        $tgl = date('Ymd');
        $this->db->select('RIGHT(no_transaksi,4) as max_id', FALSE);
        $this->db->where('DATE(tanggal)', date('Y-m-d'));
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('transaksi');
        if ($query->num_rows() > 0) {
            $data = $query->row();
            $id_max = (int) $data->max_id;
            $id_new = $id_max + 1;
        } else {
            $id_new = 1;
        }
        return 'TRX' . $tgl . sprintf("%04s", $id_new);
    }
}