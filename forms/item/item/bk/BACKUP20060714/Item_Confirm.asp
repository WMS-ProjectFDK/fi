<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
KEYWORD = Request("KEYWORD")


  call html_header("Item Entry Confirmation",KEYWORD)
%>
<%
'---------
'DB OPEN
'---------
Set db = Server.CreateObject("ADODB.Connection")
db.Open server_name,server_user,server_psword
%>
<%
'-----------------------
' 小数以下桁数チェック
'-----------------------
Sub DECIMAL_CHECK(I_NUMBER, L_NUMBER, CHK_CODE)
 STR_I_NUMBER = CStr(I_NUMBER)
 STR_SPLIT = SPLIT(STR_I_NUMBER, ".")
 IF UBound(STR_SPLIT) > 0 THEN
  IF Len(STR_SPLIT(1)) <= L_NUMBER THEN
   CHK_CODE = "0"
  ELSE
   CHK_CODE = "1"
  END IF
 ELSE
  CHK_CODE = "0"
 END IF
End Sub
%>
<%
'----------------------
' POST DATA CHECK
'----------------------
'*****************
' CHECK FLAG
'*****************
ERROR_MESSAGE = ""
ITEM_NO_FLG = 0
ITEM_FLG = 0
ITEM_FLAG_FLG = 0
ORIGIN_CODE_FLG = 0
CLASS_CODE_FLG = 0
'UOM_Q_FLG = 0
UOM_W_FLG =0
UOM_L_FLG = 0
EX_UNIT_NO_FLG = 0
STD_PRICE_FLG = 0
NEXT_TERM_PRICE_FLG = 0
SUPPLIERS_PRICE_FLG = 0
CURR_CODE_FLG = 0
WEIGHT_FLG = 0
STOCK_SUBJECT_FLG = 0
COST_SUBJECT_FLG = 0
COST_PROCESS_FLG = 0
MANU_LEAD_TIME_FLG = 0
PUR_LEAD_TIME_FLG = 0
ADJ_LEAD_TIME_FLG = 0
SECTION_CODE_FLG = 0
MANU_FAIL_RATE_FLG = 0
MAKER_FLAG_FLG = 0
UNIT_STOCK_FLG = 0
UNIT_STOCK_R_FLG = 0
ISSUE_LOT_FLG = 0
SAFETY_STOCK_FLG = 0
UNIT_ENG_FLG = 0
UNIT_ENG_RATE_FLG = 0
'************************
' ITEM NUMBER
'************************
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
  ELSE
   ITEM_NO_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ITEM_NO ONLY NUMBER.<Br>"
  END IF
 END IF
END IF
'******************
' ITEM
'******************
IF REQUEST.FORM("item") = "" THEN
 ITEM_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ITEM_NAME.<Br>"
END IF
'******************
' ITEM_FLAG
'******************
IF REQUEST.FORM("item_flag") = "" THEN
 ITEM_FLAG_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT ITEM_FLAG.<Br>"
ELSE
 SQL_ITEMFLAG = "SELECT FLAG_NAME " & _
                "  FROM ITEMFLAG " & _
                " WHERE ITEM_FLAG = '" & CStr(REQUEST.FORM("ITEM_FLAG")) & "' "
 SET REC_ITEMFLAG = DB.EXECUTE(SQL_ITEMFLAG)
 ITEM_FLAG = REC_ITEMFLAG.FIELDS("FLAG_NAME")
END IF
'*********************
' ORIGIN(COUNTRY CODE) GET
'*********************
IF REQUEST.FORM("origin_code") = "" THEN
 ORIGIN_CODE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT ORIGIN.<Br>"
ELSE
  SQL_origin = "SELECT  COUNTRY " & _
 	" FROM COUNTRY " & _
 	" WHERE DELETE_TYPE IS NULL AND " & _
 	"       COUNTRY_CODE = " & CLng(Request.form("origin_code")) & " "
  Set Rec_origin = DB.Execute(SQL_origin)
   ORIGIN =  Rec_origin.Fields("COUNTRY")
END IF
'*********************
' CLASS CODE GET
'*********************
IF REQUEST.FORM("class_code") = "" THEN
 CLASS_CODE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT CLASS.<Br>"
