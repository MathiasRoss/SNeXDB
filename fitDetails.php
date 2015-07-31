<?php
include 'connect.php';
include 'header.php';



try {
    $stmt = $conn->prepare("SELECT parameter, value FROM Parameters WHERE fitsID=:fitsID");
    $stmt -> bindValue(':fitsID', $_GET['fitsID']);
    $stmt -> execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $paramTableArray[$row['parameter']] = $row['value'];
    }
}
catch (PDOException $e) {
    echo $e -> getMessage();
}
include 'modelTable.php';
include 'footer.php';

?>
