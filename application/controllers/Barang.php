<?php
/* Filename: Barang.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class Barang extends CI_Controller {
    public function __construct() {
        parent::__construct();
        cek_login();
    }
    public function index() {
        $config['base_url'] = site_url('barang/index');
        $config['total_rows'] = $this->M_barang->count_all();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        
        $config['full_tag_open'] = '<ul class="pagination pagination-sm m-0 float-right">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);
        $data['page'] = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['barang'] = $this->M_barang->get_all($config['per_page'], $data['page']);
        $data['pagination'] = $this->pagination->create_links();
        $data['title'] = "Data Barang";
        $this->template->load('barang/index', $data);
    }
    public function tambah() {
        $data['title'] = "Tambah Barang";
        $data['kategori'] = $this->M_kategori->get_all();
        $this->template->load('barang/tambah', $data);
    }
    public function simpan() {
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|is_unique[barang.kode_barang]');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->tambah();
        } else {
            $gambar = NULL;
            if (!empty($_FILES['gambar']['name'])) {
                $config['upload_path'] = './assets/uploads/barang/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 2048;
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('gambar')) {
                    $upload_data = $this->upload->data();
                    $gambar = $upload_data['file_name'];
                }
            }
            $data = array(
                'id_kategori' => $this->input->post('id_kategori', TRUE),
                'kode_barang' => $this->input->post('kode_barang', TRUE),
                'nama_barang' => $this->input->post('nama_barang', TRUE),
                'harga' => $this->input->post('harga', TRUE),
                'stok' => $this->input->post('stok', TRUE),
                'gambar' => $gambar
            );
            $this->M_barang->insert($data);
            $this->session->set_flashdata('success', 'Barang berhasil ditambahkan');
            redirect('barang');
        }
    }
    public function edit($id) {
        $data['title'] = "Edit Barang";
        $data['barang'] = $this->M_barang->get_by_id($id);
        $data['kategori'] = $this->M_kategori->get_all();
        $this->template->load('barang/edit', $data);
    }
    public function update() {
        $id = $this->input->post('id', TRUE);
        $data = array(
            'id_kategori' => $this->input->post('id_kategori', TRUE),
            'nama_barang' => $this->input->post('nama_barang', TRUE),
            'harga' => $this->input->post('harga', TRUE),
            'stok' => $this->input->post('stok', TRUE)
        );
        if (!empty($_FILES['gambar']['name'])) {
            $config['upload_path'] = './assets/uploads/barang/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('gambar')) {
                $upload_data = $this->upload->data();
                $data['gambar'] = $upload_data['file_name'];
            }
        }
        $this->M_barang->update($id, $data);
        $this->session->set_flashdata('success', 'Barang berhasil diupdate');
        redirect('barang');
    }
    public function hapus($id) {
        $this->M_barang->delete($id);
        $this->session->set_flashdata('success', 'Barang berhasil dihapus');
        redirect('barang');
    }
}