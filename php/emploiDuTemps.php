<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/emploiDuTemps.css">
    <title>Header avec Notification</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>
<body>
    <?php
    include "header.php";
    ?>
    <script>
        
function exportEDT(type){
    // Capture l'élément en PNG
    html2canvas(document.querySelector('#conteneur_edt > div:nth-child(4)')).then(function(canvas) {
        // Convertit le canvas en image data URL
        
        exportElement.style.display = "none";
        ecran.style.display = "none";
        var imageDataURL = canvas.toDataURL('image/png');
        if(type === "png"){
            // Crée un lien de téléchargement
            var downloadLink = document.createElement('a');
            downloadLink.href = imageDataURL;
            downloadLink.download = document.getElementById("fileName").value + '.png';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    });
}

    </script>
    <main>
        <div id="parent_edt">
            <div class="edt" style="display:flex ;">
                <div id="dessus_edt">
                        <div id="control_edt">
                            <img class="svgCustom" title="Importer l'emploi du temps" src="../svg/IMPORT_FILTRE.svg" alt="" onclick="menuimportEDT(event)">
                            <img class="svgCustom" title="Supprimer l'emploi du temps" src="../svg/TRASH_FILTRE.svg" onclick="resetEDT(event)" alt="">
                            <img class="svgCustom" title="Exporter l'emploi du temps" src="../svg/EXPORT_FILTRE.svg" onclick="menuExportEDT(event)" alt="">
                        </div>
                        <div><img class="svgCustom" title="Personnaliser l'emploi du temps" src="../svg/custom.svg" id="custom_edt" onclick="openCustom(event)"></div>
                    </div>
                <ul id="menu_jour_edt">
                    <li class="jour_select" onclick="changerJour(this,'lundi')"><span class="jour_entier">Lundi</span><span class="jour_mid">Lun.</span><span class="jour_small">L.</span></li>
                    <li onclick="changerJour(this,'mardi')"><span class="jour_entier">Mardi</span><span class="jour_mid">Mar.</span><span class="jour_small">Ma.</span></li>
                    <li onclick="changerJour(this,'mercredi')"><span class="jour_entier">Mercredi</span><span class="jour_mid">Mer.</span><span class="jour_small">Me.</span></li>
                    <li onclick="changerJour(this,'jeudi')"><span class="jour_entier">Jeudi</span><span class="jour_mid">Jeu.</span><span class="jour_small">J.</span></li>
                    <li onclick="changerJour(this,'vendredi')"><span class="jour_entier">Vendredi</span><span class="jour_mid">Ven.</span><span class="jour_small">V.</span></li>
                    <li onclick="changerJour(this,'samedi')"><span class="jour_entier">Samedi</span><span class="jour_mid">Sam.</span><span class="jour_small">S.</span></li>
                    <li onclick="changerJour(this,'dimanche')"><span class="jour_entier">Dimanche</span><span class="jour_mid">Dim.</span><span class="jour_small">D.</span></li>
                </ul>
                <div id="conteneur_edt">
                    <div id="ecran_edt"></div>
                    <div class="mid_pannel" style="display: none" id="importEDTID">
                        <div class="mid_titre">
                            <h1>Ajouter mon emploi du temps</h1>
                            <hr>
                        </div>
                        <img src="../svg/croix.svg" class="croix">
                        <div class="mid_content">
                            <textarea name="texteUV" id="textUV" cols="30" rows="10" placeholder="Veuillez renseigner le mail reçu comprenant la liste des inscriptions aux UVS"></textarea>
                        </div>
                        <div class="mid_button">
                            <hr>
                            <button onclick="importEDT(event)">Ajouter</button>
                        </div>
                    </div>
                    <div class="mid_pannel" style="display: none" id="exportEDT">
                        <div class="mid_titre">
                            <h1>Exporter mon emploi du temps</h1>
                            <hr>
                        </div>
                        <img src="../svg/croix.svg" class="croix">
                        <div class="mid_content">
                            <label for="fileName">Nom du fichier:</label>
                            <input id="fileName" type="text" value="emploi_du_temps">
                        </div>
                        <div class="mid_button">
                            <hr>
                            <button onclick="exportEDT('png')" id="export_png">Exporter PNG</button>
                            <button onclick="exportEDT('pdf')" id="export_pdf">Exporter PDF</button>
                        </div>
                    </div>
                    <div>
                        <div class="conteneurHours"></div>
                        <div id="emploi_du_temps">
                            <div id="lundi" class="jour jour_select"><h1 class="titleday">Lundi</h1>
                                <div class="endroit_cours">
                                </div>
                            </div>

                            <div id="mardi" class="jour"><h1 class="titleday">Mardi</h1>
                                <div class="endroit_cours">
                                </div>
                            </div>

                            <div id="mercredi" class="jour"><h1 class="titleday">Mercredi</h1>
                                <div class="endroit_cours">
                                </div>
                            </div>

                            <div id="jeudi" class="jour"><h1 class="titleday">Jeudi</h1>
                                <div class="endroit_cours">
                                </div>
                            </div>

                            <div id="vendredi" class="jour"><h1 class="titleday">Vendredi</h1>
                                <div class="endroit_cours">
                                </div>
                            </div>

                            <div id="samedi" class="jour"><h1 class="titleday">Samedi</h1>
                                <div class="endroit_cours">
                                </div>
                            </div>

                            <div id="dimanche" class="jour"><h1 class="titleday">Dimanche</h1>
                                <div class="endroit_cours">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="customs" id="menuCustom">
                <div class="custom_titre"> 
                    <h1>Personnaliser</h1>
                    <div id="custom_croix">
                        <img src="../svg/black_cross.svg" alt="" id="CloseOpenCustomMenu" onclick="closeCustom(event)">
                    </div>
                </div>
                <div class="conteneur_custom" id="filterContainer1">
                    <div class="custom_parent" id="police">
                        <h1 class="custom_entete">Police</h1>
                        <span class="custom_span">
                            <h3 class="checkElement" onclick="changePolice(event)" id="mainPolice">Jost</h3>
                            <h3 class="uncheckElement" onclick="changePolice(event)">Kantumruy</h3>
                            <h3 class="uncheckElement" onclick="changePolice(event)">Times New Roman</h3>
                            <h3 class="uncheckElement" onclick="changePolice(event)">Comic Sans MS</h3>
                        </span>
                    </div>
                    <div class="custom_parent" id="jours">
                        <h1 class="custom_entete">Jour</h1>
                        <span class="custom_span" id="spanJour">
                            <h3 class="check" onclick="changeJour(event)">Lundi</h3>
                            <h3 class="check" onclick="changeJour(event)">Mardi</h3>
                            <h3 class="check" onclick="changeJour(event)">Mercredi</h3>
                            <h3 class="check" onclick="changeJour(event)">Jeudi</h3>
                            <h3 class="check" onclick="changeJour(event)">Vendredi</h3>
                            <h3 class="check" onclick="changeJour(event)">Samedi</h3>
                            <h3 class="uncheck" onclick="changeJour(event)">Dimanche</h3>
                        </div>
                    <div class="custom_parent" id="couleurs">
                        <h1 class="custom_entete">Couleurs</h1>
                        <span>
                            <span class="custom_span" id="couleurSpan"></span>
                        </span>
                    </div>
                    <div class="custom_parent" id="couleur_entete">
                        <h1 class="custom_entete">Couleur entête</h1>
                        <span class="custom_span">
                            <div class="inputCouleur" id="inputCouleur" style="">
                                <span style="margin-left: 20px">
                                    <input class="colorChoice" type="color" id="choix-couleur" name="choix-couleur" style="position: absolute; ; width: 2px ; height: 2px">
                                </span>
                            </div>
                        </span>
                    </div>

                    <div class="custom_parent" id="heures">
                        <h1>Horaires</h1>
                        <span class="custom_span">
                            <div>
                                <input type="time" id="custom-input-hdebut" name="hdebut" value="08:00" required onchange="customTime(event)">
                            </div>
                            <div>
                                <input type="time" id="custom-input-hfin" name="hfin" value="20:00" required onchange="customTime(event)">
                            </div>
                        </span>
                    </div>
                    <div class="buttonCustoms">
                        <button class="customButton" id="appliquerCustom" onclick="supprimerCustom(event)">Supprimer les customs</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="hoverCours" style="display: none; flex-direction: column ; position: absolute">
            <img class="hoverCoursIcon" id="swap" src="../svg/swap_icone.svg" alt="" onmouseenter="svgSwapEnter(event)" onmouseleave="svgSwapLeave(event)" onclick="posterSwap(event)">
            <img class="hoverCoursIcon" id="displace" src="../svg/deplacer_icone.svg" alt="" onmouseenter="svgpDisplaceEnter(event)" onmouseleave="svgDisplaceLeave(event)">
            <img class="hoverCoursIcon" id="trash" src="../svg/supprimer_icone.svg" onmouseenter="svgpTrasheEnter(event)" onmouseleave="svgTrashLeave(event)" onclick="trashClick()">
            <div style="display: none" id="deleteCours"><button class="coursCancel" onclick="cancelDelete(event)">Annuler</button><button class="coursDelete" onclick="deleteCours()">Supprimer</button></div>
        </div>
    </main>
    <script src="../js/emploiDuTemps.js">
    </script>
</body>




</html>
