let canSave = document.getElementById("localSave").checked;
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

function svgEditEnter(event){
    event.target.style.height = "33px";
    event.target.style.width = "auto";
    event.target.src = "../svg/edit_text.svg"
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

function svgEditLeave(event){
    event.target.style.height = "33px";
    event.target.style.width = "auto";
    event.target.src = "../svg/Edit%20icon.svg"
    event.target.style.cursor = "pointer";

}

function editCreneau(event){
    event.stopPropagation();
    nouveauClick();
    document.getElementById("bouton_update").classList.toggle("hidden" , false);
    bouton_non_submit.classList.toggle("hidden" , true);
    preremplirNouveauForm();
}

function updateCreneau(){
    var coursID = localStorage.getItem("idCours");
    document.getElementById("trash").style.display = "none";
    document.getElementById("deleteCours").style.display = "flex";
    deleteCours();
    bouton_ajouter_creneau.click();

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

    (async () => {
        try {
            const db = await ouvrirBaseDeDonneesCours();
            const courses = await getCoursByID(db, parseInt(coursID));
            if (courses) {
                await supprimerCoursByID(db, parseInt(coursID));
                for (i = 0 ; i < parseInt(coursID) ; i++){

                }
            } else {
                console.log("Erreur : aucun cours avec cet ID !")
            }
        } catch (error) {
            console.error(error);
        }
    })();
}

function cancelDelete(event){
    document.getElementById("deleteCours").style.display = "none";
    document.getElementById("trash").style.display = "flex";
}


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
    nouveauClick();
    preremplirNouveauForm();

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
        var regex = /^([A-Z0-9]+) - (ACT|TD|TP|CM)( A| B)?$/i;


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

        var hoverCours = document.getElementById("rightHover");
        var hoverLeft = document.getElementById("leftHover");
        hoverCours.style.display = "flex";
        hoverCours.style.height = coursElement.offsetHeight + "px";

        hoverLeft.style.display = "flex";
        hoverLeft.style.height = coursElement.offsetHeight + "px";

        hoverCours.style.left = `${coursElement.getBoundingClientRect().x + coursElement.offsetWidth - 10}px`;
        hoverCours.style.top = `${coursElement.getBoundingClientRect().y}px`;
        hoverLeft.style.left = `${coursElement.getBoundingClientRect().x + coursElement.offsetWidth - 250}px`;
        hoverLeft.style.top = `${coursElement.getBoundingClientRect().y}px`;


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
        var editElement = document.getElementById("edit");
        if (typeMatiere === "ACT"){
            hoverCours.appendChild(editElement);
            editElement.src = "../svg/edit_icon_right.svg";
        } else {
            editElement.src = "../svg/Edit%20icon.svg";
            hoverLeft.appendChild(editElement);
        }
        document.getElementById("swap").classList.toggle("hidden" , typeMatiere === "ACT");

    }

});

document.getElementById("emploi_du_temps").addEventListener("mouseout" , function (event) {
    if (event.target.className !== "cours" && event.target.className !== "hoverCours" && event.target.parentElement.className !== "cours") {
        stopHovering();
    }

});

function addCreneau(event) {

    if(!suivreLaSouris){
        nouveauClick();
        texte_nouveau.innerHTML = "Ajouter un nouveau créneau";
        motivation.classList.toggle('hidden', true);
        bouton_ajouter_creneau.classList.toggle('hidden', false);
        bouton_non_submit.classList.toggle('hidden', true);
        document.getElementById("li-externe").classList.toggle("hidden" , false);
        document.getElementById("li-semaine").classList.toggle("hidden" , false);
        var divHeureCliquee = event.target.closest('.divHeure');
        var divJour = event.target.closest('.jour');
        if (divHeureCliquee) {
            var [heureDebut , heureFin] = calculerHeure(divHeureCliquee);
            document.getElementById("input-hdebut1").value = heureDebut;
            document.getElementById("input-hfin1").value = heureFin;
            document.getElementById("input-creneau").value = divJour.id.toString();
        }
    }
}
function calculerHeure(divHeure) {
    var parent = divHeure.parentElement;
    var index = Array.prototype.indexOf.call(parent.children, divHeure);
    var heureFormattee = formaterHeure(document.getElementById("hdebut").innerHTML.replace('h',':'));
    var heureDebut = parseInt(heureFormattee.slice(0,2)) + index;

    return [heureDebut.toString().padStart(2, '0') + ':00' , (heureDebut+2).toString().padStart(2, '0') + ':00'];
}

