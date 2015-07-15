<?php

function getPrecision($error)
{
    return strlen(substr(strrchr(trim($error),'.'),1));
}


function removeZeros($value,$precision){
    return round($value*pow(10,$precision))/pow(10,$precision);
}

function refLink2($ref){
    $link = "<a href=\"http://adsabs.harvard.edu/abs/". $ref ."\">".$ref."</a>";
    return $link;
}


function insertTable($table){
    echo "<table style='white-space:nowrap;table-layout:fixed'><tr><th>Name</th><th>Type</th><th>Date Exploded</th><th>Distance</th><th>Date Observed</th><th>Instrument</th><th>Flux</th><th>Flux Energy Range</th><th>Flux Model</th><th>Luminosity</th><th>Reference</th></tr>";
   foreach ($table as $row){
        echo "<tr><td>".$row['name']."</td><td>";
        echo $row['type']."</td><td>";
        echo removeZeros($row['dateExploded'],0)."</td><td>";
        echo removeZeros($row['distance'],2)."</td><td>";
        echo $row['dateObserved']."</td><td>";
        echo $row['instrument']."</td><td>".removeZeros($row['flux'],getPrecision($row['fluxErrL']));
        echo "</td><td>".$row['fluxEnergyL'].' - '.$row['fluxEnergyH']."</td><td>";
        echo $row['model']."</td><td>";
        echo $row['lum']."</td><td>";
        echo refLink2($row['fluxRef'])."</td></tr>";
    }
    echo "</table>";
}
?>
