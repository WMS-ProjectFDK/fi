<?php
	include("../../connect/conn_accpac.php");
	header("Content-type: application/json");

	$tipe = isset($_REQUEST['tipe']) ? strval($_REQUEST['tipe']) : '';

	$sql = "select tipe, text_val, value_val from  (
		select 'CATEGORY' tipe, ltrim(rtrim(GRPDESC))  text_val,ltrim(rtrim(GRPDESC)) value_val from AMGROP where len(GRPID) > 5
		union all
		select 'GROUP' tipe, ltrim(rtrim(GRPDESC))   text_val, ltrim(rtrim(GRPDESC)) value_val from AMGROP where len(GRPID) = 2
		union all
		select 'ASSET' tipe,ltrim(rtrim(astno)) + ' - ' + ltrim(rtrim([DESC])) text_val,ltrim(rtrim(astno)) value_val from AMASST
		union all
		select DISTINCT 'SPEC' tipe,rtrim(ltrim(SPEC)) text_val , rtrim(ltrim(SPEC)) value_val from amasst 
		union all
		select 'CLAS' tipe,rtrim(ltrim([DESC])) text_val , rtrim(ltrim([DESC])) value_val from AMCLAS where left(code,1) = 'L' 
		)xx where tipe = '$tipe'";


$rs=sqlsrv_query($connect, strtoupper($sql));
$arrNo = 0;
while ($row=sqlsrv_fetch_object($rs)){
   $arrData[$arrNo] = array("TEXT_VAL"=>$row->TEXT_VAL,
							"VALUE_VAL"=>$row->VALUE_VAL
							);

	$arrNo++;
}
echo json_encode($arrData);
?>