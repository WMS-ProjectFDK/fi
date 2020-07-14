<!DOCTYPE>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>FDK-ASSEMBLY REPORT</title>
  <style>
  table {
      border-collapse: collapse;
  }

  table, td, th {
      border: 1px solid black;
  }
</style>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
  <h4>Dear All,</h4>
  <p>Please see the result of assembling production :<br/>Actual Assembling Production VS  Plan Assembling Production.</p>

  <div>
      <p>Production until '.$on_date.'</p>
    <table>
      <tr>
         <th style="background-color: #D2D2D2;width: 40px;" align="center">NO.</th>
         <th style="background-color: #D2D2D2;width: 150px;" align="center">ASSY LINE</th>
         <th style="background-color: #D2D2D2;width: 150px;" align="center">CELL TYPE</th>
         <th style="background-color: #D2D2D2;width: 120px;" align="center">PLAN</th>
         <th style="background-color: #D2D2D2;width: 120px;" align="center">ACTUAL</th>
         <th style="background-color: #D2D2D2;width: 120px;" align="center">GAP</th>
      </tr>
<?php
$sql2 ="select p.assy_line, p.cell_type, sum(plan) as plan, sum(actual) as actual, sum(actual) - sum(plan) as Gap
    from (
      select aa.assy_line, aa.cell_type, case when keterangan = 'a.plan' then qty else 0 end as Plan, case when keterangan = 'b.actual' then qty else 0 end as Actual 
      from (
        select 'a.plan' as keterangan, assy_line, cell_type,
        ".$pl." as qty 
        from zvw_plan_assy 
        where  bulan = ".intval(date('m'))." and tahun = ".date('Y')." and ".$pl." > 0
        UNION ALL
        select 'b.actual',k.assy_line, k.cell_type, sum(qty_act_perpallet) as ActualAmount from ztb_assy_kanban k
        where to_number(to_char(tanggal_actual,'mm'))= ".date('m')." 
        AND to_number(to_char(tanggal_actual,'yyyy'))= ".date('Y')." 
        AND to_number(to_char(tanggal_actual,'dd')) <= ".$plan_date."
        group by k.assy_line,k.cell_type
      ) aa
      order by aa.assy_line,keterangan,aa.cell_type
    ) P group by p.assy_line, p.cell_type
    order by p.assy_line";
$result2 = oci_parse($connect, $sql2);
oci_execute($result2);
$no2=1;  $t_plan2 = 0;  $t_act2 = 0;   $t_gap2 = 0;
while ($data_cek2=oci_fetch_array($result2)){
  if($data_cek2[4] < 0){
    $print_gp ="<td style='background-color: #E2EFDA;'>".$no2++."</td>
              <td style='background-color: #E2EFDA;'>".$data_cek2[0]."</td>
              <td style='background-color: #E2EFDA;'>".$data_cek2[1]."</td>
              <td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[2])."</td>
              <td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[3])."</td>
              <td style='background-color: #E2EFDA;color: #FF0000;' align='right'>".number_format($data_cek2[4])."</td>";
  }else{
    $print_gp ="<td style='background-color: #E2EFDA;'>".$no2++."</td>
              <td style='background-color: #E2EFDA;'>".$data_cek2[0]."</td>
              <td style='background-color: #E2EFDA;'>".$data_cek2[1]."</td>
              <td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[2])."</td>
              <td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[3])."</td>
              <td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek2[4])."</td>";  
  }
  
  $message .= '<tr>'.$print_gp.'</tr>';

  $t_plan2 += $data_cek2[2];
  $t_act2 += $data_cek2[3];
  $t_gap2 += $data_cek2[4]; 
}

if($t_gap2 < 0){
  $tot_gap2 = '<td style="background-color: #D2D2D2; color: #FF0000;" align="right">'.number_format($t_gap2).'</td>';
}else{
  $tot_gap2 = '<td style="background-color: #D2D2D2;" align="right">'.number_format($t_gap2).'</td>';
}

$message.= '<tr>
        <td colspan="3" style="background-color: #D2D2D2;" align="center"><b>TOTAL</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_plan2).'</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_act2).'</b></td>
        '.$tot_gap2.'
      </tr>
    </table>
  </div>';
$message .='<br/>';

