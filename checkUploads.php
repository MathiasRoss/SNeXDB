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
    $stmt = $conn->query("SELECT * FROM FitsNew LEFT JOIN ObservationsNew ON FitsNew.obsID = ObservationsNew.obsID LEFT JOIN NovaeNew ON ObservationsNew.name= NovaeNew.name");
    $stmt->execute();
    $result = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e -> getMessage();
}

include 'processResults.php';
include 'table.php';
?>
