<?php
/* Filename: autoload.php */
defined('BASEPATH') OR exit('No direct script access allowed');
$autoload['packages'] = array();
$autoload['libraries'] = array('database', 'session', 'form_validation', 'template', 'pagination');
$autoload['drivers'] = array();
$autoload['helper'] = array('url', 'file', 'form', 'auth_helper', 'fungsi_helper');
$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array('M_auth', 'M_kategori', 'M_barang', 'M_transaksi', 'M_laporan');