<?php
include 'debugFunc.php';   
include 'connect.php';
include 'header.php';

//Novae update
try{
    $stmt = $conn->query("SELECT name, type, dateExploded, dateExplodedRef, distance, distRef, uploadSet FROM NovaeNew");
    $novae = $stmt->fetchAll(PDO::FETCH_ASSOC);
     

} 
catch (PDOException $e) {
    echo $e -> getMessage();
}

foreach($novae as $key=>$nova) {
    try{
        $stmt = $conn->prepare("INSERT INTO Novae(name, type, dateExploded, dateExplodedRef, distance, distRef, uploadSet) VALUES(:name, :type, :dateExploded, :dateExplodedRef, :distance, :distRef, :uploadSet)");
        $params = array();
        $params[':name'] = $nova['name'];
        $params[':type'] = $nova['type'];
        $params[':dateExploded'] = $nova['dateExploded'];
        $params[':dateExplodedRef'] = $nova['dateExplodedRef'];
        $params[':distance'] = $nova['distance'];
        $params[':distRef'] = $nova['distRef'];
        $params[':uploadSet'] = $nova['uploadSet'];
        $stmt -> execute($nova);
    }
    catch (PDOException $e) {
        echo $e -> getMessage();
    }
}

//Observations Update
try{
    $stmt = $conn->query("SELECT name, dateObserved, dateObservedRef, instrument, uploadSet, newObsID FROM ObservationsNew");
    $observations = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e){
    echo $e -> getMessage();
} 

$newObsIDs = array();
foreach ($observations as $obs) {
    try{
        $stmt = $conn -> prepare("INSERT INTO Observations(name, dateObserved, dateObservedRef, instrument, uploadSet) VALUES(:name,:dateObserved,:dateObservedRef,:instrument, :uploadSet)");
        $params = array();
        $params[':name'] = $obs['name'];
        $params[':uploadSet']=$obs['uploadSet'];
        $params[':dateObserved'] = $obs['dateObserved'];
        $params[':dateObservedRef'] = $obs['dateObservedRef'];
        $params[':instrument'] = $obs['instrument'];
        $stmt -> execute($params);
        $newObsIDs[$obs['newObsID']] = $conn->lastInsertID();
    }
    catch (PDOException $e) {
        echo $e -> getMessage();
    }
}

//fits update
try {
    $stmt = $conn -> query("SELECT fitsID, obsID, newObsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model, uploadSet FROM FitsNew");
    $fits = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    echo $e->getMessage();
}

$newFitsIDs = array();
foreach($fits as $key=> $fit){
    try {
        $stmt = $conn -> prepare("INSERT INTO Fits(obsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model, uploadSet) VALUES(:obsID, :flux, :fluxErrL, :fluxErrH, :fluxEnergyL, :fluxEnergyH, :fluxRef, :model, :uploadSet)");
        $params = array();
        if (!empty($fit['obsID'])){$params[':obsID'] = $fit['obsID'];}
        else {$params[':obsID'] = $newObsIDs[$fit['newObsID']];}
        $params[':uploadSet']=$fit['uploadSet'];
        $params[':flux'] = $fit['flux'];
        $params[':fluxErrL'] = $fit['fluxErrL'];
        $params[':fluxErrH'] = $fit['fluxErrH'];
        $params[':fluxEnergyL'] = $fit['fluxEnergyL'];
        $params[':fluxEnergyH'] = $fit['fluxEnergyH'];
        $params[':fluxRef'] = $fit['fluxRef'];
        $params[':model'] = $fit['model'];
        $stmt -> execute($params);
        $newFitsIDs[$fit['fitsID']]=$conn->lastInsertId();
    }
    catch (PDOException $e) {
        echo $e -> getMessage();
    }
}


//Add Parameters
try {
    $stmt = $conn -> query("SELECT fitsID, parameter, value, newFitsID FROM ParametersNew");
    $parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    echo $e -> getMessage();
}

foreach($parameters as $key=>$value){
    try {
        $stmt = $conn -> prepare("INSERT INTO ParametersNew(fitsID, parameter, value) VALUES(:fitsID, :parameter, :value)");
        $params = array();
        if (!empty($value['fitsID'])){$params[':fitsID'] = $value['fitID'];}
        else{$params[':fitsID'] = $newFitsIDs[$value['newFitsID']];}
        $params[':parameter'] = $value['parameter'];
        $params[':value'] = $value['value'];
        $stmt ->execute($params);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

}


include 'footer.php';
?>
