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

<form method = get action ="index.php">

<!-- Object name pulldown -->
<select name="objid">
<option selected ="selected" value = "">Object Name</option>

<?php
foreach($names as $name){
    echo "<option>" . $name['name'] . "</option>";
}
?>
</select>
<input type="text" name = "name" >
<br>

<!-- Type search pulldown -->
<select name="typeid">
<option selected = "selected" value ="">Object Type</option>

<?php
foreach($types as $type){
    echo "<option>" . $type['type'] . "</option>";
}
?>

</select>
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
<br>

<!-- Instrument name pulldown -->
<select name="instrumentid">
<option selected ="selected" value = "">Instrument</option>

<?php
foreach($instruments as $key=>$instrument){
    echo "<option>" . $instrument['instrument'] . "</option>";
}
?>
</select>
<input type="text" name = "instrument" >
<br>





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
<br>


Disable graphing?<input type ="checkbox" name="graph" <?php if($_GET['graph']=='on'){echo 'checked';}?>>
<div id='options'>

Convert results to standard date? <input type="checkbox" name="MJD" <?php if($_GET['MJD']=='on'){echo 'checked';}?>>

<br>
</div>
Show <select name='count'> 
<option>10</option>
<option selected='selected'>100</option>
<option>200</option>
</select>

Results




<input type = "submit" value = "Search">

</form>
