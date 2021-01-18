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
<title>ACCOUNTING REPORT</title>
<link rel="icon" type="image/png" href="../../favicon.png">
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
<script type="text/javascript" src="../../js/jquery.easyui.patch.js"></script>
<script type="text/javascript" src="../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../js/jquery.edatagrid.js"></script>
<script type="text/javascript" src="../../js/datagrid-export.js"></script>
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
<?php include ('../../ico_logout.php'); ?>

<div id="toolbar" style="padding:3px 3px; height: auto;" >
	<fieldset style="float:left;width:98%;border-radius:4px;height: auto;;"><legend><span class="style3"><strong> FILTER REPORT</strong></span></legend>
		<div style="width:100%;float:left">
            <div class="fitem" >
                <span style="width:110px;display:inline-block;">PERIOD</span>
                <select style="width:93px;" name="cmb_period" id="cmb_period" class="easyui-combobox" data-options=" url:'../json/json_period_report.php',method:'get',valueField:'PERIOD_CODE',textField:'PERIOD_NAME', panelHeight:'150px'" required></select>
                <span style="width:50px;display:inline-block;"></span>
                <span style="width:110px;display:inline-block;">SELECT REPORT</span>
                <select style="width:300px;" name="cmb_report" id="cmb_report" class="easyui-combobox" data-options="panelHeight:'auto;'" required="">
                    <option value="" selected="true"></option>
                    <option value="J_PURC_IMP">JURNAL PURCHASE IMPORT</option>
                    <option value="J_PURC_LOC">JURNAL PURCHASE LOCAL</option>
		    <option value="J_PURC_SP">JURNAL PURCHASE SPAREPARTS</option>
                    <option value="SALES_ACCPAC">SALES ACCPAC</option>
                    <option value="COST_OF_SALES">COST OF SALES</option>
                    <option value="FG_INCOME">FG INCOME</option>
                    <option value="FG_INTRANSIT">FG INTRANSIT</option>
                    <option value="MATERIAL_COST">MATERIAL COST</option>
                </select>
                <span style="width:50px;display:inline-block;"></span>
                <a href="javascript:void(0)" id="download_xls" style="width: 250px;" class="easyui-linkbutton c2" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> DOWNLOAD TO EXCEL</a>
            </div>
        </div>
	</fieldset>
</div>
<table id="dg" title="ACCOUNTING REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;" rownumbers="true" singleSelect="true"></table>

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

    var get_url='';
	function print_xls(){
        var cmb_period = $('#cmb_period').combobox('getValue');
        var cmb_report = $('#cmb_report').combobox('getValue');
        
        if (cmb_period == '' && cmb_report == ''){
            $.messager.alert('Warning','Period not Found or report not selected','warning');   
        }else{
            get_url = '?period='+cmb_period+'&jns_report='+cmb_report;
            
            if (cmb_report == 'J_PURC_IMP'){
                console.log('J_PURC_IMP.php'+get_url);
                window.open('J_PURC_IMP.php'+get_url);
            }else if (cmb_report == 'J_PURC_LOC'){
                console.log('J_PURC_LOC.php'+get_url);
                window.open('J_PURC_LOC.php'+get_url);
            }else if (cmb_report == 'SALES_ACCPAC'){
                console.log('SALES_ACCPAC.php'+get_url);
                window.open('SALES_ACCPAC.php'+get_url);
            }else if (cmb_report == 'COST_OF_SALES'){
                console.log('COST_OF_SALES.php'+get_url);
                window.open('COST_OF_SALES.php'+get_url);
            }else if (cmb_report == 'FG_INCOME'){
                console.log('FG_INCOME.php'+get_url);
                window.open('FG_INCOME.php'+get_url);
            }else if (cmb_report == 'FG_INTRANSIT'){
                console.log('FG_INTRANSIT.php'+get_url);
                window.open('FG_INTRANSIT.php'+get_url);
            }else if (cmb_report == 'MATERIAL_COST'){
                console.log('MATERIAL_COST.php'+get_url);
                window.open('MATERIAL_COST.php'+get_url);

            }else if (cmb_report == 'J_PURC_SP'){
                console.log('J_PURC_SP.php'+get_url);
                window.open('J_PURC_SP.php'+get_url);
            }
        }
	}
</script>
</body>
</html>