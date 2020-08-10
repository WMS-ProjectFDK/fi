#!/usr/local/bin/perl
#
# Modify
#   2006/06/19   HTML タグの抜けを修正 (HTML,TITLE)
#   2006/06/19   入力項目追加 (COMPANY_SHORT_NAME,TAXPAYER_No) …… FIの税務署向けの資料に表示が必要な為
#   2006/06/21   入力項目追加 (E MAIL) FDKランカ依頼
#

#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# POST
#-------------------------
  $company_code = $in{'company_code'} ;
  $TYPES = $in{'TYPES'} ;

  if($TYPES eq ""){$TYPES="NEW" ;}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

if($TYPES eq "CHANGE"){
   
   #   
   # SQL
   #   
   $sql  = "select ";
   $sql .= " com.upto_date     ,";
   $sql .= " com.reg_date      ,";
   $sql .= " com.delete_type   ,";
   $sql .= " com.company_code  ,";
   $sql .= " com.company_type  ,";
   $sql .= " com.company  as company_e       ,";
   $sql .= " com.address1 as address_e1     ,";
   $sql .= " com.address2 as address_e2     ,";
   $sql .= " com.address3 as address_e3     ,";
   $sql .= " com.address4 as address_e4     ,";
   $sql .= " com.attn          ,";
   $sql .= " com.tel_no   as tel_no_e     ,";
   $sql .= " com.fax_no   as fax_no_e     ,";
   $sql .= " com.zip_code      ,";
   $sql .= " com.country_code  ,";
   $sql .= " cou.country       ,";
   $sql .= " com.curr_code     ,";
   $sql .= " com.tterm         ,";
   $sql .= " com.pdays         ,";
   $sql .= " com.pdesc         ,";
   $sql .= " com.case_mark     ,";
   $sql .= " com.edi_code      ,";
   $sql .= " com.vat           ,";
   $sql .= " com.supply_type   ,";
   $sql .= " com.transport_days,";
   $sql .= " com.company_short ,";
   $sql .= " com.taxpayer_no   ,";
   $sql .= " com.e_mail        ,";
   $sql .= " cou.country,";
   $sql .= " com2.company as company_j,";
   $sql .= " com2.address1 as address_j1 ,";
   $sql .= " com2.address2 as address_j2     ,";
   $sql .= " com2.address3 as address_j3     ,";
   $sql .= " com2.address4 as address_j4     ,";
   $sql .= " com2.tel_no as tel_no_j     ,";
   $sql .= " com2.fax_no as fax_no_j     ,";
   chop($sql);
   $sql .= " from company com,country cou,company com2";
   $sql .= " where com.country_code = cou.country_code(+) ";
   $sql .= " and com.company_code=com2.company_code(+) ";
   $sql .= "  and com.company_code = '$company_code' " ;
       
   $cur = $dbh->prepare($sql) || &err("COM err $DBI::err .... $DBI::errstr") ;
   $cur->execute() || &err("COM err $DBI::err .... $DBI::errstr") ;
   @datas = $cur->fetchrow() ;
         for($j=0;$j<@datas;$j++){
            $cur->{NAME}->[$j] =~ tr/[A-Z]/[a-z]/ ;
            ${$cur->{NAME}->[$j]} = $datas[$j] ;
         }
}

#
# company type
#
$sql = "select company_type,type_description from company_type where company_type >0 order by company_type";
$cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
$TP_NUM = 0 ;
while(@datas = $cur->fetchrow){
   $TP[$TP_NUM][0] = $datas[0] ;
   $TP[$TP_NUM][1] = $datas[1] ;
    $TP_NUM ++ ;
}
$cur->finish;

