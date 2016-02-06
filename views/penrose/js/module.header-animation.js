
//-------------------------------------------------------------------
// VARIABLES
//-------------------------------------------------------------------
var headerStatus = "tall";
var screenWidthStatus = "wide";


// HEADER STYLING
//--------------------------------
function headerCheck(){

    var scrollPosition;    
    scrollPosition = $(this).scrollTop() - 8;
    console.log("scrollTop: "+$(this).scrollTop());
    
    // IF -- we've scrolled the page, shrink the header
    if ($(this).scrollTop() > 170) {
        headerShort();

    // ELSE -- full height header
    } else {
        headerTall();
    }
}


function headerShort(){
    console.log("short");
    if(headerStatus == "tall" && screenWidthStatus == "wide") {
        TweenMax.to('.nav-short', 0.5, { top:'0px', delay:0, overwrite:1, ease:Strong.easeOut});
        headerStatus = "short";
    }
}

function headerTall(){
    console.log("tall");
    if(headerStatus == "short" && screenWidthStatus == "wide"){     
        TweenMax.to('.nav-short', 0.5, { top:'-100px', delay:0, overwrite:1});
        headerStatus = "tall";
    }
}



