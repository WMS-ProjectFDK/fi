<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>IN Label Kanban System</title>
<link rel="icon" type="../image/png" href="../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../themes/icon.css" />
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
<body>
	<div id="p" class="easyui-panel" title="IN LABEL KANBAN SYSTEM" data-options="iconCls:'icon-save', tools:'#tt', footer:'#footer'" style="width:100%;height:655px;padding:5px;">
		<fieldset style="position: absolute; float: left;margin-left:5px;border-radius:4px;width: 43%;height: 570px;"><legend><span class="style3"><strong>IN LABEL TRANSACTION</strong></span></legend>
			<div class="fitem" style="padding: 5px;" align="center">
				<span style="width:50%;display:inline-block;font-size: 14px;font-weight: bold;">SCAN KANBAN ASSY</span>
			</div>
			<div class="fitem" style="padding: 5px;" align="center">
				<input style="width:550px; height: 40px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
					onkeypress="scan_lbl(event)" name="scan_lbl" id="scan_lbl" type="text" 
					autofocus="autofocus" />
			</div>
			<hr>
			<div class="fitem" style="padding: 5px;" align="center">
				<!-- <a id="savebtn_Plan" class="easyui-linkbutton c2" onClick="proses()" style="width:180px; height: 41px;display:inline-block;" disabled="true"><i class="fa fa-save" aria-hidden="true"></i> PROCESS</a> -->
				<a id="savebtn_Plan" class="easyui-linkbutton c5" onClick="cancel()" style="width:180px; height: 41px;display:inline-block;"><i class="fa fa-remove" aria-hidden="true"></i> CANCEL</a>
			</div>
			<hr>
			<span style="display:inline-block;font-weight: bold;">REPORT : </span>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true" >REPORT 1</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true">REPORT 2</a>
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true">REPORT 3</a>
		</fieldset>

		<fieldset style="position:absolute;margin-left:46%;border-radius:4px;width: 49%;height: 570px;"><legend><span class="style3"><strong>LIST OF IN LABEL BATTERY</strong></span></legend>
			<table id="dg" class="easyui-datagrid" data-options="method: 'get', fitColumns: true, height: '550'">
		        <thead>
		            <tr>
						<th halign="center" data-options="field:'ID_PRINT', width: 75">ID PRINT</th>
						<th halign="center" data-options="field:'ASSY_LINE', width: 75">ASSY<br/>LINE</th>
						<th halign="center" data-options="field:'PALLET', width: 45, align:'center'">PALLET</th>
						<th halign="center" data-options="field:'CELL_TYPE', width: 50">GRADE</th>
						<th halign="center" data-options="field:'QTY', width: 80, align:'right'">QTY</th>
						<th halign="center" data-options="field:'LOT_NUMBER', width: 80, align:'center'">LOT<br/>NUMBER</th>
						<th halign="center" data-options="field:'UPTO_DATE', width: 100">SCAN TIME</th>
						<th halign="center" data-options="field:'STS', width: 100">REMARK</th>
		            </tr>
		        </thead>
		    </table>
		</fieldset>
		<br/>
		<div style="clear:both;"></div>
		<div id="footer" style="padding:5px;" align="center">
	        <small><i>&copy; Copyright 2019, PT. FDK INDONESIA</i></small>
	    </div>
	</div>

	<script type="text/javascript">
		var sc = '';

		function content_list(){
			$('#dg').datagrid( {
				url: 'list_in_label.php'
			});

			$('#dg').datagrid('enableFilter');
		}

		$(function(){
			content_list();
		})

		function cancel(){
			document.getElementById('scan_lbl').focus();
            document.getElementById('scan_lbl').value = ''
		}

		function scan_lbl(event){
	        if(event.keyCode == 13 || event.which == 13){
	            sc = document.getElementById('scan_lbl').value;
	            document.getElementById('scan_lbl').focus();
	            document.getElementById('scan_lbl').value = ''
	            proses();
	            content_list();
	        }
	    }

	    function proses(){
	    	var split = sc.split(",");

	    	if (sc == ''){
	    		$.messager.show({
					title:'KANBAN LABEL',
					msg:'SCAN KANBAN TIDAK BOLEH KOSONG',
					timeout:4000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
	    	}else{
		    	$.ajax({
		    		type: 'GET',
					url: 'cek_status.php?id_print='+split[8],
					success: function(data){
						if(data[0].kode=='BEFORE LABEL'){
							$.post('save_b_label.php',{
					    		id_prin: split[8],
								id_plan: split[7]
					    	},function(result){
								if(result.successMsg == 'BEFORE LABEL'){
									$.messager.show({
										title:'KANBAN LABEL',
										msg:'PROSES LABEL BERHASIL',
										timeout:4000,
										showType:'show',
										style:{
											middle:document.body.scrollTop+document.documentElement.scrollTop,
										}
									});
								}else{
				                    $.messager.show({
										title:'KANBAN LABEL',
										msg:result.errorMsg,
										timeout:4000,
										showType:'show',
										style:{
											middle:document.body.scrollTop+document.documentElement.scrollTop,
										}
									});
				                }
							},'json');
				    		document.getElementById('scan_lbl').focus();
						}else if(data[0].kode=='OUT'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PALLET MASIH DI HEATING ROOM',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}else if(data[0].kode=='EXISTS'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PALLET SUDAH DI SCAN SEBELUMNYA, SILAHKAN LANJUTKAN PROSES',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}else if(data[0].kode=='ASSEMBLY'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'PROSES ASSEMBLING BELUM SELESAI',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}
					}
		    	});
	    	}
	    }
	</script>
	</body>
</html>