<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Production Monitoring</title>
<link rel="icon" type="image/png" href="../../favicon.png">
<script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
	</script> 
<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../themes/color.css" />
<script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../js/jquery.easyui.patch.js"></script>
<script type="text/javascript" src="../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../js/jquery.edatagrid.js"></script>
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
<?php include ('../../ico_logout.php'); ?>
<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="margin-left;border-radius:4px;height:70px;width:98%"><legend><span class="style3"><strong> Production Monitoring </strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Cargo Ready Date</span>
				<input style="width:125px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			  		to   
				<input style="width:125px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				<label><input type="checkbox" name="ck_cr_date" id="ck_cr_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">WO No.</span>
				<select style="width:273px;" name="cmb_wo_no" id="cmb_wo_no" class="easyui-combobox" data-options=" url:'../json/json_mps.php',method:'get',valueField:'work_order',textField:'work_order', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_wo_no" id="ck_wo_no" checked="true">All</input></label>
			</div>
		</div>
		<div style="width:450px;float:left">
			<div class="fitem">	
				<span style="width:80px;display:inline-block;">PO No.</span>
				<select style="width:300px;" name="cmb_po_no" id="cmb_po_no" class="easyui-combobox" data-options=" url:'../json/json_mps.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_po_no" id="ck_po_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Item No.</span>
				<select style="width:300px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_mps.php',method:'get',valueField:'item_no',textField:'item_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
			</div>
		</div>

		<div style="width:200;float:left">
			<div class="fitem">	
				<span style="width:80px;display:inline-block;">Item Name</span>
				<select style="width:180px;" name="text_item" id="text_item" class="easyui-textbox" ></select>
				<label><input type="checkbox" name="ck_item" id="ck_item" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
<div style="padding:5px 6px;">
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
</div>

</div>
<table id="dg" title="Production Monitoring" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" selectOnCheck= "true">
</table>

<div id='dlg_viewKur' class="easyui-dialog" style="width:1000px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewKur" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>

<div id='dlg_viewInv' class="easyui-dialog" style="width:900px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewInv" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>

