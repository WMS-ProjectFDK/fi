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
<title>MATERIALS TRANSACTION VIEW</title>
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
	<fieldset style="float:left;width:450px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>Materials Transaction Filter</strong></span></legend>
		<div style="width:450px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to  
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip No.</span>
				<select style="width:250px;" name="cmb_slip_no" id="cmb_slip_no" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'slip_no', panelHeight:'75px'"></select>
				<label><input type="checkbox" name="ck_slip_no" id="ck_slip_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">WO No.</span>
				<select style="width:250px;" name="cmb_wo" id="cmb_wo" class="easyui-combobox" data-options=" url:'../json/json_wo_no.php', method:'get', valueField:'wo_no', textField:'wo_no', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_wo" id="ck_wo" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:475px;border-radius:4px;width: 500px;height: 100px;"><legend><span class="style3"><strong>Item Filter</strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
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
		</div><br/><br/>
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
<table id="dg" title="MATERIALS TRANSACTION VIEW" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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
		$('#cmb_slip_no').combobox('disable');
		$('#ck_slip_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_slip_no').combobox('disable');
			}else{
				$('#cmb_slip_no').combobox('enable');
			}
		});

		$('#cmb_wo').combobox('disable');
		$('#ck_wo').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_wo').combobox('disable');
			}else{
				$('#cmb_wo').combobox('enable');
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
	})

	function filterData(){
		var ck_slip_no = 'false';
		var ck_wo = 'false';
		var ck_item_no = 'false';

		if($('#ck_slip_no').attr("checked")){
			ck_slip_no='true';
		}
		if($('#ck_wo').attr("checked")){
			ck_wo='true';
		}

		if($('#ck_item_no').attr("checked")){
			ck_item_no='true';
		}
		
		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			slip_no: $('#cmb_slip_no').combobox('getValue'),
			ck_slip: ck_slip_no,
			wo_no: $('#cmb_wo').combobox('getValue'),
			ck_wo: ck_wo,
			item_no : $('#cmb_item_no').combobox('getValue'),
			ck_item : ck_item_no
		});

		$('#dg').datagrid({
	    	url:'view_mt_get.php',
	    	singleSelect: true,
		    fitColumns: true,
			rownumbers: true,
			sortName: 'po_date',
			sortOrder: 'asc',
		    columns:[[
			    {field:'ID',title:'KANBAN ID',width:75, halign: 'center', sortable:true},
			    {field:'UPPER_ITEM',title:'UPPER ITEM',width:65, halign: 'center', align: 'center', sortable:true},
			    {field:'BRAND',title:'BRAND',width:200, halign: 'center', sortable:true},
			    {field:'WO_NO',title:'WO No.',width:150, halign: 'center', sortable:true},
			    {field:'PLT_NO',title:'PALLET',width:50, halign: 'center', align: 'center', sortable:true},
			    {field:'SLIP_NO',title:'SLIP No.',width:150, halign: 'center', sortable:true},
			    {field:'SLIP_DATE',title:'SLIP DATE', width:80, halign: 'center', align: 'center'},
			    {field:'STS_APPROVE',title:'STATUS', width:120, halign: 'center', align: 'center'}
		    ]],
		    view: detailview,
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
			},
			onExpandRow: function(index,row){
				var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
				listbrg.datagrid({
                	title: 'Material Transaction Detail (KANBAN ID: '+row.ID+')',
                	url:'view_mt_get_detail.php?id='+row.ID,
					toolbar: '#ddv'+index,
					singleSelect:true,
					rownumbers:true,
					loadMsg:'load data ...',
					height:'auto',
					rownumbers: true,
					fitColumns: true,
					columns:[[
		                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:60, sortable: true},
		                {field:'ITEM', title:'Material Name', halign:'center', width:100},
		                {field:'DESCRIPTION', title:'Description', halign:'center', width:200},
		                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:40},
		                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70}
					]],
					onResize:function(){
						$('#dg').datagrid('fixDetailRowHeight',index);
					},
					onLoadSuccess:function(){
						setTimeout(function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},0);
					}
                });
			}
		})
		var dg = $('#dg').datagrid();
		dg.datagrid('enableFilter');
	}

</script>
</body>
</html>