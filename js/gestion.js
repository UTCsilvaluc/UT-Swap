var isSelectionning = false;
var button_selection = document.getElementById("button_selection");
var button_accept_all = document.getElementById("button_accept_all");
var button_decline_all = document.getElementById("button_decline_all");
var icon_select = document.getElementById("icon_select");

button_selection.addEventListener("click", function() {
    isSelectionning = !isSelectionning;
    Array.from(document.getElementsByClassName("demande_professeur")).forEach(element => {
        element.classList.toggle("demande_selected", false);
    });
    button_accept_all.classList.toggle('hidden' , !isSelectionning);
    button_decline_all.classList.toggle('hidden' , !isSelectionning);
    button_selection.classList.toggle('button_filtre_selected' , isSelectionning);
    if(isSelectionning){
        icon_select.innerHTML = "toggle_on";
    }else{
        icon_select.innerHTML = "toggle_off";
    }

});

function openFiltre(event){
    var conteneurFiltre = document.getElementById("menuFiltre");
    conteneurFiltre.style.display = "flex";
}

function closeFiltre(event){
    var conteneurFiltre = document.getElementById("menuFiltre");
    conteneurFiltre.style.display = "none";
}

document.addEventListener("click" , function (event) {
    var largeurFenetre = window.innerWidth;
    if (!(event.target.closest("#menuFiltre")) && document.getElementById("menuFiltre") != "none" && !(event.target.closest(".svgFiltre")) && largeurFenetre < 1200){
        closeFiltre();
    }
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

function addInputForm(form, choix, idDemande, demandeur, id){

    var inputChoix = document.createElement('input');
    inputChoix.setAttribute('type', 'hidden');
    inputChoix.setAttribute('name', 'choix' + id);
    inputChoix.setAttribute('value', choix);
    form.appendChild(inputChoix);

    var inputDemandeur = document.createElement('input');
    inputDemandeur.setAttribute('type', 'hidden');
    inputDemandeur.setAttribute('name', 'demandeur' + id);
    inputDemandeur.setAttribute('value', demandeur);
    form.appendChild(inputDemandeur);

    var inputIdDemande = document.createElement('input');
    inputIdDemande.setAttribute('type', 'hidden');
    inputIdDemande.setAttribute('name', 'idDemande' + id);
    inputIdDemande.setAttribute('value', idDemande);
    form.appendChild(inputIdDemande);

}

function choixProfesseurSwap(choix, element){
    event.stopPropagation();
    var clickedElement = element.target;

    // Vérifier si l'élément cliqué est le même que l'élément sur lequel l'événement est attaché
    if (clickedElement === element.currentTarget) {
        var form = document.createElement('form');
        form.setAttribute('method', 'post');
        form.setAttribute('action', '');

        var demandes_selected = document.getElementsByClassName("demande_selected");
        var i;
        if(element.id.includes("all")){
            i = demandes_selected.length;
        }else{
            i=1;
        }
        if(choix === true){
            choix = 1;
        }else{
            choix = 0;
        }
        for(var j = 1; j<=i; j++){

            var rowAttribute;
            if(element.id.includes("all")){
                rowAttribute = demandes_selected[j-1].dataset.row;
            }else{
                rowAttribute = element.parentElement.parentElement.dataset.row;
            }

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
                if(element.id.includes("all")){
                    addInputForm(form, choix, donnees.idDemande, donnees.demandeur, j);
                }else{
                    addInputForm(form, choix, donnees.idDemande, donnees.demandeur, 0);
                }
            }
        }

        // Ajouter le formulaire à la page
        document.body.appendChild(form);

        // Soumettre le formulaire
        form.submit();

        // Supprimer le formulaire après soumission
        form.remove();
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

    event.preventDefault();
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
        if(getGetValue("codeUV") === null){
            checkboxChangementGlobal.checked = !checkboxChangementGlobal.checked;
        }
        history.replaceState({}, document.title, window.location.pathname);
        
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
var boutonContainer = document.getElementById("demandes_professeur_bouton");
var contenuContainer = document.getElementById("demandes_professeur_content");
var demandeProfesseurs = document.querySelectorAll('.demande_professeur');
var button_compat = document.getElementById("button_compat");

function mettreAJourContenu() {
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

    if(largeurFenetre <= 900){
        button_accept_all.innerHTML = '<img src="../svg/Vector_check_black.svg" alt="">';
        button_decline_all.innerHTML = '<img src="../svg/Vector_none_black.svg" alt="">';
    }else{
        button_accept_all.innerHTML = '<img src="../svg/Vector_check_black.svg" alt="">Accepter';
        button_decline_all.innerHTML = '<img src="../svg/Vector_none_black.svg" alt="">Refuser';
    }

    if(largeurFenetre <= 800){
        choix_uv_label.innerHTML = "Autoriser changements UV(s)";
    }else{
        choix_uv_label.innerHTML = "Autoriser les changements de groupe au sein de mes UV(s)";
    }


    if (largeurFenetre <= 600) {
        button_compat.innerHTML = "Compatibilité";
        contenuContainer.parentNode.insertBefore(boutonContainer, contenuContainer.nextSibling);
    } else {
        button_compat.innerHTML = "Test compatibilité";
        var parent = document.getElementById("demandes_professeur_header");
        parent.insertBefore(boutonContainer, parent.children[1]);
    }
}

window.addEventListener('resize', mettreAJourContenu);
mettreAJourContenu();

function canDisplayRequest(event) {
    var divs_demande = document.getElementsByClassName("demande_professeur");
    var liste_uvs = document.getElementById("spanUV").getElementsByClassName('uvCheck');
    var liste_type = document.getElementById("spanType").getElementsByClassName("typeCheck");
    var liste_state = document.getElementById("spanState").getElementsByClassName("stateCheck");
    var stateToStr = {2:"En attente" , 3:"Refusée" , 4:"Acceptée"};
    var UvsActifs = [];
    var typeActifs = [];
    var stateActifs = [];
    var display = true;
    for (var i = 0; i < liste_type.length; i++) {
        var type = liste_type[i].innerHTML.trim();
        typeActifs.push(type);
    }
    for (var i = 0; i < liste_uvs.length; i++) {
        var uv = liste_uvs[i].innerHTML.trim();
        UvsActifs.push(uv);
    }
    for (var i = 0; i < liste_state.length; i++) {
        var state = liste_state[i].innerHTML.trim();
        stateActifs.push(state);
    }
    Array.from(divs_demande).forEach(function(div) {
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
        if (!(UvsActifs.includes(donnees.codeUV))){
            display = false;
        }
        if (!(typeActifs.includes(donnees.type))){
            display = false;
        }
        if (!(stateActifs.includes(stateToStr[donnees.statut]))){
            display = false;
        }
        if ((donnees.fil1 === "TC" || donnees.fil2 === "TC") && document.getElementById("filiere_tc").className === "filunCheck"){
            display = false;
        }
        if ((donnees.fil1 === "BR" || donnees.fil2 === "BR") && document.getElementById("filiere_br").className === "filunCheck"){
            display = false;

        }

        div.style.display = display ? 'flex' : 'none';
        display = true;
    });
}

function changeType(event){
    var target = event.target;
    if (target.className === "typeCheck"){
        target.className = "typeunCheck";
    } else {
        target.className = "typeCheck";
    }
    canDisplayRequest(event);
}

function changeFil(event){
    var target = event.target;
    if (target.className === "filCheck"){
        target.className = "filunCheck";
    } else {
        target.className = "filCheck";
    }
    canDisplayRequest(event);
}

function changeState(event){
    var target = event.target;
    if (target.className === "stateCheck"){
        target.className = "stateunCheck";
    } else {
        target.className = "stateCheck";
    }
    canDisplayRequest(event);
}

function changeUV(event){
    var target = event.target;
    if (target.className === "uvCheck"){
        target.className = "uvUnCheck";
    } else {
        target.className = "uvCheck";
    }
    canDisplayRequest(event);
}

var import_prof = document.getElementById("import_prof");
var import_mail = document.getElementById("import_mail");
var import_personne_fraud_parent = document.getElementById("import_personne_fraud_parent");
async function openImport() {
    import_prof.classList.toggle('hidden', false);
    import_mail.classList.toggle('hidden', false);
    import_personne_fraud_parent.classList.toggle('hidden', true);
    try {
        const emailContent = await getMailBDD();
        if (emailContent) {
            document.getElementById("textUV").value = emailContent;
            changeImport(document.getElementById("textUV"));
        }
    } catch (error) {
        console.error("Une erreur s'est produite" , error);
    }
}

document.addEventListener("click" , function (event) {

    if (!(event.target.closest("#import_prof")) && !document.getElementById("import_prof").className.includes("hidden") && event.target.id != "button_import"){
        import_prof.classList.toggle('hidden', true);
    }
});

const regexEtudiant = /^\s*\d{3}\s+([\w\s-]+)\s+(\w{4})\s*$/;
const regexCours = /^\s*(\w{4})\s+([\w\s]{3,5})\s*,\s*PL\.MAX=\s*(\d+)\s*,\s*LIBRES=\s*(\d+)\s*,\s*INSCRITS=\s*(\d+)\s*H=(\w+)(\.\.\.)?\s*$/;
const regexHoraireSalle = /(\d{2}:\d{2}-\d{2}:\d{2}),(\w+),S=(\w+)/;
var etudiantsDict = {};
let tousLesCours = [];

async function changeImport(element) {
    var texte = element.value;
    await saveMailBDD(texte);
    etudiantsDict = {};
    tousLesCours = [];

    // Séparer les textes de cours en utilisant la séquence "+----------"
    var cours = texte.split('+----------');

    // Réinitialiser la liste des cours
    tousLesCours = [];

    // Afficher les textes de cours séparés
    for (let i = 0; i < cours.length; i++) {
        const lines = cours[i].trim().split('\n');
        let coursInfo = null;
        let etudiants = [];

        // Parcourir chaque ligne du texte de cours
        for (let j = 0; j < lines.length; j++) {
            const ligne = lines[j].trim();

            // Vérifier si la ligne correspond à un étudiant
            if (regexEtudiant.test(ligne)) {
                const [, nom, branche] = ligne.match(regexEtudiant);
                const nomSansEspaces = nom.trim();
                etudiants.push({ nom: nomSansEspaces, branche });
            } else if (regexCours.test(ligne)) {
                // Si la ligne correspond aux informations du cours
                const match = ligne.match(regexCours);
                let detailsCours;
                let semaine;
                if (match[2].includes('D')) {
                    detailsCours = 'TD';
                    semaine = null;
                } else if (match[2].includes('C')) {
                    detailsCours = 'CM';
                    semaine = null;
                } else if (match[2].includes('T')) {
                    detailsCours = 'TP';
                    if (match[2].includes('A')){
                        semaine = "A";
                    }else if(match[2].includes('B')){
                        semaine = "B";
                    }else{
                        semaine = null;
                    }
                } else {
                    detailsCours = null;
                    semaine = null;
                }
                coursInfo = {
                    codeCours: match[1],
                    type: detailsCours,
                    semaine: semaine,
                    plMax: parseInt(match[3]),
                    plLibre: parseInt(match[4]),
                    inscrit: parseInt(match[5]),
                    num: match[2].split(" ")[1],
                    jourSemaine: match[6]
                };

                // Ajouter à la liste des cours
                tousLesCours.push({ codeCours: match[1], type: detailsCours, semaine: semaine });
            } else if (regexHoraireSalle.test(ligne)) {
                // Si la ligne correspond à l'horaire et à la salle du cours
                const [, horaire, , salle] = ligne.match(regexHoraireSalle);
                coursInfo.horaire = horaire;
                coursInfo.salle = salle;
            }
        }

        // Ajouter les étudiants au cours correspondant
        if (coursInfo) {
            // Mettre à jour le cours pour chaque étudiant
            etudiants.forEach(etudiant => {
                const key = `${coursInfo.codeCours}-${coursInfo.type}-${coursInfo.jourSemaine}-${coursInfo.horaire}-${coursInfo.salle}`;
                if (!etudiantsDict[etudiant.nom]) {
                    etudiantsDict[etudiant.nom] = { ...etudiant, cours: [] };
                }
                etudiantsDict[etudiant.nom].cours.push({ ...coursInfo });
            });
        }
    }
}

function trouverCours(heureDebut, heureFin, jour, type, semaine, codeCours, nom, prenom, branche) {
    // Remplacer "h" par ":" dans les heures
    heureDebut = heureDebut.replace("h", ":");
    heureFin = heureFin.replace("h", ":");

    const nomComplet = `${nom.toUpperCase()} ${prenom.toUpperCase()}`;
    const etudiantTrouve = etudiantsDict[nomComplet];

    const coursExistant = tousLesCours.some(cours => cours.codeCours === codeCours && cours.type === type && String(cours.semaine) === semaine);
    
    
    // Si l'étudiant est trouvé, vérifier ses cours
    if (etudiantTrouve && etudiantTrouve.branche.substring(0, 2).toUpperCase() === branche.substring(0, 2).toUpperCase() && coursExistant) {
        const coursTrouve = etudiantTrouve.cours.find(cours => {
            return (
                cours.type === type &&
                cours.codeCours === codeCours &&
                cours.jourSemaine === jour.toUpperCase() &&
                cours.horaire === `${heureDebut}-${heureFin}`
            );
        });
        if (coursTrouve) {
            return true; // L'étudiant est trouvé et inscrit au cours
        } else {
            return false; // Incertitude : le cours existe mais l'étudiant n'y est peut-être pas inscrit
        }
    }

    if (coursExistant) {
        return false; // L'étudiant est trouvé et inscrit au cours
    } else {
        return null; // Incertitude : le cours existe mais l'étudiant n'y est peut-être pas inscrit
    }
}


function checkSwap() {
    changeImport(document.getElementById("textUV"));
    var listeDemande = document.getElementsByClassName("demande_professeur");
    var prenomsFrauduleux = [];
    
    // Vider le contenu de l'élément mid_content
    import_personne_fraud_parent.innerHTML = '';

    for (let demande of listeDemande) {
        demande.getElementsByClassName("infos_swap")[0].style.color = "black";
        var rowAttribute = demande.dataset.row;

        if (rowAttribute) {
            try {
                var donnees = JSON.parse(atob(rowAttribute));
            } catch (error) {
                console.error("Erreur lors du parsing JSON :", error);
            }

            if (donnees.etudiant1 && donnees.etudiant1.heureDebut && donnees.etudiant1.heureFin && donnees.etudiant1.jour && donnees.type && donnees.codeUV && donnees.etudiant1.nom && donnees.etudiant1.prenom && donnees.etudiant1.branche) {
                var trouverCoursEtu = trouverCours(donnees.etudiant1.heureDebut, donnees.etudiant1.heureFin, donnees.etudiant1.jour, donnees.type, donnees.etudiant1.semaine, donnees.codeUV, donnees.etudiant1.nom, donnees.etudiant1.prenom, donnees.etudiant1.branche);
                if (trouverCoursEtu == false && trouverCoursEtu != null) {
                    shakeElement(demande);
                    demande.getElementsByClassName("infos_swap")[0].style.color = "red";
                    var prenomNom = donnees.etudiant1.prenom + ' ' + donnees.etudiant1.nom;
                    if (!prenomsFrauduleux.includes(prenomNom)) {
                        prenomsFrauduleux.push(prenomNom);
                    }
                }
            }
            if (donnees.etudiant2 && donnees.etudiant2.heureDebut && donnees.etudiant2.heureFin && donnees.etudiant2.jour && donnees.type && donnees.codeUV && donnees.etudiant2.nom && donnees.etudiant2.prenom && donnees.etudiant2.branche) {
                var trouverCoursEtu =trouverCours(donnees.etudiant2.heureDebut, donnees.etudiant2.heureFin, donnees.etudiant2.jour, donnees.type, donnees.etudiant2.semaine, donnees.codeUV, donnees.etudiant2.nom, donnees.etudiant2.prenom, donnees.etudiant2.branche);
                if (trouverCoursEtu == false && trouverCoursEtu != null) {
                    shakeElement(demande);
                    demande.getElementsByClassName("infos_swap")[1].style.color = "red";
                    var prenomNom = donnees.etudiant2.prenom + ' ' + donnees.etudiant2.nom;
                    if (!prenomsFrauduleux.includes(prenomNom)) {
                        prenomsFrauduleux.push(prenomNom);
                    }
                }
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }
    }

    import_personne_fraud_parent.innerHTML = '<h1 id="import_personne_fraud_title">Il y a ' + prenomsFrauduleux.length + ' personnes suspectes:</h1>';

    var frauduleuxListe = document.createElement('div');
    frauduleuxListe.id = 'import_personne_fraud_container';
    for (var i = 0; i < prenomsFrauduleux.length; i++) {
        var div = document.createElement('div');
        div.className = 'import_personne_fraud';
        div.textContent = prenomsFrauduleux[i];
        frauduleuxListe.appendChild(div);
    }

    import_personne_fraud_parent.appendChild(frauduleuxListe);

    // Ajouter le contenu généré à l'élément mid_content
    import_mail.classList.toggle('hidden', true);
    import_personne_fraud_parent.classList.toggle('hidden', false);
}

function deplacerEtudiant(nom1, prenom1, branche1, nom2, prenom2, branche2, type1, semaine1, codeCours1, jour1, horaireDebut1, horaireFin1, type2, semaine2, codeCours2, jour2, horaireDebut2, horaireFin2) {
    const nomComplet1 = `${nom1.toUpperCase()} ${prenom1.toUpperCase()}`;
    const nomComplet2 = `${nom2.toUpperCase()} ${prenom2.toUpperCase()}`;

    // Rechercher les étudiants 1 et 2 dans le dictionnaire
    const etudiant1 = etudiantsDict[nomComplet1];
    const etudiant2 = etudiantsDict[nomComplet2];

    if (etudiant1 && etudiant2 && etudiant1.branche.substring(0, 2).toUpperCase() === branche1.substring(0, 2).toUpperCase() && etudiant2.branche.substring(0, 2).toUpperCase() === branche2.substring(0, 2).toUpperCase()) {
        // Rechercher le cours correspondant à l'étudiant 1
        const coursEtudiant1 = etudiant1.cours.find(cours => cours.codeCours === codeCours1 && cours.type === type1 && String(cours.semaine) === semaine1 && cours.jourSemaine === jour1.toUpperCase() && cours.horaire === `${horaireDebut1.replace("h", ":")}-${horaireFin1.replace("h", ":")}`);

        // Rechercher le cours correspondant à l'étudiant 2
        const coursEtudiant2 = etudiant2.cours.find(cours => cours.codeCours === codeCours2 && cours.type === type2 && String(cours.semaine) === semaine2 && cours.jourSemaine === jour2.toUpperCase() && cours.horaire === `${horaireDebut2.replace("h", ":")}-${horaireFin2.replace("h", ":")}`);

        if (coursEtudiant1 && coursEtudiant2) {
            // Conserver les valeurs de plMax et inscrit pour chaque cours
            const plMax1 = coursEtudiant1.plMax;
            const plLibre1 = coursEtudiant1.plLibre;
            const inscrit1 = coursEtudiant1.inscrit;
            const num1 = coursEtudiant1.num;

            const plMax2 = coursEtudiant2.plMax;
            const plLibre2 = coursEtudiant2.plLibre;
            const inscrit2 = coursEtudiant2.inscrit;
            const num2 = coursEtudiant2.num;

            // Échanger les cours entre les étudiants
            etudiant1.cours.splice(etudiant1.cours.indexOf(coursEtudiant1), 1, {
                codeCours: codeCours2,
                type: type2,
                semaine: semaine2,
                jourSemaine: jour2.toUpperCase(),
                horaire: `${horaireDebut2.replace("h", ":")}-${horaireFin2.replace("h", ":")}`,
                salle: coursEtudiant2.salle,
                plMax: plMax2,
                plLibre: plLibre2,
                inscrit: inscrit2,
                num : num2
            });
            etudiant2.cours.splice(etudiant2.cours.indexOf(coursEtudiant2), 1, {
                codeCours: codeCours1,
                type: type1,
                semaine: semaine1,
                jourSemaine: jour1.toUpperCase(),
                horaire: `${horaireDebut1.replace("h", ":")}-${horaireFin1.replace("h", ":")}`,
                salle: coursEtudiant1.salle,
                plMax: plMax1,
                plLibre: plLibre1,
                inscrit: inscrit1,
                num : num1
            });
            return;
        } else {
            console.error(`Aucun cours trouvé pour les étudiants ${nomComplet1} (${branche1}) ou ${nomComplet2} (${branche2}) correspondant aux informations fournies.`);
        }
    } else {
        console.error(`Étudiant introuvable ou données d'inscription incorrectes.`);
    }
}


function updateMail(){
    var listeDemande = document.getElementsByClassName("demande_professeur");
    // Vider le contenu de l'élément mid_content
    import_personne_fraud_parent.innerHTML = '';

    for (let demande of listeDemande) {
        var rowAttribute = demande.dataset.row;

        if (rowAttribute) {
            try {
                var donnees = JSON.parse(atob(rowAttribute));
            } catch (error) {
                console.error("Erreur lors du parsing JSON :", error);
            }

            if (donnees.statut !== "" && donnees.statut == 4) {
                if (donnees.etudiant1 && donnees.etudiant1.heureDebut && donnees.etudiant1.heureFin && donnees.etudiant1.jour && donnees.type && donnees.codeUV && donnees.etudiant1.nom && donnees.etudiant1.prenom && donnees.etudiant1.branche && 
                    donnees.etudiant2 && donnees.etudiant2.heureDebut && donnees.etudiant2.heureFin && donnees.etudiant2.jour && donnees.type && donnees.codeUV && donnees.etudiant2.nom && donnees.etudiant2.prenom && donnees.etudiant2.branche &&
                    trouverCours(donnees.etudiant1.heureDebut, donnees.etudiant1.heureFin, donnees.etudiant1.jour, donnees.type, donnees.etudiant1.semaine, donnees.codeUV, donnees.etudiant1.nom, donnees.etudiant1.prenom, donnees.etudiant1.branche) == true && 
                    trouverCours(donnees.etudiant2.heureDebut, donnees.etudiant2.heureFin, donnees.etudiant2.jour, donnees.type, donnees.etudiant2.semaine, donnees.codeUV, donnees.etudiant2.nom, donnees.etudiant2.prenom, donnees.etudiant2.branche) == true
                ) {
                    deplacerEtudiant(donnees.etudiant1.nom, donnees.etudiant1.prenom, donnees.etudiant1.branche, donnees.etudiant2.nom, donnees.etudiant2.prenom, donnees.etudiant2.branche, donnees.type, donnees.etudiant1.semaine, donnees.codeUV, donnees.etudiant1.jour, donnees.etudiant1.heureDebut, donnees.etudiant1.heureFin, donnees.type, donnees.etudiant2.semaine, donnees.codeUV, donnees.etudiant2.jour, donnees.etudiant2.heureDebut, donnees.etudiant2.heureFin)
                    
                    // Utilisation de la fonction pour générer le texte
                    const texteGenere = genererTexte(etudiantsDict);
                    document.getElementById("textUV").value = texteGenere;
                }
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }
    }
}

function genererTexte(etudiantsDict) {
    let texte = "";
    let coursVisites = new Set(); // Pour garder une trace des cours déjà traités par cet étudiant
    let etudiantsInscritsCount = 0; 

    // Convertir les étudiants en tableau et les trier par ordre alphabétique
    const etudiantsSorted = Object.values(etudiantsDict).sort((a, b) => {
        return a.nom.localeCompare(b.nom);
    });

    // Parcours de chaque étudiant dans etudiantsSorted
    etudiantsSorted.forEach(etudiant => {
        // Vérifier si l'étudiant a des cours
        if (etudiant && etudiant.cours && etudiant.cours.length > 0) {
            etudiant.cours.forEach(cours => {
                const key = JSON.stringify({
                    codeCours: cours.codeCours,
                    type: cours.type,
                    semaine: cours.semaine,
                    jourSemaine: cours.jourSemaine,
                    horaire: cours.horaire,
                    salle: cours.salle
                });

                // Vérifier si ce cours a déjà été traité pour éviter les doublons
                if (!coursVisites.has(key)) {
                    coursVisites.add(key);
                    var typeCours;
                    if(cours.type.includes('P')){
                        typeCours = "T " + cours.num + " " + cours.semaine.padEnd(4);
                    }else{
                        typeCours = cours.type.substring(1,2) + " " + cours.num.padEnd(4);
                    }
                    texte += "+----------\n";
                    texte += ` ${cours.codeCours.padEnd(11)}${typeCours},PL.MAX=${(cours.plMax === 0 ? '0' : cours.plMax.toString()).padStart(3)} ,LIBRES=${(cours.plLibre === 0 ? '0' : cours.plLibre.toString()).padStart(3)} ,INSCRITS=${(cours.inscrit === 0 ? '0' : cours.inscrit.toString()).padStart(3)}  H=${cours.jourSemaine}...\n`;
                    texte += `${cours.horaire},F1,S=${cours.salle}\n\n`;
                    etudiantsInscritsCount = 0;

                    // Filtrer les étudiants inscrits à ce cours
                    const etudiantsDansCeCours = etudiantsSorted.filter(e =>
                        e.cours.some(c => c.codeCours === cours.codeCours && c.type === cours.type && c.jourSemaine === cours.jourSemaine && c.horaire === cours.horaire && c.salle === cours.salle)
                    );

                    // Supprimer les valeurs undefined dans les noms des étudiants
                    etudiantsDansCeCours.forEach((etudiant, index) => {
                        const numero = (index + 1).toString().padStart(3, '0'); // Assurer que le numéro soit toujours en trois chiffres
                        const nom = etudiant.nom || '';
                        const nomPrenom = `${nom}`.padEnd(25);
                        const branche = etudiant.branche || '';
                        texte += ` ${numero}   ${nomPrenom}${branche}\n`;

                        // Incrémenter le compteur d'étudiants inscrits
                        etudiantsInscritsCount++;

                        // Ajouter un saut de ligne supplémentaire après chaque groupe de quatre étudiants inscrits
                        if (etudiantsInscritsCount % 4 === 0) {
                            texte += "\n";
                        }
                    });

                    texte += "\n1\n\n";
                }
            });
        }
    });

    return texte;
}




/* Sauvegarde du mail en local */

function ouvrirBaseDeDonnees() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('emailsDB', 1);

        request.onerror = function(event) {
            reject("Erreur lors de l'ouverture de la base de données: " + event.target.error);
        };

        request.onupgradeneeded = function(event) {
            const db = event.target.result;
            db.createObjectStore('emails', { keyPath: 'id', autoIncrement: true });
        };

        request.onsuccess = function(event) {
            const db = event.target.result;
            resolve(db);
        };
    });
}

function saveMailBDD(email) {
    return new Promise((resolve, reject) => {
        ouvrirBaseDeDonnees()
            .then(db => {
                const transaction = db.transaction(['emails'], 'readwrite');
                const objectStore = transaction.objectStore('emails');

                // Vider la base de données avant d'ajouter le nouvel email
                const clearRequest = objectStore.clear();
                clearRequest.onsuccess = function(event) {
                    // Ajouter le nouvel email
                    const request = objectStore.add({ email: email });

                    request.onsuccess = function(event) {
                        resolve("Email sauvegardé avec succès dans la base de données IndexedDB");
                    };

                    request.onerror = function(event) {
                        reject("Erreur lors de la sauvegarde de l'email dans la base de données IndexedDB: " + event.target.error);
                    };
                };

                clearRequest.onerror = function(event) {
                    reject("Erreur lors de la suppression des données existantes dans la base de données IndexedDB: " + event.target.error);
                };
            })
            .catch(error => {
                reject(error);
            });
    });
}

// Fonction pour récupérer l'email sauvegardé dans la base de données IndexedDB
function getMailBDD() {
    return new Promise((resolve, reject) => {
        ouvrirBaseDeDonnees()
            .then(db => {
                const transaction = db.transaction(['emails'], 'readonly');
                const objectStore = transaction.objectStore('emails');

                const request = objectStore.getAll();

                request.onsuccess = function(event) {
                    const emails = event.target.result;
                    if (emails && emails.length > 0) {
                        resolve(emails[0].email);
                    } else {
                        resolve(null);
                    }
                };

                request.onerror = function(event) {
                    reject("Erreur lors de la récupération de l'email depuis la base de données IndexedDB: " + event.target.error);
                };
            })
            .catch(error => {
                reject(error);
            });
    });
}