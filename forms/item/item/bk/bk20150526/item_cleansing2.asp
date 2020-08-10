<%@ LANGUAGE="VBScript" %>
<!--#include file = "../../common.inc"-->
<%
'   プログラム名 ITEM_CLEANSING2.asp
'   データをロードして売上、注残等をチェックします。
'   2013.02.XX M.KAMIYAMA
'   item_add_sf_ul.aspをコピー、メンテ
%>
<%
	Dim msg               'メッセージ（正常）
	Dim msgErr            'メッセージ（異常）
	Dim msgRollback       'メッセージ（Rollback）
	
	Dim wkSysdate         'システム日付
	
	Dim UploadFileName    'アップロード後のファイル名
	UploadFileName = "item_master_cleansing_form.xls" '（固定値）
	
	Dim	Sheet1Name        '対象シート名（１つ目）
	Sheet1Name = "REMOVE_LIST"   '（固定値）
	
	DIm Sheet1Exists      '対象シートの存在有無（１つ目）
	
	'Init()でセット
	Dim LINK_BACK
	Dim totalBytes
	Dim	requestBinary
	
	'Func2()でセット
	Dim ExecResult        'ＤＢ更新結果
	
	'終了処理が必要な変数
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
	'「0.初期処理」呼出
	'-------------------------------------
	Call Init()
	
	'-------------------------------------
	'「1.Excelファイルの読込」呼出
	'-------------------------------------
	If msgErr = "" Then
		Call Func1()
	End If
	
	'-------------------------------------
	'「2.データ処理」呼出
	'-------------------------------------
	If msgErr = "" Then
		Call Func2()
	End If
	
	'-------------------------------------
	'「9.終了処理」呼出
	'-------------------------------------
	Call Finally()
	
	
	
	'***********************************************************
	' 0.初期処理
	'***********************************************************
	Sub Init()
		On Error Resume Next
		'初期値設定
		msg          = "ITEM MASTER CLEANSING LIST UPLOAD IS SUCCESSFUL."
		msgErr       = ""
		msgRollback  = ""
		wkSysdate    = Now
		Sheet1Exists = False
