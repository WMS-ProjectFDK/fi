<?php
require_once '../../class/phpexcel/PHPExcel.php';
$Arr_sheet = array('DATA','PACK TYPE','PACK GROUP');

$data_pck = file_get_contents('item_pack_master_json_pck.json');
$dt_pck = json_decode(json_encode($data_pck));
$str_pck = preg_replace('/\\\\\"/',"\"", $dt_pck);
$Arr_pck = json_decode($str_pck,true);

$data_grp = file_get_contents('item_pack_master_json_grp.json');
$dt_grp = json_decode(json_encode($data_grp));
$str_grp = preg_replace('/\\\\\"/',"\"", $dt_grp);
$Arr_grp = json_decode($str_grp,true);

$objPHPExcel = new PHPExcel();

/*---------------------- SHEETT-1 ------------------------------*/
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO.')
            ->setCellValue('B1', 'ITEM NO.')
            ->setCellValue('C1', 'OPERATION TIME')
            ->setCellValue('D1', 'MAN POWER')
            ->setCellValue('E1', 'CAPACITY')
            ->setCellValue('F1', 'PACK TYPE')
            ->setCellValue('G1', 'PACK GROUP')
            ->setCellValue('H1', '2nd PROCESS')
            ->setCellValue('I1', '2nd MACHINE')
            ->setCellValue('J1', '1st PROCESS OT')
            ->setCellValue('K1', '2nd PROCESS OT');
$sheet = $objPHPExcel->getActiveSheet(0);
$sheet->getStyle('A1:K1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
$sheet->getStyle('A1:K1')->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A1:K1')->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        )
    )
);

$sheet->getStyle('A1:K21')->applyFromArray(
    array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    )
);

foreach(range('A','P') as $columnID) {
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

$sheet->getStyle('A1:B1')->applyFromArray(
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

$sheet->getStyle('A1:D1')->applyFromArray(
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
                ->setCellValue('C'.$c, $p)
                ->setCellValue('D'.$c, $m);
    $sheet = $objPHPExcel->getActiveSheet(2);
    $sheet->getStyle('A'.$c.':D'.$c)->applyFromArray(
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

foreach(range('A','D') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

/*---------------------- set sheet 1 active ------------------------------*/
$objPHPExcel->setActiveSheetIndex(0);

// formula
// for ($row = 2; $row < 22; $row++) {
//     $objPHPExcel->getActiveSheet()
//         ->setCellValue(
//             "M" . $row, "=VLOOKUP(F2;'PACK TYPE'!$A$2:$B$50;2;FALSE)"
//             "N" . $row, "=VLOOKUP(G2;'PACK GROUP'!$A$2:$D$250;2;FALSE)"
//             "O" . $row, "=VLOOKUP(G2;'PACK GROUP'!$A$2:$D$250;3;FALSE)"
//             "P" . $row, "=VLOOKUP(G2;'PACK GROUP'!$A$2:$D$250;4;FALSE)"
//     );
// }

$dt = date('M').'-'.date('y');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="Template Item Pack'.$dt.'.xls"');
$objWriter->save('php://output');
exit;
?>