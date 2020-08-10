<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
'-- 2007/01/22 Sayuri Suzuki 購入単価外貨(unit_price_o)、購入単価レート(unit_price_rate)、購入単価通貨コード(unit_curr_code)を追加する。
'-- 2007/04/09 R.Tsutsui     登録項目に最終登録者(upto_person_code)を追加
'-- 2014/11/18 Y.Hagai    GRADE_CODE,CUSTOMER_TYPE,PACKAGE_TYPEの追加対応
'-- 2015/04/21 NTTk)Hino  項目追加（CAPACITY他 計28項目）
'-- 2015/07/03 NTTk)Hino  S/O Entry (GROUP ORDER)の「NEW ITEM ENTRY」から呼ばれると、エラーが発生する（パラメータがPOSTでなくGETで渡ってくる事が考慮されていない為）
'-- 2015/10/28 NTTk)Hino  項目追加（Labeling To Packaging Day他 計4項目）
'-- 2016/03/17 mazda      項目追加（CTN_GROSS_WEIGHT,PALLET_WEIGHT）PACKING_INFORMATION更新条件にPI_NO追加。

KEYWORD = Request("KEYWORD")

  call html_header("Item Entry Results",KEYWORD)
%>
<%
'---------
'DB OPEN
'---------
Set db = Server.CreateObject("ADODB.Connection")
db.Open server_name,server_user,server_psword
DB.Errors.Clear	'エラーフラグのクリア
DB.BeginTrans	'トランザクション取得開始
%>
<%
'************************
' ITEM NUMBER
'************************
ERROR_MESSAGE = ""
ITEM_NO_FLG = 0
IF Request.Form("item_no") = "" THEN
 ITEM_NO_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ITEM_NO.<Br>"
ELSE
 IF REQUEST.FORM("type") = "NEW" THEN
  IF IsNumeric(Request.Form("item_no")) THEN
   SQL_item = "SELECT * FROM ITEM " & _
              "WHERE ITEM_NO = " & CLng(Request.Form("item_no"))
   SET Rec_item = DB.Execute(SQL_item)
   IF Rec_item.eof or Rec_item.bof THEN
    ITEM_NO_FLG = 0
   ELSE
    ITEM_NO_FLG = 1
    ERROR_MESSAGE = ERROR_MESSAGE & "ITEM_NO ALREADY EXIST.<Br>"
   END IF
  END IF
 END IF %>
 <% IF ERROR_MESSAGE <> "" THEN %>
 <Div Align="center"> 
 <Form Method="post" Action="item_confirm.asp?KEYWORD=<%=KEYWORD%>" Name="myform">
 <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
 </Form>
 <Font Size="5" Color="red"><B>
 <% response.write ERROR_MESSAGE
    response.end
    END IF %>
  </B></Font>
 </Div>
<% END IF %>
<%
'-------------------------
' NULL 変換
'-------------------------
'********************
' ITEM_CODE
'********************
 IF Request.Form("item_code") = "" THEN
  W_ITEM_CODE = "NULL, "
 ELSE
  W_ITEM_CODE = "'" & Request.Form("item_code") & "', "
 END IF
'********************
' ITEM_FLAG
'********************
 IF Request.Form("item_flag") = "" THEN
  W_ITEM_FLAG = "NULL, "
 ELSE
  W_ITEM_FLAG = "'" & Request.Form("item_flag") & "', "
 END IF
'********************
' ORIGIN_CODE
'********************
 IF Request.Form("origin_code") = "" THEN
  W_ORIGIN_CODE = "NULL, "
 ELSE
  W_ORIGIN_CODE = Request.Form("origin_code") & ", "
 END IF
'********************
' DESCRIPTION
'********************
 IF Request.Form("description") = "" THEN
  W_DESCRIPTION = "NULL, "
 ELSE
  W_DESCRIPTION = "'" & Request.Form("description") & "', "
 END IF
'********************
' CLASS_CODE
'********************
 IF Request.Form("class_code") = "" THEN
  W_CLASS_CODE = "NULL, "
 ELSE
  W_CLASS_CODE = Request.Form("class_code") & ", "
 END IF
'********************
' UOM_Q
'********************
 IF Request.Form("unit_stock") = "" THEN
  W_UOM_Q = "NULL, "
 ELSE
  W_UOM_Q = Request.Form("unit_stock") & ", "
 END IF
'********************
' UOM_W
'********************
 IF Request.Form("uom_w") = "" THEN
  W_UOM_W = "NULL, "
 ELSE
  W_UOM_W = Request.Form("uom_w") & ", "
 END IF
'********************
' UOM_L
'********************
 IF Request.Form("uom_l") = "" THEN
  W_UOM_L = "NULL, "
 ELSE
  W_UOM_L = Request.Form("uom_l") & ", "
 END IF
'********************
' EXTERNAL_UNIT_NUMBER
'********************
 IF Request.Form("external_unit_number") = "" THEN
  W_EX_UNIT_NO = "NULL, "
 ELSE
  W_EX_UNIT_NO = Request.Form("external_unit_number") & ", "
 END IF
