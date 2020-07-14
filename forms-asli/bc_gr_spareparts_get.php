<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	include("../connect/conn2.php");
	$user = $_SESSION['id_wms'];

	$date_gr = "to_char(gh.gr_date, 'yyyy-mm-dd') between '$date_awal' and '$date_akhir'  ";

	$sql = "select * from (
		select  gh.bc_doc, gh.bc_no, gh.gr_date, gh.gr_no, c.company, cc.curr_mark, sum(gh.amt_o) as AMT_O
		from gr_header gh
		inner join company c on gh.supplier_code = c.company_code
		inner join currency cc on gh.curr_code = cc.curr_code
		where $date_gr
		group by gh.bc_doc, gh.bc_no, gh.gr_date, gh.gr_no, c.company, cc.curr_mark
		) order by company, bc_no, gr_no";
	$data_sql = oci_parse($connect, $sql);
	oci_execute($data_sql);

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;

	while($row = oci_fetch_object($data)){
		// if($rowno <= 12){
			array_push($items, $row);
			$c = $items[$rowno]->CURR_MARK;
			$a = $items[$rowno]->AMT_O;
			$items[$rowno]->AMT = $c.' '.number_format($a,5);

			$i = $items[$rowno]->ITEM_NO;
			$d = trim(str_replace("'", " - ", $items[$rowno]->ITEM_DESC));
			$items[$rowno]->ITEM_NYA = $i.' - '.$d;

			$rowno++;
		// }
	}
	
	$fp = fopen('bc_gr_spareparts_result_'.$user.'.json', 'w');
	fwrite($fp, json_encode($items));
	fclose($fp);

	$result["rows"] = $items;
	echo json_encode($result);	
?>