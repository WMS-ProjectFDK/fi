<?php 
echo "<table border='1'>
<thead>
<tr>
<th></th>
<th></th>
<th></th>
<th></th>
<th colspan=13>Item Info</th>
<th colspan=4>Lot Info</th>
</tr>
<tr>
<th bgcolor='#C8E3FF'>S.No.</th>
<th bgcolor='#C8E3FF'>Party Name</th>
<th bgcolor='#C8E3FF'>Item No.</th>
<th bgcolor='#C8E3FF'>Date</th>

<th>Flower</th>

<th bgcolor='#C8E3FF'>Lot No.</th>
<th bgcolor='#C8E3FF'>Avg Wt.</th>
<th bgcolor='#C8E3FF'>Total Weight</th>
<th bgcolor='#C8E3FF'>Detail Page</th>
</tr></thead><tfoot></tfoot><tbody>";

    echo "<tr>";
    echo "<td bgcolor='#C8E3FF' rowspan=".$itemCounter.">".$sno."</td>";
    echo "<td bgcolor='#C8E3FF' rowspan=".$itemCounter.">".$row['partyName']."</td>";

    while($row2 = mysqli_fetch_array($itemquery))
    {
        $itemNo++;
        echo "<td bgcolor='#C8E3FF'>".$itemNo."</td>";
        echo "<td bgcolor='#C8E3FF'>".$row2['date']."</td>";


            //$flower = $flower + $row2['flower'];
            echo "<td>".$row2['flower']."</td>";



        echo "<td  bgcolor='#C8E3FF'>".$row2['lotno']."</td>";
        echo "<td bgcolor='#C8E3FF'>".$row2['avgwt']."</td>";
        if ($row2['totalWeight'] == 0)
        {
            echo "<td  bgcolor='#C8E3FF'> </td>";
        }
        else{
            $totalWt = $totalWt + $row2['totalWeight'];
            echo "<td  bgcolor='#C8E3FF'>".$row2['totalWeight']."</td>";    
        }

        echo "<td  bgcolor='#C8E3FF'>".$row2['detailPage']."</td>";
        echo "</tr>";
    }
    echo "<td bgcolor='#C8E3FF'><b>Total</b></td>";


    echo "<td><b>".$flower."</b></td>";



        echo "<td bgcolor='#C8E3FF'></td>";
        echo "<td bgcolor='#C8E3FF'></td>";

            echo "<td bgcolor='#C8E3FF'><b>".$totalWt."</b></td>";  

        echo "<td  bgcolor='#C8E3FF'></td>";
        echo "</tr>";
        echo "<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>";


echo "</tbody></table>";
?>