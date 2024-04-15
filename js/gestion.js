var isSelectionning = false;
var button_selection = document.getElementById("button_selection");

button_selection.addEventListener("click", function() {
    isSelectionning = !isSelectionning;
    Array.from(document.getElementsByClassName("demande_professeur")).forEach(element => {
        element.classList.toggle("demande_selected", false);
    });
    
    button_selection.classList.toggle('button_filtre_selected' , isSelectionning);
});



function gestionUv(codeUV){
    history.replaceState({}, document.title, window.location.pathname);
    window.location.href = window.location.href + "?codeUV=" + codeUV;
    event.stopPropagation();
}

function getGetValue(key) {
    // Récupère la chaîne de requête de l'URL
    var queryString = window.location.search;

    // Supprime le '?' de la chaîne de requête
    queryString = queryString.substring(1);

    // Divise la chaîne de requête en paires clé-valeur
    var keyValuePairs = queryString.split('&');

    // Parcourt les paires clé-valeur pour trouver la valeur correspondante à la clé spécifiée
    for (var i = 0; i < keyValuePairs.length; i++) {
        // Divise chaque paire clé-valeur en clé et valeur
        var pair = keyValuePairs[i].split('=');

        // Vérifie si la clé correspond à celle spécifiée
        if (decodeURIComponent(pair[0]) === key) {
            // Retourne la valeur correspondante (décodée)
            return decodeURIComponent(pair[1]);
        }
    }

    // Retourne null si la clé n'est pas trouvée dans la chaîne de requête
    return null;
}

function choixProfesseurSwap(choix, element){
    var clickedElement = element.target;

    // Vérifier si l'élément cliqué est le même que l'élément sur lequel l'événement est attaché
    if (clickedElement === element.currentTarget) {
        var rowAttribute = element.parentElement.parentElement.dataset.row;

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

            // Ajouter le formulaire à la page
            document.body.appendChild(form);

            // Soumettre le formulaire
            form.submit();

            // Supprimer le formulaire après soumission
            form.remove();
        }
    }
}

function demandeBehvior(element){
    if(isSelectionning){
        var clickedElement = element.target;

        // Vérifier si l'élément cliqué est le même que l'élément sur lequel l'événement est attaché
        if (clickedElement === element.currentTarget) {
            var rowAttribute = element.dataset.row;

            if (rowAttribute) {
                try {
                    var donnees = JSON.parse(atob(rowAttribute));
                } catch (error) {
                    console.error("Erreur lors du parsing JSON :", error);
                }
            } else {
                console.error("Aucune donnée trouvée dans l'attribut data-row");
            }
            if(donnees.statut !== "" && donnees.statut == 2) {
                element.classList.toggle("demande_selected");
            }
        }
    }else{
        afficherInfoSwap(element);
    }
}

function afficherInfoSwap(element){
    var clickedElement = element.target;

    // Vérifier si l'élément cliqué est le même que l'élément sur lequel l'événement est attaché
    if (clickedElement === element.currentTarget) {
        var rowAttribute = element.dataset.row;

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

            var inputAfficher = document.createElement('input');
            inputAfficher.setAttribute('type', 'hidden');
            inputAfficher.setAttribute('name', 'afficher');
            inputAfficher.setAttribute('value', true);
            form.appendChild(inputAfficher);
            
            var inputIdDemande = document.createElement('input');
            inputIdDemande.setAttribute('type', 'hidden');
            inputIdDemande.setAttribute('name', 'idDemande');
            inputIdDemande.setAttribute('value', donnees.idDemande);
            form.appendChild(inputIdDemande);

            document.body.appendChild(form);

            form.submit();
            form.remove();
        }
    }
}

function gererUVChoix(choix){

    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', '');
    
    var inputChoixSwap = document.createElement('input');
    inputChoixSwap.setAttribute('type', 'hidden');
    inputChoixSwap.setAttribute('name', 'choixSwap');
    inputChoixSwap.setAttribute('value', choix);
    form.appendChild(inputChoixSwap);
    
    var inputCodeUV = document.createElement('input');
    inputCodeUV.setAttribute('type', 'hidden');
    inputCodeUV.setAttribute('name', 'codeUV');
    inputCodeUV.setAttribute('value', getGetValue("codeUV"));
    form.appendChild(inputCodeUV);
    document.body.appendChild(form);

    form.submit();
    form.remove();

}

var uv_button_enregistrer_none = document.getElementById("uv_button_enregistrer_none");
var uv_button_enregistrer_submit = document.getElementById("uv_button_enregistrer_submit");
var uv_button_enregistrer_global = document.getElementById("uv_button_enregistrer_global");
var message_attention_prof = document.getElementById("message_attention_prof");
var uv_content = document.getElementById("uv_content");
var uv_button_retour = document.getElementById("uv_button_retour");
var checkboxChangementGlobal = document.getElementById("checkboxChangementGlobal");
var uv_pannel = document.getElementById("uv_pannel");
var swap_info_pannel = document.getElementById("swap_info_pannel");

