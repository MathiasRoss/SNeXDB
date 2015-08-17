<?php
//Declare array of inputs for query
$params = array();


//write query based on search
$query = "SELECT Novae.redshift, Novae.redshiftErr, Novae.redshiftRef, Novae.name,type,dateExploded,dateExplodedRef,distance,distRef,Observations.obsID,dateObserved,dateObservedRef,instrument,fitsID,flux,fluxErrL,fluxErrH,fluxEnergyL,fluxEnergyH,fluxRef,model, getLum(flux,distance) AS lum, (dateObserved-dateExploded) AS age FROM Fits LEFT JOIN Observations ON Fits.obsID = Observations.obsID LEFT JOIN Novae on Observations.name= Novae.name WHERE ";


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
    $params[':fluxMax'] = $_GET["fluxMax"];
}

//lum search
if ($_GET['lumMin'] != ""){
    $query = $query . "AND getLum(Fits.flux,Novae.distance) >= :lumMin";
    $params[':lumMin'] = $_GET['lumMin'];
}
if ($_GET['lumMax'] != ""){
    $query = $query . "AND getLum(Fits.flux,Novae.distance) <= :lumMax";
    $params[':lumMax'] = $_GET['lumMax'];
}

//age search
if ($_GET['ageMin'] != ""){
    $query = $query . "AND (dateObserved-dateExploded) >= :ageMin";
    $params[':ageMin'] = $_GET['ageMin'];
}
if ($_GET['ageMax'] != ""){
    $query = $query . "AND (dateObserved-dateExploded) <= :ageMax";
    $params[':ageMax'] = $_GET['ageMax'];
}



$jsonTable = array();


//sort and paginate
$query = $query." ORDER BY Novae.dateExploded, Observations.dateObserved";

$queryCount = "SELECT COUNT(*)".substr($query,315);

if (!empty($_GET['count'])){
    $query = $query.' LIMIT :start, :count';
    if (empty($_GET['page'])){
        $params[':start'] = 0;
    } 
    else {
        $params[':start']=($_GET['page']-1)*$_GET['count'];
    }
    $params[':count'] = $_GET['count'];
}



//run the query and create JSON table
try {
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $stmt = $conn -> prepare($query);
    $stmt -> execute($params);
    $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    unset($params[':count']);
    unset($params[':start']);
    $stmt = $conn -> prepare($queryCount);
    $stmt -> execute($params);
    $count = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e -> getMessage();
}
$count = current(current($count));
include 'processResults.php';

?>
