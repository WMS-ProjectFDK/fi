<?php
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	
	$sql = "select aa.*, bb.*,  substr(tterm, 0, 3) term from (
		select cus.company_code, cus.company, cus.country_code, cus.attn, con.country, count(ct.company_code) jum_contract
		from company cus
		inner join country con on cus.country_code = con.country_code
		inner join contract ct on cus.company_code = ct.company_code
		where cus.company_code ='$id'
		group by cus.company_code, cus.company, cus.country_code, cus.attn, con.country) aa
		left OUTER join (
		select ct.company_code, ct.contract_seq, ct.curr_code, ct.tterm, ct.pmethod, ct.pdays, ct.pdesc, ct.loading_port, 
		ct.discharge_port, ct.final_dest, ct.port_loading_code, ct.port_discharge_code, ct.final_destination_code
		from contract ct 
		where ct.company_code = '$id' AND ct.contract_seq=1) bb on aa.company_code = bb.company_code";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();		$arrContract = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		array_push($arrData, $row);
		$arrNo++;
	}
	echo json_encode($arrData);
?>