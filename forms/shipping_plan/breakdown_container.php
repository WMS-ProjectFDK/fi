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
<title>BREAKDOWN CONTAINER</title>
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
	<fieldset style="margin-left;border-radius:4px;height:45px;width:98%"><legend><span class="style3"><strong> Filter</strong></span></legend>
		<div style="width:500px;float:left">
			<div class="fitem">
				<span style="width:80px;display:inline-block;">PPBE No.</span>
				<select style="width:120px;" name="cmb_ppbe" id="cmb_ppbe" class="easyui-combobox" data-options=" url:'../json/json_ppbe_no.php', method:'get',valueField:'ppbe_no',textField:'ppbe_no', panelHeight:'100px'"></select>
				<a href="javascript:void(0)" id="print_si" class="easyui-linkbutton c2" onClick="back_to_shipping()" style="width:200px;"><i class="fa fa-undo" aria-hidden="true"></i> Back to Shipping Plan</a>
			</div>
		</div>
	</fieldset>
	<div style="padding:5px 6px;">
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</a>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" iconCls="icon-pdf" onClick="print_pdf()" style="width:200px;"> PRINT TO PDF</a>
		<a href="javascript:void(0)" id="fltbtn" class="easyui-linkbutton c2" iconCls="icon-excel" onClick="print_xls()" style="width:200px;"> PRINT TO EXCEL</a>
		<a href="javascript:void(0)" id="add_bdc" class="easyui-linkbutton c2" disabled="true" onClick="add()" style="width:200px;"><i class="fa fa-plus" aria-hidden="true"></i> ADD </a>
		<a href="javascript:void(0)" id="recalculate_bdc" class="easyui-linkbutton c2" onClick="addTotal()" style="width:180px;"><i class="fa fa-refresh" ></i> SHOW TOTAL </a>
		<a href="javascript:void(0)" id="recalculate_bdc" class="easyui-linkbutton c2"  onClick="recalculate()" style="width:180px;"><i class="fa fa-refresh" aria-hidden="true"></i> RECALCULATE </a>
	</div>
</div>

<table id="dg" title="Breakdown Container" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true">
</table>

<div id='dlg_viewItem' class="easyui-dialog" style="width:1000px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewItem" class="easyui-datagrid" style="width:100%;height:auto;"></table>
</div>

