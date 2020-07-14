<?php
session_start();
include("../connect/conn.php");

$sql = "select company, aa.item_no, i.description,
      sum(month0) month0, sum(spmonth0) spmonth0,
      sum(month1) month1, sum(spmonth1) spmonth1,
      sum(month2) month2, sum(spmonth2) spmonth2,
      sum(month3) month3, sum(spmonth3) spmonth3,
      (select cast(trim(to_char(sysdate,'yyyymm')) as number) from dual) bulan0,
      (select cast(trim(to_char(sysdate+30,'yyyymm')) as number) from dual) bulan1,
      (select cast(trim(to_char(sysdate+60,'yyyymm')) as number) from dual) bulan2,
      (select cast(trim(to_char(sysdate+90,'yyyymm')) as number) from dual) bulan3
      from (
            select bb.company, item_no, to_char(data_date,'yyyymm') DateMonth, 
            case when to_char(data_date,'yyyymm') = (select cast(trim(to_char(sysdate,'yyyymm')) as number)  from dual) then qty else 0 end Month0,
            case when to_char(data_date,'yyyymm') = (select cast(trim(to_char(sysdate,'yyyymm')) as number)  from dual) then sp else 0 end spMonth0,
            case when to_char(data_date,'yyyymm') = (select cast(trim(to_char(sysdate+30,'yyyymm')) as number)  from dual) then qty else 0 end Month1,
            case when to_char(data_date,'yyyymm') = (select cast(trim(to_char(sysdate+30,'yyyymm')) as number)  from dual) then sp else 0 end spMonth1,
            case when to_char(data_date,'yyyymm') = (select cast(trim(to_char(sysdate+60,'yyyymm')) as number) from dual) then qty else 0 end Month2,
            case when to_char(data_date,'yyyymm') = (select cast(trim(to_char(sysdate+60,'yyyymm')) as number)  from dual) then sp else 0 end spMonth2,
            case when to_char(data_date,'yyyymm') = (select cast(trim(to_char(sysdate+90,'yyyymm')) as number)  from dual) then qty else 0 end Month3,
            case when to_char(data_date,'yyyymm') = (select cast(trim(to_char(sysdate+90,'yyyymm')) as number)  from dual) then sp else 0 end spMonth3
            from sales_plan aa
            inner join company bb on aa.customer_code = bb.company_code
            where to_char(data_date,'yyyymm') >= (select trim(to_char(sysdate,'yyyymm')) from dual)
      )aa
      left outer join item i on aa.item_no = i.item_no
      group by company,aa.item_no,i.description
      order by company,aa.item_no";

	$data = oci_parse($connect, $sql);
	oci_execute($data);

	$items = array();
	$rowno=0;
      
	while($row = oci_fetch_object($data)){
      	array_push($items, $row);
            $q = $items[$rowno]->MONTH0;
            $items[$rowno]->MONTH0 = number_format($q);

            $q1 = $items[$rowno]->MONTH1;
            $items[$rowno]->MONTH1 = number_format($q1);

            $q2 = $items[$rowno]->MONTH2;
            $items[$rowno]->MONTH2 = number_format($q2);

            $q3 = $items[$rowno]->MONTH3;
            $items[$rowno]->MONTH3 = number_format($q3);

      	$rowno++;
	}
	$result["rows"] = $items;
	echo json_encode($result);	
	
?>