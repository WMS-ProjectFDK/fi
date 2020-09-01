<?php
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");
// include("../connect/conn_spareparts.php");

$cmbR = isset($_REQUEST['cmbR']) ? strval($_REQUEST['cmbR']) : '';
$dt_A = isset($_REQUEST['dt_A']) ? strval($_REQUEST['dt_A']) : '';
$dt_Z = isset($_REQUEST['dt_Z']) ? strval($_REQUEST['dt_Z']) : '';

if ($cmbR == 1){
  $sql = "select supplier_code, Nama, Stock_Subject,
    case when curr = 'US' then amount_original else 0 end US,
    case when curr = 'JP' then amount_original else 0 end JP,
    case when curr = 'RP' then amount_original else 0 end RP,
    case when curr = 'SG' then amount_original else 0 end SGD,
    case when curr = 'US' then amount_in_us else 0 end US_in_US,
    case when curr = 'JP' then amount_in_us else 0 end JP_in_US,
    case when curr = 'RP' then amount_in_us else 0 end RP_in_US,
    case when curr = 'SG' then amount_in_us else 0 end SGD_in_US,
      pdays,pdesc
    from 
    (select h.supplier_code, company.company Nama, case when trim(ss.stock_subject) = 'Wooden Pallet' then 'Packing Materials' else trim(ss.stock_subject) end stock_subject,
      c.curr_acc_mark curr, sum(d.amt_o) amount_original, sum(d.amt_l) amount_in_us,
        company.pdays, company.pdesc
      from gr_header h
      inner join gr_details d on h.gr_no = d.gr_no
      inner join item i on d.item_no = i.item_no
      inner join stock_subject ss on i.stock_subject_code = ss.stock_subject_code
      inner join currency c on h.curr_code = c.curr_code
      left outer join company on h.supplier_code = company.company_code
      where gr_date between to_date('$dt_A','YYYY-MM-DD') AND to_date('$dt_Z','YYYY-MM-DD')
      group by  company.company,h.supplier_code,c.curr_acc_mark, 
      case when trim(ss.stock_subject) = 'Wooden Pallet' then 'Packing Materials' else trim(ss.stock_subject) end,
        company.pdays, company.pdesc
    ) 
    order by  Stock_Subject,supplier_code ";

  $sql_parts = "select supplier_code, Nama, Stock_Subject,
    case when curr = 'US' then amount_original else 0 end US,
    case when curr = 'JP' then amount_original else 0 end JP,
    case when curr = 'RP' then amount_original else 0 end RP,
    case when curr = 'SGD' then amount_original else 0 end SGD,
    case when curr = 'US' then amount_in_us else 0 end US_in_US,
    case when curr = 'JP' then amount_in_us else 0 end JP_in_US,
    case when curr = 'RP' then amount_in_us else 0 end RP_in_US,
    case when curr = 'SGD' then amount_in_us else 0 end SGD_in_US,
      pdays,pdesc
    from 
    (select h.supplier_code, company.company Nama, case when trim(ss.stock_subject) = 'Wooden Pallet' then 'Packing Materials' else trim(ss.stock_subject) end stock_subject,
      c.curr_acc_mark curr, sum(d.amt_o) amount_original, sum(d.amt_l) amount_in_us,
        company.pdays, company.pdesc
      from gr_header h
      inner join gr_details d on h.gr_no = d.gr_no
      inner join item i on d.item_no = i.item_no
      inner join stock_subject ss on i.stock_subject_code = ss.stock_subject_code
      inner join currency c on h.curr_code = c.curr_code
      left outer join company on h.supplier_code = company.company_code
      where gr_date between to_date('$dt_A','YYYY-MM-DD') AND to_date('$dt_Z','YYYY-MM-DD')
      group by  company.company,h.supplier_code,c.curr_acc_mark, 
      case when trim(ss.stock_subject) = 'Wooden Pallet' then 'Packing Materials' else trim(ss.stock_subject) end,
        company.pdays, company.pdesc
    ) 
    order by  Stock_Subject,supplier_code ";
}elseif($cmbR == 2){
  $sql = "select supplier_code, Nama, Stock_Subject,
    SUM(amount_US) TOTAL_US, SUM(amount_JP) TOTAL_JP, SUM(amount_RP) TOTAL_RP, SUM(amount_SG) TOTAL_SG,
    SUM(Jan_US) Jan_US, SUM(Jan_JP) Jan_JP, SUM(Jan_RP) Jan_RP, SUM(Jan_SG) Jan_SG, 
    SUM(Feb_US) Feb_US, SUM(Feb_JP) Feb_JP, SUM(Feb_RP) Feb_RP, SUM(Feb_SG) Feb_SG, 
    SUM(Mar_US) Mar_US, SUM(Mar_JP) Mar_JP, SUM(Mar_RP) Mar_RP, SUM(Mar_SG) Mar_SG, 
    SUM(Apr_US) Apr_US, SUM(Apr_JP) Apr_JP, SUM(Apr_RP) Apr_RP, SUM(Apr_SG) Apr_SG, 
    SUM(May_US) May_US, SUM(May_JP) May_JP, SUM(May_RP) May_RP, SUM(May_SG) May_SG, 
    SUM(Jun_US) Jun_US, SUM(Jun_JP) Jun_JP, SUM(Jun_RP) Jun_RP, SUM(Jun_SG) Jun_SG, 
    SUM(Jul_US) Jul_US, SUM(Jul_JP) Jul_JP, SUM(Jul_RP) Jul_RP, SUM(Jul_SG) Jul_SG, 
    SUM(Aug_US) Aug_US, SUM(Aug_JP) Aug_JP, SUM(Aug_RP) Aug_RP, SUM(Aug_SG) Aug_SG, 
    SUM(Sep_US) Sep_US, SUM(Sep_JP) Sep_JP, SUM(Sep_RP) Sep_RP, SUM(Sep_SG) Sep_SG, 
    SUM(Oct_US) Oct_US, SUM(Oct_JP) Oct_JP, SUM(Oct_RP) Oct_RP, SUM(Oct_SG) Oct_SG, 
    SUM(Nov_US) Nov_US, SUM(Nov_JP) Nov_JP, SUM(Nov_RP) Nov_RP, SUM(Nov_SG) Nov_SG, 
    SUM(Dec_US) Dec_US, SUM(Dec_JP) Dec_JP, SUM(Dec_RP) Dec_RP, SUM(Dec_SG) Dec_SG,
    pdays,pdesc 
    FROM (select supplier_code, Nama, Stock_Subject,
          case when curr = 'US' then amount_in_us else 0 end amount_US,
          case when curr = 'JP' then amount_in_us else 0 end amount_JP,
          case when curr = 'RP' then amount_in_us else 0 end amount_RP,
          case when curr = 'SG' then amount_in_us else 0 end amount_SG,
          case when bulan = '01' AND curr = 'US' then amount_in_us else 0 end Jan_US,
          case when bulan = '01' AND curr = 'JP' then amount_in_us else 0 end Jan_JP,
          case when bulan = '01' AND curr = 'RP' then amount_in_us else 0 end Jan_RP,
          case when bulan = '01' AND curr = 'SG' then amount_in_us else 0 end Jan_SG,
          case when bulan = '02' AND curr = 'US' then amount_in_us else 0 end Feb_US,
          case when bulan = '02' AND curr = 'JP' then amount_in_us else 0 end Feb_JP,
          case when bulan = '02' AND curr = 'RP' then amount_in_us else 0 end Feb_RP,
          case when bulan = '02' AND curr = 'SG' then amount_in_us else 0 end Feb_SG,
          case when bulan = '03' AND curr = 'US' then amount_in_us else 0 end Mar_US,
          case when bulan = '03' AND curr = 'JP' then amount_in_us else 0 end Mar_JP,
          case when bulan = '03' AND curr = 'RP' then amount_in_us else 0 end Mar_RP,
          case when bulan = '03' AND curr = 'SG' then amount_in_us else 0 end Mar_SG,
          case when bulan = '04' AND curr = 'US' then amount_in_us else 0 end Apr_US,
          case when bulan = '04' AND curr = 'JP' then amount_in_us else 0 end Apr_JP,
          case when bulan = '04' AND curr = 'RP' then amount_in_us else 0 end Apr_RP,
          case when bulan = '04' AND curr = 'SG' then amount_in_us else 0 end Apr_SG,
          case when bulan = '05' AND curr = 'US' then amount_in_us else 0 end May_US,
          case when bulan = '05' AND curr = 'JP' then amount_in_us else 0 end May_JP,
          case when bulan = '05' AND curr = 'RP' then amount_in_us else 0 end May_RP,
          case when bulan = '05' AND curr = 'SG' then amount_in_us else 0 end May_SG,
          case when bulan = '06' AND curr = 'US' then amount_in_us else 0 end Jun_US,
          case when bulan = '06' AND curr = 'JP' then amount_in_us else 0 end Jun_JP,
          case when bulan = '06' AND curr = 'RP' then amount_in_us else 0 end Jun_RP,
          case when bulan = '06' AND curr = 'SG' then amount_in_us else 0 end Jun_SG,
          case when bulan = '07' AND curr = 'US' then amount_in_us else 0 end Jul_US,
          case when bulan = '07' AND curr = 'JP' then amount_in_us else 0 end Jul_JP,
          case when bulan = '07' AND curr = 'RP' then amount_in_us else 0 end Jul_RP,
          case when bulan = '07' AND curr = 'SG' then amount_in_us else 0 end Jul_SG,
          case when bulan = '08' AND curr = 'US' then amount_in_us else 0 end Aug_US,
          case when bulan = '08' AND curr = 'JP' then amount_in_us else 0 end Aug_JP,
          case when bulan = '08' AND curr = 'RP' then amount_in_us else 0 end Aug_RP,
          case when bulan = '08' AND curr = 'SG' then amount_in_us else 0 end Aug_SG,
          case when bulan = '09' AND curr = 'US' then amount_in_us else 0 end Sep_US,
          case when bulan = '09' AND curr = 'JP' then amount_in_us else 0 end Sep_JP,
          case when bulan = '09' AND curr = 'RP' then amount_in_us else 0 end Sep_RP,
          case when bulan = '09' AND curr = 'SG' then amount_in_us else 0 end Sep_SG,
          case when bulan = '10' AND curr = 'US' then amount_in_us else 0 end Oct_US,
          case when bulan = '10' AND curr = 'JP' then amount_in_us else 0 end Oct_JP,
          case when bulan = '10' AND curr = 'RP' then amount_in_us else 0 end Oct_RP,
          case when bulan = '10' AND curr = 'SG' then amount_in_us else 0 end Oct_SG,
          case when bulan = '11' AND curr = 'US' then amount_in_us else 0 end Nov_US,
          case when bulan = '11' AND curr = 'JP' then amount_in_us else 0 end Nov_JP,
          case when bulan = '11' AND curr = 'RP' then amount_in_us else 0 end Nov_RP,
          case when bulan = '11' AND curr = 'SG' then amount_in_us else 0 end Nov_SG,
          case when bulan = '12' AND curr = 'US' then amount_in_us else 0 end Dec_US,
          case when bulan = '12' AND curr = 'JP' then amount_in_us else 0 end Dec_JP,
          case when bulan = '12' AND curr = 'RP' then amount_in_us else 0 end Dec_RP,
          case when bulan = '12' AND curr = 'SG' then amount_in_us else 0 end Dec_SG,
          pdays,pdesc
          from 
          (select h.supplier_code, company.company Nama, to_char(gr_Date,'MM') bulan, 
            case when trim(ss.stock_subject) = 'Wooden Pallet' then 'Packing Materials' else trim(ss.stock_subject) end stock_subject,
            c.curr_acc_mark curr, sum(d.amt_O) amount_in_us, company.pdays, company.pdesc
            from gr_header h
            inner join gr_details d on h.gr_no = d.gr_no
            inner join item i on d.item_no = i.item_no
            inner join stock_subject ss on i.stock_subject_code = ss.stock_subject_code
            inner join currency c on h.curr_code = c.curr_code
            left outer join company on h.supplier_code = company.company_code 
            where gr_date between to_date('$dt_A','YYYY-MM-DD') AND to_date('$dt_Z','YYYY-MM-DD')
            group by  company.company,h.supplier_code,
            case when trim(ss.stock_subject) = 'Wooden Pallet' then 'Packing Materials' else trim(ss.stock_subject) end, curr_acc_mark,
            to_char(gr_Date,'MM'),company.pdays, company.pdesc
          )
    )
    group by supplier_code, Nama, Stock_Subject, pdays,pdesc
    order by  Stock_Subject,supplier_code";

  $sql_parts = "select supplier_code, Nama, Stock_Subject,
    SUM(amount_US) TOTAL_US, SUM(amount_JP) TOTAL_JP, SUM(amount_RP) TOTAL_RP, SUM(amount_SG) TOTAL_SG,
    SUM(Jan_US) Jan_US, SUM(Jan_JP) Jan_JP, SUM(Jan_RP) Jan_RP, SUM(Jan_SG) Jan_SG, 
    SUM(Feb_US) Feb_US, SUM(Feb_JP) Feb_JP, SUM(Feb_RP) Feb_RP, SUM(Feb_SG) Feb_SG, 
    SUM(Mar_US) Mar_US, SUM(Mar_JP) Mar_JP, SUM(Mar_RP) Mar_RP, SUM(Mar_SG) Mar_SG, 
    SUM(Apr_US) Apr_US, SUM(Apr_JP) Apr_JP, SUM(Apr_RP) Apr_RP, SUM(Apr_SG) Apr_SG, 
    SUM(May_US) May_US, SUM(May_JP) May_JP, SUM(May_RP) May_RP, SUM(May_SG) May_SG, 
    SUM(Jun_US) Jun_US, SUM(Jun_JP) Jun_JP, SUM(Jun_RP) Jun_RP, SUM(Jun_SG) Jun_SG, 
    SUM(Jul_US) Jul_US, SUM(Jul_JP) Jul_JP, SUM(Jul_RP) Jul_RP, SUM(Jul_SG) Jul_SG, 
    SUM(Aug_US) Aug_US, SUM(Aug_JP) Aug_JP, SUM(Aug_RP) Aug_RP, SUM(Aug_SG) Aug_SG, 
    SUM(Sep_US) Sep_US, SUM(Sep_JP) Sep_JP, SUM(Sep_RP) Sep_RP, SUM(Sep_SG) Sep_SG, 
    SUM(Oct_US) Oct_US, SUM(Oct_JP) Oct_JP, SUM(Oct_RP) Oct_RP, SUM(Oct_SG) Oct_SG, 
    SUM(Nov_US) Nov_US, SUM(Nov_JP) Nov_JP, SUM(Nov_RP) Nov_RP, SUM(Nov_SG) Nov_SG, 
    SUM(Dec_US) Dec_US, SUM(Dec_JP) Dec_JP, SUM(Dec_RP) Dec_RP, SUM(Dec_SG) Dec_SG,
    pdays,pdesc
    FROM (select supplier_code, Nama, Stock_Subject,
          case when curr = 'US' then amount_in_us else 0 end amount_US,
          case when curr = 'JP' then amount_in_us else 0 end amount_JP,
          case when curr = 'RP' then amount_in_us else 0 end amount_RP,
          case when curr = 'SG' then amount_in_us else 0 end amount_SG,
          case when bulan = '01' AND curr = 'US' then amount_in_us else 0 end Jan_US,
          case when bulan = '01' AND curr = 'JP' then amount_in_us else 0 end Jan_JP,
          case when bulan = '01' AND curr = 'RP' then amount_in_us else 0 end Jan_RP,
          case when bulan = '01' AND curr = 'SGD' then amount_in_us else 0 end Jan_SG,
          case when bulan = '02' AND curr = 'US' then amount_in_us else 0 end Feb_US,
          case when bulan = '02' AND curr = 'JP' then amount_in_us else 0 end Feb_JP,
          case when bulan = '02' AND curr = 'RP' then amount_in_us else 0 end Feb_RP,
          case when bulan = '02' AND curr = 'SGD' then amount_in_us else 0 end Feb_SG,
          case when bulan = '03' AND curr = 'US' then amount_in_us else 0 end Mar_US,
          case when bulan = '03' AND curr = 'JP' then amount_in_us else 0 end Mar_JP,
          case when bulan = '03' AND curr = 'RP' then amount_in_us else 0 end Mar_RP,
          case when bulan = '03' AND curr = 'SGD' then amount_in_us else 0 end Mar_SG,
          case when bulan = '04' AND curr = 'US' then amount_in_us else 0 end Apr_US,
          case when bulan = '04' AND curr = 'JP' then amount_in_us else 0 end Apr_JP,
          case when bulan = '04' AND curr = 'RP' then amount_in_us else 0 end Apr_RP,
          case when bulan = '04' AND curr = 'SGD' then amount_in_us else 0 end Apr_SG,
          case when bulan = '05' AND curr = 'US' then amount_in_us else 0 end May_US,
          case when bulan = '05' AND curr = 'JP' then amount_in_us else 0 end May_JP,
          case when bulan = '05' AND curr = 'RP' then amount_in_us else 0 end May_RP,
          case when bulan = '05' AND curr = 'SGD' then amount_in_us else 0 end May_SG,
          case when bulan = '06' AND curr = 'US' then amount_in_us else 0 end Jun_US,
          case when bulan = '06' AND curr = 'JP' then amount_in_us else 0 end Jun_JP,
          case when bulan = '06' AND curr = 'RP' then amount_in_us else 0 end Jun_RP,
          case when bulan = '06' AND curr = 'SGD' then amount_in_us else 0 end Jun_SG,
          case when bulan = '07' AND curr = 'US' then amount_in_us else 0 end Jul_US,
          case when bulan = '07' AND curr = 'JP' then amount_in_us else 0 end Jul_JP,
          case when bulan = '07' AND curr = 'RP' then amount_in_us else 0 end Jul_RP,
          case when bulan = '07' AND curr = 'SGD' then amount_in_us else 0 end Jul_SG,
          case when bulan = '08' AND curr = 'US' then amount_in_us else 0 end Aug_US,
          case when bulan = '08' AND curr = 'JP' then amount_in_us else 0 end Aug_JP,
          case when bulan = '08' AND curr = 'RP' then amount_in_us else 0 end Aug_RP,
          case when bulan = '08' AND curr = 'SGD' then amount_in_us else 0 end Aug_SG,
          case when bulan = '09' AND curr = 'US' then amount_in_us else 0 end Sep_US,
          case when bulan = '09' AND curr = 'JP' then amount_in_us else 0 end Sep_JP,
          case when bulan = '09' AND curr = 'RP' then amount_in_us else 0 end Sep_RP,
          case when bulan = '09' AND curr = 'SGD' then amount_in_us else 0 end Sep_SG,
          case when bulan = '10' AND curr = 'US' then amount_in_us else 0 end Oct_US,
          case when bulan = '10' AND curr = 'JP' then amount_in_us else 0 end Oct_JP,
          case when bulan = '10' AND curr = 'RP' then amount_in_us else 0 end Oct_RP,
          case when bulan = '10' AND curr = 'SGD' then amount_in_us else 0 end Oct_SG,
          case when bulan = '11' AND curr = 'US' then amount_in_us else 0 end Nov_US,
          case when bulan = '11' AND curr = 'JP' then amount_in_us else 0 end Nov_JP,
          case when bulan = '11' AND curr = 'RP' then amount_in_us else 0 end Nov_RP,
          case when bulan = '11' AND curr = 'SGD' then amount_in_us else 0 end Nov_SG,
          case when bulan = '12' AND curr = 'US' then amount_in_us else 0 end Dec_US,
          case when bulan = '12' AND curr = 'JP' then amount_in_us else 0 end Dec_JP,
          case when bulan = '12' AND curr = 'RP' then amount_in_us else 0 end Dec_RP,
          case when bulan = '12' AND curr = 'SGD' then amount_in_us else 0 end Dec_SG,
          pdays,pdesc
          from 
          (select h.supplier_code, company.company Nama, to_char(gr_Date,'MM') bulan, 
            case when trim(ss.stock_subject) = 'Wooden Pallet' then 'Packing Materials' else trim(ss.stock_subject) end stock_subject,
            c.curr_acc_mark curr, sum(d.amt_O) amount_in_us, company.pdays, company.pdesc
            from gr_header h
            inner join gr_details d on h.gr_no = d.gr_no
            inner join item i on d.item_no = i.item_no
            inner join stock_subject ss on i.stock_subject_code = ss.stock_subject_code
            inner join currency c on h.curr_code = c.curr_code
            left outer join company on h.supplier_code = company.company_code 
            where gr_date between to_date('$dt_A','YYYY-MM-DD') AND to_date('$dt_Z','YYYY-MM-DD')
            group by  company.company,h.supplier_code,
            case when trim(ss.stock_subject) = 'Wooden Pallet' then 'Packing Materials' else trim(ss.stock_subject) end, curr_acc_mark,
            to_char(gr_Date,'MM'),company.pdays, company.pdesc
          )
    )
    group by supplier_code, Nama, Stock_Subject, pdays,pdesc
    order by  Stock_Subject,supplier_code";
}

