
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
var boundOptions = {
            series: {lines:{show:false},points:{show:true}}
};


var points = {errorbars:"y",yerr:{show:true, upperCap: "-", lowerCap: "-", radius:5}};
var boundPoints = {symbol:"triangle"};

var colors=['#990000',  '#993399',  '#990066',  '#990099'];  /*9900CC  9900FF
993300  993333  993366  993399  9933CC  9933FF
996600  996633  996666  996699  9966CC  9966FF
999900  999933  999966  999999  9999CC  9999FF
99CC00  99CC33  99CC66  99CC99  99CCCC  99CCFF
99FF00  99FF33  99FF66  99FF99  99FFCC  99FFFF*/



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
            var boundList = {};
            var dataObj = {};
            console.log(result);
            jsonTable = JSON.parse(result);
            for (i = 0; i < jsonTable.length; i++){
                console.log(jsonTable[i].fitsID);
                var id = '#'+jsonTable[i].fitsID;
                if (($(id).is(':checked')) ){
                    console.log('beeping');
                    if (labels.indexOf(jsonTable[i].name)==-1){
                        labels.push(jsonTable[i].name);
                        dataObj[jsonTable[i].name] = [];
                    }
                    if (jsonTable[i].isUpperBound==1 && !boundList[jsonTable[i].name]){
                        boundList[jsonTable[i].name]=[];
                    }
                    var row = [];
                    row[0] = jsonTable[i].age;
                    row[1] = jsonTable[i].lum;
                    row[2] = jsonTable[i].lumErrL;
                    row[3] = jsonTable[i].lumErrH;
                    if (jsonTable[i].isUpperBound==1){
                        boundList[jsonTable[i].name].push(row);
                    } else{
                        dataObj[jsonTable[i].name].push(row);
                    }
                }
            }
            var data= [];
            console.log(dataObj.length,labels.length);
            for (var i = 0; i < labels.length; i ++){
                var obj = {data:dataObj[labels[i]],label:labels[i], points:points,color:colors[i]};
                data.push(obj);
                if (boundList[labels[i]]){
                    console.log('boundList not empty',boundList[labels[i]]);
                    var obj={data:boundList[labels[i]],options:boundOptions,lines:{show:false},color:colors[i],points:boundPoints};
                    data.push(obj);
                }
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

    var yMaxOrder = Math.floor(Math.log10(plot.getAxes().yaxis.options.max));
    var yMinOrder = Math.floor(Math.log10(plot.getAxes().yaxis.options.min+.0001));
    console.log(yMinOrder);
//log scales
    if ($('#yLog').is(':checked')){
        if ($('#yAxisMin').val() == 0){
            plot.getAxes().yaxis.options.min = .00001;
        }
        var yTickArray = []; 
        for (i = yMinOrder; i < yMaxOrder; i ++){
            yTickArray.push(Math.pow(10,i));
        }
        console.log(yTickArray);
        plot.getAxes().yaxis.options.ticks = yTickArray;
        if (yMinOrder < -3){
            plot.getAxes().yaxis.options.tickFormatter = function(val, axis) {return'10^'+Math.floor(Math.log10(val));};
        }
        else {
            plot.getAxes().yaxis.options.tickFormatter = function(val, axis) {return val;};
            plot.getAxes().yaxis.options.tickDecimals = Math.abs(yMinOrder);
        }
        plot.getAxes().yaxis.options.transform =  function(v) {return Math.log(v);};
    } else {
       plot.getAxes().yaxis.options.transform = null;
}
    if ($('#xLog').is(':checked')){
        if ($('#xAxisMin').val() == 0){
            plot.getAxes().xaxis.options.min = .000001;
        }
       plot.getAxes().xaxis.options.transform =  function(v) {return Math.log(v);};
    } else {
       plot.getAxes().xaxis.options.transform = null;
}
    plot.setupGrid();
    plot.draw();
});




});
