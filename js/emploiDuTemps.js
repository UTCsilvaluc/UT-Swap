function svgSwapEnter(event){
    event.target.src = "../svg/swap_texte.svg";
    event.target.style.height = "33px";
    event.target.style.width = "auto";
    event.target.style.cursor = "pointer";
}

function svgpDisplaceEnter(event){
    event.target.style.height = "33px";
    event.target.style.width = "auto";
    event.target.src = "../svg/deplacer_texte.svg";
    event.target.style.cursor = "pointer";
}

function svgpTrasheEnter(event){
    event.target.style.height = "33px";
    event.target.style.width = "auto";
    event.target.src = "../svg/supprimer_texte.svg"
    event.target.style.cursor = "pointer";

}

function svgSwapLeave(event){
    event.target.style.marginTop = "0";
    event.target.style.height = "33px";
    event.target.style.width = "33px";
    event.target.src = "../svg/swap_icone.svg";
    event.target.style.cursor = "default";
}

function svgDisplaceLeave(event){
    event.target.style.height = "33px";
    event.target.style.width = "33px";
    event.target.src = "../svg/deplacer_icone.svg";
    event.target.style.cursor = "default";
}

function svgTrashLeave(event){
    event.target.style.height = "33px";
    event.target.style.width = "33px";
    event.target.src = "../svg/supprimer_icone.svg";
    event.target.style.cursor = "default";
}

function trashClick(){
    var coursID = localStorage.getItem("idCours");
    document.getElementById("trash").style.display = "none";
    document.getElementById("deleteCours").style.display = "flex";
}

function deleteCours(){
    var coursID = localStorage.getItem("idCours");
    var coursName = document.getElementById(coursID).querySelector("h2").innerHTML.split("-")[0].replaceAll(" " , "");
    document.getElementById(coursID).remove();
    document.getElementsByClassName("hoverCours")[0].style.display = "none";
    var isExisting = false;
    for (var elem of document.getElementsByClassName("cours")){
        if (coursName == elem.querySelector("h2").innerHTML.split("-")[0].replaceAll(" " , "")){
            isExisting = true;
        }
    }
    if (!(isExisting)){
        document.getElementById(`colorSpan${coursName}`).remove();
        delete coursColors[coursName];
    }
}

function cancelDelete(event){
    document.getElementById("deleteCours").style.display = "none";
    document.getElementById("trash").style.display = "flex";
}

function mettreAJourContenu() {
    var largeurFenetre = window.innerWidth;

    // Changez le contenu en fonction de la largeur de la fenêtre
    var exportPDF = document.getElementById('export_pdf');
    var exportPNG = document.getElementById('export_png');
    if (largeurFenetre <= 400) {
        exportPDF.innerHTML = 'PDF';
        exportPNG.innerHTML = 'PNG';
    } else {
        exportPDF.innerHTML = 'Exporter PDF';
        exportPNG.innerHTML = 'Exporter PNG';
    }
}

// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenu);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenu();

function formaterHeure(heureNonFormatee) {
    const [heures, minutes] = heureNonFormatee.split(':');
    return `${heures.padStart(2, '0')}:${minutes}`;
  }

function changerJour(element, jour){
    element.classList.add("jour_select");
    var jours = document.getElementById("menu_jour_edt").getElementsByTagName("li");
    for (var i = 0; i < jours.length; i++) {
        if (jours[i] !== element) {
            jours[i].classList.remove("jour_select");
        }
    }
    for( i of document.getElementById("emploi_du_temps").getElementsByTagName("div")){
        i.classList.remove("jour_select");
    }
    document.getElementById(jour).classList.add("jour_select");
}

function posterSwap(event){
    event.stopPropagation();
    preremplirNouveauForm();
    formulaire.style.display = "flex";
}

var isHovering = false;

