<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Progress_pekerjaan_model extends CI_Model
{

  public $table = 'progress_pekerjaan';
  public $id = 'id';
  public $order = 'DESC';
  private $db2;

  function __construct()
  {
    parent::__construct();
    $this->db2 = $this->load->database('db2',TRUE);
  }

  // get all
  function get_all()
  {
    $this->db2->order_by($this->id, $this->order);
    return $this->db2->get($this->table)->result();
  }

  function get_last_record($id_p)
  {
    $this->db2->where('pekerjaan',$id_p);
    $this->db2->order_by($this->id, 'desc');
    return $this->db2->get($this->table)->row();
  }

  // get data by id
  function get_by_id($id)
  {
    $this->db2->where($this->id, $id);
    return $this->db2->get($this->table)->row();
  }

    // get data by id pekerjaan
  function get_by_id_p($id_p)
  {
    $this->db2->select('pp.id as id_pp, pp.real_keu, pp.real_fisik, pp.tgl_progress, pp.pekerjaan, pp.progress, pp.tgl_n_progress, pp.ket, pp.create_date, p.nama, p2.nama as next_progress');
    $this->db2->from('progress_pekerjaan pp');
    $this->db2->join('progress p', 'p.id=pp.progress', 'left');
    $this->db2->join('progress p2', 'p2.id=pp.next_progress', 'left');
    $this->db2->where('pp.pekerjaan', $id_p);
    $this->db2->order_by('pp.progress','desc');
    $this->db2->order_by('pp.tgl_progress','desc');
    $this->db2->order_by('id_pp','desc');
    return $this->db2->get()->result();
  }

  function get_by_progress($id_p,$progress){
    $this->db2->where('pekerjaan',$id_p);
    $this->db2->where('progress',$progress);
    return $this->db2->get('progress_pekerjaan')->row();
  }

  function get_max_real_keu($id_p){
    $this->db2->select_max('real_keu');
    $this->db2->where('pekerjaan',$id_p);
    return $this->db2->get('progress_pekerjaan')->row();
  }

  function get_persen_real_keu($id_p,$multi_kontrak){

    $this->load->model('Kontrak_model');
    if ($multi_kontrak <> 'ya'){
          $total_kontrak = $this->Kontrak_model->get_last_kontrak($id_p);
        }else{
          $total_kontrak = $this->Kontrak_model->sum_nilai_kontrak($id_p);
        }
    if ($total_kontrak > 0){
      $real_keu = $this->get_max_real_keu($id_p)->real_keu;
      if ($real_keu <> 0){
        $persen = $real_keu / $total_kontrak *100;
      }else{
        $persen = 0;
      }
    }else{
      $persen = 0;
    }
    return $persen;
  }

  function get_max_real_fisik($id_p){
    $this->db2->select_max('real_fisik');
    $this->db2->where('pekerjaan',$id_p);
    return $this->db2->get('progress_pekerjaan')->row();
  }

  // insert data
  function insert($data)
  {
    $this->db2->insert($this->table, $data);
  }

  // update data
  function update($id, $data)
  {
    $this->db2->where($this->id, $id);
    $this->db2->update($this->table, $data);
  }

  // delete data
  function delete($id)
  {
    $this->db2->where($this->id, $id);
    $this->db2->delete($this->table);
  }

  // update progress now di tabel pekerjaan
  public function update_progress_now($id_p){
    $this->db2->select('max(progress) as now_progress');
    $this->db2->from('progress_pekerjaan');
    $this->db2->where('pekerjaan',$id_p);
    $q = $this->db2->get()->row();
    $now = $q->now_progress;


    if ($now) {
      $now = $now;
    } else {
      $now = 9;
    }
    $this->db2->set('progress_now',$now);
    $this->db2->where('id', $id_p);
    $this->db2->update('pekerjaan');
  }

  function cek_duplikat_progress($data){
    $this->db2->where('progress',$data['progress']);
    $this->db2->where('tgl_progress',$data['tgl_progress']);
    $this->db2->where('pekerjaan',$data['id_p']);
    $num = $this->db2->get($this->table)->num_rows();
    return $num;
  }

  //chained dropdown
  function get_progress(){
    $this->db2->where('id<',9);
    $this->db2->order_by('id','asc');
    return $this->db2->get('progress')->result();
  }

  function get_progress_option(){
    $this->db2->order_by('id_progress_option','asc');
    $this->db2->join('progress','progress_option.id_progress_option=progress.id','left');
    return $this->db2->get('progress_option')->result();
  }


}

/* End of file Progress_pekerjaan_model.php */
/* Location: ./application/models/Progress_pekerjaan_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-05-27 13:30:56 */
/* http://harviacode.com */
