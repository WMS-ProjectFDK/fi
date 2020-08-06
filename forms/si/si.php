<?php 
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>SHIPPING INSTRUCTION</title>

    <link rel="icon" type="image/png" href="../../favicon.png">

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
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
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
	<?php include ('../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name)); ?>
	
	<table id="dg" title="SHIPPING INSTRUCTION" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;"></table>
	<div id="toolbar">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:95%; height:100px; float:left;"><legend>SHIPPING INSTRUCTION FILTER</legend>
			<div style="width:470px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">CR Date</span>
					<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					to 
					<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
                </div>
                <div class="fitem">
					<span style="width:80px;display:inline-block;">SI/NO.</span>
					<select style="width:330px;" name="cmb_si_no" id="cmb_si_no" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'si_no', textField:'si_no', panelHeight:'100px'"></select>
					<label><input type="checkbox" name="ck_si_no" id="ck_si_no" checked="true">All</input></label>
				</div>
            </div>
			<div>
                <div class="fitem">
                    <span style="width:100px;display:inline-block;">CONSIGNEE</span>
                    <input style="width:85px;" name="consignee_code" id="consignee_code" class="easyui-textbox" disabled=""/>
                    <select style="width:240px;" name="consignee_name" id="consignee_name" class="easyui-combobox"/></select>
                    <label><input type="checkbox" name="ck_si_no" id="ck_si_no" checked="true">All</input></label>
				</div>
                <div class="fitem">
					<span style="width:100px;display:inline-block;">CUST PO/NO.</span>
					<select style="width:300px;" name="cmb_prf_no" id="cmb_prf_no" class="easyui-combobox" data-options=" url:'../json/json_prf_no.php',method:'get',valueField:'prf_no',textField:'prf_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_prf" id="ck_prf" checked="true">All</input></label>
				</div>
			</div>
		</fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 5px;margin: 5px;">
			<input style="width:150px; height: 17px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" />
			<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</a>
			<a href="javascript:void(0)" style="width: 100px;" id="add" class="easyui-linkbutton c2" onclick="addPRF()"><i class="fa fa-plus" aria-hidden="true"></i> ADD SI</a>
			<a href="javascript:void(0)" style="width: 100px;" id="edit" class="easyui-linkbutton c2" onclick="editPRF()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT SI</a>
			<a href="javascript:void(0)" style="width: 100px;" id="delete" class="easyui-linkbutton c2" onclick="destroyPRF()"><i class="fa fa-trash" aria-hidden="true"></i> DELETE SI</a>
		</div>
    </div>
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
    </Script>
	</body>
    </html>