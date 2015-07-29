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
if (($handle = fopen($_FILES['userfile']['tmp_name'],"r")) !== false) {
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        if (empty($fields)){ //set $fields array to the first row, to be used as keys
            $fields = $row;
            continue;
        }
//set the values of $result, using keys from $fields, to make the 2d associative array from the csv
        foreach ($row as $key=>$value) {          
            $result[$i][$fields[$key]] = $value;
//copy into a paramater array and then remove unneeded values; need to find better way
            $modelParams[$i][$fields[$key]] = $value;
            unset($modelParams[$i]['name']);
            unset($modelParams[$i]['type']);
            unset($modelParams[$i]['dateExploded']);
            unset($modelParams[$i]['dateExplodedRef']);
            unset($modelParams[$i]['distance']);
            unset($modelParams[$i]['distRef']);
            unset($modelParams[$i]['obsID']);
            unset($modelParams[$i]['dateObservedRef']);
            unset($modelParams[$i]['instrument']);
            unset($modelParams[$i]['dateObserved']);
            unset($modelParams[$i]['flux']);
            unset($modelParams[$i]['fluxErrL']);
            unset($modelParams[$i]['fluxErrH']);
            unset($modelParams[$i]['fluxEnergyL']);
            unset($modelParams[$i]['fluxEnergyH']);
            unset($modelParams[$i]['fluxRef']);
            unset($modelParams[$i]['model']);
            foreach($modelParams[$i] as $param=>$paramValue){
                if (empty($paramValue)){
                     unset($modelParams[$i][$param]);
                }
            }
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
                $result[$i]['fitsID']=$conn->lastInsertId();
            }
            catch (PDOException $e) {
                echo $e -> getMessage();
            }
        }

//check for new model information, and add each row to Parameters
        foreach($modelParams[$i] as $param=>$paramValue){
            try {
                $stmt = $conn -> prepare("INSERT INTO ParametersNew(fitsID, parameter, value) VALUES(:fitsID, :parameter, :value)");
                $stmt -> bindValue(':fitsID',$result[$i]['fitsID']);
                $stmt -> bindValue(':parameter',$param);
                $stmt -> bindValue(':value',$paramValue);
                $stmt -> execute();
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
