<?php

include 'calculations.php';
include 'tables.php';
include 'header.php';
include 'connect.php';
include 'searchForm.php';
if (isset($_GET['objid'])){
    include 'search.php';
    include 'exportButton.php';
    echo '<hr>';
    displayTable($novae,$observations);
    if(!isset($_GET["graph"])){
        include 'plot.php';
    }
}
include 'footer.php';

?>

