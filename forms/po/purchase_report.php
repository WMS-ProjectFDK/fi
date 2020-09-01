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
<title>PURCHASE REPORT</title>
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
	<fieldset style="float:left;width:700px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong> FILTER DATA</strong></span></legend>
		<div style="width:700px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Select Report</span>
				<select style="width:300px;" name="cmb_report" id="cmb_report" class="easyui-combobox" data-options="panelHeight:'70px'" required="">
					<option value="" selected="true"></option>
 					<option value="1">1. Purchase Payment Material</option>
 					<option value="2">2. Summary Report Purchase Amount by monthly</option>
				</select>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">GR Date</span>
				<input style="width:93px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
				to 
				<input style="width:93px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser"/>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;"></span>
				<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
			</div>
		</div>
	</fieldset>
	<fieldset style="margin-left: 725px;border-radius:4px;height: 100px;width:400px;"><legend><span class="style3"><strong>PRINT DATA</strong></span></legend>
		<div class="fitem" align="center" style="margin-top: 13px;">
			<a href="javascript:void(0)" id="printpdf" style="width: 250px;" class="easyui-linkbutton c2" disabled="true" onclick="print_pdf()"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download To PDF</a>
		</div>
		<div class="fitem" align="center" style="margin-top: -3px;">
			<a href="javascript:void(0)" id="printxls" style="width: 250px;" class="easyui-linkbutton c2" disabled="true" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download To Excel</a>
		</div>
		</div>
	</fieldset>
</div>
<table id="dg" title="PURCHASE REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:500px;" rownumbers="true" singleSelect="true"></table>

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
			$('#dg').datagrid('load', {
				cmbR: $('#cmb_report').combobox('getValue'),
				dt_A: $('#date_awal').datebox('getValue'),
				dt_Z: $('#date_akhir').datebox('getValue')
			});

			url = "?cmbR="+$('#cmb_report').combobox('getValue')
				 +"&dt_A="+$('#date_awal').datebox('getValue')
				 +"&dt_Z="+$('#date_akhir').datebox('getValue')

			if($('#cmb_report').combobox('getValue') == 1){
				$('#dg').datagrid( {
				    fitColumns: true,
				    columns:[[
					    {field:'SUPPLIER', title:'SUPPLIER', width:250, halign:'center'},
					    {field:'STOCK_SUBJECT', title:'SUBJECT', width:150, halign:'center'},
		                {field:'US', title:'USD', width:100, halign:'center', align:'right'},
		                {field:'JP', title:'YEN', width:100, halign:'center', align:'right'},
		                {field:'SGD', title:'SGD', width:100, halign:'center', align:'right'},
		                {field:'RP', title:'IDR', width:100, halign:'center', align:'right'},
		                {field:'TERM', title:'PAYMENT TERMS', width:300, halign:'center'}

					]]
				});
			}else if($('#cmb_report').combobox('getValue') == 2) {
				$('#dg').datagrid( {
					fitColumns: false,
				    columns:[[
					    {field:'SUPPLIER', title:'SUPPLIER', width:250, halign:'center'},
					    {field:'STOCK_SUBJECT', title:'SUBJECT', width:150, halign:'center'},
		                {field:'TOTAL', title:'TOTAL', width:150, halign:'center', align:'right'},
		                {field:'JAN_US', title: 'JAN<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'JAN_JP', title: 'JAN<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'JAN_RP', title: 'JAN<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'JAN_SG', title: 'JAN<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'FEB_US', title: 'FEB<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'FEB_JP', title: 'FEB<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'FEB_US', title: 'FEB<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'FEB_US', title: 'FEB<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'MAR_US', title: 'MAR<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'MAR_JP', title: 'MAR<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'MAR_RP', title: 'MAR<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'MAR_SG', title: 'MAR<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'APR_US', title: 'APR<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'APR_JP', title: 'APR<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'APR_RP', title: 'APR<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'APR_SG', title: 'APR<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'MAY_US', title: 'MAY<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'MAY_JP', title: 'MAY<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'MAY_RP', title: 'MAY<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'MAY_SG', title: 'MAY<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'JUN_US', title: 'JUN<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'JUN_JP', title: 'JUN<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'JUN_RP', title: 'JUN<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'JUN_SG', title: 'JUN<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'JUL_US', title: 'JUL<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'JUL_JP', title: 'JUL<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'JUL_RP', title: 'JUL<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'JUL_SG', title: 'JUL<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'AUG_US', title: 'AUG<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'AUG_JP', title: 'AUG<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'AUG_RP', title: 'AUG<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'AUG_SG', title: 'AUG<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'SEP_US', title: 'SEP<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'SEP_JP', title: 'SEP<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'SEP_RP', title: 'SEP<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'SEP_SG', title: 'SEP<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'OCT_US', title: 'OCT<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'OCT_JP', title: 'OCT<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'OCT_RP', title: 'OCT<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'OCT_SG', title: 'OCT<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'NOV_US', title: 'NOV<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'NOV_JP', title: 'NOV<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'NOV_RP', title: 'NOV<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'NOV_SG', title: 'NOV<br/>SGD', width:100, halign:'center', align:'right'}, 
						{field:'DEC_US', title: 'DEC<br/>USD', width:100, halign:'center', align:'right'}, 
						{field:'DEC_JP', title: 'DEC<br/>JPY', width:100, halign:'center', align:'right'}, 
						{field:'DEC_RP', title: 'DEC<br/>IDR', width:100, halign:'center', align:'right'}, 
						{field:'DEC_SG', title: 'DEC<br/>SGD', width:100, halign:'center', align:'right'}, 
		                {field:'TERM', title:'PAYMENT TERMS', width:300, halign:'center'}
					]]
				});
			}

			$('#dg').datagrid( {
				url: 'purchase_report_get.php',
				singleSelect:true
				
			})
			$('#dg').datagrid('enableFilter');
			$('#printxls').linkbutton('enable');
		}
	}

	function print_xls(){
		if(url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('purchase_report_excel.php'+url, '_blank');
		}
	}

	// function print_pdf(){
	// 	if(url=='') {
	// 		$.messager.alert('Warning','Data not Found, please click filter data','warning');
	// 	}else{
	// 		window.open('purchase_report_pdf.php'+url, '_blank');
	// 	}	
	// }
</script>
</body>
</html>