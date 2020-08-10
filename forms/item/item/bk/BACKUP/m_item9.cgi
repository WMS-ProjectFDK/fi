#!/usr/local/bin/perl
require '/pglosas/init.pl';
require '/pglosas/master/item/item_desc.pl';
&make_desc;

&html_header("Parts Maintenance:Delete");
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

$sql = " select ";
foreach $key (@desc){
$sql .= " $$key[0],";
}
chop($sql);
$sql .= " from item ";
$sql .= " where ";
$sql .= " item_no = $in{'item_no'}";
$sql .= " and origin_code = $in{'origin_code'} ";

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
<H2>Parts Maintenance:Delete Confirmation</H2>
ENDOFTEXT
#
# Checking Order Remainder
#
$po_bal = 0; $so_bal = 0;  $do_bal = 0;
print "Checking Order Remainder...<BR>";

$sql  = "select count(*) from (";
$sql .= "select po_no  from po_details";
$sql .= " where bal_qty <> 0";
$sql .= " and item_no = $in{'item_no'}";
$sql .= " and origin_code = $in{'origin_code'}";
$sql .= " )";
&do_sql($sql);
$po_bal = $cur->fetchrow;
print "P/O REMAINDER = $po_bal<BR>";

$sql  = "select count(*) from ";
$sql .= "(select distinct d.so_no";
$sql .= " from so_details d";
$sql .= " where d.bal_qty <> 0";
$sql .= " and d.origin_code = $in{'origin_code'}";
$sql .= " and d.item_no = $in{'item_no'})";
&do_sql($sql);
$so_bal = $cur->fetchrow;
print "S/O REMAINDER = $so_bal<BR>";

$sql  = "select count(*) from (";
$sql .= "select h.gr_no";
$sql .= " from gr_header h,gr_details d";
$sql .= " where h.gr_date > add_months(sysdate,-12)";
$sql .= " and d.item_no = $in{'item_no'}";
$sql .= " and d.origin_code = $in{'origin_code'}";
$sql .= " and h.gr_no = d.gr_no ";
$sql .= ")";
&do_sql($sql);
$gr_bal = $cur->fetchrow;
print "G/R IN LAST ONE YEAR = $gr_bal<BR>";

$sql  = "select count(*) from (";
$sql .= "select h.do_no";
$sql .= " from do_header h,do_details d";
$sql .= " where h.do_date > add_months(sysdate,-12)";
$sql .= " and d.item_no = $in{'item_no'}";
$sql .= " and d.origin_code = $in{'origin_code'}";
$sql .= " and h.do_no = d.do_no ";
$sql .= ")";
&do_sql($sql);
$do_bal = $cur->fetchrow;
print "D/O IN LAST ONE YEAR = $do_bal<BR>";

if ($po_bal == 0 and $so_bal == 0 and $do_bal == 0){
} else {

print<<ENDOFTEXT;
$target_item->{DESCRIPTION} ($in{'item_no'},$in{'origin_code'}) cannot be deleted.
<HR>
[<A HREF=javascript:history.back()>Back</A>]
</BODY>
</HTML>
ENDOFTEXT
exit;
}
print<<ENDOFTEXT;
<HR>
<FORM METHOD=Post ACTION=m_item10.cgi?$KEYWORD>
<input type=hidden name=item_no value=$in{'item_no'}>
<input type=hidden name=origin_code value=$in{'origin_code'}>
<table border=1>
ENDOFTEXT

foreach $key (@desc){
   if ($$key[0] eq "ORIGIN_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@country){
         if ($target_item->{$$key[0]} eq $type->{country_code}){;
         	print "$type->{country_code} $type->{country}\n";
         }
      }
   	  print "</td>\n";
      print "</tr>\n";
   } elsif ($$key[0] eq "CLASS_CODE"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>";
      foreach $type (@class){
       if ($type->{class_code} eq $target_item->{$$key[0]}){
         print "$type->{class_1}-$type->{class_2}-$type->{class_3}($type->{description})\n";
       }
      }
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
   	  print " <td>$target_item->{$$key[0]}</td>";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_L"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>$target_item->{$$key[0]}</td>";
      print "</tr>\n";
   } elsif ($$key[0] eq "UOM_W"){
   	  print "<tr>\n";
   	  print " <th bgcolor=\"LightGreen\">$$key[3]</th>\n";
   	  print " <td>$target_item->{$$key[0]}</td>";
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
   	  print " <td>$target_item->{$$key[0]}<BR></td>";
      print "</tr>\n";
   }
}
print<<ENDOFTEXT;
</table>
<P>
Do you really delete it?
<input type=submit name=submit value="Delete">
<input type=button value='Back' onClick='javascript:history.back()'>
</FORM>
<HR>
[<A HREF=/pglosas/main.asp?KEYWORD=$KEYWORD&menuType=master>Back To Main Menu</A>]
</BODY>
</HTML>
ENDOFTEXT

exit;