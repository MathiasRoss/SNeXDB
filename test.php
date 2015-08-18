<?php
include 'header.php';
include 'connect.php';
include 'calculations.php';
?>


<?php
//get menu information from database
try {
    $stmt = $conn->query("SELECT DISTINCT name FROM Novae");
    $names = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT type FROM Novae");
    $types = $stmt-> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT instrument FROM Observations");
    $instruments = $stmt-> fetchAll(PDO::FETCH_ASSOC);
}
catch(PDOException $e) {
    echo $e->getMessage();
}

?>

<form method = get action ="test.php">

<!-- Object name pulldown -->
Object: <select name="objid">
<option selected ="selected" value = "">Object Name</option>

<?php
foreach($names as $name){
    echo "<option>" . $name['name'] . "</option>";
}
?>
</select>
Or name contains:
<input type="text" name = "name" >
<br>
<!-- Type search pulldown -->
Type:<select name="typeid">
<option selected = "selected" value ="">Object Type</option>

<?php
foreach($types as $type){
    echo "<option>" . $type['type'] . "</option>";
}
?>

</select>
Or type contains:
<input type="text" name="type">
<br>
<!-- Flux Magnitude Search -->
Flux is greater than
<input type="text" name="fluxMin" value=
<?php
if (isset($_GET['fluxMin'])){echo $_GET["fluxMin"];}
?> 
>
and less than
<input type="text" name="fluxMax" value=
<?php
if (isset($_GET['fluxMax'])){echo $_GET["fluxMax"];}
?>
>
(x 10<sup>-13</sup> erg cm<sup>-2</sup> s<sup>-1</sup>)
<br>



<!-- Luminosity Magnitude Search -->
Luminosity is greater than
<input type="text" name="lumMin" value=
<?php
if (isset($_GET['lumMin'])){echo $_GET["lumMin"];}
?> 
>
and less than
<input type="text" name="lumMax" value=
<?php
if (isset($_GET['lumMax'])){echo $_GET["lumMax"];}
?>
>
(x 10<sup>-37</sup> erg s<sup>-1</sup>)
<br>
<!-- Instrument name pulldown -->
Instrument:
<select name="instrumentid">
<option selected ="selected" value = "">Instrument Name</option>

<?php
foreach($instruments as $key=>$instrument){
    echo "<option>" . $instrument['instrument'] . "</option>";
}
?>
</select>
or name contains: 
<input type="text" name = "instrument" >
<br>






<!-- Age Search -->
Older than
<input type="text" name="ageMin" value=
<?php
if (isset($_GET['ageMin'])){echo $_GET["ageMin"];}
?> 
>
and younger than
<input type="text" name="ageMax" value=
<?php
if (isset($_GET['ageMax'])){echo $_GET["ageMax"];}
?>
>
(days)

<input type = "submit" value = "Search">
</form>

<?php
if (isset($_GET['objid'])){

?>


<script src="plotSource.js"></script>

<div id ='graphWrapper'>
<div id ='graphInnerWrapper'>
Age vs. Luminosity
<div id="graph"></div>
</div>
<div id = 'graphOptions'>
x min:<input type='text' id='xAxisMin'><br>
x max:<input type='text' id='xAxisMax'><br>
y min:<input type='text' id='yAxisMin'><br>
y max:<input type='text' id='yAxisMax'><br>
Log x axis?<input type='checkbox' id='xLog'><br>
Log y axis?<input type='checkbox' id='yLog'><br>
<button id="update">Update Plot</button>
</div>
</div>



<?php 
}
include 'footer.php';
?>
