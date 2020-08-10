#m_company8.cgi
require '/pglosas/init.pl';
require '/pglosas/master/company/company_desc.pl';
&make_desc;

&html_header("Company Maintenance:Change Registration");
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
<H2>Company Maintenance:Change Registration</H2>
<HR>
<FORM METHOD=Post ACTION=m_company7.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

undef @str1;undef @str2;
foreach $key (@desc){

   $rec1 = {}; $rec2 = {};

   if ($$key[0] eq "COMPANY_TYPE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td";
   	  if ($target_company->{$$key[0]} ne $in{$$key[0]}){
   	  	print " bgcolor=\"Pink\"";
   	  	$rec1->{str1} = $$key[0];
   	  	$rec1->{str2} = $in{$$key[0]};
   	  	$rec1->{type} = $$key[1];
   		push @str1,$rec1;
   	  }
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
   	  print " <td";
   	  if ($target_company->{$$key[0]} ne $in{$$key[0]}){
   	  	print " bgcolor=\"Pink\"";
   	  	$rec1->{str1} = $$key[0];
   	  	$rec1->{str2} = $in{$$key[0]};
   	  	$rec1->{type} = $$key[1];
   		push @str1,$rec1;
   	  }
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
   	  print " <td";
   	  if ($target_company->{$$key[0]} ne $in{$$key[0]}){
   	  	print " bgcolor=\"Pink\"";
   	  	$rec1->{str1} = $$key[0];
   	  	$rec1->{str2} = $in{$$key[0]};
   	  	$rec1->{type} = $$key[1];
   		push @str1,$rec1;
   	  	$rec2->{str1} = $$key[0];
   	  	$rec2->{str2} = $in{$$key[0]};
   	  	$rec2->{type} = $$key[1];
   		push @str2,$rec2;
   	  }
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
   	  if ($target_company->{$$key[0]} ne $in{$$key[0]}
   	  		or
   	  	$target_company->{'PDESC'} ne $in{PDESC}){
   	  	print " bgcolor=\"Pink\"";
#   	  	$str1 .= "$$key[0],"; $str2 .= "$in{$$key[0]},";
#   	  	$str1 .= "$$key[0],"; $str2 .= "$in{$$key[0]},";
   	  	$rec1->{str1} = $$key[0];
   	  	$rec1->{str2} = $in{$$key[0]};
   	  	$rec1->{type} = $$key[1];
   	  	push @str1,$rec1; $rec1 = {};
   	  	$rec1->{str1} = "PDESC";
   	  	$rec1->{str2} = $in{'PDESC'};
   	  	$rec1->{type} = "N";
   	  	push @str1,$rec1;
   	  	$rec2->{str1} = $$key[0];
   	  	$rec2->{str2} = $in{$$key[0]};
   	  	$rec2->{type} = $$key[1];
   	  	push @str2,$rec2; $rec2 = {};
   	  	$rec2->{str1} = "PDESC";
   	  	$rec2->{str2} = $in{'PDESC'};
   	  	$rec2->{type} = "X";
   		push @str2,$rec2;
   	  }
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
   } elsif ($$key[0] eq "REG_DATE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "UPTO_DATE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   	  	$rec1->{str1} = $$key[0];
   	  	$rec1->{str2} = "sysdate";
   	  	$rec1->{type} = $$key[1];
   	  	push @str1,$rec1; $rec1 = {};
   	  	$rec2->{str1} = $$key[0];
   	  	$rec2->{str2} = "sysdate";
   	  	$rec2->{type} = $$key[1];
   		push @str2,$rec2;
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  print " <td";
   	  if ($target_company->{$$key[0]} ne $in{$$key[0]}){
   	  	print " bgcolor=\"Pink\"";
   	  	if ($$key[4] eq "COMPANY"){
   	  	$rec1->{str1} = $$key[0];
   	  	$rec1->{str2} = $in{$$key[0]};
   	  	$rec1->{type} = $$key[1];
   		push @str1,$rec1;
   		} elsif ($$key[4] eq "CONTRACT"){
   	  	$rec2->{str1} = $$key[0];
   	  	$rec2->{str2} = $in{$$key[0]};
   	  	$rec2->{type} = $$key[1];
   		push @str2,$rec2;
   		} elsif ($$key[4] eq ""){
   	  	$rec1->{str1} = $$key[0];
   	  	$rec1->{str2} = $in{$$key[0]};
   	  	$rec1->{type} = $$key[1];
   		push @str1,$rec1;
   	  	$rec2->{str1} = $$key[0];
   	  	$rec2->{str2} = $in{$$key[0]};
   	  	$rec2->{type} = $$key[1];
   		push @str2,$rec2;
   		}
   	  }
   	  print ">";
   	  print " $in{$$key[0]}";
      print " <input type=hidden name=$$key[0] value='$in{$$key[0]}'>";
      print "<br></td>\n";
      print "</tr>\n";
   }
}
print<<ENDOFTEXT;
</table>
ENDOFTEXT
if (defined @str1){
   $sql1  = "update company set ";
   foreach $key (@str1){
    if ($key->{str1} ne "" and $key->{str2} ne ""){
   		$sql1 .= "$key->{str1} = ";
   		if ($key->{type} eq "X"){
   		   $sql1 .= "'$key->{str2}',";
   		} elsif ($key->{type} eq "N"){
   		   $sql1 .= $key->{str2} . ",";
   		} elsif ($key->{type} eq "D"){
   		   $sql1 .= $key->{str2} . ",";
   		}
   	}
   }
   chop($sql1);
   $sql1 .= " where company_code = $in{'COMPANY_CODE'}";

   #print $sql1 ;
   &do_sql($sql1);

   $sql2  = "update contract set ";
   foreach $key (@str2){
    if ($key->{str1} ne "" and $key->{str2} ne ""){
   		$sql2 .= "$key->{str1} = ";
   		if ($key->{type} eq "X"){
   		   $sql2 .= "'$key->{str2}',";
   		} elsif ($key->{type} eq "N"){
   		   $sql2 .= $key->{str2} . ",";
   		} elsif ($key->{type} eq "D"){
   		   $sql2 .= $key->{str2} . ",";
   		}
   	}
   }
   chop($sql2);
   $sql2 .= " where company_code = $in{'COMPANY_CODE'}";
   $sql2 .= " and   contract_seq = 1 ";
   #print $sql2 ;
   &do_sql($sql2);
   $dbh->commit;
   $dbh->disconnect;
   print "Changed successfully.";
} else {
	print "No change.";
}
print<<ENDOFTEXT;
<P>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;