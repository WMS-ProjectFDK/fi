<?php
require("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>KANBAN PRINT</title>
<link rel="icon" type="image/png" href="../favicon.png">
<script language="javascript">
	function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
	}
</script> 
<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../themes/color.css" />

<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.patch.js"></script>
<script type="text/javascript" src="../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
<script type="text/javascript" src="../js/canvasjs.min.js"></script>
<script type="text/javascript" src="../js/datagrid-export.js"></script>

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
	.datagrid-row-alt{
		background: #F8F8F8;
	}
</style>
</head>

<body>
<?php include ('../ico_logout.php'); ?>

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:900px;border-radius:4px;height: 110px;"><legend><span class="style3"><strong>Kanban Print Filter</strong></span></legend>
		<div style="width:450px;float:left">
			<div class="fitem">
				<span style="width:135px;display:inline-block;">Cargo Ready Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			  		to   
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				<label><input type="checkbox" name="ck_cr_date" id="ck_cr_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:135px;display:inline-block;">WO No.</span>
				<select style="width:190px;" name="cmb_wo_no" id="cmb_wo_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'work_order',textField:'work_order', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_wo_no" id="ck_wo_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
				<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="otherPrint()" style="width:170px;"><i class="fa fa-print" aria-hidden="true"></i> Other Print</a>
			</div>
		</div>
		<div style="width:450px;float:left">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PO No.</span>
				<select style="width:300px;" name="cmb_po_no" id="cmb_po_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_po_no" id="ck_po_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Item No.</span>
				<select style="width:300px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'item_no',textField:'item_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="margin-left:910px;border-radius:4px;width: 300px;height: 110px;"><legend><span class="style3"><strong>Select Print</strong></span></legend>
		<div align="center">
		<div class="fitem">
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="printlabel()" style="width:200px;"><i class="fa fa-print" aria-hidden="true"></i> Kanban Label</a>
		</div>
		<div class="fitem">
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="printmaterial()" style="width:200px;"><i class="fa fa-print" aria-hidden="true"></i> Kanban Material</a>
		</div>
		<div class="fitem">
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="printpacking()" style="width:200px;"><i class="fa fa-print" aria-hidden="true"></i> Kanban Packing</a>
		</div>
		</div>
		</div>
	</fieldset>
</div>

<table id="dg" title="KANBAN PRINT" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" selectOnCheck= "true"></table>

<div id='dlg_header' class="easyui-dialog" style="width:400px;height:520px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">
	<fieldset style="margin-left;border-radius:4px;height:425px;width:90%"><legend><span class="style3"><strong> MPS Header Edit </strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">Work Order No : </span>
			<input style="width:200px;" name="wo_no_edit" id="wo_no_edit" class="easyui-textbox"  required="true"/> 
		</div>
		<div class="fitem">		  
			<span style="width:150px;display:inline-block;">PO No. :</span>
			<input style="width:200px;" name="po_no_edit" id="po_no_edit" class="easyui-textbox" required="true"/> 	
		</div>
		<div class="fitem">
			<span style="width:250px;display:inline-block;">PO LINE No. :</span>
			<input style="width:100px;" name="po_line_no_edit" id="po_line_no_edit" class="easyui-textbox"  required="true"/> 	
		</div>
		<div class="fitem">
			<span style="width:250px;display:inline-block;">Item No. :</span>
			<input style="width:100px;" name="item_no_edit" id="item_no_edit" class="easyui-textbox"  required="true"/> 	
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">Qty Order. :</span>
			<input style="width:200px;" name="qty_edit" id="qty_edit" class="easyui-textbox"  required="true"/> 	
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">Date Code :</span>
			<input style="width:200px;" name="date_code_edit" id="date_code_edit" class="easyui-textbox"  required="true"/> 	
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">Cargo Ready. :</span>
			<input style="width:200px;" name="cr_date_date" id="cr_date_date" data-options="formatter:myformatter,parser:myparser" class="easyui-datebox"  required="true" /> 	
		</div>
		<div class="fitem">
			<span style="width:350px;display:inline-block;">Status :</span>
			<select id="status_box" class="easyui-combobox" name="status_box" style="width:125;" required="true">
				<option value="FM">FM</option>
				<option value="O/F">O/F</option>
				<option value="INQ">INQ</option>
			</select>	
		</div>
		<div class="fitem">
			<span style="width:350px;display:inline-block;">BOM Level :</span>
			<input style="width:50px;" name="bom_level_edit" id="bom_level_edit" class="easyui-textbox"  required="true"/>
			<label><input type="checkbox" name="ck_edit_bom" id="ck_edit_bom">Edit</input></label>
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="openbom()" style="width:50px;"><i class="" aria-hidden="true"></i> view</a>
		</div>
	</fieldset>		
	<div class="fitem" style="margin-right">
		<span style="width:100px;display:inline-block;"></span>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="saveheader()" style="width:120px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
		<span style="width:10px;display:inline-block;"></span>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="$('#dlg_header').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>
	<div >
