<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengendalian extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('pengadaan/Pengendalian_model');
    $this->load->model('pengadaan/Pekerjaan_model');
    $this->load->model('Skpd_model');
    if (!$this->ion_auth->logged_in()) {
      redirect('auth/login', 'refresh');
    } else if ($this->ion_auth->in_group('user_biasa')) {
      return show_error('Not Permitted, not have enough access.');
    }
  }

  function send_notification()
  {
    $pekerjaan_terlambat =  $this->Pengendalian_model->get_next_last_month($skpd);
    foreach ($pekerjaan_terlambat as $pekerjaan) {
      $pekerjaan->id_jab_user;
    }
  }

  function index($skpd = null)
  {
    $data_jenis = $this->Pengendalian_model->count_pekerjaan_jenis($skpd);
    $data_metode = $this->Pengendalian_model->count_pekerjaan_metode($skpd);
    $data_pekerjaan_skpd =  $this->Pengendalian_model->count_pekerjaan_skpd($skpd);
    $data_pekerjaan_next_last_month =  $this->Pengendalian_model->get_next_last_month($skpd);
    if ($this->session->userdata('tahun_anggaran') == 2019) {
      $pagu_bl = 1162376399250;
    } elseif ($this->session->userdata('tahun_anggaran') == 2020) {
      $pagu_bl =  1014455685448;
    } elseif ($this->session->userdata('tahun_anggaran') == 2021) {
      $pagu_bl =  1014455685448;
    }

    $pagu_pekerjaan = $this->Pengendalian_model->total_pagu_pekerjaan($skpd);
    // $persen_pagu_pekerjaan = $pagu_pekerjaan / $pagu_bl * 100;
    $data = array(
      'total_pekerjaan' => $this->Pengendalian_model->total_pekerjaan($skpd),
      'count_jenis' => $data_jenis,
      'count_metode' => $data_metode,
      'count_skpd' => $data_pekerjaan_skpd,
      'pekerjaan_next_last_month' => $data_pekerjaan_next_last_month,
      'ck200' => $this->Pengendalian_model->count_k200($skpd),
      'c200' => $this->Pengendalian_model->count_200($skpd),
      'c25' => $this->Pengendalian_model->count_25($skpd),
      'c50' => $this->Pengendalian_model->count_50($skpd),
      'c_all' => $this->Pengendalian_model->count_all($skpd),
      'tahapan_all' => $this->Pengendalian_model->count_all_tahapan($skpd),
      'tahapan_200' => $this->Pengendalian_model->count_200_tahapan($skpd),
      'tahapan_25' => $this->Pengendalian_model->count_25_tahapan($skpd),
      'tahapan_50' => $this->Pengendalian_model->count_50_tahapan($skpd),
      // 'pagu_bl' => $pagu_bl,
      'pagu_pekerjaan' => $pagu_pekerjaan,
      // 'persen_pagu_pekerjaan' => $persen_pagu_pekerjaan,
      'controller' => 'Pengendalian',
      'uri1' => 'Dashboard',
      'main_view' => 'pengadaan/pengendalian/dashboard'
    );
    $data['pengelola_only'] = '';
    if (!$this->ion_auth->in_group('pengelola')) {
      $data['pengelola_only'] = 'hidden';
    }
    $data['id_skpd'] = '';
    if ($skpd <> null) {
      if (!$this->ion_auth->in_group('pimskpd')) {
        $data_skpd = $this->Skpd_model->get_by_id($skpd);
        $data['id_skpd'] = $data_skpd->id_skpd;
        $_SESSION['info'] = 'Data Difilter untuk ' . $data_skpd->nama_skpd;
        $this->session->mark_as_temp('info', 1);
      }
    }
    $this->load->view('template_view', $data);
  }

  function filter_skpd($skpd = null)
  {
    $pekerjaan = $this->Pekerjaan_model->filter_skpd($skpd);
    $data = array(
      'pekerjaan_data' => $pekerjaan,
      'controller' => 'Pekerjaan',
      'uri1' => 'List Pekerjaan',
      'main_view' => 'pengadaan/pekerjaan/pekerjaan_list',
    );
    $data['hidden_attr'] = '';
    if (!$this->ion_auth->in_group('pengelola')) {
      $data['hidden_attr'] = 'hidden';
    }
    $this->load->view('template_view', $data);
  }

  function filter_pagu($filter1, $filter2, $skpd = null)
  {
    $pekerjaan = $this->Pekerjaan_model->filter_pagu($filter1, $filter2, $skpd);
    $data = array(
      'pekerjaan_data' => $pekerjaan,
      'controller' => 'Pekerjaan',
      'uri1' => 'List Pekerjaan',
      'main_view' => 'pengadaan/pekerjaan/pekerjaan_list',
    );
    $data['hidden_attr'] = '';
    if (!$this->ion_auth->in_group('pengelola')) {
      $data['hidden_attr'] = 'hidden';
    }
    $this->load->view('template_view', $data);
  }

  function filter_jenis_metode($filter1, $filter2, $skpd = null)
  {
    $pekerjaan = $this->Pekerjaan_model->filter_jenis_metode($filter1, $filter2, $skpd);
    $data = array(
      'pekerjaan_data' => $pekerjaan,
      'controller' => 'Pekerjaan',
      'uri1' => 'List Pekerjaan',
      'main_view' => 'pengadaan/pekerjaan/pekerjaan_list',
    );
    $data['hidden_attr'] = '';
    if (!$this->ion_auth->in_group('pengelola')) {
      $data['hidden_attr'] = 'hidden';
    }
    $this->load->view('template_view', $data);
  }

  function filter_pagu_progress($filter1, $filter2, $filter3, $skpd = null)
  {
    $pekerjaan = $this->Pekerjaan_model->filter_pagu_progress($filter1, $filter2, $filter3, $skpd);
    $data = array(
      'pekerjaan_data' => $pekerjaan,
      'controller' => 'Pekerjaan',
      'uri1' => 'List Pekerjaan',
      'main_view' => 'pengadaan/pekerjaan/pekerjaan_list',
    );
    $data['hidden_attr'] = '';
    if (!$this->ion_auth->in_group('pengelola')) {
      $data['hidden_attr'] = 'hidden';
    }
    $this->load->view('template_view', $data);
  }

  function kirim_notifikasi()
  {
    $pekerjaan_terlambat = $this->Pengendalian_model->get_next_last_month();
    foreach ($pekerjaan_terlambat as $pekerjaan) {
      $this->send_message($pekerjaan->id_jab_user);
    }
    $this->session->set_flashdata('message', 'Notifiksi Berhasil Dikirim');
    redirect(site_url('pengadaan/pengendalian'));
  }

  function send_message($id_jab)
  {
    $message = "Ditemukan Pekerjaan Anda yang tidak Sesuai Rencana, Mohon Update Data di Aplikasi Simolek";
    $content = array(
      "en" => "$message",
      "url" => "http://simolekbjm.rf.gd/pengadaan/pekerjaan"
    );

    $fields = array(
      'app_id' => "91c97524-4032-4269-b6b3-1146af8309a8",
      'filters' => array(array("field" => "tag", "key" => "id_jab", "relation" => "=", "value" => "$id_jab")),
      'contents' => $content
    );

    $fields = json_encode($fields);
    print("\nJSON sent:\n");
    print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json; charset=utf-8',
      'Authorization: Basic MjMwNDlmOTItNTE2ZC00YWQxLWIxYjQtYTc5MjBkYzM2ZWQy'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
  }
}
