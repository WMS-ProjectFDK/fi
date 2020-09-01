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
    <title>Inventory Slide</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
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
	include ('../../ico_logout.php'); 
	//include ('sync_get2.php'); 
	?>
	<div id="toolbar" class="fitem"  style="width:auto;padding:5px 5px;">
        <form id="upd" method="post" enctype="multipart/form-data" novalidate>
            <div class="fitem" align="center">
                <a href="javascript:void(0)" style="width:180px;height:80px;display:inline-block;margin-left: 5px;" class="easyui-linkbutton c2" type="submit" onclick="uploadmps()">
                    <i class="fa fa-upload" aria-hidden="true"></i> Inventory Slide
                </a>
            </div>
        </form>
        <br/>
        <hr>
        <br/>
    </div>
	<table id="dg" title="INVENTORY SLIDE" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;"></table>
	
    <script type="text/javascript">
		$(function(){
			var data;
			$.ajax({
			type: 'GET',
			dataType: "json",
			url: '../json/json_get_last_upload_mps.php',
			data: data,
			success: function (data) {
				document.getElementById("LastUpload").innerHTML = '[' + data[0].LISTED+ ']';
				var DATECUR = data[0].LISTED;
				varDATEOLD = data[0].LASTED;
				}
			});
		});

		function uploadmps(){
			$('#upd').form('submit',{
			url: 'inventory_slide_process.php',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				$.messager.alert('INVENTORY SLIDE',result,'info');
				console.log(result);
				}
			});
		}
	</script>
	</body>
</html>