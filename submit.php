<?php
include 'connect.php';


//get menu information from database
try {
    $stmt = $conn->query("SELECT DISTINCT name FROM Novae");
    $names = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT type FROM Novae");
    $types = $stmt-> fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e->getMessage();
}





?>


<!-- Begin New Observation form -->
<form method = "post" action = "checkNewObs.php">

<select name="name">
<option selected = "selected" value = "">Object Name</option>

<?php
//Turn result into an associative array and loop, creating html code
foreach($names as $name){ 
    echo  "<option>" . $name['name'] . "</option>";
}
?>
</select>

<input type="text" name="instrument" placeholder = "instrument used" required>
<input type="text" name="flux" placeholder = "flux" required>
<input type="text" name="fluxErrL" placeholder="flux error lower" >
<input type="text" name="fluxErrH" placeholder="flux error upper; leave blank if same">
<input type="text" name="fluxRef" placeholder = "Reference">
<input type="text" name="dateObserved" placeholder = "Date Observed (MJD)">
<input type="text" name="dateObservedRef" placeholder = "Date Observed Reference">
<input type="text" name="fluxEnergyL" placeholder="lower bound flux energy" >
<input type="text" name="fluxEnergyH" placeholder="upper bound flux energy">
<input type="text" name="model" placeholder="model used">
<br>
<input type="submit">
<br>

</form>
<br>

<!-- Begin new nova form -->
<form method="post" action = "checkNewNova.php">
<input type="text" name="name" placeholder="name" required>
<input type="text" name="distance" placeholder="distance">
<input type="text" name="distRef" placeholder="distance reference">
<input type="text" name="dateExploded" placeholder="explosion date">
<input type="text" name="dateExplodedRef" placeholder="date reference">
 


<select name="typeid">
<option selected = "selected" value = "">New Type</option>

<?php
foreach($types as $type){
    echo "<option>" . $type['type'] . "</option>";
}
?>
</select>
<input type="text" name="type" placeholder="type">
<br>
<input type="submit">
</form>



<!-- Begin new fit form -->
<form method="post" action = "checkNewFit.php">


</form>


<?php
$conn -> close();
?>

