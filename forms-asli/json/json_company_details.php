<?php
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	include("../../connect/conn.php");
	header("Content-type: application/json");
	
	$sql = "select poh.supplier_code,nvl(poh.di_output_type,0) di_output_type,poh.section_code,poh.fdk_person_code,poh.attn,poh.cc,poh.po_no,
		to_char(poh.po_date,'dd-mm-yyyy') po_date,poh.curr_code,poh.req requestor,ltrim(to_char(poh.ex_rate,'99990.000000')) ex_rate,
		poh.tterm,poh.pby,nvl(com.pdays, poh.pdays) pdays,nvl(com.pdesc, poh.pdesc) pdesc, poh.revise,poh.date_type,poh.shipto_code,replace(poh.remark1,chr(13),'') remark1,
		replace(poh.marks1,chr(13),'') shipping_mark,replace(poh.reason1,chr(13),'') reason,poh.amt_o total_amount,poh.transport,
		com.company_code, com.company, com.country_code, cnt.country
		from po_header poh
		inner join company com on poh.supplier_code=com.company_code
		inner join country cnt on com.country_code=cnt.country_code
		where poh.supplier_code = $id and 
		poh.po_no =(select max(po_no) from po_header where supplier_code=poh.supplier_code and po_date=(select max(po_date) from po_header 
		where supplier_code=poh.supplier_code))";
	$result = oci_parse($connect, $sql);
	oci_execute($result);
	$arrData = array();
	$arrNo = 0;
	while ($row=oci_fetch_object($result)){
		array_push($arrData, $row);
		$crc = $row->CURR_CODE;
		if(intval($crc)!=0){
			$qry="select coalesce(rate,0) as rate from ex_rate where curr_code='".$crc."' and ex_date=(select max(ex_date) from ex_rate where curr_code='".$crc."')";
			$data = oci_parse($connect,$qry);
			oci_execute($data);
			$dt = oci_fetch_object($data);
			$rate = $dt->RATE;
			$row->EX_RATE = $rate;
		}else{
			$row->EX_RATE = '';
		}
		$arrNo++;
	}
	echo json_encode($arrData);
?>