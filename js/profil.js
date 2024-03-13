function scrollToElement(next) {
    const container = document.getElementById('demandes_faites');
    const elements = document.querySelectorAll('.demande_faite');
    
    var currentElement;

    for(element of elements){
        var rect = element.getBoundingClientRect();
        if(rect.left >= 0 && rect.left <= container.offsetWidth){
            currentElement = element;
            break;
        }
    }

    if(currentElement){
        currentIndex = Array.from(elements).indexOf(currentElement);
        if(next){
            if (currentIndex < elements.length - 1) {
                var nextElement = elements[currentIndex + 1];
            }else{
                var nextElement = elements[0];
            }
        }else{
            if (currentIndex > 0) {
                var nextElement = elements[currentIndex - 1];
            }else{
                var nextElement = elements[elements.length - 1];
            }
        }
        
        container.scrollLeft = nextElement.getBoundingClientRect().left- container.getBoundingClientRect().left + container.scrollLeft;
    }
}

var swap_en_cours = document.getElementById("profil_demande_cours").querySelector("h2");
var swap_accept = document.getElementById("profil_demande_accept").querySelector("h2");
var swap_refus = document.getElementById("profil_demande_refus").querySelector("h2");
var titre_profil = document.getElementsByClassName("profil_titre")[0].querySelector("h1");

function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;
    var elementToMove = document.getElementById("profil_texte");

    var profil_content = document.getElementById("profil_content");
    var mainElement = document.querySelector("main");
    if(largeurFenetre <= 600){
        // Vérifier si l'élément et la balise main existent
        if (elementToMove && mainElement) {
            // Supprimer l'élément de son emplacement actuel
            elementToMove.remove();

            // Insérer l'élément à la troisième position dans la balise main
            var elementsInsideMain = mainElement.children;
            var insertionIndex = Math.min(3, elementsInsideMain.length); // Troisième position
            mainElement.insertBefore(elementToMove, elementsInsideMain[insertionIndex]);
        }
        titre_profil.innerHTML = "Mes swaps";
        swap_en_cours.innerHTML = "En cours";
        swap_accept.innerHTML = "Acceptés";
        swap_refus.innerHTML = "Refusés";
    }else{
        titre_profil.innerHTML = "Mon profil";
        swap_en_cours.innerHTML = "Swaps en cours";
        swap_accept.innerHTML = "Swaps acceptés";
        swap_refus.innerHTML = "Swaps refusés";
        if (elementToMove && profil_content) {
            elementToMove.remove();
            var elementsInsideMain = profil_content.children;
            var insertionIndex = Math.min(0, elementsInsideMain.length); // Troisième position
            profil_content.insertBefore(elementToMove, elementsInsideMain[insertionIndex]);
        }
    }
}

document.getElementById('profil_image').addEventListener('click', function() {
    document.getElementById('logout_image').style.display = 'block';
    document.getElementById('fond_logout_image').style.display = 'block';
});


// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenuProfil);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenuProfil();