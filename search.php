<?php
//Declare array of inputs for query
$params = array();


//write query based on search
$query = "SELECT * FROM Fits LEFT JOIN Observations ON Fits.obsID = Observations.obsID LEFT JOIN Novae on Observations.name= Novae.name WHERE ";

//object name search
if ($_GET["objid"] != ""){
    $query = $query . "Observations.name = :name";
    $params[':name'] = $_GET["objid"];
}
else {//note: without elif could cause unnecessary performance drops; here for "where blank search" bug
    $query = $query . "Observations.name LIKE CONCAT('%',:name,'%')";
    $params[':name'] = $_GET["name"];
}

//object type search
if ($_GET["typeid"] != ""){
    $query = $query. " AND Novae.type = :type";
    $params[':type'] = $_GET["typeid"];
}
else if ($_GET["type"] != ""){
    $query = $query." AND Novae.type LIKE CONCAT('%',:type,'%')";
    $params[':type'] = $_GET["type"];
}

//instrument search
if ($_GET['instrumentid'] != ""){
    $query = $query."AND Observations.instrument = :instrument";
    $params[':instrument'] = $_GET['instrumentid'];
}
else if ($_GET['instrument'] != ""){
    $query = $query."AND Observations.instrument LIKE CONCAT('%',:instrument,'%')";
    $params[':instrument'] = $_GET['instrument'];
}


//flux search
if ($_GET['fluxMin'] != ""){
    $query = $query . " AND flux >= :fluxMin";
    $params[':fluxMin'] = $_GET["fluxMin"];
}
if ($_GET['fluxMax'] != ""){
    $query = $query . " AND flux <= :fluxMax";
    $params['"fluxMax'] = $_GET["fluxMax"];
}


$jsonTable = array();

//run the query and create JSON table
try {
    $stmt = $conn -> prepare($query);
    $stmt -> execute($params);
    $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $jsonTable[] = $row;
} 
catch(PDOException $e) {
    echo $e -> getMessage();
}

$novae=array();

foreach($result as $key => $row){
//calculate age and luminosity
    $result[$key]['age']=getAge($row['dateObserved'],$row['dateExploded']);

    $result[$key]['lum'] = getLum($row['distance'],$row['flux']);
    $result[$key]['lumErr'] = getLumErr($result[$key]['lum'],$row['flux'],$row['fluxErrL']);

//fix the magnitudes and misleading digits
    $lumErrMag = floor(log10($result[$key]['lumErr']));
    $result[$key]['lum'] = round($result[$key]['lum']/(pow(10,$lumErrMag)))*pow(10,$lumErrMag);
    $result[$key]['lum'] = $result[$key]['lum']*pow(10,-37);
    $result[$key]['lumErr']=round($result[$key]['lumErr']/(pow(10,$lumErrMag)))*pow(10,$lumErrMag);
    $result[$key]['lumErr']=$result[$key]['lumErr']*pow(10,-37);
    

//check to see if within lum search
    if ($_GET['lumMin'] != "" and $_GET['lumMin']>= $result[$key]['lum']){
        unset($result[$key]);
        continue;
    }
    if ($_GET['lumMax'] != "" and $_GET['lumMax']<= $result[$key]['lum']){
        unset($result[$key]);
        continue;
    }
 



//remove excess zeros, using uncertainty values where available
    $result[$key]['flux']=removeZeros($row['flux'],getPrecision($row['fluxErrL']));
    $result[$key]['dateObserved']=removeZeros($row['dateObserved'],0);
    $result[$key]['fluxEnergyL']=removeZeros($row['fluxEnergyL'],getPrecision($row['fluxEnergyL']));
    $result[$key]['fluxEnergyH']=removeZeros($row['fluxEnergyH'],getPrecision($row['fluxEnergyH']));

    if (empty($novae[$row['name']])){
        $novae[$row['name']]['type']=$result[$key]['type'];
        $novae[$row['name']]['dateExploded']=$result[$key]['dateExploded']; 
        $novae[$row['name']]['distance']=$result[$key]['distance'];
        $novae[$row['name']]['distRef']=$result[$key]['distRef'];
        $novae[$row['name']]['dateExplodedRef']=$result[$key]['dateExplodedRef'];
    }
    $observations[$row['name']][]=$result[$key];

}



$jsonTable = json_encode($result);


?>
