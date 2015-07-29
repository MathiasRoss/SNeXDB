<?php
include 'debugFunc.php';
include 'calculations.php';
include 'header.php';
include 'connect.php';
?>
<body>
<?
include 'nav.php';
?>
<div id = 'content'>
<?php
try {
    $stmt = $conn->query("SELECT * FROM Fits LEFT JOIN Observations ON Fits.obsID = Observations.obsID LEFT JOIN Novae ON Observations.name= Novae.name");
    $stmt->execute();
    $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e -> getMessage();
}

include 'processResults.php';
include 'table.php';
?>