bouton_ajouter_creneau.addEventListener("click", function() {
    event.preventDefault();
    nouveau_pannel = document.getElementById('nouveau_pannel');

    // Récupérer les valeurs des champs du formulaire
    var creneau = encodeURIComponent(input_creneau.value);
    var hfin = input_hfin[0].value;
    var hdebut = input_hdebut[1].value;
    var semainechoix = nouveau_pannel.querySelector('input[name="semainechoix"]:checked') ? nouveau_pannel.querySelector('input[name="semainechoix"]:checked').value : null;
    hdebut = hdebut.replace(":" , "h");
    hfin = hfin.replace(":" , "h");
    let cours;

    if (document.getElementById("input-isCours").checked){
        var activiteName = encodeURIComponent(document.getElementById("exteName").value);
        var activiteLoc = encodeURIComponent(document.getElementById("input-lieu").value);
        cours = new Cours(activiteName, hdebut, hfin, creneau, activiteLoc , semainechoix , null , encodeURIComponent("ACT"));
    } else {
        var type = encodeURIComponent(input_type.value);
        var salle = encodeURIComponent(input_salle.value);
        var uv = encodeURIComponent(input_uv.value);
        cours = new Cours(uv, hdebut, hfin, creneau, salle , semainechoix , null , type);
    }
    createCours(cours);
    tempsCurrentCours = null;
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
    constructor(codeUV, horaireDebut, horaireFin, jour, salle, semaine = null, couleur = null , type = null , id = null) {
        this.codeUV = codeUV;
        this.horaireDebut = horaireDebut;
        this.horaireFin = horaireFin;
        this.jour = jour;
        this.salle = salle;
        this.semaine = semaine;
        this.type = type;
        this.couleur = couleur;
        this.id = id;
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
let cours6 = new Cours("MT23", "11h00", "13h00", "lundi", "FA104" , "A" , null , "CM");
var colorList = [
    "#FF9292","#FFA792","#FFB692","#FFC592","#FFD992",
    "#FFE692","#FFFD92","#EAFF92","#CAFF92","#B5FF92",
    "#97FF92","#92FFB5","#92FFD6","#92FFFD","#92EAFF",
    "#92CCFF","#92AEFF","#9792FF","#A492FF","#C092FF",
    "#D792FF","#EA92FF","#FF92EA","#FF92D6","#FF92BF"
];
var coursColors = {};
var liste = [cours2, cours3, cours4 , cours6]
var endroit_cours;
var cours;

var listeJour = [lundi, mardi, mercredi, jeudi, vendredi, samedi, dimanche];
for (var i of listeJour) {
    for(var j=0; j<12 ; j++){
        if(j !== 11){
            i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\"> <div class='dash' style='height:6vh'></div> </div>";
        }else{
            i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\"> <div style='height:6vh'></div> </div>";
        }
    }
}

function setBorderRadius(){
    var listeJour = document.getElementsByClassName("jour");
    // Customr les éléments pour exclure ceux avec display: none
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

function clearBorderRadius(){
    var listeJour = document.getElementsByClassName("jour");
    for(jour of listeJour){
        var borderRadiusSize = "0";
        jour.style.borderTopLeftRadius = borderRadiusSize;
        jour.querySelector("h1").style.borderTopLeftRadius = borderRadiusSize;
        jour.style.borderBottomLeftRadius = borderRadiusSize;
        jour.style.borderTopRightRadius = borderRadiusSize;
        jour.querySelector("h1").style.borderTopRightRadius = borderRadiusSize;
        jour.style.borderBottomRightRadius = borderRadiusSize;
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

/*for (var i = 0; i < liste.length; i++) {
    createCours(liste[i]);
};*/

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

async function createCours(cours) {
    /* Retourne une promesse qui me permet d'attendre le sauvegarde d'un cours avant qu'un autre cours soit crée. Permet d'éviter le mélange des cours entre synchrone et asynchrone.*/
    return new Promise(async (resolve, reject) => {
        try {
            canSave = document.getElementById("localSave").checked;
            let endroit_cours;
            if (cours.jour == "lundi") {
                endroit_cours = lundi;
            } else if (cours.jour == "mardi") {
                endroit_cours = mardi;
            } else if (cours.jour == "mercredi") {
                endroit_cours = mercredi;
            } else if (cours.jour == "jeudi") {
                endroit_cours = jeudi;
            } else if (cours.jour == "vendredi") {
                endroit_cours = vendredi;
            } else if (cours.jour == "samedi") {
                endroit_cours = samedi;
            } else if (cours.jour == "dimanche") {
                endroit_cours = dimanche;
            }
            const spanColor = document.getElementById("couleurSpan");
            if (!(cours.codeUV in coursColors) && cours.couleur == null) {
                coursColors[cours.codeUV] = getRandomColor(colorList);
                spanColor.innerHTML += `<span id=colorSpan${cours.codeUV} style='display: flex ; margin: 0 ; padding: 0 ; align-items: center ; gap: 10px'><h4>${cours.codeUV}: </h4> <input id='color${cours.codeUV}' value=${coursColors[cours.codeUV]}  type='color' onchange='colorChange(event)'></span>`;
            } else if (cours.couleur && !(cours.codeUV in coursColors)) {
                coursColors[cours.codeUV] = cours.couleur;
                spanColor.innerHTML += `<span id=colorSpan${cours.codeUV} style='display: flex ; margin: 0 ; padding: 0 ; align-items: center ; gap: 10px'><h4>${cours.codeUV}: </h4> <input id='color${cours.codeUV}' value=${coursColors[cours.codeUV]}  type='color' onchange='colorChange(event)'></span>`;
            }
            cours.couleur = coursColors[cours.codeUV];

            if (cours.id === null && canSave) {
                const db = await ouvrirBaseDeDonneesCours();
                const maxId = await getMaxId(db);
                cours.id = maxId + 1;
                await ajouterCours(db, cours);
            }

            endroit_cours.innerHTML += `<div class="cours" id=${cours.id} onclick="suivreSouris(this, true)"><h2 class="UV"></h2><p class="horaire_cours">${cours.horaireDebut}-${cours.horaireFin}</p><p>${cours.salle}</p></div>`;
            const coursElement = endroit_cours.getElementsByClassName("cours")[endroit_cours.getElementsByClassName("cours").length - 1];

            const tailleEDT = getOffsetHeight(endroit_cours);
            const heureDebutEDT = parseInt(document.getElementById("hdebut").innerHTML.split("h")[0]);
            const heureFinEDT = parseInt(document.getElementById("hfin").innerHTML.split("h")[0]);
            const nbHeureEDT = heureFinEDT - heureDebutEDT;
            const heuresDecimalesDebut = calculDecimal(cours.horaireDebut);
            const heuresDecimalesFin = calculDecimal(cours.horaireFin);
            const tempsCours = heuresDecimalesFin - heuresDecimalesDebut;
            const pourcentageTop = calculPourcentage(heuresDecimalesDebut - heureDebutEDT, nbHeureEDT, tailleEDT);
            const pourcentageHeight = calculPourcentage(tempsCours, nbHeureEDT, tailleEDT);

            coursElement.style.height = `${pourcentageHeight}%`;
            coursElement.style.overflow = "hidden";
            coursElement.style.top = `${pourcentageTop}%`;
            coursElement.style.background = cours.couleur;

            if (pourcentageHeight < 5) {
                coursElement.style.flexDirection = "row";
                coursElement.style.gap = "4px";
                coursElement.style.justifyContent = "center";
                for (element of coursElement.children) {
                    element.style.fontSize = `${pourcentageHeight * 6.5}%`;
                }
                coursElement.getElementsByClassName("UV")[0].style.fontSize = `${pourcentageHeight * 8}%`;
            } else if (pourcentageHeight < 10) {
                coursElement.style.flexDirection = "row";
                coursElement.style.gap = "4px";
                coursElement.style.justifyContent = "center";
                for (element of coursElement.children) {
                    element.style.fontSize = `${pourcentageHeight * 4.5}%`;
                }
                coursElement.getElementsByClassName("UV")[0].style.fontSize = `${pourcentageHeight * 6}%`;
            } else {
                for (element of coursElement.children) {
                    element.style.fontSize = `${pourcentageHeight * 3}%`;
                }
                coursElement.getElementsByClassName("UV")[0].style.fontSize = `${pourcentageHeight * 4.5}%`;
            }

            if (cours.semaine == null) {
                coursElement.getElementsByClassName("UV")[0].innerHTML = `${cours.codeUV} - ${cours.type}`;
            } else {
                if (cours.semaine === "B") {
                    coursElement.style.left = "50%";
                    coursElement.getElementsByClassName("UV")[0].innerHTML = `${cours.codeUV} - ${cours.type} B`;
                } else {
                    coursElement.getElementsByClassName("UV")[0].innerHTML = `${cours.codeUV} - ${cours.type} A`;
                }
                coursElement.style.width = "50%";
                for (element of coursElement.children) {
                    element.style.fontSize = `${pourcentageHeight * 2.5}%`;
                }
                coursElement.getElementsByClassName("UV")[0].style.fontSize = `${pourcentageHeight * 3.5}%`;
            }

            if (pourcentageHeight > 20) {
                for (element of coursElement.children) {
                    element.style.fontSize = '10px';
                }
                coursElement.getElementsByClassName("UV")[0].style.fontSize = '12px';
            }

            resolve(cours); // Résoudre la promesse une fois que tout est terminé avec succès
        } catch (error) {
            reject(error); // Rejeter la promesse en cas d'erreur
        }
    });
}


function colorChange(event){
    var UV = event.target.id.split("color")[1];
    var Liste_cours = document.getElementsByClassName("cours");
    for (var cours of Liste_cours){
        var texte = cours.querySelector('h2.UV').textContent;
        var regex = /^([A-Z0-9]+) - (ACT|TD|TP|CM)( A| B)?$/i;
        var match = texte.match(regex);
        var currentCode = match[1];
        if (currentCode == UV){
            cours.style.background = event.target.value;
            coursColors[UV] = event.target.value;
            const idCurrentCours = parseInt(cours.id);
            (async () => {
                try {
                    const db = await ouvrirBaseDeDonneesCours();
                    const courses = await getCoursByID(db, idCurrentCours);
                    if (courses) {
                        await modifierAttributCoursByID(db, idCurrentCours, 'couleur', event.target.value);
                    } else {
                        console.log("Erreur : aucun cours avec cet ID !")
                    }
                } catch (error) {
                    console.error(error);
                }
            })();
        }

    }

}

function stopHovering(){
    isHovering = false;
    var hoverCours = document.getElementById("rightHover");
    var hoverLeft = document.getElementById("leftHover");
    hoverCours.style.display = "none";
    hoverLeft.style.display = "none";
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

function calculerDifference() {
    // Récupérer les valeurs des input time
    var time1 = input_hdebut[1].value;
    var time2 = input_hfin[1].value;
    // Vérifier si les valeurs sont non vides
    if (time1 && time2) {
        // Convertir les valeurs en minutes
        var minutes1 = convertirEnMinutes(time1);
        var minutes2 = convertirEnMinutes(time2);

        // Calculer la différence en minutes
        var differenceMinutes = minutes2 - minutes1;

        // Convertir la différence en heures et minutes
        var heures = Math.floor(differenceMinutes / 60);
        var minutes = differenceMinutes % 60;
        // Mettre à jour le champ de résultat
        return heures + ':' + (minutes < 10 ? '0' : '') + minutes;
    }else{
        return null;
    }
}

var suivreLaSouris = false;
var lastPosition;
var tempsCurrentCours;
function suivreSouris(element, isCours) {
    var parentJourElement = element.closest('.jour');
    if (parentJourElement) {
        (async () => {
            try {
                const db = await ouvrirBaseDeDonneesCours();
                const idCurrentCours = parseInt(element.id);
                const courses = await getCoursByID(db, idCurrentCours);
                var heureElement = element.getElementsByTagName('p')[0].innerHTML.split("-");
                var heureDebut = formaterHeure(heureElement[0].replace("h",":")).replace(":","h");
                var heureFin = formaterHeure(heureElement[1].replace("h",":")).replace(":","h");
                var coursElement = event.target;
                var texte = element.querySelector('h2.UV').textContent;
                var regex = /^([A-Z0-9]+) - (ACT|TD|TP|CM)( A| B)?$/i;
                var match = texte.match(regex);
                var semaine = match[3] ? match[3].replace(" ","") : null;
                if (courses) {
                await modifierAttributCoursByID(db, idCurrentCours, 'jour', parentJourElement.id);
                await modifierAttributCoursByID(db, idCurrentCours, 'horaireDebut', heureDebut); /* Ajouter un 0 devant l'heure si < 10*/
                await modifierAttributCoursByID(db, idCurrentCours, 'horaireFin', heureFin);
                await modifierAttributCoursByID(db, idCurrentCours, 'semaine', semaine);
                } else {
                    console.log("Erreur : aucun cours avec cet ID !")
                }
            } catch (error) {
                console.error(error);
            }
        })();
    } else {
        console.log("Aucun parent avec la classe 'jour' n'a été trouvé.");
    }
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
            const idCurrentCours = parseInt(coursEnDeplacement.id);

            if(lastPosition != newPosition && lastPosition !== null){
                var heureDebutCours = convertirDecimalEnHeure(heureDebutEDT + (newPosition * nbHeureEDT / coursEnDeplacement.parentElement.offsetHeight));
                var heureFinCours = convertirDecimalEnHeure(heureDebutEDT + ((newPosition + coursEnDeplacement.offsetHeight) * nbHeureEDT / coursEnDeplacement.parentElement.offsetHeight));
                coursEnDeplacement.getElementsByClassName("horaire_cours")[0].innerHTML = formaterHeure(heureDebutCours.replace("h",":")) + "-" + formaterHeure(heureFinCours.replace("h",":"));
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
                    function getCurrentDay(){
                        return jour;
                    }
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
        tempsCurrentCours=calculerDifference();
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
    recupererParametresUtilisateur()
        .then(parametres => {
            parametres.couleurEntete = event.target.value;
            return sauvegarderParametresUtilisateur(parametres);
        })
        .then(message => {
            return recupererParametresUtilisateur();
        })
        .catch(error => console.error("Erreur :", error));
    // Cacher à nouveau l'interface de couleur
    var days = document.getElementsByClassName("titleday");
    for (let day of days){
        day.style.background = event.target.value;
    }
});

function customTime(event) {
    var liste_Cours = document.getElementsByClassName("cours");
    var cours = [];

    /* Informations du formulaire : heure début & fin*/

    var debut = parseInt(document.getElementById("custom-input-hdebut").value);
    var fin = parseInt(document.getElementById("custom-input-hfin").value);

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

        var regex = /^([A-Z0-9]+) - (ACT|TD|TP|CM)( A| B)?$/i;

        var match = texte.match(regex);
        var codeUV = match[1];
        var typeMatiere = match[2];
        var semaine = match[3] ? match[3] : null;
        var heuresText = coursElement.querySelector('p').textContent.trim();
        var heuresSegments = heuresText.split('-');
        var heureDebut = heuresSegments[0].trim();
        var heureFin = heuresSegments[1].trim();
        var salle = coursElement.querySelector('p:nth-of-type(2)').textContent.trim();
        var currentID = parseInt(coursElement.id);

        if (parseInt(debut) <= parseInt(heureDebut) && parseInt(fin) >= parseInt(heureFin)){
            var currentCours = new Cours(codeUV , heureDebut , heureFin , jour , salle , semaine , null , typeMatiere , currentID);
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
                i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\"> <div class='dash' style='height:6vh'></div> </div>";
            }else{
                i.innerHTML += "<div class='divHeure' onclick=\"addCreneau(event)\"> <div style='height:6vh'></div> </div>";
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

    recupererParametresUtilisateur()
        .then(parametres => {
            parametres.horaireDebut = document.getElementById("custom-input-hdebut").value;
            parametres.horaireFin = document.getElementById("custom-input-hfin").value;
            return sauvegarderParametresUtilisateur(parametres);
        })
        .then(message => {
            return recupererParametresUtilisateur();
        })
        .catch(error => console.error("Erreur :", error));

}

function resetEDT(event) {
    (async () => {
        try {
            document.querySelectorAll(".cours").forEach( function (cours){
                cours.remove();
            });
            document.getElementById("couleurSpan").innerHTML = "";
            await supprimerTousLesCours();
        } catch (error) {
            console.error(error);
        }
    })();
    var colorList = [
        "#FF9292","#FFA792","#FFB692","#FFC592","#FFD992",
        "#FFE692","#FFFD92","#EAFF92","#CAFF92","#B5FF92",
        "#97FF92","#92FFB5","#92FFD6","#92FFFD","#92EAFF",
        "#92CCFF","#92AEFF","#9792FF","#A492FF","#C092FF",
        "#D792FF","#EA92FF","#FF92EA","#FF92D6","#FF92BF"
    ];
}

document.addEventListener("click" , function (event) {

    if (!(event.target.closest("#menuCustom")) && document.getElementById("menuCustom") != "none" && event.target.id !== "custom_edt"){
        document.getElementById("menuCustom").style.display = "none";
    }

});


// Create a function for setting a variable value
function changeCSSVar(variable, valeur) {
    var r = document.querySelector(':root');
    r.style.setProperty('--' + variable, valeur);
}

function pxToVw(pxValue) {
    // Obtenir la largeur de la fenêtre
    var windowWidth = window.innerWidth;

    // Calculer la valeur en vue (vw)
    var vwValue = (pxValue / windowWidth) * 100;

    return vwValue;
}

function changeJour(event){
    
    if(window.innerWidth > 600){
        var listeJour = document.getElementsByClassName("jour");
        var pushJour = [];
        var nbJour = 0;
        var taillePrec = null;
        for (var jour of listeJour){
            if (jour.style.display != "none"){
                nbJour +=1;
                taillePrec = jour.offsetWidth;
            }
        }

        if (event.target.className == "check"){
            changeCSSVar("taille-edt",pxToVw(taillePrec * nbJour / (nbJour-1)) + "vw");
        }else{
            changeCSSVar("taille-edt",pxToVw(taillePrec * nbJour / (nbJour+1)) + "vw");
        }
        var jour = event.target.innerHTML.toLowerCase();
        document.getElementById(jour).style.display = "none";
        var listeJour = document.getElementsByClassName("jour");
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
        Array.from(document.querySelectorAll("#spanJour .check")).forEach(jour => {
            pushJour.push(jour.innerHTML);
        });
        recupererParametresUtilisateur()
            .then(parametres => {
                parametres.jours = pushJour;
                return sauvegarderParametresUtilisateur(parametres);
            })
            .then(message => {
                return recupererParametresUtilisateur();
            })
            .catch(error => console.error("Erreur :", error));
    
        clearBorderRadius();
    
        setBorderRadius();
    }
    
    
}

function changePolice(event){
    var police = event.target.innerHTML;
    document.getElementsByClassName("checkElement")[0].className = "uncheckElement";
    event.target.className = "checkElement";
    document.body.style.fontFamily = `${police} , sans-serif`;

    recupererParametresUtilisateur()
        .then(parametres => {
            parametres.police = event.target.innerHTML;
            return sauvegarderParametresUtilisateur(parametres);
        })
        .then(message => {
            return recupererParametresUtilisateur();
        })
        .catch(error => console.error("Erreur :", error));
}

function openCustom(event){
    var conteneurCustom = document.getElementById("menuCustom");
    conteneurCustom.style.display = "flex";
}

function closeCustom(event){
    var conteneurCustom = document.getElementById("menuCustom");
    conteneurCustom.style.display = "none";
}

function supprimerCustom(){
    var couleurSpan = document.getElementById("couleurSpan").innerHTML;
    document.getElementById("filterContainer1").innerHTML = '<div class="conteneur_custom" id="filterContainer1"><div class="custom_parent" id="police"><h1 class="custom_entete">Police</h1><span class="custom_span"><h3 class="checkPolice" onclick="changePolice(event)" id="mainPolice">Jost</h3><h3 class="uncheckPolice" onclick="changePolice(event)">Times New Roman</h3><h3 class="uncheckPolice" onclick="changePolice(event)">Comic Sans MS</h3></span></div><div class="custom_parent" id="jours"><h1 class="custom_entete">Jour</h1><span class="custom_span" id="spanJour"><h3 class="check" onclick="changeJour(event)">Lundi</h3><h3 class="check" onclick="changeJour(event)">Mardi</h3><h3 class="check" onclick="changeJour(event)">Mercredi</h3><h3 class="check" onclick="changeJour(event)">Jeudi</h3><h3 class="check" onclick="changeJour(event)">Vendredi</h3><h3 class="check" onclick="changeJour(event)">Samedi</h3><h3 class="uncheck" onclick="changeJour(event)">Dimanche</h3></div><div class="custom_parent" id="couleurs"><h1 class="custom_entete">Couleurs</h1><span><span class="custom_span" id="couleurSpan"></span></span></div><div class="custom_parent" id="couleur_entete"><h1 class="custom_entete">Couleur entête</h1><span class="custom_span"><div class="inputCouleur" id="inputCouleur" style=""><span style="margin-left: 20px"><input class="colorChoice" type="color" id="choix-couleur" name="choix-couleur" style="position: absolute; ; width: 2px ; height: 2px"></span></div></span></div><div class="custom_parent" id="langue"><h1>Langue</h1><span class="custom_span"><h3>Anglais</h3><h3>Français</h3><h3>Espagnol</h3></span></div><div class="custom_parent" id="heures"><h1>Horaires</h1><span class="custom_span"><div><input type="time" id="custom-input-hdebut" name="hdebut" value="08:00" required onchange="customTime(event)"></div><div><input type="time" id="custom-input-hfin" name="hfin" value="20:00" required onchange="customTime(event)"></div></span></div><div class="buttonCustoms"><button class="customButton" id="appliquerCustom" onclick="supprimerCustom(event)">Supprimer les customs</button></div></div>';
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
    document.getElementById("custom-input-hfin").value = "20:00";
    var input = document.getElementById("custom-input-hdebut");
    input.value = "08:00";
    var event = new Event('change');
    input.dispatchEvent(event);

    document.getElementById("couleurSpan").innerHTML = couleurSpan;
    supprimerFiltres();

}
var importElement = document.getElementById("importEDTID");
var exportElement = document.getElementById("exportEDT");
var ecran = document.getElementById("ecran_edt");

function menuimportEDT(event){
    document.getElementById("nouveau_pannel").style.display = "none";
    importElement.style.display = "flex";
    event.stopPropagation();
    ecran.style.display = "block";
}

function menuExportEDT(event){
    document.getElementById("nouveau_pannel").style.display = "none";
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
    try {
        var listeInformations = entree.split(" ");
        var listeSansVide = listeInformations.filter(function(element) {
            // Retourne true si l'élément n'est pas vide
            return element.trim() !== "";
        });
        var semaine = null;
        if (listeSansVide.length === 5){
            var type = listeSansVide[1];
            var groupe = listeSansVide[2];
            var jour = listeSansVide[3];
            var details = listeSansVide[4];
            //let [codeUV , type , groupe , jour, details] = listeSansVide;
        } else if (listeSansVide.length === 4){
            var type = listeSansVide[1].substring(0,1);
            var groupe = listeSansVide[2].substring(1);
            var jour = listeSansVide[2];
            var details = listeSansVide[3];
        } else if (listeSansVide.length === 6){
            var type = listeSansVide[1];
            var groupe = listeSansVide[2];
            semaine = listeSansVide[3];
            var jour = listeSansVide[4];
            var details = listeSansVide[5];
            //let [codeUV , type , groupe , semaine , jour, details] = listeSansVide;
        } else {
            return null;
        }
        var codeUV = listeSansVide[0];
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
                type=null;
        }
        if (codeUV.length !== 4 || type === null || salle.length !== 5 || !((heureDebut) && (heureFin))){
            if (codeUV.length === 4 && salle.length !== 5){
                var salle = prompt(`Merci de saisir une salle pour l'UV : ${codeUV} - ${type}`).toUpperCase();
            } else {
                return null
            }
        }
        return new Cours(codeUV , heureDebut.replace(":","h") , heureFin.replaceAll(":","h") , jour.toLowerCase() , salle , semaine , null , type);
    } catch (e) {
        alert("Erreur dans l'importation. N'hésitez pas à envoyer votre mail d'UV au SIMDE afin de résoudre le problème...")
        return null;
    }
}

const dropArea = document.querySelector("#drag_file_edt"),
dragText = dropArea.querySelector("h6"),
button_input_file = dropArea.querySelector("button"),
input_file = dropArea.querySelector("input");
let file; 

button_input_file.onclick = ()=>{
    input_file.click(); 
}

input_file.addEventListener("change", function(){
 
  file = this.files[0];
  dropArea.classList.add("active");
  sendFile();
});

dropArea.addEventListener("dragover", (event)=>{
  event.preventDefault();
  dropArea.classList.add("active");
  dragText.textContent = "Relacher le fichier ici";
});


dropArea.addEventListener("dragleave", ()=>{
  dropArea.classList.remove("active");
  dragText.textContent = "Drag & Drop le fichier ici";
}); 

dropArea.addEventListener("drop", (event)=>{
  event.preventDefault(); 
  file = event.dataTransfer.files[0];
  sendFile(); 
});

function sendFile(){
    if (file) {
        resetEDT(event);
        document.getElementById("midFileError").classList.toggle("hidden", true);
        document.getElementById("input_file_edt").value = "";

        const reader = new FileReader();

        reader.onload = async function(e) {
            const content = e.target.result;
            const lines = content.split('\n');

            for (const line of lines) {
                if (line.length > 1) {
                    const detailsLine = line.split(";");
                    const currentCours = new Cours(detailsLine[0], detailsLine[3], detailsLine[4], detailsLine[6], detailsLine[5], detailsLine[2] === "null" ? null : detailsLine[2], null, detailsLine[1]);
                    try {
                        await createCours(currentCours);
                    } catch (error) {
                        console.error(error);
                    }
                }
            }
        };
        reader.readAsText(file);
        canClose = true;
    } else {
        document.getElementById("midFileError").classList.toggle("hidden", false);
    }
    if (canClose){
        document.getElementById("importEDTID").style.display = "none";
        document.getElementById("textUV").value = "";
        document.getElementById("ecran_edt").style.display = "none";
    }
}

async function importEDT(event) {
    try {
        var canClose = false;
        var allCourses = document.getElementById("textUV").value.split("\n");
        coursColors = {};
        if (document.getElementById("messageUV").checked) {
            resetEDT(event);
            for (var currentCours of allCourses) {
                if (currentCours.length > 20) {
                    if (currentCours.includes("/")){
                        const occurrences = (currentCours.match(/\//g) || []).length;
                        var listeInformations = currentCours.split(" ");
                        var listeSansVide = listeInformations.filter(function(element) {
                            // Retourne true si l'élément n'est pas vide
                            return element.trim() !== "";
                        });
                        var codeUV = listeSansVide[0];
                        var type = listeSansVide[1];
                        var groupe = listeSansVide[2];
                        for (var i = 0 ; i <= occurrences ; i++){
                            var currentSplitCourses = currentCours.split("/");
                            const parametre = (i === 0) ? ` ${currentSplitCourses[i]}` : `${codeUV} ${type} ${groupe} ${currentSplitCourses[i]}`;
                            var cours = transformerEntreeCours(parametre);
                            if (cours != null){
                                await createCours(cours);
                            }
                        }
                    } else {
                        var cours = transformerEntreeCours(currentCours);
                        if (cours != null){
                            await createCours(cours);
                        }
                    }
                }
            }
            canClose = true;
        }
        if (canClose){
            document.getElementById("importEDTID").style.display = "none";
            document.getElementById("textUV").value = "";
            document.getElementById("ecran_edt").style.display = "none";
        }
    } catch (error) {
        console.error(error);
    }
}

function lineIsCours(line){
    var detailsLine = line.split(" ");
    if ((detailsLine[0].length === 4 || detailsLine[1].length === 4)){

    }
}

/* Partie pour la sauvegarde des données en local */


if (window.indexedDB){
    afficherTousLesCours(); // Charger les cours au début
    recupererParametresUtilisateur()
        .then(parametres => {
            if (parametres.police != document.getElementById("mainPolice").innerHTML){
                document.getElementById(parametres.police).click();
            }
            if (parametres.horaireDebut && parametres.horaireFin){
                document.getElementById("custom-input-hdebut").value = parametres.horaireDebut;
                document.getElementById("custom-input-hfin").value = parametres.horaireFin;
                customTime(event);
            }
            if (parametres.couleurEntete){
                inputCouleur.style.backgroundColor = parametres.couleurEntete;
                var days = document.getElementsByClassName("titleday");
                for (let day of days){
                    day.style.background = parametres.couleurEntete;
                }
            }
            Array.from(document.querySelectorAll("#spanJour h3")).forEach(jour => {
                if ((parametres.jours.includes((jour.innerHTML))) && jour.className === "uncheck"){
                    jour.click();
                }
                else if (!(parametres.jours.includes((jour.innerHTML))) && jour.className === "check"){
                    jour.click();
                }
            });
        })
        .then(message => {
            return recupererParametresUtilisateur();
        })
        .catch(error => console.error("Erreur :", error));
} else {
    alert("Attention, indexDB est désactivé sur votre navigateur. Nous ne pourrons pas sauvegarder votre emploi du temps...")
}

// Fonction pour ouvrir ou créer une base de données IndexedDB
function ouvrirBaseDeDonneesCours() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('cours', 1);

        request.onerror = function(event) {
            reject("Erreur lors de l'ouverture de la base de données: " + event.target.error);
        };

        request.onupgradeneeded = function(event) {
            const db = event.target.result;
            const objectStore = db.createObjectStore('cours', { keyPath: 'id', autoIncrement: true });

            // Ajouter des index si nécessaire
            objectStore.createIndex('codeUV', 'codeUV', { unique: false });
        };

        request.onsuccess = function(event) {
            const db = event.target.result;
            resolve(db);
        };
    });
}

// Fonction pour ajouter un cours à la base de données
function ajouterCours(db, cours) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['cours'], 'readwrite');
        const objectStore = transaction.objectStore('cours');

        const request = objectStore.add(cours);

        request.onsuccess = function(event) {
            resolve("Cours ajouté avec succès à la base de données");
        };

        request.onerror = function(event) {
            reject("Erreur lors de l'ajout du cours: " + event.target.error);
        };
    });
}

// Fonction pour récupérer tous les cours de la base de données
function getAllCours(db) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['cours'], 'readonly');
        const objectStore = transaction.objectStore('cours');

        const request = objectStore.getAll();

        request.onsuccess = function(event) {
            resolve(event.target.result);
        };

        request.onerror = function(event) {
            reject("Erreur lors de la récupération des cours: " + event.target.error);
        };
    });
}

