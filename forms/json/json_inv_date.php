<?php
	include("../../connect/conn.php");
	header("Content-type: application/json");

    $sts = isset($_REQUEST['stsNya']) ? strval($_REQUEST['stsNya']) : '';
    $arrNo = 0;
    $arrData = array();
    
    if($sts == 'UPDATE'){
        $sql = "select  convert(varchar, do_date, 126) as ex_factory_date_n, convert(varchar, do_date, 106) as ex_factory_date , count(convert(varchar, do_date, 106)) as jum from do_header
        where bl_date is null
        and CONVERT(nvarchar(6),do_date, 112) between (select distinct last_month from WHINVENTORY) and (select distinct this_month from WHINVENTORY)
        group by convert(varchar, do_date, 106), convert(varchar, do_date, 126)";
    }elseif($sts == 'RESTORE'){
        $sql = "select  convert(varchar, do_date, 126) as ex_factory_date_n, convert(varchar, do_date, 106) as ex_factory_date , count(convert(varchar, do_date, 106)) as jum from do_header
        where bl_date is not null
        and CONVERT(nvarchar(6),do_date, 112) between (select distinct last_month from WHINVENTORY) and (select distinct this_month from WHINVENTORY)
        group by convert(varchar, do_date, 106), convert(varchar, do_date, 126)";
    }
    
	$result = sqlsrv_query($connect, strtoupper($sql));
	
	while ($row=sqlsrv_fetch_array($result)){
		$arrData[$arrNo] = array(
            "ex_factory_date"=>rtrim($row[0]),
            "ex_factory_date_text"=>rtrim($row[1]).' ('.rtrim($row[2]).')',
		);
		$arrNo++;
	}
	echo json_encode($arrData);
?>