<?php
ini_set('memory_limit', '-1');
include("../connect/conn.php");
require_once '../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$si = isset($_REQUEST['si']) ? strval($_REQUEST['si']) : '';
$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
$do = isset($_REQUEST['do']) ? strval($_REQUEST['do']) : '';

$q_si = "select distinct customer_po_no as po_no from answer where crs_remark='$ppbe' OR si_no='$si'";
$data = oci_parse($connect, $q_si);
oci_execute($data);
$dt = oci_fetch_object($data);

$qry = "select rtrim(replace(marks,chr(10),'\n'),'|') as pallet_mark from do_marks where do_no='$do' order by mark_no asc";
$result = oci_parse($connect, $qry);
oci_execute($result);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$sheet->getColumnDimension('D')->setWidth('5');
$sheet->getColumnDimension('E')->setWidth('5');
$sheet->getColumnDimension('I')->setWidth('5');
$sheet->getColumnDimension('J')->setWidth('5');

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', "ATTACHMENT OF SI NO. ".$dt->PO_NO);
$objPHPExcel->setActiveSheetIndex()->mergeCells('A4:M4');
$sheet->getStyle('A4')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A4')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A4')->applyFromArray(
    array(
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

//$row_a=0;   $col_a=0;   $page_next=0;
$nobaris=1; $noCell=6;
while ($data=oci_fetch_object($result)){
    if ($nobaris == 1){
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$noCell, $data->PALLET_MARK);
        $objPHPExcel->getActiveSheet()->getStyle('A'.$noCell)->getAlignment()->setWrapText(true);
        $mCell = $noCell+6;
        $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noCell.':C'.$mCell);
        $sheet->getStyle('A'.$noCell)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
            )
        );
        $sheet->getStyle('A'.$noCell.':C'.$mCell)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F4F4F4')
                )
            )
        );
        $nobaris++;    
    }else{
        if($nobaris%3 != 0){
          $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F'.$noCell, $data->PALLET_MARK);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$noCell)->getAlignment()->setWrapText(true);
            $mCell = $noCell+6;
            $objPHPExcel->setActiveSheetIndex()->mergeCells('F'.$noCell.':H'.$mCell);
            $sheet->getStyle('F'.$noCell)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
                )
            );
            $sheet->getStyle('F'.$noCell.':H'.$mCell)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F4F4F4')
                    )
                )
            );
            $nobaris++;  
        }else{
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('K'.$noCell, $data->PALLET_MARK);
            $objPHPExcel->getActiveSheet()->getStyle('K'.$noCell)->getAlignment()->setWrapText(true);
            $mCell = $noCell+6;
            $objPHPExcel->setActiveSheetIndex()->mergeCells('K'.$noCell.':M'.$mCell);
            $sheet->getStyle('K'.$noCell)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
                )
            );
            $sheet->getStyle('K'.$noCell.':M'.$mCell)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F4F4F4')
                    )
                )
            );
            $nobaris=1;     $noCell=$mCell+2;
        }
    }
}

$objPHPExcel->setActiveSheetIndex(0);
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('WMS-FDKI');
$objDrawing->setDescription('FDKI');
$objDrawing->setPath('../images/logo-print4.png');
$objDrawing->setWidth('500px');
$objDrawing->setCoordinates('A1');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="attachment_pallet_marks'.$do.'.xlsx"');
$objWriter->save('php://output');
?>