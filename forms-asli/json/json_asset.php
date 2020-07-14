<?php
	include("../../connect/conn_accpac.php");
	header("Content-type: application/json");

	$tipe = isset($_REQUEST['tipe']) ? strval($_REQUEST['tipe']) : '';

	//OLD
	// $sql = "
	// select * from  (
	// select 'CATEGORY' tipe,ltrim(rtrim(GRPID)) + ' - ' + ltrim(rtrim(GRPDESC))  text_val,ltrim(rtrim(GRPID)) value_val from AMGROP where len(GRPID) > 5
	// union all
	// select 'GROUP' tipe,left(ltrim(rtrim(GRPID)),2) + ' - ' + ltrim(rtrim(GRPDESC))   text_val, left(ltrim(rtrim(GRPID)),2) value_val from AMGROP where len(GRPID) = 2
	// union all
	// select 'ASSET' tipe,ltrim(rtrim(astno)) + ' - ' + ltrim(rtrim(\"desc\")) text_val,ltrim(rtrim(ASTNO)) value_val from AMASST
	// union all
	// select DISTINCT 'SPEC' tipe,rtrim(ltrim(SPEC)) text_val , rtrim(ltrim(SPEC)) value_val from amasst 
	// union all
	// select 'CLAS' tipe,rtrim(ltrim(\"desc\")) text_val , rtrim(ltrim(\"desc\")) value_val from AMCLAS where left(code,1) = 'L' 
	// )xx where tipe = '$tipe'";

	$sql = "
	select * from  (
	select 'CATEGORY' tipe, ltrim(rtrim(GRPDESC))  text_val,ltrim(rtrim(GRPDESC)) value_val from AMGROP where len(GRPID) > 5
	union all
	select 'GROUP' tipe, ltrim(rtrim(GRPDESC))   text_val, ltrim(rtrim(GRPDESC)) value_val from AMGROP where len(GRPID) = 2
	union all
	select 'ASSET' tipe,ltrim(rtrim(astno)) + ' - ' + ltrim(rtrim(\"desc\")) text_val,ltrim(rtrim(astno)) value_val from AMASST
	union all
	select DISTINCT 'SPEC' tipe,rtrim(ltrim(SPEC)) text_val , rtrim(ltrim(SPEC)) value_val from amasst 
	union all
	select 'CLAS' tipe,rtrim(ltrim(\"desc\")) text_val , rtrim(ltrim(\"desc\")) value_val from AMCLAS where left(code,1) = 'L' 
	)xx where tipe = '$tipe'";


$rs=odbc_exec($con,$sql);
$arrNo = 0;
while (odbc_fetch_row($rs)){
   
   $arrData[$arrNo] = array("TEXT_VAL"=>odbc_result($rs,"TEXT_VAL"),
                             "VALUE_VAL"=>odbc_result($rs,"VALUE_VAL")
                    );
   
$arrNo++;

}

echo json_encode($arrData);
?>