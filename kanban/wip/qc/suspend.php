<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>QC SUSPEND KANBAN SYSTEM</title>
    <link rel="icon" type="../image/png" href="../../../favicon.png">
  	<link rel="stylesheet" type="text/css" href="../../../plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../../../themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../../themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../../themes/color.css" />
    <script type="text/javascript" src="../../../js/jquery-1.8.3.js"></script>
    <script type="text/javascript" src="../../../js/jquery.easyui.min.js"></script>
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
		<div id="p" class="easyui-panel" title="SUSPEND KANBAN SYSTEM" data-options="iconCls:'icon-save', tools:'#tt', footer:'#footer'" style="width:100%;height:655px;padding:5px;">
			<fieldset style="float: left;margin-left:5px;border-radius:4px;width: 43%;height: 570px;"><legend><span class="style3"><strong>SUSPEND TRANSACTION</strong></span></legend>
				<div class="fitem" style="padding: 5px;">
					<span style="width:25%;display:inline-block;font-size: 14px;font-weight: bold;">SCAN KANBAN ASSY</span> 
					<input style="width:375px; height: 40px; border: 1px solid #0099FF;border-radius: 5px;font-size: 30px;" 
						onkeypress="scan_assy(event)" name="scn_assy" id="scn_assy" type="text" 
						autofocus="autofocus" />
				</div>
				<br/>
				<div class="fitem" style="padding: 5px;">
					<span style="width:15%;display:inline-block;font-weight: bold;">ASSY LINE</span>
					<span style="width:15%;display:inline-block;font-weight: bold;">CELL TYPE</span>
					<span style="width:20%;display:inline-block;font-weight: bold;">PALLET</span>
					<span style="width:20%;display:inline-block;font-weight: bold;">SUSPEND DATE</span>
				</div>
				<div>
					<input style="width:15%;height: 30px;" name="assy_line" id="assy_line" class="easyui-textbox" disabled=""/>
					<input style="width:15%;height: 30px;" name="cell_type" id="cell_type" class="easyui-textbox" disabled=""/>
					<input style="width:20%;height: 30px;" name="pallet" id="pallet" class="easyui-textbox" disabled=""/>
					<input style="width:20%;height: 30px;" name="suspend_date" id="suspend_date" class="easyui-datebox" data-options="formatter:myformatter, parser:myparser, panelWidth: '350px'" value="date()" />
				</div>
				<br/>
				 <div class="fitem" style="padding: 5px;">
					<span style="width:30%;display:inline-block;font-weight: bold;">SEMI BATTERY STOCK</span>
					<span style="width:25%;display:inline-block;font-weight: bold;">PROBLEM</span>
				</div>
				<div class="fitem" style="padding: 5px;">
					<input style="width:30%;height: 30px;" name="assy_line" id="assy_line" class="easyui-textbox" disabled=""/>
					<input style="width:63%;height: 30px;" name="assy_line" id="assy_line" class="easyui-combobox"/>
				</div>
				<br/>
				 <div class="fitem" style="padding: 5px;">
					<span style="width:30%;display:inline-block;font-weight: bold;">LOT NUMER</span>
					<span style="width:25%;display:inline-block;font-weight: bold;">REASON</span>
				</div>
				<div class="fitem" style="padding: 5px;">
					<input style="width:30%;height: 30px;" name="assy_line" id="assy_line" class="easyui-textbox" disabled=""/>
					<input style="width: 343px; height: 56px;" name="shipp_mark_add" id="shipp_mark_add"  multiline="true" class="easyui-textbox" data-options="validType:'length[0,299]'"/>
				</div>
				<br/>
				<fieldset style="width: 88%;border-radius: 4px;">
					<div align="center" class="fitem" style="padding: 5px;">
						<span style="width:30%;display:inline-block;font-weight: bold;">AGING QTY</span>
						<span style="width:30%;display:inline-block;font-weight: bold;">LABEL QTY</span>
						<span style="width:30%;display:inline-block;font-weight: bold;">AFTER QTY</span>
					</div>
					<div align="center" class="fitem" style="padding: 5px;">
						<input style="width:30%;height: 30px;" name="aging_qty" id="aging_qty" class="easyui-numberbox"/>
						<input style="width:30%;height: 30px;" name="label_qty" id="label_qty" class="easyui-numberbox"/>
						<input style="width:30%;height: 30px;" name="after_qty" id="after_qty" class="easyui-numberbox"/>
					</div>	
				</fieldset>
				<br/><hr><br/>
				<div class="fitem" style="padding: 5px;" align="center">
					<a id="savebtn_Plan" class="easyui-linkbutton c2" onClick="proses()" style="width:180px; height: 41px;display:inline-block;"><i class="fa fa-save" aria-hidden="true"></i> SUSPEND</a>
					<a id="savebtn_Plan" class="easyui-linkbutton c5" onClick="cancel()" style="width:180px; height: 41px;display:inline-block;"><i class="fa fa-trash" aria-hidden="true"></i> CANCEL</a>
				</div>
				<br/><hr>
				<span style="display:inline-block;font-weight: bold;">REPORT : </span>
				<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="">REPORT 1</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="">REPORT 2</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="">REPORT 3</a>
			</fieldset>
			<fieldset style="position:absolute;margin-left:44%;border-radius:4px;width: 50%;height: 570px;"><legend><span class="style3"><strong>LIST OF SUSPENDED BATTERY</strong></span></legend>
			    <table id="dg" class="easyui-datagrid" data-options="rownumbers: true, singleSelect: true, iconCls: 'icon-save', method: 'get', showFooter: true, fitColumns: true">
			        <thead>
			            <tr>
							<th halign="center" data-options="field:'ID_PRINT',width:100">PRINT</th>
							<th halign="center" data-options="field:'CELL_TYPE',width:100">GRADE</th>
							<th halign="center" data-options="field:'ASSY_LINE',width:100">TYPE<br/>BATTERY</th>
							<th halign="center" data-options="field:'LOT_DATE',width:100">LOT<br/>NUMBER</th>
							<th halign="center" data-options="field:'SUSPEND_DATE_1',width:100">SUSPEND<br/>DATE</th>
							<th halign="center" data-options="field:'QTY_SUSPEND',width:100, align:'right'">SUSPEND<br/>QTY</th>
							<th halign="center" data-options="field:'STATUS',width:100">STATUS</th>
							<th halign="center" data-options="field:'ACTION',width:100" align="center">DETAIL</th>
			            </tr>
			        </thead>
			    </table>
			</fieldset>

			<!-- START VIEW DETAILS-->
			<div id='dlg_viewDetails' class="easyui-dialog" style="width:1050px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_Dtl" data-options="modal:true">
			</div>
			<!-- END VIEW DETAILS-->

			<div style="clear:both;"></div>
			<div id="footer" style="padding:5px;" align="center">
		        <small><i>&copy; Copyright 2019, PT. FDK INDONESIA</i></small>
		    </div>
		</div>

		<script type="text/javascript">
			var sc = '';
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

			function content_list(){
				$('#dg').datagrid( {
					url: 'list_suspend.php'
				});
			}

			$(function(){
				var myVar = setInterval(function(){content_list()},10000);
			})

			function scan_assy(event){
		        if(event.keyCode == 13 || event.which == 13){
		            sc = document.getElementById('scn_assy').value;
		            document.getElementById('scn_assy').focus();
		            document.getElementById('scn_assy').value = ''
		            proses();
		        }
		    }

		    function proses(){
		    	var split = sc.split(",");

		    	if (sc == ''){
		    		$.messager.alert('INFORMATION','SCAN KANBAN ASSEMBLY TIDAK BOLEH KOSONG','info');
		    	}else{
		    		$('#assy_line').textbox('setValue',split[1]);
		    		$('#cell_type').textbox('setValue',split[2]);
		    		$.ajax({
						type: 'GET',
						url: 'cek_suspend.php?id_print='+split1[8],
						success: function(data){

						}
					});
		    	}
		    }

		    function view_details(a){
		    	$('#dlg_viewDetails').dialog('open').dialog('setTitle','VIEW INFO DETAILS ('+a+')');
		    }
		</script>
 	</body>
	</html>