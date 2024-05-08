function changeMenu(name , event){
    document.getElementsByClassName("currentSpan")[0].className = "other";
    event.target.className = "currentSpan";
    document.getElementsByClassName("visible")[0].className = "hidden";
    document.getElementById(name).className = "visible";
}