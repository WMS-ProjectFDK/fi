<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
'-- 2007/01/22 Sayuri Suzuki 購入単価外貨(unit_price_o)、購入単価レート(unit_price_rate)、購入単価通貨コード(unit_curr_code)を追加する。
'-- 2007/04/26 Sayuri Suzuki 購入単価外貨(unit_price_o)、購入単価レート(unit_price_rate)、購入単価通貨コード(unit_curr_code)に初期値をセットする。
'-- 2014/11/18 Y.Hagai    GRADE_CODE,CUSTOMER_TYPE,PACKAGE_TYPEの追加対応
'-- 2015/04/21 NTTk)Hino  項目追加（CAPACITY他 計28項目）、「Package Type」をSelectBoxに変更
'-- 2015/06/12 NTTk)Hino  不具合修正（Packing Information No.変更によるリロードでエラー発生）

KEYWORD   = Request("KEYWORD")

'add 2015/04/21 Start
FLG       = Request.QueryString("FLG")         '動作フラグ(1=PACKING_INFORMATION再読込)

'(i) PackingInformation再読込時
If FLG = "1" Then
	NO_TYPE   = Request.QueryString("NO_TYPE")     '(新規)前画面.ITEM NO TYPE
	STOCK_SUB = Request.QueryString("STOCK_SUB")   '(新規)前画面.STCK_SUBJECT_CODE
	KEY       = Request.QueryString("KEY")         '(変更/削除)前画面.KEY
	SEEK_KEY  = Request.QueryString("SEEK_KEY")    '(変更/削除)前画面.SEEK_KEY
'(ii) PackingInformation再読込時以外
Else
	NO_TYPE   = Request.Form("no_type")            '(新規)前画面.ITEM NO TYPE
	STOCK_SUB = Request.Form("stock_subject_code") '(新規)前画面.STCK_SUBJECT_CODE
	KEY       = Request.Form("key")                '(変更/削除)前画面.KEY
	SEEK_KEY  = Request.Form("seek_key")           '(変更/削除)前画面.SEEK_KEY
End If
'add 2015/04/21 End

  call html_header("Item Entry",KEYWORD)
%>
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
 
 'add 2015/04/21 Start
 If FLG = "1" Then
	 '-------------------------------------
	 'Packing Information 取得
	 '-------------------------------------
	  SQL =  "select PI_NO, PLT_SPEC_NO, PALLET_UNIT_NUMBER, PALLET_CTN_NUMBER, " & _
		     "       PALLET_STEP_CTN_NUMBER, PALLET_HEIGHT, PALLET_WIDTH, PALLET_DEPTH, " & _
		     "       PALLET_SIZE_TYPE " & _
		     "from   PACKING_INFORMATION " & _
		     "where  PI_NO = trim('" & Request.Form("packing_information") & "') "
	  SET REC_PI = DB.Execute(SQL)
 End If
 'add 2015/04/21 End

%>
<!-- add 2015/04/21 Start -->
<%
'----------------------------------------------------
'Value値取得（PackingInformation項目以外の呼出し専用）
'----------------------------------------------------
Function GET_VAL(p_DB_ID, p_FORM_ID)
	GET_VAL = GET_VAL_CMN(p_DB_ID, p_FORM_ID, "")
End Function

'----------------------------------------------------
'Value値取得（共通）
'----------------------------------------------------
Function GET_VAL_CMN(p_DB_ID, p_FORM_ID, isPI)
	
	'(i) PackingInformation再読込時
	If FLG = "1" Then
		
		'PackingInformation項目のみ
		If isPI = "PI"Then
			If p_DB_ID = "PI_NO" Then
				GET_VAL_CMN = Request.Form(p_FORM_ID)
			Else
				'PI再読込
				If Not REC_PI.EOF Then
					GET_VAL_CMN = REC_PI.FIELDS(p_DB_ID)
				Else
					GET_VAL_CMN = ""
				End If
			End If
			
		'PackingInformation項目以外
		Else
			GET_VAL_CMN = Request.Form(p_FORM_ID)
		End If
		
	'(ii) PackingInformation再読込時以外
	Else
		If Request.Form("type") = "NEW" Then
			GET_VAL_CMN = ""
		ElseIf Request.Form("type") = "CHANGE" And Request.Form("delete_type") <> "D" Then
			If Not IsNull(REC_CHANGE.FIELDS(p_DB_ID)) Then
				GET_VAL_CMN = REC_CHANGE.FIELDS(p_DB_ID)
			Else
				GET_VAL_CMN = ""
			End If
		Else
			If Not IsNull(REC_DEL.FIELDS(p_DB_ID)) Then
				GET_VAL_CMN = REC_DEL.FIELDS(p_DB_ID)
			Else
				GET_VAL_CMN = ""
			End If
		End If
	End If
End Function
%>

<SCRIPT>
<!--
// BACKボタン押下処理
function go_back(){
	var URL;
	var type = "<%= Request.Form("type") %>";
	if ( type == "NEW" ) {
		URL = "Item_Main.asp?NO_TYPE=<%= NO_TYPE %>&STOCK_SUB=<%= STOCK_SUB %>";
	} else {
		URL = "Item_Seek.asp?KEY=<%= KEY %>&SEEK_KEY=<%= SEEK_KEY %>";
	}
	document.forms[0].method = "post";
	document.forms[0].action = URL + "&KEYWORD=<%=KEYWORD%>";
	document.forms[0].submit();
}

// External Unit Number の値を、Outer Box(Unit Number) にコピー
function copy(){
	document.forms[0].o_unit_number.value = document.forms[0].external_unit_number.value;
}

// PackingInformation情報取得（リロード）
function get_pack_info(){
	var URL;
	var type = "<%= Request.Form("type") %>";
	if ( type == "NEW" ) {
		URL = "Item_Entry.asp?NO_TYPE=<%= NO_TYPE %>&STOCK_SUB=<%= STOCK_SUB %>";
	} else {
		URL = "Item_Entry.asp?KEY=<%= KEY %>&SEEK_KEY=<%= SEEK_KEY %>";
	}
	document.forms[0].method = "post";
	document.forms[0].action = URL + "&KEYWORD=<%=KEYWORD%>&FLG=1";
	document.forms[0].submit();
}
//-->
</SCRIPT>
<!-- add 2015/04/21 End -->

<Div Align="center">

<Form Method="post" Action="Item_Confirm.asp?KEYWORD=<%=KEYWORD%>" Name="data_send" Target="_self">

