<?php

include 'debugFunc.php';
include 'connect.php';

try {
    $stmt = $conn-> query("SELECT name From Novae");
    $names = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e){
    echo $e->getMessage();
}

foreach($names as $key=>$name){
    $names[] = $names[$key]['name'];
    unset($names[$key]);
}

$fields = array();
$result = array();

$i = 0;
if (($handle = fopen($_POST['uploadfile'],"r")) !== false) {
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        if (empty($fields)){ //set $fields array to the first row, to be used as keys
            $fields = $row;
            continue;
        }
//set the values of $result, using keys from $fields, to make the 2d associative array from the csv
        foreach ($row as $key=>$value) {          
            $result[$i][$fields[$key]] = $value;
        }

//check for new novae included in the file, add new information to Novae
        if (!in_array($result[$i]['name'], $names)&& (!empty($result[$i]['name']))) {
            $names[] = $result[$i]['name'];
            try{
                $stmt = $conn -> prepare('INSERT INTO NovaeNew(name, type, dateExploded,dateExplodedRef,distance,distRef) VALUES(:name,:type,:dateExploded,:dateExplodedRef,:distance,:distRef)');
                $params = array();
                $params[':name'] = $result[$i]['name'];
                $params[':type'] = $result[$i]['type'];
                $params[':dateExploded'] = $result[$i]['dateExploded'];
                $params[':dateExplodedRef'] = $result[$i]['dateExplodedRef'];
                $params[':distance'] = $result[$i]['distance'];
                $params[':distRef'] = $result[$i]['distRef'];
                $stmt -> execute($params);
            } 
            catch (PDOException $e) {
                echo $e -> getMessage();
            }
        }
//check for new observation, and add it to Observations
        if (empty($result[$i]['obsID'])) {
            try{
                $stmt = $conn -> prepare("INSERT INTO ObservationsNew(name, dateObserved, dateObservedRef, instrument) VALUES(:name,:dateObserved,:dateObservedRef,:instrument)");
                $params = array();
                $params[':name'] = $result[$i]['name'];
                $params[':dateObserved'] = $result[$i]['dateObserved'];
                $params[':dateObservedRef'] = $result[$i]['dateObservedRef'];
                $params[':instrument'] = $result[$i]['instrument'];
                $stmt -> execute($params);
                $result[$i]['obsID']=$conn->lastInsertId();
            }
            catch (PDOException $e) {
                echo $e -> getMessage();
            }
        }
//check for new flux measurements, and add it to Fits
        if (!empty($result[$i]['flux'])){
            try {
                $stmt = $conn -> prepare("INSERT INTO FitsNew(obsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model) VALUES(:obsID, :flux, :fluxErrL, :fluxErrH, :fluxEnergyL, :fluxEnergyH, :fluxRef, :model)");
                $params = array();
                $params[':obsID'] = $result[$i]['obsID'];
                $params[':flux'] = $result[$i]['flux'];
                $params[':fluxErrL'] = $result[$i]['fluxErrL'];
                $params[':fluxErrH'] = $result[$i]['fluxErrH'];
                $params[':fluxEnergyL'] = $result[$i]['fluxEnergyL'];
                $params[':fluxEnergyH'] = $result[$i]['fluxEnergyH'];
                $params[':fluxRef'] = $result[$i]['fluxRef'];
                $params[':model'] = $result[$i]['model'];
                $stmt -> execute($params);
            }
            catch (PDOException $e) {
                echo $e -> getMessage();
            }
        }
        $i++;
    }
    fclose($handle);
    echo "Submitted Succesfully";
}

?>
