<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
KEYWORD = Request("KEYWORD")


  call html_header("Item Entry",KEYWORD)
%>
<SCRIPT LANGUAGE=Javascript>
  function setITYPE1(){
        Param = "?types=item_type1"
        c1 = window.open('item_type.asp' + Param, 'cal','WIDTH=250,HEIGHT=300,SCROLLBARS=yes,RESIZABLE=yes') ;
  }
  function setITYPE2(){
        Param = "?types=item_type2"
        c1 = window.open('item_type.asp' + Param, 'cal','WIDTH=250,HEIGHT=300,SCROLLBARS=yes,RESIZABLE=yes') ;
  }

</SCRIPT>

<%
'---------
'DB OPEN
'---------
Set db = Server.CreateObject("ADODB.Connection")
db.Open server_name,server_user,server_psword

'自分の会社コードを取得
  SQL =  "select company_code from company where company_type=0 "
  SET REC = DB.Execute(SQL)
     my_company_code = rec("company_code")
  rec.Close
'926236 fdkタイランドでstandard_price等制限をかける

'ユーザコードからタイプを見て、80ならaccount
 SQL_TYPE = "select person_type from person where person_code = '" & CStr(KEYWORD) & "' "
 SET REC_TYPE = DB.Execute(SQL_TYPE)
 If not rec_type.EOF Then
    my_type = rec_type("person_type")
 End If
 rec_type.close

