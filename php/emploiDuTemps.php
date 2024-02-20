<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/emploiDuTemps.css">
    <title>Header avec Notification</title>
</head>
<body>
    <?php
    include "header.php";
    ?>
    <main>
        
        <div id="parent_edt">
            <div class="edt" style="display:flex ;">
                <div id="dessus_edt">
                        <div id="control_edt">
                            <img class="svgFiltre" src="../svg/IMPORT_FILTRE.svg" alt="" onclick="menuimportEDT(event)">
                            <img class="svgFiltre" src="../svg/TRASH_FILTRE.svg" onclick="resetEDT(event)" alt="">
                            <img class="svgFiltre" src="../svg/EXPORT_FILTRE.svg" alt="">
                        </div>
                        <div><img class="svgFiltre" src="../svg/FILTRE_FILTRE.svg" id="filtre_edt" onclick="openFiltre(event)"></div>
                    </div>
                <ul id="menu_jour_edt">
                    <li class="jour_select" onclick="changerJour(this)">Lundi</li>
                    <li onclick="changerJour(this)">Mardi</li>
                    <li onclick="changerJour(this)">Mercredi</li>
                    <li onclick="changerJour(this)">Jeudi</li>
                    <li onclick="changerJour(this)">Vendredi</li>
                    <li onclick="changerJour(this)">Samedi</li>
                    <li onclick="changerJour(this)">Dimanche</li>
                </ul>
                <div id="conteneur_edt">
                    <div id="ecran_edt"></div>
                    <div class="importEDT" style="display: none" id="importEDTID">
                            <div id="titre_import">
                                <h1>Ajouter mon emploi du temps</h1>
                                <hr>
                            </div>
                            <div id="content_import">
                                <textarea name="texteUV" id="textUV" cols="30" rows="10" placeholder="Veuillez renseigner le mail reçu comprenant la liste des inscriptions aux UVS"></textarea>
                            </div>
                            <div id="button_import">
                                <hr>
                                <button onclick="importEDT(event)">Ajouter</button>
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
            <div class="filtres" id="menuFiltre">
                <div class="filtre_titre"> 
                    <h1>Filtrer par</h1>
                    <div id="filtre_croix">
                        <img src="../svg/black_cross.svg" alt="" id="CloseOpenFiltreMenu" onclick="closeFiltre(event)">
                    </div>
                </div>
                <div class="conteneur_filtre" id="filterContainer1">
                    <div class="filtre_parent" id="police">
                        <h1 class="filtre_entete">Police</h1>
                        <span class="filtre_span">
                            <h3 class="checkElement" onclick="changePolice(event)" id="mainPolice">Jost</h3>
                            <h3 class="uncheckElement" onclick="changePolice(event)">Kantumruy</h3>
                            <h3 class="uncheckElement" onclick="changePolice(event)">Times New Roman</h3>
                            <h3 class="uncheckElement" onclick="changePolice(event)">Comic Sans MS</h3>
                        </span>
                    </div>
                    <div class="filtre_parent" id="jours">
                        <h1 class="filtre_entete">Jour</h1>
                        <span class="filtre_span" id="spanJour">
                            <h3 class="check" onclick="changeJour(event)">Lundi</h3>
                            <h3 class="check" onclick="changeJour(event)">Mardi</h3>
                            <h3 class="check" onclick="changeJour(event)">Mercredi</h3>
                            <h3 class="check" onclick="changeJour(event)">Jeudi</h3>
                            <h3 class="check" onclick="changeJour(event)">Vendredi</h3>
                            <h3 class="check" onclick="changeJour(event)">Samedi</h3>
                            <h3 class="uncheck" onclick="changeJour(event)">Dimanche</h3>
                        </div>
                    <div class="filtre_parent" id="couleurs">
                        <h1 class="filtre_entete">Couleurs</h1>
                        <span>
                            <span class="filtre_span" id="couleurSpan"></span>
                        </span>
                    </div>
                    <div class="filtre_parent" id="couleur_entete">
                        <h1 class="filtre_entete">Couleur entête</h1>
                        <span class="filtre_span">
                            <div class="inputCouleur" id="inputCouleur" style="">
                                <span style="margin-left: 20px">
                                    <input class="colorChoice" type="color" id="choix-couleur" name="choix-couleur" style="position: absolute; ; width: 2px ; height: 2px">
                                </span>
                            </div>
                        </span>
                    </div>

                    <div class="filtre_parent" id="heures">
                        <h1>Horaires</h1>
                        <span class="filtre_span">
                            <div>
                                <input type="time" id="filtre-input-hdebut" name="hdebut" value="08:00" required onchange="filtreTime(event)">
                            </div>
                            <div>
                                <input type="time" id="filtre-input-hfin" name="hfin" value="20:00" required onchange="filtreTime(event)">
                            </div>
                        </span>
                    </div>
                    <div class="buttonFiltres">
                        <button class="filtreButton" id="appliquerFiltre" onclick="supprimerFiltre(event)">Supprimer les filtres</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="hoverCours" style="display: none; flex-direction: column ; position: absolute">
            <img class="hoverCoursIcon" id="swap" src="../svg/swap_icone.svg" alt="" onmouseenter="svgSwapEnter(event)" onmouseleave="svgSwapLeave(event)" onclick="posterSwap(event)">
            <img class="hoverCoursIcon" id="displace" src="../svg/deplacer_icone.svg" alt="" onmouseenter="svgpDisplaceEnter(event)" onmouseleave="svgDisplaceLeave(event)">
            <img class="hoverCoursIcon" id="trash" src="../svg/supprimer_icone.svg" alt="" onmouseenter="svgpTrasheEnter(event)" onmouseleave="svgTrashLeave(event)" onclick="trashClick()">
            <div style="display: none" id="deleteCours"><button class="coursCancel" onclick="cancelDelete(event)">Annuler</button><button class="coursDelete" onclick="deleteCours()">Supprimer</button></div>
        </div>
    </main>
    <script src="../js/emploiDuTemps.js">
    </script>
</body>




</html>