document.getElementById("emploi_du_temps").addEventListener("mousemove" , function (event) {
    if ((event.target.className === "cours" || event.target.parentElement.className === "cours" ) && !isHovering){
        if(event.target.parentElement.className === "cours"){
            var coursElement = event.target.parentElement;
        }else{
            var coursElement = event.target;
        }
        isHovering = true;
    // Récupérer l'élément sur lequel l'événement a eu lieu
        
        var jour = coursElement.closest('.jour').id;

    // Récupérer le texte de la balise h2
        var texte = coursElement.querySelector('h2.UV').textContent;

// Expression régulière pour extraire les informations
        var regex = /^([A-Z0-9]+) - (TD|TP|CM)( A| B)?$/;

// Correspondance avec l'expression régulière
        var match = texte.match(regex);
        // Le code UV est dans match[1]
        var codeUV = match[1];

        // Le type de matière est dans match[2]
        var typeMatiere = match[2];

        // La semaine est dans match[3], si elle existe
        var semaine = match[3] ? match[3] : null;

        // Récupérer le texte de la balise <p> contenant l'heure
        var heuresText = coursElement.querySelector('.horaire_cours').textContent.trim();

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
        hoverCours.style.height = coursElement.offsetHeight + "px";
        
        hoverCours.style.left = `${coursElement.getBoundingClientRect().x + coursElement.offsetWidth - 10}px`;
        hoverCours.style.top = `${coursElement.getBoundingClientRect().y}px`;

        
        var largeurEcran = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

        if (parseFloat(coursElement.offsetHeight) < 60 || largeurEcran < 750){
            hoverCours.style.flexDirection = "row";
            hoverCours.style.top = `${coursElement.getBoundingClientRect().y - 30}px`;
            hoverCours.style.left = `${coursElement.getBoundingClientRect().x}px`;
            hoverCours.style.height = "40px";
            hoverCours.style.width = coursElement.offsetWidth +"px";
        }else{
            hoverCours.style.flexDirection = "column";
            hoverCours.style.width = "auto";
        }

    }

});

document.getElementById("emploi_du_temps").addEventListener("mouseout" , function (event) {
    if (event.target.className !== "cours" && event.target.className !== "hoverCours" && event.target.parentElement.className !== "cours") {
        stopHovering();
    }

});

function addCreneau() {
    if(!suivreLaSouris){
        nouveauClick();
        texte_nouveau.innerHTML = "Ajouter un nouveau créneau";
        motivation.classList.toggle('hidden', true);
        bouton_ajouter_creneau.classList.toggle('hidden', false);
        bouton_non_submit.classList.toggle('hidden', true);
    }
}

