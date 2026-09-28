<?php
/* Filename: Kategori.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class Kategori extends CI_Controller {
    public function __construct() {
        parent::__construct();
        cek_login();
    }
    public function index() {
        $data['title'] = "Data Kategori";
        $data['kategori'] = $this->M_kategori->get_all();
        $this->template->load('kategori/index', $data);
    }
    public function simpan() {
        $data = array('nama_kategori' => $this->input->post('nama_kategori', TRUE));
        $this->M_kategori->insert($data);
        $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan');
        redirect('kategori');
    }
    public function update() {
        $id = $this->input->post('id', TRUE);
        $data = array('nama_kategori' => $this->input->post('nama_kategori', TRUE));
        $this->M_kategori->update($id, $data);
        $this->session->set_flashdata('success', 'Kategori berhasil diubah');
        redirect('kategori');
    }
    public function hapus($id) {
        $this->M_kategori->delete($id);
        $this->session->set_flashdata('success', 'Kategori berhasil dihapus');
        redirect('kategori');
    }
}