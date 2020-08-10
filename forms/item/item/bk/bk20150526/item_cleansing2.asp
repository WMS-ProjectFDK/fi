<%@ LANGUAGE="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
'   �v���O������ ITEM_CLEANSING2.asp
'   �f�[�^�����[�h���Ĕ���A���c�����`�F�b�N���܂��B
'   2013.02.XX M.KAMIYAMA
'   item_add_sf_ul.asp���R�s�[�A�����e
%>
<%
	Dim msg               '���b�Z�[�W�i����j
	Dim msgErr            '���b�Z�[�W�i�ُ�j
	Dim msgRollback       '���b�Z�[�W�iRollback�j
	
	Dim wkSysdate         '�V�X�e�����t
	
	Dim UploadFileName    '�A�b�v���[�h��̃t�@�C����
	UploadFileName = "item_master_cleansing_form.xls" '�i�Œ�l�j
	
	Dim	Sheet1Name        '�ΏۃV�[�g���i�P�ځj
	Sheet1Name = "REMOVE_LIST"   '�i�Œ�l�j
	
	DIm Sheet1Exists      '�ΏۃV�[�g�̑��ݗL���i�P�ځj
	
	'Init()�ŃZ�b�g
	Dim LINK_BACK
	Dim totalBytes
	Dim	requestBinary
	
	'Func2()�ŃZ�b�g
	Dim ExecResult        '�c�a�X�V����
	
	'�I���������K�v�ȕϐ�
	Dim objBasp          'BASP21
	Dim objFso           'FileSystemObject
	Dim objXls           'Excel.Application
	Dim xlsBook          'objXls.Workbook
	Dim objSheet         'xlsBook.ActiveWorkbook.Sheets
	Dim xlsSheet         'xlsBook.Worksheet
	Dim objDb            'ADODB.Connection
	
	Dim fileName 'upload file name
	Dim KEYWORD
	
	'-------------------------------------
	'�u0.���������v�ďo
	'-------------------------------------
	Call Init()
	
	'-------------------------------------
	'�u1.Excel�t�@�C���̓Ǎ��v�ďo
	'-------------------------------------
	If msgErr = "" Then
		Call Func1()
	End If
	
	'-------------------------------------
	'�u2.�f�[�^�����v�ďo
	'-------------------------------------
	If msgErr = "" Then
		Call Func2()
	End If
	
	'-------------------------------------
	'�u9.�I�������v�ďo
	'-------------------------------------
	Call Finally()
	
	
	
	'***********************************************************
	' 0.��������
	'***********************************************************
	Sub Init()
		On Error Resume Next
		'�����l�ݒ�
		msg          = "ITEM MASTER CLEANSING LIST UPLOAD IS SUCCESSFUL."
		msgErr       = ""
		msgRollback  = ""
		wkSysdate    = Now
		Sheet1Exists = False
