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
    <title>MRP</title>
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
		<div align="left" class="fitem" style="width: 100%;">
			<span style="width:200px;display:inline-block;">MPS Upload Date :</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_download_list" class="easyui-combobox" data-options=" url:'../json/json_get_upload_mps.php', method:'get', valueField:'LASTED', textField:'LASTED', panelHeight:'100px'"></select>
			<label>
				<input type="checkbox" name="ck_item_no" id="ck_upload" checked="true">Current</input>
				<span id ="LastUpload"></span>
			</label>
		</div>
		<div align="left" class="fitem" style="width: 100%;">
			<span style="width:200px;display:inline-block;">MPS Compare Date :</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_upload_list" class="easyui-combobox" data-options=" url:'../json/json_get_upload_mps.php', method:'get', valueField:'LASTED', textField:'LASTED', panelHeight:'100px'"></select>
			<label>
				<input type="checkbox" name="ck_item_no" id="ck_last" checked="true">Last Upload</input>
				<span id = "LastestCompare"></span>
			</label>
		</div>
		<div align="left" class="fitem" style="width: 100%;">
			<span style="width:200px;display:inline-block;"></span>
			<a href="javascript:void(0)" id="download" style="width:250px;" class="easyui-linkbutton c2" onclick="downloadmps()" ><i class="fa fa-download"></i> Download</a>
		</div>
	</div>
	<table id="dg" title="DOWNLOAD MPS" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;"></table>
	
    <script type="text/javascript">
   		var DATECUR;
   		var DATEOLD;
   		
		$(function(){		
			$('#cmb_download_list').combobox('disable');
			$('#ck_upload').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_download_list').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_download_list').combobox('enable');
				};
			})

			$('#cmb_upload_list').combobox('disable');
			$('#ck_last').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_upload_list').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_upload_list').combobox('enable');
				};
			})

			var data;
			$.ajax({
			type: 'GET',
			dataType: "json",
			url: '../json/json_get_last_upload_mps.php',
			data: data,
			success: function (data) {
				document.getElementById("LastUpload").innerHTML = '[' + data[0].LISTED+ ']';
				document.getElementById("LastestCompare").innerHTML = '[' + data[0].LASTED+ ']';
				DATECUR = data[0].LISTED;
				DATEOLD = data[0].LASTED;
				}
			});
		});

		function downloadmps(){
			$.post('mps_download_process.php',{
				// data: unescape(str_unescape2),
				// bookingHeader: b,
				// containerHeader: c,
				// asinHeader: $('#dg').datagrid('getData').rows[1].ASIN.trim()
			}).done(function(res){
				console.log(res);
				if (res == '"success"'){
					$.messager.confirm('Confirm','Do you want to download file to excel?',function(r){
						if(r){
							download_excel();
						}
					})
				}else{
					$.messager.show({
						title: 'Error',
						msg: res
					});
				}

				// $.messager.progress('close');
				// $.messager.progress({
	            //     title:'Please waiting',
	            //     msg:'Saving carton data...'
	            // });
				// $.messager.progress('close');

				
			});
		}	
			
		function download_excel(){
			var dcur;
			var dold;
			var flag = 0;
			
			if($('#ck_last').attr("checked")){
				dold = DATEOLD;
			}else{
				dold= $('#cmb_upload_list').combobox('getValue');
				flag = 1;
			}

			if($('#ck_upload').attr("checked")){
				dcur = DATECUR;
				
			}else {
				dcur= $('#cmb_download_list').combobox('getValue');
				flag = 2;
			}

			dold = dold.split("-").join("");
			dcur = dcur.split("-").join("");

			if (flag==2){
				// url_download = "http://172.23.20f6.21/pglosas/entry/mps/mps_download2y.asp?KEYWORD=FI0111&DATE_OLD="+dold+"&DATE_CUR="+dcur+"";
				url_download = 'mps_download_xls_y.php?DATE_OLD='+dold+'&DATE_CUR='+dcur;
			}else if (flag==1){
				// url_download = "http://172.23.206.21/pglosas/entry/mps/mps_download2x.asp?KEYWORD=FI0111&DATE_OLD="+dold+"&DATE_CUR="+dcur+"";
				url_download = 'mps_download_xls_x.php?DATE_OLD='+dold+'&DATE_CUR='+dcur;
			}else{
				// url_download = "http://172.23.206.21/pglosas/entry/mps/mps_download2.asp?KEYWORD=FI0111&DATE_OLD="+dold+"&DATE_CUR="+dcur+"";
				url_download = 'mps_download_xls.php?DATE_OLD='+dold+'&DATE_CUR='+dcur;
			}
			
			// alert(url_download);
			
			if (dold != ""  &  dcur != "") {				
				window.open(url_download);
			}else{
				if (flag = 0){
					window.open(url_download);
				}else{
					alert("Please fill upload or compare date.")
				}
			}
		}
	</script>
	</body>
</html>