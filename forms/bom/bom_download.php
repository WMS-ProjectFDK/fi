<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
include("../../connect/conn.php");
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

// Get the contents of the JSON file
$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$queries = json_decode($str);

$jum_data = count($queries);
// echo $jum_data."<br/>";
$search = '';
$row_no = 1;
foreach($queries as $query){
    $upper_item_no = $query->upper_item_no;
    $quantity = $query->quantity;
    if($jum_data == $row_no){
        $search .= "'".$upper_item_no.$quantity."'";
    }else{
        $search .= "'".$upper_item_no.$quantity."',";
    }
    // echo $jum_data."~".$row_no."<br/>";
    $row_no++;
}

// echo $row_no."<br/>";

$SQL = "select s.upper_item_no, i1.item upper_item, i1.description upper_description, i1.drawing_no  drawing_no, i1.drawing_rev drawing_rev,
    i1.applicable_model applicable_model ,i1.catalog_no catalog_no, u1.unit, s.lower_item_no, i2.item lower_item, 
    i2.description rower_description , s.level_no, s.line_no, s.reference_number, s.quantity, u2.unit, s.quantity_base, 
    isnull(s.failure_rate,0) failure_rate
    from structure s, item i1, item i2, unit u1, unit u2
    where s.upper_item_no = i1.item_no 
    and s.lower_item_no = i2.item_no 
    and i1.unit_stock = u1.unit_code
    and i2.unit_engineering = u2.unit_code
    and  cast(s.upper_item_no AS VARCHAR(MAX))+CAST(LEVEL_NO AS CHAR(2)) IN (".$search.")
    order by s.upper_item_no ASC, s.level_no ASC, CAST(S.LINE_NO AS INT) ASC, s.reference_number";
// echo $SQL;
$data_sql = sqlsrv_query($connect, $SQL);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

$noRow = 1;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'UPPER ITEM NO')
            ->setCellValue('B'.$noRow, 'UPPER ITEM')
            ->setCellValue('C'.$noRow, 'UPPER DESCRIPTION')
            ->setCellValue('D'.$noRow, 'DRAWING NO')
            ->setCellValue('E'.$noRow, 'DRAWING REV')
            ->setCellValue('F'.$noRow, 'APPLICABLE MODEL')
            ->setCellValue('G'.$noRow, 'CATALOG NO')
            ->setCellValue('H'.$noRow, 'UNIT')
            ->setCellValue('I'.$noRow, 'LOWER ITEM NO')
            ->setCellValue('J'.$noRow, 'LOWER ITEM')
            ->setCellValue('K'.$noRow, 'ROWER DESCRIPTION')
            ->setCellValue('L'.$noRow, 'LEVEL NO')
            ->setCellValue('M'.$noRow, 'LINE NO')
            ->setCellValue('N'.$noRow, 'REFERENCE NUMBER')
            ->setCellValue('O'.$noRow, 'QUANTITY')
            ->setCellValue('P'.$noRow, 'UNIT')
            ->setCellValue('Q'.$noRow, 'QUANTITY BASE')
            ->setCellValue('R'.$noRow, 'FAILURE RATE');

foreach(range('A','R') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getStyle('A'.$noRow.':R'.$noRow)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);
$sheet->getStyle('A'.$noRow.':R'.$noRow)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '4D648D')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'font'  => array(
            'bold'  => true,
            'size'  => 12,
            'color' => array('rgb' => 'FFFFFF'),
        )
    )
);
$noRow++;

$color1 = '';
while ($data=sqlsrv_fetch_array($data_sql)) {
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$noRow, $data[0])
                ->setCellValue('B'.$noRow, $data[1])
                ->setCellValue('C'.$noRow, $data[2])
                ->setCellValue('D'.$noRow, $data[3])
                ->setCellValue('E'.$noRow, $data[4])
                ->setCellValue('F'.$noRow, $data[5])
                ->setCellValue('G'.$noRow, $data[6])
                ->setCellValue('H'.$noRow, $data[7])
                ->setCellValue('I'.$noRow, $data[8])
                ->setCellValue('J'.$noRow, $data[9])
                ->setCellValue('K'.$noRow, $data[10])
                ->setCellValue('L'.$noRow, $data[11])
                ->setCellValue('M'.$noRow, $data[12])
                ->setCellValue('N'.$noRow, $data[13])
                ->setCellValue('O'.$noRow, $data[14])
                ->setCellValue('P'.$noRow, $data[15])
                ->setCellValue('Q'.$noRow, $data[16])
                ->setCellValue('R'.$noRow, $data[17]);
    
    if($noRow % 2 == 0){
        $sheet->getStyle('A'.$noRow.':R'.$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D0E1F9')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => FALSE,
                    'size'  => 11
                )
            )
        );
    }else{
        $sheet->getStyle('A'.$noRow.':R'.$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F4F4F8')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => FALSE,
                    'size'  => 11
                )
            )
        );
    }

    $noRow++;
}

$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="bom.xlsx"');
$objWriter->save('php://output');
?>