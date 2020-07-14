<?php
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../connect/conn.php");

$tanggal = isset($_REQUEST['tanggal']) ? strval($_REQUEST['tanggal']) : '';




$sql = "BEGIN ZSP_VALIDATE_DATECODE(); end;";
$stmt = oci_parse($connect, $sql);
$res = oci_execute($stmt);

$qry = "select work_order,
       item_no,
       item_name,
       date_code,
       date_code_month,
       mps_Date,
       
       trunc(add_months(mps_date,-2),'MONTH') -1 lowes_date,
       trunc(add_months(mps_date,+2),'MONTH') +1 highes_date,
       case when date_code_month is null then 'Date Code Item Master Kosong'
       else case when mps_date is null then 'Belum Punya Tanggal Produksi'
       else 'Date Code tidak sesuai dengan tanggal produksi.' end
       end Ket
from 
(
        select  r.work_order,
                r.item_no,
                s.mps_date,
                r.item_name,
                date_code,
                i.date_code_month,
                add_months(to_date( case when length(substr(date_code,4,7)) < 3 then '20'||substr(date_code,4,7) else substr(date_code,4,7) end || '/' ||
                          substr(date_code, 0, 2) || '/' || '01 1:00:25', 'YYYY/MM/DD HH:MI:SS') , i.date_code_month * -1) Tanggal
        from mps_header r
        left outer join mps_details s
        on r.po_line_no = s.po_line_no and r.po_no = s.po_no
        inner join item i
        on r.item_no = i.item_no
        where date_code_type  in (
        '1',
        'MM-YYYY',
        'MM/YYYY',
        'MMAYY'
        ) and date_code is not null and i.date_code_month is not null --and work_order = '18FI056-LR1C-1'
) where  item_no not in ('15990',
                              '16000',
                              '17390',
                              '30070',
                              '31820',
                              '31830',
                              '31840',
                              '31850',
                              '31940',
                              '31950',
                              '31990',
                              '32000',
                              '32010',
                              '32130',
                              '43610',
                              '44280',
                              '44290',
                              '44340',
                              '44360',
                              '44440',
                              '44460',
                              '44480',
                              '54780',
                              '54790',
                              '57886',
                              '59000',
                              '59001',
                              '66470',
                              '68250',
                              '68260',
                              '68350',
                              '68360',
                              '68400',
                              '68450',
                              '68460',
                              '68470',
                              '68480',
                              '68500',
                              '71355',
                              '71356',
                              '76385',
                              '76386',
                              '77195',
                              '77196',
                              '84010',
                              '84011',
                              '84090',
                              '84091',
                              '84370',
                              '84371',
                              '84390',
                              '84391',
                              '85335',
                              '95340',
                              '86585',
                              '86575',
                              '95590',
                              '95630',
                              '96530',
                              '96535',
                              '73012220',
                              '73012230'
                              )  and 
  case when tanggal between trunc(add_months(mps_date,-2),'MONTH')-1 and trunc(add_months(mps_date, 2),'MONTH')+1 then 1 else 0 end =0
";
$result = oci_parse($connect, $qry);
oci_execute($result);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();
 
// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'WORK NO.')
            ->setCellValue('C1', 'ITEM NO.')
            ->setCellValue('D1', 'ITEM NAME')
            ->setCellValue('E1', 'DATE CODE')
            ->setCellValue('F1', 'DATE MONTH')
            ->setCellValue('G1', 'PRODUCTION DATE')
            ->setCellValue('H1', 'HIGHEST ALLOWANCE DATE')
            ->setCellValue('I1', 'LOWEST ALLOWANCE DATE')
            ->setCellValue('J1', 'REMARK');

$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->applyFromArray(array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'startcolor' => array(
         'rgb' => 'D2D2D2'
    )
));

$sheet->getStyle('A1:J1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true)->setSize(12);

$noUrut = 1;    
$no=2;

while ($data=oci_fetch_array($result)){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data[0])
                ->setCellValue('C'.$no, $data[1])
                ->setCellValue('D'.$no, $data[2])
                ->setCellValue('E'.$no, $data[3])
                ->setCellValue('F'.$no, $data[4])
                ->setCellValue('G'.$no, $data[5])
                ->setCellValue('H'.$no, $data[6])
                ->setCellValue('I'.$no, $data[7])
                ->setCellValue('J'.$no, $data[8]);

    $sheet = $objPHPExcel->getActiveSheet();
    $no++;
    $noUrut++;
}   

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('MPS DATE CHECK '.$tanggal);
 
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
header('Content-Disposition: attachment; filename="MPS BLank Date Code  - '.$tanggal.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');

// $sql = "BEGIN ZSP_VALIDATE_DATECODE(); end;";
// $stmt = oci_parse($connect, $sql);
// $res = oci_execute($stmt);
?>