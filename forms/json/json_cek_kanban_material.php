<?php
session_start();
include("../../connect/conn.php");
header("Content-type: application/json");
$wo_no = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
$pallet = isset($_REQUEST['pallet']) ? strval($_REQUEST['pallet']) : '';

$arrData = array();
$arrNo = 0;




/*
select plt_no,sum( distinct case when plt_no <> plt_tot then pi.pallet_unit_number
else case when qty_order - (floor(qty_order / pallet_unit_number) * pi.pallet_unit_number) = 0 
      then pi.pallet_unit_number
        else  qty_order - (floor(qty_order / pallet_unit_number) * pi.pallet_unit_number) 
        end
end ) QTY_PRINTED
from ztb_m_plan z
inner join item i on  z.item_no = i.item_no
inner join packing_information pi on i.pi_no = pi.pi_no
where wo_no = '$wo_no' and plt_no in ($pallet) and upload = 1
      and wo_no || plt_no not in (select wo_no || plt_no from ztb_item_book where  wo_no = '$wo_no' and plt_no in ($pallet) )
group by plt_no
*/

$sql = "select plt_no,
	  sum(distinct 
	    case when plt_no <> ceiling(qty / pallet_unit_number) then pi.pallet_unit_number
	    else case when qty - (floor(qty / pallet_unit_number) * pi.pallet_unit_number) = 0 then pi.pallet_unit_number
	         else  qty - (floor(qty / pallet_unit_number) * pi.pallet_unit_number) 
	         end
	    end) QTY_PRINTED
	from 
	(
	select distinct cast(plt_no as int) plt_no,wo_no 
	from ztb_m_plan 
	where upload = 1 and wo_no = '$wo_no' and plt_no in ($pallet)
	union all
	select distinct cast(plt_no as int) plt_no, wo_no 
	from ztb_item_book 
	where wo_no = '$wo_no' and plt_no in ($pallet)
	)aa
	inner join mps_header r on r.work_order = aa.wo_no 
	inner join item i on  r.item_no = i.item_no
	inner join packing_information pi on i.pi_no = pi.pi_no
	group by plt_no
";

$str = "";
$result = sqlsrv_query($connect, strtoupper($sql));
if( $result === false ) {
	if( ($errors = sqlsrv_errors() ) != null) {
		foreach( $errors as $error ) {
			echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
			echo "code: ".$error[ 'code']."<br />";
			echo "message: ".$error[ 'message']."<br />";
			echo $sql;
		}
	}
}
$qty = 0;
	$arrData = array();
    $arrNo = 0;
	while ($row=sqlsrv_fetch_array($result)){
		$str .=  ",".rtrim($row[0]) ;
		$qty  = $qty + $row[1];
	}
	$arrData[$arrNo] = array("PLT_NO"=>rtrim($str), "QTY"=>$qty);
	echo json_encode($arrData);
?>