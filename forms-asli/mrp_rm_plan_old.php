<?php
require("../connect/conn.php");
session_start();
require_once('___loginvalidation.php');
$user_name = $_SESSION['id_wms'];
$cmb_type = isset($_REQUEST['cmb_type']) ? strval($_REQUEST['cmb_type']) : '';
$cmb_item_no = isset($_REQUEST['cmb_item_no']) ? strval($_REQUEST['cmb_item_no']) : '';
$item_name = isset($_REQUEST['item_name']) ? strval($_REQUEST['item_name']) : '';
$from = isset($_REQUEST['from']) ? strval($_REQUEST['from']) : '';

if($from == 'MRP'){
	if ($cmb_item_no == ''){
		$sql = "select distinct a.uom_q, b.unit_pl from item a
			inner join unit b on a.uom_q = b.unit_code
			where a.item = '".ltrim($cmb_type)."'";
	}else{
		$sql = "select a.uom_q, b.unit_pl from item a
			inner join unit b on a.uom_q = b.unit_code
			where a.item_no=".$cmb_item_no."";
	}
}else{
	if ($cmb_item_no == ''){
		$sql = "select * from (
			select distinct b.uom_q, c.unit_pl, a.item_no from ztb_mrp_data a
			inner join item b on a.item_no=b.item_no
			inner join unit c on b.uom_q = c.unit_code
			where a.item_type='".ltrim($cmb_type)."')aa
			left outer join
			(select distinct max(line_no), item_no,purchase_leadtime from itemmaker group by item_no,purchase_leadtime)bb on aa.item_no=bb.item_no";
	}else{
		$sql = "select * from
			(select a.uom_q, b.unit_pl, a.item_no from item a
			inner join unit b on a.uom_q = b.unit_code
			where a.item_no=".$cmb_item_no.")aa
			left outer join
			(select distinct max(line_no), item_no,purchase_leadtime from itemmaker group by item_no,purchase_leadtime)bb on aa.item_no=bb.item_no";
	}
}

$data = oci_parse($connect, $sql);
oci_execute($data);
$row = oci_fetch_object($data);

