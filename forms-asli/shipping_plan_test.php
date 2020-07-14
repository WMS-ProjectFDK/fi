<?php
include("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SHIPPING PLAN</title>
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
<script type="text/javascript" src="../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../js/jquery.edatagrid.js"></script>
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

  <div id="toolbar">

  		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:$('#dg').edatagrid('addRow')">New</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:$('#dg').edatagrid('destroyRow')">Destroy</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:$('#dg').edatagrid('saveRow')">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:$('#dg').edatagrid('cancelRow')">Cancel</a>
  </div>

<table id="tt" class="easyui-datagrid" style="width:600px;height:250px"
        url="shipping_plan_test_get.php"
        title="Load Data" iconCls="icon-save"
        rownumbers="false" pagination="true">
    <thead>
        <tr>
        	<th field="REC" width="40">REC </th>
            <th field="SO_NO" width="80">SO_NO</th>
            <th field="CUSTOMER_PO_NO" width="80">PO_NO</th>
            <th field="CUSTOMER_CODE" width="100" align="right">CUSTOMER_CODE</th>
            <th field="SO_DATE" width="100" align="right">SO DATE</th>
           <!--  <th field="unitcost" width="80" align="right">Unit Cost</th>
            <th field="attr1" width="150">Attribute</th>
            <th field="status" width="60" align="center">Stauts</th> -->
        </tr>
    </thead>
</table>



<!-- 

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

	


	//addPRF()
	 //filterData()

});



function filterData(){
		

		var ck_cr_date = "false";
		var ck_po_no = "false";
		var ck_wo_no = "false";
		var ck_item_no = "false";
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
		
		if(flag == 4) {

			alert("No filter data, system only show 150 records.")
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
			flag: flag
			
		});

		
		
		$('#dg').datagrid( {
			url: 'shipping_plan_get.php',
			
			columns:[[

		    	//{field:'ck', align:'center', width:30, title:'Check', halign: 'center',editor:{type:'checkbox',options:{on:'TRUE',off:'FALSE'}}},
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
		    	{field:'SHIPPING', align:'center', width:40, title:'SHIPPING', halign: 'center',editor:{type:'textbox',options:{required:false,precision:0,groupSeparator:','}}},
			    {field:'WORK_ORDER',title:'Work Order No.', halign:'center', width:120},
			    {field:'SO_NO', title:'SO No.', halign:'center', width:70},
                {field:'PO_NO', title:'Cust PO No.', halign:'center', width:70},
                {field:'PO_LINE_NO', title:'Line No', halign:'center', width:30},
                {field:'ITEM_NO', title:'Item No', halign:'center', align:'center', width:50},
                {field:'ITEM_NAME', title:'Item Name', halign:'center', align:'left', width:100},
                {field:'CR_DATE', title:'CR Date', halign:'center', align:'center', width:50},
                {field:'BATERY_TYPE', title:'BATERY<br/>TYPE', halign:'center', align:'right', width:30},
                {field:'CELL_GRADE', title:'Grade', halign:'center', align:'center', width:30},
                {field:'QTY_ORDER', title:'QTY Order', halign:'center', align:'right', width:50},
                {field:'QTY_PRODUKSI', title:'QTY Available', halign:'center', align:'right', width:50},
                {field:'QTY_PLAN', title:'QTY Planned', halign:'center', align:'right', width:50},
                {field:'QTY_INVOICED', title:'QTY Invoiced', halign:'center', align:'right', width:50},
                {field:'SI_NO', title:'SI NO', halign:'center', align:'right', width:50, hidden:true},
                {field:'ETD', title:'ETD', halign:'center', align:'right', width:50, hidden:true}
			]],
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


		

		$('#dg_add').datagrid({
		    singleSelect: true,
			rownumbers: true,
		    columns:[[

			    {field:'WORK_ORDER', title:'WORK ORDER.', width:180, halign: 'center', align: 'left'},
			    {field:'ITEM_NO', title:'ITEM NAME', width:70, halign: 'center'},//, hidden: true},
			    {field:'SO_NO', title:'SO NO', width:70, halign: 'center', hidden: true},
			    {field:'LINE_NO', title:'LINE NO', width:70, halign: 'center', hidden: true},
			    {field:'DESCRIPTION', title:'DESCRIPTION', width: 180, halign: 'center'},
			    {field:'SI_NO', title:'SI NO', width: 100, halign: 'center'},
			    
			    {field:'QUANTITY', title:'SHIP QTY', halign: 'center', width:80, align:'right', editor:{type:'textbox',options:{required:true,precision:0,groupSeparator:','}}},
			    {field:'VESSEL', title:'VESSEL', halign: 'center', width:200, align:'right', editor:{type:'textbox',options:{required:false,precision:0,groupSeparator:','}}},
			    {field:'ETA_DATE', title: 'E.T.A DATE', halign: 'center', width: 90, editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}
			    },
			    {field:'ETD_DATE', title: 'E.T.D', halign: 'center', width: 80, editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}
			    },
			    {field:'CR_DATE', title: 'CR Date', halign: 'center', width: 85, editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}
			    },
			    {field:'EX_FAC_DATE', title: 'EX FACT', halign: 'center', width: 85, editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}
			    }
			  	

			    		   
			   
		    ]],
		    onClickRow:function(row){
		    	$(this).datagrid('beginEdit', row);
		    },
		    
		});

	}





