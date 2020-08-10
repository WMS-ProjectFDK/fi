#
# Modify
#   2006/06/19   HTML タグの抜けを修正 (HTML,TITLE)
#   2006/06/19   入力項目追加 (COMPANY_SHORT_NAME,TAXPAYER_No) …… FIの税務署向けの資料に表示が必要な為
#   2006/06/21   入力項目追加 (E MAIL) FDKランカ依頼
#   2014/06/14   ACCPACカンバニーコード
#   2014/10/07   BONDED_TYPEの追加
#

# list_company2.pl
require '/pglosas/init.pl';

#
$company_code = $in{'company_code'} ;

  if($company_code eq ""){ &err("PLEASE SELECT COMPANY_CODE") ;}
#
# db open
#
$dbh = &db_open();



#
# check formed value and make sql
#
     $sql  = " select ";
     $sql .="  to_char(c.reg_date,'dd/mm/yyyy') reg_date," ;
     $sql .="  to_char(c.upto_date,'dd/mm/yyyy') upto_date," ;
     $sql .="  c.company_type      ," ;
     $sql .="  t.type_description  ," ;
     $sql .="  c.company_code      ," ;
     $sql .="  c.company           ," ;
     $sql .="  c.address1          ," ;
     $sql .="  c.address2          ," ;
     $sql .="  c.address3          ," ;
     $sql .="  c.address4          ," ;
     $sql .="  c.attn              ," ;
     $sql .="  c.tel_no            ," ;
     $sql .="  c.fax_no            ," ;
     $sql .="  c.zip_code          ," ;
     $sql .="  c.country_code      ," ;
     $sql .="  c.edi_code          ," ;
     $sql .="  c.vat               ," ;
     $sql .="  c.supply_type       ," ;
     $sql .="  c.subc_code         ," ;
     $sql .="  c.company_short     ," ;
     $sql .="  c.taxpayer_no       ," ;
     $sql .="  c.e_mail            ," ;
     $sql .="  c.accpac_company_code            ," ;
     $sql .= " c.pdays     pdays_c    ,";
     $sql .= " c.pdesc     pdesc_c    ,";
     $sql .= " u.country            ,";
     $sql .= " b.type_description   ,";
     chop($sql);
     $sql .= " from company c,";
     $sql .= "      company_type t,";
     $sql .= "      country u,";
     $sql .= "      bonded_type b,";
     chop($sql);
     $sql .= " where c.company_code ='$company_code' ";
     $sql .= " and   c.country_code = u.country_code (+) ";
     $sql .= " and   c.company_type = t.company_type (+) ";
     $sql .= " and   c.bonded_type =  b.bonded_type (+) ";
     $sql .= " order by c.company_type,c.company " ;
     $cur = $dbh->prepare($sql) || &err("Err $DBI::err .... $DBI::errstr") ;
     $cur->execute() || &err("Err $DBI::err .... $DBI::errstr") ;

 while((@datas) = $cur->fetchrow){
     for($i=0;$i<@datas;$i++){
      $cur->{NAME}->[$i] =~ tr/[A-Z]/[a-z]/ ;
       ${$cur->{NAME}->[$i]} = $datas[$i] ;
     }
 }

