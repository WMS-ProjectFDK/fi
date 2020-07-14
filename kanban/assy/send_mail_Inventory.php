<?php
//error_reporting(0);
ini_set('memory_limit', '-1');
ini_set('MAX_EXECUTION_TIME', -1);
set_time_limit(0);
include("../../connect/conn.php");

/* CONTENT ATACHMENT*/
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$t_month = "select distinct to_char(substr(this_month,5,2)) this_month, to_char(substr(this_month,1,4)) this_year from whinventory";
$r_month = oci_parse($connect, $t_month);
oci_execute($r_month);
$dt_month = oci_fetch_object($r_month);

$arrBln = array('01' => 'JAN', '02' => 'FEB', '03' => 'MAR', '04' => 'APR', '05' => 'MAY', '06' => 'JUN',
				'07' => 'JUL', '08' => 'AUG', '09' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DEC');
$th_mon = $dt_month->THIS_MONTH;
$this_month = $arrBln[$th_mon];
$this_year = $dt_month->THIS_YEAR;

$qry = "select z.*,i.description from zvw_raw_material2 z 
	inner join item i on i.item_no = z.item_no 
	order by z.item, z.item_no ,keterangan";
$result = oci_parse($connect, $qry);
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

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'INVENTORY MONTH:')
            ->setCellValue('C1', $this_month.' '.$this_year);

$objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
 
// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A2', 'ITEM TYPE')
            ->setCellValue('B2', 'ITEM NO')
            ->setCellValue('C2', 'ITEM_NAME')
            ->setCellValue('D2', 'REMARKS')
            ->setCellValue('E2', 'LAST MONTH')
            ->setCellValue('F2', 'LAST MONTH AMOUNT')
            ->setCellValue('G2', 'DATE')
            ->setCellValue('G3', '1')
            ->setCellValue('H3', '2')
            ->setCellValue('I3', '3')
            ->setCellValue('J3', '4')
            ->setCellValue('K3', '5')
            ->setCellValue('L3', '6')
            ->setCellValue('M3', '7')
            ->setCellValue('N3', '8')
            ->setCellValue('O3', '9')
            ->setCellValue('P3', '10')
            ->setCellValue('Q3', '11')
            ->setCellValue('R3', '12')
            ->setCellValue('S3', '13')
            ->setCellValue('T3', '14')
            ->setCellValue('U3', '15')
            ->setCellValue('V3', '16')
            ->setCellValue('W3', '17')
            ->setCellValue('X3', '18')
            ->setCellValue('Y3', '19')
            ->setCellValue('Z3', '20')
            ->setCellValue('AA3', '21')
            ->setCellValue('AB3', '22')
            ->setCellValue('AC3', '23')
            ->setCellValue('AD3', '24')
            ->setCellValue('AE3', '25')
            ->setCellValue('AF3', '26')
            ->setCellValue('AG3', '27')
            ->setCellValue('AH3', '28')
            ->setCellValue('AI3', '29')
            ->setCellValue('AJ3', '30')
            ->setCellValue('AK3', '31')
            ->setCellValue('AL2', 'THIS MONTH')
            ->setCellValue('AM2', 'THIS MONTH AMOUNT');

foreach(range('A','F') as $columnID) {
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->mergeCells('A2:A3');
	$objPHPExcel->getActiveSheet()->mergeCells('B2:B3');
	$objPHPExcel->getActiveSheet()->mergeCells('C2:C3');
	$objPHPExcel->getActiveSheet()->mergeCells('D2:D3');
	$objPHPExcel->getActiveSheet()->mergeCells('E2:E3');
	$objPHPExcel->getActiveSheet()->mergeCells('F2:F3');
	$objPHPExcel->getActiveSheet()->mergeCells('G2:AK2');
	$objPHPExcel->getActiveSheet()->mergeCells('AL2:AL3');
	$objPHPExcel->getActiveSheet()->mergeCells('AM2:AM3');

	$sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('A2:AM3')->getAlignment()->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
        )
    );

    $sheet->getStyle('A2:AM3')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'DCDCDC')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
    $sheet->getStyle("A2:AM3")->getFont()->setBold(true)->setName('Tahoma')->setSize(8)->getColor()->setRGB('000000');
}
    
$no=4;
$it_type = "";		$it_no = "";		$it_nm = "";
$tot_A_lmonth = '';				$tot_A_THISMONTH = '';
$tot_A_lmonthamount = '';		$tot_A_THISMONTHAMOUNT='';
$tot_A_TANGGAL1 = 0;			$tot_A_TANGGAL11 = 0;				$tot_A_TANGGAL21 = 0;			$tot_A_TANGGAL31 = 0;
$tot_A_TANGGAL2 = 0;			$tot_A_TANGGAL12 = 0;				$tot_A_TANGGAL22 = 0;
$tot_A_TANGGAL3 = 0;			$tot_A_TANGGAL13 = 0;				$tot_A_TANGGAL23 = 0;
$tot_A_TANGGAL4 = 0;			$tot_A_TANGGAL14 = 0;				$tot_A_TANGGAL24 = 0;
$tot_A_TANGGAL5 = 0;			$tot_A_TANGGAL15 = 0;				$tot_A_TANGGAL25 = 0;
$tot_A_TANGGAL6 = 0;			$tot_A_TANGGAL16 = 0;				$tot_A_TANGGAL26 = 0;
$tot_A_TANGGAL7 = 0;			$tot_A_TANGGAL17 = 0;				$tot_A_TANGGAL27 = 0;
$tot_A_TANGGAL8 = 0;			$tot_A_TANGGAL18 = 0;				$tot_A_TANGGAL28 = 0;
$tot_A_TANGGAL9 = 0;			$tot_A_TANGGAL19 = 0;				$tot_A_TANGGAL29 = 0;
$tot_A_TANGGAL0 = 0;			$tot_A_TANGGAL20 = 0;				$tot_A_TANGGAL30 = 0;

$tot_B_lmonth = '';				$tot_B_THISMONTH = '';
$tot_B_lmonthamount = '';		$tot_B_THISMONTHAMOUNT='';			
$tot_B_TANGGAL1 = 0;			$tot_B_TANGGAL11 = 0;				$tot_B_TANGGAL21 = 0;			$tot_B_TANGGAL31 = 0;
$tot_B_TANGGAL2 = 0;			$tot_B_TANGGAL12 = 0;				$tot_B_TANGGAL22 = 0;
$tot_B_TANGGAL3 = 0;			$tot_B_TANGGAL13 = 0;				$tot_B_TANGGAL23 = 0;
$tot_B_TANGGAL4 = 0;			$tot_B_TANGGAL14 = 0;				$tot_B_TANGGAL24 = 0;
$tot_B_TANGGAL5 = 0;			$tot_B_TANGGAL15 = 0;				$tot_B_TANGGAL25 = 0;
$tot_B_TANGGAL6 = 0;			$tot_B_TANGGAL16 = 0;				$tot_B_TANGGAL26 = 0;
$tot_B_TANGGAL7 = 0;			$tot_B_TANGGAL17 = 0;				$tot_B_TANGGAL27 = 0;
$tot_B_TANGGAL8 = 0;			$tot_B_TANGGAL18 = 0;				$tot_B_TANGGAL28 = 0;
$tot_B_TANGGAL9 = 0;			$tot_B_TANGGAL19 = 0;				$tot_B_TANGGAL29 = 0;
$tot_B_TANGGAL0 = 0;			$tot_B_TANGGAL20 = 0;				$tot_B_TANGGAL30 = 0;			

$tot_C_lmonth = '';				$tot_C_THISMONTH = '';
$tot_C_lmonthamount = '';		$tot_C_THISMONTHAMOUNT='';			
$tot_C_TANGGAL1 = 0;			$tot_C_TANGGAL11 = 0;				$tot_C_TANGGAL21 = 0;			$tot_C_TANGGAL31 = 0;
$tot_C_TANGGAL2 = 0;			$tot_C_TANGGAL12 = 0;				$tot_C_TANGGAL22 = 0;
$tot_C_TANGGAL3 = 0;			$tot_C_TANGGAL13 = 0;				$tot_C_TANGGAL23 = 0;
$tot_C_TANGGAL4 = 0;			$tot_C_TANGGAL14 = 0;				$tot_C_TANGGAL24 = 0;
$tot_C_TANGGAL5 = 0;			$tot_C_TANGGAL15 = 0;				$tot_C_TANGGAL25 = 0;
$tot_C_TANGGAL6 = 0;			$tot_C_TANGGAL16 = 0;				$tot_C_TANGGAL26 = 0;
$tot_C_TANGGAL7 = 0;			$tot_C_TANGGAL17 = 0;				$tot_C_TANGGAL27 = 0;
$tot_C_TANGGAL8 = 0;			$tot_C_TANGGAL18 = 0;				$tot_C_TANGGAL28 = 0;
$tot_C_TANGGAL9 = 0;			$tot_C_TANGGAL19 = 0;				$tot_C_TANGGAL29 = 0;
$tot_C_TANGGAL0 = 0;			$tot_C_TANGGAL20 = 0;				$tot_C_TANGGAL30 = 0;			

