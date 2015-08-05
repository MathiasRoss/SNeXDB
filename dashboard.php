<?php
include 'header.php';
include 'connect.php';

try {

    $stmt = $conn->query("SELECT DISTINCT uploadSet FROM Fits");
    $setNames = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT uploadSet FROM FitsNew");
    $setNamesNew = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn -> query("SELECT DISTINCT name FROM Novae");
    $names= $stmt-> fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<form action='delUploadSet.php' method='POST'>
<select name='setName'>
<option selected = 'selected' value = ''>Upload Set</option>
<?php
foreach($setNames as $name){
    echo '<option>' . htmlspecialchars($name['uploadSet']) . '</option>';
}
?>
</select>
<input type='submit' value="Delete">
</form>
<hr>
<form action='checkUploads.php' method='POST'>
<select name='setName'>
<option selected = 'selected' value = ''>Upload Set</option>
<?php
foreach($setNamesNew as $name){
    echo '<option>' . htmlspecialchars($name['uploadSet']) . '</option>';
}
?>
</select>
<input type='submit' value="Check">
</form>
<hr>
<br>


<form action='editNovaForm.php' method='GET'>

<select name="name">
<option selected ="selected" value = "" disabled>Object Name</option>

<?php
foreach($names as $name){
    echo "<option>" . $name['name'] . "</option>";
}
?>
</select>

<input type='submit' value='edit'>
</form>
<br>

<form action='editObsForm.php' method='GET'>

<input type=text name='obsID'>
<input type='submit' value='Edit'>
</form>

<form action='editFitsForm.php' method='GET'>

<input type=text name='fitsID'>
<input type='submit' value='Edit'>
</form>




<?php
include 'footer.php';

?>
