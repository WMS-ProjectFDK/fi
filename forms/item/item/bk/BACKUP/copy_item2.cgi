# �r�h�l�r�y�}�X�^�z
# �mIMEJ0003.cgi�n�i�ړo�^�i�����j
# �m�쐬���n2002/11/05
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
$stock_subject_code = $in{'stock_subject_code'} ;
$item_no            = $in{'item_no'} ;
$TYPES              = $in{'TYPES'} ;

if($TYPES eq ""){$TYPES="�V�K�o�^" ;}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

if($TYPES eq "�ύX"){
	#ITEM
	$sql  = "select i.*,s.stock_subject from item i,stock_subject s " ;
	$sql .= "where  i.item_no            = '$item_no'";
	$sql .= "  and  i.stock_subject_code = s.stock_subject_code(+) ";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_MST err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_MST err $DBI::err .... $DBI::errstr") ;
	@datas = $cur->fetchrow() ;
	for($j=0;$j<@datas;$j++){
		$cur->{NAME}->[$j] =~ tr/[A-Z]/[a-z]/ ;
		${$cur->{NAME}->[$j]} = $datas[$j] ;
	}
}

	#COMPANY
	$sql = "select country_code,curr_code from company where company_type = 0";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_COMPANY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_COMPANY err $DBI::err .... $DBI::errstr") ;
	($my_country_code,$my_curr_code) = $cur->fetchrow() ;
	$cur->finish;

	#ITEMFLAG
	$sql = "select item_flag,flag_name from itemflag order by flag_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_ITEMFLAG err $DBI::err .... $DBI::errstr") ;
	$IMF_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$IMF[$IMF_NUM][0] = $datas[0] ;
		$IMF[$IMF_NUM][1] = $datas[1] ;
		$IMF_NUM ++ ;
	}
	$cur->finish;

	#COUNTRY
	$sql = "select country_code,country from country order by area_code,country";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_COUNTRY err $DBI::err .... $DBI::errstr") ;
	$COU_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$COU[$COU_NUM][0] = $datas[0] ;
		$COU[$COU_NUM][1] = $datas[1] ;
		$COU_NUM ++ ;
	}
	$cur->finish;

	#UNIT
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'QUANTITY' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNQ_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNQ[$UNQ_NUM][0] = $datas[0] ;
		$UNQ[$UNQ_NUM][1] = $datas[1] ;
		$UNQ_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'WEIGHT' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNW_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNW[$UNW_NUM][0] = $datas[0] ;
		$UNW[$UNW_NUM][1] = $datas[1] ;
		$UNW_NUM ++ ;
	}
	$cur->finish;
	$sql = "select unit_code,unit from unit where delete_type is null and use = 'LENGTH' order by unit";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_UNIT err $DBI::err .... $DBI::errstr") ;
	$UNL_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$UNL[$UNL_NUM][0] = $datas[0] ;
		$UNL[$UNL_NUM][1] = $datas[1] ;
		$UNL_NUM ++ ;
	}
	$cur->finish;

	#ISSUEPOLICY
	$sql = "select issue_policy,policy_name from issuepolicy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_ISSUEPOLICY err $DBI::err .... $DBI::errstr") ;
	$ISS_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ISS[$ISS_NUM][0] = $datas[0] ;
		$ISS[$ISS_NUM][1] = $datas[1] ;
		$ISS_NUM ++ ;
	}
	$cur->finish;

	#SECTION
	$sql = "select section_code,section from section order by short_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_SECTION err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_SECTION err $DBI::err .... $DBI::errstr") ;
	$SEC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SEC[$SEC_NUM][0] = $datas[0] ;
		$SEC[$SEC_NUM][1] = $datas[1] ;
		$SEC_NUM ++ ;
	}
	$cur->finish;

	#STOCK_SUBJECT
	$sql = "select stock_subject_code,stock_subject from stock_subject order by stock_subject_code";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_STOCK_SUBJECT err $DBI::err .... $DBI::errstr") ;
	$SSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$SSC[$SSC_NUM][0] = $datas[0] ;
		$SSC[$SSC_NUM][1] = $datas[1] ;
		$SSC_NUM ++ ;
	}
	$cur->finish;

	#COSTPROCESS
	$sql = "select cost_process_code,cost_process_name from costprocess order by cost_process_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_COSTPROCESS err $DBI::err .... $DBI::errstr") ;
	$CPC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CPC[$CPC_NUM][0] = $datas[0] ;
		$CPC[$CPC_NUM][1] = $datas[1] ;
		$CPC_NUM ++ ;
	}
	$cur->finish;

	#COSTSUBJECT
	$sql = "select cost_subject_code,cost_subject_name from costsubject order by cost_subject_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_COSTSUBJECT err $DBI::err .... $DBI::errstr") ;
	$CSC_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CSC[$CSC_NUM][0] = $datas[0] ;
		$CSC[$CSC_NUM][1] = $datas[1] ;
		$CSC_NUM ++ ;
	}
	$cur->finish;

	#ORDER_POLICY
	$sql = "select order_policy,policy_name from order_policy order by policy_name";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_ORDER_POLICY err $DBI::err .... $DBI::errstr") ;
	$ORP_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$ORP[$ORP_NUM][0] = $datas[0] ;
		$ORP[$ORP_NUM][1] = $datas[1] ;
		$ORP_NUM ++ ;
	}
	$cur->finish;

	#MAKERFLAG
	$sql = "select maker_flag_code,maker_flag from makerflag order by maker_flag";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_MAKERFLAG err $DBI::err .... $DBI::errstr") ;
	$MAK_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$MAK[$MAK_NUM][0] = $datas[0] ;
		$MAK[$MAK_NUM][1] = $datas[1] ;
		$MAK_NUM ++ ;
	}
	$cur->finish;

	#CURRENCY
	$sql = "select curr_code,curr_mark from currency order by curr_code";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_CURRENCY err $DBI::err .... $DBI::errstr") ;
	$CURR_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$CURR[$CURR_NUM][0] = $datas[0] ;
		$CURR[$CURR_NUM][1] = $datas[1] ;
		$CURR_NUM ++ ;
	}
	$cur->finish;

	#INSPECTIONTYPE
	$sql = "select inspection_type,inspection_name from inspectiontype order by inspection_type";
	#print $sql . "<P>";
	$cur = $dbh->prepare($sql) || &err("IMEJ0003_INSPECTION err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0003_INSPECTION err $DBI::err .... $DBI::errstr") ;
	$INSP_NUM = 0 ;
	while(@datas = $cur->fetchrow){
		$INSP[$INSP_NUM][0] = $datas[0] ;
		$INSP[$INSP_NUM][1] = $datas[1] ;
		$INSP_NUM ++ ;
	}
	$cur->finish;