$tot_D_lmonth = 0;				$tot_D_THISMONTH = 0;						
$tot_D_lmonthamount = 0;		$tot_D_THISMONTHAMOUNT=0;			
$tot_D_TANGGAL1 = 0;			$tot_D_TANGGAL11 = 0;				$tot_D_TANGGAL21 = 0;			$tot_D_TANGGAL31 = 0;
$tot_D_TANGGAL2 = 0;			$tot_D_TANGGAL12 = 0;				$tot_D_TANGGAL22 = 0;
$tot_D_TANGGAL3 = 0;			$tot_D_TANGGAL13 = 0;				$tot_D_TANGGAL23 = 0;
$tot_D_TANGGAL4 = 0;			$tot_D_TANGGAL14 = 0;				$tot_D_TANGGAL24 = 0;
$tot_D_TANGGAL5 = 0;			$tot_D_TANGGAL15 = 0;				$tot_D_TANGGAL25 = 0;
$tot_D_TANGGAL6 = 0;			$tot_D_TANGGAL16 = 0;				$tot_D_TANGGAL26 = 0;
$tot_D_TANGGAL7 = 0;			$tot_D_TANGGAL17 = 0;				$tot_D_TANGGAL27 = 0;
$tot_D_TANGGAL8 = 0;			$tot_D_TANGGAL18 = 0;				$tot_D_TANGGAL28 = 0;
$tot_D_TANGGAL9 = 0;			$tot_D_TANGGAL19 = 0;				$tot_D_TANGGAL29 = 0;
$tot_D_TANGGAL0 = 0;			$tot_D_TANGGAL20 = 0;				$tot_D_TANGGAL30 = 0;

