function svgSwapEnter(event){
    if (event.target.closest(".hoverCours").style.flexDirection === "row"){
        event.target.style.height = "15%";
        event.target.style.width = "15%";
    } else {
        event.target.style.height = "8%";
        event.target.style.width = "8%";
    }
    event.target.src = "../svg/poster_swap.svg";
    event.target.style.cursor = "pointer";

}

function svgpDisplaceEnter(event){
    if (event.target.closest(".hoverCours").style.flexDirection === "row"){
        event.target.style.height = "15%";
        event.target.style.width = "15%";
    } else {
        event.target.style.height = "20%";
        event.target.style.width = "20%";
    }
    event.target.src = "../svg/displace_cours.svg";
    event.target.style.cursor = "pointer";

}

function svgpTrasheEnter(event){
    if (event.target.closest(".hoverCours").style.flexDirection === "row"){
        event.target.style.height = "15%";
        event.target.style.width = "15%";
    } else {
        event.target.style.height = "25%";
        event.target.style.width = "25%";
    }
    event.target.src = "../svg/supprimer.svg";
    event.target.style.cursor = "pointer";

}

function svgSwapLeave(event){
    event.target.style.height = "30px";
    event.target.style.width = "30px";
    event.target.src = "../svg/swap.svg";
    event.target.style.cursor = "default";
}

function svgDisplaceLeave(event){
    event.target.style.height = "30px";
    event.target.style.width = "30px";
    event.target.src = "../svg/displace.svg";
    event.target.style.cursor = "default";
}

function svgTrashLeave(event){
    event.target.style.height = "30px";
    event.target.style.width = "30px";
    event.target.src = "../svg/trash.svg";
    event.target.style.cursor = "default";
}

function posterSwap(event){
    var codeUV = localStorage.getItem("codeUV");
    var creneau = localStorage.getItem("creneau");
    var heureDebut = localStorage.getItem("heureDebut");
    var heureFin = localStorage.getItem("heureFin");
    var salle = localStorage.getItem("salle");
    var type = localStorage.getItem("type");

    formulaire = document.getElementById("nouveau_pannel");
    formulaire.querySelector("#input-uv").value = codeUV;
    formulaire.querySelector("#input-creneau").value = creneau;
    formulaire.querySelector("#input-hdebut").value = heureDebut.replace("h" , ":");
    formulaire.querySelector("#input-hfin").value = heureFin.replace("h" , ":");
    formulaire.querySelector("#input-salle").value = salle;
    //formulaire.getElementById("addCreneau-input-type").value = type;

    formulaire.style.display = "block";
}

document.getElementById("emploi_du_temps").addEventListener("mousemove" , function (event) {
    if (event.target.className === "cours"){
    // Récupérer l'élément sur lequel l'événement a eu lieu
        var coursElement = event.target;
        var jour = coursElement.closest('.jour').id;

    // Récupérer le texte de la balise h2
        var h2Text = coursElement.querySelector('h2.UV').textContent;

    // Initialiser les variables
        var codeUV = null;
        var semaine = null;

    // Vérifier si le texte de h2 contient un "-"
        if (h2Text.includes('-')) {
            var segments = h2Text.split('-');
            codeUV = segments[0].trim();
            semaine = segments[1].trim();
        } else {
            codeUV = h2Text.trim();
        }

    // Récupérer le texte de la balise <p> contenant l'heure
        var heuresText = coursElement.querySelector('p').textContent.trim();

    // Diviser le texte des heures en heureDebut et heureFin
        var heuresSegments = heuresText.split('-');
        var heureDebut = heuresSegments[0].trim();
        var heureFin = heuresSegments[1].trim();

    // Récupérer le texte de la balise <p> contenant la salle
        var salle = coursElement.querySelector('p:nth-of-type(2)').textContent.trim();

        // Mise en cache des données pour les réutiliser si besoin de swap.
        localStorage.setItem("codeUV",codeUV);
        localStorage.setItem("creneau",jour);
        localStorage.setItem("heureDebut",heureDebut);
        localStorage.setItem("heureFin",heureFin);
        localStorage.setItem("salle",salle);

        hoverCours = document.getElementsByClassName("hoverCours")[0];
        hoverCours.style.display = "flex";
        if (parseFloat(event.target.style.height) < 15){
            hoverCours.style.flexDirection = "row";
            hoverCours.style.height = "30%";
            hoverCours.style.width = "30%";
        }
        else{
            hoverCours.style.flexDirection = "column";
            hoverCours.style.height = "auto";
            hoverCours.style.width = "auto";
        }
        if (event.target.style.width == "50%"){
            hoverCours.style.left = `${event.target.getBoundingClientRect().x + 95}px`
            hoverCours.style.top = `${event.target.getBoundingClientRect().y - 10}px`
            if (event.target.style.left == "50%"){
                localStorage.setItem("type", "B");
            } else{
                localStorage.setItem("type", "A");
            }
        }
        else{
            localStorage.setItem("type", null);
            hoverCours.style.left = `${event.target.getBoundingClientRect().x + 190}px`
            hoverCours.style.top = `${event.target.getBoundingClientRect().y - 10}px`
        }

    }

});