#-------------------------
# HTML
#-------------------------
&html_header("�i�ڃ}�X�^�����e�i���X");
&title('�i�ڃ}�X�^�����e�i���X','IMEJ0003');
print <<ENDOFTEXT ;
<SCRIPT LANGUAGE=javascript SRC=../../lib/jslib.js></SCRIPT>
<SCRIPT LANGUAGE=javascript>
	function checkInput(theForm){
		if (!isNumber(theForm.selling_unit_qty.value    )){ theForm.selling_unit_qty.value = 0     ;}
		if (!isNumber(theForm.external_unit_number.value)){ theForm.external_unit_number.value = 0 ;}
		if (!isNumber(theForm.safety_stock.value        )){ theForm.safety_stock.value = 0         ;}
		if (!isNumber(theForm.weight.value              )){ theForm.weight.value = 0               ;}
		if (!isNumber(theForm.suppliers_price.value     )){ theForm.suppliers_price.value = 0      ;}
		if (!isNumber(theForm.manufact_leadtime.value   )){ theForm.manufact_leadtime.value = 0    ;}
		if (!isNumber(theForm.purchase_leadtime.value   )){ theForm.purchase_leadtime.value = 0    ;}
		if (!isNumber(theForm.adjustment_leadtime.value )){ theForm.adjustment_leadtime.value = 0  ;}
		if (!isNumber(theForm.manufact_fail_rate.value  )){ theForm.manufact_fail_rate.value = 0   ;}

		if (theForm.weight.value           > 999999.99999     ){ alert("�d�ʂ́u999999.99999�v�ȉ����͂ł��B");			return false; }
		if (theForm.standard_price.value   > 999999999.9999   ){ alert("������P���́u999999999.9999�v�ȉ����͂ł��B");	return false; }
		if (theForm.next_term_price.value  > 999999999.9999   ){ alert("������P���́u999999999.9999�v�ȉ����͂ł��B");	return false; }
		if (theForm.suppliers_price.value  > 999999999.9999   ){ alert("�x���P���́u999999999.9999�v�ȉ����͂ł��B");		return false; }
		if (theForm.selling_unit_qty.value > 999999999.999999 ){ alert("�̔��P�ʐ��ʂ́u999999999.999999�v�ȉ����͂ł��B");	return false; }

		if (!isNumber(theForm.item_no.value             )){ alert("�u�i�ڃR�[�h�v�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.selling_unit_qty.value    )){ alert("�u�̔��P�ʐ��ʁv�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.external_unit_number.value)){ alert("�u�O��������v�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.safety_stock.value        )){ alert("�u���S�݌ɐ��v�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.weight.value              )){ alert("�u�d�ʁv�ɐ����ȊO�����͂���Ă��܂��B");			return false; }
		if (!isNumber(theForm.unit_engineer_rate.value  )){ alert("�u�P�ʕϊ����i�Z�p�j�v�ɐ����ȊO�����͂���Ă��܂��B");	return false; }
		if (!isNumber(theForm.unit_stock_rate.value     )){ alert("�u�P�ʕϊ����i�݌Ɂj�v�ɐ����ȊO�����͂���Ă��܂��B");	return false; }
		if (!isNumber(theForm.standard_price.value      )){ alert("�u������P���v�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.next_term_price.value     )){ alert("�u������P���v�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.manufact_leadtime.value   )){ alert("�u������ԁv�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.purchase_leadtime.value   )){ alert("�u���B��ԁv�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.adjustment_leadtime.value )){ alert("�u������ԁv�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
		if (!isNumber(theForm.manufact_fail_rate.value  )){ alert("�u���H�d�����v�ɐ����ȊO�����͂���Ă��܂��B");		return false; }
	}
	function isNumber(str){
		if (str.length == 0){ return false; }
			for (var i=0; i< str.length; i++){
				if ( "0123456789.".indexOf(str.charAt(i))== -1){ return false; }
			}
			return true;
	}

</SCRIPT>
ENDOFTEXT
if($TYPES eq "�V�K�o�^"){
	print "�@�y�V�K�o�^�z<BR><BR>" ;
 	print "�@---<FONT COLOR=RED><B>�Ԏ�����</B></FONT>�͕K�{���ڂł�----" ;
}else{
	print "�@�y$TYPES�z<BR>" ;
}
print "<BR>" ;
print "<FORM METHOD=Post NAME=myform ACTION=IMEJ0004.cgi?$KEYWORD onSubmit=\"return checkInput(this)\">" ;
print "<TABLE BORDER=0><TBODY><TR><TD>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"><TBODY>" ;
print " <TR><TD BGCOLOR=LightGreen><FONT COLOR=RED><B>�i�ڃR�[�h</B></FONT></TD>\n" ;
if($TYPES eq "�V�K�o�^"){
	print "   <TD><INPUT TYPE=Text NAME=item_no VALUE=\"$item_no\" SIZE=8 MAXLENGTH=8 onChange= 'chkNum(this.form,this.value,\"8,0\")'>(���p�����W�����ȓ�)</TD>\n" ;
}else{
	print "   <TD> $item_no<INPUT TYPE=Hidden NAME=item_no VALUE=\"$item_no\"></TD>\n" ;
}
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>���Y�敪</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=stock_subject_code>" ;
		if($SSC_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$SSC_NUM;$i++){
			if($SSC[$i][0] eq $stock_subject_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$SSC[$i][0]\" $SEL>$SSC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>�i�ږ�</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=item VALUE=\"$item\" SIZE=41 MAXLENGTH=40></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���ދ敪</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=item_flag>" ;
		if($IMF_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$IMF_NUM;$i++){
			if($IMF[$i][0] eq $item_flag){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$IMF[$i][0]\" $SEL>$IMF[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>�K�i</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=description VALUE=\"$description\" SIZE=51 MAXLENGTH=50></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>���Y��</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=origin_code>" ;
		if($COU_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		if($origin_code eq ""){ $origin_code = $my_country_code ; }
		for($i=0;$i<$COU_NUM;$i++){
			if($COU[$i][0] eq $origin_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$COU[$i][0]\" $SEL>$COU[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>���[�J�[</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=mak VALUE=\"$mak\" SIZE=31 MAXLENGTH=30></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>�ʉ�</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=curr_code>" ;
		if($CURR_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		if($curr_code eq ""){ $curr_code = $my_curr_code ; }
		for($i=0;$i<$CURR_NUM;$i++){
			if($CURR[$i][0] eq $curr_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CURR[$i][0]\" $SEL>$CURR[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
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
print "   <TD><INPUT TYPE=Text NAME=selling_unit_qty VALUE=\"$selling_unit_qty\" SIZE=17 MAXLENGTH=16 onChange='chkNum(this.form,this.value,\"15,6\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>�O�������</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=external_unit_number VALUE=\"$external_unit_number\" SIZE=6 MAXLENGTH=5 onChange='chkNum(this.form,this.value,\"5,0\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���S�݌ɐ�</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=safety_stock VALUE=\"$safety_stock\" SIZE=10 MAXLENGTH=9 onChange='chkNum(this.form,this.value,\"9,0\")'></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>�d��</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=weight VALUE=\"$weight\" SIZE=13 MAXLENGTH=12 onChange='chkNum(this.form,this.value,\"11,5\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>�d�ʒP��</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=uom_w>" ;
		if($UNW_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$UNW_NUM;$i++){
			if($UNW[$i][0] eq $uom_w){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNW[$i][0]\" $SEL>$UNW[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>�e�ϒP��</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=uom_l>" ;
		if($UNL_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$UNL_NUM;$i++){
			if($UNL[$i][0] eq $uom_l){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNL[$i][0]\" $SEL>$UNL[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen COLSPAN=3><FONT COLOR=RED><B>�P�ʕϊ����i�΍݌ɒP�ʁj:�P�ʕϊ����i�΋Z�p�P�ʁj</B></FONT></TD>\n" ;
		if($unit_stock_rate    eq ""){ $unit_stock_rate = 1 ; }
		if($unit_engineer_rate eq ""){ $unit_engineer_rate = 1 ; }

print "   <TD COLSPAN=3><INPUT TYPE=Text NAME=unit_stock_rate VALUE=\"$unit_stock_rate\" SIZE=7 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")'>\n" ;
print "   <SELECT NAME=unit_stock>" ;
		if($UNQ_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$UNQ_NUM;$i++){
			if($UNQ[$i][0] eq $unit_stock){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNQ[$i][0]\" $SEL>$UNQ[$i][1]" ;
		}
print "   </SELECT>" ;
print " �F <INPUT TYPE=Text NAME=unit_engineer_rate VALUE=\"$unit_engineer_rate\" SIZE=7 MAXLENGTH=6 onChange='chkNum(this.form,this.value,\"6,0\")'>\n" ;
print "   <SELECT NAME=unit_engineering>" ;
		if($UNQ_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$UNQ_NUM;$i++){
			if($UNQ[$i][0] eq $unit_engineering){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$UNQ[$i][0]\" $SEL>$UNQ[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
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
print "   <TD><INPUT TYPE=Text NAME=catalog_no VALUE=\"$catalog_no\" SIZE=29 MAXLENGTH=28></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>�i�`�m�R�[�h�i���i�ԍ��j</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=item_code VALUE=\"$item_code\" SIZE=25 MAXLENGTH=24></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>������P��</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=standard_price VALUE=\"$standard_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>������P��</B></FONT></TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=next_term_price VALUE=\"$next_term_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>�x���P��</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=suppliers_price VALUE=\"$suppliers_price\" SIZE=15 MAXLENGTH=14 onChange='chkNum(this.form,this.value,\"13,4\")'></TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR>" ;
print "<TR><TD>�@<FONT SIZE = -1>��������P�������܂��Ă��Ȃ��ꍇ�͓�����P���Ɠ��z��o�^���ĉ������B</FONT><BR>" ;
#--------------------------------------------------------------------------------------------------------------------------
print "</TD></TR>" ;
print "<TR><TD>" ;
print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0 width=\"100%\"></TBODY>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>�������</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=manufact_leadtime VALUE=\"$manufact_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>��</TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���B���</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=purchase_leadtime VALUE=\"$purchase_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>��</TD>\n" ;
print "   <TD BGCOLOR=LightGreen>�������</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=adjustment_leadtime VALUE=\"$adjustment_leadtime\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>��</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>���o�敪</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=issue_policy>" ;
		if($ISS_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$ISS_NUM;$i++){
			if($ISS[$i][0] eq $issue_policy){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$ISS[$i][0]\" $SEL>$ISS[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���o���b�g</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=issue_lot VALUE=\"$issue_lot\" SIZE=10 MAXLENGTH=9 onChange='chkNum(this.form,this.value,\"9,0\")'></TD>\n" ;
print "   <TD BGCOLOR=LightGreen>���H�d����</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=manufact_fail_rate VALUE=\"$manufact_fail_rate\" SIZE=4 MAXLENGTH=3 onChange='chkNum(this.form,this.value,\"3,0\")'>��</TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>����R�[�h</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=section_code>" ;
		if($SEC_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$SEC_NUM;$i++){
			if($SEC[$i][0] eq $section_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$SEC[$i][0]\" $SEL>$SEC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>�����R�[�h</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=cost_process_code>" ;
		if($CPC_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$CPC_NUM;$i++){
			if($CPC[$i][0] eq $cost_process_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CPC[$i][0]\" $SEL>$CPC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen><FONT COLOR=RED><B>�ȖڃR�[�h</B></FONT></TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=cost_subject_code>" ;
		if($CSC_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$CSC_NUM;$i++){
			if($CSC[$i][0] eq $cost_subject_code){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$CSC[$i][0]\" $SEL>$CSC[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>��������</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=order_policy>" ;
		if($ORP_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$ORP_NUM;$i++){
			if($ORP[$i][0] eq $order_policy){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$ORP[$i][0]\" $SEL>$ORP[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen>��z��敪</TD>\n" ;
print "   <TD>" ;
print "   <SELECT NAME=maker_flag>" ;
		if($MAK_NUM > 0){ print "<OPTION VALUE=''>" ; }else{ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$MAK_NUM;$i++){
			if($MAK[$i][0] eq $maker_flag){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$MAK[$i][0]\" $SEL>$MAK[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print "   <TD BGCOLOR=LightGreen>����</TD>\n" ;
print "   <TD><INPUT TYPE=Text NAME=item_type1 VALUE=\"$item_type1\" SIZE=31 MAXLENGTH=30></TD>\n" ;
print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen>�����敪</TD>\n" ;
print "   <TD COLSPAN=5>";
print "   <SELECT NAME=inspection_type>" ;
		if($INSP_NUM == 0){ print "<OPTION VALUE=''>�}�X�^�o�^�Ȃ�" ; }
		for($i=0;$i<$INSP_NUM;$i++){
			if($INSP[$i][0] eq $inspection_type){ $SEL = "SELECTED" ; }else{ $SEL = "" ; }
			print " <OPTION VALUE=\"$INSP[$i][0]\" $SEL>$INSP[$i][1]" ;
		}
print "   </SELECT>" ;
print "   </TD>\n" ;
print " </TR>" ;
print "</TBODY></TABLE>" ;
print "</TD></TR></TBODY></TABLE>" ;
print<<ENDOFTEXT;
<P>
<INPUT TYPE=Hidden NAME=TYPES VALUE='$TYPES'>
<input type=submit name=submit value="����">
<input type=button value='�߂�' onClick='javascript:history.back()'>
<input type=reset name=submit value="���̓��Z�b�g">
</FORM>
<HR>
</BODY>
</HTML>
ENDOFTEXT

exit;