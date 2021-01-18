<?php 
//error_reporting(0);
ini_set('memory_limit', '-1'); 
include("../../connect/conn.php");
session_start();
date_default_timezone_set('Asia/Jakarta');
$user_name = $_SESSION['id_wms'];
$nama_user = $_SESSION['name_wms'];
$date=date("d M y / H:i:s",time());

//http://localhost:8088/fi/forms/warehouse/inventory_pdf.php?cmbBln=202008&cmbBln_txt=08-2020&src=&rdo_sts=check_WP


$cmbBln = isset($_REQUEST['cmbBln']) ? strval($_REQUEST['cmbBln']) : '';
$cmbBln_txt = isset($_REQUEST['cmbBln_txt']) ? strval($_REQUEST['cmbBln_txt']) : '';
$rdo_sts = isset($_REQUEST['rdo_sts']) ? strval($_REQUEST['rdo_sts']) : '';
$src = isset($_REQUEST['src']) ? strval($_REQUEST['src']) : '';

if($rdo_sts=='check_all'){
    $stock = "";
}elseif($rdo_sts=='check_PM'){
    $stock = "b.stock_subject_code='2' and ";
}elseif($rdo_sts=='check_FG'){
    $stock = "b.stock_subject_code='5' and ";
}elseif($rdo_sts=='check_WP'){
    $stock = "b.stock_subject_code='0' and ";
}elseif($rdo_sts=='check_WIP'){
    $stock = "b.stock_subject_code='3' and ";
}elseif($rdo_sts=='check_CSP'){
    $stock = "b.stock_subject_code='6' and ";
}elseif($rdo_sts=='check_RM'){
    $stock = "b.stock_subject_code='1' and ";
}elseif($rdo_sts=='check_semiFG'){
    $stock = "b.stock_subject_code='4' and ";
}elseif($rdo_sts=='check_material2'){
    $stock = "b.stock_subject_code='7' and ";
}elseif($rdo_sts==''){
    $stock = "b.stock_subject_code is null and ";
}

if ($src !='') {
    $where="where a.item_no='$src' AND (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";
}else{
    $where ="where $stock (a.this_month='$cmbBln' OR a.last_month='$cmbBln')";
}

$sql = "select distinct max(this_month) as this_month, max(last_month) as last_month from whinventory";
$data = sqlsrv_query($connect, strtoupper($sql));
$dt_result = sqlsrv_fetch_object($data);

if($dt_result->THIS_MONTH == $cmbBln){
    $sql = "select a.item_no, b.item, b.description, b.uom_q, c.unit, a.this_month, 
	a.this_inventory, 
	a.receive1,
	a.other_receive1,
	a.issue1,
	a.other_issue1,
	a.last_inventory,
	(select sum(slip_quantity)from [transaction] where item_no=a.item_no and LEFT(CONVERT(varchar, slip_date,112),6)='$cmbBln') as qty_act
	from whinventory a
	inner join item b on a.item_no=b.item_no
	inner join unit c on b.uom_q=c.unit_code
	$where order by b.item asc, b.description asc"; 
		$result = sqlsrv_query($connect, strtoupper($sql));
}else{
    $sql = "select a.item_no, b.item, b.description, b.uom_q, c.unit, a.this_month, 
	a.last_inventory as this_inventory, 
	receive2 as receive1,
	other_receive2 as other_receive1,
	issue2 as issue1,
	other_issue2 as other_issue1,
	last2_inventory as last_inventory,
	(select sum(slip_quantity)from [transaction] where item_no=a.item_no and LEFT(CONVERT(varchar, slip_date,112),6)='$cmbBln') as qty_act
	from whinventory a
	inner join item b on a.item_no=b.item_no
	inner join unit c on b.uom_q=c.unit_code
	$where order by b.item asc, b.description asc"; 
		$result = sqlsrv_query($connect, strtoupper($sql));
}
$nourut = 1;

