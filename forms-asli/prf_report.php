<?php 
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>PURCHASE REQUESTION</title>
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
	<link rel="stylesheet" type="text/css" href="../css/style.css">
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
	<?php include ('../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name)); ?>

	<table id="dg" title="PRF REPORT" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:515px;"></table>

	<div id="toolbar">
		<fieldset style="border-radius:4px; border-radius:4px; width:1000px; height:100px; float:left;"><legend><span class="style3"><strong>PRF FILTER</strong></span></legend>
			<div style="width:380px; float:left;">
				<div class="fitem">
					<span style="width:95px;display:inline-block;">PRF DATE</span>
					<input style="width:85px;" name="date_prf_a" id="date_prf_a" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					to 
					<input style="width:85px;" name="date_prf_z" id="date_prf_z" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<label><input type="checkbox" name="ck_prf_date" id="ck_prf_date" checked="true">ALL</input></label>
				</div>
				<div class="fitem">
					<span style="width:95px;display:inline-block;">REQUIRE DATE</span>
					<input style="width:85px;" name="date_req_a" id="date_req_a" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					to 
					<input style="width:85px;" name="date_req_z" id="date_req_z" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
					<label><input type="checkbox" name="ck_req_date" id="ck_req_date" checked="true">ALL</input></label>
				</div>
				<div class="fitem">
					<span style="width:95px;display:inline-block;">PRF No.</span>
					<select style="width:190px;" name="cmb_prf_no" id="cmb_prf_no" class="easyui-combobox" data-options=" url:'json/json_prf_no.php',method:'get',valueField:'prf_no',textField:'prf_no', panelHeight:'150px'"></select>
					<label><input type="checkbox" name="ck_prf" id="ck_prf" checked="true">ALL</input></label>
				</div>
			</div>
			<div style="position: absolute; margin-left: 390px; width:440px;">
				<div class="fitem">
					<span style="width:80px;display:inline-block;">ITEM No.</span>
					<select style="width:300px;" name="cmb_item_no" id="cmb_item_no" class="easyui-combobox" data-options=" url:'json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'100px',
					onSelect:function(rec){
						var spl = rec.id_name_item;
						var sp = spl.split(' - ');
						$('#txt_item_name').textbox('setValue', sp[1]);
					}"></select>
					<label><input type="checkbox" name="ck_item_no" id="ck_item_no" checked="true">ALL</input></label>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">ITEM NAME</span>
					<input style="width:300px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
				</div>
				<div class="fitem">
					<span style="width:80px;display:inline-block;">PERSON</span>
					<select style="width:210px;" name="cmb_person" id="cmb_person" class="easyui-combobox" data-options=" url:'json/json_person_prf.php', method:'get', valueField:'id_person', textField:'id_name_person', panelHeight:'100px'"></select>
					<label><input type="checkbox" name="ck_person" id="ck_person" checked="true">ALL</input></label>
				</div>
			</div>
			<div style="margin-left: 870px;width:150px;">
				<div class="fitem">
					<label><input type="checkbox" name="ck_view_end" id="ck_view_end">VIEW END PRF</input></label>
				</div>
				<div>
					<label><input type="checkbox" name="ck_approve" id="ck_approve">APPROVE</input></label>
				</div>
			</div>
		</fieldset>
		<fieldset style="margin-left: 1030px;border-radius:4px;height:100px;"><legend><span class="style3"><strong>ACTION</strong></span></legend>
			<div class="fitem">
				<a href="javascript:void(0)" style="width: 150px;" id="btn_pdf" class="easyui-linkbutton c2" onClick="print_pdf()">
					<i class="fa fa-file-pdf-o" aria-hidden="true"></i> DOWNLOAD PDF</a>
			</div>
			<div class="fitem">
				<a href="javascript:void(0)" style="width: 150px;" id="btn_xls" class="easyui-linkbutton c2" onClick="print_xls()">
					<i class="fa fa-file-excel-o" aria-hidden="true"></i> DOWNLOAD EXCEL</a>
			</div>
		</fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 3px;margin: 3px;">
			<a href="javascript:void(0)" id="btn_filterdata" class="easyui-linkbutton c2" onClick="filterData()" style="width:200px;">
				<i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
		</div>
	</div>

	<script type="text/javascript">
		var print_url= '';

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
			$('#date_prf_a').datebox('disable');
			$('#date_prf_z').datebox('disable');
			$('#ck_prf_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_prf_a').datebox('disable');
					$('#date_prf_z').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_prf_a').datebox('enable');
					$('#date_prf_z').datebox('enable');
				};
			})

			$('#date_req_a').datebox('disable');
			$('#date_req_z').datebox('disable');
			$('#ck_req_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_req_a').datebox('disable');
					$('#date_req_z').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_req_a').datebox('enable');
					$('#date_req_z').datebox('enable');
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

			$('#cmb_person').combobox('disable');
			$('#ck_').change(function(){
				if ($(this).is(':checked')) {
					$('#cmb_item_no').combobox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#cmb_item_no').combobox('enable');
				};
			})

			$('#btn_pdf').linkbutton('disable');
			$('#btn_xls').linkbutton('disable');

			$('#dg').datagrid({
				url:'prf_report_get.php',
		    	singleSelect: true,
			    fitColumns: true,
				rownumbers: true,
				sortName: 'PRF_DATE',
				sortOrder: 'asc',
			    columns:[[
				    {field:'PRF_NO',title:'PRF NO.',width:75, halign: 'center', align: 'center'},
				    {field:'PRF_DATE',title:'PRF DATE',width:60, halign: 'center', align: 'center', sortable:true},
				    {field:'ITEM_NO',title:'ITEM',width:170, halign: 'center'},
				    {field:'QTY',title:'QTY/<br>(REMAINDER)',width:75, halign: 'center', align: 'right'},
				    {field:'U_PRICE',title:'ESTIMATION<br/>PRICE',width:75, halign: 'center', align: 'right'},
				    {field:'REQ_DATE',title:'REQUIRED<br>DATE', width:60, halign: 'center', align: 'center', sortable: true},
				    {field:'OHSAS',title:'OHSAS(K3)', width:70, halign: 'center', align: 'center'},
				    {field:'REQUIRE_PERSON_CODE',title:'PERSON', width:60, halign: 'center', align: 'center'},
				    {field:'REMARK',title:'REMARK', width:100, halign: 'center', align: 'center'}
			    ]]
			});
		})

		function filterData(){
			var ck_prf_date = 'false';
			var ck_req_date = 'false';
			var ck_prf = 'false';
			var ck_item_no = 'false';
			var ck_person = 'false';
			var ck_view_end = 'false';
			var	ck_approve = 'false';

			var flag = 0

			if($('#ck_prf_date').attr("checked")){
				ck_prf_date='true';
				flag += 1;
			}

			if($('#ck_req_date').attr("checked")){
				ck_req_date='true';
				flag += 1;
			}

			if($('#ck_prf').attr("checked")){
				ck_prf='true';
				flag += 1;
			}

			if($('#ck_item_no').attr("checked")){
				ck_item_no='true';
				flag += 1;
			}

			if($('#ck_person').attr("checked")){
				ck_person='true';
				flag += 1;
			}

			if($('#ck_view_end').attr("checked")){
				ck_view_end='true';
			}

			if($('#ck_approve').attr("checked")){
				ck_approve='true';
			}

			if(flag == 5) {
				$.messager.alert('INFORMATION','No filter data, please choose your options from filter data','info');
			}else{
				$('#btn_pdf').linkbutton('enable');
				$('#btn_xls').linkbutton('enable');

				$('#dg').datagrid('load', {
					ck_prf_date: ck_prf_date,
					ck_req_date: ck_req_date,
					ck_prf: ck_prf,
					ck_item_no: ck_item_no,
					ck_person: ck_person,
					date_prf_a: $('#date_prf_a').datebox('getValue'),
					date_prf_z: $('#date_prf_z').datebox('getValue'),
					date_req_a: $('#date_req_a').datebox('getValue'),
					date_req_z: $('#date_req_z').datebox('getValue'),
					cmb_prf_no: $('#cmb_prf_no').combobox('getValue'),
					cmb_item_no: $('#cmb_item_no').combobox('getValue'),
					txt_item_name: $('#txt_item_name').textbox('getValue'),
					cmb_person: $('#cmb_person').combobox('getValue'),
					ck_view_end: ck_view_end,
					ck_approve: ck_approve
				});
			   	$('#dg').datagrid('enableFilter');

			   	print_url='?ck_prf_date='+ck_prf_date+
			   			  '&ck_req_date='+ck_req_date+
						  '&ck_prf='+ck_prf+
						  '&ck_item_no='+ck_item_no+
						  '&ck_person='+ck_person+
						  '&date_prf_a='+$('#date_prf_a').datebox('getValue')+
						  '&date_prf_z='+$('#date_prf_z').datebox('getValue')+
						  '&date_req_a='+$('#date_req_a').datebox('getValue')+
						  '&date_req_z='+$('#date_req_z').datebox('getValue')+
						  '&cmb_prf_no='+$('#cmb_prf_no').combobox('getValue')+
						  '&cmb_item_no='+$('#cmb_item_no').combobox('getValue')+
						  '&txt_item_name='+$('#txt_item_name').textbox('getValue')+
						  '&cmb_person='+$('#cmb_person').combobox('getValue')+
						  '&ck_view_end='+ck_view_end+
						  '&ck_approve='+ck_approve;
				console.log(print_url);
			}
		}

		function print_pdf(){
			window.open('prf_report_print_pdf.php'+print_url);	
		}

		function print_xls(){
			window.open('prf_report_print_XLS.php'+print_url);	
		}
	</script>
	</body>
    </html>