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
        console.log(heureDebut);
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
                const joursSemaine = {
                    "1": 'Lundi',
                    "2": 'Mardi',
                    "3": 'Mercredi',
                    "4": 'Jeudi',
                    "5": 'Vendredi',
                    "6": 'Samedi'
                };
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


                if (donnees.semaine === "null") {
                    checkbox.disabled = true;
                } else {
                    checkbox.disabled = false;
                    checkbox.click();
                    checkbox.checked = true;
                    localStorage.setItem("semaine" , donnees.semaine);
                }

            }

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

