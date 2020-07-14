<?php
$hari_DO = 31;		$bln_DO = 7;
$hari_ini = 2;		$bln_ini = 8;

echo "DO ini tgl ".$hari_DO."-".$bln_DO."-2018<br/>";
echo "hari ini tgl ".$hari_ini."-".$bln_ini."-2018<br/>";

if ($bln_ini != $bln_DO){
	if ($hari_ini>2){
		echo "tidak: DO ini hari: ".$hari_DO."-".$bln_DO."";
	}else{
		echo "ya: beda bulan tapi sebelum tanggal 3";
	}
}else{
	echo "ya: DO ini hari: ".$hari_DO."-".$bln_DO."";
}

?>