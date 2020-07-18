<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../../connect/conn.php");

	if(date('D')=='Fri'){
		$k = date("l, F d, Y", mktime(0,0,0,date("m"),date("d")+3,date("Y")));
		$hari = "Monday";
		$del_date = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+3,date("Y")));
	}else{
		$k = date("l, F d, Y", mktime(0,0,0,date("m"),date("d")+1,date("Y")));
		$hari = "Tomorrow";
		$del_date = date("Y-m-d", mktime(0,0,0,date("m"),date("d")+1,date("Y")));
	}
	
	$di_vendor = htmlspecialchars($_REQUEST['di_vendor']);
	$di_attn = htmlspecialchars($_REQUEST['di_attn']);
	$di_no = htmlspecialchars($_REQUEST['di_no']);
	$di_date = htmlspecialchars($_REQUEST['di_date']);
	$di_line = htmlspecialchars($_REQUEST['di_line']);
	$di_req = htmlspecialchars($_REQUEST['di_req']);
	// $di_po = htmlspecialchars($_REQUEST['di_po']);
	// $di_po_line = htmlspecialchars($_REQUEST['di_po_line']);
	$di_item = htmlspecialchars($_REQUEST['di_item']);
	$di_org_code = htmlspecialchars($_REQUEST['di_org_code']);
	$di_qty = htmlspecialchars($_REQUEST['di_qty']);
	$di_uomq = htmlspecialchars($_REQUEST['di_uomq']);
	$di_unit = htmlspecialchars($_REQUEST['di_unit']);

	$now=date('Y-m-d H:i:s');
	$user = $_SESSION['id_wms'];
	$now2=date('Y-m-d');

	//CEK mte_HEADER
	$cek = "select count(*) as jum_do from di_header where di_no='$di_no'";
	$data = sqlsrv_query($connect, strtoupper($cek));
	$dt = sqlsrv_fetch_object($data);

	if($dt->JUM_DO == 0){
		// INSERT DI_HEADER
		$ins1 = "insert into di_header(shipto_code,
										   section_code,
										   di_no,
										   di_date,
										   attn,
										   req,
										   person_code,
										   upto_date,
										   reg_date,
										   sec)
										values($di_vendor,
											100,
											'$di_no',
											'$di_date',
											'$di_attn',
											'$di_req',
											'$user',
											'$now2',
											'$di_date',
											'Purchasing & EXIM Manager')";
        $data_ins1 = sqlsrv_query($connect, $ins1);
        // echo $ins1;
	}

	if($di_unit=='BUNDLE'){

	}

	$qry = "select distinct a.po_no, a.po_date, b.line_no, b.item_no,c.item, c.description, 
        case d.sts_bundle when 'Y' then round(coalesce(b.qty/d.bundle_qty,0),0) else b.qty end as qty, 
        case d.sts_bundle when 'Y' then round(coalesce(b.gr_qty/d.bundle_qty,0),0) else b.gr_qty end as gr_qty, 
        case d.sts_bundle when 'Y' then round(coalesce(b.qty/d.bundle_qty,0),0)-round(coalesce(b.gr_qty/d.bundle_qty,0),0) else b.qty-b.gr_qty end as balance, b.schedule
        from po_header a
        inner join po_details b on a.po_no=b.po_no
        inner join item c on b.item_no=c.item_no
        inner join ztb_safety_stock d on b.item_no= d.item_no
        where a.supplier_code='$di_vendor' 
        and b.item_no='$di_item' 
        and b.qty-b.gr_qty > 0 
        and a.po_date >= CAST('2016-01-01' as date)
		order by a.po_date asc, b.line_no asc";
	$data_qry = sqlsrv_query($connect, strtoupper($qry));
	$TOTAL = $di_qty;
	while ($dt_qry = sqlsrv_fetch_object($data_qry)) {
		if(floatval($TOTAL)>0) {
			$t = floatval($TOTAL) - floatval($dt_qry->BALANCE);
			if(floatval($t)>0){
				$ins2 = "insert into di_details (di_no,
													line_no,
													po_no,
													po_line_no,
													item_no,
													origin_code,
													qty,
													uom_q,
													data_date,
													supplier_code,
													reg_date)
												values('$di_no',
														$di_line,
														'".$dt_qry->PO_NO."',
														".$dt_qry->LINE_NO.",
														".$dt_qry->ITEM_NO.",
														'$di_org_code',
														".$dt_qry->BALANCE.",
														'$di_uomq',
														'$del_date',
														$di_vendor,
														'$now2'
														)";
				$data_ins2 = sqlsrv_query($connect, $ins2);
			}else{
				$ins2 = "insert into di_details (di_no,
													line_no,
													po_no,
													po_line_no,
													item_no,
													origin_code,
													qty,
													uom_q,
													data_date,
													supplier_code,
													reg_date)
												values('$di_no',
														$di_line,
														'".$dt_qry->PO_NO."',
														".$dt_qry->LINE_NO.",
														".$dt_qry->ITEM_NO.",
														'$di_org_code',
														$TOTAL,
														'$di_uomq',
														'$del_date',
														$di_vendor,
														'$now2'
														)";
				$data_ins2 = sqlsrv_query($connect, $ins2);
			}
		}
		$TOTAL = $t;
	}
	$TOTAL=0;
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>