'		Sheet2Exists = False
		
		'BASP�I�u�W�F�N�g����
		set objBasp = Server.CreateObject(basp_str)
		
		If Err.Number <> 0 Then
			setErrMsg_Exception "CreateObject BASP21. (0)", Err.Description
			
		Else
			'Form�l�̎擾
			totalBytes     = Request.TotalBytes
			requestBinary  = Request.BinaryRead(totalBytes)
			If Err.Number <> 0 Then
				setErrMsg_Exception "Request.BinaryRead(totalBytes). (0)", Err.Description
			End If
			
			KEYWORD        = objBasp.Form(requestBinary, "KEYWORD")
			
			'[BACK]�����N
			LINK_BACK = "item_cleansing1.asp?KEYWORD=" & KEYWORD
		End If
		
		On Error Goto 0
	End Sub
	
	
	'***********************************************************
	' 1.Excel�t�@�C���̓Ǎ�
	'***********************************************************
	Sub Func1()
		On Error Resume Next
		
		'-------------------------------------------------
		'�i1-1�jExcel�t�@�C���A�b�v���[�h
		'-------------------------------------------------
		'�t�@�C���V�X�e���I�u�W�F�N�g�̐���
		Set objFso = Server.CreateObject("Scripting.FileSystemObject")
		
		'�O��ʂœ��͂��ꂽ�t�@�C���p�X�i��΃p�X�j
		filePath = objBasp.FormFileName(requestBinary, "upfile")
		'�t�@�C���T�C�Y
		fileSize = objBasp.FormFileSize(requestBinary, "upfile")
		'�O��ʂœ��͂��ꂽ�t�@�C�����̂�
		fileName = Mid(filePath, InstrRev(filePath, "\") + 1)
		'objFso�Ƀp�X��ݒ�
		fsoPath = objFso.BuildPath(Server.MapPath("./temp/" ), UploadFileName)
		
		'�t�@�C���A�b�v���[�h
		leng = objBasp.FormSaveAs(requestBinary, "upfile", fsoPath)
		
		'�A�b�v���[�h�G���[�`�F�b�N
		If leng <= 0 Then
			setErrMsg_Upload "", leng, fsoPath
		'�A�b�v���[�h�����t�@�C���̑��݃`�F�b�N
		ElseIf objFso.FileExists(fsoPath) = False Then
			setErrMsg_Upload "File was not uploaded.", leng, fsoPath
		End If
		
		
		'-------------------------------------------------
		'�i1-2�j�V�[�g���݃`�F�b�N
		'-------------------------------------------------
		'Excel�A�v���P�[�V�����I�u�W�F�N�g�̐���
		If msgErr = "" Then
			Set objXls  = Server.CreateObject("Excel.Application")
			If Err.Number <> 0 Then
				setErrMsg_Exception "CreateObject ExcelApplication. (1-2)", Err.Description
			End If
			
			'���ׂẴV�[�g�̃V�[�g�����`�F�b�N
			Set xlsBook = objXls.workbooks.Open(fsoPath, 0, True)
			Set objSheet = objXls.ActiveWorkbook.Sheets
			For Each objSheet In objXls.ActiveWorkbook.Sheets
				sheetName = objSheet.Name
				If sheetName = Sheet1Name Then
					Sheet1Exists = True
				End If
				Set objSheet = Nothing
			Next
			Set objSheet = Nothing
			
			'�uMST�v�V�[�g���݂��Ȃ��ꍇ�A�G���[
'			If Sheet1Exists = False And Sheet2Exists = False Then
			If Sheet1Exists = False Then
			   	msgErr = "LIST DATA SHEET NOT EXIST IN THIS FILE."
			End If
		End If
		
		On Error Goto 0
	End Sub
	
	
	'***********************************************************
	' 2.�f�[�^����
	'***********************************************************
	Sub Func2()
		On Error Resume Next
		'-------------------------------------------------
		'�i2-1�j�f�[�^�`�F�b�N
		'-------------------------------------------------
		If Sheet1Exists = True Then
			checkNumeric Sheet1Name
		End If
		
'		If Sheet2Exists = True and msgErr = "" Then
'			checkNumeric Sheet2Name
'		End If
		
		
		'-------------------------------------------------
		'�i2-2�j�c�a�X�V����
		'-------------------------------------------------
		If msgErr = "" Then
			ExecResult = True  '�i�����l�j
			
			'�c�a�ڑ�
			set objDb = Server.CreateObject("ADODB.Connection")
			objDb.Open server_name, server_user, server_psword
			If Err.Number <> 0 Then
				ExecResult = False
				setErrMsg_Exception "DB Connect. (2-2)", Err.Description
			End If
			
			
			BeginTrans '������ �g�����U�N�V�����J�n ������
			
			'---------------------------------------------
			' (2-2-1) �f�[�^�폜
			'[ITEM_CLEANSING_TMP] Delete
			SQL2 = " delete from ITEM_CLEANSING_TMP "
			
			If ExecResult = True Then
				objDb.Execute(SQL2)
				If objDb.Errors.Count <> 0 Then
					ExecResult = False
					RollbackTrans   'Rollback����
					setErrMsg_Sql "Delete ITEM_CLEANSING_TMP"
				End If
			End If
			

			'---------------------------------------------
			' (2-2-2) �f�[�^�C���T�[�g
			'---------------------------------------------
			
			'[ITEM_CLEANSING_TMP] Insert
			If ExecResult = True And Sheet1Exists = True Then
				insertICT Sheet1Name
			End If
			
			'---------------------------------------------
			' (2-2-3) DB�`�F�b�N
			'---------------------------------------------
			
			'[ITEM_CLEANSING_TMP] check db
			If ExecResult = True And Sheet1Exists = True Then
				checkDB
			End If
			
			
			'---------------------------------------------
			' (2-2-3) �R�~�b�g
			'---------------------------------------------
			'���ׂĂ̏���������ɏI��
			If ExecResult = True Then
				CommitTrans    'Commit����
			End If
			
			
			'�c�a�ڑ�����
			objDb.RollbackTrans  'Rollback�����i�g�����U�N�V�����I����ł���΋�U�聦�j
			objDb.Close
			Set objDb = Nothing
			
			'ExcelApplication���
			xlsBook.Saved = True '�ۑ��ς݂Ƃ������Ƃɂ���i�v���Z�X�c���h�~�΍�j
			xlsBook.Close
			Set xlsBook = Nothing
			objXls.Visible = True '��U�\��������i�v���Z�X�c���h�~�΍�j
			objXls.Quit
			Set objXls = Nothing
		End If
		
		On Error Goto 0
	End Sub
	
	
	'***********************************************************
	' 9.�I�������i�I�u�W�F�N�g�̔j���A�c�a�̐ڑ������Ȃǁj
	'***********************************************************
	Sub Finally()
		On Error Resume Next
			objDb.RollbackTrans  'Rollback�����i���j
			objDb.Close
			Set objDb = Nothing
			
			xlsBook.Saved = True '�ۑ��ς݂Ƃ������Ƃɂ���i�v���Z�X�c���h�~�΍�j
			xlsBook.Close
			Set xlsBook = Nothing
			objXls.Visible = True '��U�\��������i�v���Z�X�c���h�~�΍�j
			objXls.Quit
			Set objXls = Nothing
		On Error Goto 0
		
		Set objBasp  = Nothing
		Set objFso   = Nothing
		Set xlsSheet = Nothing
		Set objSheet = Nothing
	End Sub
%>

<% 
	'***********************************************************
	' HTML����
	'***********************************************************

	'�`����<html>�`<body>�^�O�́Ahtml_header����������icommon.inc�Q�Ɓj
	call html_header("Item Cleansing Check Result", KEYWORD)
%>
<form method="post" action="./item_cleansing3.asp" name="frm1" target="_self" id="frm1">
<div align="left">
<% if msgErr = "" then %>
	<SCRIPT LANGUAGE="JavaScript" SRC="funcs.js"></SCRIPT>
	<SCRIPT LANGUAGE="JavaScript" SRC="item_cleansing2.js"></SCRIPT>
<% end if %>
	<div id="div-comment">
		<h3 align="left">Item Cleansing Check Result :</h3>
	</div>
	<div id="div-main">
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td width="20px">&nbsp;</td>
		<td>
		<table border="1" cellpadding="10" cellspacing="1">
		<tr>
		<td>
			<% If msgErr <> "" Then %>
				<font color="red"><%= msgErr %></font>
				<input type="hidden" name="result" id="result" value="NG">
			<% Else %>
				<font><%= msg %></font>
				<input type="hidden" name="result" id="result" value="OK">
			<% End If %>
		</td>
		</tr>
		</table>
		</td>
		</tr>
	</table>
	<% if msgErr = "" then %>
		<input type="radio" name="rd1" id="rdAll" checked>ALL
		<input type="radio" name="rd1" id="rdOk">OK
		<input type="radio" name="rd1" id="rdNg">NG
		<input type="button" name="btnview" id="btnview" value="VIEW">
		<input type="button" name="btnremove" id="btnremove" value="REMOVE">
	<% end if %>
	</div>
<br>
<br>
<% if msgErr = "" then %>
	<div id="div-list"></div>
<% end if %>
<input type="hidden" name="KEYWORD" id="KEYWORD" value=<%=KEYWORD%>>
<input type="hidden" name="orderby" id="orderby">
<input type="hidden" name="removable_cnt" id="removable_cnt">
</form>
[<a href="<%= LINK_BACK %>">BACK</A>]
<br>
</div>
</body>
</html>


<%
	'***********************************************************
	' �����ʏ�����
	'***********************************************************
	'-----------------------------------------------------------
	'�f�[�^�`�F�b�N
	'-----------------------------------------------------------
	Sub checkNumeric( SheetName )
		On Error Resume Next
		
		Set xlsSheet = xlsBook.Worksheets( SheetName )
		
'		For row = 4 To 65536
		For row = 2 To 65536
			'1�s�ڂ���ǂ݁A[A��]:ITEM NO �ɓ��͂�����Ԃ͏����𑱍s
			If xlsSheet.Cells(row, 1).Value = "" Then 
				Exit For
			End If
				
			'�o���f�[�V�����`�F�b�N�i���l�j
			' --- Excel[A��]:ITEM NO
			If IsNumeric(xlsSheet.Cells(row, 1).Value) = False Then
				setErrMsg_Valid SheetName, row, "ITEM_NO"
				Exit For
			End If
'			'�K�v���ڂ̂ǂꂩ���ݒ肳��Ă�����`�F�b�N
'			if xlsSheet.Cells(row, 4).Value <> "" or xlsSheet.Cells(row, 5).Value <> "" or xlsSheet.Cells(row, 6).Value <> "" or _
'			   xlsSheet.Cells(row, 7).Value <> "" or xlsSheet.Cells(row, 8).Value <> "" then
'				'���ݒ�`�F�b�N
'				' --- Excel[D��]:SALES GROUP
'				If xlsSheet.Cells(row, 4).Value = "" Then
'					setErrMsg_Null SheetName, row, "SALES_GROUP"
'					Exit For
'				End If
'				' --- Excel[E��]:CELL_TYPE
'				If xlsSheet.Cells(row, 5).Value = "" Then
'					setErrMsg_Null SheetName, row, "CELL_TYPE"
'					Exit For
'				End If
'				' --- Excel[F��]:PACKING_TYPE
'				If xlsSheet.Cells(row, 6).Value = "" Then
'					setErrMsg_Null SheetName, row, "PACKING_TYPE"
'					Exit For
'				End If
'				' --- Excel[G��]:CELL_GRADE
'				If xlsSheet.Cells(row, 7).Value = "" Then
'					setErrMsg_Null SheetName, row, "CELL_GRADE"
'					Exit For
'				End If
'				' --- Excel[H��]:END_USER
'				If xlsSheet.Cells(row, 8).Value = "" Then
'					setErrMsg_Null SheetName, row, "END_USER"
'					Exit For
'				End If
'			end if
		Next
		
		Set xlsSheet = Nothing
		On Error Goto 0
	End Sub	
		
	'-----------------------------------------------------------
	'�f�[�^�C���T�[�g
	'-----------------------------------------------------------
	Sub insertICT ( SheetName )
		On Error Resume Next
		
		Set xlsSheet = xlsBook.Worksheets( SheetName )
		
'		For row = 4 To 65536
		For row = 2 To 65536
			'1�s�ڂ���ǂ݁A[A��]:ITEM NO �ɓ��͂�����Ԃ͏����𑱍s
			If xlsSheet.Cells(row, 1).Value = "" Then 
				Exit For
			End If
			
			SQL3 = "INSERT INTO ITEM_CLEANSING_TMP ("
			SQL3 = SQL3 & "ITEM_NO,OPERATION_DATE,FILENAME"
			SQL3 = SQL3 & ")VALUES("
			SQL3 = SQL3 & xlsSheet.Cells(row,1).Value & ","                        'item_no
			SQL3 = SQL3 & "to_date('" & wkSysdate & "', 'YYYY/MM/DD HH24:MI:SS')," 'operation_date
			SQL3 = SQL3 & "'" & fileName & "'"            'filename
			SQL3 = SQL3 & ")"
 			
			insCnt = 0
			objDb.Execute SQL3, insCnt
			
			'�X�V�����ɂăG���[����
			If objDb.Errors.Count <> 0 Then
				ExecResult = False
				setErrMsg_Sql_Ins1 "Insert ITEM_ADD_SF", SheetName, row, "ITEM_CLEANSING_TMP"
				RollbackTrans   'Rollback����
				Exit For
			End If
			
			'�X�V�������O���̏ꍇ�A�G���[
			If insCnt = 0 Then
				ExecResult = False
				setErrMsg_Sql_Ins2 "Insert ITEM_CLEANSING_TMP", SheetName, row, insCnt
				RollbackTrans   'Rollback����
				Exit For
			End If
		Next
		
		Set xlsSheet = Nothing
		On Error Goto 0
	End Sub
	

	'-----------------------------------------------------------
	'DB�`�F�b�N���ʃZ�b�g
	'-----------------------------------------------------------
	Sub checkDB
		On Error Resume Next
		'DB CHECK
		SQLU1 = "UPDATE ITEM_CLEANSING_TMP ICT SET "
		SQLU1 = SQLU1 & "SALES_CNT = (SELECT COUNT(S1.ITEM_NO) FROM SALES S1 WHERE S1.ITEM_NO = ICT.ITEM_NO),"
		SQLU1 = SQLU1 & "LAST_SALES_DATE = (SELECT MAX(S2.DATA_DATE) FROM SALES S2 WHERE S2.ITEM_NO = ICT.ITEM_NO),"
		SQLU1 = SQLU1 & "PURCHASE_CNT = (SELECT COUNT(P1.ITEM_NO) FROM PURCHASE P1 WHERE P1.ITEM_NO = ICT.ITEM_NO),"
		SQLU1 = SQLU1 & "LAST_PURCHASE_DATE = (SELECT MAX(P2.DATA_DATE) FROM PURCHASE P2 WHERE P2.ITEM_NO = ICT.ITEM_NO),"
		SQLU1 = SQLU1 & "SO_CNT = (SELECT COUNT(SD1.ITEM_NO) FROM SO_DETAILS SD1 WHERE SD1.ITEM_NO = ICT.ITEM_NO AND SD1.BAL_QTY <> 0),"
		SQLU1 = SQLU1 & "LAST_SO_DATE = (SELECT MAX(SH.SO_DATE) FROM SO_HEADER SH,SO_DETAILS SD2 WHERE SD2.ITEM_NO = ICT.ITEM_NO AND SD2.BAL_QTY <> 0 AND SH.SO_NO = SD2.SO_NO),"
		SQLU1 = SQLU1 & "PO_CNT = (SELECT COUNT(PD1.ITEM_NO) FROM PO_DETAILS PD1 WHERE PD1.ITEM_NO = ICT.ITEM_NO AND PD1.BAL_QTY <> 0),"
		SQLU1 = SQLU1 & "LAST_PO_DATE = (SELECT MAX(PH.PO_DATE) FROM PO_HEADER PH,PO_DETAILS PD2 WHERE PD2.ITEM_NO = ICT.ITEM_NO AND PD2.BAL_QTY <> 0 AND PH.PO_NO = PD2.PO_NO),"
		SQLU1 = SQLU1 & "TRN_CNT = (SELECT COUNT(T1.ITEM_NO) FROM TRANSACTION T1 WHERE T1.ITEM_NO = ICT.ITEM_NO),"
		SQLU1 = SQLU1 & "LAST_SLIP_DATE = (SELECT MAX(T2.SLIP_DATE) FROM TRANSACTION T2 WHERE T2.ITEM_NO = ICT.ITEM_NO),"
		SQLU1 = SQLU1 & "INQ_CNT = (SELECT COUNT(ID1.ITEM_NO) FROM INQ_DETAILS ID1 WHERE ID1.ITEM_NO = ICT.ITEM_NO),"
		SQLU1 = SQLU1 & "LAST_INQ_DATE = (SELECT MAX(IH.INQ_DATE) FROM INQ_HEADER IH,INQ_DETAILS ID2 WHERE ID2.ITEM_NO = ICT.ITEM_NO AND IH.INQ_NO = ID2.INQ_NO),"
		SQLU1 = SQLU1 & "PRF_CNT = (SELECT COUNT(PD1.ITEM_NO) FROM PRF_DETAILS PD1 WHERE PD1.ITEM_NO = ICT.ITEM_NO AND PD1.REMAINDER_QTY <> 0),"
		SQLU1 = SQLU1 & "LAST_PRF_DATE = (SELECT MAX(PH.PRF_DATE) FROM PRF_HEADER PH,PRF_DETAILS PD2 WHERE PD2.ITEM_NO = ICT.ITEM_NO AND PD2.REMAINDER_QTY <> 0 AND PH.PRF_NO = PD2.PRF_NO),"
		SQLU1 = SQLU1 & "WHI_CNT = (SELECT COUNT(W.ITEM_NO) FROM WHINVENTORY W WHERE W.ITEM_NO = ICT.ITEM_NO AND W.THIS_INVENTORY <> 0)"
		insCnt = 0
		objDb.Execute SQLU1, insCnt
		
		'�X�V�����ɂăG���[����
		If objDb.Errors.Count <> 0 Then
			ExecResult = False
			setErrMsg_Sql "DB check"
			RollbackTrans   'Rollback����
		End If
		
		'�X�V�������O���̏ꍇ�A�G���[
		If insCnt = 0 Then
			ExecResult = False
			setErrMsg_Sql "DB check"
			RollbackTrans   'Rollback����
		End If
		
		'FLAG SET
		SQLU2 = "UPDATE ITEM_CLEANSING_TMP SET REMOVABLE_FLAG = "
		SQLU2 = SQLU2 & "CASE WHEN SALES_CNT = 0 AND PURCHASE_CNT = 0 AND SO_CNT = 0 AND PO_CNT = 0 AND TRN_CNT = 0 AND INQ_CNT = 0 AND PRF_CNT = 0 AND WHI_CNT = 0 "
		SQLU2 = SQLU2 & "THEN '0' ELSE '1' END"
		insCnt = 0
		objDb.Execute SQLU2, insCnt
		
		'�X�V�����ɂăG���[����
		If objDb.Errors.Count <> 0 Then
			ExecResult = False
			setErrMsg_Sql "FLAG SET"
			RollbackTrans   'Rollback����
		End If
		
		'�X�V�������O���̏ꍇ�A�G���[
		If insCnt = 0 Then
			ExecResult = False
			setErrMsg_Sql "FLAG SET"
			RollbackTrans   'Rollback����
		End If
		On Error Goto 0

	End Sub

	'-----------------------------------------------------------
	'�G���[���b�Z�[�W�ҏW�i��O�G���[�j
	'-----------------------------------------------------------
	Sub setErrMsg_Exception ( errPoint, description )
		msgErr = "<b><u>EXCEPTION ERROR!</u></b><br><br>" & _
				 "-- System Info. -------- <br>" & _
				 "ErrPoint = " & errPoint & "<br>" & _
				 "Err.Description = " & description
	End Sub
	
	'-----------------------------------------------------------
	'�G���[���b�Z�[�W�ҏW�i�t�@�C���A�b�v���[�h�G���[�j
	'-----------------------------------------------------------
	Sub setErrMsg_Upload ( errTitle, leng, fsoPath )
		msgErr = "<b><u>FILE UPLOAD ERROR! " & errTitle & "</u></b><br><br>" & _
				 "-- System Info. -------- <br>" & _
				 "leng = " & leng & "<br>" & _
				 "UploadPath = " & fsoPath
	End Sub
	
	'-----------------------------------------------------------
	'�G���[���b�Z�[�W�ҏW�i���l�`�F�b�N�G���[�j
	'-----------------------------------------------------------
	Sub setErrMsg_Valid ( sheetName, row, columnName )
		msgErr = "<b><u>VALIDATION ERROR! Value is not numeric.</u></b><br><br>" & _
				 " SheetName = " & sheetName & "<br>" & _
				 " RowNo = " & row & "<br>" & _
				 " ColumnName = " & columnName
	End Sub
	
	'-----------------------------------------------------------
	'�G���[���b�Z�[�W�ҏW�i���ݒ�`�F�b�N�G���[�j
	'-----------------------------------------------------------
	Sub setErrMsg_Null ( sheetName, row, columnName )
		msgErr = "<b><u>DATA SETTING ERROR! Value is Null.</u></b><br><br>" & _
				 " SheetName = " & sheetName & "<br>" & _
				 " RowNo = " & row & "<br>" & _
				 " ColumnName = " & columnName
	End Sub
	
	'-----------------------------------------------------------
	'�G���[���b�Z�[�W�ҏW�i�c�a�X�V�G���[�FUpdate�ADelete�j
	'-----------------------------------------------------------
	Sub setErrMsg_Sql ( errPoint )
		Dim objErrItem
		Set objErrItem = objDb.Errors.Item(0)
		msgErr = "<b><u>DB ERROR! </u></b><br><br>" & _
				 "-- System Info. -------- <br>" & _
				 "ErrPoint = " & errPoint & "<br>" & _
				 "Description = " & objErrItem.Description & "<br>" & _
				 msgRollback
    	Set objErrItem = Nothing
	End Sub
	
	'-----------------------------------------------------------
	'�G���[���b�Z�[�W�ҏW�i�c�a�X�V�G���[�FInsert�jOracle�G���[
	'-----------------------------------------------------------
	Sub setErrMsg_Sql_Ins1 ( errPoint, SheetName, row ,tablename)
		Dim objErrItem
		Set objErrItem = objDb.Errors.Item(0)
		
		errTitle  = ""
		uniqueKey = ""
		
		'��Ӑ���G���[�̏ꍇ�A�Œ胁�b�Z�[�W��t�^
		If InStr(objErrItem.Description, "ORA-00001") > 0 Then
			errTitle  = "Unique constraint violated."
			If tablename = "ITEM_ADD_SF" then
				uniqueKey = "UniqueKey = (SheetName), ITEM NO<br>"
			else
				uniqueKey = "UniqueKey = (SheetName), ITEM NO, SALES GROUP<br>"
			end if
		End If
		
		msgErr = "<b><u>DB ERROR! " & errTitle & "</u></b><br><br>" & _
				 "SheetName = " & SheetName & "<br>" & _
				 "ROW = " & row & "<br>" & _
				 uniqueKey & "<br>" & _
				 "-- System Info. -------- <br>" & _
				 "ErrPoint = " & errPoint & "<br>" & _
				 "Description = " & objErrItem.Description & "<br>" & _
				 msgRollback
	    Set objErrItem = Nothing
	End Sub
	
	'-----------------------------------------------------------
	'�G���[���b�Z�[�W�ҏW�i�c�a�X�V�G���[�FInsert�j�X�V����0��
	'-----------------------------------------------------------
	Sub setErrMsg_Sql_Ins2 ( errPoint, SheetName, row, insCnt )
		msgErr = "<b><u>DB ERROR! This data was not able to be registered.</u></b><br><br>" & _
				 "SheetName = " & SheetName & "<br>" & _
				 "RowNo = " & row & "<br><br>" & _
				 "-- System Info. -------- <br>" & _
				 "ErrPoint = " & errPoint & "<br>" & _
				 "InsertCount = " & insCnt & "<br>" & _
				 msgRollback
	End Sub
	
	
	'-----------------------------------------------------------
	'�c�a�g�����U�N�V�����J�n
	'-----------------------------------------------------------
	Sub BeginTrans
		objDb.BeginTrans
	End Sub
	
	'-----------------------------------------------------------
	'�c�a�g�����U�N�V�����I���yCommit�z
	'-----------------------------------------------------------
	Sub CommitTrans
		objDb.CommitTrans
	End Sub
	
	'-----------------------------------------------------------
	'�c�a�g�����U�N�V�����I���yRollback�z
	'-----------------------------------------------------------
	Sub RollbackTrans
		objDb.RollbackTrans
		msgRollback = "*** Rollback Complete. ***"
	End Sub

Function GET_SQL_VALUE(value1,value2)	
	IF value1 = "" OR ISNULL(value1) THEN
		GET_SQL_VALUE = "NULL"
	ELSE
		GET_SQL_VALUE = "'" & replace(value1,"'","''") & "'"
	End If
End Function



%>
