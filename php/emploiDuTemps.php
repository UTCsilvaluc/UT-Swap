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
<div class="hoverCours" style="display: none ; flex-direction: column ; position: absolute">
    <img class="hoverCoursIcon" id="swap" src="../svg/swap.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgSwapEnter(event)" onmouseleave="svgSwapLeave(event)">
    <img class="hoverCoursIcon" id="displace" src="../svg/displace.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgpDisplaceEnter(event)" onmouseleave="svgDisplaceLeave(event)">
    <img class="hoverCoursIcon" id="trash" src="../svg/trash.svg" alt="" style="width: 30px ; height: 30px" onmouseenter="svgpTrasheEnter(event)" onmouseleave="svgTrashLeave(event)">
</div>

<div class="addCreneau" id="addCreneau">
    <div class="titre"><h1>Ajouter un nouveau créneau</h1></div>

    <hr>
    <div class="formulaire">
        <div class="firstLine">
            <div class="divInput">
                <label for="codeUV">Code d'UV</label> <input type="text" name="" id="codeUV" placeholder="Veuillez entrer le code de l'UV">
            </div>
            <div class="divInput">
                <label for="creneau">Créneau:</label>
                <select id="creneau" name="jourSemaine" required>
                    <option value="" disabled selected>Sélectionnez un créneau</option>
                    <option value="lundi">Lundi</option>
                    <option value="mardi">Mardi</option>
                    <option value="mercredi">Mercredi</option>
                    <option value="jeudi">Jeudi</option>
                    <option value="vendredi">Vendredi</option>
                    <option value="samedi">Samedi</option>
                </select>
            </div>
        </div>

        <div class="secondLine">
            <div class="divInput">
                <label for="input-hdebut">Heure début:</label>
                <input type="time" id="input-hdebut" name="hdebut">
            </div>
            <div class="divInput">
                <label for="input-hfin">Heure fin:</label>
                <input type="time" id="input-hfin" name="hfin">
            </div>
        </div>

        <div class="thirdLine">
            <div class="divInput">
                <label for="salle">Salle:</label>
                <input type="text" id="salle" name="salle" placeholder="Veuillez entrer la salle">
            </div>
            <div class="divInput">
                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="" disabled selected>Sélectionnez un type</option>
                    <option value="CM">CM</option>
                    <option value="TP">TP</option>
                    <option value="TD">TD</option>
                </select>
            </div>
        </div>

        <div class="fourLine">
            <div class="divSemaine"><input type="checkbox" name="semaine" id="semaine"><label for="semaine">Créneau une semaine sur deux</label></div>
        </div>

        <hr>
        <button type="button" onclick="alert('send')">Ajouter le créneau</button>
    </div>
</div>
</html>
