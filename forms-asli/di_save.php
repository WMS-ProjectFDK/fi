<?php
session_start();
if (isset($_SESSION['id_wms'])){
	include("../connect/conn.php");

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
	$di_po = htmlspecialchars($_REQUEST['di_po']);
	$di_po_line = htmlspecialchars($_REQUEST['di_po_line']);
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
	$data = oci_parse($connect, $cek);
	oci_execute($data);
	$dt = oci_fetch_object($data);

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
											TO_DATE('$di_date','yyyy-mm-dd'),
											'$di_attn',
											'$di_req',
											'$user',
											TO_DATE('$now2','yyyy-mm-dd'),
											TO_DATE('$di_date','yyyy-mm-dd'),
											'Purchasing & EXIM Manager')";
		$data_ins1 = oci_parse($connect, $ins1);
		oci_execute($data_ins1);
	}

	if($di_unit=='BUNDLE'){

	}

	$qry = "select distinct a.po_no, a.po_date, b.line_no, b.item_no,c.item, c.description, 
		case d.sts_bundle when 'Y' then round(b.qty/d.bundle_qty) else b.qty end as qty, 
    	case d.sts_bundle when 'Y' then round(b.gr_qty/d.bundle_qty) else b.gr_qty end as gr_qty, 
    	case d.sts_bundle when 'Y' then (round(b.qty/d.bundle_qty)-round(b.gr_qty/d.bundle_qty)) else b.qty-b.gr_qty end as balance, b.schedule
		from po_header a
		inner join po_details b on a.po_no=b.po_no
		inner join item c on b.item_no=c.item_no
		inner join ztb_safety_stock d on b.item_no= d.item_no
		where a.supplier_code='$di_vendor' and b.item_no='$di_item' and b.qty-b.gr_qty > 0 and to_char(a.po_date,'yyyy-mm-dd') >= '2016-01-01'
		order by a.po_date asc, b.line_no asc";
	$data_qry = oci_parse($connect, $qry);
	oci_execute($data_qry);
	$TOTAL = $di_qty;
	while ($dt_qry = oci_fetch_object($data_qry)) {
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
														TO_DATE('$del_date','yyyy-mm-dd'),
														$di_vendor,
														TO_DATE('$now2','yyyy-mm-dd')
														)";
				$data_ins2 = oci_parse($connect, $ins2);
				oci_execute($data_ins2);
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
														TO_DATE('$del_date','yyyy-mm-dd'),
														$di_vendor,
														TO_DATE('$now2','yyyy-mm-dd')
														)";
				$data_ins2 = oci_parse($connect, $ins2);
				oci_execute($data_ins2);
			}
		}
		$TOTAL = $t;
	}
	$TOTAL=0;
	//INSERT DI_DETAILS
	
	/*if($di_po!=''){
		$ins2 = "insert into ztb_di_details (di_no,
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
												'$di_po',
												$di_po_line,
												$di_item,
												'$di_org_code',
												$di_qty,
												'$di_uomq',
												TO_DATE('$now2','yyyy-mm-dd'),
												$di_vendor,
												TO_DATE('$now2','yyyy-mm-dd')
												)";
		$data_ins2 = oci_parse($connect, $ins2);
		oci_execute($data_ins2);

		if ($data_ins2){
	        echo json_encode("Success");
	    }else{
	        echo json_encode(array('errorMsg'=>'Error'));
	    }	
	}*/
}else{
	echo json_encode(array('errorMsg'=>'Session Expired'));
}
?>