<?php
include 'connect.php';
include 'header.php';

$params=array();
$params[':fitsID']=$_POST['fitsID'];
$params[':flux']=$_POST['flux'];
$params[':fluxErrL']=$_POST['fluxErrL'];
$params[':fluxErrH']=$_POST['fluxErrH'];
$params[':fluxEnergyL']=$_POST['fluxEnergyL'];
$params[':fluxEnergyH']=$_POST['fluxEnergyH'];
$params[':fluxRef']=$_POST['fluxRef'];
if ($_POST['model'] != 'new'){
    $params[':model'] = $_POST['model'];
}
else {
    $params[':model']=$_POST['newmodel'];
}
try{
    $stmt = $conn->prepare("UPDATE Fits SET flux=:flux, fluxErrL=:fluxErrL, fluxErrH=:fluxErrH, fluxEnergyL=:fluxEnergyL, fluxEnergyH=:fluxEnergyH, model=:model, fluxRef=:fluxRef WHERE fitsID=:fitsID");
    $stmt->execute($params);
}
catch (PDOException $e){
    echo $e->getMessage();
}
try {
    $stmt = $conn->prepare("SELECT parameter FROM Parameters WHERE fitsID=:fitsID");
    $stmt->bindValue(':fitsID',$_POST['fitsID']);
    $stmt->execute();
    $parameters = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e){
    echo $e->getMessage();
}
foreach($parameters as $param)
{
    $values=array();
    $values[':fitsID'] = $_POST['fitsID'];
    $values[':parameter'] = $param['parameter'];
    $values[':newParameter'] = $_POST[$param['parameter']];
    $values[':value'] = $_POST[$param['parameter'].'Value'];
    try{
        $stmt = $conn->prepare("UPDATE Parameters SET parameter=:newParameter, value=:value WHERE parameter=:parameter AND fitsID=:fitsID");
        $stmt->execute($values);
    }
    catch (PDOException $e){
        echo $e->getMessage();
    }
}

include 'footer.php';
?>