function info_kuraire(a){
		
		$('#dlg_viewKur').dialog('open').dialog('setTitle','VIEW INFO KURAIRE');
		//alert('mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan);
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

function info_plan(a){
		
		$('#dlg_viewPln').dialog('open').dialog('setTitle','VIEW & EDIT INFO SHIPPING');
		//alert('mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan);
		$('#dg_viewPln').datagrid({
			url: 'shipping_plan_info_plan.php?work_order='+a+'',
			singleSelect: true,
			rownumbers: true,
		    columns:[[
		    {field:'AM',title:'AMEND',width:130,align:'center',
                formatter:function(value,row,index){
                        var e = '<a href="javascript:void(0)" onclick="addrowPlan(this)">Amend</a> ';
                        return e;
                }
            },
			   
			    {field:'WO_NO', title:'Work Order.', width:170, halign: 'center', align: 'center'},
			    {field:'ITEM_NO', title:'Item No', width: 80, halign: 'center'},
			    {field:'ITEM_NAME', title:'Description.', width: 250, halign: 'center'},
			    {field:'CR_DATE', title:'CR Date', width: 100, halign: 'center',editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}},
			    {field:'ETA', title:'ETA', width: 70, halign: 'center',editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}},
			    {field:'ETD', title:'ETD', width: 100, halign: 'center', align: 'right',editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}},
			    {field:'EX_FACT', title:'Ex Factory Date', width:100, halign: 'center',editor:{
			    																	type:'datebox',
			    																	options:{required:true,formatter:myformatter,parser:myparser}
			    																}},
			    {field:'QTY', title:'Quantity', width:100, halign: 'center',editor:{type:'textbox',options:{required:false,precision:0,groupSeparator:','}}},
			    {field:'DEL',title:'DELETE',width:130,align:'center',
                formatter:function(value,row,index){
                	var ef = '<a href="javascript:void(0)" onclick="deleterow(this)">Delete</a> ';
                	return ef;
                }
            },
             {field:'ROWID', title:'ROWID', width: 80, halign: 'center',hidden:true}

			]],

		onClickRow:function(row){
		    	$(this).datagrid('beginEdit', row);
           
        }
		});

		
	}	