'		Sheet2Exists = False
		
		'BASPオブジェクト生成
		set objBasp = Server.CreateObject(basp_str)
		
		If Err.Number <> 0 Then
			setErrMsg_Exception "CreateObject BASP21. (0)", Err.Description
			
		Else
			'Form値の取得
			totalBytes     = Request.TotalBytes
			requestBinary  = Request.BinaryRead(totalBytes)
			If Err.Number <> 0 Then
				setErrMsg_Exception "Request.BinaryRead(totalBytes). (0)", Err.Description
			End If
			
			KEYWORD        = objBasp.Form(requestBinary, "KEYWORD")
			
			'[BACK]リンク
			LINK_BACK = "item_cleansing1.asp?KEYWORD=" & KEYWORD
		End If
		
		On Error Goto 0
	End Sub
	
	
	'***********************************************************
	' 1.Excelファイルの読込
	'***********************************************************
	Sub Func1()
		On Error Resume Next
		
		'-------------------------------------------------
		'（1-1）Excelファイルアップロード
		'-------------------------------------------------
		'ファイルシステムオブジェクトの生成
		Set objFso = Server.CreateObject("Scripting.FileSystemObject")
		
		'前画面で入力されたファイルパス（絶対パス）
		filePath = objBasp.FormFileName(requestBinary, "upfile")
		'ファイルサイズ
		fileSize = objBasp.FormFileSize(requestBinary, "upfile")
		'前画面で入力されたファイル名のみ
		fileName = Mid(filePath, InstrRev(filePath, "\") + 1)
		'objFsoにパスを設定
		fsoPath = objFso.BuildPath(Server.MapPath("./temp/" ), UploadFileName)
		
		'ファイルアップロード
		leng = objBasp.FormSaveAs(requestBinary, "upfile", fsoPath)
		
		'アップロードエラーチェック
		If leng <= 0 Then
			setErrMsg_Upload "", leng, fsoPath
		'アップロードしたファイルの存在チェック
		ElseIf objFso.FileExists(fsoPath) = False Then
			setErrMsg_Upload "File was not uploaded.", leng, fsoPath
		End If
		
		
		'-------------------------------------------------
		'（1-2）シート存在チェック
		'-------------------------------------------------
		'Excelアプリケーションオブジェクトの生成
		If msgErr = "" Then
			Set objXls  = Server.CreateObject("Excel.Application")
			If Err.Number <> 0 Then
				setErrMsg_Exception "CreateObject ExcelApplication. (1-2)", Err.Description
			End If
			
			'すべてのシートのシート名をチェック
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
			
			'「MST」シート存在しない場合、エラー
'			If Sheet1Exists = False And Sheet2Exists = False Then
			If Sheet1Exists = False Then
			   	msgErr = "LIST DATA SHEET NOT EXIST IN THIS FILE."
			End If
		End If
		
		On Error Goto 0
	End Sub
	
	
	'***********************************************************
	' 2.データ処理
	'***********************************************************
	Sub Func2()
		On Error Resume Next
		'-------------------------------------------------
		'（2-1）データチェック
		'-------------------------------------------------
		If Sheet1Exists = True Then
			checkNumeric Sheet1Name
		End If
		
'		If Sheet2Exists = True and msgErr = "" Then
'			checkNumeric Sheet2Name
'		End If
		
		
		'-------------------------------------------------
		'（2-2）ＤＢ更新処理
		'-------------------------------------------------
		If msgErr = "" Then
			ExecResult = True  '（初期値）
			
			'ＤＢ接続
			set objDb = Server.CreateObject("ADODB.Connection")
			objDb.Open server_name, server_user, server_psword
			If Err.Number <> 0 Then
				ExecResult = False
				setErrMsg_Exception "DB Connect. (2-2)", Err.Description
			End If
			
			
			BeginTrans '※※※ トランザクション開始 ※※※
			
			'---------------------------------------------
			' (2-2-1) データ削除
			'[ITEM_CLEANSING_TMP] Delete
			SQL2 = " delete from ITEM_CLEANSING_TMP "
			
			If ExecResult = True Then
				objDb.Execute(SQL2)
				If objDb.Errors.Count <> 0 Then
					ExecResult = False
					RollbackTrans   'Rollback処理
					setErrMsg_Sql "Delete ITEM_CLEANSING_TMP"
				End If
			End If
			

			'---------------------------------------------
			' (2-2-2) データインサート
			'---------------------------------------------
			
			'[ITEM_CLEANSING_TMP] Insert
			If ExecResult = True And Sheet1Exists = True Then
				insertICT Sheet1Name
			End If
			
			'---------------------------------------------
			' (2-2-3) DBチェック
			'---------------------------------------------
			
			'[ITEM_CLEANSING_TMP] check db
			If ExecResult = True And Sheet1Exists = True Then
				checkDB
			End If
			
			
			'---------------------------------------------
			' (2-2-3) コミット
			'---------------------------------------------
			'すべての処理が正常に終了
			If ExecResult = True Then
				CommitTrans    'Commit処理
			End If
			
			
			'ＤＢ接続解除
			objDb.RollbackTrans  'Rollback処理（トランザクション終了後であれば空振り※）
			objDb.Close
			Set objDb = Nothing
			
			'ExcelApplication解放
			xlsBook.Saved = True '保存済みということにする（プロセス残留防止対策）
			xlsBook.Close
			Set xlsBook = Nothing
			objXls.Visible = True '一旦表示させる（プロセス残留防止対策）
			objXls.Quit
			Set objXls = Nothing
		End If
		
		On Error Goto 0
	End Sub
	
	
	'***********************************************************
	' 9.終了処理（オブジェクトの破棄、ＤＢの接続解除など）
	'***********************************************************
	Sub Finally()
		On Error Resume Next
			objDb.RollbackTrans  'Rollback処理（※）
			objDb.Close
			Set objDb = Nothing
			
			xlsBook.Saved = True '保存済みということにする（プロセス残留防止対策）
			xlsBook.Close
			Set xlsBook = Nothing
			objXls.Visible = True '一旦表示させる（プロセス残留防止対策）
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
	' HTML生成
	'***********************************************************

	'冒頭の<html>～<body>タグは、html_headerが生成する（common.inc参照）
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
	' ＜共通処理＞
	'***********************************************************
	'-----------------------------------------------------------
	'データチェック
	'-----------------------------------------------------------
	Sub checkNumeric( SheetName )
		On Error Resume Next
		
		Set xlsSheet = xlsBook.Worksheets( SheetName )
		
'		For row = 4 To 65536
		For row = 2 To 65536
			'1行目から読み、[A列]:ITEM NO に入力がある間は処理を続行
			If xlsSheet.Cells(row, 1).Value = "" Then 
				Exit For
			End If
				
			'バリデーションチェック（数値）
			' --- Excel[A列]:ITEM NO
			If IsNumeric(xlsSheet.Cells(row, 1).Value) = False Then
				setErrMsg_Valid SheetName, row, "ITEM_NO"
				Exit For
			End If
'			'必要項目のどれかが設定されていたらチェック
'			if xlsSheet.Cells(row, 4).Value <> "" or xlsSheet.Cells(row, 5).Value <> "" or xlsSheet.Cells(row, 6).Value <> "" or _
'			   xlsSheet.Cells(row, 7).Value <> "" or xlsSheet.Cells(row, 8).Value <> "" then
'				'未設定チェック
'				' --- Excel[D列]:SALES GROUP
'				If xlsSheet.Cells(row, 4).Value = "" Then
'					setErrMsg_Null SheetName, row, "SALES_GROUP"
'					Exit For
'				End If
'				' --- Excel[E列]:CELL_TYPE
'				If xlsSheet.Cells(row, 5).Value = "" Then
'					setErrMsg_Null SheetName, row, "CELL_TYPE"
'					Exit For
'				End If
'				' --- Excel[F列]:PACKING_TYPE
'				If xlsSheet.Cells(row, 6).Value = "" Then
'					setErrMsg_Null SheetName, row, "PACKING_TYPE"
'					Exit For
'				End If
'				' --- Excel[G列]:CELL_GRADE
'				If xlsSheet.Cells(row, 7).Value = "" Then
'					setErrMsg_Null SheetName, row, "CELL_GRADE"
'					Exit For
'				End If
'				' --- Excel[H列]:END_USER
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
	'データインサート
	'-----------------------------------------------------------
	Sub insertICT ( SheetName )
		On Error Resume Next
		
		Set xlsSheet = xlsBook.Worksheets( SheetName )
		
'		For row = 4 To 65536
		For row = 2 To 65536
			'1行目から読み、[A列]:ITEM NO に入力がある間は処理を続行
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
			
			'更新処理にてエラー発生
			If objDb.Errors.Count <> 0 Then
				ExecResult = False
				setErrMsg_Sql_Ins1 "Insert ITEM_ADD_SF", SheetName, row, "ITEM_CLEANSING_TMP"
				RollbackTrans   'Rollback処理
				Exit For
			End If
			
			'更新件数が０件の場合、エラー
			If insCnt = 0 Then
				ExecResult = False
				setErrMsg_Sql_Ins2 "Insert ITEM_CLEANSING_TMP", SheetName, row, insCnt
				RollbackTrans   'Rollback処理
				Exit For
			End If
		Next
		
		Set xlsSheet = Nothing
		On Error Goto 0
	End Sub
	

	'-----------------------------------------------------------
	'DBチェック結果セット
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
		
		'更新処理にてエラー発生
		If objDb.Errors.Count <> 0 Then
			ExecResult = False
			setErrMsg_Sql "DB check"
			RollbackTrans   'Rollback処理
		End If
		
		'更新件数が０件の場合、エラー
		If insCnt = 0 Then
			ExecResult = False
			setErrMsg_Sql "DB check"
			RollbackTrans   'Rollback処理
		End If
		
		'FLAG SET
		SQLU2 = "UPDATE ITEM_CLEANSING_TMP SET REMOVABLE_FLAG = "
		SQLU2 = SQLU2 & "CASE WHEN SALES_CNT = 0 AND PURCHASE_CNT = 0 AND SO_CNT = 0 AND PO_CNT = 0 AND TRN_CNT = 0 AND INQ_CNT = 0 AND PRF_CNT = 0 AND WHI_CNT = 0 "
		SQLU2 = SQLU2 & "THEN '0' ELSE '1' END"
		insCnt = 0
		objDb.Execute SQLU2, insCnt
		
		'更新処理にてエラー発生
		If objDb.Errors.Count <> 0 Then
			ExecResult = False
			setErrMsg_Sql "FLAG SET"
			RollbackTrans   'Rollback処理
		End If
		
		'更新件数が０件の場合、エラー
		If insCnt = 0 Then
			ExecResult = False
			setErrMsg_Sql "FLAG SET"
			RollbackTrans   'Rollback処理
		End If
		On Error Goto 0

	End Sub

	'-----------------------------------------------------------
	'エラーメッセージ編集（例外エラー）
	'-----------------------------------------------------------
	Sub setErrMsg_Exception ( errPoint, description )
		msgErr = "<b><u>EXCEPTION ERROR!</u></b><br><br>" & _
				 "-- System Info. -------- <br>" & _
				 "ErrPoint = " & errPoint & "<br>" & _
				 "Err.Description = " & description
	End Sub
	
	'-----------------------------------------------------------
	'エラーメッセージ編集（ファイルアップロードエラー）
	'-----------------------------------------------------------
	Sub setErrMsg_Upload ( errTitle, leng, fsoPath )
		msgErr = "<b><u>FILE UPLOAD ERROR! " & errTitle & "</u></b><br><br>" & _
				 "-- System Info. -------- <br>" & _
				 "leng = " & leng & "<br>" & _
				 "UploadPath = " & fsoPath
	End Sub
	
	'-----------------------------------------------------------
	'エラーメッセージ編集（数値チェックエラー）
	'-----------------------------------------------------------
	Sub setErrMsg_Valid ( sheetName, row, columnName )
		msgErr = "<b><u>VALIDATION ERROR! Value is not numeric.</u></b><br><br>" & _
				 " SheetName = " & sheetName & "<br>" & _
				 " RowNo = " & row & "<br>" & _
				 " ColumnName = " & columnName
	End Sub
	
	'-----------------------------------------------------------
	'エラーメッセージ編集（未設定チェックエラー）
	'-----------------------------------------------------------
	Sub setErrMsg_Null ( sheetName, row, columnName )
		msgErr = "<b><u>DATA SETTING ERROR! Value is Null.</u></b><br><br>" & _
				 " SheetName = " & sheetName & "<br>" & _
				 " RowNo = " & row & "<br>" & _
				 " ColumnName = " & columnName
	End Sub
	
	'-----------------------------------------------------------
	'エラーメッセージ編集（ＤＢ更新エラー：Update、Delete）
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
	'エラーメッセージ編集（ＤＢ更新エラー：Insert）Oracleエラー
	'-----------------------------------------------------------
	Sub setErrMsg_Sql_Ins1 ( errPoint, SheetName, row ,tablename)
		Dim objErrItem
		Set objErrItem = objDb.Errors.Item(0)
		
		errTitle  = ""
		uniqueKey = ""
		
		'一意制約エラーの場合、固定メッセージを付与
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
	'エラーメッセージ編集（ＤＢ更新エラー：Insert）更新件数0件
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
	'ＤＢトランザクション開始
	'-----------------------------------------------------------
	Sub BeginTrans
		objDb.BeginTrans
	End Sub
	
	'-----------------------------------------------------------
	'ＤＢトランザクション終了【Commit】
	'-----------------------------------------------------------
	Sub CommitTrans
		objDb.CommitTrans
	End Sub
	
	'-----------------------------------------------------------
	'ＤＢトランザクション終了【Rollback】
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
