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
    <title>FORECAST</title>
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script> 
    <link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../themes/color.css">
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
	<?php 
	include ('../ico_logout.php'); 
	//include ('sync_get2.php'); 
	?>
	
	<div id="toolbar" style="margin-top: 5px;margin-bottom: 5px;width: 100%;">
		<div align="center" class="fitem" style="width: 100%;">
			<a href="forecast_excel.php" style="width:250px;height:80px;" class="easyui-linkbutton c2" onclick="download_fc()">
				<i class="fa fa-arrow-circle-down fa-3x" aria-hidden="true"></i>
				<br/>DOWNLOAD SALES FORECAST 
			</a>
		</div>
	</div>
	<table id="dg" title="SALES FORECAST " class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;"></table>

	<div id="dlg" class="easyui-dialog" style="background-opacity:0.6; width:460px;padding:10px 20px" 
		data-options="modal:true, collapsible:false, minimizable:false,maximizable:false,closable:false,closed:true">
		<div id="p" class="easyui-progressbar" data-options="value:0" style="width:400px;"></div>
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

		function download_fc(){
			$.messager.alert('INFORMATION','Please Wait a few minutes','info');
			/*$('#dlg').dialog('open').dialog('center').dialog('setTitle','PLEASE WAIT...');
			$('#p').progressbar({value: 0});
			setInterval(function(){
				var value = $('#p').progressbar('getValue');
				if (value < 100){
				    value += Math.floor(Math.random() * 25);
				    $('#p').progressbar('setValue', value);
				    filterData();
				}
				if (value >=100) {
					$('#dlg').dialog('close');
				};
			},800);*/
		}
	</script>
    </body>
    </html>