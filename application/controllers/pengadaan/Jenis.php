<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');

class Jenis extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('pengadaan/Jenis_model');
    $this->load->library('form_validation');
    if (!$this->ion_auth->logged_in())
    {
      redirect('auth/login', 'refresh');
    }else if (!$this->ion_auth->in_group('admin')) {
      return show_error('You must be an admin to view this page.');
    }
  }

  public function index()
  {
    $jenis = $this->Jenis_model->get_all();

    $data = array(
      'jenis_data' => $jenis,
      'controller' => 'Jenis',
      'uri1' => 'List Jenis',
      'main_view' => 'pengadaan/jenis/jenis_list'
    );

    $this->load->view('template_view', $data);
  }

  public function read($id)
  {
    $row = $this->Jenis_model->get_by_id($id);
    if ($row) {
      $data = array(
        'controller' => 'Jenis',
        'uri1' => 'Data Jenis',
        'main_view' => 'pengadaan/jenis/jenis_read',

        'id' => $row->id,
        'nama' => $row->nama,
      );
      $this->load->view('template_view', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(site_url('pengadaan/jenis'));
    }
  }

  public function create()
  {
    $data = array(
      'button' => 'Simpan',
      'action' => site_url('pengadaan/jenis/create_action'),
      'controller' => 'Jenis',
      'uri1' => 'Tambah Jenis',
      'main_view' => 'pengadaan/jenis/jenis_form',

      'id' => set_value('id'),
      'nama' => set_value('nama'),
    );
    $this->load->view('template_view', $data);
  }

  public function create_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create();
    } else {
      $data = array(
        'nama' => $this->input->post('nama',TRUE),
      );

      $this->Jenis_model->insert($data);
      $this->session->set_flashdata('message', 'Data Berhasil Ditambahkan');
      redirect(site_url('pengadaan/jenis'));
    }
  }

  public function update($id)
  {
    $row = $this->Jenis_model->get_by_id($id);

    if ($row) {
      $data = array(
        'button' => 'Update',
        'action' => site_url('pengadaan/jenis/update_action'),
        'controller' => 'Jenis',
        'uri1' => 'Update Jenis',
        'main_view' => 'pengadaan/jenis/jenis_form',

        'id' => set_value('id', $row->id),
        'nama' => set_value('nama', $row->nama),
      );
      $this->load->view('template_view', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(site_url('pengadaan/jenis'));
    }
  }

  public function update_action()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id', TRUE));
    } else {
      $data = array(
        'nama' => $this->input->post('nama',TRUE),
      );

      $this->Jenis_model->update($this->input->post('id', TRUE), $data);
      $this->session->set_flashdata('message', 'Update Data Berhasil');
      redirect(site_url('pengadaan/jenis'));
    }
  }

  public function delete($id)
  {
    $row = $this->Jenis_model->get_by_id($id);

    if ($row) {
      $this->Jenis_model->delete($id);
      $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
      redirect(site_url('pengadaan/jenis'));
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(site_url('pengadaan/jenis'));
    }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama', 'nama', 'trim|required');

    $this->form_validation->set_rules('id', 'id', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }

  function pdf()
  {
    $data = array(
      'jenis_data' => $this->Jenis_model->get_all(),
      'start' => 0
    );

    ini_set('memory_limit', '32M');
    $this->load->library('pdfgenerator');
    $psize = 'folio'; //setting kertas
    $orient = 'landscape'; 	//setting orientasi

    $html = $this->load->view('pengadaan/jenis/jenis_pdf', $data, true);

    $this->pdfgenerator->generate($html,'list Jenis',$psize,$orient);

  }

}

/* End of file Jenis.php */
/* Location: ./application/controllers/Jenis.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-05-27 13:24:37 */
/* http://harviacode.com */
?>