<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ren_real_model extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->db3 = $this->load->database('db3',TRUE);
  }

  function get_laporan_skpd($skpd,$periode,$ta,$bulan,$jenis_lap=null){
    $this->db3->select('k.*,np.*,nk.kum'.$bulan.' as ren_keu,lk.kum'.$bulan.' as real_keu,nf.kum'.$bulan.' as ren_fisik,lf.kum'.$bulan.' as real_fisik');
    $this->db3->from('kegiatan k');
    $this->db3->join('ren_keu nk','nk.kegiatan=k.id_kegiatan and nk.periode_pagu='.$periode.'','left');
    $this->db3->join('real_keu lk','lk.kegiatan=k.id_kegiatan and lk.periode_pagu='.$periode.'','left');
    $this->db3->join('ren_fisik nf','nf.kegiatan=k.id_kegiatan and nf.periode_pagu='.$periode.'','left');
    $this->db3->join('real_fisik lf','lf.kegiatan=k.id_kegiatan and lf.periode_pagu='.$periode.'','left');
    $this->db3->join('nilai_pagu np','np.kegiatan=k.id_kegiatan and np.periode_pagu='.$periode.'','left');
    $this->db3->where('k.skpd', $skpd);
    $this->db3->where('k.tahun_anggaran', $ta);
    if ($jenis_lap==='insidentil'){
      $this->db3->where('k.insidentil', 1);
    }
    if ($jenis_lap==='non_insidentil'){
      $this->db3->where('k.insidentil', 0);
    }
    $this->db3->where('np.nilai<>', 0);
    return $this->db3->get()->result_array();
  }

  function get_total_laporan_skpd($skpd,$periode,$ta,$bulan){
    $this->db3->select('ps.*,s.*,nk.kum'.$bulan.' as ren_keu_skpd,lk.kum'.$bulan.' as real_keu_skpd,nf.kum'.$bulan.' as ren_fisik_skpd,lf.kum'.$bulan.' as real_fisik_skpd');
    $this->db3->from('pagu_skpd ps');
    $this->db3->join('ren_keu_skpd nk','nk.skpd=ps.skpd and nk.periode_pagu='.$periode.'','left');
    $this->db3->join('real_keu_skpd lk','lk.skpd=ps.skpd and lk.periode_pagu='.$periode.'','left');
    $this->db3->join('ren_fisik_skpd nf','nf.skpd=ps.skpd and nf.periode_pagu='.$periode.'','left');
    $this->db3->join('real_fisik_skpd lf','lf.skpd=ps.skpd and lf.periode_pagu='.$periode.'','left');
    $this->db3->join('epiz_21636198_simolek.skpd s','s.id_skpd='.$skpd.'','left');
    $this->db3->where('ps.skpd', $skpd);
    $this->db3->where('ps.periode_pagu', $periode);
    return $this->db3->get()->row_array();
  }

  function get_laporan_pemko($periode,$ta,$bulan){
    $this->db3->select('s.*,ps.*,nk.kum'.$bulan.' as ren_keu,lk.kum'.$bulan.' as real_keu,nf.kum'.$bulan.' as ren_fisik,lf.kum'.$bulan.' as real_fisik');
    $this->db3->from('epiz_21636198_simolek.skpd s');
    $this->db3->join('pagu_skpd ps', 'ps.skpd=s.id_skpd','left');
    $this->db3->join('ren_keu_skpd nk','nk.skpd=ps.skpd and nk.periode_pagu='.$periode.'','left');
    $this->db3->join('real_keu_skpd lk','lk.skpd=ps.skpd and lk.periode_pagu='.$periode.'','left');
    $this->db3->join('ren_fisik_skpd nf','nf.skpd=ps.skpd and nf.periode_pagu='.$periode.'','left');
    $this->db3->join('real_fisik_skpd lf','lf.skpd=ps.skpd and lf.periode_pagu='.$periode.'','left');
    $this->db3->where('ps.periode_pagu', $periode);
    $this->db3->order_by('ps.skpd','asc');
    return $this->db3->get()->result_array();
  }

  function get_total_laporan_pemko($periode,$ta,$bulan){
    $this->db3->select('sum(ps.nilai) as pagu_pemko');
    $this->db3->from('pagu_skpd ps');
    $this->db3->where('ps.periode_pagu', $periode);
    $pagu_pemko = $this->db3->get()->row_array()['pagu_pemko'];

    $this->db3->select('sum(kum'.$bulan.') as ren_keu_pemko');
    $this->db3->from('ren_keu_skpd');
    $this->db3->where('periode_pagu', $periode);
    $ren_keu_pemko = $this->db3->get()->row_array()['ren_keu_pemko'];

    $this->db3->select('sum(kum'.$bulan.') as real_keu_pemko');
    $this->db3->from('real_keu_skpd');
    $this->db3->where('periode_pagu', $periode);
    $real_keu_pemko = $this->db3->get()->row_array()['real_keu_pemko'];

    $this->db3->select('sum(kum'.$bulan.') as ren_fisik_pemko');
    $this->db3->from('ren_fisik_skpd');
    $this->db3->where('periode_pagu', $periode);
    $ren_fisik_pemko = $this->db3->get()->row_array()['ren_fisik_pemko'];

    $this->db3->select('sum(kum'.$bulan.') as real_fisik_pemko');
    $this->db3->from('real_fisik_skpd');
    $this->db3->where('periode_pagu', $periode);
    $real_fisik_pemko = $this->db3->get()->row_array()['real_fisik_pemko'];

    return $total_pemko = array(
                                'pagu_pemko' => $pagu_pemko,
                                'ren_keu_pemko' => $ren_keu_pemko,
                                'real_keu_pemko' => $real_keu_pemko,
                                'ren_fisik_pemko' => $ren_fisik_pemko,
                                'real_fisik_pemko' => $real_fisik_pemko,
                                );
  }


  function get_rencana_keu_by_periode_pagu($id_kegiatan,$periode_pagu){
    $this->db3->where('kegiatan',$id_kegiatan);
    $this->db3->where('periode_pagu',$periode_pagu);
    return $this->db3->get('ren_keu')->row();
  }


  function get_realisasi_keu($id_kegiatan){
    $this->db3->where('kegiatan',$id_kegiatan);
    return $this->db3->get('real_keu')->row();
  }

  function get_skpd_from_kegiatan(){
    $this->db3->distinct();
    $this->db3->select('skpd');
    $this->db3->from('kegiatan k');
    $this->db3->get()->result();
  }

  function get_tpp_skpd($periode,$ta,$bulan){
    $this->db3->select('nk.kum'.$bulan.' as ren_keu, lk.kum'.$bulan.' as real_keu, k.*, s.*');
    $this->db3->from('kegiatan k');
    $this->db3->join('ren_keu nk','nk.kegiatan=k.id_kegiatan');
    $this->db3->join('real_keu lk','lk.kegiatan=k.id_kegiatan');
    $this->db3->join('epiz_21636198_simolek.skpd s','s.id_skpd=k.skpd','left');
    $this->db3->where('k.tahun_anggaran',$ta);
    $this->db3->where('nk.periode_pagu',$periode);
    $this->db3->where('lk.periode_pagu',$periode);
    $query = $this->db3->get_compiled_select();

    $this->db3->select('q.nama_skpd, sum(q.ren_keu) as ren_keu, sum(q.real_keu) as real_keu');
    $this->db3->from('('.$query.') q');
    $this->db3->group_by('q.skpd');
    $all = $this->db3->get()->result_array();

    $this->db3->select('q.nama_skpd, sum(q.ren_keu) as ren_keu, sum(q.real_keu) as real_keu');
    $this->db3->from('('.$query.') q');
    $this->db3->where('q.insidentil',0);
    $this->db3->group_by('q.skpd');
    $ni = $this->db3->get()->result_array();

    $this->db3->select('q.nama_skpd, sum(q.ren_keu) as ren_keu, sum(q.real_keu) as real_keu');
    $this->db3->from('('.$query.') q');
    $this->db3->where('q.insidentil',1);
    $this->db3->group_by('q.skpd');
    $i = $this->db3->get()->result_array();

    $data = array(
      'all' => $all,
      'non_insidentil' => $ni,
      'insidentil' => $i,
    );
    return $data;
  }


  //chained dropdown
  function get_ta(){
    $this->db3->order_by('id_ta','desc');
    return $this->db3->get('tahun_anggaran')->result();
  }

  function get_periode_pagu(){
    $this->db3->order_by('id_per_pagu','desc');
    $this->db3->join('tahun_anggaran','tahun_anggaran.id_ta=periode_pagu.tahun_anggaran','left');
    return $this->db3->get('periode_pagu')->result();
  }

}