'********************
' STANDARD_PRICE
'********************
 IF Request.Form("standard_price") = "" THEN
  W_STD_PRICE = "NULL, "
 ELSE
  W_STD_PRICE = Request.Form("standard_price") & ", "
 END IF
'********************
' NEXT_TERM_PRICE
'********************
 IF Request.Form("next_term_price") = "" THEN
  W_NEXT_TERM_PRICE = "NULL, "
 ELSE
  W_NEXT_TERM_PRICE = Request.Form("next_term_price") & ", "
 END IF
'********************
' SUPPLIERS_PRICE
'********************
 IF Request.Form("suppliers_price") = "" THEN
  W_SUPPLIERS_PRICE = "NULL, "
 ELSE
  W_SUPPLIERS_PRICE = Request.Form("suppliers_price") & ", "
 END IF
'********************
' CURR_CODE
'********************
 IF Request.Form("curr_code") = "" THEN
  W_CURR_CODE = "NULL, "
 ELSE
  W_CURR_CODE = Request.Form("curr_code") & ", "
 END IF
'********************
' WEIGHT
'********************
 IF Request.Form("weight") = "" THEN
  W_WEIGHT = "NULL, "
 ELSE
  W_WEIGHT = Request.Form("weight") & ", "
 END IF
'********************
' STOCK_SUBJECT_CODE
'********************
 IF Request.Form("stock_subject_code") = "" THEN
  W_STOCK_SUBJECT_CODE = "NULL, "
 ELSE
  W_STOCK_SUBJECT_CODE = "'" & Request.Form("stock_subject_code") & "', "
 END IF
'********************
' SECTION_CODE
'********************
 IF Request.Form("section_code") = "" THEN
  W_SECTION_CODE = "NULL, "
 ELSE
  W_SECTION_CODE = Request.Form("section_code") & ", "
 END IF
'********************
' COST_SUBJECT_CODE
'********************
 IF Request.Form("cost_subject_code") = "" THEN
  W_COST_SUBJECT_CODE = "NULL, "
 ELSE
  W_COST_SUBJECT_CODE = "'" & Request.Form("cost_subject_code") & "', "
 END IF
'********************
' COST_PROCESS_CODE
'********************
 IF Request.Form("cost_process_code") = "" THEN
  W_COST_PROCESS_CODE = "NULL, "
 ELSE
  W_COST_PROCESS_CODE = "'" & Request.Form("cost_process_code") & "', "
 END IF
'********************
' MANUFACT_LEADTIME
'********************
 IF Request.Form("manufact_leadtime") = "" THEN
  W_MANUFACT_LEADTIME = "NULL, "
 ELSE
  W_MANUFACT_LEADTIME = Request.Form("manufact_leadtime") & ", "
 END IF
'********************
' PURCHASE_LEADTIME
'********************
 IF Request.Form("purchase_leadtime") = "" THEN
  W_PURCHASE_LEADTIME = "NULL, "
 ELSE
  W_PURCHASE_LEADTIME = Request.Form("purchase_leadtime") & ", "
 END IF
'********************
' ADJUSTMENT_LEADTIME
'********************
 IF Request.Form("adjustment_leadtime") = "" THEN
  W_ADJUSTMENT_LEADTIME = "NULL, "
 ELSE
  W_ADJUSTMENT_LEADTIME = Request.Form("adjustment_leadtime") & ", "
 END IF
'********************
' ISSUE_POLICY
'********************
 IF Request.Form("issue_policy") = "" THEN
  W_ISSUE_POLICY = "NULL, "
 ELSE
  W_ISSUE_POLICY = "'" & Request.Form("issue_policy") & "', "
 END IF
'*********************
' ISSUE_LOT
'*********************
 IF REQUEST.FORM("issue_lot") = "" THEN
  W_ISSUE_LOT = "NULL, "
 ELSE
  W_ISSUE_LOT = REQUEST.FORM("issue_lot") & ", "
 END IF
'********************
' MANUFACT_FAIL_RATE
'********************
 IF Request.Form("manufact_fail_rate") = "" THEN
  W_MANUFACT_FAIL_RATE = "NULL, "
 ELSE
  W_MANUFACT_FAIL_RATE = Request.Form("manufact_fail_rate") & ", "
 END IF
'********************
' MAKER_FLAG
'********************
 IF Request.Form("maker_flag") = "" THEN
  W_MAKER_FLAG = "NULL, "
 ELSE
  W_MAKER_FLAG = "'" & Request.Form("maker_flag") & "', "
 END IF
'********************
' UNIT_STOCK
'********************
 IF Request.Form("unit_stock") = "" THEN
  W_UNIT_STOCK = "NULL, "
 ELSE
  W_UNIT_STOCK = Request.Form("unit_stock") & ", "
 END IF
'********************
' UNIT_STOCK_RATE
'********************
 IF Request.Form("unit_stock_rate") = "" THEN
  W_UNIT_STOCK_RATE = "NULL, "
 ELSE
  W_UNIT_STOCK_RATE = Request.Form("unit_stock_rate") & ", "
 END IF
'********************
' DRAWING NO
'********************
 IF REQUEST.FORM("drawing_no") = "" THEN
  W_DRAWING_NO = "NULL, "
 ELSE
  W_DRAWING_NO = "'" & REQUEST.FORM("drawing_no") & "', "
 END IF
