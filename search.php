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
} 
catch(PDOException $e) {
    echo $e -> getMessage();
}

include 'processResults.php';

?>
