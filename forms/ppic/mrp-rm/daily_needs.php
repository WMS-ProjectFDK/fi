<?php
/*// Create By : Ueng hernama
// Date : 29-Sept-2017
// ID = 2*/
include("../../../connect/conn.php");
session_start();
//require_once('../../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>DAILY NEEDS RM</title>
<link rel="icon" type="image/png" href="../../../favicon.png">
<script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
</script> 
<link rel="stylesheet" type="text/css" href="../../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../../themes/color.css" />
<script type="text/javascript" src="../../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../../js/jquery.edatagrid.js"></script>
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
<?php include ('../../../ico_logout.php'); ?>

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:500px;height: 50px; border-radius:4px;"><legend><span class="style3"><strong>Period Filter</strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">PERIOD</span>
				<input style="width:150px;" name="cmb_bln" id="cmb_bln" class="easyui-combobox" data-options="url:'../../json/json_month.json', method:'get', valueField:'id', textField:'month', panelHeight:'150px'"/>
				<input style="width:100px;" name="cmb_years" id="cmb_years" class="easyui-combobox" data-options="url:'../../json/json_years.php', method:'get', valueField:'th', textField:'th', panelHeight:'150px'"/>
				<label><input type="checkbox" name="ck_period" id="ck_period" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:525px;border-radius:4px;width: 500px;height: 50px;"><legend><span class="style3"><strong>Type Filter</strong></span></legend>
		<div class="fitem">
			<span style="width:110px;display:inline-block;">TYPE</span>
			<input style="width:200px;" name="cmb_type" id="cmb_type" class="easyui-combobox" data-options="url:'../../json/json_groupRM.php', method:'get', valueField:'tipe', textField:'tipe', panelHeight:'50px'"/>
			<label><input type="checkbox" name="ck_type" id="ck_type" checked="true">All</input></label>
		</div>
	</fieldset>
	<fieldset style="margin-left: 1050px;border-radius:4px;height: 50px;"><legend><span class="style3"><strong>Print</strong></span></legend></fieldset>
	<div style="padding:5px 6px;">
    	<span style="width:50px;display:inline-block;">SEARCH</span>
		<input style="width:200px; height: 19px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress = "filter (event) " name="src" id="src" type="text" />
		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="create_dn()"><i class="fa fa-plus" aria-hidden="true"></i> Add Needs</a>
		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="edit_dn()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Needs</a>
		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="delete_dn()"><i class="fa fa-trash" aria-hidden="true"></i> Delete Needs</a>
	</div>
</div>

<!-- START CREATE DN -->
<div id="dlg_add" class="easyui-dialog" style="width:1150px;height: auto;padding:5px 5px" closed="true" buttons="#dlg-buttons-add" data-options="modal:true, position: 'center'">
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width: 1100px; float:left; margin:5px;">
		<form id="upd" method="post" enctype="multipart/form-data" novalidate>
			<div class="fitem">
				<a href="daily_needs_excel.php" style="width:250px;display:inline-block;" class="easyui-linkbutton c2"><i class="fa fa-table" aria-hidden="true"></i> Download Format Excel</a>
				<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:250px;" data-options="prompt:'Choose a file...'">
				<a href="javascript:void(0)" id="btnUpload" class="easyui-linkbutton c2" onclick="uploaddata()" style="width:250px;">
					<i class="fa fa-download" aria-hidden="true"></i> Upload Daily Needs
				</a>
			</div>
		</form>
	</fieldset>
</div>
<!-- END CREATE DN -->

<div id="dlg_edit" class="easyui-dialog" style="width:380px;height: 200px;padding:5px 5px" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true, position: 'center'">
	<div class="fitem">
		<span style="width:110px;display:inline-block;">DN NO.</span>
		<input style="width: 205px;" name="dn_edit" id="dn_edit" class="easyui-textbox" disabled="true" />
	</div>
	<div class="fitem">
		<span style="width:110px;display:inline-block;">TYPE</span>
		<input style="width: 205px;" name="type_edit" id="type_edit" class="easyui-textbox" disabled="true" />
	</div>
	<div class="fitem">
		<span style="width:110px;display:inline-block;">PERIOD</span>
		<input style="width: 205px;" name="period_edit" id="period_edit" class="easyui-textbox" disabled="true" />
	</div>
	<div class="fitem">
		<span style="width:110px;display:inline-block;">QTY</span>
		<input style="width: 205px;" name="qty_edit" id="qty_edit" class="easyui-textbox"/>
	</div>
</div>

<div id="dlg-buttons-edit">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save_edit()" style="width:90px">Save</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
</div>