'********************
' DRAWING REV
'********************
 IF REQUEST.FORM("drawing_rev") = "" THEN
  W_DRAWING_REV = "NULL, "
 ELSE
  W_DRAWING_REV = "'" & REQUEST.FORM("drawing_rev") & "', "
 END IF
'********************
' APPLICABLE MODEL
'********************
 IF REQUEST.FORM("applicable_model") = "" THEN
  W_APPLICABLE_MODEL = "NULL, "
 ELSE
  W_APPLICABLE_MODEL = "'" & REQUEST.FORM("applicable_model") & "', "
 END IF
'********************
' CATALOG NO
'********************
 IF REQUEST.FORM("catalog_no") = "" THEN
  W_CATALOG_NO = "NULL, "
 ELSE
  W_CATALOG_NO = "'" & REQUEST.FORM("catalog_no") & "',"
 END IF
'********************
' SAFETY STOCK
'********************
 IF REQUEST.FORM("safety_stock") = "" THEN
  W_SAFETY_STOCK = "NULL, "
 ELSE
  W_SAFETY_STOCK = REQUEST.FORM("safety_stock") & ", "
 END IF
'********************
' ORDER POLICY
'********************
 IF REQUEST.FORM("order_policy") = "" THEN
  W_ORDER_POLICY = "NULL, "
 ELSE
  W_ORDER_POLICY = "'" & REQUEST.FORM("order_policy") & "', "
 END IF
'********************
' MAKER
'********************
 IF REQUEST.FORM("mak") = "" THEN
  W_MAK = "NULL, "
 ELSE
  W_MAK = "'" & REQUEST.FORM("mak") & "', "
 END IF
'********************
' UNIT_ENGINEERING
'********************
 IF Request.Form("unit_engineering") = "" THEN
  W_UNIT_ENG = "NULL, "
 ELSE
  W_UNIT_ENG = Request.Form("unit_engineering") & ", "
 END IF
'********************
' UNIT_ENGINEER_RATE
'********************
 IF Request.Form("unit_engineer_rate") = "" THEN
  W_UNIT_ENG_RATE = "NULL, "
 ELSE
  W_UNIT_ENG_RATE = Request.Form("unit_engineer_rate") & ", "
 END IF
'********************
' ITEM_TYPE1
'********************
 IF Request.Form("item_type1") = "" THEN
  W_ITEM_TYPE1 = "NULL, "
 ELSE
  W_ITEM_TYPE1 = "'" & Request.Form("item_type1") & "', "
 END IF
'********************
' ITEM_TYPE2
'********************
 IF Request.Form("item_type2") = "" THEN
  W_ITEM_TYPE2 = "NULL "
 ELSE
  W_ITEM_TYPE2 = "'" & Request.Form("item_type2") & "' "
 END IF

'add 2015/04/21 Start
'Null変換
Function SET_NULL(p_REQ_NAME)
  If Request.Form(p_REQ_NAME) = "" Then
    SET_NULL = "NULL"
  Else
    SET_NULL = Request.Form(p_REQ_NAME)
  End If
