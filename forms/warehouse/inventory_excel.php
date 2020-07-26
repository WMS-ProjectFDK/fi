<?php
ini_set('memory_limit', '-1');
set_time_limit(-1);
require_once '../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
include("../../connect/conn.php");

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
        (select sum(slip_quantity)from [transaction] where item_no=a.item_no and LEFT(CONVERT(varchar, slip_date,112),6)='$cmbBln') as qty_act
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
        (select sum(slip_quantity)from [transaction] where item_no=a.item_no and LEFT(CONVERT(varchar, slip_date,112),6)='$cmbBln') as qty_act
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
            ->setCellValue('E1', 'UNIT')
            ->setCellValue('F1', 'MONTH')
            ->setCellValue('G1', 'INVENTORY')
            ->setCellValue('H1', 'RECEIVE')
            ->setCellValue('I1', 'OTHER RECEIVE')
            ->setCellValue('J1', 'ISSUE')
            ->setCellValue('K1', 'OTHER ISSUE')
            ->setCellValue('L1', 'LAST INVENTORY');
            ;

cellColor('A1:L1', 'A5A5A5');
$objPHPExcel->setActiveSheetIndex()->mergeCells('C1:D1');

$sheet->getStyle('A1:L1')->applyFromArray(
    array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'A5A5A5')
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
                ->setCellValue('E'.$no, $data->UNIT)
                ->setCellValue('F'.$no, $cmbBln_txt)
                ->setCellValue('G'.$no, number_format($data->THIS_INVENTORY))
                ->setCellValue('H'.$no, number_format($data->RECEIVE1))
                ->setCellValue('I'.$no, number_format($data->OTHER_RECEIVE1))
                ->setCellValue('J'.$no, number_format($data->ISSUE1))
                ->setCellValue('K'.$no, number_format($data->OTHER_ISSUE1))
                ->setCellValue('L'.$no, number_format($data->LAST_INVENTORY));
    $sheet = $objPHPExcel->getActiveSheet();

    cellColor('A'.$no.':L'.$no, 'A5A5A5');
    $objPHPExcel->setActiveSheetIndex()->mergeCells('C'.$no.':D'.$no);
    $sheet->getStyle('A'.$no.':L'.$no)->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'A5A5A5')
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
    $no++;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$no, 'DETAILS');
    $sheet->mergeCells('A'.$no.':L'.$no);
    $sheet->getStyle('A'.$no.':L'.$no)->applyFromArray(
        array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
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
    $no++;

    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B'.$no, 'NO')
                ->setCellValue('C'.$no, 'SLIP DATE')
                ->setCellValue('D'.$no, 'SLIP NO')
                ->setCellValue('E'.$no, 'SLIP TYPE')
                ->setCellValue('G'.$no, 'COMPANY')
                ->setCellValue('H'.$no, 'RECEIVE')
                ->setCellValue('I'.$no, 'OTHER RECEIVE')
                ->setCellValue('J'.$no, 'ISSUE')
                ->setCellValue('K'.$no, 'OTHER ISSUE')
                ->setCellValue('L'.$no, 'INVENTORY');

    cellColor('B'.$no.':L'.$no, 'D2D2D2');
    $sheet->mergeCells('E'.$no.':F'.$no);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->getStyle('B'.$no.':L'.$no)->applyFromArray(
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
    $no++;

    $nourut_dtl = 1;

    $l_inv = $data->LAST_INVENTORY;
    $sql = "
          select cast(t.operation_date as varchar(10)) operation_date, t.section_code, sc.section, i.stock_subject_code, st.stock_subject, 
		  cast(t.slip_date as varchar(10))  slip_date, t.slip_type, sl.slip_name slip_name, t.slip_no, sl.in_out_flag,
			case sl.table_position when 1 then t.slip_quantity end  receive,
			case sl.table_position when 2 then t.slip_quantity end other_receive,
			case sl.table_position when 3 then t.slip_quantity end issue,
			case sl.table_position when 4 then t.slip_quantity end  other_issue,
			case sl.in_out_flag
				  when 'I' then isnull(t.slip_quantity,0)
				  when 'O' then isnull(-t.slip_quantity,0)
			   end qty,
			t.cost_process_code, t.cost_subject_code, t.remark1, t.remark2, t.unit_stock, t.company_code, c.company, t.ex_rate
			from [transaction] t, item i, section sc, unit u, stock_subject st,sliptype sl, company c, currency cu
			where t.item_no = i.item_no  and i.delete_type  is null and t.section_code = sc.section_code  and t.unit_stock = u.unit_code 
			and t.section_code = sc.section_code  and t.slip_type = sl.slip_type  and t.company_code = c.company_code  
			and t.stock_subject_code = st.stock_subject_code  and t.curr_code = cu.curr_code  
			and t.section_code = '100' and t.item_no = ".$data->ITEM_NO." and  t.accounting_month = '".$data->THIS_MONTH."' 
			order by t.slip_date,t.slip_type,t.SLIP_NO ";
    $detail = sqlsrv_query($connect, strtoupper($sql));
   
    while ($dta = sqlsrv_fetch_object($detail) ){
        $q = $dta->QTY;
        $total = intval($l_inv) + $q;

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.$no, $nourut_dtl)
                    ->setCellValue('C'.$no, $dta->SLIP_DATE)
                    ->setCellValue('D'.$no, $dta->SLIP_NO)
                    ->setCellValue('E'.$no, wordwrap('['.$dta->SLIP_TYPE.'] '.$dta->SLIP_NAME,6))
                    ->setCellValue('G'.$no, wordwrap($dta->COMPANY_CODE.' - '.$dta->COMPANY,20))
                    ->setCellValue('H'.$no, number_format($dta->RECEIVE))
                    ->setCellValue('I'.$no, number_format($dta->OTHER_RECEIVE))
                    ->setCellValue('J'.$no, number_format($dta->ISSUE))
                    ->setCellValue('K'.$no, number_format($dta->OTHER_ISSUE))
                    ->setCellValue('L'.$no, number_format($total));

        $sheet->mergeCells('E'.$no.':F'.$no);
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getStyle('B'.$no.':L'.$no)->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );
        $l_inv = $total;
        $no++;
        $nourut_dtl++;
    }
    $noUrut++;
}   

$objPHPExcel->getDefaultStyle()
            ->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
 
// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('ITEM - '.$wh);
 
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
// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));

// We'll be outputting an excel file
header('Content-type: application/vnd.ms-excel');

// It will be called file.xls
header('Content-Disposition: attachment; filename="INVENTORY_'.$cmbBln_txt.'.xlsx"');

// Write file to the browser
$objWriter->save('php://output');
?>