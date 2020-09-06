<?php
ini_set('memory_limit', '-1');
set_time_limit(0);
require_once '../../../class/phpexcel/PHPExcel.php';
include("../../../connect/conn.php");
$s=0;
$Arr_sheet = array('ITO STATUS','MRP PLAN');

$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';
$cmb_day = isset($_REQUEST['cmb_day']) ? strval($_REQUEST['cmb_day']) : '';
$ck_day = isset($_REQUEST['ck_day']) ? strval($_REQUEST['ck_day']) : '';
$sts_ito = isset($_REQUEST['sts_ito']) ? strval($_REQUEST['sts_ito']) : '';

$arrKolom1 = array('1' => 'G', '2' => 'H', '3' => 'I', '4' => 'J', '5' => 'K', '6' => 'L', '7' => 'M', '8' => 'N', '9' => 'O', '10' => 'P',
                   '11' => 'Q', '12' => 'R', '13' => 'S', '14' => 'T', '15' => 'U', '16' => 'V', '17' => 'W', '18' => 'X', '19' => 'Y', '20' => 'Z',
                   '21' => 'AA', '22' => 'AB', '23' => 'AC', '24' => 'AD', '25' => 'AE', '26' => 'AF', '27' => 'AG', '28' => 'AH', '29' => 'AI', '30' => 'AJ',
                   '31' => 'AK', '32' => 'AL', '33' => 'AM', '34' => 'AN', '35' => 'AO', '36' => 'AP', '37' => 'AQ', '38' => 'AR', '39' => 'AS', '40' => 'AT',
                   '41' => 'AU', '42' => 'AV', '43' => 'AW', '44' => 'AX', '45' => 'AY', '46' => 'AZ', '47' => 'BA', '48' => 'BB', '49' => 'BC', '50' => 'BD',
                   '51' => 'BE', '52' => 'BF', '53' => 'BG', '54' => 'BH', '55' => 'BI', '56' => 'BJ', '57' => 'BK', '58' => 'BL', '59' => 'BM', '60' => 'BN',
                   '61' => 'BO', '62' => 'BP', '63' => 'BQ', '64' => 'BR', '65' => 'BS', '66' => 'BT', '67' => 'BU', '68' => 'BV', '69' => 'BW', '70' => 'BX',
                   '71' => 'BY', '72' => 'BZ', '73' => 'CA', '74' => 'CB', '75' => 'CC', '76' => 'CD', '77' => 'CE', '78' => 'CF', '79' => 'CG', '80' => 'CH',
                   '81' => 'CI', '82' => 'CJ', '83' => 'CK', '84' => 'CL', '85' => 'CM', '86' => 'CN', '87' => 'CO', '88' => 'CP', '89' => 'CQ', '90' => 'CR');

$arrKolom2 = array('1' => 'D', '2' => 'E', '3' => 'F', '4' => 'G', '5' => 'H', '6' => 'I', '7' => 'J', '8' => 'K', '9' => 'L', '10' => 'M',
                   '11' => 'N', '12' => 'O', '13' => 'P', '14' => 'Q', '15' => 'R', '16' => 'S', '17' => 'T', '18' => 'U', '19' => 'v', '20' => 'W',
                   '21' => 'X', '22' => 'Y', '23' => 'z', '24' => 'AA', '25' => 'AB', '26' => 'AC', '27' => 'AD', '28' => 'AE', '29' => 'AF', '30' => 'AG',
                   '31' => 'AH', '32' => 'AI', '33' => 'AJ', '34' => 'AK', '35' => 'AL', '36' => 'AM', '37' => 'AN', '38' => 'AO', '39' => 'AP', '40' => 'AQ',
                   '41' => 'AR', '42' => 'AS', '43' => 'AT', '44' => 'AU', '45' => 'AV', '46' => 'AW', '47' => 'AX', '48' => 'AY', '49' => 'AZ', '50' => 'BA',
                   '51' => 'BB', '52' => 'BC', '53' => 'BD', '54' => 'BE', '55' => 'BF', '56' => 'BG', '57' => 'BH', '58' => 'BI', '59' => 'BJ', '60' => 'BK',
                   '61' => 'BL', '62' => 'BM', '63' => 'BN', '64' => 'BO', '65' => 'BP', '66' => 'BQ', '67' => 'BR', '68' => 'BS', '69' => 'BT', '70' => 'BU',
                   '71' => 'BV', '72' => 'BW', '73' => 'BX', '74' => 'BY', '75' => 'BZ', '76' => 'CA', '77' => 'CB', '78' => 'CC', '79' => 'CD', '80' => 'CE',
                   '81' => 'CF', '82' => 'CG', '83' => 'CH', '84' => 'CI', '85' => 'CJ', '86' => 'CK', '87' => 'CL', '88' => 'CM', '89' => 'CN', '90' => 'CO');

function datehr($hr){
    $z = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+$hr,date("Y")));
    $date = date_create($z);
    //$format = date_format($date, "D,  d M Y");
    $format = date_format($date, "D").' '.date_format($date, "d M y");
    return $format;
}

