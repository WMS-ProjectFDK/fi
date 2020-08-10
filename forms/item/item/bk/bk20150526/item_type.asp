<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
'****************************************
'		データベースを開く
'****************************************
Set db = Server.CreateObject("ADODB.Connection")	'DBオブジェクトの作成
db.open server_name,server_user,server_psword		'DB接続のオープン

'****************************************
'		POSTデータ読み込み
'****************************************
types = Request("types") 

'****************************************
'		タイトル表示
'****************************************
 call html_header(title_master&"検索",KEYWORD)
 
'****************************************
'			検索項目
'****************************************
 Response.Write "<FORM METHOD=POST NAME=myform>" 

 %>
 <SCRIPT language="JavaScript">
	this.focus() ;

    function setData(num){
		opener.document.data_send.<%=types%>.value = document.myform['item_type' + num].value ;
		this.close() ;
	}
 </SCRIPT>
 <%

 
'------ SQL発行 ------
 SQL =       "SELECT "
 SQL = SQL & "   item_type, "
 SQL = SQL & "   item_type_name  "
 SQL = SQL & " FROM " & types 
 SQL = SQL & " ORDER BY item_type "
'Response.Write SQL 
'Response.End

 Set rec = db.Execute(SQL)

'該当するデータがない場合
 if rec.BOF and rec.EOF then
    rec.Close                                            'レコードを閉じる
 	Response.Write "該当データがありません。<BR><BR>" & vbCRLF
 	Response.Write "[<A HREF=""javascript:this.close()"">戻る</A>]" & vbCRLF
 	Response.End 
 end if

Response.Write "<TABLE border='1' CELLPADDING=2 CELLSPACING=0>" & vbCRLF
Response.Write "<TR>" & vbCRLF
Response.Write "<TD bgcolor='aliceblue' align='center'>TYPE</TD>" & vbCRLF
Response.Write "<TD bgcolor='aliceblue' align='center'>DESCRIPTION</TD>" & vbCRLF
Response.Write "</TR>" & vbCRLF
num = 0
do while not rec.EOF
	'フォントカラーの設定
	F_COLOR="BLACK"

	Response.Write "<TR>" & vbCRLF
	Response.Write "<TD><A HREF=""javascript:setData('" & num & "')"">" & rec("ITEM_TYPE") &"</A>"
	Response.write "</TD>" & vbCRLF
	Response.Write "<TD><FONT COLOR='"&F_COLOR&"'>" & rec("ITEM_TYPE_NAME") & "</FONT><BR></TD>" & vbCRLF
	Response.Write "</TR>" & vbCRLF
	Response.Write "<INPUT TYPE=Hidden NAME=""item_type" & num & """ VALUE=""" & replace(rec("ITEM_TYPE")&"","""","&quot;") & """>" 
	rec.MoveNext
	num = num +1
loop

Response.Write "</TABLE>" & vbCRLF
Response.Write "<BR>" & vbCRLF

Response.Write "[<A HREF='javascript:this.close() ;'>閉じる</A>]" & vbCRLF

rec.Close
%>
</CENTER>
</BODY>
</HTML>
