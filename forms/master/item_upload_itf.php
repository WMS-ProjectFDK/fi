<?php
include("../../connect/conn.php");
session_start();
//require_once('___loginvalidation.php');
//$user_name = $_SESSION['id_wms'];
$user_name = htmlspecialchars($_SESSION['id_wms']);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ITEM UPLOAD ITF CODE</title>
<link rel="icon" type="image/png" href="../favicon.png">
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
<?php //include ('../ico_logout.php'); 

	// $newformat = "201812";
	// $newformat .= "01";
	// echo date('Y-m-d', strtotime($newformat));
?>
	<table id="dg" title="ITEM UPLOAD ITF CODE" class="easyui-datagrid" style="width:100%;height:auto;"  url="itf_code_list.php" toolbar="#toolbar" fitcolumns="true" rownumbers="true"></table>

	<div id="toolbar">
		<div class="fitem">
			
			<form id="upd" method="post" enctype="multipart/form-data">
				<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:500px;">
				<a href="javascript:void(0)" style="width:200px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit"  id ="upload" onclick="uploaddata()">
					<i class="fa fa-upload" aria-hidden="true"></i> Upload
				</a>
			</form>
		</div>
	</div>

	<script type="text/javascript">
		var url;
		var x = 0;
		function uploaddata() {
		x=1;
		alert("Process around two minutes, please do not leave this page until the next message.");
		document.getElementById('upload').disabled = true;
		$('#upd').form('submit',{
			url: 'item_upload_itf_process.php',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				$.messager.alert('ITEM NO',result,'info');
		 		$('#fileexcel').filebox('clear');
				document.getElementById('upload').disabled = false;
				x=0;
				$('#dg').datagrid('reload');
				}
			});
		};

		$(function(){
			$('#dg').datagrid({
			    columns:[[
				    {field:'item_no', halign:'center', align:'center', title:'ITEM NO',width:70},
				    {field:'shrink', halign:'center', title:'SHRINK',width:70},
				    {field:'blister', halign:'center', title:'BLISTER',width:70},
				    {field:'inner', halign:'center', align:'center', title:'INNER',width:70},
				    {field:'medium', halign:'center', align:'center', title:'MEDIUM',width:70},
				    {field:'outer', halign:'center', align:'right', title:'OUTER',width:70},
				    {field:'berat_inner', halign:'center', title:'BERAT INNER',width:50},
				    {field:'toleransi_plus', halign:'center', title:'TOLERANSI PLUS',width:50},
				    {field:'toleransi_minus', halign:'center', align:'center', title:'TOLERANSI MINUS',width:40},
				    {field:'isi_inner', halign:'center', align:'center', title:'ISI INNER',width:40},
			    ]]
		    });
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		});

		function filter(){
			$('#dg').datagrid('reload');
		};
	</script>
</body>
</html>