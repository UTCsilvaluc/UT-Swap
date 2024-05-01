<?php
include "../utils/db_functions.php";
include "../utils/header_utils.php";
include "../utils/utils.php";
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

function consoleLog($value){
    echo "<script>console.log('".$value."')</script>";
}

$connect = DBCredential();
if (isset($_POST['login'], $_POST['nom'], $_POST['prenom']) && !empty($_POST['login']) && !empty($_POST['nom']) && !empty($_POST['prenom'])){
    $loginEtu = validateInput($_POST['login'],$connect);
    $prenom = validateInput($_POST['prenom'],$connect);
    $nom = validateInput($_POST['nom'],$connect);
    if(strlen($loginEtu) == 8){
        $sqlSelectLogin = "SELECT * FROM personne WHERE login = ?";
        $stmtSelectLogin = $connect->prepare($sqlSelectLogin);
        $stmtSelectLogin->bind_param("s", $loginEtu);
        $stmtSelectLogin->execute();
        $stmtSelectLogin->store_result();
        if ($stmtSelectLogin->num_rows === 0) {
            $mail = $prenom.".".$nom."@etu.utc.fr";
            $sqlInsertPersonne = "INSERT INTO `ut_swap`.`personne` (`login`, `nom`, `prenom`, `mail`) VALUES (?,?,?,?);";
            $stmtInsertPersonne = $connect->prepare($sqlInsertPersonne);
            $stmtInsertPersonne->bind_param("ssss", $loginEtu, $nom, $prenom, $mail);
            $stmtInsertPersonne->execute();
            $sqlInsertEtudiant = "INSERT INTO `ut_swap`.`etudiant` (`login`, `alert`, `reception`, `progress`) VALUES (?,1,1,1);";
            $stmtInsertEtudiant = $connect->prepare($sqlInsertEtudiant);
            $stmtInsertEtudiant->bind_param("s", $loginEtu);
            $stmtInsertEtudiant->execute();
        }
        $_SESSION["login"] = $loginEtu;
    }
}
$login;

$current_uri = $_SERVER['REQUEST_URI'];

