#!/usr/local/bin/perl
require '/pglosas/init.pl';
#require 'init.pl';
require '/pglosas/master/company/company_desc.pl';
#require 'c:/inetpub/wwwroot/master/company/company_desc.pl' ;
&make_desc;

&html_header("Company Maintenance");
&title;

$dbh = &db_open();

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
# country
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
$sql = "select curr_code,curr_mark from currency";

&do_sql($sql) ;

undef @currency;
while ((@datas) = $cur->fetchrow){
	push @currency,[ $datas[0], $datas[1] ];
}
$cur->finish;

print<<ENDOFTEXT;
<H2>Company Maintenance:Input Form</H2>
<HR>
<FORM METHOD=Post ACTION=m_company4.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "COMPANY_TYPE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      foreach $type (@company_types){
         print " <option value=$$type[0]>$$type[0] $$type[1]\n";
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "COUNTRY_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      foreach $type (@country){
         print " <option value=$$type[0]";
         print " selected" if ($$type[0] eq "112");
         print ">$$type[0] $$type[1]\n";
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CURR_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      foreach $type (@currency){
         print " <option value=$$type[0]>$$type[0] $$type[1]\n";
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "PDAYS"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">PAYMENT</th>\n";
   	  print " <td>";
      print " 	<input type=text name=$$key[0] size=$$key[2]>";
   } elsif ($$key[0] eq "PDESC"){
      print " 	<input type=text name=$$key[0] size=$$key[2]>";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
   	  print "<input type=hidden name=$$key[0] value='sysdate'>";
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "DELETE_TYPE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "MARKS") {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " <textarea cols=50 rows=6 name=$$key[0]>";
      print " </textarea>\n";
      print "</td>\n";
      print "</tr>\n";
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " <input type=text name=$$key[0] size=$$key[2]>";
      print "</td>\n";
      print "</tr>\n";
   }
}
print<<ENDOFTEXT;
</tr>
</table>
<P>
<input type=submit name=submit value="Next">
<input type=reset name=submit value="Reset">
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;