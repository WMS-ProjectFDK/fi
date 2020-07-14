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
<title>MPS EDIT</title>
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
</style>
</head>

<body>
<?php include ('../ico_logout.php'); ?>
<div id="toolbar" style="padding:3px 3px;">
<fieldset style="margin-left;border-radius:4px;height:70px;width:98%"><legend><span class="style3"><strong> MPS Edit </strong></span></legend>
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
				<select style="width:273px;" name="cmb_wo_no" id="cmb_wo_no" class="easyui-combobox" data-options=" url:'json/json_mps.php',method:'get',valueField:'work_order',textField:'work_order', panelHeight:'150px'"></select>
				<label><input type="checkbox" name="ck_wo_no" id="ck_wo_no" checked="true">All</input></label>
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
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
	
	</fieldset>
</div>

<table id="dg" title="MPS Edit" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" selectOnCheck= "true">	
</table>

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
			 <!-- data-options=" url:'json/json_bom_level.php', method:'get', valueField:'level_no', textField:'level_no', panelHeight:'150px'"/>  -->
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="openbom()" style="width:50px;"><i class="" aria-hidden="true"></i> view</a>
			
		</div>
		<!-- <span style="width:300px;display:inline-block;"></span>
		<span style="width:300px;display:inline-block;"></span> -->
		<!-- <input style="width:50px; " name="po_no_old_edit" id="po_no_old_edit" class="easyui-textbox" visibility="hidden" /> 
		<input style="width:50px;" name="po_line_no_old_edit" id="po_line_no_old_edit" class="easyui-textbox" visibility="hidden" />  -->

	</fieldset>		
	<div class="fitem" style="margin-right">
			<span style="width:100px;display:inline-block;"></span>
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="saveheader()" style="width:120px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
			<span style="width:10px;display:inline-block;"></span>
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onclick="$('#dlg_header').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>
	<div >
</div>

<div id='dlg_detail' class="easyui-dialog" style="width:450px;height:480px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">
		<table id="dg_detail" class="easyui-datagrid" style="width:99%;height:90%;">  </table>	
		<span style="width:380px;display:inline-block;"></span>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="savedetail()" style="width:120px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>	
		<span style="width:20px;display:inline-block;"></span>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="addrowdetail()" style="width:120px;"><i class="fa fa-next" aria-hidden="true"></i> Add  Row</a>	
		<span style="width:20px;display:inline-block;"></span>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="$('#dlg_detail').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>	
			
</div>

<div id='dlg_split' class="easyui-dialog" style="width:500px;height:520px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-si" data-options="modal:true">

			<span style="width:100px;display:inline-block;">Work Order :</span>
			<input style="width:150px;" name="wo_no_split_edit" id="wo_no_split_edit" class="easyui-textbox"  required="true"/>
			<span style="width:10px;display:inline-block;"></span>
			<span style="width:100px;display:inline-block;">QTY Order :</span>
			<input style="width:50px;" name="qty_order_split_edit" id="qty_order_split_edit" class="easyui-numberbox"  required="true"/>
			<span style="width:10px;display:inline-block;"></span>

			<span style="width:100px;display:inline-block;">PO No. :</span>
			<input style="width:150px;" name="po_no_split_edit" id="po_no_split_edit" class="easyui-textbox"  required="true"/>
			<span style="width:10px;display:inline-block;"></span>
			<span style="width:100px;display:inline-block;">Line No. :</span>
			<input style="width:50px;" name="line_no_split_edit" id="line_no_split_edit" class="easyui-numberbox"  required="true"/>
			<span style="width:10px;display:inline-block;"></span>


			<span style="width:100px;display:inline-block;">Split Into :</span>
			<input style="width:50px;" name="split_edit" id="split_edit" class="easyui-numberbox"  required="true"/>
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="split_simulasi()" style="width:100px;"><i class="fa fa-back" aria-hidden="true"></i> Split</a>	 	
			
			<table id="dg_split" class="easyui-datagrid" style="width:98%;height:75%;">  </table>	
			<span style="width:380px;display:inline-block;"></span>
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="split_save()" style="width:120px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>	
			<span style="width:20px;display:inline-block;"></span>
			<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="$('#dlg_split').dialog('close')" style="width:120px;"><i class="fa fa-back" aria-hidden="true"></i> Exit</a>	
		