ELSE
  SQL_class = "SELECT  CLASS_1||CLASS_2||CLASS_3 AS CLASS " & _
 	" FROM CLASS " & _
 	" WHERE CLASS_CODE = " & CLng(Request.form("class_code")) & " "
  Set Rec_class = DB.Execute(SQL_class)
   CLASS1 = Rec_class.Fields("CLASS")
END IF
'*********************
' UNIT OF QUANTITY GET
'*********************
'IF REQUEST.FORM("uom_q") = "" THEN
' UOM_Q_FLG = 1
' ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT OF QTY.<Br>"
'ELSE
' SQL_uoq = "SELECT  UNIT " & _
' 	 " FROM UNIT " & _
' 	 " WHERE DELETE_TYPE IS NULL AND" & _
' 	 "       UNIT_CODE = " & CLng(Request.form("uom_q")) & " "
' Set Rec_uoq = DB.Execute(SQL_uoq)
'  UNIT_Q =  Rec_uoq.Fields("UNIT")
'END IF
'*********************
' UNIT OF WEIGHT GET
'*********************
IF REQUEST.FORM("uom_w") = "" THEN
 UOM_W_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT OF WEIGHT.<Br>"
ELSE
 SQL_uow = "SELECT  UNIT " & _
 	 " FROM UNIT " & _
 	 " WHERE DELETE_TYPE IS NULL AND" & _
 	 "       UNIT_CODE = " & CLng(Request.form("uom_w")) & " "
 Set Rec_uow = DB.Execute(SQL_uow)
  UNIT_W =  Rec_uow.Fields("UNIT")
END IF
'*********************
' UNIT OF LENGTH GET
'*********************
IF REQUEST.FORM("uom_l") = "" THEN
 UOM_L_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT OF LENGTH.<Br>"
ELSE
 SQL_uol = "SELECT  UNIT " & _
 	 " FROM UNIT " & _
 	 " WHERE DELETE_TYPE IS NULL AND" & _
 	 "       UNIT_CODE = " & CLng(Request.form("uom_l")) & " "
 Set Rec_uol = DB.Execute(SQL_uol)
  UNIT_L =  Rec_uol.Fields("UNIT")
END IF
'************************************
' EXTERNAL UNIT NUMBER
'************************************
IF Request.Form("external_unit_number") <> "" THEN
 IF IsNumeric(Request.Form("external_unit_number")) THEN
   EX_UNIT_NO_FLG = 0
 ELSE
  EX_UNIT_NO_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT EXTERNAL_UNIT_NUMBER ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' STANDARD PRICE
'************************************
IF REQUEST.FORM("standard_price") = "" THEN
 STD_PRICE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT STANDARD PRICE.<Br>"
ELSE
 IF IsNumeric(Request.Form("standard_price")) THEN
  CALL DECIMAL_CHECK(REQUEST.FORM("standard_price"), 4, RESULT)
  IF RESULT = "0" THEN
   STD_PRICE_FLG = 0
  ELSE
   STD_PRICE_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF STANDARD_PRICE IS 4.<Br>"
  END IF
 ELSE
  STD_PRICE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT STANDARD_PRICE ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' NEXT TERM PRICE
'************************************
IF Request.Form("next_term_price") <> "" THEN
 IF IsNumeric(Request.Form("next_term_price")) THEN
  CALL DECIMAL_CHECK(REQUEST.FORM("next_term_price"), 4, RESULT)
  IF RESULT = "0" THEN
   NEXT_TERM_PRICE_FLG = 0
  ELSE
   NEXT_TERM_PRICE_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF NEXT_TERM_PRICE IS 4.<Br>"
  END IF
 ELSE
  NEXT_TERM_PRICE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT NEXT_TERM_PRICE ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' SUPPLIERS PRICE
'************************************
IF Request.Form("suppliers_price") <> "" THEN
 IF IsNumeric(Request.Form("suppliers_price")) THEN
  CALL DECIMAL_CHECK(REQUEST.FORM("suppliers_price"), 4, RESULT)
  IF RESULT = "0" THEN
   SUPPLIERS_PRICE_FLG = 0
  ELSE
   SUPPLIERS_PRICE_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF SUPPLIERS_PRICE IS 4.<Br>"
  END IF
 ELSE
  SUPPLIERS_PRICE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT SUPPLIERS_PRICE ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' CURRENCY CODE
