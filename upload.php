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


$knownFields = array('name','redshiftErr','redshift','redshiftRef','type','dateExploded','dateExplodedRef','distance','distRef','obsID','fitsID','localObsID','localFitsID','dateObservedRef','instrument','dateObserved','flux','fluxErrL','fluxErrH','fluxEnergyL','fluxEnergyH','model','fluxRef');



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
            $result[$i]['dateExploded']=$oldNovae[$oldObs[$result[$i]['obsID']]['name']]['dateExploded'];
        }
//or if a new observation 
        else if ((!in_array($result[$i]['localObsID'],$newObs))&&(!empty($result[$i]['dateObserved']))){

            $observations[$i]=$result[$i];//has excess info; $i a conveient index
            if (!empty($result[$i]['localObsID'])){
                $newObs[$i] = $result[$i]['localObsID'];
            }
        }




//check for new novae included in the file, fill $novae with the new nova information
        if ((!in_array($result[$i]['name'], $names)) && !in_array($result[$i]['name'], $newNames) && !empty($result[$i]['type'])) {
            $newNames[] = $result[$i]['name'];
            $novae[$result[$i]['name']] = $result[$i];//has some excess info
        } 
//if from old novae, need some info for calculations:
        else if ((!in_array($result[$i]['name'],$newNames)) && !empty($result[$i]['type'])){
            $result[$i]['dateExploded']=$oldNovae[$result[$i]['name']]['dateExploded'];
            $result[$i]['distance']=$oldNovae[$result[$i]['name']]['distance'];
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
table(array('name','type','dateExploded','distance','redshift','distRef','redshiftRef','dateExplodedRef'),$novae);
echo 'New observations to be added: <br>';

$novae = array_merge($novae,$oldNovae);
foreach($observations as $key=>$row){
    $observations[$key]['age'] = $row['dateObserved']-$novae[$row['name']]['dateExploded'];
}


table(array('name','dateObserved','age','instrument','fluxRef'),$observations);
echo 'New analysis and measurements to be added: <br>';

//fill in missing information and remove rows that aren't for new fits
foreach ($result as $key=>$row){
    if (empty($result[$key]['flux'])){
        unset($result[$key]);
        continue;
    }
    if (!empty($result[$key]['localObsID'])) {
        $result[$key]['name']=$observations[array_search($result[$key]['localObsID'],$newObs)]['name'];
        $result[$key]['dateObserved']=$observations[array_search($result[$key]['localObsID'],$newObs)]['dateObserved'];
        $result[$key]['instrument']=$observations[array_search($result[$key]['localObsID'],$newObs)]['instrument'];
    }
    if (empty($result[$key]['dateExploded'])){
        $result[$key]['dateExploded'] = $novae[$result[$key]['name']]['dateExploded'];
    }
    if (empty($result[$key]['dateExplodedRef'])&& isset($observations[$key])){
        $result[$key]['dateExplodedRef'] = $novae[$observations[$key]['name']]['dateExplodedRef'];
    }

    if (empty($result[$key]['distance'])){
        $result[$key]['distance'] = $novae[$result[$key]['name']]['distance'];
    }
    if (empty($result[$key]['distRef'])){
        $result[$key]['distRef'] = $novae[$result[$key]['name']]['distRef'];
    }
    $result[$key]['lum']=getLum($result[$key]['distance'],$result[$key]['flux']);
    $result[$key]['age']=getAge($result[$key]['dateObserved'],$result[$key]['dateExploded']);
    $result[$key]['ID'] = $key;

}
//include 'processResults.php';
//detailsTable($result);
table(array('ID','name','dateObserved','age','instrument','flux','fluxEnergyL','model','fluxRef','dateObservedRef'),$result);
foreach ($modelParams as $key=>$paramTableArray) {
    echo 'paramaters associated with '.$key.':';
    include 'modelTable.php';
}




include 'footer.php';

?>