%>
<Div Align="center">
<Form Method="post" Action="Item_Confirm.asp?KEYWORD=<%=KEYWORD%>" Name="data_send" Target="_self">
<!-- type(new,change,delete get start -->
<Input Type="hidden" Name="type" Value="<%= request("type") %>">
<Input Type="hidden" Name="GRP_FLG" Value="<%= request("GRP_FLG") %>">
<% IF REQUEST("GRP_FLG") = " " THEN %>
    <Input Type="hidden" Name="customer_code" Value="">
    <Input Type="hidden" Name="customer_po_no" Value="">
<% ELSEIF REQUEST("GRP_FLG") = "ORDER" THEN %>
    <Input Type="hidden" Name="customer_code" Value="<%=REQUEST("CUSTOMER_CODE")%>">
    <Input Type="hidden" Name="customer_po_no" Value="<%=REQUEST("CUSTOMER_PO_NO")%>">
<% ELSEIF REQUEST("GRP_FLG") = "FORECAST" THEN %>
    <Input Type="hidden" Name="customer_code" Value="<%=REQUEST("CUSTOMER_CODE")%>">
    <Input Type="hidden" Name="customer_po_no" Value="">
<% END IF %>
<!-- type(new,change,delete get end -->
<% IF REQUEST("type") = "NEW" THEN
    '*********************************
    'STOCK SUBJECT CHECK
    '*********************************
    IF REQUEST("stock_subject_code") = "" THEN %>
     <Pre>
     <Font Size="5" Color="#ff0000" Face="@ＭＳ ゴシック">
     <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
     <% RESPONSE.WRITE "SELECT STOCK SUBJECT."
        RESPONSE.END %>
     </Font>
     </Pre><Br>
 <% END IF %>
 <Input Type="submit" Name="confirm_button" Value="Confirm">
 <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
<% ELSE %>
 <% IF REQUEST("delete_type") = "D" THEN %>
     <Pre>
     <Font Size="5" Color="#ff0000" Face="@ＭＳ ゴシック">
     <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
     <% RESPONSE.WRITE "THIS IS DELETED DATA." %>
     </Font>
     </Pre><Br>
 <% ELSE %>
     <Input Type="submit" Name="confirm_button" Value="Confirm">
     <Input Type="Button" Value="BACK" onClick='history.back()'><BR>
 <% END IF %>
<% END IF %>
Mode:<% response.write request("type") %>
<Table Border Align="center" Width="100%" Height="100%" CellSpacing="1" BgColor="#ffffff" BorderColor="black">
<!-- NEW(FROM ITEM ENTRY MAIN) -->
<% IF REQUEST("type") = "NEW" AND REQUEST("GRP_FLG") = " " THEN %>
 <%
 '*****************************
 ' FOR ORIGIN, CURRENCY DEFAULT
 '*****************************
  SQL_COMPANY = "SELECT COUNTRY_CODE, CURR_CODE " & _
                "  FROM COMPANY " & _
                " WHERE COMPANY_TYPE = 0 "
  SET REC_COMPANY = DB.Execute(SQL_COMPANY)
  %>

 <Br>
 If text color is <Font Color="#0000ff">Blue</Font>, Input Number only.
 <Tr>
 <Td BgColor="#00FF00"><Font Color="#0000ff">Item No</Font></Td>
 <Td ColSpan="2">
 <% IF CStr(REQUEST("no_type")) = "GROUP" THEN %>
  <Input Type="text" Name="item_no" Size="8" Maxlength="8"> 
 <% If (Session("NGM") = "1") Then %>
  Make an NG Materials <Input Type="checkbox" Name="ngmc" value="on" checked> 
 <% End If %>

 <% ELSEIF CStr(REQUEST("no_type")) = "NGM" THEN %>
  <Input Type="text" Name="item_no" Size="8" Maxlength="8"> NG Materials ITEM NO is 9XXXXXXXX
  <Input Type="hidden" Name="no_type" Value="NGM">
 <% ELSE
     '******************
     ' FIX PART GET
     '******************
     SQL_FIX = "SELECT FIX_NO FROM ITEM_NO_FIX "
     SET REC_FIX = DB.EXECUTE(SQL_FIX)
     IF REC_FIX.EOF OR REC_FIX.BOF THEN
      W_ITEM_NO = "FIX PART NOT ENTRIED."
     ELSE
      '******************
      ' AUTO NO GET
      '******************
      SQL_SEQUENCE = "SELECT ITEM_NO_SEQ.NEXTVAL AS NEXT FROM DUAL "
      SET REC_SEQUENCE = DB.EXECUTE(SQL_SEQUENCE)
      IF REC_SEQUENCE.EOF OR REC_SEQUENCE.BOF THEN
       W_ITEM_NO = "OBJECT ITEM_NO_SEQ NOT CREATED."
      ELSE
       W_ITEM_NO = CStr(REC_FIX.FIELDS("FIX_NO")) & RIGHT("000000" & REC_SEQUENCE.FIELDS("NEXT"), 6)
      END IF
     END IF
     RESPONSE.WRITE W_ITEM_NO %>
  <Input Type="hidden" Name="item_no" Value="<%= W_ITEM_NO %>">
 <% END IF %>
 </Td>
 <Td BgColor="#00ff00">Item Code</Td>
 <Td ColSpan="2"><Input Type="text" Name="item_code" Size="24" Maxlength="24"></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Item Name</Td>
 <Td ColSpan="2"><Input Type="text" Name="item" Size="40" Maxlength="40"></Td>
 <Td BgColor="#00ff00">Item Flag</Td>
 <Td ColSpan="2"><Select Name="item_flag" Size="1">
 <%
  '*********************
  ' item flag get
  '*********************
  SQL_ITEMFLAG = "SELECT ITEM_FLAG, FLAG_NAME " & _
                 "  FROM ITEMFLAG " & _
                 " ORDER BY FLAG_NAME "
  SET REC_ITEMFLAG = DB.EXECUTE(SQL_ITEMFLAG)
  IF REC_ITEMFLAG.EOF OR REC_ITEMFLAG.BOF THEN %>
   <Option Value="">DATA NOT FOUND
 <%ELSE
   DO WHILE NOT REC_ITEMFLAG.EOF
    IF CStr(REC_ITEMFLAG.FIELDS("ITEM_FLAG")) = "1" THEN %>
     <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>" SELECTED><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
    <% ELSE %>
     <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>"><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
    <% END IF %>
 <% REC_ITEMFLAG.MOVENEXT
    LOOP
  END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Description</Td>
 <Td ColSpan="2"><Input Type="text" Name="description" Size="30" Maxlength="30"></Td>
 <Td BgColor="#00ff00">Class Code</Td>
 <Td ColSpan="2"><Select Name="class_code" Size="1">
  <Option Value="">
 <%
 '------------------
 ' class get
 '------------------
 SQL_class = "SELECT  nvl(CLASS_CODE,0) CLASS_CODE, CLASS_1 || '-' ||CLASS_2|| '-' || CLASS_3 AS CLASS " & _
             "  FROM CLASS " & _
             " ORDER BY CLASS "
 Set Rec_class = DB.Execute(SQL_class)
 IF Rec_class.eof or Rec_class.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_class.EOF %>
  <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>"><%= Rec_class.Fields("CLASS") %>
 <% Rec_class.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Origin Code</Td>
 <Td ColSpan="2"><Select Name="origin_code" Size="1">
 <Option Value="">
 <%
 '------------------
 ' country code get
 '------------------
 SQL_country = "SELECT  COUNTRY_CODE, COUNTRY " & _
               "  FROM COUNTRY " & _
               " WHERE DELETE_TYPE IS NULL " & _
               " ORDER BY COUNTRY "
 Set Rec_country = DB.Execute(SQL_country)
 IF Rec_country.eof or Rec_country.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_country.EOF %>
 <% IF CLng(REC_COUNTRY.FIELDS("COUNTRY_CODE")) = CLng(REC_COMPANY.FIELDS("COUNTRY_CODE")) THEN %>
  <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>" SELECTED ><%= Rec_country.Fields("COUNTRY") %>
  <% ELSE %>
  <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>"><%= Rec_country.Fields("COUNTRY") %>
  <% END IF %>
 <% Rec_country.Movenext
   LOOP %>
 <% END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Curr Code</Td>
 <Td ColSpan="2"><Select Name="curr_code" Size="1">
  <Option Value="">
 <%
 '------------------
 ' currency code get
 '------------------
 SQLcurrency = "SELECT  CURR_CODE, CURR_MARK " & _
               "  FROM CURRENCY " & _
               " WHERE DELETE_TYPE IS NULL " & _
               " ORDER BY CURR_MARK "
 Set Reccurrency = DB.Execute(SQLcurrency)
 IF Reccurrency.eof or Reccurrency.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Reccurrency.EOF %>
  <% IF CLng(RECCURRENCY.FIELDS("CURR_CODE")) = CLng(REC_COMPANY.FIELDS("CURR_CODE")) THEN %>
   <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>" SELECTED ><%= Reccurrency.Fields("CURR_MARK") %>
  <% ELSE %>
   <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>"><%= Reccurrency.Fields("CURR_MARK") %>
  <%  END IF %>
 <% Reccurrency.Movenext
  LOOP %>
 <% END IF %>
 </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">External Unit Number</Font></Td>
 <Td ColSpan="2"><Input Type="text" Name="external_unit_number" Size="5" Maxlength="5"></Td>
 <Td BgColor="#00ff00">Safety Stock</Td>
 <Td ColSpan="2"><Input Type="text" Name="safety_stock" Size="9" Maxlength="9"></Td>
 </Tr>
 <Tr>
 <!-- 
 <Td BgColor="#00ff00">Unit of Quantity</Td>
 <Td><Select Name="uom_q" Size="1">
 <Option Value="">
 <%
 '------------------
 ' unit of quantity get
 '------------------
 'SQL_uoq = "SELECT  UNIT_CODE, UNIT " & _
 '          "  FROM UNIT " & _
 '          " WHERE DELETE_TYPE IS NULL " & _
 '          "   AND USE = 'QUANTITY'" & _
 '          " ORDER BY UNIT "
 'Set Rec_uoq = DB.Execute(SQL_uoq)
 'IF Rec_uoq.eof or Rec_uoq.bof then %>
  <Option Value="">NO DATA FOUND
 <%' ELSE
 ' DO While not Rec_uoq.EOF %>
  <Option Value="<%'= Rec_uoq.Fields("UNIT_CODE") %>"><%'= Rec_uoq.Fields("UNIT") %>
 <%' Rec_uoq.Movenext
  'LOOP %>
 <%' END IF %>
 </Select></Td>
  -->
 <Td BgColor="#00ff00">Unit Stock</Td>
 <Td ColSpan="2"><Select Name="unit_stock" Size="1">
 <Option Value="">
 <%
 '------------------
 ' unit stock get
 '------------------
 SQL_unit_stock = "SELECT  UNIT_CODE, UNIT " & _
                  "  FROM UNIT " & _
                  " WHERE DELETE_TYPE IS NULL " & _
                  "   AND USE = 'QUANTITY'" & _
                  " ORDER BY UNIT "
 Set Rec_unit_stock = DB.Execute(SQL_unit_stock)
 IF Rec_unit_stock.eof or Rec_unit_stock.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_unit_stock.EOF %>
  <Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>"><%= Rec_unit_stock.Fields("UNIT") %>
 <% Rec_unit_stock.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 <Td BgColor="#00ff00">Unit of Engineering</Td>
 <Td ColSpan="2"><Select Name="unit_engineering" Size="1">
 <Option Value="">
 <%
 '------------------
 ' unit of quantity get
 '------------------
 SQL_unit_eng = "SELECT  UNIT_CODE, UNIT " & _
           "  FROM UNIT " & _
           " WHERE DELETE_TYPE IS NULL " & _
           "   AND USE = 'QUANTITY'" & _
           " ORDER BY UNIT "
 Set Rec_unit_eng = DB.Execute(SQL_unit_eng)
 IF Rec_unit_eng.eof or Rec_unit_eng.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_unit_eng.EOF %>
  <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>"><%= Rec_unit_eng.Fields("UNIT") %>
 <% Rec_unit_eng.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td ColSpan="3" BgColor="#00FF00">
 <Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
 </Td>
 <Td ColSpan="3">
 <Input Type="text" Name="unit_stock_rate" Size="6" Maxlength="6">
 :
 <Input Type="text" Name="unit_engineer_rate" Size="6" Maxlength="6">
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Weight</Font></Td>
 <Td><Input Type="text" Name="weight" Size="12" Maxlength="12"></Td>
 <Td BgColor="#00ff00">Unit of Weight</Td>
 <Td><Select Name="uom_w" Size="1">
 <Option Value="">
 <%
 '------------------
 ' unit of weight get
 '------------------
 SQL_uow = "SELECT  UNIT_CODE, UNIT " & _
           "  FROM UNIT " & _
           " WHERE DELETE_TYPE IS NULL " & _
           "   AND USE = 'WEIGHT'" & _
           " ORDER BY UNIT "
 Set Rec_uow = DB.Execute(SQL_uow)
 IF Rec_uow.eof or Rec_uow.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_uow.EOF %>
  <Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>"><%= Rec_uow.Fields("UNIT") %>
 <% Rec_uow.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 <Td BgColor="#00ff00">Unit of Measurement</Td>
 <Td><Select Name="uom_l" Size="1">
  <Option Value="">
 <%
 '------------------
 ' unit of length get
 '------------------
 SQL_uol = "SELECT  UNIT_CODE, UNIT " & _
           "  FROM UNIT " & _
           " WHERE DELETE_TYPE IS NULL " & _
           "   AND USE = 'LENGTH' " & _
           " ORDER BY UNIT "
 Set Rec_uol = DB.Execute(SQL_uol)
 IF Rec_uol.eof or Rec_uol.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_uol.EOF %>
  <Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>"><%= Rec_uol.Fields("UNIT") %>
 <% Rec_uol.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">DRAWING NO</Td>
 <Td ColSpan="2"><Input Type="text" Name="drawing_no" Size="15" Maxlength="15"></Td>
 <Td BgColor="#00ff00">DRAWING REV</Td>
 <Td ColSpan="2"><Input Type="text" Name="drawing_rev" Size="1" Maxlength="1"></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">APPLICABLE MODEL</Td>
 <Td ColSpan="2"><Input Type="text" Name="applicable_model" Size="20" Maxlength="20"></Td>
 <Td BgColor="#00ff00">CATALOG NO</Td>
 <Td ColSpan="2"><Input Type="text" Name="catalog_no" Size="28" Maxlength="28"></Td>
 </Tr>
 <% IF CLng(my_type) <> 80 AND CLng(my_company_code) = 926236 THEN %>
  <Input Type="hidden" Name="standard_price" Value="0">
  <Input Type="hidden" Name="next_term_price" Value="">
  <Input Type="hidden" Name="suppliers_price" Value="">
 <% ELSE %>
  <Tr>
  <Td BgColor="#00ff00"><Font Color="#0000ff">Standard Price</Font></Td>
  <Td><Input Type="text" Name="standard_price" Size="14" Maxlength="14"></Td>
  <Td BgColor="#00ff00"><Font Color="#0000ff">Next Term Price</Font></Td>
  <Td><Input Type="text" Name="next_term_price" Size="14" Maxlength="14"></Td>
  <Td BgColor="#00ff00"><Font Color="#0000ff">Suppliers Price</Font></Td>
  <Td><Input Type="text" Name="suppliers_price" Size="14" Maxlength="14"></Td>
  </Tr>
 <% END IF%>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Lead Time</Font></Td>
 <Td><Input Type="text" Name="manufact_leadtime" Size="3" Maxlength="3">Days</Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Purchase Lead Time</Font></Td>
 <Td><Input Type="text" Name="purchase_leadtime" Size="3" Maxlength="3">Days</Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Adjustment Lead Time</Font></Td>
 <Td><Input Type="text" Name="adjustment_leadtime" Size="3" Maxlength="3">Days</Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Issue Policy</Td>
 <Td><Select Name="issue_policy" Size="1">
 <Option Value="">
 <%
  '*********************
  ' ISSUE POLICY GET
  '*********************
  SQL_ISSUEPOLICY = "SELECT ISSUE_POLICY, POLICY_NAME " & _
                    "  FROM ISSUEPOLICY " & _
                    " ORDER BY POLICY_NAME "
  SET REC_ISSUEPOLICY = DB.EXECUTE(SQL_ISSUEPOLICY)
  IF REC_ISSUEPOLICY.EOF OR REC_ISSUEPOLICY.BOF THEN %>
  <Option Value="">NO DATA FOUND
 <%ELSE
    DO WHILE NOT REC_ISSUEPOLICY.EOF%>
    <Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>"><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%>
 <% REC_ISSUEPOLICY.MOVENEXT
    LOOP
   END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00"><Font Color="blue">Issue Lot</Font></Td>
 <Td><Input Type="text" Name="issue_lot" Size="9" Maxlength="9"></Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Fail Rate</Font></Td>
 <Td><Input Type="text" Name="manufact_fail_rate" Size="3" Maxlength="3">%</Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Section Code</Td>
 <Td ColSpan="2"><Select Name="section_code" Size="1">
  <Option Value="">
 <%
 '------------------
 ' section code get
 '------------------
 SQL_section = "SELECT  SECTION_CODE, SHORT_NAME " & _
               "  FROM SECTION " & _
               " ORDER BY SHORT_NAME "
 Set Rec_section = DB.Execute(SQL_section)
 IF Rec_section.eof or Rec_section.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_section.EOF %>
  <Option Value="<%= Rec_section.Fields("SECTION_CODE") %>"><%= Rec_section.Fields("SHORT_NAME") %>
 <% Rec_section.Movenext
  LOOP %>
 <% END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Stock Subject Code</Td>
 <Td ColSpan="2">
<%
 '------------------------
 ' stock subject code get
 '------------------------
If (CStr(REQUEST("no_type")) = "NGM") Then
	SQL_stocksubject = "SELECT STOCK_SUBJECT FROM STOCK_SUBJECT WHERE STOCK_SUBJECT_CODE = 7 "
	Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
	RESPONSE.WRITE "<FONT COLOR=RED><B>" & REC_STOCKSUBJECT.FIELDS("STOCK_SUBJECT") & "</B></FONT>"
	RESPONSE.WRITE "<Input Type='hidden' Name='stock_subject_code' Value='7'>"
Else
	SQL_stocksubject = "SELECT STOCK_SUBJECT FROM STOCK_SUBJECT WHERE STOCK_SUBJECT_CODE = " & CLng(REQUEST("stock_subject_code")) & " "
	Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
	RESPONSE.WRITE REC_STOCKSUBJECT.FIELDS("STOCK_SUBJECT")
	RESPONSE.WRITE "<Input Type='hidden' Name='stock_subject_code' Value=" & REQUEST("stock_subject_code") & ">"
End If
%>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Cost Process Code</Td>
 <Td ColSpan="2"><Select Name="cost_process_code" Size="1">
 <Option Value="">
 <%
 '------------------
 ' cost process code get
 '------------------
 SQL_costprocess = "SELECT  COST_PROCESS_CODE, COST_PROCESS_NAME " & _
                   "  FROM COSTPROCESS " & _
                   " ORDER BY COST_PROCESS_NAME "
 Set Rec_costprocess = DB.Execute(SQL_costprocess)
 IF Rec_costprocess.eof or Rec_costprocess.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_costprocess.EOF %>
  <Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>"><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>
 <% Rec_costprocess.Movenext
  LOOP %>
 <% END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Cost Subject Code</Td>
 <Td ColSpan="2"><Select Name="cost_subject_code" Size="1">
 <Option Value="">
 <%
 '------------------
 ' cost subject code get
 '------------------
 SQL_costsubject = "SELECT  COST_SUBJECT_CODE, COST_SUBJECT_NAME " & _
                   "  FROM COSTSUBJECT " & _
                   " ORDER BY COST_SUBJECT_NAME "
 Set Rec_costsubject = DB.Execute(SQL_costsubject)
 IF Rec_costsubject.eof or Rec_costsubject.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_costsubject.EOF %>
  <Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>"><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>
 <% Rec_costsubject.Movenext
  LOOP %>
 <% END IF %>
 </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Order Policy</Td>
 <Td ColSpan="5"><Select Name="order_policy" Size="1">
 <Option Value="">
 <%
 '********************
 ' order policy get
 '********************
 SQL_ORDER_POLICY = "SELECT ORDER_POLICY, POLICY_NAME " & _
                    "  FROM ORDER_POLICY " & _
                    " ORDER BY POLICY_NAME "
 SET REC_ORDER_POLICY = DB.EXECUTE(SQL_ORDER_POLICY)
 IF REC_ORDER_POLICY.EOF OR REC_ORDER_POLICY.BOF THEN %>
  <Option Value="">NO DATA FOUND
<% ELSE
  DO WHILE NOT REC_ORDER_POLICY.EOF
 %>
   <Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY")%>"><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME")%>
<% REC_ORDER_POLICY.MOVENEXT
  LOOP
 END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Maker Flag</Td>
 <Td ColSpan="2"><Select Name="maker_flag" Size="1">
 <Option Value="">
 <%
 '*************************
 'maker flag
 '*************************
 SQL_MAKERFLAG = "SELECT MAKER_FLAG_CODE, MAKER_FLAG " & _
                 "  FROM MAKERFLAG " & _
                 "ORDER BY MAKER_FLAG "
 SET REC_MAKERFLAG = DB.EXECUTE(SQL_MAKERFLAG)
 IF REC_MAKERFLAG.eof or REC_MAKERFLAG.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not REC_MAKERFLAG.EOF %>
  <Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>"><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %>
 <% REC_MAKERFLAG.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 <Td BgColor="#00ff00">Maker</Td>
 <Td ColSpan="2"><Input Type="text" Name="mak" Size="30" Maxlength="30"></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Item Type1</Td>
 <Td ColSpan="2"><Input Type="text" Name="item_type1" Size="30" Maxlength="30">
  <A HREF="javascript:setITYPE1()">SET</A>
 </Td>
 <Td BgColor="#00ff00">Item Type2</Td>
 <Td ColSpan="2"><Input Type="text" Name="item_type2" Size="18" Maxlength="18">
  <A HREF="javascript:setITYPE2()">SET</A>
 </Td>
 </Tr>
 <!-- 
 <Tr>
  <Td BgColor="#00ff00">Supplier Code</Td>
 <Td ColSpan="5"><Input Type="text" Name="supplier_code" Size="8" Maxlength="8"></Td>
 </Tr>
  -->
 <!-- <Tr>
 <Td BgColor="#00ff00">Reorder Point</Td>
 <Td ColSpan="2"><Input Type="text" Name="reorder_point" Size="9" Maxlength="9"></Td>
 <Td BgColor="#00ff00">Llc Code</Td>
 <Td ColSpan="2"><Input Type="text" Name="llc_code" Size="3" Maxlength="3"></Td>
 </Tr> -->
<% END IF %>
<!-- NEW(FROM ITEM ENTRY MAIN) END -->

<!-- NEW(FROM ORDER/FORCAST) -->
<% IF REQUEST("type") = "NEW" AND REQUEST("GRP_FLG") <> " " THEN %>
 <%
 '*****************************
 ' FOR ORIGIN, CURRENCY DEFAULT
 '*****************************
  SQL_COMPANY = "SELECT COUNTRY_CODE, CURR_CODE " & _
                "  FROM COMPANY " & _
                " WHERE COMPANY_TYPE = 0 "
  SET REC_COMPANY = DB.Execute(SQL_COMPANY)
  %>

 <Br>
 If text color is <Font Color="#0000ff">Blue</Font>, Input Number only.
 <Tr>
 <Td BgColor="#00FF00"><Font Color="#0000ff">Item No</Font></Td>
 <Td ColSpan="2">
  <% RESPONSE.WRITE REQUEST("ITEM_NO")%>
  <Input Type="hidden" Name="item_no" Value="<%= REQUEST("ITEM_NO") %>">
 </Td>
 <Td BgColor="#00ff00">Item Code</Td>
 <Td ColSpan="2"><Input Type="text" Name="item_code" Size="24" Maxlength="24"></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Item Name</Td>
 <Td ColSpan="2"><Input Type="text" Name="item" Size="40" Maxlength="40"></Td>
 <Td BgColor="#00ff00">Item Flag</Td>
 <Td ColSpan="2"><Select Name="item_flag" Size="1">
 <%
  '*********************
  ' item flag get
  '*********************
  SQL_ITEMFLAG = "SELECT ITEM_FLAG, FLAG_NAME " & _
                 "  FROM ITEMFLAG " & _
                 " ORDER BY FLAG_NAME "
  SET REC_ITEMFLAG = DB.EXECUTE(SQL_ITEMFLAG)
  IF REC_ITEMFLAG.EOF OR REC_ITEMFLAG.BOF THEN %>
   <Option Value="">DATA NOT FOUND
 <%ELSE
   DO WHILE NOT REC_ITEMFLAG.EOF
    IF CStr(REC_ITEMFLAG.FIELDS("ITEM_FLAG")) = "1" THEN %>
     <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>" SELECTED><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
    <% ELSE %>
     <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>"><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
    <% END IF %>
 <% REC_ITEMFLAG.MOVENEXT
    LOOP
  END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Description</Td>
 <Td ColSpan="2">
 <% RESPONSE.WRITE REQUEST("DESCRIPTION")%>
 <Input Type="hidden" Name="description" Value="<%=REQUEST("DESCRIPTION")%>">
 &nbsp;</Td>
 <Td BgColor="#00ff00">Class Code</Td>
 <Td ColSpan="2"><Select Name="class_code" Size="1">
  <Option Value="">
 <%
 '------------------
 ' class get
 '------------------
 SQL_class = "SELECT  nvl(CLASS_CODE,0) CLASS_CODE, CLASS_1 || '-' ||CLASS_2|| '-' || CLASS_3 AS CLASS " & _
             "  FROM CLASS " & _
             " ORDER BY CLASS "
 Set Rec_class = DB.Execute(SQL_class)
 IF Rec_class.eof or Rec_class.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_class.EOF %>
  <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>"><%= Rec_class.Fields("CLASS") %>
 <% Rec_class.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Origin Code</Td>
 <Td ColSpan="2"><Select Name="origin_code" Size="1">
 <Option Value="">
 <%
 '------------------
 ' country code get
 '------------------
 SQL_country = "SELECT  COUNTRY_CODE, COUNTRY " & _
               "  FROM COUNTRY " & _
               " WHERE DELETE_TYPE IS NULL " & _
               " ORDER BY COUNTRY "
 Set Rec_country = DB.Execute(SQL_country)
 IF Rec_country.eof or Rec_country.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_country.EOF %>
 <% IF CLng(REC_COUNTRY.FIELDS("COUNTRY_CODE")) = CLng(REC_COMPANY.FIELDS("COUNTRY_CODE")) THEN %>
  <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>" SELECTED ><%= Rec_country.Fields("COUNTRY") %>
  <% ELSE %>
  <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>"><%= Rec_country.Fields("COUNTRY") %>
  <% END IF %>
 <% Rec_country.Movenext
   LOOP %>
 <% END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Curr Code</Td>
 <Td ColSpan="2"><Select Name="curr_code" Size="1">
  <Option Value="">
 <%
 '------------------
 ' currency code get
 '------------------
 SQLcurrency = "SELECT  CURR_CODE, CURR_MARK " & _
               "  FROM CURRENCY " & _
               " WHERE DELETE_TYPE IS NULL " & _
               " ORDER BY CURR_MARK "
 Set Reccurrency = DB.Execute(SQLcurrency)
 IF Reccurrency.eof or Reccurrency.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Reccurrency.EOF %>
  <% IF CLng(RECCURRENCY.FIELDS("CURR_CODE")) = CLng(REC_COMPANY.FIELDS("CURR_CODE")) THEN %>
   <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>" SELECTED ><%= Reccurrency.Fields("CURR_MARK") %>
  <% ELSE %>
   <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>"><%= Reccurrency.Fields("CURR_MARK") %>
  <%  END IF %>
 <% Reccurrency.Movenext
  LOOP %>
 <% END IF %>
 </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">External Unit Number</Font></Td>
 <Td ColSpan="2"><Input Type="text" Name="external_unit_number" Size="5" Maxlength="5"></Td>
 <Td BgColor="#00ff00">Safety Stock</Td>
 <Td ColSpan="2"><Input Type="text" Name="safety_stock" Size="9" Maxlength="9"></Td>
 </Tr>
 <Tr>
 <!-- 
 <Td BgColor="#00ff00">Unit of Quantity</Td>
 <Td><Select Name="uom_q" Size="1">
 <Option Value="">
 <%
 '------------------
 ' unit of quantity get
 '------------------
 'SQL_uoq = "SELECT  UNIT_CODE, UNIT " & _
 '          "  FROM UNIT " & _
 '          " WHERE DELETE_TYPE IS NULL " & _
 '          "   AND USE = 'QUANTITY'" & _
 '          " ORDER BY UNIT "
 'Set Rec_uoq = DB.Execute(SQL_uoq)
 'IF Rec_uoq.eof or Rec_uoq.bof then %>
  <Option Value="">NO DATA FOUND
 <%' ELSE
  'DO While not Rec_uoq.EOF %>
  <Option Value="<%'= Rec_uoq.Fields("UNIT_CODE") %>"><%'= Rec_uoq.Fields("UNIT") %>
 <%' Rec_uoq.Movenext
  'LOOP %>
 <%' END IF %>
 </Select></Td>
  -->
 <Td BgColor="#00ff00">Unit Stock</Td>
 <Td ColSpan="2"><Select Name="unit_stock" Size="1">
 <Option Value="">
 <%
 '------------------
 ' unit stock get
 '------------------
 SQL_unit_stock = "SELECT  UNIT_CODE, UNIT " & _
                  "  FROM UNIT " & _
                  " WHERE DELETE_TYPE IS NULL " & _
                  "   AND USE = 'QUANTITY'" & _
                  " ORDER BY UNIT "
 Set Rec_unit_stock = DB.Execute(SQL_unit_stock)
 IF Rec_unit_stock.eof or Rec_unit_stock.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_unit_stock.EOF %>
  <Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>"><%= Rec_unit_stock.Fields("UNIT") %>
 <% Rec_unit_stock.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 <Td BgColor="#00ff00">Unit of Engineering</Td>
 <Td ColSpan="2"><Select Name="unit_engineering" Size="1">
 <Option Value="">
 <%
 '------------------
 ' unit of quantity get
 '------------------
 SQL_unit_eng = "SELECT  UNIT_CODE, UNIT " & _
           "  FROM UNIT " & _
           " WHERE DELETE_TYPE IS NULL " & _
           "   AND USE = 'QUANTITY'" & _
           " ORDER BY UNIT "
 Set Rec_unit_eng = DB.Execute(SQL_unit_eng)
 IF Rec_unit_eng.eof or Rec_unit_eng.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_unit_eng.EOF %>
  <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>"><%= Rec_unit_eng.Fields("UNIT") %>
 <% Rec_unit_eng.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 </Tr>
  <Td ColSpan="3" BgColor="#00FF00">
 <Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
 </Td>
 <Td ColSpan="3">
 <Input Type="text" Name="unit_stock_rate" Size="6" Maxlength="6">
 :
 <Input Type="text" Name="unit_engineer_rate" Size="6" Maxlength="6">
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Weight</Font></Td>
 <Td><Input Type="text" Name="weight" Size="12" Maxlength="12"></Td>
 <Td BgColor="#00ff00">Unit of Weight</Td>
 <Td><Select Name="uom_w" Size="1">
 <Option Value="">
 <%
 '------------------
 ' unit of weight get
 '------------------
 SQL_uow = "SELECT  UNIT_CODE, UNIT " & _
           "  FROM UNIT " & _
           " WHERE DELETE_TYPE IS NULL " & _
           "   AND USE = 'WEIGHT'" & _
           " ORDER BY UNIT "
 Set Rec_uow = DB.Execute(SQL_uow)
 IF Rec_uow.eof or Rec_uow.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_uow.EOF %>
  <Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>"><%= Rec_uow.Fields("UNIT") %>
 <% Rec_uow.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 <Td BgColor="#00ff00">Unit of Measurement</Td>
 <Td><Select Name="uom_l" Size="1">
  <Option Value="">
 <%
 '------------------
 ' unit of length get
 '------------------
 SQL_uol = "SELECT  UNIT_CODE, UNIT " & _
           "  FROM UNIT " & _
           " WHERE DELETE_TYPE IS NULL " & _
           "   AND USE = 'LENGTH' " & _
           " ORDER BY UNIT "
 Set Rec_uol = DB.Execute(SQL_uol)
 IF Rec_uol.eof or Rec_uol.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_uol.EOF %>
  <Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>"><%= Rec_uol.Fields("UNIT") %>
 <% Rec_uol.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">DRAWING NO</Td>
 <Td ColSpan="2"><Input Type="text" Name="drawing_no" Size="15" Maxlength="15"></Td>
 <Td BgColor="#00ff00">DRAWING REV</Td>
 <Td ColSpan="2"><Input Type="text" Name="drawing_rev" Size="1" Maxlength="1"></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">APPLICABLE MODEL</Td>
 <Td ColSpan="2"><Input Type="text" Name="applicable_model" Size="20" Maxlength="20"></Td>
 <Td BgColor="#00ff00">CATALOG NO</Td>
 <Td ColSpan="2"><Input Type="text" Name="catalog_no" Size="28" Maxlength="28"></Td>
 </Tr>
 <% IF CLng(my_type) <> 80 AND CLng(my_company_code) = 926236 THEN %>
  <Input Type="hidden" Name="standard_price" Value="0">
  <Input Type="hidden" Name="next_term_price" Value="">
  <Input Type="hidden" Name="suppliers_price" Value="">
 <% ELSE %>
  <Tr>
  <Td BgColor="#00ff00"><Font Color="#0000ff">Standard Price</Font></Td>
  <Td><Input Type="text" Name="standard_price" Size="14" Maxlength="14"></Td>
  <Td BgColor="#00ff00"><Font Color="#0000ff">Next Term Price</Font></Td>
  <Td><Input Type="text" Name="next_term_price" Size="14" Maxlength="14"></Td>
  <Td BgColor="#00ff00"><Font Color="#0000ff">Suppliers Price</Font></Td>
  <Td><Input Type="text" Name="suppliers_price" Size="14" Maxlength="14"></Td>
  </Tr>
 <% END IF%>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Lead Time</Font></Td>
 <Td><Input Type="text" Name="manufact_leadtime" Size="3" Maxlength="3">Days</Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Purchase Lead Time</Font></Td>
 <Td><Input Type="text" Name="purchase_leadtime" Size="3" Maxlength="3">Days</Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Adjustment Lead Time</Font></Td>
 <Td><Input Type="text" Name="adjustment_leadtime" Size="3" Maxlength="3">Days</Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Issue Policy</Td>
 <Td><Select Name="issue_policy" Size="1">
 <Option Value="">
 <%
  '*********************
  ' ISSUE POLICY GET
  '*********************
  SQL_ISSUEPOLICY = "SELECT ISSUE_POLICY, POLICY_NAME " & _
                    "  FROM ISSUEPOLICY " & _
                    " ORDER BY POLICY_NAME "
  SET REC_ISSUEPOLICY = DB.EXECUTE(SQL_ISSUEPOLICY)
  IF REC_ISSUEPOLICY.EOF OR REC_ISSUEPOLICY.BOF THEN %>
  <Option Value="">NO DATA FOUND
 <%ELSE
    DO WHILE NOT REC_ISSUEPOLICY.EOF%>
    <Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>"><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%>
 <% REC_ISSUEPOLICY.MOVENEXT
    LOOP
   END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00"><Font Color="blue">Issue Lot</Font></Td>
 <Td><Input Type="text" Name="issue_lot" Size="9" Maxlength="9"></Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Fail Rate</Font></Td>
 <Td><Input Type="text" Name="manufact_fail_rate" Size="3" Maxlength="3">%</Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Section Code</Td>
 <Td ColSpan="2"><Select Name="section_code" Size="1">
  <Option Value="">
 <%
 '------------------
 ' section code get
 '------------------
 SQL_section = "SELECT  SECTION_CODE, SHORT_NAME " & _
               "  FROM SECTION " & _
               " ORDER BY SHORT_NAME "
 Set Rec_section = DB.Execute(SQL_section)
 IF Rec_section.eof or Rec_section.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_section.EOF %>
  <Option Value="<%= Rec_section.Fields("SECTION_CODE") %>"><%= Rec_section.Fields("SHORT_NAME") %>
 <% Rec_section.Movenext
  LOOP %>
 <% END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Stock Subject Code</Td>
 <Td ColSpan="2">
 <%
 '------------------------
 ' stock subject code get
 '------------------------
 SQL_stocksubject = "SELECT STOCK_SUBJECT " & _
                    "  FROM STOCK_SUBJECT " & _
                    " WHERE STOCK_SUBJECT_CODE = " & CLng(REQUEST("stock_subject_code")) & " "
 Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
 RESPONSE.WRITE REC_STOCKSUBJECT.FIELDS("STOCK_SUBJECT") %>
 <Input Type="hidden" Name="stock_subject_code" Value="<%= REQUEST("stock_subject_code") %>">
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Cost Process Code</Td>
 <Td ColSpan="2"><Select Name="cost_process_code" Size="1">
 <Option Value="">
 <%
 '------------------
 ' cost process code get
 '------------------
 SQL_costprocess = "SELECT  COST_PROCESS_CODE, COST_PROCESS_NAME " & _
                   "  FROM COSTPROCESS " & _
                   " ORDER BY COST_PROCESS_NAME "
 Set Rec_costprocess = DB.Execute(SQL_costprocess)
 IF Rec_costprocess.eof or Rec_costprocess.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_costprocess.EOF %>
  <Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>"><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>
 <% Rec_costprocess.Movenext
  LOOP %>
 <% END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Cost Subject Code</Td>
 <Td ColSpan="2"><Select Name="cost_subject_code" Size="1">
 <Option Value="">
 <%
 '------------------
 ' cost subject code get
 '------------------
 SQL_costsubject = "SELECT  COST_SUBJECT_CODE, COST_SUBJECT_NAME " & _
                   "  FROM COSTSUBJECT " & _
                   " ORDER BY COST_SUBJECT_NAME "
 Set Rec_costsubject = DB.Execute(SQL_costsubject)
 IF Rec_costsubject.eof or Rec_costsubject.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not Rec_costsubject.EOF %>
  <Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>"><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>
 <% Rec_costsubject.Movenext
  LOOP %>
 <% END IF %>
 </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Order Policy</Td>
 <Td ColSpan="5"><Select Name="order_policy" Size="1">
 <Option Value="">
 <%
 '********************
 ' order policy get
 '********************
 SQL_ORDER_POLICY = "SELECT ORDER_POLICY, POLICY_NAME " & _
                    "  FROM ORDER_POLICY " & _
                    " ORDER BY POLICY_NAME "
 SET REC_ORDER_POLICY = DB.EXECUTE(SQL_ORDER_POLICY)
 IF REC_ORDER_POLICY.EOF OR REC_ORDER_POLICY.BOF THEN %>
  <Option Value="">NO DATA FOUND
<% ELSE
  DO WHILE NOT REC_ORDER_POLICY.EOF
 %>
   <Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY")%>"><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME")%>
<% REC_ORDER_POLICY.MOVENEXT
  LOOP
 END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Maker Flag</Td>
 <Td ColSpan="2"><Select Name="maker_flag" Size="1">
 <Option Value="">
 <%
 '*************************
 'maker flag
 '*************************
 SQL_MAKERFLAG = "SELECT MAKER_FLAG_CODE, MAKER_FLAG " & _
                 "  FROM MAKERFLAG " & _
                 "ORDER BY MAKER_FLAG "
 SET REC_MAKERFLAG = DB.EXECUTE(SQL_MAKERFLAG)
 IF REC_MAKERFLAG.eof or REC_MAKERFLAG.bof then %>
  <Option Value="">NO DATA FOUND
 <% ELSE
  DO While not REC_MAKERFLAG.EOF %>
  <Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>"><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %>
 <% REC_MAKERFLAG.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 <Td BgColor="#00ff00">Maker</Td>
 <Td ColSpan="2"><Input Type="text" Name="mak" Size="30" Maxlength="30"></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Item Type1</Td>
 <Td ColSpan="2"><Input Type="text" Name="item_type1" Size="30" Maxlength="30">
  <A HREF="javascript:setITYPE1()">SET</A>
 </Td>
 <Td BgColor="#00ff00">Item Type2</Td>
 <Td ColSpan="2"><Input Type="text" Name="item_type2" Size="18" Maxlength="18">
  <A HREF="javascript:setITYPE2()">SET</A>
 </Td>
 </Tr>
 <!-- 
 <Tr>
  <Td BgColor="#00ff00">Supplier Code</Td>
 <Td ColSpan="5"><Input Type="text" Name="supplier_code" Size="8" Maxlength="8"></Td>
 </Tr>
  -->
 <!-- <Tr>
 <Td BgColor="#00ff00">Reorder Point</Td>
 <Td ColSpan="2"><Input Type="text" Name="reorder_point" Size="9" Maxlength="9"></Td>
 <Td BgColor="#00ff00">Llc Code</Td>
 <Td ColSpan="2"><Input Type="text" Name="llc_code" Size="3" Maxlength="3"></Td>
 </Tr> -->
<% END IF %>
<!-- NEW(FROM ORDER/FORECAST END -->


<!-- CHANGE -->
<% IF REQUEST("type") = "CHANGE" AND REQUEST("delete_type") <> "D" THEN %>
<Br>
If text color is <Font Color="#0000ff">Blue</Font>, Input Number only.
<% SQL_CHANGE = "SELECT ITEM, ITEM_CODE, ITEM_FLAG, " & _
                "ORIGIN_CODE, DESCRIPTION, nvl(CLASS_CODE,0) CLASS_CODE, " & _
                "UOM_W, UOM_L, " & _
                "EXTERNAL_UNIT_NUMBER, STANDARD_PRICE, NEXT_TERM_PRICE, " & _
                "SUPPLIERS_PRICE, CURR_CODE, WEIGHT, " & _
                "STOCK_SUBJECT_CODE, COST_SUBJECT_CODE, COST_PROCESS_CODE, " & _
                "MANUFACT_LEADTIME, PURCHASE_LEADTIME, ADJUSTMENT_LEADTIME, " & _
                "NVL(ISSUE_POLICY, ' ') ISSUE_POLICY, NVL(SECTION_CODE,0) SECTION_CODE, MANUFACT_FAIL_RATE, " & _
                "nvl(MAKER_FLAG,0) MAKER_FLAG , UNIT_STOCK, UNIT_STOCK_RATE, " & _
                "ISSUE_LOT, DRAWING_NO, DRAWING_REV, " & _
                "APPLICABLE_MODEL, CATALOG_NO, SAFETY_STOCK, " & _
                "NVL(ORDER_POLICY, ' ') ORDER_POLICY, MAK, " & _
                "UNIT_ENGINEERING, UNIT_ENGINEER_RATE, " & _
                "ITEM_TYPE1, ITEM_TYPE2 " & _
                "FROM ITEM " & _
                "WHERE ITEM_NO = " & CLng(REQUEST("ITEM_NO"))
   SET REC_CHANGE = DB.EXECUTE(SQL_CHANGE) %>
<Tr>
<Td BgColor="#00FF00">Item No</Td>
<Td ColSpan="2">
<%= REQUEST("ITEM_NO") %>
<Input Type="hidden" Name="item_no" Value="<%= REQUEST("ITEM_NO") %>"></Td>
<Td BgColor="#00ff00">Item Code</Td>
<Td ColSpan="2">
<Input Type="text" Name="item_code" Value="<%= REC_CHANGE.FIELDS("ITEM_CODE") %>" Size="24" Maxlength="24">
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Name</Td>
<Td ColSpan="2">
<Input Type="text" Name="item" Value="<%= REC_CHANGE.FIELDS("ITEM") %>" Size="40" Maxlength="40">
</Td>
<Td BgColor="#00ff00">Item Flag</Td>
<Td ColSpan="2"><Select Name="item_flag" Size="1">
 <%
  '*********************
  ' item flag get
  '*********************
  SQL_ITEMFLAG = "SELECT ITEM_FLAG, FLAG_NAME " & _
                 "  FROM ITEMFLAG " & _
                 " ORDER BY FLAG_NAME "
  SET REC_ITEMFLAG = DB.EXECUTE(SQL_ITEMFLAG)
  IF REC_ITEMFLAG.EOF OR REC_ITEMFLAG.BOF THEN %>
   <Option Value="">DATA NOT FOUND
 <%ELSE
   DO WHILE NOT REC_ITEMFLAG.EOF
    IF CStr(REC_ITEMFLAG.FIELDS("ITEM_FLAG")) = CStr(REC_CHANGE.FIELDS("ITEM_FLAG")) THEN %>
     <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>" SELECTED><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
    <% ELSE %>
     <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>"><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
    <% END IF %>
 <% REC_ITEMFLAG.MOVENEXT
    LOOP
  END IF %>
 </Select></Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Description</Td>
<Td ColSpan="2">
<Input Type="text" Name="description" Value="<%= REC_CHANGE.FIELDS("DESCRIPTION") %>" Size="30" Maxlength="30">
</Td>
<Td BgColor="#00ff00">Class Code</Td>
<Td ColSpan="2"><Select Name="class_code" Size="1">
<%
'------------------
' class get
'------------------
SQL_class = "SELECT  nvl(CLASS_CODE,0) CLASS_CODE, CLASS_1||CLASS_2||CLASS_3 AS CLASS " & _
	 " FROM CLASS " & _
	 " ORDER BY CLASS "
Set Rec_class = DB.Execute(SQL_class)
IF Rec_class.eof or Rec_class.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_class.EOF %>
 <% IF CLng(REC_CHANGE.FIELDS("CLASS_CODE")) = CLng(Rec_class.Fields("CLASS_CODE")) THEN %>
  <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>" SELECTED><%= Rec_class.Fields("CLASS") %>
 <% ELSE %>
  <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>"><%= Rec_class.Fields("CLASS") %>
 <% END IF %>
<% Rec_class.Movenext
LOOP %>
<% END IF %>
</Select></Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Origin Code</Td>
<Td ColSpan="2">
<%
'------------------
' country code get
'------------------
SQL_country = "SELECT COUNTRY " & _
	 " FROM COUNTRY " & _
	 " WHERE DELETE_TYPE IS NULL AND " & _
	 "       COUNTRY_CODE ='" & REC_CHANGE.FIELDS("ORIGIN_CODE") & "'"
Set Rec_country = DB.Execute(SQL_country)
IF Rec_country.eof or Rec_country.bof then %>
 <% RESPONSE.WRITE "DATA NOT FOUND" %>
<% ELSE %>
 <% RESPONSE.WRITE REC_COUNTRY.FIELDS("COUNTRY") %>
 <Input Type="hidden" Name="origin_code" Value="<%= REC_CHANGE.FIELDS("ORIGIN_CODE") %>">
<% END IF %>
</Td>
<Td BgColor="#00ff00">Curr Code</Td>
<Td ColSpan="2"><Select Name="curr_code" Size="1">
<Option Value="">
<%
'------------------
' currency code get
'------------------
SQLcurrency = "SELECT  CURR_CODE, CURR_MARK " & _
	 " FROM CURRENCY " & _
	 " WHERE DELETE_TYPE IS NULL " & _
	 " ORDER BY CURR_MARK "
Set Reccurrency = DB.Execute(SQLcurrency)
IF Reccurrency.eof or Reccurrency.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Reccurrency.EOF %>
 <% IF CStr(REC_CHANGE.FIELDS("CURR_CODE") & "") = CStr(Reccurrency.Fields("CURR_CODE") & "") THEN %>
  <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>" SELECTED><%= Reccurrency.Fields("CURR_MARK") %>
 <% ELSE %>
  <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>"><%= Reccurrency.Fields("CURR_MARK") %>
 <% END IF %>
<% Reccurrency.Movenext
LOOP %>
<% END IF %>
</Select>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00"><Font Color="#0000ff">External Unit Number</Font></Td>
<Td ColSpan="2">
<Input Type="text" Name="external_unit_number" Value="<%= REC_CHANGE.FIELDS("EXTERNAL_UNIT_NUMBER") %>" Size="5" Maxlength="5">
</Td>
<Td BgColor="#00ff00">Safety Stock</Td>
<Td ColSpan="2">
<% IF REC_CHANGE.FIELDS("SAFETY_STOCK") = " " THEN %>
 <Input Type="text" Name="safety_stock" Value="1" Size="9" Maxlength="9">
<% ELSE %>
 <Input Type="text" Name="safety_stock" Value="<%= REC_CHANGE.FIELDS("SAFETY_STOCK") %>" Size="9" Maxlength="9">
<% END IF %>
</Td>
</Tr>
<Tr>
<!-- 
<Td BgColor="#00ff00">Unit of Quantity</Td>
<Td><Select Name="uom_q" Size="1">
<Option Value="">
<%
'------------------
' unit of quantity get
'------------------
'SQL_uoq = "SELECT  UNIT_CODE, UNIT " & _
'	 " FROM UNIT " & _
'	 " WHERE DELETE_TYPE IS NULL AND" & _
'	 "       USE = 'QUANTITY'" & _
'	 " ORDER BY UNIT "
'Set Rec_uoq = DB.Execute(SQL_uoq)
'IF Rec_uoq.eof or Rec_uoq.bof then %>
 <Option Value="">NO DATA FOUND
<%' ELSE
 'DO While not Rec_uoq.EOF %>
 <%' IF CLng(REC_CHANGE.FIELDS("UOM_Q")) = CLng(Rec_uoq.Fields("UNIT_CODE")) THEN %>
  <Option Value="<%'= Rec_uoq.Fields("UNIT_CODE") %>" SELECTED><%'= Rec_uoq.Fields("UNIT") %>
 <%' ELSE %>
  <Option Value="<%'= Rec_uoq.Fields("UNIT_CODE") %>"><%'= Rec_uoq.Fields("UNIT") %>
 <%' END IF %>
<%' Rec_uoq.Movenext
'LOOP %>
<%' END IF %>
</Select></Td>
 -->
<Td BgColor="#00ff00">Unit Stock</Td>
<Td ColSpan="2"><Select Name="unit_stock" Size="1">
<Option Value="">
<%
'------------------
' unit stock get
'------------------
SQL_unit_stock = "SELECT  UNIT_CODE, UNIT " & _
	 " FROM UNIT " & _
	 " WHERE DELETE_TYPE IS NULL AND" & _
	 "       USE = 'QUANTITY'" & _
	 " ORDER BY UNIT "
Set Rec_unit_stock = DB.Execute(SQL_unit_stock)
IF Rec_unit_stock.eof or Rec_unit_stock.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_unit_stock.EOF %>
 <% IF CStr(REC_CHANGE.FIELDS("UNIT_STOCK") & "") = CStr(Rec_unit_stock.Fields("UNIT_CODE") & "") THEN %>
  <Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>" SELECTED><%= Rec_unit_stock.Fields("UNIT") %>
 <% ELSE %>
  <Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>"><%= Rec_unit_stock.Fields("UNIT") %>
 <% END IF %>
<% Rec_unit_stock.Movenext
LOOP %>
<% END IF %>
</Select>
</Td>
<Td BgColor="#00ff00">Unit of Engineering</Td>
<Td ColSpan="2"><Select Name="unit_engineering" Size="1">
<Option Value="">
<%
'------------------
' unit of quantity get
'------------------
SQL_unit_eng = "SELECT  UNIT_CODE, UNIT " & _
	 " FROM UNIT " & _
	 " WHERE DELETE_TYPE IS NULL AND" & _
	 "       USE = 'QUANTITY'" & _
	 " ORDER BY UNIT "
Set Rec_unit_eng = DB.Execute(SQL_unit_eng)
IF Rec_unit_eng.eof or Rec_unit_eng.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_unit_eng.EOF %>
 <% IF CStr(REC_CHANGE.FIELDS("UNIT_ENGINEERING") & "") = CStr(Rec_unit_eng.Fields("UNIT_CODE") & "") THEN %>
  <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>" SELECTED><%= Rec_unit_eng.Fields("UNIT") %>
 <% ELSE %>
  <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>"><%= Rec_unit_eng.Fields("UNIT") %>
 <% END IF %>
<% Rec_unit_eng.Movenext
LOOP %>
<% END IF %>
</Select></Td>
</Tr>
<Tr>
<Td ColSpan="3" BgColor="#00FF00">
<Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
</Td>
<Td ColSpan="3">
<Input Type="text" Name="unit_stock_rate" Value="<%= REC_CHANGE.FIELDS("UNIT_STOCK_RATE") %>" Size="6" Maxlength="6">
 :
<Input Type="text" Name="unit_engineer_rate" Value="<%= REC_CHANGE.FIELDS("UNIT_ENGINEER_RATE") %>" Size="6" Maxlength="6">
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Weight</Td>
<Td>
<Input Type="text" Name="weight" Value="<%= REC_CHANGE.FIELDS("WEIGHT") %>" Size="12" Maxlength="12">
</Td>
<Td BgColor="#00ff00">Unit of Weight</Td>
<Td><Select Name="uom_w" Size="1">
<Option Value="">
<%
'------------------
' unit of weight get
'------------------
SQL_uow = "SELECT  UNIT_CODE, UNIT " & _
	 " FROM UNIT " & _
	 " WHERE DELETE_TYPE IS NULL AND" & _
	 "       USE = 'WEIGHT'" & _
	 " ORDER BY UNIT "
Set Rec_uow = DB.Execute(SQL_uow)
IF Rec_uow.eof or Rec_uow.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_uow.EOF %>
 <% IF CStr(REC_CHANGE.FIELDS("UOM_W") &"") = CStr(Rec_uow.Fields("UNIT_CODE") & "") THEN %>
  <Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>" SELECTED><%= Rec_uow.Fields("UNIT") %>
 <% ELSE %>
  <Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>"><%= Rec_uow.Fields("UNIT") %>
 <% END IF %>
<% Rec_uow.Movenext
LOOP %>
<% END IF %>
</Select></Td>
<Td BgColor="#00ff00">Unit of Measurement</Td>
<Td><Select Name="uom_l" Size="1">
<Option Value="">
<%
'------------------
' unit of length get
'------------------
SQL_uol = "SELECT  UNIT_CODE, UNIT " & _
	 " FROM UNIT " & _
	 " WHERE DELETE_TYPE IS NULL AND" & _
	 "       USE = 'LENGTH'" & _
	 " ORDER BY UNIT "
Set Rec_uol = DB.Execute(SQL_uol)
IF Rec_uol.eof or Rec_uol.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_uol.EOF %>
 <% IF CStr(REC_CHANGE.FIELDS("UOM_L") &"") = CStr(Rec_uol.Fields("UNIT_CODE") &"") THEN %>
  <Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>" SELECTED><%= Rec_uol.Fields("UNIT") %>
 <% ELSE %>
  <Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>"><%= Rec_uol.Fields("UNIT") %>
 <% END IF %>
<% Rec_uol.Movenext
LOOP %>
<% END IF %>
</Select></Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">DRAWING NO</Td>
<Td ColSpan="2">
<Input Type="text" Name="drawing_no" Value="<%= REC_CHANGE.FIELDS("DRAWING_NO") %>" Size="15" Maxlength="15">
</Td>
<Td BgColor="#00ff00">DRAWING REV</Td>
<Td ColSpan="2">
<Input Type="text" Name="drawing_rev" Value="<%= REC_CHANGE.FIELDS("DRAWING_REV") %>" Size="1" Maxlength="1">
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">APPLICABLE MODEL</Td>
<Td ColSpan="2">
<Input Type="text" Name="applicable_model" Value="<%= REC_CHANGE.FIELDS("APPLICABLE_MODEL") %>" Size="20" Maxlength="20">
</Td>
<Td BgColor="#00ff00">CATALOG NO</Td>
<Td ColSpan="2">
<Input Type="text" Name="catalog_no" Value="<%= REC_CHANGE.FIELDS("CATALOG_NO") %>" Size="28" Maxlength="28">
</Td>
</Tr>
<% IF CLng(my_type) <> 80 AND CLng(my_company_code) = 926236 THEN %>
 <Input Type="hidden" Name="standard_price" Value="<%= REC_CHANGE.FIELDS("STANDARD_PRICE") %>">
 <Input Type="hidden" Name="next_term_price" Value="<%= REC_CHANGE.FIELDS("NEXT_TERM_PRICE") %>">
 <Input Type="hidden" Name="suppliers_price" Value="<%= REC_CHANGE.FIELDS("SUPPLIERS_PRICE") %>">
<% ELSE %>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Standard Price</Font></Td>
 <Td>
 <Input Type="text" Name="standard_price" Value="<%= REC_CHANGE.FIELDS("STANDARD_PRICE") %>" Size="14" Maxlength="14">
 </Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Next Term Price</Font></Td>
 <Td>
 <Input Type="text" Name="next_term_price" Value="<%= REC_CHANGE.FIELDS("NEXT_TERM_PRICE") %>" Size="14" Maxlength="14">
 </Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Suppliers Price</Font></Td>
 <Td>
 <Input Type="text" Name="suppliers_price" Value="<%= REC_CHANGE.FIELDS("SUPPLIERS_PRICE") %>" Size="14" Maxlength="14">
 </Td>
 </Tr>
<% END IF%>
<Tr>
<Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Lead Time</Font></Td>
<Td>
<Input Type="text" Name="manufact_leadtime" Value="<%= REC_CHANGE.FIELDS("MANUFACT_LEADTIME") %>" Size="3" Maxlength="3">
Days</Td>
<Td BgColor="#00ff00"><Font Color="#0000ff">Purchase Lead Time</Font></Td>
<Td>
<Input Type="text" Name="purchase_leadtime" Value="<%= REC_CHANGE.FIELDS("PURCHASE_LEADTIME") %>" Size="3" Maxlength="3">
Days</Td>
<Td BgColor="#00ff00"><Font Color="#0000ff">Adjustment Lead Time</Font></Td>
<Td>
<Input Type="text" Name="adjustment_leadtime" Value="<%= REC_CHANGE.FIELDS("ADJUSTMENT_LEADTIME") %>" Size="3" Maxlength="3">
Days</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Issue Policy</Td>
<Td><Select Name="issue_policy" Size="1">
  <Option Value="">
  <%
  '*********************
  ' ISSUE POLICY GET
  '*********************
  SQL_ISSUEPOLICY = "SELECT ISSUE_POLICY, POLICY_NAME " & _
                    "  FROM ISSUEPOLICY " & _
                    " ORDER BY POLICY_NAME "
  SET REC_ISSUEPOLICY = DB.EXECUTE(SQL_ISSUEPOLICY)
  IF REC_ISSUEPOLICY.EOF OR REC_ISSUEPOLICY.BOF THEN %>
   <Option Value="">NO DATA FOUND
<%ELSE
   DO WHILE NOT REC_ISSUEPOLICY.EOF
    IF CStr(REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")) = CStr(REC_CHANGE.FIELDS("ISSUE_POLICY")) THEN %>
     <Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>" SELECTED><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%>
  <%ELSE%>
    <Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>"><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%>
 <% END IF
    REC_ISSUEPOLICY.MOVENEXT
    LOOP
  END IF %>
 </Select>
</Td>
<Td BgColor="#00ff00"><Font Color="blue">Issue Lot</Font></Td>
<Td><Input Type="text" Name="issue_lot" Value="<%= REC_CHANGE.FIELDS("ISSUE_LOT") %>" Size="9" Maxlength="9"></Td>
<Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Fail Rate</Font></Td>
<Td>
<Input Type="text" Name="manufact_fail_rate" Value="<%= REC_CHANGE.FIELDS("MANUFACT_FAIL_RATE") %>" Size="3" Maxlength="3">
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Section Code</Td>
<Td ColSpan="2"><Select Name="section_code" Size="1">
<Option Value="">
<%
'------------------
' section code get
'------------------
SQL_section = "SELECT  SECTION_CODE, SHORT_NAME " & _
	 " FROM SECTION " & _
	 " ORDER BY SHORT_NAME "
Set Rec_section = DB.Execute(SQL_section)
IF Rec_section.eof or Rec_section.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_section.EOF %>
 <% IF CStr(REC_CHANGE.FIELDS("SECTION_CODE") &"") = CStr(Rec_section.Fields("SECTION_CODE") &"") THEN %>
  <Option Value="<%= Rec_section.Fields("SECTION_CODE") %>" SELECTED><%= Rec_section.Fields("SHORT_NAME") %>
 <% ELSE %>
  <Option Value="<%= Rec_section.Fields("SECTION_CODE") %>"><%= Rec_section.Fields("SHORT_NAME") %>
 <% END IF %>
<% Rec_section.Movenext
LOOP %>
<% END IF %>
</Select>
</Td>
<Td BgColor="#00ff00">Stock Subject Code</Td>
<Td ColSpan="2"><Select Name="stock_subject_code" Size="1">
<Option Value="">
<%
'------------------------
' stock subject code get
'------------------------
SQL_stocksubject = "SELECT  STOCK_SUBJECT_CODE, STOCK_SUBJECT " & _
	 " FROM STOCK_SUBJECT " & _
	 " ORDER BY STOCK_SUBJECT "
Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
IF Rec_stocksubject.eof or Rec_stocksubject.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_stocksubject.EOF %>
 <% IF REC_CHANGE.FIELDS("STOCK_SUBJECT_CODE") = Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") THEN %>
  <Option Value="<%= Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") %>" SELECTED><%= Rec_stocksubject.Fields("STOCK_SUBJECT") %>
 <% ELSE %>
  <Option Value="<%= Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") %>"><%= Rec_stocksubject.Fields("STOCK_SUBJECT") %>
 <% END IF %>
<% Rec_stocksubject.Movenext
LOOP %>
<% END IF %>
</Select></Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Cost Process Code</Td>
<Td ColSpan="2"><Select Name="cost_process_code" Size="1">
<Option Value="">
<%
'------------------
' cost process code get
'------------------
SQL_costprocess = "SELECT  COST_PROCESS_CODE, COST_PROCESS_NAME " & _
	 " FROM COSTPROCESS " & _
	 " ORDER BY COST_PROCESS_NAME "
Set Rec_costprocess = DB.Execute(SQL_costprocess)
IF Rec_costprocess.eof or Rec_costprocess.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_costprocess.EOF %>
 <% IF REC_CHANGE.FIELDS("COST_PROCESS_CODE") = Rec_costprocess.Fields("COST_PROCESS_CODE") THEN %>
  <Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>" SELECTED><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>
 <% ELSE %>
  <Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>"><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>
 <% END IF %>
<% Rec_costprocess.Movenext
LOOP %>
<% END IF %>
</Select>
</Td>
<Td BgColor="#00ff00">Cost Subject Code</Td>
<Td ColSpan="2"><Select Name="cost_subject_code" Size="1">
<Option Value="">
<%
'------------------
' cost subject code get
'------------------
SQL_costsubject = "SELECT  COST_SUBJECT_CODE, COST_SUBJECT_NAME " & _
	 " FROM COSTSUBJECT " & _
	 " ORDER BY COST_SUBJECT_NAME "
Set Rec_costsubject = DB.Execute(SQL_costsubject)
IF Rec_costsubject.eof or Rec_costsubject.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not Rec_costsubject.EOF %>
 <% IF REC_CHANGE.FIELDS("COST_SUBJECT_CODE") = Rec_costsubject.Fields("COST_SUBJECT_CODE") THEN %>
  <Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>" SELECTED><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>
 <% ELSE %>
  <Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>"><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>
 <% END IF %>
<% Rec_costsubject.Movenext
LOOP %>
<% END IF %>
</Select>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Order Policy</Td>
<Td ColSpan="5"><Select Name="order_policy" Size="1">
<Option Value="">
<%
'********************
' order policy get
'********************
SQL_ORDER_POLICY = "SELECT ORDER_POLICY, POLICY_NAME " & _
                   "  FROM ORDER_POLICY " & _
                   " ORDER BY POLICY_NAME "
SET REC_ORDER_POLICY = DB.EXECUTE(SQL_ORDER_POLICY)
IF REC_ORDER_POLICY.EOF OR REC_ORDER_POLICY.BOF THEN %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO WHILE NOT REC_ORDER_POLICY.EOF
  IF CStr(REC_ORDER_POLICY.FIELDS("ORDER_POLICY")) = CStr(REC_CHANGE.FIELDS("ORDER_POLICY")) THEN %>
   <Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY") %>" SELECTED><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME") %>
<%ELSE%>
   <Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY") %>"><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME") %>
<%END IF%>
<% REC_ORDER_POLICY.MOVENEXT
 LOOP
END IF %>
</Select></Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Maker Flag</Td>
<Td ColSpan="2"><Select Name="maker_flag" Size="1">
 <Option Value="">
<%
'*************************
'maker flag
'*************************
SQL_MAKERFLAG = "SELECT nvl(MAKER_FLAG_CODE,0) MAKER_FLAG_CODE, MAKER_FLAG  " & _
                "FROM MAKERFLAG " & _
                "ORDER BY MAKER_FLAG "
SET REC_MAKERFLAG = DB.EXECUTE(SQL_MAKERFLAG)
IF REC_MAKERFLAG.eof or REC_MAKERFLAG.bof then %>
 <Option Value="">NO DATA FOUND
<% ELSE
 DO While not REC_MAKERFLAG.EOF %>
 <% IF CLng(REC_CHANGE.FIELDS("MAKER_FLAG")) = CLng(REC_MAKERFLAG.FIELDS("MAKER_FLAG_CODE")) THEN %>
  <Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>" SELECTED ><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %>
 <% ELSE %>
  <Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>"><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %>
 <% END IF %>
<% REC_MAKERFLAG.Movenext
LOOP %>
<% END IF %>
</Select>
</Td>
<Td BgColor="#00ff00">Maker</Td>
<Td ColSpan="2">
<Input Type="text" Name="mak" Value="<%= REC_CHANGE.FIELDS("MAK") %>" Size="30" Maxlength="30">
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Type1</Td>
<Td ColSpan="2">
<Input Type="text" Name="item_type1" Value="<%= REC_CHANGE.FIELDS("ITEM_TYPE1") %>" Size="30" Maxlength="30">
  <A HREF="javascript:setITYPE1()">SET</A>
</Td>
<Td BgColor="#00ff00">Item Type2</Td>
<Td ColSpan="2">
<Input Type="text" Name="item_type2" Value="<%= REC_CHANGE.FIELDS("ITEM_TYPE2") %>" Size="18" Maxlength="18">
  <A HREF="javascript:setITYPE2()">SET</A>
</Td>
</Tr>
<!-- 
<Tr>
 <Td BgColor="#00ff00">Supplier Code</Td>
<Td ColSpan="5"><Input Type="text" Name="supplier_code" Size="8" Maxlength="8"></Td>
</Tr>
 -->
<!-- <Tr>
<Td BgColor="#00ff00">Reorder Point</Td>
<Td ColSpan="2"><Input Type="text" Name="reorder_point" Size="9" Maxlength="9"></Td>
<Td BgColor="#00ff00">Llc Code</Td>
<Td ColSpan="2"><Input Type="text" Name="llc_code" Size="3" Maxlength="3"></Td>
</Tr> -->
<% END IF %>
<!-- UPDATE END -->

<!-- DELETE OR DELETED START -->
<% IF REQUEST("type") = "DELETE" OR _
 (REQUEST("type") = "CHANGE" AND REQUEST("delete_type") = "D") THEN %>
<% SQL_DELETE = "SELECT ITEM, ITEM_CODE, ITEM_FLAG, " & _
                "ORIGIN_CODE, DESCRIPTION, nvl(CLASS_CODE,0) CLASS_CODE, " & _
                "UOM_W, UOM_L, " & _
                "EXTERNAL_UNIT_NUMBER, STANDARD_PRICE, NEXT_TERM_PRICE, " & _
                "SUPPLIERS_PRICE, CURR_CODE, WEIGHT, " & _
                "STOCK_SUBJECT_CODE, COST_SUBJECT_CODE, COST_PROCESS_CODE, " & _
                "MANUFACT_LEADTIME, PURCHASE_LEADTIME, ADJUSTMENT_LEADTIME, " & _
                "NVL(ISSUE_POLICY, ' ') ISSUE_POLICY, SECTION_CODE, MANUFACT_FAIL_RATE, " & _
                "NVL(MAKER_FLAG,0) MAKER_FLAG, UNIT_STOCK, UNIT_STOCK_RATE, " & _
                "ISSUE_LOT, DRAWING_NO, DRAWING_REV, " & _
                "APPLICABLE_MODEL, CATALOG_NO, SAFETY_STOCK, " & _
                "NVL(ORDER_POLICY, ' ') ORDER_POLICY, MAK, " & _
                "UNIT_ENGINEERING, UNIT_ENGINEER_RATE, " & _
                "ITEM_TYPE1, ITEM_TYPE2 " & _
                "FROM ITEM " & _
                "WHERE ITEM_NO = " & CLng(REQUEST("ITEM_NO"))
   SET REC_DEL = DB.EXECUTE(SQL_DELETE) %>
<Tr>
<Td BgColor="#00FF00">Item No</Td>
<Td ColSpan="2">
<%= REQUEST("ITEM_NO") %>
<Input Type="hidden" Name="item_no" Value="<%= REQUEST("ITEM_NO") %>"></Td>
<Td BgColor="#00ff00">Item Code</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("ITEM_CODE") %>
<Input Type="hidden" Name="item_code" Value="<%= REC_DEL.FIELDS("ITEM_CODE") %>">
&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Name</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("ITEM") %>
<Input Type="hidden" Name="item" Value="<%= REC_DEL.FIELDS("ITEM") %>">
</Td>
<Td BgColor="#00ff00">Item Flag</Td>
<Td ColSpan="2">
<%
 IF CStr(REC_DEL.FIELDS("ITEM_FLAG")) <> "" THEN
  '*********************
  ' item flag get
  '*********************
  SQL_ITEMFLAG = "SELECT FLAG_NAME " & _
                 "  FROM ITEMFLAG " & _
                 " WHERE ITEM_FLAG = '" & CStr(REC_DEL.FIELDS("ITEM_FLAG")) & "' "
  SET REC_ITEMFLAG = DB.EXECUTE(SQL_ITEMFLAG)
  IF REC_ITEMFLAG.EOF OR REC_ITEMFLAG.BOF THEN %>
   DATA NOT FOUND
 <%ELSE
    RESPONSE.WRITE REC_ITEMFLAG.FIELDS("FLAG_NAME")
  END IF
 END IF %>
<Input Type="hidden" Name="item_flag" Value="<%= REC_DEL.FIELDS("ITEM_FLAG") %>">
&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Description</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("DESCRIPTION") %>
<Input Type="hidden" Name="description" Value="<%= REC_DEL.FIELDS("DESCRIPTION") %>">
&nbsp;</Td>
<Td BgColor="#00ff00">Class Code</Td>
<Td ColSpan="2" >
<%
'------------------
' class get
'------------------
SQL_class = "SELECT CLASS_1||CLASS_2||CLASS_3 AS CLASS " & _
	 " FROM CLASS " & _
	 " WHERE CLASS_CODE ='" & REC_DEL.FIELDS("CLASS_CODE") & "'"
Set Rec_class = DB.Execute(SQL_class)
IF Rec_class.eof or Rec_class.bof then %>
<% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
<%= Rec_class.Fields("CLASS") %>
<Input Type="hidden" Name="class_code" Value="<%= REC_DEL.FIELDS("CLASS_CODE") %>">
<% END IF %>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Origin Code</Td>
<Td ColSpan="2">
<%
'------------------
' country code get
'------------------
SQL_country = "SELECT COUNTRY " & _
	 " FROM COUNTRY " & _
	 " WHERE DELETE_TYPE IS NULL AND " & _
	 "       COUNTRY_CODE = " & REC_DEL.FIELDS("ORIGIN_CODE")
Set Rec_country = DB.Execute(SQL_country)
IF Rec_country.eof or Rec_country.bof then %>
 <% RESPONSE.WRITE "DATA NOT FOUND" %>
<% ELSE %>
 <% RESPONSE.WRITE REC_COUNTRY.FIELDS("COUNTRY") %>
 <Input Type="hidden" Name="origin_code" Value="<%= REC_DEL.FIELDS("ORIGIN_CODE") %>">
<% END IF %>
</Td>
<Td BgColor="#00ff00">Curr Code</Td>
<Td ColSpan="2">
<%
'------------------
' currency code get
'------------------
SQLcurrency = "SELECT  CURR_MARK " & _
	 " FROM CURRENCY " & _
	 " WHERE DELETE_TYPE IS NULL AND " & _
	 " CURR_CODE = " & REC_DEL.FIELDS("CURR_CODE")
Set Reccurrency = DB.Execute(SQLcurrency)
IF Reccurrency.eof or Reccurrency.bof then %>
 <% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
 <%= Reccurrency.Fields("CURR_MARK") %>
 <Input Type="hidden" Name="curr_code" Value="<%= REC_DEL.FIELDS("CURR_CODE") %>">
<% END IF %>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">External Unit Number</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("EXTERNAL_UNIT_NUMBER") %>
<Input Type="hidden" Name="external_unit_number" Value="<%= REC_DEL.FIELDS("EXTERNAL_UNIT_NUMBER") %>">
&nbsp;</Td>
<Td BgColor="#00ff00">Safety Stock</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("SAFETY_STOCK") %>
<Input Type="hidden" Name="safety_stock" Value="<%= REC_DEL.FIELDS("SAFETY_STOCK") %>">
&nbsp;</Td>
</Tr>
<Tr>
<!-- 
<Td BgColor="#00ff00">Unit of Quantity</Td>
<Td>
<%
'------------------
' unit of quantity get
'------------------
'SQL_uoq = "SELECT  UNIT " & _
'	 " FROM UNIT " & _
'	 " WHERE DELETE_TYPE IS NULL AND" & _
'	 "       USE = 'QUANTITY' AND " & _
'	 " UNIT_CODE = " & REC_DEL.FIELDS("UOM_Q")
'Set Rec_uoq = DB.Execute(SQL_uoq)
'IF Rec_uoq.eof or Rec_uoq.bof then %>
 <%' RESPONSE.WRITE "NO DATA FOUND" %>
<%' ELSE %>
 <%'= Rec_uoq.Fields("UNIT") %>
 <Input Type="hidden" Name="uom_q" Value="<%'= REC_DEL.FIELDS("UOM_Q") %>">
<%' END IF %>
</Td>
 -->
<Td BgColor="#00ff00">Unit Stock</Td>
<Td ColSpan="2">
<%
'------------------
' unit stock get
'------------------
SQL_unit_stock = "SELECT  UNIT " & _
	 " FROM UNIT " & _
	 " WHERE DELETE_TYPE IS NULL AND " & _
	 "       USE = 'QUANTITY' AND " & _
	 "       UNIT_CODE = " & CLng(REC_DEL.FIELDS("UNIT_STOCK")) & _
	 " ORDER BY UNIT "
Set Rec_unit_stock = DB.Execute(SQL_unit_stock)
IF Rec_unit_stock.eof or Rec_unit_stock.bof then %>
 NO DATA FOUND
<% ELSE
 RESPONSE.WRITE REC_UNIT_STOCK.FIELDS("UNIT") %>
 <Input Type="hidden" Name="unit_stock" Value="<%= REC_DEL.FIELDS("UNIT_STOCK") %>">
<% END IF %>
</Td>
<Td BgColor="#00ff00">Unit of Engineering</Td>
<Td ColSpan="2">
<%
'------------------
' unit of quantity get
'------------------
SQL_unit_eng = "SELECT  UNIT " & _
	 " FROM UNIT " & _
	 " WHERE DELETE_TYPE IS NULL AND" & _
	 "       USE = 'QUANTITY' AND " & _
	 " UNIT_CODE = " & REC_DEL.FIELDS("UNIT_ENGINEERING")
Set Rec_unit_eng = DB.Execute(SQL_unit_eng)
IF Rec_unit_eng.eof or Rec_unit_eng.bof then %>
 <% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
 <%= Rec_unit_eng.Fields("UNIT") %>
 <Input Type="hidden" Name="unit_engineering" Value="<%= REC_DEL.FIELDS("UNIT_ENGINEERING") %>">
<% END IF %>
</Td>
</Tr>
<Tr>
<Td ColSpan="3" BgColor="#00FF00">
<Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
</Td>
<Td ColSpan="3">
<% RESPONSE.WRITE REC_DEL.FIELDS("UNIT_STOCK_RATE") %>
<Input Type="hidden" Name="unit_stock_rate" Value="<%= REC_DEL.FIELDS("UNIT_STOCK_RATE") %>" Size="6" Maxlength="6">
:
<% RESPONSE.WRITE REC_DEL.FIELDS("UNIT_ENGINEER_RATE") %>
<Input Type="hidden" Name="unit_engineer_rate" Value="<%= REC_DEL.FIELDS("UNIT_ENGINEER_RATE") %>" Size="6" Maxlength="6">
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Weight</Td>
<Td>
<%= REC_DEL.FIELDS("WEIGHT") %>
<Input Type="hidden" Name="weight" Value="<%= REC_DEL.FIELDS("WEIGHT") %>">
&nbsp;</Td>
<Td BgColor="#00ff00">Unit of Weight</Td>
<Td>
<%
'------------------
' unit of weight get
'------------------
SQL_uow = "SELECT  UNIT " & _
	 " FROM UNIT " & _
	 " WHERE DELETE_TYPE IS NULL AND" & _
	 "       USE = 'WEIGHT' AND " & _
	 " UNIT_CODE = " & REC_DEL.FIELDS("UOM_W")
Set Rec_uow = DB.Execute(SQL_uow)
IF Rec_uow.eof or Rec_uow.bof then %>
 <% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
 <%= Rec_uow.Fields("UNIT") %>
 <Input Type="hidden" Name="uom_w" Value="<%= REC_DEL.FIELDS("UOM_W") %>">
<% END IF %>
</Td>
<Td BgColor="#00ff00">Unit of Measurement</Td>
<Td>
<%
'------------------
' unit of length get
'------------------
SQL_uol = "SELECT  UNIT " & _
	 " FROM UNIT " & _
	 " WHERE DELETE_TYPE IS NULL AND" & _
	 "       USE = 'LENGTH' AND " & _
	 " UNIT_CODE = " & REC_DEL.FIELDS("UOM_L")
Set Rec_uol = DB.Execute(SQL_uol)
IF Rec_uol.eof or Rec_uol.bof then %>
 <% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
 <%= Rec_uol.Fields("UNIT") %>
 <Input Type="hidden" Name="uom_l" Value="<%= REC_DEL.FIELDS("UOM_L") %>">
<% END IF %>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">DRAWING NO</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("DRAWING_NO") %>
<Input Type="hidden" Name="drawing_no" Value="<%= REC_DEL.FIELDS("DRAWING_NO") %>" Size="15" Maxlength="15">
&nbsp;</Td>
<Td BgColor="#00ff00">DRAWING REV</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("DRAWING_REV") %>
<Input Type="hidden" Name="drawing_rev" Value="<%= REC_DEL.FIELDS("DRAWING_REV") %>" Size="1" Maxlength="1">
&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">APPLICABLE MODEL</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("APPLICABLE_MODEL") %>
<Input Type="hidden" Name="applicable_model" Value="<%= REC_DEL.FIELDS("APPLICABLE_MODEL") %>" Size="20" Maxlength="20">
&nbsp;</Td>
<Td BgColor="#00ff00">CATALOG NO</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("CATALOG_NO") %>
<Input Type="hidden" Name="catalog_no" Value="<%= REC_DEL.FIELDS("CATALOG_NO") %>" Size="28" Maxlength="28">
&nbsp;</Td>
</Tr>
<% IF CLng(my_type) <> 80 AND CLng(my_company_code) = 926236 THEN %>
 <Input Type="hidden" Name="standard_price" Value="<%= REC_DEL.FIELDS("STANDARD_PRICE") %>">
 <Input Type="hidden" Name="next_term_price" Value="<%= REC_DEL.FIELDS("NEXT_TERM_PRICE") %>">
 <Input Type="hidden" Name="suppliers_price" Value="<%= REC_DEL.FIELDS("SUPPLIERS_PRICE") %>">
<% ELSE %>
 <Tr>
 <Td BgColor="#00ff00">Standard Price</Td>
 <Td>
 <%= REC_DEL.FIELDS("STANDARD_PRICE") %>
 <Input Type="hidden" Name="standard_price" Value="<%= REC_DEL.FIELDS("STANDARD_PRICE") %>">
 </Td>
 <Td BgColor="#00ff00">Next Term Price</Td>
 <Td>
 <%= REC_DEL.FIELDS("NEXT_TERM_PRICE") %>
 <Input Type="hidden" Name="next_term_price" Value="<%= REC_DEL.FIELDS("NEXT_TERM_PRICE") %>">
 &nbsp;</Td>
 <Td BgColor="#00ff00">Suppliers Price</Td>
 <Td>
 <%= REC_DEL.FIELDS("SUPPLIERS_PRICE") %>
 <Input Type="hidden" Name="suppliers_price" Value="<%= REC_DEL.FIELDS("SUPPLIERS_PRICE") %>">
 &nbsp;</Td>
 </Tr>
<% END IF%>
<Tr>
<Td BgColor="#00ff00">Manufact Lead Time</Td>
<Td>
<%= REC_DEL.FIELDS("MANUFACT_LEADTIME") %>
<Input Type="hidden" Name="manufact_leadtime" Value="<%= REC_DEL.FIELDS("MANUFACT_LEADTIME") %>">
&nbsp;Days</Td>
<Td BgColor="#00ff00">Purchase Lead Time</Td>
<Td>
<%= REC_DEL.FIELDS("PURCHASE_LEADTIME") %>
<Input Type="hidden" Name="purchase_leadtime" Value="<%= REC_DEL.FIELDS("PURCHASE_LEADTIME") %>">
&nbsp;Days</Td>
<Td BgColor="#00ff00">Adjustment Lead Time</Td>
<Td>
<%= REC_DEL.FIELDS("ADJUSTMENT_LEADTIME") %>
<Input Type="hidden" Name="adjustment_leadtime" Value="<%= REC_DEL.FIELDS("ADJUSTMENT_LEADTIME") %>">
&nbsp;Days</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Issue Policy</Td>
<Td>
<%
 IF CStr(REC_DEL.FIELDS("ISSUE_POLICY")) <> " " THEN
  '*********************
  ' ISSUE POLICY GET
  '*********************
  SQL_ISSUEPOLICY = "SELECT POLICY_NAME " & _
                    "  FROM ISSUEPOLICY " & _
                    " WHERE ISSUE_POLICY = '" & CStr(REC_DEL.FIELDS("ISSUE_POLICY")) & "' "
  SET REC_ISSUEPOLICY = DB.EXECUTE(SQL_ISSUEPOLICY)
  IF REC_ISSUEPOLICY.EOF OR REC_ISSUEPOLICY.BOF THEN %>
   NO DATA FOUND
<%ELSE
   RESPONSE.WRITE REC_ISSUEPOLICY.FIELDS("POLICY_NAME") %>
  <Input Type="hidden" Name="issue_policy" Value="<%= REC_DEL.FIELDS("ISSUE_POLICY") %>">
<%END IF
 ELSE%>
  <Input Type="hidden" Name="issue_policy" Value="">
<%END IF%>
&nbsp;</Td>
<Td BgColor="#00ff00">Issue Lot</Td>
<Td>
<% RESPONSE.WRITE REC_DEL.FIELDS("ISSUE_LOT") %>
<Input Type="hidden" Name="issue_lot" Value="<%= REC_DEL.FIELDS("ISSUE_LOT") %>">
&nbsp;</Td>
<Td BgColor="#00ff00">Manufact Fail Rate</Td>
<Td>
<% RESPONSE.WRITE REC_DEL.FIELDS("MANUFACT_FAIL_RATE") %>
<Input Type="hidden" Name="manufact_fail_rate" Value="<%= REC_DEL.FIELDS("MANUFACT_FAIL_RATE") %>">
&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Section Code</Td>
<Td ColSpan="2">
<%
'------------------
' section code get
'------------------
SQL_section = "SELECT  SHORT_NAME " & _
	 " FROM SECTION " & _
	 " WHERE SECTION_CODE = " & CLng(REC_DEL.FIELDS("SECTION_CODE"))
Set Rec_section = DB.Execute(SQL_section)
IF Rec_section.eof or Rec_section.bof then %>
 <% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
 <%= Rec_section.Fields("SHORT_NAME") %>
 <Input Type="hidden" Name="section_code" Value="<%= REC_DEL.FIELDS("SECTION_CODE") %>">
<% END IF %>
</Td>
<Td BgColor="#00ff00">Stock Subject Code</Td>
<Td ColSpan="2">
<%
'------------------------
' stock subject code get
'------------------------
SQL_stocksubject = "SELECT  STOCK_SUBJECT " & _
	 " FROM STOCK_SUBJECT " & _
	 " WHERE STOCK_SUBJECT_CODE = '" & REC_DEL.FIELDS("STOCK_SUBJECT_CODE") & "' "
Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
IF Rec_stocksubject.eof or Rec_stocksubject.bof then %>
 <% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
 <%= Rec_stocksubject.Fields("STOCK_SUBJECT") %>
 <Input Type="hidden" Name="stock_subject_code" Value="<%= REC_DEL.FIELDS("STOCK_SUBJECT_CODE") %>">
<% END IF %>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Cost Process Code</Td>
<Td ColSpan="2">
<%
'------------------
' cost process code get
'------------------
SQL_costprocess = "SELECT  COST_PROCESS_NAME " & _
	 " FROM COSTPROCESS " & _
	 " WHERE COST_PROCESS_CODE = '" & REC_DEL.FIELDS("COST_PROCESS_CODE") & "' "
Set Rec_costprocess = DB.Execute(SQL_costprocess)
IF Rec_costprocess.eof or Rec_costprocess.bof then %>
 <% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
 <%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>
 <Input Type="hidden" Name="cost_process_code" Value="<%= REC_DEL.FIELDS("COST_PROCESS_CODE") %>">
<% END IF %>
</Td>
<Td BgColor="#00ff00">Cost Subject Code</Td>
<Td ColSpan="2">
<%
'------------------
' cost subject code get
'------------------
SQL_costsubject = "SELECT  COST_SUBJECT_NAME " & _
	 " FROM COSTSUBJECT " & _
	 " WHERE COST_SUBJECT_CODE = '" & REC_DEL.FIELDS("COST_SUBJECT_CODE") & "' "
Set Rec_costsubject = DB.Execute(SQL_costsubject)
IF Rec_costsubject.eof or Rec_costsubject.bof then %>
 <% RESPONSE.WRITE "NO DATA FOUND" %>
<% ELSE %>
 <%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>
 <Input Type="hidden" Name="cost_subject_code" Value="<%= REC_DEL.FIELDS("COST_SUBJECT_CODE") %>">
<% END IF %>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Order Policy</Td>
<Td ColSpan="5">
<%
IF REC_DEL.FIELDS("ORDER_POLICY") <> " " THEN
 '********************
 ' order policy get
 '********************
 SQL_ORDER_POLICY = "SELECT POLICY_NAME " & _
                    "  FROM ORDER_POLICY " & _
                    " WHERE ORDER_POLICY = '" & CStr(REC_DEL.FIELDS("ORDER_POLICY")) & "' "
 SET REC_ORDER_POLICY = DB.EXECUTE(SQL_ORDER_POLICY)
 IF REC_ORDER_POLICY.EOF OR REC_ORDER_POLICY.BOF THEN %>
  NO DATA FOUND
<% ELSE
    RESPONSE.WRITE REC_ORDER_POLICY.FIELDS("POLICY_NAME") %>
   <Input Type="hidden" Name="order_policy" Value="<%= REC_DEL.FIELDS("ORDER_POLICY") %>">
 <%END IF
ELSE %>
 <Input Type="hidden" Name="order_policy" Value="">
<%END IF%>
&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Maker Flag</Td>
<Td ColSpan="2">
<%
IF REC_DEL.FIELDS("MAKER_FLAG") <> 0 THEN
 '*************************
 'maker flag
 '*************************
 SQL_MAKERFLAG = "SELECT MAKER_FLAG " & _
                 "FROM MAKERFLAG " & _
                 "WHERE MAKER_FLAG_CODE = " & CLng(REC_DEL.FIELDS("MAKER_FLAG"))
 SET REC_MAKERFLAG = DB.EXECUTE(SQL_MAKERFLAG)
 IF REC_MAKERFLAG.eof or REC_MAKERFLAG.bof then %>
  NO DATA FOUND
<% ELSE
  RESPONSE.WRITE REC_MAKERFLAG.Fields("MAKER_FLAG")
   END IF %>
 <Input Type="hidden" Name="maker_flag" Value="<%= REC_DEL.FIELDS("MAKER_FLAG")%>">
<%ELSE%>
 <Input Type="hidden" Name="maker_flag" Value="">
<%END IF%>
&nbsp;</Td>
<Td BgColor="#00ff00">Maker</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("MAK") %>
<Input Type="hidden" Name="mak" Value="<%= REC_DEL.FIELDS("MAK") %>">
&nbsp;</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Type1</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("ITEM_TYPE1") %>
<Input Type="hidden" Name="item_type1" Value="<%= REC_DEL.FIELDS("ITEM_TYPE1") %>">
&nbsp;
</Td>
<Td BgColor="#00ff00">Item Type2</Td>
<Td ColSpan="2">
<%= REC_DEL.FIELDS("ITEM_TYPE2") %>
<Input Type="hidden" Name="item_type2" Value="<%= REC_DEL.FIELDS("ITEM_TYPE2") %>">
&nbsp;
</Td>
</Tr>
<!-- 
<Tr>
 <Td BgColor="#00ff00">Supplier Code</Td>
<Td ColSpan="5"><Input Type="text" Name="supplier_code" Size="8" Maxlength="8"></Td>
</Tr>
 -->
<!-- <Tr>
<Td BgColor="#00ff00">Reorder Point</Td>
<Td ColSpan="2"><Input Type="text" Name="reorder_point" Size="9" Maxlength="9"></Td>
<Td BgColor="#00ff00">Llc Code</Td>
<Td ColSpan="2"><Input Type="text" Name="llc_code" Size="3" Maxlength="3"></Td>
</Tr> -->
<% END IF %>
<!-- DELETE END -->
</Table>

</Form>

</Div>
</Body>
</Html>
<% DB.Close %>
