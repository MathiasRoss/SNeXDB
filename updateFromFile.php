<?php
include 'connect.php';
include 'header.php';
try {
    $stmt = $conn-> query("SELECT name FROM Novae");
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
$newObs = array();
$newFits = array();

$knownFields = array('redshift', 'redishiftRef','name','type','dateExploded','dateExplodedRef','distance','distRef','obsID','fitsID','localObsID','localFitsID','dateObservedRef','instrument','dateObserved','flux','fluxErrL','fluxErrH','fluxEnergyL','fluxEnergyH','model','fluxRef');


$i = 0;
if (($handle = fopen($_FILES['userfile']['tmp_name'],"r")) !== false) {
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        if (empty($fields)){ //set $fields array to the first row, to be used as keys
            $fields = $row;
            continue;
        }
//set the values of $result, using keys from $fields, to make the 2d associative array from the csv
        $modelParams[$i]=array();
        foreach ($row as $key=>$value) {          
            $result[$i][$fields[$key]] = $value;
//copy into a paramater array and then remove unneeded values
            if ((!in_array($fields[$key],$knownFields)) &&(!empty($value))){
                $modelParams[$i][$fields[$key]] = $value;
            }
        }

//check for new novae included in the file, add new information to Novae
        if (!in_array($result[$i]['name'], $names) && (!empty($result[$i]['name']))) {
            beep();
            $names[] = $result[$i]['name'];
            try{
                $stmt = $conn -> prepare('INSERT INTO NovaeNew(name, type, dateExploded,dateExplodedRef,distance,distRef,uploadSet,redshift,redshiftRef) VALUES(:name,:type,:dateExploded,:dateExplodedRef,:distance,:distRef,:uploadSet, :redshift, :redshiftRef)');
                $params = array();
                $params[':uploadSet']=$_POST['uploadSet'];
                $params[':name'] = $result[$i]['name'];
                $params[':type'] = $result[$i]['type'];
                $params[':dateExploded'] = $result[$i]['dateExploded'];
                $params[':dateExplodedRef'] = $result[$i]['dateExplodedRef'];
                $params[':distance'] = $result[$i]['distance'];
                $params[':distRef'] = $result[$i]['distRef'];
                $params[':redshift'] = $result[$i]['redshift'];
                $params[':redshiftRef'] = $result[$i]['redshiftRef'];
                $stmt -> execute($params);
            } 
            catch (PDOException $e) {
                echo $e -> getMessage();
            }
        }
//check for new observation, and add it to Observations
        if (empty($result[$i]['obsID'])&&(!empty($result[$i]['instrument']))&&(!in_array($result[$i]['localObsID'],$newObs))) {
            try{
                $stmt = $conn -> prepare("INSERT INTO ObservationsNew(name, dateObserved, dateObservedRef, instrument, uploadSet) VALUES(:name,:dateObserved,:dateObservedRef,:instrument, :uploadSet)");
                $params = array();
                $params[':name'] = $result[$i]['name'];
                $params[':uploadSet']=$_POST['uploadSet'];
                $params[':dateObserved'] = $result[$i]['dateObserved'];
                $params[':dateObservedRef'] = $result[$i]['dateObservedRef'];
                $params[':instrument'] = $result[$i]['instrument'];
                $stmt -> execute($params);
                $result[$i]['newObsID']=$conn->lastInsertId();
            }
            catch (PDOException $e) {
                echo $e -> getMessage();
            }
            if(!empty($result[$i]['localObsID'])){
                $newObs[$result[$i]['newObsID']] = $result[$i]['localObsID'];
            }
        }
//check for new flux measurements, and add it to Fits
        if ((empty($result[$i]['fitsID']))&&(!empty($result[$i]['flux']))&&(!in_array($result[$i]['localFitsID'],$newFits))){
            if (!empty($result[$i]['localObsID'])){
                $flipped=array_flip($newObs);
                $result[$i]['newObsID']=$flipped[$result[$i]['localObsID']];
                unset($flipped);
            }
            try {
                $stmt = $conn -> prepare("INSERT INTO FitsNew(obsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model, uploadSet, newObsID) VALUES(:obsID, :flux, :fluxErrL, :fluxErrH, :fluxEnergyL, :fluxEnergyH, :fluxRef, :model, :uploadSet, :newObsID)");
                $params = array();
                $params[':obsID'] = $result[$i]['obsID'];
                $params[':newObsID']=$result[$i]['newObsID'];
                $params[':uploadSet']=$_POST['uploadSet'];
                $params[':flux'] = $result[$i]['flux'];
                $params[':fluxErrL'] = $result[$i]['fluxErrL'];
                $params[':fluxErrH'] = $result[$i]['fluxErrH'];
                $params[':fluxEnergyL'] = $result[$i]['fluxEnergyL'];
                $params[':fluxEnergyH'] = $result[$i]['fluxEnergyH'];
                $params[':fluxRef'] = $result[$i]['fluxRef'];
                $params[':model'] = $result[$i]['model'];
                $stmt -> execute($params);
                $result[$i]['newFitsID']=$conn->lastInsertId();
            }
            catch (PDOException $e) {
                echo $e -> getMessage();
            }
            if (!empty($result[$i]['localFitsID'])){
                $newFits[$result[$i]['newFitsID']] = $result[$i]['localFitsID'];
            }
        }
        

//check for new model information, and add each row to Parameters
        foreach($modelParams[$i] as $param=>$paramValue){
            if(!empty($result[$i]['localFitsID'])){
                $flipped = array_flip($newFits);
                $result[$i]['newFitsID']=$flipped[$result[$i]['localFitsID']];
                unset($flipped);
            }
            if(empty($result[$i]['fitsID'])){
                $result[$i]['fitsID']=0;
            }
            if(empty($result[$i]['newFitsID'])){
//                $result[$i]['newFitsID']=0;
            }
            try {
                $stmt = $conn -> prepare("INSERT INTO ParametersNew(fitsID, parameter, value, newFitsID, uploadSet) VALUES(:fitsID, :parameter, :value, :newFitsID, :uploadSet)");
                $stmt -> bindValue(':fitsID',$result[$i]['fitsID']);
                $stmt -> bindValue(':newFitsID',$result[$i]['newFitsID']);
                $stmt -> bindValue(':parameter',$param);
                $stmt -> bindValue(':value',$paramValue);
                $stmt -> bindValue(':uploadSet', $_POST['uploadSet']);
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
include 'footer.php';
?>
