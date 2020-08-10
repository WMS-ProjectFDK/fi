#!/usr/local/bin/perl
require '/pglosas/init.pl';
require '/pglosas/master/item/item_desc.pl';
&make_desc;

&html_header("Parts Maintenance:Change");
&title;

$dbh = &db_open();

#
# class code
#
$sql  = "select class_code,";
$sql .= " 		class_1,";
$sql .= "		class_2,";
$sql .= "		class_3,";
$sql .= "		description,";
chop($sql);
$sql .= " from class";

&do_sql($sql);

undef @class;
while ((@datas) = $cur->fetchrow){
   $rec = {};
      $rec->{class_code} = $datas[0];
      $rec->{class_1} = $datas[1];
      $rec->{class_2} = $datas[2];
      $rec->{class_3} = $datas[3];
      $rec->{description} = $datas[4];
	push @class,$rec ;
}
$cur->finish;

#
# unit
#
$sql = "select unit_code,unit,use from unit ";

&do_sql($sql);

undef @unit;
while ((@datas) = $cur->fetchrow){
  $rec = {};
  $rec->{unit_code} = $datas[0];
  $rec->{unit} = $datas[1];
  $rec->{use} = $datas[2];
	push @unit,$rec ;
}
$cur->finish;

#
# origin country
#
$sql = "select country_code,country from country ";

&do_sql($sql);

undef @country;
while ((@datas) = $cur->fetchrow){
 $rec = {};
  $rec->{country_code} = $datas[0];
  $rec->{country} = $datas[1];
	push @country,$rec ;
}
$cur->finish;

#
# supplier
#
$sql  = "select company_code,";
$sql .= "       company ";
$sql .= " from company ";
$sql .= " where company_type = 3 ";
#$sql .= " and country_code = $in{'origin_code'} ";

&do_sql($sql);

undef @suppliers;
while ((@datas) = $cur->fetchrow){
 $rec = {};
   $rec->{supplier_code} = $datas[0] ;
   $rec->{supplier} = $datas[1] ;
   push @suppliers, $rec ;
}
$cur->finish;

#
# ITEM INFORMATION
#
$sql = " select ";
foreach $key (@desc){
$sql .= " $$key[0],";
}
chop($sql);
$sql .= " from item ";
$sql .= " where ";
$sql .= " 	  item_no = $in{'item_no'}";
$sql .= " and origin_code = $in{'origin_code'}";

&do_sql($sql);

while((@datas) = $cur->fetchrow){

   $target_item = {};

	$i = 0;
   	foreach $key (@desc){
   		$target_item->{$$key[0]} = $datas[$i];
   		$i ++;
   	}
}

print<<ENDOFTEXT;
<H2>Parts Maintenance:Change Form</H2>
<HR>
<FORM METHOD=Post ACTION=m_item7.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "ORIGIN_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      #print "<select name=$$key[0]>\n";
      foreach $type (@country){
       if ($target_item->{$$key[0]} eq $type->{country_code}){
       	print "$type->{country_code} $type->{country}\n";
       }
      #   print " <option value=$type->{country_code}";
      #   print " selected" if ($target_item->{$$key[0]} eq $type->{country_code});
      #   print ">$type->{country_code} $type->{country}\n";
      }
      #print "</select>\n";
      print "<input type=\"hidden\" name=ORIGIN_CODE value=$in{'origin_code'}>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "ITEM_NO"){
      print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
      print "<td>$target_item->{$$key[0]}\n";
      print " <input type=hidden name=$$key[0] value='$target_item->{$$key[0]}'>";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CLASS_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      print "<option>CODE(DESCRIPTION)\n";
      foreach $type (@class){
         print " <option value=$type->{class_code}";
         print " selected" if ($type->{class_code} eq $target_item->{$$key[0]}) ;
         print ">$type->{class_1}-$type->{class_2}-$type->{class_3}($type->{description})\n";
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "UOM_Q"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      print "<option>";
      foreach $type (@unit){
        if ($type->{use} eq "QUANTITY"){
         print " <option value=$type->{unit_code}";
         print " selected" if ($type->{unit_code} eq $target_item->{$$key[0]}) ;
         print ">$type->{unit}\n";
        }
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_L"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      print "<option>";
      foreach $type (@unit){
        if ($type->{use} eq "LENGTH"){
         print " <option value=$type->{unit_code}";
         print " selected" if ($type->{unit_code} eq $target_item->{$$key[0]}) ;
         print ">$type->{unit}\n";
        }
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_W"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      print "<option>";
      foreach $type (@unit){
        if ($type->{use} eq "WEIGHT"){
         print " <option value=$type->{unit_code}";
         print " selected" if ($type->{unit_code} eq $target_item->{$$key[0]});
         print ">$type->{unit}\n" ;
        }
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "DELETE_TYPE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[0]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "SUPPLIER_CODE") {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
   	  print "<select name=SUPPLIER_CODE>\n";
   	  print "<option>\n";
   	  foreach $type (@suppliers){
   	    print "<option value=$type->{supplier_code}";
   	    print " selected" if ($type->{supplier_code} eq $target_item->{$$key[0]});
   	    print ">$type->{supplier_code} $type->{supplier}\n";
      	#print " <input type=text name=$$key[0] size=$$key[2] value='$target_item->{$$key[0]}'>";
      }
   	  print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " <input type=text name=$$key[0] size=$$key[2] value=\"$target_item->{$$key[0]}\" maxlength=$$key[2]>";
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
<input type=button value='Back' onClick='javascript:history.back()'>
<input type=reset name=submit value="Reset">
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;