while ($data=oci_fetch_object($result)) {
	if($no==4){
		$it_type = $data->ITEM;
	}else{
		if ($data->ITEM == $it_type){
			$it_type = "";	$it_nm="";
		}else{
			$objPHPExcel->getActiveSheet()->mergeCells('A'.$no.':C'.$no);
			$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A'.$no, 'TOTAL')
		                ->setCellValue('D'.$no, 'A.USAGE')
		                ->setCellValue('E'.$no, $tot_A_lmonth)
		                ->setCellValue('F'.$no, $tot_A_lmonthamount)
		                ->setCellValue('G'.$no, $tot_A_TANGGAL1)
			            ->setCellValue('H'.$no, $tot_A_TANGGAL2)
			            ->setCellValue('I'.$no, $tot_A_TANGGAL3)
			            ->setCellValue('J'.$no, $tot_A_TANGGAL4)
			            ->setCellValue('K'.$no, $tot_A_TANGGAL5)
			            ->setCellValue('L'.$no, $tot_A_TANGGAL6)
			            ->setCellValue('M'.$no, $tot_A_TANGGAL7)
			            ->setCellValue('N'.$no, $tot_A_TANGGAL8)
			            ->setCellValue('O'.$no, $tot_A_TANGGAL9)
			            ->setCellValue('P'.$no, $tot_A_TANGGAL0)
			            ->setCellValue('Q'.$no, $tot_A_TANGGAL11)
			            ->setCellValue('R'.$no, $tot_A_TANGGAL12)
			            ->setCellValue('S'.$no, $tot_A_TANGGAL13)
			            ->setCellValue('T'.$no, $tot_A_TANGGAL14)
			            ->setCellValue('U'.$no, $tot_A_TANGGAL15)
			            ->setCellValue('V'.$no, $tot_A_TANGGAL16)
			            ->setCellValue('W'.$no, $tot_A_TANGGAL17)
			            ->setCellValue('X'.$no, $tot_A_TANGGAL18)
			            ->setCellValue('Y'.$no, $tot_A_TANGGAL19)
			            ->setCellValue('Z'.$no, $tot_A_TANGGAL20)
			            ->setCellValue('AA'.$no, $tot_A_TANGGAL21) 
			            ->setCellValue('AB'.$no, $tot_A_TANGGAL22)
			            ->setCellValue('AC'.$no, $tot_A_TANGGAL23)
			            ->setCellValue('AD'.$no, $tot_A_TANGGAL24)
			            ->setCellValue('AE'.$no, $tot_A_TANGGAL25)
			            ->setCellValue('AF'.$no, $tot_A_TANGGAL26)
			            ->setCellValue('AG'.$no, $tot_A_TANGGAL27)
			            ->setCellValue('AH'.$no, $tot_A_TANGGAL28)
			            ->setCellValue('AI'.$no, $tot_A_TANGGAL29)
			            ->setCellValue('AJ'.$no, $tot_A_TANGGAL30)
			            ->setCellValue('AK'.$no, $tot_A_TANGGAL31)
			            ->setCellValue('AL'.$no, $tot_A_THISMONTH)
			            ->setCellValue('AM'.$no, $tot_A_THISMONTHAMOUNT);

			$sheet->getStyle('A'.$no.':C'.$no)->getAlignment()->applyFromArray(
		        array(
		            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		        )
		    );

			$sheet->getStyle('A'.$no.':AM'.$no)->applyFromArray(
		        array(
		            'fill' => array(
		                'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                'color' => array('rgb' => 'AAFFFF')
		            )
		        )
		    );

		    $objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

	        $no++;

	        $objPHPExcel->getActiveSheet()->mergeCells('A'.$no.':C'.$no);
			$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A'.$no, 'TOTAL')
		                ->setCellValue('D'.$no, 'B.PURCHASE')
		                ->setCellValue('E'.$no, $tot_B_lmonth)
		                ->setCellValue('F'.$no, $tot_B_lmonthamount)
		                ->setCellValue('G'.$no, $tot_B_TANGGAL1)
			            ->setCellValue('H'.$no, $tot_B_TANGGAL2)
			            ->setCellValue('I'.$no, $tot_B_TANGGAL3)
			            ->setCellValue('J'.$no, $tot_B_TANGGAL4)
			            ->setCellValue('K'.$no, $tot_B_TANGGAL5)
			            ->setCellValue('L'.$no, $tot_B_TANGGAL6)
			            ->setCellValue('M'.$no, $tot_B_TANGGAL7)
			            ->setCellValue('N'.$no, $tot_B_TANGGAL8)
			            ->setCellValue('O'.$no, $tot_B_TANGGAL9)
			            ->setCellValue('P'.$no, $tot_B_TANGGAL0)
			            ->setCellValue('Q'.$no, $tot_B_TANGGAL11)
			            ->setCellValue('R'.$no, $tot_B_TANGGAL12)
			            ->setCellValue('S'.$no, $tot_B_TANGGAL13)
			            ->setCellValue('T'.$no, $tot_B_TANGGAL14)
			            ->setCellValue('U'.$no, $tot_B_TANGGAL15)
			            ->setCellValue('V'.$no, $tot_B_TANGGAL16)
			            ->setCellValue('W'.$no, $tot_B_TANGGAL17)
			            ->setCellValue('X'.$no, $tot_B_TANGGAL18)
			            ->setCellValue('Y'.$no, $tot_B_TANGGAL19)
			            ->setCellValue('Z'.$no, $tot_B_TANGGAL20)
			            ->setCellValue('AA'.$no, $tot_B_TANGGAL21)
			            ->setCellValue('AB'.$no, $tot_B_TANGGAL22)
			            ->setCellValue('AC'.$no, $tot_B_TANGGAL23)
			            ->setCellValue('AD'.$no, $tot_B_TANGGAL24)
			            ->setCellValue('AE'.$no, $tot_B_TANGGAL25)
			            ->setCellValue('AF'.$no, $tot_B_TANGGAL26)
			            ->setCellValue('AG'.$no, $tot_B_TANGGAL27)
			            ->setCellValue('AH'.$no, $tot_B_TANGGAL28)
			            ->setCellValue('AI'.$no, $tot_B_TANGGAL29)
			            ->setCellValue('AJ'.$no, $tot_B_TANGGAL30)
			            ->setCellValue('AK'.$no, $tot_B_TANGGAL31)
			            ->setCellValue('AL'.$no, $tot_B_THISMONTH)
			            ->setCellValue('AM'.$no, $tot_B_THISMONTHAMOUNT);

			$sheet->getStyle('A'.$no.':C'.$no)->getAlignment()->applyFromArray(
		        array(
		            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		        )
		    );

			$sheet->getStyle('A'.$no.':AM'.$no)->applyFromArray(
		        array(
		            'fill' => array(
		                'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                'color' => array('rgb' => 'AAFFFF')
		            )
		        )
		    );

		    $objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

	        $no++;

	        $objPHPExcel->getActiveSheet()->mergeCells('A'.$no.':C'.$no);
			$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A'.$no, 'TOTAL')
		                ->setCellValue('D'.$no, 'C.MUTATION')
		                ->setCellValue('E'.$no, $tot_C_lmonth)
		                ->setCellValue('F'.$no, $tot_C_lmonthamount)
		                ->setCellValue('G'.$no, $tot_C_TANGGAL1)
			            ->setCellValue('H'.$no, $tot_C_TANGGAL2)
			            ->setCellValue('I'.$no, $tot_C_TANGGAL3)
			            ->setCellValue('J'.$no, $tot_C_TANGGAL4)
			            ->setCellValue('K'.$no, $tot_C_TANGGAL5)
			            ->setCellValue('L'.$no, $tot_C_TANGGAL6)
			            ->setCellValue('M'.$no, $tot_C_TANGGAL7)
			            ->setCellValue('N'.$no, $tot_C_TANGGAL8)
			            ->setCellValue('O'.$no, $tot_C_TANGGAL9)
			            ->setCellValue('P'.$no, $tot_C_TANGGAL0)
			            ->setCellValue('Q'.$no, $tot_C_TANGGAL11)
			            ->setCellValue('R'.$no, $tot_C_TANGGAL12)
			            ->setCellValue('S'.$no, $tot_C_TANGGAL13)
			            ->setCellValue('T'.$no, $tot_C_TANGGAL14)
			            ->setCellValue('U'.$no, $tot_C_TANGGAL15)
			            ->setCellValue('V'.$no, $tot_C_TANGGAL16)
			            ->setCellValue('W'.$no, $tot_C_TANGGAL17)
			            ->setCellValue('X'.$no, $tot_C_TANGGAL18)
			            ->setCellValue('Y'.$no, $tot_C_TANGGAL19)
			            ->setCellValue('Z'.$no, $tot_C_TANGGAL20)
			            ->setCellValue('AA'.$no,$tot_C_TANGGAL21)
			            ->setCellValue('AB'.$no, $tot_C_TANGGAL22)
			            ->setCellValue('AC'.$no, $tot_C_TANGGAL23)
			            ->setCellValue('AD'.$no, $tot_C_TANGGAL24)
			            ->setCellValue('AE'.$no, $tot_C_TANGGAL25)
			            ->setCellValue('AF'.$no, $tot_C_TANGGAL26)
			            ->setCellValue('AG'.$no, $tot_C_TANGGAL27)
			            ->setCellValue('AH'.$no, $tot_C_TANGGAL28)
			            ->setCellValue('AI'.$no, $tot_C_TANGGAL29)
			            ->setCellValue('AJ'.$no, $tot_C_TANGGAL30)
			            ->setCellValue('AK'.$no, $tot_C_TANGGAL31)
			            ->setCellValue('AL'.$no, $tot_C_THISMONTH)
			            ->setCellValue('AM'.$no, $tot_C_THISMONTHAMOUNT);

			$sheet->getStyle('A'.$no.':C'.$no)->getAlignment()->applyFromArray(
		        array(
		            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		        )
		    );

			$sheet->getStyle('A'.$no.':AM'.$no)->applyFromArray(
		        array(
		            'fill' => array(
		                'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                'color' => array('rgb' => 'AAFFFF')
		            )
		        )
		    );

		    $objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

	        $no++;

			$objPHPExcel->getActiveSheet()->mergeCells('A'.$no.':C'.$no);
			$objPHPExcel->setActiveSheetIndex(0)
		                ->setCellValue('A'.$no, 'TOTAL')
		                ->setCellValue('D'.$no, 'D.STOCK')
		                ->setCellValue('E'.$no, $tot_D_lmonth)
		                ->setCellValue('F'.$no, $tot_D_lmonthamount)
		                ->setCellValue('G'.$no, $tot_D_TANGGAL1)
			            ->setCellValue('H'.$no, $tot_D_TANGGAL2)
			            ->setCellValue('I'.$no, $tot_D_TANGGAL3)
			            ->setCellValue('J'.$no, $tot_D_TANGGAL4)
			            ->setCellValue('K'.$no, $tot_D_TANGGAL5)
			            ->setCellValue('L'.$no, $tot_D_TANGGAL6)
			            ->setCellValue('M'.$no, $tot_D_TANGGAL7)
			            ->setCellValue('N'.$no, $tot_D_TANGGAL8)
			            ->setCellValue('O'.$no, $tot_D_TANGGAL9)
			            ->setCellValue('P'.$no, $tot_D_TANGGAL0)
			            ->setCellValue('Q'.$no, $tot_D_TANGGAL11)
			            ->setCellValue('R'.$no, $tot_D_TANGGAL12)
			            ->setCellValue('S'.$no, $tot_D_TANGGAL13)
			            ->setCellValue('T'.$no, $tot_D_TANGGAL14)
			            ->setCellValue('U'.$no, $tot_D_TANGGAL15)
			            ->setCellValue('V'.$no, $tot_D_TANGGAL16)
			            ->setCellValue('W'.$no, $tot_D_TANGGAL17)
			            ->setCellValue('X'.$no, $tot_D_TANGGAL18)
			            ->setCellValue('Y'.$no, $tot_D_TANGGAL19)
			            ->setCellValue('Z'.$no, $tot_D_TANGGAL20)
			            ->setCellValue('AA'.$no,$tot_D_TANGGAL21)
			            ->setCellValue('AB'.$no, $tot_D_TANGGAL22)
			            ->setCellValue('AC'.$no, $tot_D_TANGGAL23)
			            ->setCellValue('AD'.$no, $tot_D_TANGGAL24)
			            ->setCellValue('AE'.$no, $tot_D_TANGGAL25)
			            ->setCellValue('AF'.$no, $tot_D_TANGGAL26)
			            ->setCellValue('AG'.$no, $tot_D_TANGGAL27)
			            ->setCellValue('AH'.$no, $tot_D_TANGGAL28)
			            ->setCellValue('AI'.$no, $tot_D_TANGGAL29)
			            ->setCellValue('AJ'.$no, $tot_D_TANGGAL30)
			            ->setCellValue('AK'.$no, $tot_D_TANGGAL31)
			            ->setCellValue('AL'.$no, $tot_D_THISMONTH)
			            ->setCellValue('AM'.$no, $tot_D_THISMONTHAMOUNT);

			$sheet->getStyle('A'.$no.':C'.$no)->getAlignment()->applyFromArray(
		        array(
		            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
		        )
		    );

			$sheet->getStyle('A'.$no.':AM'.$no)->applyFromArray(
		        array(
		            'fill' => array(
		                'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                'color' => array('rgb' => 'AAFFFF')
		            )
		        )
		    );

		    $objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

	        $no++;

	        /*$tot_A_lmonth = 0;				$tot_A_THISMONTH = 0;						
			$tot_A_lmonthamount = 0;		$tot_A_THISMONTHAMOUNT=0;			*/
			$tot_A_TANGGAL1 = 0;			$tot_A_TANGGAL11 = 0;				$tot_A_TANGGAL21 = 0;			$tot_A_TANGGAL31 = 0;
			$tot_A_TANGGAL2 = 0;			$tot_A_TANGGAL12 = 0;				$tot_A_TANGGAL22 = 0;
			$tot_A_TANGGAL3 = 0;			$tot_A_TANGGAL13 = 0;				$tot_A_TANGGAL23 = 0;
			$tot_A_TANGGAL4 = 0;			$tot_A_TANGGAL14 = 0;				$tot_A_TANGGAL24 = 0;
			$tot_A_TANGGAL5 = 0;			$tot_A_TANGGAL15 = 0;				$tot_A_TANGGAL25 = 0;
			$tot_A_TANGGAL6 = 0;			$tot_A_TANGGAL16 = 0;				$tot_A_TANGGAL26 = 0;
			$tot_A_TANGGAL7 = 0;			$tot_A_TANGGAL17 = 0;				$tot_A_TANGGAL27 = 0;
			$tot_A_TANGGAL8 = 0;			$tot_A_TANGGAL18 = 0;				$tot_A_TANGGAL28 = 0;
			$tot_A_TANGGAL9 = 0;			$tot_A_TANGGAL19 = 0;				$tot_A_TANGGAL29 = 0;
			$tot_A_TANGGAL0 = 0;			$tot_A_TANGGAL20 = 0;				$tot_A_TANGGAL30 = 0;

			/*$tot_B_lmonth = 0;				$tot_B_THISMONTH = 0;						
			$tot_B_lmonthamount = 0;		$tot_B_THISMONTHAMOUNT=0;			*/
			$tot_B_TANGGAL1 = 0;			$tot_B_TANGGAL11 = 0;				$tot_B_TANGGAL21 = 0;			$tot_B_TANGGAL31 = 0;
			$tot_B_TANGGAL2 = 0;			$tot_B_TANGGAL12 = 0;				$tot_B_TANGGAL22 = 0;
			$tot_B_TANGGAL3 = 0;			$tot_B_TANGGAL13 = 0;				$tot_B_TANGGAL23 = 0;
			$tot_B_TANGGAL4 = 0;			$tot_B_TANGGAL14 = 0;				$tot_B_TANGGAL24 = 0;
			$tot_B_TANGGAL5 = 0;			$tot_B_TANGGAL15 = 0;				$tot_B_TANGGAL25 = 0;
			$tot_B_TANGGAL6 = 0;			$tot_B_TANGGAL16 = 0;				$tot_B_TANGGAL26 = 0;
			$tot_B_TANGGAL7 = 0;			$tot_B_TANGGAL17 = 0;				$tot_B_TANGGAL27 = 0;
			$tot_B_TANGGAL8 = 0;			$tot_B_TANGGAL18 = 0;				$tot_B_TANGGAL28 = 0;
			$tot_B_TANGGAL9 = 0;			$tot_B_TANGGAL19 = 0;				$tot_B_TANGGAL29 = 0;
			$tot_B_TANGGAL0 = 0;			$tot_B_TANGGAL20 = 0;				$tot_B_TANGGAL30 = 0;			

			/*$tot_C_lmonth = 0;				$tot_C_THISMONTH = 0;						
			$tot_C_lmonthamount = 0;		$tot_C_THISMONTHAMOUNT=0;			*/
			$tot_C_TANGGAL1 = 0;			$tot_C_TANGGAL11 = 0;				$tot_C_TANGGAL21 = 0;			$tot_C_TANGGAL31 = 0;
			$tot_C_TANGGAL2 = 0;			$tot_C_TANGGAL12 = 0;				$tot_C_TANGGAL22 = 0;
			$tot_C_TANGGAL3 = 0;			$tot_C_TANGGAL13 = 0;				$tot_C_TANGGAL23 = 0;
			$tot_C_TANGGAL4 = 0;			$tot_C_TANGGAL14 = 0;				$tot_C_TANGGAL24 = 0;
			$tot_C_TANGGAL5 = 0;			$tot_C_TANGGAL15 = 0;				$tot_C_TANGGAL25 = 0;
			$tot_C_TANGGAL6 = 0;			$tot_C_TANGGAL16 = 0;				$tot_C_TANGGAL26 = 0;
			$tot_C_TANGGAL7 = 0;			$tot_C_TANGGAL17 = 0;				$tot_C_TANGGAL27 = 0;
			$tot_C_TANGGAL8 = 0;			$tot_C_TANGGAL18 = 0;				$tot_C_TANGGAL28 = 0;
			$tot_C_TANGGAL9 = 0;			$tot_C_TANGGAL19 = 0;				$tot_C_TANGGAL29 = 0;
			$tot_C_TANGGAL0 = 0;			$tot_C_TANGGAL20 = 0;				$tot_C_TANGGAL30 = 0;			

			$tot_D_lmonth = 0;				$tot_D_THISMONTH = 0;						
			$tot_D_lmonthamount = 0;		$tot_D_THISMONTHAMOUNT=0;			
			$tot_D_TANGGAL1 = 0;			$tot_D_TANGGAL11 = 0;				$tot_D_TANGGAL21 = 0;			$tot_D_TANGGAL31 = 0;
			$tot_D_TANGGAL2 = 0;			$tot_D_TANGGAL12 = 0;				$tot_D_TANGGAL22 = 0;
			$tot_D_TANGGAL3 = 0;			$tot_D_TANGGAL13 = 0;				$tot_D_TANGGAL23 = 0;
			$tot_D_TANGGAL4 = 0;			$tot_D_TANGGAL14 = 0;				$tot_D_TANGGAL24 = 0;
			$tot_D_TANGGAL5 = 0;			$tot_D_TANGGAL15 = 0;				$tot_D_TANGGAL25 = 0;
			$tot_D_TANGGAL6 = 0;			$tot_D_TANGGAL16 = 0;				$tot_D_TANGGAL26 = 0;
			$tot_D_TANGGAL7 = 0;			$tot_D_TANGGAL17 = 0;				$tot_D_TANGGAL27 = 0;
			$tot_D_TANGGAL8 = 0;			$tot_D_TANGGAL18 = 0;				$tot_D_TANGGAL28 = 0;
			$tot_D_TANGGAL9 = 0;			$tot_D_TANGGAL19 = 0;				$tot_D_TANGGAL29 = 0;
			$tot_D_TANGGAL0 = 0;			$tot_D_TANGGAL20 = 0;				$tot_D_TANGGAL30 = 0;

			$it_type = $data->ITEM;
		}	
	}
	

	if ($data->ITEM_NO == $it_no){
		$it_no = "";		$it_nm;

		if($data->KETERANGAN == 'B.Purchase'){
		 	$data->LASTMONTH = '';
		 	$data->LASTMONTHAMOUNT = '';
		 	$data->THISMONTH = '';
			$data->THISMONTHAMOUNT = '';

			/*$tot_B_lmonth = '';
			$tot_B_lmonthamount = '';*/

			$tot_B_TANGGAL1 += $data->TANGGAL1;		$tot_B_TANGGAL11 += $data->TANGGAL11;		$tot_B_TANGGAL21 += $data->TANGGAL21;		$tot_B_TANGGAL31 += $data->TANGGAL31;
			$tot_B_TANGGAL2 += $data->TANGGAL2;		$tot_B_TANGGAL12 += $data->TANGGAL12;		$tot_B_TANGGAL22 += $data->TANGGAL22;
			$tot_B_TANGGAL3 += $data->TANGGAL3;		$tot_B_TANGGAL13 += $data->TANGGAL13;		$tot_B_TANGGAL23 += $data->TANGGAL23;
			$tot_B_TANGGAL4 += $data->TANGGAL4;		$tot_B_TANGGAL14 += $data->TANGGAL14;		$tot_B_TANGGAL24 += $data->TANGGAL24;
			$tot_B_TANGGAL5 += $data->TANGGAL5;		$tot_B_TANGGAL15 += $data->TANGGAL15;		$tot_B_TANGGAL25 += $data->TANGGAL25;
			$tot_B_TANGGAL6 += $data->TANGGAL6;		$tot_B_TANGGAL16 += $data->TANGGAL16;		$tot_B_TANGGAL26 += $data->TANGGAL26;
			$tot_B_TANGGAL7 += $data->TANGGAL7;		$tot_B_TANGGAL17 += $data->TANGGAL17;		$tot_B_TANGGAL27 += $data->TANGGAL27;
			$tot_B_TANGGAL8 += $data->TANGGAL8;		$tot_B_TANGGAL18 += $data->TANGGAL18;		$tot_B_TANGGAL28 += $data->TANGGAL28;
			$tot_B_TANGGAL9 += $data->TANGGAL9;		$tot_B_TANGGAL19 += $data->TANGGAL19;		$tot_B_TANGGAL29 += $data->TANGGAL29;
			$tot_B_TANGGAL0 += $data->TANGGAL0;		$tot_B_TANGGAL20 += $data->TANGGAL20;		$tot_B_TANGGAL30 += $data->TANGGAL30;

			/*$tot_B_THISMONTH = '';
			$tot_B_THISMONTHAMOUNT = '';*/
		}

		if($data->KETERANGAN == 'C.Mutation'){
			$data->LASTMONTH = '';
		 	$data->LASTMONTHAMOUNT = '';
		 	$data->THISMONTH = '';
			$data->THISMONTHAMOUNT = '';

			/*$tot_C_lmonth = '';
			$tot_C_lmonthamount = '';*/

			$tot_C_TANGGAL1 += $data->TANGGAL1;		$tot_C_TANGGAL11 += $data->TANGGAL11;		$tot_C_TANGGAL21 += $data->TANGGAL21;		$tot_C_TANGGAL31 += $data->TANGGAL31;
			$tot_C_TANGGAL2 += $data->TANGGAL2;		$tot_C_TANGGAL12 += $data->TANGGAL12;		$tot_C_TANGGAL22 += $data->TANGGAL22;
			$tot_C_TANGGAL3 += $data->TANGGAL3;		$tot_C_TANGGAL13 += $data->TANGGAL13;		$tot_C_TANGGAL23 += $data->TANGGAL23;
			$tot_C_TANGGAL4 += $data->TANGGAL4;		$tot_C_TANGGAL14 += $data->TANGGAL14;		$tot_C_TANGGAL24 += $data->TANGGAL24;
			$tot_C_TANGGAL5 += $data->TANGGAL5;		$tot_C_TANGGAL15 += $data->TANGGAL15;		$tot_C_TANGGAL25 += $data->TANGGAL25;
			$tot_C_TANGGAL6 += $data->TANGGAL6;		$tot_C_TANGGAL16 += $data->TANGGAL16;		$tot_C_TANGGAL26 += $data->TANGGAL26;
			$tot_C_TANGGAL7 += $data->TANGGAL7;		$tot_C_TANGGAL17 += $data->TANGGAL17;		$tot_C_TANGGAL27 += $data->TANGGAL27;
			$tot_C_TANGGAL8 += $data->TANGGAL8;		$tot_C_TANGGAL18 += $data->TANGGAL18;		$tot_C_TANGGAL28 += $data->TANGGAL28;
			$tot_C_TANGGAL9 += $data->TANGGAL9;		$tot_C_TANGGAL19 += $data->TANGGAL19;		$tot_C_TANGGAL29 += $data->TANGGAL29;
			$tot_C_TANGGAL0 += $data->TANGGAL0;		$tot_C_TANGGAL20 += $data->TANGGAL20;		$tot_C_TANGGAL30 += $data->TANGGAL30;

			/*$tot_C_THISMONTH = '';
			$tot_C_THISMONTHAMOUNT = '';*/
		}

		if($data->KETERANGAN == 'D.Stock'){
			$tot_D_lmonth += $data->LASTMONTH;			
			$tot_D_lmonthamount += $data->LASTMONTHAMOUNT;

			$tot_D_TANGGAL1 += $data->TANGGAL1;		$tot_D_TANGGAL11 += $data->TANGGAL11;		$tot_D_TANGGAL21 += $data->TANGGAL21;		$tot_D_TANGGAL31 += $data->TANGGAL31;
			$tot_D_TANGGAL2 += $data->TANGGAL2;		$tot_D_TANGGAL12 += $data->TANGGAL12;		$tot_D_TANGGAL22 += $data->TANGGAL22;
			$tot_D_TANGGAL3 += $data->TANGGAL3;		$tot_D_TANGGAL13 += $data->TANGGAL13;		$tot_D_TANGGAL23 += $data->TANGGAL23;
			$tot_D_TANGGAL4 += $data->TANGGAL4;		$tot_D_TANGGAL14 += $data->TANGGAL14;		$tot_D_TANGGAL24 += $data->TANGGAL24;
			$tot_D_TANGGAL5 += $data->TANGGAL5;		$tot_D_TANGGAL15 += $data->TANGGAL15;		$tot_D_TANGGAL25 += $data->TANGGAL25;
			$tot_D_TANGGAL6 += $data->TANGGAL6;		$tot_D_TANGGAL16 += $data->TANGGAL16;		$tot_D_TANGGAL26 += $data->TANGGAL26;
			$tot_D_TANGGAL7 += $data->TANGGAL7;		$tot_D_TANGGAL17 += $data->TANGGAL17;		$tot_D_TANGGAL27 += $data->TANGGAL27;
			$tot_D_TANGGAL8 += $data->TANGGAL8;		$tot_D_TANGGAL18 += $data->TANGGAL18;		$tot_D_TANGGAL28 += $data->TANGGAL28;
			$tot_D_TANGGAL9 += $data->TANGGAL9;		$tot_D_TANGGAL19 += $data->TANGGAL19;		$tot_D_TANGGAL29 += $data->TANGGAL29;
			$tot_D_TANGGAL0 += $data->TANGGAL0;		$tot_D_TANGGAL20 += $data->TANGGAL20;		$tot_D_TANGGAL30 += $data->TANGGAL30;

			$tot_D_THISMONTH += $data->THISMONTH;
			$tot_D_THISMONTHAMOUNT += $data->THISMONTHAMOUNT;
		}
	}else{
		$it_no = $data->ITEM_NO;
		$it_nm = $data->DESCRIPTION;

		if($data->KETERANGAN == 'A.Usage'){
			$data->LASTMONTH = '';
		 	$data->LASTMONTHAMOUNT = '';
		 	$data->THISMONTH = '';
			$data->THISMONTHAMOUNT = '';

			/*$tot_A_lmonth = 0;
			$tot_A_lmonthamount = '';*/

			$tot_A_TANGGAL1 += $data->TANGGAL1;		$tot_A_TANGGAL11 += $data->TANGGAL11;		$tot_A_TANGGAL21 += $data->TANGGAL21;		$tot_A_TANGGAL31 += $data->TANGGAL31;
			$tot_A_TANGGAL2 += $data->TANGGAL2;		$tot_A_TANGGAL12 += $data->TANGGAL12;		$tot_A_TANGGAL22 += $data->TANGGAL22;
			$tot_A_TANGGAL3 += $data->TANGGAL3;		$tot_A_TANGGAL13 += $data->TANGGAL13;		$tot_A_TANGGAL23 += $data->TANGGAL23;
			$tot_A_TANGGAL4 += $data->TANGGAL4;		$tot_A_TANGGAL14 += $data->TANGGAL14;		$tot_A_TANGGAL24 += $data->TANGGAL24;
			$tot_A_TANGGAL5 += $data->TANGGAL5;		$tot_A_TANGGAL15 += $data->TANGGAL15;		$tot_A_TANGGAL25 += $data->TANGGAL25;
			$tot_A_TANGGAL6 += $data->TANGGAL6;		$tot_A_TANGGAL16 += $data->TANGGAL16;		$tot_A_TANGGAL26 += $data->TANGGAL26;
			$tot_A_TANGGAL7 += $data->TANGGAL7;		$tot_A_TANGGAL17 += $data->TANGGAL17;		$tot_A_TANGGAL27 += $data->TANGGAL27;
			$tot_A_TANGGAL8 += $data->TANGGAL8;		$tot_A_TANGGAL18 += $data->TANGGAL18;		$tot_A_TANGGAL28 += $data->TANGGAL28;
			$tot_A_TANGGAL9 += $data->TANGGAL9;		$tot_A_TANGGAL19 += $data->TANGGAL19;		$tot_A_TANGGAL29 += $data->TANGGAL29;
			$tot_A_TANGGAL0 += $data->TANGGAL0;		$tot_A_TANGGAL20 += $data->TANGGAL20;		$tot_A_TANGGAL30 += $data->TANGGAL30;

			/*$tot_A_THISMONTH = '';
			$tot_A_THISMONTHAMOUNT = '';*/
		}
	}

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, $it_type)
                ->setCellValue('B'.$no, $it_no)
                ->setCellValue('C'.$no, $it_nm)
                ->setCellValue('D'.$no, $data->KETERANGAN)
                ->setCellValue('E'.$no, $data->LASTMONTH)
                ->setCellValue('F'.$no, $data->LASTMONTHAMOUNT)
                ->setCellValue('G'.$no, $data->TANGGAL1)
	            ->setCellValue('H'.$no, $data->TANGGAL2)
	            ->setCellValue('I'.$no, $data->TANGGAL3)
	            ->setCellValue('J'.$no, $data->TANGGAL4)
	            ->setCellValue('K'.$no, $data->TANGGAL5)
	            ->setCellValue('L'.$no, $data->TANGGAL6)
	            ->setCellValue('M'.$no, $data->TANGGAL7)
	            ->setCellValue('N'.$no, $data->TANGGAL8)
	            ->setCellValue('O'.$no, $data->TANGGAL9)
	            ->setCellValue('P'.$no, $data->TANGGAL0)
	            ->setCellValue('Q'.$no, $data->TANGGAL11)
	            ->setCellValue('R'.$no, $data->TANGGAL12)
	            ->setCellValue('S'.$no, $data->TANGGAL13)
	            ->setCellValue('T'.$no, $data->TANGGAL14)
	            ->setCellValue('U'.$no, $data->TANGGAL15)
	            ->setCellValue('V'.$no, $data->TANGGAL16)
	            ->setCellValue('W'.$no, $data->TANGGAL17)
	            ->setCellValue('X'.$no, $data->TANGGAL18)
	            ->setCellValue('Y'.$no, $data->TANGGAL19)
	            ->setCellValue('Z'.$no, $data->TANGGAL20)
	            ->setCellValue('AA'.$no, $data->TANGGAL21) 
	            ->setCellValue('AB'.$no, $data->TANGGAL22)
	            ->setCellValue('AC'.$no, $data->TANGGAL23)
	            ->setCellValue('AD'.$no, $data->TANGGAL24)
	            ->setCellValue('AE'.$no, $data->TANGGAL25)
	            ->setCellValue('AF'.$no, $data->TANGGAL26)
	            ->setCellValue('AG'.$no, $data->TANGGAL27)
	            ->setCellValue('AH'.$no, $data->TANGGAL28)
	            ->setCellValue('AI'.$no, $data->TANGGAL29)
	            ->setCellValue('AJ'.$no, $data->TANGGAL30)
	            ->setCellValue('AK'.$no, $data->TANGGAL31)
	            ->setCellValue('AL'.$no, $data->THISMONTH)
	            ->setCellValue('AM'.$no, $data->THISMONTHAMOUNT);

    $sheet = $objPHPExcel->getActiveSheet();

    if($data->KETERANGAN == 'D.Stock'){
		$sheet->getStyle('D'.$no.':AM'.$no)->applyFromArray(
	        array(
	            'fill' => array(
	                'type' => PHPExcel_Style_Fill::FILL_SOLID,
	                'color' => array('rgb' => 'CC9999')
	            )
	        )
	    );
	}

	$objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);


    $it_type = $data->ITEM;
    $it_no = $data->ITEM_NO;
    $it_nm = $data->DESCRIPTION;
    $noUrut = $noUrut;
    $no++;
}

