function deconnexion(){
    // Créez une requête XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "logout.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Redirigez l'utilisateur après la déconnexion
            window.location.href = "login.php";
        }
    };
    xhr.send();
}

function scrollToElement(type,next) {
    var container = document.getElementById('demandes_'+type+'es');
    var elements = document.querySelectorAll('.demande_'+type+'e');
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
if(window.location.href.includes("profil.php")){
    var swap_attente = document.getElementById("profil_demande_attente").querySelector("h2");
}
var titre_profil = document.getElementsByClassName("profil_titre")[0].querySelector("h1");

function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;

    var elementToMove;
    var profil_texte = document.getElementById("profil_texte");

    if(window.location.href.includes("profil.php")){
        elementToMove = profil_texte;
    }else{
        elementToMove = document.getElementById("profil_id");
    }
    

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
        if(window.location.href.includes("profil.php")){
            swap_attente.innerHTML = "En attente";
        }
    }else{
        titre_profil.innerHTML = "Mon profil";
        swap_en_cours.innerHTML = "Swaps en cours";
        if(window.location.href.includes("profil.php")){
            swap_attente.innerHTML = "Swaps en attente";
        }
        swap_accept.innerHTML = "Swaps acceptés";
        swap_refus.innerHTML = "Swaps refusés";
        if (elementToMove && profil_content) {
            elementToMove.remove();
            if(window.location.href.includes("profil.php")){
                var elementsInsideMain = profil_content.children;
                var insertionIndex = Math.min(0, elementsInsideMain.length); // Troisième position
                profil_content.insertBefore(elementToMove, elementsInsideMain[insertionIndex]);
            }else{
                var elementsInsideMain = profil_texte.children;
                var insertionIndex = Math.min(0, elementsInsideMain.length); // Troisième position
                profil_texte.insertBefore(elementToMove, elementsInsideMain[insertionIndex]);
            }
            
        }
    }
}

// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenuProfil);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenuProfil();

var demandes_faite_titre = document.getElementsByClassName("demande_faite_titre");
for(var demande_faite_titre of demandes_faite_titre){
    if(demande_faite_titre.querySelectorAll("h2")[2].innerHTML.length > 2){
        demande_faite_titre.style.fontSize = "14px";
    }
}
var demandes_reçue_titre = document.getElementsByClassName("demande_reçue_titre");
for(var demande_reçue_titre of demandes_reçue_titre){
    if(demande_reçue_titre.querySelectorAll("h2")[2].innerHTML.length > 2){
        demande_reçue_titre.style.fontSize = "14px";
    }
}


var swap_pannel = document.getElementById("swap_pannel");
function openSwapPannel(type){
    history.replaceState({}, document.title, window.location.pathname);
    if(type=="attente"){
        window.location.href = window.location.href + "?typeSwap=attente";
    }else if(type=="cours"){
        window.location.href = window.location.href + "?typeSwap=cours";
    }else if(type=="accept"){
        window.location.href = window.location.href + "?typeSwap=accept";
    }else if(type=="refus"){
        window.location.href = window.location.href + "?typeSwap=refus";
    }
    event.stopPropagation();
}

document.addEventListener("click" , function (event){
    if ((!(event.target.closest("#swap_pannel")) && swap_pannel && swap_pannel.style.display != "none") || event.target.id == "swap_button_retour") {
        swap_pannel.style.display = "none";
        history.replaceState({}, document.title, window.location.pathname);
    }
})

function cancelDemandeFaite(element){
    var clickedElement = element.target;

    // Vérifier si l'élément cliqué est le même que l'élément sur lequel l'événement est attaché
    if (clickedElement === element.currentTarget) {
        var rowAttribute = element.parentElement.parentElement.parentElement.dataset.row;

        if (rowAttribute) {
            try {
                var donnees = JSON.parse(atob(rowAttribute));
            } catch (error) {
                console.error("Erreur lors du parsing JSON :", error);
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }
        if(donnees.idDemande !== "" && donnees.demandeur !== "") {
            
            var form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', '');
            
            var inputDemandeur = document.createElement('input');
            inputDemandeur.setAttribute('type', 'hidden');
            inputDemandeur.setAttribute('name', 'demandeur');
            inputDemandeur.setAttribute('value', donnees.demandeur);
            form.appendChild(inputDemandeur);
            
            var inputIdDemande = document.createElement('input');
            inputIdDemande.setAttribute('type', 'hidden');
            inputIdDemande.setAttribute('name', 'idDemande');
            inputIdDemande.setAttribute('value', donnees.idDemande);
            form.appendChild(inputIdDemande);

            // Ajouter le formulaire à la page
            document.body.appendChild(form);

            // Soumettre le formulaire
            form.submit();

            // Supprimer le formulaire après soumission
            form.remove();
        }
    }
}

function choixDemande(choix, element){
    var clickedElement = element.target;

    // Vérifier si l'élément cliqué est le même que l'élément sur lequel l'événement est attaché
    if (clickedElement === element.currentTarget) {
        var rowAttribute = element.closest('.demande_reçue').dataset.row;

        if (rowAttribute) {
            try {
                var donnees = JSON.parse(atob(rowAttribute));
            } catch (error) {
                console.error("Erreur lors du parsing JSON :", error);
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }
        if(donnees.idDemande !== "" && donnees.demandeur !== "" && donnees.id_notif !== "") {
            if(choix === true){
                choix = 1;
            }else{
                choix = 0;
            }

            var form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', '');

            var inputChoix = document.createElement('input');
            inputChoix.setAttribute('type', 'hidden');
            inputChoix.setAttribute('name', 'choix');
            inputChoix.setAttribute('value', choix);
            form.appendChild(inputChoix);
            
            var inputDemandeur = document.createElement('input');
            inputDemandeur.setAttribute('type', 'hidden');
            inputDemandeur.setAttribute('name', 'demandeur');
            inputDemandeur.setAttribute('value', donnees.demandeur);
            form.appendChild(inputDemandeur);
            
            var inputIdDemande = document.createElement('input');
            inputIdDemande.setAttribute('type', 'hidden');
            inputIdDemande.setAttribute('name', 'idDemande');
            inputIdDemande.setAttribute('value', donnees.idDemande);
            form.appendChild(inputIdDemande);

            var inputIdNotif = document.createElement('input');
            inputIdNotif.setAttribute('type', 'hidden');
            inputIdNotif.setAttribute('name', 'id_notif');
            inputIdNotif.setAttribute('value', donnees.id_notif);
            form.appendChild(inputIdNotif);

            // Ajouter le formulaire à la page
            document.body.appendChild(form);

            // Soumettre le formulaire
            form.submit();

            // Supprimer le formulaire après soumission
            form.remove();
        }
    }
    

}