$result = oci_parse($connect, $sql);
oci_execute($result);

$result_parts = oci_parse($connect_spareparts, $sql_parts);
oci_execute($result_parts);

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
              ->setCellValue('A1', 'PURCHASE PAYMENT REPORT by SUPPLIER');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A2', 'NO')
              ->setCellValue('B2', 'SUPPLIER NAME')
              ->setCellValue('C2', 'USD')
              ->setCellValue('D2', 'YEN')
              ->setCellValue('E2', 'SGD')
              ->setCellValue('F2', 'IDR')
              ->setCellValue('G2', 'PAYMENT TERMS');

  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A3');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:B3');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:C3');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D2:D3');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E2:E3');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F2:F3');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G2:G3');

  foreach(range('A','G') as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A1:G3')->applyFromArray(
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

  cellColor('A1:G3', 'D2D2D2');
  $sheet->getStyle('A1:G3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A1:G3')->getFont()->setBold(true)->setSize(12);

}elseif($cmbR == 2){
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A1', 'Summary Report Purchase Amount by monthly');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:BB1');

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A2', 'NO')
              ->setCellValue('B2', 'SUPPLIER NAME')
              ->setCellValue('C2', 'TOTAL')
              ->setCellValue('G2', 'JAN')
              ->setCellValue('K2', 'FEB')
              ->setCellValue('O2', 'MAR')
              ->setCellValue('S2', 'APR')
              ->setCellValue('W2', 'MAY')
              ->setCellValue('AA2', 'JUN')
              ->setCellValue('AE2', 'JUL')
              ->setCellValue('AI2', 'AUG')
              ->setCellValue('AM2', 'SEP')
              ->setCellValue('AQ2', 'OCT')
              ->setCellValue('AU2', 'NOV')
              ->setCellValue('AY2', 'DEC');

  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:A3');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B2:B3');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('G2:J2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('K2:N2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('O2:R2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('S2:V2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('W2:Z2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AA2:AD2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AE2:AH2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AI2:AL2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AM2:AP2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AQ2:AT2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AU2:AX2');
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('AY2:BB2');

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('C3', 'USD')
              ->setCellValue('D3', 'JPY')
              ->setCellValue('E3', 'IDR')
              ->setCellValue('F3', 'SGD')
              ->setCellValue('G3', 'USD')
              ->setCellValue('H3', 'JPY')
              ->setCellValue('I3', 'IDR')
              ->setCellValue('J3', 'SGD')
              ->setCellValue('K3', 'USD')
              ->setCellValue('L3', 'JPY')
              ->setCellValue('M3', 'IDR')
              ->setCellValue('N3', 'SGD')
              ->setCellValue('O3', 'USD')
              ->setCellValue('P3', 'JPY')
              ->setCellValue('Q3', 'IDR')
              ->setCellValue('R3', 'SGD')
              ->setCellValue('S3', 'USD')
              ->setCellValue('T3', 'JPY')
              ->setCellValue('U3', 'IDR')
              ->setCellValue('V3', 'SGD')
              ->setCellValue('W3', 'USD')
              ->setCellValue('X3', 'JPY')
              ->setCellValue('Y3', 'IDR')
              ->setCellValue('Z3', 'SGD')
              ->setCellValue('AA3', 'USD')
              ->setCellValue('AB3', 'JPY')
              ->setCellValue('AC3', 'IDR')
              ->setCellValue('AD3', 'SGD')
              ->setCellValue('AE3', 'USD')
              ->setCellValue('AF3', 'JPY')
              ->setCellValue('AG3', 'IDR')
              ->setCellValue('AH3', 'SGD')
              ->setCellValue('AI3', 'USD')
              ->setCellValue('AJ3', 'JPY')
              ->setCellValue('AK3', 'IDR')
              ->setCellValue('AL3', 'SGD')
              ->setCellValue('AM3', 'USD')
              ->setCellValue('AN3', 'JPY')
              ->setCellValue('AO3', 'IDR')
              ->setCellValue('AP3', 'SGD')
              ->setCellValue('AQ3', 'USD')
              ->setCellValue('AR3', 'JPY')
              ->setCellValue('AS3', 'IDR')
              ->setCellValue('AT3', 'SGD')
              ->setCellValue('AU3', 'USD')
              ->setCellValue('AV3', 'JPY')
              ->setCellValue('AW3', 'IDR')
              ->setCellValue('AX3', 'SGD')
              ->setCellValue('AY3', 'USD')
              ->setCellValue('AZ3', 'JPY')
              ->setCellValue('BA3', 'IDR')
              ->setCellValue('BB3', 'SGD');

  foreach(range('A','BB') as $columnID) {
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
  }

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A1:BB3')->applyFromArray(
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

  cellColor('A1:BB3', 'D2D2D2');
  $sheet->getStyle('A1:BB3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A1:BB3')->getFont()->setBold(true)->setSize(12);
}

$noUrut = 1;    
$no=4;    $subject='';
$no_jum = 0;
$JUM_USD_PERSUBJECT=0;      $JUM_USD=0;
$JUM_YEN_PERSUBJECT=0;      $JUM_YEN=0;
$JUM_SGD_PERSUBJECT=0;      $JUM_SGD=0;
$JUM_IDR_PERSUBJECT=0;      $JUM_IDR=0;

$JUM_TOT_US_PERSUBJECT=0;      $JUM_TOT_JP_PERSUBJECT=0;      $JUM_TOT_RP_PERSUBJECT=0;     $JUM_TOT_SG_PERSUBJECT=0;
$JUM_TOTAL_US=0;               $JUM_TOTAL_JP=0;               $JUM_TOTAL_RP=0;              $JUM_TOTAL_SG=0;

$JUM_JAN_US_PERSUBJECT=0;      $JUM_JAN_JP_PERSUBJECT=0;      $JUM_JAN_RP_PERSUBJECT=0;      $JUM_JAN_SG_PERSUBJECT=0;
$JUM_FEB_US_PERSUBJECT=0;      $JUM_FEB_JP_PERSUBJECT=0;      $JUM_FEB_RP_PERSUBJECT=0;      $JUM_FEB_SG_PERSUBJECT=0;
$JUM_MAR_US_PERSUBJECT=0;      $JUM_MAR_JP_PERSUBJECT=0;      $JUM_MAR_RP_PERSUBJECT=0;      $JUM_MAR_SG_PERSUBJECT=0;
$JUM_APR_US_PERSUBJECT=0;      $JUM_APR_JP_PERSUBJECT=0;      $JUM_APR_RP_PERSUBJECT=0;      $JUM_APR_SG_PERSUBJECT=0;
$JUM_MAY_US_PERSUBJECT=0;      $JUM_MAY_JP_PERSUBJECT=0;      $JUM_MAY_RP_PERSUBJECT=0;      $JUM_MAY_SG_PERSUBJECT=0;
$JUM_JUN_US_PERSUBJECT=0;      $JUM_JUN_JP_PERSUBJECT=0;      $JUM_JUN_RP_PERSUBJECT=0;      $JUM_JUN_SG_PERSUBJECT=0;
$JUM_JUL_US_PERSUBJECT=0;      $JUM_JUL_JP_PERSUBJECT=0;      $JUM_JUL_RP_PERSUBJECT=0;      $JUM_JUL_SG_PERSUBJECT=0;
$JUM_AUG_US_PERSUBJECT=0;      $JUM_AUG_JP_PERSUBJECT=0;      $JUM_AUG_RP_PERSUBJECT=0;      $JUM_AUG_SG_PERSUBJECT=0;
$JUM_SEP_US_PERSUBJECT=0;      $JUM_SEP_JP_PERSUBJECT=0;      $JUM_SEP_RP_PERSUBJECT=0;      $JUM_SEP_SG_PERSUBJECT=0;
$JUM_OCT_US_PERSUBJECT=0;      $JUM_OCT_JP_PERSUBJECT=0;      $JUM_OCT_RP_PERSUBJECT=0;      $JUM_OCT_SG_PERSUBJECT=0;
$JUM_NOV_US_PERSUBJECT=0;      $JUM_NOV_JP_PERSUBJECT=0;      $JUM_NOV_RP_PERSUBJECT=0;      $JUM_NOV_SG_PERSUBJECT=0;
$JUM_DEC_US_PERSUBJECT=0;      $JUM_DEC_JP_PERSUBJECT=0;      $JUM_DEC_RP_PERSUBJECT=0;      $JUM_DEC_SG_PERSUBJECT=0;

$JUM_JAN_US=0;     $JUM_JAN_JP=0;     $JUM_JAN_RP=0;     $JUM_JAN_SG=0;
$JUM_FEB_US=0;     $JUM_FEB_JP=0;     $JUM_FEB_RP=0;     $JUM_FEB_SG=0;
$JUM_MAR_US=0;     $JUM_MAR_JP=0;     $JUM_MAR_RP=0;     $JUM_MAR_SG=0;
$JUM_APR_US=0;     $JUM_APR_JP=0;     $JUM_APR_RP=0;     $JUM_APR_SG=0;
$JUM_MAY_US=0;     $JUM_MAY_JP=0;     $JUM_MAY_RP=0;     $JUM_MAY_SG=0;
$JUM_JUN_US=0;     $JUM_JUN_JP=0;     $JUM_JUN_RP=0;     $JUM_JUN_SG=0;
$JUM_JUL_US=0;     $JUM_JUL_JP=0;     $JUM_JUL_RP=0;     $JUM_JUL_SG=0;
$JUM_AUG_US=0;     $JUM_AUG_JP=0;     $JUM_AUG_RP=0;     $JUM_AUG_SG=0;
$JUM_SEP_US=0;     $JUM_SEP_JP=0;     $JUM_SEP_RP=0;     $JUM_SEP_SG=0;
$JUM_OCT_US=0;     $JUM_OCT_JP=0;     $JUM_OCT_RP=0;     $JUM_OCT_SG=0;
$JUM_NOV_US=0;     $JUM_NOV_JP=0;     $JUM_NOV_RP=0;     $JUM_NOV_SG=0;
$JUM_DEC_US=0;     $JUM_DEC_JP=0;     $JUM_DEC_RP=0;     $JUM_DEC_SG=0;

while ($data=oci_fetch_object($result)){
  if ($cmbR == 1){
    $subjectNya = $data->STOCK_SUBJECT;
    if($no==4){
      $no_jum = $no;    $no++;
      $JUM_USD_PERSUBJECT+= $data->US;
      $JUM_YEN_PERSUBJECT+= $data->JP;
      $JUM_SGD_PERSUBJECT+= $data->SGD;
      $JUM_IDR_PERSUBJECT+= $data->RP;
    }else{
      if($subject == $subjectNya){
        $JUM_USD_PERSUBJECT+= $data->US;
        $JUM_YEN_PERSUBJECT+= $data->JP;
        $JUM_SGD_PERSUBJECT+= $data->SGD;
        $JUM_IDR_PERSUBJECT+= $data->RP;
      }else{
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$no_jum, strtoupper($subject))
                    ->setCellValue('C'.$no_jum, $JUM_USD_PERSUBJECT)
                    ->setCellValue('D'.$no_jum, $JUM_YEN_PERSUBJECT)
                    ->setCellValue('E'.$no_jum, $JUM_SGD_PERSUBJECT)
                    ->setCellValue('F'.$no_jum, $JUM_IDR_PERSUBJECT);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no_jum.':B'.$no_jum);

        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('A'.$no_jum)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '00AAFF')
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

        $sheet->getStyle('A'.$no_jum.':G'.$no_jum)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '00AAFF')
                ),
                'borders' => array(
                   'allborders' => array(
                       'style' => PHPExcel_Style_Border::BORDER_THIN
                   )
                )
            )
        );

        cellColor('A'.$no_jum.':G'.$no_jum, '00AAFF');
        $sheet->getStyle('A'.$no_jum)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$no_jum.':G'.$no_jum)->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$no_jum.':F'.$no_jum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $JUM_USD += $JUM_USD_PERSUBJECT;
        $JUM_YEN += $JUM_YEN_PERSUBJECT;
        $JUM_SGD += $JUM_SGD_PERSUBJECT;
        $JUM_IDR += $JUM_IDR_PERSUBJECT;

        $no_jum=$no;    $no++;
        $JUM_USD_PERSUBJECT=0;
        $JUM_YEN_PERSUBJECT=0;
        $JUM_SGD_PERSUBJECT=0;
        $JUM_IDR_PERSUBJECT=0;

        $JUM_USD_PERSUBJECT+= $data->US;
        $JUM_YEN_PERSUBJECT+= $data->JP;
        $JUM_SGD_PERSUBJECT+= $data->SGD;
        $JUM_IDR_PERSUBJECT+= $data->RP;
      }
    }

    $subject = $subjectNya;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data->SUPPLIER_CODE.'-'.$data->NAMA)
                ->setCellValue('C'.$no, $data->US)
                ->setCellValue('D'.$no, $data->JP)
                ->setCellValue('E'.$no, $data->SGD)
                ->setCellValue('F'.$no, $data->RP)
                ->setCellValue('G'.$no, $data->PDAYS.' '.strtoupper($data->PDESC));
    $sheet = $objPHPExcel->getActiveSheet();

    $sheet->getStyle('A'.$no.':G'.$no)->applyFromArray(
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
    $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':F'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  }elseif($cmbR == 2){
    $subjectNya = $data->STOCK_SUBJECT;
    if($no==4){
      $no_jum = $no;    $no++;
      $JUM_TOT_US_PERSUBJECT += $data->TOTAL_US;
      $JUM_TOT_JP_PERSUBJECT += $data->TOTAL_JP;
      $JUM_TOT_RP_PERSUBJECT += $data->TOTAL_RP;
      $JUM_TOT_SG_PERSUBJECT += $data->TOTAL_SG;

      $JUM_JAN_US_PERSUBJECT+=$data->JAN_US;
      $JUM_JAN_JP_PERSUBJECT+=$data->JAN_JP;
      $JUM_JAN_RP_PERSUBJECT+=$data->JAN_RP;
      $JUM_JAN_SG_PERSUBJECT+=$data->JAN_SG;
      $JUM_FEB_US_PERSUBJECT+=$data->FEB_US;
      $JUM_FEB_JP_PERSUBJECT+=$data->FEB_JP;
      $JUM_FEB_RP_PERSUBJECT+=$data->FEB_RP;
      $JUM_FEB_SG_PERSUBJECT+=$data->FEB_SG;
      $JUM_MAR_US_PERSUBJECT+=$data->MAR_US;
      $JUM_MAR_JP_PERSUBJECT+=$data->MAR_JP;
      $JUM_MAR_RP_PERSUBJECT+=$data->MAR_RP;
      $JUM_MAR_SG_PERSUBJECT+=$data->MAR_SG;
      $JUM_APR_US_PERSUBJECT+=$data->APR_US;
      $JUM_APR_JP_PERSUBJECT+=$data->APR_JP;
      $JUM_APR_RP_PERSUBJECT+=$data->APR_RP;
      $JUM_APR_SG_PERSUBJECT+=$data->APR_SG;
      $JUM_MAY_US_PERSUBJECT+=$data->MAY_US;
      $JUM_MAY_JP_PERSUBJECT+=$data->MAY_JP;
      $JUM_MAY_RP_PERSUBJECT+=$data->MAY_RP;
      $JUM_MAY_SG_PERSUBJECT+=$data->MAY_SG;
      $JUM_JUN_US_PERSUBJECT+=$data->JUN_US;
      $JUM_JUN_JP_PERSUBJECT+=$data->JUN_JP;
      $JUM_JUN_RP_PERSUBJECT+=$data->JUN_RP;
      $JUM_JUN_SG_PERSUBJECT+=$data->JUN_SG;
      $JUM_JUL_US_PERSUBJECT+=$data->JUL_US;
      $JUM_JUL_JP_PERSUBJECT+=$data->JUL_JP;
      $JUM_JUL_RP_PERSUBJECT+=$data->JUL_RP;
      $JUM_JUL_SG_PERSUBJECT+=$data->JUL_SG;
      $JUM_AUG_US_PERSUBJECT+=$data->AUG_US;
      $JUM_AUG_JP_PERSUBJECT+=$data->AUG_JP;
      $JUM_AUG_RP_PERSUBJECT+=$data->AUG_RP;
      $JUM_AUG_SG_PERSUBJECT+=$data->AUG_SG;
      $JUM_SEP_US_PERSUBJECT+=$data->SEP_US;
      $JUM_SEP_JP_PERSUBJECT+=$data->SEP_JP;
      $JUM_SEP_RP_PERSUBJECT+=$data->SEP_RP;
      $JUM_SEP_SG_PERSUBJECT+=$data->SEP_SG;
      $JUM_OCT_US_PERSUBJECT+=$data->OCT_US;
      $JUM_OCT_JP_PERSUBJECT+=$data->OCT_JP;
      $JUM_OCT_RP_PERSUBJECT+=$data->OCT_RP;
      $JUM_OCT_SG_PERSUBJECT+=$data->OCT_SG;
      $JUM_NOV_US_PERSUBJECT+=$data->NOV_US;
      $JUM_NOV_JP_PERSUBJECT+=$data->NOV_JP;
      $JUM_NOV_RP_PERSUBJECT+=$data->NOV_RP;
      $JUM_NOV_SG_PERSUBJECT+=$data->NOV_SG;
      $JUM_DEC_US_PERSUBJECT+=$data->DEC_US;
      $JUM_DEC_JP_PERSUBJECT+=$data->DEC_JP;
      $JUM_DEC_RP_PERSUBJECT+=$data->DEC_RP;
      $JUM_DEC_SG_PERSUBJECT+=$data->DEC_SG;                                      
    }else{
      if($subject == $subjectNya){
        $JUM_TOT_US_PERSUBJECT += $data->TOTAL_US;
        $JUM_TOT_JP_PERSUBJECT += $data->TOTAL_JP;
        $JUM_TOT_RP_PERSUBJECT += $data->TOTAL_RP;
        $JUM_TOT_SG_PERSUBJECT += $data->TOTAL_SG;

        $JUM_JAN_US_PERSUBJECT+=$data->JAN_US;
        $JUM_JAN_JP_PERSUBJECT+=$data->JAN_JP;
        $JUM_JAN_RP_PERSUBJECT+=$data->JAN_RP;
        $JUM_JAN_SG_PERSUBJECT+=$data->JAN_SG;
        $JUM_FEB_US_PERSUBJECT+=$data->FEB_US;
        $JUM_FEB_JP_PERSUBJECT+=$data->FEB_JP;
        $JUM_FEB_RP_PERSUBJECT+=$data->FEB_RP;
        $JUM_FEB_SG_PERSUBJECT+=$data->FEB_SG;
        $JUM_MAR_US_PERSUBJECT+=$data->MAR_US;
        $JUM_MAR_JP_PERSUBJECT+=$data->MAR_JP;
        $JUM_MAR_RP_PERSUBJECT+=$data->MAR_RP;
        $JUM_MAR_SG_PERSUBJECT+=$data->MAR_SG;
        $JUM_APR_US_PERSUBJECT+=$data->APR_US;
        $JUM_APR_JP_PERSUBJECT+=$data->APR_JP;
        $JUM_APR_RP_PERSUBJECT+=$data->APR_RP;
        $JUM_APR_SG_PERSUBJECT+=$data->APR_SG;
        $JUM_MAY_US_PERSUBJECT+=$data->MAY_US;
        $JUM_MAY_JP_PERSUBJECT+=$data->MAY_JP;
        $JUM_MAY_RP_PERSUBJECT+=$data->MAY_RP;
        $JUM_MAY_SG_PERSUBJECT+=$data->MAY_SG;
        $JUM_JUN_US_PERSUBJECT+=$data->JUN_US;
        $JUM_JUN_JP_PERSUBJECT+=$data->JUN_JP;
        $JUM_JUN_RP_PERSUBJECT+=$data->JUN_RP;
        $JUM_JUN_SG_PERSUBJECT+=$data->JUN_SG;
        $JUM_JUL_US_PERSUBJECT+=$data->JUL_US;
        $JUM_JUL_JP_PERSUBJECT+=$data->JUL_JP;
        $JUM_JUL_RP_PERSUBJECT+=$data->JUL_RP;
        $JUM_JUL_SG_PERSUBJECT+=$data->JUL_SG;
        $JUM_AUG_US_PERSUBJECT+=$data->AUG_US;
        $JUM_AUG_JP_PERSUBJECT+=$data->AUG_JP;
        $JUM_AUG_RP_PERSUBJECT+=$data->AUG_RP;
        $JUM_AUG_SG_PERSUBJECT+=$data->AUG_SG;
        $JUM_SEP_US_PERSUBJECT+=$data->SEP_US;
        $JUM_SEP_JP_PERSUBJECT+=$data->SEP_JP;
        $JUM_SEP_RP_PERSUBJECT+=$data->SEP_RP;
        $JUM_SEP_SG_PERSUBJECT+=$data->SEP_SG;
        $JUM_OCT_US_PERSUBJECT+=$data->OCT_US;
        $JUM_OCT_JP_PERSUBJECT+=$data->OCT_JP;
        $JUM_OCT_RP_PERSUBJECT+=$data->OCT_RP;
        $JUM_OCT_SG_PERSUBJECT+=$data->OCT_SG;
        $JUM_NOV_US_PERSUBJECT+=$data->NOV_US;
        $JUM_NOV_JP_PERSUBJECT+=$data->NOV_JP;
        $JUM_NOV_RP_PERSUBJECT+=$data->NOV_RP;
        $JUM_NOV_SG_PERSUBJECT+=$data->NOV_SG;
        $JUM_DEC_US_PERSUBJECT+=$data->DEC_US;
        $JUM_DEC_JP_PERSUBJECT+=$data->DEC_JP;
        $JUM_DEC_RP_PERSUBJECT+=$data->DEC_RP;
        $JUM_DEC_SG_PERSUBJECT+=$data->DEC_SG;
      }else{
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$no_jum, strtoupper($subject))
                    ->setCellValue('C'.$no_jum, $JUM_TOT_US_PERSUBJECT)
                    ->setCellValue('D'.$no_jum, $JUM_TOT_JP_PERSUBJECT)
                    ->setCellValue('E'.$no_jum, $JUM_TOT_RP_PERSUBJECT)
                    ->setCellValue('F'.$no_jum, $JUM_TOT_SG_PERSUBJECT)
                    ->setCellValue('G'.$no_jum, $JUM_JAN_US_PERSUBJECT)
                    ->setCellValue('H'.$no_jum, $JUM_JAN_JP_PERSUBJECT)
                    ->setCellValue('I'.$no_jum, $JUM_JAN_RP_PERSUBJECT)
                    ->setCellValue('J'.$no_jum, $JUM_JAN_SG_PERSUBJECT)
                    ->setCellValue('K'.$no_jum, $JUM_FEB_US_PERSUBJECT)
                    ->setCellValue('L'.$no_jum, $JUM_FEB_JP_PERSUBJECT)
                    ->setCellValue('M'.$no_jum, $JUM_FEB_RP_PERSUBJECT)
                    ->setCellValue('N'.$no_jum, $JUM_FEB_SG_PERSUBJECT)
                    ->setCellValue('O'.$no_jum, $JUM_MAR_US_PERSUBJECT)
                    ->setCellValue('P'.$no_jum, $JUM_MAR_JP_PERSUBJECT)
                    ->setCellValue('Q'.$no_jum, $JUM_MAR_RP_PERSUBJECT)
                    ->setCellValue('R'.$no_jum, $JUM_MAR_SG_PERSUBJECT)
                    ->setCellValue('S'.$no_jum, $JUM_APR_US_PERSUBJECT)
                    ->setCellValue('T'.$no_jum, $JUM_APR_JP_PERSUBJECT)
                    ->setCellValue('U'.$no_jum, $JUM_APR_RP_PERSUBJECT)
                    ->setCellValue('V'.$no_jum, $JUM_APR_SG_PERSUBJECT)
                    ->setCellValue('W'.$no_jum, $JUM_MAY_US_PERSUBJECT)
                    ->setCellValue('X'.$no_jum, $JUM_MAY_JP_PERSUBJECT)
                    ->setCellValue('Y'.$no_jum, $JUM_MAY_RP_PERSUBJECT)
                    ->setCellValue('Z'.$no_jum, $JUM_MAY_SG_PERSUBJECT)
                    ->setCellValue('AA'.$no_jum, $JUM_JUN_US_PERSUBJECT)
                    ->setCellValue('AB'.$no_jum, $JUM_JUN_JP_PERSUBJECT)
                    ->setCellValue('AC'.$no_jum, $JUM_JUN_RP_PERSUBJECT)
                    ->setCellValue('AD'.$no_jum, $JUM_JUN_SG_PERSUBJECT)
                    ->setCellValue('AE'.$no_jum, $JUM_JUL_US_PERSUBJECT)
                    ->setCellValue('AF'.$no_jum, $JUM_JUL_JP_PERSUBJECT)
                    ->setCellValue('AG'.$no_jum, $JUM_JUL_RP_PERSUBJECT)
                    ->setCellValue('AH'.$no_jum, $JUM_JUL_SG_PERSUBJECT)
                    ->setCellValue('AI'.$no_jum, $JUM_AUG_US_PERSUBJECT)
                    ->setCellValue('AJ'.$no_jum, $JUM_AUG_JP_PERSUBJECT)
                    ->setCellValue('AK'.$no_jum, $JUM_AUG_RP_PERSUBJECT)
                    ->setCellValue('AL'.$no_jum, $JUM_AUG_SG_PERSUBJECT)
                    ->setCellValue('AM'.$no_jum, $JUM_SEP_US_PERSUBJECT)
                    ->setCellValue('AN'.$no_jum, $JUM_SEP_JP_PERSUBJECT)
                    ->setCellValue('AO'.$no_jum, $JUM_SEP_RP_PERSUBJECT)
                    ->setCellValue('AP'.$no_jum, $JUM_SEP_SG_PERSUBJECT)
                    ->setCellValue('AQ'.$no_jum, $JUM_OCT_US_PERSUBJECT)
                    ->setCellValue('AR'.$no_jum, $JUM_OCT_JP_PERSUBJECT)
                    ->setCellValue('AS'.$no_jum, $JUM_OCT_RP_PERSUBJECT)
                    ->setCellValue('AT'.$no_jum, $JUM_OCT_SG_PERSUBJECT)
                    ->setCellValue('AU'.$no_jum, $JUM_NOV_US_PERSUBJECT)
                    ->setCellValue('AV'.$no_jum, $JUM_NOV_JP_PERSUBJECT)
                    ->setCellValue('AW'.$no_jum, $JUM_NOV_RP_PERSUBJECT)
                    ->setCellValue('AX'.$no_jum, $JUM_NOV_SG_PERSUBJECT)
                    ->setCellValue('AY'.$no_jum, $JUM_DEC_US_PERSUBJECT)
                    ->setCellValue('AZ'.$no_jum, $JUM_DEC_JP_PERSUBJECT)
                    ->setCellValue('BA'.$no_jum, $JUM_DEC_RP_PERSUBJECT)
                    ->setCellValue('BB'.$no_jum, $JUM_DEC_SG_PERSUBJECT);

        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no_jum.':B'.$no_jum);

        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('A'.$no_jum)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '00AAFF')
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

        $sheet->getStyle('C'.$no_jum.':BB'.$no_jum)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '00AAFF')
                ),
                'borders' => array(
                   'allborders' => array(
                       'style' => PHPExcel_Style_Border::BORDER_THIN
                   )
                )
            )
        );

        cellColor('A'.$no_jum.':BB'.$no_jum, '00AAFF');
        $sheet->getStyle('A'.$no_jum)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $objPHPExcel->getActiveSheet()->getStyle('A'.$no_jum.':BB'.$no_jum)->getFont()->setBold(true)->setSize(12);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$no_jum.':BB'.$no_jum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        $JUM_TOTAL_US += $JUM_TOT_US_PERSUBJECT;
        $JUM_TOTAL_JP += $JUM_TOT_JP_PERSUBJECT;
        $JUM_TOTAL_RP += $JUM_TOT_RP_PERSUBJECT;
        $JUM_TOTAL_SG += $JUM_TOT_SG_PERSUBJECT;

        $JUM_JAN_US+=$JUM_JAN_US_PERSUBJECT;
        $JUM_JAN_JP+=$JUM_JAN_JP_PERSUBJECT;
        $JUM_JAN_RP+=$JUM_JAN_RP_PERSUBJECT;
        $JUM_JAN_SG+=$JUM_JAN_SG_PERSUBJECT;
        $JUM_FEB_US+=$JUM_FEB_US_PERSUBJECT;
        $JUM_FEB_JP+=$JUM_FEB_JP_PERSUBJECT;
        $JUM_FEB_RP+=$JUM_FEB_RP_PERSUBJECT;
        $JUM_FEB_SG+=$JUM_FEB_SG_PERSUBJECT;
        $JUM_MAR_US+=$JUM_MAR_US_PERSUBJECT;
        $JUM_MAR_JP+=$JUM_MAR_JP_PERSUBJECT;
        $JUM_MAR_RP+=$JUM_MAR_RP_PERSUBJECT;
        $JUM_MAR_SG+=$JUM_MAR_SG_PERSUBJECT;
        $JUM_APR_US+=$JUM_APR_US_PERSUBJECT;
        $JUM_APR_JP+=$JUM_APR_JP_PERSUBJECT;
        $JUM_APR_RP+=$JUM_APR_RP_PERSUBJECT;
        $JUM_APR_SG+=$JUM_APR_SG_PERSUBJECT;
        $JUM_MAY_US+=$JUM_MAY_US_PERSUBJECT;
        $JUM_MAY_JP+=$JUM_MAY_JP_PERSUBJECT;
        $JUM_MAY_RP+=$JUM_MAY_RP_PERSUBJECT;
        $JUM_MAY_SG+=$JUM_MAY_SG_PERSUBJECT;
        $JUM_JUN_US+=$JUM_JUN_US_PERSUBJECT;
        $JUM_JUN_JP+=$JUM_JUN_JP_PERSUBJECT;
        $JUM_JUN_RP+=$JUM_JUN_RP_PERSUBJECT;
        $JUM_JUN_SG+=$JUM_JUN_SG_PERSUBJECT;
        $JUM_JUL_US+=$JUM_JUL_US_PERSUBJECT;
        $JUM_JUL_JP+=$JUM_JUL_JP_PERSUBJECT;
        $JUM_JUL_RP+=$JUM_JUL_RP_PERSUBJECT;
        $JUM_JUL_SG+=$JUM_JUL_SG_PERSUBJECT;
        $JUM_AUG_US+=$JUM_AUG_US_PERSUBJECT;
        $JUM_AUG_JP+=$JUM_AUG_JP_PERSUBJECT;
        $JUM_AUG_RP+=$JUM_AUG_RP_PERSUBJECT;
        $JUM_AUG_SG+=$JUM_AUG_SG_PERSUBJECT;
        $JUM_SEP_US+=$JUM_SEP_US_PERSUBJECT;
        $JUM_SEP_JP+=$JUM_SEP_JP_PERSUBJECT;
        $JUM_SEP_RP+=$JUM_SEP_RP_PERSUBJECT;
        $JUM_SEP_SG+=$JUM_SEP_SG_PERSUBJECT;
        $JUM_OCT_US+=$JUM_OCT_US_PERSUBJECT;
        $JUM_OCT_JP+=$JUM_OCT_JP_PERSUBJECT;
        $JUM_OCT_RP+=$JUM_OCT_RP_PERSUBJECT;
        $JUM_OCT_SG+=$JUM_OCT_SG_PERSUBJECT;
        $JUM_NOV_US+=$JUM_NOV_US_PERSUBJECT;
        $JUM_NOV_JP+=$JUM_NOV_JP_PERSUBJECT;
        $JUM_NOV_RP+=$JUM_NOV_RP_PERSUBJECT;
        $JUM_NOV_SG+=$JUM_NOV_SG_PERSUBJECT;
        $JUM_DEC_US+=$JUM_DEC_US_PERSUBJECT;
        $JUM_DEC_JP+=$JUM_DEC_JP_PERSUBJECT;
        $JUM_DEC_RP+=$JUM_DEC_RP_PERSUBJECT;
        $JUM_DEC_SG+=$JUM_DEC_SG_PERSUBJECT;

        $no_jum=$no;    $no++;

        $JUM_TOT_US_PERSUBJECT=0;      $JUM_TOT_JP_PERSUBJECT=0;      $JUM_TOT_RP_PERSUBJECT=0;      $JUM_TOT_SG_PERSUBJECT=0;
        $JUM_JAN_US_PERSUBJECT=0;      $JUM_JAN_JP_PERSUBJECT=0;      $JUM_JAN_RP_PERSUBJECT=0;      $JUM_JAN_SG_PERSUBJECT=0;
        $JUM_FEB_US_PERSUBJECT=0;      $JUM_FEB_JP_PERSUBJECT=0;      $JUM_FEB_RP_PERSUBJECT=0;      $JUM_FEB_SG_PERSUBJECT=0;
        $JUM_MAR_US_PERSUBJECT=0;      $JUM_MAR_JP_PERSUBJECT=0;      $JUM_MAR_RP_PERSUBJECT=0;      $JUM_MAR_SG_PERSUBJECT=0;
        $JUM_APR_US_PERSUBJECT=0;      $JUM_APR_JP_PERSUBJECT=0;      $JUM_APR_RP_PERSUBJECT=0;      $JUM_APR_SG_PERSUBJECT=0;
        $JUM_MAY_US_PERSUBJECT=0;      $JUM_MAY_JP_PERSUBJECT=0;      $JUM_MAY_RP_PERSUBJECT=0;      $JUM_MAY_SG_PERSUBJECT=0;
        $JUM_JUN_US_PERSUBJECT=0;      $JUM_JUN_JP_PERSUBJECT=0;      $JUM_JUN_RP_PERSUBJECT=0;      $JUM_JUN_SG_PERSUBJECT=0;
        $JUM_JUL_US_PERSUBJECT=0;      $JUM_JUL_JP_PERSUBJECT=0;      $JUM_JUL_RP_PERSUBJECT=0;      $JUM_JUL_SG_PERSUBJECT=0;
        $JUM_AUG_US_PERSUBJECT=0;      $JUM_AUG_JP_PERSUBJECT=0;      $JUM_AUG_RP_PERSUBJECT=0;      $JUM_AUG_SG_PERSUBJECT=0;
        $JUM_SEP_US_PERSUBJECT=0;      $JUM_SEP_JP_PERSUBJECT=0;      $JUM_SEP_RP_PERSUBJECT=0;      $JUM_SEP_SG_PERSUBJECT=0;
        $JUM_OCT_US_PERSUBJECT=0;      $JUM_OCT_JP_PERSUBJECT=0;      $JUM_OCT_RP_PERSUBJECT=0;      $JUM_OCT_SG_PERSUBJECT=0;
        $JUM_NOV_US_PERSUBJECT=0;      $JUM_NOV_JP_PERSUBJECT=0;      $JUM_NOV_RP_PERSUBJECT=0;      $JUM_NOV_SG_PERSUBJECT=0;
        $JUM_DEC_US_PERSUBJECT=0;      $JUM_DEC_JP_PERSUBJECT=0;      $JUM_DEC_RP_PERSUBJECT=0;      $JUM_DEC_SG_PERSUBJECT=0;

        $JUM_TOT_US_PERSUBJECT+=$data->TOTAL_US;
        $JUM_TOT_JP_PERSUBJECT+=$data->TOTAL_JP;
        $JUM_TOT_RP_PERSUBJECT+=$data->TOTAL_RP;
        $JUM_TOT_SG_PERSUBJECT+=$data->TOTAL_SG;

        $JUM_JAN_US_PERSUBJECT+=$data->JAN_US;
        $JUM_JAN_JP_PERSUBJECT+=$data->JAN_JP;
        $JUM_JAN_RP_PERSUBJECT+=$data->JAN_RP;
        $JUM_JAN_SG_PERSUBJECT+=$data->JAN_SG;
        $JUM_FEB_US_PERSUBJECT+=$data->FEB_US;
        $JUM_FEB_JP_PERSUBJECT+=$data->FEB_JP;
        $JUM_FEB_RP_PERSUBJECT+=$data->FEB_RP;
        $JUM_FEB_SG_PERSUBJECT+=$data->FEB_SG;
        $JUM_MAR_US_PERSUBJECT+=$data->MAR_US;
        $JUM_MAR_JP_PERSUBJECT+=$data->MAR_JP;
        $JUM_MAR_RP_PERSUBJECT+=$data->MAR_RP;
        $JUM_MAR_SG_PERSUBJECT+=$data->MAR_SG;
        $JUM_APR_US_PERSUBJECT+=$data->APR_US;
        $JUM_APR_JP_PERSUBJECT+=$data->APR_JP;
        $JUM_APR_RP_PERSUBJECT+=$data->APR_RP;
        $JUM_APR_SG_PERSUBJECT+=$data->APR_SG;
        $JUM_MAY_US_PERSUBJECT+=$data->MAY_US;
        $JUM_MAY_JP_PERSUBJECT+=$data->MAY_JP;
        $JUM_MAY_RP_PERSUBJECT+=$data->MAY_RP;
        $JUM_MAY_SG_PERSUBJECT+=$data->MAY_SG;
        $JUM_JUN_US_PERSUBJECT+=$data->JUN_US;
        $JUM_JUN_JP_PERSUBJECT+=$data->JUN_JP;
        $JUM_JUN_RP_PERSUBJECT+=$data->JUN_RP;
        $JUM_JUN_SG_PERSUBJECT+=$data->JUN_SG;
        $JUM_JUL_US_PERSUBJECT+=$data->JUL_US;
        $JUM_JUL_JP_PERSUBJECT+=$data->JUL_JP;
        $JUM_JUL_RP_PERSUBJECT+=$data->JUL_RP;
        $JUM_JUL_SG_PERSUBJECT+=$data->JUL_SG;
        $JUM_AUG_US_PERSUBJECT+=$data->AUG_US;
        $JUM_AUG_JP_PERSUBJECT+=$data->AUG_JP;
        $JUM_AUG_RP_PERSUBJECT+=$data->AUG_RP;
        $JUM_AUG_SG_PERSUBJECT+=$data->AUG_SG;
        $JUM_SEP_US_PERSUBJECT+=$data->SEP_US;
        $JUM_SEP_JP_PERSUBJECT+=$data->SEP_JP;
        $JUM_SEP_RP_PERSUBJECT+=$data->SEP_RP;
        $JUM_SEP_SG_PERSUBJECT+=$data->SEP_SG;
        $JUM_OCT_US_PERSUBJECT+=$data->OCT_US;
        $JUM_OCT_JP_PERSUBJECT+=$data->OCT_JP;
        $JUM_OCT_RP_PERSUBJECT+=$data->OCT_RP;
        $JUM_OCT_SG_PERSUBJECT+=$data->OCT_SG;
        $JUM_NOV_US_PERSUBJECT+=$data->NOV_US;
        $JUM_NOV_JP_PERSUBJECT+=$data->NOV_JP;
        $JUM_NOV_RP_PERSUBJECT+=$data->NOV_RP;
        $JUM_NOV_SG_PERSUBJECT+=$data->NOV_SG;
        $JUM_DEC_US_PERSUBJECT+=$data->DEC_US;
        $JUM_DEC_JP_PERSUBJECT+=$data->DEC_JP;
        $JUM_DEC_RP_PERSUBJECT+=$data->DEC_RP;
        $JUM_DEC_SG_PERSUBJECT+=$data->DEC_SG;
      }
    }
    $subject = $subjectNya;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data->SUPPLIER_CODE.'-'.$data->NAMA)
                ->setCellValue('C'.$no, $data->TOTAL_US)
                ->setCellValue('D'.$no, $data->TOTAL_JP)
                ->setCellValue('E'.$no, $data->TOTAL_RP)
                ->setCellValue('F'.$no, $data->TOTAL_SG)
                ->setCellValue('G'.$no, $data->JAN_US)
                ->setCellValue('H'.$no, $data->JAN_JP)
                ->setCellValue('I'.$no, $data->JAN_RP)
                ->setCellValue('J'.$no, $data->JAN_SG)
                ->setCellValue('K'.$no, $data->FEB_US)
                ->setCellValue('L'.$no, $data->FEB_JP)
                ->setCellValue('M'.$no, $data->FEB_RP)
                ->setCellValue('N'.$no, $data->FEB_SG)
                ->setCellValue('O'.$no, $data->MAR_US)
                ->setCellValue('P'.$no, $data->MAR_JP)
                ->setCellValue('Q'.$no, $data->MAR_RP)
                ->setCellValue('R'.$no, $data->MAR_SG)
                ->setCellValue('S'.$no, $data->APR_US)
                ->setCellValue('T'.$no, $data->APR_JP)
                ->setCellValue('U'.$no, $data->APR_RP)
                ->setCellValue('V'.$no, $data->APR_SG)
                ->setCellValue('W'.$no, $data->MAY_US)
                ->setCellValue('X'.$no, $data->MAY_JP)
                ->setCellValue('Y'.$no, $data->MAY_RP)
                ->setCellValue('Z'.$no, $data->MAY_SG)
                ->setCellValue('AA'.$no, $data->JUN_US)
                ->setCellValue('AB'.$no, $data->JUN_JP)
                ->setCellValue('AC'.$no, $data->JUN_RP)
                ->setCellValue('AD'.$no, $data->JUN_SG)
                ->setCellValue('AE'.$no, $data->JUL_US)
                ->setCellValue('AF'.$no, $data->JUL_JP)
                ->setCellValue('AG'.$no, $data->JUL_RP)
                ->setCellValue('AH'.$no, $data->JUL_SG)
                ->setCellValue('AI'.$no, $data->AUG_US)
                ->setCellValue('AJ'.$no, $data->AUG_JP)
                ->setCellValue('AK'.$no, $data->AUG_RP)
                ->setCellValue('AL'.$no, $data->AUG_SG)
                ->setCellValue('AM'.$no, $data->SEP_US)
                ->setCellValue('AN'.$no, $data->SEP_JP)
                ->setCellValue('AO'.$no, $data->SEP_RP)
                ->setCellValue('AP'.$no, $data->SEP_SG)
                ->setCellValue('AQ'.$no, $data->OCT_US)
                ->setCellValue('AR'.$no, $data->OCT_JP)
                ->setCellValue('AS'.$no, $data->OCT_RP)
                ->setCellValue('AT'.$no, $data->OCT_SG)
                ->setCellValue('AU'.$no, $data->NOV_US)
                ->setCellValue('AV'.$no, $data->NOV_JP)
                ->setCellValue('AW'.$no, $data->NOV_RP)
                ->setCellValue('AX'.$no, $data->NOV_SG)
                ->setCellValue('AY'.$no, $data->DEC_US)
                ->setCellValue('AZ'.$no, $data->DEC_JP)
                ->setCellValue('BA'.$no, $data->DEC_RP)
                ->setCellValue('BB'.$no, $data->DEC_SG);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A'.$no.':BB'.$no)->applyFromArray(
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
    $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':BB'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  }

  $no++;
  $noUrut++;
}

