<?php
	session_start();
	error_reporting(0);
	set_time_limit(0);
	include("../../../connect/conn.php");
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$from = isset($_REQUEST['from']) ? strval($_REQUEST['from']) : '';
	$sts = isset($_REQUEST['sts']) ? strval($_REQUEST['sts']) : '';

	if ($sts == 'lower'){
		if($cmb_item_no!=''){
			$it = "a.item_no = $cmb_item_no AND ";
			$it2 = "item_no = $cmb_item_no AND ";
		}else{
			$it = "";
			$it2 = "";
		}	
	}else{
		if($cmb_item_no!=''){
			$it = "a.item_no in (select distinct lower_item_no from structure where upper_item_no=$cmb_item_no
				and level_no = (select max(level_no) from structure where upper_item_no=$cmb_item_no)) AND ";
			$it2 = "item_no in (select distinct lower_item_no from structure where upper_item_no=$cmb_item_no
				and level_no = (select max(level_no) from structure where upper_item_no=$cmb_item_no)) AND ";
		}else{
			$it = "";
			$it2 = "";
		}
	}
	

	//cek item to DI
	$filename = "../../json/json_item_to_DI_MRP.json";
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
	$cek = "select isnull(sum(bal_qty),0) as balance 
		from po_details 
		where item_no=$cmb_item_no 
		and eta <= cast(getdate() as date)
		and bal_qty != 0";
	$data_cek = sqlsrv_query($connect, strtoupper($cek));
	
	$dt_cek = sqlsrv_fetch_object($data_cek);
	$b = ' (BALANCE : '.number_format($dt_cek->BALANCE).')';

	$where = "where $it a.item_no is not null";
	$where2 = "where $it2 item_no is not null";

	$qry =  "select NO_ID,DESCRIPTION,
		ceiling(N_1) as N_1,
		ceiling(N_2) as N_2,
		ceiling(N_3) as N_3,
		ceiling(N_4) as N_4,
		ceiling(N_5) as N_5,
		ceiling(N_6) as N_6,
		ceiling(N_7) as N_7,
		ceiling(N_8) as N_8,
		ceiling(N_9) as N_9,
		ceiling(N_10) as N_10,
		ceiling(N_11) as N_11,
		ceiling(N_12) as N_12,
		ceiling(N_13) as N_13,
		ceiling(N_14) as N_14,
		ceiling(N_15) as N_15,
		ceiling(N_16) as N_16,
		ceiling(N_17) as N_17,
		ceiling(N_18) as N_18,
		ceiling(N_19) as N_19,
		ceiling(N_20) as N_20,
		ceiling(N_21) as N_21,
		ceiling(N_22) as N_22,
		ceiling(N_23) as N_23,
		ceiling(N_24) as N_24,
		ceiling(N_25) as N_25,
		ceiling(N_26) as N_26,
		ceiling(N_27) as N_27,
		ceiling(N_28) as N_28,
		ceiling(N_29) as N_29,
		ceiling(N_30) as N_30,
		ceiling(N_31) as N_31,
		ceiling(N_32) as N_32,
		ceiling(N_33) as N_33,
		ceiling(N_34) as N_34,
		ceiling(N_35) as N_35,
		ceiling(N_36) as N_36,
		ceiling(N_37) as N_37,
		ceiling(N_38) as N_38,
		ceiling(N_39) as N_39,
		ceiling(N_40) as N_40,
		ceiling(N_41) as N_41,
		ceiling(N_42) as N_42,
		ceiling(N_43) as N_43,
		ceiling(N_44) as N_44,
		ceiling(N_45) as N_45,
		ceiling(N_46) as N_46,
		ceiling(N_47) as N_47,
		ceiling(N_48) as N_48,
		ceiling(N_49) as N_49,
		ceiling(N_50) as N_50,
		ceiling(N_51) as N_51,
		ceiling(N_52) as N_52,
		ceiling(N_53) as N_53,
		ceiling(N_54) as N_54,
		ceiling(N_55) as N_55,
		ceiling(N_56) as N_56,
		ceiling(N_57) as N_57,
		ceiling(N_58) as N_58,
		ceiling(N_59) as N_59,
		ceiling(N_60) as N_60,
		ceiling(N_61) as N_61,
		ceiling(N_62) as N_62,
		ceiling(N_63) as N_63,
		ceiling(N_64) as N_64,
		ceiling(N_65) as N_65,
		ceiling(N_66) as N_66,
		ceiling(N_67) as N_67,
		ceiling(N_68) as N_68,
		ceiling(N_69) as N_69,
		ceiling(N_70) as N_70,
		ceiling(N_71) as N_71,
		ceiling(N_72) as N_72,
		ceiling(N_73) as N_73,
		ceiling(N_74) as N_74,
		ceiling(N_75) as N_75,
		ceiling(N_76) as N_76,
		ceiling(N_77) as N_77,
		ceiling(N_78) as N_78,
		ceiling(N_79) as N_79,
		ceiling(N_80) as N_80,
		ceiling(N_81) as N_81,
		ceiling(N_82) as N_82,
		ceiling(N_83) as N_83,
		ceiling(N_84) as N_84,
		ceiling(N_85) as N_85,
		ceiling(N_86) as N_86,
		ceiling(N_87) as N_87,
		ceiling(N_88) as N_88,
		ceiling(N_89) as N_89,
		ceiling(N_90) as N_90,
		
		aa.ITEM_NO,ITEM_DESC,item,item_description,unit_pl,purchase_leadtime from (
						select a.*, c.item, c.description as item_description,unit_pl from ztb_mrp_data_pck a 
						inner join item c on a.item_no=c.item_no
						inner join unit u on c.uom_q = u.unit_code
						$where
					)aa
					left outer join (
						select ax.item_no, bx.purchase_leadtime from (
							select max(line_no) as line_no, item_no from itemmaker $where2
							group by item_no)ax
						inner join
						(select line_no,item_no, purchase_leadtime from itemmaker)bx on ax.item_no=bx.item_no AND ax.line_no=bx.line_no
					)ab on aa.item_no = ab.item_no
					order by aa.item_no, aa.no_id";
	$data_qry = sqlsrv_query($connect, strtoupper($qry));
	// echo $qry;

	$items = array();		$minArr = array();			$maxArr = array();
	$rowno=0;
	$no=1;
	$nilai = 'N_';		$itm = "";

	while ($row = sqlsrv_fetch_object($data_qry)) {
		array_push($items, $row);
		$id = intval($items[$rowno]->NO_ID);
		$desc = strtoupper($items[$rowno]->DESCRIPTION);
		
		if ($desc == 'PURCHASE'){
			$items[$rowno]->DESCRIPTION = '<b>'.$desc.$b.'</b>';
		}else{
			$items[$rowno]->DESCRIPTION = '<b>'.$desc.'</b>';	
		}

	

		if($id == 1){												//PLAN
			
			$items[$rowno]->DESCRIPTION = '<a href="javascript:void(0)" onclick="info_plan('.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;"><b><u>'.$items[$rowno]->DESCRIPTION.'</u></a>';
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n,2)." ".$x;
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
			$items[$rowno]->DESCRIPTION = '<a href="javascript:void(0)" onclick="info_ost('.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: black;"><b>'.$items[$rowno]->DESCRIPTION.'</a>';
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$x = $items[$rowno]->UNIT_PL;
				$nx = number_format($n)." ".$x;
				if($n == 0 or is_null($n)){
					if($itemNya == 'Y'){
						if($i <= $items[$rowno]->PURCHASE_LEADTIME){
							$items[$rowno]->$v = '<a href="javascript:void(0)" title="'.$v.'" onclick="add_DI('.$i.','.$items[$rowno]->ITEM_NO.')" style="text-decoration: none; color: red;"><b>'.$nx.'<br/>OUT OF<br/>LEADTIME</b></a>';
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
			for ($i=1; $i <=90 ; $i++) { 
				$v = $nilai.$i;
				$n = $items[$rowno]->$v;
				$nb = number_format($n+$dt_cek->BALANCE);
				if($n <= 0 && $nb <= 0){
					$items[$rowno]->$v = '<span style="text-decoration: none; color: #FF0000;"><b>'.number_format($n).'<br>'.number_format($dt_cek->BALANCE).'<br>'.$nb.'</b></span>';
				}elseif($n <= 0 && $nb > 0){
					$items[$rowno]->$v = '<span style="text-decoration: none; color: #FF7F00;"><b>'.number_format($n).'<br>'.number_format($dt_cek->BALANCE).'<br>'.$nb.'</b></span>';
				}else{
					$items[$rowno]->$v = '<span><b>'.number_format($n).'<br>'.number_format($dt_cek->BALANCE).'<br>'.$nb.'</b></span>';
				}
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