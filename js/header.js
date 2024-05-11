

var tempsCurrentCours;
var bouton_nouveau = document.getElementsByClassName("bouton_nouveau");
var nouveau_pannel = document.getElementById("nouveau_pannel");
var ul_nouveau = document.getElementById("ul_nouveau");

var largeurEcran = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

var bouton_non_submit = document.getElementById("bouton_non_submit");
var boutons_message = document.getElementById("boutons_message");
var bouton_retour = document.getElementById("bouton_retour");
var bouton_ok = document.getElementById('bouton_ok');
var bouton_impossible_uv = document.getElementById("bouton_impossible_uv");
var bouton_remplacer = document.getElementById("bouton_remplacer");
var bouton_ajouter_creneau = document.getElementById("bouton_ajouter_creneau");
var bouton_continuer = document.getElementById("bouton_continuer");
var boutons_uv = document.getElementById("boutons_uv");

var message_pression = document.getElementById("message_pression");
var message_insertion = document.getElementById('message_insertion');
var message_impossible_uv = document.getElementById("message_impossible_uv");
var message_uv_type = document.getElementById("message_uv_type");
var message_changement_creneau = document.getElementById("message_changement_creneau");
var message_demande_recu_changement_creneau = document.getElementById("message_demande_recu_changement_creneau");
var message_creneau_incompatible_semaine = document.getElementById("message_creneau_incompatible_semaine");
var message_envoie_swap = document.getElementById("message_envoie_swap");
var message_meme_creneau_existant = document.getElementById('message_meme_creneau_existant');
var message_creneau_deja_accepte = document.getElementById("message_creneau_deja_accepte");

var input_type = document.getElementById("input-type");
var input_salle = document.getElementById("input-salle");
var input_hfin = document.getElementsByClassName("input-hfin");
var input_hdebut = document.getElementsByClassName("input-hdebut");
var input_creneau = document.getElementById("input-creneau");
var input_motivation_autre = document.getElementById("input-motivation-autre");
var input_motivation = document.getElementById("input-motivation");
var input_uv = document.getElementById("input-uv");
var motivation = document.getElementById("li_motivation");
var texte_nouveau = document.getElementById("div_debut_nouveau").getElementsByTagName("h1")[0];
var checkbox = document.getElementById('input-semaine');
var choix_semaine = document.getElementById('choix-semaine');

var notifications = document.getElementsByClassName("notification");
var notification_pannel = document.getElementById("notification_pannel");
var nombreClickNotification = 0;

var ecran = document.getElementById("ecran");

var navBar = document.querySelector("nav");
var bouton_menu = document.getElementById("bouton_menu");
var boutons_confirmation = document.getElementById("boutons_confirmation");
var menu_pannel = document.getElementById("menu_pannel");



var largeurFenetre;
var lastHeight;

var list_input=[input_creneau,input_type,input_salle,input_hfin[0],input_hfin[1],input_hdebut[0],input_hdebut[1],input_uv];

for(var element of input_hfin){
    element.addEventListener('change', function() {

        var heureActuelle = this.value;

        var [heures, minutes] = heureActuelle.split(':');

        minutes = Math.round(minutes / 15) * 15;

        if (minutes === 60) {
            heures = parseInt(heures, 10) + 1;
            minutes = 0;
        }

        this.value = heures.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');
        for(var autre_element of input_hfin){
            if (autre_element !== element) {
                autre_element.value = event.target.value;
            }
        };

        if(tempsCurrentCours){
            for(var element_hdebut of input_hdebut){
                // Calculer la différence en minutes
                var differenceEnMinutes = convertirEnMinutes(this.value) - convertirEnMinutes(tempsCurrentCours);

                // Mettre à jour la valeur du résultat
                element_hdebut.value = convertirEnFormatTime(differenceEnMinutes);
            }
        }
    });
}

function shakeElement(element){
    element.classList.add("shake-element");
    setTimeout(function() {
        element.classList.remove("shake-element");
    }, 200);
}

// Fonction pour convertir une heure au format HH:mm en minutes
function convertirEnMinutes(heure) {
    var [heures, minutes] = heure.split(':').map(Number);
    return heures * 60 + minutes;
}