$content = "	
	<style>
		table {
			border-collapse: collapse;
			padding:4px;
			font-size:11px;
		}
		table, th, td {
			border: 1px solid #d0d0d0;	
		}
		th {
			//background-color: #4bd2fe;
			color: black;
		}
		.brd {
			border:none;
		}
	</style>
	<page>
		<div style='position:absolute;margin-top:0px;'>
			<img src='../../images/logo-print4.png' alt='#' style='width:300px;height: 70px'/><br/>
		</div>	

		<div style='margin-top:0;margin-left:950px;font-size:9px'>
			<p align='' >Bekasi, ".$date."<br>Print By : ".$nama_user."</p>
		</div>

	<page_footer>
		<div style='width:100%;text-align:right;margin-bottom:100%;font-size:9px;'>
			page [[page_cu]] of [[page_nb]]
		</div>
    </page_footer>
	
	<div style='margin-top:20px;margin-bottom:100%;position:absolute;'>
		<h2 align='center'>WAREHOUSE INVENTORY<br></h2>
		<table align='center'>";
$content .= "
			<thead>
				<tr>
					<th valign='middle' align='center' style='font-size:12px;width:30px;height:25px;'>NO</th>
					<th valign='middle' align='center' style='font-size:12px;width:60px;height:25px;'>ITEM NO</th>
					<th colspan=2 valign='middle' align='center' style='font-size:12px;width:180px;height:25px;'>DESCRIPTION</th>
					<th valign='middle' align='center' style='font-size:12px;width:20px;height:25px;'>UNIT</th>
					<th valign='middle' align='center' style='font-size:12px;width:40px;height:25px;'>MONTH</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>INVENTORY</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>RECEIVE</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>OTHER<br/>RECEIVE</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>ISSUE</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>OTHER ISSUE</th>
					<th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>LAST<br/>INVENTORY</th>
				</tr>
			</thead>";
