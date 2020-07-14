<?php
include("../../connect/conn.php");
session_start();
if(isset($_SESSION['user_labelAfter'])){
	$user = $_SESSION['user_labelAfter'];
	$name = $_SESSION['name_labelAfter'];
	$shift = $_SESSION['shift_labelAfter'];
	$line = $_SESSION['line_labelAfter'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>KANBAN LABEL SYSTEM [OUT]</title>
<link rel="icon" type="../../image/png" href="../../plugins/font-awesome/css/font-awesome.min.css">
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
<body class="easyui-layout" >
	<div id="layout" 
		 data-options="region: 'north',
					   iconCls: 'icon-save',
					   title: 'OUT LABEL KANBAN SYSTEM',
					   collapsible:false,
					   modal: true,
					   tools: '#tt',
					   footer: '#footer'"
		 style="height:100%;padding:5px;">

		<!-- INFORMATION IN LABEL -->
		<fieldset style="border:1px solid #d0d0d0;border-radius:4px;width: 97.69%;height: auto;float:left;">
			<legend><span class="style3"><strong>OUT LABEL INFORMATION</strong></span></legend>
		    <div class="fitem" style="inline-block;float: right;padding: 5px 5px;">
				<span style="width:315px;height: 30px; display:inline-block; font-size: 16px; font-weight: bold;vertical-align: middle;">TOTAL BATTERY OUT <?php echo str_replace("-", "#", $line); ?> : </span>
				<input style="width:335px; height: 30px; border: 1px solid #0099FF;border-radius: 5px;font-size: 25px; background-color: #000000; color: #2AFF2A; text-align: right;" name="total_out" id="total_out" type="text" value="0" disabled="true" />
				<a id="savebtn_Plan" class="easyui-linkbutton c2" onClick="list_go_after_label()" style="width:75px; height: 34px;display:inline-block;vertical-align: top;"><i class="fa fa-bars" aria-hidden="true"></i>VIEW LIST</a>
			</div>
		    <div class="fitem">
				<div style="display: inline-block; float: left;">
					<span style="display:inline-block;font-weight: bold;">REPORT : </span>
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true" >REPORT 1</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true">REPORT 2</a>
					<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="print_po_sts_pdf()" iconCls="icon-pdf" plain="true" style="width:100px" disabled="true">REPORT 3</a>
				</div>
			</div>
		</fieldset>
		<!-- END -->

		<!-- TRANSACTION KANBAN LABEL -->
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97.6%; height: 85%;"><legend><span class="style3"><strong>KANBAN LABEL TRANSACTION</strong></span></legend>
			<div id="toolbar" style="padding:10px 10px;">
				<div class="fitem">
					<div class="fitem">
						<input style="width:550px; height: 46px; border: 1px solid #0099FF;border-radius: 5px;font-size: 25px;vertical-align: bottom;" 
							onkeypress="scan_kanbanL(event)" name="scan_kanbanL" id="scan_kanbanL" type="text" placeholder="SCAN KANBAN LABEL" 
							autofocus="autofocus" />
						<label><input type="checkbox" name="ck_tambahan" id="ck_tambahan" >TAMBAHAN</input></label>
						<!-- <span style="width:180px;height: 31px; display:inline-block;"></span> -->
						<a id="savebtn_Plan" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px; height: 46px;display:inline-block;vertical-align: middle;margin-left: 190px;"><i class="fa fa-save" aria-hidden="true"></i> FILTER DATA</a>
						<a id="savebtn_Plan" class="easyui-linkbutton c2" onClick="input_ng()" style="width:100px; height: 46px;display:inline-block;vertical-align: middle;"><i class="fa fa-save" aria-hidden="true"></i>INPUT NG</a>
						<a id="savebtn_Plan" class="easyui-linkbutton c2" onClick="list_wo()" style="width:200px; height: 46px;display:inline-block;vertical-align: middle;"><i class="fa fa-save" aria-hidden="true"></i> LIST PENGERJAAN WO</a>
						<div style="clear:both;margin-bottom:1px;"></div>
					</div>
				</div>
			</div>
			<div style="clear:both;margin-bottom:10px;"></div>
			<table id="dg_kanban_lbl" class="easyui-datagrid" toolbar="#toolbar" data-options="method: 'get', fitColumns: true, height: '95%', width: '100%'">
		        <thead>
	            <tr>
					<th halign="center" data-options="field:'WO_NO', width: 120">WO NO.</th>
					<th halign="center" data-options="field:'ITEM_NO', width: 50" align="center">ITEM NO.</th>
					<th halign="center" data-options="field:'BRAND', width: 250">DESCRIPTION</th>
					<th halign="center" data-options="field:'DATE_CODE', width: 45, align:'center'">DATE<BR>CODE</th>
					<th halign="center" data-options="field:'PLT_NO', width: 40" align="center">PALLET</th>
					<th halign="center" data-options="field:'STARTDATE', width: 100, align:'center'">START TIME</th>
					<th halign="center" data-options="field:'QTY_S', width: 70, align:'right'">QTY ORDER /<br>BATTERY IN</th>
					<th halign="center" data-options="field:'ENDDATE', width: 100" align="center">END TIME</th>
					<th halign="center" data-options="field:'DELETE', width: 70" align="center">DELETE WO</th>
	            </tr>
		        </thead>
		    </table>
		</fieldset>
		<!-- END -->

		<!-- END TIME KANBAN LABEL -->
		<div id="dlg_batt_in" class="easyui-dialog" style="width: 650px;height: auto;padding: 5px;" closed="true" buttons="#dlg-buttons-print-rec" data-options="modal:true">
		  <fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:auto; height:auto; padding: 5px;">
			<div class="fitem" hidden="true"><!-- hidden="true" -->
				<span style="width:120px;display:inline-block;font-size: 20px;">RECORD</span>
				<input style="width:150px;" name="id_record" id="id_record" class="easyui-textbox" disabled=""/>
			</div>
			<div class="fitem" hidden="true"><!-- hidden="true" -->
				<span style="width:120px;display:inline-block;">GRADE</span>
				<input style="width:150px;" name="grade_wo" id="grade_wo" class="easyui-textbox" disabled=""/>
			</div>
			<div class="fitem" hidden="true"><!-- hidden="true" -->
				<span style="width:120px;display:inline-block;">ID</span>
				<input style="width:150px;" name="id_kanban" id="id_kanban" class="easyui-textbox" disabled=""/>
			</div>
			<div class="fitem" hidden="true"><!-- hidden="true" -->
				<span style="width:120px;display:inline-block;">WO</span>
				<input style="width:150px;" name="wo" id="wo" class="easyui-textbox" disabled=""/>
			</div>
			<div class="fitem" hidden="true"><!-- hidden="true" -->
				<span style="width:120px;display:inline-block;">PLT NO.</span>
				<input style="width:150px;" name="plt_no" id="plt_no" class="easyui-textbox" disabled=""/>
			</div>
			<div class="fitem">
				<span style="width:200px;display:inline-block;font-size: 20px;">OUTPUT</span>
				<span style="width:200px;display:inline-block;font-size: 20px;">NG</span>
				<span style="width:200px;display:inline-block;font-size: 20px;">ORDER</span>
			</div>
			<div class="fitem">
				<input style="width:200px;height: 40px;text-align: right;font-size: 20px;" name="qty_result_batt_out" id="qty_result_batt_out" class="easyui-numberbox" 
					data-options="min:0,precision:0,groupSeparator:','" disabled=""/>
				<input style="width:200px;height: 40px;text-align: right;font-size: 20px;" name="qty_ng_batt_out" id="qty_ng_batt_out" class="easyui-numberbox" 
					data-options="min:0,precision:0, groupSeparator:','" align="right" value="0" />
				<input style="width:200px;height: 40px;text-align: right;font-size: 20px;" name="qty_result_batt_in" id="qty_result_batt_in" class="easyui-numberbox" 
					data-options="min:0,precision:0,groupSeparator:','"/>
			</div>
			<hr>
			<div class="fitem">
				<span style="width:200px;height: 40px;display:inline-block;">BOX TYPE</span>
				<select style="width:400px;height: 40px;" name="cmb_box_type" id="cmb_box_type" class="easyui-combobox" 
					data-options=" url:'json_boxType.php',method:'get',valueField:'box_type',textField:'box_type', panelHeight:'100px',size: '20px', 
						onSelect: function(rec){
							var lr = line.split('-');
							var l3 = rec.LR03;		var l6 = rec.LR6;
							var qact = $('#qty_result_batt_in').numberbox('getValue').replace(/,/g,'');

							if(lr[0] == 'LR03'){
								$('#set_box_qty').textbox('setValue',l3);
								var qact3 = qact/l3;
								var nonbox3 = qact - (parseInt(qact3) * l3);
								$('#box_qty_batt_in').textbox('setValue', parseInt(qact3));
								$('#non_box_qty_batt_in').textbox('setValue', parseInt(nonbox3));
							}else{
								$('#set_box_qty').textbox('setValue',l3);
								var qact6 = qact/l6;
								var nonbox6 = qact - (parseInt(qact6) * l6);
								$('#box_qty_batt_in').textbox('setValue', parseInt(qact6));
								$('#non_box_qty_batt_in').textbox('setValue', parseInt(nonbox6));
							}

							//$('#box_qty_batt_in').textbox('enable');
							//$('#non_box_qty_batt_in').textbox('enable');
						}"
				></select>
			</div>
			<div class="fitem" hidden="true">
				<span style="width:200px;height: 40px;display:inline-block;">BOX QTY</span>
				<input style="width:200px;height: 40px;" name="set_box_qty" id="set_box_qty" class="easyui-textbox" disabled=""/>
			</div>
			<div class="fitem">
				<span style="width:200px;height: 40px;display:inline-block;">BOX QTY</span>
				<input style="width:200px;height: 40px;" name="box_qty_batt_in" id="box_qty_batt_in" class="easyui-textbox" disabled=""/>
			</div>
			<div class="fitem">
				<span style="width:200px;height: 40px;display:inline-block;">NON-BOX QTY</span>
				<input style="width:200px;height: 40px;" name="non_box_qty_batt_in" id="non_box_qty_batt_in" class="easyui-textbox" disabled=""/>
			</div>
			<hr>
			<div align="center">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-save" onclick="save_batt_in()" style="width:90px; height: 40px; font-size: 20px;">SAVE</a>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-cancel" onclick="javascript:$('#dlg_batt_in').dialog('close')" style="width:90px; height: 40px; font-size: 20px;">CANCEL</a>
			</div>
		  </fieldset>
		</div>
		<!-- END  -->

		<!-- LIST PENGERJAAN WO -->
		<div id="dlg_list_in" class="easyui-dialog" style="width: 1150px;height: 500px;padding: 5px;" closed="true" buttons="#dlg-buttons-print-rec" data-options="modal:true">
			<div class="fitem">
				<span style="width:250px;display:inline-block;font-size: 25px;vertical-align: middle;">TANGGAL SCAN</span>
				<input style="width:120px;height: 33PX;vertical-align: middle;font-size: 20px;" name="date_scanL" id="date_scanL" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser, panelWidth: '300px', panelHeight: '300px'" value="<?date();?>"/>
				<span style="width:5px;display:inline-block;font-size: 25px;"></span>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-search" onclick="search_list_in()" style="width:90px; height: 33px;">CARI</a>
			</div>
			<table id="dg_list_wo" class="easyui-datagrid" data-options="method: 'get', fitColumns: true, height: '90%', width: 'auto'">
				<thead>
		            <tr>
						<th halign="center" data-options="field:'WORKER', width: 120">USER</th>
						<th halign="center" data-options="field:'MULAI', width: 70" align="center">TANGGAL</th>
						<th halign="center" data-options="field:'STARTDATE', width: 120">WAKTU SCAN</th>
						<th halign="center" data-options="field:'PLT_NO', width: 40, align:'center'">PALLET</th>
						<th halign="center" data-options="field:'LOTNUMBER', width: 120" align="center">LOT NUMBER</th>
						<th halign="center" data-options="field:'ASYLINE', width: 70, align:'center'">ASSY LINE</th>
						<th halign="center" data-options="field:'LABELLINE', width: 70, align:'center'">LABEL LINE</th>
						<th halign="center" data-options="field:'QTY', width: 100" align="right">QTY</th>
						<th halign="center" data-options="field:'WO_NO', width: 150" align="left">WO NO.</th>
						<th halign="center" data-options="field:'PLT_NO', width: 60" align="center">PALLET</th>
		            </tr>
		        </thead>
			</table>
		</div>
		<!-- END LIST PENGERJAAN WO -->

		<div id="dlg_filterData" class="easyui-dialog" style="width: 1100px;height: 500px;padding: 5px;" closed="true" buttons="#dlg-buttons-filterData" data-options="modal:true">
			<div class="fitem">
				<span style="width:100px;display:inline-block;">DATE SCAN</span>
				<input style="width:100px;" name="date_scan" id="date_scan" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<span style="width:50px;display:inline-block;"></span>
				<label><input type="checkbox" name="ck_selesai" id="ck_selesai" checked="true">BELUM SELESAI</input></label>
				<span style="width:50px;display:inline-block;"></span>
				<a href="javascript:void(0)" style="width: 150px;" id="add" class="easyui-linkbutton c2" onclick="searchData()"><i class="fa fa-search" aria-hidden="true"></i> SEARCH</a>
			</div>
			<table id="dg_list_filterData" class="easyui-datagrid" data-options="method: 'get', fitColumns: true, height: '90%', width: 'auto'">
				<thead>
	            <tr>
					<th halign="center" data-options="field:'WO_NO', width: 120">WO<br>NO.</th>
					<th halign="center" data-options="field:'ITEM_NO', width: 45" align="center">ITEM<br>NO.</th>
					<th halign="center" data-options="field:'BRAND', width: 225">DESCRIPTION</th>
					<th halign="center" data-options="field:'DATE_CODE', width: 45, align:'center'">DATE<BR>CODE</th>
					<th halign="center" data-options="field:'PLT_NO', width: 40" align="center">PALLET</th>
					<th halign="center" data-options="field:'STARTDATE', width: 80, align:'center'">START TIME</th>
					<th halign="center" data-options="field:'QTY_S', width: 70, align:'right'">QTY ORDER /<br>BATTERY IN</th>
					<th halign="center" data-options="field:'ENDDATE', width: 80" align="center">END TIME</th>
	            </tr>
		        </thead>
			</table>
		</div>

		<!-- START VIEW LIST TOTAL -->
		<div id="dlg_view_list" class="easyui-dialog" style="width: 950px;height: auto;padding: 5px;" closed="true" buttons="#dlg-buttons-print-rec" data-options="modal:true">
			<table id="dg_view_list" style="font-size: 28px;" class="easyui-datagrid" data-options="method: 'get', fitColumns: true, height: '500px', width: 'auto'">
				<thead>
	            <tr>
					<th halign="center" data-options="field:'LABELLINE', width: 100"><b>LABEL LINE</b></th>
					<th halign="center" data-options="field:'SHIFT', width: 50" align="center"><b>SHIFT</b></th>
					<th halign="center" data-options="field:'RECORDDATE', width: 150"><b>SCAN TIME</b></th>
					<th halign="center" data-options="field:'TANGGAL', width: 100, align:'center'"><b>DATE</b></th>
					<th halign="center" data-options="field:'ASY_LINE', width: 100" align="center"><b>ASSY LINE</b></th>
					<th halign="center" data-options="field:'GRADE', width: 50, align:'center'"><b>GRADE</b></th>
					<th halign="center" data-options="field:'TOTAL', width: 100, align:'right'"><b>TOTAL QTY</b></th>
					<th halign="center" data-options="field:'ACT', width: 80, align:'right'"><b>ACTION</b></th>
	            </tr>
		        </thead>
			</table>
			<hr>
			<div align="center">
				<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-cancel" onclick="javascript:$('#dlg_view_list').dialog('close')" style="width:90px">CANCEL</a>
			</div>
		</div>
		<!-- END VIEW LIST TOTAL -->

		<div style="clear:both;"></div>
		<div id="footer" style="padding:5px;" align="center">
	        <small><i>&copy; Copyright 2019, PT. FDK INDONESIA</i></small>
	    </div>
	</div>

	<div id="tt" style="height: 20px;">
		<span style="width:600px;display:inline-block;color: #FFFFFF;text-align: right;"><b>
			<?php echo 'USERNAME : '.$name.' ['.$user.'] - SHIFT '.$shift.' - '.str_replace("-", "#", $line); ?></b>
		</span>
		<span style="display:inline-block;color: #FFFFFF;">&nbsp;|&nbsp;</span>
		<a href="#"  class="easyui-linkbutton" plain="true" iconCls="icon-help"></a>
		<span style="display:inline-block;color: #FFFFFF;">&nbsp;|&nbsp;</span>
		<a href="#" style="width: 80px;text-decoration: none;color: #FFFFFF;" onclick="logOut();"><b>&nbsp;LOG OUT&nbsp;</b></a>
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

		var user = "<?=$user;?>";
		var name = "<?=$name;?>";
		var shift = "<?=$shift;?>";
		var line = "<?=$line;?>".replace("#","-");
		var sc = '';
		var scList = '';

		$(function(){
			total();
			content_kanbanL();
			var myVar = setInterval(function(){total()},5000);
		});

		function total(){
			$.ajax({
	    		type: 'GET',
				url: 'total.php?label='+line.replace("#","-"),
				data: { kode:'kode' },
				success: function(data){
					document.getElementById('total_out').value = data[0].TOTAL;
				}
			});
		}

		function list_go_after_label(){
			//console.log('list_view_detail_total.php?label='+line);
			var tot = document.getElementById('total_out').value;
			if(tot != 0){
				$('#dlg_view_list').dialog('open').dialog('setTitle','LIST RESULT BATTERY');
				$('#dg_view_list').datagrid( {
					url: 'list_view_detail_total.php?label='+line
				})
			}else{
				$.messager.show({
					title:'INFORMATION',
					msg:'DATA TIDAK ADA, OUTPUT BATTERY KOSONG',
					timeout:2000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
			}
		}

		function logOut(){
	    	$.messager.confirm('Confirm','Yakin Akan keluar dari System Kanban?',function(r){
				if(r){
					location.href='logout.php';
				}
			});
	    } 

		function content_kanbanL(){
			$('#dg_kanban_lbl').datagrid( {
				url: 'list_kanban_l.php?worker='+user
			});

			$('#dg_kanban_lbl').datagrid('enableFilter');
		}

		function scan_kanbanL(event){
	        if(event.keyCode == 13 || event.which == 13){
	            sc = document.getElementById('scan_kanbanL').value;
	            document.getElementById('scan_kanbanL').focus();
	            document.getElementById('scan_kanbanL').value = ''
	            proses();
	            content_kanbanL();
	        }
	    }

	    function proses(){
	    	var split = sc.split(" ");
	    	if (sc == ''){
	    		$.messager.show({
					title:'KANBAN LABEL',
					msg:'SCAN KANBAN LABEL TIDAK BOLEH KOSONG',
					timeout:4000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
				document.getElementById('scan_kanbanL').focus();
	    	}else{
	    		console.log('cek_status.php?id_kanbanL='+split[0].trim()+'&user='+user);
		    	$.ajax({
		    		type: 'GET',
					url: 'cek_status.php?id_kanbanL='+split[0].trim()+'&user='+user,
					success: function(data){
						if(data[0].kode=='YES'){
							$.post('save_a_label.php',{
					    		id_kanban: split[0].trim(),
								id_worker: user,
					    	},function(result){
								if(result.successMsg == 'success'){
									$.messager.show({
										title:'KANBAN LABEL',
										msg:'START KANBAN WO LABEL BERHASIL',
										timeout:4000,
										showType:'show',
										style:{
											middle:document.body.scrollTop+document.documentElement.scrollTop,
										}
									});
									content_kanbanL();
									$('#dg_kanban_lbl').datagrid('reload');
									document.getElementById('scan_kanbanL').focus();
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
						}else if (data[0].kode == 'DISCONNECT'){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'SESSION TELAH BERAKHIR<br>SILAHKAN LOGIN LAGI',
								timeout:2000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
							location.href='logout.php';
						}else{
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'<span style="font-size: 14px;">Data sudah pernah di masukan atau wo ini belum di selesaikan,<br>silahkan mencari di list yang sudah di sediakan</span>',
								timeout:5000,
								showType:'show',
								width: 500,
                             	height: 100,
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
						}
					}
		    	});
		    	document.getElementById('scan_kanbanL').focus();
	    	}
	    }

	    function filterData(){
	    	$('#dlg_filterData').dialog('open').dialog('setTitle','SEARCH DATA');
	    	$('#dg_list_filterData').datagrid('reload');
	    }

		function searchData(){
			var ckend = "false";

			if ($('#ck_selesai').attr("checked")) {
				ckend = "true";
			}

			var dt = $('#date_scan').datebox('getValue');
			
			console.log('list_filterData.php?date='+dt+'&ckend='+ckend);

			$('#dg_list_filterData').datagrid( {
				url: 'list_filterData.php?date='+dt+'&ckend='+ckend
			});

			$('#dg_list_filterData').datagrid('enableFilter');
		}

	    function input_ng(){
			var url = 'input_ng.php?line='+line;
			window.open(decodeURIComponent(url));
		}

	    function finish(a,b,c,d,e,f){
	    	console.log('cek_grade.php?id_kanbanL='+a+'&grade='+f+'&qty='+d);
	    	$.ajax({
	    		type: 'GET',
				url: 'cek_grade.php?id_kanbanL='+a+'&grade='+f+'&qty='+d,
				success: function(data){
					console.log(data[0].kode);
					if(data[0].kode=='YES'){
						console.log(data[0].qty);
						var tot_out = data[0].qty;
						$('#dlg_batt_in').dialog('open').dialog('setTitle','BATTERY FINISH LABEL SETTING');
						$('#box_qty_batt_in').textbox('disable');
						$('#non_box_qty_batt_in').textbox('disable');
						
						$('#qty_result_batt_out').numberbox('textbox').css('font-size','25px');
						$('#qty_ng_batt_out').numberbox('textbox').css('font-size','25px');
						$('#qty_result_batt_in').numberbox('textbox').css('font-size','25px');
						$('#cmb_box_type').combobox('textbox').css('font-size','25px', 'vertical-align', 'middle');
						$('#box_qty_batt_in').numberbox('textbox').css('font-size','25px', 'vertical-align', 'middle');
						$('#non_box_qty_batt_in').numberbox('textbox').css('font-size','25px', 'vertical-align', 'middle');

						$('#non_box_qty_batt_in').textbox('setValue',0);
				    	$('#id_record').textbox('setValue',e);
				    	$('#id_kanban').textbox('setValue',a);
				    	$('#wo').textbox('setValue',b);
				    	$('#plt_no').textbox('setValue',c);
				    	$('#cmb_box_type').combobox('setValue','');
				    	$('#box_qty_batt_in').textbox('setValue', '');
				    	$('#qty_result_batt_in').numberbox('setValue',d);
				    	$('#qty_result_batt_out').numberbox('setValue', tot_out);
				    	$('#grade_wo').textbox('setValue', f);
					}else if (data[0].kode == 'DISCONNECT'){
						$.messager.show({
							title:'KANBAN LABEL',
							msg:'SESSION TELAH BERAKHIR<br>SILAHKAN LOGIN LAGI',
							timeout:2000,
							showType:'show',
							style:{
								middle:document.body.scrollTop+document.documentElement.scrollTop,
							}
						});
						location.href='logout.php';
					}else{
						$.messager.show({
							title:'KANBAN LABEL',
							msg:'<span style="font-size: 14px;">BATTERY OUT LABEL '+line.replace("-","#")+' TIDAK CUKUP UNTUK GRADE '+f+'</span>',
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

	    function save_batt_in(){
	    	/*	1 cari total qty status=0, remark=result
				2 looping selama qty wo <= 0 dari qty di no1
	    		3 selama looping insert ztb_lbl_trans_det dan ztb_lbl_trans(qty_terpakai)
	    		4 selai looping lalu update ztb_kanban_lbl (asyline,z_qty,labelline,enddate,battery_IN,LOTNUMBER,BOXTYPE,QTY,QTYSISAKOTAK,QTYSISAPRODUKSI,)	*/
	    	var dataRows = [];
	    	var out = parseInt(document.getElementById('total_out').value.replace(/,/g,''));
	    	var a = $('#cmb_box_type').combobox('getValue');
	    	var b = parseInt($('#qty_result_batt_in').numberbox('getValue'));
	    	var c = $('#non_box_qty_batt_in').textbox('getValue');
	    	var d = $('#id_kanban').textbox('getValue');
	    	var e = $('#wo').textbox('getValue');
	    	var f = $('#plt_no').textbox('getValue');
	    	var g = $('#cmb_box_type').combobox('getValue');
	    	var h = $('#box_qty_batt_in').textbox('getValue');
	    	var i = $('#id_record').textbox('getValue');
	    	var j = $('#set_box_qty').textbox('getValue');
	    	var k = $('#grade_wo').textbox('getValue');
	    	var l = (h * j) + parseInt(c);
	    	var m = $('#qty_ng_batt_out').numberbox('getValue');

	    	if(a == ''){
	    		$.messager.show({
					title:'KANBAN LABEL',
					msg:'BOX TYPE TIDAK BOLEH KOSONG',
					timeout:4000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
	    	}else if(b>out){
	    		$.messager.show({
					title:'KANBAN LABEL',
					msg:'TOTAL BATTERY OUT TIDAK CUKUP',
					timeout:4000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
	    	}else{
		    	dataRows.push({
					id_kanban: d,
					wo_no: e,
					plt_no:f,
					qty_prod:b,
					box_type:g.toUpperCase(),
					box_qty: h,
					non_box_qty: c,
					id_record: i,
					set_box_qty: j,
					grade: k,
					qty_in_act: l,
					qty_ng: m
				});

				var myJSON=JSON.stringify(dataRows);
				var str_unescape=unescape(myJSON);

				console.log('save_detail_semi.php?data='+str_unescape);

				$.post('save_detail_semi.php',{
					data: unescape(str_unescape)
				}).done(function(res){
					console.log(res);
					if(res == '"success"'){
						$('#dg_kanban_lbl').datagrid('reload');
						$.messager.alert('INFORMATION','Finish Success..!!<br/>WO No. '+e+' and pallet no.'+f,'info');
						$('#dlg_batt_in').dialog('close');
					}else{
						$.messager.alert('ERROR',res,'warning');
					}
				});
			}
	    }

	    function deleteRow(a,b,c,d,e){
	    	$.messager.confirm('Confirm','Apakah anda ingin menghapus WO ini sekarang?',function(r){
				if(r){
					//console.log('delete_wo.php?id_kanbanL='+a+'&worker='+user+'&rowid='+e);
					$.post('delete_wo.php',{id_kanbanL: a,worker: user, rowid: e},function(result){
						if (result.success){
							$.messager.show({
								title:'KANBAN LABEL',
								msg:'DELETE WO BERHASIL',
								timeout:4000,
								showType:'show',
								style:{
									middle:document.body.scrollTop+document.documentElement.scrollTop,
								}
							});
                        }else{
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
					},'json');

					content_kanbanL();
					$('#dg_kanban_lbl').datagrid('reload');
				}
			});
	    }

	    function list_wo(){
	    	$('#dlg_list_in').dialog('open').dialog('setTitle','LIST PENGERJAAN WO');
	    }

	    function search_list_in(){
	    	var dt_scan_list = $('#date_scanL').datebox('getValue');
	    	content_kanbanList_wo(dt_scan_list);
	    }


	    /*function scan_kanbanList_wo(event){
	        if(event.keyCode == 13 || event.which == 13){
	            scList = document.getElementById('scan_kanbanList_wo').value;
	            document.getElementById('scan_kanbanList_wo').focus();
	            document.getElementById('scan_kanbanList_wo').value = ''
	            prosesList(scList);
	        }
	    }*/

	    function content_kanbanList_wo(a){
			$('#dg_list_wo').datagrid( {
				url: 'list_kanban_list_wo.php?wo='+a
			});

			$('#dg_list_wo').datagrid('enableFilter');
		}

	    /*function prosesList(a){
	    	if (a == ''){
	    		$.messager.show({
					title:'KANBAN LABEL',
					msg:'SCAN KANBAN LABEL TIDAK BOLEH KOSONG',
					timeout:4000,
					showType:'show',
					style:{
						middle:document.body.scrollTop+document.documentElement.scrollTop,
					}
				});
				document.getElementById('scan_kanbanList_wo').focus();
	    	}else{
		    	content_kanbanList_wo(scList);
		    	document.getElementById('scan_kanbanL').focus();
	    	}
	    }*/

	</script>
	</body>
</html>
<?php
}else{
	header('location:index.php');
}
?>