if($from == 'ITO'){
	$ld_time = ', Lead Time = '.$row->PURCHASE_LEADTIME.' Days';
}else{
	$ld_time = '';
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>RAW MATERIAL ANALISYST</title>
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
<script type="text/javascript" src="../js/jquery.easyui.patch.js"></script>
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
<?php include ('../ico_logout.php'); ?>

<table id="dg_plan" title="VIEW PLAN <?php echo  $cmb_type.' ('.$item_name.')'; ?>" class="easyui-datagrid" style="width:100%;height:auto;"></table>

<div style="margin-top: 5px; width: 700px;">
	<div style="float: left; width:100px;height: 17px;border-radius:4px; background-color: green; color: white; text-align: center;"><b>ACHIEVED</b></div>
	<div style="position:absolute; margin-left: 110px;width:100px;height: 17px;border-radius:4px; background-color: red; color: white; text-align: center;"><b>SHORTAGE</b></div>
	<div style="position:absolute; margin-left: 220px;width:100px;height: 17px;border-radius:4px; background-color: blue; color: white; text-align: center;"><b>OVER</b></div>
	<div style="margin-left: 330px;width:500px;height: 17px;"><b>Unit of Measure : <?php echo $row->UNIT_PL; ?> <?php echo $ld_time; ?></b></div>
</div>

<div align="center" style="margin-top: 50px;">
	<a href="javascript:void(0)" title="Back to <?php echo $from; ?>" class="easyui-linkbutton c2" iconCls="icon-back" onclick="javascript:window.close();" style="width:100px;height: 30px;">BACK</a>

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
<div id='dlg_viewPLAN' class="easyui-dialog" style="width:1050px;height:300px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-view_po" data-options="modal:true">
	<table id="dg_viewPLAN" class="easyui-datagrid" style="width:100%;height:100%;"></table>
</div>
<!-- END VIEW INFO PLAN ASSEMBLING-->

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

	$(function(){
		//document.getElementById(N_3).title="New tooltip";
		/*$('#N_3').linkbutton({
			title: 'aku adalah'
		});*/
	})

	function addPRF(t,i){
		var date_plan = AddDate(t);
		n_plan = t;
		i_plan = i;
		$.messager.confirm('Confirm','Are you sure you want to Create Purchase Requestion?',function(r){
			if(r){
				$.ajax({
					type: 'GET',
					url: 'json/json_cek_leadtime.php?day='+t+'&item_no='+i,
					data: { kode:'kode' },
					success: function(data){
						$.messager.confirm('INFORMATION', data[0].lead, function(x){
							if(x){
								$('#dlg_add').dialog('open').dialog('setTitle','Create Purchase Requestion');
								$('#prf_no_add').textbox('setValue','');
								$('#remark_add').textbox('setValue','');
								$('#dg_add').datagrid('loadData',[]);
								//$('#prf_date_add').datebox('setValue',date_plan);
								
								$('#dg_add').datagrid({
									url: 'mrp_rm_plan_getItem.php?item_no='+i+'&date='+date_plan+'&no=4',
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

		if($('#ck_new_add').attr("checked")){
			ck_dsign='true';
		}

		var t = $('#dg_add').datagrid('getRows');
		var total = t.length;
		var jmrow=0;
		for(i=0;i<total;i++){
			jmrow = i+1;
			$('#dg_add').datagrid('endEdit',i);
			$.post('purchase_req_save.php',{
				pu_sts: 'MRP',
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
			}).done(function(res){
				//alert(res);
				//console.log(res);
			})
			$.post('mrp_plan_update.php',{
				pu_item: $('#dg_add').datagrid('getData').rows[i].ITEM_NO
			}).done(function(res){
				$.messager.confirm('Confirm','Are you sure you want to print PRF?',function(r){
					if(r){
						window.open('purchase_req_print.php?prf='+$('#prf_no_add').textbox('getValue'));
					}
				});
				//alert(res);
				console.log(res);
				$('#dlg_add').dialog('close');
				$('#dg_plan').datagrid('reload');
			})
		}
		$.messager.alert('INFORMATION','Insert Data Success..!!<br/>PRF No. : '+$('#prf_no_add').textbox('getValue'),'info');
	}

	function savePRF(){
		var url='';
		$.ajax({
			type: 'GET',
			url: 'json/json_kode_prf.php',
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
			url: 'mrp_rm_plan_info_ost.php?item_no='+p,
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

	function info_assy (f,g,h){
		var tgl_plan = AddDate(f);
		if (h == 0 || h == null){
			$.messager.show({
                title: 'INFORMATION',
                msg: 'Value is null'
            });
		}else{
			$('#dlg_viewPLAN').dialog('open').dialog('setTitle','VIEW INFO Assembly Plan ('+AddDateII(f)+')');	
			//alert('mrp_rm_plan_info_ASSY.php?item_no='+g+'&tgl_plan='+tgl_plan);
			$('#dg_viewPLAN').datagrid({
				url: 'mrp_rm_plan_info_ASSY.php?item_no='+g+'&tgl_plan='+tgl_plan,
				singleSelect: true,
				rownumbers: true,
			    columns:[[
				    {field:'ID_PLAN', title:'ID<br/>PLAN', width: 120, halign: 'center'},
				    {field:'ASSY_LINE', title:'ASSY_LINE', width:90, halign: 'center', align: 'center'},
				    {field:'CELL_TYPE', title:'CELL TYPE', width: 90, halign: 'center', align: 'center'},
				    {field:'QTY', title:'QTY PLAN', width: 80, halign: 'center', align: 'right'},
				    {field:'ITEM_NO', title:'ITEM NO.', width: 80, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION', title:'DESCRIPTION', width: 300, halign: 'center'},
				    {field:'KONVERSI', title:'CONVERTION<br/> / 1000', width: 100, halign: 'center', align: 'right'},
				    {field:'USAGE', title:'USAGE', width: 120, halign: 'center', align: 'right'}
				]]
			});
		}
	}

	$('#dg_plan').datagrid({
		url:'mrp_rm_get_plan.php?cmb_type=<?php echo $cmb_type;?>&cmb_item_no=<?php echo $cmb_item_no;?>&from=<?php echo $from;?>',
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