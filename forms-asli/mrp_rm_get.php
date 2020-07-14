<?php
	ini_set('max_execution_time', -1);
	session_start();
	$cmb_type = isset($_REQUEST['cmb_type']) ? strval($_REQUEST['cmb_type']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';

	if($cmb_type!=''){
		$ty = "a.item = '$cmb_type' AND ";
	}else{
		$ty = "";
	}

	if($cmb_item_no!=''){
		$it = "a.item_no = $cmb_item_no AND ";
	}else{
		$it = "";
	}

	include("../connect/conn.php");

	$where = "where $ty $it a.item_no is not null ";
	$cek = "select a.*, b.description from zvw_material3 a inner join item b on a.item_no = b.item_no $where" ;
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);

	$items = array();
	$rowno=0;
	$no=1;
	$i='';	$t='';

	while($row = oci_fetch_object($data_cek)){
		array_push($items, $row);
		$item_no = $items[$rowno]->ITEM_NO;
		$desc = $items[$rowno]->DESCRIPTION;
		$tipe = $items[$rowno]->ITEM;
		$ket = $items[$rowno]->KETERANGAN;
		$items[$rowno]->KETERANGAN = strtoupper($ket);

		if($i==$item_no){
			$items[$rowno]->DESC = '';
			if($t==$tipe){
				$items[$rowno]->TIPE = '';
			}
			$items[$rowno]->NO = '';
		}else{
			$items[$rowno]->NO = $no;
			$items[$rowno]->DESC = $item_no.' - '.$desc;
			$items[$rowno]->TIPE = $tipe;
			$no++;
		}

		$t1 = $items[$rowno]->TANGGAL1;	$items[$rowno]->TANGGAL1 = number_format(ceil($t1));	 
		$t2 = $items[$rowno]->TANGGAL2;	$items[$rowno]->TANGGAL2 = number_format(ceil($t2));	 
		$t3 = $items[$rowno]->TANGGAL3;	$items[$rowno]->TANGGAL3 = number_format(ceil($t3));	 
		$t4 = $items[$rowno]->TANGGAL4;	$items[$rowno]->TANGGAL4 = number_format(ceil($t4));	 
		$t5 = $items[$rowno]->TANGGAL5;	$items[$rowno]->TANGGAL5 = number_format(ceil($t5));	 
		$t6 = $items[$rowno]->TANGGAL6;	$items[$rowno]->TANGGAL6 = number_format(ceil($t6));	 
		$t7 = $items[$rowno]->TANGGAL7;	$items[$rowno]->TANGGAL7 = number_format(ceil($t7));	 
		$t8 = $items[$rowno]->TANGGAL8;	$items[$rowno]->TANGGAL8 = number_format(ceil($t8));	 
		$t9 = $items[$rowno]->TANGGAL9;	$items[$rowno]->TANGGAL9 = number_format(ceil($t9));	 
		$t0 = $items[$rowno]->TANGGAL0; $items[$rowno]->TANGGAL0 = number_format(ceil($t0));	 
		$t11 = $items[$rowno]->TANGGAL11; $items[$rowno]->TANGGAL11 = number_format(ceil($t11)); 
		$t12 = $items[$rowno]->TANGGAL12; $items[$rowno]->TANGGAL12 = number_format(ceil($t12)); 
		$t13 = $items[$rowno]->TANGGAL13; $items[$rowno]->TANGGAL13 = number_format(ceil($t13)); 
		$t14 = $items[$rowno]->TANGGAL14; $items[$rowno]->TANGGAL14 = number_format(ceil($t14)); 
		$t15 = $items[$rowno]->TANGGAL15; $items[$rowno]->TANGGAL15 = number_format(ceil($t15)); 
		$t16 = $items[$rowno]->TANGGAL16; $items[$rowno]->TANGGAL16 = number_format(ceil($t16));
		$t17 = $items[$rowno]->TANGGAL17; $items[$rowno]->TANGGAL17 = number_format(ceil($t17));
		$t18 = $items[$rowno]->TANGGAL18; $items[$rowno]->TANGGAL18 = number_format(ceil($t18));
		$t19 = $items[$rowno]->TANGGAL19; $items[$rowno]->TANGGAL19 = number_format(ceil($t19));
		$t20 = $items[$rowno]->TANGGAL20; $items[$rowno]->TANGGAL20 = number_format(ceil($t20));
		$t21 = $items[$rowno]->TANGGAL21; $items[$rowno]->TANGGAL21 = number_format(ceil($t21));
		$t22 = $items[$rowno]->TANGGAL22; $items[$rowno]->TANGGAL22 = number_format(ceil($t22));
		$t23 = $items[$rowno]->TANGGAL23; $items[$rowno]->TANGGAL23 = number_format(ceil($t23));
		$t24 = $items[$rowno]->TANGGAL24; $items[$rowno]->TANGGAL24 = number_format(ceil($t24));
		$t25 = $items[$rowno]->TANGGAL25; $items[$rowno]->TANGGAL25 = number_format(ceil($t25));
		$t26 = $items[$rowno]->TANGGAL26; $items[$rowno]->TANGGAL26 = number_format(ceil($t26));
		$t27 = $items[$rowno]->TANGGAL27; $items[$rowno]->TANGGAL27 = number_format(ceil($t27));
		$t28 = $items[$rowno]->TANGGAL28; $items[$rowno]->TANGGAL28 = number_format(ceil($t28));
		$t29 = $items[$rowno]->TANGGAL29; $items[$rowno]->TANGGAL29 = number_format(ceil($t29));
		$t30 = $items[$rowno]->TANGGAL30; $items[$rowno]->TANGGAL30 = number_format(ceil($t30));
		$t31 = $items[$rowno]->TANGGAL31; $items[$rowno]->TANGGAL31 = number_format(ceil($t31));

	 if($items[$rowno]->KETERANGAN == 'A.PLAN'){
	   $items[$rowno]->TANGGAL1 =  '<a href="javascript:void(0)" onclick="info_assy(1,'.$items[$rowno]->ITEM_NO.','.$t1.')" style="text-decoration: none;color: black;">'.number_format(ceil($t1)).'</a>';
	   $items[$rowno]->TANGGAL2 =  '<a href="javascript:void(0)" onclick="info_assy(2,'.$items[$rowno]->ITEM_NO.','.$t2.')" style="text-decoration: none;color: black;">'.number_format(ceil($t2)).'</a>';
	   $items[$rowno]->TANGGAL3 =  '<a href="javascript:void(0)" onclick="info_assy(3,'.$items[$rowno]->ITEM_NO.','.$t3.')" style="text-decoration: none;color: black;">'.number_format(ceil($t3)).'</a>';
	   $items[$rowno]->TANGGAL4 =  '<a href="javascript:void(0)" onclick="info_assy(4,'.$items[$rowno]->ITEM_NO.','.$t4.')" style="text-decoration: none;color: black;">'.number_format(ceil($t4)).'</a>';
	   $items[$rowno]->TANGGAL5 =  '<a href="javascript:void(0)" onclick="info_assy(5,'.$items[$rowno]->ITEM_NO.','.$t5.')" style="text-decoration: none;color: black;">'.number_format(ceil($t5)).'</a>';
	   $items[$rowno]->TANGGAL6 =  '<a href="javascript:void(0)" onclick="info_assy(6,'.$items[$rowno]->ITEM_NO.','.$t6.')" style="text-decoration: none;color: black;">'.number_format(ceil($t6)).'</a>';
	   $items[$rowno]->TANGGAL7 =  '<a href="javascript:void(0)" onclick="info_assy(7,'.$items[$rowno]->ITEM_NO.','.$t7.')" style="text-decoration: none;color: black;">'.number_format(ceil($t7)).'</a>';
	   $items[$rowno]->TANGGAL8 =  '<a href="javascript:void(0)" onclick="info_assy(8,'.$items[$rowno]->ITEM_NO.','.$t8.')" style="text-decoration: none;color: black;">'.number_format(ceil($t8)).'</a>';
	   $items[$rowno]->TANGGAL9 =  '<a href="javascript:void(0)" onclick="info_assy(9,'.$items[$rowno]->ITEM_NO.','.$t9.')" style="text-decoration: none;color: black;">'.number_format(ceil($t9)).'</a>';
	   $items[$rowno]->TANGGAL0 =  '<a href="javascript:void(0)" onclick="info_assy(10,'.$items[$rowno]->ITEM_NO.','.$t0.')" style="text-decoration: none;color: black;">'.number_format(ceil($t0)).'</a>';
	   $items[$rowno]->TANGGAL11 = '<a href="javascript:void(0)" onclick="info_assy(11,'.$items[$rowno]->ITEM_NO.','.$t11.')" style="text-decoration: none;color: black;">'.number_format(ceil($t11)).'</a>';
	   $items[$rowno]->TANGGAL12 = '<a href="javascript:void(0)" onclick="info_assy(12,'.$items[$rowno]->ITEM_NO.','.$t12.')" style="text-decoration: none;color: black;">'.number_format(ceil($t12)).'</a>';
	   $items[$rowno]->TANGGAL13 = '<a href="javascript:void(0)" onclick="info_assy(13,'.$items[$rowno]->ITEM_NO.','.$t13.')" style="text-decoration: none;color: black;">'.number_format(ceil($t13)).'</a>';
	   $items[$rowno]->TANGGAL14 = '<a href="javascript:void(0)" onclick="info_assy(14,'.$items[$rowno]->ITEM_NO.','.$t14.')" style="text-decoration: none;color: black;">'.number_format(ceil($t14)).'</a>';
	   $items[$rowno]->TANGGAL15 = '<a href="javascript:void(0)" onclick="info_assy(15,'.$items[$rowno]->ITEM_NO.','.$t15.')" style="text-decoration: none;color: black;">'.number_format(ceil($t15)).'</a>';
	   $items[$rowno]->TANGGAL16 = '<a href="javascript:void(0)" onclick="info_assy(16,'.$items[$rowno]->ITEM_NO.','.$t16.')" style="text-decoration: none;color: black;">'.number_format(ceil($t16)).'</a>';
	   $items[$rowno]->TANGGAL17 = '<a href="javascript:void(0)" onclick="info_assy(17,'.$items[$rowno]->ITEM_NO.','.$t17.')" style="text-decoration: none;color: black;">'.number_format(ceil($t17)).'</a>';
	   $items[$rowno]->TANGGAL18 = '<a href="javascript:void(0)" onclick="info_assy(18,'.$items[$rowno]->ITEM_NO.','.$t18.')" style="text-decoration: none;color: black;">'.number_format(ceil($t18)).'</a>';
	   $items[$rowno]->TANGGAL19 = '<a href="javascript:void(0)" onclick="info_assy(19,'.$items[$rowno]->ITEM_NO.','.$t19.')" style="text-decoration: none;color: black;">'.number_format(ceil($t19)).'</a>';
	   $items[$rowno]->TANGGAL20 = '<a href="javascript:void(0)" onclick="info_assy(20,'.$items[$rowno]->ITEM_NO.','.$t20.')" style="text-decoration: none;color: black;">'.number_format(ceil($t20)).'</a>';
	   $items[$rowno]->TANGGAL21 = '<a href="javascript:void(0)" onclick="info_assy(21,'.$items[$rowno]->ITEM_NO.','.$t21.')" style="text-decoration: none;color: black;">'.number_format(ceil($t21)).'</a>';
	   $items[$rowno]->TANGGAL22 = '<a href="javascript:void(0)" onclick="info_assy(22,'.$items[$rowno]->ITEM_NO.','.$t22.')" style="text-decoration: none;color: black;">'.number_format(ceil($t22)).'</a>';
	   $items[$rowno]->TANGGAL23 = '<a href="javascript:void(0)" onclick="info_assy(23,'.$items[$rowno]->ITEM_NO.','.$t23.')" style="text-decoration: none;color: black;">'.number_format(ceil($t23)).'</a>';
	   $items[$rowno]->TANGGAL24 = '<a href="javascript:void(0)" onclick="info_assy(24,'.$items[$rowno]->ITEM_NO.','.$t24.')" style="text-decoration: none;color: black;">'.number_format(ceil($t24)).'</a>';
	   $items[$rowno]->TANGGAL25 = '<a href="javascript:void(0)" onclick="info_assy(25,'.$items[$rowno]->ITEM_NO.','.$t25.')" style="text-decoration: none;color: black;">'.number_format(ceil($t25)).'</a>';
	   $items[$rowno]->TANGGAL26 = '<a href="javascript:void(0)" onclick="info_assy(26,'.$items[$rowno]->ITEM_NO.','.$t26.')" style="text-decoration: none;color: black;">'.number_format(ceil($t26)).'</a>';
	   $items[$rowno]->TANGGAL27 = '<a href="javascript:void(0)" onclick="info_assy(27,'.$items[$rowno]->ITEM_NO.','.$t27.')" style="text-decoration: none;color: black;">'.number_format(ceil($t27)).'</a>';
	   $items[$rowno]->TANGGAL28 = '<a href="javascript:void(0)" onclick="info_assy(28,'.$items[$rowno]->ITEM_NO.','.$t28.')" style="text-decoration: none;color: black;">'.number_format(ceil($t28)).'</a>';
	   $items[$rowno]->TANGGAL29 = '<a href="javascript:void(0)" onclick="info_assy(29,'.$items[$rowno]->ITEM_NO.','.$t29.')" style="text-decoration: none;color: black;">'.number_format(ceil($t29)).'</a>';
	   $items[$rowno]->TANGGAL30 = '<a href="javascript:void(0)" onclick="info_assy(30,'.$items[$rowno]->ITEM_NO.','.$t30.')" style="text-decoration: none;color: black;">'.number_format(ceil($t30)).'</a>';
	   $items[$rowno]->TANGGAL31 = '<a href="javascript:void(0)" onclick="info_assy(31,'.$items[$rowno]->ITEM_NO.','.$t31.')" style="text-decoration: none;color: black;">'.number_format(ceil($t31)).'</a>';
	 }
	
	 if($items[$rowno]->KETERANGAN == 'B.USAGE'){
	   $items[$rowno]->TANGGAL1 =  '<a href="javascript:void(0)" onclick="info_do(1,'.$items[$rowno]->ITEM_NO.','.$t1.')" style="text-decoration: none;color: black;">'.number_format(ceil($t1)).'</a>';
	   $items[$rowno]->TANGGAL2 =  '<a href="javascript:void(0)" onclick="info_do(2,'.$items[$rowno]->ITEM_NO.','.$t2.')" style="text-decoration: none;color: black;">'.number_format(ceil($t2)).'</a>';
	   $items[$rowno]->TANGGAL3 =  '<a href="javascript:void(0)" onclick="info_do(3,'.$items[$rowno]->ITEM_NO.','.$t3.')" style="text-decoration: none;color: black;">'.number_format(ceil($t3)).'</a>';
	   $items[$rowno]->TANGGAL4 =  '<a href="javascript:void(0)" onclick="info_do(4,'.$items[$rowno]->ITEM_NO.','.$t4.')" style="text-decoration: none;color: black;">'.number_format(ceil($t4)).'</a>';
	   $items[$rowno]->TANGGAL5 =  '<a href="javascript:void(0)" onclick="info_do(5,'.$items[$rowno]->ITEM_NO.','.$t5.')" style="text-decoration: none;color: black;">'.number_format(ceil($t5)).'</a>';
	   $items[$rowno]->TANGGAL6 =  '<a href="javascript:void(0)" onclick="info_do(6,'.$items[$rowno]->ITEM_NO.','.$t6.')" style="text-decoration: none;color: black;">'.number_format(ceil($t6)).'</a>';
	   $items[$rowno]->TANGGAL7 =  '<a href="javascript:void(0)" onclick="info_do(7,'.$items[$rowno]->ITEM_NO.','.$t7.')" style="text-decoration: none;color: black;">'.number_format(ceil($t7)).'</a>';
	   $items[$rowno]->TANGGAL8 =  '<a href="javascript:void(0)" onclick="info_do(8,'.$items[$rowno]->ITEM_NO.','.$t8.')" style="text-decoration: none;color: black;">'.number_format(ceil($t8)).'</a>';
	   $items[$rowno]->TANGGAL9 =  '<a href="javascript:void(0)" onclick="info_do(9,'.$items[$rowno]->ITEM_NO.','.$t9.')" style="text-decoration: none;color: black;">'.number_format(ceil($t9)).'</a>';
	   $items[$rowno]->TANGGAL0 =  '<a href="javascript:void(0)" onclick="info_do(10,'.$items[$rowno]->ITEM_NO.','.$t0.')" style="text-decoration: none;color: black;">'.number_format(ceil($t0)).'</a>';
	   $items[$rowno]->TANGGAL11 = '<a href="javascript:void(0)" onclick="info_do(11,'.$items[$rowno]->ITEM_NO.','.$t11.')" style="text-decoration: none;color: black;">'.number_format(ceil($t11)).'</a>';
	   $items[$rowno]->TANGGAL12 = '<a href="javascript:void(0)" onclick="info_do(12,'.$items[$rowno]->ITEM_NO.','.$t12.')" style="text-decoration: none;color: black;">'.number_format(ceil($t12)).'</a>';
	   $items[$rowno]->TANGGAL13 = '<a href="javascript:void(0)" onclick="info_do(13,'.$items[$rowno]->ITEM_NO.','.$t13.')" style="text-decoration: none;color: black;">'.number_format(ceil($t13)).'</a>';
	   $items[$rowno]->TANGGAL14 = '<a href="javascript:void(0)" onclick="info_do(14,'.$items[$rowno]->ITEM_NO.','.$t14.')" style="text-decoration: none;color: black;">'.number_format(ceil($t14)).'</a>';
	   $items[$rowno]->TANGGAL15 = '<a href="javascript:void(0)" onclick="info_do(15,'.$items[$rowno]->ITEM_NO.','.$t15.')" style="text-decoration: none;color: black;">'.number_format(ceil($t15)).'</a>';
	   $items[$rowno]->TANGGAL16 = '<a href="javascript:void(0)" onclick="info_do(16,'.$items[$rowno]->ITEM_NO.','.$t16.')" style="text-decoration: none;color: black;">'.number_format(ceil($t16)).'</a>';
	   $items[$rowno]->TANGGAL17 = '<a href="javascript:void(0)" onclick="info_do(17,'.$items[$rowno]->ITEM_NO.','.$t17.')" style="text-decoration: none;color: black;">'.number_format(ceil($t17)).'</a>';
	   $items[$rowno]->TANGGAL18 = '<a href="javascript:void(0)" onclick="info_do(18,'.$items[$rowno]->ITEM_NO.','.$t18.')" style="text-decoration: none;color: black;">'.number_format(ceil($t18)).'</a>';
	   $items[$rowno]->TANGGAL19 = '<a href="javascript:void(0)" onclick="info_do(19,'.$items[$rowno]->ITEM_NO.','.$t19.')" style="text-decoration: none;color: black;">'.number_format(ceil($t19)).'</a>';
	   $items[$rowno]->TANGGAL20 = '<a href="javascript:void(0)" onclick="info_do(20,'.$items[$rowno]->ITEM_NO.','.$t20.')" style="text-decoration: none;color: black;">'.number_format(ceil($t20)).'</a>';
	   $items[$rowno]->TANGGAL21 = '<a href="javascript:void(0)" onclick="info_do(21,'.$items[$rowno]->ITEM_NO.','.$t21.')" style="text-decoration: none;color: black;">'.number_format(ceil($t21)).'</a>';
	   $items[$rowno]->TANGGAL22 = '<a href="javascript:void(0)" onclick="info_do(22,'.$items[$rowno]->ITEM_NO.','.$t22.')" style="text-decoration: none;color: black;">'.number_format(ceil($t22)).'</a>';
	   $items[$rowno]->TANGGAL23 = '<a href="javascript:void(0)" onclick="info_do(23,'.$items[$rowno]->ITEM_NO.','.$t23.')" style="text-decoration: none;color: black;">'.number_format(ceil($t23)).'</a>';
	   $items[$rowno]->TANGGAL24 = '<a href="javascript:void(0)" onclick="info_do(24,'.$items[$rowno]->ITEM_NO.','.$t24.')" style="text-decoration: none;color: black;">'.number_format(ceil($t24)).'</a>';
	   $items[$rowno]->TANGGAL25 = '<a href="javascript:void(0)" onclick="info_do(25,'.$items[$rowno]->ITEM_NO.','.$t25.')" style="text-decoration: none;color: black;">'.number_format(ceil($t25)).'</a>';
	   $items[$rowno]->TANGGAL26 = '<a href="javascript:void(0)" onclick="info_do(26,'.$items[$rowno]->ITEM_NO.','.$t26.')" style="text-decoration: none;color: black;">'.number_format(ceil($t26)).'</a>';
	   $items[$rowno]->TANGGAL27 = '<a href="javascript:void(0)" onclick="info_do(27,'.$items[$rowno]->ITEM_NO.','.$t27.')" style="text-decoration: none;color: black;">'.number_format(ceil($t27)).'</a>';
	   $items[$rowno]->TANGGAL28 = '<a href="javascript:void(0)" onclick="info_do(28,'.$items[$rowno]->ITEM_NO.','.$t28.')" style="text-decoration: none;color: black;">'.number_format(ceil($t28)).'</a>';
	   $items[$rowno]->TANGGAL29 = '<a href="javascript:void(0)" onclick="info_do(29,'.$items[$rowno]->ITEM_NO.','.$t29.')" style="text-decoration: none;color: black;">'.number_format(ceil($t29)).'</a>';
	   $items[$rowno]->TANGGAL30 = '<a href="javascript:void(0)" onclick="info_do(30,'.$items[$rowno]->ITEM_NO.','.$t30.')" style="text-decoration: none;color: black;">'.number_format(ceil($t30)).'</a>';
	   $items[$rowno]->TANGGAL31 = '<a href="javascript:void(0)" onclick="info_do(31,'.$items[$rowno]->ITEM_NO.','.$t31.')" style="text-decoration: none;color: black;">'.number_format(ceil($t31)).'</a>';
	 }

	 if($items[$rowno]->KETERANGAN == 'C.ARRIVE'){
	   $items[$rowno]->TANGGAL1 =  '<a href="javascript:void(0)" onclick="info_po(1,'.$items[$rowno]->ITEM_NO.','.$t1.')" style="text-decoration: none;color: black;">'.number_format(ceil($t1)).'</a>';
	   $items[$rowno]->TANGGAL2 =  '<a href="javascript:void(0)" onclick="info_po(2,'.$items[$rowno]->ITEM_NO.','.$t2.')" style="text-decoration: none;color: black;">'.number_format(ceil($t2)).'</a>';
	   $items[$rowno]->TANGGAL3 =  '<a href="javascript:void(0)" onclick="info_po(3,'.$items[$rowno]->ITEM_NO.','.$t3.')" style="text-decoration: none;color: black;">'.number_format(ceil($t3)).'</a>';
	   $items[$rowno]->TANGGAL4 =  '<a href="javascript:void(0)" onclick="info_po(4,'.$items[$rowno]->ITEM_NO.','.$t4.')" style="text-decoration: none;color: black;">'.number_format(ceil($t4)).'</a>';
	   $items[$rowno]->TANGGAL5 =  '<a href="javascript:void(0)" onclick="info_po(5,'.$items[$rowno]->ITEM_NO.','.$t5.')" style="text-decoration: none;color: black;">'.number_format(ceil($t5)).'</a>';
	   $items[$rowno]->TANGGAL6 =  '<a href="javascript:void(0)" onclick="info_po(6,'.$items[$rowno]->ITEM_NO.','.$t6.')" style="text-decoration: none;color: black;">'.number_format(ceil($t6)).'</a>';
	   $items[$rowno]->TANGGAL7 =  '<a href="javascript:void(0)" onclick="info_po(7,'.$items[$rowno]->ITEM_NO.','.$t7.')" style="text-decoration: none;color: black;">'.number_format(ceil($t7)).'</a>';
	   $items[$rowno]->TANGGAL8 =  '<a href="javascript:void(0)" onclick="info_po(8,'.$items[$rowno]->ITEM_NO.','.$t8.')" style="text-decoration: none;color: black;">'.number_format(ceil($t8)).'</a>';
	   $items[$rowno]->TANGGAL9 =  '<a href="javascript:void(0)" onclick="info_po(9,'.$items[$rowno]->ITEM_NO.','.$t9.')" style="text-decoration: none;color: black;">'.number_format(ceil($t9)).'</a>';
	   $items[$rowno]->TANGGAL0 =  '<a href="javascript:void(0)" onclick="info_po(10,'.$items[$rowno]->ITEM_NO.','.$t0.')" style="text-decoration: none;color: black;">'.number_format(ceil($t0)).'</a>';
	   $items[$rowno]->TANGGAL11 = '<a href="javascript:void(0)" onclick="info_po(11,'.$items[$rowno]->ITEM_NO.','.$t11.')" style="text-decoration: none;color: black;">'.number_format(ceil($t11)).'</a>';
	   $items[$rowno]->TANGGAL12 = '<a href="javascript:void(0)" onclick="info_po(12,'.$items[$rowno]->ITEM_NO.','.$t12.')" style="text-decoration: none;color: black;">'.number_format(ceil($t12)).'</a>';
	   $items[$rowno]->TANGGAL13 = '<a href="javascript:void(0)" onclick="info_po(13,'.$items[$rowno]->ITEM_NO.','.$t13.')" style="text-decoration: none;color: black;">'.number_format(ceil($t13)).'</a>';
	   $items[$rowno]->TANGGAL14 = '<a href="javascript:void(0)" onclick="info_po(14,'.$items[$rowno]->ITEM_NO.','.$t14.')" style="text-decoration: none;color: black;">'.number_format(ceil($t14)).'</a>';
	   $items[$rowno]->TANGGAL15 = '<a href="javascript:void(0)" onclick="info_po(15,'.$items[$rowno]->ITEM_NO.','.$t15.')" style="text-decoration: none;color: black;">'.number_format(ceil($t15)).'</a>';
	   $items[$rowno]->TANGGAL16 = '<a href="javascript:void(0)" onclick="info_po(16,'.$items[$rowno]->ITEM_NO.','.$t16.')" style="text-decoration: none;color: black;">'.number_format(ceil($t16)).'</a>';
	   $items[$rowno]->TANGGAL17 = '<a href="javascript:void(0)" onclick="info_po(17,'.$items[$rowno]->ITEM_NO.','.$t17.')" style="text-decoration: none;color: black;">'.number_format(ceil($t17)).'</a>';
	   $items[$rowno]->TANGGAL18 = '<a href="javascript:void(0)" onclick="info_po(18,'.$items[$rowno]->ITEM_NO.','.$t18.')" style="text-decoration: none;color: black;">'.number_format(ceil($t18)).'</a>';
	   $items[$rowno]->TANGGAL19 = '<a href="javascript:void(0)" onclick="info_po(19,'.$items[$rowno]->ITEM_NO.','.$t19.')" style="text-decoration: none;color: black;">'.number_format(ceil($t19)).'</a>';
	   $items[$rowno]->TANGGAL20 = '<a href="javascript:void(0)" onclick="info_po(20,'.$items[$rowno]->ITEM_NO.','.$t20.')" style="text-decoration: none;color: black;">'.number_format(ceil($t20)).'</a>';
	   $items[$rowno]->TANGGAL21 = '<a href="javascript:void(0)" onclick="info_po(21,'.$items[$rowno]->ITEM_NO.','.$t21.')" style="text-decoration: none;color: black;">'.number_format(ceil($t21)).'</a>';
	   $items[$rowno]->TANGGAL22 = '<a href="javascript:void(0)" onclick="info_po(22,'.$items[$rowno]->ITEM_NO.','.$t22.')" style="text-decoration: none;color: black;">'.number_format(ceil($t22)).'</a>';
	   $items[$rowno]->TANGGAL23 = '<a href="javascript:void(0)" onclick="info_po(23,'.$items[$rowno]->ITEM_NO.','.$t23.')" style="text-decoration: none;color: black;">'.number_format(ceil($t23)).'</a>';
	   $items[$rowno]->TANGGAL24 = '<a href="javascript:void(0)" onclick="info_po(24,'.$items[$rowno]->ITEM_NO.','.$t24.')" style="text-decoration: none;color: black;">'.number_format(ceil($t24)).'</a>';
	   $items[$rowno]->TANGGAL25 = '<a href="javascript:void(0)" onclick="info_po(25,'.$items[$rowno]->ITEM_NO.','.$t25.')" style="text-decoration: none;color: black;">'.number_format(ceil($t25)).'</a>';
	   $items[$rowno]->TANGGAL26 = '<a href="javascript:void(0)" onclick="info_po(26,'.$items[$rowno]->ITEM_NO.','.$t26.')" style="text-decoration: none;color: black;">'.number_format(ceil($t26)).'</a>';
	   $items[$rowno]->TANGGAL27 = '<a href="javascript:void(0)" onclick="info_po(27,'.$items[$rowno]->ITEM_NO.','.$t27.')" style="text-decoration: none;color: black;">'.number_format(ceil($t27)).'</a>';
	   $items[$rowno]->TANGGAL28 = '<a href="javascript:void(0)" onclick="info_po(28,'.$items[$rowno]->ITEM_NO.','.$t28.')" style="text-decoration: none;color: black;">'.number_format(ceil($t28)).'</a>';
	   $items[$rowno]->TANGGAL29 = '<a href="javascript:void(0)" onclick="info_po(29,'.$items[$rowno]->ITEM_NO.','.$t29.')" style="text-decoration: none;color: black;">'.number_format(ceil($t29)).'</a>';
	   $items[$rowno]->TANGGAL30 = '<a href="javascript:void(0)" onclick="info_po(30,'.$items[$rowno]->ITEM_NO.','.$t30.')" style="text-decoration: none;color: black;">'.number_format(ceil($t30)).'</a>';
	   $items[$rowno]->TANGGAL31 = '<a href="javascript:void(0)" onclick="info_po(31,'.$items[$rowno]->ITEM_NO.','.$t31.')" style="text-decoration: none;color: black;">'.number_format(ceil($t31)).'</a>';
	 }	 																						 
		$i = $item_no;		$t = $tipe;
		$rowno++;
	}
	
	$result["rows"] = $items;
	echo json_encode($result);
?>