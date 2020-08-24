<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>
<!-- 
name : ueng h<IT>
date : 2017/04/19
description : assembly plan dibuat untuk upload plan assembly dari excel 
 -->

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ASSEMBLY PLAN</title>
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
<?php include ('../../ico_logout.php'); ?>

	<div id="toolbar" style="padding:3px 3px;">
		<fieldset style="float:left;width:850px;border-radius:4px;height: 80px;"><legend><span class="style3"><strong>Assembly Plan Filter</strong></span></legend>
			<div style="width:350px; float:left;">
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Period</span>
					<input style="width:135px;" name="cmb_bln" id="cmb_bln" class="easyui-combobox" data-options="url:'../json/json_month.json', method:'get', valueField:'id', textField:'month', panelHeight:'150px'"/>
					<input style="width:80px;" name="txt_tahun" id="txt_tahun" class="easyui-textbox" value="<?php echo date('Y');?>"/>
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:70px;display:inline-block;">Day</span>
					<select style="width:60px;" name="cmb_day" id="cmb_day" class="easyui-combobox" data-options="panelHeight:'150px'">
						<option selected="" value=""></option>
						<?php for ($i=1; $i<=31 ; $i++) { echo "<option value=".$i.">$i</option>";}?>
					</select>
					<label><input type="checkbox" name="ck_day" id="ck_day" checked="true">All</input></label>
					<span style="width:13px;display:inline-block;"></span>
					<span style="display:inline-block;">Revisi</span>
					<select style="width:60px;" name="cmb_rev" id="cmb_rev" class="easyui-combobox" style="width:142px;"  data-options="url:'../json/json_revisi_plan_assy.php', method:'get', valueField:'revisi', textField:'revisi', panelHeight:120"/></select>
					<label><input type="checkbox" name="ck_revisi" id="ck_revisi" checked="true">All</input></label>
				</div>
			</div>
			<div style="width:400px; margin-left: 450px;">
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Cell Type</span>
					<select style="width:120px;" name="cmb_cell_type" id="cmb_cell_type" class="easyui-combobox" data-options="url:'../json/json_cell_type.php', method:'get', valueField:'NAME', textField:'NAME', panelHeight:'auto'" /></select>
					<label><input type="checkbox" name="ck_cell_type" id="ck_cell_type" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Assembly Line</span>
					<select style="width:120px;" name="cmb_assy_line" id="cmb_assy_line" class="easyui-combobox" data-options="url:'../json/json_assy_line.php', method:'get', valueField:'NAME', textField:'NAME', panelHeight:'auto'" /></select>
					<label><input type="checkbox" name="ck_assy_ln" id="ck_assy_ln" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<fieldset style="margin-left: 860px;border-radius:4px;height: 80px;"><legend><span class="style3"><strong>Upload Plan</strong></span></legend>
			<form id="upd" method="post" enctype="multipart/form-data" novalidate >
				<div class="fitem" align="center">
					<div style="width:180px;display:inline-block;"></div>
					<a href="assy_plan_excel.php" style="width:180px;display:inline-block;" class="easyui-linkbutton c2"><i class="fa fa-download" aria-hidden="true"></i> 
						Download Format Plan
					</a>
				</div>
				<div class="fitem" align="center">
					<input class="easyui-filebox" name="fileexcel" id="fileexcel" style="width:180px;" data-options="prompt:'Choose a file...'">
					<a href="javascript:void(0)" id="btnUpload" class="easyui-linkbutton c2" onclick="uploaddata()" style="width:180px;">
						<i class="fa fa-upload" aria-hidden="true"></i> Upload Plan
					</a>
				</div>
			</form>
		</fieldset>
		<div style="padding:3px 3px;">
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="filterData()" style="width:150px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_assy()" style="width:200px;"><i class="fa fa-download" aria-hidden="true"></i> Download Assembly Plan</a>
		</div>
	</div>

	<table id="dg" title="ASSEMBLY PLAN" class="easyui-datagrid" style="width:100%;height:490px;" toolbar="#toolbar" fitcolumns="true" rownumbers="true"></table>

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

		var url;
		function uploaddata() {
		$('#upd').form('submit',{
			url: 'assy_plan_upload.php',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				$.messager.alert('ASSEMBLY PLAN',result,'info');
				//alert(result);
		 		$('#fileexcel').filebox('clear');
				$('#dg').datagrid('reload');
				}
			});
		}

		var pdf_url='';
		
		$(function(){
			$('#cmb_bln').combobox('disable');
			$('#txt_tahun').textbox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_bln').combobox('disable');
					$('#txt_tahun').textbox('disable');
				}else{
					$('#cmb_bln').combobox('enable');
					$('#txt_tahun').textbox('enable');
				}
			});

			$('#cmb_day').combobox('disable');
			$('#ck_day').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_day').combobox('disable');
				}else{
					$('#cmb_day').combobox('enable');
				}
			});			

			$('#cmb_assy_line').combobox('disable');
			$('#ck_assy_ln').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_assy_line').combobox('disable');
				}else{
					$('#cmb_assy_line').combobox('enable');
				}
			});

			$('#cmb_cell_type').combobox('disable');
			$('#ck_cell_type').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_cell_type').combobox('disable');
				}else{
					$('#cmb_cell_type').combobox('enable');
				}
			});

			$('#cmb_rev').combobox('disable');
			$('#ck_revisi').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_rev').combobox('disable');
				}else{
					$('#cmb_rev').combobox('enable');
				}
			});
			$('#dg').datagrid({
			    columns:[[
			    	{field:'ID_PLAN', halign:'center', title:'ID PLAN',width:80},
				    {field:'ASSY_LINE', halign:'center', align:'center', title:'ASSEMBLY<br/>LINE',width:80},
				    {field:'CELL_TYPE', halign:'center', align:'center', title:'CELL TYPE',width:80},
				    {field:'TANGGAL', halign:'center', align:'center', title:'DAY',width:40},
				    {field:'BULAN', halign:'center', align:'center', title:'MONTH',width:40},
				    {field:'TAHUN', halign:'center', align:'center', title:'YEAR',width:40},
				    {field:'QTY', halign:'center', align:'right', title:'QTY',width:50},
				    {field:'REVISI', halign:'center', align:'center', title:'REVISI',width:40}
			    ]]
		    });
			var dg = $('#dg').datagrid();
			dg.datagrid('enableFilter');
		});

		function filterData(){
			var ck_date='false';
			var ck_day='false';
			var ck_assy_ln='false';
			var ck_cell_type='false';
			var ck_revisi='false';
			//var ck_use='false';

			if($('#ck_date').attr("checked")){ck_date='true';}
			if($('#ck_day').attr("checked")){ck_day='true';}
			if($('#ck_assy_ln').attr("checked")){ck_assy_ln='true';}
			if($('#ck_cell_type').attr("checked")){ck_cell_type='true';}
			if($('#ck_revisi').attr("checked")){ck_revisi='true';}
			//if($('#ck_use').attr("checked")){ck_use='true';}
			
			$('#dg').datagrid('load', {
				pl_bulan: parseInt($('#cmb_bln').combobox('getValue')),
				pl_tahun: $('#txt_tahun').textbox('getValue'),
				pl_cdate: ck_date,
				pl_aline: $('#cmb_assy_line').combobox('getValue'),
				pl_cline: ck_assy_ln,
				pl_cltyp: $('#cmb_cell_type').combobox('getValue'),
				pl_cktyp: ck_cell_type,
				pl_revis: $('#cmb_rev').combobox('getValue'),
				pl_crev: ck_revisi,
				pl_day: $('#cmb_day').combobox('getValue'),
				pl_cday: ck_day
				//pl_cuse: ck_use
			});

			$('#dg').datagrid({url:'assy_plan_get.php'});
		   	$('#dg').datagrid('enableFilter');

		   	pdf_url = "?pl_bulan="+parseInt($('#cmb_bln').combobox('getValue'))+
		   			  "&pl_tahun="+$('#txt_tahun').textbox('getValue')+
		   			  "&pl_cdate="+ck_date+
					  "&pl_aline="+$('#cmb_assy_line').combobox('getValue')+
					  "&pl_cline="+ck_assy_ln+
					  "&pl_cltyp="+$('#cmb_cell_type').combobox('getValue')+
					  "&pl_cktyp="+ck_cell_type+
					  "&pl_revis="+$('#cmb_rev').combobox('getValue')+
					  "&pl_crev="+ck_revisi+
					  "&pl_day="+$('#cmb_day').combobox('getValue')+
					  "&pl_cday="+ck_day
					  //"&pl_cuse="+ck_use ;
			console.log('assy_plan_get.php'+pdf_url);
		}

		function print_assy(){
			if(pdf_url=='') {
				$.messager.show({
					title: 'Assembly Plan',
					msg: 'Data Not Defined'
				});
			} else {
				window.open('assy_plan_xls.php'+pdf_url, '_blank');
			}
		}
	</script>
</body>
</html>