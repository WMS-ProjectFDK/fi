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
<title>ITEM PACK TYPE MASTER</title>
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
	<table id="dg" title="ITEM PACK TYPE MASTER" class="easyui-datagrid" style="width:100%;height:auto;"  url="item_pack_master_get.php" toolbar="#toolbar" fitcolumns="true" rownumbers="true"></table>
	<div id="toolbar">
		<div class="fitem">
			<form id="upd" method="post" enctype="multipart/form-data">
				<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:300px;padding: 5px 5px;">
				<a href="javascript:void(0)" style="width:200px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit"  id ="upload" onclick="uploaddata()">
					<i class="fa fa-upload" aria-hidden="true"></i> Upload
				</a>
				<a href="item_pack_master_excel.php" style="width:200px;display:inline-block;" class="easyui-linkbutton c2"><i class="fa fa-download" aria-hidden="true"></i> 
					Download Template Excel
				</a>
			</form>
			
		</div>
	</div>

	<script type="text/javascript">
		var url;
		var x = 0;

		function uploaddata() {
			x=1;
			// alert("Process around two minutes, please do not leave this page until the next message.");
			document.getElementById('upload').disabled = true;
			$.messager.progress({
			    title:'Please waiting',
			    msg:'Upload data...'
			});

			$('#upd').form('submit',{
				url: 'item_pack_master_process.php',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					console.log(result);
					$.messager.alert('ITEM NO',result,'info');
			 		$('#fileexcel').filebox('clear');
					document.getElementById('upload').disabled = false;
					x=0;
					$.messager.progress('close');
					$('#dg').datagrid('reload');
				}
			});
		};

		$(function(){
			$('#dg').datagrid({
			    columns:[[
				    {field:'ITEM_NO', halign:'center', align:'center', title:'ITEM NO',width:70},
				    {field:'DESCRIPTION', halign:'center', title:'DESCRIPTION',width:200},
				    {field:'OPERATION_TIME', halign:'center', title:'OT',width:70, align:'right'},
				    {field:'MAN_POWER', halign:'center', align:'center', title:'MAN POWER',width:70, align:'right'},
				    {field:'CAPACITY', halign:'center', align:'right', title:'CAPACITY',width:70},
				    {field:'PACKAGE_TYPE', halign:'center', title:'PACK TYPE',width:150},
				    {field:'GROUPS_PCK', halign:'center', title:'GROUP PACK',width:200},
				    {field:'SECOND_PROCESS', halign:'center', title:'PROCCESS (2ND)',width:100},
				    {field:'SECOND_MACHINE', halign:'center', title:'MACHINE (2ND)',width:100}
			    ]]
		    });

			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		});
	</script>
</body>
</html>