<?php
/*id =2
NAme : ueng hernana
tanggal : 18-JAN-19
deskripsi: print kanban packaging
 */
ini_set('memory_limit','-1');
ini_set('max_execution_time', 0);
set_time_limit(0);
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];

$sql_h = "select distinct a.id, a.wo_no, a.item_no, substring(a.brand,0,30) as brand, a.date_code, a.type_item, a.grade, 
	cast(a.date_prod as varchar(10)) date_prod, a.qty_prod, a.plt_no, a.plt_tot, cast(a.cr_date as varchar(10)) cr_date, st.level_no as bom_level
	from ztb_plan_m a
  	left join (select * from structure s
               inner join (select max(level_no) level_nos, upper_item_no upper 
                           from structure 
                           group by upper_item_no
                          ) ss on s.upper_item_no = ss.upper and s.level_no = ss.level_nos
    ) st on a.item_no = st.upper_item_no
	left join mps_header b on a.wo_no = b.work_order and a.item_no = b.item_no
	where user_id = '$user_name'
	order by plt_no asc";

// select a.id, a.wo_no, a.item_no, substr(a.brand,0,30) as brand, a.date_code, a.type_item, a.grade, 
// 	a.date_prod, a.qty_prod, a.plt_no, a.plt_tot, a.cr_date, isnull(b.bom_level,0) as bom_level
// 	from ztb_plan_m a
// 	left join mps_header b on a.wo_no = b.work_order and a.item_no = b.item_no
// 	where user_id = '$user_name'
// 	order by plt_no asc";
$result = sqlsrv_query($connect, strtoupper($sql_h));


$content = " 
	<style> 
		table {
			border-collapse: collapse;
			padding:0px;
			font-size:14px;
			height:10px;
		}
		table, th, tr {
			border-left:0px solid #ffffff; 
			border-right:0px solid #ffffff; 
			border-bottom:0px solid #ffffff; 
			border-top:0px solid #ffffff;
		}
		th {
			
			color: black;
		}
		.brd {
			border:none;
		}

		.class_00{
			border:1px;width:360px;height:510px;border-radius:4px;margin-top:10px;
		}
		.class_01{
			position:absolute;margin-left:390px;border:1px;width:360px;height:510px;border-radius:4px;margin-top:10px;
		}


		.class_10{
			border:1px;width:360px;height:510px;border-radius:4px;margin-top:30px;
		}
		.class_11{
			position:absolute;margin-left:390px;border:1px;width:360px;height:510px;border-radius:4px;margin-top:550px;
		}
	</style>

	<page>
     ";

