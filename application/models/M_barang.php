<?php
/* Filename: M_barang.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class M_barang extends CI_Model {
    public function get_all($limit = NULL, $start = NULL) {
        $this->db->select('barang.*, kategori.nama_kategori');
        $this->db->from('barang');
        $this->db->join('kategori', 'kategori.id = barang.id_kategori');
        if ($limit != NULL || $start != NULL) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result_array();
    }
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('barang')->row_array();
    }
    public function get_by_kode($kode) {
        $this->db->where('kode_barang', $kode);
        return $this->db->get('barang')->row_array();
    }
    public function count_all() {
        return $this->db->count_all('barang');
    }
    public function insert($data) {
        return $this->db->insert('barang', $data);
    }
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('barang', $data);
    }
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('barang');
    }
}