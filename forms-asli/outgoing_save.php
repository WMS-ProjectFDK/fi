<?php
session_start();
if (isset($_SESSION['id_wms'])){
	$slip = rtrim(@$_REQUEST['slip']);
	$item = rtrim(@$_REQUEST['item']);
	$Gtotal = rtrim(@$_REQUEST['qty']);
	$ln = rtrim(@$_REQUEST['ln']);

	include("../connect/conn.php");

	$cek = "select substr(a.rack,1,4) from ztb_wh_in_det a INNER join gr_header b on a.gr_no = b.gr_no
		where a.item_no='$item' and a.rack is not null and rownum = 1 order by b.gr_date, a.pallet asc";
	$cek_rack = oci_parse($connect, $cek);
	oci_execute($cek_rack);
	$row_rack = oci_fetch_array($cek_rack);

	$r = $row_rack[0];
	if ($r=='RM.C' || $r=='RM.D' || $r=='RM.E' || $r=='RM.F' || $r=='RM.G' || $r=='RM.H' || $r=='RM.I' || $r=='RM.J' || $r=='RM.K' || $r=='RM.L'){
		$rowno=0;

		$rs = "select a.gr_no, a.line_no, a.rack, a.item_no, a.tanggal, a.pallet, a.qty - a.qty_out as QTY, a.id, a.item_no
			from ztb_wh_in_det a inner join gr_header b on a.gr_no= b.gr_no 
			where a.item_no = '$item' and a.qty - a.qty_out > 0 and a.rack is not null 
			order by b.gr_date asc, a.pallet asc";
		$data = oci_parse($connect, $rs);
		oci_execute($data);
		$items = array();
		while($row = oci_fetch_object($data)) {
			$qty = floatval($row->QTY);		$rack = $row->RACK;		$plt = $row->PALLET;
			if($Gtotal>0){
				$t = floatval($Gtotal-$qty);
				if(($t-$qty)<0) {
					$qty_save = $Gtotal;
				}else{
					$qty_save = $qty;
				}
				/*if($t>=$qty){
					$qty_save = $qty;
				}elseif($Gtotal<=$qty){
					$qty_save = $Gtotal;
				}else{
					$qty_save = $t;
				}*/
				$ins = "insert into ztb_wh_do_det(do_no,line_no,qty,print,item_no,rack) values ('$slip','$ln',$qty_save,0,'$item','$rack')";
				$data0 = oci_parse($connect, $ins);
				oci_execute($data0);
			}
			$Gtotal = $t;
		}
	}else{
		$rowno=0;
		$rs = "select a.gr_no, a.line_no, a.rack, a.item_no, a.tanggal, a.pallet, a.qty-a.qty_RESERVE as QTY, a.id, a.item_no
			from ztb_wh_in_det a inner join gr_header b on a.gr_no= b.gr_no
			where a.item_no = '$item' and a.qty - a.qty_RESERVE > 0 and a.rack is not null
			order by b.gr_date asc, a.pallet asc";
		$data = oci_parse($connect, $rs);
		oci_execute($data);
		$items = array();
		while($row = oci_fetch_object($data)){
			$qty = floatval($row->QTY);	$gr = $row->GR_NO;			$lineno_in = $row->LINE_NO;
			$id = $row->ID;				$pallet = $row->PALLET;		$rack = $row->RACK;
			if($Gtotal>0){
				$t = floatval($Gtotal-$qty);
				if(($t-$qty)<0) {
					$qty_save = $Gtotal;
				}else{
					$qty_save = $qty;
				}
				/*$t = floatval($Gtotal-$qty);
				if($t>=$qty){
					$qty_save = $qty;
				}elseif($Gtotal<=$qty){
					$qty_save = $Gtotal;
				}else{
					$qty_save = $t;
				}*/

				$cek_reserve = "select qty_reserve from ztb_wh_in_det where id=$id";
				$jum_r = oci_parse($connect, $cek_reserve);
				oci_execute($jum_r);
				$rsrve = oci_fetch_array($jum_r);
				$t_rsrve = floatval($rsrve[0]) + $qty_save;

				$upd = "update ztb_wh_in_det set qty_reserve = $t_rsrve where id=$id";
				$dataupd = oci_parse($connect, $upd);
				oci_execute($dataupd);

				$ins = "insert into ztb_wh_do_det(do_no,line_no,pallet,qty,print,sticker_id,item_no,rack) values ('$slip','$ln',$pallet,$qty_save,0,$id,'$item','$rack')";
				$data2 = oci_parse($connect, $ins);
				oci_execute($data2);
			}
			$Gtotal = $t;
			$rowno++;
		}
	}

}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}

/*if (isset($_SESSION['id_wms'])){
	$gr_no = rtrim(@$_REQUEST['gr_no']);
	$lineno_in = rtrim(@$_REQUEST['lineno_in']);
	$rack = rtrim(@$_REQUEST['rack']);
	$pallet = rtrim(@$_REQUEST['pallet']);
	$qtyy = rtrim(@$_REQUEST['qtyy']);
	$id = rtrim(@$_REQUEST['id']);
	$lineno_out = rtrim(@$_REQUEST['lineno_out']);
	$slipno = rtrim(@$_REQUEST['slipno']);
	$itemno = rtrim(@$_REQUEST['itemno']);
	$q_slip = rtrim(@$_REQUEST['q_slip']);

	include("../connect/conn.php");*/
	
	/*$cek = "select coalesce(qty_reserve,0) as reserve from ztb_wh_in_det where gr_no='$gr_no' and line_no='$lineno_in' and id=$id and pallet='$pallet'";
	$cekData = oci_parse($connect, $cek);
	oci_execute($cekData);
	$dt = oci_fetch_array($cekData);
	if(intval($dt[0]) == 0 || is_null($dt[0]) || intval($dt[0]) ==''){
		$qtyy = $qtyy;*/
	/*$r = substr($rack,0,4);

	if ($r=='RM.C' || $r=='RM.D' || $r=='RM.E' || $r=='RM.F' || $r=='RM.G' || $r=='RM.H' || $r=='RM.I' || $r=='RM.J' || $r=='RM.K' || $r=='RM.L'){
		$ins = "insert into ztb_wh_do_det(do_no,line_no,qty,print,item_no,rack) values ('$slipno','$lineno_out',$qtyy,0,'$itemno','$rack')";
		$data0 = oci_parse($connect, $ins);
		oci_execute($data0);	
	}else{
		$upd = "update ztb_wh_in_det set qty_reserve = qty_reserve + $qtyy where gr_no='$gr_no' and line_no='$lineno_in' and id=$id and pallet=$pallet";
		$data = oci_parse($connect, $upd);
		oci_execute($data);

		$ins = "insert into ztb_wh_do_det(do_no,line_no,pallet,qty,print,sticker_id,item_no,rack) values ('$slipno','$lineno_out',$pallet,$qtyy,0,$id,'$itemno','$rack')";
		$data2 = oci_parse($connect, $ins);
		oci_execute($data2);	
	}*/
	/*}else{
		$q = $qtyy + intval($dt[0]);
		$upd = "update ztb_wh_in_det set qty_reserve=$q where gr_no='$gr_no' and line_no='$lineno_in' and id=$id and pallet=$pallet";
		$data = oci_parse($connect, $upd);
		oci_execute($data);
	}*/
/*}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}*/
?>