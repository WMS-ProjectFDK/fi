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
<title>ANODE GEL REPORT</title>
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
	<fieldset style="float:left;width:700px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong> FILTER DATA</strong></span></legend>
		<div style="width:600px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">Date Production</span>
				<input style="width:100;" name="date_prod" id="date_prod" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
			</div>
			<div class="fitem">
				<div id="ket_tgl" style="font-size: 10px; color: red;"></div>	
			</div>
			<div class="fitem">
				<a href="javascript:void(0)" style="width: 110px;" class="easyui-linkbutton c2"  onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
			</div>
		</div>
	</fieldset>
	<fieldset style="margin-left: 725px;border-radius:4px;height: 100px;width:auto;"><legend><span class="style3"><strong>PRINT DATA</strong></span></legend>
		<div class="fitem" align="center" style="margin-top: 13px;">
			<a href="javascript:void(0)" id="printpdf" style="width: 250px;" class="easyui-linkbutton c2" disabled="true" onclick="print_pdf()"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Download To PDF</a>
		</div>
		<div class="fitem" align="center" style="margin-top: -3px;">
			<a href="javascript:void(0)" id="printxls" style="width: 250px;" class="easyui-linkbutton c2" disabled="true" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Download To Excel</a>
		</div>
		</div>
	</fieldset>
</div>
<table id="dg" title="ANODE GEL REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;" data-options="rownumbers: true, singleSelect: true, iconCls: 'icon-save', method: 'get', showFooter: true, fitColumns: true">
    <thead>
    <tr>
    	<th rowspan="2" data-options="field:'DATE_PROD',halign: 'center', width:70">PRODUCT<br/>DATE</th>
        <th rowspan="2" data-options="field:'TYPE_GEL',halign: 'center', width:60">TYPE<br/>GEL</th>
        <th rowspan="2" data-options="field:'KANBAN_NO',halign: 'center', width:55, align: 'center'">KANBAN<br/>NO.</th>
        <th rowspan="2" data-options="field:'NO_TAG',halign: 'center', width:50, align: 'center'">TAG<br/>NO.</th>
        <th rowspan="2" data-options="field:'TYPE_ZN',halign: 'center', width:40, align: 'center'">TYPE<br/>ZINC</th>
        <th colspan="6">COMPOSITION</th>
        <th rowspan="2" data-options="field:'QTY_TOTAL',halign: 'center', align: 'right', width:80" formatter="formatPrice">TOTAL</th>
        <th colspan="3">WEIGHT RESULT</th>
        <th rowspan="2" data-options="field:'DENSITY',halign: 'center', align: 'right', width:80" formatter="formatPrice">DENSITY</th>
        <th rowspan="2" data-options="field:'WORKER_ID_GEL',halign: 'center', width:80">ANODE GEL<br/>WORKER</th>
        <th rowspan="2" data-options="field:'UPTO_DATE_HASIL_ANODE',halign: 'center', width:80">ANODE GEL<br/>TIME</th>
        <th rowspan="2" data-options="field:'REMARK',halign: 'center', width:100">REMARK</th>
        <th rowspan="2" data-options="field:'ASSY_LINE',halign: 'center', width:80">ASSEMBLY<br/>LINE</th>
        <th rowspan="2" data-options="field:'DATE_USE',halign: 'center', width:80">ASSEMBLY<br/>TIME</th>
    </tr>
    <tr>
    	<th data-options="field:'QTY_ZN',width:70, halign: 'center', align: 'right'" formatter="formatPrice">ZN</th>
    	<th data-options="field:'QTY_AQUAPEC',width:70, halign: 'center', align: 'right'" formatter="formatPrice">AQUPEC<br>HV-505 HC</th>
    	<th data-options="field:'QTY_PW150',width:70, halign: 'center', align: 'right'" formatter="formatPrice">AQUPEC<br>HV-501 E</th>
    	<th data-options="field:'QTY_TH175B',width:70, halign: 'center', align: 'right'" formatter="formatPrice">TH-175B</th>
    	<th data-options="field:'QTY_ELEC',width:70, halign: 'center', align: 'right'" formatter="formatPrice">ELEC. L</th>
    	<th data-options="field:'QTY_AIR',width:70, halign: 'center', align: 'right'" formatter="formatPrice">AIR</th>
    	<th data-options="field:'ACT_QTY_AQUPEC',width:70, halign: 'center', align: 'right'" formatter="formatPrice">AQUPEC<br>HV-505 HC</th>
    	<th data-options="field:'ACT_QTY_PW150',width:70, halign: 'center', align: 'right'" formatter="formatPrice">AQUPEC<br>HV-501 E</th>
    	<th data-options="field:'ACT_QTY_TH175B',width:70, halign: 'center', align: 'right'" formatter="formatPrice">TH-175B</th>

    </tr>
	</thead>
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

	function formatPrice(val,row){
        if (val < 1.0 && val > 0.0){
            return '0'+val;
        } else {
            return val;
        }
    }

	$(function(){});
	var url='';	var note='';

	function filterData(){
		var tgl = new Date($('#date_prod').datebox('getValue'));
		tgl.setDate(tgl.getDate() + 1);
//
		var yy = tgl.getFullYear();;
		var mm = tgl.getMonth()+1;
		var dd = tgl.getDate();
//
		var akhir = yy+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd)+' 06:59:59';
//
		if(tgl == ''){
			$.messager.alert('Warning','Date Production not Found','warning');
		}else{
			var t = new Date();
			var mulai = $('#date_prod').datebox('getValue')+' 07:00:00 ';
			note = mulai+'to '+akhir;
			document.getElementById('ket_tgl').innerHTML = note;

			url = '?mulai='+mulai.trim()+'&akhir='+akhir.trim();

			$('#dg').datagrid( {
				url: 'anode_report_get.php'+url
			});

			$('#printpdf').linkbutton('enable');
			$('#printxls').linkbutton('enable');

			//var dg = $('#dg').datagrid();
			//dg.datagrid('enableFilter');
		}
	}

	function print_pdf(){
		if(url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('anode_report_pdf.php'+url, '_blank');
		}	
	}

	function print_xls(){
		if(url=='') {
			$.messager.alert('Warning','Data not Found, please click filter data','warning');
		}else{
			window.open('anode_report_excel.php'+url, '_blank');
		}
	}
</script>
</body>
</html>