<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/emploiDuTemps.css">
    <title>Header avec Notification</title>
</head>
<?php
include 'header.php';
?>
<body>
    <div class="conteneur">
        <div class="edt" style="display:flex ; width: 70%">
            <div class="conteneurHours"></div>
            <div>
                <div id="emploi_du_temps">
                    <div id="lundi" class="jour"><h1 class="titleday">Lundi</h1>
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

                    <div id="dimanche" class="jour" style="display: none"><h1 class="titleday">Dimanche</h1>
                        <div class="endroit_cours">
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="filtres"">
            <div><div class="filtre_titre"> <h1>Filtrer par</h1> <div class="vertical_ligne" style="height: 70px; width: 2px ; background: #ACACAC "></div>
                    <img src="../svg/black_menu.svg" alt="" onclick="openFiltre(event)" id="CloseOpenFiltreMenu"></div></div>
                <div class="conteneurFiltre" id="filterContainer1" style="display: none">
                    <div class="divFiltre" id="police"><h1 class="filtre_entete">Police</h1> <span class="policeSpan"><h3 class="checkPolice" onclick="changePolice(event)">Jost</h3> <h3 class="uncheckPolice" onclick="changePolice(event)">Times New Roman</h3> <h3 class="uncheckPolice" onclick="changePolice(event)">Comic Sans MS</h3></span></div>
                    <div class="divFiltre" id="jours"><h1 class="filtre_entete">Jour</h1> <span class="policeSpan" id="spanJour"><h3 class="check" onclick="changeJour(event)">Lundi</h3><h3 class="check" onclick="changeJour(event)">Mardi</h3><h3 class="check" onclick="changeJour(event)">Mercredi</h3><h3 class="check" onclick="changeJour(event)">Jeudi</h3><h3 class="check" onclick="changeJour(event)">Vendredi</h3><h3 class="check" onclick="changeJour(event)">Samedi</h3><h3 class="uncheck" onclick="changeJour(event)">Dimanche</h3></div>
                    <div class="divFiltre" id="couleurs"><h1 class="filtre_entete">Couleurs</h1> <span> <span class="policeSpan" id="couleurSpan"></span></div>
                    <div class="divFiltre" id="couleur_entete"><h1 class="filtre_entete">Couleur entête</h1> <span class="policeSpan">
                    <div class="inputCouleur" id="inputCouleur" style=""> <span style="margin-left: 20px"><input class="colorChoice" type="color" id="choix-couleur" name="choix-couleur" style="position: absolute; ; width: 2px ; height: 2px"></span> </div>

                </span></div>
                    <div class="divFiltre" id="langue"><h1>Langue</h1><span class="policeSpan"><h3>Anglais</h3> <h3>Français</h3> <h3>Espagnol</h3></span></div>
                    <div class="divFiltre" id="heures">
                        <h1>Horaires</h1> <span class="policeSpan">                <div>
                    <input type="time" id="filtre-input-hdebut" name="hdebut" value="08:00" required onchange="filtreTime(event)">
                </div>
                <div>
                    <input type="time" id="filtre-input-hfin" name="hfin" value="20:00" required onchange="filtreTime(event)">
                </div></span>
                    </div>
                    <div class="buttonFiltres"><button>Supprimer les filtres</button> <button style="background: #E6E6E6 ; color: black">Appliquer les filtres</button></div>
                </div>
                </div>
    </div>

    <form action="#" method="post" id="addCreneau">
        <div id="titre_nouveau">
            <h1>Ajouter un nouveau créneau</h1>
            <hr>
        </div>
        <div id="div_debut_nouveau">
            <ul id="ul_nouveau">
                <li class="double-input">
                    <div>
                        <label for="addCreneau-input-uv">Code d'UV:<p class="hidden" id="require_uv">*</p></label>
                        <input type="text" id="addCreneau-input-uv" list="uvs" name="uv" placeholder="Veuillez entrer le code de l'UV" required>
                        <p class="hidden">UV non valide</p>
                        <datalist id="uvs">
                            <option value="UV1">
                            <option value="UV2">
                            <option value="UV3">
                        </datalist>
                    </div>
                    <div>
                        <label for="addCreneau-input-creneau">Créneau:<p class="hidden" id="require_creneau">*</p></label>
                        <select id="addCreneau-input-creneau" name="creneau" required>
                            <option value="" disabled selected>Sélectionnez un créneau</option>
                            <option value="lundi">Lundi</option>
                            <option value="mardi">Mardi</option>
                            <option value="mercredi">Mercredi</option>
                            <option value="jeudi">Jeudi</option>
                            <option value="vendredi">Vendredi</option>
                            <option value="samedi">Samedi</option>
                        </select>
                        <p class="hidden">Créneau non valide</p>
                    </div>
                </li>
                <li class="double-input">
                    <div>
                        <label for="addCreneau-input-hdebut">Heure début:<p class="hidden" id="require_hdebut">*</p></label>
                        <input type="time" id="addCreneau-input-hdebut" name="hdebut" required>
                        <p class="hidden">Heures non valide</p>
                    </div>
                    <div>
                        <label for="addCreneau-input-hfin">Heure fin:<p class="hidden" id="require_hfin">*</p></label>
                        <input type="time" id="addCreneau-input-hfin" name="hfin" required>
                    </div>
                </li>
                <li class="double-input">
                    <div>
                        <label for="addCreneau-input-salle">Salle:<p class="hidden" id="require_salle">*</p></label>
                        <input type="text" id="addCreneau-input-salle" name="salle" placeholder="Veuillez entrer votre salle" required>
                        <p class="hidden">Salle non valide</p>
                    </div>
                    <div>
                        <label for="addCreneau-input-type">Type:<p class="hidden" id="require_type">*</p></label>
                        <select id="addCreneau-input-type" name="type" required>
                            <option value="" disabled selected>Sélectionnez un type</option>
                            <option value="TD">TD</option>
                            <option value="TP">TP</option>
                            <option value="CM">Cours</option>
                        </select>
                        <p class="hidden">Type non valide</p>
                    </div>
                </li>
                <li class="basique">
                    <input type="checkbox" id="addCreneau-input-semaine" name="semaine">
                    <label for="addCreneau-input-semaine">Créneau une semaine sur deux</label>
                </li>
                <li class="basique hidden" id="addCreneau-choix-semaine" hidden="hidden">
                    <input type="radio" name="semainechoix" value="A" id="addCreneau-sA-choix">
                    <label for="addCreneau-sA-choix">Semaine A</label>
                    <input type="radio" name="semainechoix" value="B" id="addCreneau-sB-choix">
                    <label for="addCreneau-sB-choix">Semaine B</label>
                </li>
            </ul>
            <p id="message_pression" class="hidden">Assurez-vous de la validité ainsi que de la possession du créneau renseigné. Des incohérences répétées pourraient entraîner des sanctions, y compris le bannissement.</p>
            <p id="message_insertion" class="hidden">La demande a été envoyée !!</p>
        </div>
        <div id="div_fin_nouveau">
            <hr>
            <button class="bouton_nouveau hidden" id="bouton_ok">OK !</button></li>
            <button id="bouton_non_submit">Ajouter le créneau</button>
            <div id="boutons_message" class="hidden">
                <button id="bouton_retour">Retour</button>
                <input type="submit" value="Poster la demande" id="submit_fin_nouveau">
            </div>
        </div>
    </form>
    <div class="hoverCours" style="display: none ; flex-direction: column ; position: absolute">
        <img class="hoverCoursIcon" id="swap" src="../svg/swap.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgSwapEnter(event)" onmouseleave="svgSwapLeave(event)" onclick="posterSwap(event)">
        <img class="hoverCoursIcon" id="displace" src="../svg/displace.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgpDisplaceEnter(event)" onmouseleave="svgDisplaceLeave(event)">
        <img class="hoverCoursIcon" id="trash" src="../svg/trash.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgpTrasheEnter(event)" onmouseleave="svgTrashLeave(event)" onclick="trashClick(event)">
        <div style="display: none" id="deleteCours"><button name="oui" class="coursDelete" onclick="deleteCours(event)">Supprimer</button><button name="oui" class="coursCancel" onclick="cancelDelete(event)">Annuler</button></div>
    </div>
    <script src="../js/emploiDuTemps.js">
    </script>

</body>




</html>
