//document.body.style.background="red"; //mettre le background de la page en rouge
let descr = Array.from(document.body.getElementsByClassName("descr"));
descr.forEach( elt=> {
    if(elt.nodeName == "P"){
        elt.style.background="red";
    }    
});