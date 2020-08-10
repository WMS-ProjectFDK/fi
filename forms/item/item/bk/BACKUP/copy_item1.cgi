# ＳＩＭＳ【マスタ】
# ［IMEJ0002.cgi］品目登録（国内）
# ［作成日］2002/11/04
# ［作成者］松本一義
#
# ［変更履歴］9999/99/99 ＮＮＮＮ ＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮＮ
#======================================================================================

#-------------------------
# Require init file
#-------------------------
require '/pglosas/init.pl' ;

#-------------------------
# POST
#-------------------------
$stock_subject_code = $in{'stock_subject_code'} ;
$key                = $in{'key'} ;

#-------------------------
# SQL
#-------------------------
 $dbh = &db_open() ;

	#stock_subject_code
	$sql  = "select stock_subject from stock_subject where stock_subject_code = $stock_subject_code";
	$cur = $dbh->prepare($sql) || &err("IMEJ0002_SSC err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0002_SSC err $DBI::err .... $DBI::errstr") ;
	($stock_subject) = $cur->fetchrow ;

	#ITEM
	$sql  = "select i.*,s.stock_subject from item i,stock_subject s where 1 = 1 " ;
	$sql .= " and ( i.item_no like '%$key%' or upper(i.item) like '%$key%' or upper(i.description) like '%$key%' or upper(i.mak) like '%$key%')" if($key ne "") ;
	$sql .= " and i.stock_subject_code = s.stock_subject_code(+) ";
	$sql .= " and i.stock_subject_code = $stock_subject_code     ";
	$sql .= " order by item ";
	#print $sql ;
	$cur = $dbh->prepare($sql) || &err("IMEJ0002_MST err $DBI::err .... $DBI::errstr") ;
	$cur->execute() || &err("IMEJ0002_MST err $DBI::err .... $DBI::errstr") ;

#-------------------------
# HTML
#-------------------------
&html_header("品目マスターメンテナンス");
&title('品目マスターメンテナンス','IMEJ0002');


print<<ENDOFTEXT;
　( 検索キーワード：$key　資産区分：$stock_subject )<P>
　　■ 一覧で見つからない場合は検索キーワードを変更してください。<BR>
　　■ 復元ボタンが表\示されているデータは、過去に削除したデータです。<BR><BR>
<TABLE border=1 CELLPADDING=1 CELLSPACING=0>
<TR>
<TH bgcolor="LightGreen">品目コード</TH>
<TH bgcolor="LightGreen">品目名</TH>
<TH bgcolor="LightGreen">規格</TH>
<TH bgcolor="LightGreen">メーカー</TH>
<TH bgcolor="LightGreen"><br></TH>
</tr>
ENDOFTEXT

undef @item;
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
	print "<TR BGCOLOR=$BGC>\n";
		print "<TD>$ref->{ITEM_NO}<BR></TD>\n";
		print "<TD>$ref->{ITEM}<BR></TD>\n";
		print "<TD>$ref->{DESCRIPTION}<BR></TD>\n";
		print "<TD>$ref->{MAK}<BR></TD>\n";
		print "<TD>\n";
		print "<FORM METHOD=post NAME=myForm>\n";
		print "<INPUT TYPE=\"Hidden\" name=\"item_no\" VALUE=\"$ref->{ITEM_NO}\">\n";
		if($ref->{DELETE_TYPE} eq ""){
			print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"変更\" onClick='this.form.action=\"IMEJ0003.cgi?$KEYWORD\"'>\n";
			print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"削除\" onClick='this.form.action=\"IMEJ0004.cgi?$KEYWORD\"'>\n";
		}else{
			print "	<INPUT TYPE=\"submit\" NAME='TYPES' VALUE=\"復元\" onClick='this.form.action=\"IMEJ0004.cgi?$KEYWORD\"'>\n";
		}
	print "</TR>\n";
	print "</FORM>\n";
}
print<<ENDOFTEXT;
</table>
<HR>
ENDOFTEXT

&html_footer("javascript:history.back()","戻る");


exit ;