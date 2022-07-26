<?php

if (!defined('BASEPATH'))
exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Permasalahan extends CI_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->model('lkpk/Permasalahan_model');
    $this->load->model('lkpk/Kegiatan_model');
    $this->load->library('form_validation');
  }

  public function list($status=null)
  {
    $permasalahan = $this->Permasalahan_model->get_all($status);

    $data = array(
      'permasalahan_data' => $permasalahan,
      'controller' => 'Permasalahan',
      'uri1' => 'List Permasalahan',
      'main_view' => 'lkpk/permasalahan/permasalahan_list'
    );

    $this->load->view('template_view', $data);
  }

  public function perkegiatan($id)
  {
    $permasalahan = $this->Permasalahan_model->get_perkegiatan($id);

    $data = array(
      'permasalahan_data' => $permasalahan,
      'controller' => 'Permasalahan',
      'uri1' => 'List Permasalahan',
      'main_view' => 'lkpk/permasalahan/permasalahan_list'
    );

    $this->load->view('template_view', $data);
  }

  public function read($id)
  {
    $row = $this->Permasalahan_model->get_by_id($id);
    if ($row) {
      $data = array(
        'controller' => 'Permasalahan',
        'uri1' => 'Data Permasalahan',
        'main_view' => 'lkpk/permasalahan/permasalahan_read',

        'id_masalah' => $row->id_masalah,
        'masalah' => $row->masalah,
        'sejak' => $row->sejak,
        'upaya' => $row->upaya,
        'arahan' => $row->arahan,
        'status' => $row->status,
        'kegiatan' => $row->kegiatan,
      );
      $this->load->view('template_view', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(site_url('lkpk/permasalahan'));
    }
  }

  public function create($id_keg)
  {
    $kegiatan_data = $this->Kegiatan_model->get_by_id($id_keg);
    $data = array(
      'button' => 'Simpan',
      'action' => site_url('lkpk/permasalahan/create_action/'.$id_keg),
      'controller' => 'Permasalahan',
      'uri1' => 'Tambah Permasalahan',
      'main_view' => 'lkpk/permasalahan/permasalahan_form',

      'id_masalah' => set_value('id_masalah'),
      'masalah' => set_value('masalah'),
      'sejak' => set_value('sejak'),
      'upaya' => set_value('upaya'),
      'pihak' => set_value('pihak'),
      'arahan' => set_value('arahan'),
      'status' => set_value('status'),
      'kegiatan' => $kegiatan_data->nama_kegiatan,
      'id_kegiatan' => $kegiatan_data->id_kegiatan,

    );
    $this->load->view('template_view', $data);
  }

  public function create_action($id_keg)
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->create($id_keg);
    } else {
      $data = array(
        'masalah' => $this->input->post('masalah',TRUE),
        'sejak' => $this->input->post('sejak',TRUE),
        'upaya' => $this->input->post('upaya',TRUE),
        'pihak' => $this->input->post('pihak',TRUE),
        'arahan' => $this->input->post('arahan',TRUE),
        'status' => $this->input->post('status',TRUE),
        'kegiatan' => $this->input->post('kegiatan',TRUE),
      );

      $this->Permasalahan_model->insert($data);
      $this->session->set_flashdata('message', 'Data Berhasil Ditambahkan');
      redirect(site_url('lkpk/kegiatan/read/'.$id_keg));
    }
  }

  public function update($id,$id_keg)
  {
    $kegiatan_data = $this->Kegiatan_model->get_by_id($id_keg);
    $row = $this->Permasalahan_model->get_by_id($id);

    if ($row) {
      $data = array(
        'button' => 'Update',
        'action' => site_url('lkpk/permasalahan/update_action/'.$id_keg),
        'controller' => 'Permasalahan',
        'uri1' => 'Update Permasalahan',
        'main_view' => 'lkpk/permasalahan/permasalahan_form',

        'id_masalah' => set_value('id_masalah', $row->id_masalah),
        'masalah' => set_value('masalah', $row->masalah),
        'sejak' => set_value('sejak', $row->sejak),
        'upaya' => set_value('upaya', $row->upaya),
        'pihak' => set_value('pihak', $row->bantuan_pihak),
        'arahan' => set_value('arahan', $row->arahan),
        'status' => set_value('status', $row->status),
        'kegiatan' => $kegiatan_data->nama_kegiatan,
        'id_kegiatan' => $kegiatan_data->id_kegiatan,
      );
      $this->load->view('template_view', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(site_url('lkpk/kegiatan/read/'.$id_keg));
    }
  }

  public function update_action($id_keg)
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_masalah', TRUE),$id_keg);
    } else {
      $data = array(
        'masalah' => $this->input->post('masalah',TRUE),
        'sejak' => $this->input->post('sejak',TRUE),
        'upaya' => $this->input->post('upaya',TRUE),
        'bantuan_pihak' => $this->input->post('pihak',TRUE),
        'arahan' => $this->input->post('arahan',TRUE),
        'solusi' => $this->input->post('solusi',TRUE),
        'status' => $this->input->post('status',TRUE),
        'kegiatan' => $this->input->post('kegiatan',TRUE),
      );

      $this->Permasalahan_model->update($this->input->post('id_masalah', TRUE), $data);
      $this->session->set_flashdata('message', 'Update Data Berhasil');
      redirect(site_url('lkpk/kegiatan/read/'.$id_keg));
    }
  }

  public function input_solusi($id,$id_keg)
  {
    $row = $this->Permasalahan_model->get_by_id($id);

    if ($row) {
      $data = array(
        'button' => 'Simpan',
        'action' => site_url('lkpk/permasalahan/input_solusi_action/'.$id_keg),
        'controller' => 'Permasalahan',
        'uri1' => 'Input Solusi Permasalahan',
        'main_view' => 'lkpk/permasalahan/solusi_form',

        'solusi' => set_value('solusi', $row->solusi),
        'id_masalah' => set_value('id_masalah', $row->id_masalah),
        'id_kegiatan' => $id_keg,
      );
      $this->load->view('template_view', $data);
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(site_url('lkpk/kegiatan/read/'.$id_keg));
    }
  }

  public function input_solusi_action($id_keg)
  {
    $this->_rules_solusi();

    if ($this->form_validation->run() == FALSE) {
      $this->input_solusi($this->input->post('id_masalah', TRUE),$id_keg);
    } else {
      $data = array(
        'solusi' => $this->input->post('solusi',TRUE),
        'status' => $this->input->post('status',TRUE),
        'selesai' => $this->input->post('selesai',TRUE),
      );

      $this->Permasalahan_model->input_solusi($this->input->post('id_masalah', TRUE), $data);
      $this->session->set_flashdata('message', 'Input Solusi Berhasil');
      redirect(site_url('lkpk/kegiatan/read/'.$id_keg));
    }
  }

  public function delete($id, $id_keg)
  {
    $row = $this->Permasalahan_model->get_by_id($id);

    if ($row) {
      $this->Permasalahan_model->delete($id);
      $this->session->set_flashdata('message', 'Data Berhasil Dihapus');
      redirect(site_url('lkpk/kegiatan/read/'.$id_keg));
    } else {
      $this->session->set_flashdata('message', 'Data Tidak Ditemukan');
      redirect(site_url('lkpk/kegiatan/read/'.$id_keg));
    }
  }

  public function _rules()
  {
    $this->form_validation->set_rules('masalah', 'masalah', 'trim|required|min_length[50]');
    $this->form_validation->set_message('min_length', 'Kolom %s: Minimal diisi %s Karakter');

    $this->form_validation->set_rules('status', 'status', 'trim|required');
    $this->form_validation->set_rules('kegiatan', 'kegiatan', 'trim|required');
    $this->form_validation->set_rules('sejak', 'sejak', 'trim|required');

    $this->form_validation->set_rules('id_masalah', 'id_masalah', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }

  public function _rules_solusi()
  {
    $this->form_validation->set_rules('solusi', 'solusi', 'trim|required|min_length[50]');
    $this->form_validation->set_message('min_length', 'Kolom %s: Minimal diisi %s Karakter');

    $this->form_validation->set_rules('id_masalah', 'id_masalah', 'trim');
    $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
  }

  public function cetak($status = null){
    $permasalahan = $this->Permasalahan_model->get_all($status);

    $style_table_header = [
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        'wrapText' => true,
      ],
      'borders' => [
        'allBorders' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
      ],
      'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => [
          'argb' => 'D7D3D3',
        ],
      ],
    ];

    // STYLING TABLE DATA
    $style_table_data = [
      'borders' => [
        'outline' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'vertical' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
        'horizontal' => [
          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED,
        ],
      ],
      'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
        'wrapText' => true,
      ],
    ];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    //WRITE DATA
    $sheet->setCellValue('A1','LAPORAN PERMASALAHAN PELAKSANAAN KEGIATAN DAN PENYERAPAN ANGGARAN');
    $sheet->mergeCells('A1:I1');
    $sheet->setCellValue('A2','PEMERINTAH KOTA BANJARMASIN');
    $sheet->mergeCells('A2:I2');
    $sheet->setCellValue('A3','KONDISI '.date('d-m-Y'));
    $sheet->mergeCells('A3:I3');
    $sheet->setCellValue('A6','NO');
    $sheet->setCellValue('B6','SKPD');
    $sheet->setCellValue('C6','PERMASALAHAN');
    $sheet->setCellValue('D6','SEJAK');
    $sheet->setCellValue('E6','UPAYA YANG SUDAH DILAKUKAN');
    $sheet->setCellValue('F6','PIHAK EKSTERNAL SKPD YANG DIHARAPKAN DAPAT MEMBANTU');
    $sheet->setCellValue('G6','ARAHAN');
    $sheet->setCellValue('H6','STATUS');
    $sheet->setCellValue('I6','SOLUSI');

    //FORMATTING
    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(35);
    $sheet->getColumnDimension('C')->setWidth(35);
    $sheet->getColumnDimension('D')->setWidth(13);
    $sheet->getColumnDimension('E')->setWidth(35);
    $sheet->getColumnDimension('F')->setWidth(35);
    $sheet->getColumnDimension('G')->setWidth(35);
    $sheet->getColumnDimension('H')->setWidth(10);
    $sheet->getColumnDimension('I')->setWidth(35);
    $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A6:I6')->applyFromArray($style_table_header);

    $dr_awal = 8;
    $dr = $dr_awal;
    $i = 1;
    foreach ($permasalahan as $masalah) {
      $sheet->setCellValue('A'.$dr,$i);
      $sheet->setCellValue('B'.$dr,$masalah->nama_skpd);
      $sheet->setCellValue('C'.$dr,$masalah->masalah.' (Kegiatan '.$masalah->nama_kegiatan.')');
      $sheet->setCellValue('D'.$dr,$masalah->sejak);
      $sheet->setCellValue('E'.$dr,$masalah->upaya);
      $sheet->setCellValue('F'.$dr,$masalah->bantuan_pihak);
      $sheet->setCellValue('G'.$dr,$masalah->arahan);
      if ($masalah->status <> null){
        $status = $masalah->status;
        if ($status == 1){
          $status = 'closed';
        }else{
          $status = 'open';
        }
      }
      $sheet->setCellValue('H'.$dr,$status);
      $sheet->setCellValue('I'.$dr,$masalah->solusi);
      $dr++;
      $i++;
    }
    $dr_akhir = $dr-1;

    $sheet->setCellValue('H'.($dr_akhir+3),'Banjarmasin,                 2019');
    $sheet->setCellValue('H'.($dr_akhir+4),'Sekretaris Daerah,');
    $sheet->setCellValue('H'.($dr_akhir+8),'Nama');
    $sheet->setCellValue('H'.($dr_akhir+9),'Nip.');

    //FORMATTING
    $sheet->getStyle('A'.($dr_awal-1).':I'.$dr)->applyFromArray($style_table_data);

    //page setting
    $sheet->setAutoFilter('B'.($dr_awal-1).':I'.($dr_akhir));
    $sheet->freezePane('C'.$dr_awal);
    $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(6, 7);
    $sheet->getPageSetup()->setPrintArea('A1:I'.($dr_akhir+9));
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getPageSetup()->setFitToHeight(0);
    //output
    $filename = 'Laporan Permasalahan';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
  }
}

/* End of file Permasalahan.php */
/* Location: ./application/controllers/Permasalahan.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-11-26 14:40:23 */
/* http://harviacode.com */
?>