// Fonction pour récupérer un cours par son ID
function getCoursByID(db, id) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['cours'], 'readonly');
        const objectStore = transaction.objectStore('cours');

        const request = objectStore.get(id);

        request.onsuccess = function(event) {
            const cours = event.target.result;
            if (cours) {
                resolve(cours); // Le cours a été trouvé
            } else {
                resolve(null); // Le cours n'existe pas dans la base de données
            }
        };

        request.onerror = function(event) {
            reject("Erreur lors de la récupération du cours: " + event.target.error);
        };
    });
}
// Fonction pour afficher tous les cours
async function afficherTousLesCours() {
    try {
        const db = await ouvrirBaseDeDonneesCours();

        // Récupérer tous les cours de la base de données
        const tousLesCours = await getAllCours(db);
        tousLesCours.forEach(cours => {
            createCours(cours);
        });
    } catch (error) {
        console.error(error);
    }
}

async function supprimerTousLesCours() {
    try {
        const db = await ouvrirBaseDeDonneesCours();
        // Récupérer tous les cours de la base de données
        const tousLesCours = await getAllCours(db);
        tousLesCours.forEach(cours => {
            supprimerCoursByID(db , cours.id);
        });
    } catch (error) {
        console.error(error);
    }
}

// Fonction pour supprimer un cours de la base de données par son ID
async function supprimerCoursByID(db , id) {
    try {
        const transaction = db.transaction(['cours'], 'readwrite');
        const objectStore = transaction.objectStore('cours');

        const request = objectStore.delete(id);

        request.onsuccess = function(event) {
            console.log("Cours supprimé avec succès de la base de données");
        };

        request.onerror = function(event) {
            console.error("Erreur lors de la suppression du cours:", event.target.error);
        };
    } catch (error) {
        console.error(error);
    }
}

