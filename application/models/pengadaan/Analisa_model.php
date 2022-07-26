<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Analisa_model extends CI_Model {

	private $db2;

	public function __construct()
	{
		parent::__construct();
		$this->db2 = $this->load->database('db2',TRUE);
		
	}

	public function get_by_metode($metode){
		$db2 = $this->db2;
		$db2->select('m.nama, count(p.metode) as c_metode');
	    $db2->from('pekerjaan p');
	    $db2->join('metode m', 'm.id=p.metode','left');
	    $db2->where('p.tahun_anggaran',$this->session->userdata('tahun_anggaran'));
	    $db2->where('p.metode',$metode);
	    $db2->order_by('p.id','asc');

	    return $db2->get()->row();
	}

	public function get_metode_belum_progress($metode){
		$db2 = $this->db2;
		$db2->select('m.nama, count(p.metode) as c_belum_progress');
	    $db2->from('pekerjaan p');
	    $db2->join('metode m', 'm.id=p.metode','left');
	    $db2->where('p.tahun_anggaran',$this->session->userdata('tahun_anggaran'));
	    $db2->where('p.metode', $metode);
	    $db2->where('p.progress_now',9);
	    $db2->order_by('p.id','asc');

	    return $db2->get()->row();
	}

	public function get_metode_batal($metode){
		$db2 = $this->db2;
		$db2->select('m.nama, count(p.metode) as c_batal');
	    $db2->from('pekerjaan p');
	    $db2->join('metode m', 'm.id=p.metode','left');
	    $db2->where('p.tahun_anggaran',$this->session->userdata('tahun_anggaran'));
	    $db2->where('p.metode', $metode);
	    $db2->where('p.progress_now',8);
	    $db2->order_by('p.id','asc');

	    return $db2->get()->row();
	}

	public function get_tidak_update($metode){
		$db2 = $this->db2;
	    $db2->select('pekerjaan, MAX(tgl_progress) as tgl_progress, MAX(progress) as progress');
	    $db2->from('progress_pekerjaan');
	    $db2->group_by('pekerjaan');
	    $subquery = $db2->get_compiled_select();

	    $db2->select('a.*');
	    $db2->from('progress_pekerjaan a');
	    $db2->join('('.$subquery.') b','a.pekerjaan = b.pekerjaan AND a.tgl_progress = b.tgl_progress AND a.progress = b.progress');
	    $db2->group_by('a.pekerjaan');
	    $subquery2 = $db2->get_compiled_select();

	    $db2->select('count(p.nama) as c_tidak_update');
	    $db2->from('pekerjaan p');
	    $db2->join('('.$subquery2.') pr', 'pr.pekerjaan=p.id','left');
	    $db2->join('progress ps', 'pr.next_progress=ps.id','left');
	    $db2->where('pr.progress < 5');
	    $db2->where('p.metode',$metode);
	    $db2->where('p.tahun_anggaran',$this->session->userdata('tahun_anggaran'));
	    $db2->where('pr.tgl_n_progress <= curdate()');
	    return $db2->get()->row();
	}

}

/* End of file  */
/* Location: ./application/models/ */