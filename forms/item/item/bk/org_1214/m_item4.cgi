#!/usr/local/bin/perl
require '/pglosas/init.pl';
require '/pglosas/master/item/item_desc.pl';
&make_desc;

&html_header("Parts Maintenance");
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


&err("PART NO IS NOT SPECIFIED") if ($in{'ITEM_NO'} eq "");
&err("PART DESCRIPTION IS NOT SPECIFIED") if ($in{'DESCRIPTION'} eq "");
&err("ORIGIN COUNTRY IS NOT SPECIFIED") if ($in{'ORIGIN_CODE'} eq "");
&err("SUPPLIER IS NOT SPECIFIED") if ($in{'SUPPLIER_CODE'} eq "");
&err("CLASSFICATION CODE IS NOT SPECIFIED") if ($in{'CLASS_CODE'} eq "");

#
# サプライヤーチェック
#
$sql  = "select country_code from company";
$sql .= " where company_code = $in{'SUPPLIER_CODE'} ";

&do_sql($sql);

($supplierS_country_code) = $cur->fetchrow();

$cur->finish;
if ($supplierS_country_code ne $in{'ORIGIN_CODE'}){
    foreach $key (@country){
    	if ($key->{country_code} eq $in{'ORIGIN_CODE'}){
    		$supplierS_country = $key->{country} ;
    	}
    }
	&err("SELECTED SUPPLIER'S DIST COUNTRY IS NOT $supplierS_country($in{'ORIGIN_CODE'})");
}
#
# 重複チェック
#
$sql  = "select item_no,";
$sql .= "       description,";
$sql .= "       origin_code,";
chop($sql);
$sql .= " from item ";
$sql .= " where item_no = $in{'ITEM_NO'}";
$sql .= " and   origin_code = $in{'ORIGIN_CODE'}";

&do_sql($sql);

($a_item_no,$a_description,$a_origin_code) = $cur->fetchrow;

if ($a_item_no ne ""){
print<<ENDOFTEXT;
<H2>Parts Maintenance:Input Confirmation</H2>
<HR>
<FONT SIZE=4>Duplication!<BR>$a_item_no($a_origin_code) is already in the PART MASTER as $a_description.</FONT>
<h4>
[<A HREF='javascript:history.back()'>Back To Previous Menu</A>]
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;
	
}
print<<ENDOFTEXT;
<H2>Parts Maintenance:Input Confirmation</H2>
<HR>
<FORM METHOD=Post ACTION=m_item5.cgi?$KEYWORD>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "ORIGIN_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@country){
         print "$type->{country_code} $type->{country}\n" if ($type->{country_code} eq $in{$$key[0]});
      }
      print "<input type=\"hidden\" name=\"$$key[0]\" value='$in{$$key[0]}'>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CLASS_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@class){
         print "$type->{class_code} $type->{class_1}-$type->{class_2}-$type->{class_3}($type->{description})\n" if ($type->{class_code} eq $in{$$key[0]});
      }
      print "<input type=\"hidden\" name=\"$$key[0]\" value='$in{$$key[0]}'>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "REG_DATE" or $$key[0] eq "UPTO_DATE"){
   	  #print "<tr>\n";
   	  #print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  #print " <td>";
      #print " <input type=text name=$$key[0] size=$$key[2]>";
      #print "</td>\n";
      #print "</tr>\n";
   } elsif ($$key[0] eq "UOM_Q"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
   	  foreach $ref (@unit){
      print "$ref->{unit}\n" if ($ref->{unit_code} eq $in{$$key[0]});
      }
      print "<input type=\"hidden\" name=\"$$key[0]\" value='$in{$$key[0]}'>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_L"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
   	  foreach $ref (@unit){
      print "$ref->{unit}\n" if ($ref->{unit_code} eq $in{$$key[0]});
      }
      print "<input type=\"hidden\" name=\"$$key[0]\" value='$in{$$key[0]}'>\n";
      print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_W"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
   	  foreach $ref (@unit){
      print "$ref->{unit}\n" if ($ref->{unit_code} eq $in{$$key[0]});
      }
      print "<input type=\"hidden\" name=\"$$key[0]\" value='$in{$$key[0]}'>\n";
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
   } elsif ($$KEY[0] eq "ITEM_NO") {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " $in{$$key[0]}";
      print "<input type=\"hidden\" name=\"$$key[0]\" value='$in{$$key[0]}'>\n";
      print "<br></td>\n";
      print "</tr>\n";
   } else {
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      print " $in{$$key[0]}";
      print "<input type=\"hidden\" name=\"$$key[0]\" value='$in{$$key[0]}'>\n";
      print "<br></td>\n";
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