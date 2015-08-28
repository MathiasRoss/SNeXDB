<button id='plot'>Plot Selected</button>
<br>

<div id ='graphWrapper' style='display:none;'>
<div id ='graphInnerWrapper' style='background-color:#ffffff'>
Age vs. Luminosity
<div id="graph"></div>
</div>
<div id = 'graphOptions'>
<br>
x min:<input type='text' id='xAxisMin'><br>
x max:<input type='text' id='xAxisMax'><br>
y min:<input type='text' id='yAxisMin'><br>
y max:<input type='text' id='yAxisMax'><br>
Log x axis?<input type='checkbox' id='xLog'><br>
Log y axis?<input type='checkbox' id='yLog'><br>
<button id="update">Update Plot</button>
<a id="save" download="SNeXDB_Plot.png" href=''>Save Plot</a>
</div>
</div>

<script src="plotSource.js"></script>




