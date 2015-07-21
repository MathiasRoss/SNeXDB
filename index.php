<?php
include 'calculations.php';
include 'debugFunc.php';

include 'header.php';
include 'connect.php';
include 'searchForm.php';
include 'search.php';
include 'exportButton.php';

echo "Table 1:
<br>";
include 'table.php';

//echo "Table 2:
//<br>";
//include 'phpTable.php';
include 'plot.php';

$conn = null;
?>