</div>

<script type="text/javascript">


function split_save(){
		var rows = $('#dg_split').datagrid('getRows');
		var dataRows_Edit = [];
		var total = 0;
		var percent = 0;
		for(var i=0; i<rows.length; i++){
			$('#dg_split').datagrid('endEdit',i);
			total = total + parseInt(rows[i]['QTY']);
			percent = parseInt(percent + (rows[i]['QTY'] / $('#qty_order_split_edit').numberbox('getValue') *100));
			new_wo = rows[i]['WORK_ORDER'];
			new_qty = rows[i]['QTY'];

			if(i+1==rows.length){
				percent = 100- percent;	
				dataRows_Edit.push ({
				percent: parseInt(rows[i]['QTY'] / $('#qty_order_split_edit').numberbox('getValue') *100) + percent,
				new_wo: rows[i]['WORK_ORDER'],
				new_qty: rows[i]['QTY'],
				po_line_no: i+1,
				old_wo: $('#wo_no_split_edit').textbox('getValue'),
				old_po: $('#po_no_split_edit').textbox('getValue'),
				old_po_line_no: $('#line_no_split_edit').textbox('getValue')
			});		
			}else{
				dataRows_Edit.push ({
				percent: parseInt(rows[i]['QTY'] / $('#qty_order_split_edit').numberbox('getValue') *100) ,
				new_wo: rows[i]['WORK_ORDER'],
				new_qty: rows[i]['QTY'],
				po_line_no: i+1,
				old_wo: $('#wo_no_split_edit').textbox('getValue'),
				old_po: $('#po_no_split_edit').textbox('getValue'),
				old_po_line_no: $('#line_no_split_edit').textbox('getValue')
			});
			}

			

		}

		if(total != $('#qty_order_split_edit').numberbox('getValue')){
			$.messager.alert('ERROR','Please check again the quantity '+ total +', not same with order '+ $('#qty_order_split_edit').numberbox('getValue') +'.','warning');
		}else{
			var myJSON_e=JSON.stringify(dataRows_Edit);
			var str_unescape_e=unescape(myJSON_e);
			
			$.post('mps_edit_split.php',{
						data: unescape(str_unescape_e)
					}).done(function(res){
						if(res == '"success"'){
							$('#dlg_detail').dialog('close');
							$('#dg').datagrid('reload');
							$.messager.alert('INFORMATION','Update Data Success..!!','info');
							$.messager.progress('close');
						}else{
							$.messager.alert('ERROR',res,'warning');
							$.messager.progress('close');
						}
					});
			
		}
	}