// Fonction pour convertir une durée en minutes au format HH:mm
function convertirEnFormatTime(dureeEnMinutes) {
    var heures = Math.floor(dureeEnMinutes / 60);
    var minutes = dureeEnMinutes % 60;

    // Formater la nouvelle heure au format HH:mm
    var heuresFormat = heures.toString().padStart(2, '0');
    var minutesFormat = minutes.toString().padStart(2, '0');

    return heuresFormat + ':' + minutesFormat;
}

for(var element of input_hdebut){
    element.addEventListener('change', function() {
        var heureActuelle = this.value;

        var [heures, minutes] = heureActuelle.split(':');

        minutes = Math.round(minutes / 15) * 15;

        if (minutes === 60) {
            heures = parseInt(heures, 10) + 1;
            minutes = 0;
        }

        this.value = heures.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');
        for(var autre_element of input_hdebut){
            if (autre_element !== element) {
                autre_element.value = event.target.value;
            }
        };
        if(tempsCurrentCours){
            for(var element_hfin of input_hfin){
                // Calculer la différence en minutes
                var differenceEnMinutes = convertirEnMinutes(this.value) + convertirEnMinutes(tempsCurrentCours);

                // Mettre à jour la valeur du résultat
                element_hfin.value = convertirEnFormatTime(differenceEnMinutes);
            }
        }
    });
}


bouton_non_submit.addEventListener("click", function() {
    event.preventDefault();
    var type = encodeURIComponent(input_type.value);
    var salle = encodeURIComponent(input_salle.value);
    var creneau = encodeURIComponent(input_creneau.value);
    var uv = encodeURIComponent(input_uv.value);
    if (window.innerWidth <= 550 && window.innerHeight <= 550) {
        input_hfin[0].value = input_hfin[1].value;
        input_hdebut[1].value = input_hdebut[0].value;
    } else {
        input_hfin[1].value = input_hfin[0].value;
        input_hdebut[0].value = input_hdebut[1].value;
    }
    var hfin = encodeURIComponent(input_hfin[1].value);
    var hdebut = encodeURIComponent(input_hdebut[1].value);

    if(type === "" || salle === "" || hfin === "" || hdebut === "" || creneau === "" || uv === ""){
        for(var element of list_input){
            var pElement = element.parentNode.querySelector("p")
            if(encodeURIComponent(element.value) === ""){
                pElement.classList.toggle('hidden', false);
            }else{
                pElement.classList.toggle('hidden', true);
            }
        }
        bouton_non_submit.classList.add("shake-element");
        setTimeout(function() {
            bouton_non_submit.classList.remove("shake-element");
        }, 200);
    }else if(uv.length != 4){
        input_uv.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
    }else if(!["TD","TP","CM"].includes(type)){
        input_uv.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_type.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
    }else if(!["lundi","mardi","mercredi","jeudi","vendredi","samedi"].includes(creneau)){
        input_type.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_creneau.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
    }else if(hdebut >= hfin){
        input_creneau.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_hdebut[0].parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
        input_hdebut[1].parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
    } else{
        input_hdebut[0].parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_hdebut[1].parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_creneau.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_type.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_uv.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        bouton_non_submit.classList.toggle('hidden', true);
        for(var element of list_input){
            var pElement = element.parentNode.querySelector("p");
            pElement.classList.toggle('hidden', true);
        }
        if (bouton_non_submit.className.includes("submitSwap")){
            input_uv.disabled = false;
            input_type.disabled = false;
            document.getElementById("swapIdDemande").value = localStorage.getItem("idDemande");

            var titleDiv = document.getElementById("swapRecap");
            ul_nouveau.classList.toggle('hidden', true);
            boutons_message.classList.toggle('hidden', false);
            message_pression.classList.toggle('hidden', false);
            document.getElementById("sendSwap").classList.toggle('hidden' , false);
            document.getElementById("MonCreneauSwap1").classList.toggle('hidden' , false);
            document.getElementById("MonCreneauSwap2").classList.toggle('hidden' , false);
            document.getElementById("newDemandeSwap").classList.toggle('hidden' , true);
            titleDiv.classList.toggle('hidden' , false);
            titleDiv.innerHTML = `Récapitulatif : ${input_type.value} de ${input_uv.value}`
            document.getElementById("swapJour1").innerHTML = `${input_creneau.value[0].toUpperCase() + input_creneau.value.slice(1)}`
            document.getElementById("swapSalle1").innerHTML = `${input_salle.value}`
            document.getElementById("swapCreneau1").innerHTML = `${input_hdebut[1].value} - ${input_hfin[1].value}`

            document.getElementById("swapSalle2").innerHTML = `${localStorage.getItem("salle")}`
            document.getElementById("swapJour2").innerHTML = `${localStorage.getItem("jour")}`
            document.getElementById("swapCreneau2").innerHTML = `${localStorage.getItem("hdebut").slice(0,-3)} - ${localStorage.getItem("hfin").slice(0,-3)}`


            if (checkbox.checked){
                const choixSemaine = document.querySelector('input[name="semainechoix"]:checked').value;
                document.getElementById("spanSemaine1").classList.toggle("hidden" , false);
                document.getElementById("spanSemaine2").classList.toggle("hidden" , false);
                document.getElementById("swapSemaine1").innerHTML = choixSemaine;
                document.getElementById("swapSemaine2").innerHTML = `${localStorage.getItem("semaine")}`;
            }
            else{
                document.getElementById("spanSemaine1").classList.toggle("hidden" , true);
                document.getElementById("spanSemaine2").classList.toggle("hidden" , true);
            }
        } else {
            ul_nouveau.classList.toggle('hidden', true);
            boutons_message.classList.toggle('hidden', false);
            message_pression.classList.toggle('hidden', false);
        }
    }
});

