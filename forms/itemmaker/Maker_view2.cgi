#!/usr/local/bin/perl
#
# Require init-file
#
#  2003/07/14 Y.YAMANAKA
#
require '/pglosas/init.pl';
#require 'init.pl';

&html_header("Supplier's Items");
&title();

#
# db open
#
$dbh = &db_open();
#
#SQL
#
#データの受け取り
#

if($KEYWORD eq ""){
	print "<B>Err : Login Failed</B>\n;";
}




print <<ENDOFTEXT ;

<script language='javascript'>
<!--//

	//数値チェック
	function MyIsNaN(wk_num){
		
		syousuuten = false;

		for(cnt=0;cnt < wk_num.length ; cnt++){
			
			if(!(wk_num.charAt(cnt) == "0" || wk_num.charAt(cnt) == "1" ||
				 wk_num.charAt(cnt) == "2" || wk_num.charAt(cnt) == "3" ||
				 wk_num.charAt(cnt) == "4" || wk_num.charAt(cnt) == "5" ||
				 wk_num.charAt(cnt) == "6" || wk_num.charAt(cnt) == "7" ||
				 wk_num.charAt(cnt) == "8" || wk_num.charAt(cnt) == "9" )){
				
				if( cnt == 0 && (wk_num.charAt(cnt) == "-" || wk_num.charAt(cnt) == "+"))	continue;
				
				if( wk_num.charAt(cnt) == "." && syousuuten == false ){
					syousuuten = true ;
					continue;
				}
				return true;
			}
		}
		return false;
	}
	//********************************
	//  MYIsNumeric : 数値チェックと桁数チェック
	// Fname フレーム名
	// sei_k 整数桁数
	// syo_k 少数桁数
	//********************************
	function MYIsNumeric(Fname,sei_k,syo_k){
		
		chack_num = document.inputform[Fname].value;

		if(MyIsNaN(chack_num)){
			alert("The " + chack_num + " is not Number");
			document.inputform[Fname].value="";
			return false;
		}
		
		//整数、少数桁数がともに０のときはチェックしない
		if(sei_k == 0 && syo_k == 0){
			return true;
		}
		
		str_len = (chack_num).length;
		point   = (chack_num).indexOf(".",0);

		msg_str = ""
		for(i_cnt=0;i_cnt<sei_k;i_cnt++){ msg_str += "9" ; }
		msg_str += "." ;
		for(i_cnt=0;i_cnt<syo_k;i_cnt++){ msg_str += "9" ; }

		if(point != -1 ){
			if( point > sei_k){
				alert("Max value is " + msg_str);
				document.inputform[Fname].value="";
				return false;
			}
			if(syo_k == 0){
				alert("The Number of decimals is φ");
				document.inputform[Fname].value="";
				return false;
			}
			if(str_len - (point+1) > syo_k ){
				alert("Max value is " + msg_str);
				document.inputform[Fname].value="";
				return false;
			}
		}else{
			if( str_len > sei_k){
				alert("Max value is " + msg_str);
				document.inputform[Fname].value="";
				return false;
			}
		}
		return true;
	}

	function GO_main(){
		location.href = "../../../main.asp?KEYWORD=$KEYWORD&menuType=master";
	}
 function new_sup(theForm,nos){
      w3 = window.open('supplier2.pl?nos=' + nos,'itm','WIDTH=600,HEIGHT=300,SCROLLBARS=yes,RESIZABLE=yes') ;
 }
//-->
</script>
ENDOFTEXT

#キャンセル時
if($in{'BTN_TYPE'} eq "Cancel"){  $in{'sup_cd'}= ""; }


$wk_sup_cd = "";
$wk_sup_nm = "";
$err_msg_str = "";
$wk_item_cnt = 0 ;


#print $in{'sup_cd'} ;

