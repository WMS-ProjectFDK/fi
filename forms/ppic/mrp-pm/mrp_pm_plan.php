<?php
require("../../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$upper_item_no = isset($_REQUEST['upper_item_no']) ? strval($_REQUEST['upper_item_no']) : '';
$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$from = isset($_REQUEST['from']) ? strval($_REQUEST['from']) : '';

if ($upper_item_no == ''){
	$cmb_item_no = $cmb_item_no;
	$sts = 'lower';
}else{
	$cmb_item_no = $upper_item_no;
	$sts = 'upper';
}

$sql = "select uom_q,unit_pl,aa.item_no,description,line_no,purchase_leadtime from
(select a.uom_q, b.unit_pl, a.item_no, a.description from item a
inner join unit b on a.uom_q = b.unit_code
where a.item_no=".$cmb_item_no.")aa
left outer join
(select distinct max(line_no) line_no, item_no,isnull(purchase_leadtime,0) as purchase_leadtime 
	from itemmaker 
	group by item_no,purchase_leadtime)bb on aa.item_no=bb.item_no";
$data = sqlsrv_query($connect, strtoupper($sql));
$row = sqlsrv_fetch_object($data);
$ld_time = '';

if($from == 'ITO'){
	$ld_time = ', Lead Time = '.$row->PURCHASE_LEADTIME.' Days';
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PACKAGING MATERIAL ANALISYST</title>
<link rel="icon" type="image/png" href="../../../favicon.png">
<script language="javascript">
	function confirmLogOut(){
		var is_confirmed;
		is_confirmed = window.confirm("End current session?");
		return is_confirmed;
	}
</script> 
<link rel="stylesheet" type="text/css" href="../../../plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="../../../themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../../themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../../themes/color.css" />
<script type="text/javascript" src="../../../js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../../../js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="../../../js/jquery.easyui.patch.js"></script>
<script type="text/javascript" src="../../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../../js/jquery.edatagrid.js"></script>
<script type="text/javascript" src="../../../js/canvasjs.min.js"></script>
<script type="text/javascript" src="../../../js/datagrid-export.js"></script>

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

.tooltip {
    display: inline;
    position: relative;
    text-decoration: none;
    top: 0px;
    left: 4px;
}

.tooltip:hover:after {
    background: #333;
    background: rgba(0,0,0,.8);
    border-radius: 5px;
    top: -5px;
    color: #fff;
    content: attr(alt);
    left: 160px;
    padding: 5px 15px;
    position: absolute;
    z-index: 98;
    width: 150px;
}
</style>
</head>
<body>
<?php include ('../../../ico_logout.php'); ?>

<table id="dg_plan" title="VIEW PLAN <?php echo  $cmb_item_no.' ('.$row->DESCRIPTION.')'; ?>" class="easyui-datagrid" style="width:100%;height:auto;"></table>

<div style="margin-top: 5px; width: 700px;">
	<div><b>Unit of Measure : <?php echo $row->UNIT_PL; ?> <?php echo $ld_time; ?></b></div>
</div>

<div align="center" style="margin-top: 50px;">
	<a href="javascript:void(0)" title="Back to <?php echo $from; ?>" class="easyui-linkbutton c2" iconCls="icon-back" onclick="javascript:window.close();" style="width:100px;height: 30px;">BACK</a>
<?php
if ($sts == 'lower'){
	echo '<a href="javascript:void(0)"  class="easyui-linkbutton c2"  onclick="run_mrp_item('.$cmb_item_no.')" style="width:200px;height: 30px;">RUN MRP FOR THIS ITEM</a>';
}
?>

<!-- START PRF ADD -->
<div id='dlg_add' class="easyui-dialog" style="width:1100px;height:420px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:1045px; float:left; margin:5px;"><legend>Create Purchase Requestion</legend>
		<div style="width:100%; height: 60px; float:left;">	
			<div class="fitem">
				<span style="width:60px;display:inline-block;">PRF No.</span>
				<input style="width:150px;" name="po_no_add" id="prf_no_add" class="easyui-textbox" disabled="" />
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:60px;display:inline-block;">PRF Date</span>
				<input style="width:85px;" name="prf_date_add" id="prf_date_add" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
				<!-- validType="validDate" --> 
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:80px;display:inline-block;">Cust. PO No.</span>
				<input style="width:150px;" name="cust_pono_add" id="cust_pono_add" class="easyui-textbox" disabled="" />
				<span style="width:5px;display:inline-block;"></span>
				<label><input type="checkbox" name="ck_new_add" id="ck_new_add">New Design</input></label>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width: 50px;display:inline-block;">Remark</span>
				<input style="width: 200px; height: 50px;" name="remark_add" id="remark_add"  multiline="true" class="easyui-textbox" autofocus=""/>
			</div>
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:10px;"></div>
	<table align="center" id="dg_add" class="easyui-datagrid" toolbar="#toolbar_add" style="width:1075px;height:220px;padding:10px 10px; margin:5px;"></table>
</div>

<div id="dlg-buttons-add">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePRF()" style="width:90px">Save</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
</div>
<!-- END PRF ADD -->

<!-- START CREATE DI -->
<div id="dlg" class="easyui-dialog" style="width:1150px;height:300px;padding:5px 5px" closed="true" buttons="#dlg-buttons" data-options="modal:true">
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:720px; height: 70px; float:left;"><legend><span class="style3"><strong>Select Vendor</strong></span></legend>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">VENDOR</span>
			<select style="width:400px;" name="cmb_company" id="cmb_company" class="easyui-combobox" data-options=" url:'../../json/json_company_DI_byMRP.php', method:'get', valueField:'COMPANY_CODE', textField:'COMB_COMPANY', panelHeight:'100px',
			onSelect: function(rec){
				$('#txt_attn').textbox('setValue',rec.ATTN);
			}"
			required=""></select>
		</div>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">ATTN</span>
			<input style="width:400px;" name="txt_attn" id="txt_attn" class="easyui-textbox" required="" />
		</div>
	</fieldset>
	<fieldset style="position:absolute; border:1px solid #d0d0d0; margin-left:750px; border-radius:2px; width:350px; height: 70px;"><legend><span class="style3"><strong> Insert Delivery Instruction </strong></span></legend>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">DI No.</span>
			<input style="width:120px;" name="txt_di_no" id="txt_di_no" class="easyui-textbox" disabled="true" />
		</div>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">DI Date</span>
			<input style="width:120px;" name="di_date" id="di_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" disabled="true" /> 
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:10px;"></div>
	<table id="dg_create" class="easyui-datagrid" style="width:1125px;height:100px; border-radius: 10px;"></table>
</div>
<div id="dlg-buttons">
	<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="save_create_di()" style="width:100px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
	<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="javascript:$('#dlg').dialog('close')" style="width:100px;"><i class="fa fa-ban" aria-hidden="true"></i> Cancel</a>
</div>

<div id="dlg_PO" class="easyui-dialog" style="width:850px;height:250px;" closed="true" data-options="modal:true">
	<table id="dg_po" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>
<!-- END CREATE DI -->

<!-- START VIEW INFO PRF-->
<div id='dlg_viewPRF' class="easyui-dialog" style="width:100%;height:200px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_prf" data-options="modal:true">
	<table id="dg_viewPRF" class="easyui-datagrid" style="width:100%;height:100%;" toolbar="#toolbar_viewPRF"></table>
</div>
<div id="dlg-buttons-view_prf">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="delete_PRF()" style="width:90px">Delete PRF</a>
</div>
<!-- END VIEW INFO PRF-->

<!-- START VIEW INFO PO-->
<div id='dlg_viewPO' class="easyui-dialog" style="width:100%;height:200px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewPO" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>
<!-- END VIEW INFO PO-->

<!-- START VIEW INFO PLAN ASSEMBLING-->
<div id='dlg_viewPLAN' class="easyui-dialog" style="width:1250px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewPLAN" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>
<!-- END VIEW INFO PLAN ASSEMBLING-->

<!-- START VIEW INFO PLAN WO-->
<div id='dlg_viewPLANWO' class="easyui-dialog" style="width:1300px;height:500px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" 
	onclick="print_wo()" style="width:90px">Print Plan</a>
	<table id="dg_viewPLANWO" class="easyui-datagrid" style="width:100%;height:90%;"></table>
</div>
</div>
<!-- END VIEW INFO PLAN WO-->

<!-- INFO OST -->
<div id='dlg_ost' class="easyui-dialog" style="width:1050px;height:200px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-ost" data-options="modal:true">
	<table id="dg_ost" class="easyui-datagrid" style="width:100%;height:auto;"></table>
</div>
<!-- END INFO OST -->

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
		}else{
			return new Date();
		}
	}

	function AddDate(count) {
	    var someDate = new Date();
	    someDate.setDate(someDate.getDate() + count); 
	    var dd = someDate.getDate();
	    var mm = someDate.getMonth() + 1;
	    var y = someDate.getFullYear();

	    var someFormattedDate = y + '-'+ (mm<10?('0'+mm):mm) + '-'+ (dd<10?('0'+dd):dd);
	  	return someFormattedDate
	}

	function AddDateII(count) {
	    var someDate = new Date();
	    someDate.setDate(someDate.getDate() + count); 
	    var dd = someDate.getDate();
	    var mm = someDate.getMonth() + 1;
	    var y = someDate.getFullYear();
	    var month = ["-","JAN", "FEB", "MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
	    var someFormattedDate = dd + '-'+ month[parseInt(mm)] + '-'+ y;
	  	return someFormattedDate
	}

	function AddDateIII(count) {
	    var someDate = new Date();
	    someDate.setDate(someDate.getDate() + count); 
	    var weekday = new Array(7);
		weekday[0] = "Sun";
		weekday[1] = "Mon";
		weekday[2] = "Tue";
		weekday[3] = "Wed";
		weekday[4] = "Thu";
		weekday[5] = "Fri";
		weekday[6] = "Sat";
		var hr = weekday[someDate.getDay()];
	    var dd = someDate.getDate();
	    var mm = someDate.getMonth() + 1;
	    var yy = someDate.getFullYear();
	    var y = parseInt(yy)-2000;
	    var month = ["-","JAN", "FEB", "MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
	    var someFormattedDate = hr+'<br/>'+dd+'-'+ month[parseInt(mm)]+'-'+y;
	  	return someFormattedDate
	}

	var n_plan = '';		var i_plan = '';
	var date = new Date();

	var y = date.getFullYear();
	var m = date.getMonth();
	var d = date.getDate();

	var d1 = new Date(y,m,d+1);				var d31 = new Date(y,m,d+31);			var d61 = new Date(y,m,d+61);
	var d2 = new Date(y,m,d+2);				var d32 = new Date(y,m,d+32);			var d62 = new Date(y,m,d+62);
	var d3 = new Date(y,m,d+3);				var d33 = new Date(y,m,d+33);			var d63 = new Date(y,m,d+63);
	var d4 = new Date(y,m,d+4);				var d34 = new Date(y,m,d+34);			var d64 = new Date(y,m,d+64);
	var d5 = new Date(y,m,d+5);				var d35 = new Date(y,m,d+35);			var d65 = new Date(y,m,d+65);
	var d6 = new Date(y,m,d+6);				var d36 = new Date(y,m,d+36);			var d66 = new Date(y,m,d+66);
	var d7 = new Date(y,m,d+7);				var d37 = new Date(y,m,d+37);			var d67 = new Date(y,m,d+67);
	var d8 = new Date(y,m,d+8);				var d38 = new Date(y,m,d+38);			var d68 = new Date(y,m,d+68);
	var d9 = new Date(y,m,d+9);				var d39 = new Date(y,m,d+39);			var d69 = new Date(y,m,d+69);
	var d10 = new Date(y,m,d+10);			var d40 = new Date(y,m,d+40);			var d70 = new Date(y,m,d+70);
	var d11 = new Date(y,m,d+11);			var d41 = new Date(y,m,d+41);			var d71 = new Date(y,m,d+71);
	var d12 = new Date(y,m,d+12);			var d42 = new Date(y,m,d+42);			var d72 = new Date(y,m,d+72);
	var d13 = new Date(y,m,d+13);			var d43 = new Date(y,m,d+43);			var d73 = new Date(y,m,d+73);
	var d14 = new Date(y,m,d+14);			var d44 = new Date(y,m,d+44);			var d74 = new Date(y,m,d+74);
	var d15 = new Date(y,m,d+15);			var d45 = new Date(y,m,d+45);			var d75 = new Date(y,m,d+75);
	var d16 = new Date(y,m,d+16);			var d46 = new Date(y,m,d+46);			var d76 = new Date(y,m,d+76);
	var d17 = new Date(y,m,d+17);			var d47 = new Date(y,m,d+47);			var d77 = new Date(y,m,d+77);
	var d18 = new Date(y,m,d+18);			var d48 = new Date(y,m,d+48);			var d78 = new Date(y,m,d+78);
	var d19 = new Date(y,m,d+19);			var d49 = new Date(y,m,d+49);			var d79 = new Date(y,m,d+79);
	var d20 = new Date(y,m,d+20);			var d50 = new Date(y,m,d+50);			var d80 = new Date(y,m,d+80);
	var d21 = new Date(y,m,d+21);			var d41 = new Date(y,m,d+51);			var d81 = new Date(y,m,d+81);
	var d22 = new Date(y,m,d+22);			var d42 = new Date(y,m,d+52);			var d82 = new Date(y,m,d+82);
	var d23 = new Date(y,m,d+23);			var d43 = new Date(y,m,d+53);			var d83 = new Date(y,m,d+83);
	var d24 = new Date(y,m,d+24);			var d44 = new Date(y,m,d+54);			var d84 = new Date(y,m,d+84);
	var d25 = new Date(y,m,d+25);			var d45 = new Date(y,m,d+55);			var d85 = new Date(y,m,d+85);
	var d26 = new Date(y,m,d+26);			var d46 = new Date(y,m,d+56);			var d86 = new Date(y,m,d+86);
	var d27 = new Date(y,m,d+27);			var d47 = new Date(y,m,d+57);			var d87 = new Date(y,m,d+87);
	var d28 = new Date(y,m,d+28);			var d48 = new Date(y,m,d+58);			var d88 = new Date(y,m,d+88);
	var d29 = new Date(y,m,d+29);			var d49 = new Date(y,m,d+59);			var d89 = new Date(y,m,d+89);
	var d30 = new Date(y,m,d+30);			var d50 = new Date(y,m,d+60);			var d90 = new Date(y,m,d+90);

	var month_names = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    
	 function cfd(d){
 		var today = d;
    	var day = today.getDate();
    	var month_index = today.getMonth();
    	var year = today.getFullYear();

	 	return day + "-" + month_names[month_index];
 	} ;  
   

	//$(function(){
	//	addGraph();
	//})

	function addPRF(t,i){
		var date_plan = AddDate(t);
		n_plan = t;
		i_plan = i;
		$.messager.confirm('Confirm','Are you sure you want to Create Purchase Requestion?',function(r){
			if(r){
				$.ajax({
					type: 'GET',
					url: '../../json/json_cek_leadtime.php?day='+t+'&item_no='+i,
					data: { kode:'kode' },
					success: function(data){
						$.messager.confirm('INFORMATION', data[0].lead, function(x){
							if(x){
								$('#dlg_add').dialog('open').dialog('setTitle','Create Purchase Requestion');
								$('#prf_no_add').textbox('setValue','');
								$('#remark_add').textbox('setValue','');
								$('#dg_add').datagrid('loadData',[]);
								//$('#prf_date_add').datebox('setValue',date_plan);
								
								// console.log('mrp-rm/mrp_rm_plan_getItem.php?item_no='+i+'&date='+date_plan+'&no=4&sts=ztb_mrp_data_pck');

								$('#dg_add').datagrid({
									url: '../mrp-rm/mrp_rm_plan_getItem.php?item_no='+i+'&date='+date_plan+'&no=4',
								    singleSelect: true,
								    fitColumns: true,
									rownumbers: true,
								    columns:[[
									    {field:'ITEM_NO', title:'ITEM NO.', width:65, halign: 'center', align: 'center'},
									    {field:'DESCRIPTION', title:'DESCRIPTION', width: 150, halign: 'center'},
									    {field:'UNIT', title:'UoM', halign: 'center', width:45, align:'center'},
									    {field:'STANDARD_PRICE', title:'STANDARD PRICE', halign: 'center', width:80, align:'right', editor:{
									    																								type:'numberbox',
									    																								options:{precision:8,groupSeparator:','}
									    																							}
									    },
									    {field:'REQUIRE_DATE', title: 'REQUIRE DATE', halign: 'center', width: 80, editor:{
									    																				type:'datebox',
									    																				options:{formatter:myformatter,parser:myparser}
									    																			}

									    },
									    {field:'QTY', title:'QTY', align:'right', halign: 'center', width:70, editor:{
									    																		type:'numberbox',
									    																		options:{precision:0,groupSeparator:','}
									    																	}
									    },
									    {field:'AMT', title:'ESTIMATE PRICE', halign: 'center', width:80, align:'right', editor:{
									    																					type:'numberbox',
									    																					options:{precision:2,groupSeparator:','}
									    																				}
									    },
									    {field:'OHSAS', title:'DATE CODE', halign: 'center', width:80, align:'right', editor:
									    																								{type:'textbox'}
									    },
									    {field:'UOM_Q', hidden: true}
								    ]],
								    onClickRow:function(row){
								    	$(this).datagrid('beginEdit', row);
								    },
								    onBeginEdit:function(rowIndex){
								        var editors = $('#dg_add').datagrid('getEditors', rowIndex);
								        var n1 = $(editors[0].target);
								        var n2 = $(editors[1].target);
								        var n3 = $(editors[2].target);
								        var n4 = $(editors[3].target);
								        var n5 = $(editors[4].target);
								        n1.add(n3).numberbox({
								            onChange:function(){
								                var amt = n1.numberbox('getValue')*n3.numberbox('getValue');
								                n4.numberbox('setValue',amt);
								            }
								        })
								    }
								});
							}
						});
					}
				});

				
			}	
		});
	}

	function simpan(){	
		var ck_dsign='false';
		var dataRows = [];
		if($('#ck_new_add').attr("checked")){
			ck_dsign='true';
		}

		var t = $('#dg_add').datagrid('getRows');
		var total = t.length;
		var jmrow=0;
		
		for(i=0;i<total;i++){
			
			jmrow = i+1;
			$('#dg_add').datagrid('endEdit',i);
			// $.post('purchase_req_save.php',{
			dataRows.push({
				pu_sts: 'MRPP',
				pu_prf: $('#prf_no_add').textbox('getValue'),
				pu_line: jmrow,
				pu_date: $('#prf_date_add').datebox('getValue'),
				pu_cust_po_no: $('#cust_pono_add').textbox('getValue'),
				pu_ck_new: ck_dsign,
				pu_rmark: $('#remark_add').textbox('getValue'),
				pu_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
				pu_unit: $('#dg_add').datagrid('getData').rows[i].UOM_Q,
				pu_s_price: $('#dg_add').datagrid('getData').rows[i].STANDARD_PRICE.replace(/,/g,''),
				pu_require: $('#dg_add').datagrid('getData').rows[i].REQUIRE_DATE,
				pu_qty: $('#dg_add').datagrid('getData').rows[i].QTY.replace(/,/g,''),
				pu_amt: $('#dg_add').datagrid('getData').rows[i].AMT,
				pu_ohsas: $('#dg_add').datagrid('getData').rows[i].OHSAS
			});
		}
			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			
			$.post('../../prf/purchase_req_save.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>PRF No. : '+$('#prf_no_add').textbox('getValue'),'info');
					$.messager.progress('close');
					$.messager.confirm('Confirm','Are you sure you want to print PRF?',function(r){
					if(r){
						window.open('../../prf/purchase_req_print.php?prf='+$('#prf_no_add').textbox('getValue'));
					}
				});
				}else{
					$.post('purchase_req_destroy.php',{prf_no: $('#prf_no_add').textbox('getValue')},'json');
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		
			// $.messager.alert('INFORMATION','Insert Data Success..!!<br/>PRF No. : '+$('#prf_no_add').textbox('getValue'),'info');
	}

	function savePRF(){
		var url='';
		$.ajax({
			type: 'GET',
			url: '../../json/json_kode_prf.php',
			data: { kode:'kode' },
			success: function(data){
				if(data[0].kode == 'UNDEFINIED'){
					$.messager.alert('INFORMATION','kode PRF Error..!!','info');
				}else{
					$('#prf_no_add').textbox('setValue', data[0].kode);
					simpan();	
				}
			}
		});
	}

	function infoPRF(a,b){
		var tgl_plan = AddDate(a);
		$('#dlg_viewPRF').dialog('open').dialog('setTitle','VIEW INFO PURCHASE REQUESTION ('+AddDateII(a)+')');
		console.log('mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan);
		$('#dg_viewPRF').datagrid({
			url: '../mrp-rm/mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan,
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
		    columns:[[
			    {field:'PRF_NO', title:'PRF NO.', width:115, halign: 'center', align: 'center'},
			    {field:'PRF_DATE', title:'PRF DATE', width: 100, halign: 'center'},
			    {field:'ITEM_NO', title:'ITEM NO.', width: 90, halign: 'center'},
			    {field:'DESCRIPTION', title:'DESCRIPTION', width: 330, halign: 'center'},
			    {field:'UNIT', title:'UoM', width: 50, halign: 'center'},
			    {field:'ESTIMATE_PRICE', title:'ESTIMATE PRICE', width: 120, halign: 'center'},
			    {field:'QTY', title:'QTY PRF', width: 100, halign: 'center', align: 'right'},
			    {field:'PO_NO', title:'PO NO.', width:115, halign: 'center', align: 'center'},
			    {field:'QTY_PO', title:'QTY PO', width: 100, halign: 'center', align: 'right'},
			    {field:'ETA', title:'ETA', width: 100, halign: 'center'}
			]]
		});
	}

	function delete_PRF(){
		var row = $('#dg_viewPRF').datagrid('getSelected');	
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
				if(r){
					//alert (row.PRF_NO+' - '+row.ITEM_NO);
					$.post('mrp_rm_destroy.php',{prf_no: row.PRF_NO, item: row.ITEM_NO},function(result){
						alert(result.successMsg);
						if (result.successMsg=='success'){
                            $('#dg_viewPRF').dialog('close');
							$('#dg_plan').datagrid('reload');
                            console.log(result.successMsg);
                        }else{
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

	function add_DI(t,i){
		var date_plan = AddDate(t);
		$.messager.confirm('Confirm','Are you sure you want to Create Delivery Instruction?',function(r){
			if(r){
				$.ajax({
					type: 'GET',
					url: 'json/json_cek_leadtime.php?day='+t+'&item_no='+i,
					data: { kode:'kode' },
					success: function(data){
						$.messager.confirm('INFORMATION', data[0].lead, function(x){
							if(x){
								$('#dlg').dialog('open').dialog('setTitle','Create Delivery Instruction');
								$('#cmb_company').combobox('setValue','');
								$('#txt_attn').textbox('setValue','');
								$('#txt_di_no').textbox('setValue','');
								$('#di_date').datebox('setValue',date_plan);
								$('#dg_create').datagrid('loadData',[]);
								$('#dg_create').datagrid({
									url: 'mrp_pm_plan_di_create.php?item_no='+i,
									rownumbers: true,
									fitColumns: true,
									singleSelect: true,
								    columns:[[
						                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', width:60, sortable: true},
						                {field:'ITEM', title:'ITEM NAME', halign:'center', width:80},
						                {field:'DESCRIPTION', title:'DESCRIPTION', halign:'center', width:200},
						                {field:'UNIT',title:'UoM', halign:'center', width:40, align: 'center'},
						                {field:'INVENTORY', title:'THIS<br/>INVENTORY', halign:'center', align:'right', width:70},
						                {field:'BALANCE',title:'BALANCE', halign:'center', align:'right', width:70, sortable: true},
						                {field:'BLC', title:'BALANCE', halign: 'center',width:80, align:'right', editor:{
									    																			type:'numberbox',
									    																			options:{precision:0,groupSeparator:','},
									    																			disabled:true
									    																		}
									    ,hidden: true},//
						                {field:'QTY', title:'QTY', align:'right', halign: 'center', width:70, 
						                	editor:{
						                		type:'numberbox',
						                		options:{
						                			precision:0,
						                			groupSeparator:','
						                		}
						                	}},
						                {field:'ORIGIN_CODE',hidden: true},
						                {field:'UOM_Q',hidden: true}
									]],
									onClickRow:function(row){
								    	$(this).datagrid('beginEdit', row);
								    },
								    onBeginEdit:function(rowIndex){
								        var editors = $('#dg_create').datagrid('getEditors', rowIndex);
								        var n1 = $(editors[0].target);
								        var n2 = $(editors[1].target);
								        n2.numberbox({
								            onChange:function(){
								                var blc = n1.numberbox('getValue');
								                if(n2.numberbox('getValue').replace(/,/g,'') > blc){
													$.messager.confirm('Confirm','actual value over',function(r){
														if(r){
															//alert(blc+'-'+n2.numberbox('getValue').replace(/,/g,''))
															n2.numberbox('setValue',blc);
															
														}else{
															//alert(blc+'-'+n2.numberbox('getValue').replace(/,/g,''))
															n2.numberbox('setValue',0);
														}		
													});
								                }
								            }
								        })
								    }
								});
							}
						});
					}
				});
			}	
		});
	}

	function save_create_di(){
		$.messager.progress({
		    title:'Please waiting',
		    msg:'Save data...'
		});

		var vndr = $('#cmb_company').combobox('getValue');
		var attn = $('#txt_attn').textbox('getValue');
		var didate = $('#di_date').datebox('getValue');

		if(vndr=='' || attn=='' || didate==''){
			$.messager.alert("WARNING","Required Field Can't Empty!","warning");
			$.messager.progress('close');
		}else{
			$.ajax({
				type: 'GET',
				url: 'json/json_kode_di_mrp.php',
				data: { kode:'kode' },
				success: function(data){
					if((data[0].kode)!='UNDEFINIED'){
						$('#txt_di_no').textbox('setValue',data[0].kode);
						simpan_di();
					}else{
						$.messager.alert("WARNING","DI No. Already Exist..!!","warning");
						$.messager.progress('close');
					}
				}
			});
		}
	}

	function simpan_di(){
		var dataRows = [];
		var t = $('#dg_create').datagrid('getRows');
		var total = t.length;
		for(i=0;i<total;i++){
			jmrow = i+1;
			$('#dg_create').datagrid('endEdit',i);

			var qty = $('#dg_create').datagrid('getData').rows[i].QTY.replace(/,/g,'');
			var bal_qty = $('#dg_create').datagrid('getData').rows[i].BALANCE.replace(/,/g,'');

			if(qty > bal_qty){
				$.messager.alert("WARNING","Required Field Can't Empty!","warning");
			}else{
				dataRows.push({
					di_vendor: $('#cmb_company').combobox('getValue'),
					di_attn: $('#txt_attn').textbox('getValue'),
					di_no: $('#txt_di_no').textbox('getValue'),
					di_date: $('#di_date').datebox('getValue'),
					di_line: jmrow,
					di_req: 'AGUSMAN SURYA',
					di_po: '',
					di_po_line: '',
					di_item: $('#dg_create').datagrid('getData').rows[i].ITEM_NO,
					di_org_code: $('#dg_create').datagrid('getData').rows[i].ORIGIN_CODE,
					di_qty: $('#dg_create').datagrid('getData').rows[i].QTY.replace(/,/g,''),
					di_uomq: $('#dg_create').datagrid('getData').rows[i].UOM_Q,
					di_unit: $('#dg_create').datagrid('getData').rows[i].UNIT
				});
			}
		}

		var myJSON=JSON.stringify(dataRows);
		var str_unescape=unescape(myJSON);

		$.post('mrp_pm_plan_di_save.php',{
			data: unescape(str_unescape)
		}).done(function(res){
			if(res == '"success"'){
				$('#dlg').dialog('close');
				$('#dg_create').datagrid('reload');
				$.messager.alert('INFORMATION','Insert Data Success..!!<br/>DI No. : '+$('#txt_di_no').textbox('getValue'),'info');
				$.messager.progress('close');
				//print DI
				$.messager.confirm('Confirm','Are you sure you want to print Delivery Instruction?',function(r){
					if(r){
						window.open('di_print.php?di='+$('#txt_di_no').textbox('getValue'));
					}
				});
			}else{
				//$.post('po_destroy.php',{po_no: $('#po_no_add').textbox('getValue')},'json');
				$.messager.alert('ERROR',res,'warning');
				$.messager.progress('close');
			}
		});
	}

	

	function info_DI(a,b){
		var tgl_plan = AddDate(a);
		$('#dlg_viewPRF').dialog('open').dialog('setTitle','VIEW INFO PURCHASE REQUESTION ('+AddDateII(a)+')');
		//alert('mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan);
		$('#dg_viewPRF').datagrid({
			url: 'mrp_rm_plan_info_PRF.php?item_no='+b+'&tgl_plan='+tgl_plan,
			singleSelect: true,
			rownumbers: true,
			fitColumns: true,
		    columns:[[
			    {field:'PRF_NO', title:'PRF NO.', width:115, halign: 'center', align: 'center'},
			    {field:'PRF_DATE', title:'PRF DATE', width: 100, halign: 'center'},
			    {field:'ITEM_NO', title:'ITEM NO.', width: 90, halign: 'center'},
			    {field:'DESCRIPTION', title:'DESCRIPTION', width: 330, halign: 'center'},
			    {field:'UNIT', title:'UoM', width: 50, halign: 'center'},
			    {field:'ESTIMATE_PRICE', title:'ESTIMATE PRICE', width: 120, halign: 'center'},
			    {field:'QTY', title:'QTY PRF', width: 100, halign: 'center', align: 'right'},
			    {field:'PO_NO', title:'PO NO.', width:115, halign: 'center', align: 'center'},
			    {field:'QTY_PO', title:'QTY PO', width: 100, halign: 'center', align: 'right'},
			    {field:'ETA', title:'ETA', width: 100, halign: 'center'}
			]]
		});
	}

	function delete_PRF(){
		var row = $('#dg_viewPRF').datagrid('getSelected');	
		if (row){
			$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
				if(r){
					//alert (row.PRF_NO+' - '+row.ITEM_NO);
					$.post('mrp_rm_destroy.php',{prf_no: row.PRF_NO, item: row.ITEM_NO},function(result){
						alert(result.successMsg);
						if (result.successMsg=='success'){
                            $('#dg_viewPRF').dialog('close');
							$('#dg_plan').datagrid('reload');
                            console.log(result.successMsg);
                        }else{
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


	function infoPO(c,d,e){
		var tgl_plan = AddDate(c);
		if (e == 0 || e == null){
			$.messager.show({
                title: 'INFORMATION',
                msg: 'Value is null'
            });
		}else{
			$('#dlg_viewPO').dialog('open').dialog('setTitle','VIEW INFO PURCHASE ORDER ('+AddDateII(c)+')');
			$('#dg_viewPO').datagrid({
				url: 'mrp_rm_plan_info_PO.php?item_no='+d+'&tgl_plan='+tgl_plan,
				singleSelect: true,
				rownumbers: true,
			    columns:[[
				    {field:'PRF_NO', title:'PRF NO.', width:120, halign: 'center', align: 'center'},
				    {field:'PO_NO', title:'PO NO.', width:90, halign: 'center', align: 'center'},
				    {field:'PO_DATE', title:'PO DATE', width: 90, halign: 'center', align: 'center'},
				    {field:'EX_RATE', title:'EXCHANGE<br/>RATE', width: 80, halign: 'center', align: 'center'},
				    {field:'CURR_SHORT', title:'CURRENCY', width: 80, halign: 'center', align: 'center'},
				    {field:'ITEM_NO', title:'ITEM NO.', width: 80, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION', title:'DESCRIPTION', width: 300, halign: 'center'},
				    {field:'UNIT', title:'UoM', width: 50, halign: 'center', align: 'center'},
				    {field:'U_PRICE', title:'PRICE', width: 120, halign: 'center', align: 'right'},
				    {field:'QTY', title:'QTY', width: 100, halign: 'center', align: 'right'},
				    {field:'AMT_O', title:'AMOUNT<br/>ORIGINAL', width: 120, halign: 'center', align: 'right'},
				    {field:'AMT_L', title:'AMOUNT<br/>LOCAL', width: 120, halign: 'center', align: 'right'}
				]]
			});
		}
	}

	function info_ost(p){
		$('#dlg_ost').dialog('open').dialog('setTitle','VIEW INFO OUTSTANDING ('+p+')');
		$('#dg_ost').datagrid({
			url: '../mrp-rm/mrp_rm_plan_info_ost.php?item_no='+p,
			singleSelect: true,
			rownumbers: true,
		    columns:[[
			    {field:'PO_NO', title:'PURCHASE NO.', width: 120, halign: 'center'},
			    {field:'LINE_NO', title:'LINE', width:90, halign: 'center', align: 'center'},
			    {field:'ITEM_NO', title:'ITEM NO.', width: 80, halign: 'center', align: 'center'},
			    {field:'DESCRIPTION', title:'DESCRIPTION', width: 300, halign: 'center'},
			    {field:'ETA', title:'ETA FI', width: 90, halign: 'center', align: 'center'},
			    {field:'QTY', title:'QTY PO', width: 100, halign: 'center', align: 'right'},
			    {field:'GR_QTY', title:'RECEIVE', width: 100, halign: 'center', align: 'right'},
			    {field:'BAL_QTY', title:'BALANCE', width: 100, halign: 'center', align: 'right'}
			]]
		});
	}

	function print_wo(){
		$('#dg_viewPLANWO').datagrid('toExcel','sales_report.xls')
	}

	function info_plan(a){
		$('#dlg_viewPLANWO').dialog('open').dialog('setTitle','VIEW INFO WO DETAILS');
		$('#dg_viewPLANWO').datagrid({
			url:'mrp_pm_plan_get_wo.php?cmb_item_no='+a,
		singleSelect: true,
		frozenColumns:[[
			{field:'ITEM_NO',title:'<b>ITEM<br/>NO</b>',width:60, halign: 'center', align: 'center'},
			{field:'WORK_ORDER',title:'<b>WORK<br/>ORDER</b>',width:180, halign: 'center'},
			{field:'ITEM_NAME',title:'<b>ITEM<br/>NAME</b>',width:250, halign: 'center', align: 'left'},
			{field:'CR_DATE',title:'<b>CR DATE</b>',width:100, halign: 'center', align: 'center'},
			{field:'QTY',title:'<b>QTY</b>',width:100, halign: 'center', align: 'right'},
			{field:'STATUS',title:'<b>STATUS</b>',width:100, halign: 'center', align: 'right'},
			{field:'DATE_CODE',title:'<b>DATE CODE</b>',width:100, halign: 'center', align: 'right'},
		]],
		columns:[[
			{field:'N_1',title: '<b>'+AddDateIII(1)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_2',title: '<b>'+AddDateIII(2)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_3',title: '<b>'+AddDateIII(3)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_4',title: '<b>'+AddDateIII(4)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_5',title: '<b>'+AddDateIII(5)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_6',title: '<b>'+AddDateIII(6)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_7',title: '<b>'+AddDateIII(7)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_8',title: '<b>'+AddDateIII(8)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_9',title: '<b>'+AddDateIII(9)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_10',title: '<b>'+AddDateIII(10)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_11',title: '<b>'+AddDateIII(11)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_12',title: '<b>'+AddDateIII(12)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_13',title: '<b>'+AddDateIII(13)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_14',title: '<b>'+AddDateIII(14)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_15',title: '<b>'+AddDateIII(15)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_16',title: '<b>'+AddDateIII(16)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_17',title: '<b>'+AddDateIII(17)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_18',title: '<b>'+AddDateIII(18)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_19',title: '<b>'+AddDateIII(19)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_20',title: '<b>'+AddDateIII(20)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_21',title: '<b>'+AddDateIII(21)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_22',title: '<b>'+AddDateIII(22)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_23',title: '<b>'+AddDateIII(23)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_24',title: '<b>'+AddDateIII(24)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_25',title: '<b>'+AddDateIII(25)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_26',title: '<b>'+AddDateIII(26)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_27',title: '<b>'+AddDateIII(27)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_28',title: '<b>'+AddDateIII(28)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_29',title: '<b>'+AddDateIII(29)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_30',title: '<b>'+AddDateIII(30)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_31',title: '<b>'+AddDateIII(31)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_32',title: '<b>'+AddDateIII(32)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_33',title: '<b>'+AddDateIII(33)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_34',title: '<b>'+AddDateIII(34)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_35',title: '<b>'+AddDateIII(35)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_36',title: '<b>'+AddDateIII(36)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_37',title: '<b>'+AddDateIII(37)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_38',title: '<b>'+AddDateIII(38)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_39',title: '<b>'+AddDateIII(39)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_40',title: '<b>'+AddDateIII(40)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_41',title: '<b>'+AddDateIII(41)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_42',title: '<b>'+AddDateIII(42)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_43',title: '<b>'+AddDateIII(43)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_44',title: '<b>'+AddDateIII(44)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_45',title: '<b>'+AddDateIII(45)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_46',title: '<b>'+AddDateIII(46)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_47',title: '<b>'+AddDateIII(47)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_48',title: '<b>'+AddDateIII(48)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_49',title: '<b>'+AddDateIII(49)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_50',title: '<b>'+AddDateIII(50)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_51',title: '<b>'+AddDateIII(51)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_52',title: '<b>'+AddDateIII(52)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_53',title: '<b>'+AddDateIII(53)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_54',title: '<b>'+AddDateIII(54)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_55',title: '<b>'+AddDateIII(55)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_56',title: '<b>'+AddDateIII(56)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_57',title: '<b>'+AddDateIII(57)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_58',title: '<b>'+AddDateIII(58)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_59',title: '<b>'+AddDateIII(59)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_60',title: '<b>'+AddDateIII(60)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_61',title: '<b>'+AddDateIII(61)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_62',title: '<b>'+AddDateIII(62)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_63',title: '<b>'+AddDateIII(63)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_64',title: '<b>'+AddDateIII(64)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_65',title: '<b>'+AddDateIII(65)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_66',title: '<b>'+AddDateIII(66)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_67',title: '<b>'+AddDateIII(67)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_68',title: '<b>'+AddDateIII(68)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_69',title: '<b>'+AddDateIII(69)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_70',title: '<b>'+AddDateIII(70)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_71',title: '<b>'+AddDateIII(71)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_72',title: '<b>'+AddDateIII(72)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_73',title: '<b>'+AddDateIII(73)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_74',title: '<b>'+AddDateIII(74)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_75',title: '<b>'+AddDateIII(75)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_76',title: '<b>'+AddDateIII(76)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_77',title: '<b>'+AddDateIII(77)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_78',title: '<b>'+AddDateIII(78)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_79',title: '<b>'+AddDateIII(79)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_80',title: '<b>'+AddDateIII(80)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_81',title: '<b>'+AddDateIII(81)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_82',title: '<b>'+AddDateIII(82)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_83',title: '<b>'+AddDateIII(83)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_84',title: '<b>'+AddDateIII(84)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_85',title: '<b>'+AddDateIII(85)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_86',title: '<b>'+AddDateIII(86)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_87',title: '<b>'+AddDateIII(87)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_88',title: '<b>'+AddDateIII(88)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_89',title: '<b>'+AddDateIII(89)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_90',title: '<b>'+AddDateIII(90)+'</b>', width:100, halign: 'center', align: 'right'}
		]]
		});
	}

	/*function addGraph(){
		var pesan = '';	
		// alert('view_mrp_get.php?item_no=<?php //echo $parm;?>&flag=<?php //echo $flag;?>');
        var data;
        var sts = 'RUNNING'
		$.ajax({
			type: 'GET',
			dataType: "json",
			url: 'view_mrp_get.php?item_no=<?php //echo $parm;?>&flag=<?php //echo $flag;?>',
			data: data,
			success: function (data) {

			// ####################################################### CHART 2 $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
			var namaitem = "MATERIAL INVENTORY FOR  " + data[0].ITEM_DESC ;
		
			
			var chart = new CanvasJS.Chart("chartContainer", {
				animationEnabled: true,
				exportEnabled: true,
				title:{
					text: namaitem  

				}, 
				axisY:{
					title: "ITO Days",
					labelFontSize: 12,
				},
				axisX:{
					title: "Date",
					labelFontSize: 10,
				},


				toolTip: {
					shared: true
				},
				
				data: [
				
				{        
					type: "line",  
					name: "Max ITO Days",  

					showInLegend: true,
					dataPoints: [
						{ label: d1.toLocaleDateString(), y: parseInt(data[1].N_1) },     
						{ label: d2.toLocaleDateString(), y: parseInt(data[1].N_2) },     
						{ label: d3.toLocaleDateString(), y: parseInt(data[1].N_3) },     
						{ label: d4.toLocaleDateString(), y: parseInt(data[1].N_4) },     
						{ label: d5.toLocaleDateString(), y: parseInt(data[1].N_5) },
						{ label: d6.toLocaleDateString(), y: parseInt(data[1].N_6) },
						{ label: d7.toLocaleDateString(), y: parseInt(data[1].N_7) },     
						{ label: d8.toLocaleDateString(), y: parseInt(data[1].N_8) },     
						{ label: d9.toLocaleDateString(), y: parseInt(data[1].N_9) },     
						{ label: d10.toLocaleDateString(), y: parseInt(data[1].N_10) },
						{ label: d11.toLocaleDateString(), y: parseInt(data[1].N_11) },     
						{ label: d12.toLocaleDateString(), y: parseInt(data[1].N_12) },     
						{ label: d13.toLocaleDateString(), y: parseInt(data[1].N_13) },     
						{ label: d14.toLocaleDateString(), y: parseInt(data[1].N_14) },     
						{ label: d15.toLocaleDateString(), y: parseInt(data[1].N_15) },
						{ label: d16.toLocaleDateString(), y: parseInt(data[1].N_16) },
						{ label: d17.toLocaleDateString(), y: parseInt(data[1].N_17) },     
						{ label: d18.toLocaleDateString(), y: parseInt(data[1].N_18) },     
						{ label: d19.toLocaleDateString(), y: parseInt(data[1].N_19) }, 
						{ label: d20.toLocaleDateString(), y: parseInt(data[1].N_20) },
						{ label: d21.toLocaleDateString(), y: parseInt(data[1].N_21) },
						{ label: d22.toLocaleDateString(), y: parseInt(data[1].N_22) },
						{ label: d23.toLocaleDateString(), y: parseInt(data[1].N_23) },
						{ label: d24.toLocaleDateString(), y: parseInt(data[1].N_24) },
						{ label: d25.toLocaleDateString(), y: parseInt(data[1].N_25) },
						{ label: d26.toLocaleDateString(), y: parseInt(data[1].N_26) },
						{ label: d27.toLocaleDateString(), y: parseInt(data[1].N_27) },
						{ label: d28.toLocaleDateString(), y: parseInt(data[1].N_28) },
						{ label: d29.toLocaleDateString(), y: parseInt(data[1].N_29) },
						{ label: d30.toLocaleDateString(), y: parseInt(data[1].N_30) }, 
						{ label: d31.toLocaleDateString(), y: parseInt(data[1].N_31) }, 
						{ label: d32.toLocaleDateString(), y: parseInt(data[1].N_32) }, 
						{ label: d33.toLocaleDateString(), y: parseInt(data[1].N_33) }, 
						{ label: d34.toLocaleDateString(), y: parseInt(data[1].N_34) }, 
						{ label: d35.toLocaleDateString(), y: parseInt(data[1].N_35) }, 
						{ label: d36.toLocaleDateString(), y: parseInt(data[1].N_36) }, 
						{ label: d37.toLocaleDateString(), y: parseInt(data[1].N_37) }, 
						{ label: d38.toLocaleDateString(), y: parseInt(data[1].N_38) }, 
						{ label: d39.toLocaleDateString(), y: parseInt(data[1].N_39) }, 
						{ label: d40.toLocaleDateString(), y: parseInt(data[1].N_40) }, 
						{ label: d41.toLocaleDateString(), y: parseInt(data[1].N_41) }, 
						{ label: d42.toLocaleDateString(), y: parseInt(data[1].N_42) }, 
						{ label: d43.toLocaleDateString(), y: parseInt(data[1].N_43) }, 
						{ label: d44.toLocaleDateString(), y: parseInt(data[1].N_44) }, 
						{ label: d45.toLocaleDateString(), y: parseInt(data[1].N_45) }, 
						{ label: d46.toLocaleDateString(), y: parseInt(data[1].N_46) }, 
						{ label: d47.toLocaleDateString(), y: parseInt(data[1].N_47) }, 
						{ label: d48.toLocaleDateString(), y: parseInt(data[1].N_48) }, 
						{ label: d49.toLocaleDateString(), y: parseInt(data[1].N_49) }, 
						{ label: d50.toLocaleDateString(), y: parseInt(data[1].N_50) },
						{ label: d51.toLocaleDateString(), y: parseInt(data[1].N_51) },
						{ label: d52.toLocaleDateString(), y: parseInt(data[1].N_52) },
						{ label: d53.toLocaleDateString(), y: parseInt(data[1].N_53) },
						{ label: d54.toLocaleDateString(), y: parseInt(data[1].N_54) },
						{ label: d55.toLocaleDateString(), y: parseInt(data[1].N_55) },
						{ label: d56.toLocaleDateString(), y: parseInt(data[1].N_56) },
						{ label: d57.toLocaleDateString(), y: parseInt(data[1].N_57) },
						{ label: d58.toLocaleDateString(), y: parseInt(data[1].N_58) },
						{ label: d59.toLocaleDateString(), y: parseInt(data[1].N_59) },
						{ label: d60.toLocaleDateString(), y: parseInt(data[1].N_60) },
						{ label: d61.toLocaleDateString(), y: parseInt(data[1].N_61) },
						{ label: d62.toLocaleDateString(), y: parseInt(data[1].N_62) },
						{ label: d63.toLocaleDateString(), y: parseInt(data[1].N_63) },
						{ label: d64.toLocaleDateString(), y: parseInt(data[1].N_64) },
						{ label: d65.toLocaleDateString(), y: parseInt(data[1].N_65) },
						{ label: d66.toLocaleDateString(), y: parseInt(data[1].N_66) },
						{ label: d67.toLocaleDateString(), y: parseInt(data[1].N_67) },
						{ label: d68.toLocaleDateString(), y: parseInt(data[1].N_68) },
						{ label: d69.toLocaleDateString(), y: parseInt(data[1].N_69) },
						{ label: d70.toLocaleDateString(), y: parseInt(data[1].N_70) },
						{ label: d71.toLocaleDateString(), y: parseInt(data[1].N_71) },
						{ label: d72.toLocaleDateString(), y: parseInt(data[1].N_72) },
						{ label: d73.toLocaleDateString(), y: parseInt(data[1].N_73) },
						{ label: d74.toLocaleDateString(), y: parseInt(data[1].N_74) },
						{ label: d75.toLocaleDateString(), y: parseInt(data[1].N_75) },
						{ label: d76.toLocaleDateString(), y: parseInt(data[1].N_76) },
						{ label: d77.toLocaleDateString(), y: parseInt(data[1].N_77) },
						{ label: d78.toLocaleDateString(), y: parseInt(data[1].N_78) },
						{ label: d79.toLocaleDateString(), y: parseInt(data[1].N_79) },
						{ label: d80.toLocaleDateString(), y: parseInt(data[1].N_80) },
						{ label: d81.toLocaleDateString(), y: parseInt(data[1].N_81) },
						{ label: d82.toLocaleDateString(), y: parseInt(data[1].N_82) },
						{ label: d83.toLocaleDateString(), y: parseInt(data[1].N_83) },
						{ label: d84.toLocaleDateString(), y: parseInt(data[1].N_84) },
						{ label: d85.toLocaleDateString(), y: parseInt(data[1].N_85) },
						{ label: d86.toLocaleDateString(), y: parseInt(data[1].N_86) },
						{ label: d87.toLocaleDateString(), y: parseInt(data[1].N_87) },
						{ label: d88.toLocaleDateString(), y: parseInt(data[1].N_88) },
						{ label: d89.toLocaleDateString(), y: parseInt(data[1].N_89) },
						{ label: d90.toLocaleDateString(), y: parseInt(data[1].N_90) }
					]
				},
				{        
					type: "line",  
					name: "MIN ITO Days",        
					showInLegend: true,
					dataPoints: [
						{ label: d1.toLocaleDateString(), y: parseInt(data[3].N_1) },     
						{ label: d2.toLocaleDateString(), y: parseInt(data[3].N_2) },     
						{ label: d3.toLocaleDateString(), y: parseInt(data[3].N_3) },     
						{ label: d4.toLocaleDateString(), y: parseInt(data[3].N_4) },     
						{ label: d5.toLocaleDateString(), y: parseInt(data[3].N_5) },
						{ label: d6.toLocaleDateString(), y: parseInt(data[3].N_6) },
						{ label: d7.toLocaleDateString(), y: parseInt(data[3].N_7) },     
						{ label: d8.toLocaleDateString(), y: parseInt(data[3].N_8) },     
						{ label: d9.toLocaleDateString(), y: parseInt(data[3].N_9) },     
						{ label: d10.toLocaleDateString(), y: parseInt(data[3].N_10) },
						{ label: d11.toLocaleDateString(), y: parseInt(data[3].N_11) },     
						{ label: d12.toLocaleDateString(), y: parseInt(data[3].N_12) },     
						{ label: d13.toLocaleDateString(), y: parseInt(data[3].N_13) },     
						{ label: d14.toLocaleDateString(), y: parseInt(data[3].N_14) },     
						{ label: d15.toLocaleDateString(), y: parseInt(data[3].N_15) },
						{ label: d16.toLocaleDateString(), y: parseInt(data[3].N_16) },
						{ label: d17.toLocaleDateString(), y: parseInt(data[3].N_17) },     
						{ label: d18.toLocaleDateString(), y: parseInt(data[3].N_18) },     
						{ label: d19.toLocaleDateString(), y: parseInt(data[3].N_19) }, 
						{ label: d20.toLocaleDateString(), y: parseInt(data[3].N_20) },
						{ label: d21.toLocaleDateString(), y: parseInt(data[3].N_21) },
						{ label: d22.toLocaleDateString(), y: parseInt(data[3].N_22) },
						{ label: d23.toLocaleDateString(), y: parseInt(data[3].N_23) },
						{ label: d24.toLocaleDateString(), y: parseInt(data[3].N_24) },
						{ label: d25.toLocaleDateString(), y: parseInt(data[3].N_25) },
						{ label: d26.toLocaleDateString(), y: parseInt(data[3].N_26) },
						{ label: d27.toLocaleDateString(), y: parseInt(data[3].N_27) },
						{ label: d28.toLocaleDateString(), y: parseInt(data[3].N_28) },
						{ label: d29.toLocaleDateString(), y: parseInt(data[3].N_29) },
						{ label: d30.toLocaleDateString(), y: parseInt(data[3].N_30) }, 
						{ label: d31.toLocaleDateString(), y: parseInt(data[3].N_31) }, 
						{ label: d32.toLocaleDateString(), y: parseInt(data[3].N_32) }, 
						{ label: d33.toLocaleDateString(), y: parseInt(data[3].N_33) }, 
						{ label: d34.toLocaleDateString(), y: parseInt(data[3].N_34) }, 
						{ label: d35.toLocaleDateString(), y: parseInt(data[3].N_35) }, 
						{ label: d36.toLocaleDateString(), y: parseInt(data[3].N_36) }, 
						{ label: d37.toLocaleDateString(), y: parseInt(data[3].N_37) }, 
						{ label: d38.toLocaleDateString(), y: parseInt(data[3].N_38) }, 
						{ label: d39.toLocaleDateString(), y: parseInt(data[3].N_39) }, 
						{ label: d40.toLocaleDateString(), y: parseInt(data[3].N_40) }, 
						{ label: d41.toLocaleDateString(), y: parseInt(data[3].N_41) }, 
						{ label: d42.toLocaleDateString(), y: parseInt(data[3].N_42) }, 
						{ label: d43.toLocaleDateString(), y: parseInt(data[3].N_43) }, 
						{ label: d44.toLocaleDateString(), y: parseInt(data[3].N_44) }, 
						{ label: d45.toLocaleDateString(), y: parseInt(data[3].N_45) }, 
						{ label: d46.toLocaleDateString(), y: parseInt(data[3].N_46) }, 
						{ label: d47.toLocaleDateString(), y: parseInt(data[3].N_47) }, 
						{ label: d48.toLocaleDateString(), y: parseInt(data[3].N_48) }, 
						{ label: d49.toLocaleDateString(), y: parseInt(data[3].N_49) }, 
						{ label: d50.toLocaleDateString(), y: parseInt(data[3].N_50) },
						{ label: d51.toLocaleDateString(), y: parseInt(data[3].N_51) },
						{ label: d52.toLocaleDateString(), y: parseInt(data[3].N_52) },
						{ label: d53.toLocaleDateString(), y: parseInt(data[3].N_53) },
						{ label: d54.toLocaleDateString(), y: parseInt(data[3].N_54) },
						{ label: d55.toLocaleDateString(), y: parseInt(data[3].N_55) },
						{ label: d56.toLocaleDateString(), y: parseInt(data[3].N_56) },
						{ label: d57.toLocaleDateString(), y: parseInt(data[3].N_57) },
						{ label: d58.toLocaleDateString(), y: parseInt(data[3].N_58) },
						{ label: d59.toLocaleDateString(), y: parseInt(data[3].N_59) },
						{ label: d60.toLocaleDateString(), y: parseInt(data[3].N_60) },
						{ label: d61.toLocaleDateString(), y: parseInt(data[3].N_61) },
						{ label: d62.toLocaleDateString(), y: parseInt(data[3].N_62) },
						{ label: d63.toLocaleDateString(), y: parseInt(data[3].N_63) },
						{ label: d64.toLocaleDateString(), y: parseInt(data[3].N_64) },
						{ label: d65.toLocaleDateString(), y: parseInt(data[3].N_65) },
						{ label: d66.toLocaleDateString(), y: parseInt(data[3].N_66) },
						{ label: d67.toLocaleDateString(), y: parseInt(data[3].N_67) },
						{ label: d68.toLocaleDateString(), y: parseInt(data[3].N_68) },
						{ label: d69.toLocaleDateString(), y: parseInt(data[3].N_69) },
						{ label: d70.toLocaleDateString(), y: parseInt(data[3].N_70) },
						{ label: d71.toLocaleDateString(), y: parseInt(data[3].N_71) },
						{ label: d72.toLocaleDateString(), y: parseInt(data[3].N_72) },
						{ label: d73.toLocaleDateString(), y: parseInt(data[3].N_73) },
						{ label: d74.toLocaleDateString(), y: parseInt(data[3].N_74) },
						{ label: d75.toLocaleDateString(), y: parseInt(data[3].N_75) },
						{ label: d76.toLocaleDateString(), y: parseInt(data[3].N_76) },
						{ label: d77.toLocaleDateString(), y: parseInt(data[3].N_77) },
						{ label: d78.toLocaleDateString(), y: parseInt(data[3].N_78) },
						{ label: d79.toLocaleDateString(), y: parseInt(data[3].N_79) },
						{ label: d80.toLocaleDateString(), y: parseInt(data[3].N_80) },
						{ label: d81.toLocaleDateString(), y: parseInt(data[3].N_81) },
						{ label: d82.toLocaleDateString(), y: parseInt(data[3].N_82) },
						{ label: d83.toLocaleDateString(), y: parseInt(data[3].N_83) },
						{ label: d84.toLocaleDateString(), y: parseInt(data[3].N_84) },
						{ label: d85.toLocaleDateString(), y: parseInt(data[3].N_85) },
						{ label: d86.toLocaleDateString(), y: parseInt(data[3].N_86) },
						{ label: d87.toLocaleDateString(), y: parseInt(data[3].N_87) },
						{ label: d88.toLocaleDateString(), y: parseInt(data[3].N_88) },
						{ label: d89.toLocaleDateString(), y: parseInt(data[3].N_89) },
						{ label: d90.toLocaleDateString(), y: parseInt(data[3].N_90) }
					]
					
				}, 
				{        
					type: "spline",
					name: "ITO Days",        
					showInLegend: true,
					dataPoints: [
						{ label: d1.toLocaleDateString(), y: parseInt(data[0].N_1 / data[4].N_1) },     
						{ label: d2.toLocaleDateString(), y: parseInt(data[0].N_2/ data[4].N_2) },     
						{ label: d3.toLocaleDateString(), y: parseInt(data[0].N_3/ data[4].N_3) },     
						{ label: d4.toLocaleDateString(), y: parseInt(data[0].N_4/ data[4].N_4) },     
						{ label: d5.toLocaleDateString(), y: parseInt(data[0].N_5 / data[4].N_5) },
						{ label: d6.toLocaleDateString(), y: parseInt(data[0].N_6/ data[4].N_6) },
						{ label: d7.toLocaleDateString(), y: parseInt(data[0].N_7/ data[4].N_7) },     
						{ label: d8.toLocaleDateString(), y: parseInt(data[0].N_8/ data[4].N_8) },     
						{ label: d9.toLocaleDateString(), y: parseInt(data[0].N_9/ data[4].N_9) },     
						{ label: d10.toLocaleDateString(), y: parseInt(data[0].N_10/ data[4].N_10) },
						{ label: d11.toLocaleDateString(), y: parseInt(data[0].N_11/ data[4].N_11) },     
						{ label: d12.toLocaleDateString(), y: parseInt(data[0].N_12/ data[4].N_12) },     
						{ label: d13.toLocaleDateString(), y: parseInt(data[0].N_13/ data[4].N_13) },     
						{ label: d14.toLocaleDateString(), y: parseInt(data[0].N_14/ data[4].N_14) },     
						{ label: d15.toLocaleDateString(), y: parseInt(data[0].N_15/ data[4].N_15) },
						{ label: d16.toLocaleDateString(), y: parseInt(data[0].N_16/ data[4].N_16) },
						{ label: d17.toLocaleDateString(), y: parseInt(data[0].N_17/ data[4].N_17) },     
						{ label: d18.toLocaleDateString(), y: parseInt(data[0].N_18/ data[4].N_18) },     
						{ label: d19.toLocaleDateString(), y: parseInt(data[0].N_19/ data[4].N_19) }, 
						{ label: d20.toLocaleDateString(), y: parseInt(data[0].N_20/ data[4].N_20) },
						{ label: d21.toLocaleDateString(), y: parseInt(data[0].N_21/ data[4].N_21) },
						{ label: d22.toLocaleDateString(), y: parseInt(data[0].N_22/ data[4].N_22) },
						{ label: d23.toLocaleDateString(), y: parseInt(data[0].N_23/ data[4].N_23) },
						{ label: d24.toLocaleDateString(), y: parseInt(data[0].N_24/ data[4].N_24) },
						{ label: d25.toLocaleDateString(), y: parseInt(data[0].N_25/ data[4].N_25) },
						{ label: d26.toLocaleDateString(), y: parseInt(data[0].N_26/ data[4].N_26) },
						{ label: d27.toLocaleDateString(), y: parseInt(data[0].N_27/ data[4].N_27) },
						{ label: d28.toLocaleDateString(), y: parseInt(data[0].N_28/ data[4].N_28) },
						{ label: d29.toLocaleDateString(), y: parseInt(data[0].N_29/ data[4].N_29) },
						{ label: d30.toLocaleDateString(), y: parseInt(data[0].N_30/ data[4].N_30) }, 
						{ label: d31.toLocaleDateString(), y: parseInt(data[0].N_31/ data[4].N_31) }, 
						{ label: d32.toLocaleDateString(), y: parseInt(data[0].N_32/ data[4].N_32) }, 
						{ label: d33.toLocaleDateString(), y: parseInt(data[0].N_33/ data[4].N_33) }, 
						{ label: d34.toLocaleDateString(), y: parseInt(data[0].N_34/ data[4].N_34) }, 
						{ label: d35.toLocaleDateString(), y: parseInt(data[0].N_35/ data[4].N_35) }, 
						{ label: d36.toLocaleDateString(), y: parseInt(data[0].N_36/ data[4].N_36) }, 
						{ label: d37.toLocaleDateString(), y: parseInt(data[0].N_37/ data[4].N_37) }, 
						{ label: d38.toLocaleDateString(), y: parseInt(data[0].N_38/ data[4].N_38) }, 
						{ label: d39.toLocaleDateString(), y: parseInt(data[0].N_39/ data[4].N_39) }, 
						{ label: d40.toLocaleDateString(), y: parseInt(data[0].N_40/ data[4].N_40) }, 
						{ label: d41.toLocaleDateString(), y: parseInt(data[0].N_41/ data[4].N_41) }, 
						{ label: d42.toLocaleDateString(), y: parseInt(data[0].N_42/ data[4].N_42) }, 
						{ label: d43.toLocaleDateString(), y: parseInt(data[0].N_43/ data[4].N_43) }, 
						{ label: d44.toLocaleDateString(), y: parseInt(data[0].N_44/ data[4].N_44) }, 
						{ label: d45.toLocaleDateString(), y: parseInt(data[0].N_45/ data[4].N_45) }, 
						{ label: d46.toLocaleDateString(), y: parseInt(data[0].N_46/ data[4].N_46) }, 
						{ label: d47.toLocaleDateString(), y: parseInt(data[0].N_47/ data[4].N_47) }, 
						{ label: d48.toLocaleDateString(), y: parseInt(data[0].N_48/ data[4].N_48) }, 
						{ label: d49.toLocaleDateString(), y: parseInt(data[0].N_49/ data[4].N_49) }, 
						{ label: d50.toLocaleDateString(), y: parseInt(data[0].N_50/ data[4].N_50) },
						{ label: d51.toLocaleDateString(), y: parseInt(data[0].N_51/ data[4].N_51) },
						{ label: d52.toLocaleDateString(), y: parseInt(data[0].N_52/ data[4].N_52) },
						{ label: d53.toLocaleDateString(), y: parseInt(data[0].N_53/ data[4].N_53) },
						{ label: d54.toLocaleDateString(), y: parseInt(data[0].N_54/ data[4].N_54) },
						{ label: d55.toLocaleDateString(), y: parseInt(data[0].N_55/ data[4].N_55) },
						{ label: d56.toLocaleDateString(), y: parseInt(data[0].N_56/ data[4].N_56) },
						{ label: d57.toLocaleDateString(), y: parseInt(data[0].N_57/ data[4].N_57) },
						{ label: d58.toLocaleDateString(), y: parseInt(data[0].N_58/ data[4].N_58) },
						{ label: d59.toLocaleDateString(), y: parseInt(data[0].N_59/ data[4].N_59) },
						{ label: d60.toLocaleDateString(), y: parseInt(data[0].N_60/ data[4].N_60) },
						{ label: d61.toLocaleDateString(), y: parseInt(data[0].N_61/ data[4].N_61) },
						{ label: d62.toLocaleDateString(), y: parseInt(data[0].N_62/ data[4].N_62) },
						{ label: d63.toLocaleDateString(), y: parseInt(data[0].N_63/ data[4].N_63) },
						{ label: d64.toLocaleDateString(), y: parseInt(data[0].N_64/ data[4].N_64) },
						{ label: d65.toLocaleDateString(), y: parseInt(data[0].N_65/ data[4].N_65) },
						{ label: d66.toLocaleDateString(), y: parseInt(data[0].N_66/ data[4].N_66) },
						{ label: d67.toLocaleDateString(), y: parseInt(data[0].N_67/ data[4].N_67) },
						{ label: d68.toLocaleDateString(), y: parseInt(data[0].N_68/ data[4].N_68) },
						{ label: d69.toLocaleDateString(), y: parseInt(data[0].N_69/ data[4].N_69) },
						{ label: d70.toLocaleDateString(), y: parseInt(data[0].N_70/ data[4].N_70) },
						{ label: d71.toLocaleDateString(), y: parseInt(data[0].N_71/ data[4].N_71) },
						{ label: d72.toLocaleDateString(), y: parseInt(data[0].N_72/ data[4].N_72) },
						{ label: d73.toLocaleDateString(), y: parseInt(data[0].N_73/ data[4].N_73) },
						{ label: d74.toLocaleDateString(), y: parseInt(data[0].N_74/ data[4].N_74) },
						{ label: d75.toLocaleDateString(), y: parseInt(data[0].N_75/ data[4].N_75) },
						{ label: d76.toLocaleDateString(), y: parseInt(data[0].N_76/ data[4].N_76) },
						{ label: d77.toLocaleDateString(), y: parseInt(data[0].N_77/ data[4].N_77) },
						{ label: d78.toLocaleDateString(), y: parseInt(data[0].N_78/ data[4].N_78) },
						{ label: d79.toLocaleDateString(), y: parseInt(data[0].N_79/ data[4].N_79) },
						{ label: d80.toLocaleDateString(), y: parseInt(data[0].N_80/ data[4].N_80) },
						{ label: d81.toLocaleDateString(), y: parseInt(data[0].N_81/ data[4].N_81) },
						{ label: d82.toLocaleDateString(), y: parseInt(data[0].N_82/ data[4].N_82) },
						{ label: d83.toLocaleDateString(), y: parseInt(data[0].N_83/ data[4].N_83) },
						{ label: d84.toLocaleDateString(), y: parseInt(data[0].N_84/ data[4].N_84) },
						{ label: d85.toLocaleDateString(), y: parseInt(data[0].N_85/ data[4].N_85) },
						{ label: d86.toLocaleDateString(), y: parseInt(data[0].N_86/ data[4].N_86) },
						{ label: d87.toLocaleDateString(), y: parseInt(data[0].N_87/ data[4].N_87) },
						{ label: d88.toLocaleDateString(), y: parseInt(data[0].N_88/ data[4].N_88) },
						{ label: d89.toLocaleDateString(), y: parseInt(data[0].N_89/ data[4].N_89) },
						{ label: d90.toLocaleDateString(), y: parseInt(data[0].N_90/ data[4].N_90) }
					]
					
				}]
			});
			chart.render();
			}
			});
	}*/

	function info_assy (f,g,h){
		var tgl_plan = AddDate(f);
		if (h == 0 || h == null){
			$.messager.show({
                title: 'INFORMATION',
                msg: 'Value is null'
            });
		}else{
			$('#dlg_viewPLAN').dialog('open').dialog('setTitle','VIEW INFO MPS ('+AddDateII(f)+')');
			console.log('mrp_pm_plan_info_mps.php?item_no='+g+'&tgl_plan='+tgl_plan);
			$('#dg_viewPLAN').datagrid({
				url: 'mrp_pm_plan_info_mps.php?item_no='+g+'&tgl_plan='+tgl_plan,
				singleSelect: true,
				rownumbers: true,
			    columns:[[
				    {field:'BRAND', title:'BRAND', width: 280, halign: 'center'},
				    {field:'WORK_ORDER', title:'WORK ORDER', width: 150, halign: 'center'},
				    {field:'CR_DATE', title:'CR DATE', width: 80, halign: 'center'},
				    // {field:'STATUS', title:'STATUS', width: 60, halign: 'center', align: 'center'},
				    // {field:'DATE_CODE', title:'DATE CODE', width: 60, halign: 'center', align: 'center'},
				    {field:'QTY', title:'QTY ORDER', width: 80, halign: 'center', align: 'right'},
				    {field:'MPS_QTY', title:'QTY PROD', width: 80, halign: 'center', align: 'right'},
				    {field:'LOWER_ITEM_NO', title:'ITEM NO.', width: 80, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION', title:'DESCRIPTION', width: 300, halign: 'center'},
				    {field:'KONVERSI', title:'CONVERTION', width: 80, halign: 'center', align: 'right'}
				]]
			});
		}
	}

	function run_mrp_item(a){
		if (confirm("This process will takes time around 3-5 minutes, also MRP data now will be erased. if you agree please confirm ?")) {
			alert("Please do not close browser while MRP Item running.");
		    // window.open('http://172.23.225.85/wms/schedule/execute_sp_item.php?item='+a);
			$.messager.progress({
				title:'Please waiting',
				msg:'Save data...'
			});

			$.post('mrp_pm_run_item.php?item=',{
				item_no: a
			}).done(function(res){
				if(res == '"success"'){
					// console.log('mrp_pm_run_item.php?item='+a);
					// console.log(res);
					$.messager.alert('INFORMATION','Running MRP Success..!!','info');
					$('#dg').datagrid('reload');
				}else{
					$.messager.alert('ERROR',res,'warning');
				}
				$.messager.progress('close');
			});
		}else{
			alert("Process cancelled");
		}
	}

	// console.log('mrp_pm_plan_get.php?cmb_item_no=<?php echo $cmb_item_no;?>&sts=<?php echo $sts;?>');
	$('#dg_plan').datagrid({
		url:'mrp_pm_plan_get.php?cmb_item_no=<?php echo $cmb_item_no;?>&sts=<?php echo $sts;?>',
		singleSelect: true,
		frozenColumns:[[
			{field:'NO',title:'<b>NO</b>',width:35, halign: 'center', align: 'center'},
			{field:'ITEM_DESCRIPTION',title:'<b>ITEM<br/>DESCRIPTION</b>',width:250, halign: 'center'},
			{field:'DESCRIPTION',title:'<b>DESCRIPTION</b>',width:250, halign: 'center'}
		]],
		columns:[[
			{field:'N_1',title: '<b>'+AddDateIII(1)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_2',title: '<b>'+AddDateIII(2)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_3',title: '<b>'+AddDateIII(3)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_4',title: '<b>'+AddDateIII(4)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_5',title: '<b>'+AddDateIII(5)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_6',title: '<b>'+AddDateIII(6)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_7',title: '<b>'+AddDateIII(7)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_8',title: '<b>'+AddDateIII(8)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_9',title: '<b>'+AddDateIII(9)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_10',title: '<b>'+AddDateIII(10)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_11',title: '<b>'+AddDateIII(11)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_12',title: '<b>'+AddDateIII(12)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_13',title: '<b>'+AddDateIII(13)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_14',title: '<b>'+AddDateIII(14)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_15',title: '<b>'+AddDateIII(15)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_16',title: '<b>'+AddDateIII(16)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_17',title: '<b>'+AddDateIII(17)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_18',title: '<b>'+AddDateIII(18)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_19',title: '<b>'+AddDateIII(19)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_20',title: '<b>'+AddDateIII(20)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_21',title: '<b>'+AddDateIII(21)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_22',title: '<b>'+AddDateIII(22)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_23',title: '<b>'+AddDateIII(23)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_24',title: '<b>'+AddDateIII(24)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_25',title: '<b>'+AddDateIII(25)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_26',title: '<b>'+AddDateIII(26)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_27',title: '<b>'+AddDateIII(27)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_28',title: '<b>'+AddDateIII(28)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_29',title: '<b>'+AddDateIII(29)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_30',title: '<b>'+AddDateIII(30)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_31',title: '<b>'+AddDateIII(31)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_32',title: '<b>'+AddDateIII(32)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_33',title: '<b>'+AddDateIII(33)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_34',title: '<b>'+AddDateIII(34)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_35',title: '<b>'+AddDateIII(35)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_36',title: '<b>'+AddDateIII(36)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_37',title: '<b>'+AddDateIII(37)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_38',title: '<b>'+AddDateIII(38)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_39',title: '<b>'+AddDateIII(39)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_40',title: '<b>'+AddDateIII(40)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_41',title: '<b>'+AddDateIII(41)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_42',title: '<b>'+AddDateIII(42)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_43',title: '<b>'+AddDateIII(43)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_44',title: '<b>'+AddDateIII(44)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_45',title: '<b>'+AddDateIII(45)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_46',title: '<b>'+AddDateIII(46)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_47',title: '<b>'+AddDateIII(47)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_48',title: '<b>'+AddDateIII(48)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_49',title: '<b>'+AddDateIII(49)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_50',title: '<b>'+AddDateIII(50)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_51',title: '<b>'+AddDateIII(51)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_52',title: '<b>'+AddDateIII(52)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_53',title: '<b>'+AddDateIII(53)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_54',title: '<b>'+AddDateIII(54)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_55',title: '<b>'+AddDateIII(55)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_56',title: '<b>'+AddDateIII(56)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_57',title: '<b>'+AddDateIII(57)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_58',title: '<b>'+AddDateIII(58)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_59',title: '<b>'+AddDateIII(59)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_60',title: '<b>'+AddDateIII(60)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_61',title: '<b>'+AddDateIII(61)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_62',title: '<b>'+AddDateIII(62)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_63',title: '<b>'+AddDateIII(63)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_64',title: '<b>'+AddDateIII(64)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_65',title: '<b>'+AddDateIII(65)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_66',title: '<b>'+AddDateIII(66)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_67',title: '<b>'+AddDateIII(67)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_68',title: '<b>'+AddDateIII(68)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_69',title: '<b>'+AddDateIII(69)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_70',title: '<b>'+AddDateIII(70)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_71',title: '<b>'+AddDateIII(71)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_72',title: '<b>'+AddDateIII(72)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_73',title: '<b>'+AddDateIII(73)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_74',title: '<b>'+AddDateIII(74)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_75',title: '<b>'+AddDateIII(75)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_76',title: '<b>'+AddDateIII(76)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_77',title: '<b>'+AddDateIII(77)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_78',title: '<b>'+AddDateIII(78)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_79',title: '<b>'+AddDateIII(79)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_80',title: '<b>'+AddDateIII(80)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_81',title: '<b>'+AddDateIII(81)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_82',title: '<b>'+AddDateIII(82)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_83',title: '<b>'+AddDateIII(83)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_84',title: '<b>'+AddDateIII(84)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_85',title: '<b>'+AddDateIII(85)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_86',title: '<b>'+AddDateIII(86)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_87',title: '<b>'+AddDateIII(87)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_88',title: '<b>'+AddDateIII(88)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_89',title: '<b>'+AddDateIII(89)+'</b>', width:100, halign: 'center', align: 'right'},
			{field:'N_90',title: '<b>'+AddDateIII(90)+'</b>', width:100, halign: 'center', align: 'right'}
		]]
	});
</script>
</body>
</html>