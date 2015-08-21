function toggle(d){
    if (document.getElementById(d).style.display=="none"){
        document.getElementById(d).style.display="";
    } else{ 
        document.getElementById(d).style.display="none";
    }
}

function toggleDiv(d){
    toggle(d);
    var button = d + 'Button';
    if (document.getElementById(button).innerHTML == '–'){
        document.getElementById(button).innerHTML = "+";
    }
    else {
        document.getElementById(button).innerHTML = "–";
    }
}




function modelPopup(d) {
    var selector = '#'+d;
    var popupId = 'model'+d;
    $('<div/>', {id: popupId}).appendTo(selector);
    $('#'+popupId).dialog();
    console.log('Hi');
}