function info_invoiced(a){
		
		$('#dlg_viewInv').dialog('open').dialog('setTitle','VIEW INFO INVOICE');
		//alert('mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan);
		$('#dg_viewInv').datagrid({
			url: 'shipping_plan_info_inv.php?work_order='+a+'',
			singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'CUSTOMER_PO_NO', title:'PO No.', width:115, halign: 'center', align: 'center'},
			    {field:'ETD', title:'ETD', width: 80, halign: 'center'},
			    {field:'ETA', title:'ETA', width: 80, halign: 'center'},
			    {field:'CR_DATE', title:'Cargo Ready.', width: 200, halign: 'center'},
			    {field:'DO_NO', title:'Invoice No', width: 150, halign: 'center'},
			    {field:'ITEM_NO', title:'Item No', width: 70, halign: 'center'},
			    {field:'QTY', title:'Quantity', width: 100, halign: 'center', align: 'right'}]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		});
	}



function CloseDGAdd(){
	$('#dlg_add').dialog('close');
	//filterData()

}

function addShippingPlan(){
		$('#dg_add').datagrid('loadData',[]);	
		
		var idxfield=0;
		var flag = 0;
		var rows = $('#dg').datagrid('getRows'); 

    		var rows1 = $('#dg').datagrid('getSelections');


			for(iy=0;iy<rows1.length;iy++){
				$('#dg').datagrid('endEdit');
				for(ix=0;ix<rows1[iy].SHIPPING;ix++){
					$('#dg_add').datagrid('insertRow',{
					index: idxfield,
					row: {
						WORK_ORDER: rows1[iy].WORK_ORDER,
						ITEM_NO: rows1[iy].ITEM_NO,
						DESCRIPTION: rows1[iy].ITEM_NAME,
						SI_NO: rows1[iy].SI_NO,
						CR_DATE: rows1[iy].CR_DATE,
						QUANTITY: rows1[iy].QTY_ORDER,
						SO_NO: rows1[iy].SO_NO,
						LINE_NO: rows1[iy].PO_LINE_NO,
						ETD_DATE: rows1[iy].ETD
						}
					});
    		 		}
				}
   
		
		
		$('#dlg_add').dialog('open').dialog('setTitle','Shipping Plan Add');
		
		
	}

function savedata(){
	var t = $('#dg_add').datagrid('getRows');
	var total = t.length;
	var flag = 0;
	for(i=0;i<total;i++){
		
		$('#dg_add').datagrid('endEdit',i);
		if ($('#dg_add').datagrid('getData').rows[i].ETA_DATE != undefined & $('#dg_add').datagrid('getData').rows[i].QUANTITY != undefined &  $('#dg_add').datagrid('getData').rows[i].EX_FAC_DATE != undefined & $('#dg_add').datagrid('getData').rows[i].ETD_DATE != undefined & $('#dg_add').datagrid('getData').rows[i].SI_NO != null){
			flag = flag + 1;
		}
				
	}

	if (flag  != i) {
		alert("Data is not completed fill.");
	}else{

		for(i=0;i<total;i++){
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
					VESSEL: $('#dg_add').datagrid('getData').rows[i].VESSEL,
					LINE_NO: $('#dg_add').datagrid('getData').rows[i].LINE_NO
				}).done(function(res){
					console.log(res);
				});
			CloseDGAdd()
		}
		alert("Shipping Plan Created");
	}

	


	
}

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

function addrowPlan(target){
	
    $('#dlg_viewPln').datagrid('beginEdit', getRowIndexplan(target));
}

function saverowPlan(target){
    $('#dlg_viewPln').datagrid('endEdit', getRowIndexplan(target));
     alert(traget);
}

function deleterow(target){
	 var row = $('#dlg_viewPln').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						var idx = $("#dlg_viewPln").datagrid("getRowIndex", row);
						$('#dlg_viewPln').datagrid('deleteRow', idx);
					}	
				});
			}

}

function getRowIndexplan(target){
    alert(traget);
    var tr = $(target).closest('tr.datagrid-row');
    return parseInt(tr.attr('datagrid-row-index'));
}

</script> -->
</body>
</html>
