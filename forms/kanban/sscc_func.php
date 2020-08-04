<?php

function sscc_kode_xls($nom){
	$arr = array();
	$jum = strlen($nom);
	$jumGan = 0;		$jumGen = 0;
	$x=1;

	if($jum < 17){
		$kode = 'Error';
	}else{
		while ($x <= $jum) {
			$karakter = substr($nom, $x-1, 1);
			if ($x % 2 == 0){
				$jumGen += intval($karakter);
			}else{
				$jumGan += intval($karakter);
			}
			$x++;
		}

		$total = ($jumGan*3) + ($jumGen);
		$mod = $total % 10;
		
		if($mod == 0){
			$s = 0;
		}else{
			$s = 10-$mod;
		}

		$kode = $nom.$s;
	}
	return $kode;
}

function sscc_kode_print($nom){
	$arr = array();
	$jum = strlen($nom);
	$jumGan = 0;		$jumGen = 0;
	$x=1;

	if($jum < 17){
		$kode = 'Error';
	}else{
		while ($x <= $jum) {
			$karakter = substr($nom, $x-1, 1);
			if ($x % 2 == 0){
				$jumGen += intval($karakter);
			}else{
				$jumGan += intval($karakter);
			}
			$x++;
		}

		$total = ($jumGan*3) + ($jumGen);
		$mod = $total % 10;
		
		if($mod == 0){
			$s = 0;
		}else{
			$s = 10-$mod;
		}
		
		$kode = '00'.$nom.$s;
	}
	return $kode;
}
?>