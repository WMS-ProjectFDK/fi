<?php
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	
	$sql = "select com.company_code supplier_code,coalesce(poh.di_output_type,0) di_output_type,poh.section_code,poh.fdk_person_code,com.attn,poh.cc,
	poh.po_no,cast(poh.po_date as varchar(10)) as po_date,com.curr_code,poh.req requestor,isnull(poh.ex_rate,1) ex_rate,
	com.tterm,poh.pby,coalesce(com.pdays, poh.pdays) pdays,coalesce(com.pdesc, poh.pdesc) pdesc, poh.revise,poh.date_type,
	poh.shipto_code,replace(poh.remark1,'char(13)','') remark1,replace(poh.marks1,'char(13)','') shipping_mark,replace(poh.reason1,'char(13)','') reason,
	poh.amt_o total_amount,poh.transport, com.company_code, com.company, com.country_code, cnt.country
	from company com  
	left join po_header poh on poh.supplier_code=com.company_code
	left join country cnt on com.country_code=cnt.country_code
	where com.company_code = $id
	order by po_date desc ";
	$result = sqlsrv_query($connect, strtoupper($sql));
	$arrData = array();
	$arrNo = 0;
	while ($row=sqlsrv_fetch_object($result)){
		array_push($arrData, $row);
		$crc = $row->CURR_CODE;
		if(intval($crc)!=0){
			$qry="select coalesce(rate,0) as rate from ex_rate 
				where curr_code='".$crc."' 
				and ex_date=(select max(ex_date) from ex_rate where curr_code='".$crc."')";
			$data = sqlsrv_query($connect,strtoupper($qry));
			$dt = sqlsrv_fetch_object($data);
			$rate = $dt->RATE;
			$row->ex_rate = $rate;
		}else{
			$row->EX_RATE = '';
		}
		$arrNo++;
	}
	echo json_encode($arrData);

?>