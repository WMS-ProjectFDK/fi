<?php
	session_start();
	$result = array();
	$answer = isset($_REQUEST['answer']) ? strval($_REQUEST['answer']) : '';
	include("../../connect/conn.php");

	$rowno=0;
	$rs = "select sod.pallet_mark_1, sod.pallet_mark_2, sod.pallet_mark_3, sod.pallet_mark_4, sod.pallet_mark_5,
		sod.pallet_mark_6, sod.pallet_mark_7, sod.pallet_mark_8, sod.pallet_mark_9, sod.pallet_mark_10,
		'P/NO. '||'1 - '|| ceil(ans.qty/ ztb_item.pallet_pcs) as pallet_mark_11
		from answer ans 
		left join so_details sod on ans.so_no=sod.so_no and ans.so_line_no=sod.line_no
		left join ztb_shipping_ins sins on ans.answer_no = sins.answer_no
		left join ztb_item on sins.item_no = ztb_item.item_no
		where ans.answer_no='$answer'";
	$data = sqlsrv_query($connect, strtoupper($rs));
	$items = array();
    $row = sqlsrv_fetch_object($data);
    
    for ($i=1; $i <= 11; $i++) {
        $r = 'PALLET_MARK_'.$i;
        $row_plt = $row->$r;
        if (! is_null($row_plt)){
            array_push($items, $row_plt);
        }
    }
	for ($j=0; $j < count($items) ; $j++){
		$result[$j]	 = array("NO"=>$j+1, "HASIL" => $items[$j]);
	}
	echo json_encode($result);
?>