<?php
/* Filename: application/controllers/Setting.php */
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {
    public function __construct() {
        parent::__construct();
        cek_login();
        if($this->session->userdata('role') != 'Admin') {
            $this->session->set_flashdata('error', 'Akses ditolak! Menu ini khusus Admin.');
            redirect('dashboard');
        }
    }

    public function index() {
        $this->load->model('M_setting');
        $data['title'] = "Pengaturan Toko";
        $data['setting'] = $this->M_setting->get_setting();
        $this->template->load('setting/index', $data);
    }

    public function update() {
        $this->load->model('M_setting');
        $data = array(
            'nama_toko' => $this->input->post('nama_toko', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'telp' => $this->input->post('telp', TRUE)
        );
        $this->M_setting->update($data);
        $this->session->set_flashdata('success', 'Pengaturan toko berhasil diperbarui!');
        redirect('setting');
    }
}