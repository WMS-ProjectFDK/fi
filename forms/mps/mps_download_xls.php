<?php
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");

$q_date = "select RIGHT(CAST(eomonth(dateadd(month,-1,getdate())) as varchar(10)),2) as off_old,
    RIGHT(CONVERT(nvarchar(10), dateadd(month,-1,getdate()), 105),8) as m_old,
    RIGHT(CAST(eomonth(getdate()) as varchar(10)),2) as off_now,
    RIGHT(CONVERT(nvarchar(10), getdate(), 105),8) as m_now,
    RIGHT(CAST(eomonth(dateadd(month,+1,getdate())) as varchar(10)),2) as off_plus_1,
    RIGHT(CONVERT(nvarchar(10), dateadd(month,+1,getdate()), 105),8) as m_plus1,
    RIGHT(CAST(eomonth(dateadd(month,+2,getdate())) as varchar(10)),2) as off_plus_2,
    RIGHT(CONVERT(nvarchar(10), dateadd(month,+2,getdate()), 105),8) as m_plus2,
    RIGHT(CAST(eomonth(dateadd(month,+3,getdate())) as varchar(10)),2) as off_plus_3,
    RIGHT(CONVERT(nvarchar(6), dateadd(month,+3,getdate()), 105),8) as m_plus3,
    CAST(RIGHT(CAST(eomonth(dateadd(month,-1,getdate())) as varchar(10)),2) AS INT) +
    CAST(RIGHT(CAST(eomonth(getdate()) as varchar(10)),2) as INT) +
    CAST(RIGHT(CAST(eomonth(dateadd(month,+1,getdate())) as varchar(10)),2) as INT) +
    CAST(RIGHT(CAST(eomonth(dateadd(month,+2,getdate())) as varchar(10)),2) as INT) +
    CAST(RIGHT(CAST(eomonth(dateadd(month,+3,getdate())) as varchar(10)),2) as INT) as JUM_HARI";
$data = sqlsrv_query ($connect, strtoupper($q_date));
$dt = sqlsrv_fetch_object($data);

$t = intval($dt->JUM_HARI);

$Aold = intval(OFF_OLD);    $Anow = intval(OFF_NOW);    $Apl1 = intval(OFF_PLUS_1);    $Apl2 = intval(OFF_PLUS_2);    $Apl3 = intval(OFF_PLUS_3);
$Dold = $dt->M_OLD;         $Dnow = $dt->M_NOW;         $Dpl1 = $dt->M_PLUS1;          $Dpl2 = $dt->M_PLUS2;          $Dpl3 = $dt->M_PLUS3;

$sts = "OLD";

$arrHr = array ('V','W','X','Y','Z',
                'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM',
                'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
                'BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM',
                'BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ',
                'CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM',
                'CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ',
                'DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM',
                'DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ',
                'EA','EB','EC','ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM',
                'EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ',
                'FA','FB','FC','FD','FE','FF','FG','FH','FI','FJ','FK','FL','FM',
                'FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ',
                'GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM',
                'GN','GO','GP','GQ','GR','GS','GT','GU','GV','GW','GX','GY','GZ'
            );
$tgl = 1;

/* START CONTENT ATACHMENT*/
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

// Get the contents of the JSON file 
$data = file_get_contents("mps_download_result.json");
$dt = json_decode(json_encode($data));
$str = preg_replace('/\\\\\"/',"\"", $dt);
$someArray = json_decode($str,true);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// --------------------------------------------------------------------
$noRow = 1;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'MPS');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':D'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');
$objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$noRow.':D'.$noRow);

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'ITEM NO.')
            ->setCellValue('B'.$noRow, 'ITEM NAME')
            ->setCellValue('C'.$noRow, 'BATTERY TYPE')
            ->setCellValue('D'.$noRow, 'CELL GRADE')
            ->setCellValue('E'.$noRow, 'PO NO')
            ->setCellValue('F'.$noRow, 'PO LINE NO.')
            ->setCellValue('G'.$noRow, 'WORK ORDER')
            ->setCellValue('H'.$noRow, 'CONSIGNEE')
            ->setCellValue('I'.$noRow, 'PACKAGING TYPE')
            ->setCellValue('J'.$noRow, 'DATE CODE')
            ->setCellValue('K'.$noRow, 'CR DATE')
            ->setCellValue('L'.$noRow, 'REQUESTED ETD')
            ->setCellValue('M'.$noRow, 'STATUS')
            ->setCellValue('N'.$noRow, 'LABEL ITEM NO.')
            ->setCellValue('O'.$noRow, 'LABEL NAME')
            ->setCellValue('P'.$noRow, 'QTY')
            ->setCellValue('Q'.$noRow, 'MAN POWER')
            ->setCellValue('R'.$noRow, 'OT(MAN SECOND/PCS')
            ->setCellValue('S'.$noRow, 'PACKAGING GROUPING')
            ->setCellValue('T'.$noRow, 'CAPACITY (GROUP/DAY)')
            ->setCellValue('U'.$noRow, 'REMARK');
            
            for ($i=0;$i<$t; $i++) {
                if($sts == "OLD"){ 
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue($arrHr[$i].$noRow, $tgl.$old)
                    ;
                    $tgl++;
                }
            }

            $sheet = $objPHPExcel->getActiveSheet();
    
            $sheet->getStyle('A'.$noRow.':U'.$noRow)->applyFromArray(
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
    
            $sheet->getStyle('A'.$noRow.':U'.$noRow)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FFD966')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
    
            $sheet->getStyle('A'.$noRow.':U'.$noRow)->applyFromArray(
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

$noRow++;
foreach ($someArray as $key => $value) {
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$noRow, $value['ITEM_NO'])
                ->setCellValue('B'.$noRow, $value['ITEM_NAME'])
                ->setCellValue('C'.$noRow, $value['BATERY_TYPE'])
                ->setCellValue('D'.$noRow, $value['CELL_GRADE'])
                ->setCellValue('E'.$noRow, $value['PO_NO'])
                ->setCellValue('F'.$noRow, $value['PO_LINE_NO'])
                ->setCellValue('G'.$noRow, $value['WORK_ORDER'])
                ->setCellValue('H'.$noRow, $value['CONSIGNEE'])
                ->setCellValue('I'.$noRow, $value['PACKAGE_TYPE'])
                ->setCellValue('J'.$noRow, $value['DATE_CODE'])
                ->setCellValue('K'.$noRow, $value['CR_DATE'])
                ->setCellValue('L'.$noRow, $value['REQUESTED_ETD'])
                ->setCellValue('M'.$noRow, $value['STATUS'])
                ->setCellValue('N'.$noRow, $value['LABEL_ITEM_NUMBER'])
                ->setCellValue('O'.$noRow, $value['LABEL_NAME'])
                ->setCellValue('P'.$noRow, $value['QTY'])
                ->setCellValue('Q'.$noRow, $value['MAN_POWER'])
                ->setCellValue('R'.$noRow, $value['OPERATION_TIME'])
                ->setCellValue('S'.$noRow, $value['PACKAGE_TYPE'])
                ->setCellValue('T'.$noRow, $value['CAPACITY'])
                ->setCellValue('U'.$noRow, $value['REMARK']);
    
    if ($value['FLG'] != 'MPS') {
        $sheet->getStyle('A'.$noRow.':U'.$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFD966')
                )
            )
        );
    }
    $noRow++;
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="MPS.xls"');
$objWriter->save('php://output');
?>