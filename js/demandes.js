document.addEventListener('DOMContentLoaded', function() {
    sortByRecent();
});
const joursSemaine = {
    "1": 'Lundi',
    "2": 'Mardi',
    "3": 'Mercredi',
    "4": 'Jeudi',
    "5": 'Vendredi',
    "6": 'Samedi'
};
function copierLien(element) {
    // Récupérer les informations sur la demande
    var uvType = element.closest('.div_demande').querySelector('h2').innerText;
    var horaires = element.closest('.div_demande').querySelector('h4').innerText;
    var matchResult = horaires.match(/(\d{2}:\d{2}) - (\d{2}:\d{2})/);
    var heureDebut;
    var heureFin;
    // Vérifier si des correspondances ont été trouvées
    if (matchResult) {
        
        heureDebut = matchResult[1];
        heureFin = matchResult[2];
    }
    // Créer le lien avec les informations
    var lien = "../php/demandes.php?uv=" + encodeURIComponent(uvType.split(" - ")[0]) +
                "&type=" + encodeURIComponent(uvType.split(" - ")[1]) +
                "&hDeb=" + encodeURIComponent(heureDebut) +
                "&hFin=" + encodeURIComponent(heureFin);

    // Créer un élément textarea temporaire pour copier le texte dans le presse-papiers
    var textarea = document.createElement('textarea');
    textarea.value = lien;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    document.body.removeChild(textarea);

    // Afficher un message ou effectuer d'autres actions si nécessaire
    alert("Le lien a été copié dans le presse-papiers !");
    event.stopPropagation();
}

function clickDemande(element) {

    async function afficherTousLesCours() {
        try {
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
                if(donnees.codeUV !== "" && donnees.type !== "" && donnees.salle !== "") {
                    nouveauClick();
                    bouton_non_submit.className = "submitSwap";

                    input_uv.value = donnees.codeUV;
                    input_type.value = donnees.type;
                    input_uv.disabled = true;
                    input_type.disabled = true;
                    localStorage.setItem("idDemande" , donnees.idDemande);
                    localStorage.setItem("salle" , donnees.salle);
                    localStorage.setItem("hdebut" , donnees.horaireDebut);
                    localStorage.setItem("hfin" , donnees.horaireFin);
                    localStorage.setItem("jour" , joursSemaine[donnees.jour]);

                    tempsCurrentCours = calculerDifference(donnees.horaireDebut , donnees.horaireFin);


                    if (donnees.semaine === "null") {
                        checkbox.disabled = true;
                    } else {
                        checkbox.disabled = false;
                        checkbox.click();
                        checkbox.checked = true;
                        localStorage.setItem("semaine" , donnees.semaine);
                    }

                    const db = await ouvrirBaseDeDonnees();
                    // Récupérer tous les cours de la base de données
                    const tousLesCours = await getAllCours(db);
                    tousLesCours.forEach(cours => {
                        if (cours.type === donnees.type && cours.codeUV === donnees.codeUV){
                            input_salle.value = cours.salle;
                            input_creneau.value = cours.jour.toLowerCase();
                            Array.from(input_hdebut).map(element => {
                                const [heures, minutes] = cours.horaireDebut.split('h');
                                element.value = `${heures.padStart(2,'0')}:${minutes}`;
                            });
                            Array.from(input_hfin).map(element => {
                                const [heures, minutes] = cours.horaireFin.split('h');
                                element.value = `${heures.padStart(2,'0')}:${minutes}`;
                            });
                        }
                    });

                }

            }

        } catch (error) {
            console.error(error);
        }
    }
    afficherTousLesCours();    
}