// Fonction pour modifier un attribut d'un cours par son ID
async function modifierAttributCoursByID(db, id, attribut, nouvelleValeur) {
    try {
        const transaction = db.transaction(['cours'], 'readwrite');
        const objectStore = transaction.objectStore('cours');

        const getCoursRequest = objectStore.get(id);

        getCoursRequest.onsuccess = function(event) {
            const cours = event.target.result;
            if (cours) {
                // Modifier l'attribut spécifié
                cours[attribut] = nouvelleValeur;
                const updateRequest = objectStore.put(cours);

                updateRequest.onsuccess = function(event) {
                    console.log("Attribut du cours modifié avec succès dans la base de données");
                };

                updateRequest.onerror = function(event) {
                    console.error("Erreur lors de la modification de l'attribut du cours:", event.target.error);
                };
            } else {
                console.log("Aucun cours trouvé avec l'ID:", id);
            }
        };

        getCoursRequest.onerror = function(event) {
            console.error("Erreur lors de la récupération du cours pour modification:", event.target.error);
        };
    } catch (error) {
        console.error(error);
    }
}
async function getMaxId(db) {
    return new Promise((resolve, reject) => {
        const transaction = db.transaction(['cours'], 'readonly');
        const objectStore = transaction.objectStore('cours');

        let maxId = -1; // Initialiser maxId avec une valeur minimale

        const request = objectStore.openCursor();

        request.onsuccess = function(event) {
            const cursor = event.target.result;
            if (cursor) {
                const courseId = cursor.value.id;
                if (courseId > maxId) {
                    maxId = courseId;
                }
                cursor.continue();
            } else {
                resolve(maxId);
            }
        };

        request.onerror = function(event) {
            reject(event.target.error);
        };
    });
}

