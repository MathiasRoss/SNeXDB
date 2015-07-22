<button id="newData">Update Data</button>

<div id="placeholder" style="width:600px;height:300px"></div>
x min:<input type='text' id='xAxisMin'>
x max:<input type='text' id='xAxisMax'>
y min:<input type='text' id='yAxisMin'>
y max:<input type='text' id='yAxisMax'>

<button id="update">Update Plot</button>


<script>
$( document ).ready(function() {


<?php
//print the javascript object with age, lum, and lum err
$jsTable = '{';
foreach ($observations as $name=>$row) {
    foreach($row as $obs){
        $jsTable = $jsTable.$obs['fitsID'].':{';
        $jsTable = $jsTable."name:'".$name."',";
        $jsTable = $jsTable.'age:'.$obs['age'].',';
        $jsTable = $jsTable.'lum:'.$obs['lum'].',';
        $jsTable = $jsTable.'lumErr:'.$obs['lumErr'];
        $jsTable = $jsTable.'},';
    }
}
$jsTable = $jsTable.'};';
echo 'var jsTable = '.$jsTable;




//sort by age for the plot
foreach($result as $key=>$row){
    $age[$key] = $row['age'];
    $lum[$key] = $row['lum'];
}

array_multisort($age, SORT_ASC, $lum, $result);

?>




    var points = {errorbars:"y",yerr:{show:true, upperCap: "-", lowerCap: "-", radius:5}};
<?php
$data = 'var data = [';
$i = 1;
foreach($novae as $name=>$nova)
{
    $age=array();
    $lum=array();
    //sort by age for the plot
    foreach($observations[$name] as $key=>$row){
        $age[] = $row['age'];
        $lum[] = $row['lum'];
    }

    array_multisort($age, SORT_ASC, $lum, $observations[$name]);


    echo 'var d'.$i.'=[';
    foreach($observations[$name] as $obs){
        echo '['.$obs['age'].','.$obs['lum'].','.getLumErr($obs['lum'],$obs['flux'],$obs['fluxErrL']).'],';
    }
    echo '];';  
    $data = $data."{label:'".$name."', points:points, data:d".$i.'},';

    $i++;
}
$data = $data.'];';
echo $data;
?>

    var options = { 
            series: {lines:{show:true},points:{show:true}}
};


//actually plot
var plot =  $.plot($("#placeholder"),data,options);
var axes = plot.getAxes();

//print initial axes limits
$('#xAxisMin').val(axes.xaxis.min);
$('#xAxisMax').val(axes.xaxis.max);
$('#yAxisMin').val(axes.yaxis.min);
$('#yAxisMax').val(axes.yaxis.max);

//update axes on click
$('#update').click(function(){
    plot.getAxes().xaxis.options.min = $('#xAxisMin').val();
    plot.getAxes().xaxis.options.max = $('#xAxisMax').val();
    plot.getAxes().yaxis.options.min = $('#yAxisMin').val();
    plot.getAxes().yaxis.options.max = $('#yAxisMax').val();
    plot.setupGrid();
    plot.draw();
});


//toggle selected classes
$(".detailsTable tr").click(function(){
        if ($(this).attr('class')!='selected') {
            $(this).addClass('selected');
        } else{
            $(this).removeClass('selected');

   }
 
});


//Get selected data
$('#newData').on('click', function() {
    var data = [];
    var names = [];
    var dataObj = {};
    var newArray = [];
    $(".selected").each(function () {
        name = jsTable[$(this).attr('id')].name;
        age = jsTable[$(this).attr('id')].age;
        lum = jsTable[$(this).attr('id')].lum;
        lumErr = jsTable[$(this).attr('id')].lumErr;
        if(names.indexOf(name) == -1){
            names.push(name);
            dataObj[name] = [];
        }
        var row = [];
        row[0] = jsTable[$(this).attr('id')].age;
        row[1] = jsTable[$(this).attr('id')].lum;
        row[2] = jsTable[$(this).attr('id')].lumErr;
        dataObj[name].push(row);
});
   console.log(dataObj);
   obj = [];
   for (var i = 0; i<names.length; i++){
       obj = {data:dataObj[names[i]],points:points,label:names[i]};
       console.log(obj);
       data.push(obj);
}
   plot.setData(data);
   plot.setupGrid();
   plot.draw();
});

});
</script>