bouton_retour.addEventListener("click", function() {
    event.preventDefault();
    bouton_non_submit.classList.toggle('hidden', false);
    ul_nouveau.classList.toggle('hidden', false);
    boutons_message.classList.toggle('hidden', true);
    message_pression.classList.toggle('hidden', true);
    if (document.getElementById("swapIdDemande").value != ""){
        input_uv.disabled = true;
        input_type.disabled = true;
        document.getElementById("sendSwap").classList.toggle('hidden' , true);
        document.getElementById("newDemandeSwap").classList.toggle('hidden' , false);
        document.getElementById("swapRecap").classList.toggle("hidden" , true);
    }
});

// Ajoutez un écouteur d'événements pour détecter les changements de la checkbox
checkbox.addEventListener('change', function () {
    // Modifiez la visibilité de l'élément en fonction de l'état de la checkbox
    choix_semaine.classList.toggle('hidden', !checkbox.checked);
    if (checkbox.checked) {
        lastHeight= nouveau_pannel.scrollHeight;
        document.getElementById("sA-choix").checked = true;
        document.getElementById("div_milieu_nouveau").style.height = nouveau_pannel.scrollHeight + 10 + "px";
    } else {
        document.getElementById("div_milieu_nouveau").style.height = ""; // Ajustez ici la hauteur minimale souhaitée
    }
});