End Function
'add 2015/04/21 End
%>
<%
TODAY = NOW()
IF Request.form("type") = "NEW" THEN
 SQL_ins = "INSERT INTO ITEM ( " & _
           "UPTO_DATE, " & _
           "REG_DATE, " & _
           "ITEM_NO, " & _
           "ITEM_CODE, " & _
           "ITEM, " & _
           "ITEM_FLAG, " & _
           "ORIGIN_CODE, " & _
           "DESCRIPTION, " & _
           "CLASS_CODE, " & _
           "UOM_Q, " & _
           "UOM_W, " & _
           "UOM_L, " & _
           "EXTERNAL_UNIT_NUMBER, " & _
           "STANDARD_PRICE, " & _
           "NEXT_TERM_PRICE, " & _
           "SUPPLIERS_PRICE, " & _
           "CURR_CODE, " & _
           "WEIGHT, " & _
           "STOCK_SUBJECT_CODE, " & _
           "SECTION_CODE, " & _
           "COST_SUBJECT_CODE, " & _
           "COST_PROCESS_CODE, " & _
           "MANUFACT_LEADTIME, " & _
           "PURCHASE_LEADTIME, " & _
           "ADJUSTMENT_LEADTIME, " & _
           "LEVEL_CONT_KEY, " & _
           "ISSUE_POLICY, " & _
           "ISSUE_LOT, " & _
           "MANUFACT_FAIL_RATE, " & _
           "MAKER_FLAG, " & _
           "UNIT_STOCK, " & _
           "UNIT_STOCK_RATE, " & _
           "DRAWING_NO, " & _
           "DRAWING_REV, " & _
           "APPLICABLE_MODEL, " & _
           "CATALOG_NO, " & _
           "SAFETY_STOCK, " & _
           "ORDER_POLICY, " & _
           "MAK, " & _
           "UNIT_ENGINEERING, " & _
           "UNIT_ENGINEER_RATE, " & _
           "ITEM_TYPE1, " & _
           "ITEM_TYPE2," & _
           "PACKAGE_UNIT_NUMBER," & _
           "UNIT_PACKAGE " & _
           ",UNIT_PRICE_O " & _
           ",UNIT_PRICE_RATE " & _
           ",UNIT_CURR_CODE " & _
           ",UPTO_PERSON_CODE " & _
           ",GRADE_CODE " & _
           ",CUSTOMER_TYPE " & _
           ",PACKAGE_TYPE " & _
           ",CAPACITY " & _
           ",DATE_CODE_TYPE " & _
           ",DATE_CODE_MONTH " & _
           ",LABEL_TYPE " & _
           ",INNER_BOX_UNIT_NUMBER " & _
           ",INNER_BOX_HEIGHT " & _
           ",INNER_BOX_WIDTH " & _
           ",INNER_BOX_DEPTH " & _
           ",MEDIUM_BOX_UNIT_NUMBER " & _
           ",MEDIUM_BOX_HEIGHT " & _
           ",MEDIUM_BOX_WIDTH " & _
           ",MEDIUM_BOX_DEPTH " & _
           ",OUTER_BOX_HEIGHT " & _
           ",OUTER_BOX_WIDTH " & _
           ",OUTER_BOX_DEPTH " & _
           ",MEASUREMENT " & _
           ",PI_NO " & _
           ",OPERATION_TIME " & _
           ",MAN_POWER " & _
           ",AGING_DAY " & _
           ",LABELING_TO_PACK_DAY " & _
           ",ASSEMBLING_TO_LAB_DAY " & _
           ",CUSTOMER_ITEM_NO " & _
           ",CUSTOMER_ITEM_NAME " & _
           ",CTN_GROSS_WEIGHT " & _
           ") VALUES ( " & _
           "SYSDATE, " & _
           "SYSDATE, " & _
           CLng(Request.form("item_no")) & ", " & _
           W_ITEM_CODE & _
           "'" & Request.form("item") & "', " & _
           W_ITEM_FLAG & _
           W_ORIGIN_CODE & _
           W_DESCRIPTION & _
           W_CLASS_CODE & _
           W_UOM_Q & _
           W_UOM_W & _
           W_UOM_L & _
           W_EX_UNIT_NO & _
           W_STD_PRICE & _
           W_NEXT_TERM_PRICE & _
           W_SUPPLIERS_PRICE & _
           W_CURR_CODE & _
           W_WEIGHT & _
           W_STOCK_SUBJECT_CODE & _
           W_SECTION_CODE & _
           W_COST_SUBJECT_CODE & _
           W_COST_PROCESS_CODE & _
           W_MANUFACT_LEADTIME & _
           W_PURCHASE_LEADTIME & _
           W_ADJUSTMENT_LEADTIME & _
           CLng(Request.form("item_no")) & ", " & _
           W_ISSUE_POLICY & _
           W_ISSUE_LOT & _
           W_MANUFACT_FAIL_RATE & _
           W_MAKER_FLAG & _
           W_UNIT_STOCK & _
           W_UNIT_STOCK_RATE & _
           W_DRAWING_NO & _
           W_DRAWING_REV & _
           W_APPLICABLE_MODEL & _
           W_CATALOG_NO & _
           W_SAFETY_STOCK & _
           W_ORDER_POLICY & _
           W_MAK & _
           W_UNIT_ENG & _
           W_UNIT_ENG_RATE & _
           W_ITEM_TYPE1 & _
           W_ITEM_TYPE2 & _
           "," & _
           "'" & Request("package_unit_number") & "'," & _
           "'" & Request("unit_package")        & "' " & _
          ",'" & Request("unit_price_o") & "'" & _
          ",'" & Request("unit_price_rate") & "'" & _
          ",'" & Request("unit_curr_code") & "'" & _
          ",'" & KEYWORD & "'" & _
          ",'" & Request("grade_code") & "'" & _
          ",'" & Request("customer_type") & "'" & _
          ",'" & Request("package_type") & "'" & _
          "," & SET_NULL("capacity") & _
          ",'" & Request("date_code_type") & "'" & _
          "," & SET_NULL("date_code_month") & _
          ",'" & Request("label_type") & "'" & _
          "," & SET_NULL("i_unit_number") & _
          "," & SET_NULL("i_height") & _
          "," & SET_NULL("i_width") & _
          "," & SET_NULL("i_depth") & _
          "," & SET_NULL("m_unit_number") & _
          "," & SET_NULL("m_height") & _
          "," & SET_NULL("m_width") & _
          "," & SET_NULL("m_depth") & _
          "," & SET_NULL("o_height") & _
          "," & SET_NULL("o_width") & _
          "," & SET_NULL("o_depth") & _
          "," & SET_NULL("measurement") & _
          ",'" & Request("packing_information") & "'" & _
          "," & SET_NULL("operation_time") & _
          "," & SET_NULL("man_power") & _
          "," & SET_NULL("aging_day") & _
          "," & SET_NULL("lab_to_pack_day") & _
          "," & SET_NULL("assembling_to_lab_day") & _
          ",'" & Request("cust_item_no") & "'" & _
          ",'" & Request("cust_item_name") & "'" & _
          "," & SET_NULL("c_gross_weight") & _
           ")"
