<?php
require_once '../../class/phpexcel/PHPExcel.php';
$Arr_sheet = array('DATA','PACK TYPE','PACK GROUP');
$sql = "select item_no, item, description, standard_price from item order by item_no asc";
$data = sqlsrv_query($connect, strtoupper($sql));

$objPHPExcel = new PHPExcel();
/*---------------------- SHEETT-1 ------------------------------*/
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ITEM_NO')
            ->setCellValue('B1', 'DESCRIPTION')
            ->setCellValue('C1', 'STD PRICE');
$sheet = $objPHPExcel->getActiveSheet(0);
$sheet->getStyle('A1:C1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:C1')->getAlignment()->applyFromArray(
    arrayC
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:C1')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        )
    )
);

$sheet->getStyle('A1:C21')->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

foreach(range('A','C') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

$objPHPExcel->getSheet(0)->setTitle('DATA');
 
/*---------------------- SHEETT-2 ------------------------------*/
$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'PACK TYPE');
$objPHPExcel->addSheet($myWorkSheet, 1);
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('A1', 'KODE')
            ->setCellValue('B1', 'TYPE');
$sheet = $objPHPExcel->getActiveSheet(1);
$sheet->getStyle('A1:B1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:B1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:C1')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

$b = 2;
foreach ($Arr_pck as $key => $value) {
    $objPHPExcel->setActiveSheetIndex(1)
                ->setCellValue('A'.$b, $value['KODE'])
                ->setCellValue('B'.$b, $value['TYPE']);
    $sheet = $objPHPExcel->getActiveSheet(1);
    $sheet->getStyle('A'.$b.':B'.$b)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
    $b++;
}

foreach(range('A','B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

/*---------------------- SHEETT-3 ------------------------------*/
$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, 'PACK GROUP');
$objPHPExcel->addSheet($myWorkSheet, 2);
$objPHPExcel->setActiveSheetIndex(2)
            ->setCellValue('A1', 'KODE')
            ->setCellValue('B1', 'DESCRIPTION')
            ->setCellValue('C1', '2nd PROCESS')
            ->setCellValue('D1', '2nd MACHINE');
$sheet = $objPHPExcel->getActiveSheet(2);
$sheet->getStyle('A1:D1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:D1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:C1')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

$c = 2;
foreach ($Arr_grp as $key => $valueB) {
    if ( $valueB['PROCESS'] == "0"){
        $p = "";
    }else{
        $p = $valueB['PROCESS'];
    }

    if ( $valueB['MACH'] == "0"){
        $m = "";
    }else{
        $m = $valueB['MACH'];
    }

    $objPHPExcel->setActiveSheetIndex(2)
                ->setCellValue('A'.$c, $valueB['KODE'])
                ->setCellValue('B'.$c, $valueB['DESC'])
                ->setCellValue('C'.$c, $p);
    $sheet = $objPHPExcel->getActiveSheet(2);
    $sheet->getStyle('A'.$c.':C'.$c)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
    $c++;
}

foreach(range('A','C') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

/*---------------------- set sheet 1 active ------------------------------*/
$objPHPExcel->setActiveSheetIndex(0);

$dt = date('M').'-'.date('y');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Template Item Pack'.$dt.'.xls"');
$objWriter->save('php://output');
exit;
?>