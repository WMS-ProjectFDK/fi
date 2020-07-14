<?php
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	
	$sql = "select a.company_code, a.company, a.attn, a.country_code, b.country, a.pdays, a.pdesc, a.tterm, coalesce(a.curr_code,0) as curr_code 
    	from company a 
		inner join country b on a.country_code=b.country_code
		where a.company_code='$id'";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		$crc = $row->CURR_CODE;
		if(intval($crc)!=0){
			$qry="select coalesce(rate,0) as rate from ex_rate where curr_code='".$crc."' and ex_date=(select max(ex_date) from ex_rate where curr_code='".$crc."')";
			$data = oci_parse($connect,$qry);
			oci_execute($data);
			$dt = oci_fetch_object($data);
			$rate = $dt->RATE;
		}else{
			$row->CURR_CODE = '';
			$rate = '';
		}

		$arrData[$arrNo] = array(
			"company_code"=>rtrim($row->COMPANY_CODE), 
			"company"=>rtrim($row->COMPANY),
			"attn"=>rtrim($row->ATTN),
			"country_code"=>rtrim($row->COUNTRY_CODE),
			"country"=>rtrim($row->COUNTRY),
			"pday"=>rtrim($row->PDAYS),
			"pdesc"=>rtrim($row->PDESC),
			"tterm"=>rtrim($row->TTERM),
			"curr"=>rtrim($row->CURR_CODE),
			"x_rate"=>rtrim($rate)
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>