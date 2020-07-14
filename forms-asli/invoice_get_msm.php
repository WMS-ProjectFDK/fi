<?php
	include("../connect/conn.php");
	$id = isset($_REQUEST['id']) ? strval($_REQUEST['id']) : '';
	$ppbe = isset($_REQUEST['ppbe']) ? strval($_REQUEST['ppbe']) : '';
	
	$sql = "select * from

		(select 'Breakdown Container' as sts,sum(gross) as gw,sum(net) as nw ,sum(msm) as msm, ppbe_no as doc  
			from ztb_shipping_detail 
			where ppbe_no='$ppbe'
			group by ppbe_no
		)

		union all
		
		(select 'Packing List' as sts, sum(gw) as gw,sum(nw) as nw,sum(msm) as msm, remarks as doc  
			from ztb_shipping_ins 
			where remarks='$ppbe'
			group by remarks
		)
		
		union all

		(select 'Invoice' as sts, sum(gross) as gw,sum(net) as nw, sum(measurement) as msm, do_no as doc  
			from do_pl_header 
			where do_no='$id'
			group by do_no
		)";
		
	$data = oci_parse($connect, $sql);
	oci_execute($data);
	$rowno=0;
	$result = array();		$arrData = array();
	while ($row=oci_fetch_object($data)){
		array_push($arrData, $row);
		$s = $arrData[$rowno]->STS;
		$g = $arrData[$rowno]->GW;
		$n = $arrData[$rowno]->NW;
		$m = $arrData[$rowno]->MSM;

		$arrData[$rowno]->STS = strtoupper($s);
		$arrData[$rowno]->GW = number_format($g,2);
		$arrData[$rowno]->NW = number_format($n,2);
		$arrData[$rowno]->MSM = number_format($m,3);

		$rowno++;
	}
	$result["rows"] = $arrData;
	echo json_encode($result);
?>