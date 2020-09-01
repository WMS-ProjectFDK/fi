#
# Programed by T.Ohsugi 
# 2000/08/04 

#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# DB OPEN
#-------------------------
 $dbh = &db_open() ;


#-------------------------
# MAIN
#-------------------------
 # HTML HEADER
  &html_header("BUYING PRICE MASTER MAINTENANCE");  
  &title() ;

  &SEARCH()  ;

  &html_fooder("../../../main.asp?KEYWORD=$KEYWORD&menuType=master","BACK") ;

exit ;


#-------------------------
# SUBROUTINE 
#-------------------------
#
# SEARCH
#
sub SEARCH{
  # 
  #テーブル参照
  # 
     $sql  = " select distinct " ;
     $sql .= "  c.company_code," ;
     $sql .= "  c.company " ;
     $sql .= " from  company c where company_type in (2,3) " ;
     $sql .= " order by c.company " ;

   #print $sql ;
 
    $cur = $dbh->prepare($sql) || &err("SP_REF err $DBI::err .... $DBI::errstr") ;
    $cur->execute() || &err("SP_REF err $DBI::err .... $DBI::errstr") ;
    $COM_NUM =0 ;
    while(@datas = $cur->fetchrow){
      $COM[$COM_NUM][0] = $datas[0] ;
      $COM[$COM_NUM][1] = $datas[1] ;
       $COM_NUM ++ ;
    }
    $cur->finish();

  # 
  #テーブル参照
  # 
     $sql  = " select distinct " ;
     $sql .= "  section_code," ;
     $sql .= "  section " ;
     $sql .= " from section " ;
     $sql .= " order by section " ;

   #print $sql ;
    $cur = $dbh->prepare($sql) || &err("SEC err $DBI::err .... $DBI::errstr") ;
    $cur->execute() || &err("SEC err $DBI::err .... $DBI::errstr") ;
    $SEC_NUM =0 ;
    while(@datas = $cur->fetchrow){
      $SEC[$SEC_NUM][0] = $datas[0] ;
      $SEC[$SEC_NUM][1] = $datas[1] ;
       $SEC_NUM ++ ;
    }
    $cur->finish();

 #
 # HTML
 #
 print "$TITLE <P>\n" ;
 print <<ENDOFTEXT ;
    <SCRIPT LANGUAGE=javascript>
     function chg_supplier(theForm){
          theForm.supplier_code.value = theForm.supplier.options[theForm.supplier.selectedIndex].value ;
     }
     function chg_supplier_code(theForm){
          num =0 ;
          for(i=0;i<theForm.supplier.options.length;i++){
             if(theForm.supplier.options[i].value == theForm.supplier_code.value){
                   num = i ;
             }
          }
          if(num==0 && theForm.supplier_code.value != ""){
             alert("this supplier_code " + theForm.supplier_code.value + " is not found.") ;
             theForm.supplier.options.selectedIndex = 0 ;
             return false ;
          }else{
             theForm.supplier.options.selectedIndex = num ;
             return true ;
          }
     }
     function chkCustomer(theForm){
          if(theForm.supplier.selectedIndex ==0){
             alert("Please select SUPPLIER!") ;
             return false ;
          }else{
             return true ;
          }
     }
   </SCRIPT>
ENDOFTEXT
  print "<FORM METHOD=Post NAME=myform ACTION=download2.cgi?$KEYWORD>\n" ;
  print "<FONT SIZE=+1 COLOR=Blue>BUYING PRICE MASTER DOWNLOAD</FONT><BR>\n" ;
  print "SUPPLIER:" ;
  print " <INPUT TYPE=TEXT NAME=supplier_code SIZE=9 MAXLENGTH=8 VALUE='$supplier_code' onChange='return chg_supplier_code(this.form)'>\n" ;
  print " <SELECT NAME=supplier onChange='return chg_supplier(this.form)'>\n" ;
  print "  <OPTION VALUE=''>\n" ;
    for($i=0;$i<$COM_NUM;$i++){
       if($customer_code == $COM[$i][0]){ $SEL = "SELECTED" ;}else{ $SEL = "" ;}
       print "<OPTION VALUE='$COM[$i][0]' $SEL>$COM[$i][1]\n" ;
    }
  print " </SELECT><BR>\n" ;
  print "SECTION:" ;
  print " <SELECT NAME=section_code>\n" ;
  print "  <OPTION VALUE=''>\n" ;
    for($i=0;$i<$SEC_NUM;$i++){
       if($section_code == $SEC[$i][0]){ $SEL = "SELECTED" ;}else{ $SEL = "" ;}
       print "<OPTION VALUE='$SEC[$i][0]' $SEL>$SEC[$i][1]\n" ;
    }
  print " </SELECT><BR>\n" ;

  print "<INPUT TYPE=Submit VALUE='DOWNLOAD' onClick='return chkCustomer(this.form)'>\n" ;
  print "<P>\n" ;
  print "<HR NOSHADE>\n" ;
  print "</FORM>\n" ;
}

