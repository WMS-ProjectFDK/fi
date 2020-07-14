<?php
	session_start();
	$result = array();

	$brand = isset($_REQUEST['brand']) ? strval($_REQUEST['brand']) : '';
	$qty = isset($_REQUEST['qty']) ? strval($_REQUEST['qty']) : '';
	//23560-ENERGIZER COR EU LR6 BULK-0
	$split_brand = split('@',$brand);

	include("../connect/conn.php");
	$rowno=0;

	$rs = "select st.lower_item_no as item_no, i.item, i.description, nvl(w.this_inventory,0) as STOCK_WH, u.unit_pl,i.uom_q, st.level_no,
		Trim(to_char(Trunc(nvl(st.quantity,0) / nvl(st.quantity_base,0)+ 0.009,3),'99,999,990.000')) ref_qty,
		ceil( nvl(st.quantity,0) / nvl(st.quantity_base,0) * 
		  (1 + (nvl(i_u.manufact_fail_rate,0)/100) +
       (nvl(i.manufact_fail_rate,0)/100) +
       (nvl(st.failure_rate,0)/100)
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
	$data = oci_parse($connect, $rs);
	oci_execute($data);
	$items = array();
	while($row = oci_fetch_object($data)) {
		array_push($items, $row);

		$item_no = $items[$rowno]->ITEM_NO;

		$dtl = "select item_fg, po_no, po_line_no, work_order, cr_date,status,mps_date,mps_qty, item_material,
			to_Date(mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy') as selisih,
			'N_' || to_char(to_Date(mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) as name_selisih, end_stock,
			(select nvl(sum(remainder_qty),0) as qtyPRF
		       from prf_details
		       where item_no=item_material  and remainder_qty > 0 
		       and require_date > (select sysdate-90 from dual) and require_date < (select sysdate from dual)
		    ) as qtyPRF
			from (
				select a.*, b.mps_date, b.mps_qty,
			  case when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '1' then c.N_1 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '2' then c.N_2 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '3' then c.N_3 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '4' then c.N_4 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '5' then c.N_5 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '6' then c.N_6 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '7' then c.N_7 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '8' then c.N_8 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '9' then c.N_9 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '10' then c.N_10
			  
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '11' then c.N_11 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '12' then c.N_12 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '13' then c.N_13 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '14' then c.N_14 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '15' then c.N_15 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '16' then c.N_16 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '17' then c.N_17 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '18' then c.N_18 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '19' then c.N_19 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '20' then c.N_20
			  
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '21' then c.N_21
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '22' then c.N_22
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '23' then c.N_23
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '24' then c.N_24
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '25' then c.N_25
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '26' then c.N_26
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '27' then c.N_27
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '28' then c.N_28
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '29' then c.N_29
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '30' then c.N_30
			  
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '31' then c.N_31 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '32' then c.N_32 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '33' then c.N_33 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '34' then c.N_34 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '35' then c.N_35 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '36' then c.N_36 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '37' then c.N_37 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '38' then c.N_38 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '39' then c.N_39 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '40' then c.N_40
			  
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '41' then c.N_41 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '42' then c.N_42 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '43' then c.N_43 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '44' then c.N_44 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '45' then c.N_45 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '46' then c.N_46 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '47' then c.N_47 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '48' then c.N_48 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '49' then c.N_49 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '50' then c.N_50
			  
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '51' then c.N_51 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '52' then c.N_52 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '53' then c.N_53 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '54' then c.N_54 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '55' then c.N_55 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '56' then c.N_56 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '57' then c.N_57 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '58' then c.N_58 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '59' then c.N_59 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '60' then c.N_60
			  
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '61' then c.N_61 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '62' then c.N_62 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '63' then c.N_63 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '64' then c.N_64 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '65' then c.N_65 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '66' then c.N_66 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '67' then c.N_67 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '68' then c.N_68 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '69' then c.N_69 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '70' then c.N_70
			  
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '71' then c.N_71 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '72' then c.N_72 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '73' then c.N_73 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '74' then c.N_74 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '75' then c.N_75 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '76' then c.N_76 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '77' then c.N_77 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '78' then c.N_78 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '79' then c.N_79 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '80' then c.N_80
			  
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '81' then c.N_81 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '82' then c.N_82 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '83' then c.N_83 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '84' then c.N_84 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '85' then c.N_85 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '86' then c.N_86 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '87' then c.N_87 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '88' then c.N_88 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '89' then c.N_89 
			       when to_number(to_Date(b.mps_date,'dd-MM-yyyy') - to_date(trim(sysdate), 'dd-MM-yyyy')) = '90' then c.N_90
			  else '0' end as end_stock
			  from (
				  select * from (
				    select a.item_no as item_fg, a.po_no, a.po_line_no, a.work_order, a.cr_date, a.status,'$item_no' as item_material
				      from mps_header a
				      where a.item_no in(select distinct upper_item_no from structure where lower_item_no=$item_no) 
				      and a.status in ('INQ','FM')
				      order by a.cr_date desc
				  ) where rownum <= 1
				) a
				left join mps_details b on a.po_no=b.po_no and a.po_line_no=b.po_line_no
			  left join (select * from ztb_mrp_data_pck where no_id=4) c on a.item_material = c.item_no
				order by b.mps_date desc
			) where rownum <= 1";
		$data_dtl = oci_parse($connect, $dtl);
		oci_execute($data_dtl);
		$rowDtl = oci_fetch_object($data_dtl);

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