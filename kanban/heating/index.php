<?php include("../../connect/conn.php"); ?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>Heating Kanban System</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../themes/color.css" />
    <script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
	<style>
	*{
	font-size:12px;
	}
	body {
		font-family:verdana,helvetica,arial,sans-serif;
		padding:20px;
		font-size:16px;
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
    <body class="easyui-layout">
		<div id="layout" 
		 data-options="region: 'north',
					   iconCls: 'icon-save',
					   title: 'HEATING KANBAN SYSTEM',
					   collapsible:false,
					   modal: true,
					   tools: '#tt',
					   footer: '#footer'"
		 style="height:100%;padding:5px;">
			<div style="position:absolute;border-radius:4px;width: 100%;border: 1px;" align="center">
				<div class="fitem" style="padding: 5px;">
					<span style="width:450px;display:inline-block;font-size: 18px;font-weight: bold;">SCAN KANBAN ONE WAY (KECIL)</span> 
					<input style="width:530px; height: 41px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
						onkeypress="scan_assy(event)" name="scn_assy" id="scn_assy" type="text" 
						autofocus="autofocus" />
				</div>
				<div class="fitem" style="padding: 5px;">
					<span style="width:450px;display:inline-block;font-size: 18px;font-weight: bold;">SCAN KANBAN CIRCULATE (BESAR)</span> 
					<input style="width:530px; height: 41px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
						onkeypress="scan_pallet(event)" name="scn_pallet" id="scn_pallet" type="text"/>
				</div>
				<div class="fitem" style="padding: 5px;">
					<span style="width:450px;display:inline-block;font-size: 25px;font-weight: bold;"></span> 
					<a  id="savebtn_Plan" class="easyui-linkbutton c2" onClick="proses()" style="width:530px; height: 41px;"><i class="fa fa-save" aria-hidden="true"></i> Process</a>
				</div>
			</div>

			<div style="margin-top: 190px;" align="center">
				<div align="center">
					<table id="dg_details" title="TOP 7 TRANSACTION" class="easyui-datagrid" style="width:95%;height:400px;border-radius:4px;">
						
					</table>
				</div>
			</div>

			<div style="clear:both;"></div>
			<div id="footer" style="padding:5px;" align="center">
		        <small><i>&copy; Copyright 2018, PT. FDK INDONESIA</i></small>
		    </div>
		</div>

		<script type="text/javascript">
			var sc1 = '';		var sc2 = '';		//var age = '';
			var ins = '';		var outs= '';

			function table_proses(){
				$('#dg_details').datagrid('loaded');
				$('#dg_details').datagrid( {
					url: 'heat_details_get.php'
				});
			}

			$('#dg_details').datagrid( {
				url: 'heat_details_get.php',
				fitColumns: true,
				rownumbers: true,
			    columns:[[
			    	{field:'ID_PRINT',title:'ID PRINT',width:80,halign:'center'},
			    	{field:'ID_PLAN',title:'ID PLAN',width:130,halign:'center'},
	                {field:'ASSY_LINE',title:'ASSEMBLY<br>LINE',width:80,halign:'center'},
	                {field:'CELL_TYPE',title:'CELL TYPE',width:80,halign:'center'},
	                {field:'PALLET',title:'PALLET',width:40,halign:'center', align:'center'},
	                {field:'QTY',title:'QTY',width:80,halign:'center', align:'right'},
	                {field:'REMARK', title:'STATUS',width:120,halign:'center', align:'center'},
	                {field:'UPTO_DATE',title:'DATE TIME',width:120,halign:'center'}
				]]
			});

			function scan_assy(event){
				var src1 = document.getElementById('scn_assy').value;
				var search1 = src1.toUpperCase();
				document.getElementById('scn_assy').value = search1;

		        if(event.keyCode == 13 || event.which == 13){
		            sc1 = document.getElementById('scn_assy').value;
		            document.getElementById('scn_pallet').focus();
		        }
		    }

		    function scan_pallet(event){
		    	if(event.keyCode == 13 || event.which == 13){
		    		var src2 = document.getElementById('scn_pallet').value;
					var search2 = src2.toUpperCase();
					document.getElementById('scn_pallet').value = search2;
		    		sc2 = document.getElementById('scn_pallet').value;
		    		proses();
		    	}
		    }

			var myVar = setInterval(function(){cek_jumlah()},1000);

		    function proses(){
		    	var split1 = sc1.split(",");
		    	var split2 = sc2.split(",");

		    	if (sc2 == ''){
		    		$.messager.alert('INFORMATION','SCAN KANBAN CIRCULATE TIDAK BOLEH KOSONG','info');
		    	}else{
		    		//CEK STATUS
			    	$.ajax({
						type: 'GET',
						url: 'cek_status.php?id_pallet='+split2[0]+'&id_print='+split1[8],
						success: function(data){
							if(data[0].kode=='IN'){
								in_heating();
								table_proses();
							}else if(data[0].kode=='OUT'){
								out_heating();
								table_proses();
							}else if(data[0].kode=='EXISTS'){
								$.messager.alert('INFORMATION','PALLET SUDAH TIDAK ADA DI HEATING ROOM','info');
								setTimeout(function () { window.location.reload(1); }, 2000);
							}else if(data[0].kode=='ASSEMBLY'){
								$.messager.alert('INFORMATION','PROSES ASSEMBLING BELUM SELESAI','info');
								setTimeout(function () { window.location.reload(1); }, 2000);
							}
						}
					});
					
					document.getElementById('scn_assy').value = '';
			    	document.getElementById('scn_pallet').value = '';
		    		document.getElementById('scn_assy').focus();
		    	}
		    }

		    function in_heating(){
		    	var split1 = sc1.split(",");
		    	var split2 = sc2.split(",");

		    	$.post('save_heating.php',{
		    		id_prin: split1[8],
					id_plan: split1[7],
					id_pall: split2[0],
					remarks: 'IN'/*,
					aging : age*/
		    	},function(result){
					if(result.successMsg == 'IN-HEATING'){
						$.messager.alert('INFORMATION','IN-HEATING','info');
					}else{
	                    $.messager.show({title: 'Error',msg: result.errorMsg});
	                }
	                setTimeout(function () { window.location.reload(1); }, 2000);
				},'json');
	    		document.getElementById('scn_assy').focus();
		    }

		    function out_heating(){
		    	var split1 = sc1.split(",");
		    	var split2 = sc2.split(",");

		    	$.post('save_heating.php',{
		    		id_prin: split1[8],
					id_plan: split1[7],
					id_pall: split2[0],
					remarks: 'OUT'/*,
					aging : age*/
		    	},function(result){
					if(result.successMsg == 'OUT-HEATING'){
						$.messager.alert('INFORMATION','OUT-HEATING','info');
					}else{
	                    $.messager.show({title: 'Error',msg: result.errorMsg});
	                }
	                setTimeout(function () { window.location.reload(1); }, 2000);
				},'json');
	    		document.getElementById('scn_assy').focus();
		    }
		</script>
 	</body>
	</html>