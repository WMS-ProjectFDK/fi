<?php
    error_reporting(0);
	session_start();
	$result = array();

    $wo_no = isset($_REQUEST['wo_no']) ? strval($_REQUEST['wo_no']) : '';
	$qty = isset($_REQUEST['qty']) ? strval($_REQUEST['qty']) : '';
	$tahun = date('Y');
	$bulan = date('n');
	//23560-ENERGIZER COR EU LR6 BULK-0
	$split_brand = explode('-',$brand);

	include("../../connect/conn.php");
	$rowno=0;

	$rs = "select st.lower_item_no as item_no,i.item,i.description item_name, 
		CEILING((round((quantity * $qty) / quantity_base,0) + 
		CEILING((quantity * $qty) / quantity_base  * failure_rate/100)) / isnull(bundle_qty,1)) * isnull(bundle_qty,1) 
		as QTY_REQ,isnull(zz.qty,0) QTY_ON_BOOK, isnull(w.this_inventory,0) as QTY_WAREHOUSE,
		case when lower_item_no in ('2116095'	,'2116102'	,'2126023'	,'2126036'	,'2126040'	,'2126041'	,'2126052'	,'2145001'	,'2145008'	
								   ,'2150002'	,'2150003'	,'2150007'	,'2150009'	,'2216120'	,'2216215'	,'2226017'	,'2226079'	,'2226080'	
								   ,'2245006'	,'2245012'	,'2245013'	,'2245036'	,'2250001'	,'2250002'	,'2250012'	,'2250022'	,'2250023'	
								   ,'2250024'	,'2250025'	,'2250026'	,'2250027'	,'2317002'	,'2322002'	,'2600001'	,'2600006'	,'2600007'	
								   ,'2600009'	,'2600053'	,'2600068'	,'2600164'	,'2600183'  ,'12600165' ,'2150012'  ,'2150013'  ,'2150010'
								   ,'2116013'	,'2216048'  ,'2116012')
		    then '1' else '0' end ign
	    from (select * from structure s
              inner join (select max(level_no) level_nos, upper_item_no upper 
                          from structure 
                          group by upper_item_no
                         ) ss on s.upper_item_no = ss.upper and s.level_no = ss.level_nos
        ) st
		inner join mps_header r on st.upper_item_no = r.item_no
	    left join item i on st.lower_item_no = i.item_no
	    left join item i_u on st.upper_item_no = i_u.item_no
	    left join unit u on i.uom_q = u.unit_code
	    left join whinventory w on i.item_no = w.item_no
		left join (select item_no,sum(qty) qty from ztb_item_book group by item_no) zz on i.item_no = zz.item_no
		left outer join ztb_safety_stock c on st.lower_item_no = c.item_no and period = $bulan and year = '$tahun'
	    where work_order = '$wo_no'
		and st.lower_item_no = i.item_no 
		and lower_item_no < '9999999' 
		and lower_item_no > 2000000
		order by st.line_no";
	
	$data = sqlsrv_query($connect, strtoupper($rs));
	if( $data === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
				echo $rs;
			}
		}
	}
	

	$items = array();
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);
		$stock = $items[$rowno]->QTY_WAREHOUSE - $items[$rowno]->QTY_ON_BOOK;
		$pesan = $items[$rowno]->QTY_REQ;
		if($pesan == 0){
			$pesan = 1;
		}
		$items[$rowno]->QTY_REQ = $pesan;
		if ($items[$rowno]->IGN == 0){
			if($stock >= $pesan){
				$items[$rowno]->STS = '<a   style="text-decoration: none; color: blue;">STOCK AVAILABLE</a>';
				$items[$rowno]->RSTS = 'STOCK AVAILABLE';
			}
			else{
				$items[$rowno]->STS = '<a   style="text-decoration: none; color: red;">STOCK SHORTAGE</a>';
				$items[$rowno]->RSTS = 'STOCK SHORTAGE';
			
			}
		}else{
			$items[$rowno]->STS = '<a   style="text-decoration: none; color: green;">IGNORED</a>';
			$items[$rowno]->RSTS = 'IGNORED';
		}

		$e = $items[$rowno]->QTY_ON_BOOK;
		$i = $items[$rowno]->ITEM_NO;
		$items[$rowno]->QTY_ON_BOOK = '<a href="javascript:void(0)" title="'.$e.'" onclick="info_qty_book('.$i.')"  style="text-decoration: none; color: blue;">'.number_format($e).'</a>';
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>