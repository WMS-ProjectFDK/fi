<?php
require("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
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
<script type="text/javascript" src="../../js/canvasjs.min.js"></script>
<script type="text/javascript" src="../../js/datagrid-export.js"></script>

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
	<fieldset style="float:left;width:900px;border-radius:4px;height: 100px;"><legend><span class="style3"><strong>KANBAN PRINT FILTER</strong></span></legend>
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
				<select style="width:190px;" name="cmb_wo_no" id="cmb_wo_no" class="easyui-combobox" data-options=" url:'../json/json_mps.php',method:'get',valueField:'work_order',textField:'work_order', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_wo_no" id="ck_wo_no" checked="true">All</input></label>
			</div>
		</div>
		<div style="width:450px;float:left">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PO No.</span>
				<select style="width:300px;" name="cmb_po_no" id="cmb_po_no" class="easyui-combobox" data-options=" url:'../json/json_mps.php',method:'get',valueField:'po_no',textField:'po_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_po_no" id="ck_po_no" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:80px;display:inline-block;">Item FG</span>
				<select style="width:300px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_mps.php',method:'get',valueField:'item_no',textField:'item_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="margin-left:910px;border-radius:4px;width: 250px;height: 100px;"><legend><span class="style3"><strong>SELECT KANBAN PRINT</strong></span></legend>
		<div align="center">
			<div class="fitem">
				<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="printlabel()" style="width:200px;"><i class="fa fa-print" aria-hidden="true"></i> Kanban Label</a>
			</div>
			<div class="fitem">
				<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="printmaterial()" style="width:200px;"><i class="fa fa-print" aria-hidden="true"></i> Kanban Material</a>
			</div>
			<div class="fitem">
				<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="printpacking()" style="width:200px;"><i class="fa fa-print" aria-hidden="true"></i> Kanban Packing</a>
			</div>
		</div>
	</fieldset>

	<div style="padding:3px 3px;">
		<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
		<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="otherPrint()" style="width:170px;"><i class="fa fa-print" aria-hidden="true"></i> Other Print</a>
		<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="SSCCPrint()" style="width:170px;"><i class="fa fa-print" aria-hidden="true"></i> SSCC Print</a>

		<?php
		if($user_name == 'FI0122' OR $user_name == 'FI202'){ ?>
		<a href="javascript:void(0)" class="easyui-splitbutton c2" data-options="menu:'#mm'" style="width:170px;"><i class="fa fa-trash" aria-hidden="true"></i> Delete Kanban</a>
		<?php } ?>
	</div>
	<?php
	if($user_name == 'FI0122' OR $user_name == 'FI202'){ ?>
    <div id="mm" style="width:auto;">
        <div>
        	<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" style="width: 250px;" onclick="deleteKanbanLabel()">
        		<i class="fa fa-trash" aria-hidden="true"></i> Kanban Label
        	</a>
        </div>
        <div>
        	<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" style="width: 250px;" onclick="deleteKanbanpacking()">
        		<i class="fa fa-trash" aria-hidden="true"></i> Kanban Packing
        	</a>
        </div>
        <div>
        	<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" style="width: 250px;" onclick="deleteKanbanMaterial()">
        		<i class="fa fa-trash" aria-hidden="true"></i> Kanban Material
        	</a>
        </div>
    </div>
    <?php } ?>
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
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="openbom()" style="width:50px;"><i class="" aria-hidden="true"></i> view</a>
		</div>
	</fieldset>		
	<div class="fitem" style="margin-right">
		<span style="width:100px;display:inline-block;"></span>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="saveheader()" style="width:120px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
		<span style="width:10px;display:inline-block;"></span>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="$('#dlg_header').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>
	<div >
</div>

<!-- OTHER PRINT -->
<div id="dlg_otherPrint" class="easyui-dialog" style="width: 330px;height: auto;" closed="true" data-options="modal:true">
	<div class="fitem" id="print_type">
		<INPUT TYPE='radio' NAME='sheet_type' id='casemark' VALUE='case_mark' checked/> CASE MARK<br/>
		<INPUT TYPE='radio' NAME='sheet_type' id='palletmark' VALUE='pallet_mark'/> PALLET MARK<br/>
		<INPUT TYPE='radio' NAME='sheet_type' id='cautionmark' VALUE='caution_mark'/> CAUTION MARK<br/>
	</div>
	<div class="fitem" align="center">
		<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="printOther" onclick="printOther()"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
	</div>
</div>
<!-- END -->

<!-- SSCC PRINT -->
<div id="dlg_SSCCPrint" class="easyui-dialog" style="width: 730px;height: 450px;padding: 5px 5px;" closed="true" data-options="modal:true" buttons="#dlg-buttons-sscc">
	<div class="fitem">
		<span style="display:inline-block;">QUANTITY :</span>
		<input style="width:120px;" name="sscc_qty" id="sscc_qty" class="easyui-textbox" disabled="true"/>
		<span style="width:25px;display:inline-block;"></span>
		<span style="display:inline-block;">TOTAL CARTON :</span>
		<input style="width:100px;" name="sscc_total_carton" id="sscc_total_carton" class="easyui-textbox" disabled="true"/>
		<span style="width:25px;display:inline-block;"></span>
		<span style="display:inline-block;">ITEM NO. :</span>
		<input style="width:100px;" name="sscc_item_no" id="sscc_item_no" class="easyui-textbox" disabled="true"/>
	</div>
	<table id="dg_sscc" toolbar="#toolbar_dgsscc" class="easyui-datagrid" style="width:100%;height:90%;padding: 5px 5px;" data-options="fitColumns: true, rownumbers: true"></table>

	<div id="dlg-buttons-sscc">
		<span style="display:inline-block;font-size: 9px; color: red;text-align: left;">*): max print SSCC 5 pallet</span>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="printSSCC()" style="width:120px;"> Save & Print</a>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg_SSCCPrint').dialog('close')" style="width:120px;"> Exit</a>
	</div>
