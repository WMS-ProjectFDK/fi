<%@ Language="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
'****************************************
'		�f�[�^�x�[�X���J��
'****************************************
Set db = Server.CreateObject("ADODB.Connection")	'DB�I�u�W�F�N�g�̍쐬
db.open server_name,server_user,server_psword		'DB�ڑ��̃I�[�v��

'****************************************
'		POST�f�[�^�ǂݍ���
'****************************************
types = Request("types") 

'****************************************
'		�^�C�g���\��
'****************************************
 call html_header(title_master&"����",KEYWORD)
 
'****************************************
'			��������
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

 
'------ SQL���s ------
 SQL =       "SELECT "
 SQL = SQL & "   item_type, "
 SQL = SQL & "   item_type_name  "
 SQL = SQL & " FROM " & types 
 SQL = SQL & " ORDER BY item_type "
'Response.Write SQL 
'Response.End

 Set rec = db.Execute(SQL)

'�Y������f�[�^���Ȃ��ꍇ
 if rec.BOF and rec.EOF then
    rec.Close                                            '���R�[�h�����
 	Response.Write "�Y���f�[�^������܂���B<BR><BR>" & vbCRLF
 	Response.Write "[<A HREF=""javascript:this.close()"">�߂�</A>]" & vbCRLF
 	Response.End 
 end if

Response.Write "<TABLE border='1' CELLPADDING=2 CELLSPACING=0>" & vbCRLF
Response.Write "<TR>" & vbCRLF
Response.Write "<TD bgcolor='aliceblue' align='center'>TYPE</TD>" & vbCRLF
Response.Write "<TD bgcolor='aliceblue' align='center'>DESCRIPTION</TD>" & vbCRLF
Response.Write "</TR>" & vbCRLF
num = 0
do while not rec.EOF
	'�t�H���g�J���[�̐ݒ�
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

Response.Write "[<A HREF='javascript:this.close() ;'>����</A>]" & vbCRLF

rec.Close
%>
</CENTER>
</BODY>
</HTML>
