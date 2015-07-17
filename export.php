<?php
include 'connect.php';
include 'calculations.php';
include 'search.php';

$string = " \"obsID\", \"name\", \"type\",\"dateExploded\",\"distance\", \"age\", \"dateObserved\", \"instrument\", \"flux\", \"fluxErrL\",\"fluxErrH\",\"fluxEnergyL\",\"fluxEnergyH\",\"model\",\"lum\", \"fluxRef\",\"dateObservedRef\",\"dateExplodedRef\",\"distRef\" \n";
foreach($result as $value){
    $string = $string . $value["obsID"] .','. $value["name"] . ",". $value["type"] . "," . $value['dateExploded'].','.$value['distance'].$value['age'] . "," . $value['dateObserved'].','.$value['instrument'].','.$value["flux"] . ",". $value['fluxErrL'].','.$value['fluxErrH'].','.$value['fluxEnergyL'].','.$value['fluxEnergyH'].','.$value['model'].','.$value["lum"] . ','.$value['fluxRef'].','.$value['dateObservedRef'].','.$value['dateExplodedRef'].','.$value['distRef']."\n";  
}
header('content-type: text/csv');
header('content-disposition: attachment; filename="SNeXDB.csv"');
header("content-length: " . mb_strlen($string));
header("Connection: close");
print $string;
exit();


$conn = null;
?>
