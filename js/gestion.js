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

window.addEventListener('resize', mettreAJourContenuProfil);
mettreAJourContenuProfil();

function canDisplayRequest(event) {
    var divs_demande = document.getElementsByClassName("demande_professeur");
    var liste_uvs = document.getElementById("spanUV").getElementsByClassName('uvCheck');
    var liste_type = document.getElementById("spanType").getElementsByClassName("typeCheck");
    var UvsActifs = [];
    var typeActifs = [];
    var display = true;
    for (var i = 0; i < liste_type.length; i++) {
        var type = liste_type[i].innerHTML.trim();
        typeActifs.push(type);
    }

    for (var i = 0; i < liste_uvs.length; i++) {
        var uv = liste_uvs[i].innerHTML.trim();
        UvsActifs.push(uv);
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
function openImport(){
    import_prof.classList.toggle('hidden', false);
    import_mail.classList.toggle('hidden', false);
    import_personne_fraud_parent.classList.toggle('hidden', true);
}

document.addEventListener("click" , function (event) {

    if (!(event.target.closest("#import_prof")) && !document.getElementById("import_prof").className.includes("hidden") && event.target.id != "button_import"){
        import_prof.classList.toggle('hidden', true);
    }
});

const regexEtudiant = /^\s*\d{3}\s+([\w\s-]+)\s+(\w{4})\s*$/;
const regexCours =/^\s*(\w{4})\s+([\w\s]{3})\s*,\s*PL\.MAX=\s*(\d+)\s*,\s*LIBRES=\s*(\d+)\s*,\s*INSCRITS=\s*(\d+)\s*H=(\w+)\.\.\.\s*$/;
const regexHoraireSalle = /(\d{2}:\d{2}-\d{2}:\d{2}),(\w+),S=(\w+)/;
var coursAvecEtudiants;
function changeImport(element){
    coursAvecEtudiants = [];
    var texte = element.value;
    // Séparer les textes de cours en utilisant la séquence "+----------"
    var cours = texte.split('+----------');
    
    
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
                if (match[2].includes('D')) {
                    detailsCours = 'TD';
                } else if (match[2].includes('C')) {
                    detailsCours = 'CM';
                } else if (match[2].includes('P')) {
                    detailsCours = 'TP';
                } else {
                    detailsCours = match[2];
                }
                coursInfo = {
                    codeCours: match[1],
                    type: detailsCours,
                    plMax: parseInt(match[3]),
                    placesLibres: parseInt(match[4]),
                    inscrits: parseInt(match[5]),
                    jourSemaine: match[6],
                    etudiants: []
                };
            } else if (regexHoraireSalle.test(ligne)) {
                // Si la ligne correspond à l'horaire et à la salle du cours
                const [, horaire,, salle] = ligne.match(regexHoraireSalle);
                coursInfo.horaire = horaire;
                coursInfo.salle = salle;
            }
        }
    
        // Ajouter les étudiants au cours correspondant
        if (coursInfo) {
            coursInfo.etudiants = etudiants;
            coursAvecEtudiants.push(coursInfo);
        }
    }
}



function trouverCours(heureDebut, heureFin, jour, type, codeCours, nom, prenom, branche) {
    // Parcourir la liste des cours avec étudiants
    heureDebut = heureDebut.replace("h",":");
    heureFin = heureFin.replace("h",":");
    const coursTrouve = coursAvecEtudiants.find(cours => {
        return (
            cours.type === type &&
            cours.codeCours === codeCours &&
            cours.jourSemaine === jour.toUpperCase() &&
            cours.horaire === `${heureDebut}-${heureFin.replace("h",":")}`
        );
    });

    // Vérification de la présence de l'élève dans le cours trouvé
    if (coursTrouve) {
        const etudiantPresent = coursTrouve.etudiants.find(etudiant => {
            return (
                etudiant.nom === `${nom.toUpperCase()} ${prenom.toUpperCase()}` &&
                etudiant.branche.substring(0, 2) === branche.substring(0, 2).toUpperCase()
            );
        });

        return !!etudiantPresent; // Convertit en booléen
    }

    return false; // Le cours n'a pas été trouvé
}