$message.='
  <div>
    <p>Production on '.$on_date.'</p>
     <table>
      <tr>
        <th style="background-color: #D2D2D2;width: 40px;" align="center">NO.</th>
        <th style="background-color: #D2D2D2;width: 150px;" align="center">ASSY LINE</th>
        <th style="background-color: #D2D2D2;width: 150px;" align="center">CELL TYPE</th>
        <th style="background-color: #D2D2D2;width: 120px;" align="center">PLAN</th>
        <th style="background-color: #D2D2D2;width: 120px;" align="center">ACTUAL</th>
        <th style="background-color: #D2D2D2;width: 120px;" align="center">GAP</th>
      </tr>';

$sql = "select p.assy_line, p.cell_type, sum(plans) as plan, sum(actual) as actual, sum(actual) - sum(plans) as gap
    from (
      select aa.assy_line, aa.cell_type, case when keterangan = 'a.plan' then qty else 0 end as Plans, case when keterangan = 'b.actual' then qty else 0 end as Actual
      from (
        select 'a.plan' as keterangan,assy_line, cell_type, $plan as Qty from zvw_plan_assy 
        where  bulan = ".intval(date('m'))." and tahun = ".date('Y')." and $plan > 0 
        union all
        select 'b.actual',k.assy_line,k.cell_type,sum(qty_act_perpallet) as ActualAmount from ztb_assy_kanban k
      where to_number(to_char(tanggal_actual,'mm'))= ".date('m')."
        AND to_number(to_char(tanggal_actual,'yyyy'))= ".date('Y')."
        AND to_number(to_char(tanggal_actual,'dd')) = ".$plan_date."
      group by k.assy_line,k.cell_type
      )aa
      order by aa.assy_line,keterangan,aa.cell_type
    ) P 
    group by p.assy_line, p.cell_type
    order by p.assy_line";
$result = oci_parse($connect, $sql);
oci_execute($result);
$no=1;  $t_plan = 0;  $t_act = 0;   $t_gap = 0;
while ($data_cek=oci_fetch_array($result)){
  if($data_cek[4] < 0){
    $print_gp ="<td style='background-color: #E2EFDA;'>".$no++."</td>
              <td style='background-color: #E2EFDA;'>".$data_cek[0]."</td>
              <td style='background-color: #E2EFDA;'>".$data_cek[1]."</td>
              <td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek[2])."</td>
              <td style='background-color: #E2EFDA;' align='right'>".number_format($data_cek[3])."</td>
              <td style='background-color: #E2EFDA; color: #FF0000;' align='right'>".number_format($data_cek[4])."</td>";
  }else{
    $print_gp ="<td background-color: #E2EFDA;'>".$no++."</td>
              <td background-color: #E2EFDA;'>".$data_cek[0]."</td>
              <td background-color: #E2EFDA;'>".$data_cek[1]."</td>
              <td background-color: #E2EFDA;' align='right'>".number_format($data_cek[2])."</td>
              <td background-color: #E2EFDA;' align='right'>".number_format($data_cek[3])."</td>
              <td background-color: #E2EFDA;' align='right'>".number_format($data_cek[4])."</td>";  
  }
  
  $message .= '<tr>'.$print_gp.'</tr>';

  $t_plan += $data_cek[2];
  $t_act += $data_cek[3];
  $t_gap += $data_cek[4];
}

if($t_gap < 0){
  $tot_gap = '<td style="background-color: #D2D2D2; color: #FF0000;" align="right">'.number_format($t_gap).'</td>';
}else{
  $tot_gap = '<td style="background-color: #D2D2D2;" align="right">'.number_format($t_gap).'</td>';
}

$message.= '<tr>
        <td colspan="3" style="background-color: #D2D2D2;" align="center"><b>TOTAL</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_plan).'</b></td>
        <td style="background-color: #D2D2D2;" align="right"><b>'.number_format($t_act).'</b></td>
        '.$tot_gap.'
      </tr>
    </table>
  </div>';


$message.='
<p>Do not reply this email.<br/><br/>';
//if you want to see realtime data then open the browser:
//http://fdkindonesia/kanban/assy/view.php
$message.='Thanks and Regards,<br/><br/><br/>

FDK INDONESIA</p>
</div>
</body>
</html>';