<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
include("../../../connect/conn.php");
require_once '../../../class/phpexcel/PHPExcel.php';
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array( ' memoryCacheSize ' => '8MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$cmb_week = isset($_REQUEST['cmb_week']) ? strval($_REQUEST['cmb_week']) : '';
$ck_week = isset($_REQUEST['ck_week']) ? strval($_REQUEST['ck_week']) : '';
$cmb_fg = isset($_REQUEST['cmb_fg']) ? strval($_REQUEST['cmb_fg']) : '';
$ck_fg = isset($_REQUEST['ck_fg']) ? strval($_REQUEST['ck_fg']) : '';
$cmb_item = isset($_REQUEST['cmb_item']) ? strval($_REQUEST['cmb_item']) : '';
$ck_item = isset($_REQUEST['ck_item']) ? strval($_REQUEST['ck_item']) : '';

if ($ck_fg != "true"){
    $fg = "a.item_no = $cmb_fg and ";
}else{
    $fg = "";
}

if($ck_item != "true") {
    $item = "st.lower_item_no = $cmb_item and ";
}else{
    $item = "";
}

if ($ck_week != "true"){
    $jumN = $cmb_week * 7 ;
    $week = " a.item_no in (select distinct item_no from mps_header r
        inner join ztb_mps_details s
        on r.po_no = s.po_no and r.po_line_no = s.po_line_no
        where mps_date between cast(getdate() as date) and cast(getdate() + $jumN as date)
        ) and ";
}else{
    $week = "";
}

$where = " where $fg $item $week i.description is not null
    and st.lower_item_no in (select distinct item_no from ztb_mrp_data_pck)";

$sql = "
    select upper_item_no,item_name,this_inventory,level_no,lower_item_no,line_no,NO_ID,DESCRIPTION,N_1,N_2,N_3,N_4,N_5,N_6,N_7,N_8,N_9,N_10,N_11,N_12,N_13,N_14,N_15,N_16,N_17,N_18,N_19,N_20,N_21,N_22,N_23,N_24,N_25,N_26,N_27,N_28,N_29,N_30,N_31,N_32,N_33,N_34,N_35,N_36,N_37,N_38,N_39,N_40,N_41,N_42,N_43,N_44,N_45,N_46,N_47,N_48,N_49,N_50,N_51,N_52,N_53,N_54,N_55,N_56,N_57,N_58,N_59,N_60,N_61,N_62,N_63,N_64,N_65,N_66,N_67,N_68,N_69,N_70,N_71,N_72,N_73,N_74,N_75,N_76,N_77,N_78,N_79,N_80,N_81,N_82,N_83,N_84,N_85,N_86,N_87,N_88,N_89,N_90,z.ITEM_NO,ITEM_DESC,item_no_lower,description_lower_item,balance
    from (
        select distinct a.item_no upper_item_no, i.description as item_name, isnull(wh.this_inventory,0) as this_inventory, st.level_no, st.lower_item_no, st.line_no
        from mps_header a
        inner join (select distinct po_no, po_line_no, isnull(sum(mps_qty),0) as qty 
                    from ztb_mps_details 
                    group by po_no, po_line_no) b on a.po_no=b.po_no and a.po_line_no = b.po_line_no
        inner join (select st1.upper_item_no, st1.level_no, st2.lower_item_no, st2.line_no
                    from (select upper_item_no, max(level_no) as level_no from structure group by upper_item_no) st1
                    inner join structure st2 on st1.upper_item_no= st2.upper_item_no and st1.level_no= st2.level_no) st on a.item_no = st.upper_item_no
        left outer join whinventory wh on a.item_no = wh.item_no
        left join item i on a.item_no = i.item_no
        $where
    
    )aa
    inner join (select * from ztb_mrp_data_pck where no_id in(4,2)) z on aa.lower_item_no = z.item_no
    inner join (select item_no as item_no_lower, description as description_lower_item from item)it on aa.lower_item_no = it.item_no_lower 
    left outer join (select ITEM_NO, isnull(sum(bal_qty),0) as balance from po_details where eta <= cast(getdate() as date ) group by item_no) pod on aa.lower_item_no = pod.item_no
    order by aa.item_name asc, aa.upper_item_no asc, aa.line_no asc, z.no_id asc
";

// VERS LAMA

// "select * from (
//     select a.item_no upper_item_no, a.item_name, 
//         this_inventory, a.bom_level as level_no, st.lower_item_no
//         from (select distinct item_no,item_name,bom_level from mps_header) a
    
//     inner join structure st on st.upper_item_no = a.item_no and st.level_no = a.bom_level
    
//     left outer join whinventory wh on a.item_no = wh.item_no
//     $where
//     )aa 
//     inner join (select * from ztb_mrp_data_pck where no_id in(4,2)) z on aa.lower_item_no = z.item_no
//     inner join (select item_no as item_no_lower, description as description_lower_item from item)it on aa.lower_item_no = it.item_no_lower 
//     left outer join (select ITEM_NO, nvl(sum(bal_qty),0) as balance from po_details where eta <= (select sysdate from dual) group by item_no) pod on aa.lower_item_no = pod.item_no
//     --where upper_item_no=88844
//     order by aa.upper_item_no,aa.lower_item_no, z.no_id asc";




$data = sqlsrv_query($connect, strtoupper($sql));


//Added by Reza Vebrian --> ueng update 26-APR-19
$flag1 = 0;  //minus orange
$flag2 = 0;  //minus merah
$flag3 = 0;  //normal

function cellcolor($num1,$num2,$cell){
    global $flag1;          global $flag2;          global $flag3;
    global $cellArray1;     global $cellArray2;     global $cellArray3;
    $total = $num1+$num2;
    if ($num1 <=0 && $total <= 0){      //merah
        $cellArray2[$flag2] = $cell;
        $flag2=$flag2+1;
    }elseif($num1 <=0 && $total > 0){   //orange
        $cellArray1[$flag1] = $cell;
        $flag1=$flag1+1;
    }else{                              //normal
        $cellArray3[$flag3] = $cell;
        $flag3=$flag3+1;
    }
};
//Ended By Reza vebrian

$arrKolom = array('1' => 'E', '2' => 'F', '3' => 'G', '4' => 'H', '5' => 'I', '6' => 'J', '7' => 'K', '8' => 'L', '9' => 'M', '10' => 'N',
    '11' => 'O', '12' => 'P', '13' => 'Q', '14' => 'R', '15' => 'S', '16' => 'T', '17' => 'U', '18' => 'V', '19' => 'W', '20' => 'X',
    '21' => 'Y', '22' => 'Z', '23' => 'AA', '24' => 'AB', '25' => 'AC', '26' => 'AD', '27' => 'AE', '28' => 'AF', '29' => 'AG', '30' => 'AH',
    '31' => 'AI', '32' => 'AJ', '33' => 'AK', '34' => 'AL', '35' => 'AM', '36' => 'AN', '37' => 'AO', '38' => 'AP', '39' => 'AQ', '40' => 'AR',
    '41' => 'AS', '42' => 'AT', '43' => 'AU', '44' => 'AV', '45' => 'AW', '46' => 'AX', '47' => 'AY', '48' => 'AZ', '49' => 'BA', '50' => 'BB',
    '51' => 'BC', '52' => 'BD', '53' => 'BE', '54' => 'BF', '55' => 'BG', '56' => 'BH', '57' => 'BI', '58' => 'BJ', '59' => 'BK', '60' => 'BL',
    '61' => 'BM', '62' => 'BN', '63' => 'BO', '64' => 'BP', '65' => 'BQ', '66' => 'BR', '67' => 'BS', '68' => 'BT', '69' => 'BU', '70' => 'BV',
    '71' => 'BW', '72' => 'BX', '73' => 'BY', '74' => 'BZ', '75' => 'CA', '76' => 'CB', '77' => 'CC', '78' => 'CD', '79' => 'CE', '80' => 'CF',
    '81' => 'CG', '82' => 'CH', '83' => 'CI', '84' => 'CJ', '85' => 'CK', '86' => 'CL', '87' => 'CM', '88' => 'CN', '89' => 'CO', '90' => 'CP');

function datehr($hr){
    $z = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+$hr,date("Y")));
    $date = date_create($z);
    //$format = date_format($date, "D,  d M Y");
    $format = date_format($date, "D").' '.date_format($date, "d M y");
    return $format;
}

//orange
$styleArray1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FFC000'),
    )
);

//merah
$styleArray2 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
    )
);

//normal
$styleArray3 = array(
    'font'  => array(
        'bold'  => true
    )
);

$objPHPExcel = new PHPExcel(); 
$sheet = $objPHPExcel->getActiveSheet();

$sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

// Add Data in your file


