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

    try {
        var demandeDiv = element.closest('.div_demande');
        // Vérifier si l'élément cliqué est le même que l'élément sur lequel l'événement est attaché
        if (demandeDiv) {
            var rowAttribute = demandeDiv.dataset.row;

            if (rowAttribute) {
                try {
                    var donnees = JSON.parse(atob(rowAttribute));
                } catch (error) {
                    console.error("Erreur lors du parsing JSON :", error);
                }

                var urlParams = new URLSearchParams(window.location.search);
                urlParams.set('codeUV', encodeURIComponent(donnees.codeUV));
                urlParams.set('type', encodeURIComponent(donnees.type));
                urlParams.set('hDebut', encodeURIComponent(donnees.horaireDebut.substring(0,5)));
                urlParams.set('hFin', encodeURIComponent(donnees.horaireFin.substring(0,5)));
                urlParams.set('jour', encodeURIComponent(donnees.jour));
                var lien = "?" + urlParams.toString();

                // Créer un élément textarea temporaire pour copier le texte dans le presse-papiers
                var textarea = document.createElement('textarea');
                textarea.value = window.location.origin + window.location.pathname + lien;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                // Afficher un message ou effectuer d'autres actions si nécessaire
                alert("Le lien a été copié dans le presse-papiers !");
                event.stopPropagation();

            } else {
                console.error("Aucune donnée trouvée dans l'attribut data-row");
            }

        }

    } catch (error) {
        console.error(error);
    }

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
                        checkbox_semaine.disabled = true;
                    } else {
                        checkbox_semaine.disabled = false;
                        checkbox_semaine.click();
                        checkbox_semaine.checked = true;
                        localStorage.setItem("semaine" , donnees.semaine);
                    }

                    const db = await ouvrirBaseDeDonnees();
                    // Récupérer tous les cours de la base de données
                    const tousLesCours = await getAllCours(db);
                    tousLesCours.forEach(cours => {
                        console.log(cours);
                        if (cours.type === donnees.type && cours.codeUV === donnees.codeUV){
                            input_salle.value = cours.salle;
                            input_creneau.value = cours.jour.toLowerCase();
                            const [heuresDebut, minutesDebut] = cours.horaireDebut.split('h');
                            input_hdebut.value = `${heuresDebut.padStart(2,'0')}:${minutesDebut}`;
                            const [heuresFin, minutesFin] = cours.horaireFin.split('h');
                            input_hfin.value = `${heuresFin.padStart(2,'0')}:${minutesFin}`;
                            if (cours.semaine !== null){
                                checkbox_semaine = document.getElementById("input-semaine");
                                checkbox_semaine.checked = true;
                                document.getElementById("choix-semaine").className = "nouveau_pannel_checkbox";//modif
                                if (cours.semaine === "A"){
                                    document.getElementById("sA-choix").checked = true;
                                } else if(cours.semaine === "B") {
                                    document.getElementById("sB-choix").checked = true;
                                }
                            } else {
                                checkbox_semaine.checked = false;
                                document.getElementById("choix-semaine").className = "nouveau_pannel_checkbox hidden hidden";
                            }
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
    if (input_code_uv.value.length === 0){
        var divs_demande = document.getElementsByClassName("div_demande");
        Array.from(divs_demande).forEach(function(div) {
            div.style.display = "flex";
        });
        history.pushState({}, document.title, window.location.pathname);
        canDisplayCourses(event);
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
    const params = new URLSearchParams(window.location.search);
    var filter = "";

    if (document.getElementById("filtre_pertinence").checked){
        filter = "pertinence";
        handlechangeFilter();
    } else if (document.getElementById("filtre_date").checked){
        filter = "date";
        sortBySchedule();
    } else if (document.getElementById("filtre_demande").checked){
        filter = "demande";
        sortByDemand();
    } else if (document.getElementById("filtre_auteur").checked) {
        filter = "auteur";
        sortByAuthor();
    } else {
        filter = "recent";
        sortByRecent();
    }
    afficherPage(currentPage);
    if (params.has("filtre")){
        params.delete("filtre");
    }
    params.append("filtre" , filter);
    history.replaceState({}, '', window.location.pathname + '?' + params.toString());
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
function inverserOrdre() {
    const container = document.querySelector('.demande_container');
    const elements = Array.from(container.children);
    const reversedElements = elements.reverse();
    reversedElements.forEach(element => container.appendChild(element));
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
    if (!(event.target.closest("#menuFiltre")) && document.getElementById("menuFiltre") != "none" && !(event.target.closest(".svgFiltre")) && largeurFenetre < 1200){
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

function canDisplayCours(div) {
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
        if (donnees.codeUV.toLowerCase() != codeUV.toLowerCase()){
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
    return display;
}
function canDisplayCourses(event) {
    var params = new URLSearchParams();
    var divs_demande = document.getElementsByClassName("div_demande");
    var liste_jours = document.getElementById("jours").getElementsByClassName('check');
    var liste_type = document.getElementById("type").getElementsByClassName("check");
    var heureDebut = document.getElementById("filtre-input-hdebut").value;
    var heureFin = document.getElementById("filtre-input-hfin").value;
    params.append('hDebut' , encodeURIComponent(heureDebut));
    params.append('hFin' , encodeURIComponent(heureFin));
    heureDebut = heureDebut.replace(":","h");
    heureFin = heureFin.replace(":","h");
    var joursActifs = []; // Initialiser une liste pour stocker les jours actifs
    var typeActifs = []; // Initialiser une liste pour stocker les jours actifs
    var display = true;
    params.append("A" , document.getElementById("semaine-sA").className === "check");
    params.append("B" , document.getElementById("semaine-sB").className === "check");
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
    params.append('jour' , JSON.stringify(joursActifs));
    for (var i = 0; i < liste_type.length; i++) {
        var type = liste_type[i].innerHTML.trim(); // Récupérer le contenu HTML de l'élément et supprimer les espaces
        typeActifs.push(type);
    }
    params.append('type' , JSON.stringify(typeActifs));
    // Créer un nouvel objet URLSearchParams avec la chaîne de requête de l'URL actuelle
    var currentParams = new URLSearchParams(window.location.search);
    var codeUV = currentParams.get('codeUV');
    if (codeUV != null) params.append('codeUV' , codeUV);
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
            if (donnees.codeUV.toLowerCase() != codeUV.toLowerCase()){
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
    history.replaceState({}, '', window.location.pathname + '?' + params.toString());
    afficherPage(currentPage);
}
function calculDecimal(nombre) {
    var heuresMinutesDebut = nombre.split('h');
    var heuresDebut = parseInt(heuresMinutesDebut[0], 10);
    var minutesDebut = parseInt(heuresMinutesDebut[1], 10);

    return heuresDebut + minutesDebut / 60;
}
function resetFilter(){
    document.getElementById('filterContainer1').innerHTML = "                    <div class=\"filtre_parent\" id=\"filter\">\n" +
        "                        <span class=\"alignTrier\">\n" +
        "                            <h1 class=\"filtre_entete\">Trier par</h1>\n" +
        "                            <img id=\"trierDecroissant\" src=\"../svg/filter_decroissant.png\" alt=\"\" onclick=\"inverserOrdre()\">\n" +
        "                        </span>\n" +
        "                        <span class=\"filtre_span\">\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\"  for=\"filtre_recent\" id=\"mainFilter\" >Récent</label><input type=\"radio\" name=\"filterBy\" id=\"filtre_recent\" onclick=\"changeFilter(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"uncheck\" for=\"filtre_pertinence\">Pertinence</label><input type=\"radio\" name=\"filterBy\" id=\"filtre_pertinence\" onclick=\"changeFilter(event)\"></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"uncheck\" for=\"filtre_date\">Date</label><input type=\"radio\" name=\"filterBy\" id=\"filtre_date\" onclick=\"changeFilter(event)\"></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"uncheck\" for=\"filtre_demande\">Demande</label><input type=\"radio\" name=\"filterBy\" id=\"filtre_demande\" onclick=\"changeFilter(event)\"></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"uncheck\" for=\"filtre_auteur\">Auteur</label><input type=\"radio\" name=\"filterBy\" id=\"filtre_auteur\" onclick=\"changeFilter(event)\"></div>\n" +
        "                        </span>\n" +
        "                    </div>\n" +
        "                    <div class=\"filtre_parent\" id=\"jours\">\n" +
        "                        <h1 class=\"filtre_entete\">Jour</h1>\n" +
        "                        <span class=\"filtre_span\" id=\"spanJour\">\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_lundi\">Lundi</label><input type=\"checkbox_semaine\" id=\"filtre_lundi\" onclick=\"changeJour(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_mardi\">Mardi</label><input type=\"checkbox_semaine\" id=\"filtre_mardi\" onclick=\"changeJour(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_mercredi\">Mercredi</label><input type=\"checkbox_semaine\" id=\"filtre_mercredi\" onclick=\"changeJour(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_jeudi\">Jeudi</label><input type=\"checkbox_semaine\" id=\"filtre_jeudi\" onclick=\"changeJour(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_vendredi\">Vendredi</label><input type=\"checkbox_semaine\" id=\"filtre_vendredi\" onclick=\"changeJour(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_samedi\">Samedi</label><input type=\"checkbox_semaine\" id=\"filtre_samedi\" onclick=\"changeJour(event)\" checked></div>\n" +
        "                    </div>\n" +
        "                    <div class=\"filtre_parent\" id=\"type\">\n" +
        "                        <h1 class=\"filtre_entete\">Type</h1>\n" +
        "                        <span class=\"filtre_span\" id=\"spanType\">\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_CM\">CM</label><input type=\"checkbox_semaine\" id=\"filtre_CM\" onclick=\"changeTypeFilter(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_TD\">TD</label><input type=\"checkbox_semaine\" id=\"filtre_TD\" onclick=\"changeTypeFilter(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label class=\"check\" for=\"filtre_TP\">TP</label><input type=\"checkbox_semaine\" id=\"filtre_TP\" onclick=\"changeTypeFilter(event)\" checked></div>\n" +
        "                    </div>\n" +
        "                    <div class=\"filtre_parent\" id=\"jours\">\n" +
        "                        <h1 class=\"filtre_entete\">Semaine</h1>\n" +
        "                        <span class=\"filtre_span\" id=\"spanSemaine\">\n" +
        "                            <div class=\"filtre_parent_label\"><label id=\"semaine-sA\" class=\"check\"  for=\"filtre_sA\">Semaine A</label><input type=\"checkbox_semaine\" id=\"filtre_sA\" onclick=\"changeSemaine(event)\" checked></div>\n" +
        "                            <div class=\"filtre_parent_label\"><label id=\"semaine-sB\" class=\"check\" for=\"filtre_sB\">Semaine B</label><input type=\"checkbox_semaine\" id=\"filtre_sB\"  onclick=\"changeSemaine(event)\" checked></div>\n" +
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
        "                    </div>\n" +
        "                </div>";
    var divs_demande = document.getElementsByClassName("div_demande");
    Array.from(divs_demande).forEach(function(div) {
        div.style.display = 'flex';
    });
    window.history.replaceState({}, document.title, window.location.pathname);
    afficherPage(currentPage);
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

/* Système de pagination */

// Variables globales
const divDemandeContainer = document.querySelector('.demande_container');
const itemsPerPage = calculateNumberOfElements(window.innerHeight , 13) - 1; // Nombre d'éléments à afficher par page 68px hauteur une div demande
let currentPage = 1;
var spanNumberPage = document.getElementById("pageList");

function calculateNumberOfElements(viewportHeight, headerHeight) {
    var headerPixels = (headerHeight * viewportHeight) / 100;

    // Calculer la hauteur disponible en pixels
    var availableHeight = viewportHeight - headerPixels;

    // Diviser la hauteur disponible par la hauteur d'un élément individuel (68px)
    var numberOfElements = Math.floor(availableHeight / 60);

    return numberOfElements;
}


function afficherPage(page) {
    var divDemandeElements = Array.from(divDemandeContainer.querySelectorAll('.div_demande'));
    // Calculer les indices de début et de fin pour les éléments de la page
    const startIndex = (page - 1) * itemsPerPage;
    const endIndex = page * itemsPerPage;

    // Masquer tous les éléments de div_demande
    divDemandeElements.forEach(element => {
        element.style.display = 'none';
    });

    let count = 0;
    for (let i = 0; i < divDemandeElements.length; i++) {
        const element = divDemandeElements[i];
        if (canDisplayCours(element)) {
            if (count >= startIndex && count < endIndex) {
                element.style.display = 'flex';
            }
            count++;
        }
    }
    spanNumberPage.innerHTML = "";
    var totalPages = Math.ceil(getVisibleElementCount() / itemsPerPage); // Nombre total de pages nécessaires

    var startPage;
    startPage = currentPage - 2 < 1 ? 1 : currentPage - 2;
    if (totalPages <= 5) {
        startPage = 1; // Si le nombre total de pages est inférieur ou égal à 5, commencer à partir de la première page
    }
    else if (currentPage + 2 >= totalPages) {
        startPage = totalPages - 4; // Si le nombre total de pages est supérieur à 5 et si currentPage est suffisamment proche de la fin, ajuster startPage
    }

    else if (currentPage > 3 && totalPages > 5) {
        startPage = currentPage - 2; // Si le nombre total de pages est supérieur à 5 et si currentPage n'est pas trop proche du début, ajuster startPage pour centrer la page courante
    }

    else if (totalPages - startPage < 4) {
        startPage = totalPages - 4; // Si le nombre total de pages est supérieur à 5 et si startPage est trop loin de la fin, l'ajuster pour garantir qu'il y ait au moins 5 pages affichées
    }

    // Limitez la boucle à 5 pages
    var endPage = Math.min(startPage + 4, totalPages);
    for (let i = startPage; i <= endPage; i++) {
        var numberPage = document.createElement("h3");
        numberPage.innerHTML = i;
        numberPage.className = "pageClick";
        if (i === currentPage) {
            numberPage.id = "currentPage";
        } else {
            numberPage.id = "justPage";
        }
        numberPage.addEventListener("click", function() {
            currentPage = i;
            afficherPage(i);
        });
        spanNumberPage.appendChild(numberPage);
    }

}

function firstPage(){
    currentPage = 1;
    afficherPage(currentPage)
}

function lastPage(){
    currentPage = Math.ceil(getVisibleElementCount() / itemsPerPage);
    afficherPage(currentPage);
}

// Fonction pour passer à la page suivante
function nextPage() {
    if (currentPage < Math.ceil(getVisibleElementCount() / itemsPerPage)) {
        currentPage++;
        afficherPage(currentPage);
    }
}

// Fonction pour revenir à la page précédente
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        afficherPage(currentPage);
    }
}

// Fonction pour obtenir le nombre d'éléments visibles qui correspondent aux critères de filtrage
function getVisibleElementCount() {
    var divDemandeElements = Array.from(divDemandeContainer.querySelectorAll('.div_demande'));
    let count = 0;
    divDemandeElements.forEach(element => {
        if (canDisplayCours(element)) {
            count++;
        }
    });
    return count;
}

// Afficher la première page au chargement de la page
window.addEventListener('load', () => {
    appliquerFiltres();
    afficherPage(currentPage);
});

// Attacher la fonction au changement de taille de la fenêtre
window.addEventListener('resize', mettreAJourContenuProfil);

// Appeler la fonction une fois au chargement de la page
mettreAJourContenuProfil();

//Appliquer les paramètres utilisateur des filtres
function appliquerFiltres() {
    var joursMap = {
        "lundi": 1,
        "mardi": 2,
        "mercredi": 3,
        "jeudi": 4,
        "vendredi": 5,
        "samedi": 6
    };
    const params = new URLSearchParams(window.location.search);
    if (params.has('jour')){
        const jours = params.get('jour');
        Array.from(document.querySelectorAll("#spanJour input")).forEach(labelJour => {
            if (!(jours.includes(joursMap[labelJour.id.slice(7)]))){
                labelJour.click();
            }
        });
    }
    if (params.has("type")){
        const types = params.get('type');
        Array.from(document.querySelectorAll("#spanType input")).forEach(labelType => {
            if (!(types.includes(labelType.id.slice(7).toUpperCase()))){
                labelType.click();
            }
        });
    }
    if (params.has("hDebut")){
        document.getElementById("filtre-input-hdebut").value = decodeURIComponent(params.get("hDebut"));
    }
    if (params.has("hFin")){
        document.getElementById("filtre-input-hfin").value = decodeURIComponent(params.get("hFin"));
    }

    if (params.has("A")){
        if (params.get("A") == 'false'){
            document.getElementById("filtre_sA").click();
        }
    }
    if (params.has("B")){
        if (params.get("B") == 'false'){
            document.getElementById("filtre_sB").click();
        }
    }

    if (params.has("filtre")){
        var filtre = params.get("filtre");
        document.getElementById(`filtre_${filtre}`).click();

    }

}

// Écouteur d'événements pour les changements de filtres
document.addEventListener('change', function(event) {
    const element = event.target;
    if (element.classList.contains('filtre')) {
        appliquerFiltres();
    }
});