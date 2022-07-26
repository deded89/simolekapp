<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
class Ren_real extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array('lkpk/Ren_real_model','lkpk/Lra_keu_model'));
  }

  function index(){
    $data = array(
      'controller' => 'LKPK',
      'uri1' => 'Laporan RFK',
      'main_view' => 'lkpk/kegiatan/lkpk_skpd',
      'action' => site_url('lkpk/Ren_real/laporan_rfk_skpd'),
      'tahun_anggaran' => $this->Ren_real_model->get_ta(),
      'periode_pagu' => $this->Ren_real_model->get_periode_pagu(),
    );

    $data['skpd'] = set_value('skpd');
    $this->load->view('template_view', $data);
  }

  function rfk_pemko(){
    $data = array(
      'controller' => 'LKPK',
      'uri1' => 'Laporan RFK',
      'main_view' => 'lkpk/kegiatan/lkpk_pemko',
      'action' => site_url('lkpk/Ren_real/laporan_rfk_pemko'),
      'tahun_anggaran' => $this->Ren_real_model->get_ta(),
      'periode_pagu' => $this->Ren_real_model->get_periode_pagu(),
    );
    $this->load->view('template_view', $data);
  }

  function laporan_rfk_skpd(){
    $skpd = $this->input->post('skpd',True);
    $bulan = $this->input->post('bulan_laporan',True);
    $periode = $this->input->post('periode',True);
    $ta = $this->input->post('tahun_anggaran',True);

    //validasi inputan
    if($periode =='' or $ta == '' or $skpd == ''){
      $this->session->set_flashdata('error','Mohon Pilih SKPD, Tahun Anggaran dan Periode Pagunya');
      redirect(site_url('lkpk/Ren_real/'));
    }

    $bulan_column = array('b01','b02','b03','b04','b05','b06','b07','b08','b09','b01','b11','b12');
    $nama_bulan = array('Januari','Pebruari','Maret','April','Mei','Juni','Juni','Agustus','September','Oktober','Nopember','Desember');
    $rfk = $this->Ren_real_model->get_laporan_skpd($skpd,$periode,$ta,$bulan_column[$bulan]);
    $rfk_total = $this->Ren_real_model->get_total_laporan_skpd($skpd,$periode,$ta,$bulan_column[$bulan]);
    $data = array(
      'controller' => 'LKPK',
      'uri1' => 'Laporan RFK Belanja Langsung',
      'main_view' => 'lkpk/kegiatan/laporan_rfk_skpd',
      'bulan' => $bulan,
      'nama_bulan' => $nama_bulan[$bulan],
      'ta' => $ta,
      'nama_skpd' => $rfk_total['nama_skpd'],
      'rfk_data' => $rfk,
      'rfk_total_data' => $rfk_total,
      'skpd' => $skpd,
      'periode' => $periode,
    );
    $this->load->view('template_view', $data);
  }

  function cetak_laporan_rfk_skpd($skpd,$bulan,$periode,$ta,$jenis_lap=null){
    $bulan_column = array('b01','b02','b03','b04','b05','b06','b07','b08','b09','b01','b11','b12');
    $nama_bulan = array('Januari','Pebruari','Maret','April','Mei','Juni','Juni','Agustus','September','Oktober','Nopember','Desember');

    //validasi selisih data
    // $selisih = $this->Lra_keu_model->validasi_selisih_lra($skpd,$periode,$bulan_column[$bulan]);
    // if ($selisih == false  ){
    //   $this->session->set_flashdata('error','Maaf Data tidak bisa ditampilkan, Terdapat Selisih Data LRA dan Laporan SKPD '.$selisih);
    //   redirect(site_url('lkpk/Ren_real/'));
    // }

    $rfk = $this->Ren_real_model->get_laporan_skpd($skpd,$periode,$ta,$bulan_column[$bulan],$jenis_lap);
    $rfk_total = $this->Ren_real_model->get_total_laporan_skpd($skpd,$periode,$ta,$bulan_column[$bulan]);
    $nama_skpd = $rfk_total['nama_skpd'];

    $style_table_header = [
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        // 'wrapText' => true,
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
    if ($jenis_lap <> null){
      $sheet->setCellValue('A1','LAPORAN REALISASI FISIK DAN KEUANGAN KEGIATAN '.strtoupper($jenis_lap));
    }else{
      $sheet->setCellValue('A1','LAPORAN REALISASI FISIK DAN KEUANGAN');
    }
    $sheet->mergeCells('A1:W1');
    $sheet->setCellValue('A2',strtoupper($nama_skpd));
    $sheet->mergeCells('A2:W2');
    $sheet->setCellValue('A3','TAHUN ANGGARAN '.$ta);
    $sheet->mergeCells('A3:W3');
    $sheet->setCellValue('A4','KONDISI '.strtoupper($nama_bulan[$bulan]).' '.$ta);
    $sheet->mergeCells('A4:W4');
    $sheet->setCellValue('A8','NO');
    $sheet->mergeCells('A8:A10');
    $sheet->setCellValue('B8','URAIAN KEGIATAN');
    $sheet->mergeCells('B8:B10');
    $sheet->setCellValue('C8','NILAI ANGGARAN');
    $sheet->mergeCells('C8:D8');
    $sheet->setCellValue('C9','Rp');
    $sheet->mergeCells('C9:C10');
    $sheet->setCellValue('D9','%');
    $sheet->mergeCells('D9:D10');
    $sheet->setCellValue('E8','KEUANGAN');
    $sheet->mergeCells('E8:M8');
    $sheet->setCellValue('E9','RENCANA');
    $sheet->mergeCells('E9:G9');
    $sheet->setCellValue('E10','Rp');
    $sheet->setCellValue('F10','%');
    $sheet->setCellValue('G10','%');
    $sheet->setCellValue('H9','REALISASI');
    $sheet->mergeCells('H9:J9');
    $sheet->setCellValue('H10','Rp');
    $sheet->setCellValue('I10','%');
    $sheet->setCellValue('J10','%');
    $sheet->setCellValue('K9','DEVIASI');
    $sheet->mergeCells('K9:L9');
    $sheet->setCellValue('K10','%');
    $sheet->setCellValue('L10','%');
    $sheet->setCellValue('M9','SISA ANGGARAN');
    $sheet->setCellValue('M10','Rp');
    $sheet->setCellValue('N8','FISIK');
    $sheet->mergeCells('N8:S8');
    $sheet->setCellValue('N9','REN');
    $sheet->mergeCells('N9:O9');
    $sheet->setCellValue('N10','%');
    $sheet->setCellValue('O10','%');
    $sheet->setCellValue('P9','REAL');
    $sheet->mergeCells('P9:Q9');
    $sheet->setCellValue('P10','%');
    $sheet->setCellValue('Q10','%');
    $sheet->setCellValue('R9','DEV');
    $sheet->mergeCells('R9:S9');
    $sheet->setCellValue('R10','%');
    $sheet->setCellValue('S10','%');
    $sheet->setCellValue('T8','CAPAIAN');
    $sheet->mergeCells('T8:W8');
    $sheet->setCellValue('T9','KEU');
    $sheet->setCellValue('T10','%');
    $sheet->setCellValue('U9','FISIK');
    $sheet->setCellValue('U10','%');
    $sheet->setCellValue('V9','RATA2');
    $sheet->setCellValue('V10','%');
    $sheet->setCellValue('W9','KAT');
    $sheet->mergeCells('W9:W10');

    // FORMATTING
    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(35);

    $rupiah_col = array('C','E','H','M');
    foreach ($rupiah_col as $col) {
      $sheet->getColumnDimension($col)->setWidth(18);
    }
    $persen_col = array('D','F','G','I','J','K','L','N','O','P','Q','R','S','T','U','V','W');
    foreach ($persen_col as $col) {
      $sheet->getColumnDimension('D')->setWidth(7);
    }
    $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A8:W10')->applyFromArray($style_table_header);

    $hide_col = array ('G','J','L','O','Q','S');
    foreach ($hide_col as $col) {
      $sheet->getColumnDimension($col)->setVisible(false);
    }
    // TABEL DATA
    $dr_awal = 12;
    $dr = 12;
    $i = 1;
    foreach ($rfk as $data){
      $sheet->setCellValue('A'.$dr,$i);
      $sheet->setCellValue('B'.$dr,$data['nama_kegiatan']);
      $sheet->setCellValue('C'.$dr,$data['nilai']);
      $sheet->setCellValue('E'.$dr,$data['ren_keu']);
      $sheet->setCellValue('F'.$dr,'=E'.$dr.'/C'.$dr.'*100');
      $sheet->setCellValue('H'.$dr,$data['real_keu']);
      $sheet->setCellValue('I'.$dr,'=H'.$dr.'/C'.$dr.'*100');
      $sheet->setCellValue('K'.$dr,'=I'.$dr.'-F'.$dr);
      $sheet->setCellValue('M'.$dr,'=C'.$dr.'-H'.$dr);
      $sheet->setCellValue('N'.$dr,$data['ren_fisik']);
      $sheet->setCellValue('P'.$dr,$data['real_fisik']);
      $sheet->setCellValue('R'.$dr,'=P'.$dr.'-N'.$dr);
      $sheet->setCellValue('T'.$dr,'=IF(F'.$dr.'>0,I'.$dr.'/F'.$dr.'*100,I'.$dr.')');
      $sheet->setCellValue('U'.$dr,'=IF(N'.$dr.'>0,P'.$dr.'/N'.$dr.'*100,P'.$dr.')');
      $sheet->setCellValue('V'.$dr,'=AVERAGE(T'.$dr.':U'.$dr.')');
      $sheet->setCellValue('W'.$dr,'=V'.$dr.'');
      $dr++;
      $i++;
    }
    $dr_akhir = $dr-1;
    $dr_total = $dr+1;
    $sheet->setCellValue('B'.$dr_total,'TOTAL');
    $sheet->setCellValue('c'.$dr_total,'=SUM(C'.$dr_awal.':C'.$dr_akhir.')');
    $sheet->setCellValue('T'.$dr_total,'=IF(G'.$dr_total.'>0,J'.$dr_total.'/G'.$dr_total.'*100,J'.$dr_total.')');
    $sheet->setCellValue('U'.$dr_total,'=IF(O'.$dr_total.'>0,Q'.$dr_total.'/O'.$dr_total.'*100,Q'.$dr_total.')');
    $sheet->setCellValue('V'.$dr_total,'=AVERAGE(T'.$dr_total.':U'.$dr_total.')');
    // $sheet->setCellValue('T'.($dr_total+2),'Banjarmasin, tgl-bln-tahun');
    // $sheet->setCellValue('T'.($dr_total+3),'Kepala SKPD,');
    // $sheet->setCellValue('T'.($dr_total+7),'Nama');
    // $sheet->setCellValue('T'.($dr_total+8),'Nip.');
    if ($jenis_lap <> null){
      $sheet->setCellValue('A6','=CONCATENATE("Nilai Capaian TPP BK RFK bulan ini adalah = ",ROUND(T'.$dr_total.',2)," %")');
    }

    //looping kedua data
    $dr = 12;
    foreach ($rfk as $data){
      $sheet->setCellValue('D'.$dr,'=C'.$dr.'/$C$'.($dr_total).'*100');
      $sheet->setCellValue('G'.$dr,'=F'.$dr.'*D'.$dr.'/100');
      $sheet->setCellValue('J'.$dr,'=I'.$dr.'*D'.$dr.'/100');
      $sheet->setCellValue('L'.$dr,'=J'.$dr.'-G'.$dr);
      $sheet->setCellValue('O'.$dr,'=N'.$dr.'*D'.$dr.'/100');
      $sheet->setCellValue('Q'.$dr,'=P'.$dr.'*D'.$dr.'/100');
      $sheet->setCellValue('S'.$dr,'=Q'.$dr.'-O'.$dr);
      $dr++;
    }
    $dr_akhir = $dr-1;
    $sheet->setCellValue('D'.$dr_total,'=SUM(D'.$dr_awal.':D'.$dr_akhir.')');
    $sheet->setCellValue('E'.$dr_total,'=SUM(E'.$dr_awal.':E'.$dr_akhir.')');
    $sheet->setCellValue('G'.$dr_total,'=SUM(G'.$dr_awal.':G'.$dr_akhir.')');
    $sheet->setCellValue('F'.$dr_total,'=G'.$dr_total);
    $sheet->setCellValue('H'.$dr_total,'=SUM(H'.$dr_awal.':H'.$dr_akhir.')');
    $sheet->setCellValue('J'.$dr_total,'=SUM(J'.$dr_awal.':J'.$dr_akhir.')');
    $sheet->setCellValue('I'.$dr_total,'=J'.$dr_total);
    $sheet->setCellValue('L'.$dr_total,'=SUM(L'.$dr_awal.':L'.$dr_akhir.')');
    $sheet->setCellValue('K'.$dr_total,'=L'.$dr_total);
    $sheet->setCellValue('M'.$dr_total,'=SUM(M'.$dr_awal.':M'.$dr_akhir.')');
    $sheet->setCellValue('O'.$dr_total,'=SUM(O'.$dr_awal.':O'.$dr_akhir.')');
    $sheet->setCellValue('N'.$dr_total,'=O'.$dr_total);
    $sheet->setCellValue('Q'.$dr_total,'=SUM(Q'.$dr_awal.':Q'.$dr_akhir.')');
    $sheet->setCellValue('P'.$dr_total,'=Q'.$dr_total);
    $sheet->setCellValue('S'.$dr_total,'=SUM(S'.$dr_awal.':S'.$dr_akhir.')');
    $sheet->setCellValue('R'.$dr_total,'=S'.$dr_total);

    //FROMATTING
    $sheet->getStyle('A'.($dr_awal-1).':W'.$dr)->applyFromArray($style_table_data);
    $sheet->getStyle('A'.$dr_total.':W'.$dr_total)->applyFromArray($style_table_total);
    foreach ($rupiah_col as $col) {
      $sheet->getStyle($col.$dr_awal.':'.$col.$dr_total)->getNumberFormat()->setFormatCode(('#,##0'));
    }
    foreach ($persen_col as $col) {
      $sheet->getStyle($col.$dr_awal.':'.$col.$dr_total)->getNumberFormat()->setFormatCode(('#,##0.00'));
    }

    //CONDITIONAL FORMATTING
    $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_GREATERTHANOREQUAL);
    $conditional1->addCondition('85');
    $conditional1->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);
    $conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $conditional1->getStyle()->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);
    $conditional1->getStyle()->getFill()->getEndColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);

    $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_GREATERTHANOREQUAL);
    $conditional2->addCondition('$v$'.$dr_total);
    $conditional2->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
    $conditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $conditional2->getStyle()->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
    $conditional2->getStyle()->getFill()->getEndColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);

    $conditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    $conditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    $conditional3->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
    $conditional3->addCondition('$v$'.$dr_total);
    $conditional3->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
    $conditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $conditional3->getStyle()->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
    $conditional3->getStyle()->getFill()->getEndColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

    $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('B2')->getConditionalStyles();
    $conditionalStyles[] = $conditional1;
    $conditionalStyles[] = $conditional2;
    $conditionalStyles[] = $conditional3;

    $sheet->getStyle('W'.$dr_awal.':W'.$dr_akhir.'')->setConditionalStyles($conditionalStyles);


    //page setting
    $sheet->getSheetView()->setZoomScale(80);
    $sheet->setAutoFilter('B'.($dr_awal-1).':W'.($dr_akhir));
    $sheet->freezePane('C'.$dr_awal);
    $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(8, 10);
    $sheet->getPageSetup()->setPrintArea('A1:W'.($dr_total+8));
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getPageSetup()->setFitToHeight(0);
    //output
    $sheet->setTitle($rfk_total['alias']);
    $filename = $rfk_total['id_skpd'].' Laporan RFK '.$rfk_total['alias'];
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

  }

  function laporan_rfk_pemko(){
    $bulan = $this->input->post('bulan_laporan',True);
    $periode = $this->input->post('periode',True);
    $ta = $this->input->post('tahun_anggaran',True);

    //validasi inputan
    if($periode =='' or $ta == ''){
      $this->session->set_flashdata('error','Mohon Pilih Tahun Anggaran dan Periode Pagunya');
      redirect(site_url('lkpk/Ren_real/rfk_pemko'));
    }


    $bulan_column = array('b01','b02','b03','b04','b05','b06','b07','b08','b09','b01','b11','b12');
    $nama_bulan = array('Januari','Pebruari','Maret','April','Mei','Juni','Juni','Agustus','September','Oktober','Nopember','Desember');
    $rfk = $this->Ren_real_model->get_laporan_pemko($periode,$ta,$bulan_column[$bulan]);
    $rfk_total = $this->Ren_real_model->get_total_laporan_pemko($periode,$ta,$bulan_column[$bulan]);
    $data = array(
      'controller' => 'LKPK',
      'uri1' => 'Laporan RFK Belanja Langsung',
      'main_view' => 'lkpk/kegiatan/laporan_rfk_pemko',
      'bulan' => $bulan,
      'nama_bulan' => $nama_bulan[$bulan],
      'ta' => $ta,
      'rfk_data' => $rfk,
      'rfk_total_data' => $rfk_total,
      'periode' => $periode,
    );
    $this->load->view('template_view', $data);
  }

  function cetak_laporan_rfk_pemko($bulan,$periode,$ta){
    $bulan_column = array('b01','b02','b03','b04','b05','b06','b07','b08','b09','b01','b11','b12');
    $nama_bulan = array('Januari','Pebruari','Maret','April','Mei','Juni','Juni','Agustus','September','Oktober','Nopember','Desember');
    $rfk = $this->Ren_real_model->get_laporan_pemko($periode,$ta,$bulan_column[$bulan]);
    $rfk_total = $this->Ren_real_model->get_total_laporan_pemko($periode,$ta,$bulan_column[$bulan]);

    $style_table_header = [
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        // 'wrapText' => true,
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
    $sheet->setCellValue('A1','LAPORAN REALISASI FISIK DAN KEUANGAN ');
    $sheet->mergeCells('A1:W1');
    $sheet->setCellValue('A2','PEMERINTAH KOTA BANJARMASIN');
    $sheet->mergeCells('A2:W2');
    $sheet->setCellValue('A3','TAHUN ANGGARAN '.$ta);
    $sheet->mergeCells('A3:W3');
    $sheet->setCellValue('A4','KONDISI '.strtoupper($nama_bulan[$bulan]).' '.$ta);
    $sheet->mergeCells('A4:W4');
    $sheet->setCellValue('A8','NO');
    $sheet->mergeCells('A8:A10');
    $sheet->setCellValue('B8','URAIAN KEGIATAN');
    $sheet->mergeCells('B8:B10');
    $sheet->setCellValue('C8','NILAI ANGGARAN');
    $sheet->mergeCells('C8:D8');
    $sheet->setCellValue('C9','Rp');
    $sheet->mergeCells('C9:C10');
    $sheet->setCellValue('D9','%');
    $sheet->mergeCells('D9:D10');
    $sheet->setCellValue('E8','KEUANGAN');
    $sheet->mergeCells('E8:M8');
    $sheet->setCellValue('E9','RENCANA');
    $sheet->mergeCells('E9:G9');
    $sheet->setCellValue('E10','Rp');
    $sheet->setCellValue('F10','%');
    $sheet->setCellValue('G10','%');
    $sheet->setCellValue('H9','REALISASI');
    $sheet->mergeCells('H9:J9');
    $sheet->setCellValue('H10','Rp');
    $sheet->setCellValue('I10','%');
    $sheet->setCellValue('J10','%');
    $sheet->setCellValue('K9','DEVIASI');
    $sheet->mergeCells('K9:L9');
    $sheet->setCellValue('K10','%');
    $sheet->setCellValue('L10','%');
    $sheet->setCellValue('M9','SISA ANGGARAN');
    $sheet->setCellValue('M10','Rp');
    $sheet->setCellValue('N8','FISIK');
    $sheet->mergeCells('N8:S8');
    $sheet->setCellValue('N9','REN');
    $sheet->mergeCells('N9:O9');
    $sheet->setCellValue('N10','%');
    $sheet->setCellValue('O10','%');
    $sheet->setCellValue('P9','REAL');
    $sheet->mergeCells('P9:Q9');
    $sheet->setCellValue('P10','%');
    $sheet->setCellValue('Q10','%');
    $sheet->setCellValue('R9','DEV');
    $sheet->mergeCells('R9:S9');
    $sheet->setCellValue('R10','%');
    $sheet->setCellValue('S10','%');
    $sheet->setCellValue('T8','CAPAIAN');
    $sheet->mergeCells('T8:W8');
    $sheet->setCellValue('T9','KEU');
    $sheet->setCellValue('T10','%');
    $sheet->setCellValue('U9','FISIK');
    $sheet->setCellValue('U10','%');
    $sheet->setCellValue('V9','RATA2');
    $sheet->setCellValue('V10','%');
    $sheet->setCellValue('W9','KAT');
    $sheet->mergeCells('W9:W10');

    // FORMATTING
    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(35);

    $rupiah_col = array('C','E','H','M');
    foreach ($rupiah_col as $col) {
      $sheet->getColumnDimension($col)->setWidth(18);
    }
    $persen_col = array('D','F','G','I','J','K','L','N','O','P','Q','R','S','T','U','V','W');
    foreach ($persen_col as $col) {
      $sheet->getColumnDimension($col)->setWidth(7);
    }
    $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A8:W10')->applyFromArray($style_table_header);

    $hide_col = array ('G','J','L','O','Q','S');
    foreach ($hide_col as $col) {
      $sheet->getColumnDimension($col)->setVisible(false);
    }
    // TABEL DATA
    $dr_awal = 12;
    $dr = 12;
    $i = 1;
    foreach ($rfk as $data){
      $sheet->setCellValue('A'.$dr,$i);
      $sheet->setCellValue('B'.$dr,$data['nama_skpd']);
      $sheet->setCellValue('C'.$dr,$data['nilai']);
      $sheet->setCellValue('E'.$dr,$data['ren_keu']);
      $sheet->setCellValue('F'.$dr,'=E'.$dr.'/C'.$dr.'*100');
      $sheet->setCellValue('H'.$dr,$data['real_keu']);
      $sheet->setCellValue('I'.$dr,'=H'.$dr.'/C'.$dr.'*100');
      $sheet->setCellValue('K'.$dr,'=I'.$dr.'-F'.$dr);
      $sheet->setCellValue('M'.$dr,'=C'.$dr.'-H'.$dr);
      $sheet->setCellValue('N'.$dr,$data['ren_fisik']);
      $sheet->setCellValue('P'.$dr,$data['real_fisik']);
      $sheet->setCellValue('R'.$dr,'=P'.$dr.'-N'.$dr);
      $sheet->setCellValue('T'.$dr,'=IF(F'.$dr.'>0,I'.$dr.'/F'.$dr.'*100,I'.$dr.')');
      $sheet->setCellValue('U'.$dr,'=IF(N'.$dr.'>0,P'.$dr.'/N'.$dr.'*100,P'.$dr.')');
      $sheet->setCellValue('V'.$dr,'=AVERAGE(T'.$dr.':U'.$dr.')');
      $sheet->setCellValue('W'.$dr,'=V'.$dr.'');
      $dr++;
      $i++;
    }
    $dr_akhir = $dr-1;
    $dr_total = $dr+1;
    $sheet->setCellValue('B'.$dr_total,'TOTAL');
    $sheet->setCellValue('c'.$dr_total,'=SUM(C'.$dr_awal.':C'.$dr_akhir.')');
    $sheet->setCellValue('T'.$dr_total,'=IF(G'.$dr_total.'>0,J'.$dr_total.'/G'.$dr_total.'*100,J'.$dr_total.')');
    $sheet->setCellValue('U'.$dr_total,'=IF(O'.$dr_total.'>0,Q'.$dr_total.'/O'.$dr_total.'*100,Q'.$dr_total.')');
    $sheet->setCellValue('V'.$dr_total,'=AVERAGE(T'.$dr_total.':U'.$dr_total.')');
    $sheet->setCellValue('T'.($dr_total+2),'Banjarmasin,                 2019');
    $sheet->setCellValue('T'.($dr_total+3),'Sekretaris Daerah,');
    $sheet->setCellValue('T'.($dr_total+7),'Nama');
    $sheet->setCellValue('T'.($dr_total+8),'Nip.');


    //looping kedua data
    $dr = 12;
    foreach ($rfk as $data){
      $sheet->setCellValue('D'.$dr,'=C'.$dr.'/$C$'.($dr_total).'*100');
      $sheet->setCellValue('G'.$dr,'=F'.$dr.'*D'.$dr.'/100');
      $sheet->setCellValue('J'.$dr,'=I'.$dr.'*D'.$dr.'/100');
      $sheet->setCellValue('L'.$dr,'=J'.$dr.'-G'.$dr);
      $sheet->setCellValue('O'.$dr,'=N'.$dr.'*D'.$dr.'/100');
      $sheet->setCellValue('Q'.$dr,'=P'.$dr.'*D'.$dr.'/100');
      $sheet->setCellValue('S'.$dr,'=Q'.$dr.'-O'.$dr);
      $dr++;
    }
    $dr_akhir = $dr-1;
    $sheet->setCellValue('D'.$dr_total,'=SUM(D'.$dr_awal.':D'.$dr_akhir.')');
    $sheet->setCellValue('E'.$dr_total,'=SUM(E'.$dr_awal.':E'.$dr_akhir.')');
    $sheet->setCellValue('G'.$dr_total,'=SUM(G'.$dr_awal.':G'.$dr_akhir.')');
    $sheet->setCellValue('F'.$dr_total,'=G'.$dr_total);
    $sheet->setCellValue('H'.$dr_total,'=SUM(H'.$dr_awal.':H'.$dr_akhir.')');
    $sheet->setCellValue('J'.$dr_total,'=SUM(J'.$dr_awal.':J'.$dr_akhir.')');
    $sheet->setCellValue('I'.$dr_total,'=J'.$dr_total);
    $sheet->setCellValue('L'.$dr_total,'=SUM(L'.$dr_awal.':L'.$dr_akhir.')');
    $sheet->setCellValue('K'.$dr_total,'=L'.$dr_total);
    $sheet->setCellValue('M'.$dr_total,'=SUM(M'.$dr_awal.':M'.$dr_akhir.')');
    $sheet->setCellValue('O'.$dr_total,'=SUM(O'.$dr_awal.':O'.$dr_akhir.')');
    $sheet->setCellValue('N'.$dr_total,'=O'.$dr_total);
    $sheet->setCellValue('Q'.$dr_total,'=SUM(Q'.$dr_awal.':Q'.$dr_akhir.')');
    $sheet->setCellValue('P'.$dr_total,'=Q'.$dr_total);
    $sheet->setCellValue('S'.$dr_total,'=SUM(S'.$dr_awal.':S'.$dr_akhir.')');
    $sheet->setCellValue('R'.$dr_total,'=S'.$dr_total);

    //FROMATTING
    $sheet->getStyle('A'.($dr_awal-1).':W'.$dr)->applyFromArray($style_table_data);
    $sheet->getStyle('A'.$dr_total.':W'.$dr_total)->applyFromArray($style_table_total);
    foreach ($rupiah_col as $col) {
      $sheet->getStyle($col.$dr_awal.':'.$col.$dr_total)->getNumberFormat()->setFormatCode(('#,##0'));
    }
    foreach ($persen_col as $col) {
      $sheet->getStyle($col.$dr_awal.':'.$col.$dr_total)->getNumberFormat()->setFormatCode(('#,##0.00'));
    }

    //CONDITIONAL FORMATTING
    $conditional1 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    $conditional1->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    $conditional1->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_GREATERTHANOREQUAL);
    $conditional1->addCondition('85');
    $conditional1->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);
    $conditional1->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $conditional1->getStyle()->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);
    $conditional1->getStyle()->getFill()->getEndColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_GREEN);

    $conditional2 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    $conditional2->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    $conditional2->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_GREATERTHANOREQUAL);
    $conditional2->addCondition('$v$'.$dr_total);
    $conditional2->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
    $conditional2->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $conditional2->getStyle()->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);
    $conditional2->getStyle()->getFill()->getEndColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);

    $conditional3 = new \PhpOffice\PhpSpreadsheet\Style\Conditional();
    $conditional3->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS);
    $conditional3->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_LESSTHAN);
    $conditional3->addCondition('$v$'.$dr_total);
    $conditional3->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
    $conditional3->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $conditional3->getStyle()->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
    $conditional3->getStyle()->getFill()->getEndColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

    $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('B2')->getConditionalStyles();
    $conditionalStyles[] = $conditional1;
    $conditionalStyles[] = $conditional2;
    $conditionalStyles[] = $conditional3;

    $sheet->getStyle('W'.$dr_awal.':W'.$dr_akhir.'')->setConditionalStyles($conditionalStyles);


    //page setting
    $sheet->getSheetView()->setZoomScale(80);
    $sheet->setAutoFilter('B'.($dr_awal-1).':W'.($dr_akhir));
    $sheet->freezePane('C'.$dr_awal);
    $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(8, 10);
    $sheet->getPageSetup()->setPrintArea('A1:W'.($dr_total+8));
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getPageSetup()->setFitToHeight(0);
    //output
    $filename = 'Laporan RFK SKPD';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');

  }

  function perkegiatan($id_kegiatan,$periode_pagu){
    $ren_keu_data = $this->Ren_real_model->get_rencana_keu_by_periode_pagu($id_kegiatan,$periode_pagu);
    $real_keu_data = $this->Ren_real_model->get_realisasi_keu($id_kegiatan);

    $data = array(
      'controller' => 'Kegiatan',
      'uri1' => 'Rencana dan Realisasi',
      'main_view' => 'lkpk/kegiatan/detail_ren_real',
      'keg' => $id_kegiatan,
      'pp' => $periode_pagu,
    );
    $this->load->view('template_view', $data);
  }

  function cetak_tpp($bulan,$periode,$ta){
    $bulan_column = array('b01','b02','b03','b04','b05','b06','b07','b08','b09','b01','b11','b12');
    $nama_bulan = array('Januari','Pebruari','Maret','April','Mei','Juni','Juni','Agustus','September','Oktober','Nopember','Desember');
    $tpp = $this->Ren_real_model->get_tpp_skpd($periode,$ta,$bulan_column[$bulan]);
    $tpp_all = $tpp['all'];
    $tpp_ni = $tpp['non_insidentil'];
    $tpp_i = $tpp['insidentil'];

    $style_table_header = [
      'font' => [
        'bold' => true,
      ],
      'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        // 'wrapText' => true,
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

    //WRITE DATA
    $sheet->setCellValue('A1','LAPORAN PERHITUNGAN TPPBK RFK ');
    $sheet->mergeCells('A1:J1');
    $sheet->setCellValue('A2','PEMERINTAH KOTA BANJARMASIN');
    $sheet->mergeCells('A2:J2');
    $sheet->setCellValue('A3','TAHUN ANGGARAN '.$ta);
    $sheet->mergeCells('A3:J3');
    $sheet->setCellValue('A4','KONDISI '.strtoupper($nama_bulan[$bulan]).' '.$ta);
    $sheet->mergeCells('A4:J4');
    $sheet->setCellValue('A7','NO');
    $sheet->mergeCells('A7:A9');
    $sheet->setCellValue('B7','SKPD');
    $sheet->mergeCells('B7:B9');
    $sheet->setCellValue('C7','SEMUA KEGIATAN');
    $sheet->mergeCells('C7:E7');
    $sheet->setCellValue('C8','RENCANA');
    $sheet->setCellValue('C9','Rp');
    $sheet->setCellValue('D8','REALISASI');
    $sheet->setCellValue('D9','Rp');
    $sheet->setCellValue('E8','CAPAIAN');
    $sheet->setCellValue('E9','%');
    $sheet->setCellValue('F7','KEGIATAN INSIDENTIL');
    $sheet->mergeCells('F7:G7');
    $sheet->setCellValue('F8','RENCANA');
    $sheet->setCellValue('F9','Rp');
    $sheet->setCellValue('G8','REALISASI');
    $sheet->setCellValue('G9','Rp');
    $sheet->setCellValue('H7','NON INSIDENTIL');
    $sheet->mergeCells('H7:J7');
    $sheet->setCellValue('H8','RENCANA');
    $sheet->setCellValue('H9','Rp');
    $sheet->setCellValue('I8','REALISASI');
    $sheet->setCellValue('I9','Rp');
    $sheet->setCellValue('J8','CAPAIAN');
    $sheet->setCellValue('J9','%');

    //FORMATTING
    $sheet->getColumnDimension('A')->setWidth(4);
    $sheet->getColumnDimension('B')->setWidth(35);

    $rupiah_col = array('C','D','F','G', 'H', 'I');
    foreach ($rupiah_col as $col) {
      $sheet->getColumnDimension($col)->setWidth(18);
    }
    $persen_col = array('E','J');
    foreach ($persen_col as $col) {
      $sheet->getColumnDimension($col)->setWidth(10);
    }
    $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A7:J9')->applyFromArray($style_table_header);

    // TABEL DATA
    $dr_awal = 11;
    $dr = 11;
    $i = 1;
    foreach ($tpp_all as $tpa){
      $sheet->setCellValue('A'.$dr,$i);
      $sheet->setCellValue('B'.$dr,$tpa['nama_skpd']);
      $sheet->setCellValue('C'.$dr,$tpa['ren_keu']);
      $sheet->setCellValue('D'.$dr,$tpa['real_keu']);
      $sheet->setCellValue('E'.$dr,'=IF(C'.$dr.'>0,D'.$dr.'/C'.$dr.'*100,D'.$dr.')');
      $dr++;
      $i++;
    }

    $dr = 11;
    foreach ($tpp_i as $tpi){
      $sheet->setCellValue('F'.$dr,$tpi['ren_keu']);
      $sheet->setCellValue('G'.$dr,$tpi['real_keu']);
      $dr++;
    }

    $dr = 11;
    foreach ($tpp_ni as $tpni){
      $sheet->setCellValue('H'.$dr,$tpni['ren_keu']);
      $sheet->setCellValue('I'.$dr,$tpni['real_keu']);
      $sheet->setCellValue('J'.$dr,'=IF(H'.$dr.'>0,I'.$dr.'/H'.$dr.'*100,I'.$dr.')');
      $dr++;
    }
    $dr_akhir = $dr-1;
    $dr_total = $dr+1;
    $sheet->setCellValue('B'.$dr_total,'TOTAL');
    $sheet->setCellValue('C'.$dr_total,'=SUM(C'.$dr_awal.':C'.$dr_akhir.')');
    $sheet->setCellValue('D'.$dr_total,'=SUM(D'.$dr_awal.':D'.$dr_akhir.')');
    $sheet->setCellValue('E'.$dr_total,'=IF(C'.$dr_total.'>0,D'.$dr_total.'/C'.$dr_total.'*100,D'.$dr_total.')');
    $sheet->setCellValue('F'.$dr_total,'=SUM(F'.$dr_awal.':F'.$dr_akhir.')');
    $sheet->setCellValue('G'.$dr_total,'=SUM(G'.$dr_awal.':G'.$dr_akhir.')');
    $sheet->setCellValue('H'.$dr_total,'=SUM(H'.$dr_awal.':H'.$dr_akhir.')');
    $sheet->setCellValue('I'.$dr_total,'=SUM(I'.$dr_awal.':I'.$dr_akhir.')');
    $sheet->setCellValue('J'.$dr_total,'=IF(H'.$dr_total.'>0,I'.$dr_total.'/H'.$dr_total.'*100,I'.$dr_total.')');

    $sheet->setCellValue('H'.($dr_total+2),'Banjarmasin,               ');
    $sheet->setCellValue('H'.($dr_total+3),'Sekretaris Daerah,');
    $sheet->setCellValue('H'.($dr_total+7),'Nama');
    $sheet->setCellValue('H'.($dr_total+8),'Nip.');

    //FROMATTING
    $sheet->getStyle('A'.($dr_awal-1).':J'.$dr)->applyFromArray($style_table_data);
    $sheet->getStyle('A'.$dr_total.':J'.$dr_total)->applyFromArray($style_table_total);
    foreach ($rupiah_col as $col) {
      $sheet->getStyle($col.$dr_awal.':'.$col.$dr_total)->getNumberFormat()->setFormatCode(('#,##0'));
    }
    foreach ($persen_col as $col) {
      $sheet->getStyle($col.$dr_awal.':'.$col.$dr_total)->getNumberFormat()->setFormatCode(('#,##0.00'));
    }

    //page setting
    $sheet->setAutoFilter('B'.($dr_awal-1).':J'.($dr_akhir));
    $sheet->freezePane('C'.$dr_awal);
    $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(7, 9);
    $sheet->getPageSetup()->setPrintArea('A1:J'.($dr_total+8));
    $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $sheet->getPageSetup()->setFitToWidth(1);
    $sheet->getPageSetup()->setFitToHeight(0);
    //output
    $filename = 'Laporan Perhitungan TPP SKPD';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
  }
}
