<?php
	error_reporting(0);
	//ini_set('MAX_EXECUTION_TIME', -1);
	set_time_limit(0);
	session_start();
	$cmb_type = isset($_REQUEST['cmb_type']) ? strval($_REQUEST['cmb_type']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';

	$from = isset($_REQUEST['from']) ? strval($_REQUEST['from']) : '';

	if ($from == 'MRP') {
		if ($cmb_type!=''){
			$ty = "c.item = '$cmb_type' AND ";
		}else{
			$ty = "";
		}

		if($cmb_item_no!=''){
			$it = "a.item_no = $cmb_item_no AND ";
		}else{
			$it = "";
		}
	}else{
		if ($cmb_type!=''){
			$ty = "b.item_type = '$cmb_type' AND ";
		}else{
			$ty = "";
		}

		if($cmb_item_no!=''){
			$it = "a.item_no = $cmb_item_no AND ";
		}else{
			$it = "";
		}
	}

	include("../connect/conn.php");

	$where = "where $ty $it a.item_no is not null";

	$qry =  "select aa.*, bb.purchase_leadtime from
		(
		select a.min_days as MAX, a.max_days as MIN, b.*, c.item, c.description as item_description,unit_pl from ztb_config_rm a 
		inner join ztb_mrp_data b on a.item_no=b.item_no 
		inner join item c on a.item_no=c.item_no
    	inner join unit u on c.uom_q = u.unit_code 
		$where
		)aa
		left outer join
		(select distinct max(line_no), item_no,purchase_leadtime from itemmaker group by item_no,purchase_leadtime)bb on aa.item_no=bb.item_no
		order by aa.item_no, aa.no_id";
	$data_qry = oci_parse($connect, $qry);
	oci_execute($data_qry);

	$items = array();		$minArr = array();			$maxArr = array();
	$rowno=0;
	$no=1;
	$nilai = 'N_';		$itm = "";

	while ($row = oci_fetch_object($data_qry)) {
		array_push($items, $row);
		$id = intval($items[$rowno]->NO_ID);
		$desc = strtoupper($items[$rowno]->DESCRIPTION);
		$items[$rowno]->DESCRIPTION = '<b>'.$desc.'</b>';

		if($id == 1){												//DAILY CONSUMPTION PLAN
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format(ceil($n))." ".$x;
				$items[$rowno]->$v = '<a href="javascript:void(0)" onclick="info_assy('.$i.','.$items[$rowno]->ITEM_NO.','.$n.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
			}
		}
		//sett title (info remain date di PRF)
		if($id == 2){												//ARRIVAL FI PLAN
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n)." ".$x;
				$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="infoPO('.$i.','.$items[$rowno]->ITEM_NO.','.$n.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
			}
		}
		//sett title (info ETA di PO)
		if($id == 3){												//PURCHASE PLAN
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n)." ".$x;
				if($n == 0 or is_null($n)){
					if($i <= $items[$rowno]->PURCHASE_LEADTIME){
						$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="addPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: red;">'.$nx.'<br/>OUT OF<br/>LEADTIME</a>';
					}else{
						$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="addPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
					}
				}else{
					$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="infoPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
				}
			}
		}

		if($id == 4){											//INVENTORY
			$items[$rowno]->DESCRIPTION = '<a href="javascript:void(0)" onclick="info_ost('.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;"><b>'.substr($desc, 0,24).'<br/>'.substr($desc, 25,-1).'</a>';
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$items[$rowno]->$v = number_format($n);
			}
		}

		if($id == 5){												//MIN
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$items[$rowno]->$v = $items[$rowno]->MIN;
			}
		}

		if($id == 6){												//ITO
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = intval($items[$rowno]->$v);

				if($n > $items[$rowno]->MAX){
					if($n <= $items[$rowno]->MIN){
						$a_hari = '<span style="color:white;color:green;font-size:11px;"><b>'.number_format(ceil($n)).'</b></span>';
					}else{
						$a_hari = '<span style="color:white;color:blue;font-size:11px;"><b>'.number_format(ceil($n)).'</b></span>';
					}	
				}else{
					$a_hari = '<span style="color:white;color:red;font-size:11px;"><b>'.number_format(ceil($n)).'</b></span>';	
				}

				$items[$rowno]->$v = $a_hari;
			}
		}

		if($id == 7){												//MAX
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$items[$rowno]->$v = $items[$rowno]->MAX;
			}
		}

		if($id == 8){												//QTY ITO/DAY
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n)." ".$x;
				$items[$rowno]->$v = $nx;
			}
		}

		if($itm == $items[$rowno]->ITEM_NO){
			$items[$rowno]->ITEM_DESCRIPTION = "<p><br/></p>";
		}else{
			$items[$rowno]->ITEM_DESCRIPTION = '<b>'.$items[$rowno]->ITEM_NO.'<br/>'.$items[$rowno]->ITEM_DESCRIPTION.'</b>';
			$items[$rowno]->NO = $no++;
		}

		$itm = $items[$rowno]->ITEM_NO;
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);	
?>