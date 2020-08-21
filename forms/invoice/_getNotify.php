<?php
	include("../../connect/conn.php");
	session_start();
	$result = array();
	$rowno=0;

	$word = isset($_REQUEST['sch']) ? strval($_REQUEST['sch']) : '';

	if($word == ""){
		$where = "where c.country_code = cou.country_code
				  and company_type in (1,2,7) ";
	}else{
		$where = "where c.country_code = cou.country_code
				  and (upper(c.company) like '%$word%' 
     					or cou.country like '%$word%' 
     					or c.company_code like '%$word%'
    				  ) 	
				  and company_type in (1,2,7) ";
	}

	$rs = " select c.company_code, c.company, c.company_type, c.address1, c.address2, c.address3, c.address4, c.country_code, cou.country,
	isnull(c.company,'') + char(10) + isnull(c.address1,'') + char(10) + isnull(c.address2,'') + char(10) + isnull(address3,'') + char(10) + isnull(address4,'') notify 
	from company c, country cou
		$where 
        order by c.company ";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$a1 = $items[$rowno]->ADDRESS1;
		$a2 = $items[$rowno]->ADDRESS2;
		$a3 = $items[$rowno]->ADDRESS3;
		$a4 = $items[$rowno]->ADDRESS4;
		$items[$rowno]->ADDRESS = $a1.'<BR>'.$a2.'<BR>'.$a3.'<BR>'.$a4;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>