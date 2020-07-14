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
    <title>Item Search</title>
    <link rel="icon" type="image/png" href="../favicon.png">
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script> 
  	<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
  	<link rel="stylesheet" type="text/css" href="../plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../themes/color.css">
    <script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../js/datagrid-filter.js"></script>
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
	<div id="p" class="easyui-panel" title="Item Search & Visual Rack" style="width:100%;height:490px;padding:10px;" data-options="maximizable:true">
        <div id="ty" style="margin-top: 10px;float:left;width:350px;">
        	<select id="type" class="easyui-combobox" style="width:200px;" required>
		        <option value="0"></option>
		        <option value="1">ITEM</option>
		        <option value="2">WH - Cathode Can</option>
		        <option value="3">WH - Raw Material</option>
		        <option value="4">WH - Separator</option>
		        <option value="5">WH - Flammable</option>
		        <option value="6">WH - NPS</option>
		        <option value="7">WH - AREA CORIDOR</option>
		    </select>
		    <a href="javascript:void(0)" id="btn_type" class="easyui-linkbutton c2" onClick="pilihType()" style="width:100px;"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;SEARCH</a>
		</div>

		<div id="it" style="margin-top: 10px;margin-left:380px;">
	        <select id="item" class="easyui-combobox" name="item" style="width:250px;" data-options=" url:'json/json_item.php',method:'get',valueField:'item_no',textField:'description', panelHeight:'150px'" required></select>

	        <a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;SEARCH</a>
	    </div>

	    <div id="information">
		    <div id="wh_cc" style="float: left; margin-top: 10px;width:1500px;">
			    <table>
			    	<tr>
			    		<td style="height: 30px;" colspan="31" align="center"><b>WH - Cathode Can</b></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_01x3x1" onclick="info()">A01.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_02x3x1" onclick="info()">A02.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_03x3x1" onclick="info()">A03.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_04x3x1" onclick="info()">A04.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_05x3x1" onclick="info()">A05.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_06x3x1" onclick="info()">A06.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_07x3x1" onclick="info()">A07.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_08x3x1" onclick="info()">A08.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_09x3x1" onclick="info()">A09.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_10x3x1" onclick="info()">A10.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_11x3x1" onclick="info()">A11.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_12x3x1" onclick="info()">A12.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_13x3x1" onclick="info()">A13.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_14x3x1" onclick="info()">A14.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_15x3x1" onclick="info()">A15.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_16x3x1" onclick="info()">A16.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_17x3x1" onclick="info()">A17.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_18x3x1" onclick="info()">A18.3.1</a></td>
			    		<td rowspan="33" style="width:60px;height:20px;background-color: #ECF0F5;"></td>
			    		<td colspan="4" rowspan="10" style="background-color: #ECF0F5;"></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_01x2x1" onclick="info()">A01.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_02x2x1" onclick="info()">A02.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_03x2x1" onclick="info()">A03.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_04x2x1" onclick="info()">A04.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_05x2x1" onclick="info()">A05.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_06x2x1" onclick="info()">A06.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_07x2x1" onclick="info()">A07.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_08x2x1" onclick="info()">A08.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_09x2x1" onclick="info()">A09.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_10x2x1" onclick="info()">A10.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_11x2x1" onclick="info()">A11.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_12x2x1" onclick="info()">A12.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_13x2x1" onclick="info()">A13.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_14x2x1" onclick="info()">A14.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_15x2x1" onclick="info()">A15.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_16x2x1" onclick="info()">A16.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_17x2x1" onclick="info()">A17.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_18x2x1" onclick="info()">A18.2.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_01x1x1" onclick="info()">A01.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_02x1x1" onclick="info()">A02.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_03x1x1" onclick="info()">A03.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_04x1x1" onclick="info()">A04.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_05x1x1" onclick="info()">A05.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_06x1x1" onclick="info()">A06.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_07x1x1" onclick="info()">A07.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_08x1x1" onclick="info()">A08.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_09x1x1" onclick="info()">A09.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_10x1x1" onclick="info()">A10.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_11x1x1" onclick="info()">A11.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_12x1x1" onclick="info()">A12.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_13x1x1" onclick="info()">A13.1.1</a></td>
			    		<td style="width: 20px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_14x1x1" onclick="info()">A14.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_15x1x1" onclick="info()">A15.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_16x1x1" onclick="info()">A16.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_17x1x1" onclick="info()">A17.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c1" id="CC_A_18x1x1" onclick="info()">A18.1.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 60px;background-color: #ECF0F5;" colspan="26" align="center"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp;<b>JALUR FORKLIFT</b></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_01x3x1" onclick="info()">B01.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_02x3x1" onclick="info()">B02.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_03x3x1" onclick="info()">B03.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_04x3x1" onclick="info()">B04.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_05x3x1" onclick="info()">B05.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_06x3x1" onclick="info()">B06.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_07x3x1" onclick="info()">B07.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_08x3x1" onclick="info()">B08.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_09x3x1" onclick="info()">B09.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_10x3x1" onclick="info()">B10.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_11x3x1" onclick="info()">B11.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_12x3x1" onclick="info()">B12.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_13x3x1" onclick="info()">B13.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_14x3x1" onclick="info()">B14.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_15x3x1" onclick="info()">B15.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_16x3x1" onclick="info()">B16.3.1</a></td>
			    		<td colspan="3" rowspan="7" style="background-color:#ECF0F5;"></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_01x2x1" onclick="info()">B01.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_02x2x1" onclick="info()">B02.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_03x2x1" onclick="info()">B03.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_04x2x1" onclick="info()">B04.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_05x2x1" onclick="info()">B05.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_06x2x1" onclick="info()">B06.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_07x2x1" onclick="info()">B07.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_08x2x1" onclick="info()">B08.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_09x2x1" onclick="info()">B09.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_10x2x1" onclick="info()">B10.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_11x2x1" onclick="info()">B11.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_12x2x1" onclick="info()">B12.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_13x2x1" onclick="info()">B13.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_14x2x1" onclick="info()">B14.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_15x2x1" onclick="info()">B15.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_16x2x1" onclick="info()">B16.2.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_01x1x1" onclick="info()">B01.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_02x1x1" onclick="info()">B02.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_03x1x1" onclick="info()">B03.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_04x1x1" onclick="info()">B04.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_05x1x1" onclick="info()">B05.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_06x1x1" onclick="info()">B06.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_07x1x1" onclick="info()">B07.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_08x1x1" onclick="info()">B08.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_09x1x1" onclick="info()">B09.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_10x1x1" onclick="info()">B10.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_11x1x1" onclick="info()">B11.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_12x1x1" onclick="info()">B12.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_13x1x1" onclick="info()">B13.1.1</a></td>
			    		<td style="width: 20px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_14x1x1" onclick="info()">B14.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_15x1x1" onclick="info()">B15.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c2" id="CC_B_16x1x1" onclick="info()">B16.1.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 10px;" colspan="26" align="center"></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_01x3x1" onclick="info()">C01.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_02x3x1" onclick="info()">C02.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_03x3x1" onclick="info()">C03.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_04x3x1" onclick="info()">C04.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_05x3x1" onclick="info()">C05.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_06x3x1" onclick="info()">C06.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_07x3x1" onclick="info()">C07.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_08x3x1" onclick="info()">C08.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_09x3x1" onclick="info()">C09.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_10x3x1" onclick="info()">C10.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_11x3x1" onclick="info()">C11.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_12x3x1" onclick="info()">C12.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_13x3x1" onclick="info()">C13.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_14x3x1" onclick="info()">C14.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_15x3x1" onclick="info()">C15.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_16x3x1" onclick="info()">C16.3.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-01x2x1" onclick="info()">C01.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-02x2x1" onclick="info()">C02.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-03x2x1" onclick="info()">C03.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-04x2x1" onclick="info()">C04.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-05x2x1" onclick="info()">C05.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-06x2x1" onclick="info()">C06.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-07x2x1" onclick="info()">C07.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-08x2x1" onclick="info()">C08.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-09x2x1" onclick="info()">C09.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-10x2x1" onclick="info()">C10.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-11x2x1" onclick="info()">C11.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-12x2x1" onclick="info()">C12.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-13x2x1" onclick="info()">C13.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-14x2x1" onclick="info()">C14.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-15x2x1" onclick="info()">C15.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C-16x2x1" onclick="info()">C16.2.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_01x1x1" onclick="info()">C01.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_02x1x1" onclick="info()">C02.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_03x1x1" onclick="info()">C03.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_04x1x1" onclick="info()">C04.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_05x1x1" onclick="info()">C05.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_06x1x1" onclick="info()">C06.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_07x1x1" onclick="info()">C07.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_08x1x1" onclick="info()">C08.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_09x1x1" onclick="info()">C09.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_10x1x1" onclick="info()">C10.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_11x1x1" onclick="info()">C11.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_12x1x1" onclick="info()">C12.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_13x1x1" onclick="info()">C13.1.1</a></td>
			    		<td style="width: 20px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_14x1x1" onclick="info()">C14.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_15x1x1" onclick="info()">C15.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c3" id="CC_C_16x1x1" onclick="info()">C16.1.1</a></td>
			    		<td colspan="4" rowspan="2" style="background-color: #ECF0F5;" align="right"><i class="fa fa-arrow-right fa-2x" aria-hidden="true"></i></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 60px;background-color: #ECF0F5;" colspan="26" align="center"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp;<b>JALUR FORKLIFT</b></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_01x3x1" onclick="info()">D01.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_02x3x1" onclick="info()">D02.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_03x3x1" onclick="info()">D03.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_04x3x1" onclick="info()">D04.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_05x3x1" onclick="info()">D05.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_06x3x1" onclick="info()">D06.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_07x3x1" onclick="info()">D07.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_08x3x1" onclick="info()">D08.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_09x3x1" onclick="info()">D09.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_10x3x1" onclick="info()">D10.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_11x3x1" onclick="info()">D11.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_12x3x1" onclick="info()">D12.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_13x3x1" onclick="info()">D13.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_14x3x1" onclick="info()">D14.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_15x3x1" onclick="info()">D15.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_16x3x1" onclick="info()">D16.3.1</a></td>
			    		<td colspan="3" rowspan="7" style="background-color:#ECF0F5;"></td>
			    		<td colspan="4" rowspan="3" style="background-color: #ECF0F5;" align="right"></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_01x2x1" onclick="info()">D01.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_02x2x1" onclick="info()">D02.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_03x2x1" onclick="info()">D03.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_04x2x1" onclick="info()">D04.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_05x2x1" onclick="info()">D05.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_06x2x1" onclick="info()">D06.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_07x2x1" onclick="info()">D07.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_08x2x1" onclick="info()">D08.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_09x2x1" onclick="info()">D09.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_10x2x1" onclick="info()">D10.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_11x2x1" onclick="info()">D11.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_12x2x1" onclick="info()">D12.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_13x2x1" onclick="info()">D13.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_14x2x1" onclick="info()">D14.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_15x2x1" onclick="info()">D15.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_16x2x1" onclick="info()">D16.2.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_01x1x1" onclick="info()">D01.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_02x1x1" onclick="info()">D02.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_03x1x1" onclick="info()">D03.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_04x1x1" onclick="info()">D04.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_05x1x1" onclick="info()">D05.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_06x1x1" onclick="info()">D06.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_07x1x1" onclick="info()">D07.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_08x1x1" onclick="info()">D08.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_09x1x1" onclick="info()">D09.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_10x1x1" onclick="info()">D10.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_11x1x1" onclick="info()">D11.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_12x1x1" onclick="info()">D12.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_13x1x1" onclick="info()">D13.1.1</a></td>
			    		<td style="width: 20px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_14x1x1" onclick="info()">D14.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_15x1x1" onclick="info()">D15.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c4" id="CC_D_16x1x1" onclick="info()">D16.1.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 10px;" colspan="26" align="center"></td>
			    		<td colspan="4" rowspan="4">
			    			<table>
			    				<tr>
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_01x3x1" onclick="info()">I01.3.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_01x2x1" onclick="info()">I01.2.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_01x1x1" onclick="info()">I01.1.1</a></td>
			    				</tr>
			    				<tr>
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_02x3x1" onclick="info()">I02.3.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_02x2x1" onclick="info()">I02.2.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_02x1x1" onclick="info()">I02.1.1</a></td>	
			    				</tr>
			    			</table>
			    		</td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_01x3x1" onclick="info()">E01.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_02x3x1" onclick="info()">E02.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_03x3x1" onclick="info()">E03.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_04x3x1" onclick="info()">E04.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_05x3x1" onclick="info()">E05.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_06x3x1" onclick="info()">E06.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_07x3x1" onclick="info()">E07.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_08x3x1" onclick="info()">E08.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_09x3x1" onclick="info()">E09.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_10x3x1" onclick="info()">E10.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_11x3x1" onclick="info()">E11.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_12x3x1" onclick="info()">E12.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_13x3x1" onclick="info()">E13.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_14x3x1" onclick="info()">E14.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_15x3x1" onclick="info()">E15.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_16x3x1" onclick="info()">E16.3.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_01x2x1" onclick="info()">E01.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_02x2x1" onclick="info()">E02.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_03x2x1" onclick="info()">E03.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_04x2x1" onclick="info()">E04.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_05x2x1" onclick="info()">E05.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_06x2x1" onclick="info()">E06.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_07x2x1" onclick="info()">E07.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_08x2x1" onclick="info()">E08.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_09x2x1" onclick="info()">E09.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_10x2x1" onclick="info()">E10.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_11x2x1" onclick="info()">E11.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_12x2x1" onclick="info()">E12.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_13x2x1" onclick="info()">E13.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_14x2x1" onclick="info()">E14.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_15x2x1" onclick="info()">E15.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_16x2x1" onclick="info()">E16.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_01x1x1" onclick="info()">E01.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_02x1x1" onclick="info()">E02.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_03x1x1" onclick="info()">E03.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_04x1x1" onclick="info()">E04.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_05x1x1" onclick="info()">E05.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_06x1x1" onclick="info()">E06.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_07x1x1" onclick="info()">E07.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_08x1x1" onclick="info()">E08.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_09x1x1" onclick="info()">E09.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_10x1x1" onclick="info()">E10.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_11x1x1" onclick="info()">E11.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_12x1x1" onclick="info()">E12.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_13x1x1" onclick="info()">E13.1.1</a></td>
			    		<td style="width: 20px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_14x1x1" onclick="info()">E14.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_15x1x1" onclick="info()">E15.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c5" id="CC_E_16x1x1" onclick="info()">E16.1.1</a></td>
			    		<td colspan="4"></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 60px;background-color: #ECF0F5;" colspan="26" align="center"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp;<b>JALUR FORKLIFT</b></td>
			    		<td colspan="4">
			    			<table style="margin-top: 0px;">
			    				<tr>
			    					<td valign="top" style="width: 50px;height:30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_03x3x1" onclick="info()">I03.3.1</a></td>
			    					<td valign="top" style="width: 50px;height:30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_03x2x1" onclick="info()">I03.2.1</a></td>
			    					<td valign="top" style="width: 50px;height:30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_03x1x1" onclick="info()">I03.1.1</a></td>
			    				</tr>
			    				<tr>
			    					<td valign="top" style="width: 50px;height:30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_04x3x1" onclick="info()">I04.3.1</a></td>
			    					<td valign="top" style="width: 50px;height:30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_04x2x1" onclick="info()">I04.2.1</a></td>
			    					<td valign="top" style="width: 50px;height:30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_04x1x1" onclick="info()">I04.1.1</a></td>
			    				</tr>
			    			</table>
			    		</td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_01x3x1" onclick="info()">F01.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_02x3x1" onclick="info()">F02.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_03x3x1" onclick="info()">F03.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_04x3x1" onclick="info()">F04.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_05x3x1" onclick="info()">F05.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_06x3x1" onclick="info()">F06.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_07x3x1" onclick="info()">F07.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_08x3x1" onclick="info()">F08.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_09x3x1" onclick="info()">F09.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_10x3x1" onclick="info()">F10.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_11x3x1" onclick="info()">F11.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_12x3x1" onclick="info()">F12.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_13x3x1" onclick="info()">F13.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_14x3x1" onclick="info()">F14.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_15x3x1" onclick="info()">F15.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_16x3x1" onclick="info()">F16.3.1</a></td>
			    		<td colspan="3" rowspan="7" style="background-color:#ECF0F5;"></td>
			    		<td colspan="4" rowspan="4">
			    			<table >
			    				<tr valign="bottom">
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_05x3x1" onclick="info()">I05.3.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_05x2x1" onclick="info()">I05.2.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_05x1x1" onclick="info()">I05.1.1</a></td>
			    				</tr>
			    				<tr valign="bottom">
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_06x3x1" onclick="info()">I06.3.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_06x2x1" onclick="info()">I06.2.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_06x1x1" onclick="info()">I06.1.1</a></td>
			    				</tr>
			    			</table>
			    		</td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_01x2x1" onclick="info()">F01.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_02x2x1" onclick="info()">F02.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_03x2x1" onclick="info()">F03.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_04x2x1" onclick="info()">F04.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_05x2x1" onclick="info()">F05.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_06x2x1" onclick="info()">F06.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_07x2x1" onclick="info()">F07.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_08x2x1" onclick="info()">F08.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_09x2x1" onclick="info()">F09.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_10x2x1" onclick="info()">F10.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_11x2x1" onclick="info()">F11.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_12x2x1" onclick="info()">F12.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_13x2x1" onclick="info()">F13.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_14x2x1" onclick="info()">F14.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_15x2x1" onclick="info()">F15.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_16x2x1" onclick="info()">F16.2.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_01x1x1" onclick="info()">F01.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_02x1x1" onclick="info()">F02.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_03x1x1" onclick="info()">F03.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_04x1x1" onclick="info()">F04.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_05x1x1" onclick="info()">F05.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_06x1x1" onclick="info()">F06.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_07x1x1" onclick="info()">F07.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_08x1x1" onclick="info()">F08.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_09x1x1" onclick="info()">F09.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_10x1x1" onclick="info()">F10.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_11x1x1" onclick="info()">F11.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_12x1x1" onclick="info()">F12.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_13x1x1" onclick="info()">F13.1.1</a></td>
			    		<td style="width: 20px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_14x1x1" onclick="info()">F14.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_15x1x1" onclick="info()">F15.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c6" id="CC_F_16x1x1" onclick="info()">F16.1.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 10px;" colspan="26" align="center"></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_01x3x1" onclick="info()">G01.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_02x3x1" onclick="info()">G02.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_03x3x1" onclick="info()">G03.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_04x3x1" onclick="info()">G04.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_05x3x1" onclick="info()">G05.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_06x3x1" onclick="info()">G06.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_07x3x1" onclick="info()">G07.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_08x3x1" onclick="info()">G08.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_09x3x1" onclick="info()">G09.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_10x3x1" onclick="info()">G10.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_11x3x1" onclick="info()">G11.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_12x3x1" onclick="info()">G12.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_13x3x1" onclick="info()">G13.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_14x3x1" onclick="info()">G14.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_15x3x1" onclick="info()">G15.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_16x3x1" onclick="info()">G16.3.1</a></td>
			    		<td colspan="4" rowspan="3">
			    			<table>
			    				<tr>
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_07x3x1" onclick="info()">I07.3.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_07x2x1" onclick="info()">I07.2.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_07x1x1" onclick="info()">I07.1.1</a></td>
			    				</tr>
			    				<tr>
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_08x3x1" onclick="info()">I08.3.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_08x2x1" onclick="info()">I08.2.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_08x1x1" onclick="info()">I08.1.1</a></td>
			    				</tr>
			    			</table>
			    		</td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_01x2x1" onclick="info()">G01.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_02x2x1" onclick="info()">G02.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_03x2x1" onclick="info()">G03.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_04x2x1" onclick="info()">G04.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_05x2x1" onclick="info()">G05.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_06x2x1" onclick="info()">G06.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_07x2x1" onclick="info()">G07.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_08x2x1" onclick="info()">G08.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_09x2x1" onclick="info()">G09.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_10x2x1" onclick="info()">G10.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_11x2x1" onclick="info()">G11.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_12x2x1" onclick="info()">G12.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_13x2x1" onclick="info()">G13.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_14x2x1" onclick="info()">G14.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_15x2x1" onclick="info()">G15.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_16x2x1" onclick="info()">G16.2.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_01x1x1" onclick="info()">G01.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_02x1x1" onclick="info()">G02.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_03x1x1" onclick="info()">G03.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_04x1x1" onclick="info()">G04.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_05x1x1" onclick="info()">G05.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_06x1x1" onclick="info()">G06.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_07x1x1" onclick="info()">G07.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_08x1x1" onclick="info()">G08.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_09x1x1" onclick="info()">G09.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_10x1x1" onclick="info()">G10.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_11x1x1" onclick="info()">G11.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_12x1x1" onclick="info()">G12.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_13x1x1" onclick="info()">G13.1.1</a></td>
			    		<td style="width: 20px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_14x1x1" onclick="info()">G14.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_15x1x1" onclick="info()">G15.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c7" id="CC_G_16x1x1" onclick="info()">G16.1.1</a></td>
			    		<td colspan="4"></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 5px;background-color: #ECF0F5;" colspan="26" align="center"></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 60px;background-color: #ECF0F5;" colspan="26" align="center"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp;<b>JALUR FORKLIFT</b></td>
			    		<td colspan="3">
			    			<table>
			    				<tr>
			    					<td><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_09x3x1" onclick="info()">I09.3.1</a></td>
			    					<td><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_09x2x1" onclick="info()">I09.2.1</a></td>
			    					<td><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_09x1x1" onclick="info()">I09.1.1</a></td>
			    				</tr>
			    				<tr>
			    					<td><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_10x3x1" onclick="info()">I10.3.1</a></td>
			    					<td><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_10x2x1" onclick="info()">I10.2.1</a></td>
			    					<td><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_10x1x1" onclick="info()">I10.1.1</a></td>
			    				</trCC.I36>
			    			</table>
			    		</td>
			    		<td style="width:20px;height:20px;"></td>
			    	</tr>
			    	<tr>
			    		<td style="height: 5px;background-color: #ECF0F5;" colspan="26" align="center"></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_01x3x1" onclick="info()">H01.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_02x3x1" onclick="info()">H02.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_03x3x1" onclick="info()">H03.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_04x3x1" onclick="info()">H04.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_05x3x1" onclick="info()">H05.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_06x3x1" onclick="info()">H06.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_07x3x1" onclick="info()">H07.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_08x3x1" onclick="info()">H08.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_09x3x1" onclick="info()">H09.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_10x3x1" onclick="info()">H10.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_11x3x1" onclick="info()">H11.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_12x3x1" onclick="info()">H12.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_13x3x1" onclick="info()">H13.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_14x3x1" onclick="info()">H14.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_15x3x1" onclick="info()">H15.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_16x3x1" onclick="info()">H16.3.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_17x3x1" onclick="info()">H17.3.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_18x3x1" onclick="info()">H18.3.1</a></td>
			    		<td colspan="4" rowspan="3">
			    			<table>
			    				<tr>
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_11x3x1" onclick="info()">I11.3.1</a></td>
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_11x2x1" onclick="info()">I11.2.1</a></td>
			    					<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_11x1x1" onclick="info()">I11.1.1</a></td>
			    				</tr>
			    				<tr>
			  						<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_12x3x1" onclick="info()">I12.3.1</a></td>
			  						<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_12x2x1" onclick="info()">I12.2.1</a></td>
						    		<td style="width: 50px;height: 30px;"><a href="#" style="width: 50px;height:30px;background-color: #FFC000;" class="easyui-linkbutton c9" id="CC_I_12x1x1" onclick="info()">I12.1.1</a></td>
			    				</tr>
			    			</table>
			    		</td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_01x2x1" onclick="info()">H01.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_02x2x1" onclick="info()">H02.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_03x2x1" onclick="info()">H03.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_04x2x1" onclick="info()">H04.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_05x2x1" onclick="info()">H05.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_06x2x1" onclick="info()">H06.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_07x2x1" onclick="info()">H07.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_08x2x1" onclick="info()">H08.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_09x2x1" onclick="info()">H09.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_10x2x1" onclick="info()">H10.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_11x2x1" onclick="info()">H11.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_12x2x1" onclick="info()">H12.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_13x2x1" onclick="info()">H13.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_14x2x1" onclick="info()">H14.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_15x2x1" onclick="info()">H15.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_16x2x1" onclick="info()">H16.2.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_17x2x1" onclick="info()">H17.2.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_18x2x1" onclick="info()">H18.2.1</a></td>
			    	</tr>
			    	<tr>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_01x1x1" onclick="info()">H01.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_02x1x1" onclick="info()">H02.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_03x1x1" onclick="info()">H03.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_04x1x1" onclick="info()">H04.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_05x1x1" onclick="info()">H05.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_06x1x1" onclick="info()">H06.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_07x1x1" onclick="info()">H07.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_08x1x1" onclick="info()">H08.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_09x1x1" onclick="info()">H09.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_10x1x1" onclick="info()">H10.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_11x1x1" onclick="info()">H11.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_12x1x1" onclick="info()">H12.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_13x1x1" onclick="info()">H13.1.1</a></td>
			    		<td style="width: 20px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_14x1x1" onclick="info()">H14.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_15x1x1" onclick="info()">H15.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_16x1x1" onclick="info()">H16.1.1</a></td>
			    		<td style="width:20px;height:20px;"></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_17x1x1" onclick="info()">H17.1.1</a></td>
			    		<td style="width: 50px;height: 20px;"><a href="#" style="width: 50px;height:20px;background-color: #FFC000;" class="easyui-linkbutton c8" id="CC_H_18x1x1" onclick="info()">H18.1.1</a></td>
			    	</tr>
			    </table>
			</div>

			<div id="wh_rm"  style="float: left; margin-top: 10px;width:2000px;">
		        <table align="center">
		        	<tr>
		        		<td colspan="28" align="center" style="height:40px;"><b>WH - RAW MATERIAL</b></td>
		        	</tr>
		        	<tr>
		        		<td colspan="3" style="width:340px;height:50px;"></td>
		        		<td rowspan="3" style="width:100px;" align="center" valign="top"><b>LIFT</b><br/><i class="fa fa-arrow-up fa-2x" aria-hidden="true"></i></td>
		        		<td align="center"></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFC000;" class="easyui-linkbutton c7" id="RM_C_01" onclick="info()">C01</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFC000;" class="easyui-linkbutton c7" id="RM_C_02" onclick="info()">C02</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFC000;" class="easyui-linkbutton c7" id="RM_C_03" onclick="info()">C03</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFC000;" class="easyui-linkbutton c7" id="RM_C_04" onclick="info()">C04</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFC000;" class="easyui-linkbutton c7" id="RM_C_05" onclick="info()">C05</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFC000;" class="easyui-linkbutton c7" id="RM_C_06" onclick="info()">C06</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFC000;" class="easyui-linkbutton c7" id="RM_C_07" onclick="info()">C07</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFCCCC;" class="easyui-linkbutton c3" id="RM_D_01" onclick="info()">D01</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFCCCC;" class="easyui-linkbutton c3" id="RM_D_02" onclick="info()">D02</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #FFCCCC;" class="easyui-linkbutton c3" id="RM_D_03" onclick="info()">D03</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #33CC33;" class="easyui-linkbutton c1" id="RM_E_01" onclick="info()">E01</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #33CC33;" class="easyui-linkbutton c1" id="RM_E_02" onclick="info()">E02</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #33CC33;" class="easyui-linkbutton c1" id="RM_E_03" onclick="info()">E03</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #33CC33;" class="easyui-linkbutton c1" id="RM_E_04" onclick="info()">E04</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"></td>
			        	<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #3333FF;" class="easyui-linkbutton c8" id="RM_F_01" onclick="info()">F01</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #3333FF;" class="easyui-linkbutton c8" id="RM_F_02" onclick="info()">F02</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #3333FF;" class="easyui-linkbutton c8" id="RM_F_03" onclick="info()">F03</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #3333FF;" class="easyui-linkbutton c8" id="RM_F_04" onclick="info()">F04</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #3333FF;" class="easyui-linkbutton c8" id="RM_F_05" onclick="info()">F05</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"><a href="#" style="width: 50px;height:100px;background-color: #999999;" class="easyui-linkbutton c2" id="RM_G_01" onclick="info()">G01</a></td>
		        		<td rowspan="3" style="width:50px;height:90px;"></td>
		        		<td style="width:50px;height:45px;"><a href="#" style="width: 50px;height:45px;background-color: #999999;" class="easyui-linkbutton c2" id="RM_H_01" onclick="info()">H01</a></td>
		        		<td rowspan="10" style="width:25px;height:90px;"></td>
		        	</tr>
		        	<tr>
		        		<td rowspan="2" colspan="3" style="height:40px;width: 350px;"><a href="#" style="width:350px;height:40px;background-color: #AAFF55;" class="easyui-linkbutton c6" id="RM_L_01" onclick="info()">L01</a></td>
		        		<td align="center" style="height:50px;background-color: #F4F4F4;">AREA DO</td>
		        		<td style="width:50px;height:20px;"></td>
		        	</tr>
		        	<tr>
		        		<td colspan="33" style="height:10px;"></td>
		        	</tr>
		        	<tr>
		        		<td colspan="3" align="center" style="height:30px;background-color: #24748F;color:#ffffff"><b>RM L = 14 PALLET</b></td>
		        		<td colspan="2" style="height:30px;"></td>
		        		<td colspan="10" align="center" style="height:30px;background-color: #FFC000;"><b>EMD GHT2</b></td>
		        		<td colspan="3" align="center" style="height:30px;background-color: #FFCCCC;"><b>KOH</b></td>
		        		<td style="width:40px;height:30px;"></td>
		        		<td colspan="4" align="center" style="height:30px;background-color: #33CC33;"><b>EMD HHT2</b></td>
		        		<td style="width:40px;height:30px;"></td>
		        		<td colspan="6" align="center" style="height:30px;background-color: #3333FF;"><b>EMD HHT2A</b></td>
		        		<td colspan="3" align="center" style="height:30px;background-color: #999999;"></td>
		        	</tr>
		        	<tr>
		        		<td align="left" colspan="1" style="height:70px;"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></td>
		        		<td align="center" colspan="9" style="height:70px;"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp;<b>JALUR FORKLIFT</b></td>
		        		<td align="center" colspan="9" style="height:70px;"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp;<b>JALUR FORKLIFT</b></td>
		        		<td align="center" colspan="9" style="height:70px;"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp;<b>JALUR FORKLIFT</b></td>
		        		<td align="right" colspan="5" style="height:70px;"><i class="fa fa-arrow-right fa-2x" aria-hidden="true"></i>&nbsp;<b>INCOMING<br/>AREA</b></td>
		        	</tr>
		        	<tr>
		        		<td align="center" style="height:30px;background-color: #339999;color:#ffffff"><b>RM I = 4 PALLET</b></td>
		        		<td align="center"></td>
		        		<td align="center" style="height:30px;background-color: #D4FF00;"><b>RM J = 3 PALLET</b></td>
		        		<td style="width:100px;" align="center"></td>
		        		<td align="center" style="height:30px;background-color: #AA00FF;"><b>RM K = 6 PALLET</b></td>
		        		<td colspan="8" align="center" style="height:30px;background-color: #C9302C;color: #ffffff;"><b>RM B=24 PALLET</b></td>
		        		<td colspan="8" align="center" style="height:30px;"></td>
		        		<td colspan="12" align="center" style="height:30px;background-color: #C5F1FF;"><b>RM A=36 PALLET</b></td>
		        	</tr>
		        	<tr>
		        		<td colspan="33" style="height:10px;"></td>
		        	</tr>
		        	<tr>
		        		<td align ="center" rowspan="3" style="width:150px;height:60px;">
		        			<a href="#" style="width:150px;height:60px;background-color: #339999;" class="easyui-linkbutton c4" id="RM_I_01" onclick="info()">I01</a>
		        		</td>
		        		<td align ="center" rowspan="3" style="width:40px;background-color: red;color:#ffffff">APAR</td>
		        		<td align ="center" rowspan="3" style="width:60px;height:60px;">
		        			<a href="#" style="width:60px;height:60px;background-color: #D4FF00;" class="easyui-linkbutton c1" id="RM_J_01" onclick="info()">J01</a>
		        		</td>
		        		<td rowspan="3" style="width:100px;" align="center" valign="bottom"><i class="fa fa-arrow-down fa-2x" aria-hidden="true"></i><br/>Pintu<br/>Emergency</td>
		        		<td align ="center" rowspan="3" style="width:150px;height:60px;">
		        			<a href="#" style="width:120px;height:60px;background-color: #AA00FF;" class="easyui-linkbutton c9" id="RM_K_01" onclick="info()">K01</a>
		        		</td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_01x3x1" onclick="info()">B01.3.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_02x3x1" onclick="info()">B02.3.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_03x3x1" onclick="info()">B03.3.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_04x3x1" onclick="info()">B04.3.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_05x3x1" onclick="info()">B05.3.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_06x3x1" onclick="info()">B06.3.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_07x3x1" onclick="info()">B07.3.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_08x3x1" onclick="info()">B08.3.1</a></td>
		        		<td style="width:50px;"></td>
		        		<td rowspan="3" align ="center" style="width:50px;height:60px;background-color: #FF0000;color:#ffffff">APAR</td>
		        		<td colspan=3 rowspan="3" align ="center" style="width:150px;height:60px;"></td>
		        		<td colspan="3" rowspan="3" align ="center" style="width:150px;height:60px;background-color: #FF0000;color:#ffffff">HYDRANT</td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_01x3x1" onclick="info()">A01.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_02x3x1" onclick="info()">A02.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_03x3x1" onclick="info()">A03.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_04x3x1" onclick="info()">A04.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_05x3x1" onclick="info()">A05.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_06x3x1" onclick="info()">A06.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_07x3x1" onclick="info()">A07.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_08x3x1" onclick="info()">A08.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_09x3x1" onclick="info()">A09.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_10x3x1" onclick="info()">A10.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_11x3x1" onclick="info()">A11.3.1</a></td>
		        		<td style="width:50px;height:20px;font-size:8px"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_12x3x1" onclick="info()">A12.3.1</a></td>
		        	</tr>
		        	<tr>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_01x2x1" onclick="info()">B01.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_02x2x1" onclick="info()">B02.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_03x2x1" onclick="info()">B03.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_04x2x1" onclick="info()">B04.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_05x2x1" onclick="info()">B05.2.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_06x2x1" onclick="info()">B06.2.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_07x2x1" onclick="info()">B07.2.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_08x2x1" onclick="info()">B08.2.1</a></td>
		        		<td style="width:50px;"></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_01x2x1" onclick="info()">A01.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_02x2x1" onclick="info()">A02.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_03x2x1" onclick="info()">A03.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_04x2x1" onclick="info()">A04.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_05x2x1" onclick="info()">A05.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_06x2x1" onclick="info()">A06.2.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_07x2x1" onclick="info()">A07.2.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_08x2x1" onclick="info()">A08.2.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_09x2x1" onclick="info()">A09.2.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_10x2x1" onclick="info()">A10.2.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_11x2x1" onclick="info()">A11.2.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_12x2x1" onclick="info()">A12.2.1</a></td>
		        	</tr>
		        	<tr>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_01x1x1" onclick="info()">B01.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_02x1x1" onclick="info()">B02.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_03x1x1" onclick="info()">B03.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_04x1x1" onclick="info()">B04.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_05x1x1" onclick="info()">B05.1.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_06x1x1" onclick="info()">B06.1.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_07x1x1" onclick="info()">B07.1.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #DD4B39;" class="easyui-linkbutton c5" id="RM_B_08x1x1" onclick="info()">B08.1.1</a></td>
		        		<td style="width:50px;"></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_01x1x1" onclick="info()">A01.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_02x1x1" onclick="info()">A02.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_03x1x1" onclick="info()">A03.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_04x1x1" onclick="info()">A04.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_05x1x1" onclick="info()">A05.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_06x1x1" onclick="info()">A06.1.1</a></td>
		        		<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_07x1x1" onclick="info()">A07.1.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_08x1x1" onclick="info()">A08.1.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_09x1x1" onclick="info()">A09.1.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_10x1x1" onclick="info()">A10.1.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_11x1x1" onclick="info()">A11.1.1</a></td>
						<td style="width:50px;height:20px;"><a href="#" style="width: 50px;height:20px;background-color: #C5F1FF;" class="easyui-linkbutton c4" id="RM_A_12x1x1" onclick="info()">A12.1.1</a></td>
		        	</tr>
		        </table>
		    </div>

		    <div id="wh_separator" style="float: left; margin-top: 10px;width: 100%;">
			    <table align="center">
		    		<tr>
		    			<td colspan="5" align="center" style="height:20px;"><b>WH - SEPARATOR</b></td>
		    		</tr>
		    		<tr>
		    			<td rowspan="11" style="width:120px;height:60px;background-color: #F4F4F4;"></td>
		    			<!-- <td style="width:60px;height:60px;"><a class="btn btn-primary" style="width:60px;height:60px;font-size: 8px;" id="SR.A.01-1-10" onclick="info()" href="#" role="button">A01.1.10</a></td> -->
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x10" onclick="info()">A01.1.10</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x10" onclick="info()">A02.1.10</a></td>
		    			<td colspan="2" rowspan="2" align="center" style="width:60px;height:60px;background-color: #F4F4F4;"><i class="fa fa-gears fa-spin fa-2x" ari_-_idxex="true"></i></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x09" onclick="info()">A01.1.09</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x09" onclick="info()">A02.1.09</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x08" onclick="info()">A01.1.08</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x08" onclick="info()">A02.1.08</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_03x1x08" onclick="info()">A03.1.08</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_04x1x08" onclick="info()">A04.1.08</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x07" onclick="info()">A01.1.07</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x07" onclick="info()">A02.1.07</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_03x1x07" onclick="info()">A03.1.07</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_04x1x07" onclick="info()">A04.1.07</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x06" onclick="info()">A01.1.06</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x06" onclick="info()">A02.1.06</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_03x1x06" onclick="info()">A03.1.06</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_04x1x06" onclick="info()">A04.1.06</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x05" onclick="info()">A01.1.05</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x05" onclick="info()">A02.1.05</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_03x1x05" onclick="info()">A03.1.05</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_04x1x05" onclick="info()">A04.1.05</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x04" onclick="info()">A01.1.04</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x04" onclick="info()">A02.1.04</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_03x1x04" onclick="info()">A03.1.04</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_04x1x04" onclick="info()">A04.1.04</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x03" onclick="info()">A01.1.03</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x03" onclick="info()">A02.1.03</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_03x1x03" onclick="info()">A03.1.03</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_04x1x03" onclick="info()">A04.1.03</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x02" onclick="info()">A01.1.02</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x02" onclick="info()">A02.1.02</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_03x1x02" onclick="info()">A03.1.02</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_04x1x02" onclick="info()">A04.1.02</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_01x1x01" onclick="info()">A01.1.01</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_02x1x01" onclick="info()">A02.1.01</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_03x1x01" onclick="info()">A03.1.01</a></td>
		    			<td style="width:60px;height:60px;"><a href="#" style="width:60px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="SR_A_04x1x01" onclick="info()">A04.1.01</a></td>
		    		</tr>
		    		<tr>
		    			<td colspan="4" align="right" style="width:60px;height:60px;background-color: #F4F4F4;"><b>PINTU&nbsp;&nbsp;&nbsp;&nbsp;</b><i class="fa fa-arrow-right fa-4x" aria-hidden="true"></i></td>
		    		</tr>
			    </table>
			</div>

		    <div id="wh_flammable" style="float: left; margin-top: 10px;width: 100%;">
		    	<table align="center">
		    		<tr>
		    			<td colspan="7" align="center" style="height:20px;"><b>WH - FLAMMABLE</b></td>
		    		</tr>
		    		<tr>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_12x1x1" onclick="info()">FL.A.12</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_11x1x1" onclick="info()">FL.A.11</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_10x1x1" onclick="info()">FL.A.10</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_09x1x1" onclick="info()">FL.A.09</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_08x1x1" onclick="info()">FL.A.08</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_07x1x1" onclick="info()">FL.A.07</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_05x1x1" onclick="info()">FL.A.05</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_06x1x1" onclick="info()">FL.A.06</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_03x1x1" onclick="info()">FL.A.03</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_04x1x1" onclick="info()">FL.A.04</a></td>
		    		</tr>
		    		<tr>
		    			<td  colspan="2" align="center" style="width:200px;height:60px;border-left:2px solid #000000;border-top:2px solid #000000;border-right:2px solid #000000;border-bottom:2px solid #000000;"><i class="fa fa-circle fa-2x" aria-hidden="true"></i><i class="fa fa-circle" aria-hidden="true"></i>&nbsp;<b>SOLAR</b></td>
		    			<td colspan="3" align="center" style="width:100px;height:60px;"><i class="fa fa-arrow-up fa-4x" aria-hidden="true"></i></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_01x1x1" onclick="info()">FL.A.01</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton c3" id="FL_A_02x1x1" onclick="info()">FL.A.02</a></td>
		    		</tr>
		    	</table>
		    </div>

		    <div id="wh_nps" style="float: left; margin-top: 10px;width: 100%;">
		    	<table align="center">
		    		<tr>
		    			<td colspan="9" align="center" style="height:20px;"><b>WH - NPS</b></td>
		    		</tr>
		    		<tr>
			    		<td style="width:80px;height:50px;border-left:1px solid #000000;border-top:1px solid #000000;"></td>
			    		<td style="width:80px;height:50px;border-top:1px solid #000000;"></td>
			    		<td style="width:80px;height:50px;border-top:1px solid #000000;"></td>
			    		<td style="width:80px;height:50px;border-right:1px solid #000000;border-top:1px solid #000000;"></td>
		    			<td colspan="5" style="width:50px;height:60px;"></td>
					</tr>
					<tr>
			    		<td style="width:80px;height:50px;border-left:1px solid #000000;"></td>
			    		<td style="width:80px;height:50px;"></td>
			    		<td style="width:80px;height:50px;"></td>
			    		<td style="width:80px;height:50px;"></td>
		    			<td colspan="5" style="width:50px;height:60px;"></td>
					</tr>
					<tr>
			    		<td align="center" colspan="4" rowspan="5" style="border-left:1px solid #000000;border-right:1px solid #000000;font-size: 10px;"><b><i class="fa fa-gear fa-spin fa-2x" aria-hidden="true"></i> AREA MESIN CATHODE CAN LR03 </b></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x2x1" onclick="info()">A01.2.1</a></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x2x2" onclick="info()">A01.2.2</a></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x2x3" onclick="info()">A01.2.3</a></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x2x4" onclick="info()">A01.2.4</a></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x2x5" onclick="info()">A01.2.5</a></td>
					</tr>
					<tr>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x1x1" onclick="info()">A01.1.1</a></td>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x1x2" onclick="info()">A01.1.2</a></td>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x1x3" onclick="info()">A01.1.3</a></td>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x1x4" onclick="info()">A01.1.4</a></td>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_01x1x5" onclick="info()">A01.1.5</a></td>
					</tr>
					<tr>
						<td colspan="5" style="height:10px;"></td>
					</tr>
					<tr>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x2x1" onclick="info()">A02.2.1</a></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x2x2" onclick="info()">A02.2.2</a></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x2x3" onclick="info()">A02.2.3</a></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x2x4" onclick="info()">A02.2.4</a></td>
		    			<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x2x5" onclick="info()">A02.2.5</a></td>
					</tr>
					<tr>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x1x1" onclick="info()">A02.1.1</a></td>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x1x2" onclick="info()">A02.1.2</a></td>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x1x3" onclick="info()">A02.1.3</a></td>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x1x4" onclick="info()">A02.1.4</a></td>
						<td style="width:80px;height:25px;"><a href="#" style="width:80px;height:25px;background-color: #FFFFFF;" class="easyui-linkbutton c7" id="NP_A_02x1x5" onclick="info()">A02.1.5</a></td>
					</tr>
					<tr>
			    		<td style="width:80px;height:50px;border-left:1px solid #000000;"></td>
			    		<td style="width:80px;height:50px;"></td>
			    		<td style="width:80px;height:50px;"></td>
			    		<td align="right" style="width:80px;height:50px;"><i class="fa fa-arrow-right fa-4x" aria-hidden="true"></i></td>
		    			<td colspan="5" style="width:50px;height:60px;"></td>
					</tr>
					<tr>
			    		<td style="width:80px;height:50px;border-left:1px solid #000000;border-bottom:1px solid #000000;"></td>
			    		<td style="width:80px;height:50px;border-bottom:1px solid #000000;"></td>
			    		<td style="width:80px;height:50px;border-bottom:1px solid #000000;"></td>
			    		<td style="width:80px;height:50px;border-right:1px solid #000000;border-bottom:1px solid #000000;"></td>
		    			<td colspan="5" style="width:50px;height:60px;"></td>
					</tr>	
		    	</table>
		    </div>

		    <div id="wh_area_coridor" style="float: left; margin-top: 10px;width: 100%;">
		    	<table align="center">
		    		<tr>
		    			<td colspan="17" align="center" style="height:20px;"><b>WH - AREA CORIDOR</b></td>
		    		</tr>
		    		<tr>
		    			<td colspan="3" align="center" style="width:60px;height:70px;border:1px solid #000000;"><b>ASSEMBLING LR6-U</b></td>
		    			<td align="center" style="width:60px;height:70px;border:1px solid #000000;"><b>TANGGA</b></td>
		    			<td align="center" style="width:60px;height:70px;border:1px solid #000000;"><b>CARGO LIFT</b></td>
		    			<td align="center" colspan="8" style="width:60px;height:70px;border:1px solid #000000;"><i class="fa fa-wrench" aria-hidden="true"></i><br/><b>COMPONENT</b></td>
		    			<td align="center" colspan="4" style="width:60px;height:70px;border:1px solid #000000;"><i class="fa fa-wrench" aria-hidden="true"></i><br/><b>COMPONENT</b></td>
		    		</tr>
		    		<!-- <tr>
		    			<td colspan="5" style="width:60px;height:35px;"></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_12" onclick="info()">A02.2.12</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_11" onclick="info()">A02.2.11</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_10" onclick="info()">A02.2.10</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_9" onclick="info()">A02.2.9</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_8" onclick="info()">A02.2.8</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_7" onclick="info()">A02.2.7</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_6" onclick="info()">A02.2.6</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_5" onclick="info()">A02.2.5</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_4" onclick="info()">A02.2.4</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_3" onclick="info()">A02.2.3</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_2" onclick="info()">A02.2.2</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_1" onclick="info()">A02.2.1</a></td>
		    		</tr> -->
		    		<tr>
		    			<td colspan="5" style="width:60px;height:35px;"></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_12" onclick="info()">A.02.12</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_11" onclick="info()">A.02.11</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_10" onclick="info()">A.02.10</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_9" onclick="info()">A.02.9</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_8" onclick="info()">A.02.8</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_7" onclick="info()">A.02.7</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_6" onclick="info()">A.02.6</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_5" onclick="info()">A.02.5</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_4" onclick="info()">A.02.4</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_3" onclick="info()">A.02.3</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_2" onclick="info()">A.02.2</a></td>
		    			<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_02_1" onclick="info()">A.02.1</a></td>
		    		</tr>
		    		<tr>
		    			<td style="width:60px;height:70px;" align="center" colspan="5"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp; <b>JALUR FORKLIFT</b></td>
		    			<td style="width:60px;height:70px;" align="center" colspan="10"><i class="fa fa-subway fa-2x" aria-hidden="true"></i>&nbsp; <b>JALUR FORKLIFT</b></td>
		    			<td style="width:60px;height:70px;" align="right" colspan="2"><i class="fa fa-arrow-left fa-4x" aria-hidden="true"></i></td>
		    		</tr>
		    		<!-- <tr>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_17" onclick="info()">A01.2.17</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_16" onclick="info()">A01.2.16</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_15" onclick="info()">A01.2.15</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_14" onclick="info()">A01.2.14</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_13" onclick="info()">A01.2.13</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_12" onclick="info()">A01.2.12</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_11" onclick="info()">A01.2.11</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_10" onclick="info()">A01.2.10</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_9" onclick="info()">A01.2.9</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_8" onclick="info()">A01.2.8</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_7" onclick="info()">A01.2.7</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_6" onclick="info()">A01.2.6</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_5" onclick="info()">A01.2.5</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_4" onclick="info()">A01.2.4</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_3" onclick="info()">A01.2.3</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_2" onclick="info()">A01.2.2</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4" id="CD_A_01_1" onclick="info()">A01.2.1</a></td>
		    		</tr> -->
		    		<tr>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_17" onclick="info()">A.01.17</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_16" onclick="info()">A.01.16</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_15" onclick="info()">A.01.15</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_14" onclick="info()">A.01.14</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_13" onclick="info()">A.01.13</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_12" onclick="info()">A.01.12</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_11" onclick="info()">A.01.11</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_10" onclick="info()">A.01.10</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_9" onclick="info()">A.01.9</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_8" onclick="info()">A.01.8</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_7" onclick="info()">A.01.7</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_6" onclick="info()">A.01.6</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_5" onclick="info()">A.01.5</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_4" onclick="info()">A.01.4</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_3" onclick="info()">A.01.3</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_2" onclick="info()">A.01.2</a></td>
		        		<td style="width:60px;height:35px;"><a href="#" style="width: 60px;height:35px;background-color: #FFFFFF;" class="easyui-linkbutton c4"  id="CD_A_01_1" onclick="info()">A.01.1</a></td>
		    		</tr>
		    	</table>
		    </div>
		</div>
    </div>

    <div id="dlg_view" class="easyui-dialog" style="width:950px;" closed="true" buttons="#dlg-buttons-view" data-options="modal:true">
		<table id="dg_view" class="easyui-datagrid" style="height: 350px;"></table>
	</div>

	<div id="dlg_info" class="easyui-dialog" style="width:800px;padding:10px 20px" closed="true" buttons="#dlg-buttons-info" data-options="maximizable:true, modal:true">
		<table id="dg_info" class="easyui-datagrid" style="width:750px;height: 150px;"></table>
	</div>

	<div id="dlg-buttons-view">
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-excel" onclick="download_data()" style="width:90px">Download</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="view_rack()" style="width:90px">View Rack</a>
	</div>

	<div id="dlg-buttons-info">
		<a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg_info').dialog('close')" style="width:90px"><i class="fa fa-remove" aria-hidden="true"></i>&nbsp;Close</a>
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

		var url;
		var pdf_url='';

		$(function(){
			$('#it').hide();
			$('#wh_cc').hide();
			$('#wh_rm').hide();
			$('#wh_separator').hide();	
			$('#wh_flammable').hide();	
			$('#wh_nps').hide();
			$('#wh_area_coridor').hide();
		});

		$('#SR.A.01-1-10').linkbutton({
			Select: true
		})

		$('#dg_view').datagrid({
			url:'item_search_get.php',
			singleSelect: true,
			rownumbers: true,
			fitColumns: false,
		    columns:[[
		    	{field:'ID',title:'ID<br>INCOMING', halign:'center', width:80, sortable: true},
		    	{field:'GR_NO',title:'GOOD RECEIVE<br>NO.', halign:'center', width:100, sortable: true},
		    	{field:'GR_DATE',title:'GOOD RECEIVE<br>DATE', halign:'center', align:'center', width:80, sortable: true},
		    	{field:'ITEM_NO', title:'ITEM NO.', halign:'center', align:'center'},
		    	{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:200, sortable: true},
                {field:'LINE_NO', title:'LINE', halign:'center', align:'center', width:70}, 
                {field:'WAREHOUSE',title:'WH Name', halign:'center', align:'center', width:110, sortable: true},
                {field:'RACK',title:'Rack No.', halign:'center', align:'center', width:100, sortable: true},
                {field:'PALLET', title:'PALLET', halign:'center', align:'center', width:70},
                {field:'QTY', title:'QTY', halign:'center', align:'right', width:100}
		    ]]
		});

		$('#dg_info').datagrid({
			url:'item_search_info.php',
			rownumbers: true,
			fitColumns: true,
		    columns:[[
		    	{field:'WAREHOUSE',title:'WH Name', halign:'center', align:'center', width:140, sortable: true},
		    	{field:'RACK',title:'RACK', halign:'center', align:'center', width:100, sortable: true},
                {field:'BARANG', title:'ITEM', halign:'center', width:250}, 
                {field:'QTY', title:'QTY', halign:'center', align:'center', width:50},
                {field:'PALLET', title:'PALLET', halign:'center', align:'center', width:50},
                {field:'TGL',title:'Receive Date', halign:'center', align:'center', width:100, sortable: true}
		    ]]
		});

		function pilihType(){
			$('#wh_cc').hide();
			$('#wh_rm').hide();
			$('#wh_separator').hide();	
			$('#wh_flammable').hide();	
			$('#wh_nps').hide();
			$('#wh_area_coridor').hide();
			var cari = $('#type').combobox('getValue');
			if(cari== '0'){
				$.messager.show({title: 'warning',msg: 'Please select type'});
			}else if(cari == '1'){
				$('#it').show();
				$('#item').combobox('setValue','');
				$('#wh_cc').hide();
				$('#wh_rm').hide();
				$('#wh_separator').hide();	
				$('#wh_flammable').hide();	
				$('#wh_nps').hide();
				$('#wh_area_coridor').hide();	
			}else if(cari=='2'){
				$('#it').hide();
				$('#wh_rm').hide();
				$('#wh_separator').hide();	
				$('#wh_flammable').hide();	
				$('#wh_nps').hide();
				$('#wh_area_coridor').hide();
				$('#wh_cc').show();
			}else if(cari=='3'){
				$('#it').hide();
				$('#wh_cc').hide();
				$('#wh_separator').hide();	
				$('#wh_flammable').hide();	
				$('#wh_nps').hide();
				$('#wh_area_coridor').hide();
				$('#wh_rm').show();
			}else if(cari=='4'){
				$('#it').hide();
				$('#wh_cc').hide();
				$('#wh_rm').hide();
				$('#wh_flammable').hide();	
				$('#wh_nps').hide();
				$('#wh_area_coridor').hide();
				$('#wh_separator').show();	
			}else if(cari=='5'){
				$('#it').hide();
				$('#wh_cc').hide();
				$('#wh_rm').hide();
				$('#wh_separator').hide();	
				$('#wh_nps').hide();
				$('#wh_area_coridor').hide();
				$('#wh_flammable').show();	
			}else if(cari=='6'){
				$('#it').hide();
				$('#wh_cc').hide();
				$('#wh_rm').hide();
				$('#wh_separator').hide();	
				$('#wh_flammable').hide();	
				$('#wh_area_coridor').hide();
				$('#wh_nps').show();	
			}else if(cari=='7'){
				$('#it').hide();
				$('#wh_cc').hide();
				$('#wh_rm').hide();
				$('#wh_separator').hide();	
				$('#wh_flammable').hide();	
				$('#wh_nps').hide();
				$('#wh_area_coridor').show();	
			}

		}

		function flammable(){
			$('#it').hide();
			$('#wh_cc').hide();
			$('#wh_rm').hide();
			$('#wh_separator').hide();	
			$('#wh_nps').hide();
			$('#wh_area_coridor').hide();
			$('#wh_flammable').show();
		}

		function filterData(){
			$('#wh_rm').hide();
			var t = $('#item').combobox('getValue');
			var t_name = $('#item').combobox('getText');
			if(t!=0){
				$('#dlg_view').dialog('open').dialog('setTitle','View Details Item ('+t+' - '+t_name+')');

				$('#dg_view').datagrid('load', {
					item: $('#item').combobox('getValue')
				});
			}else{
				$.messager.show({title: 'warning',msg: 'Please select item'});
			}
		}

		function view_rack(){
			var row = $('#dg_view').datagrid('getSelected');
			if(row){
				/*alert (row.RACK+'-'+row.WAREHOUSE);*/
				var WH = row.WAREHOUSE;		var r = row.RACK;
				if(WH == 'WH-CATHODE CAN'){
					$('#wh_cc').show();	
				}else if(WH == 'WH-RAW MATERIAL'){
					$('#wh_rm').show();
					$('#'+row.rack+'').attr('background-color','red');
				}else if(WH == 'WH-SEPARATOR'){
					$('#wh_separator').show();
				}else if(WH == 'WH-FLAMMABLE'){
					$('#wh_flammable').show();
				}else if(WH == 'WH-NPS'){
					$('#wh_nps').show();
				}else if(WH == 'WH-AREA CORIDOR'){
					$('#wh_area_coridor').show();
				}
				$('#dlg_view').dialog('close');
			}else{
				$.messager.show({title: 'visual Rack',msg: 'Data Not select'});
			}
		}

		function download_data(){
			var cmb_item = $('#item').combobox('getValue');
			if(cmb_item!=''){
				window.open('item_search_print.php?item='+$('#item').combobox('getValue'));
			}else{
				$.messager.show({title: 'PDF Report',msg: 'Data Not select'});
			}
		}

		function info(){
			var divID = '';
			$('#information a').click(function(){
				var ID = $(this).attr('id');
				divID = ID.replace(/_/gi,'.');
				//alert (divID);
				var a = $(this).attr('id').replace(/_/gi,'.');
				var b = a.replace(/x/gi,'-');
				$('#dg_info').datagrid( 'load',{
					rack: b
				});

				$('#dlg_info').dialog('open').dialog('setTitle','Information Rack ('+b+')');
				document.getElementById('information').reload();
			});
		}

	</script>
    </body>
    </html>