/* Partie de sauvegarde pour les filtres */

// Fonction pour ouvrir la base de données "Filtres"
function ouvrirBaseDeDonneesFiltre() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('Filtres', 1);

        request.onerror = function(event) {
            reject("Erreur lors de l'ouverture de la base de données: " + event.target.error);
        };

        request.onsuccess = function(event) {
            const db = event.target.result;
            resolve(db);
        };

        request.onupgradeneeded = function(event) {
            const db = event.target.result;
            const objectStore = db.createObjectStore('parametres', { keyPath: 'utilisateur' }); // Utilisateur est une clé primaire fixe
            objectStore.transaction.oncomplete = function() {
                // Initialisation des valeurs par défaut
                const parametresObjectStore = db.transaction('parametres', 'readwrite').objectStore('parametres');
                parametresObjectStore.add({ utilisateur: 'utilisateur', jours: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'], horaireDebut: '08:00', horaireFin: '20:00', police: 'Jost', couleurEntete: '#CCCCCC' });
            };
        };
    });
}


// Fonction pour sauvegarder ou mettre à jour les paramètres d'un utilisateur
function sauvegarderParametresUtilisateur(parametres) {
    return new Promise((resolve, reject) => {
        ouvrirBaseDeDonneesFiltre().then(db => {
            const transaction = db.transaction(['parametres'], 'readwrite');
            const objectStore = transaction.objectStore('parametres');
            const request = objectStore.put(parametres);

            request.onsuccess = () => resolve("Paramètres de l'utilisateur sauvegardés avec succès !");
            request.onerror = (event) => reject("Erreur lors de la sauvegarde des paramètres de l'utilisateur : " + event.target.error);
        }).catch(error => reject(error));
    });
}

