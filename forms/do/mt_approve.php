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
<title>MATERIALS TRANSACTION APPROVE</title>
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
	<fieldset style="float:left;width:540px;border-radius:4px;height:100px;"><legend><span class="style3"><strong> MATERIALS TRANSACTION FILTER </strong></span></legend>
		<div style="width:540px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip No.</span>
				<select style="width:190px;" name="cmb_slip_no" id="cmb_slip_no" class="easyui-combobox" data-options=" url:'../json/json_slip_no.php', method:'get', valueField:'slip_no', textField:'slip_no', panelHeight:'75px'"></select>
				<label><input type="checkbox" name="ck_slip_no" id="ck_slip_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Slip Type</span>
				<select style="width:300px;" name="cmb_slip_type" id="cmb_slip_type" class="easyui-combobox" data-options=" url:'../json/json_sliptype.php', method:'get', valueField:'id', textField:'name', panelHeight:'auto'"></select>
				<label><input type="checkbox" name="ck_slip_type" id="ck_slip_type" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:565px;border-radius:4px;width: 500px;height:100px;"><legend><span class="style3"><strong> ITEM FILTER </strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<!-- <select style="width:190px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'#', method:'get', valueField:'#', textField:'#', panelHeight:'100px'"></select> -->
			<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
			onSelect:function(rec){
				var spl = rec.id_name_item;
				var sp = spl.split(' - ');
				$('#txt_item_name').textbox('setValue', sp[1]);
			}"></select>
			<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
		</div>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item Name</span>
			<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox"></input>
		</div>
		<div class="fitem"></div><div class="fitem"></div><br/>
	</fieldset>
	<fieldset style="margin-left: 1090px;border-radius:4px;height:100px;"><legend><span class="style3"><strong>ACTION</strong></span></legend>
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 150px;height: 35px;" id="checkmaterial" class="easyui-linkbutton c2" onClick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Material</a>
		</div>
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 150px;height: 35px;" id="save_approve" class="easyui-linkbutton c2" onClick="save_approve()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Process Approve</a><br/>
			<span style="font-size: 8px;color:red;">*) max approve 10 trasaction</span>
		</div>
		<div class="fitem" align="center">

		</div>
	</fieldset>
</div>

<table id="dg" title="MATERIALS APPROVE" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true"></table>

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
		$('#ck_date').change(function(){
			if ($(this).is(':checked')) {
				$('#date_awal').datebox('disable');
				$('#date_akhir').datebox('disable');
			}else{
				$('#date_awal').datebox('enable');
				$('#date_akhir').datebox('enable');
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

		$('#cmb_slip_type').combobox('disable');
		$('#ck_slip_type').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_slip_type').combobox('disable');
			}else{
				$('#cmb_slip_type').combobox('enable');
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
		$('#ck_item_name').change(function(){
			if ($(this).is(':checked')) {
				$('#txt_item_name').textbox('disable');
			}else{
				$('#txt_item_name').textbox('enable');
			}
		});

		$('#save_approve').linkbutton('disable');
	});

	function filterData(){
		var ck_date = "false";
		var ck_slip_no = "false";
		var ck_slip_type = "false";
		var ck_item_no = "false";
		var flag = 0;

		if ($('#ck_date').attr("checked")) {
			ck_date = "true";
			flag += 1;
		};

		if ($('#ck_slip_no').attr("checked")) {
			ck_slip_no = "true";
			flag += 1;
		};

		if ($('#ck_slip_type').attr("checked")) {
			ck_slip_type = "true";
			flag += 1;
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
			flag += 1;
		};

		if(flag == 4) {
			$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
		}

		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			ck_date: ck_date,
			cmb_slip_no : $('#cmb_slip_no').combobox('getValue'),
			ck_slip_no: ck_slip_no,
			cmb_slip_type: $('#cmb_slip_type').combobox('getValue'),
			ck_slip_type: ck_slip_type,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no
		});

		$('#dg').datagrid( {
			url: 'mt_approve_get.php',
			view: detailview,
			singleSelect: false,
			checkOnSelect: true,
    		selectOnCheck: true,
			columns:[[
		    	{field:'ck', checkbox:true, width:30, halign: 'center'},
			    {field:'SLIP_NO',title:'SLIP NO.', halign:'center', width:150, sortable: true, sortable: true},
                {field:'SLIP_DATE', title:'SLIP DATE', halign:'center', align:'center', width:50, sortable: true},
                {field:'SLIP_DATE_A', title:'SLIP DATE', halign:'center', align:'center', width:50, sortable: true, hidden: true},
                {field:'COMPANY_CODE',hidden:true},
                {field:'SLIP_TYPE',hidden:true},
                {field:'SLIP_NM', title:'SLIP TYPE', halign:'center', width:70},
                {field:'COMPANY',title:'ISSUE TO', halign:'center', width:200, sortable: true},
                {field:'PERSON_CODE', title:'USER ENTRY', halign:'center', width:70},
                {field:'STS', hidden:true},//
                {field:'STS_NM', title:'STATUS STOCK', halign:'center', align:'center', width:70}
			]],
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
			},
			onExpandRow: function(index,row){
				var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
				listbrg.datagrid({
                	title: 'Materials Approve Detail (No: '+row.SLIP_NO+')',
					url:'mt_approve_get_detail.php?req='+row.SLIP_NO,
					toolbar: '#ddv'+index,
					singleSelect:true,
					rownumbers:true,
					loadMsg:'load data ...',
					height:'auto',
					rownumbers: true,
					fitColumns: true,
					columns:[[
						{field:'SLIP_NO',title:'Slip No.', halign:'center', width:150, sortable: true, hidden:true},
		                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:80, sortable: true},
		                {field:'DESCRIPTION', title:'Material Name', halign:'center', width:170},
		                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:40},
		                {field:'QTY', title:'QTY', halign:'center', align:'right', width:50},
		                {field:'STOCK', title:'STOCK', halign:'center', align:'right', width:50},
		                {field:'RACK', title:'RACK ADDRESS', halign:'center', width:150}
					]],
					onResize:function(){
						//alert(index);
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
		$('#dg').datagrid('enableFilter');
		$('#save_approve').linkbutton('enable');

		$('#dg').datagrid({
			rowStyler: function (index, row) {
				if (row.STS > 0) {
					return 'background-color: red; color: white; font-weight: bold;';
				}
			}
		});
	}

	function save_approve(){
		var ArrApprove = [];
		$.messager.progress({
            msg:'save data...'
        });

		var rows = $('#dg').datagrid('getSelections');
		if(rows.length > 10){
			$.messager.alert('MATERIALS APPROVE','Approve max=10 slip No.','warning');	
			$.messager.progress('close');
		}else if(rows.length == 0){
			$.messager.alert('MATERIALS APPROVE','Slip No. not selected','warning');
			$.messager.progress('close');
		}else{
			for(i=0;i<rows.length;i++){
				$('#dg').datagrid('endEdit',i);
				if(rows[i].STS == 0){
					ArrApprove.push(rows[i].SLIP_NO);
				}
			}
			$.ajax({
			  	type: "POST",
			  	url: 'mt_approve_save.php?approve_slip='+ArrApprove,
			  	data: { kode:'kode' },
			  	success: function(data){
					if(data[0].kode == 'success'){
						$.messager.alert('INFORMATION','Data Saved','info');
						$.messager.progress('close');
						$('#save_approve').linkbutton('disable');
						$('#dg').datagrid('reload');
					}else{
						$.messager.alert('WARNING',data[0].kode,'warning');
						$.messager.progress('close');
					}
				}
			});
		}
	}
</script>
</body>
</html>