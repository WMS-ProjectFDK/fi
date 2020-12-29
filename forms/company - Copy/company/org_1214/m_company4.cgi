#!/usr/local/bin/perl
require '/pglosas/init.pl';
#require 'init.pl';
require '/pglosas/master/company/company_desc.pl';
#require 'c:/inetpub/wwwroot/master/company/company_desc.pl' ;
&make_desc;

&html_header("Company Maintenance:Confirmation");
&title;

$dbh = &db_open();

#
# company type
#
$sql = "select type,description from company_type";

&do_sql($sql);

undef @company_types;
while ((@datas) = $cur->fetchrow){
	push @company_types,[ $datas[0], $datas[1] ];
}
$cur->finish;

#
# company type
#
$sql = "select country_code,country from country";

&do_sql($sql);

undef @country;
while ((@datas) = $cur->fetchrow){
	push @country,[ $datas[0], $datas[1] ];
}
$cur->finish;

#
# company type
#
$sql = "select curr_code,curr_mark from currency";

&do_sql($sql);

undef @currency;
while ((@datas) = $cur->fetchrow){
	push @currency,[ $datas[0], $datas[1] ];
}
$cur->finish;

#
# 重複チェック
#
$sql  = "select company_code,";
$sql .= "       company,";
chop($sql);
$sql .= " from company ";
$sql .= " where company_code = $in{'COMPANY_CODE'}";

&do_sql($sql);

($a_company_code,$a_company) = $cur->fetchrow;

if ($a_company_code ne ""){
print<<ENDOFTEXT;
<H2>Parts Maintenance:Input Confirmation</H2>
<HR>
<FONT SIZE=4>Duplication!<BR>$a_company($a_company_code) is already in the PART MASTER as $a_description.</FONT>
<h4>
[<A HREF='javascript:history.back()'>Back To Previous Menu</A>]
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;
	
}
print<<ENDOFTEXT;
<H2>Company Maintenance:Confirmation</H2>
<HR>
<FORM METHOD=Post ACTION=m_company5.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "COMPANY_TYPE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td>";
      foreach $type (@company_types){
         print " $$type[0] $$type[1]\n" if ($in{$$key[0]} eq $$type[0]);
      }
   	  print "<input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "COUNTRY_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td><input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
      foreach $type (@country){
         print " $$type[0] $$type[1]\n" if ($in{$$key[0]} eq $$type[0]);
      }
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CURR_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td><input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
      foreach $type (@currency){
         print " $$type[1]\n" if ($in{$$key[0]} eq $$type[0]);;
      }
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "PDAYS"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">PAYMENT</th>\n";
   	  print " <td>$in{$$key[0]}";
      print " 	<input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
   } elsif ($$key[0] eq "PDESC"){
      print " 	$in{$$key[0]}<input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "DELETE_TYPE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
      print "<input type=hidden name=$$key[0] value='sysdate'>\n";
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "MARKS") {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td>";
      print "<PRE>$in{$$key[0]}</PRE>";
      print "<input type=hidden name=$$key[0] value='$in{$$key[0]}'>\n";
      print "</td>\n";
      print "</tr>\n";
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td>";
      print " $in{$$key[0]}<BR><input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
      print "</td>\n";
      print "</tr>\n";
   }
}
print<<ENDOFTEXT;
 	 </select>
 	 </td>
</tr>
</table>
<P>
<input type=submit name=submit value="Next">
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;