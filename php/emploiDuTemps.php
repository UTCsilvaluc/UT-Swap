<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/emploiDuTemps.css">
    <title>Header avec Notification</title>
</head>
<body>
    <header>
        <img class="logo" src="../img/logo.png">
        <nav>
            <ul id="menu_liste_grand">
                <li><a href="#">Home</a></li>
                <li><a href="#">Swap</a></li>
                <li><a href="#">Demandes</a></li>
                <li><a href="#">Profil</a></li>
                <li><a href="#">Informations</a></li>
                <li><img class="notification" src="../svg/notif.svg"></li>
                <li><button class="bouton_nouveau"><img src="../svg/plus.svg">Nouveau</button></li>
            </ul>
            <ul id="menu_liste_petit">
                <li><img class="notification" src="../svg/notif.svg"></li>
                <li><img src="../svg/menu.svg" id="bouton_menu"></li>
            </ul>
        </nav>
    </header>
    
    <div id="notification_pannel">
        <h2>Notification</h2>
        <hr>
    </div>
    <div id="menu_pannel">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Swap</a></li>
            <li><a href="#">Demandes</a></li>
            <li><a href="#">Profil</a></li>
            <li><a href="#">Informations</a></li>
        </ul>
        <hr>
        <a href="#">
            <img class="profil" src="../svg/profil.svg">
            <h2>Nom Prénom</h2>
        </a>
        <button class="bouton_nouveau"><img src="../svg/plus.svg">Nouveau</button>
    </div>
    <div id="ecran"></div>

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
    <div id="nouveau_pannel">
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
                            <option value="UV2">
                            <option value="UV3">
                        </datalist>
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


    <article>
        <h2>YOles potes</h2>
        <p>efgjlhgdflukez grbiyuerzgfidz
            f elfiu glhezojyf 
             iueia hfpiueahgdf eaukygdf u
        </p>

    </article>
    <script>

        //Récupération des éléments désirés
        var notifications = document.getElementsByClassName("notification");
        var notification_pannel = document.getElementById("notification_pannel");
        var ecran = document.getElementById("ecran");

        var navBar = document.querySelector("nav");

        var bouton_menu = document.getElementById("bouton_menu");
        var menu_pannel = document.getElementById("menu_pannel");

        var bouton_nouveau = document.getElementsByClassName("bouton_nouveau");
        var nouveau_pannel = document.getElementById("nouveau_pannel");

        var largeurFenetre;
        var checkbox = document.getElementById('input-semaine');
        var choix_semaine = document.getElementById('choix-semaine');
        var lastHeight;
        // Ajoutez un écouteur d'événements pour détecter les changements de la checkbox
        checkbox.addEventListener('change', function () {
            // Modifiez la visibilité de l'élément en fonction de l'état de la checkbox
            choix_semaine.classList.toggle('hidden', !checkbox.checked);
            if (checkbox.checked) {
                lastHeight= nouveau_pannel.scrollHeight;
                nouveau_pannel.style.height = nouveau_pannel.scrollHeight + 10 + "px";
            } else {
                nouveau_pannel.style.height = lastHeight + "px"; // Ajustez ici la hauteur minimale souhaitée
            }
        });
        
        // Il y a le notification du menu PC et celui du menu téléphone donc on boucle pour tous les deux les selectionner
        for (var i = 0; i < notifications.length; i++) {
            notifications[i].addEventListener("click", function() {
                if(nouveau_pannel.style.display!="flex" && menu_pannel.style.left!="0"){
                    ecran.style.display = (ecran.style.display === "none" || ecran.style.display === "") ? "block" : "none";
                }
                nouveau_pannel.style.display = "none"
                largeurEcran = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

                //Si la largeur est inférieur à 750px alors il faut slide l'écran sinon il faux juste l'afficher 
                if (largeurEcran <= 750) {
                    notification_pannel.style.display = "block";
                    notification_pannel.style.right = (notification_pannel.style.right === "-90%" || notification_pannel.style.right === "") ? "0" : "-90%";                    
                } else {
                    //Tests pour contourner les bugs
                    if(notification_pannel.style.right != "-90%"){
                        notification_pannel.style.display = (notification_pannel.style.display === "none" || notification_pannel.style.display === "") ? "block" : "none";
                    }else{
                        notification_pannel.style.right = 0;
                    }
                }
                
                //Parfois il y a un bug avec les transitions lorsqu'on passe du format téléphone au format PC alors c'est pour vérifier
                if(ecran.style.display === "block"){
                    notification_pannel.style.right = "0";
                    notification_pannel.style.display = "block";
                }
                menu_pannel.style.left = '-65%';
            });
        };

        for (var i = 0; i < bouton_nouveau.length; i++) {
            bouton_nouveau[i].addEventListener("click", function() {
                largeurEcran = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
                if(notification_pannel.style.display != "block" && menu_pannel.style.left!="0"){
                    ecran.style.display = (ecran.style.display === "none" || ecran.style.display === "") ? "block" : "none";
                }
                nouveau_pannel.style.display = (nouveau_pannel.style.display === "none" || nouveau_pannel.style.display === "") ? "flex" : "none";                
                menu_pannel.style.left = '-65%';
                if (largeurEcran <= 750) {
                    notification_pannel.style.right = "-90%";
                } else {
                    notification_pannel.style.display = "none";
                    notification_pannel.style.right = 0;
                }                
            });
        };

        //Lorsqu'on clique en dehors du menu notif/navbar et en dehors du header ça ferme le menu
        ecran.addEventListener("click", function() {
            nouveau_pannel.style.display = "none"
            largeurEcran = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            ecran.style.display = "none";
            menu_pannel.style.left = '-65%';
            if (largeurEcran <= 750) {
                notification_pannel.style.right = "-90%";                    
            } else {
                notification_pannel.style.display = "none";
                notification_pannel.style.right = 0;
            }
        });

        //Pareil qu'au dessus mais pour navbar
        bouton_menu.addEventListener("click", function() {
            if(notification_pannel.style.display != "block" && menu_pannel.style.left!="0"){
                ecran.style.display = (ecran.style.display === "none" || ecran.style.display === "") ? "block" : "none";
            }

            nouveau_pannel.style.display = "none"
            
            menu_pannel.style.left = (menu_pannel.style.left <= '0' || menu_pannel.style.left === "") ? "0" : '-65%';
            
            largeurEcran = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            if (largeurEcran <= 750) {
                notification_pannel.style.right = "-90%";
            } else {
                notification_pannel.style.display = "none";
                notification_pannel.style.right = 0;
            }
            ecran.style.display = (ecran.style.display === "none" || ecran.style.display === "") ? "block" : "none";
        });

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
