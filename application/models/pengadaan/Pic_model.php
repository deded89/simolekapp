<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pic_model extends CI_Model
{

    public $table = 'pic';
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

    // get data by id
    function get_by_id($id)
    {
        $this->db2->where($this->id, $id);
        return $this->db2->get($this->table)->row();
    }

    // get data by id_p
    function get_by_id_p($id_p)
    {
        $this->db2->where('pekerjaan', $id_p);
        $this->db2->order_by('status','desc');
        $this->db2->order_by('tmt','desc');
        return $this->db2->get($this->table)->result();
    }

    // get data pptk by id_p
    function get_pptk($id_p)
    {
        $this->db2->where('pekerjaan', $id_p);
        $this->db2->where('status', 'pptk');
        return $this->db2->get($this->table)->result();
    }
    // get data ppk by id_p
    function get_ppk($id_p)
    {
        $this->db2->where('pekerjaan', $id_p);
        $this->db2->where('status', 'ppk');
        return $this->db2->get($this->table)->result();
    }
    // get data Pa/KPA by id_p
    function get_pakpa($id_p)
    {
        $this->db2->where('pekerjaan', $id_p);
        $this->db2->where('status', 'pa');
        $this->db2->or_where('status', 'kpa');
        $this->db2->where('pekerjaan', $id_p);
        return $this->db2->get($this->table)->result();
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

    function cek_duplikat_pegawai($data){
      $this->db2->where('nip',$data['nip']);
      $this->db2->where('status',$data['status']);
      $this->db2->where('pekerjaan',$data['id_p']);
      $num = $this->db2->get('pic')->num_rows();
      return $num;
    }

    function cek_pegawai($nip){
      $this->db2->where('nip',$nip);
      return $this->db2->get($this->table)->row();
    }


}

/* End of file Metode_model.php */
/* Location: ./application/models/Metode_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-05-27 13:26:02 */
/* http://harviacode.com */