'response.write sql_ins
'response.end
 DB.Execute(SQL_ins)
 
'add 2015/04/21 Start
'------------------------------------------------
' PACKING_INFORMATION更新 (Delete & Insert)
'------------------------------------------------
 If Request("packing_information") <> "" Then

   SQL_del = "DELETE FROM PACKING_INFORMATION " & _
             "WHERE  PI_NO = '" & Request("packing_information") & "'"
   DB.Execute(SQL_del)
 
   SQL_ins = "INSERT INTO PACKING_INFORMATION ( " & _
               "PI_NO, " & _
               "PLT_SPEC_NO, " & _
               "PALLET_UNIT_NUMBER, " & _
               "PALLET_CTN_NUMBER, " & _
               "PALLET_STEP_CTN_NUMBER, " & _
               "PALLET_HEIGHT, " & _
               "PALLET_WIDTH, " & _
               "PALLET_DEPTH, " & _
               "PALLET_SIZE_TYPE, " & _
               "PALLET_WEIGHT " & _
             ") VALUES ( " & _
               "'" & Request("packing_information") & "'," & _
               "'" & Request("plt_spec_no") & "'," & _
               SET_NULL("p_unit_number") & "," & _
               SET_NULL("p_ctn_number") & "," & _
               SET_NULL("p_step_ctn_number") & "," & _
               SET_NULL("p_height") & "," & _
               SET_NULL("p_width") & "," & _
               SET_NULL("p_depth") & "," & _
               SET_NULL("pallet_size_type") & "," & _
               SET_NULL("p_weight") & _
             ")"
   DB.Execute(SQL_ins)
 End If
