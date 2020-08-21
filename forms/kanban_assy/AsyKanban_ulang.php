<?php
include("../connect/conn_kanbansys.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!-- 
ID = 1
Name : Reza Vebrian
Tanggal : 21 April 2017
Deskripsi : Membuat kanban Asy Line

D = 2
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
			<div style="width:300px; float:left;">
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Tanggal Produksi</span>
					<input style="width:100px;" name="date_prod" id="date_prod" class="easyui-datebox" 
						data-options="formatter:myformatter,parser:myparser" value="<?date();?>" required=""/>
				</div>
				<div class="fitem">
					<span style="width:130px;display:inline-block;">Assembling Line</span>
					<input style="width:100px;" name="cmb_Line" id="cmb_Line" class="easyui-combobox" data-options="url:'json/json_assy_line.php', method:'get', valueField:'NAME2', textField:'NAME', panelHeight:'160px'" required="" />
				</div>
				<div class="fitem" style="width: 650px;">
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;">Filter Data</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="PrintData()" style="width:200px;">Print ulang Kanban</a>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width:100px;display:inline-block;">Cell Type</span>
					<select style="width:120px;" name="cmb_cell_type" id="cmb_cell_type" class="easyui-combobox" data-options="url:'json/json_cell_type.php', method:'get', valueField:'NAME', textField:'NAME', panelHeight:'auto'"></select>
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

		var url='';
		function filterData(){
			$('#dg').datagrid('load', {
				date_prod: $('#date_prod').datebox('getValue'),
				cell_type: $('#cmb_cell_type').combobox('getValue'),
				Line: $('#cmb_Line').combobox('getValue')
			});

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

		   	url ="?date_prod="+$('#date_prod').datebox('getValue')+
				 "&cell_type="+$('#cmb_cell_type').combobox('getValue')+
				 "&Line="+$('#cmb_Line').combobox('getValue');

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