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
<title>SHIPPING PLAN APPROVE</title>
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
	<fieldset style="float:left;width:540px;border-radius:4px;height:75px;"><legend><span class="style3"><strong> SHIPPING PLAN FILTER </strong></span></legend>
		<div style="width:540px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Ex-Factory Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">PPBE No.</span>
				<select style="width:190px;" name="cmb_ppbe" id="cmb_ppbe" class="easyui-combobox" 
					data-options=" url:'json/json_ppbe_no.php', method:'get',valueField:'ppbe_no',textField:'ppbe_no', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_ppbe_no" id="ck_ppbe_no" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:565px;border-radius:4px;width: 500px;height:75px;"><legend><span class="style3"><strong> ITEM FILTER </strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
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
	<fieldset style="margin-left: 1090px;border-radius:4px;height:75px;"><legend><span class="style3"><strong>ACTION</strong></span></legend>
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 150px;" id="checkmaterial" class="easyui-linkbutton c2" onClick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
		</div>
		<div class="fitem" align="center">
			<a href="javascript:void(0)" style="width: 150px;" id="save_approve" class="easyui-linkbutton c2" onClick="save_approve()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Process Approve</a>
		</div>
	</fieldset>
</div>

<table id="dg" title="SHIPPING PLAN APPROVE" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true"></table>

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

		$('#cmb_ppbe').combobox('disable');
		$('#ck_ppbe_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_ppbe').combobox('disable');
			}else{
				$('#cmb_ppbe').combobox('enable');
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
		var ck_ppbe_no = "false";
		var ck_item_no = "false";

		if ($('#ck_date').attr("checked")) {
			ck_date = "true";
		};

		if ($('#ck_ppbe_no').attr("checked")) {
			ck_ppbe_no = "true";
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
		};

		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			ck_date: ck_date,
			cmb_ppbe : $('#cmb_ppbe').combobox('getValue'),
			ck_ppbe_no: ck_ppbe_no,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no
		});

		$('#dg').datagrid( {
			url: 'shipp_approve_get.php',
			//view: detailview,
			singleSelect: false,
			checkOnSelect: true,
    		selectOnCheck: true,
			columns:[[
		    	{field:'ck', checkbox:true, width:30, halign: 'center'},
			    {field:'CRS_REMARK',title:'PPBE NO.', halign:'center', width:50},
                {field:'EX_FACTORY', title:'EXFACTORY<br>DATE', halign:'center', align:'center', width:70},
                {field:'ETA', title:'ETA<br>DATE', halign:'center', align:'center', width:70},
                {field:'ETD', title:'ETD<br>DATE', halign:'center', align:'center', width:70},
                {field:'ITEM_NO',title:'ITEM NO. ', halign:'center', width:70},
                {field:'DESCRIPTION', title:'DESCRIPTION', halign:'center', width:180},
                {field:'SO_NO', title:'SO NO.', halign:'center', width:75},
                {field:'CUSTOMER_PO_NO', title:'CUST PO NO.', halign:'center', width:100},
                {field:'WORK_NO', title:'WORK NO.', halign:'center', width:120},
                {field:'QTY_ORDER', title:'QTY ORDER', halign:'center', width:75, align:'right'},
                {field:'QTY_PRODUKSI', title:'QTY AVAILABLE', halign:'center', width:75, align:'right'},
                {field:'ANSWER_NO', hidden: true}
			]]/*,
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
			},
			onExpandRow: function(index,row){
				var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
				listbrg.datagrid({
                	title: 'Shipping Plan Approve Detail (PPBE No: '+row.CRS_REMARK+')',
					url:'shipp__approve_get_detail.php?req='+row.CRS_REMARK,
					toolbar: '#ddv'+index,
					singleSelect:true,
					rownumbers:true,
					loadMsg:'load data ...',
					height:'auto',
					rownumbers: true,
					fitColumns: true,
					columns:[[
		                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', align:'center', width:40},
		                {field:'DESCRIPTION', title:'DESCRIPTION', halign:'center', width:150},
		                {field:'SO_NO', title:'SO NO.', halign:'center', align:'center', width:60},
		                {field:'SO_LINE_NO', title:'LINE NO.', halign:'center', align:'center', width:40},
		                {field:'CUSTOMER_PO_NO', title:'CUST PO NO.', halign:'center', width:60},
		                {field:'WORK_NO', title:'WORK NO.', halign:'center', width:100},
		                {field:'QTY_ORDER', title:'ORDER<br>QTY', halign:'center', width:40, align:'right'},
		                {field:'QTY_PRODUKSI', title:'AVAILABLE<br>QTY', halign:'center', width:40, align:'right'},
		                {field:'QTY_PLAN', title:'PLAN<br>QTY', halign:'center', width:40, align:'right'}
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
			}*/
		})

		var col_1 = $('#dg').datagrid('getColumnOption','QTY_ORDER');
		var col_2 = $('#dg').datagrid('getColumnOption','QTY_PRODUKSI');

		col_1.styler = function(){
			return 'background-color:#AAFFFF; color: #000000; font-weight:bold;';
		};

		col_2.styler = function(){
			return 'background-color:#AAFFFF; color: #FF0000; font-weight:bold;';
		};

		$('#dg').datagrid('enableFilter');
		$('#save_approve').linkbutton('enable');
	}

	function save_approve(){
		$.messager.progress({
            msg:'save data...'
        });
		var rows = $('#dg').datagrid('getSelections');
		for(i=0;i<rows.length;i++){
			$('#dg').datagrid('endEdit',i);
			$.post('shipp_approve_save.php',{
				approve_answer: rows[i].ANSWER_NO
			}).done(function(res){
				console.log(res);
			})
		}
		$.messager.alert('INFORMATION','Data Saved','info');
		$.messager.progress('close');
		$('#save_approve').linkbutton('disable');
		$('#dg').datagrid('reload');
	}
</script>
</body>
</html>