$total=0;
while ($data=sqlsrv_fetch_object($result)){
	$content .= "
			<tr>
				<td valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".$nourut."</td>
				<td valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".$data->ITEM_NO."</td>
				<td colspan=2 valign='middle' align='left' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".$data->ITEM."<br/>".$data->DESCRIPTION."</td>
				<td valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".$data->UNIT."</td>
				<td valign='middle' align='center' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".$cmbBln_txt."</td>
				<td valign='middle' align='right' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".number_format($data->THIS_INVENTORY)."&nbsp;</td>
				<td valign='middle' align='right' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".number_format($data->RECEIVE1)."&nbsp;</td>
				<td valign='middle' align='right' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".number_format($data->OTHER_RECEIVE1)."&nbsp;</td>
				<td valign='middle' align='right' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".number_format($data->ISSUE1)."&nbsp;</td>
				<td valign='middle' align='right' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".number_format($data->OTHER_ISSUE1)."&nbsp;</td>
				<td valign='middle' align='right' style='background-color:#A5A5A5;font-size:12px;height:20px;'>".number_format($data->LAST_INVENTORY)."&nbsp;</td>
			</tr>
			<tr>
				<th valign='middle' align='center' style='font-size:12pxheight:25px;'></th>
				<th valign='middle' align='center' style='background-color: #D2D2D2;font-size:12pxheight:25px;'>NO</th>
				<th valign='middle' align='center' style='background-color: #D2D2D2;font-size:12pxheight:25px;'>SLIP DATE</th>
				<th valign='middle' align='center' style='background-color: #D2D2D2;font-size:12pxheight:25px;'>SLIP NO</th>
				<th colspan=2 valign='middle' align='center' style='background-color: #D2D2D2; font-size:12pxheight:25px;'>SLIP TYPE</th>
				<th valign='middle' align='center' style='background-color: #D2D2D2; font-size:12px;height:25px;'>COMPANY</th>
				<th valign='middle' align='center' style='background-color: #D2D2D2; font-size:12px;height:25px;width: 100px;'>RECEIVE</th>
				<th valign='middle' align='center' style='background-color: #D2D2D2; font-size:12px;height:25px;'>OTHER<br/>RECEIVE</th>
				<th valign='middle' align='center' style='background-color: #D2D2D2; font-size:12px;height:25px;'>ISSUE</th>
				<th valign='middle' align='center' style='background-color: #D2D2D2; font-size:12px;height:25px;'>OTHER<br/>ISSUE</th>
				<th valign='middle' align='center' style='background-color: #D2D2D2; font-size:12px;height:25px;'>INVENTORY</th>
			</tr>";
	$nourut_dtl = 1;
	$l_inv = $data->LAST_INVENTORY;
    $sql = "select cast(t.operation_date as varchar(10)) operation_date, t.section_code, sc.section, i.stock_subject_code, st.stock_subject, 
		  cast(t.slip_date as varchar(10))  slip_date, t.slip_type, sl.slip_name slip_name, t.slip_no, sl.in_out_flag,
			case sl.table_position when 1 then t.slip_quantity end  receive,
			case sl.table_position when 2 then t.slip_quantity end other_receive,
			case sl.table_position when 3 then t.slip_quantity end issue,
			case sl.table_position when 4 then t.slip_quantity end  other_issue,
			case sl.in_out_flag
				  when 'I' then isnull(t.slip_quantity,0)
				  when 'O' then isnull(-t.slip_quantity,0)
			   end qty,
			t.cost_process_code, t.cost_subject_code, t.remark1, t.remark2, t.unit_stock, t.company_code, c.company, t.ex_rate
			from [transaction] t, item i, section sc, unit u, stock_subject st,sliptype sl, company c, currency cu
			where t.item_no = i.item_no  and i.delete_type  is null and t.section_code = sc.section_code  and t.unit_stock = u.unit_code 
			and t.section_code = sc.section_code  and t.slip_type = sl.slip_type  and t.company_code = c.company_code  
			and t.stock_subject_code = st.stock_subject_code  and t.curr_code = cu.curr_code  
			and t.section_code = '100' and t.item_no = ".$data->ITEM_NO." and  t.accounting_month = '".$data->THIS_MONTH."' 
			order by t.slip_date,t.slip_type,t.SLIP_NO ";
    $detail = sqlsrv_query($connect, strtoupper($sql));
	
	

    while ($dta = sqlsrv_fetch_object($detail) ){
        $q = $dta->QTY;
        $total = intval($l_inv) + $q;

        $content .= "
        	<tr>
        		<td valign='middle' align='right' style='font-size:12px;height:20px;'></td>
        		<td valign='middle' align='center' style='font-size:12px;height:20px;'>".$nourut_dtl."</td>
				<td valign='middle' align='center' style='font-size:12px;height:20px;'>".$dta->SLIP_DATE."</td>
				<td valign='middle' style='font-size:12px;height:20px;'>".$dta->SLIP_NO."</td>
				<td colspan=2 valign='middle' style='font-size:12px;height:20px;width:120px;'>".wordwrap('['.$dta->SLIP_TYPE.'] '.$dta->SLIP_NAME,6)."</td>
				<td valign='middle' align='left' style='font-size:12px;width:120px;height:20px;'>".wordwrap($dta->COMPANY_CODE.' - '.$dta->COMPANY,20)."</td>
				<td valign='middle' align='right' style='font-size:12px;height:20px;'>".number_format($dta->RECEIVE)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:12px;height:20px;'>".number_format($dta->OTHER_RECEIVE)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:12px;height:20px;'>".number_format($dta->ISSUE)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:12px;height:20px;'>".number_format($dta->OTHER_ISSUE)."&nbsp;</td>
				<td valign='middle' align='right' style='font-size:12px;height:20px;'>".number_format($total)."&nbsp;</td>
        	</tr>
        ";

        $l_inv = $total;
        $nourut_dtl++;
    }
	$nourut++;
}

$content .= "
		</table>
	</div>
</page>";

require_once(dirname(__FILE__).'../../../class/html2pdf/html2pdf.class.php');
$html2pdf = new HTML2PDF('L','A4','en');
$html2pdf->WriteHTML($content);
$html2pdf->Output('INVENTORY-'.$cmbBln_txt.'.pdf');
//echo  $content;
?>