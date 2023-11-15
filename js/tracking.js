

function habitus_cb_completed() {
            
    let cb = document.getElementById("cb_complete");
    if(cb.checked == false) {
        cb.checked = true; 
        console.log("check 1");
        document.getElementById("svg_complete").setAttribute("fill","green");
    }
    else {
        if(cb.checked == true) {
            cb.checked = false; 
            console.log("check 3");
            document.getElementById("svg_complete").setAttribute('fill','#ccc');
        }   
    }
}

var svgCompleted = document.getElementById("svg_complete");
svgCompleted.addEventListener("click", habitus_cb_completed);