'************************************
IF REQUEST.FORM("curr_code") = "" THEN
 CURR_CODE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT CURRENCY MARK.<Br>"
ELSE
 SQL_currency = "SELECT  CURR_MARK " & _
	 " FROM CURRENCY " & _
	 " WHERE DELETE_TYPE IS NULL AND " & _
	 "       CURR_CODE = " & CLng(Request.Form("curr_code")) & " "
 Set Rec_currency = DB.Execute(SQL_currency)
  CURR_MARK =  Rec_currency.Fields("CURR_MARK")
END IF
'************************************
' WEIGHT
'************************************
IF Request.Form("weight") <> "" THEN
 IF IsNumeric(Request.Form("weight")) THEN
  CALL DECIMAL_CHECK(REQUEST.FORM("weight"), 5, RESULT)
  IF RESULT = "0" THEN
   WEIGHT_FLG = 0
  ELSE
   WEIGHT_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "DECIMAL LENGTH OF WEIGHT IS 5.<Br>"
  END IF
 ELSE
  WEIGHT_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT WEIGHT ONLY NUMBER.<Br>"
 END IF
END IF
'*********************
' STOCK SUBJECT CODE GET
'*********************
IF REQUEST.FORM("stock_subject_code") = "" THEN
 STOCK_SUBJECT_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT STOCK SUBJECT.<Br>"
ELSE
 SQL_stock_subject = "SELECT  STOCK_SUBJECT " & _
 	 " FROM STOCK_SUBJECT " & _
 	 " WHERE STOCK_SUBJECT_CODE = " & CLng(Request.form("stock_subject_code")) & " "
 Set Rec_stock_subject = DB.Execute(SQL_stock_subject)
  STOCK_SUBJECT =  Rec_stock_subject.Fields("STOCK_SUBJECT")
END IF
'*********************
' SHORT NAME(SECTION) GET
'*********************
IF REQUEST.FORM("section_code") = "" THEN
 SECTION_CODE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT SECTION SHORT NAME.<Br>"
ELSE
  SQL_section = "SELECT  SHORT_NAME " & _
 	" FROM SECTION " & _
 	" WHERE SECTION_CODE = " & CLng(Request.form("section_code")) & " "
  Set Rec_section = DB.Execute(SQL_section)
   SECTION =  Rec_section.Fields("SHORT_NAME")
END IF
'*********************
' COST SUBJECT CODE GET
'*********************
IF REQUEST.FORM("cost_subject_code") = "" THEN
 COST_SUBJECT_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT COST SUBJECT.<Br>"
ELSE
 SQL_costsubject = "SELECT  COST_SUBJECT_NAME " & _
 	 " FROM COSTSUBJECT " & _
 	 " WHERE COST_SUBJECT_CODE = '" & Request.form("cost_subject_code") & "' "
 Set Rec_costsubject = DB.Execute(SQL_costsubject)
  COST_SUBJECT =  Rec_costsubject.Fields("COST_SUBJECT_NAME")
END IF
'*********************
' COST PROCESS CODE GET
'*********************
IF REQUEST.FORM("cost_process_code") = "" THEN
 COST_PROCESS_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT COST PROCESS.<Br>"
ELSE
 SQL_costprocess = "SELECT  COST_PROCESS_NAME " & _
 	 " FROM COSTPROCESS " & _
 	 " WHERE COST_PROCESS_CODE = '" & Request.form("cost_process_code") & "' "
 Set Rec_costprocess = DB.Execute(SQL_costprocess)
  COST_PROCESS =  Rec_costprocess.Fields("COST_PROCESS_NAME")
END IF
'************************************
' MANUFACT LEAD TIME
'************************************
IF Request.Form("manufact_leadtime") <> "" THEN
 IF IsNumeric(Request.Form("manufact_leadtime")) THEN
   MANU_LEAD_TIME_FLG = 0
 ELSE
  MANU_LEAD_TIME_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT MANUFACT LEAD TIME ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' PURCHASE LEAD TIME
