<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_pekerjaan_model extends CI_Model{

  private $db2;
  public function __construct()
  {
    parent::__construct();
    $this->db2 = $this->load->database('db2',TRUE);
  }

  public function get_user_pekerjaan(){

// MENDAPATKAN lAST PPTK NAME
    $db2 = $this->db2;
    // $db2->select('pekerjaan, MAX(tmt) as tmt');
    // $db2->from('pic');
    // $db2->group_by('pekerjaan');
    // $db2->where('status','pptk');
    // $subquery = $db2->get_compiled_select();

    // $db2->select('a.*');
    // $db2->from('pic a');
    // $db2->join('('.$subquery.') b','a.pekerjaan = b.pekerjaan AND a.tmt = b.tmt');
    // $db2->where('a.status','pptk');
    // $subquery2 = $db2->get_compiled_select();

    $db2->select('p.id as id_p, p.nama, p.kegiatan, p.pagu, p.skpd, p.user, s.nama_skpd, u.username');
    // $db2->select('p.id as id_p, p.nama, p.kegiatan, p.pagu, p.skpd, p.user, u.id, u.username, s.id_skpd, s.nama_skpd, pic.nama as nama_pptk');
    $db2->from('pekerjaan p');
    $db2->join('epiz_21636198_simolek.users u','u.id=p.user','left');
    $db2->join('epiz_21636198_simolek.skpd s','s.id_skpd=p.skpd','left');
    // $db2->join('('.$subquery2.') pic','pic.pekerjaan=p.id','left');
    $db2->where('p.tahun_anggaran',$this->session->userdata('tahun_anggaran'));
    $db2->order_by('p.skpd','asc');
    $db2->order_by('p.pagu','desc');
    // var_dump($this->db2->get());die();
    return $this->db2->get()->result();
  }

  public function update_user_pekerjaan(){
    $id_p = $this->input->post('pekerjaan',true);
    $id_u = $this->input->post('users',true);

    $this->db2->set('user',$id_u);
    $this->db2->where('id',$id_p);
    $this->db2->update('pekerjaan');
  }

  public function remove_user_pekerjaan($id_p){
    $this->db2->set('user','');
    $this->db2->where('id',$id_p);
    $this->db2->update('pekerjaan');
  }

}
