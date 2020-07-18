<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>DELIVERY INSTRUCTION</title>
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
<?php include ('../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name));?>

<div id="toolbar" style="padding:3px 3px;">
	<fieldset style="float:left;width:400px;height: 70px; border-radius:4px;"><legend><span class="style3"><strong>Delivery Instruction Filter</strong></span></legend>
		<div style="width:400px;float:left">
			<div class="fitem">
				<span style="width:110px;display:inline-block;">DI Date</span>
				<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
				to 
				<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
			</div>
			<div class="fitem">
				<span style="width:110px;display:inline-block;">DI No.</span>
				<select style="width:190px;" name="cmb_di_no" id="cmb_di_no" class="easyui-combobox" data-options=" url:'../json/json_dino_all.php', method:'get', valueField:'di_number', textField:'di_number', panelHeight:'75px'"></select>
				<label><input type="checkbox" name="ck_di_no" id="ck_di_no" checked="true">All</input></label>
			</div>
		</div>
	</fieldset>
	<fieldset style="position:absolute;margin-left:425px;border-radius:4px;width: 500px;height: 70px;"><legend><span class="style3"><strong>Item Filter</strong></span></legend>
		<div class="fitem">
			<span style="width:80px;display:inline-block;">Item No.</span>
			<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
			onSelect:function(rec){
				//alert(rec.id_name_item);
				var spl = rec.id_name_item;
				var sp = spl.split(' - ');
				$('#txt_item_name').textbox('setValue', sp[1]);
			}"></select>
			<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">All</input></label>
		</div>
		<div class="fitem">
			<span style="width:80px;display:inline-block;">Item Name</span>
			<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
		</div>
	</fieldset>
	<fieldset style="margin-left: 950px;border-radius:4px;height: 70px;"><legend><span class="style3"><strong>Print</strong></span></legend></fieldset>
	<div style="padding:5px 6px;">
    	<span style="width:50px;display:inline-block;">SEARCH</span>
		<input style="width:200px; height: 19px; border: 1px solid #0099FF;border-radius: 3px;" onkeypress="filter(event)" name="src" id="src"type="text" />
		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2" onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> Filter Data</a>
		<a href="javascript:void(0)" style="width: 100px;" id="add" class="easyui-linkbutton c2" onclick="create_di()"><i class="fa fa-plus" aria-hidden="true"></i> Create DI</a>
		<a href="javascript:void(0)" style="width: 100px;" id="delete" class="easyui-linkbutton c2" onclick="delete_di()"><i class="fa fa-trash" aria-hidden="true"></i> Delete DI</a>
		<a href="javascript:void(0)" style="width: 100px;" id="print" class="easyui-linkbutton c2" onclick="print_di()"><i class="fa fa-print" aria-hidden="true"></i> Print DI</a>
		<a href="javascript:void(0)" style="width: 100px;" id="edit" class="easyui-linkbutton c2" onclick="send_email()"><i class="fa fa-envelope-o" aria-hidden="true"></i> Send Email</a><!-- disabled="" -->
	</div>
</div>

<!-- START CREATE DI -->
<div id="dlg" class="easyui-dialog" style="width:1150px;height:500px;padding:5px 5px" closed="true" buttons="#dlg-buttons" data-options="modal:true, position: 'center'">
	<fieldset style="border:1px solid #d0d0d0; border-radius:2px; width:720px; height: 100px; float:left;"><legend><span class="style3"><strong>Select Vendor</strong></span></legend>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">VENDOR</span>
			<select style="width:400px;" name="cmb_company" id="cmb_company" class="easyui-combobox" data-options=" url:'json/json_company_DI.php', method:'get', valueField:'COMPANY_CODE', textField:'COMB_COMPANY', panelHeight:'40px'" required=""></select>
			<a href="javascript:void(0)" style="width: 200px;" class="easyui-linkbutton c2" onclick="search_item();"><i class="fa fa-search" aria-hidden="true"></i> SEARCH ITEM</a>
		</div>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">ATTN</span>
			<input style="width:400px;" name="txt_attn" id="txt_attn" class="easyui-textbox" required="" />
		</div>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">REQUESTOR</span>
			<input style="width:400px;" name="txt_req" id="txt_req" class="easyui-textbox"/>
		</div>
	</fieldset>
	<fieldset style="position:absolute; border:1px solid #d0d0d0; margin-left:750px; border-radius:2px; width:350px; height: 100px;"><legend><span class="style3"><strong> Insert Delivery Instruction </strong></span></legend>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">DI No.</span>
			<input style="width:120px;" name="txt_di_no" id="txt_di_no" class="easyui-textbox" required="" />
		</div>
		<div class="fitem">
			<span style="width:100px;display:inline-block;margin-left: 5px;">DI Date</span>
			<input style="width:120px;" name="di_date" id="di_date" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>" required=""/> 
		</div>
	</fieldset>
	<div style="clear:both;margin-bottom:10px;"></div>
	<table id="dg_create" class="easyui-datagrid" style="width:1125px;height:290px; border-radius: 10px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>
</div>
<div id="dlg-buttons">
	<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="save_create_di()" style="width:100px;"><i class="fa fa-save" aria-hidden="true"></i> Save</a>
	<a href="javascript:void(0)" class="easyui-linkbutton c2" onclick="javascript:$('#dlg').dialog('close')" style="width:100px;"><i class="fa fa-ban" aria-hidden="true"></i> Cancel</a>
</div>

<div id="dlg_PO" class="easyui-dialog" style="width:850px;height:250px;" closed="true" data-options="modal:true">
	<table id="dg_po" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>
<!-- END CREATE DI -->

<table id="dg" title="DELIVERY INSTRUCTION" class="easyui-datagrid" toolbar="#toolbar" style="width:100%;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>

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

	function format_link(val,row){
	    var url = "upload/";
	    return '<a href="'+url + row.ITEM_NO+'" target="_blank">'+val+'</a>';
	}

	function access_log(){
		//ADD//UPDATE/T
		//DELETE/T
		//PRINT/T

		var add = "<?=$exp[0]?>";
		var upd = "<?=$exp[1]?>";
		var del = "<?=$exp[2]?>";
		var prn = "<?=$exp[4]?>";

		if (add == 'ADD/T'){
			$('#add').linkbutton('enable');
		}else{
			$('#add').linkbutton('disable');
		}

		if (upd == 'UPDATE/T'){
			$('#edit').linkbutton('enable');
		}else{
			$('#edit').linkbutton('disable');
		}

		if (del == 'DELETE/T'){
			$('#delete').linkbutton('enable');
		}else{
			$('#delete').linkbutton('disable');
		}

		if (prn == 'PRINT/T'){
			$('#print').linkbutton('enable');
		}else{
			$('#print').linkbutton('disable');
		}			
	}

	$(function(){
		access_log();
		$('#cmb_di_no').combobox('disable');
		$('#ck_di_no').change(function(){
			if ($(this).is(':checked')) {
				$('#cmb_di_no').combobox('disable');
			}else{
				$('#cmb_di_no').combobox('enable');
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

		$('#dg').datagrid( {
		    columns:[[
			    {field:'DI_NO',title:'D/I NO.', halign:'center', width:150, sortable: true, sortable: true},
                {field:'DI_DATE', title:'D/I DATE', halign:'center', align:'center', width:50, sortable: true}, 
                {field:'SHIPTO_CODE',hidden:true},
                {field:'COMPANY',title:'COMPANY', halign:'center', width:200, sortable: true},
                {field:'REQ', title:'REQUESTOR', halign:'center', align:'center', width:50},
                {field:'PERSON_CODE', title:'USER ENTRY', halign:'center', width:70},
                {field:'UPTO_DATE', title:'ENTRY DATE', halign:'center', width:70},
			]]
		});

		$('#dg_create').datagrid( {
		    columns:[[
                {field:'VENDOR',title:'VENDOR', hidden: true},
                {field:'ITEM_NO',title:'ITEM NO.', halign:'center', width:60, sortable: true},
                {field:'ITEM', title:'ITEM NAME', halign:'center', width:80},
                {field:'DESCRIPTION', title:'DESCRIPTION', halign:'center', width:200},
                {field:'UNIT',title:'UoM', halign:'center', width:40, align: 'center'},
                {field:'SAFETY',title:'MAX QTY', halign:'center', align:'right', width:70, sortable: true},
                {field:'INVENTORY', title:'THIS<br/>INVENTORY', halign:'center', align:'right', width:70},
                {field:'BALANCE',title:'SHORTAGE', halign:'center', align:'right', width:70, sortable: true},
                {field:'PO_NO',title:'View PO Select', halign:'center', width:80, format_link, hidden: true},
                {field:'LINE_PO_NO',hidden: true},
                {field:'ORIGIN_CODE',hidden: true},
                {field:'UOM_Q',hidden: true}
			]]/*,
			onDblClickRow:function(id,row){
				var vendor = row.VENDOR;
		    	var itm = row.ITEM_NO;
		    	var selectedrow = $("#dg_create").datagrid("getSelected");
		        var rowIndex = $("#dg_create").datagrid("getRowIndex", selectedrow);

		    	//alert(rowIndex);

		    	$('#dlg_PO').dialog('open').dialog('center').dialog('setTitle','SELECT PO');
		    	$('#dg_po').datagrid({
		    		url:'di_select_po.php?supp='+vendor+'&item='+itm,
		    		singleSelect:true,
					loadMsg:'load data ...',
					height:'auto',
					rownumbers: true,
					fitColumns: true,
					columns:[[
						{field:'PO_NO',title:'PO NO.', halign:'center', width:100},
						{field:'PO_DATE',title:'PO DATE', halign:'center', width:80},
						{field:'LINE_NO', hidden: true},
						{field:'ITEM_NO',title:'ITEM NO.', halign:'center', width:80},
						{field:'ITEM',title:'ITEM', halign:'center', width:80, hidden: true},
						{field:'DESCRIPTION',title:'DESCRIPTION', halign:'center', width:250},
						{field:'QTY',title:'QTY PO', halign:'center', align:'right', width:80},
						{field:'GR_QTY',title:'QTY<br/>DELIVERY', halign:'center', align: 'right', width:80},
						{field:'BALANCE',title:'ACTUAL<br/>QTY', halign:'center', align:'right', width:80},
					]],
					onClickRow:function(id,row2){
							$('#dg_create').datagrid('updateRow',{
								index: rowIndex,
								row: {
									PO_NO: row2.PO_NO,
									LINE_PO_NO: row2.LINE_NO
								}
							});
					
					}
				});
			}*/
		});
	});

	function filterData(){
		var ck_di_no = "false";
		var ck_item_no = "false";

		if ($('#ck_di_no').attr("checked")) {
			ck_di_no = "true";
		};

		if ($('#ck_item_no').attr("checked")) {
			ck_item_no = "true";
		};

		$('#dg').datagrid('load', {
			date_awal: $('#date_awal').datebox('getValue'),
			date_akhir: $('#date_akhir').datebox('getValue'),
			cmb_di_no: $('#cmb_di_no').combobox('getValue'),
			ck_di_no: ck_di_no,
			cmb_item_no: $('#cmb_item_no').combobox('getValue'),
			ck_item_no: ck_item_no,
			txt_item_name: $('#txt_item_name').textbox('getValue'),
			src: ''
		});

		$('#dg').datagrid( {
			url: 'di_get.php',
			view: detailview,
			detailFormatter: function(rowIndex, rowData){
				return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
			},
			onExpandRow: function(index,row){
				var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
				listbrg.datagrid({
                	title: 'Delivery Instruction Detail (No: '+row.DI_NO+')',
					url:'di_get_detail.php?di='+row.DI_NO,
					toolbar: '#ddv'+index,
					singleSelect:true,
					rownumbers:true,
					loadMsg:'load data ...',
					height:'auto',
					rownumbers: true,
					fitColumns: true,
					columns:[[
						{field:'PO_NO',title:'PO No.', halign:'center', width:80, sortable: true},
						{field:'PO_DATE',title:'PO Date.', halign:'center', align:'center', width:70, sortable: true},
		                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:80, sortable: true},
		                {field:'ITEM', title:'Material Name', halign:'center', width:100},
		                {field:'DESCRIPTION', title:'Description', halign:'center', width:240},
		                {field:'UNIT', title:'UoM', halign:'center', align:'center', width:40},
		                {field:'PO_LINE_NO', hidden:true}, 
		                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
		                {field:'DATA_DATE', title:'Delivery<br/>Date', halign:'center', align:'center', width:100},
		                {field:'ORIGIN_CODE', title:'ORIGN', halign:'center', align:'center', width:40}
					]],
					onResize:function(){
						//alert(index);
						$('#dg').datagrid('fixDetailRowHeight',index);
					},
					onLoadSuccess:function(){
						setTimeout(function(){
							$('#dg').datagrid('fixDetailRowHeight',index);
						},0);
					}
                });
			}
		})
		$('#dg').datagrid('enableFilter');
	}

	function create_di(){
		$('#dlg').dialog('open').dialog('setTitle','Create Delivery Instruction');
		$('#txt_attn').textbox('setValue','');
		$('#txt_di_no').textbox('setValue','');
		$('#txt_req').textbox('setValue','AGUSMAN SURYA');
		$('#dg_create').datagrid('loadData',[]);
		$.ajax({
			type: 'GET',
			url: 'json/json_cek_safety_stock.php',
			data: { kode:'kode' },
			success: function(data){
				var z = data[0].jum;
				if(z==0){
					$.messager.alert("WARNING","This Month not Setting Safety Stock","warning");	
				}
			}
		})
	}

	function search_item(){
		var comp = $('#cmb_company').combobox('getValue');
		$('#dg_create').datagrid('load', { vendor: $('#cmb_company').combobox('getValue')});
		$('#dg_create').datagrid({url: 'di_get_create.php'});
		$.ajax({
			type: 'GET',
			url: 'json/json_company_dtl.php?id='+comp,
			data: { kode:'kode' },
			success: function(data){
				$('#txt_attn').textbox('setValue',data[0].attn);
			}
		})
	}

	function save_create_di(){
		$.ajax({
			type: 'GET',
			url: 'json/json_cek_di_no.php?di='+ $('#txt_di_no').textbox('getValue'),
			data: { kode:'kode' },
			success: function(data){
				if((data[0].kode)=='0'){
					simpan_di();
				}else{
					$.messager.alert("WARNING","DI No. Already Exist..!!","warning");	
				}
			}
		});
	}

	function simpan_di(){
		var vndr = $('#cmb_company').combobox('getValue');
		var attn = $('#txt_attn').textbox('getValue');
		var dino = $('#txt_di_no').textbox('getValue');
		var didate = $('#di_date').datebox('getValue');

		if(vndr=='' || attn=='' || dino=='' || didate==''){
			$.messager.alert("WARNING","Required Field Can't Empty!","warning");
		}else{
			var t = $('#dg_create').datagrid('getRows');
			var total = t.length;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_create').datagrid('endEdit',i);
				$.post('di_save.php',{
					di_vendor: vndr,
					di_attn: attn,
					di_no: dino,
					di_date: didate,
					di_line: jmrow,
					di_req: $('#txt_req').textbox('getValue'),
					di_po: $('#dg_create').datagrid('getData').rows[i].PO_NO,
					di_po_line: $('#dg_create').datagrid('getData').rows[i].LINE_PO_NO,
					di_item: $('#dg_create').datagrid('getData').rows[i].ITEM_NO,
					di_org_code: $('#dg_create').datagrid('getData').rows[i].ORIGIN_CODE,
					di_qty: $('#dg_create').datagrid('getData').rows[i].BALANCE.replace(/,/g,''),
					di_uomq: $('#dg_create').datagrid('getData').rows[i].UOM_Q,
					di_unit: $('#dg_create').datagrid('getData').rows[i].UNIT
				}).done(function(res){
					//alert(res);
					//console.log(res);
					$('#dlg').dialog('close');
					$('#dg').datagrid('reload');
				})
			}
		}
	}

	function delete_di(){
		var row = $('#dg').datagrid('getSelected');
		if(row){
			$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
				if(r){
					$.post('di_destroy.php',{di_no: row.DI_NO},function(result){
						if (result.success){
                            $('#dg').datagrid('reload');
                        }else{
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        }
					},'json');
				}
			});
		}else{
			$.messager.show({title: 'Delivery Instruction',msg: 'Data Not select'});
		}
	}

	function print_di(){
		var row = $('#dg').datagrid('getSelected');
		if(row){
			window.open('di_print.php?di='+row.DI_NO);
		}else{
			$.messager.show({title: 'Delivery Instruction',msg: 'Data Not select'});
		}
	}

	function send_email(){
		var row = $('#dg').datagrid('getSelected');
		if(row){
			window.open('di_send_mail.php?di='+row.DI_NO);
		}else{
			$.messager.show({title: 'Delivery Instruction',msg: 'Data Not select'});
		}
	}
</script>
</body>
</html>