$row_a=0;	$col_a=0;
while ($data=sqlsrv_fetch_object($result)){
	$link1 = "http://localhost:8888/fi/forms/qr_generate_material.php?id=".$data->ID;

	if($data->ITEM_NO != '73012220'){
		$link2 = "http://localhost:8888/fi/forms/qr_generate_material_datecode.php?id=".$data->ID;	
	}else{
		$link2 = '';
	}
	
	if ($row_a==0) {
		if($col_a==0){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif ($col_a == 1) {
			$cls = "class_".$row_a.$col_a;
			$col_a=0;	$row_a++;
		}
	}elseif($row_a==1){
		if($col_a==0){
			$cls = "class_".$row_a.$col_a;
			$col_a++;
		}elseif ($col_a == 1) {
			$cls = "class_".$row_a.$col_a;
			$col_a=0;	$row_a=0;
		}
	}

	$content.= "
	<div class=".$cls.">
		<table border=1 style='font-size:10px;width:100%;'>
			<tr>
				<td align='center' valign='middle' style='width: 250px;height: 40px;border:0px solid #ffffff; font-size:16px;'>
					<b>MATERIAL PACKING</b>
				</td>
				<td align='center' valign='middle' style='border:0px solid #ffffff;'>
					Pallet No.<br>
					<span style='font-size: 27px;'>".$data->PLT_NO."/".$data->PLT_TOT."</span>
				</td>
			</tr>
			<tr>
				<td colspan=6 style='height: 2px;border:0px solid #ffffff;'>
					<table border=1 style='width:535px;font-size:9px;width:100%;'>
						<tr>
							<td align='left' valign='middle' style='width: 35px;border-right:0px solid #ffffff;'>WO NO.</td>
							<td align='left' valign='middle' style='width: 235px;border-left:0px solid #ffffff;'>: <b>".$data->WO_NO."</b></td>
							<td align='center' valign='middle' style='width: 65px;'>QTY</td>
						</tr>
						<tr>
							<td align='left' valign='middle' style='width: 35px;border-right:0px solid #ffffff;'>BRAND</td>
							<td align='left' valign='middle' style='width: 235px;border-left:0px solid #ffffff;'>: <b>".$data->ITEM_NO." - ".$data->BRAND."</b></td>
							<td align='right' valign='middle' style='width: 65px;'><b>".number_format($data->QTY_PROD)."</b></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align='center' valign='middle' style='font-size: 8px;width: 243px;border:0px solid #ffffff;'><b>schedule of finishing</b></td>
				<td rowspan=2 align='center' valign='middle' style='font-size: 8px;width: 80px;border:0px solid #ffffff;'>
	              
	            </td>
			</tr>
			<tr>
				<td colspan=5 style='height: 2px;border:0px solid #ffffff;'>
					<table border=1 style='font-size:9px;width:100%;'>
						<tr>
							<td align='center' valign='middle' style='width: 64px;'>CR DATE</td>
							<td align='center' valign='middle' style='width: 64px;'>DATE CODE</td>
							<td align='center' valign='middle' style='width: 64px;'>DATE</td>
							<td align='center' valign='middle' style='width: 64px;'>TIME</td>
							<td align='center' valign='middle' style='width: 65px; rowspan=2'>";
							if($data->ITEM_NO != '73012220'){
								$content .= "
								&nbsp;&nbsp;
								<span align='center'>
									<img src=".$link2." style='width: 55px;height: 55px;'/>
									<br><label style='font-size:9px;'><b>DATE CODE</b></label>
								</span>";
							}
								$content.="
							</td>
						</tr>
						<tr>
							<td align='center' style='width: 64px;' valign='middle'>".$data->CR_DATE."</td>
							<td align='center' style='width: 64px;' valign='middle'>".$data->DATE_CODE."</td>
							<td align='center' style='width: 64px;' valign='middle'>".$data->DATE_PROD."</td>
							<td align='center' style='width: 64px;' valign='middle' >____:____:____</td>
						</tr>
					</table>
				</td>
			</tr>";

		$content .= "
			<tr>
				<td colspan=6 style='height: 2px;border:0px solid #ffffff;'><b>ID : ".$data->ID."</b>
					<table border=1 style='width:535px;font-size:9px;width:100%;'>
						<tr>
							<td align='center' valign='middle' style='width: 13px;'>NO.</td>
							<td align='center' valign='middle' style='width: 50px;'>MATERIAL NO.</td>
							<td align='center' valign='middle' style='width: 200px;'>MATERIAL NAME</td>
							<td align='center' valign='middle' style='width: 65px;'>QTY</td>
						</tr>";

	/*CARI JUMLAH */				
	$sql_j = "select count(*) as jum from (
		select st.lower_item_no as item_no,i.description,
			ceil( isnull(st.quantity,0) / isnull(st.quantity_base,0) * 
			  ( 1 + (isnull(i_u.manufact_fail_rate,0)/100) +
			    (isnull(i.manufact_fail_rate,0)/100) +
			    (isnull(st.failure_rate,0)/100)
			  ) * ".$data->QTY_PROD."
			) as SLIP_QTY, u.unit_pl
		from structure st
		left join item i on st.lower_item_no=i.item_no
		left join item i_u on st.upper_item_no=i_u.item_no
		left join unit u on i.uom_q=u.unit_code
		left join whinventory w on i.item_no=w.item_no
		where st.upper_item_no = ".$data->ITEM_NO."
		and st.level_no = ".$data->BOM_LEVEL."
		and st.lower_item_no = i.item_no 
		order by i.description
	)";
	$result_j = sqlsrv_query($connect, strtoupper($sql_j));
	
	$j = sqlsrv_fetch_object($result_j);
	$jum = $j->JUM;
	
	/* CARI DETAIL */
	$sql_d = "select st.lower_item_no as item_no, substring(i.description,0,30) as description,
		ceiling( isnull(st.quantity,0) / isnull(st.quantity_base,0) * 
		  ( 1 + (isnull(i_u.manufact_fail_rate,0)/100) +
		    (isnull(i.manufact_fail_rate,0)/100) +
		    (isnull(st.failure_rate,0)/100)
		  ) * ".$data->QTY_PROD."
		) as SLIP_QTY, u.unit_pl
		from structure st
		left join item i on st.lower_item_no=i.item_no
		left join item i_u on st.upper_item_no=i_u.item_no
		left join unit u on i.uom_q=u.unit_code
		left join whinventory w on i.item_no=w.item_no
		where st.upper_item_no = ".$data->ITEM_NO."
		and st.level_no = ".$data->BOM_LEVEL."
		and st.lower_item_no = i.item_no
		and st.lower_item_no not in('2116095'	,'2116102'	,'2126023'	,'2126036'	,'2126040'	,'2126041'	,'2126052'	,'2145001'	,'2145008'	
								   ,'2150002'	,'2150003'	,'2150007'	,'2150009'	,'2216120'	,'2216215'	,'2226017'	,'2226079'	,'2226080'	
								   ,'2245006'	,'2245012'	,'2245013'	,'2245036'	,'2250001'	,'2250002'	,'2250012'	,'2250022'	,'2250023'	
								   ,'2250024'	,'2250025'	,'2250026'	,'2250027'	,'2317002'	,'2322002'	,'2600001'	,'2600006'	,'2600007'	
								   ,'2600009'	,'2600053'	,'2600068'	,'2600164'	,'2600183'  ,'12600165' ,'2150012'  ,'2150013'  ,'2150010'
								   ,'2116013'	,'2216048')
		order by i.item_no asc";

	$result2 = sqlsrv_query($connect, strtoupper($sql_d));
	
	$noUrut = 1;
	
	while ($data2=sqlsrv_fetch_object($result2)) {
		if ($noUrut % 2 == 0){
			$content .= "
						<tr>
							<td align='center' valign='middle' style='width: 13px;'>".$noUrut."</td>
							<td style='width: 50px;' align='left' valign='middle'>".$data2->ITEM_NO."</td>
							<td style='width: 200px;' align='left' valign='middle'>".$data2->DESCRIPTION."</td>
							<td style='width: 65px;' align='right' valign='middle'><b>".number_format($data2->SLIP_QTY)." ".$data2->UNIT_PL."</b></td>
						</tr>";		
		}else{
			$content .= "
						<tr>
							<td align='center' valign='middle' style='width: 13px;background-color: #EBEBEB;'>".$noUrut."</td>
							<td align='left' valign='middle' style='width: 50px;background-color: #EBEBEB;'>".$data2->ITEM_NO."</td>
							<td align='left' valign='middle' style='width: 200px;background-color: #EBEBEB;'>".$data2->DESCRIPTION."</td>
							<td align='right' valign='middle' style='width: 65px;background-color: #EBEBEB;'><b>".number_format($data2->SLIP_QTY)." ".$data2->UNIT_PL."</b></td>
						</tr>";	
		}
		

		if($noUrut == $jum){
			$noUrut++;
			for ($i=$noUrut; $i<=20 ; $i++) {
				if ($i % 2 == 0){
					$content .= "
						<tr>
							<td align='center' valign='middle' style='width: 13px;'>".$i."</td>
							<td align='center' valign='middle' style='width: 50px;'></td>
							<td align='center' valign='middle' style='width: 200px;'></td>
							<td align='center' valign='middle' style='width: 65px;'></td>
						</tr>
					";
				}else{
					$content .= "
						<tr>
							<td align='center' valign='middle' style='width: 13px;background-color: #EBEBEB;'>".$i."</td>
							<td align='center' valign='middle' style='width: 50px;background-color: #EBEBEB;'></td>
							<td align='center' valign='middle' style='width: 200px;background-color: #EBEBEB;'></td>
							<td align='center' valign='middle' style='width: 65px;background-color: #EBEBEB;'></td>
						</tr>
					";
				}
			}
		}

		$noUrut++;
	}

		$content .= "
						<tr>
							<td colspan=3 style='width: 250px;height: 50px;font-size: 8px;'>
								<b>NOTE :</b>
							</td>
							<td align='center' valign='middle' style='width: 65px;height: 65px;'>
								<img src=".$link1." style='width: 65px;height: 65px;' /> 	
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>";
}

$content .= "</page>";

require_once(dirname(__FILE__).'/../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('P','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('KanbanAssembling.pdf');
?>