'add 2015/04/21 End

	If (Session("NGM") = "1") Then
		If CStr(REQUEST("ngmc")) = "on" Then 
			SQL_ins = "INSERT INTO ITEM (	" & _
				"UPTO_DATE,		" & _
				"REG_DATE,		" & _
				"ITEM_NO,		" & _
				"ITEM_CODE,		" & _
				"ITEM,			" & _
				"ITEM_FLAG,		" & _
				"ORIGIN_CODE,		" & _
				"DESCRIPTION,		" & _
				"CLASS_CODE,		" & _
				"UOM_Q,			" & _
				"UOM_W,			" & _
				"UOM_L,			" & _
				"EXTERNAL_UNIT_NUMBER,	" & _
				"STANDARD_PRICE,	" & _
				"NEXT_TERM_PRICE,	" & _
				"SUPPLIERS_PRICE,	" & _
				"CURR_CODE,		" & _
				"WEIGHT,		" & _
				"STOCK_SUBJECT_CODE,	" & _
				"SECTION_CODE,		" & _
				"COST_SUBJECT_CODE,	" & _
				"COST_PROCESS_CODE,	" & _
				"MANUFACT_LEADTIME,	" & _
				"PURCHASE_LEADTIME,	" & _
				"ADJUSTMENT_LEADTIME,	" & _
				"LEVEL_CONT_KEY,	" & _
				"ISSUE_POLICY,		" & _
				"ISSUE_LOT,		" & _
				"MANUFACT_FAIL_RATE,	" & _
				"MAKER_FLAG,		" & _
				"UNIT_STOCK,		" & _
				"UNIT_STOCK_RATE,	" & _
				"DRAWING_NO,		" & _
				"DRAWING_REV,		" & _
				"APPLICABLE_MODEL,	" & _
				"CATALOG_NO,		" & _
				"SAFETY_STOCK,		" & _
				"ORDER_POLICY,		" & _
				"MAK,			" & _
				"UNIT_ENGINEERING,	" & _
				"UNIT_ENGINEER_RATE,	" & _
				"ITEM_TYPE1,		" & _
				"ITEM_TYPE2,		" & _
				"PACKAGE_UNIT_NUMBER," & _
				"UNIT_PACKAGE "        & _
				",UNIT_PRICE_O " & _
				",UNIT_PRICE_RATE " & _
				",UNIT_CURR_CODE " & _
				",UPTO_PERSON_CODE " & _
				",GRADE_CODE " & _
				",CUSTOMER_TYPE " & _
				",PACKAGE_TYPE " & _
				",CAPACITY " & _
				",DATE_CODE_TYPE " & _
				",DATE_CODE_MONTH " & _
				",LABEL_TYPE " & _
				",INNER_BOX_UNIT_NUMBER " & _
				",INNER_BOX_HEIGHT " & _
				",INNER_BOX_WIDTH " & _
				",INNER_BOX_DEPTH " & _
				",MEDIUM_BOX_UNIT_NUMBER " & _
				",MEDIUM_BOX_HEIGHT " & _
				",MEDIUM_BOX_WIDTH " & _
				",MEDIUM_BOX_DEPTH " & _
				",OUTER_BOX_HEIGHT " & _
				",OUTER_BOX_WIDTH " & _
				",OUTER_BOX_DEPTH " & _
				",MEASUREMENT " & _
				",PI_NO " & _
				",OPERATION_TIME " & _
				",MAN_POWER " & _
				",AGING_DAY " & _
				",LABELING_TO_PACK_DAY " & _
				",ASSEMBLING_TO_LAB_DAY " & _
				",CUSTOMER_ITEM_NO " & _
				",CUSTOMER_ITEM_NAME " & _
				",CTN_GROSS_WEIGHT " & _
				") VALUES (		" & _
				"SYSDATE,		" & _
				"SYSDATE,		" & _
				CLng(Request.form("item_no")) + 90000000 & ", " & _
				W_ITEM_CODE				& _
				"'" & Request.form("item") & "', "	& _
				W_ITEM_FLAG				& _
				W_ORIGIN_CODE				& _
				W_DESCRIPTION				& _
				W_CLASS_CODE				& _
				W_UOM_Q					& _
				W_UOM_W					& _
				W_UOM_L					& _
				W_EX_UNIT_NO				& _
				W_STD_PRICE				& _
				W_NEXT_TERM_PRICE			& _
				W_SUPPLIERS_PRICE			& _
				W_CURR_CODE				& _
				W_WEIGHT				& _
				"7,"					& _
				W_SECTION_CODE				& _
				W_COST_SUBJECT_CODE			& _
				W_COST_PROCESS_CODE			& _
				W_MANUFACT_LEADTIME			& _
				W_PURCHASE_LEADTIME			& _
				W_ADJUSTMENT_LEADTIME			& _
				CLng(Request.form("item_no")) + 90000000  & ", " & _
				W_ISSUE_POLICY				& _
				W_ISSUE_LOT				& _
				W_MANUFACT_FAIL_RATE			& _
				W_MAKER_FLAG				& _
				W_UNIT_STOCK				& _
				W_UNIT_STOCK_RATE			& _
				W_DRAWING_NO				& _
				W_DRAWING_REV				& _
				W_APPLICABLE_MODEL			& _
				W_CATALOG_NO				& _
				W_SAFETY_STOCK				& _
				W_ORDER_POLICY				& _
				W_MAK					& _
				W_UNIT_ENG				& _
				W_UNIT_ENG_RATE				& _
				W_ITEM_TYPE1				& _
				W_ITEM_TYPE2				& _
				"," & _
				"'" & Request("package_unit_number") & "'," & _
				"'" & Request("unit_package")        & "' " & _
				",'" & Request("unit_price_o") & "'" & _
				",'" & Request("unit_price_rate") & "'" & _
				",'" & Request("unit_curr_code") & "'" & _
				",'" & KEYWORD & "'" & _
				",'" & Request("grade_code") & "'" & _
				",'" & Request("customer_type") & "'" & _
				",'" & Request("package_type") & "'" & _
				"," & SET_NULL("capacity") & _
				",'" & Request("date_code_type") & "'"  & _
				"," & SET_NULL("date_code_month") & _
				",'" & Request("label_type") & "'" & _
				"," & SET_NULL("i_unit_number") & _
				"," & SET_NULL("i_height") & _
				"," & SET_NULL("i_width") & _
				"," & SET_NULL("i_depth") & _
				"," & SET_NULL("m_unit_number") & _
				"," & SET_NULL("m_height") & _
				"," & SET_NULL("m_width") & _
				"," & SET_NULL("m_depth") & _
				"," & SET_NULL("o_height") & _
				"," & SET_NULL("o_width") & _
				"," & SET_NULL("o_depth") & _
				"," & SET_NULL("measurement") & _
				",'" & Request("packing_information") & "'" & _
				"," & SET_NULL("operation_time") & _
				"," & SET_NULL("man_power") & _
				"," & SET_NULL("aging_day") & _
				"," & SET_NULL("lab_to_pack_day") & _
				"," & SET_NULL("assembling_to_lab_day") & _
				",'" & Request("cust_item_no") & "'" & _
				",'" & Request("cust_item_name") & "'" & _
				"," & SET_NULL("c_gross_weight") & _
				")"
				'response.write sql_ins
				'response.end
				DB.Execute(SQL_ins)
		End If
	End If
