<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
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
    
    <script>
        window.addEventListener('load', function() {
            var lundi = document.getElementById("lundi").getElementsByClassName("endroit_cours")[0];
            var mardi = document.getElementById("mardi").getElementsByClassName("endroit_cours")[0];
            var mercredi = document.getElementById("mercredi").getElementsByClassName("endroit_cours")[0];
            var jeudi = document.getElementById("jeudi").getElementsByClassName("endroit_cours")[0];
            var vendredi = document.getElementById("vendredi").getElementsByClassName("endroit_cours")[0];
            var samedi = document.getElementById("samedi").getElementsByClassName("endroit_cours")[0];

            

            var debutEDT = 8;
            var finEDT = 20;

            let conteneurHours = document.getElementsByClassName("conteneurHours")[0];
            conteneurHours.className = "conteneurHours";

            for (var heure = debutEDT ; heure <= finEDT ; heure++){

                let textHour = document.createElement("h4");
                textHour.innerHTML = `${heure}h00`;
                textHour.style.height = "40px";
                textHour.id = `h${heure}`;

                conteneurHours.appendChild(textHour);
            }

            class Cours {
                constructor(codeUV, horaireDebut, horaireFin, jour, salle, semaine = null, couleur = null) {
                    this.codeUV = codeUV;
                    this.horaireDebut = horaireDebut;
                    this.horaireFin = horaireFin;
                    this.jour = jour;
                    this.salle = salle;
                    this.semaine = semaine;
                    this.couleur = couleur;
                }

                afficherDetails() {
                    console.log(`Code UV: ${this.codeUV}`);
                    console.log(`Horaire début: ${this.horaireDebut}`);
                    console.log(`Horaire fin: ${this.horaireFin}`);
                    console.log(`Jour: ${this.jour}`);
                    console.log(`Salle: ${this.salle}`);
                }
            }

            let cours1 = new Cours("MT23", "10h15", "12h15", "lundi", "FA104" , "B");
            let cours2 = new Cours("IC05", "12h30", "13h00", "mardi", "FA104");
            let cours3 = new Cours("MT02", "10h15", "11h15", "mardi", "FA104");
            let cours4 = new Cours("PS21", "14h15", "16h15", "vendredi", "FA104");
            let cours5 = new Cours("CACA", "08h00", "10h00", "mercredi", "FA104");
            let cours6 = new Cours("MT23", "11h00", "13h00", "lundi", "FA104" , "A");
            var colorList = [
                "#A7C4BC", "#D0C4B0", "#B0C4C4", "#C4A4B0", "#C4B8A4",
                "#B0C4B3", "#B0A4C4", "#C4B0A4", "#A4B0C4", "#C4B0B0",
                "#A4C4B0", "#C4B0C1", "#B0C1C4", "#C1C4A4", "#C4B0A7",
                "#A4B0C4", "#C4A790", "#A7C4B0", "#C4A4A4", "#B0A7C4"
            ];
            var coursColors = {};
            var liste = [cours1, cours2, cours3, cours4, cours5 , cours6]
            var endroit_cours;
            var cours;
            
            
            var listeJour = [lundi, mardi, mercredi, jeudi, vendredi, samedi];
            for (var i of listeJour) {
                for(var j=0; j<=48 ; j++){
                    if(j%4 === 0){
                        i.innerHTML+="<div class='dash' style='height:10px'></div>";
                    }else{
                        i.innerHTML+="<div style='height:1.5vh'></div>";
                    }
                }
                i.removeChild(i.getElementsByClassName('dash')[0]);
            }

            function calculDecimal(nombre) {
                var heuresMinutesDebut = nombre.split('h');
                var heuresDebut = parseInt(heuresMinutesDebut[0], 10);
                var minutesDebut = parseInt(heuresMinutesDebut[1], 10);

                return heuresDebut + minutesDebut / 60;
            }

            function calculPourcentage(nombre) {

                var pixel = nombre * tailleEDT / nbHeureEDT;
                return pixel * 100 / tailleEDT;
            }

            function getRandomColor(listOfColors) {
                if (listOfColors.length === 0) {
                    console.log("La liste de couleurs est vide !");
                    return null;
                }
                // Générer un index aléatoire
                const randomIndex = Math.floor(Math.random() * listOfColors.length);

                // Récupérer la couleur à l'index aléatoire
                const randomColor = listOfColors[randomIndex];

                // Supprimer la couleur de la liste
                listOfColors.splice(randomIndex, 1);

                // Retourner la couleur sélectionnée
                return randomColor;
            }

            var compteur=0;
            var lundiListe = [];
            var mardiListe = [];
            var mercrediListe = [];
            var jeudiListe = [];
            var vendrediListe = [];
            for (var i = 0; i < liste.length; i++) {
                
                if(liste[i].jour == "lundi"){
                    endroit_cours = lundi;
                }
                if(liste[i].jour == "mardi"){
                    endroit_cours = mardi;
                }
                if(liste[i].jour == "mercredi"){
                    endroit_cours = mercredi;
                }
                if(liste[i].jour == "jeudi"){
                    endroit_cours = jeudi;
                }
                if(liste[i].jour == "vendredi"){
                    endroit_cours = vendredi;
                }
                if(liste[i].jour == "samedi"){
                    endroit_cours = samedi;
                }

                if (!(liste[i].codeUV in coursColors)) {
                    coursColors[liste[i].codeUV] = getRandomColor(colorList);
                }
                liste[i].couleur = coursColors[liste[i].codeUV];

                endroit_cours.innerHTML += '<div class="cours"><h2 class="UV">' + '</h2><p>' + liste[i].horaireDebut + '-' + liste[i].horaireFin + '</p><p>' + liste[i].salle + '</p></div>'
                cours = endroit_cours.getElementsByClassName("cours")[endroit_cours.getElementsByClassName("cours").length -1];
                
                var tailleEDT = endroit_cours.offsetHeight;
                var nbHeureEDT = 12;
                var heureDebutEDT = 8;
                var heuresDecimalesDebut = calculDecimal(liste[i].horaireDebut);

                var heuresDecimalesFin = calculDecimal(liste[i].horaireFin);

                var tempsCours = heuresDecimalesFin - heuresDecimalesDebut;
                
                var pourcentageTop = calculPourcentage(heuresDecimalesDebut - heureDebutEDT);

                var pourcentageHeight = calculPourcentage(tempsCours);


                cours.style.height = pourcentageHeight + "%";
                cours.style.overflow = "hidden";

                cours.style.top = pourcentageTop + "%";

                cours.style.fontSize = pourcentageHeight * 4 + '%';
                cours.style.background = liste[i].couleur;

                if (liste[i].semaine == null){
                    cours.getElementsByClassName("UV")[0].innerHTML = liste[i].codeUV;
                } else {
                    if (liste[i].semaine === "B"){
                        cours.style.left = "50%";
                    }
                    cours.style.width = "50%";
                    cours.getElementsByClassName("UV")[0].innerHTML = liste[i].codeUV + ' - ' + liste[i].semaine;
                }

            };
        });
        


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
</html>