#EDPKEY が入力されているとき
if($in{'sup_cd'} ne ""){
	
	$sql  = "SELECT itm.item_no,itm.SUPPLIER_CODE,";
	$sql .= "       trim(TO_CHAR(itm.ESTIMATE_PRICE,'9,999,999,999,990.999990')) AS ESTIMATE_PRICE,";
	$sql .= "       it.ITEM, it.DESCRIPTION,";
	$sql .= "       un.UNIT,cm.COMPANY,";
	$sql .= "       cu.CURR_MARK";
	$sql .= "  FROM ITEMMAKER itm,";
	$sql .= "       ITEM      it,";
	$sql .= "       COMPANY   cm,";
	$sql .= "       CURRENCY  cu,";
	$sql .= "       UNIT      un";
	$sql .= " WHERE itm.ITEM_NO=it.ITEM_NO";
	$sql .= "   AND itm.SUPPLIER_CODE=cm.COMPANY_CODE";
	$sql .= "   AND itm.CURR_CODE=cu.CURR_CODE(+)";
	$sql .= "   AND it.UNIT_STOCK=un.UNIT_CODE(+)";
	$sql .= "   AND itm.SUPPLIER_CODE=" . $in{'sup_cd'};
	$sql .= " ORDER BY itm.ITEM_NO";
#print $sql ;
	$cur = $dbh->prepare($sql) || &err("Prepare error: $DBI::err .... $DBI::errstr") ;
	$cur->execute() ;

	while(@datas = $cur->fetchrow){
	    for($i=0;$i<scalar(@datas);$i++){
	     	${$cur->{NAME}->[$i]} = $datas[$i] ;
	    }
	    
	    $wk_sup_cd = $SUPPLIER_CODE;
	    $wk_sup_nm = $COMPANY ;
	    if ($wk_item_cnt eq 0 ){
			&MASTER_HEAD();
		}
		
		&DataDetail($ITEM_NO,$ITEM,$DESCRIPTION,$UNIT,$ESTIMATE_PRICE,$CURR_MARK);
		
		$wk_item_cnt = $wk_item_cnt + 1
	}

}

#データが検索されていない時
if ($wk_item_cnt eq 0 ){

	if($in{'sup_cd'} ne ""){
		$err_msg_str = "<Font color=red><B>This Supplier Code Not Exist in ItemMaker Table.</B></font><hr width=70%>" ;
	}

	&MASTER_HEAD();
}

#明細データ表示ＴＡＢＬＥを閉じる
print "</table>\n" ;


print <<ENDOFTEXT ;

	<CENTER>
	<HR WIDTH=90%>
		<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=80%>
		<TR>
			<TD ALIGN=RIGHT>
			<FONT SIZE=3 COLOR=#FF0000>Copyright 2001 FDK Corporation. All rights reserved.</FONT>
			</TD>
		</TR>
		</TABLE>
	</CENTER>

</body>
</html>

ENDOFTEXT


#Supplier 画面ヘッダー作成
sub MASTER_HEAD(){

print <<ENDOFTEXT ;

<center>Supplier's Items(ITEM MAKER MENTENANCE)</center>
<hr width=90%>
<center>
	
	<form action='Maker_view2.cgi' method='post' name='inputform'>
	<table border=0 cellspacing=1 cellpadding=1 width=680>
	<tr>
		<th NOWRAP bgcolor='#ccffcc' rowspan=2><h5>Supplier Code</th>
		<td width=100 bgcolor='#ccccff' rowspan=2>
			<input type='text' name='sup_cd' value='$wk_sup_cd' size=15 maxlength=13 onChange='MYIsNumeric(\"sup_cd\",10,0)'>
			<A HREF='javascript:new_sup(this.form)'><IMG SRC=$IMG_DIR/set.gif BORDER=0></A>
		</td>
		<th NOWRAP bgcolor='#ccffcc' ><h5>Supplier Name</th>
		<td colspan=3 bgcolor='#ccccff'>$wk_sup_nm</th>
	</tr>
	<tr>
		<th width=100><input type='submit' NAME=BTN_TYPE value='Show'></th>
		<th width=100><BR></th>
		<th width=100><BR></th>
		<th width=100><input type='Button' NAME=BTN_TYPE value='End' onClick='return GO_main()'></th>
		<input type='hidden' name='chk_bt' value='Show'>
		<input type='hidden' name='KEYWORD' value='$KEYWORD'>
		</tr>
	</table>
	</form>

<hr width=70%>

$err_msg_str

<table border=0 cellspacing=1 cellpadding=1 width=680>
<tr bgcolor='#ccffcc'>
	<th>Item No.</th>
	<th>Item Name</th>
	<th>Description</th>
	<th>Unit</th>
	<th>Estimate Price</th>
	<th>Currncy</th>
</tr>


ENDOFTEXT
	
}

#明細データ表示
sub DataDetail{
    local($it_cd,$it_nm,$descri,$uni,$Est,$cur) = @_ ;
    
print <<ENDOFTEXT ;
<tr bgcolor='#ccccff'>
	<td>$it_cd</td>
	<td>$it_nm</td>
	<td>$descri</td>
	<td align=center>$uni</td>
	<td align=right>$Est</td>
	<td align=center>$cur</td>
</tr>
ENDOFTEXT

}
