function copierLien(element) {
    // Récupérer les informations sur la demande
    var uvType = element.closest('.demande').querySelector('h1').innerText;
    var horaires = element.closest('.demande').querySelector('h2').innerText;
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
                    var donnees = JSON.parse(rowAttribute);
                } catch (error) {
                    console.error("Erreur lors du parsing JSON :", error);
                }
            } else {
                console.error("Aucune donnée trouvée dans l'attribut data-row");
            }
            if(donnees.codeUV !== "" && donnees.type !== "" && donnees.salle !== ""){
                document.getElementsByClassName("bouton_nouveau")[0].click();
                bouton_non_submit.innerHTML = "Continuer";
                
                input_uv.value = donnees.codeUV;
                input_type.value = donnees.type;
                input_uv.disabled = true
                input_type.disabled = true
                bouton_non_submit.addEventListener("click", function() {
            
                    if(type !== "" && salle !== "" && hfin !== "" && hdebut !== "" && creneau !== "" && uv !== "" && uv.length === 4 && ["TD","TP","CM"].includes(type) && ["lundi","mardi","mercredi","jeudi","vendredi","samedi"].includes(creneau) && hdebut < hfin){
                        
                    }
                });
            }
        }
    
}