// Fonction pour récupérer les paramètres de l'utilisateur
function recupererParametresUtilisateur() {
    return new Promise((resolve, reject) => {
        ouvrirBaseDeDonneesFiltre().then(db => {
            const transaction = db.transaction(['parametres'], 'readonly');
            const objectStore = transaction.objectStore('parametres');
            const request = objectStore.get('utilisateur'); // Utilisateur est la clé primaire fixe

            request.onsuccess = () => {
                if (request.result) {
                    resolve(request.result);
                } else {
                    reject("Paramètres de l'utilisateur non trouvés !");
                }
            };
            request.onerror = (event) => reject("Erreur lors de la récupération des paramètres de l'utilisateur : " + event.target.error);
        }).catch(error => reject(error));
    });
}

function supprimerFiltres() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.deleteDatabase("Filtres");

        request.onsuccess = function () {
            console.log("BDD réinitialisée avec succès");
        }
        request.onerror = function () {
            console.log("Erreur dans la suppression des filtres...");
        }
    })
}

function insertActivity(){
    if (document.getElementById("input-isCours").checked){
        document.getElementById("creneauFirstLine").classList.toggle("hidden" , true);
        document.getElementById("creneauThirdLine").classList.toggle("hidden" , true);
        document.getElementById("activiteFirstLine").classList.toggle("hidden" , false);
        document.getElementById("activiteThirdLine").classList.toggle("hidden" , false);
    }
    else {
        document.getElementById("creneauFirstLine").classList.toggle("hidden" , false);
        document.getElementById("creneauThirdLine").classList.toggle("hidden" , false);
        document.getElementById("activiteFirstLine").classList.toggle("hidden" , true);
        document.getElementById("activiteThirdLine").classList.toggle("hidden" , true);
    }

}

