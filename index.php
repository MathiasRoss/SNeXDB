<?php

include 'debugFunc.php';
include 'calculations.php';

include 'header.php';

include 'connect.php';
include 'searchForm.php';
if (isset($_GET['objid'])){
    include 'search.php';
    include 'exportButton.php';
?>
<hr>
<?php
    include 'table.php';
    if(!isset($_GET["graph"])){
        include 'plot.php';
    }
}
include 'footer.php';

?>

