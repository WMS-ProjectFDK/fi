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
<title>COMPANY</title>
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
    <!-- ADD -->
	<div id='dlg_add' class="easyui-dialog" style="width:1150px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	  <form id="ff" method="post" novalidate>	
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:190px; float:left;"><legend><span class="style3"><strong>COMPANY NAME</strong></span></legend>
            <div class="fitem">
            <span style="width:140px;display:inline-block;">COMPANY CODE</span>
                <input  style="width:200px;" name="company_no_add" id="company_no_add" class="easyui-numberbox" require="true"/>
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">COMPANY TYPE</span>
                <select  style="width:252px;" name="cmb_company_type_add" id="cmb_company_type_add" class="easyui-combobox"  required="true" >
                    <option value="" selected></option>
                    <option value="1">CUSTOMER</option>
                    <option value="2" >CUSTOMER / VENDOR</option>
                    <option value="3" >VENDOR</option>
                    <option value="4" >SUB CONTRACTOR</option>
                    <option value="5" >SHIP TO</option>
                    <option value="7" >PLANT</option>
                </select>
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">COMPANY NAME</span>
                <input style="width:600px;" name="company_name_add" id="company_name_add" class="easyui-textbox" required="true"/>
                <span style="width:140px;display:inline-block;">COMPANY INITIAL</span>
                 <input  style="width:146px;" name="company_short_name_add" id="company_short_name_add" class="easyui-textbox" />
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">COMPANY ADDRESS</span>
                <input style="width:895px;" name="company_address1_add" id="company_address1_add" class="easyui-textbox" required="true"/>
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input  style="width:895px;" name="company_address2_add" id="company_address2_add" class="easyui-textbox" />
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input style="width:895px;" name="company_address3_add" id="company_address3_add" class="easyui-textbox" />
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input  style="width:895px;" name="company_address4_add" id="company_address4_add" class="easyui-textbox" />
            </div>
        </fieldset>
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:150px; float:left;"><legend><span class="style3"><strong>COMPANY DETAILS</strong></span></legend>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">ATTN</span>
                <input style="width:200px;" name="attn_add" id="attn_add" class="easyui-textbox" />
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">TEL NO.</span>
                <input  style="width:200px;" name="telno_add" id="telno_add" class="easyui-textbox" />
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">FAX NO.</span>
                <input style="width:198px;" name="faxno_add" id="faxno_add" class="easyui-textbox" />
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">ZIP CODE</span>
                <input  style="width:200px;" name="zip_add" id="zip_add" class="easyui-textbox" />
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">EMAIL</span>
                <input  style="width:200px;" name="email_add" id="email_add" class="easyui-textbox" />
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">COUNTRY</span>
                <select  style="width:200px;" name="cmb_country_add" id="cmb_country_add" class="easyui-combobox"   required="true">
                    <option value="" selected></option>
                    <option VALUE="105">CHINA </option>
                    <option VALUE="108">HONG KONG </option>
                    <option VALUE="123">INDIA </option>
                    <option VALUE="118">INDONESIA</option>
                    <option VALUE="143">ISRAEL </option>
                    <option VALUE="192">JAPAN </option>
                    <option VALUE="103">KOREA </option>
                    <option VALUE="113">MALAYSIA </option>
                    <option VALUE="131">NEPAL </option>
                    <option VALUE="117">PHILLIPINES </option>
                    <option VALUE="112">SINGAPORE </option>
                    <option VALUE="125">SRI LANKA </option>
                    <option VALUE="106">TAIWAN </option>
                    <option VALUE="111">THAILAND </option>
                    <option VALUE="144">JORDAN </option>
                    <option VALUE="141">OMAN</option>
                    <option VALUE="147">UNITED ARAB EMIRATES </option>
                    <option VALUE="208">BELGIUM </option>
                    <option VALUE="210">FRANCE </option>
                    <option VALUE="213">GERMANY </option>
                    <option VALUE="300">GREECE </option>
                    <option VALUE="237">LITHUANIA </option>
                    <option VALUE="207">NETHERLANDS </option>
                    <option VALUE="223">POLAND </option>
                    <option VALUE="217">PORTUGAL </option>
                    <option VALUE="218">SPAIN </option>
                    <option VALUE="215">SWITZERLAND </option>
                    <option VALUE="205">UNITED KINGDOM/UK </option>
                    <option VALUE="308">CANADA </option>
                    <option VALUE="304">USA </option>
                    <option VALUE="305">MEXICO </option>
                    <option VALUE="410">BRAZIL </option>
                    <option VALUE="409">CHILE </option>
                    <option VALUE="407">PERU </option>
                    <option VALUE="601">AUSTRALIA </option>
                    <option VALUE="606">NEW ZEALAND </option>
                    <option VALUE="506">EGYPT</option>
                </select>
                <span style="width:35px;display:inline-block;"></span>
            <span style="width:100px;display:inline-block;">SUPPLY TYPE</span>
                <select   style="width:200px;" name="cmb_supply_type_add" id="cmb_supply_type_add" class="easyui-combobox"  required="true">
                    <option value="Y" selected>CHARGE</option>
                    <option value="N" >FREE OF CHARGE</option>
                </select>
            </div>

            <div class="fitem">
                <span style="width:140px;display:inline-block;">BONDED TYPE</span>
                <select style="width:200px;" name="cmb_bonded_add" id="cmb_bonded_add" class="easyui-combobox"  required="true">
                    <option value=A selected>OVERSEA BONDED </option>
                    <option value=B >LOCAL BONDED </option>
                    <option value=C >LOCAL NON BONDED</option>
                </select>
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">DAYS TRANS.</span>
                <input style="width:200px;" name="days_trans_add" id="days_trans_add" class="easyui-numberbox" required="true"/>
                <span style="width:100px;display:inline-block;">DAYS</span>
            </div>
        </fieldset>
              
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:auto; float:left;"><legend><span class="style3"><strong>TRADE TERMS</strong></span></legend>
           <div class="fitem">
                 <span style="width:140px;display:inline-block;">CURRENCY</span>
                 <select   style="width:150px;" name="cmb_currency_add" id="cmb_currency_add" class="easyui-combobox"  required="true">
                    <option VALUE=1 selected>US$ </option>
                    <option VALUE=5>SG$</option>
                    <option VALUE=7>EURO</option>
                    <option VALUE=8>YEN</option>
                    <option VALUE=23>RP</SELECT>
                </select>
                <span style="width:85px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">TRADE TERMS</span>
                <input  style="width:300px;" name="tterms_add" id="ttrems_add" class="easyui-textbox"/>
            </div>   
            <div class="fitem">
                <span style="width:140px;display:inline-block;">PAYMENT DAYS</span>
                <input  style="width:150px;" name="pday_add" id="pday_add" class="easyui-numberbox" required="true"/>
                <span style="width:85px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">PAY TERMS</span>
                <input  style="width:300px;" name="pdesc_add" id="pdesc_add" class="easyui-textbox" />
            </div>
            <div class="fitem">
				<span style="width:140px;display:inline-block;">LOAD PORT</span>
				<input style="width:70px;" name="LOAD_PORT_CODE" id="LOAD_PORT_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="LOAD_PORT" id="LOAD_PORT" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('PORT LOADING')">SET</a>
            </div>
            <div class="fitem">
				<span style="width:140px;display:inline-block;">DISCHARGE PORT</span>
				<input style="width:70px;" name="DISCH_PORT_CODE" id="DISCH_PORT_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="DISCH_PORT" id="DISCH_PORT" class="easyui-textbox"/>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('PORT DISCHARGE')">SET</a>
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">FINAL DESTINATION</span>
				<input style="width:70px;" name="FINAL_DEST_CODE" id="FINAL_DEST_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="FINAL_DEST" id="FINAL_DEST" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('FINAL DESTINATION')">SET</a>
			</div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">SHIPPING MARK</span>
                <input  style="width:896px; height:70px;" name="ship_mark_add" id="ship_mark_add" class="easyui-textbox" />
            </div>
        </fieldset>
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:auto; float:left;"><legend><span class="style3"><strong>TAX</strong></span></legend>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">TAXPAYER NO.</span>
                <input  style="width:150px;" name="taxpayer_add" id="taxpayer_add" class="easyui-textbox" />
                <span style="width:140px;display:inline-block;">ACCPAC COMP. CODE</span>
                <input  style="width:300px;" name="accpac_add" id="accpac_add" class="easyui-textbox" required="true"/>
            </div>
        </fieldset> 
       
        <div id="dlg-buttons-add">
            <a href="javascript:void(0)" id="save_add" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveAdd()" style="width:90px">Save</a>
            <a href="javascript:void(0)" id="cancel_add" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
        </div>
      </form>
    </div>
	<!-- END ADD -->

     <!-- EDIT -->
    <div id='dlg_edit' class="easyui-dialog" style="width:1150px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
      <form id="ff1" method="post" novalidate>
      <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:190px; float:left;"><legend><span class="style3"><strong>COMPANY NAME</strong></span></legend>
            <div class="fitem">
            <span style="width:140px;display:inline-block;">COMPANY CODE</span>
                <input  style="width:200px;" name="company_no_add" id="company_no_add" class="easyui-numberbox" require="true"/>
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">COMPANY TYPE</span>
                <select  style="width:252px;" name="cmb_company_type_add" id="cmb_company_type_add" class="easyui-combobox"  required="true" >
                    <option value="" selected></option>
                    <option value="1">CUSTOMER</option>
                    <option value="2" >CUSTOMER / VENDOR</option>
                    <option value="3" >VENDOR</option>
                    <option value="4" >SUB CONTRACTOR</option>
                    <option value="5" >SHIP TO</option>
                    <option value="7" >PLANT</option>
                </select>
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">COMPANY NAME</span>
                <input style="width:600px;" name="company_name_add" id="company_name_add" class="easyui-textbox" required="true"/>
                <span style="width:140px;display:inline-block;">COMPANY INITIAL</span>
                 <input  style="width:146px;" name="company_short_name_add" id="company_short_name_add" class="easyui-textbox" />
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">COMPANY ADDRESS</span>
                <input style="width:895px;" name="company_address1_add" id="company_address1_add" class="easyui-textbox" required="true"/>
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input  style="width:895px;" name="company_address2_add" id="company_address2_add" class="easyui-textbox" />
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input style="width:895px;" name="company_address3_add" id="company_address3_add" class="easyui-textbox" />
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input  style="width:895px;" name="company_address4_add" id="company_address4_add" class="easyui-textbox" />
            </div>
        </fieldset>
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:150px; float:left;"><legend><span class="style3"><strong>COMPANY DETAILS</strong></span></legend>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">ATTN</span>
                <input style="width:200px;" name="attn_add" id="attn_add" class="easyui-textbox" />
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">TEL NO.</span>
                <input  style="width:200px;" name="telno_add" id="telno_add" class="easyui-textbox" />
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">FAX NO.</span>
                <input style="width:198px;" name="faxno_add" id="faxno_add" class="easyui-textbox" />
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">ZIP CODE</span>
                <input  style="width:200px;" name="zip_add" id="zip_add" class="easyui-textbox" />
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">EMAIL</span>
                <input  style="width:200px;" name="email_add" id="email_add" class="easyui-textbox" />
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">COUNTRY</span>
                <select  style="width:200px;" name="cmb_country_add" id="cmb_country_add" class="easyui-combobox"   required="true">
                    <option value="" selected></option>
                    <option VALUE="105">CHINA </option>
                    <option VALUE="108">HONG KONG </option>
                    <option VALUE="123">INDIA </option>
                    <option VALUE="118">INDONESIA</option>
                    <option VALUE="143">ISRAEL </option>
                    <option VALUE="192">JAPAN </option>
                    <option VALUE="103">KOREA </option>
                    <option VALUE="113">MALAYSIA </option>
                    <option VALUE="131">NEPAL </option>
                    <option VALUE="117">PHILLIPINES </option>
                    <option VALUE="112">SINGAPORE </option>
                    <option VALUE="125">SRI LANKA </option>
                    <option VALUE="106">TAIWAN </option>
                    <option VALUE="111">THAILAND </option>
                    <option VALUE="144">JORDAN </option>
                    <option VALUE="141">OMAN</option>
                    <option VALUE="147">UNITED ARAB EMIRATES </option>
                    <option VALUE="208">BELGIUM </option>
                    <option VALUE="210">FRANCE </option>
                    <option VALUE="213">GERMANY </option>
                    <option VALUE="300">GREECE </option>
                    <option VALUE="237">LITHUANIA </option>
                    <option VALUE="207">NETHERLANDS </option>
                    <option VALUE="223">POLAND </option>
                    <option VALUE="217">PORTUGAL </option>
                    <option VALUE="218">SPAIN </option>
                    <option VALUE="215">SWITZERLAND </option>
                    <option VALUE="205">UNITED KINGDOM/UK </option>
                    <option VALUE="308">CANADA </option>
                    <option VALUE="304">USA </option>
                    <option VALUE="305">MEXICO </option>
                    <option VALUE="410">BRAZIL </option>
                    <option VALUE="409">CHILE </option>
                    <option VALUE="407">PERU </option>
                    <option VALUE="601">AUSTRALIA </option>
                    <option VALUE="606">NEW ZEALAND </option>
                    <option VALUE="506">EGYPT</option>
                </select>
                <span style="width:35px;display:inline-block;"></span>
            <span style="width:100px;display:inline-block;">SUPPLY TYPE</span>
                <select   style="width:200px;" name="cmb_supply_type_add" id="cmb_supply_type_add" class="easyui-combobox"  required="true">
                    <option value="Y" selected>CHARGE</option>
                    <option value="N" >FREE OF CHARGE</option>
                </select>
            </div>

            <div class="fitem">
                <span style="width:140px;display:inline-block;">BONDED TYPE</span>
                <select style="width:200px;" name="cmb_bonded_add" id="cmb_bonded_add" class="easyui-combobox"  required="true">
                    <option value=A selected>OVERSEA BONDED </option>
                    <option value=B >LOCAL BONDED </option>
                    <option value=C >LOCAL NON BONDED</option>
                </select>
                <span style="width:35px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">DAYS TRANS.</span>
                <input style="width:200px;" name="days_trans_add" id="days_trans_add" class="easyui-numberbox" required="true"/>
                <span style="width:100px;display:inline-block;">DAYS</span>
            </div>
        </fieldset>
              
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:auto; float:left;"><legend><span class="style3"><strong>TRADE TERMS</strong></span></legend>
           <div class="fitem">
                 <span style="width:140px;display:inline-block;">CURRENCY</span>
                 <select   style="width:150px;" name="cmb_currency_add" id="cmb_currency_add" class="easyui-combobox"  required="true">
                    <option VALUE=1 selected>US$ </option>
                    <option VALUE=5>SG$</option>
                    <option VALUE=7>EURO</option>
                    <option VALUE=8>YEN</option>
                    <option VALUE=23>RP</SELECT>
                </select>
                <span style="width:85px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">TRADE TERMS</span>
                <input  style="width:300px;" name="tterms_add" id="ttrems_add" class="easyui-textbox"/>
            </div>   
            <div class="fitem">
                <span style="width:140px;display:inline-block;">PAYMENT DAYS</span>
                <input  style="width:150px;" name="pday_add" id="pday_add" class="easyui-numberbox" required="true"/>
                <span style="width:85px;display:inline-block;"></span>
                <span style="width:100px;display:inline-block;">PAY TERMS</span>
                <input  style="width:300px;" name="pdesc_add" id="pdesc_add" class="easyui-textbox" />
            </div>
            <div class="fitem">
				<span style="width:140px;display:inline-block;">LOAD PORT</span>
				<input style="width:70px;" name="LOAD_PORT_CODE" id="LOAD_PORT_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="LOAD_PORT" id="LOAD_PORT" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('PORT LOADING')">SET</a>
            </div>
            <div class="fitem">
				<span style="width:140px;display:inline-block;">DISCHARGE PORT</span>
				<input style="width:70px;" name="DISCH_PORT_CODE" id="DISCH_PORT_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="DISCH_PORT" id="DISCH_PORT" class="easyui-textbox"/>
                <a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('PORT DISCHARGE')">SET</a>
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">FINAL DESTINATION</span>
				<input style="width:70px;" name="FINAL_DEST_CODE" id="FINAL_DEST_CODE" class="easyui-textbox"/>
				<input style="width:300px;" name="FINAL_DEST" id="FINAL_DEST" class="easyui-textbox"/>
				<a href="javascript:void(0)" class="easyui-linkbutton c6" onclick="SET_port_loading('FINAL DESTINATION')">SET</a>
			</div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">SHIPPING MARK</span>
                <input  style="width:896px; height:70px;" name="ship_mark_add" id="ship_mark_add" class="easyui-textbox" />
            </div>
        </fieldset>
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:auto; float:left;"><legend><span class="style3"><strong>TAX</strong></span></legend>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">TAXPAYER NO.</span>
                <input  style="width:150px;" name="taxpayer_add" id="taxpayer_add" class="easyui-textbox" />
                <span style="width:140px;display:inline-block;">ACCPAC COMP. CODE</span>
                <input  style="width:300px;" name="accpac_add" id="accpac_add" class="easyui-textbox" required="true"/>
            </div>
        </fieldset>
        <div id="dlg-buttons-edit">
            <a href="javascript:void(0)" id="save_edit" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveEdit()" style="width:90px">Save</a>
            <a href="javascript:void(0)" id="cancel_edit" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
        </div>
      </form>
	</div>
	<!-- END EDIT -->
	 
	<div id="toolbar">
		<fieldset style="border-radius:4px; border-radius:4px; width:98%; height:auto; float:left;"><legend><span class="style3"><strong>FILTER DATA COMPANY</strong></span></legend>
			<div class="fitem">
                <span style="width:100px;display:inline-block;">COMPANY NO.</span>
                <select style="width:330px;" name="company_rec" id="company_rec" class="easyui-combobox" data-options=" url:'../json/json_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px'"></select>
                <label><input type="checkbox" name="ck_company_no" id="ck_company_no" checked="true">All</input></label>
                <span style="width:100px;display:inline-block;"></span>
				<span style="width:100px;display:inline-block;">COMPANY TYPE</span>
				<select  style="width:300px;" name="cmb_company_type" id="cmb_company_type" class="easyui-combobox" require="true"  >
                        <option value="" selected></option>
                        <option value="1">CUSTOMER</option>
                        <option value="2" >CUSTOMER / VENDOR</option>
                        <option value="3" >VENDOR</option>
                        <option value="4" >SUB CONTRACTOR</option>
                        <option value="5" >SHIP TO</option>
                        <option value="7" >PLANT</option>
                </select>
                <label><input type="checkbox" name="ck_company_type" id="ck_company_type" checked="true">All</input></label>
			</div>
        </fieldset>
        
		<div style="clear:both;"></div>
        
        <div style="margin-top: 5px;margin: 5px;">
			<a href="javascript:void(0)" style="width: 140px;" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> FILTER DATA </a>
			<a href="javascript:void(0)" style="width: 140px;" class="easyui-linkbutton c2" id="add" onclick="addCompany()"><i class="fa fa-plus" aria-hidden="true"></i> ADD COMPANY</a>
			<a href="javascript:void(0)" style="width: 140px;" class="easyui-linkbutton c2" id="edit" onclick="editCompany()"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT COMPANY </a>
			<a href="javascript:void(0)" style="width: 140px;" class="easyui-linkbutton c2" id="delete" onclick="deleteCompany()"><i class="fa fa-trash" aria-hidden="true"></i> DELETE COMPANY </a>
			<a href="javascript:void(0)" style="width: 170px;" class="easyui-linkbutton c2" id="print" onclick="downloadCompany()"><i class="fa fa-download" aria-hidden="true"></i> DOWNLOAD COMPANY </a>
		</div>
	</div>

    <table id="dg" title="MASTER COMPANY" toolbar="#toolbar" class="easyui-datagrid" rownumbers="true" fitColumns="true" style="width:100%;height:590px;"></table>
    
	<script type="text/javascript">
		var flagTipe = "";

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
            $('#company_rec').combobox('disable');
            $('#ck_company_no').change(function(){
                if ($(this).is(':checked')) {
                    $('#company_rec').combobox('disable');
                }else{
                    $('#company_rec').combobox('enable');
                }
			});

            $('#cmb_company_type').combobox('disable');
            $('#ck_company_type').change(function(){
                if ($(this).is(':checked')) {
                    $('#cmb_company_type').combobox('disable');
                }else{
                    $('#cmb_company_type').combobox('enable');
                }
            });

			$('#dg').datagrid({
				singleSelect:true,
			    columns:[[
                    {field:'COMPANY_NO',title:'COMPANY NO.',width:45, halign: 'center', align: 'center'},
				    {field:'COMPANY_NAME',title:'NAME',width:100, halign: 'center', align: 'left'},
					{field:'COMPANY_TYPE',title:'TYPE', width:40, halign: 'center'},
					{field:'ADDRESS1',title:'ADDRESS1', width:150, halign: 'center'},
                    {field:'ADDRESS2',title:'ADDRESS2',width:55, halign: 'center'},
				    {field:'ADDRESS3',title:'ADDRESS3',width:60, halign: 'center'},
					{field:'ADDRESS4',title:'ADDRESS14', width:60, halign: 'center'},
					{field:'TERMS',title:'TERMS', width:150, halign: 'center'}
			    ]]
			})
		})

        var getUrl = '';
        
		function filterData(){
            var ck_company_no = "false";
            var ck_company_type = "false";

            if ($('#ck_company_no').attr("checked")) {
                ck_company_no = "true";
            }

            if ($('#ck_company_type').attr("checked")) {
                ck_company_type = "true";
            }

            var companyNo = $('#company_rec').combobox('getValue');
            var companyType = $('#cmb_company_type').combobox('getValue');
            $('#dg').datagrid('load', {
				company_no: companyNo,
                ck_company_no: ck_company_no,
                company_type: companyType,
                ck_company_type: ck_company_type
			});
			$('#dg').datagrid({
				url:'get_company.php'
			})
		   	$('#dg').datagrid('enableFilter');

            getUrl = "?company_no="+companyNo+
                "&ck_company_no="+ck_company_no+
                "&company_type="+companyType+
                "&ck_company_type="+ck_company_type

            console.log('get_company.php'+getUrl);
		}

		function deleteCompany(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						$.messager.progress({
						    title:'Please waiting',
						    msg:'removing data...'
						});
						// console.log('delete_bom.php?item_no='+row.UPPER_ITEM_NO+'&level_no='+row.LEVEL_NO)
						$.post('delete_bom.php',{item_no: row.UPPER_ITEM_NO,level_no: row.LEVEL_NO},function(result){
							if (result.success){
	                            $('#dg').datagrid('reload');
	                            $.messager.progress('close');
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
				$.messager.show({title: 'BOM DELETE',msg:'Data Not select'});
			}
		}

        function clearField(){
            $('#company_no_add').numberbox('setValue','');
            $('#cmb_company_type_add').combobox('setValue','');
            $('#company_name_add').textbox('setValue','');
            $('#company_short_name_add').textbox('setValue','');
            $('#company_address1_add').textbox('setValue','');
            $('#company_address2_add').textbox('setValue','');
            $('#company_address3_add').textbox('setValue','');
            $('#company_address4_add').textbox('setValue','');
            $('#attn_add').textbox('setValue','');
            $('#telno_add').textbox('setValue','');
            $('#faxno_add').textbox('setValue','');
            $('#zip_add').textbox('setValue','');
            $('#email_add').textbox('setValue','');
            $('#cmb_country_add').combobox('setValue','');
            $('#cmb_supply_type_add').combobox('setValue','');
            $('#cmb_bonded_add').combobox('setValue','');
            $('#days_trans_add').numberbox('setValue','0');
            $('#cmb_currency_add').combobox('setValue','1');
            $('#ttrems_add').textbox('setValue','');
            $('#pday_add').numberbox('setValue','0');
            $('#pdesc_add').textbox('setValue','');
            $('#ship_mark_add').textbox('setValue','');
            $('#taxpayer_add').textbox('setValue','');
            $('#accpac_add').textbox('setValue','');
        }
       
		function addCompany(){
			$('#dlg_add').dialog('open').dialog('setTitle','CREATE COMPANY');
			clearField();
		}

        function saveAdd(){
            var dataRows = [];
            var country = $('#cmb_country_add').combobox('getValue');
            var bonded = $('#cmb_bonded_add').combobox('getValue');
            var supply_type = $('#cmb_supply_type_add').combobox('getValue');
            var currency = $('#cmb_currency_add').combobox('getValue');
			dataRows.push({
                company_no: $('#company_no_add').numberbox('getValue'),
                company_type: $('#cmb_company_type_add').combobox('getValue'),
                company_name: $('#company_name_add').textbox('getValue'),
                company_short_name: $('#company_short_name_add').textbox('getValue'),
                company_address1: $('#company_address1_add').textbox('getValue'),
                company_address2: $('#company_address2_add').textbox('getValue'),
                company_address3: $('#company_address3_add').textbox('getValue'),
                company_address4: $('#company_address4_add').textbox('getValue'),
                attn: $('#attn_add').textbox('getValue'),
                telno: $('#telno_add').textbox('getValue'),
                faxno: $('#faxno_add').textbox('getValue'),
                zip_code: $('#zip_add').textbox('getValue'),
                email: $('#email_add').textbox('getValue'),
                country: country,
                supply_type: supply_type,
                bonded: bonded,
                days_trans: $('#days_trans_add').numberbox('getValue'),
                currency: currency,
                ttrems: $('#ttrems_add').textbox('getValue'),
                pday: $('#pday_add').numberbox('getValue'),
                pdesc: $('#pdesc_add').textbox('getValue'),
                ship_mark: $('#ship_mark_add').textbox('getValue'),
                taxpayer: $('#taxpayer_add').textbox('getValue'),
                accpac: $('#accpac_add').textbox('getValue')
			});

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			
			console.log(unescape(str_unescape));

			$.post('post_company.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				console.log(res);
                if(res == '"OK"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
        }

		function saveEditBOM(){
			var dataRows = [];
			var t = $('#dg_edit').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_edit').datagrid('endEdit',i);
				dataRows.push({
					upper_item_no: $('#item_no_edit').textbox('getValue'),
					level_no: $('#level_no_edit').textbox('getValue'),
					line_no: jmrow,
					lower_item_no: $('#dg_edit').datagrid('getData').rows[i].ITEM_NO,
					quantity: $('#dg_edit').datagrid('getData').rows[i].QUANTITY,
					quantity_base: $('#dg_edit').datagrid('getData').rows[i].QUANTITY_BASE,
					failure_rate: $('#dg_edit').datagrid('getData').rows[i].FAILURE_RATE
				});
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			
			console.log(unescape(str_unescape));

			$.post('post_bom.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_edit').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}

		function saveBOM(){
			var dataRows = [];
			var t = $('#dg_add').datagrid('getRows');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_add').datagrid('endEdit',i);
				dataRows.push({
					upper_item_no: $('#cmb_item_no_add').combobox('getValue'),
					level_no: $('#level_no_add').textbox('getValue'),
					line_no: jmrow,
					lower_item_no: $('#dg_add').datagrid('getData').rows[i].ITEM_NO,
					quantity: $('#dg_add').datagrid('getData').rows[i].QUANTITY,
					quantity_base: $('#dg_add').datagrid('getData').rows[i].QUANTITY_BASE,
					failure_rate: $('#dg_add').datagrid('getData').rows[i].FAILURE_RATE
				});
				
			}

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			
			console.log(unescape(str_unescape));

			$.post('post_bom.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				if(res == '"success"'){
					$('#dlg_add').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Insert Data Success..!!<br/>','info');
					$.messager.progress('close');
				}else{
					$.messager.alert('ERROR',res,'warning');
					$.messager.progress('close');
				}
			});
		}

		function editCompany(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				var lvl = row.LEVEL_NO;
				var item = row.UPPER_ITEM_NO;
				$('#dlg_edit').dialog('open').dialog('setTitle','EDIT COMPANY');
				$('#item_no_edit').textbox('setValue',item);
				$('#level_no_edit').textbox('setValue',lvl);

				$('#dg_edit').datagrid({
				    url:'get_bom_detail.php?item_no='+item+'&level_no='+lvl,
				    singleSelect: true,
				    fitColumns: true,
					rownumbers: true,
				    columns:[[
						{field:'ITEM_NO', title:'ITEM NO.', width:65, halign: 'center', align: 'center'},
						{field:'ITEM', title:'ITEM NAME', width:100, halign: 'center'},//, hidden: true},
						{field:'DESCRIPTION', title:'DESCRIPTION', width: 150, halign: 'center'},
						{field:'UOM_Q', title:'UNIT', width: 50, halign: 'center'},
						{field:'QUANTITY_BASE',title:'QTY BASE',width:100,halign:'center', align: 'right',editor:{type:'numberbox',options:{precision:2}}},
						{field:'QUANTITY',title:'QTY',width:100,halign:'center', align: 'right',editor:{type:'numberbox',options:{precision:2}}},
						{field:'FAILURE_RATE',title:'FAILURE RATE',width:100,halign:'center', align: 'right'	,editor:{type:'numberbox',options:{precision:2}}}
				    ]],
				    onClickRow:function(row){
				    	$(this).datagrid('beginEdit', row);
				    }
				});
			}
		}

		function search_item_add(){
			var s_item = document.getElementById('s_item_add').value;
			if(s_item != ''){
				$('#dg_addItem').datagrid('load',{item_no: s_item});
				$('#dg_addItem').datagrid({url: 'get_bom_material.php',});
				document.getElementById('s_item_add').value = '';
			}
		}

		function sch_item_add(event){
			var sch_a = document.getElementById('s_item_add').value;
			var search = sch_a.toUpperCase();
			document.getElementById('s_item_add').value = search;
			
		    if(event.keyCode == 13 || event.which == 13){
				search_item_add();
		    }
		}

		function search_item_edit(){
			var s_item = document.getElementById('s_item_edit').value;
	
			if(s_item != ''){
				$('#dg_addItem').datagrid('load',{item_no: s_item});
				$('#dg_addItem').datagrid({url: 'get_bom_material.php',});
				document.getElementById('s_item_edit').value = '';
			}
		}

		function sch_item_edit(event){
			var sch_a = document.getElementById('s_item_edit').value;
			var search = sch_a.toUpperCase();
			document.getElementById('s_item_edit').value = search;
			
			if(event.keyCode == 13 || event.which == 13){
				search_item_add();
			}
		}

		function remove_bom_item(){
			var row = $('#dg_add').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						var idx = $("#dg_add").datagrid("getRowIndex", row);
						$('#dg_add').datagrid('deleteRow', idx);
					}	
				});
			}
		}

		function remove_bom_item_edit(){
			var row = $('#dg_edit').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						var idx = $("#dg_edit").datagrid("getRowIndex", row);
						$('#dg_edit').datagrid('deleteRow', idx);
					}	
				});
			}
		}

		function downloadCompany(){
			$('#dlg_download').dialog('open').dialog('setTitle','SELECT TO DOWNLOAD');
			$('#dg_download').datagrid({
				url:'get_bom.php?sts=all',
				fitColumns: true,
				rownumbers: true,
				columns:[[
                    {field:'UPPER_ITEM_NO',title:'ITEM NO.',width:55, halign: 'center', align: 'center'},
				    {field:'DESCRIPTION',title:'DESCRIPTION',width:220, halign: 'center'},
					{field:'LEVEL_NO',title:'LEVEL_NO', width:60, halign: 'center'}
			    ]],
				onClickRow:function(row){
					$(this).datagrid('beginEdit', row);
				}
			});

			$('#dg_download').datagrid('enableFilter');
		}

		function downloadBOM_select(){
			var dataRows_dowload = [];
			var t = $('#dg_download').datagrid('getSelections');
			var total = t.length;
			var jmrow=0;
			for(i=0;i<total;i++){
				jmrow = i+1;
				$('#dg_download').datagrid('endEdit',i);
				dataRows_dowload.push({
					upper_item_no: $('#dg_download').datagrid('getData').rows[i].UPPER_ITEM_NO,
					quantity: $('#dg_download').datagrid('getData').rows[i].LEVEL_NO
				});
			}

			var myJSON_download=JSON.stringify(dataRows_dowload);
			var str_unescape_download=unescape(myJSON_download);
			
			console.log('bom_download.php?data='+str_unescape_download);

			// var fs = '';//require('fs');
			// fs.writeFile("bom_download.json", myJSON_download, function(err, result) {
			// 	if(err) console.log('error', err);
			// });

			if(dataRows_dowload == '') {
				$.messager.show({
					title: 'BOM Download',
					msg: 'Data Not Select'
				});
			}else{
				window.open('bom_download.php?data='+str_unescape_download, '_blank');
				$('#dlg_download').dialog('close');
				$('#dg_download').datagrid('loadData', []); 
				dataRows_dowload = [];
			}
		}
	</script>
</body>
</html>