var dessus_edt = document.getElementById("dessus_edt");
var taille_edt = document.querySelector(".conteneurHours").offsetWidth + document.querySelector("#emploi_du_temps").offsetWidth;

var joursComplet = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
var joursAbrege = ['Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.', 'Dim.'];
var joursTresAbrege = ['L', 'Ma', 'Me', 'J', 'V', 'S', 'D'];
var jours = document.querySelectorAll('#menu_jour_edt li');
dessus_edt.style.width = pxToVw(taille_edt) + "vw";

function mettreAJourContenu() {
    var listeJour = document.getElementsByClassName("jour");
    var largeurFenetre = window.innerWidth;

    // Changez le contenu en fonction de la largeur de la fenêtre
    var exportTXT = document.getElementById('export_txt');
    var exportPNG = document.getElementById('export_png');

    var messageExport = document.getElementById('messageExport').parentElement.querySelector("label");
    var messageUV = document.getElementById('messageUV').parentElement.querySelector("label");

    if (largeurFenetre <= 600) {
        dessus_edt.style.width = ""
    }else{
        dessus_edt = document.getElementById("dessus_edt");
        taille_edt = document.querySelector(".conteneurHours").offsetWidth + document.querySelector("#emploi_du_temps").offsetWidth;
        dessus_edt.style.width = pxToVw(taille_edt) + "vw";
    }
    
    if (largeurFenetre <= 400) {
        messageUV.innerHTML = "UV";
        messageExport.innerHTML = "Export";
        exportTXT.innerHTML = 'TXT';
        exportPNG.innerHTML = 'PNG';
    } else {
        messageUV.innerHTML = "Message d'UV";
        messageExport.innerHTML = "Message d'export";
        exportTXT.innerHTML = 'Exporter TXT';
        exportPNG.innerHTML = 'Exporter PNG';
    }

    if(largeurFenetre <= 600){
        jours.forEach(function(jour, index) {
            jour.textContent = joursComplet[index];
        });

        var nbJour = 0;
        var taillePrec;
        for(jour of listeJour){
            if(jour.style.display != "none"){
                nbJour++;
            }
            jour.style.display = "";
            jour.style.border = "none";
            jour.style.borderRadius ="none";
            taillePrec = jour.offsetWidth;
        }
        clearBorderRadius();
        changeCSSVar("taille-edt","12vw");
    }else{
        var premierJourVisible = true; // Variable pour garder une trace du premier jour visible

        for (var jour of listeJour) {
            if (jour.style.display !== "none") { // Vérifie si le jour est visible
                if (premierJourVisible) { // Si c'est le premier élément visible
                    jour.style.borderLeft = "1px solid black"; // Applique une bordure à gauche uniquement pour le premier élément visible
                    premierJourVisible = false; // Met à jour la variable pour les éléments suivants
                }
                jour.style.borderTop = "1px solid black"; // Applique une bordure en haut
                jour.style.borderRight = "1px solid black"; // Applique une bordure à droite
                jour.style.borderBottom = "1px solid black"; // Applique une bordure en bas
            }
        }
        setBorderRadius();
    }

    if(largeurFenetre <=450 && largeurFenetre > 300){
        jours.forEach(function(jour, index) {
            jour.textContent = joursAbrege[index];
        });
    }else if(largeurFenetre <=300){
        jours.forEach(function(jour, index) {
            jour.textContent = joursTresAbrege[index];
        });
    }
}

// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenu);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenu();

const dropZone = document.getElementById('dropZone_custom');
const emploi_du_temps = document.getElementById('emploi_du_temps');

var dragTextImageCustom = dropZone.querySelector("h6");
var buttonImageCustom = dropZone.querySelector("button");
var inputImageCustom = dropZone.querySelector("input");
let fileImageCustom; 

buttonImageCustom.onclick = ()=>{
    inputImageCustom.click(); 
}

inputImageCustom.addEventListener("change", function(){
    fileImageCustom = this.files[0];
    dropZone.classList.add("active");
    sendFileImageCustom();
});

dropZone.addEventListener("dragover", (event)=>{
  event.preventDefault();
  dropZone.classList.add("active");
  dragTextImageCustom.textContent = "Relacher le fichier ici";
});


dropZone.addEventListener("dragleave", ()=>{
    dropZone.classList.remove("active");
    dragTextImageCustom.textContent = "Drag & Drop le fichier ici";
}); 

dropZone.addEventListener("drop", (event)=>{
    event.preventDefault(); 
    fileImageCustom = event.dataTransfer.files[0];
    sendFileImageCustom(); 
});

function sendFileImageCustom(){
    if (fileImageCustom) {
        // Vérifier si le fichier est une image en vérifiant son type MIME
        if (fileImageCustom.type.startsWith('image/')) {
            // Si c'est une image, continuer le traitement avec image-compressor.js
            const reader = new FileReader();

            reader.onload = function(event) {
                changeCSSVar("bg-image", `url(${event.target.result})`)
            };

            reader.readAsDataURL(fileImageCustom);
        } else {
            // Si ce n'est pas une image, afficher un message d'erreur ou ignorer le fichier
            console.error('Le fichier déposé n\'est pas une image.');
        }
    }
}


var sliderBlur = document.getElementById("range_blur");

sliderBlur.addEventListener("input", function() {
    changeCSSVar("bg-image-blur",sliderBlur.value+"px");
});

var sliderTop = document.getElementById("range_top");

sliderTop.addEventListener("input", function() {
    changeCSSVar("bg-position",sliderTop.value + "%");
});

var sliderBlack = document.getElementById("range_black");

sliderBlack.addEventListener("input", function() {
    changeCSSVar("bg-black",sliderBlack.value/10);
});