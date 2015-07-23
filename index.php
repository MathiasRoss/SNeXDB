<?php
include 'calculations.php';
include 'debugFunc.php';

include 'header.php';
include 'connect.php';
include 'searchForm.php';
include 'search.php';
include 'exportButton.php';

include 'table.php';

if($_GET["graph"]!='on'){
    include 'plot.php';
}
$conn = null;
?>
