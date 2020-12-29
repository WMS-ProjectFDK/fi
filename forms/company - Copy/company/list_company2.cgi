# list_company2.pl
require '/pglosas/init.pl';

#
$word = $in{'word'} ;
$word =~ tr/[a-z]/[A-Z]/ ;
$company_type = $in{'company_type'} ;


#
# db open
#
$dbh = &db_open();

#COMPANY TYPE
 $sql  = " select  distinct" ;
 $sql .= "  company_type," ;
 $sql .= "  type_description " ;
 $sql .= " from company_type" ;
 $sql .= " order by company_type " ;

 $cur = $dbh->prepare($sql) || &err("STK_SUBJ err $DBI::err .... $DBI::errstr") ;
 $cur->execute() || &err("STK_SUBJ err $DBI::err .... $DBI::errstr") ;
 while(@datas = $cur->fetchrow){
   $TYP{$datas[0]} = $datas[1] ;
 }
 $cur->finish();


&html_header("COMPANY MASTER VIEW");
&title();
print ("COMPANY MASTER VIEW");
print "<FORM METHOD=Post NAME=myform ACTION=list_item2.cgi?$KEYWORD>\n" ;
print "<INPUT TYPE=Hidden NAME=word VALUE='$word'>\n" ;
print "<INPUT TYPE=Hidden NAME=company_type VALUE='$company_type'>\n" ;
print "<INPUT TYPE=Hidden NAME=page VALUE='$page'>\n" ;



print<<ENDOFTEXT;
	<TABLE BORDER=1 CELLPADDING=1 CELLSPACING=0>
	<TR BGCOLOR="LIGHTGREEN">
	<TH>CODE</TH>
	<TH>NAME</TH>
	<TH>COUNTRY</TH>
	<TH><BR></TH>
	</tr>
ENDOFTEXT

#
# check formed value and make sql
#
     $sql  = " select ";
     $sql .= "  c.company_type,";
     $sql .= "  c.company_code,";
     $sql .= "  c.company,";
     $sql .= "  c.country_code,";
     $sql .= "  u.country,";
     chop($sql);
     $sql .= " from company c,";
     $sql .= "	   country u,";
     chop($sql);
     $sql .= " where c.delete_type is null ";
     $sql .= " and  (    1=0 " ;
     $sql .= "        or upper(c.company) like '%$word%'" ;
     $sql .= "        or c.company_code like '$word'"  if($word !~ /[^0-9]/);;
     $sql .= "      ) " ;
     $sql .= " and   c.company_type = '$company_type' " if($company_type ne "");
     $sql .= " and   c.country_code = u.country_code (+) ";
     $sql .= " order by c.company_type,c.company " ;
     $cur = $dbh->prepare($sql) || &err("Err $DBI::err .... $DBI::errstr") ;
     $cur->execute() || &err("Err $DBI::err .... $DBI::errstr") ;

 while((@datas) = $cur->fetchrow){
     for($i=0;$i<@datas;$i++){
       ${$cur->{NAME}->[$i]} = $datas[$i] ;
     }

    if($COMPANY_TYPE ne $COMPANY_TYPE_OLD){
       print "<TR BGCOLOR=DarkBlue>" ;
       print "<TD COLSPAN=6><FONT COLOR=White>$TYP{$COMPANY_TYPE}</FOINT></TD>" ;
       print "</TR>" ;
    }
print <<ENDOFTEXT;
        	<tr BGCOLOR=White>
        	<td ALIGN=Right>$COMPANY_CODE</td>
        	<td>$COMPANY</td>
        	<td>$COUNTRY</td>
        	<td><A HREF=list_company3.cgi?KEYWORD=$KEYWORD&company_code=$COMPANY_CODE>DETAIL</A></td>
        	</tr>
ENDOFTEXT

    $COMPANY_TYPE_OLD = $COMPANY_TYPE ;
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