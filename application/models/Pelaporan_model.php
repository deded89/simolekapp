<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pelaporan_model extends CI_Model
{

    public $table = 'pelaporan';
    public $id = 'id_pelaporan';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }
	
	function get_by_idLap($id)
    {
        $this->db->select('*');
		$this->db->from('pelaporan p');
		$this->db->where('id_lap', $id);
		$this->db->join('skpd s', 's.id_skpd=p.id_skpd', 'left');		
		$this->db->join('status t', 't.id_status=p.id_status', 'left');
		$this->db->join('jabatan j', 'j.id_jab=p.id_jab', 'left');
		$this->db->order_by('s.id_skpd','asc'); 
        return $this->db->get()->result();
    }
	
	function get_id_pelaporan($id_lap,$id_skpd)
	{
		$this->db->select('*');
		$this->db->from('pelaporan p');
		$this->db->where('id_lap', $id_lap);
		$this->db->where('id_skpd', $id_skpd);
		return $this->db->get()->row();
	}

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id_pelaporan', $q);
		$this->db->or_like('id_lap', $q);
		$this->db->or_like('id_skpd', $q);
		$this->db->or_like('id_status', $q);
		$this->db->or_like('id_jab', $q);
		$this->db->or_like('tgl_upload', $q);
		$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id_pelaporan', $q);
		$this->db->or_like('id_lap', $q);
		$this->db->or_like('id_skpd', $q);
		$this->db->or_like('id_status', $q);
		$this->db->or_like('id_jab', $q);
		$this->db->or_like('tgl_upload', $q);
		$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }
	
	// insert data
    function insert_file($data)
    {
        $this->db->insert('file_upload', $data);
    }
	
	//cek nama file upload sama
	function cek_nama_file($nama_file,$id_pelaporan)
	{
		$this->db->select('*');
		$this->db->from('file_upload');
		$this->db->where('nama_file',$nama_file);
		$this->db->where('id_pelaporan',$id_pelaporan);
		return $this->db->get()->row();
	}
	
	function get_all_nama_file($id_pelaporan)
	{
		$this->db->select('*');
		$this->db->from('file_upload f');		
		$this->db->where('f.id_pelaporan',$id_pelaporan);
		$this->db->join('jabatan j', 'j.id_jab=f.id_jab', 'left');
		$this->db->order_by('f.id_file','desc');
		return $this->db->get()->result();
	}

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }
	
	// update data
    function update_tgl_upload($id, $data)
    {
        $this->db->where('id_file', $id);
        $this->db->update('file_upload', $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }
	// delete file data
    function delete_file($id_file)
    {
        $this->db->where('id_file', $id_file);
        $this->db->delete('file_upload');
    }
}

/* End of file Pelaporan_model.php */
/* Location: ./application/models/Pelaporan_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-08-15 09:44:22 */
/* http://harviacode.com */