<?php
$params = array();

//Declare array of inputs for query
$params = array();


//write query based on search
$query = "SELECT DISTINCT Novae.name, Novae.type, Novae.dateExploded, Novae.dateExplodedRef, Novae.distance, Novae.distRef FROM Fits LEFT JOIN Observations ON Fits.obsID = Observations.obsID LEFT JOIN Novae on Observations.name= Novae.name WHERE ";

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

//flux search
if ($_GET['fluxMin'] != ""){
    $query = $query . " AND flux >= :fluxMin";
    $params[':fluxMin'] = $_GET["fluxMin"];
}
if ($_GET['fluxMax'] != ""){
    $query = $query . " AND flux <= :fluxMax";
    $params['"fluxMax'] = $_GET["fluxMax"];
}
try {
    $stmt = $conn -> prepare($query);
    $stmt -> execute($params);
    $novae = $stmt -> fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e -> getMessage();
}

$observations = array();
foreach ($result as $key=>$row){
    $observations[$row['name']][]=$row;
}


?>

<table style='white-space:nowrap;table-layout:fixed'>
<tr>
<th>Name</th>
<th>Type</th>
<th>Date Exploded</th>
<th>Distance</th>
<th>Distance Reference</th>
<th>Date Reference</th>
</tr>

<?php
foreach ($novae as $row) {
?>
<tr>
<td> <?php echo $row['name']; ?> </td>
<td> <?php echo $row['type']; ?> </td>
<td> <?php echo removeZeros($row['dateExploded'],0);?></td>
<td> <?php echo removeZeros($row['distance'],2); ?></td>
<td> <?php echo refLink($row['distRef']) ?></td>
<td> <?php echo refLink($row['dateExplodedRef']) ?></td>
</tr>
<tr>
<td colspan=9>
<a href="javascript:flipDiv('<?php echo $row['name']; ?>')">
Click to show/hide details.
</a>


<div id = '<?php echo $row['name'];?>' style="display:none;" >
<table style='white-space:nowrap;table-layout:fixed'>
<tr>
<th>Observation ID</th>
<th>Date Observed</th>
<th>Age</th>
<th>Instrument</th>
<th>Flux</th>
<th>Flux Energy Range</th>
<th>Luminosity</th>
<th>Model</th>
<th>Flux Reference</th>
<th>Observation Reference</th>
</tr>
<?php
foreach($observations[$row['name']] as $obs){ 
?>
<tr>
<td> <?php echo $obs['obsID'];?></td>
<td> <?php echo $obs['dateObserved'];?></td>
<td> <?php echo getAge($obs['dateObserved'],$obs['dateExploded']);?> </td>
<td> <?php echo $obs['instrument'];?></td>
<td><?php echo removeZeros($obs['flux'],getPrecision($obs['fluxErrL'])); ?>
<span class='supsub'>
<sup class = 'superscript'>+<?php echo removeZeros($obs['fluxErrH'],getPrecision($obs['fluxErrH'])); ?></sup>
<sub class = 'subscript'>-<?php echo removeZeros($obs['fluxErrL'],getPrecision($obs['fluxErrL'])); ?></sub>
</span>
</td>
<td> <?php echo $obs['fluxEnergyL'].' - '.$obs['fluxEnergyH']; ?></td>
<td> <?php echo $obs['lum']; ?></td>
<td> <?php echo $obs['model']; ?></td>
<td> <?php echo refLink($obs['fluxRef']) ?></td>
<td> <?php echo refLink($obs['dateObservedRef']) ?></td>

</tr>
<?php 
} 
?>
</table>
</div>
</td>
</tr>
<?php
}
?>
</table>

<script>
function hideDiv(d){
    document.getElementById(d).style.display="none";

}
function flipDiv(d){
    if (document.getElementById(d).style.display=="none"){
        document.getElementById(d).style.display="block";
    } else{ 
        document.getElementById(d).style.display="none";
    }
}
</script>

