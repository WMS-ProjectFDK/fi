<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
KEYWORD = Request("KEYWORD")


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
<Input Type="radio" Name="no_type" Value="GROUP" CHECKED>COMMON TO FDK GROUP
<Input Type="radio" Name="no_type" Value="BASE">BASE ONLY<Br>
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
    DO While not Rec_stocksubject.EOF %>
    <Option Value="<%= Rec_stocksubject.Fields("STOCK_SUBJECT_CODE") %>"><%= Rec_stocksubject.Fields("STOCK_SUBJECT") %>
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
<Input Type="text" Name="key" Size="20"><Br>
<Input Type="radio" Name="seek_key" Value="ITEM NO" Checked>Item No
<Input Type="radio" Name="seek_key" Value="ITEM NAME or DESCRIPTION">Item Name or Description
<Input Type="submit" Name="seek_button" Value="Seek">
</Form>
</Div>
[<A HREF=../../main.asp?KEYWORD=<%=KEYWORD%>&menuType=master>BACK</A>]
</Body>
</Html>
<% DB.Close %>
