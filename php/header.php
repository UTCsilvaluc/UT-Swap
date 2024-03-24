<?php
include "../utils/db_functions.php";
include "../utils/header_utils.php";
include "../utils/utils.php";
session_start();

function DBCredential(){
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
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
$search_string = "login.php";

if(isset($_SESSION["login"]) && !empty($_SESSION['login'])){
    $login = $_SESSION["login"];
}else if(strpos($current_uri, $search_string) === false){
    header("Location: login.php");
    exit;
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
function sendNotifications($loginNotif, $idDemande, $demandeur, $type_notif, $statut, $connect){
    
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
    }else if($type_notif === 4){
        $contenu_notif = $personne1." a retourné sa veste.;La demande de swap du ".$type2.$semaine2." de ".$codeUV2." pour ".nombreEnJour($jour2)." ".date("H\hi", strtotime($horaireDebut2))."-".date("H\hi", strtotime($horaireFin2))." a été annulée;";
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

if (isset($_POST['view'])){
    $view = validateInput($_POST['view'],$connect);
    if($view === "1"){
        $sqlUpdateSwap = "UPDATE notifications SET viewed = 1 WHERE loginEtu = ? AND typeNotif != 1";
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
}else if(isset($_POST['demandeur'], $_POST['idDemande']) && !empty($_POST['demandeur']) && !empty($_POST['idDemande'])){
    cancelSwapFait($_POST['idDemande'], $_POST['demandeur'], $login, $connect);
    unset($_POST['demandeur']);
    unset($_POST['idDemande']);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
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
            <li><a href="#">Home</a></li>
            <li><a href="emploiDuTemps.php">Swap</a></li>
            <li><a href="demandes.php">Demandes</a></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="#">Informations</a></li>
            <li onclick="notificationClick()"><img class="notification" src="../svg/notif.svg">
                <?php
                $connect = DBCredential();
                $sql = "SELECT * FROM notifications WHERE viewed = 0 AND loginEtu = ? AND typeNotif='1'";
                $stmt = $connect->prepare($sql);
                $stmt->bind_param("s", $login);
                $stmt->execute();
                $resultat = $stmt->get_result();
                $sql2 = "SELECT * FROM notifications WHERE viewed = 0 AND loginEtu = ? AND typeNotif='2';";
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
                ?>
            </li>
            <li><button onclick="nouveauClick()" class="bouton_nouveau"><img src="../svg/plus.svg">Nouveau</button></li>
        </ul>
        <ul id="menu_liste_petit">
            <li onclick="notificationClick()"><img class="notification" src="../svg/notif.svg">
                <?php
                $connect = DBCredential();
                $sql1 = "SELECT * FROM notifications WHERE viewed = 0 AND loginEtu = ? AND typeNotif='1'";
                $stmt1 = $connect->prepare($sql1);
                $stmt1->bind_param("s", $login);
                $stmt1->execute();
                $result = $stmt1->get_result();
                $sql12 = "SELECT * FROM notifications WHERE viewed = 0 AND loginEtu = ? AND typeNotif='2';";
                $stmt12 = $connect->prepare($sql12);
                $stmt12->bind_param("s", $login);
                $stmt12->execute();
                $result2 = $stmt12->get_result();
                if ($result->num_rows > 0 || $result2->num_rows > 0) {
                    $classeDiv = "cercle";
                    if ($result2->num_rows > 0) {
                        $classeDiv .= " orange";
                    }
                    if($result->num_rows > 0){
                        $classeDiv .= " vert";
                    }
                    echo '<div class="' . $classeDiv . '"></div>';
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
        $sqlSelectNotif = "SELECT idNotif, typeNotif, contenuNotif, idDemande, demandeur, date, viewed FROM notifications WHERE loginEtu=? ORDER BY date DESC;";
        $stmtSelectNotif = $connect->prepare($sqlSelectNotif);
        $stmtSelectNotif->bind_param("s", $login);
        $stmtSelectNotif->execute();
        $resultat = $stmtSelectNotif->get_result();

        // Vérifier s'il y a des résultats
        if ($resultat->num_rows > 0) {
            // Afficher les options du datalist

            foreach ($resultat as $row) {
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
                    <input type="text" id="input-motivation" name="motivation" placeholder="Veuillez entrer votre motivation">
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
                <p id="message_pression" class="hidden">Assurez-vous de la validité ainsi que de la possession du créneau renseigné. Des incohérences répétées pourraient entraîner des sanctions, y compris le bannissement.</p>
                <p id="message_impossible_uv" class="hidden">Nous sommes désolé mais le responsable de cette UV a désactivé les changements de créneaux. Aucune demande n'est donc possible...</p>
                <p id="message_insertion" class="hidden">La demande a été envoyée !!</p>
                <p id="message_envoie_swap" class="hidden">Votre demande de SWAP a bien été envoyée !</p>
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
        if (isset($_SESSION['swap'])){
            $uv = $_SESSION['uv'];
            $type = $_SESSION['type'];
            $offerId = $_SESSION['swap'];
            create_swap($connect , $idDemande , $offerId , $uv , $type , $login);
            $loginNotif = getLoginById($connect , $offerId);
            sendNotifications($loginNotif , $offerId , $idDemande , 1 , 0 , $connect);
            $_SESSION['reloadPage'] = "swapSuccess";
        } else {
            $_SESSION['reloadPage'] = "updateSuccess";
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
                    $primaryKeyDemande = insert_demande($connect , $login , $uv , $type , $jour , $hdebut , $hfin , $salle , $semaineChoix);
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
                            afficherChangementCreneau($connect , $currentIDdemande['idDemande'] , $jour , $salle , $hdebut , $hfin);
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
                    afficherChangementCreneau($connect , $primaryKeyDemande , $jour , $salle , $hdebut , $hfin);
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
    if (isset($_POST['swapIdDemande']) && !empty($_POST['swapIdDemande']) && $canSwap){
        if ($primaryKeyDemande != null){
            $offerId = $_POST['swapIdDemande'];
            create_swap($connect , $primaryKeyDemande , $offerId , $uv , $type , $login);
            $loginNotif = getLoginById($connect , $offerId);
            sendNotifications($loginNotif , $offerId , $primaryKeyDemande , 1 , 0 , $connect);
        } else {
            echo "Swap -> Erreur dans l'insertion des données : ";
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