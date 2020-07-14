<?php
set_time_limit(0);
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../connect/conn.php");

$cmbR = isset($_REQUEST['cmbR']) ? strval($_REQUEST['cmbR']) : '';
$rdJn = isset($_REQUEST['rdJn']) ? strval($_REQUEST['rdJn']) : '';
$dt_A = isset($_REQUEST['dt_A']) ? strval($_REQUEST['dt_A']) : '';
$dt_Z = isset($_REQUEST['dt_Z']) ? strval($_REQUEST['dt_Z']) : '';
$ck_s = isset($_REQUEST['ck_s']) ? strval($_REQUEST['ck_s']) : '';

if ($ck_s != "true"){
  $sample = "nvl(customer,'xx')  <> '14. ITEM SAMPLE' and ";
}

if ($rdJn == 'check_eta'){
  $dt = "to_char(etd,'YYYY-MM-DD') between '$dt_A' and '$dt_Z' and ";
}elseif($rdJn == 'check_bl'){
  $dt = "to_char(bl_date,'YYYY-MM-DD') between '$dt_A' and '$dt_Z' and ";
}elseif ($rdJn == 'check_xfact'){
  $dt = "to_char(ex_fact,'YYYY-MM-DD') between '$dt_A' and '$dt_Z' and ";
}

if ($cmbR == 1){
  $where =" where $dt $sample qty is not null ";
  $sql = "select type_batery, sum(qty) as Quantity, sum(qty*u_price) as amount 
          from zvw_sales_report $where
          group by type_batery";
}elseif($cmbR == 2){
  $where =" where $dt $sample qty is not null ";  
  $sql = "select customer, sum(qty) as Quantity, sum(qty*u_price) as amount
          from zvw_sales_report $where
          group by customer";
}elseif($cmbR == 3){
  $where =" where $dt $sample qty is not null ";
  $sql = "  select * from Zvw_SALES_REPORT $where";
}

$result = oci_parse($connect, $sql);
oci_execute($result);

function cellColor($cells,$color){
    global $objPHPExcel;
    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));
}

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();
 
// Add Data in your file
if ($cmbR == 1){
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A1', 'Sales Report Summary by Battery Type');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A2', 'NO')
              ->setCellValue('B2', 'BATERRY TYPE')
              ->setCellValue('C2', 'QTY')
              ->setCellValue('D2', 'AMOUNT');

  foreach(range('A','D') as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A2:D2')->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => 'B4B4B4')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          ),
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
      )
  );

  cellColor('A2:D2', 'D2D2D2');
  $sheet->getStyle('A2:D2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true)->setSize(12);
}elseif($cmbR == 2){
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A1', 'Sales Report Summary by Customer');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:D1');

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A2', 'NO')
              ->setCellValue('B2', 'CUSTOMER')
              ->setCellValue('C2', 'QTY')
              ->setCellValue('D2', 'AMOUNT');

  foreach(range('A','D') as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A2:D2')->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => 'B4B4B4')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          ),
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
      )
  );

  cellColor('A2:D2', 'D2D2D2');
  $sheet->getStyle('A2:D2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true)->setSize(12);
}elseif($cmbR == 3){
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A1', 'Sales Report Details ');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:T1');

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A2', 'NO')
              ->setCellValue('B2', 'CUSTOMER')
              ->setCellValue('C2', 'PO NO.')
              ->setCellValue('D2', 'SO NO.')
              ->setCellValue('E2', 'DO NO.')
              ->setCellValue('F2', 'ITEM NO')
              ->setCellValue('G2', 'ITEM DESC')
              ->setCellValue('H2', 'BATERY TYPE')
              ->setCellValue('I2', 'QTY')
              ->setCellValue('J2', 'UoM')
              ->setCellValue('K2', 'CURR')
              ->setCellValue('L2', 'PRICE')
              ->setCellValue('M2', 'AMOUNT')
              ->setCellValue('N2', 'STANDARD PRICE')
              ->setCellValue('O2', 'DESTINATION')
              ->setCellValue('P2', 'TRADE TERM')
              ->setCellValue('Q2', 'PORT LOADING')
              ->setCellValue('R2', 'ETD')
              ->setCellValue('S2', 'ETA')
              ->setCellValue('T2', 'BL_DATE')
              ->setCellValue('U2', 'EXFACTORY DATE');

  foreach(range('A','T') as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A2:T2')->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => 'B4B4B4')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          ),
          'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
      )
  );

  cellColor('A2:T2', 'D2D2D2');
  $sheet->getStyle('A2:T2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A2:T2')->getFont()->setBold(true)->setSize(12);
}

$noUrut = 1;    
$no=3;
$TOT_Q = 0;   $TOT_A = 0 ;

