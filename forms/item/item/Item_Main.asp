<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
'-- 2015/04/21 NTTk)Hino  項目追加対応（CAPACITY他 計28項目）

KEYWORD = Request("KEYWORD")

'add 2015/04/21 Start
NO_TYPE   = Request.QueryString("NO_TYPE")     '(新規)前画面.ITEM NO TYPE
STOCK_SUB = Request.QueryString("STOCK_SUB")   '(新規)前画面.STCK_SUBJECT_CODE
KEY       = Request.QueryString("KEY")         '(変更/削除)前画面.KEY
SEEK_KEY  = Request.QueryString("SEEK_KEY")    '(変更/削除)前画面.SEEK_KEY
'add 2015/04/21 End

  call html_header("Item Main",KEYWORD)
%>
<%
'---------
'DB OPEN
'---------
Set db = Server.CreateObject("ADODB.Connection")
db.Open server_name,server_user,server_psword
%>
<H3 Align="left">Select Menu New,Change or Delete</H3><Br>
<Div Align="left">
<Form Method="post" Action="Item_Entry.asp?KEYWORD=<%=KEYWORD%>" Name="new_entry" Target="_self">
<Font Size="3" Color="#00ff40" >1:New Item Entry</Font><Br>
ITEM NO TYPE:
<%
  'mod 2015/04/21 Start
  'If (Session("NGM") = "1") Then
  '	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='GROUP' CHECKED>Make an NG Materials automatically"
  '	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='BASE'>BASE ONLY (Finished Goods)"
  '	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='NGM'>Make only an NG Materials"
  'Else
  '	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='GROUP' CHECKED>COMMON TO FDK GROUP"
  '	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='BASE'>BASE ONLY"
  'End If
  
  If NO_TYPE = "GROUP" Then
  	CHECK_G = "checked"
  ElseIf NO_TYPE = "BASE" Then
  	CHECK_B = "checked"
  ElseIf NO_TYPE = "NGM" Then
  	CHECK_N = "checked"
  Else
  	CHECK_G = "checked"
  End If
  
  If (Session("NGM") = "1") Then
	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='GROUP' " & CHECK_G & " >Make an NG Materials automatically"
	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='BASE' " & CHECK_B & " >BASE ONLY (Finished Goods)"
	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='NGM' " & CHECK_N & " >Make only an NG Materials"
  Else
	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='GROUP' " & CHECK_G & " >COMMON TO FDK GROUP"
	RESPONSE.WRITE "<Input Type='radio' Name='no_type' Value='BASE' " & CHECK_B & " >BASE ONLY"
  End If
  'mod 2015/04/21 End
%>
<Br>
STOCK SUBJECT:
<Select Name="stock_subject_code" Size="1">
 <Option Value="">
<%
'------------------------
' stock subject code get
'------------------------
SQL_stocksubject = "SELECT  STOCK_SUBJECT_CODE, STOCK_SUBJECT " & _
                   "  FROM STOCK_SUBJECT " & _
                   " ORDER BY STOCK_SUBJECT "
Set Rec_stocksubject = DB.Execute(SQL_stocksubject)
   IF Rec_stocksubject.eof or Rec_stocksubject.bof then %>
    <Option Value="">NO DATA FOUND
<% ELSE
    DO While not Rec_stocksubject.EOF
    
	'add 2015/04/21 Start
	SEL = ""
	If Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") = STOCK_SUB Then
		SEL = "selected"
	End If
	'add 2015/04/21 End
    %>
    <!-- <Option Value="<%= Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") %>"><%= Rec_stocksubject.Fields("STOCK_SUBJECT") %>-->
    <Option Value="<%= Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") %>" <%= SEL %>><%= Rec_stocksubject.Fields("STOCK_SUBJECT") %><!-- mod 2015/04/21 -->
    <% Rec_stocksubject.Movenext
    LOOP %>
<% END IF %>
</Select><Br>
NEW ENTRY<Input Type="submit" Name="new_entry_button" Value="ENTRY">
<Input Type="hidden" Name="type" Value="NEW">
<Input Type="hidden" Name="grp_flg" Value=" ">
</Form>
<Form Method="post" Action="Item_Seek.asp?KEYWORD=<%=KEYWORD%>" Name="change_delete" Target="_self">
<Font Size="3" Color="#00ff40" >2:Item Change or Delete</Font><Br>
Seek key(a part of Item No, Item Name or Description)
<!--<Input Type="text" Name="key" Size="20"><Br>-->
<Input Type="text" Name="key" Size="20" Value="<%= KEY %>"><Br><!-- mod 2015/04/21 -->
<%
'add 2015/04/21 Start
If SEEK_KEY = "ITEM NO" Then
	SEL_1 = "checked"
ElseIf SEEK_KEY = "ITEM NAME or DESCRIPTION" Then
	SEL_2 = "checked"
Else
	SEL_1 = "checked"
End If
'add 2015/04/21 End
%>
<Input Type="radio" Name="seek_key" Value="ITEM NO" <%= SEL_1 %> >Item No
<Input Type="radio" Name="seek_key" Value="ITEM NAME or DESCRIPTION" <%= SEL_2 %> >Item Name or Description
<Input Type="submit" Name="seek_button" Value="Seek">
</Form>
</Div>
[<A HREF=../../main.asp?KEYWORD=<%=KEYWORD%>&menuType=master>BACK</A>]
</Body>
</Html>
<% DB.Close %>
