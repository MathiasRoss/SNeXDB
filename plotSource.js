
function makeURLParams()
{
    var URL = window.location.search.substring(1);
    var params = URL.split('?');
    params = params[0].split('&');
    for(var i = 0; i<params.length; i++){
        params[i] = params[i].split('=');
    }
    return params;
}

function unsetAxes(plot){
    plot.getAxes().xaxis.options.min=null;
    plot.getAxes().xaxis.options.max=null;
    plot.getAxes().yaxis.options.min=null;
    plot.getAxes().yaxis.options.max=null;
}




$(document).ready(function() {
var start = performance.now();

var header = makeURLParams();

//jsTable holds the data to be plotted
var jsTable = {};

var data = [];

var getSend = {};
for (i = 0; i < header.length; i++){
    getSend[header[i][0]] = header[i][1].replace('+',' ');
}


//returns the id (fitsid in naming scheme) from each selected object
function getselected () {
    var idlist = []
    $('#.selected').each(function() {
        idlist.push($(this).attr('id'));
    });
}

var options = { 
            series: {lines:{show:true},points:{show:true}},
            axisLabels:{show:true},
            xaxes:[{axisLabel:'Age (days)'}],
            yaxes:[{axisLabel:'Luminosity'}]
};


var points = {errorbars:"y",yerr:{show:true, upperCap: "-", lowerCap: "-", radius:5}};






function drawPlot(plot) {
    $.ajax({
        url: "ajaxSearch.php", 
        data: getSend,
        type:'GET', 
        async:true, 
        success:function (result){
            var labels = [];
            var dataObj = {};
            console.log(result);
            jsonTable = JSON.parse(result);
            for (i = 0; i < jsonTable.length; i++){
                if (labels.indexOf(jsonTable[i].name)==-1){
                    labels.push(jsonTable[i].name);
                    dataObj[jsonTable[i].name] = [];
                }
                var row = [];
                row[0] = jsonTable[i].age;
                row[1] = jsonTable[i].lum;
                row[2] = jsonTable[i].lumErrL;
                row[3] = jsonTable[i].lumErrH;
                dataObj[jsonTable[i].name].push(row);
            }
            var data= [];
            for (var i = 0; i < labels.length; i ++){
                obj = {data:dataObj[labels[i]],label:labels[i], points:points};
                data.push(obj);
            }
            plot.setData(data);
            plot.setupGrid();
            plot.draw();
            console.log(performance.now()-start);
            axes=plot.getAxes();
            $('#xAxisMin').val(axes.xaxis.min);
            $('#xAxisMax').val(axes.xaxis.max);
            $('#yAxisMin').val(axes.yaxis.min);
            $('#yAxisMax').val(axes.yaxis.max);
            console.log('BEEP');
        }
    });
}


function plotSelected(plot) {
    $.ajax({
        url: "ajaxSearch.php", 
        data: getSend,
        type:'GET', 
        async:true, 
        success:function (result){
            var labels = [];
            var dataObj = {};
            console.log(result);
            jsonTable = JSON.parse(result);
            for (i = 0; i < jsonTable.length; i++){
                console.log(jsonTable[i].fitsID);
                var id = '#'+jsonTable[i].fitsID;
                if (($(id).is(':checked'))&&(jsonTable[i].isUpperBound!=1) ){
                    console.log('beeping');
                    if (labels.indexOf(jsonTable[i].name)==-1){
                        labels.push(jsonTable[i].name);
                        dataObj[jsonTable[i].name] = [];
                    }
                    var row = [];
                    row[0] = jsonTable[i].age;
                    row[1] = jsonTable[i].lum;
                    row[2] = jsonTable[i].lumErrL;
                    row[3] = jsonTable[i].lumErrH;
                    dataObj[jsonTable[i].name].push(row);
                }
            }
            var data= [];
            for (var i = 0; i < labels.length; i ++){
                var obj = {data:dataObj[labels[i]],label:labels[i], points:points};
                data.push(obj);
            }
            unsetAxes(plot);
            plot.setData(data); 
            plot.setupGrid();
            plot.draw();
            console.log(performance.now()-start);
            var axes=plot.getAxes();
            $('#xAxisMin').val(axes.xaxis.min);
            $('#xAxisMax').val(axes.xaxis.max);
            $('#yAxisMin').val(axes.yaxis.min);
            $('#yAxisMax').val(axes.yaxis.max);
            console.log('BEEP');
        }
    });
}

var data = [];
var plot;



$('#plot').click(function(){
    if (document.getElementById('graphWrapper').style.display =='none'){
        document.getElementById('graphWrapper').style.display='';
        plot = $.plot($('#graph'),data,options);
    } 
    plotSelected(plot);
});



var novaBoxes = document.getElementsByClassName("novaeBox");
for (i =0; i < novaBoxes.length; i++){
    novaBoxes[i].onclick= function(){
        var tableID=this.id+'Table';
        var table = document.getElementById(tableID);
        var boxes = table.getElementsByTagName('input');
        console.log(boxes);
        for (var j = 0;j < boxes.length; j++){
            if (this.checked){
                boxes[j].checked=true;
            } else{
                boxes[j].checked=false;
            }
        }
    }
}





$('#update').click(function(){
    plot.getAxes().xaxis.options.min = $('#xAxisMin').val();
    plot.getAxes().xaxis.options.max = $('#xAxisMax').val();
    plot.getAxes().yaxis.options.min = $('#yAxisMin').val();
    plot.getAxes().yaxis.options.max = $('#yAxisMax').val();

//log scales
    if ($('#yLog').is(':checked')){
       plot.getAxes().yaxis.options.transform =  function(v) {return Math.log(v);};
    } else {
       plot.getAxes().yaxis.options.transform = null;
}
    if ($('#xLog').is(':checked')){
       plot.getAxes().xaxis.options.transform =  function(v) {return Math.log(v);};
    } else {
       plot.getAxes().xaxis.options.transform = null;
}
    plot.setupGrid();
    plot.draw();
});




});
