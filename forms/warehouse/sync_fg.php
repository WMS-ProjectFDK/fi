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
    <title>Syncronized</title>
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script> 
     <link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../../themes/color.css">
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
	<?php 
	include ('../ico_logout.php'); 
	//include ('sync_get2.php'); 
	?>
	
	<div id="toolbar" style="margin-top: 5px;margin-bottom: 5px;width: 100%;">
		<div align="center" class="fitem" style="width: 100%;">
			<a href="#" style="width:150px;height:60px;" class="easyui-linkbutton c2" onclick="select_device()" disabled="true"><i class="fa fa-tablet"></i><br/>SELECT DEVICE</a>
			<a href="#" style="width:150px;height:60px;" class="easyui-linkbutton c2" onclick="sync()"><i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>SYNCRONIZED</a>
		</div>
		<br/>
		<div id="progress" class="easyui-progressbar" style="margin-left: 20px; width: 96%;"></div><br/>
	</div>
	<table id="dg" title="SYNCRONIZED DATA FINISH GOODS" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;"></table>

	<div id="dlg" class="easyui-dialog" style="width:500px;" closed="true" data-options="position:'bottom', modal:true" buttons="#dlg-buttons">
		<div class="fitem" style="margin:2px; padding:15px 15px;">
			<span style="width:100px;display:inline-block;">Barcode Device</span>
			<select id="brc" class="easyui-combobox" name="brc" style="width:200px;" data-options="panelHeight:'50px'" required="">
			    <option></option>
			    <option value="172.23.225.214">Device I (214)</option>
			</select>
		</div>
	</div>
	<div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="proses_device()" style="width:90px">Process</a>
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

		function select_device(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','select Barcode Device');
        }

		$('#dg').datagrid({
		    url:'sync_fg_get.php',
		   	singleSelect: true,
			rownumbers: true,
			fitColumns:true,
		    columns:[[
		    	{field:'SLIP_NO',title:'SLIP NO',halign:'center', align:'center', width:30},
		    	{field:'SLIP_DATE',title:'SLIP DATE',halign:'center', align:'center', width:40},
		    	{field:'SLIP_NAME',title:'SLIP NAME',halign:'center', width:50},
		    	{field:'ITEM_NO',title:'ITEM NO',halign:'center', align:'center', width:30},
		    	{field:'ITEM',title:'ITEM',halign:'center', width:50},
		    	{field:'DESCRIPTION',title:'DESCRIPTION',halign:'center', width:150},
		    	{field:'SLIP_QUANTITY',title:'QTY',halign:'center', align:'right', width:50},
		    	{field:'UNIT',title:'UoM',halign:'center',align:'center', width:15}
		    ]]
	    });

		function proses_device(){
			var cmb_D = $('#brc').combobox('getValue');
			$.post("sync_fg_get2.php", {
				device: cmb_D
			}).done(function(res){
				//alert(res);
			});
			$('#dlg').dialog('close');
			$('#dg').datagrid('reload');
		}

		function sync(){
			rows = $('#dg').datagrid('getRows');
			$('#dg').datagrid('endEdit');
			$.post("sync_fg_save.php").done(function(res){
				//console.log(res);
			});
			$.messager.alert('SYNCRONIZED','Data Saved!','info');
			$('#dg').datagrid('reload');
		}
	</script>
    </body>
    </html>