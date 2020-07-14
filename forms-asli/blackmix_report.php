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
<title>BALCK MIX REPORT</title>
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
<table id="dg" title="PEMAKAIAN BLACK MIX" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;" data-options="rownumbers: true, singleSelect: true, iconCls: 'icon-save', method: 'get', showFooter: true, fitColumns: true">
    <thead>
    <tr>
    	<th rowspan="2" data-options="field:'assy_line',halign: 'center', width:80">ASSEMBLY<br/>LINE</th>
    	<th rowspan="2" data-options="field:'NO_HOPPER',halign: 'center', width:50, align: 'center'">HOPPER<br/>NO.</th>
    	<th rowspan="2" data-options="field:'tgl_pemakaian',halign: 'center', width:70">WAKTU<br/>PENUANGAN</th>
        <th rowspan="2" data-options="field:'type_out',halign: 'center', width:60">TYPE</th>
        <th colspan="6">PRODUKSI BLACK MIX</th>
        <th rowspan="2" data-options="field:'petugas_penuangan',halign: 'center', width:80">PETUGAS<br/>SUPPLY</th>
        <th rowspan="2" data-options="field:'keterangan',halign: 'center', width:100">KETERANGAN</th>
        
    </tr>
    <tr>
    	<th data-options="field:'time_out',width:70, halign: 'center', align: 'right'" formatter="formatPrice">OUTPUT DATE</th>
    	<th data-options="field:'tag_no',width:70, halign: 'center', align: 'right'" formatter="formatPrice">TAG NO.</th>
    	<th data-options="field:'resistivity',width:70, halign: 'center', align: 'right'" formatter="formatPrice">RESISTIVITY</th>
    	<th data-options="field:'density',width:70, halign: 'center', align: 'right'" formatter="formatPrice">DENSITY</th>
    	<th data-options="field:'moisture',width:70, halign: 'center', align: 'right'" formatter="formatPrice">MOISTURE</th>
    	<th data-options="field:'type_out',width:70, halign: 'center', align: 'right'" formatter="formatPrice">TYPE EMD</th>

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

		var yy = tgl.getFullYear();;
		var mm = tgl.getMonth()+1;
		var dd = tgl.getDate();

		var akhir = yy+'-'+(mm<10?('0'+mm):mm)+'-'+(dd<10?('0'+dd):dd)+' 06:59:59';

		if(tgl == ''){
			$.messager.alert('Warning','Date Production not Found','warning');
		}else{
			var t = new Date();
			var mulai = $('#date_prod').datebox('getValue')+' 07:00:00 ';
			note = mulai+'to '+akhir;
			document.getElementById('ket_tgl').innerHTML = note;

			url = '?mulai='+mulai.trim()+'&akhir='+akhir.trim();

			$('#dg').datagrid( {
				url: 'blackmix_report_get.php'+url
			});

			$('#printpdf').linkbutton('enable');
			$('#printxls').linkbutton('enable');
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