#
# HTML
#
&html_header("COMPANY MASTER VIEW");
&title();
print ("COMPANY MASTER VIEW DETAIL");
 print "<P>" ;
 print "DETAIL : " ;
 print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>CODE</TD>" ;
 print "   <TD COLSPAN=3>$company_code<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>NAME</TD>" ;
 print "   <TD COLSPAN=3>$company<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>TYPE</TD>" ;
 print "   <TD COLSPAN=3>$type_description<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>ADDRESS</TD>" ;
 print "   <TD COLSPAN=3 >" ;
 print "     $address1<BR>" ;
 print "     $address2<BR>" ;
 print "     $address3<BR>" ;
 print "     $address4<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>TEL</TD>" ;
 print "   <TD WIDTH=100>$tel_no<BR></TD>" ;
 print "   <TD BGCOLOR=LightGreen>FAX</TD>" ;
 print "   <TD WIDTH=100>$fax_no<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>ATTN</TD>" ;
 print "   <TD>$attn<BR></TD>" ;
 print "   <TD BGCOLOR=LightGreen>ZIP CODE</TD>" ;
 print "   <TD>$zip_code<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>COUNTRY</TD>" ;
 print "   <TD  COLSPAN=3>$country<BR>" ;
 print "   </TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>EDI CODE</TD>" ;
 print "   <TD COLSPAN=3>$edi_code<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>SUPPLY TYPE</TD>" ;
 print "   <TD COLSPAN=3>$supply_type<BR></TD>" ;
 print " </TR>" ;
 print " <TR>" ;
 print "   <TD BGCOLOR=LightGreen>BONDED TYPE</TD>" ;
 print "   <TD COLSPAN=3>$type_description<BR></TD>" ;
 print " </TR>" ;
 print " </TABLE>" ;


 #
 # CONTRUCT
 #
  #テーブル参照
     $sql  = " select " ;
     $sql .= "  substr('00' || ct.contract_seq,-2,2) contract_seq," ;
     $sql .= "  ct.curr_code," ;
     $sql .= "  cu.curr_mark," ;
     $sql .= "  ct.pmethod," ;
     $sql .= "  decode(ct.pdays,0,'',ct.pdays) pdays," ;
     $sql .= "  ct.pdesc," ;
     $sql .= "  ct.pinit," ;
     $sql .= "  ct.tterm," ;
     $sql .= "  ct.tdesc," ;
     $sql .= "  ct.loading_port," ;
     $sql .= "  ct.discharge_port," ;
     $sql .= "  ct.final_dest," ;
     $sql .= "  replace(ct.marks,chr(10),'<BR>') marks," ;
     $sql .= "  ct.upto_date," ;
     $sql .= "  ct.reg_date " ;
     $sql .= " from contract ct," ;
     $sql .= "      currency cu " ;
     $sql .= " where ct.company_code = $company_code " ;
     $sql .= "   and ct.curr_code = cu.curr_code (+) " ;
     $sql .= " order by ct.contract_seq " ;
     $cur = $dbh->prepare($sql) || &err("Err $DBI::err .... $DBI::errstr") ;
     $cur->execute() || &err("Err $DBI::err .... $DBI::errstr") ;

 while((@datas) = $cur->fetchrow){
     for($i=0;$i<@datas;$i++){
      $cur->{NAME}->[$i] =~ tr/[A-Z]/[a-z]/ ;
       ${$cur->{NAME}->[$i]} = $datas[$i] ;
     }
 print "<P>" ;
 print "CONTRACT : <FONT COLOR=Blue>$contract_seq</FONT>" ;
 print "<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=0>" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>CURRENCY</TD>\n" ;
 print " <TD COLSPAN=3>$curr_mark<BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>PAYMENT</TD>\n" ;
 print " <TD COLSPAN=3>\n" ;
 print "  $pmethod\n" ;
 print "  $pdays\n" ;
 print "  $pdesc\n" ;
 print " <BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>TRADE TERMS</TD>\n" ;
 print " <TD COLSPAN=3>$tterm<BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>LOADING PORT</TD>\n" ;
 print " <TD WIDTH=100>\n" ;
 print "  $loading_port<BR>\n" ;
 print " </TD>\n" ;
 print " <TD BGCOLOR=LightGreen>DISCHARGE PORT</TD>\n" ;
 print " <TD WIDTH=100>\n" ;
 print "  $discharge_port<BR>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>FINAL DESTINATION</TD>\n" ;
 print " <TD COLSPAN=3>\n" ;
 print "  $final_dest<BR>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD VALIGN=Top  BGCOLOR=LightGreen>SHIPPING MARK</TD>\n" ;
 print " <TD COLSPAN=3><PRE>$marks</PRE><BR>\n" ;
 print " </TD>\n" ;
 print "</TR>\n" ;
 print "</TABLE>\n" ;
} 

# 2006/06/19 入力項目追加 
 print "<P>\n" ;
 print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0>" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>COMPANY SHORT NAME</TD>\n" ;
 print " <TD Width=100>$company_short<BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>TAXPAYER No.</TD>\n" ;
 print " <TD Width=100>$taxpayer_no<BR></TD>\n" ;
 print "</TR>\n" ;
# 2006/06/21 入力項目追加 
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>E MAIL.</TD>\n" ;
 print " <TD Width=100>$e_mail<BR></TD>\n" ;
 print "</TR>\n" ;
# 2014/06/14 入力項目追加 
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>ACCPAC COMPANY CODE</TD>\n" ;
 print " <TD>$accpac_company_code<BR></TD>\n" ;
 print "</TR>\n" ;
 print "<TR>\n" ;
 print " <TD BGCOLOR=LightGreen>ACCPAC PAYMENT</TD>\n" ;
 print " <TD COLSPAN=3 BGCOLOR=$BG_COM_PAY>\n" ;
 print "  $pdays_c\n" ;
 print "  $pdesc_c\n" ;
 print " <BR></TD>\n" ;
 print "</TR>\n" ;
 print "</TABLE>" ;
 print "<P>\n" ; 
print<<ENDOFTEXT;
[<A HREF=javascript:history.back()>BACK</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;