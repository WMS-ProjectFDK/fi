<?php 
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>MENU</title>
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
		<table id="dg" title="MASTER MENU" class="easyui-datagrid" style="width:100%;height:490px;"
				url="menu_get.php" fitColumns="true"
				toolbar="#toolbar" 
				rownumbers="true" singleSelect="true"
				sortName="kode_menu" sortOrder="asc">
		</table>
		<div id="toolbar">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMenu()">Add</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMenu()">Edit</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyMenu()">Delete</a>
		</div>
		
		<div id="dlg" class="easyui-dialog" style="width:580px;height:210px;padding:10px 20px" closed="true" buttons="#dlg-buttons" data-options="modal:true">
			<form id="fm" method="post" novalidate>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Menu Parent</span>
					<select id="menu_parent" name="menu_parent" style="width:433px" class="easyui-combobox" data-options="
					url: '../json/json_menu.php',
					method: 'get',
					textField: 'nama_menu',
					valueField: 'kode_menu',
					panelHeight: '80px'"
					required="true"></select>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Menu ID</span>
					<input name="kode_menu" id="kode_menu" class="easyui-textbox" style="width:80px;" required="true">
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:100px;display:inline-block;">Menu Name</span>
					<input id="nama_menu" name="nama_menu" class="easyui-textbox" style="width:220px" required="true">
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Sub Menu ID</span>
					<input name="kode_submenu" id="kode_submenu" class="easyui-textbox" style="width:80px;">
					<span style="width:20px;display:inline-block;"></span>
					<span style="width:100px;display:inline-block;">Sub Menu Name</span>
					<input id="nama_submenu" name="nama_submenu" class="easyui-textbox" style="width:220px">
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Menu Link</span>
					<input name="link" id="link" class="easyui-textbox" style="width:433px;" required="true">
				</div>
			</form>
		</div>
		<div id="dlg-buttons">
			<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveMenu()" style="width:90px">Save</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
		</div>
		<script type="text/javascript">
		
		var url;
		function newMenu(){
			//alert();
			$('#dlg').dialog('open').dialog('setTitle','Entry Menu');
			$('#fm').form('clear');
			$('#kode_menu').textbox('enable');
			$('#menu_parent').combobox('enable');
			url = 'menu_save.php';
		}

		function editMenu(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$('#dlg').dialog('open').dialog('setTitle','Edit');
				$('#fm').form('load',row);
				$('#kode_menu').textbox('enable');
				$('#kode_menu').textbox('setValue',row.id_menu);
				$('#menu_parent').combobox('enable');
				$('#menu_parent').combobox('setValue',row.id_parent+'-'+row.menu_parent);
				$('#nama_menu').textbox('setValue',$.trim(row.menu_name));
				$('#kode_submenu').textbox('setValue',row.id_sub_parent);
				$('#nama_submenu').textbox('setValue',row.menu_sub_parent_real);
				$('#link').textbox('setValue',$.trim(row.link));
				url = 'menu_edit.php?menu_id='+row.id;
			}
		}

		function destroyMenu(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove this Menu ?',function(r){
					if (r){
						$.post('menu_destroy.php',{kode:row.id},function(result){
							if (result.success){
								$('#dg').datagrid('reload');	// reload data
							} else {
								$.messager.alert('Equivalent','Error','warning');

							}
						},'json');
					}
				});
			}
		}
		
		function saveMenu(){
			// console.log(url);
			$('#fm').form('submit',{
				url: url,
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					// console.log(result);
					var result = eval('('+result+')');
					
					if (result.errorMsg){
						$.messager.show({
							title: 'Error',
							msg: result.errorMsg
						});
						// console.log(result.errorMsg);
					} else {
						$('#dlg').dialog('close');		// close the dialog
						$('#dg').datagrid('reload');	// reload data
						$.messager.alert('Menu','Success','info');
						$('#menu_parent').combobox('reload');
					}
				}
			});
		}

		$('#dg').datagrid({
			columns: [[
					{field: 'id', title: 'ID', width: 40, halign: 'center', align: 'center'},
					{field: 'id_parent', hidden: true},
					{field: 'id_sub_parent', hidden: true},
					{field: 'id_menu', title: 'MENU ID', width: 50, halign: 'center', align: 'center', sortable: true},
					{field: 'menu_parent', title: 'MENU PARENT', width: 200, halign: 'center', sortable: true},
					{field: 'menu_sub_parent', title: 'SUB MENU PARENT<br>[ID SUB MENU PARENT]', width: 200, halign: 'center', sortable: true},
					{field: 'menu_sub_parent_real', hidden: true},
					{field: 'menu_name', title: 'MENU NAME', width: 200, halign: 'center', sortable: true},
					{field: 'link', title: 'MENU LINK', width: 300, halign: 'center', sortable: true},
				]]
		});
			
		$(function(){
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		});

		</script>
    </body>
    </html>