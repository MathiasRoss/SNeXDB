<?php


echo "<table style='white-space:nowrap;table-layout:fixed'><tr><th>Name</th><th>Type</th><th>Date Exploded</th><th>Distance</th><th>Date Observed</th><th>Instrument</th><th>Flux</th><th>Flux Energy Range</th><th>Flux Model</th><th>Luminosity</th><th>Reference</th></tr>";
foreach ($result as $row){
    echo "<tr><td>".$row['name']."</td><td>";
    echo $row['type']."</td><td>";
    echo removeZeros($row['dateExploded'],0)."</td><td>";
    echo removeZeros($row['distance'],2)."</td><td>";
    echo $row['dateObserved']."</td><td>";
    echo $row['instrument']."</td><td>".removeZeros($row['flux'],getPrecision($row['fluxErrL']));
    echo "</td><td>".$row['fluxEnergyL'].' - '.$row['fluxEnergyH']."</td><td>";
    echo $row['model']."</td><td>";
    echo $row['lum']."</td><td>";
    echo refLink($row['fluxRef'])."</td></tr>";
}
echo "</table>";


?>
