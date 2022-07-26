<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

    class Kontrak extends CI_Controller
    {
      function __construct()
      {
        parent::__construct();
        $this->load->model('pengadaan/Kontrak_model');
        $this->load->model('pengadaan/Pekerjaan_model');
        $this->load->library('form_validation');
        if (!$this->ion_auth->logged_in())
        {
          redirect('auth/login', 'refresh');
        }else if (!$this->ion_auth->in_group('pengelola') and !$this->ion_auth->in_group('pptk')){
          return show_error('You must be an pptk or pengelola to view this page.');
        }
      }


      // index for admin only
      // public function index()
      // {
      //   $kontrak = $this->Kontrak_model->get_all();
      //
      //   $data = array(
      //     'kontrak_data' => $kontrak,
      //     'controller' => 'Kontrak',
      //     'uri1' => 'List Kontrak',
      //     'main_view' => 'pengadaan/kontrak/kontrak_list'
      //   );
      //   $data['hidden_attr'] = '';
      //   if (!$this->ion_auth->in_group('pptk')){
      //     $data['hidden_attr'] = 'hidden';
      //   }
      //   $this->load->view('template_view', $data);
      // }

      // public function read($id)
      // {
      //   $row = $this->Kontrak_model->get_by_id($id);
      //   if ($row) {
      //     $data = array(
      //       'controller' => 'Kontrak',
      //       'uri1' => 'Data Kontrak',
      //       'main_view' => 'pengadaan/kontrak/kontrak_read',
      //
      //       'id' => $row->id,
      //       'nomor' => $row->nomor,
      //       'tanggal' => $row->tanggal,
      //       'penyedia' => $row->penyedia,
      //       'lama' => $row->lama,
      //       'awal' => $row->awal,
      //       'akhir' => $row->akhir,
      //       'ket' => $row->ket,
      //     );
      //     $this->load->view('template_view', $data);
      //   } else {
      //     $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      //     redirect(site_url('kontrak'));
      //   }
      // }

      public function create($id_p)
      {
        $row = $this->Pekerjaan_model->get_by_id($id_p);
        if (!$row){
          $this->session->set_flashdata('error', 'Akses Dilarang (error 403 Prohibited)');
          redirect(site_url('pengadaan/pekerjaan'));
        } else {
          if ($this->ion_auth->in_group('guest')) {
             return show_error('Guest Forbid to Access This Page.');
          }
          $data = array(
            'button' => 'Simpan',
            'action' => site_url('pengadaan/kontrak/create_action'),
            'controller' => 'Kontrak',
            'uri1' => 'Tambah Kontrak',
            'main_view' => 'pengadaan/kontrak/kontrak_form',

            'id_p' => set_value('id_p',$id_p),
            'nomor' => set_value('nomor'),
            'tanggal' => set_value('tanggal'),
            'penyedia' => set_value('penyedia'),
            'nilai' => set_value('nilai'),
            'lama' => set_value('lama'),
            'awal' => set_value('awal'),
            'akhir' => set_value('akhir'),
            'ket' => set_value('ket'),
            'id_k' => set_value('id_k'),
            'hidden' => '',
          );
          $this->load->view('template_view', $data);
        }
      }

      public function create_action()
      {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
          $this->create($this->input->post('id_p',TRUE));
        } else {
          $data = array(
            'pekerjaan' => $this->input->post('id_p',TRUE),
            'nomor' => $this->input->post('nomor',TRUE),
            'tanggal' => $this->input->post('tanggal',TRUE),
            'penyedia' => $this->input->post('penyedia',TRUE),
            'nilai' => $this->input->post('nilai',TRUE),
            'lama' => $this->input->post('lama',TRUE),
            'awal' => $this->input->post('awal',TRUE),
            'akhir' => $this->input->post('akhir',TRUE),
            'ket' => $this->input->post('ket',TRUE),
          );

          // CEK NILAI KONTRAK TIDAK BOLEH > PAGU
          $this->cek_kontrak_melebihi_pagu($this->input->post('id_p',TRUE));

          //NILAI ADDENDUM TIDAK BOLEH > NILAI AWAL +10 %
          $this->cek_addendum_melebihi_ketentuan($this->input->post('id_p',TRUE),$this->input->post('nilai',TRUE));

          $this->Kontrak_model->insert($data);
          $this->session->set_flashdata('message', 'Data Berhasil Ditambahkan');
          redirect(site_url('pengadaan/pekerjaan/read/'.$this->input->post('id_p',TRUE)));
        }
      }

      public function update($id_k,$id_p){
        $row = $this->Pekerjaan_model->get_by_id($id_p);
        if (!$row){
          $this->session->set_flashdata('error', 'Akses Dilarang (error 403 Prohibited)');
          redirect(site_url('pengadaan/pekerjaan'));
        } else {
          if ($this->ion_auth->in_group('guest')) {
             return show_error('Guest Forbid to Access This Page.');
          }
          $row = $this->Kontrak_model->get_by_id($id_k);

          if ($row) {
            $data = array(
              'button' => 'Update',
              'action' => site_url('pengadaan/kontrak/update_action'),
              'controller' => 'Kontrak',
              'uri1' => 'Update Kontrak',
              'main_view' => 'pengadaan/kontrak/kontrak_form',

              'id_k' => set_value('id_k', $row->id),
              'nomor' => set_value('nomor', $row->nomor),
              'tanggal' => set_value('tanggal', $row->tanggal),
              'penyedia' => set_value('penyedia', $row->penyedia),
              'nilai' => set_value('nilai', $row->nilai),
              'lama' => set_value('lama', $row->lama),
              'awal' => set_value('awal', $row->awal),
              'akhir' => set_value('akhir', $row->akhir),
              'ket' => set_value('ket', $row->ket),
              'id_p' => set_value('id_p', $row->pekerjaan),
              'hidden' => 'hidden',
            );
            $this->load->view('template_view', $data);
          } else {
            $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
            redirect(site_url('pengadaan/pekerjaan/read/'.$id_p));
          }
        }
      }

      public function update_action()
      {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
          $this->update($this->input->post('id_k', TRUE),$this->input->post('id_p', TRUE));
        } else {
          $data = array(
            'nomor' => $this->input->post('nomor',TRUE),
            'tanggal' => $this->input->post('tanggal',TRUE),
            'penyedia' => $this->input->post('penyedia',TRUE),
            'nilai' => $this->input->post('nilai',TRUE),
            'lama' => $this->input->post('lama',TRUE),
            'awal' => $this->input->post('awal',TRUE),
            'akhir' => $this->input->post('akhir',TRUE),
            'ket' => $this->input->post('ket',TRUE),
            'pekerjaan' => $this->input->post('id_p',TRUE),
          );
          // CEK NILAI KONTRAK TIDAK BOLEH > PAGU
          $this->cek_kontrak_melebihi_pagu($this->input->post('id_p',TRUE));

          //NILAI ADDENDUM TIDAK BOLEH > NILAI AWAL +10 %
          $this->cek_addendum_melebihi_ketentuan($this->input->post('id_p',TRUE),$this->input->post('nilai',TRUE));

          $this->Kontrak_model->update($this->input->post('id_k', TRUE), $data);
          $this->session->set_flashdata('message', 'Update Data Berhasil');
          redirect(site_url('pengadaan/pekerjaan/read/'.$this->input->post('id_p',TRUE)));
        }
      }

      public function delete($id_k,$id_p){
        $row = $this->Pekerjaan_model->get_by_id($id_p);
        if (!$row){
          $this->session->set_flashdata('error', 'Akses Dilarang (error 403 Prohibited)');
          redirect(site_url('pengadaan/pekerjaan'));
        } else {

          if ($this->ion_auth->in_group('guest')) {
             return show_error('Guest Forbid to Access This Page.');
          }
          $row = $this->Kontrak_model->get_by_id($id_k);

          if ($row) {
            $this->Kontrak_model->delete($id_k);
            $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
            redirect(site_url('pengadaan/pekerjaan/read/'.$row->pekerjaan));
          } else {
            $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
            redirect(site_url('pengadaan/pekerjaan/read/'.$row->pekerjaan));
          }
        }
      }

      public function cek_kontrak_melebihi_pagu($id_p){
        $total_kontrak = $this->Kontrak_model->get_last_kontrak($id_p);
        if (!$total_kontrak){
          $total_kontrak = 0;
        }
          //CEK APAKAH NILAI KONTRAK YANG DIINPUT MELEBIHI PAGU
          $pagu = $this->Pekerjaan_model->get_by_id($id_p)->pagu;
          $melebihi_pagu = $this->Kontrak_model->kontrak_melebihi_pagu($total_kontrak,$pagu);
          if ($melebihi_pagu == true){
            $this->session->set_flashdata('error', 'Total Kontrak Tidak Boleh Lebih dari Pagu');
            redirect(site_url('pengadaan/pekerjaan/read/'.$id_p));
          }
      }

      public function cek_addendum_melebihi_ketentuan($id_p,$nilai_akhir){
        $result = $this->check_multi_kontrak($this->input->post('id_p',true));

        if ($result == false){
          $lebih_dari_ketentuan = $this->Kontrak_model->addendum_melebihi_ketentuan($id_p,$nilai_akhir);
          if ($lebih_dari_ketentuan == true){
            $this->session->set_flashdata('error', 'Nilai Kontrak Akhir tidak boleh melebihi 10% dari yang tercantum di kontrak awal');
            redirect(site_url('pengadaan/pekerjaan/read/'.$id_p));
          }
        }
      }

      public function _rules()
      {
        $this->form_validation->set_rules('nomor', 'nomor', 'trim|required|callback_check_nomor_kontrak');
        $this->form_validation->set_rules('tanggal', 'tanggal', 'trim|required|callback_check_nomor_kontrak');
        $this->form_validation->set_rules('penyedia', 'penyedia', 'trim|required');
        $this->form_validation->set_rules('nilai', 'nilai', 'trim|required');
        $this->form_validation->set_rules('lama', 'lama', 'trim|required');
        $this->form_validation->set_rules('awal', 'awal', 'trim|required');
        $this->form_validation->set_rules('akhir', 'akhir', 'trim|required');
        $this->form_validation->set_rules('ket', 'ket', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="label label-danger">', '</span>');
      }

        //cek apakah multi kontrak
      public function check_multi_kontrak($id_p)
      {
        $row = $this->Pekerjaan_model->get_by_id($id_p);
        if ($row->multi_kontrak == 'ya'){
          return true;
        }
        else {
          return false;
        }
      }

      public function check_nomor_kontrak(){
        $result = $this->check_multi_kontrak($this->input->post('id_p',true));
        if($result == true){
          return true;
        } else {
          $data = array (
            'nomor'    => $this->input->post('nomor',TRUE),
            'tanggal'  => $this->input->post('tanggal',TRUE),
            'id_p'  => $this->input->post('id_p',TRUE)
          );
          $num = $this->Kontrak_model->cek_duplikat_kontrak($data);
          if ($num > 0){
            $this->form_validation->set_message('check_nomor_kontrak', 'Kontrak dengan nomor atau tanggal ini sudah ada');
            return false;
          }else{
            return true;
          }
        }
      }

      function pdf()
      {
        $data = array(
          'kontrak_data' => $this->Kontrak_model->get_all(),
          'start' => 0
        );

        ini_set('memory_limit', '32M');
        $this->load->library('pdfgenerator');
        $psize = 'folio'; //setting kertas
        $orient = 'landscape'; 	//setting orientasi

        $html = $this->load->view('pengadaan/kontrak/kontrak_pdf', $data, true);

        $this->pdfgenerator->generate($html,'list Kontrak',$psize,$orient);

      }

    }

/* End of file Kontrak.php */
/* Location: ./application/controllers/Kontrak.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-05-27 14:32:28 */
/* http://harviacode.com */
?>
