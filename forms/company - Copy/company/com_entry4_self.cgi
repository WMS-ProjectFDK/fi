#!/usr/local/bin/perl
#
# Modify
#   2006/06/19   HTML タグの抜けを修正 (HTML,TITLE)
#   2006/06/19   入力項目追加 (COMPANY_SHORT_NAME,TAXPAYER_No) …… FIの税務署向けの資料に表示が必要な為
#   2006/06/19   エラーチェック強化（自社コードの複数登録は不可とする）
#   2006/06/21   入力項目追加 (E MAIL) FDKランカ依頼
#

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

if($TYPES eq "NEW"){
    #
    # 自社コードの登録の複数件登録は不可
    #
     $sql = "select nvl(count(*),0) from company where company_type = 0 " ;
     $cur = $dbh->prepare($sql) || &err("OWN CNT err $DBI::err .... $DBI::errstr") ;
     $cur->execute() || &err("OWN CNT err $DBI::err .... $DBI::errstr") ;
       ($own_com_cnt) = $cur->fetchrow() ;
     $cur->finish;
     if($own_com_cnt != 0){ &err("SELF IS ALREADY REGISTERED.") ;}

    #
    # ダブりチェック
    #
    $sql = "select count(*) from company where company_code ='$company_code' ";
    $cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
    $cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
    ($COUNT) = $cur->fetchrow() ;
    $cur->finish;
    if($COUNT >0){ &err("THIS CODE IS ALREADY USED.") ;}
}

