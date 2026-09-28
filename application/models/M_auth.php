<?php
/* Filename: M_auth.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class M_auth extends CI_Model {
    public function cek_login($username) {
        $this->db->where('username', $username);
        return $this->db->get('users')->row_array();
    }
}