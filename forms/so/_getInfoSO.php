<?php
    header("Content-type: application/json");
	session_start();
	$items = array();       $items_plt = array();   $items_case = array();
	$rowno=0;

	$so = isset($_REQUEST['so']) ? strval($_REQUEST['so']) : '';
	$line = isset($_REQUEST['line']) ? strval($_REQUEST['line']) : '';
    $for = isset($_REQUEST['for']) ? strval($_REQUEST['for']) : '';

	include("../../connect/conn.php");

    $rs = "select SO_NO, LINE_NO, CUSTOMER_PART_NO, ITEM_NO, ORIGIN_CODE, QTY, UOM_Q, U_PRICE, AMT_O, AMT_L, 
        CONVERT(varchar(10),ETD,120) as etd, DEL_QTY,SRET_QTY, BAL_QTY, CUSTOMER_PO_LINE_NO, 
        CONVERT(varchar(10),CUSTOMER_REQ_DATE,120) as CUSTOMER_REQ_DATE, AGING_DAY,
        DATE_CODE, IN_MPS, ASIN, AMAZON_PO_NO,
        PALLET_MARK_1,
        PALLET_MARK_2,
        PALLET_MARK_3,
        PALLET_MARK_4,
        PALLET_MARK_5,
        PALLET_MARK_6,
        PALLET_MARK_7,
        PALLET_MARK_8,
        PALLET_MARK_9,
        PALLET_MARK_10,
        CASE_MARK_1,
        CASE_MARK_2,
        CASE_MARK_3,
        CASE_MARK_4,
        CASE_MARK_5,
        CASE_MARK_6,
        CASE_MARK_7,
        CASE_MARK_8,
        CASE_MARK_9,
        CASE_MARK_10
        from  so_details
        where SO_NO='$so' and line_no=$line ";
	$data = sqlsrv_query($connect, strtoupper($rs));
	while($row = sqlsrv_fetch_object($data)) {
        array_push($items, $row);

        if ($for == 'pallet') {
            for ($p=1; $p <= 10; $p++) { 
                $l_field = 'PALLET_MARK_'.$p;
                $items_plt[$p-1] = array(
                    "pmark"=>$l_field,
                    "vmark"=>$items[$rowno]->$l_field
                );
            }
        }else if ($for == 'case') {
            for ($c=1; $c <= 10; $c++) { 
                $c_field = 'CASE_MARK_'.$c;
                $items_case[$c-1] = array(
                    "cmark"=>$c_field,
                    "vmark"=>$items[$rowno]->$c_field
                );
            }
        }

		$rowno++;
    }
    
    if ($for == 'pallet') {
        $items = $items_plt;
    }elseif($for == 'case'){
        $items = $items_case;
    }

	echo json_encode($items);
?>