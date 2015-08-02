<?php
include 'calculations.php';
include 'tables.php';
include 'header.php';
include 'connect.php';
?>
<form action='updateDatabase.php' method="post">
<input type="submit" value="Upload">
</form>




<?php
try {
    $stmt = $conn->query("SELECT name, type, dateExploded, dateExplodedRef, distance, distRef, uploadSet FROM NovaeNew");
    $novae = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT name, dateObserved, dateObservedRef, instrument, uploadSet FROM ObservationsNew");
    $observations=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT obsID, flux, fluxErrL, fluxErrH, fluxEnergyL, fluxEnergyH, fluxRef, model, uploadSet FROM FitsNew");
    $fits = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e -> getMessage();
}
echo 'Novae in staging area: <br>';
novaeTable($novae);
echo 'Observations in staging area: <br>';
obsTable($observations);
echo 'Fits and analysis in staging area: <br>';
lazyTable($fits);
include 'footer.php';
?>
