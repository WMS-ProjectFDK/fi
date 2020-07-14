<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
require_once '../../class/phpexcel/PHPExcel.php';
include("../../connect/conn.php");
$s=0;  

$min_hari = mktime(0,0,0,date("n"),date("j")-1,date("Y"));
$kemaren = intval(date("d", $min_hari));

$now = intval(date('d')); 

$plus_hari = mktime(0,0,0,date("n"),date("j")+1,date("Y"));       
$lusa = intval(date("d", $plus_hari));

$Arr_sheet = array('PROGRESS SUMMARY','LR6 EACH GRADE','LR3 EACH GRADE','SUMMARY TROUBLE','TROUBLE');

$arrBulan = array('1' => 'JANUARY', '2' => 'FEBRUARY', '3' => 'MARCH', '4' => 'APRIL', '5' => 'MAY', '6' => 'JUNE', 
                  '7' => 'JULY', '8' => 'AUGUST', '9' => 'SEPTEMBER', '10' => 'OCTOBER', '11' => 'NOVEMBER', '12' => 'DECEMBER');

$arrKolom = array('1' => 'C','2' => 'D','3' => 'E','4' => 'F','5' => 'G','6' => 'H','7' => 'I','8' => 'J','9' => 'K','10' => 'L',
                  '11' => 'M','12' => 'N','13' => 'O','14' => 'P','15' => 'Q','16' => 'R','17' => 'S','18' => 'T','19' => 'U','20' => 'V',
                  '21' => 'W','22' => 'X','23' => 'Y','24' => 'Z','25' => 'AA','26' => 'AB','27' => 'AC','28' => 'AD','29' => 'AE','30' => 'AF',
                  '31' => 'AG','32' => 'AH');

$arrABC = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','0','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH');

if (intval(date('d')) == 1){
    if(intval(date('m')) == 1){
        $nob = intval(date('m'))-1;
        $not = intval(date('Y'))-1;
        $bln = $arrBulan[$nob];
    }else{
        $nob = intval(date('m'))-1;
        $not = date('Y');
        $bln = $arrBulan[$nob];
    }
}else{
    $nob = intval(date('m'));
    $not = date('Y');
    $bln = $arrBulan[$nob];
}

if($nob<10){
	$nob2 = '0'.$nob;
}else{
	$nob2 = $nob;
}

$qry1 = "select * from
        (
        select assy_line, 'PLAN' as keterangan,
        sum(PLAN_1) as DATE_1, sum(PLAN_2) as DATE_2, sum(PLAN_3) as DATE_3, sum(PLAN_4) as DATE_4, sum(PLAN_5) as DATE_5, sum(PLAN_6) as DATE_6, sum(PLAN_7) as DATE_7, sum(PLAN_8) as DATE_8, sum(PLAN_9) as DATE_9, sum(PLAN_10) as DATE_10,
        sum(PLAN_11) as DATE_11, sum(PLAN_12) as DATE_12, sum(PLAN_13) as DATE_13, sum(PLAN_14) as DATE_14, sum(PLAN_15) as DATE_15,sum(PLAN_16) as DATE_16, sum(PLAN_17) as DATE_17, sum(PLAN_18) as DATE_18, sum(PLAN_19) as DATE_19, sum(PLAN_20) as DATE_20,
        sum(PLAN_21) as DATE_21, sum(PLAN_22) as DATE_22, sum(PLAN_23) as DATE_23, sum(PLAN_24) as DATE_24, sum(PLAN_25) as DATE_25,sum(PLAN_26) as DATE_26, sum(PLAN_27) as DATE_27, sum(PLAN_28) as DATE_28, sum(PLAN_29) as DATE_29, sum(PLAN_30) as DATE_30, sum(PLAN_31) as DATE_31
        from zvw_plan_assy 
        where  bulan = ".$nob." and tahun = ".$not."
        group by assy_line
        UNION ALL
        --ACTUAL ASSY
        select b.assy_line, 'ACTUAL',
        nvl(sum(b.date_1),0) as date_1, nvl(sum(b.date_2),0) as date_2, nvl(sum(b.date_3),0) as date_3, nvl(sum(b.date_4),0) as date_4, nvl(sum(b.date_5),0) as date_5,
        nvl(sum(b.date_6),0) as date_6, nvl(sum(b.date_7),0) as date_7, nvl(sum(b.date_8),0) as date_8, nvl(sum(b.date_9),0) as date_9, nvl(sum(b.date_10),0) as date_10,
        nvl(sum(b.date_11),0) as date_11, nvl(sum(b.date_12),0) as date_12, nvl(sum(b.date_13),0) as date_13, nvl(sum(b.date_14),0) as date_14, nvl(sum(b.date_15),0) as date_15,
        nvl(sum(b.date_16),0) as date_16, nvl(sum(b.date_17),0) as date_17, nvl(sum(b.date_18),0) as date_18, nvl(sum(b.date_19),0) as date_19, nvl(sum(b.date_20),0) as date_20,
        nvl(sum(b.date_21),0) as date_21, nvl(sum(b.date_22),0) as date_22, nvl(sum(b.date_23),0) as date_23, nvl(sum(b.date_24),0) as date_24, nvl(sum(b.date_25),0) as date_25,
        nvl(sum(b.date_26),0) as date_26, nvl(sum(b.date_27),0) as date_27, nvl(sum(b.date_28),0) as date_28, nvl(sum(b.date_29),0) as date_29, nvl(sum(b.date_30),0) as date_30, nvl(sum(b.date_31),0) as date_31
        from zvw_assy_actual b
        where bulan=".$nob2." and tahun=".$not."
        group by b.assy_line
        )
        order by assy_line";
$data1 = oci_parse($connect, $qry1);
oci_execute($data1);

$qry2 = "select * from 
        (
        select cell_type, 'PLAN' as keterangan,
        sum(PLAN_1) as DATE_1, sum(PLAN_2) as DATE_2, sum(PLAN_3) as DATE_3, sum(PLAN_4) as DATE_4, sum(PLAN_5) as DATE_5, sum(PLAN_6) as DATE_6, sum(PLAN_7) as DATE_7, sum(PLAN_8) as DATE_8, sum(PLAN_9) as DATE_9, sum(PLAN_10) as DATE_10,
        sum(PLAN_11) as DATE_11, sum(PLAN_12) as DATE_12, sum(PLAN_13) as DATE_13, sum(PLAN_14) as DATE_14, sum(PLAN_15) as DATE_15,sum(PLAN_16) as DATE_16, sum(PLAN_17) as DATE_17, sum(PLAN_18) as DATE_18, sum(PLAN_19) as DATE_19, sum(PLAN_20) as DATE_20,
        sum(PLAN_21) as DATE_21, sum(PLAN_22) as DATE_22, sum(PLAN_23) as DATE_23, sum(PLAN_24) as DATE_24, sum(PLAN_25) as DATE_25,sum(PLAN_26) as DATE_26, sum(PLAN_27) as DATE_27, sum(PLAN_28) as DATE_28, sum(PLAN_29) as DATE_29, sum(PLAN_30) as DATE_30, sum(PLAN_31) as DATE_31
        from zvw_plan_assy 
        where  bulan = ".$nob." and tahun = ".$not." and substr(assy_line, 1, 4)='LR06'
        group by cell_type
        UNION ALL
        --ACTUAL ASSY
        select b.cell_type, 'ACTUAL',
        nvl(sum(b.date_1),0) as date_1, nvl(sum(b.date_2),0) as date_2, nvl(sum(b.date_3),0) as date_3, nvl(sum(b.date_4),0) as date_4, nvl(sum(b.date_5),0) as date_5,
        nvl(sum(b.date_6),0) as date_6, nvl(sum(b.date_7),0) as date_7, nvl(sum(b.date_8),0) as date_8, nvl(sum(b.date_9),0) as date_9, nvl(sum(b.date_10),0) as date_10,
        nvl(sum(b.date_11),0) as date_11, nvl(sum(b.date_12),0) as date_12, nvl(sum(b.date_13),0) as date_13, nvl(sum(b.date_14),0) as date_14, nvl(sum(b.date_15),0) as date_15,
        nvl(sum(b.date_16),0) as date_16, nvl(sum(b.date_17),0) as date_17, nvl(sum(b.date_18),0) as date_18, nvl(sum(b.date_19),0) as date_19, nvl(sum(b.date_20),0) as date_20,
        nvl(sum(b.date_21),0) as date_21, nvl(sum(b.date_22),0) as date_22, nvl(sum(b.date_23),0) as date_23, nvl(sum(b.date_24),0) as date_24, nvl(sum(b.date_25),0) as date_25,
        nvl(sum(b.date_26),0) as date_26, nvl(sum(b.date_27),0) as date_27, nvl(sum(b.date_28),0) as date_28, nvl(sum(b.date_29),0) as date_29, nvl(sum(b.date_30),0) as date_30, nvl(sum(b.date_31),0) as date_31
        from zvw_assy_actual b
        where bulan=".$nob2." and tahun=".$not." AND substr(b.assy_line, 1, 4)='LR06'
        group by b.cell_type
        )
        order by cell_type";
$data2 = oci_parse($connect, $qry2);
oci_execute($data2);

$qry3 = "select * from 
        (
        select cell_type, 'PLAN' as keterangan,
        sum(PLAN_1) as DATE_1, sum(PLAN_2) as DATE_2, sum(PLAN_3) as DATE_3, sum(PLAN_4) as DATE_4, sum(PLAN_5) as DATE_5, sum(PLAN_6) as DATE_6, sum(PLAN_7) as DATE_7, sum(PLAN_8) as DATE_8, sum(PLAN_9) as DATE_9, sum(PLAN_10) as DATE_10,
        sum(PLAN_11) as DATE_11, sum(PLAN_12) as DATE_12, sum(PLAN_13) as DATE_13, sum(PLAN_14) as DATE_14, sum(PLAN_15) as DATE_15,sum(PLAN_16) as DATE_16, sum(PLAN_17) as DATE_17, sum(PLAN_18) as DATE_18, sum(PLAN_19) as DATE_19, sum(PLAN_20) as DATE_20,
        sum(PLAN_21) as DATE_21, sum(PLAN_22) as DATE_22, sum(PLAN_23) as DATE_23, sum(PLAN_24) as DATE_24, sum(PLAN_25) as DATE_25,sum(PLAN_26) as DATE_26, sum(PLAN_27) as DATE_27, sum(PLAN_28) as DATE_28, sum(PLAN_29) as DATE_29, sum(PLAN_30) as DATE_30, sum(PLAN_31) as DATE_31
        from zvw_plan_assy 
        where  bulan = ".$nob." and tahun = ".$not." and substr(assy_line, 1, 4)='LR03'
        group by cell_type
        UNION ALL
        --ACTUAL ASSY
        select b.cell_type, 'ACTUAL',
        nvl(sum(b.date_1),0) as date_1, nvl(sum(b.date_2),0) as date_2, nvl(sum(b.date_3),0) as date_3, nvl(sum(b.date_4),0) as date_4, nvl(sum(b.date_5),0) as date_5,
        nvl(sum(b.date_6),0) as date_6, nvl(sum(b.date_7),0) as date_7, nvl(sum(b.date_8),0) as date_8, nvl(sum(b.date_9),0) as date_9, nvl(sum(b.date_10),0) as date_10,
        nvl(sum(b.date_11),0) as date_11, nvl(sum(b.date_12),0) as date_12, nvl(sum(b.date_13),0) as date_13, nvl(sum(b.date_14),0) as date_14, nvl(sum(b.date_15),0) as date_15,
        nvl(sum(b.date_16),0) as date_16, nvl(sum(b.date_17),0) as date_17, nvl(sum(b.date_18),0) as date_18, nvl(sum(b.date_19),0) as date_19, nvl(sum(b.date_20),0) as date_20,
        nvl(sum(b.date_21),0) as date_21, nvl(sum(b.date_22),0) as date_22, nvl(sum(b.date_23),0) as date_23, nvl(sum(b.date_24),0) as date_24, nvl(sum(b.date_25),0) as date_25,
        nvl(sum(b.date_26),0) as date_26, nvl(sum(b.date_27),0) as date_27, nvl(sum(b.date_28),0) as date_28, nvl(sum(b.date_29),0) as date_29, nvl(sum(b.date_30),0) as date_30, nvl(sum(b.date_31),0) as date_31
        from zvw_assy_actual b
        where bulan=".$nob2." and tahun=".$not." AND substr(b.assy_line, 1, 4)='LR03'
        group by b.cell_type
        )
        order by cell_type";
$data3 = oci_parse($connect, $qry3);
oci_execute($data3);

$qry40 = "select * from 
		(
		select a.assy_line, a.ng_id_proses, b.ng_name_proses, a.ng_id, b.ng_name, 
		(
		select sum(ng_qty) from ztb_assy_trans_ng 
		where assy_line = a.assy_line and ng_id_proses = a.ng_id_proses 
        and to_char(tanggal_produksi,'MM') = '".$nob2."'
        and to_char(tanggal_produksi,'YYYY') = '".$not."'
		) as total_qty,
		sum(a.ng_qty) as qty, count(a.ng_id_proses) as freq 
		from ztb_assy_trans_ng a
		inner join ztb_assy_ng b on a.ng_id_proses = b.ng_id_proses and a.ng_id = b.ng_id
		where to_char(a.tanggal_produksi,'MM') = '".$nob2."' and to_char(a.tanggal_produksi,'YYYY') = '".$not."'
		group by a.assy_line, a.ng_id_proses, b.ng_name_proses, a.ng_id, b.ng_name
		)
		order by assy_line, ng_id_proses, total_qty desc" ;
$data40 = oci_parse($connect, $qry40);
oci_execute($data40);

$qry4 = "select a.tanggal_produksi, a.assy_line, a.ng_id_proses, b.ng_name_proses, a.ng_id, b.ng_name, a.ng_qty, a.perbaikan FROM ztb_assy_trans_ng a
        INNER JOIN ztb_assy_ng b on a.ng_id_proses = b.ng_id_proses AND a.ng_id = b.ng_id
        WHERE to_char(a.tanggal_produksi,'MM') = '".$nob2."' AND to_char(a.tanggal_produksi,'YYYY') = '".$not."'
        ORDER BY a.tanggal_produksi, a.assy_line, a.cell_type" ;
$data4 = oci_parse($connect, $qry4);
oci_execute($data4);

$objPHPExcel = new PHPExcel();
$objPHPExcel->createSheet();

