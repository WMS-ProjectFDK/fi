<?php 
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>PURCHASE REQUESTION APPROVAL</title>
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

	<div id="toolbar">
		<fieldset style="border-radius:4px; border-radius:4px; width:940px; height:100px; float:left;"><legend><span class="style3"><strong>Purchase Requestion Filter</strong></span></legend>
			<div style="width:470px; float:left;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PRF Date</span>
					<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					to 
					<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PRF No.</span>
					<select style="width:300px;" name="cmb_prf_no" id="cmb_prf_no" class="easyui-combobox" data-options=" url:'../json/json_prf_no.php',method:'get',valueField:'prf_no',textField:'prf_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_prf" id="ck_prf" checked="true">All</input></label>
				</div>
			</div>
			<div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">Item No.</span>
					<select style="width:330px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
					onSelect:function(rec){
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
			</div>
		</fieldset>
		<fieldset style="margin-left: 970px;border-radius:4px;height:100px;"><legend><span class="style3"><strong>ACTION</strong></span></legend>
			<div class="fitem" align="center">
				<a href="javascript:void(0)" style="width: 150px;height: 35px;" id="checkmaterial" class="easyui-linkbutton c2" onClick="filterData()"><i class="fa fa-filter" aria-hidden="true"></i> Filter PRF</a>
			</div>
			<div class="fitem" align="center">
				<a href="javascript:void(0)" style="width: 150px;height: 35px;" id="save_approve" class="easyui-linkbutton c2" onClick="save_approve()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Approve PRF</a>
			</div>
		</fieldset>
		<div style="clear:both;"></div>
	</div>

	<table id="dg" title="PURCHASE REQUESTION APPROVAL" toolbar="#toolbar" class="easyui-datagrid" rownumbers="true" fitColumns="true" style="width:100%;height:590px;"></table>

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

		function access_log(){
			//ADD//UPDATE/T
			//DELETE/T
			//PRINT/T
			var add = "<?=$exp[0]?>";
			if (add == 'ADD/T'){
				$('#add').linkbutton('enable');
			}else{
				$('#add').linkbutton('disable');
			}
		}

		var url_prf;

		$(function(){
			access_log();
			$('#date_awal').datebox('disable');
			$('#date_akhir').datebox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_awal').datebox('disable');
					$('#date_akhi').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_awal').datebox('enable');
					$('#date_akhir').datebox('enable');
				};
			})


			$('#cmb_prf_no').combobox('disable');
			$('#ck_prf').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_prf_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_prf_no').combobox('enable');
				};
			})

			$('#cmb_item_no').combobox('disable');
			$('#ck_item_no').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_item_no').combobox('enable');
				};
			})

			$('#save_approve').linkbutton('disable');

			$('#dg').datagrid({
				view: detailview,
				singleSelect: false,
				checkOnSelect: true,
	    		selectOnCheck: true,
			    columns:[[
				    {field:'ck', checkbox:true, width:30, halign: 'center'},
				    //{field:'ck',align:'center', width:30, title:'APPROVED', halign: 'center',editor:{type:'checkbox',options:{on:'TRUE',off:'FALSE'}}},
				    {field:'PRF_NO',title:'PRF NO.',width:75, halign: 'center', align: 'center'},
				    {field:'PRF_DATE',title:'PRF DATE',width:60, halign: 'center', align: 'center', sortable:true},
				    {field:'REQUIRE_PERSON_CODE',title:'Require<br/>Person',width:60, halign: 'center', align: 'center'},
				    {field:'APPROVAL_DATE',title:'Approval<br/>Date',width:60, halign: 'center', align: 'center'},
				    {field:'REMARK',title:'REMARK', width:250, halign: 'center'},
				    {field:'CUSTOMER_PO_NO', hidden: true}
			    ]],
			    onLoadSuccess: function (data) {
					for (i=0; i<data.rows.length; i++) {
	                    $(this).datagrid('beginEdit',i);
	                }
				},
				detailFormatter: function(rowIndex, rowData){
					return '<div style="padding:2px"><table id="dregbrg'+rowIndex+'" class="listbrg"></table></div>';
				},
				onExpandRow: function(index,row){
					var listbrg = $(this).datagrid('getRowDetail',index).find('table.listbrg');
					listbrg.datagrid({
	                	title: 'PRF Detail (No: '+row.PRF_NO+')',
	                	url:'purchase_req_get_detail.php?prf='+row.PRF_NO,
						toolbar: '#ddv'+index,
						singleSelect:true,
						rownumbers:true,
						loadMsg:'load data ...',
						height:'auto',
						rownumbers: true,
						fitColumns: true,
						columns:[[
			                {field:'ITEM_NO',title:'Material No.', halign:'center', align:'center', width:60, sortable: true},
			                {field:'DESCRIPTION', title:'Material Name', halign:'center', width:200},
			                {field:'UOM_Q', hidden: true},
			                {field:'UNIT_PL', title:'UoM', halign:'center', align:'center', width:40},
			                {field:'ESTIMATE_PRICE', title:'STANDARD<br/>PRICE', halign:'center', align:'right', width:70},
			                {field:'QTY', title:'QTY', halign:'center', align:'right', width:70},
			                {field:'AMT', title:'ESTIMATION<br/>PRICE', halign:'center', align:'right', width:70},
			                {field:'REQUIRE_DATE', title:'REQUIRE<br/>DATE', halign:'center', align:'center', width:70},
			                {field:'OHSAS', title:'OHSAS(K3)<br/>Element', halign:'center', align:'center', width:70}
						]],
						onResize:function(){
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
		})

		function filterData(){
			var ck_date = 'false';
			var ck_item_no='false';
			var ck_prf='false';
			var flag = 0;

			if($('#ck_date').attr("checked")){
				ck_date='true';
				flag += 1;
			}
			if($('#ck_item_no').attr("checked")){
				ck_item_no='true';
				flag += 1;
			}
			if($('#ck_prf').attr("checked")){
				ck_prf='true';
				flag += 1;
			}

			if(flag == 3){
				$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
			}
			
			$('#dg').datagrid('load', {
				date_awal: $('#date_awal').datebox('getValue'),
				date_akhir: $('#date_akhir').datebox('getValue'),
				ck_date: ck_date,
				cmb_item_no: $('#cmb_item_no').combobox('getValue'),
				ck_item_no: ck_item_no,
				cmb_prf_no: $('#cmb_prf_no').combobox('getValue'),
				ck_prf: ck_prf
			});

			$('#dg').datagrid({
				url:'pr_approval_get.php'
			})

		   	$('#dg').datagrid('enableFilter');
		   	$('#save_approve').linkbutton('enable');
		}

		function save_approve(){
			$.messager.progress({
	            msg:'save data...'
	        });
			var ArrApprove = [];
	        var rows = $('#dg').datagrid('getSelections');

	        if(rows.length > 10){
				$.messager.alert('PRF APPROVE','Approve max=10 PRF No.','warning');	
				$.messager.progress('close');
			}else if(rows.length == 0){
				$.messager.alert('PRF APPROVE','PRF No. not selected','warning');
				$.messager.progress('close');
			}else{
				for(i=0;i<rows.length;i++){
					$('#dg').datagrid('endEdit',i);
					ArrApprove.push(rows[i].PRF_NO);
				}
			}

			console.log(ArrApprove);

			$.ajax({
			  	type: "POST",
			  	url: 'pr_approval_save.php?approve_slip='+ArrApprove,
			  	data: { kode:'kode' },
			  	success: function(data){
					if(data[0].kode == 'success'){
						$.messager.alert('INFORMATION','Data Saved','info');
						$.messager.progress('close');
						$('#dg').datagrid('reload');
					}else{
						$.messager.alert('WARNING',data[0].kode,'warning');
						console.log(data[0].kode);
						$.messager.progress('close');
					}
				}
			});
		}
	</script>
</body>
</html>