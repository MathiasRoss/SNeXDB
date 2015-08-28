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
    echo $count.' results returned';
    if ($count !=0){
        echo "; click on '+' symbol to expand.";
        echo "<br>";

        displayTable($novae,$observations);
//        include 'paginator.php';
        echo '<br>';
        include 'plot.php';
    }
}

include 'footer.php';

?>

