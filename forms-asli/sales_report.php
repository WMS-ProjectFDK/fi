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
<title>SALES REPORT</title>
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

<script type="text/javascript" src="../js/datagrid-export.js"></script>
<style>
*{
font-size:12px;
}
body {
	font-family:verdana,helvetica,arial,sans-serif;
	padding:20px;
	font-size:10px;
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

<div id="toolbar" style="padding:3px 3px; height: 150px" >
	<fieldset style="float:left;width:800px;border-radius:4px;height: 130px;"><legend><span class="style3"><strong> FILTER DATA</strong></span></legend>
		<div style="width:800px;float:left">
			<div class="fitem" >
				<span style="width:110px;display:inline-block;">Select Report</span>
				<select style="width:300px;" name="cmb_report" id="cmb_report" class="easyui-combobox" data-options="panelHeight:'70px'" required="">
					<option value="" selected="true"></option>
 					<option value="1">1. Sales Report Summary by Battery Type</option>
 					<option value="2">2. Sales Report Summary by Customer</option>
 					<option value="3">3. Sales Report Details </option>
				</select>
				<!-- <label><input type="checkbox" name="ck_report" id="ck_report" checked="true">All</input></label> -->
				<span style="width:130px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">  Item Sample</span>
				<label><input type="checkbox" name="ck_sample" id="ck_sample" checked="true"></input></label>
				<span style="width:550px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">Item Material</span>
				<label><input type="checkbox" name="ck_material" id="ck_material" checked="true"></input></label>
			</div>
			<div class="fitem" >
				<span style="width:110px;display:inline-block;">Date</span>
				<span style="width:60px;display:inline-block;"><input type="radio" name="name_date" id="check_eta" value="check_eta"/> ETD</span>
			    <span style="width:50px;display:inline-block;"><input type="radio" name="name_date" id="check_bl" value="check_bl"/> BL</span>
			    <span style="width:100px;display:inline-block;"><input type="radio" name="name_date" id="check_xfact" value="check_xfact"/> EX FACT</span>
			    <span style="width:100px;display:inline-block;"><input type="radio" name="name_date" id="check_sales" value="check_sales"/> SALES DATE</span>
			    <span style="width:150px;display:inline-block;"></span>
			    <a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;"></span>
				<input style="width:93px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
				to 
				<input style="width:93px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
			</div>
		</div>
	</fieldset>
	<fieldset style="margin-left: 725px;border-radius:4px;height: 130px;width:400px;"><legend><span class="style3"><strong>PRINT DATA</strong></span></legend>
		<div class="fitem" align="center" style="margin-top: 13px;">
			<a href="javascript:void(0)" id="printpdf" style="width: 250px;" class="easyui-linkbutton c2" disabled="true" onclick="print_pdf()"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download To PDF</a>
		</div>
		<div class="fitem" align="center" style="margin-top: -3px;">
			<a href="javascript:void(0)" id="printxls" style="width: 250px;" class="easyui-linkbutton c2" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download To Excel</a>
		</div>
		</div>
	</fieldset>
</div>
<table id="dg" title="SALES REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;" rownumbers="true" singleSelect="true"></table>

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
		document.getElementById('check_bl').checked=true;

		var today = new Date();
		var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth()+1, 0);
		var yy = lastDayOfMonth.getFullYear();
		var mm = lastDayOfMonth.getMonth()+1;
		var dd = lastDayOfMonth.getDate();
		var first = yy+'-'+(mm<10?('0'+mm):mm)+'-'+'01'; 
		var last = yy+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd);
		$('#date_awal').datebox('setValue', first);
		$('#date_akhir').datebox('setValue', last);
	});
	
	var url='';

	function filterData(){
		if($('#cmb_report').combobox('getValue')==''){
			$.messager.alert('Warning','Data Report not Found, please select Report','warning');
		}else{
			var ck_s = "false";
			var ck_m = "false";
			var rdo_date;

			if(document.getElementById('check_eta').checked == true){
				rdo_date = document.getElementById('check_eta').value;
			}else if(document.getElementById('check_bl').checked == true){
				rdo_date = document.getElementById('check_bl').value;
			}else if(document.getElementById('check_xfact').checked == true){
				rdo_date = document.getElementById('check_xfact').value;
			}else if(document.getElementById('check_sales').checked == true){
				rdo_date = document.getElementById('check_sales').value;	
			}else{
				rdo_date ='';
			}

			if ($('#ck_sample').attr("checked")) {
				ck_s = "true";
			};

			if ($('#ck_material').attr("checked")) {
				ck_m = "true";
			}	else {
					ck_m = "false";

			};

			$('#dg').datagrid('load', {
				cmbR: $('#cmb_report').combobox('getValue'),
				rdJn: rdo_date,
				dt_A: $('#date_awal').datebox('getValue'),
				dt_Z: $('#date_akhir').datebox('getValue'),
				ck_s: ck_s,
				ck_m: ck_m
			});

			url = "?cmbR="+$('#cmb_report').combobox('getValue')
				 +"&rdJn="+rdo_date
				 +"&dt_A="+$('#date_awal').datebox('getValue')
				 +"&dt_Z="+$('#date_akhir').datebox('getValue')
				 +"&ck_s="+ck_s
				 +"&ck_m="+ck_m

			if($('#cmb_report').combobox('getValue') == 1){
				$('#dg').datagrid( {
				    fitColumns: true,
				    showFooter: true,
				    columns:[[
					    {field:'TYPE_BATERY', title:'BATTERY TYPE', width:150, halign:'center'},
		                {field:'QUANTITY', title:'QTY', width:150, halign:'center', align:'right'},
		                {field:'AMOUNT', title:'AMOUNT', width:150, halign:'center', align:'right'}
					]]
				});
			}else if($('#cmb_report').combobox('getValue') == 2) {
				$('#dg').datagrid( {
				    fitColumns: true,
				    showFooter: true,
				    columns:[[
					    {field:'CUSTOMER', title:'CUSTOMER', width:150, halign:'center'},
		                {field:'QUANTITY', title:'QTY', width:150, halign:'center', align:'right'},
		                {field:'AMOUNT', title:'AMOUNT', width:150, halign:'center', align:'right'}
					]]
				});
			}else if($('#cmb_report').combobox('getValue') == 3) {
				$('#dg').datagrid( {
					fitColumns: true,
				    showFooter: true,
				    columns:[[
					    {field:'CUSTOMER', title:'CUSTOMER', width:100, halign:'center'},
					    {field:'COMPANY', title:'CUST. NAME', width:90, halign:'center'},
		                {field:'CUSTOMER_PO_NO', title:'PO NO.<br/>CUSTOMER', width:90, halign:'center'},
		                {field:'SO_NO', title:'SO NO.', width:80, halign:'center'},
		                {field:'DO_NO', title:'DO NO.', width:100, halign:'center'},
		                {field:'ITEM_NO', title:'ITEM', width:70, halign:'center'},
		                {field:'DESCRIPTION', title:'DESCRIPTION', width:200, halign:'center'},
		                {field:'TYPE_BATERY', title:'BATTERY<br/>TYPE', width:80, halign:'center', align:'center'},
		                {field:'QTY', title:'QTY', width:170, halign:'center', align:'right'},
		                {field:'UNIT_PL', title:'UoM', width:50, halign:'center', align:'center'},
		                {field:'CURR_MARK', title:'CURR', width:50, halign:'center', align:'center'},
		                {field:'U_PRICE', title:'PRICE', width:100, halign:'center', align:'right'},
		                {field:'AMOUNT', title:'AMOUNT', width:170, halign:'center', align:'right'},
		                {field:'STANDARD_PRICE', title:'STANDARD<br/>PRICE', width:100, halign:'center', align:'right'},
		                {field:'FINAL_DESTINATION', title:'DESTINATION', width:150, halign:'center'},
		                {field:'TRADE_TERM', title:'TRADE<br/>TERM', width:70, halign:'center', align:'center'},
		                {field:'PORT_LOADING', title:'PORT<br/>LOADING', width:150, halign:'center', align:'right'},
		                {field:'ETD', title:'ETD', width:120, halign:'center', align:'center'},
		                {field:'ETA', title:'ETA', width:120, halign:'center', align:'center'},
		                {field:'BL_DATE', title:'BL DATE', width:120, halign:'center', align:'center'},
		                {field:'EX_FACT', title:'EX-FACT<br/>DATE', width:120, halign:'center', align:'center'}
					]]
				});
			}

			$('#dg').datagrid( {
				url: 'sales_report_get.php',
				singleSelect:true
				
			})

		

			//$('#dg').datagrid('enableFilter');
		}
	}

	function print_xls(){
		$('#dg').datagrid('toExcel','sales_report.xls')
		// if(url=='') {
		// 	$.messager.alert('Warning','Data not Found, please click filter data','warning');
		// }else{
		// 	window.open('sales_report_excel.php'+url, '_blank');
		// }
	}

	function print_pdf(){
		if(url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('sales_report_pdf.php'+url, '_blank');
		}	
	}
</script>
</body>
</html>