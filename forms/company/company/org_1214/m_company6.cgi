#m_company6.cgi
require '/pglosas/init.pl';
require '/pglosas/master/company/company_desc.pl';
&make_desc;

&html_header("Company Maintenance:Change");
&title;

$company_code = $in{'company_code'} ;
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
$sql .= " where company_code = $company_code ";

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
$sql .= " where company_code = $company_code ";
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
<H2>Company Maintenance:Change Form</H2>
<HR>
<FORM METHOD=Post ACTION=m_company7.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "COMPANY_TYPE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      foreach $type (@company_types){
         print " <option value=$$type[0]";
         print " selected" if ($$type[0] eq $target_company->{$$key[0]});
         print ">$$type[0] $$type[1]\n";
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
         print " selected" if ($$type[0] eq $target_company->{$$key[0]});
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
         print " <option value=$$type[0]";
         print " selected" if ($$type[0] eq $target_company->{$$key[0]});
         print ">$$type[1]\n";
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "PDAYS"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">PAYMENT</th>\n";
   	  print " <td>";
      print " 	<input type=text name=$$key[0] size=$$key[2] value='$target_company->{$$key[0]}'>";
   } elsif ($$key[0] eq "PDESC"){
      print " 	<input type=text name=$$key[0] size=$$key[2] value='$target_company->{$$key[0]}'>";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "DELETE_TYPE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
   } elsif ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "MARKS"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " <textarea name=$$key[0] cols=50 rows=6>$target_company->{$$key[0]}</textarea>";
      print "</td>\n";
      print "</tr>\n";
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " <input type=text name=$$key[0] size=$$key[2] value='$target_company->{$$key[0]}'>";
      print "</td>\n";
      print "</tr>\n";
   }
}
print<<ENDOFTEXT;
</table>
<P>
CONTRACT MASTER MAINTENANCE 
ENDOFTEXT

$sql = "select contract_seq from contract where company_code = $company_code and contract_seq > 1 ";
&do_sql($sql) ;
while((@datas) = $cur->fetchrow){
print "( <A HREF=../contract/ct_entry1.cgi?$KEYWORD&company_code=$company_code&seq_no=" . $datas[0] . ">" . $datas[0] . "</A>)\n";
}
print<<ENDOFTEXT;
 [<A HREF=../contract/ct_entry2.cgi?$KEYWORD&company_code=$company_code&types=NEW>NEW</A>]<FORM METHOD=Post NAME=myform ACTION=ct_entry2.cgi?$KEYWORD>
<BR>
<input type=submit name=submit value="Next">
<input type=reset name=submit value="Reset">
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;