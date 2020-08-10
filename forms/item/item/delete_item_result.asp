<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
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
 <Form Method="post" Action="delete_item_confirm.asp?KEYWORD=<%=KEYWORD%>" Name="myform">
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
%>
<%
TODAY = NOW()
IF Request.form("type") = "DELETE" THEN
 SQL_DEL = "DELETE FROM ITEM " & _
           "WHERE ITEM_NO = " & CLng(REQUEST.FORM("item_no"))
'response.write sql_DEL
'response.end
 DB.Execute(SQL_DEL)
END IF
%>
<Div Align="center">
 <Form Method="post" Action="delete_Item_Main.asp?KEYWORD=<%=KEYWORD%>" Name="goto_main" Target="_self">
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
