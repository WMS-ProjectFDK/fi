<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Packaging Plan</title>
    <link rel="icon" type="image/png" href="../favicon.png">
	<script language="javascript">
			function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
			}
	</script> 
	<!-- /<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css"> -->
	<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
	<link rel="stylesheet" type="text/css" href="../themes/icon.css" />
	<link rel="stylesheet" type="text/css" href="../css/style.css">
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
	<?php include ('../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name)); ?>
	
	<table id="dg" title="PACKAGING PLAN" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:auto;"></table>
	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:97%; float:left; margin:5px;"><legend>FILTER PACKAGING PLAN</legend>
			<div style="width:100%; height: 60px; float:left;">	
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Period</span>
					<input style="width:120px;" name="cmb_period" id="cmb_period" class="easyui-combobox" data-options="url:'json/json_period_plan.php', method:'get', valueField:'bln', textField:'blnA', panelHeight:'auto'" required=""/>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">Grouping Pack</span>
					<select style="width:300px;" name="cmb_group_pack" id="cmb_group_pack" class="easyui-combobox" 
						data-options="url:'json/json_packing_type.php',
									  method:'get',
									  valueField:'TYPE2',
									  textField:'TYPE1',
									  multiple:true,
									  panelHeight:'150px',
									  label: 'Language:',
                    				  labelPosition: 'top'"></select>
					<label><input type="checkbox" name="ck_group_pack" id="ck_group_pack" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
		<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:97%; float:left; margin:5px;">
			<div align="center">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="download_pack_plan()" style="width:100px">DOWNLOAD</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="clear_pack_plan()" style="width:100px">CANCEL</a>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:10px;"></div>
	</div>
	
	<script type="text/javascript">
		var getUrl = '';

		$(function(){
			$('#cmb_group_pack').combobox('disable');
			$('#ck_group_pack').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_group_pack').combobox('disable');
				}else{
					$('#cmb_group_pack').combobox('enable');
				}
			});
		});

		function validate(){
			var hasil=0;
			var msg='';
			return hasil;
		}

		function download_pack_plan(){
			var ck_group = "false";
			var cp = $('#cmb_period').combobox('getText');

			if ($('#ck_group_pack').attr("checked")) {
				getUrl = '?KEYWORD='+"<?=$user_name?>"+
						 '&YM='+$('#cmb_period').combobox('getValue')+
						 '&YMF='+$('#cmb_period').combobox('getText');
			}else{
				var nArr = [];
				var gp = $('#cmb_group_pack').combobox('getText');

				getUrl = '?KEYWORD='+"<?=$user_name?>"+
						 '&YM='+$('#cmb_period').combobox('getValue')+
						 '&YMF='+$('#cmb_period').combobox('getText')+
						 '&IP='+gp;
			}

			 window.open('http://sysfi01.indonesia.fdk.co.jp/pglosas/entry/pac_plan/pacp_download2.asp'+getUrl);			
			 //alert('http://sysfi01.indonesia.fdk.co.jp/pglosas/entry/pac_plan/pacp_download2.asp'+getUrl);
			 
			 //http://sysfi01.indonesia.fdk.co.jp/pglosas/entry/pac_plan/pacp_download2.asp?KEYWORD=FI0111&YM=201902&YF=FEB/2019&IP=MANUAL%20SHRINK
		}
	</script>
	</body>
    </html>