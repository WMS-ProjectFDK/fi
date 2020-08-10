# list_company2.pl
require '/pglosas/init.pl';

#
$word = $in{'word'} ;
$word =~ tr/[a-z]/[A-Z]/ ;
$stock_subject_code = $in{'stock_subject_code'} ;
$section_code = $in{'section_code'} ;
$class_1 = $in{'class_1'} ;
$class_2 = $in{'class_2'} ;
$class_3 = $in{'class_3'} ;



#
# db open
#
$dbh = &db_open();

#UNIT
$sql = " select unit_code,unit from unit " ;
 $cur = $dbh->prepare($sql) || &err("Err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("Err $DBI::err .... $DBI::errstr") ;
 while((@datas) = $cur->fetchrow){
       $UNIT{$datas[0]} = $datas[1] ;
 }
 $cur->finish() ;


#STOCK SUBJECT
 $sql  = " select  distinct" ;
 $sql .= "  stock_subject_code," ;
 $sql .= "  stock_subject" ;
 $sql .= " from stock_subject" ;
 $sql .= " where  stock_subject_code !=0" ;
 $sql .= " order by stock_subject_code " ;

 $cur = $dbh->prepare($sql) || &err("STK_SUBJ err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("STK_SUBJ err $DBI::err .... $DBI::errstr") ;
 while(@datas = $cur->fetchrow){
   $SBJ{$datas[0]} = $datas[1] ;
 }
 $cur->finish();


&html_header("ITEM Master VIEW");
&title();
print ("ITEM Master VIEW");
print "<FORM METHOD=Post NAME=myform ACTION=list_item2.cgi?$KEYWORD>\n" ;
print "<INPUT TYPE=Hidden NAME=word VALUE='$word'>\n" ;
print "<INPUT TYPE=Hidden NAME=stock_subject_code VALUE='$stock_subject_code'>\n" ;
print "<INPUT TYPE=Hidden NAME=class_1 VALUE='$class_1'>\n" ;
print "<INPUT TYPE=Hidden NAME=class_2 VALUE='$class_2'>\n" ;
print "<INPUT TYPE=Hidden NAME=class_3 VALUE='$class_3'>\n" ;
print "<INPUT TYPE=Hidden NAME=page VALUE='$page'>\n" ;



print<<ENDOFTEXT;
	<TABLE BORDER=1 CELLPADDING=1 CELLSPACING=0>
	<TR BGCOLOR="LIGHTGREEN">
	<TH>CODE</TH>
	<TH>NAME</TH>
	<TH>DESCRIPTION</TH>
	<TH>MAK</TH>
	<TH>ORIGIN</TH>
	<TH>CLASS</TH>
	<TH><BR></TH>
	</tr>
ENDOFTEXT

#
# check formed value and make sql
#
     $sql  = " select ";
     $sql .= "  i.stock_subject_code,";
     $sql .= "  i.item_no,";
     $sql .= "  i.item,";
     $sql .= "  i.description ,";
     $sql .= "  i.origin_code,";
     $sql .= "  i.mak,";
     $sql .= "  u.country,";
     $sql .= "  c.class_1 || ' ' ||  c.class_2 ||' ' ||  c.class_3 class,";
     $sql .= "  i.uom_q,";
     $sql .= "  i.uom_w,";
     $sql .= "  i.uom_l,";
     chop($sql);
     $sql .= " from class c,";
     $sql .= "	   item i,";
     $sql .= "	   country u,";
     chop($sql);
     $sql .= " where i.delete_type is null ";
     if($word ne ""){
     $sql .= " and  (    1=0 " ;
     $sql .= "        or upper(i.item) like '%$word%'"  if($word =~ /[^0-9]/);;
     $sql .= "        or upper(i.description) like '%$word%'"  if($word =~ /[^0-9]/);;
     $sql .= "        or i.item_no like '$word'"  if($word !~ /[^0-9]/);;
     $sql .= "      ) " ;
     }
     $sql .= " and   i.stock_subject_code = '$stock_subject_code' " if($stock_subject_code ne "");
     $sql .= " and   i.section_code = '$section_code' " if($section_code ne "");
     $sql .= " and   i.origin_code = u.country_code (+) ";
     $sql .= " and   i.class_code = c.class_code (+) ";
     $sql .= " and   c.class_1 = '$class_1' " if($class_1 ne "");
     $sql .= " and   c.class_2 = '$class_2' " if($class_2 ne "");
     $sql .= " and   c.class_3 = '$class_3' " if($class_3 ne "");
     $sql .= " order by i.stock_subject_code,i.item,i.description,i.item_no " ;
 $cur = $dbh->prepare($sql) || &err("Err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("Err $DBI::err .... $DBI::errstr") ;

 while((@datas) = $cur->fetchrow){
     for($i=0;$i<@datas;$i++){
       ${$cur->{NAME}->[$i]} = $datas[$i] ;
     }

    if($STOCK_SUBJECT_CODE ne $STOCK_SUBJECT_CODE_OLD){
       print "<TR BGCOLOR=DarkBlue>" ;
       print "<TD COLSPAN=7><FONT COLOR=White>$SBJ{$STOCK_SUBJECT_CODE}</FOINT></TD>" ;
       print "</TR>" ;
    }
print <<ENDOFTEXT;
        	<tr BGCOLOR=White>
        	<td ALIGN=Right>$ITEM_NO</td>
        	<td>$ITEM</td>
        	<td>$DESCRIPTION<BR></td>
        	<td>$MAK<BR></td>
        	<td>$COUNTRY</td>
        	<td>$CLASS</td>
        	<td><A HREF=list_item3.cgi?KEYWORD=$KEYWORD&item_no=$ITEM_NO>DETAIL</A></td>
        	</tr>
ENDOFTEXT

    $STOCK_SUBJECT_CODE_OLD = $STOCK_SUBJECT_CODE ;
 }



print "</table>\n";

print<<ENDOFTEXT;
</FORM>
<HR>
[<A HREF=javascript:history.back()>BACK</A>]
</BODY>
</HTML>
ENDOFTEXT

exit ;