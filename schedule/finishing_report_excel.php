<?php
error_reporting(0);
ini_set('memory_limit', '-1');
set_time_limit(0);
require_once '../class/phpexcel/PHPExcel.php';
include("../connect/conn.php");
$s=0;

$min_hari = mktime(0,0,0,date("n"),date("j")-1,date("Y"));
$kemaren = intval(date("d", $min_hari));

$now = intval(date('d')); 

$plus_hari = mktime(0,0,0,date("n"),date("j")+1,date("Y"));       
$lusa = intval(date("d", $plus_hari));

$arrBulan = array('1' => 'JANUARY', '2' => 'FEBRUARY', '3' => 'MARCH', '4' => 'APRIL', '5' => 'MAY', '6' => 'JUNE', '7' => 'JULY', '8' => 'AUGUST', '9' => 'SEPTEMBER', '10' => 'OCTOBER', '11' => 'NOVEMBER', '12' => 'DECEMBER');

if (intval(date('d')) == 1){
    $nob = intval(date('m'))-1;
    $bln = $arrBulan[$nob];
}else{
    $bln = strtoupper(date('m_checkstatus(conn, identifier)'));
}

$arrKolom = array('1' => 'E','2' => 'F','3' => 'G','4' => 'H','5' => 'I','6' => 'J','7' => 'K','8' => 'L','9' => 'M','10' => 'N',
                  '11' => 'O','12' => 'P','13' => 'Q','14' => 'R','15' => 'S','16' => 'T','17' => 'U','18' => 'V','19' => 'W','20' => 'X',
                  '21' => 'Y','22' => 'Z','23' => 'AA','24' => 'AB','25' => 'AC','26' => 'AD','27' => 'AE','28' => 'AF','29' => 'AG','30' => 'AH',
                  '31' => 'AI', '32' => 'AJ');

$arrKolom2 = array('1' => 'N', '2' => 'O',  '3' => 'P',  '4' => 'Q',  '5' => 'R',  '6' => 'S',  '7' => 'T',  '8' => 'U',  '9' => 'V',  '10' => 'W', 
                   '11' => 'X', '12' => 'Y',  '13' => 'Z',  '14' => 'AA', '15' => 'AB', '16' => 'AC', '17' => 'AD', '18' => 'AE', '19' => 'AF', '20' => 'AG',
                   '21' => 'AH', '22' => 'AI', '23' => 'AJ', '24' => 'AK', '25' => 'AL', '26' => 'AM', '27' => 'AN', '28' => 'AO', '29' => 'AP', '30' => 'AQ',
                   '31' => 'AR', '32' => 'AS');

$Arr_sheet = array('TOTAL ALL BATTERY','BATTERY TYPE','PACKAGING GROUP','COMPARATION','DELAY ORDER','SUMMARY','TODAY ORDER','MOVEUP ORDER');

$qry1 = "select 'TOTAL' as groupinglabel,case when Stat = 'C' Then 'OUTPUT' else 'PLAN' END as orders, 
        sum(satu) as tgl_1,sum(dua) as tgl_2, sum(tiga) as tgl_3, sum(empat) as tgl_4, sum(lima) as tgl_5, 
        sum(enam) as tgl_6, sum(tujuh) as tgl_7, sum(delapan) as tgl_8, sum(sembilan) as tgl_9, sum(sepuluh) as tgl_10,
        sum(sebelas) as tgl_11,sum(duabelas) as tgl_12, sum(tigabelas) as tgl_13, sum(empatbelas) as tgl_14, sum(limabelas) as tgl_15, 
        sum(enambelas) as tgl_16, sum(tujuhbelas) as tgl_17, sum(delapanbelas) as tgl_18, sum(sembilanbelas) as tgl_19, sum(duapuluh) as tgl_20,
        sum(duapuluhsatu) as tgl_21,sum(duapuluhdua) as tgl_22, sum(duapuluhtiga) as tgl_23, sum(duapuluhempat) as tgl_24, sum(duapuluhlima) as tgl_25, 
        sum(duapuluhenam) as tgl_26, sum(duapuluhtujuh) as tgl_27, sum(duapuluhdelapan) as tgl_28, sum(duapuluhsembilan) as tgl_29, sum(tigapuluh) as tgl_30,
        sum(tigapuluhsatu) as tgl_31, sum(total) as total
        from zvw_comparison3 where BULAN = case when day(getdate()) = 1 then FORMAT(getdate()-1,'MM') else FORMAT(getdate(),'MM') end and stat in ('A','C') and groupinglabel is not null   group by Stat order by groupinglabel";

        $data1 = sqlsrv_query($connect, strtoupper($qry1));
        //echo $qry1;

$qry2 = "select batery_type as groupinglabel,case when Stat = 'C' Then 'OUTPUT' else 'PLAN' END as orders, 
        sum(satu) as tgl_1,sum(dua) as tgl_2, sum(tiga) as tgl_3, sum(empat) as tgl_4, sum(lima) as tgl_5, 
        sum(enam) as tgl_6, sum(tujuh) as tgl_7, sum(delapan) as tgl_8, sum(sembilan) as tgl_9, sum(sepuluh) as tgl_10,
        sum(sebelas) as tgl_11,sum(duabelas) as tgl_12, sum(tigabelas) as tgl_13, sum(empatbelas) as tgl_14, sum(limabelas) as tgl_15, 
        sum(enambelas) as tgl_16, sum(tujuhbelas) as tgl_17, sum(delapanbelas) as tgl_18, sum(sembilanbelas) as tgl_19, sum(duapuluh) as tgl_20,
        sum(duapuluhsatu) as tgl_21,sum(duapuluhdua) as tgl_22, sum(duapuluhtiga) as tgl_23, sum(duapuluhempat) as tgl_24, sum(duapuluhlima) as tgl_25, 
        sum(duapuluhenam) as tgl_26, sum(duapuluhtujuh) as tgl_27, sum(duapuluhdelapan) as tgl_28, sum(duapuluhsembilan) as tgl_29, sum(tigapuluh) as tgl_30,
        sum(tigapuluhsatu) as tgl_31, sum(total) as total
        from zvw_comparison3 where BULAN = case when day(getdate()) = 1 then FORMAT(getdate()-1,'MM') else FORMAT(getdate(),'MM') end and stat in ('A','C') and groupinglabel is not null group by Stat,batery_type order by groupinglabel";
$data2 = sqlsrv_query($connect, strtoupper($qry2));

$qry3 = "select groupinglabel  as groupinglabel,
               case groupinglabel 
                when 'AUTO SHRINK LR03' then 1
                when 'AUTO SHRINK LR6' then 2
                when 'MULTI SHRINK' then 3
                when 'MANUAL SHRINK' then 4
                else 10 end Urut
                ,case when Stat = 'C' Then 'OUTPUT' else 'PLAN' END as orders, 
        sum(satu) as tgl_1,sum(dua) as tgl_2, sum(tiga) as tgl_3, sum(empat) as tgl_4, sum(lima) as tgl_5, 
        sum(enam) as tgl_6, sum(tujuh) as tgl_7, sum(delapan) as tgl_8, sum(sembilan) as tgl_9, sum(sepuluh) as tgl_10,
        sum(sebelas) as tgl_11,sum(duabelas) as tgl_12, sum(tigabelas) as tgl_13, sum(empatbelas) as tgl_14, sum(limabelas) as tgl_15, 
        sum(enambelas) as tgl_16, sum(tujuhbelas) as tgl_17, sum(delapanbelas) as tgl_18, sum(sembilanbelas) as tgl_19, sum(duapuluh) as tgl_20,
        sum(duapuluhsatu) as tgl_21,sum(duapuluhdua) as tgl_22, sum(duapuluhtiga) as tgl_23, sum(duapuluhempat) as tgl_24, sum(duapuluhlima) as tgl_25, 
        sum(duapuluhenam) as tgl_26, sum(duapuluhtujuh) as tgl_27, sum(duapuluhdelapan) as tgl_28, sum(duapuluhsembilan) as tgl_29, sum(tigapuluh) as tgl_30,
        sum(tigapuluhsatu) as tgl_31, sum(total) as total
        from zvw_comparison3 where BULAN = case when day(getdate()) = 1 then FORMAT(getdate()-1,'MM') else FORMAT(getdate(),'MM') end and stat in ('A','C') and groupinglabel is not null group by Stat,groupinglabel order by Urut,groupinglabel";
$data3 = sqlsrv_query($connect, strtoupper($qry3));
// echo $qry3;

$qry4 = "
declare @var varchar(5) = ''
select @var = case when day(getdate()) = 1 then FORMAT(getdate()-1,'MM') else FORMAT(getdate(),'MM') end 
select distinct LABEL_TYPE,GROUPINGLABEL,WORK_ORDER,ITEM_NO,ITEM_NAME,DATE_CODE,PACKAGING_TYPE,BATERY_TYPE,GRADE as GRADE_CODE,GRADE,cast(CR_DATE as varchar(10)) cr_date,OPERATEION_TIME ,QTY,BULAN,TOTAL,STAT,SATU,DUA,TIGA,EMPAT,LIMA,ENAM,TUJUH,DELAPAN,SEMBILAN,SEPULUH,SEBELAS,DUABELAS,TIGABELAS,EMPATBELAS,LIMABELAS,ENAMBELAS,TUJUHBELAS,DELAPANBELAS,SEMBILANBELAS,DUAPULUH,DUAPULUHSATU,DUAPULUHDUA,DUAPULUHTIGA,DUAPULUHEMPAT,DUAPULUHLIMA,DUAPULUHENAM,DUAPULUHTUJUH,DUAPULUHDELAPAN,DUAPULUHSEMBILAN,TIGAPULUH,TIGAPULUHSATU 
from zvw_comparison3 where stat in ('A','C') and bulan = @var order by label_type, cr_date, work_order";
$data4 = sqlsrv_query($connect, strtoupper($qry4));

$qry5 = "select package_type,work_order,cast(cr_date as varchar(10)) cr_date,item_no,item,description,label_type_name,batt_type,qty,plan_qty,actualQty,delayQty from zvw_production_delay order by package_type,cr_Date";
$data5 = sqlsrv_query($connect, strtoupper($qry5));
// echo $qry5;

$qry51 = "select package_type,work_order,cast(cr_date as varchar(10)) cr_date,item_no,item,description,label_type_name,batt_type,qty,plan_qty,actualQty,MoveUpQty from ZVW_PRODUCTION_MOVEUP order by package_type,cr_Date";
$data51 = sqlsrv_query($connect, strtoupper($qry51));
// echo $qry6;

$qry61 = "

declare @var varchar(5) = ''
select @var = case when day(getdate()) = 1 then FORMAT(getdate()-1,'MM') else FORMAT(getdate(),'MM') end 

select batery_type,sum(pln) as pln, sum(output)as output,sum(acumm) as accumulation  
          from zvw_comparison_summary where bulan = @var and hari <= day((getdate() -1)) group by batery_type";
$data61 = sqlsrv_query($connect, strtoupper($qry61));
// echo $qry61;

$qry62 = "
declare @var varchar(5) = ''
select @var = case when day(getdate()) = 1 then FORMAT(getdate()-1,'MM') else FORMAT(getdate(),'MM') end 


select label_type,sum(pln) as pln, sum(output)as output,sum(acumm) as accumulation  
          from zvw_comparison_summary where bulan = @var and hari <= day((getdate() - 1)) group by label_type";
$data62 = sqlsrv_query($connect, strtoupper($qry62));
// echo $qry62;

$qry63 = "
declare @var varchar(5) = ''
select @var = case when day(getdate()) = 1 then FORMAT(getdate()-1,'MM') else FORMAT(getdate(),'MM') end 


select label_type,sum(pln) as pln, sum(output)as output,sum(acumm) as accumulation  
          from zvw_comparison_summary where bulan = @var and hari = day((getdate() - 1))  group by label_type";
$data63 = sqlsrv_query($connect, strtoupper($qry63));

$qry7 = "select work_order, cr_date, hr.item_no, it.item, it.description, lt.label_type_name, 
        cl.class_1 +''+ cl.class_2 as batt_type, hr.qty, sum(mps_qty) as plan_qty
        from mps_header hr
        inner join mps_details dt on hr.po_line_no = dt.po_line_no and hr.po_no = dt.po_no
        inner join item it on hr.item_no = it.item_no
        inner join label_type lt on it.label_type = lt.label_type_code
        inner join class cl on it.class_code = cl.class_code
        where dt.mps_date = getdate()
        group by hr.item_no,it.item,it.description, lt.label_type_name, cl.class_1, cl.class_2, work_order,hr.qty,cr_date";
$data7 = sqlsrv_query($connect, strtoupper($qry7));
//echo $qry7;

