#m_company7.cgi
require '/pglosas/init.pl';
require '/pglosas/master/company/company_desc.pl';
&make_desc;

&html_header("Company Maintenance:Change Confirmation");
&title;

$dbh = &db_open();

#
# Form Check
#
&err("Company Code is null") if ($in{'COMPANY_CODE'} eq "");

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
# country code
#
$sql = "select country_code,country from country";

&do_sql($sql);

undef @country;
while ((@datas) = $cur->fetchrow){
	push @country,[ $datas[0], $datas[1] ];
}
$cur->finish;

#
# currency
#
$sql = "select curr_code,curr_mark from currency";

&do_sql($sql);

undef @currency;
while ((@datas) = $cur->fetchrow){
	push @currency,[ $datas[0], $datas[1] ];
}
$cur->finish;

#
# select target
#
$sql  = " select ";
foreach $key (@desc){
   $sql .= "$$key[0]," if ($$key[4] eq "COMPANY" or $$key[4] eq "");
}
chop($sql);
$sql .= " from company ";
$sql .= " where company_code = $in{'COMPANY_CODE'} ";

&do_sql($sql);

while((@datas) = $cur->fetchrow){

   $target_company = {};

	$i = 0;
   	foreach $key (@desc){
   	  if ($$key[4] eq "COMPANY" or $$key[4] eq ""){
   		$target_company->{$$key[0]} = $datas[$i];
   		$i ++;
   	  }
   	}
}
$sql  = " select ";
foreach $key (@desc){
   $sql .= "$$key[0]," if ($$key[4] eq "CONTRACT" or $$key[4] eq "");
}
chop($sql);
$sql .= " from contract ";
$sql .= " where company_code = $in{'COMPANY_CODE'} ";
$sql .= " and   contract_seq = 1 ";

&do_sql($sql);

while((@datas) = $cur->fetchrow){

   #$target_company = {};

	$i = 0;
   	foreach $key (@desc){
   	  if ($$key[4] eq "CONTRACT" or $$key[4] eq ""){
   		$target_company->{$$key[0]} = $datas[$i];
   		$i ++;
   	  }
   	}
}


print<<ENDOFTEXT;
<H2>Company Maintenance:Change Confirmation</H2>
<HR>
<FORM METHOD=Post ACTION=m_company8.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "COMPANY_TYPE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td>";
      foreach $type (@company_types){
         print "$$type[0] $$type[1]\n" if ($$type[0] eq $target_company->{$$key[0]});
      }
      print "</td>\n";
   	  print " <td";
   	  print " bgcolor=\"Pink\"" if ($target_company->{$$key[0]} ne $in{$$key[0]});
   	  print ">";
      foreach $type (@company_types){
         print "$in{$$key[0]} $$type[1]\n" if ($$type[0] eq $in{$$key[0]});
      }
      print "<input type=hidden name=$$key[0] value='$in{$$key[0]}'>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "COUNTRY_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td>";
      foreach $type (@country){
         print "$$type[0] $$type[1]\n" if ($$type[0] eq $target_company->{$$key[0]});
      }
      print "</td>\n";
   	  print " <td";
   	  print " bgcolor=\"Pink\"" if ($target_company->{$$key[0]} ne $in{$$key[0]});
   	  print ">";
      foreach $type (@country){
         print "$in{$$key[0]} $$type[1]\n" if ($$type[0] eq $in{$$key[0]});
      }
      print "<input type=hidden name=$$key[0] value='$in{$$key[0]}'>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CURR_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td>";
      foreach $type (@currency){
         print "$$type[1]\n" if ($$type[0] eq $target_company->{$$key[0]});
      }
      print "</td>\n";
   	  print " <td";
   	  print " bgcolor=\"Pink\"" if ($target_company->{$$key[0]} ne $in{$$key[0]});
   	  print ">";
      foreach $type (@currency){
         print "$$type[1]\n" if ($$type[0] eq $in{$$key[0]});
      }
      print "<input type=hidden name=$$key[0] value='$in{$$key[0]}'>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "PDAYS"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">PAYMENT</th>\n";
   	  print " <td";
#   	  print " bgcolor=\"Pink\"" if ($target_company->{$$key[0]} ne $in{$$key[0]}
#   	  								or
#   	  								$target_company->{'PDESC'} ne $in{PDESC});
   	  print ">";
      print " 	$target_company->{$$key[0]}";
      print " 	$target_company->{'PDESC'}";
      print "</td>\n";
   	  print " <td";
   	  print " bgcolor=\"Pink\"" if ($target_company->{$$key[0]} ne $in{$$key[0]}
   	  								or
   	  								$target_company->{'PDESC'} ne $in{PDESC});
   	  print ">";
      print " 	$in{$$key[0]}";
      print " 	<input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
      print " 	$in{PDESC}";
      print " 	<input type=hidden name='PDESC' value='$in{PDESC}'>";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "PDESC"){
   } elsif ($$key[0] eq "DELETE_TYPE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td>";
      print " $target_company->{$$key[0]}";
      print "<br></td>\n";
   	  print " <td";
   	  print " bgcolor=\"Pink\"" if ($target_company->{$$key[0]} ne $in{$$key[0]});
   	  print ">";
   	  print " $in{$$key[0]}";
      print " <input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
      print "<br></td>\n";
      print "</tr>\n";
   }
}
print<<ENDOFTEXT;
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