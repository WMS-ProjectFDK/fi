<?php
	session_start();
	error_reporting(0);
	set_time_limit(0);
	include("../connect/conn.php");
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$from = isset($_REQUEST['from']) ? strval($_REQUEST['from']) : '';
	$sts = isset($_REQUEST['sts']) ? strval($_REQUEST['sts']) : '';

	if ($sts == 'lower'){
		if($cmb_item_no!=''){
			$it = "a.item_no = $cmb_item_no AND ";
		}else{
			$it = "";
		}	
	}else{
		if($cmb_item_no!=''){
			$it = "a.item_no in (select distinct case when lower_item_no > 70000000 then lower_item_no - 70000000 else lower_item_no end from structure where upper_item_no=$cmb_item_no
				and level_no = (select max(level_no) from structure where upper_item_no=$cmb_item_no)) AND ";
		}else{
			$it = "";
		}
	}
	

	//cek item to DI
	$filename = "json/json_item_to_DI_MRP.json";
	$string = file_get_contents($filename);
	$json_a = json_decode($string, true);
	$itemNya = '';

	foreach ($json_a as $item => $item_a) {
	    if ($item_a['item_no'] == $cmb_item_no){
	    	$itemNya .= 'Y';
	    }
	}
	// end item to DI

	//cek balance
	$cek = "select nvl(sum(bal_qty),0) as balance from po_details where item_no=$cmb_item_no and eta <= (select sysdate from dual)
		and bal_qty != 0";
	$data_cek = oci_parse($connect, $cek);
	oci_execute($data_cek);
	$dt_cek = oci_fetch_object($data_cek);
	$b = ' (BALANCE : '.number_format($dt_cek->BALANCE).')';

	$where = "where $it a.item_no is not null";

	$qry =  "select aa.*, bb.purchase_leadtime from
		(
		select a.*, c.item, c.description as item_description,unit_pl from ztb_mrp_data_pck a 
		inner join item c on a.item_no=c.item_no
	    inner join unit u on c.uom_q = u.unit_code
		$where
		)aa
		left outer join
		(select distinct max(line_no), item_no,max(purchase_leadtime) purchase_leadtime from itemmaker group by item_no)bb on aa.item_no=bb.item_no
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
		
		if ($desc == 'PURCHASE'){
			$items[$rowno]->DESCRIPTION = '<b>'.$desc.$b.'</b>';
		}else{
			$items[$rowno]->DESCRIPTION = '<b>'.$desc.'</b>';	
		}

		if($id == 1){												//PLAN
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format(ceil($n))." ".$x;
				$items[$rowno]->$v = '<a href="javascript:void(0)" onclick="info_assy('.$i.','.$items[$rowno]->ITEM_NO.','.$n.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
			}
		}

		if($id == 2){												//ARRIVE
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n)." ".$x;
				$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="infoPO('.$i.','.$items[$rowno]->ITEM_NO.','.$n.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
			}
		}

		if($id == 3){	
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n)." ".$x;
				if($n == 0 or is_null($n)){
					if($itemNya == 'Y'){
						if($i <= $items[$rowno]->PURCHASE_LEADTIME){
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="add_DI('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: red;">'.$nx.'<br/>OUT OF<br/>LEADTIME</a>';
						}else{
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="add_DI('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
						}
					}else{
						if($i <= $items[$rowno]->PURCHASE_LEADTIME){
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="addPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: red;">'.$nx.'<br/>OUT OF<br/>LEADTIME</a>';
						}else{
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="addPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
						}
					}
				}else{
					if($itemNya == 'Y'){
						$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="info_DI('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
					}else{
						$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="infoPRF('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;">'.$nx.'</a>';
					}
				}
			}
		}

		if($id == 4){
			$items[$rowno]->DESCRIPTION = '<a href="javascript:void(0)" onclick="info_ost('.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;"><b>'.substr($desc, 0,24).'<br/>'.substr($desc, 25,-1).'</a>';
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$items[$rowno]->$v = number_format($n);
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