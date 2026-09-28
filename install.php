<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(300);

$success = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_user = $_POST['db_user'] ?? 'root';
    $db_pass = $_POST['db_pass'] ?? '';
    $db_name = $_POST['db_name'] ?? 'inventory_pos';
    
    $conn = new mysqli($db_host, $db_user, $db_pass);
    if ($conn->connect_error) {
        $errors[] = "Koneksi gagal: " . $conn->connect_error;
    } else {
        $conn->query("CREATE DATABASE IF NOT EXISTS $db_name");
        $conn->select_db($db_name);
        
        $folders = [
            'application/config',
            'application/controllers',
            'application/models',
            'application/views/template',
            'application/views/auth',
            'application/views/barang',
            'application/views/kategori',
            'application/views/transaksi',
            'application/views/laporan',
            'assets/css',
            'assets/js',
            'assets/uploads/barang'
        ];
        
        foreach ($folders as $folder) {
            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
                $success[] = "Folder $folder dibuat";
            }
        }
        
        $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            nama VARCHAR(100) NOT NULL,
            level ENUM('admin','kasir','manager') DEFAULT 'kasir',
            status TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS kategori (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama VARCHAR(100) NOT NULL,
            keterangan TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS barang (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kode VARCHAR(50) UNIQUE NOT NULL,
            nama VARCHAR(200) NOT NULL,
            kategori_id INT,
            harga_beli DECIMAL(12,2) DEFAULT 0,
            harga_jual DECIMAL(12,2) DEFAULT 0,
            stok INT DEFAULT 0,
            satuan VARCHAR(20) DEFAULT 'pcs',
            gambar VARCHAR(255),
            status TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        );
        
        CREATE TABLE IF NOT EXISTS transaksi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            no_transaksi VARCHAR(50) UNIQUE NOT NULL,
            tanggal DATE NOT NULL,
            user_id INT,
            total DECIMAL(12,2) DEFAULT 0,
            bayar DECIMAL(12,2) DEFAULT 0,
            kembalian DECIMAL(12,2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id)
        );
        
        CREATE TABLE IF NOT EXISTS detail_transaksi (
            id INT AUTO_INCREMENT PRIMARY KEY,
            transaksi_id INT,
            barang_id INT,
            qty INT DEFAULT 1,
            harga DECIMAL(12,2) DEFAULT 0,
            subtotal DECIMAL(12,2) DEFAULT 0,
            FOREIGN KEY (transaksi_id) REFERENCES transaksi(id),
            FOREIGN KEY (barang_id) REFERENCES barang(id)
        );
        
        INSERT INTO users (username, password, nama, level) VALUES 
        ('admin', '".password_hash('admin123', PASSWORD_DEFAULT)."', 'Administrator', 'admin'),
        ('kasir', '".password_hash('kasir123', PASSWORD_DEFAULT)."', 'Kasir 1', 'kasir');
        
        INSERT INTO kategori (nama, keterangan) VALUES 
        ('Elektronik', 'Barang elektronik umum'),
        ('Peralatan Kantor', 'ATK dan peralatan kantor');
        
        INSERT INTO barang (kode, nama, kategori_id, harga_beli, harga_jual, stok, satuan) VALUES 
        ('BRG001', 'Mouse Logitech', 1, 50000, 75000, 20, 'pcs'),
        ('BRG002', 'Keyboard Mechanical', 1, 150000, 200000, 15, 'pcs'),
        ('BRG003', 'Pulpen Standard', 2, 2000, 3500, 100, 'pcs'),
        ('BRG004', 'Buku Tulis', 2, 5000, 8000, 50, 'pcs'),
        ('BRG005', 'Monitor 24 inch', 1, 1200000, 1500000, 5, 'unit');
        ";
        
        if ($conn->multi_query($sql)) {
            while ($conn->next_result()) {}
            $success[] = "Database dan tabel berhasil dibuat";
        } else {
            $errors[] = "Error SQL: " . $conn->error;
        }
        
        // Config files
        file_put_contents('application/config/database.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
\$active_group = 'default';
\$query_builder = TRUE;
\$db['default'] = array(
    'dsn'   => '',
    'hostname' => '$db_host',
    'username' => '$db_user',
    'password' => '$db_pass',
    'database' => '$db_name',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);");
        
        file_put_contents('application/config/autoload.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
\$autoload['packages'] = array();
\$autoload['libraries'] = array('database', 'session', 'form_validation', 'pagination');
\$autoload['drivers'] = array();
\$autoload['helper'] = array('url', 'form', 'file', 'fungsi', 'auth');
\$autoload['config'] = array();
\$autoload['language'] = array();
\$autoload['model'] = array();");
        
        file_put_contents('application/config/config.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
\$config['base_url'] = ((isset(\$_SERVER['HTTPS']) && \$_SERVER['HTTPS'] == 'on') ? 'https' : 'http');
\$config['base_url'] .= '://'.\$_SERVER['HTTP_HOST'];
\$config['base_url'] .= str_replace(basename(\$_SERVER['SCRIPT_NAME']), '', \$_SERVER['SCRIPT_NAME']);
\$config['index_page'] = '';
\$config['uri_protocol'] = 'REQUEST_URI';
\$config['url_suffix'] = '';
\$config['language'] = 'english';
\$config['charset'] = 'UTF-8';
\$config['enable_hooks'] = FALSE;
\$config['subclass_prefix'] = 'MY_';
\$config['composer_autoload'] = FALSE;
\$config['permitted_uri_chars'] = 'a-z 0-9~%.:_-';
\$config['allow_get_array'] = TRUE;
\$config['enable_query_strings'] = FALSE;
\$config['encryption_key'] = 'inventory_pos_key_2024';
\$config['sess_driver'] = 'files';
\$config['sess_cookie_name'] = 'ci_session';
\$config['sess_expiration'] = 7200;
\$config['sess_save_path'] = NULL;
\$config['sess_match_ip'] = FALSE;
\$config['sess_time_to_update'] = 300;
\$config['sess_regenerate_destroy'] = FALSE;
\$config['cookie_prefix'] = '';
\$config['cookie_domain'] = '';
\$config['cookie_path'] = '/';
\$config['cookie_secure'] = FALSE;
\$config['cookie_httponly'] = FALSE;
\$config['standardize_newlines'] = FALSE;
\$config['global_xss_filtering'] = FALSE;
\$config['csrf_protection'] = FALSE;
\$config['csrf_token_name'] = 'csrf_test_name';
\$config['csrf_cookie_name'] = 'csrf_cookie_name';
\$config['csrf_expire'] = 7200;
\$config['csrf_regenerate'] = TRUE;
\$config['csrf_exclude_uris'] = array();
\$config['compress_output'] = FALSE;
\$config['time_reference'] = 'local';
\$config['rewrite_short_tags'] = FALSE;
\$config['proxy_ips'] = '';");
        
        file_put_contents('application/config/routes.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
\$route['default_controller'] = 'auth';
\$route['404_override'] = '';
\$route['translate_uri_dashes'] = FALSE;
\$route['login'] = 'auth';
\$route['logout'] = 'auth/logout';
\$route['dashboard'] = 'dashboard';");
        
        file_put_contents('application/helpers/fungsi_helper.php', "<?php
function rupiah(\$angka){
    return 'Rp '.number_format(\$angka,0,',','.');
}
function tanggal_indo(\$tanggal){
    \$bulan = array (1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
    \$split = explode('-', \$tanggal);
    return \$split[2] . ' ' . \$bulan[ (int)\$split[1] ] . ' ' . \$split[0];
}
function no_transaksi(){
    return 'TRX'.date('YmdHis').rand(100,999);
}
function alert_success(\$msg){
    return '<div class=\"alert alert-success alert-dismissible\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>'.\$msg.'</div>';
}
function alert_error(\$msg){
    return '<div class=\"alert alert-danger alert-dismissible\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>'.\$msg.'</div>';
}");
        
        file_put_contents('application/helpers/auth_helper.php', "<?php
function cek_login(){
    \$CI =& get_instance();
    if(!\$CI->session->userdata('logged_in')){
        redirect('auth');
    }
}
function cek_admin(){
    \$CI =& get_instance();
    cek_login();
    if(\$CI->session->userdata('level') != 'admin'){
        redirect('dashboard');
    }
}
function user_login(){
    \$CI =& get_instance();
    return \$CI->session->userdata('nama');
}
function level_login(){
    \$CI =& get_instance();
    return \$CI->session->userdata('level');
}");
        
        file_put_contents('application/models/M_auth.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_auth extends CI_Model {
    public function login(\$username, \$password){
        \$this->db->where('username', \$username);
        \$this->db->where('status', 1);
        \$user = \$this->db->get('users')->row();
        if(\$user && password_verify(\$password, \$user->password)){
            return \$user;
        }
        return false;
    }
}");
        
        file_put_contents('application/models/M_kategori.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_kategori extends CI_Model {
    public function get_all(){ return \$this->db->get('kategori')->result(); }
    public function get_by_id(\$id){ return \$this->db->get_where('kategori', array('id' => \$id))->row(); }
    public function insert(\$data){ return \$this->db->insert('kategori', \$data); }
    public function update(\$id, \$data){ \$this->db->where('id', \$id); return \$this->db->update('kategori', \$data); }
    public function delete(\$id){ \$this->db->where('id', \$id); return \$this->db->delete('kategori'); }
}");
        
        file_put_contents('application/models/M_barang.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_barang extends CI_Model {
    public function get_all(\$limit = null, \$start = null){
        \$this->db->select('barang.*, kategori.nama as nama_kategori');
        \$this->db->join('kategori', 'kategori.id = barang.kategori_id');
        if(\$limit) \$this->db->limit(\$limit, \$start);
        return \$this->db->get('barang')->result();
    }
    public function count_all(){ return \$this->db->count_all('barang'); }
    public function get_by_id(\$id){
        \$this->db->select('barang.*, kategori.nama as nama_kategori');
        \$this->db->join('kategori', 'kategori.id = barang.kategori_id');
        return \$this->db->get_where('barang', array('barang.id' => \$id))->row();
    }
    public function get_by_kode(\$kode){ return \$this->db->get_where('barang', array('kode' => \$kode))->row(); }
    public function insert(\$data){ return \$this->db->insert('barang', \$data); }
    public function update(\$id, \$data){ \$this->db->where('id', \$id); return \$this->db->update('barang', \$data); }
    public function delete(\$id){ \$this->db->where('id', \$id); return \$this->db->delete('barang'); }
    public function update_stok(\$id, \$qty, \$tipe = 'kurang'){
        if(\$tipe == 'kurang'){ \$this->db->set('stok', 'stok - '.\$qty, FALSE); }
        else { \$this->db->set('stok', 'stok + '.\$qty, FALSE); }
        \$this->db->where('id', \$id);
        return \$this->db->update('barang');
    }
}");
        
        file_put_contents('application/models/M_transaksi.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_transaksi extends CI_Model {
    public function get_all(){
        \$this->db->select('transaksi.*, users.nama as nama_user');
        \$this->db->join('users', 'users.id = transaksi.user_id');
        \$this->db->order_by('transaksi.created_at', 'DESC');
        return \$this->db->get('transaksi')->result();
    }
    public function get_by_id(\$id){
        \$this->db->select('transaksi.*, users.nama as nama_user');
        \$this->db->join('users', 'users.id = transaksi.user_id');
        return \$this->db->get_where('transaksi', array('transaksi.id' => \$id))->row();
    }
    public function get_detail(\$transaksi_id){
        \$this->db->select('detail_transaksi.*, barang.nama as nama_barang, barang.kode');
        \$this->db->join('barang', 'barang.id = detail_transaksi.barang_id');
        \$this->db->where('transaksi_id', \$transaksi_id);
        return \$this->db->get('detail_transaksi')->result();
    }
    public function insert_transaksi(\$data){ \$this->db->insert('transaksi', \$data); return \$this->db->insert_id(); }
    public function insert_detail(\$data){ return \$this->db->insert('detail_transaksi', \$data); }
    public function get_laporan(\$tgl_awal, \$tgl_akhir){
        \$this->db->select('transaksi.*, users.nama as nama_user');
        \$this->db->join('users', 'users.id = transaksi.user_id');
        \$this->db->where('tanggal >=', \$tgl_awal);
        \$this->db->where('tanggal <=', \$tgl_akhir);
        \$this->db->order_by('tanggal', 'DESC');
        return \$this->db->get('transaksi')->result();
    }
}");
        
        file_put_contents('application/controllers/Auth.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller {
    public function __construct(){ parent::__construct(); \$this->load->model('M_auth'); }
    public function index(){
        if(\$this->session->userdata('logged_in')){ redirect('dashboard'); }
        \$this->load->view('auth/login');
    }
    public function proses_login(){
        \$username = \$this->input->post('username');
        \$password = \$this->input->post('password');
        \$user = \$this->M_auth->login(\$username, \$password);
        if(\$user){
            \$session = array('id' => \$user->id, 'username' => \$user->username, 'nama' => \$user->nama, 'level' => \$user->level, 'logged_in' => TRUE);
            \$this->session->set_userdata(\$session);
            redirect('dashboard');
        } else {
            \$this->session->set_flashdata('error', 'Username atau password salah');
            redirect('auth');
        }
    }
    public function logout(){ \$this->session->sess_destroy(); redirect('auth'); }
}");
        
        file_put_contents('application/controllers/Dashboard.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {
    public function __construct(){ parent::__construct(); cek_login(); }
    public function index(){
        \$data['title'] = 'Dashboard';
        \$data['total_barang'] = \$this->db->count_all('barang');
        \$data['total_kategori'] = \$this->db->count_all('kategori');
        \$data['total_transaksi'] = \$this->db->count_all('transaksi');
        \$this->db->select_sum('total');
        \$result = \$this->db->get('transaksi')->row();
        \$data['total_pendapatan'] = \$result->total ?? 0;
        \$this->load->view('template/header', \$data);
        \$this->load->view('template/sidebar', \$data);
        \$this->load->view('dashboard', \$data);
        \$this->load->view('template/footer');
    }
}");
        
        file_put_contents('application/controllers/Kategori.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kategori extends CI_Controller {
    public function __construct(){ parent::__construct(); cek_login(); \$this->load->model('M_kategori'); }
    public function index(){
        \$data['title'] = 'Data Kategori';
        \$data['kategori'] = \$this->M_kategori->get_all();
        \$this->load->view('template/header', \$data);
        \$this->load->view('template/sidebar', \$data);
        \$this->load->view('kategori/index', \$data);
        \$this->load->view('template/footer');
    }
    public function tambah(){
        cek_admin();
        \$data = array('nama' => \$this->input->post('nama'), 'keterangan' => \$this->input->post('keterangan'));
        if(\$this->M_kategori->insert(\$data)){ \$this->session->set_flashdata('success', 'Kategori berhasil ditambahkan'); }
        else { \$this->session->set_flashdata('error', 'Gagal menambahkan kategori'); }
        redirect('kategori');
    }
    public function edit(\$id){
        cek_admin();
        \$data = array('nama' => \$this->input->post('nama'), 'keterangan' => \$this->input->post('keterangan'));
        if(\$this->M_kategori->update(\$id, \$data)){ \$this->session->set_flashdata('success', 'Kategori berhasil diupdate'); }
        else { \$this->session->set_flashdata('error', 'Gagal update kategori'); }
        redirect('kategori');
    }
    public function hapus(\$id){
        cek_admin();
        if(\$this->M_kategori->delete(\$id)){ \$this->session->set_flashdata('success', 'Kategori berhasil dihapus'); }
        else { \$this->session->set_flashdata('error', 'Gagal menghapus kategori'); }
        redirect('kategori');
    }
}");
        
        file_put_contents('application/controllers/Barang.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Barang extends CI_Controller {
    public function __construct(){ parent::__construct(); cek_login(); \$this->load->model('M_barang'); \$this->load->model('M_kategori'); }
    public function index(){
        \$data['title'] = 'Data Barang';
        \$config['base_url'] = site_url('barang/index');
        \$config['total_rows'] = \$this->M_barang->count_all();
        \$config['per_page'] = 10;
        \$config['uri_segment'] = 3;
        \$this->pagination->initialize(\$config);
        \$page = (\$this->uri->segment(3)) ? \$this->uri->segment(3) : 0;
        \$data['barang'] = \$this->M_barang->get_all(\$config['per_page'], \$page);
        \$data['pagination'] = \$this->pagination->create_links();
        \$this->load->view('template/header', \$data);
        \$this->load->view('template/sidebar', \$data);
        \$this->load->view('barang/index', \$data);
        \$this->load->view('template/footer');
    }
    public function tambah(){
        cek_admin();
        \$data['title'] = 'Tambah Barang';
        \$data['kategori'] = \$this->M_kategori->get_all();
        \$this->form_validation->set_rules('kode', 'Kode Barang', 'required|is_unique[barang.kode]');
        \$this->form_validation->set_rules('nama', 'Nama Barang', 'required');
        \$this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|numeric');
        \$this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        \$this->form_validation->set_rules('stok', 'Stok', 'required|numeric');
        if(\$this->form_validation->run() == FALSE){
            \$this->load->view('template/header', \$data);
            \$this->load->view('template/sidebar', \$data);
            \$this->load->view('barang/tambah', \$data);
            \$this->load->view('template/footer');
        } else {
            \$config['upload_path'] = './assets/uploads/barang/';
            \$config['allowed_types'] = 'gif|jpg|png|jpeg';
            \$config['max_size'] = 2048;
            \$config['file_name'] = time().'_'.\$_FILES['gambar']['name'];
            \$this->load->library('upload', \$config);
            \$gambar = '';
            if(\$this->upload->do_upload('gambar')){ \$upload_data = \$this->upload->data(); \$gambar = \$upload_data['file_name']; }
            \$data_insert = array('kode' => \$this->input->post('kode'), 'nama' => \$this->input->post('nama'), 'kategori_id' => \$this->input->post('kategori_id'), 'harga_beli' => \$this->input->post('harga_beli'), 'harga_jual' => \$this->input->post('harga_jual'), 'stok' => \$this->input->post('stok'), 'satuan' => \$this->input->post('satuan'), 'gambar' => \$gambar);
            if(\$this->M_barang->insert(\$data_insert)){ \$this->session->set_flashdata('success', 'Barang berhasil ditambahkan'); }
            else { \$this->session->set_flashdata('error', 'Gagal menambahkan barang'); }
            redirect('barang');
        }
    }
    public function edit(\$id){
        cek_admin();
        \$data['title'] = 'Edit Barang';
        \$data['barang'] = \$this->M_barang->get_by_id(\$id);
        \$data['kategori'] = \$this->M_kategori->get_all();
        \$this->form_validation->set_rules('nama', 'Nama Barang', 'required');
        \$this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|numeric');
        \$this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        if(\$this->form_validation->run() == FALSE){
            \$this->load->view('template/header', \$data);
            \$this->load->view('template/sidebar', \$data);
            \$this->load->view('barang/edit', \$data);
            \$this->load->view('template/footer');
        } else {
            \$data_update = array('nama' => \$this->input->post('nama'), 'kategori_id' => \$this->input->post('kategori_id'), 'harga_beli' => \$this->input->post('harga_beli'), 'harga_jual' => \$this->input->post('harga_jual'), 'stok' => \$this->input->post('stok'), 'satuan' => \$this->input->post('satuan'));
            if(\$this->M_barang->update(\$id, \$data_update)){ \$this->session->set_flashdata('success', 'Barang berhasil diupdate'); }
            else { \$this->session->set_flashdata('error', 'Gagal update barang'); }
            redirect('barang');
        }
    }
    public function hapus(\$id){
        cek_admin();
        \$barang = \$this->M_barang->get_by_id(\$id);
        if(\$barang->gambar && file_exists('./assets/uploads/barang/'.\$barang->gambar)){ unlink('./assets/uploads/barang/'.\$barang->gambar); }
        if(\$this->M_barang->delete(\$id)){ \$this->session->set_flashdata('success', 'Barang berhasil dihapus'); }
        else { \$this->session->set_flashdata('error', 'Gagal menghapus barang'); }
        redirect('barang');
    }
    public function get_barang_json(\$kode){
        \$barang = \$this->M_barang->get_by_kode(\$kode);
        echo json_encode(\$barang);
    }
}");
        
        file_put_contents('application/controllers/Transaksi.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transaksi extends CI_Controller {
    public function __construct(){ parent::__construct(); cek_login(); \$this->load->model('M_transaksi'); \$this->load->model('M_barang'); }
    public function index(){
        \$data['title'] = 'Data Transaksi';
        \$data['transaksi'] = \$this->M_transaksi->get_all();
        \$this->load->view('template/header', \$data);
        \$this->load->view('template/sidebar', \$data);
        \$this->load->view('transaksi/index', \$data);
        \$this->load->view('template/footer');
    }
    public function tambah(){
        \$data['title'] = 'Transaksi Baru';
        \$data['barang'] = \$this->M_barang->get_all();
        \$this->load->view('template/header', \$data);
        \$this->load->view('template/sidebar', \$data);
        \$this->load->view('transaksi/tambah', \$data);
        \$this->load->view('template/footer');
    }
    public function simpan(){
        \$this->db->trans_start();
        \$data_transaksi = array('no_transaksi' => no_transaksi(), 'tanggal' => date('Y-m-d'), 'user_id' => \$this->session->userdata('id'), 'total' => \$this->input->post('grandtotal'), 'bayar' => \$this->input->post('bayar'), 'kembalian' => \$this->input->post('kembalian'));
        \$transaksi_id = \$this->M_transaksi->insert_transaksi(\$data_transaksi);
        \$barang_ids = \$this->input->post('barang_id');
        \$qtys = \$this->input->post('qty');
        \$hargas = \$this->input->post('harga');
        for(\$i = 0; \$i < count(\$barang_ids); \$i++){
            \$data_detail = array('transaksi_id' => \$transaksi_id, 'barang_id' => \$barang_ids[\$i], 'qty' => \$qtys[\$i], 'harga' => \$hargas[\$i], 'subtotal' => \$qtys[\$i] * \$hargas[\$i]);
            \$this->M_transaksi->insert_detail(\$data_detail);
            \$this->M_barang->update_stok(\$barang_ids[\$i], \$qtys[\$i], 'kurang');
        }
        \$this->db->trans_complete();
        if(\$this->db->trans_status()){ \$this->session->set_flashdata('success', 'Transaksi berhasil disimpan'); redirect('transaksi/detail/'.\$transaksi_id); }
        else { \$this->session->set_flashdata('error', 'Gagal menyimpan transaksi'); redirect('transaksi/tambah'); }
    }
    public function detail(\$id){
        \$data['title'] = 'Detail Transaksi';
        \$data['transaksi'] = \$this->M_transaksi->get_by_id(\$id);
        \$data['detail'] = \$this->M_transaksi->get_detail(\$id);
        \$this->load->view('template/header', \$data);
        \$this->load->view('template/sidebar', \$data);
        \$this->load->view('transaksi/detail', \$data);
        \$this->load->view('template/footer');
    }
}");
        
        file_put_contents('application/controllers/Laporan.php', "<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Laporan extends CI_Controller {
    public function __construct(){ parent::__construct(); cek_login(); \$this->load->model('M_transaksi'); }
    public function stok(){
        \$data['title'] = 'Laporan Stok Barang';
        \$this->db->select('barang.*, kategori.nama as nama_kategori');
        \$this->db->join('kategori', 'kategori.id = barang.kategori_id');
        \$data['barang'] = \$this->db->get('barang')->result();
        \$this->load->view('template/header', \$data);
        \$this->load->view('template/sidebar', \$data);
        \$this->load->view('laporan/stok', \$data);
        \$this->load->view('template/footer');
    }
    public function penjualan(){
        \$data['title'] = 'Laporan Penjualan';
        \$data['transaksi'] = array();
        if(\$this->input->post()){
            \$tgl_awal = \$this->input->post('tgl_awal');
            \$tgl_akhir = \$this->input->post('tgl_akhir');
            \$data['transaksi'] = \$this->M_transaksi->get_laporan(\$tgl_awal, \$tgl_akhir);
            \$data['tgl_awal'] = \$tgl_awal;
            \$data['tgl_akhir'] = \$tgl_akhir;
        }
        \$this->load->view('template/header', \$data);
        \$this->load->view('template/sidebar', \$data);
        \$this->load->view('laporan/penjualan', \$data);
        \$this->load->view('template/footer');
    }
}");
        
        // Views
        file_put_contents('application/views/template/header.php', '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?> | Inventory POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-dark-primary { background: #1a1a2e !important; }
        .nav-sidebar .nav-link.active { background: #16213e !important; }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" href="<?= site_url(\'logout\') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </nav>');
        
        file_put_contents('application/views/template/sidebar.php', '<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= site_url(\'dashboard\') ?>" class="brand-link">
        <span class="brand-text font-weight-light">Inventory POS</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <a href="#" class="d-block"><?= user_login() ?> (<?= level_login() ?>)</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="<?= site_url(\'dashboard\') ?>" class="nav-link <?= $this->uri->segment(1) == \'dashboard\' ? \'active\' : \'\' ?>"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url(\'kategori\') ?>" class="nav-link <?= $this->uri->segment(1) == \'kategori\' ? \'active\' : \'\' ?>"><i class="nav-icon fas fa-tags"></i><p>Kategori</p></a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url(\'barang\') ?>" class="nav-link <?= $this->uri->segment(1) == \'barang\' ? \'active\' : \'\' ?>"><i class="nav-icon fas fa-box"></i><p>Barang</p></a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url(\'transaksi\') ?>" class="nav-link <?= $this->uri->segment(1) == \'transaksi\' ? \'active\' : \'\' ?>"><i class="nav-icon fas fa-shopping-cart"></i><p>Transaksi</p></a>
                </li>
                <li class="nav-item has-treeview <?= $this->uri->segment(1) == \'laporan\' ? \'menu-open\' : \'\' ?>"><a href="#" class="nav-link"><i class="nav-icon fas fa-chart-bar"></i><p>Laporan<i class="right fas fa-angle-left"></i></p></a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"><a href="<?= site_url(\'laporan/stok\') ?>" class="nav-link <?= $this->uri->segment(2) == \'stok\' ? \'active\' : \'\' ?>"><i class="far fa-circle nav-icon"></i><p>Stok Barang</p></a></li>
                        <li class="nav-item"><a href="<?= site_url(\'laporan/penjualan\') ?>" class="nav-link <?= $this->uri->segment(2) == \'penjualan\' ? \'active\' : \'\' ?>"><i class="far fa-circle nav-icon"></i><p>Penjualan</p></a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
<div class="content-wrapper">');
        
        file_put_contents('application/views/template/footer.php', '</div>
<footer class="main-footer">
    <div class="float-right d-none d-sm-inline">Inventory POS v1.0</div>
    <strong>PKL RPL <?= date(\'Y\') ?></strong>
</footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>');
        
        file_put_contents('application/views/auth/login.php', '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login | Inventory POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100vh; display: flex; align-items: center; }
        .login-box { width: 400px; }
        .card { border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <h3 class="login-box-msg">Inventory POS</h3>
            <p class="text-center text-muted">Sistem Manajemen Inventory</p>
            <?php if($this->session->flashdata(\'error\')): ?>
                <div class="alert alert-danger"><?= $this->session->flashdata(\'error\') ?></div>
            <?php endif; ?>
            <form action="<?= site_url(\'auth/proses_login\') ?>" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-user"></span></div></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div>
                </div>
                <div class="row">
                    <div class="col-12"><button type="submit" class="btn btn-primary btn-block">Login</button></div>
                </div>
            </form>
            <div class="mt-3 text-center text-muted small">
                <p>Default Login:<br>Admin: admin / admin123<br>Kasir: kasir / kasir123</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>');
        
        file_put_contents('application/views/dashboard.php', '<section class="content-header"><h1>Dashboard</h1></section>
<section class="content">
    <?php if($this->session->flashdata(\'success\')) echo alert_success($this->session->flashdata(\'success\')); ?>
    <?php if($this->session->flashdata(\'error\')) echo alert_error($this->session->flashdata(\'error\')); ?>
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner"><h3><?= $total_barang ?></h3><p>Total Barang</p></div>
                <div class="icon"><i class="fas fa-box"></i></div>
                <a href="<?= site_url(\'barang\') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner"><h3><?= $total_kategori ?></h3><p>Kategori</p></div>
                <div class="icon"><i class="fas fa-tags"></i></div>
                <a href="<?= site_url(\'kategori\') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner"><h3><?= $total_transaksi ?></h3><p>Transaksi</p></div>
                <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                <a href="<?= site_url(\'transaksi\') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner"><h3><?= rupiah($total_pendapatan) ?></h3><p>Total Pendapatan</p></div>
                <div class="icon"><i class="fas fa-money-bill"></i></div>
                <a href="<?= site_url(\'laporan/penjualan\') ?>" class="small-box-footer">Lihat <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
</section>');
        
        file_put_contents('application/views/kategori/index.php', '<section class="content-header"><h1>Data Kategori</h1></section>
<section class="content">
    <?php if($this->session->flashdata(\'success\')) echo alert_success($this->session->flashdata(\'success\')); ?>
    <?php if($this->session->flashdata(\'error\')) echo alert_error($this->session->flashdata(\'error\')); ?>
    <div class="card">
        <div class="card-header">
            <?php if(level_login() == \'admin\'): ?>
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah"><i class="fas fa-plus"></i> Tambah</button>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead><tr><th>No</th><th>Nama</th><th>Keterangan</th><?php if(level_login() == \'admin\'): ?><th>Aksi</th><?php endif; ?></tr></thead>
                <tbody>
                    <?php $no=1; foreach($kategori as $k): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $k->nama ?></td>
                        <td><?= $k->keterangan ?></td>
                        <?php if(level_login() == \'admin\'): ?>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalEdit<?= $k->id ?>"><i class="fas fa-edit"></i></button>
                            <a href="<?= site_url(\'kategori/hapus/\'.$k->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus?\')"><i class="fas fa-trash"></i></a>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php if(level_login() == \'admin\'): ?>
<div class="modal fade" id="modalTambah"><div class="modal-dialog"><div class="modal-content">
    <form action="<?= site_url(\'kategori/tambah\') ?>" method="post">
    <div class="modal-header"><h4>Tambah Kategori</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
    <div class="modal-body">
        <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
        <div class="form-group"><label>Keterangan</label><textarea name="keterangan" class="form-control"></textarea></div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
</div></div></div>

<?php foreach($kategori as $k): ?>
<div class="modal fade" id="modalEdit<?= $k->id ?>"><div class="modal-dialog"><div class="modal-content">
    <form action="<?= site_url(\'kategori/edit/\'.$k->id) ?>" method="post">
    <div class="modal-header"><h4>Edit Kategori</h4><button type="button" class="close" data-dismiss="modal">&times;</button></div>
    <div class="modal-body">
        <div class="form-group"><label>Nama</label><input type="text" name="nama" class="form-control" value="<?= $k->nama ?>" required></div>
        <div class="form-group"><label>Keterangan</label><textarea name="keterangan" class="form-control"><?= $k->keterangan ?></textarea></div>
    </div>
    <div class="modal-footer"><button type="submit" class="btn btn-primary">Update</button></div>
    </form>
</div></div></div>
<?php endforeach; endif; ?>');
        
        file_put_contents('application/views/barang/index.php', '<section class="content-header"><h1>Data Barang</h1></section>
<section class="content">
    <?php if($this->session->flashdata(\'success\')) echo alert_success($this->session->flashdata(\'success\')); ?>
    <?php if($this->session->flashdata(\'error\')) echo alert_error($this->session->flashdata(\'error\')); ?>
    <div class="card">
        <div class="card-header">
            <?php if(level_login() == \'admin\'): ?>
            <a href="<?= site_url(\'barang/tambah\') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Barang</a>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead><tr><th>No</th><th>Kode</th><th>Gambar</th><th>Nama</th><th>Kategori</th><th>Harga Jual</th><th>Stok</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php $no=1; foreach($barang as $b): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $b->kode ?></td>
                        <td><img src="<?= $b->gambar ? base_url(\'assets/uploads/barang/\'.$b->gambar) : base_url(\'assets/img/no-image.png\') ?>" width="50"></td>
                        <td><?= $b->nama ?></td>
                        <td><?= $b->nama_kategori ?></td>
                        <td><?= rupiah($b->harga_jual) ?></td>
                        <td><?= $b->stok ?> <?= $b->satuan ?></td>
                        <td>
                            <a href="<?= site_url(\'barang/edit/\'.$b->id) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                            <a href="<?= site_url(\'barang/hapus/\'.$b->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin hapus?\')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $pagination ?>
        </div>
    </div>
</section>');
        
        file_put_contents('application/views/barang/tambah.php', '<section class="content-header"><h1>Tambah Barang</h1></section>
<section class="content">
    <div class="card">
        <div class="card-body">
            <?= validation_errors(\'<div class="alert alert-danger">\',\'</div>\') ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label>Kode Barang</label><input type="text" name="kode" class="form-control" required></div>
                        <div class="form-group"><label>Nama Barang</label><input type="text" name="nama" class="form-control" required></div>
                        <div class="form-group"><label>Kategori</label><select name="kategori_id" class="form-control" required>
                            <option value="">Pilih Kategori</option>
                            <?php foreach($kategori as $k): ?><option value="<?= $k->id ?>"><?= $k->nama ?></option><?php endforeach; ?>
                        </select></div>
                        <div class="form-group"><label>Satuan</label><input type="text" name="satuan" class="form-control" value="pcs" required></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Harga Beli</label><input type="number" name="harga_beli" class="form-control" required></div>
                        <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" class="form-control" required></div>
                        <div class="form-group"><label>Stok Awal</label><input type="number" name="stok" class="form-control" required></div>
                        <div class="form-group"><label>Gambar</label><input type="file" name="gambar" class="form-control" accept="image/*"></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= site_url(\'barang\') ?>" class="btn btn-default">Kembali</a>
            </form>
        </div>
    </div>
</section>');
        
        file_put_contents('application/views/barang/edit.php', '<section class="content-header"><h1>Edit Barang</h1></section>
<section class="content">
    <div class="card">
        <div class="card-body">
            <?= validation_errors(\'<div class="alert alert-danger">\',\'</div>\') ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label>Kode Barang</label><input type="text" class="form-control" value="<?= $barang->kode ?>" disabled></div>
                        <div class="form-group"><label>Nama Barang</label><input type="text" name="nama" class="form-control" value="<?= $barang->nama ?>" required></div>
                        <div class="form-group"><label>Kategori</label><select name="kategori_id" class="form-control" required>
                            <?php foreach($kategori as $k): ?>
                            <option value="<?= $k->id ?>" <?= $k->id == $barang->kategori_id ? \'selected\' : \'\' ?>><?= $k->nama ?></option>
                            <?php endforeach; ?>
                        </select></div>
                        <div class="form-group"><label>Satuan</label><input type="text" name="satuan" class="form-control" value="<?= $barang->satuan ?>" required></div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group"><label>Harga Beli</label><input type="number" name="harga_beli" class="form-control" value="<?= $barang->harga_beli ?>" required></div>
                        <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" class="form-control" value="<?= $barang->harga_jual ?>" required></div>
                        <div class="form-group"><label>Stok</label><input type="number" name="stok" class="form-control" value="<?= $barang->stok ?>" required></div>
                        <div class="form-group"><label>Gambar</label><br><?php if($barang->gambar): ?><img src="<?= base_url(\'assets/uploads/barang/\'.$barang->gambar) ?>" width="100" class="mb-2"><?php endif; ?><input type="file" name="gambar" class="form-control" accept="image/*"></div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?= site_url(\'barang\') ?>" class="btn btn-default">Kembali</a>
            </form>
        </div>
    </div>
</section>');
        
        file_put_contents('application/views/transaksi/index.php', '<section class="content-header"><h1>Data Transaksi</h1></section>
<section class="content">
    <div class="card">
        <div class="card-header"><a href="<?= site_url(\'transaksi/tambah\') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Transaksi Baru</a></div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead><tr><th>No</th><th>No Transaksi</th><th>Tanggal</th><th>User</th><th>Total</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach($transaksi as $t): ?>
                    <tr>
                        <td><?= $t->id ?></td>
                        <td><?= $t->no_transaksi ?></td>
                        <td><?= tanggal_indo($t->tanggal) ?></td>
                        <td><?= $t->nama_user ?></td>
                        <td><?= rupiah($t->total) ?></td>
                        <td><a href="<?= site_url(\'transaksi/detail/\'.$t->id) ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>');
        
        file_put_contents('application/views/transaksi/tambah.php', '<section class="content-header"><h1>Transaksi Baru (POS)</h1></section>
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card"><div class="card-header"><h3 class="card-title">Daftar Barang</h3></div>
            <div class="card-body">
                <div class="form-group"><input type="text" id="cari" class="form-control" placeholder="Cari barang..." onkeyup="filterBarang()"></div>
                <div class="row" id="list-barang">
                    <?php foreach($barang as $b): if($b->stok > 0): ?>
                    <div class="col-md-4 barang-item" data-nama="<?= strtolower($b->nama) ?>">
                        <div class="card" onclick="tambahItem(<?= $b->id ?>, \'<?= addslashes($b->nama) ?>\', <?= $b->harga_jual ?>, <?= $b->stok ?>)"> 
                            <div class="card-body p-2"> 
                                <h6 class="mb-1"><?= $b->nama ?></h6> 
                                <small class="text-muted"><?= rupiah($b->harga_jual) ?> | Stok: <?= $b->stok ?></small> 
                            </div> 
                        </div> 
                    </div>
                    <?php endif; endforeach; ?>
                </div>
            </div></div>
        </div>
        <div class="col-md-4">
            <div class="card"><div class="card-header bg-primary"><h3 class="card-title">Keranjang</h3></div>
            <div class="card-body p-0"><table class="table table-sm" id="keranjang"><thead><tr><th>Item</th><th>Qty</th><th>Hapus</th></tr></thead><tbody></tbody></table></div>
            <div class="card-footer">
                <form action="<?= site_url(\'transaksi/simpan\') ?>" method="post" id="form-transaksi">
                    <div class="form-group"><label>Total</label><input type="text" id="grandtotal" class="form-control" readonly></div>
                    <input type="hidden" name="grandtotal" id="input-grandtotal">
                    <div class="form-group"><label>Bayar</label><input type="number" name="bayar" id="bayar" class="form-control" required onkeyup="hitungKembalian()"></div>
                    <div class="form-group"><label>Kembalian</label><input type="text" id="kembalian" class="form-control" readonly></div>
                    <input type="hidden" name="kembalian" id="input-kembalian">
                    <button type="submit" class="btn btn-success btn-block" id="btn-simpan" disabled><i class="fas fa-save"></i> Simpan Transaksi</button>
                </form>
            </div></div>
        </div>
    </div>
</section>

<script>
let items = [];
function tambahItem(id, nama, harga, stok){
    let existing = items.find(i => i.id == id);
    if(existing){
        if(existing.qty < stok) existing.qty++;
        else { alert(\'Stok tidak cukup!\'); return; }
    } else {
        items.push({id: id, nama: nama, harga: harga, qty: 1, stok: stok});
    }
    renderKeranjang();
}

function renderKeranjang(){
    let html = \'\';
    let total = 0;
    items.forEach(function(item, index) {
        total += item.harga * item.qty;
        html += \'<tr>\';
        html += \'<td>\' + item.nama + \'<br><small>Rp \' + item.harga.toLocaleString() + \'</small>\';
        html += \'<input type="hidden" name="barang_id[]" value="\' + item.id + \'">\';
        html += \'<input type="hidden" name="harga[]" value="\' + item.harga + \'"></td>\';
        html += \'<td><input type="number" name="qty[]" value="\' + item.qty + \'" min="1" max="\' + item.stok + \'" class="form-control form-control-sm" style="width:60px" onchange="updateQty(\' + index + \', this.value)"></td>\';
        html += \'<td><button type="button" class="btn btn-danger btn-sm" onclick="hapusItem(\' + index + \')"><i class="fas fa-trash"></i></button></td>\';
        html += \'</tr>\';
    });
    document.getElementById(\'keranjang\').querySelector(\'tbody\').innerHTML = html;
    document.getElementById(\'grandtotal\').value = \'Rp \' + total.toLocaleString();
    document.getElementById(\'input-grandtotal\').value = total;
    hitungKembalian();
    document.getElementById(\'btn-simpan\').disabled = items.length === 0;
}

function updateQty(index, val){
    if(val < 1) val = 1;
    if(val > items[index].stok) {
        alert(\'Stok tidak mencukupi! Maks: \' + items[index].stok);
        val = items[index].stok;
    }
    items[index].qty = parseInt(val);
    renderKeranjang();
}

function hapusItem(index){
    items.splice(index, 1);
    renderKeranjang();
}

function hitungKembalian(){
    let total = parseInt(document.getElementById(\'input-grandtotal\').value) || 0;
    let bayar = parseInt(document.getElementById(\'bayar\').value) || 0;
    let kembalian = bayar - total;
    document.getElementById(\'kembalian\').value = \'Rp \' + kembalian.toLocaleString();
    document.getElementById(\'input-kembalian\').value = kembalian;
    document.getElementById(\'btn-simpan\').disabled = kembalian < 0 || items.length === 0;
}

function filterBarang(){
    let keyword = document.getElementById(\'cari\').value.toLowerCase();
    document.querySelectorAll(\'.barang-item\').forEach(function(el) {
        el.style.display = el.getAttribute(\'data-nama\').includes(keyword) ? \'block\' : \'none\';
    });
}
</script>');
        
        file_put_contents('application/views/transaksi/detail.php', '<section class="content-header"><h1>Detail Transaksi</h1></section>
<section class="content">
    <div class="card">
        <div class="card-header"><h3 class="card-title"><?= $transaksi->no_transaksi ?></h3></div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr><td width="150">Tanggal</td><td><?= tanggal_indo($transaksi->tanggal) ?></td></tr>
                <tr><td>Kasir</td><td><?= $transaksi->nama_user ?></td></tr>
                <tr><td>Total</td><td><strong><?= rupiah($transaksi->total) ?></strong></td></tr>
                <tr><td>Bayar</td><td><?= rupiah($transaksi->bayar) ?></td></tr>
                <tr><td>Kembalian</td><td><?= rupiah($transaksi->kembalian) ?></td></tr>
            </table>
            <h5>Detail Item:</h5>
            <table class="table table-bordered table-striped">
                <thead><tr><th>No</th><th>Kode</th><th>Nama Barang</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                <tbody>
                    <?php $no=1; foreach($detail as $d): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $d->kode ?></td>
                        <td><?= $d->nama_barang ?></td>
                        <td><?= rupiah($d->harga) ?></td>
                        <td><?= $d->qty ?></td>
                        <td><?= rupiah($d->subtotal) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="<?= site_url(\'transaksi\') ?>" class="btn btn-default">Kembali</a>
            <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
        </div>
    </div>
</section>');
        
        file_put_contents('application/views/laporan/stok.php', '<section class="content-header"><h1>Laporan Stok Barang</h1></section>
<section class="content">
    <div class="card"><div class="card-body">
        <table class="table table-bordered table-striped" id="table-laporan">
            <thead><tr><th>No</th><th>Kode</th><th>Nama</th><th>Kategori</th><th>Harga Beli</th><th>Harga Jual</th><th>Stok</th><th>Status</th></tr></thead>
            <tbody>
                <?php $no=1; foreach($barang as $b): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $b->kode ?></td>
                    <td><?= $b->nama ?></td>
                    <td><?= $b->nama_kategori ?></td>
                    <td><?= rupiah($b->harga_beli) ?></td>
                    <td><?= rupiah($b->harga_jual) ?></td>
                    <td><?= $b->stok ?> <?= $b->satuan ?></td>
                    <td><?= $b->stok < 5 ? \'<span class="badge badge-danger">Stok Menipis</span>\' : \'<span class="badge badge-success">Aman</span>\' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
    </div></div>
</section>');
        
        file_put_contents('application/views/laporan/penjualan.php', '<section class="content-header"><h1>Laporan Penjualan</h1></section>
<section class="content">
    <div class="card"><div class="card-body">
        <form method="post" class="form-inline mb-3">
            <label class="mr-2">Dari:</label><input type="date" name="tgl_awal" class="form-control mr-2" value="<?= isset($tgl_awal) ? $tgl_awal : date(\'Y-m-01\') ?>" required>
            <label class="mr-2">Sampai:</label><input type="date" name="tgl_akhir" class="form-control mr-2" value="<?= isset($tgl_akhir) ? $tgl_akhir : date(\'Y-m-d\') ?>" required>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
        
        <?php if(!empty($transaksi)): ?>
        <table class="table table-bordered table-striped">
            <thead><tr><th>No</th><th>No Transaksi</th><th>Tanggal</th><th>User</th><th>Total</th></tr></thead>
            <tbody>
                <?php $no=1; $grand=0; foreach($transaksi as $t): $grand+=$t->total; ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $t->no_transaksi ?></td>
                    <td><?= tanggal_indo($t->tanggal) ?></td>
                    <td><?= $t->nama_user ?></td>
                    <td><?= rupiah($t->total) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="bg-light"><td colspan="4" class="text-right"><strong>Grand Total:</strong></td><td><strong><?= rupiah($grand) ?></strong></td></tr>
            </tbody>
        </table>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Cetak</button>
        <?php endif; ?>
    </div></div>
</section>');
        
        file_put_contents('.htaccess', "RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]");
        
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Installer Inventory POS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background: #f4f6f9; padding: 50px 0; }
        .installer-box { max-width: 600px; margin: 0 auto; }
        .card { box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container installer-box">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Installer Inventory POS</h4>
            </div>
            <div class="card-body">
                <?php if($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
                <div class="alert alert-info">
                    <h5>Selamat Datang!</h5>
                    <p>Installer ini akan membuat:</p>
                    <ul>
                        <li>Folder structure CodeIgniter 3</li>
                        <li>Database MySQL dengan tabel lengkap</li>
                        <li>Config, Controller, Model, View</li>
                        <li>User login dan data dummy</li>
                    </ul>
                    <p class="text-danger"><strong>Pastikan:</strong> Sudah download CodeIgniter 3 dan extract ke folder ini sebelum install!</p>
                </div>
                <form method="post">
                    <h6>Konfigurasi Database:</h6>
                    <div class="form-group">
                        <label>Host</label>
                        <input type="text" name="db_host" class="form-control" value="localhost" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="db_user" class="form-control" value="root" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="db_pass" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak ada password (default Laragon)</small>
                    </div>
                    <div class="form-group">
                        <label>Nama Database</label>
                        <input type="text" name="db_name" class="form-control" value="inventory_pos" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Install Sekarang</button>
                </form>
                
                <?php else: ?>
                    <?php if(!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <h5>Terjadi Error:</h5>
                            <?php foreach($errors as $e) echo "<p>$e</p>"; ?>
                        </div>
                        <a href="" class="btn btn-primary">Kembali</a>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <h5>Installasi Berhasil!</h5>
                            <p>File yang dibuat:</p>
                            <ul style="font-size: 12px; max-height: 200px; overflow-y: auto;">
                                <?php foreach($success as $s) echo "<li>$s</li>"; ?>
                            </ul>
                        </div>
                        <div class="alert alert-info">
                            <h6>Login Default:</h6>
                            <p><strong>Admin:</strong> admin / admin123<br>
                            <strong>Kasir:</strong> kasir / kasir123</p>
                        </div>
                        <a href="http://localhost/inventory-pos/" class="btn btn-success btn-block btn-lg" target="_blank">Buka Aplikasi</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>