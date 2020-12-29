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
</head>
<body>
	<?php include ('../../../ico_logout.php'); $exp = explode('-', access_log($menu_id,$user_name));?>
    <!-- ADD -->
	<div id='dlg_add' class="easyui-dialog" style="width:1100px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-add" data-options="modal:true">
	   <form id="ff" method="post" novalidate>	
	   
       <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:190px; float:left;"><legend><span class="style3"><strong>Company Add</strong></span></legend>
            <div class="fitem">
            <span style="width:140px;display:inline-block;">Company Code.</span>
                <input  style="width:150px;" name="company_no_add" id="company_no_add" class="easyui-numberbox" require="true"/>
                <span style="width:100px;display:inline-block;">Company Type</span>
                <select  style="width:300px;" name="cmb_company_type_add" id="cmb_company_type_add" class="easyui-combobox"  required="true" >
                                <option value="None" selected></option>
                                <option value="1">CUSTOMER</option>
                                <option value="2" >CUSTOMER / VENDOR</option>
                                <option value="3" >VENDOR</option>
                                <option value="4" >SUB CONTRACTOR</option>
                                <option value="5" >SHIP TO</option>
                                <option value="7" >PLANT</option>
                </select>
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">Company Name.</span>
                <input style="width:250px;" name="company_name_add" id="company_name_add" class="easyui-textbox" required="true"/>
                <span style="width:140px;display:inline-block;">Company Short name</span>
                 <input  style="width:150px;" name="company_short_name_add" id="company_short_name_add" class="easyui-textbox" />
               
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">Company Address.</span>
                <input style="width:250px;" name="company_address1_add" id="company_address1_add" class="easyui-textbox" required="true"/>
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input  style="width:250px;" name="company_address2_add" id="company_address2_add" class="easyui-textbox" />
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input style="width:250px;" name="company_address3_add" id="company_address3_add" class="easyui-textbox" />
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input  style="width:250px;" name="company_address4_add" id="company_address4_add" class="easyui-textbox" />
            </div>  
            
        </fieldset>
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:150px; float:left;"><legend><span class="style3"><strong>Company Detail</strong></span></legend>
            <div class="fitem">
                 <span style="width:100px;display:inline-block;">Attn</span>
                    <input style="width:150px;" name="attn_add" id="attn_add" class="easyui-textbox" />
                  
            </div>
            <div class="fitem">
                  <span style="width:100px;display:inline-block;">Tel No.</span>
                    <input  style="width:150px;" name="telno_add" id="telno_add" class="easyui-textbox" />
                    <span style="width:100px;display:inline-block;">Fax No.</span>
                    <input style="width:150px;" name="faxno_add" id="faxno_add" class="easyui-textbox" />
                    <span style="width:100px;display:inline-block;">Zip Code.</span>
                    <input  style="width:150px;" name="zip_add" id="zip_add" class="easyui-textbox" />
                    <span style="width:100px;display:inline-block;">Email</span>
                <input  style="width:150px;" name="email_add" id="email_add" class="easyui-textbox" />
                    
             </div>
            
            <div class="fitem">
            <span style="width:100px;display:inline-block;">Country</span>
                <select  style="width:300px;" name="cmb_country_add" id="cmb_country_add" class="easyui-combobox"   required="true">
                    <option value="None" selected></option>
                    <OPTION VALUE="105" >CHINA </option>
                    <OPTION VALUE="108" >HONG KONG </option>
                    <OPTION VALUE="123" >INDIA </option>
                    <OPTION VALUE="118" >INDONESIA</option>
                     <OPTION VALUE="143" >ISRAEL </option>
                    <OPTION VALUE="192" >JAPAN </option>
                    <OPTION VALUE="103" >KOREA </option>
                    <OPTION VALUE="113" >MALAYSIA </option>
                    <OPTION VALUE="131" >NEPAL </option>
                    <OPTION VALUE="117" >PHILLIPINES </option>
                    <OPTION VALUE="112" >SINGAPORE </option>
                    <OPTION VALUE="125" >SRI LANKA </option>
                    <OPTION VALUE="106" >TAIWAN </option>
                    <OPTION VALUE="111" >THAILAND </option>
                    <OPTION VALUE="144" >JORDAN </option>
                    <OPTION VALUE="141" >OMAN</option>
                    <OPTION VALUE="147" >UNITED ARAB EMIRATES </option>
                    <OPTION VALUE="208" >BELGIUM </option>
                    <OPTION VALUE="210" >FRANCE </option>
                    <OPTION VALUE="213" >GERMANY </option>
                    <OPTION VALUE="300" >GREECE </option>
                    <OPTION VALUE="237" >LITHUANIA </option>
                    <OPTION VALUE="207" >NETHERLANDS </option>
                    <OPTION VALUE="223" >POLAND </option>
                    <OPTION VALUE="217" >PORTUGAL </option>
                    <OPTION VALUE="218" >SPAIN </option>
                    <OPTION VALUE="215" >SWITZERLAND </option>
                    <OPTION VALUE="205" >UNITED KINGDOM/UK </option>
                    <OPTION VALUE="308" >CANADA </option>
                    <OPTION VALUE="304" >USA </option>
                    <OPTION VALUE="305" >MEXICO </option>
                    <OPTION VALUE="410" >BRAZIL </option>
                    <OPTION VALUE="409" >CHILE </option>
                    <OPTION VALUE="407" >PERU </option>
                    <OPTION VALUE="601" >AUSTRALIA </option>
                    <OPTION VALUE="606" >NEW ZEALAND </option>
                    <OPTION VALUE="506" >EGYPT</option>
                </select>
            <span style="width:140px;display:inline-block;">Supply Type</span>
                <select   style="width:300px;" name="cmb_supply_type_add" id="cmb_supply_type_add" class="easyui-combobox"  required="true">
                               
                                <option value="Y" selected>CHARGE</option>
                                <option value="N" >FREE OF CHARGE</option>
                </select>

            </div>

            <div class="fitem">
            <span style="width:100px;display:inline-block;">Bonded Type</span>
            <select style="width:300px;" name="cmb_bonded_add" id="cmb_bonded_add" class="easyui-combobox"  required="true">
                <option value="A" selected>OVERSEA BONDED </option>
                <option value="B" >LOCAL BONDED </option>
                <option value="C" >LOCAL NON BONDED</option>
            </select>
            <span style="width:140px;display:inline-block;">Days Of Transport</span>
            <input style="width:150px;" name="days_trans_add" id="days_trans_add" class="easyui-numberbox" required="true"/>
            <span style="width:100px;display:inline-block;">Days</span>
            
            </div>
           
        </fieldset>
              
       
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:150px; float:left;"><legend><span class="style3"><strong>Trade Terms</strong></span></legend>
           <div class="fitem">
                 <span style="width:100px;display:inline-block;">Currency</span>
                 <select   style="width:150px;" name="cmb_currency_add" id="cmb_currency_add" class="easyui-combobox"  required="true">
                 
                     <OPTION VALUE=1 selected>US$ </option>
                    <OPTION VALUE=5 >SG$ </option>
                    <OPTION VALUE=7 >EURO </option>
                    <OPTION VALUE=8 >YEN  </option>
                    <OPTION VALUE=23 >RP </SELECT>
                </select>
                <span style="width:100px;display:inline-block;">Trade Terms</span>
                <input  style="width:300px;" name="tterms_add" id="ttrems_add" class="easyui-textbox" />
                    
           
            </div>   
            <div class="fitem">
                 <span style="width:100px;display:inline-block;">Payment Days</span>
                 <input  style="width:150px;" name="pday_add" id="pday_add" class="easyui-numberbox" required="true"/>
                <span style="width:100px;display:inline-block;">Payment Terms</span>
                <input  style="width:300px;" name="pdesc_add" id="pdesc_add" class="easyui-textbox" />
                    
           
            </div>  
            <div class="fitem">
                 <span style="width:100px;display:inline-block;">Shipping Mark</span>
                 <input  style="width:600px; height:70px;" name="ship_mark_add" id="ship_mark_add" class="easyui-textbox" />
            </div>  
        </fieldset>
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:70px; float:left;"><legend><span class="style3"><strong>Misc</strong></span></legend>
        <div class="fitem">
              
           
            </div> 
            <div class="fitem">
                 <span style="width:100px;display:inline-block;">Taxpayer No.</span>
                 <input  style="width:150px;" name="taxpayer_add" id="taxpayer_add" class="easyui-textbox" />
                <span style="width:150px;display:inline-block;">Accpac Company Code</span>
                <input  style="width:300px;" name="accpac_add" id="accpac_add" class="easyui-textbox" required="true"/>
                    
           
            </div> 
        </fieldset> 
       
        <div id="dlg-buttons-add">
            <a href="javascript:void(0)" id="save_add" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveAdd()" style="width:90px">Save</a>
            <a href="javascript:void(0)" id="cancel_add" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_add').dialog('close')" style="width:90px">Cancel</a>
        </div>
        </div>
       
       
        </form>
	</div>
	<!-- END ADD -->

     <!-- EDIT -->
     <div id='dlg_edit' class="easyui-dialog" style="width:1100px;height:550px;padding:5px 5px;" closed="true" buttons="#dlg-buttons-edit" data-options="modal:true">
	   <form id="ff1" method="post" novalidate>	
	   
	 <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:190px; float:left;"><legend><span class="style3"><strong>Company Add</strong></span></legend>
            <div class="fitem">
            <span style="width:140px;display:inline-block;">Company Code.</span>
                <input  style="width:150px;" name="company_no_edit" id="company_no_edit" class="easyui-numberbox" require="true"/>
                <span style="width:100px;display:inline-block;">Company Type</span>
                <select  style="width:300px;" name="cmb_company_type_edit" id="cmb_company_type_edit" class="easyui-combobox"  required="true" >
                                <option value="None" selected></option>
                                <option value="1">CUSTOMER</option>
                                <option value="2" >CUSTOMER / VENDOR</option>
                                <option value="3" >VENDOR</option>
                                <option value="4" >SUB CONTRACTOR</option>
                                <option value="5" >SHIP TO</option>
                                <option value="7" >PLANT</option>
                </select>
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">Company Name.</span>
                <input style="width:250px;" name="company_name_edit" id="company_name_edit" class="easyui-textbox" required="true"/>
                <span style="width:140px;display:inline-block;">Company Short name</span>
                 <input  style="width:150px;" name="company_short_name_edit" id="company_short_name_edit" class="easyui-textbox" />
               
            </div>
            <div class="fitem">
                <span style="width:140px;display:inline-block;">Company Address.</span>
                <input style="width:250px;" name="company_address1_edit" id="company_address1_edit" class="easyui-textbox" required="true"/>
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input  style="width:250px;" name="company_address2_edit" id="company_address2_edit" class="easyui-textbox" />
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input style="width:250px;" name="company_address3_edit" id="company_address3_edit" class="easyui-textbox" />
            </div> 
            <div class="fitem"> 
                <span style="width:140px;display:inline-block;"></span>
                <input  style="width:250px;" name="company_address4_edit" id="company_address4_edit" class="easyui-textbox" />
            </div>  
            
        </fieldset>
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:150px; float:left;"><legend><span class="style3"><strong>Company Detail</strong></span></legend>
            <div class="fitem">
                 <span style="width:100px;display:inline-block;">Attn</span>
                    <input style="width:150px;" name="attn_edit" id="attn_edit" class="easyui-textbox" />
                  
            </div>
            <div class="fitem">
                  <span style="width:100px;display:inline-block;">Tel No.</span>
                    <input  style="width:150px;" name="telno_edit" id="telno_edit" class="easyui-textbox" />
                    <span style="width:100px;display:inline-block;">Fax No.</span>
                    <input style="width:150px;" name="faxno_edit" id="faxno_edit" class="easyui-textbox" />
                    <span style="width:100px;display:inline-block;">Zip Code.</span>
                    <input  style="width:150px;" name="zip_edit" id="zip_edit" class="easyui-textbox" />
                    <span style="width:100px;display:inline-block;">Email</span>
                <input  style="width:150px;" name="email_edit" id="email_edit" class="easyui-textbox" />
                    
             </div>
            
            <div class="fitem">
            <span style="width:100px;display:inline-block;">Country</span>
                <select  style="width:300px;" name="cmb_country_edit" id="cmb_country_edit" class="easyui-combobox"   required="true">
                    <option value="None" selected></option>
                    <OPTION VALUE="105" >CHINA </option>
                    <OPTION VALUE="108" >HONG KONG </option>
                    <OPTION VALUE="123" >INDIA </option>
                    <OPTION VALUE="118" >INDONESIA</option>
                     <OPTION VALUE="143" >ISRAEL </option>
                    <OPTION VALUE="192" >JAPAN </option>
                    <OPTION VALUE="103" >KOREA </option>
                    <OPTION VALUE="113" >MALAYSIA </option>
                    <OPTION VALUE="131" >NEPAL </option>
                    <OPTION VALUE="117" >PHILLIPINES </option>
                    <OPTION VALUE="112" >SINGAPORE </option>
                    <OPTION VALUE="125" >SRI LANKA </option>
                    <OPTION VALUE="106" >TAIWAN </option>
                    <OPTION VALUE="111" >THAILAND </option>
                    <OPTION VALUE="144" >JORDAN </option>
                    <OPTION VALUE="141" >OMAN</option>
                    <OPTION VALUE="147" >UNITED ARAB EMIRATES </option>
                    <OPTION VALUE="208" >BELGIUM </option>
                    <OPTION VALUE="210" >FRANCE </option>
                    <OPTION VALUE="213" >GERMANY </option>
                    <OPTION VALUE="300" >GREECE </option>
                    <OPTION VALUE="237" >LITHUANIA </option>
                    <OPTION VALUE="207" >NETHERLANDS </option>
                    <OPTION VALUE="223" >POLAND </option>
                    <OPTION VALUE="217" >PORTUGAL </option>
                    <OPTION VALUE="218" >SPAIN </option>
                    <OPTION VALUE="215" >SWITZERLAND </option>
                    <OPTION VALUE="205" >UNITED KINGDOM/UK </option>
                    <OPTION VALUE="308" >CANADA </option>
                    <OPTION VALUE="304" >USA </option>
                    <OPTION VALUE="305" >MEXICO </option>
                    <OPTION VALUE="410" >BRAZIL </option>
                    <OPTION VALUE="409" >CHILE </option>
                    <OPTION VALUE="407" >PERU </option>
                    <OPTION VALUE="601" >AUSTRALIA </option>
                    <OPTION VALUE="606" >NEW ZEALAND </option>
                    <OPTION VALUE="506" >EGYPT</option>
                </select>
            <span style="width:140px;display:inline-block;">Supply Type</span>
                <select   style="width:300px;" name="cmb_supply_type_edit" id="cmb_supply_type_edit" class="easyui-combobox"  required="true">
                               
                                <option value="Y" selected>CHARGE</option>
                                <option value="N" >FREE OF CHARGE</option>
                </select>

            </div>

            <div class="fitem">
            <span style="width:100px;display:inline-block;">Bonded Type</span>
            <select style="width:300px;" name="cmb_bonded_edit" id="cmb_bonded_edit" class="easyui-combobox"  required="true">
                <option value="A" selected>OVERSEA BONDED </option>
                <option value="B" >LOCAL BONDED </option>
                <option value="C" >LOCAL NON BONDED</option>
            </select>
            <span style="width:140px;display:inline-block;">Days Of Transport</span>
            <input style="width:150px;" name="days_trans_edit" id="days_trans_edit" class="easyui-numberbox" required="true"/>
            <span style="width:100px;display:inline-block;">Days</span>
            
            </div>
           
        </fieldset>
              
       
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:150px; float:left;"><legend><span class="style3"><strong>Trade Terms</strong></span></legend>
           <div class="fitem">
                 <span style="width:100px;display:inline-block;">Currency</span>
                 <select   style="width:150px;" name="cmb_currency_edit" id="cmb_currency_edit" class="easyui-combobox"  required="true">
                 
                     <OPTION VALUE=1 selected>US$ </option>
                    <OPTION VALUE=5 >SG$ </option>
                    <OPTION VALUE=7 >EURO </option>
                    <OPTION VALUE=8 >YEN  </option>
                    <OPTION VALUE=23 >RP </SELECT>
                </select>
                <span style="width:100px;display:inline-block;">Trade Terms</span>
                <input  style="width:300px;" name="tterms_edit" id="tterms_edit" class="easyui-textbox" />
                    
           
            </div>   
            <div class="fitem">
                 <span style="width:100px;display:inline-block;">Payment Days</span>
                 <input  style="width:150px;" name="pday_edit" id="pday_edit" class="easyui-numberbox" required="true"/>
                <span style="width:100px;display:inline-block;">Payment Terms</span>
                <input  style="width:300px;" name="pdesc_edit" id="pdesc_edit" class="easyui-textbox" />
                    
           
            </div>  
            <div class="fitem">
                 <span style="width:100px;display:inline-block;">Shipping Mark</span>
                 <input  style="width:600px; height:70px;" name="ship_mark_edit" id="ship_mark_edit" class="easyui-textbox" />
            </div>  
        </fieldset>
        <fieldset style="border-radius:4px; border-radius:4px; width:98%; height:70px; float:left;"><legend><span class="style3"><strong>Misc</strong></span></legend>
        <div class="fitem">
              
           
            </div> 
            <div class="fitem">
                 <span style="width:100px;display:inline-block;">Taxpayer No.</span>
                 <input  style="width:150px;" name="taxpayer_edit" id="taxpayer_edit" class="easyui-textbox" />
                <span style="width:150px;display:inline-block;">Accpac Company Code</span>
                <input  style="width:300px;" name="accpac_edit" id="accpac_edit" class="easyui-textbox" required="true"/>
                    
           
            </div> 
        </fieldset> 
       
        <div id="dlg-buttons-edit">
            <a href="javascript:void(0)" id="save_edit" class="easyui-linkbutton c6" iconCls="icon-ok" onClick="saveEdit()" style="width:90px">Save</a>
            <a href="javascript:void(0)" id="cancel_edit" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_edit').dialog('close')" style="width:90px">Cancel</a>
        </div>
        </div>
    
        </form>
	</div>
	<!-- END EDIT -->
	 
	<div id="toolbar">
		<fieldset style="border-radius:4px; border-radius:4px; width:98%; height:80px; float:left;"><legend><span class="style3"><strong>COMPANY</strong></span></legend>
        <div style="width:470px; float:left;">
			
			<div class="fitem">
					<span style="width:100px;display:inline-block;">Company No.</span>
                    <select style="width:330px;" name="company_rec" id="company_rec" class="easyui-combobox" data-options=" url:'../../json/json_sp_company.php',method:'get',valueField:'company_code',textField:'company', panelHeight:'150px'"></select> 	
             
		   </div>
			<div class="fitem">
				<span style="width:100px;display:inline-block;">Company Type</span>
				<select  style="width:300px;" name="cmb_company_type" id="cmb_company_type" class="easyui-combobox" require="true"  >
                        <option value="" selected></option>
                        <option value="1">CUSTOMER</option>
                        <option value="2" >CUSTOMER / VENDOR</option>
                        <option value="3" >VENDOR</option>
                        <option value="4" >SUB CONTRACTOR</option>
                        <option value="5" >PLANT</option>
                        <option value="7" >SHIP TO</option>
                    </select>
			</div>
			
		</fieldset>
		<div style="clear:both;"></div>
		<div style="margin-top: 5px;margin: 5px;">
			<a href="javascript:void(0)" id="savebtn" class="easyui-linkbutton c2" onClick="filterData()" style="width:100px;"><i class="fa fa-filter" aria-hidden="true"></i> Filter</a>
			<a href="javascript:void(0)" style="width: 140px;" class="easyui-linkbutton c2" id="add" onclick="addBOM()"><i class="fa fa-plus" aria-hidden="true"></i> Add Company</a>
			<a href="javascript:void(0)" style="width: 140px;" class="easyui-linkbutton c2" id="edit" onclick="editBOM()"><i class="fa fa-pencil" aria-hidden="true"></i> Edit Company</a>
			<a href="javascript:void(0)" style="width: 140px;" class="easyui-linkbutton c2" id="delete" onclick="deleteBOM()"><i class="fa fa-trash" aria-hidden="true"></i> Delete Company</a>
			<a href="javascript:void(0)" style="width: 170px;" class="easyui-linkbutton c2" id="delete" onclick="downloadBOM()"><i class="fa fa-download" aria-hidden="true"></i> Download Company</a>
		</div>
	</div>

	<table id="dg" title="MASTER COMPANY" toolbar="#toolbar" class="easyui-datagrid" rownumbers="true" fitColumns="true" style="width:100%;height:590px;"></table>

	<div id="dlg_download" class="easyui-dialog" style="width: 700px;height: 400px;" closed="true" buttons="#dlg-buttons-download" data-options="modal:true" align="center">		
		<table id="dg_download" class="easyui-datagrid" style="width:100%;height:100%;border-radius: 10px;" rownumbers="true"></table>
	</div>
	<div id="dlg-buttons-download">
		<a href="javascript:void(0)" class="easyui-linkbutton c6" onClick="downloadBOM_select()" style="width:100px"><i class="fa fa-download" aria-hidden="true"></i> DOWNLOAD</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg_download').dialog('close')" style="width:90px">Cancel</a>
	</div>

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
			if (add == 'ADD/T'){
				$('#add').linkbutton('enable');
			}else{
				$('#add').linkbutton('disable');
			}
		}

		$(function(){
			$('#dg').datagrid({
				singleSelect:true,
			    columns:[[
                    {field:'COMPANY_NO',title:'COMPANY NO.',width:45, halign: 'center', align: 'center'},
				    {field:'COMPANY_NAME',title:'NAME',width:100, halign: 'center', align: 'left'},
					{field:'COMPANY_TYPE',title:'TYPE', width:40, halign: 'center'},
					{field:'ADDRESS1',title:'ADDRESS1', width:150, halign: 'center'},
                    {field:'ADDRESS2',title:'ADDRESS2',width:55, halign: 'center', align: 'left'},
				    {field:'ADDRESS3',title:'ADDRESS3',width:60, halign: 'center', align: 'left'},
					{field:'ADDRESS4',title:'ADDRESS14', width:60, halign: 'center'},
					{field:'TERMS',title:'TERMS', width:150, halign: 'center'},


                    {field:'ATTN',title:'ATTN', width:150, halign: 'center',hidden: true},
                    {field:'TEL_NO',title:'TEL_NO', width:150, halign: 'center',hidden: true},
                    {field:'FAX_NO',title:'FAX_NO', width:150, halign: 'center',hidden: true},
                    {field:'ZIP_CODE',title:'ZIP_CODE', width:150, halign: 'center',hidden: true},
                    {field:'COUNTRY_CODE',title:'COUNTRY_CODE', width:150, halign: 'center',hidden: true},
                    {field:'CURR_CODE',title:'CURR_CODE', width:150, halign: 'center',hidden: true},
                    {field:'TTERM',title:'TTERM', width:150, halign: 'center',hidden: true},
                    {field:'PDAYS',title:'PDAYS', width:150, halign: 'center',hidden: true},
                    {field:'PDESC',title:'PDESC', width:150, halign: 'center',hidden: true},
                    {field:'CASE_MARK',title:'CASE_MARK', width:150, halign: 'center',hidden: true},
                    {field:'EDI_CODE',title:'EDI_CODE', width:150, halign: 'center',hidden: true},
                    {field:'VAT',title:'VAT', width:150, halign: 'center',hidden: true},
                    {field:'SUPPLY_TYPE',title:'SUPPLY_TYPE', width:150, halign: 'center',hidden: true},
                    {field:'SUBC_CODE',title:'SUBC_CODE', width:150, halign: 'center',hidden: true},
                    {field:'TRANSPORT_DAYS',title:'TRANSPORT_DAYS', width:150, halign: 'center',hidden: true},
                    {field:'CC',title:'CC', width:150, halign: 'center',hidden: true},
                    {field:'COMPANY_SHORT',title:'COMPANY_SHORT', width:150, halign: 'center',hidden: true},
                    {field:'TAXPAYER_NO',title:'TAXPAYER_NO', width:150, halign: 'center',hidden: true},

                    {field:'E_MAIL',title:'E_MAIL', width:150, halign: 'center',hidden: true},
                    {field:'QUOT_SALE_CODE',title:'QUOT_SALE_CODE', width:150, halign: 'center',hidden: true},
                    {field:'ACCPAC_COMPANY_CODE',title:'ACCPAC_COMPANY_CODE', width:150, halign: 'center',hidden: true},
                    {field:'BONDED_TYPE',title:'BONDED_TYPE', width:150, halign: 'center',hidden: true},
                    {field:'BC_DOC',title:'BC_DOC', width:150, halign: 'center',hidden: true},
                    {field:'BC_DOC_REVERSE',title:'BC_DOC_REVERSE', width:150, halign: 'center',hidden: true}


			    ]]
			})
		})

        var get_url='';
        
		function filterData(){
            var companyNo = $('#company_rec').combobox('getValue');
            var companyType = $('#cmb_company_type').combobox('getValue');
            $('#dg').datagrid('load', {
				company_no: companyNo,
                company_type: companyType
			});

			$('#dg').datagrid({
				url:'get_sp_company.php'
			})

		   	$('#dg').datagrid('enableFilter');

            get_url='?company_no='+companyNo+
                    '&company_type='+companyType;
		}

		function deleteBOM(){
			var row = $('#dg').datagrid('getSelected');	
			if (row){
				$.messager.confirm('Confirm','Are you sure you want to remove?',function(r){
					if(r){
						$.messager.progress({
						    title:'Please waiting',
						    msg:'removing data...'
						});
						// console.log('delete_bom.php?item_no='+row.UPPER_ITEM_NO+'&level_no='+row.LEVEL_NO)
						$.post('del_sp_company.php',{company_code: row.COMPANY_NO},function(result){
							if (result.success == 'success'){
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
            //             company_no_add
            // cmb_company_type_add
            // company_name_add
            // company_short_name_add
            // company_address1_add
            // company_address2_add
            // company_address3_add
            // company_address4_add
            // attn_add
            // telno_add
            // faxno_add
            // zip_code_add
            // email_add
            // cmb_country_add
            // cmb_supply_type_add
            // cmb_bonded_add
            // days_trans_add
            // cmb_currency_add
            // ttrems_add
            // pday_add
            // pdesc_add
            // ship_mark_add
            // taxpayer_add
            // accpac_add
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

            $('#company_no_edit').numberbox('setValue','');
            $('#cmb_company_type_edit').combobox('setValue','');
            $('#company_name_edit').textbox('setValue','');
            $('#company_short_name_edit').textbox('setValue','');
            $('#company_address1_edit').textbox('setValue','');
            $('#company_address2_edit').textbox('setValue','');
            $('#company_address3_edit').textbox('setValue','');
            $('#company_address4_edit').textbox('setValue','');
            $('#attn_edit').textbox('setValue','');
            $('#telno_edit').textbox('setValue','');
            $('#faxno_edit').textbox('setValue','');
            $('#zip_edit').textbox('setValue','');
            $('#email_edit').textbox('setValue','');
            $('#cmb_country_edit').combobox('setValue','');
            $('#cmb_supply_type_edit').combobox('setValue','');
            $('#cmb_bonded_edit').combobox('setValue','');
            $('#days_trans_edit').numberbox('setValue','0');
            $('#cmb_currency_edit').combobox('setValue','1');
            $('#ttrems_edit').textbox('setValue','');
            $('#pday_edit').numberbox('setValue','0');
            $('#pdesc_edit').textbox('setValue','');
            $('#ship_mark_edit').textbox('setValue','');
            $('#taxpayer_edit').textbox('setValue','');
            $('#accpac_edit').textbox('setValue','');
        }
       
		function addBOM(){
			$('#dlg_add').dialog('open').dialog('setTitle','Create Company');
			clearField();
			
		}

        function saveAdd(){
            var country = $('#cmb_country_add').combobox('getValue');
            var bonded = $('#cmb_bonded_add').combobox('getValue');
            var supply_type = $('#cmb_supply_type_add').combobox('getValue');
            var currency = $('#cmb_currency_add').combobox('getValue');
            var dataRows = [];
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

			$.post('post_sp_company.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				console.log(res);
                if(res.length == 2){
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

		

		function saveEdit(){
			var country = $('#cmb_country_edit').combobox('getValue');
            var bonded = $('#cmb_bonded_edit').combobox('getValue');
            var supply_type = $('#cmb_supply_type_edit').combobox('getValue');
            var currency = $('#cmb_currency_edit').combobox('getValue');
            var dataRows = [];
			dataRows.push({
					        company_no: $('#company_no_edit').numberbox('getValue'),
                            company_type: $('#cmb_company_type_edit').combobox('getValue'),
                            company_name: $('#company_name_edit').textbox('getValue'),
                            company_short_name: $('#company_short_name_edit').textbox('getValue'),
                            company_address1: $('#company_address1_edit').textbox('getValue'),
                            company_address2: $('#company_address2_edit').textbox('getValue'),
                            company_address3: $('#company_address3_edit').textbox('getValue'),
                            company_address4: $('#company_address4_edit').textbox('getValue'),
                            attn: $('#attn_edit').textbox('getValue'),
                            telno: $('#telno_edit').textbox('getValue'),
                            faxno: $('#faxno_edit').textbox('getValue'),
                            zip_code: $('#zip_edit').textbox('getValue'),
                            email: $('#email_edit').textbox('getValue'),
                            country: country,
                            supply_type: supply_type,
                            bonded: bonded,
                            days_trans: $('#days_trans_edit').numberbox('getValue'),
                            currency: currency,
                            tterms: $('#tterms_edit').textbox('getValue'),
                            pday: $('#pday_edit').numberbox('getValue'),
                            pdesc: $('#pdesc_edit').textbox('getValue'),
                            ship_mark: $('#ship_mark_edit').textbox('getValue'),
                            taxpayer: $('#taxpayer_edit').textbox('getValue'),
                            accpac: $('#accpac_edit').textbox('getValue')
			});
			

			var myJSON=JSON.stringify(dataRows);
			var str_unescape=unescape(myJSON);
			
			console.log(unescape(str_unescape));

			$.post('put_sp_company.php',{
				data: unescape(str_unescape)
			}).done(function(res){
				console.log(res);
                if(res.length == 2){
					$('#dlg_edit').dialog('close');
					$('#dg').datagrid('reload');
					$.messager.alert('INFORMATION','Update Data Success..!!<br/>','info');
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

			$.post('post_sp_bom.php',{
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

		function editBOM(){
			var row = $('#dg').datagrid('getSelected');	
            clearField();
			$('#dlg_edit').dialog('open').dialog('setTitle','Edit Company');
			if (row){
                $('#company_no_edit').numberbox('setValue',row.COMPANY_NO);
                $('#cmb_company_type_edit').combobox('setValue',row.COMPANY_TYPE);
                $('#company_name_edit').textbox('setValue',row.COMPANY_NAME);
                $('#company_short_name_edit').textbox('setValue',row.COMPANY_SHORT);
                $('#company_address1_edit').textbox('setValue',row.ADDRESS1);
                $('#company_address2_edit').textbox('setValue',row.ADDRESS2);
                $('#company_address3_edit').textbox('setValue',row.ADDRESS3);
                $('#company_address4_edit').textbox('setValue',row.ADDRESS4);
                $('#attn_edit').textbox('setValue',row.ATTN);
                $('#telno_edit').textbox('setValue',row.TEL_NO);
                $('#faxno_edit').textbox('setValue',row.FAX_NO);
                $('#zip_edit').textbox('setValue',row.ZIP_CODE);
                $('#email_edit').textbox('setValue',row.E_MAIL);
                $('#cmb_country_edit').combobox('setValue',row.COUNTRY_CODE);
                $('#cmb_supply_type_edit').combobox('setValue',row.SUPPLY_TYPE);
                $('#cmb_bonded_edit').combobox('setValue',row.BONDED_TYPE);
                $('#days_trans_edit').numberbox('setValue',row.TRANSPORT_DAYS);
                $('#cmb_currency_edit').combobox('setValue',row.CURR_CODE);
                $('#tterms_edit').textbox('setValue',row.TTERMS);
                $('#pday_edit').numberbox('setValue',row.PDAYS);
                $('#pdesc_edit').textbox('setValue',row.PDESC);
                // $('#ship_mark_edit').textbox('setValue',row.);
                $('#taxpayer_edit').textbox('setValue',row.TAXPAYER_NO);
                $('#accpac_edit').textbox('setValue',row.ACCPAC_COMPANY_CODE);

                // {field:'COMPANY_NO',title:'COMPANY NO.',width:45, halign: 'center', align: 'center'},
				//     {field:'COMPANY_NAME',title:'NAME',width:100, halign: 'center', align: 'left'},
				// 	{field:'COMPANY_TYPE',title:'TYPE', width:40, halign: 'center'},
				// 	{field:'ADDRESS1',title:'ADDRESS1', width:150, halign: 'center'},
                //     {field:'ADDRESS2',title:'ADDRESS2',width:55, halign: 'center', align: 'center'},
				//     {field:'ADDRESS3',title:'ADDRESS3',width:60, halign: 'center', align: 'center'},
				// 	{field:'ADDRESS4',title:'ADDRESS14', width:60, halign: 'center'},
				// 	{field:'TERMS',title:'TERMS', width:150, halign: 'center'},


                //     {field:'ATTN',title:'ATTN', width:150, halign: 'center',hidden: true},
                //     {field:'TEL_NO',title:'TEL_NO', width:150, halign: 'center',hidden: true},
                //     {field:'FAX_NO',title:'FAX_NO', width:150, halign: 'center',hidden: true},
                //     {field:'ZIP_CODE',title:'ZIP_CODE', width:150, halign: 'center',hidden: true},
                //     {field:'COUNTRY_CODE',title:'COUNTRY_CODE', width:150, halign: 'center',hidden: true},
                //     {field:'CURR_CODE',title:'CURR_CODE', width:150, halign: 'center',hidden: true},
                //     {field:'TTERM',title:'TTERM', width:150, halign: 'center',hidden: true},
                //     {field:'PDAYS',title:'PDAYS', width:150, halign: 'center',hidden: true},
                //     {field:'PDESC',title:'PDESC', width:150, halign: 'center',hidden: true},
                //     {field:'CASE_MARK',title:'CASE_MARK', width:150, halign: 'center',hidden: true},
                //     {field:'EDI_CODE',title:'EDI_CODE', width:150, halign: 'center',hidden: true},
                //     {field:'VAT',title:'VAT', width:150, halign: 'center',hidden: true},
                //     {field:'SUPPLY_TYPE',title:'SUPPLY_TYPE', width:150, halign: 'center',hidden: true},
                //     {field:'SUBC_CODE',title:'SUBC_CODE', width:150, halign: 'center',hidden: true},
                //     {field:'TRANSPORT_DAYS',title:'TRANSPORT_DAYS', width:150, halign: 'center',hidden: true},
                //     {field:'CC',title:'CC', width:150, halign: 'center',hidden: true},
                //     {field:'COMPANY_SHORT',title:'COMPANY_SHORT', width:150, halign: 'center',hidden: true},
                //     {field:'TAXPAYER_NO',title:'TAXPAYER_NO', width:150, halign: 'center',hidden: true},

                //     {field:'E_MAIL',title:'E_MAIL', width:150, halign: 'center',hidden: true},
                //     {field:'QUOT_SALE_CODE',title:'QUOT_SALE_CODE', width:150, halign: 'center',hidden: true},
                //     {field:'ACCPAC_COMPANY_CODE',title:'ACCPAC_COMPANY_CODE', width:150, halign: 'center',hidden: true},
                //     {field:'BONDED_TYPE',title:'BONDED_TYPE', width:150, halign: 'center',hidden: true},
                //     {field:'BC_DOC',title:'BC_DOC', width:150, halign: 'center',hidden: true},
                //     {field:'BC_DOC_REVERSE',title:'BC_DOC_REVERSE', width:150, halign: 'center',hidden: true}

			}
		}

		function search_item_add(){
			var s_item = document.getElementById('s_item_add').value;
		
			if(s_item != ''){
				$('#dg_addItem').datagrid('load',{item_no: s_item});
				$('#dg_addItem').datagrid({url: 'get_sp_bom_material.php',});
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
				$('#dg_addItem').datagrid({url: 'get_sp_bom_material.php',});
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

		function downloadBOM(){
			if (get_url != ''){
                console.log('company_sp_download_proses.php'+get_url);
                $.post('company_sp_download_proses.php'+get_url,{}).done(function(res){
                    download_excel();
                });
            }
            // $('#dlg_download').dialog('open').dialog('setTitle','SELECT TO DOWNLOAD');
			// $('#dg_download').datagrid({
			// 	url:'get_bom.php?sts=all',
			// 	fitColumns: true,
			// 	rownumbers: true,
			// 	columns:[[
            //         {field:'UPPER_ITEM_NO',title:'ITEM NO.',width:55, halign: 'center', align: 'center'},
			// 	    {field:'DESCRIPTION',title:'DESCRIPTION',width:220, halign: 'center'},
			// 		{field:'LEVEL_NO',title:'LEVEL_NO', width:60, halign: 'center'}
			//     ]],
			// 	onClickRow:function(row){
			// 		$(this).datagrid('beginEdit', row);
			// 	}
			// });

			// $('#dg_download').datagrid('enableFilter');
		}

        function download_excel(){
            url_download = 'company_sp_download_xls.php';
            window.open(url_download);
        }

		// function downloadBOM_select(){
		// 	var dataRows_dowload = [];
		// 	var t = $('#dg_download').datagrid('getSelections');
		// 	var total = t.length;
		// 	var jmrow=0;
		// 	for(i=0;i<total;i++){
		// 		jmrow = i+1;
		// 		$('#dg_download').datagrid('endEdit',i);
		// 		dataRows_dowload.push({
		// 			upper_item_no: $('#dg_download').datagrid('getData').rows[i].UPPER_ITEM_NO,
		// 			quantity: $('#dg_download').datagrid('getData').rows[i].LEVEL_NO
		// 		});
		// 	}

		// 	var myJSON_download=JSON.stringify(dataRows_dowload);
		// 	var str_unescape_download=unescape(myJSON_download);
			
		// 	console.log('bom_download.php?data='+str_unescape_download);

		// 	// var fs = '';//require('fs');
		// 	// fs.writeFile("bom_download.json", myJSON_download, function(err, result) {
		// 	// 	if(err) console.log('error', err);
		// 	// });

		// 	if(dataRows_dowload == '') {
		// 		$.messager.show({
		// 			title: 'BOM Download',
		// 			msg: 'Data Not Select'
		// 		});
		// 	}else{
		// 		window.open('bom_download.php?data='+str_unescape_download, '_blank');
		// 		$('#dlg_download').dialog('close');
		// 		$('#dg_download').datagrid('loadData', []); 
		// 		dataRows_dowload = [];
		// 	}
		// }
	</script>
</body>
</html>