</div>
<!-- END -->

<!-- SSCC PRINT ULANG -->
<div id="dlg_SSCCPrint_ulang" class="easyui-dialog" style="width: 600px;height: 300px;padding: 5px 5px;" closed="true" data-options="modal:true">
	<table id="dg_sscc_ulang" class="easyui-datagrid" style="width:100%;height:88%;padding: 5px 5px;" data-options="fitColumns: true"></table>
	<div style="clear:both;margin-bottom:5px;"></div>
	<div id="dlg-buttons-sscc-ulang">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="printSSCC_ulang()" style="width:120px;"> Print Ulang</a>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg_SSCCPrint_ulang').dialog('close')" style="width:120px;"> Exit</a>
	</div>
</div>
<!-- END -->

<!-- DELETE KANBAN -->
<div id="dlg_delete_kanban" class="easyui-dialog" style="width: 350px;height: auto;padding: 5px 5px;" closed="true" data-options="modal:true" buttons="#dlg-buttons-delkan">
    <div style="padding: 5px 5px;">
		<div class="fitem" hidden="true">
			<input style="width:150px;" name="w_kan" id="w_kan" class="easyui-textbox" disabled="true" hidden="true" />
			<input style="width:150px;" name="s_kan" id="s_kan" class="easyui-textbox" disabled="true" hidden="true" />
		</div>
		<div class="fitem">
			<span style="width:100px;display:inline-block;">PALLET NO. : </span>
			<input style="width:70px;" name="plt_del_A" id="plt_del_A" class="easyui-numberbox"  required="true"/>
			<span style="width:30px;display:inline-block;">TO</span>
			<input style="width:70px;" name="plt_del_Z" id="plt_del_Z" class="easyui-numberbox"  required="true"/>
		</div>
	</div>

	<div id="dlg-buttons-delkan">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="delete_kanban()" style="width:120px;"> Delete</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg_delete_kanban').dialog('close')" style="width:120px;"> Exit</a>	
	</div>
</div>
<!-- END -->

<div id='dlg_detail' class="easyui-dialog" style="width:450px;height:480px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">
	<table id="dg_detail" class="easyui-datagrid" style="width:99%;height:90%;">  </table>	
	<span style="width:380px;display:inline-block;"></span>
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="savedetail()" style="width:120px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>	
	<span style="width:20px;display:inline-block;"></span>
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="addrowdetail()" style="width:120px;"><i class="fa fa-next" aria-hidden="true"></i> Add  Row</a>	
	<span style="width:20px;display:inline-block;"></span>
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="$('#dlg_detail').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>	
</div>

