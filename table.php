<head>






<link rel="stylesheet" href="/jqGrid/ui.jqgrid.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/smoothness/jquery-ui.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="/jqGrid/jquery.jqgrid.min.js"></script>

</head>

<?php
function insertTable($table)
{
?>


<table id = "grid"></table>
<div id="pager"></div>

<script type="text/javascript">


jQuery(document).ready(function(){

var mydata = [
{ id : "one", "name" : "row one" },
{ id : "two", "name" : "row two" },
{ id : "three", "name" : "row three" }
];

$("#grid").jqGrid({ //set your grid id
data: mydata, //insert data from the data object we created above
datatype: 'local',
width: 500, //specify width; optional
colNames:['Id','Name'], //define column names
colModel:[
{name:'id', index:'id', key: true, width:50},
{name:'name', index:'name', width:100}
], //define column models
shrinkToFit: false,
height: 'auto',
pager: '#pager', //set your pager div id
sortname: 'id', //the column according to which data is to be sorted; optional
viewrecords: true, //if true, displays the total number of records, etc. as: "View X to Y out of Z” optional
sortorder: "asc", //sort order; optional
caption:"jqGrid Example" //title of grid
});
});
</script>


<?php
}





?>
