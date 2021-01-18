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
<title>RECEIVE AGING REPORT</title>
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
                <span style="width:110px;display:inline-block;">SELECT REPORT</span>
                <select style="width:300px;" name="cmb_report" id="cmb_report" class="easyui-combobox" data-options="panelHeight:'auto;'" required="">
                    <option value="" selected="true"></option>
                    <option value="MATERIAL">MATERIAL</option>
                    <option value="SPAREPARTS">SPAREPARTS</option>
                </select>
                <span style="width:50px;display:inline-block;"></span>
                <a href="javascript:void(0)" id="download_xls" style="width: 250px;" class="easyui-linkbutton c2" onclick="print_xls()"><i class="fa fa-file-excel-o" aria-hidden="true"></i> DOWNLOAD TO EXCEL</a>
            </div>
        </div>
	</fieldset>
</div>
<table id="dg" title="ACCOUNRECEIVE AGING REPORT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;" rownumbers="true" singleSelect="true"></table>

<script type="text/javascript">
    var get_url='';
	function print_xls(){
        var cmb_report = $('#cmb_report').combobox('getValue');
        
        if (cmb_report == ''){
            $.messager.alert('Warning','Report not selected','warning');   
        }else{
            get_url = '?jns_report='+cmb_report;
            console.log('RECEIVE_AGING_PRINT.php'+get_url);
            window.open('RECEIVE_AGING_PRINT.php'+get_url);
        }
	}
</script>
</body>
</html>