<div id='dlg_packing' class="easyui-dialog" style="width:43%;height:500px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-packing">
	<span style="width:400px;display:inline-block;"></span>
	<table id="dg_packing" toolbar="#toolbar_dgPacking" class="easyui-datagrid" style="width:99%;height:100%;">  </table>

	<div id="dlg-buttons-packing">	
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="printpackingnow()" style="width:120px;"> Print</a>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg_packing').dialog('close')" style="width:120px;"> Exit</a>
	</div>
</div>

<div id='dlg_label' class="easyui-dialog" style="width:43%;height:500px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-label">
	<div id="toolbar_dgLabel" style="padding: 5px 5px;">
		<span style="width:100px;display:inline-block;">Label Machine: </span>
		<select id="machine_box" class="easyui-combobox" name="machine_box" style="width:140px;" required="true">
			<option value="" selected="selected"></option>
			<option value="LR03-1">LR03#1</option>
			<option value="LR03-2">LR03#2</option>
			<option value="LR6-1">LR6#1</option>
			<option value="LR6-2">LR6#2</option>
			<option value="LR6-3">LR6#3</option>
			<option value="LR6-4">LR6#4</option>
			<option value="LR6-5">LR6#5</option>
			<option value="LR6-6">LR6#6</option>
		</select>
	</div>

	<table id="dg_label" toolbar="#toolbar_dgLabel" class="easyui-datagrid" style="width:auto;height:100%;">  </table>

	<div id="dlg-buttons-label">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="printlabelnow()" style="width:150px;"> Print</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg_label').dialog('close')" style="width:150px;"> Exit</a>
	</div>
</div>

<div id='dlg_material' class="easyui-dialog" style="width:53%;height:500px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-material">
	<div id="toolbar_dgMaterial" style="padding: 5px 5px;">
		<span style="width:80px;display:inline-block;">Pallet No : </span>
		<input style="width:50px;" name="plt_mtr_start_edit" id="plt_mtr_start_edit" class="easyui-numberbox"  required="true"/>
		<span style="width:20px;display:inline-block;">to</span>
		<input style="width:50px;" name="plt_mtr_end_edit" id="plt_mtr_end_edit" class="easyui-numberbox"  required="true"/>
		<a href="javascript:void(0)" id="kalkulasi_button" class="easyui-linkbutton c2" onclick="kalkulasi()" style="width:120px;"><i class="fa fa-process" aria-hidden="true"></i> Calculate</a>	
		<span style="width:200px;display:inline-block;color:red;font-weight:bold" id="loading_span"></span>
	</div>

	<table id="dg_material" toolbar="#toolbar_dgMaterial" class="easyui-datagrid" style="width:99%;height:100%;">  </table>

	<div id="dlg-buttons-material">
		<a href="javascript:void(0)" id="printmaterial_button" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="printmaterialnow()" style="width:120px;"> Print</a>
		<a href="javascript:void(0)" id="exitmaterial_button" class="easyui-linkbutton" iconCls="icon-cancel" onclick="$('#dlg_material').dialog('close')" style="width:120px;"> Exit</a>	
	</div>
</div>

