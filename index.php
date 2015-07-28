<?php
include 'calculations.php';
include 'debugFunc.php';

include 'header.php';

include 'connect.php';
?>
<body>
<?php
include 'nav.php';
?>
<div id = 'content'>
<?php
include 'searchForm.php';

include 'search.php';
include 'exportButton.php';
?>
<?php
include 'table.php';

if($_GET["graph"]!='on'){
    include 'plot.php';
}
?>

</div>
<?php
include 'footer.php';
$conn = null;
?>
</body>
