<?php
require("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ASSET REPORT</title>
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
<script type="text/javascript" src="../js/canvasjs.min.js"></script>
<script type="text/javascript" src="../js/datagrid-export.js"></script>

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
.datagrid-row-alt{
		background: #F8F8F8;
	}
</style>
</head>

<body>
<?php include ('../ico_logout.php'); ?>
<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="margin-left;border-radius:4px;height:110px;width:98%"><legend><span class="style3"><strong> ASSET REPORT FILTER</strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Group No.</span>
				<select style="width:250px;" name="cmb_category_no" id="cmb_category_no" class="easyui-combobox" data-options=" url:'json/json_asset.php?tipe=CATEGORY',method:'get',valueField:'VALUE_VAL',textField:'TEXT_VAL', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_category" id="ck_category" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Category</span>
				<select style="width:250px;" name="cmb_group_no" id="cmb_group_no" class="easyui-combobox" data-options="  url:'json/json_asset.php?tipe=GROUP',method:'get',valueField:'VALUE_VAL',textField:'TEXT_VAL', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_group_no" id="ck_group_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Asset No.</span>
				<select style="width:250px;" name="cmb_ast_no" id="cmb_ast_no" class="easyui-combobox" data-options="  url:'json/json_asset.php?tipe=ASSET',method:'get',valueField:'VALUE_VAL',textField:'TEXT_VAL', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_ast_no" id="ck_ast_no" checked="true">All</input></label>
			</div>
		</div>
		<div style="width:370px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Line Machine No.</span>
				<select style="width:200px;" name="cmb_line_no" id="cmb_line_no" class="easyui-combobox" data-options="  url:'json/json_asset.php?tipe=CLAS',method:'get',valueField:'VALUE_VAL',textField:'TEXT_VAL', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_line_no" id="ck_line_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Location/Process</span>
				<select style="width:130px;" name="cmb_location_no" id="cmb_location_no" class="easyui-combobox" data-options="  url:'json/json_asset.php?tipe=SPEC',method:'get',valueField:'VALUE_VAL',textField:'TEXT_VAL', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_Location_no" id="ck_Location_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Report Type.</span>
				<select id="cmb_type" class="easyui-combobox" name="cmb_type" style="width:130px;" required="true" text = "SUMMARY">
					<option value="SUMMARY">SUMMARY</option>
					<option value="DETAIL">DETAIL</option>
					<option value="COMPONENT">COMPONENT</option>
				</select>	
			</div>
		</div>
		<div style="width:330px;float:left">
			<div class="fitem" align="center">
				<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:120px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			</div>
			<div class="fitem" align="center">
				<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="print_xls()" style="width:120px;"><i class="fa fa-download" aria-hidden="true"></i> Excel Report</a>
			</div>
			<div class="fitem" align="center">
				<a href="javascript:void(0)" id="btnPrint" class="easyui-linkbutton c2" onClick="print_pdf()" style="width:120px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
			</div>
		</div>
	</fieldset>
</div>

<table id="dg" title="Asset Report" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" selectOnCheck= "true">	
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
		$('#btnPrint').linkbutton('disable');
		$('#cmb_category_no').combobox('disable');
		$('#ck_category').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_category_no').combobox('disable');
			}else{
				$('#cmb_category_no').combobox('enable');
			}
		});


		$('#cmb_ast_no').combobox('disable');
		$('#ck_ast_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_ast_no').combobox('disable');
			}else{
				$('#cmb_ast_no').combobox('enable');
			}
		});

		$('#cmb_group_no').combobox('disable');
		$('#ck_group_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_group_no').combobox('disable');
			}else{
				$('#cmb_group_no').combobox('enable');
			}
		});
		

		$('#cmb_line_no').combobox('disable');
		$('#ck_line_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_line_no').combobox('disable');
			}else{
				$('#cmb_line_no').combobox('enable');
			}
		});

		$('#cmb_location_no').combobox('disable');
		$('#ck_Location_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_location_no').combobox('disable');
			}else{
				$('#cmb_location_no').combobox('enable');
			}
		});
	});

	var printUrl = '';
	function filterData(){
		var ck_category = "false";
		var ck_ast_no = "false";
		var ck_group_no = "false";
		var ck_line_no = "false";
		var ck_Location_no = "false";
		var flag = 0;
		var url = 'asset_report_get.php?tipe='+$('#cmb_type').combobox('getValue')
		printUrl = '?tipe='+$('#cmb_type').combobox('getValue');
		
		if($('#cmb_type').combobox('getValue')=="COMPONENT"){
			$.messager.alert('Warning','This report type is excel only','warning');
		}

		if ($('#ck_category').attr("checked")) {
			ck_category = "true";
			flag += 1;
			url = url + '&category=';
			printUrl = printUrl + '&category=';
		}else{
			url = url + '&category='+$('#cmb_category_no').combobox('getText');
			printUrl = printUrl + '&category='+$('#cmb_category_no').combobox('getText');
		}

		if ($('#ck_ast_no').attr("checked")) {
			ck_ast_no = "true";
			flag += 1;
			url = url + '&ast=';
			printUrl = printUrl + '&ast=';
		}else{
			url = url + '&ast='+$('#cmb_ast_no').combobox('getText');	
			printUrl = printUrl + '&ast='+$('#cmb_ast_no').combobox('getText');	
		} 

		if ($('#ck_group_no').attr("checked")) {
			ck_group_no = "true";
			flag += 1;
			url = url + '&group=';
			printUrl = printUrl + '&group=';
		}else{
			url = url + '&group='+$('#cmb_group_no').combobox('getText');	
			printUrl = printUrl + '&group='+$('#cmb_group_no').combobox('getText');	
		} 

		var line = $('#cmb_line_no').combobox('getValue')
		
		if ($('#ck_line_no').attr("checked")) {
			ck_line_no = "true";
			flag += 1;
			url = url + '&line=';
			printUrl = printUrl + '&line=';
	    }else{
	    	url = url + '&line='+line.replace(/#/g,'*');	
	    	printUrl = printUrl + '&line='+line.replace(/#/g,'*');	
	    } 

		if ($('#ck_Location_no').attr("checked")) {
			ck_Location_no = "true";
			flag += 1;
			url = url + '&location='
			printUrl = printUrl + '&location='
	    }else{
	    	url = url + '&location='+$('#cmb_location_no').combobox('getText');	
	    	printUrl = printUrl + '&location='+$('#cmb_location_no').combobox('getText');	
	    }

		if($('#cmb_type').combobox('getValue') == 'DETAIL'){
			$('#dg').datagrid( {
				url: url,
				singleSelect:true,
				showFooter: true,
				loadMsg:'Load Data, Please Wait ...',
				rownumbers: true,
				fitColumns: true,
				striped: true,
			    columns:[[
				    {field:'CATEGORY', title:'CATEGORY', width:90, halign:'left'},
	                {field:'GRP', title:'GROUP', width:80, halign:'center', align:'left'},
	                {field:'GRPDESC', title:'GROUP NAME', width:250, halign:'center', align:'left'},
				    {field:'ASTNO', title:'ASSET ID', width:80, halign:'center', align:'left'},
	                {field:'DESC_NAME', title:'ASSET NAME', width:350, halign:'center', align:'left'},
					{field:'LINE', title:'LINE', width:50, halign:'center', align:'left'},
					{field:'LOCATION', title:'LOCATION', width:120, halign:'center', align:'left'},
					{field:'ACQDATE', title:'ACQ DATE', width:100, halign:'center', align:'left'},
					{field:'VENDOR', title:'VENDOR NAME', width:150, halign:'center', align:'left'},
					{field:'INVOICE', title:'INVOICE', width:100, halign:'center', align:'left'},
	                {field:'ACQ_VALUE', title:'ACQ COST', width:100, halign:'center', align:'right' },
					{field:'ACCUMULATED_DEP', title:'ACCUM DEPRE', width:100, halign:'center', align:'right'},
	                {field:'BOOK_VALUE', title:'BOOK VALUE', width:100, halign:'center', align:'right'}
				]]
			});
		}else{
			$('#dg').datagrid( {
				url: url,
			    singleSelect:true,
				showFooter: true,
				loadMsg:'Load Data, Please Wait ...',
				rownumbers: true,
				fitColumns: true,
				striped: true,
			    columns:[[
				    {field:'CATEGORY', title:'CATEGORY', width:80, halign:'center'},
	                {field:'GRP', title:'GROUP', width:80, halign:'center'},
	                {field:'GRPDESC', title:'GROUP NAME', width:200, halign:'center'},
				    {field:'LINE', title:'LINE', width:50, halign:'center'},
					{field:'LOCATION', title:'LOCATION', width:250, halign:'center'},
					{field:'ACQDATE', title:'ACQ DATE', width:100, halign:'center', align:'left'},
					{field:'STAT', title:'STATUS', width:50, halign:'center'},
					{field:'ACQ_VALUE', title:'ACQ COST', width:100, halign:'center', align:'right'},
					{field:'ACCUMULATED_DEP', title:'ACCUM DEPRE', width:100, halign:'center', align:'right'},
	                {field:'BOOK_VALUE', title:'BOOK VALUE', width:100, halign:'center', align:'right'}
					
				]]
			});
		}

		if ($('#cmb_type').combobox('getValue') != 'SUMMARY'){
			$('#btnPrint').linkbutton('enable');
		}
	}

	var formatter = new Intl.NumberFormat('en-US', {maximumSignificantDigits: 3});
	function formattgl(tgl){
	    var hari = tgl.substring(0,2);
	    var bulan = getMonthFromString(tgl.substring(3,6));
	    var tahun = tgl.substring(7,9);

	    if (bulan<10) {
	    	bulan = '0'+bulan;
	    }
	    
		return '20'+tahun+'-'+bulan+'-'+hari;
	};

	function getMonthFromString(mon){
		return new Date(Date.parse(mon +" 1, 2012")).getMonth()+1
	}	

	function formatvalue(val,row){
	    if (val < 0){
	        return '<span style="color:red;">('+formatter.format(val)+')</span>';
	    } else {
	        return formatter.format(val);
	    }
	}

	function print_xls(){
		var url = 'http://172.23.225.85/wms/forms/asset_report_comp_get.php?';
		var flag = 0;
			
		if ($('#ck_category').attr("checked")) {
			ck_category = "true";
			flag += 1;
			url = url + '&category=';
			
		}else url = url + '&category='+$('#cmb_category_no').combobox('getText');

		if ($('#ck_ast_no').attr("checked")) {
			ck_ast_no = "true";
			flag += 1;
			url = url + '&ast=';
			
		}else url = url + '&ast='+$('#cmb_ast_no').combobox('getText');

		if ($('#ck_group_no').attr("checked")) {
			ck_group_no = "true";
			flag += 1;
			url = url + '&group=';
			
		}else url = url + '&group='+$('#cmb_group_no').combobox('getText');

		

		if ($('#ck_line_no').attr("checked")) {
			ck_line_no = "true";
			flag += 1;
			url = url + '&line=';
			
	    }else url = url + '&line='+$('#cmb_line_no').combobox('getText');
	    

		if ($('#ck_Location_no').attr("checked")) {
			ck_Location_no = "true";
			flag += 1;
			url = url + '&location='
			
	    }else url = url + '&location='+$('#cmb_location_no').combobox('getText');
	   
		if($('#cmb_type').combobox('getValue')=="COMPONENT"){
			window.open(url);
		}
		else{
			$('#dg').datagrid('toExcel','asset_report.xls');
		}		
	}

	function print_pdf(){
		if(printUrl != ''){
			if ($('#cmb_type').combobox('getValue') == 'SUMMARY'){
				$.messager.alert('Warning','Report type Summary cannot be printed','warning');
			}else{
				window.open('asset_report_pdf.php'+printUrl);	
			}
		}else{
			$.messager.show({
				title: 'ASSET REPORT',
				msg: 'Data Not Found'
			});
		}
	}
</script>
</body>
</html>