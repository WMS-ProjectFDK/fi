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
<title>SAFETY STOCK</title>
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
	<table id="dg" title="SAFETY STOCK" class="easyui-datagrid" style="width:100%;height:490px;" toolbar="#toolbar" fitcolumns="true" rownumbers="true"></table>

	<div id="toolbar">
		<fieldset style="float:left;width:850px;border-radius:4px;height: 80px;"><legend><span class="style3"><strong>Safety Stock Filter</strong></span></legend>
			<div style="width:350px; float:left;">
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Period</span>
					<input style="width:135px;" name="cmb_bln" id="cmb_bln" class="easyui-combobox" data-options="url:'../json/json_month.json', method:'get', valueField:'id', textField:'month', panelHeight:'150px'"/>
					<input style="width:80px;" name="txt_tahun" id="txt_tahun" class="easyui-textbox" value="<?php echo date('Y');?>"/>
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Status</span>
					<select style="width:135px;" name="cmb_sts" id="cmb_sts" class="easyui-combobox" data-options="panelHeight:'50px'">
     					<option value="0" selected="">ORDERED</option>
     					<option value="1">SAFETY STOCK</option>
					</select>
					<label><input type="checkbox" name="ck_sts" id="ck_sts" checked="true">All</input></label>
				</div>
			</div>
			<div style="width:460px; margin-left:390px;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Item No.</span>
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
					<span style="width:80px;display:inline-block;">Item Name</span>
					<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
				</div>
			</div>
		</fieldset>
		<fieldset style="margin-left: 860px;border-radius:4px;height: 80px;"><legend><span class="style3"><strong>Safety Stock Upload</strong></span></legend>
			<form id="upd" method="post" enctype="multipart/form-data" novalidate >
				<div class="fitem" align="center">
					<a href="javascript:void(0)" style="width:180px;display:inline-block;" class="easyui-linkbutton c2" onclick="view_master()"><i class="fa fa-eye" aria-hidden="true"></i> View Master Safety Stock</a>
					<a href="safety_stock_excel.php" style="width:180px;display:inline-block;" class="easyui-linkbutton c2"><i class="fa fa-table" aria-hidden="true"></i> Download Format Excel</a>
				</div>
				<div class="fitem" align="center">
					<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:175px;">
					<a href="javascript:void(0)" style="width:180px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit" onclick="uploaddata()">
						<i class="fa fa-upload" aria-hidden="true"></i> Upload
					</a>
				</div>
			</form>
		</fieldset>
		<div style="padding:3px 3px;">
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="filterData()" style="width:150px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="autorun()" style="width:200px;"><i class="fa fa-refresh" aria-hidden="true"></i> Auto Run Safety Stock</a>
		</div>

		<div id='toolbar_addItem'>
			<div class="fitem">
				<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="add_safety()" style="width:150px;"><i class="fa fa-plus" aria-hidden="true"></i> ADD ITEM</a>
				<div id="form_add" hidden="true">
					<div class="fitem"><br></div>
					<div class="fitem">
						<span style="width:80px;display:inline-block;">Item No.</span>
						<select style="width:330px;" name="cmb_item_no_add" id="cmb_item_no_add" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
						onSelect:function(rec){
							//alert(rec.id_name_item);
							var spl = rec.id_name_item;
							var sp = spl.split(' - ');
							$('#txt_item_name_add').textbox('setValue', sp[1]);
						}"></select>
					</div>
					<div class="fitem">
						<span style="width:80px;display:inline-block;">Item Name</span>
						<input style="width:330px;" name="txt_item_name_add" id="txt_item_name_add" class="easyui-textbox" disabled=""/>
					</div>
					<div style="padding:3px 3px;">
						<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="save_add()" style="width:150px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
						<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="cancel_add()" style="width:150px;"><i class="fa fa-save" aria-hidden="true"></i> Cancel</a>
					</div>
					<div class="fitem"><br></div>
				</div>
			</div>
		</div>

		<div id="dlg_master" class="easyui-dialog" style="width: 680px;height: 570px;" closed="true" buttons="#dlg-buttons-master" data-options="modal:true">
			<table id="dg_master" class="easyui-datagrid" toolbar="#toolbar_addItem" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true" singleSelect="true"></table>
		</div>
	</div>

	<script type="text/javascript">
		var url;

		function save_add(){
			var rows = [];
			rows.push({item_no: $('#cmb_item_no_add').combobox('getValue')})

			var myJSON = JSON.stringify(rows);
			var str_unescape=unescape(myJSON);
			console.log(str_unescape);
			$.post('safety_stock_save.php',{data: unescape(str_unescape)}).done(function(res){
				if(res == '"success"'){
					cancel_add()
					$('#dg_master').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!','info');
				}else{
					$.messager.alert('ERROR',res,'warning');
				}
			})
		}

		function cancel_add(){
			document.getElementById('form_add').hidden = true;
			$('#cmb_item_no_add').combobox('setValue','');
			$('#cmb_item_no_add').combobox('setText','');
			$('#txt_item_name_add').textbox('setValue', '');
			$('#txt_item_name_add').textbox('setText', '');
		}

		function add_safety(){
			document.getElementById('form_add').hidden = false;
		}

		function view_master(){
			$('#dlg_master').dialog('open').dialog('setTitle','Master Item Safety Stock');
			$('#dg_master').datagrid({
				url:'safety_stock_getMaster.php',
				fitColumns: true,
				columns:[[
	                {field:'item_no',title:'ITEM NO.',width:65,halign:'center', align:'center'},
	                {field:'item',title:'ITEM',width:100,halign:'center'},
	                {field:'description',title:'DESCRIPTION',width:250,halign:'center'},
	                {field:'unit',title:'UoM',width:45,halign:'center', align:'center'},
	                {field:'del_link',title:'ACTION',width:80,halign:'center', align:'center'}
	            ]]
			})
			$('#dg_master').datagrid('enableFilter');
		}

		function safety_delete(a){
			$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
				if(r){
					$.post('safety_stock_delete.php',{item_no: a},function(result){
						if (result.success){
                            $('#dg_master').datagrid('reload');
                            $.messager.progress('close');
                        }else{
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                            $.messager.progress('close');
                        }
					},'json');
				}
			})
		}

		function uploaddata() {
		$('#upd').form('submit',{
			url: 'safety_stock_upload.php',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				$.messager.alert('SAFETY STOCK',result,'info');
				//alert(result);
		 		$('#fileexcel').filebox('clear');
				$('#dg').datagrid('reload');
				}
			});
		}
		
		$(function(){
			$('#cmb_bln').combobox('disable');
			$('#txt_tahun').textbox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_bln').combobox('disable');
					$('#txt_tahun').textbox('disable');
				}else{
					$('#cmb_bln').combobox('enable');
					$('#txt_tahun').textbox('enable');
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

			$('#cmb_sts').combobox('disable');
			$('#ck_sts').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_sts').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_sts').combobox('enable');
				};
			})

			$('#dg').datagrid({
			    columns:[[
				    {field:'ITEM_NO', halign:'center', align:'center', title:'ITEM_NO',width:70},
				    {field:'ITEM', halign:'center', title:'ITEM',width:100},
				    {field:'DESCRIPTION', halign:'center', title:'DESCRIPTION',width:200},
				    {field:'PERIOD', halign:'center', align:'center', title:'MONTH',width:40},
				    {field:'YEAR', halign:'center', align:'center', title:'YEAR',width:40},
				    {field:'QTY', halign:'center', align:'right', title:'SAFETY QTY',width:70},
				    {field:'THIS_INVENTORY', halign:'center', align:'right', title:'INVENTORY',width:70},
				    {field:'STS_BUNDLE', halign:'center', title:'STATUS BUNDLE',width:100},
				    {field:'STS', halign:'center', title:'STATUS',width:70}

			    ]]
		    });
		});

		function filterData(){
			var ck_date='false';
			var ck_item_no='false';
			var ck_sts='false';
			var flag = 0;

			if($('#ck_date').attr("checked")){ck_date='true'; flag += 1;}
			if($('#ck_item_no').attr("checked")){ck_item_no='true'; flag += 1;}
			if($('#ck_sts').attr("checked")){ck_sts='true'; flag += 1;}

			if(flag == 3) {
				$.messager.alert('INFORMATION','No filter data, system only show 200 records','info');
			}

			$('#dg').datagrid('load', {
				ss_bulan: parseInt($('#cmb_bln').combobox('getValue')),
				ss_tahun: $('#txt_tahun').textbox('getValue'),
				ss_cdate: ck_date,
				ss_nitem: $('#cmb_item_no').combobox('getValue'),
				ss_citem: ck_item_no,
				ss_nstat: $('#cmb_sts').combobox('getValue'),
				ss_cstat: ck_sts
			});

			$('#dg').datagrid({url:'safety_stock_get.php'});
		   	$('#dg').datagrid('enableFilter');
		}

		function autorun(){
			alert('This process will takes 1-2 minutes please wait');
			$.post('safety_stock_autorun.php').done(function(res){
				if(res == '"success"'){
					$('#dg_master').datagrid('reload');
					$.messager.alert('INFORMATION','Auto Run Success..!!','info');
				}else{
					$.messager.alert('ERROR',res,'warning');
				}
			})
		}
	</script>
</body>
</html>