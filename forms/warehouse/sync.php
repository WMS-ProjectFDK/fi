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
    <title>Syncronized</title>
	<script language="javascript">
 		function confirmLogOut(){
			var is_confirmed;
			is_confirmed = window.confirm("End current session?");
			return is_confirmed;
 		}
  	</script> 
    <link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css">
    <link rel="stylesheet" type="text/css" href="../../themes/color.css">
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="../../js/datagrid-filter.js"></script>
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
	<?php 
	include ('../../ico_logout.php'); 
	//include ('sync_get2.php'); 
	?>
	
	<div id="toolbar" style="margin-top: 5px;margin-bottom: 5px;width: 100%;">
		<div align="center" class="fitem" style="width: 100%;">
			<a href="#" style="width:150px;height:60px;" class="easyui-linkbutton c2" onclick="select_device()"><i class="fa fa-tablet"></i><br/>SELECT DEVICE</a>
			<a href="#" style="width:150px;height:60px;" class="easyui-linkbutton c2" onclick="sync()"><i class="fa fa-refresh fa-spin fa-2x fa-fw"></i><br/>SYNCRONIZED</a>
		</div>
		<br/>
		<div id="progress" class="easyui-progressbar" style="margin-left: 20px; width: 96%;"></div><br/>
	</div>
	<table id="dg" title="SYNCRONIZED DATA" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:auto;"></table>

	<div id="dlg" class="easyui-dialog" style="width:500px;" closed="true" data-options="position:'bottom', modal:true" buttons="#dlg-buttons">
		<div class="fitem" style="margin:2px; padding:15px 15px;">
			<span style="width:100px;display:inline-block;">Barcode Device</span>
			<select id="brc" class="easyui-combobox" name="brc" style="width:200px;" data-options="panelHeight:'50px'" required="">
			    <option></option>
			    <!-- <option value="172.23.206.90">Device I (90)</option> -->
			    <option value="172.23.206.94">Device I (94)</option>
			</select>
		</div>
	</div>
	<div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="proses_device()" style="width:90px">Process</a>
    </div>

	<script type="text/javascript">

		function select_device(){
            $('#dlg').dialog('open').dialog('center').dialog('setTitle','select Barcode Device');
        }

		$('#dg').datagrid({
		    url:'sync_get.php',
		   	singleSelect: true,
			rownumbers: true,
			fitColumns:true,
		    columns:[[
		    	{field:'ID_NO', hidden: true},
		    	{field:'TANGGAL',title:'DATE',halign:'center', width:50},
			    {field:'TYPE', hidden: true},
			    {field:'NAME_TYPE',title:'TYPE',width:70, halign:'center'},
			    {field:'ID',title:'ID', halign:'center', align:'center', width:50},
			    {field:'DOC',title:'SLIP_NO',halign:'center', width:100},
			    {field:'LINE',title:'LINE', halign:'center', align:'center', width:30},
			    {field:'ITEM',title:'ITEM',halign:'center', width:80},
			    {field:'DESCRIPTION',title:'DESCRIPTION',halign:'center', width:200},
			    {field:'RACK',title:'RACK',halign:'center', width:80},
			    {field:'PALLET',title:'PALLET',halign:'center', align:'center', width:30},
			    {field:'QTY',title:'QTY',halign:'center', align:'right', width:50},
			    {field:'FLAG', hidden: true}
		    ]]
	    });

		function proses_device(){
			var cmb_D = $('#brc').combobox('getValue');
			$.post("sync_get2.php", {
				device: cmb_D
			}).done(function(res){
				//alert(res);
			});
			$('#dlg').dialog('close');
			$('#dg').datagrid('reload');
		}

		var hasil = '';

		function sync(){
			$.messager.progress({
            	title:'Please waiting',
            	msg:'save data...'
            });

			var dataRows = [];
			rows = $('#dg').datagrid('getRows');
			total = rows.length;

			for(i=0;i<total;i++){
				$('#dg').datagrid('endEdit', i);
				/*$.post('sync_save.php',{
                	sync_idno: $('#dg').datagrid('getData').rows[i].ID_NO,
					sync_id: $('#dg').datagrid('getData').rows[i].ID,
					sync_type: $('#dg').datagrid('getData').rows[i].NAME_TYPE,
					sync_rack: $('#dg').datagrid('getData').rows[i].RACK,
					sync_qty: $('#dg').datagrid('getData').rows[i].QTY.replace(/,/g,''),
					sync_doc: $('#dg').datagrid('getData').rows[i].DOC,
					sync_item: $('#dg').datagrid('getData').rows[i].ITEM,
					sync_line: $('#dg').datagrid('getData').rows[i].LINE,
					sync_pallet: $('#dg').datagrid('getData').rows[i].PALLET
                },function(result){
                	if(i==total-1 || total == 1){
				    	if (result.successMsg == 'SUCCESS'){
				    	    $.messager.progress('close');
							$.messager.alert('INFORMATION','SYNCRONIZED SUCCESS','info');
							$('#dg').datagrid('reload');
				    	}else{
				    		$.messager.progress('close');
				    	    $.messager.show({
				    	        title: 'Error',
				    	        msg: result.errorMsg
				    	    });
				    	}
				    }
				},'json');*/

				dataRows.push({
                	sync_idno: $('#dg').datagrid('getData').rows[i].ID_NO,
					sync_id: $('#dg').datagrid('getData').rows[i].ID,
					sync_type: $('#dg').datagrid('getData').rows[i].NAME_TYPE,
					sync_rack: $('#dg').datagrid('getData').rows[i].RACK,
					sync_qty: $('#dg').datagrid('getData').rows[i].QTY,
					sync_doc: $('#dg').datagrid('getData').rows[i].DOC,
					sync_item: $('#dg').datagrid('getData').rows[i].ITEM,
					sync_line: $('#dg').datagrid('getData').rows[i].LINE,
					sync_pallet: $('#dg').datagrid('getData').rows[i].PALLET
                });
            }

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			//$.messager.alert('Error',unescape(str_unescape),'warning');

			$.post('sync_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Syncronized Data Success..!!','info');
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}
	</script>
    </body>
    </html>