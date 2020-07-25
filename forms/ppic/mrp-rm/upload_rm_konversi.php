<?php
session_start();
include("../../../connect/conn.php");
//require_once('	../../../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>UPLOAD RM KONVERSI</title>
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
	<table id="dg" title="UPLOAD RM KONVERSI" class="easyui-datagrid" style="width:100%;height:500px;" toolbar="#toolbar" fitcolumns="true" rownumbers="true"></table>
	<div id="toolbar">
		<fieldset style="float:left;width:800px;border-radius:4px;height: 80px;"><legend><span class="style3"><strong>Raw Material Konversi Filter</strong></span></legend>
			<div style="width:300px; float:left;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Cell Type</span>
					<select style="width:120px;" name="cmb_cell_type" id="cmb_cell_type" class="easyui-combobox" data-options="panelHeight:'auto'">
						<option selected="" value=""></option>
						<option value="C01">C01</option>
						<option value="C01NC">C01NC</option>
						<option value="G06">G06</option>
						<option value="G06NC">G06NC</option>
						<option value="G07">G07</option>
						<option value="G07NC">G07NC</option>
						<option value="G08">G08</option>
						<option value="G08NC">G08NC</option>
						<option value="G08E">G08E</option>
					</select>
					<label><input type="checkbox" name="ck_cell_type" id="ck_cell_type" checked="true">All</input></label>
					<!-- <span style="width:100px;display:inline-block;"></span>
					<label><input type="checkbox" name="ck_use" id="ck_use" checked="true">USED</input></label> -->
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Assembly Line</span>
					<select style="width:120px;" name="cmb_assy_line" id="cmb_assy_line" class="easyui-combobox" data-options="panelHeight:'150px'">
						<option selected="" value=""></option>
						<option value="LR01#1">LR01#1</option>
						<option value="LR03#1">LR03#1</option>
						<option value="LR03#2">LR03#2</option>
						<option value="LR03#2">LR03#3</option>
						<option value="LR06#1">LR06#1</option>
						<option value="LR06#2">LR06#2</option>
						<option value="LR06#3">LR06#3</option>
						<option value="LR06#4">LR06#4(T)</option>
						<option value="LR06#5">LR06#5</option>
						<option value="LR06#6">LR06#6</option>
						
					</select>
					<label><input type="checkbox" name="ck_assy_ln" id="ck_assy_ln" checked="true">All</input></label>
				</div>
			</div>
			<div style="width:460px; margin-left:340px;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Item No.</span>
					<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
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
			</div>
		</fieldset>
		<fieldset style="margin-left: 810px;border-radius:4px;height: 80px;"><legend><span class="style3"><strong>Raw Material Konversi Upload</strong></span></legend>
			<div class="fitem" align="right">
				<div style="width:180px;display:inline-block;"></div>
				<a href="upload_rm_konversi_excel.php" style="width:180px;display:inline-block;" class="easyui-linkbutton c2" iconCls="icon-excel"> Download Format konversi</a>	
			</div>
			<div class="fitem" align="right">
				<form id="upd" method="post" enctype="multipart/form-data">
					<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:200px;">
					<a href="javascript:void(0)" style="width:180px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit"  id ="upload" onclick="uploaddata()">
					<i class="fa fa-upload" aria-hidden="true"></i> Upload
					</a>
				</form>
			</div>
		</fieldset>
		<div style="padding:3px 3px;">
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="filterData()" style="width:150px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="del_item_show()" style="width:200px;"><i class="fa fa-trash" aria-hidden="true"></i> Delete Item Conversion</a>
		</div>
	</div>

	<div id="dlg-delete" class="easyui-dialog" style="width: 750px;height: 280px;" closed="true" buttons="#dlg-buttons-print-rec" data-options="modal:true">
		<table id="dg_del" class="easyui-datagrid" style="width:auto;height:500px;" url="upload_rm_konversi_get_delete.php" toolbar="#toolbar_del" fitcolumns="true" rownumbers="true"></table>
		<div id="toolbar_del">
			<div class="fitem">
				<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="del_item()" style="width:200px;"><i class="fa fa-trash" aria-hidden="true"></i> Delete Item</a>	
			</div>
		</div>
	</div>

	<script type="text/javascript">
		var url;

		function del_item_show(){
			$('#dlg-delete').dialog('open').dialog('setTitle','Delete Item Conversion');
			$('#dg_del').datagrid({
				singleSelect: false,
				checkOnSelect: true,
	    		selectOnCheck: true,
			    columns:[[
				    {field:'ck', checkbox:true, width:30, halign: 'center'},
				    {field:'ITEM_NO', halign:'center', align:'center', title:'ITEM NO',width:80},
				    {field:'TIPE', halign:'center', title:'DESCRIPTION',width:200},
				    {field:'MIN_DAYS', halign:'center', align:'right', title:'MIN',width:80},
				    {field:'AVERAGE', halign:'center', align:'right', title:'STD',width:80},
				    {field:'MAX_DAYS', halign:'center', align:'right', title:'MAX',width:80}
			    ]]
		    });
		    $('#dg_del').datagrid('enableFilter');
		}

		function del_item(){
			var delArr = [];
			$.messager.progress({
	            msg:'Removing data...'
	        });

	        var rows = $('#dg_del').datagrid('getSelections');
	        for(i=0;i<rows.length;i++){
				$('#dg').datagrid('endEdit',i);
				delArr.push(rows[i].ITEM_NO);
			}

			$.ajax({
			  	type: "POST",
			  	url: 'upload_rm_konversi_delete.php?item_no='+delArr,
			  	data: { kode:'kode' },
			  	success: function(data){
					if(data[0].kode == 'success'){
						$.messager.progress('close');
						$.messager.alert('INFORMATION','Data Saved','info');
						$('#dlg-delete').dialog('close');
						$('#dg').datagrid('reload');
					}else{
						$.messager.alert('WARNING',data[0].kode,'warning');
						$.messager.progress('close');
					}
				}
			});
		}

		function uploaddata() {
			$.messager.progress({
			    title:'Please waiting',
			    msg:'Upload data...'
			});
			document.getElementById('upload').disabled = true;
			$('#upd').form('submit',{
				url: 'upload_rm_konversi_process.php',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					$.messager.progress('close');
					$.messager.alert('ITEM NO',result,'info');
			 		$('#fileexcel').filebox('clear');
			 		$('#dg').datagrid('reload');
					document.getElementById('upload').disabled = false;
				}
			});
		};

		$(function(){
			$('#cmb_assy_line').combobox('disable');
			$('#ck_assy_ln').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_assy_line').combobox('disable');
				}else{
					$('#cmb_assy_line').combobox('enable');
				}
			});

			$('#cmb_cell_type').combobox('disable');
			$('#ck_cell_type').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_cell_type').combobox('disable');
				}else{
					$('#cmb_cell_type').combobox('enable');
				}
			});

			$('#cmb_item_no').combobox('disable');
			$('#ck_item_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_item_no').combobox('enable');
				};
			})

			$('#dg').datagrid({
			    columns:[[
				    {field:'ASSY_LINE', halign:'center', align:'center', title:'ASSY LINE',width:80},
				    {field:'CELL_TYPE', halign:'center', align:'center', title:'CELL TYPE',width:80},
				    {field:'ITEM_NO', halign:'center', align:'center', title:'ITEM NO',width:80},
				    {field:'DESCRIPTION', halign:'center', title:'DESCRIPTION',width:200},
				    {field:'KONVERSI', halign:'center', align:'right', title:'CONVERSION',width:80},
				    {field:'MIN_DAYS', halign:'center', align:'right', title:'MIN',width:80},
				    {field:'AVERAGE', halign:'center', align:'right', title:'STD',width:80},
				    {field:'MAX_DAYS', halign:'center', align:'right', title:'MAX',width:80}
			    ]]
		    });
		});

		function filterData(){
			var ck_assy_ln='false';
			var ck_cell_type='false';
			var ck_item_no='false';
			var flag = 0;

			if($('#ck_assy_ln').attr("checked")){ck_assy_ln='true'; flag += 1;}
			if($('#ck_cell_type').attr("checked")){ck_cell_type='true'; flag += 1;}
			if($('#ck_item_no').attr("checked")){ck_item_no='true'; flag += 1;}

			if(flag == 3) {
				$.messager.alert('INFORMATION','No filter data, system only show 200 records','info');
			}

			$('#dg').datagrid('load', {
				conf_aline: $('#cmb_assy_line').combobox('getValue'),
				conf_cline: ck_assy_ln,
				conf_cltyp: $('#cmb_cell_type').combobox('getValue'),
				conf_cktyp: ck_cell_type,
				conf_nitem: $('#cmb_item_no').combobox('getValue'),
				conf_citem: ck_item_no
			});

			$('#dg').datagrid({url:'upload_rm_konversi_get.php'});
		   	$('#dg').datagrid('enableFilter');
		};
	</script>
</body>
</html>