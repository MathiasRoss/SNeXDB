<?php
//This file loads way more into memory than necessary
//In case of slowdowns, may become necessary to add separate queries
include 'header.php';
include 'calculations.php';
include 'tables.php';
?>

<form enctype='multipart/form-data' method ="post" action="updateFromFile.php">
<input type="file" name="userfile" >
<input type="text" name="uploadSet">
<input type="hidden" name="MAX_FILE_SIZE" value="30000" >
<input type="submit" value="modify database">

</form>




<?php
include 'connect.php';

try {
    $stmt = $conn-> query("SELECT * From Novae");
    $oldNovae = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT * FROM Observations");
    $oldObs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e){
    echo $e->getMessage();
}
$names = array();
$types = array();


//change the array keys to the natural ones for ease of search:
foreach($oldNovae as $key=>$row){
    $oldNovae[$row['name']]=$row;
    unset($oldNovae[$key]);

    if (!in_array($row['name'], $names)){
        $names[] = $row['name'];
    }
    if (!in_array($row['type'], $types)){
        $types[] = $row['type'];
    }
}

foreach($oldObs as $key=>$row){
    $oldObs[$row['obsID']]=$row;
//    unset($oldObs[$key]);//commented because $oldObs was being created with correct ids, and this was just unsetting them.
}


//prepare arrays for the new values
$novae = array();
$observations = array();
$newNames = array();
$newTypes = array();
$modelParams = array();
$newObs = array();
$newFits = array();


$knownFields = array('name','type','dateExploded','dateExplodedRef','distance','distRef','obsID','fitsID','localObsID','localFitsID','dateObservedRef','instrument','dateObserved','flux','fluxErrL','fluxErrH','fluxEnergyL','fluxEnergyH','model','fluxRef');



//Loops through each row in the file, creating an associative array $result with all the new information
$result = $fields = array();
$i = 0;
if (($handle = fopen($_FILES['userfile']['tmp_name'],"r")) !== false) {
    while (($row = fgetcsv($handle, 1000, ",")) !== false) {
        if (empty($fields)){ //set $fields array to the first row, to be used as keys in $result
            $fields = $row;
            continue;
        }

//set the values of $result, using keys from $fields
        foreach ($row as $key=>$value) {
            $result[$i][$fields[$key]] = $value; 
//create an array of parameters 
            if ((!in_array($fields[$key],$knownFields)) &&(!empty($value))){
                $modelParams[$i][$fields[$key]] = $value;
            }
        }





//check for an observation id, in case adding to existing observation
        if (!empty($result[$i]['obsID'])){
            $result[$i]['name']=$oldObs[$result[$i]['obsID']]['name'];
            $result[$i]['dateObserved']=$oldObs[$result[$i]['obsID']]['dateObserved'];
            $result[$i]['dateObservedRef']=$oldObs[$result[$i]['obsID']]['dateObservedRef'];
            $result[$i]['instrument']=$oldObs[$result[$i]['obsID']]['instrument'];
        } else if ((!in_array($result[$i]['localObsID'],$newObs))&&(!empty($result[$i]['dateObserved']))){//record new observation info for display
            $observations[]=$result[$i];//has excess info
            if (!empty($result[$i]['localObsID'])){
                $newObs[] = $result[$i]['localObsID'];
            }
        }




//check for new novae included in the file, fill $novae with the new nova information
        if ((!in_array($result[$i]['name'], $names)) && !in_array($result[$i]['name'], $newNames)) {
            $newNames[] = $result[$i]['name'];
            $novae[$result[$i]['name']] = $result[$i];//has some excess info
        }

//checks for new types
        if (!in_array($result[$i]['type'], $types)) {
            $newTypes[] = $result[$i]['type'];
        }




        $i++;
    }
    fclose($handle);
}
echo 'New novae to be added: <br>';
include 'novaeTable.php';
echo 'New observations to be added: <br>';
include 'obsTable.php';
echo 'New analysis and measurements to be added: <br>';
$novae = array_merge($novae,$oldNovae);
//fill in missing information and remove rows that aren't for new fits
foreach ($result as $key=>$row){
    if (empty($result[$key]['flux'])){
        unset($result[$key]);
        continue;
    }
    if (empty($result[$key]['type'])){
        $result[$key]['type'] = $novae[$row['name']]['type'];
    }
    if (empty($result[$key]['dateExploded'])){
        $result[$key]['dateExploded'] = $novae[$row['name']]['dateExploded'];
    }
    if (empty($result[$key]['dateExplodedRef'])){
        $result[$key]['dateExplodedRef'] = $novae[$row['name']]['dateExplodedRef'];
    }

    if (empty($result[$key]['distance'])){
        $result[$key]['distance'] = $novae[$row['name']]['distance'];
    }
    if (empty($result[$key]['distRef'])){
        $result[$key]['distRef'] = $novae[$row['name']]['distRef'];
    }
    $result[$key]['lum']=getLum($result[$key]['distance'],$result[$key]['flux']);
    $result[$key]['age']=getAge($result[$key]['dateObserved'],$result[$key]['dateExploded']);
    $result[$key]['obsID'] = $key;

}
include 'processResults.php';
detailsTable($result);
foreach ($modelParams as $key=>$paramTableArray) {
    echo 'paramaters associated with '.$key.':';
    include 'modelTable.php';
}


dispPeakMem();


include 'footer.php';

?>
