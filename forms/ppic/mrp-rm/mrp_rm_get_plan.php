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

	include("../../../connect/conn.php");

	$where = "where $ty $it a.item_no is not null";

	$qry =  "select MAX,MIN,NO_ID,DESCRIPTION,N_1,N_2,N_3,N_4,N_5,N_6,N_7,N_8,N_9,N_10,N_11,N_12,N_13,N_14,N_15,N_16,N_17,N_18,N_19,N_20,N_21,N_22,N_23,N_24,N_25,N_26,N_27,N_28,N_29,N_30,N_31,N_32,N_33,N_34,N_35,N_36,N_37,N_38,N_39,N_40,N_41,N_42,N_43,N_44,N_45,N_46,N_47,N_48,N_49,N_50,N_51,N_52,N_53,N_54,N_55,N_56,N_57,N_58,N_59,N_60,N_61,N_62,N_63,N_64,N_65,N_66,N_67,N_68,N_69,N_70,N_71,N_72,N_73,N_74,N_75,N_76,N_77,N_78,N_79,N_80,N_81,N_82,N_83,N_84,N_85,N_86,N_87,N_88,N_89,N_90,aa.ITEM_NO,ITEM_DESC,ITEM_TYPE,item,item_description,unit_pl,purchase_leadtime 
	from
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
	
	$data_qry = sqlsrv_query($connect, strtoupper($qry));
	

	$items = array();		$minArr = array();			$maxArr = array();
	$rowno=0;
	$no=1;
	$nilai = 'N_';		$itm = "";

	while ($row = sqlsrv_fetch_object($data_qry)) {
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