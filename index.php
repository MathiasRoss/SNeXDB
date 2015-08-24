<?php

include 'calculations.php';
include 'tables.php';
include 'header.php';
include 'connect.php';
include 'searchForm.php';
if (isset($_GET['objid'])){
    include 'search.php';
    echo '<hr>';
    include 'exportButton.php';
    echo $count.' results returned';
    echo '<br>';
    if ($count !=0){
        displayTable($novae,$observations);
//        include 'paginator.php';
        echo '<br>';
        include 'plot.php';
    }
}

include 'footer.php';

?>

