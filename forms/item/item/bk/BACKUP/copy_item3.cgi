# �r�h�l�r�y�}�X�^�z
# �mIMEJ0004.cgi�n�i�ړo�^�i�����j
# �m�쐬���n2002/11/04
# �m�쐬�ҁn���{��`
#
# �m�ύX�����n9999/99/99 �m�m�m�m �m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m�m
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

if($TYPES eq "�V�K�o�^"){
	# �d���m�F
	$sql = "SELECT count(*) FROM ITEM where ITEM_NO ='$item_no' ";
	$cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
	($COUNT) = $cur->fetchrow() ;
	$cur->finish;
	if($COUNT >0){ &err("���̃R�[�h�͊��ɑ��݂��܂��B") ;}
}

if($TYPES eq "�폜" or $TYPES eq "����"){
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

if($TYPES ne "�폜"){
	# �y�i�ڃR�[�h�z
		&err("�i�ڃR�[�h��������܂���B") if($item_no eq "");
		if($item_no =~ /[^0-9 .]/){ $ERROR .="�@�i�ڃR�[�h�������͂ł��B<BR>" ; $BG_ITEM_NO ="lightpink" ; $ERR_MSG = "ERR"; }
	# �y���Y�敪�z
		if($stock_subject_code eq ""){ $ERROR .="�@���Y�敪�������͂ł��B<BR>";   $BG_STOCK_SUBJECT    = "lightpink"; $ERR_MSG = "ERR"; }
	# �y�i�ږ��z
		if($item               eq ""){ $ERROR .="�@�i�ږ��������͂ł��B<BR>" ;    $BG_ITEM             = "lightpink"; $ERR_MSG = "ERR"; }
	# �y���Y���z
		if($origin_code        eq ""){ $ERROR .="�@���Y���������͂ł��B<BR>";     $BG_ORIGIN_CODE      = "lightpink"; $ERR_MSG = "ERR"; }
	# �y�ʉ݁z
		if($curr_code          eq ""){ $ERROR .="�@�ʉ݂������͂ł��B<BR>";       $BG_CURR_CODE        = "lightpink"; $ERR_MSG = "ERR"; }
	# �y�d�ʒP�ʁz
		if($uom_w              eq ""){ $ERROR .="�@�d�ʒP�ʂ������͂ł��B<BR>";   $BG_UOM_W            = "lightpink"; $ERR_MSG = "ERR"; }
	# �y�e�ϒP�ʁz
		if($uom_l              eq ""){ $ERROR .="�@�e�ϒP�ʂ������͂ł��B<BR>";   $BG_UOM_L            = "lightpink"; $ERR_MSG = "ERR"; }
	# �y�P�ʕϊ����z
		if($unit_stock         eq ""){ $ERROR .="�@�ϊ��P�ʂ������͂ł��B<BR>"; $BG_UNIT = "lightpink"; $ERR_MSG = "ERR"; }
		if($unit_engineering   eq ""){ $ERROR .="�@�ϊ��P�ʂ������͂ł��B<BR>"; $BG_UNIT = "lightpink"; $ERR_MSG = "ERR"; }
		if($unit_stock_rate    =~ /[^0-9 .]/){ $ERROR .="�@�P�ʕϊ����������͂ł��B<BR>" ; $BG_UNIT ="lightpink" ; $ERR_MSG = "ERR"; }
		if($unit_engineer_rate =~ /[^0-9 .]/){ $ERROR .="�@�P�ʕϊ����������͂ł��B<BR>" ; $BG_UNIT ="lightpink" ; $ERR_MSG = "ERR"; }
	# �y������P���z
		if($standard_price  =~ /[^0-9 .]/){ $ERROR .="�@������P���������͂ł��B<BR>" ; $BG_S_PRICE ="lightpink" ; $ERR_MSG = "ERR"; }
	# �y������P���z
		if($next_term_price =~ /[^0-9 .]/){ $ERROR .="�@������P���������͂ł��B<BR>" ; $BG_N_PRICE ="lightpink" ; $ERR_MSG = "ERR"; }
	# �y����R�[�h�z
		if($section_code       eq ""){ $ERROR .="�@����R�[�h�������͂ł��B<BR>"; $BG_SECTION = "lightpink"; $ERR_MSG = "ERR"; }
	# �y�����R�[�h�z
		if($cost_process_code  eq ""){ $ERROR .="�@�����R�[�h�������͂ł��B<BR>"; $BG_PROCESS = "lightpink"; $ERR_MSG = "ERR"; }
	# �y�ȖڃR�[�h�z
		if($cost_subject_code  eq ""){ $ERROR .="�@�ȖڃR�[�h�������͂ł��B<BR>"; $BG_SUBJECT = "lightpink"; $ERR_MSG = "ERR"; }
}
#-------------------------
# HTML
#-------------------------
&html_header("�i�ڃ}�X�^�����e�i���X");
&title('�i�ڃ}�X�^�����e�i���X','IMEJ0004');

if($TYPES eq "�V�K�o�^"){
	print "�@�y�V�K�o�^�z<BR><BR>" ;
	print "�@---���L���e���m�F��A�o�^�{�^���������Ă�������---" ;
}else{
 	print "�y$TYPES�z<BR>" ;
}

print "<BR>" ;
print "<FORM METHOD=Post ACTION=IMEJ0005.cgi?$KEYWORD>" ;
foreach(keys (%in)){
	print "<INPUT TYPE=Hidden NAME=$_ VALUE=\"$$_\">\n" ;
}
print "<TABLE BORDER=0><TBODY><TR><TD>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"><TBODY>" ;
print " <TR><TD BGCOLOR=LightGreen><FONT COLOR=RED>�i�ڃR�[�h</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ITEM_NO>$item_no</TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>���Y�敪</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_STOCK_SUBJECT>$SSC[$stock_subject_code]</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>�i�ږ�</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ITEM>$item<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���ދ敪</TD>\n" ;
print "   <TD>$IMF[$item_flag]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>�K�i</TD>\n" ;
print "   <TD>$description<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>���Y��</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_ORIGIN_CODE>$COU[$origin_code]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>���[�J�[</TD>\n" ;
print "   <TD>$mak<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>�ʉ�</FONT></TD>\n" ;
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
print "   <TD BGCOLOR=LightGreen>�̔��P�ʐ���</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$selling_unit_qty") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>�O�������</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$external_unit_number") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���S�݌ɐ�</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$safety_stock") . "<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>�d��</TD>\n" ;
print "   <TD ALIGN=Right>" . &commas("$weight") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>�d�ʒP��</FONT></TD>\n" ;
print "   <TD ALIGN=Center BGCOLOR=$BG_UOM_W>$UNW[$uom_w]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>�e�ϒP��</FONT></TD>\n" ;
print "   <TD ALIGN=Center BGCOLOR=$BG_UOM_L>$UNL[$uom_l]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen COLSPAN=3><FONT COLOR=RED>�P�ʕϊ����i�΍݌ɒP�ʁj:�P�ʕϊ����i�΋Z�p�P�ʁj</FONT></TD>\n" ;
print "   <TD COLSPAN=3 ALIGN=Center BGCOLOR=$BG_UNIT>$unit_stock_rate $UNQ[$unit_stock] �F $unit_engineer_rate $UNQ[$unit_engineering]</TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>�J�^���O�ԍ�</TD>\n" ;
print "   <TD>$catalog_no<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>�i�`�m�R�[�h�i���i�ԍ��j</TD>\n" ;
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
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>������P��</FONT></TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_S_PRICE>" . &commas("$standard_price") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>������P��</FONT></TD>\n" ;
print "   <TD ALIGN=Right BGCOLOR=$BG_N_PRICE>" . &commas("$next_term_price") . "<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>�x���P��</TD>\n" ;
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
print "   <TD BGCOLOR=LightGreen>�������</TD>\n" ;
print "   <TD ALIGN=Right>$manufact_leadtime ��</TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���B���</TD>\n" ;
print "   <TD ALIGN=Right>$purchase_leadtime ��</TD>\n" ;
print "   <TD BGCOLOR=LightGreen>�������</TD>\n" ;
print "   <TD ALIGN=Right>$adjustment_leadtime ��</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>���o�敪</TD>\n" ;
print "   <TD>$ISS[$issue_policy]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���o���b�g</TD>\n" ;
print "   <TD>$issue_lot<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���H�d����</TD>\n" ;
print "   <TD ALIGN=Right>$manufact_fail_rate ��</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>����R�[�h</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_SECTION>$SEC[$section_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>�����R�[�h</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_PROCESS>$CPC[$cost_process_code]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED>�ȖڃR�[�h</FONT></TD>\n" ;
print "   <TD BGCOLOR=$BG_SUBJECT>$CSC[$cost_subject_code]<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>��������</TD>\n" ;
print "   <TD>$ORP[$order_policy]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>��z��敪</TD>\n" ;
print "   <TD>$MAK[$maker_flag]<BR></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>����</TD>\n" ;
print "   <TD>$item_type1<BR></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>�����敪</TD>\n" ;
print "   <TD COLSPAN=5>$INS[$inspection_type]<BR></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR></TBODY></TABLE>" ;

print "<P>\n";
if($ERR_MSG eq ""){print "<input type=submit name=submit value= $TYPES >\n"; }
print<<ENDOFTEXT;
<input type=button value='�߂�' onClick='javascript:history.back()'>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>���C�����j���[�ɖ߂�</A>]
</BODY>
</HTML>
ENDOFTEXT

