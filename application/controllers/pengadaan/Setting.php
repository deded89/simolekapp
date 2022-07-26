<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
  }

  function index()
  {
    $data = array(
      'controller' => 'Setting',
      'uri1' => 'Tahun Anggaran',
      'main_view' => 'pengadaan/setting',
    );
    $this->load->view('template_view', $data);
  }

  function update(){
    $this->session->set_userdata('tahun_anggaran',$this->input->post('tahun_anggaran',true));
    $this->session->set_flashdata('message','Setting berhasil disimpan');
    redirect(site_url('pengadaan/setting'));
  }

}
