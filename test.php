<?php
include 'header.php';
?>
<script src="plotSource.js"></script>


<div id ='graphWrapper'>
<div id ='graphInnerWrapper'>
Age vs. Luminosity
<div id="graph"></div>
</div>
<div id = 'graphOptions'>
x min:<input type='text' id='xAxisMin'><br>
x max:<input type='text' id='xAxisMax'><br>
y min:<input type='text' id='yAxisMin'><br>
y max:<input type='text' id='yAxisMax'><br>
Log x axis?<input type='checkbox' id='xLog'><br>
Log y axis?<input type='checkbox' id='yLog'><br>
<button id="update">Update Plot</button>
</div>
</div>




<?php 
include 'footer.php';
?>