ELSEIF Request.form("type") = "CHANGE" THEN
 SQL_upd = "UPDATE ITEM SET " & _
           "UPTO_DATE = SYSDATE, " & _
           "ITEM_NO = " & CLng(Request.form("item_no")) & ", " & _
           "ITEM_CODE = " & W_ITEM_CODE & _
           "ITEM = " & "'" & Request.form("item") & "', " & _
           "ITEM_FLAG = " & W_ITEM_FLAG & _
           "ORIGIN_CODE = " & W_ORIGIN_CODE & _
           "DESCRIPTION = " & W_DESCRIPTION & _
           "CLASS_CODE = " & W_CLASS_CODE & _
           "UOM_Q = " & W_UOM_Q & _
           "UOM_W = " & W_UOM_W & _
           "UOM_L = " & W_UOM_L & _
           "EXTERNAL_UNIT_NUMBER = " & W_EX_UNIT_NO & _
           "STANDARD_PRICE = " & W_STD_PRICE & _
           "NEXT_TERM_PRICE = " & W_NEXT_TERM_PRICE & _
           "SUPPLIERS_PRICE = " & W_SUPPLIERS_PRICE & _
           "CURR_CODE = " & W_CURR_CODE & _
           "WEIGHT = " & W_WEIGHT & _
           "STOCK_SUBJECT_CODE = " & W_STOCK_SUBJECT_CODE & _
           "SECTION_CODE = " & W_SECTION_CODE & _
           "COST_SUBJECT_CODE = " & W_COST_SUBJECT_CODE & _
           "COST_PROCESS_CODE = " & W_COST_PROCESS_CODE & _
           "MANUFACT_LEADTIME = " & W_MANUFACT_LEADTIME & _
           "PURCHASE_LEADTIME = " & W_PURCHASE_LEADTIME & _
           "ADJUSTMENT_LEADTIME = " & W_ADJUSTMENT_LEADTIME & _
           "ISSUE_POLICY = " & W_ISSUE_POLICY & _
           "ISSUE_LOT = " & W_ISSUE_LOT & _
           "MANUFACT_FAIL_RATE = " & W_MANUFACT_FAIL_RATE & _
           "MAKER_FLAG = " & W_MAKER_FLAG & _
           "UNIT_STOCK = " & W_UNIT_STOCK & _
           "UNIT_STOCK_RATE = " & W_UNIT_STOCK_RATE & _
           "DRAWING_NO = " & W_DRAWING_NO & _
           "DRAWING_REV = " & W_DRAWING_REV & _
           "APPLICABLE_MODEL = " & W_APPLICABLE_MODEL & _
           "CATALOG_NO = " & W_CATALOG_NO & _
           "SAFETY_STOCK = " & W_SAFETY_STOCK & _
           "ORDER_POLICY = " & W_ORDER_POLICY & _
           "MAK = " & W_MAK & _
           "UNIT_ENGINEERING = " & W_UNIT_ENG & _
           "UNIT_ENGINEER_RATE = " & W_UNIT_ENG_RATE & _
           "ITEM_TYPE1 = " & W_ITEM_TYPE1 & _
           "ITEM_TYPE2 = " & W_ITEM_TYPE2 & _
           "," & _
           "PACKAGE_UNIT_NUMBER = '" & Request("package_unit_number") & "'," & _
           "UNIT_PACKAGE        = '" & Request("unit_package")        & "' " & _
           ",UNIT_PRICE_O    = '" & Request("unit_price_o") & "'" & _
           ",UNIT_PRICE_RATE = '" & Request("unit_price_rate") & "'" & _
           ",UNIT_CURR_CODE  = '" & Request("unit_curr_code") & "'" & _
           ",UPTO_PERSON_CODE  = '" & KEYWORD & "'" & _
           ",GRADE_CODE  =    '" & Request("grade_code") & "'" & _
           ",CUSTOMER_TYPE  = '" & Request("customer_type") & "'" & _
           ",PACKAGE_TYPE  =  '" & Request("package_type") & "'" & _
           ",CAPACITY               =  " & SET_NULL("capacity") & _
           ",DATE_CODE_TYPE         =  '" & Request("date_code_type") & "'" & _
           ",DATE_CODE_MONTH        =  " & SET_NULL("date_code_month") & _
           ",LABEL_TYPE             =  '" & Request("label_type") & "'" & _
           ",INNER_BOX_UNIT_NUMBER  =  " & SET_NULL("i_unit_number") & _
           ",INNER_BOX_HEIGHT       =  " & SET_NULL("i_height") & _
           ",INNER_BOX_WIDTH        =  " & SET_NULL("i_width") & _
           ",INNER_BOX_DEPTH        =  " & SET_NULL("i_depth") & _
           ",MEDIUM_BOX_UNIT_NUMBER =  " & SET_NULL("m_unit_number") & _
           ",MEDIUM_BOX_HEIGHT      =  " & SET_NULL("m_height") & _
           ",MEDIUM_BOX_WIDTH       =  " & SET_NULL("m_width") & _
           ",MEDIUM_BOX_DEPTH       =  " & SET_NULL("m_depth") & _
           ",OUTER_BOX_HEIGHT       =  " & SET_NULL("o_height") & _
           ",OUTER_BOX_WIDTH        =  " & SET_NULL("o_width") & _
           ",OUTER_BOX_DEPTH        =  " & SET_NULL("o_depth") & _
           ",MEASUREMENT            =  " & SET_NULL("measurement") & _
           ",PI_NO                  =  '" & Request("packing_information") & "'" & _
           ",OPERATION_TIME         =  " & SET_NULL("operation_time")  & _
           ",MAN_POWER              =  " & SET_NULL("man_power") & _
           ",AGING_DAY              =  " & SET_NULL("aging_day") & _
           ",LABELING_TO_PACK_DAY   =  " & SET_NULL("lab_to_pack_day") & _
           ",ASSEMBLING_TO_LAB_DAY  =  " & SET_NULL("assembling_to_lab_day") & _
           ",CUSTOMER_ITEM_NO       =  '" & Request("cust_item_no") & "'"  & _
           ",CUSTOMER_ITEM_NAME     =  '" & Request("cust_item_name") & "'"  & _
           ",CTN_GROSS_WEIGHT       =  " & SET_NULL("c_gross_weight") & _
           " WHERE ITEM_NO = " & CLng(REQUEST.FORM("item_no"))
'response.write sql_upd
'response.end
 DB.Execute(SQL_upd)
 
