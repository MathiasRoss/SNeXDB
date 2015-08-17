
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

$(document).ready(function() {
var start = performance.now();
var header = makeURLParams();
console.log(header[0][1]);

//jsTable holds the data to be plotted
var jsTable = {};

var data = [];

var getSend = {};
for (i = 0; i < header.length; i++){
    getSend[header[i][0]] = header[i][1];
}
console.log(getSend);


//returns the id (fitsID in naming scheme) from each selected object
function getSelected () {
    var idList = []
    $('#.selected').each(function() {
        idList.push($(this).attr('id'));
    });
}

var options = { 
            series: {lines:{show:true},points:{show:true}},
            axisLabels:{show:true},
            xaxes:[{axisLabel:'Age (days)'}],
            yaxes:[{axisLabel:'Luminosity'}]
};

var data = [];
var plot = $.plot($('#graph'),data,options);

function drawPlot(plot) {
    $.ajax({
        url: "ajaxTest.php", 
        data: getSend,
        type:'GET', 
        async:true, 
        success:function (result){
            var labels = [];
            var dataObj = {};
            jsonTable = JSON.parse(result);
            for (i = 0; i < jsonTable.length; i++){
                if (labels.indexOf(jsonTable[i].name)==-1){
                    labels.push(jsonTable[i].name);
                    dataObj[jsonTable[i].name] = [];
                }
                var row = [];
                row[0] = jsonTable[i].age;
                row[1] = jsonTable[i].lum;
                dataObj[jsonTable[i].name].push(row);
            }
            var data= [];
            for (var i = 0; i < labels.length; i ++){
                obj = {data:dataObj[labels[i]],label:labels[i]};
                data.push(obj);
            }
            plot.setData(data);
            plot.setupGrid();
            plot.draw();
            console.log(performance.now()-start);
        }
    });
}
drawPlot(plot);
});