document.getElementById("emploi_du_temps").addEventListener("mouseout" , function (event) {
    if (event.target.className !== "cours" && event.target.className !== "hoverCours" && event.target.parentElement.className !== "cours") {
        hoverCours = document.getElementsByClassName("hoverCours")[0];
        hoverCours.style.display = "none";
    }

});

function addCreneau(event) {
    // Empêcher la propagation du clic à l'extérieur du formulaire
    event.stopPropagation();
    formulaire = document.getElementById("addCreneau");

    // Vérifier si le formulaire est actuellement caché
    if (formulaire.style.display !== "block") {
        // Afficher le formulaire
        formulaire.style.display = "block";
    } else {
        // Le formulaire est déjà affiché, vérifier si le clic est à l'extérieur du formulaire
        var isInsideFormulaire = formulaire.contains(event.target);
        if (!isInsideFormulaire) {
            // Masquer le formulaire
            formulaire.style.display = 'none';
            formulaire.reset();
        }
    }
}
/* ici le bug nouveau_pannel afficher semaine ne fonctionne pas*/
checkbox = document.getElementById("addCreneau").querySelector("#addCreneau-input-semaine");
checkbox.addEventListener('change', function () {
    var nouveau_pannel = document.getElementById("addCreneau")
    var choix_semaine = nouveau_pannel.querySelector("#addCreneau-choix-semaine");
    // Modifiez la visibilité de l'élément en fonction de l'état de la checkbox
    if (checkbox.checked) {
        choix_semaine.style.display = "block";
        lastHeight= nouveau_pannel.scrollHeight;
        nouveau_pannel.style.height = nouveau_pannel.scrollHeight + 10 + "px";
    } else {
        nouveau_pannel.style.height = lastHeight + "px"; // Ajustez ici la hauteur minimale souhaitée
        choix_semaine.style.display = "none";
    }
});

formAddCreneau = document.getElementById('addCreneau');
formAddCreneau.querySelector('#addCreneau-input-hfin').addEventListener('change', function() {
    var [heures, minutes] = roundMinutes(this.value);
    this.value = heures.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');
});

formAddCreneau.querySelector('#addCreneau-input-hdebut').addEventListener('change', function() {
    var [heures, minutes] = roundMinutes(this.value);
    this.value = heures.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');
});

var formulaire = document.getElementById('addCreneau');

// Ajouter un gestionnaire d'événements pour le clic sur le document
document.addEventListener('click', function (event) {
    var formulaire = document.getElementById('addCreneau');
    // Vérifier si l'élément cliqué n'appartient pas au formulaire ni à ses enfants
    if (!formulaire.contains(event.target)) {
        // Masquer le formulaire
        formulaire.style.display = 'none';
        formulaire.reset();
        formulaire.querySelector("#addCreneau-choix-semaine").style.display = "none";
    }
});

// Empêcher la propagation du clic à l'intérieur du formulaire
formulaire.addEventListener('click', function (event) {
    event.stopPropagation();
});

// Ajouter un gestionnaire d'événements pour le clic sur le bouton "Poster la demande"
var boutonPoster = document.getElementById('bouton_non_submit');
boutonPoster.addEventListener('click', function () {
    // Afficher le formulaire
    formulaire.style.display = 'block';
});