#
# country
#
$sql = "select country_code,country from country order by area_code,country";
$cur = $dbh->prepare($sql) || &err("COUNTRY err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("COUNTRY err $DBI::err .... $DBI::errstr") ;
$COU_NUM = 0 ;
while(@datas = $cur->fetchrow){
   $COU[$COU_NUM][0] = $datas[0] ;
   $COU[$COU_NUM][1] = $datas[1] ;
    $COU_NUM ++ ;
}
$cur->finish;

#
# CURRENCY
#
$sql = "select curr_code,curr_mark from currency order by curr_code";
$cur = $dbh->prepare($sql) || &err("COUNTRY err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("COUNTRY err $DBI::err .... $DBI::errstr") ;
$CURR_NUM = 0 ;
while(@datas = $cur->fetchrow){
   $CURR[$CURR_NUM][0] = $datas[0] ;
   $CURR[$CURR_NUM][1] = $datas[1] ;
    $CURR_NUM ++ ;
}
$cur->finish;


#-------------------------
# HTML
#-------------------------
&html_header("COMPANY MAINTENANCE");
&title();
print <<ENDOFTEXT ;
 <SCRIPT LANGUAGE=javascript>
     function setPort(F_NAME,F_CODE){
         Param  = "?KEYWORD=$KEYWORD" ;
         Param += "&f_name=" + F_NAME ;
         Param += "&f_code=" + F_CODE ;
         w2 = window.open('port_set.pl' + Param, 'port','WIDTH=400,HEIGHT=600,SCROLLBARS=1,RESIZABLE=1') ;
     }
 </SCRIPT>
ENDOFTEXT
 print "COMPANY MAINTENANCE<BR>\n" ;
 #print "<H2>COMPANY MAINTENANCE</H2>" ;
 #print "<HR>" ;
 print "<FORM METHOD=Post NAME=myform ACTION=com_entry4_self.cgi?$KEYWORD>" ;
 print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen COLSPAN=2><FONT COLOR=Blue>CODE</FONT></TD>" ;
 if($TYPES eq "NEW"){
 print "   <TD COLSPAN=3><INPUT TYPE=Text NAME=company_code VALUE=\"$company_code\" SIZE=8 MAXLENGTH=8></TD>" ;
 }else{
 print "   <TD COLSPAN=3>$company_code<INPUT TYPE=Hidden NAME=company_code VALUE=\"$company_code\" SIZE=8 MAXLENGTH=8></TD>" ;
 }
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen ROWSPAN=2><FONT COLOR=Blue>NAME</FONT></TD>" ;
 print "   <TD BGCOLOR=LightGreen ><FONT COLOR=Blue>JAPANESE</FONT></TD>" ;
 print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=company_j VALUE=\"$company_j\" SIZE=50 MAXLENGTH=50></TD>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen><FONT COLOR=Blue>ENGLISH</FONT></TD>" ;
 print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=company_e VALUE=\"$company_e\" SIZE=50 MAXLENGTH=50></TD>" ;
 print " </TR>" ;

 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen ROWSPAN=2><FONT COLOR=Blue>ADDRESS</FONT></TD>" ;
 print "   <TD  BGCOLOR=LightGreen><FONT COLOR=Blue>JAPANESE</FONT></TD>" ;
 print "   <TD COLSPAN=2>  <INPUT TYPE=Text NAME=address_j1 VALUE=\"$address_j1\" SIZE=50 MAXLENGTH=50><BR>" ;
 print "     <INPUT TYPE=Text NAME=address_j2 VALUE=\"$address_j2\" SIZE=50 MAXLENGTH=50><BR>" ;
 print "     <INPUT TYPE=Text NAME=address_j3 VALUE=\"$address_j3\" SIZE=50 MAXLENGTH=50><BR>" ;
 print "     <INPUT TYPE=Text NAME=address_j4 VALUE=\"$address_j4\" SIZE=50 MAXLENGTH=50><BR>" ;
 print "   </TD>" ;
  print " <TR>" ;
 print "   <TD  BGCOLOR=LightGreen><FONT COLOR=Blue>ENGLISH</FONT></TD>" ;
 print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=address_e1 VALUE=\"$address_e1\" SIZE=50 MAXLENGTH=50><BR>" ;
 print "     <INPUT TYPE=Text NAME=address_e2 VALUE=\"$address_e2\" SIZE=50 MAXLENGTH=50><BR>" ;
 print "     <INPUT TYPE=Text NAME=address_e3 VALUE=\"$address_e3\" SIZE=50 MAXLENGTH=50><BR>" ;
 print "     <INPUT TYPE=Text NAME=address_e4 VALUE=\"$address_e4\" SIZE=50 MAXLENGTH=50><BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen ROWSPAN=2>TEL</TD>" ;
 print "   <TD BGCOLOR=LightGreen >JAPANESE</TD>" ;
 print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=tel_no_j VALUE=\"$tel_no_j\" SIZE=25 MAXLENGTH=25></TD>" ;
  print "   <TR><TD BGCOLOR=LightGreen>ENGLISH</TD>" ;
  print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=tel_no_e VALUE=\"$tel_no_e\" SIZE=25 MAXLENGTH=25></TD>" ;
 
 
 print "   <TR><TD BGCOLOR=LightGreen ROWSPAN=2>FAX</TD>" ;
 print "   <TD BGCOLOR=LightGreen >JAPANESE</TD>" ;
 print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=fax_no_j VALUE=\"$fax_no_j\" SIZE=25 MAXLENGTH=25></TD>" ;
 print " </TR>" ;
print "   <TR><TD BGCOLOR=LightGreen>ENGLISH</TD>" ;
print "  <TD COLSPAN=2><INPUT TYPE=Text NAME=fax_no_e VALUE=\"$fax_no_e\" SIZE=25 MAXLENGTH=25></TD>" ;
 print " <TR>" ;
 #print "   <TD BGCOLOR=LightGreen>ATTN</TD>" ;
 #print "   <TD><INPUT TYPE=Text NAME=attn VALUE=\"$attn\" SIZE=25 MAXLENGTH=25></TD>" ;
 print "   <TD BGCOLOR=LightGreen COLSPAN=2>ZIP CODE</TD>" ;
 print "   <TD COLSPAN=2><INPUT TYPE=Text NAME=zip_code VALUE=\"$zip_code\" SIZE=10 MAXLENGTH=25></TD>" ;
 print " </TR>" ;
print " <TR>" ;
print "   <TD BGCOLOR=LightGreen COLSPAN=2><FONT COLOR=Blue>COUNTRY</FONT></TD>" ;
print "   <TD>" ;
  print "<SELECT NAME=country_code>" ;
  print " <OPTION VALUE=''>" ;
  for($i=0;$i<$COU_NUM;$i++){
    if($COU[$i][0] eq $country_code){ $SEL ="SELECTED" ; }else{ $SEL ="" ; }
    print " <OPTION VALUE=\"$COU[$i][0]\" $SEL>$COU[$i][1]" ;
  }
  print "</SELECT>" ;
  print "   </TD>" ;
 print " </TR>" ;
# print " <TR>" ;
# print "   <TD BGCOLOR=LightGreen>EDI CODE</TD>" ;
#print "   <TD COLSPAN=3><INPUT TYPE=Text NAME=edi_code VALUE=\"$edi_code\" SIZE=12 MAXLENGTH=12></TD>" ;
 #print " </TR>" ;
# print " <TR>" ;
# print "   <TD BGCOLOR=LightGreen>SUPPLY TYPE</TD>" ;
# print "   <TD COLSPAN=3>" ;
##   print "<SELECT NAME=supply_type>" ;
#     if($supply_type eq "Y"){ $SEL_Y ="SELECTED" ; }elsif($supply_type eq "N"){ $SEL_N ="SELECTED" ; }
#   print " <OPTION VALUE=''>" ;
#   print " <OPTION VALUE=Y $SEL_Y>CHARGE" ;
#   print " <OPTION VALUE=N $SEL_N>FREE OF CHARGE" ;
 #  print "</SELECT>" ;
 #print "   </TD>" ;
# print " </TR>" ;
#  print " <TR>" ;
# print "   <TD BGCOLOR=LightGreen>DAYS OF TRANSPORT</TD>" ;
# print "   <TD COLSPAN=3><INPUT TYPE=Text NAME=transport_days VALUE=\"$transport_days\" SIZE=4 MAXLENGTH=3>DAYS</TD>" ;
# print " </TR>" ;

print " <TD BGCOLOR=LightGreen COLSPAN=2><FONT COLOR=Blue>CURRENCY</FONT></TD>\n" ;
 print " <TD COLSPAN=3>\n" ;
 print "<SELECT NAME=curr_code>" ;
 print " <OPTION VALUE=''>" ;
 for($i=0;$i<$CURR_NUM;$i++){
   if($CURR[$i][0] eq $curr_code){ $SEL ="SELECTED" ; }else{ $SEL ="" ; }
    print " <OPTION VALUE=$CURR[$i][0] $SEL>$CURR[$i][1]" ;
 }
 print "</SELECT>" ;
 print "   </TD>" ;
 print "</TR>\n" ;
 print " </TABLE>" ;

#if($TYPES eq "NEW"){
# print "<P>" ;
# print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen><FONT COLOR=Blue>CURRENCY</FONT></TD>\n" ;
# print " <TD COLSPAN=3>\n" ;
# print "<SELECT NAME=curr_code>" ;
# print " <OPTION VALUE=''>" ;
# for($i=0;$i<$CURR_NUM;$i++){
#   if($CURR[$i][0] eq $curr_code){ $SEL ="SELECTED" ; }else{ $SEL ="" ; }
#    print " <OPTION VALUE=$CURR[$i][0] $SEL>$CURR[$i][1]" ;
# }
# print "</SELECT>" ;
# print "   </TD>" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>PAYMENT</TD>\n" ;
# print " <TD COLSPAN=3>\n" ;
# print "  METHOD:<INPUT TYPE=Text NAME=pmethod VALUE='$pmethod' SIZE=20 MAXLENGTH=20>\n" ;
# print "  DAYS:<INPUT TYPE=Text NAME=pdays VALUE='$pdays' SIZE=3 MAXLENGTH=3>\n" ;
# print "  DESC:<INPUT TYPE=Text NAME=pdesc VALUE='$pdesc' SIZE=40 MAXLENGTH=40>\n" ;
# print " </TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>TRADE TERMS</TD>\n" ;
# print " <TD COLSPAN=3>\n" ;
# print "<INPUT TYPE=Text NAME=tterm VALUE='$tterm' SIZE=20 MAXLENGTH=20>" ;
# print " </TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>LOADING PORT</TD>\n" ;
# print " <TD>\n" ;
# print "  <INPUT TYPE=Text NAME=port_loading_code VALUE='$port_loading_code' SIZE=6 MAXLENGTH=6>\n" ;
# print "  <INPUT TYPE=Text NAME=loading_port VALUE='$loading_port' SIZE=20 MAXLENGTH=25>\n" ;
#     print "<A HREF='javascript:setPort(\"loading_port\",\"port_loading_code\")'><IMG SRC=$IMG_DIR/set.gif BORDER=0></A> " ; 
# print " </TD>\n" ;
# print " <TD BGCOLOR=LightGreen>DISCHARGE PORT</TD>\n" ;
# print " <TD>\n" ;
# print "  <INPUT TYPE=Text NAME=port_discharge_code VALUE='$port_discharge_code' SIZE=6 MAXLENGTH=6>\n" ;
# print "  <INPUT TYPE=Text NAME=discharge_port VALUE='$discharge_port' SIZE=20 MAXLENGTH=25>\n" ;
#     print "<A HREF='javascript:setPort(\"discharge_port\",\"port_discharge_code\")'><IMG SRC=$IMG_DIR/set.gif BORDER=0></A> " ; 
# print " </TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>FINAL DESTINATION</TD>\n" ;
# print " <TD COLSPAN=3>\n" ;
# print "  <INPUT TYPE=Text NAME=final_destination_code VALUE='$final_destination_code' SIZE=6 MAXLENGTH=6>\n" ;
# print "  <INPUT TYPE=Text NAME=final_dest VALUE='$final_dest' SIZE=20 MAXLENGTH=20>\n" ;
#     print "<A HREF='javascript:setPort(\"final_dest\",\"final_destination_code\")'><IMG SRC=$IMG_DIR/set.gif BORDER=0></A> " ; 
# print " </TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD VALIGN=Top  BGCOLOR=LightGreen>SHIPPING MARK</TD>\n" ;
# print " <TD COLSPAN=3><TEXTAREA NAME=marks COLS=30 ROWS=10 >$marks</TEXTAREA>\n" ;
# print " </TD>\n" ;
# print "</TR>\n" ;
# print " </TABLE>" ;
#}

# 2006/06/19 入力項目追加 
 print "<P>\n" ;
 print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0>" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>COMPANY SHORT NAME<TD>\n" ;
 print " <TD>\n" ;
 print "  <INPUT TYPE=Text NAME=company_short VALUE='$company_short' SIZE=25 MAXLENGTH=20>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>TAXPAYER No.<TD>\n" ;
 print " <TD>\n" ;
 print "  <INPUT TYPE=Text NAME=taxpayer_no VALUE='$taxpayer_no' SIZE=35 MAXLENGTH=30>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
# 2006/06/21 入力項目追加 
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>E MAIL<TD>\n" ;
 print " <TD>\n" ;
 print "  <INPUT TYPE=Text NAME=e_mail VALUE='$e_mail' SIZE=45 MAXLENGTH=40>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
 print " </TABLE>" ;

print<<ENDOFTEXT;
<P>
<INPUT TYPE=Hidden NAME=TYPES VALUE='$TYPES'>
<input type=submit name=submit value="Next">
<input type=button value='Back' onClick='javascript:history.back()'>
<input type=reset name=submit value="Reset">
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;