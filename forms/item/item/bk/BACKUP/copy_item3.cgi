# ＳＩＭＳ【マスタ】
# ［IMEJ0004.cgi］品目登録（国内）
# ［作成日］2002/11/04
# ［作成者］松本一義
#
# ［変更履歴］9999/99/99 ＮＮＮＮ ＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮ
#======================================================================================

#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# POST
#-------------------------
foreach(keys (%in)){
  $$_ = $in{$_} ;
  $$_ =~ s/\r//g ;
}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

if($TYPES eq "新規登録"){
	# 重複確認
	$sql = "SELECT count(*) FROM ITEM where ITEM_NO ='$item_no' ";
	$cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	($COUNT) = $cur->fetchrow() ;
	$cur->finish;
	if($COUNT >0){ &err("このコードは既に存在します。") ;}
}

if($TYPES eq "削除" or $TYPES eq "復元"){
	#ITEM
	$sql  = "select i.*,s.stock_subject from item i,stock_subject s " ;
	$sql .= "where  i.item_no            = '$item_no'";
	$sql .= "  and  i.stock_subject_code = s.stock_subject_code(+) ";
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_MST err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_MST err $DBI::err .... $DBI::errstr") ;
	@datas = $cur->fetchrow() ;
	for($j=0;$j<@datas;$j++){
		$cur->{NAME}->[$j] =~ tr/[A-Z]/[a-z]/ ;
		${$cur->{NAME}->[$j]} = $datas[$j] ;
	}
}

	#ITEMFLAG
	$sql = "select item_flag,flag_name from itemflag order by flag_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$IMF_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$IMF[$datas[0]] = $datas[1] ;
		$IMF_NUM ++ ;
	}
	$cur->finish;

	#COUNTRY
	$sql = "select country_code,country from country order by area_code,country";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$COU_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$COU[$datas[0]] = $datas[1] ;
		$COU_NUM ++ ;
	}
	$cur->finish;

	#UNIT
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'QUANTITY' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNQ_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNQ[$datas[0]] = $datas[1] ;
		$UNQ_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'WEIGHT' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNW_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNW[$datas[0]] = $datas[1] ;
		$UNW_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'LENGTH' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNL_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNL[$datas[0]] = $datas[1] ;
		$UNL_NUM ++ ;
	}
	$cur->finish;

	#ISSUEPOLICY
	$sql = "select issue_policy,policy_name from issuepolicy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$ISS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ISS[$datas[0]] = $datas[1] ;
		$ISS_NUM ++ ;
	}
	$cur->finish;

	#SECTION
	$sql = "select section_code,section from section order by short_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_SECTION err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_SECTION err $DBI::err .... $DBI::errstr") ;
	$SEC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SEC[$datas[0]] = $datas[1] ;
		$SEC_NUM ++ ;
	}
	$cur->finish;

	#STOCK_SUBJECT
	$sql = "select stock_subject_code,stock_subject from stock_subject order by stock_subject_code";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$SSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SSC[$datas[0]] = $datas[1] ;
		$SSC_NUM ++ ;
	}
	$cur->finish;

	#COSTPROCESS
	$sql = "select cost_process_code,cost_process_name from costprocess order by cost_process_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$CPC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CPC[$datas[0]] = $datas[1] ;
		$CPC_NUM ++ ;
	}
	$cur->finish;

	#COSTSUBJECT
	$sql = "select cost_subject_code,cost_subject_name from costsubject order by cost_subject_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$CSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CSC[$datas[0]] = $datas[1] ;
		$CSC_NUM ++ ;
	}
	$cur->finish;

	#ORDER_POLICY
	$sql = "select order_policy,policy_name from order_policy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$ORP_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ORP[$datas[0]] = $datas[1] ;
		$ORP_NUM ++ ;
	}
	$cur->finish;

	#MAKERFLAG
	$sql = "select maker_flag_code,maker_flag from makerflag order by maker_flag";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$MAK_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$MAK[$datas[0]] = $datas[1] ;
		$MAK_NUM ++ ;
	}
	$cur->finish;

	#CURRENCY
	$sql = "select curr_code,curr_mark from currency order by curr_code";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$CURR_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CURR[$datas[0]] = $datas[1] ;
		$CURR_NUM ++ ;
	}
	$cur->finish;

	#INSPECTIONTYPE
	$sql = "select inspection_type,inspection_name from inspectiontype order by inspection_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0004_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0004_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$INS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$INS[$datas[0]] = $datas[1] ;
		$INS_NUM ++ ;
	}
	$cur->finish;

	# ERROR CHECK
	$ERR_MSG          = "" ;
	$BG_ITEM_NO       = "WHITE";
	$BG_STOCK_SUBJECT = "WHITE";
	$BG_ITEM          = "WHITE";
	$BG_ORIGIN_CODE   = "WHITE";
	$BG_CURR_CODE     = "WHITE";
	$BG_UOM_W         = "WHITE";
	$BG_UOM_L         = "WHITE";
	$BG_UNIT          = "WHITE";
	$BG_S_PRICE       = "WHITE";
	$BG_N_PRICE       = "WHITE";
	$BG_SECTION       = "WHITE";
	$BG_PROCESS       = "WHITE";
	$BG_SUBJECT = "WHITE";

