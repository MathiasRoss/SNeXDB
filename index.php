<?php

include 'debugFunc.php';
include 'calculations.php';

include 'header.php';

include 'connect.php';

include 'searchForm.php';
include 'search.php';
dispMem();
include 'exportButton.php';
?>
<hr>
<?php
include 'table.php';
dispMem();
if(!isset($_GET["graph"])){
    include 'plot.php';
}
include 'footer.php';

?>