</div>

<!-- OTHER PRINT -->
<div id="dlg_otherPrint" class="easyui-dialog" style="width: 330px;height: auto;" closed="true" data-options="modal:true">
	<div class="fitem" id="print_type">
		<INPUT TYPE='radio' NAME='sheet_type' id='invoice' VALUE='invoice' checked/> CASE MARK<br/>
		<INPUT TYPE='radio' NAME='sheet_type' id='packing_list' VALUE='packing_list'/> PALLET MARK<br/>
	</div>
	<div class="fitem" align="center">
		<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="printOther" onclick="printOther()"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
	</div>
</div>
<!-- END -->

<div id='dlg_detail' class="easyui-dialog" style="width:450px;height:480px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">
	<table id="dg_detail" class="easyui-datagrid" style="width:99%;height:90%;">  </table>	
	<span style="width:380px;display:inline-block;"></span>
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="savedetail()" style="width:120px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>	
	<span style="width:20px;display:inline-block;"></span>
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="addrowdetail()" style="width:120px;"><i class="fa fa-next" aria-hidden="true"></i> Add  Row</a>	
	<span style="width:20px;display:inline-block;"></span>
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="$('#dlg_detail').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>	
</div>

<div id='dlg_packing' class="easyui-dialog" style="width:43%;height:500px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">
	<span style="width:400px;display:inline-block;"></span>
	<table id="dg_packing" class="easyui-datagrid" style="width:99%;height:85%;">  </table>	
	<!-- <span style="width:380px;display:inline-block;"></span> -->
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="printpackingnow()" style="width:120px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>	
	<span style="width:350px;display:inline-block;"></span>	
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="$('#dlg_packing').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>
</div>

<div id='dlg_label' class="easyui-dialog" style="width:43%;height:500px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">
	<span style="width:100px;display:inline-block;">Label Machine: </span>
	<select id="machine_box" class="easyui-combobox" name="machine_box" style="width:425;" required="true">
			<option value="" selected="selected"></option>
			<option value="LR03#1">LR03#1</option>
			<option value="LR03#2">LR03#2</option>
			<option value="LR6#1">LR6#1</option>
			<option value="LR6#2">LR6#2</option>
			<option value="LR6#3">LR6#3</option>
			<option value="LR6#4">LR6#4</option>
			<option value="LR6#5">LR6#5</option>
			<option value="LR6#6">LR6#6</option>
		</select>	
	<table id="dg_label" class="easyui-datagrid" style="width:99%;height:85%;">  </table>
		
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="printlabelnow()" style="width:120px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>	
	
	<span style="width:250px;display:inline-block;"></span>
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="$('#dlg_label').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>
</div>

<div id='dlg_material' class="easyui-dialog" style="width:53%;height:500px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">
	<span style="width:80px;display:inline-block;">Pallet No : </span>
	<input style="width:50px;" name="plt_mtr_start_edit" id="plt_mtr_start_edit" class="easyui-numberbox"  required="true"/>
	<span style="width:20px;display:inline-block;">to</span>
	<input style="width:50px;" name="plt_mtr_end_edit" id="plt_mtr_end_edit" class="easyui-numberbox"  required="true"/>
	<a href="javascript:void(0)" id="kalkulasi_button" class="easyui-linkbutton c2" onClick="kalkulasi()" style="width:120px;"><i class="fa fa-process" aria-hidden="true"></i> Calculate</a>	
	<span style="width:400px;display:inline-block;color:red;font-weight:bold" id="loading_span"></span>
	
	<table id="dg_material" class="easyui-datagrid" style="width:99%;height:85%;">  </table>	
	<!-- <span style="width:380px;display:inline-block;"></span> -->
	<a href="javascript:void(0)" id="printmaterial_button" class="easyui-linkbutton c2" onClick="printmaterialnow()" style="width:120px;"><i class="fa fa-print" aria-hidden="true"></i> Print</a>	
	<span style="width:450px;display:inline-block;"></span>
	<a href="javascript:void(0)" id="exitmaterial_button" class="easyui-linkbutton c2" onClick="$('#dlg_material').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>	