if($TYPES eq "DELETE" or $TYPES eq "RESTORE"){
   $sql  = "select ";
   $sql .= " com.upto_date     ,";
   $sql .= " com.reg_date      ,";
   $sql .= " com.delete_type   ,";
   $sql .= " com.company_code  ,";
   $sql .= " com.company_type  ,";
   $sql .= " com.company  as company_e     ,";
   $sql .= " com.address1 as address_e1     ,";
   $sql .= " com.address2 as address_e2     ,";
   $sql .= " com.address3 as address_e3     ,";
   $sql .= " com.address4 as address_e4     ,";
   $sql .= " com.attn          ,";
   $sql .= " com.tel_no   as tel_no_e     ,";
   $sql .= " com.fax_no   as fax_no_e     ,";
   $sql .= " com.zip_code      ,";
   $sql .= " com.country_code  ,";
   $sql .= " cou.country  ,";
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
   $sql .= " from company com,country cou,company2 com2";
   $sql .= " where com.country_code = cou.country_code(+) ";
   $sql .= " and com.company_code=com2.company_code(+) ";
   $sql .= "  and com.company_code = '$company_code' " ;
       
   #print $sql ;
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
$sql = "select type_description from company_type where company_type ='$company_type' ";
$cur = $dbh->prepare($sql) || &err("TYPE err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("TYPE err $DBI::err .... $DBI::errstr") ;
($type_description) = $cur->fetchrow() ;
$cur->finish;

#
# country
#
$sql = "select country from country where country_code ='$country_code' ";
$cur = $dbh->prepare($sql) || &err("COUNTRY err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("COUNTRY err $DBI::err .... $DBI::errstr") ;
($country) = $cur->fetchrow() ;
$cur->finish;

#
# CURRENCY
#
$sql = "select curr_mark from currency where curr_code ='$curr_code' ";
$cur = $dbh->prepare($sql) || &err("CURRENCY err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("CURRENCY err $DBI::err .... $DBI::errstr") ;
($curr_mark) = $cur->fetchrow() ;
$cur->finish;

#
# ERROR CHECK
#
 $BG_COM_CODE ="white";
 $BG_COM_NAME_E ="white";
 $BG_COM_NAME_J ="white";
 $BG_COM_TYPE ="white";
 $BG_COM_ADDR_E ="white";
 $BG_COM_ADDR_J ="white";


# $BG_COM_CODE ="White" ;
#  $BG_COM_NAME_E ="White" ;
#  $BG_COM_NAME_J ="White" ;

#  $BG_COM_TYPE ="White" ;
#  $BG_COM_ADDR ="White" ;
#  $BG_COM_COU ="White" ;
#  $BG_COM_CURR ="White" ;
#  $BG_COM_TTERM ="White" ;
#  $BG_COM_PAY ="White" ;

 if($TYPES ne "DELETE"){
   if($company_code eq ""){ $BG_COM_CODE ="Pink" ;}
    if($company_e eq ""){ $BG_COM_NAME_E ="Pink" ;}
    if($company_j eq ""){ $BG_COM_NAME_J ="Pink" ;}
  # if($company_type eq ""){ $BG_COM_TYPE ="Pink" ;}
    if($address_e1 eq ""){ $BG_COM_ADDR_E ="Pink" ;}
    if($address_j1 eq ""){ $BG_COM_ADDR_J ="Pink" ;}
if($curr_code eq ""){ $BG_COM_CURR ="Pink" ;}
   


    




    
    if($country_code eq ""){ $BG_COM_COU ="Pink" ;}
   
   if($TYPES eq "NEW"){
    if($curr_code eq ""){ $BG_COM_CURR ="Pink" ;}
    if($tterm eq ""){ $BG_COM_TTERM ="Pink" ;}
    if($pdays eq "" || $pdays =~  /[^0-9]/){ $BG_COM_PAY ="Pink" ;}
    if($pdesc eq ""){ $BG_COM_PAY ="Pink" ;}
   }
}
#-------------------------
# HTML
#-------------------------
 &html_header("COMPANY MAINTENANCE");
 &title();
 print "COMPANY MAINTENANCE" ;
 #print "<H2>COMPANY MAINTENANCE</H2>" ;
 #print "<HR>" ;
 print "<FORM METHOD=Post ACTION=com_entry5_self.cgi?$KEYWORD>" ;
 foreach(keys (%in)){
    print "<INPUT TYPE=Hidden NAME=$_ VALUE=\"$$_\">\n" ;
 }
 print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen COLSPAN=2>CODE</TD>" ;
 print "   <TD COLSPAN=3 BGCOLOR=$BG_COM_CODE>$company_code<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen ROWSPAN=2>NAME</TD>" ;
  print "   <TD BGCOLOR=LightGreen>JAPANESE</FONT></TD>" ;
 print "   <TD COLSPAN=2 BGCOLOR=$BG_COM_NAME_J>$company_j</TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>ENGLISH</TD>" ;
 print "   <TD COLSPAN=2 BGCOLOR=$BG_COM_NAME_E>$company_e</TD>" ;
 print " </TR>" ;
 
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen ROWSPAN=2>ADDRESS</TD>" ;
 print "   <TD BGCOLOR=LightGreen>JAPANESE</TD>" ;
 print "   <TD COLSPAN=2  BGCOLOR=$BG_COM_ADDR_J>" ;
 print "     $address_j1<BR>" ;
 print "     $address_j2<BR>" ;
 print "     $address_j3<BR>" ;
 print "     $address_j4<BR>" ;
 print "   </TD>" ; 
 print " </TR>" ;
 print "   <TD BGCOLOR=LightGreen>ENGLISH</TD>" ;
 print "   <TD COLSPAN=2  BGCOLOR=$BG_COM_ADDR_E>" ;
 print "     $address_e1<BR>" ;
 print "     $address_e2<BR>" ;
 print "     $address_e3<BR>" ;
 print "     $address_e4<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen ROWSPAN=2>TEL</TD>" ;
 print "   <TD BGCOLOR=LightGreen >JAPANESE</TD>" ;
 print "   <TD COLSPAN=2 >$tel_no_j</TD>" ;
 print "  <TR><TD BGCOLOR=LightGreen>ENGLISH</TD>" ;
 print "   <TD COLSPAN=2>$tel_no_e</TD>" ;


 print "  <TR><TD BGCOLOR=LightGreen ROWSPAN=2>FAX</TD>" ;
 print "   <TD BGCOLOR=LightGreen >JAPANESE</TD>" ;
 print "   <TD COLSPAN=2>$fax_no_j</TD>" ;
 print " </TR>" ;
 print " <TR><TD BGCOLOR=LightGreen >ENGLISH</TD>" ;
 print "   <TD COLSPAN=2>$fax_no_e</TD>" ;
 print " </TR>" ;
  
 print " <TR>" ;
 #print "   <TD BGCOLOR=LightGreen>ATTN</TD>" ;
 #print "   <TD>$attn<BR></TD>" ;
 
 print "   <TD BGCOLOR=LightGreen COLSPAN=2>ZIP CODE</TD>" ;
 print "   <TD COLSPAN=2>$zip_code<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen COLSPAN=2>COUNTRY</TD>" ;
 print "   <TD  BGCOLOR=$BG_COM_COU>$country<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 #print " <TR>" ;
 #print "   <TD BGCOLOR=LightGreen>EDI CODE</TD>" ;
 #print "   <TD COLSPAN=3>$edi_code<BR></TD>" ;
 #print " </TR>" ;
 #print " <TR>" ;
 #print "   <TD BGCOLOR=LightGreen>SUPPLY TYPE</TD>" ;
 #print "   <TD COLSPAN=3>$supply_type<BR></TD>" ;
 #print " </TR>" ;
 #print "   <TD BGCOLOR=LightGreen>DAYS OF TRANSPORT</TD>" ;
 #   $transport_days = $transport_days * 1 ;
 #print "   <TD COLSPAN=3>$transport_days DAYS<BR></TD>" ;
 #print " </TR>" ;
 
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen COLSPAN=2>CURRENCY</TD>\n" ;
 print " <TD COLSPAN=3 BGCOLOR=$BG_COM_CURR>$curr_mark<BR></TD>\n" ;
 print "</TR>\n" ;
 
 print " </TABLE>" ;

#if($TYPES eq "NEW"){
# print "<P>" ;
# print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>CURRENCY</TD>\n" ;
# print " <TD COLSPAN=3 BGCOLOR=$BG_COM_CURR>$curr_mark<BR></TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>PAYMENT</TD>\n" ;
# print " <TD COLSPAN=3 BGCOLOR=$BG_COM_PAY>\n" ;
# print "  $pmethod\n" ;
# print "  $pdays\n" ;
# print "  $pdesc\n" ;
# print " <BR></TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>TRADE TERMS</TD>\n" ;
# print " <TD COLSPAN=3  BGCOLOR=$BG_COM_TTERM>$tterm<BR></TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>LOADING PORT</TD>\n" ;
# print " <TD>\n" ;
# print "  $loading_port<BR>\n" ;
# print " </TD>\n" ;
# print " <TD BGCOLOR=LightGreen>DISCHARGE PORT</TD>\n" ;
# print " <TD>\n" ;
# print "  $discharge_port<BR>\n" ;
# print " </TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD BGCOLOR=LightGreen>FINAL DESTINATION</TD>\n" ;
# print " <TD COLSPAN=3>\n" ;
# print "  $final_dest<BR>\n" ;
# print " </TD>\n" ;
# print "</TR>\n" ;
# print "<TR>\n" ;
# print " <TD VALIGN=Top  BGCOLOR=LightGreen>SHIPPING MARK</TD>\n" ;
# print " <TD COLSPAN=3><PRE>$marks</PRE><BR>\n" ;
# print " </TD>\n" ;
# print "</TR>\n" ;
# print "</TABLE>\n" ;
#} 

# 2006/06/19 入力項目追加 
 print "<P>" ;
 print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>COMPANY SHORT NAME</TD>\n" ;
 print " <TD>$company_short<BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>TAXPAYER No.</TD>\n" ;
 print " <TD>$taxpayer_no<BR></TD>\n" ;
 print "</TR>\n" ;
# 2006/06/21 入力項目追加 
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>E MAIL</TD>\n" ;
 print " <TD>$e_mail<BR></TD>\n" ;
 print "</TR>\n" ;
 print "</TABLE>\n" ;

print<<ENDOFTEXT;
<P>
<input type=submit name=submit value="CONFIRM">
<input type=button value='Back' onClick='javascript:history.back()'>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

