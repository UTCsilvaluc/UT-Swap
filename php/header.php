<?php    
    function DBCredential(){
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = 'root';
        $dbname = 'ut_swap';
        $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
        return $connect;
    }

    function validateInput($input, $connect) {
        $input = trim($input); // Supprime les espaces en début et fin de chaîne
        $input = stripslashes($input); // Supprime les antislashs ajoutés par addslashes
        $input = htmlspecialchars($input); // Convertit les caractères spéciaux en entités HTML
        $input = $connect->real_escape_string($input);
        return $input;
    }

    function jourEnNombre($jour) {
        $jours = array(
            'lundi' => 1,
            'mardi' => 2,
            'mercredi' => 3,
            'jeudi' => 4,
            'vendredi' => 5,
            'samedi' => 6
        );
    
        // Convertir le jour en minuscules pour éviter les problèmes de casse
        $jour = strtolower($jour);
    
        // Vérifier si le jour existe dans le tableau
        if (array_key_exists($jour, $jours)) {
            return $jours[$jour];
        } else {
            // Retourner une valeur par défaut ou gérer l'erreur selon vos besoins
            return null;
        }
    }   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <header>
        <img class="logo" src="logo.png">
        <nav>
            <ul id="menu_liste_grand">
                <li><a href="#">Home</a></li>
                <li><a href="#">Swap</a></li>
                <li><a href="#">Demandes</a></li>
                <li><a href="#">Profil</a></li>
                <li><a href="#">Informations</a></li>
                <li><img class="notification" src="notif.svg"></li>
                <li><button class="bouton_nouveau"><img src="plus.svg">Nouveau</button></li>
            </ul>
            <ul id="menu_liste_petit">
                <li><img class="notification" src="notif.svg"></li>
                <li><img src="menu.svg" id="bouton_menu"></li>
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
            <img class="profil" src="profil.svg">
            <h2>Nom Prénom</h2>
        </a>
        <button class="bouton_nouveau"><img src="plus.svg">Nouveau</button>
    </div>
    <div id="ecran"></div>
    
    <form action="#" method="post" id="nouveau_pannel">
        <div id="titre_nouveau">
            <h1>Nouvelle demande de Swap</h1>
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
    
    
    <script>
        var bouton_non_submit = document.getElementById("bouton_non_submit");
        var ul_nouveau = document.getElementById("ul_nouveau");
        var boutons_message = document.getElementById("boutons_message");
        var message_pression = document.getElementById("message_pression");
        var bouton_retour = document.getElementById("bouton_retour");
        var input_type = document.getElementById("input-type");
        var input_salle = document.getElementById("input-salle");
        var input_hfin = document.getElementById("input-hfin");
        var input_hdebut = document.getElementById("input-hdebut");
        var input_creneau = document.getElementById("input-creneau");
        var input_uv = document.getElementById("input-uv");
        var message_insertion = document.getElementById('message_insertion');
        var bouton_ok = document.getElementById('bouton_ok');

        var list_input=[input_creneau,input_type,input_salle,input_hfin,input_hdebut,input_uv];

        input_hfin.addEventListener('change', function() {
            var heureActuelle = this.value;

            var [heures, minutes] = heureActuelle.split(':');

            minutes = Math.round(minutes / 15) * 15;

            if (minutes === 60) {
                heures = parseInt(heures, 10) + 1;
                minutes = 0;
            }

            this.value = heures.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');
        });

        input_hdebut.addEventListener('change', function() {
            var heureActuelle = this.value;

            var [heures, minutes] = heureActuelle.split(':');

            minutes = Math.round(minutes / 15) * 15;

            if (minutes === 60) {
                heures = parseInt(heures, 10) + 1;
                minutes = 0;
            }

            this.value = heures.toString().padStart(2, '0') + ':' + minutes.toString().padStart(2, '0');
        });
        

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

        bouton_non_submit.addEventListener("click", function() {
            event.preventDefault();
            var type = encodeURIComponent(input_type.value);
            var salle = encodeURIComponent(input_salle.value);
            var creneau = encodeURIComponent(input_creneau.value);
            var uv = encodeURIComponent(input_uv.value);
            var hfin = encodeURIComponent(input_hfin.value);
            var hdebut = encodeURIComponent(input_hdebut.value);
            
            if(type === "" || salle === "" || hfin === "" || hdebut === "" || creneau === "" || uv === ""){
                for(var element of list_input){
                    var pElement = element.parentNode.querySelector("p")
                    if(encodeURIComponent(element.value) === ""){
                        pElement.classList.toggle('hidden', false);
                    }else{
                        pElement.classList.toggle('hidden', true);
                    }
                }
                bouton_non_submit.classList.add("shake-element");
                setTimeout(function() {
                    bouton_non_submit.classList.remove("shake-element");
                }, 200);
            }else if(uv.length != 4){
                input_uv.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
            }else if(!["TD","TP","CM"].includes(type)){
                input_uv.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
                input_type.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
            }else if(!["lundi","mardi","mercredi","jeudi","vendredi","samedi"].includes(creneau)){
                input_type.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
                input_creneau.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
            }else if(hdebut >= hfin){
                input_creneau.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
                input_hdebut.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', false);
            }else{
                input_hdebut.parentNode.getElementsByTagName("p")[1].classList.toggle('hidden', true);
                for(var element of list_input){
                    var pElement = element.parentNode.querySelector("p");
                    pElement.classList.toggle('hidden', true);
                }
                bouton_non_submit.classList.toggle('hidden', true);
                ul_nouveau.classList.toggle('hidden', true);
                boutons_message.classList.toggle('hidden', false);
                message_pression.classList.toggle('hidden', false);
            }
        });

        bouton_retour.addEventListener("click", function() {
            event.preventDefault();
            bouton_non_submit.classList.toggle('hidden', false);
            ul_nouveau.classList.toggle('hidden', false);
            boutons_message.classList.toggle('hidden', true);
            message_pression.classList.toggle('hidden', true);
        });
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
                bouton_ok.classList.toggle('hidden', true);
                message_insertion.classList.toggle('hidden', true);
                bouton_non_submit.classList.toggle('hidden', false);
                ul_nouveau.classList.toggle('hidden', false);
                boutons_message.classList.toggle('hidden', true);
                message_pression.classList.toggle('hidden', true);
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
    <?php
    

    function getResponsableByUv($uv){
        $responsableLogin = "antjouglet";
        $responsableNom = "Jouglet";
        $responsablePrénom = "Antoine";
        $responsableMail = "antoine.jouglet@utc.fr";
        return array('login' => $responsableLogin, 'nom' => $responsableNom,'prénom' => $responsablePrénom, 'mail' => $responsableMail);
    }
    // Vérifier si les variables sont définies et non vides
    if (
        isset($_POST['uv'], $_POST['creneau'], $_POST['hdebut'], $_POST['hfin'], $_POST['salle'], $_POST['type']) &&
        !empty($_POST['uv']) && !empty($_POST['creneau']) && !empty($_POST['hdebut']) && !empty($_POST['hfin']) && !empty($_POST['salle']) && !empty($_POST['type'])
    ) {
        
        $connect = DBCredential();
        // Valider les données
        $uv = validateInput($_POST['uv'],$connect);
        $creneau = validateInput($_POST['creneau'],$connect);
        $hdebut = validateInput($_POST['hdebut'],$connect);
        $hfin = validateInput($_POST['hfin'],$connect);
        $salle = validateInput($_POST['salle'],$connect);
        $type = validateInput($_POST['type'],$connect);
    
        


        if(strlen($uv) != 4){
            header("Location: erreur.php");
            exit();
        }else if(!in_array($type,array("TD","TP","CM"))){
            header("Location: erreur.php");
            exit();
        }else if(!in_array($creneau,array("lundi","mardi","mercredi","jeudi","vendredi","samedi"))){
            header("Location: erreur.php");
            exit();
        }else if($hdebut >= $hfin){
            header("Location: erreur.php");
            exit();
        }else{
            // Récupérez la valeur de la case à cocher
            $creneauUneSemaine = isset($_POST['semaine']) ? 1 : 0;
            $responsable= getResponsableByUv($uv);
            $responsableLogin = $responsable["login"];
            $responsableNom = $responsable["nom"];
            $responsablePrénom = $responsable["prénom"];
            $responsableMail = $responsable["mail"];

            // Vérifier si l'UV existe
            $sqlCheckUV = "SELECT codeUV FROM uv WHERE codeUV = ?";
            $stmtCheckUV = $connect->prepare($sqlCheckUV);
            $stmtCheckUV->bind_param("s", $uv);
            $stmtCheckUV->execute();
            $stmtCheckUV->store_result();
            if ($stmtCheckUV->num_rows === 0) {
                $sqlCheckResponsable = "SELECT login FROM professeur WHERE login = ?";
                $stmtCheckResponsable = $connect->prepare($sqlCheckResponsable);
                $stmtCheckResponsable->bind_param("s", $responsableLogin);
                $stmtCheckResponsable->execute();
                $stmtCheckResponsable->store_result();

                // Si le responsable n'existe pas, le créer
                if ($stmtCheckResponsable->num_rows === 0) {
                    // Vérifier si la personne existe
                    $sqlCheckPersonne = "SELECT login FROM personne WHERE login = ?";
                    $stmtCheckPersonne = $connect->prepare($sqlCheckPersonne);
                    $stmtCheckPersonne->bind_param("s", $responsableLogin);
                    $stmtCheckPersonne->execute();
                    $stmtCheckPersonne->store_result();
                    // Si la personne n'existe pas, la créer
                    if ($stmtCheckPersonne->num_rows === 0) {
                        $sqlInsertPersonne = "INSERT INTO personne (login, nom, prenom, mail) VALUES (?, ?, ?, ?)";
                        $stmtInsertPersonne = $connect->prepare($sqlInsertPersonne);
                        $stmtInsertPersonne->bind_param("ssss", $responsableLogin, $responsableNom, $responsablePrénom, $responsableMail);
                        $stmtInsertPersonne->execute();
                    }

                    $sqlInsertResponsable = "INSERT INTO professeur (login, notif) VALUES (?, 0)";
                    $stmtInsertResponsable = $connect->prepare($sqlInsertResponsable);
                    $stmtInsertResponsable->bind_param("s", $responsableLogin);
                    $stmtInsertResponsable->execute();
                }
                $sqlInsertUV = "INSERT INTO uv (codeUV, responsable, swap) VALUES (?, ?, 1)";
                $stmtInsertUV = $connect->prepare($sqlInsertUV);
                $stmtInsertUV->bind_param("ss", $uv, $responsableLogin);
                $stmtInsertUV->execute();
            }

            // Récupérez la valeur du choix de semaine seulement si la case à cocher est cochée
            $semaineChoix = $creneauUneSemaine && isset($_POST['semainechoix']) ? validateInput($_POST['semainechoix'],$connect) : 'null';
            mysqli_set_charset($connect, 'utf8');
            // Préparez la requête SQL d'insertion
            $insertion = $connect->prepare("INSERT INTO demande (login, codeUV, type, jour, horaireDebut, horaireFin, salle, semaine, raison, demande) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $login = "ldompnie";
            $jour = jourEnNombre($creneau);
            $raison = "raison";
            $demande = 1;
            // Liez les valeurs aux paramètres de la requête
            $insertion->bind_param("sssisssssi", $login, $uv, $type, $jour, $hdebut, $hfin, $salle, $semaineChoix, $raison, $demande);

            // Exécutez la requête
            if ($insertion->execute()) {
                echo "<script>ecran.style.display = 'block';nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_insertion.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
            }else {
                echo "Erreur lors de l'insertion des données : " . $insertion->error;
            }

            // Fermez la connexion
            $insertion->close();
            $connect->close();
        }

        
    }
?>
</body>
</html>