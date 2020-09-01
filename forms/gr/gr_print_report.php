<?php
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");

$YM = isset($_REQUEST['YM']) ? strval($_REQUEST['YM']) : '';
$YMF = isset($_REQUEST['YMF']) ? strval($_REQUEST['YMF']) : '';
$IP = isset($_REQUEST['IP']) ? strval($_REQUEST['IP']) : '';

$ip_res = '';
if ($IP != ''){
    $ip_res = "and z.package_type in ('$IP')";
}

$q_date = "SELECT DAY(Eomonth(cast('".$YM."01' AS DATE))) AS JUM_HARI,
    RIGHT(CONVERT(nvarchar(10), cast('".$YM."01' AS DATE), 103),8) as M_HARI";
$data = sqlsrv_query ($connect, strtoupper($q_date));
$dt = sqlsrv_fetch_object($data);
$t = intval($dt->JUM_HARI);
$format_dt = $dt->M_HARI;

$r_aRR = array();
$arrHr = array ('K','L','M','N','O','P','Q','R','S','T',
                'U','V','W','X','Y','Z','AA','AB','AC','AD',
                'AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO');

function s_day($a){
    $date = str_replace('/', '-', $a);
    return strtoupper(date('D', strtotime($date)));
}

/* START CONTENT ATACHMENT*/
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

// Get the contents of the JSON file 
$data = file_get_contents("packaging_plan_download_result.json");
$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$someArray = json_decode($str,true);

$data2 = file_get_contents("packaging_plan_download_result_details.json");
$dt2 = json_decode(json_encode($data2));
$str2 = preg_replace('/\\\\\"/',"\"", $dt2);
$someArray2 = json_decode($str2,true);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// --------------------------------------------------------------------
$noRow = 1;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'PACKAGING PLAN');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':J'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':J'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'PACKAGING GROUPING')
            ->setCellValue('B'.$noRow, 'WO NO.')
            ->setCellValue('C'.$noRow, 'ITEM_NO')
            ->setCellValue('D'.$noRow, 'ITEM NAME')
            ->setCellValue('E'.$noRow, 'DATE CODE')
            ->setCellValue('F'.$noRow, 'BATTERY TYPE')
            ->setCellValue('G'.$noRow, 'GRADE')
            ->setCellValue('H'.$noRow, 'QTY ORDER (PCS)')
            ->setCellValue('I'.$noRow, 'CR DATE')
            ->setCellValue('J'.$noRow, 'OT');
            
