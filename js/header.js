


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

var message_pression = document.getElementById("message_pression");
var message_insertion = document.getElementById('message_insertion');
var message_impossible_uv = document.getElementById("message_impossible_uv");
var message_uv_type = document.getElementById("message_uv_type");

var input_type = document.getElementById("input-type");
var input_salle = document.getElementById("input-salle");
var input_hfin = document.getElementsByClassName("input-hfin");
var input_hdebut = document.getElementsByClassName("input-hdebut");
var input_creneau = document.getElementById("input-creneau");
var input_uv = document.getElementById("input-uv");
var motivation = document.getElementById("li_motivation");
var texte_nouveau = document.getElementById("titre_nouveau").getElementsByTagName("h1")[0];
var checkbox = document.getElementById('input-semaine');
var choix_semaine = document.getElementById('choix-semaine');

var notifications = document.getElementsByClassName("notification");
var notification_pannel = document.getElementById("notification_pannel");

var ecran = document.getElementById("ecran");

var navBar = document.querySelector("nav");
var bouton_menu = document.getElementById("bouton_menu");
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
    });
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
    }else{
        input_hdebut[0].parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_hdebut[1].parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_creneau.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_type.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        input_uv.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
        for(var element of list_input){
            var pElement = element.parentNode.querySelector("p");
            pElement.classList.toggle('hidden', true);
        }
        bouton_non_submit.classList.toggle('hidden', true);
        ul_nouveau.classList.toggle('hidden', true);
        boutons_message.classList.toggle('hidden', false);
        message_pression.classList.toggle('hidden', false);
    }
});

bouton_retour.addEventListener("click", function() {
    event.preventDefault();
    bouton_non_submit.classList.toggle('hidden', false);
    ul_nouveau.classList.toggle('hidden', false);
    boutons_message.classList.toggle('hidden', true);
    message_pression.classList.toggle('hidden', true);
});

// Ajoutez un écouteur d'événements pour détecter les changements de la checkbox
checkbox.addEventListener('change', function () {
    // Modifiez la visibilité de l'élément en fonction de l'état de la checkbox
    choix_semaine.classList.toggle('hidden', !checkbox.checked);
    if (checkbox.checked) {
        lastHeight= nouveau_pannel.scrollHeight;
        document.getElementById("div_debut_nouveau").style.height = nouveau_pannel.scrollHeight + 10 + "px";
    } else {
        document.getElementById("div_debut_nouveau").style.height = ""; // Ajustez ici la hauteur minimale souhaitée
    }
});

// Il y a le notification du menu PC et celui du menu téléphone donc on boucle pour tous les deux les selectionner
for (var i = 0; i < notifications.length; i++) {
    notifications[i].addEventListener("click", function() {
        var xhr = new XMLHttpRequest();

        // Configurer la requête
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        // Envoyer la requête avec les données
        xhr.send("view=" + "1");

        var conteneurFiltre = document.getElementById("menuFiltre");
        
        var notifs = document.getElementsByClassName("notif");
        if(notification_pannel.style.right !== "" && notification_pannel.style.display !== ""){
            for(var j of notifs){
                if(!j.classList.contains("type_1")){
                    j.classList.toggle('viewed', true);
                }
            }
        }
        
        if(conteneurFiltre !== null && typeof conteneurFiltre !== 'undefined'){
            conteneurFiltre.style.display = "none";
        }

        nouveau_pannel.style.display = "none"

        notification_pannel.style.display = (notification_pannel.style.display === "none" || notification_pannel.style.display === "") ? "flex" : "none";
        
        menu_pannel.style.left = '-65%';
    });
};

document.getElementById("croix_nouveau").addEventListener("click", function() {
    
    nouveau_pannel.style.display = "none";
    menu_pannel.style.left = '-65%';
    
    choix_semaine.classList.toggle('hidden', true);
    document.getElementById("div_debut_nouveau").style.height = "";

    notification_pannel.style.display = "none";      
    
});

document.addEventListener("click" , function (event) {

    if (!(event.target.closest("#nouveau_pannel")) && document.getElementById("nouveau_pannel") != "none" && event.target.className != "bouton_nouveau" && event.target.className !== "dash" && event.target.id !== "displace"){
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
    document.getElementById("div_debut_nouveau").style.height = "";
    texte_nouveau.innerHTML = "Nouvelle demande de Swap";

    nouveau_pannel.reset();
    bouton_ajouter_creneau.classList.toggle('hidden', true);
    motivation.classList.toggle('hidden', false);
    input_salle.disabled = false
    input_uv.disabled = false
    input_type.disabled = false
    bouton_ok.classList.toggle('hidden', true);
    message_insertion.classList.toggle('hidden', true);
    bouton_non_submit.classList.toggle('hidden', false);
    ul_nouveau.classList.toggle('hidden', false);
    boutons_message.classList.toggle('hidden', true);
    message_pression.classList.toggle('hidden', true);
    var conteneurFiltre = document.getElementById("menuFiltre");
    
    if(conteneurFiltre !== null && typeof conteneurFiltre !== 'undefined'){
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
};


bouton_menu.addEventListener("click", function() {
    var conteneurFiltre = document.getElementById("menuFiltre");
    
    if(conteneurFiltre !== null && typeof conteneurFiltre !== 'undefined'){
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

function reloadPage(){
    event.preventDefault();
    location.reload();
}
