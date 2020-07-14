
<?php
$po_date = '2017-06-20';
$newDate = date("d-M-y", strtotime($po_date));
echo date('Y').'<br>';
echo strtoupper(date('F'))."<br/>";
echo $newDate."<br/>";

$a = intval(date('d'))-1;
echo $a."<br/>";

$b = mktime(0,0,0,date("n")-1,date("j"),date("Y"));

echo date("m",$b)."<br/>";

$pinjam = date("d-m-Y");
$tujuh_hari = mktime(0,0,0,date("n"),date("j")+7,date("Y"));
$kembali = date("d-m-Y", $tujuh_hari);
$min_hari = mktime(0,0,0,date("n"),date("j")-1,date("Y"));
$min_1hari = date("d", $min_hari);

echo "Tgl Pinjam : $pinjam ";
echo "<br />";
echo "Tgl Kembali : $kembali<br/>";
echo "Tgl min-1 : $min_1hari<br/>";

$bln_lalu = date("Ym", mktime(0,0,0,date("m")-1,date("d"),date("Y")));
echo $bln_lalu."<br/>";

echo md5('uenghernama')."<br/>";
echo base64_encode('uenghernama')."<br/>";
echo base64_decode('dWVuZ2hlcm5hbWE=')."<br/>";
echo sha1('uenghernama')."<br/>";

echo date('H')."<br/>";
date('Y-m-d H:i:s');

if(intval(date('H')) < 7){
	$start = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y")))." 07:00:00";
	$end = date('Y-m-d')." 07:00:00";
}else{
	$start = date('Y-m-d')." 07:00:00";
	$end = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")))." 07:00:00";
}
echo 'START: '.$start."<br/>";
echo 'END: '.$end."<br/>";

$dt = "2017-09-22 04:54:09";
echo intval(substr($dt, 11,2))."<br/>";

$i=1;
while ($i <= 5) {
	echo 'Bilangan'.$i.'<br/>';
	$i++;
}
echo "<br/>";
echo intval(date('H'))."<br/>";
$ed = intval(substr('2017-10-10 23:59:00',11,2));
echo $ed."<br/>";
if($ed < 7){
	$hr = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-1,date("Y")));
}else{
	$hr = date('Y-m-d');
}
echo $hr.'<BR/>';

date('l, F d, Y');

echo strtoupper(date('F')).'<br/>';
echo intval(date('d'))+1;
echo '<br/>';
$min_date = strtotime('-1 day',strtotime(date('Y-m-d')));
$on_date = date("l, F d, Y",$min_date);
$plan_date = intval(date("d",$min_date));

echo $plan_date;
echo '<br/>';
echo intval(date('Y')-1);
echo '<br/>';
echo strtoupper(date('F'));
echo '<br/>';
echo intval(date('d'))-1;
echo '<br/>';
$dt ='2017-11-07 08:17:17';
echo intval(substr($dt,11,2));

$array = array("PHP", "Perl", "Java");
print_r("Size 1: ".count($array)."\n");
$array = array();
print_r("Size 2: ".count($array)."\n");
echo '<br/>ROUND<br/>';
echo(ceil(0.1360294117647059) . "<br>");

echo intval(date('d'))."<br/>";
echo strtoupper(date('F'))."<br/>";

$arrBulan = array('1' => 'JANUARY', '2' => 'FEBRUARY', '3' => 'MARCH', '4' => 'APRIL', '5' => 'MAY', '6' => 'JUNE', 
             '7' => 'JULY', '8' => 'AUGUST', '9' => 'SEPTEMBER', '10' => 'OCTOBER', '11' => 'NOVEMBER', '12' => 'DECEMBER');

if (intval(date('d')) == 1){
    $nob = intval(date('m'))-1;
    $bln = $arrBulan[$nob];
}else{
    $bln = strtoupper(date('F'));
}
echo $bln."<br/>";

echo intval(date('d')) - 1;
echo "<br/>";

$text = explode("'", "Jum'at");
$text1 = implode("''", $text);

echo $text1.'<br/>';

function cek_pesan($teks){
	$kata_kotor = array("'");
	 
	$hasil = 0;
	 
	$jml_kata = count($kata_kotor);
	 
	for ($i=0;$i<$jml_kata;$i++) {
		if (stristr($teks,$kata_kotor[$i])){ 
			$hasil=$teks; 
		}
	}
	return $hasil;
}

$pesan = "Jum'at";
 
if (cek_pesan($pesan)) {
	echo "Eits, tidak bisa..tidak bisa. Ada kata-kata kotor dalam pesan kamu";
}else{
	echo "Aman";
}

echo '<br/>';

//$effectiveDate = date('Y-m-d', strtotime("+3 months", strtotime($effectiveDate)));
$effectiveDate = strtotime("+1 months", strtotime(date('Y-m-d'))); // returns timestamp
echo date('F',$effectiveDate);

$hari_ini = date("Y-m-d");
echo "<br/>";
// Tanggal pertama pada bulan ini
$tgl_pertama = date('Y-m-01', strtotime($hari_ini));
echo $tgl_pertama ;

echo "<br/>";
echo "Tanggal terakhir pada bulan ini";
$tgl_terakhir = date('Y-m-t', strtotime($hari_ini));
echo $tgl_terakhir ;
echo "<br/>";
echo intval(date('m'))+1;
echo "<br/>";