'************************************
IF Request.Form("purchase_leadtime") <> "" THEN
 IF IsNumeric(Request.Form("purchase_leadtime")) THEN
   PUR_LEAD_TIME_FLG = 0
 ELSE
  PUR_LEAD_TIME_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT PURCHASE LEAD TIME ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' ADJUSTMENT LEAD TIME
'************************************
IF Request.Form("adjustment_leadtime") <> "" THEN
 IF IsNumeric(Request.Form("adjustment_leadtime")) THEN
   ADJ_LEAD_TIME_FLG = 0
 ELSE
  ADJ_LEAD_TIME_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ADJUSTMENT LEAD TIME ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' ISSUE LOT 
'************************************
IF REQUEST.FORM("issue_policy") = "D" THEN
 IF REQUEST.FORM("issue_lot") = "" THEN
  ISSUE_LOT_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ISSUE LOT WHEN ISSUE POLICY IS &quot;Gloss&quot;.<Br>"
 ELSE
  IF IsNumeric(REQUEST.FORM("ISSUE_LOT")) THEN
   ISSUE_LOT_FLG = 0
  ELSE
   ISSUE_LOT_FLG = 1
   ERROR_MESSAGE = ERROR_MESSAGE & "INPUT ISSUE LOT ONLY NUMBER.<Br>"
  END IF
 END IF
END IF
'************************************
' MANUFACT FAIL RATE
'************************************
IF Request.Form("manufact_fail_rate") <> "" THEN
 IF IsNumeric(Request.Form("manufact_fail_rate")) THEN
   MANU_FAIL_RATE_FLG = 0
 ELSE
  MANU_FAIL_RATE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT MANUFACT FAIL RATE ONLY NUMBER.<Br>"
 END IF
END IF
'************************************
' MAKER FLAG
'************************************
'RESPONSE.WRITE REQUEST.FORM("MAKER_FLAG")
'RESPONSE.END
IF Request.Form("maker_flag") = "" THEN
' MAKER_FLAG_FLG = 1
'  ERROR_MESSAGE = ERROR_MESSAGE & "SELECT MAKER FLAG.<Br>"
 MAKERFLAG = ""
ELSE
 '*************************
 'maker flag
 '*************************
 SQL_MAKERFLAG = "SELECT MAKER_FLAG " & _
                 "FROM MAKERFLAG " & _
                 "WHERE MAKER_FLAG_CODE = " & CLng(REQUEST.FORM("MAKER_FLAG"))
 SET REC_MAKERFLAG = DB.EXECUTE(SQL_MAKERFLAG)
  MAKERFLAG = REC_MAKERFLAG.FIELDS("MAKER_FLAG")
END IF
'*********************
' UNIT STOCK GET
'*********************
IF REQUEST.FORM("unit_stock") = "" THEN
 UNIT_STOCK_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT STOCK.<Br>"
ELSE
 SQL_unit_stock = "SELECT  UNIT " & _
 	 " FROM UNIT " & _
 	 " WHERE DELETE_TYPE IS NULL AND" & _
 	 "       UNIT_CODE = " & CLng(Request.form("unit_stock")) & " "
 Set Rec_unit_stock = DB.Execute(SQL_unit_stock)
  UNIT_STOCK =  Rec_unit_stock.Fields("UNIT")
END IF
'************************************
' UNIT STOCK RATE
'************************************
IF Request.Form("unit_stock_rate") <> "" THEN
 IF IsNumeric(Request.Form("unit_stock_rate")) THEN
  UNIT_STOCK_R_FLG = 0
 ELSE
  UNIT_STOCK_R_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT UNIT STOCK RATE ONLY NUMBER.<Br>"
 END IF
ELSE
 UNIT_STOCK_R_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT UNIT STOCK RATE.<Br>"
END IF
'*********************
' SAFETY STOCK
'*********************
IF REQUEST.FORM("SAFETY_STOCK") <> "" THEN
 IF IsNumeric(Request.Form("safety_stock")) THEN
  SAFETY_STOCK_FLG = 0
 ELSE
  SAFETY_STOCK_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT SAFETY STOCK ONLY NUMBER.<Br>"
 END IF
