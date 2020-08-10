#!/usr/local/bin/perl
require '/pglosas/init.pl';
require '/pglosas/master/item/item_desc.pl';
&make_desc;

&html_header("Parts Maintenance");
&title;

$dbh = &db_open();

#
# Vender
#
$sql  = "select company_code,";
$sql .= " 		company ";
chop($sql);
$sql .= " from company";
$sql .= " where company_type = 3 ";	# type = 3 is supplier;

&do_sql($sql);

undef @suppliers;
while ((@datas) = $cur->fetchrow){
   $rec = {};

   $rec->{company_code} = $datas[0];
   $rec->{company} = $datas[1];

   push @suppliers,$rec;
}

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
$sql .= " order by class_code ";

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


print<<ENDOFTEXT;
<H2>Parts Maintenance:Input Form</H2>
<HR>
<FORM METHOD=Post ACTION=m_item4.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "ORIGIN_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      print "<option value=>Select ORIGIN from below...\n";
      foreach $type (@country){
         print " <option value=$type->{country_code}>$type->{country_code} $type->{country}\n";
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CLASS_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      print "<option value=>Select CODE(DESCRIPTION) from below...\n";
      foreach $type (@class){
         print " <option value=$type->{class_code}>$type->{class_code} $type->{class_1}-$type->{class_2}-$type->{class_3}($type->{description})\n";
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
         print " <option value=$type->{unit_code}>$type->{unit}\n" if ($type->{use} eq "QUANTITY");
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
         print " <option value=$type->{unit_code}>$type->{unit}\n" if ($type->{use} eq "LENGTH");
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
         print " <option value=$type->{unit_code}>$type->{unit}\n" if ($type->{use} eq "WEIGHT");
      }
      print "</select>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "SUPPLIER_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print "<select name=$$key[0]>\n";
      print "<option>";
      foreach $type (@suppliers){
         print " <option value=$type->{company_code}>$type->{company_code} $type->{company}\n";
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
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " <input type=text name=$$key[0] size=$$key[2] maxlength=$$key[2]>";
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