<?php
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!-- 
ID = 2
Name : ueng hernama
Tanggal : 27 April 2017
Deskripsi : required assy_line & json_AsyLine.php tanpa #

Tanggal : 05 may 2017
Deskripsi : tanggal produksi hanya 1(bukan date to date)
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

	<div id="toolbar" style="padding: 3px  3px;">
		<fieldset style="width:97%;border-radius:4px;height: auto;">
			<div style="width:500px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Tanggal Produksi</span>
					<input style="width:100px;" name="date_prod" id="date_prod" class="easyui-datebox" 
						data-options="formatter:myformatter,parser:myparser" value="<?date();?>" required=""/>
					<input style="width:100px;" name="date_prod_z" id="date_prod_z" class="easyui-datebox" 
						data-options="formatter:myformatter,parser:myparser" value="<?date();?>" required=""/>
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Assembling Line</span>
					<input style="width:100px;" name="cmb_Line" id="cmb_Line" class="easyui-combobox" data-options="url:'json/json_AsyLine.php', method:'get', valueField:'assy_line', textField:'assy_line_2', panelHeight:'160px'"/>
					<label><input type="checkbox" name="ck_assy_line" id="ck_assy_line" checked="true">All</input></label>
				</div>
				<div class="fitem" style="width: 650px;">
					<input style="width:130px; height: 20px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" placeholder="  ID Print" name="src" id="src" type="text"  autofocus="true"/>
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;">Filter Data</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="PrintData()" style="width:200px;">Print ulang Kanban</a>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Cell Type</span>
					<select style="width:120px;" name="cmb_cell_type" id="cmb_cell_type" class="easyui-combobox" >
					<option value=""></option>
     					<option>C01</option>
     					<option>C01NC</option>
     					<option>G06</option>
     					<option>G06NC</option>
     					<option>G07</option>
     					<option>G07NC</option>
     					<option>G08</option>
     					<option>G08NC</option>
   					</select>
   					<label><input type="checkbox" name="ck_cell_type" id="ck_cell_type" checked="true">All</input></label>
				</div>
				
			</div>
		</fieldset>
	</div>
	<table id="dg" title="Print Ulang Kanban Assembling" class="easyui-datagrid" style="width:95%;height:490px;" toolbar="#toolbar"></table>

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
			$('#date_prod').datebox('disable');
			$('#date_prod_z').datebox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_prod').datebox('disable');
					$('#date_prod_z').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_prod').datebox('enable');
					$('#date_prod_z').datebox('enable');
				};
			})

			$('#cmb_Line').combobox('disable');
			$('#ck_assy_line').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_Line').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_Line').combobox('enable');
				};
			})	

			$('#cmb_cell_type').combobox('disable');
			$('#ck_cell_type').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_cell_type').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_cell_type').combobox('enable');
				};
			})					

			$('#dg').datagrid({
				url:'AsyKanban_ulang_get.php',
		    	singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
			    columns:[[
				    {field:'ID',title:'ID',width:60, halign: 'center'},
				    {field:'ID_PLAN',title:'ID PLAN',width:100, halign: 'center'},
				    {field:'ASYLINE',title:'ASSY LINE',width:85, halign: 'center'},
				    {field:'CELL_TYPE',title:'CELL TYPE',width:85, halign: 'center', align: 'center'},
				    {field:'DATE_PROD',title:'TANGGAL PRODUKSI',width:85, halign: 'center'},
				    {field:'PALLET',title:'PALLET',width:60, halign: 'center', align:'right'},
				    {field:'QTY',title:'QTY',width:60, halign: 'center', align: 'center'},
				    {field:'BOX',title:'JUMLAH BOX',width:60, halign: 'center', align: 'center'},
				    {field:'UPTO_DATE',title:'TANGGAL PRINT',width:100, halign: 'center'}
			    ]]
			});
		});

		function filter(event){
			var src = document.getElementById('src').value;
			var search = src.toUpperCase();
			document.getElementById('src').value = search;
			
		    if(event.keyCode == 13 || event.which == 13){
				var src = document.getElementById('src').value;
				//alert(src);
				$('#dg').datagrid('load', {
					src: search
				});

				$('#dg').datagrid('enableFilter');

				if (src=='') {
					filterData();
				};
		    }
		}

		function filterData(){
			var ck_date = 'false';
			var ck_assy_line = 'false';
			var ck_cell_type = 'false';
			var flag = 0;

			if($('#ck_date').attr("checked")){
				ck_date='true';
				flag += 1;
			}

			if($('#ck_assy_line').attr("checked")){
				ck_assy_line='true';
				flag += 1;
			}

			if($('#ck_cell_type').attr("checked")){
				ck_cell_type='true';
				flag += 1;
			}

			if(flag == 3){
				$.messager.alert("INFORMATION","No filter data, system only show 150 records","info");
			}

			$('#dg').datagrid('load', {
				date_prod: $('#date_prod').datebox('getValue'),
				date_prod_z: $('#date_prod_z').datebox('getValue'),
				ck_date: ck_date,
				cell_type: $('#cmb_cell_type').combobox('getValue'),
				ck_cell_type: ck_cell_type,
				Line: $('#cmb_Line').combobox('getValue'),
				ck_assy_line: ck_assy_line,
				src: ''
			});

			console.log('?date_prod='+ $('#date_prod').datebox('getValue')+'&date_prod_z='+$('#date_prod_z').datebox('getValue')+'&ck_date='+ck_date+'&cell_type='+$('#cmb_cell_type').combobox('getValue')+'&ck_cell_type='+ck_cell_type+'&Line='+$('#cmb_Line').combobox('getValue')+'&ck_assy_line='+ck_assy_line+'&src=');

		   	$('#dg').datagrid('enableFilter');
		}

		function PrintData(){
			var row = $('#dg').datagrid('getSelected');
			if(row){
				window.open('assy_print.php?kodeid='+row.ID);	
			}else{
				$.messager.alert('Warning','Data not selected','warning');
			}
		}
	</script>
</body>
</html>