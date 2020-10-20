<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
require_once '../class/phpexcel/PHPExcel.php';
include("../connect/conn.php");

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
    $bln = strtoupper(date('F'));
}

$arrKolom = array('1' => 'E','2' => 'F','3' => 'G','4' => 'H','5' => 'I','6' => 'J','7' => 'K','8' => 'L','9' => 'M','10' => 'N',
                  '11' => 'O','12' => 'P','13' => 'Q','14' => 'R','15' => 'S','16' => 'T','17' => 'U','18' => 'V','19' => 'W','20' => 'X',
                  '21' => 'Y','22' => 'Z','23' => 'AA','24' => 'AB','25' => 'AC','26' => 'AD','27' => 'AE','28' => 'AF','29' => 'AG','30' => 'AH',
                  '31' => 'AI', '32' => 'AJ');

$arrKolom2 = array('1' => 'N', '2' => 'O',  '3' => 'P',  '4' => 'Q',  '5' => 'R',  '6' => 'S',  '7' => 'T',  '8' => 'U',  '9' => 'V',  '10' => 'W', 
                   '11' => 'X', '12' => 'Y',  '13' => 'Z',  '14' => 'AA', '15' => 'AB', '16' => 'AC', '17' => 'AD', '18' => 'AE', '19' => 'AF', '20' => 'AG',
                   '21' => 'AH', '22' => 'AI', '23' => 'AJ', '24' => 'AK', '25' => 'AL', '26' => 'AM', '27' => 'AN', '28' => 'AO', '29' => 'AP', '30' => 'AQ',
                   '31' => 'AR', '32' => 'AS');

$qry = "select i.grade_code,label_type,GroupingLabel,work_order,z.item_no,item_name,
date_code,packaging_type,batery_type,tot,grade,cast(cr_date as varchar(10)) as cr_date,operateion_time,qty,Bulan,Total,STAT,
Satu,dua,Tiga,empat,lima,enam,tujuh,delapan,sembilan,sepuluh,sebelas,duabelas,tigabelas,empatbelas,limabelas,
enambelas,tujuhbelas,delapanbelas,sembilanbelas,duapuluh,duapuluhsatu,DuaPuluhDua,DuaPuluhTiga,DuaPuluhEmpat,DuaPuluhLima,
DuaPuluhEnam,DuaPuluhTujuh,DuaPuluhDelapan,DuaPuluhSembilan,TigaPuluh,TigaPuluhSatu
from zvw_comparison_labelmps z
inner join (select item.grade_code,item_no from item )i on z.item_no = i.item_no
where stat in ('A','C') and bulan = case when day(getdate()) = 1 then FORMAT(getdate()-1,'MM') else FORMAT(getdate(),'MM') end
order by label_type, cr_date, work_order";
// echo $qry;
$data = sqlsrv_query($connect, strtoupper($qry));

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex()
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

while($row4 = sqlsrv_fetch_object($data)){
    $TGL_4_1 = $row4->SATU;             $TGL_4_11 = $row4->SEBELAS;              $TGL_4_21 = $row4->DUAPULUHSATU;               
    $TGL_4_2 = $row4->DUA;              $TGL_4_12 = $row4->DUABELAS;             $TGL_4_22 = $row4->DUAPULUHDUA;
    $TGL_4_3 = $row4->TIGA;             $TGL_4_13 = $row4->TIGABELAS;            $TGL_4_23 = $row4->DUAPULUHTIGA;
    $TGL_4_4 = $row4->EMPAT;            $TGL_4_14 = $row4->EMPATBELAS;           $TGL_4_24 = $row4->DUAPULUHEMPAT;
    $TGL_4_5 = $row4->LIMA;             $TGL_4_15 = $row4->LIMABELAS;            $TGL_4_25 = $row4->DUAPULUHLIMA;
    $TGL_4_6 = $row4->ENAM;             $TGL_4_16 = $row4->ENAMBELAS;            $TGL_4_26 = $row4->DUAPULUHENAM;
    $TGL_4_7 = $row4->TUJUH;            $TGL_4_17 = $row4->TUJUHBELAS;           $TGL_4_27 = $row4->DUAPULUHTUJUH;
    $TGL_4_8 = $row4->DELAPAN;          $TGL_4_18 = $row4->DELAPANBELAS;         $TGL_4_28 = $row4->DUAPULUHDELAPAN;
    $TGL_4_9 = $row4->SEMBILAN;         $TGL_4_19 = $row4->SEMBILANBELAS;        $TGL_4_29 = $row4->DUAPULUHSEMBILAN;
    $TGL_4_10 = $row4->SEPULUH;         $TGL_4_20 = $row4->DUAPULUH;             $TGL_4_30 = $row4->TIGAPULUH;
                                                                                 $TGL_4_31 = $row4->TIGAPULUHSATU;
    $sts = $row4->STAT;

    if ($sts == 'A'){
        $sts = 'PLAN';
    }else{
        $sts = 'OUTPUT';
    }

    if($no4==2){
        /*1. buat satu row header*/
        $objPHPExcel->setActiveSheetIndex()
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

        $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$no4.':D'.$no4);
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

        if($row4->TOT == '' OR is_null($row4->TOT) OR $row4->TOT <= 0){    //belum dikerjakan
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
                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
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
                $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
            }
        }
    }else{
        if($lbl == $row4->LABEL_TYPE){
            if($row4->TOT == '' OR is_null($row4->TOT)){    //belum dikerjakan
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
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
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
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
                        $ada = 1;
                    }
                }else{
                    if($ada==1){
                        /*tambah buat remain*/   
                        $objPHPExcel->setActiveSheetIndex()
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
                        
                        if($prog >= $q){
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
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
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
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
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
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
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
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
                        $ada = 1;
                    }
                }else{
                    if($ada==1){
                        /*tambah buat remain*/   
                        $objPHPExcel->setActiveSheetIndex()
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
                        
                        if($prog >= $q){
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
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
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
                        $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;
                        $ada = 1;
                    }
                }
            }
        }else{
            if($ada==1){
                /*tambah buat remain*/   
                $objPHPExcel->setActiveSheetIndex()
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
                
                if($prog >= $q){
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
            $q = $row4->QTY;            $ot = $row4->OPERATEION_TIME;   $prog = $row4->TOT;

            /*1. buat satu row header*/
            $objPHPExcel->setActiveSheetIndex()
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

            $objPHPExcel->setActiveSheetIndex()->mergeCells('A'.$no4.':D'.$no4);
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

    $objPHPExcel->setActiveSheetIndex()
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
                ->setCellValue('K'.$no4, $row4->TOT)
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

    if($row4->TOT == '' OR is_null($row4->TOT)){
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
    }elseif($row4->QTY >= $row4->TOT){
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

// ini jika ingin save file to folder
// $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
// $objWriter->save(str_replace(__FILE__,$_SERVER['DOCUMMENT_ROOT'].'C:\xampp/Kuraire/wms/schedule/label_mail_excel.xls',__FILE__));

$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="label_mail_excel.xls"');
$objWriter->save('php://output');
?>

