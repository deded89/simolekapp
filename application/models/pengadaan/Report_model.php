<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends CI_Model{

  private $db2;

  public function __construct()
  {
    parent::__construct();
    $this->db2 = $this->load->database('db2',TRUE);
  }

    // function tepra(){
    //   $db2 = $this->db2;
    //
    //   $db2->select('pekerjaan, MAX(tgl_progress) as tgl_progress, MAX(progress) as progress');
    //   $db2->from('progress_pekerjaan');
    //   $db2->where('year(tgl_progress) = year(current_date)');
    //   $db2->where('month(tgl_progress) <= month(current_date)');
    //   $db2->group_by('pekerjaan');
    //   $subquery = $db2->get_compiled_select();
    //
    //   $db2->select('a.*');
    //   $db2->from('progress_pekerjaan a');
    //   $db2->join('('.$subquery.') b','a.pekerjaan = b.pekerjaan AND a.tgl_progress = b.tgl_progress AND a.progress = b.progress');
    //   $db2->group_by('a.pekerjaan');
    //   $subquery2 = $db2->get_compiled_select();
    //
    //   $db2->select('p.*, p.id as id_p, p.nama as nama_pekerjaan, pp.ket as ket_progress, pp.*, s.*,pr.*');
    //   $db2->from('('.$subquery2.') pp');
    //   $db2->join('pekerjaan p', 'pp.pekerjaan=p.id','left');
    //   $db2->join('progress pr', 'pr.id=pp.progress','left');
    //   $db2->join('epiz_21636198_simolek.skpd s', 's.id_skpd=p.skpd','left');
    //   $db2->where('p.tahun_anggaran',$this->session->userdata('tahun_anggaran'));
    //   $db2->order_by('s.id_skpd','asc');
    //   $db2->order_by('p.pagu','desc');
    //   return $db2->get()->result();
    // }

  function tepra_filter(){
    $db2 = $this->db2;
    // $query = $db2->query('select pekerjaan.id from pekerjaan where tahun_anggaran = '.$this->session->userdata('tahun_anggaran').'');
    $query = $db2->query('select pekerjaan.id,nama,progress from pekerjaan left outer join (select * from progress_pekerjaan where (progress, pekerjaan) in (select max(progress) as max_p,pekerjaan from progress_pekerjaan group by progress_pekerjaan.pekerjaan, max_p)) as last_progress ON pekerjaan.id = last_progress.pekerjaan where tahun_anggaran = '.$this->session->userdata('tahun_anggaran').'');
    return $query->result();
  }
//   function tepra_filter($tanggal,$pagu){
//     $db2 = $this->db2;
//     $db2->select('pekerjaan, MAX(tgl_progress) as tgl_progress, MAX(progress) as progress');
//     $db2->from('progress_pekerjaan');
//     $db2->where('tgl_progress <=',$tanggal);
//     $db2->group_by('pekerjaan');
//     $subquery = $db2->get_compiled_select();

//     $db2->select('a.*');
//     $db2->from('progress_pekerjaan a');
//     $db2->join('('.$subquery.') b','a.pekerjaan = b.pekerjaan AND a.tgl_progress = b.tgl_progress AND a.progress = b.progress');
//     $db2->group_by('a.pekerjaan');
//     $subquery2 = $db2->get_compiled_select();

// // MENDAPATKAN NILAI LAST KONTRAK
//     $db2->select('pekerjaan, MAX(tanggal) as tanggal');
//     $db2->from('kontrak');
//     $db2->group_by('pekerjaan');
//     $subquery3 = $db2->get_compiled_select();

//     $db2->select('a.*');
//     $db2->from('kontrak a');
//     $db2->join('('.$subquery3.') b','a.pekerjaan = b.pekerjaan AND a.tanggal = b.tanggal');
//     $subquery4 = $db2->get_compiled_select();

// // Mendapatkan Data Serah Terima
//     $db2->select('pekerjaan, MAX(tanggal) as tanggal');
//     $db2->from('serah_terima');
//     $db2->group_by('pekerjaan');
//     $subquery5 = $db2->get_compiled_select();

//     $db2->select('a.*');
//     $db2->from('serah_terima a');
//     $db2->join('('.$subquery5.') b','a.pekerjaan = b.pekerjaan AND a.tanggal = b.tanggal');
//     $subquery6 = $db2->get_compiled_select();

//     $db2->select('p.*, p.id as id_p, p.nama as nama_pekerjaan, pp.ket as ket_progress, pp.*, s.*,pr.nama as progress_sekarang, pr2.nama as progress_next, k.*, st.tanggal as tanggal_st, st.nomor as nomor_st, st.penyedia as penyedia_st, j.nama as jenis, m.nama as metode');
//     $db2->from('('.$subquery2.') pp');
//     $db2->join('pekerjaan p', 'pp.pekerjaan=p.id','left');
//     $db2->join('progress pr', 'pr.id=pp.progress','left');
//     $db2->join('progress pr2', 'pr2.id=pp.next_progress','left');
//     $db2->join('jenis j', 'j.id=p.jenis','left');
//     $db2->join('metode m', 'm.id=p.metode','left');
//     $db2->join('epiz_21636198_simolek.skpd s', 's.id_skpd=p.skpd','left');
//     $db2->join('('.$subquery4.') k', 'k.pekerjaan=p.id','left');
//     $db2->join('('.$subquery6.') st', 'st.pekerjaan=p.id','left');
//     $db2->where('p.tahun_anggaran',$this->session->userdata('tahun_anggaran'));
//     if ($pagu == 'l200'){
//       $db2->where('p.pagu > 200000000 and p.pagu <= 2500000000');
//     }
//     if ($pagu == 'l25'){
//       $db2->where('p.pagu > 2500000000 and p.pagu <= 50000000000');
//     }
//     if ($pagu == 'l50'){
//       $db2->where('p.pagu > 50000000000');
//     }
//     $db2->order_by('s.id_skpd','asc');
//     $db2->order_by('p.pagu','desc');
//     return $db2->get()->result();
//   }

  function tepra_show_count($tanggal,$pagu){
    $db2 = $this->db2;
    $db2->select('pekerjaan, MAX(tgl_progress) as tgl_progress, MAX(progress) as progress');
    $db2->from('progress_pekerjaan');
    $db2->where('tgl_progress <=',$tanggal);
    $db2->group_by('pekerjaan');
    $subquery = $db2->get_compiled_select();

    $db2->select('a.*');
    $db2->from('progress_pekerjaan a');
    $db2->join('('.$subquery.') b','a.pekerjaan = b.pekerjaan AND a.tgl_progress = b.tgl_progress AND a.progress = b.progress');
    $db2->group_by('a.pekerjaan');
    $subquery2 = $db2->get_compiled_select();

    $db2->select('pp.progress, count(pp.progress) as c_progress, pr.nama');
    $db2->from('('.$subquery2.') pp');
    $db2->join('pekerjaan p', 'pp.pekerjaan=p.id','left');
    $db2->join('progress pr', 'pr.id=pp.progress','left');
    $db2->join('epiz_21636198_simolek.skpd s', 's.id_skpd=p.skpd','left');
    $db2->where('p.tahun_anggaran',$this->session->userdata('tahun_anggaran'));
    if ($pagu == 'l200'){
      $db2->where('p.pagu > 200000000 and p.pagu <= 2500000000');
    }
    if ($pagu == 'l25'){
      $db2->where('p.pagu > 2500000000 and p.pagu <= 50000000000');
    }
    if ($pagu == 'l50'){
      $db2->where('p.pagu > 50000000000');
    }
    $db2->group_by('pp.progress');
    $db2->order_by('pp.progress');
    return $db2->get()->result();
  }

}
