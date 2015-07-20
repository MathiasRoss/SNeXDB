<?php
include 'connect.php';
include 'calculations.php';
include 'search.php';

switch($_GET['exportType']){
    case 'CSV':
        $sep = ',';
        break;
    case 'TSV':
        $sep = "\t";
        break;
}


$string = "\"obsID\"".$sep." \"name\"".$sep." \"type\"".$sep."\"dateExploded\"".$sep."\"distance\"".$sep." \"age\"".$sep." \"dateObserved\"".$sep." \"instrument\"".$sep." \"flux\"".$sep." \"fluxErrL\"".$sep."\"fluxErrH\"".$sep."\"fluxEnergyL\"".$sep."\"fluxEnergyH\"".$sep."\"model\"".$sep."\"lum\"".$sep." \"fluxRef\"".$sep."\"dateObservedRef\"".$sep."\"dateExplodedRef\"".$sep."\"distRef\" \n";
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
