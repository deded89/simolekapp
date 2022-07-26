<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Dok_cek extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $data = array(
      'controller' => 'Checker',
      'uri1' => 'Dokumen Checker',
      'main_view' => 'dok_cek'
    );

    $this->load->view('template_view', $data);
  }
}
?>
