<?php
ini_set('memory_limit', '-1');
set_time_limit(-1);
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

//http://localhost:8088/fi/forms/warehouse/inventory_excel.php?cmbBln=202008&cmbBln_txt=08-2020&src=&rdo_sts=check_WP

$cmbBln = isset($_REQUEST['cmbBln']) ? strval($_REQUEST['cmbBln']) : '';
$cmbBln_txt = isset($_REQUEST['cmbBln_txt']) ? strval($_REQUEST['cmbBln_txt']) : '';
$rdo_sts = isset($_REQUEST['rdo_sts']) ? strval($_REQUEST['rdo_sts']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

if($rdo_sts=='check_all'){
    $stock = "";
}elseif($rdo_sts=='check_PM'){
    $stock = "b.stock_subject_code='2' and ";
}elseif($rdo_sts=='check_FG'){
    $stock = "b.stock_subject_code='5' and ";
}elseif($rdo_sts=='check_WP'){
    $stock = "b.stock_subject_code='0' and ";
}elseif($rdo_sts=='check_WIP'){
    $stock = "b.stock_subject_code='3' and ";
}elseif($rdo_sts=='check_CSP'){
    $stock = "b.stock_subject_code='6' and ";
}elseif($rdo_sts=='check_RM'){
    $stock = "b.stock_subject_code='1' and ";
}elseif($rdo_sts=='check_semiFG'){
    $stock = "b.stock_subject_code='4' and ";
}elseif($rdo_sts=='check_material2'){
    $stock = "b.stock_subject_code='7' and ";
}elseif($rdo_sts==''){
    $stock = "b.stock_subject_code is null and ";
}

if ($src !='') {
    $where="where a.item_no='$src' AND (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";
}else{
    $where ="where $stock (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";
}

$sql = "select distinct max(this_month) as this_month, max(last_month) as last_month from whinventory";
$data = sqlsrv_query($connect, strtoupper($sql));
$dt_result = sqlsrv_fetch_object($data);


if($dt_result->THIS_MONTH == $cmbBln){
    $sql = "select a.item_no, b.item, b.description, b.uom_q, c.unit, a.this_month, 
        a.this_inventory, 
        a.receive1,
        a.other_receive1,
        a.issue1,
        a.other_issue1,
        a.last_inventory,
        (select sum(slip_quantity)from [transaction] where item_no=a.item_no and LEFT(CONVERT(varchar, slip_date,112),6)='$cmbBln') as qty_act,
        b.standard_price
        from whinventory a
        inner join item b on a.item_no=b.item_no
        inner join unit c on b.uom_q=c.unit_code
        $where order by b.item asc, b.description asc"; 
    $result = sqlsrv_query($connect, strtoupper($sql));
}else{
    $sql = "select a.item_no, b.item, b.description, b.uom_q, c.unit, a.this_month, 
        a.last_inventory as this_inventory, 
        receive2 as receive1,
        other_receive2 as other_receive1,
        issue2 as issue1,
        other_issue2 as other_issue1,
        last2_inventory as last_inventory,
        (select sum(slip_quantity)from [transaction] where item_no=a.item_no and LEFT(CONVERT(varchar, slip_date,112),6)='$cmbBln') as qty_act,
        b.standard_price
        from whinventory a
        inner join item b on a.item_no=b.item_no
        inner join unit c on b.uom_q=c.unit_code
        $where order by b.item asc, b.description asc"; 
    $result = sqlsrv_query($connect, strtoupper($sql));
  
}

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
 
// Add Data in your file
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NO')
            ->setCellValue('B1', 'ITEM NO')
            ->setCellValue('C1', 'DESCRIPTION')
            ->setCellValue('D1', 'UNIT')
            ->setCellValue('E1', 'MONTH')
            ->setCellValue('F1', 'STANDARD PRICE')
            ->setCellValue('G1', 'LAST INVENTORY')
            ->setCellValue('H1', 'RECEIVE')
            ->setCellValue('I1', 'OTHER RECEIVE')
            ->setCellValue('J1', 'ISSUE')
            ->setCellValue('K1', 'OTHER ISSUE')
            ->setCellValue('L1', 'THIS INVENTORY');

cellColor('A1:L1', 'D2D2D2');
$sheet->getStyle('A1:L1')->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'D2D2D2')
        ),
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        ),
        'font'  => array(
            'bold'  => true,
            'size'  => 12
        )
    )
);

$noUrut = 1;    
$no=2;

while ($data=sqlsrv_fetch_object($result)){
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, ($noUrut))
                ->setCellValue('B'.$no, $data->ITEM_NO)
                ->setCellValue('C'.$no, $data->ITEM.' - '.$data->DESCRIPTION)
                ->setCellValue('d'.$no, $data->UNIT)
                ->setCellValue('E'.$no, $cmbBln_txt)
                ->setCellValue('F'.$no, $data->STANDARD_PRICE)
                ->setCellValue('G'.$no, $data->LAST_INVENTORY)
                ->setCellValue('H'.$no, $data->RECEIVE1)
                ->setCellValue('I'.$no, $data->OTHER_RECEIVE1)
                ->setCellValue('J'.$no, $data->ISSUE1)
                ->setCellValue('K'.$no, $data->OTHER_ISSUE1)
                ->setCellValue('L'.$no, $data->THIS_INVENTORY);
    $sheet = $objPHPExcel->getActiveSheet();

    cellColor('A'.$no.':L'.$no, 'FFFFFF');
    $sheet->getStyle('A'.$no.':L'.$no)->applyFromArray(
        array(
            
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFFFF')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            'font'  => array(
                'bold'  => false,
                'size'  => 11
            )
        )
    );
    $no++;
    $noUrut++;
}   

$objPHPExcel->getDefaultStyle()
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="INVENTORY_HEADER_'.$cmbBln_txt.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>