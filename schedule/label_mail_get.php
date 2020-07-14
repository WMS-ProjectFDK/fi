<?php	
	include("../connect/conn.php");

	$sql = "select labelline,
		       sum(shift1) shift1,
		       sum(shift2) shift2,
		       sum(shift3) shift3,
		       (select sum(qty) from ztb_label_header r
		          inner join ztb_label_detail l
		          on r.wo_no = l.wo_no
		          where trim(date_prod) = '28-FEB-18'  
		                and item_type = substr(labelline,1,instr(labelline,'#',1,1)-1)
		      ) PlanQty
		       
		from (
		    select Labelline,
		           case when shift = 1 then sum(total) else 0 end Shift1,
		           case when shift = 2 then sum(total) else 0 end Shift2,
		           case when shift = 3 then sum(total) else 0 end Shift3
		    
		    from (
		    
		    select 
		           case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
		           else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
		           else 3 end end shift ,sum(battery_in) total,labelline   
		    from ZTB_KANBAN_LBL s
		    inner join ztb_l_plan b
		    on s.idkanban = b.id
		    inner join ztb_label_header x
		    on x.wo_no = b.wo_no
		    where mulai = '28-SEP-18' and labelline is not null
		    group by labelline ,
		           case when (cast(substr(s.startdate,12,2) as int) >= 7 and cast(substr(s.startdate,12,2) as int) < 15) then 1 
		           else case when (cast(substr(s.startdate,12,2) as int) >= 15 and cast(substr(s.startdate,12,2) as int) < 23) then 2 
		           else 3 end end
		      )a
		      group by labelline,shift
		)bbb group by labelline";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	$tot3_1 = 0;		$tot3_2 = 0;		$tot3_3 = 0;
	$tot6_1 = 0;		$tot6_2 = 0;		$tot6_3 = 0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$ln3 = explode("#", $row->LABELLINE);
		$ln6 = explode("#", $row->LABELLINE);

		if($ln3[0] == 'LR03'){
			$tot3_1 += $items[$rowno]->SHIFT1;
			$tot3_2 += $items[$rowno]->SHIFT2;
			$tot3_3 += $items[$rowno]->SHIFT3;
		}elseif($ln6[0] == 'LR6'){
			if($rowno==3){
				$items[$rowno-3]->TOT_3_1 = $tot3_1;
				$items[$rowno-3]->TOT_3_2 = $tot3_2;
				$items[$rowno-3]->TOT_3_3 = $tot3_3;

				$items[$rowno-2]->TOT_3_1 = $tot3_1;
				$items[$rowno-2]->TOT_3_2 = $tot3_2;
				$items[$rowno-2]->TOT_3_3 = $tot3_3;	
			}
			
			$tot6_1 += $items[$rowno]->SHIFT1;
			$tot6_2 += $items[$rowno]->SHIFT2;
			$tot6_3 += $items[$rowno]->SHIFT3;

			if($items[$rowno]->LABELLINE == 'LR6#6'){
				$items[$rowno]->TOT_6_1 = $tot6_1;
				$items[$rowno]->TOT_6_2 = $tot6_2;
				$items[$rowno]->TOT_6_3 = $tot6_3;
				
				$items[$rowno-1]->TOT_6_1 = $tot6_1;
				$items[$rowno-1]->TOT_6_2 = $tot6_2;
				$items[$rowno-1]->TOT_6_3 = $tot6_3;

				$items[$rowno-2]->TOT_6_1 = $tot6_1;
				$items[$rowno-2]->TOT_6_2 = $tot6_2;
				$items[$rowno-2]->TOT_6_3 = $tot6_3;

				$items[$rowno-3]->TOT_6_1 = $tot6_1;
				$items[$rowno-3]->TOT_6_2 = $tot6_2;
				$items[$rowno-3]->TOT_6_3 = $tot6_3;

				$items[$rowno-4]->TOT_6_1 = $tot6_1;
				$items[$rowno-4]->TOT_6_2 = $tot6_2;
				$items[$rowno-4]->TOT_6_3 = $tot6_3;
			}
			
		}
		$rowno++;
	}
	echo json_encode($items);
?>