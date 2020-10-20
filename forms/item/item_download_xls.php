<?php
error_reporting(0);
set_time_limit(0);
date_default_timezone_set('Asia/Jakarta');
include("../../connect/conn.php");

/* START CONTENT ATACHMENT*/
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

// Get the contents of the JSON file 
$data = file_get_contents("item_download_result.json");
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
            ->setCellValue('A'.$noRow, 'ITEM MAINTENANCE');

// --------------------------------------------------------------------
$noRow++;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, '(DOWNLOAD DATE : '.date("Y-m-d H:i:s").')');

// --------------------------------------------------------------------
$noRow++;
$noRow++;

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$noRow, 'ITEM_NO')
            ->setCellValue('B'.$noRow, 'ITEM_CODE')
            ->setCellValue('C'.$noRow, 'STOCK_SUBJECT')
            ->setCellValue('D'.$noRow, 'ITEM')
            ->setCellValue('E'.$noRow, 'SECTION')
            ->setCellValue('F'.$noRow, 'MAKER')
            ->setCellValue('G'.$noRow, 'ORIGIN')
            ->setCellValue('H'.$noRow, 'ITEM_FLAG')
            ->setCellValue('I'.$noRow, 'ITEM_FLAG_NAME')
            ->setCellValue('J'.$noRow, 'DESCRIPTION')
            ->setCellValue('K'.$noRow, 'CLASS_CODE')
            ->setCellValue('L'.$noRow, 'CLASS')
            ->setCellValue('M'.$noRow, 'DRAWING_NO')
            ->setCellValue('N'.$noRow, 'DRAWING_REV')
            ->setCellValue('O'.$noRow, 'CATALOG_NO')
            ->setCellValue('P'.$noRow, 'APPLICABLE_MODEL')
            ->setCellValue('Q'.$noRow, 'EXTERNAL_UNIT_NUMBER')
            ->setCellValue('R'.$noRow, 'QTY_UNIT')
            ->setCellValue('S'.$noRow, 'STOCK_UNIT')
            ->setCellValue('T'.$noRow, 'STOCK_RATE')
            ->setCellValue('U'.$noRow, 'ENGINEER_UNIT')
            ->setCellValue('V'.$noRow, 'ENGINEER_RATE')
            ->setCellValue('W'.$noRow, 'WEIGHT')
            ->setCellValue('X'.$noRow, 'WEIGHT_UNIT')
            ->setCellValue('Y'.$noRow, 'LENGTH_UNIT')
            ->setCellValue('Z'.$noRow, 'CURRENCY')
            ->setCellValue('AA'.$noRow, 'STANDARD_PRICE')
            ->setCellValue('AB'.$noRow, 'NEXT_TERM_PRICE')
            ->setCellValue('AC'.$noRow, 'SUPPLIERS_PRICE')
            ->setCellValue('AD'.$noRow, 'COST_SUBJECT_CODE')
            ->setCellValue('AE'.$noRow, 'COST_PROCESS_CODE')
            ->setCellValue('AF'.$noRow, 'MANUFACT_LEADTIME')
            ->setCellValue('AG'.$noRow, 'PURCHASE_LEADTIME')
            ->setCellValue('AH'.$noRow, 'ADJUSTMENT_LEADTIME')
            ->setCellValue('AI'.$noRow, 'LABELING_TO_PACKAGING_DAY')
            ->setCellValue('AJ'.$noRow, 'ASSEMBLING_TO_LABELING_DAY')
            ->setCellValue('AK'.$noRow, 'CUSTOMER_ITEM_CODE')
            ->setCellValue('AL'.$noRow, 'CUSTOMER_ITEM_NAME')
            ->setCellValue('AM'.$noRow, 'LLC')
            ->setCellValue('AN'.$noRow, 'REORDER_POINT')
            ->setCellValue('AO'.$noRow, 'LEVEL_CONT_KEY')
            ->setCellValue('AP'.$noRow, 'MANUFACT_FAIL_RATE')
            ->setCellValue('AQ'.$noRow, 'MAKER_FLAG')
            ->setCellValue('AR'.$noRow, 'ISSUE_POLICY')
            ->setCellValue('AS'.$noRow, 'ISSUE_LOT')
            ->setCellValue('AT'.$noRow, 'ORDER_POLICY')
            ->setCellValue('AU'.$noRow, 'SAFETY_STOCK')
            ->setCellValue('AV'.$noRow, 'STOCK_ISSUE')
            ->setCellValue('AW'.$noRow, 'ITEM_TYPE1')
            ->setCellValue('AX'.$noRow, 'ITEM_TYPE2')
            ->setCellValue('AY'.$noRow, 'UPTO_DATE')
            ->setCellValue('AZ'.$noRow, 'REG_DATE')
            ->setCellValue('BA'.$noRow, 'LAST_RECEIVE_DATE')
            ->setCellValue('BB'.$noRow, 'LAST_ISSUE_DATE')
            ->setCellValue('BC'.$noRow, 'PACKAGE_UNIT_NUMBER')
            ->setCellValue('BD'.$noRow, 'UNIT_PACKAGE')
            ->setCellValue('BE'.$noRow, 'UNIT_PRICE_ORG')
            ->setCellValue('BF'.$noRow, 'UNIT_PRICE_RATE')
            ->setCellValue('BG'.$noRow, 'UNIT_CURRENCY')
            ->setCellValue('BH'.$noRow, 'UPTO_PERSON_NAME')
            ->setCellValue('BI'.$noRow, 'GRADE_CODE')
            ->setCellValue('BJ'.$noRow, 'CUSTOMER_TYPE')
            ->setCellValue('BK'.$noRow, 'PACKAGE_TYPE')
            ->setCellValue('BL'.$noRow, 'CAPACITY')
            ->setCellValue('BM'.$noRow, 'DATE_CODE_TYPE')
            ->setCellValue('BN'.$noRow, 'DATE_CODE_MONTH')
            ->setCellValue('BO'.$noRow, 'LABEL_TYPE_NAME')
            ->setCellValue('BP'.$noRow, 'MEASUREMENT')
            ->setCellValue('BQ'.$noRow, 'INNER_BOX_HEIGHT')
            ->setCellValue('BR'.$noRow, 'INNER_BOX_WIDTH')
            ->setCellValue('BS'.$noRow, 'INNER_BOX_DEPTH')
            ->setCellValue('BT'.$noRow, 'INNER_BOX_UNIT_NUMBER')
            ->setCellValue('BU'.$noRow, 'MEDIUM_BOX_HEIGHT')
            ->setCellValue('BV'.$noRow, 'MEDIUM_BOX_WIDTH')
            ->setCellValue('BW'.$noRow, 'MEDIUM_BOX_DEPTH')
            ->setCellValue('BX'.$noRow, 'MEDIUM_BOX_UNIT_NUMBER')
            ->setCellValue('BY'.$noRow, 'OUTER_BOX_HEIGHT')
            ->setCellValue('BZ'.$noRow, 'OUTER_BOX_WIDTH')
            ->setCellValue('CA'.$noRow, 'OUTER_BOX_DEPTH')
            ->setCellValue('CB'.$noRow, 'PACKING_INFORMATION_NO')
            ->setCellValue('CC'.$noRow, 'PLT_SPEC_NO')
            ->setCellValue('CD'.$noRow, 'PALLET_SIZE_TYPE_NAME')
            ->setCellValue('CE'.$noRow, 'PALLET_HEIGHT')
            ->setCellValue('CF'.$noRow, 'PALLET_WIDTH')
            ->setCellValue('CG'.$noRow, 'PALLET_DEPTH')
            ->setCellValue('CH'.$noRow, 'PALLET_UNIT_NUMBER')
            ->setCellValue('CI'.$noRow, 'PALLET_CTN_NUMBER')
            ->setCellValue('CJ'.$noRow, 'PALLET_STEP_CTN_NUMBER')
            ->setCellValue('CK'.$noRow, 'OPERATION_TIME')
            ->setCellValue('CL'.$noRow, 'MAN_POWER')
            ->setCellValue('CM'.$noRow, 'AGING_DAY')