// Il y a le notification du menu PC et celui du menu téléphone donc on boucle pour tous les deux les selectionner
function notificationClick(){
    nombreClickNotification++;
    var xhr = new XMLHttpRequest();

    // Configurer la requête
    xhr.open("POST", "", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Envoyer la requête avec les données
    xhr.send("view=" + "1");
    for(var j of document.getElementsByClassName("cercle")){
        j.classList.remove("orange");;
    }

    var notifs = document.getElementsByClassName("notif");
    if(nombreClickNotification >= 2){
        for(var j of notifs){
            if(!j.classList.contains("type_1")){
                j.classList.toggle('viewed', true);
            }
        }
    }

    var conteneurFiltre = document.getElementById("menuFiltre");
    var largeurFenetre = window.innerWidth;

    if(conteneurFiltre !== null && typeof conteneurFiltre !== 'undefined' && largeurFenetre < 1200){
        conteneurFiltre.style.display = "none";
    }

    nouveau_pannel.style.display = "none"

    notification_pannel.style.display = (notification_pannel.style.display === "none" || notification_pannel.style.display === "") ? "flex" : "none";

    menu_pannel.style.left = '-65%';
};

document.getElementById("croix_nouveau").addEventListener("click", function() {

    nouveau_pannel.style.display = "none";
    menu_pannel.style.left = '-65%';

    choix_semaine.classList.toggle('hidden', true);
    document.getElementById("div_milieu_nouveau").style.height = "";

    notification_pannel.style.display = "none";

});

document.addEventListener("click" , function (event) {

    if (!(event.target.closest("#nouveau_pannel")) && !(event.target.closest(".infos_auteur")) && !(event.target.closest(".gauche_container")) && document.getElementById("nouveau_pannel") != "none" && event.target.className != "bouton_nouveau" && event.target.parentNode.className != "bouton_nouveau" && event.target.className !== "dash" && event.target.id !== "displace" && event.target.className != "div_demande"){
        document.getElementById("nouveau_pannel").style.display = "none";
    }

    if (!(event.target.closest("#notification_pannel")) && document.getElementById("notification_pannel") != "none" && event.target.className != "notification"){
        document.getElementById("notification_pannel").style.display = "none";
    }

    if (!(event.target.closest("#menu_pannel")) && document.getElementById("menu_pannel") != "none" && event.target.id != "bouton_menu"){
        document.getElementById("menu_pannel").style.left = '-65%';
    }

});
function nouveauClick() {
   
    choix_semaine.classList.toggle('hidden', true);
    document.getElementById("div_milieu_nouveau").style.height = "";
    texte_nouveau.innerHTML = "Nouvelle demande de Swap";

    nouveau_pannel.reset();
    message_impossible_uv.classList.toggle('hidden', true);
    message_creneau_deja_accepte.classList.toggle('hidden', true);
    bouton_impossible_uv.classList.toggle('hidden', true);
    document.getElementById("bouton_remplacer").classList.toggle('hidden', true)
    document.getElementById("message_changement_creneau").classList.toggle('hidden', true);

    bouton_ajouter_creneau.classList.toggle('hidden', true);
    bouton_continuer.classList.toggle('hidden', true);
    motivation.classList.toggle('hidden', false);
    input_salle.disabled = false
    input_uv.disabled = false
    input_type.disabled = false
    bouton_ok.classList.toggle('hidden', true);
    boutons_confirmation.classList.toggle("hidden" , true);
    message_creneau_incompatible_semaine.classList.toggle("hidden" , true)
    message_envoie_swap.classList.toggle("hidden" , true);
    message_demande_recu_changement_creneau.classList.toggle("hidden" , true)
    message_insertion.classList.toggle('hidden', true);
    bouton_non_submit.classList.toggle('hidden', false);
    ul_nouveau.classList.toggle('hidden', false);
    boutons_message.classList.toggle('hidden', true);
    message_pression.classList.toggle('hidden', true);
    checkbox.disabled = false;

    /* Réinitialiser l'affichage du formulaire si changement.  */
    document.getElementById("creneauFirstLine").classList.toggle("hidden" , false);
    document.getElementById("creneauThirdLine").classList.toggle("hidden" , false);
    document.getElementById("activiteFirstLine").classList.toggle("hidden" , true);
    document.getElementById("activiteThirdLine").classList.toggle("hidden" , true);

    document.getElementById("sendSwap").classList.toggle('hidden' , true);
    document.getElementById("newDemandeSwap").classList.toggle('hidden' , false);
    document.getElementById("swapRecap").classList.toggle("hidden" , true);
    document.getElementById("MonCreneauSwap1").classList.toggle('hidden' , true);
    document.getElementById("MonCreneauSwap2").classList.toggle('hidden' , true);
    document.getElementById("ancienCreneauSwap2").classList.toggle('hidden' , true);
    document.getElementById("ancienCreneauSwap1").classList.toggle('hidden' , true);
    var conteneurFiltre = document.getElementById("menuFiltre");
    var largeurFenetre = window.innerWidth;

    if(conteneurFiltre !== null && typeof conteneurFiltre !== 'undefined' && largeurFenetre < 1200){
        conteneurFiltre.style.display = "none";
    }
    nouveau_pannel.style.display = (nouveau_pannel.style.display === "none" || nouveau_pannel.style.display === "") ? "flex" : "none";
    menu_pannel.style.left = '-65%';

    notification_pannel.style.display = "none";
    input_hdebut[0].parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
    input_hdebut[1].parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
    input_creneau.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
    input_type.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
    input_uv.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
    for(var element of list_input){
        var pElement = element.parentNode.querySelector("p");
        pElement.classList.toggle('hidden', true);
    }

    var bodyHasScrollbar = isScrollbarVisible(document.getElementById("div_milieu_nouveau"));
    if (bodyHasScrollbar) {
        document.getElementById("ul_nouveau").style.justifyContent = "unset";
    } else {
        document.getElementById("ul_nouveau").style.justifyContent = "center";
    }

    if (tempsCurrentCours){
        tempsCurrentCours = null;
    }
    document.getElementById('update_choix').value = '0';
};

bouton_continuer.addEventListener("click" , function (ev) {
    bouton_continuer.classList.toggle('hidden' , true);
    message_demande_recu_changement_creneau.classList.toggle('hidden' , true);
    bouton_remplacer.classList.toggle('hidden', false);
    document.getElementById('sendSwap').classList.toggle('hidden' , false);
    document.getElementById('ancienCreneauSwap1').classList.toggle('hidden' , false);
    document.getElementById('ancienCreneauSwap2').classList.toggle('hidden' , false);
})

function isScrollbarVisible(element) {
    return element.scrollHeight > element.clientHeight;
}

function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;
    var label_motivation = document.getElementById("label_motivation");
    var choix_li_nouveau_panel = document.getElementById("choix-li-nouveau-panel");
    var choix_semaine = document.getElementById("choix-semaine");
    var label_input_semaine = document.getElementById("input-semaine").parentElement.querySelector("label");
    if (largeurFenetre <= 350) {
        label_motivation.innerHTML = 'Motivation: (!)';
        label_motivation.title = 'Facultatif';
        choix_li_nouveau_panel.style.flexDirection = "column";
        choix_semaine.style.flexDirection = "column";
    } else {
        label_motivation.innerHTML = 'Motivation: (facultatif)';
        label_motivation.title = '';
        choix_li_nouveau_panel.style.flexDirection = "row";
        choix_semaine.style.flexDirection = "row";
    }
    var bodyHasScrollbar = isScrollbarVisible(document.getElementById("div_milieu_nouveau"));
    if (bodyHasScrollbar) {
        document.getElementById("ul_nouveau").style.justifyContent = "unset";
    } else {
        document.getElementById("ul_nouveau").style.justifyContent = "center";
    }
}
    
// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenuProfil);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenuProfil();

bouton_menu.addEventListener("click", function() {
    var conteneurFiltre = document.getElementById("menuFiltre");
    var largeurFenetre = window.innerWidth;

    if(conteneurFiltre !== null && typeof conteneurFiltre !== 'undefined' && largeurFenetre < 1200){
        conteneurFiltre.style.display = "none";
    }
    nouveau_pannel.style.display = "none"

    menu_pannel.style.left = (menu_pannel.style.left <= '0' || menu_pannel.style.left === "") ? "0" : '-65%';

    notification_pannel.style.display = "none";
});

var bouton_accepter_notif = document.getElementsByClassName("bouton_accepter_notif");

for (let i = 0; i < bouton_accepter_notif.length; i++) {
    bouton_accepter_notif[i].addEventListener("click", function () {
        event.preventDefault();
        bouton_accepter_notif[i].parentNode.parentNode.getElementsByClassName("choix_notification")[0].value = 1;
        bouton_accepter_notif[i].parentNode.parentNode.submit();
    });
}

function reloadPage() {
    event.preventDefault();
    // Stocker le token dans le stockage local
    var tokenRemplacer = document.getElementById("csrf_token_remplacer");
    if (tokenRemplacer) {
        var tokenInput = document.getElementById("input_csrf_token_remplacer");
        tokenInput.value = tokenRemplacer.value;
    }
    // Recharger la page
    location.reload();
}

function updateReason(){
    if (input_motivation.value == "autre"){
        input_motivation_autre.classList.toggle('hidden' , false);
    } else {
        input_motivation_autre.classList.toggle('hidden' , true);
    }
}