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
<title>ASSEMBLY PLAN</title>
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

	<div id="toolbar" style="padding:3px 3px;">
		<fieldset style="float:left;width:800px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>Assembly Plan Filter</strong></span></legend>
			<div style="width:430px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Period</span>
					<input style="width:135px;" name="cmb_bln" id="cmb_bln" class="easyui-combobox" data-options="url:'json/json_period.php', method:'get', valueField:'blnA', textField:'bln', panelHeight:'75px'" required="" />
					<input style="width:80px;" name="txt_tahun" id="txt_tahun" class="easyui-textbox" value="<?php echo date('Y');?>" requierd=""/>
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>

				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Assembly Line</span>
					<select style="width:220px;" name="cmb_assy_line" id="cmb_assy_line" class="easyui-combobox" data-options=" url:'json/json_supplier.php',method:'get',valueField:'kode_supplier',textField:'nama_supplier', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_assy_ln" id="ck_assy_ln" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="append()" style="width:100px;">Filter</a>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Cell Type</span>
					<select style="width:120px;" name="cmb_cell_type" id="cmb_cell_type" class="easyui-combobox" data-options=" url:'json/json_type_po.php',method:'get',valueField:'name_type',textField:'name_type', panelHeight:'auto'"></select>
					<label><input type="checkbox" name="ck_cell_type" id="ck_cell_type" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Revisi</span>
					<select style="width:120px;" name="cmb_rev" id="cmb_rev" class="easyui-combobox" style="width:142px;"  data-options=" url:'json/json_status.json'"></select>
					<label><input type="checkbox" name="ck_revisi" id="ck_revisi" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<!-- <fieldset style="margin-left: 825px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>Upload Plan</strong></span></legend>
			<form id="upd" method="post" enctype="multipart/form-data" novalidate>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" id="btnDownload" class="easyui-linkbutton c2" onclick="downloaddata()" style="width:350px;">Download Format</a>
				</div>
				<div class="fitem" align="center">
					<input class="easyui-filebox" id="btnFile" name="file1" id="file1" data-options="prompt:'Choose a file...'" style="width:350px; margin-top:20px;">	
				</div>
				<div class="fitem" align="center">
					<a href="javascript:void(0)" id="btnUpload" class="easyui-linkbutton c2" onclick="uploaddata()" style="width:350px;">Upload Plan</a>
				</div>
			</form>
		</fieldset> -->
	</div>

	<table id="dg" title="ASSEMBLY PLAN" class="easyui-datagrid" style="width:100%;height:490px;" toolbar="#toolbar" fitcolumns="true" rownumbers="true">
		<thead>
		 <th data-options="field:'attr1',width:250,editor:'text'">Attribute</th>
		</thead>>
	</table>

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
			url: 'safety_stock_upload.php',
			onSubmit: function(){
				return $(this).form('validate');
			},
			success: function(result){
				$.messager.alert('SAFETY STOCK',result,'info');
				//alert(result);
		 		$('#fileexcel').filebox('clear');
				$('#dg').datagrid('reload');
				}
			});
		}
		
 		var editIndex = undefined;
                    function endEditing(){
                        // if (editIndex == undefined){return true}
                        // if ($('#dg').datagrid('validateRow', editIndex)){
                        //     var ed = $('#dg').datagrid('getEditor', {index:editIndex,field:'productid'});
                        //     var productname = $(ed.target).combobox('getText');
                        //     $('#dg').datagrid('getRows')[editIndex]['productname'] = productname;
                        //     $('#dg').datagrid('endEdit', editIndex);
                        //     editIndex = undefined;
                        //     return true;
                        // } else {
                        //     return false;
                        // }
                    return true;    
                    }

 		function append(){
                        if (endEditing()){
                            $('#dg').datagrid('appendRow',{status:'P'});
                            editIndex = $('#dg').datagrid('getRows').length-1;
                            $('#dg').datagrid('selectRow', editIndex)
                            .datagrid('beginEdit', editIndex);
                        }
                    }

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
				    {field:'ITEM_NO', halign:'center', align:'center', title:'ASSEMBLY<br/>LINE',width:70},
				    {field:'ITEM', halign:'center', title:'CELL<br/>TYPE',width:100},
				    {field:'DESCRIPTION', halign:'center', title:'DAY',width:80},
				    {field:'PERIOD', halign:'center', align:'center', title:'MONTH',width:40},
				    {field:'YEAR', halign:'center', align:'center', title:'YEAR',width:40},
				    {field:'QTY', halign:'center', align:'right', title:'QTY',width:70},
				    {field:'QTY', halign:'center', align:'right', title:'REVISI',width:70}
			    ]]
		    });
		   
			var dg = $('#dg').datagrid();

			dg.datagrid('enableFilter');
		});
	</script>
</body>
</html>