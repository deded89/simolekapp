<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Metode extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('pengadaan/Metode_model');
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
        $metode = $this->Metode_model->get_all();

        $data = array(
            'metode_data' => $metode,
			'controller' => 'Metode',
			'uri1' => 'List Metode',
			'main_view' => 'pengadaan/metode/metode_list'
        );

        $this->load->view('template_view', $data);
    }

    public function read($id)
    {
        $row = $this->Metode_model->get_by_id($id);
        if ($row) {
            $data = array(
			'controller' => 'Metode',
			'uri1' => 'Data Metode',
			'main_view' => 'pengadaan/metode/metode_read',

			'id' => $row->id,
			'nama' => $row->nama,
	    );
            $this->load->view('template_view', $data);
        } else {
            $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
            redirect(site_url('pengadaan/metode'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Simpan',
            'action' => site_url('pengadaan/metode/create_action'),
			'controller' => 'Metode',
			'uri1' => 'Tambah Metode',
			'main_view' => 'pengadaan/metode/metode_form',

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

            $this->Metode_model->insert($data);
            $this->session->set_flashdata('message', 'Data Berhasil Ditambahkan');
            redirect(site_url('pengadaan/metode'));
        }
    }

    public function update($id)
    {
        $row = $this->Metode_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('pengadaan/metode/update_action'),
				'controller' => 'Metode',
				'uri1' => 'Update Metode',
				'main_view' => 'pengadaan/metode/metode_form',

			'id' => set_value('id', $row->id),
			'nama' => set_value('nama', $row->nama),
	    );
            $this->load->view('template_view', $data);
        } else {
            $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
            redirect(site_url('pengadaan/metode'));
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

            $this->Metode_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Data Berhasil');
            redirect(site_url('pengadaan/metode'));
        }
    }

    public function delete($id)
    {
        $row = $this->Metode_model->get_by_id($id);

        if ($row) {
            $this->Metode_model->delete($id);
            $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
            redirect(site_url('pengadaan/metode'));
        } else {
            $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
            redirect(site_url('pengadaan/metode'));
        }
    }

    public function _rules()
    {
	$this->form_validation->set_rules('nama', 'nama', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Metode.php */
/* Location: ./application/controllers/Metode.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-05-27 13:26:02 */
/* http://harviacode.com */
?>