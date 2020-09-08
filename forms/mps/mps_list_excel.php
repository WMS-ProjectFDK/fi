<?php
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

$tanggal = isset($_REQUEST['tanggal']) ? strval($_REQUEST['tanggal']) : '';

$sql = "call {ZSP_VALIDATE_DATECODE}";
$stmt = sqlsrv_query($connect, $sql);

$qry = "select work_order, item_no, item_name, date_code, date_code_month, CAST(mps_date as varchar(10)) as mps_date,
    CAST(dateadd(M,-3, mps_date) as varchar(10)) as lowes_date,
    CAST(dateadd(M,3, mps_date) as varchar(10)) as highes_date,
    case when date_code_month is null then 'Date Code Item Master Kosong'
    else case when mps_date is null then 'Belum Punya Tanggal Produksi'
        else 'Date Code tidak sesuai dengan tanggal produksi.' end
    end Ket
    from 
    (
    select  r.work_order, r.item_no,s.mps_date, r.item_name, date_code, i.date_code_month,
    dateadd(MONTH,
        i.DATE_CODE_MONTH*-1,
        cast(case when len(substring(r.date_code,4,7)) < 3 then '20' + substring(r.date_code,4,7) 
            else substring(r.date_code,4,7) end  
            + '/' +
            substring(r.date_code, 1, 2) 
            + '/' + '01' 
        as date)
    ) Tanggal
    from mps_header r
    left outer join mps_details s on r.po_line_no = s.po_line_no and r.po_no = s.po_no
    inner join item i on r.item_no = i.item_no
    where date_code_type  in ('1','MM-YYYY','MM/YYYY','MMAYY') 
    and r.date_code is not null AND r.date_code != '' and i.date_code_month is not null
    ) aa
    where aa.item_no not in ('15990','16000','17390','30070','31820','31830','31840','31850','31940','31950',
                            '31990','32000','32010','32130','43610','44280','44290','44340','44360','44440',
                            '44460','44480','54780','54790','57886','59000','59001','66470','68250','68260',
                            '68350','68360','68400','68450','68460','68470','68480','68500','71355','71356',
                            '76385','76386','77195','77196','84010','84011','84090','84091','84370','84371',
                            '84390','84391','85335','95340','86585','86575','95590','95630','96530','96535',
                            '73012220','73012230')  
    and case when tanggal between DATEADD(MONTH,-3,mps_date) and DATEADD(MONTH,3,mps_date) then 1 else 0 end=0";
$result = sqlsrv_query($connect, strtoupper($qry));

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

while ($data=sqlsrv_fetch_array($result)){
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

// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="MPS BLank Date Code  - '.$tanggal.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>