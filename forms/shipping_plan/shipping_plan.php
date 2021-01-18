<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
if ($varConn=='Y'){

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SHIPPING PLAN</title>
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
<link rel="stylesheet" type="text/css" href="../../themes/color.css" />
<script type="text/javascript" src="../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../js/jquery.easyui.patch.js"></script>
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
<?php include ('../../ico_logout.php'); ?>
<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="margin-left;border-radius:4px;height:70px;width:98%"><legend><span class="style3"><strong> Shipping Plan By MPS </strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:150px;display:inline-block;">Cargo Ready Date</span>
				<input style="width:125px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			  		to   
				<input style="width:125px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				<label><input type="checkbox" name="ck_cr_date" id="ck_cr_date" checked="true">All</input></label>
			</div>
			<div class="fitem">
				<span style="width:150px;display:inline-block;">WO No.</span>
				<select style="width:273px;" name="cmb_wo_no" id="cmb_wo_no" class="easyui-combobox" data-options=" url:'../json/json_mps.php',method:'get',valueField:'work_order',textField:'work_order', panelHeight:'150px'"></select>
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
				<span style="width:80px;display:inline-block;">Item No.</span>
				<select style="width:300px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_mps.php',method:'get',valueField:'item_no',textField:'item_no', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
			</div>
		</div>
		<div style="width:500px; margin-left: 900px;">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PPBE No.</span>
				<select style="width:120px;" name="cmb_ppbe" id="cmb_ppbe" class="easyui-combobox" 
					data-options=" url:'../json/json_ppbe_no.php?user=<? echo $user_name; ?>',method:'get',valueField:'ppbe_no',textField:'ppbe_no', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_ppbe" id="ck_ppbe" checked="true">All</input></label>
			</div>		
		     <div class="fitem">
				<span style="width:80px;display:inline-block;">SI No.</span>
				<select style="width:200px;" name="cmb_si_no" id="cmb_si_no" class="easyui-combobox" 
					data-options=" url:'../json/json_si_no.php',method:'get',valueField:'si_no',textField:'si_no', panelHeight:'100px'"></select>
				<label><input type="checkbox" name="ck_si" id="ck_si" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<div style="padding:5px 6px;">
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
		<a href="javascript:void(0)" id="add_shipping_plan" class="easyui-linkbutton c2" onClick="addShippingPlan()" style="width:200px;"><i class="fa fa-plus" aria-hidden="true"></i> Add Shipping Plan</a>
		<a href="javascript:void(0)" id="print_si" class="easyui-linkbutton c2" onClick="print_si()" style="width:200px;"><i class="fa fa-print" aria-hidden="true"></i> Print SI to Forwarder</a>
		<label><input type="checkbox" name="ck_mark" id="ck_mark" checked="true">Print SI Marking</input></label>
		<a href="javascript:void(0)" id="print_si" class="easyui-linkbutton c2" onClick="link_to_beakdown()" style="width:200px;"><i class="fa fa-eye" aria-hidden="true"></i> View Breakdown Container</a>
		<a href="javascript:void(0)" id="print_si" class="easyui-linkbutton c2" onClick="link_to_packinglist()" style="width:200px;"><i class="fa fa-eye" aria-hidden="true"></i> View Packing List</a>
		<label><input type="checkbox" name="ck_sample" id="ck_sample" >Sample Item</input></label>
	
	</div>
</div>

<table id="dg" title="Shipping Plan Entry" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" selectOnCheck= "true">	
</table>

<div id='dlg_viewKur' class="easyui-dialog" style="width:1000px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewKur" class="easyui-datagrid" style="width:100%;height:auto;"></table>
</div>

<div id='dlg_viewInv' class="easyui-dialog" style="width:900px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewInv" class="easyui-datagrid" style="width:100%;height:auto;"></table>
</div>

<div id='dlg_viewPln' class="easyui-dialog" style="width:1300px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true"
	title="<span style='margin-right:1060px;'>VIEW & EDIT INFO SHIPPING</span>
		   <span><a href='javascript:void(0)' style='color:white' onclick='closeDialog()'>
		   			<!-- <img src='../images/cancel.png' alt='Close' title='close'></img> -->
		   		 </a>
		   	</span>">
	<table id="dg_viewPln" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>

<div id='dlg_add' class="easyui-dialog" style="width:1325px;height:450px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	<table align="center" id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:auto;height:365px;padding:10px 10px; margin:5px;"></table>
	<div style="padding:5px 6px;" align="right">
		<a href="javascript:void(0)" id="savebtn_Plan" class="easyui-linkbutton c2" onClick="savedata()" style="width:200px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
		<a href="javascript:void(0)" id="closebtn" class="easyui-linkbutton c2" onclick="javascript:$('#dlg_add').dialog('close');" style="width:200px;"><i class="fa fa-cancel" aria-hidden="true"></i> Exit</a>
	</div>
	<div id="toolbar_add" style="padding:3px 3px;">
		<a href="javascript:void(0)" id="add_si" class="easyui-linkbutton c2" onClick="add_SI()" style="width:200px;">
			<i class="fa fa-plus" aria-hidden="true"></i> Add Shipping Instruction</a>
		<a href="javascript:void(0)" id="create_si" class="easyui-linkbutton c2" onClick="SI_entry()" style="width:200px;">
			<i class="fa fa-pencil" aria-hidden="true"></i> Create Shipping Instruction</a>
		<a href="javascript:void(0)" id="copy_row" class="easyui-linkbutton c2" onClick="copy_data()" style="width:200px;">
			<i class="fa fa-copy" aria-hidden="true"></i> Copy row</a>
		<span style="width:100px;display:inline-block;">PPBE No.</span>
					<input style="width:170px;" name="ppbe_no" id="ppbe_no" class="easyui-textbox"/>
					<a href="javascript:void(0)" onclick="sett_ppbe()">SET</a>
		<!-- <span style="width:50px;display:inline-block;"> </span>
					Container 20 Feet <input style="width:50px;" name="40 Feet" id="40F" class="easyui-textbox"/>
					40 Feet <input style="width:50px;" name="20 Feet" id="20F" class="easyui-textbox"/> -->
	</div>
	<div id='dlg_si' class="easyui-dialog" style="width:1300px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">
		<table id="dg_si" class="easyui-datagrid" style="width:100%;height:100%;"></table>
	</div>
</div>

<div id="dlg_transport" class="easyui-dialog" style="width: 650px;height: 300px;" closed="true" data-options="modal:true">
			<table id="dg_transport" class="easyui-datagrid" style="width:100%;height:auto;border-radius: 10px;" rownumbers="true"></table>
			<div data-options="region:'south',border:false" style="text-align:right;padding:10px 0 0;">
				<!-- <a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="calculate_con()" style="width:250px">
					<i class="fa fa-save" aria-hidden="true"></i> Auto Calculate Container </a> -->
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="recalculate_transport()" style="width:100px">
					<i class="fa fa-save" aria-hidden="true"></i> Recalculate </a>
				<a class="easyui-linkbutton c2" href="javascript:void(0)" onclick="delete_transport()" style="width:100px">
					<i class="fa fa-save" aria-hidden="true"></i> Delete </a>	
			</div>
</div>

<div id="dlg_print" class="easyui-dialog" style="width: 300px;height: 120px;" closed="true" data-options="modal:true">
	<div class="fitem" id="print_type">
		<INPUT TYPE='radio' NAME='sheet_type' id='si' VALUE='si'/> SI to Forwarder<br/>
		<!-- <INPUT TYPE='radio' NAME='sheet_type' id='ppbe' VALUE='ppbe'/> PPBE<br/> -->
	</div>
	<div class="fitem" align="center">
		<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="printInv" onclick="print()"><i class="fa fa-print" aria-hidden="true"></i> Print</a>
		<a href="javascript:void(0)" style="width: 120px;" class="easyui-linkbutton c2" id="printInv" onclick="close_print()"><i class="fa fa-cancel" aria-hidden="true"></i> Close</a>
	</div>
</div>






<script type="text/javascript">
	function closeDialog(){
		$('#dlg_viewPln').dialog('close');
		$('#dg').datagrid('reload');
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
		$('#print_si').linkbutton('disable');
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

		$('#cmb_si_no').combobox('disable');
		$('#ck_si').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_si_no').combobox('disable');
			}else{
				$('#cmb_si_no').combobox('enable');
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

		$('#cmb_ppbe').combobox('disable');
		$('#ck_ppbe').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_ppbe').combobox('disable');
			}else{
				$('#cmb_ppbe').combobox('enable');
			}
		});		

		$('#dg').datagrid( {
			columns:[[
		    	{field:'ACTION',title:'ADD SHIP',width:55,align:'center',
                	formatter:function(value,row,index){
                	    if (row.editing){
                	        var s = '<a href="javascript:void(0)" onclick="saverow(this)">Add Partial</a> ';
                	        return s;
                	    } else {
                	        
                	        var e = '<a href="javascript:void(0)" onclick="addrow(this)">Entry Partial</a> ';
                	        return e;
                	    }
                	}
            	},
            	{field:'ACTION_ADD', hidden: true},
		    	{field:'SHIPPING', align:'center', width:40, title:'SHIPPING', halign: 'center',editor:{type:'textbox',options:{required:false,precision:0,groupSeparator:','}}},
			    {field:'WORK_ORDER',title:'WORK ORDER', halign:'center', width:120},
			    {field:'SI_NO', title:'SI NO.', halign:'center'},
			    {field:'CRS_REMARK', title:'PPBE NO', halign:'center'},
			    {field:'SO_NO', title:'SO', halign:'center', width:50},
			    {field:'LINE_NO', title:'SO<br>LINE', halign:'center', align:'center', width:30},
                {field:'PO_NO', title:'CUST. PO NO.', halign:'center', width:70},
                {field:'PO_LINE_NO', title:'PO<br>LINE', halign:'center', align:'center', width:30},
                {field:'ITEM_NO', title:'ITEM<br/>NO.', halign:'center', align:'center', width:40},
                {field:'ITEM_NAME', title:'ITEM NAME', halign:'center', align:'left', width:100},
                {field:'CR_DATE', title:'CR<br>DATE', halign:'center', align:'center', width:50},

				{field:'STUFFY_DATE', title:'STUFFY<br>DATE', halign:'center', align:'center', width:50},
				{field:'ETD_ANS', title:'ETD<br>DATE', halign:'center', align:'center', width:50},
				{field:'ETA', title:'ETA<br>DATE', halign:'center', align:'center', width:50},
				{field:'SHIPPING_TYPE', title:'SHIPPING', halign:'center', align:'center', width:70},
				//{field:'CONTAINERS', title:'CONTAIN', halign:'center', align:'center', width:70},

                {field:'BATERY_TYPE', title:'BATT<br/>TYPE', halign:'center', align:'center', width:40},
                {field:'CELL_GRADE', title:'CELL<br>TYPE', halign:'center', align:'center', width:35},
                {field:'QTY_ORDER', title:'ORDER<br/>QTY', halign:'center', align:'right', width:50},
                {field:'QTY_PRODUKSI', title:'AVAILABLE<br/>QTY', halign:'center', align:'right', width:50},
                {field:'QTY_PRODUKSI_VALUE', hidden: true},
                {field:'QTY_PLAN', title:'PLANNED<br/>QTY', halign:'center', align:'right', width:50},
                {field:'QTY_PLAN_VALUE', hidden: true},
                {field:'QTY_INVOICED', title:'INVOICED<br/>QTY', halign:'center', align:'right', width:50},
                {field:'QTY_INVOICED_VALUE', hidden: true},
                {field:'ETD', hidden:true},
                {field:'CUSTOMER_CODE', hidden:true},
                {field:'CURR_CODE', hidden:true},
                {field:'U_PRICE', hidden:true},
                {field:'ITEM_SET', hidden:true}
                
			]]
		});

	});

	function sett_ppbe(){
		console.log('../json/json_kode_ppbe_to_shipping.php?user=<? echo $user_name; ?>');
		$.ajax({
			type: 'GET',
			url: '../json/json_kode_ppbe_to_shipping.php?user=<? echo $user_name; ?>',
			data: { kode:'kode' },
			success: function(data){
				$('#ppbe_no').textbox('setValue',data[0].kode);
			}
		});
	}

	function filterData(){			
		var ck_sample = "false";
		var ck_cr_date = "false";
		var ck_po_no = "false";
		var ck_wo_no = "false";
		var ck_item_no = "false";
		var ck_si = "false";
		var ck_ppbe = "false";
		var flag = 0;

		if ($('#ck_sample').attr("checked")) {
			ck_sample = "true";
		};

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

		if ($('#ck_si').attr("checked")) {
			ck_si = "true";
			flag += 1;
		};

		if ($('#ck_ppbe').attr("checked")) {
			ck_ppbe = "true";
			flag += 1;
		};
		
		if(flag == 6) {
			$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
		}		

		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			ck_cr_date: ck_cr_date,
			cmb_wo_no : $('#cmb_wo_no').combobox('getValue'),
			ck_wo_no: ck_wo_no,
			cmb_po_no : $('#cmb_po_no').combobox('getValue'),
			ck_po_no: ck_po_no,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no,
			cmb_ppbe: $('#cmb_ppbe').combobox('getValue'),
			ck_ppbe: ck_ppbe,
			flag: flag,
			ck_si: ck_si,
			cmb_si_no : $('#cmb_si_no').combobox('getValue'),
			ck_sample: ck_sample
		});
		
		console.log('shipping_plan_get.php?date_awal='+$('#date_awal').datebox('getValue')+
			'&date_akhir='+$('#date_akhir').datebox('getValue')+
			'&ck_cr_date='+ck_cr_date+
			'&cmb_wo_no='+$('#cmb_wo_no').combobox('getValue')+
			'&ck_wo_no='+ck_wo_no+
			'&cmb_po_no='+$('#cmb_po_no').combobox('getValue')+
			'&ck_po_no='+ck_po_no+
			'&cmb_item_no='+$('#cmb_item_no').combobox('getValue')+
			'&ck_item_no='+ck_item_no+
			'&cmb_ppbe='+$('#cmb_ppbe').combobox('getValue')+
			'&ck_ppbe='+ck_ppbe+
			'&flag='+flag+
			'&ck_si='+ck_si+
			'&ck_sample='+ck_sample+
			'&cmb_si_no='+$('#cmb_si_no').combobox('getValue')
		);

		$('#dg').datagrid( {
			url: 'shipping_plan_get.php',
	   		onEndEdit:function(index,row){
	            var ed = $(this).datagrid('getEditor', {
	                index: index,
	                field: 'SHIPPING'
	            });
        	},
	        onBeforeEdit:function(index,row){
	            row.editing = true;
	            $(this).datagrid('refreshRow', index);
	        },
	        onAfterEdit:function(index,row){
	            row.editing = false;
	            $(this).datagrid('refreshRow', index);
	        },
	        onCancelEdit:function(index,row){
	            row.editing = false;
	            $(this).datagrid('refreshRow', index);
	        }
		})

		$('#dg').datagrid('enableFilter');

		var col_1 = $('#dg').datagrid('getColumnOption','QTY_PLAN');
		var col_2 = $('#dg').datagrid('getColumnOption','QTY_PRODUKSI');
		var col_3 = $('#dg').datagrid('getColumnOption','QTY_INVOICED');

		col_1.styler = function(){
			return 'background-color:#AAFFFF; color: #000000; font-weight:bold;';
		};

		col_2.styler = function(){
			return 'background-color:#AAFFFF; color: #000000; font-weight:bold;';
		};

		col_3.styler = function(){
			return 'background-color:#AAFFFF; color: #000000; font-weight:bold;';
		};

		$('#add_shipping_plan').linkbutton('enable');
		$('#print_si').linkbutton('enable');
	}

	var cust_po_no = '';


	function recalculate_transport(){
			var wo = '';
			var ppbe = '';
			var item = '';
			var tot_amt_o = 0;
			var tot_amt_l = 0;
			var t = $('#dg_transport').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				$('#dg_transport').datagrid('endEdit',i);
				
				if($('#dg_transport').datagrid('getData').rows[i].CONTAINERS != ''){
					// alert($('#dg_transport').datagrid('getData').rows[i].CONTAINERS);
					rd = $('#dg_transport').datagrid('getData').rows[i].RD;
					
					if($('#dg_transport').datagrid('getData').rows[i].RD== ''){
						rd ='xxx';
					}else{
						wo = $('#dg_transport').datagrid('getData').rows[i].WO_NO;
						ppbe = $('#dg_transport').datagrid('getData').rows[i].PPBE_NO;
						item = $('#dg_transport').datagrid('getData').rows[i].ITEM_NO;
					};
					
					$.post('shipping_plan_recalculate.php',{
					
					PPBE: ppbe,
					ITEM_NO: item,
					QTY: $('#dg_transport').datagrid('getData').rows[i].QTY,
					WO: wo,
					RD: rd
					
					}).done(function(res){
						//alert(res);
						console.log(res);
					});
				}
				
			};
			set_container('X',ppbe,wo);
	}

	function set_container(x,z,wo){
		var si = $('#dg_add').datagrid('getData').rows[0].SI_NO;
		var y = $('#ppbe_no').textbox('getValue');
		var urlx = ''
		$('#dg_transport').datagrid('loadData',[]);

		if(x=='X'){
			urlx = 'shipping_plan_load_container.php?PPBE='+y+'&WO='+wo+''
		}else{
			urlx = 'shipping_plan_container.php?PPBE='+y+'&ITEM_NO='+x+'&QTY='+z+'&WO='+wo+'&SI='+si+''
		}
		
		if (y=='' || si== null){
			alert("Please fill PPBE or SI No first");
			return;
		};
		$('#dlg_transport').dialog('open').dialog('setTitle','Setting Container');
			// var row = calculate_con(x,y,z,wo);
			var rows = $('#dg_transport').datagrid('getRows');
			$('#dg_transport').datagrid( {
			url: urlx,
			
			singleSelect: true,
			
			columns:[[
			   	{field:'CONTAINERS', title:'SIZE TYPE', width:80, halign:'center', editor:{type:'combobox',options: {url: '../json/json_cargo_size.php',
					    																												panelHeight: '50px',
																										                    			valueField: 'SIZE_TYPE',
																										                    			textField: 'DESCRIPTION'
					    																											}
					    																					}
				},
				 {field:'QTY', title:'QTY', width:70, halign:'center', align:'right', editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:false}
																   				  			}
				},
				{field:'PALLET', title:'PALLET', width:70, halign: 'center', align: 'left',hidden: false},
				{field:'CONTAINER_VALUE', title:'ISI CONTAINER', width:70, halign: 'center', align: 'left',hidden: false},
				{field:'CARTON_NOT_FULL', title:'CTN NOT FULL', width:80, halign: 'center', align: 'left',hidden: false},
				{field:'NET', title:'NET', width:90, halign: 'center', align: 'center',hidden: false},
				{field:'GROSS', title:'GROSS', width:90, halign: 'center', align: 'center',hidden: false},
				{field:'MSM', title:'MSM', width:80, halign: 'center', align: 'center',hidden: false},
				{field:'WO_NO', title:'WO', width:80, halign: 'center', align: 'center',hidden: true},
				{field:'PPBE_NO', title:'PPBE', width:80, halign: 'center', align: 'center',hidden: true},
				{field:'RD', title:'RD', width:80, halign: 'center', align: 'center',hidden: true},
             
			]],
			 onClickRow:function(row){
			    	$(this).datagrid('beginEdit', row);
			    },
			onLoadSuccess: function (data) {
			   var rows = $('#dg_transport').datagrid('getRows');
			   for (i=0; i<=7; i++) {
			   	   if (i>rows.length){
			   	   		$('#dg_transport').datagrid('appendRow',{
					    CONTAINERS: '',
					    QTY: '',
					    PALLET: '',
					    CARTON_NOT_FULL: '',
					    NET: '',
					    GROSS: '',
					    MSM: '',
					    RD: '',
					  });
			   	   }
         		  
        		}
			}
			});	
			// alert(rows.length);
			
		    
							
	


	}

	// function calculate_con(x,y,z,wo){
	

	// 	return i;


	// };

	function print_si(){
		var ck_mark = "false";
		var flag = 0;

		if ($('#ck_mark').attr("checked")) {
			ck_mark = "true";
		};

		var arrSI = [];
    	var r = $('#dg').datagrid('getSelections');

		for(p=0;p<r.length;p++){
			arrSI.push(r[p].SI_NO+"-"+r[p].CRS_REMARK);
		}
		var SIexp = arrSI[0].split("-");

		if(SIexp[0] == null || SIexp[1] == null){
			$.messager.alert("Data not palnned");
		}else{
			window.open('invoice_print_si.php?si='+SIexp[0]+'&do='+SIexp[1]+'&si_sts=si&print_mark='+ck_mark+'');
		}
	}
	
	function addShippingPlan(){
		$('#ppbe_no').textbox('setValue','');
		var ArrItem = [];
		$('#dg_add').datagrid({
		    singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			
		    columns:[[
			    {field:'WORK_ORDER', title:'WORK ORDER', width:180, halign: 'center', align: 'left'},
			    {field:'ITEM_NO', title:'ITEM NAME', width:70, halign: 'center'},//, hidden: true},
			    {field:'PO_NO', title:'PO NO', width: 100, halign: 'center'},
			    {field:'SO_NO', title:'SO NO', width:70, halign: 'center', hidden: true},
			    {field:'LINE_NO', title:'LINE NO', width:70, halign: 'center', hidden: false},
			    {field:'DESCRIPTION', title:'DESCRIPTION', width: 180, halign: 'center'},
			    {field:'SI_NO', title:'SI NO', width: 100, halign: 'center'},
			    {field:'QUANTITY', title:'SHIP QTY', halign: 'center', width:80, align:'right', editor:{type:'textbox',options:{required:true,precision:0,groupSeparator:','}}},
			    {field:'VESSEL', title:'VESSEL', halign: 'center', width:200, align:'right', editor:{type:'textbox',options:{required:false,precision:0,groupSeparator:','}}},
			    {field:'CR_REMARK', title:'CR REMARK', halign: 'center', width:200, align:'right', editor:{type:'textbox',options:{required:false,precision:0,groupSeparator:','}}},
			    {field:'ETD_DATE', title: 'E.T.D', halign: 'center', width: 80, editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}
			    },
			    {field:'ETA_DATE', title: 'E.T.A', halign: 'center', width: 90, editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}
			    },
			    {field:'CR_DATE', title: 'CR Date', halign: 'center', width: 85, halign: 'center'
			    },
			    {field:'EX_FAC_DATE', title: 'EX FACT', halign: 'center', width: 85, editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}
			    },
			    {field:'CUSTOMER_CODE', hidden: true},
			    {field:'QTY_ORDER', hidden: true},
			    {field:'CURR_CODE', hidden:true},
                {field:'U_PRICE', hidden:true},
                {field:'PO_LINE_NO', hidden:true},
                {field:'CONTAINER', title:'CONTAINER',halign:'center', width:180, halign: 'center', align: 'center'},
		    ]],
		    onClickRow:function(row){
		    	$(this).datagrid('beginEdit', row);
		    }
		});
		
		$('#dg_add').datagrid('loadData',[]);	
		
		var idxfield=0;
		var flag = 0;
		var rows = $('#dg').datagrid('getRows'); 
    	var rows1 = $('#dg').datagrid('getSelections');

		for(iy=0;iy<rows1.length;iy++){
			// alert(rows1[iy].ETD);
			$('#dg').datagrid('endEdit');
			if (rows1[iy].ACTION_ADD == 'T'){
				if(rows1[iy].ITEM_SET == 'Y'){
					var angka =  rows1[iy].QTY_ORDER.replace(/,/g,'');
					var qty = parseInt(angka) / parseInt(rows1[iy].SHIPPING);
					cust_po_no = rows1[iy].PO_NO;
					qty = Math.ceil(qty);
					for(ix=0;ix<rows1[iy].SHIPPING;ix++){
						$('#dg_add').datagrid('insertRow',{
						index: idxfield,
						row: {
								WORK_ORDER: rows1[iy].WORK_ORDER,
								ITEM_NO: rows1[iy].ITEM_NO,
								DESCRIPTION: rows1[iy].ITEM_NAME,
								SI_NO: rows1[iy].SI_NO,
								VESSEL: '',
								CR_DATE: rows1[iy].CR_DATE,
								EX_FAC_DATE: '',
								QUANTITY: qty,
								PO_NO: rows1[iy].PO_NO,
								SO_NO: rows1[iy].SO_NO,
								PO_LINE_NO: rows1[iy].PO_LINE_NO,
								ETD_DATE: rows1[iy].ETD,
								ETA_DATE: '',
								CUSTOMER_CODE: rows1[iy].CUSTOMER_CODE,
								QTY_ORDER: rows1[iy].QTY_ORDER,
								CURR_CODE:rows1[iy].CURR_CODE,
								U_PRICE: rows1[iy].U_PRICE,
								LINE_NO: rows1[iy].LINE_NO,
								CONTAINER : '<a href="javascript:void(0)" onclick="set_container('+rows1[iy].ITEM_NO+','+qty+',\''+rows1[iy].WORK_ORDER+'\')"  style="text-decoration: none; ">SET</a>'

								
								
							 }
						});
				 	}
				}else{
					ArrItem.push(rows1[iy].ITEM_NO);
					//$.messager.alert('INFORMATION','item no. '+rows1[iy].ITEM_NO+' not setting pallet','info');
				}
			}
		}

		var total_n = ArrItem.length;
		if (total_n > 0){
			$.messager.alert('INFORMATION','item no. '+ArrItem+' not setting pallet','info');
		}

		var t = $('#dg_add').datagrid('getRows');
		var total = t.length;

		if (total > 0){
			$('#dlg_add').dialog('open').dialog('setTitle','Shipping Plan Add');
		}else{
			$.messager.alert('INFORMATION','shipping plan not selected or<br/>stock not available or<br/>Shipping Plan Already exists','info');
		}
	}

	function copy_data(){
		var t = $('#dg_add').datagrid('getRows');
		var total = t.length;
		
		for(i=0;i<total;i++){
			$('#dg_add').datagrid('endEdit',i);
			if (i == 0){
				if($('#dg_add').datagrid('getData').rows[i].ETA_DATE == '' || $('#dg_add').datagrid('getData').rows[i].EX_FAC_DATE == ''){
					$.messager.alert('SHIPPING PLAN', 'data row 1st not found','info');
				}
			}else{
				//if($('#dg_add').datagrid('getData').rows[0].ETA_DATE == '' || $('#dg_add').datagrid('getData').rows[0].EX_FAC_DATE == ''){
				//	$.messager.alert('SHIPPING PLAN', 'data row 1st not found','info');
				//}else{
					$('#dg_add').datagrid('updateRow', {
			            index: i,
			            row: {
			            	VESSEL: $('#dg_add').datagrid('getData').rows[0].VESSEL,
			            	ETD_DATE: $('#dg_add').datagrid('getData').rows[0].ETD_DATE,
			                ETA_DATE: $('#dg_add').datagrid('getData').rows[0].ETA_DATE,
			                CR_DATE: $('#dg_add').datagrid('getData').rows[0].CR_DATE,
			                EX_FAC_DATE: $('#dg_add').datagrid('getData').rows[0].EX_FAC_DATE
			            }
			        });
				//}
			}
		}
	}

	function add_SI(){
		$('#dlg_si').dialog('open').dialog('setTitle','SHIPPING INSTRUCTION ADD (CUST PO NO : '+cust_po_no+')');
		console.log('shipping_plan_get_si.php?cust_po_no='+cust_po_no+'&ck_sample='+ck_sample);
		$('#dg_si').datagrid({
			url: 'shipping_plan_get_si.php?cust_po_no='+cust_po_no+'&ck_sample='+ck_sample,
		    singleSelect: true,
			rownumbers: true,
			fitColumns: true,
		    columns:[[
			    {field:'SI_NO', title:'SI NO.', width:80, halign: 'center', align: 'left'},
			    {field:'PLACE_DELI_CODE', title:'PLACE<br/>OF DELIVERY', width:70, halign: 'center'},
			    {field:'CONSIGNEE_NAME', title:'CONSIGNE', width: 150, halign: 'center'},
			    {field:'NOTIFY_NAME', title:'NOTIFY<br/>PARTY(1)', width:100, halign: 'center'},
			    {field:'NOTIFY_NAME_2', title:'NOTIFY<br/>PARTY(2)', width:100, halign: 'center'},
			    {field:'FORWARDER_NAME', title:'TO', width: 150, halign: 'center'},
			    {field:'EMKL_NAME', title:'EMKL', width: 150, halign: 'center'}
		    ]],
		    onDblClickRow:function(id,row){
				var t = $('#dg_add').datagrid('getRows');
				var total = t.length;
			   	var idxfield=0;
				if (parseInt(total) == 0) {
					idxfield=0;
				}else{
					idxfield=total;
				}

				for(i=0;i<total;i++){
					$('#dg_add').datagrid('updateRow', {
			            index: i,
			            row: {
			                SI_NO: row.SI_NO
			            }
			        });
				}
				$('#dlg_si').dialog('close');

				$('#dg_add').datagrid({
					onClickRow:function(row_add){
						$(this).datagrid('beginEdit', row_add);
						$(this).datagrid('endEdit', row_add);
					}
				});
			}
		});
	}

	function SI_entry(){
		var session = '<?php echo $user_name ?>';
		window.open('../si/si.php?id=1002');
	}

	var SIno = '';

	function simpan(){
		$.messager.progress({
            msg:'save data...'
        });

		var t = $('#dg_add').datagrid('getRows');
		var ppbe = $('#ppbe_no').textbox('getValue');
		var total = t.length;
		var flag = 0;
		for(i=0;i<total;i++){
			$('#dg_add').datagrid('endEdit',i);
			if ($('#dg_add').datagrid('getData').rows[i].ETA_DATE != undefined & $('#dg_add').datagrid('getData').rows[i].QUANTITY != undefined &  $('#dg_add').datagrid('getData').rows[i].EX_FAC_DATE != undefined & $('#dg_add').datagrid('getData').rows[i].ETD_DATE != undefined & $('#dg_add').datagrid('getData').rows[i].SI_NO != null
				&& ppbe != ''){
				flag = flag + 1;
			}		
		}

		if (flag  != i) {
			$.messager.alert("INFORMATION","Data is not completed fill, please check Field SI, CR Date, ETA Date, EX FACT Date or Quantity again","info");
			$.messager.progress('close');
		}else{
			for(i=0;i<total;i++){
				SIno = $('#dg_add').datagrid('getData').rows[i].SI_NO;
				
				console.log($('#dg_add').datagrid('getData').rows[i].CR_DATE);

				$.post('shipping_plan_save.php',{
					WORK_ORDER: $('#dg_add').datagrid('getData').rows[i].WORK_ORDER,
					ITEM_NO: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
					ITEM_NAME: $('#dg_add').datagrid('getData').rows[i].DESCRIPTION,
					SI_NO: $('#dg_add').datagrid('getData').rows[i].SI_NO,
					QUANTITY: $('#dg_add').datagrid('getData').rows[i].QUANTITY,
					CR_DATE: $('#dg_add').datagrid('getData').rows[i].CR_DATE,
					ETA_DATE: $('#dg_add').datagrid('getData').rows[i].ETA_DATE,
					EX_FAC_DATE: $('#dg_add').datagrid('getData').rows[i].EX_FAC_DATE,
					ETD_DATE: $('#dg_add').datagrid('getData').rows[i].ETD_DATE,
					SO_NO: $('#dg_add').datagrid('getData').rows[i].SO_NO,
					PO_NO: $('#dg_add').datagrid('getData').rows[i].PO_NO,
					PO_LINE_NO: $('#dg_add').datagrid('getData').rows[i].PO_LINE_NO,
					VESSEL: $('#dg_add').datagrid('getData').rows[i].VESSEL,
					LINE_NO: $('#dg_add').datagrid('getData').rows[i].LINE_NO,
					CUST_CODE: $('#dg_add').datagrid('getData').rows[i].CUSTOMER_CODE,
					QTY_ORDER: $('#dg_add').datagrid('getData').rows[i].QTY_ORDER.replace(/,/g,''),
					CR_REMARK: $('#dg_add').datagrid('getData').rows[i].CR_REMARK,
					CURR_CODE:$('#dg_add').datagrid('getData').rows[i].CURR_CODE,
					U_PRICE: $('#dg_add').datagrid('getData').rows[i].U_PRICE,
					PPBE: ppbe
				}).done(function(res){
					console.log(res);
				});
			}
			
	
			//$.messager.progress('close');
			//$.messager.alert("INFORMATION","Shipping Plan Created","info");	
			$.messager.confirm('Confirm','Do you want to print SI Forwarder?',function(r){
				if(r){
					$.messager.progress('close');
					$('#dlg_print').dialog('open').dialog('setTitle','Print Properties ('+$("#ppbe_no").textbox("getValue")+')');
				}
			});	
		}
	}

	function savedata(){
		var ppbe = $('#ppbe_no').textbox('getValue');
		$.ajax({
			type: 'GET',
			url: '../json/json_cek_kode_ppbe.php?ppbe='+ppbe,
			data: { kode:'kode' },
			success: function(data){
				if(data[0].kode!='SUCCESS'){
					$.messager.alert('Warning',data[0].kode,'warning');
				}
				simpan();
			}
		});
	}

	function print(){
		var ppbe = $("#ppbe_no").textbox("getValue");
		if(document.getElementById('si').checked == true){
			s_type = 'si';
		}else if(document.getElementById('ppbe').checked == true){
			s_type = document.getElementById('ppbe').value;
		}

		if (s_type == ''){
			$.messager.show({title: 'SHIPPING',msg: 'Data Not select to Print'});
		}else{
			if (s_type == 'si'){
				window.open('invoice_print_si.php?si='+SIno+'&do='+ppbe+'&si_sts=si');
			}else{
				window.open('invoice_print_ppbe.php?si='+SIno+'&ppbe='+ppbe);
			}
		}
	}

	function close_print(){
		$('#dlg_print').dialog('close');
		$('#dlg_add').dialog('close');
		$('#dg').datagrid('reload');
	}

	function link_to_beakdown(){
		window.location.href = 'breakdown_container.php?id=701';
	}

	function link_to_packinglist(){
		window.location.href = 'packing_list.php';
	}
	//=============================END WENK. =========================

	function getRowIndex(target){
	    var tr = $(target).closest('tr.datagrid-row');
	    return parseInt(tr.attr('datagrid-row-index'));
	}

	function addrow(target){
	    $('#dg').datagrid('beginEdit', getRowIndex(target));
	}

	function saverow(target){
	    $('#dg').datagrid('endEdit', getRowIndex(target));
	}

	function info_kuraire(a){
		$('#dlg_viewKur').dialog('open').dialog('setTitle','VIEW INFO KURAIRE');
		$('#dg_viewKur').datagrid({
			url: 'shipping_plan_info_kur.php?work_order='+a+'',
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'WO_NO', title:'WORK ORDER', width:130, halign: 'center', align: 'center'},
			    {field:'PLT_NO', title:'PALLET', width: 60, halign: 'center', align: 'center'},
			    {field:'ITEM_NO', title:'ITEM NO.', width: 80, halign: 'center', align: 'center'},
			    {field:'ITEM_DESCRIPTION', title:'DESCRIPTION', width: 200, halign: 'center'},
			    {field:'SCAN_DATE', title:'SCAN TIME', width: 150, halign: 'center'},
			    {field:'SLIP_TYPE', title:'SLIP TYPE', width: 70, halign: 'center', align: 'center'},
			    {field:'SLIP_QUANTITY', title:'QTY', width: 100, halign: 'center', align: 'right'},
			    {field:'APPROVAL_DATE', title:'APPROVAL DATE', width:100, halign: 'center'}
			]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		});
	}

	function info_plan(a,b){
		$('#dlg_viewPln').dialog('open');
		$('#dg_viewPln').datagrid({
			url: 'shipping_plan_info_plan.php?work_order='+a+'&ppbe='+b,
			singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'AM',title:'AMEND',width:70,align:'center',
	                formatter:function(value,row,index){
	                    if (row.editing){
	                        var se = '<a href="javascript:void(0)" onclick="saverowPlan(this)">Save</a> ';
	                        return se;
	                    } else {
	                        var ee = '<a href="javascript:void(0)" onclick="addrowPlan(this)">Editing</a> ';
	                        return ee;
	                    }
	                }
	            },
			    {field:'WO_NO', title:'WORK ORDER.', width:150, halign: 'center'},
			    {field:'CRS_REMARK', title:'PPBE NO.', hidden: false},
			    {field:'ITEM_NO', title:'ITEM<br>NO.', width: 70, halign: 'center', align: 'center'},
			    {field:'ITEM_NAME', title:'DESCRIPTION', width: 200, halign: 'center'},
			    {field:'VESSEL', title:'VESSEL', width:170, halign: 'center',editor:{type:'textbox'}},
			    {field:'CR_DATE', title:'CR<br/>DATE', width: 80, halign: 'center'},
			    {field:'ETA_FORMAT', title:'ETA', width: 80, halign: 'center',editor:{
			    																	type:'datebox',
			    																	options:{formatter:myformatter,parser:myparser}
			    																}},
			    {field:'ETD_FORMAT', title:'ETD', width: 80, halign: 'center', align: 'right',editor:{
			    																	type:'datebox',
			    																	options:{formatter:myformatter,parser:myparser}
			    																}},
			    {field:'EX_FACT_FORMAT', title:'EX FACT<br/>DATE', width:80, halign: 'center',editor:{
			    																	type:'datebox',
			    																	options:{formatter:myformatter,parser:myparser}
			    																}},
			    {field:'QTY', title:'QTY', width:65, halign: 'center', align: 'right',editor:{type:'numberbox',options:{precision:0,groupSeparator:','}}},
			    {field:'CRS_REMARK', title:'PPBE<br/>NO.', width: 70, halign: 'center', editor:{type:'textbox'}},
			    {field:'ANSWER_NO', title:'ANSWER<br/>NO.', width: 70, halign: 'center'},
			    {field:'DEL',title:'DELETE',width:70,align:'center',
	                formatter:function(value,row,index){
	                	var ef = '<a href="javascript:void(0)" onclick="deleterow(this)">Delete</a> ';
	                	return ef;
	                }
            	},
             	{field:'RD', title:'ROWID', width: 80, halign: 'center',hidden:true},
             	{field:'SO_NO', title:'so no.', hidden: true},//
             	{field:'LINE_NO', title:'line no.', hidden: true},//
             	//
             	{field:'SI_NO', title:'line no.', hidden: true}//
			]],
		onEndEdit:function(index,row){
           var ed = $(this).datagrid('getEditor', {
                index: index,
                field: 'ETA'
            });
          
        },
        onBeforeEdit:function(index,row){
            row.editing = true;
            
            $(this).datagrid('refreshRow', index);
            var row = $('#dg_viewPln').datagrid('getSelected');	
            row.ETD = ETD;
            row.ETA = ETD;
            row.EX_FACT = ETD;

        },
        onAfterEdit:function(index,row){
            row.editing = false;
            $(this).datagrid('refreshRow', index);
        },
        onCancelEdit:function(index,row){
            row.editing = false;
            $(this).datagrid('refreshRow', index);
        }
		});
	}	

	var ETD = ""
	function addrowPlan(target){
		var row = $('#dg_viewPln').datagrid('getSelected');	
		ETD = row.ETD;
	    $('#dg_viewPln').datagrid('beginEdit', getRowIndexplan(target));
	    row.ETD = formattgl(ETD);

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

	function saverowPlan(target){
	    $('#dg_viewPln').datagrid('endEdit', getRowIndexplan(target));
	    var row = $('#dg_viewPln').datagrid('getSelected');	
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to update?',function(r){
				if(r){
					$.messager.progress({
	                	title:'Please waiting',
	                	msg:'Loading data...'
		            });

		            var idx = $("#dg_viewPln").datagrid("getRowIndex", row);

		            console.log('shipping_plan_iu.php?type=IU&ID='+row.RD+
						'&CR_DATE='+row.CR_DATE+
						'&ETA='+row.ETA_FORMAT+
						'&ETD='+row.ETD_FORMAT+
						'&EX_FACT='+row.EX_FACT_FORMAT+
						'&QTY='+row.QTY.replace(/,/g,'')+
						'&SO_NO='+row.SO_NO+
						'&LINE_NO='+row.LINE_NO+
						'&ANSWER_NO='+row.ANSWER_NO+
						'&CRS_REMARK='+row.CRS_REMARK+
						'&VESSEL='+row.VESSEL+
						'&SI_NO='+row.SI_NO);
					
                    $.post('shipping_plan_iu.php',{
                    	type: 'IU',
						ID: row.RD,
						CR_DATE : row.CR_DATE,
						ETA : row.ETA_FORMAT,
						ETD : row.ETD_FORMAT,
						EX_FACT : row.EX_FACT_FORMAT,
						QTY : row.QTY.replace(/,/g,''),
						SO_NO: row.SO_NO,
						LINE_NO: row.LINE_NO,
						ANSWER_NO: row.ANSWER_NO,
						CRS_REMARK: row.CRS_REMARK,
						VESSEL: row.VESSEL,
						SI_NO: row.SI_NO
                    },function(result){
					    if (result.successMsg == 'IU'){
					        $.messager.progress('close');
							$.messager.alert('INFORMATION','UPDATE DATA SUCCESS','info');
							$('#dlg_viewPln').dialog('close');
							$('#dg').datagrid('reload');
					    }else{
					    	$.messager.progress('close');
					        $.messager.show({
					            title: 'Error',
					            msg: result.errorMsg
					        });
					    }
					},'json');
				}	
			});
		}
	}

	function deleterow(target){
		var row = $('#dg_viewPln').datagrid('getSelected');	
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
				if(r){
					console.log(row.RD);
					var idx = $("#dg_viewPln").datagrid("getRowIndex", row);
					$('#dg_viewPln').datagrid('deleteRow', idx);
					$.post('shipping_plan_iu.php',{
						type: 'DEL',
						ID: row.RD,
						CR_DATE : row.CR_DATE,
						ETA : row.ETA,
						ETD : row.ETD,
						EX_FACT : row.EX_FACT,
						QTY : row.QTY.replace(/,/g,''),
						SO_NO: row.SO_NO,
						LINE_NO: row.LINE_NO,
						ANSWER_NO: row.ANSWER_NO
					}).done(function(res){
						console.log(res);
						if (res.length < 10){
							$.messager.alert('INFORMATION','DELETE DATA SUCCESS','info');
						}else{
							$.messager.alert('ERROR',res,'warning');
						}
					});
					$('#dg').datagrid('reload');
				}	
			});
		}
	}

	function info_invoiced(a){
		$('#dlg_viewInv').dialog('open').dialog('setTitle','VIEW INFO INVOICE');
		//alert('mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan);
		$('#dg_viewInv').datagrid({
			url: 'shipping_plan_info_inv.php?work_order='+a+'',
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'CUSTOMER_PO_NO', title:'PO NO.', width:115, halign: 'center', align: 'center'},
			    {field:'ETD', title:'ETD', width: 80, halign: 'center', align: 'center'},
			    {field:'ETA', title:'ETA', width: 80, halign: 'center', align: 'center'},
			    {field:'CR_DATE', title:'CARGO READY', width: 80, halign: 'center', align: 'center'},
			    {field:'DO_NO', title:'INVOICE NO.', width: 150, halign: 'center'},
			    {field:'ITEM_NO', title:'ITEM NO.', width: 70, halign: 'center'},
			    {field:'QTY', title:'QTY', width: 100, halign: 'center', align: 'right'}]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		});
	}

	function getRowIndexplan(target){
	    var tr = $(target).closest('tr.datagrid-row');
	    return parseInt(tr.attr('datagrid-row-index'));
	}
</script>

</body>
</html>
<?php }else{	
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}