<?php
	session_start();
	include("../connect/conn.php");
	$items = array();		$foot = array();
	$rowno = 0;
	$t_zn = 0;		$t_el = 0;		$t_hvc = 0;
	$t_aq = 0;		$t_ai = 0;		$t_hve = 0;
	$t_pw = 0;		$t_to = 0;		$t_tha = 0;
	$t_th = 0;
	
	$date_awal = isset($_REQUEST['mulai']) ? strval($_REQUEST['mulai']) : '';
	$date_akhir = isset($_REQUEST['akhir']) ? strval($_REQUEST['akhir']) : '';
	
	$where ="where to_char(date_prod, 'YYYY-MM-DD HH24:MI:SS') BETWEEN '$date_awal' AND '$date_akhir'";

	$sql = "select * from (
			select type_gel, kanban_no, no_tag, type_zn, qty_zn, 
			qty_aquapec, qty_pw150, qty_th175b, qty_elec,
			act_qty_aqupec, act_qty_pw150, act_qty_th175b,
			qty_air, qty_total, density,worker_id_gel, zw.name, to_char(upto_date_hasil_anode,'DD-MON-YY HH24:MI:SS') as upto_date_hasil_anode, 
			remark, assy_line, to_char(date_use,'DD-MON-YY HH24:MI:SS') as date_use, to_char(date_prod,'DD-MON-YY HH24:MI:SS') as date_prod,
			to_char(date_prod,'YYYYMMDDHH24MISS') as NO_ID
			from ztb_assy_anode_gel a
			inner join ztb_worker zw on a.worker_id_gel = zw.worker_id
			$where
			order by date_prod asc
		) order by NO_ID asc";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$prod_date = $items[$rowno]->DATE_PROD;
		$items[$rowno]->DATE_PROD = str_replace(' ', '<br/>', $prod_date);

		$work_gel = $items[$rowno]->WORKER_ID_GEL;
		$items[$rowno]->WORKER_ID_GEL = $work_gel.'<br/>'.$items[$rowno]->NAME;

		$anode_date = $items[$rowno]->UPTO_DATE_HASIL_ANODE;
		$items[$rowno]->UPTO_DATE_HASIL_ANODE = str_replace(' ', '<br/>', $anode_date);

		$use_date = $items[$rowno]->DATE_USE;
		$items[$rowno]->DATE_USE = str_replace(' ', '<br/>', $use_date);

		$n_zn = $items[$rowno]->QTY_ZN;
		$n_aq = $items[$rowno]->QTY_AQUAPEC;
		$n_pw = $items[$rowno]->QTY_PW150;
		$n_th = $items[$rowno]->QTY_TH175B;
		$n_el = $items[$rowno]->QTY_ELEC;
		$n_ai = $items[$rowno]->QTY_AIR;
		$n_to = $items[$rowno]->QTY_TOTAL;

		$n_hvc = $items[$rowno]->ACT_QTY_AQUPEC;
		$n_hve = $items[$rowno]->ACT_QTY_PW150;
		$n_tha = $items[$rowno]->ACT_QTY_TH175B;

		$t_zn += $n_zn;
		$t_aq += $n_aq;
		$t_pw += $n_pw;
		$t_th += $n_th;
		$t_el += $n_el;
		$t_ai += $n_ai;
		$t_to += $n_to;

		$t_hvc += $n_hvc;
		$t_hve += $n_hve;
		$t_tha += $n_tha;
		
		$rowno++;
	}

	$foot[0]->QTY_ZN = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_zn,2).'</b></span>';
	$foot[0]->QTY_AQUAPEC = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_aq,2).'</b></span>';
	$foot[0]->QTY_PW150 = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_pw,2).'</b></span>';
	$foot[0]->QTY_TH175B = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_th,2).'</b></span>';
	$foot[0]->QTY_ELEC = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_el,2).'</b></span>';
	$foot[0]->QTY_AIR = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_ai,2).'</b></span>';
	$foot[0]->QTY_TOTAL = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_to,2).'</b></span>';
	
	$foot[0]->ACT_QTY_AQUPEC = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_hvc,2).'</b></span>';
	$foot[0]->ACT_QTY_PW150 = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_hve,2).'</b></span>';
	$foot[0]->ACT_QTY_TH175B = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_tha,2).'</b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;
	echo json_encode($result);
?>