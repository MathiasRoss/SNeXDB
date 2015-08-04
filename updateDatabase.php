<?php
include 'connect.php';
include 'header.php';

//get information from the staging area
try{
    $stmt = $conn->prepare("SELECT name, type, dateExploded, dateExplodedRef, distance, distRef, uploadSet, redshift, redshiftRef FROM NovaeNew WHERE uploadSet = :setName");
    $stmt->execute(array(':setName'=>$_POST['setName']));
    $novae = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->prepare("SELECT name, dateObserved, dateObservedRef, instrument, uploadSet, newObsID FROM ObservationsNew WHERE uploadSet=:setName");
    $stmt->execute(array(':setName'=>$_POST['setName']));
    $observations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn -> prepare("SELECT fitsID, obsID, newObsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model, uploadSet FROM FitsNew WHERE uploadSet = :setName");
    $stmt->execute(array(':setName'=>$_POST['setName']));
    $fits = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn -> prepare("SELECT fitsID, parameter, value, newFitsID, uploadSet FROM ParametersNew WHERE uploadSet = :setName");
    $stmt->execute(array(':setName'=>$_POST['setName']));
    $parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    echo $e -> getMessage();
}


$conn->beginTransaction();


//Novae update
foreach($novae as $key=>$nova) {
    try{
        $stmt = $conn->prepare("INSERT INTO Novae(name, type, dateExploded, dateExplodedRef, distance, distRef, uploadSet, redshift, redshiftRef) VALUES(:name, :type, :dateExploded, :dateExplodedRef, :distance, :distRef, :uploadSet, :redshift, :redshiftRef)");
        $params = array();
        $params[':name'] = $nova['name'];
        $params[':type'] = $nova['type'];
        $params[':dateExploded'] = $nova['dateExploded'];
        $params[':dateExplodedRef'] = $nova['dateExplodedRef'];
        $params[':distance'] = $nova['distance'];
        $params[':distRef'] = $nova['distRef'];
        $params[':uploadSet'] = $nova['uploadSet'];
        $params[':redshift'] = $nova['redshift'];
        $params[':redshiftRef'] =$nova['redshiftRef'];
        $stmt -> execute($params);
    }
    catch (PDOException $e) {
        echo $e -> getMessage();
    }
}




//Observations Update
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
foreach($parameters as $key=>$value){
    try {
        $stmt = $conn -> prepare("INSERT INTO Parameters(fitsID, parameter, value, uploadSet) VALUES(:fitsID, :parameter, :value, :uploadSet)");
        $params = array();
        if (!empty($value['fitsID'])){$params[':fitsID'] = $value['fitID'];}
        else{$params[':fitsID'] = $newFitsIDs[$value['newFitsID']];}
        $params[':parameter'] = $value['parameter'];
        $params[':value'] = $value['value'];
        $params[':uploadSet'] = $value['uploadSet'];
        $stmt ->execute($params);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }

}

//clean out staging area
try{
    $stmt =  $conn->prepare("DELETE FROM ObservationsNew WHERE uploadSet = :setName");
    $stmt->execute(array(':setName'=>$_POST['setName']));
    $stmt =  $conn->prepare("DELETE FROM NovaeNew WHERE uploadSet = :setName");
    $stmt->execute(array(':setName'=>$_POST['setName']));
    $stmt =  $conn->prepare("DELETE FROM FitsNew WHERE uploadSet = :setName");
    $stmt->execute(array(':setName'=>$_POST['setName']));
    $stmt =  $conn->prepare("DELETE FROM ParametersNew WHERE uploadSet = :setName");
    $stmt->execute(array(':setName'=>$_POST['setName']));
}
catch (PDOException $e){
    echo $e -> getMessage();
}


$conn->commit();



include 'footer.php';
?>