document.addEventListener("click" , function (event){
    if ((!(event.target.closest("#uv_pannel")) && (uv_pannel.style.display != "none")) || event.target.id == "uv_button_enregistrer") {
        uv_pannel.style.display = "none";
        history.replaceState({}, document.title, window.location.pathname);
        if(getGetValue("codeUV") === null){
            checkboxChangementGlobal.checked = !checkboxChangementGlobal.checked;
        }
    }

    if (!(event.target.closest("#swap_info_pannel")) && swap_info_pannel != null && swap_info_pannel.style.display != "none") {
        swap_info_pannel.style.display = "none";
        history.replaceState({}, document.title, window.location.pathname);
    }
})

uv_button_enregistrer_none.addEventListener("click", function() {
    event.preventDefault();
    uv_button_enregistrer_submit.classList.toggle('hidden', false);
    uv_button_enregistrer_none.classList.toggle('hidden', true);
    uv_content.classList.toggle('hidden', true);
    message_attention_prof.classList.toggle('hidden', false);
    uv_button_retour.classList.toggle('hidden', false);
});

checkboxChangementGlobal.addEventListener("change", function() {
    uv_button_enregistrer_global.classList.toggle('hidden', false);
    uv_button_enregistrer_none.classList.toggle('hidden', true);
    uv_content.classList.toggle('hidden', true);
    uv_pannel.style.display = "flex";
    message_attention_prof.classList.toggle('hidden', false);
});

uv_button_enregistrer_global.addEventListener("click", function() {
    event.preventDefault();
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', '');
    
    var inputChoixSwap = document.createElement('input');
    inputChoixSwap.setAttribute('type', 'hidden');
    inputChoixSwap.setAttribute('name', 'choixChangement');
    inputChoixSwap.setAttribute('value', checkboxChangementGlobal.checked);
    form.appendChild(inputChoixSwap);
    document.body.appendChild(form);

    form.submit();
    form.remove();

});

uv_button_retour.addEventListener("click", function() {
    event.preventDefault();
    uv_button_enregistrer_submit.classList.toggle('hidden', true);
    uv_button_enregistrer_none.classList.toggle('hidden', false);
    uv_content.classList.toggle('hidden', false);
    message_attention_prof.classList.toggle('hidden', true);
    uv_button_retour.classList.toggle('hidden', true);
});

if(getGetValue("codeUV")){
    var uv_en_cours = document.getElementById("uv_demande_cours").querySelector("h2");
    var uv_accept = document.getElementById("uv_demande_accept").querySelector("h2");
    var uv_refus = document.getElementById("uv_demande_refus").querySelector("h2");
}
var choix_uv_label = document.getElementById("profil_choix_uv_parent").querySelector("label");

var demandeProfesseurs = document.querySelectorAll('.demande_professeur');
function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;
    if(getGetValue("codeUV")){
        if(largeurFenetre <= 600){
            uv_en_cours.innerHTML = "En cours";
            uv_accept.innerHTML = "Acceptés";
            uv_refus.innerHTML = "Refusés";
        }else{
            uv_en_cours.innerHTML = "Swaps en cours";
            uv_accept.innerHTML = "Swaps acceptés";
            uv_refus.innerHTML = "Swaps refusés";
        }
    }

    // Parcourir chaque demande_professeur
    demandeProfesseurs.forEach(function(demandeProfesseur) {
        var gaucheContainer = demandeProfesseur.querySelector('.gauche_container');
        var buttonContainer = demandeProfesseur.querySelector('.button_choix_etudiant');
        var midContainer = demandeProfesseur.querySelector('.mid_container');
        if (largeurFenetre <= 950) {
            if (!buttonContainer.parentElement.classList.contains('top_container')) {
                var topContainer = document.createElement('div');
                topContainer.classList.add('top_container');

                topContainer.appendChild(gaucheContainer);
                topContainer.appendChild(buttonContainer);

                midContainer.parentNode.insertBefore(topContainer, midContainer);
            }
        } else {
            if (buttonContainer.parentElement.classList.contains('top_container')) {
                midContainer.parentNode.insertBefore(gaucheContainer, midContainer);
                midContainer.parentNode.insertBefore(buttonContainer, midContainer.nextSibling);
                
                var topContainer = document.querySelector('.top_container');
                topContainer.parentNode.removeChild(topContainer);
            }
        }
    });

    if(largeurFenetre <= 800){
        choix_uv_label.innerHTML = "Autoriser changements UV(s)";
    }else{
        choix_uv_label.innerHTML = "Autoriser les changements de groupe au sein de mes UV(s)";
    }
}

window.addEventListener('resize', mettreAJourContenuProfil);
mettreAJourContenuProfil();