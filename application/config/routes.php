<?php
/* Filename: routes.php */
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard';