#!/usr/local/bin/perl
require '/pglosas/init.pl';
#require 'init.pl';
require '/pglosas/master/company/company_desc.pl';
#require 'c:/inetpub/wwwroot/master/company/company_desc.pl' ;
&make_desc;

&html_header("Company Maintenance:Registration");
&title;

$dbh = &db_open();

$str1_company = ""; $str2_company = "";
$str1_contract = ""; $str2_contract = "";

foreach $key (@desc){
   if ($in{$$key[0]} ne ""){
     if ($$key[4] eq "COMPANY"){
      $str1_company .=     $$key[0]  . "," ;
      if ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
      		$str2_company .= "sysdate," ;
      } elsif($$key[0] eq "MARKS") {
      		$str2_company .= "'$in{$$key[0]}'," ;
      } else {
         if ($$key[1] eq "X"){
      		$str2_company .= "'" . $in{$$key[0]} . "'," ;
      	 } else {
      		$str2_company .=       $in{$$key[0]} . "," ;
      	 }
      }
     } elsif ($$key[4] eq "CONTRACT"){
      $str1_contract .=     $$key[0]  . "," ;
      if ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
      		$str2_contract .= "sysdate," ;
      } elsif($$key[0] eq "MARKS") {
      		$str2_contract .= "'$in{$$key[0]}'," ;
      } else {
         if ($$key[1] eq "X"){
      		$str2_contract .= "'" . $in{$$key[0]} . "'," ;
      	 } else {
      		$str2_contract .=       $in{$$key[0]} . "," ;
      	 }
      }
     } elsif ($$key[4] eq ""){
      $str1_company .=     $$key[0]  . "," ;
      $str1_contract .=     $$key[0]  . "," ;
      if ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
      		$str2_company .= "sysdate," ;
      		$str2_contract .= "sysdate," ;
      } elsif($$key[0] eq "MARKS") {
      		$str2_company .= "'$in{$$key[0]}'," ;
      		$str2_contract .= "'$in{$$key[0]}'," ;
      } else {
         if ($$key[1] eq "X"){
      		$str2_company .= "'" . $in{$$key[0]} . "'," ;
      		$str2_contract .= "'" . $in{$$key[0]} . "'," ;
      	 } else {
      		$str2_company .=       $in{$$key[0]} . "," ;
      		$str2_contract .=       $in{$$key[0]} . "," ;
      	 }
      }
     }
   }
}
#
# contract sequence
#
$str1_contract .= "CONTRACT_SEQ,";
$str2_contract .= "1,";

$sql  = "insert into company(";
$sql .= $str1_company ;
chop($sql);
$sql .= ") values(";
$sql .= $str2_company ;
chop($sql);
$sql .= ")";

&do_sql($sql) ;
print $sql ;

$sql  = "insert into contract(";
$sql .= $str1_contract ;
chop($sql);
$sql .= ") values(";
$sql .= $str2_contract ;
chop($sql);
$sql .= ")";

&do_sql($sql) ;
print $sql ;

$dbh->commit;

#
# company type
#
$sql = "select type,description from company_type";

&do_sql($sql) ;

undef @company_types;
while ((@datas) = $cur->fetchrow){
	push @company_types,[ $datas[0], $datas[1] ];
}
$cur->finish;

#
# company type
#
$sql = "select country_code,country from country";

&do_sql($sql) ;

undef @country;
while ((@datas) = $cur->fetchrow){
	push @country,[ $datas[0], $datas[1] ];
}
$cur->finish;

#
# company type
#
$sql = "select curr_mark from currency";

&do_sql($sql) ;

undef @currency;
while ((@datas) = $cur->fetchrow){
	push @currency,[ $datas[0], $datas[1] ];
}
$cur->finish;

print<<ENDOFTEXT;
<H2>Company Maintenance:Registeration</H2>
<HR>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "COMPANY_TYPE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@company_types){
         print " $$type[0] $$type[1]\n" if ($in{$$key[0]} eq $$type[0]);
      }
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "COUNTRY_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@country){
         print " $$type[0] $$type[1]\n" if ($in{$$key[0]} eq $$type[0]);
      }
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CURR_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@currency){
         print " $$type[0] $$type[1]\n" if ($in{$$key[0]} eq $$type[0]);;
      }
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "PDAYS"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">PAYMENT</th>\n";
   	  print " <td>$in{$$key[0]}";
      print " 	<input type=hidden name=$$key[0] value=$in{$$key[0]}>";
   } elsif ($$key[0] eq "PDESC"){
      print " 	$in{$$key[0]}<input type=hidden name=$$key[0] value=$in{$$key[0]}>";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "DELETE_TYPE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "MARKS") {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " <textarea name=$$key[0] cols=50 rows=6>$in{$$key[0]}</textarea>";
      print "</td>\n";
      print "</tr>\n";
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " $in{$$key[0]}<BR><input type=hidden name=$$key[0] value=$in{$$key[0]}>";
      print "</td>\n";
      print "</tr>\n";
   }
}
print<<ENDOFTEXT;
</table>
<P>
Registered successfully.
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;