<?php
error_reporting(0);
include("../../../connect/conn.php");
session_start();
require_once('../___loginvalidation.php');
$user_name = $_SESSION['id_wms'];

$sts = $_GET['sts'];
$item_no = $_GET['item_no'];
$item_desc = $_GET['item_desc'];
$data = $_GET['data'];

if($sts == 'new'){
	$s = 'ITEM ENTRY';
	$TITLE = $s;
	$post = 'save_item.php';
}else{
	$s = 'ITEM EDIT';
	$TITLE = $s.' ('.$item_no.' - '.$item_desc.')';
	$post = 'save_item_edit.php';

	$data = isset($_REQUEST['data']) ? strval($_REQUEST['data']) : '';
	$dt = json_decode(json_encode($data));
	$str = preg_replace('/\\\\\"/',"\"", $dt);
	$queries = json_decode($str);

	foreach($queries as $query){
        // ITEM_NO
        // DESCRIPTION
        // DESCRIPTION_ORG
        // SPECIFICATION
        // MACHINE_CODE
        // ITEM_TYPE1
        // UNIT_STOCK
        // CURR_CODE
        // REPORTGROUP_CODE
        // CLASS_CODE
        // STOCK_SUBJECT_CODE
        // SAFETY_STOCK
        // SECTION_CODE
        // PURCHASE_LEADTIME

        $ITEM_NO = $query->ITEM_NO;
        $ITEM = $query->DESCRIPTION;
        $DESCRIPTION = $query->SPECIFICATION;
        $MACHINE_CODE = $query->MACHINE_CODE;
        $ITEM_TYPE1 = $query->ITEM_TYPE1;
        $UNIT_STOCK = $query->UNIT_STOCK;
        $CURR_CODE = $query->CURR_CODE;
        $REPORTGROUP_CODE = $query->REPORTGROUP_CODE;
        $CLASS_CODE = $query->CLASS_CODE;
        $STOCK_SUBJECT_CODE = $query->STOCK_SUBJECT_CODE;
        $SAFETY_STOCK = $query->SAFETY_STOCK;
        $SECTION_CODE = $query->SECTION_CODE;
        $PURCHASE_LEADTIME = $query->PURCHASE_LEADTIME ;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ITEM ENTRY</title>
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
<script type="text/javascript" src="../../../js/datagrid-filter.js"></script>
<script type="text/javascript" src="../../../js/datagrid-detailview.js"></script>
<script type="text/javascript" src="../../../js/jquery.edatagrid.js"></script>
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
		window.history.back();
	}

	function save(){
		if('<?=$sts?>' == 'new'){
			var itm = $('#ITEM_NO').textbox('getValue');
			// alert(itm);
			if (itm != ''){
				$.ajax({
					type: 'GET',
					url: 'json/json_cek_master_item_no.php?item='+ $('#ITEM_NO').textbox('getValue'),
					data: {kode:'kode'},
					success: function(data){
						if((data[0].kode)=='0'){
							$('#add').form('submit',{
								onSubmit: function(){
									return $(this).form ('validate');
								},
								success:function(data){
									$.messager.alert('Info', data, 'info');
									console.log(data);
									// location.href = 'item.php?id=1190';
									window.history.back();
								}
							});
						}else{
							$.messager.alert("WARNING","ITEM NO. Already Exist..!!","warning");	
						}
					}
				});
			}else{
				$.messager.alert("WARNING","ITEM NO. not found..!!","warning");	
			}
		}else{
			$('#add').form('submit',{
				onSubmit: function(){
					return $(this).form ('validate');
				},
				success:function(data){
					$.messager.alert('Info', data, 'info');
					console.log(data);
					window.history.back();
				}
			});
		}
	}

	$(function(){
		var sts = '<?=$sts?>';
        if(sts == 'edit'){
            $('#ITEM_NO').textbox({editable:false})
            
            $('#ITEM_NO').textbox('setValue', '<?=$ITEM_NO?>');
            $('#ITEM').textbox('setValue', '<?=$ITEM?>');
            $('#MACHINE_CODE').combobox('setValue', '<?=$MACHINE_CODE?>');
            $('#ITEM_TYPE1').combobox('setValue', '<?=$ITEM_TYPE1?>');
            $('#UNIT_STOCK').combobox('setValue', '<?=$UNIT_STOCK?>');
            $('#CURR_CODE').combobox('setValue', '<?=$CURR_CODE?>');
            $('#REPORTGROUP_CODE').combobox('setValue', '<?=$REPORTGROUP_CODE?>');
            $('#CLASS_CODE').combobox('setValue', '<?=$CLASS_CODE?>');
            $('#STOCK_SUBJECT_CODE').combobox('setValue', '<?=$STOCK_SUBJECT_CODE?>');
            $('#SAFETY_STOCK').textbox('setValue', '<?=$SAFETY_STOCK?>');
            $('#PURCHASE_LEADTIME').textbox('setValue', '<?=$PURCHASE_LEADTIME?>');
            
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
		<legend><span class="style3"><strong>ITEM NAME</strong></span></legend>
		<div class="fitem">
			<span style="width:150px;display:inline-block;color:blue;">ITEM NO.</span>
			<input style="width:200px;" name="ITEM_NO" id="ITEM_NO" class="easyui-textbox" required/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">ITEM NAME</span>
			<input style="width:900px;" name="ITEM" id="ITEM" class="easyui-textbox" required/>
		</div>
		<div class="fitem">
			<span style="width:150px;display:inline-block;">SPECIFICATION</span>
			<input style="width:900px;" name="DESCRIPTION" id="DESCRIPTION" class="easyui-textbox"/>
        </div>
	</fieldset>
	<fieldset style="border:1px solid #d0d0d0; border-radius:4px; width:97%; float:left;height: auto;padding:15px 15px;">
		<legend><span class="style3"><strong>ITEM PROPERTIES</strong></span></legend>
        <div class="fitem">
			<span style="width:150px;display:inline-block;">GROUP BARANG</span>
			<select style="width:200px;" name="MACHINE_CODE" id="MACHINE_CODE" class="easyui-combobox" data-options="panelHeight:'75px'">
                <option value="D-S"selected>D-S:DIRECT</option>
                <option value="I-C">I-C:KODEIC</option>
                <option value="I-DIR">I-DIR:DIRECTSPAREPART</option>
                <option value="I-E">I-E:KODEIE</option>
                <option value="I-G">I-G:KODEIG</option>
                <option value="I-W">I-W:KODEIW</option>
                <option value="I-Y">I-Y:KODEIY</option>
                <option value="I-Z">I-Z:KODEIZ</option>
                <option value="L-A">L-A:KODEA</option>
                <option value="L-B">L-B:KODEB</option>
                <option value="L-C">L-C:KODEC</option>
                <option value="L-D">L-D:KODED</option>
                <option value="L-DIR">L-DIR:DIRECTSPAREPART</option>
                <option value="L-E">L-E:KODEE</option>
                <option value="L-F">L-F:KODEF</option>
                <option value="L-G">L-G:KODEG</option>
                <option value="L-H">L-H:KODEH</option>
                <option value="L-I">L-I:KODEI</option>
                <option value="L-J">L-J:KODEJ</option>
                <option value="L-W">L-W:KODEW</option>
                <option value="L-X">L-X:KODEX</option>
                <option value="L-Y">L-Y:KODEY</option>
                <option value="L-Z">L-Z:KODEZ</option>
                <option value="S-C">S-C:KODESC</option>
                <option value="S-D">S-D:DIRECTCOMMON</option>
			</select>
            <span style="width:180px;display:inline-block;"></span>
            <span style="width:150px;display:inline-block;">JENIS BARANG</span>
			<select style="width:300px;" name="ITEM_TYPE1" id="ITEM_TYPE1" class="easyui-combobox" data-options="panelHeight:'auto;'">
                <option value="ALKALINE" >ALKALINE : ALKALINE</option>
                <option value="COMMON" >COMMON : SPAREPART UNTUK UMUM</option>
                <option value="DIRECT" selected>DIRECT : SPAREPART PAKAI LANGSUNG</option>
                <option value="ELECTRIC" >ELECTRIC : ELECTRIC</option>
                <option value="LITHIUM" >LITHIUM : LITHIUM</option>
                <option value="MECHANIC" >MECHANIC : MECHANIC</option>
			</select>
        </div>
        <div class="fitem">
			<span style="width:150px;display:inline-block;">SATUAN</span>
			<select style="width:200px;" name="UNIT_STOCK" id="UNIT_STOCK" class="easyui-combobox" data-options="panelHeight:'75px'">
                <option value="10">10 : BOX</option>
                <option value="20">20 : BTG</option>
                <option value="30">30 : CAN</option>
                <option value="40">40 : CM</option>
                <option value="50">50 : DRUM</option>
                <option value="60">60 : GLN</option>
                <option value="70">70 : KG</option>
                <option value="80">80 : KLG</option>
                <option value="90">90 : LBR</option>
                <option value="100">100 : LTR</option>
                <option value="110">110 : MM</option>
                <option value="120">120 : MTR</option>
                <option value="130">130 : PCS</option>
                <option value="140">140 : ROLL</option>
                <option value="150">150 : SET</option>
                <option value="160">160 : SHEET</option>
                <option value="170">170 : TBG</option>
                <option value="180">180 : UNIT</option>
                <option value="190">190 : BTL</option>
                <option value="200">200 : CASE</option>
                <option value="210">210 : DUS</option>
                <option value="220">220 : JRG</option>
                <option value="230">230 : PACK</option>
                <option value="240">240 : PAIL</option>
			</select>
            <span style="width:180px;display:inline-block;"></span>
            <span style="width:150px;display:inline-block;">REPORT GROUP</span>
            <select style="width:300px;" name="REPORTGROUP_CODE" id="REPORTGROUP_CODE" class="easyui-combobox" data-options=" url:'json/json_cost_reportgroup_code.php', method:'get', valueField:'id', textField:'name', panelHeight:'75px'"></select>
		</div>
        <div class="fitem">
			<span style="width:150px;display:inline-block;">CLASS</span>
			<select style="width:200px;" name="CLASS_CODE" id="CLASS_CODE" class="easyui-combobox" data-options="panelHeight:'75px'">
                <option value="111000">111000 : LR--</option>
                <option value="211000">211000 : CR--</option>
                <option value="311000">311000 : CMN--</option>
                <option value="411000" selected>411000 : DCT--</option>
			</select>
            <span style="width:180px;display:inline-block;"></span>
            <span style="width:150px;display:inline-block;">MATA UANG</span>
			<select style="width:300px;" name="CURR_CODE" id="CURR_CODE" class="easyui-combobox" data-options="panelHeight:'auto;'">
                <option value="1" selected>1 : USD</option>
                <option value="5">5 : SGD</option>
                <option value="7">7 : EUR</option>
                <option value="8">8 : JPY</option>
                <option value="23">23 : IDR</option>
			</select>
        </div>
        <div class="fitem">
			<span style="width:150px;display:inline-block;">ASAL KLASIFIKASI</span>
			<select style="width:200px;" name="STOCK_SUBJECT_CODE" id="STOCK_SUBJECT_CODE" class="easyui-combobox" data-options="panelHeight:'75px'">
                <option value="0">0 : Direct Sparepart</option>
                <option value="1" selected>1 : Spare Parts</option>
                <option value="2">2 : SIP</option>
			</select>
        </div>
        <div class="fitem">
			<span style="width:150px;display:inline-block;">SAFETY STOCK</span>
			<input style="width:200px;" name="SAFETY_STOCK" id="SAFETY_STOCK" class="easyui-numberbox" data-options="min:0,precision:6"/> 
			<span style="width:180px;display:inline-block;"></span>
			<span style="width:150px;display:inline-block;">PURCHASE LEADTIME</span>
			<input style="width:200px;" name="PURCHASE_LEADTIME" id="PURCHASE_LEADTIME" class="easyui-numberbox" data-options="min:0,precision:6"/> 
		</div>
</form>
</div>
</body>
</html>