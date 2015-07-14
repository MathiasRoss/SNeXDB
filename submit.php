<?php
include 'config.php';

//connect
$conn = new mysqli($servername, $username, $password, $dbname) or die('Could not connect to database: '. $conn -> connect_error);

?>


<!-- Begin New Observation form -->
<form method = "post" action = "checkNewObs.php">

<select name="name">
<option selected = "selected" value = "">Object Name</option>

<?php
//Query to get object names
$query = "SELECT name FROM Novae";

$names = $conn -> query($query);


//Turn result into an associative array and loop, creating html code
while($name = mysqli_fetch_array($names)){ 
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
$query = "SELECT type FROM Novae";

$types = $conn -> query($query);

while($type = mysqli_fetch_array($types)){
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