$exp_ito = explode('-', $sts_ito);

if($ck_item_no != 'true'){
    $item = "item_no = $cmb_item_no and ";
}else{
    $item = " ";
}


$sql1 = "select distinct item_desc, min, max, average as std, count (distinct item_no) as jum_item,
case when count (distinct item_no) = 1 then (select item_no from ztb_config_rm where tipe=item_desc) else '-' end as item_no,
case when count (distinct item_no) = 1 then (select standard_price from item where item_no=(select item_no from ztb_config_rm where tipe=item_desc)) else 0 end standard_price,
ceiling((n_1+n_2+n_3+n_4+n_5+n_6+n_7+n_8+n_9+n_10+ n_11+n_12+n_13+n_14+n_15+n_16+n_17+n_18+n_19+n_20+ 
         n_21+n_22+n_23+n_24+n_25+n_26+n_27+n_28+n_29+n_30+ n_31+n_32+n_33+n_34+n_35+n_36+n_37+n_38+n_39+n_40+ 
         n_41+n_42+n_43+n_44+n_45+n_46+n_47+n_48+n_49+n_50+ n_51+n_52+n_53+n_54+n_55+n_56+n_57+n_58+n_59+n_60+ 
         n_61+n_62+n_63+n_64+n_65+n_66+n_67+n_68+n_69+n_70+ n_71+n_72+n_73+n_74+n_75+n_76+n_77+n_78+n_79+n_80+ 
         n_81+n_82+n_83+n_84+n_85+n_86+n_87+n_88+n_89+n_90)/90) as avg, 
n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10, n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20, 
n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30, n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40, 
n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50, n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60, 
n_61, n_62, n_63, n_64, n_65, n_66, n_67, n_68, n_69, n_70, n_71, n_72, n_73, n_74, n_75, n_76, n_77, n_78, n_79, n_80, 
n_81, n_82, n_83, n_84, n_85, n_86, n_87, n_88, n_89, n_90 
from (select distinct a.item_type as item_desc, b.item_no, 
      isnull(ceiling(a.n_1),0) n_1, isnull(ceiling(a.n_2),0) n_2, isnull(ceiling(a.n_3),0) n_3, isnull(ceiling(a.n_4),0) n_4, 
      isnull(ceiling(a.n_5),0) n_5, isnull(ceiling(a.n_6),0) n_6, isnull(ceiling(a.n_7),0) n_7, isnull(ceiling(a.n_8),0) n_8, 
      isnull(ceiling(a.n_9),0) n_9, isnull(ceiling(a.n_10),0) n_10, isnull(ceiling(a.n_11),0) n_11, isnull(ceiling(a.n_12),0) n_12, 
      isnull(ceiling(a.n_13),0) n_13, isnull(ceiling(a.n_14),0) n_14, isnull(ceiling(a.n_15),0) n_15, isnull(ceiling(a.n_16),0) n_16, 
      isnull(ceiling(a.n_17),0) n_17, isnull(ceiling(a.n_18),0) n_18, isnull(ceiling(a.n_19),0) n_19, isnull(ceiling(a.n_20),0) n_20, 
      isnull(ceiling(a.n_21),0) n_21, isnull(ceiling(a.n_22),0) n_22, isnull(ceiling(a.n_23),0) n_23, isnull(ceiling(a.n_24),0) n_24, 
      isnull(ceiling(a.n_25),0) n_25, isnull(ceiling(a.n_26),0) n_26, isnull(ceiling(a.n_27),0) n_27, isnull(ceiling(a.n_28),0) n_28, 
      isnull(ceiling(a.n_29),0) n_29, isnull(ceiling(a.n_30),0) n_30, isnull(ceiling(a.n_31),0) n_31, isnull(ceiling(a.n_32),0) n_32, 
      isnull(ceiling(a.n_33),0) n_33, isnull(ceiling(a.n_34),0) n_34, isnull(ceiling(a.n_35),0) n_35, isnull(ceiling(a.n_36),0) n_36, 
      isnull(ceiling(a.n_37),0) n_37, isnull(ceiling(a.n_38),0) n_38, isnull(ceiling(a.n_39),0) n_39, isnull(ceiling(a.n_40),0) n_40, 
      isnull(ceiling(a.n_41),0) n_41, isnull(ceiling(a.n_42),0) n_42, isnull(ceiling(a.n_43),0) n_43, isnull(ceiling(a.n_44),0) n_44, 
      isnull(ceiling(a.n_45),0) n_45, isnull(ceiling(a.n_46),0) n_46, isnull(ceiling(a.n_47),0) n_47, isnull(ceiling(a.n_48),0) n_48, 
      isnull(ceiling(a.n_49),0) n_49, isnull(ceiling(a.n_50),0) n_50, isnull(ceiling(a.n_51),0) n_51, isnull(ceiling(a.n_52),0) n_52, 
      isnull(ceiling(a.n_53),0) n_53, isnull(ceiling(a.n_54),0) n_54, isnull(ceiling(a.n_55),0) n_55, isnull(ceiling(a.n_56),0) n_56, 
      isnull(ceiling(a.n_57),0) n_57, isnull(ceiling(a.n_58),0) n_58, isnull(ceiling(a.n_59),0) n_59, isnull(ceiling(a.n_60),0) n_60, 
      isnull(ceiling(a.n_61),0) n_61, isnull(ceiling(a.n_62),0) n_62, isnull(ceiling(a.n_63),0) n_63, isnull(ceiling(a.n_64),0) n_64, 
      isnull(ceiling(a.n_65),0) n_65, isnull(ceiling(a.n_66),0) n_66, isnull(ceiling(a.n_67),0) n_67, isnull(ceiling(a.n_68),0) n_68, 
      isnull(ceiling(a.n_69),0) n_69, isnull(ceiling(a.n_70),0) n_70, isnull(ceiling(a.n_71),0) n_71, isnull(ceiling(a.n_72),0) n_72, 
      isnull(ceiling(a.n_73),0) n_73, isnull(ceiling(a.n_74),0) n_74, isnull(ceiling(a.n_75),0) n_75, isnull(ceiling(a.n_76),0) n_76, 
      isnull(ceiling(a.n_77),0) n_77, isnull(ceiling(a.n_78),0) n_78, isnull(ceiling(a.n_79),0) n_79, isnull(ceiling(a.n_80),0) n_80, 
      isnull(ceiling(a.n_81),0) n_81, isnull(ceiling(a.n_82),0) n_82, isnull(ceiling(a.n_83),0) n_83, isnull(ceiling(a.n_84),0) n_84, 
      isnull(ceiling(a.n_85),0) n_85, isnull(ceiling(a.n_86),0) n_86, isnull(ceiling(a.n_87),0) n_87, isnull(ceiling(a.n_88),0) n_88, 
      isnull(ceiling(a.n_89),0) n_89, isnull(ceiling(a.n_90),0) n_90 
      from zvw_mrp_head a 
      left join ztb_mrp_data b on a.item_type = b.item_type
     ) aa 
      left outer join (select item_type,n_1 as min from ztb_mrp_data where no_id in (5) ) bb on aa.item_desc = bb.item_type 
      left outer join ( select item_type,n_1 as max from ztb_mrp_data where no_id in (7) ) cc on aa.item_desc = cc.item_type 
      left OUTER join ( select tipe, average from ztb_config_rm ) dd on aa.item_desc=dd.tipe 
      where item_desc is not null 
      group by item_desc, min, max, average, 
      n_1, n_2, n_3, n_4, n_5, n_6, n_7, n_8, n_9, n_10, n_11, n_12, n_13, n_14, n_15, n_16, n_17, n_18, n_19, n_20, 
      n_21, n_22, n_23, n_24, n_25, n_26, n_27, n_28, n_29, n_30, n_31, n_32, n_33, n_34, n_35, n_36, n_37, n_38, n_39, n_40, 
      n_41, n_42, n_43, n_44, n_45, n_46, n_47, n_48, n_49, n_50, n_51, n_52, n_53, n_54, n_55, n_56, n_57, n_58, n_59, n_60, 
      n_61, n_62, n_63, n_64, n_65, n_66, n_67, n_68, n_69, n_70, n_71, n_72, n_73, n_74, n_75, n_76, n_77, n_78, n_79, n_80, 
      n_81, n_82, n_83, n_84, n_85, n_86, n_87, n_88, n_89, n_90 
      order by item_desc asc";
