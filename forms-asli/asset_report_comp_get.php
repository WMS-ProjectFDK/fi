<?php
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


	
	$sql = "select * from zvw_master_comp where 1=1 ";


	if($category!="")$sql.=" and ltrim(rtrim(grp)) = '$category'";
	if($group!="")$sql.=" and left(grp,2) = '$group'";
	if($ast!="")$sql.=" and ltrim(rtrim(AstNoHead)) = '$ast'";
	if($line!="")$sql.=" and ltrim(rtrim(line)) = '$line'";
	if($location!="")$sql.=" and ltrim(rtrim(location)) = '$location'";
	
	$sql .=  " order by astnohead ";

$rs=odbc_exec($con,$sql);


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


$a = "";
$b = "";
$c = "";
$d = "";
$e = "";
$f = "";
$flag = 0;

$nourut = 6;

while( $row = odbc_fetch_object($rs) ) { 
	// array_push($items, $row);
	$a = $row->ASTNOHEAD;

	if($a == $b){
		//Buat Detail
		
		
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('F'.$nourut , $row->ASTNOCOMP)
            ->setCellValue('G'.$nourut , $row->DESCCOMP)
            ->setCellValue('H'.$nourut , $row->ACQCOMP)
			->setCellValue('I'.$nourut , $row->ACQ_VALUE)
			->setCellValue('J'.$nourut , $row->ACCUMULATED_DEP)
			->setCellValue('K'.$nourut , $row->BOOK_VALUE);

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
            ->setCellValue('A'.$nourut, $row->ASTNOHEAD)
            ->setCellValue('B'.$nourut, $row->DESCHEAD)
            ->setCellValue('C'.$nourut, $row->ACQDATEHEAD)
          
            ->setCellValue('D'.$nourut,  $row->CATEGORY)
            ->setCellValue('E'.$nourut,  $row->GRP)
			->setCellValue('F'.$nourut,  $row->GRPDESC)
			
			->setCellValue('G'.$nourut,  $row->LOCATION)
			->setCellValue('H'.$nourut,  $row->LINE)
			
			->setCellValue('I'.$nourut,  $row->ACQ_VALUE_HEAD)
			->setCellValue('J'.$nourut,  $row->ACCUMULATED_DEP_HEAD)
			->setCellValue('K'.$nourut,  $row->BOOK_VALUE_HEAD);
			
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
				->setCellValue('F'.$nourut , $row->ASTNOCOMP)
				->setCellValue('G'.$nourut , $row->DESCCOMP)
				->setCellValue('H'.$nourut , $row->ACQCOMP)
				->setCellValue('I'.$nourut , $row->ACQ_VALUE)
				->setCellValue('J'.$nourut , $row->ACCUMULATED_DEP)
				->setCellValue('K'.$nourut , $row->BOOK_VALUE);
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
            ->setCellValue('A'.$nourut, $row->ASTNOHEAD)
            ->setCellValue('B'.$nourut, $row->DESCHEAD)
            ->setCellValue('C'.$nourut, $row->ACQDATEHEAD)
          
            ->setCellValue('D'.$nourut,  $row->CATEGORY)
            ->setCellValue('E'.$nourut,  $row->GRP)
			->setCellValue('F'.$nourut,  $row->GRPDESC)
			
			->setCellValue('G'.$nourut,  $row->LOCATION)
			->setCellValue('H'.$nourut,  $row->LINE)
			
			->setCellValue('I'.$nourut,  $row->ACQ_VALUE_HEAD)
			->setCellValue('J'.$nourut,  $row->ACCUMULATED_DEP_HEAD)
			->setCellValue('K'.$nourut,  $row->BOOK_VALUE_HEAD);
			
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
				->setCellValue('F'.$nourut , $row->ASTNOCOMP)
				->setCellValue('G'.$nourut , $row->DESCCOMP)
				->setCellValue('H'.$nourut , $row->ACQCOMP)
				->setCellValue('I'.$nourut , $row->ACQ_VALUE)
				->setCellValue('J'.$nourut , $row->ACCUMULATED_DEP)
				->setCellValue('K'.$nourut , $row->BOOK_VALUE);
			
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

	$d = $row->ACQ_VALUE_TOTAL;
	$e = $row->ACCUMULATED_DEP_TOTAL;
	$f = $row->BOOK_VALUE_TOTAL;
	$nourut++;
	$b = $a;
	$rowno++;
} 


$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

$objPHPExcel->setActiveSheetIndex(0);
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('WMS-FDKI');
$objDrawing->setDescription('FDKI');
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