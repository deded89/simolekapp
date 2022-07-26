<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class Lra_keu extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
   $this->load->model(array('lkpk/Lra_keu_model'));
  }

  function hal_download_format(){
    $data = array(
      'controller' => 'LRA',
      'uri1' => 'Download Format',
      'main_view' => 'lkpk/lra_keu/hal_download_format',
      'action' => site_url('lkpk/lra_keu/download_format'),
      'periode' => set_value('periode'),
    );

    $this->load->view('template_view', $data);
  }

  function download_format(){
    $periode = $this->input->post('periode',true);
    $bulan_column = array('b01','b02','b03','b04','b05','b06','b07','b08','b09','b01','b11','b12');
    $nama_bulan = array('Januari','Pebruari','Maret','April','Mei','Juni','Juni','Agustus','September','Oktober','Nopember','Desember');
    $bulan = $this->input->post('bulan_laporan',true);
    $bulan_laporan = $bulan_column[$bulan];
    $skpd_data = $this->Lra_keu_model->get_data_skpd();
    $lra_data = $this->Lra_keu_model->get_data_lra($periode);

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

    $style_table_total = [
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
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

    // write data
    $sheet->setCellValue('A1','FORMAT UPLOAD DATA LRA');
    $sheet->mergeCells('A1:E1');
    $sheet->setCellValue('A2','BULAN '.strtoupper($nama_bulan[$bulan]));
    $sheet->mergeCells('A2:E2');
    $sheet->setCellValue('A4','NO');
    $sheet->setCellValue('B4','NAMA SKPD');
    $sheet->setCellValue('C4','NILAI LRA (Rp)');
    $sheet->setCellValue('D4','ID_SKPD');
    $sheet->setCellValue('E4','ID_BULAN');
    $sheet->setCellValue('F4','PERIODE');

    // FORMATTING
    $sheet->getColumnDimension('B')->setWidth(62);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setVisible(false);
    $sheet->getColumnDimension('E')->setVisible(false);
    $sheet->getColumnDimension('F')->setVisible(false);
    $sheet->getStyle('A4:C4')->applyFromArray($style_table_header);
    $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    // write data
    $dr_awal = 6;
    $dr = $dr_awal;
    $i = 1;

    if ($lra_data){
      foreach ($lra_data as $data) {
        $sheet->setCellValue('A'.$dr,$i);
        $sheet->setCellValue('B'.$dr,$data->nama_skpd);
        $sheet->setCellValue('C'.$dr,$data->{$bulan_laporan});
        $sheet->getStyle('C'.$dr)->getNumberFormat()->setFormatCode(('#,##0.00'));
        $sheet->setCellValue('D'.$dr,$data->id_skpd);
        $sheet->setCellValue('E'.$dr,$bulan_laporan);
        $sheet->setCellValue('F'.$dr,$periode);
        $dr++;
        $i++;
      }
    } else {
      foreach ($skpd_data as $skpd) {
        // var_dump($skpd_data);die;
        $sheet->setCellValue('A'.$dr,$i);
        $sheet->setCellValue('B'.$dr,$skpd->nama_skpd);
        $sheet->getStyle('C'.$dr)->getNumberFormat()->setFormatCode(('#,##0.00'));
        $sheet->setCellValue('D'.$dr,$skpd->id_skpd);
        $sheet->setCellValue('E'.$dr,$bulan_laporan);
        $sheet->setCellValue('F'.$dr,$periode);
        $dr++;
        $i++;
      }
    }
    $dr_akhir = $dr-1;

    // FORMATTING
    $sheet->getStyle('A'.($dr_awal-1).':C'.($dr-1))->applyFromArray($style_table_data);

    //page setting
    $sheet->freezePane('A'.($dr_awal-1));

    //output
    $filename = 'Format Upload Data LRA';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

  }

  public function hal_import(){
    $data = array(
      'controller' => 'LRA',
      'uri1' => 'Import File',
      'main_view' => 'lkpk/lra_keu/hal_import',
      'action' => site_url('lkpk/lra_keu/import_action'),
    );

    $this->load->view('template_view', $data);
  }

  public function import_action(){
        $fileName = time().$_FILES['file']['name'];

        $config['upload_path'] = './temp/'; //buat folder dengan nama assets di root folder
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'xls|xlsx|csv';
        $config['max_size'] = 10000;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if(! $this->upload->do_upload('file') ){
          $this->upload->display_errors();
        }

        $media = $this->upload->data('file_name');
        $inputFileName = './temp/'.$media;

        try {
                $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
                $objReader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch(Exception $e) {
                die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            for ($row = 6; $row <= $highestRow; $row++){                  //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('C' . $row . ':' . $highestColumn . $row,
                                                NULL,
                                                TRUE,
                                                FALSE);
                //Sesuaikan sama nama kolom tabel di database
                 $data = array(
                    $rowData[0][2]=> $rowData[0][0],
                    "skpd"=> $rowData[0][1],
                    "periode_pagu"=> $rowData[0][3],
                );

                //sesuaikan nama dengan nama tabel
                $this->Lra_keu_model->insert($data);
            }
            $media = $this->upload->data('full_path');
            unlink($media);
        $this->session->set_flashdata('message','Data berhasil diimport');
        redirect(site_url('lkpk/kegiatan/'));
    }


    //#######################  DATA RENCANA

  public function compare_lra(){
    $data = array(
      'controller' => 'LRA',
      'uri1' => 'Perbandingan Data',
      'main_view' => 'lkpk/lra_keu/compare_lra',
      'action' => site_url('lkpk/lra_keu/compare_lra_action'),
      'periode' => set_value('periode'),
    );

    $this->load->view('template_view', $data);
  }

  public function compare_lra_action(){
    $periode = $this->input->post('periode',true);
    $bulan_column = array('b01','b02','b03','b04','b05','b06','b07','b08','b09','b01','b11','b12');
    $nama_bulan = array('Januari','Pebruari','Maret','April','Mei','Juni','Juni','Agustus','September','Oktober','Nopember','Desember');
    $bulan = $this->input->post('bulan_laporan',true);
    $bulan_laporan = $bulan_column[$bulan];

    $compare_data = $this->Lra_keu_model->compare_lra($periode,$bulan_laporan);

    $data = array(
      'controller' => 'LRA',
      'uri1' => 'Perbandingan Data',
      'main_view' => 'lkpk/lra_keu/compare_lra_list',
      'compare_data' => $compare_data,
      'bulan' => $bulan_laporan,
    );

    $this->load->view('template_view', $data);

  }

}
