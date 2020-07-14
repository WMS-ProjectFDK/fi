<?php
	session_start();
	$date_awal = isset($_REQUEST['date_awal']) ? strval($_REQUEST['date_awal']) : '';
	$date_akhir = isset($_REQUEST['date_akhir']) ? strval($_REQUEST['date_akhir']) : '';
	$ck_date = isset($_REQUEST['ck_date']) ? strval($_REQUEST['ck_date']) : '';
	$cmb_slip_no = isset($_REQUEST['cmb_slip_no']) ? strval($_REQUEST['cmb_slip_no']) : '';
	$ck_slip_no = isset($_REQUEST['ck_slip_no']) ? strval($_REQUEST['ck_slip_no']) : '';
	$cmb_slip_type = isset($_REQUEST['cmb_slip_type']) ? strval($_REQUEST['cmb_slip_type']) : '';
	$ck_slip_type = isset($_REQUEST['ck_slip_type']) ? strval($_REQUEST['ck_slip_type']) : '';
	$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
	$ck_item_no = isset($_REQUEST['ck_item_no']) ? strval($_REQUEST['ck_item_no']) : '';

    
include("../connect/conn.php");

	$sql = "Insert into mte_header
							    (Slip_no, 
							     slip_date, 
							     company_code, 
							     slip_type, 
							     display_type, 
							     section_code, 
							     person_code,
							     wo_no)
							select distinct    s.slip_no, 
								   z.date_in,
								   '100001',
								   '21',
								   'C',
								   100,
								   'KANBAN',
								   s.wo_no
							from mte_details s
							inner join ztb_wh_kanban_trans z
								on s.slip_no = z.slip_no
							left outer join mte_header r
								on s.slip_no = r.slip_no
							where  r.slip_no is null and s.reg_date > '01-NOV-18'
							group by s.slip_no, z.date_in, s.wo_no
							having count(s.slip_no) = count(z.slip_no)";
	$stmt = oci_parse($connect, $sql);
	$res = oci_execute($stmt);
	$pesan = oci_error($stmt);



	if($ck_date != "true"){
		$date = "a.slip_date between to_date('$date_awal','YYYY-MM-DD') and to_date('$date_akhir','YYYY-MM-DD') and ";
	}else{
		$date = "";
	}


	if ($ck_slip_no != "true"){
		$slip_no = "a.slip_no = '$cmb_slip_no' and ";
	}else{
		 $slip_no = "";
	}

	if ($ck_slip_type != "true"){
		$slip_type = "a.slip_type = '$cmb_slip_type' and ";
	}else{
		 $slip_type = "";
	}

	if ($ck_item_no != "true"){
		$item_no = "c.item_no='$cmb_item_no' and ";
	}else{
		 $item_no = "";
	}
	
	$where ="where $slip_no $slip_type $item_no $date
		a.approval_date is null and a.approval_person_code is null
		and to_char (a.slip_date,'YYYYMM') = wh.this_month and tot1 = nvl(tot2,tot1)";
	
	

	$sql = "select * from 
		(select distinct a.slip_no, a.slip_date, TO_CHAR(a.slip_date,'yyyy-mm-dd') as slip_date_a,
		a.company_code, b.company, 
		case when a.person_code = 'KANBAN' then a.person_code
	    when a.person_code is null then ''
	    else a.person_code || ' - ' || upper(p.person) end as person_code, 
		a.approval_date, a.slip_type, sl.slip_name,
		nvl((select count(it.item_no) from mte_details it 
			left join whinventory whi on it.item_no=whi.item_no
			left join mte_header mth on it.slip_no=mth.slip_no
	        where it.slip_no=a.slip_no and 
	        	  mth.slip_type!='20' and
	        	  it.qty > whi.this_inventory)
	    ,0) as sts
		from mte_header a 
		left join company b on a.company_code= b.company_code
		left join mte_details c on a.slip_no=c.slip_no
		left join item d on c.item_no=d.item_no
		left join sliptype sl on a.slip_type= sl.slip_type
		left join whinventory wh on c.item_no=wh.item_no
		left join person p on a.person_code = p.person_code
		left join (select slip_no,count(slip_no) tot1 from mte_details group by slip_no) cc on a.slip_no=cc.slip_no
		left outer join (select slip_no,count(slip_no) tot2 from ztb_wh_kanban_trans group by slip_no)z on z.slip_no = a.slip_no
		$where 
		order by a.slip_date desc, a.slip_no asc) where rownum <= 150 ";
	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
	while($row = oci_fetch_object($data)){
		array_push($items, $row);
		$items[$rowno]->SLIP_NM = "[".$items[$rowno]->SLIP_TYPE."] ".$items[$rowno]->SLIP_NAME;

		$sts = $items[$rowno]->STS;
		if($sts==0){
			$items[$rowno]->STS_NM = '<span style="color:BLUE;font-size:11px;"><b>OK</b></span>';
		}else{
			$items[$rowno]->STS_NM = '<span style="color:WHITE;font-size:11px;"><b>OVER</b></span>';
		}
		$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);
?>