<?php
$machine = isset($_REQUEST["id_machine"]) ? strval($_REQUEST["id_machine"]) : "";
$line = isset($_REQUEST["line"]) ? strval($_REQUEST["line"]) : "";

$items = array();
$rowno = 0;
include("../../connect/conn_kanbansys.php");
$sql = "select a.id_part, a.id_machine, b.machine, a.line, a.nama_part, a.unit_qty, a.tgl_ganti, a.lifetime,
    a.current_lifetime, a.estimation_tgl_ganti as estimation_replacment, a.status 
    from assembly_part a
    left outer join assembly_line_master b on a.id_machine = b.id_machine and a.line = b.line
    where replace(a.line,'#','-') = '$line' and a.id_machine=$machine
    order by a.id_part asc";

$rs = odbc_exec($connect,$sql);
while($row = odbc_fetch_object($rs)){
    array_push($items, $row);

    $current_lifetime = $items[$rowno]->current_lifetime;
    $items[$rowno]->lifetime_c = number_format($current_lifetime);
    
    $lifetime = $items[$rowno]->lifetime;
    $items[$rowno]->lifetime_r = number_format($lifetime);

    $rowno++;
}

$p = strtoupper($items[0]->machine);
odbc_close($connect);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="300">
    <title>Monitoring Assembling</title>
    <link rel="icon" type="image/png" href="../../favicon.png">
    <script src="../../plugins/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="../../plugins/bootstrap/css/bootstrap.min.css">
    <script src="../../plugins/bootstrap/js/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row"><div class="col-sm-12">
            <h3 align="center"><b>LIST PART LINE <?php echo str_replace("-", "#", $line).' PROCESS '.$p; ?></b></h3>
            <table class="table table-bordered">
                <thead style="font-size: 16px;">
                <tr>
                    <th class="info text-center" style="vertical-align: middle;" width="220">PROCESS</th>
                    <th class="info text-center" style="vertical-align: middle;" width="80">LINE</th>
                    <th class="info text-center" style="vertical-align: middle;" width="300">PART</th>
                    <th class="info text-center" style="vertical-align: middle;" width="80">UNIT QTY</th>
                    <th class="info text-center" style="vertical-align: middle;" width="100">LAST<br>REPLACEMENT</th>
                    <th class="info text-center" style="vertical-align: middle;" width="150">LIFE TIME (pcs)</th>
                    <th class="info text-center" style="vertical-align: middle;" width="150">CURRENT<br>LIFE TIME (pcs)</th>
                    <th class="info text-center" style="vertical-align: middle;" width="200">ESTIMATION<br>REPLACEMENT DATE</th>
                    <th class="info text-center" style="vertical-align: middle;" width="100">STATUS</th>
                </tr>
                </thead>
                <?php
                for ($i=0; $i<count($items); $i++) { ?>
                    
                <tbody style="font-size: 14px;">
                <tr>
                    <td align="left" width="220"><?php echo strtoupper($items[$i]->machine); ?></td>
                    <td align="center" width="80"><?php echo $items[$i]->line; ?></td>
                    <td align="left" width="300"><?php echo strtoupper($items[$i]->nama_part); ?></td>
                    <td align="right" width="80"><?php echo $items[$i]->unit_qty; ?></td>
                    <td align="center" width="100"><?php echo $items[$i]->tgl_ganti; ?></td>
                    <td align="right" width="150"><?php echo $items[$i]->lifetime_r; ?></td>
                    <td align="right" width="150"><?php echo $items[$i]->lifetime_c; ?></td>
                    <td align="center" width="200"><?php echo $items[$i]->estimation_replacment; ?></td>
                    
                    <?php
                    if($items[$i]->status == 'OK'){
                        echo '<td align="center" width="100" style="background-color:green;color:white">'.$items[$i]->status.'</td>';
                    }elseif($items[$i]->status == 'REPLACE'){
                        echo '<td align="center" width="100" style="background-color:red;color:white">'.$items[$i]->status.'</td>';
                    }elseif($items[$i]->status == 'ORDER'){
                        echo '<td align="center" width="100" style="background-color:yellow;color:black">'.$items[$i]->status.'</td>';
                    }
                    ?>
                    
                </tr>    
                </tbody>    

                <?php
                }
                ?>
            </table>
            <div align="center">
                <button type="button" class="btn btn-danger" title="Back to Monitoring" onclick="javascript:window.close();">BACK</button>
            </div>
        </div></div>
    </div>
</body>
</html>