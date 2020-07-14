<?php
error_reporting(1);

// require_once '../class/phpexcel/PHPExcel.php';
include("../connect/conn.php");
$s=0;       

$min_hari = mktime(0,0,0,date("n"),date("j")-1,date("Y"));
$kemaren = intval(date("d", $min_hari));

$now = intval(date('d')); 

$plus_hari = mktime(0,0,0,date("n"),date("j")+1,date("Y"));       
$lusa = intval(date("d", $plus_hari));

$arrBulan = array('1' => 'JANUARI', '2' => 'FEBRUARI', '3' => 'MARET', '4' => 'APRIL', '5' => 'MEI', '6' => 'JUNI', '7' => 'JULI', '8' => 'AGUSTUS', '9' => 'SEPTEMBER', '10' => 'OKTOBER', '11' => 'NOVEMBER', '12' => 'DESEMBER');

if (intval(date('d')) == 1){
    $nob = intval(date('m'))-1;
    $bln = $arrBulan[$nob];
}else{
    $bln = strtoupper(date('F'));

    if ($bln == 'JANUARY') {
    	$bln = 'JANUARI';
    }elseif ($bln == 'FEBRUARY') {
    	$bln = 'FEBRUARY';
    }elseif ($bln == 'MARCH') {
    	$bln = 'MARET';
    }elseif ($bln == 'APRIL') {
    	$bln = 'APRIL';
    }elseif ($bln == 'MAY') {
    	$bln = 'MEI';
    }elseif ($bln == 'JUNY') {
    	$bln = 'JUNI';
    }elseif ($bln == 'JULY') {
    	$bln = 'JULI';
    }elseif ($bln == 'AUGUSTUS') {
    	$bln = 'AGUSTUS';
    }elseif ($bln == 'SEPTEMBER') {
    	$bln = 'SEPTEMBER';
    }elseif ($bln == 'OCTOBER') {
    	$bln = 'OKTOBER';
    }elseif ($bln == 'NOVEMBER') {
    	$bln = 'NOVEMBER';
    }elseif ($bln == 'DECEMBER') {
    	$bln = 'DESEMBER';
    }
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

// 1
$qry1 = "select to_char(sysdate,'MONTH') as now from dual";
$data1 = oci_parse($connect, $qry1);
oci_execute($data1);

$pesan = oci_error($data1);
$msg = $pesan['message'];

$response1 = array();
$k1 = 0;

while ($row1 = oci_fetch_object($data1)){
    array_push($response1, $row1);
}

$fp1 = fopen('total_all_battery.json', 'w');
fwrite($fp1, json_encode($response1));
fclose($fp1);

//echo $msg;
// echo $response1;
echo json_encode($response1);
// ECHO strtoupper(date('F'));

// SELECT value FROM V$NLS_PARAMETERS WHERE parameter = 'NLS_DATE_FORMAT';
  
//ALTER SESSION SET NLS_DATE_FORMAT = 'DD-MON-RR';

// SELECT value FROM V$NLS_PARAMETERS WHERE parameter = 'NLS_DATE_LANGUAGE';
  
// ALTER SESSION SET NLS_DATE_LANGUAGE = 'AMERICAN';
?>