$objPHPExcel->getActiveSheet()->mergeCells('A'.$no.':C'.$no);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, 'TOTAL')
            ->setCellValue('D'.$no, 'A.USAGE')
            ->setCellValue('E'.$no, $tot_A_lmonth)
            ->setCellValue('F'.$no, $tot_A_lmonthamount)
            ->setCellValue('G'.$no, $tot_A_TANGGAL1)
            ->setCellValue('H'.$no, $tot_A_TANGGAL2)
            ->setCellValue('I'.$no, $tot_A_TANGGAL3)
            ->setCellValue('J'.$no, $tot_A_TANGGAL4)
            ->setCellValue('K'.$no, $tot_A_TANGGAL5)
            ->setCellValue('L'.$no, $tot_A_TANGGAL6)
            ->setCellValue('M'.$no, $tot_A_TANGGAL7)
            ->setCellValue('N'.$no, $tot_A_TANGGAL8)
            ->setCellValue('O'.$no, $tot_A_TANGGAL9)
            ->setCellValue('P'.$no, $tot_A_TANGGAL0)
            ->setCellValue('Q'.$no, $tot_A_TANGGAL11)
            ->setCellValue('R'.$no, $tot_A_TANGGAL12)
            ->setCellValue('S'.$no, $tot_A_TANGGAL13)
            ->setCellValue('T'.$no, $tot_A_TANGGAL14)
            ->setCellValue('U'.$no, $tot_A_TANGGAL15)
            ->setCellValue('V'.$no, $tot_A_TANGGAL16)
            ->setCellValue('W'.$no, $tot_A_TANGGAL17)
            ->setCellValue('X'.$no, $tot_A_TANGGAL18)
            ->setCellValue('Y'.$no, $tot_A_TANGGAL19)
            ->setCellValue('Z'.$no, $tot_A_TANGGAL20)
            ->setCellValue('AA'.$no, $tot_A_TANGGAL21) 
            ->setCellValue('AB'.$no, $tot_A_TANGGAL22)
            ->setCellValue('AC'.$no, $tot_A_TANGGAL23)
            ->setCellValue('AD'.$no, $tot_A_TANGGAL24)
            ->setCellValue('AE'.$no, $tot_A_TANGGAL25)
            ->setCellValue('AF'.$no, $tot_A_TANGGAL26)
            ->setCellValue('AG'.$no, $tot_A_TANGGAL27)
            ->setCellValue('AH'.$no, $tot_A_TANGGAL28)
            ->setCellValue('AI'.$no, $tot_A_TANGGAL29)
            ->setCellValue('AJ'.$no, $tot_A_TANGGAL30)
            ->setCellValue('AK'.$no, $tot_A_TANGGAL31)
            ->setCellValue('AL'.$no, $tot_A_THISMONTH)
            ->setCellValue('AM'.$no, $tot_A_THISMONTHAMOUNT);

