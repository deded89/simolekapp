<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Report extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    if (!$this->ion_auth->logged_in())
    {
      redirect('auth/login', 'refresh');
    }else if (!$this->ion_auth->in_group('pengelola')) {
      return show_error('Anda tidak memiliki akses untuk melihat halaman ini, halaman hanya untuk Pengelola.');
    }
    $this->load->model('pengadaan/Report_model');
    // $this->load->model('pengadaan/Pekerjaan_model');
  }

  // function index()
  // {
  //   $tanggal = date("Y-m-d");
  //   $pagu = 'all';
  //   $pekerjaan = $this->Report_model->tepra();
  //   $count = $this->Report_model->tepra_show_count($tanggal,$pagu);
  //   $data = array(
  //     'pekerjaan_data' => $pekerjaan,
  //     'count_data' => $count,
  //     'controller' => 'Report',
  //     'uri1' => 'TEPRA',
  //     'main_view' => 'pengadaan/report/tepra',
  //     'filter_tanggal' => $tanggal,
  //     'filter_pagu' => $pagu,
  //   );
  //
  //   $this->load->view('template_view', $data);
  // }

  function filter()
  {
    $data = $this->Report_model->tepra_filter();
    echo '<pre>'; var_dump($data); echo '</pre>'; exit;
    if (empty($_POST)){
      $tanggal = date("Y-m-d");
      $pagu = 'all';
    }else{
      $tanggal = $this->input->post('filter_tanggal',true);
      $pagu = $this->input->post('pagu',true);
    }
    $pekerjaan = $this->Report_model->tepra_filter($tanggal,$pagu);
    $count = $this->Report_model->tepra_show_count($tanggal,$pagu);
    $data = array(
      'pekerjaan_data' => $pekerjaan,
      'count_data' => $count,
      'controller' => 'Report',
      'uri1' => 'TEPRA',
      'main_view' => 'pengadaan/report/tepra',
      'filter_tanggal' => $tanggal,
      'filter_pagu' => $pagu,
      'pagu' => set_value('pagu'),
    );
    $this->load->view('template_view', $data);
  }

  function cetak($tanggal,$pagu){
    $pekerjaan_data = $this->Report_model->tepra_filter($tanggal,$pagu);
    $spreadsheet = new Spreadsheet();

    $sheet = $spreadsheet->getActiveSheet();

    //STYLE FOR TABLE HEADER
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
            'argb' => 'FFA0A0A0',
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
      ],
    ];

    //SETTING KOLOM
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->getColumnDimension('B')->setAutoSize(false);
    $sheet->getColumnDimension('B')->setWidth(40);

    $sheet->getColumnDimension('C')->setAutoSize(false);
    $sheet->getColumnDimension('C')->setWidth(30);

    $sheet->getColumnDimension('D')->setAutoSize(false);
    $sheet->getColumnDimension('D')->setWidth(20);

    $sheet->getColumnDimension('E')->setAutoSize(false);
    $sheet->getColumnDimension('E')->setWidth(14);

    $sheet->getColumnDimension('F')->setAutoSize(false);
    $sheet->getColumnDimension('F')->setWidth(12);

    $sheet->getColumnDimension('G')->setAutoSize(true);

    $sheet->getColumnDimension('H')->setAutoSize(false);
    $sheet->getColumnDimension('H')->setWidth(12);

    $sheet->getColumnDimension('I')->setAutoSize(true);
    $sheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->getColumnDimension('J')->setAutoSize(false);
    $sheet->getColumnDimension('J')->setWidth(40);

    $sheet->getColumnDimension('K')->setAutoSize(false);
    $sheet->getColumnDimension('K')->setWidth(12);

    $sheet->getColumnDimension('L')->setAutoSize(true);
    $sheet->getStyle('L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    $sheet->getColumnDimension('M')->setAutoSize(true);

    $sheet->getColumnDimension('N')->setAutoSize(false);
    $sheet->getColumnDimension('N')->setWidth(40);

    $sheet->getColumnDimension('O')->setAutoSize(true);
    $sheet->getColumnDimension('P')->setAutoSize(true);

    $sheet->getColumnDimension('Q')->setAutoSize(false);
    $sheet->getColumnDimension('Q')->setWidth(40);

    $sheet->getColumnDimension('R')->setAutoSize(true);

    // TABLE DATA
    $i=6;
    $i_awal = $i;
    $nomor=1;
    foreach($pekerjaan_data as $pekerjaan) {
      //WRITE DATA
      $sheet->setCellValue('A'.$i, $nomor);
      $sheet->setCellValue('B'.$i, $pekerjaan->nama_pekerjaan);
      $sheet->setCellValue('C'.$i, $pekerjaan->kegiatan);
      $sheet->setCellValue('D'.$i, $pekerjaan->nama_skpd);
      $sheet->setCellValue('E'.$i, $pekerjaan->jenis);
      $sheet->setCellValue('F'.$i, $pekerjaan->metode);
      $sheet->setCellValue('G'.$i, $pekerjaan->pagu);
      $sheet->setCellValue('H'.$i, $pekerjaan->progress_sekarang);
      $sheet->setCellValue('I'.$i, $pekerjaan->tgl_progress);
      $sheet->setCellValue('J'.$i, $pekerjaan->ket_progress);
      $sheet->setCellValue('K'.$i, $pekerjaan->progress_next);
      $sheet->setCellValue('L'.$i, $pekerjaan->tgl_n_progress);
      if ($pekerjaan->nomor){
        $date1=date_create($pekerjaan->awal);
        $date2=date_create($pekerjaan->akhir);
        $date2=$date2->modify('+1 day');
        $masa=date_diff($date1,$date2);

        $sheet->setCellValue('M'.$i, $pekerjaan->nilai);
        $sheet->setCellValue('N'.$i, 'Nomor: '.$pekerjaan->nomor.', Penyedia: '.$pekerjaan->penyedia.', Tanggal: '.$pekerjaan->tanggal.', Masa Kontrak: '.$pekerjaan->awal.' s.d '.$pekerjaan->akhir.$masa->format(" (%r%a Hari Kalender)"));
      }
      $sheet->setCellValue('O'.$i, $pekerjaan->real_keu);
      $sheet->setCellValue('P'.$i, $pekerjaan->real_fisik);
      if ($pekerjaan->nomor_st){
        $sheet->setCellValue('Q'.$i, 'Nomor: '.$pekerjaan->nomor_st.', Penyedia: '.$pekerjaan->penyedia_st.', Tanggal: '.$pekerjaan->tanggal_st);
      }
      $date1=date_create($tanggal);
      $date2=date_create($pekerjaan->tgl_n_progress);
      $diff=date_diff($date1,$date2);
      $sheet->setCellValue('R'.$i, $diff->format("%R%a"));

      //NUMBERING FORMAT
      $sheet->getStyle('G'.$i)->getNumberFormat()->setFormatCode(('#,##0'));
      $sheet->getStyle('M'.$i)->getNumberFormat()->setFormatCode(('#,##0'));
      $sheet->getStyle('O'.$i)->getNumberFormat()->setFormatCode(('#,##0'));
      $sheet->getStyle('P'.$i)->getNumberFormat()->setFormatCode(('#,##0'));

      //WRAPPING TEXT
      $sheet->getStyle('B'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('C'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('D'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('E'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('F'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('H'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('J'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('K'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('N'.$i)->getAlignment()->setWrapText(true);
      $sheet->getStyle('Q'.$i)->getAlignment()->setWrapText(true);

      $i++;
      $nomor++;
    }

    $i_akhir = $i-1;

    //conditional formatting untuk STATUS
    $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
    $conditional1->addCondition('0');
    $conditional1->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
    $conditional1->getStyle()->getFont()->setBold(true);

    $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('R'.$i_awal)->getConditionalStyles();
    $conditionalStyles[] = $conditional1;

    $sheet->getStyle('R'.$i_awal.':R'.$i_akhir)->setConditionalStyles($conditionalStyles);
    // $sheet->duplicateStyle($sheet->getStyle('M'.$i_awal),'M'.$i_awal.':M'.$i_akhir);

    //BORDERING TABLE DATA
    $sheet->getStyle('A'.$i_awal.':R'.$i_akhir)->applyFromArray($style_table_data);

    // SETTING HEADER TABLE VALUE
    $hr = 5;
		$sheet->setCellValue('A'.$hr, 'NO');
		$sheet->setCellValue('B'.$hr, 'NAMA PEKERJAAN');
		$sheet->setCellValue('C'.$hr, 'NAMA KEGIATAN');
		$sheet->setCellValue('D'.$hr, 'SKPD PELAKSANA');
		$sheet->setCellValue('E'.$hr, 'JENIS PENGADAAN');
		$sheet->setCellValue('F'.$hr, 'METODE PEMILIHAN');
    $sheet->setCellValue('G'.$hr, 'PAGU');
		$sheet->setCellValue('H'.$hr, 'PROGRESS');
		$sheet->setCellValue('I'.$hr, 'TANGGAL');
		$sheet->setCellValue('J'.$hr, 'KETERANGAN');
    $sheet->setCellValue('K'.$hr, 'NEXT PROGRESS');
    $sheet->setCellValue('L'.$hr, 'TANGGAL');
    $sheet->setCellValue('M'.$hr, 'NILAI KONTRAK');
    $sheet->setCellValue('N'.$hr, 'DATA KONTRAK');
		$sheet->setCellValue('O'.$hr, 'REALISASI KEUANGAN');
		$sheet->setCellValue('P'.$hr, 'REALISASI FISIK');
		$sheet->setCellValue('Q'.$hr, 'DATA SERAH TERIMA');
		$sheet->setCellValue('R'.$hr, 'STATUS');
    //memborder table header
    $sheet->getStyle('A'.$hr.':R'.$hr)->applyFromArray($style_table_header);

    //JUDUL LAPORAN
    $sheet->setCellValue('A1','LAPORAN KONDISI TERAKHIR PEKERJAAN');
    $sheet->getStyle('A1')->getFont()->setSize(16);
    $sheet->getStyle('A1')->getFont()->setBold(true);
    $sheet->mergeCells('A1:R1');

    $tanggal = date_create($tanggal)->format('d M Y');
    $sheet->setCellValue('A2','Kondisi s.d Tanggal '.$tanggal);
    $sheet->getStyle('A2')->getFont()->setSize(12);
    $sheet->mergeCells('A2:R2');

    if ($pagu == 'l200'){$pagu = 'Pagu bernilai > 200 Juta s.d 2,5 Milyar';}
    if ($pagu == 'l25'){$pagu = 'Pagu bernilai > 2,5 Milyar s.d 50 Milyar';}
    if ($pagu == 'l50'){$pagu = 'Pagu bernilai > 50 Milyar';}

    if ($pagu <> 'all'){
      $sheet->setCellValue('A3',$pagu);
      $sheet->getStyle('A3')->getFont()->setSize(12);
      $sheet->mergeCells('A3:R3');
    }

    //SETTING AUTO FILTER
    $sheet->setAutoFilter('A'.$hr.':R'.$i_akhir);

    //MEMBUAT COUNT PER PROGRESS ///////////////////////////////////////###############################################
    $htbl2 = $i + 2;
    $dtbl2 = $i + 3;

    //SETTING DATA TABEL 2
    $sheet->setCellValue('B'. ($dtbl2 + 0) ,'Persiapan');
    $sheet->setCellValue('B'. ($dtbl2 + 1) ,'Pemilihan Penyedia');
    $sheet->setCellValue('B'. ($dtbl2 + 2) ,'Hasil Pemilihan');
    $sheet->setCellValue('B'. ($dtbl2 + 3) ,'Kontrak');
    $sheet->setCellValue('B'. ($dtbl2 + 4) ,'Serah Terima (PHO)');
    $sheet->setCellValue('B'. ($dtbl2 + 5) ,'Serah Terima (FHO)');
    $sheet->setCellValue('B'. ($dtbl2 + 6) ,'Dibatalkan');

    $sheet->setCellValue('C'. ($dtbl2 + 0) ,'=COUNTIF(E'.$i_awal.':E'.$i_akhir.',"Persiapan")');
    $sheet->setCellValue('C'. ($dtbl2 + 1) ,'=COUNTIF(E'.$i_awal.':E'.$i_akhir.',"Pemilihan Penyedia")');
    $sheet->setCellValue('C'. ($dtbl2 + 2) ,'=COUNTIF(E'.$i_awal.':E'.$i_akhir.',"Hasil Pemilihan")');
    $sheet->setCellValue('C'. ($dtbl2 + 3) ,'=COUNTIF(E'.$i_awal.':E'.$i_akhir.',"Kontrak")');
    $sheet->setCellValue('C'. ($dtbl2 + 4) ,'=COUNTIF(E'.$i_awal.':E'.$i_akhir.',"Serah Terima (PHO)")');
    $sheet->setCellValue('C'. ($dtbl2 + 5) ,'=COUNTIF(E'.$i_awal.':E'.$i_akhir.',"Serah Terima Akhir (FHO)")');
    $sheet->setCellValue('C'. ($dtbl2 + 6) ,'=COUNTIF(E'.$i_awal.':E'.$i_akhir.',"Dibatalkan")');

    //BORDERING TABLE DATA
    $sheet->getStyle('B'.($dtbl2 + 0).':C'.($dtbl2 + 6))->applyFromArray($style_table_data);
    //STYLING TABEL 2 DATA
    $sheet->getStyle('C'.($dtbl2 + 0).':C'.($dtbl2 + 6))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

    //SETTING HEADER TABEL 2
    $sheet->setCellValue('B'.$htbl2,'Progress');
    $sheet->setCellValue('C'.$htbl2,'Jumlah Pekerjaan');
    //memborder table header
    $sheet->getStyle('B'.$htbl2.':C'.$htbl2)->applyFromArray($style_table_header);



		$writer = new Xlsx($spreadsheet);

		$filename = 'Kondisi Pekerjaan '.$pagu.' Update '.date('Y-m-d H:m:i');

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

}
