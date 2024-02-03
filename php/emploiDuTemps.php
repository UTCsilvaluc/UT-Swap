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
    
    <script src="../js/emploiDuTemps.js">
    </script>

</body>
<div class="hoverCours" style="display: none ; flex-direction: column ; position: absolute ; z-index: 500">
    <img class="hoverCoursIcon" id="swap" src="../svg/swap.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgSwapEnter(event)" onmouseleave="svgSwapLeave(event)">
    <img class="hoverCoursIcon" id="displace" src="../svg/displace.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgpDisplaceEnter(event)" onmouseleave="svgDisplaceLeave(event)">
    <img class="hoverCoursIcon" id="trash" src="../svg/trash.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgpTrasheEnter(event)" onmouseleave="svgTrashLeave(event)">
</div>

<div id="addCreneau" style="display: none;">
    <div id="titre_nouveau">
        <h1>Nouvelle demande de Swap</h1>
        <hr>
    </div>
    <form action="#" method="post" id="form_debut_nouveau">
        <ul>
            <li class="double-input">
                <div>
                    <label for="input-uv">Code d'UV:</label>
                    <input type="text" id="input-uv" list="uvs" name="uv" placeholder="Veuillez entrer le code de l'UV">
                    <datalist id="uvs">
                        <option value="UV1">
                        </option><option value="UV2">
                        </option><option value="UV3">
                        </option></datalist>
                </div>
                <div>
                    <label for="input-creneau">Créneau:</label>
                    <input type="date" id="input-creneau" name="creneau" placeholder="Veuillez entrer le créneau">
                </div>
            </li>
            <li class="double-input">
                <div>
                    <label for="input-hdebut">Heure début:</label>
                    <input type="time" id="input-hdebut" name="hdebut">
                </div>
                <div>
                    <label for="input-hfin">Heure fin:</label>
                    <input type="time" id="input-hfin" name="hfin">
                </div>
            </li>
            <li class="double-input">
                <div>
                    <label for="input-salle">Salle:</label>
                    <input type="text" id="input-salle" name="salle" placeholder="Veuillez entrer votre salle">
                </div>
                <div>
                    <label for="input-type">Type:</label>
                    <select id="input-type" name="type">
                        <option value="td">TD</option>
                        <option value="tp">TP</option>
                        <option value="cm">Cours</option>
                    </select>
                </div>
            </li>
            <li class="basique">
                <input type="checkbox" id="input-semaine">
                <label for="input-semaine">Créneau une semaine sur deux</label>
            </li>
            <li class="basique hidden" id="choix-semaine">
                <input type="radio" name="semainechoix" value="sA" id="sA-choix">
                <label for="sA-choix">Semaine A</label>
                <input type="radio" name="semainechoix" value="sB" id="sB-choix">
                <label for="sB-choix">Semaine B</label>

            </li>
        </ul>
    </form>
    <form id="form_fin_nouveau" action="#" method="post">
        <hr>
        <input type="submit" value="Poster la demande">
    </form>
</div>

</html>
