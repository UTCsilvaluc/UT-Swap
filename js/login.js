function mettreAJourContenu() {
    var bodyHasScrollbar = isScrollbarVisible(document.getElementById("login_mid"));
    if (bodyHasScrollbar) {
        document.getElementById("login_mid").style.justifyContent = "unset";
    } else {
        document.getElementById("login_mid").style.justifyContent = "center";
    }
}
    
// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenu);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenu();