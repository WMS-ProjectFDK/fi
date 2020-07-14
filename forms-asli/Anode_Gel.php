<?php
include("../connect/conn.php");
// session_start();
// require_once('___loginvalidation.php');
// $user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Production Monitoring</title>
<link rel="icon" type="image/png" href="../favicon.png">
<!-- <script language="javascript">
		function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
		}
	</script>  -->
<link rel="stylesheet" type="text/css" href="../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../themes/color.css" />
<script type="text/javascript" src="../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
<style>
*{
font-size:20px;
text-align: left;
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

<div style="width:600px;float:left" >

	
		  <table style="width:600px; height: 500px" border="2" >
		    
		    <tr>
		      <th  scope="row" style=""> SCAN</th>
		      <td><label for="Txt1"></label>
		      <input  style='width:98%; height: 50px' type="text" name="Txt1" id="Txt1" /></td>
		     
		    </tr>
		    <tr>
		      <th scope="row">Date</th>
		      <td><input style="width:98%; height: 50px;font-size: 	large" name="date_awal" id="date_awal" class="easyui-textbox"  value="<? Date() ;?>"/> </td>
		      
		    </tr>
		    <tr>
		      <th scope="row">No Tag</th>
		      <td><input style='width:98%; height: 50px' type="text" name="Txt3" id="Txt3"/></td>
		    
		    </tr>
		    <tr>
		      <th scope="row">Type Gel</th>
		      <td><input style='width:98%; height: 50px' type="text" name="Txt4" id="Txt4" /></td>
		    </tr>
		    <tr>
		      <th scope="row">Type Zn Powder</th>
		      <td><input style='width:98%; height: 50px' type="text" name="Txt5" id="Txt5" /></td>
		    </tr>
		    <tr>
		      <th scope="row">No Proses</th>
		      <td><input style='width:98%; height: 50px' type="text" name="Txt6" id="Txt6" /></td>
		    </tr>
		    <tr>
		      <th scope="row">Density</th>
		      <td><input style='width:98%; height: 50px' type="text" name="Txt7" id="Txt7" /></td>
		    </tr>
		    </font>
		  </table>
		  
		    <input type="submit" name="Btn1" style="width:200px;height:50px; text-align: center;" id="Btn1" value="Input" />
		    <input type="submit" name="Btn2" style="width:200px;height:50px; text-align: center;" id="Btn2" value="Cancel" />

	

</div>



<div style="width:500px;float:left">

			<table style="width:600px; height: 500px" border="2" >
		    
		    <tr>
		      <th  scope="row" style=""> Compotition</th>
		      <td><label for="Txt1"></label>
		      <input  style='width:98%; height: 50px' type="text" name="Txt1" id="Txt1" /></td>
		     
		    </tr>
		    <tr>
		      <th scope="row">Date</th>
		      <td><input style='width:98%; height: 50px'type="text" name="Txt2" id="Txt2" /></td>
		      
		    </tr>
		    <tr>
		      <th scope="row">No Tag</th>
		      <td><input style='width:98%; height: 50px'type="text" name="Txt3" id="Txt3" /></td>
		    
		    </tr>
		    <tr>
		      <th scope="row">Type Gel</th>
		      <td><input style='width:98%; height: 50px'type="text" name="Txt4" id="Txt4" /></td>
		    </tr>
		    <tr>
		      <th scope="row">Type Zn Powder</th>
		      <td><input style='width:98%; height: 50px'type="text" name="Txt5" id="Txt5" /></td>
		    </tr>
		    <tr>
		      <th scope="row">No Proses</th>
		      <td><input style='width:98%; height: 50px'type="text" name="Txt6" id="Txt6" /></td>
		    </tr>
		    <tr>
		      <th scope="row">Density</th>
		      <td><input style='width:98%; height: 50px'type="text" name="Txt7" id="Txt7" /></td>
		    </tr>
		    </font>
		  </table>
</div>


 
<!-- <?php 
// include ('../ico_logout.php'); ?> -->
<!-- <div id="toolbar" style="padding:3px 3px;">
	<fieldset style="margin-left;border-radius:4px;height:70px;width:98%"><legend><span class="style3"><strong> Production Monitoring </strong></span></legend>
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
		<div style="width:500px;float:left">
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
<div style="padding:5px 6px;">
	<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>

	<!-- <a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="addShippingPlan()" style="width:200px;"><i class="fa fa-plus" aria-hidden="true"></i> Add Shipping Plan</a>

	<a href="javascript:void(0)" id="editbtn" class="easyui-linkbutton c2" onClick="editplan()" style="width:200px;"><i class="fa fa-edit" aria-hidden="true"></i> Edit Shipping Plan</a> -->
<!-- </div> -->

<!-- </div>
<table id="dg" title="Production Monitoring" class="easyui-datagrid" toolbar="#toolbar	" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" selectOnCheck= "true">
</table>

<div id='dlg_viewKur' class="easyui-dialog" style="width:1000px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewKur" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>

<div id='dlg_viewInv' class="easyui-dialog" style="width:900px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewInv" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div> --> 

<!-- <div id='dlg_add' class="easyui-dialog" style="width:1060px;height:450px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	<table align="center" id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1020px;height:370px;padding:10px 10px; margin:5px;"></table>
	<div style="padding:5px 6px;" align="right">
	<a href="javascript:void(0)" id="savebtn_Plan" class="easyui-linkbutton c2" onClick="savedata()" style="width:200px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
	<a href="javascript:void(0)" id="closebtn" class="easyui-linkbutton c2" onClick="CloseDGAdd()" style="width:200px;"><i class="fa fa-cancel" aria-hidden="true"></i> Exit</a>
	</div>
</div> -->






<script type="text/javascript">





// function myformatter(date){
// 		var y = date.getFullYear();
// 		var m = date.getMonth()+1;
// 		var d = date.getDate();
// 		return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
// 	}
// function myparser(s){
// 		if (!s) return new Date();
// 			var ss = (s.split('-'));
// 			var y = parseInt(ss[0],10);
// 			var m = parseInt(ss[1],10);
// 			var d = parseInt(ss[2],10);
// 			if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
// 			return new Date(y,m-1,d);
// 		} else {
// 			return new Date();
// 		}
// 	}
function date(){

		var d = new Date();
    	var n = d.getDate();
    	return new Date(); 	
    alert(n);

};
		// $('#date_awal').datebox('disable');
		// $('#date_akhir').datebox('disable');
		// $('#ck_cr_date').change(function(){
		// 	if ($(this).is(':checked')) {
		// 		$('#date_awal').datebox('disable');
		// 		$('#date_akhir').datebox('disable');
		// 	}else{
		// 		$('#date_awal').datebox('enable');
		// 		$('#date_akhir').datebox('enable');
		// 	}
		// });


		// $('#cmb_wo_no').combobox('disable');
		// $('#ck_wo_no').change(function(){
		// 	if ($(this).is(':checked')) {
		// 		$('#cmb_wo_no').combobox('disable');
		// 	}else{
		// 		$('#cmb_wo_no').combobox('enable');
		// 	}
		// });

		// $('#cmb_po_no').combobox('disable');
		// $('#ck_po_no').change(function(){
		// 	if ($(this).is(':checked')) {
		// 		$('#cmb_po_no').combobox('disable');
		// 	}else{
		// 		$('#cmb_po_no').combobox('enable');
		// 	}
		// });

		// $('#cmb_item_no').combobox('disable');
		// $('#ck_item_no').change(function(){
		// 	if ($(this).is(':checked')) {
		// 		$('#cmb_item_no').combobox('disable');
		// 	}else{
		// 		$('#cmb_item_no').combobox('enable');
		// 	}
		// });

	


	//addPRF()
	 //filterData()





// function filterData(){
		

// 		var ck_cr_date = "false";
// 		var ck_po_no = "false";
// 		var ck_wo_no = "false";
// 		var ck_item_no = "false";
// 		var flag = 0;

// 		if ($('#ck_cr_date').attr("checked")) {
// 			ck_cr_date = "true";
// 			flag += 1;
// 		};

// 		if ($('#ck_po_no').attr("checked")) {
// 			ck_po_no = "true";
// 			flag += 1;
// 		};

// 		if ($('#ck_item_no').attr("checked")) {
// 			ck_item_no = "true";
// 			flag += 1;
// 		};

// 		if ($('#ck_wo_no').attr("checked")) {
// 			ck_wo_no = "true";
// 			flag += 1;
// 		};
		
// 		if(flag == 4) {

// 			alert("No filter data, system only show 150 records.")
// 		}
		

// 		$('#dg').datagrid('load', {
// 			date_awal: $('#date_awal').datebox('getValue'),
// 			date_akhir: $('#date_akhir').datebox('getValue'),
// 			ck_cr_date: ck_cr_date,
// 			cmb_wo_no : $('#cmb_wo_no').combobox('getValue'),
// 			ck_wo_no: ck_wo_no,
// 			cmb_po_no : $('#cmb_po_no').combobox('getValue'),
// 			ck_po_no: ck_po_no,
// 			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
// 			ck_item_no: ck_item_no,
// 			flag: flag
			
// 		});

		
		
// 		$('#dg').datagrid( {
// 			url: 'production_monitoring_get.php',
			
// 			singleSelect: true,
			
// 			columns:[[

// 		    	//{field:'ck', align:'center', width:30, title:'Check', halign: 'center',editor:{type:'checkbox',options:{on:'TRUE',off:'FALSE'}}},
// 		    	//{field:'SHIPPING', align:'right', width:20, title:'Ship Qty', halign: 'center',editor:{type:'textbox',options:{required:false,precision:0,groupSeparator:','}}},
// 			    {field:'WORK_ORDER',title:'Work Order No.', halign:'center', width:120},
//                 {field:'PO_NO', title:'Cust PO No.', halign:'center', width:70},
//                 {field:'PO_LINE_NO', title:'Line No', halign:'center', width:30},
//                 {field:'ITEM_NO', title:'Item No', halign:'center', align:'center', width:50},
//                 {field:'ITEM_NAME', title:'Item Name', halign:'center', align:'left', width:100},
//                 {field:'CR_DATE', title:'CR Date', halign:'center', align:'center', width:50},
//                 {field:'BATERY_TYPE', title:'BATERY<br/>TYPE', halign:'center', align:'right', width:30},
//                 {field:'CELL_GRADE', title:'Grade', halign:'center', align:'center', width:30},
//                 {field:'QTY_ORDER', title:'QTY Order', halign:'center', align:'right', width:50},
//                 {field:'QTY_PRODUKSI', title:'QTY Available', halign:'center', align:'right', width:50},
//                 {field:'QTY_INVOICED', title:'QTY Invoiced', halign:'center', align:'right', width:50},
//                 {field:'SI_NO', title:'SI NO', halign:'center', align:'right', width:50, hidden:true}
// 			]],
// 			onLoadSuccess: function (data) {
// 				// for (i=0; i<data.rows.length; i++) {
//     //                 $(this).datagrid('beginEdit',i);
//     //             }
// 			}

			


// 		})
// 		$('#dg').datagrid('enableFilter');


		

// 		$('#dg_add').datagrid({
// 		    singleSelect: true,
// 			rownumbers: true,
// 		    columns:[[
// 			    {field:'WORK_ORDER', title:'WORK ORDER.', width:180, halign: 'center', align: 'left'},
// 			    {field:'ITEM_NO', title:'ITEM NAME', width:70, halign: 'center'},//, hidden: true},
// 			    {field:'DESCRIPTION', title:'DESCRIPTION', width: 180, halign: 'center'},
// 			    {field:'SI_NO', title:'SI NO', width: 100, halign: 'center'},
			    
// 			    {field:'QUANTITY', title:'SHIP QTY', halign: 'center', width:80, align:'right', editor:{type:'textbox',options:{required:true,precision:0,groupSeparator:','}}},
			    
// 			    {field:'ETA_DATE', title: 'E.T.A DATE', halign: 'center', width: 90, editor:{
// 			    																	type:'datebox',
// 			    																	options:{required:true,formatter:myformatter,parser:myparser}
// 			    																}
// 			    },
// 			    {field:'ETD_DATE', title: 'E.T.D', halign: 'center', width: 80, editor:{
// 			    																	type:'datebox',
// 			    																	options:{required:true,formatter:myformatter,parser:myparser}
// 			    																}
// 			    },
// 			    {field:'CR_DATE', title: 'CR Date', halign: 'center', width: 85, editor:{
// 			    																	type:'datebox',
// 			    																	options:{required:true,formatter:myformatter,parser:myparser}
// 			    																}
// 			    },
// 			    {field:'EX_FAC_DATE', title: 'EX FACT', halign: 'center', width: 85, editor:{
// 			    																	type:'datebox',
// 			    																	options:{required:true,formatter:myformatter,parser:myparser}
// 			    																}
// 			    }
			  	

			    		   
			   
// 		    ]],
// 		    onClickRow:function(row){
// 		    	$(this).datagrid('beginEdit', row);
// 		    },
		    
// 		});

// 	}





// function info_kuraire(a){
		
// 		$('#dlg_viewKur').dialog('open').dialog('setTitle','VIEW INFO KURAIRE');
// 		//alert('mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan);
// 		$('#dg_viewKur').datagrid({
// 			url: 'shipping_plan_info_kur.php?work_order='+a+'',
// 			singleSelect: true,
// 			rownumbers: true,
// 		    columns:[[
// 			    {field:'WO_NO', title:'Work Order.', width:130, halign: 'center', align: 'center'},
// 			    {field:'PLT_NO', title:'Plt No', width: 60, halign: 'center'},
// 			    {field:'ITEM_NO', title:'Item Name', width: 80, halign: 'center'},
// 			    {field:'ITEM_DESCRIPTION', title:'Description.', width: 200, halign: 'center'},
// 			    {field:'SCAN_DATE', title:'Scan Time', width: 150, halign: 'center'},
// 			    {field:'SLIP_TYPE', title:'Slip Type', width: 70, halign: 'center'},
// 			    {field:'SLIP_QUANTITY', title:'Quantity', width: 100, halign: 'center', align: 'right'},
// 			    {field:'APPROVAL_DATE', title:'Approval Date', width:100, halign: 'center'}
// 			]],
// 			onLoadSuccess: function (data) {
// 				for (i=0; i<data.rows.length; i++) {
//                     $(this).datagrid('beginEdit',i);
//                 }
// 			}
// 		});
// 	}

// function info_invoiced(a){
		
// 		$('#dlg_viewInv').dialog('open').dialog('setTitle','VIEW INFO INVOICE');
// 		//alert('mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan);
// 		$('#dg_viewInv').datagrid({
// 			url: 'shipping_plan_info_inv.php?work_order='+a+'',
// 			singleSelect: true,
// 			rownumbers: true,
// 		    columns:[[
// 			    {field:'CUSTOMER_PO_NO', title:'PO No.', width:115, halign: 'center', align: 'center'},
// 			    {field:'ETD', title:'ETD', width: 80, halign: 'center'},
// 			    {field:'ETA', title:'ETA', width: 80, halign: 'center'},
// 			    {field:'CR_DATE', title:'Cargo Ready.', width: 200, halign: 'center'},
// 			    {field:'DO_NO', title:'Invoice No', width: 150, halign: 'center'},
// 			    {field:'ITEM_NO', title:'Item No', width: 70, halign: 'center'},
// 			    {field:'QTY', title:'Quantity', width: 100, halign: 'center', align: 'right'}]],
// 			onLoadSuccess: function (data) {
// 				for (i=0; i<data.rows.length; i++) {
//                     $(this).datagrid('beginEdit',i);
//                 }
// 			}
// 		});
// 	}



// function CloseDGAdd(){
// 	$('#dlg_add').dialog('close');
// 	filterData()

// }

// function addShippingPlan(){
// 		$('#dg_add').datagrid('loadData',[]);	
		
// 		var t = $('#dg').datagrid('getRows');
// 		var total = t.length;
// 		var t_true = 0;
// 		var q_tot = 0;
// 		var idxfield=0;
// 		var po_no='';
// 		for(i=0;i<total;i++){
// 			jmrow = i+1;
// 			$('#dg').datagrid('endEdit',i);
// 			if($('#dg').datagrid('getData').rows[i].ck=="TRUE"){
// 				$('#dlg_add').dialog('open').dialog('setTitle','Shipping Plan Add');
// 			var ship = $('#dg').datagrid('getData').rows[i].SHIPPING
// 			var po_no = $('#dg').datagrid('getData').rows[i].PO_NO
// 			for(iy=0;iy<ship;iy++){
// 				$('#dg_add').datagrid('insertRow',{
// 					index: idxfield,
// 					row: {
// 						WORK_ORDER: $('#dg').datagrid('getData').rows[i].WORK_ORDER,
// 						ITEM_NO: $('#dg').datagrid('getData').rows[i].ITEM_NO,
// 						DESCRIPTION: $('#dg').datagrid('getData').rows[i].ITEM_NAME,
// 						SI_NO: $('#dg').datagrid('getData').rows[i].SI_NO,
// 						CR_DATE: $('#dg').datagrid('getData').rows[i].CR_DATE,
// 						QUANTITY: $('#dg').datagrid('getData').rows[i].QTY_ORDER
// 					}
// 				});
// 			}
// 					t_true++;
// 				}
// 			}

// 			if(t_true == 0){
// 				$.messager.alert('WARNING','Please Select Work Order','warning');
// 				filterData();
// 			}
		
		
// 	}

// function savedata(){
// 	var t = $('#dg_add').datagrid('getRows');
// 	var total = t.length;
// 	var flag = 0;
// 	for(i=0;i<total;i++){
// 		$('#dg_add').datagrid('endEdit',i);
		
// 		if ($('#dg_add').datagrid('getData').rows[i].ETA_DATE = ''){
// 			alert('hello');
// 		}

// 		$.post('shipping_plan_save.php',{
// 					WORK_ORDER: $('#dg_add').datagrid('getData').rows[i].WORK_ORDER,
// 					ITEM_NO: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
// 					ITEM_NAME: $('#dg_add').datagrid('getData').rows[i].DESCRIPTION,
// 					SI_NO: $('#dg_add').datagrid('getData').rows[i].SI_NO,
// 					QUANTITY: $('#dg_add').datagrid('getData').rows[i].QUANTITY,
// 					CR_DATE: $('#dg_add').datagrid('getData').rows[i].CR_DATE,
// 					ETA_DATE: $('#dg_add').datagrid('getData').rows[i].ETA_DATE,
// 					EX_FAC_DATE: $('#dg_add').datagrid('getData').rows[i].EX_FAC_DATE,
// 					ETD_DATE: $('#dg_add').datagrid('getData').rows[i].ETD_DATE
// 				}).done(function(res){
// 					//alert(res);
// 					console.log(res);
// 				});
				
// 	}

// CloseDGAdd()


	


</script>
</body>
</html>
