#!/usr/local/bin/perl
#
# Require init-file
#
#  2002/02/27 Y.YAMANAKA
#
require '/pglosas/init.pl';
#require 'init.pl';

#&html_header("PARTS GROUP Master Listing");
#&title();

#
# db open
#
$dbh = &db_open();
#
#SQL
#
#ÉfÅ[É^ÇÃéÛÇØéÊÇË
#

print "<html>\n";
print "<BODY>\n";

print <<ENDOFTEXT ;

<script language='javascript'>
<!--//
	function set_cmp(line_no){
		
		window.opener.document.inputform.station_code.value = document.S_FORM["CM_CD" + line_no].value;
		window.opener.document.inputform.company.value = document.S_FORM["CM_NM" + line_no].value;
		window.close();
	}

//-->
</script>
ENDOFTEXT

$sql = "select COMPANY_CODE, COMPANY from COMPANY WHERE COMPANY_CODE like '$in{'ST_CODE'}%' order by COMPANY_CODE" ;

print "<form name=S_FORM >\n";
print "<table border=1>\n";
print "<tr>\n";
print "<td bgcolor=navy ><font color='white'>Station</font></td>\n";
print "<td bgcolor=navy ><font color='white'>Station Name</font></td>\n";
print "<td bgcolor=navy ><BR></td>\n";
print "</tr>\n";
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;
	$wk_cnt= 0 ;
	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }
	    print "<tr>\n";
	    print "<td>$COMPANY_CODE<input type=hidden name=CM_CD$wk_cnt value='$COMPANY_CODE'></td>\n";
	    print "<td>$COMPANY<input type=hidden name=CM_NM$wk_cnt value='$COMPANY'></td>\n";
	    print "<td><INPUT TYPE=button name=s_btn value='SET' onclick='set_cmp($wk_cnt)'></td>\n";
	    print "</tr>\n";
	    $wk_cnt++;
	}
print "</table>\n";
print "<br>\n";
print "<br>\n";
print "<input type=button name=close value='Close' onclick='window.close()'>\n";
print "</form>\n";


print "</body>\n";
print "</html>\n";

