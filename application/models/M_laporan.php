    <?php
/* Filename: M_laporan.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class M_laporan extends CI_Model {
    public function get_penjualan($tgl_awal, $tgl_akhir) {
        $this->db->select('transaksi.*, users.nama as nama_kasir');
        $this->db->from('transaksi');
        $this->db->join('users', 'users.id = transaksi.id_user');
        $this->db->where('tanggal >=', $tgl_awal);
        $this->db->where('tanggal <=', $tgl_akhir);
        return $this->db->get()->result_array();
    }
}