<div id='dlg_viewItem' class="easyui-dialog" style="width:1000px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewItem" class="easyui-datagrid" style="width:100%;height:auto;"></table>
</div>

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

		$('#text_item').combobox('disable');
		$('#ck_item').change(function(){
			if ($(this).is(':checked')) {
				$('#text_item').combobox('disable');
			}else{
				$('#text_item').combobox('enable');
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
	});

	function filterData(){
		var ck_cr_date = "false";
		var ck_po_no = "false";
		var ck_wo_no = "false";
		var ck_item_no = "false";
		var ck_item = "false";
		var flag = 0;

		if ($('#ck_cr_date').attr("checked")) {
			ck_cr_date = "true";
			flag += 1;
		};

		if ($('#ck_po_no').attr("checked")) {
			ck_po_no = "true";
			flag += 1;
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
			flag += 1;
		};

		if ($('#ck_wo_no').attr("checked")) {
			ck_wo_no = "true";
			flag += 1;
		};

		if ($('#ck_item').attr("checked")) {
			ck_item = "true";
			flag += 1;
		};
		
		if(flag == 5) {
			alert("No filter data, system only show 150 records.")
		}
		
		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			ck_cr_date: ck_cr_date,
			cmb_wo_no : $('#cmb_wo_no').combobox('getValue'),
			ck_wo_no: ck_wo_no,
			cmb_po_no : $('#cmb_po_no').combobox('getValue'),
			ck_po_no: ck_po_no,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no,
			text_item: $('#text_item').textbox('getValue').toUpperCase(),
			ck_item: ck_item,
			flag: flag
			
		});
		
		$('#dg').datagrid( {
			url: 'production_monitoring_get.php',
			singleSelect: true,
			columns:[[
			    {field:'WORK_ORDER',title:'Work Order No.', halign:'center', width:120},
                {field:'PO_NO', title:'Cust PO No.', halign:'center', width:70},
                {field:'PO_LINE_NO', title:'Line No', halign:'center', width:30},
                {field:'ITEM_NO', title:'Item No', halign:'center', align:'center', width:50},
                {field:'ITEM_NAME', title:'Item Name', halign:'center', align:'left', width:100},
                {field:'CR_DATE', title:'CR Date<br>MPS', halign:'center', align:'center', width:50},
                {field:'CRDATE_SHIP_PLAN', title:'CR Date<br>SHIP PLAN', halign:'center', align:'center', width:50},
                {field:'BATERY_TYPE', title:'BATERY<br/>TYPE', halign:'center', align:'right', width:30},
                {field:'CELL_GRADE', title:'Grade', halign:'center', align:'center', width:30},
                {field:'QTY_ORDER', title:'QTY Order', halign:'center', align:'right', width:50},
                {field:'QTY_PRODUKSI', title:'QTY Available', halign:'center', align:'right', width:50},
                {field:'QTY_INVOICED', title:'QTY Invoiced', halign:'center', align:'right', width:50},
                {field:'SI_NO', title:'SI NO', halign:'center', align:'right', width:50, hidden:true}
			]]
		})
		$('#dg').datagrid('enableFilter');
	}

	function info_kuraire(a){
		$('#dlg_viewKur').dialog('open').dialog('setTitle','VIEW INFO KURAIRE');
		$('#dg_viewKur').datagrid({
			url: '../shipping_plan/shipping_plan_info_kur.php?work_order='+a+'',
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'WO_NO', title:'Work Order.', width:130, halign: 'center', align: 'center'},
			    {field:'PLT_NO', title:'Plt No', width: 60, halign: 'center'},
			    {field:'ITEM_NO', title:'Item Name', width: 80, halign: 'center'},
			    {field:'ITEM_DESCRIPTION', title:'Description.', width: 200, halign: 'center'},
			    {field:'SCAN_DATE', title:'Scan Time', width: 150, halign: 'center'},
			    {field:'SLIP_TYPE', title:'Slip Type', width: 70, halign: 'center'},
			    {field:'SLIP_QUANTITY', title:'Quantity', width: 100, halign: 'center', align: 'right'},
			    {field:'APPROVAL_DATE', title:'Approval Date', width:100, halign: 'center'}
			]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		});
	}

	function info_invoiced(a){
		$('#dlg_viewInv').dialog('open').dialog('setTitle','VIEW INFO INVOICE');
		$('#dg_viewInv').datagrid({
			url: '../shipping_plan/shipping_plan_info_inv.php?work_order='+a+'',
			singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'CUSTOMER_PO_NO', title:'PO No.', width:115, halign: 'center', align: 'center'},
			    {field:'ETD', title:'ETD', width: 80, halign: 'center'},
			    {field:'ETA', title:'ETA', width: 80, halign: 'center'},
			    {field:'CR_DATE', title:'Cargo Ready.', width: 200, halign: 'center'},
			    {field:'DO_NO', title:'Invoice No', width: 150, halign: 'center'},
			    {field:'ITEM_NO', title:'Item No', width: 70, halign: 'center'},
			    {field:'QTY', title:'Quantity', width: 100, halign: 'center', align: 'right'}]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		});
	}

	function info_item(a){
		$('#dlg_viewItem').dialog('open').dialog('setTitle','VIEW INFO Item '+a+'');
		$('#dg_viewItem').datagrid({
			url: 'production_monitoring_info_item.php?item_no='+a+'',
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'ITEM_NO', title:'ITEM NO', width:130, halign: 'center', align: 'center'},
			    {field:'PACKAGE_TYPE', title:'PACKACGE<br>TYPE.', width: 100, halign: 'center', align: 'center'},
			    {field:'GROUPS_PCK', title:'GROUP<br>PACKING', width: 100, halign: 'center'},
			    {field:'CAPACITY', title:'CAPACITY', width: 70, halign: 'center'},
			    {field:'MAN_POWER', title:'MAN<br>POWER', width: 100, halign: 'center'}
			]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		});
	}
</script>
</body>
</html>
