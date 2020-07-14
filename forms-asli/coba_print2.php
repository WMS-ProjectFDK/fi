<?php
set_time_limit (1000);
ini_set('memory_limit', '-1');
$content_html = "
<style type='text/css'>
<!--
  table.page_header {width: 100%; height: 90px; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
  table.page_footer {width: 100%; height: 90px; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}
  h1 {color: #000033}
  h2 {color: #000055}
  h3 {color: #000077}
  
  div.standard
  {
    padding-left: 5mm;
  }
-->
</style>

<page backtop='14mm' backbottom='14mm' backleft='10mm' backright='10mm' style='font-size: 12pt'>
  <page_header>
    <table class='page_header'>
      <tr>
        <td style='width: 100%; text-align: left'>
          Example of using bookmarks
        </td>
      </tr>
      <tr>
        <td style='width: 100%; text-align: left'>
          Example of using bookmarks
        </td>
      </tr>
      <tr>
        <td style='width: 100%; text-align: left'>
          Example of using bookmarks
        </td>
      </tr>
      <tr>
        <td style='width: 100%; text-align: left'>
          Example of using bookmarks
        </td>
      </tr>
      <tr>
        <td style='width: 100%; text-align: left'>
          Example of using bookmarks
        </td>
      </tr>
      <tr>
        <td style='width: 100%; text-align: left'>
          Example of using bookmarks
        </td>
      </tr>
    </table>
  </page_header>
  <page_footer>
    <table class='page_footer'>
      <tr>
        <td style='width: 100%; text-align: right'>
          page [[page_cu]]/[[page_nb]]
        </td>
      </tr>
    </table>
  </page_footer>
  <div style='margin-top:100px;margin-bottom:100%;position:absolute;'>
  <h2 align='center'>PURCHASE ORDER<br></h2>
  <table border=1>
    <thead>
      <tr>
        <th valign='middle' align='center' style='font-size:12px;width:30px;height:25px;'>NO</th>
        <th valign='middle' align='center' style='font-size:12px;width:30px;height:25px;'>ITEM NO</th>
        <th valign='middle' align='center' style='font-size:12px;width:68px;height:25px;'>E.T.A</th>
        <th valign='middle' align='center' style='font-size:12px;width:80px;height:25px;'>QTY</th>
        <th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>UNIT PRICE</th>
        <th valign='middle' align='center' style='font-size:12px;width:100px;height:25px;'>AMOUNT</th>
      </tr>
    </thead> ";
    $hal_new=1;
    for ($i=1; $i<60 ; $i++) { 
      $hal = '[[page_cu]]';
     if (intval($hal) != $hal_new){
      $content_html .="
      <tr>
        <td colspan=6 valign='middle' align='center' style='font-size:11px;height:50px;'></td>
      </tr>
      ";
     } 
      $content_html .="
      <tr>
        <td valign='middle' align='center' style='font-size:11px;height:25px;'>".$i."-".$hal."</td>
        <td valign='middle' align='left' style='font-size:11px;height:25px;'>data->DESCRIPTION-".$hal_new."</td>
        <td valign='middle' align='center' style='font-size:11px;height:25px;'>ETA</td>
        <td valign='middle' align='right' style='font-size:11px;height:25px;'>data->QTY</td>
        <td valign='middle' align='right' style='font-size:11px;height:25px;'>data->U_PRICE</td>
        <td valign='middle' align='right' style='font-size:11px;height:25px;'>data->AMT_O,2</td>
      </tr> 
      ";
      $hal_new = $hal;
    }
$content_html .="
   </table>
   </div>
</page>
";
  require_once(dirname(__FILE__).'/../class/html2pdf/html2pdf.class.php');
  $html2pdf = new HTML2PDF('P','A4','en');
  $html2pdf->writeHTML($content_html);
  $html2pdf->Output('example.pdf');
?>

