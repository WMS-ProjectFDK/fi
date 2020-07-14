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
<title>BC UPLOAD RETURN-PGLOSAS</title>
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
	<table id="dg" title="BC UPLOAD RETURN RECEIVE TO PGLOSAS" class="easyui-datagrid" style="width:100%;height:auto;" toolbar="#toolbar" fitcolumns="true" rownumbers="true"></table>
	<div id="toolbar">
		<div class="fitem" style="padding: 3px 3px;display:inline-block;">
			<span style="display:inline-block;">SLIP DATE</span>
			<input style="width:100px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			TO 
			<input style="width:100px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
			<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
			<a href="bc_return_pglosas_excel.php" id="dowloadBtn" style="width:200px;display:inline-block;" class="easyui-linkbutton c2" disabled="true"><i class="fa fa-download" aria-hidden="true"></i> 
				DOWNLOAD TEMPLATE EXCEL
			</a>
			<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2"  onclick="UploadData();"><i class="fa fa-upload" aria-hidden="true"></i> UPLOAD PROCESS</a>
		</div>
	</div>
	
	<div id="dlg_upload" class="easyui-dialog" style="width:350px;height:auto;padding:5px 5px" closed="true" data-options="modal:true, position: 'center'">
		<form id="upd" method="post" enctype="multipart/form-data">
			<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:240px;padding: 5px 5px;">
			<a href="javascript:void(0)" style="width:auto;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit"  id ="upload" onclick="uploaddataNya()">
				<i class="fa fa-upload" aria-hidden="true"></i> UPLOAD
			</a>
		</form>
	</div>	

	<script type="text/javascript">
		var url;
		var x = 0;

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
			$('#dg').datagrid({
			    columns:[[
				    {field:'BC_DOC', halign:'center', align:'center', title:'BC DOC',width:70},
				    {field:'BC_NO', halign:'center', align:'center', title:'BC NO.',width:70},
				    {field:'SLIP_DATE', halign:'center', title:'SLIP DATE',width:70, align:'center'},
				    {field:'SLIP_NO', halign:'center', title:'SLIP NO.',width:70},
				    {field:'CUSTOMER', halign:'center', title:'SUPPLIER',width:200},
				    {field:'AMT', halign:'center', align:'center', title:'AMOUNT(O)',width:70, align:'right'}
			    ]]
		    });

			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		});

		function filterData(){
			$('#dg').datagrid('load', {
				date_awal: $('#date_awal').datebox('getValue'),
				date_akhir: $('#date_akhir').datebox('getValue')
			});

			$('#dg').datagrid({
				url: "bc_return_pglosas_get.php"	
			})
			$('#dowloadBtn').linkbutton('enable');
		}

		function UploadData() {
			$('#dlg_upload').dialog('open').dialog('setTitle','UPLOAD DATA');
		};

		function uploaddataNya(){
			x=1;
			document.getElementById('upload').disabled = true;
			$.messager.progress({
			    title:'Please waiting',
			    msg:'Upload data...'
			});

			$('#upd').form('submit',{
				url: 'bc_return_pglosas_upload.php',
				onSubmit: function(){
					return $(this).form('validate');
				},
				success: function(result){
					console.log(result);
					$.messager.alert('ITEM NO',result,'info');
			 		$('#fileexcel').filebox('clear');
					document.getElementById('upload').disabled = false;
					x=0;
					$('#dlg_upload').dialog('close');
					$.messager.progress('close');
					$('#dg').datagrid('reload');
				}
			});
		}
	</script>
</body>
</html>