while ($data=oci_fetch_object($result)){
  if ($cmbR == 1){
    $TOT_Q += $data->QUANTITY;
    $TOT_A += $data->AMOUNT;
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data->TYPE_BATERY)
                ->setCellValue('C'.$no, $data->QUANTITY)
                ->setCellValue('D'.$no, $data->AMOUNT);
    $sheet = $objPHPExcel->getActiveSheet();

    $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
        array(
            'borders' => array(
               'allborders' => array(
                   'style' => PHPExcel_Style_Border::BORDER_THIN
               )
            ),
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':D'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  }elseif($cmbR == 2){
    $TOT_Q += $data->QUANTITY;
    $TOT_A += $data->AMOUNT;
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data->CUSTOMER)
                ->setCellValue('C'.$no, $data->QUANTITY)
                ->setCellValue('D'.$no, $data->AMOUNT);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
        array(
            'borders' => array(
               'allborders' => array(
                   'style' => PHPExcel_Style_Border::BORDER_THIN
               )
            ),
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':D'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  }elseif($cmbR == 3){
    $ITNO = $data->ITEM_NO;
    $ITNAME = $data->ITEM;
    $ITDESC = $data->DESCRIPTION;

    $ITEM = $ITNO.' - '.$ITNAME.'<br/>'.$ITDESC;

    $TOT_Q += $data->QTY;
    $TOT_A += $data->AMOUNT;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data->CUSTOMER)
                ->setCellValue('C'.$no, $data->CUSTOMER_PO_NO)
                ->setCellValue('D'.$no, $data->SO_NO)
                ->setCellValue('E'.$no, $data->DO_NO)
                ->setCellValue('F'.$no, $data->ITEM_NO)
                ->setCellValue('G'.$no, $data->DESCRIPTION)
                ->setCellValue('H'.$no, $data->TYPE_BATERY)
                ->setCellValue('I'.$no, $data->QTY)
                ->setCellValue('J'.$no, $data->UNIT_PL)
                ->setCellValue('K'.$no, $data->CURR_MARK)
                ->setCellValue('L'.$no, $data->U_PRICE)
                ->setCellValue('M'.$no, $data->AMOUNT)
                ->setCellValue('N'.$no, $data->STANDARD_PRICE)
                ->setCellValue('O'.$no, $data->FINAL_DESTINATION)
                ->setCellValue('P'.$no, $data->TRADE_TERM)
                ->setCellValue('Q'.$no, $data->PORT_LOADING)
                ->setCellValue('R'.$no, $data->ETD)
                ->setCellValue('S'.$no, $data->ETA)
                ->setCellValue('T'.$no, $data->BL_DATE)
                ->setCellValue('U'.$no, $data->EX_FACT);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A'.$no.':U'.$no)->applyFromArray(
        array(
            'borders' => array(
               'allborders' => array(
                   'style' => PHPExcel_Style_Border::BORDER_THIN
               )
            ),
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );
    $objPHPExcel->getActiveSheet()->getStyle('I'.$no.':N'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  }

  $no++;
  $noUrut++;
}

if ($cmbR == 1){
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$no, 'TOTAL')
              ->setCellValue('C'.$no, $TOT_Q)
              ->setCellValue('D'.$no, $TOT_A);
  $sheet = $objPHPExcel->getActiveSheet();
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no.':B'.$no);
  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => 'B4B4B4')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          ),
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
      )
  );

  cellColor('A'.$no.':D'.$no, 'D2D2D2');
  $sheet->getStyle('A'.$no.':D'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A'.$no.':D'.$no)->getFont()->setBold(true)->setSize(12);
  $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':D'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
}elseif($cmbR == 2){
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$no, 'TOTAL')
              ->setCellValue('C'.$no, $TOT_Q)
              ->setCellValue('D'.$no, $TOT_A);
  $sheet = $objPHPExcel->getActiveSheet();
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no.':B'.$no);
  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A'.$no.':D'.$no)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => 'B4B4B4')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          ),
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
      )
  );

  cellColor('A'.$no.':D'.$no, 'D2D2D2');
  $sheet->getStyle('A'.$no.':D'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A'.$no.':D'.$no)->getFont()->setBold(true)->setSize(12);
  $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':D'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
}elseif($cmbR == 3){

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$no, 'TOTAL')
              ->setCellValue('H'.$no, $TOT_Q)
              ->setCellValue('I'.$no, '')
              ->setCellValue('J'.$no, '')
              ->setCellValue('K'.$no, '')
              ->setCellValue('L'.$no, $TOT_A)
              ->setCellValue('M'.$no, '')
              ->setCellValue('N'.$no, '')
              ->setCellValue('O'.$no, '')
              ->setCellValue('P'.$no, '')
              ->setCellValue('Q'.$no, '')
              ->setCellValue('R'.$no, '')
              ->setCellValue('S'.$no, '')
              ->setCellValue('T'.$no, '');
  $sheet = $objPHPExcel->getActiveSheet();
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no.':H'.$no);
  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A'.$no.':T'.$no)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => 'B4B4B4')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          ),
          'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
      )
  );

  cellColor('A'.$no.':T'.$no, 'D2D2D2');
  $sheet->getStyle('A'.$no.':T'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A'.$no.':T'.$no)->getFont()->setBold(true)->setSize(12);
  $objPHPExcel->getActiveSheet()->getStyle('I'.$no.':M'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
}

$objPHPExcel->getDefaultStyle()
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('SALES REPORT - '.date('Y-m-d'));
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);
 // Menambahkan file gambar pada document excel pada kolom B2
/*$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('FDK');
$objDrawing->setDescription('FDK');
$objDrawing->setPath('../images/fdk8.png');
$objDrawing->setWidth('100px');
$objDrawing->setCoordinates('B2');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="SALES_'.date('Y/m/d').'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>