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
<title>SLOW MOVING</title>
<link rel="icon" type="image/png" href="../favicon.png">
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
	<fieldset style="float:left;border-radius:4px;width: 45%;height: 40px;"><legend><span class="style3"><strong> SELECT CUT OFF </strong></span></legend>
	   <div id="select_stock">
		<div class="fitem">
		  <!-- <span style="width:180px;display:inline-block;"><input type="radio" name="cut_off_days" id="d_90" value="90"/> 90 Days</span> -->
		  <span style="width:180px;display:inline-block;"><input type="radio" name="cut_off_days" id="d_180" value="180"/> 180 Days</span>
		  <span style="width:180px;display:inline-block;"><input type="radio" name="cut_off_days" id="d_270" value="270"/> 270 Days</span>
		  <span style="width:180px;display:inline-block;"><input type="radio" name="cut_off_days" id="d_360" value="260"/> 360 Days</span>
		</div>
	   </div>
	</fieldset>
	<fieldset style="margin-left: 46%;border-radius:4px;height: 40px;"><legend><span class="style3"><strong>PRINT DATA</strong></span></legend>
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" onclick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
			<a href="javascript:void(0)" id="printpdf" style="width: 150px;" class="easyui-linkbutton c2" onclick="print_pdf()"><i class="fa fa-file-pdf-o" aria-hidden="true" disabled="true"></i> Print To PDF</a>
			<a href="javascript:void(0)" id="printxls" style="width: 150px;" class="easyui-linkbutton c2" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true" disabled="true"></i> Print To Excel</a>
		</div>
	</fieldset>
</div>
<table id="dg" title="SLOW MOVING" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" singleSelect="true"></table>

<div id='dlg_detail' class="easyui-dialog" style="width:100%;height:400px;" closed="true" data-options="modal:true">
	<table id="dg_detail" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" showFooter="true"></table>
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
		document.getElementById('d_180').checked=true;
		$('#printpdf').linkbutton('disable');
		$('#printxls').linkbutton('disable');

		$('#dg').datagrid({
	    	url:'slow_moving_get.php?rdo_sts=0',
	    	singleSelect: true,
			rownumbers: true,
		    frozenColumns:[[
			    {field:'ITEM_NO',title:'ITEM NO.',width:65, halign: 'center'},
			    {field:'DESC',title:'DESCRIPTION',width:300, halign: 'center'},
			    {field:'LAST_INVENTORY',title:'LAST<br/>INVENTORY',width:100, halign: 'center', align: 'right'},
			    {field:'STANDARD_PRICE',title:'STANDARD<br/>PRICE',width:100, halign: 'center', align: 'right'},
		    ]],
		    columns:[[
		    	{field:'SUPPLIER',title:'SUPPLIER',width:250, halign: 'center'},
		    	{field:'GR_NO',title:'GOODS RECEVIE<br/>NO.',width:120, halign: 'center'},
		    	{field:'QTY',title:'GOODS RECEVIE<br/>QTY',width:120, halign: 'center', align: 'right'},
		    	{field:'LAST_DATE_BUY',title:'GOODS RECEIVE<br/>DATE',width:120, halign: 'center', align: 'center'},
		    	{field:'TRANSACTION_TYPE',title:'SLIP NAME',width:250, halign: 'center'},
		    	{field:'TRANSACTION_QTY',title:'SLIP QTY',width:100, halign: 'center', align: 'right'},
		    	{field:'TRANSACTION_DATE',title:'SLIP DATE',width:100, halign: 'center', align: 'center'}
		    ]]
		});

		var dg = $('#dg').datagrid();
		dg.datagrid('enableFilter');
	})

	var pdf_url='';

	function filterData(){
		var order;
		if(document.getElementById('d_180').checked == true){
			order = document.getElementById('d_180').value;
		}else if(document.getElementById('d_270').checked == true){
			order = document.getElementById('d_270').value;
		}else if(document.getElementById('d_360').checked == true){
			order = document.getElementById('d_360').value;
		}else{
			order=0;
		}
		
		//alert (order);
		$('#dg').datagrid('load', {
			rdo_sts: order
		});

		$('#printpdf').linkbutton('enable');
		$('#printxls').linkbutton('enable');
	}

	function print_xls(){
		window.open('slow_moving_xls.php', '_blank');
	}

	function print_pdf(){
		window.open('slow_moving_pdf.php', '_blank');
	}
</script>
</body>
</html>