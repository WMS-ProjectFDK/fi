<?php
	session_start();
	include("../connect/conn_kanbansys.php");
	$items = array();		$foot = array();
	$rowno = 0;
	$t_zn = 0;		$t_el = 0;		$t_hvc = 0;
	$t_aq = 0;		$t_ai = 0;		$t_hve = 0;
	$t_pw = 0;		$t_to = 0;		$t_tha = 0;
	$t_th = 0;		$t_a2 = 0;
	
	$date_awal = isset($_REQUEST['mulai']) ? strval($_REQUEST['mulai']) : '';
	$date_akhir = isset($_REQUEST['akhir']) ? strval($_REQUEST['akhir']) : '';
	
	$where ="where date_prod BETWEEN '$date_awal' AND '$date_akhir'";

	$sql = "select type_gel, kanban_no, no_tag, type_zn, qty_zn, qty_aquapec, qty_pw150, qty_th175b, qty_elec, qty_aqupec2,
		act_qty_aqupec, act_qty_pw150, act_qty_th175b, qty_air, act_qty_aqupec2, qty_total, density,worker_id_gel, zw.name, 
		convert(varchar, upto_date_hasil_anode,120) as upto_date_hasil_anode, 
		case when assy_line is null then '' 
         when assy_line = '0' then '' 
	    else assy_line end as assy_line, 
	    case when remark is null then '' 
	         when remark = '0' then '' 
	    else remark end as remark,
		convert(varchar, date_use, 120) as date_use, 
		convert(varchar, date_prod, 120) as date_prod, id as NO_ID
		from ztb_assy_anode_gel a
		inner join ztb_worker zw on a.worker_id_gel = zw.worker_id
		$where
		order by NO_ID asc";
	$data = odbc_exec($connect, $sql);

	while($row = odbc_fetch_object($data)){
		array_push($items, $row);
		$prod_date = $items[$rowno]->date_prod;
		$items[$rowno]->date_prod = str_replace(' ', '<br/>', $prod_date);

		$work_gel = $items[$rowno]->worker_id_gel;
		$items[$rowno]->worker_id_gel = $work_gel.'<br/>'.$items[$rowno]->name;

		$anode_date = $items[$rowno]->upto_date_hasil_anode;
		$items[$rowno]->upto_date_hasil_anode = str_replace(' ', '<br/>', $anode_date);

		$use_date = $items[$rowno]->date_use;
		$items[$rowno]->date_use = str_replace(' ', '<br/>', $use_date);

		$n_zn = $items[$rowno]->qty_zn;
		$n_aq = $items[$rowno]->qty_aquapec;
		$n_pw = $items[$rowno]->qty_pw150;
		$n_th = $items[$rowno]->qty_th175b;
		$n_el = $items[$rowno]->qty_elec;
		$n_a2 = $items[$rowno]->qty_aqupec2;
		$n_ai = $items[$rowno]->qty_air;
		$n_to = $items[$rowno]->qty_total;

		$n_hvc = $items[$rowno]->act_qty_aqupec;
		$n_hve = $items[$rowno]->act_qty_pw150;
		$n_hv2 = $items[$rowno]->act_qty_aqupec2;
		$n_tha = $items[$rowno]->act_qty_th175b;

		$t_zn += $n_zn;
		$t_aq += $n_aq;
		$t_pw += $n_pw;
		$t_th += $n_th;
		$t_el += $n_el;
		$t_a2 += $n_a2;
		$t_ai += $n_ai;
		$t_to += $n_to;

		$t_hvc += $n_hvc;
		$t_hve += $n_hve;
		$t_hv2 += $n_hv2;
		$t_tha += $n_tha;
		
		$rowno++;
	}

	$foot[0]->qty_zn = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_zn,2).'</b></span>';
	$foot[0]->qty_aquapec = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_aq,2).'</b></span>';
	$foot[0]->qty_pw150 = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_pw,2).'</b></span>';
	$foot[0]->qty_th175b = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_th,2).'</b></span>';
	$foot[0]->qty_aqupec2 = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_a2,2).'</b></span>';
	$foot[0]->qty_elec = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_el,2).'</b></span>';
	$foot[0]->qty_air = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_ai,2).'</b></span>';
	$foot[0]->qty_total = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_to,2).'</b></span>';
	
	$foot[0]->act_qty_aqupec = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_hvc,2).'</b></span>';
	$foot[0]->act_qty_pw150 = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_hve,2).'</b></span>';
	$foot[0]->act_qty_aqupec2 = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_hv2,2).'</b></span>';
	$foot[0]->act_qty_th175b = '<span style="color:blue;font-size:12px;"><b>'.number_format($t_tha,2).'</b></span>';

	$result["rows"] = $items;
	$result["footer"] = $foot;
	echo json_encode($result);
?>