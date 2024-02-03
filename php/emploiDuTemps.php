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
    <div style="display:flex">
        <div class="conteneurHours"></div>
        <div>
            <div id="emploi_du_temps">
                <div id="lundi" class="jour"><h1>Lundi</h1>
                    <div class="endroit_cours">
                    </div>
                </div>
    
                <div id="mardi" class="jour"><h1>Mardi</h1>
                    <div class="endroit_cours">
                    </div>
                </div>
    
                <div id="mercredi" class="jour"><h1>Mercredi</h1>
                    <div class="endroit_cours">
                    </div>
                </div>
    
                <div id="jeudi" class="jour"><h1>Jeudi</h1>
                    <div class="endroit_cours">
                    </div>
                </div>
    
                <div id="vendredi" class="jour"><h1>Vendredi</h1>
                    <div class="endroit_cours">
                    </div>
                </div>
    
                <div id="samedi" class="jour"><h1>Samedi</h1>
                    <div class="endroit_cours">
                    </div>
                </div>
                
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
                        <label for="input-uv">Code d'UV:<p class="hidden" id="require_uv">*</p></label>
                        <input type="text" id="input-uv" list="uvs" name="uv" placeholder="Veuillez entrer le code de l'UV" required>
                        <p class="hidden">UV non valide</p>
                        <datalist id="uvs">
                            <option value="UV1">
                            <option value="UV2">
                            <option value="UV3">
                        </datalist>
                    </div>
                    <div>
                        <label for="input-creneau">Créneau:<p class="hidden" id="require_creneau">*</p></label>
                        <select id="input-creneau" name="creneau" required>
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
                        <label for="input-hdebut">Heure début:<p class="hidden" id="require_hdebut">*</p></label>
                        <input type="time" id="input-hdebut" name="hdebut" required>
                        <p class="hidden">Heures non valide</p>
                    </div>
                    <div>
                        <label for="input-hfin">Heure fin:<p class="hidden" id="require_hfin">*</p></label>
                        <input type="time" id="input-hfin" name="hfin" required>
                    </div>
                </li>
                <li class="double-input">
                    <div>
                        <label for="input-salle">Salle:<p class="hidden" id="require_salle">*</p></label>
                        <input type="text" id="input-salle" name="salle" placeholder="Veuillez entrer votre salle" required>
                        <p class="hidden">Salle non valide</p>
                    </div>
                    <div>
                        <label for="input-type">Type:<p class="hidden" id="require_type">*</p></label>
                        <select id="input-type" name="type" required>
                            <option value="" disabled selected>Sélectionnez un type</option>
                            <option value="TD">TD</option>
                            <option value="TP">TP</option>
                            <option value="CM">Cours</option>
                        </select>
                        <p class="hidden">Type non valide</p>
                    </div>
                </li>
                <li class="basique">
                    <input type="checkbox" id="input-semaine" name="semaine">
                    <label for="input-semaine">Créneau une semaine sur deux</label>
                </li>
                <li class="basique hidden" id="choix-semaine">
                    <input type="radio" name="semainechoix" value="A" id="sA-choix">
                    <label for="sA-choix">Semaine A</label>
                    <input type="radio" name="semainechoix" value="B" id="sB-choix">
                    <label for="sB-choix">Semaine B</label>

                </li>
            </ul>
            <p id="message_pression" class="hidden">Assurez-vous de la validité ainsi que de la possession du créneau renseigné. Des incohérences répétées pourraient entraîner des sanctions, y compris le bannissement.</p>
            <p id="message_insertion" class="hidden">La demande a été envoyée !!</p>
        </div>
        <div id="div_fin_nouveau">
            <hr>
            <button class="bouton_nouveau hidden" id="bouton_ok">OK !</button></li>
            <button id="bouton_non_submit">Poster la demande</button>
            <div id="boutons_message" class="hidden">
                <button id="bouton_retour">Retour</button>
                <input type="submit" value="Poster la demande" id="submit_fin_nouveau">
            </div>
        </div>
    </form>

    <script src="../js/emploiDuTemps.js">
    </script>

</body>
<div class="hoverCours" style="display: none ; flex-direction: column ; position: absolute">
    <img class="hoverCoursIcon" id="swap" src="../svg/swap.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgSwapEnter(event)" onmouseleave="svgSwapLeave(event)">
    <img class="hoverCoursIcon" id="displace" src="../svg/displace.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgpDisplaceEnter(event)" onmouseleave="svgDisplaceLeave(event)">
    <img class="hoverCoursIcon" id="trash" src="../svg/trash.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgpTrasheEnter(event)" onmouseleave="svgTrashLeave(event)">
</div>



</html>