if($TYPES ne "削除"){
	# 【品目コード】
		&err("品目コードが見つかりません。") if($item_no eq "");
		if($item_no =~ /[^0-9 .]/){ $ERROR .="　品目コードが未入力です。<BR>" ; $BG_ITEM_NO ="lightpink" ; $ERR_MSG = "ERR"; }
	# 【資産区分】
		if($stock_subject_code eq ""){ $ERROR .="　資産区分が未入力です。<BR>";   $BG_STOCK_SUBJECT    = "lightpink"; $ERR_MSG = "ERR"; }
	# 【品目名】
		if($item               eq ""){ $ERROR .="　品目名が未入力です。<BR>" ;    $BG_ITEM             = "lightpink"; $ERR_MSG = "ERR"; }
	# 【原産国】
		if($origin_code        eq ""){ $ERROR .="　原産国が未入力です。<BR>";     $BG_ORIGIN_CODE      = "lightpink"; $ERR_MSG = "ERR"; }
	# 【通貨】
		if($curr_code          eq ""){ $ERROR .="　通貨が未入力です。<BR>";       $BG_CURR_CODE        = "lightpink"; $ERR_MSG = "ERR"; }
	# 【重量単位】
		if($uom_w              eq ""){ $ERROR .="　重量単位が未入力です。<BR>";   $BG_UOM_W            = "lightpink"; $ERR_MSG = "ERR"; }
	# 【容積単位】
		if($uom_l              eq ""){ $ERROR .="　容積単位が未入力です。<BR>";   $BG_UOM_L            = "lightpink"; $ERR_MSG = "ERR"; }
	# 【単位変換率】
		if($unit_stock         eq ""){ $ERROR .="　変換単位が未入力です。<BR>"; $BG_UNIT = "lightpink"; $ERR_MSG = "ERR"; }
		if($unit_engineering   eq ""){ $ERROR .="　変換単位が未入力です。<BR>"; $BG_UNIT = "lightpink"; $ERR_MSG = "ERR"; }
		if($unit_stock_rate    =~ /[^0-9 .]/){ $ERROR .="　単位変換率が未入力です。<BR>" ; $BG_UNIT ="lightpink" ; $ERR_MSG = "ERR"; }
		if($unit_engineer_rate =~ /[^0-9 .]/){ $ERROR .="　単位変換率が未入力です。<BR>" ; $BG_UNIT ="lightpink" ; $ERR_MSG = "ERR"; }
	# 【当期基準単価】
		if($standard_price  =~ /[^0-9 .]/){ $ERROR .="　当期基準単価が未入力です。<BR>" ; $BG_S_PRICE ="lightpink" ; $ERR_MSG = "ERR"; }
	# 【翌期基準単価】
		if($next_term_price =~ /[^0-9 .]/){ $ERROR .="　翌期基準単価が未入力です。<BR>" ; $BG_N_PRICE ="lightpink" ; $ERR_MSG = "ERR"; }
	# 【部門コード】
		if($section_code       eq ""){ $ERROR .="　部門コードが未入力です。<BR>"; $BG_SECTION = "lightpink"; $ERR_MSG = "ERR"; }
	# 【原価コード】
		if($cost_process_code  eq ""){ $ERROR .="　原価コードが未入力です。<BR>"; $BG_PROCESS = "lightpink"; $ERR_MSG = "ERR"; }
	# 【科目コード】
		if($cost_subject_code  eq ""){ $ERROR .="　科目コードが未入力です。<BR>"; $BG_SUBJECT = "lightpink"; $ERR_MSG = "ERR"; }
}
#-------------------------
# HTML
#-------------------------
&html_header("品目マスタメンテナンス");
&title('品目マスタメンテナンス','IMEJ0004');