bouton_ajouter_creneau.addEventListener("click", function() {
    event.preventDefault();
    deleteCours();
    nouveau_pannel = document.getElementById('nouveau_pannel');

    // Récupérer les valeurs des champs du formulaire
    var type = encodeURIComponent(input_type.value);
    var salle = encodeURIComponent(input_salle.value);
    var creneau = encodeURIComponent(input_creneau.value);
    var uv = encodeURIComponent(input_uv.value);
    var hfin = input_hfin[1].value;
    var hdebut = input_hdebut[1].value;

    var semainechoix = nouveau_pannel.querySelector('input[name="semainechoix"]:checked') ? nouveau_pannel.querySelector('input[name="semainechoix"]:checked').value : null;

    hdebut = hdebut.replace(":" , "h");
    hfin = hfin.replace(":" , "h");
    let cours = new Cours(uv, hdebut, hfin, creneau, salle , semainechoix , null , type);
    createCours(cours);
    nouveauClick();
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
    if(heure !== parseInt(finEDT)){
        textHour.style.height = "6vh";
    }
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

let cours2 = new Cours("IC05", "12h30", "13h00", "mardi", "FA104" , null , null , "TD");
let cours3 = new Cours("MT02", "10h15", "11h15", "mardi", "FA104" , null , null , "TD");
let cours4 = new Cours("PS21", "14h15", "16h15", "vendredi", "FA104" , null , null , "TD");
let cours5 = new Cours("CACA", "08h00", "10h00", "mercredi", "FA104", null , null , "TP");
let cours6 = new Cours("MT23", "11h00", "13h00", "lundi", "FA104" , "A" , null , "CM");
var colorList = [
    "#FF9292","#FFA792","#FFB692","#FFC592","#FFD992",
    "#FFE692","#FFFD92","#EAFF92","#CAFF92","#B5FF92",
    "#97FF92","#92FFB5","#92FFD6","#92FFFD","#92EAFF",
    "#92CCFF","#92AEFF","#9792FF","#A492FF","#C092FF",
    "#D792FF","#EA92FF","#FF92EA","#FF92D6","#FF92BF"
];
var coursColors = {};
var liste = [cours2, cours3, cours4, cours5 , cours6]
var endroit_cours;
var cours;

var listeJour = [lundi, mardi, mercredi, jeudi, vendredi, samedi, dimanche];
for (var i of listeJour) {
    for(var j=0; j<12 ; j++){
        if(j !== 11){
            i.innerHTML += "<div class='divHeure' onclick=\"addCreneau()\"> <div class='dash' style='height:6vh'></div> </div>";
        }else{
            i.innerHTML += "<div class='divHeure' onclick=\"addCreneau()\"> <div style='height:6vh'></div> </div>";
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

function getOffsetHeight(element) {
    if(!element.parentElement.classList.contains("jour_select")){
        // Change le display pour le rendre visible temporairement
        element.parentElement.classList.add("jour_select");

        // Récupère la hauteur
        var height = element.getBoundingClientRect().height;

        // Rétablit le style original
        element.parentElement.classList.remove("jour_select");
    }else{
        var height = element.getBoundingClientRect().height;
    }

    

    return height;
}

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
        spanColor.innerHTML += "<span id=colorSpan" + cours.codeUV + " style='display: flex ; margin: 0 ; padding: 0 ; align-items: center ; gap: 10px'><h4> " + cours.codeUV + ": </h4> <input id='color" + cours.codeUV +"' value= "+ coursColors[cours.codeUV] +"  type='color' onchange='colorChange(event)'></span>"
    }
    cours.couleur = coursColors[cours.codeUV];
    var nbCours = document.getElementsByClassName("cours").length;
    endroit_cours.innerHTML += '<div class="cours" id= ' + parseInt(nbCours + 1) + ' onclick="suivreSouris(this, true)"><h2 class="UV">' + '</h2><p class="horaire_cours">' + cours.horaireDebut + '-' + cours.horaireFin + '</p><p>' + cours.salle + '</p></div>'
    coursElement = endroit_cours.getElementsByClassName("cours")[endroit_cours.getElementsByClassName("cours").length -1];

    var tailleEDT = getOffsetHeight(endroit_cours);
    var heureDebutEDT = parseInt(document.getElementById("hdebut").innerHTML.split("h")[0]);
    var heureFinEDT = parseInt(document.getElementById("hfin").innerHTML.split("h")[0]);
    var nbHeureEDT = heureFinEDT - heureDebutEDT;
    var heuresDecimalesDebut = calculDecimal(cours.horaireDebut);

    var heuresDecimalesFin = calculDecimal(cours.horaireFin);

    var tempsCours = heuresDecimalesFin - heuresDecimalesDebut;

    var pourcentageTop = calculPourcentage(heuresDecimalesDebut - heureDebutEDT , nbHeureEDT , tailleEDT);

    var pourcentageHeight = calculPourcentage(tempsCours , nbHeureEDT , tailleEDT);
    coursElement.style.height = pourcentageHeight + "%";
    coursElement.style.overflow = "hidden";

    coursElement.style.top = pourcentageTop + "%";

    coursElement.style.background = cours.couleur;

    if (pourcentageHeight < 5){
        coursElement.style.flexDirection = "row";
        coursElement.style.gap = "4px";
        coursElement.style.justifyContent = "center";
        for (element of coursElement.children){
            element.style.fontSize = pourcentageHeight * 6.5 + '%';
        }
        coursElement.getElementsByClassName("UV")[0].style.fontSize = pourcentageHeight * 8 + '%';
    }else if(pourcentageHeight < 10){
        coursElement.style.flexDirection = "row";
        coursElement.style.gap = "4px";
        coursElement.style.justifyContent = "center";
        for (element of coursElement.children){
            element.style.fontSize = pourcentageHeight * 4.5 + '%';
        }
        coursElement.getElementsByClassName("UV")[0].style.fontSize = pourcentageHeight * 6 + '%';
    } else {
        for (element of coursElement.children){
            element.style.fontSize = pourcentageHeight * 3 + '%';
        }
        coursElement.getElementsByClassName("UV")[0].style.fontSize = pourcentageHeight * 4.5 + '%';
    }

    if (cours.semaine == null){
        coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV + " - " + cours.type
    } else {

        if (cours.semaine === "B"){
            coursElement.style.left = "50%";
            coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV + " - " + cours.type +" B";
        } else {
            coursElement.getElementsByClassName("UV")[0].innerHTML = cours.codeUV + " - " + cours.type + " A";
        }
        coursElement.style.width = "50%";
        for (element of coursElement.children){
            element.style.fontSize = pourcentageHeight * 2.5 + '%';
        }
        coursElement.getElementsByClassName("UV")[0].style.fontSize = pourcentageHeight * 3.5 + '%';
    }

    

    if(pourcentageHeight > 20){
        for (element of coursElement.children){
            element.style.fontSize = '10px';
        }
        coursElement.getElementsByClassName("UV")[0].style.fontSize = '12px';
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

function stopHovering(){
    isHovering = false;
    hoverCours = document.getElementsByClassName("hoverCours")[0];
    hoverCours.style.display = "none";
    document.getElementById("trash").style.display = "block";
    document.getElementById("deleteCours").style.display = "none";
}

function convertirDecimalEnHeure(decimal) {
    let heures = Math.floor(decimal);
    let minutes = Math.round((decimal - heures) * 60);

    if (minutes === 60) {
        heures++;
        minutes = 0;
    }

    return `${heures}h${String(minutes).padStart(2, '0')}`;
}

var suivreLaSouris = false;
var lastPosition;
function suivreSouris(element, isCours) {

    var gestionnaireSouris = function(event) {
        //faire le changement de jour
        if (suivreLaSouris) {
            
            //enlever le hover avec l'apparition des bulles
            stopHovering()
            
            
            
            var newPosition = event.clientY - coursEnDeplacement.parentElement.getBoundingClientRect().top - coursEnDeplacement.offsetHeight / 2;

            

            var heureDebutEDT = parseInt(document.getElementById("hdebut").innerHTML.split("h")[0]);
            var heureFinEDT = parseInt(document.getElementById("hfin").innerHTML.split("h")[0]);
            var nbHeureEDT = heureFinEDT - heureDebutEDT;
            
            var arrondi = 0.25 * coursEnDeplacement.parentElement.offsetHeight / nbHeureEDT;
            newPosition = Math.round(newPosition / arrondi) * arrondi;
            newPosition = Math.min(Math.max(newPosition, 0), coursEnDeplacement.parentElement.clientHeight - coursEnDeplacement.clientHeight)
            
            coursEnDeplacement.style.top = newPosition + "px";
            
            if(lastPosition != newPosition && lastPosition !== null){
                var heureDebutCours = convertirDecimalEnHeure(heureDebutEDT + (newPosition * nbHeureEDT / coursEnDeplacement.parentElement.offsetHeight));
                var heureFinCours = convertirDecimalEnHeure(heureDebutEDT + ((newPosition + coursEnDeplacement.offsetHeight) * nbHeureEDT / coursEnDeplacement.parentElement.offsetHeight));
                coursEnDeplacement.getElementsByClassName("horaire_cours")[0].innerHTML = heureDebutCours + "-" + heureFinCours;
            }
            lastPosition = newPosition;

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
            
            if (coursEnDeplacement.style.width == "50%" && event.clientX > coursEnDeplacement.parentElement.getBoundingClientRect().left + coursEnDeplacement.offsetWidth) {
                coursEnDeplacement.style.left = "50%";
                coursEnDeplacement.querySelector("h2").innerHTML = coursEnDeplacement.querySelector("h2").innerHTML.replace(" A", " B");
            }else if(coursEnDeplacement.style.width == "50%" && event.clientX < coursEnDeplacement.parentElement.getBoundingClientRect().left + coursEnDeplacement.offsetWidth){
                coursEnDeplacement.style.left = "";
                coursEnDeplacement.querySelector("h2").innerHTML = coursEnDeplacement.querySelector("h2").innerHTML.replace(" B", " A");
            }

            return true;
        }else{
            return false;
        }
    }
    var largeurEcran = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    if(largeurEcran > 600){
        if (suivreLaSouris || isCours) {
            // Si on était déjà en train de suivre la souris, cela signifie que le clic actuel doit être pour terminer le déplacement
            suivreLaSouris = false;
            coursEnDeplacement = null;
            anciennePositionArrondie = null;
        } else {
            // Sinon, le clic actuel est pour commencer le déplacement
            suivreLaSouris = true;
            coursEnDeplacement = element; // 'this' fait référence à l'élément sur lequel le clic a été effectué
    
            // Ajoutez ici le code que vous voulez exécuter au début du déplacement
        }
        if(!suivreLaSouris){
            event.stopPropagation();
            document.removeEventListener("mousemove" , gestionnaireSouris);
        }else{
            document.addEventListener("mousemove", gestionnaireSouris);
        }
    }else if(!isCours){
        addCreneau();
        input_salle.disabled = true;
        input_uv.disabled = true;
        input_type.disabled = true;
        input_creneau.disabled = true;
        preremplirNouveauForm();
    }
    
}

function preremplirNouveauForm(){
    var codeUV = localStorage.getItem("codeUV");
    var creneau = localStorage.getItem("creneau");
    var heureDebut = formaterHeure(localStorage.getItem("heureDebut").replace("h" , ":"));
    var heureFin = formaterHeure(localStorage.getItem("heureFin").replace("h" , ":"));
    var salle = localStorage.getItem("salle");
    var type = localStorage.getItem("type");
    var semaine = localStorage.getItem("semaine");
    formulaire = document.getElementById("nouveau_pannel");
    formulaire.querySelector("#input-uv").value = codeUV;
    formulaire.querySelector("#input-creneau").value = creneau;
    formulaire.querySelector("#input-hdebut1").value = heureDebut;
    formulaire.querySelector("#input-hfin1").value = heureFin;
    formulaire.querySelector("#input-hdebut2").value = heureDebut;
    formulaire.querySelector("#input-hfin2").value = heureFin;
    formulaire.querySelector("#input-salle").value = salle;
    formulaire.querySelector("#input-type").value = type;
    if (semaine !== 'null'){
        checkbox = document.getElementById("input-semaine");
        checkbox.checked = true;
        document.getElementById("choix-semaine").className = "basique";
        if (semaine === " A"){
            document.getElementById("sA-choix").checked = true;
        } else if(semaine === " B") {
            document.getElementById("sB-choix").checked = true;
        }
    } else {
        checkbox.checked = false;
        document.getElementById("choix-semaine").className = "basique hidden";
    }
}

document.getElementById("displace").addEventListener("click" , function () {
    var coursID = localStorage.getItem("idCours");
    suivreSouris(document.getElementById(coursID, false));
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
        if(heure !== parseInt(fin)){
            textHour.style.height = "6vh";
        }
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
            if(j !== nbHeures-1){
                i.innerHTML += "<div class='divHeure' onclick=\"addCreneau()\"> <div class='dash' style='height:6vh'></div> </div>";
            }else{
                i.innerHTML += "<div class='divHeure' onclick=\"addCreneau()\"> <div style='height:6vh'></div> </div>";
            }
            
        }
    }
    for (var i = 0; i < cours.length; i++) {

        createCours(cours[i]);
    }
    var isExisting = false;
    for (var elem of document.getElementById("couleurSpan").querySelectorAll("span")){
        isExisting = false;
        var codeUV = elem.querySelector("h4").innerHTML.replaceAll(":" , "");
        var codeUVr = codeUV.replaceAll(" " , "");
        for (var c of cours){
            if (c.codeUV == codeUVr){
                isExisting = true;
            }

        }
        if (!(isExisting)){
            elem.style.display = "none";
        }
    }

}

function resetEDT(event) {
    document.querySelectorAll(".cours").forEach( function (cours){
        cours.remove();
    });
  
  document.getElementById("couleurSpan").innerHTML = "";
}

document.addEventListener("click" , function (event) {

    if (!(event.target.closest("#menuFiltre")) && document.getElementById("menuFiltre") != "none" && event.target.id !== "filtre_edt"){
        document.getElementById("menuFiltre").style.display = "none";
    }

});

function changeJour(event){
    
    var listeJour = document.getElementsByClassName("jour");
    var nbJour = 0;
    var taillePrec = null;
    for (var jour of listeJour){
        if (jour.style.display != "none"){
            nbJour +=1;
            taillePrec = jour.offsetWidth;
        }
    }

    for (var jour of listeJour){
        if (event.target.className == "check"){
            jour.style.width = taillePrec * nbJour / (nbJour-1) + "px";
        }else{
            jour.style.width = taillePrec * nbJour / (nbJour+1) + "px";
        }
    }
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
        
        var borderRadiusSize = "0";
        jour.style.borderTopLeftRadius = borderRadiusSize;
        jour.querySelector("h1").style.borderTopLeftRadius = borderRadiusSize;
        jour.style.borderBottomLeftRadius = borderRadiusSize;
        jour.style.borderTopRightRadius = borderRadiusSize;
        jour.querySelector("h1").style.borderTopRightRadius = borderRadiusSize;
        jour.style.borderBottomRightRadius = borderRadiusSize;
        if (jour.style.display != "none"){
            jour.style.borderLeft = "0";
        }
        if (firstDay === false && jour.style.display != "none"){
            jour.style.borderLeft = "1px black solid";
            firstDay = true;
        }
    }

    // Filtrer les éléments pour exclure ceux avec display: none
    var elementsVisibles = Array.from(listeJour).filter(function(element) {
        return window.getComputedStyle(element).display !== 'none';
    });

    // Vérifiez s'il y a des éléments visibles
    if (elementsVisibles.length > 0) {
        // Récupérez le premier et le dernier élément visibles
        var premierElement = elementsVisibles[0];
        var dernierElement = elementsVisibles[elementsVisibles.length - 1];
        var borderRadiusSize = "10px";
        premierElement.style.borderTopLeftRadius = borderRadiusSize;
        premierElement.querySelector("h1").style.borderTopLeftRadius = borderRadiusSize;
        premierElement.style.borderBottomLeftRadius = borderRadiusSize;
        dernierElement.style.borderTopRightRadius = borderRadiusSize;
        dernierElement.querySelector("h1").style.borderTopRightRadius = borderRadiusSize;
        dernierElement.style.borderBottomRightRadius = borderRadiusSize;
    }
}

function changePolice(event){
    var police = event.target.innerHTML;
    document.getElementsByClassName("checkElement")[0].className = "uncheckElement";
    event.target.className = "checkElement";
    document.body.style.fontFamily = `${police} , sans-serif`;
}

function openFiltre(event){
    var conteneurFiltre = document.getElementById("menuFiltre");
    conteneurFiltre.style.display = "flex";
}

function closeFiltre(event){
    var conteneurFiltre = document.getElementById("menuFiltre");
    conteneurFiltre.style.display = "none";
}

function supprimerFiltre(){
    var couleurSpan = document.getElementById("couleurSpan").innerHTML;
    document.getElementById("filterContainer1").innerHTML = '<div class="conteneur_filtre" id="filterContainer1"><div class="filtre_parent" id="police"><h1 class="filtre_entete">Police</h1><span class="filtre_span"><h3 class="checkPolice" onclick="changePolice(event)" id="mainPolice">Jost</h3><h3 class="uncheckPolice" onclick="changePolice(event)">Times New Roman</h3><h3 class="uncheckPolice" onclick="changePolice(event)">Comic Sans MS</h3></span></div><div class="filtre_parent" id="jours"><h1 class="filtre_entete">Jour</h1><span class="filtre_span" id="spanJour"><h3 class="check" onclick="changeJour(event)">Lundi</h3><h3 class="check" onclick="changeJour(event)">Mardi</h3><h3 class="check" onclick="changeJour(event)">Mercredi</h3><h3 class="check" onclick="changeJour(event)">Jeudi</h3><h3 class="check" onclick="changeJour(event)">Vendredi</h3><h3 class="check" onclick="changeJour(event)">Samedi</h3><h3 class="uncheck" onclick="changeJour(event)">Dimanche</h3></div><div class="filtre_parent" id="couleurs"><h1 class="filtre_entete">Couleurs</h1><span><span class="filtre_span" id="couleurSpan"></span></span></div><div class="filtre_parent" id="couleur_entete"><h1 class="filtre_entete">Couleur entête</h1><span class="filtre_span"><div class="inputCouleur" id="inputCouleur" style=""><span style="margin-left: 20px"><input class="colorChoice" type="color" id="choix-couleur" name="choix-couleur" style="position: absolute; ; width: 2px ; height: 2px"></span></div></span></div><div class="filtre_parent" id="langue"><h1>Langue</h1><span class="filtre_span"><h3>Anglais</h3><h3>Français</h3><h3>Espagnol</h3></span></div><div class="filtre_parent" id="heures"><h1>Horaires</h1><span class="filtre_span"><div><input type="time" id="filtre-input-hdebut" name="hdebut" value="08:00" required onchange="filtreTime(event)"></div><div><input type="time" id="filtre-input-hfin" name="hfin" value="20:00" required onchange="filtreTime(event)"></div></span></div><div class="buttonFiltres"><button class="filtreButton" id="appliquerFiltre" onclick="supprimerFiltre(event)">Supprimer les filtres</button></div></div>';
    document.body.style.fontFamily = `Jost , sans-serif`;
    document.getElementById("spanJour").querySelectorAll("h3").forEach(function (jour){
        var elementJour = document.getElementById(jour.innerHTML.toLowerCase());
        if (jour.innerHTML != "Dimanche"){
            jour.className = "check";
            elementJour.style.display = "block";
            if (jour.innerHTML.toLowerCase() !== "lundi"){
                elementJour.style.borderLeft = "0";
            }
        }
        else{
            jour.className = "uncheck";
            elementJour.style.display = "none";
        }
    })
    document.getElementById("filtre-input-hfin").value = "20:00";
    var input = document.getElementById("filtre-input-hdebut");
    input.value = "08:00";
    var event = new Event('change');
    input.dispatchEvent(event);

    document.getElementById("couleurSpan").innerHTML = couleurSpan;


}
var importElement = document.getElementById("importEDTID");
var exportElement = document.getElementById("exportEDT");
var ecran = document.getElementById("ecran_edt");

function menuimportEDT(event){
    importElement.style.display = "flex";
    event.stopPropagation();
    ecran.style.display = "block";
}

function menuExportEDT(event){
    exportElement.style.display = "flex";
    event.stopPropagation();
    ecran.style.display = "block";
}

document.addEventListener("click" , function (event){
    if (!(event.target.closest("#importEDTID")) && importElement.style.display != "none") {
        importElement.style.display = "none";
        ecran.style.display = "none";
    }
    if (!(event.target.closest("#exportEDT")) && exportElement.style.display != "none") {
        exportElement.style.display = "none";
        ecran.style.display = "none";
    }
})

// Fonction pour transformer une entrée de cours en objet Cours
function transformerEntreeCours(entree) {
    var listeInformations = entree.split(" ");
    var listeSansVide = listeInformations.filter(function(element) {
        // Retourne true si l'élément n'est pas vide
        return element.trim() !== "";
    });
    var semaine = null;
    if (listeSansVide.length === 5){
        var codeUV = listeSansVide[0];
        var type = listeSansVide[1];
        var groupe = listeSansVide[2];
        var jour = listeSansVide[3];
        var details = listeSansVide[4];
        //let [codeUV , type , groupe , jour, details] = listeSansVide;
    }
    else {
        var codeUV = listeSansVide[0];
        var type = listeSansVide[1];
        var groupe = listeSansVide[2];
        semaine = listeSansVide[3];
        var jour = listeSansVide[4];
        var details = listeSansVide[5];
        //let [codeUV , type , groupe , semaine , jour, details] = listeSansVide;
    }
    var detailsSplit = details.split(",");
    var horaire = detailsSplit[0].split("-");
    var heureDebut = horaire[0];
    var heureFin = horaire[1];
    var salle = detailsSplit[2].split("S=")[1];
    jour = jour.replaceAll("." , "");

    switch(type) {
        case "T":
            type = "TP";
            break;
        case "C":
            type = "CM";
            break;
        case "D":
            type = "TD";
            break;
        default:
            type="CM";
            console.log("Erreur dans le type");
    }
    return new Cours(codeUV , heureDebut.replace(":","h") , heureFin.replaceAll(":","h") , jour.toLowerCase() , salle , semaine , null , type);
}
function importEDT(event){
    var allCourses = document.getElementById("textUV").value.split("\n");
    resetEDT(event)
    for (var currentCours of allCourses){
        if (currentCours.length > 0){
            var cours = transformerEntreeCours(currentCours);
            createCours(cours);
        }
    }

    document.getElementById("importEDTID").style.display = "none";
    document.getElementById("textUV").value = "";
    document.getElementById("ecran_edt").style.display = "none";


}