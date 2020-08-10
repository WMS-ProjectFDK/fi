<%@ Language="VBScript" %>
<%
'ITEM_CLEANSING1.asp
'ITEM_CLEANSING DOWNLOAD/UPLOAD
'2013.02.XX M.KAMIYAMA
%>
<!--#include file = "../../common.inc"-->
<%
KEYWORD = Request("KEYWORD")

  call html_header("Item Cleansing DL/UL",KEYWORD)
%>
<%
'****************************************
'   リンク先設定
'****************************************
 LINK_BACK    = "../../main.asp?KEYWORD=" & KEYWORD & "&menuType=master"
 LINK_CURRENT = "item_cleansing1.asp"
' LINK_NEXT = "cell_grade2.asp?KEYWORD=" & KEYWORD & "&menuType=master"

%>

<Div Align="left">
<!--外部JavaScriptファイル-->
<SCRIPT LANGUAGE="JavaScript" SRC="funcs.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="item_cleansing1.js"></SCRIPT>
<!--<form method="post" action="<%= LINK_NEXT %>" name="frm1" target="_self" id="frm1">-->
<form method='post' enctype='multipart/form-data' name='myform' id="myform">
<div id="div-comment">
<H3 Align="left">Item Master Cleansing Data Download/Upload</H3><Br>
</div>
<div id="div-main">
<table border="1" cellpadding="2" cellspacing="0" style="font-size:smaller;">
<tr>
<td style="background-color:#32CD32;text-align:center;">UPLOAD</td>
<td>
<font color="Red"><b>Please upload LIST FILE saved as Excel Workbook.</b></FONT><br/>
<font color="Blue">UPFILE : </FONT>
<input type="file" name="upfile" size="50" id="upfile">
<input type="button" value="UPLOAD" name="btnul" id="btnul">
</td>
</tr>
<tr>
<td style="background-color:#32CD32;text-align:center;">DOWNLOAD</td>
<td><input type="button" name="btndl" value="DOWNLOAD" id="btndl"></td>
</tr>
</table>

</div>
<br/>
[<A HREF="<%= LINK_BACK %>">BACK</A>]
<br/>
<div id="div-list"></div>
<input type="hidden" name="mode" id="mode">
<input type="hidden" name="KEYWORD" id="KEYWORD" value=<%=KEYWORD%>>
</form>
</Div>
</Body>
</Html>

