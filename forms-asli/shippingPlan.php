<?php
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
if ($varConn=='Y'){

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SHIPPING PLAN</title>
<link rel="icon" type="image/png" href="../favicon.png">
<script language="javascript">
	function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
	}
</script> 
<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../themes/color.css" />
<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.patch.js"></script>
<script type="text/javascript" src="../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
<style>
*{
font-size:12px;
}
body {
	font-family:verdana,helvetica,arial,sans-serif;
	padding:20px;
	font-size:12px;
	margin:0;
}
h2 {
	font-size:18px;
	font-weight:bold;
	margin:0;
	margin-bottom:15px;
}
.demo-info{
	padding:0 0 12px 0;
}
.demo-tip{
	display:none;
}
.fitem{
	padding: 3px 0px;
}
.board_2 {
	position: absolute;
	margin-left:725px;	
	top: 0px;
	border-style: solid;
	border-width: 0px;
}
</style>
</head>
<body>
	<?php include ('../ico_logout.php'); ?>
	<div id="toolbar" style="padding:3px 3px;">
		<fieldset style="float: left;border-radius:4px;height:100px;width:905px;"><legend><span class="style3"><strong> FILTER SHIPPING PLAN </strong></span></legend>
			<div style="width:450px;float:left">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CR DATE</span>
					<input style="width:125px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				  		to   
					<input style="width:125px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					<label><input type="checkbox" name="ck_cr_date" id="ck_cr_date" checked="true">ALL</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">WO No.</span>
					<select style="width:273px;" name="cmb_wo_no" id="cmb_wo_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'work_order',textField:'work_order', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_wo_no" id="ck_wo_no" checked="true">ALL</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PO No.</span>
					<select style="width:273px;" name="cmb_po_no" id="cmb_po_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_po_no" id="ck_po_no" checked="true">ALL</input></label>
				</div>
			</div>
			<div style="width:430px;float:left">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">ITEM No.</span>
					<select style="width:273px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'item_no',textField:'item_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">ALL</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PPBE No.</span>
					<select style="width:120px;" name="cmb_ppbe" id="cmb_ppbe" class="easyui-combobox" 
						data-options=" url:'json/json_ppbe_no.php?user=<? echo $user_name; ?>',method:'get',valueField:'ppbe_no',textField:'ppbe_no', panelHeight:'100px'"></select>
					<label><input type="checkbox" name="ck_ppbe" id="ck_ppbe" checked="true">ALL</input></label>
				</div>		
			     <div class="fitem">
					<span style="width:80px;display:inline-block;">SI No.</span>
					<select style="width:120px;" name="cmb_si_no" id="cmb_si_no" class="easyui-combobox" 
						data-options=" url:'json/json_si_no.php',method:'get',valueField:'si_no',textField:'si_no', panelHeight:'100px'"></select>
					<label><input type="checkbox" name="ck_si" id="ck_si" checked="true">ALL</input></label>
				</div>
			</div>
		</fieldset>
		<fieldset style="margin-left: 935px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>PRINT & VIEW</strong></span></legend>
			<div class="fitem">
				<a href="javascript:void(0)" id="print_si" class="easyui-linkbutton c2" onClick="print_si()" style="width:150px;"><i class="fa fa-print" aria-hidden="true"></i> Print SI to Forwarder</a>
				<a href="javascript:void(0)" id="print_si" class="easyui-linkbutton c2" onClick="link_to_beakdown()" style="width:200px;"><i class="fa fa-eye" aria-hidden="true"></i> View Breakdown Container</a>
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;"></span>
				<a href="javascript:void(0)" id="print_si" class="easyui-linkbutton c2" onClick="link_to_packinglist()" style="width:200px;"><i class="fa fa-eye" aria-hidden="true"></i> View Packing List</a>
			</div>
		</fieldset>
		<div style="padding:5px 6px;">
			<a href="javascript:void(0)" id="filterData" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;">
				<i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
			<a href="javascript:void(0)" id="addShippingPlan" class="easyui-linkbutton c2" onClick="addShippingPlan()" style="width:200px;">
				<i class="fa fa-plus" aria-hidden="true"></i> ADD SHIPPING PLAN</a>
			<a href="javascript:void(0)" id="editShippingPlan" class="easyui-linkbutton c2" onClick="editShippingPlan()" style="width:200px;">
				<i class="fa fa-edit" aria-hidden="true"></i> EDIT SHIPPING PLAN</a>
			<a href="javascript:void(0)" id="removeShippingPlan" class="easyui-linkbutton c2" onClick="removeShippingPlan()" style="width:200px;">
				<i class="fa fa-trash" aria-hidden="true"></i> REMOVE SHIPPING PLAN</a>
		</div>
	</div>
	<table id="dg" title="SHIPPING PLAN ENTRY" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:100%;" rownumbers="true" fitColumns="true" selectOnCheck= "true">	
	</table>

	<script type="text/javascript">
		function myformatter(date){
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var d = date.getDate();
			return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
		}

		function myparser(s){
			if (!s) return new Date();
				var ss = (s.split('-'));
				var y = parseInt(ss[0],10);
				var m = parseInt(ss[1],10);
				var d = parseInt(ss[2],10);
				if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
				return new Date(y,m-1,d);
			} else {
				return new Date();
			}
		}

		$(function(){
			$('#add_shipping_plan').linkbutton('disable');
			$('#print_si').linkbutton('disable');
			$('#date_awal').datebox('disable');
			$('#date_akhir').datebox('disable');
			$('#ck_cr_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_awal').datebox('disable');
					$('#date_akhir').datebox('disable');
				}else{
					$('#date_awal').datebox('enable');
					$('#date_akhir').datebox('enable');
				}
			});

			$('#cmb_wo_no').combobox('disable');
			$('#ck_wo_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_wo_no').combobox('disable');
				}else{
					$('#cmb_wo_no').combobox('enable');
				}
			});

			$('#cmb_si_no').combobox('disable');
			$('#ck_si').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_si_no').combobox('disable');
				}else{
					$('#cmb_si_no').combobox('enable');
				}
			});

			$('#cmb_po_no').combobox('disable');
			$('#ck_po_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_po_no').combobox('disable');
				}else{
					$('#cmb_po_no').combobox('enable');
				}
			});

			$('#cmb_item_no').combobox('disable');
			$('#ck_item_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_no').combobox('disable');
				}else{
					$('#cmb_item_no').combobox('enable');
				}
			});

			$('#cmb_ppbe').combobox('disable');
			$('#ck_ppbe').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_ppbe').combobox('disable');
				}else{
					$('#cmb_ppbe').combobox('enable');
				}
			});

			$('#dg').datagrid( {
				columns:[[
				    {field:'CRS_REMARK', title:'PPBE NO', halign:'center'},
				    {field:'SI_NO', title:'SI NO.', halign:'center'},
				    {field:'WORK_ORDER',title:'WORK ORDER', halign:'center', width:120},
				    {field:'SO_NO', title:'SO', halign:'center', width:50},
				    {field:'LINE_NO', title:'SO<br>LINE', halign:'center', align:'center', width:30},
	                {field:'PO_NO', title:'CUST. PO NO.', halign:'center', width:70},
	                {field:'PO_LINE_NO', title:'PO<br>LINE', halign:'center', align:'center', width:30},
	                {field:'CR_DATE', title:'CR<br>DATE', halign:'center', align:'center', width:50},
					{field:'STUFFY_DATE', title:'STUFFY<br>DATE', halign:'center', align:'center', width:50},
					{field:'ETD_ANS', title:'ETD<br>DATE', halign:'center', align:'center', width:50},
					{field:'ETA', title:'ETA<br>DATE', halign:'center', align:'center', width:50},
					{field:'SHIPPING_TYPE', title:'SHIPPING', halign:'center', align:'center', width:70},
	                {field:'BATERY_TYPE', title:'BATT<br/>TYPE', halign:'center', align:'center', width:40},
	                {field:'CELL_GRADE', title:'CELL<br>TYPE', halign:'center', align:'center', width:35}
				]]
			});
		});
	</script>
</body>
</html>
<?php }else{ echo "<script type='text/javascript'>location.href='../404.html';</script>";} ?>