$month_1 = strtotime("-10 days", strtotime(date('Y-m-d')));
$month_2 = strtotime("+8 months", strtotime(date('Y-m-d')));
$month_3 = strtotime("+9 months", strtotime(date('Y-m-d')));
$month_4 = strtotime("+10 months", strtotime(date('Y-m-d')));

$m = strtoupper(date('F'));
$m1 = strtoupper(date('F',$month_1));
$m2 = strtoupper(date('F',$month_2));
$m3 = strtoupper(date('F',$month_3));
$m4 = strtoupper(date('F',$month_4));

echo $m."<br/>".$m1."<br/>".$m2."<br/>".$m3."<br/>".$m4;
echo "<br/>";
echo $month_1."<br/>".$month_2."<br/>".$month_3."<br/>".$month_4."<br/>";
echo strtotime("+25 days", strtotime(date('Y-m-d')));
echo "<br/>";
echo "coba : ";
$arrBln = array('','JANUARY','FEBRUARY','MARCH','APRIL','MAY','JUNE','JULY','','AUGUST','SEPTEMBER','OCTOBER','NOVEMBER','DECEMBER');
$m1 = $arrBln[intval(date('m'))+1];
echo $m1;
echo "<br/>";
$ed = intval(substr(date('Y-m-d H:i:s'),11,2));
echo $ed;
echo "<br/>";
echo date('Y-m-d H:i:s');
echo "<br/>";
function sum($hr) {
    $z = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+$hr,date("Y")));
    $date = date_create($z);
    $format = date_format($date, "l d M Y");
    return $format;
}

echo  sum(20);
echo "<br/>";
$tahun = date('Y'); //Mengambil tahun saat ini
$bulan = date('m'); //Mengambil bulan saat ini
$tanggal = cal_days_in_month(CAL_GREGORIAN, 6, 2020);

echo "Jumlah hari pada bulan saat ini adalah <b>{$tanggal}</b>";
echo "<br/>";

function datehr($hr){
    $z = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+$hr,date("Y")));
    $date = date_create($z);
    //$format = date_format($date, "D,  d M Y");
    $format = date_format($date, "D").' '.date_format($date, "d M y");
    return $format;
}
echo datehr(12);
echo "<br/>";
echo strtoupper(substr(datehr(12), 0,3));
echo "<br/>";
$k = "778-777-776-775-774-773-772-771-770";
echo $k.'<br/>';
echo strrev($k);

echo "<img alt='testing' src='../plugins/php-barcode-master/barcode.php?text=ueng&print=true'/>";
echo '<br/>';

$selisih = ((abs(strtotime ('2019-08-10') - strtotime (date('d-m-Y'))))/(60*60*24));
echo $selisih."<br>";

$tgl1 = "2018-08-10";// pendefinisian tanggal awal
$tgl2 = date('Y-m-d', strtotime('+6 days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
$tgl3 = date('Y-m-d', strtotime('+68 days', strtotime('2019-08-14')));
$tgl4 = date('Y-m-d', strtotime('+68 days', strtotime('2019-08-14')));
date('Y-m-d', strtotime('+68 days', strtotime('2019-08-14')));
echo $tgl4.'<br>';
echo '-------------------------------------<br>';
$password = "Admin";
$EncryptPassword = md5($password);
$e = crypt($password);

echo " Password Before Encryption = " . $password . '</br>' ;
echo " Password After Encryption = " . $EncryptPassword.'</br>';
echo " Password After Encryption = " . $e.'<br>' ;

echo '-------------------------------------<br>';
$str = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
$shuffled1 = str_shuffle($str);
$shuffled2 = str_shuffle($str);
// This will echo something like: bfdaec
echo substr($shuffled1, 0, 8).'<br>';
echo substr($shuffled2, 0, 8).'<br>';

function scramble_word($word) {
    if (strlen($word) < 2)
        return $word;
    else
        return $word{0} . str_shuffle(substr($word, 1, -1)) . $word{strlen($word) - 1};
}

echo preg_replace('/(\w+)/e', 'scramble_word("\1")', 'A quick brown fox jumped over the lazy dog.').'<br>';

function str_shuffle_unicode($str) {
    $tmp = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    shuffle($tmp);
    return join("", $tmp);
}

$str = "admin"; // My sweet love
echo str_shuffle($str).'<br>'; // i�eymrŢekr �
echo str_shuffle_unicode($str).'<br>'; // Şr mreyeikâ


function getCode($n) { 
    $characters = '1234567890';
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters)-1); 
        $randomString .= $characters[$index]; 
    } 
  
    return date('ymd').'-'.$randomString; 
} 
  
echo getCode(3).'<br>'; 

function getDigit($n) {
	$n_ratus = '';
	$char = '1234567890';
	$r_sisa = ''; 

	for ($i=1; $i<=$n ; $i++) { 
		$n_ratus .= $i;
	}
	
	$index1 = rand(0,2);
	$ind = $n_ratus[$index1];

	for ($j = 0; $j<2; $j++) { 
        $index2 = rand(0, strlen($char)-1); 
        $r_sisa .= $char[$index2]; 
    }
	
	return $y = $ind.$r_sisa;
} 
  
echo getDigit(3); 

echo '<br/>';

$jum_hari = cal_days_in_month(CAL_GREGORIAN, 2, 2020);

echo 'jumlah hari : '.$jum_hari.'<br/>';

$po_date = '2020-04-20';
$tgl11 = date("Y-m-d", strtotime($po_date));
$tgl21 = date("Y-m-d");

$d = date_diff($tgl11, $tgl21);
echo $tgl11." hari";
?>