$sheet->getStyle('A'.$no.':C'.$no)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A'.$no.':AM'.$no)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'AAFFFF')
        )
    )
);

$objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$no++;

$objPHPExcel->getActiveSheet()->mergeCells('A'.$no.':C'.$no);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, 'TOTAL')
            ->setCellValue('D'.$no, 'B.PURCHASE')
            ->setCellValue('E'.$no, $tot_B_lmonth)
            ->setCellValue('F'.$no, $tot_B_lmonthamount)
            ->setCellValue('G'.$no, $tot_B_TANGGAL1)
            ->setCellValue('H'.$no, $tot_B_TANGGAL2)
            ->setCellValue('I'.$no, $tot_B_TANGGAL3)
            ->setCellValue('J'.$no, $tot_B_TANGGAL4)
            ->setCellValue('K'.$no, $tot_B_TANGGAL5)
            ->setCellValue('L'.$no, $tot_B_TANGGAL6)
            ->setCellValue('M'.$no, $tot_B_TANGGAL7)
            ->setCellValue('N'.$no, $tot_B_TANGGAL8)
            ->setCellValue('O'.$no, $tot_B_TANGGAL9)
            ->setCellValue('P'.$no, $tot_B_TANGGAL0)
            ->setCellValue('Q'.$no, $tot_B_TANGGAL11)
            ->setCellValue('R'.$no, $tot_B_TANGGAL12)
            ->setCellValue('S'.$no, $tot_B_TANGGAL13)
            ->setCellValue('T'.$no, $tot_B_TANGGAL14)
            ->setCellValue('U'.$no, $tot_B_TANGGAL15)
            ->setCellValue('V'.$no, $tot_B_TANGGAL16)
            ->setCellValue('W'.$no, $tot_B_TANGGAL17)
            ->setCellValue('X'.$no, $tot_B_TANGGAL18)
            ->setCellValue('Y'.$no, $tot_B_TANGGAL19)
            ->setCellValue('Z'.$no, $tot_B_TANGGAL20)
            ->setCellValue('AA'.$no, $tot_B_TANGGAL21)
            ->setCellValue('AB'.$no, $tot_B_TANGGAL22)
            ->setCellValue('AC'.$no, $tot_B_TANGGAL23)
            ->setCellValue('AD'.$no, $tot_B_TANGGAL24)
            ->setCellValue('AE'.$no, $tot_B_TANGGAL25)
            ->setCellValue('AF'.$no, $tot_B_TANGGAL26)
            ->setCellValue('AG'.$no, $tot_B_TANGGAL27)
            ->setCellValue('AH'.$no, $tot_B_TANGGAL28)
            ->setCellValue('AI'.$no, $tot_B_TANGGAL29)
            ->setCellValue('AJ'.$no, $tot_B_TANGGAL30)
            ->setCellValue('AK'.$no, $tot_B_TANGGAL31)
            ->setCellValue('AL'.$no, $tot_B_THISMONTH)
            ->setCellValue('AM'.$no, $tot_B_THISMONTHAMOUNT);

