
var footer_links = document.getElementById("footer_links");
var footer_social = document.getElementById("footer_social");
function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;
    if(largeurFenetre <= 500){
        footer_social.style.display ="none";
        footer_links.style.display = "none";
    }else if(largeurFenetre <= 850){
        footer_links.style.display = "none";
        footer_social.style.display ="flex";
    }else{
        footer_links.style.display = "flex";
        footer_social.style.display ="flex";
    }
}
// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenuProfil);
mettreAJourContenuProfil();
