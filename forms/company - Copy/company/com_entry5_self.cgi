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
foreach(keys (%in)){
  $$_ = $in{$_} ;
  $$_ =~ s/\r//g ;
  $$_ =~ s/'/''/g ;
}

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

 if($company_code eq ""){ &err(" COMPANY CODE is ERROR")} ;

if($TYPES eq "NEW"){
   &COM_NEW()
# &CNT_NEW()
}elsif($TYPES eq "CHANGE"){
   &COM_CHG()
}elsif($TYPES eq "DELETE"){
   &COM_DEL()
}elsif($TYPES eq "RESTORE"){
   &COM_RES()
}

#-------------------------
# COMMIT or ROLLBACK
#-------------------------
 $dbh->commit ;
 #$dbh->rollback ;

#-------------------------
# HTML
#-------------------------
 &html_header("COMPANY MAINTENANCE");
 &title();
 print "COMPANY MAINTENANCE</H2>" ;
 #print "<HR>" ;
 print " $TYPES is successful." ; 
 print "<P>" ;
&html_fooder("com_entry1.cgi?$KEYWORD","BACK") ;

exit ;

#-------------------------
# SUBROUTINE
#-------------------------
#--- COMPANY START ----------
 sub COM_NEW{
   # INSERT COMPANY
    $field  = "upto_date      ," ; $value  = "sysdate           ," ;
    $field .= "reg_date       ," ; $value .= "sysdate           ," ;
    $field .= "delete_type    ," ; $value .= "null              ," ;
    $field .= "company_code   ," ; $value .= "'$company_code'   ," ;
    $field .= "company_type   ," ; $value .= "0," ;
    $field .= "company        ," ; $value .= "'$company_e'        ," ;
    $field .= "address1       ," ; $value .= "'$address_e1'       ," ;
    $field .= "address2       ," ; $value .= "'$address_e2'       ," ;
    $field .= "address3       ," ; $value .= "'$address_e3'       ," ;
    $field .= "address4       ," ; $value .= "'$address_e4'       ," ;
 #  $field .= "attn           ," ; $value .= "'$attn'           ," ;
    $field .= "tel_no         ," ; $value .= "'$tel_no_e'         ," ;
    $field .= "fax_no         ," ; $value .= "'$fax_no_e'         ," ;
    $field .= "zip_code       ," ; $value .= "'$zip_code'       ," ;
    $field .= "country_code   ," ; $value .= "'$country_code'   ," ;
    $field .= "curr_code      ," ; $value .= "'$curr_code'      ," ;
 #  $field .= "tterm          ," ; $value .= "'$tterm'          ," ;
 #   $field .= "pdays          ," ; $value .= "'$pdays'          ," ;
 #   $field .= "pdesc          ," ; $value .= "'$pdesc'          ," ;
 #   $field .= "case_mark      ," ; $value .= "'$case_mark'      ," ;
 #   $field .= "edi_code       ," ; $value .= "'$edi_code'       ," ;
 #   $field .= "vat            ," ; $value .= "'$vat'            ," ;
 #   $field .= "supply_type    ," ; $value .= "'$supply_type'    ," ;
 #   $field .= "subc_code      ," ; $value .= "'$subc_code'      ," ;
 #   $field .= "transport_days ," ; $value .= "'$transport_days' ," ;
    $field .= "company_short  ," ; $value .= "'$company_short'  ," ;
    $field .= "taxpayer_no    ," ; $value .= "'$taxpayer_no'    ," ;
# 2006/06/21 入力項目追加 
    $field .= "e_mail         ," ; $value .= "'$e_mail'         ," ;
     chop($field) ;                 chop($value) ;

     $sql  = " insert into company ($field) values ($value) " ;
     #print $sql . "<P>";
     $cur = $dbh->prepare($sql) || &err("HEADER err $DBI::err .... $DBI::errstr") ;
     $cur->execute() || &err("HEADER err $DBI::err .... $DBI::errstr") ;
     
     
    #日本語のデータをcompany2に挿入
    
    # INSERT COMPANY2
    
#    $field2  = "company        ," ; $value2  = "'$company_j'        ," ;
#    $field2 .= "company_code   ," ; $value2 .= "'$company_code'     ," ;
#    $field2 .= "address1       ," ; $value2 .= "'$address_j1'       ," ;
#    $field2 .= "address2       ," ; $value2 .= "'$address_j2'       ," ;
#    $field2 .= "address3       ," ; $value2 .= "'$address_j3'       ," ;
#    $field2 .= "address4       ," ; $value2 .= "'$address_j4'       ," ;
#    $field2 .= "tel_no         ," ; $value2 .= "'$tel_no_j'         ," ;
#    $field2 .= "fax_no         ," ; $value2 .= "'$fax_no_j'         ," ;
#    chop($field2) ;                  chop($value2) ;
    
 #    $sql2  = " insert into company2 ($field2) values ($value2) " ;
     #print $sql2 . "<P>";
 #    $cur = $dbh->prepare($sql2) || &err("HEADER err $DBI::err .... $DBI::errstr") ;
 #    $cur->execute() || &err("HEADER err $DBI::err .... $DBI::errstr") ;
    
    
    
    
    
    
 }
 sub COM_CHG{
    # UPDATE COMPANY
    $sql  = "update company set " ;
    $sql .= " upto_date      = sysdate           ," ;
    $sql .= " delete_type    = null              ," ;
    
    $sql .= " company        = '$company_e'        ," ;
    $sql .= " address1       = '$address_e1'       ," ;
    $sql .= " address2       = '$address_e2'       ," ;
    $sql .= " address3       = '$address_e3'       ," ;
    $sql .= " address4       = '$address_e4'       ," ;
 #  $sql .= " attn           = '$attn'           ," ;
    $sql .= " tel_no         = '$tel_no_e'         ," ;
    $sql .= " fax_no         = '$fax_no_e'         ," ;
    $sql .= " zip_code       = '$zip_code'       ," ;
    $sql .= " country_code   = '$country_code'   ," ;
    $sql .= " curr_code      = '$curr_code'      ," ;
#    $sql .= " tterm          = '$tterm'          ," ;
#    $sql .= " pdays          = '$pdays'          ," ;
#    $sql .= " pdesc          = '$pdesc'          ," ;
#    $sql .= " case_mark      = '$case_mark'      ," ;
#    $sql .= " edi_code       = '$edi_code'       ," ;
#    $sql .= " vat            = '$vat'            ," ;
#    $sql .= " supply_type    = '$supply_type'    ," ;
#    $sql .= " subc_code      = '$subc_code'      ," ;
#    $sql .= " transport_days = '$transport_days' ," ;
    $sql .= " company_short  = '$company_short'  ," ;
    $sql .= " taxpayer_no    = '$taxpayer_no'    ," ;
# 2006/06/21 入力項目追加 
    $sql .= " e_mail         = '$e_mail'    ," ;
    chop($sql) ;

    $sql .= " where company_code = '$company_code'" ;
     #print $sql . "<P>";
     $cur = $dbh->prepare($sql) || &err("HEADER err $DBI::err .... $DBI::errstr") ;
     $cur->execute() || &err("HEADER err $DBI::err .... $DBI::errstr") ;

	#UPDATE COMPANY2

    $sql2  = "update company2 set " ;
    $sql2 .= " company       = '$company_j'," ;
    $sql2 .= " address1      = '$address_j1'," ;
    $sql2 .= " address2      = '$address_j2'," ;
    $sql2 .= " address3      = '$address_j3'," ;
    $sql2 .= " address4      = '$address_j4'," ;
    $sql2 .= " tel_no        = '$tel_no_j'," ;
    $sql2 .= " fax_no        = '$fax_no_j'," ;

      chop($sql2) ;

    $sql2 .= " where company_code = '$company_code'" ;
     #print $sql2 . "<P>";
#     $cur = $dbh->prepare($sql2) || &err("HEADER err $DBI::err .... $DBI::errstr") ;
#     $cur->execute() || &err("HEADER err $DBI::err .... $DBI::errstr") ;
    
    
 }
 sub COM_DEL{
    #DELETE COMPANY
     $sql = "update company set delete_type ='D' where company_code='$company_code' " ;


     #print $sql . "<P>";
    $cur = $dbh->prepare($sql) || &err("HEADER err $DBI::err .... $DBI::errstr") ;
     $cur->execute() || &err("HEADER err $DBI::err .... $DBI::errstr") ;
 }
 sub COM_RES{
    #RESTORE COMPANY
     $sql = "update company set delete_type = null where company_code='$company_code' " ;

     #print $sql . "<P>";
#     $cur = $dbh->prepare($sql) || &err("HEADER err $DBI::err .... $DBI::errstr") ;
#     $cur->execute() || &err("HEADER err $DBI::err .... $DBI::errstr") ;
 }
#--- COMPANY END ----------


#--- CONTRACT START ----------
#sub CNT_NEW{
#   $sql = "lock table contract in exclusive mode" ;
#  $cur = $dbh->prepare($sql) || &err("NEW err $DBI::err .... $DBI::errstr") ;
#   $cur->execute() || &err("NEW err $DBI::err .... $DBI::errstr") ;
#

#  $sql  = "insert into contract("   ;
#  $sql .= "  company_code,"  ;
#   $sql .= "  contract_seq,"  ;
#   $sql .= "  curr_code,"     ;
#   $sql .= "  pmethod,"       ;
#   $sql .= "  pdays,"         ;
#   $sql .= "  pdesc,"         ;
#   $sql .= "  tterm,"         ;
#   $sql .= "  tdesc,"         ;
#   $sql .= "  loading_port,"  ;
#   $sql .= "  discharge_port,";
#   $sql .= "  notify_code,"   ;
#   $sql .= "  consignee_code,"   ;
#   $sql .= "  marks,"         ;
#   $sql .= "  port_loading_code,"         ;
#   $sql .= "  port_discharge_code,"         ;
#   $sql .= "  final_destination_code,"         ;
#   $sql .= "  upto_date,"     ;
#   $sql .= "  reg_date "      ;
#   $sql .= " ) values ( "        ;
#   $sql .= "  '$company_code',"  ;
#   $sql .= "   1,"  ;
#   $sql .= "  '$curr_code',"     ;
#   $sql .= "  '$pmethod',"       ;
#   $sql .= "  '$pdays',"         ;
#   $sql .= "  '$pdesc',"         ;
#   $sql .= "  '$tterm',"         ;
#   $sql .= "  '$tdesc',"         ;
#   $sql .= "  '$loading_port',"  ;
#   $sql .= "  '$discharge_port',";
#   $sql .= "  '$notify_code',"   ;
#   $sql .= "  '$consignee_code',";
#   $sql .= "  '$marks',"         ;
#   $sql .= "  '$port_loading_code',"         ;
#   $sql .= "  '$port_discharge_code',"         ;
#   $sql .= "  '$final_destination_code',"         ;
#   $sql .= "  sysdate,"          ;
#   $sql .= "  sysdate "          ;
#   $sql .= " ) " ;

   #print "NEW: " . $sql ."<P>";

#   $cur = $dbh->prepare($sql) || &err("NEW err $DBI::err .... $DBI::errstr") ;
#   $cur->execute() || &err("NEW err $DBI::err .... $DBI::errstr") ;
  
#}