;

$sheet = $objPHPExcel->getActiveSheet();
$sheet->getStyle('A'.$noRow.':CM'.$noRow)->applyFromArray(
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
                ->setCellValue('B'.$noRow, $value['ITEM_CODE'])
                ->setCellValue('C'.$noRow, $value['STOCK_SUBJECT'])
                ->setCellValue('D'.$noRow, $value['ITEM'])
                ->setCellValue('E'.$noRow, $value['SECTION'])
                ->setCellValue('F'.$noRow, $value['MAKER'])
                ->setCellValue('G'.$noRow, $value['ORIGIN'])
                ->setCellValue('H'.$noRow, $value['ITEM_FLAG'])
                ->setCellValue('I'.$noRow, $value['ITEM_FLAG_NAME'])
                ->setCellValue('J'.$noRow, $value['DESCRIPTION'])
                ->setCellValue('K'.$noRow, $value['CLASS_CODE'])
                ->setCellValue('L'.$noRow, $value['CLASS'])
                ->setCellValue('M'.$noRow, $value['DRAWING_NO'])
                ->setCellValue('N'.$noRow, $value['DRAWING_REV'])
                ->setCellValue('O'.$noRow, $value['CATALOG_NO'])
                ->setCellValue('P'.$noRow, $value['APPLICABLE_MODEL'])
                ->setCellValue('Q'.$noRow, $value['EXTERNAL_UNIT_NUMBER'])
                ->setCellValue('R'.$noRow, $value['QTY_UNIT'])
                ->setCellValue('S'.$noRow, $value['STOCK_UNIT'])
                ->setCellValue('T'.$noRow, $value['STOCK_RATE'])
                ->setCellValue('U'.$noRow, $value['STOCK_UNIT'])
                ->setCellValue('V'.$noRow, $value['STOCK_RATE'])
                ->setCellValue('W'.$noRow, $value['WEIGHT'])
                ->setCellValue('X'.$noRow, $value['WEIGHT_UNIT'])
                ->setCellValue('Y'.$noRow, $value['LENGTH_UNIT'])
                ->setCellValue('Z'.$noRow, $value['CURRENCY'])
                ->setCellValue('AA'.$noRow, $value['STANDARD_PRICE'])
                ->setCellValue('AB'.$noRow, $value['NEXT_TERM_PRICE'])
                ->setCellValue('AC'.$noRow, $value['SUPPLIERS_PRICE'])
                ->setCellValue('AD'.$noRow, $value['COST_SUBJECT_CODE'])
                ->setCellValue('AE'.$noRow, $value['COST_PROCESS_CODE'])
                ->setCellValue('AF'.$noRow, $value['MANUFACT_LEADTIME'])
                ->setCellValue('AG'.$noRow, $value['PURCHASE_LEADTIME'])
                ->setCellValue('AH'.$noRow, $value['ADJUSTMENT_LEADTIME'])
                ->setCellValue('AI'.$noRow, $value['LABELING_TO_PACKAGING_DAY'])
                ->setCellValue('AJ'.$noRow, $value['ASSEMBLING_TO_LABELING_DAY'])
                ->setCellValue('AK'.$noRow, $value['CUSTOMER_ITEM_CODE'])
                ->setCellValue('AL'.$noRow, $value['CUSTOMER_ITEM_NAME'])
                ->setCellValue('AM'.$noRow, '-')
                ->setCellValue('AN'.$noRow, $value['REORDER_POINT'])
                ->setCellValue('AO'.$noRow, $value['LEVEL_CONT_KEY'])
                ->setCellValue('AP'.$noRow, $value['MANUFACT_FAIL_RATE'])
                ->setCellValue('AQ'.$noRow, $value['MAKER_FLAG'])
                ->setCellValue('AR'.$noRow, $value['ISSUE_POLICY'])
                ->setCellValue('AS'.$noRow, $value['ISSUE_LOT'])
                ->setCellValue('AT'.$noRow, $value['ORDER_POLICY'])
                ->setCellValue('AU'.$noRow, $value['SAFETY_STOCK'])
                ->setCellValue('AV'.$noRow, $value['STOCK_ISSUE'])
                ->setCellValue('AW'.$noRow, $value['ITEM_TYPE1'])
                ->setCellValue('AX'.$noRow, $value['ITEM_TYPE2'])
                ->setCellValue('AY'.$noRow, $value['UPTO_DATE'])
                ->setCellValue('AZ'.$noRow, $value['REG_DATE'])
                ->setCellValue('BA'.$noRow, $value['LAST_RECEIVE_DATE'])
                ->setCellValue('BB'.$noRow, $value['LAST_ISSUE_DATE'])
                ->setCellValue('BC'.$noRow, $value['PACKAGE_UNIT_NUMBER'])
                ->setCellValue('BD'.$noRow, $value['UNIT_PACKAGE'])
                ->setCellValue('BE'.$noRow, $value['UNIT_PRICE_ORG'])
                ->setCellValue('BF'.$noRow, $value['UNIT_PRICE_RATE'])
                ->setCellValue('BG'.$noRow, $value['UNIT_CURRENCY'])
                ->setCellValue('BH'.$noRow, $value['UPTO_PERSON_NAME'])
                ->setCellValue('BI'.$noRow, $value['GRADE_CODE'])
                ->setCellValue('BJ'.$noRow, $value['CUSTOMER_TYPE'])
                ->setCellValue('BK'.$noRow, $value['PACKAGE_TYPE'])
                ->setCellValue('BL'.$noRow, $value['CAPACITY'])
                ->setCellValue('BM'.$noRow, $value['DATE_CODE_TYPE'])
                ->setCellValue('BN'.$noRow, $value['DATE_CODE_MONTH'])
                ->setCellValue('BO'.$noRow, $value['LABEL_TYPE_NAME'])
                ->setCellValue('BP'.$noRow, $value['MEASUREMENT'])
                ->setCellValue('BQ'.$noRow, $value['INNER_BOX_HEIGHT'])
                ->setCellValue('BR'.$noRow, $value['INNER_BOX_WIDTH'])
                ->setCellValue('BS'.$noRow, $value['INNER_BOX_DEPTH'])
                ->setCellValue('BT'.$noRow, $value['INNER_BOX_UNIT_NUMBER'])
                ->setCellValue('BU'.$noRow, $value['MEDIUM_BOX_HEIGHT'])
                ->setCellValue('BV'.$noRow, $value['MEDIUM_BOX_WIDTH'])
                ->setCellValue('BW'.$noRow, $value['MEDIUM_BOX_DEPTH'])
                ->setCellValue('BX'.$noRow, $value['MEDIUM_BOX_UNIT_NUMBER'])
                ->setCellValue('BY'.$noRow, $value['OUTER_BOX_HEIGHT'])
                ->setCellValue('BZ'.$noRow, $value['OUTER_BOX_WIDTH'])
                ->setCellValue('CA'.$noRow, $value['OUTER_BOX_DEPTH'])
                ->setCellValue('CB'.$noRow, $value['PACKING_INFORMATION_NO'])
                ->setCellValue('CC'.$noRow, $value['PLT_SPEC_NO'])
                ->setCellValue('CD'.$noRow, $value['PALLET_SIZE_TYPE_NAME'])
                ->setCellValue('CE'.$noRow, $value['PALLET_HEIGHT'])
                ->setCellValue('CF'.$noRow, $value['PALLET_WIDTH'])
                ->setCellValue('CG'.$noRow, $value['PALLET_DEPTH'])
                ->setCellValue('CH'.$noRow, $value['PALLET_UNIT_NUMBER'])
                ->setCellValue('CI'.$noRow, $value['PALLET_CTN_NUMBER'])
                ->setCellValue('CJ'.$noRow, $value['PALLET_STEP_CTN_NUMBER'])
                ->setCellValue('CK'.$noRow, $value['OPERATION_TIME'])
                ->setCellValue('CL'.$noRow, $value['MAN_POWER'])
                ->setCellValue('CM'.$noRow, $value['AGING_DAY'])
    ;
    
    $sheet->getStyle('A'.$noRow.':CM'.$noRow)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        )
    );
    $noRow++;    
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="DOWNLOAD-MASTER-ITEM.xlsx"');
$objWriter->save('php://output');
?>