</div>

<script type="text/javascript">
	function otherPrint(){
		var row = $('#dg').datagrid('getSelected');
		if (row){
			$('#dlg_otherPrint').dialog('open').dialog('setTitle','Print Properties ('+row.WORK_ORDER+')');	
		}else{
			$.messager.show({
				title: 'KANBAN PRINT',
				msg: 'Data Not Found'
			});
		}
	}

	function printmaterialnow(){
		var flag = 0;
		var rows = $('#dg_material').datagrid('getRows');
		for(var i=0; i<rows.length; i++){
			if(rows[i]['RSTS'] == 'STOCK SHORTAGE'){
				flag++;	
			}
		}
		
		if(flag==0){
			var dataRows = [];
			var plt_start = parseInt($('#plt_mtr_start_edit').textbox('getValue'));
			var plt_end = parseInt($('#plt_mtr_end_edit').textbox('getValue'));
			var pallet = "";
			var qty = 0;
			var rows1 = $('#dg').datagrid('getSelections');
			var wo_no = rows1[0].WORK_ORDER;
			var item_no = rows1[0].ITEM_NO;
			var brand = rows1[0].ITEM_NAME;
			var date_code = rows1[0].DATE_CODE;
			var item_type = rows1[0].BATERY_TYPE;
			var grade = rows1[0].CELL_GRADE;
			var qty_order = rows1[0].QTY;
			var cr_date = rows1[0].CR_DATE;
			var qty_pallet = rows1[0].QTY_PALLET;
			var qty_prod = 0;
			var plt_tot = rows1[0].TOTALPALLET;
			var totalpallet = parseInt(rows1[0].TOTALPALLET);

			if(parseInt(plt_start) <= parseInt(plt_end) && parseInt(plt_start) > 0 &&  parseInt(plt_end) > 0 
				&& parseInt(plt_end) <= totalpallet && parseInt(plt_start) <= totalpallet){
				while(plt_start <= plt_end){
					if(parseInt(plt_start) != parseInt(totalpallet)){
						qty = parseInt(rows1[0].QTY_PALLET);
					}else{
						qty = parseInt(rows1[0].QTYPLTAKHIR);
					}

					dataRows.push ({
						wo_no: wo_no,
						item_no: item_no,
						brand: brand,
						date_code: date_code,
						item_type: item_type,
						grade: grade,
						qty_order: qty_order,
						cr_date: cr_date,
						qty_pallet: qty_pallet,
						qty: qty,
						plt_no: plt_start,
						plt_tot: plt_tot
					});

					plt_start++;
				}

				var myJSON=JSON.stringify(dataRows);
				var str_unescape=unescape(myJSON);
				
				$.post('kanban_print_material.php',{
					data: unescape(str_unescape)
				}).done(function(res){
					if(res == '"success"'){
						$('#dlg_material').dialog('close');
						// $('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Print Material Kanban Success','info');
						window.open("kanban_print_material_pack_pdf.php");
						$.messager.progress('close');
					}else{
						$.messager.alert('ERROR',res,'warning');
						$.messager.progress('close');
					}
				});
			}else{
				$.messager.alert('ERROR','Pengisian nomor pallet salah','warning');	
			}
		}else{
			$.messager.alert('ERROR','STOCK SHORTAGE','warning');
		}
	}

	function printpackingnow(){
		var rows = $('#dg_packing').datagrid('getSelections');
		var rows1 = $('#dg').datagrid('getSelections');
		var wo_no = rows1[0].WORK_ORDER;
		var item_no = rows1[0].ITEM_NO;
		var brand = rows1[0].ITEM_NAME;
		var date_code = rows1[0].DATE_CODE;
		var item_type = rows1[0].BATERY_TYPE;
		var grade = rows1[0].CELL_GRADE;
		var qty_order = rows1[0].QTY;
		var cr_date = rows1[0].CR_DATE;
		var qty_pallet = rows1[0].QTY_PALLET;
		var qty_prod = 0;
		var plt_tot = rows1[0].TOTALPALLET;
		var dataRows = [];

		if(rows.length > 0){
			for(i=0;i<rows.length;i++){
			    if(rows[i].PALLET == plt_tot){
					qty_prod = rows1[0].QTYPLTAKHIR
				}else{
					qty_prod = rows1[0].QTY_PALLET
				}
				dataRows.push ({
					wo_no: wo_no,
					item_no: item_no,
					brand: brand,
					date_code: date_code,
					item_type: item_type,
					grade: grade,
					qty_order: qty_order,
					cr_date: cr_date,
					qty_pallet: qty_pallet,
					qty_prod: qty_prod,
					plt_no: rows[i].PALLET,
					plt_tot: plt_tot
				});
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);

			$.post('kanban_print_packing.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_packing').dialog('close');
					// $('#dlg_packing').datagrid('reload');
					$.messager.alert('INFORMATION','Print Packing Kanban Success','info');
					window.open("kanban_print_packaging_pdf.php");
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}else{
			$.messager.alert('INFORMATION','Please select the pallet number first.','info');		
		}
	}

	function printlabelnow(){
		var rows = $('#dg_label').datagrid('getSelections');
		var rows1 = $('#dg').datagrid('getSelections');
		var wo_no = rows1[0].WORK_ORDER;
		var item_no = rows1[0].ITEM_NO;
		var brand = rows1[0].ITEM_NAME;
		var date_code = rows1[0].DATE_CODE;
		var item_type = rows1[0].BATERY_TYPE;
		var grade = rows1[0].CELL_GRADE;
		var qty_order = rows1[0].QTY;
		var cr_date = rows1[0].CR_DATE;
		var qty_pallet = rows1[0].QTY_PALLET;
		var qty_prod = 0;
		var plt_tot = rows1[0].TOTALPALLET;
		var dataRows = [];

		if(rows.length > 0){
				for(i=0;i<rows.length;i++){
					if(rows[i].PALLET == plt_tot){
						qty_prod = rows1[0].QTYPLTAKHIR
					}else{
						qty_prod = rows1[0].QTY_PALLET
					}
					dataRows.push ({
						wo_no: wo_no,
						item_no: item_no,
						brand: brand,
						date_code: date_code,
						item_type: item_type,
						grade: grade,
						qty_order: qty_order,
						cr_date: cr_date,
						qty_pallet: qty_pallet,
						qty_prod: qty_prod,
						plt_no: rows[i].PALLET,
						plt_tot: plt_tot
					});
				}

				var myJSON=JSON.stringify(dataRows);
				var str_unescape=unescape(myJSON);

				if($('#machine_box').textbox('getValue')==''){
					$.messager.alert('Warning','Please fill the label machine.','warning');
				}else{
					$.post('kanban_print_label.php',{
						data: unescape(str_unescape)
					}).done(function(res){
						if(res == '"success"'){
							$.messager.alert('INFORMATION','Print Label Kanban Success','info');
							window.open('kanban_print_label_pdf.php?machine='+$('#machine_box').textbox('getValue'));
							$.messager.progress('close');
						}else{
							$.messager.alert('ERROR',res,'warning');
							$.messager.progress('close');
						}
					});
				}
				$('#dlg_label').dialog('close')	
		}else{
			$.messager.alert('INFORMATION','Please select the pallet number first.','info');
		}
	}

	function wait(ms){
	   var start = new Date().getTime();
	   var end = start;
	   while(end < start + ms) {
	    	end = new Date().getTime();
	  	}
	}

	function printmaterial(){
		$('#dg_material').datagrid('loadData', []);
		$('#plt_mtr_start_edit').textbox('setValue','');
	    $('#plt_mtr_end_edit').textbox('setValue','');
		var rows1 = $('#dg').datagrid('getSelections');
		if(rows1[0].WORK_ORDER == ""){
				
		}else{
			$('#dlg_material').dialog('open').dialog('setTitle','Print Material Kanban of WO ('+rows1[0].WORK_ORDER+') Total pallet  ('+rows1[0].TOTALPALLET+')  ');
		}
	}

	function kalkulasi(){
		var plt_start = parseInt($('#plt_mtr_start_edit').textbox('getValue'));
		var plt_end = parseInt($('#plt_mtr_end_edit').textbox('getValue'));
		var pallet = "";
		var qty = 0;
		var rows1 = $('#dg').datagrid('getSelections');
		var totalpallet = parseInt(rows1[0].TOTALPALLET);
		if(parseInt(plt_start) <= parseInt(plt_end) && parseInt(plt_start) > 0 &&  parseInt(plt_end) > 0 
			&& parseInt(plt_end) <= totalpallet && parseInt(plt_start) <= totalpallet){
			while(plt_start <= plt_end){
				if(parseInt(plt_start) != parseInt(totalpallet)){
					qty = qty + parseInt(rows1[0].QTY_PALLET);
				}else{
					qty = qty + parseInt(rows1[0].QTYPLTAKHIR);
				}

				if(parseInt(plt_start) != parseInt(plt_end)){
					pallet = pallet + plt_start+',';
				}else{
					pallet = pallet + plt_start+'';
				}
				plt_start++;
			}
			
			document.getElementById("loading_span").innerHTML = 'Loading max 1 minute...';
			document.getElementById('printmaterial_button').style.visibility = 'hidden';
			document.getElementById('exitmaterial_button').style.visibility = 'hidden';
			document.getElementById('kalkulasi_button').style.visibility = 'hidden';

			$.ajax({
				type: 'GET',
				url: 'json/json_cek_kanban_material.php?wo_no='+rows1[0].WORK_ORDER+'&pallet='+pallet,
				data: { kode:'kode' },
				success: function(data){
					qty = qty - data[0].QTY;
					url_print = 'kanban_print_material_get.php?wo_no='+rows1[0].WORK_ORDER+'&qty='+qty;
							
					if(qty == 0){
						$.messager.alert('Warning','Pallet sudah di print semua','warning');
						
					}else{
						if(data[0].PLT_NO != ""){
							$.messager.alert('Warning','Pallet no '+ data[0].PLT_NO +' ini sudah di print','warning');
							$('#dg_material').datagrid({url: url_print,});
							
						}else{
							$('#dg_material').datagrid({url: url_print,});
						}
					}
					document.getElementById('printmaterial_button').style.visibility = 'visible';
					document.getElementById('exitmaterial_button').style.visibility = 'visible';
					document.getElementById('kalkulasi_button').style.visibility = 'visible';
					document.getElementById("loading_span").innerHTML = '';
				}
			});
		}else{
			$.messager.alert('ERROR','Pengisian nomor pallet salah','warning');	
		}
	}

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
	    	
	$(function(){
		$('#add_shipping_plan').linkbutton('disable');
		$('#date_awal').datebox('disable');
		$('#date_akhir').datebox('disable');
		$('#ck_cr_date').change(function(){
			if ($(this).is(':checked')) {
				$('#date_awal').datebox('disable');
				$('#date_akhir').datebox('disable');
			}else{
				$('#date_awal').datebox('enable');
				$('#date_akhir').datebox('enable');
			}
		});

		$('#cmb_wo_no').combobox('disable');
		$('#ck_wo_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_wo_no').combobox('disable');
			}else{
				$('#cmb_wo_no').combobox('enable');
			}
		});

		$('#cmb_po_no').combobox('disable');
		$('#ck_po_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_po_no').combobox('disable');
			}else{
				$('#cmb_po_no').combobox('enable');
			}
		});

		$('#cmb_item_no').combobox('disable');
		$('#ck_item_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_item_no').combobox('disable');
			}else{
				$('#cmb_item_no').combobox('enable');
			}
		});

		$('#dg_packing').datagrid( {
			loadMsg:'load data max 1 minute...',
			rownumbers :true,
			columns:[[
				{field:'WORK_ORDER',title:'WO NO',width:180,halign:'center', align:'center',hidden: false},
				{field:'PALLET',title:'PLT NO.',width:50,halign:'center', align:'center'},
				{field:'STATUS', title:'STATUS', halign:'center', width:150},
				{field:'PRINT_DATE',title:'PRINT DATE.', halign:'center', align:'center', width:180, sortable: true}
			]],
			onSelect:function(id,row){
				if(row.STATUS == 'SUDAH DI PRINT'){
					$('#dg_packing').datagrid('unselectRow', id);
				} 
			}	
		});
			
		$('#dg_label').datagrid( {
			loadMsg:'load data max 1 minute...',
			rownumbers :true,
			columns:[[
				{field:'WORK_ORDER',title:'WO NO',width:180,halign:'center', align:'center',hidden: false},
				{field:'PALLET',title:'PLT NO.',width:50,halign:'center', align:'center'},
				{field:'STATUS', title:'STATUS', halign:'center', width:150},
				{field:'PRINT_DATE',title:'PRINT DATE.', halign:'center', align:'center', width:180, sortable: true}
			]],
			onSelect:function(id,row){
				if(row.STATUS == 'SUDAH DI PRINT'){
					$('#dg_label').datagrid('unselectRow', id);
				} 
			}	
		});

		$('#dg_material').datagrid( {
			singleSelect: true,
			loadMsg:'load data max 1 minute...',
			rownumbers :true,
			columns:[[
				{field:'ITEM_NO',title:'ITEM NO',width:70,halign:'center', align:'center',hidden: false},
				{field:'ITEM_NAME',title:'DESCRIPTION.',width:230,halign:'center', align:'left'},
				{field:'QTY_REQ', title:'QTY<br>REQUESTED', halign:'center', width:85, align:'right'},
				{field:'QTY_ON_BOOK',title:'QTY<br>RESERVED', halign:'center', align:'right', width:85},
				{field:'QTY_WAREHOUSE',title:'QTY<br>WAREHOUSE', halign:'center', align:'right', width:85},
				{field:'STS',title:'STATUS', halign:'center', align:'center', width:150},
				{field:'RSTS',title:'STATUS', halign:'center', align:'center', width:150,hidden: true}
			]]
		});
	}); 
	    
	function checkdate(){
		var dataRows_Edit = [];
		var t = $('#dg_detail').datagrid('getRows');
		var total = t.length;
		var flag = 0;
		for(i=0;i<total;i++){
			$('#dg_detail').datagrid('endEdit',i);	
			dataRows_Edit.push ({
				mps_date: $('#dg_detail').datagrid('getData').rows[i].MPS_DATE	
			});
		}
		
		for(i=0;i<total;i++){
			for(iy=0;iy<total;iy++){
				if(i!=iy){
					if($('#dg_detail').datagrid('getData').rows[i].MPS_DATE == $('#dg_detail').datagrid('getData').rows[iy].MPS_DATE){
						return flag+1;
					}
				}
			}
		}
		return flag;
	}

	function formattgl(tgl){
	    var hari = tgl.substring(0,2); // sl
	    var bulan = getMonthFromString(tgl.substring(3,6));
	    var tahun = tgl.substring(7,9);

	    if (bulan<10) {
	    	bulan = '0'+bulan;
	    }
	    
		return '20'+tahun+'-'+bulan+'-'+hari;
	};

	function getMonthFromString(mon){
		return new Date(Date.parse(mon +" 1, 2012")).getMonth()+1
	}	

	function filterData(){
		var ck_cr_date = "false";
		var ck_po_no = "false";
		var ck_wo_no = "false";
		var ck_item_no = "false";
		var ck_si = "false";
		var ck_ppbe = "false";
		var flag = 0;

		if ($('#ck_cr_date').attr("checked")) {
			ck_cr_date = "true";
			flag += 1;
		};

		if ($('#ck_po_no').attr("checked")) {
			ck_po_no = "true";
			flag += 1;
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
			flag += 1;
		};

		if ($('#ck_wo_no').attr("checked")) {
			ck_wo_no = "true";
			flag += 1;
	    };
	       
	    $('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			ck_cr_date: ck_cr_date,
			cmb_wo_no : $('#cmb_wo_no').combobox('getValue'),
			ck_wo_no: ck_wo_no,
			cmb_po_no : $('#cmb_po_no').combobox('getValue'),
			ck_po_no: ck_po_no,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no
		});

		$('#dg').datagrid( {
			url: 'kanban_print_get.php',
			singleSelect:true,
			fitColumns:true,
			columns:[[
	        	{field:'WORK_ORDER',title:'WORK ORDER', halign:'center', width:110},
	            {field:'PO_NO', title:'CUST. PO NO.', halign:'center', width:70},
	            {field:'PO_LINE_NO', title:'PO<br>LINE', halign:'center', align:'center', width:28},
	            {field:'ITEM_NO', title:'ITEM<br/>NO.', halign:'center', width:38},
	            {field:'ITEM_NAME', title:'ITEM NAME', halign:'center', align:'left', width:100},
				{field:'STATUS', title:'STATUS', halign:'center', align:'center', width:28},
				{field:'BOM_LEVEL', title:'BOM<br>LEVEL', halign:'center', align:'center', width:30},
				{field:'DATE_CODE', title:'DATE<br>CODE', halign:'center', align:'left', width:30},
				{field:'CR_DATE', title:'CR DATE', halign:'center', align:'left', width:35},
	            {field:'BATERY_TYPE', title:'BATERY<br/>TYPE', halign:'center', align:'center', width:30},
	            {field:'CELL_GRADE', title:'GRADE', halign:'center', align:'center', width:25},
				{field:'QTY_PRODUKSI', title:'QTY<br>PRODUKSI', halign:'center', align:'right', width:40},
				{field:'QTY', title:'QTY<br/>ORDER', halign:'center', align:'right', width:45},
	            {field:'TOTALPALLET', title:'TOTAL<br/>QTY', halign:'center', align:'right', width:45 ,hidden: true},
				{field:'QTY_PALLET', title:'QTY PALLET', halign:'center', align:'right', width:45 ,hidden: true},
				{field:'QTYPLTAKHIR', title:'AKHIR<br/>QTY PALLET', halign:'center', align:'right', width:45 ,hidden: true}
			]]
		});

		$('#dg').datagrid('enableFilter');
	}

	function printpacking(){
		$('#dlg_packing').dialog('open').dialog('setTitle','Print Packing Kanban');
		$('#dg_packing').datagrid('loadData', []);
		var rows1 = $('#dg').datagrid('getSelections');
		$('#dg_packing').datagrid( {
			url:'json/json_cek_kanban_packing_print.php?wo_no='+rows1[0].WORK_ORDER+'&type=PACKING',
		});
	}

	function printlabel(){
		$('#dlg_label').dialog('open').dialog('setTitle','Print Packing Label');
		$('#dg_label').datagrid('loadData', []);
		var rows1 = $('#dg').datagrid('getSelections');
		$('#dg_label').datagrid( {
			url:'json/json_cek_kanban_packing_print.php?wo_no='+rows1[0].WORK_ORDER+'&type=LABEL',
		});
		$('#machine_box').textbox('setValue','');
	}

	function info_kuraire(a){
		$('#dlg_viewKur').dialog('open').dialog('setTitle','VIEW INFO KURAIRE');
		$('#dg_viewKur').datagrid({
			url: 'shipping_plan_info_kur.php?work_order='+a+'',
			singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'WO_NO', title:'Work Order.', width:130, halign: 'center', align: 'center'},
			    {field:'PLT_NO', title:'Plt No', width: 60, halign: 'center'},
			    {field:'ITEM_NO', title:'Item Name', width: 80, halign: 'center'},
			    {field:'ITEM_DESCRIPTION', title:'Description.', width: 200, halign: 'center'},
			    {field:'SCAN_DATE', title:'Scan Time', width: 150, halign: 'center'},
			    {field:'SLIP_TYPE', title:'Slip Type', width: 70, halign: 'center'},
			    {field:'SLIP_QUANTITY', title:'Quantity', width: 100, halign: 'center', align: 'right'},
			    {field:'APPROVAL_DATE', title:'Approval Date', width:100, halign: 'center'}
			]]
		});
	}
</script>
</body>
</html>
