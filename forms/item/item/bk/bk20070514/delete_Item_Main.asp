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
<H3 Align="left">DELETE ITEM MASTER</H3><Br>
<Div Align="left">
<Form Method="post" Action="delete_item_seek.asp?KEYWORD=<%=KEYWORD%>" Name="change_delete" Target="_self">
<Font Size="3" Color="#00ff40" >1: Delete</Font><Br>
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