$objPHPExcel = new PHPExcel();
$objPHPExcel->createSheet();
/**/
while ($s < 8) {
    if ($Arr_sheet[$s] == "TOTAL ALL BATTERY"){

        $tot_1_TGL_1 = 0;   $tot_1_TGL_10 = 0;      $tot_1_TGL_19 = 0;      $tot_1_TGL_28 = 0;
        $tot_1_TGL_2 = 0;   $tot_1_TGL_11 = 0;      $tot_1_TGL_20 = 0;      $tot_1_TGL_29 = 0;
        $tot_1_TGL_3 = 0;   $tot_1_TGL_12 = 0;      $tot_1_TGL_21 = 0;      $tot_1_TGL_30 = 0;
        $tot_1_TGL_4 = 0;   $tot_1_TGL_13 = 0;      $tot_1_TGL_22 = 0;      $tot_1_TGL_31 = 0;
        $tot_1_TGL_5 = 0;   $tot_1_TGL_14 = 0;      $tot_1_TGL_23 = 0;      $prog_1_plan = 0;
        $tot_1_TGL_6 = 0;   $tot_1_TGL_15 = 0;      $tot_1_TGL_24 = 0;      $prog_1_output = 0;
        $tot_1_TGL_7 = 0;   $tot_1_TGL_16 = 0;      $tot_1_TGL_25 = 0;      $tot_1_total_p = 0;       $tot_1_total_o = 0;
        $tot_1_TGL_8 = 0;   $tot_1_TGL_17 = 0;      $tot_1_TGL_26 = 0;      $tot_1_progress_p = 0;    $tot_1_progress_o = 0;
        $tot_1_TGL_9 = 0;   $tot_1_TGL_18 = 0;      $tot_1_TGL_27 = 0;      

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'BATTERY')
                    ->setCellValue('B1', 'ORDER')
                    ->setCellValue('C1', 'TOTAL')
                    ->setCellValue('D1', 'PROGRESS')
                    ->setCellValue('E1', '1')
                    ->setCellValue('F1', '2')
                    ->setCellValue('G1', '3')
                    ->setCellValue('H1', '4')
                    ->setCellValue('I1', '5')
                    ->setCellValue('J1', '6')
                    ->setCellValue('K1', '7')
                    ->setCellValue('L1', '8')
                    ->setCellValue('M1', '9')
                    ->setCellValue('N1', '10')
                    ->setCellValue('O1', '11')
                    ->setCellValue('P1', '12')
                    ->setCellValue('Q1', '13')
                    ->setCellValue('R1', '14')
                    ->setCellValue('S1', '15')
                    ->setCellValue('T1', '16')
                    ->setCellValue('U1', '17')
                    ->setCellValue('V1', '18')
                    ->setCellValue('W1', '19')
                    ->setCellValue('X1', '20')
                    ->setCellValue('Y1', '21')
                    ->setCellValue('Z1', '22')
                    ->setCellValue('AA1', '23')
                    ->setCellValue('AB1', '24')
                    ->setCellValue('AC1', '25')
                    ->setCellValue('AD1', '26')
                    ->setCellValue('AE1', '27')
                    ->setCellValue('AF1', '28')
                    ->setCellValue('AG1', '29')
                    ->setCellValue('AH1', '30')
                    ->setCellValue('AI1', '31');

        $sheet = $objPHPExcel->getActiveSheet();
 
        $sheet->getStyle('A1:AI1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:AI1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:'.$arrKolom[$kemaren].'1')->applyFromArray(
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

        $sheet->getStyle($arrKolom[$now].'1')->applyFromArray(
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

        if($arrKolom[$lusa] != 'AI'){
            $sheet->getStyle($arrKolom[$lusa].'1:AI1')->applyFromArray(
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
            $sheet->getStyle('AI1')->applyFromArray(
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
        

        $no1=2;

        while ($row1=sqlsrv_fetch_object($data1)){

            $TGL_1 = $row1->TGL_1;   $TGL_10 = $row1->TGL_10;      $TGL_19 = $row1->TGL_19;      $TGL_28 = $row1->TGL_28;
            $TGL_2 = $row1->TGL_2;   $TGL_11 = $row1->TGL_11;      $TGL_20 = $row1->TGL_20;      $TGL_29 = $row1->TGL_29;
            $TGL_3 = $row1->TGL_3;   $TGL_12 = $row1->TGL_12;      $TGL_21 = $row1->TGL_21;      $TGL_30 = $row1->TGL_30;
            $TGL_4 = $row1->TGL_4;   $TGL_13 = $row1->TGL_13;      $TGL_22 = $row1->TGL_22;      $TGL_31 = $row1->TGL_31;
            $TGL_5 = $row1->TGL_5;   $TGL_14 = $row1->TGL_14;      $TGL_23 = $row1->TGL_23;
            $TGL_6 = $row1->TGL_6;   $TGL_15 = $row1->TGL_15;      $TGL_24 = $row1->TGL_24;
            $TGL_7 = $row1->TGL_7;   $TGL_16 = $row1->TGL_16;      $TGL_25 = $row1->TGL_25;
            $TGL_8 = $row1->TGL_8;   $TGL_17 = $row1->TGL_17;      $TGL_26 = $row1->TGL_26;
            $TGL_9 = $row1->TGL_9;   $TGL_18 = $row1->TGL_18;      $TGL_27 = $row1->TGL_27;

            if($kemaren==1){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1;
                }
            }elseif($kemaren == 2){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2;
                }
            }elseif($kemaren == 3){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3;
                }
            }elseif($kemaren == 4){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4;
                }
            }elseif($kemaren == 5){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5;
                }
            }elseif($kemaren == 6){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6;
                }
            }elseif($kemaren == 7){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7;
                }
            }elseif($kemaren == 8){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8;
                }
            }elseif($kemaren == 9){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9;
                }
            }elseif($kemaren == 10){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10;
                }
            }elseif($kemaren == 11){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11;
                }
            }elseif($kemaren == 12){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12;
                }
            }elseif($kemaren == 13){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13;
                }
            }elseif($kemaren == 14){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14;
                }
            }elseif($kemaren == 15){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15;
                }
            }elseif($kemaren == 16){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16;
                }
            }elseif($kemaren == 17){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17;
                }
            }elseif($kemaren == 18){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18;
                }
            }elseif($kemaren == 19){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19;
                }
            }elseif($kemaren == 20){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20;
                }
            }elseif($kemaren == 21){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21;
                }
            }elseif($kemaren == 22){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22;
                }
            }elseif($kemaren == 23){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23;
                }
            }elseif($kemaren == 24){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24;
                }
            }elseif($kemaren == 25){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25;
                }
            }elseif($kemaren == 26){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26;
                }
            }elseif($kemaren == 27){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27;
                }
            }elseif($kemaren == 28){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+$TGL_28;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+$TGL_28;
                }
            }elseif($kemaren == 29){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+$TGL_28+$TGL_29;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+$TGL_28+$TGL_29;
                }
            }elseif($kemaren == 30){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+$TGL_28+$TGL_29+$TGL_30;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+$TGL_28+$TGL_29+$TGL_30;
                }
            }elseif($kemaren == 31){
                if($row1->ORDERS == 'PLAN'){
                    $prog_1_plan = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+$TGL_28+$TGL_29+$TGL_30+$TGL_31;
                }elseif($row1->ORDERS == 'OUTPUT'){
                    $prog_1_output = $TGL_1+$TGL_2+$TGL_3+$TGL_4+$TGL_5+$TGL_6+$TGL_7+$TGL_8+$TGL_9+$TGL_10+$TGL_11+$TGL_12+$TGL_13+$TGL_14+$TGL_15+$TGL_16+$TGL_17+$TGL_18+$TGL_19+$TGL_20+$TGL_21+$TGL_22+$TGL_23+$TGL_24+$TGL_25+$TGL_26+$TGL_27+$TGL_28+$TGL_29+$TGL_30+$TGL_31;
                }
            }

            if($row1->ORDERS == 'PLAN'){
                $progress = $prog_1_plan;
                $tot_1_total_p = $row1->TOTAL;
                $tot_1_progress_p = $prog_1_plan;
                $tot_1_TGL_1 -= $TGL_1;   $tot_1_TGL_10 -= $TGL_10;      $tot_1_TGL_19 -= $TGL_19;      $tot_1_TGL_28 -= $TGL_28;
                $tot_1_TGL_2 -= $TGL_2;   $tot_1_TGL_11 -= $TGL_11;      $tot_1_TGL_20 -= $TGL_20;      $tot_1_TGL_29 -= $TGL_29;
                $tot_1_TGL_3 -= $TGL_3;   $tot_1_TGL_12 -= $TGL_12;      $tot_1_TGL_21 -= $TGL_21;      $tot_1_TGL_30 -= $TGL_30;
                $tot_1_TGL_4 -= $TGL_4;   $tot_1_TGL_13 -= $TGL_13;      $tot_1_TGL_22 -= $TGL_22;      $tot_1_TGL_31 -= $TGL_31;
                $tot_1_TGL_5 -= $TGL_5;   $tot_1_TGL_14 -= $TGL_14;      $tot_1_TGL_23 -= $TGL_23;      
                $tot_1_TGL_6 -= $TGL_6;   $tot_1_TGL_15 -= $TGL_15;      $tot_1_TGL_24 -= $TGL_24;      
                $tot_1_TGL_7 -= $TGL_7;   $tot_1_TGL_16 -= $TGL_16;      $tot_1_TGL_25 -= $TGL_25;
                $tot_1_TGL_8 -= $TGL_8;   $tot_1_TGL_17 -= $TGL_17;      $tot_1_TGL_26 -= $TGL_26;
                $tot_1_TGL_9 -= $TGL_9;   $tot_1_TGL_18 -= $TGL_18;      $tot_1_TGL_27 -= $TGL_27;

                $sheet = $objPHPExcel->getActiveSheet();
            }else{
                $progress = $prog_1_output;
                $tot_1_total_o = $row1->TOTAL;
                $tot_1_progress_o = $prog_1_output;
                $tot_1_TGL_1 += $TGL_1;   $tot_1_TGL_10 += $TGL_10;      $tot_1_TGL_19 += $TGL_19;      $tot_1_TGL_28 += $TGL_28;
                $tot_1_TGL_2 += $TGL_2;   $tot_1_TGL_11 += $TGL_11;      $tot_1_TGL_20 += $TGL_20;      $tot_1_TGL_29 += $TGL_29;
                $tot_1_TGL_3 += $TGL_3;   $tot_1_TGL_12 += $TGL_12;      $tot_1_TGL_21 += $TGL_21;      $tot_1_TGL_30 += $TGL_30;
                $tot_1_TGL_4 += $TGL_4;   $tot_1_TGL_13 += $TGL_13;      $tot_1_TGL_22 += $TGL_22;      $tot_1_TGL_31 += $TGL_31;
                $tot_1_TGL_5 += $TGL_5;   $tot_1_TGL_14 += $TGL_14;      $tot_1_TGL_23 += $TGL_23;      
                $tot_1_TGL_6 += $TGL_6;   $tot_1_TGL_15 += $TGL_15;      $tot_1_TGL_24 += $TGL_24;      
                $tot_1_TGL_7 += $TGL_7;   $tot_1_TGL_16 += $TGL_16;      $tot_1_TGL_25 += $TGL_25;
                $tot_1_TGL_8 += $TGL_8;   $tot_1_TGL_17 += $TGL_17;      $tot_1_TGL_26 += $TGL_26;
                $tot_1_TGL_9 += $TGL_9;   $tot_1_TGL_18 += $TGL_18;      $tot_1_TGL_27 += $TGL_27;
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no1, 'ALL BATTTRY')
                        ->setCellValue('B'.$no1, $row1->ORDERS)
                        ->setCellValue('C'.$no1, $row1->TOTAL)
                        ->setCellValue('D'.$no1, $progress)
                        ->setCellValue('E'.$no1, $row1->TGL_1)
                        ->setCellValue('F'.$no1, $row1->TGL_2)
                        ->setCellValue('G'.$no1, $row1->TGL_3)
                        ->setCellValue('H'.$no1, $row1->TGL_4)
                        ->setCellValue('I'.$no1, $row1->TGL_5)
                        ->setCellValue('J'.$no1, $row1->TGL_6)
                        ->setCellValue('K'.$no1, $row1->TGL_7)
                        ->setCellValue('L'.$no1, $row1->TGL_8)
                        ->setCellValue('M'.$no1, $row1->TGL_9)
                        ->setCellValue('N'.$no1, $row1->TGL_10)
                        ->setCellValue('O'.$no1, $row1->TGL_11)
                        ->setCellValue('P'.$no1, $row1->TGL_12)
                        ->setCellValue('Q'.$no1, $row1->TGL_13)
                        ->setCellValue('R'.$no1, $row1->TGL_14)
                        ->setCellValue('S'.$no1, $row1->TGL_15)
                        ->setCellValue('T'.$no1, $row1->TGL_16)
                        ->setCellValue('U'.$no1, $row1->TGL_17)
                        ->setCellValue('V'.$no1, $row1->TGL_18)
                        ->setCellValue('W'.$no1, $row1->TGL_19)
                        ->setCellValue('X'.$no1, $row1->TGL_20)
                        ->setCellValue('Y'.$no1, $row1->TGL_21)
                        ->setCellValue('Z'.$no1, $row1->TGL_22)
                        ->setCellValue('AA'.$no1, $row1->TGL_23)
                        ->setCellValue('AB'.$no1, $row1->TGL_24)
                        ->setCellValue('AC'.$no1, $row1->TGL_25)
                        ->setCellValue('AD'.$no1, $row1->TGL_26)
                        ->setCellValue('AE'.$no1, $row1->TGL_27)
                        ->setCellValue('AF'.$no1, $row1->TGL_28)
                        ->setCellValue('AG'.$no1, $row1->TGL_29)
                        ->setCellValue('AH'.$no1, $row1->TGL_30)
                        ->setCellValue('AI'.$no1, $row1->TGL_31);

            $objPHPExcel->getActiveSheet()->getStyle('C'.$no1.':AI'.$no1)->getNumberFormat()->setFormatCode('#,##0');
            $no1++;
        } 

        /*$tot_1_TGL_1 = $tot_1_TGL_1;
        $tot_1_TGL_2 = $tot_1_TGL_1+$tot_1_TGL_2;
        $tot_1_TGL_3 = $tot_1_TGL_2+$tot_1_TGL_3;
        $tot_1_TGL_4 = $tot_1_TGL_3+$tot_1_TGL_4;  
        $tot_1_TGL_5 = $tot_1_TGL_4+$tot_1_TGL_5;
        $tot_1_TGL_6 = $tot_1_TGL_5+$tot_1_TGL_6;
        $tot_1_TGL_7 = $tot_1_TGL_6+$tot_1_TGL_7;
        $tot_1_TGL_8 = $tot_1_TGL_7+$tot_1_TGL_8;
        $tot_1_TGL_9 = $tot_1_TGL_8+$tot_1_TGL_9;
        $tot_1_TGL_10 = $tot_1_TGL_9+$tot_1_TGL_10;
        $tot_1_TGL_11 = $tot_1_TGL_10+$tot_1_TGL_11;
        $tot_1_TGL_12 = $tot_1_TGL_11+$tot_1_TGL_12;
        $tot_1_TGL_13 = $tot_1_TGL_12+$tot_1_TGL_13;
        $tot_1_TGL_14 = $tot_1_TGL_13+$tot_1_TGL_14;
        $tot_1_TGL_15 = $tot_1_TGL_14+$tot_1_TGL_15;
        $tot_1_TGL_16 = $tot_1_TGL_15+$tot_1_TGL_16;
        $tot_1_TGL_17 = $tot_1_TGL_16+$tot_1_TGL_17;
        $tot_1_TGL_18 = $tot_1_TGL_17+$tot_1_TGL_18;
        $tot_1_TGL_19 = $tot_1_TGL_18+$tot_1_TGL_19;
        $tot_1_TGL_20 = $tot_1_TGL_19+$tot_1_TGL_20;
        $tot_1_TGL_21 = $tot_1_TGL_20+$tot_1_TGL_21;
        $tot_1_TGL_22 = $tot_1_TGL_21+$tot_1_TGL_22;
        $tot_1_TGL_23 = $tot_1_TGL_22+$tot_1_TGL_23;
        $tot_1_TGL_24 = $tot_1_TGL_23+$tot_1_TGL_24;
        $tot_1_TGL_25 = $tot_1_TGL_24+$tot_1_TGL_25;
        $tot_1_TGL_26 = $tot_1_TGL_25+$tot_1_TGL_26;
        $tot_1_TGL_27 = $tot_1_TGL_26+$tot_1_TGL_27;
        $tot_1_TGL_28 = $tot_1_TGL_27+$tot_1_TGL_28;
        $tot_1_TGL_29 = $tot_1_TGL_28+$tot_1_TGL_29;
        $tot_1_TGL_30 = $tot_1_TGL_29+$tot_1_TGL_30;
        $tot_1_TGL_31 = $tot_1_TGL_30+$tot_1_TGL_31;*/

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no1, 'ALL BATTERY')
                    ->setCellValue('B'.$no1, 'GAP')
                    ->setCellValue('C'.$no1, $tot_1_total_o - $tot_1_total_p)
                    ->setCellValue('D'.$no1, $tot_1_progress_o - $tot_1_progress_p)
                    ->setCellValue('E'.$no1, $tot_1_TGL_1)
                    ->setCellValue('F'.$no1, $tot_1_TGL_2)
                    ->setCellValue('G'.$no1, $tot_1_TGL_3)
                    ->setCellValue('H'.$no1, $tot_1_TGL_4)
                    ->setCellValue('I'.$no1, $tot_1_TGL_5)
                    ->setCellValue('J'.$no1, $tot_1_TGL_6)
                    ->setCellValue('K'.$no1, $tot_1_TGL_7)
                    ->setCellValue('L'.$no1, $tot_1_TGL_8)
                    ->setCellValue('M'.$no1, $tot_1_TGL_9)
                    ->setCellValue('N'.$no1, $tot_1_TGL_10)
                    ->setCellValue('O'.$no1, $tot_1_TGL_11)
                    ->setCellValue('P'.$no1, $tot_1_TGL_12)
                    ->setCellValue('Q'.$no1, $tot_1_TGL_13)
                    ->setCellValue('R'.$no1, $tot_1_TGL_14)
                    ->setCellValue('S'.$no1, $tot_1_TGL_15)
                    ->setCellValue('T'.$no1, $tot_1_TGL_16)
                    ->setCellValue('U'.$no1, $tot_1_TGL_17)
                    ->setCellValue('V'.$no1, $tot_1_TGL_18)
                    ->setCellValue('W'.$no1, $tot_1_TGL_19)
                    ->setCellValue('X'.$no1, $tot_1_TGL_20)
                    ->setCellValue('Y'.$no1, $tot_1_TGL_21)
                    ->setCellValue('Z'.$no1, $tot_1_TGL_22)
                    ->setCellValue('AA'.$no1, $tot_1_TGL_23)
                    ->setCellValue('AB'.$no1, $tot_1_TGL_24)
                    ->setCellValue('AC'.$no1, $tot_1_TGL_25)
                    ->setCellValue('AD'.$no1, $tot_1_TGL_26)
                    ->setCellValue('AE'.$no1, $tot_1_TGL_27)
                    ->setCellValue('AF'.$no1, $tot_1_TGL_28)
                    ->setCellValue('AG'.$no1, $tot_1_TGL_29)
                    ->setCellValue('AH'.$no1, $tot_1_TGL_30)
                    ->setCellValue('AI'.$no1, $tot_1_TGL_31);

        $sheet->getStyle('A2')->applyFromArray(
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
        
        $sheet->getStyle('B2:'.$arrKolom[$kemaren].'2')->applyFromArray(
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

        $sheet->getStyle($arrKolom[$now].'2')->applyFromArray(
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

        
        if($arrKolom[$lusa] != 'AI'){
            $sheet->getStyle($arrKolom[$lusa].'2:AI2')->applyFromArray(
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
            $sheet->getStyle('AI2')->applyFromArray(
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
        

        $sheet->getStyle('A3')->applyFromArray(
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

        $sheet->getStyle('B3:'.$arrKolom[$kemaren].'3')->applyFromArray(
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

        if($arrKolom[$lusa] != 'AI'){
            $sheet->getStyle($arrKolom[$lusa].'3:AI3')->applyFromArray(
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
            $sheet->getStyle('AI3')->applyFromArray(
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
        
        $sheet->getStyle('A4')->applyFromArray(
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

        $sheet->getStyle('B4:'.$arrKolom[$kemaren].'4')->applyFromArray(
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

        $sheet->getStyle($arrKolom[$now].'4')->applyFromArray(
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

        if($arrKolom[$lusa] != 'AI'){
            $sheet->getStyle($arrKolom[$lusa].'4:AI4')->applyFromArray(
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
            $sheet->getStyle('AI4')->applyFromArray(
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

        $sheet->getStyle('C2:AI4')->getNumberFormat();

        $objPHPExcel->getActiveSheet()->getStyle('C'.$no1.':AI'.$no1)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->setTitle('TOTAL ALL BATTERY');
    }elseif ($Arr_sheet[$s] == "BATTERY TYPE"){
        
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'BATTERY')
                    ->setCellValue('B1', 'ORDER')
                    ->setCellValue('C1', 'TOTAL')
                    ->setCellValue('D1', 'PROGRESS')
                    ->setCellValue('E1', '1')
                    ->setCellValue('F1', '2')
                    ->setCellValue('G1', '3')
                    ->setCellValue('H1', '4')
                    ->setCellValue('I1', '5')
                    ->setCellValue('J1', '6')
                    ->setCellValue('K1', '7')
                    ->setCellValue('L1', '8')
                    ->setCellValue('M1', '9')
                    ->setCellValue('N1', '10')
                    ->setCellValue('O1', '11')
                    ->setCellValue('P1', '12')
                    ->setCellValue('Q1', '13')
                    ->setCellValue('R1', '14')
                    ->setCellValue('S1', '15')
                    ->setCellValue('T1', '16')
                    ->setCellValue('U1', '17')
                    ->setCellValue('V1', '18')
                    ->setCellValue('W1', '19')
                    ->setCellValue('X1', '20')
                    ->setCellValue('Y1', '21')
                    ->setCellValue('Z1', '22')
                    ->setCellValue('AA1', '23')
                    ->setCellValue('AB1', '24')
                    ->setCellValue('AC1', '25')
                    ->setCellValue('AD1', '26')
                    ->setCellValue('AE1', '27')
                    ->setCellValue('AF1', '28')
                    ->setCellValue('AG1', '29')
                    ->setCellValue('AH1', '30')
                    ->setCellValue('AI1', '31');

        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet->getStyle('A1:AI1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:AI1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:'.$arrKolom[$kemaren].'1')->applyFromArray(
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

        $sheet->getStyle($arrKolom[$now].'1')->applyFromArray(
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

        if($arrKolom[$lusa] != 'AI'){
            $sheet->getStyle($arrKolom[$lusa].'1:AI1')->applyFromArray(
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
            $sheet->getStyle('AI1')->applyFromArray(
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

        $no2=2;         $battery='';
        $tot_2_TGL_1 = 0;   $tot_2_TGL_10 = 0;      $tot_2_TGL_19 = 0;      $tot_2_TGL_28 = 0;
        $tot_2_TGL_2 = 0;   $tot_2_TGL_11 = 0;      $tot_2_TGL_20 = 0;      $tot_2_TGL_29 = 0;
        $tot_2_TGL_3 = 0;   $tot_2_TGL_12 = 0;      $tot_2_TGL_21 = 0;      $tot_2_TGL_30 = 0;
        $tot_2_TGL_4 = 0;   $tot_2_TGL_13 = 0;      $tot_2_TGL_22 = 0;      $tot_2_TGL_31 = 0;
        $tot_2_TGL_5 = 0;   $tot_2_TGL_14 = 0;      $tot_2_TGL_23 = 0;      $prog_2_plan = 0;
        $tot_2_TGL_6 = 0;   $tot_2_TGL_15 = 0;      $tot_2_TGL_24 = 0;      $prog_2_output = 0;
        $tot_2_TGL_7 = 0;   $tot_2_TGL_16 = 0;      $tot_2_TGL_25 = 0;      $tot_2_total_p = 0;       $tot_2_total_o = 0;
        $tot_2_TGL_8 = 0;   $tot_2_TGL_17 = 0;      $tot_2_TGL_26 = 0;      $tot_2_progress_p = 0;    $tot_2_progress_o = 0;
        $tot_2_TGL_9 = 0;   $tot_2_TGL_18 = 0;      $tot_2_TGL_27 = 0;

        while ($row2=sqlsrv_fetch_object($data2)){
            $TGL_2_1 = $row2->TGL_1;   $TGL_2_10 = $row2->TGL_10;      $TGL_2_19 = $row2->TGL_19;      $TGL_2_28 = $row2->TGL_28;
            $TGL_2_2 = $row2->TGL_2;   $TGL_2_11 = $row2->TGL_11;      $TGL_2_20 = $row2->TGL_20;      $TGL_2_29 = $row2->TGL_29;
            $TGL_2_3 = $row2->TGL_3;   $TGL_2_12 = $row2->TGL_12;      $TGL_2_21 = $row2->TGL_21;      $TGL_2_30 = $row2->TGL_30;
            $TGL_2_4 = $row2->TGL_4;   $TGL_2_13 = $row2->TGL_13;      $TGL_2_22 = $row2->TGL_22;      $TGL_2_31 = $row2->TGL_31;
            $TGL_2_5 = $row2->TGL_5;   $TGL_2_14 = $row2->TGL_14;      $TGL_2_23 = $row2->TGL_23;
            $TGL_2_6 = $row2->TGL_6;   $TGL_2_15 = $row2->TGL_15;      $TGL_2_24 = $row2->TGL_24;
            $TGL_2_7 = $row2->TGL_7;   $TGL_2_16 = $row2->TGL_16;      $TGL_2_25 = $row2->TGL_25;
            $TGL_2_8 = $row2->TGL_8;   $TGL_2_17 = $row2->TGL_17;      $TGL_2_26 = $row2->TGL_26;
            $TGL_2_9 = $row2->TGL_9;   $TGL_2_18 = $row2->TGL_18;      $TGL_2_27 = $row2->TGL_27;

            if($kemaren==1){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1;
                }
            }elseif($kemaren == 2){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2;
                }
            }elseif($kemaren == 3){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3;
                }
            }elseif($kemaren == 4){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4;
                }
            }elseif($kemaren == 5){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5;
                }
            }elseif($kemaren == 6){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL__25+$TGL_2_6;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL__25+$TGL_2_6;
                }
            }elseif($kemaren == 7){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL__25+$TGL_2_6+$TGL_2_7;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7;
                }
            }elseif($kemaren == 8){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8;
                }
            }elseif($kemaren == 9){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9;
                }
            }elseif($kemaren == 10){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10;
                }
            }elseif($kemaren == 11){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11;
                }
            }elseif($kemaren == 12){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12;
                }
            }elseif($kemaren == 13){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13;
                }
            }elseif($kemaren == 14){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14;
                }
            }elseif($kemaren == 15){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15;
                }
            }elseif($kemaren == 16){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16;
                }
            }elseif($kemaren == 17){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17;
                }
            }elseif($kemaren == 18){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18;
                }
            }elseif($kemaren == 19){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19;
                }
            }elseif($kemaren == 20){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20;
                }
            }elseif($kemaren == 21){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21;
                }
            }elseif($kemaren == 22){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22;
                }
            }elseif($kemaren == 23){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23;
                }
            }elseif($kemaren == 24){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24;
                }
            }elseif($kemaren == 25){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25;
                }
            }elseif($kemaren == 26){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26;
                }
            }elseif($kemaren == 27){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27;
                }
            }elseif($kemaren == 28){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27+$TGL_2_28;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27+$TGL_2_28;
                }
            }elseif($kemaren == 29){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27+$TGL_2_28+$TGL_2_29;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27+$TGL_2_28+$TGL_2_29;
                }
            }elseif($kemaren == 30){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27+$TGL_2_28+$TGL_2_29+$TGL_2_30;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27+$TGL_2_28+$TGL_2_29+$TGL_2_30;
                }
            }elseif($kemaren == 31){
                if($row2->ORDERS == 'PLAN'){
                    $prog_2_plan = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27+$TGL_2_28+$TGL_2_29+$TGL_2_30+$TGL_2_31;
                }elseif($row2->ORDERS == 'OUTPUT'){
                    $prog_2_output = $TGL_2_1+$TGL_2_2+$TGL_2_3+$TGL_2_4+$TGL_2_5+$TGL_2_6+$TGL_2_7+$TGL_2_8+$TGL_2_9+$TGL_2_10+$TGL_2_11+$TGL_2_12+$TGL_2_13+$TGL_2_14+$TGL_2_15+$TGL_2_16+$TGL_2_17+$TGL_2_18+$TGL_2_19+$TGL_2_20+$TGL_2_21+$TGL_2_22+$TGL_2_23+$TGL_2_24+$TGL_2_25+$TGL_2_26+$TGL_2_27+$TGL_2_28+$TGL_2_29+$TGL_2_30+$TGL_2_31;
                }
            }

            if ($no2==2){
                $LR = $row2->GROUPINGLABEL;
                /*PLAN YG KE-1*/
                $progress2 = $prog_2_plan;
                $tot_2_total_p = $row2->TOTAL;
                $tot_2_progress_p = $prog_2_plan;
                $tot_2_TGL_1 -= $TGL_2_1;   $tot_2_TGL_10 -= $TGL_2_10;      $tot_2_TGL_19 -= $TGL_2_19;      $tot_2_TGL_28 -= $TGL_2_28;
                $tot_2_TGL_2 -= $TGL_2_2;   $tot_2_TGL_11 -= $TGL_2_11;      $tot_2_TGL_20 -= $TGL_2_20;      $tot_2_TGL_29 -= $TGL_2_29;
                $tot_2_TGL_3 -= $TGL_2_3;   $tot_2_TGL_12 -= $TGL_2_12;      $tot_2_TGL_21 -= $TGL_2_21;      $tot_2_TGL_30 -= $TGL_2_30;
                $tot_2_TGL_4 -= $TGL_2_4;   $tot_2_TGL_13 -= $TGL_2_13;      $tot_2_TGL_22 -= $TGL_2_22;      $tot_2_TGL_31 -= $TGL_2_31;
                $tot_2_TGL_5 -= $TGL_2_5;   $tot_2_TGL_14 -= $TGL_2_14;      $tot_2_TGL_23 -= $TGL_2_23;      
                $tot_2_TGL_6 -= $TGL_2_6;   $tot_2_TGL_15 -= $TGL_2_15;      $tot_2_TGL_24 -= $TGL_2_24;      
                $tot_2_TGL_7 -= $TGL_2_7;   $tot_2_TGL_16 -= $TGL_2_16;      $tot_2_TGL_25 -= $TGL_2_25;
                $tot_2_TGL_8 -= $TGL_2_8;   $tot_2_TGL_17 -= $TGL_2_17;      $tot_2_TGL_26 -= $TGL_2_26;
                $tot_2_TGL_9 -= $TGL_2_9;   $tot_2_TGL_18 -= $TGL_2_18;      $tot_2_TGL_27 -= $TGL_2_27;
            }else{
                if($row2->GROUPINGLABEL == $battery){
                    $LR = "";
                    /*INI BUAT OUTPUT*/
                    $progress2 = $prog_2_output;
                    $tot_2_total_o = $row2->TOTAL;
                    $tot_2_progress_o = $prog_2_output;
                    $tot_2_TGL_1 += $TGL_2_1;     $tot_2_TGL_10 += $TGL_2_10;      $tot_2_TGL_19 += $TGL_2_19;      $tot_2_TGL_28 += $TGL_2_28;
                    $tot_2_TGL_2 += $TGL_2_2;     $tot_2_TGL_11 += $TGL_2_11;      $tot_2_TGL_20 += $TGL_2_20;      $tot_2_TGL_29 += $TGL_2_29;
                    $tot_2_TGL_3 += $TGL_2_3;     $tot_2_TGL_12 += $TGL_2_12;      $tot_2_TGL_21 += $TGL_2_21;      $tot_2_TGL_30 += $TGL_2_30;
                    $tot_2_TGL_4 += $TGL_2_4;     $tot_2_TGL_13 += $TGL_2_13;      $tot_2_TGL_22 += $TGL_2_22;      $tot_2_TGL_31 += $TGL_2_31;
                    $tot_2_TGL_5 += $TGL_2_5;     $tot_2_TGL_14 += $TGL_2_14;      $tot_2_TGL_23 += $TGL_2_23;      
                    $tot_2_TGL_6 += $TGL_2_6;     $tot_2_TGL_15 += $TGL_2_15;      $tot_2_TGL_24 += $TGL_2_24;      
                    $tot_2_TGL_7 += $TGL_2_7;     $tot_2_TGL_16 += $TGL_2_16;      $tot_2_TGL_25 += $TGL_2_25;
                    $tot_2_TGL_8 += $TGL_2_8;     $tot_2_TGL_17 += $TGL_2_17;      $tot_2_TGL_26 += $TGL_2_26;
                    $tot_2_TGL_9 += $TGL_2_9;     $tot_2_TGL_18 += $TGL_2_18;      $tot_2_TGL_27 += $TGL_2_27;
                }else{
                    $LR = $row2->GROUPINGLABEL;

                    /*$tot_2_TGL_1 = $tot_2_TGL_1;
                    $tot_2_TGL_2 = $tot_2_TGL_1+$tot_2_TGL_2;
                    $tot_2_TGL_3 = $tot_2_TGL_2+$tot_2_TGL_3;
                    $tot_2_TGL_4 = $tot_2_TGL_3+$tot_2_TGL_4;  
                    $tot_2_TGL_5 = $tot_2_TGL_4+$tot_2_TGL_5;
                    $tot_2_TGL_6 = $tot_2_TGL_5+$tot_2_TGL_6;
                    $tot_2_TGL_7 = $tot_2_TGL_6+$tot_2_TGL_7;
                    $tot_2_TGL_8 = $tot_2_TGL_7+$tot_2_TGL_8;
                    $tot_2_TGL_9 = $tot_2_TGL_8+$tot_2_TGL_9;
                    $tot_2_TGL_10 = $tot_2_TGL_9+$tot_2_TGL_10;
                    $tot_2_TGL_11 = $tot_2_TGL_10+$tot_2_TGL_11;
                    $tot_2_TGL_12 = $tot_2_TGL_11+$tot_2_TGL_12;
                    $tot_2_TGL_13 = $tot_2_TGL_12+$tot_2_TGL_13;
                    $tot_2_TGL_14 = $tot_2_TGL_13+$tot_2_TGL_14;
                    $tot_2_TGL_15 = $tot_2_TGL_14+$tot_2_TGL_15;
                    $tot_2_TGL_16 = $tot_2_TGL_15+$tot_2_TGL_16;
                    $tot_2_TGL_17 = $tot_2_TGL_16+$tot_2_TGL_17;
                    $tot_2_TGL_18 = $tot_2_TGL_17+$tot_2_TGL_18;
                    $tot_2_TGL_19 = $tot_2_TGL_18+$tot_2_TGL_19;
                    $tot_2_TGL_20 = $tot_2_TGL_19+$tot_2_TGL_20;
                    $tot_2_TGL_21 = $tot_2_TGL_20+$tot_2_TGL_21;
                    $tot_2_TGL_22 = $tot_2_TGL_21+$tot_2_TGL_22;
                    $tot_2_TGL_23 = $tot_2_TGL_22+$tot_2_TGL_23;
                    $tot_2_TGL_24 = $tot_2_TGL_23+$tot_2_TGL_24;
                    $tot_2_TGL_25 = $tot_2_TGL_24+$tot_2_TGL_25;
                    $tot_2_TGL_26 = $tot_2_TGL_25+$tot_2_TGL_26;
                    $tot_2_TGL_27 = $tot_2_TGL_26+$tot_2_TGL_27;
                    $tot_2_TGL_28 = $tot_2_TGL_27+$tot_2_TGL_28;
                    $tot_2_TGL_29 = $tot_2_TGL_28+$tot_2_TGL_29;
                    $tot_2_TGL_30 = $tot_2_TGL_29+$tot_2_TGL_30;
                    $tot_2_TGL_31 = $tot_2_TGL_30+$tot_2_TGL_31;*/

                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no2, '')
                                ->setCellValue('B'.$no2, 'GAP')
                                ->setCellValue('C'.$no2, $tot_2_total_o - $tot_2_total_p)
                                ->setCellValue('D'.$no2, $tot_2_progress_o - $tot_2_progress_p)
                                ->setCellValue('E'.$no2, $tot_2_TGL_1)
                                ->setCellValue('F'.$no2, $tot_2_TGL_2)
                                ->setCellValue('G'.$no2, $tot_2_TGL_3)
                                ->setCellValue('H'.$no2, $tot_2_TGL_4)
                                ->setCellValue('I'.$no2, $tot_2_TGL_5)
                                ->setCellValue('J'.$no2, $tot_2_TGL_6)
                                ->setCellValue('K'.$no2, $tot_2_TGL_7)
                                ->setCellValue('L'.$no2, $tot_2_TGL_8)
                                ->setCellValue('M'.$no2, $tot_2_TGL_9)
                                ->setCellValue('N'.$no2, $tot_2_TGL_10)
                                ->setCellValue('O'.$no2, $tot_2_TGL_11)
                                ->setCellValue('P'.$no2, $tot_2_TGL_12)
                                ->setCellValue('Q'.$no2, $tot_2_TGL_13)
                                ->setCellValue('R'.$no2, $tot_2_TGL_14)
                                ->setCellValue('S'.$no2, $tot_2_TGL_15)
                                ->setCellValue('T'.$no2, $tot_2_TGL_16)
                                ->setCellValue('U'.$no2, $tot_2_TGL_17)
                                ->setCellValue('V'.$no2, $tot_2_TGL_18)
                                ->setCellValue('W'.$no2, $tot_2_TGL_19)
                                ->setCellValue('X'.$no2, $tot_2_TGL_20)
                                ->setCellValue('Y'.$no2, $tot_2_TGL_21)
                                ->setCellValue('Z'.$no2, $tot_2_TGL_22)
                                ->setCellValue('AA'.$no2, $tot_2_TGL_23)
                                ->setCellValue('AB'.$no2, $tot_2_TGL_24)
                                ->setCellValue('AC'.$no2, $tot_2_TGL_25)
                                ->setCellValue('AD'.$no2, $tot_2_TGL_26)
                                ->setCellValue('AE'.$no2, $tot_2_TGL_27)
                                ->setCellValue('AF'.$no2, $tot_2_TGL_28)
                                ->setCellValue('AG'.$no2, $tot_2_TGL_29)
                                ->setCellValue('AH'.$no2, $tot_2_TGL_30)
                                ->setCellValue('AI'.$no2, $tot_2_TGL_31);
                    
                    $sheet->getStyle('A'.$no2)->applyFromArray(
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

                    if($arrKolom[$lusa] != 'AI'){
                        $sheet->getStyle($arrKolom[$lusa].$no2.':AI'.$no2)->applyFromArray(
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
                        $sheet->getStyle('AI'.$no2)->applyFromArray(
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

                    $objPHPExcel->getActiveSheet()->getStyle('C'.$no2.':AI'.$no2)->getNumberFormat()->setFormatCode('#,##0');

                    $no2++;

                    $tot_2_TGL_1 = 0;   $tot_2_TGL_10 = 0;      $tot_2_TGL_19 = 0;      $tot_2_TGL_28 = 0;
                    $tot_2_TGL_2 = 0;   $tot_2_TGL_11 = 0;      $tot_2_TGL_20 = 0;      $tot_2_TGL_29 = 0;
                    $tot_2_TGL_3 = 0;   $tot_2_TGL_12 = 0;      $tot_2_TGL_21 = 0;      $tot_2_TGL_30 = 0;
                    $tot_2_TGL_4 = 0;   $tot_2_TGL_13 = 0;      $tot_2_TGL_22 = 0;      $tot_2_TGL_31 = 0;
                    $tot_2_TGL_5 = 0;   $tot_2_TGL_14 = 0;      $tot_2_TGL_23 = 0;      $tot_2_total_p = 0;       $tot_2_total_o = 0;
                    $tot_2_TGL_6 = 0;   $tot_2_TGL_15 = 0;      $tot_2_TGL_24 = 0;      $tot_2_progress_p = 0;    $tot_2_progress_o = 0;
                    $tot_2_TGL_7 = 0;   $tot_2_TGL_16 = 0;      $tot_2_TGL_25 = 0;
                    $tot_2_TGL_8 = 0;   $tot_2_TGL_17 = 0;      $tot_2_TGL_26 = 0;
                    $tot_2_TGL_9 = 0;   $tot_2_TGL_18 = 0;      $tot_2_TGL_27 = 0;


                    $progress2 = $prog_2_plan;
                    $tot_2_total_p = $row2->TOTAL;
                    $tot_2_progress_p = $prog_2_plan;
                    $tot_2_TGL_1 -= $TGL_2_1;   $tot_2_TGL_10 -= $TGL_2_10;      $tot_2_TGL_19 -= $TGL_2_19;      $tot_2_TGL_28 -= $TGL_2_28;
                    $tot_2_TGL_2 -= $TGL_2_2;   $tot_2_TGL_11 -= $TGL_2_11;      $tot_2_TGL_20 -= $TGL_2_20;      $tot_2_TGL_29 -= $TGL_2_29;
                    $tot_2_TGL_3 -= $TGL_2_3;   $tot_2_TGL_12 -= $TGL_2_12;      $tot_2_TGL_21 -= $TGL_2_21;      $tot_2_TGL_30 -= $TGL_2_30;
                    $tot_2_TGL_4 -= $TGL_2_4;   $tot_2_TGL_13 -= $TGL_2_13;      $tot_2_TGL_22 -= $TGL_2_22;      $tot_2_TGL_31 -= $TGL_2_31;
                    $tot_2_TGL_5 -= $TGL_2_5;   $tot_2_TGL_14 -= $TGL_2_14;      $tot_2_TGL_23 -= $TGL_2_23;      
                    $tot_2_TGL_6 -= $TGL_2_6;   $tot_2_TGL_15 -= $TGL_2_15;      $tot_2_TGL_24 -= $TGL_2_24;      
                    $tot_2_TGL_7 -= $TGL_2_7;   $tot_2_TGL_16 -= $TGL_2_16;      $tot_2_TGL_25 -= $TGL_2_25;
                    $tot_2_TGL_8 -= $TGL_2_8;   $tot_2_TGL_17 -= $TGL_2_17;      $tot_2_TGL_26 -= $TGL_2_26;
                    $tot_2_TGL_9 -= $TGL_2_9;   $tot_2_TGL_18 -= $TGL_2_18;      $tot_2_TGL_27 -= $TGL_2_27;

                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no2, $LR)
                        ->setCellValue('B'.$no2, $row2->ORDERS)
                        ->setCellValue('C'.$no2, $row2->TOTAL)
                        ->setCellValue('D'.$no2, $progress2)
                        ->setCellValue('E'.$no2, $row2->TGL_1)
                        ->setCellValue('F'.$no2, $row2->TGL_2)
                        ->setCellValue('G'.$no2, $row2->TGL_3)
                        ->setCellValue('H'.$no2, $row2->TGL_4)
                        ->setCellValue('I'.$no2, $row2->TGL_5)
                        ->setCellValue('J'.$no2, $row2->TGL_6)
                        ->setCellValue('K'.$no2, $row2->TGL_7)
                        ->setCellValue('L'.$no2, $row2->TGL_8)
                        ->setCellValue('M'.$no2, $row2->TGL_9)
                        ->setCellValue('N'.$no2, $row2->TGL_10)
                        ->setCellValue('O'.$no2, $row2->TGL_11)
                        ->setCellValue('P'.$no2, $row2->TGL_12)
                        ->setCellValue('Q'.$no2, $row2->TGL_13)
                        ->setCellValue('R'.$no2, $row2->TGL_14)
                        ->setCellValue('S'.$no2, $row2->TGL_15)
                        ->setCellValue('T'.$no2, $row2->TGL_16)
                        ->setCellValue('U'.$no2, $row2->TGL_17)
                        ->setCellValue('V'.$no2, $row2->TGL_18)
                        ->setCellValue('W'.$no2, $row2->TGL_19)
                        ->setCellValue('X'.$no2, $row2->TGL_20)
                        ->setCellValue('Y'.$no2, $row2->TGL_21)
                        ->setCellValue('Z'.$no2, $row2->TGL_22)
                        ->setCellValue('AA'.$no2, $row2->TGL_23)
                        ->setCellValue('AB'.$no2, $row2->TGL_24)
                        ->setCellValue('AC'.$no2, $row2->TGL_25)
                        ->setCellValue('AD'.$no2, $row2->TGL_26)
                        ->setCellValue('AE'.$no2, $row2->TGL_27)
                        ->setCellValue('AF'.$no2, $row2->TGL_28)
                        ->setCellValue('AG'.$no2, $row2->TGL_29)
                        ->setCellValue('AH'.$no2, $row2->TGL_30)
                        ->setCellValue('AI'.$no2, $row2->TGL_31);

            if($row2->ORDERS == 'PLAN'){

                $sheet->getStyle('A'.$no2)->applyFromArray(
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

                if($arrKolom[$lusa] != 'AI'){
                    $sheet->getStyle($arrKolom[$lusa].$no2.':AI'.$no2)->applyFromArray(
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
                    $sheet->getStyle('AI'.$no2)->applyFromArray(
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
            }else{
                $sheet->getStyle('A'.$no2)->applyFromArray(
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

                if($arrKolom[$lusa] != 'AI'){
                    $sheet->getStyle($arrKolom[$lusa].$no2.':AI'.$no2)->applyFromArray(
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
                    $sheet->getStyle('AI'.$no2)->applyFromArray(
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
            }

            $objPHPExcel->getActiveSheet()->getStyle('C'.$no2.':AI'.$no2)->getNumberFormat()->setFormatCode('#,##0');

            $battery = $row2->GROUPINGLABEL;
            $no2++;
        }

        /*$tot_2_TGL_1 = $tot_2_TGL_1;
        $tot_2_TGL_2 = $tot_2_TGL_1+$tot_2_TGL_2;
        $tot_2_TGL_3 = $tot_2_TGL_2+$tot_2_TGL_3;
        $tot_2_TGL_4 = $tot_2_TGL_3+$tot_2_TGL_4;  
        $tot_2_TGL_5 = $tot_2_TGL_4+$tot_2_TGL_5;
        $tot_2_TGL_6 = $tot_2_TGL_5+$tot_2_TGL_6;
        $tot_2_TGL_7 = $tot_2_TGL_6+$tot_2_TGL_7;
        $tot_2_TGL_8 = $tot_2_TGL_7+$tot_2_TGL_8;
        $tot_2_TGL_9 = $tot_2_TGL_8+$tot_2_TGL_9;
        $tot_2_TGL_10 = $tot_2_TGL_9+$tot_2_TGL_10;
        $tot_2_TGL_11 = $tot_2_TGL_10+$tot_2_TGL_11;
        $tot_2_TGL_12 = $tot_2_TGL_11+$tot_2_TGL_12;
        $tot_2_TGL_13 = $tot_2_TGL_12+$tot_2_TGL_13;
        $tot_2_TGL_14 = $tot_2_TGL_13+$tot_2_TGL_14;
        $tot_2_TGL_15 = $tot_2_TGL_14+$tot_2_TGL_15;
        $tot_2_TGL_16 = $tot_2_TGL_15+$tot_2_TGL_16;
        $tot_2_TGL_17 = $tot_2_TGL_16+$tot_2_TGL_17;
        $tot_2_TGL_18 = $tot_2_TGL_17+$tot_2_TGL_18;
        $tot_2_TGL_19 = $tot_2_TGL_18+$tot_2_TGL_19;
        $tot_2_TGL_20 = $tot_2_TGL_19+$tot_2_TGL_20;
        $tot_2_TGL_21 = $tot_2_TGL_20+$tot_2_TGL_21;
        $tot_2_TGL_22 = $tot_2_TGL_21+$tot_2_TGL_22;
        $tot_2_TGL_23 = $tot_2_TGL_22+$tot_2_TGL_23;
        $tot_2_TGL_24 = $tot_2_TGL_23+$tot_2_TGL_24;
        $tot_2_TGL_25 = $tot_2_TGL_24+$tot_2_TGL_25;
        $tot_2_TGL_26 = $tot_2_TGL_25+$tot_2_TGL_26;
        $tot_2_TGL_27 = $tot_2_TGL_26+$tot_2_TGL_27;
        $tot_2_TGL_28 = $tot_2_TGL_27+$tot_2_TGL_28;
        $tot_2_TGL_29 = $tot_2_TGL_28+$tot_2_TGL_29;
        $tot_2_TGL_30 = $tot_2_TGL_29+$tot_2_TGL_30;
        $tot_2_TGL_31 = $tot_2_TGL_30+$tot_2_TGL_31;*/

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no2, '')
                    ->setCellValue('B'.$no2, 'GAP')
                    ->setCellValue('C'.$no2, $tot_2_total_o - $tot_2_total_p)
                    ->setCellValue('D'.$no2, $tot_2_progress_o - $tot_2_progress_p)
                    ->setCellValue('E'.$no2, $tot_2_TGL_1)
                    ->setCellValue('F'.$no2, $tot_2_TGL_2)
                    ->setCellValue('G'.$no2, $tot_2_TGL_3)
                    ->setCellValue('H'.$no2, $tot_2_TGL_4)
                    ->setCellValue('I'.$no2, $tot_2_TGL_5)
                    ->setCellValue('J'.$no2, $tot_2_TGL_6)
                    ->setCellValue('K'.$no2, $tot_2_TGL_7)
                    ->setCellValue('L'.$no2, $tot_2_TGL_8)
                    ->setCellValue('M'.$no2, $tot_2_TGL_9)
                    ->setCellValue('N'.$no2, $tot_2_TGL_10)
                    ->setCellValue('O'.$no2, $tot_2_TGL_11)
                    ->setCellValue('P'.$no2, $tot_2_TGL_12)
                    ->setCellValue('Q'.$no2, $tot_2_TGL_13)
                    ->setCellValue('R'.$no2, $tot_2_TGL_14)
                    ->setCellValue('S'.$no2, $tot_2_TGL_15)
                    ->setCellValue('T'.$no2, $tot_2_TGL_16)
                    ->setCellValue('U'.$no2, $tot_2_TGL_17)
                    ->setCellValue('V'.$no2, $tot_2_TGL_18)
                    ->setCellValue('W'.$no2, $tot_2_TGL_19)
                    ->setCellValue('X'.$no2, $tot_2_TGL_20)
                    ->setCellValue('Y'.$no2, $tot_2_TGL_21)
                    ->setCellValue('Z'.$no2, $tot_2_TGL_22)
                    ->setCellValue('AA'.$no2, $tot_2_TGL_23)
                    ->setCellValue('AB'.$no2, $tot_2_TGL_24)
                    ->setCellValue('AC'.$no2, $tot_2_TGL_25)
                    ->setCellValue('AD'.$no2, $tot_2_TGL_26)
                    ->setCellValue('AE'.$no2, $tot_2_TGL_27)
                    ->setCellValue('AF'.$no2, $tot_2_TGL_28)
                    ->setCellValue('AG'.$no2, $tot_2_TGL_29)
                    ->setCellValue('AH'.$no2, $tot_2_TGL_30)
                    ->setCellValue('AI'.$no2, $tot_2_TGL_31);

        $sheet->getStyle('A'.$no2)->applyFromArray(
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

        if($arrKolom[$lusa] != 'AI'){
            $sheet->getStyle($arrKolom[$lusa].$no2.':AI'.$no2)->applyFromArray(
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
            $sheet->getStyle('AI'.$no2)->applyFromArray(
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

        //$objPHPExcel->createSheet();
        //$objPHPExcel->setActiveSheetIndex($s);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$no2.':AI'.$no2)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->setTitle('BATTERY TYPE');
    }elseif ($Arr_sheet[$s] == "PACKAGING GROUP"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'PACKAGING')
                    ->setCellValue('B1', 'ORDER')
                    ->setCellValue('C1', 'TOTAL')
                    ->setCellValue('D1', 'PROGRESS')
                    ->setCellValue('E1', '1')
                    ->setCellValue('F1', '2')
                    ->setCellValue('G1', '3')
                    ->setCellValue('H1', '4')
                    ->setCellValue('I1', '5')
                    ->setCellValue('J1', '6')
                    ->setCellValue('K1', '7')
                    ->setCellValue('L1', '8')
                    ->setCellValue('M1', '9')
                    ->setCellValue('N1', '10')
                    ->setCellValue('O1', '11')
                    ->setCellValue('P1', '12')
                    ->setCellValue('Q1', '13')
                    ->setCellValue('R1', '14')
                    ->setCellValue('S1', '15')
                    ->setCellValue('T1', '16')
                    ->setCellValue('U1', '17')
                    ->setCellValue('V1', '18')
                    ->setCellValue('W1', '19')
                    ->setCellValue('X1', '20')
                    ->setCellValue('Y1', '21')
                    ->setCellValue('Z1', '22')
                    ->setCellValue('AA1', '23')
                    ->setCellValue('AB1', '24')
                    ->setCellValue('AC1', '25')
                    ->setCellValue('AD1', '26')
                    ->setCellValue('AE1', '27')
                    ->setCellValue('AF1', '28')
                    ->setCellValue('AG1', '29')
                    ->setCellValue('AH1', '30')
                    ->setCellValue('AI1', '31');

        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet->getStyle('A1:AI1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:AI1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:'.$arrKolom[$kemaren].'1')->applyFromArray(
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

        $sheet->getStyle($arrKolom[$now].'1')->applyFromArray(
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

        if($arrKolom[$lusa] != 'AI'){
            $sheet->getStyle($arrKolom[$lusa].'1:AI1')->applyFromArray(
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
            $sheet->getStyle('AI1')->applyFromArray(
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

        $no3=2;     $battery3='';

        $tot_3_TGL_1 = 0;   $tot_3_TGL_10 = 0;      $tot_3_TGL_19 = 0;      $tot_3_TGL_28 = 0;
        $tot_3_TGL_2 = 0;   $tot_3_TGL_11 = 0;      $tot_3_TGL_20 = 0;      $tot_3_TGL_29 = 0;
        $tot_3_TGL_3 = 0;   $tot_3_TGL_12 = 0;      $tot_3_TGL_21 = 0;      $tot_3_TGL_30 = 0;
        $tot_3_TGL_4 = 0;   $tot_3_TGL_13 = 0;      $tot_3_TGL_22 = 0;      $tot_3_TGL_31 = 0;
        $tot_3_TGL_5 = 0;   $tot_3_TGL_14 = 0;      $tot_3_TGL_23 = 0;      $prog_3_plan = 0;
        $tot_3_TGL_6 = 0;   $tot_3_TGL_15 = 0;      $tot_3_TGL_24 = 0;      $prog_3_output = 0;
        $tot_3_TGL_7 = 0;   $tot_3_TGL_16 = 0;      $tot_3_TGL_25 = 0;      $tot_3_total_p = 0;       $tot_3_total_o = 0;
        $tot_3_TGL_8 = 0;   $tot_3_TGL_17 = 0;      $tot_3_TGL_26 = 0;      $tot_3_progress_p = 0;    $tot_3_progress_o = 0;
        $tot_3_TGL_9 = 0;   $tot_3_TGL_18 = 0;      $tot_3_TGL_27 = 0;

        while ($row3=sqlsrv_fetch_object($data3)){
            $TGL_3_1 = $row3->TGL_1;   $TGL_3_10 = $row3->TGL_10;      $TGL_3_19 = $row3->TGL_19;      $TGL_3_28 = $row3->TGL_28;
            $TGL_3_2 = $row3->TGL_2;   $TGL_3_11 = $row3->TGL_11;      $TGL_3_20 = $row3->TGL_20;      $TGL_3_29 = $row3->TGL_29;
            $TGL_3_3 = $row3->TGL_3;   $TGL_3_12 = $row3->TGL_12;      $TGL_3_21 = $row3->TGL_21;      $TGL_3_30 = $row3->TGL_30;
            $TGL_3_4 = $row3->TGL_4;   $TGL_3_13 = $row3->TGL_13;      $TGL_3_22 = $row3->TGL_22;      $TGL_3_31 = $row3->TGL_31;
            $TGL_3_5 = $row3->TGL_5;   $TGL_3_14 = $row3->TGL_14;      $TGL_3_23 = $row3->TGL_23;
            $TGL_3_6 = $row3->TGL_6;   $TGL_3_15 = $row3->TGL_15;      $TGL_3_24 = $row3->TGL_24;
            $TGL_3_7 = $row3->TGL_7;   $TGL_3_16 = $row3->TGL_16;      $TGL_3_25 = $row3->TGL_25;
            $TGL_3_8 = $row3->TGL_8;   $TGL_3_17 = $row3->TGL_17;      $TGL_3_26 = $row3->TGL_26;
            $TGL_3_9 = $row3->TGL_9;   $TGL_3_18 = $row3->TGL_18;      $TGL_3_27 = $row3->TGL_27;

            if($kemaren==1){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1;
                }
            }elseif($kemaren == 2){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2;
                }
            }elseif($kemaren == 3){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $$TGL_3_1+$TGL_3_2+$TGL_3_3;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3;
                }
            }elseif($kemaren == 4){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4;
                }
            }elseif($kemaren == 5){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5;
                }
            }elseif($kemaren == 6){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6;
                }
            }elseif($kemaren == 7){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7;
                }
            }elseif($kemaren == 8){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8;
                }
            }elseif($kemaren == 9){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9;
                }
            }elseif($kemaren == 10){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10;
                }
            }elseif($kemaren == 11){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11;
                }
            }elseif($kemaren == 12){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12;
                }
            }elseif($kemaren == 13){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13;
                }
            }elseif($kemaren == 14){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14;
                }
            }elseif($kemaren == 15){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15;
                }
            }elseif($kemaren == 16){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16;
                }
            }elseif($kemaren == 17){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17;
                }
            }elseif($kemaren == 18){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18;
                }
            }elseif($kemaren == 19){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19;
                }
            }elseif($kemaren == 20){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20;
                }
            }elseif($kemaren == 21){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21;
                }
            }elseif($kemaren == 22){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22;
                }
            }elseif($kemaren == 23){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23;
                }
            }elseif($kemaren == 24){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24;
                }
            }elseif($kemaren == 25){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25;
                }
            }elseif($kemaren == 26){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26;
                }
            }elseif($kemaren == 27){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27;
                }
            }elseif($kemaren == 28){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27+$TGL_3_28;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27+$TGL_3_28;
                }
            }elseif($kemaren == 29){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27+$TGL_3_28+$TGL_3_29;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27+$TGL_3_28+$TGL_3_29;
                }
            }elseif($kemaren == 30){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27+$TGL_3_28+$TGL_3_29+$TGL_3_30;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27+$TGL_3_28+$TGL_3_29+$TGL_3_30;
                }
            }elseif($kemaren == 31){
                if($row3->ORDERS == 'PLAN'){
                    $prog_3_plan = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27+$TGL_3_28+$TGL_3_29+$TGL_3_30+$TGL_3_31;
                }elseif($row3->ORDERS == 'OUTPUT'){
                    $prog_3_output = $TGL_3_1+$TGL_3_2+$TGL_3_3+$TGL_3_4+$TGL_3_5+$TGL_3_6+$TGL_3_7+$TGL_3_8+$TGL_3_9+$TGL_3_10+$TGL_3_11+$TGL_3_12+$TGL_3_13+$TGL_3_14+$TGL_3_15+$TGL_3_16+$TGL_3_17+$TGL_3_18+$TGL_3_19+$TGL_3_20+$TGL_3_21+$TGL_3_22+$TGL_3_23+$TGL_3_24+$TGL_3_25+$TGL_3_26+$TGL_3_27+$TGL_3_28+$TGL_3_29+$TGL_3_30+$TGL_3_31;
                }
            }

            if ($no3==2){
                $LR3 = $row3->GROUPINGLABEL;
                /*PLAN YG KE-1*/
                $progress3 = $prog_3_plan;
                $tot_3_total_p = $row3->TOTAL;
                $tot_3_progress_p = $prog_3_plan;
                $tot_3_TGL_1 -= $TGL_3_1;   $tot_3_TGL_10 -= $TGL_3_10;      $tot_3_TGL_19 -= $TGL_3_19;      $tot_3_TGL_28 -= $TGL_3_28;
                $tot_3_TGL_2 -= $TGL_3_2;   $tot_3_TGL_11 -= $TGL_3_11;      $tot_3_TGL_20 -= $TGL_3_20;      $tot_3_TGL_29 -= $TGL_3_29;
                $tot_3_TGL_3 -= $TGL_3_3;   $tot_3_TGL_12 -= $TGL_3_12;      $tot_3_TGL_21 -= $TGL_3_21;      $tot_3_TGL_30 -= $TGL_3_30;
                $tot_3_TGL_4 -= $TGL_3_4;   $tot_3_TGL_13 -= $TGL_3_13;      $tot_3_TGL_22 -= $TGL_3_22;      $tot_3_TGL_31 -= $TGL_3_31;
                $tot_3_TGL_5 -= $TGL_3_5;   $tot_3_TGL_14 -= $TGL_3_14;      $tot_3_TGL_23 -= $TGL_3_23;      
                $tot_3_TGL_6 -= $TGL_3_6;   $tot_3_TGL_15 -= $TGL_3_15;      $tot_3_TGL_24 -= $TGL_3_24;      
                $tot_3_TGL_7 -= $TGL_3_7;   $tot_3_TGL_16 -= $TGL_3_16;      $tot_3_TGL_25 -= $TGL_3_25;
                $tot_3_TGL_8 -= $TGL_3_8;   $tot_3_TGL_17 -= $TGL_3_17;      $tot_3_TGL_26 -= $TGL_3_26;
                $tot_3_TGL_9 -= $TGL_3_9;   $tot_3_TGL_18 -= $TGL_3_18;      $tot_3_TGL_27 -= $TGL_3_27;
            }else{
                if($row3->GROUPINGLABEL == $battery3){
                    $LR3 = "";
                    /*INI BUAT OUTPUT*/
                    $progress3 = $prog_3_output;
                    $tot_3_total_o = $row3->TOTAL;
                    $tot_3_progress_o = $prog_3_output;
                    $tot_3_TGL_1 += $TGL_3_1;     $tot_3_TGL_10 += $TGL_3_10;      $tot_3_TGL_19 += $TGL_3_19;      $tot_3_TGL_28 += $TGL_3_28;
                    $tot_3_TGL_2 += $TGL_3_2;     $tot_3_TGL_11 += $TGL_3_11;      $tot_3_TGL_20 += $TGL_3_20;      $tot_3_TGL_29 += $TGL_3_29;
                    $tot_3_TGL_3 += $TGL_3_3;     $tot_3_TGL_12 += $TGL_3_12;      $tot_3_TGL_21 += $TGL_3_21;      $tot_3_TGL_30 += $TGL_3_30;
                    $tot_3_TGL_4 += $TGL_3_4;     $tot_3_TGL_13 += $TGL_3_13;      $tot_3_TGL_22 += $TGL_3_22;      $tot_3_TGL_31 += $TGL_3_31;
                    $tot_3_TGL_5 += $TGL_3_5;     $tot_3_TGL_14 += $TGL_3_14;      $tot_3_TGL_23 += $TGL_3_23;      
                    $tot_3_TGL_6 += $TGL_3_6;     $tot_3_TGL_15 += $TGL_3_15;      $tot_3_TGL_24 += $TGL_3_24;      
                    $tot_3_TGL_7 += $TGL_3_7;     $tot_3_TGL_16 += $TGL_3_16;      $tot_3_TGL_25 += $TGL_3_25;
                    $tot_3_TGL_8 += $TGL_3_8;     $tot_3_TGL_17 += $TGL_3_17;      $tot_3_TGL_26 += $TGL_3_26;
                    $tot_3_TGL_9 += $TGL_3_9;     $tot_3_TGL_18 += $TGL_3_18;      $tot_3_TGL_27 += $TGL_3_27;
                }else{
                    $LR3 = $row3->GROUPINGLABEL;

                    /*$tot_3_TGL_1 = $tot_3_TGL_1;
                    $tot_3_TGL_2 = $tot_3_TGL_1+$tot_3_TGL_2;
                    $tot_3_TGL_3 = $tot_3_TGL_2+$tot_3_TGL_3;
                    $tot_3_TGL_4 = $tot_3_TGL_3+$tot_3_TGL_4;  
                    $tot_3_TGL_5 = $tot_3_TGL_4+$tot_3_TGL_5;
                    $tot_3_TGL_6 = $tot_3_TGL_5+$tot_3_TGL_6;
                    $tot_3_TGL_7 = $tot_3_TGL_6+$tot_3_TGL_7;
                    $tot_3_TGL_8 = $tot_3_TGL_7+$tot_3_TGL_8;
                    $tot_3_TGL_9 = $tot_3_TGL_8+$tot_3_TGL_9;
                    $tot_3_TGL_10 = $tot_3_TGL_9+$tot_3_TGL_10;
                    $tot_3_TGL_11 = $tot_3_TGL_10+$tot_3_TGL_11;
                    $tot_3_TGL_12 = $tot_3_TGL_11+$tot_3_TGL_12;
                    $tot_3_TGL_13 = $tot_3_TGL_12+$tot_3_TGL_13;
                    $tot_3_TGL_14 = $tot_3_TGL_13+$tot_3_TGL_14;
                    $tot_3_TGL_15 = $tot_3_TGL_14+$tot_3_TGL_15;
                    $tot_3_TGL_16 = $tot_3_TGL_15+$tot_3_TGL_16;
                    $tot_3_TGL_17 = $tot_3_TGL_16+$tot_3_TGL_17;
                    $tot_3_TGL_18 = $tot_3_TGL_17+$tot_3_TGL_18;
                    $tot_3_TGL_19 = $tot_3_TGL_18+$tot_3_TGL_19;
                    $tot_3_TGL_20 = $tot_3_TGL_19+$tot_3_TGL_20;
                    $tot_3_TGL_21 = $tot_3_TGL_20+$tot_3_TGL_21;
                    $tot_3_TGL_22 = $tot_3_TGL_21+$tot_3_TGL_22;
                    $tot_3_TGL_23 = $tot_3_TGL_22+$tot_3_TGL_23;
                    $tot_3_TGL_24 = $tot_3_TGL_23+$tot_3_TGL_24;
                    $tot_3_TGL_25 = $tot_3_TGL_24+$tot_3_TGL_25;
                    $tot_3_TGL_26 = $tot_3_TGL_25+$tot_3_TGL_26;
                    $tot_3_TGL_27 = $tot_3_TGL_26+$tot_3_TGL_27;
                    $tot_3_TGL_28 = $tot_3_TGL_27+$tot_3_TGL_28;
                    $tot_3_TGL_29 = $tot_3_TGL_28+$tot_3_TGL_29;
                    $tot_3_TGL_30 = $tot_3_TGL_29+$tot_3_TGL_30;
                    $tot_3_TGL_31 = $tot_3_TGL_30+$tot_3_TGL_31;*/

                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no3, '')
                                ->setCellValue('B'.$no3, 'GAP')
                                ->setCellValue('C'.$no3, $tot_3_total_o - $tot_3_total_p)
                                ->setCellValue('D'.$no3, $tot_3_progress_o - $tot_3_progress_p)
                                ->setCellValue('E'.$no3, $tot_3_TGL_1)
                                ->setCellValue('F'.$no3, $tot_3_TGL_2)
                                ->setCellValue('G'.$no3, $tot_3_TGL_3)
                                ->setCellValue('H'.$no3, $tot_3_TGL_4)
                                ->setCellValue('I'.$no3, $tot_3_TGL_5)
                                ->setCellValue('J'.$no3, $tot_3_TGL_6)
                                ->setCellValue('K'.$no3, $tot_3_TGL_7)
                                ->setCellValue('L'.$no3, $tot_3_TGL_8)
                                ->setCellValue('M'.$no3, $tot_3_TGL_9)
                                ->setCellValue('N'.$no3, $tot_3_TGL_10)
                                ->setCellValue('O'.$no3, $tot_3_TGL_11)
                                ->setCellValue('P'.$no3, $tot_3_TGL_12)
                                ->setCellValue('Q'.$no3, $tot_3_TGL_13)
                                ->setCellValue('R'.$no3, $tot_3_TGL_14)
                                ->setCellValue('S'.$no3, $tot_3_TGL_15)
                                ->setCellValue('T'.$no3, $tot_3_TGL_16)
                                ->setCellValue('U'.$no3, $tot_3_TGL_17)
                                ->setCellValue('V'.$no3, $tot_3_TGL_18)
                                ->setCellValue('W'.$no3, $tot_3_TGL_19)
                                ->setCellValue('X'.$no3, $tot_3_TGL_20)
                                ->setCellValue('Y'.$no3, $tot_3_TGL_21)
                                ->setCellValue('Z'.$no3, $tot_3_TGL_22)
                                ->setCellValue('AA'.$no3, $tot_3_TGL_23)
                                ->setCellValue('AB'.$no3, $tot_3_TGL_24)
                                ->setCellValue('AC'.$no3, $tot_3_TGL_25)
                                ->setCellValue('AD'.$no3, $tot_3_TGL_26)
                                ->setCellValue('AE'.$no3, $tot_3_TGL_27)
                                ->setCellValue('AF'.$no3, $tot_3_TGL_28)
                                ->setCellValue('AG'.$no3, $tot_3_TGL_29)
                                ->setCellValue('AH'.$no3, $tot_3_TGL_30)
                                ->setCellValue('AI'.$no3, $tot_3_TGL_31);

                    $sheet->getStyle('A'.$no3)->applyFromArray(
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

                    if($arrKolom[$lusa] != 'AI'){
                        $sheet->getStyle($arrKolom[$lusa].$no3.':AI'.$no3)->applyFromArray(
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
                        $sheet->getStyle('AI'.$no3)->applyFromArray(
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

                    $objPHPExcel->getActiveSheet()->getStyle('C'.$no3.':AI'.$no3)->getNumberFormat()->setFormatCode('#,##0');

                    $no3++;

                    $tot_3_TGL_1 = 0;   $tot_3_TGL_10 = 0;      $tot_3_TGL_19 = 0;      $tot_3_TGL_28 = 0;
                    $tot_3_TGL_2 = 0;   $tot_3_TGL_11 = 0;      $tot_3_TGL_20 = 0;      $tot_3_TGL_29 = 0;
                    $tot_3_TGL_3 = 0;   $tot_3_TGL_12 = 0;      $tot_3_TGL_21 = 0;      $tot_3_TGL_30 = 0;
                    $tot_3_TGL_4 = 0;   $tot_3_TGL_13 = 0;      $tot_3_TGL_22 = 0;      $tot_3_TGL_31 = 0;
                    $tot_3_TGL_5 = 0;   $tot_3_TGL_14 = 0;      $tot_3_TGL_23 = 0;      $tot_3_total_p = 0;       $tot_3_total_o = 0;
                    $tot_3_TGL_6 = 0;   $tot_3_TGL_15 = 0;      $tot_3_TGL_24 = 0;      $tot_3_progress_p = 0;    $tot_3_progress_o = 0;
                    $tot_3_TGL_7 = 0;   $tot_3_TGL_16 = 0;      $tot_3_TGL_25 = 0;      
                    $tot_3_TGL_8 = 0;   $tot_3_TGL_17 = 0;      $tot_3_TGL_26 = 0;      
                    $tot_3_TGL_9 = 0;   $tot_3_TGL_18 = 0;      $tot_3_TGL_27 = 0;

                    $progress3 = $prog_3_plan;
                    $tot_3_total_p = $row3->TOTAL;
                    $tot_3_progress_p = $prog_3_plan;
                    $tot_3_TGL_1 -= $TGL_3_1;   $tot_3_TGL_10 -= $TGL_3_10;      $tot_3_TGL_19 -= $TGL_3_19;      $tot_3_TGL_28 -= $TGL_3_28;
                    $tot_3_TGL_2 -= $TGL_3_2;   $tot_3_TGL_11 -= $TGL_3_11;      $tot_3_TGL_20 -= $TGL_3_20;      $tot_3_TGL_29 -= $TGL_3_29;
                    $tot_3_TGL_3 -= $TGL_3_3;   $tot_3_TGL_12 -= $TGL_3_12;      $tot_3_TGL_21 -= $TGL_3_21;      $tot_3_TGL_30 -= $TGL_3_30;
                    $tot_3_TGL_4 -= $TGL_3_4;   $tot_3_TGL_13 -= $TGL_3_13;      $tot_3_TGL_22 -= $TGL_3_22;      $tot_3_TGL_31 -= $TGL_3_31;
                    $tot_3_TGL_5 -= $TGL_3_5;   $tot_3_TGL_14 -= $TGL_3_14;      $tot_3_TGL_23 -= $TGL_3_23;      
                    $tot_3_TGL_6 -= $TGL_3_6;   $tot_3_TGL_15 -= $TGL_3_15;      $tot_3_TGL_24 -= $TGL_3_24;      
                    $tot_3_TGL_7 -= $TGL_3_7;   $tot_3_TGL_16 -= $TGL_3_16;      $tot_3_TGL_25 -= $TGL_3_25;
                    $tot_3_TGL_8 -= $TGL_3_8;   $tot_3_TGL_17 -= $TGL_3_17;      $tot_3_TGL_26 -= $TGL_3_26;
                    $tot_3_TGL_9 -= $TGL_3_9;   $tot_3_TGL_18 -= $TGL_3_18;      $tot_3_TGL_27 -= $TGL_3_27;
                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no3, $LR3)
                        ->setCellValue('B'.$no3, $row3->ORDERS)
                        ->setCellValue('C'.$no3, $row3->TOTAL)
                        ->setCellValue('D'.$no3, $progress3)
                        ->setCellValue('E'.$no3, $row3->TGL_1)
                        ->setCellValue('F'.$no3, $row3->TGL_2)
                        ->setCellValue('G'.$no3, $row3->TGL_3)
                        ->setCellValue('H'.$no3, $row3->TGL_4)
                        ->setCellValue('I'.$no3, $row3->TGL_5)
                        ->setCellValue('J'.$no3, $row3->TGL_6)
                        ->setCellValue('K'.$no3, $row3->TGL_7)
                        ->setCellValue('L'.$no3, $row3->TGL_8)
                        ->setCellValue('M'.$no3, $row3->TGL_9)
                        ->setCellValue('N'.$no3, $row3->TGL_10)
                        ->setCellValue('O'.$no3, $row3->TGL_11)
                        ->setCellValue('P'.$no3, $row3->TGL_12)
                        ->setCellValue('Q'.$no3, $row3->TGL_13)
                        ->setCellValue('R'.$no3, $row3->TGL_14)
                        ->setCellValue('S'.$no3, $row3->TGL_15)
                        ->setCellValue('T'.$no3, $row3->TGL_16)
                        ->setCellValue('U'.$no3, $row3->TGL_17)
                        ->setCellValue('V'.$no3, $row3->TGL_18)
                        ->setCellValue('W'.$no3, $row3->TGL_19)
                        ->setCellValue('X'.$no3, $row3->TGL_20)
                        ->setCellValue('Y'.$no3, $row3->TGL_21)
                        ->setCellValue('Z'.$no3, $row3->TGL_22)
                        ->setCellValue('AA'.$no3, $row3->TGL_23)
                        ->setCellValue('AB'.$no3, $row3->TGL_24)
                        ->setCellValue('AC'.$no3, $row3->TGL_25)
                        ->setCellValue('AD'.$no3, $row3->TGL_26)
                        ->setCellValue('AE'.$no3, $row3->TGL_27)
                        ->setCellValue('AF'.$no3, $row3->TGL_28)
                        ->setCellValue('AG'.$no3, $row3->TGL_29)
                        ->setCellValue('AH'.$no3, $row3->TGL_30)
                        ->setCellValue('AI'.$no3, $row3->TGL_31);

            if($row3->ORDERS == 'PLAN'){
                
                $sheet->getStyle('A'.$no3)->applyFromArray(
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

                if($arrKolom[$lusa] != 'AI'){
                    $sheet->getStyle($arrKolom[$lusa].$no3.':AI'.$no3)->applyFromArray(
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
                    $sheet->getStyle('AI'.$no3)->applyFromArray(
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
            }else{
                $sheet->getStyle('A'.$no3)->applyFromArray(
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

                if($arrKolom[$lusa] != 'AI'){
                    $sheet->getStyle($arrKolom[$lusa].$no3.':AI'.$no3)->applyFromArray(
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
                    $sheet->getStyle('AI'.$no3)->applyFromArray(
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
            }

            $objPHPExcel->getActiveSheet()->getStyle('C'.$no3.':AI'.$no3)->getNumberFormat()->setFormatCode('#,##0');

            $battery3 = $row3->GROUPINGLABEL;
            $no3++;
        }

        /*$tot_3_TGL_1 = $tot_3_TGL_1;
        $tot_3_TGL_2 = $tot_3_TGL_1+$tot_3_TGL_2;
        $tot_3_TGL_3 = $tot_3_TGL_2+$tot_3_TGL_3;
        $tot_3_TGL_4 = $tot_3_TGL_3+$tot_3_TGL_4;  
        $tot_3_TGL_5 = $tot_3_TGL_4+$tot_3_TGL_5;
        $tot_3_TGL_6 = $tot_3_TGL_5+$tot_3_TGL_6;
        $tot_3_TGL_7 = $tot_3_TGL_6+$tot_3_TGL_7;
        $tot_3_TGL_8 = $tot_3_TGL_7+$tot_3_TGL_8;
        $tot_3_TGL_9 = $tot_3_TGL_8+$tot_3_TGL_9;
        $tot_3_TGL_10 = $tot_3_TGL_9+$tot_3_TGL_10;
        $tot_3_TGL_11 = $tot_3_TGL_10+$tot_3_TGL_11;
        $tot_3_TGL_12 = $tot_3_TGL_11+$tot_3_TGL_12;
        $tot_3_TGL_13 = $tot_3_TGL_12+$tot_3_TGL_13;
        $tot_3_TGL_14 = $tot_3_TGL_13+$tot_3_TGL_14;
        $tot_3_TGL_15 = $tot_3_TGL_14+$tot_3_TGL_15;
        $tot_3_TGL_16 = $tot_3_TGL_15+$tot_3_TGL_16;
        $tot_3_TGL_17 = $tot_3_TGL_16+$tot_3_TGL_17;
        $tot_3_TGL_18 = $tot_3_TGL_17+$tot_3_TGL_18;
        $tot_3_TGL_19 = $tot_3_TGL_18+$tot_3_TGL_19;
        $tot_3_TGL_20 = $tot_3_TGL_19+$tot_3_TGL_20;
        $tot_3_TGL_21 = $tot_3_TGL_20+$tot_3_TGL_21;
        $tot_3_TGL_22 = $tot_3_TGL_21+$tot_3_TGL_22;
        $tot_3_TGL_23 = $tot_3_TGL_22+$tot_3_TGL_23;
        $tot_3_TGL_24 = $tot_3_TGL_23+$tot_3_TGL_24;
        $tot_3_TGL_25 = $tot_3_TGL_24+$tot_3_TGL_25;
        $tot_3_TGL_26 = $tot_3_TGL_25+$tot_3_TGL_26;
        $tot_3_TGL_27 = $tot_3_TGL_26+$tot_3_TGL_27;
        $tot_3_TGL_28 = $tot_3_TGL_27+$tot_3_TGL_28;
        $tot_3_TGL_29 = $tot_3_TGL_28+$tot_3_TGL_29;
        $tot_3_TGL_30 = $tot_3_TGL_29+$tot_3_TGL_30;
        $tot_3_TGL_31 = $tot_3_TGL_30+$tot_3_TGL_31;*/

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no3, '')
                    ->setCellValue('B'.$no3, 'GAP')
                    ->setCellValue('C'.$no3, $tot_3_total_o - $tot_3_total_p)
                    ->setCellValue('D'.$no3, $tot_3_progress_o - $tot_3_progress_p)
                    ->setCellValue('E'.$no3, $tot_3_TGL_1)
                    ->setCellValue('F'.$no3, $tot_3_TGL_2)
                    ->setCellValue('G'.$no3, $tot_3_TGL_3)
                    ->setCellValue('H'.$no3, $tot_3_TGL_4)
                    ->setCellValue('I'.$no3, $tot_3_TGL_5)
                    ->setCellValue('J'.$no3, $tot_3_TGL_6)
                    ->setCellValue('K'.$no3, $tot_3_TGL_7)
                    ->setCellValue('L'.$no3, $tot_3_TGL_8)
                    ->setCellValue('M'.$no3, $tot_3_TGL_9)
                    ->setCellValue('N'.$no3, $tot_3_TGL_10)
                    ->setCellValue('O'.$no3, $tot_3_TGL_11)
                    ->setCellValue('P'.$no3, $tot_3_TGL_12)
                    ->setCellValue('Q'.$no3, $tot_3_TGL_13)
                    ->setCellValue('R'.$no3, $tot_3_TGL_14)
                    ->setCellValue('S'.$no3, $tot_3_TGL_15)
                    ->setCellValue('T'.$no3, $tot_3_TGL_16)
                    ->setCellValue('U'.$no3, $tot_3_TGL_17)
                    ->setCellValue('V'.$no3, $tot_3_TGL_18)
                    ->setCellValue('W'.$no3, $tot_3_TGL_19)
                    ->setCellValue('X'.$no3, $tot_3_TGL_20)
                    ->setCellValue('Y'.$no3, $tot_3_TGL_21)
                    ->setCellValue('Z'.$no3, $tot_3_TGL_22)
                    ->setCellValue('AA'.$no3, $tot_3_TGL_23)
                    ->setCellValue('AB'.$no3, $tot_3_TGL_24)
                    ->setCellValue('AC'.$no3, $tot_3_TGL_25)
                    ->setCellValue('AD'.$no3, $tot_3_TGL_26)
                    ->setCellValue('AE'.$no3, $tot_3_TGL_27)
                    ->setCellValue('AF'.$no3, $tot_3_TGL_28)
                    ->setCellValue('AG'.$no3, $tot_3_TGL_29)
                    ->setCellValue('AH'.$no3, $tot_3_TGL_30)
                    ->setCellValue('AI'.$no3, $tot_3_TGL_31);

        $sheet->getStyle('A'.$no3)->applyFromArray(
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

        if($arrKolom[$lusa] != 'AI'){
            $sheet->getStyle($arrKolom[$lusa].$no3.':AI'.$no3)->applyFromArray(
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
            $sheet->getStyle('AI'.$no3)->applyFromArray(
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

        $objPHPExcel->setActiveSheetIndex($s);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$no3.':AI'.$no3)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->setTitle('PACKAGING GROUP');
    }elseif ($Arr_sheet[$s] == "COMPARATION"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'LABEL TYPE')
                    ->setCellValue('B1', 'WORK ORDER')
                    ->setCellValue('C1', 'ITEM NO')
                    ->setCellValue('D1', 'ITEM NAME')
                    ->setCellValue('E1', 'DATE CODE')
                    ->setCellValue('F1', 'TYPE')
                    ->setCellValue('G1', 'GRADE')
                    ->setCellValue('H1', 'CR DATE')
                    ->setCellValue('I1', 'QTY ORDER')
                    ->setCellValue('J1', 'OT')
                    ->setCellValue('K1', 'PROGRESS')
                    ->setCellValue('L1', 'REMARK')
                    ->setCellValue('M1', 'TOTAL')
                    ->setCellValue('N1', '1')
                    ->setCellValue('O1', '2')
                    ->setCellValue('P1', '3')
                    ->setCellValue('Q1', '4')
                    ->setCellValue('R1', '5')
                    ->setCellValue('S1', '6')
                    ->setCellValue('T1', '7')
                    ->setCellValue('U1', '8')
                    ->setCellValue('V1', '9')
                    ->setCellValue('W1', '10')
                    ->setCellValue('X1', '11')
                    ->setCellValue('Y1', '12')
                    ->setCellValue('Z1', '13')
                    ->setCellValue('AA1', '14')
                    ->setCellValue('AB1', '15')
                    ->setCellValue('AC1', '16')
                    ->setCellValue('AD1', '17')
                    ->setCellValue('AE1', '18')
                    ->setCellValue('AF1', '19')
                    ->setCellValue('AG1', '20')
                    ->setCellValue('AH1', '21')
                    ->setCellValue('AI1', '22')
                    ->setCellValue('AJ1', '23')
                    ->setCellValue('AK1', '24')
                    ->setCellValue('AL1', '25')
                    ->setCellValue('AM1', '26')
                    ->setCellValue('AN1', '27')
                    ->setCellValue('AO1', '28')
                    ->setCellValue('AP1', '29')
                    ->setCellValue('AQ1', '30')
                    ->setCellValue('AR1', '31');

        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet->getStyle('A1:AR1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:AR1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:'.$arrKolom2[$kemaren].'1')->applyFromArray(
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

        $sheet->getStyle($arrKolom2[$now].'1')->applyFromArray(
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

        if($arrKolom2[$lusa] != 'AR'){
            $sheet->getStyle($arrKolom2[$lusa].'1:AR1')->applyFromArray(
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
            $sheet->getStyle('AR1')->applyFromArray(
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

        $no4=2;             $wo='';                 $ada = 0;               $lbl = '';
        $l = '';            $ino = '';              $dtc = '';              $grd = '';
        $w = '';            $inm = '';              $typ = '';              $crd = '';
        $q = '';            $ot = '';               $prog = '';
        $tot_4_TGL_1 = 0;   $tot_4_TGL_10 = 0;      $tot_4_TGL_19 = 0;      $tot_4_TGL_28 = 0;
        $tot_4_TGL_2 = 0;   $tot_4_TGL_11 = 0;      $tot_4_TGL_20 = 0;      $tot_4_TGL_29 = 0;
        $tot_4_TGL_3 = 0;   $tot_4_TGL_12 = 0;      $tot_4_TGL_21 = 0;      $tot_4_TGL_30 = 0;
        $tot_4_TGL_4 = 0;   $tot_4_TGL_13 = 0;      $tot_4_TGL_22 = 0;      $tot_4_TGL_31 = 0;
        $tot_4_TGL_5 = 0;   $tot_4_TGL_14 = 0;      $tot_4_TGL_23 = 0;      $tot_4_total = 0;
        $tot_4_TGL_6 = 0;   $tot_4_TGL_15 = 0;      $tot_4_TGL_24 = 0;
        $tot_4_TGL_7 = 0;   $tot_4_TGL_16 = 0;      $tot_4_TGL_25 = 0;
        $tot_4_TGL_8 = 0;   $tot_4_TGL_17 = 0;      $tot_4_TGL_26 = 0;
        $tot_4_TGL_9 = 0;   $tot_4_TGL_18 = 0;      $tot_4_TGL_27 = 0;

        while ($row4=sqlsrv_fetch_object($data4)){
            $TGL_4_1 = $row4->SATU;             $TGL_4_11 = $row4->SEBELAS;              $TGL_4_21 = $row4->DUAPULUHSATU;               
            $TGL_4_31 = $row4->TIGAPULUHSATU;
            $TGL_4_2 = $row4->DUA;              $TGL_4_12 = $row4->DUABELAS;             $TGL_4_22 = $row4->DUAPULUHDUA;
            $TGL_4_3 = $row4->TIGA;             $TGL_4_13 = $row4->TIGABELAS;            $TGL_4_23 = $row4->DUAPULUHTIGA;
            $TGL_4_4 = $row4->EMPAT;            $TGL_4_14 = $row4->EMPATBELAS;           $TGL_4_24 = $row4->DUAPULUHEMPAT;
            $TGL_4_5 = $row4->LIMA;             $TGL_4_15 = $row4->LIMABELAS;            $TGL_4_25 = $row4->DUAPULUHLIMA;
            $TGL_4_6 = $row4->ENAM;             $TGL_4_16 = $row4->ENAMBELAS;            $TGL_4_26 = $row4->DUAPULUHENAM;
            $TGL_4_7 = $row4->TUJUH;            $TGL_4_17 = $row4->TUJUHBELAS;           $TGL_4_27 = $row4->DUAPULUHTUJUH;
            $TGL_4_8 = $row4->DELAPAN;          $TGL_4_18 = $row4->DELAPANBELAS;         $TGL_4_28 = $row4->DUAPULUHDELAPAN;
            $TGL_4_9 = $row4->SEMBILAN;         $TGL_4_19 = $row4->SEMBILANBELAS;        $TGL_4_29 = $row4->DUAPULUHSEMBILAN;
            $TGL_4_10 = $row4->SEPULUH;         $TGL_4_20 = $row4->DUAPULUH;             $TGL_4_30 = $row4->TIGAPULUH;

            $sts = $row4->STAT;

            if ($sts == 'A'){
                $sts = 'PLAN';
            }else{
                $sts = 'OUTPUT';
            }

            if($no4==2){
                /*1. buat satu row header*/
                $objPHPExcel->setActiveSheetIndex($s)
                            ->setCellValue('A'.$no4, $row4->LABEL_TYPE)
                            ->setCellValue('B'.$no4, '')
                            ->setCellValue('C'.$no4, '')
                            ->setCellValue('D'.$no4, '')
                            ->setCellValue('E'.$no4, '')
                            ->setCellValue('F'.$no4, '')
                            ->setCellValue('G'.$no4, '')
                            ->setCellValue('H'.$no4, '')
                            ->setCellValue('I'.$no4, '')
                            ->setCellValue('J'.$no4, '')
                            ->setCellValue('K'.$no4, '')
                            ->setCellValue('L'.$no4, '')
                            ->setCellValue('M'.$no4, '')
                            ->setCellValue('N'.$no4, '')
                            ->setCellValue('O'.$no4, '')
                            ->setCellValue('P'.$no4, '')
                            ->setCellValue('Q'.$no4, '')
                            ->setCellValue('R'.$no4, '')
                            ->setCellValue('S'.$no4, '')
                            ->setCellValue('T'.$no4, '')
                            ->setCellValue('U'.$no4, '')
                            ->setCellValue('V'.$no4, '')
                            ->setCellValue('W'.$no4, '')
                            ->setCellValue('X'.$no4, '')
                            ->setCellValue('Y'.$no4, '')
                            ->setCellValue('Z'.$no4, '')
                            ->setCellValue('AA'.$no4, '')
                            ->setCellValue('AB'.$no4, '')
                            ->setCellValue('AC'.$no4, '')
                            ->setCellValue('AD'.$no4, '')
                            ->setCellValue('AE'.$no4, '')
                            ->setCellValue('AF'.$no4, '')
                            ->setCellValue('AG'.$no4, '')
                            ->setCellValue('AH'.$no4, '')
                            ->setCellValue('AI'.$no4, '')
                            ->setCellValue('AJ'.$no4, '')
                            ->setCellValue('AK'.$no4, '')
                            ->setCellValue('AL'.$no4, '')
                            ->setCellValue('AM'.$no4, '')
                            ->setCellValue('AN'.$no4, '')
                            ->setCellValue('AO'.$no4, '')
                            ->setCellValue('AP'.$no4, '')
                            ->setCellValue('AQ'.$no4, '')
                            ->setCellValue('AR'.$no4, '');

                $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A'.$no4.':D'.$no4);
                $sheet->getStyle('A'.$no4.':AR'.$no4)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'FFFF00')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        ),
                        'font'  => array(
                            'bold'  => true,
                            'size'  => 14
                        )
                    )
                );
                $no4++;

                if($row4->GRADE == '' OR is_null($row4->GRADE)){    //belum dikerjakan
                    $tot_4_TGL_1 = 0;   $tot_4_TGL_10 = 0;      $tot_4_TGL_19 = 0;      $tot_4_TGL_28 = 0;
                    $tot_4_TGL_2 = 0;   $tot_4_TGL_11 = 0;      $tot_4_TGL_20 = 0;      $tot_4_TGL_29 = 0;
                    $tot_4_TGL_3 = 0;   $tot_4_TGL_12 = 0;      $tot_4_TGL_21 = 0;      $tot_4_TGL_30 = 0;
                    $tot_4_TGL_4 = 0;   $tot_4_TGL_13 = 0;      $tot_4_TGL_22 = 0;      $tot_4_TGL_31 = 0;
                    $tot_4_TGL_5 = 0;   $tot_4_TGL_14 = 0;      $tot_4_TGL_23 = 0;      $tot_4_total = 0;
                    $tot_4_TGL_6 = 0;   $tot_4_TGL_15 = 0;      $tot_4_TGL_24 = 0;
                    $tot_4_TGL_7 = 0;   $tot_4_TGL_16 = 0;      $tot_4_TGL_25 = 0;
                    $tot_4_TGL_8 = 0;   $tot_4_TGL_17 = 0;      $tot_4_TGL_26 = 0;
                    $tot_4_TGL_9 = 0;   $tot_4_TGL_18 = 0;      $tot_4_TGL_27 = 0;

                    $l = '';        $ino = '';      $dtc = '';      $grd = '';
                    $w = '';        $inm = '';      $typ = '';      $crd = '';
                    $q = '';        $ot = '';       $prog = '';
                }else{
                    if($sts == 'PLAN'){
                        /*PLAN*/
                        $tot_4_TGL_1 += $TGL_4_1;   $tot_4_TGL_10 += $TGL_4_10;      $tot_4_TGL_19 += $TGL_4_19;      $tot_4_TGL_28 += $TGL_4_28;
                        $tot_4_TGL_2 += $TGL_4_2;   $tot_4_TGL_11 += $TGL_4_11;      $tot_4_TGL_20 += $TGL_4_20;      $tot_4_TGL_29 += $TGL_4_29;
                        $tot_4_TGL_3 += $TGL_4_3;   $tot_4_TGL_12 += $TGL_4_12;      $tot_4_TGL_21 += $TGL_4_21;      $tot_4_TGL_30 += $TGL_4_30;
                        $tot_4_TGL_4 += $TGL_4_4;   $tot_4_TGL_13 += $TGL_4_13;      $tot_4_TGL_22 += $TGL_4_22;      $tot_4_TGL_31 += $TGL_4_31;
                        $tot_4_TGL_5 += $TGL_4_5;   $tot_4_TGL_14 += $TGL_4_14;      $tot_4_TGL_23 += $TGL_4_23;      $tot_4_total += $row4->TOTAL;
                        $tot_4_TGL_6 += $TGL_4_6;   $tot_4_TGL_15 += $TGL_4_15;      $tot_4_TGL_24 += $TGL_4_24;      
                        $tot_4_TGL_7 += $TGL_4_7;   $tot_4_TGL_16 += $TGL_4_16;      $tot_4_TGL_25 += $TGL_4_25;
                        $tot_4_TGL_8 += $TGL_4_8;   $tot_4_TGL_17 += $TGL_4_17;      $tot_4_TGL_26 += $TGL_4_26;
                        $tot_4_TGL_9 += $TGL_4_9;   $tot_4_TGL_18 += $TGL_4_18;      $tot_4_TGL_27 += $TGL_4_27;
                        $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                        $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                    }else{
                        /*OUTPUT*/
                        $tot_4_TGL_1 -= $TGL_4_1;   $tot_4_TGL_10 -= $TGL_4_10;      $tot_4_TGL_19 -= $TGL_4_19;      $tot_4_TGL_28 -= $TGL_4_28;
                        $tot_4_TGL_2 -= $TGL_4_2;   $tot_4_TGL_11 -= $TGL_4_11;      $tot_4_TGL_20 -= $TGL_4_20;      $tot_4_TGL_29 -= $TGL_4_29;
                        $tot_4_TGL_3 -= $TGL_4_3;   $tot_4_TGL_12 -= $TGL_4_12;      $tot_4_TGL_21 -= $TGL_4_21;      $tot_4_TGL_30 -= $TGL_4_30;
                        $tot_4_TGL_4 -= $TGL_4_4;   $tot_4_TGL_13 -= $TGL_4_13;      $tot_4_TGL_22 -= $TGL_4_22;      $tot_4_TGL_31 -= $TGL_4_31;
                        $tot_4_TGL_5 -= $TGL_4_5;   $tot_4_TGL_14 -= $TGL_4_14;      $tot_4_TGL_23 -= $TGL_4_23;      $tot_4_total -= $row4->TOTAL;
                        $tot_4_TGL_6 -= $TGL_4_6;   $tot_4_TGL_15 -= $TGL_4_15;      $tot_4_TGL_24 -= $TGL_4_24;      
                        $tot_4_TGL_7 -= $TGL_4_7;   $tot_4_TGL_16 -= $TGL_4_16;      $tot_4_TGL_25 -= $TGL_4_25;
                        $tot_4_TGL_8 -= $TGL_4_8;   $tot_4_TGL_17 -= $TGL_4_17;      $tot_4_TGL_26 -= $TGL_4_26;
                        $tot_4_TGL_9 -= $TGL_4_9;   $tot_4_TGL_18 -= $TGL_4_18;      $tot_4_TGL_27 -= $TGL_4_27;

                        $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                        $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                    }
                }
            }else{
                if($lbl == $row4->LABEL_TYPE){
                    if($row4->GRADE == '' OR is_null($row4->GRADE)){    //belum dikerjakan
                        if($wo == $row4->WORK_ORDER){
                            if($sts == 'PLAN'){
                                /*PLAN*/
                                $tot_4_TGL_1 += $TGL_4_1;   $tot_4_TGL_10 += $TGL_4_10;      $tot_4_TGL_19 += $TGL_4_19;      $tot_4_TGL_28 += $TGL_4_28;
                                $tot_4_TGL_2 += $TGL_4_2;   $tot_4_TGL_11 += $TGL_4_11;      $tot_4_TGL_20 += $TGL_4_20;      $tot_4_TGL_29 += $TGL_4_29;
                                $tot_4_TGL_3 += $TGL_4_3;   $tot_4_TGL_12 += $TGL_4_12;      $tot_4_TGL_21 += $TGL_4_21;      $tot_4_TGL_30 += $TGL_4_30;
                                $tot_4_TGL_4 += $TGL_4_4;   $tot_4_TGL_13 += $TGL_4_13;      $tot_4_TGL_22 += $TGL_4_22;      $tot_4_TGL_31 += $TGL_4_31;
                                $tot_4_TGL_5 += $TGL_4_5;   $tot_4_TGL_14 += $TGL_4_14;      $tot_4_TGL_23 += $TGL_4_23;      $tot_4_total += $row4->TOTAL;
                                $tot_4_TGL_6 += $TGL_4_6;   $tot_4_TGL_15 += $TGL_4_15;      $tot_4_TGL_24 += $TGL_4_24;      
                                $tot_4_TGL_7 += $TGL_4_7;   $tot_4_TGL_16 += $TGL_4_16;      $tot_4_TGL_25 += $TGL_4_25;
                                $tot_4_TGL_8 += $TGL_4_8;   $tot_4_TGL_17 += $TGL_4_17;      $tot_4_TGL_26 += $TGL_4_26;
                                $tot_4_TGL_9 += $TGL_4_9;   $tot_4_TGL_18 += $TGL_4_18;      $tot_4_TGL_27 += $TGL_4_27;
                                $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                                $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                            }else{
                                /*OUTPUT*/
                                $tot_4_TGL_1 -= $TGL_4_1;   $tot_4_TGL_10 -= $TGL_4_10;      $tot_4_TGL_19 -= $TGL_4_19;      $tot_4_TGL_28 -= $TGL_4_28;
                                $tot_4_TGL_2 -= $TGL_4_2;   $tot_4_TGL_11 -= $TGL_4_11;      $tot_4_TGL_20 -= $TGL_4_20;      $tot_4_TGL_29 -= $TGL_4_29;
                                $tot_4_TGL_3 -= $TGL_4_3;   $tot_4_TGL_12 -= $TGL_4_12;      $tot_4_TGL_21 -= $TGL_4_21;      $tot_4_TGL_30 -= $TGL_4_30;
                                $tot_4_TGL_4 -= $TGL_4_4;   $tot_4_TGL_13 -= $TGL_4_13;      $tot_4_TGL_22 -= $TGL_4_22;      $tot_4_TGL_31 -= $TGL_4_31;
                                $tot_4_TGL_5 -= $TGL_4_5;   $tot_4_TGL_14 -= $TGL_4_14;      $tot_4_TGL_23 -= $TGL_4_23;      $tot_4_total -= $row4->TOTAL;
                                $tot_4_TGL_6 -= $TGL_4_6;   $tot_4_TGL_15 -= $TGL_4_15;      $tot_4_TGL_24 -= $TGL_4_24;      
                                $tot_4_TGL_7 -= $TGL_4_7;   $tot_4_TGL_16 -= $TGL_4_16;      $tot_4_TGL_25 -= $TGL_4_25;
                                $tot_4_TGL_8 -= $TGL_4_8;   $tot_4_TGL_17 -= $TGL_4_17;      $tot_4_TGL_26 -= $TGL_4_26;
                                $tot_4_TGL_9 -= $TGL_4_9;   $tot_4_TGL_18 -= $TGL_4_18;      $tot_4_TGL_27 -= $TGL_4_27;

                                $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                                $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                                $ada = 1;
                            }
                        }else{
                            if($ada==1){
                                /*tambah buat remain*/   
                                $objPHPExcel->setActiveSheetIndex($s)
                                            ->setCellValue('A'.$no4, $l)
                                            ->setCellValue('B'.$no4, $w)
                                            ->setCellValue('C'.$no4, $ino)
                                            ->setCellValue('D'.$no4, $inm)
                                            ->setCellValue('E'.$no4, $dtc)
                                            ->setCellValue('F'.$no4, $typ)
                                            ->setCellValue('G'.$no4, $grd)
                                            ->setCellValue('H'.$no4, $crd)
                                            ->setCellValue('I'.$no4, $q)
                                            ->setCellValue('J'.$no4, $ot)
                                            ->setCellValue('K'.$no4, $prog)
                                            ->setCellValue('L'.$no4, 'REMAIN')
                                            ->setCellValue('M'.$no4, $tot_4_total)
                                            ->setCellValue('N'.$no4, $tot_4_TGL_1)
                                            ->setCellValue('O'.$no4, $tot_4_TGL_2)
                                            ->setCellValue('P'.$no4, $tot_4_TGL_3)
                                            ->setCellValue('Q'.$no4, $tot_4_TGL_4)
                                            ->setCellValue('R'.$no4, $tot_4_TGL_5)
                                            ->setCellValue('S'.$no4, $tot_4_TGL_6)
                                            ->setCellValue('T'.$no4, $tot_4_TGL_7)
                                            ->setCellValue('U'.$no4, $tot_4_TGL_8)
                                            ->setCellValue('V'.$no4, $tot_4_TGL_9)
                                            ->setCellValue('W'.$no4, $tot_4_TGL_10)
                                            ->setCellValue('X'.$no4, $tot_4_TGL_11)
                                            ->setCellValue('Y'.$no4, $tot_4_TGL_12)
                                            ->setCellValue('Z'.$no4, $tot_4_TGL_13)
                                            ->setCellValue('AA'.$no4, $tot_4_TGL_14)
                                            ->setCellValue('AB'.$no4, $tot_4_TGL_15)
                                            ->setCellValue('AC'.$no4, $tot_4_TGL_16)
                                            ->setCellValue('AD'.$no4, $tot_4_TGL_17)
                                            ->setCellValue('AE'.$no4, $tot_4_TGL_18)
                                            ->setCellValue('AF'.$no4, $tot_4_TGL_19)
                                            ->setCellValue('AG'.$no4, $tot_4_TGL_20)
                                            ->setCellValue('AH'.$no4, $tot_4_TGL_21)
                                            ->setCellValue('AI'.$no4, $tot_4_TGL_22)
                                            ->setCellValue('AJ'.$no4, $tot_4_TGL_23)
                                            ->setCellValue('AK'.$no4, $tot_4_TGL_24)
                                            ->setCellValue('AL'.$no4, $tot_4_TGL_25)
                                            ->setCellValue('AM'.$no4, $tot_4_TGL_26)
                                            ->setCellValue('AN'.$no4, $tot_4_TGL_27)
                                            ->setCellValue('AO'.$no4, $tot_4_TGL_28)
                                            ->setCellValue('AP'.$no4, $tot_4_TGL_29)
                                            ->setCellValue('AQ'.$no4, $tot_4_TGL_30)
                                            ->setCellValue('AR'.$no4, $tot_4_TGL_31);
                                
                                if($prog == $q){
                                    $sheet->getStyle('A'.$no4.':AR'.$no4)->applyFromArray(
                                        array(
                                            'fill' => array(
                                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('rgb' => '00B050')
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                                )
                                            )
                                        )
                                    );
                                }else{
                                    $sheet->getStyle('A'.$no4.':'.$arrKolom2[$kemaren].$no4)->applyFromArray(
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

                                    $sheet->getStyle($arrKolom2[$now].$no4)->applyFromArray(
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

                                    if($arrKolom2[$lusa] != 'AR'){
                                        $sheet->getStyle($arrKolom2[$lusa].$no4.':AR'.$no4)->applyFromArray(
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
                                        $sheet->getStyle('AR'.$no4)->applyFromArray(
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
                                }

                                $objPHPExcel->getActiveSheet()->getStyle('K'.$no4.':AR'.$no4)->getNumberFormat()->setFormatCode('#,##0');
                                $objPHPExcel->getActiveSheet()->getStyle('I'.$no4)->getNumberFormat()->setFormatCode('#,##0');

                                $no4++;
                                $ada= 0;

                                $tot_4_TGL_1 = 0;   $tot_4_TGL_10 = 0;      $tot_4_TGL_19 = 0;      $tot_4_TGL_28 = 0;
                                $tot_4_TGL_2 = 0;   $tot_4_TGL_11 = 0;      $tot_4_TGL_20 = 0;      $tot_4_TGL_29 = 0;
                                $tot_4_TGL_3 = 0;   $tot_4_TGL_12 = 0;      $tot_4_TGL_21 = 0;      $tot_4_TGL_30 = 0;
                                $tot_4_TGL_4 = 0;   $tot_4_TGL_13 = 0;      $tot_4_TGL_22 = 0;      $tot_4_TGL_31 = 0;
                                $tot_4_TGL_5 = 0;   $tot_4_TGL_14 = 0;      $tot_4_TGL_23 = 0;      $tot_4_total = 0;
                                $tot_4_TGL_6 = 0;   $tot_4_TGL_15 = 0;      $tot_4_TGL_24 = 0;
                                $tot_4_TGL_7 = 0;   $tot_4_TGL_16 = 0;      $tot_4_TGL_25 = 0;
                                $tot_4_TGL_8 = 0;   $tot_4_TGL_17 = 0;      $tot_4_TGL_26 = 0;
                                $tot_4_TGL_9 = 0;   $tot_4_TGL_18 = 0;      $tot_4_TGL_27 = 0;

                                $l = '';        $ino = '';      $dtc = '';      $grd = '';
                                $w = '';        $inm = '';      $typ = '';      $crd = '';
                                $q = '';        $ot = '';       $prog = '';
                            }
                            if($sts == 'PLAN'){
                                /*PLAN*/
                                $tot_4_TGL_1 += $TGL_4_1;   $tot_4_TGL_10 += $TGL_4_10;      $tot_4_TGL_19 += $TGL_4_19;      $tot_4_TGL_28 += $TGL_4_28;
                                $tot_4_TGL_2 += $TGL_4_2;   $tot_4_TGL_11 += $TGL_4_11;      $tot_4_TGL_20 += $TGL_4_20;      $tot_4_TGL_29 += $TGL_4_29;
                                $tot_4_TGL_3 += $TGL_4_3;   $tot_4_TGL_12 += $TGL_4_12;      $tot_4_TGL_21 += $TGL_4_21;      $tot_4_TGL_30 += $TGL_4_30;
                                $tot_4_TGL_4 += $TGL_4_4;   $tot_4_TGL_13 += $TGL_4_13;      $tot_4_TGL_22 += $TGL_4_22;      $tot_4_TGL_31 += $TGL_4_31;
                                $tot_4_TGL_5 += $TGL_4_5;   $tot_4_TGL_14 += $TGL_4_14;      $tot_4_TGL_23 += $TGL_4_23;      $tot_4_total += $row4->TOTAL;
                                $tot_4_TGL_6 += $TGL_4_6;   $tot_4_TGL_15 += $TGL_4_15;      $tot_4_TGL_24 += $TGL_4_24;      
                                $tot_4_TGL_7 += $TGL_4_7;   $tot_4_TGL_16 += $TGL_4_16;      $tot_4_TGL_25 += $TGL_4_25;
                                $tot_4_TGL_8 += $TGL_4_8;   $tot_4_TGL_17 += $TGL_4_17;      $tot_4_TGL_26 += $TGL_4_26;
                                $tot_4_TGL_9 += $TGL_4_9;   $tot_4_TGL_18 += $TGL_4_18;      $tot_4_TGL_27 += $TGL_4_27;
                                $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                                $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                            }else{
                                /*OUTPUT*/
                                $tot_4_TGL_1 -= $TGL_4_1;   $tot_4_TGL_10 -= $TGL_4_10;      $tot_4_TGL_19 -= $TGL_4_19;      $tot_4_TGL_28 -= $TGL_4_28;
                                $tot_4_TGL_2 -= $TGL_4_2;   $tot_4_TGL_11 -= $TGL_4_11;      $tot_4_TGL_20 -= $TGL_4_20;      $tot_4_TGL_29 -= $TGL_4_29;
                                $tot_4_TGL_3 -= $TGL_4_3;   $tot_4_TGL_12 -= $TGL_4_12;      $tot_4_TGL_21 -= $TGL_4_21;      $tot_4_TGL_30 -= $TGL_4_30;
                                $tot_4_TGL_4 -= $TGL_4_4;   $tot_4_TGL_13 -= $TGL_4_13;      $tot_4_TGL_22 -= $TGL_4_22;      $tot_4_TGL_31 -= $TGL_4_31;
                                $tot_4_TGL_5 -= $TGL_4_5;   $tot_4_TGL_14 -= $TGL_4_14;      $tot_4_TGL_23 -= $TGL_4_23;      $tot_4_total -= $row4->TOTAL;
                                $tot_4_TGL_6 -= $TGL_4_6;   $tot_4_TGL_15 -= $TGL_4_15;      $tot_4_TGL_24 -= $TGL_4_24;      
                                $tot_4_TGL_7 -= $TGL_4_7;   $tot_4_TGL_16 -= $TGL_4_16;      $tot_4_TGL_25 -= $TGL_4_25;
                                $tot_4_TGL_8 -= $TGL_4_8;   $tot_4_TGL_17 -= $TGL_4_17;      $tot_4_TGL_26 -= $TGL_4_26;
                                $tot_4_TGL_9 -= $TGL_4_9;   $tot_4_TGL_18 -= $TGL_4_18;      $tot_4_TGL_27 -= $TGL_4_27;

                                $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                                $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                                $ada = 1;
                            }
                        }
                    }else{
                        if($wo == $row4->WORK_ORDER){
                            if($sts == 'PLAN'){
                                /*PLAN*/
                                $tot_4_TGL_1 += $TGL_4_1;   $tot_4_TGL_10 += $TGL_4_10;      $tot_4_TGL_19 += $TGL_4_19;      $tot_4_TGL_28 += $TGL_4_28;
                                $tot_4_TGL_2 += $TGL_4_2;   $tot_4_TGL_11 += $TGL_4_11;      $tot_4_TGL_20 += $TGL_4_20;      $tot_4_TGL_29 += $TGL_4_29;
                                $tot_4_TGL_3 += $TGL_4_3;   $tot_4_TGL_12 += $TGL_4_12;      $tot_4_TGL_21 += $TGL_4_21;      $tot_4_TGL_30 += $TGL_4_30;
                                $tot_4_TGL_4 += $TGL_4_4;   $tot_4_TGL_13 += $TGL_4_13;      $tot_4_TGL_22 += $TGL_4_22;      $tot_4_TGL_31 += $TGL_4_31;
                                $tot_4_TGL_5 += $TGL_4_5;   $tot_4_TGL_14 += $TGL_4_14;      $tot_4_TGL_23 += $TGL_4_23;      $tot_4_total += $row4->TOTAL;
                                $tot_4_TGL_6 += $TGL_4_6;   $tot_4_TGL_15 += $TGL_4_15;      $tot_4_TGL_24 += $TGL_4_24;      
                                $tot_4_TGL_7 += $TGL_4_7;   $tot_4_TGL_16 += $TGL_4_16;      $tot_4_TGL_25 += $TGL_4_25;
                                $tot_4_TGL_8 += $TGL_4_8;   $tot_4_TGL_17 += $TGL_4_17;      $tot_4_TGL_26 += $TGL_4_26;
                                $tot_4_TGL_9 += $TGL_4_9;   $tot_4_TGL_18 += $TGL_4_18;      $tot_4_TGL_27 += $TGL_4_27;
                                $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                                $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                            }else{
                                /*OUTPUT*/
                                $tot_4_TGL_1 -= $TGL_4_1;   $tot_4_TGL_10 -= $TGL_4_10;      $tot_4_TGL_19 -= $TGL_4_19;      $tot_4_TGL_28 -= $TGL_4_28;
                                $tot_4_TGL_2 -= $TGL_4_2;   $tot_4_TGL_11 -= $TGL_4_11;      $tot_4_TGL_20 -= $TGL_4_20;      $tot_4_TGL_29 -= $TGL_4_29;
                                $tot_4_TGL_3 -= $TGL_4_3;   $tot_4_TGL_12 -= $TGL_4_12;      $tot_4_TGL_21 -= $TGL_4_21;      $tot_4_TGL_30 -= $TGL_4_30;
                                $tot_4_TGL_4 -= $TGL_4_4;   $tot_4_TGL_13 -= $TGL_4_13;      $tot_4_TGL_22 -= $TGL_4_22;      $tot_4_TGL_31 -= $TGL_4_31;
                                $tot_4_TGL_5 -= $TGL_4_5;   $tot_4_TGL_14 -= $TGL_4_14;      $tot_4_TGL_23 -= $TGL_4_23;      $tot_4_total -= $row4->TOTAL;
                                $tot_4_TGL_6 -= $TGL_4_6;   $tot_4_TGL_15 -= $TGL_4_15;      $tot_4_TGL_24 -= $TGL_4_24;      
                                $tot_4_TGL_7 -= $TGL_4_7;   $tot_4_TGL_16 -= $TGL_4_16;      $tot_4_TGL_25 -= $TGL_4_25;
                                $tot_4_TGL_8 -= $TGL_4_8;   $tot_4_TGL_17 -= $TGL_4_17;      $tot_4_TGL_26 -= $TGL_4_26;
                                $tot_4_TGL_9 -= $TGL_4_9;   $tot_4_TGL_18 -= $TGL_4_18;      $tot_4_TGL_27 -= $TGL_4_27;

                                $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                                $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                                $ada = 1;
                            }
                        }else{
                            if($ada==1){
                                /*tambah buat remain*/   
                                $objPHPExcel->setActiveSheetIndex($s)
                                            ->setCellValue('A'.$no4, $l)
                                            ->setCellValue('B'.$no4, $w)
                                            ->setCellValue('C'.$no4, $ino)
                                            ->setCellValue('D'.$no4, $inm)
                                            ->setCellValue('E'.$no4, $dtc)
                                            ->setCellValue('F'.$no4, $typ)
                                            ->setCellValue('G'.$no4, $grd)
                                            ->setCellValue('H'.$no4, $crd)
                                            ->setCellValue('I'.$no4, $q)
                                            ->setCellValue('J'.$no4, $ot)
                                            ->setCellValue('K'.$no4, $prog)
                                            ->setCellValue('L'.$no4, 'REMAIN')
                                            ->setCellValue('M'.$no4, $tot_4_total)
                                            ->setCellValue('N'.$no4, $tot_4_TGL_1)
                                            ->setCellValue('O'.$no4, $tot_4_TGL_2)
                                            ->setCellValue('P'.$no4, $tot_4_TGL_3)
                                            ->setCellValue('Q'.$no4, $tot_4_TGL_4)
                                            ->setCellValue('R'.$no4, $tot_4_TGL_5)
                                            ->setCellValue('S'.$no4, $tot_4_TGL_6)
                                            ->setCellValue('T'.$no4, $tot_4_TGL_7)
                                            ->setCellValue('U'.$no4, $tot_4_TGL_8)
                                            ->setCellValue('V'.$no4, $tot_4_TGL_9)
                                            ->setCellValue('W'.$no4, $tot_4_TGL_10)
                                            ->setCellValue('X'.$no4, $tot_4_TGL_11)
                                            ->setCellValue('Y'.$no4, $tot_4_TGL_12)
                                            ->setCellValue('Z'.$no4, $tot_4_TGL_13)
                                            ->setCellValue('AA'.$no4, $tot_4_TGL_14)
                                            ->setCellValue('AB'.$no4, $tot_4_TGL_15)
                                            ->setCellValue('AC'.$no4, $tot_4_TGL_16)
                                            ->setCellValue('AD'.$no4, $tot_4_TGL_17)
                                            ->setCellValue('AE'.$no4, $tot_4_TGL_18)
                                            ->setCellValue('AF'.$no4, $tot_4_TGL_19)
                                            ->setCellValue('AG'.$no4, $tot_4_TGL_20)
                                            ->setCellValue('AH'.$no4, $tot_4_TGL_21)
                                            ->setCellValue('AI'.$no4, $tot_4_TGL_22)
                                            ->setCellValue('AJ'.$no4, $tot_4_TGL_23)
                                            ->setCellValue('AK'.$no4, $tot_4_TGL_24)
                                            ->setCellValue('AL'.$no4, $tot_4_TGL_25)
                                            ->setCellValue('AM'.$no4, $tot_4_TGL_26)
                                            ->setCellValue('AN'.$no4, $tot_4_TGL_27)
                                            ->setCellValue('AO'.$no4, $tot_4_TGL_28)
                                            ->setCellValue('AP'.$no4, $tot_4_TGL_29)
                                            ->setCellValue('AQ'.$no4, $tot_4_TGL_30)
                                            ->setCellValue('AR'.$no4, $tot_4_TGL_31);
                                
                                if($prog == $q){
                                    $sheet->getStyle('A'.$no4.':AR'.$no4)->applyFromArray(
                                        array(
                                            'fill' => array(
                                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                                'color' => array('rgb' => '00B050')
                                            ),
                                            'borders' => array(
                                                'allborders' => array(
                                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                                )
                                            )
                                        )
                                    );
                                }else{
                                    $sheet->getStyle('A'.$no4.':'.$arrKolom2[$kemaren].$no4)->applyFromArray(
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

                                    $sheet->getStyle($arrKolom2[$now].$no4)->applyFromArray(
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

                                    if($arrKolom2[$lusa] != 'AR'){
                                        $sheet->getStyle($arrKolom2[$lusa].$no4.':AR'.$no4)->applyFromArray(
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
                                        $sheet->getStyle('AR'.$no4)->applyFromArray(
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
                                }

                                $objPHPExcel->getActiveSheet()->getStyle('K'.$no4.':AR'.$no4)->getNumberFormat()->setFormatCode('#,##0');
                                $objPHPExcel->getActiveSheet()->getStyle('I'.$no4)->getNumberFormat()->setFormatCode('#,##0');

                                $no4++;
                                $ada= 0;

                                $tot_4_TGL_1 = 0;   $tot_4_TGL_10 = 0;      $tot_4_TGL_19 = 0;      $tot_4_TGL_28 = 0;
                                $tot_4_TGL_2 = 0;   $tot_4_TGL_11 = 0;      $tot_4_TGL_20 = 0;      $tot_4_TGL_29 = 0;
                                $tot_4_TGL_3 = 0;   $tot_4_TGL_12 = 0;      $tot_4_TGL_21 = 0;      $tot_4_TGL_30 = 0;
                                $tot_4_TGL_4 = 0;   $tot_4_TGL_13 = 0;      $tot_4_TGL_22 = 0;      $tot_4_TGL_31 = 0;
                                $tot_4_TGL_5 = 0;   $tot_4_TGL_14 = 0;      $tot_4_TGL_23 = 0;      $tot_4_total = 0;
                                $tot_4_TGL_6 = 0;   $tot_4_TGL_15 = 0;      $tot_4_TGL_24 = 0;
                                $tot_4_TGL_7 = 0;   $tot_4_TGL_16 = 0;      $tot_4_TGL_25 = 0;
                                $tot_4_TGL_8 = 0;   $tot_4_TGL_17 = 0;      $tot_4_TGL_26 = 0;
                                $tot_4_TGL_9 = 0;   $tot_4_TGL_18 = 0;      $tot_4_TGL_27 = 0;

                                $l = '';        $ino = '';      $dtc = '';      $grd = '';
                                $w = '';        $inm = '';      $typ = '';      $crd = '';
                                $q = '';        $ot = '';       $prog = '';
                            }
                            if($sts == 'PLAN'){
                                /*PLAN*/
                                $tot_4_TGL_1 += $TGL_4_1;   $tot_4_TGL_10 += $TGL_4_10;      $tot_4_TGL_19 += $TGL_4_19;      $tot_4_TGL_28 += $TGL_4_28;
                                $tot_4_TGL_2 += $TGL_4_2;   $tot_4_TGL_11 += $TGL_4_11;      $tot_4_TGL_20 += $TGL_4_20;      $tot_4_TGL_29 += $TGL_4_29;
                                $tot_4_TGL_3 += $TGL_4_3;   $tot_4_TGL_12 += $TGL_4_12;      $tot_4_TGL_21 += $TGL_4_21;      $tot_4_TGL_30 += $TGL_4_30;
                                $tot_4_TGL_4 += $TGL_4_4;   $tot_4_TGL_13 += $TGL_4_13;      $tot_4_TGL_22 += $TGL_4_22;      $tot_4_TGL_31 += $TGL_4_31;
                                $tot_4_TGL_5 += $TGL_4_5;   $tot_4_TGL_14 += $TGL_4_14;      $tot_4_TGL_23 += $TGL_4_23;      $tot_4_total += $row4->TOTAL;
                                $tot_4_TGL_6 += $TGL_4_6;   $tot_4_TGL_15 += $TGL_4_15;      $tot_4_TGL_24 += $TGL_4_24;      
                                $tot_4_TGL_7 += $TGL_4_7;   $tot_4_TGL_16 += $TGL_4_16;      $tot_4_TGL_25 += $TGL_4_25;
                                $tot_4_TGL_8 += $TGL_4_8;   $tot_4_TGL_17 += $TGL_4_17;      $tot_4_TGL_26 += $TGL_4_26;
                                $tot_4_TGL_9 += $TGL_4_9;   $tot_4_TGL_18 += $TGL_4_18;      $tot_4_TGL_27 += $TGL_4_27;
                                $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                                $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                            }else{
                                /*OUTPUT*/
                                $tot_4_TGL_1 -= $TGL_4_1;   $tot_4_TGL_10 -= $TGL_4_10;      $tot_4_TGL_19 -= $TGL_4_19;      $tot_4_TGL_28 -= $TGL_4_28;
                                $tot_4_TGL_2 -= $TGL_4_2;   $tot_4_TGL_11 -= $TGL_4_11;      $tot_4_TGL_20 -= $TGL_4_20;      $tot_4_TGL_29 -= $TGL_4_29;
                                $tot_4_TGL_3 -= $TGL_4_3;   $tot_4_TGL_12 -= $TGL_4_12;      $tot_4_TGL_21 -= $TGL_4_21;      $tot_4_TGL_30 -= $TGL_4_30;
                                $tot_4_TGL_4 -= $TGL_4_4;   $tot_4_TGL_13 -= $TGL_4_13;      $tot_4_TGL_22 -= $TGL_4_22;      $tot_4_TGL_31 -= $TGL_4_31;
                                $tot_4_TGL_5 -= $TGL_4_5;   $tot_4_TGL_14 -= $TGL_4_14;      $tot_4_TGL_23 -= $TGL_4_23;      $tot_4_total -= $row4->TOTAL;
                                $tot_4_TGL_6 -= $TGL_4_6;   $tot_4_TGL_15 -= $TGL_4_15;      $tot_4_TGL_24 -= $TGL_4_24;      
                                $tot_4_TGL_7 -= $TGL_4_7;   $tot_4_TGL_16 -= $TGL_4_16;      $tot_4_TGL_25 -= $TGL_4_25;
                                $tot_4_TGL_8 -= $TGL_4_8;   $tot_4_TGL_17 -= $TGL_4_17;      $tot_4_TGL_26 -= $TGL_4_26;
                                $tot_4_TGL_9 -= $TGL_4_9;   $tot_4_TGL_18 -= $TGL_4_18;      $tot_4_TGL_27 -= $TGL_4_27;

                                $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                                $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;
                                $ada = 1;
                            }
                        }
                    }
                }else{
                    if($ada==1){
                        /*tambah buat remain*/   
                        $objPHPExcel->setActiveSheetIndex($s)
                                    ->setCellValue('A'.$no4, $l)
                                    ->setCellValue('B'.$no4, $w)
                                    ->setCellValue('C'.$no4, $ino)
                                    ->setCellValue('D'.$no4, $inm)
                                    ->setCellValue('E'.$no4, $dtc)
                                    ->setCellValue('F'.$no4, $typ)
                                    ->setCellValue('G'.$no4, $grd)
                                    ->setCellValue('H'.$no4, $crd)
                                    ->setCellValue('I'.$no4, $q)
                                    ->setCellValue('J'.$no4, $ot)
                                    ->setCellValue('K'.$no4, $prog)
                                    ->setCellValue('L'.$no4, 'REMAIN')
                                    ->setCellValue('M'.$no4, $tot_4_total)
                                    ->setCellValue('N'.$no4, $tot_4_TGL_1)
                                    ->setCellValue('O'.$no4, $tot_4_TGL_2)
                                    ->setCellValue('P'.$no4, $tot_4_TGL_3)
                                    ->setCellValue('Q'.$no4, $tot_4_TGL_4)
                                    ->setCellValue('R'.$no4, $tot_4_TGL_5)
                                    ->setCellValue('S'.$no4, $tot_4_TGL_6)
                                    ->setCellValue('T'.$no4, $tot_4_TGL_7)
                                    ->setCellValue('U'.$no4, $tot_4_TGL_8)
                                    ->setCellValue('V'.$no4, $tot_4_TGL_9)
                                    ->setCellValue('W'.$no4, $tot_4_TGL_10)
                                    ->setCellValue('X'.$no4, $tot_4_TGL_11)
                                    ->setCellValue('Y'.$no4, $tot_4_TGL_12)
                                    ->setCellValue('Z'.$no4, $tot_4_TGL_13)
                                    ->setCellValue('AA'.$no4, $tot_4_TGL_14)
                                    ->setCellValue('AB'.$no4, $tot_4_TGL_15)
                                    ->setCellValue('AC'.$no4, $tot_4_TGL_16)
                                    ->setCellValue('AD'.$no4, $tot_4_TGL_17)
                                    ->setCellValue('AE'.$no4, $tot_4_TGL_18)
                                    ->setCellValue('AF'.$no4, $tot_4_TGL_19)
                                    ->setCellValue('AG'.$no4, $tot_4_TGL_20)
                                    ->setCellValue('AH'.$no4, $tot_4_TGL_21)
                                    ->setCellValue('AI'.$no4, $tot_4_TGL_22)
                                    ->setCellValue('AJ'.$no4, $tot_4_TGL_23)
                                    ->setCellValue('AK'.$no4, $tot_4_TGL_24)
                                    ->setCellValue('AL'.$no4, $tot_4_TGL_25)
                                    ->setCellValue('AM'.$no4, $tot_4_TGL_26)
                                    ->setCellValue('AN'.$no4, $tot_4_TGL_27)
                                    ->setCellValue('AO'.$no4, $tot_4_TGL_28)
                                    ->setCellValue('AP'.$no4, $tot_4_TGL_29)
                                    ->setCellValue('AQ'.$no4, $tot_4_TGL_30)
                                    ->setCellValue('AR'.$no4, $tot_4_TGL_31);
                        
                        if($prog == $q){
                            $sheet->getStyle('A'.$no4.':AR'.$no4)->applyFromArray(
                                array(
                                    'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'color' => array('rgb' => '00B050')
                                    ),
                                    'borders' => array(
                                        'allborders' => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THIN
                                        )
                                    )
                                )
                            );
                        }else{
                            $sheet->getStyle('A'.$no4.':'.$arrKolom2[$kemaren].$no4)->applyFromArray(
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

                            $sheet->getStyle($arrKolom2[$now].$no4)->applyFromArray(
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

                            if($arrKolom2[$lusa] != 'AR'){
                                $sheet->getStyle($arrKolom2[$lusa].$no4.':AR'.$no4)->applyFromArray(
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
                                $sheet->getStyle('AR'.$no4)->applyFromArray(
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
                        }

                        $objPHPExcel->getActiveSheet()->getStyle('K'.$no4.':AR'.$no4)->getNumberFormat()->setFormatCode('#,##0');
                        $objPHPExcel->getActiveSheet()->getStyle('I'.$no4)->getNumberFormat()->setFormatCode('#,##0');

                        $no4++;
                        $ada= 0;

                        $tot_4_TGL_1 = 0;   $tot_4_TGL_10 = 0;      $tot_4_TGL_19 = 0;      $tot_4_TGL_28 = 0;
                        $tot_4_TGL_2 = 0;   $tot_4_TGL_11 = 0;      $tot_4_TGL_20 = 0;      $tot_4_TGL_29 = 0;
                        $tot_4_TGL_3 = 0;   $tot_4_TGL_12 = 0;      $tot_4_TGL_21 = 0;      $tot_4_TGL_30 = 0;
                        $tot_4_TGL_4 = 0;   $tot_4_TGL_13 = 0;      $tot_4_TGL_22 = 0;      $tot_4_TGL_31 = 0;
                        $tot_4_TGL_5 = 0;   $tot_4_TGL_14 = 0;      $tot_4_TGL_23 = 0;      $tot_4_total = 0;
                        $tot_4_TGL_6 = 0;   $tot_4_TGL_15 = 0;      $tot_4_TGL_24 = 0;
                        $tot_4_TGL_7 = 0;   $tot_4_TGL_16 = 0;      $tot_4_TGL_25 = 0;
                        $tot_4_TGL_8 = 0;   $tot_4_TGL_17 = 0;      $tot_4_TGL_26 = 0;
                        $tot_4_TGL_9 = 0;   $tot_4_TGL_18 = 0;      $tot_4_TGL_27 = 0;

                        $l = '';        $ino = '';      $dtc = '';      $grd = '';
                        $w = '';        $inm = '';      $typ = '';      $crd = '';
                        $q = '';        $ot = '';       $prog = '';
                    }

                    $tot_4_TGL_1 += $TGL_4_1;   $tot_4_TGL_10 += $TGL_4_10;      $tot_4_TGL_19 += $TGL_4_19;      $tot_4_TGL_28 += $TGL_4_28;
                    $tot_4_TGL_2 += $TGL_4_2;   $tot_4_TGL_11 += $TGL_4_11;      $tot_4_TGL_20 += $TGL_4_20;      $tot_4_TGL_29 += $TGL_4_29;
                    $tot_4_TGL_3 += $TGL_4_3;   $tot_4_TGL_12 += $TGL_4_12;      $tot_4_TGL_21 += $TGL_4_21;      $tot_4_TGL_30 += $TGL_4_30;
                    $tot_4_TGL_4 += $TGL_4_4;   $tot_4_TGL_13 += $TGL_4_13;      $tot_4_TGL_22 += $TGL_4_22;      $tot_4_TGL_31 += $TGL_4_31;
                    $tot_4_TGL_5 += $TGL_4_5;   $tot_4_TGL_14 += $TGL_4_14;      $tot_4_TGL_23 += $TGL_4_23;      $tot_4_total += $row4->TOTAL;
                    $tot_4_TGL_6 += $TGL_4_6;   $tot_4_TGL_15 += $TGL_4_15;      $tot_4_TGL_24 += $TGL_4_24;      
                    $tot_4_TGL_7 += $TGL_4_7;   $tot_4_TGL_16 += $TGL_4_16;      $tot_4_TGL_25 += $TGL_4_25;
                    $tot_4_TGL_8 += $TGL_4_8;   $tot_4_TGL_17 += $TGL_4_17;      $tot_4_TGL_26 += $TGL_4_26;
                    $tot_4_TGL_9 += $TGL_4_9;   $tot_4_TGL_18 += $TGL_4_18;      $tot_4_TGL_27 += $TGL_4_27;
                    $l = $row4->LABEL_TYPE;     $ino = $row4->ITEM_NO;          $dtc = $row4->DATE_CODE;           $grd = $row4->GRADE_CODE;
                    $w = $row4->WORK_ORDER;     $inm = $row4->ITEM_NAME;        $typ = $row4->GROUPINGLABEL;       $crd = $row4->CR_DATE;
                    $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->GRADE;

                    /*1. buat satu row header*/
                    $objPHPExcel->setActiveSheetIndex($s)
                                ->setCellValue('A'.$no4, $row4->LABEL_TYPE)
                                ->setCellValue('B'.$no4, '')
                                ->setCellValue('C'.$no4, '')
                                ->setCellValue('D'.$no4, '')
                                ->setCellValue('E'.$no4, '')
                                ->setCellValue('F'.$no4, '')
                                ->setCellValue('G'.$no4, '')
                                ->setCellValue('H'.$no4, '')
                                ->setCellValue('I'.$no4, '')
                                ->setCellValue('J'.$no4, '')
                                ->setCellValue('K'.$no4, '')
                                ->setCellValue('L'.$no4, '')
                                ->setCellValue('M'.$no4, '')
                                ->setCellValue('N'.$no4, '')
                                ->setCellValue('O'.$no4, '')
                                ->setCellValue('P'.$no4, '')
                                ->setCellValue('Q'.$no4, '')
                                ->setCellValue('R'.$no4, '')
                                ->setCellValue('S'.$no4, '')
                                ->setCellValue('T'.$no4, '')
                                ->setCellValue('U'.$no4, '')
                                ->setCellValue('V'.$no4, '')
                                ->setCellValue('W'.$no4, '')
                                ->setCellValue('X'.$no4, '')
                                ->setCellValue('Y'.$no4, '')
                                ->setCellValue('Z'.$no4, '')
                                ->setCellValue('AA'.$no4, '')
                                ->setCellValue('AB'.$no4, '')
                                ->setCellValue('AC'.$no4, '')
                                ->setCellValue('AD'.$no4, '')
                                ->setCellValue('AE'.$no4, '')
                                ->setCellValue('AF'.$no4, '')
                                ->setCellValue('AG'.$no4, '')
                                ->setCellValue('AH'.$no4, '')
                                ->setCellValue('AI'.$no4, '')
                                ->setCellValue('AJ'.$no4, '')
                                ->setCellValue('AK'.$no4, '')
                                ->setCellValue('AL'.$no4, '')
                                ->setCellValue('AM'.$no4, '')
                                ->setCellValue('AN'.$no4, '')
                                ->setCellValue('AO'.$no4, '')
                                ->setCellValue('AP'.$no4, '')
                                ->setCellValue('AQ'.$no4, '')
                                ->setCellValue('AR'.$no4, '');

                    $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A'.$no4.':D'.$no4);
                    $sheet->getStyle('A'.$no4.':AR'.$no4)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'FFFF00')
                            ),
                            'borders' => array(
                                'allborders' => array(
                                    'style' => PHPExcel_Style_Border::BORDER_THIN
                                )
                            ),
                            'font'  => array(
                                'bold'  => true,
                                'size'  => 14
                            )
                        )
                    );
                    $no4++;
                }
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no4, $row4->LABEL_TYPE)
                        ->setCellValue('B'.$no4, $row4->WORK_ORDER)
                        ->setCellValue('C'.$no4, $row4->ITEM_NO)
                        ->setCellValue('D'.$no4, $row4->ITEM_NAME)
                        ->setCellValue('E'.$no4, $row4->DATE_CODE)
                        ->setCellValue('F'.$no4, $row4->GROUPINGLABEL)
                        ->setCellValue('G'.$no4, $row4->GRADE_CODE)
                        ->setCellValue('H'.$no4, $row4->CR_DATE)
                        ->setCellValue('I'.$no4, $row4->QTY)
                        ->setCellValue('J'.$no4, $row4->OPERATEION_TIME)
                        ->setCellValue('K'.$no4, $row4->GRADE)
                        ->setCellValue('L'.$no4, $sts)
                        ->setCellValue('M'.$no4, $row4->TOTAL)
                        ->setCellValue('N'.$no4, $TGL_4_1)
                        ->setCellValue('O'.$no4, $TGL_4_2)
                        ->setCellValue('P'.$no4, $TGL_4_3)
                        ->setCellValue('Q'.$no4, $TGL_4_4)
                        ->setCellValue('R'.$no4, $TGL_4_5)
                        ->setCellValue('S'.$no4, $TGL_4_6)
                        ->setCellValue('T'.$no4, $TGL_4_7)
                        ->setCellValue('U'.$no4, $TGL_4_8)
                        ->setCellValue('V'.$no4, $TGL_4_9)
                        ->setCellValue('W'.$no4, $TGL_4_10)
                        ->setCellValue('X'.$no4, $TGL_4_11)
                        ->setCellValue('Y'.$no4, $TGL_4_12)
                        ->setCellValue('Z'.$no4, $TGL_4_13)
                        ->setCellValue('AA'.$no4, $TGL_4_14)
                        ->setCellValue('AB'.$no4, $TGL_4_15)
                        ->setCellValue('AC'.$no4, $TGL_4_16)
                        ->setCellValue('AD'.$no4, $TGL_4_17)
                        ->setCellValue('AE'.$no4, $TGL_4_18)
                        ->setCellValue('AF'.$no4, $TGL_4_19)
                        ->setCellValue('AG'.$no4, $TGL_4_20)
                        ->setCellValue('AH'.$no4, $TGL_4_21)
                        ->setCellValue('AI'.$no4, $TGL_4_22)
                        ->setCellValue('AJ'.$no4, $TGL_4_23)
                        ->setCellValue('AK'.$no4, $TGL_4_24)
                        ->setCellValue('AL'.$no4, $TGL_4_25)
                        ->setCellValue('AM'.$no4, $TGL_4_26)
                        ->setCellValue('AN'.$no4, $TGL_4_27)
                        ->setCellValue('AO'.$no4, $TGL_4_28)
                        ->setCellValue('AP'.$no4, $TGL_4_29)
                        ->setCellValue('AQ'.$no4, $TGL_4_30)
                        ->setCellValue('AR'.$no4, $TGL_4_31);

            if($row4->GRADE == '' OR is_null($row4->GRADE)){
                $ada = 0;
                $tot_4_TGL_1 = 0;   $tot_4_TGL_10 = 0;      $tot_4_TGL_19 = 0;      $tot_4_TGL_28 = 0;
                $tot_4_TGL_2 = 0;   $tot_4_TGL_11 = 0;      $tot_4_TGL_20 = 0;      $tot_4_TGL_29 = 0;
                $tot_4_TGL_3 = 0;   $tot_4_TGL_12 = 0;      $tot_4_TGL_21 = 0;      $tot_4_TGL_30 = 0;
                $tot_4_TGL_4 = 0;   $tot_4_TGL_13 = 0;      $tot_4_TGL_22 = 0;      $tot_4_TGL_31 = 0;
                $tot_4_TGL_5 = 0;   $tot_4_TGL_14 = 0;      $tot_4_TGL_23 = 0;      $tot_4_total = 0;
                $tot_4_TGL_6 = 0;   $tot_4_TGL_15 = 0;      $tot_4_TGL_24 = 0;
                $tot_4_TGL_7 = 0;   $tot_4_TGL_16 = 0;      $tot_4_TGL_25 = 0;
                $tot_4_TGL_8 = 0;   $tot_4_TGL_17 = 0;      $tot_4_TGL_26 = 0;
                $tot_4_TGL_9 = 0;   $tot_4_TGL_18 = 0;      $tot_4_TGL_27 = 0;


                $sheet->getStyle('A'.$no4.':AR'.$no4)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => 'FF0000')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );
            }elseif($row4->QTY == $row4->GRADE){
                $sheet->getStyle('A'.$no4.':AR'.$no4)->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '00B050')
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    )
                );
            }else{
                if($sts == 'PLAN'){

                    $sheet->getStyle('A'.$no4.':'.$arrKolom2[$kemaren].$no4)->applyFromArray(
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

                    $sheet->getStyle($arrKolom2[$now].$no4)->applyFromArray(
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

                    if($arrKolom2[$lusa] != 'AR'){
                        $sheet->getStyle($arrKolom2[$lusa].$no4.':AR'.$no4)->applyFromArray(
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
                        $sheet->getStyle('AR'.$no4)->applyFromArray(
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

                }else{
                    
                    $sheet->getStyle('A'.$no4.':'.$arrKolom2[$kemaren].$no4)->applyFromArray(
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

                    $sheet->getStyle($arrKolom2[$now].$no4)->applyFromArray(
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

                    if($arrKolom2[$lusa] != 'AR'){
                        $sheet->getStyle($arrKolom2[$lusa].$no4.':AR'.$no4)->applyFromArray(
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
                        $sheet->getStyle('AR'.$no4)->applyFromArray(
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
                }
            }
            $objPHPExcel->getActiveSheet()->getStyle('K'.$no4.':AR'.$no4)->getNumberFormat()->setFormatCode('#,##0');
            $objPHPExcel->getActiveSheet()->getStyle('I'.$no4)->getNumberFormat()->setFormatCode('#,##0');

            $wo = $row4->WORK_ORDER;
            $lbl = $row4->LABEL_TYPE;
            $no4++;
        }
        $objPHPExcel->getActiveSheet()->getStyle('K'.$no4.':AR'.$no4)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->getStyle('I'.$no4)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->setTitle('COMPARATION');
    }elseif ($Arr_sheet[$s] == "DELAY ORDER"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'BATTERY TYPE')
                    ->setCellValue('B1', 'PACKAGING GROUPING')
                    ->setCellValue('C1', 'PACKAGING TYPE')
                    ->setCellValue('D1', 'WO NO.')
                    ->setCellValue('E1', 'ITEM NO.')
                    ->setCellValue('F1', 'ITEM NAME')
                    ->setCellValue('G1', 'CR DATE')
                    ->setCellValue('H1', 'TOTAL ORDER')
                    ->setCellValue('I1', 'PLAN')
                    ->setCellValue('J1', 'ACTUAL')
                    ->setCellValue('K1', 'DELAY QTY')
                    ->setCellValue('L1', 'REASON');

        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet->getStyle('A1:L1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:L1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:L1')->applyFromArray(
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

        $no5=2;

        $tot_order = 0;         $tot_act = 0;
        $tot_plan = 0;          $tot_delay = 0;

        while($row5 = sqlsrv_fetch_object($data5)){
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no5, $row5->BATT_TYPE)
                        ->setCellValue('B'.$no5, $row5->LABEL_TYPE_NAME)
                        ->setCellValue('C'.$no5, $row5->PACKAGE_TYPE)
                        ->setCellValue('D'.$no5, $row5->WORK_ORDER)
                        ->setCellValue('E'.$no5, $row5->ITEM_NO)
                        ->setCellValue('F'.$no5, $row5->DESCRIPTION)
                        ->setCellValue('G'.$no5, $row5->CR_DATE)
                        ->setCellValue('H'.$no5, $row5->QTY)
                        ->setCellValue('I'.$no5, $row5->PLAN_QTY)
                        ->setCellValue('J'.$no5, $row5->ACTUALQTY)
                        ->setCellValue('K'.$no5, $row5->DELAYQTY)
                        ->setCellValue('L'.$no5, '');
            if($no5 % 2 == 0){
                $sheet->getStyle('A'.$no5.':L'.$no5)->applyFromArray(
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
                $sheet->getStyle('A'.$no5.':L'.$no5)->applyFromArray(
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
            $objPHPExcel->getActiveSheet()->getStyle('H'.$no5.':K'.$no5)->getNumberFormat()->setFormatCode('#,##0');
            $tot_order += $row5->QTY;               $tot_act += $row5->ACTUALQTY;
            $tot_plan += $row5->PLAN_QTY;           $tot_delay += $row5->DELAYQTY;
            $no5++;
        }

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no5, 'TOTAL')
                    ->setCellValue('H'.$no5, $tot_order)
                    ->setCellValue('I'.$no5, $tot_plan)
                    ->setCellValue('J'.$no5, $tot_act)
                    ->setCellValue('K'.$no5, $tot_delay)
                    ->setCellValue('L'.$no5, '');

        $sheet->getStyle('A'.$no5.':G'.$no5)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A'.$no5.':L'.$no5)->applyFromArray(
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

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A'.$no5.':G'.$no5);
        $objPHPExcel->getActiveSheet()->getStyle('H'.$no5.':K'.$no5)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->setTitle('DELAY ORDER');
    }elseif ($Arr_sheet[$s] == "SUMMARY"){
        $objWorkSheet = $objPHPExcel->createSheet();

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', '1. Progress of delivery for packaging');
        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }
        
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $sheet->getStyle('A1')->applyFromArray(
            array(
                'font'  => array(
                    'bold'  => true,
                    'size'  => 14,
                    'name'  => 'Verdana'
                )
            )
        );

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A2', '1-1. Gap of Accumulation');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A2:D2');
        $sheet->getStyle('A2')->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '00B050')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => true,
                    'size'  => 12,
                    'name'  => 'Verdana'
                )
            )
        );

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A3', 'BATTERY TYPE')
                    ->setCellValue('B3', 'ACCUMULATION');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('B3:D3');
        foreach(range('A','D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->getStyle('A3:D3')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A3:D3')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A3:D3')->applyFromArray(
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
                'font'  => array(
                    'bold'  => true
                )
            )
        );
        
        $no6 = 4;       $accum = 0;
        while ($row61 = sqlsrv_fetch_object($data61)){
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no6, $row61->BATERY_TYPE)
                        ->setCellValue('B'.$no6, $row61->ACCUMULATION);
            $objPHPExcel->setActiveSheetIndex($s)->mergeCells('B'.$no6.':D'.$no6);

            $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
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

            if(intval($row61->ACCUMULATION) < 0){
                $sheet->getStyle('B'.$no6)->applyFromArray(
                    array(
                        'font'  => array(
                            'color' => array('rgb' => 'FF002A')
                        )
                    )
                );
            }

            $objPHPExcel->getActiveSheet()->getStyle('B'.$no6)->getNumberFormat()->setFormatCode('#,##0');
            $accum += $row61->ACCUMULATION;
            $no6++;
        }

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no6, 'TOTAL GAP')
                    ->setCellValue('B'.$no6, $accum);
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('B'.$no6.':D'.$no6);

        $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E2EFDA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => true
                )
            )
        );

        if(intval($accum) < 0){
            $sheet->getStyle('B'.$no6)->applyFromArray(
                array(
                    'font'  => array(
                        'color' => array('rgb' => 'FF002A')
                    )
                )
            );
        }

        $objPHPExcel->getActiveSheet()->getStyle('B'.$no6)->getNumberFormat()->setFormatCode('#,##0');

        $no6+=2;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no6, 'GROUP TYPE')
                    ->setCellValue('B'.$no6, 'PLAN')
                    ->setCellValue('C'.$no6, 'OUTPUT')
                    ->setCellValue('D'.$no6, 'GAP');
        $sheet->getStyle('A'.$no6.':D'.$no6)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A'.$no6.':D'.$no6)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
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
                'font'  => array(
                    'bold'  => true
                )
            )
        );
        $no6++;
        $p = 0;     $o=0;       $g=0;
        while ($row62 = sqlsrv_fetch_object($data62)){
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no6, $row62->LABEL_TYPE)
                        ->setCellValue('B'.$no6, $row62->PLN)
                        ->setCellValue('C'.$no6, $row62->OUTPUT)
                        ->setCellValue('D'.$no6, $row62->ACCUMULATION);

            $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
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

            if(intval($row62->ACCUMULATION) < 0){
                $sheet->getStyle('D'.$no6)->applyFromArray(
                    array(
                        'font'  => array(
                            'color' => array('rgb' => 'FF002A')
                        )
                    )
                );
            }

            $objPHPExcel->getActiveSheet()->getStyle('B'.$no6.':D'.$no6)->getNumberFormat()->setFormatCode('#,##0');
            $p += $row62->PLN;      $o += $row62->OUTPUT;   $g += $row62->ACCUMULATION;
            $no6++;
        }

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no6, 'TOTAL GAP')
                    ->setCellValue('B'.$no6, $p)
                    ->setCellValue('C'.$no6, $o)
                    ->setCellValue('D'.$no6, $g);

        $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
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
                'font'  => array(
                    'bold'  => true
                )
            )
        );

        if(intval($g) < 0){
            $sheet->getStyle('D'.$no6)->applyFromArray(
                array(
                    'font'  => array(
                        'color' => array('rgb' => 'FF002A')
                    )
                )
            );
        }

        $objPHPExcel->getActiveSheet()->getStyle('B'.$no6.':D'.$no6)->getNumberFormat()->setFormatCode('#,##0');
        $no6+=2;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no6, '1-2. Output Result Yesterday');
        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A'.$no6.':D'.$no6);
        $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => '00B050')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => true,
                    'size'  => 12,
                    'name'  => 'Verdana'
                )
            )
        );

        $no6++;

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no6, 'GROUP')
                    ->setCellValue('B'.$no6, 'PLAN')
                    ->setCellValue('C'.$no6, 'OUTPUT')
                    ->setCellValue('D'.$no6, 'GAP');
        $sheet->getStyle('A'.$no6.':D'.$no6)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A'.$no6.':D'.$no6)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
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
                'font'  => array(
                    'bold'  => true
                )
            )
        );
        $no6++;
        $p63 = 0;     $o63=0;       $g63=0;
        while ($row63 = sqlsrv_fetch_object($data63)){
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no6, $row63->LABEL_TYPE)
                        ->setCellValue('B'.$no6, $row63->PLN)
                        ->setCellValue('C'.$no6, $row63->OUTPUT)
                        ->setCellValue('D'.$no6, $row63->ACCUMULATION);
            $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
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

            if(intval($row63->ACCUMULATION) < 0){
                $sheet->getStyle('D'.$no6)->applyFromArray(
                    array(
                        'font'  => array(
                            'color' => array('rgb' => 'FF002A')
                        )
                    )
                );
            }
            $objPHPExcel->getActiveSheet()->getStyle('B'.$no6.':D'.$no6)->getNumberFormat()->setFormatCode('#,##0');
            $p63 += $row63->PLN;      $o63 += $row63->OUTPUT;   $g63 += $row63->ACCUMULATION;
            $no6++;
        }

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no6, 'TOTAL GAP')
                    ->setCellValue('B'.$no6, $p63)
                    ->setCellValue('C'.$no6, $o63)
                    ->setCellValue('D'.$no6, $g63);
        $sheet->getStyle('A'.$no6.':D'.$no6)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'E2EFDA')
                ),
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
                'font'  => array(
                    'bold'  => true
                )
            )
        );

        if(intval($g63) < 0){
            $sheet->getStyle('D'.$no6)->applyFromArray(
                array(
                    'font'  => array(
                        'color' => array('rgb' => 'FF002A')
                    )
                )
            );
        }

        $objPHPExcel->getActiveSheet()->getStyle('B'.$no6.':D'.$no6)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->setTitle('SUMMARY');
    }elseif ($Arr_sheet[$s] == "TODAY ORDER"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'WO NO.')
                    ->setCellValue('B1', 'CR DATE')
                    ->setCellValue('C1', 'ITEM NO.')
                    ->setCellValue('D1', 'DESCRIPTION')
                    ->setCellValue('E1', 'LABEL TYPE')
                    ->setCellValue('F1', 'BATTERY TYPE')
                    ->setCellValue('G1', 'PLAN')
                    ->setCellValue('H1', 'QTY');

        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet->getStyle('A1:H1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:H1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:H1')->applyFromArray(
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

        $no7=2;     $tot_order7 = 0;    $tot_plan7 = 0; 

        while($row7 = sqlsrv_fetch_object($data7)){
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no7, $row7->WORK_ORDER)
                        ->setCellValue('B'.$no7, $row7->CR_DATE)
                        ->setCellValue('C'.$no7, $row7->ITEM_NO)
                        ->setCellValue('D'.$no7, $row7->DESCRIPTION)
                        ->setCellValue('E'.$no7, $row7->LABEL_TYPE_NAME)
                        ->setCellValue('F'.$no7, $row7->BATT_TYPE)
                        ->setCellValue('G'.$no7, $row7->PLAN_QTY)
                        ->setCellValue('H'.$no7, $row7->QTY);

            if($no7 % 2 == 0){
                $sheet->getStyle('A'.$no7.':H'.$no7)->applyFromArray(
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
                $sheet->getStyle('A'.$no7.':H'.$no7)->applyFromArray(
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

            $objPHPExcel->getActiveSheet()->getStyle('G'.$no7.':H'.$no7)->getNumberFormat()->setFormatCode('#,##0');
            
            $tot_order7 += $row7->QTY;
            $tot_plan7 += $row7->PLAN_QTY;
            $no7++;
        }

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no7, 'TOTAL')
                    ->setCellValue('G'.$no7, $tot_plan7)
                    ->setCellValue('H'.$no7, $tot_order7);

        $sheet->getStyle('A'.$no7.':F'.$no7)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A'.$no7.':H'.$no7)->applyFromArray(
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

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A'.$no7.':F'.$no7);
        $objPHPExcel->getActiveSheet()->getStyle('G'.$no7.':H'.$no7)->getNumberFormat()->setFormatCode('#,##0');
        $objPHPExcel->getActiveSheet()->setTitle('TODAY ORDER');
    }elseif ($Arr_sheet[$s] == "MOVEUP ORDER"){
        $objWorkSheet = $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'BATTERY TYPE')
                    ->setCellValue('B1', 'PACKAGING GROUPING')
                    ->setCellValue('C1', 'PACKAGING TYPE')
                    ->setCellValue('D1', 'WO NO.')
                    ->setCellValue('E1', 'ITEM NO.')
                    ->setCellValue('F1', 'ITEM NAME')
                    ->setCellValue('G1', 'CR DATE')
                    ->setCellValue('H1', 'TOTAL ORDER')
                    ->setCellValue('I1', 'PLAN')
                    ->setCellValue('J1', 'ACTUAL')
                    ->setCellValue('K1', 'MOVEUP QTY');

        $sheet = $objPHPExcel->getActiveSheet();

        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

            $sheet = $objPHPExcel->getActiveSheet();
            $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(true);
            /** @var PHPExcel_Cell $cell */
            foreach ($cellIterator as $cell) {
                $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
            }
        }

        $sheet->getStyle('A1:K1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:K1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:K1')->applyFromArray(
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

        $no51=2;

        $tot_order51 = 0;         $tot_act51 = 0;
        $tot_plan51 = 0;          $tot_delay51 = 0;

        while($row51 = sqlsrv_fetch_object($data51)){
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no51, $row51->BATT_TYPE)
                        ->setCellValue('B'.$no51, $row51->LABEL_TYPE_NAME)
                        ->setCellValue('C'.$no51, $row51->PACKAGE_TYPE)
                        ->setCellValue('D'.$no51, $row51->WORK_ORDER)
                        ->setCellValue('E'.$no51, $row51->ITEM_NO)
                        ->setCellValue('F'.$no51, $row51->DESCRIPTION)
                        ->setCellValue('G'.$no51, $row51->CR_DATE)
                        ->setCellValue('H'.$no51, $row51->QTY)
                        ->setCellValue('I'.$no51, $row51->PLAN_QTY)
                        ->setCellValue('J'.$no51, $row51->ACTUALQTY)
                        ->setCellValue('K'.$no51, $row51->MOVEUPQTY);
            if($no51 % 2 == 0){
                $sheet->getStyle('A'.$no51.':K'.$no51)->applyFromArray(
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
                $sheet->getStyle('A'.$no51.':K'.$no51)->applyFromArray(
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
            $objPHPExcel->getActiveSheet()->getStyle('H'.$no51.':K'.$no51)->getNumberFormat()->setFormatCode('#,##0');
            $tot_order51 += $row51->QTY;               $tot_act51 += $row51->ACTUALQTY;
            $tot_plan51 += $row51->PLAN_QTY;           $tot_delay51 += $row51->MOVEUPQTY;
            $no51++;
        }

        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A'.$no51, 'TOTAL')
                    ->setCellValue('H'.$no51, $tot_order51)
                    ->setCellValue('I'.$no51, $tot_plan51)
                    ->setCellValue('J'.$no51, $tot_act51)
                    ->setCellValue('K'.$no51, $tot_delay51);

        $sheet->getStyle('A'.$no51.':G'.$no51)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A'.$no51.':K'.$no51)->applyFromArray(
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

        $objPHPExcel->setActiveSheetIndex($s)->mergeCells('A'.$no51.':G'.$no51);
        $objPHPExcel->getActiveSheet()->getStyle('H'.$no51.':K'.$no51)->getNumberFormat()->setFormatCode('#,##0');

        $objPHPExcel->getActiveSheet($s)->setTitle('MOVEUP ORDER');
    }
    $s++;
}

$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="finishing_report.xls"');
$objWriter->save('php://output');
?>

