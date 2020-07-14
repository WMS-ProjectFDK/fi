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
    <title>RACK ADDRESS</title>
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script> 
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../themes/icon.css">
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../js/datagrid-filter.js"></script>
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
	
	<div id="toolbar">
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:98%;"><legend>Filter Rack</legend>
	<div style="width:500px; float:left;">
		<div class="fitem">
			<span style="width:100px;display:inline-block;">Warehouse</span>
			<input style="width:150px;" name="cmb_wh" id="cmb_wh" class="easyui-combobox" data-options=" url:'json/json_wh.php',method:'get',valueField:'wrh',textField:'wrh', panelHeight:'auto', onSelect:function(rec){
				
			}"/>
			<input type="checkbox" name="ck_wh" id="ck_wh" checked="true">All</input>
		</div>
		<div class="fitem">
			<span style="width:100px;display:inline-block;">Rack</span>
			<input style="width:150px;" name="cmb_rack" id="cmb_rack" class="easyui-combobox"/>
			<input type="checkbox" name="ck_rack" id="ck_rack" checked="true">All</input>
		</div>
	</div>
	</fieldset>
	<div style="margin-top: 3px;margin-bottom: 3px;margin-left: 3px;">
		<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton" iconCls='icon-filter' onClick="filterData()" style="width:100px;">FILTER</a>
		<a href="javascript:void(0)" id="printpdf" class="easyui-linkbutton" iconCls='icon-print' onClick="printPDF()" style="width:100px;">Print</a>
	</div>
	</div>

	<table id="dg" title="RACK ADDRESS" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:500px;"></table>

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
			$('#cmb_wh').combobox('disable');
			$('#ck_wh').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_wh').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					//alert('HOREEEEEE!!!');
					$('#cmb_wh').combobox('enable');					
				};
			})

			$('#cmb_rack').combobox('disable');
			$('#ck_rack').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_rack').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					//alert('HOREEEEEE!!!');
					$('#cmb_rack').combobox('enable');					
				};
			})

			$('#dg').datagrid({
			    url:'rack_get.php',
			   	singleSelect: true,
				rownumbers: true,
			    columns:[[
			    	{field:'ID_RACK',title:'RACK',halign:'center', width:150},
				    {field:'WAREHOUSE',title:'WAREHOUSE',width:300, halign:'center', sortable: true},
				    {field:'ADDRESS',title:'ADDRESS', halign:'center', width:300},
				    {field:'LEVEL_RACK',title:'LEVEL',halign:'center', width:300},
				    {field:'STACK',title:'STACK', halign:'center', width:100},
				    {field:'KUOTA_PALLET',title:'KUOTA',halign:'center', width:100}
			    ]]
		    });

		    var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		})
	</script>
    </body>
    </html>