END IF
'*********************
' ISSUE POLICY
'*********************
IF CStr(REQUEST.FORM("ISSUE_POLICY")) <> "" THEN
 SQL_ISSUEPOLICY = "SELECT POLICY_NAME " & _
                   "  FROM ISSUEPOLICY " & _
                   " WHERE ISSUE_POLICY = '" & CStr(REQUEST.FORM("ISSUE_POLICY")) & "' "
 SET REC_ISSUEPOLICY = DB.EXECUTE(SQL_ISSUEPOLICY)
 ISSUE_POLICY = REC_ISSUEPOLICY.FIELDS("POLICY_NAME")
ELSE
 ISSUE_POLICY = " "
END IF
'*********************
' UNIT OF ENGINEERING GET
'*********************
IF REQUEST.FORM("unit_engineering") = "" THEN
 UNIT_ENG_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "SELECT UNIT OF ENGINEERING.<Br>"
ELSE
 SQL_unit_eng = "SELECT  UNIT " & _
 	 " FROM UNIT " & _
 	 " WHERE DELETE_TYPE IS NULL AND" & _
 	 "       UNIT_CODE = " & CLng(Request.form("unit_engineering")) & " "
 Set Rec_unit_eng = DB.Execute(SQL_unit_eng)
  UNIT_ENG =  Rec_unit_eng.Fields("UNIT")
END IF
'************************************
' UNIT ENGINEERING RATE
'************************************
IF Request.Form("unit_engineer_rate") <> "" THEN
 IF IsNumeric(Request.Form("unit_engineer_rate")) THEN
  UNIT_ENG_RATE_FLG = 0
 ELSE
  UNIT_ENG_RATE_FLG = 1
  ERROR_MESSAGE = ERROR_MESSAGE & "INPUT UNIT ENGINEER RATE ONLY NUMBER.<Br>"
 END IF
ELSE
 UNIT_ENG_RATE_FLG = 1
 ERROR_MESSAGE = ERROR_MESSAGE & "INPUT UNIT ENGINEER RATE.<Br>"
END IF
%>
<Font Size="5" Color="#ff0000" ><B>
<% IF ERROR_MESSAGE <> "" THEN
    RESPONSE.WRITE ERROR_MESSAGE
   END IF%>
</B></Font>
<Div Align="center">
<Form Method="post" Action="Item_Result.asp?KEYWORD=<%=KEYWORD%>" Name="data_send" Target="_self">
<% IF ERROR_MESSAGE = "" THEN %>
 <Input Type="submit" Name="entry_button" Value="Entry">
<% END IF %>
 <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
