<?php
include("../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];

$q= "select count(*) as jum from whinventory where this_month=(SELECT CONVERT(nvarchar(6), getdate(), 112))" ;
$data_q = sqlsrv_query($connect, strtoupper($q));
$dt_q = sqlsrv_fetch_object($data_q);
?>

<!DOCTYPE html>
   	<html>
   	<head>
   	<meta charset="UTF-8">
   	<title>Goods Receive Invoice</title>
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
			margin: 0px;
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
		<?php 
			include ('../../ico_logout.php');
			$exp = explode('-', access_log($menu_id,$user_name));
		?>
		
		<div id="toolbar" style="padding:3px 3px;">
			<fieldset style="float:left;width:auto;height:105px;border-radius:4px;"><legend><span class="style3"><strong>GOODS RECEIVE FILTER</strong></span></legend>
				<div style="width:550px;float:left">
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">GR DATE</span>
						<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
						to 
						<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/>
						<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
	    			</div>
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">GR NO.</span>
	    				<select style="width:190px;" name="cmb_gr_no" id="cmb_gr_no" class="easyui-combobox" data-options=" url:'../json/json_grno.php', method:'get', valueField:'gr_no', textField:'gr_no', panelHeight:'75px'"></select>
						<label><input type="checkbox" name="ck_gr_no" id="ck_gr_no" checked="true">All</input></label>
	    			</div>
	    			<div class="fitem">
	    				<span style="width:110px;display:inline-block;">SUPPLIER</span>
	    				<select style="width:350px;" name="cmb_supp" id="cmb_supp" class="easyui-combobox" data-options=" url:'../json/json_company.php', method:'get', valueField:'company_code', textField:'company', panelHeight:'100px'"></select>
						<label><input type="checkbox" name="ck_supp" id="ck_supp" checked="true">All</input></label>
	    			</div>
	    		</div>
			</fieldset>
			<fieldset style="float:left;width:550px;height:105px;border-radius:4px;"><legend><span class="style3"><strong>ITEM FILTER</strong></span></legend>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">PO NO.</span>
					<select style="width:190px;" name="cmb_po" id="cmb_po" class="easyui-combobox" data-options=" url:'../json/json_pono.php', method:'get', valueField:'po_no', textField:'po_no', panelHeight:'100px'"></select>
					<label><input type="checkbox" name="ck_po" id="ck_po" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">ITEM NO.</span>
					<select style="width:330px;" name="cmb_item" id="cmb_item" class="easyui-combobox" data-options=" url:'../json/json_item_all.php', method:'get', valueField:'id_item', textField:'id_name_item', panelHeight:'150px',
					onSelect:function(rec){
						//alert(rec.id_name_item);
						var spl = rec.id_name_item;
						var sp = spl.split(' - ');
						$('#txt_item_name').textbox('setValue', sp[1]);
					}"></select>
					<label><input type="checkbox" name="ck_item" id="ck_item" checked="true">All</input></label>
				</div>
				<div class="fitem">
					<span style="width:110px;display:inline-block;">ITEM NAME</span>
					<input style="width:330px;" name="txt_item_name" id="txt_item_name" class="easyui-textbox" disabled=""/>
				</div>
			</fieldset>
			<div style="clear:both;margin-bottom:3px;"></div>
			<div style="padding:5px 6px;">
		    	<span style="width:50px;display:inline-block;">SEARCH</span>
				<input style="width:150px; height: 20px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src"type="text" placeholder="Goods Receive No."/>
	    		<a href="javascript:void(0)" style="width: 100px;" class="easyui-linkbutton c2"  onclick="filterData();"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA</a>
				<a href="javascript:void(0)" style="width: 270px;" class="easyui-linkbutton c2" onclick="save_gr()"><i class="fa fa-save" aria-hidden="true"></i> SAVE INVOICE GOODS RECEIVE</a>
	    	</div></div>
		</div>

		<table id="dg" title="GOODS RECEIVE ADD INVOICE" class="easyui-datagrid" toolbar="#toolbar	" style="width:auto;height:490px;" rownumbers="true" fitColumns="true" singleSelect="true"></table>
		
		<script type="text/javascript">
			function upload_bc_show(){
				$('#dlg-uploadbc').dialog('open').dialog('setTitle','Upload Data BC');
				$('#fileexcel').filebox('clear');
			}

			function upload_bc_show_sp(){
				$('#dlg-uploadbcsp').dialog('open').dialog('setTitle','Upload Data BC Spareparts');
				$('#fileexcelbc').filebox('clear');
			}

			function uploaddatabc() {
				$('#uploaddatabc').form('submit',{
					url: 'bc_upload.php',
					onSubmit: function(){
						return $(this).form('validate');
					},
					success: function(result){
						$.messager.alert('UPLOAD BC DATA : ',result,'info');
				 		$('#fileexcelbc').filebox('clear');
						}
				});
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

			var pdf_url='';
			$(function(){
				access_log();
				$('#date_awal').datebox('disable');
				$('#date_akhir').datebox('disable');
				$('#ck_date').change(function(){
					if($(this).is(':checked')){
						$('#date_awal').datebox('disable');
						$('#date_akhir').datebox('disable');
					}else{
						$('#date_awal').datebox('enable');
						$('#date_akhir').datebox('enable');
					}
				});

				$('#cmb_gr_no').combobox('disable');
				$('#ck_gr_no').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_gr_no').combobox('disable');
					}else{
						$('#cmb_gr_no').combobox('enable');
					}
				});

				$('#cmb_supp').combobox('disable');
				$('#ck_supp').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_supp').combobox('disable');
					}else{
						$('#cmb_supp').combobox('enable');
					}
				});

				$('#cmb_po').combobox('disable');
				$('#ck_po').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_po').combobox('disable');
					}else{
						$('#cmb_po').combobox('enable');
					}
				});

				$('#cmb_item').combobox('disable');
				$('#ck_item').change(function(){
					if ($(this).is(':checked')) {
						$('#cmb_item').combobox('disable');
					}else{
						$('#cmb_item').combobox('enable');
					}
				});
				$('#dg').datagrid( {
					url: 'gr_get.php',
					singleSelect: true,
				    fitColumns: true,
					rownumbers: true,
				    columns:[[
						{field:'EDIT',title:'EDIT', width:80, halign:'center', hidden: true},
					    {field:'GR_NO',title:'GOODS RECEIVE<br>NO.', width:80, halign:'center'},
		                {field:'GR_DATE',title:'GOODS RECEIVE<br>DATE',width:80,halign:'center', align:'center'},
		                {field:'SUPPLIER_CODE',title:'SUPLIER<br>CODE',width:60,halign:'center', hidden: true},
		                {field:'COMPANY',title:'SUPLIER',width:180,halign:'center'},
		                {field:'CURR_CODE',title:'CURRENCY<br>CODE',width:40,halign:'center', hidden: true},
		                {field:'CURR_SHORT',title:'CURRENCY',width:50,halign:'center', align:'center'},
		                {field:'EX_RATE',title:'RATE',width:50,halign:'center', align:'center'},
		                {field:'AMT_O',title:'AMOUNT (O)',width:80,halign:'center', align:'right'},
						{field:'AMT_L',title:'AMOUNT (L)',width:80,halign:'center', align:'right'},
		                {field:'REMARK',title:'REMARK',width:60,halign:'center'},
		                {field:'PAYTERMS',title:'Payment<br>Terms',width:150,halign:'center'},
		                {field:'INV_NO',title:'INVOICE NO.',width:80,halign:'center',editor:{type:'textbox'}},
		                {field:'INV_DATE',title:'INVOICE DATE',width:80,halign:'center',editor:{type:'datebox',options:{
							required:true,
							formatter:function(date){
								var y = date.getFullYear();
								var m = date.getMonth()+1;
								var d = date.getDate();
								return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
							},
							parser:function(s){
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
							}}
						}
					]],
					onClickRow:function(rowIndex){
				    	$(this).datagrid('beginEdit', rowIndex);
				    },
					onBeforeEdit: function(index,row){
						row.editing = true;
						$(this).datagrid('updateRow',{index:index, row:{EDIT: 1}});
					},
				});
				document.getElementById('src').focus();
			})

			function filter(event){
				var src = document.getElementById('src').value;
				var search = src.toUpperCase();
				document.getElementById('src').value = search;
				
			    if(event.keyCode == 13 || event.which == 13){
					var src = document.getElementById('src').value;
					$('#dg').datagrid('load', {
						src: search
					});

					$('#dg').datagrid('enableFilter');

					if (src=='') {
						filterData();
					};
			    }
			}

			var get_url='';
			var flag = 5;
			function save_gr(){
				if(flag == 5){
					$.messager.alert('INFORMATION','Please filter data first','info');
				}else{
					$.messager.progress({
						title:'Please waiting',
						msg:'Save data...'
				    });
					var dataRows = [];
					var t = $('#dg').datagrid('getRows');
					var total = t.length;
					var jmrow=0;
					for(i=0;i<total;i++){
						jmrow = i+1;
						$('#dg').datagrid('endEdit',i);
						if($('#dg').datagrid('getData').rows[i].EDIT == 1){
							dataRows.push({
							gr_no: $('#dg').datagrid('getData').rows[i].GR_NO,
							inv_no: $('#dg').datagrid('getData').rows[i].INV_NO,
							inv_date: $('#dg').datagrid('getData').rows[i].INV_DATE
							});
						}
					}

					var myJSON=JSON.stringify(dataRows);
					var str_unescape=unescape(myJSON);
					
					console.log(unescape(str_unescape));

					$.post('gr_save_invoice.php',{
						data: unescape(str_unescape)
					}).done(function(res){
						if(res.length == 8){
							$('#dg').datagrid('loadData',[]);
							$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
							$.messager.progress('close');
						}else{
							$.messager.alert('ERROR',res,'warning');
							$.messager.progress('close');
						}
					});
				}

			}

			function filterData(){
				var ck_date = "false";
				var ck_gr_no = "false";
				var ck_supp = "false";
				var ck_po = "false";
				var ck_item = "false";
				flag = 0;

				if ($('#ck_date').attr("checked")) {
					ck_date = "true";
					flag += 1;
				}

				if ($('#ck_gr_no').attr("checked")) {
					ck_gr_no = "true";
					flag += 1;
				};

				if ($('#ck_supp').attr("checked")) {
					ck_supp = "true";
					flag += 1;
				};

				if ($('#ck_po').attr("checked")) {
					ck_po = "true";
					flag += 1;
				};

				if ($('#ck_item').attr("checked")) {
					ck_item = "true";
					flag += 1;
				};

				if(flag == 5) {
					$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
				}else{
					$('#dg').datagrid('load', {
					date_awal: $('#date_awal').datebox('getValue'),
					date_akhir: $('#date_akhir').datebox('getValue'),
					ck_date: ck_date,
					cmb_gr_no: $('#cmb_gr_no').combobox('getValue'),
					ck_gr_no: ck_gr_no,
					cmb_supp: $('#cmb_supp').combobox('getValue'),
					ck_supp: ck_supp,
					cmb_po: $('#cmb_po').combobox('getValue'),
					ck_po: ck_po,
					cmb_item: $('#cmb_item').combobox('getValue'),
					ck_item: ck_item,
					src: ''
				});

				get_url = "?date_awal="+$('#date_awal').datebox('getValue')+
						  "&date_akhir="+$('#date_akhir').datebox('getValue')+
						  "&ck_date="+ck_date+
						  "&cmb_gr_no="+$('#cmb_gr_no').combobox('getValue')+
						  "&ck_gr_no="+ck_gr_no+
						  "&cmb_supp="+$('#cmb_supp').combobox('getValue')+
						  "&nm_supp="+$('#cmb_supp').combobox('getText')+
						  "&ck_supp="+ck_supp+
						  "&cmb_po="+$('#cmb_po').combobox('getValue')+
						  "&ck_po="+ck_po+
						  "&cmb_item="+$('#cmb_item').combobox('getValue')+
						  "&ck_item="+ck_item;
				}
			}
		</script>
	</body>
    </html>