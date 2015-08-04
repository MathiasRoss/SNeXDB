<?php
include 'header.php';
include 'connect.php';

try {
    $stmt = $conn->query("SELECT DISTINCT uploadSet FROM Fits");
    $setNames = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    $stmt = $conn->query("SELECT DISTINCT uploadSet FROM FitsNew");
    $setNamesNew = $stmt -> fetchAll(PDO::FETCH_ASSOC);
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






<?php
include 'footer.php';

?>