function openbom(){
		var item_no = $("#item_no_edit").textbox('getValue');
		var goto = 'http://172.23.206.21/pglosas/master/parts/item/item_mentenance.cgi?&ITEM_EDPKEY='+item_no+'&job_type=&WK_TYPE=SHOW';
		window.open(goto);
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
		
		// $('#item_no_edit').textbox({
		// 	onChange: function(newValue,oldValue){
		// 		var nval = newValue;
		// 		var data;
		// 		if(nval != ''){
		// 			$.ajax({
		// 				type: 'GET',
		// 				dataType: "json",
		// 				url: 'json/json_bom_level.php?item=63270',
		// 				data: data,
		// 				success: function(data){
		// 					alert(data[0].LEVEL_NO);
		// 					// $('#bom_level_edit').combobox('setValue',data[0].LEVEL_NO);
		// 				}
		// 			})
		// 		}
		// 	}
		// });
		

        $('#dg').datagrid( {
			singleSelect: true,
			showFooter: true,
			columns:[[
		    	
            	{field:'WORK_ORDER',title:'WORK ORDER', halign:'center', width:110},
                {field:'PO_NO', title:'CUST. PO NO.', halign:'center', width:70},
                {field:'PO_LINE_NO', title:'PO<br>LINE', halign:'center', align:'center', width:30},
                {field:'ITEM_NO', title:'ITEM<br/>NO.', halign:'center', align:'center', width:40},
                {field:'ITEM_NAME', title:'ITEM NAME', halign:'center', align:'left', width:100},
				{field:'STATUS', title:'STATUS', halign:'center', align:'left', width:30},
				{field:'BOM_LEVEL', title:'BOM<br>LEVEL', halign:'center', align:'center', width:30},
				{field:'DATE_CODE', title:'DATE<br>CODE', halign:'center', align:'left', width:30},
				{field:'CR_DATE', title:'CR DATE', halign:'center', align:'left', width:35},
                {field:'BATERY_TYPE', title:'BATERY<br/>TYPE', halign:'center', align:'center', width:30},
                {field:'CELL_GRADE', title:'GRADE', halign:'center', align:'center', width:25},
                {field:'QTY', title:'ORDER<br/>QTY', halign:'center', align:'right', width:45},
				{field:'EDIT_HEADER',title:'EDIT<br>HEADER', halign:'center',align:'center', width:40},
				{field:'EDIT_DETAIL',title:'EDIT<br>DETAIL', halign:'center',align:'center', width:40},
				{field:'SPLIT_WO',title:'SPLIT<br>WO', halign:'center',align:'center', width:40}
				
                
			]]
			// ,view: detailview,
			// 	detailFormatter: function(rowIndex, rowData){
			// 		return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listmps"></table></div>';
			// 	},
			// 	onExpandRow: function(index,row){
			// 		var listmps = $(this).datagrid('getRowDetail',index).find('table.listmps');
			// 		listmps.datagrid({
	        //         	title: 'Plan Detail (No: '+row.PO_NO+')',
	        //         	url:'mps_edit_get_detail.php?po_no='+row.PO_NO+'&po_line_no='+row.PO_LINE_NO,
			// 			toolbar: '#ddv'+index,
			// 			singleSelect:true,
			// 			loadMsg:'load data ...',
			// 			height:'auto',
			// 			columns:[[
			// 				{field:'PO_NO',title:'PO NO.',width:150,halign:'center', align:'center'},
			//                 {field:'PO_LINE_NO',title:'Line No.', halign:'center', align:'center', width:120, sortable: true},
			//                 {field:'MPS_DATE', title:'DATE', halign:'center', width:150},
			//                 {field:'MPS_QTY', title:'QTY', halign:'center', width:150},
			                
			// 			]],
			// 			onResize:function(){
			// 			$('#dg').datagrid('fixDetailRowHeight',index);
			// 			},
			// 			onLoadSuccess:function(){
			// 				setTimeout(function(){
			// 					$('#dg').datagrid('fixDetailRowHeight',index);
			// 				},0);
			// 			},
						
	        //         });
			// 	}
		});

		$('#dg_split').datagrid( {
			singleSelect: true,
			columns:[[
		    	
            	{field:'WORK_ORDER',title:'WORK ORDER', halign:'center', width:270},
                {field:'QTY', title:'ORDER<br/>QTY', halign:'center', align:'right', width:175,editor:{type:'textbox',options:{required:true,precision:0,groupSeparator:','}}},
				{field:'PERSEN',title:'PERCENT', halign:'center',align:'center', width:100,hidden: true}
				
                
			]],
				onClickRow:function(row){
		    	$(this).datagrid('beginEdit', row);
				
				}
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

	function savedetail(){
		var dataRows_Edit = [];
		var t = $('#dg_detail').datagrid('getRows');
		var total = t.length;
		var qtytotal = 0;
		var qtyorder = 0;
		
		for(i=0;i<total;i++){
			$('#dg_detail').datagrid('endEdit',i);
			qtytotal = qtytotal + parseFloat($('#dg_detail').datagrid('getData').rows[i].MPS_QTY.replace(/,/g,''));
			qtyorder = $('#dg_detail').datagrid('getData').rows[i].QTY.replace(/,/g,'')
			dataRows_Edit.push ({
							po_no: $('#dg_detail').datagrid('getData').rows[i].PO_NO,
							po_line_no: $('#dg_detail').datagrid('getData').rows[i].PO_LINE_NO,
							mps_date: $('#dg_detail').datagrid('getData').rows[i].MPS_DATE,
							old_mps_date: $('#dg_detail').datagrid('getData').rows[i].OLD_MPS_DATE,
							mps_qty: $('#dg_detail').datagrid('getData').rows[i].MPS_QTY.replace(/,/g,''),
							edit_type: $('#dg_detail').datagrid('getData').rows[i].EDIT_TYPE
						});
		}
		
		var myJSON_e=JSON.stringify(dataRows_Edit);
		var str_unescape_e=unescape(myJSON_e);
		
		if (parseFloat(qtytotal) != parseFloat(qtyorder)){
			$.messager.alert('ERROR','Quantity Tidak Sama Dengan Total Order, <br>Total Order = '+ parseFloat(qtyorder) +' <br>Order Plan = '+ parseFloat(qtytotal) +'','warning');
		}else{
			
			if (checkdate()== 0){
				$.post('mps_edit_save_detail.php',{
						data: unescape(str_unescape_e)
					}).done(function(res){
						if(res == '"success"'){
							$('#dlg_detail').dialog('close');
							$('#dg').datagrid('reload');
							$.messager.alert('INFORMATION','Update Data Success..!!','info');
							$.messager.progress('close');
						}else{
							$.messager.alert('ERROR',res,'warning');
							$.messager.progress('close');
						}
					});
			}else{
				
				$.messager.alert('warning','Ada Tanggal yang sama !');
			}
		};

		
		

	};

	function saveheader(){
		var dataRows_Edit = [];
		var edit = $('#ck_edit_bom').attr("checked");
		dataRows_Edit.push ({
							po_no: $('#po_no_edit').textbox('getValue'),
							po_line_no: $('#po_line_no_edit').textbox('getValue'),
							cr_date: $('#cr_date_date').datebox('getValue'),
							date_code: $('#date_code_edit').textbox('getValue'),
							qty: $('#qty_edit').textbox('getValue'),
							status: $('#status_box').combobox('getValue'),
							item: $('#item_no_edit').textbox('getValue'),
							bom: $('#bom_level_edit').textbox('getValue'),
							edit: edit
						});
		var myJSON_e=JSON.stringify(dataRows_Edit);
		var str_unescape_e=unescape(myJSON_e);
		
		if ( $('#bom_level_edit').textbox('getValue') != ""
			 && $('#item_no_edit').textbox('getValue') != ""
			 && $('#qty_edit').textbox('getValue') != ""
			 && $('#date_code_edit').textbox('getValue') != ""
			){

				$.post('mps_edit_save_header.php',{
					data: unescape(str_unescape_e)
				}).done(function(res){
					if(res == '"success"'){
						$('#dlg_header').dialog('close');
						$('#dg').datagrid('reload');
						$.messager.alert('INFORMATION','Update Data Success..!!<br/>WO No. : '+$('#po_no_edit').textbox('getValue')+' and Line no '+$('#po_line_no_edit').textbox('getValue'),'info');
						$.messager.progress('close');
					}else{
						$.messager.alert('ERROR',res,'warning');
						$.messager.progress('close');
					}
				});

			}else{
				$.messager.alert('ERROR','Please fill all the data.','warning');
			};
		
	}
	
	function addrowdetail(){
		if(checkdate()==0){
			var rows = $('#dg_detail').datagrid('getRows'); 
		
			$('#dg_detail').datagrid('appendRow',{
				EDIT_TYPE: 1,
				PO_NO: rows[0].PO_NO,
				PO_LINE_NO: rows[0].PO_LINE_NO,
				MPS_QTY: 0,
				OLD_MPS_DATE: rows[0].OLD_MPS_DATE,
				QTY: rows[0].QTY

			});
		}else{
			$.messager.alert('warning','Ada Tanggal yang sama !');
		}
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
        
        // alert("Please very becareful using this function.");
		// alert(ck_cr_date);
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
			url: 'mps_edit_get.php'
		})

		$('#dg').datagrid('enableFilter');


		
    }


	function editheader(po_no,line,date_code,status,cr_date,qty,wo,bom,item,edit){
		
		
		$('#dlg_header').dialog('open').dialog('setTitle','EDIT HEADER WO NO '+wo+'');
		$("#wo_no_edit").textbox('setValue', wo);
		$("#item_no_edit").textbox('setValue', item);
		$("#po_no_edit").textbox('setValue', po_no);
		$("#po_line_no_edit").textbox('setValue', line);
		$("#date_code_edit").textbox('setValue', date_code);
		$("#bom_level_edit").textbox('setValue', bom);
		$("#qty_edit").textbox('setValue', qty);
		$("#cr_date_date").datebox('setValue', formattgl(cr_date));
		$('#status_box').combobox('setValue', status);
		$('#status_box').combobox('setText', status);
		$('#wo_no_edit').textbox('disable');
		$('#po_no_edit').textbox('disable');
		$('#po_line_no_edit').textbox('disable');	
		if(edit=='EDIT'){
			$('#ck_edit_bom').attr("checked", true) ;
		}else{
			$('#ck_edit_bom').attr("checked", false) ;
		}

		
	}


	function editdetail(a,b){
		var rows = $('#dg').datagrid('getRows'); 
		var rows1 = $('#dg').datagrid('getSelections');
		
		
			$('#dlg_detail').dialog('open').dialog('setTitle','EDIT DETAIL OF '+a+'');
			$('#dg_detail').datagrid( {
				url:'mps_edit_get_detail.php?po_no='+a+'&po_line_no='+b,
				singleSelect:true,
				loadMsg:'load data ...',
				columns:[[
					{field:'EDIT_TYPE',title:'EDIT_TYPE',width:150,halign:'center', align:'center',hidden: true},
					{field:'PO_NO',title:'PO NO.',width:150,halign:'center', align:'center'},
					{field:'PO_LINE_NO',title:'Line No.', halign:'center', align:'center', width:60, sortable: true},
					{field:'MPS_QTY', title:'Quantity', halign:'center', width:100,editor:{type:'textbox',options:{required:true,precision:0,groupSeparator:','}}},
					{field:'MPS_DATE', title:'Prod<br>Date', halign:'center', width:90,editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}},
					{field:'OLD_MPS_DATE', title:'OLD_MPS_DATE', halign:'center',hidden: true, width:90},
					{field:'QTY', title:'QTY', halign:'center', width:90 ,hidden: true}
				]],
				onClickRow:function(row){
		    		$(this).datagrid('beginEdit', row);
					
				},
				onAfterEdit:function(index,row){
					row.editing = false;
					$(this).datagrid('refreshRow', index);
				}
			});
		
	}


	function splitwo(a,b,c,d){
		
		$('#dg_split').datagrid('loadData', []);
		$('#split_edit').numberbox('setValue');
		var rows = $('#dg').datagrid('getRows'); 
		var rows1 = $('#dg').datagrid('getSelections');
		$('#wo_no_split_edit').textbox('setValue',a);
		$('#qty_order_split_edit').numberbox('setValue',b);		
		$('#dlg_split').dialog('open').dialog('setTitle','SPLIT WO OF '+a+'');
		$('#wo_no_split_edit').textbox('disable');
		$('#qty_order_split_edit').numberbox('disable');
		$('#po_no_split_edit').textbox('setValue',c);
		$('#line_no_split_edit').textbox('setValue',d);
		$('#po_no_split_edit').textbox('disable');
		$('#line_no_split_edit').numberbox('disable');
	}

	function split_simulasi(){
		var split = $('#split_edit').numberbox('getValue');
		$('#dg_split').datagrid('loadData', []);
		if(split>1){
			for(i=0;i<split;i++){
				var aa= i+1;	
				$('#dg_split').datagrid('appendRow',{
					WORK_ORDER: $('#wo_no_split_edit').textbox('getValue')+aa,
					QTY: parseInt($('#qty_order_split_edit').numberbox('getValue')/split),
					PERSEN: parseInt(100/split)

				});
			}
		}
	
	
		
		
	}
</script>







</body>

</html>