if(isset($_SESSION["login"]) && !empty($_SESSION['login'])){
    $login = $_SESSION["login"];
}else if(strpos($current_uri, "login.php") === false){
    header("Location: login.php");
    exit;
}
if(strpos($current_uri, "profil.php")){
    $sqlSelectProf = "SELECT * FROM professeur WHERE login = ?";
    $stmtSelectProf = $connect->prepare($sqlSelectProf);
    $stmtSelectProf->bind_param("s", $login);
    $stmtSelectProf->execute();
    $stmtSelectProf->store_result();
    if ($stmtSelectProf->num_rows !== 0) {
        header("Location: gestion.php");
        exit;
    }
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
function sendNotifications($loginNotif, $idDemande, $demandeur, $type_notif, $choix, $connect){

    date_default_timezone_set('Europe/Paris');
    // si la notif n'a pas été envoyé alors le faire
    $sqlCheckNotif = "SELECT * FROM notifications WHERE login = ? AND typeNotif=? AND idDemande=? AND demandeur=? AND choix=?";
    $stmtCheckNotif = $connect->prepare($sqlCheckNotif);
    $stmtCheckNotif->bind_param("sssss", $loginNotif, $type_notif, $idDemande, $demandeur,$choix);
    $stmtCheckNotif->execute();
    $stmtCheckNotif->store_result();
    if ($stmtCheckNotif->num_rows === 0) {
        $sqlInsertNotif = "INSERT INTO notifications (login, typeNotif, idDemande, demandeur, choix, date, viewed) VALUES (?, ?, ?, ?, ?, NOW(), 0)";
        $stmtInsertNotif = $connect->prepare($sqlInsertNotif);
        $stmtInsertNotif->bind_param("sssss", $loginNotif, $type_notif, $idDemande, $demandeur, $choix);
        $stmtInsertNotif->execute();
    }
}

function getResponsableByUv($uv){
    $responsableLogin = "antjougl";
    $responsableNom = "Jouglet";
    $responsablePrénom = "Antoine";
    $responsableMail = "antoine.jouglet@utc.fr";
    return array('login' => $responsableLogin, 'nom' => $responsableNom,'prénom' => $responsablePrénom, 'mail' => $responsableMail);
}

if (isset($_POST['view'])){
    $view = validateInput($_POST['view'],$connect);
    if($view === "1"){
        $sqlUpdateSwap = "UPDATE notifications SET viewed = 1 WHERE login = ? AND typeNotif != 1";
        $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
        $stmtUpdateSwap->bind_param("s", $login);
        $stmtUpdateSwap->execute();
        unset($_POST['view']);
    }
}
if (
    isset($_POST['choix'], $_POST['demandeur'], $_POST['idDemande'], $_POST['id_notif']) &&
    (!empty($_POST['choix']) || validateInput($_POST['choix'],$connect) === "0") && !empty($_POST['demandeur']) && !empty($_POST['idDemande']) && !empty($_POST['id_notif'])
){
    updateSwapInsertNotif($_POST['choix'], $_POST['demandeur'], $_POST['idDemande'], $_POST['id_notif'], $login, $connect);
    unset($_POST['choix']);
    unset($_POST['demandeur']);
    unset($_POST['idDemande']);
    unset($_POST['id_notif']);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

//Fonction servant à générer un rond orange ou vert au dessus de l'icone de notification selon l'importance des notifs
function notificationImportance(){
    global $login;
    $connect = DBCredential();
    $sql = "SELECT * FROM notifications WHERE viewed = 0 AND login = ? AND (typeNotif=1 OR typeNotif=6)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $resultat = $stmt->get_result();
    $sql2 = "SELECT * FROM notifications WHERE viewed = 0 AND login = ? AND typeNotif IN (2,3,4,5);";
    $stmt2 = $connect->prepare($sql2);
    $stmt2->bind_param("s", $login);
    $stmt2->execute();
    $resultat2 = $stmt2->get_result();
    if ($resultat->num_rows > 0 || $resultat2->num_rows > 0) {
        $classeDiv = "cercle";
        if ($resultat2->num_rows > 0) {
            $classeDiv .= " orange";
        }
        if($resultat->num_rows > 0){
            $classeDiv .= " vert";
        }
        echo '<div class="' . $classeDiv . '"></div>';
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
    <img class="logo" src="../img/logo23.png">
    <nav>
        <ul id="menu_liste_grand">
            <li><a href="home.php">Home</a></li>
            <li><a href="emploiDuTemps.php">Swap</a></li>
            <li><a href="demandes.php">Demandes</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="#">Informations</a></li>
            <!-- Code pour afficher s'il y a une notif importante ou non --> 
            <li onclick="notificationClick()"><img class="notification" src="../svg/notif.svg">
                <?php
                notificationImportance();
                ?>
            </li>
            <li><button onclick="nouveauClick()" class="bouton_nouveau"><img src="../svg/plus.svg">Nouveau</button></li>
        </ul>
        <ul id="menu_liste_petit">
            <li onclick="notificationClick()"><img class="notification" src="../svg/notif.svg">
            <?php
                notificationImportance();
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
        $sqlSelectNotif = "SELECT n.idNotif, n.typeNotif, n.choix, n.idDemande, n.demandeur, n.date, n.viewed, d1.codeUV, d1.type, d1.jour as jour1, d2.jour as jour2, d1.horaireDebut as hDeb1, d1.horaireFin as hFin1, d2.horaireDebut as hDeb2, d2.horaireFin as hFin2, d1.semaine as semaine1, d2.semaine as semaine2, p1.nom as nom1, p1.prenom as prenom1, p2.nom as nom2, p2.prenom as prenom2 FROM notifications n JOIN demande d1 ON d1.idDemande = n.idDemande JOIN personne p1 ON p1.login = d1.login JOIN demande d2 ON d2.idDemande = n.demandeur JOIN personne p2 ON p2.login = d2.login WHERE n.login=? ORDER BY n.date DESC;";
        $stmtSelectNotif = $connect->prepare($sqlSelectNotif);
        $stmtSelectNotif->bind_param("s", $login);
        $stmtSelectNotif->execute();
        $resultat = $stmtSelectNotif->get_result();
        // Vérifier s'il y a des résultats
        if ($resultat->num_rows > 0) {
            // Afficher les options du datalist

            foreach ($resultat as $row) {
                $idNotif = $row["idNotif"];
                $typeNotif = $row["typeNotif"];
                $choix = $row["choix"];
                $idDemande = $row["idDemande"];
                $demandeur = $row["demandeur"];
                $date = $row["date"];
                $viewed = $row["viewed"];
                $codeUV = $row["codeUV"];
                $type = $row["type"];
                $jour1 = $row["jour1"];
                $jour2 = $row["jour2"];
                $hDeb1 = $row["hDeb1"];
                $hFin1 = $row["hFin1"];
                $hDeb2 = $row["hDeb2"];
                $hFin2 = $row["hFin2"];
                $semaine1 = $row["semaine1"];
                if($semaine1 !== "null"){
                    $semaine1 = " en semaine ".$semaine1;
                }else{
                    $semaine1 = null;
                }
                $semaine2 = $row["semaine2"];
                if($semaine2 !== "null"){
                    $semaine2 = " en semaine ".$semaine2;
                }else{
                    $semaine2 = null;
                }
                $nom1 = $row["nom1"];
                $prenom1 = $row["prenom1"];
                $nom2 = $row["nom2"];
                $prenom2 = $row["prenom2"];
                $personne1= ucfirst($prenom1)." ".ucfirst($nom1);
                $personne2= ucfirst($prenom2)." ".ucfirst($nom2);
                echo '<div class="notif type_'. $row["typeNotif"];
                if($row["viewed"] === 1){
                    echo  ' viewed">';
                }else{
                    echo '">';
                }
                $contenuNotif = explode(";", $row["contenuNotif"]);
                if($row["typeNotif"] === "1"){
                    if($row["viewed"] === 1){
                        if($row["choix"] === 0){
                            $choixTexte = "refusé";
                            
                        }else if($row["choix"] === 1){
                            $choixTexte = "refusé";
                        }
                        $titre_notif = "Vous avez ".$choixTexte." la demande de Swap de ".$personne2.".";
                        $texte_notif = "La demande de swap du ".$type.$semaine2." de ".$codeUV." pour ".nombreEnJour($jour2)." ".date("H\hi", strtotime($hDeb2))."-".date("H\hi", strtotime($hFin))." a été ".$choixTexte."e";
                    }else{
                        $titre_notif = "Vous avez une nouvelle demande de Swap !";
                        $texte_notif = $personne2." serait intéréssé pour un Swap du ".$type."".$semaine2." de ".$codeUV." contre ".nombreEnJour($jour2)." de ".date("H\hi", strtotime($hDeb2))." à ".date("H\hi", strtotime($hFin2)).".";
                    }
                }else if($row["typeNotif"] === "2"){
                    if($row["choix"] === 0){
                        $titre_notif = "Votre demande de Swap a été refusée.";
                        $texte_notif = $personne1." a refusé la demande de Swap du ".$type."".$semaine1." de ".$codeUV." le ".nombreEnJour($jour1)." de ".date("H\hi", strtotime($hDeb1))." à ".date("H\hi", strtotime($hFin1)).".";
                    }else if($row["choix"] === 1){
                        $titre_notif = "Votre demande de Swap a été acceptée.";
                        $texte_notif = $personne1." a accepté la demande de Swap du ".$type."".$semaine1." de ".$codeUV." le ".nombreEnJour($jour1)." de ".date("H\hi", strtotime($hDeb1))." à ".date("H\hi", strtotime($hFin1)).".";
                    }
                }else if($row["typeNotif"] === "3"){
                    if($row["choix"] === 0){
                        $titre_notif = "Votre demande de Swap a été rejetée par le reponsable de l'UV.";
                        $texte_notif = "Le responsable de l'UV a rejeté la demande de Swap du ".$type." de ".$codeUV.".";
                    }else if($row["choix"] === 1){
                        $titre_notif = "Votre demande de Swap a été approuvée par le reponsable de l'UV.";
                        $texte_notif = "Le responsable de l'UV a approuvé la demande de Swap du ".$type." de ".$codeUV.".";
                    }
                }else if($row["typeNotif"] === "4"){
                    $titre_notif = " a retourné sa veste.";
                    $texte_notif = "La demande de swap du ".$type.$semaine2." de ".$codeUV." pour ".nombreEnJour($jour2)." ".date("H\hi", strtotime($hDeb2))."-".date("H\hi", strtotime($hFin2))." a été annulée;";
                }else if($row["typeNotif"] === "5"){
                    $titre_notif = "Votre demande de Swap a été annulée.";
                    $texte_notif = "Votre demande pour le Swap du $type de $codeUV a été annulée par " . $personne1 . " suite à un changement de son horaire...";
                }else if($row["typeNotif"] === "6"){
                    $titre_notif = "Vous avez reçu un nouveau swap.";
                    $texte_notif = $personne1." et ".$personne2." se sont mis d'accord pour échanger leur ".$type." de ".$codeUV."";
                }
                ?>
                <div class="endroit_texte_notif">
                    <div class="user_importance_notification">
                        <div class="image_profil_notification">
                            <img src="../svg/profil.svg">
                            <div class="cercle"></div>
                        </div>
                    </div>
                <?php
                
                
                if($row["typeNotif"] === "1"){
                    ?>
                    <form class="texte_notification" method="POST">
                        <input type="hidden" name="idDemande" value="<?= $idDemande ?>">
                        <input type="hidden" name="demandeur" value="<?= $demandeur ?>">
                        <input type="hidden" name="id_notif" value="<?= $row["idNotif"] ?>">
                        <input type="hidden" class="choix_notification" name="choix" value="0">
                    <?php
                }else{
                    ?>
                    <div class="texte_notification">
                    <?php
                }
                ?>
                    <h1><?= $titre_notif ?></h1>
                    <p><?= $texte_notif ?></p>
                <?php
                if($row["typeNotif"] === "1"){
                    ?>
                        <div><button class="bouton_refuser_notif">Refuser</button><button class="bouton_accepter_notif">Accepter</button></div>
                    </form>
                    <?php
                }else{
                   ?>
                   </div>
                   <?php
                }
                ?>
                        <div class="time_notification">
                            <p>il y a <?= convertirTemps($row["date"]) ?></p>
                        </div>
                    </div>
                    <hr>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
<div id="menu_pannel">
    <div>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="emploiDuTemps.php">Swap</a></li>
            <li><a href="demandes.php">Demandes</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="#">Informations</a></li>
        </ul>
        <hr>
        <a href="#">
            <img class="profil" src="../svg/profil.svg">
            <h2>Nom Prénom</h2>
        </a>
    </div>
    <button onclick="nouveauClick()" class="bouton_nouveau"><img onclick="nouveauClick()" src="../svg/plus.svg">Nouveau</button>
</div>
<div id="ecran"></div>

<form action="#" method="post" id="nouveau_pannel">
    <div id="div_debut_nouveau">
        <h1 id="newDemandeSwap" class="">Nouvelle demande de Swap</h1>
        <h1 id="swapRecap" class="hidden">Récapitulatif : </h1>
        <hr>
    </div>
    <img src="../svg/croix.svg" id="croix_nouveau">
    <div id="div_milieu_nouveau">
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
                    <label for="input-motivation" id="label_motivation">Motivation: (facultatif)</label>
                    <select id="input-motivation" name="motivation" onchange="updateReason()">
                        <option value="">Choisissez une option</option>
                        <option value="sport">Sport</option>
                        <option value="association">Associations</option>
                        <option value="incompatibilité">Incompatibilité d'horaires</option>
                        <option value="travail">Travail</option>
                        <option value="santé">Raisons de santé</option>
                        <option value="autre">Autre (à préciser).</option>
                    </select>
                    <span class="hidden" id="input-motivation-autre"><input type="text" name="motivation-autre" placeholder="Veuillez entrer votre motivation"></span>
                </div>
            </li>
            <li class="basique">
                <input type="checkbox" id="input-semaine" name="semaine">
                <label for="input-semaine">Une semaine sur deux</label>
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
        <div id="afficherSwapRequest">
            <div id="div_messages">
                <p id="message_uv_type" class="hidden">Vous proposez déjà un horaire pour cette UV et ce type de cours !</p>
                <p id="message_changement_creneau" class="hidden">Vous avez effectué une autre demande pour cette UV et ce type en fournissant des créneaux différents, souhaitez-vous modifier le créneau ?</p>
                <p id="message_demande_recu_changement_creneau" class="hidden">Attention ! vous avez des demandes en cours avec l'ancien créneau, si vous continuez, toutes les demandes seront automatiquement annulées !!!</p>
                <p id="message_pression" class="hidden">Assurez-vous de la validité ainsi que de la possession du créneau renseigné. Des incohérences répétées pourraient entraîner des sanctions, y compris le bannissement.</p>
                <p id="message_impossible_uv" class="hidden">Nous sommes désolé mais le responsable de cette UV a désactivé les changements de créneaux. Aucune demande n'est donc possible...</p>
                <p id="message_insertion" class="hidden">La demande a été envoyée !!</p>
                <p id="message_envoie_swap" class="hidden">Votre demande de SWAP a bien été envoyée !</p>
                <p id="message_creneau_deja_accepte" class="hidden">Votre demande a déjà été acceptée par un professeur. Vous ne pouvez plus faire de demandes... En cas de problème merci de contacter le SIMDE.</p>
                <p id="message_creneau_incompatible_semaine" class="hidden">Les deux créneaux ne sont pas compatibles quant à la semaine !</p>
                <p id="message_meme_creneau_existant" class="hidden">Vous avez déjà proposé ce créneau... !</p>
            </div>

            <div class="hidden" id="sendSwap">
                <div class="confirmationSwap">
                    <input type="text" name="swapIdDemande" id="swapIdDemande" hidden>
                    <div>
                        <h3 class="hidden" id="ancienCreneauSwap1" style="text-align: center ; margin: 0">Nouveau créneau</h3>
                        <h3 class="hidden" id="MonCreneauSwap1" style="text-align: center ; margin: 0">Mon créneau</h3>
                        <div class="creneau">
                            <div class="details">
                            <span class="row">
                                <h3>Jour:</h3>
                                <p id="swapJour1"></p>
                            </span>
                                <span class="hidden" id="spanSemaine1">
                                <span class="row">
                                    <h3>Semaine:</h3>
                                    <p id="swapSemaine1"></p>
                                </span>
                            </span>
                                <span class="row">
                                <h3>Salle:</h3>
                                <p id="swapSalle1"></p>
                            </span>
                                <span class="row">
                                <h3>Horaire:</h3>
                                <p id="swapCreneau1"></p>
                            </span>
                            </div>
                        </div>
                    </div>

                    <img src="../svg/swap_icon_demande.svg" id="image_swap">

                    <div>
                        <h3 id="ancienCreneauSwap2" style="text-align: center ; margin: 0" class="hidden">Ancien créneau</h3>
                        <h3 id="MonCreneauSwap2" style="text-align: center ; margin: 0" class="hidden">Créneau souhaité</h3>
                        <div class="creneau">
                            <div class="details">
                            <span class="row">
                                <h3>Jour:</h3>
                                <p id="swapJour2"></p>
                            </span>
                                <span class="hidden" id="spanSemaine2">
                                <span class="row">
                                    <h3>Semaine:</h3>
                                    <p id="swapSemaine2"></p>
                                </span>
                            </span>
                                <span class="row">
                                <h3>Salle:</h3>
                                <p id="swapSalle2"></p>
                            </span>
                                <span class="row">
                                <h3>Horaire:</h3>
                                <p id="swapCreneau2"></p>
                            </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>



    <div id="div_fin_nouveau">
        <hr>
        <button id="bouton_ajouter_creneau" class="hidden" id="bouton_ok">Ajouter</button>
        <button onclick="nouveauClick()" class="bouton_nouveau hidden" id="bouton_ok">OK !</button>
        <button id="bouton_non_submit">Poster</button>
        <div id="boutons_uv" >
            <button id="bouton_impossible_uv" onclick="nouveauClick()" type="reset" class="bouton_nouveau hidden" type="reset">Abandonner</button>
            <button id="bouton_remplacer" type="submit" class="hidden">Remplacer</button>
            <button id="bouton_continuer" type="button" class="hidden">Continuer</button>
        </div>
        <div id="boutons_message" class="hidden">
            <button id="bouton_retour">Retour</button>
            <input type="submit" value="Poster" id="submit_fin_nouveau">
        </div>
        <div id="boutons_confirmation" class="hidden">
            <button id="bouton_retour" onclick="nouveauClick()">Fermer la fenêtre</button>
        </div>
    </div>

    <input type="hidden" name="csrf_token_remplacer" id="input_csrf_token_remplacer" value="">
    <input type="hidden" name="update_choix" id="update_choix" value="0">
</form>


<script src="../js/header.js"></script>
<?php
if (isset($_POST['update_choix']) && !(empty($_POST['update_choix']))) {
    $update_choix = $_POST['update_choix'];
    if ($update_choix == '1'){
        $idDemande = $_SESSION['idDemande'];
        $sqlCheckInsertion = "UPDATE demande SET jour=?,horaireDebut=?, horaireFin=?, salle=?, semaine=? WHERE idDemande=?";
        $stmtCheckInsertion = $connect->prepare($sqlCheckInsertion);
        $stmtCheckInsertion->bind_param("issssi", $_SESSION['jour'], $_SESSION['hDeb'], $_SESSION['hFin'], $_SESSION['salle'], $_SESSION['semaine'], $_SESSION['idDemande']);
        if ($stmtCheckInsertion->execute()) {
            //echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_insertion.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
        }else {
            echo "Erreur lors de l'insertion des données : " . $stmtCheckInsertion->error;
        }
        $offerId = $_SESSION['swap'];
        if (isset($_SESSION['swap'])){
            $hasSemaine = checkIfCreneauHasSemaine($connect , $idDemande , $offerId);
            if (hasCreneauAccepted($connect , $idDemande) && $hasSemaine){
                $uv = $_SESSION['uv'];
                $type = $_SESSION['type'];
                create_swap($connect , $idDemande , $offerId , $uv , $type , $login);
                $loginNotif = getLoginById($connect , $offerId);
                sendNotifications($loginNotif , $offerId , $idDemande , 1 , null, $connect);
                $_SESSION['reloadPage'] = "swapSuccess";
            } else {
                if (!($hasSemaine)){
                    echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_creneau_incompatible_semaine.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);</script>";
                } else {
                    echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_creneau_deja_accepte.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);</script>";
                }
            }
        } else {
            $_SESSION['reloadPage'] = "updateSuccess";
        }
        if (isset($_SESSION["hasRequest"])){
            $message = $_SESSION["hasRequest"];
            if ($message === "hasRequest"){
                $rows = checkIfHasRequest($connect ,$idDemande);
                foreach ($rows as $row){
                    $idRequester = $row['demandeur'];
                    $loginNotif = getLoginById($connect , $idRequester);
                    sendNotifications($loginNotif , $idDemande , $idRequester , 5 , null, $connect);
                }
                updateSwapByDemandeur($connect , $idDemande , 1);
            }
        }
    }
    unset($_SESSION['jour']);
    unset($_SESSION['idDemande']);
    unset($_SESSION['hDeb']);
    unset($_SESSION['hFin']);
    unset($_SESSION['salle']);
    unset($_SESSION['semaine']);
    unset($_SESSION['swap']);
    unset($_SESSION['uv']);
    unset($_SESSION['type']);
    unset($_POST['uv'], $_POST['creneau'], $_POST['hdebut'], $_POST['hfin'], $_POST['salle'], $_POST['type']);
}
?>
<?php
// Vérifier si les variables sont définies et non vides
if (
    isset($_POST['uv'], $_POST['creneau'], $_POST['hdebut'], $_POST['hfin'], $_POST['salle'], $_POST['type']) && ($_POST['update_choix'] == '0') &&
    !empty($_POST['uv']) && !empty($_POST['creneau']) && !empty($_POST['hdebut']) && !empty($_POST['hfin']) && !empty($_POST['salle']) && !empty($_POST['type'] )
) {
    $connect = DBCredential();
    // Valider les données
    $uv = validateInput($_POST['uv'],$connect);
    $creneau = validateInput($_POST['creneau'],$connect);
    $hdebut = validateInput($_POST['hdebut'],$connect);
    $hfin = validateInput($_POST['hfin'],$connect);
    $salle = validateInput($_POST['salle'],$connect);
    $type = validateInput($_POST['type'],$connect);
    $raison = validateInput($_POST['motivation'], $connect);
    $motivationAutre = validateInput($_POST['motivation-autre'], $connect);
    if($raison != "autre" && $motivationAutre != null){
        $motivationAutre = null;
    }else if($raison == "autre" && $motivationAutre == null){
        $raison = "";
    }
    if($motivationAutre != null){
        $raison = "";
    }

    if(!in_array($raison, ["sport", "association", "incompatibilité", "santé", ""])){
        header("Location: erreur.php");
        exit();
    }else if(strlen($uv) != 4){
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
        $responsable = getResponsableByUv($uv);
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
                $canSwap = false;
                echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_impossible_uv.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);</script>";
            }
        }

        if($swap_uv === 1){
            $canSwap = false;
            // Récupérez la valeur du choix de semaine seulement si la case à cocher est cochée
            $semaineChoix = $creneauUneSemaine && isset($_POST['semainechoix']) ? validateInput($_POST['semainechoix'],$connect) : 'null';
            // Préparez la requête SQL d'insertion
            $jour = jourEnNombre($creneau);
            $isDemandeExisting = getIdDemandeSwap($connect , $login , $type , $uv);
            if ($isDemandeExisting === null || (isset($_POST['swapIdDemande']) && !empty($_POST['swapIdDemande']))) {
                /* Quand un étudiant fait une demande de swap, vérifier que la demande n'existe pas encore, cela permet à l'étudiant de formuler plusieurs demandes*/
                /* Si elle existe, simplement créer le SWAP sinon créer la demande puis ensuite créer le swap */
                $isDemandeExisting = getIdDemandeSwap($connect , $login , $type , $uv);
                /* --------------------------------------- */
                if ($isDemandeExisting === null) {
                    $primaryKeyDemande = insert_demande($connect , $login , $uv , $type , $jour , $hdebut , $hfin , $salle , $semaineChoix, $raison, $motivationAutre);
                    if ($primaryKeyDemande != null) {
                        $canSwap = true;
                        if (!(isset($_POST['swapIdDemande']) && !empty($_POST['swapIdDemande']))){
                            echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_insertion.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
                        }
                    }else {
                        $primaryKeyDemande = null;
                        echo "Création demande -> Erreur lors de l'insertion des données : ";
                    }
                } else {
                    /* Quand un étudiant a déjà fait une demande pour un créneau, et qu'il change l'horaire en faisant la demande de swap par rapport à son ancien créneau, le notifier. */
                    $primaryKeyDemande = $isDemandeExisting['idDemande'];
                    $result = checkIfDetailsChange($connect , $primaryKeyDemande , $type, $uv , $hdebut , $hfin , $salle , $semaineChoix , $jour);
                    if ($result === null){
                        /* Ici, l'élève a changer son créneau , il faut lui proposer de l'update. */
                        // Penser à supprimer la vérification inutile, j'ia déjà le currentIDdemande inutile de faire une nouvelle requête...
                        $currentIDdemande = getIdDemandeSwap($connect ,$login , $type , $uv);
                        if ($currentIDdemande != null) {
                            if (isset($_POST['swapIdDemande']) && !empty($_POST['swapIdDemande'])){
                                $_SESSION["swap"] = $_POST['swapIdDemande'];
                                $_SESSION["uv"] = $uv;
                                $_SESSION["type"] = $type;
                            }
                            echo "<script> document.getElementById('update_choix').value = '1'; document.getElementById('input-uv').value = '$uv' ; </script>";
                            $_SESSION["idDemande"] = $currentIDdemande['idDemande'];
                            $_SESSION["hDeb"] = $hdebut;
                            $_SESSION["hFin"] = $hfin;
                            $_SESSION["salle"] = $salle;
                            $_SESSION["jour"] = $jour;
                            $_SESSION["semaine"] = $semaineChoix;
                            afficherChangementCreneau($connect , $currentIDdemande['idDemande'] , $jour , $salle , $hdebut , $hfin , $semaineChoix);
                        } else {
                            error_log("Erreur dans la récupération des données...");
                        }
                    } else {
                        if ($primaryKeyDemande){
                            //echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_insertion.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
                            $canSwap = true;
                        } else {
                            error_log("Erreur...");
                        }

                    }
                }
            }else{
                $primaryKeyDemande = $isDemandeExisting['idDemande'];
                $isOffer = $isDemandeExisting['demande'];
                $result = checkIfDetailsChange($connect , $primaryKeyDemande , $type, $uv , $hdebut , $hfin , $salle , $semaineChoix , $jour);

                if ($result === null) {
                    /* Ici, l'élève a changer son créneau , il faut lui proposer de l'update. */
                    echo "<script> document.getElementById('update_choix').value = '1' ; </script>";
                    /* Afficher ancien et nouvel horaire. */
                    afficherChangementCreneau($connect , $primaryKeyDemande , $jour , $salle , $hdebut , $hfin , $semaineChoix);
                    $_SESSION["idDemande"] = $primaryKeyDemande;
                    $_SESSION["hDeb"] = $hdebut;
                    $_SESSION["hFin"] = $hfin;
                    $_SESSION["salle"] = $salle;
                    $_SESSION["jour"] = $jour;
                    $_SESSION["semaine"] = $semaineChoix;
                    $canSwap = false;
                } else {
                    if ($isOffer !== 0 ) {
                        echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_meme_creneau_existant.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
                    } else {
                        echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_insertion.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
                    }
                }
                // Faire en sorte que le changement se fait dans le else ou après avoir validé le changement d'horaire !
                if ($isOffer == 0 ) {
                    update_demande_statut($connect, $primaryKeyDemande, 1);
                }

            }
        }
    }
    /* Vérifier si un swap n'existe pas déjà pour la demande de l'étudiant. */
    if (isset($_POST['swapIdDemande']) && !empty($_POST['swapIdDemande']) && $canSwap && $swap_uv){
        if ($primaryKeyDemande != null){
            $offerId = $_POST['swapIdDemande'];
            $hasSemaine = checkIfCreneauHasSemaine($connect , $primaryKeyDemande , $offerId);
            if (hasCreneauAccepted($connect , $primaryKeyDemande) && $hasSemaine){
                create_swap($connect , $primaryKeyDemande , $offerId , $uv , $type , $login);
                $loginNotif = getLoginById($connect , $offerId);
                sendNotifications($loginNotif , $offerId , $primaryKeyDemande , 1 , null, $connect);
            } else {
                if (!($hasSemaine)){
                    echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_creneau_incompatible_semaine.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);</script>";
                } else {
                    echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_creneau_deja_accepte.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);</script>";
                }

            }
        } else {
            if (!($swap_uv)){
                echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_impossible_uv.classList.toggle('hidden', false);bouton_impossible_uv.classList.toggle('hidden', false);</script>";
            } else {
                echo "Swap -> Erreur dans l'insertion des données : ";
            }
        }

    }
    $connect->close();
} else if (isset($_SESSION['reloadPage'])){
    $content = $_SESSION['reloadPage'];
    if ($content == "swapSuccess"){
        echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_envoie_swap.classList.toggle('hidden', false);boutons_confirmation.classList.toggle('hidden', false);</script>";
    } else {
        echo "<script>nouveau_pannel.style.display = 'flex';bouton_non_submit.classList.toggle('hidden', true);ul_nouveau.classList.toggle('hidden', true);message_insertion.classList.toggle('hidden', false);bouton_ok.classList.toggle('hidden', false);</script>";
    }
    unset($_SESSION['reloadPage']);
}
?>


</body>
</html>