if($TYPES eq "新規登録"){
	print "　【新規登録】<BR><BR>" ;
	print "　---下記内容を確認後、登録ボタンを押してください---" ;
}else{
 	print "【$TYPES】<BR>" ;
}

print "<BR>" ;
print "<FORM METHOD=Post ACTION=IMEJ0005.cgi?$KEYWORD>" ;
foreach(keys (%in)){
	print "<INPUT TYPE=Hidden NAME=$_ VALUE=\"$$_\">\n" ;
}
print "<TABLE BORDER=0><TBODY><TR><TD>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"><TBODY>" ;
print " <TR><TD BGCOLOR=LightGreen><FONT COLOR=RED>品目コード</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ITEM_NO>$item_no</TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>資産区分</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_STOCK_SUBJECT>$SSC[$stock_subject_code]</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>品目名</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ITEM>$item<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>分類区分</TD>\n" ;
print "   <TD>$IMF[$item_flag]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>規格</TD>\n" ;
print "   <TD>$description<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>原産国</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ORIGIN_CODE>$COU[$origin_code]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>メーカー</TD>\n" ;
print "   <TD>$mak<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>通貨</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_CURR_CODE>$CURR[$curr_code]<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>販売単位数量</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$selling_unit_qty") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>外装箱梱包数</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$external_unit_number") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>安全在庫数</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$safety_stock") . "<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>重量</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$weight") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>重量単位</FONT></TD>\n" ;
print "   <TD ALIGN=Center BGCOLOR=$BG_UOM_W>$UNW[$uom_w]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>容積単位</FONT></TD>\n" ;
print "   <TD ALIGN=Center BGCOLOR=$BG_UOM_L>$UNL[$uom_l]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen COLSPAN=3><FONT COLOR=RED>単位変換率（対在庫単位）:単位変換率（対技術単位）</FONT></TD>\n" ;
print "   <TD COLSPAN=3 ALIGN=Center BGCOLOR=$BG_UNIT>$unit_stock_rate $UNQ[$unit_stock] ： $unit_engineer_rate $UNQ[$unit_engineering]</TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>カタログ番号</TD>\n" ;
print "   <TD>$catalog_no<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>ＪＡＮコード（物品番号）</TD>\n" ;
print "   <TD>$item_code<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>当期基準単価</FONT></TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_S_PRICE>" . &commas("$standard_price") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>翌期基準単価</FONT></TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_N_PRICE>" . &commas("$next_term_price") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>支給単価</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$suppliers_price") . "<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>製造手番</TD>\n" ;
print "   <TD ALIGN=Right>$manufact_leadtime 日</TD>\n" ;
print "   <TD BGCOLOR=LightGreen>調達手番</TD>\n" ;
print "   <TD ALIGN=Right>$purchase_leadtime 日</TD>\n" ;
print "   <TD BGCOLOR=LightGreen>調整手番</TD>\n" ;
print "   <TD ALIGN=Right>$adjustment_leadtime 日</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>払出区分</TD>\n" ;
print "   <TD>$ISS[$issue_policy]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>払出ロット</TD>\n" ;
print "   <TD>$issue_lot<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>加工仕損率</TD>\n" ;
print "   <TD ALIGN=Right>$manufact_fail_rate ％</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>部門コード</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_SECTION>$SEC[$section_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>原価コード</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_PROCESS>$CPC[$cost_process_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>科目コード</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_SUBJECT>$CSC[$cost_subject_code]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>発注方式</TD>\n" ;
print "   <TD>$ORP[$order_policy]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>手配先区分</TD>\n" ;
print "   <TD>$MAK[$maker_flag]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>分類</TD>\n" ;
print "   <TD>$item_type1<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>検査区分</TD>\n" ;
print "   <TD COLSPAN=5>$INS[$inspection_type]<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR></TBODY></TABLE>" ;

print "<P>\n";
if($ERR_MSG eq ""){print "<input type=submit name=submit value= $TYPES >\n"; }
print<<ENDOFTEXT;
<input type=button value='戻る' onClick='javascript:history.back()'>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>メインメニューに戻る</A>]
</BODY>
</HTML>
ENDOFTEXT