<table id="dg" title="DAILY NEEDS SETTING" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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
		$('#cmb_bln').combobox('disable');
		$('#cmb_years').combobox('disable');
		$('#ck_period').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_bln').combobox('disable');
				$('#cmb_years').combobox('disable');
			}else{
				$('#cmb_bln').combobox('enable');
				$('#cmb_years').combobox('enable');
			}
		});

		$('#cmb_type').combobox('disable');
		$('#ck_type').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_type').combobox('disable');
			}else{
				$('#cmb_type').combobox('enable');
			}
		});

	});

	function filter(event){
		var src = document.getElementById('src').value;
		var search = src.toUpperCase();
		document.getElementById('src').value = search;
		
	    if(event.keyCode == 13 || event.which == 13){
			var src = document.getElementById('src').value;
			//alert(src);
			$('#dg').datagrid('load', {
				src: search
			});

			$('#dg').datagrid( {
				url: 'daily_needs_get.php',
				columns:[[
				    {field:'ID_NEEDS',title:'DN NO.', halign:'center', width:80, sortable: true, sortable: true},
	                {field:'TIPE', title:'TIPE', halign:'center', width:150, sortable: true},
	                {field:'BULAN',title:'MONTH', halign:'center', align:'center', width:50},
	                {field:'TAHUN',title:'YEAR', halign:'center', align:'center', width:50},
	                {field:'QTY_NEEDS', title:'QTY', halign:'center', align:'right', width:100}
				]]
			});

			$('#dg').datagrid('enableFilter');

			if (src=='') {
				filterData();
			};
			//document.getElementById('src').value = '';
	    }
	}

	function filterData(){
		var ck_per = "false";
		var ck_typ = "false";

		if ($('#ck_period').attr("checked")) {
			ck_per = "true";
		};

		if ($('#ck_type').attr("checked")) {
			ck_typ = "true";
		};

		$('#dg').datagrid('load', {
			bln: $('#cmb_bln').combobox('getValue'),
			thn: $('#cmb_years').combobox('getValue'),
			ck_per: ck_per,
			typ: $('#cmb_type').combobox('getValue'),
			ck_typ: ck_typ,
			src: ''
		});

		$('#dg').datagrid( {
			url: 'daily_needs_get.php',
		    columns:[[
			    {field:'ID_NEEDS',title:'DN NO.', halign:'center', width:80, sortable: true, sortable: true},
                {field:'TIPE', title:'TYPE', halign:'center', width:150, sortable: true},
                {field:'BULAN',title:'MONTH', halign:'center', align:'center', width:50},
                {field:'TAHUN',title:'YEAR', halign:'center', align:'center', width:50},
                {field:'QTY_NEEDS', title:'QTY', halign:'center', align:'right', width:100}
			]]
		});

		$('#dg').datagrid('enableFilter');
	}

	function create_dn(){
		$('#dlg_add').dialog('open').dialog('setTitle','ADD DAILY NEEDS');
	}

	function uploaddata() {
	$('#upd').form('submit',{
		url: 'daily_needs_upload.php',
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			$.messager.alert('DAILY NEEDS',result,'info');
			//alert(result);
	 		$('#fileexcel').filebox('clear');
			$('#dlg_add').dialog('close');
			}
		});
	}

	function edit_dn(){
		var row = $('#dg').datagrid('getSelected');	
		if (row){
			$('#dlg_edit').dialog('open').dialog('setTitle','UPDATE DAILY NEEDS (No. '+row.ID_NEEDS+')');
			$('#dn_edit').textbox('setValue',row.ID_NEEDS);
			$('#type_edit').textbox('setValue',row.TIPE);
			$('#period_edit').textbox('setValue',row.BULAN+' - '+row.TAHUN);
			$('#qty_edit').textbox('setValue',row.QTY_NEEDS);
		}
	}

	function save_edit(){
		$.post('daily_needs_edit.php',{
			dn_no: $('#dn_edit').textbox('getValue'),
			dn_qt: $('#qty_edit').textbox('getValue').replace(/,/g,'')
		}).done(function(res){
			//alert(res);
			console.log(res);
			$.messager.alert('INFORMATION','Update Data Success..!!','info');
			$('#dlg_edit').dialog('close');
			$('#dg').datagrid('reload');
		})
	}

	function delete_dn(){
		var row = $('#dg').datagrid('getSelected');	
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
				if(r){
					$.post('daily_needs_destroy.php',{dn_no: row.ID_NEEDS},function(result){
						if (result.success){
                            $('#dg').datagrid('reload');
                        }else{
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
					},'json');
				}
			});
		}else{
			$.messager.show({title: 'Daily Needs',msg:'Data Not select'});
		}
	}
</script>
</body>
</html>