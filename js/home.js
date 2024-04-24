
var home_bg = document.getElementById("home_bg");
var path_bg = document.getElementById("path_bg");
var text_intro = document.getElementById("text_intro");
var header = document.getElementsByTagName("header")[0];

function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;
    text_intro.style.top = header.offsetHeight + (home_bg.clientHeight - path_bg.getBoundingClientRect().height)/2 - text_intro.offsetHeight/2.5 + "px";
    console.log(path_bg.getBoundingClientRect().height)
    if(largeurFenetre <= 1500){
        home_bg.setAttribute('width', '1500');
    }else{
        home_bg.setAttribute('width', '100%');
    }
}

// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenuProfil);
requestAnimationFrame(function() {
    mettreAJourContenuProfil();
});
window.addEventListener("load", function() {
    mettreAJourContenuProfil();
});