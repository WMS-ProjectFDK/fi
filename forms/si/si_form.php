<?php
session_start();
// error_reporting(0);
include("../../connect/conn.php");
require_once('../___loginvalidation.php');

$user_name = $_SESSION['id_wms'];

$sts = $_GET['sts'];
$si_no = $_GET['si_no'];
$items = array();
$rowno = 0;

$sql = "select top 150 CAST(a.CREATE_DATE as varchar(10)) as CREATE_DATE, a.ENTRY_PERSON_CODE, a.IP_ADDRESS, a.SI_NO, a.CONTRACT_NO, a.CUST_SI_NO, b.CUST_PO_NO, 
	a.PERSON_NAME, a.GOODS_NAME, a.LOAD_PORT_CODE, a.LOAD_PORT, a.DISCH_PORT_CODE, a.DISCH_PORT, 
	a.FINAL_DEST_CODE, a.FINAL_DEST, a.PLACE_DELI_CODE, a.PLACE_DELI, a.SHIPPING_TYPE, a.PAYMENT_TYPE, a.PAYMENT_REMARK, 
	REPLACE(REPLACE(REPLACE(REPLACE(a.SPECIAL_INST,'&',''),'#',''), CHAR(13), ''), CHAR(10), '<br/>') as SPECIAL_INST, 
	REPLACE(REPLACE(REPLACE(REPLACE(a.SPECIAL_INFO,'&',''),'#',''), CHAR(13), ''), CHAR(10), '<br/>') as SPECIAL_INFO,
	CAST(c.CR_DATE as varchar(10)) as CR_DATE,
	a.SHIPPER_NAME, a.SHIPPER_ADDR1, a.SHIPPER_ADDR2, a.SHIPPER_ADDR3, a.SHIPPER_TEL, a.SHIPPER_ATTN, a.SHIPPER_FAX,
	REPLACE(a.FORWARDER_NAME, '''', '') as FORWARDER_NAME, 
	REPLACE(a.FORWARDER_ADDR1, '''', '') as FORWARDER_ADDR1, 
	REPLACE(a.FORWARDER_ADDR2, '''', '') as FORWARDER_ADDR2, 
	REPLACE(a.FORWARDER_ADDR3, '''', '') as FORWARDER_ADDR3, 
	a.FORWARDER_TEL, a.FORWARDER_FAX, replace(a.FORWARDER_ATTN,'/',' ') as FORWARDER_ATTN,
	REPLACE(a.CONSIGNEE_NAME, '''', '') as CONSIGNEE_NAME, 
	REPLACE(a.CONSIGNEE_ADDR1, '''', '') as CONSIGNEE_ADDR1, 
	REPLACE(a.CONSIGNEE_ADDR2, '''', '') as CONSIGNEE_ADDR2, 
	REPLACE(a.CONSIGNEE_ADDR3, '''', '') as CONSIGNEE_ADDR3, 
	a.CONSIGNEE_TEL, a.CONSIGNEE_FAX, a.CONSIGNEE_ATTN,
	REPLACE(a.NOTIFY_NAME, '''', '') as NOTIFY_NAME,
	REPLACE(a.NOTIFY_ADDR1, '''', '') as NOTIFY_ADDR1,
	REPLACE(a.NOTIFY_ADDR2, '''', '') as NOTIFY_ADDR2,
	REPLACE(a.NOTIFY_ADDR3, '''', '') as NOTIFY_ADDR3,
	a.NOTIFY_TEL, a.NOTIFY_FAX, a.NOTIFY_ATTN,
	REPLACE(a.NOTIFY_NAME_2, '''', '') as NOTIFY_NAME_2, 
	REPLACE(a.NOTIFY_ADDR1_2, '''', '') as NOTIFY_ADDR1_2, 
	REPLACE(a.NOTIFY_ADDR2_2, '''', '') as NOTIFY_ADDR2_2, 
	REPLACE(a.NOTIFY_ADDR3_2, '''', '') as NOTIFY_ADDR3_2, 
	a.NOTIFY_TEL_2, a.NOTIFY_FAX_2, a.NOTIFY_ATTN_2,
	REPLACE(a.EMKL_NAME,'''','') as EMKL_NAME, 
	REPLACE(a.EMKL_ADDR1,'''','') as EMKL_ADDR1, 
	REPLACE(a.EMKL_ADDR2,'''','') as EMKL_ADDR2, 
	REPLACE(a.EMKL_ADDR3,'''','') as EMKL_ADDR3, 
	a.EMKL_TEL, a.EMKL_FAX, a.EMKL_ATTN,
	doc1.SHEET_NO as SHEET_NO_DOC1, doc1.DOC_NAME as DOC_NAME_DOC1, REPLACE(REPLACE(REPLACE(REPLACE(doc1.DOC_DETAIL,'&',''),'#',''), CHAR(13), ''), CHAR(10), '<br/>') as DOC_DETAIL_DOC1,
	doc2.SHEET_NO as SHEET_NO_DOC2, doc2.DOC_NAME as DOC_NAME_DOC2, REPLACE(REPLACE(REPLACE(REPLACE(doc2.DOC_DETAIL,'&',''),'#',''), CHAR(13), ''), CHAR(10), '<br/>') as DOC_DETAIL_DOC2,
	doc3.SHEET_NO as SHEET_NO_DOC3, doc3.DOC_NAME as DOC_NAME_DOC3, REPLACE(REPLACE(REPLACE(REPLACE(doc3.DOC_DETAIL,'&',''),'#',''), CHAR(13), ''), CHAR(10), '<br/>') as DOC_DETAIL_DOC3,
	doc4.SHEET_NO as SHEET_NO_DOC4, doc4.DOC_NAME as DOC_NAME_DOC4, REPLACE(REPLACE(REPLACE(REPLACE(doc4.DOC_DETAIL,'&',''),'#',''), CHAR(13), ''), CHAR(10), '<br/>') as DOC_DETAIL_DOC4
	from SI_HEADER a
	left join (SELECT si_no,
			CUST_PO_NO = STUFF(
				(SELECT ', ' + po_no FROM si_po t1
				WHERE t1.si_no = t2.si_no
				FOR XML PATH ('')
				), 1, 1, '') 
			from si_po t2
			group by si_no) b on b.SI_NO = a.SI_NO
	left join (select s.SI_NO, max(s.CR_DATE) as CR_DATE from ANSWER s
			where s.SI_NO is not null group by s.SI_NO
	) c on c.SI_NO = a.SI_NO
	left join (select SI_NO, SHEET_NO, DOC_NAME, DOC_DETAIL from SI_DOC where LINE_NO=1) doc1 on a.SI_NO = doc1.SI_NO
	left join (select SI_NO, SHEET_NO, DOC_NAME, DOC_DETAIL from SI_DOC where LINE_NO=2) doc2 on a.SI_NO = doc2.SI_NO
	left join (select SI_NO, SHEET_NO, DOC_NAME, DOC_DETAIL from SI_DOC where LINE_NO=3) doc3 on a.SI_NO = doc3.SI_NO
	left join (select SI_NO, SHEET_NO, DOC_NAME, DOC_DETAIL from SI_DOC where LINE_NO=4) doc4 on a.SI_NO = doc4.SI_NO	
	where a.si_no = '$si_no'
	order by a.operation_date desc";

if($sts == 'new'){
	$TITLE = 'SHIPPING INSTRUCTION ENTRY';
	$post = 'si_save.php';
}elseif($sts == 'edit'){
	$TITLE = 'SHIPPING INSTRUCTION EDIT ('.$si_no.')';
	$post = 'si_edit.php';	
	$data_sql_edit = sqlsrv_query($connect, strtoupper($sql));

	while ($row = sqlsrv_fetch_object($data_sql_edit)) {
		array_push($items, $row);
	}
}elseif($sts == 'copy'){
	$TITLE = 'SHIPPING INSTRUCTION NEW COPY FROM '.$si_no;
	$post = 'si_copy.php';
	$data_sql_edit = sqlsrv_query($connect, strtoupper($sql));

	while ($row = sqlsrv_fetch_object($data_sql_edit)) {
		array_push($items, $row);
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SI ENTRY</title>
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

<script type="text/javascript">
	function clearForm(){
		$('#add').form('clear');
	}

	function backForm(){
		//window.history.back();
		window.close();
	}

	function validate(){
		var hasil=0;
		var msg='';

		if($('#SPECIAL_INST').textbox('getValue').length > 999){
			msg = 'Please enter a value notify between 0 and 999';
			hasil=1;
		}else if ( $('#SPECIAL_INFO').textbox('getValue').length > 999){
			msg = 'Please enter a value notify between 0 and 999';
			hasil=1;
		}else if($('#detail_bl_doc').textbox('getValue').length > 999){
			msg = 'Please enter a value notify between 0 and 999';
			hasil=1;
		}else if($('#detail_certificate').textbox('getValue').length > 999){
			msg = 'Please enter a value notify between 0 and 999';
			hasil=1;
		}else if($('#detail_inv').textbox('getValue').length > 999){
			msg = 'Please enter a value notify between 0 and 999';
			hasil=1;
		}else if($('#detail_pack').textbox('getValue').length > 999){
			msg = 'Please enter a value notify between 0 and 999';
			hasil=1;
		}else if($('#CUST_SI_NO').textbox('getValue').length > 499){
			msg = 'Please enter a value notify between 0 and 499';
			hasil=1;
		}else if($('#SINO_FROM_CUST').textbox('getValue').length > 499){
			msg = 'Please enter a value notify between 0 and 499';
			hasil=1;
		}else if($('#GOODS_NAME').textbox('getValue').length > 199){
			msg = 'Please enter a value notify between 0 and 199';
			hasil=1;
		}

		return [hasil,msg];
	}

	function save(){
		var valid = validate();

		if('<?=$sts?>' == 'new'){
			$.ajax({
				type: 'GET',
				url: '../json/json_kode_si.php',
				data: { kode:'kode' },
				success: function(data){
					if(data[0].kode == 'UNDEFINIED'){
						$.messager.alert('INFORMATION','kode SI Error..!!','info');
					}else{
						$('#SI_NO').numberbox('setValue', data[0].kode);
						if (valid[0] != 1) {
							$('#add').form('submit',{
								onSubmit: function(){
									return $(this).form ('validate');
								},
								success:function(data){
									$.messager.confirm('Confirm','Add Data '+data+' ..!!<br/>SI No. New : '+$('#SI_NO').numberbox('getValue'),function(r){
										if (r){
											console.log(data);
											//window.history.back();
											window.close();		
										}
										console.log(data);
										//window.history.back();
										window.close();
									});
								}
							});
						}else{
							$.messager.alert('WARNING',valid[1],'warning');
						}
					}
				}
			});
		}else if ('<?=$sts?>' == 'copy'){
			$.ajax({
				type: 'GET',
				url: '../json/json_kode_si.php',
				data: { kode:'kode' },
				success: function(data){
					if(data[0].kode == 'UNDEFINIED'){
						$.messager.alert('INFORMATION','kode SI Error..!!','info');
					}else{
						$('#SI_NO').numberbox('setValue', data[0].kode);
						if (valid[0] != 1){
							$('#add').form('submit',{
								onSubmit: function(){
									return $(this).form ('validate');
								},
								success:function(data){
									$.messager.confirm('Confirm','Copy Data '+data+' ..!!<br/>SI No. New : '+$('#SI_NO').numberbox('getValue'),function(r){
										if (r){
											console.log(data);
											//window.history.back();
											window.close();		
										}
										console.log(data);
										//window.history.back();
										window.close();
									});
								}
							});
						}else{
							$.messager.alert('WARNING',valid[1],'warning');
						}
					}
				}
			});
		}else{
			if (valid[0] != 1){
				$('#add').form('submit',{
					onSubmit: function(){
						return $(this).form ('validate');
					},
					success:function(data){
						$.messager.confirm('Confirm',data,function(r){
							if (r){
								console.log(data);
								//window.history.back();
								window.close();		
							}
							console.log(data);
							//window.history.back();
							window.close();
						});
					}
				});
			}else{
				$.messager.alert('WARNING',valid[1],'warning');
			}
		}
	}

	$(function(){
		var sts = '<?=$sts?>';
		$('#LOAD_PORT_CODE').textbox('setValue','TPP');
		$('#LOAD_PORT').textbox('setValue','TANJUNG PRIOK, JAKARTA');
		if (sts == 'new'){
			$('#KET').textbox('setValue', 'CREATE');
		}else{
			if (sts == 'edit'){
				$('#KET').textbox('setValue', 'UPDATE');
				$('#SI_NO').numberbox('setValue', '<?=$items[$rowno]->SI_NO?>');
			}else{
				$('#KET').textbox('setValue', '<?=$si_no?>');
				$('#SI_NO').numberbox('setValue', '');
			}

			$('#CONTRACT_NO').textbox('setValue', '<?=$items[$rowno]->CONTRACT_NO?>');
			$('#PERSON_NAME').textbox('setValue', '<?=$items[$rowno]->PERSON_NAME?>');
			$('#GOODS_NAME').textbox('setValue', '<?=$items[$rowno]->GOODS_NAME?>');
			$('#SHIPPER_NAME').textbox('setValue', '<?=$items[$rowno]->SHIPPER_NAME?>');
			$('#SHIPPER_ADDR1').textbox('setValue', '<?=$items[$rowno]->SHIPPER_ADDR1?>');
			$('#SHIPPER_ADDR2').textbox('setValue', '<?=$items[$rowno]->SHIPPER_ADDR2?>');
			$('#SHIPPER_ADDR3').textbox('setValue', '<?=$items[$rowno]->SHIPPER_ADDR3?>');
			$('#SHIPPER_TEL').textbox('setValue', '<?=$items[$rowno]->SHIPPER_TEL?>');
			$('#SHIPPER_FAX').textbox('setValue', '<?=$items[$rowno]->SHIPPER_FAX?>');
			$('#SHIPPER_ATTN').textbox('setValue', '<?=$items[$rowno]->SHIPPER_ATTN?>');
			$('#LOAD_PORT_CODE').textbox('setValue','<?=$items[$rowno]->LOAD_PORT_CODE?>');
			$('#LOAD_PORT').textbox('setValue','<?=$items[$rowno]->LOAD_PORT?>');
			$('#DISCH_PORT_CODE').textbox('setValue','<?=$items[$rowno]->DISCH_PORT_CODE?>');
			$('#DISCH_PORT').textbox('setValue','<?=$items[$rowno]->DISCH_PORT?>');
			$('#FINAL_DEST_CODE').textbox('setValue','<?=$items[$rowno]->FINAL_DEST_CODE?>');
			$('#FINAL_DEST').textbox('setValue','<?=$items[$rowno]->FINAL_DEST?>');
			$('#PLACE_DELI_CODE').textbox('setValue','<?=$items[$rowno]->PLACE_DELI_CODE?>');
			$('#PLACE_DELI').textbox('setValue','<?=$items[$rowno]->PLACE_DELI?>');
			$('#SHIPPING_TYPE').combobox('setValue','<?=$items[$rowno]->SHIPPING_TYPE?>');
			$('#PAYMENT_TYPE').combobox('setValue','<?=$items[$rowno]->PAYMENT_TYPE?>');
			$('#PAYMENT_REMARK').textbox('setValue','<?=$items[$rowno]->PAYMENT_REMARK?>');
			$('#FORWARDER_NAME').textbox('setValue','<?=$items[$rowno]->FORWARDER_NAME?>');
			$('#FORWARDER_ADDR1').textbox('setValue','<?=$items[$rowno]->FORWARDER_ADDR1?>');
			$('#FORWARDER_ADDR2').textbox('setValue','<?=$items[$rowno]->FORWARDER_ADDR2?>');
			$('#FORWARDER_ADDR3').textbox('setValue','<?=$items[$rowno]->FORWARDER_ADDR3?>');
			$('#FORWARDER_TEL').textbox('setValue','<?=$items[$rowno]->FORWARDER_TEL?>');
			$('#FORWARDER_FAX').textbox('setValue','<?=$items[$rowno]->FORWARDER_FAX?>');
			$('#FORWARDER_ATTN').textbox('setValue','<?=$items[$rowno]->FORWARDER_ATTN?>');
			$('#SPECIAL_INST').textbox('setValue','<?=$items[$rowno]->SPECIAL_INST?>');
			$('#CONSIGNEE_NAME').textbox('setValue','<?=$items[$rowno]->CONSIGNEE_NAME?>');
			$('#CONSIGNEE_ADDR1').textbox('setValue','<?=$items[$rowno]->CONSIGNEE_ADDR1?>');
			$('#CONSIGNEE_ADDR2').textbox('setValue','<?=$items[$rowno]->CONSIGNEE_ADDR2?>');
			$('#CONSIGNEE_ADDR3').textbox('setValue','<?=$items[$rowno]->CONSIGNEE_ADDR3?>');
			$('#CONSIGNEE_TEL').textbox('setValue','<?=$items[$rowno]->CONSIGNEE_TEL?>');
			$('#CONSIGNEE_FAX').textbox('setValue','<?=$items[$rowno]->CONSIGNEE_FAX?>');
			$('#CONSIGNEE_ATTN').textbox('setValue','<?=$items[$rowno]->CONSIGNEE_ATTN?>');
			$('#NOTIFY_NAME').textbox('setValue', '<?=$items[$rowno]->NOTIFY_NAME?>');
			$('#NOTIFY_ADDR1 ').textbox('setValue', '<?=$items[$rowno]->NOTIFY_ADDR1 ?>');
			$('#NOTIFY_ADDR2').textbox('setValue', '<?=$items[$rowno]->NOTIFY_ADDR2?>');
			$('#NOTIFY_ADDR3').textbox('setValue', '<?=$items[$rowno]->NOTIFY_ADDR3?>');
			$('#NOTIFY_TEL').textbox('setValue', '<?=$items[$rowno]->NOTIFY_TEL?>');
			$('#NOTIFY_FAX').textbox('setValue', '<?=$items[$rowno]->NOTIFY_FAX?>');
			$('#NOTIFY_ATTN').textbox('setValue', '<?=$items[$rowno]->NOTIFY_ATTN?>');
			$('#NOTIFY_NAME_2').textbox('setValue', '<?=$items[$rowno]->NOTIFY_NAME_2?>');
			$('#NOTIFY_ADDR1_2').textbox('setValue', '<?=$items[$rowno]->NOTIFY_ADDR1_2?>');
			$('#NOTIFY_ADDR2_2').textbox('setValue', '<?=$items[$rowno]->NOTIFY_ADDR2_2?>');
			$('#NOTIFY_ADDR3_2').textbox('setValue', '<?=$items[$rowno]->NOTIFY_ADDR3_2?>');
			$('#NOTIFY_TEL_2').textbox('setValue', '<?=$items[$rowno]->NOTIFY_TEL_2?>');
			$('#NOTIFY_FAX_2').textbox('setValue', '<?=$items[$rowno]->NOTIFY_FAX_2?>');
			$('#NOTIFY_ATTN_2').textbox('setValue', '<?=$items[$rowno]->NOTIFY_ATTN_2?>');
			$('#EMKL_NAME').textbox('setValue', '<?=$items[$rowno]->EMKL_NAME?>');
			$('#EMKL_ADDR1').textbox('setValue', '<?=$items[$rowno]->EMKL_ADDR1?>');
			$('#EMKL_ADDR2').textbox('setValue', '<?=$items[$rowno]->EMKL_ADDR2?>');
			$('#EMKL_ADDR3').textbox('setValue', '<?=$items[$rowno]->EMKL_ADDR3?>');
			$('#EMKL_TEL').textbox('setValue', '<?=$items[$rowno]->EMKL_TEL?>');
			$('#EMKL_FAX').textbox('setValue', '<?=$items[$rowno]->EMKL_FAX?>');
			$('#EMKL_ATTN').textbox('setValue', '<?=$items[$rowno]->EMKL_ATTN?>');
			$('#SPECIAL_INFO').textbox('setValue', '<?=str_replace('<br/>','\n', str_replace('<BR/>', '<br/>', $items[$rowno]->SPECIAL_INFO))?>');
			$('#CUST_SI_NO').textbox('setValue', '<?=$items[$rowno]->CUST_SI_NO?>');
			$('#SINO_FROM_CUST').textbox('setValue', '<?=ltrim($items[$rowno]->CUST_PO_NO)?>');

			$('#cmb_bl_doc').combobox('setValue', '<?=$items[$rowno]->DOC_NAME_DOC1?>');
			$('#sheet_bl_doc').textbox('setValue', '<?=$items[$rowno]->SHEET_NO_DOC1?>');
			$('#detail_bl_doc').textbox('setValue', '<?=str_replace('<br/>','\n', str_replace('<BR/>', '<br/>', $items[$rowno]->DOC_DETAIL_DOC1))?>');
			
			$('#cmb_certificate').combobox('setValue', '<?=$items[$rowno]->DOC_NAME_DOC2?>');
			$('#sheet_certificate').textbox('setValue', '<?=$items[$rowno]->SHEET_NO_DOC2?>');
			$('#detail_certificate').textbox('setValue', '<?=str_replace('<br/>','\n', str_replace('<BR/>', '<br/>', $items[$rowno]->DOC_DETAIL_DOC2))?>');

			$('#inv_doc').textbox('setValue', '<?=$items[$rowno]->DOC_NAME_DOC3?>');
			$('#shett_inv').textbox('setValue', '<?=$items[$rowno]->SHEET_NO_DOC3?>');
			$('#detail_inv').textbox('setValue', '<?=str_replace('<br/>','\n', str_replace('<BR/>', '<br/>', $items[$rowno]->DOC_DETAIL_DOC3))?>');

			$('#pack_doc').textbox('setValue', '<?=$items[$rowno]->DOC_NAME_DOC4?>');
			$('#sheet_pack').textbox('setValue', '<?=$items[$rowno]->SHEET_NO_DOC4?>');
			$('#detail_pack').textbox('setValue', '<?=str_replace('<br/>','\n', str_replace('<BR/>', '<br/>', $items[$rowno]->DOC_DETAIL_DOC4))?>');
		}
	});
</script>
</head>
<body>
<?php include ('../../ico_logout.php'); ?>

<div style="margin:20px 0;">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="save()">SAVE</a>
	<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="clearForm()">CLEAR</a>
	<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="backForm()">BACK</a>
</div>
<div class="easyui-panel" title="<?php echo $TITLE; ?>" style="width:100%;max-width:100%;padding:10px 10px;">
	<div id="dlg_pi" class="easyui-dialog" style="width:920px;height:500px;" closed="true" buttons="#dlg-buttons-pi" data-options="modal:true">
		<table id="dg_pi" class="easyui-datagrid"></table>
	</div>

	<div id="dlg-buttons-pi">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="select _pi()" style="width:90px">SELECT</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_pi').dialog('close');" style="width:90px">Cancel</a>
	</div>

	<form id="add" method="post" action="<?=$post;?>" data-options="novalidate:true">
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
			<legend><span class="style3"><strong>SI NO.</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SI NO.</span>
				<input style="width:250px;" name="SI_NO" id="SI_NO" class="easyui-numberbox" readonly/>
				<span style="width:42px;display:inline-block;"></span>
				<input style="width:250px;" name="KET" id="KET" class="easyui-textbox" readonly/>
				<span style="width:70px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">CONTRACT NO.</span>
				<input style="width:219px;" name="CONTRACT_NO" id="CONTRACT_NO" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" disabled=""  onclick="pi_sett()">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">CUSTOMER PO NO.</span>
				<input style="width:1001px;height: 80px;" name="SINO_FROM_CUST" id="SINO_FROM_CUST" class="easyui-textbox" multiline="true" data-options="validType:'length[0,499]'"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_cust_po_no('CUSTOMER. PO NO')">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SI NO. FROM CUST</span>
				<input style="width:457px;" name="CUST_SI_NO" id="CUST_SI_NO" class="easyui-textbox" data-options="validType:'length[0,499]'"/> 
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">PERSON IN CHARGE</span>
				<input style="width:397px;" name="PERSON_NAME" id="PERSON_NAME" class="easyui-textbox"/> 
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">DESCRIPTION OF GOODS</span>
				<input style="width:1036px;" name="GOODS_NAME" id="GOODS_NAME" class="easyui-textbox" data-options="validType:'length[0,199]'"/> 
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
			<legend><span class="style3"><strong>SHIPPER</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SHIPPER</span>
				<input style="width:250px;" name="SHIPPER_NAME" id="SHIPPER_NAME" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_shipper('SHIPPER')">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">ADDRESS 1</span>
				<input style="width:250px;" name="SHIPPER_ADDR1" id="SHIPPER_ADDR1" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 2</span>
				<input style="width:250px;" name="SHIPPER_ADDR2" id="SHIPPER_ADDR2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 3</span>
				<input style="width:250px;" name="SHIPPER_ADDR3" id="SHIPPER_ADDR3" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">TEL NO.</span>
				<input style="width:250px;" name="SHIPPER_TEL" id="SHIPPER_TEL" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">FAX NO.</span>
				<input style="width:250px;" name="SHIPPER_FAX" id="SHIPPER_FAX" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ATTN</span>
				<input style="width:250px;" name="SHIPPER_ATTN" id="SHIPPER_ATTN" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;"><legend><span class="style3"><strong>PORT</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">LOAD PORT</span>
				<input style="width:70px;" name="LOAD_PORT_CODE" id="LOAD_PORT_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="LOAD_PORT" id="LOAD_PORT" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('PORT LOADING')">SET</a>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:180px;display:inline-block;">PLACE OF DELIVERY</span>
				<input style="width:70px;" name="PLACE_DELI_CODE" id="PLACE_DELI_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="PLACE_DELI" id="PLACE_DELI" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('PLACE OF DELIVERY')">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">DISCHARGE PORT</span>
				<input style="width:70px;" name="DISCH_PORT_CODE" id="DISCH_PORT_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="DISCH_PORT" id="DISCH_PORT" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('PORT DISCHARGE')">SET</a>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:180px;display:inline-block;">FINAL DESTINATION</span>
				<input style="width:70px;" name="FINAL_DEST_CODE" id="FINAL_DEST_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="FINAL_DEST" id="FINAL_DEST" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('FINAL DESTINATION')">SET</a>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
			<legend><span class="style3"><strong>FORWARDER</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">FORWARDER</span>
				<input style="width:250px;" name="FORWARDER_NAME" id="FORWARDER_NAME" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_forwarder('FORWARDER')">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">ADDRESS 1</span>
				<input style="width:250px;" name="FORWARDER_ADDR1" id="FORWARDER_ADDR1" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 2</span>
				<input style="width:250px;" name="FORWARDER_ADDR2" id="FORWARDER_ADDR2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 3</span>
				<input style="width:250px;" name="FORWARDER_ADDR3" id="FORWARDER_ADDR3" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">TEL NO.</span>
				<input style="width:250px;" name="FORWARDER_TEL" id="FORWARDER_TEL" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">FAX NO.</span>
				<input style="width:250px;" name="FORWARDER_FAX" id="FORWARDER_FAX" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ATTN</span>
				<input style="width:250px;" name="FORWARDER_ATTN" id="FORWARDER_ATTN" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
			<legend><span class="style3"><strong>CONSIGNEE</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">CONSIGNEE</span>
				<input style="width:250px;" name="CONSIGNEE_NAME" id="CONSIGNEE_NAME" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_consignee('CONSIGNEE')">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">ADDRESS 1</span>
				<input style="width:250px;" name="CONSIGNEE_ADDR1" id="CONSIGNEE_ADDR1" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 2</span>
				<input style="width:250px;" name="CONSIGNEE_ADDR2" id="CONSIGNEE_ADDR2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 3</span>
				<input style="width:250px;" name="CONSIGNEE_ADDR3" id="CONSIGNEE_ADDR3" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">TEL NO.</span>
				<input style="width:250px;" name="CONSIGNEE_TEL" id="CONSIGNEE_TEL" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">FAX NO.</span>
				<input style="width:250px;" name="CONSIGNEE_FAX" id="CONSIGNEE_FAX" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ATTN</span>
				<input style="width:250px;" name="CONSIGNEE_ATTN" id="CONSIGNEE_ATTN" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
			<legend><span class="style3"><strong>NOTIFY 1</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">NOTIFY 1</span>
				<input style="width:250px;" name="NOTIFY_NAME" id="NOTIFY_NAME" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_notify('NOTIFY')">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">ADDRESS 1</span>
				<input style="width:250px;" name="NOTIFY_ADDR1" id="NOTIFY_ADDR1" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 2</span>
				<input style="width:250px;" name="NOTIFY_ADDR2" id="NOTIFY_ADDR2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 3</span>
				<input style="width:250px;" name="NOTIFY_ADDR3" id="NOTIFY_ADDR3" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">TEL NO.</span>
				<input style="width:250px;" name="NOTIFY_TEL" id="NOTIFY_TEL" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">FAX NO.</span>
				<input style="width:250px;" name="NOTIFY_FAX" id="NOTIFY_FAX" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ATTN</span>
				<input style="width:250px;" name="NOTIFY_ATTN" id="NOTIFY_ATTN" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
			<legend><span class="style3"><strong>NOTIFY 2</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">NOTIFY 2</span>
				<input style="width:250px;" name="NOTIFY_NAME_2" id="NOTIFY_NAME_2" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_notify('NOTIFY 2')">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">ADDRESS 1</span>
				<input style="width:250px;" name="NOTIFY_ADDR1_2" id="NOTIFY_ADDR1_2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 2</span>
				<input style="width:250px;" name="NOTIFY_ADDR2_2" id="NOTIFY_ADDR2_2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 3</span>
				<input style="width:250px;" name="NOTIFY_ADDR3_2" id="NOTIFY_ADDR3_2" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">TEL NO.</span>
				<input style="width:250px;" name="NOTIFY_TEL_2" id="NOTIFY_TEL_2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">FAX NO.</span>
				<input style="width:250px;" name="NOTIFY_FAX_2" id="NOTIFY_FAX_2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ATTN</span>
				<input style="width:250px;" name="NOTIFY_ATTN_2" id="NOTIFY_ATTN_2" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
			<legend><span class="style3"><strong>EMKL</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">EMKL</span>
				<input style="width:250px;" name="EMKL_NAME" id="EMKL_NAME" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_emkl('EMKL')">SET</a>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">ADDRESS 1</span>
				<input style="width:250px;" name="EMKL_ADDR1" id="EMKL_ADDR1" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 2</span>
				<input style="width:250px;" name="EMKL_ADDR2" id="EMKL_ADDR2" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ADDRESS 3</span>
				<input style="width:250px;" name="EMKL_ADDR3" id="EMKL_ADDR3" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">TEL NO.</span>
				<input style="width:250px;" name="EMKL_TEL" id="EMKL_TEL" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">FAX NO.</span>
				<input style="width:250px;" name="EMKL_FAX" id="EMKL_FAX" class="easyui-textbox"/>
				<span style="width:15px;display:inline-block;"></span>
				<span style="width:115px;display:inline-block;">ATTN</span>
				<input style="width:250px;" name="EMKL_ATTN" id="EMKL_ATTN" class="easyui-textbox"/>
			</div>
		</fieldset>
		<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
			<legend><span class="style3"><strong>DOCUMENT</strong></span></legend>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SPECIAL INSTRUCTION</span>
				<input style="width:1035px;" name="SPECIAL_INST" id="SPECIAL_INST" class="easyui-textbox" data-options="validType:'length[0,999]'"/>
			</div>
			<div class="fitem">	
				<span style="width:180px;display:inline-block;">SHIPPING METHOD</span>
				<select style="width:120px;" name="SHIPPING_TYPE" id="SHIPPING_TYPE" class="easyui-combobox" >
					<option value=""></option>
					<option value="LCL" selected="true">LCL</option>
					<option value="FCL">FCL</option>
					<option value="BY AIR">BY AIR</option>
					<option value="LOKAL">LOKAL</option>
				</select>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">PAYMENT / FREIGHT</span>
				<select style="width:120px;" name="PAYMENT_TYPE" id="PAYMENT_TYPE" class="easyui-combobox" >
					<option value=""></option>
					<option value="Prepaid" selected="true">Prepaid</option>
					<option value="Colect">Colect</option>
					<option value="Other">Other</option>
				</select>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:140px;display:inline-block;">PAYMENT REMARK</span>
				<input style="width:440px;" name="PAYMENT_REMARK" id="PAYMENT_REMARK" class="easyui-textbox"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">B/L DOC</span>
				<input style="width:120px;" name="cmb_bl_doc" id="cmb_bl_doc" class="easyui-combobox" 
					data-options=" url:'../json/json_bl_doc.php',method:'get',valueField:'doc_name',textField:'doc_name', panelHeight:'100px'"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_bl_doc" id="sheet_bl_doc" class="easyui-numberbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:580px;" name="detail_bl_doc" id="detail_bl_doc" class="easyui-textbox" data-options="multiline:true" data-options="validType:'length[0,999]'"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">CERTIFICATE OF ORIGIN</span>
				<input style="width:120px;" name="cmb_certificate" id="cmb_certificate" class="easyui-combobox" 
					data-options=" url:'../json/json_certificate.php',method:'get',valueField:'doc_name',textField:'doc_name', panelHeight:'100px'"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_certificate" id="sheet_certificate" class="easyui-numberbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:580px;" name="detail_certificate" id="detail_certificate" class="easyui-textbox" data-options="multiline:true" data-options="validType:'length[0,999]'"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;"></span>
				<input style="width:120px;" name="inv_doc" id="inv_doc" class="easyui-textbox" disabled="" value="INVOICE"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="shett_inv" id="shett_inv" class="easyui-numberbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:580px;" name="detail_inv" id="detail_inv" class="easyui-textbox" data-options="multiline:true" data-options="validType:'length[0,999]'"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;"></span>
				<input style="width:120px;" name="pack_doc" id="pack_doc" class="easyui-textbox"  disabled=""  value="PACKING LIST"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:70px;display:inline-block;">SHEET</span>
				<input style="width:50px;" name="sheet_pack" id="sheet_pack" class="easyui-numberbox"/>
				<span style="width:20px;display:inline-block;"></span>
				<span style="width:150px;display:inline-block;">DETAIL SENDING DOC.</span>
				<input style="width:580px;" name="detail_pack" id="detail_pack" class="easyui-textbox" data-options="multiline:true" data-options="validType:'length[0,999]'"/>
			</div>
			<div class="fitem">
				<span style="width:180px;display:inline-block;">SPECIAL INFORMATION</span>
				<input style="width:1035px;height:60px;" name="SPECIAL_INFO" id="SPECIAL_INFO" class="easyui-textbox" data-options="multiline:true, validType:'length[0,999]'"/>
			</div>
		</fieldset>
	</form>

	<!-- START CUSTOMER PO NO. SETT -->
	<div id="dlg_cust" class="easyui-dialog" style="width: 700px;height: 300px;" closed="true" data-options="modal:true">
		<table id="dg_cust" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;"></table>
	</div>
	<!-- END CUSTOMER PO NO. SETT -->

	<!-- START SHIPPER SETT -->
	<div id="dlg_shipper" class="easyui-dialog" style="width: 700px;height: 300px;" closed="true" data-options="modal:true">
		<table id="dg_shipper" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;"></table>
	</div>
	<!-- END SHIPPER SETT -->

	<!-- START PORT_LOADING PO NO. SETT -->
	<div id="dlg_port_loading" class="easyui-dialog" style="width: 700px;height: 300px;" closed="true" data-options="modal:true">
		<table id="dg_port_loading" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;"></table>
	</div>
	<!-- END PORT_LOADING PO NO. SETT -->

	<!-- START FORWARDER PO NO. SETT -->
	<div id="dlg_forwarder" class="easyui-dialog" style="width: 700px;height: 300px;" closed="true" data-options="modal:true">
		<table id="dg_forwarder" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;"></table>
	</div>
	<!-- END FORWARDER PO NO. SETT -->

	<!-- START CONSIGNEE SETT -->
	<div id="dlg_consignee" class="easyui-dialog" style="width: 700px;height: 300px;" closed="true" data-options="modal:true">
		<table id="dg_consignee" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;"></table>
	</div>
	<!-- END CONSIGNEE SETT -->

	<!-- START NOTIFY SETT -->
	<div id="dlg_notify" class="easyui-dialog" style="width: 700px;height: 300px;" closed="true" data-options="modal:true">
		<table id="dg_notify" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;"></table>
	</div>
	<!-- END NOTIFY SETT -->

	<!-- START NOTIFY-2 SETT -->
	<div id="dlg_notify2" class="easyui-dialog" style="width: 700px;height: 300px;" closed="true" data-options="modal:true">
		<table id="dg_notify2" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;"></table>
	</div>
	<!-- END CONSIGNEE SETT -->

	<!-- START EMKL SETT -->
	<div id="dlg_emkl" class="easyui-dialog" style="width: 700px;height: 300px;" closed="true" data-options="modal:true">
		<table id="dg_emkl" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;"></table>
	</div>
	<!-- END EMKL SETT -->
</div>
<script type="text/javascript">
	function SET_emkl(res){
		$('#dlg_emkl').dialog('open').dialog('setTitle','SET '+res);
		$('#dg_emkl').datagrid({
			url: '_get_emkl.php',
			fitColumns: true,
			singleSelect: true,
			columns:[[
				{field:'EMKL_NO',title:'NOTIFY NO.',width:50,halign:'center', align:'center'},
				{field:'NAME',title:'NAME',width:200,halign:'center'},
				{field:'ADDR',title:'ADDRESS',width:250,halign:'center', align:'center'},
				{field:'ADDR1', hidden: true},
				{field:'ADDR2', hidden: true},
				{field:'ADDR3', hidden: true},
				{field:'TEL', hidden: true},
				{field:'FAX', hidden: true},
				{field:'ATTN', hidden: true}
			]],
			onClickRow:function(id,row){
				var rows = $('#dg_emkl').datagrid('getSelections');
				$('#EMKL_NAME').textbox('setValue',rows[0].NAME)
				$('#EMKL_ADDR1').textbox('setValue',rows[0].ADDR1);
				$('#EMKL_ADDR2').textbox('setValue',rows[0].ADDR2);
				$('#EMKL_ADDR3').textbox('setValue',rows[0].ADDR3);
				$('#EMKL_TEL').textbox('setValue',rows[0].TEL);
				$('#EMKL_FAX').textbox('setValue',rows[0].FAX);
				$('#EMKL_ATTN').textbox('setValue',rows[0].ATTN);
			}
		});
		$('#dg_emkl').datagrid('enableFilter');
	}

	function SET_notify(res){
		$('#dlg_notify').dialog('open').dialog('setTitle','SET '+res);
		$('#dg_notify').datagrid({
			url: '_get_notify.php',
			fitColumns: true,
			singleSelect: true,
			columns:[[
				{field:'NOTIFY_NO',title:'NOTIFY NO.',width:50,halign:'center', align:'center'},
				{field:'NAME',title:'NAME',width:200,halign:'center'},
				{field:'ADDR',title:'ADDRESS',width:250,halign:'center', align:'center'},
				{field:'ADDR1', hidden: true},
				{field:'ADDR2', hidden: true},
				{field:'ADDR3', hidden: true},
				{field:'TEL', hidden: true},
				{field:'FAX', hidden: true},
				{field:'ATTN', hidden: true}
			]],
			onClickRow:function(id,row){
				var rows = $('#dg_notify').datagrid('getSelections');

				if (res == 'NOTIFY'){
					$('#NOTIFY_NAME').textbox('setValue',rows[0].NAME)
					$('#NOTIFY_ADDR1').textbox('setValue',rows[0].ADDR1);
					$('#NOTIFY_ADDR2').textbox('setValue',rows[0].ADDR2);
					$('#NOTIFY_ADDR3').textbox('setValue',rows[0].ADDR3);
					$('#NOTIFY_TEL').textbox('setValue',rows[0].TEL);
					$('#NOTIFY_FAX').textbox('setValue',rows[0].FAX);
					$('#NOTIFY_ATTN').textbox('setValue',rows[0].ATTN);
				}else if (res == 'NOTIFY 2'){
					$('#NOTIFY_NAME_2').textbox('setValue',rows[0].NAME)
					$('#NOTIFY_ADDR1_2').textbox('setValue',rows[0].ADDR1);
					$('#NOTIFY_ADDR2_2').textbox('setValue',rows[0].ADDR2);
					$('#NOTIFY_ADDR3_2').textbox('setValue',rows[0].ADDR3);
					$('#NOTIFY_TEL_2').textbox('setValue',rows[0].TEL);
					$('#NOTIFY_FAX_2').textbox('setValue',rows[0].FAX);
					$('#NOTIFY_ATTN_2').textbox('setValue',rows[0].ATTN);
				}
			}
		});
		$('#dg_notify').datagrid('enableFilter');
	}

	function SET_consignee(res){
		$('#dlg_consignee').dialog('open').dialog('setTitle','SET '+res);
		$('#dg_consignee').datagrid({
			url: '_get_consignee.php',
			fitColumns: true,
			singleSelect: true,
			columns:[[
				{field:'CONSIGNEE_NO',title:'CONSIGNEE NO.',width:10,halign:'center', align:'center'},
				{field:'NAME',title:'NAME',width:200,halign:'center', align:'center'},
				{field:'ADDR',title:'ADDRESS',width:250,halign:'center', align:'center'}
			]]
		});
		$('#dg_consignee').datagrid('enableFilter');
	}	

	function SET_forwarder(res){
		$('#dlg_forwarder').dialog('open').dialog('setTitle','SET '+res);
		$('#dg_forwarder').datagrid({
			url: '_get_forwarder.php',
			fitColumns: true,
			singleSelect: true,
			columns:[[
				{field:'FORWARDER_NO',title:'SHIPPER NO.',width:45,halign:'center', align:'center'},
				{field:'NAME',title:'NAME.',width:200,halign:'center'},
				{field:'ADDR',title:'ADDRESS',width:250,halign:'center'},
				{field:'ADDR1', hidden: true},
				{field:'ADDR2', hidden: true},
				{field:'ADDR3', hidden: true},
				{field:'TEL', hidden: true},
				{field:'FAX', hidden: true},
				{field:'ATTN', hidden: true}
			]],
			onClickRow:function(id,row){
				var v_forwarder = [];
				var rows = $('#dg_forwarder').datagrid('getSelections');
				$('#FORWARDER_NAME').textbox('setValue',rows[0].NAME)
				$('#FORWARDER_ADDR1').textbox('setValue',rows[0].ADDR1);
				$('#FORWARDER_ADDR2').textbox('setValue',rows[0].ADDR2);
				$('#FORWARDER_ADDR3').textbox('setValue',rows[0].ADDR3);
				$('#FORWARDER_TEL').textbox('setValue',rows[0].TEL);
				$('#FORWARDER_FAX').textbox('setValue',rows[0].FAX);
				$('#FORWARDER_ATTN').textbox('setValue',rows[0].ATTN);
			}
		});
		$('#dg_forwarder').datagrid('enableFilter');
	}

	function SET_port_loading(res){
		$('#dlg_port_loading').dialog('open').dialog('setTitle','SET '+res);
		s_port = res;
		$('#dg_port_loading').datagrid({
			url: '_get_port_loading.php',
			fitColumns: true,
			singleSelect: true,
			columns:[[
				{field:'CODE',title:'CODE',width:45,halign:'center', align:'center'},
				{field:'NAME',title:'NAME',width:250,halign:'center'},
			]],
			onClickRow:function(id,row){
				var rows = $('#dg_port_loading').datagrid('getSelections');

				if(res == 'PORT LOADING'){
					$('#LOAD_PORT_CODE').textbox('setValue', rows[0].CODE);
					$('#LOAD_PORT').textbox('setValue', rows[0].NAME);
				}else if (res == 'PLACE OF DELIVERY'){
					$('#PLACE_DELI_CODE').textbox('setValue', rows[0].CODE);
					$('#PLACE_DELI').textbox('setValue', rows[0].NAME);
				}else if(res == 'PORT DISCHARGE'){
					$('#DISCH_PORT_CODE').textbox('setValue', rows[0].CODE);
					$('#DISCH_PORT').textbox('setValue', rows[0].NAME);
				}else if(res == 'FINAL DESTINATION'){
					$('#FINAL_DEST_CODE').textbox('setValue', rows[0].CODE);
					$('#FINAL_DEST').textbox('setValue', rows[0].NAME);
				}
			}
		});
		$('#dg_port_loading').datagrid('enableFilter');
	}

	function SET_shipper(res){
		$('#dlg_shipper').dialog('open').dialog('setTitle','SET '+res);
		$('#dg_shipper').datagrid({
			url: '_get_shipper.php',
			fitColumns: true,
			singleSelect: true,
			columns:[[
				{field:'SHIPPER_NO',title:'SHIPPER NO.',width:45,halign:'center', align:'center'},
				{field:'NAME',title:'NAME.',width:200,halign:'center'},
				{field:'ADDR',title:'ADDRESS',width:250,halign:'center'},
				{field:'ADDR1', hidden: true},
				{field:'ADDR2', hidden: true},
				{field:'ADDR3', hidden: true},
				{field:'TEL', hidden: true},
				{field:'FAX', hidden: true},
				{field:'ATTN', hidden: true}
			]],
			onClickRow:function(id,row){
				// alert(rows[0].FAX);
				var rows = $('#dg_shipper').datagrid('getSelections');
				$('#SHIPPER_NAME').textbox('setValue',"["+rows[0].NAME);
				$('#SHIPPER_ADDR1').textbox('setValue',rows[0].ADDR1);
				$('#SHIPPER_ADDR2').textbox('setValue',rows[0].ADDR2);
				$('#SHIPPER_ADDR3').textbox('setValue',rows[0].ADDR3);
				$('#SHIPPER_TEL').textbox('setValue',rows[0].TEL);
				$('#SHIPPER_FAX').textbox('setValue',rows[0].FAX);
				$('#SHIPPER_ATTN').textbox('setValue',rows[0].ATTN);
			}
		});
		$('#dg_shipper').datagrid('enableFilter');
	}

	function SET_cust_po_no(res){
		$('#dlg_cust').dialog('open').dialog('setTitle','SET '+res);
		$('#dg_cust').datagrid({
			url: '_get_cust_po_no.php',
			fitColumns: true,
			singleSelect: false,
			columns:[[
				{field:'CUSTOMER_PO_NO',title:'CUSTOMER<br/>PO NO.',width:75,halign:'center', align:'center'},
				{field:'SO_NO',title:'SO NO.',width:100,halign:'center', align:'center'},
				{field:'SO_DATE',title:'SO DATE',width:75,halign:'center', align:'center'},
				{field:'CONSIGNEE_FROM_JP',title:'CONSIGNEE<br/>FROM JP',width:100,halign:'center'},
				{field:'REMARK', title:'REMARK',width:100,halign:'center'}
			]],
			onClickRow:function(id,row){
				var  po_no_v = $('#SINO_FROM_CUST').textbox('getValue');
				var rows = $('#dg_cust').datagrid('getSelections');
				for(var i=0; i<rows.length; i++){
						$('#SINO_FROM_CUST').textbox('setValue', po_no_v+rows[i].CUSTOMER_PO_NO+", ");
				}
			}
		});
		$('#dg_cust').datagrid('enableFilter');
	}
</script>
</body>
</html>