$sheet->getStyle('A'.$no.':C'.$no)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A'.$no.':AM'.$no)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'AAFFFF')
        )
    )
);

$objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$no++;

$objPHPExcel->getActiveSheet()->mergeCells('A'.$no.':C'.$no);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, 'TOTAL')
            ->setCellValue('D'.$no, 'C.MUTATION')
            ->setCellValue('E'.$no, $tot_C_lmonth)
            ->setCellValue('F'.$no, $tot_C_lmonthamount)
            ->setCellValue('G'.$no, $tot_C_TANGGAL1)
            ->setCellValue('H'.$no, $tot_C_TANGGAL2)
            ->setCellValue('I'.$no, $tot_C_TANGGAL3)
            ->setCellValue('J'.$no, $tot_C_TANGGAL4)
            ->setCellValue('K'.$no, $tot_C_TANGGAL5)
            ->setCellValue('L'.$no, $tot_C_TANGGAL6)
            ->setCellValue('M'.$no, $tot_C_TANGGAL7)
            ->setCellValue('N'.$no, $tot_C_TANGGAL8)
            ->setCellValue('O'.$no, $tot_C_TANGGAL9)
            ->setCellValue('P'.$no, $tot_C_TANGGAL0)
            ->setCellValue('Q'.$no, $tot_C_TANGGAL11)
            ->setCellValue('R'.$no, $tot_C_TANGGAL12)
            ->setCellValue('S'.$no, $tot_C_TANGGAL13)
            ->setCellValue('T'.$no, $tot_C_TANGGAL14)
            ->setCellValue('U'.$no, $tot_C_TANGGAL15)
            ->setCellValue('V'.$no, $tot_C_TANGGAL16)
            ->setCellValue('W'.$no, $tot_C_TANGGAL17)
            ->setCellValue('X'.$no, $tot_C_TANGGAL18)
            ->setCellValue('Y'.$no, $tot_C_TANGGAL19)
            ->setCellValue('Z'.$no, $tot_C_TANGGAL20)
            ->setCellValue('AA'.$no,$tot_C_TANGGAL21)
            ->setCellValue('AB'.$no, $tot_C_TANGGAL22)
            ->setCellValue('AC'.$no, $tot_C_TANGGAL23)
            ->setCellValue('AD'.$no, $tot_C_TANGGAL24)
            ->setCellValue('AE'.$no, $tot_C_TANGGAL25)
            ->setCellValue('AF'.$no, $tot_C_TANGGAL26)
            ->setCellValue('AG'.$no, $tot_C_TANGGAL27)
            ->setCellValue('AH'.$no, $tot_C_TANGGAL28)
            ->setCellValue('AI'.$no, $tot_C_TANGGAL29)
            ->setCellValue('AJ'.$no, $tot_C_TANGGAL30)
            ->setCellValue('AK'.$no, $tot_C_TANGGAL31)
            ->setCellValue('AL'.$no, $tot_C_THISMONTH)
            ->setCellValue('AM'.$no, $tot_C_THISMONTHAMOUNT);

$sheet->getStyle('A'.$no.':C'.$no)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A'.$no.':AM'.$no)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'AAFFFF')
        )
    )
);

$objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$no++;

$objPHPExcel->getActiveSheet()->mergeCells('A'.$no.':C'.$no);
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$no, 'TOTAL')
            ->setCellValue('D'.$no, 'D.STOCK')
            ->setCellValue('E'.$no, $tot_D_lmonth)
            ->setCellValue('F'.$no, $tot_D_lmonthamount)
            ->setCellValue('G'.$no, $tot_D_TANGGAL1)
            ->setCellValue('H'.$no, $tot_D_TANGGAL2)
            ->setCellValue('I'.$no, $tot_D_TANGGAL3)
            ->setCellValue('J'.$no, $tot_D_TANGGAL4)
            ->setCellValue('K'.$no, $tot_D_TANGGAL5)
            ->setCellValue('L'.$no, $tot_D_TANGGAL6)
            ->setCellValue('M'.$no, $tot_D_TANGGAL7)
            ->setCellValue('N'.$no, $tot_D_TANGGAL8)
            ->setCellValue('O'.$no, $tot_D_TANGGAL9)
            ->setCellValue('P'.$no, $tot_D_TANGGAL0)
            ->setCellValue('Q'.$no, $tot_D_TANGGAL11)
            ->setCellValue('R'.$no, $tot_D_TANGGAL12)
            ->setCellValue('S'.$no, $tot_D_TANGGAL13)
            ->setCellValue('T'.$no, $tot_D_TANGGAL14)
            ->setCellValue('U'.$no, $tot_D_TANGGAL15)
            ->setCellValue('V'.$no, $tot_D_TANGGAL16)
            ->setCellValue('W'.$no, $tot_D_TANGGAL17)
            ->setCellValue('X'.$no, $tot_D_TANGGAL18)
            ->setCellValue('Y'.$no, $tot_D_TANGGAL19)
            ->setCellValue('Z'.$no, $tot_D_TANGGAL20)
            ->setCellValue('AA'.$no,$tot_D_TANGGAL21)
            ->setCellValue('AB'.$no, $tot_D_TANGGAL22)
            ->setCellValue('AC'.$no, $tot_D_TANGGAL23)
            ->setCellValue('AD'.$no, $tot_D_TANGGAL24)
            ->setCellValue('AE'.$no, $tot_D_TANGGAL25)
            ->setCellValue('AF'.$no, $tot_D_TANGGAL26)
            ->setCellValue('AG'.$no, $tot_D_TANGGAL27)
            ->setCellValue('AH'.$no, $tot_D_TANGGAL28)
            ->setCellValue('AI'.$no, $tot_D_TANGGAL29)
            ->setCellValue('AJ'.$no, $tot_D_TANGGAL30)
            ->setCellValue('AK'.$no, $tot_D_TANGGAL31)
            ->setCellValue('AL'.$no, $tot_D_THISMONTH)
            ->setCellValue('AM'.$no, $tot_D_THISMONTHAMOUNT);

$sheet->getStyle('A'.$no.':C'.$no)->getAlignment()->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
);

$sheet->getStyle('A'.$no.':AM'.$no)->applyFromArray(
    array(
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'AAFFFF')
        )
    )
);

$objPHPExcel->getActiveSheet()->getStyle('E'.$no.':AM'.$no)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('RM INVENTORY '.$this_month);
 
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

@ob_start();
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

$objWriter->save("php://output");
$dataXLS = @ob_get_contents();
@ob_end_clean();

date_default_timezone_set('Etc/UTC');
require_once '../../class/PHPMailer/PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "virus.fdk.co.jp";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
//$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "do.not.reply.fdkindonesia";
//Password to use for SMTP authentication
$mail->Password = "fidonot";
//Set who the message is to be sent from
$mail->setFrom('do.not.reply.fdkindonesia@fdk.co.jp', 'FDK INDONESIA');

//Set who the message is to be sent to 


/*$mail->addAddress('agusman@fdk.co.jp', 'Agusman');
$mail->addAddress('atsushi.sohara@fdk.co.jp', 'atsushi sohara');
$mail->addAddress('kamiya@fdk.co.jp', 'kamiya');
$mail->addAddress('ari_harari@fdk.co.jp', 'Ari Harari');
$mail->addAddress('victor.antonio@fdk.co.jp', 'Victor antonio');
$mail->addAddress('lutfi.sulthony@fdk.co.jp', 'Lutfi Sulthony');
$mail->addAddress('riani.ayu@fdk.co.jp', 'Riani Ayu');
$mail->addAddress('reza@fdk.co.jp', 'Reza Vebrian');*/
$mail->addAddress('ueng.hernama@fdk.co.jp', 'Ueng Hernama');
/*$mail->addAddress('wakuda_nobuyuki@fdk.co.jp', 'Wakuda Nobuyuki');
$mail->addAddress('Nakata@fdk.co.jp', 'Nakata');
$mail->addAddress('christian.alexander@fdk.co.jp', 'Christian Alexander');
$mail->addAddress('yoga.kristianto@fdk.co.jp', 'Yoga Kristianto');
$mail->addAddress('agung.mardiansyah@fdk.co.jp', 'Agung Mardiansyah');
$mail->addAddress('leslie.avanti@fdk.co.jp', 'Leslie Avanti');*/


//Set the subject line
$mail->Subject = 'RM INVENTORY DAILY REPORT';
$mail->AddEmbeddedImage("../../images/logo-print4.png", "my-attach", "../../images/logo-print4.png");