while ($s <= 4) {
    if ($Arr_sheet[$s] == "PROGRESS SUMMARY"){
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'PROGRESS SUMMARY '.$bln.' '.$not);

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A1:AH1');


        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A2', 'LINE')
                    ->setCellValue('B2', 'REMARK')
                    ->setCellValue('C2',  $bln.' '.$not)
                    ->setCellValue('AH2', 'TOTAL');

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A2:A3');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('B2:B3');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('C2:AG2');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('AH2:AH3');

        $sheet = $objPHPExcel->getActiveSheet($s);

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('C3','1')
                    ->setCellValue('D3','2')
                    ->setCellValue('E3','3')
                    ->setCellValue('F3','4')
                    ->setCellValue('G3','5')
                    ->setCellValue('H3','6')
                    ->setCellValue('I3','7')
                    ->setCellValue('J3','8')
                    ->setCellValue('K3','9')
                    ->setCellValue('L3','10')
                    ->setCellValue('M3','11')
                    ->setCellValue('N3','12')
                    ->setCellValue('O3','13')
                    ->setCellValue('P3','14')
                    ->setCellValue('Q3','15')
                    ->setCellValue('R3','16')
                    ->setCellValue('S3','17')
                    ->setCellValue('T3','18')
                    ->setCellValue('U3','19')
                    ->setCellValue('V3','20')
                    ->setCellValue('W3','21')
                    ->setCellValue('X3','22')
                    ->setCellValue('Y3','23')
                    ->setCellValue('Z3','24')
                    ->setCellValue('AA3','25')
                    ->setCellValue('AB3','26')
                    ->setCellValue('AC3','27')
                    ->setCellValue('AD3','28')
                    ->setCellValue('AE3','29')
                    ->setCellValue('AF3','30')
                    ->setCellValue('AG3','31');

        for ($i=0;$i<count($arrABC);$i++){
            $sheet->getColumnDimension($arrABC[$i])->setWidth('14');
        }

        $objPHPExcel->getActiveSheet($s)->freezePane('C4');
        $sheet->getStyle('A2:AH3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A2:AH3')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A2:B3')->applyFromArray(
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

        $sheet->getStyle('C2:AG2')->applyFromArray(
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

        $sheet->getStyle('AH2:AH3')->applyFromArray(
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

        $sheet->getStyle('C3:'.$arrKolom[$kemaren].'3')->applyFromArray(
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

        $sheet->getStyle($arrKolom[$now].'3')->applyFromArray(
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

        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].'3:AG3')->applyFromArray(
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
        }else{
            $sheet->getStyle('AG3')->applyFromArray(
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
        }

        $no1 = 4;

        $tot_1_TGL_1 = 0;   $tot_1_TGL_10 = 0;      $tot_1_TGL_19 = 0;      $tot_1_TGL_28 = 0;
        $tot_1_TGL_2 = 0;   $tot_1_TGL_11 = 0;      $tot_1_TGL_20 = 0;      $tot_1_TGL_29 = 0;
        $tot_1_TGL_3 = 0;   $tot_1_TGL_12 = 0;      $tot_1_TGL_21 = 0;      $tot_1_TGL_30 = 0;
        $tot_1_TGL_4 = 0;   $tot_1_TGL_13 = 0;      $tot_1_TGL_22 = 0;      $tot_1_TGL_31 = 0;
        $tot_1_TGL_5 = 0;   $tot_1_TGL_14 = 0;      $tot_1_TGL_23 = 0;
        $tot_1_TGL_6 = 0;   $tot_1_TGL_15 = 0;      $tot_1_TGL_24 = 0;
        $tot_1_TGL_7 = 0;   $tot_1_TGL_16 = 0;      $tot_1_TGL_25 = 0;
        $tot_1_TGL_8 = 0;   $tot_1_TGL_17 = 0;      $tot_1_TGL_26 = 0;
        $tot_1_TGL_9 = 0;   $tot_1_TGL_18 = 0;      $tot_1_TGL_27 = 0;

        $tot_1_total_TGL_1 = 0;   $tot_1_total_TGL_10 = 0;      $tot_1_total_TGL_19 = 0;      $tot_1_total_TGL_28 = 0;
        $tot_1_total_TGL_2 = 0;   $tot_1_total_TGL_11 = 0;      $tot_1_total_TGL_20 = 0;      $tot_1_total_TGL_29 = 0;
        $tot_1_total_TGL_3 = 0;   $tot_1_total_TGL_12 = 0;      $tot_1_total_TGL_21 = 0;      $tot_1_total_TGL_30 = 0;
        $tot_1_total_TGL_4 = 0;   $tot_1_total_TGL_13 = 0;      $tot_1_total_TGL_22 = 0;      $tot_1_total_TGL_31 = 0;
        $tot_1_total_TGL_5 = 0;   $tot_1_total_TGL_14 = 0;      $tot_1_total_TGL_23 = 0;
        $tot_1_total_TGL_6 = 0;   $tot_1_total_TGL_15 = 0;      $tot_1_total_TGL_24 = 0;
        $tot_1_total_TGL_7 = 0;   $tot_1_total_TGL_16 = 0;      $tot_1_total_TGL_25 = 0;
        $tot_1_total_TGL_8 = 0;   $tot_1_total_TGL_17 = 0;      $tot_1_total_TGL_26 = 0;
        $tot_1_total_TGL_9 = 0;   $tot_1_total_TGL_18 = 0;      $tot_1_total_TGL_27 = 0;

        $ln='';     $total_plan = 0;

        while ($row1=oci_fetch_object($data1)){
            $TGL_1 = $row1->DATE_1;   $TGL_10 = $row1->DATE_10;      $TGL_19 = $row1->DATE_19;      $TGL_28 = $row1->DATE_28;
            $TGL_2 = $row1->DATE_2;   $TGL_11 = $row1->DATE_11;      $TGL_20 = $row1->DATE_20;      $TGL_29 = $row1->DATE_29;
            $TGL_3 = $row1->DATE_3;   $TGL_12 = $row1->DATE_12;      $TGL_21 = $row1->DATE_21;      $TGL_30 = $row1->DATE_30;
            $TGL_4 = $row1->DATE_4;   $TGL_13 = $row1->DATE_13;      $TGL_22 = $row1->DATE_22;      $TGL_31 = $row1->DATE_31;
            $TGL_5 = $row1->DATE_5;   $TGL_14 = $row1->DATE_14;      $TGL_23 = $row1->DATE_23;
            $TGL_6 = $row1->DATE_6;   $TGL_15 = $row1->DATE_15;      $TGL_24 = $row1->DATE_24;
            $TGL_7 = $row1->DATE_7;   $TGL_16 = $row1->DATE_16;      $TGL_25 = $row1->DATE_25;
            $TGL_8 = $row1->DATE_8;   $TGL_17 = $row1->DATE_17;      $TGL_26 = $row1->DATE_26;
            $TGL_9 = $row1->DATE_9;   $TGL_18 = $row1->DATE_18;      $TGL_27 = $row1->DATE_27;
            
            if($no1 == 4){
                $tot_1_TGL_1 -= $TGL_1;   $tot_1_TGL_10 -= $TGL_10;      $tot_1_TGL_19 -= $TGL_19;      $tot_1_TGL_28 -= $TGL_28;
                $tot_1_TGL_2 -= $TGL_2;   $tot_1_TGL_11 -= $TGL_11;      $tot_1_TGL_20 -= $TGL_20;      $tot_1_TGL_29 -= $TGL_29;
                $tot_1_TGL_3 -= $TGL_3;   $tot_1_TGL_12 -= $TGL_12;      $tot_1_TGL_21 -= $TGL_21;      $tot_1_TGL_30 -= $TGL_30;
                $tot_1_TGL_4 -= $TGL_4;   $tot_1_TGL_13 -= $TGL_13;      $tot_1_TGL_22 -= $TGL_22;      $tot_1_TGL_31 -= $TGL_31;
                $tot_1_TGL_5 -= $TGL_5;   $tot_1_TGL_14 -= $TGL_14;      $tot_1_TGL_23 -= $TGL_23;      
                $tot_1_TGL_6 -= $TGL_6;   $tot_1_TGL_15 -= $TGL_15;      $tot_1_TGL_24 -= $TGL_24;      
                $tot_1_TGL_7 -= $TGL_7;   $tot_1_TGL_16 -= $TGL_16;      $tot_1_TGL_25 -= $TGL_25;
                $tot_1_TGL_8 -= $TGL_8;   $tot_1_TGL_17 -= $TGL_17;      $tot_1_TGL_26 -= $TGL_26;
                $tot_1_TGL_9 -= $TGL_9;   $tot_1_TGL_18 -= $TGL_18;      $tot_1_TGL_27 -= $TGL_27;
                $total_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                              $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                              $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                              $TGL_28+$TGL_29+$TGL_30+$TGL_31;
            }else{
                if ($ln == $row1->ASSY_LINE){
                    if($row1->KETERANGAN == "ACTUAL"){
                        $row1->ASSY_LINE = '';
                        $tot_1_TGL_1 += $TGL_1;   $tot_1_TGL_10 += $TGL_10;      $tot_1_TGL_19 += $TGL_19;      $tot_1_TGL_28 += $TGL_28;
                        $tot_1_TGL_2 += $TGL_2;   $tot_1_TGL_11 += $TGL_11;      $tot_1_TGL_20 += $TGL_20;      $tot_1_TGL_29 += $TGL_29;
                        $tot_1_TGL_3 += $TGL_3;   $tot_1_TGL_12 += $TGL_12;      $tot_1_TGL_21 += $TGL_21;      $tot_1_TGL_30 += $TGL_30;
                        $tot_1_TGL_4 += $TGL_4;   $tot_1_TGL_13 += $TGL_13;      $tot_1_TGL_22 += $TGL_22;      $tot_1_TGL_31 += $TGL_31;
                        $tot_1_TGL_5 += $TGL_5;   $tot_1_TGL_14 += $TGL_14;      $tot_1_TGL_23 += $TGL_23;      
                        $tot_1_TGL_6 += $TGL_6;   $tot_1_TGL_15 += $TGL_15;      $tot_1_TGL_24 += $TGL_24;      
                        $tot_1_TGL_7 += $TGL_7;   $tot_1_TGL_16 += $TGL_16;      $tot_1_TGL_25 += $TGL_25;
                        $tot_1_TGL_8 += $TGL_8;   $tot_1_TGL_17 += $TGL_17;      $tot_1_TGL_26 += $TGL_26;
                        $tot_1_TGL_9 += $TGL_9;   $tot_1_TGL_18 += $TGL_18;      $tot_1_TGL_27 += $TGL_27;

                        $total_plan= $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                              $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                              $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                              $TGL_28+$TGL_29+$TGL_30+$TGL_31;  
                    }
                }else{
                    $tot_1_total_TGL_1 = $tot_1_TGL_1;   
                    $tot_1_total_TGL_2 = $tot_1_total_TGL_1 + $tot_1_TGL_2 ;
                    $tot_1_total_TGL_3 = $tot_1_total_TGL_2 + $tot_1_TGL_3 ;
                    $tot_1_total_TGL_4 = $tot_1_total_TGL_3 + $tot_1_TGL_4 ;
                    $tot_1_total_TGL_5 = $tot_1_total_TGL_4 + $tot_1_TGL_5 ;
                    $tot_1_total_TGL_6 = $tot_1_total_TGL_5 + $tot_1_TGL_6 ;
                    $tot_1_total_TGL_7 = $tot_1_total_TGL_6 + $tot_1_TGL_7 ;
                    $tot_1_total_TGL_8 = $tot_1_total_TGL_7 + $tot_1_TGL_8 ;
                    $tot_1_total_TGL_9 = $tot_1_total_TGL_8 + $tot_1_TGL_9 ;
                    $tot_1_total_TGL_10 = $tot_1_total_TGL_9 + $tot_1_TGL_10 ;
                    $tot_1_total_TGL_11 = $tot_1_total_TGL_10 + $tot_1_TGL_11 ;
                    $tot_1_total_TGL_12 = $tot_1_total_TGL_11 + $tot_1_TGL_12 ;
                    $tot_1_total_TGL_13 = $tot_1_total_TGL_12 + $tot_1_TGL_13 ;
                    $tot_1_total_TGL_14 = $tot_1_total_TGL_13 + $tot_1_TGL_14 ;
                    $tot_1_total_TGL_15 = $tot_1_total_TGL_14 + $tot_1_TGL_15 ;
                    $tot_1_total_TGL_16 = $tot_1_total_TGL_15 + $tot_1_TGL_16 ;
                    $tot_1_total_TGL_17 = $tot_1_total_TGL_16 + $tot_1_TGL_17 ;
                    $tot_1_total_TGL_18 = $tot_1_total_TGL_17 + $tot_1_TGL_18 ;
                    $tot_1_total_TGL_19 = $tot_1_total_TGL_18 + $tot_1_TGL_19 ;
                    $tot_1_total_TGL_20 = $tot_1_total_TGL_19 + $tot_1_TGL_20 ;
                    $tot_1_total_TGL_21 = $tot_1_total_TGL_20 + $tot_1_TGL_21 ;
                    $tot_1_total_TGL_22 = $tot_1_total_TGL_21 + $tot_1_TGL_22 ;
                    $tot_1_total_TGL_23 = $tot_1_total_TGL_22 + $tot_1_TGL_23 ;
                    $tot_1_total_TGL_24 = $tot_1_total_TGL_23 + $tot_1_TGL_24 ;
                    $tot_1_total_TGL_25 = $tot_1_total_TGL_24 + $tot_1_TGL_25 ;
                    $tot_1_total_TGL_26 = $tot_1_total_TGL_25 + $tot_1_TGL_26 ;
                    $tot_1_total_TGL_27 = $tot_1_total_TGL_26 + $tot_1_TGL_27 ;
                    $tot_1_total_TGL_28 = $tot_1_total_TGL_27 + $tot_1_TGL_28 ;
                    $tot_1_total_TGL_29 = $tot_1_total_TGL_28 + $tot_1_TGL_29 ;
                    $tot_1_total_TGL_30 = $tot_1_total_TGL_29 + $tot_1_TGL_30 ;
                    $tot_1_total_TGL_31 = $tot_1_total_TGL_30 + $tot_1_TGL_31 ;

                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no1, '')
                                ->setCellValue('B'.$no1, 'GAP')
                                ->setCellValue('C'.$no1, $tot_1_TGL_1)
                                ->setCellValue('D'.$no1, $tot_1_TGL_2)
                                ->setCellValue('E'.$no1, $tot_1_TGL_3)
                                ->setCellValue('F'.$no1, $tot_1_TGL_4)
                                ->setCellValue('G'.$no1, $tot_1_TGL_5)
                                ->setCellValue('H'.$no1, $tot_1_TGL_6)
                                ->setCellValue('I'.$no1, $tot_1_TGL_7)
                                ->setCellValue('J'.$no1, $tot_1_TGL_8)
                                ->setCellValue('K'.$no1, $tot_1_TGL_9)
                                ->setCellValue('L'.$no1, $tot_1_TGL_10)
                                ->setCellValue('M'.$no1, $tot_1_TGL_11)
                                ->setCellValue('N'.$no1, $tot_1_TGL_12)
                                ->setCellValue('O'.$no1, $tot_1_TGL_13)
                                ->setCellValue('P'.$no1, $tot_1_TGL_14)
                                ->setCellValue('Q'.$no1, $tot_1_TGL_15)
                                ->setCellValue('R'.$no1, $tot_1_TGL_16)
                                ->setCellValue('S'.$no1, $tot_1_TGL_17)
                                ->setCellValue('T'.$no1, $tot_1_TGL_18)
                                ->setCellValue('U'.$no1, $tot_1_TGL_19)
                                ->setCellValue('V'.$no1, $tot_1_TGL_20)
                                ->setCellValue('W'.$no1, $tot_1_TGL_21)
                                ->setCellValue('X'.$no1, $tot_1_TGL_22)
                                ->setCellValue('Y'.$no1, $tot_1_TGL_23)
                                ->setCellValue('Z'.$no1, $tot_1_TGL_24)
                                ->setCellValue('AA'.$no1, $tot_1_TGL_25)
                                ->setCellValue('AB'.$no1, $tot_1_TGL_26)
                                ->setCellValue('AC'.$no1, $tot_1_TGL_27)
                                ->setCellValue('AD'.$no1, $tot_1_TGL_28)
                                ->setCellValue('AE'.$no1, $tot_1_TGL_29)
                                ->setCellValue('AF'.$no1, $tot_1_TGL_30)
                                ->setCellValue('AG'.$no1, $tot_1_TGL_31)
                                ->setCellValue('AH'.$no1, $tot_1_total_TGL_31);

                    $sheet->getStyle('A'.$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D2D2D2')
                            )
                        )
                    );

                    $sheet->getStyle('B'.$no1.':'.$arrKolom[$kemaren].$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'E2EFDA')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle($arrKolom[$now].$no1)->applyFromArray(
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

                    
                    if($arrKolom[$lusa] != 'AG'){
                        $sheet->getStyle($arrKolom[$lusa].$no1.':AH'.$no1)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'E2EFDA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );    
                    }else{
                        $sheet->getStyle('AG'.$no1.':AH'.$no1)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'E2EFDA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );
                    }   

                    $objPHPExcel->getActiveSheet()->getStyle('C'.$no1.':AH'.$no1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $no1++;

                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no1, '')
                                ->setCellValue('B'.$no1, 'TOTAL')
                                ->setCellValue('C'.$no1, $tot_1_total_TGL_1)
                                ->setCellValue('D'.$no1, $tot_1_total_TGL_2)
                                ->setCellValue('E'.$no1, $tot_1_total_TGL_3)
                                ->setCellValue('F'.$no1, $tot_1_total_TGL_4)
                                ->setCellValue('G'.$no1, $tot_1_total_TGL_5)
                                ->setCellValue('H'.$no1, $tot_1_total_TGL_6)
                                ->setCellValue('I'.$no1, $tot_1_total_TGL_7)
                                ->setCellValue('J'.$no1, $tot_1_total_TGL_8)
                                ->setCellValue('K'.$no1, $tot_1_total_TGL_9)
                                ->setCellValue('L'.$no1, $tot_1_total_TGL_10)
                                ->setCellValue('M'.$no1, $tot_1_total_TGL_11)
                                ->setCellValue('N'.$no1, $tot_1_total_TGL_12)
                                ->setCellValue('O'.$no1, $tot_1_total_TGL_13)
                                ->setCellValue('P'.$no1, $tot_1_total_TGL_14)
                                ->setCellValue('Q'.$no1, $tot_1_total_TGL_15)
                                ->setCellValue('R'.$no1, $tot_1_total_TGL_16)
                                ->setCellValue('S'.$no1, $tot_1_total_TGL_17)
                                ->setCellValue('T'.$no1, $tot_1_total_TGL_18)
                                ->setCellValue('U'.$no1, $tot_1_total_TGL_19)
                                ->setCellValue('V'.$no1, $tot_1_total_TGL_20)
                                ->setCellValue('W'.$no1, $tot_1_total_TGL_21)
                                ->setCellValue('X'.$no1, $tot_1_total_TGL_22)
                                ->setCellValue('Y'.$no1, $tot_1_total_TGL_23)
                                ->setCellValue('Z'.$no1, $tot_1_total_TGL_24)
                                ->setCellValue('AA'.$no1, $tot_1_total_TGL_25)
                                ->setCellValue('AB'.$no1, $tot_1_total_TGL_26)
                                ->setCellValue('AC'.$no1, $tot_1_total_TGL_27)
                                ->setCellValue('AD'.$no1, $tot_1_total_TGL_28)
                                ->setCellValue('AE'.$no1, $tot_1_total_TGL_29)
                                ->setCellValue('AF'.$no1, $tot_1_total_TGL_30)
                                ->setCellValue('AG'.$no1, $tot_1_total_TGL_31);

                    $sheet->getStyle('A'.$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D2D2D2')
                            ),
                            'borders' => array(
                                'bottom' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle('B'.$no1.':'.$arrKolom[$kemaren].$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'AAFFAA')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle($arrKolom[$now].$no1)->applyFromArray(
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

                    
                    if($arrKolom[$lusa] != 'AG'){
                        $sheet->getStyle($arrKolom[$lusa].$no1.':AH'.$no1)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'AAFFAA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );    
                    }else{
                        $sheet->getStyle('AG'.$no1.':AH'.$no1)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'AAFFAA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );
                    }

                    $objPHPExcel->getActiveSheet()->getStyle('C'.$no1.':AH'.$no1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $no1++;

                    $tot_1_TGL_1 = 0;   $tot_1_TGL_10 = 0;      $tot_1_TGL_19 = 0;      $tot_1_TGL_28 = 0;
                    $tot_1_TGL_2 = 0;   $tot_1_TGL_11 = 0;      $tot_1_TGL_20 = 0;      $tot_1_TGL_29 = 0;
                    $tot_1_TGL_3 = 0;   $tot_1_TGL_12 = 0;      $tot_1_TGL_21 = 0;      $tot_1_TGL_30 = 0;
                    $tot_1_TGL_4 = 0;   $tot_1_TGL_13 = 0;      $tot_1_TGL_22 = 0;      $tot_1_TGL_31 = 0;
                    $tot_1_TGL_5 = 0;   $tot_1_TGL_14 = 0;      $tot_1_TGL_23 = 0;      
                    $tot_1_TGL_6 = 0;   $tot_1_TGL_15 = 0;      $tot_1_TGL_24 = 0;      
                    $tot_1_TGL_7 = 0;   $tot_1_TGL_16 = 0;      $tot_1_TGL_25 = 0;
                    $tot_1_TGL_8 = 0;   $tot_1_TGL_17 = 0;      $tot_1_TGL_26 = 0;
                    $tot_1_TGL_9 = 0;   $tot_1_TGL_18 = 0;      $tot_1_TGL_27 = 0;

                    $tot_1_total_TGL_1 = 0;   $tot_1_total_TGL_10 = 0;      $tot_1_total_TGL_19 = 0;      $tot_1_total_TGL_28 = 0;
                    $tot_1_total_TGL_2 = 0;   $tot_1_total_TGL_11 = 0;      $tot_1_total_TGL_20 = 0;      $tot_1_total_TGL_29 = 0;
                    $tot_1_total_TGL_3 = 0;   $tot_1_total_TGL_12 = 0;      $tot_1_total_TGL_21 = 0;      $tot_1_total_TGL_30 = 0;
                    $tot_1_total_TGL_4 = 0;   $tot_1_total_TGL_13 = 0;      $tot_1_total_TGL_22 = 0;      $tot_1_total_TGL_31 = 0;
                    $tot_1_total_TGL_5 = 0;   $tot_1_total_TGL_14 = 0;      $tot_1_total_TGL_23 = 0;
                    $tot_1_total_TGL_6 = 0;   $tot_1_total_TGL_15 = 0;      $tot_1_total_TGL_24 = 0;
                    $tot_1_total_TGL_7 = 0;   $tot_1_total_TGL_16 = 0;      $tot_1_total_TGL_25 = 0;
                    $tot_1_total_TGL_8 = 0;   $tot_1_total_TGL_17 = 0;      $tot_1_total_TGL_26 = 0;
                    $tot_1_total_TGL_9 = 0;   $tot_1_total_TGL_18 = 0;      $tot_1_total_TGL_27 = 0;

                    $tot_1_total_TGL_31 = 0;

                    if($row1->KETERANGAN == "ACTUAL"){
                        $row1->ASSY_LINE = '';
                        $tot_1_TGL_1 += $TGL_1;   $tot_1_TGL_10 += $TGL_10;      $tot_1_TGL_19 += $TGL_19;      $tot_1_TGL_28 += $TGL_28;
                        $tot_1_TGL_2 += $TGL_2;   $tot_1_TGL_11 += $TGL_11;      $tot_1_TGL_20 += $TGL_20;      $tot_1_TGL_29 += $TGL_29;
                        $tot_1_TGL_3 += $TGL_3;   $tot_1_TGL_12 += $TGL_12;      $tot_1_TGL_21 += $TGL_21;      $tot_1_TGL_30 += $TGL_30;
                        $tot_1_TGL_4 += $TGL_4;   $tot_1_TGL_13 += $TGL_13;      $tot_1_TGL_22 += $TGL_22;      $tot_1_TGL_31 += $TGL_31;
                        $tot_1_TGL_5 += $TGL_5;   $tot_1_TGL_14 += $TGL_14;      $tot_1_TGL_23 += $TGL_23;      
                        $tot_1_TGL_6 += $TGL_6;   $tot_1_TGL_15 += $TGL_15;      $tot_1_TGL_24 += $TGL_24;      
                        $tot_1_TGL_7 += $TGL_7;   $tot_1_TGL_16 += $TGL_16;      $tot_1_TGL_25 += $TGL_25;
                        $tot_1_TGL_8 += $TGL_8;   $tot_1_TGL_17 += $TGL_17;      $tot_1_TGL_26 += $TGL_26;
                        $tot_1_TGL_9 += $TGL_9;   $tot_1_TGL_18 += $TGL_18;      $tot_1_TGL_27 += $TGL_27;

                        $total_plan= $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                              $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                              $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                              $TGL_28+$TGL_29+$TGL_30+$TGL_31;  
                    }else{
                        $tot_1_TGL_1 -= $TGL_1;   $tot_1_TGL_10 -= $TGL_10;      $tot_1_TGL_19 -= $TGL_19;      $tot_1_TGL_28 -= $TGL_28;
                        $tot_1_TGL_2 -= $TGL_2;   $tot_1_TGL_11 -= $TGL_11;      $tot_1_TGL_20 -= $TGL_20;      $tot_1_TGL_29 -= $TGL_29;
                        $tot_1_TGL_3 -= $TGL_3;   $tot_1_TGL_12 -= $TGL_12;      $tot_1_TGL_21 -= $TGL_21;      $tot_1_TGL_30 -= $TGL_30;
                        $tot_1_TGL_4 -= $TGL_4;   $tot_1_TGL_13 -= $TGL_13;      $tot_1_TGL_22 -= $TGL_22;      $tot_1_TGL_31 -= $TGL_31;
                        $tot_1_TGL_5 -= $TGL_5;   $tot_1_TGL_14 -= $TGL_14;      $tot_1_TGL_23 -= $TGL_23;      
                        $tot_1_TGL_6 -= $TGL_6;   $tot_1_TGL_15 -= $TGL_15;      $tot_1_TGL_24 -= $TGL_24;      
                        $tot_1_TGL_7 -= $TGL_7;   $tot_1_TGL_16 -= $TGL_16;      $tot_1_TGL_25 -= $TGL_25;
                        $tot_1_TGL_8 -= $TGL_8;   $tot_1_TGL_17 -= $TGL_17;      $tot_1_TGL_26 -= $TGL_26;
                        $tot_1_TGL_9 -= $TGL_9;   $tot_1_TGL_18 -= $TGL_18;      $tot_1_TGL_27 -= $TGL_27;

                        $total_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                                  $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                                  $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                                  $TGL_28+$TGL_29+$TGL_30+$TGL_31;
                    }
                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no1, $row1->ASSY_LINE)
                        ->setCellValue('B'.$no1, $row1->KETERANGAN)
                        ->setCellValue('C'.$no1, $TGL_1)
                        ->setCellValue('D'.$no1, $TGL_2)
                        ->setCellValue('E'.$no1, $TGL_3)
                        ->setCellValue('F'.$no1, $TGL_4)
                        ->setCellValue('G'.$no1, $TGL_5)
                        ->setCellValue('H'.$no1, $TGL_6)
                        ->setCellValue('I'.$no1, $TGL_7)
                        ->setCellValue('J'.$no1, $TGL_8)
                        ->setCellValue('K'.$no1, $TGL_9)
                        ->setCellValue('L'.$no1, $TGL_10)
                        ->setCellValue('M'.$no1, $TGL_11)
                        ->setCellValue('N'.$no1, $TGL_12)
                        ->setCellValue('O'.$no1, $TGL_13)
                        ->setCellValue('P'.$no1, $TGL_14)
                        ->setCellValue('Q'.$no1, $TGL_15)
                        ->setCellValue('R'.$no1, $TGL_16)
                        ->setCellValue('S'.$no1, $TGL_17)
                        ->setCellValue('T'.$no1, $TGL_18)
                        ->setCellValue('U'.$no1, $TGL_19)
                        ->setCellValue('V'.$no1, $TGL_20)
                        ->setCellValue('W'.$no1, $TGL_21)
                        ->setCellValue('X'.$no1, $TGL_22)
                        ->setCellValue('Y'.$no1, $TGL_23)
                        ->setCellValue('Z'.$no1, $TGL_24)
                        ->setCellValue('AA'.$no1, $TGL_25)
                        ->setCellValue('AB'.$no1, $TGL_26)
                        ->setCellValue('AC'.$no1, $TGL_27)
                        ->setCellValue('AD'.$no1, $TGL_28)
                        ->setCellValue('AE'.$no1, $TGL_29)
                        ->setCellValue('AF'.$no1, $TGL_30)
                        ->setCellValue('AG'.$no1, $TGL_31)
                        ->setCellValue('AH'.$no1, $total_plan);

            if($row1->KETERANGAN == 'PLAN'){
                $sheet->getStyle('A'.$no1)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'D2D2D2')
                        )
                    )
                );

                $sheet->getStyle('B'.$no1.':'.$arrKolom[$kemaren].$no1)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'DDEBF7')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );

                $sheet->getStyle($arrKolom[$now].$no1)->applyFromArray(
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

                
                if($arrKolom[$lusa] != 'AG'){
                    $sheet->getStyle($arrKolom[$lusa].$no1.':AH'.$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'DDEBF7')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );    
                }else{
                    $sheet->getStyle('AG'.$no1.':AH'.$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'DDEBF7')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );
                }
            }elseif($row1->KETERANGAN == 'ACTUAL'){
                $sheet->getStyle('A'.$no1)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'D2D2D2')
                        )
                    )
                );

                $sheet->getStyle('B'.$no1.':'.$arrKolom[$kemaren].$no1)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'FCE4D6')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );

                $sheet->getStyle($arrKolom[$now].$no1)->applyFromArray(
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

                
                if($arrKolom[$lusa] != 'AG'){
                    $sheet->getStyle($arrKolom[$lusa].$no1.':AH'.$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FCE4D6')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );    
                }else{
                    $sheet->getStyle('AG'.$no1.':AH'.$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FCE4D6')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );
                }
            }

            $objPHPExcel->getActiveSheet()->getStyle('C'.$no1.':AH'.$no1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $ln = $row1->ASSY_LINE;
            $no1++;
        }

        $tot_1_total_TGL_1 = $tot_1_TGL_1;   
        $tot_1_total_TGL_2 = $tot_1_total_TGL_1 + $tot_1_TGL_2 ;
        $tot_1_total_TGL_3 = $tot_1_total_TGL_2 + $tot_1_TGL_3 ;
        $tot_1_total_TGL_4 = $tot_1_total_TGL_3 + $tot_1_TGL_4 ;
        $tot_1_total_TGL_5 = $tot_1_total_TGL_4 + $tot_1_TGL_5 ;
        $tot_1_total_TGL_6 = $tot_1_total_TGL_5 + $tot_1_TGL_6 ;
        $tot_1_total_TGL_7 = $tot_1_total_TGL_6 + $tot_1_TGL_7 ;
        $tot_1_total_TGL_8 = $tot_1_total_TGL_7 + $tot_1_TGL_8 ;
        $tot_1_total_TGL_9 = $tot_1_total_TGL_8 + $tot_1_TGL_9 ;
        $tot_1_total_TGL_10 = $tot_1_total_TGL_9 + $tot_1_TGL_10 ;
        $tot_1_total_TGL_11 = $tot_1_total_TGL_10 + $tot_1_TGL_11 ;
        $tot_1_total_TGL_12 = $tot_1_total_TGL_11 + $tot_1_TGL_12 ;
        $tot_1_total_TGL_13 = $tot_1_total_TGL_12 + $tot_1_TGL_13 ;
        $tot_1_total_TGL_14 = $tot_1_total_TGL_13 + $tot_1_TGL_14 ;
        $tot_1_total_TGL_15 = $tot_1_total_TGL_14 + $tot_1_TGL_15 ;
        $tot_1_total_TGL_16 = $tot_1_total_TGL_15 + $tot_1_TGL_16 ;
        $tot_1_total_TGL_17 = $tot_1_total_TGL_16 + $tot_1_TGL_17 ;
        $tot_1_total_TGL_18 = $tot_1_total_TGL_17 + $tot_1_TGL_18 ;
        $tot_1_total_TGL_19 = $tot_1_total_TGL_18 + $tot_1_TGL_19 ;
        $tot_1_total_TGL_20 = $tot_1_total_TGL_19 + $tot_1_TGL_20 ;
        $tot_1_total_TGL_21 = $tot_1_total_TGL_20 + $tot_1_TGL_21 ;
        $tot_1_total_TGL_22 = $tot_1_total_TGL_21 + $tot_1_TGL_22 ;
        $tot_1_total_TGL_23 = $tot_1_total_TGL_22 + $tot_1_TGL_23 ;
        $tot_1_total_TGL_24 = $tot_1_total_TGL_23 + $tot_1_TGL_24 ;
        $tot_1_total_TGL_25 = $tot_1_total_TGL_24 + $tot_1_TGL_25 ;
        $tot_1_total_TGL_26 = $tot_1_total_TGL_25 + $tot_1_TGL_26 ;
        $tot_1_total_TGL_27 = $tot_1_total_TGL_26 + $tot_1_TGL_27 ;
        $tot_1_total_TGL_28 = $tot_1_total_TGL_27 + $tot_1_TGL_28 ;
        $tot_1_total_TGL_29 = $tot_1_total_TGL_28 + $tot_1_TGL_29 ;
        $tot_1_total_TGL_30 = $tot_1_total_TGL_29 + $tot_1_TGL_30 ;
        $tot_1_total_TGL_31 = $tot_1_total_TGL_30 + $tot_1_TGL_31 ;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no1, '')
                    ->setCellValue('B'.$no1, 'GAP')
                    ->setCellValue('C'.$no1, $tot_1_TGL_1)
                    ->setCellValue('D'.$no1, $tot_1_TGL_2)
                    ->setCellValue('E'.$no1, $tot_1_TGL_3)
                    ->setCellValue('F'.$no1, $tot_1_TGL_4)
                    ->setCellValue('G'.$no1, $tot_1_TGL_5)
                    ->setCellValue('H'.$no1, $tot_1_TGL_6)
                    ->setCellValue('I'.$no1, $tot_1_TGL_7)
                    ->setCellValue('J'.$no1, $tot_1_TGL_8)
                    ->setCellValue('K'.$no1, $tot_1_TGL_9)
                    ->setCellValue('L'.$no1, $tot_1_TGL_10)
                    ->setCellValue('M'.$no1, $tot_1_TGL_11)
                    ->setCellValue('N'.$no1, $tot_1_TGL_12)
                    ->setCellValue('O'.$no1, $tot_1_TGL_13)
                    ->setCellValue('P'.$no1, $tot_1_TGL_14)
                    ->setCellValue('Q'.$no1, $tot_1_TGL_15)
                    ->setCellValue('R'.$no1, $tot_1_TGL_16)
                    ->setCellValue('S'.$no1, $tot_1_TGL_17)
                    ->setCellValue('T'.$no1, $tot_1_TGL_18)
                    ->setCellValue('U'.$no1, $tot_1_TGL_19)
                    ->setCellValue('V'.$no1, $tot_1_TGL_20)
                    ->setCellValue('W'.$no1, $tot_1_TGL_21)
                    ->setCellValue('X'.$no1, $tot_1_TGL_22)
                    ->setCellValue('Y'.$no1, $tot_1_TGL_23)
                    ->setCellValue('Z'.$no1, $tot_1_TGL_24)
                    ->setCellValue('AA'.$no1, $tot_1_TGL_25)
                    ->setCellValue('AB'.$no1, $tot_1_TGL_26)
                    ->setCellValue('AC'.$no1, $tot_1_TGL_27)
                    ->setCellValue('AD'.$no1, $tot_1_TGL_28)
                    ->setCellValue('AE'.$no1, $tot_1_TGL_29)
                    ->setCellValue('AF'.$no1, $tot_1_TGL_30)
                    ->setCellValue('AG'.$no1, $tot_1_TGL_31)
                    ->setCellValue('AH'.$no1, $tot_1_total_TGL_31);

        $sheet->getStyle('A'.$no1)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D2D2D2')
                )
            )
        );

        $sheet->getStyle('B'.$no1.':'.$arrKolom[$kemaren].$no1)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E2EFDA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle($arrKolom[$now].$no1)->applyFromArray(
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

        
        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].$no1.':AH'.$no1)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'E2EFDA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );    
        }else{
            $sheet->getStyle('AG'.$no1.':AH'.$no1)->applyFromArray(
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
        }   

        $objPHPExcel->getActiveSheet()->getStyle('C'.$no1.':AH'.$no1)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $no1++;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no1, '')
                    ->setCellValue('B'.$no1, 'TOTAL')
                    ->setCellValue('C'.$no1, $tot_1_total_TGL_1)
                    ->setCellValue('D'.$no1, $tot_1_total_TGL_2)
                    ->setCellValue('E'.$no1, $tot_1_total_TGL_3)
                    ->setCellValue('F'.$no1, $tot_1_total_TGL_4)
                    ->setCellValue('G'.$no1, $tot_1_total_TGL_5)
                    ->setCellValue('H'.$no1, $tot_1_total_TGL_6)
                    ->setCellValue('I'.$no1, $tot_1_total_TGL_7)
                    ->setCellValue('J'.$no1, $tot_1_total_TGL_8)
                    ->setCellValue('K'.$no1, $tot_1_total_TGL_9)
                    ->setCellValue('L'.$no1, $tot_1_total_TGL_10)
                    ->setCellValue('M'.$no1, $tot_1_total_TGL_11)
                    ->setCellValue('N'.$no1, $tot_1_total_TGL_12)
                    ->setCellValue('O'.$no1, $tot_1_total_TGL_13)
                    ->setCellValue('P'.$no1, $tot_1_total_TGL_14)
                    ->setCellValue('Q'.$no1, $tot_1_total_TGL_15)
                    ->setCellValue('R'.$no1, $tot_1_total_TGL_16)
                    ->setCellValue('S'.$no1, $tot_1_total_TGL_17)
                    ->setCellValue('T'.$no1, $tot_1_total_TGL_18)
                    ->setCellValue('U'.$no1, $tot_1_total_TGL_19)
                    ->setCellValue('V'.$no1, $tot_1_total_TGL_20)
                    ->setCellValue('W'.$no1, $tot_1_total_TGL_21)
                    ->setCellValue('X'.$no1, $tot_1_total_TGL_22)
                    ->setCellValue('Y'.$no1, $tot_1_total_TGL_23)
                    ->setCellValue('Z'.$no1, $tot_1_total_TGL_24)
                    ->setCellValue('AA'.$no1, $tot_1_total_TGL_25)
                    ->setCellValue('AB'.$no1, $tot_1_total_TGL_26)
                    ->setCellValue('AC'.$no1, $tot_1_total_TGL_27)
                    ->setCellValue('AD'.$no1, $tot_1_total_TGL_28)
                    ->setCellValue('AE'.$no1, $tot_1_total_TGL_29)
                    ->setCellValue('AF'.$no1, $tot_1_total_TGL_30)
                    ->setCellValue('AG'.$no1, $tot_1_total_TGL_31);

        $sheet->getStyle('A'.$no1)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D2D2D2')
                ),
                'borders' => array(
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle('B'.$no1.':'.$arrKolom[$kemaren].$no1)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'AAFFAA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle($arrKolom[$now].$no1)->applyFromArray(
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

        
        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].$no1.':AH'.$no1)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAFFAA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );    
        }else{
            $sheet->getStyle('AG'.$no1.':AH'.$no1)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAFFAA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet($s)->setTitle('PROGRESS SUMMARY');

    }elseif($Arr_sheet[$s] == "LR6 EACH GRADE"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'LR6 EACH GRADE '.$bln.' '.$not);

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A1:AH1');


        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A2', 'GRADE')
                    ->setCellValue('B2', 'REMARK')
                    ->setCellValue('C2',  $bln.' '.$not)
                    ->setCellValue('AH2', 'TOTAL');

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A2:A3');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('B2:B3');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('C2:AG2');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('AH2:AH3');

        $sheet = $objPHPExcel->getActiveSheet($s);

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('C3','1')
                    ->setCellValue('D3','2')
                    ->setCellValue('E3','3')
                    ->setCellValue('F3','4')
                    ->setCellValue('G3','5')
                    ->setCellValue('H3','6')
                    ->setCellValue('I3','7')
                    ->setCellValue('J3','8')
                    ->setCellValue('K3','9')
                    ->setCellValue('L3','10')
                    ->setCellValue('M3','11')
                    ->setCellValue('N3','12')
                    ->setCellValue('O3','13')
                    ->setCellValue('P3','14')
                    ->setCellValue('Q3','15')
                    ->setCellValue('R3','16')
                    ->setCellValue('S3','17')
                    ->setCellValue('T3','18')
                    ->setCellValue('U3','19')
                    ->setCellValue('V3','20')
                    ->setCellValue('W3','21')
                    ->setCellValue('X3','22')
                    ->setCellValue('Y3','23')
                    ->setCellValue('Z3','24')
                    ->setCellValue('AA3','25')
                    ->setCellValue('AB3','26')
                    ->setCellValue('AC3','27')
                    ->setCellValue('AD3','28')
                    ->setCellValue('AE3','29')
                    ->setCellValue('AF3','30')
                    ->setCellValue('AG3','31');

        for ($i=0;$i<count($arrABC);$i++){
            $sheet->getColumnDimension($arrABC[$i])->setWidth('14');
        }

        $objPHPExcel->getActiveSheet($s)->freezePane('C4');
        $sheet->getStyle('A2:AH3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A2:AH3')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A2:B3')->applyFromArray(
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

        $sheet->getStyle('C2:AG2')->applyFromArray(
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

        $sheet->getStyle('AH2:AH3')->applyFromArray(
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

        $sheet->getStyle('C3:'.$arrKolom[$kemaren].'3')->applyFromArray(
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

        $sheet->getStyle($arrKolom[$now].'3')->applyFromArray(
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

        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].'3:AG3')->applyFromArray(
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
        }else{
            $sheet->getStyle('AG3')->applyFromArray(
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
        }

        $no2 = 4;

        $tot_2_TGL_1 = 0;   $tot_2_TGL_10 = 0;      $tot_2_TGL_19 = 0;      $tot_2_TGL_28 = 0;
        $tot_2_TGL_2 = 0;   $tot_2_TGL_11 = 0;      $tot_2_TGL_20 = 0;      $tot_2_TGL_29 = 0;
        $tot_2_TGL_3 = 0;   $tot_2_TGL_12 = 0;      $tot_2_TGL_21 = 0;      $tot_2_TGL_30 = 0;
        $tot_2_TGL_4 = 0;   $tot_2_TGL_13 = 0;      $tot_2_TGL_22 = 0;      $tot_2_TGL_31 = 0;
        $tot_2_TGL_5 = 0;   $tot_2_TGL_14 = 0;      $tot_2_TGL_23 = 0;
        $tot_2_TGL_6 = 0;   $tot_2_TGL_15 = 0;      $tot_2_TGL_24 = 0;
        $tot_2_TGL_7 = 0;   $tot_2_TGL_16 = 0;      $tot_2_TGL_25 = 0;
        $tot_2_TGL_8 = 0;   $tot_2_TGL_17 = 0;      $tot_2_TGL_26 = 0;
        $tot_2_TGL_9 = 0;   $tot_2_TGL_18 = 0;      $tot_2_TGL_27 = 0;

        $tot_2_total_TGL_1 = 0;   $tot_2_total_TGL_10 = 0;      $tot_2_total_TGL_19 = 0;      $tot_2_total_TGL_28 = 0;
        $tot_2_total_TGL_2 = 0;   $tot_2_total_TGL_11 = 0;      $tot_2_total_TGL_20 = 0;      $tot_2_total_TGL_29 = 0;
        $tot_2_total_TGL_3 = 0;   $tot_2_total_TGL_12 = 0;      $tot_2_total_TGL_21 = 0;      $tot_2_total_TGL_30 = 0;
        $tot_2_total_TGL_4 = 0;   $tot_2_total_TGL_13 = 0;      $tot_2_total_TGL_22 = 0;      $tot_2_total_TGL_31 = 0;
        $tot_2_total_TGL_5 = 0;   $tot_2_total_TGL_14 = 0;      $tot_2_total_TGL_23 = 0;
        $tot_2_total_TGL_6 = 0;   $tot_2_total_TGL_15 = 0;      $tot_2_total_TGL_24 = 0;
        $tot_2_total_TGL_7 = 0;   $tot_2_total_TGL_16 = 0;      $tot_2_total_TGL_25 = 0;
        $tot_2_total_TGL_8 = 0;   $tot_2_total_TGL_17 = 0;      $tot_2_total_TGL_26 = 0;
        $tot_2_total_TGL_9 = 0;   $tot_2_total_TGL_18 = 0;      $tot_2_total_TGL_27 = 0;

        $ln2='';        $total_plan2 = 0;

        while ($row2=oci_fetch_object($data2)){
            $TGL_1 = $row2->DATE_1;   $TGL_10 = $row2->DATE_10;      $TGL_19 = $row2->DATE_19;      $TGL_28 = $row2->DATE_28;
            $TGL_2 = $row2->DATE_2;   $TGL_11 = $row2->DATE_11;      $TGL_20 = $row2->DATE_20;      $TGL_29 = $row2->DATE_29;
            $TGL_3 = $row2->DATE_3;   $TGL_12 = $row2->DATE_12;      $TGL_21 = $row2->DATE_21;      $TGL_30 = $row2->DATE_30;
            $TGL_4 = $row2->DATE_4;   $TGL_13 = $row2->DATE_13;      $TGL_22 = $row2->DATE_22;      $TGL_31 = $row2->DATE_31;
            $TGL_5 = $row2->DATE_5;   $TGL_14 = $row2->DATE_14;      $TGL_23 = $row2->DATE_23;
            $TGL_6 = $row2->DATE_6;   $TGL_15 = $row2->DATE_15;      $TGL_24 = $row2->DATE_24;
            $TGL_7 = $row2->DATE_7;   $TGL_16 = $row2->DATE_16;      $TGL_25 = $row2->DATE_25;
            $TGL_8 = $row2->DATE_8;   $TGL_17 = $row2->DATE_17;      $TGL_26 = $row2->DATE_26;
            $TGL_9 = $row2->DATE_9;   $TGL_18 = $row2->DATE_18;      $TGL_27 = $row2->DATE_27;
            
            if($no2 == 4){
                $tot_2_TGL_1 -= $TGL_1;   $tot_2_TGL_10 -= $TGL_10;      $tot_2_TGL_19 -= $TGL_19;      $tot_2_TGL_28 -= $TGL_28;
                $tot_2_TGL_2 -= $TGL_2;   $tot_2_TGL_11 -= $TGL_11;      $tot_2_TGL_20 -= $TGL_20;      $tot_2_TGL_29 -= $TGL_29;
                $tot_2_TGL_3 -= $TGL_3;   $tot_2_TGL_12 -= $TGL_12;      $tot_2_TGL_21 -= $TGL_21;      $tot_2_TGL_30 -= $TGL_30;
                $tot_2_TGL_4 -= $TGL_4;   $tot_2_TGL_13 -= $TGL_13;      $tot_2_TGL_22 -= $TGL_22;      $tot_2_TGL_31 -= $TGL_31;
                $tot_2_TGL_5 -= $TGL_5;   $tot_2_TGL_14 -= $TGL_14;      $tot_2_TGL_23 -= $TGL_23;      
                $tot_2_TGL_6 -= $TGL_6;   $tot_2_TGL_15 -= $TGL_15;      $tot_2_TGL_24 -= $TGL_24;      
                $tot_2_TGL_7 -= $TGL_7;   $tot_2_TGL_16 -= $TGL_16;      $tot_2_TGL_25 -= $TGL_25;
                $tot_2_TGL_8 -= $TGL_8;   $tot_2_TGL_17 -= $TGL_17;      $tot_2_TGL_26 -= $TGL_26;
                $tot_2_TGL_9 -= $TGL_9;   $tot_2_TGL_18 -= $TGL_18;      $tot_2_TGL_27 -= $TGL_27;

                $total_plan2 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                               $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                               $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                               $TGL_28+$TGL_29+$TGL_30+$TGL_31;
            }else{
                if ($ln2 == $row2->CELL_TYPE){
                    if($row2->KETERANGAN == "ACTUAL"){
                        $row2->CELL_TYPE = '';
                        $tot_2_TGL_1 += $TGL_1;   $tot_2_TGL_10 += $TGL_10;      $tot_2_TGL_19 += $TGL_19;      $tot_2_TGL_28 += $TGL_28;
                        $tot_2_TGL_2 += $TGL_2;   $tot_2_TGL_11 += $TGL_11;      $tot_2_TGL_20 += $TGL_20;      $tot_2_TGL_29 += $TGL_29;
                        $tot_2_TGL_3 += $TGL_3;   $tot_2_TGL_12 += $TGL_12;      $tot_2_TGL_21 += $TGL_21;      $tot_2_TGL_30 += $TGL_30;
                        $tot_2_TGL_4 += $TGL_4;   $tot_2_TGL_13 += $TGL_13;      $tot_2_TGL_22 += $TGL_22;      $tot_2_TGL_31 += $TGL_31;
                        $tot_2_TGL_5 += $TGL_5;   $tot_2_TGL_14 += $TGL_14;      $tot_2_TGL_23 += $TGL_23;      
                        $tot_2_TGL_6 += $TGL_6;   $tot_2_TGL_15 += $TGL_15;      $tot_2_TGL_24 += $TGL_24;      
                        $tot_2_TGL_7 += $TGL_7;   $tot_2_TGL_16 += $TGL_16;      $tot_2_TGL_25 += $TGL_25;
                        $tot_2_TGL_8 += $TGL_8;   $tot_2_TGL_17 += $TGL_17;      $tot_2_TGL_26 += $TGL_26;
                        $tot_2_TGL_9 += $TGL_9;   $tot_2_TGL_18 += $TGL_18;      $tot_2_TGL_27 += $TGL_27;

                        $total_plan2 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                              $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                              $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                              $TGL_28+$TGL_29+$TGL_30+$TGL_31;  
                    }else{
                        $row2->CELL_TYPE = '';
                        $tot_2_TGL_1 -= $TGL_1;   $tot_2_TGL_10 -= $TGL_10;      $tot_2_TGL_19 -= $TGL_19;      $tot_2_TGL_28 -= $TGL_28;
                        $tot_2_TGL_2 -= $TGL_2;   $tot_2_TGL_11 -= $TGL_11;      $tot_2_TGL_20 -= $TGL_20;      $tot_2_TGL_29 -= $TGL_29;
                        $tot_2_TGL_3 -= $TGL_3;   $tot_2_TGL_12 -= $TGL_12;      $tot_2_TGL_21 -= $TGL_21;      $tot_2_TGL_30 -= $TGL_30;
                        $tot_2_TGL_4 -= $TGL_4;   $tot_2_TGL_13 -= $TGL_13;      $tot_2_TGL_22 -= $TGL_22;      $tot_2_TGL_31 -= $TGL_31;
                        $tot_2_TGL_5 -= $TGL_5;   $tot_2_TGL_14 -= $TGL_14;      $tot_2_TGL_23 -= $TGL_23;      
                        $tot_2_TGL_6 -= $TGL_6;   $tot_2_TGL_15 -= $TGL_15;      $tot_2_TGL_24 -= $TGL_24;      
                        $tot_2_TGL_7 -= $TGL_7;   $tot_2_TGL_16 -= $TGL_16;      $tot_2_TGL_25 -= $TGL_25;
                        $tot_2_TGL_8 -= $TGL_8;   $tot_2_TGL_17 -= $TGL_17;      $tot_2_TGL_26 -= $TGL_26;
                        $tot_2_TGL_9 -= $TGL_9;   $tot_2_TGL_18 -= $TGL_18;      $tot_2_TGL_27 -= $TGL_27;

                        $total_plan2 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                              $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                              $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                              $TGL_28+$TGL_29+$TGL_30+$TGL_31;
                    }
                }else{
                    $tot_2_total_TGL_1 = $tot_2_TGL_1;   
                    $tot_2_total_TGL_2 = $tot_2_total_TGL_1 + $tot_2_TGL_2 ;
                    $tot_2_total_TGL_3 = $tot_2_total_TGL_2 + $tot_2_TGL_3 ;
                    $tot_2_total_TGL_4 = $tot_2_total_TGL_3 + $tot_2_TGL_4 ;
                    $tot_2_total_TGL_5 = $tot_2_total_TGL_4 + $tot_2_TGL_5 ;
                    $tot_2_total_TGL_6 = $tot_2_total_TGL_5 + $tot_2_TGL_6 ;
                    $tot_2_total_TGL_7 = $tot_2_total_TGL_6 + $tot_2_TGL_7 ;
                    $tot_2_total_TGL_8 = $tot_2_total_TGL_7 + $tot_2_TGL_8 ;
                    $tot_2_total_TGL_9 = $tot_2_total_TGL_8 + $tot_2_TGL_9 ;
                    $tot_2_total_TGL_10 = $tot_2_total_TGL_9 + $tot_2_TGL_10 ;
                    $tot_2_total_TGL_11 = $tot_2_total_TGL_10 + $tot_2_TGL_11 ;
                    $tot_2_total_TGL_12 = $tot_2_total_TGL_11 + $tot_2_TGL_12 ;
                    $tot_2_total_TGL_13 = $tot_2_total_TGL_12 + $tot_2_TGL_13 ;
                    $tot_2_total_TGL_14 = $tot_2_total_TGL_13 + $tot_2_TGL_14 ;
                    $tot_2_total_TGL_15 = $tot_2_total_TGL_14 + $tot_2_TGL_15 ;
                    $tot_2_total_TGL_16 = $tot_2_total_TGL_15 + $tot_2_TGL_16 ;
                    $tot_2_total_TGL_17 = $tot_2_total_TGL_16 + $tot_2_TGL_17 ;
                    $tot_2_total_TGL_18 = $tot_2_total_TGL_17 + $tot_2_TGL_18 ;
                    $tot_2_total_TGL_19 = $tot_2_total_TGL_18 + $tot_2_TGL_19 ;
                    $tot_2_total_TGL_20 = $tot_2_total_TGL_19 + $tot_2_TGL_20 ;
                    $tot_2_total_TGL_21 = $tot_2_total_TGL_20 + $tot_2_TGL_21 ;
                    $tot_2_total_TGL_22 = $tot_2_total_TGL_21 + $tot_2_TGL_22 ;
                    $tot_2_total_TGL_23 = $tot_2_total_TGL_22 + $tot_2_TGL_23 ;
                    $tot_2_total_TGL_24 = $tot_2_total_TGL_23 + $tot_2_TGL_24 ;
                    $tot_2_total_TGL_25 = $tot_2_total_TGL_24 + $tot_2_TGL_25 ;
                    $tot_2_total_TGL_26 = $tot_2_total_TGL_25 + $tot_2_TGL_26 ;
                    $tot_2_total_TGL_27 = $tot_2_total_TGL_26 + $tot_2_TGL_27 ;
                    $tot_2_total_TGL_28 = $tot_2_total_TGL_27 + $tot_2_TGL_28 ;
                    $tot_2_total_TGL_29 = $tot_2_total_TGL_28 + $tot_2_TGL_29 ;
                    $tot_2_total_TGL_30 = $tot_2_total_TGL_29 + $tot_2_TGL_30 ;
                    $tot_2_total_TGL_31 = $tot_2_total_TGL_30 + $tot_2_TGL_31 ;

                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no2, '')
                                ->setCellValue('B'.$no2, 'GAP')
                                ->setCellValue('C'.$no2, $tot_2_TGL_1)
                                ->setCellValue('D'.$no2, $tot_2_TGL_2)
                                ->setCellValue('E'.$no2, $tot_2_TGL_3)
                                ->setCellValue('F'.$no2, $tot_2_TGL_4)
                                ->setCellValue('G'.$no2, $tot_2_TGL_5)
                                ->setCellValue('H'.$no2, $tot_2_TGL_6)
                                ->setCellValue('I'.$no2, $tot_2_TGL_7)
                                ->setCellValue('J'.$no2, $tot_2_TGL_8)
                                ->setCellValue('K'.$no2, $tot_2_TGL_9)
                                ->setCellValue('L'.$no2, $tot_2_TGL_10)
                                ->setCellValue('M'.$no2, $tot_2_TGL_11)
                                ->setCellValue('N'.$no2, $tot_2_TGL_12)
                                ->setCellValue('O'.$no2, $tot_2_TGL_13)
                                ->setCellValue('P'.$no2, $tot_2_TGL_14)
                                ->setCellValue('Q'.$no2, $tot_2_TGL_15)
                                ->setCellValue('R'.$no2, $tot_2_TGL_16)
                                ->setCellValue('S'.$no2, $tot_2_TGL_17)
                                ->setCellValue('T'.$no2, $tot_2_TGL_18)
                                ->setCellValue('U'.$no2, $tot_2_TGL_19)
                                ->setCellValue('V'.$no2, $tot_2_TGL_20)
                                ->setCellValue('W'.$no2, $tot_2_TGL_21)
                                ->setCellValue('X'.$no2, $tot_2_TGL_22)
                                ->setCellValue('Y'.$no2, $tot_2_TGL_23)
                                ->setCellValue('Z'.$no2, $tot_2_TGL_24)
                                ->setCellValue('AA'.$no2, $tot_2_TGL_25)
                                ->setCellValue('AB'.$no2, $tot_2_TGL_26)
                                ->setCellValue('AC'.$no2, $tot_2_TGL_27)
                                ->setCellValue('AD'.$no2, $tot_2_TGL_28)
                                ->setCellValue('AE'.$no2, $tot_2_TGL_29)
                                ->setCellValue('AF'.$no2, $tot_2_TGL_30)
                                ->setCellValue('AG'.$no2, $tot_2_TGL_31)
                                ->setCellValue('AH'.$no2, $tot_2_total_TGL_31);

                    $sheet->getStyle('A'.$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D2D2D2')
                            )
                        )
                    );

                    $sheet->getStyle('B'.$no2.':'.$arrKolom[$kemaren].$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'E2EFDA')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle($arrKolom[$now].$no2)->applyFromArray(
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

                    
                    if($arrKolom[$lusa] != 'AG'){
                        $sheet->getStyle($arrKolom[$lusa].$no2.':AH'.$no2)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'E2EFDA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );    
                    }else{
                        $sheet->getStyle('AG'.$no2.':AH'.$no2)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'E2EFDA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );
                    }   

                    $objPHPExcel->getActiveSheet()->getStyle('C'.$no2.':AH'.$no2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $no2++;

                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no2, '')
                                ->setCellValue('B'.$no2, 'TOTAL')
                                ->setCellValue('C'.$no2, $tot_2_total_TGL_1)
                                ->setCellValue('D'.$no2, $tot_2_total_TGL_2)
                                ->setCellValue('E'.$no2, $tot_2_total_TGL_3)
                                ->setCellValue('F'.$no2, $tot_2_total_TGL_4)
                                ->setCellValue('G'.$no2, $tot_2_total_TGL_5)
                                ->setCellValue('H'.$no2, $tot_2_total_TGL_6)
                                ->setCellValue('I'.$no2, $tot_2_total_TGL_7)
                                ->setCellValue('J'.$no2, $tot_2_total_TGL_8)
                                ->setCellValue('K'.$no2, $tot_2_total_TGL_9)
                                ->setCellValue('L'.$no2, $tot_2_total_TGL_10)
                                ->setCellValue('M'.$no2, $tot_2_total_TGL_11)
                                ->setCellValue('N'.$no2, $tot_2_total_TGL_12)
                                ->setCellValue('O'.$no2, $tot_2_total_TGL_13)
                                ->setCellValue('P'.$no2, $tot_2_total_TGL_14)
                                ->setCellValue('Q'.$no2, $tot_2_total_TGL_15)
                                ->setCellValue('R'.$no2, $tot_2_total_TGL_16)
                                ->setCellValue('S'.$no2, $tot_2_total_TGL_17)
                                ->setCellValue('T'.$no2, $tot_2_total_TGL_18)
                                ->setCellValue('U'.$no2, $tot_2_total_TGL_19)
                                ->setCellValue('V'.$no2, $tot_2_total_TGL_20)
                                ->setCellValue('W'.$no2, $tot_2_total_TGL_21)
                                ->setCellValue('X'.$no2, $tot_2_total_TGL_22)
                                ->setCellValue('Y'.$no2, $tot_2_total_TGL_23)
                                ->setCellValue('Z'.$no2, $tot_2_total_TGL_24)
                                ->setCellValue('AA'.$no2, $tot_2_total_TGL_25)
                                ->setCellValue('AB'.$no2, $tot_2_total_TGL_26)
                                ->setCellValue('AC'.$no2, $tot_2_total_TGL_27)
                                ->setCellValue('AD'.$no2, $tot_2_total_TGL_28)
                                ->setCellValue('AE'.$no2, $tot_2_total_TGL_29)
                                ->setCellValue('AF'.$no2, $tot_2_total_TGL_30)
                                ->setCellValue('AG'.$no2, $tot_2_total_TGL_31);

                    $sheet->getStyle('A'.$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D2D2D2')
                            ),
                            'borders' => array(
                                'bottom' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle('B'.$no2.':'.$arrKolom[$kemaren].$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'AAFFAA')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle($arrKolom[$now].$no2)->applyFromArray(
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

                    
                    if($arrKolom[$lusa] != 'AG'){
                        $sheet->getStyle($arrKolom[$lusa].$no2.':AH'.$no2)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'AAFFAA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );    
                    }else{
                        $sheet->getStyle('AG'.$no2.':AH'.$no2)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'AAFFAA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );
                    }

                    $objPHPExcel->getActiveSheet()->getStyle('C'.$no2.':AH'.$no2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $no2++;

                    $tot_2_TGL_1 = 0;   $tot_2_TGL_10 = 0;      $tot_2_TGL_19 = 0;      $tot_2_TGL_28 = 0;
                    $tot_2_TGL_2 = 0;   $tot_2_TGL_11 = 0;      $tot_2_TGL_20 = 0;      $tot_2_TGL_29 = 0;
                    $tot_2_TGL_3 = 0;   $tot_2_TGL_12 = 0;      $tot_2_TGL_21 = 0;      $tot_2_TGL_30 = 0;
                    $tot_2_TGL_4 = 0;   $tot_2_TGL_13 = 0;      $tot_2_TGL_22 = 0;      $tot_2_TGL_31 = 0;
                    $tot_2_TGL_5 = 0;   $tot_2_TGL_14 = 0;      $tot_2_TGL_23 = 0;      
                    $tot_2_TGL_6 = 0;   $tot_2_TGL_15 = 0;      $tot_2_TGL_24 = 0;      
                    $tot_2_TGL_7 = 0;   $tot_2_TGL_16 = 0;      $tot_2_TGL_25 = 0;
                    $tot_2_TGL_8 = 0;   $tot_2_TGL_17 = 0;      $tot_2_TGL_26 = 0;
                    $tot_2_TGL_9 = 0;   $tot_2_TGL_18 = 0;      $tot_2_TGL_27 = 0;

                    $tot_2_total_TGL_1 = 0;   $tot_2_total_TGL_10 = 0;      $tot_2_total_TGL_19 = 0;      $tot_2_total_TGL_28 = 0;
                    $tot_2_total_TGL_2 = 0;   $tot_2_total_TGL_11 = 0;      $tot_2_total_TGL_20 = 0;      $tot_2_total_TGL_29 = 0;
                    $tot_2_total_TGL_3 = 0;   $tot_2_total_TGL_12 = 0;      $tot_2_total_TGL_21 = 0;      $tot_2_total_TGL_30 = 0;
                    $tot_2_total_TGL_4 = 0;   $tot_2_total_TGL_13 = 0;      $tot_2_total_TGL_22 = 0;      $tot_2_total_TGL_31 = 0;
                    $tot_2_total_TGL_5 = 0;   $tot_2_total_TGL_14 = 0;      $tot_2_total_TGL_23 = 0;
                    $tot_2_total_TGL_6 = 0;   $tot_2_total_TGL_15 = 0;      $tot_2_total_TGL_24 = 0;
                    $tot_2_total_TGL_7 = 0;   $tot_2_total_TGL_16 = 0;      $tot_2_total_TGL_25 = 0;
                    $tot_2_total_TGL_8 = 0;   $tot_2_total_TGL_17 = 0;      $tot_2_total_TGL_26 = 0;
                    $tot_2_total_TGL_9 = 0;   $tot_2_total_TGL_18 = 0;      $tot_2_total_TGL_27 = 0;

                    $tot_2_total_TGL_31 = 0;

                    if($row2->KETERANGAN == "ACTUAL"){
                        $tot_2_TGL_1 += $TGL_1;   $tot_2_TGL_10 += $TGL_10;      $tot_2_TGL_19 += $TGL_19;      $tot_2_TGL_28 += $TGL_28;
                        $tot_2_TGL_2 += $TGL_2;   $tot_2_TGL_11 += $TGL_11;      $tot_2_TGL_20 += $TGL_20;      $tot_2_TGL_29 += $TGL_29;
                        $tot_2_TGL_3 += $TGL_3;   $tot_2_TGL_12 += $TGL_12;      $tot_2_TGL_21 += $TGL_21;      $tot_2_TGL_30 += $TGL_30;
                        $tot_2_TGL_4 += $TGL_4;   $tot_2_TGL_13 += $TGL_13;      $tot_2_TGL_22 += $TGL_22;      $tot_2_TGL_31 += $TGL_31;
                        $tot_2_TGL_5 += $TGL_5;   $tot_2_TGL_14 += $TGL_14;      $tot_2_TGL_23 += $TGL_23;      
                        $tot_2_TGL_6 += $TGL_6;   $tot_2_TGL_15 += $TGL_15;      $tot_2_TGL_24 += $TGL_24;      
                        $tot_2_TGL_7 += $TGL_7;   $tot_2_TGL_16 += $TGL_16;      $tot_2_TGL_25 += $TGL_25;
                        $tot_2_TGL_8 += $TGL_8;   $tot_2_TGL_17 += $TGL_17;      $tot_2_TGL_26 += $TGL_26;
                        $tot_2_TGL_9 += $TGL_9;   $tot_2_TGL_18 += $TGL_18;      $tot_2_TGL_27 += $TGL_27;

                        $total_plan2 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                              $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                              $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                              $TGL_28+$TGL_29+$TGL_30+$TGL_31;  
                    }else{
                        $tot_2_TGL_1 -= $TGL_1;   $tot_2_TGL_10 -= $TGL_10;      $tot_2_TGL_19 -= $TGL_19;      $tot_2_TGL_28 -= $TGL_28;
                        $tot_2_TGL_2 -= $TGL_2;   $tot_2_TGL_11 -= $TGL_11;      $tot_2_TGL_20 -= $TGL_20;      $tot_2_TGL_29 -= $TGL_29;
                        $tot_2_TGL_3 -= $TGL_3;   $tot_2_TGL_12 -= $TGL_12;      $tot_2_TGL_21 -= $TGL_21;      $tot_2_TGL_30 -= $TGL_30;
                        $tot_2_TGL_4 -= $TGL_4;   $tot_2_TGL_13 -= $TGL_13;      $tot_2_TGL_22 -= $TGL_22;      $tot_2_TGL_31 -= $TGL_31;
                        $tot_2_TGL_5 -= $TGL_5;   $tot_2_TGL_14 -= $TGL_14;      $tot_2_TGL_23 -= $TGL_23;      
                        $tot_2_TGL_6 -= $TGL_6;   $tot_2_TGL_15 -= $TGL_15;      $tot_2_TGL_24 -= $TGL_24;      
                        $tot_2_TGL_7 -= $TGL_7;   $tot_2_TGL_16 -= $TGL_16;      $tot_2_TGL_25 -= $TGL_25;
                        $tot_2_TGL_8 -= $TGL_8;   $tot_2_TGL_17 -= $TGL_17;      $tot_2_TGL_26 -= $TGL_26;
                        $tot_2_TGL_9 -= $TGL_9;   $tot_2_TGL_18 -= $TGL_18;      $tot_2_TGL_27 -= $TGL_27;

                        $total_plan2 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                                  $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                                  $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                                  $TGL_28+$TGL_29+$TGL_30+$TGL_31;
                    }
                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no2, $row2->CELL_TYPE)
                        ->setCellValue('B'.$no2, $row2->KETERANGAN)
                        ->setCellValue('C'.$no2, $TGL_1)
                        ->setCellValue('D'.$no2, $TGL_2)
                        ->setCellValue('E'.$no2, $TGL_3)
                        ->setCellValue('F'.$no2, $TGL_4)
                        ->setCellValue('G'.$no2, $TGL_5)
                        ->setCellValue('H'.$no2, $TGL_6)
                        ->setCellValue('I'.$no2, $TGL_7)
                        ->setCellValue('J'.$no2, $TGL_8)
                        ->setCellValue('K'.$no2, $TGL_9)
                        ->setCellValue('L'.$no2, $TGL_10)
                        ->setCellValue('M'.$no2, $TGL_11)
                        ->setCellValue('N'.$no2, $TGL_12)
                        ->setCellValue('O'.$no2, $TGL_13)
                        ->setCellValue('P'.$no2, $TGL_14)
                        ->setCellValue('Q'.$no2, $TGL_15)
                        ->setCellValue('R'.$no2, $TGL_16)
                        ->setCellValue('S'.$no2, $TGL_17)
                        ->setCellValue('T'.$no2, $TGL_18)
                        ->setCellValue('U'.$no2, $TGL_19)
                        ->setCellValue('V'.$no2, $TGL_20)
                        ->setCellValue('W'.$no2, $TGL_21)
                        ->setCellValue('X'.$no2, $TGL_22)
                        ->setCellValue('Y'.$no2, $TGL_23)
                        ->setCellValue('Z'.$no2, $TGL_24)
                        ->setCellValue('AA'.$no2, $TGL_25)
                        ->setCellValue('AB'.$no2, $TGL_26)
                        ->setCellValue('AC'.$no2, $TGL_27)
                        ->setCellValue('AD'.$no2, $TGL_28)
                        ->setCellValue('AE'.$no2, $TGL_29)
                        ->setCellValue('AF'.$no2, $TGL_30)
                        ->setCellValue('AG'.$no2, $TGL_31)
                        ->setCellValue('AH'.$no2, $total_plan2);

            if($row2->KETERANGAN == 'PLAN'){
                $sheet->getStyle('A'.$no2)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'D2D2D2')
                        )
                    )
                );

                $sheet->getStyle('B'.$no2.':'.$arrKolom[$kemaren].$no2)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'DDEBF7')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );

                $sheet->getStyle($arrKolom[$now].$no2)->applyFromArray(
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

                
                if($arrKolom[$lusa] != 'AG'){
                    $sheet->getStyle($arrKolom[$lusa].$no2.':AH'.$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'DDEBF7')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );    
                }else{
                    $sheet->getStyle('AG'.$no2.':AH'.$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'DDEBF7')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );
                }
            }elseif($row2->KETERANGAN == 'ACTUAL'){
                $sheet->getStyle('A'.$no2)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'D2D2D2')
                        )
                    )
                );

                $sheet->getStyle('B'.$no2.':'.$arrKolom[$kemaren].$no2)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'FCE4D6')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );

                $sheet->getStyle($arrKolom[$now].$no2)->applyFromArray(
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

                
                if($arrKolom[$lusa] != 'AG'){
                    $sheet->getStyle($arrKolom[$lusa].$no2.':AH'.$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FCE4D6')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );    
                }else{
                    $sheet->getStyle('AG'.$no2.':AH'.$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FCE4D6')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );
                }
            }

            $objPHPExcel->getActiveSheet()->getStyle('C'.$no2.':AH'.$no2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $ln2 = $row2->CELL_TYPE;
            $no2++;
        }

        $tot_2_total_TGL_1 = $tot_2_TGL_1;   
        $tot_2_total_TGL_2 = $tot_2_total_TGL_1 + $tot_2_TGL_2 ;
        $tot_2_total_TGL_3 = $tot_2_total_TGL_2 + $tot_2_TGL_3 ;
        $tot_2_total_TGL_4 = $tot_2_total_TGL_3 + $tot_2_TGL_4 ;
        $tot_2_total_TGL_5 = $tot_2_total_TGL_4 + $tot_2_TGL_5 ;
        $tot_2_total_TGL_6 = $tot_2_total_TGL_5 + $tot_2_TGL_6 ;
        $tot_2_total_TGL_7 = $tot_2_total_TGL_6 + $tot_2_TGL_7 ;
        $tot_2_total_TGL_8 = $tot_2_total_TGL_7 + $tot_2_TGL_8 ;
        $tot_2_total_TGL_9 = $tot_2_total_TGL_8 + $tot_2_TGL_9 ;
        $tot_2_total_TGL_10 = $tot_2_total_TGL_9 + $tot_2_TGL_10 ;
        $tot_2_total_TGL_11 = $tot_2_total_TGL_10 + $tot_2_TGL_11 ;
        $tot_2_total_TGL_12 = $tot_2_total_TGL_11 + $tot_2_TGL_12 ;
        $tot_2_total_TGL_13 = $tot_2_total_TGL_12 + $tot_2_TGL_13 ;
        $tot_2_total_TGL_14 = $tot_2_total_TGL_13 + $tot_2_TGL_14 ;
        $tot_2_total_TGL_15 = $tot_2_total_TGL_14 + $tot_2_TGL_15 ;
        $tot_2_total_TGL_16 = $tot_2_total_TGL_15 + $tot_2_TGL_16 ;
        $tot_2_total_TGL_17 = $tot_2_total_TGL_16 + $tot_2_TGL_17 ;
        $tot_2_total_TGL_18 = $tot_2_total_TGL_17 + $tot_2_TGL_18 ;
        $tot_2_total_TGL_19 = $tot_2_total_TGL_18 + $tot_2_TGL_19 ;
        $tot_2_total_TGL_20 = $tot_2_total_TGL_19 + $tot_2_TGL_20 ;
        $tot_2_total_TGL_21 = $tot_2_total_TGL_20 + $tot_2_TGL_21 ;
        $tot_2_total_TGL_22 = $tot_2_total_TGL_21 + $tot_2_TGL_22 ;
        $tot_2_total_TGL_23 = $tot_2_total_TGL_22 + $tot_2_TGL_23 ;
        $tot_2_total_TGL_24 = $tot_2_total_TGL_23 + $tot_2_TGL_24 ;
        $tot_2_total_TGL_25 = $tot_2_total_TGL_24 + $tot_2_TGL_25 ;
        $tot_2_total_TGL_26 = $tot_2_total_TGL_25 + $tot_2_TGL_26 ;
        $tot_2_total_TGL_27 = $tot_2_total_TGL_26 + $tot_2_TGL_27 ;
        $tot_2_total_TGL_28 = $tot_2_total_TGL_27 + $tot_2_TGL_28 ;
        $tot_2_total_TGL_29 = $tot_2_total_TGL_28 + $tot_2_TGL_29 ;
        $tot_2_total_TGL_30 = $tot_2_total_TGL_29 + $tot_2_TGL_30 ;
        $tot_2_total_TGL_31 = $tot_2_total_TGL_30 + $tot_2_TGL_31 ;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no2, '')
                    ->setCellValue('B'.$no2, 'GAP')
                    ->setCellValue('C'.$no2, $tot_2_TGL_1)
                    ->setCellValue('D'.$no2, $tot_2_TGL_2)
                    ->setCellValue('E'.$no2, $tot_2_TGL_3)
                    ->setCellValue('F'.$no2, $tot_2_TGL_4)
                    ->setCellValue('G'.$no2, $tot_2_TGL_5)
                    ->setCellValue('H'.$no2, $tot_2_TGL_6)
                    ->setCellValue('I'.$no2, $tot_2_TGL_7)
                    ->setCellValue('J'.$no2, $tot_2_TGL_8)
                    ->setCellValue('K'.$no2, $tot_2_TGL_9)
                    ->setCellValue('L'.$no2, $tot_2_TGL_10)
                    ->setCellValue('M'.$no2, $tot_2_TGL_11)
                    ->setCellValue('N'.$no2, $tot_2_TGL_12)
                    ->setCellValue('O'.$no2, $tot_2_TGL_13)
                    ->setCellValue('P'.$no2, $tot_2_TGL_14)
                    ->setCellValue('Q'.$no2, $tot_2_TGL_15)
                    ->setCellValue('R'.$no2, $tot_2_TGL_16)
                    ->setCellValue('S'.$no2, $tot_2_TGL_17)
                    ->setCellValue('T'.$no2, $tot_2_TGL_18)
                    ->setCellValue('U'.$no2, $tot_2_TGL_19)
                    ->setCellValue('V'.$no2, $tot_2_TGL_20)
                    ->setCellValue('W'.$no2, $tot_2_TGL_21)
                    ->setCellValue('X'.$no2, $tot_2_TGL_22)
                    ->setCellValue('Y'.$no2, $tot_2_TGL_23)
                    ->setCellValue('Z'.$no2, $tot_2_TGL_24)
                    ->setCellValue('AA'.$no2, $tot_2_TGL_25)
                    ->setCellValue('AB'.$no2, $tot_2_TGL_26)
                    ->setCellValue('AC'.$no2, $tot_2_TGL_27)
                    ->setCellValue('AD'.$no2, $tot_2_TGL_28)
                    ->setCellValue('AE'.$no2, $tot_2_TGL_29)
                    ->setCellValue('AF'.$no2, $tot_2_TGL_30)
                    ->setCellValue('AG'.$no2, $tot_2_TGL_31)
                    ->setCellValue('AH'.$no2, $tot_2_total_TGL_31);

        $sheet->getStyle('A'.$no2)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D2D2D2')
                )
            )
        );

        $sheet->getStyle('B'.$no2.':'.$arrKolom[$kemaren].$no2)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E2EFDA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle($arrKolom[$now].$no2)->applyFromArray(
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

        
        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].$no2.':AH'.$no2)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'E2EFDA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );    
        }else{
            $sheet->getStyle('AG'.$no2.':AH'.$no2)->applyFromArray(
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
        }   

        $objPHPExcel->getActiveSheet()->getStyle('C'.$no2.':AH'.$no2)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $no2++;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no2, '')
                    ->setCellValue('B'.$no2, 'TOTAL')
                    ->setCellValue('C'.$no2, $tot_2_total_TGL_1)
                    ->setCellValue('D'.$no2, $tot_2_total_TGL_2)
                    ->setCellValue('E'.$no2, $tot_2_total_TGL_3)
                    ->setCellValue('F'.$no2, $tot_2_total_TGL_4)
                    ->setCellValue('G'.$no2, $tot_2_total_TGL_5)
                    ->setCellValue('H'.$no2, $tot_2_total_TGL_6)
                    ->setCellValue('I'.$no2, $tot_2_total_TGL_7)
                    ->setCellValue('J'.$no2, $tot_2_total_TGL_8)
                    ->setCellValue('K'.$no2, $tot_2_total_TGL_9)
                    ->setCellValue('L'.$no2, $tot_2_total_TGL_10)
                    ->setCellValue('M'.$no2, $tot_2_total_TGL_11)
                    ->setCellValue('N'.$no2, $tot_2_total_TGL_12)
                    ->setCellValue('O'.$no2, $tot_2_total_TGL_13)
                    ->setCellValue('P'.$no2, $tot_2_total_TGL_14)
                    ->setCellValue('Q'.$no2, $tot_2_total_TGL_15)
                    ->setCellValue('R'.$no2, $tot_2_total_TGL_16)
                    ->setCellValue('S'.$no2, $tot_2_total_TGL_17)
                    ->setCellValue('T'.$no2, $tot_2_total_TGL_18)
                    ->setCellValue('U'.$no2, $tot_2_total_TGL_19)
                    ->setCellValue('V'.$no2, $tot_2_total_TGL_20)
                    ->setCellValue('W'.$no2, $tot_2_total_TGL_21)
                    ->setCellValue('X'.$no2, $tot_2_total_TGL_22)
                    ->setCellValue('Y'.$no2, $tot_2_total_TGL_23)
                    ->setCellValue('Z'.$no2, $tot_2_total_TGL_24)
                    ->setCellValue('AA'.$no2, $tot_2_total_TGL_25)
                    ->setCellValue('AB'.$no2, $tot_2_total_TGL_26)
                    ->setCellValue('AC'.$no2, $tot_2_total_TGL_27)
                    ->setCellValue('AD'.$no2, $tot_2_total_TGL_28)
                    ->setCellValue('AE'.$no2, $tot_2_total_TGL_29)
                    ->setCellValue('AF'.$no2, $tot_2_total_TGL_30)
                    ->setCellValue('AG'.$no2, $tot_2_total_TGL_31);

        $sheet->getStyle('A'.$no2)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D2D2D2')
                ),
                'borders' => array(
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle('B'.$no2.':'.$arrKolom[$kemaren].$no2)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'AAFFAA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle($arrKolom[$now].$no2)->applyFromArray(
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

        
        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].$no2.':AH'.$no2)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAFFAA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );    
        }else{
            $sheet->getStyle('AG'.$no2.':AH'.$no2)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAFFAA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet($s)->setTitle('LR6 EACH GRADE');
    }elseif($Arr_sheet[$s] == "LR3 EACH GRADE"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'LR3 EACH GRADE '.$bln.' '.$not);

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A1:AH1');


        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A2', 'GRADE')
                    ->setCellValue('B2', 'REMARK')
                    ->setCellValue('C2',  $bln.' '.$not)
                    ->setCellValue('AH2', 'TOTAL');

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A2:A3');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('B2:B3');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('C2:AG2');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('AH2:AH3');

        $sheet = $objPHPExcel->getActiveSheet($s);

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('C3','1')
                    ->setCellValue('D3','2')
                    ->setCellValue('E3','3')
                    ->setCellValue('F3','4')
                    ->setCellValue('G3','5')
                    ->setCellValue('H3','6')
                    ->setCellValue('I3','7')
                    ->setCellValue('J3','8')
                    ->setCellValue('K3','9')
                    ->setCellValue('L3','10')
                    ->setCellValue('M3','11')
                    ->setCellValue('N3','12')
                    ->setCellValue('O3','13')
                    ->setCellValue('P3','14')
                    ->setCellValue('Q3','15')
                    ->setCellValue('R3','16')
                    ->setCellValue('S3','17')
                    ->setCellValue('T3','18')
                    ->setCellValue('U3','19')
                    ->setCellValue('V3','20')
                    ->setCellValue('W3','21')
                    ->setCellValue('X3','22')
                    ->setCellValue('Y3','23')
                    ->setCellValue('Z3','24')
                    ->setCellValue('AA3','25')
                    ->setCellValue('AB3','26')
                    ->setCellValue('AC3','27')
                    ->setCellValue('AD3','28')
                    ->setCellValue('AE3','29')
                    ->setCellValue('AF3','30')
                    ->setCellValue('AG3','31');

        for ($i=0;$i<count($arrABC);$i++){
            $sheet->getColumnDimension($arrABC[$i])->setWidth('14');
        }

        $objPHPExcel->getActiveSheet($s)->freezePane('C4');
        $sheet->getStyle('A2:AH3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A2:AH3')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A2:B3')->applyFromArray(
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

        $sheet->getStyle('C2:AG2')->applyFromArray(
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

        $sheet->getStyle('AH2:AH3')->applyFromArray(
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

        $sheet->getStyle('C3:'.$arrKolom[$kemaren].'3')->applyFromArray(
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

        $sheet->getStyle($arrKolom[$now].'3')->applyFromArray(
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

        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].'3:AG3')->applyFromArray(
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
        }else{
            $sheet->getStyle('AG3')->applyFromArray(
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
        }

        $no3 = 4;

        $tot_3_TGL_1 = 0;   $tot_3_TGL_10 = 0;      $tot_3_TGL_19 = 0;      $tot_3_TGL_28 = 0;
        $tot_3_TGL_2 = 0;   $tot_3_TGL_11 = 0;      $tot_3_TGL_20 = 0;      $tot_3_TGL_29 = 0;
        $tot_3_TGL_3 = 0;   $tot_3_TGL_12 = 0;      $tot_3_TGL_21 = 0;      $tot_3_TGL_30 = 0;
        $tot_3_TGL_4 = 0;   $tot_3_TGL_13 = 0;      $tot_3_TGL_22 = 0;      $tot_3_TGL_31 = 0;
        $tot_3_TGL_5 = 0;   $tot_3_TGL_14 = 0;      $tot_3_TGL_23 = 0;
        $tot_3_TGL_6 = 0;   $tot_3_TGL_15 = 0;      $tot_3_TGL_24 = 0;
        $tot_3_TGL_7 = 0;   $tot_3_TGL_16 = 0;      $tot_3_TGL_25 = 0;
        $tot_3_TGL_8 = 0;   $tot_3_TGL_17 = 0;      $tot_3_TGL_26 = 0;
        $tot_3_TGL_9 = 0;   $tot_3_TGL_18 = 0;      $tot_3_TGL_27 = 0;

        $tot_3_total_TGL_1 = 0;   $tot_3_total_TGL_10 = 0;      $tot_3_total_TGL_19 = 0;      $tot_3_total_TGL_28 = 0;
        $tot_3_total_TGL_2 = 0;   $tot_3_total_TGL_11 = 0;      $tot_3_total_TGL_20 = 0;      $tot_3_total_TGL_29 = 0;
        $tot_3_total_TGL_3 = 0;   $tot_3_total_TGL_12 = 0;      $tot_3_total_TGL_21 = 0;      $tot_3_total_TGL_30 = 0;
        $tot_3_total_TGL_4 = 0;   $tot_3_total_TGL_13 = 0;      $tot_3_total_TGL_22 = 0;      $tot_3_total_TGL_31 = 0;
        $tot_3_total_TGL_5 = 0;   $tot_3_total_TGL_14 = 0;      $tot_3_total_TGL_23 = 0;
        $tot_3_total_TGL_6 = 0;   $tot_3_total_TGL_15 = 0;      $tot_3_total_TGL_24 = 0;
        $tot_3_total_TGL_7 = 0;   $tot_3_total_TGL_16 = 0;      $tot_3_total_TGL_25 = 0;
        $tot_3_total_TGL_8 = 0;   $tot_3_total_TGL_17 = 0;      $tot_3_total_TGL_26 = 0;
        $tot_3_total_TGL_9 = 0;   $tot_3_total_TGL_18 = 0;      $tot_3_total_TGL_27 = 0;

        $ln3='';        $total_plan3 = 0;

        while ($row3=oci_fetch_object($data3)){
            $TGL_1 = $row3->DATE_1;   $TGL_10 = $row3->DATE_10;      $TGL_19 = $row3->DATE_19;      $TGL_28 = $row3->DATE_28;
            $TGL_2 = $row3->DATE_2;   $TGL_11 = $row3->DATE_11;      $TGL_20 = $row3->DATE_20;      $TGL_29 = $row3->DATE_29;
            $TGL_3 = $row3->DATE_3;   $TGL_12 = $row3->DATE_12;      $TGL_21 = $row3->DATE_21;      $TGL_30 = $row3->DATE_30;
            $TGL_4 = $row3->DATE_4;   $TGL_13 = $row3->DATE_13;      $TGL_22 = $row3->DATE_22;      $TGL_31 = $row3->DATE_31;
            $TGL_5 = $row3->DATE_5;   $TGL_14 = $row3->DATE_14;      $TGL_23 = $row3->DATE_23;
            $TGL_6 = $row3->DATE_6;   $TGL_15 = $row3->DATE_15;      $TGL_24 = $row3->DATE_24;
            $TGL_7 = $row3->DATE_7;   $TGL_16 = $row3->DATE_16;      $TGL_25 = $row3->DATE_25;
            $TGL_8 = $row3->DATE_8;   $TGL_17 = $row3->DATE_17;      $TGL_26 = $row3->DATE_26;
            $TGL_9 = $row3->DATE_9;   $TGL_18 = $row3->DATE_18;      $TGL_27 = $row3->DATE_27;
            
            if($no3 == 4){
                $tot_3_TGL_1 -= $TGL_1;   $tot_3_TGL_10 -= $TGL_10;      $tot_3_TGL_19 -= $TGL_19;      $tot_3_TGL_28 -= $TGL_28;
                $tot_3_TGL_2 -= $TGL_2;   $tot_3_TGL_11 -= $TGL_11;      $tot_3_TGL_20 -= $TGL_20;      $tot_3_TGL_29 -= $TGL_29;
                $tot_3_TGL_3 -= $TGL_3;   $tot_3_TGL_12 -= $TGL_12;      $tot_3_TGL_21 -= $TGL_21;      $tot_3_TGL_30 -= $TGL_30;
                $tot_3_TGL_4 -= $TGL_4;   $tot_3_TGL_13 -= $TGL_13;      $tot_3_TGL_22 -= $TGL_22;      $tot_3_TGL_31 -= $TGL_31;
                $tot_3_TGL_5 -= $TGL_5;   $tot_3_TGL_14 -= $TGL_14;      $tot_3_TGL_23 -= $TGL_23;      
                $tot_3_TGL_6 -= $TGL_6;   $tot_3_TGL_15 -= $TGL_15;      $tot_3_TGL_24 -= $TGL_24;      
                $tot_3_TGL_7 -= $TGL_7;   $tot_3_TGL_16 -= $TGL_16;      $tot_3_TGL_25 -= $TGL_25;
                $tot_3_TGL_8 -= $TGL_8;   $tot_3_TGL_17 -= $TGL_17;      $tot_3_TGL_26 -= $TGL_26;
                $tot_3_TGL_9 -= $TGL_9;   $tot_3_TGL_18 -= $TGL_18;      $tot_3_TGL_27 -= $TGL_27;
                $total_plan3 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                               $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                               $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                               $TGL_28+$TGL_29+$TGL_30+$TGL_31;
            }else{
                if ($ln3 == $row3->CELL_TYPE){
                    if($row3->KETERANGAN == "ACTUAL"){
                        $row3->CELL_TYPE = '';
                        $tot_3_TGL_1 += $TGL_1;   $tot_3_TGL_10 += $TGL_10;      $tot_3_TGL_19 += $TGL_19;      $tot_3_TGL_28 += $TGL_28;
                        $tot_3_TGL_2 += $TGL_2;   $tot_3_TGL_11 += $TGL_11;      $tot_3_TGL_20 += $TGL_20;      $tot_3_TGL_29 += $TGL_29;
                        $tot_3_TGL_3 += $TGL_3;   $tot_3_TGL_12 += $TGL_12;      $tot_3_TGL_21 += $TGL_21;      $tot_3_TGL_30 += $TGL_30;
                        $tot_3_TGL_4 += $TGL_4;   $tot_3_TGL_13 += $TGL_13;      $tot_3_TGL_22 += $TGL_22;      $tot_3_TGL_31 += $TGL_31;
                        $tot_3_TGL_5 += $TGL_5;   $tot_3_TGL_14 += $TGL_14;      $tot_3_TGL_23 += $TGL_23;      
                        $tot_3_TGL_6 += $TGL_6;   $tot_3_TGL_15 += $TGL_15;      $tot_3_TGL_24 += $TGL_24;      
                        $tot_3_TGL_7 += $TGL_7;   $tot_3_TGL_16 += $TGL_16;      $tot_3_TGL_25 += $TGL_25;
                        $tot_3_TGL_8 += $TGL_8;   $tot_3_TGL_17 += $TGL_17;      $tot_3_TGL_26 += $TGL_26;
                        $tot_3_TGL_9 += $TGL_9;   $tot_3_TGL_18 += $TGL_18;      $tot_3_TGL_27 += $TGL_27;

                        $total_plan3 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                              $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                              $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                              $TGL_28+$TGL_29+$TGL_30+$TGL_31;  
                    }
                }else{
                    $tot_3_total_TGL_1 = $tot_3_TGL_1;   
                    $tot_3_total_TGL_2 = $tot_3_total_TGL_1 + $tot_3_TGL_2 ;
                    $tot_3_total_TGL_3 = $tot_3_total_TGL_2 + $tot_3_TGL_3 ;
                    $tot_3_total_TGL_4 = $tot_3_total_TGL_3 + $tot_3_TGL_4 ;
                    $tot_3_total_TGL_5 = $tot_3_total_TGL_4 + $tot_3_TGL_5 ;
                    $tot_3_total_TGL_6 = $tot_3_total_TGL_5 + $tot_3_TGL_6 ;
                    $tot_3_total_TGL_7 = $tot_3_total_TGL_6 + $tot_3_TGL_7 ;
                    $tot_3_total_TGL_8 = $tot_3_total_TGL_7 + $tot_3_TGL_8 ;
                    $tot_3_total_TGL_9 = $tot_3_total_TGL_8 + $tot_3_TGL_9 ;
                    $tot_3_total_TGL_10 = $tot_3_total_TGL_9 + $tot_3_TGL_10 ;
                    $tot_3_total_TGL_11 = $tot_3_total_TGL_10 + $tot_3_TGL_11 ;
                    $tot_3_total_TGL_12 = $tot_3_total_TGL_11 + $tot_3_TGL_12 ;
                    $tot_3_total_TGL_13 = $tot_3_total_TGL_12 + $tot_3_TGL_13 ;
                    $tot_3_total_TGL_14 = $tot_3_total_TGL_13 + $tot_3_TGL_14 ;
                    $tot_3_total_TGL_15 = $tot_3_total_TGL_14 + $tot_3_TGL_15 ;
                    $tot_3_total_TGL_16 = $tot_3_total_TGL_15 + $tot_3_TGL_16 ;
                    $tot_3_total_TGL_17 = $tot_3_total_TGL_16 + $tot_3_TGL_17 ;
                    $tot_3_total_TGL_18 = $tot_3_total_TGL_17 + $tot_3_TGL_18 ;
                    $tot_3_total_TGL_19 = $tot_3_total_TGL_18 + $tot_3_TGL_19 ;
                    $tot_3_total_TGL_20 = $tot_3_total_TGL_19 + $tot_3_TGL_20 ;
                    $tot_3_total_TGL_21 = $tot_3_total_TGL_20 + $tot_3_TGL_21 ;
                    $tot_3_total_TGL_22 = $tot_3_total_TGL_21 + $tot_3_TGL_22 ;
                    $tot_3_total_TGL_23 = $tot_3_total_TGL_22 + $tot_3_TGL_23 ;
                    $tot_3_total_TGL_24 = $tot_3_total_TGL_23 + $tot_3_TGL_24 ;
                    $tot_3_total_TGL_25 = $tot_3_total_TGL_24 + $tot_3_TGL_25 ;
                    $tot_3_total_TGL_26 = $tot_3_total_TGL_25 + $tot_3_TGL_26 ;
                    $tot_3_total_TGL_27 = $tot_3_total_TGL_26 + $tot_3_TGL_27 ;
                    $tot_3_total_TGL_28 = $tot_3_total_TGL_27 + $tot_3_TGL_28 ;
                    $tot_3_total_TGL_29 = $tot_3_total_TGL_28 + $tot_3_TGL_29 ;
                    $tot_3_total_TGL_30 = $tot_3_total_TGL_29 + $tot_3_TGL_30 ;
                    $tot_3_total_TGL_31 = $tot_3_total_TGL_30 + $tot_3_TGL_31 ;

                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no3, '')
                                ->setCellValue('B'.$no3, 'GAP')
                                ->setCellValue('C'.$no3, $tot_3_TGL_1)
                                ->setCellValue('D'.$no3, $tot_3_TGL_2)
                                ->setCellValue('E'.$no3, $tot_3_TGL_3)
                                ->setCellValue('F'.$no3, $tot_3_TGL_4)
                                ->setCellValue('G'.$no3, $tot_3_TGL_5)
                                ->setCellValue('H'.$no3, $tot_3_TGL_6)
                                ->setCellValue('I'.$no3, $tot_3_TGL_7)
                                ->setCellValue('J'.$no3, $tot_3_TGL_8)
                                ->setCellValue('K'.$no3, $tot_3_TGL_9)
                                ->setCellValue('L'.$no3, $tot_3_TGL_10)
                                ->setCellValue('M'.$no3, $tot_3_TGL_11)
                                ->setCellValue('N'.$no3, $tot_3_TGL_12)
                                ->setCellValue('O'.$no3, $tot_3_TGL_13)
                                ->setCellValue('P'.$no3, $tot_3_TGL_14)
                                ->setCellValue('Q'.$no3, $tot_3_TGL_15)
                                ->setCellValue('R'.$no3, $tot_3_TGL_16)
                                ->setCellValue('S'.$no3, $tot_3_TGL_17)
                                ->setCellValue('T'.$no3, $tot_3_TGL_18)
                                ->setCellValue('U'.$no3, $tot_3_TGL_19)
                                ->setCellValue('V'.$no3, $tot_3_TGL_20)
                                ->setCellValue('W'.$no3, $tot_3_TGL_21)
                                ->setCellValue('X'.$no3, $tot_3_TGL_22)
                                ->setCellValue('Y'.$no3, $tot_3_TGL_23)
                                ->setCellValue('Z'.$no3, $tot_3_TGL_24)
                                ->setCellValue('AA'.$no3, $tot_3_TGL_25)
                                ->setCellValue('AB'.$no3, $tot_3_TGL_26)
                                ->setCellValue('AC'.$no3, $tot_3_TGL_27)
                                ->setCellValue('AD'.$no3, $tot_3_TGL_28)
                                ->setCellValue('AE'.$no3, $tot_3_TGL_29)
                                ->setCellValue('AF'.$no3, $tot_3_TGL_30)
                                ->setCellValue('AG'.$no3, $tot_3_TGL_31)
                                ->setCellValue('AH'.$no3, $tot_3_total_TGL_31);

                    $sheet->getStyle('A'.$no3)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D2D2D2')
                            )
                        )
                    );

                    $sheet->getStyle('B'.$no3.':'.$arrKolom[$kemaren].$no3)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'E2EFDA')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle($arrKolom[$now].$no3)->applyFromArray(
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

                    
                    if($arrKolom[$lusa] != 'AG'){
                        $sheet->getStyle($arrKolom[$lusa].$no3.':AH'.$no3)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'E2EFDA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );    
                    }else{
                        $sheet->getStyle('AG'.$no3.':AH'.$no3)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'E2EFDA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );
                    }   

                    $objPHPExcel->getActiveSheet()->getStyle('C'.$no3.':AH'.$no3)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $no3++;

                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no3, '')
                                ->setCellValue('B'.$no3, 'TOTAL')
                                ->setCellValue('C'.$no3, $tot_3_total_TGL_1)
                                ->setCellValue('D'.$no3, $tot_3_total_TGL_2)
                                ->setCellValue('E'.$no3, $tot_3_total_TGL_3)
                                ->setCellValue('F'.$no3, $tot_3_total_TGL_4)
                                ->setCellValue('G'.$no3, $tot_3_total_TGL_5)
                                ->setCellValue('H'.$no3, $tot_3_total_TGL_6)
                                ->setCellValue('I'.$no3, $tot_3_total_TGL_7)
                                ->setCellValue('J'.$no3, $tot_3_total_TGL_8)
                                ->setCellValue('K'.$no3, $tot_3_total_TGL_9)
                                ->setCellValue('L'.$no3, $tot_3_total_TGL_10)
                                ->setCellValue('M'.$no3, $tot_3_total_TGL_11)
                                ->setCellValue('N'.$no3, $tot_3_total_TGL_12)
                                ->setCellValue('O'.$no3, $tot_3_total_TGL_13)
                                ->setCellValue('P'.$no3, $tot_3_total_TGL_14)
                                ->setCellValue('Q'.$no3, $tot_3_total_TGL_15)
                                ->setCellValue('R'.$no3, $tot_3_total_TGL_16)
                                ->setCellValue('S'.$no3, $tot_3_total_TGL_17)
                                ->setCellValue('T'.$no3, $tot_3_total_TGL_18)
                                ->setCellValue('U'.$no3, $tot_3_total_TGL_19)
                                ->setCellValue('V'.$no3, $tot_3_total_TGL_20)
                                ->setCellValue('W'.$no3, $tot_3_total_TGL_21)
                                ->setCellValue('X'.$no3, $tot_3_total_TGL_22)
                                ->setCellValue('Y'.$no3, $tot_3_total_TGL_23)
                                ->setCellValue('Z'.$no3, $tot_3_total_TGL_24)
                                ->setCellValue('AA'.$no3, $tot_3_total_TGL_25)
                                ->setCellValue('AB'.$no3, $tot_3_total_TGL_26)
                                ->setCellValue('AC'.$no3, $tot_3_total_TGL_27)
                                ->setCellValue('AD'.$no3, $tot_3_total_TGL_28)
                                ->setCellValue('AE'.$no3, $tot_3_total_TGL_29)
                                ->setCellValue('AF'.$no3, $tot_3_total_TGL_30)
                                ->setCellValue('AG'.$no3, $tot_3_total_TGL_31);

                    $sheet->getStyle('A'.$no3)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D2D2D2')
                            ),
                            'borders' => array(
                                'bottom' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle('B'.$no3.':'.$arrKolom[$kemaren].$no3)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'AAFFAA')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );

                    $sheet->getStyle($arrKolom[$now].$no3)->applyFromArray(
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

                    
                    if($arrKolom[$lusa] != 'AG'){
                        $sheet->getStyle($arrKolom[$lusa].$no3.':AH'.$no3)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'AAFFAA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );    
                    }else{
                        $sheet->getStyle('AG'.$no3.':AH'.$no3)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'AAFFAA')
                                ),
                                'borders' => array(
                                    'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );
                    }

                    $objPHPExcel->getActiveSheet()->getStyle('C'.$no3.':AH'.$no3)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    $no3++;

                    $tot_3_TGL_1 = 0;   $tot_3_TGL_10 = 0;      $tot_3_TGL_19 = 0;      $tot_3_TGL_28 = 0;
                    $tot_3_TGL_2 = 0;   $tot_3_TGL_11 = 0;      $tot_3_TGL_20 = 0;      $tot_3_TGL_29 = 0;
                    $tot_3_TGL_3 = 0;   $tot_3_TGL_12 = 0;      $tot_3_TGL_21 = 0;      $tot_3_TGL_30 = 0;
                    $tot_3_TGL_4 = 0;   $tot_3_TGL_13 = 0;      $tot_3_TGL_22 = 0;      $tot_3_TGL_31 = 0;
                    $tot_3_TGL_5 = 0;   $tot_3_TGL_14 = 0;      $tot_3_TGL_23 = 0;      
                    $tot_3_TGL_6 = 0;   $tot_3_TGL_15 = 0;      $tot_3_TGL_24 = 0;      
                    $tot_3_TGL_7 = 0;   $tot_3_TGL_16 = 0;      $tot_3_TGL_25 = 0;
                    $tot_3_TGL_8 = 0;   $tot_3_TGL_17 = 0;      $tot_3_TGL_26 = 0;
                    $tot_3_TGL_9 = 0;   $tot_3_TGL_18 = 0;      $tot_3_TGL_27 = 0;

                    $tot_3_total_TGL_1 = 0;   $tot_3_total_TGL_10 = 0;      $tot_3_total_TGL_19 = 0;      $tot_3_total_TGL_28 = 0;
                    $tot_3_total_TGL_2 = 0;   $tot_3_total_TGL_11 = 0;      $tot_3_total_TGL_20 = 0;      $tot_3_total_TGL_29 = 0;
                    $tot_3_total_TGL_3 = 0;   $tot_3_total_TGL_12 = 0;      $tot_3_total_TGL_21 = 0;      $tot_3_total_TGL_30 = 0;
                    $tot_3_total_TGL_4 = 0;   $tot_3_total_TGL_13 = 0;      $tot_3_total_TGL_22 = 0;      $tot_3_total_TGL_31 = 0;
                    $tot_3_total_TGL_5 = 0;   $tot_3_total_TGL_14 = 0;      $tot_3_total_TGL_23 = 0;
                    $tot_3_total_TGL_6 = 0;   $tot_3_total_TGL_15 = 0;      $tot_3_total_TGL_24 = 0;
                    $tot_3_total_TGL_7 = 0;   $tot_3_total_TGL_16 = 0;      $tot_3_total_TGL_25 = 0;
                    $tot_3_total_TGL_8 = 0;   $tot_3_total_TGL_17 = 0;      $tot_3_total_TGL_26 = 0;
                    $tot_3_total_TGL_9 = 0;   $tot_3_total_TGL_18 = 0;      $tot_3_total_TGL_27 = 0;

                    $tot_3_total_TGL_31 = 0;

                    if($row2->KETERANGAN == "ACTUAL"){
                        $tot_3_TGL_1 += $TGL_1;   $tot_3_TGL_10 += $TGL_10;      $tot_3_TGL_19 += $TGL_19;      $tot_3_TGL_28 += $TGL_28;
                        $tot_3_TGL_2 += $TGL_2;   $tot_3_TGL_11 += $TGL_11;      $tot_3_TGL_20 += $TGL_20;      $tot_3_TGL_29 += $TGL_29;
                        $tot_3_TGL_3 += $TGL_3;   $tot_3_TGL_12 += $TGL_12;      $tot_3_TGL_21 += $TGL_21;      $tot_3_TGL_30 += $TGL_30;
                        $tot_3_TGL_4 += $TGL_4;   $tot_3_TGL_13 += $TGL_13;      $tot_3_TGL_22 += $TGL_22;      $tot_3_TGL_31 += $TGL_31;
                        $tot_3_TGL_5 += $TGL_5;   $tot_3_TGL_14 += $TGL_14;      $tot_3_TGL_23 += $TGL_23;      
                        $tot_3_TGL_6 += $TGL_6;   $tot_3_TGL_15 += $TGL_15;      $tot_3_TGL_24 += $TGL_24;      
                        $tot_3_TGL_7 += $TGL_7;   $tot_3_TGL_16 += $TGL_16;      $tot_3_TGL_25 += $TGL_25;
                        $tot_3_TGL_8 += $TGL_8;   $tot_3_TGL_17 += $TGL_17;      $tot_3_TGL_26 += $TGL_26;
                        $tot_3_TGL_9 += $TGL_9;   $tot_3_TGL_18 += $TGL_18;      $tot_3_TGL_27 += $TGL_27;

                        $total_plan3 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                              $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                              $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                              $TGL_28+$TGL_29+$TGL_30+$TGL_31;  
                    }else{
                        $tot_3_TGL_1 -= $TGL_1;   $tot_3_TGL_10 -= $TGL_10;      $tot_3_TGL_19 -= $TGL_19;      $tot_3_TGL_28 -= $TGL_28;
                        $tot_3_TGL_2 -= $TGL_2;   $tot_3_TGL_11 -= $TGL_11;      $tot_3_TGL_20 -= $TGL_20;      $tot_3_TGL_29 -= $TGL_29;
                        $tot_3_TGL_3 -= $TGL_3;   $tot_3_TGL_12 -= $TGL_12;      $tot_3_TGL_21 -= $TGL_21;      $tot_3_TGL_30 -= $TGL_30;
                        $tot_3_TGL_4 -= $TGL_4;   $tot_3_TGL_13 -= $TGL_13;      $tot_3_TGL_22 -= $TGL_22;      $tot_3_TGL_31 -= $TGL_31;
                        $tot_3_TGL_5 -= $TGL_5;   $tot_3_TGL_14 -= $TGL_14;      $tot_3_TGL_23 -= $TGL_23;      
                        $tot_3_TGL_6 -= $TGL_6;   $tot_3_TGL_15 -= $TGL_15;      $tot_3_TGL_24 -= $TGL_24;      
                        $tot_3_TGL_7 -= $TGL_7;   $tot_3_TGL_16 -= $TGL_16;      $tot_3_TGL_25 -= $TGL_25;
                        $tot_3_TGL_8 -= $TGL_8;   $tot_3_TGL_17 -= $TGL_17;      $tot_3_TGL_26 -= $TGL_26;
                        $tot_3_TGL_9 -= $TGL_9;   $tot_3_TGL_18 -= $TGL_18;      $tot_3_TGL_27 -= $TGL_27;

                        $total_plan3 = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+
                                  $TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+
                                  $TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+
                                  $TGL_28+$TGL_29+$TGL_30+$TGL_31;
                    }
                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no3, $row3->CELL_TYPE)
                        ->setCellValue('B'.$no3, $row3->KETERANGAN)
                        ->setCellValue('C'.$no3, $TGL_1)
                        ->setCellValue('D'.$no3, $TGL_2)
                        ->setCellValue('E'.$no3, $TGL_3)
                        ->setCellValue('F'.$no3, $TGL_4)
                        ->setCellValue('G'.$no3, $TGL_5)
                        ->setCellValue('H'.$no3, $TGL_6)
                        ->setCellValue('I'.$no3, $TGL_7)
                        ->setCellValue('J'.$no3, $TGL_8)
                        ->setCellValue('K'.$no3, $TGL_9)
                        ->setCellValue('L'.$no3, $TGL_10)
                        ->setCellValue('M'.$no3, $TGL_11)
                        ->setCellValue('N'.$no3, $TGL_12)
                        ->setCellValue('O'.$no3, $TGL_13)
                        ->setCellValue('P'.$no3, $TGL_14)
                        ->setCellValue('Q'.$no3, $TGL_15)
                        ->setCellValue('R'.$no3, $TGL_16)
                        ->setCellValue('S'.$no3, $TGL_17)
                        ->setCellValue('T'.$no3, $TGL_18)
                        ->setCellValue('U'.$no3, $TGL_19)
                        ->setCellValue('V'.$no3, $TGL_20)
                        ->setCellValue('W'.$no3, $TGL_21)
                        ->setCellValue('X'.$no3, $TGL_22)
                        ->setCellValue('Y'.$no3, $TGL_23)
                        ->setCellValue('Z'.$no3, $TGL_24)
                        ->setCellValue('AA'.$no3, $TGL_25)
                        ->setCellValue('AB'.$no3, $TGL_26)
                        ->setCellValue('AC'.$no3, $TGL_27)
                        ->setCellValue('AD'.$no3, $TGL_28)
                        ->setCellValue('AE'.$no3, $TGL_29)
                        ->setCellValue('AF'.$no3, $TGL_30)
                        ->setCellValue('AG'.$no3, $TGL_31)
                        ->setCellValue('AH'.$no3, $total_plan3);

            if($row3->KETERANGAN == 'PLAN'){
                $sheet->getStyle('A'.$no3)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'D2D2D2')
                        )
                    )
                );

                $sheet->getStyle('B'.$no3.':'.$arrKolom[$kemaren].$no3)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'DDEBF7')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );

                $sheet->getStyle($arrKolom[$now].$no3)->applyFromArray(
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

                
                if($arrKolom[$lusa] != 'AG'){
                    $sheet->getStyle($arrKolom[$lusa].$no3.':AH'.$no3)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'DDEBF7')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );    
                }else{
                    $sheet->getStyle('AG'.$no3.':AH'.$no3)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'DDEBF7')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );
                }
            }elseif($row3->KETERANGAN == 'ACTUAL'){
                $sheet->getStyle('A'.$no3)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'D2D2D2')
                        )
                    )
                );

                $sheet->getStyle('B'.$no3.':'.$arrKolom[$kemaren].$no3)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'FCE4D6')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );

                $sheet->getStyle($arrKolom[$now].$no3)->applyFromArray(
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

                
                if($arrKolom[$lusa] != 'AG'){
                    $sheet->getStyle($arrKolom[$lusa].$no3.':AH'.$no3)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FCE4D6')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );    
                }else{
                    $sheet->getStyle('AG'.$no3.':AH'.$no3)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FCE4D6')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            )
                        )
                    );
                }
            }

            $objPHPExcel->getActiveSheet()->getStyle('C'.$no3.':AH'.$no3)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $ln3 = $row3->CELL_TYPE;
            $no3++;
        }

        $tot_3_total_TGL_1 = $tot_3_TGL_1;   
        $tot_3_total_TGL_2 = $tot_3_total_TGL_1 + $tot_3_TGL_2 ;
        $tot_3_total_TGL_3 = $tot_3_total_TGL_2 + $tot_3_TGL_3 ;
        $tot_3_total_TGL_4 = $tot_3_total_TGL_3 + $tot_3_TGL_4 ;
        $tot_3_total_TGL_5 = $tot_3_total_TGL_4 + $tot_3_TGL_5 ;
        $tot_3_total_TGL_6 = $tot_3_total_TGL_5 + $tot_3_TGL_6 ;
        $tot_3_total_TGL_7 = $tot_3_total_TGL_6 + $tot_3_TGL_7 ;
        $tot_3_total_TGL_8 = $tot_3_total_TGL_7 + $tot_3_TGL_8 ;
        $tot_3_total_TGL_9 = $tot_3_total_TGL_8 + $tot_3_TGL_9 ;
        $tot_3_total_TGL_10 = $tot_3_total_TGL_9 + $tot_3_TGL_10 ;
        $tot_3_total_TGL_11 = $tot_3_total_TGL_10 + $tot_3_TGL_11 ;
        $tot_3_total_TGL_12 = $tot_3_total_TGL_11 + $tot_3_TGL_12 ;
        $tot_3_total_TGL_13 = $tot_3_total_TGL_12 + $tot_3_TGL_13 ;
        $tot_3_total_TGL_14 = $tot_3_total_TGL_13 + $tot_3_TGL_14 ;
        $tot_3_total_TGL_15 = $tot_3_total_TGL_14 + $tot_3_TGL_15 ;
        $tot_3_total_TGL_16 = $tot_3_total_TGL_15 + $tot_3_TGL_16 ;
        $tot_3_total_TGL_17 = $tot_3_total_TGL_16 + $tot_3_TGL_17 ;
        $tot_3_total_TGL_18 = $tot_3_total_TGL_17 + $tot_3_TGL_18 ;
        $tot_3_total_TGL_19 = $tot_3_total_TGL_18 + $tot_3_TGL_19 ;
        $tot_3_total_TGL_20 = $tot_3_total_TGL_19 + $tot_3_TGL_20 ;
        $tot_3_total_TGL_21 = $tot_3_total_TGL_20 + $tot_3_TGL_21 ;
        $tot_3_total_TGL_22 = $tot_3_total_TGL_21 + $tot_3_TGL_22 ;
        $tot_3_total_TGL_23 = $tot_3_total_TGL_22 + $tot_3_TGL_23 ;
        $tot_3_total_TGL_24 = $tot_3_total_TGL_23 + $tot_3_TGL_24 ;
        $tot_3_total_TGL_25 = $tot_3_total_TGL_24 + $tot_3_TGL_25 ;
        $tot_3_total_TGL_26 = $tot_3_total_TGL_25 + $tot_3_TGL_26 ;
        $tot_3_total_TGL_27 = $tot_3_total_TGL_26 + $tot_3_TGL_27 ;
        $tot_3_total_TGL_28 = $tot_3_total_TGL_27 + $tot_3_TGL_28 ;
        $tot_3_total_TGL_29 = $tot_3_total_TGL_28 + $tot_3_TGL_29 ;
        $tot_3_total_TGL_30 = $tot_3_total_TGL_29 + $tot_3_TGL_30 ;
        $tot_3_total_TGL_31 = $tot_3_total_TGL_30 + $tot_3_TGL_31 ;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no3, '')
                    ->setCellValue('B'.$no3, 'GAP')
                    ->setCellValue('C'.$no3, $tot_3_TGL_1)
                    ->setCellValue('D'.$no3, $tot_3_TGL_2)
                    ->setCellValue('E'.$no3, $tot_3_TGL_3)
                    ->setCellValue('F'.$no3, $tot_3_TGL_4)
                    ->setCellValue('G'.$no3, $tot_3_TGL_5)
                    ->setCellValue('H'.$no3, $tot_3_TGL_6)
                    ->setCellValue('I'.$no3, $tot_3_TGL_7)
                    ->setCellValue('J'.$no3, $tot_3_TGL_8)
                    ->setCellValue('K'.$no3, $tot_3_TGL_9)
                    ->setCellValue('L'.$no3, $tot_3_TGL_10)
                    ->setCellValue('M'.$no3, $tot_3_TGL_11)
                    ->setCellValue('N'.$no3, $tot_3_TGL_12)
                    ->setCellValue('O'.$no3, $tot_3_TGL_13)
                    ->setCellValue('P'.$no3, $tot_3_TGL_14)
                    ->setCellValue('Q'.$no3, $tot_3_TGL_15)
                    ->setCellValue('R'.$no3, $tot_3_TGL_16)
                    ->setCellValue('S'.$no3, $tot_3_TGL_17)
                    ->setCellValue('T'.$no3, $tot_3_TGL_18)
                    ->setCellValue('U'.$no3, $tot_3_TGL_19)
                    ->setCellValue('V'.$no3, $tot_3_TGL_20)
                    ->setCellValue('W'.$no3, $tot_3_TGL_21)
                    ->setCellValue('X'.$no3, $tot_3_TGL_22)
                    ->setCellValue('Y'.$no3, $tot_3_TGL_23)
                    ->setCellValue('Z'.$no3, $tot_3_TGL_24)
                    ->setCellValue('AA'.$no3, $tot_3_TGL_25)
                    ->setCellValue('AB'.$no3, $tot_3_TGL_26)
                    ->setCellValue('AC'.$no3, $tot_3_TGL_27)
                    ->setCellValue('AD'.$no3, $tot_3_TGL_28)
                    ->setCellValue('AE'.$no3, $tot_3_TGL_29)
                    ->setCellValue('AF'.$no3, $tot_3_TGL_30)
                    ->setCellValue('AG'.$no3, $tot_3_TGL_31)
                    ->setCellValue('AH'.$no3, $tot_3_total_TGL_31);

        $sheet->getStyle('A'.$no3)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D2D2D2')
                )
            )
        );

        $sheet->getStyle('B'.$no3.':'.$arrKolom[$kemaren].$no3)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E2EFDA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle($arrKolom[$now].$no3)->applyFromArray(
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

        
        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].$no3.':AH'.$no3)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'E2EFDA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );    
        }else{
            $sheet->getStyle('AG'.$no3.':AH'.$no3)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'E2EFDA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
        }   

        $objPHPExcel->getActiveSheet()->getStyle('C'.$no3.':AH'.$no3)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $no3++;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no3, '')
                    ->setCellValue('B'.$no3, 'TOTAL')
                    ->setCellValue('C'.$no3, $tot_3_total_TGL_1)
                    ->setCellValue('D'.$no3, $tot_3_total_TGL_2)
                    ->setCellValue('E'.$no3, $tot_3_total_TGL_3)
                    ->setCellValue('F'.$no3, $tot_3_total_TGL_4)
                    ->setCellValue('G'.$no3, $tot_3_total_TGL_5)
                    ->setCellValue('H'.$no3, $tot_3_total_TGL_6)
                    ->setCellValue('I'.$no3, $tot_3_total_TGL_7)
                    ->setCellValue('J'.$no3, $tot_3_total_TGL_8)
                    ->setCellValue('K'.$no3, $tot_3_total_TGL_9)
                    ->setCellValue('L'.$no3, $tot_3_total_TGL_10)
                    ->setCellValue('M'.$no3, $tot_3_total_TGL_11)
                    ->setCellValue('N'.$no3, $tot_3_total_TGL_12)
                    ->setCellValue('O'.$no3, $tot_3_total_TGL_13)
                    ->setCellValue('P'.$no3, $tot_3_total_TGL_14)
                    ->setCellValue('Q'.$no3, $tot_3_total_TGL_15)
                    ->setCellValue('R'.$no3, $tot_3_total_TGL_16)
                    ->setCellValue('S'.$no3, $tot_3_total_TGL_17)
                    ->setCellValue('T'.$no3, $tot_3_total_TGL_18)
                    ->setCellValue('U'.$no3, $tot_3_total_TGL_19)
                    ->setCellValue('V'.$no3, $tot_3_total_TGL_20)
                    ->setCellValue('W'.$no3, $tot_3_total_TGL_21)
                    ->setCellValue('X'.$no3, $tot_3_total_TGL_22)
                    ->setCellValue('Y'.$no3, $tot_3_total_TGL_23)
                    ->setCellValue('Z'.$no3, $tot_3_total_TGL_24)
                    ->setCellValue('AA'.$no3, $tot_3_total_TGL_25)
                    ->setCellValue('AB'.$no3, $tot_3_total_TGL_26)
                    ->setCellValue('AC'.$no3, $tot_3_total_TGL_27)
                    ->setCellValue('AD'.$no3, $tot_3_total_TGL_28)
                    ->setCellValue('AE'.$no3, $tot_3_total_TGL_29)
                    ->setCellValue('AF'.$no3, $tot_3_total_TGL_30)
                    ->setCellValue('AG'.$no3, $tot_3_total_TGL_31);

        $sheet->getStyle('A'.$no3)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D2D2D2')
                ),
                'borders' => array(
                    'bottom' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle('B'.$no3.':'.$arrKolom[$kemaren].$no3)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'AAFFAA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            )
        );

        $sheet->getStyle($arrKolom[$now].$no3)->applyFromArray(
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

        
        if($arrKolom[$lusa] != 'AG'){
            $sheet->getStyle($arrKolom[$lusa].$no3.':AH'.$no3)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAFFAA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );    
        }else{
            $sheet->getStyle('AG'.$no3.':AH'.$no3)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'AAFFAA')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet($s)->setTitle('LR3 EACH GRADE');
    }elseif($Arr_sheet[$s] == "SUMMARY TROUBLE"){
    	$objWorkSheet = $objPHPExcel->createSheet();

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'SUMMARY TROUBLE '.$bln.' '.$not);

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A1:F1');

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A2','ASSY LINE')
                    ->setCellValue('B2','TOTAL TIME (MINUTE)')
                    ->setCellValue('C2','PROCESS')
                    ->setCellValue('D2','PROBLEM')
                    ->setCellValue('E2','TIME (MINUTE)')
                    ->setCellValue('F2','FREQ');
        
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth('14');
        $sheet->getColumnDimension('B')->setWidth('12');
        $sheet->getColumnDimension('C')->setWidth('30');
        $sheet->getColumnDimension('D')->setWidth('40');
        $sheet->getColumnDimension('E')->setWidth('12');
        $sheet->getColumnDimension('F')->setWidth('12');

        $objPHPExcel->getActiveSheet($s)->freezePane('A3');
        $sheet->getStyle('A2:F2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A2:F2')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A2:F2')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'D2D2D2')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'alignment' => array(
			        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
			        'wrap' => true
			    )
            )
        );


        $no40=3;
        $ln40 = '';     $line40 = '';		$process='';
        $ng_pro = '';   $ng_proses = '';    $ng_time = '';

        while ($row40=oci_fetch_object($data40)) {
            if($ln40 == $row40->ASSY_LINE){
                $line40 = '';
            }else{
                $line40 = $row40->ASSY_LINE;
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no40, $line40)
                        ->setCellValue('B'.$no40, $row40->TOTAL_QTY)
                        ->setCellValue('C'.$no40, $row40->NG_NAME_PROSES)
                        ->setCellValue('D'.$no40, $row40->NG_NAME)
                        ->setCellValue('E'.$no40, $row40->QTY)
                        ->setCellValue('F'.$no40, $row40->FREQ);

            $sheet->getStyle('A'.$no40)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'D2D2D2')
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'alignment' => array(
                        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap' => true
                    )
                )
            );

            if ($no40 % 2 == 0){
                $sheet->getStyle('B'.$no40.':F'.$no40)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'DDEBF7')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        ),
                        'alignment' => array(
                            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'wrap' => true
                        )
                    )
                );
            }else{
                $sheet->getStyle('B'.$no40.':F'.$no40)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'AAFFAA')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        ),
                        'alignment' => array(
                            'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'wrap' => true
                        )
                    )
                );
            }

            $objPHPExcel->getActiveSheet()->getStyle('A'.$no40.':F'.$no40)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $ln40 = $row40->ASSY_LINE;
            $no40++;         
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('SUMMARY TROUBLE');
	}elseif($Arr_sheet[$s] == "TROUBLE"){
        $objWorkSheet = $objPHPExcel->createSheet();

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'TROUBLE '.$bln.' '.$not);

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A1:F1');

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A2','DATE')
                    ->setCellValue('B2','LINE')
                    ->setCellValue('C2','PROCESS')
                    ->setCellValue('D2','NG NAME')
                    ->setCellValue('E2','TIME (MINUTE)')
                    ->setCellValue('F2','MAINTENANCE');
        
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->getColumnDimension('A')->setWidth('14');
        $sheet->getColumnDimension('B')->setWidth('14');
        $sheet->getColumnDimension('C')->setWidth('25');
        $sheet->getColumnDimension('D')->setWidth('25');
        $sheet->getColumnDimension('E')->setWidth('14');
        $sheet->getColumnDimension('F')->setWidth('50');

        $objPHPExcel->getActiveSheet($s)->freezePane('A3');
        $sheet->getStyle('A2:F2')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A2:F2')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A2:F2')->applyFromArray(
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

        $no4=3;

        while ($row4=oci_fetch_object($data4)) {
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no4, $row4->TANGGAL_PRODUKSI)
                        ->setCellValue('B'.$no4, $row4->ASSY_LINE)
                        ->setCellValue('C'.$no4, $row4->NG_NAME_PROSES)
                        ->setCellValue('D'.$no4, $row4->NG_NAME)
                        ->setCellValue('E'.$no4, $row4->NG_QTY)
                        ->setCellValue('F'.$no4, $row4->PERBAIKAN);

            //$objPHPExcel->getActiveSheet($s)->getStyle('F'.$no4)->getAlignment()->setWrapText(true); 

            if ($no4 % 2 == 0){
            	$sheet->getStyle('A'.$no4.':F'.$no4)->applyFromArray(
	                array(
	                    'fill' => array(
		                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                    'color' => array('rgb' => 'DDEBF7')
		                ),
	                    'borders' => array(
	                        'allborders' => array(
	                            'style' => PHPExcel_Style_Border::BORDER_THIN
	                        )
	                    ),
	                    'alignment' => array(
					        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
					        'wrap' => true
					    )
	                )
	            );
            }else{
            	$sheet->getStyle('A'.$no4.':F'.$no4)->applyFromArray(
	                array(
	                    'fill' => array(
		                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
		                    'color' => array('rgb' => 'AAFFAA')
		                ),
	                    'borders' => array(
	                        'allborders' => array(
	                            'style' => PHPExcel_Style_Border::BORDER_THIN
	                        )
	                    ),
	                    'alignment' => array(
					        'vertical'=> PHPExcel_Style_Alignment::VERTICAL_CENTER,
					        'wrap' => true
					    )
	                )
	            );
            }

            $objPHPExcel->getActiveSheet()->getStyle('A'.$no4.':F'.$no4)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $no4++;
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('TROUBLE');
    }
    $s++;
}
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace(__FILE__,$_SERVER['DOCUMMENT_ROOT'].'C:\xampp/htdocs/wms/kanban/assy/assembly_report.xls',__FILE__));
?>