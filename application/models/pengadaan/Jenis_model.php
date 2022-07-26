<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jenis_model extends CI_Model
{

    public $table = 'jenis';
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

}

/* End of file Jenis_model.php */
/* Location: ./application/models/Jenis_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-05-27 13:24:37 */
/* http://harviacode.com */
