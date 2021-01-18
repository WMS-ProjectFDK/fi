<?php
include("../../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SPAREPARTS REPORT</title>
<link rel="icon" type="image/png" href="../../../favicon.png">
<script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
</script> 
<link rel="stylesheet" type="text/css" href="../../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../../themes/color.css" />
<script type="text/javascript" src="../../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../../js/jquery.easyui.patch.js"></script>
<script type="text/javascript" src="../../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../../js/jquery.edatagrid.js"></script>
<script type="text/javascript" src="../../../js/datagrid-export.js"></script>
<style>
*{
font-size:12px;
}
body {
	font-family:verdana,helvetica,arial,sans-serif;
	padding:20px;
	font-size:10px;
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
<?php include ('../../../ico_logout.php'); ?>

<div id="toolbar" style="padding:3px 3px; height: auto;" >
	<fieldset style="float:left;width:25%;border-radius:4px;height: auto;;"><legend><span class="style3"><strong> FILTER DATA</strong></span></legend>
		<div style="width:400px;float:left">
            <div class="fitem" >
                <span style="width:110px;display:inline-block;">PERIOD</span>
                <select style="width:93px;" name="cmb_period" id="cmb_period" class="easyui-combobox" data-options=" url:'json/json_period.php',method:'get',valueField:'PERIOD_CODE',textField:'PERIOD_NAME', panelHeight:'150px'"></select>
                <span style="width:50px;display:inline-block;"></span>
                <a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
            </div>
		</div>
	</fieldset>
	<fieldset style="margin-left: 400px;border-radius:4px;height: auto; width:65%;"><legend><span class="style3"><strong>REPORT</strong></span></legend>
        <div class="fitem" align="center">
            <span style="width:110px;display:inline-block;">SELECT REPORT</span>
            <select style="width:300px;" name="cmb_report" id="cmb_report" class="easyui-combobox" data-options="panelHeight:'auto;'" required="" disabled>
                <option value="" selected="true"></option>
                <option value="AK_SALDO_STK">AK SALDO STK</option>
                <option value="AK_TRM_BRG">AK TRM BRG</option>
                <option value="MONTH_AK_KARTU_STOCK">MONTH AK KARTU STOCK</option>
                <option value="MONTH_AK_PAKAI_BRG">MONTH AK PAKAI BRG</option>
                <option value="MONTH_AK_PAKAI_SPAREPART">MONTH AK PAKAI SPAREPART</option>
                <option value="MONTH_AK_REKAPSTK_DETAIL">MONTH AK REKAPSTK DETAIL</option>
                <option value="MONTH_AK_TRM_BRG">MONTH AK TRM BRG</option>]
                <option value="REKAP_STOCK_SPART">REKAP STOCK SPART</option>]
            </select>
            <span style="width:50px;display:inline-block;"></span>
			<a href="javascript:void(0)" id="download_xls" style="width: 250px;" class="easyui-linkbutton c2" onclick="print_xls()" disabled><i class="fa fa-file-excel-o" aria-hidden="true"></i> DOWNLOAD TO EXCEL</a>
		</div>
	</fieldset>
</div>
<table id="dg" title="SPAREPARTS REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;" rownumbers="true" singleSelect="true"></table>

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
		$.messager.progress({
            title:'Please waiting',
            msg:'Generate data...'
        });

        if($('#cmb_period').combobox('getValue')==''){
			$.messager.alert('Warning','Period not Found, please select Period','warning');
		}else{
            $.post('sp_report_get.php',{
				period: $('#cmb_period').combobox('getValue')
			}).done(function(res){
				if(res == '"success"'){
                    $.messager.progress('close');
					$.messager.alert('INFORMATION','Generate Data Success..!!','info');
                    $('#cmb_report').combobox('enable');
                    $('#download_xls').linkbutton('enable');
				}
			});
		}
	}

	function print_xls(){
        console.log('sp_report_report.php?period='+$('#cmb_period').combobox('getValue')+'&jns_report='+$('#cmb_report').combobox('getValue'));
        window.open('sp_report_report.php?period='+$('#cmb_period').combobox('getValue')+'&jns_report='+$('#cmb_report').combobox('getValue'));
	}
</script>
</body>
</html>