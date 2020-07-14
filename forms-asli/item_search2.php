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
		    <a href="javascript:void(0)" id="btn_type" class="easyui-linkbutton" onClick="pilihType()" style="width:100px;"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;SEARCH</a>
		</div>

        <div id="information">
		    <div id="wh_flammable" style="float: left; margin-top: 10px;width: 100%;">
		    	<table align="center">
		    		<tr>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton" id="RM_D_01" onclick="info()">a</a></td>
		    			<td style="width:100px;height:60px;"><a href="#" style="width:100px;height:60px;background-color: #FFFFFF;" class="easyui-linkbutton" id="FL_A_12x1x1" onclick="info()">b</a></td>
		    		</tr>
		    	</table>
		    </div>
		</div>
		<div id="dlg_info" class="easyui-dialog" style="width:800px;padding:10px 20px" closed="true" buttons="#dlg-buttons-info" data-options="maximizable:true">
			<table id="dg_info" class="easyui-datagrid" style="width:750px;height: 150px;"></table>
		</div>
		<div id="dlg-buttons-info">
			<a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg_info').dialog('close')" style="width:90px"><i class="fa fa-remove" aria-hidden="true"></i>&nbsp;Close</a>
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

		var url;
		var pdf_url='';

		$(function(){
			$('#wh_flammable').hide();
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
			}else{
				$('#wh_flammable').show();
				flammable();
			}
		}

		function flammable(){
			$.ajax({
				type: 'GET',
				url: 'wh_flammable.php',
				data: { kode:'kode' },
				success: function(data){
					alert(data);
				}
			});
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