'add 2015/04/21 Start
'------------------------------------------------
' PACKING_INFORMATION更新 (Delete & Insert)
'------------------------------------------------
 If Request("packing_information") <> "" Then
   SQL_del = "DELETE FROM PACKING_INFORMATION " & _
             "WHERE  PI_NO = '" & Request("packing_information") & "'"
   DB.Execute(SQL_del)

   SQL_ins = "INSERT INTO PACKING_INFORMATION ( " & _
               "PI_NO, " & _
               "PLT_SPEC_NO, " & _
               "PALLET_UNIT_NUMBER, " & _
               "PALLET_CTN_NUMBER, " & _
               "PALLET_STEP_CTN_NUMBER, " & _
               "PALLET_HEIGHT, " & _
               "PALLET_WIDTH, " & _
               "PALLET_DEPTH, " & _
               "PALLET_SIZE_TYPE, " & _
               "PALLET_WEIGHT " & _
             ") VALUES ( " & _
               "'" & Request("packing_information") & "'," & _
               "'" & Request("plt_spec_no") & "'," & _
               SET_NULL("p_unit_number") & "," & _
               SET_NULL("p_ctn_number") & "," & _
               SET_NULL("p_step_ctn_number") & "," & _
               SET_NULL("p_height") & "," & _
               SET_NULL("p_width") & "," & _
               SET_NULL("p_depth") & "," & _
               SET_NULL("pallet_size_type") & "," & _
               SET_NULL("p_weight") & _
             ")"
   DB.Execute(SQL_ins)
   'add 2015/04/21 End
 End If

ELSE
 SQL_DEL = "UPDATE ITEM SET " & _
           "UPTO_DATE = SYSDATE, " & _
           "DELETE_TYPE = 'D'," & _
           "UPTO_PERSON_CODE = '" & KEYWORD & "' " & _
           " WHERE ITEM_NO = " & CLng(REQUEST.FORM("item_no"))
'response.write sql_DEL
'response.end
 DB.Execute(SQL_DEL)
END IF
%>
<Div Align="center">
 <Form Method="post" Action="Item_Main.asp?KEYWORD=<%=KEYWORD%>" Name="goto_main" Target="_self">
<%
'//* 更新状況チェック *//
If DB.Errors.Count > 0 Then
	DB.RollbackTrans %>
 <% IF REQUEST.FORM("type") = "NEW" THEN %>
  <H3>INSERT ERROR</H3><Br>
 <% ELSEIF REQUEST.FORM("type") = "CHANGE" THEN %>
  <H3>UPDATE ERROR</H3><Br>
 <% ELSE %>
  <H3>DELETE ERROR</H3><Br>
 <% END IF %>
 <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
<% Else
	DB.CommitTrans %>
 <% IF REQUEST.FORM("type") = "NEW" THEN %>
  <H3>NEW DATA ENTRIED</H3><Br>
 <% ELSEIF REQUEST.FORM("type") = "CHANGE" THEN %>
  <H3>DATA UPDATED</H3><Br>
 <% ELSE %>
  <H3>DATA SET DELETE_TYPE</H3><Br>
 <% END IF %>
<% End If %>
<% IF REQUEST("GRP_FLG") = " " THEN %>
 <Input Type="submit" Name="goto_main_button" Value="Go to Item Main"><Br>
 </Form>
<% ELSEIF REQUEST("GRP_FLG") = "ORDER" THEN %>
 <Form Method="post" Action="entry/so/grp_order2.cgi?KEYWORD=<%=KEYWORD%>" Name="return_order" Target="_self">
 <Input Type="hidden" Name="customer_code" Value="<%= REQUEST("CUSTOMER_CODE") %>">
 <Input Type="hidden" Name="customer_po_no" Value="<%= REQUEST("CUSTOMER_PO_NO") %>">
<!-- <Input Type="submit" Name="re_order_button" Value="RETURN TO GROUP ORDER INPUT"> -->
 <Input Type="Button" Name="re_order_button" Value="RETURN TO GROUP ORDER INPUT" onClick='opener.location.replace("../../entry/so/grp_order2.cgi?KEYWORD=<%=KEYWORD%>&customer_code=<%=REQUEST("CUSTOMER_CODE")%>&customer_po_no=<%=REQUEST("CUSTOMER_PO_NO")%>") ; self.close()'>
 </Form>
<% ELSEIF REQUEST("GRP_FLG") = "FORECAST" THEN %>
 <Form Method="post" Action="entry/fst/grp_fst2.cgi?KEYWORD=<%=KEYWORD%>" Name="return_forecast" Target="_self">
 <Input Type="hidden" Name="customer_code" Value="<%= REQUEST("CUSTOMER_CODE") %>">
 <Input Type="Button" Name="re_forecast_button" Value="RETURN TO GROUP FORECAST INPUT"  onClick='opener.location.replace("../../entry/fst/grp_fst2.cgi?KEYWORD=<%=KEYWORD%>&customer_code=<%=REQUEST("CUSTOMER_CODE")%>&item_no=<%=REQUEST("item_no")%>") ; self.close()'>
 </Form>
<% END IF %>
</Div>
</Body>
</Html>
<% DB.Close %>
