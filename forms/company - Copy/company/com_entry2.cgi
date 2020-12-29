# m_company2.pl
require '/pglosas/init.pl';

#
# Check Formed Value
#
$company_type = $in{'company_type'} ;

$key = $in{'key'} ;
 $key =~ tr/[a-z]/[A-Z]/ ;

#
# DB Open
#
$dbh = &db_open();

$sql  = "select type_description from company_type where company_type = $company_type";
$cur = $dbh->prepare($sql) || &err("SP_REF err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("SP_REF err $DBI::err .... $DBI::errstr") ;
($type_description) = $cur->fetchrow ;

&html_header("Company Maintenance:Search Key = $key ($type_description)");
&title();

#
# SQL
#
$sql  = "select ";
$sql .= " com.upto_date     ,";
$sql .= " com.reg_date      ,";
$sql .= " com.delete_type   ,";
$sql .= " com.company_code  ,";
$sql .= " com.company_type  ,";
$sql .= " com.company       ,";
$sql .= " com.address1      ,";
$sql .= " com.address2      ,";
$sql .= " com.address3      ,";
$sql .= " com.address4      ,";
$sql .= " com.attn          ,";
$sql .= " com.tel_no        ,";
$sql .= " com.fax_no        ,";
$sql .= " com.zip_code      ,";
$sql .= " com.country_code  ,";
$sql .= " cou.country  ,";
$sql .= " com.curr_code     ,";
$sql .= " com.tterm         ,";
$sql .= " com.pdays         ,";
$sql .= " com.pdesc         ,";
$sql .= " com.case_mark     ,";
$sql .= " com.edi_code      ,";
$sql .= " com.vat           ,";
$sql .= " com.supply_type   ,";
$sql .= " cou.country,";
chop($sql);
$sql .= " from company com,country cou";
$sql .= " where 1=1 " ;
if($key ne ""){
$sql .= "   and  (   com.company_code like '%$key%' ";
$sql .= "        or upper(com.company) 	 like '%$key%')";
}
$sql .= " and cou.country_code(+) = com.country_code ";
$sql .= " and com.company_type = $company_type ";
$sql .= " order by com.company_code ";

#print $sql ;
$cur = $dbh->prepare($sql) || &err("SP_REF err $DBI::err .... $DBI::errstr") ;
$cur->execute() || &err("SP_REF err $DBI::err .... $DBI::errstr") ;


print<<ENDOFTEXT;
<FONT SIZE=4>Company Maintenance:Search Results. Search Key = $key (as $type_description)</FONT>
<TABLE border=1 CELLPADDING=1 CELLSPACING=0>
<tr>
<TH bgcolor="LightGreen">Code</TH>
<!--<TH bgcolor="LightGreen">Type</TH>-->
<TH bgcolor="LightGreen">Name</TH>
<TH bgcolor="LightGreen">Country</TH>
<TH bgcolor="LightGreen"><br></TH>
</tr>
ENDOFTEXT

undef @company;
while((@datas) = $cur->fetchrow){
      for($j=0;$j<@datas;$j++){
         $ref->{$cur->{NAME}->[$j]} = $datas[$j] ;
         print 
      }
	if($ref->{DELETE_TYPE} eq ""){
	   $BGC="#FFFFFF" ;
	}else{
	   $BGC="#DDDDDD" ;
	}
	print "<tr BGCOLOR=$BGC>\n";
		print "<td>$ref->{COMPANY_CODE}</td>\n";
		print "<td>$ref->{COMPANY}</td>\n";
		print "<td>$ref->{COUNTRY}</td>\n";
		print "<td>\n";
		print "<form method=post name=myForm>\n";
		print "<input type=\"Hidden\" name=\"company_code\" value=\"$ref->{COMPANY_CODE}\">\n";
		if($ref->{DELETE_TYPE} eq ""){
			
			
			if($company_type == 0){
				print "	<input type=\"submit\" name='TYPES' value=\"CHANGE\" onClick='this.form.action=\"com_entry3_self.cgi?$KEYWORD\"'>\n";
			#	print "	<input type=\"submit\" name='TYPES' value=\"CHANGE CONTRACT\" onClick='this.form.action=\"../contract/ct_entry1.cgi?$KEYWORD\"'>\n";
				print "	<input type=\"submit\" name='TYPES' value=\"DELETE\" onClick='this.form.action=\"com_entry4_self.cgi?$KEYWORD\"'>\n";
			}else{			
				print "	<input type=\"submit\" name='TYPES' value=\"CHANGE\" onClick='this.form.action=\"com_entry3.cgi?$KEYWORD\"'>\n";
				print "	<input type=\"submit\" name='TYPES' value=\"CHANGE CONTRACT\" onClick='this.form.action=\"../contract/ct_entry1.cgi?$KEYWORD\"'>\n";
				print "	<input type=\"submit\" name='TYPES' value=\"DELETE\" onClick='this.form.action=\"com_entry4.cgi?$KEYWORD\"'>\n";
			}
		}else{
			if($company_type == 0){
			print "	<input type=\"submit\" name='TYPES' value=\"RESTORE\" onClick='this.form.action=\"com_entry4_self.cgi?$KEYWORD\"'>\n";
			}else{
			print "	<input type=\"submit\" name='TYPES' value=\"RESTORE\" onClick='this.form.action=\"com_entry4.cgi?$KEYWORD\"'>\n";
			}
		}
	print "</tr>\n";
	print "</form>\n";
}
print<<ENDOFTEXT;
</table>
<HR>
ENDOFTEXT

&html_footer("javascript:history.back()","Back");


exit ;