<?php
/* Filename: Template.php */
defined('BASEPATH') OR exit('No direct script access allowed');
class Template {
    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function load($view, $view_data = array()) {
        $this->CI->load->view('template/header', $view_data);
        $this->CI->load->view('template/sidebar', $view_data);
        $this->CI->load->view($view, $view_data);
        $this->CI->load->view('template/footer', $view_data);
    }
}