// Empêcher la propagation du clic à l'intérieur du formulaire
formulaire.addEventListener('click', function (event) {
    event.stopPropagation();
});
function roundMinutes(valeur){
    var heureActuelle = valeur;

    var [heures, minutes] = heureActuelle.split(':');

    minutes = Math.round(minutes / 15) * 15;

    if (minutes === 60) {
        heures = parseInt(heures, 10) + 1;
        minutes = 0;
    }
    return [heures , minutes]
}
document.getElementById('addCreneau').addEventListener('submit', function (event) {
    /* Penser à vérifier les valeurs entrées !!!!!*/
    // Empêcher le rechargement de la page par défaut
    event.preventDefault();
    form = document.getElementById('addCreneau');

    // Récupérer les valeurs des champs du formulaire
    var uv = form.querySelector('#addCreneau-input-uv').value;
    var creneau = form.querySelector('#addCreneau-input-creneau').value;
    var hdebut = form.querySelector('#addCreneau-input-hdebut').value;
    var hfin = form.querySelector('#addCreneau-input-hfin').value;
    var salle = form.querySelector('#addCreneau-input-salle').value;
    var type = form.querySelector('#addCreneau-input-type').value;
    var semaine = form.querySelector('#addCreneau-input-semaine').checked;
    var semainechoix = form.querySelector('input[name="semainechoix"]:checked') ? form.querySelector('input[name="semainechoix"]:checked').value : null;

    hdebut = hdebut.replace(":" , "h");
    hfin = hfin.replace(":" , "h");
    let cours = new Cours(uv, hdebut, hfin, creneau, salle , semainechoix , type);
    createCours(cours);
    form.reset();
    form.querySelector("#addCreneau-choix-semaine").style.display = "none";
    form.style.display = "none";
});

var lundi = document.getElementById("lundi").getElementsByClassName("endroit_cours")[0];
var mardi = document.getElementById("mardi").getElementsByClassName("endroit_cours")[0];
var mercredi = document.getElementById("mercredi").getElementsByClassName("endroit_cours")[0];
var jeudi = document.getElementById("jeudi").getElementsByClassName("endroit_cours")[0];
var vendredi = document.getElementById("vendredi").getElementsByClassName("endroit_cours")[0];
var samedi = document.getElementById("samedi").getElementsByClassName("endroit_cours")[0];



var debutEDT = 8;
var finEDT = 20;

let conteneurHours = document.getElementsByClassName("conteneurHours")[0];
conteneurHours.className = "conteneurHours";

for (var heure = debutEDT ; heure <= finEDT ; heure++){

    let textHour = document.createElement("h4");
    textHour.innerHTML = `${heure}h00`;
    textHour.style.height = "5.55vh";
    textHour.id = `h${heure}`;

    conteneurHours.appendChild(textHour);
}

class Cours {
    constructor(codeUV, horaireDebut, horaireFin, jour, salle, semaine = null, couleur = null , type = null) {
        this.codeUV = codeUV;
        this.horaireDebut = horaireDebut;
        this.horaireFin = horaireFin;
        this.jour = jour;
        this.salle = salle;
        this.semaine = semaine;
        this.type = type;
        this.couleur = couleur;
    }

    afficherDetails() {
        console.log(`Code UV: ${this.codeUV}`);
        console.log(`Horaire début: ${this.horaireDebut}`);
        console.log(`Horaire fin: ${this.horaireFin}`);
        console.log(`Jour: ${this.jour}`);
        console.log(`Salle: ${this.salle}`);
    }
}

let cours1 = new Cours("MT23", "10h15", "12h15", "lundi", "FA104" , "B");
let cours2 = new Cours("IC05", "12h30", "13h00", "mardi", "FA104");
let cours3 = new Cours("MT02", "10h15", "11h15", "mardi", "FA104");
let cours4 = new Cours("PS21", "14h15", "16h15", "vendredi", "FA104");
let cours5 = new Cours("CACA", "08h00", "10h00", "mercredi", "FA104");
let cours6 = new Cours("MT23", "11h00", "13h00", "lundi", "FA104" , "A");
var colorList = [
    "#A7C4BC", "#D0C4B0", "#B0C4C4", "#C4A4B0", "#C4B8A4",
    "#B0C4B3", "#B0A4C4", "#C4B0A4", "#A4B0C4", "#C4B0B0",
    "#A4C4B0", "#C4B0C1", "#B0C1C4", "#C1C4A4", "#C4B0A7",
    "#A4B0C4", "#C4A790", "#A7C4B0", "#C4A4A4", "#B0A7C4"
];
var coursColors = {};
var liste = [cours1, cours2, cours3, cours4, cours5 , cours6]
var endroit_cours;
var cours;