//START PARTS 
while ($data_parts=oci_fetch_object($result_parts)){
  if ($cmbR == 1){
    $subjectNya = $data_parts->STOCK_SUBJECT;
    if($subject == $subjectNya){
      $JUM_USD_PERSUBJECT+= $data_parts->US;
      $JUM_YEN_PERSUBJECT+= $data_parts->JP;
      $JUM_SGD_PERSUBJECT+= $data_parts->SGD;
      $JUM_IDR_PERSUBJECT+= $data_parts->RP;
    }else{
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$no_jum, strtoupper($subject))
                  ->setCellValue('C'.$no_jum, $JUM_USD_PERSUBJECT)
                  ->setCellValue('D'.$no_jum, $JUM_YEN_PERSUBJECT)
                  ->setCellValue('E'.$no_jum, $JUM_SGD_PERSUBJECT)
                  ->setCellValue('F'.$no_jum, $JUM_IDR_PERSUBJECT);
      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no_jum.':B'.$no_jum);

      $sheet = $objPHPExcel->getActiveSheet();
      $sheet->getStyle('A'.$no_jum)->applyFromArray(
          array(
              'fill' => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => '00AAFF')
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

      $sheet->getStyle('A'.$no_jum.':G'.$no_jum)->applyFromArray(
          array(
              'fill' => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => '00AAFF')
              ),
              'borders' => array(
                 'allborders' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN
                 )
              )
          )
      );

      cellColor('A'.$no_jum.':G'.$no_jum, '00AAFF');
      $sheet->getStyle('A'.$no_jum)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
      $objPHPExcel->getActiveSheet()->getStyle('A'.$no_jum.':G'.$no_jum)->getFont()->setBold(true)->setSize(12);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$no_jum.':F'.$no_jum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

      $JUM_USD += $JUM_USD_PERSUBJECT;
      $JUM_YEN += $JUM_YEN_PERSUBJECT;
      $JUM_SGD += $JUM_SGD_PERSUBJECT;
      $JUM_IDR += $JUM_IDR_PERSUBJECT;

      $no_jum=$no;    $no++;
      $JUM_USD_PERSUBJECT=0;
      $JUM_YEN_PERSUBJECT=0;
      $JUM_SGD_PERSUBJECT=0;
      $JUM_IDR_PERSUBJECT=0;

      $JUM_USD_PERSUBJECT+= $data_parts->US;
      $JUM_YEN_PERSUBJECT+= $data_parts->JP;
      $JUM_SGD_PERSUBJECT+= $data_parts->SGD;
      $JUM_IDR_PERSUBJECT+= $data_parts->RP;
    }

    $subject = $subjectNya;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data_parts->SUPPLIER_CODE.'-'.$data_parts->NAMA)
                ->setCellValue('C'.$no, $data_parts->US)
                ->setCellValue('D'.$no, $data_parts->JP)
                ->setCellValue('E'.$no, $data_parts->SGD)
                ->setCellValue('F'.$no, $data_parts->RP)
                ->setCellValue('G'.$no, $data_parts->PDAYS.' '.strtoupper($data->PDESC));
    $sheet = $objPHPExcel->getActiveSheet();

    $sheet->getStyle('A'.$no.':G'.$no)->applyFromArray(
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
    $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':F'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  }elseif($cmbR == 2){
    $subjectNya = $data_parts->STOCK_SUBJECT;
    if($subject == $subjectNya){
      $JUM_TOT_US_PERSUBJECT+=$data_parts->TOTAL_US;
      $JUM_TOT_JP_PERSUBJECT+=$data_parts->TOTAL_JP;
      $JUM_TOT_RP_PERSUBJECT+=$data_parts->TOTAL_RP;
      $JUM_TOT_SG_PERSUBJECT+=$data_parts->TOTAL_SG;

      $JUM_JAN_US_PERSUBJECT+=$data_parts->JAN_US;
      $JUM_JAN_JP_PERSUBJECT+=$data_parts->JAN_JP;
      $JUM_JAN_RP_PERSUBJECT+=$data_parts->JAN_RP;
      $JUM_JAN_SG_PERSUBJECT+=$data_parts->JAN_SG;
      $JUM_FEB_US_PERSUBJECT+=$data_parts->FEB_US;
      $JUM_FEB_JP_PERSUBJECT+=$data_parts->FEB_JP;
      $JUM_FEB_RP_PERSUBJECT+=$data_parts->FEB_RP;
      $JUM_FEB_SG_PERSUBJECT+=$data_parts->FEB_SG;
      $JUM_MAR_US_PERSUBJECT+=$data_parts->MAR_US;
      $JUM_MAR_JP_PERSUBJECT+=$data_parts->MAR_JP;
      $JUM_MAR_RP_PERSUBJECT+=$data_parts->MAR_RP;
      $JUM_MAR_SG_PERSUBJECT+=$data_parts->MAR_SG;
      $JUM_APR_US_PERSUBJECT+=$data_parts->APR_US;
      $JUM_APR_JP_PERSUBJECT+=$data_parts->APR_JP;
      $JUM_APR_RP_PERSUBJECT+=$data_parts->APR_RP;
      $JUM_APR_SG_PERSUBJECT+=$data_parts->APR_SG;
      $JUM_MAY_US_PERSUBJECT+=$data_parts->MAY_US;
      $JUM_MAY_JP_PERSUBJECT+=$data_parts->MAY_JP;
      $JUM_MAY_RP_PERSUBJECT+=$data_parts->MAY_RP;
      $JUM_MAY_SG_PERSUBJECT+=$data_parts->MAY_SG;
      $JUM_JUN_US_PERSUBJECT+=$data_parts->JUN_US;
      $JUM_JUN_JP_PERSUBJECT+=$data_parts->JUN_JP;
      $JUM_JUN_RP_PERSUBJECT+=$data_parts->JUN_RP;
      $JUM_JUN_SG_PERSUBJECT+=$data_parts->JUN_SG;
      $JUM_JUL_US_PERSUBJECT+=$data_parts->JUL_US;
      $JUM_JUL_JP_PERSUBJECT+=$data_parts->JUL_JP;
      $JUM_JUL_RP_PERSUBJECT+=$data_parts->JUL_RP;
      $JUM_JUL_SG_PERSUBJECT+=$data_parts->JUL_SG;
      $JUM_AUG_US_PERSUBJECT+=$data_parts->AUG_US;
      $JUM_AUG_JP_PERSUBJECT+=$data_parts->AUG_JP;
      $JUM_AUG_RP_PERSUBJECT+=$data_parts->AUG_RP;
      $JUM_AUG_SG_PERSUBJECT+=$data_parts->AUG_SG;
      $JUM_SEP_US_PERSUBJECT+=$data_parts->SEP_US;
      $JUM_SEP_JP_PERSUBJECT+=$data_parts->SEP_JP;
      $JUM_SEP_RP_PERSUBJECT+=$data_parts->SEP_RP;
      $JUM_SEP_SG_PERSUBJECT+=$data_parts->SEP_SG;
      $JUM_OCT_US_PERSUBJECT+=$data_parts->OCT_US;
      $JUM_OCT_JP_PERSUBJECT+=$data_parts->OCT_JP;
      $JUM_OCT_RP_PERSUBJECT+=$data_parts->OCT_RP;
      $JUM_OCT_SG_PERSUBJECT+=$data_parts->OCT_SG;
      $JUM_NOV_US_PERSUBJECT+=$data_parts->NOV_US;
      $JUM_NOV_JP_PERSUBJECT+=$data_parts->NOV_JP;
      $JUM_NOV_RP_PERSUBJECT+=$data_parts->NOV_RP;
      $JUM_NOV_SG_PERSUBJECT+=$data_parts->NOV_SG;
      $JUM_DEC_US_PERSUBJECT+=$data_parts->DEC_US;
      $JUM_DEC_JP_PERSUBJECT+=$data_parts->DEC_JP;
      $JUM_DEC_RP_PERSUBJECT+=$data_parts->DEC_RP;
      $JUM_DEC_SG_PERSUBJECT+=$data_parts->DEC_SG;                                      
    }else{
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$no_jum, strtoupper($subject))
                  ->setCellValue('C'.$no_jum, $JUM_TOT_US_PERSUBJECT)
                  ->setCellValue('D'.$no_jum, $JUM_TOT_JP_PERSUBJECT)
                  ->setCellValue('E'.$no_jum, $JUM_TOT_RP_PERSUBJECT)
                  ->setCellValue('F'.$no_jum, $JUM_TOT_SG_PERSUBJECT)
                  ->setCellValue('G'.$no_jum, $JUM_JAN_US_PERSUBJECT)
                  ->setCellValue('H'.$no_jum, $JUM_JAN_JP_PERSUBJECT)
                  ->setCellValue('I'.$no_jum, $JUM_JAN_RP_PERSUBJECT)
                  ->setCellValue('J'.$no_jum, $JUM_JAN_SG_PERSUBJECT)
                  ->setCellValue('K'.$no_jum, $JUM_FEB_US_PERSUBJECT)
                  ->setCellValue('L'.$no_jum, $JUM_FEB_JP_PERSUBJECT)
                  ->setCellValue('M'.$no_jum, $JUM_FEB_RP_PERSUBJECT)
                  ->setCellValue('N'.$no_jum, $JUM_FEB_SG_PERSUBJECT)
                  ->setCellValue('O'.$no_jum, $JUM_MAR_US_PERSUBJECT)
                  ->setCellValue('P'.$no_jum, $JUM_MAR_JP_PERSUBJECT)
                  ->setCellValue('Q'.$no_jum, $JUM_MAR_RP_PERSUBJECT)
                  ->setCellValue('R'.$no_jum, $JUM_MAR_SG_PERSUBJECT)
                  ->setCellValue('S'.$no_jum, $JUM_APR_US_PERSUBJECT)
                  ->setCellValue('T'.$no_jum, $JUM_APR_JP_PERSUBJECT)
                  ->setCellValue('U'.$no_jum, $JUM_APR_RP_PERSUBJECT)
                  ->setCellValue('V'.$no_jum, $JUM_APR_SG_PERSUBJECT)
                  ->setCellValue('W'.$no_jum, $JUM_MAY_US_PERSUBJECT)
                  ->setCellValue('X'.$no_jum, $JUM_MAY_JP_PERSUBJECT)
                  ->setCellValue('Y'.$no_jum, $JUM_MAY_RP_PERSUBJECT)
                  ->setCellValue('Z'.$no_jum, $JUM_MAY_SG_PERSUBJECT)
                  ->setCellValue('AA'.$no_jum, $JUM_JUN_US_PERSUBJECT)
                  ->setCellValue('AB'.$no_jum, $JUM_JUN_JP_PERSUBJECT)
                  ->setCellValue('AC'.$no_jum, $JUM_JUN_RP_PERSUBJECT)
                  ->setCellValue('AD'.$no_jum, $JUM_JUN_SG_PERSUBJECT)
                  ->setCellValue('AE'.$no_jum, $JUM_JUL_US_PERSUBJECT)
                  ->setCellValue('AF'.$no_jum, $JUM_JUL_JP_PERSUBJECT)
                  ->setCellValue('AG'.$no_jum, $JUM_JUL_RP_PERSUBJECT)
                  ->setCellValue('AH'.$no_jum, $JUM_JUL_SG_PERSUBJECT)
                  ->setCellValue('AI'.$no_jum, $JUM_AUG_US_PERSUBJECT)
                  ->setCellValue('AJ'.$no_jum, $JUM_AUG_JP_PERSUBJECT)
                  ->setCellValue('AK'.$no_jum, $JUM_AUG_RP_PERSUBJECT)
                  ->setCellValue('AL'.$no_jum, $JUM_AUG_SG_PERSUBJECT)
                  ->setCellValue('AM'.$no_jum, $JUM_SEP_US_PERSUBJECT)
                  ->setCellValue('AN'.$no_jum, $JUM_SEP_JP_PERSUBJECT)
                  ->setCellValue('AO'.$no_jum, $JUM_SEP_RP_PERSUBJECT)
                  ->setCellValue('AP'.$no_jum, $JUM_SEP_SG_PERSUBJECT)
                  ->setCellValue('AQ'.$no_jum, $JUM_OCT_US_PERSUBJECT)
                  ->setCellValue('AR'.$no_jum, $JUM_OCT_JP_PERSUBJECT)
                  ->setCellValue('AS'.$no_jum, $JUM_OCT_RP_PERSUBJECT)
                  ->setCellValue('AT'.$no_jum, $JUM_OCT_SG_PERSUBJECT)
                  ->setCellValue('AU'.$no_jum, $JUM_NOV_US_PERSUBJECT)
                  ->setCellValue('AV'.$no_jum, $JUM_NOV_JP_PERSUBJECT)
                  ->setCellValue('AW'.$no_jum, $JUM_NOV_RP_PERSUBJECT)
                  ->setCellValue('AX'.$no_jum, $JUM_NOV_SG_PERSUBJECT)
                  ->setCellValue('AY'.$no_jum, $JUM_DEC_US_PERSUBJECT)
                  ->setCellValue('AZ'.$no_jum, $JUM_DEC_JP_PERSUBJECT)
                  ->setCellValue('BA'.$no_jum, $JUM_DEC_RP_PERSUBJECT)
                  ->setCellValue('BB'.$no_jum, $JUM_DEC_SG_PERSUBJECT);

      $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no_jum.':B'.$no_jum);

      $sheet = $objPHPExcel->getActiveSheet();
      $sheet->getStyle('A'.$no_jum)->applyFromArray(
          array(
              'fill' => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => '00AAFF')
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

      $sheet->getStyle('C'.$no_jum.':BB'.$no_jum)->applyFromArray(
          array(
              'fill' => array(
                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                  'color' => array('rgb' => '00AAFF')
              ),
              'borders' => array(
                 'allborders' => array(
                     'style' => PHPExcel_Style_Border::BORDER_THIN
                 )
              )
          )
      );

      cellColor('A'.$no_jum.':BB'.$no_jum, '00AAFF');
      $sheet->getStyle('A'.$no_jum)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
      $objPHPExcel->getActiveSheet()->getStyle('A'.$no_jum.':BB'.$no_jum)->getFont()->setBold(true)->setSize(12);
      $objPHPExcel->getActiveSheet()->getStyle('C'.$no_jum.':BB'.$no_jum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

      $JUM_TOTAL_US+= $JUM_TOT_US_PERSUBJECT;
      $JUM_TOTAL_JP+= $JUM_TOT_JP_PERSUBJECT;
      $JUM_TOTAL_RP+= $JUM_TOT_RP_PERSUBJECT;
      $JUM_TOTAL_SG+= $JUM_TOT_SG_PERSUBJECT;

      $JUM_JAN_US+=$JUM_JAN_US_PERSUBJECT;
      $JUM_JAN_JP+=$JUM_JAN_JP_PERSUBJECT;
      $JUM_JAN_RP+=$JUM_JAN_RP_PERSUBJECT;
      $JUM_JAN_SG+=$JUM_JAN_SG_PERSUBJECT;
      $JUM_FEB_US+=$JUM_FEB_US_PERSUBJECT;
      $JUM_FEB_JP+=$JUM_FEB_JP_PERSUBJECT;
      $JUM_FEB_RP+=$JUM_FEB_RP_PERSUBJECT;
      $JUM_FEB_SG+=$JUM_FEB_SG_PERSUBJECT;
      $JUM_MAR_US+=$JUM_MAR_US_PERSUBJECT;
      $JUM_MAR_JP+=$JUM_MAR_JP_PERSUBJECT;
      $JUM_MAR_RP+=$JUM_MAR_RP_PERSUBJECT;
      $JUM_MAR_SG+=$JUM_MAR_SG_PERSUBJECT;
      $JUM_APR_US+=$JUM_APR_US_PERSUBJECT;
      $JUM_APR_JP+=$JUM_APR_JP_PERSUBJECT;
      $JUM_APR_RP+=$JUM_APR_RP_PERSUBJECT;
      $JUM_APR_SG+=$JUM_APR_SG_PERSUBJECT;
      $JUM_MAY_US+=$JUM_MAY_US_PERSUBJECT;
      $JUM_MAY_JP+=$JUM_MAY_JP_PERSUBJECT;
      $JUM_MAY_RP+=$JUM_MAY_RP_PERSUBJECT;
      $JUM_MAY_SG+=$JUM_MAY_SG_PERSUBJECT;
      $JUM_JUN_US+=$JUM_JUN_US_PERSUBJECT;
      $JUM_JUN_JP+=$JUM_JUN_JP_PERSUBJECT;
      $JUM_JUN_RP+=$JUM_JUN_RP_PERSUBJECT;
      $JUM_JUN_SG+=$JUM_JUN_SG_PERSUBJECT;
      $JUM_JUL_US+=$JUM_JUL_US_PERSUBJECT;
      $JUM_JUL_JP+=$JUM_JUL_JP_PERSUBJECT;
      $JUM_JUL_RP+=$JUM_JUL_RP_PERSUBJECT;
      $JUM_JUL_SG+=$JUM_JUL_SG_PERSUBJECT;
      $JUM_AUG_US+=$JUM_AUG_US_PERSUBJECT;
      $JUM_AUG_JP+=$JUM_AUG_JP_PERSUBJECT;
      $JUM_AUG_RP+=$JUM_AUG_RP_PERSUBJECT;
      $JUM_AUG_SG+=$JUM_AUG_SG_PERSUBJECT;
      $JUM_SEP_US+=$JUM_SEP_US_PERSUBJECT;
      $JUM_SEP_JP+=$JUM_SEP_JP_PERSUBJECT;
      $JUM_SEP_RP+=$JUM_SEP_RP_PERSUBJECT;
      $JUM_SEP_SG+=$JUM_SEP_SG_PERSUBJECT;
      $JUM_OCT_US+=$JUM_OCT_US_PERSUBJECT;
      $JUM_OCT_JP+=$JUM_OCT_JP_PERSUBJECT;
      $JUM_OCT_RP+=$JUM_OCT_RP_PERSUBJECT;
      $JUM_OCT_SG+=$JUM_OCT_SG_PERSUBJECT;
      $JUM_NOV_US+=$JUM_NOV_US_PERSUBJECT;
      $JUM_NOV_JP+=$JUM_NOV_JP_PERSUBJECT;
      $JUM_NOV_RP+=$JUM_NOV_RP_PERSUBJECT;
      $JUM_NOV_SG+=$JUM_NOV_SG_PERSUBJECT;
      $JUM_DEC_US+=$JUM_DEC_US_PERSUBJECT;
      $JUM_DEC_JP+=$JUM_DEC_JP_PERSUBJECT;
      $JUM_DEC_RP+=$JUM_DEC_RP_PERSUBJECT;
      $JUM_DEC_SG+=$JUM_DEC_SG_PERSUBJECT;

      $no_jum=$no;    $no++;

      $JUM_TOT_US_PERSUBJECT=0;      $JUM_TOT_JP_PERSUBJECT=0;      $JUM_TOT_RP_PERSUBJECT=0;      $JUM_TOT_SG_PERSUBJECT=0;
      $JUM_JAN_US_PERSUBJECT=0;      $JUM_JAN_JP_PERSUBJECT=0;      $JUM_JAN_RP_PERSUBJECT=0;      $JUM_JAN_SG_PERSUBJECT=0;
      $JUM_FEB_US_PERSUBJECT=0;      $JUM_FEB_JP_PERSUBJECT=0;      $JUM_FEB_RP_PERSUBJECT=0;      $JUM_FEB_SG_PERSUBJECT=0;
      $JUM_MAR_US_PERSUBJECT=0;      $JUM_MAR_JP_PERSUBJECT=0;      $JUM_MAR_RP_PERSUBJECT=0;      $JUM_MAR_SG_PERSUBJECT=0;
      $JUM_APR_US_PERSUBJECT=0;      $JUM_APR_JP_PERSUBJECT=0;      $JUM_APR_RP_PERSUBJECT=0;      $JUM_APR_SG_PERSUBJECT=0;
      $JUM_MAY_US_PERSUBJECT=0;      $JUM_MAY_JP_PERSUBJECT=0;      $JUM_MAY_RP_PERSUBJECT=0;      $JUM_MAY_SG_PERSUBJECT=0;
      $JUM_JUN_US_PERSUBJECT=0;      $JUM_JUN_JP_PERSUBJECT=0;      $JUM_JUN_RP_PERSUBJECT=0;      $JUM_JUN_SG_PERSUBJECT=0;
      $JUM_JUL_US_PERSUBJECT=0;      $JUM_JUL_JP_PERSUBJECT=0;      $JUM_JUL_RP_PERSUBJECT=0;      $JUM_JUL_SG_PERSUBJECT=0;
      $JUM_AUG_US_PERSUBJECT=0;      $JUM_AUG_JP_PERSUBJECT=0;      $JUM_AUG_RP_PERSUBJECT=0;      $JUM_AUG_SG_PERSUBJECT=0;
      $JUM_SEP_US_PERSUBJECT=0;      $JUM_SEP_JP_PERSUBJECT=0;      $JUM_SEP_RP_PERSUBJECT=0;      $JUM_SEP_SG_PERSUBJECT=0;
      $JUM_OCT_US_PERSUBJECT=0;      $JUM_OCT_JP_PERSUBJECT=0;      $JUM_OCT_RP_PERSUBJECT=0;      $JUM_OCT_SG_PERSUBJECT=0;
      $JUM_NOV_US_PERSUBJECT=0;      $JUM_NOV_JP_PERSUBJECT=0;      $JUM_NOV_RP_PERSUBJECT=0;      $JUM_NOV_SG_PERSUBJECT=0;
      $JUM_DEC_US_PERSUBJECT=0;      $JUM_DEC_JP_PERSUBJECT=0;      $JUM_DEC_RP_PERSUBJECT=0;      $JUM_DEC_SG_PERSUBJECT=0;

      $JUM_TOT_US_PERSUBJECT+=$data_parts->TOTAL_US;
      $JUM_TOT_JP_PERSUBJECT+=$data_parts->TOTAL_JP;
      $JUM_TOT_RP_PERSUBJECT+=$data_parts->TOTAL_RP;
      $JUM_TOT_SG_PERSUBJECT+=$data_parts->TOTAL_SG;

      $JUM_JAN_US_PERSUBJECT+=$data_parts->JAN_US;
      $JUM_JAN_JP_PERSUBJECT+=$data_parts->JAN_JP;
      $JUM_JAN_RP_PERSUBJECT+=$data_parts->JAN_RP;
      $JUM_JAN_SG_PERSUBJECT+=$data_parts->JAN_SG;
      $JUM_FEB_US_PERSUBJECT+=$data_parts->FEB_US;
      $JUM_FEB_JP_PERSUBJECT+=$data_parts->FEB_JP;
      $JUM_FEB_RP_PERSUBJECT+=$data_parts->FEB_RP;
      $JUM_FEB_SG_PERSUBJECT+=$data_parts->FEB_SG;
      $JUM_MAR_US_PERSUBJECT+=$data_parts->MAR_US;
      $JUM_MAR_JP_PERSUBJECT+=$data_parts->MAR_JP;
      $JUM_MAR_RP_PERSUBJECT+=$data_parts->MAR_RP;
      $JUM_MAR_SG_PERSUBJECT+=$data_parts->MAR_SG;
      $JUM_APR_US_PERSUBJECT+=$data_parts->APR_US;
      $JUM_APR_JP_PERSUBJECT+=$data_parts->APR_JP;
      $JUM_APR_RP_PERSUBJECT+=$data_parts->APR_RP;
      $JUM_APR_SG_PERSUBJECT+=$data_parts->APR_SG;
      $JUM_MAY_US_PERSUBJECT+=$data_parts->MAY_US;
      $JUM_MAY_JP_PERSUBJECT+=$data_parts->MAY_JP;
      $JUM_MAY_RP_PERSUBJECT+=$data_parts->MAY_RP;
      $JUM_MAY_SG_PERSUBJECT+=$data_parts->MAY_SG;
      $JUM_JUN_US_PERSUBJECT+=$data_parts->JUN_US;
      $JUM_JUN_JP_PERSUBJECT+=$data_parts->JUN_JP;
      $JUM_JUN_RP_PERSUBJECT+=$data_parts->JUN_RP;
      $JUM_JUN_SG_PERSUBJECT+=$data_parts->JUN_SG;
      $JUM_JUL_US_PERSUBJECT+=$data_parts->JUL_US;
      $JUM_JUL_JP_PERSUBJECT+=$data_parts->JUL_JP;
      $JUM_JUL_RP_PERSUBJECT+=$data_parts->JUL_RP;
      $JUM_JUL_SG_PERSUBJECT+=$data_parts->JUL_SG;
      $JUM_AUG_US_PERSUBJECT+=$data_parts->AUG_US;
      $JUM_AUG_JP_PERSUBJECT+=$data_parts->AUG_JP;
      $JUM_AUG_RP_PERSUBJECT+=$data_parts->AUG_RP;
      $JUM_AUG_SG_PERSUBJECT+=$data_parts->AUG_SG;
      $JUM_SEP_US_PERSUBJECT+=$data_parts->SEP_US;
      $JUM_SEP_JP_PERSUBJECT+=$data_parts->SEP_JP;
      $JUM_SEP_RP_PERSUBJECT+=$data_parts->SEP_RP;
      $JUM_SEP_SG_PERSUBJECT+=$data_parts->SEP_SG;
      $JUM_OCT_US_PERSUBJECT+=$data_parts->OCT_US;
      $JUM_OCT_JP_PERSUBJECT+=$data_parts->OCT_JP;
      $JUM_OCT_RP_PERSUBJECT+=$data_parts->OCT_RP;
      $JUM_OCT_SG_PERSUBJECT+=$data_parts->OCT_SG;
      $JUM_NOV_US_PERSUBJECT+=$data_parts->NOV_US;
      $JUM_NOV_JP_PERSUBJECT+=$data_parts->NOV_JP;
      $JUM_NOV_RP_PERSUBJECT+=$data_parts->NOV_RP;
      $JUM_NOV_SG_PERSUBJECT+=$data_parts->NOV_SG;
      $JUM_DEC_US_PERSUBJECT+=$data_parts->DEC_US;
      $JUM_DEC_JP_PERSUBJECT+=$data_parts->DEC_JP;
      $JUM_DEC_RP_PERSUBJECT+=$data_parts->DEC_RP;
      $JUM_DEC_SG_PERSUBJECT+=$data_parts->DEC_SG;
    }
    $subject = $subjectNya;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data_parts->SUPPLIER_CODE.'-'.$data_parts->NAMA)
                ->setCellValue('C'.$no, $data_parts->TOTAL_US)
                ->setCellValue('D'.$no, $data_parts->TOTAL_JP)
                ->setCellValue('E'.$no, $data_parts->TOTAL_RP)
                ->setCellValue('F'.$no, $data_parts->TOTAL_SG)
                ->setCellValue('G'.$no, $data_parts->JAN_US)
                ->setCellValue('H'.$no, $data_parts->JAN_JP)
                ->setCellValue('I'.$no, $data_parts->JAN_RP)
                ->setCellValue('J'.$no, $data_parts->JAN_SG)
                ->setCellValue('K'.$no, $data_parts->FEB_US)
                ->setCellValue('L'.$no, $data_parts->FEB_JP)
                ->setCellValue('M'.$no, $data_parts->FEB_RP)
                ->setCellValue('N'.$no, $data_parts->FEB_SG)
                ->setCellValue('O'.$no, $data_parts->MAR_US)
                ->setCellValue('P'.$no, $data_parts->MAR_JP)
                ->setCellValue('Q'.$no, $data_parts->MAR_RP)
                ->setCellValue('R'.$no, $data_parts->MAR_SG)
                ->setCellValue('S'.$no, $data_parts->APR_US)
                ->setCellValue('T'.$no, $data_parts->APR_JP)
                ->setCellValue('U'.$no, $data_parts->APR_RP)
                ->setCellValue('V'.$no, $data_parts->APR_SG)
                ->setCellValue('W'.$no, $data_parts->MAY_US)
                ->setCellValue('X'.$no, $data_parts->MAY_JP)
                ->setCellValue('Y'.$no, $data_parts->MAY_RP)
                ->setCellValue('Z'.$no, $data_parts->MAY_SG)
                ->setCellValue('AA'.$no, $data_parts->JUN_US)
                ->setCellValue('AB'.$no, $data_parts->JUN_JP)
                ->setCellValue('AC'.$no, $data_parts->JUN_RP)
                ->setCellValue('AD'.$no, $data_parts->JUN_SG)
                ->setCellValue('AE'.$no, $data_parts->JUL_US)
                ->setCellValue('AF'.$no, $data_parts->JUL_JP)
                ->setCellValue('AG'.$no, $data_parts->JUL_RP)
                ->setCellValue('AH'.$no, $data_parts->JUL_SG)
                ->setCellValue('AI'.$no, $data_parts->AUG_US)
                ->setCellValue('AJ'.$no, $data_parts->AUG_JP)
                ->setCellValue('AK'.$no, $data_parts->AUG_RP)
                ->setCellValue('AL'.$no, $data_parts->AUG_SG)
                ->setCellValue('AM'.$no, $data_parts->SEP_US)
                ->setCellValue('AN'.$no, $data_parts->SEP_JP)
                ->setCellValue('AO'.$no, $data_parts->SEP_RP)
                ->setCellValue('AP'.$no, $data_parts->SEP_SG)
                ->setCellValue('AQ'.$no, $data_parts->OCT_US)
                ->setCellValue('AR'.$no, $data_parts->OCT_JP)
                ->setCellValue('AS'.$no, $data_parts->OCT_RP)
                ->setCellValue('AT'.$no, $data_parts->OCT_SG)
                ->setCellValue('AU'.$no, $data_parts->NOV_US)
                ->setCellValue('AV'.$no, $data_parts->NOV_JP)
                ->setCellValue('AW'.$no, $data_parts->NOV_RP)
                ->setCellValue('AX'.$no, $data_parts->NOV_SG)
                ->setCellValue('AY'.$no, $data_parts->DEC_US)
                ->setCellValue('AZ'.$no, $data_parts->DEC_JP)
                ->setCellValue('BA'.$no, $data_parts->DEC_RP)
                ->setCellValue('BB'.$no, $data_parts->DEC_SG);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A'.$no.':BB'.$no)->applyFromArray(
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
    $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':BB'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  }

  $no++;
  $noUrut++;
}

