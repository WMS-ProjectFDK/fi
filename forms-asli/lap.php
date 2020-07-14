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
<title>REPORT FINISHING</title>
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
	<fieldset style="float:left;width:350px;height: 70px; border-radius:4px;"><legend><span class="style3"><strong>Type Filter</strong></span></legend>
		<div style="width:350px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">DATE</span>
				<select style="width:65px;" name="cmb_date_a" id="cmb_date_a" class="easyui-combobox" data-options=" url:'json/json_date.json', method:'get', valueField:'date', textField:'date', panelHeight:'100px'" required=""></select>
				to 
				<select style="width:65px;" name="cmb_date_z" id="cmb_date_z" class="easyui-combobox" data-options=" url:'json/json_date.json', method:'get', valueField:'date', textField:'date', panelHeight:'100px'" required=""></select>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">TYPE</span>
				<select style="width:150px;" name="cmb_type" id="cmb_type" class="easyui-combobox" data-options="panelHeight:'70px'">
					<option selected=""></option>
					<option value="A">PLAN</option>
					<option value="B">ACTUAL</option>
					<option value="C">BOTH</option>
				</select>
				<label><input type="checkbox" name="ck_type" id="ck_type" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:375px;border-radius:4px;width: 500px;height: 70px;"><legend><span class="style3"><strong>Item Filter</strong></span></legend>
		<div class="fitem">
			<span style="width:80px;display:inline-block;">Item No.</span>
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
			<span style="width:80px;display:inline-block;">Item Name</span>
			<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
		</div>
	</fieldset>
	<fieldset style="margin-left: 900px;border-radius:4px;height: 70px;"><legend><span class="style3"><strong>Print</strong></span></legend></fieldset>
	<div style="padding:5px 6px;">
		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
	</div>
</div>


<table id="dg" title="REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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
		$('#cmb_type').combobox('disable');
		$('#ck_type').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_type').combobox('disable');
			}else{
				$('#cmb_type').combobox('enable');
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
		var a = $('#cmb_date_a').combobox('getValue');
		var z = $('#cmb_date_z').combobox('getValue');

		var ck_type = "false";
		var ck_item_no= "false";

		if ($('#ck_type').attr("checked")) {
			ck_type = "true";
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
		};

		$('#dg').datagrid('load', {
			date_awal: $('#cmb_date_a').combobox('getValue'),
			date_akhir: $('#cmb_date_z').combobox('getValue'),
			ck_type: ck_type,
			cmb_type: $('#cmb_type').combobox('getValue'),
			ck_item_no: ck_item_no,
			cmb_item_no: $('#cmb_item_no').textbox('getValue')
		});

		if(a=='' || ){
			$.messager.alert("WARNING","Date Not Selected..!!","warning");
		}else{
			$('#dg').datagrid({
				url: 'lap_get.php',
			    columns:[[
			    	{field:'ITEM_NO',title:'ITEM NO.', halign:'center', width:60, sortable: true},
			    	{field:'ITEM_NAME', title:'DESCRIPTION', halign:'center', width:150, sortable: true},
			    	{field:'LABEL_TYPE', title:'LABEL_TYPE', halign:'center', width:200, sortable: true},
			    	{field:'WORK_ORDER', title:'WO', halign:'center', width:200, sortable: true},
			    	{field:'GRADE', title:'GRADE', halign:'center', width:200, sortable: true},
			    	{field:'PACKAGING_TYPE', title:'PACKAGING_TYPE', halign:'center', width:200, sortable: true}
			    ]],
			    view: detailview,
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
				},
				onExpandRow: function(index,row){
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					listbrg.datagrid({
	                	title: 'Report Detail (ITEM:'+row.ITEM_NAME+' - WO:'+row.WORK_ORDER+')',
						url:'lap_get_detail.php?item='+row.ITEM_NAME+'&wo='+row.WORK_ORDER+'&dt_a='+a+'&dt_z='+z,
						toolbar: '#ddv'+index,
						singleSelect:true,
						rownumbers:true,
						loadMsg:'load data ...',
						height:'auto',
						rownumbers: true,
						fitColumns: true,
						columns:[[
							{field:'STATUS',title:'STATE', halign:'center', width:80, sortable: true},
							{field:'TOTAL',title:'TOTAL', halign:'center', align:'center', width:80, sortable: true},
							
						]]
	                });
				}
			});
		}
	}

</script>
</body>
</html>