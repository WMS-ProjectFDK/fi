<?php
    error_reporting(0);
	include("../../connect/conn_accpac.php");
	// header("Content-type: application/json");
	ini_set('memory_limit', '-1');
	require_once '../../class/phpexcel/PHPExcel.php';
	$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
	$cacheSettings = array( ' memoryCacheSize ' => '8MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);


	
	$category = isset($_REQUEST['category']) ? strval($_REQUEST['category']) : '';
	$ast = isset($_REQUEST['ast']) ? strval($_REQUEST['ast']) : '';
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
	$location = isset($_REQUEST['location']) ? strval($_REQUEST['location']) : '';
	$group = isset($_REQUEST['group']) ? strval($_REQUEST['group']) : '';

	$sql = "select ASTNOHEAD, DESCHEAD, CAST(ACQDATEHEAD as varchar(10)) as ACQDATEHEAD, 
        CATEGORY, GRP, GRPDESC, LOCATION, LINE,ACQ_VALUE_TOTAL, ACCUMULATED_DEP_TOTAL, BOOK_VALUE_TOTAL,
        ACQ_VALUE_HEAD, ACCUMULATED_DEP_HEAD, BOOK_VALUE_HEAD, ASTNOCOMP, DESCCOMP, 
        CAST(ACQCOMP as varchar(10)) as ACQCOMP, ACQ_VALUE, ACCUMULATED_DEP, BOOK_VALUE
        from zvw_master_comp where 1=1";

    // echo $sql;

	if($category!="")$sql.=" and grp = '$category'";
	if($group!="")$sql.=" and left(grp,2) = '$group'";
	if($ast!="")$sql.=" and AstNoHead = '$ast'";
	if($line!="")$sql.=" and line = '$line'";
	if($location!="")$sql.=" and ocation = '$location'";
	
	$sql .=  " order by astnohead ";

    $rs = sqlsrv_query($connect, strtoupper($sql));

    $objPHPExcel = new PHPExcel(); 
    $sheet = $objPHPExcel->getActiveSheet();

    function cellColor($cells,$color){
        global $objPHPExcel;

        $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array(
                'rgb' => $color
            )
        ));
    }

    foreach(range('A','K') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }
    $sheet->getStyle('A5:K5')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));

    $sheet->getStyle('A5:K5')->getAlignment()->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );


    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A5', 'ASSET NO')
            ->setCellValue('B5', 'DESCRIPTION')
            ->setCellValue('C5', 'Acquitision Date')
          
            ->setCellValue('D5',  'Category')
            ->setCellValue('E5',  'Group ID')
			->setCellValue('F5',  'Group Desc')
			
			->setCellValue('G5',  'Location')
			->setCellValue('H5',  'Line')
			
			->setCellValue('I5',  'Acquitision Value')
			->setCellValue('J5',  'Accumulated Depre')
            ->setCellValue('K5',  'Book Value');
 
    $sheet->getStyle('A5:K5')->applyFromArray(
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


    $a = "";        $b = "";        $c = "";        $d = "";        $e = "";        $f = "";
    $flag = 0;
    $rowno=0;
    $items = array();
    $nourut = 6;

while($row = sqlsrv_fetch_object($rs) ) { 
	array_push($items, $row);
	$a = $items[$rowno]->ASTNOHEAD;

	if($a == $b){
		//Buat Detail
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F'.$nourut , $items[$rowno]->ASTNOCOMP)
            ->setCellValue('G'.$nourut , $items[$rowno]->DESCCOMP)
            ->setCellValue('H'.$nourut , $items[$rowno]->ACQCOMP)
			->setCellValue('I'.$nourut , $items[$rowno]->ACQ_VALUE)
			->setCellValue('J'.$nourut , $items[$rowno]->ACCUMULATED_DEP)
			->setCellValue('K'.$nourut , $items[$rowno]->BOOK_VALUE);

		$sheet->getStyle('F'.$nourut.':K'.$nourut)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'F0F8FF')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                    'wrap' => true
                ),
                'font'  => array(
                    'size'  => 12
                )
            )
        );
	}else{
		if($rowno == 0){
			//Buat Header
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$nourut, $$items[$rowno]>ASTNOHEAD)
            ->setCellValue('B'.$nourut, $$items[$rowno]>DESCHEAD)
            ->setCellValue('C'.$nourut, $$items[$rowno]>ACQDATEHEAD)
          
            ->setCellValue('D'.$nourut,  str_replace('&', ' ', $items[$rowno]->CATEGORY))
            ->setCellValue('E'.$nourut,  $items[$rowno]->GRP)
			->setCellValue('F'.$nourut,  $items[$rowno]->GRPDESC)
			
			->setCellValue('G'.$nourut,  $items[$rowno]->LOCATION)
			->setCellValue('H'.$nourut,  $items[$rowno]->LINE)
			
			->setCellValue('I'.$nourut,  $items[$rowno]->ACQ_VALUE_HEAD)
			->setCellValue('J'.$nourut,  $items[$rowno]->ACCUMULATED_DEP_HEAD)
			->setCellValue('K'.$nourut,  $items[$rowno]->BOOK_VALUE_HEAD);
			
            $sheet->getStyle('A'.$nourut.':K'.$nourut)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'ECE5B6')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                        'wrap' => true
                    ),
                    'font'  => array(
                        'size'  => 12
                    )
                )
            );
		
			$nourut++;
			$nourut++;
			$objPHPExcel->setActiveSheetIndex(0)
			
            ->setCellValue('F'.$nourut, 'COMPONENT ASSET NO')
            ->setCellValue('G'.$nourut , 'COMPONENT DESCRIPTION')
            ->setCellValue('H'.$nourut , 'Acquitision Date')
			->setCellValue('I'.$nourut , 'Acquitision Value')
			->setCellValue('J'.$nourut , 'Accumulated Depre')
			->setCellValue('K'.$nourut , 'Book Value');

			$sheet->getStyle('F'.$nourut.':K'.$nourut)->applyFromArray(
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
		
			$nourut++;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('F'.$nourut , $items[$rowno]->ASTNOCOMP)
				->setCellValue('G'.$nourut , $items[$rowno]->DESCCOMP)
				->setCellValue('H'.$nourut , $items[$rowno]->ACQCOMP)
				->setCellValue('I'.$nourut , $items[$rowno]->ACQ_VALUE)
				->setCellValue('J'.$nourut , $items[$rowno]->ACCUMULATED_DEP)
				->setCellValue('K'.$nourut , $items[$rowno]->BOOK_VALUE);
			$sheet->getStyle('F'.$nourut.':K'.$nourut)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F0F8FF')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                        'wrap' => true
                    ),
                    'font'  => array(
                        'size'  => 12
                    )
                )
            );
		}else{
			//Buat Total
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('H'.$nourut,  "TOTAL")
			->setCellValue('I'.$nourut,  $d)
			->setCellValue('J'.$nourut,  $e)
			->setCellValue('K'.$nourut,  $f);
			
			$sheet->getStyle('H'.$nourut.':K'.$nourut)->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FFFF00')
					),
					'borders' => array(
						'allborders' => array(
							'style' => PHPExcel_Style_Border::BORDER_THIN
						)
					)
				)
			);

			$nourut++;
			$nourut++;
			//Buat Header
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$nourut, $$items[$rowno]>ASTNOHEAD)
            ->setCellValue('B'.$nourut, $$items[$rowno]>DESCHEAD)
            ->setCellValue('C'.$nourut, $$items[$rowno]>ACQDATEHEAD)
          
            ->setCellValue('D'.$nourut,  str_replace('&', ' ', $items[$rowno]->CATEGORY))
            ->setCellValue('E'.$nourut,  $items[$rowno]->GRP)
			->setCellValue('F'.$nourut,  $items[$rowno]->GRPDESC)
			
			->setCellValue('G'.$nourut,  $items[$rowno]->LOCATION)
			->setCellValue('H'.$nourut,  $items[$rowno]->LINE)
			
			->setCellValue('I'.$nourut,  $items[$rowno]->ACQ_VALUE_HEAD)
			->setCellValue('J'.$nourut,  $items[$rowno]->ACCUMULATED_DEP_HEAD)
			->setCellValue('K'.$nourut,  $items[$rowno]->BOOK_VALUE_HEAD);
			
			$sheet->getStyle('A'.$nourut.':K'.$nourut)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'ECE5B6')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                        'wrap' => true
                    ),
                    'font'  => array(
                        'size'  => 12
                    )
                )
            );
		
			$nourut++;
			$nourut++;
			$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F'.$nourut, 'COMPONENT ASSET NO')
            ->setCellValue('G'.$nourut , 'COMPONENT DESCRIPTION')
            ->setCellValue('H'.$nourut , 'Acquitision Date')
			->setCellValue('I'.$nourut , 'Acquitision Value')
			->setCellValue('J'.$nourut , 'Accumulated Depre')
			->setCellValue('K'.$nourut , 'Book Value');

			$sheet->getStyle('F'.$nourut.':K'.$nourut)->applyFromArray(
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
		
			$nourut++;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('F'.$nourut , $items[$rowno]->ASTNOCOMP)
				->setCellValue('G'.$nourut , $items[$rowno]->DESCCOMP)
				->setCellValue('H'.$nourut , $items[$rowno]->ACQCOMP)
				->setCellValue('I'.$nourut , $items[$rowno]->ACQ_VALUE)
				->setCellValue('J'.$nourut , $items[$rowno]->ACCUMULATED_DEP)
				->setCellValue('K'.$nourut , $items[$rowno]->BOOK_VALUE);
			
			$sheet->getStyle('F'.$nourut.':K'.$nourut)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F0F8FF')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                        'wrap' => true
                    ),
                    'font'  => array(
                        'size'  => 12
                    )
                )
            );

		}
	}

	$d = $items[$rowno]->ACQ_VALUE_TOTAL;
	$e = $items[$rowno]->ACCUMULATED_DEP_TOTAL;
	$f = $items[$rowno]->BOOK_VALUE_TOTAL;
	$nourut++;
	$b = $a;
	$rowno++;
} 


$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

$objPHPExcel->setActiveSheetIndex(0);
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('WMS-RBI');
$objDrawing->setDescription('RBI');
$objDrawing->setPath('../../images/logo-print4.png');
$objDrawing->setWidth('400px');
$objDrawing->setCoordinates('A1');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="ASSET_COMPONENT.xlsx"');
$objWriter->save('php://output');
?>