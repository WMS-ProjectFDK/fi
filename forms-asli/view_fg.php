<?php
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>VIEW TRANSACTION KANBAN-FG</title>
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
	<fieldset style="float:left;width:450px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>Materials Transaction Filter</strong></span></legend>
		<div style="width:450px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Date</span>
				<input style="width:85px;" name="date_awal_slip" id="date_awal_slip" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir_slip" id="date_akhir_slip" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				<label><input type="checkbox" name="ck_slip_date" id="ck_slip_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Scan Date</span>
				<input style="width:85px;" name="date_awal_scan" id="date_awal_scan" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir_scan" id="date_akhir_scan" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				<label><input type="checkbox" name="ck_scan_date" id="ck_scan_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip No.</span>
				<select style="width:250px;" name="cmb_slip_no" id="cmb_slip_no" class="easyui-combobox" data-options=" url:'json/json_slip_fg.php', method:'get', valueField:'slip_no', textField:'slip_no', panelHeight:'75px'"></select>
				<label><input type="checkbox" name="ck_slip_no" id="ck_slip_no" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:475px;border-radius:4px;width: 500px;height: 100px;"><legend><span class="style3"><strong>Item Filter</strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
			onSelect:function(rec){
				//alert(rec.id_name_item);
				var spl = rec.id_name_item;
				var sp = spl.split(' - ');
				$('#txt_item_name').textbox('setValue', sp[1]);
			}"></select>
			<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
		</div>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item Name</span>
			<select style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" data-options=" url:'#', method:'get', valueField:'#', textField:'#', panelHeight:'150px'"></select>
		</div>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Work No.</span>
			<select style="width:250px;" name="cmb_wo_no" id="cmb_wo_no" class="easyui-combobox" data-options=" url:'json/json_wo_fg.php', method:'get', valueField:'wo_no', textField:'wo_no', panelHeight:'75px'"></select>
			<label><input type="checkbox" name="ck_wo_no" id="ck_wo_no" checked="true">All</input></label>
		</div>
	</fieldset>
	<fieldset style="margin-left: 998px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>Filter Data</strong></span></legend>
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
		</div>
		<div class="fitem" align="center" style="margin-top: -3px;">
			<a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="print_pdf()" disabled="true"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Print To PDF</a>
		</div>
		<div class="fitem" align="center" style="margin-top: -3px;">
			<a href="javascript:void(0)" style="width: 150px;" class="easyui-linkbutton c2" onclick="print_xls()" disabled="true"><i class="fa fa-file-excel-o" aria-hidden="true"></i> print To Excel</a>
		</div>
		</div>
	</fieldset>
</div>
<table id="dg" title="VIEW TRANSACTION KANBAN FINISH GOODS" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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
		$('#date_awal_slip').datebox('disable');
		$('#date_akhir_slip').datebox('disable');
		$('#ck_slip_date').change(function(){
			if ($(this).is(':checked')) {
				$('#date_awal_slip').combobox('disable');
				$('#date_akhir_slip').combobox('disable');
			}else{
				$('#date_awal_slip').combobox('enable');
				$('#date_akhir_slip').combobox('enable');
			}
		});

		$('#date_awal_scan').datebox('disable');
		$('#date_akhir_scan').datebox('disable');
		$('#ck_scan_date').change(function(){
			if ($(this).is(':checked')) {
				$('#date_awal_scan').combobox('disable');
				$('#date_akhir_scan').combobox('disable');
			}else{
				$('#date_awal_scan').combobox('enable');
				$('#date_akhir_scan').combobox('enable');
			}
		});

		$('#cmb_slip_no').combobox('disable');
		$('#ck_slip_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_slip_no').combobox('disable');
			}else{
				$('#cmb_slip_no').combobox('enable');
			}
		});

		$('#cmb_sts').combobox('disable');
		$('#ck_sts').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_sts').combobox('disable');
			}else{
				$('#cmb_sts').combobox('enable');
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
		$('#txt_item_name').textbox('disable');

		$('#cmb_wo_no').combobox('disable');
		$('#ck_wo_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_wo_no').combobox('disable');
			}else{
				$('#cmb_wo_no').combobox('enable');
			}
		});
	})

	function filterData(){
		var ck_slip_date = 'false';
		var ck_scan_date = 'false';
		var ck_slip_no = 'false';
		var ck_sts = 'false';
		var ck_item_no = 'false';
		var ck_wo_no = 'false';

		if($('#ck_slip_date').attr("checked")){
			ck_slip_date='true';
		}

		if($('#ck_scan_date').attr("checked")){
			ck_scan_date='true';
		}

		if($('#ck_slip_no').attr("checked")){
			ck_slip_no='true';
		}

		if($('#ck_item_no').attr("checked")){
			ck_item_no='true';
		}

		if($('#ck_wo_no').attr("checked")){
			ck_wo_no='true';
		}
		
		$('#dg').datagrid('load', {
			date_awal_slip: $('#date_awal_slip').datebox('getValue'),
			date_akhir_slip: $('#date_akhir_slip').datebox('getValue'),
			ck_slip_date: ck_slip_date,
			date_awal_scan: $('#date_awal_scan').datebox('getValue'),
			date_akhir_scan: $('#date_akhir_scan').datebox('getValue'),
			ck_scan_date: ck_scan_date,
			slip_no: $('#cmb_slip_no').combobox('getValue'),
			ck_slip: ck_slip_no,
			item_no : $('#cmb_item_no').combobox('getValue'),
			ck_item : ck_item_no,
			wo_no: $('#cmb_wo_no').combobox('getValue'),
			ck_wo: ck_wo_no
		});

		$('#dg').datagrid({
	    	url:'view_fg_get.php',
	    	singleSelect: true,
		    fitColumns: true,
			rownumbers: true,
			sortName: 'po_date',
			sortOrder: 'asc',
		    columns:[[
			    {field:'SLIP_NO',title:'SLIP NO.',width:75, halign: 'center'},
			    {field:'SLIP_DATE',title:'SLIP DATE',width:75, halign: 'center',align: 'center'},
			    {field:'WO_NO',title:'WORK ORDER<br>NO.',width:125, halign: 'center'},
			    {field:'PLT_NO',title:'PALLET<Br>NO.',width:50, halign: 'center',align: 'center'},
			    {field:'ITEM_NO',title:'ITEM',width:65, halign: 'center', align: 'center'},
			    {field:'ITEM_DESCRIPTION',title:'DESCRIPTION',width:200, halign: 'center'},
			    {field:'SLIP_QUANTITY',title:'QTY',width:70, halign: 'center', align: 'right'},
			    {field:'STS_APPROVE',title:'STATUS<br/>APPROVE', width:120, halign: 'center', align: 'center'},
			    {field:'SCAN',title:'SCAN<br/>WAREHOUSE', width:120, halign: 'center', align: 'center'}
		    ]]
		})
		var dg = $('#dg').datagrid();
		dg.datagrid('enableFilter');
	}

</script>
</body>
</html>