<?php
/* Filename: auth_helper.php */
defined('BASEPATH') OR exit('No direct script access allowed');
function cek_login() {
    $ci =& get_instance();
    if (!$ci->session->userdata('id_user')) {
        $ci->session->set_flashdata('error', 'Silakan login terlebih dahulu!');
        redirect('auth');
    }
}