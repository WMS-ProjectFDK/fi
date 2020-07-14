<?php
/*id =2
NAme : ueng hernana
tanggal : 18-JAN-19
deskripsi: print kanban packaging
 */
ini_set('memory_limit','-1');
ini_set('max_execution_time', 0);
set_time_limit(0);
include("../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];

$sql_h = "select a.id, a.wo_no, a.item_no, substr(a.brand,0,30) as brand, a.date_code, a.type_item, a.grade, 
	a.date_prod, a.qty_prod, a.plt_no, a.plt_tot, a.cr_date, b.bom_level
	from ztb_plan_m a
	left join mps_header b on a.wo_no = b.work_order and a.item_no = b.item_no
	--where user_id = '$user_name'
	order by plt_no asc";
$result = oci_parse($connect, $sql_h);
oci_execute($result); 

echo "<!DOCTYPE html>
   	<html>
   	<head>
   	<meta charset='UTF-8'>
   	<link rel='icon' type='image/png' href='../favicon.png'>
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
			border:1px;width:370px;height:525px;border-radius:4px;margin-top:10px;
		}
		.class_01{
			position:absolute;margin-left:380px;border:1px;width:370px;height:525px;border-radius:4px;margin-top:10px;
		}


		.class_10{
			border:1px;width:370px;height:525px;border-radius:4px;margin-top:10px;
		}
		.class_11{
			position:absolute;margin-left:380px;border:1px;width:370px;height:525px;border-radius:4px;margin-top:10px;
		}
	</style>

	<page>
     ";

$row_a=0;	$col_a=0;
while ($data=oci_fetch_object($result)){
	$link1 = "http://172.23.225.85/wms/forms/qr_generate_material.php?id=".$data->ID;
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

echo "
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
							<td align='center' valign='middle' style='width: 150px;'>WO NO.</td>
							<td align='center' valign='middle' style='width: 150px;'>BRAND</td>
							<td align='center' valign='middle' style='width: 45px;'>QTY</td>
						</tr>
						<tr>
							<td style='width: 150px;' align='left ' valign='middle'>".$data->WO_NO."</td>
							<td style='width: 150px;' align='left' valign='middle'>".$data->ITEM_NO." - ".$data->BRAND."</td>
							<td style='width: 45px;' align='right' valign='middle'><b>".number_format($data->QTY_PROD)."</b></td>
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
							<td align='center' valign='middle' style='width: 61px;'>CR DATE</td>
							<td align='center' valign='middle' style='width: 61px;'>DATE CODE</td>
							<td align='center' valign='middle' style='width: 61px;'>DATE</td>
							<td align='center' valign='middle' style='width: 62px;'>TIME</td>
						</tr>
						<tr>
							<td align='center' style='width: 61px;' valign='middle'>".$data->CR_DATE."</td>
							<td align='center' style='width: 61px;' valign='middle'>".$data->DATE_CODE."</td>
							<td align='center' style='width: 61px;' valign='middle'>".$data->DATE_PROD."</td>
							<td align='center' style='width: 62px;' valign='middle' >___:___:___</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan=6 style='height: 2px;border:0px solid #ffffff;'><b>ID : ".$data->ID."</b>
					<table border=1 style='width:535px;font-size:9px;width:100%;'>
						<tr>
							<td align='center' valign='middle' style='width: 10px;'>NO.</td>
							<td align='center' valign='middle' style='width: 50px;'>MATERIAL NO.</td>
							<td align='center' valign='middle' style='width: 250px;'>MATERIAL NAME</td>
							<td align='center' valign='middle' style='width: 85px;'>QTY</td>
						</tr>";

	/*CARI JUMLAH */				
	$sql_j = "select count(*) as jum from (
		select st.lower_item_no as item_no,i.description,
			ceil( nvl(st.quantity,0) / nvl(st.quantity_base,0) * 
			  ( 1 + (nvl(i_u.manufact_fail_rate,0)/100) +
			    (nvl(i.manufact_fail_rate,0)/100) +
			    (nvl(st.failure_rate,0)/100)
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
	$result_j = oci_parse($connect, $sql_j);
	oci_execute($result_j);
	$j = oci_fetch_object($result_j);
	$jum = $j->JUM;
	
	/* CARI DETAIL */
	$sql_d = "select st.lower_item_no as item_no, substr(i.description,0,30) as description,
		ceil( nvl(st.quantity,0) / nvl(st.quantity_base,0) * 
		  ( 1 + (nvl(i_u.manufact_fail_rate,0)/100) +
		    (nvl(i.manufact_fail_rate,0)/100) +
		    (nvl(st.failure_rate,0)/100)
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
		order by i.description";
	$result2 = oci_parse($connect, $sql_d);
	oci_execute($result2);
	$noUrut = 1;
	
	while ($data2=oci_fetch_object($result2)) {
		if ($noUrut % 2 == 0){
			echo "
						<tr>
							<td align='center' valign='middle' style='width: 10px;'>".$noUrut."</td>
							<td style='width: 50px;' align='left' valign='middle'>".$data2->ITEM_NO."</td>
							<td style='width: 200px;' align='left' valign='middle'>".$data2->DESCRIPTION."</td>
							<td style='width: 85px;' align='right' valign='middle'><b>".number_format($data2->SLIP_QTY)." ".$data2->UNIT_PL."</b></td>
						</tr>";		
		}else{
			echo "
						<tr>
							<td align='center' valign='middle' style='width: 10px;background-color: #EBEBEB;'>".$noUrut."</td>
							<td align='left' valign='middle' style='width: 50px;background-color: #EBEBEB;'>".$data2->ITEM_NO."</td>
							<td align='left' valign='middle' style='width: 200px;background-color: #EBEBEB;'>".$data2->DESCRIPTION."</td>
							<td align='right' valign='middle' style='width: 85px;background-color: #EBEBEB;'><b>".number_format($data2->SLIP_QTY)." ".$data2->UNIT_PL."</b></td>
						</tr>";	
		}
		

		if($noUrut == $jum){
			$noUrut++;
			for ($i=$noUrut; $i<=20 ; $i++) {
				if ($i % 2 == 0){
					echo "
						<tr>
							<td align='center' valign='middle' style='width: 10px;'>".$i."</td>
							<td align='center' valign='middle' style='width: 50px;'></td>
							<td align='center' valign='middle' style='width: 200px;'></td>
							<td align='center' valign='middle' style='width: 85px;'></td>
						</tr>
					";
				}else{
					echo "
						<tr>
							<td align='center' valign='middle' style='width: 10px;background-color: #EBEBEB;'>".$i."</td>
							<td align='center' valign='middle' style='width: 50px;background-color: #EBEBEB;'></td>
							<td align='center' valign='middle' style='width: 200px;background-color: #EBEBEB;'></td>
							<td align='center' valign='middle' style='width: 85px;background-color: #EBEBEB;'></td>
						</tr>
					";
				}
			}
		}

		$noUrut++;
	}

		echo "
						<tr>
							<td colspan=3 style='width: 250px;height: 70px;font-size: 8px;'><b>NOTE :</b></td>
							<td align='center' valign='middle' style='width: 85px;height: 80px;'>
								<img src=".$link1." /> 	
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>";
}

echo "</page>";
?>