var listeJour = [lundi, mardi, mercredi, jeudi, vendredi, samedi];
for (var i of listeJour) {
    for(var j=0; j<=48 ; j++){
        if(j%4 === 0 & j !== 0){
            if (j !== 48){
                i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\"> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class='dash' style='height:10px'></div> </div>";
            }
            else{
                i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\"> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class='nondash' style='height:10px'></div> </div>";

            }
        }
    }
}

function calculDecimal(nombre) {
    var heuresMinutesDebut = nombre.split('h');
    var heuresDebut = parseInt(heuresMinutesDebut[0], 10);
    var minutesDebut = parseInt(heuresMinutesDebut[1], 10);

    return heuresDebut + minutesDebut / 60;
}

function calculPourcentage(nombre , nbHeureEDT , tailleEDT) {

    var pixel = nombre * tailleEDT / nbHeureEDT;
    return pixel * 100 / tailleEDT;
}

function getRandomColor(listOfColors) {
    if (listOfColors.length === 0) {
        console.log("La liste de couleurs est vide !");
        return null;
    }
    // Générer un index aléatoire
    const randomIndex = Math.floor(Math.random() * listOfColors.length);

    // Récupérer la couleur à l'index aléatoire
    const randomColor = listOfColors[randomIndex];

    // Supprimer la couleur de la liste
    listOfColors.splice(randomIndex, 1);

    // Retourner la couleur sélectionnée
    return randomColor;
}
for (var i = 0; i < liste.length; i++) {
    createCours(liste[i]);
};

function createCours(cours){
    if(cours.jour == "lundi"){
        endroit_cours = lundi;
    }
    if(cours.jour == "mardi"){
        endroit_cours = mardi;
    }
    if(cours.jour == "mercredi"){
        endroit_cours = mercredi;
    }
    if(cours.jour == "jeudi"){
        endroit_cours = jeudi;
    }
    if(cours.jour == "vendredi"){
        endroit_cours = vendredi;
    }
    if(cours.jour == "samedi"){
        endroit_cours = samedi;
    }

    if (!(cours.codeUV in coursColors)) {
        coursColors[cours.codeUV] = getRandomColor(colorList);
    }
    cours.couleur = coursColors[cours.codeUV];

    endroit_cours.innerHTML += '<div class="cours"><h2 class="UV">' + '</h2><p>' + cours.horaireDebut + '-' + cours.horaireFin + '</p><p>' + cours.salle + '</p></div>'
    coursElement = endroit_cours.getElementsByClassName("cours")[endroit_cours.getElementsByClassName("cours").length -1];

    var tailleEDT = endroit_cours.offsetHeight;
    var nbHeureEDT = 12;
    var heureDebutEDT = 8;
    var heuresDecimalesDebut = calculDecimal(cours.horaireDebut);

    var heuresDecimalesFin = calculDecimal(cours.horaireFin);

    var tempsCours = heuresDecimalesFin - heuresDecimalesDebut;

    var pourcentageTop = calculPourcentage(heuresDecimalesDebut - heureDebutEDT , nbHeureEDT , tailleEDT);

    var pourcentageHeight = calculPourcentage(tempsCours , nbHeureEDT , tailleEDT);

    coursElement.style.height = pourcentageHeight + "%";
    coursElement.style.overflow = "hidden";

    coursElement.style.top = pourcentageTop + "%";

    coursElement.style.background = cours.couleur;

    if (cours.semaine == null){
        coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV;
    } else {

        if (cours.semaine === "B"){
            coursElement.style.left = "50%";
            coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV + " - B";
        } else {
            coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV + " - A";
        }
        coursElement.style.width = "50%";
    }
    if (pourcentageHeight < 10){
        coursElement.style.flexDirection = "row";
        coursElement.style.fontSize = pourcentageHeight * 8 + '%';
        coursElement.style.gap = "4px";
        for (element of coursElement.children){
            element.style.fontSize = "12px";
        }
    } else {
        coursElement.style.fontSize = pourcentageHeight * 5 + '%';
    }
}