function calculerDifference(time1 , time2) {
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
function filtrerUV(event){
    document.getElementById('filterForm').submit();
}

function openFiltre(event){
    var conteneurFiltre = document.getElementById("menuFiltre");
    conteneurFiltre.style.display = "flex";
}

function closeFiltre(event){
    var conteneurFiltre = document.getElementById("menuFiltre");
    conteneurFiltre.style.display = "none";
}

/* Partie sur les filtres */

var input_code_uv = document.getElementById('filtre_demandes_codeUV');
input_code_uv.addEventListener("keyup" , function (event){
    if (event.keyCode === 13){
        researchUV(event);
    }
});

function researchUV(event){
    if (input_code_uv.value.length == 0){
        var divs_demande = document.getElementsByClassName("div_demande");
        Array.from(divs_demande).forEach(function(div) {
            div.style.display = "flex";
        });
    } else if (input_code_uv.value.length != 4){
        alert("Le code d'UV doit être composé de quatre caractères !");
    } else {
        // Créer un nouvel URLSearchParams avec la chaîne de requête actuelle
        var params = new URLSearchParams(window.location.search);
        // Ajouter le paramètre codeUV avec la valeur "uv"
        params.set("codeUV", input_code_uv.value);
        // Modifier l'URL dans la barre d'adresse sans recharger la page
        history.pushState(null, null, '?' + params.toString());
        canDisplayCourses(event);
    }
}
function changeFilter(event){
    if (document.getElementById("filtre_pertinence").checked){
        handlechangeFilter();
    } else if (document.getElementById("filtre_date").checked){
        sortBySchedule();
    } else if (document.getElementById("filtre_demande").checked){
        sortByDemand();
    } else if (document.getElementById("filtre_auteur").checked) {
        sortByAuthor();
    } else {
        sortByRecent();
    }
}
async function handlechangeFilter(event) {
    const pointsType = {"TD":1 , "TP":0.5,"CM":0}
    const allDemandes = Array.from(document.getElementsByClassName("div_demande"));
    const { moyenne, min, max } = meansDemandes(allDemandes);
    var nbPoints = {};
    for (const demande of allDemandes) {
        var rowAttribute = demande.dataset.row;
        if (rowAttribute) {
            try {
                var donnees = JSON.parse(atob(rowAttribute));
                const hasUV = await checkIfHasUV(donnees.codeUV);
                nbPoints[donnees.idDemande] = 0;
                if (hasUV) {
                    nbPoints[donnees.idDemande] += 7;

                }
                const compatible = await checkIfCreneauCompatible(joursSemaine[donnees.jour], donnees.horaireDebut.slice(0,-3).replace(":","h"), donnees.horaireFin.slice(0,-3).replace(":","h"));
                if (compatible) {
                    nbPoints[donnees.idDemande] += 3;
                }
                nbPoints[donnees.idDemande] += pointsType[donnees.type];
                nbPoints[donnees.idDemande] += valeurdecroissante(donnees.nbDemandes , moyenne , min , max);
            } catch (error) {
                console.error("Erreur lors du parsing JSON :", error);
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }
    }
    rearrangeElements(nbPoints);
}

function rearrangeElements(nbPoints) {
    const container = document.querySelector('.demande_container');
    const elements = Array.from(container.children);

    // Trier les éléments en fonction du nombre de points
    elements.sort((a, b) => {
        const idDemandeA = JSON.parse(atob(a.dataset.row)).idDemande;
        const idDemandeB = JSON.parse(atob(b.dataset.row)).idDemande;

        const pointsA = nbPoints[idDemandeA] || 0;
        const pointsB = nbPoints[idDemandeB] || 0;

        return pointsB - pointsA;
    });

    // Réorganiser les éléments dans le conteneur
    elements.forEach(element => container.appendChild(element));
}
function sortByAuthor() {
    const container = document.querySelector('.demande_container');
    const elements = Array.from(container.children);
    elements.sort((a, b) => {
        const loginA = JSON.parse(atob(a.dataset.row)).login.toLowerCase();
        const loginB = JSON.parse(atob(b.dataset.row)).login.toLowerCase();
        return loginA.localeCompare(loginB);
    });

    elements.forEach(element => container.appendChild(element));
}

function sortByDemand() {
    const container = document.querySelector('.demande_container');
    const elements = Array.from(container.children);
    elements.sort((a, b) => {
        const dataA = JSON.parse(atob(a.dataset.row));
        const dataB = JSON.parse(atob(b.dataset.row));
        return dataA.nbDemandes - dataB.nbDemandes;
    });
    elements.forEach(element => container.appendChild(element));
}

function sortBySchedule() {
    const container = document.querySelector('.demande_container');
    const elements = Array.from(container.children);
    elements.sort((a, b) => {
        const dataA = JSON.parse(atob(a.dataset.row));
        const dataB = JSON.parse(atob(b.dataset.row));
        const timeDecimalA = calculDecimal(dataA.horaireDebut.slice(0,-3).replace(":","h"));
        const timeDecimalB = calculDecimal(dataB.horaireDebut.slice(0,-3).replace(":","h"));
        // Tri par jour
        if (dataA.jour !== dataB.jour) {
            return dataA.jour - dataB.jour;
        }
        // Tri par heure (en utilisant la valeur décimale)
        return timeDecimalA - timeDecimalB;
    });
    elements.forEach(element => container.appendChild(element));
}
// Définition de la fonction de tri par ID de demande (le plus grand en premier)
function sortByRecent() {
    const container = document.querySelector('.demande_container');
    const elements = Array.from(container.children);
    elements.sort((a, b) => {
        const idDemandeA = JSON.parse(atob(a.dataset.row)).idDemande;
        const idDemandeB = JSON.parse(atob(b.dataset.row)).idDemande;
        return idDemandeB - idDemandeA; // Trie de manière décroissante
    });
    elements.forEach(element => container.appendChild(element));
}




function checkIfCreneauCompatible(jour, heureDebut, heureFin) {
    return new Promise(async (resolve, reject) => {
        try {
            const db = await ouvrirBaseDeDonnees();
            const courses = await getAllCours(db);
            var isAvailable = true;
            courses.forEach(cours => {
                if (cours.jour.toLowerCase() === jour.toLowerCase()){
                    if (!((calculDecimal(cours.horaireFin) <= calculDecimal(heureDebut)) || (calculDecimal(heureFin) <= calculDecimal(cours.horaireDebut)))){
                        isAvailable = false;
                    }
                }
            })
            resolve(isAvailable);
        } catch (error) {
            console.error(error);
            reject(error);
        }
    });
}
function checkIfHasUV(codeUV) {
    return new Promise(async (resolve, reject) => {
        try {
            const db = await ouvrirBaseDeDonnees();
            const courses = await getAllCours(db);
            let hasUV = false;
            courses.forEach(cours => {
                if (cours.codeUV === codeUV){
                    hasUV = true;
                }
            })
            resolve(hasUV);
        } catch (error) {
            console.error(error);
            reject(error);
        }
    });
}

function meansDemandes(Arraydemandes){
    var somme = 0;
    var min = Infinity; // Initialisez min à Infinity pour s'assurer que toute valeur sera inférieure à celle-ci
    var max = -Infinity; // Initialisez max à -Infinity pour s'assurer que toute valeur sera supérieure à celle-ci
    var nbDemandes = 0;
    Arraydemandes.forEach(demande => {
        var rowAttribute = demande.dataset.row;
        if (rowAttribute) {
            try {
                var donnees = JSON.parse(atob(rowAttribute));
                var nbDemandesCours = donnees.nbDemandes; // Assurez-vous d'utiliser la bonne variable ici (donnees.nbDemandes ou cours.nbDemandes ?)
                somme += nbDemandesCours;
                nbDemandes+=1;
                if (max < nbDemandesCours){
                    max = nbDemandesCours;
                }
                if (min > nbDemandesCours){
                    min = nbDemandesCours;
                }
            } catch (error) {
                console.error("Erreur lors du parsing JSON :", error);
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }
    })
    // Retourner un objet contenant la moyenne, le minimum et le maximum
    return {
        moyenne: somme / nbDemandes,
        min: min !== Infinity ? min : undefined,
        max: max !== -Infinity ? max : undefined
    };
}

function valeurdecroissante(nbDemandes, moyenne, min, max) {
    const coefficient = 2 - ((nbDemandes - min) / (max - min));
    return coefficient < 0 ? 0 : (coefficient > 2 ? 2 : coefficient);
}


function changeJour(event){
    var labelForInput = document.querySelector('label[for="' + event.target.id + '"]');
    if (labelForInput.className === "check"){
        labelForInput.className = "uncheck";
    } else {
        labelForInput.className = "check";
    }
    canDisplayCourses(event);

}

document.addEventListener("click" , function (event) {
    var largeurFenetre = window.innerWidth;
    if (!(event.target.closest("#menuFiltre")) && document.getElementById("menuFiltre") != "none" && !(event.target.closest("#svg_filtre_parent")) && largeurFenetre < 1200){
        closeFiltre();
    }
});

function changeTypeFilter(event){
    var labelForInput = document.querySelector('label[for="' + event.target.id + '"]');
    if (labelForInput.className === "check"){
        labelForInput.className = "uncheck";
    } else {
        labelForInput.className = "check";
    }
    canDisplayCourses(event);
}

function changeSemaine(event){
    var labelForInput = document.querySelector('label[for="' + event.target.id + '"]');
    if (labelForInput.className === "check"){
        labelForInput.className = "uncheck";
    } else {
        labelForInput.className = "check";
    }
    canDisplayCourses(event);
}
function canDisplayCourses(event) {
    var divs_demande = document.getElementsByClassName("div_demande");
    var liste_jours = document.getElementById("jours").getElementsByClassName('check');
    var liste_type = document.getElementById("type").getElementsByClassName("check");
    var heureDebut = document.getElementById("filtre-input-hdebut").value.replace(":","h");
    var heureFin = document.getElementById("filtre-input-hfin").value.replace(":","h");
    var joursActifs = []; // Initialiser une liste pour stocker les jours actifs
    var typeActifs = []; // Initialiser une liste pour stocker les jours actifs
    var display = true;

// Créer un objet pour stocker la correspondance entre les jours et les nombres
    var joursMap = {
        "Lundi": 1,
        "Mardi": 2,
        "Mercredi": 3,
        "Jeudi": 4,
        "Vendredi": 5,
        "Samedi": 6
    };
    for (var i = 0; i < liste_jours.length; i++) {
        var jour = liste_jours[i].innerHTML.trim(); // Récupérer le contenu HTML de l'élément et supprimer les espaces
        joursActifs.push(joursMap[jour]);
    }
    for (var i = 0; i < liste_type.length; i++) {
        var type = liste_type[i].innerHTML.trim(); // Récupérer le contenu HTML de l'élément et supprimer les espaces
        typeActifs.push(type);
    }
    // Créer un nouvel objet URLSearchParams avec la chaîne de requête de l'URL actuelle
    var params = new URLSearchParams(window.location.search);
    var codeUV = params.get('codeUV');
    Array.from(divs_demande).forEach(function(div) {
        // Faites quelque chose avec chaque élément div ici
        var rowAttribute = div.dataset.row;
        if (rowAttribute) {
            try {
                var donnees = JSON.parse(atob(rowAttribute));
            } catch (error) {
                console.error("Erreur lors du parsing JSON :", error);
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }

        if (codeUV != null){
            if (donnees.codeUV.toLowerCase() != input_code_uv.value.toLowerCase()){
                display = false;
            }
        }

        if (!(joursActifs.includes(donnees.jour))){
            display = false;
        }
        if (!(typeActifs.includes(donnees.type))){
            display = false;
        }
        if (!(calculDecimal(heureDebut) <= calculDecimal(donnees.horaireDebut.slice(0,-3).replace(":","h")) && calculDecimal(heureFin) >= calculDecimal(donnees.horaireFin.slice(0,-3).replace(":","h")))){
            display = false;
        }
        if (donnees.semaine === "A" && document.getElementById("semaine-sA").className === "uncheck"){
            display = false;
        } else if (donnees.semaine === "B" && document.getElementById("semaine-sB").className === "uncheck"){
            display = false;
        }
        div.style.display = display ? 'flex' : 'none';
        display = true;
    });

}
function calculDecimal(nombre) {
    var heuresMinutesDebut = nombre.split('h');
    var heuresDebut = parseInt(heuresMinutesDebut[0], 10);
    var minutesDebut = parseInt(heuresMinutesDebut[1], 10);

    return heuresDebut + minutesDebut / 60;
}
function resetFilter(){
    document.getElementById('filterContainer1').innerHTML = "<div class=\"filtre_parent\" id=\"police\">\n" +
        "                        <h1 class=\"filtre_entete\">Trier par</h1>\n" +
        "                        <span class=\"filtre_span\">\n" +
        "                            <h3 class=\"checkElement\" onclick=\"changeFilter(event)\" id=\"mainFilter\">Pertinence</h3>\n" +
        "                            <h3 class=\"uncheckElement\" onclick=\"changeFilter(event))\">Date</h3>\n" +
        "                            <h3 class=\"uncheckElement\" onclick=\"changeFilter(event)\">Demande</h3>\n" +
        "                            <h3 class=\"uncheckElement\" onclick=\"changeFilter(event)\">Auteur</h3>\n" +
        "                        </span>\n" +
        "                    </div>\n" +
        "                    <div class=\"filtre_parent\" id=\"jours\">\n" +
        "                        <h1 class=\"filtre_entete\">Jour</h1>\n" +
        "                        <span class=\"filtre_span\" id=\"spanJour\">\n" +
        "                            <h3 class=\"check\" onclick=\"changeJour(event)\">Lundi</h3>\n" +
        "                            <h3 class=\"check\" onclick=\"changeJour(event)\">Mardi</h3>\n" +
        "                            <h3 class=\"check\" onclick=\"changeJour(event)\">Mercredi</h3>\n" +
        "                            <h3 class=\"check\" onclick=\"changeJour(event)\">Jeudi</h3>\n" +
        "                            <h3 class=\"check\" onclick=\"changeJour(event)\">Vendredi</h3>\n" +
        "                            <h3 class=\"check\" onclick=\"changeJour(event)\">Samedi</h3>\n" +
        "                    </div>\n" +
        "                    <div class=\"filtre_parent\" id=\"type\">\n" +
        "                        <h1 class=\"filtre_entete\">Type</h1>\n" +
        "                        <span class=\"filtre_span\" id=\"spanType\">\n" +
        "                            <h3 class=\"checkType\" onclick=\"changeTypeFilter(event)\">Cours</h3>\n" +
        "                            <h3 class=\"checkType\" onclick=\"changeTypeFilter(event)\">TD</h3>\n" +
        "                            <h3 class=\"checkType\" onclick=\"changeTypeFilter(event)\">TP</h3>\n" +
        "                    </div>\n" +
        "                    <div class=\"filtre_parent\" id=\"jours\">\n" +
        "                        <h1 class=\"filtre_entete\">Semaine</h1>\n" +
        "                        <span class=\"filtre_span\" id=\"spanSemaine\">\n" +
        "                            <h3 id=\"semaine-sA\" class=\"checkSemaine\" onclick=\"changeSemaine(event)\">A</h3>\n" +
        "                            <h3 id=\"semaine-sB\" class=\"checkSemaine\" onclick=\"changeSemaine(event)\">B</h3>\n" +
        "                    </div>\n" +
        "                    <div class=\"filtre_parent\" id=\"heures\">\n" +
        "                        <h1>Horaires</h1>\n" +
        "                        <span class=\"filtre_span\">\n" +
        "                            <div>\n" +
        "                                <input type=\"time\" id=\"filtre-input-hdebut\" name=\"hdebut\" value=\"08:00\" required onchange=\"canDisplayCourses(event)\">\n" +
        "                            </div>\n" +
        "                            <div>\n" +
        "                                <input type=\"time\" id=\"filtre-input-hfin\" name=\"hfin\" value=\"20:00\" required onchange=\"canDisplayCourses(event)\">\n" +
        "                            </div>\n" +
        "                        </span>\n" +
        "                    </div>\n" +
        "                    <div class=\"buttonFiltres\">\n" +
        "                        <button class=\"filtreButton\" id=\"appliquerFiltre\" onclick=\"resetFilter(event)\">Supprimer les filtres</button>\n" +
        "                    </div>";
    var divs_demande = document.getElementsByClassName("div_demande");
    Array.from(divs_demande).forEach(function(div) {
        div.style.display = 'flex';
    });
}

function ouvrirBaseDeDonnees() {
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


var isIn1200Px = true;

function mettreAJourContenuProfil() {
    var largeurFenetre = window.innerWidth;
    
    if(largeurFenetre >= 1200){
        openFiltre();
    }else{
        closeFiltre();
    }
}

// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenuProfil);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenuProfil();

