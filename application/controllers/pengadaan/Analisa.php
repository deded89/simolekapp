<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 use PhpOffice\PhpSpreadsheet\Spreadsheet;
 use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Analisa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengadaan/Analisa_model');
	}

	public function index()
	{

		//DATA REQUEST
		$tender = $this->Analisa_model->get_by_metode(2);
		$tender_cepat = $this->Analisa_model->get_by_metode(3);
		$epurchasing = $this->Analisa_model->get_by_metode(4);
		$seleksi = $this->Analisa_model->get_by_metode(5);
		$penunjukan_langsung = $this->Analisa_model->get_by_metode(6);

		$batal_tender = $this->Analisa_model->get_metode_batal(2);
		$batal_tender_cepat = $this->Analisa_model->get_metode_batal(3);
		$batal_epurchasing = $this->Analisa_model->get_metode_batal(4);
		$batal_seleksi = $this->Analisa_model->get_metode_batal(5);
		$batal_penunjukan_langsung = $this->Analisa_model->get_metode_batal(6);

		$bp_tender = $this->Analisa_model->get_metode_belum_progress(2);
		$bp_tender_cepat = $this->Analisa_model->get_metode_belum_progress(3);
		$bp_epurchasing = $this->Analisa_model->get_metode_belum_progress(4);
		$bp_seleksi = $this->Analisa_model->get_metode_belum_progress(5);
		$bp_penunjukan_langsung = $this->Analisa_model->get_metode_belum_progress(6);

		$tu_tender = $this->Analisa_model->get_tidak_update(2);
		$tu_tender_cepat = $this->Analisa_model->get_tidak_update(3);
		$tu_epurchasing = $this->Analisa_model->get_tidak_update(4);
		$tu_seleksi = $this->Analisa_model->get_tidak_update(5);
		$tu_penunjukan_langsung = $this->Analisa_model->get_tidak_update(6);
		$spreadsheet = new Spreadsheet();
      	$sheet = $spreadsheet->getActiveSheet();

      	//STYLING
      	
      	//**STYLE FOR TABLE HEADER
	      $style_table_header = [
	        'font' => [
	            'bold' => true,
	        ],
	        'alignment' => [
	            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
	            'wrapText' =>true,
	        ],
	      ];

	    $sheet->getStyle('B2:G2')->applyFromArray($style_table_header);

      	$sheet->getColumnDimension('A')->setAutoSize(true);
      	
      	$sheet->getColumnDimension('B')->setAutoSize(false);
     	$sheet->getColumnDimension('B')->setWidth(11);

     	$sheet->getColumnDimension('C')->setAutoSize(false);
     	$sheet->getColumnDimension('C')->setWidth(11);

     	$sheet->getColumnDimension('D')->setAutoSize(false);
     	$sheet->getColumnDimension('D')->setWidth(11);

     	$sheet->getColumnDimension('E')->setAutoSize(false);
     	$sheet->getColumnDimension('E')->setWidth(11);
		
		$sheet->getColumnDimension('F')->setAutoSize(false);
     	$sheet->getColumnDimension('F')->setWidth(11);
		
		$sheet->getColumnDimension('G')->setAutoSize(false);
     	$sheet->getColumnDimension('G')->setWidth(11);
		

      	//DATA WRITING
      	$sheet->setCellValue('B2','Total');
      	$sheet->setCellValue('C2','Batal');
      	$sheet->setCellValue('D2','Aktif');
      	$sheet->setCellValue('E2','Belum Ada Progress');
      	$sheet->setCellValue('F2','Sedang Berproses');
      	$sheet->setCellValue('G2','Terlambat');
      	
      	$row_start = 3;
      	$sheet->setCellValue('A'.$row_start,'Tender');
      	$sheet->setCellValue('A'.($row_start+1),'Tender Cepat');
      	$sheet->setCellValue('A'.($row_start+2),'Seleksi');
      	$sheet->setCellValue('A'.($row_start+3),'E-Purchasing');
      	$sheet->setCellValue('A'.($row_start+4),'Penunjukan Langsung');

      	$sheet->setCellValue('B'.$row_start,$tender->c_metode);
      	$sheet->setCellValue('B'.($row_start+1),$tender_cepat->c_metode);
      	$sheet->setCellValue('B'.($row_start+2),$seleksi->c_metode);
      	$sheet->setCellValue('B'.($row_start+3),$epurchasing->c_metode);
      	$sheet->setCellValue('B'.($row_start+4),$penunjukan_langsung->c_metode);

      	$sheet->setCellValue('C'.$row_start,$batal_tender->c_batal);
      	$sheet->setCellValue('C'.($row_start+1),$batal_tender_cepat->c_batal);
      	$sheet->setCellValue('C'.($row_start+2),$batal_seleksi->c_batal);
      	$sheet->setCellValue('C'.($row_start+3),$batal_epurchasing->c_batal);
      	$sheet->setCellValue('C'.($row_start+4),$batal_penunjukan_langsung->c_batal);


      	$sheet->setCellValue('E'.$row_start,$bp_tender->c_belum_progress);
      	$sheet->setCellValue('E'.($row_start+1),$bp_tender_cepat->c_belum_progress);
      	$sheet->setCellValue('E'.($row_start+2),$bp_seleksi->c_belum_progress);
      	$sheet->setCellValue('E'.($row_start+3),$bp_epurchasing->c_belum_progress);
      	$sheet->setCellValue('E'.($row_start+4),$bp_penunjukan_langsung->c_belum_progress);

      	$sheet->setCellValue('G'.$row_start,$tu_tender->c_tidak_update);
      	$sheet->setCellValue('G'.($row_start+1),$tu_tender_cepat->c_tidak_update);
      	$sheet->setCellValue('G'.($row_start+2),$tu_seleksi->c_tidak_update);
      	$sheet->setCellValue('G'.($row_start+3),$tu_epurchasing->c_tidak_update);
      	$sheet->setCellValue('G'.($row_start+4),$tu_penunjukan_langsung->c_tidak_update);

      	for ($i=0; $i <5 ; $i++) { 
			$sheet->setCellValue('D'.($row_start+$i),"=B".($row_start+$i)."-C".($row_start+$i));
      	}

      	for ($i=0; $i <5 ; $i++) { 
			$sheet->setCellValue('F'.($row_start+$i),"=D".($row_start+$i)."-E".($row_start+$i));
      	}


      	//WRITE UOTPUT
      	$filename = 'Anlisa Keterisian Data '.date('Y-m-d H:m:i');
  		header('Content-Type: application/vnd.ms-excel');
  		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
  		header('Cache-Control: max-age=0');

	    $writer = new Xlsx($spreadsheet);
	    $writer->save('php://output');
	}

}

/* End of file Analisa.php */
/* Location: ./application/controllers/Analisa.php */