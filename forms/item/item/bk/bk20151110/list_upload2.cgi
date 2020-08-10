#
# Programed by Y.Hagai
# 2010/02/11

#-------------------------
# Require init file
#-------------------------
$ReadParse ="False" ;
require '/pglosas/init.pl' ;

#-------------------------
# Spool the files to the directory
#-------------------------
  $cgi_lib::writefiles = "/pglosas/master/item/tmp";

#-------------------------
# ReadParse
#-------------------------
  $ret = &ReadParse(\%cgi_data,\%cgi_cfn,\%cgi_ct,\%cgi_sfn);

#-------------------------
# POST
#-------------------------
  $upfile = $cgi_sfn{'upfile'} ;
  $client_file_name = (reverse split(/\\/,$cgi_cfn{'upfile'}))[0] ;

  if(-z $upfile || $client_file_name eq ""){
     &err("UPLOADING FILE IS NOT FOUND.") ;
  }

#-------------------------
# DB OPEN
#-------------------------
 $dbh = &db_open() ;
 
#-------------------------
# SQL
#-------------------------

#WK削除
   $sql  = " delete from  list_item_up_wk where ipaddress = '$ENV{'REMOTE_ADDR'}'" ;
   $cur = $dbh->prepare($sql) || &err("LIST_ITEM_UP_DEL err $DBI::err .... $DBI::errstr") ;
   $cur->execute() || &err("IST_ITEM_UP_DEL err $DBI::err .... $DBI::errstr") ;



 
 #データアップロード
  $sql  = "insert into list_item_up_wk ( " ;
  $sql .= " IPADDRESS           ," ; $value .= "  '$ENV{'REMOTE_ADDR'}'  ," ;
  $sql .= " UPLOAD_DATE         ," ; $value .= "  sysdate  ," ;
  $sql .= " ITEM_NO             ," ; $value .= "  ?        ," ;
    chop($sql)  ;                     chop($value)  ; 
  $sql .= " ) values ($value) " ;
  $cur = $dbh->prepare($sql) || &err("LIST_ITEM_UP err $DBI::err .... $DBI::errstr") ;
  open("UPFILE","$upfile") ;
     #1行目タイトル行
      $_ = <UPFILE> ;
      chop($_) ;
      @FIELD = (
                   ITEM_NO
                ) ;



      while(<UPFILE>){
         chop ($_) ;
# " "で囲まれた項目の , は .に置き換え
         $serch_on = "OFF" ;
         for($i=0;$i<length($_)-1;$i++){
             $serch_char = substr $_,$i,1 ;
             if ($serch_char eq "\"") {
                if ($serch_on eq "OFF"){
                    $serch_on = "ON" ;
                }else{
                    $serch_on = "OFF" ;
                }
             }
             if ($serch_char eq ",") {
                if ($serch_on eq "ON"){
                    substr ($_,$i,1) = "." ;

                }
             }

         }

         @datas = split(/\,/,$_) ;
         for($i=0;$i<@FIELD;$i++){
             ${$FIELD[$i]} = $datas[$i] ;
         }

          $cur->execute(
                        $ITEM_NO
                       ) || &err("LIST_ITEM_UP2 err $DBI::err .... $DBI::errstr <p>  ITEM NO   :$ITEM_NO<BR><font color=RED><BR> Please check UPLOAD FILE's columns</FONT>") ;

     }
  close(UPFILE) ;



#-------------------------
# MAIN
#-------------------------
  &html_header()
  &title("LIST ITEM DATA UPLOAD") ;

  print "<FORM METHOD=Post NAME=myform ACTION='list_upload_item2.cgi?$KEYWORD'>\n" ;
  print "<FONT COLOR=Blue>CONTENTS</FONT><BR>";

  print "<FONT COLOR=RED>$ERROR_MSG</FONT><BR>\n" ;
 #UPLOAD FILE内容チェック
   $sql  = " select  " ;
   $sql .= "  l.UPLOAD_DATE         ," ;
   $sql .= "  l.ITEM_NO             ," ;
   $sql .= "  i.ITEM_NO         I_ITEM_NO," ;
   $sql .= "  i.ITEM                ," ;
   $sql .= "  i.DESCRIPTION         " ;
   $sql .= " from list_item_up_wk  l," ;
   $sql .= "      item             i" ;
   $sql .= " where l.item_no = i.item_no (+) " ;
   $sql .= "   and l.ipaddress = '$ENV{'REMOTE_ADDR'}' " ;
   $sql .= " order by item,description " ;
   $cur = $dbh->prepare($sql) || &err("LIST_ITEM_UP err $DBI::err .... $DBI::errstr") ;
   $cur->execute() || &err("LIST_ITEM_UP err $DBI::err .... $DBI::errstr") ;

      #print $sql."<p>" ;

   $num = 0 ;
   while(@datas = $cur->fetchrow()){
      for($i=0;$i<@datas;$i++){
          ${$cur->{NAME}->[$i]} = $datas[$i] ;
      }

     
     if($num == 0){
         print "<TABLE BORDER=1 CELLPADDING=0 CELLSPACING=0>\n" ;
         print "<TR BGCOLOR=#DDDDDD>" ;
         print "<TD>ITEM NO<BR></TD>" ;
         print "<TD>ITEM<BR></TD>" ;
         print "<TD>DESCRIPTION<BR></TD>" ;
         print "</TR>" ;
      }

 #     if($STATION_CODE ne $STATION_CODE_OLD){
 #        print "<TR BGCOLOR=DarkBlue>" ;
 #        print "<TD COLSPAN=31><FONT COLOR=White>[$STATION_CODE] $STATION</FONT><BR></TD>" ;
 #        print "</TR>" ;
 #        $SECTION_CODE_OLD = "" ;
 #     }
      
#      $BGC="WHITE" ;
#      if($ITEM eq ""){ $BGC='Pink' ; $alert_msg = "1" ; }
      
      print "<TR BGCOLOR='WHITE'>" ;
#ITEMチェック
      if($I_ITEM_NO eq ""){
         print "<TD BGCOLOR='Pink'>$ITEM_NO<BR></TD>" ;
         $alert_msg = "1" ;
      }else{
         print "<TD>$ITEM_NO<BR></TD>" ;
      }
      print "<TD>$ITEM<BR></TD>" ;
      print "<TD>$DESCRIPTION<BR></TD>" ;
      print "</TR>" ;

      $num ++ ;
   }
   print "</TABLE>\n" ;
   print "<TABLE><TR><TD WIDTH=30 BGCOLOR=Pink><BR></TD><TD> THIS COLUMNS ARE ERROR.</TD></TR></TABLE>" if($alert_msg ne "");
   print "<P>" ;
   print "<INPUT TYPE=Hidden NAME=ipaddress VALUE='$ENV{'REMOTE_ADDR'}'> " ;

   if($alert_msg == ""){
     print "<INPUT TYPE=Submit VALUE='GO!'> " ;
   }
   print "<INPUT TYPE=Button VALUE='BACK' onClick='history.back()'> " ;

   
   print "</FORM>" ;
   print "</BODY></HTML>" ;


#-------------------------
# COMMIT or ROLLBACK
#-------------------------

if($ERROR_FLG == 1){
   $dbh ->rollback ;
}else{
   $dbh ->commit ;
}
    

#-------------------------
# UNLINK
#-------------------------
       unlink($upfile) ;

#-------------------------
# UPFILE CHECK
#-------------------------
&html_fooder("../item/list_upload1.cgi?$KEYWORD","BACK") ;