<script type="text/javascript">
	var link = '';
	$(function(){
		$('#dg').datagrid( {
			singleSelect: true,
			columns:[[
			    {field:'SO_NO', title:'SO', halign:'center', width:50},
                {field:'CUSTOMER_PO_NO', title:'CUST. PO NO.', halign:'center', width:70},
                {field:'WO_NO', title:'WORK NO.', halign:'center', width:100},
                {field:'ITEM_NO', title:'ITEM<br/>NO.', halign:'center', align:'center', width:40},
                {field:'DESCRIPTION', title:'ITEM NAME', halign:'center', align:'left', width:140},
                //{field:'CONTAINERS', title:'CONTAINER<br>SIZE', width:50, halign:'center'},
                {field:'CONTAINERS', title:'CONTAINER<br>SIZE', width:80, halign:'center', 
                	editor:{type:'combobox',
                		options: {url: '../json/json_cargo_size.php',
					    		  panelHeight: '50px',
								  valueField: 'DESCRIPTION',
								  textField: 'DESCRIPTION'
					    		}
					}
				},
				{field:'save',title:'SAVE',width:70,align:'center',
	                formatter:function(value,row,index){
	                	var ef = '<a href="javascript:void(0)" onclick="saverow(this)">Save</a> ';
	                	return ef;
	                }
            	},
                {field:'CONTAINER_NO', title:'CONTAINER<br>NO', width:80, halign:'center',editor:{type:'textbox'}},
 				{field:'QTY', title:'QTY', width:60, halign:'center', align:'right', editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:true}
																   				  			}
				},
				{field:'TW', title:'TARE<br/>WEIGHT', width:60, halign:'center', align:'right', editor:{type:'numberbox',
																				   			 options:{precision:0,groupSeparator:',',disable:true}
																   				  			}
				},
				{field:'ENR', title:'ENR<br/>PART', width:60, halign:'center', align:'right', editor:{type:'textbox'}},
                {field:'CARTON', title:'CARTON', halign:'center', align:'right', width:50},
                {field:'GROSS', title:'GW<br/>(KGS)', halign:'center', align:'right', width:50},
                {field:'NET', title:'NW<br/>(KGS)', halign:'center', align:'right', width:50},
                {field:'MSM', title:'MSM<br/>(CBM)', halign:'center', align:'right', width:50},
                {field:'PALLET', title:'PALLET<br/>QTY', halign:'center', align:'right', width:40},
                {field:'CONTAINER_VALUE', title:'CONTAINER<br/>VALUE', halign:'center', align:'right', width:50},
                {field:'ROW_ID', width: 40, hidden: true},
                {field:'ITEM2', width: 40, hidden: true},
                {field:'STS', width: 40, hidden: true},
                {field:'ANSWER_NO', width: 40, hidden: true}
			]],
			
		});
		$('#dg').datagrid({
		    rowStyler:function(index,row){
		        if (row.CONTAINER_NO =='TOTAL'){
		            return 'background-color:red;color:white;font-weight:bold;';
		        }
		    }
		});
		$('#dg').datagrid({
			onBeforeSelect:function(index,row){
				var col = $('#dg').datagrid('getColumnOption');
				    if(row.CONTAINER_NO =='TOTAL') return col.editor = null;
			}
		})
	});

	function saverow(target){
		
		var row = $('#dg').datagrid('getSelected');	
		if (row){
			
			var idx = $('#dg').datagrid("getRowIndex", row);
			
			$('#dg').datagrid('endEdit', idx);
			$.post('breakdown_container_save2.php',{
				bdc_tw : row.TW,
				bdc_enr : row.ENR,
				bdc_ppbe : $('#cmb_ppbe').combobox('getValue'),
				bdc_size : row.CONTAINERS,
				bdc_container : row.CONTAINER_NO,
				bdc_row : row.ROW_ID
			}).done(function(res){
				console.log(res);
			});
			$.messager.alert('INFORMATION','SAVE DATA SUCCESS','info');
			$('#dg').datagrid('reload');
		}
		filterData();
	}

	function getRowIndexplan(target){
	    var tr = $(target).closest('tr.datagrid-row');
	    return parseInt(tr.attr('datagrid-row-index'));
	}

	function filterData(){
		$('#dg').datagrid('load', {
			ppbe: $('#cmb_ppbe').combobox('getValue')
		});

		$('#dg').datagrid( {
			url: 'breakdown_container_get.php',
			onClickRow:function(row){
		    	$(this).datagrid('beginEdit', row);
		    	$('#add_bdc').linkbutton('enable');
		    	$('#recalculate_bdc').linkbutton('enable');
		    }
		})
		add();
		link = "?ppbe="+$('#cmb_ppbe').combobox('getValue');
	}


	function formatListprice(val,row){
    if (val > 50){
        return '<span style="background-color:pink;color:blue;font-weight:bold;">'+val+'</span>';
    } else {
        return val;
    }
}

	function add(){
		var row = $('#dg').datagrid('getSelected');
		if(row){
			var indx = $('#dg').datagrid('getRowIndex',row);
			var idx = $('#dg').datagrid('getRowIndex', row)+1;
			if (row.CONTAINER_NO != 'TOTAL'){
				$('#dg').datagrid('insertRow',{
					index: idx,
					row: {
						SO_NO: row.SO_NO, 
						CUSTOMER_PO_NO: row.CUSTOMER_PO_NO,
						WO_NO: row.WO_NO,
						ITEM_NO: row.ITEM_NO, 
						DESCRIPTION: row.DESCRIPTION, 
						QTY: row.QTY, 
						TW: row.TW,
						ENR: row.ENR,
						CONTAINERS: row.CONTAINERS		, 
						CONTAINER_NO: row.CONTAINER_NO,
						CARTON: row.CARTON, 
						GROSS: row.GROSS, 
						NET: row.NET, 
						MSM: row.MSM, 
						PALLET: row.PALLET, 
						CONTAINER_VALUE: row.CONTAINER_VALUE, 
						ITEM2: row.ITEM2,
						ROW_ID: '',
						STS: 'NEW',
						ANSWER_NO: row.ANSWER_NO
					}
				});
			};
		}
	}

	function clearTotal(){
		var t = $('#dg').datagrid('getRows');
		var total = t.length;
		for(var i=0; i<total; i++){
			if ($('#dg').datagrid('getData').rows[i].CONTAINER_NO == 'TOTAL'){
				$('#dg').datagrid('deleteRow', i);
			};
		}

	}

	function print(){
		var t = $('#dg').datagrid('getRows');
		var total = t.length;
		if (total>0){
			$('#dg').datagrid('toExcel','sales_report.xls');
		}else{alert('Mohon lakukan filter data dahulu.');}
		
	}
	
	function addTotal(){
		clearTotal();
		var flag = 0;
		var t = $('#dg').datagrid('getRows');
		var total = t.length;
		var oldCont = 'xyz';
		var newCont = '';
		var totalPallet = 0;
		var totalCarton = 0;
		var totalQty = 0;
		var totalNet = 0;
		var totalGross=0;
		var totalMSM = 0;
		var totalContainer = 0;
		//$('#dg').datagrid('deleteRow', index);
		
		for(var i=0; i<total+flag; i++){
			newCont =  $('#dg').datagrid('getData').rows[i].CONTAINER_NO;
				if (newCont != oldCont && oldCont != 'xyz'){
					var index = $('#dg').datagrid('getRowIndex', t[i]);
					$('#dg').datagrid('insertRow',{
						index: index,
						row: {
							QTY: totalQty, 
							PALLET: totalPallet,
							NET: totalNet,
							GROSS: totalGross,
							MSM: totalMSM,
							CARTON: totalCarton,
							CONTAINER_VALUE: totalContainer,
							CONTAINER_NO:'TOTAL'
						}
					});
					flag++;
					totalPallet = 0;
					totalCarton = 0;
					totalQty = 0;
					totalNet = 0;
					totalGross=0;
					totalMSM = 0;
					totalContainer = 0;
				}else{
					totalPallet = totalPallet + parseFloat($('#dg').datagrid('getData').rows[i].PALLET);
					totalCarton = totalCarton + parseFloat($('#dg').datagrid('getData').rows[i].CARTON);
					totalQty = totalQty + parseFloat($('#dg').datagrid('getData').rows[i].QTY);
					totalNet = totalNet + parseFloat($('#dg').datagrid('getData').rows[i].NET);
					totalGross = totalGross + parseFloat($('#dg').datagrid('getData').rows[i].GROSS);
					totalMSM = totalMSM + parseFloat($('#dg').datagrid('getData').rows[i].MSM);
					totalContainer = totalContainer + parseFloat($('#dg').datagrid('getData').rows[i].CONTAINER_VALUE);
				};
			oldCont = newCont;
		}

		$('#dg').datagrid('insertRow',{
			index: total+flag,
			row: {
				QTY: totalQty, 
				PALLET: totalPallet,
				NET: totalNet,
				GROSS: totalGross,
				MSM: totalMSM,
				CARTON: totalCarton,
				CONTAINER_VALUE: totalContainer,
				CONTAINER_NO:'TOTAL'
			}
		});
	}

	function recalculate(){
		var t = $('#dg').datagrid('getRows');
		var x = 0;
		var total = t.length;
		for(i=0;i<total;i++){
			$('#dg').datagrid('endEdit',i);
				if ($('#dg').datagrid('getData').rows[i].ITEM2 != undefined){
					$.post('breakdown_container_save.php',{
					bdc_item: $('#dg').datagrid('getData').rows[i].ITEM2,
					bdc_qty: $('#dg').datagrid('getData').rows[i].QTY.replace(/,/g,''),
					bdc_tw: $('#dg').datagrid('getData').rows[i].TW,
					bdc_enr: $('#dg').datagrid('getData').rows[i].ENR,
					bdc_ppbe: $('#cmb_ppbe').combobox('getValue'),
					bdc_wono: $('#dg').datagrid('getData').rows[i].WO_NO,
					bdc_container: $('#dg').datagrid('getData').rows[i].CONTAINER_NO,
					bdc_i : x,
					bdc_row: $('#dg').datagrid('getData').rows[i].ROW_ID,
					bdc_answer_no: $('#dg').datagrid('getData').rows[i].ANSWER_NO
				}).done(function(res){
					console.log(res);
				})
				x++;
			};
			
		};
		filterData();
	};

	function back_to_shipping(){
		window.location.href = 'shipping_plan.php?id=341';
	}

	function info_item(a){
		$('#dlg_viewItem').dialog('open').dialog('setTitle','VIEW INFO Item '+a+'');
		$('#dg_viewItem').datagrid({
			url: 'breakdown_container_info_item.php?item_no='+a+'',
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
			showFooter: true,
		    columns:[[
			    {field:'ITEM_NO', title:'ITEM NO', width:130, halign: 'center', align: 'center'},
			    {field:'DESCRIPTION', title:'DESCRIPTION', width: 200, halign: 'center', align: 'center'},
			    {field:'QTY_PCS', title:'QTY PER<br>PALLET.', width: 100, halign: 'center', align: 'center'},
			    {field:'QTY_BOX', title:'BOX PER<br>PALLET', width: 100, halign: 'center'},
			    {field:'STEP', title:'STEP', width: 70, halign: 'center'},
			    {field:'GW', title:'Gross<br>Weight', width: 100, halign: 'center'},
			    {field:'NW', title:'NET<br>Weight', width: 100, halign: 'center', align: 'center'},
			    {field:'CARTON_HEIGHT', title:'Tinggi<br>Pallet', width: 100, halign: 'center', align: 'right'},
			    {field:'PANJANG_PALLET', title:'Panjang<br>Pallet', width: 100, halign: 'center', align: 'right'},
			    {field:'LEBAR_PALLET', title:'Lebar<br>Pallet', width: 100, halign: 'center', align: 'right'},
			    {field:'TWO_FEET', title:'20 Feet<br>Pallet', width: 100, halign: 'center', align: 'right'},
			    {field:'FOUR_FEET', title:'40 Feet<br>Pallet', width: 100, halign: 'center', align: 'right'}
			]],
			onLoadSuccess: function (data) {
				for (i=0; i<data.rows.length; i++) {
                    $(this).datagrid('beginEdit',i);
                }
			}
		});
	}

	function print_pdf(){
		if (link == ''){
			$.messager.show({
				title: 'Report',
				msg: 'Data Not Found'
			});	
		}else {

			$.get('pallet_check.php',{
					ppbe_no: $('#cmb_ppbe').combobox('getValue')
				}).done(function(res){
					if(res == '"success"'){
						window.open('breakdown_container_print.php'+link);
					}else{
						$.messager.alert('ERROR',res,'warning');
					}
			});			
			
		}

		
	}

	function print_xls(){
		if (link == ''){
			$.messager.show({
				title: 'Report',
				msg: 'Data Not Found'
			});	
		}else {

			$.get('pallet_check.php'+link,{
					ppbe_no: $('#cmb_ppbe').combobox('getValue')
				}).done(function(res){
					if(res == '"success"'){
						window.open('breakdown_container_print_xls.php'+link);
					}else{
						$.messager.alert('ERROR',res,'warning');
					}
			});		
				
		}
	}

</script>

</body>
</html>
<?php }else{	
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
}