<!-- type(new,change,delete get start -->
<Input Type="hidden" Name="type" Value="<%= request("type") %>">
<Input Type="hidden" Name="GRP_FLG" Value="<%= request("GRP_FLG") %>">
<% 'add 2015/04/21 Start %>
<Input Type="hidden" Name="no_type"            Value="<%= NO_TYPE %>">
<Input Type="hidden" Name="key"                Value="<%= KEY %>">
<Input Type="hidden" Name="seek_key"           Value="<%= SEEK_KEY %>">
<% 'add 2015/04/21 End %>

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
     <!--<Input Type="Button" Value="BACK" onClick='history.back()'><BR>-->
     <Input Type="Button" Value="BACK" onClick='go_back()'><BR><!-- mod 2015/04/21 -->
     <% RESPONSE.WRITE "SELECT STOCK SUBJECT."
        RESPONSE.END %>
     </Font>
     </Pre><Br>
 <% END IF %>
 <Input Type="submit" Name="confirm_button" Value="Confirm">
 <!--<Input Type="Button" Value="BACK" onClick='history.back()'><BR>-->
 <Input Type="Button" Value="BACK" onClick='go_back()'><BR><!-- mod 2015/04/21 -->
<% ELSE %>
 <% IF REQUEST("delete_type") = "D" THEN %>
     <Pre>
     <Font Size="5" Color="#ff0000" Face="@ＭＳ ゴシック">
     <!--<Input Type="Button" Value="BACK" onClick='history.back()'><BR>-->
     <Input Type="Button" Value="BACK" onClick='go_back()'><BR><!-- mod 2015/04/21 -->
     <% RESPONSE.WRITE "THIS IS DELETED DATA." %>
     </Font>
     </Pre><Br>
 <% ELSE %>
     <Input Type="submit" Name="confirm_button" Value="Confirm">
     <!--<Input Type="Button" Value="BACK" onClick='history.back()'><BR>-->
     <Input Type="Button" Value="BACK" onClick='go_back()'><BR><!-- mod 2015/04/21 -->
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

  '-- 2007/01/22 Add --
	If Not REC_COMPANY.EOF Then
	   wk_company_country_code   = REC_COMPANY("COUNTRY_CODE")
	   wk_company_curr_code      = REC_COMPANY("CURR_CODE")
	End If
  '-- 2007/01/22 End --
  %>

 <Br>
 If text color is <Font Color="#0000ff">Blue</Font>, Input Number only.
 <Tr>
 <Td BgColor="#00FF00"><Font Color="#0000ff">Item No</Font></Td>
 <Td ColSpan="2">
 <% IF CStr(REQUEST("no_type")) = "GROUP" THEN %>
  <!--<Input Type="text" Name="item_no" Size="8" Maxlength="8">-->
  <Input Type="text" Name="item_no" Size="8" Maxlength="8" value='<%= GET_VAL("ITEM_NO", "item_no") %>'><!-- mod 2015/04/21 -->
 <% If (Session("NGM") = "1") Then %>
  Make an NG Materials <Input Type="checkbox" Name="ngmc" value="on" checked> 
 <% End If %>

 <% ELSEIF CStr(REQUEST("no_type")) = "NGM" THEN %>
  <!--<Input Type="text" Name="item_no" Size="8" Maxlength="8"> NG Materials ITEM NO is 9XXXXXXXX -->
  <Input Type="text" Name="item_no" Size="8" Maxlength="8" value='<%= GET_VAL("ITEM_NO", "item_no") %>'> NG Materials ITEM NO is 9XXXXXXXX<!-- mod 2015/04/21 -->
  <Input Type="hidden" Name="no_type" Value="NGM">
 <% ELSE
     'add 2015/04/21 Start
	 '(i) PackingInformation再読込時
	 If FLG = "1" Then
	   W_ITEM_NO = Request.Form("item_no")
	   
	'(ii) PackingInformation再読込時以外
	 Else
 	 'add 2015/04/21 End
 	   
       '******************
       ' FIX PART GET
       '******************
       SQL_FIX = "SELECT FIX_NO " & _
                 "  FROM ITEM_NO_FIX "
       SET REC_FIX = DB.EXECUTE(SQL_FIX)
       IF REC_FIX.EOF OR REC_FIX.BOF THEN
         W_ITEM_NO = "FIX PART NOT ENTRIED."
       ELSE
         '******************
         ' AUTO NO GET
         '******************
         SQL_SEQUENCE = "SELECT ITEM_NO_SEQ.NEXTVAL AS NEXT" & _
                        "  FROM DUAL "
         SET REC_SEQUENCE = DB.EXECUTE(SQL_SEQUENCE)
         IF REC_SEQUENCE.EOF OR REC_SEQUENCE.BOF THEN
           W_ITEM_NO = "OBJECT ITEM_NO_SEQ NOT CREATED."
         ELSE
           W_ITEM_NO = CStr(REC_FIX.FIELDS("FIX_NO")) & RIGHT("000000" & REC_SEQUENCE.FIELDS("NEXT"), 6)
         END IF
       END IF
       RESPONSE.WRITE W_ITEM_NO
       
	 End If 'add 2015/04/21
     %>
  <Input Type="hidden" Name="item_no" Value="<%= W_ITEM_NO %>">
 <% END IF %>
 </Td>
 <Td BgColor="#00ff00">Item Code</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="item_code" Size="24" Maxlength="24"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="item_code" Size="24" Maxlength="24" value='<%= GET_VAL("ITEM_CODE", "item_code") %>'></Td><!-- mod 2015/04/21 -->
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Item Name</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="item" Size="40" Maxlength="40"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="item" Size="40" Maxlength="40" value='<%= GET_VAL("ITEM", "item") %>'></Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00">Item Flag</Td>
 <Td ColSpan="2">
 <Select Name="item_flag" Size="1">
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
 <!--<Td ColSpan="2"><Input Type="text" Name="description" Size="30" Maxlength="30"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="description" Size="30" Maxlength="30" value='<%= GET_VAL("DESCRIPTION", "description") %>'></Td><!-- mod 2015/04/21 -->
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
 <%
 ELSE
  VAL = GET_VAL("CLASS_CODE", "class_code")  'add 2015/04/21
  
  DO While not Rec_class.EOF
    'add 2015/04/21 Start
    If VAL <> "" Then
      SEL = ""
      If CLng(Rec_class.Fields("CLASS_CODE")) = CLng(VAL) Then
        SEL = "selected"
      End If
    End If
    'add 2015/04/21 End
    %>
    <!--<Option Value="<%= Rec_class.Fields("CLASS_CODE") %>"><%= Rec_class.Fields("CLASS") %>-->
    <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>" <%= SEL %>><%= Rec_class.Fields("CLASS") %><!-- mod 2015/04/21 -->
    <% Rec_class.Movenext
  LOOP %>
 <% END IF %>
 </Select></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Origin Code</Td>
 <Td ColSpan="2">
   <Select Name="origin_code" Size="1">
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
     <%
     ELSE
      VAL = GET_VAL("ORIGIN_CODE", "origin_code")  'add 2015/04/21
      
      DO While not Rec_country.EOF
        'add 2015/04/21 Start
        If VAL <> "" Then
          SEL = ""
          If CLng(Rec_country.Fields("COUNTRY_CODE")) = CLng(VAL) Then
            SEL = "selected"
          End If
          %>
          <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>" <%= SEL %> ><%= Rec_country.Fields("COUNTRY") %>
          <%
        Else
        'add 2015/04/21 End
        %>
        <% IF CLng(REC_COUNTRY.FIELDS("COUNTRY_CODE")) = CLng(REC_COMPANY.FIELDS("COUNTRY_CODE")) THEN %>
          <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>" SELECTED ><%= Rec_country.Fields("COUNTRY") %>
        <% ELSE %>
          <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>"><%= Rec_country.Fields("COUNTRY") %>
        <% END IF %>
        <%
        End If 'add 2015/04/21
        
        Rec_country.Movenext
      LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00">Curr Code</Td>
 <Td ColSpan="2">
 <Select Name="curr_code" Size="1">
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
 <%
 ELSE
  VAL = GET_VAL("CURR_CODE", "curr_code")  'add 2015/04/21
  DO While not Reccurrency.EOF %>
    <%
    'add 2015/04/21 Start
    If VAL <> "" Then
      SEL = ""
      If CLng(Reccurrency.Fields("CURR_CODE")) = CLng(VAL) Then
        SEL = "selected"
      End If
      %>
      <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>" <%= SEL %> ><%= Reccurrency.Fields("CURR_MARK") %>
      <%
    Else
    'add 2015/04/21 End
      
      IF CLng(RECCURRENCY.FIELDS("CURR_CODE")) = CLng(REC_COMPANY.FIELDS("CURR_CODE")) THEN %>
        <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>" SELECTED ><%= Reccurrency.Fields("CURR_MARK") %>
      <%
      ELSE %>
        <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>"><%= Reccurrency.Fields("CURR_MARK") %>
      <%
      END IF 
    End If
    
    Reccurrency.Movenext
  LOOP %>
 <%
 END IF %>
 </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">External Unit Number</Font></Td>
 <!-- <Td ColSpan="2"><Input Type="text" Name="external_unit_number" Size="5" Maxlength="5"></Td> -->
 <Td ColSpan="2"><Input Type="text" Name="external_unit_number" Size="5" Maxlength="5" value='<%= GET_VAL("EXTERNAL_UNIT_NUMBER", "external_unit_number") %>' onChange="copy()"></Td> <!-- mod 2015/04/21 -->
 
 <Td BgColor="#00ff00">Safety Stock</Td>
 <!-- <Td ColSpan="2"><Input Type="text" Name="safety_stock" Size="9" Maxlength="9"></Td> -->
 <Td ColSpan="2"><Input Type="text" Name="safety_stock" Size="9" Maxlength="9" value='<%= GET_VAL("SAFETY_STOCK", "safety_stock") %>'></Td><!-- mod 2015/04/21 -->
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
 <Td ColSpan="2">
   <Select Name="unit_stock" Size="1">
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
       <%
       ELSE
         VAL = GET_VAL("UNIT_STOCK", "unit_stock")  'add 2015/04/21
         DO While not Rec_unit_stock.EOF
           'add 2015/04/21 Start
           If VAL <> "" Then
             SEL = ""
             If CLng(Rec_unit_stock.Fields("UNIT_CODE")) = CLng(VAL) Then
               SEL = "selected"
             End If
           End If
           'add 2015/04/21 End
           %>
           <!--<Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>"><%= Rec_unit_stock.Fields("UNIT") %>-->
           <Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_unit_stock.Fields("UNIT") %><!-- mod 2015/04/21 -->
           <%
           Rec_unit_stock.Movenext
         LOOP %>
       <%
       END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00">Unit of Engineering</Td>
 <Td ColSpan="2">
   <Select Name="unit_engineering" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("UNIT_ENGINEERING", "unit_engineering")  'add 2015/04/21
       DO While not Rec_unit_eng.EOF
           'add 2015/04/21 Start
           If VAL <> "" Then
             SEL = ""
             If CLng(Rec_unit_eng.Fields("UNIT_CODE")) = CLng(VAL) Then
               SEL = "selected"
             End If
           End If
           'add 2015/04/21 End
           %>
         <!-- <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>"><%= Rec_unit_eng.Fields("UNIT") %> -->
         <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_unit_eng.Fields("UNIT") %><!-- mod 2015/04/21 -->
         <% Rec_unit_eng.Movenext
      LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 </Tr>
 <Tr>
 <Td ColSpan="3" BgColor="#00FF00">
 <Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
 </Td>
 <Td ColSpan="3">
 <!--<Input Type="text" Name="unit_stock_rate" Size="6" Maxlength="6">-->
 <Input Type="text" Name="unit_stock_rate" Size="6" Maxlength="6" value='<%= GET_VAL("UNIT_STOCK_RATE", "unit_stock_rate") %>'><!-- mod 2015/04/21 -->
 :
 <!--<Input Type="text" Name="unit_engineer_rate" Size="6" Maxlength="6">-->
 <Input Type="text" Name="unit_engineer_rate" Size="6" Maxlength="6" value='<%= GET_VAL("UNIT_ENGINEER_RATE", "unit_engineer_rate") %>'><!-- mod 2015/04/21 -->
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Weight</Font></Td>
 <!--<Td><Input Type="text" Name="weight" Size="12" Maxlength="12"></Td>-->
 <Td><Input Type="text" Name="weight" Size="12" Maxlength="12" value='<%= GET_VAL("WEIGHT", "weight") %>'></Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00">Unit of Weight</Td>
 <Td>
 <Select Name="uom_w" Size="1">
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
     <%
   ELSE
     VAL = GET_VAL("UOM_W", "uom_w")  'add 2015/04/21
     DO While not Rec_uow.EOF
        'add 2015/04/21 Start
        If VAL <> "" Then
          SEL = ""
          If CLng(Rec_uow.Fields("UNIT_CODE")) = CLng(VAL) Then
            SEL = "selected"
          End If
        End If
        'add 2015/04/21 End
        %>
       <!--<Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>"><%= Rec_uow.Fields("UNIT") %>-->
       <Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_uow.Fields("UNIT") %><!-- mod 2015/04/21 -->
       <% Rec_uow.Movenext
     LOOP %>
   <%
   END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Unit of Measurement</Td>
 <Td>
   <Select Name="uom_l" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("UOM_L", "uom_l")  'add 2015/04/21
       DO While not Rec_uol.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CLng(Rec_uol.Fields("UNIT_CODE")) = CLng(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>"><%= Rec_uol.Fields("UNIT") %>-->
         <Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_uol.Fields("UNIT") %><!-- mod 2015/04/21 -->
         <% Rec_uol.Movenext
       LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">DRAWING NO</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="drawing_no" Size="15" Maxlength="15"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="drawing_no" Size="20" Maxlength="20" value='<%= GET_VAL("DRAWING_NO", "drawing_no") %>'></Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00">DRAWING REV</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="drawing_rev" Size="1" Maxlength="1"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="drawing_rev" Size="1" Maxlength="1" value='<%= GET_VAL("DRAWING_REV", "drawing_rev") %>'></Td><!-- mod 2015/04/21 -->
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">APPLICABLE MODEL</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="applicable_model" Size="20" Maxlength="20"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="applicable_model" Size="20" Maxlength="20" value='<%= GET_VAL("APPLICABLE_MODEL", "applicable_model") %>'></Td>
 <Td BgColor="#00ff00">CATALOG NO</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="catalog_no" Size="28" Maxlength="28"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="catalog_no" Size="28" Maxlength="28" value='<%= GET_VAL("CATALOG_NO", "catalog_no") %>'></Td>
 </Tr>
   <% IF CLng(my_type) <> 80 AND CLng(my_company_code) = 926236 THEN %>
     <Input Type="hidden" Name="standard_price" Value="0">
     <Input Type="hidden" Name="next_term_price" Value="">
     <Input Type="hidden" Name="suppliers_price" Value="">
   <% ELSE %>
     <Tr>
       <Td BgColor="#00ff00"><Font Color="#0000ff">Standard Price</Font></Td>
       <!--<Td><Input Type="text" Name="standard_price" Size="24" Maxlength="24"></Td>-->
       <Td><Input Type="text" Name="standard_price" Size="24" Maxlength="24" value='<%= GET_VAL("STANDARD_PRICE", "standard_price") %>'></Td><!-- mod 2015/04/21 -->
       <Td BgColor="#00ff00"><Font Color="#0000ff">Next Term Price</Font></Td>
       <!--<Td><Input Type="text" Name="next_term_price" Size="24" Maxlength="24"></Td>-->
       <Td><Input Type="text" Name="next_term_price" Size="24" Maxlength="24" value='<%= GET_VAL("NEXT_TERM_PRICE", "next_term_price") %>'></Td><!-- mod 2015/04/21 -->
       <Td BgColor="#00ff00"><Font Color="#0000ff">Suppliers Price</Font></Td>
       <!--<Td><Input Type="text" Name="suppliers_price" Size="24" Maxlength="24"></Td>-->
       <Td><Input Type="text" Name="suppliers_price" Size="24" Maxlength="24" value='<%= GET_VAL("SUPPLIERS_PRICE", "suppliers_price") %>'></Td><!-- mod 2015/04/21 -->
     </Tr>
   <% END IF %>
 
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Lead Time</Font></Td>
 <!--<Td><Input Type="text" Name="manufact_leadtime" Size="3" Maxlength="3">Days</Td>-->
 <Td><Input Type="text" Name="manufact_leadtime" Size="3" Maxlength="3" value='<%= GET_VAL("MANUFACT_LEADTIME", "manufact_leadtime") %>'>Days</Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00"><Font Color="#0000ff">Purchase Lead Time</Font></Td>
 <!--<Td><Input Type="text" Name="purchase_leadtime" Size="3" Maxlength="3">Days</Td>-->
 <Td><Input Type="text" Name="purchase_leadtime" Size="3" Maxlength="3" value='<%= GET_VAL("PURCHASE_LEADTIME", "purchase_leadtime") %>'>Days</Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00"><Font Color="#0000ff">Adjustment Lead Time</Font></Td>
 <!--<Td><Input Type="text" Name="adjustment_leadtime" Size="3" Maxlength="3">Days</Td>-->
 <Td><Input Type="text" Name="adjustment_leadtime" Size="3" Maxlength="3" value='<%= GET_VAL("ADJUSTMENT_LEADTIME", "adjustment_leadtime") %>'>Days</Td><!-- mod 2015/04/21 -->
 </Tr>
 
 <Tr>
 <Td BgColor="#00ff00">Issue Policy</Td>
 <Td>
   <Select Name="issue_policy" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("ISSUE_POLICY", "issue_policy")  'add 2015/04/21
       DO WHILE NOT REC_ISSUEPOLICY.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CStr(REC_ISSUEPOLICY.Fields("ISSUE_POLICY")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>"><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%>-->
         <Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>" <%= SEL %>><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%><!-- mod 2015/04/21 -->
         <% REC_ISSUEPOLICY.MOVENEXT
       LOOP
     END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00"><Font Color="blue">Issue Lot</Font></Td>
 <!--<Td><Input Type="text" Name="issue_lot" Size="9" Maxlength="9"></Td>-->
 <Td><Input Type="text" Name="issue_lot" Size="9" Maxlength="9" value='<%= GET_VAL("ISSUE_LOT", "issue_lot") %>'></Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Fail Rate</Font></Td>
 <!--<Td><Input Type="text" Name="manufact_fail_rate" Size="3" Maxlength="3">%</Td>-->
 <Td><Input Type="text" Name="manufact_fail_rate" Size="3" Maxlength="3" value='<%= GET_VAL("MANUFACT_FAIL_RATE", "manufact_fail_rate") %>'>%</Td><!-- mod 2015/04/21 -->
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Section Code</Td>
 <Td ColSpan="2">
   <Select Name="section_code" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("SECTION_CODE", "section_code")  'add 2015/04/21
       DO While not Rec_section.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           'If Rec_section.Fields("SECTION_CODE") = VAL Then
           If CStr(Rec_section.Fields("SECTION_CODE")) = CStr(VAL) Then 'mod 2015/06/12
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= Rec_section.Fields("SECTION_CODE") %>"><%= Rec_section.Fields("SHORT_NAME") %>-->
         <Option Value="<%= Rec_section.Fields("SECTION_CODE") %>" <%= SEL %>><%= Rec_section.Fields("SHORT_NAME") %><!-- mod 2015/06/12 -->
         <% Rec_section.Movenext
        LOOP %>
     <%
     END IF %>
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
	'mod 2015/04/21 Start
	'SQL_stocksubject = "SELECT STOCK_SUBJECT FROM STOCK_SUBJECT WHERE STOCK_SUBJECT_CODE = " & CLng(REQUEST("stock_subject_code")) & " "
	'Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
	'RESPONSE.WRITE REC_STOCKSUBJECT.FIELDS("STOCK_SUBJECT")
	'RESPONSE.WRITE "<Input Type='hidden' Name='stock_subject_code' Value=" & REQUEST("stock_subject_code") & ">"
	
	SQL_stocksubject = "SELECT STOCK_SUBJECT FROM STOCK_SUBJECT WHERE STOCK_SUBJECT_CODE = " & CLng(STOCK_SUB) & " "
	Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
	RESPONSE.WRITE REC_STOCKSUBJECT.FIELDS("STOCK_SUBJECT")
	RESPONSE.WRITE "<Input Type='hidden' Name='stock_subject_code' Value=" & STOCK_SUB & ">"
	'mod 2015/04/21 End
End If
%>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Cost Process Code</Td>
 <Td ColSpan="2">
   <Select Name="cost_process_code" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("COST_PROCESS_CODE", "cost_process_code")  'add 2015/04/21
       DO While not Rec_costprocess.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CStr(Rec_costprocess.Fields("COST_PROCESS_CODE")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>"><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>-->
         <Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>" <%= SEL %>><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %><!-- mod 2015/04/21 -->
         <% Rec_costprocess.Movenext
       LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00">Cost Subject Code</Td>
 <Td ColSpan="2">
   <Select Name="cost_subject_code" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("COST_SUBJECT_CODE", "cost_subject_code")  'add 2015/04/21
       DO While not Rec_costsubject.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CStr(Rec_costsubject.Fields("COST_SUBJECT_CODE")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>"><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>-->
         <Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>" <%= SEL %>><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %><!-- mod 2015/04/21 -->
         <% Rec_costsubject.Movenext
       LOOP
     END IF %>
   </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Order Policy</Td>
 <Td ColSpan="5">
   <Select Name="order_policy" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("ORDER_POLICY", "order_policy")  'add 2015/04/21
       DO WHILE NOT REC_ORDER_POLICY.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CStr(REC_ORDER_POLICY.Fields("ORDER_POLICY")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
       <!--<Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY")%>"><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME")%>-->
       <Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY")%>" <%= SEL %>><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME")%><!-- mod 2015/04/21 -->
       <% REC_ORDER_POLICY.MOVENEXT
      LOOP
     END IF %>
   </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Maker Flag</Td>
 <Td ColSpan="2">
   <Select Name="maker_flag" Size="1">
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
       <%
     ELSE
       VAL = GET_VAL("MAKER_FLAG", "maker_flag")  'add 2015/04/21
       DO While not REC_MAKERFLAG.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CLng(REC_MAKERFLAG.Fields("MAKER_FLAG_CODE")) = CLng(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>"><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %>-->
         <Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>" <%= SEL %>><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %><!-- mod 2015/04/21 -->
         <% REC_MAKERFLAG.Movenext
       LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00">Maker</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="mak" Size="18" Maxlength="18"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="mak" Size="18" Maxlength="18" value='<%= GET_VAL("MAK", "mak") %>'></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Item Type1</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="item_type1" Size="30" Maxlength="30"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="item_type1" Size="30" Maxlength="30" value='<%= GET_VAL("ITEM_TYPE1", "item_type1") %>'></Td>
 <Td BgColor="#00ff00">Item Type2</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="item_type2" Size="18" Maxlength="18"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="item_type2" Size="18" Maxlength="18" value='<%= GET_VAL("ITEM_TYPE2", "item_type2") %>'></Td>
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

  '-- 2007/01/22 Add --
	If Not REC_COMPANY.EOF Then
	   wk_company_country_code   = REC_COMPANY("COUNTRY_CODE")
	   wk_company_curr_code      = REC_COMPANY("CURR_CODE")
	End If
  '-- 2007/01/22 End --
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
 <!--<Td ColSpan="2"><Input Type="text" Name="item_code" Size="24" Maxlength="24"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="item_code" Size="24" Maxlength="24" value='<%= GET_VAL("ITEM_CODE", "item_code") %>'></Td><!-- mod 2015/04/21 -->
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Item Name</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="item" Size="40" Maxlength="40"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="item" Size="40" Maxlength="40" value='<%= GET_VAL("ITEM", "item") %>'></Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00">Item Flag</Td>
 <Td ColSpan="2">
 <Select Name="item_flag" Size="1">
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
  <%
  ELSE
    VAL = GET_VAL("ITEM_FLAG", "item_flag")  'add 2015/04/21
    DO WHILE NOT REC_ITEMFLAG.EOF
      'add 2015/04/21 Start
      If VAL <> "" Then
        SEL = ""
        If CStr(REC_ITEMFLAG.Fields("ITEM_FLAG")) = CStr(VAL) Then
          SEL = "selected"
        End If
        %>
        <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>" <%= SEL %>><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
        <%
      Else
      'add 2015/04/21 End
        IF CStr(REC_ITEMFLAG.FIELDS("ITEM_FLAG")) = "1" THEN %>
          <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>" SELECTED><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
        <%
        ELSE %>
          <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>"><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
        <%
        END IF
      End If 'add 2015/04/21
      %>
      <% REC_ITEMFLAG.MOVENEXT
    LOOP
  END IF %>
 </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Description</Td>
 <Td ColSpan="2">
 <% RESPONSE.WRITE REQUEST("DESCRIPTION")%>
 <Input Type="hidden" Name="description" Value="<%=REQUEST("DESCRIPTION")%>">
 &nbsp;</Td>
 <Td BgColor="#00ff00">Class Code</Td>
 <Td ColSpan="2">
   <Select Name="class_code" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("CLASS_CODE", "class_code")  'add 2015/04/21
       
       DO While not Rec_class.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CLng(Rec_class.Fields("CLASS_CODE")) = CLng(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= Rec_class.Fields("CLASS_CODE") %>"><%= Rec_class.Fields("CLASS") %>-->
         <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>" <%= SEL %>><%= Rec_class.Fields("CLASS") %><!-- mod 2015/04/21 -->
         <% Rec_class.Movenext
       LOOP
     END IF %>
   </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Origin Code</Td>
 <Td ColSpan="2">
   <Select Name="origin_code" Size="1">
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
     <%
     ELSE
      VAL = GET_VAL("ORIGIN_CODE", "origin_code")  'add 2015/04/21
      
      DO While not Rec_country.EOF
        'add 2015/04/21 Start
        If VAL <> "" Then
          SEL = ""
          If CLng(Rec_country.Fields("COUNTRY_CODE")) = CLng(VAL) Then
            SEL = "selected"
          End If
          %>
          <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>" <%= SEL %> ><%= Rec_country.Fields("COUNTRY") %>
          <%
        Else
        'add 2015/04/21 End
        %>
        <% IF CLng(REC_COUNTRY.FIELDS("COUNTRY_CODE")) = CLng(REC_COMPANY.FIELDS("COUNTRY_CODE")) THEN %>
          <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>" SELECTED ><%= Rec_country.Fields("COUNTRY") %>
        <% ELSE %>
          <Option Value="<%= Rec_country.Fields("COUNTRY_CODE") %>"><%= Rec_country.Fields("COUNTRY") %>
        <% END IF %>
        <%
        End If 'add 2015/04/21
        
        Rec_country.Movenext
      LOOP %>
     <%
     END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Curr Code</Td>
 <Td ColSpan="2">
 <Select Name="curr_code" Size="1">
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
 <%
 ELSE
  VAL = GET_VAL("CURR_CODE", "curr_code")  'add 2015/04/21
  DO While not Reccurrency.EOF %>
    <%
    'add 2015/04/21 Start
    If VAL <> "" Then
      SEL = ""
      If CLng(Reccurrency.Fields("CURR_CODE")) = CLng(VAL) Then
        SEL = "selected"
      End If
      %>
      <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>" <%= SEL %> ><%= Reccurrency.Fields("CURR_MARK") %>
      <%
    Else
    'add 2015/04/21 End
      
      IF CLng(RECCURRENCY.FIELDS("CURR_CODE")) = CLng(REC_COMPANY.FIELDS("CURR_CODE")) THEN %>
        <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>" SELECTED ><%= Reccurrency.Fields("CURR_MARK") %>
      <%
      ELSE %>
        <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>"><%= Reccurrency.Fields("CURR_MARK") %>
      <%
      END IF 
    End If
    
    Reccurrency.Movenext
  LOOP %>
 <%
 END IF %>
 </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">External Unit Number</Font></Td>
 <!-- <Td ColSpan="2"><Input Type="text" Name="external_unit_number" Size="5" Maxlength="5"></Td> -->
 <Td ColSpan="2"><Input Type="text" Name="external_unit_number" Size="5" Maxlength="5" value='<%= GET_VAL("EXTERNAL_UNIT_NUMBER", "external_unit_number") %>' onChange="copy()"></Td> <!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00">Safety Stock</Td>
 <!-- <Td ColSpan="2"><Input Type="text" Name="safety_stock" Size="9" Maxlength="9"></Td> -->
 <Td ColSpan="2"><Input Type="text" Name="safety_stock" Size="9" Maxlength="9" value='<%= GET_VAL("SAFETY_STOCK", "safety_stock") %>'></Td><!-- mod 2015/04/21 -->
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
 <Td ColSpan="2">
   <Select Name="unit_stock" Size="1">
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
       <%
       ELSE
         VAL = GET_VAL("UNIT_STOCK", "unit_stock")  'add 2015/04/21
         DO While not Rec_unit_stock.EOF
           'add 2015/04/21 Start
           If VAL <> "" Then
             SEL = ""
             If CLng(Rec_unit_stock.Fields("UNIT_CODE")) = CLng(VAL) Then
               SEL = "selected"
             End If
           End If
           'add 2015/04/21 End
           %>
           <!--<Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>"><%= Rec_unit_stock.Fields("UNIT") %>-->
           <Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_unit_stock.Fields("UNIT") %><!-- mod 2015/04/21 -->
           <%
           Rec_unit_stock.Movenext
         LOOP %>
       <%
       END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00">Unit of Engineering</Td>
 <Td ColSpan="2">
   <Select Name="unit_engineering" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("UNIT_ENGINEERING", "unit_engineering")  'add 2015/04/21
       DO While not Rec_unit_eng.EOF
           'add 2015/04/21 Start
           If VAL <> "" Then
             SEL = ""
             If CLng(Rec_unit_eng.Fields("UNIT_CODE")) = CLng(VAL) Then
               SEL = "selected"
             End If
           End If
           'add 2015/04/21 End
           %>
         <!-- <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>"><%= Rec_unit_eng.Fields("UNIT") %> -->
         <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_unit_eng.Fields("UNIT") %><!-- mod 2015/04/21 -->
         <% Rec_unit_eng.Movenext
      LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 </Tr>
  <Td ColSpan="3" BgColor="#00FF00">
 <Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
 </Td>
 <Td ColSpan="3">
 <!--<Input Type="text" Name="unit_stock_rate" Size="6" Maxlength="6">-->
 <Input Type="text" Name="unit_stock_rate" Size="6" Maxlength="6" value='<%= GET_VAL("UNIT_STOCK_RATE", "unit_stock_rate") %>'><!-- mod 2015/04/21 -->
 :
 <!--<Input Type="text" Name="unit_engineer_rate" Size="6" Maxlength="6">-->
 <Input Type="text" Name="unit_engineer_rate" Size="6" Maxlength="6" value='<%= GET_VAL("UNIT_ENGINEER_RATE", "unit_engineer_rate") %>'><!-- mod 2015/04/21 -->
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Weight</Font></Td>
 <!--<Td><Input Type="text" Name="weight" Size="12" Maxlength="12"></Td>-->
 <Td><Input Type="text" Name="weight" Size="12" Maxlength="12" value='<%= GET_VAL("WEIGHT", "weight") %>'></Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00">Unit of Weight</Td>
 <Td>
 <Select Name="uom_w" Size="1">
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
     <%
   ELSE
     VAL = GET_VAL("UOM_W", "uom_w")  'add 2015/04/21
     DO While not Rec_uow.EOF
        'add 2015/04/21 Start
        If VAL <> "" Then
          SEL = ""
          If CLng(Rec_uow.Fields("UNIT_CODE")) = CLng(VAL) Then
            SEL = "selected"
          End If
        End If
        'add 2015/04/21 End
        %>
       <!--<Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>"><%= Rec_uow.Fields("UNIT") %>-->
       <Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_uow.Fields("UNIT") %><!-- mod 2015/04/21 -->
       <% Rec_uow.Movenext
     LOOP %>
   <%
   END IF %>
 </Select>
 </Td>
 <Td BgColor="#00ff00">Unit of Measurement</Td>
 <Td>
   <Select Name="uom_l" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("UOM_L", "uom_l")  'add 2015/04/21
       DO While not Rec_uol.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CLng(Rec_uol.Fields("UNIT_CODE")) = CLng(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>"><%= Rec_uol.Fields("UNIT") %>-->
         <Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_uol.Fields("UNIT") %><!-- mod 2015/04/21 -->
         <% Rec_uol.Movenext
       LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">DRAWING NO</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="drawing_no" Size="15" Maxlength="15"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="drawing_no" Size="20" Maxlength="20" value='<%= GET_VAL("DRAWING_NO", "drawing_no") %>'></Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00">DRAWING REV</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="drawing_rev" Size="1" Maxlength="1"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="drawing_rev" Size="1" Maxlength="1" value='<%= GET_VAL("DRAWING_REV", "drawing_rev") %>'></Td><!-- mod 2015/04/21 -->
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">APPLICABLE MODEL</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="applicable_model" Size="20" Maxlength="20"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="applicable_model" Size="20" Maxlength="20" value='<%= GET_VAL("APPLICABLE_MODEL", "applicable_model") %>'></Td>
 <Td BgColor="#00ff00">CATALOG NO</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="catalog_no" Size="28" Maxlength="28"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="catalog_no" Size="28" Maxlength="28" value='<%= GET_VAL("CATALOG_NO", "catalog_no") %>'></Td>
 </Tr>
   <% IF CLng(my_type) <> 80 AND CLng(my_company_code) = 926236 THEN %>
     <Input Type="hidden" Name="standard_price" Value="0">
     <Input Type="hidden" Name="next_term_price" Value="">
     <Input Type="hidden" Name="suppliers_price" Value="">
   <% ELSE %>
     <Tr>
       <Td BgColor="#00ff00"><Font Color="#0000ff">Standard Price</Font></Td>
       <!--<Td><Input Type="text" Name="standard_price" Size="24" Maxlength="24"></Td>-->
       <Td><Input Type="text" Name="standard_price" Size="24" Maxlength="24" value='<%= GET_VAL("STANDARD_PRICE", "standard_price") %>'></Td><!-- mod 2015/04/21 -->
       <Td BgColor="#00ff00"><Font Color="#0000ff">Next Term Price</Font></Td>
       <!--<Td><Input Type="text" Name="next_term_price" Size="24" Maxlength="24"></Td>-->
       <Td><Input Type="text" Name="next_term_price" Size="24" Maxlength="24" value='<%= GET_VAL("NEXT_TERM_PRICE", "next_term_price") %>'></Td><!-- mod 2015/04/21 -->
       <Td BgColor="#00ff00"><Font Color="#0000ff">Suppliers Price</Font></Td>
       <!--<Td><Input Type="text" Name="suppliers_price" Size="24" Maxlength="24"></Td>-->
       <Td><Input Type="text" Name="suppliers_price" Size="24" Maxlength="24" value='<%= GET_VAL("SUPPLIERS_PRICE", "suppliers_price") %>'></Td><!-- mod 2015/04/21 -->
     </Tr>
   <% END IF %>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Lead Time</Font></Td>
 <!--<Td><Input Type="text" Name="manufact_leadtime" Size="3" Maxlength="3">Days</Td>-->
 <Td><Input Type="text" Name="manufact_leadtime" Size="3" Maxlength="3" value='<%= GET_VAL("MANUFACT_LEADTIME", "manufact_leadtime") %>'>Days</Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00"><Font Color="#0000ff">Purchase Lead Time</Font></Td>
 <!--<Td><Input Type="text" Name="purchase_leadtime" Size="3" Maxlength="3">Days</Td>-->
 <Td><Input Type="text" Name="purchase_leadtime" Size="3" Maxlength="3" value='<%= GET_VAL("PURCHASE_LEADTIME", "purchase_leadtime") %>'>Days</Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00"><Font Color="#0000ff">Adjustment Lead Time</Font></Td>
 <!--<Td><Input Type="text" Name="adjustment_leadtime" Size="3" Maxlength="3">Days</Td>-->
 <Td><Input Type="text" Name="adjustment_leadtime" Size="3" Maxlength="3" value='<%= GET_VAL("ADJUSTMENT_LEADTIME", "adjustment_leadtime") %>'>Days</Td><!-- mod 2015/04/21 -->
 </Tr>
 
 <Tr>
 <Td BgColor="#00ff00">Issue Policy</Td>
 <Td>
   <Select Name="issue_policy" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("ISSUE_POLICY", "issue_policy")  'add 2015/04/21
       DO WHILE NOT REC_ISSUEPOLICY.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CStr(REC_ISSUEPOLICY.Fields("ISSUE_POLICY")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>"><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%>-->
         <Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>" <%= SEL %>><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%><!-- mod 2015/04/21 -->
         <% REC_ISSUEPOLICY.MOVENEXT
       LOOP
     END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00"><Font Color="blue">Issue Lot</Font></Td>
 <!--<Td><Input Type="text" Name="issue_lot" Size="9" Maxlength="9"></Td>-->
 <Td><Input Type="text" Name="issue_lot" Size="9" Maxlength="9" value='<%= GET_VAL("ISSUE_LOT", "issue_lot") %>'></Td><!-- mod 2015/04/21 -->
 <Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Fail Rate</Font></Td>
 <!--<Td><Input Type="text" Name="manufact_fail_rate" Size="3" Maxlength="3">%</Td>-->
 <Td><Input Type="text" Name="manufact_fail_rate" Size="3" Maxlength="3" value='<%= GET_VAL("MANUFACT_FAIL_RATE", "manufact_fail_rate") %>'>%</Td><!-- mod 2015/04/21 -->
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Section Code</Td>
 <Td ColSpan="2">
   <Select Name="section_code" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("SECTION_CODE", "section_code")  'add 2015/04/21
       DO While not Rec_section.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CStr(Rec_section.Fields("SECTION_CODE")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <Option Value="<%= Rec_section.Fields("SECTION_CODE") %>"><%= Rec_section.Fields("SHORT_NAME") %>
         <% Rec_section.Movenext
        LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00">Stock Subject Code</Td>
 <Td ColSpan="2">
 <%
 '------------------------
 ' stock subject code get
 '------------------------
 'mod 2015/04/21 Start
 'SQL_stocksubject = "SELECT STOCK_SUBJECT " & _
 '                   "  FROM STOCK_SUBJECT " & _
 '                   " WHERE STOCK_SUBJECT_CODE = " & CLng(REQUEST("stock_subject_code")) & " "
 SQL_stocksubject = "SELECT STOCK_SUBJECT " & _
                    "  FROM STOCK_SUBJECT " & _
                    " WHERE STOCK_SUBJECT_CODE = " & CLng(STOCK_SUB) & " "
 'mod 2015/04/21 End
 
 Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
 RESPONSE.WRITE REC_STOCKSUBJECT.FIELDS("STOCK_SUBJECT") %>
 <!--<Input Type="hidden" Name="stock_subject_code" Value="<%= REQUEST("stock_subject_code") %>">-->
 <Input Type="hidden" Name="stock_subject_code" Value="<%= STOCK_SUB %>"><!-- mod 2015/04/21 -->
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Cost Process Code</Td>
 <Td ColSpan="2">
   <Select Name="cost_process_code" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("COST_PROCESS_CODE", "cost_process_code")  'add 2015/04/21
       DO While not Rec_costprocess.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If Cstr(Rec_costprocess.Fields("COST_PROCESS_CODE")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>"><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>-->
         <Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>" <%= SEL %>><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %><!-- mod 2015/04/21 -->
         <% Rec_costprocess.Movenext
       LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00">Cost Subject Code</Td>
 <Td ColSpan="2">
   <Select Name="cost_subject_code" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("COST_SUBJECT_CODE", "cost_subject_code")  'add 2015/04/21
       DO While not Rec_costsubject.EOF 
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CStr(Rec_costsubject.Fields("COST_SUBJECT_CODE")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>"><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>-->
         <Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>" <%= SEL %>><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %><!-- mod 2015/04/21 -->
         <% Rec_costsubject.Movenext
       LOOP
     END IF %>
   </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Order Policy</Td>
 <Td ColSpan="5">
   <Select Name="order_policy" Size="1">
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
     <%
     ELSE
       VAL = GET_VAL("ORDER_POLICY", "order_policy")  'add 2015/04/21
       DO WHILE NOT REC_ORDER_POLICY.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CStr(REC_ORDER_POLICY.Fields("ORDER_POLICY")) = CStr(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
        %>
       <!--<Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY")%>"><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME")%>-->
       <Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY")%>" <%= SEL %>><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME")%><!-- mod 2015/04/21 -->
       <% REC_ORDER_POLICY.MOVENEXT
      LOOP
     END IF %>
   </Select>
 </Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Maker Flag</Td>
 <Td ColSpan="2">
   <Select Name="maker_flag" Size="1">
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
       <%
     ELSE
       VAL = GET_VAL("MAKER_FLAG", "maker_flag")  'add 2015/04/21
       DO While not REC_MAKERFLAG.EOF
         'add 2015/04/21 Start
         If VAL <> "" Then
           SEL = ""
           If CLng(REC_MAKERFLAG.Fields("MAKER_FLAG_CODE")) = CLng(VAL) Then
             SEL = "selected"
           End If
         End If
         'add 2015/04/21 End
         %>
         <!--<Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>"><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %>-->
         <Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>" <%= SEL %>><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %><!-- mod 2015/04/21 -->
         <% REC_MAKERFLAG.Movenext
       LOOP %>
     <%
     END IF %>
   </Select>
 </Td>
 <Td BgColor="#00ff00">Maker</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="mak" Size="18" Maxlength="18"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="mak" Size="18" Maxlength="18" value='<%= GET_VAL("MAK", "mak") %>'></Td>
 </Tr>
 <Tr>
 <Td BgColor="#00ff00">Item Type1</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="item_type1" Size="30" Maxlength="30"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="item_type1" Size="30" Maxlength="30" value='<%= GET_VAL("ITEM_TYPE1", "item_type1") %>'></Td>
 <Td BgColor="#00ff00">Item Type2</Td>
 <!--<Td ColSpan="2"><Input Type="text" Name="item_type2" Size="18" Maxlength="18"></Td>-->
 <Td ColSpan="2"><Input Type="text" Name="item_type2" Size="18" Maxlength="18" value='<%= GET_VAL("ITEM_TYPE2", "item_type2") %>'></Td>
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
<% SQL_CHANGE = "SELECT ITEM, ITEM_NO, ITEM_CODE, ITEM_FLAG, " & _
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
                "ITEM_TYPE1, ITEM_TYPE2, " & _
                "GRADE_CODE, CUSTOMER_TYPE, PACKAGE_TYPE, " & _
                "CAPACITY, DATE_CODE_TYPE, DATE_CODE_MONTH, LABEL_TYPE, " & _
                "INNER_BOX_UNIT_NUMBER, INNER_BOX_HEIGHT, INNER_BOX_WIDTH, INNER_BOX_DEPTH, " & _
                "MEDIUM_BOX_UNIT_NUMBER, MEDIUM_BOX_HEIGHT, MEDIUM_BOX_WIDTH, MEDIUM_BOX_DEPTH, " & _
                "OUTER_BOX_HEIGHT, OUTER_BOX_WIDTH, OUTER_BOX_DEPTH, MEASUREMENT, " & _
                "i.PI_NO, p.PLT_SPEC_NO, OPERATION_TIME, MAN_POWER, p.PALLET_UNIT_NUMBER, p.PALLET_CTN_NUMBER, " & _
                "p.PALLET_STEP_CTN_NUMBER, p.PALLET_HEIGHT, p.PALLET_WIDTH, p.PALLET_DEPTH, p.PALLET_SIZE_TYPE, " & _
                "AGING_DAY " & _
                "FROM  ITEM i, " & _
                "      PACKING_INFORMATION p " & _
                "WHERE ITEM_NO = " & CLng(REQUEST("ITEM_NO")) & _
                "  AND i.PI_NO = p.PI_NO(+) "
   SET REC_CHANGE = DB.EXECUTE(SQL_CHANGE) 

  '-- 2007/04/26 Add --
	If Not REC_CHANGE.EOF Then
	   wk_company_curr_code      = REC_CHANGE("CURR_CODE")
	End If
  '-- 2007/04/26 End --
%>
<Tr>
<Td BgColor="#00FF00">Item No</Td>
<Td ColSpan="2">
<%= REQUEST("ITEM_NO") %>
<Input Type="hidden" Name="item_no" Value="<%= REQUEST("ITEM_NO") %>"></Td>
<Td BgColor="#00ff00">Item Code</Td>
<Td ColSpan="2">
<!--<Input Type="text" Name="item_code" Value="<%= REC_CHANGE.FIELDS("ITEM_CODE") %>" Size="24" Maxlength="24">-->
<Input Type="text" Name="item_code" Value='<%= GET_VAL("ITEM_CODE", "item_code") %>' Size="24" Maxlength="24"><!-- mod 2015/04/21-->
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Name</Td>
<Td ColSpan="2">
<!-- mod 2015/04/21 Start -->
<!--<Input Type="text" Name="item" Value="<%= replace(REC_CHANGE.FIELDS("ITEM") &"","""","&quot;") %>" Size="40" Maxlength="40">-->
<%
CHANGE_ITEM_VAL = GET_VAL("ITEM", "item")
%>
<Input Type="text" Name="item" Value="<%= replace(CHANGE_ITEM_VAL &"","""","&quot;") %>" Size="40" Maxlength="40"><!-- mod 2015/04/21 -->
<!-- mod 2015/04/21 End -->

</Td>
<Td BgColor="#00ff00">Item Flag</Td>
<Td ColSpan="2">
 <Select Name="item_flag" Size="1">
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
  <%
  ELSE
    VAL = GET_VAL("ITEM_FLAG", "item_flag")  'add 2015/04/21
    DO WHILE NOT REC_ITEMFLAG.EOF
      'add 2015/04/21 Start
      If VAL <> "" Then
        SEL = ""
        If CStr(REC_ITEMFLAG.Fields("ITEM_FLAG")) = CStr(VAL) Then
          SEL = "selected"
        End If
        %>
        <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>" <%= SEL %>><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
        <%
      Else
      'add 2015/04/21 End
        IF CStr(REC_ITEMFLAG.FIELDS("ITEM_FLAG")) = "1" THEN %>
          <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>" SELECTED><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
        <%
        ELSE %>
          <Option Value="<%= REC_ITEMFLAG.FIELDS("ITEM_FLAG")%>"><%= REC_ITEMFLAG.FIELDS("FLAG_NAME")%>
        <%
        END IF
      End If 'add 2015/04/21
      %>
      <% REC_ITEMFLAG.MOVENEXT
    LOOP
  END IF %>
 </Select>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Description</Td>
<Td ColSpan="2">
<!-- mod 2015/04/21 Start -->
<%
CHANGE_DESC_VAL = GET_VAL("DESCRIPTION", "description")
%>
<!--<Input Type="text" Name="description" Value="<%= replace(REC_CHANGE.FIELDS("DESCRIPTION") &"","""","&quot;") %>" Size="30" Maxlength="30">-->
<Input Type="text" Name="description" Value="<%= replace(CHANGE_DESC_VAL &"","""","&quot;") %>" Size="30" Maxlength="30">
<!-- mod 2015/04/21 End -->
</Td>
<Td BgColor="#00ff00">Class Code</Td>
<Td ColSpan="2">
  <Select Name="class_code" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("CLASS_CODE", "class_code")  'add 2015/04/21
      DO While not Rec_class.EOF
        %>
        <!-- mod 2015/04/21 Start
        <%
        IF CLng(REC_CHANGE.FIELDS("CLASS_CODE")) = CLng(Rec_class.Fields("CLASS_CODE")) THEN %>
          <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>" SELECTED><%= Rec_class.Fields("CLASS") %>
        <%
        ELSE %>
          <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>"><%= Rec_class.Fields("CLASS") %>
        <%
        END IF %>
        -->
        <%
        If VAL <> "" Then
          SEL = ""
          If CLng(Rec_class.Fields("CLASS_CODE")) = CLng(VAL) Then
            SEL = "selected"
          End If
        End If
        %>
        <Option Value="<%= Rec_class.Fields("CLASS_CODE") %>" <%= SEL %>><%= Rec_class.Fields("CLASS") %>
        <!-- mod 2015/04/21 End -->
        
        <% Rec_class.Movenext
      LOOP
    END IF %>
  </Select>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Origin Code</Td>
<Td ColSpan="2">
<%
'------------------
' country code get
'------------------
'mod 2015/04/21 Start
'SQL_country = "SELECT COUNTRY " & _
'	 " FROM COUNTRY " & _
'	 " WHERE DELETE_TYPE IS NULL AND " & _
'	 "       COUNTRY_CODE ='" & REC_CHANGE.FIELDS("ORIGIN_CODE") & "'"
VAL = GET_VAL("ORIGIN_CODE", "origin_code") 
SQL_country = "SELECT COUNTRY " & _
	 " FROM COUNTRY " & _
	 " WHERE DELETE_TYPE IS NULL AND " & _
	 "       COUNTRY_CODE ='" & VAL & "'"
'mod 2015/04/21 End

Set Rec_country = DB.Execute(SQL_country)
IF Rec_country.eof or Rec_country.bof then %>
  <% RESPONSE.WRITE "DATA NOT FOUND" %>
<%
ELSE %>
  <% RESPONSE.WRITE REC_COUNTRY.FIELDS("COUNTRY") %>
  <!--<Input Type="hidden" Name="origin_code" Value="<%= REC_CHANGE.FIELDS("ORIGIN_CODE") %>">-->
  <Input Type="hidden" Name="origin_code" Value="<%= VAL %>"><!-- mod 2015/04/21 -->
<%
END IF %>

</Td>

<Td BgColor="#00ff00">Curr Code</Td>
<Td ColSpan="2">
  <Select Name="curr_code" Size="1">
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
  <%
  ELSE
    VAL = GET_VAL("CURR_CODE", "curr_code")  'add 2015/04/21
    DO While not Reccurrency.EOF %>
      <%
      'IF CStr(REC_CHANGE.FIELDS("CURR_CODE") & "") = CStr(Reccurrency.Fields("CURR_CODE") & "") THEN
      IF CStr(VAL & "") = CStr(Reccurrency.Fields("CURR_CODE") & "") THEN 'mod 2015/04/21
      %>
        <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>" SELECTED><%= Reccurrency.Fields("CURR_MARK") %>
      <%
      ELSE %>
        <Option Value="<%= Reccurrency.Fields("CURR_CODE") %>"><%= Reccurrency.Fields("CURR_MARK") %>
      <%
      END IF
      Reccurrency.Movenext
    LOOP %>
  <%
  END IF %>
</Select>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00"><Font Color="#0000ff">External Unit Number</Font></Td>
<Td ColSpan="2">
<!-- <Input Type="text" Name="external_unit_number" Value="<%= REC_CHANGE.FIELDS("EXTERNAL_UNIT_NUMBER") %>" Size="5" Maxlength="5">-->
<Input Type="text" Name="external_unit_number" Value='<%= GET_VAL("EXTERNAL_UNIT_NUMBER", "external_unit_number") %>' Size="5" Maxlength="5" onChange="copy()"><!-- mod 2015/04/21 -->
</Td>
<Td BgColor="#00ff00">Safety Stock</Td>
<Td ColSpan="2">
<!-- mod 2015/04/21 Start
<% IF REC_CHANGE.FIELDS("SAFETY_STOCK") = " " THEN %>
 <Input Type="text" Name="safety_stock" Value="1" Size="9" Maxlength="9">
<% ELSE %>
 <Input Type="text" Name="safety_stock" Value="<%= REC_CHANGE.FIELDS("SAFETY_STOCK") %>" Size="9" Maxlength="9">
<% END IF %>
-->
 <Input Type="text" Name="safety_stock" Size="9" Maxlength="9" Value='<%= GET_VAL("SAFETY_STOCK", "safety_stock") %>'>
<!-- mod 2015/04/21 End -->
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
<Td ColSpan="2">
<Select Name="unit_stock" Size="1">
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
  <%
  ELSE
    VAL = GET_VAL("UNIT_STOCK", "unit_stock")  'add 2015/04/21
    DO While not Rec_unit_stock.EOF %>
      <%
      'IF CStr(REC_CHANGE.FIELDS("UNIT_STOCK") & "") = CStr(Rec_unit_stock.Fields("UNIT_CODE") & "") THEN
      IF CStr(VAL & "") = CStr(Rec_unit_stock.Fields("UNIT_CODE") & "") THEN '//mod 2015/04/21
      %>
        <Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>" SELECTED><%= Rec_unit_stock.Fields("UNIT") %>
      <%
      ELSE %>
        <Option Value="<%= Rec_unit_stock.Fields("UNIT_CODE") %>"><%= Rec_unit_stock.Fields("UNIT") %>
      <%
      END IF
      Rec_unit_stock.Movenext
    LOOP %>
  <%
  END IF %>
</Select>
</Td>
<Td BgColor="#00ff00">Unit of Engineering</Td>
<Td ColSpan="2">
  <Select Name="unit_engineering" Size="1">
    <Option Value="">
    <%
    '------------------
    ' unit of quantity get
    '------------------
    SQL_unit_eng = "SELECT   nvl(UNIT_CODE,'10') unit_code, UNIT " & _
	     " FROM UNIT " & _
	     " WHERE DELETE_TYPE IS NULL AND" & _
	     "       USE = 'QUANTITY'" & _
	     " ORDER BY UNIT "
    Set Rec_unit_eng = DB.Execute(SQL_unit_eng)
    IF Rec_unit_eng.eof or Rec_unit_eng.bof then %>
      <Option Value="">NO DATA FOUND
    <%
    ELSE
      VAL = GET_VAL("UNIT_ENGINEERING", "unit_engineering")  'add 2015/04/21
      DO While not Rec_unit_eng.EOF
        'mod 2015/04/21 Start
        'IF CStr(REC_CHANGE.FIELDS("UNIT_ENGINEERING") & "") = CStr(Rec_unit_eng.Fields("UNIT_CODE") & "") THEN %>
         <!--  <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>" SELECTED><%= Rec_unit_eng.Fields("UNIT") %>-->
        <%
        'ELSE %>
        <!-- <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>"><%= Rec_unit_eng.Fields("UNIT") %> -->
        <%
        'END IF
        If VAL <> "" Then
          SEL = ""
          If CLng(Rec_unit_eng.Fields("UNIT_CODE")) = CLng(VAL) Then
            SEL = "selected"
          End If
        End If
        %>
        <Option Value="<%= Rec_unit_eng.Fields("UNIT_CODE") %>" <%= SEL %>><%= Rec_unit_eng.Fields("UNIT") %>
        <!-- mod 2015/04/21 End -->
        <%
        Rec_unit_eng.Movenext
      LOOP
    END IF %>
  </Select></Td>
</Tr>
<Tr>
<Td ColSpan="3" BgColor="#00FF00">
<Font Color="#0000ff">Unit Stock Rate:Unit Engineer Rate</Font>
</Td>
<Td ColSpan="3">
<!--<Input Type="text" Name="unit_stock_rate" Value="<%= REC_CHANGE.FIELDS("UNIT_STOCK_RATE") %>" Size="6" Maxlength="6">-->
<Input Type="text" Name="unit_stock_rate" Value='<%= GET_VAL("UNIT_STOCK_RATE", "unit_stock_rate") %>' Size="6" Maxlength="6"><!-- mod 2015/04/21 -->
 :
<!--<Input Type="text" Name="unit_engineer_rate" Value="<%= REC_CHANGE.FIELDS("UNIT_ENGINEER_RATE") %>" Size="6" Maxlength="6">-->
<Input Type="text" Name="unit_engineer_rate" Value='<%= GET_VAL("UNIT_ENGINEER_RATE", "unit_engineer_rate") %>' Size="6" Maxlength="6"><!-- mod 2015/04/21 -->
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Weight</Td>
<Td>
<!--<Input Type="text" Name="weight" Value="<%= REC_CHANGE.FIELDS("WEIGHT") %>" Size="12" Maxlength="12">-->
<Input Type="text" Name="weight" Value='<%= GET_VAL("WEIGHT", "weight") %>' Size="12" Maxlength="12"><!-- mod 2015/04/21 -->
</Td>
<Td BgColor="#00ff00">Unit of Weight</Td>
<Td>
  <Select Name="uom_w" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("UOM_W", "uom_w")  'add 2015/04/21
      DO While not Rec_uow.EOF
        'IF CStr(REC_CHANGE.FIELDS("UOM_W") &"") = CStr(Rec_uow.Fields("UNIT_CODE") & "") THEN
        IF CStr(VAL &"") = CStr(Rec_uow.Fields("UNIT_CODE") & "") THEN '// mod 2015/04/21
        %>
          <Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>" SELECTED><%= Rec_uow.Fields("UNIT") %>
        <%
        ELSE %>
          <Option Value="<%= Rec_uow.Fields("UNIT_CODE") %>"><%= Rec_uow.Fields("UNIT") %>
        <%
        END IF
        Rec_uow.Movenext
      LOOP
    END IF %>
  </Select>
</Td>
<Td BgColor="#00ff00">Unit of Measurement</Td>
<Td>
  <Select Name="uom_l" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("UOM_L", "uom_l")  'add 2015/04/21
      DO While not Rec_uol.EOF
        'IF CStr(REC_CHANGE.FIELDS("UOM_L") &"") = CStr(Rec_uol.Fields("UNIT_CODE") &"") THEN
        IF CStr(VAL &"") = CStr(Rec_uol.Fields("UNIT_CODE") &"") THEN '// mod 2015/04/21
        %>
          <Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>" SELECTED><%= Rec_uol.Fields("UNIT") %>
        <%
        ELSE %>
          <Option Value="<%= Rec_uol.Fields("UNIT_CODE") %>"><%= Rec_uol.Fields("UNIT") %>
        <%
        END IF
        Rec_uol.Movenext
      LOOP
    END IF %>
  </Select>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">DRAWING NO</Td>
<Td ColSpan="2">
<!--<Input Type="text" Name="drawing_no" Value="<%= Replace(REC_CHANGE.FIELDS("DRAWING_NO") &"","""","&quot;") %>" Size="15" Maxlength="15">-->
<Input Type="text" Name="drawing_no" Value='<%= Replace(GET_VAL("DRAWING_NO", "drawing_no") &"","""","&quot;") %>' Size="20" Maxlength="20"><!-- mod 2015/04/21 -->
</Td>
<Td BgColor="#00ff00">DRAWING REV</Td>
<Td ColSpan="2">
<!--<Input Type="text" Name="drawing_rev" Value="<%= Replace(REC_CHANGE.FIELDS("DRAWING_REV") &"","""","&quot;") %>" Size="1" Maxlength="1">-->
<Input Type="text" Name="drawing_rev" Value='<%= Replace(GET_VAL("DRAWING_REV", "drawing_rev") &"","""","&quot;") %>' Size="1" Maxlength="1"><!-- mod 2015/04/21 -->
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">APPLICABLE MODEL</Td>
<Td ColSpan="2">
<!--<Input Type="text" Name="applicable_model" Value="<%= Replace(REC_CHANGE.FIELDS("APPLICABLE_MODEL") &"","""","&quot;") %>" Size="20" Maxlength="20">-->
<Input Type="text" Name="applicable_model" Value='<%= Replace(GET_VAL("APPLICABLE_MODEL", "applicable_model") &"","""","&quot;") %>' Size="20" Maxlength="20"><!-- mod 2015/04/21 -->
</Td>
<Td BgColor="#00ff00">CATALOG NO</Td>
<Td ColSpan="2">
<!--<Input Type="text" Name="catalog_no" Value="<%= Replace(REC_CHANGE.FIELDS("CATALOG_NO") &"","""","&quot;") %>" Size="28" Maxlength="28">-->
<Input Type="text" Name="catalog_no" Value='<%= Replace(GET_VAL("CATALOG_NO", "catalog_no") &"","""","&quot;") %>' Size="28" Maxlength="28"><!-- mod 2015/04/21 -->
</Td>
</Tr>
<% IF CLng(my_type) <> 80 AND CLng(my_company_code) = 926236 THEN %>
 <!--<Input Type="hidden" Name="standard_price" Value="<%= REC_CHANGE.FIELDS("STANDARD_PRICE") %>">-->
 <Input Type="hidden" Name="standard_price" Value='<%= GET_VAL("STANDARD_PRICE", "standard_price") %>'><!-- mod 2015/04/21 -->
 <!--<Input Type="hidden" Name="next_term_price" Value="<%= REC_CHANGE.FIELDS("NEXT_TERM_PRICE") %>">-->
 <Input Type="hidden" Name="next_term_price" Value='<%= GET_VAL("NEXT_TERM_PRICE", "next_term_price") %>'><!-- mod 2015/04/21 -->
 <!--<Input Type="hidden" Name="suppliers_price" Value="<%= REC_CHANGE.FIELDS("SUPPLIERS_PRICE") %>">-->
 <Input Type="hidden" Name="suppliers_price" Value='<%= GET_VAL("SUPPLIERS_PRICE", "suppliers_price") %>'><!-- mod 2015/04/21 -->
<% ELSE %>
 <Tr>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Standard Price</Font></Td>
 <Td>
 <!--<Input Type="text" Name="standard_price" Value="<%= REC_CHANGE.FIELDS("STANDARD_PRICE") %>" Size="24" Maxlength="24">-->
 <Input Type="text" Name="standard_price" Value='<%= GET_VAL("STANDARD_PRICE", "standard_price") %>' Size="24" Maxlength="24"><!-- mod 2015/04/21 -->
 </Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Next Term Price</Font></Td>
 <Td>
 <!--<Input Type="text" Name="next_term_price" Value="<%= REC_CHANGE.FIELDS("NEXT_TERM_PRICE") %>" Size="24" Maxlength="24">-->
 <Input Type="text" Name="next_term_price" Value='<%= GET_VAL("NEXT_TERM_PRICE", "next_term_price") %>' Size="24" Maxlength="24"><!-- mod 2015/04/21 -->
 </Td>
 <Td BgColor="#00ff00"><Font Color="#0000ff">Suppliers Price</Font></Td>
 <Td>
 <!--<Input Type="text" Name="suppliers_price" Value="<%= REC_CHANGE.FIELDS("SUPPLIERS_PRICE") %>" Size="24" Maxlength="24">-->
 <Input Type="text" Name="suppliers_price" Value='<%= GET_VAL("SUPPLIERS_PRICE", "suppliers_price") %>' Size="24" Maxlength="24"><!-- mod 2015/04/21 -->
 </Td>
 </Tr>
<% END IF%>
<Tr>
<Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Lead Time</Font></Td>
<Td>
<!--<Input Type="text" Name="manufact_leadtime" Value="<%= REC_CHANGE.FIELDS("MANUFACT_LEADTIME") %>" Size="3" Maxlength="3">-->
<Input Type="text" Name="manufact_leadtime" Value='<%= GET_VAL("MANUFACT_LEADTIME", "manufact_leadtime") %>' Size="3" Maxlength="3"><!-- mod 2015/04/21 -->
Days</Td>
<Td BgColor="#00ff00"><Font Color="#0000ff">Purchase Lead Time</Font></Td>
<Td>
<!--<Input Type="text" Name="purchase_leadtime" Value="<%= REC_CHANGE.FIELDS("PURCHASE_LEADTIME") %>" Size="3" Maxlength="3">-->
<Input Type="text" Name="purchase_leadtime" Value='<%= GET_VAL("PURCHASE_LEADTIME", "purchase_leadtime") %>' Size="3" Maxlength="3"><!-- mod 2015/04/21 -->
Days</Td>
<Td BgColor="#00ff00"><Font Color="#0000ff">Adjustment Lead Time</Font></Td>
<Td>
<!--<Input Type="text" Name="adjustment_leadtime" Value="<%= REC_CHANGE.FIELDS("ADJUSTMENT_LEADTIME") %>" Size="3" Maxlength="3">-->
<Input Type="text" Name="adjustment_leadtime" Value='<%= GET_VAL("ADJUSTMENT_LEADTIME", "adjustment_leadtime") %>' Size="3" Maxlength="3"><!-- mod 2015/04/21 -->
Days</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Issue Policy</Td>
<Td>
  <Select Name="issue_policy" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("ISSUE_POLICY", "issue_policy")  'add 2015/04/21
      DO WHILE NOT REC_ISSUEPOLICY.EOF
        'IF CStr(REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")) = CStr(REC_CHANGE.FIELDS("ISSUE_POLICY")) THEN
        IF CStr(REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")) = CStr(VAL) THEN 'mod 2015/04/21
        %>
          <Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>" SELECTED><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%>
        <%
        ELSE %>
          <Option Value="<%= REC_ISSUEPOLICY.FIELDS("ISSUE_POLICY")%>"><%= REC_ISSUEPOLICY.FIELDS("POLICY_NAME")%>
        <%
        END IF
        REC_ISSUEPOLICY.MOVENEXT
      LOOP
    END IF
    %>
 </Select>
</Td>
<Td BgColor="#00ff00"><Font Color="blue">Issue Lot</Font></Td>
<!--<Td><Input Type="text" Name="issue_lot" Value="<%= REC_CHANGE.FIELDS("ISSUE_LOT") %>" Size="9" Maxlength="9"></Td>-->
<Td><Input Type="text" Name="issue_lot" Value='<%= GET_VAL("ISSUE_LOT", "issue_lot") %>' Size="9" Maxlength="9"></Td><!-- mod 2015/04/21 -->
<Td BgColor="#00ff00"><Font Color="#0000ff">Manufact Fail Rate</Font></Td>
<Td>
<!--<Input Type="text" Name="manufact_fail_rate" Value="<%= REC_CHANGE.FIELDS("MANUFACT_FAIL_RATE") %>" Size="3" Maxlength="3">-->
<Input Type="text" Name="manufact_fail_rate" Value='<%= GET_VAL("MANUFACT_FAIL_RATE", "manufact_fail_rate") %>' Size="3" Maxlength="3"><!-- mod 2015/04/21 -->
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Section Code</Td>
<Td ColSpan="2">
  <Select Name="section_code" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("SECTION_CODE", "section_code")  'add 2015/04/21
      DO While not Rec_section.EOF %>
        <%
        'IF CStr(REC_CHANGE.FIELDS("SECTION_CODE") &"") = CStr(Rec_section.Fields("SECTION_CODE") &"") THEN
        IF CStr(VAL &"") = CStr(Rec_section.Fields("SECTION_CODE") &"") THEN 'mod 2015/04/21
        %>
          <Option Value="<%= Rec_section.Fields("SECTION_CODE") %>" SELECTED><%= Rec_section.Fields("SHORT_NAME") %>
        <%
        ELSE %>
          <Option Value="<%= Rec_section.Fields("SECTION_CODE") %>"><%= Rec_section.Fields("SHORT_NAME") %>
        <%
        END IF
        Rec_section.Movenext
      LOOP
    END IF %>
  </Select>
</Td>
<Td BgColor="#00ff00">Stock Subject Code</Td>
<Td ColSpan="2">
  <Select Name="stock_subject_code" Size="1">
    <Option Value="">
    <%
    '------------------------
    ' stock subject code get
    '------------------------
    SQL_stocksubject = "SELECT  STOCK_SUBJECT_CODE, STOCK_SUBJECT " & _
	                   " FROM   STOCK_SUBJECT " & _
	                   " ORDER BY STOCK_SUBJECT "
    Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
    IF Rec_stocksubject.eof or Rec_stocksubject.bof then %>
      <Option Value="">NO DATA FOUND
    <%
    ELSE
      VAL = GET_VAL("STOCK_SUBJECT_CODE", "stock_subject_code")  'add 2015/04/21
      DO While not Rec_stocksubject.EOF %>
        <%
        'IF REC_CHANGE.FIELDS("STOCK_SUBJECT_CODE") = Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") THEN
        IF CStr(VAL) = CStr(Rec_stocksubject.Fields("STOCK_SUBJECT_CODE")) THEN '// mod 2015/04/21
        %>
          <Option Value="<%= Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") %>" SELECTED><%= Rec_stocksubject.Fields("STOCK_SUBJECT") %>
        <%
        ELSE %>
          <Option Value="<%= Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") %>"><%= Rec_stocksubject.Fields("STOCK_SUBJECT") %>
        <%
        END IF
        Rec_stocksubject.Movenext
      LOOP
    END IF %>
  </Select></Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Cost Process Code</Td>
<Td ColSpan="2">
  <Select Name="cost_process_code" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("COST_PROCESS_CODE", "cost_process_code")  'add 2015/04/21
      DO While not Rec_costprocess.EOF %>
        <%
        'IF REC_CHANGE.FIELDS("COST_PROCESS_CODE") = Rec_costprocess.Fields("COST_PROCESS_CODE") THEN
        IF VAL = Rec_costprocess.Fields("COST_PROCESS_CODE") THEN '//mod 2015/04/21
        %>
          <Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>" SELECTED><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>
        <%
        ELSE %>
          <Option Value="<%= Rec_costprocess.Fields("COST_PROCESS_CODE") %>"><%= Rec_costprocess.Fields("COST_PROCESS_NAME") %>
        <%
        END IF
        Rec_costprocess.Movenext
      LOOP
    END IF %>
  </Select>
</Td>
<Td BgColor="#00ff00">Cost Subject Code</Td>
<Td ColSpan="2">
  <Select Name="cost_subject_code" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("COST_SUBJECT_CODE", "cost_subject_code")  'add 2015/04/21
      DO While not Rec_costsubject.EOF %>
        <%
        'IF REC_CHANGE.FIELDS("COST_SUBJECT_CODE") = Rec_costsubject.Fields("COST_SUBJECT_CODE") THEN
        IF VAL = Rec_costsubject.Fields("COST_SUBJECT_CODE") THEN '// mod 2015/04/21
        %>
          <Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>" SELECTED><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>
        <%
        ELSE %>
          <Option Value="<%= Rec_costsubject.Fields("COST_SUBJECT_CODE") %>"><%= Rec_costsubject.Fields("COST_SUBJECT_NAME") %>
        <%
        END IF
        Rec_costsubject.Movenext
      LOOP
    END IF %>
  </Select>
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Order Policy</Td>
<Td ColSpan="5">
  <Select Name="order_policy" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("ORDER_POLICY", "order_policy")  'add 2015/04/21
      DO WHILE NOT REC_ORDER_POLICY.EOF
        'IF CStr(REC_ORDER_POLICY.FIELDS("ORDER_POLICY")) = CStr(REC_CHANGE.FIELDS("ORDER_POLICY")) THEN
        IF CStr(REC_ORDER_POLICY.FIELDS("ORDER_POLICY")) = CStr(VAL) THEN '// mod 2015/04/21
        %>
          <Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY") %>" SELECTED><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME") %>
        <%
        ELSE %>
          <Option Value="<%= REC_ORDER_POLICY.FIELDS("ORDER_POLICY") %>"><%= REC_ORDER_POLICY.FIELDS("POLICY_NAME") %>
        <%
        END IF
        REC_ORDER_POLICY.MOVENEXT
      LOOP
    END IF %>
  </Select></Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Maker Flag</Td>
<Td ColSpan="2">
  <Select Name="maker_flag" Size="1">
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
    <%
    ELSE
      VAL = GET_VAL("MAKER_FLAG", "maker_flag")  'add 2015/04/21
      DO While not REC_MAKERFLAG.EOF %>
        <%
        'IF CLng(REC_CHANGE.FIELDS("MAKER_FLAG")) = CLng(REC_MAKERFLAG.FIELDS("MAKER_FLAG_CODE")) THEN 
        IF VAL <> "" THEN
          IF CLng(VAL) = CLng(REC_MAKERFLAG.FIELDS("MAKER_FLAG_CODE")) THEN  '// mod 2015/04/21
          %>
            <Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>" SELECTED ><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %>
          <%
          ELSE %>
            <Option Value="<%= REC_MAKERFLAG.Fields("MAKER_FLAG_CODE") %>"><%= REC_MAKERFLAG.Fields("MAKER_FLAG") %>
          <%
          END IF
        END IF
        REC_MAKERFLAG.Movenext
      LOOP
    END IF %>
  </Select>
</Td>
<Td BgColor="#00ff00">Maker</Td>
<Td ColSpan="2">
<!--<Input Type="text" Name="mak" Value="<%= REC_CHANGE.FIELDS("MAK") %>" Size="18" Maxlength="18">-->
<Input Type="text" Name="mak" Value='<%= GET_VAL("MAK", "mak") %>' Size="18" Maxlength="18"><!-- mod 2015/04/12 -->
</Td>
</Tr>
<Tr>
<Td BgColor="#00ff00">Item Type1</Td>
<Td ColSpan="2">
<!--<Input Type="text" Name="item_type1" Value="<%= REC_CHANGE.FIELDS("ITEM_TYPE1") %>" Size="30" Maxlength="30">-->
<Input Type="text" Name="item_type1" Value='<%= GET_VAL("ITEM_TYPE1", "item_type1") %>' Size="30" Maxlength="30"><!-- mod 2015/04/12 -->
</Td>
<Td BgColor="#00ff00">Item Type2</Td>
<Td ColSpan="2">
<!--<Input Type="text" Name="item_type2" Value="<%= REC_CHANGE.FIELDS("ITEM_TYPE2") %>" Size="18" Maxlength="18">-->
<Input Type="text" Name="item_type2" Value='<%= GET_VAL("ITEM_TYPE2", "item_type2") %>' Size="18" Maxlength="18"><!-- mod 2015/04/12 -->
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
                "ITEM_TYPE1, ITEM_TYPE2, " & _
                "GRADE_CODE, CUSTOMER_TYPE, PACKAGE_TYPE, " & _
                "CAPACITY, DATE_CODE_TYPE, DATE_CODE_MONTH, LABEL_TYPE, " & _
                "INNER_BOX_UNIT_NUMBER, INNER_BOX_HEIGHT, INNER_BOX_WIDTH, INNER_BOX_DEPTH, " & _
                "MEDIUM_BOX_UNIT_NUMBER, MEDIUM_BOX_HEIGHT, MEDIUM_BOX_WIDTH, MEDIUM_BOX_DEPTH, " & _
                "OUTER_BOX_HEIGHT, OUTER_BOX_WIDTH, OUTER_BOX_DEPTH, MEASUREMENT, " & _
                "i.PI_NO, p.PLT_SPEC_NO, OPERATION_TIME, MAN_POWER, p.PALLET_UNIT_NUMBER, p.PALLET_CTN_NUMBER, " & _
                "p.PALLET_STEP_CTN_NUMBER, p.PALLET_HEIGHT, p.PALLET_WIDTH, p.PALLET_DEPTH, p.PALLET_SIZE_TYPE, " & _
                "AGING_DAY, PACKAGE_UNIT_NUMBER, UNIT_PACKAGE " & _
                "FROM  ITEM i, " & _
                "      PACKING_INFORMATION p " & _
                "WHERE ITEM_NO = " & CLng(REQUEST("ITEM_NO")) & _
                "  AND i.PI_NO = p.PI_NO(+) "
   SET REC_DEL = DB.EXECUTE(SQL_DELETE) 
%>
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
 IF CStr(REC_DEL.FIELDS("ITEM_FLAG") & "") <> "" THEN
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

  '-- 2007/04/26 Add --
	If Not REC_DEL.EOF Then
	   wk_company_curr_mark      = Reccurrency("CURR_MARK")
	End If
  '-- 2007/04/26 End --

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
SQL_unit_eng = "SELECT  nvl(UNIT_CODE,'10') unit_code, nvl(UNIT,'10') unit " & _
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
<!--<Input Type="hidden" Name="drawing_no" Value="<%= REC_DEL.FIELDS("DRAWING_NO") %>" Size="15" Maxlength="15">-->
<Input Type="hidden" Name="drawing_no" Value="<%= REC_DEL.FIELDS("DRAWING_NO") %>" Size="20" Maxlength="20">
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

<!-- 2006/07/06 FIのみ、客先の内箱情報を追加 -->
<%
  If Session("FI_PACKAGE_UNIT") = "1" Then
    
    'add 2015/04/21 Start
    If Request.Form("type") = "DELETE" Or _
       (Request.Form("type") = "CHANGE" And Request.Form("delete_type") = "D") Then %>
	    <TR>
	       <TD BgColor="#00ff00">Package Unit Number</Td>
	       <TD ColSpan="2">
	           <%
	           'PACKAGE_UNIT_NUMBER
			   Response.Write GET_VAL("PACKAGE_UNIT_NUMBER", "package_unit_number") 
		       
		       'UNIT_PACKAGE
		       VAL = GET_VAL("UNIT_PACKAGE", "unit_package")
		       If VAL <> "" Then
				   SQL = " select UNIT " & _
				         " from   UNIT " & _
				         " where  UNIT_CODE = " & VAL 
				   Set rec_package_unit = db.Execute(SQL)
				   
				   Response.Write "&nbsp;" & rec_package_unit.Fields("UNIT")
		       End If
	           %>
	       </TD>
	       <TD COLSPAN=4><BR></TD>
	    </TR>
    <%
    Else
	    
		'(i) PackingInformation再読込時
		If FLG = "1" Then
	      wk_package_unit_number = Request.Form("package_unit_number")
	      wk_unit_package        = Request.Form("unit_package")
	      
		'(ii) PackingInformation再読込時以外
		Else
	    'add 2015/04/21 End
		  
		  '--客先の内箱情報 / 内箱単位のデータを取得
	      SQL_PACKAGE_UNIT = " select " & _
	                         "     nvl(package_unit_number,'1') package_unit_number," & _
	                         "     nvl(unit_package,'0')  unit_package " & _
	                         " from item " & _
	                         " where item_no = '" & Request("item_no") & "' " & _
	                         "   and delete_type is null "
	      Set rec_package_unit = db.Execute(SQL_PACKAGE_UNIT)
	      
	      If Not rec_package_unit.EOF Then
	          wk_package_unit_number = rec_package_unit("package_unit_number")
	          wk_unit_package        = rec_package_unit("unit_package")
	      End If
	    
		End If 'add 2015/04/21
	    
	    '--数量単位の取得
	    Set Rec_unit = DB.Execute(SQL_unit_eng)
	    %>
	    <TR>
	       <TD BgColor="#00ff00">Package Unit Number</Td>
	       <TD ColSpan="2">
		       <INPUT TYPE=TEXT   NAME=package_unit_number VALUE="<%= wk_package_unit_number %>" Size=12 MaxLength=8>
		       <SELECT NAME=unit_package>
		           <OPTION VALUE="">
		           <%  
		              Do While Not Rec_unit.EOF
		                  selected = ""
		                  If (CStr(wk_unit_package) = CStr(Rec_unit("unit_code"))) Then
		                      selected = "selected"
		                  End If
		                  Response.Write "<OPTION VALUE='" & Rec_unit("unit_code") & "' " & selected & ">" & Rec_unit("unit")
		                  Rec_unit.MoveNext
		              Loop
		           %>
		       </SELECT>
	       </TD>
	       <TD COLSPAN=4><BR></TD>
	    </TR>
	<%
    End If
  End If %>
<!-- 2007/01/22 Add -->
  <TR>
  <%
    'add 2015/04/21 Start
	'(i) PackingInformation再読込時
	If FLG = "1" Then
		wk_unit_price_o    = Request.Form("unit_price_o")
		wk_unit_price_rate = Request.Form("unit_price_rate")
		
	'(ii) PackingInformation再読込時以外
	Else
    'add 2015/04/21 End
    	
		'-- 購入単価外貨 / 購入単価レート / 購入単価通貨コードのデータを取得
		sql_a = " select "
		sql_a = sql_a & "     nvl(i.unit_price_o,0) unit_price_o," 
		sql_a = sql_a & "     nvl(i.unit_price_rate,0) unit_price_rate,"
		sql_a = sql_a & "     nvl(i.unit_curr_code,0) unit_curr_code,"
		sql_a = sql_a & "     cur.curr_mark "
		sql_a = sql_a & "  from item i,currency cur "
		sql_a = sql_a & " where i.item_no = '" & Request("item_no") & "' "
	'	sql_a = sql_a & "   and i.delete_type is null "
		sql_a = sql_a & "   and nvl(i.unit_curr_code,0) = cur.curr_code(+) "
		
		'Response.Write sql_a & "<BR>"
		
		Set rec_a = db.Execute(sql_a)
		
		If Not rec_a.EOF Then
		   wk_unit_price_o    = rec_a("unit_price_o")
		   wk_unit_price_rate = rec_a("unit_price_rate")
		   wk_unit_curr_code  = rec_a("unit_curr_code")
		   wk_unit_curr_mark  = rec_a("curr_mark")
	   '-- 2007/04/26 Add --
		Else
		   wk_unit_price_o    = 0
		   wk_unit_price_rate = 0
	   '-- 2007/04/26 End --
		End If
    
    End If 'add 2015/04/21
    
	Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Unit Price(ORG)</FONT></TD>" & VbCrLf
	If Request("type") = "NEW" Or (Request("type") = "CHANGE" And Request("delete_type") <> "D") Then
	   Response.Write "<TD><INPUT type='text' name='unit_price_o' value='" & wk_unit_price_o & "' size='14' maxlength='14'></TD>" & VbCrLf
	Else
	   Response.Write "<TD>" & wk_unit_price_o & "<BR></TD>" & VbCrLf
	End If

	Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Unit Price Rate</FONT></TD>" & VbCrLf
	If Request("type") = "NEW" Or (Request("type") = "CHANGE" And Request("delete_type") <> "D") Then
	   Response.Write "<TD><INPUT type='text' name='unit_price_rate' value='" & wk_unit_price_rate & "' size='14' maxlength='14'></TD>" & VbCrLf
	Else
	   Response.Write "<TD>" & wk_unit_price_rate & "<BR></TD>" & VbCrLf
	End If

	Response.Write "<TD bgcolor='#00ff00'>Unit Curr Code</TD>" & VbCrLf
	If Request("type") = "NEW" Or (Request("type") = "CHANGE" And Request("delete_type") <> "D") Then
	   Response.Write "<TD>" & VbCrLf
	   Response.Write " <SELECT name='unit_curr_code'>" & VbCrLf
	   Response.Write "<OPTION value=''>" & VbCrLf

	   '-- 通貨コードの取得
	   sql_c = "select curr_code,curr_mark "
	   sql_c = sql_c & " from currency"
	   sql_c = sql_c & "  where delete_type is null "

	   Set rec_curr = DB.Execute(sql_c)

	   Do While Not rec_curr.EOF
		  selected = ""
		  'add 2015/04/21 Start	  
	      '(i) PackingInformation再読込時
	      If FLG = "1" Then
	         If Request.Form("unit_curr_code") <> "" Then
	         	 If  CLng(Request.Form("unit_curr_code")) =  CLng(rec_curr("curr_code")) Then
	         	 	selected = "selected"
	         	 End If
	         End If
	         
	      '(ii) PackingInformation再読込時以外
	      Else
	      'add 2015/04/21 End
	         
			 '-- 2007/04/26 Chg --
			 ' If (CLng(wk_unit_curr_code) = CLng(rec_curr("curr_code")) Or CLng(wk_company_curr_code) = CLng(rec_curr("curr_code"))) Then
			 '	  selected = "selected"
			 ' End If
			  If CLng(wk_unit_curr_code) > 0 And CLng(wk_unit_curr_code) = CLng(rec_curr("curr_code")) Then
				 selected = "selected"
			  End If
			  If CLng(wk_unit_curr_code) = 0 And CLng(wk_company_curr_code) = CLng(rec_curr("curr_code")) Then
				 selected = "selected"
			  End If
			 '-- 2007/04/26 End --
	      End If 'add 2015/04/21
	      
		  Response.Write "<OPTION VALUE='" & rec_curr("curr_code") & "' " & selected & ">" & rec_curr("curr_mark") & VbCrLf
		  rec_curr.MoveNext
	   Loop

	   Response.Write "</SELECT>" & VbCrLf
	   Response.Write "</TD>" & VbCrLf
	Else
	  '-- 2007/04/26 Chg --
	   'Response.Write "<TD>" & wk_unit_curr_mark & "<BR></TD>" & VbCrLf
	   If CLng(wk_unit_curr_code) > 0 Then
		  Response.Write "<TD>" & wk_unit_curr_mark & "<BR></TD>" & VbCrLf
	   Else
		  Response.Write "<TD>" & wk_company_curr_mark & "<BR></TD>" & VbCrLf
	   End If
	  '-- 2007/04/26 End --
	   Response.Write "<Input Type='hidden' Name='unit_price_o' Value='" & wk_unit_price_o & "'>" & VbCrLf
	   Response.Write "<Input Type='hidden' Name='unit_price_rate' Value='" & wk_unit_price_rate & "'>" & VbCrLf
	   Response.Write "<Input Type='hidden' Name='unit_curr_code' Value='" & wk_unit_curr_code & "'>" & VbCrLf
	   Response.Write "<Input Type='hidden' Name='unit_curr_mark' Value='" & wk_unit_curr_mark & "'>" & VbCrLf
	End If

   '-- 2014/11/17 Y.Hagai Start --
	Response.Write "</TR>"
	Response.Write "<TR>"
	Response.Write "<TD bgcolor='#00ff00'><FONT>Grade Code<BR>(Link from FDK ENERGY)</FONT></TD>" & VbCrLf
	If Request("type") = "NEW" Then
	   'Response.Write "<TD><INPUT type='text' name='grade_code'  size='12' maxlength='10'></TD>" & VbCrLf
	   Response.Write "<TD><INPUT type='text' name='grade_code'  size='12' maxlength='10' value='" & GET_VAL("GRADE_CODE", "grade_code") & "'></TD>" & VbCrLf '// mod 2015/04/21
	ELSEIf Request("type") = "CHANGE" And Request("delete_type") <> "D" Then
	   'Response.Write "<TD><INPUT type='text' name='grade_code' value='" & REC_CHANGE.FIELDS("grade_code") & "' size='12' maxlength='10'></TD>" & VbCrLf
	   Response.Write "<TD><INPUT type='text' name='grade_code' value='" & GET_VAL("GRADE_CODE", "grade_code") & "' size='12' maxlength='10'></TD>" & VbCrLf '// mod 2015/04/21
	Else
	   Response.Write "<TD>" & REC_DEL.FIELDS("grade_code") & "<BR></TD>" & VbCrLf
	End If

	Response.Write "<TD bgcolor='#00ff00'><FONT>Customer Type</FONT></TD>" & VbCrLf

	If Request("type") = "NEW"  Then
	   'Response.Write "<TD><INPUT type='text' name='customer_type'  size='7' maxlength='5'></TD>" & VbCrLf
	   Response.Write "<TD><INPUT type='text' name='customer_type'  size='7' maxlength='5' value='" & GET_VAL("CUSTOMER_TYPE", "customer_type") & "'></TD>" & VbCrLf '// mod 2015/04/21
	ELSEIf Request("type") = "CHANGE" And Request("delete_type") <> "D" Then
	   'Response.Write "<TD><INPUT type='text' name='customer_type' value='" & REC_CHANGE.FIELDS("customer_type") & "' size='7' maxlength='5'></TD>" & VbCrLf
	   Response.Write "<TD><INPUT type='text' name='customer_type' value='" & GET_VAL("CUSTOMER_TYPE", "customer_type") & "' size='7' maxlength='5'></TD>" & VbCrLf '// mod 2015/04/21
	Else
	   Response.Write "<TD>" & REC_DEL.FIELDS("customer_type") & "<BR></TD>" & VbCrLf
	End If
	
   '-- 2015/04/21 NTTk)Hino Start --
'	Response.Write "<TD bgcolor='#00ff00'><FONT>Package Type</FONT></TD>" & VbCrLf
'	If Request("type") = "NEW"  Then
'	   Response.Write "<TD><INPUT type='text' name='PACKAGE_TYPE'  size='7' maxlength='5'></TD>" & VbCrLf
'	ELSEIf Request("type") = "CHANGE" And Request("delete_type") <> "D" Then
'	   'Response.Write "<TD><INPUT type='text' name='PACKAGE_TYPE' value='" & REC_CHANGE.FIELDS("PACKAGE_TYPE") & "' size='7' maxlength='5'></TD>" & VbCrLf
'	   Response.Write "<TD><INPUT type='text' name='PACKAGE_TYPE' value='" & GET_VAL("PACKAGE_TYPE", "PACKAGE_TYPE") & "' size='7' maxlength='5'></TD>" & VbCrLf '// mod 2015/04/21
'	Else
'	   Response.Write "<TD>" & REC_DEL.FIELDS("PACKAGE_TYPE") & "<BR></TD>" & VbCrLf
'	End If
	
	'照会フラグ判定＆セット
	IS_VIEW = False
	If Request.Form("type") = "DELETE" Or _
       (Request.Form("type") = "CHANGE" And Request.Form("delete_type") = "D") Then
       IS_VIEW = True
    End If
	
	'（Package Type）
	Response.Write "<TD bgcolor='#00ff00'><FONT>Package Type</FONT></TD>" & VbCrLf
	Response.Write "<TD>" & VbCrLf
		If IS_VIEW Then
			VAL = GET_VAL("PACKAGE_TYPE", "PACKAGE_TYPE")
			If VAL <> "" Then
				'-- PACKING_TYPE名称の取得
				sql_c = ""
				sql_c = sql_c & " select   PACKING_TYPE_COMMENT "
				sql_c = sql_c & " from     PACKING_TYPE"
				sql_c = sql_c & " where    PACKING_TYPE_JPN = '" & VAL & "' "
				sql_c = sql_c & "   and    PACKING_TYPE_JPN is not null "
				sql_c = sql_c & " order by PACKING_TYPE_JPN "
				Set rec_curr = DB.Execute(sql_c)
				Response.Write rec_curr("PACKING_TYPE_COMMENT") & "&nbsp;"
			End If
		Else
			Response.Write "<SELECT name='PACKAGE_TYPE'>" & VbCrLf
			Response.Write "<OPTION value=''>" & VbCrLf
			
			'-- PACKAGE_TYPEリストの取得
			sql_c = ""
			sql_c = sql_c & " select   PACKING_TYPE_JPN, PACKING_TYPE_COMMENT "
			sql_c = sql_c & " from     PACKING_TYPE"
			sql_c = sql_c & " order by PACKING_TYPE_JPN "
			Set rec_curr = DB.Execute(sql_c)
			
			val = GET_VAL("PACKAGE_TYPE", "PACKAGE_TYPE")
			Do While Not rec_curr.EOF
				selected = ""
				If val = rec_curr("PACKING_TYPE_JPN") Then
					selected = "selected"
				End If
				Response.Write "<OPTION VALUE='" & rec_curr("PACKING_TYPE_JPN") & "' " & selected & ">" & rec_curr("PACKING_TYPE_COMMENT") & VbCrLf
				rec_curr.MoveNext
			Loop
			
			Response.Write "</SELECT>" & VbCrLf
		End If
	Response.Write "</TD>" & VbCrLf
   '-- 2015/04/21 NTTk)Hino End --

'	Response.Write "<Input Type='hidden' Name='grade_code'    Value='" & wk_grade_code & "'>" & VbCrLf
'	Response.Write "<Input Type='hidden' Name='customer_type' Value='" & wk_customer_type & "'>" & VbCrLf
'	Response.Write "<Input Type='hidden' Name='PACKAGE_TYPE'  Value='" & wk_PACKAGE_TYPE & "'>" & VbCrLf

   '-- 2014/11/17 Y.Hagai End --

   '-- 2015/04/21 NTTk)Hino Start --
	Response.Write "</TR>" & VbCrLf
	
	'（Capacity, Date Code Type, Date Code Month）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Capacity</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD>" & GET_VAL("CAPACITY", "capacity") & "&nbsp;</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='capacity' size='18' maxlength='25' value='" & GET_VAL("CAPACITY", "capacity") & "'></TD>" & VbCrLf
		End If
		
		Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Date Code Type</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD>" & GET_VAL("DATE_CODE_TYPE", "date_code_type") & "&nbsp;</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='date_code_type' size='12' maxlength='10' value='" & GET_VAL("DATE_CODE_TYPE", "date_code_type") & "'></TD>" & VbCrLf
		End If
		
		Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Date Code Month</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD>" & GET_VAL("DATE_CODE_MONTH", "date_code_month") & "&nbsp;</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='date_code_month' size='8' maxlength='6' value='" & GET_VAL("DATE_CODE_MONTH", "date_code_month") & "'></TD>" & VbCrLf
		End If
	Response.Write "</TR>" & VbCrLf
	
	'（Label Type, Measurement）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00'><FONT>Label Type</FONT></TD>" & VbCrLf
		Response.Write "<TD>" & VbCrLf
			If IS_VIEW Then
				VAL = GET_VAL("LABEL_TYPE", "label_type")
				If VAL <> "" Then
					'-- LABEL TYPE名称の取得
					sql_c = ""
					sql_c = sql_c & " select   LABEL_TYPE_NAME "
					sql_c = sql_c & " from     LABEL_TYPE"
					sql_c = sql_c & " where    LABEL_TYPE_CODE = " & VAL & " "
					sql_c = sql_c & " order by LABEL_TYPE_CODE "
					Set rec_curr = DB.Execute(sql_c)
					Response.Write rec_curr("LABEL_TYPE_NAME") & "&nbsp;"
				End If
			Else
				Response.Write "<SELECT name='label_type'>" & VbCrLf
				Response.Write "<OPTION value=''>" & VbCrLf
				
				'-- LABEL TYPEリストの取得
				sql_c = ""
				sql_c = sql_c & " select   LABEL_TYPE_CODE, LABEL_TYPE_NAME "
				sql_c = sql_c & " from     LABEL_TYPE"
				sql_c = sql_c & " order by LABEL_TYPE_CODE "
				Set rec_curr = DB.Execute(sql_c)
				
				val = GET_VAL("LABEL_TYPE", "label_type")
				Do While Not rec_curr.EOF
					selected = ""
					If CStr(val) = CStr(rec_curr("LABEL_TYPE_CODE")) Then
						selected = "selected"
					End If
					Response.Write "<OPTION VALUE='" & rec_curr("LABEL_TYPE_CODE") & "' " & selected & ">" & rec_curr("LABEL_TYPE_NAME") & VbCrLf
					rec_curr.MoveNext
				Loop
				
				Response.Write "</SELECT>" & VbCrLf
			End If
		Response.Write "</TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Measurement</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD colspan='3'>" & GET_VAL("MEASUREMENT", "measurement") & "&nbsp;</TD>" & VbCrLf
		Else
	 		Response.Write "<TD colspan='3'><INPUT type='text' name='measurement' size='18' maxlength='25' value='" & GET_VAL("MEASUREMENT", "measurement") & "'></TD>" & VbCrLf
		End If
	Response.Write "</TR>"
	
	'タイトル（高さ、巾、奥行、梱包数）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00'>&nbsp;</TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center'><FONT color='#0000ff'>Height</FONT></TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center'><FONT color='#0000ff'>Width</FONT></TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center'><FONT color='#0000ff'>Depth</FONT></TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center' colspan='2'><FONT color='#0000ff'>Unit Number</FONT></TD>" & VbCrLf
	Response.Write "</TR>" & VbCrLf
	
	'入力欄（INNER_BOX）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00'>Inner Box</TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("INNER_BOX_HEIGHT", "i_height") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("INNER_BOX_WIDTH", "i_width") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("INNER_BOX_DEPTH", "i_depth") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD colspan='2' style='text-align:right'><FONT>" & GET_VAL("INNER_BOX_UNIT_NUMBER", "i_unit_number") & "&nbsp;mm</FONT></TD>" & VbCrLf
		Else
			Response.Write "<TD><FONT><INPUT type='text' name='i_height' size='18' maxlength='25' value='" & GET_VAL("INNER_BOX_HEIGHT", "i_height") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD><FONT><INPUT type='text' name='i_width'  size='18' maxlength='25' value='" & GET_VAL("INNER_BOX_WIDTH", "i_width") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD><FONT><INPUT type='text' name='i_depth'  size='18' maxlength='25' value='" & GET_VAL("INNER_BOX_DEPTH", "i_depth") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD colspan='2'><FONT><INPUT type='text' name='i_unit_number'  size='18' maxlength='25' value='" & GET_VAL("INNER_BOX_UNIT_NUMBER", "i_unit_number") & "' style='text-align:right;'>&nbsp;PC</FONT></TD>" & VbCrLf
		End If
	Response.Write "</TR>" & VbCrLf
	
	'入力欄（MEDIUM_BOX）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00'>Medium Box</TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("MEDIUM_BOX_HEIGHT", "m_height") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("MEDIUM_BOX_WIDTH", "m_width") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("MEDIUM_BOX_DEPTH", "m_depth") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD colspan='2' style='text-align:right'><FONT>" & GET_VAL("MEDIUM_BOX_UNIT_NUMBER", "i_unit_number") & "&nbsp;mm</FONT></TD>" & VbCrLf
		Else
			Response.Write "<TD><FONT><INPUT type='text' name='m_height' size='18' maxlength='25' value='" & GET_VAL("MEDIUM_BOX_HEIGHT", "m_height") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD><FONT><INPUT type='text' name='m_width'  size='18' maxlength='25' value='" & GET_VAL("MEDIUM_BOX_WIDTH", "m_width") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD><FONT><INPUT type='text' name='m_depth'  size='18' maxlength='25' value='" & GET_VAL("MEDIUM_BOX_DEPTH", "m_depth") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD colspan='2'><FONT><INPUT type='text' name='m_unit_number'  size='18' maxlength='25' value='" & GET_VAL("MEDIUM_BOX_UNIT_NUMBER", "m_unit_number") & "' style='text-align:right;'>&nbsp;PC</FONT></TD>" & VbCrLf
		End If
	Response.Write "</TR>" & VbCrLf
	
	'入力欄（OUTER_BOX）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00'>Outer Box</TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("OUTER_BOX_HEIGHT", "o_height") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("OUTER_BOX_WIDTH", "o_width") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD style='text-align:right'><FONT>" & GET_VAL("OUTER_BOX_DEPTH", "o_depth") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD colspan='2' style='text-align:right'><FONT>" & GET_VAL("EXTERNAL_UNIT_NUMBER", "o_unit_number") & "&nbsp;mm</FONT></TD>" & VbCrLf
		Else
			Response.Write "<TD><FONT><INPUT type='text' name='o_height' size='18' maxlength='25' value='" & GET_VAL("OUTER_BOX_HEIGHT", "o_height") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD><FONT><INPUT type='text' name='o_width'  size='18' maxlength='25' value='" & GET_VAL("OUTER_BOX_WIDTH", "o_width") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD><FONT><INPUT type='text' name='o_depth'  size='18' maxlength='25' value='" & GET_VAL("OUTER_BOX_DEPTH", "o_depth") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD colspan='2'><FONT><INPUT type='text' name='o_unit_number'  size='18' maxlength='25' value='" & GET_VAL("EXTERNAL_UNIT_NUMBER", "o_unit_number") & "' style='text-align:right; border:none;' readonly>&nbsp;PC</FONT></TD>" & VbCrLf
		End If
	Response.Write "</TR>" & VbCrLf
	
	'---------------------------------------------------------------
	' Packing Information
	'---------------------------------------------------------------
	'（Packing Information, PLT Spec No., Pallet Size Type）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' nowrap><FONT>Packing Information No.</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD style='text-align:right'>&nbsp;" & GET_VAL_CMN("PI_NO", "packing_information", "PI") & "</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='packing_information' size='22' maxlength='20' value='" & GET_VAL_CMN("PI_NO", "packing_information", "PI") & "' onChange='get_pack_info()'></TD>" & VbCrLf
		End If
		
		Response.Write "<TD bgcolor='#00ff00'><FONT>PLT Spec No.</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD style='text-align:right'>&nbsp;" & GET_VAL_CMN("PLT_SPEC_NO", "plt_spec_no", "PI") & "</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='plt_spec_no' size='22' maxlength='20' value='" & GET_VAL_CMN("PLT_SPEC_NO", "plt_spec_no", "PI") & "'></TD>" & VbCrLf
		End If
		
		Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Pallet Size Type</FONT></TD>" & VbCrLf
		Response.Write "<TD>" & VbCrLf
			VAL = GET_VAL_CMN("PALLET_SIZE_TYPE", "pallet_size_type", "PI")
			
			If IS_VIEW Then
				If VAL <> "" Then
					'-- PALLET_SIZE_TYPE名称の取得
					sql_c = ""
					sql_c = sql_c & " select   PALLET_SIZE_TYPE_NAME "
					sql_c = sql_c & " from     PALLET_SIZE_TYPE"
					sql_c = sql_c & " where    PALLET_SIZE_TYPE_CODE = " & VAL & " "
					sql_c = sql_c & " order by PALLET_SIZE_TYPE_CODE "
					Set rec_curr = DB.Execute(sql_c)
					Response.Write rec_curr.Fields("PALLET_SIZE_TYPE_NAME")
				End If
				
			Else
				Response.Write "<SELECT name='pallet_size_type'>" & VbCrLf
				Response.Write "<OPTION value=''>" & VbCrLf
				
				'-- PALLET_SIZE_TYPEリストの取得
				sql_c = ""
				sql_c = sql_c & " select   PALLET_SIZE_TYPE_CODE, PALLET_SIZE_TYPE_NAME "
				sql_c = sql_c & " from     PALLET_SIZE_TYPE"
				sql_c = sql_c & " order by PALLET_SIZE_TYPE_CODE "
				Set rec_curr = DB.Execute(sql_c)
				
				Do While Not rec_curr.EOF
					selected = ""
					If CStr(VAL) = CStr(rec_curr("PALLET_SIZE_TYPE_CODE")) Then
						selected = "selected"
					End If
					Response.Write "<OPTION VALUE='" & rec_curr("PALLET_SIZE_TYPE_CODE") & "' " & selected & ">" & rec_curr("PALLET_SIZE_TYPE_NAME") & VbCrLf
					rec_curr.MoveNext
				Loop
				
				Response.Write "</SELECT>" & VbCrLf
			End If
		Response.Write "&nbsp;</TD>" & VbCrLf
	Response.Write "</TR>" & VbCrLf
	
	'タイトル（高さ、巾、奥行、梱包数）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' rowspan='4'>Pallet</TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center'><FONT color='#0000ff'>Height</FONT></TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center'><FONT color='#0000ff'>Width</FONT></TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center'><FONT color='#0000ff'>Depth</FONT></TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center' colspan='2'><FONT color='#0000ff'>Unit Number</FONT></TD>" & VbCrLf
	Response.Write "</TR>" & VbCrLf
	
	'入力欄（PALLET）
	Response.Write "<TR>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD rowspan='3' style='text-align:right'><FONT>" & GET_VAL_CMN("PALLET_HEIGHT", "p_height", "PI") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD rowspan='3' style='text-align:right'><FONT>" & GET_VAL_CMN("PALLET_WIDTH", "p_width", "PI") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD rowspan='3' style='text-align:right'><FONT>" & GET_VAL_CMN("PALLET_DEPTH", "p_depth", "PI") & "&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD colspan='2' style='text-align:right'><FONT>" & GET_VAL_CMN("PALLET_UNIT_NUMBER", "p_unit_number", "PI") & "&nbsp;mm</FONT></TD>" & VbCrLf
		Else
			Response.Write "<TD rowspan='3'><FONT><INPUT type='text' name='p_height' size='18' maxlength='25' value='" & GET_VAL_CMN("PALLET_HEIGHT", "p_height", "PI") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD rowspan='3'><FONT><INPUT type='text' name='p_width'  size='18' maxlength='25' value='" & GET_VAL_CMN("PALLET_WIDTH", "p_width", "PI") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD rowspan='3'><FONT><INPUT type='text' name='p_depth'  size='18' maxlength='25' value='" & GET_VAL_CMN("PALLET_DEPTH", "p_depth", "PI") & "' style='text-align:right;'>&nbsp;mm</FONT></TD>" & VbCrLf
			Response.Write "<TD colspan='2'><FONT><INPUT type='text' name='p_unit_number'  size='18' maxlength='25' value='" & GET_VAL_CMN("PALLET_UNIT_NUMBER", "p_unit_number", "PI") & "' style='text-align:right;'>&nbsp;PC</FONT></TD>" & VbCrLf
		End If
	Response.Write "</TR>" & VbCrLf
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center'><FONT color='#0000ff'>Ctn Number</FONT></TD>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00' align='center'><FONT color='#0000ff'>Step Ctn Numbetr</FONT></TD>" & VbCrLf
	Response.Write "</TR>" & VbCrLf
	Response.Write "<TR>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD style='text-align:right'>" & GET_VAL_CMN("PALLET_CTN_NUMBER", "p_ctn_number", "PI") & "&nbsp;</TD>" & VbCrLf
			Response.Write "<TD style='text-align:right'>" & GET_VAL_CMN("PALLET_STEP_CTN_NUMBER", "p_step_ctn_number", "PI") & "&nbsp;</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='p_ctn_number'       size='18' maxlength='25' value='" & GET_VAL_CMN("PALLET_CTN_NUMBER", "p_ctn_number", "PI") & "' style='text-align:right;'></TD>" & VbCrLf
			Response.Write "<TD><INPUT type='text' name='p_step_ctn_number'  size='18' maxlength='25' value='" & GET_VAL_CMN("PALLET_STEP_CTN_NUMBER", "p_step_ctn_number", "PI") & "' style='text-align:right;'></TD>" & VbCrLf
		End If
	Response.Write "</TR>" & VbCrLf
	
	'（Operation Time, Man Power, Aging Day）
	Response.Write "<TR>" & VbCrLf
		Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Operation Time</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD>" & GET_VAL("OPERATION_TIME", "operation_time") & "&nbsp;</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='operation_time' size='18' maxlength='25' value='" & GET_VAL("OPERATION_TIME", "operation_time") & "'></TD>" & VbCrLf
		End If
		
		Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Man Power</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD>" & GET_VAL("MAN_POWER", "man_power") & "&nbsp;</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='man_power' size='18' maxlength='25' value='" & GET_VAL("MAN_POWER", "man_power") & "'></TD>" & VbCrLf
		End If
		
		Response.Write "<TD bgcolor='#00ff00'><FONT color='#0000ff'>Aging Day</FONT></TD>" & VbCrLf
		If IS_VIEW Then
			Response.Write "<TD>" & GET_VAL("AGING_DAY", "aging_day") & "&nbsp;</TD>" & VbCrLf
		Else
			Response.Write "<TD><INPUT type='text' name='aging_day' size='5' maxlength='3' value='" & GET_VAL("AGING_DAY", "aging_day") & "'></TD>" & VbCrLf
		End If
	Response.Write "</TR>" & VbCrLf
   '-- 2015/04/21 NTTk)Hino End --


%>
  </TR>
<!-- 2007/01/22 End -->

</Table>
</Form>
</Div>
</Body>
</Html>
<%
 On Error Resume Next
 rec_curr.Close
 If FLG = "1" Then
 	REC_PI.Close
 End If
%>
<% DB.Close %>
