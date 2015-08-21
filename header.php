<!doctype html>
<?php 
include 'config.php';
include'password_protect.php';
?>

<meta charset="UTF-8"> 
<head>
<link rel="stylesheet" type="text/css" href="css/stylesheet.css">
<link rel="stylesheet" type="text/css" href="/DataTables-1.10.7/media/css/jquery.dataTables.css">

<script src='scripts/source.js'></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="flot/jquery.flot.js"></script>
<script src="flot/jquery.flot.errorbars.js"></script>
<script src="flot/jquery.flot.symbol.js"></script>
<script src="flot/jquery.flot.axislabels.js"></script>
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.js"></script>
</head>
<body>
<div id='wrapper'>

<div id='header'>
<!--<img src="images/logoWhite.gif" alt='logo' />-->
<h1><a href='<?php echo $baseDir; ?>'>Supernovae X-Ray Database</a></h1>
</div>

<div id='menu'>
<ul>
<li><a href='<?php echo $baseDir; ?>'>Home</a></li>
<li><a href='submitUpload.php'>Submit Upload</a></li>
<li><a href='about.php'>About</a></li>
<li><a href='methodology.php'>Methodology</a></li>
</ul>
</div>
<div id='content'>


<?php
//include 'debugFunc.php';//for debugging
?>