//END PARTS

//TOTAL START
if ($cmbR == 1){
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$no_jum, strtoupper($subject))
              ->setCellValue('C'.$no_jum, $JUM_USD_PERSUBJECT)
              ->setCellValue('D'.$no_jum, $JUM_YEN_PERSUBJECT)
              ->setCellValue('E'.$no_jum, $JUM_SGD_PERSUBJECT)
              ->setCellValue('F'.$no_jum, $JUM_IDR_PERSUBJECT);
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no_jum.':B'.$no_jum);

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A'.$no_jum)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => '00AAFF')
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

  $sheet->getStyle('A'.$no_jum.':G'.$no_jum)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => '00AAFF')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          )
      )
  );

  cellColor('A'.$no_jum.':G'.$no_jum, '00AAFF');
  $sheet->getStyle('A'.$no_jum)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A'.$no_jum.':G'.$no_jum)->getFont()->setBold(true)->setSize(12);
  $objPHPExcel->getActiveSheet()->getStyle('C'.$no_jum.':F'.$no_jum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

  $JUM_USD += $JUM_USD_PERSUBJECT;
  $JUM_YEN += $JUM_YEN_PERSUBJECT;
  $JUM_SGD += $JUM_SGD_PERSUBJECT;
  $JUM_IDR += $JUM_IDR_PERSUBJECT;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$no, 'TOTAL')
              ->setCellValue('C'.$no, $JUM_USD)
              ->setCellValue('D'.$no, $JUM_YEN)
              ->setCellValue('E'.$no, $JUM_SGD)
              ->setCellValue('F'.$no, $JUM_IDR);
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no.':B'.$no);

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A'.$no)->applyFromArray(
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

  $sheet->getStyle('A'.$no.':G'.$no)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => 'B4B4B4')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          )
      )
  );

  cellColor('A'.$no.':G'.$no, 'B4B4B4');
  $sheet->getStyle('A'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A'.$no.':G'.$no)->getFont()->setBold(true)->setSize(12);
  $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':F'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
}elseif ($cmbR == 2){
  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$no_jum, strtoupper($subject))
              ->setCellValue('C'.$no_jum, $JUM_TOT_US_PERSUBJECT)
              ->setCellValue('D'.$no_jum, $JUM_TOT_JP_PERSUBJECT)
              ->setCellValue('E'.$no_jum, $JUM_TOT_RP_PERSUBJECT)
              ->setCellValue('F'.$no_jum, $JUM_TOT_SG_PERSUBJECT)
              ->setCellValue('G'.$no_jum, $JUM_JAN_US_PERSUBJECT)
              ->setCellValue('H'.$no_jum, $JUM_JAN_JP_PERSUBJECT)
              ->setCellValue('I'.$no_jum, $JUM_JAN_RP_PERSUBJECT)
              ->setCellValue('J'.$no_jum, $JUM_JAN_SG_PERSUBJECT)
              ->setCellValue('K'.$no_jum, $JUM_FEB_US_PERSUBJECT)
              ->setCellValue('L'.$no_jum, $JUM_FEB_JP_PERSUBJECT)
              ->setCellValue('M'.$no_jum, $JUM_FEB_RP_PERSUBJECT)
              ->setCellValue('N'.$no_jum, $JUM_FEB_SG_PERSUBJECT)
              ->setCellValue('O'.$no_jum, $JUM_MAR_US_PERSUBJECT)
              ->setCellValue('P'.$no_jum, $JUM_MAR_JP_PERSUBJECT)
              ->setCellValue('Q'.$no_jum, $JUM_MAR_RP_PERSUBJECT)
              ->setCellValue('R'.$no_jum, $JUM_MAR_SG_PERSUBJECT)
              ->setCellValue('S'.$no_jum, $JUM_APR_US_PERSUBJECT)
              ->setCellValue('T'.$no_jum, $JUM_APR_JP_PERSUBJECT)
              ->setCellValue('U'.$no_jum, $JUM_APR_RP_PERSUBJECT)
              ->setCellValue('V'.$no_jum, $JUM_APR_SG_PERSUBJECT)
              ->setCellValue('W'.$no_jum, $JUM_MAY_US_PERSUBJECT)
              ->setCellValue('X'.$no_jum, $JUM_MAY_JP_PERSUBJECT)
              ->setCellValue('Y'.$no_jum, $JUM_MAY_RP_PERSUBJECT)
              ->setCellValue('Z'.$no_jum, $JUM_MAY_SG_PERSUBJECT)
              ->setCellValue('AA'.$no_jum, $JUM_JUN_US_PERSUBJECT)
              ->setCellValue('AB'.$no_jum, $JUM_JUN_JP_PERSUBJECT)
              ->setCellValue('AC'.$no_jum, $JUM_JUN_RP_PERSUBJECT)
              ->setCellValue('AD'.$no_jum, $JUM_JUN_SG_PERSUBJECT)
              ->setCellValue('AE'.$no_jum, $JUM_JUL_US_PERSUBJECT)
              ->setCellValue('AF'.$no_jum, $JUM_JUL_JP_PERSUBJECT)
              ->setCellValue('AG'.$no_jum, $JUM_JUL_RP_PERSUBJECT)
              ->setCellValue('AH'.$no_jum, $JUM_JUL_SG_PERSUBJECT)
              ->setCellValue('AI'.$no_jum, $JUM_AUG_US_PERSUBJECT)
              ->setCellValue('AJ'.$no_jum, $JUM_AUG_JP_PERSUBJECT)
              ->setCellValue('AK'.$no_jum, $JUM_AUG_RP_PERSUBJECT)
              ->setCellValue('AL'.$no_jum, $JUM_AUG_SG_PERSUBJECT)
              ->setCellValue('AM'.$no_jum, $JUM_SEP_US_PERSUBJECT)
              ->setCellValue('AN'.$no_jum, $JUM_SEP_JP_PERSUBJECT)
              ->setCellValue('AO'.$no_jum, $JUM_SEP_RP_PERSUBJECT)
              ->setCellValue('AP'.$no_jum, $JUM_SEP_SG_PERSUBJECT)
              ->setCellValue('AQ'.$no_jum, $JUM_OCT_US_PERSUBJECT)
              ->setCellValue('AR'.$no_jum, $JUM_OCT_JP_PERSUBJECT)
              ->setCellValue('AS'.$no_jum, $JUM_OCT_RP_PERSUBJECT)
              ->setCellValue('AT'.$no_jum, $JUM_OCT_SG_PERSUBJECT)
              ->setCellValue('AU'.$no_jum, $JUM_NOV_US_PERSUBJECT)
              ->setCellValue('AV'.$no_jum, $JUM_NOV_JP_PERSUBJECT)
              ->setCellValue('AW'.$no_jum, $JUM_NOV_RP_PERSUBJECT)
              ->setCellValue('AX'.$no_jum, $JUM_NOV_SG_PERSUBJECT)
              ->setCellValue('AY'.$no_jum, $JUM_DEC_US_PERSUBJECT)
              ->setCellValue('AZ'.$no_jum, $JUM_DEC_JP_PERSUBJECT)
              ->setCellValue('BA'.$no_jum, $JUM_DEC_RP_PERSUBJECT)
              ->setCellValue('BB'.$no_jum, $JUM_DEC_SG_PERSUBJECT);

  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no_jum.':B'.$no_jum);

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A'.$no_jum)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => '00AAFF')
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

  $sheet->getStyle('C'.$no_jum.':BB'.$no_jum)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => '00AAFF')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          )
      )
  );

  cellColor('A'.$no_jum.':BB'.$no_jum, '00AAFF');
  $sheet->getStyle('A'.$no_jum)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A'.$no_jum.':BB'.$no_jum)->getFont()->setBold(true)->setSize(12);
  $objPHPExcel->getActiveSheet()->getStyle('C'.$no_jum.':BB'.$no_jum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

  $JUM_TOTAL_US+=$JUM_TOT_US_PERSUBJECT;
  $JUM_TOTAL_JP+=$JUM_TOT_JP_PERSUBJECT;
  $JUM_TOTAL_RP+=$JUM_TOT_RP_PERSUBJECT;
  $JUM_TOTAL_SG+=$JUM_TOT_SG_PERSUBJECT;

  $JUM_JAN_US+=$JUM_JAN_US_PERSUBJECT;
  $JUM_JAN_JP+=$JUM_JAN_JP_PERSUBJECT;
  $JUM_JAN_RP+=$JUM_JAN_RP_PERSUBJECT;
  $JUM_JAN_SG+=$JUM_JAN_SG_PERSUBJECT;
  $JUM_FEB_US+=$JUM_FEB_US_PERSUBJECT;
  $JUM_FEB_JP+=$JUM_FEB_JP_PERSUBJECT;
  $JUM_FEB_RP+=$JUM_FEB_RP_PERSUBJECT;
  $JUM_FEB_SG+=$JUM_FEB_SG_PERSUBJECT;
  $JUM_MAR_US+=$JUM_MAR_US_PERSUBJECT;
  $JUM_MAR_JP+=$JUM_MAR_JP_PERSUBJECT;
  $JUM_MAR_RP+=$JUM_MAR_RP_PERSUBJECT;
  $JUM_MAR_SG+=$JUM_MAR_SG_PERSUBJECT;
  $JUM_APR_US+=$JUM_APR_US_PERSUBJECT;
  $JUM_APR_JP+=$JUM_APR_JP_PERSUBJECT;
  $JUM_APR_RP+=$JUM_APR_RP_PERSUBJECT;
  $JUM_APR_SG+=$JUM_APR_SG_PERSUBJECT;
  $JUM_MAY_US+=$JUM_MAY_US_PERSUBJECT;
  $JUM_MAY_JP+=$JUM_MAY_JP_PERSUBJECT;
  $JUM_MAY_RP+=$JUM_MAY_RP_PERSUBJECT;
  $JUM_MAY_SG+=$JUM_MAY_SG_PERSUBJECT;
  $JUM_JUN_US+=$JUM_JUN_US_PERSUBJECT;
  $JUM_JUN_JP+=$JUM_JUN_JP_PERSUBJECT;
  $JUM_JUN_RP+=$JUM_JUN_RP_PERSUBJECT;
  $JUM_JUN_SG+=$JUM_JUN_SG_PERSUBJECT;
  $JUM_JUL_US+=$JUM_JUL_US_PERSUBJECT;
  $JUM_JUL_JP+=$JUM_JUL_JP_PERSUBJECT;
  $JUM_JUL_RP+=$JUM_JUL_RP_PERSUBJECT;
  $JUM_JUL_SG+=$JUM_JUL_SG_PERSUBJECT;
  $JUM_AUG_US+=$JUM_AUG_US_PERSUBJECT;
  $JUM_AUG_JP+=$JUM_AUG_JP_PERSUBJECT;
  $JUM_AUG_RP+=$JUM_AUG_RP_PERSUBJECT;
  $JUM_AUG_SG+=$JUM_AUG_SG_PERSUBJECT;
  $JUM_SEP_US+=$JUM_SEP_US_PERSUBJECT;
  $JUM_SEP_JP+=$JUM_SEP_JP_PERSUBJECT;
  $JUM_SEP_RP+=$JUM_SEP_RP_PERSUBJECT;
  $JUM_SEP_SG+=$JUM_SEP_SG_PERSUBJECT;
  $JUM_OCT_US+=$JUM_OCT_US_PERSUBJECT;
  $JUM_OCT_JP+=$JUM_OCT_JP_PERSUBJECT;
  $JUM_OCT_RP+=$JUM_OCT_RP_PERSUBJECT;
  $JUM_OCT_SG+=$JUM_OCT_SG_PERSUBJECT;
  $JUM_NOV_US+=$JUM_NOV_US_PERSUBJECT;
  $JUM_NOV_JP+=$JUM_NOV_JP_PERSUBJECT;
  $JUM_NOV_RP+=$JUM_NOV_RP_PERSUBJECT;
  $JUM_NOV_SG+=$JUM_NOV_SG_PERSUBJECT;
  $JUM_DEC_US+=$JUM_DEC_US_PERSUBJECT;
  $JUM_DEC_JP+=$JUM_DEC_JP_PERSUBJECT;
  $JUM_DEC_RP+=$JUM_DEC_RP_PERSUBJECT;
  $JUM_DEC_SG+=$JUM_DEC_SG_PERSUBJECT;

  $objPHPExcel->setActiveSheetIndex(0)
              ->setCellValue('A'.$no, 'TOTAL')
              ->setCellValue('C'.$no, $JUM_TOTAL_US)
              ->setCellValue('D'.$no, $JUM_TOTAL_JP)
              ->setCellValue('E'.$no, $JUM_TOTAL_RP)
              ->setCellValue('F'.$no, $JUM_TOTAL_SG)
              ->setCellValue('G'.$no, $JUM_JAN_US)
              ->setCellValue('H'.$no, $JUM_JAN_JP)
              ->setCellValue('I'.$no, $JUM_JAN_RP)
              ->setCellValue('J'.$no, $JUM_JAN_SG)
              ->setCellValue('K'.$no, $JUM_FEB_US)
              ->setCellValue('L'.$no, $JUM_FEB_JP)
              ->setCellValue('M'.$no, $JUM_FEB_RP)
              ->setCellValue('N'.$no, $JUM_FEB_SG)
              ->setCellValue('O'.$no, $JUM_MAR_US)
              ->setCellValue('P'.$no, $JUM_MAR_JP)
              ->setCellValue('Q'.$no, $JUM_MAR_RP)
              ->setCellValue('R'.$no, $JUM_MAR_SG)
              ->setCellValue('S'.$no, $JUM_APR_US)
              ->setCellValue('T'.$no, $JUM_APR_JP)
              ->setCellValue('U'.$no, $JUM_APR_RP)
              ->setCellValue('V'.$no, $JUM_APR_SG)
              ->setCellValue('W'.$no, $JUM_MAY_US)
              ->setCellValue('X'.$no, $JUM_MAY_JP)
              ->setCellValue('Y'.$no, $JUM_MAY_RP)
              ->setCellValue('Z'.$no, $JUM_MAY_SG)
              ->setCellValue('AA'.$no, $JUM_JUN_US)
              ->setCellValue('AB'.$no, $JUM_JUN_JP)
              ->setCellValue('AC'.$no, $JUM_JUN_RP)
              ->setCellValue('AD'.$no, $JUM_JUN_SG)
              ->setCellValue('AE'.$no, $JUM_JUL_US)
              ->setCellValue('AF'.$no, $JUM_JUL_JP)
              ->setCellValue('AG'.$no, $JUM_JUL_RP)
              ->setCellValue('AH'.$no, $JUM_JUL_SG)
              ->setCellValue('AI'.$no, $JUM_AUG_US)
              ->setCellValue('AJ'.$no, $JUM_AUG_JP)
              ->setCellValue('AK'.$no, $JUM_AUG_RP)
              ->setCellValue('AL'.$no, $JUM_AUG_SG)
              ->setCellValue('AM'.$no, $JUM_SEP_US)
              ->setCellValue('AN'.$no, $JUM_SEP_JP)
              ->setCellValue('AO'.$no, $JUM_SEP_RP)
              ->setCellValue('AP'.$no, $JUM_SEP_SG)
              ->setCellValue('AQ'.$no, $JUM_OCT_US)
              ->setCellValue('AR'.$no, $JUM_OCT_JP)
              ->setCellValue('AS'.$no, $JUM_OCT_RP)
              ->setCellValue('AT'.$no, $JUM_OCT_SG)
              ->setCellValue('AU'.$no, $JUM_NOV_US)
              ->setCellValue('AV'.$no, $JUM_NOV_JP)
              ->setCellValue('AW'.$no, $JUM_NOV_RP)
              ->setCellValue('AX'.$no, $JUM_NOV_SG)
              ->setCellValue('AY'.$no, $JUM_DEC_US)
              ->setCellValue('AZ'.$no, $JUM_DEC_JP)
              ->setCellValue('BA'.$no, $JUM_DEC_RP)
              ->setCellValue('BB'.$no, $JUM_DEC_SG);
  $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$no.':B'.$no);

  $sheet = $objPHPExcel->getActiveSheet();
  $sheet->getStyle('A'.$no)->applyFromArray(
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

  $sheet->getStyle('C'.$no.':BB'.$no)->applyFromArray(
      array(
          'fill' => array(
              'type' => PHPExcel_Style_Fill::FILL_SOLID,
              'color' => array('rgb' => 'B4B4B4')
          ),
          'borders' => array(
             'allborders' => array(
                 'style' => PHPExcel_Style_Border::BORDER_THIN
             )
          )
      )
  );

  cellColor('A'.$no.':BB'.$no, 'B4B4B4');
  $sheet->getStyle('A'.$no)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
  $objPHPExcel->getActiveSheet()->getStyle('A'.$no.':BB'.$no)->getFont()->setBold(true)->setSize(12);
  $objPHPExcel->getActiveSheet()->getStyle('C'.$no.':BB'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
}
//TOTAL END

$objPHPExcel->getActiveSheet()->setTitle('PURCHASE REPORT - '.date('Y-m-d'));
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="PURCHASE_'.date('Y/m/d').'.xlsx"');
$objWriter->save('php://output');
?>