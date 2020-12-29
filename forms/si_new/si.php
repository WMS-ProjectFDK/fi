<?php 
session_start();
include("../../connect/conn.php");
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$menu_id = $_GET['id'];
if ($varConn=='Y'){
?>
<!DOCTYPE html>
    <html>
    <head>
    <meta charset="UTF-8">
    <title>SHIPPING INSTRUCTION</title>
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
	<link rel="stylesheet" type="text/css" href="../../css/style.css">
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
	<?php include ('../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name)); ?>
	
	<div id="toolbar" style="padding:3px 3px;">
		<fieldset style="float:left;width:98%;border-radius:4px;"><legend><span class="style3"><strong>SHIPPING INSTRUCTION FILTER</strong></span></legend>
			<div style="width:540px;float:left">
				<div class="fitem">
					<span style="display:inline-block;">Cargo Ready Date</span>
					<input style="width:85px;" name="date_awal" id="date_awal" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					to 
					<input style="width:85px;" name="date_akhir" id="date_akhir" class="easyui-datebox" data-options="formatter:myformatter,parser:myparser" value="<?date();?>"/> 
					<label><input type="checkbox" name="ck_date" id="ck_date" checked="true">All</input></label>
                </div>
			</div>
		</fieldset>
		<div style="clear:both;margin-bottom:3px;"></div>
		<div style="padding:5px 6px;">
			<!-- <input style="width:310px; height: 20px; border: 1px solid #0099FF;border-radius: 5px;" onkeypress="filter(event)" name="src" id="src" type="text" placeholder="search SI NO. or consignee or customer po no." autofocus="autofocus"/> -->
			<a href="javascript:void(0)" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER</a>
			<a href="javascript:void(0)" style="width: 100px;" id="add" class="easyui-linkbutton c2" onclick="addSI()"><i class="fa fa-plus" aria-hidden="true"></i> ADD SI</a>
			<a href="javascript:void(0)" style="width: 200px;" id="edit" class="easyui-linkbutton c2" onclick="copySI()"><i class="fa fa-copy" aria-hidden="true"></i> COPY DATA FOR NEW SI</a>
			<a href="javascript:void(0)" style="width: 100px;" id="add" class="easyui-linkbutton c2" onclick="editSI()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT SI</a>
			<a href="javascript:void(0)" style="width: 100px;" id="delete" class="easyui-linkbutton c2" onclick="destroySI()"><i class="fa fa-trash" aria-hidden="true"></i> DELETE SI</a>
		</div>
	</div>

	<table id="dg" title="SHIPPING INSTRUCTION" toolbar="#toolbar" class="easyui-datagrid" style="width:100%;height:490px;" data-options="fitColumns:true"></table>
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

            $('#date_awal').datebox('disable');
			$('#date_akhir').datebox('disable');
			$('#ck_date').change(function(){
				if ($(this).is(':checked')) {
					$('#date_awal').datebox('disable');
					$('#date_akhir').datebox('disable');
				}
				if (!$(this).is(':checked')) {
					$('#date_awal').datebox('enable');
					$('#date_akhir').datebox('enable');
				};
			})

            $('#dg').datagrid({
				// url:'si_get.php',
		    	singleSelect: true,
				rownumbers: true,
				fitColumns: true,
				sortName: 'po_date',
				sortOrder: 'asc',
			    columns:[[
				    {field:'SI_NO',title:'SI NO.',width:150, halign: 'center'},
					{field:'CUST_SI_NO',title:'SI NO.<br/>FROM CUST',width:150, halign: 'center'},
					{field:'CUST_PO_NO',title:'PO NO.<br/>CUSTOMER',width:200, halign: 'center'},
					{field:'PERSON_NAME',title:'PERSON IN CHARGE',width:150, halign: 'center'},
					{field:'GOODS_NAME',title:'DESCRIPTION',width:250, halign: 'center'},
					{field:'FORWARDER_NAME',title:'FORWARDER',width:150, halign: 'center'},
					{field:'CONSIGNEE_NAME',title:'CONSIGNEE',width:150, halign: 'center'},
					{field:'CR_DATE',title:'CR DATE',width:100, halign: 'center'}
			    ]]
            });
        });

        function filterData(){
            var ck_date = 'false';
			var flag = 0;

            if($('#ck_date').attr("checked")){
				ck_date='true';
				flag += 1;
			}
			
			// if(flag == 1) {
			// 	$.messager.alert('INFORMATION','No filter data, system only show 150 records','info');
			// }

			$('#dg').datagrid('load', {
				date_awal: $('#date_awal').datebox('getValue'),
				date_akhir: $('#date_akhir').datebox('getValue'),
				ck_date: ck_date//,
				// src: ''
			});

			// console.log('si_get.php?date_awal='+$('#date_awal').datebox('getValue')+'&date_akhir='+$('#date_akhir').datebox('getValue')+'&ck_date='+ck_date+'&src='+document.getElementById('src').value);

			$('#dg').datagrid({
				url:'si_get.php'
			});

			$('#dg').datagrid('enableFilter');
        }

		function addSI(){
			location.href = 'si_form.php?sts=new&si_no=new&data=new';
		}

		function copySI(){
			var dataRowsCopy = [];
			var row = $('#dg').datagrid('getSelected');
			if (row){
				dataRowsCopy.push({
					SI_NO: row.SI_NO,
					CONTRACT_NO: row.CONTRACT_NO,
					PERSON_NAME: row.PERSON_NAME,
					GOODS_NAME: row.GOODS_NAME,
					SHIPPER_NAME: row.SHIPPER_NAME,
					SHIPPER_ADDR1: row.SHIPPER_ADDR1,
					SHIPPER_ADDR2: row.SHIPPER_ADDR2,
					SHIPPER_ADDR3: row.SHIPPER_ADDR3,
					SHIPPER_TEL: row.SHIPPER_TEL,
					SHIPPER_FAX: row.SHIPPER_FAX,
					SHIPPER_ATTN: row.SHIPPER_ATTN,
					LOAD_PORT_CODE: row.LOAD_PORT_CODE,
					LOAD_PORT: row.LOAD_PORT,
					DISCH_PORT_CODE: row.DISCH_PORT_CODE,
					DISCH_PORT: row.DISCH_PORT,
					FINAL_DEST_CODE: row.FINAL_DEST_CODE,
					FINAL_DEST: row.FINAL_DEST,
					PLACE_DELI_CODE: row.PLACE_DELI_CODE,
					PLACE_DELI: row.PLACE_DELI,
					SHIPPING_TYPE: row.SHIPPING_TYPE,
					PAYMENT_TYPE: row.PAYMENT_TYPE,
					PAYMENT_REMARK: row.PAYMENT_REMARK,
					// BL_DATE: row.BL_DATE,
					FORWARDER_NAME: row.FORWARDER_NAME,
					FORWARDER_ADDR1: row.FORWARDER_ADDR1,
					FORWARDER_ADDR2: row.FORWARDER_ADDR2,
					FORWARDER_ADDR3: row.FORWARDER_ADDR3,
					FORWARDER_TEL: row.FORWARDER_TEL,
					FORWARDER_FAX: row.FORWARDER_FAX,
					FORWARDER_ATTN: row.FORWARDER_ATTN,
					SPECIAL_INST: row.SPECIAL_INST,
					CONSIGNEE_NAME: row.CONSIGNEE_NAME,
					CONSIGNEE_ADDR1: row.CONSIGNEE_ADDR1,
					CONSIGNEE_ADDR2: row.CONSIGNEE_ADDR2,
					CONSIGNEE_ADDR3: row.CONSIGNEE_ADDR3,
					CONSIGNEE_TEL: row.CONSIGNEE_TEL,
					CONSIGNEE_FAX: row.CONSIGNEE_FAX,
					CONSIGNEE_ATTN: row.CONSIGNEE_ATTN,
					NOTIFY_NAME: row.NOTIFY_NAME,
					NOTIFY_ADDR1: row.NOTIFY_ADDR1,
					NOTIFY_ADDR2: row.NOTIFY_ADDR2,
					NOTIFY_ADDR3: row.NOTIFY_ADDR3,
					NOTIFY_TEL: row.NOTIFY_TEL,
					NOTIFY_FAX: row.NOTIFY_FAX,
					NOTIFY_ATTN: row.NOTIFY_ATTN,
					NOTIFY_NAME_2: row.NOTIFY_NAME_2,
					NOTIFY_ADDR1_2: row.NOTIFY_ADDR1_2,
					NOTIFY_ADDR2_2: row.NOTIFY_ADDR2_2,
					NOTIFY_ADDR3_2: row.NOTIFY_ADDR3_2,
					NOTIFY_TEL_2: row.NOTIFY_TEL_2,
					NOTIFY_FAX_2: row.NOTIFY_FAX_2,
					NOTIFY_ATTN_2: row.NOTIFY_ATTN_2,
					EMKL_NAME: row.EMKL_NAME,
					EMKL_ADDR1: row.EMKL_ADDR1,
					EMKL_ADDR2: row.EMKL_ADDR2,
					EMKL_ADDR3: row.EMKL_ADDR3,
					EMKL_TEL: row.EMKL_TEL,
					EMKL_FAX: row.EMKL_FAX,
					EMKL_ATTN: row.EMKL_ATTN,
					SPECIAL_INFO: row.SPECIAL_INFO.replace(/\n/gi,"<br>"),
					CUST_SI_NO: row.CUST_SI_NO,
					SINO_FROM_CUST: row.CUST_PO_NO,

					SHEET_NO_DOC1: row.SHEET_NO_DOC1,
					DOC_NAME_DOC1: row.DOC_NAME_DOC1,
					DOC_DETAIL_DOC1: row.DOC_DETAIL_DOC1,

					SHEET_NO_DOC2: row.SHEET_NO_DOC2,
					DOC_NAME_DOC2: row.DOC_NAME_DOC2,
					DOC_DETAIL_DOC2: row.DOC_DETAIL_DOC2,

					SHEET_NO_DOC3: row.SHEET_NO_DOC3,
					DOC_NAME_DOC3: row.DOC_NAME_DOC3,
					DOC_DETAIL_DOC3: row.DOC_DETAIL_DOC3,

					SHEET_NO_DOC4: row.SHEET_NO_DOC4,
					DOC_NAME_DOC4: row.DOC_NAME_DOC4,
					DOC_DETAIL_DOC4: row.DOC_DETAIL_DOC4
				});
				var myJSON=JSON.stringify(dataRowsCopy);
				var str_unescape=unescape(myJSON);

				console.log('si_form.php?sts=copy&si_no='+row.SI_NO+'&data='+str_unescape);
				location.href = 'si_form.php?sts=copy&si_no='+row.SI_NO+'&data='+str_unescape;
			}
		}

		function editSI(){
			// var dataRows = [];
			var row = $('#dg').datagrid('getSelected');
			if (row){
				// dataRows.push({
				// 	SI_NO: row.SI_NO,
				// 	CONTRACT_NO: row.CONTRACT_NO,
				// 	PERSON_NAME: row.PERSON_NAME,
				// 	GOODS_NAME: row.GOODS_NAME,
				// 	SHIPPER_NAME: row.SHIPPER_NAME,
				// 	SHIPPER_ADDR1: row.SHIPPER_ADDR1,
				// 	SHIPPER_ADDR2: row.SHIPPER_ADDR2,
				// 	SHIPPER_ADDR3: row.SHIPPER_ADDR3,
				// 	SHIPPER_TEL: row.SHIPPER_TEL,
				// 	SHIPPER_FAX: row.SHIPPER_FAX,
				// 	SHIPPER_ATTN: row.SHIPPER_ATTN,
				// 	LOAD_PORT_CODE: row.LOAD_PORT_CODE,
				// 	LOAD_PORT: row.LOAD_PORT,
				// 	DISCH_PORT_CODE: row.DISCH_PORT_CODE,
				// 	DISCH_PORT: row.DISCH_PORT,
				// 	FINAL_DEST_CODE: row.FINAL_DEST_CODE,
				// 	FINAL_DEST: row.FINAL_DEST,
				// 	PLACE_DELI_CODE: row.PLACE_DELI_CODE,
				// 	PLACE_DELI: row.PLACE_DELI,
				// 	SHIPPING_TYPE: row.SHIPPING_TYPE,
				// 	PAYMENT_TYPE: row.PAYMENT_TYPE,
				// 	PAYMENT_REMARK: row.PAYMENT_REMARK,
				// 	// BL_DATE: row.BL_DATE,
				// 	FORWARDER_NAME: row.FORWARDER_NAME,
				// 	FORWARDER_ADDR1: row.FORWARDER_ADDR1,
				// 	FORWARDER_ADDR2: row.FORWARDER_ADDR2,
				// 	FORWARDER_ADDR3: row.FORWARDER_ADDR3,
				// 	FORWARDER_TEL: row.FORWARDER_TEL,
				// 	FORWARDER_FAX: row.FORWARDER_FAX,
				// 	FORWARDER_ATTN: row.FORWARDER_ATTN,
				// 	SPECIAL_INST: row.SPECIAL_INST,
				// 	CONSIGNEE_NAME: row.CONSIGNEE_NAME,
				// 	CONSIGNEE_ADDR1: row.CONSIGNEE_ADDR1,
				// 	CONSIGNEE_ADDR2: row.CONSIGNEE_ADDR2,
				// 	CONSIGNEE_ADDR3: row.CONSIGNEE_ADDR3,
				// 	CONSIGNEE_TEL: row.CONSIGNEE_TEL,
				// 	CONSIGNEE_FAX: row.CONSIGNEE_FAX,
				// 	CONSIGNEE_ATTN: row.CONSIGNEE_ATTN,
				// 	NOTIFY_NAME: row.NOTIFY_NAME,
				// 	NOTIFY_ADDR1: row.NOTIFY_ADDR1,
				// 	NOTIFY_ADDR2: row.NOTIFY_ADDR2,
				// 	NOTIFY_ADDR3: row.NOTIFY_ADDR3,
				// 	NOTIFY_TEL: row.NOTIFY_TEL,
				// 	NOTIFY_FAX: row.NOTIFY_FAX,
				// 	NOTIFY_ATTN: row.NOTIFY_ATTN,
				// 	NOTIFY_NAME_2: row.NOTIFY_NAME_2,
				// 	NOTIFY_ADDR1_2: row.NOTIFY_ADDR1_2,
				// 	NOTIFY_ADDR2_2: row.NOTIFY_ADDR2_2,
				// 	NOTIFY_ADDR3_2: row.NOTIFY_ADDR3_2,
				// 	NOTIFY_TEL_2: row.NOTIFY_TEL_2,
				// 	NOTIFY_FAX_2: row.NOTIFY_FAX_2,
				// 	NOTIFY_ATTN_2: row.NOTIFY_ATTN_2,
				// 	EMKL_NAME: row.EMKL_NAME,
				// 	EMKL_ADDR1: row.EMKL_ADDR1,
				// 	EMKL_ADDR2: row.EMKL_ADDR2,
				// 	EMKL_ADDR3: row.EMKL_ADDR3,
				// 	EMKL_TEL: row.EMKL_TEL,
				// 	EMKL_FAX: row.EMKL_FAX,
				// 	EMKL_ATTN: row.EMKL_ATTN,
				// 	SPECIAL_INFO: row.SPECIAL_INFO.replace(/\n/gi,"<br>"),
				// 	CUST_SI_NO: row.CUST_SI_NO,
				// 	SINO_FROM_CUST: row.CUST_PO_NO,

				// 	SHEET_NO_DOC1: row.SHEET_NO_DOC1,
				// 	DOC_NAME_DOC1: row.DOC_NAME_DOC1,
				// 	DOC_DETAIL_DOC1: row.DOC_DETAIL_DOC1.replace(/\r\n/gi,"<br>"),

				// 	SHEET_NO_DOC2: row.SHEET_NO_DOC2,
				// 	DOC_NAME_DOC2: row.DOC_NAME_DOC2,
				// 	DOC_DETAIL_DOC2: row.DOC_DETAIL_DOC2,//.replace(/\n/gi,"<br>"),

				// 	SHEET_NO_DOC3: row.SHEET_NO_DOC3,
				// 	DOC_NAME_DOC3: row.DOC_NAME_DOC3,
				// 	DOC_DETAIL_DOC3: row.DOC_DETAIL_DOC3,//.replace(/\n/gi,"<br>"),

				// 	SHEET_NO_DOC4: row.SHEET_NO_DOC4,
				// 	DOC_NAME_DOC4: row.DOC_NAME_DOC4,
				// 	DOC_DETAIL_DOC4: row.DOC_DETAIL_DOC4,//.replace(/\n/gi,"<br>")
				// });
				// var myJSON=JSON.stringify(dataRows);
				// var str_unescape=unescape(myJSON);

				console.log('si_form.php?sts=edit&si_no='+row.SI_NO);//+'&data='+str_unescape);
				// location.href = 'si_form.php?sts=edit&si_no='+row.SI_NO+'&data='+str_unescape;
			}
		}

		function destroySI(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to destroy this Data?',function(r){
					if (r){
						$.messager.progress({title:'Please waiting', msg:'removing data...'});
						$.post('si_destroy.php',{si_no:row.SI_NO},function(result){
							if (result.success){
								$.messager.progress('close');
								$('#dg').datagrid('reload');
							}else{
								$.messager.show({
									title: 'Error',
									msg: result.errorMsg
								});
								$.messager.progress('close');
							}
						},'json');
					}
				});
			}else{
				$.messager.show({title: 'MASTER SI',msg: 'Data not Selected'});
			}
		}
	</script>
	</body>
	</html>
<?php }else{
	echo "<script type='text/javascript'>location.href='../404.html';</script>";
} ?>