// echo $sql1;
$data1 = sqlsrv_query($connect, strtoupper($sql1));

$sql2 = "select a.item_no, a.min_days as MAX, a.max_days as MIN, b.*, c.item, c.description as item_description from ztb_config_rm a 
        inner join ztb_mrp_data b on a.item_no=b.item_no 
        inner join item c on a.item_no=c.item_no 
        where a.item_no is not null
        order by a.item_no, b.no_id";
$data2 = sqlsrv_query($connect, strtoupper($sql2));

$objPHPExcel = new PHPExcel();
$objPHPExcel->createSheet();

while ($s <= 1) {
    if ($Arr_sheet[$s] == "ITO STATUS"){
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'ITEM NO')
                    ->setCellValue('B1', 'ITEM TYPE')
                    ->setCellValue('C1', 'STANDARD PRICE')
                    ->setCellValue('D1', 'MIN')
                    ->setCellValue('E1', 'STD')
                    ->setCellValue('F1', 'MAX')
                    ->setCellValue('G1', 'AVG')
                    ->setCellValue('H1', datehr(1))
                    ->setCellValue('I1', datehr(2))
                    ->setCellValue('J1', datehr(3))
                    ->setCellValue('K1', datehr(4))
                    ->setCellValue('L1', datehr(5))
                    ->setCellValue('M1', datehr(6))
                    ->setCellValue('N1', datehr(7))
                    ->setCellValue('O1', datehr(8))
                    ->setCellValue('P1', datehr(9))
                    ->setCellValue('Q1', datehr(10))
                    ->setCellValue('R1', datehr(11))
                    ->setCellValue('S1', datehr(12))
                    ->setCellValue('T1', datehr(13))
                    ->setCellValue('U1', datehr(14))
                    ->setCellValue('V1', datehr(15))
                    ->setCellValue('W1', datehr(16))
                    ->setCellValue('X1', datehr(17))
                    ->setCellValue('Y1', datehr(18))
                    ->setCellValue('Z1', datehr(19))
                    ->setCellValue('AA1', datehr(20))
                    ->setCellValue('AB1', datehr(21))
                    ->setCellValue('AC1', datehr(22))
                    ->setCellValue('AD1', datehr(23))
                    ->setCellValue('AE1', datehr(24))
                    ->setCellValue('AF1', datehr(25))
                    ->setCellValue('AG1', datehr(26))
                    ->setCellValue('AH1', datehr(27))
                    ->setCellValue('AI1', datehr(28))
                    ->setCellValue('AJ1', datehr(29))
                    ->setCellValue('AK1', datehr(30))
                    ->setCellValue('AL1', datehr(31))
                    ->setCellValue('AM1', datehr(32))
                    ->setCellValue('AN1', datehr(33))
                    ->setCellValue('AO1', datehr(34))
                    ->setCellValue('AP1', datehr(35))
                    ->setCellValue('AQ1', datehr(36))
                    ->setCellValue('AR1', datehr(37))
                    ->setCellValue('AS1', datehr(38))
                    ->setCellValue('AT1', datehr(39))
                    ->setCellValue('AU1', datehr(40))
                    ->setCellValue('AV1', datehr(41))
                    ->setCellValue('AW1', datehr(42))
                    ->setCellValue('AX1', datehr(43))
                    ->setCellValue('AY1', datehr(44))
                    ->setCellValue('AZ1', datehr(45))
                    ->setCellValue('BA1', datehr(46))
                    ->setCellValue('BB1', datehr(47))
                    ->setCellValue('BC1', datehr(48))
                    ->setCellValue('BD1', datehr(49))
                    ->setCellValue('BE1', datehr(50))
                    ->setCellValue('BF1', datehr(51))
                    ->setCellValue('BG1', datehr(52))
                    ->setCellValue('BH1', datehr(53))
                    ->setCellValue('BI1', datehr(54))
                    ->setCellValue('BJ1', datehr(55))
                    ->setCellValue('BK1', datehr(56))
                    ->setCellValue('BL1', datehr(57))
                    ->setCellValue('BM1', datehr(58))
                    ->setCellValue('BN1', datehr(59))
                    ->setCellValue('BO1', datehr(60))
                    ->setCellValue('BP1', datehr(61))
                    ->setCellValue('BQ1', datehr(62))
                    ->setCellValue('BR1', datehr(63))
                    ->setCellValue('BS1', datehr(64))
                    ->setCellValue('BT1', datehr(65))
                    ->setCellValue('BU1', datehr(66))
                    ->setCellValue('BV1', datehr(67))
                    ->setCellValue('BW1', datehr(68))
                    ->setCellValue('BX1', datehr(69))
                    ->setCellValue('BY1', datehr(70))
                    ->setCellValue('BZ1', datehr(71))
                    ->setCellValue('CA1', datehr(72))
                    ->setCellValue('CB1', datehr(73))
                    ->setCellValue('CC1', datehr(74))
                    ->setCellValue('CD1', datehr(75))
                    ->setCellValue('CE1', datehr(76))
                    ->setCellValue('CF1', datehr(77))
                    ->setCellValue('CG1', datehr(78))
                    ->setCellValue('CH1', datehr(79))
                    ->setCellValue('CI1', datehr(80))
                    ->setCellValue('CJ1', datehr(81))
                    ->setCellValue('CK1', datehr(82))
                    ->setCellValue('CL1', datehr(83))
                    ->setCellValue('CM1', datehr(84))
                    ->setCellValue('CN1', datehr(85))
                    ->setCellValue('CO1', datehr(86))
                    ->setCellValue('CP1', datehr(87))
                    ->setCellValue('CQ1', datehr(88))
                    ->setCellValue('CR1', datehr(89))
                    ->setCellValue('CS1', datehr(90));

        $sheet = $objPHPExcel->getActiveSheet();

        foreach(range('A','CS') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->getStyle('A1:CS1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:CS1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:CS1')->applyFromArray(
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
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'wrap' => true
                )
            )
        );

        $no1 = 2;
        while($row1 = sqlsrv_fetch_object($data1)){
            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no1, $row1->ITEM_NO)
                        ->setCellValue('B'.$no1, $row1->ITEM_DESC)
                        ->setCellValue('C'.$no1, $row1->STANDARD_PRICE)
                        ->setCellValue('D'.$no1, $row1->MIN)
                        ->setCellValue('E'.$no1, $row1->STD)
                        ->setCellValue('F'.$no1, $row1->MAX)
                        ->setCellValue('G'.$no1, $row1->AVG)
                        ->setCellValue('H'.$no1, $row1->N_1)
                        ->setCellValue('I'.$no1, $row1->N_2)
                        ->setCellValue('J'.$no1, $row1->N_3)
                        ->setCellValue('K'.$no1, $row1->N_4)
                        ->setCellValue('L'.$no1, $row1->N_5)
                        ->setCellValue('M'.$no1, $row1->N_6)
                        ->setCellValue('N'.$no1, $row1->N_7)
                        ->setCellValue('O'.$no1, $row1->N_8)
                        ->setCellValue('P'.$no1, $row1->N_9)
                        ->setCellValue('Q'.$no1, $row1->N_10)
                        ->setCellValue('R'.$no1, $row1->N_11)
                        ->setCellValue('S'.$no1, $row1->N_12)
                        ->setCellValue('T'.$no1, $row1->N_13)
                        ->setCellValue('U'.$no1, $row1->N_14)
                        ->setCellValue('V'.$no1, $row1->N_15)
                        ->setCellValue('W'.$no1, $row1->N_16)
                        ->setCellValue('X'.$no1, $row1->N_17)
                        ->setCellValue('Y'.$no1, $row1->N_18)
                        ->setCellValue('Z'.$no1, $row1->N_19)
                        ->setCellValue('AA'.$no1, $row1->N_20)
                        ->setCellValue('AB'.$no1, $row1->N_21)
                        ->setCellValue('AC'.$no1, $row1->N_22)
                        ->setCellValue('AD'.$no1, $row1->N_23)
                        ->setCellValue('AE'.$no1, $row1->N_24)
                        ->setCellValue('AF'.$no1, $row1->N_25)
                        ->setCellValue('AG'.$no1, $row1->N_26)
                        ->setCellValue('AH'.$no1, $row1->N_27)
                        ->setCellValue('AI'.$no1, $row1->N_28)
                        ->setCellValue('AJ'.$no1, $row1->N_29)
                        ->setCellValue('AK'.$no1, $row1->N_30)
                        ->setCellValue('AL'.$no1, $row1->N_31)
                        ->setCellValue('AM'.$no1, $row1->N_32)
                        ->setCellValue('AN'.$no1, $row1->N_33)
                        ->setCellValue('AO'.$no1, $row1->N_34)
                        ->setCellValue('AP'.$no1, $row1->N_35)
                        ->setCellValue('AQ'.$no1, $row1->N_36)
                        ->setCellValue('AR'.$no1, $row1->N_37)
                        ->setCellValue('AS'.$no1, $row1->N_38)
                        ->setCellValue('AT'.$no1, $row1->N_39)
                        ->setCellValue('AU'.$no1, $row1->N_40)
                        ->setCellValue('AV'.$no1, $row1->N_41)
                        ->setCellValue('AW'.$no1, $row1->N_42)
                        ->setCellValue('AX'.$no1, $row1->N_43)
                        ->setCellValue('AY'.$no1, $row1->N_44)
                        ->setCellValue('AZ'.$no1, $row1->N_45)
                        ->setCellValue('BA'.$no1, $row1->N_46)
                        ->setCellValue('BB'.$no1, $row1->N_47)
                        ->setCellValue('BC'.$no1, $row1->N_48)
                        ->setCellValue('BD'.$no1, $row1->N_49)
                        ->setCellValue('BE'.$no1, $row1->N_50)
                        ->setCellValue('BF'.$no1, $row1->N_51)
                        ->setCellValue('BG'.$no1, $row1->N_52)
                        ->setCellValue('BH'.$no1, $row1->N_53)
                        ->setCellValue('BI'.$no1, $row1->N_54)
                        ->setCellValue('BJ'.$no1, $row1->N_55)
                        ->setCellValue('BK'.$no1, $row1->N_56)
                        ->setCellValue('BL'.$no1, $row1->N_57)
                        ->setCellValue('BM'.$no1, $row1->N_58)
                        ->setCellValue('BN'.$no1, $row1->N_59)
                        ->setCellValue('BO'.$no1, $row1->N_60)
                        ->setCellValue('BP'.$no1, $row1->N_61)
                        ->setCellValue('BQ'.$no1, $row1->N_62)
                        ->setCellValue('BR'.$no1, $row1->N_63)
                        ->setCellValue('BS'.$no1, $row1->N_64)
                        ->setCellValue('BT'.$no1, $row1->N_65)
                        ->setCellValue('BU'.$no1, $row1->N_66)
                        ->setCellValue('BV'.$no1, $row1->N_67)
                        ->setCellValue('BW'.$no1, $row1->N_68)
                        ->setCellValue('BX'.$no1, $row1->N_69)
                        ->setCellValue('BY'.$no1, $row1->N_70)
                        ->setCellValue('BZ'.$no1, $row1->N_71)
                        ->setCellValue('CA'.$no1, $row1->N_72)
                        ->setCellValue('CB'.$no1, $row1->N_73)
                        ->setCellValue('CC'.$no1, $row1->N_74)
                        ->setCellValue('CD'.$no1, $row1->N_75)
                        ->setCellValue('CE'.$no1, $row1->N_76)
                        ->setCellValue('CF'.$no1, $row1->N_77)
                        ->setCellValue('CG'.$no1, $row1->N_78)
                        ->setCellValue('CH'.$no1, $row1->N_79)
                        ->setCellValue('CI'.$no1, $row1->N_80)
                        ->setCellValue('CJ'.$no1, $row1->N_81)
                        ->setCellValue('CK'.$no1, $row1->N_82)
                        ->setCellValue('CL'.$no1, $row1->N_83)
                        ->setCellValue('CM'.$no1, $row1->N_84)
                        ->setCellValue('CN'.$no1, $row1->N_85)
                        ->setCellValue('CO'.$no1, $row1->N_86)
                        ->setCellValue('CP'.$no1, $row1->N_87)
                        ->setCellValue('CQ'.$no1, $row1->N_88)
                        ->setCellValue('CR'.$no1, $row1->N_89)
                        ->setCellValue('CS'.$no1, $row1->N_90);

            if($no1 % 2 == 0){
                $sheet->getStyle('A'.$no1.':CS'.$no1)->applyFromArray(
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
                $sheet->getStyle('A'.$no1.':CS'.$no1)->applyFromArray(
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

            for ($j=1; $j <=90 ; $j++) { 
                $h2 = strtoupper(substr(datehr($j),0,3));
                if($h2 == 'SAT'){
                    $sheet->getStyle($arrKolom1[$j].$no1.':'.$arrKolom1[$j+1].$no1)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D2D2D2')
                            )
                        )
                    );       
                }
                $n = 'N_'.$j;
                if($row1->$n < 1){
                    $sheet->getStyle($arrKolom1[$j].$no1.":".$arrKolom1[$j].$no1)->getFont()->getColor()->setRGB('FF0000');
                }
            }



            $no1++;
        }
        $objPHPExcel->getActiveSheet()->setTitle('ITO STATUS');
    }elseif ($Arr_sheet[$s] == "MRP PLAN"){
        $objPHPExcel->setActiveSheetIndex($s)
                    ->setCellValue('A1', 'NO')
                    ->setCellValue('B1', 'ITEM DESCRIPTION')
                    ->setCellValue('C1', 'DESCRIPTION')
                    ->setCellValue('D1', datehr(1))
                    ->setCellValue('E1', datehr(2))
                    ->setCellValue('F1', datehr(3))
                    ->setCellValue('G1', datehr(4))
                    ->setCellValue('H1', datehr(5))
                    ->setCellValue('I1', datehr(6))
                    ->setCellValue('J1', datehr(7))
                    ->setCellValue('K1', datehr(8))
                    ->setCellValue('L1', datehr(9))
                    ->setCellValue('M1', datehr(10))
                    ->setCellValue('N1', datehr(11))
                    ->setCellValue('O1', datehr(12))
                    ->setCellValue('P1', datehr(13))
                    ->setCellValue('Q1', datehr(14))
                    ->setCellValue('R1', datehr(15))
                    ->setCellValue('S1', datehr(16))
                    ->setCellValue('T1', datehr(17))
                    ->setCellValue('U1', datehr(18))
                    ->setCellValue('V1', datehr(19))
                    ->setCellValue('W1', datehr(20))
                    ->setCellValue('X1', datehr(21))
                    ->setCellValue('Y1', datehr(22))
                    ->setCellValue('Z1', datehr(23))
                    ->setCellValue('AA1', datehr(24))
                    ->setCellValue('AB1', datehr(25))
                    ->setCellValue('AC1', datehr(26))
                    ->setCellValue('AD1', datehr(27))
                    ->setCellValue('AE1', datehr(28))
                    ->setCellValue('AF1', datehr(29))
                    ->setCellValue('AG1', datehr(30))
                    ->setCellValue('AH1', datehr(31))
                    ->setCellValue('AI1', datehr(32))
                    ->setCellValue('AJ1', datehr(33))
                    ->setCellValue('AK1', datehr(34))
                    ->setCellValue('AL1', datehr(35))
                    ->setCellValue('AM1', datehr(36))
                    ->setCellValue('AN1', datehr(37))
                    ->setCellValue('AO1', datehr(38))
                    ->setCellValue('AP1', datehr(39))
                    ->setCellValue('AQ1', datehr(40))
                    ->setCellValue('AR1', datehr(41))
                    ->setCellValue('AS1', datehr(42))
                    ->setCellValue('AT1', datehr(43))
                    ->setCellValue('AU1', datehr(44))
                    ->setCellValue('AV1', datehr(45))
                    ->setCellValue('AW1', datehr(46))
                    ->setCellValue('AX1', datehr(47))
                    ->setCellValue('AY1', datehr(48))
                    ->setCellValue('AZ1', datehr(49))
                    ->setCellValue('BA1', datehr(50))
                    ->setCellValue('BB1', datehr(51))
                    ->setCellValue('BC1', datehr(52))
                    ->setCellValue('BD1', datehr(53))
                    ->setCellValue('BE1', datehr(54))
                    ->setCellValue('BF1', datehr(55))
                    ->setCellValue('BG1', datehr(56))
                    ->setCellValue('BH1', datehr(57))
                    ->setCellValue('BI1', datehr(58))
                    ->setCellValue('BJ1', datehr(59))
                    ->setCellValue('BK1', datehr(60))
                    ->setCellValue('BL1', datehr(61))
                    ->setCellValue('BM1', datehr(62))
                    ->setCellValue('BN1', datehr(63))
                    ->setCellValue('BO1', datehr(64))
                    ->setCellValue('BP1', datehr(65))
                    ->setCellValue('BQ1', datehr(66))
                    ->setCellValue('BR1', datehr(67))
                    ->setCellValue('BS1', datehr(68))
                    ->setCellValue('BT1', datehr(69))
                    ->setCellValue('BU1', datehr(70))
                    ->setCellValue('BV1', datehr(71))
                    ->setCellValue('BW1', datehr(72))
                    ->setCellValue('BX1', datehr(73))
                    ->setCellValue('BY1', datehr(74))
                    ->setCellValue('BZ1', datehr(75))
                    ->setCellValue('CA1', datehr(76))
                    ->setCellValue('CB1', datehr(77))
                    ->setCellValue('CC1', datehr(78))
                    ->setCellValue('CD1', datehr(79))
                    ->setCellValue('CE1', datehr(80))
                    ->setCellValue('CF1', datehr(81))
                    ->setCellValue('CG1', datehr(82))
                    ->setCellValue('CH1', datehr(83))
                    ->setCellValue('CI1', datehr(84))
                    ->setCellValue('CJ1', datehr(85))
                    ->setCellValue('CK1', datehr(86))
                    ->setCellValue('CL1', datehr(87))
                    ->setCellValue('CM1', datehr(88))
                    ->setCellValue('CN1', datehr(89))
                    ->setCellValue('CO1', datehr(90));

        $sheet = $objPHPExcel->getActiveSheet();

        foreach(range('A','CO') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->getStyle('A1:CO1')->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('A1:CO1')->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('A1:CO1')->applyFromArray(
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
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'wrap' => true
                )
            )
        );

        $no2 = 2;
        $no_urut = 1;
        $itm = '';
        while($row2 = sqlsrv_fetch_object($data2)){
            if($itm == $row2->ITEM_NO){
                $itm = '';
                $nom = '';
                $nomur = $no_urut-1;
            }else{
                $itm = $row2->ITEM_NO.' - '.$row2->ITEM_DESCRIPTION;
                $nom = $no_urut;
                $nomur = $no_urut;
                $no_urut++;
            }

            $objPHPExcel->setActiveSheetIndex($s)
                        ->setCellValue('A'.$no2, $nom)
                        ->setCellValue('B'.$no2, $row2->ITEM_NO.' - '.$row2->ITEM_DESCRIPTION)
                        ->setCellValue('C'.$no2, strtoupper($row2->DESCRIPTION))
                        ->setCellValue('D'.$no2, $row2->N_1)
                        ->setCellValue('E'.$no2, $row2->N_2)
                        ->setCellValue('F'.$no2, $row2->N_3)
                        ->setCellValue('G'.$no2, $row2->N_4)
                        ->setCellValue('H'.$no2, $row2->N_5)
                        ->setCellValue('I'.$no2, $row2->N_6)
                        ->setCellValue('J'.$no2, $row2->N_7)
                        ->setCellValue('K'.$no2, $row2->N_8)
                        ->setCellValue('L'.$no2, $row2->N_9)
                        ->setCellValue('M'.$no2, $row2->N_10)
                        ->setCellValue('N'.$no2, $row2->N_11)
                        ->setCellValue('O'.$no2, $row2->N_12)
                        ->setCellValue('P'.$no2, $row2->N_13)
                        ->setCellValue('Q'.$no2, $row2->N_14)
                        ->setCellValue('R'.$no2, $row2->N_15)
                        ->setCellValue('S'.$no2, $row2->N_16)
                        ->setCellValue('T'.$no2, $row2->N_17)
                        ->setCellValue('U'.$no2, $row2->N_18)
                        ->setCellValue('V'.$no2, $row2->N_19)
                        ->setCellValue('W'.$no2, $row2->N_20)
                        ->setCellValue('X'.$no2, $row2->N_21)
                        ->setCellValue('Y'.$no2, $row2->N_22)
                        ->setCellValue('Z'.$no2, $row2->N_23)
                        ->setCellValue('AA'.$no2, $row2->N_24)
                        ->setCellValue('AB'.$no2, $row2->N_25)
                        ->setCellValue('AC'.$no2, $row2->N_26)
                        ->setCellValue('AD'.$no2, $row2->N_27)
                        ->setCellValue('AE'.$no2, $row2->N_28)
                        ->setCellValue('AF'.$no2, $row2->N_29)
                        ->setCellValue('AG'.$no2, $row2->N_30)
                        ->setCellValue('AH'.$no2, $row2->N_31)
                        ->setCellValue('AI'.$no2, $row2->N_32)
                        ->setCellValue('AJ'.$no2, $row2->N_33)
                        ->setCellValue('AK'.$no2, $row2->N_34)
                        ->setCellValue('AL'.$no2, $row2->N_35)
                        ->setCellValue('AM'.$no2, $row2->N_36)
                        ->setCellValue('AN'.$no2, $row2->N_37)
                        ->setCellValue('AO'.$no2, $row2->N_38)
                        ->setCellValue('AP'.$no2, $row2->N_39)
                        ->setCellValue('AQ'.$no2, $row2->N_40)
                        ->setCellValue('AR'.$no2, $row2->N_41)
                        ->setCellValue('AS'.$no2, $row2->N_42)
                        ->setCellValue('AT'.$no2, $row2->N_43)
                        ->setCellValue('AU'.$no2, $row2->N_44)
                        ->setCellValue('AV'.$no2, $row2->N_45)
                        ->setCellValue('AW'.$no2, $row2->N_46)
                        ->setCellValue('AX'.$no2, $row2->N_47)
                        ->setCellValue('AY'.$no2, $row2->N_48)
                        ->setCellValue('AZ'.$no2, $row2->N_49)
                        ->setCellValue('BA'.$no2, $row2->N_50)
                        ->setCellValue('BB'.$no2, $row2->N_51)
                        ->setCellValue('BC'.$no2, $row2->N_52)
                        ->setCellValue('BD'.$no2, $row2->N_53)
                        ->setCellValue('BE'.$no2, $row2->N_54)
                        ->setCellValue('BF'.$no2, $row2->N_55)
                        ->setCellValue('BG'.$no2, $row2->N_56)
                        ->setCellValue('BH'.$no2, $row2->N_57)
                        ->setCellValue('BI'.$no2, $row2->N_58)
                        ->setCellValue('BJ'.$no2, $row2->N_59)
                        ->setCellValue('BK'.$no2, $row2->N_60)
                        ->setCellValue('BL'.$no2, $row2->N_61)
                        ->setCellValue('BM'.$no2, $row2->N_62)
                        ->setCellValue('BN'.$no2, $row2->N_63)
                        ->setCellValue('BO'.$no2, $row2->N_64)
                        ->setCellValue('BP'.$no2, $row2->N_65)
                        ->setCellValue('BQ'.$no2, $row2->N_66)
                        ->setCellValue('BR'.$no2, $row2->N_67)
                        ->setCellValue('BS'.$no2, $row2->N_68)
                        ->setCellValue('BT'.$no2, $row2->N_69)
                        ->setCellValue('BU'.$no2, $row2->N_70)
                        ->setCellValue('BV'.$no2, $row2->N_71)
                        ->setCellValue('BW'.$no2, $row2->N_72)
                        ->setCellValue('BX'.$no2, $row2->N_73)
                        ->setCellValue('BY'.$no2, $row2->N_74)
                        ->setCellValue('BZ'.$no2, $row2->N_75)
                        ->setCellValue('CA'.$no2, $row2->N_76)
                        ->setCellValue('CB'.$no2, $row2->N_77)
                        ->setCellValue('CC'.$no2, $row2->N_78)
                        ->setCellValue('CD'.$no2, $row2->N_79)
                        ->setCellValue('CE'.$no2, $row2->N_80)
                        ->setCellValue('CF'.$no2, $row2->N_81)
                        ->setCellValue('CG'.$no2, $row2->N_82)
                        ->setCellValue('CH'.$no2, $row2->N_83)
                        ->setCellValue('CI'.$no2, $row2->N_84)
                        ->setCellValue('CJ'.$no2, $row2->N_85)
                        ->setCellValue('CK'.$no2, $row2->N_86)
                        ->setCellValue('CL'.$no2, $row2->N_87)
                        ->setCellValue('CM'.$no2, $row2->N_88)
                        ->setCellValue('CN'.$no2, $row2->N_89)
                        ->setCellValue('CO'.$no2, $row2->N_90);

            if($nomur % 2 != 0){
                $sheet->getStyle('A'.$no2.':CO'.$no2)->applyFromArray(
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
                $sheet->getStyle('A'.$no2.':CO'.$no2)->applyFromArray(
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

            for ($i=1; $i <=90 ; $i++) { 
                $h1 = strtoupper(substr(datehr($i),0,3));
                if($h1 == 'SAT'){
                    $sheet->getStyle($arrKolom2[$i].$no2.':'.$arrKolom2[$i+1].$no2)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'D2D2D2')
                            )
                        )
                    );       
                }

                $n2 = 'N_'.$i;
                if($row2->$n2 < 1){
                    $sheet->getStyle($arrKolom2[$i].$no2.":".$arrKolom2[$i].$no2)->getFont()->getColor()->setRGB('FF0000');
                }
            }

            $sheet->getStyle('A'.$no2.':CO'.$no2)->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    )
                )
            );



            $objPHPExcel->getActiveSheet()->getStyle('D'.$no2.':CO'.$no2)->getNumberFormat()->setFormatCode('#,##0');

            $itm = $row2->ITEM_NO;
            $no2++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('MRP PLAN');
    }
    $s++;
}

$objPHPExcel->setActiveSheetIndex(0);
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="MRP-RM-'.date('Y-m-d').'.xls"');
$objWriter->save('php://output');
?>