<div id='dlg_viewKur' class="easyui-dialog" style="width:1000px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewKur" class="easyui-datagrid" style="width:100%;height:100%;"></table>
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
	    	
	$(function(){
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
	});

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

		$('#dg').datagrid({
			url: 'kanban_print_get.php',
			singleSelect:true,
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
				{field:'QTYPLTAKHIR', title:'AKHIR<br/>QTY PALLET', halign:'center', align:'right', width:45 ,hidden: true},
				{field:'QTY_CARTON', title:'QTY CARTON', halign:'center', align:'right', width:45 ,hidden: true},
				{field:'QTY_CARTON_AKHIR', title:'AKHIR<br/>QTY CARTON', halign:'center', align:'right', width:45 ,hidden: true},
				{field:'QTY_PCSPERCARTON', title:'PCS<br/>PERPALLET', halign:'center', align:'right', width:45 ,hidden: true},
				{field:'COUNTRY_CODE', title:'COUNTRY<br/>CODE', halign:'center', align:'right', width:45 ,hidden: true},
				{field:'JUM_SSCC', title:'STATUS<br>SSCC', halign:'center', align:'right', width:45 ,hidden: true}
			]]
		});

		$('#dg').datagrid('enableFilter');
	}

	function printlabel(){
		var row = $('#dg').datagrid('getSelected');	
		if (row){
			$('#dlg_label').dialog('open').dialog('setTitle','PRINT PACKING LABEL');
			$('#dg_label').datagrid('loadData', []);
			var rows1 = $('#dg').datagrid('getSelections');

			$('#dg_label').datagrid( {
				url:'../json/json_cek_kanban_packing_print.php?wo_no='+rows1[0].WORK_ORDER+'&type=LABEL',
				loadMsg:'load data max 1 minute...',
				rownumbers :true,
				fitColumns: true,
				columns:[[
					{field:'WORK_ORDER',title:'WO NO',width:180,halign:'center'},
					{field:'PALLET',title:'PLT NO.',width:65,halign:'center', align:'center'},
					{field:'STATUS', title:'STATUS', halign:'center', width:125, align:'center'},
					{field:'PRINT_DATE',title:'PRINT DATE', halign:'center', width:180}
				]],
				onSelect:function(id,row){
					if(row.STATUS == 'SUDAH DI PRINT'){
						$('#dg_label').datagrid('unselectRow', id);
					} 
				}	
			});

			$('#machine_box').textbox('setValue','');
		}else{
			$.messager.show({
				title: 'KANBAN PRINT',
				msg: 'Data Not Selected'
			});
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
			}else if (date_code == ''){
				$.messager.alert('Warning','Date Code not found','warning');
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

	function printmaterial(){
		var row = $('#dg').datagrid('getSelected');	
		if (row){
			$('#dlg_material').dialog('open').dialog('setTitle','PRINT MATERIAL KANBAN OF WO ('+row.WORK_ORDER+') TOTAL PALLET ('+row.TOTALPALLET+')');
			$('#dg_material').datagrid('loadData', []);
			$('#plt_mtr_start_edit').textbox('setValue','');
		    $('#plt_mtr_end_edit').textbox('setValue','');

		    $('#dg_material').datagrid( {
				singleSelect: true,
				loadMsg:'load data max 1 minute...',
				rownumbers: true,
				fitColumns: true,
				columns:[[
					{field:'ITEM_NO',title:'ITEM NO',width:70,halign:'center', align:'center'},
					{field:'ITEM_NAME',title:'DESCRIPTION',width:230,halign:'center'},
					{field:'QTY_REQ', title:'QTY<br>REQUESTED', halign:'center', align:'right', width:85},
					{field:'QTY_ON_BOOK',title:'QTY<br>RESERVED', halign:'center', align:'right', width:85},
					{field:'QTY_WAREHOUSE',title:'QTY<br>WAREHOUSE', halign:'center', align:'right', width:88},
					{field:'STS',title:'STATUS', halign:'center', align:'center', width:150},
					{field:'RSTS',title:'STATUS', halign:'center', align:'center', width:150,hidden: true}
				]]
			});
		}else{
			$.messager.show({
				title: 'KANBAN PRINT',
				msg: 'Data Not Selected'
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

	function printpacking(){
		var row = $('#dg').datagrid('getSelected');	
		if (row){
			$('#dlg_packing').dialog('open').dialog('setTitle','PRINT PACKING KANBAN');
			$('#dg_packing').datagrid('loadData', []);

			$('#dg_packing').datagrid( {
				url:'../json/json_cek_kanban_packing_print.php?wo_no='+row.WORK_ORDER+'&type=PACKING',
				loadMsg:'load data max 1 minute...',
				rownumbers: true,
				fitColumns: true,
				columns:[[
					{field:'WORK_ORDER',title:'WO NO',width:180,halign:'center'},
					{field:'PALLET',title:'PLT NO.',width:55,halign:'center', align:'center'},
					{field:'STATUS', title:'STATUS', halign:'center', width:150, align:'center'},
					{field:'PRINT_DATE',title:'PRINT DATE', halign:'center', width:180}
				]],
				onSelect:function(id,row){
					if(row.STATUS == 'SUDAH DI PRINT'){
						$('#dg_packing').datagrid('unselectRow', id);
					} 
				}
			});	
		}else{
			$.messager.show({
				title: 'KANBAN PRINT',
				msg: 'Data Not Selected'
			});
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

			if (date_code == ''){
				$.messager.alert('Warning','Date Code not found','warning');
			}else{
				console.log('kanban_print_packing.php?data='+unescape(str_unescape));
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
			}
		}else{
			$.messager.alert('INFORMATION','Please select the pallet number first.','info');		
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
				url: '../json/json_cek_kanban_material.php?wo_no='+rows1[0].WORK_ORDER+'&pallet='+pallet,
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
			]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		});
	}

	var wo_print = '';
	function otherPrint(){
		var row = $('#dg').datagrid('getSelected');
		if (row){
			wo_print = row.WORK_ORDER;
			$('#dlg_otherPrint').dialog('open').dialog('setTitle','Print Properties ('+row.WORK_ORDER+')');	
		}else{
			$.messager.show({
				title: 'KANBAN PRINT',
				msg: 'Data Not Found'
			});
		}
	}

	function printOther(){
		var prnt;

		if(document.getElementById('casemark').checked == true){
			prnt = document.getElementById('casemark').value;
		}else if(document.getElementById('palletmark').checked == true){
			prnt = document.getElementById('palletmark').value;
		}else if(document.getElementById('cautionmark').checked == true){
			prnt = document.getElementById('cautionmark').value;
		}

		// alert(prnt);
		if (prnt == 'case_mark'){
			window.open('kanban_print_other_casemark.php?wo='+wo_print);	
		}else if (prnt == 'pallet_mark'){
			window.open('kanban_print_other_palletmark.php?wo='+wo_print);	
		}else if (prnt == 'caution_mark'){
			window.open('../images/CAUTION_MARK.pdf');
		}

		$('#dlg_otherPrint').dialog('close');
	}

	var sscc_item = '';
	var sscc_qtyTotal = '';
	var sscc_pcs_percarton = '';
	var sscc_qty_carton = '';
	var sscc_country_code = '';
	var sscc_start_carton = '';

	function SSCCPrint(){
		var ArrSSCCnew = [];
		var row = $('#dg').datagrid('getSelected');
		if (row){
			$('#dg_sscc').datagrid('loadData', []);
			sscc_pcs_percarton = row.QTY_PCSPERCARTON;
			sscc_qty_carton = row.QTY_CARTON;
			sscc_country_code = row.COUNTRY_CODE;
			
			if (row.JUM_SSCC == 0){
				$.messager.confirm('CONFIRM SSCC','Ini WO No. baru belum ada Perhitungan carton untuk SSCC, apakah akan dilakukan perhitungan carton sekarang?',function(r){
					if(r){
						$.messager.progress({
						    title:'Please waiting',
						    msg:'create data...'
						});
						// insert ztb_amazon_wo
						ArrSSCCnew.push({
							sscc_new_qty: row.QTY.replace(/,/g,''),
							sscc_new_wo: row.WORK_ORDER,
							sscc_new_item: row.ITEM_NO,
							sscc_new_pcs_per_pallet: row.QTY_PALLET,
							sscc_new_carton_per_pallet: row.QTY_CARTON,
							sscc_new_pcs_per_carton: row.QTY_PCSPERCARTON,
							sscc_new_country_code: row.COUNTRY_CODE
						});

						var myJSONnew = JSON.stringify(ArrSSCCnew);
						var str_unescapeSSCCnew=unescape(myJSONnew);
						console.log('kanban_print_sscc_save_new.php?data='+str_unescapeSSCCnew);

						$.post('kanban_print_sscc_save_new.php',{
							data: unescape(str_unescapeSSCCnew)
						}).done(function(res){
							if(res == '"success"'){
								$('#dg').datagrid('reload');
								$.messager.alert('INFORMATION','Perhitungan SSCC sukses..!!','info');
							}else{
								$.messager.alert('ERROR',res,'warning');
							}
							$.messager.progress('close');
						});
					}
				});
			}else{
				$.ajax({
					type: 'GET',
					url: 'kanban_print_sscc_get.php?wo_no='+row.WORK_ORDER,
					data: { kode:'kode' },
					success: function(data){
						$('#dlg_SSCCPrint').dialog('open').dialog('setTitle','PRINT SSCC ('+row.WORK_ORDER+')');
						$('#sscc_qty').textbox('setValue',data[0].QUANTITY);
						$('#sscc_total_carton').textbox('setValue',data[0].TOTAL_CARTON);
						$('#sscc_item_no').textbox('setValue',data[0].ITEM);
						
						sscc_item = data[0].ITEM;
						sscc_qtyTotal = data[0].QUANTITY.replace(/,/g,'');
						sscc_start_carton = data[0].START_CARTON.replace(/,/g,'');
						$('#dg_sscc').datagrid({
							url: 'kanban_print_sscc_info.php?wo_no='+row.WORK_ORDER+'&jumPlt='+row.TOTALPALLET,
							rownumbers: true,
							checkOnSelect: true,
				    		selectOnCheck: true,
						    columns:[[
						    	{field:'ck', checkbox:true, width:30, halign: 'center'},
							    {field:'WO_NO', title:'WORK ORDER', width:130, halign: 'center', align: 'center'},
							    {field:'PLT_NO', title:'PALLET NO.', width: 55, halign: 'center', align: 'center'},
							    {field:'STATUS', title:'STATUS', width: 85, halign: 'center', align: 'center'},
							    {field:'PRINT_DATE', title:'PRINT DATE', width: 100, halign: 'center', align: 'center'},
							    {field:'USER', title:'USER', width:100, halign: 'center', align: 'center'},
							    {field:'PRINT_ULANG', title:'PRINT ULANG', width:100, halign: 'center', align: 'center'}
							]]//,
							// onclickRow: function(index,row){
		     //  					if(row.STATUS == 'SUDAH DI PRINT'){
							// 		$.messager.alert('Warning','PALLET INI SUDAH DI PRINT','warning');
							// 	}
		     //                },
							// onSelect: function(rowIndex, rowData){
		     //  					if(rowData.STATUS == 'SUDAH DI PRINT'){
							// 		$('#dg_sscc').datagrid('unselectRow', rowIndex);
							// 	}
		     //                },
						 //    onUncheck: function(index,row){
						 //        if(row.STATUS == 'SUDAH DI PRINT'){
						 //        	$('#dg_sscc').datagrid('uncheckRow', index);	
							// 	}
						 //    }
						});
					}
				});
			}
		}else{
			$.messager.show({
				title: 'PRINT SSCC',
				msg: 'Data Not Found'
			});
		}
	}

	function printSSCC(){
		$.messager.progress({
		    title:'Please waiting',
		    msg:'saving data...'
		});

		var ArrprintSSCC = [];
		var rows = $('#dg_sscc').datagrid('getSelections');
		var wo = '';

		if (rows.length == 0){
			$.messager.alert('Warning','DATA BELUM ADA YANG DIPILIH','warning');
			$.messager.progress('close');
		}else if (rows.length > 5){
			$.messager.alert('Warning','DATA YANG DIPILIH LEBIH DARI 5 PALLET','warning');
			$.messager.progress('close');
		}else{
			for(i=0;i<rows.length;i++){
				$('#dg_sscc').datagrid('endEdit',i);
				wo = rows[i].WO_NO;
				ArrprintSSCC.push({
					sscc_wo: rows[i].WO_NO,
					sscc_plt: rows[i].PLT_NO,
					sscc_item: sscc_item,
					sscc_qtyTotal: sscc_qtyTotal, 
					sscc_pcs_percarton: sscc_pcs_percarton,
					sscc_qty_carton: sscc_qty_carton,
					sscc_country_code: sscc_country_code,
					sscc_start_carton: sscc_start_carton
				});
			}

			console.log(ArrprintSSCC);
			console.log(ArrprintSSCC.length);

			if(ArrprintSSCC.length == 0){
				$.messager.alert('Warning','DATA BELUM ADA YANG DIPILIH ATAU SUDAH DI PRINT','warning');
				$.messager.progress('close');
			}else{
				var myJSON=JSON.stringify(ArrprintSSCC);
				var str_unescape=unescape(myJSON);
				console.log('kanban_print_sscc_save.php?wo='+wo+'&data='+str_unescape);

				$.post('kanban_print_sscc_save.php',{
					wo: wo,
					data: unescape(str_unescape)
				}).done(function(res){
					if(res == '"success"'){
						$('#dg').datagrid('reload');
						$('#dlg_SSCCPrint').dialog('close')
						$.messager.progress('close');
						$.messager.confirm('CONFIRM PRINT SSCC','Apakah akan dilakukan Print SSCC sekarang?',function(r){
							if(r){
								window.open('kanban_print_sscc_print.php');
							}
						});		
					}else{
						$.messager.alert('ERROR',res,'warning');
					}
				});
			}
		}
	}

	function print_ulang_sscc_percarton(a,b){
		$('#dlg_SSCCPrint_ulang').dialog('open').dialog('setTitle','PRINT ULANG SSCC (WO: '+a+', PALLET: '+b+')');
		$('#dg_sscc_ulang').datagrid({
			url: 'kanban_print_sscc_print_ulang.php?wo='+a+'&plt='+b,
    		rownumbers: true,
    		singleSelect: true,
		    columns:[[
		    	{field:'ck', checkbox:true, width:30, halign: 'center'},
		    	{field:'ID_CARTON', title:'CARTON ID', width:70, halign: 'center', align: 'center'},
			    {field:'SSCC', title:'SSCC NO.', width:100, halign: 'center', align: 'center'},
			    {field:'PO', title:'PO', width:70, halign: 'center', align: 'center'},
			    {field:'ASIN', title:'ASIN', width:70, halign: 'center', align: 'center'}
			]]
		});

		// printSSCC_ulang
		// alert('http://localhost/wms/forms/kanban_print_sscc_print_ulang.php?wo='+a+'&plt='+b);
	}

	function printSSCC_ulang(){
		var row = $('#dg_sscc_ulang').datagrid('getSelected');
		if(row){
			window.open('kanban_print_sscc_print_ulang_print.php?id='+row.ID_CARTON);
		}else{
			$.messager.show({
				title: 'KANBAN PRINT',
				msg: 'Data Not Selected'
			});
		}
	}

/*-----------------------		DELETE KANBAN PRINT 		--------------------*/

	var stsKanban;
	function deleteKanbanLabel () {
		var row = $('#dg').datagrid('getSelected');
		if (row){
			// alert (row.WORK_ORDER);
			$('#dlg_delete_kanban').dialog('open').dialog('setTitle','DELETE KANBAN LABEL (WO: '+row.WORK_ORDER+')');
			$('#plt_del_A').numberbox('setValue', 0);
			$('#plt_del_Z').numberbox('setValue', 0);
			$('#s_kan').textbox('setValue', 'ZTB_KANBAN_PRINT_LABEL');
			$('#w_kan').textbox('setValue', row.WORK_ORDER);
		}else{
			$.messager.show({
				title: 'DELETE KANBAN',
				msg: 'Data Not selected'
			});
		}
	}

	function deleteKanbanpacking () {
		var row = $('#dg').datagrid('getSelected');
		if (row){
			$('#dlg_delete_kanban').dialog('open').dialog('setTitle','DELETE KANBAN PACKING (WO: '+row.WORK_ORDER+')');
			$('#plt_del_A').numberbox('setValue', 0);
			$('#plt_del_Z').numberbox('setValue', 0);
			$('#s_kan').textbox('setValue', 'ZTB_KANBAN_PRINT');
			$('#w_kan').textbox('setValue', row.WORK_ORDER);
		}else{
			$.messager.show({
				title: 'DELETE KANBAN',
				msg: 'Data Not selected'
			});
		}
	}

	function deleteKanbanMaterial () {
		var row = $('#dg').datagrid('getSelected');
		if (row){
			$('#dlg_delete_kanban').dialog('open').dialog('setTitle','DELETE KANBAN MATERIAL (WO: '+row.WORK_ORDER+')');
			$('#plt_del_A').numberbox('setValue', 0);
			$('#plt_del_Z').numberbox('setValue', 0);
			$('#s_kan').textbox('setValue', 'ZTB_ITEM_BOOK');
			$('#w_kan').textbox('setValue', row.WORK_ORDER);
		}else{
			$.messager.show({
				title: 'DELETE KANBAN',
				msg: 'Data Not selected'
			});
		}
	}

	function delete_kanban () {
		$.messager.confirm('CONFIRM DELETE KANBAN','Yakin akan menghapus print kanban sekarang?',function(r){
			if(r){
				$.post('kanban_print_delete.php',{
					sts: $('#s_kan').textbox('getValue'),
					wo: $('#w_kan').textbox('getValue'),
					pltA: $('#plt_del_A').numberbox('getValue'),
					pltZ: $('#plt_del_Z').numberbox('getValue')
				},function(result){
					if (result.successMsg == 'success'){
	                   $('#dlg_delete_kanban').dialog('close')
	                   $('#dg').datagrid('reload');
	                }else{
	                   $.messager.show({
	                       title: 'Error',
	                       msg: result.errorMsg
	                   });
	                   $.messager.progress('close');
	               }
				},'json');
			}
		});	
	}
</script>
</body>
</html>