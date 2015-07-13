<?php
include 'calculations.php';
include 'config.php';


//connect
$conn = new mysqli($servername, $username, $password, $dbname) or die('Could not connect to database: '. $conn -> connect_error);

//check for blank flux error
if($_POST["flux_herror"] == ""){
    $_POST["flux_herror"] = $_POST["flux_lerror"];
}


echo "You have selected the following: <br>";
echo "Object name: " . $_POST["name"] . "<br>";
if ($_POST["typeid"] == ""){
    echo "New object type: " . $_POST["type"]. "<br>";
    $type = $_POST["typeid"]; 
}
else {
    echo "Object type: ". $_POST["typeid"]. "<br>";
    $type = $_POST["type"];
}
echo "Distance: " . $_POST["distance"] . "<br>";
echo "Distance reference: ". refLink($_POST["distRef"]) . "<br>";
echo "Date exploded (MJD): " . $_POST["dateExploded"]. "<br>";
echo "Date exploded (Gregorian): " . jdtogregorian(ceil(mjdtojd($_POST["dateExploded"]))). "<br>";
echo "Reference for date:" . refLink($_POST["dateExplodedRef"]) . "<br>";







echo "<br>If these values are correct, press submit.  Else, go to previous page.";

?>


<!-- Submission form, hidden fields to pass variables -->
<form method = "post" action = "newNova.php">

<input type="hidden" name="name" value="<?php echo $_POST["name"]; ?>"> 
<input type="hidden" name="type" value="<?php echo $type; ?>">
<input type="hidden" name="dateExploded" value="<?php echo $_POST["dateExploded"]; ?>">
<input type="hidden" name="dateExplodedRef" value="<?php echo $_POST["dateExplodedRef"]; ?>">
<input type="hidden" name="distance" value="<?php echo $_POST["distance"]; ?>">
<input type="hidden" name="distRef" value="<?php echo $_POST["distRef"]; ?>">
<br>
<input type="submit">
<br>

</form>



<?php
$conn -> close();
?>
