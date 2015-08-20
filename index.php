<?php

include 'calculations.php';
include 'tables.php';
include 'header.php';
include 'connect.php';
include 'searchForm.php';
if (isset($_GET['objid'])){
    include 'search.php';
    echo '<hr>';
    echo $count.' results returned';
    if ($count !=0){
        displayTable($novae,$observations);
//        include 'paginator.php';
        include 'exportButton.php';
        if(!isset($_GET["graph"])){
            include 'plot.php';

        }
    }
}

include 'footer.php';

?>

