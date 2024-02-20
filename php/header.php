<?php
session_start();
function DBCredential(){
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = 'root';
    $dbname = 'ut_swap';
    $connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');
    mysqli_set_charset($connect, 'utf8');
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
function convertirTemps($timestamp) {
    date_default_timezone_set('Europe/Paris');
    $maintenant = time();
    $difference = $maintenant - strtotime($timestamp);

    if ($difference < 60) {
        return $difference . "s";
    } elseif ($difference < 3600) {
        $minutes = floor($difference / 60);
        return $minutes . "min";
    } elseif ($difference < 86400) {
        $heures = floor($difference / 3600);
        return $heures . "h";
    } else {
        $jours = floor($difference / 86400);
        return $jours . "j";
    }
}
function nombreEnJour($chiffre){
    // Tableau de jours de la semaine à partir de lundi
    $joursSemaine = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi');

    // Vérifier si le chiffre est valide
    if ($chiffre >= 0 && $chiffre <= 4) {
        // Récupérer le jour correspondant au chiffre
        $jourSemaine = $joursSemaine[$chiffre];

        // Afficher le jour de la semaine
        return $jourSemaine;
    } else {
        return null;
    }
}
function sendNotifications($loginNotif, $loginPersonne, $idDemande, $demandeur, $type_notif, $statut, $connect){
    
    date_default_timezone_set('Europe/Paris');
    $sqlInsertNotif = "INSERT INTO notifications (loginEtu, typeNotif, idDemande, demandeur, contenuNotif, date, viewed) VALUES (?, ?, ?, ?, ?, NOW(), 0)";
    $stmtInsertNotif = $connect->prepare($sqlInsertNotif);

    $sqlSelectDemande1 = "SELECT d.codeUV, d.type, d.jour, d.horaireDebut, d.horaireFin, d.semaine, p.nom, p.prenom FROM demande d JOIN etudiant e ON e.login = d.login JOIN personne p ON p.login = e.login WHERE d.idDemande = ?";
    $stmtSelectDemande1 = $connect->prepare($sqlSelectDemande1);
    $stmtSelectDemande1->bind_param("s", $demandeur);
    $stmtSelectDemande1->execute();
    $stmtSelectDemande1->store_result();
    $stmtSelectDemande1->bind_result($codeUV1, $type1, $jour1, $horaireDebut1, $horaireFin1, $semaine1, $nom1, $prenom1);
    $stmtSelectDemande1->fetch();
    $personne1= ucfirst($prenom1)." ".ucfirst($nom1);

    $sqlSelectDemande2 = "SELECT d.codeUV, d.type, d.jour, d.horaireDebut, d.horaireFin, d.semaine, p.nom, p.prenom FROM demande d JOIN personne p ON p.login = d.login WHERE d.idDemande = ?";
    $stmtSelectDemande2 = $connect->prepare($sqlSelectDemande2);
    $stmtSelectDemande2->bind_param("s", $idDemande);
    $stmtSelectDemande2->execute();
    $stmtSelectDemande2->store_result();
    $stmtSelectDemande2->bind_result($codeUV2, $type2, $jour2, $horaireDebut2, $horaireFin2, $semaine2, $nom2, $prenom2);
    $stmtSelectDemande2->fetch();
    $personne2= ucfirst($prenom2)." ".ucfirst($nom2);
    if($semaine1 !== "null"){
        $semaine1 = " en semaine ".$semaine1;
    }else{
        $semaine1 = null;
    }
    if($type_notif === 1){
        $contenu_notif = "Vous avez une nouvelle demande de Swap !;".$personne1." serait intéréssé pour un Swap du ".$type1."".$semaine1." de ".$codeUV1." contre ".nombreEnJour($jour1)." de ".date("H\hi", strtotime($horaireDebut1))." à ".date("H\hi", strtotime($horaireFin1)).".";
    }else if($type_notif === 2){
        if($statut === 1){
            $contenu_notif = "Votre demande de Swap a été refusée.;".$personne2." a refusé la demande de Swap du ".$type2."".$semaine1." de ".$codeUV2." le ".nombreEnJour($jour2)." de ".date("H\hi", strtotime($horaireDebut2))." à ".date("H\hi", strtotime($horaireFin2)).".";
        }else if($statut === 2){
            $contenu_notif = "Votre demande de Swap a été acceptée.;".$personne2." a accepté la demande de Swap du ".$type2."".$semaine1." de ".$codeUV2." le ".nombreEnJour($jour2)." de ".date("H\hi", strtotime($horaireDebut2))." à ".date("H\hi", strtotime($horaireFin2)).".";
        }else if($statut === 3){
            $contenu_notif = "Votre demande de Swap a été rejetée par le reponsable de l'UV.; Le responsable de l'UV a rejeté la demande de Swap du ".$type2." ".$semaine2." de ".$codeUV2.".";
        }else if($statut === 4){
            $contenu_notif = "Votre demande de Swap a été approuvée par le reponsable de l'UV.; Le responsable de l'UV a approuvé la demande de Swap du ".$type2." ".$semaine2." de ".$codeUV2.".";
        }
    }else if($type_notif === 3){
        $contenu_notif = "Une nouvelle demande a été postée !"; //à continuer
    }

    // si il la notif n'a pas été envoyé alors le faire
    $sqlCheckNotif = "SELECT * FROM notifications WHERE loginEtu = ? AND typeNotif=? AND idDemande=? AND demandeur=? AND contenuNotif=?";
    $stmtCheckNotif = $connect->prepare($sqlCheckNotif);
    $stmtCheckNotif->bind_param("sssss", $loginNotif, $type_notif, $idDemande, $demandeur,$contenu_notif);
    $stmtCheckNotif->execute();
    $stmtCheckNotif->store_result();
    if ($stmtCheckNotif->num_rows === 0) {
        $stmtInsertNotif->bind_param("sssss", $loginNotif, $type_notif, $idDemande, $demandeur, $contenu_notif);
        $stmtInsertNotif->execute();
    }
    
}
function getResponsableByUv($uv){
    $responsableLogin = "antjouglet";
    $responsableNom = "Jouglet";
    $responsablePrénom = "Antoine";
    $responsableMail = "antoine.jouglet@utc.fr";
    return array('login' => $responsableLogin, 'nom' => $responsableNom,'prénom' => $responsablePrénom, 'mail' => $responsableMail);
}
$login = "ldompnie";
$connect = DBCredential();
if (isset($_SESSION['idDemande']) && isset($_SESSION['hDeb']) && isset($_SESSION['hFin']) && isset($_SESSION['salle']) && isset($_SESSION['jour']) && isset($_SESSION['semaine'])) {
    $sqlCheckInsertion = "UPDATE demande SET jour=?,horaireDebut=?, horaireFin=?, salle=?, semaine=? WHERE idDemande=?";
    $stmtCheckInsertion = $connect->prepare($sqlCheckInsertion);
    $stmtCheckInsertion->bind_param("issssi", $_SESSION['jour'], $_SESSION['hDeb'], $_SESSION['hFin'], $_SESSION['salle'], $_SESSION['semaine'], $_SESSION['idDemande']);
    if ($stmtCheckInsertion->execute()) {
        echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_insertion.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
    }else {
        echo "Erreur lors de l'insertion des données : " . $stmtCheckInsertion->error;
    }
    unset($_SESSION['jour']);
    unset($_SESSION['idDemande']);
    unset($_SESSION['hDeb']);
    unset($_SESSION['hFin']);
    unset($_SESSION['salle']);
    unset($_SESSION['semaine']);
    unset($_POST['uv'], $_POST['creneau'], $_POST['hdebut'], $_POST['hfin'], $_POST['salle'], $_POST['type']);
}
if (isset($_POST['view'])){
    $view = validateInput($_POST['view'],$connect);
    if($view === "1"){
        $sqlUpdateSwap = "UPDATE notifications SET viewed = 1 WHERE typeNotif != 1";
        $connect->query($sqlUpdateSwap);
        unset($_POST['view']);
    }
}
if (
    isset($_POST['choix'], $_POST['demandeur'], $_POST['idDemande'], $_POST['id_notif']) &&
    (!empty($_POST['choix']) || validateInput($_POST['choix'],$connect) === "0") && !empty($_POST['demandeur']) && !empty($_POST['idDemande']) && !empty($_POST['id_notif'])
) {
    // Valider les données
    $choix = validateInput($_POST['choix'],$connect);
    $demandeur = validateInput($_POST['demandeur'],$connect);
    $idDemande = validateInput($_POST['idDemande'],$connect);
    $idNotif = validateInput($_POST['id_notif'],$connect);

    $sqlCheckSwap = "SELECT d.login FROM notifications n JOIN demande d ON d.idDemande=n.demandeur WHERE n.idDemande = ? AND n.demandeur = ? AND n.idNotif = ? AND n.loginEtu = ?";
    $stmtCheckSwap = $connect->prepare($sqlCheckSwap);
    $stmtCheckSwap->bind_param("ssss", $idDemande, $demandeur, $idNotif, $login);
    $stmtCheckSwap->execute();
    $stmtCheckSwap->store_result();
    if ($stmtCheckSwap->num_rows !== 0) {
        $stmtCheckSwap->bind_result($loginDemandeur);
        $stmtCheckSwap->fetch();
        
        if($choix === "0"){
            $sqlUpdateSwap = "UPDATE swap SET statut = 1 WHERE idDemande = ? AND demandeur = ?";
            $choixTexte = "refusé";
            sendNotifications($loginDemandeur, $login, $idDemande, $demandeur, 2, $choix+1, $connect);
        } else if($choix === "1"){
            $sqlUpdateSwap = "UPDATE swap SET statut = 2 WHERE idDemande = ? AND demandeur = ?";
            $choixTexte = "accepté";
            sendNotifications($loginDemandeur, $login, $idDemande, $demandeur, 2, $choix+1, $connect);
        }
        $sqlSelectDemande = "SELECT codeUV, type, jour, horaireDebut, horaireFin, semaine FROM demande WHERE idDemande = ?";
        $stmtSelectDemande = $connect->prepare($sqlSelectDemande);
        $stmtSelectDemande->bind_param("s", $demandeur);
        $stmtSelectDemande->execute();
        $stmtSelectDemande->store_result();
        $stmtSelectDemande->bind_result($codeUV, $type, $jour, $horaireDebut, $horaireFin, $semaine);
        $stmtSelectDemande->fetch();

        $sqlUpdateNotif = "UPDATE notifications SET contenuNotif = ?, viewed=1 WHERE idNotif = ?";
        if($semaine !== "null"){
            $nouveauContenuNotif = "Vous avez ".$choixTexte." la demande de Swap.;La demande de swap du ".$type." en semaine ".$semaine." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été ".$choixTexte."e";
        }else{
            $nouveauContenuNotif = "Vous avez ".$choixTexte." la demande de Swap.;La demande de swap du ".$type." de ".$codeUV." pour ".nombreEnJour($jour)." ".date("H\hi", strtotime($horaireDebut))."-".date("H\hi", strtotime($horaireFin))." a été ".$choixTexte."e";
        }
        

        $stmtUpdateNotif = $connect->prepare($sqlUpdateNotif);
        $stmtUpdateNotif->bind_param("ss", $nouveauContenuNotif, $idNotif);
        $stmtUpdateNotif->execute();

        $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
        $stmtUpdateSwap->bind_param("ss", $idDemande, $demandeur);
        $stmtUpdateSwap->execute();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <header>
        <img class="logo" src="../img/logo.png">
        <nav>
            <ul id="menu_liste_grand">
                <li><a href="#">Home</a></li>
                <li><a href="emploiDuTemps.php">Swap</a></li>
                <li><a href="demandes.php">Demandes</a></li>
                <li><a href="#">Profil</a></li>
                <li><a href="#">Informations</a></li>
                <li><img class="notification" src="../svg/notif.svg">
<?php
$connect = DBCredential();
$sql = "SELECT * FROM notifications WHERE viewed = 0 AND typeNotif='1';";
$sql2 = "SELECT * FROM notifications WHERE viewed = 0 AND typeNotif='2';";

$resultat = $connect->query($sql);
$resultat2 = $connect->query($sql2);
if ($resultat->num_rows > 0 || $resultat2->num_rows > 0) {
    echo '<div class="cercle';
    if($resultat2->num_rows > 0){
        echo ' important"></div>';
    }else{
        echo '"></div>';
    }
}
?>
                </li>
                <li><button onclick="nouveauClick()" class="bouton_nouveau"><img src="../svg/plus.svg">Nouveau</button></li>
            </ul>
            <ul id="menu_liste_petit">
                <li><img class="notification" src="../svg/notif.svg">
<?php
$connect = DBCredential();
$sql = "SELECT * FROM notifications WHERE viewed = 0 AND typeNotif='1';";
$sql2 = "SELECT * FROM notifications WHERE viewed = 0 AND typeNotif='2';";

$resultat = $connect->query($sql);
$resultat2 = $connect->query($sql2);
if ($resultat->num_rows > 0 || $resultat2->num_rows > 0) {
    echo '<div class="cercle';
    if($resultat2->num_rows > 0){
        echo ' important"></div>';
    }else{
        echo '"></div>';
    }
}
?>
                </li>
                <li><img src="../svg/menu.svg" id="bouton_menu"></li>
            </ul>
        </nav>
    </header>
    
    <div id="notification_pannel">
        <div id="titre_notification">
            <h2>Notification</h2>
            <hr>
        </div>
        <div id="endroit_notification">
<?php
$connect = DBCredential();
$sql = "SELECT idNotif, typeNotif, contenuNotif, idDemande, demandeur, date, viewed FROM notifications ORDER BY date DESC;";
$resultat = $connect->query($sql);

// Vérifier s'il y a des résultats
if ($resultat->num_rows > 0) {
    // Afficher les options du datalist
    
    while ($row = $resultat->fetch_assoc()) {
        echo '<div class="notif type_'. $row["typeNotif"];
        if($row["viewed"]){
            echo  ' viewed">';
        }else{
            echo '">';
        }
        echo '<div class="endroit_texte_notif">';
        echo '<div class="user_importance_notification">';
        echo '<div class="image_profil_notification">';
        echo '<img src="../svg/profil.svg">';
        echo '<div class="cercle"></div>';
        echo '</div>';
        echo '</div>';
        $contenuNotif = explode(";", $row["contenuNotif"]);
        $titre_notif = $contenuNotif[0];
        $texte_notif = $contenuNotif[1];
        if($row["typeNotif"] === "1"){
            echo '<form class="texte_notification" method="POST">';
            $idDemande = $row["idDemande"];
            $demandeur = $row["demandeur"];
            echo '<input type="hidden" name="idDemande" value="'.$idDemande.'">';
            echo '<input type="hidden" name="demandeur" value="'.$demandeur.'">';
            echo '<input type="hidden" name="id_notif" value="'.$row["idNotif"].'">';
            echo '<input type="hidden" class="choix_notification" name="choix" value="0">';
        }else{
            echo '<div class="texte_notification">';
        }
        echo '<h1>'. $titre_notif .'</h1>';
        echo '<p>'. $texte_notif .'<p>';
        if($row["typeNotif"] === "1"){
            echo '<div><button class="bouton_refuser_notif">Refuser</button><button class="bouton_accepter_notif">Accepter</button></div>';
            echo '</form>';
        }else{
            echo '</div>';
        }
        echo '<div class="time_notification">';
        echo '<p>il y a '. convertirTemps($row["date"]) .'</p>';
        echo '</div>';
        echo '</div>';
        echo '<hr>';
        echo '</div>';
    }
}
?>
        </div>
    </div>
    <div id="menu_pannel">
        <div>
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
        </div>
        <button onclick="nouveauClick()" class="bouton_nouveau"><img src="../svg/plus.svg">Nouveau</button>
    </div>
    <div id="ecran"></div>
    
    <form action="#" method="post" id="nouveau_pannel">
        <div id="titre_nouveau">
            <h1>Nouvelle demande de Swap</h1>
            <hr>
        </div>
        <img src="../svg/croix.svg" id="croix_nouveau">
        <div id="div_debut_nouveau">
            <ul id="ul_nouveau">
                <li class="double-input">
                    <div>
                        <label for="input-uv">Code d'UV:<p class="hidden">*</p></label>
                        <input type="text" id="input-uv" list="uvs" name="uv" placeholder="Veuillez entrer le code de l'UV" >
                        <p class="hidden">UV non valide</p>
<?php
$connect = DBCredential();
$sql = "SELECT codeUV FROM uv";
$resultat = $connect->query($sql);

// Vérifier s'il y a des résultats
if ($resultat->num_rows > 0) {
    // Afficher les options du datalist
    echo '<datalist id="uvs">';
    while ($row = $resultat->fetch_assoc()) {
        echo '<option value="' . $row["codeUV"] . '">';
    }
    echo '</datalist>';
}
?>
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
                <li id="li_motivation">
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
                    <input type="radio" name="semainechoix" value="A" id="sA-choix" selected>
                    <label for="sA-choix">Semaine A</label>
                    <input type="radio" name="semainechoix" value="B" id="sB-choix">
                    <label for="sB-choix">Semaine B</label>
                    
                </li>
            </ul>
            <div class="hidden">
                <h1>Récapitulatif:</h1>
                <div>
                    <p>Créneau désiré -></p><div><p>UV:</p><p>MT23</p></div><div><p>Type:</p><p>TD</p></div>
                </div>
            </div>
            <p id="message_uv_type" class="hidden">Malheureusement vous avez déjà réalisé une demande pour ce type et cet UV</p>
            <p id="message_pression" class="hidden">Assurez-vous de la validité ainsi que de la possession du créneau renseigné. Des incohérences répétées pourraient entraîner des sanctions, y compris le bannissement.</p>
            <p id="message_impossible_uv" class="hidden">Nous sommes désolé mais le responsable de cette UV a désactivé les changements de créneaux. Aucune demande n'est donc possible...</p>
            <p id="message_insertion" class="hidden">La demande a été envoyée !!</p>
        </div>
        <div id="div_fin_nouveau">
            <hr>
            <button id="bouton_ajouter_creneau" class="hidden" id="bouton_ok">Ajouter</button>
            <button onclick="nouveauClick()" class="bouton_nouveau hidden" id="bouton_ok">OK !</button>
            <button id="bouton_non_submit">Poster</button>
            <div id="boutons_uv" >
                <button id="bouton_impossible_uv" onclick="nouveauClick()" class="bouton_nouveau hidden">Abandonner</button>
                <button id="bouton_remplacer" onclick="reloadPage()" class="hidden">Remplacer</button>
            </div>
            <div id="boutons_message" class="hidden">
                <button id="bouton_retour">Retour</button>
                <input type="submit" value="Poster la demande" id="submit_fin_nouveau">
            </div>
        </div>
    </form>
    
    
    <script src="../js/header.js"></script>
<?php
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
                echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_impossible_uv.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);</script>";
                
            }
        }

        if($swap_uv === 1){
            // Récupérez la valeur du choix de semaine seulement si la case à cocher est cochée
            $semaineChoix = $creneauUneSemaine && isset($_POST['semainechoix']) ? validateInput($_POST['semainechoix'],$connect) : 'null';
            // Préparez la requête SQL d'insertion
            $jour = jourEnNombre($creneau);
            $sqlCheckInsertion = "SELECT idDemande FROM demande WHERE login = ? and type=? and codeUV = ?";
            $stmtCheckInsertion = $connect->prepare($sqlCheckInsertion);
            $stmtCheckInsertion->bind_param("sss", $login, $type, $uv);
            $stmtCheckInsertion->execute();
            $stmtCheckInsertion->store_result();
            if ($stmtCheckInsertion->num_rows === 0) {
                $insertion = $connect->prepare("INSERT INTO demande (login, codeUV, type, jour, horaireDebut, horaireFin, salle, semaine, raison, demande) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
                
                $raison = validateInput($_POST['motivation'],$connect);
                $demande = 1;
                // Liez les valeurs aux paramètres de la requête
                $insertion->bind_param("sssisssssi", $login, $uv, $type, $jour, $hdebut, $hfin, $salle, $semaineChoix, $raison, $demande);
                // Exécutez la requête
                if ($insertion->execute()) {
                    echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_insertion.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
                }else {
                    echo "Erreur lors de l'insertion des données : " . $insertion->error;
                }
                // Fermez la connexion
                $insertion->close();
            }else{
                $stmtCheckInsertion->bind_result($idDemande);
                $stmtCheckInsertion->fetch();
                echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_uv_type.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);bouton_remplacer.classList.toggle('hidden', false);</script>";
                $_SESSION["idDemande"] = $idDemande;
                $_SESSION["hDeb"] = $hdebut;
                $_SESSION["hFin"] = $hfin;
                $_SESSION["salle"] = $salle;
                $_SESSION["jour"] = $jour;
                $_SESSION["semaine"] = $semaineChoix;
            }
        }
        $connect->close();
    }

    
}
?>
</body>
</html>