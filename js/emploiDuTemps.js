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

function trashClick(event){
    var coursID = localStorage.getItem("idCours");
    document.getElementById("trash").style.display = "none";
    document.getElementById("deleteCours");
    document.getElementById("deleteCours").style.display = "flex";
    if (parseFloat(document.getElementById(coursID).style.height) <= 10){
        document.getElementById("deleteCours").style.height = "10%";
    }

}

function deleteCours(event){
    var coursID = localStorage.getItem("idCours");
    document.getElementById(coursID).remove();
    document.getElementsByClassName("hoverCours")[0].style.display = "none";
}

function cancelDelete(event){
    document.getElementById("deleteCours").style.display = "none";
    document.getElementById("trash").style.display = "flex";
}

function posterSwap(event){
    event.stopPropagation();
    var codeUV = localStorage.getItem("codeUV");
    var creneau = localStorage.getItem("creneau");
    var heureDebut = localStorage.getItem("heureDebut");
    var heureFin = localStorage.getItem("heureFin");
    var salle = localStorage.getItem("salle");
    var type = localStorage.getItem("type");
    var semaine = localStorage.getItem("semaine");

    formulaire = document.getElementById("nouveau_pannel");
    formulaire.querySelector("#input-uv").value = codeUV;
    formulaire.querySelector("#input-creneau").value = creneau;
    formulaire.querySelector("#input-hdebut1").value = heureDebut.replace("h" , ":");
    formulaire.querySelector("#input-hfin1").value = heureFin.replace("h" , ":");
    formulaire.querySelector("#input-salle").value = salle;
    formulaire.querySelector("#input-type").value = type;
    if (semaine !== 'null'){
        checkbox = document.getElementById("input-semaine");
        checkbox.checked = true;
        document.getElementById("choix-semaine").className = "basique";
        if (semaine == "A"){
            document.getElementById("sA-choix").checked = true;
        } else {
            document.getElementById("sB-choix").checked = true;
        }
    } else {
        checkbox.checked = false;
        document.getElementById("choix-semaine").className = "basique hidden";
    }
    formulaire.style.display = "block";
}

document.getElementById("emploi_du_temps").addEventListener("mousemove" , function (event) {
    if (event.target.className === "cours"){
    // Récupérer l'élément sur lequel l'événement a eu lieu
        var coursElement = event.target;
        var jour = coursElement.closest('.jour').id;

    // Récupérer le texte de la balise h2
        var texte = coursElement.querySelector('h2.UV').textContent;

// Expression régulière pour extraire les informations
        var regex = /^([A-Z0-9]+) - (TD|TP|CM)(A|B)?$/;

// Correspondance avec l'expression régulière
        var match = texte.match(regex);
        // Le code UV est dans match[1]
        var codeUV = match[1];

        // Le type de matière est dans match[2]
        var typeMatiere = match[2];

        // La semaine est dans match[3], si elle existe
        var semaine = match[3] ? match[3] : null;

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
        localStorage.setItem("type",typeMatiere);
        localStorage.setItem("semaine",semaine);
        localStorage.setItem("idCours" , coursElement.id)

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
            hoverCours.style.top = `${event.target.getBoundingClientRect().y}px`
        }
        else{
            hoverCours.style.left = `${event.target.getBoundingClientRect().x + 190}px`
            hoverCours.style.top = `${event.target.getBoundingClientRect().y}px`
        }

    }

});