function checkSwap() {
    var listeDemande = document.getElementsByClassName("demande_professeur");
    var prenomsFrauduleux = [];

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

            if (donnees.statut !== "" && donnees.statut == 2) {
                if (donnees.etudiant1 && donnees.etudiant1.heureDebut && donnees.etudiant1.heureFin && donnees.etudiant1.jour && donnees.type && donnees.codeUV && donnees.etudiant1.nom && donnees.etudiant1.prenom && donnees.etudiant1.branche) {
                    if (!trouverCours(donnees.etudiant1.heureDebut, donnees.etudiant1.heureFin, donnees.etudiant1.jour, donnees.type, donnees.codeUV, donnees.etudiant1.nom, donnees.etudiant1.prenom, donnees.etudiant1.branche)) {
                        shakeElement(demande);
                        demande.getElementsByClassName("infos_swap")[0].style.color = "red";
                        var prenomNom = donnees.etudiant1.prenom + ' ' + donnees.etudiant1.nom;
                        if (!prenomsFrauduleux.includes(prenomNom)) {
                            prenomsFrauduleux.push(prenomNom);
                        }
                    }
                }
                if (donnees.etudiant2 && donnees.etudiant2.heureDebut && donnees.etudiant2.heureFin && donnees.etudiant2.jour && donnees.type && donnees.codeUV && donnees.etudiant2.nom && donnees.etudiant2.prenom && donnees.etudiant2.branche) {
                    if (!trouverCours(donnees.etudiant2.heureDebut, donnees.etudiant2.heureFin, donnees.etudiant2.jour, donnees.type, donnees.codeUV, donnees.etudiant2.nom, donnees.etudiant2.prenom, donnees.etudiant2.branche)) {
                        shakeElement(demande);
                        demande.getElementsByClassName("infos_swap")[1].style.color = "red";
                        var prenomNom = donnees.etudiant2.prenom + ' ' + donnees.etudiant2.nom;
                        if (!prenomsFrauduleux.includes(prenomNom)) {
                            prenomsFrauduleux.push(prenomNom);
                        }
                    }
                }
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }
    }

    import_personne_fraud_parent.innerHTML = '<h1 id="import_personne_fraud_title">Il y a ' + prenomsFrauduleux.length + ' personnes frauduleuses:</h1>';

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
function deplacerEtudiant(nom1, prenom1, branche1, nom2, prenom2, branche2, type, codeCours, jour, horaireDebut, horaireFin) {
    // Rechercher le cours correspondant au code, au type, au jour et à l'horaire
    var coursTrouve = coursAvecEtudiants.find(cours => cours.codeCours === codeCours && cours.type === type && cours.jourSemaine === jour.toUpperCase() && cours.horaire === `${horaireDebut.replace("h",":")}-${horaireFin.replace("h",":")}`);
    nom1 = nom1.toUpperCase();
    prenom1 = prenom1.toUpperCase();
    nom2 = nom2.toUpperCase();
    prenom2 = prenom2.toUpperCase();
    if (coursTrouve) {
        // Rechercher l'étudiant 2 dans le cours
        var etudiant2 = coursTrouve.etudiants.find(etudiant => etudiant.nom === `${nom2} ${prenom2}` && etudiant.branche.substring(0, 2).toUpperCase() === branche2.substring(0, 2).toUpperCase());
        if (etudiant2) {
            // Remplacer l'étudiant 2 par l'étudiant 1
            var index = coursTrouve.etudiants.indexOf(etudiant2);
            coursTrouve.etudiants[index] = { nom: `${nom1} ${prenom1}`, branche: branche1 };
            return; // Sortir de la fonction une fois le déplacement effectué
        } else {
            console.error(`Étudiant introuvable dans le cours (${codeCours} (${type}), ${jour}, ${horaireDebut}-${horaireFin})`);
        }
    } else {
        console.error(`Aucun cours trouvé avec les caractéristiques spécifiées : ${codeCours} (${type}), ${jour.toUpperCase()}, ${horaireDebut.replace("h",":")}-${horaireFin.replace("h",":")}`);
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
                    trouverCours(donnees.etudiant1.heureDebut, donnees.etudiant1.heureFin, donnees.etudiant1.jour, donnees.type, donnees.codeUV, donnees.etudiant1.nom, donnees.etudiant1.prenom, donnees.etudiant1.branche) && 
                    trouverCours(donnees.etudiant2.heureDebut, donnees.etudiant2.heureFin, donnees.etudiant2.jour, donnees.type, donnees.codeUV, donnees.etudiant2.nom, donnees.etudiant2.prenom, donnees.etudiant2.branche)
                ) {
                    deplacerEtudiant(donnees.etudiant1.nom, donnees.etudiant1.prenom, donnees.etudiant1.branche, donnees.etudiant2.nom, donnees.etudiant2.prenom, donnees.etudiant2.branche, donnees.type, donnees.codeUV, donnees.etudiant2.jour, donnees.etudiant2.heureDebut, donnees.etudiant2.heureFin)
                    deplacerEtudiant(donnees.etudiant2.nom, donnees.etudiant2.prenom, donnees.etudiant2.branche, donnees.etudiant1.nom, donnees.etudiant1.prenom, donnees.etudiant1.branche, donnees.type, donnees.codeUV, donnees.etudiant1.jour, donnees.etudiant1.heureDebut, donnees.etudiant1.heureFin)
                }
            }
        } else {
            console.error("Aucune donnée trouvée dans l'attribut data-row");
        }
    }
}
