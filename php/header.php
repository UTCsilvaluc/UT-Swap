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
    
    <form action="#" method="post" id="nouveau_pannel">
        <div id="titre_nouveau">
            <h1>Nouvelle demande de Swap</h1>
            <hr>
        </div>
        <div id="div_debut_nouveau">
            <ul id="ul_nouveau">
                <li class="double-input">
                    <div>
                        <label for="input-uv">Code d'UV:<p class="hidden">*</p></label>
                        <input type="text" id="input-uv" list="uvs" name="uv" placeholder="Veuillez entrer le code de l'UV" >
                        <p class="hidden">UV non valide</p>
                        <datalist id="uvs">
                            <option value="UV1">
                            <option value="UV2">
                            <option value="UV3">
                        </datalist>
                    </div>
                    <div>
                        <label for="input-creneau">Créneau:<p class="hidden">*</p></label>
                        <select id="input-creneau" name="creneau" >
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
                    <div class="nouveau_heure_triple">
                        <label for="input-hdebut2">Heure début:<p class="hidden">*</p></label>
                        <input type="time" class="input-hdebut" name="hdebut" id="input-hdebut2" >
                        <p class="hidden">Heures non valide</p>
                    </div>
                </li>
                <li class="double-input" id="nouveau_heure_double">
                    <div>
                        <label for="input-hdebut1">Heure début:<p class="hidden">*</p></label>
                        <input type="time" class="input-hdebut" name="hdebut" id="input-hdebut1" >
                        <p class="hidden">Heures non valide</p>
                    </div>
                    <div>
                        <label for="input-hfin1">Heure fin:<p class="hidden">*</p></label>
                        <input type="time" class="input-hfin" name="hfin" id="input-hfin1" >
                    </div>
                </li>
                <li class="double-input">
                    <div>
                        <label for="input-salle">Salle:<p class="hidden">*</p></label>
                        <input type="text" id="input-salle" name="salle" placeholder="Veuillez entrer votre salle" >
                        <p class="hidden">Salle non valide</p>
                    </div>
                    <div>
                        <label for="input-type">Type:<p class="hidden">*</p></label>
                        <select id="input-type" name="type" >
                            <option value="" disabled selected>Sélectionnez un type</option>
                            <option value="TD">TD</option>
                            <option value="TP">TP</option>
                            <option value="CM">Cours</option>
                        </select>
                        <p class="hidden">Type non valide</p>
                    </div>
                    <div class="nouveau_heure_triple">
                        <label for="input-hfin2">Heure fin:<p class="hidden">*</p></label>
                        <input type="time" class="input-hfin" name="hfin" id="input-hfin2" >
                    </div>
                </li>
                <li>
                    <div>
                        <label for="input-motivation">Motivation: (facultatif)</label>
                        <input type="text" id="input-motivation" name="motivation" placeholder="Veuillez entrer votre motivation">
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
            <p id="message_impossible_uv" class="hidden">Nous sommes désolé mais le responsable de cette UV a désactivé les changements de créneaux. Aucune demande n'est donc possible...</p>
            <p id="message_insertion" class="hidden">La demande a été envoyée !!</p>
        </div>
        <div id="div_fin_nouveau">
            <hr>
            <button class="bouton_nouveau hidden" id="bouton_ok">OK !</button></li>
            <button id="bouton_non_submit">Poster la demande</button>
            <button id="bouton_impossible_uv" class="bouton_nouveau hidden">Abandonner la demande</button>
            <div id="boutons_message" class="hidden">
                <button id="bouton_retour">Retour</button>
                <input type="submit" value="Poster la demande" id="submit_fin_nouveau">
            </div>
        </div>
    </form>
    
    
    <script src="../js/header.js"></script>
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

            $swap_uv = 1;

            // Vérifier si l'UV existe
            $sqlCheckUV = "SELECT swap FROM uv WHERE codeUV = ?";
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
            }else{
                $stmtCheckUV->bind_result($swap_uv);
                $stmtCheckUV->fetch();
                if($swap_uv === 0){
                    echo "<script>ecran.style.display = 'block';nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_impossible_uv.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);</script>";
                    
                }
            }

            if($swap_uv === 1){
                // Récupérez la valeur du choix de semaine seulement si la case à cocher est cochée
                $semaineChoix = $creneauUneSemaine && isset($_POST['semainechoix']) ? validateInput($_POST['semainechoix'],$connect) : 'null';
                mysqli_set_charset($connect, 'utf8');
                // Préparez la requête SQL d'insertion
                $insertion = $connect->prepare("INSERT INTO demande (login, codeUV, type, jour, horaireDebut, horaireFin, salle, semaine, raison, demande) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $login = "ldompnie";
                $jour = jourEnNombre($creneau);
                $raison = validateInput($_POST['motivation'],$connect);
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
            }
            $connect->close();
        }

        
    }
?>
</body>
</html>