$tgl = 1;
for ($i=0;$i<$t; $i++) { 
    $tglR = $tgl< 10 ? '0'.$tgl : $tgl;
    $n_hari = s_day($tglR.$format_dt);
    $r_aRR[$i] = array('KOLOM_DATE' => $tglR.$format_dt, 'KOLOM_URUT' => $arrHr[$i], 'KOLOM_HARI' => $n_hari);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue($arrHr[$i].$noRow, $tglR.$format_dt)
    ;

    if ($n_hari == 'SAT' OR $n_hari == 'SUN'){
        $sheet->getStyle($arrHr[$i].$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFD4AA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
    }else{
        $sheet->getStyle($arrHr[$i].$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'AAFFFF')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
    }

    $tgl++;
}

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A'.$noRow.':J'.$noRow)->applyFromArray(
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

$fp = fopen('packaging_plan_download_result_table.json', 'w');
fwrite($fp, json_encode($r_aRR));
fclose($fp);

$data3 = file_get_contents("packaging_plan_download_result_table.json");
$dt3 = json_decode(json_encode($data3));
$str3 = preg_replace('/\\\\\"/',"\"", $dt3);
$someArray3 = json_decode($str3,true);

$noRow++;
$PCK_GRP = '';      $PCK_GRP_TEMP = '';
$sumA = '';         $sumB = '';
$hitung_Arr_hari = 0;
foreach ($someArray as $key => $value) {
    $PCK_GRP = $value['LABEL_TYPE'];
    if($noRow == 5){
        $sumA = $noRow;
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$noRow, $value['LABEL_TYPE'])
                    ->setCellValue('B'.$noRow, $value['WORK_ORDER'])
                    ->setCellValue('C'.$noRow, $value['ITEM_NO'])
                    ->setCellValue('D'.$noRow, $value['ITEM_NAME'])
                    ->setCellValue('E'.$noRow, $value['DATE_CODE'])
                    ->setCellValue('F'.$noRow, $value['BATTERY_TYPE'])
                    ->setCellValue('G'.$noRow, $value['CELL_GRADE'])
                    ->setCellValue('H'.$noRow, $value['QTY'])
                    ->setCellValue('I'.$noRow, $value['CR_DATE'])
                    ->setCellValue('J'.$noRow, $value['OPERATION_TIME']);
        $sheet->getStyle('A'.$noRow.':J'.$noRow)->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
    }else{
        if ($PCK_GRP != $PCK_GRP_TEMP){
            // TOTAL
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$noRow, 'TOTAL');
            $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':J'.$noRow);
            
            $sumB = $noRow-1;
            
            for ($tot_i=0; $tot_i < 31; $tot_i++) { 
                $xz = '=SUM('.$arrHr[$tot_i].$sumA.':'.$arrHr[$tot_i].$sumB.')';
                $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($arrHr[$tot_i].$noRow, $xz);
                $sheet->getStyle($arrHr[$tot_i].$noRow)->applyFromArray(
                    array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );
            }

            $sheet->getStyle('A'.$noRow.':AO'.$noRow)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'D0E1F9')
                    )
                )
            );

            $sumA = '';
            $noRow++;
            // ==================================================
            
            // MANPOWER CALCULATE ===============================
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$noRow, 'MANPOWER CALCULATE');
            
            $sheet->getStyle('A'.$noRow.':AO'.$noRow)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'F4F4F8')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
            $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':J'.$noRow);
            $noRow++;
            $sumA = $noRow;
            // ==================================================

            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$noRow, $value['LABEL_TYPE'])
                        ->setCellValue('B'.$noRow, $value['WORK_ORDER'])
                        ->setCellValue('C'.$noRow, $value['ITEM_NO'])
                        ->setCellValue('D'.$noRow, $value['ITEM_NAME'])
                        ->setCellValue('E'.$noRow, $value['DATE_CODE'])
                        ->setCellValue('F'.$noRow, $value['BATTERY_TYPE'])
                        ->setCellValue('G'.$noRow, $value['CELL_GRADE'])
                        ->setCellValue('H'.$noRow, $value['QTY'])
                        ->setCellValue('I'.$noRow, $value['CR_DATE'])
                        ->setCellValue('J'.$noRow, $value['OPERATION_TIME']);
            $sheet->getStyle('A'.$noRow.':J'.$noRow)->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
        }else{
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$noRow, $value['LABEL_TYPE'])
                        ->setCellValue('B'.$noRow, $value['WORK_ORDER'])
                        ->setCellValue('C'.$noRow, $value['ITEM_NO'])
                        ->setCellValue('D'.$noRow, $value['ITEM_NAME'])
                        ->setCellValue('E'.$noRow, $value['DATE_CODE'])
                        ->setCellValue('F'.$noRow, $value['BATTERY_TYPE'])
                        ->setCellValue('G'.$noRow, $value['CELL_GRADE'])
                        ->setCellValue('H'.$noRow, $value['QTY'])
                        ->setCellValue('I'.$noRow, $value['CR_DATE'])
                        ->setCellValue('J'.$noRow, $value['OPERATION_TIME']);

            $sheet->getStyle('A'.$noRow.':J'.$noRow)->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
        }
    }

    foreach ($someArray2 as $key => $value2) {
        if($value['PO_NO'] == $value2['PO_NO'] AND $value['PO_LINE_NO'] == $value2['PO_LINE_NO']){
            $mps_d = $value2['MPS_DATE'];
            foreach ($someArray3 as $key => $value3) {
                if($value3['KOLOM_DATE'] == $mps_d){
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($value3['KOLOM_URUT'].$noRow, $value2['MPS_QTY'])
                    ;
                }
                $sheet->getStyle($value3['KOLOM_URUT'].$noRow)->applyFromArray(
                    array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );
            }
        }
    }

    $PCK_GRP_TEMP = $value['LABEL_TYPE'];
    $noRow++;
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="PACKAGING-PLAN.xls"');
$objWriter->save('php://output');
?>