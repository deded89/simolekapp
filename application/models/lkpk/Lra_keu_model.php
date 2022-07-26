<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lra_keu_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model(array('lkpk/Lra_keu_model'));
    $this->db3 = $this->load->database('db3',TRUE);
  }

  function get_data_skpd(){
    $this->db3->select('s.*');
    $this->db3->from('epiz_21636198_simolek.skpd s');
    $this->db3->where('s.id_klasifikasi <', '6');
    $this->db3->order_by('s.id_skpd','asc');
    return $this->db3->get()->result();
  }

  function get_data_lra($periode){
    $this->db3->select('lk.*, s.*');
    $this->db3->from('lra_keu lk');
    $this->db3->join('epiz_21636198_simolek.skpd s','s.id_skpd=lk.skpd','left');
    $this->db3->where('periode_pagu',$periode);
    $this->db3->order_by('s.id_skpd','asc');
    return $this->db3->get()->result();
  }

  function insert($data){
    $this->db3->where('periode_pagu', $data['periode_pagu']);
    $this->db3->where('skpd', $data['skpd']);
    $num = $this->db3->get('lra_keu')->num_rows();
    if ($num > 0){
      $this->db3->where('periode_pagu', $data['periode_pagu']);
      $this->db3->where('skpd', $data['skpd']);
      $this->db3->update('lra_keu', $data);
    } else {
      $this->db3->insert('lra_keu', $data);
    }
  }

  function compare_lra($periode,$bulan){
    $this->db3->select('l.'.$bulan.' as lra, lk.kum'.$bulan.' as lap_skpd, s.*');
    $this->db3->from('lra_keu l');
    $this->db3->join('real_keu_skpd lk','l.periode_pagu=lk.periode_pagu and l.skpd=lk.skpd','left');
    $this->db3->join('epiz_21636198_simolek.skpd s','s.id_skpd=l.skpd','left');
    $this->db3->where('l.periode_pagu',$periode);
    $this->db3->order_by('s.id_skpd','asc');
    return $this->db3->get()->result_array();
  }

  function validasi_selisih_lra($skpd,$periode,$bulan){
    $this->db3->select('l.'.$bulan.' as lra, lk.kum'.$bulan.' as lap_skpd');
    $this->db3->from('lra_keu l');
    $this->db3->join('real_keu_skpd lk','l.periode_pagu=lk.periode_pagu and l.skpd=lk.skpd','left');
    $this->db3->where('l.periode_pagu',$periode);
    $this->db3->where('l.skpd',$skpd);
    $data = $this->db3->get()->row_array();
    $selisih = $data['lra'] - $data['lap_skpd'];
    if ($selisih <> 0 ){
      return false;
    } else {
      return true;
    }
  }

}