Mode:<% response.write request.form("type") %>
<!-- DATA SET TO INPUT(HIDDEN) START-->
<Input Type="hidden" Name="type" Value="<%= request.form("type") %>">
<Input Type="hidden" Name="item_no" Value="<%= Request.form("item_no") %>" >
<Input Type="hidden" Name="item_code" Value="<%= replace(Request.form("item_code") & "","""","&quot;") %>" >
<Input Type="hidden" Name="item" Value="<%= replace(UCase(Request.form("item")) & "","""","&quot;") %>" >
<Input Type="hidden" Name="item_flag" Value="<%= UCase(Request.form("item_flag")) %>" >
<Input Type="hidden" Name="origin_code" Value="<%= Request.form("origin_code") %>" >
<Input Type="hidden" Name="description" Value="<%= replace(Request.form("description") & "","""","&quot;") %>" >
<Input Type="hidden" Name="class_code" Value="<%= Request.form("class_code") %>" >
<!-- 
<Input Type="hidden" Name="uom_q" Value="<%'= Request.form("uom_q") %>" >
 -->
<Input Type="hidden" Name="uom_w" Value="<%= Request.form("uom_w") %>" >
<Input Type="hidden" Name="uom_l" Value="<%= Request.form("uom_l") %>" >
<Input Type="hidden" Name="external_unit_number" Value="<%= Request.form("external_unit_number") %>" >
<Input Type="hidden" Name="standard_price" Value="<%= Request.form("standard_price") %>" >
<Input Type="hidden" Name="next_term_price" Value="<%= Request.form("next_term_price") %>" >
<Input Type="hidden" Name="suppliers_price" Value="<%= Request.form("suppliers_price") %>" >
<Input Type="hidden" Name="curr_code" Value="<%= Request.form("curr_code") %>" >
<Input Type="hidden" Name="weight" Value="<%= Request.form("weight") %>" >
<Input Type="hidden" Name="stock_subject_code" Value="<%= Request.form("stock_subject_code") %>" >
<Input Type="hidden" Name="section_code" Value="<%= Request.form("section_code") %>" >
<Input Type="hidden" Name="cost_subject_code" Value="<%= Request.form("cost_subject_code") %>" >
<Input Type="hidden" Name="cost_process_code" Value="<%= Request.form("cost_process_code") %>" >
<Input Type="hidden" Name="manufact_leadtime" Value="<%= Request.form("manufact_leadtime") %>" >
<Input Type="hidden" Name="purchase_leadtime" Value="<%= Request.form("purchase_leadtime") %>" >
<Input Type="hidden" Name="adjustment_leadtime" Value="<%= Request.form("adjustment_leadtime") %>" >
<Input Type="hidden" Name="issue_policy" Value="<%= Request.form("issue_policy") %>" >
<Input Type="hidden" Name="issue_lot" Value="<%= Request.form("issue_lot") %>">
<Input Type="hidden" Name="manufact_fail_rate" Value="<%= Request.form("manufact_fail_rate") %>" >
<Input Type="hidden" Name="maker_flag" Value="<%= Request.form("maker_flag") %>" >
<Input Type="hidden" Name="unit_stock" Value="<%= Request.form("unit_stock") %>" >
<Input Type="hidden" Name="unit_stock_rate" Value="<%= Request.form("unit_stock_rate") %>" >
<Input Type="hidden" Name="drawing_no" Value="<%= Replace(UCase(REQUEST.FORM("drawing_no")) &"","""","&quot;") %>">
<Input Type="hidden" Name="drawing_rev" Value="<%= Replace(UCase(REQUEST.FORM("drawing_rev")) &"","""","&quot;") %>">
<Input Type="hidden" Name="applicable_model" Value="<%= Replace(UCase(REQUEST.FORM("applicable_model")) &"","""","&quot;") %>">
<Input Type="hidden" Name="catalog_no" Value="<%= Replace(UCase(REQUEST.FORM("catalog_no")) &"","""","&quot;") %>">
<Input Type="hidden" Name="safety_stock" Value="<%= REQUEST.FORM("SAFETY_STOCK") %>">
<Input Type="hidden" Name="order_policy" Value="<%= REQUEST.FORM("ORDER_POLICY") %>">
<Input Type="hidden" Name="mak" Value="<%= UCase(REQUEST.FORM("MAK")) %>">
<Input Type="hidden" Name="unit_engineering" Value="<%= REQUEST.FORM("UNIT_ENGINEERING") %>">
<Input Type="hidden" Name="unit_engineer_rate" Value="<%= REQUEST.FORM("UNIT_ENGINEER_RATE") %>">
<Input Type="hidden" Name="item_type1" Value="<%= UCase(REQUEST.FORM("item_type1"))%>">
<Input Type="hidden" Name="item_type2" Value="<%= UCase(REQUEST.FORM("item_type2"))%>">
<!-- FOR ORDER/FORCAST START -->
<Input Type="hidden" Name="GRP_FLG" Value="<%= request("GRP_FLG") %>">
<Input Type="hidden" Name="customer_code" Value="<%=REQUEST("CUSTOMER_CODE")%>">
<Input Type="hidden" Name="customer_po_no" Value="<%=REQUEST("CUSTOMER_PO_NO")%>">
<!-- FOR ORDER/FORCAST END -->
</Form>
<!-- DATA SET TO INPUT(HIDDEN) END -->
<Table Border Align="center" Width="100%" Height="100%" CellSpacing="1" BorderColor="black">
<Tr>
<Td BgColor="#00FF00">Item No</Td>
<% IF ITEM_NO_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000"><%= Request.form("item_no") %>&nbsp;</Td>
<% ELSE %>
 <Td ColSpan="2"><%= Request.form("item_no") %></Td>
<% END IF %>
<Td BgColor="#00ff00">Item Code</Td>
<Td ColSpan="2"><%= Request.form("item_code")%>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Name</Td>
<% IF ITEM_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000"><%= UCase(Request.form("item")) %>&nbsp;</Td>
<% ELSE %>
 <Td ColSpan="2"><%= UCase(Request.form("item")) %></Td>
<% END IF %>
<Td BgColor="#00ff00">Item Flag</Td>
<% IF ITEM_FLAG_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000"><% RESPONSE.WRITE ITEM_FLAG %>&nbsp;</Td>
<% ELSE %>
<Td ColSpan="2"><% RESPONSE.WRITE ITEM_FLAG %>&nbsp;</Td>
<% END IF %>
</Tr>
<Tr>
<Td BgColor="#00ff00">Description</Td><Td ColSpan="2"><%= Request.form("description") %>&nbsp;</Td>
<Td BgColor="#00ff00">Class Code</Td>
<% IF CLASS_CODE_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% RESPONSE.WRITE CLASS1 %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Origin Code</Td>
<% IF ORIGIN_CODE_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write ORIGIN %>&nbsp;</Td>
<Td BgColor="#00ff00">Curr Code</Td>
<% IF CURR_CODE_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write CURR_MARK %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">External Unit Number</Td>
<% IF EX_UNIT_NO_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000"><%= Request.form("external_unit_number") %>&nbsp;</Td>
<% ELSE %>
 <Td ColSpan="2"><%= Request.form("external_unit_number") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Safety Stock</Td>
<% IF SAFETY_STOCK_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="red"><%= REQUEST.FORM("SAFETY_STOCK") %>
<% ELSE%>
 <Td ColSpan="2"><%= REQUEST.FORM("SAFETY_STOCK") %>
<% END IF %>
&nbsp;</Td>
</Tr>
<Tr>
<!-- 
<Td BgColor="#00ff00">Unit of Quantity</Td>
<%' IF UOM_Q_FLG = 1 THEN %>
 <Td BgColor="#ff0000">
<%' ELSE %>
 <Td>
<%' END IF %>
<%' Response.write UNIT_Q %>&nbsp;</Td>
 -->
<Td BgColor="#00ff00">Unit Stock</Td>
<% IF UNIT_STOCK_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write UNIT_STOCK %>&nbsp;</Td>
<Td BgColor="#00ff00">Unit of Engineering</Td>
<% IF UNIT_ENG_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write UNIT_ENG %>&nbsp;</Td>
</Tr>
<Tr>
<Td ColSpan="3" BgColor="#00FF00">
<Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
</Td>
<% IF UNIT_STOCK_R_FLG = 1 OR UNIT_ENG_RATE_FLG = 1 THEN %>
 <Td ColSpan="3" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="3">
<% END IF %>
<%= Request.form("unit_stock_rate") %>&nbsp;
:
<%= Request.form("unit_engineer_rate") %>&nbsp;
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Weight</Td>
<% IF WEIGHT_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("weight") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("weight") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Unit of Weight</Td>
<% IF UOM_W_FLG = 1 THEN %>
 <Td BgColor="#ff0000">
<% ELSE %>
 <Td>
<% END IF %>
<% Response.write UNIT_W %>&nbsp;</Td>
<Td BgColor="#00ff00">Unit of Measurement</Td>
<% IF UOM_L_FLG = 1 THEN %>
 <Td BgColor="#ff0000">
<% ELSE %>
 <Td>
<% END IF %>
<% Response.write UNIT_L %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">DRAWING NO</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("drawing_no")) %>&nbsp;</Td>
<Td BgColor="#00ff00">DRAWING REV</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("drawing_rev")) %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">APPLICABLE MODEL</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("applicable_model")) %>&nbsp;</Td>
<Td BgColor="#00ff00">CATALOG NO</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("catalog_no")) %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Standard Price</Td>
<% IF STD_PRICE_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("standard_price") %>&nbsp;</Td>
<% ELSE %>
 <Td ><%= Request.form("standard_price") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Next Term Price</Td>
<% IF NEXT_TERM_PRICE_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("next_term_price") %>&nbsp;</Td>
<% ELSE %>
 <Td ><%= Request.form("next_term_price") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Suppliers Price</Td>
<% IF SUPPLIERS_PRICE_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("suppliers_price") %>&nbsp;</Td>
<% ELSE %>
 <Td ><%= Request.form("suppliers_price") %>&nbsp;</Td>
<% END IF %>
</Tr>
<Tr>
<Td BgColor="#00ff00">Manufact Lead Time</Td>
<% IF MANU_LEAD_TIME_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("manufact_leadtime") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("manufact_leadtime") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Purchase Lead Time</Td>
<% IF PUR_LEAD_TIME_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("purchase_leadtime") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("purchase_leadtime") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Adjustment Lead Time</Td>
<% IF ADJ_LEAD_TIME_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("adjustment_leadtime") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("adjustment_leadtime") %>&nbsp;</Td>
<% END IF %>
</Tr>
<Tr>
<Td BgColor="#00ff00">Issue Policy</Td>
<Td>
<% RESPONSE.WRITE ISSUE_POLICY %>&nbsp;</Td>
<Td BgColor="#00ff00">Issue Lot</Td>
<% IF ISSUE_LOT_FLG = 1 THEN %>
 <Td BgColor="red"><%= REQUEST.FORM("issue_lot") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= REQUEST.FORM("issue_lot") %>&nbsp;</Td>
<% END IF %>
<Td BgColor="#00ff00">Manufact Fail Rate</Td>
<% IF MANU_FAIL_RATE_FLG = 1 THEN %>
 <Td BgColor="#ff0000"><%= Request.form("manufact_fail_rate") %>&nbsp;</Td>
<% ELSE %>
 <Td><%= Request.form("manufact_fail_rate") %>&nbsp;</Td>
<% END IF %>
</Tr>
<Tr>
<Td BgColor="#00ff00">Section Code</Td>
<% IF SECTION_CODE_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write SECTION %>&nbsp;</Td>
<Td BgColor="#00ff00">Stock Subject Code</Td>
<% IF STOCK_SUBJECT_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write STOCK_SUBJECT %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Cost Process Code</Td>
<% IF COST_PROCESS_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write COST_PROCESS %>&nbsp;</Td>
<Td BgColor="#00ff00">Cost Subject Code</Td>
<% IF COST_SUBJECT_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#ff0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<% Response.write COST_SUBJECT %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Order Policy</Td>
<Td ColSpan="5">
<%
IF REQUEST.FORM("ORDER_POLICY") <> "" THEN
 '********************
 ' order policy get
 '********************
 SQL_ORDER_POLICY = "SELECT POLICY_NAME " & _
                    "  FROM ORDER_POLICY " & _
                    " WHERE ORDER_POLICY = '" & CStr(REQUEST.FORM("ORDER_POLICY")) & "' "
 SET REC_ORDER_POLICY = DB.EXECUTE(SQL_ORDER_POLICY) %>
 <%= REC_ORDER_POLICY.FIELDS("POLICY_NAME") %>
<%END IF%>
&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Maker Flag</Td>
<% IF MAKER_FLAG_FLG = 1 THEN %>
 <Td ColSpan="2" BgColor="#FF0000">
<% ELSE %>
 <Td ColSpan="2">
<% END IF %>
<%
RESPONSE.WRITE MAKERFLAG
%>
&nbsp;</Td>
<Td BgColor="#00ff00">Maker</Td>
<Td ColSpan="2"><%= UCase(Request.form("mak")) %>&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Type1</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("item_type1"))%>&nbsp;</Td>
<Td BgColor="#00ff00">Item Type2</Td>
<Td ColSpan="2"><%= UCase(REQUEST.FORM("item_type2"))%>&nbsp;</Td>
</Tr>
<!-- 
<Tr>
<Td BgColor="#00ff00">Supplier Code</Td><Td ColSpan="5"><%= Request.form("supplier_code")%>&nbsp;</Td>
</Tr>
 -->
<!-- <Tr>
<Td BgColor="#00ff00">Reorder Point</Td><Td ColSpan="2"><%= Request.form("reorder_point")%>&nbsp;</Td>
<Td BgColor="#00ff00">Llc Code</Td><Td ColSpan="2"><%= Request.form("llc_code")%>&nbsp;</Td>
</Tr> -->

</Table>


</Div>
</Body>
</Html>
<% DB.Close %>