$item_up = '';      $noUrut_up=1;
$noRow = 4;
while ($dt=sqlsrv_fetch_object($data) ){
    if ($noRow == 4){
        /*HEADER UPPER*/                                                                                                    
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$noRow, 'NO.')
                    ->setCellValue('B'.$noRow, 'FG ITEM NO.')
                    ->setCellValue('D'.$noRow, 'BRAND')
                    ->setCellValue('F'.$noRow, 'INVENTORY')
                    ->setCellValue('G'.$noRow, 'LEVEL');
        $objPHPExcel->setActiveSheetIndex()->mergeCells('B'.$noRow.':C'.$noRow);
        $objPHPExcel->setActiveSheetIndex()->mergeCells('D'.$noRow.':E'.$noRow);
        foreach(range('A','G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
        $sheet->getStyle('A'.$noRow.':G'.$noRow)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );
        $sheet->getStyle('A'.$noRow.':G'.$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'BFBFBF')
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
        $noRow++;

        /*DATA UPPER*/
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$noRow, $noUrut_up)
                    ->setCellValue('B'.$noRow, $dt->UPPER_ITEM_NO)
                    ->setCellValue('D'.$noRow, $dt->ITEM_NAME)
                    ->setCellValue('F'.$noRow, $dt->THIS_INVENTORY)
                    ->setCellValue('G'.$noRow, $dt->LEVEL_NO);
        $objPHPExcel->setActiveSheetIndex()->mergeCells('B'.$noRow.':C'.$noRow);
        $objPHPExcel->setActiveSheetIndex()->mergeCells('D'.$noRow.':E'.$noRow);
        foreach(range('A','G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->getStyle('A'.$noRow.':E'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
        $sheet->getStyle('F'.$noRow.':G'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));

        $sheet->getStyle('A'.$noRow.':G'.$noRow)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FDE9D9')
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

        $sheet->getStyle('F'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
        $noRow++;

        /*HEADER LOWER ITEM*/
       

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.$noRow, 'NO.')
                    ->setCellValue('C'.$noRow, 'ITEM NO.')
                    ->setCellValue('D'.$noRow, 'DESCRIPTION')
                    ->setCellValue('E'.$noRow, 'BALANCE')
                    ->setCellValue('F'.$noRow, datehr(1))
                    ->setCellValue('G'.$noRow, datehr(2))
                    ->setCellValue('H'.$noRow, datehr(3))
                    ->setCellValue('I'.$noRow, datehr(4))
                    ->setCellValue('J'.$noRow, datehr(5))
                    ->setCellValue('K'.$noRow, datehr(6))
                    ->setCellValue('L'.$noRow, datehr(7))
                    ->setCellValue('M'.$noRow, datehr(8))
                    ->setCellValue('N'.$noRow, datehr(9))
                    ->setCellValue('O'.$noRow, datehr(10))
                    ->setCellValue('P'.$noRow, datehr(11))
                    ->setCellValue('Q'.$noRow, datehr(12))
                    ->setCellValue('R'.$noRow, datehr(13))
                    ->setCellValue('S'.$noRow, datehr(14))
                    ->setCellValue('T'.$noRow, datehr(15))
                    ->setCellValue('U'.$noRow, datehr(16))
                    ->setCellValue('V'.$noRow, datehr(17))
                    ->setCellValue('W'.$noRow, datehr(18))
                    ->setCellValue('X'.$noRow, datehr(19))
                    ->setCellValue('Y'.$noRow, datehr(20))
                    ->setCellValue('Z'.$noRow, datehr(21))
                    ->setCellValue('AA'.$noRow, datehr(22))
                    ->setCellValue('AB'.$noRow, datehr(23))
                    ->setCellValue('AC'.$noRow, datehr(24))
                    ->setCellValue('AD'.$noRow, datehr(25))
                    ->setCellValue('AE'.$noRow, datehr(26))
                    ->setCellValue('AF'.$noRow, datehr(27))
                    ->setCellValue('AG'.$noRow, datehr(28))
                    ->setCellValue('AH'.$noRow, datehr(29))
                    ->setCellValue('AI'.$noRow, datehr(30))
                    ->setCellValue('AJ'.$noRow, datehr(31))
                    ->setCellValue('AK'.$noRow, datehr(32))
                    ->setCellValue('AL'.$noRow, datehr(33))
                    ->setCellValue('AM'.$noRow, datehr(34))
                    ->setCellValue('AN'.$noRow, datehr(35))
                    ->setCellValue('AO'.$noRow, datehr(36))
                    ->setCellValue('AP'.$noRow, datehr(37))
                    ->setCellValue('AQ'.$noRow, datehr(38))
                    ->setCellValue('AR'.$noRow, datehr(39))
                    ->setCellValue('AS'.$noRow, datehr(40))
                    ->setCellValue('AT'.$noRow, datehr(41))
                    ->setCellValue('AU'.$noRow, datehr(42))
                    ->setCellValue('AV'.$noRow, datehr(43))
                    ->setCellValue('AW'.$noRow, datehr(44))
                    ->setCellValue('AX'.$noRow, datehr(45))
                    ->setCellValue('AY'.$noRow, datehr(46))
                    ->setCellValue('AZ'.$noRow, datehr(47))
                    ->setCellValue('BA'.$noRow, datehr(48))
                    ->setCellValue('BB'.$noRow, datehr(49))
                    ->setCellValue('BC'.$noRow, datehr(50))
                    ->setCellValue('BD'.$noRow, datehr(51))
                    ->setCellValue('BE'.$noRow, datehr(52))
                    ->setCellValue('BF'.$noRow, datehr(53))
                    ->setCellValue('BG'.$noRow, datehr(54))
                    ->setCellValue('BH'.$noRow, datehr(55))
                    ->setCellValue('BI'.$noRow, datehr(56))
                    ->setCellValue('BJ'.$noRow, datehr(57))
                    ->setCellValue('BK'.$noRow, datehr(58))
                    ->setCellValue('BL'.$noRow, datehr(59))
                    ->setCellValue('BM'.$noRow, datehr(60))
                    ->setCellValue('BN'.$noRow, datehr(61))
                    ->setCellValue('BO'.$noRow, datehr(62))
                    ->setCellValue('BP'.$noRow, datehr(63))
                    ->setCellValue('BQ'.$noRow, datehr(64))
                    ->setCellValue('BR'.$noRow, datehr(65))
                    ->setCellValue('BS'.$noRow, datehr(66))
                    ->setCellValue('BT'.$noRow, datehr(67))
                    ->setCellValue('BU'.$noRow, datehr(68))
                    ->setCellValue('BV'.$noRow, datehr(69))
                    ->setCellValue('BW'.$noRow, datehr(70))
                    ->setCellValue('BX'.$noRow, datehr(71))
                    ->setCellValue('BY'.$noRow, datehr(72))
                    ->setCellValue('BZ'.$noRow, datehr(73))
                    ->setCellValue('CA'.$noRow, datehr(74))
                    ->setCellValue('CB'.$noRow, datehr(75))
                    ->setCellValue('CC'.$noRow, datehr(76))
                    ->setCellValue('CD'.$noRow, datehr(77))
                    ->setCellValue('CE'.$noRow, datehr(78))
                    ->setCellValue('CF'.$noRow, datehr(79))
                    ->setCellValue('CG'.$noRow, datehr(80))
                    ->setCellValue('CH'.$noRow, datehr(81))
                    ->setCellValue('CI'.$noRow, datehr(82))
                    ->setCellValue('CJ'.$noRow, datehr(83))
                    ->setCellValue('CK'.$noRow, datehr(84))
                    ->setCellValue('CL'.$noRow, datehr(85))
                    ->setCellValue('CM'.$noRow, datehr(86))
                    ->setCellValue('CN'.$noRow, datehr(87))
                    ->setCellValue('CO'.$noRow, datehr(88))
                    ->setCellValue('CP'.$noRow, datehr(89))
                    ->setCellValue('CQ'.$noRow, datehr(90));

        foreach(range('B','D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        foreach(range('E','CQ') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth('12');
        }

        $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
        $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );

        $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->applyFromArray(
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
                ),
                'font'  => array(
                    'bold'  => true,
                    'size'  => 12
                )
            )
        );

        $noRow++;
        $no_urut_low = 1;

        /*DATA LOWER ITEM*/
        if($dt->NO_ID == 2){
            for ($i=1; $i <= 90 ; $i++) {
                $d1= 'N_'.$i;
                if($dt->$d1 <> 0){
                     $besok = $i+1;
                     $sheet->getStyle($arrKolom[$besok].$noRow)->applyFromArray(
                        array(
                            'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => '552AFF')
                            )
                        )
                    );

                }
            }
            
        }

        //apakah no_id=4 => inventory
        if($dt->NO_ID == 4){
            cellColor($dt->N_1,$dt->BALANCE,'F'.$noRow);
            cellColor($dt->N_2,$dt->BALANCE,'G'.$noRow);
            cellColor($dt->N_3,$dt->BALANCE,'H'.$noRow);
            cellColor($dt->N_4,$dt->BALANCE,'I'.$noRow);
            cellColor($dt->N_5,$dt->BALANCE,'J'.$noRow);
            cellColor($dt->N_6,$dt->BALANCE,'K'.$noRow);
            cellColor($dt->N_7,$dt->BALANCE,'L'.$noRow);
            cellColor($dt->N_8,$dt->BALANCE,'M'.$noRow);
            cellColor($dt->N_9,$dt->BALANCE,'N'.$noRow);
            cellColor($dt->N_10,$dt->BALANCE,'O'.$noRow);

            cellColor($dt->N_11,$dt->BALANCE,'P'.$noRow);
            cellColor($dt->N_12,$dt->BALANCE,'Q'.$noRow);
            cellColor($dt->N_13,$dt->BALANCE,'R'.$noRow);
            cellColor($dt->N_14,$dt->BALANCE,'S'.$noRow);
            cellColor($dt->N_15,$dt->BALANCE,'T'.$noRow);
            cellColor($dt->N_16,$dt->BALANCE,'U'.$noRow);
            cellColor($dt->N_17,$dt->BALANCE,'V'.$noRow);
            cellColor($dt->N_18,$dt->BALANCE,'W'.$noRow);
            cellColor($dt->N_19,$dt->BALANCE,'X'.$noRow);
            cellColor($dt->N_20,$dt->BALANCE,'Y'.$noRow);

            cellColor($dt->N_21,$dt->BALANCE,'Z'.$noRow);
            cellColor($dt->N_22,$dt->BALANCE,'AA'.$noRow);
            cellColor($dt->N_23,$dt->BALANCE,'AB'.$noRow);
            cellColor($dt->N_24,$dt->BALANCE,'AC'.$noRow);
            cellColor($dt->N_25,$dt->BALANCE,'AD'.$noRow);
            cellColor($dt->N_26,$dt->BALANCE,'AE'.$noRow);
            cellColor($dt->N_27,$dt->BALANCE,'AF'.$noRow);
            cellColor($dt->N_28,$dt->BALANCE,'AG'.$noRow);
            cellColor($dt->N_29,$dt->BALANCE,'AH'.$noRow);
            cellColor($dt->N_30,$dt->BALANCE,'AI'.$noRow);

            cellColor($dt->N_31,$dt->BALANCE,'AJ'.$noRow);
            cellColor($dt->N_32,$dt->BALANCE,'AK'.$noRow);
            cellColor($dt->N_33,$dt->BALANCE,'AL'.$noRow);
            cellColor($dt->N_34,$dt->BALANCE,'AM'.$noRow);
            cellColor($dt->N_35,$dt->BALANCE,'AN'.$noRow);
            cellColor($dt->N_36,$dt->BALANCE,'AO'.$noRow);
            cellColor($dt->N_37,$dt->BALANCE,'AP'.$noRow);
            cellColor($dt->N_38,$dt->BALANCE,'AQ'.$noRow);
            cellColor($dt->N_39,$dt->BALANCE,'AR'.$noRow);
            cellColor($dt->N_40,$dt->BALANCE,'AS'.$noRow);

            cellColor($dt->N_41,$dt->BALANCE,'AT'.$noRow);
            cellColor($dt->N_42,$dt->BALANCE,'AU'.$noRow);
            cellColor($dt->N_43,$dt->BALANCE,'AV'.$noRow);
            cellColor($dt->N_44,$dt->BALANCE,'AW'.$noRow);
            cellColor($dt->N_45,$dt->BALANCE,'AX'.$noRow);
            cellColor($dt->N_46,$dt->BALANCE,'AY'.$noRow);
            cellColor($dt->N_47,$dt->BALANCE,'AZ'.$noRow);
            cellColor($dt->N_48,$dt->BALANCE,'BA'.$noRow);
            cellColor($dt->N_49,$dt->BALANCE,'BB'.$noRow);
            cellColor($dt->N_50,$dt->BALANCE,'BC'.$noRow);

            cellColor($dt->N_51,$dt->BALANCE,'BD'.$noRow);
            cellColor($dt->N_52,$dt->BALANCE,'BE'.$noRow);
            cellColor($dt->N_53,$dt->BALANCE,'BF'.$noRow);
            cellColor($dt->N_54,$dt->BALANCE,'BG'.$noRow);
            cellColor($dt->N_55,$dt->BALANCE,'BH'.$noRow);
            cellColor($dt->N_56,$dt->BALANCE,'BI'.$noRow);
            cellColor($dt->N_57,$dt->BALANCE,'BJ'.$noRow);
            cellColor($dt->N_58,$dt->BALANCE,'BK'.$noRow);
            cellColor($dt->N_59,$dt->BALANCE,'BL'.$noRow);
            cellColor($dt->N_60,$dt->BALANCE,'BM'.$noRow);

            cellColor($dt->N_61,$dt->BALANCE,'BN'.$noRow);
            cellColor($dt->N_62,$dt->BALANCE,'BO'.$noRow);
            cellColor($dt->N_63,$dt->BALANCE,'BP'.$noRow);
            cellColor($dt->N_64,$dt->BALANCE,'BQ'.$noRow);
            cellColor($dt->N_65,$dt->BALANCE,'BR'.$noRow);
            cellColor($dt->N_66,$dt->BALANCE,'BS'.$noRow);
            cellColor($dt->N_67,$dt->BALANCE,'BT'.$noRow);
            cellColor($dt->N_68,$dt->BALANCE,'BU'.$noRow);
            cellColor($dt->N_69,$dt->BALANCE,'BV'.$noRow);
            cellColor($dt->N_70,$dt->BALANCE,'BW'.$noRow);

            cellColor($dt->N_71,$dt->BALANCE,'BX'.$noRow);
            cellColor($dt->N_72,$dt->BALANCE,'BY'.$noRow);
            cellColor($dt->N_73,$dt->BALANCE,'BZ'.$noRow);
            cellColor($dt->N_74,$dt->BALANCE,'CA'.$noRow);
            cellColor($dt->N_75,$dt->BALANCE,'CB'.$noRow);
            cellColor($dt->N_76,$dt->BALANCE,'CC'.$noRow);
            cellColor($dt->N_77,$dt->BALANCE,'CD'.$noRow);
            cellColor($dt->N_78,$dt->BALANCE,'CE'.$noRow);
            cellColor($dt->N_79,$dt->BALANCE,'CF'.$noRow);
            cellColor($dt->N_80,$dt->BALANCE,'CG'.$noRow);

            cellColor($dt->N_81,$dt->BALANCE,'CH'.$noRow);
            cellColor($dt->N_82,$dt->BALANCE,'CI'.$noRow);
            cellColor($dt->N_83,$dt->BALANCE,'CJ'.$noRow);
            cellColor($dt->N_84,$dt->BALANCE,'CK'.$noRow);
            cellColor($dt->N_85,$dt->BALANCE,'CL'.$noRow);
            cellColor($dt->N_86,$dt->BALANCE,'CM'.$noRow);
            cellColor($dt->N_87,$dt->BALANCE,'CN'.$noRow);
            cellColor($dt->N_88,$dt->BALANCE,'CO'.$noRow);
            cellColor($dt->N_89,$dt->BALANCE,'CP'.$noRow);
            cellColor($dt->N_90,$dt->BALANCE,'CQ'.$noRow);
        
            //$objPHPExcel->getActiveSheet()->getStyle('E'.$noRow)->applyFromArray($styleArray);

            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.$noRow, $no_urut_low)
                    ->setCellValue('C'.$noRow, $dt->LOWER_ITEM_NO)
                    ->setCellValue('D'.$noRow, $dt->DESCRIPTION_LOWER_ITEM.' ('.$dt->DESCRIPTION.' )')
                    ->setCellValue('E'.$noRow, $dt->BALANCE)
                    ->setCellValue('F'.$noRow, $dt->N_1 + $dt->BALANCE)
                    ->setCellValue('G'.$noRow, $dt->N_2 + $dt->BALANCE)
                    ->setCellValue('H'.$noRow, $dt->N_3 + $dt->BALANCE)
                    ->setCellValue('I'.$noRow, $dt->N_4 + $dt->BALANCE)
                    ->setCellValue('J'.$noRow, $dt->N_5 + $dt->BALANCE)
                    ->setCellValue('K'.$noRow, $dt->N_6 + $dt->BALANCE)
                    ->setCellValue('L'.$noRow, $dt->N_7 + $dt->BALANCE)
                    ->setCellValue('M'.$noRow, $dt->N_8 + $dt->BALANCE)
                    ->setCellValue('N'.$noRow, $dt->N_9 + $dt->BALANCE)
                    ->setCellValue('O'.$noRow, $dt->N_10 + $dt->BALANCE)
                    ->setCellValue('P'.$noRow, $dt->N_11 + $dt->BALANCE)
                    ->setCellValue('Q'.$noRow, $dt->N_12 + $dt->BALANCE)
                    ->setCellValue('R'.$noRow, $dt->N_13 + $dt->BALANCE)
                    ->setCellValue('S'.$noRow, $dt->N_14 + $dt->BALANCE)
                    ->setCellValue('T'.$noRow, $dt->N_15 + $dt->BALANCE)
                    ->setCellValue('U'.$noRow, $dt->N_16 + $dt->BALANCE)
                    ->setCellValue('V'.$noRow, $dt->N_17 + $dt->BALANCE)
                    ->setCellValue('W'.$noRow, $dt->N_18 + $dt->BALANCE)
                    ->setCellValue('X'.$noRow, $dt->N_19 + $dt->BALANCE)
                    ->setCellValue('Y'.$noRow, $dt->N_20 + $dt->BALANCE)
                    ->setCellValue('Z'.$noRow, $dt->N_21 + $dt->BALANCE)
                    ->setCellValue('AA'.$noRow, $dt->N_22 + $dt->BALANCE)
                    ->setCellValue('AB'.$noRow, $dt->N_23 + $dt->BALANCE)
                    ->setCellValue('AC'.$noRow, $dt->N_24 + $dt->BALANCE)
                    ->setCellValue('AD'.$noRow, $dt->N_25 + $dt->BALANCE)
                    ->setCellValue('AE'.$noRow, $dt->N_26 + $dt->BALANCE)
                    ->setCellValue('AF'.$noRow, $dt->N_27 + $dt->BALANCE)
                    ->setCellValue('AG'.$noRow, $dt->N_28 + $dt->BALANCE)
                    ->setCellValue('AH'.$noRow, $dt->N_29 + $dt->BALANCE)
                    ->setCellValue('AI'.$noRow, $dt->N_30 + $dt->BALANCE)
                    ->setCellValue('AJ'.$noRow, $dt->N_31 + $dt->BALANCE)
                    ->setCellValue('AK'.$noRow, $dt->N_32 + $dt->BALANCE)
                    ->setCellValue('AL'.$noRow, $dt->N_33 + $dt->BALANCE)
                    ->setCellValue('AM'.$noRow, $dt->N_34 + $dt->BALANCE)
                    ->setCellValue('AN'.$noRow, $dt->N_35 + $dt->BALANCE)
                    ->setCellValue('AO'.$noRow, $dt->N_36 + $dt->BALANCE)
                    ->setCellValue('AP'.$noRow, $dt->N_37 + $dt->BALANCE)
                    ->setCellValue('AQ'.$noRow, $dt->N_38 + $dt->BALANCE)
                    ->setCellValue('AR'.$noRow, $dt->N_39 + $dt->BALANCE)
                    ->setCellValue('AS'.$noRow, $dt->N_40 + $dt->BALANCE)
                    ->setCellValue('AT'.$noRow, $dt->N_41 + $dt->BALANCE)
                    ->setCellValue('AU'.$noRow, $dt->N_42 + $dt->BALANCE)
                    ->setCellValue('AV'.$noRow, $dt->N_43 + $dt->BALANCE)
                    ->setCellValue('AW'.$noRow, $dt->N_44 + $dt->BALANCE)
                    ->setCellValue('AX'.$noRow, $dt->N_45 + $dt->BALANCE)
                    ->setCellValue('AY'.$noRow, $dt->N_46 + $dt->BALANCE)
                    ->setCellValue('AZ'.$noRow, $dt->N_47 + $dt->BALANCE)
                    ->setCellValue('BA'.$noRow, $dt->N_48 + $dt->BALANCE)
                    ->setCellValue('BB'.$noRow, $dt->N_49 + $dt->BALANCE)
                    ->setCellValue('BC'.$noRow, $dt->N_50 + $dt->BALANCE)
                    ->setCellValue('BD'.$noRow, $dt->N_51 + $dt->BALANCE)
                    ->setCellValue('BE'.$noRow, $dt->N_52 + $dt->BALANCE)
                    ->setCellValue('BF'.$noRow, $dt->N_53 + $dt->BALANCE)
                    ->setCellValue('BG'.$noRow, $dt->N_54 + $dt->BALANCE)
                    ->setCellValue('BH'.$noRow, $dt->N_55 + $dt->BALANCE)
                    ->setCellValue('BI'.$noRow, $dt->N_56 + $dt->BALANCE)
                    ->setCellValue('BJ'.$noRow, $dt->N_57 + $dt->BALANCE)
                    ->setCellValue('BK'.$noRow, $dt->N_58 + $dt->BALANCE)
                    ->setCellValue('BL'.$noRow, $dt->N_59 + $dt->BALANCE)
                    ->setCellValue('BM'.$noRow, $dt->N_60 + $dt->BALANCE)
                    ->setCellValue('BN'.$noRow, $dt->N_61 + $dt->BALANCE)
                    ->setCellValue('BO'.$noRow, $dt->N_62 + $dt->BALANCE)
                    ->setCellValue('BP'.$noRow, $dt->N_63 + $dt->BALANCE)
                    ->setCellValue('BQ'.$noRow, $dt->N_64 + $dt->BALANCE)
                    ->setCellValue('BR'.$noRow, $dt->N_65 + $dt->BALANCE)
                    ->setCellValue('BS'.$noRow, $dt->N_66 + $dt->BALANCE)
                    ->setCellValue('BT'.$noRow, $dt->N_67 + $dt->BALANCE)
                    ->setCellValue('BU'.$noRow, $dt->N_68 + $dt->BALANCE)
                    ->setCellValue('BV'.$noRow, $dt->N_69 + $dt->BALANCE)
                    ->setCellValue('BW'.$noRow, $dt->N_70 + $dt->BALANCE)
                    ->setCellValue('BX'.$noRow, $dt->N_71 + $dt->BALANCE)
                    ->setCellValue('BY'.$noRow, $dt->N_72 + $dt->BALANCE)
                    ->setCellValue('BZ'.$noRow, $dt->N_73 + $dt->BALANCE)
                    ->setCellValue('CA'.$noRow, $dt->N_74 + $dt->BALANCE)
                    ->setCellValue('CB'.$noRow, $dt->N_75 + $dt->BALANCE)
                    ->setCellValue('CC'.$noRow, $dt->N_76 + $dt->BALANCE)
                    ->setCellValue('CD'.$noRow, $dt->N_77 + $dt->BALANCE)
                    ->setCellValue('CE'.$noRow, $dt->N_78 + $dt->BALANCE)
                    ->setCellValue('CF'.$noRow, $dt->N_79 + $dt->BALANCE)
                    ->setCellValue('CG'.$noRow, $dt->N_80 + $dt->BALANCE)
                    ->setCellValue('CH'.$noRow, $dt->N_81 + $dt->BALANCE)
                    ->setCellValue('CI'.$noRow, $dt->N_82 + $dt->BALANCE)
                    ->setCellValue('CJ'.$noRow, $dt->N_83 + $dt->BALANCE)
                    ->setCellValue('CK'.$noRow, $dt->N_84 + $dt->BALANCE)
                    ->setCellValue('CL'.$noRow, $dt->N_85 + $dt->BALANCE)
                    ->setCellValue('CM'.$noRow, $dt->N_86 + $dt->BALANCE)
                    ->setCellValue('CN'.$noRow, $dt->N_87 + $dt->BALANCE)
                    ->setCellValue('CO'.$noRow, $dt->N_88 + $dt->BALANCE)
                    ->setCellValue('CP'.$noRow, $dt->N_89 + $dt->BALANCE)
                    ->setCellValue('CQ'.$noRow, $dt->N_90 + $dt->BALANCE);


            foreach(range('B','D') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            foreach(range('E','CQ') as $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth('12');
            }

            $sheet->getStyle('B'.$noRow.':D'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->getStyle('E'.$noRow.':CQ'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));
            $sheet->getStyle('E'.$noRow.':CQ'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

            $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN
                        )
                    ),
                    'alignment' => array(
                        'wrap' => true
                    ),
                    'font'  => array(
                        'size'  => 11
                    )
                )
            );
            $noRow++;

            $noUrut_up++;
            $no_urut_low++;
        }
    }else{
        if($item_up == $dt->UPPER_ITEM_NO){

            /*DATA LOWER ITEM*/
            if($dt->NO_ID == 2){
                for ($i=1; $i <= 90 ; $i++) {
                    $d1= 'N_'.$i;
                    if($dt->$d1 <> 0){
                         $besok = $i+1;
                         $sheet->getStyle($arrKolom[$besok].$noRow)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => '552AFF')
                                )
                            )
                        );

                    }
                }
                
            }

            //apakah no_id=4 => inventory
            if($dt->NO_ID == 4){
                cellColor($dt->N_1,$dt->BALANCE,'F'.$noRow);
                cellColor($dt->N_2,$dt->BALANCE,'G'.$noRow);
                cellColor($dt->N_3,$dt->BALANCE,'H'.$noRow);
                cellColor($dt->N_4,$dt->BALANCE,'I'.$noRow);
                cellColor($dt->N_5,$dt->BALANCE,'J'.$noRow);
                cellColor($dt->N_6,$dt->BALANCE,'K'.$noRow);
                cellColor($dt->N_7,$dt->BALANCE,'L'.$noRow);
                cellColor($dt->N_8,$dt->BALANCE,'M'.$noRow);
                cellColor($dt->N_9,$dt->BALANCE,'N'.$noRow);
                cellColor($dt->N_10,$dt->BALANCE,'O'.$noRow);
        
                cellColor($dt->N_11,$dt->BALANCE,'P'.$noRow);
                cellColor($dt->N_12,$dt->BALANCE,'Q'.$noRow);
                cellColor($dt->N_13,$dt->BALANCE,'R'.$noRow);
                cellColor($dt->N_14,$dt->BALANCE,'S'.$noRow);
                cellColor($dt->N_15,$dt->BALANCE,'T'.$noRow);
                cellColor($dt->N_16,$dt->BALANCE,'U'.$noRow);
                cellColor($dt->N_17,$dt->BALANCE,'V'.$noRow);
                cellColor($dt->N_18,$dt->BALANCE,'W'.$noRow);
                cellColor($dt->N_19,$dt->BALANCE,'X'.$noRow);
                cellColor($dt->N_20,$dt->BALANCE,'Y'.$noRow);
        
                cellColor($dt->N_21,$dt->BALANCE,'Z'.$noRow);
                cellColor($dt->N_22,$dt->BALANCE,'AA'.$noRow);
                cellColor($dt->N_23,$dt->BALANCE,'AB'.$noRow);
                cellColor($dt->N_24,$dt->BALANCE,'AC'.$noRow);
                cellColor($dt->N_25,$dt->BALANCE,'AD'.$noRow);
                cellColor($dt->N_26,$dt->BALANCE,'AE'.$noRow);
                cellColor($dt->N_27,$dt->BALANCE,'AF'.$noRow);
                cellColor($dt->N_28,$dt->BALANCE,'AG'.$noRow);
                cellColor($dt->N_29,$dt->BALANCE,'AH'.$noRow);
                cellColor($dt->N_30,$dt->BALANCE,'AI'.$noRow);
        
                cellColor($dt->N_31,$dt->BALANCE,'AJ'.$noRow);
                cellColor($dt->N_32,$dt->BALANCE,'AK'.$noRow);
                cellColor($dt->N_33,$dt->BALANCE,'AL'.$noRow);
                cellColor($dt->N_34,$dt->BALANCE,'AM'.$noRow);
                cellColor($dt->N_35,$dt->BALANCE,'AN'.$noRow);
                cellColor($dt->N_36,$dt->BALANCE,'AO'.$noRow);
                cellColor($dt->N_37,$dt->BALANCE,'AP'.$noRow);
                cellColor($dt->N_38,$dt->BALANCE,'AQ'.$noRow);
                cellColor($dt->N_39,$dt->BALANCE,'AR'.$noRow);
                cellColor($dt->N_40,$dt->BALANCE,'AS'.$noRow);
        
                cellColor($dt->N_41,$dt->BALANCE,'AT'.$noRow);
                cellColor($dt->N_42,$dt->BALANCE,'AU'.$noRow);
                cellColor($dt->N_43,$dt->BALANCE,'AV'.$noRow);
                cellColor($dt->N_44,$dt->BALANCE,'AW'.$noRow);
                cellColor($dt->N_45,$dt->BALANCE,'AX'.$noRow);
                cellColor($dt->N_46,$dt->BALANCE,'AY'.$noRow);
                cellColor($dt->N_47,$dt->BALANCE,'AZ'.$noRow);
                cellColor($dt->N_48,$dt->BALANCE,'BA'.$noRow);
                cellColor($dt->N_49,$dt->BALANCE,'BB'.$noRow);
                cellColor($dt->N_50,$dt->BALANCE,'BC'.$noRow);
        
                cellColor($dt->N_51,$dt->BALANCE,'BD'.$noRow);
                cellColor($dt->N_52,$dt->BALANCE,'BE'.$noRow);
                cellColor($dt->N_53,$dt->BALANCE,'BF'.$noRow);
                cellColor($dt->N_54,$dt->BALANCE,'BG'.$noRow);
                cellColor($dt->N_55,$dt->BALANCE,'BH'.$noRow);
                cellColor($dt->N_56,$dt->BALANCE,'BI'.$noRow);
                cellColor($dt->N_57,$dt->BALANCE,'BJ'.$noRow);
                cellColor($dt->N_58,$dt->BALANCE,'BK'.$noRow);
                cellColor($dt->N_59,$dt->BALANCE,'BL'.$noRow);
                cellColor($dt->N_60,$dt->BALANCE,'BM'.$noRow);
        
                cellColor($dt->N_61,$dt->BALANCE,'BN'.$noRow);
                cellColor($dt->N_62,$dt->BALANCE,'BO'.$noRow);
                cellColor($dt->N_63,$dt->BALANCE,'BP'.$noRow);
                cellColor($dt->N_64,$dt->BALANCE,'BQ'.$noRow);
                cellColor($dt->N_65,$dt->BALANCE,'BR'.$noRow);
                cellColor($dt->N_66,$dt->BALANCE,'BS'.$noRow);
                cellColor($dt->N_67,$dt->BALANCE,'BT'.$noRow);
                cellColor($dt->N_68,$dt->BALANCE,'BU'.$noRow);
                cellColor($dt->N_69,$dt->BALANCE,'BV'.$noRow);
                cellColor($dt->N_70,$dt->BALANCE,'BW'.$noRow);
        
                cellColor($dt->N_71,$dt->BALANCE,'BX'.$noRow);
                cellColor($dt->N_72,$dt->BALANCE,'BY'.$noRow);
                cellColor($dt->N_73,$dt->BALANCE,'BZ'.$noRow);
                cellColor($dt->N_74,$dt->BALANCE,'CA'.$noRow);
                cellColor($dt->N_75,$dt->BALANCE,'CB'.$noRow);
                cellColor($dt->N_76,$dt->BALANCE,'CC'.$noRow);
                cellColor($dt->N_77,$dt->BALANCE,'CD'.$noRow);
                cellColor($dt->N_78,$dt->BALANCE,'CE'.$noRow);
                cellColor($dt->N_79,$dt->BALANCE,'CF'.$noRow);
                cellColor($dt->N_80,$dt->BALANCE,'CG'.$noRow);
        
                cellColor($dt->N_81,$dt->BALANCE,'CH'.$noRow);
                cellColor($dt->N_82,$dt->BALANCE,'CI'.$noRow);
                cellColor($dt->N_83,$dt->BALANCE,'CJ'.$noRow);
                cellColor($dt->N_84,$dt->BALANCE,'CK'.$noRow);
                cellColor($dt->N_85,$dt->BALANCE,'CL'.$noRow);
                cellColor($dt->N_86,$dt->BALANCE,'CM'.$noRow);
                cellColor($dt->N_87,$dt->BALANCE,'CN'.$noRow);
                cellColor($dt->N_88,$dt->BALANCE,'CO'.$noRow);
                cellColor($dt->N_89,$dt->BALANCE,'CP'.$noRow);
                cellColor($dt->N_90,$dt->BALANCE,'CQ'.$noRow);
        
            
                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('B'.$noRow, $no_urut_low)
                            ->setCellValue('C'.$noRow, $dt->LOWER_ITEM_NO)
                            ->setCellValue('D'.$noRow, $dt->DESCRIPTION_LOWER_ITEM.' ('.$dt->DESCRIPTION.' )')
                            ->setCellValue('E'.$noRow, $dt->BALANCE)
                            ->setCellValue('F'.$noRow, $dt->N_1 + $dt->BALANCE)
                            ->setCellValue('G'.$noRow, $dt->N_2 + $dt->BALANCE)
                            ->setCellValue('H'.$noRow, $dt->N_3 + $dt->BALANCE)
                            ->setCellValue('I'.$noRow, $dt->N_4 + $dt->BALANCE)
                            ->setCellValue('J'.$noRow, $dt->N_5 + $dt->BALANCE)
                            ->setCellValue('K'.$noRow, $dt->N_6 + $dt->BALANCE)
                            ->setCellValue('L'.$noRow, $dt->N_7 + $dt->BALANCE)
                            ->setCellValue('M'.$noRow, $dt->N_8 + $dt->BALANCE)
                            ->setCellValue('N'.$noRow, $dt->N_9 + $dt->BALANCE)
                            ->setCellValue('O'.$noRow, $dt->N_10 + $dt->BALANCE)
                            ->setCellValue('P'.$noRow, $dt->N_11 + $dt->BALANCE)
                            ->setCellValue('Q'.$noRow, $dt->N_12 + $dt->BALANCE)
                            ->setCellValue('R'.$noRow, $dt->N_13 + $dt->BALANCE)
                            ->setCellValue('S'.$noRow, $dt->N_14 + $dt->BALANCE)
                            ->setCellValue('T'.$noRow, $dt->N_15 + $dt->BALANCE)
                            ->setCellValue('U'.$noRow, $dt->N_16 + $dt->BALANCE)
                            ->setCellValue('V'.$noRow, $dt->N_17 + $dt->BALANCE)
                            ->setCellValue('W'.$noRow, $dt->N_18 + $dt->BALANCE)
                            ->setCellValue('X'.$noRow, $dt->N_19 + $dt->BALANCE)
                            ->setCellValue('Y'.$noRow, $dt->N_20 + $dt->BALANCE)
                            ->setCellValue('Z'.$noRow, $dt->N_21 + $dt->BALANCE)
                            ->setCellValue('AA'.$noRow, $dt->N_22 + $dt->BALANCE)
                            ->setCellValue('AB'.$noRow, $dt->N_23 + $dt->BALANCE)
                            ->setCellValue('AC'.$noRow, $dt->N_24 + $dt->BALANCE)
                            ->setCellValue('AD'.$noRow, $dt->N_25 + $dt->BALANCE)
                            ->setCellValue('AE'.$noRow, $dt->N_26 + $dt->BALANCE)
                            ->setCellValue('AF'.$noRow, $dt->N_27 + $dt->BALANCE)
                            ->setCellValue('AG'.$noRow, $dt->N_28 + $dt->BALANCE)
                            ->setCellValue('AH'.$noRow, $dt->N_29 + $dt->BALANCE)
                            ->setCellValue('AI'.$noRow, $dt->N_30 + $dt->BALANCE)
                            ->setCellValue('AJ'.$noRow, $dt->N_31 + $dt->BALANCE)
                            ->setCellValue('AK'.$noRow, $dt->N_32 + $dt->BALANCE)
                            ->setCellValue('AL'.$noRow, $dt->N_33 + $dt->BALANCE)
                            ->setCellValue('AM'.$noRow, $dt->N_34 + $dt->BALANCE)
                            ->setCellValue('AN'.$noRow, $dt->N_35 + $dt->BALANCE)
                            ->setCellValue('AO'.$noRow, $dt->N_36 + $dt->BALANCE)
                            ->setCellValue('AP'.$noRow, $dt->N_37 + $dt->BALANCE)
                            ->setCellValue('AQ'.$noRow, $dt->N_38 + $dt->BALANCE)
                            ->setCellValue('AR'.$noRow, $dt->N_39 + $dt->BALANCE)
                            ->setCellValue('AS'.$noRow, $dt->N_40 + $dt->BALANCE)
                            ->setCellValue('AT'.$noRow, $dt->N_41 + $dt->BALANCE)
                            ->setCellValue('AU'.$noRow, $dt->N_42 + $dt->BALANCE)
                            ->setCellValue('AV'.$noRow, $dt->N_43 + $dt->BALANCE)
                            ->setCellValue('AW'.$noRow, $dt->N_44 + $dt->BALANCE)
                            ->setCellValue('AX'.$noRow, $dt->N_45 + $dt->BALANCE)
                            ->setCellValue('AY'.$noRow, $dt->N_46 + $dt->BALANCE)
                            ->setCellValue('AZ'.$noRow, $dt->N_47 + $dt->BALANCE)
                            ->setCellValue('BA'.$noRow, $dt->N_48 + $dt->BALANCE)
                            ->setCellValue('BB'.$noRow, $dt->N_49 + $dt->BALANCE)
                            ->setCellValue('BC'.$noRow, $dt->N_50 + $dt->BALANCE)
                            ->setCellValue('BD'.$noRow, $dt->N_51 + $dt->BALANCE)
                            ->setCellValue('BE'.$noRow, $dt->N_52 + $dt->BALANCE)
                            ->setCellValue('BF'.$noRow, $dt->N_53 + $dt->BALANCE)
                            ->setCellValue('BG'.$noRow, $dt->N_54 + $dt->BALANCE)
                            ->setCellValue('BH'.$noRow, $dt->N_55 + $dt->BALANCE)
                            ->setCellValue('BI'.$noRow, $dt->N_56 + $dt->BALANCE)
                            ->setCellValue('BJ'.$noRow, $dt->N_57 + $dt->BALANCE)
                            ->setCellValue('BK'.$noRow, $dt->N_58 + $dt->BALANCE)
                            ->setCellValue('BL'.$noRow, $dt->N_59 + $dt->BALANCE)
                            ->setCellValue('BM'.$noRow, $dt->N_60 + $dt->BALANCE)
                            ->setCellValue('BN'.$noRow, $dt->N_61 + $dt->BALANCE)
                            ->setCellValue('BO'.$noRow, $dt->N_62 + $dt->BALANCE)
                            ->setCellValue('BP'.$noRow, $dt->N_63 + $dt->BALANCE)
                            ->setCellValue('BQ'.$noRow, $dt->N_64 + $dt->BALANCE)
                            ->setCellValue('BR'.$noRow, $dt->N_65 + $dt->BALANCE)
                            ->setCellValue('BS'.$noRow, $dt->N_66 + $dt->BALANCE)
                            ->setCellValue('BT'.$noRow, $dt->N_67 + $dt->BALANCE)
                            ->setCellValue('BU'.$noRow, $dt->N_68 + $dt->BALANCE)
                            ->setCellValue('BV'.$noRow, $dt->N_69 + $dt->BALANCE)
                            ->setCellValue('BW'.$noRow, $dt->N_70 + $dt->BALANCE)
                            ->setCellValue('BX'.$noRow, $dt->N_71 + $dt->BALANCE)
                            ->setCellValue('BY'.$noRow, $dt->N_72 + $dt->BALANCE)
                            ->setCellValue('BZ'.$noRow, $dt->N_73 + $dt->BALANCE)
                            ->setCellValue('CA'.$noRow, $dt->N_74 + $dt->BALANCE)
                            ->setCellValue('CB'.$noRow, $dt->N_75 + $dt->BALANCE)
                            ->setCellValue('CC'.$noRow, $dt->N_76 + $dt->BALANCE)
                            ->setCellValue('CD'.$noRow, $dt->N_77 + $dt->BALANCE)
                            ->setCellValue('CE'.$noRow, $dt->N_78 + $dt->BALANCE)
                            ->setCellValue('CF'.$noRow, $dt->N_79 + $dt->BALANCE)
                            ->setCellValue('CG'.$noRow, $dt->N_80 + $dt->BALANCE)
                            ->setCellValue('CH'.$noRow, $dt->N_81 + $dt->BALANCE)
                            ->setCellValue('CI'.$noRow, $dt->N_82 + $dt->BALANCE)
                            ->setCellValue('CJ'.$noRow, $dt->N_83 + $dt->BALANCE)
                            ->setCellValue('CK'.$noRow, $dt->N_84 + $dt->BALANCE)
                            ->setCellValue('CL'.$noRow, $dt->N_85 + $dt->BALANCE)
                            ->setCellValue('CM'.$noRow, $dt->N_86 + $dt->BALANCE)
                            ->setCellValue('CN'.$noRow, $dt->N_87 + $dt->BALANCE)
                            ->setCellValue('CO'.$noRow, $dt->N_88 + $dt->BALANCE)
                            ->setCellValue('CP'.$noRow, $dt->N_89 + $dt->BALANCE)
                            ->setCellValue('CQ'.$noRow, $dt->N_90 + $dt->BALANCE);
            

                foreach(range('B','E') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                foreach(range('F','CQ') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setWidth('12');
                }

                $sheet->getStyle('B'.$noRow.':D'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
                $sheet->getStyle('E'.$noRow.':CQ'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));
                $sheet->getStyle('E'.$noRow.':CQ'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->applyFromArray(
                    array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        ),
                        'alignment' => array(
                            'wrap' => true
                        ),
                        'font'  => array(
                            'size'  => 11
                        )
                    )
                );
                $noRow++;

                $no_urut_low++;
            }
        }else{
            /*HEADER UPPER*/
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$noRow, 'NO.')
                        ->setCellValue('B'.$noRow, 'FG ITEM NO.')
                        ->setCellValue('D'.$noRow, 'BRAND')
                        ->setCellValue('F'.$noRow, 'INVENTORY')
                        ->setCellValue('G'.$noRow, 'LEVEL');
            $objPHPExcel->setActiveSheetIndex()->mergeCells('B'.$noRow.':C'.$noRow);
            $objPHPExcel->setActiveSheetIndex()->mergeCells('D'.$noRow.':E'.$noRow);
            foreach(range('A','G') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            $sheet->getStyle('A'.$noRow.':G'.$noRow)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );
            $sheet->getStyle('A'.$noRow.':G'.$noRow)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'BFBFBF')
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
            $noRow++;
            $noUrut_up++;

            /*DATA UPPER*/
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$noRow, $noUrut_up)
                        ->setCellValue('B'.$noRow, $dt->UPPER_ITEM_NO)
                        ->setCellValue('D'.$noRow, $dt->ITEM_NAME)
                        ->setCellValue('F'.$noRow, $dt->THIS_INVENTORY)
                        ->setCellValue('G'.$noRow, $dt->LEVEL_NO);
            $objPHPExcel->setActiveSheetIndex()->mergeCells('B'.$noRow.':C'.$noRow);
            $objPHPExcel->setActiveSheetIndex()->mergeCells('D'.$noRow.':E'.$noRow);
            foreach(range('A','G') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $sheet->getStyle('A'.$noRow.':E'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->getStyle('F'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));

            $sheet->getStyle('A'.$noRow.':G'.$noRow)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => 'FDE9D9')
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
            $sheet->getStyle('F'.$noRow.':G'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
            $noRow++;

            /*HEADER LOWER ITEM*/
            $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('B'.$noRow, 'NO.')
                        ->setCellValue('C'.$noRow, 'ITEM NO.')
                        ->setCellValue('D'.$noRow, 'DESCRIPTION')
                        ->setCellValue('E'.$noRow, 'BALANCE')
                        ->setCellValue('F'.$noRow, datehr(1))
                        ->setCellValue('G'.$noRow, datehr(2))
                        ->setCellValue('H'.$noRow, datehr(3))
                        ->setCellValue('I'.$noRow, datehr(4))
                        ->setCellValue('J'.$noRow, datehr(5))
                        ->setCellValue('K'.$noRow, datehr(6))
                        ->setCellValue('L'.$noRow, datehr(7))
                        ->setCellValue('M'.$noRow, datehr(8))
                        ->setCellValue('N'.$noRow, datehr(9))
                        ->setCellValue('O'.$noRow, datehr(10))
                        ->setCellValue('P'.$noRow, datehr(11))
                        ->setCellValue('Q'.$noRow, datehr(12))
                        ->setCellValue('R'.$noRow, datehr(13))
                        ->setCellValue('S'.$noRow, datehr(14))
                        ->setCellValue('T'.$noRow, datehr(15))
                        ->setCellValue('U'.$noRow, datehr(16))
                        ->setCellValue('V'.$noRow, datehr(17))
                        ->setCellValue('W'.$noRow, datehr(18))
                        ->setCellValue('X'.$noRow, datehr(19))
                        ->setCellValue('Y'.$noRow, datehr(20))
                        ->setCellValue('Z'.$noRow, datehr(21))
                        ->setCellValue('AA'.$noRow, datehr(22))
                        ->setCellValue('AB'.$noRow, datehr(23))
                        ->setCellValue('AC'.$noRow, datehr(24))
                        ->setCellValue('AD'.$noRow, datehr(25))
                        ->setCellValue('AE'.$noRow, datehr(26))
                        ->setCellValue('AF'.$noRow, datehr(27))
                        ->setCellValue('AG'.$noRow, datehr(28))
                        ->setCellValue('AH'.$noRow, datehr(29))
                        ->setCellValue('AI'.$noRow, datehr(30))
                        ->setCellValue('AJ'.$noRow, datehr(31))
                        ->setCellValue('AK'.$noRow, datehr(32))
                        ->setCellValue('AL'.$noRow, datehr(33))
                        ->setCellValue('AM'.$noRow, datehr(34))
                        ->setCellValue('AN'.$noRow, datehr(35))
                        ->setCellValue('AO'.$noRow, datehr(36))
                        ->setCellValue('AP'.$noRow, datehr(37))
                        ->setCellValue('AQ'.$noRow, datehr(38))
                        ->setCellValue('AR'.$noRow, datehr(39))
                        ->setCellValue('AS'.$noRow, datehr(40))
                        ->setCellValue('AT'.$noRow, datehr(41))
                        ->setCellValue('AU'.$noRow, datehr(42))
                        ->setCellValue('AV'.$noRow, datehr(43))
                        ->setCellValue('AW'.$noRow, datehr(44))
                        ->setCellValue('AX'.$noRow, datehr(45))
                        ->setCellValue('AY'.$noRow, datehr(46))
                        ->setCellValue('AZ'.$noRow, datehr(47))
                        ->setCellValue('BA'.$noRow, datehr(48))
                        ->setCellValue('BB'.$noRow, datehr(49))
                        ->setCellValue('BC'.$noRow, datehr(50))
                        ->setCellValue('BD'.$noRow, datehr(51))
                        ->setCellValue('BE'.$noRow, datehr(52))
                        ->setCellValue('BF'.$noRow, datehr(53))
                        ->setCellValue('BG'.$noRow, datehr(54))
                        ->setCellValue('BH'.$noRow, datehr(55))
                        ->setCellValue('BI'.$noRow, datehr(56))
                        ->setCellValue('BJ'.$noRow, datehr(57))
                        ->setCellValue('BK'.$noRow, datehr(58))
                        ->setCellValue('BL'.$noRow, datehr(59))
                        ->setCellValue('BM'.$noRow, datehr(60))
                        ->setCellValue('BN'.$noRow, datehr(61))
                        ->setCellValue('BO'.$noRow, datehr(62))
                        ->setCellValue('BP'.$noRow, datehr(63))
                        ->setCellValue('BQ'.$noRow, datehr(64))
                        ->setCellValue('BR'.$noRow, datehr(65))
                        ->setCellValue('BS'.$noRow, datehr(66))
                        ->setCellValue('BT'.$noRow, datehr(67))
                        ->setCellValue('BU'.$noRow, datehr(68))
                        ->setCellValue('BV'.$noRow, datehr(69))
                        ->setCellValue('BW'.$noRow, datehr(70))
                        ->setCellValue('BX'.$noRow, datehr(71))
                        ->setCellValue('BY'.$noRow, datehr(72))
                        ->setCellValue('BZ'.$noRow, datehr(73))
                        ->setCellValue('CA'.$noRow, datehr(74))
                        ->setCellValue('CB'.$noRow, datehr(75))
                        ->setCellValue('CC'.$noRow, datehr(76))
                        ->setCellValue('CD'.$noRow, datehr(77))
                        ->setCellValue('CE'.$noRow, datehr(78))
                        ->setCellValue('CF'.$noRow, datehr(79))
                        ->setCellValue('CG'.$noRow, datehr(80))
                        ->setCellValue('CH'.$noRow, datehr(81))
                        ->setCellValue('CI'.$noRow, datehr(82))
                        ->setCellValue('CJ'.$noRow, datehr(83))
                        ->setCellValue('CK'.$noRow, datehr(84))
                        ->setCellValue('CL'.$noRow, datehr(85))
                        ->setCellValue('CM'.$noRow, datehr(86))
                        ->setCellValue('CN'.$noRow, datehr(87))
                        ->setCellValue('CO'.$noRow, datehr(88))
                        ->setCellValue('CP'.$noRow, datehr(89))
                        ->setCellValue('CQ'.$noRow, datehr(90));

            foreach(range('B','D') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            foreach(range('E','CQ') as $columnID) {
                $sheet->getColumnDimension($columnID)->setWidth('12');
            }

            $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->getAlignment()->applyFromArray(
                array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                )
            );

            $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->applyFromArray(
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
                    ),
                    'font'  => array(
                        'bold'  => true,
                        'size'  => 12
                    )
                )
            );

            $noRow++;
            $no_urut_low = 1;

            /*DATA LOWER ITEM*/

            if($dt->NO_ID == 2){
                for ($i=1; $i <= 90 ; $i++) {
                    $d1= 'N_'.$i;
                    if($dt->$d1 <> 0){
                         $besok = $i+1;
                         $sheet->getStyle($arrKolom[$besok].$noRow)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => '552AFF')
                                )
                            )
                        );

                    }
                }
                
            }

            //apakah no_id=4 => inventory
            if($dt->NO_ID == 4){
                cellColor($dt->N_1,$dt->BALANCE,'F'.$noRow);
                cellColor($dt->N_2,$dt->BALANCE,'G'.$noRow);
                cellColor($dt->N_3,$dt->BALANCE,'H'.$noRow);
                cellColor($dt->N_4,$dt->BALANCE,'I'.$noRow);
                cellColor($dt->N_5,$dt->BALANCE,'J'.$noRow);
                cellColor($dt->N_6,$dt->BALANCE,'K'.$noRow);
                cellColor($dt->N_7,$dt->BALANCE,'L'.$noRow);
                cellColor($dt->N_8,$dt->BALANCE,'M'.$noRow);
                cellColor($dt->N_9,$dt->BALANCE,'N'.$noRow);
                cellColor($dt->N_10,$dt->BALANCE,'O'.$noRow);
        
                cellColor($dt->N_11,$dt->BALANCE,'P'.$noRow);
                cellColor($dt->N_12,$dt->BALANCE,'Q'.$noRow);
                cellColor($dt->N_13,$dt->BALANCE,'R'.$noRow);
                cellColor($dt->N_14,$dt->BALANCE,'S'.$noRow);
                cellColor($dt->N_15,$dt->BALANCE,'T'.$noRow);
                cellColor($dt->N_16,$dt->BALANCE,'U'.$noRow);
                cellColor($dt->N_17,$dt->BALANCE,'V'.$noRow);
                cellColor($dt->N_18,$dt->BALANCE,'W'.$noRow);
                cellColor($dt->N_19,$dt->BALANCE,'X'.$noRow);
                cellColor($dt->N_20,$dt->BALANCE,'Y'.$noRow);
        
                cellColor($dt->N_21,$dt->BALANCE,'Z'.$noRow);
                cellColor($dt->N_22,$dt->BALANCE,'AA'.$noRow);
                cellColor($dt->N_23,$dt->BALANCE,'AB'.$noRow);
                cellColor($dt->N_24,$dt->BALANCE,'AC'.$noRow);
                cellColor($dt->N_25,$dt->BALANCE,'AD'.$noRow);
                cellColor($dt->N_26,$dt->BALANCE,'AE'.$noRow);
                cellColor($dt->N_27,$dt->BALANCE,'AF'.$noRow);
                cellColor($dt->N_28,$dt->BALANCE,'AG'.$noRow);
                cellColor($dt->N_29,$dt->BALANCE,'AH'.$noRow);
                cellColor($dt->N_30,$dt->BALANCE,'AI'.$noRow);
        
                cellColor($dt->N_31,$dt->BALANCE,'AJ'.$noRow);
                cellColor($dt->N_32,$dt->BALANCE,'AK'.$noRow);
                cellColor($dt->N_33,$dt->BALANCE,'AL'.$noRow);
                cellColor($dt->N_34,$dt->BALANCE,'AM'.$noRow);
                cellColor($dt->N_35,$dt->BALANCE,'AN'.$noRow);
                cellColor($dt->N_36,$dt->BALANCE,'AO'.$noRow);
                cellColor($dt->N_37,$dt->BALANCE,'AP'.$noRow);
                cellColor($dt->N_38,$dt->BALANCE,'AQ'.$noRow);
                cellColor($dt->N_39,$dt->BALANCE,'AR'.$noRow);
                cellColor($dt->N_40,$dt->BALANCE,'AS'.$noRow);
        
                cellColor($dt->N_41,$dt->BALANCE,'AT'.$noRow);
                cellColor($dt->N_42,$dt->BALANCE,'AU'.$noRow);
                cellColor($dt->N_43,$dt->BALANCE,'AV'.$noRow);
                cellColor($dt->N_44,$dt->BALANCE,'AW'.$noRow);
                cellColor($dt->N_45,$dt->BALANCE,'AX'.$noRow);
                cellColor($dt->N_46,$dt->BALANCE,'AY'.$noRow);
                cellColor($dt->N_47,$dt->BALANCE,'AZ'.$noRow);
                cellColor($dt->N_48,$dt->BALANCE,'BA'.$noRow);
                cellColor($dt->N_49,$dt->BALANCE,'BB'.$noRow);
                cellColor($dt->N_50,$dt->BALANCE,'BC'.$noRow);
        
                cellColor($dt->N_51,$dt->BALANCE,'BD'.$noRow);
                cellColor($dt->N_52,$dt->BALANCE,'BE'.$noRow);
                cellColor($dt->N_53,$dt->BALANCE,'BF'.$noRow);
                cellColor($dt->N_54,$dt->BALANCE,'BG'.$noRow);
                cellColor($dt->N_55,$dt->BALANCE,'BH'.$noRow);
                cellColor($dt->N_56,$dt->BALANCE,'BI'.$noRow);
                cellColor($dt->N_57,$dt->BALANCE,'BJ'.$noRow);
                cellColor($dt->N_58,$dt->BALANCE,'BK'.$noRow);
                cellColor($dt->N_59,$dt->BALANCE,'BL'.$noRow);
                cellColor($dt->N_60,$dt->BALANCE,'BM'.$noRow);
        
                cellColor($dt->N_61,$dt->BALANCE,'BN'.$noRow);
                cellColor($dt->N_62,$dt->BALANCE,'BO'.$noRow);
                cellColor($dt->N_63,$dt->BALANCE,'BP'.$noRow);
                cellColor($dt->N_64,$dt->BALANCE,'BQ'.$noRow);
                cellColor($dt->N_65,$dt->BALANCE,'BR'.$noRow);
                cellColor($dt->N_66,$dt->BALANCE,'BS'.$noRow);
                cellColor($dt->N_67,$dt->BALANCE,'BT'.$noRow);
                cellColor($dt->N_68,$dt->BALANCE,'BU'.$noRow);
                cellColor($dt->N_69,$dt->BALANCE,'BV'.$noRow);
                cellColor($dt->N_70,$dt->BALANCE,'BW'.$noRow);
        
                cellColor($dt->N_71,$dt->BALANCE,'BX'.$noRow);
                cellColor($dt->N_72,$dt->BALANCE,'BY'.$noRow);
                cellColor($dt->N_73,$dt->BALANCE,'BZ'.$noRow);
                cellColor($dt->N_74,$dt->BALANCE,'CA'.$noRow);
                cellColor($dt->N_75,$dt->BALANCE,'CB'.$noRow);
                cellColor($dt->N_76,$dt->BALANCE,'CC'.$noRow);
                cellColor($dt->N_77,$dt->BALANCE,'CD'.$noRow);
                cellColor($dt->N_78,$dt->BALANCE,'CE'.$noRow);
                cellColor($dt->N_79,$dt->BALANCE,'CF'.$noRow);
                cellColor($dt->N_80,$dt->BALANCE,'CG'.$noRow);
        
                cellColor($dt->N_81,$dt->BALANCE,'CH'.$noRow);
                cellColor($dt->N_82,$dt->BALANCE,'CI'.$noRow);
                cellColor($dt->N_83,$dt->BALANCE,'CJ'.$noRow);
                cellColor($dt->N_84,$dt->BALANCE,'CK'.$noRow);
                cellColor($dt->N_85,$dt->BALANCE,'CL'.$noRow);
                cellColor($dt->N_86,$dt->BALANCE,'CM'.$noRow);
                cellColor($dt->N_87,$dt->BALANCE,'CN'.$noRow);
                cellColor($dt->N_88,$dt->BALANCE,'CO'.$noRow);
                cellColor($dt->N_89,$dt->BALANCE,'CP'.$noRow);
                cellColor($dt->N_90,$dt->BALANCE,'CQ'.$noRow);

                $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('B'.$noRow, $no_urut_low)
                            ->setCellValue('C'.$noRow, $dt->LOWER_ITEM_NO)
                            ->setCellValue('D'.$noRow, $dt->DESCRIPTION_LOWER_ITEM.' ('.$dt->DESCRIPTION.' )')
                            ->setCellValue('E'.$noRow, $dt->BALANCE)
                            ->setCellValue('F'.$noRow, $dt->N_1 + $dt->BALANCE)
                            ->setCellValue('G'.$noRow, $dt->N_2 + $dt->BALANCE)
                            ->setCellValue('H'.$noRow, $dt->N_3 + $dt->BALANCE)
                            ->setCellValue('I'.$noRow, $dt->N_4 + $dt->BALANCE)
                            ->setCellValue('J'.$noRow, $dt->N_5 + $dt->BALANCE)
                            ->setCellValue('K'.$noRow, $dt->N_6 + $dt->BALANCE)
                            ->setCellValue('L'.$noRow, $dt->N_7 + $dt->BALANCE)
                            ->setCellValue('M'.$noRow, $dt->N_8 + $dt->BALANCE)
                            ->setCellValue('N'.$noRow, $dt->N_9 + $dt->BALANCE)
                            ->setCellValue('O'.$noRow, $dt->N_10 + $dt->BALANCE)
                            ->setCellValue('P'.$noRow, $dt->N_11 + $dt->BALANCE)
                            ->setCellValue('Q'.$noRow, $dt->N_12 + $dt->BALANCE)
                            ->setCellValue('R'.$noRow, $dt->N_13 + $dt->BALANCE)
                            ->setCellValue('S'.$noRow, $dt->N_14 + $dt->BALANCE)
                            ->setCellValue('T'.$noRow, $dt->N_15 + $dt->BALANCE)
                            ->setCellValue('U'.$noRow, $dt->N_16 + $dt->BALANCE)
                            ->setCellValue('V'.$noRow, $dt->N_17 + $dt->BALANCE)
                            ->setCellValue('W'.$noRow, $dt->N_18 + $dt->BALANCE)
                            ->setCellValue('X'.$noRow, $dt->N_19 + $dt->BALANCE)
                            ->setCellValue('Y'.$noRow, $dt->N_20 + $dt->BALANCE)
                            ->setCellValue('Z'.$noRow, $dt->N_21 + $dt->BALANCE)
                            ->setCellValue('AA'.$noRow, $dt->N_22 + $dt->BALANCE)
                            ->setCellValue('AB'.$noRow, $dt->N_23 + $dt->BALANCE)
                            ->setCellValue('AC'.$noRow, $dt->N_24 + $dt->BALANCE)
                            ->setCellValue('AD'.$noRow, $dt->N_25 + $dt->BALANCE)
                            ->setCellValue('AE'.$noRow, $dt->N_26 + $dt->BALANCE)
                            ->setCellValue('AF'.$noRow, $dt->N_27 + $dt->BALANCE)
                            ->setCellValue('AG'.$noRow, $dt->N_28 + $dt->BALANCE)
                            ->setCellValue('AH'.$noRow, $dt->N_29 + $dt->BALANCE)
                            ->setCellValue('AI'.$noRow, $dt->N_30 + $dt->BALANCE)
                            ->setCellValue('AJ'.$noRow, $dt->N_31 + $dt->BALANCE)
                            ->setCellValue('AK'.$noRow, $dt->N_32 + $dt->BALANCE)
                            ->setCellValue('AL'.$noRow, $dt->N_33 + $dt->BALANCE)
                            ->setCellValue('AM'.$noRow, $dt->N_34 + $dt->BALANCE)
                            ->setCellValue('AN'.$noRow, $dt->N_35 + $dt->BALANCE)
                            ->setCellValue('AO'.$noRow, $dt->N_36 + $dt->BALANCE)
                            ->setCellValue('AP'.$noRow, $dt->N_37 + $dt->BALANCE)
                            ->setCellValue('AQ'.$noRow, $dt->N_38 + $dt->BALANCE)
                            ->setCellValue('AR'.$noRow, $dt->N_39 + $dt->BALANCE)
                            ->setCellValue('AS'.$noRow, $dt->N_40 + $dt->BALANCE)
                            ->setCellValue('AT'.$noRow, $dt->N_41 + $dt->BALANCE)
                            ->setCellValue('AU'.$noRow, $dt->N_42 + $dt->BALANCE)
                            ->setCellValue('AV'.$noRow, $dt->N_43 + $dt->BALANCE)
                            ->setCellValue('AW'.$noRow, $dt->N_44 + $dt->BALANCE)
                            ->setCellValue('AX'.$noRow, $dt->N_45 + $dt->BALANCE)
                            ->setCellValue('AY'.$noRow, $dt->N_46 + $dt->BALANCE)
                            ->setCellValue('AZ'.$noRow, $dt->N_47 + $dt->BALANCE)
                            ->setCellValue('BA'.$noRow, $dt->N_48 + $dt->BALANCE)
                            ->setCellValue('BB'.$noRow, $dt->N_49 + $dt->BALANCE)
                            ->setCellValue('BC'.$noRow, $dt->N_50 + $dt->BALANCE)
                            ->setCellValue('BD'.$noRow, $dt->N_51 + $dt->BALANCE)
                            ->setCellValue('BE'.$noRow, $dt->N_52 + $dt->BALANCE)
                            ->setCellValue('BF'.$noRow, $dt->N_53 + $dt->BALANCE)
                            ->setCellValue('BG'.$noRow, $dt->N_54 + $dt->BALANCE)
                            ->setCellValue('BH'.$noRow, $dt->N_55 + $dt->BALANCE)
                            ->setCellValue('BI'.$noRow, $dt->N_56 + $dt->BALANCE)
                            ->setCellValue('BJ'.$noRow, $dt->N_57 + $dt->BALANCE)
                            ->setCellValue('BK'.$noRow, $dt->N_58 + $dt->BALANCE)
                            ->setCellValue('BL'.$noRow, $dt->N_59 + $dt->BALANCE)
                            ->setCellValue('BM'.$noRow, $dt->N_60 + $dt->BALANCE)
                            ->setCellValue('BN'.$noRow, $dt->N_61 + $dt->BALANCE)
                            ->setCellValue('BO'.$noRow, $dt->N_62 + $dt->BALANCE)
                            ->setCellValue('BP'.$noRow, $dt->N_63 + $dt->BALANCE)
                            ->setCellValue('BQ'.$noRow, $dt->N_64 + $dt->BALANCE)
                            ->setCellValue('BR'.$noRow, $dt->N_65 + $dt->BALANCE)
                            ->setCellValue('BS'.$noRow, $dt->N_66 + $dt->BALANCE)
                            ->setCellValue('BT'.$noRow, $dt->N_67 + $dt->BALANCE)
                            ->setCellValue('BU'.$noRow, $dt->N_68 + $dt->BALANCE)
                            ->setCellValue('BV'.$noRow, $dt->N_69 + $dt->BALANCE)
                            ->setCellValue('BW'.$noRow, $dt->N_70 + $dt->BALANCE)
                            ->setCellValue('BX'.$noRow, $dt->N_71 + $dt->BALANCE)
                            ->setCellValue('BY'.$noRow, $dt->N_72 + $dt->BALANCE)
                            ->setCellValue('BZ'.$noRow, $dt->N_73 + $dt->BALANCE)
                            ->setCellValue('CA'.$noRow, $dt->N_74 + $dt->BALANCE)
                            ->setCellValue('CB'.$noRow, $dt->N_75 + $dt->BALANCE)
                            ->setCellValue('CC'.$noRow, $dt->N_76 + $dt->BALANCE)
                            ->setCellValue('CD'.$noRow, $dt->N_77 + $dt->BALANCE)
                            ->setCellValue('CE'.$noRow, $dt->N_78 + $dt->BALANCE)
                            ->setCellValue('CF'.$noRow, $dt->N_79 + $dt->BALANCE)
                            ->setCellValue('CG'.$noRow, $dt->N_80 + $dt->BALANCE)
                            ->setCellValue('CH'.$noRow, $dt->N_81 + $dt->BALANCE)
                            ->setCellValue('CI'.$noRow, $dt->N_82 + $dt->BALANCE)
                            ->setCellValue('CJ'.$noRow, $dt->N_83 + $dt->BALANCE)
                            ->setCellValue('CK'.$noRow, $dt->N_84 + $dt->BALANCE)
                            ->setCellValue('CL'.$noRow, $dt->N_85 + $dt->BALANCE)
                            ->setCellValue('CM'.$noRow, $dt->N_86 + $dt->BALANCE)
                            ->setCellValue('CN'.$noRow, $dt->N_87 + $dt->BALANCE)
                            ->setCellValue('CO'.$noRow, $dt->N_88 + $dt->BALANCE)
                            ->setCellValue('CP'.$noRow, $dt->N_89 + $dt->BALANCE)
                            ->setCellValue('CQ'.$noRow, $dt->N_90 + $dt->BALANCE);

                foreach(range('B','D') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }

                foreach(range('E','CQ') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setWidth('12');
                }

                $sheet->getStyle('B'.$noRow.':D'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
                $sheet->getStyle('E'.$noRow.':CQ'.$noRow)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,));
                $sheet->getStyle('E'.$noRow.':CQ'.$noRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                $sheet->getStyle('B'.$noRow.':CQ'.$noRow)->applyFromArray(
                    array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        ),
                        'alignment' => array(
                            'wrap' => true
                        ),
                        'font'  => array(
                            'size'  => 11
                        )
                    )
                );
                $noRow++;
                $noUrut_up++;
                $no_urut_low++;
            }
        }
    }     
    $item_up = $dt->UPPER_ITEM_NO;
}


//Added By Reza Vebrian Tambah Warna Minus
//orange
for( $i = 0; $i < $flag1; $i++ ) {
    $objPHPExcel->getActiveSheet()->getStyle($cellArray1[$i])->applyFromArray($styleArray1);
}
//merah
for( $j = 0; $j < $flag2; $j++ ) {
    $objPHPExcel->getActiveSheet()->getStyle($cellArray2[$j])->applyFromArray($styleArray2);
}
//normal
for( $k = 0; $k < $flag3; $k++ ) {
    $objPHPExcel->getActiveSheet()->getStyle($cellArray3[$k])->applyFromArray($styleArray3);
}
 //Reza Vebrian Ending

$objPHPExcel->getDefaultStyle()
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

$objPHPExcel->setActiveSheetIndex(0);
// $objDrawing = new PHPExcel_Worksheet_Drawing();
// $objDrawing->setName('WMS-FDKI');
// $objDrawing->setDescription('FDKI');
// $objDrawing->setPath('../../../images/logo-print4.png');
// $objDrawing->setWidth('400px');
// $objDrawing->setCoordinates('A1');
// $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save(str_replace('.php', '.xls', __FILE__));
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="MRP_PACK.xls"');
$objWriter->save('php://output');
?>