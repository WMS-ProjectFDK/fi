<?php
	session_start();
	$result = array();

	$brand = isset($_REQUEST['brand']) ? strval($_REQUEST['brand']) : '';
	$qty = isset($_REQUEST['qty']) ? strval($_REQUEST['qty']) : '';
	//23560-ENERGIZER COR EU LR6 BULK-0
	$split_brand = explode('@',$brand);

	include("../../../connect/conn.php");
	$rowno=0;

    $rs = "select st.lower_item_no as item_no, i.item, i.description, isnull(w.this_inventory,0) as STOCK_WH, u.unit_pl,i.uom_q, st.level_no,
            isnull(st.quantity,0) / isnull(st.quantity_base,0)+ 0.009 ref_qty,
            ceiling(isnull(st.quantity,0) / isnull(st.quantity_base,0) * 
            (1 + (isnull(i_u.manufact_fail_rate,0)/100) +
        (isnull(i.manufact_fail_rate,0)/100) +
        (isnull(st.failure_rate,0)/100)
		  ) * ".$qty."
		) as SLIP_QTY
		from structure st
		left join item i on st.lower_item_no=i.item_no
		left join item i_u on st.upper_item_no=i_u.item_no
		left join unit u on i.uom_q=u.unit_code
		left join whinventory w on i.item_no=w.item_no
		where st.upper_item_no = '".$split_brand[0]."' 
		and st.level_no = '".$split_brand[2]."' 
		and st.lower_item_no = i.item_no 
		order by st.line_no";
	$data = sqlsrv_query($connect, strtoupper($rs));
    $items = array();
    
	while($row = sqlsrv_fetch_object($data)) {
		array_push($items, $row);

		$item_no = $items[$rowno]->ITEM_NO;

		$dtl = "select item_fg, po_no, po_line_no, work_order, cr_date, status, mps_date, mps_qty, item_material,
            DATEDIFF(day, getdate(), mps_date) as selisih,
            'N_' + CAST(DATEDIFF(day,GETDATE(),mps_date) as varchar) as name_selisih, end_stock,
            (select isnull(sum(remainder_qty),0) as qtyPRF
            from prf_details
            where item_no=item_material  and remainder_qty > 0 
            and require_date > (DATEADD(DAY,-90,GETDATE())) and require_date < (GETDATE())
            ) as qtyPRF
            from (
            select top 1 a.*, CONVERT(varchar,b.mps_date,102) as mps_date, b.mps_qty,
            case when DATEDIFF(day,b.mps_date,getdate()) = '1' then c.N_1 
                when DATEDIFF(day,b.mps_date,getdate()) = '2' then c.N_2 
                when DATEDIFF(day,b.mps_date,getdate()) = '3' then c.N_3 
                when DATEDIFF(day,b.mps_date,getdate()) = '4' then c.N_4 
                when DATEDIFF(day,b.mps_date,getdate()) = '5' then c.N_5 
                when DATEDIFF(day,b.mps_date,getdate()) = '6' then c.N_6 
                when DATEDIFF(day,b.mps_date,getdate()) = '7' then c.N_7 
                when DATEDIFF(day,b.mps_date,getdate()) = '8' then c.N_8 
                when DATEDIFF(day,b.mps_date,getdate()) = '9' then c.N_9 
                when DATEDIFF(day,b.mps_date,getdate()) = '10' then c.N_10
            
                when DATEDIFF(day,b.mps_date,getdate()) = '11' then c.N_11 
                when DATEDIFF(day,b.mps_date,getdate()) = '12' then c.N_12 
                when DATEDIFF(day,b.mps_date,getdate()) = '13' then c.N_13 
                when DATEDIFF(day,b.mps_date,getdate()) = '14' then c.N_14 
                when DATEDIFF(day,b.mps_date,getdate()) = '15' then c.N_15 
                when DATEDIFF(day,b.mps_date,getdate()) = '16' then c.N_16 
                when DATEDIFF(day,b.mps_date,getdate()) = '17' then c.N_17 
                when DATEDIFF(day,b.mps_date,getdate()) = '18' then c.N_18 
                when DATEDIFF(day,b.mps_date,getdate()) = '19' then c.N_19 
                when DATEDIFF(day,b.mps_date,getdate()) = '20' then c.N_20
            
                when DATEDIFF(day,b.mps_date,getdate()) = '21' then c.N_21
                when DATEDIFF(day,b.mps_date,getdate()) = '22' then c.N_22
                when DATEDIFF(day,b.mps_date,getdate()) = '23' then c.N_23
                when DATEDIFF(day,b.mps_date,getdate()) = '24' then c.N_24
                when DATEDIFF(day,b.mps_date,getdate()) = '25' then c.N_25
                when DATEDIFF(day,b.mps_date,getdate()) = '26' then c.N_26
                when DATEDIFF(day,b.mps_date,getdate()) = '27' then c.N_27
                when DATEDIFF(day,b.mps_date,getdate()) = '28' then c.N_28
                when DATEDIFF(day,b.mps_date,getdate()) = '29' then c.N_29
                when DATEDIFF(day,b.mps_date,getdate()) = '30' then c.N_30
            
                when DATEDIFF(day,b.mps_date,getdate()) = '31' then c.N_31 
                when DATEDIFF(day,b.mps_date,getdate()) = '32' then c.N_32 
                when DATEDIFF(day,b.mps_date,getdate()) = '33' then c.N_33 
                when DATEDIFF(day,b.mps_date,getdate()) = '34' then c.N_34 
                when DATEDIFF(day,b.mps_date,getdate()) = '35' then c.N_35 
                when DATEDIFF(day,b.mps_date,getdate()) = '36' then c.N_36 
                when DATEDIFF(day,b.mps_date,getdate()) = '37' then c.N_37 
                when DATEDIFF(day,b.mps_date,getdate()) = '38' then c.N_38 
                when DATEDIFF(day,b.mps_date,getdate()) = '39' then c.N_39 
                when DATEDIFF(day,b.mps_date,getdate()) = '40' then c.N_40
            
                when DATEDIFF(day,b.mps_date,getdate()) = '41' then c.N_41 
                when DATEDIFF(day,b.mps_date,getdate()) = '42' then c.N_42 
                when DATEDIFF(day,b.mps_date,getdate()) = '43' then c.N_43 
                when DATEDIFF(day,b.mps_date,getdate()) = '44' then c.N_44 
                when DATEDIFF(day,b.mps_date,getdate()) = '45' then c.N_45 
                when DATEDIFF(day,b.mps_date,getdate()) = '46' then c.N_46 
                when DATEDIFF(day,b.mps_date,getdate()) = '47' then c.N_47 
                when DATEDIFF(day,b.mps_date,getdate()) = '48' then c.N_48 
                when DATEDIFF(day,b.mps_date,getdate()) = '49' then c.N_49 
                when DATEDIFF(day,b.mps_date,getdate()) = '50' then c.N_50
            
                when DATEDIFF(day,b.mps_date,getdate()) = '51' then c.N_51 
                when DATEDIFF(day,b.mps_date,getdate()) = '52' then c.N_52 
                when DATEDIFF(day,b.mps_date,getdate()) = '53' then c.N_53 
                when DATEDIFF(day,b.mps_date,getdate()) = '54' then c.N_54 
                when DATEDIFF(day,b.mps_date,getdate()) = '55' then c.N_55 
                when DATEDIFF(day,b.mps_date,getdate()) = '56' then c.N_56 
                when DATEDIFF(day,b.mps_date,getdate()) = '57' then c.N_57 
                when DATEDIFF(day,b.mps_date,getdate()) = '58' then c.N_58 
                when DATEDIFF(day,b.mps_date,getdate()) = '59' then c.N_59 
                when DATEDIFF(day,b.mps_date,getdate()) = '60' then c.N_60
            
                when DATEDIFF(day,b.mps_date,getdate()) = '61' then c.N_61 
                when DATEDIFF(day,b.mps_date,getdate()) = '62' then c.N_62 
                when DATEDIFF(day,b.mps_date,getdate()) = '63' then c.N_63 
                when DATEDIFF(day,b.mps_date,getdate()) = '64' then c.N_64 
                when DATEDIFF(day,b.mps_date,getdate()) = '65' then c.N_65 
                when DATEDIFF(day,b.mps_date,getdate()) = '66' then c.N_66 
                when DATEDIFF(day,b.mps_date,getdate()) = '67' then c.N_67 
                when DATEDIFF(day,b.mps_date,getdate()) = '68' then c.N_68 
                when DATEDIFF(day,b.mps_date,getdate()) = '69' then c.N_69 
                when DATEDIFF(day,b.mps_date,getdate()) = '70' then c.N_70
            
                when DATEDIFF(day,b.mps_date,getdate()) = '71' then c.N_71 
                when DATEDIFF(day,b.mps_date,getdate()) = '72' then c.N_72 
                when DATEDIFF(day,b.mps_date,getdate()) = '73' then c.N_73 
                when DATEDIFF(day,b.mps_date,getdate()) = '74' then c.N_74 
                when DATEDIFF(day,b.mps_date,getdate()) = '75' then c.N_75 
                when DATEDIFF(day,b.mps_date,getdate()) = '76' then c.N_76 
                when DATEDIFF(day,b.mps_date,getdate()) = '77' then c.N_77 
                when DATEDIFF(day,b.mps_date,getdate()) = '78' then c.N_78 
                when DATEDIFF(day,b.mps_date,getdate()) = '79' then c.N_79 
                when DATEDIFF(day,b.mps_date,getdate()) = '80' then c.N_80
            
                when DATEDIFF(day,b.mps_date,getdate()) = '81' then c.N_81 
                when DATEDIFF(day,b.mps_date,getdate()) = '82' then c.N_82 
                when DATEDIFF(day,b.mps_date,getdate()) = '83' then c.N_83 
                when DATEDIFF(day,b.mps_date,getdate()) = '84' then c.N_84 
                when DATEDIFF(day,b.mps_date,getdate()) = '85' then c.N_85 
                when DATEDIFF(day,b.mps_date,getdate()) = '86' then c.N_86 
                when DATEDIFF(day,b.mps_date,getdate()) = '87' then c.N_87 
                when DATEDIFF(day,b.mps_date,getdate()) = '88' then c.N_88 
                when DATEDIFF(day,b.mps_date,getdate()) = '89' then c.N_89 
                when DATEDIFF(day,b.mps_date,getdate()) = '90' then c.N_90
            else '0' end as end_stock
            from (select top 1 a.item_no as item_fg, a.po_no, a.po_line_no, a.work_order, a.cr_date, a.status,
                    ".$item_no." as item_material
                    from mps_header a
                    where a.item_no in(select distinct upper_item_no from structure where lower_item_no=".$item_no.") 
                    and a.status in ('INQ','FM')
                    order by a.cr_date desc
            ) a
            left join mps_details b on a.po_no=b.po_no and a.po_line_no=b.po_line_no
            left join (select * from ztb_mrp_data_pck where no_id=4) c on a.item_material = c.item_no
            order by b.mps_date desc
            ) x";
		$data_dtl = sqlsrv_query($connect, strtoupper($dtl));
		$rowDtl = sqlsrv_fetch_object($data_dtl);

		$end_s = $rowDtl->END_STOCK;
		$end_d = $rowDtl->MPS_DATE;
		$s_wh = $items[$rowno]->STOCK_WH;
		$slip_qty = $items[$rowno]->SLIP_QTY;
		$TOTAL_END_STOCK = $end_s - $slip_qty;
		$open_prf = $rowDtl->QTYPRF;

		$items[$rowno]->END_STOCK = number_format($end_s);
		$items[$rowno]->END_DATE = $end_d;
		$items[$rowno]->STOCK_WH = number_format($s_wh);
		$items[$rowno]->SLIP_QTY = number_format($slip_qty);
		$items[$rowno]->TOTAL_END_STOCK = number_format($TOTAL_END_STOCK);
		$items[$rowno]->OPEN_PRF = number_format($open_prf);

		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>