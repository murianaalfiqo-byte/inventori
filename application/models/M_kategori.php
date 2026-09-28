<?php
/* Filename: M_kategori.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class M_kategori extends CI_Model {
    public function get_all() {
        return $this->db->get('kategori')->result_array();
    }
    public function insert($data) {
        return $this->db->insert('kategori', $data);
    }
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('kategori', $data);
    }
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('kategori');
    }
}