$min_date = date('Y-m-d');//strtotime('-1 day',strtotime());
$on_date = date("l, F d, Y");
$plan_date = intval(date("d",$min_date));
$plan = "plan_".$plan_date;
$tot_plan = "total_plan_".$plan_date;
$actual = "actual_".$plan_date;
$tot_actual = "total_actual_".$plan_date;
$month1 = date('M');

$message = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDK-ASSEMBLY REPORT</title>
  <style>
	table {
	    border-collapse: collapse;
	}

	table, td, th {
	    border: 1px solid black;
	}
  </style>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 14px;">
	<h4>Dear All,</h4>
 		<div>
		 <p>Summary Information of Assembling</p>
	  	 <table>
			<tr>
		       <th style="background-color: #D2D2D2;width: 70px;" align="center">CELL</th>
		       <th style="background-color: #D2D2D2;width: 100px;" align="center">WORKING DAYS</th>
		       <th style="background-color: #D2D2D2;width: 150px;" align="center">TOTAL PLAN '.strtoupper(date('F')).' </th>
		       <th style="background-color: #D2D2D2;width: 150px;" align="center">DAILY PRODUCTION</th>
		    </tr>';  

$sql3 = "select cell, workingdays, dailyproduction, total from zvw_Summary_Asy_Plan where bulan = 12 and tahun = 2017";
$result3 = oci_parse($connect, $sql3);
oci_execute($result3);
$no2=1;
while ($data_cek2=oci_fetch_array($result3)){
	$print_gp ='
			        <td style="background-color: #E2EFDA;">'.$data_cek2[0].'</td>
			        <td style="background-color: #E2EFDA;" align="center">'.$data_cek2[1].'</td>
			        <td style="background-color: #E2EFDA;" align="right">'.number_format($data_cek2[3]).'</td>
			        <td style="background-color: #E2EFDA;" align="right">'.number_format($data_cek2[2]).'</td>
			';
	$message .= '<tr>'.$print_gp.'</tr> ';
}

$message .= '</table> </div> </p> <p>Please see the result Actual Inventory VS  Target Inventory also details in attached file.<br/>  
  </p>';

$message .='
  <div>
  	  <p>Inventory For '.$on_date.'</p>
  	  <p>(Target Based on Assembling Plan)</p>
	  <table>
	  	<tr>
	       <th style="background-color: #D2D2D2;width: 40px;" align="center">NO.</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">ITEM CATEGORY</th>
	       <th style="background-color: #D2D2D2;width: 70px;" align="center">MIN ITO DAYS</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">MIN TARGET STOCK</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">MIN TARGET AMOUNT</th>
	       <th style="background-color: #D2D2D2;width: 70px;" align="center">MAX ITO DAYS</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">MAX TARGET STOCK</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">MAX TARGET AMOUNT</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">ACTUAL STOCK</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">ACTUAL AMOUNT</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">STOCK DIFFERENCE</th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">AMOUNT DIFFERENCE</th>
	       <th style="background-color: #D2D2D2;width: 150px;" align="center">REMARK   </th>
	       <th style="background-color: #D2D2D2;width: 100px;" align="center">AMOUNT VARIANCE (%)</th>
	    </tr>';

$sql2 = "select zz.item1 as item, 
       cast(thismonth as decimal(18,2)) ActualQty,
       cast(actualamount as decimal(18,2)) ActualAmount,

       max_TargetQty,  
       cast(z.max_TargetAmount as decimal(18,2)) max_TargetAmount ,
       case when actualamount <= max_TargetAmount and actualamount >= min_TargetAmount then 'ACHIEVED' else
        CASE When actualamount > max_TargetAmount Then 'OVER' ELSE 'SHORTAGE' END END REMARK,
       z.Max_ITO,
       min_TargetQty,  
       cast(z.min_TargetAmount as decimal(18,2)) min_TargetAmount ,
       z.ito_days_min
       
       
