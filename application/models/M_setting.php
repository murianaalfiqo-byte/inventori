<?php
/* Filename: application/models/M_setting.php */
defined('BASEPATH') OR exit('No direct script access allowed');

class M_setting extends CI_Model {
    public function get_setting() {
        return $this->db->get('setting')->row_array();
    }
    public function update($data) {
        $this->db->where('id', 1);
        return $this->db->update('setting', $data);
    }
}