document.getElementById("emploi_du_temps").addEventListener("mouseout" , function (event) {
    if (event.target.className !== "cours" && event.target.className !== "hoverCours" && event.target.parentElement.className !== "cours") {
        hoverCours = document.getElementsByClassName("hoverCours")[0];
        hoverCours.style.display = "none";
        document.getElementById("trash").style.display = "block";
        document.getElementById("deleteCours").style.display = "none";
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
creneauCheckbox = document.getElementById("addCreneau").querySelector("#addCreneau-input-semaine");
creneauCheckbox.addEventListener('change', function () {
    var nouveau_pannel = document.getElementById("addCreneau")
    var choix_semaine = nouveau_pannel.querySelector("#addCreneau-choix-semaine");
    // Modifiez la visibilité de l'élément en fonction de l'état de la creneauCheckbox
    if (creneauCheckbox.checked) {
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
    let cours = new Cours(uv, hdebut, hfin, creneau, salle , semainechoix , null , type);
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
var dimanche = document.getElementById("dimanche").getElementsByClassName("endroit_cours")[0];



var debutEDT = 8;
var finEDT = 20;

let conteneurHours = document.getElementsByClassName("conteneurHours")[0];
conteneurHours.className = "conteneurHours";

for (var heure = debutEDT ; heure <= finEDT ; heure++){

    let textHour = document.createElement("h4");
    textHour.innerHTML = `${heure}h00`;
    textHour.style.height = "5.55vh";
    if (heure == parseInt(debutEDT)){
        textHour.id = `hdebut`;
    }
    else if (heure == parseInt(finEDT)){
        textHour.id = `hfin`;
    } else {
        textHour.id = `h${heure}`;
    }
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

let cours1 = new Cours("MT23", "10h15", "12h15", "lundi", "FA104" , "B" , null , "TD");
let cours2 = new Cours("IC05", "12h30", "13h00", "mardi", "FA104" , null , null , "TD");
let cours3 = new Cours("MT02", "10h15", "11h15", "mardi", "FA104" , null , null , "TD");
let cours4 = new Cours("PS21", "14h15", "16h15", "vendredi", "FA104" , null , null , "TD");
let cours5 = new Cours("CACA", "08h00", "10h00", "mercredi", "FA104", null , null , "TP");
let cours6 = new Cours("MT23", "11h00", "13h00", "lundi", "FA104" , "A" , null , "CM");
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

var listeJour = [lundi, mardi, mercredi, jeudi, vendredi, samedi, dimanche];
for (var i of listeJour) {
    for(var j=0; j<12 ; j++){
        if (j !== 12){
            i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\" > <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class='dash' style='height:10px'></div> </div>";
        }
        else{
            i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\"> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class='nondash' style='height:10px'></div> </div>";

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
    if(cours.jour == "dimanche"){
        endroit_cours = dimanche;
    }

    if (!(cours.codeUV in coursColors)) {
        coursColors[cours.codeUV] = getRandomColor(colorList);
        var spanColor = document.getElementById("couleurSpan");
        spanColor.innerHTML += "<span style='display: flex ; margin: 0 ; padding: 0 ; align-items: center ; gap: 10px'><h4> " + cours.codeUV + ": </h4> <input id='color" + cours.codeUV +"' value= "+ coursColors[cours.codeUV] +"  type='color' onchange='colorChange(event)'></span>"
    }
    cours.couleur = coursColors[cours.codeUV];
    var nbCours = document.getElementsByClassName("cours").length;
    endroit_cours.innerHTML += '<div class="cours" id= ' + parseInt(nbCours + 1) + '><h2 class="UV">' + '</h2><p>' + cours.horaireDebut + '-' + cours.horaireFin + '</p><p>' + cours.salle + '</p></div>'
    coursElement = endroit_cours.getElementsByClassName("cours")[endroit_cours.getElementsByClassName("cours").length -1];

    var tailleEDT = endroit_cours.offsetHeight;
    var heureDebutEDT = parseInt(document.getElementById("hdebut").innerHTML.split("h")[0]);
    var heureFinEDT = parseInt(document.getElementById("hfin").innerHTML.split("h")[0]);
    var nbHeureEDT = heureFinEDT - heureDebutEDT;
    var heuresDecimalesDebut = calculDecimal(cours.horaireDebut);

    var heuresDecimalesFin = calculDecimal(cours.horaireFin);

    console.log({"debut" : heureDebutEDT , "fin":heureFinEDT , "nbheure":nbHeureEDT});

    var tempsCours = heuresDecimalesFin - heuresDecimalesDebut;

    var pourcentageTop = calculPourcentage(heuresDecimalesDebut - heureDebutEDT , nbHeureEDT , tailleEDT);

    var pourcentageHeight = calculPourcentage(tempsCours , nbHeureEDT , tailleEDT);

    coursElement.style.height = pourcentageHeight + "%";
    coursElement.style.overflow = "hidden";

    coursElement.style.top = pourcentageTop + "%";

    coursElement.style.background = cours.couleur;

    if (cours.semaine == null){
        coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV + " - " + cours.type
    } else {

        if (cours.semaine === "B"){
            coursElement.style.left = "50%";
            coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV + " - " + cours.type +"B";
        } else {
            coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV + " - " + cours.type + "A";
        }
        coursElement.style.width = "50%";
        coursElement.getElementsByClassName("UV")[0].style.fontSize = "1.2em"
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

function colorChange(event){
    var UV = event.target.id.split("color")[1];
    var Liste_cours = document.getElementsByClassName("cours");
    for (var cours of Liste_cours){
        var texte = cours.querySelector('h2.UV').textContent;
        var regex = /^([A-Z0-9]+) - (TD|TP|CM)(A|B)?$/;
        var match = texte.match(regex);
        var currentCode = match[1];
        if (currentCode == UV){
            cours.style.background = event.target.value;
            coursColors[UV] = event.target.value;
        }

    }
}
var suivreLaSouris = false;
function suivreSouris(element) {
    suivreLaSouris = !suivreLaSouris;
    if(!suivreLaSouris){
        event.stopPropagation();
        document.removeEventListener("mousemove", suivreLaSouris);
    }
    document.addEventListener("mousemove", function(event) {
        //faire le changement de jour
        if (suivreLaSouris) {
            coursEnDeplacement = element;
            var newPosition = event.clientY - coursEnDeplacement.parentElement.clientHeight / 2 + coursEnDeplacement.clientHeight / 2;
            var roundedPosition = Math.round(newPosition / 10) * 10
            coursEnDeplacement.style.top = Math.min(Math.max(roundedPosition, 0), coursEnDeplacement.parentElement.clientHeight - coursEnDeplacement.clientHeight) + "px";

            var jours = document.querySelectorAll(".jour");
            jours.forEach(function(jour) {
                var rect = jour.getBoundingClientRect();
                if (
                    event.clientX >= rect.left &&
                    event.clientX <= rect.right) {
                    // Ajoutez le cours au jour survolé
                    var endroitCours = jour.querySelector(".endroit_cours");
                    endroitCours.appendChild(coursEnDeplacement);
                }
            });
        }
    });
}
document.getElementById("displace").addEventListener("click" , function () {
    var coursID = localStorage.getItem("IDcours")
    suivreSouris(document.getElementById(coursID));
})
// Sélection de la div
// Sélection de la div
var inputCouleur = document.getElementById('inputCouleur');
var couleurInput = document.getElementById('choix-couleur');

// Écouteur d'événement pour le clic sur la div
inputCouleur.addEventListener('click', function(event) {
    // Afficher l'interface de couleur
    couleurInput.style.display = 'block';
    // Positionner l'interface de couleur au-dessus de la div
    couleurInput.click();
});

// Écouteur d'événement pour détecter le changement de couleur dans la palette
couleurInput.addEventListener('change', function(event) {
    // Mettre à jour la couleur de fond de la div avec la couleur sélectionnée
    inputCouleur.style.backgroundColor = event.target.value;
    // Cacher à nouveau l'interface de couleur
    var days = document.getElementsByClassName("titleday");
    for (let day of days){
        day.style.background = event.target.value;
    }
});

function filtreTime(event) {
    var liste_Cours = document.getElementsByClassName("cours");
    var cours = [];

    /* Informations du formulaire : heure début & fin*/

    var debut = parseInt(document.getElementById("filtre-input-hdebut").value);
    var fin = parseInt(document.getElementById("filtre-input-hfin").value);

    let conteneurHours = document.getElementsByClassName("conteneurHours")[0];
    conteneurHours.className = "conteneurHours";
    conteneurHours.innerHTML = "";

    for (var heure = parseInt(debut) ; heure <= parseInt(fin) ; heure++){


        let textHour = document.createElement("h4");
        textHour.innerHTML = `${heure}h00`;
        textHour.style.height = "5.55vh";
        console.log(heure);
        console.log(debut);
        if (heure == parseInt(debut)){
            textHour.id = `hdebut`;
        }
        else if (heure == parseInt(fin)){
            textHour.id = `hfin`;
        } else {
            textHour.id = `h${heure}`;
        }

        conteneurHours.appendChild(textHour);
    }

    for (var element of liste_Cours){

        /* Informations sur un cours */
        var coursElement = element;
        var jour = coursElement.closest('.jour').id;

        var texte = coursElement.querySelector('h2.UV').textContent;

        var regex = /^([A-Z0-9]+) - (TD|TP|CM)(A|B)?$/;

        var match = texte.match(regex);
        var codeUV = match[1];
        var typeMatiere = match[2];
        var semaine = match[3] ? match[3] : null;
        var heuresText = coursElement.querySelector('p').textContent.trim();
        var heuresSegments = heuresText.split('-');
        var heureDebut = heuresSegments[0].trim();
        var heureFin = heuresSegments[1].trim();
        var salle = coursElement.querySelector('p:nth-of-type(2)').textContent.trim();

        if (parseInt(debut) <= parseInt(heureDebut) && parseInt(fin) >= parseInt(heureFin)){
            var currentCours = new Cours(codeUV , heureDebut , heureFin , jour , salle , semaine , null , typeMatiere);
            cours.push(currentCours);
        }
    }
    var listeJour = document.getElementsByClassName("check");

    for (var jour of listeJour) {
        i = document.getElementById(jour.innerHTML.toLowerCase()).querySelector(".endroit_cours");
        i.innerHTML = "";
        var nbHeures = parseInt(fin) - parseInt(debut);
        for (var j = 0; j < nbHeures; j++) {
            if (j !== nbHeures) {
                i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\" > <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class='dash' style='height:10px'></div> </div>";
            } else {
                i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\"> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class=\"creneau\" style=\"height:1.5vh\" div=\"\"></div> <div class='nondash' style='height:10px'></div> </div>";

            }

        }
    }
    for (var i = 0; i < cours.length; i++) {

        createCours(cours[i]);
    }


}

function changeJour(event){
    var jour = event.target.innerHTML.toLowerCase();
    document.getElementById(jour).style.display = "none";
    var listeJour = document.getElementsByClassName("jour");
    var taille = listeJour.length;
    if (event.target.className == "check"){
        event.target.className = "uncheck"
        for (var jour of listeJour){
            if (jour.style.display != "none"){
                jour.style.borderLeft = "1px black solid";
                break;
            }
        }
    } else {
        event.target.className = "check"
        document.getElementById(jour).style.display = "block";
    }

    var firstDay = false;
    for (var jour of listeJour){
        if (jour.style.display != "none"){
            jour.style.borderLeft = "0";
        }
        if (firstDay === false && jour.style.display != "none"){
            jour.style.borderLeft = "1px black solid";
            firstDay = true;
        }
    }
}

function changePolice(event){
    var police = event.target.innerHTML;
    document.getElementsByClassName("checkPolice")[0].className = "uncheckPolice";
    event.target.className = "checkPolice";
    document.body.style.fontFamily = `${police} , sans-serif`;
}