from zvw_material_item_view_this zz
inner join (

select item, 
       ito_days Max_ITO,
       case when item = 'METAL LABEL' Then (select sum(total)  from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year.")
       Else
       LR6 * ito_days *(select (dailyproduction/ 1000)   from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR01') +
       LR3 * ito_days *(select (dailyproduction/ 1000)  from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR03') +
       LR1 * ito_days *(select (dailyproduction/ 1000)  from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR06' ) end  as Max_TargetQty,
       case when item = 'METAL LABEL' Then amount * (select sum(total)  from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year.")
       Else
       amount * LR6 * ito_days *(select (dailyproduction/ 1000)    from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR01') +
       amount * LR3 * ito_days *(select (dailyproduction/ 1000)   from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR03') +
       amount * LR1 * ito_days *(select (dailyproduction/ 1000)   from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR06' )  end as Max_TargetAmount,
       ito_days_min,
         case when item = 'METAL LABEL' Then (select sum(total)/2  from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year.")
       Else
       LR6 * ito_days_min *(select (dailyproduction/ 1000)   from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR01') +
       LR3 * ito_days_min *(select (dailyproduction/ 1000)  from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR03') +
       LR1 * ito_days_min *(select (dailyproduction/ 1000)  from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR06' ) end  as MIN_TargetQty,
       case when item = 'METAL LABEL' Then amount * (select sum(total) /2 from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year.")
       Else
       amount * LR6 * ito_days_min *(select (dailyproduction/ 1000)    from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR01') +
       amount * LR3 * ito_days_min *(select (dailyproduction/ 1000)   from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR03') +
       amount * LR1 * ito_days_min *(select (dailyproduction/ 1000)   from zvw_Summary_Asy_Plan where bulan = ".intval($th_mon)." and tahun = ".$this_year." and cell = 'LR06' )  end as MIN_TargetAmount

from ztb_material_target2

)z
on zz.item1 = z.item
order by Max_TargetAmount desc,ITEM";

$result2 = oci_parse($connect, $sql2);
oci_execute($result2);
$no2=1;  $t_plan2 = 0;  $t_act2 = 0;   $t_gap2 = 0;
while ($data_cek2=oci_fetch_array($result2)){
	$gp2 = $data_cek2[5] ;
	$Variance1 = intval(number_format($data_cek2[2]) / number_format($data_cek2[4])*100) -100;
	// if ($variance1 < 0)
	// 	{
	// 		$variance1  = 200 + $variance1;
	// 	} 	  
	$difAmount = number_format($data_cek2[2]-$data_cek2[4]);
	$difStock = number_format($data_cek2[1]-$data_cek2[3]);
	if($gp2 == "ACHIEVED"){
		$print_gp ="<td style='background-color: #00D4FF;'>".$no2++."</td>
			        <td style='background-color: #00D4FF;'>".$data_cek2[0]."</td>
			        <td style='background-color: #00D4FF;'>".$data_cek2[9]."</td>
			        <td style='background-color: #00D4FF;' align='right'>".number_format($data_cek2[7])."</td>
			        <td style='background-color: #00D4FF;' align='right'>".number_format($data_cek2[8])."</td>
			        <td style='background-color: #00D4FF;'>".$data_cek2[6]."</td>
			        <td style='background-color: #00D4FF;' align='right'>".number_format($data_cek2[3])."</td>
			        <td style='background-color: #00D4FF;' align='right'>".number_format($data_cek2[4])."</td>
			        <td style='background-color: #00D4FF;' align='right'>".number_format($data_cek2[1])."</td>
			        <td style='background-color: #00D4FF;' align='right'>".number_format($data_cek2[2])."</td>
			        <td style='background-color: #00D4FF;' align='right'>".$difStock."</td>
			        <td style='background-color: #00D4FF;' align='right'>".$difAmount."</td>
			        <td style='background-color: #00D4FF;' align='center'>".$gp2."</td>
			        <td style='background-color: #00D4FF;' align='right'>".$Variance1."%</td>"
			        ;
	}elseif ($gp2 == "SHORTAGE"){
		$Variance1 = $Variance1 *-1;
		$print_gp ="<td style='background-color: #FF0055;'>".$no2++."</td>
			        <td style='background-color: #FF0055;'>".$data_cek2[0]."</td>
			        <td style='background-color: #FF0055;'>".$data_cek2[9]."</td>
			        <td style='background-color: #FF0055;' align='right'>".number_format($data_cek2[7])."</td>
			        <td style='background-color: #FF0055;' align='right'>".number_format($data_cek2[8])."</td>
			        <td style='background-color: #FF0055;'>".$data_cek2[6]."</td>
			        <td style='background-color: #FF0055;' align='right'>".number_format($data_cek2[3])."</td>
			        <td style='background-color: #FF0055;' align='right'>".number_format($data_cek2[4])."</td>
			        <td style='background-color: #FF0055;' align='right'>".number_format($data_cek2[1])."</td>
			        <td style='background-color: #FF0055;' align='right'>".number_format($data_cek2[2])."</td>
			        <td style='background-color: #FF0055;' align='right'>".$difStock."</td>
					<td style='background-color: #FF0055;' align='right'>".$difAmount."</td>
			        <td style='background-color: #FF0055;' align='center'>".$gp2."</td>
			        <td style='background-color: #FF0055;' align='right'>".$Variance1."%</td>";
	}elseif ($gp2 == "OVER"){
		$print_gp ="<td style='background-color: #2AFF00;'>".$no2++."</td>
			        <td style='background-color: #2AFF00;'>".$data_cek2[0]."</td>
			        <td style='background-color: #2AFF00;'>".$data_cek2[9]."</td>
			        <td style='background-color: #2AFF00;' align='right'>".number_format($data_cek2[7])."</td>
			        <td style='background-color: #2AFF00;' align='right'>".number_format($data_cek2[8])."</td>
			        <td style='background-color: #2AFF00;'>".$data_cek2[6]."</td>
			        <td style='background-color: #2AFF00;' align='right'>".number_format($data_cek2[3])."</td>
			        <td style='background-color: #2AFF00;' align='right'>".number_format($data_cek2[4])."</td>
			        <td style='background-color: #2AFF00;' align='right'>".number_format($data_cek2[1])."</td>
			        <td style='background-color: #2AFF00;' align='right'>".number_format($data_cek2[2])."</td>
			        <td style='background-color: #2AFF00;' align='right'>".$difStock."</td>
					<td style='background-color: #2AFF00;' align='right'>".$difAmount."</td>
			        <td style='background-color: #2AFF00;' align='center'>".$gp2."</td>
			        <td style='background-color: #2AFF00;' align='right'>".$Variance1."%</td>";
	}

	$message .= '<tr>'.$print_gp.'</tr>';

	$t_plan2 += $data_cek2[2];
	$t_act2 += $data_cek2[4];
	$t_act2x += $data_cek2[8];
	$t_gap2 += $gp2;
	$TotaalDiffStock += $difStock;
}

$Variance2 = (intval( $t_plan2/$t_act2  *100) -100 )*-1;
$Variance1 = intval(number_format($data_cek2[2]) / number_format($data_cek2[4])*100) - 100;
	 


if($t_plan2 > $t_act2){
	$tot_gap2 = '<td style="background-color: #D2D2D2;color: red;" align="center">OVER</td>';
}
else{
	$tot_gap2 = '<td style="background-color: #D2D2D2;color: blue;" align="center">ACHIEVED</td>';
}

$TotaalDiffAmount = number_format($t_plan2-$t_act2);

$message.= '<tr>
		        <td colspan="4" style="background-color: #D2D2D2;" align="center"><b>TOTAL</b></td>
		        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_act2x).'</b></td>
		        <td style="background-color: #D2D2D2;"></td>
		        <td style="background-color: #D2D2D2;"></td>
		        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_act2).'</b></td>
		        <td style="background-color: #D2D2D2;"></td>
		        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_plan2).'</b></td>
				<td style="background-color: #D2D2D2;"></td>
		        <td style="background-color: #D2D2D2;" align="right"><b>'.$TotaalDiffAmount.'</b></td>
		        '.$tot_gap2.'
		        <td style="background-color: #D2D2D2;" align="right"><b>'.$Variance2 .' %</b></td>
      		</tr>
    	</table>
  	</div>';
$message .='<br/><br/>';

$message.='
   <div>
     <table>
       <tr>
         <th style="background-color: #D2D2D2;width: 40px;" align="center">NO.</th>
         <th style="background-color: #D2D2D2;width: 80px;" align="center">ITEM NO.</th>
         <th style="background-color: #D2D2D2;width: 250px;" align="center">DESCRIPTION</th>
         <th style="background-color: #D2D2D2;width: 100px;" align="center">INVENTORY</th>
         <th style="background-color: #D2D2D2;width: 100px;" align="center">DAILY NEEDS</th>
         <th style="background-color: #D2D2D2;width: 80px;" align="center">MIN ITO DAYS</th>
         <th style="background-color: #D2D2D2;width: 80px;" align="center">ACTUAL ITO DAYS</th>
         <th style="background-color: #D2D2D2;width: 80px;" align="center">MAX ITO DAYS</th>
         <th style="background-color: #D2D2D2;width: 150px;" align="center">NEXT ARRIVAL</th>
         <th style="background-color: #D2D2D2;width: 150px;" align="center">REMARK</th>
       </tr>';

$sqlz = "select z.item_no, 
         i.description,
         this_inventory,
         cast ( sum(zzz.qty * z.konversi /1000) / 
            (select count(distinct ztb_assy_plan.tanggal) from ztb_assy_plan where tahun||bulan = ( select distinct this_month  from whinventory))   
            as integer) DailyNeeds,  
   
       
        min_days,
         cast ( this_inventory / 
         (cast ( sum(zzz.qty * z.konversi /1000) / 
            (select count(distinct ztb_assy_plan.tanggal) from ztb_assy_plan where tahun||bulan = ( select distinct this_month  from whinventory))   
            as integer)) as integer) actual_days,
        max_days,
        ( select ETA||' / '||qty from po_details  where item_no = z.item_no and trim(ETA) > (select sysdate from dual) and rownum = 1) Next_Arrival,
        case when  cast ( this_inventory / 
         (cast ( sum(zzz.qty * z.konversi /1000) / 
            (select count(distinct ztb_assy_plan.tanggal) from ztb_assy_plan where tahun||bulan = ( select distinct this_month  from whinventory))   
            as integer)) as integer) < min_days then 'Shortage' Else
              case when  cast ( this_inventory / 
         (cast ( sum(zzz.qty * z.konversi /1000) / 
            (select count(distinct ztb_assy_plan.tanggal) from ztb_assy_plan where tahun||bulan = ( select distinct this_month  from whinventory))   
            as integer)) as integer) > max_days then 'Over' else 'Achieved' End End Status
  from ztb_material_konversi z
  inner join item i
  on z.item_no = i.item_no
  inner join ztb_config_rm zz
  on z.item_no = zz.item_no
  inner join whinventory w
  on w.item_no = z.item_no
  left outer join  ztb_assy_plan   zzz
  on z.assy_line = zzz.assy_line and z.cell_type = zzz.cell_type
  where   zzz.tahun||bulan = ( select distinct this_month  from whinventory) 
          and revisi = (select max(revisi)  from ztb_assy_plan where tahun||bulan = ( select distinct this_month  from whinventory))
          --and z.item_no in (1170113,1170130)
  group by item,z.item_no,this_inventory, min_days,i.description,
        max_days
        order by item";
 $resultz = oci_parse($connect, $sqlz);
 oci_execute($resultz);
 $noz=1;
 while ($data_cekz=oci_fetch_object($resultz)){
 	$arrv = $data_cekz->NEXT_ARRIVAL;

 	if(! is_null($arrv)){
 		$exp_arrv = explode('/', $arrv);
 		$arrvNya = $exp_arrv[0]. '<br/>('.number_format($exp_arrv[1]).')';
 	}else{
 		$arrvNya = '-';
 	}

 	if(strtoupper($data_cekz->STATUS) == "ACHIEVED") {
 		$message .="<tr>
		 				<td style='background-color: #00D4FF;'>".$noz++."</td>
		 			    <td style='background-color: #00D4FF;' align='center'>".$data_cekz->ITEM_NO."</td>
		 			    <td style='background-color: #00D4FF;'>".$data_cekz->DESCRIPTION."</td>
		 			    <td style='background-color: #00D4FF;' align='right'>".number_format($data_cekz->THIS_INVENTORY)."&nbsp;</td>
		 			    <td style='background-color: #00D4FF;' align='right'>".number_format($data_cekz->DAILYNEEDS)."&nbsp;</td>
		 			    <td style='background-color: #00D4FF;' align='right'>".number_format($data_cekz->MIN_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #00D4FF;' align='right'>".number_format($data_cekz->ACTUAL_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #00D4FF;' align='right'>".number_format($data_cekz->MAX_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #00D4FF;'>".$arrvNya."</td>
		 			    <td style='background-color: #00D4FF;  align='center'>".strtoupper($data_cekz->STATUS)."</td>
	 			    </tr>";
 	}elseif(strtoupper($data_cekz->STATUS) == "SHORTAGE") {
 		$message .="<tr>
		 				<td style='background-color: #FF0055;'>".$noz++."</td>
		 			    <td style='background-color: #FF0055;' align='center'>".$data_cekz->ITEM_NO."</td>
		 			    <td style='background-color: #FF0055;'>".$data_cekz->DESCRIPTION."</td>
		 			    <td style='background-color: #FF0055;' align='right'>".number_format($data_cekz->THIS_INVENTORY)."&nbsp;</td>
		 			    <td style='background-color: #FF0055;' align='right'>".number_format($data_cekz->DAILYNEEDS)."&nbsp;</td>
		 			    <td style='background-color: #FF0055;' align='right'>".number_format($data_cekz->MIN_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #FF0055;  align='right'>".number_format($data_cekz->ACTUAL_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #FF0055;' align='right'>".number_format($data_cekz->MAX_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #FF0055;'>".$arrvNya."</td>
		 			    <td style='background-color: #FF0055;  align='center'>".strtoupper($data_cekz->STATUS)."</td>
	 			    </tr>";
 	}elseif (strtoupper($data_cekz->STATUS) == "OVER") {
 		$message .="<tr>
		 				<td style='background-color: #2AFF00;'>".$noz++."</td>
		 			    <td style='background-color: #2AFF00;' align='center'>".$data_cekz->ITEM_NO."</td>
		 			    <td style='background-color: #2AFF00;'>".$data_cekz->DESCRIPTION."</td>
		 			    <td style='background-color: #2AFF00;' align='right'>".number_format($data_cekz->THIS_INVENTORY)."&nbsp;</td>
		 			    <td style='background-color: #2AFF00;' align='right'>".number_format($data_cekz->DAILYNEEDS)."&nbsp;</td>
		 			    <td style='background-color: #2AFF00;' align='right'>".number_format($data_cekz->MIN_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #2AFF00;  align='right'>".number_format($data_cekz->ACTUAL_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #2AFF00;' align='right'>".number_format($data_cekz->MAX_DAYS)."&nbsp;</td>
		 			    <td style='background-color: #2AFF00;'>".$arrvNya."</td>
		 			    <td style='background-color: #2AFF00;  align='center'>".strtoupper($data_cekz->STATUS)."</td>
	 			    </tr>";
 	}
}

$message.='
</table>
<p>Do not reply this email.<br/><br/><br/>
Thanks and Regards,<br/>
<img src="cid:my-attach" width="400" height="80"/></p>';
$message.='
		</div>
	</body>
</html>';

$mail->msgHTML($message);

//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

$mail->AddStringAttachment($dataXLS, "RM INVENTORY ".$this_month.".xls");

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}else{
    echo "Message sent!";
    echo "<script>window.onload = self.close();</script>";
}