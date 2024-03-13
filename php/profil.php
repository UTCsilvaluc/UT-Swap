<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profil.css">
    <title>Profil - UT'Swap</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    <?php

    $login = "ldompnie";
    function getProfil(){
        global $login;
        $connect = DBCredential();
        $sqlMail = "SELECT nom, prenom FROM personne WHERE login = ?";
        $stmtMail = $connect->prepare($sqlMail);
        $stmtMail->bind_param("s", $login);
        $stmtMail->execute();
        $stmtMail->store_result();
        if ($stmtMail->num_rows !== 0) {
            $stmtMail->bind_result($nom, $prenom);
            $stmtMail->fetch();
            return ucfirst($prenom) . " " . ucfirst($nom);
        }
        return "Nom Prénom";
    }

    function getMail(){
        global $login;
        $connect = DBCredential();
        $sqlMail = "SELECT mail FROM personne WHERE login = ?";
        $stmtMail = $connect->prepare($sqlMail);
        $stmtMail->bind_param("s", $login);
        $stmtMail->execute();
        $stmtMail->store_result();
        if ($stmtMail->num_rows !== 0) {
            $stmtMail->bind_result($mail);
            $stmtMail->fetch();
            return $mail;
        }
        return "email@email.com";
    }

    function getNbSwapProfil($type){
        global $login;
        $connect = DBCredential();
        if($type === "attente"){
            $sqlNbSwap = "SELECT count(*) FROM `swap` s JOIN demande d1 ON d1.idDemande=s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur WHERE (d1.login = ? OR d2.login = ?) AND s.statut=3";
        }else if($type === "accept"){
            $sqlNbSwap = "SELECT count(*) FROM `swap` s JOIN demande d1 ON d1.idDemande=s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur WHERE (d1.login = ? OR d2.login = ?) AND s.statut=5";
        }else if($type === "refus"){
            $sqlNbSwap = "SELECT count(*) FROM `swap` s JOIN demande d1 ON d1.idDemande=s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur WHERE (d1.login = ? OR d2.login = ?) AND s.statut=4";
        }
        $stmtNbSwap = $connect->prepare($sqlNbSwap);
        $stmtNbSwap->bind_param("ss", $login, $login);
        $stmtNbSwap->execute();
        $stmtNbSwap->store_result();
        if ($stmtNbSwap->num_rows !== 0) {
            $stmtNbSwap->bind_result($nbSwap);
            $stmtNbSwap->fetch();
            return $nbSwap;
        }
        return "0";
    }
    function formaterHeure($heureNonFormatee) {
        list($heures, $minutes) = explode(':', $heureNonFormatee);
        return sprintf('%02dh%02d', $heures, $minutes);
    }

    ?>
    <main>
        <div class="arc-de-cercle"></div>
        <img src="../svg/profil.svg" id="profil_image">
        <div id="fond_profil_image"></div>
        <div id="profil_utilisateur" class="profil_pannel">
            <div id="profil_header">
                <span class="profil_titre"><span class="tictac"></span><h1>Mon profil</h1></span>
                <button>Se déconnecter</button>
            </div>
            <div id="profil_content">
                <div id="profil_texte">
                    <h1><?= getProfil(); ?></h1>
                    <div id="profil_mail">
                        <span>e-mail: </span>
                        <span><?= getMail(); ?></span>
                    </div>
                </div>
                <div id="profil_demandes">
                    <div id="profil_demandes_parent">
                        <div id="profil_demande_cours" class="profil_demande">
                            <span class="profil_demande_header"><img src="../svg/dmd_attent.svg" alt=""><span><?= getNbSwapProfil("attente"); ?></span></span>
                            <h2>Swaps en cours</h2>
                        </div>
                        <div id="profil_demande_accept" class="profil_demande">
                            <span class="profil_demande_header"><img src="../svg/dmd_accept.svg" alt=""><span><?= getNbSwapProfil("accept"); ?></span></span>
                            <h2>Swaps acceptés</h2>
                        </div>
                        <div id="profil_demande_refus" class="profil_demande">
                            <span class="profil_demande_header"><img src="../svg/dmd_refus.svg" alt=""><span><?= getNbSwapProfil("refus"); ?></span></span>
                            <h2>Swaps refusés</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="demandes_faites_utilisateur" class="profil_pannel">
            <div id="demandes_faites_header" class="demandes_profil_header">
                <span class="demandes_profil_titre"><span class="tictac"></span><h1 title="demandes que vous avez fais à un autre utilisateur">Demandes faites</h1></span>
            </div>
            <div id="demandes_faites_content" class="demandes_profil_content">
                <div id="demandes_faites" class="demandes_profil">
                    <?php 
                    $connect = DBCredential();
                    $sqlDemandeFaite = "SELECT s.statut, p.nom, p.prenom, d1.codeUV, d1.type, d1.jour, d1.horaireDebut, d1.horaireFin, d1.salle, d1.semaine, d1.raison FROM `swap` s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN demande d2 ON d2.idDemande=s.demandeur JOIN personne p ON p.login = d1.login WHERE d2.login= ? AND s.statut <= 2 ORDER BY CASE statut WHEN 0 THEN 1 WHEN 2 THEN 2 WHEN 1 THEN 3 ELSE 4 END;";
                    $stmtDemandeFaite = $connect->prepare($sqlDemandeFaite);
                    $stmtDemandeFaite->bind_param("s", $login);
                    $stmtDemandeFaite->execute();
                    $result = $stmtDemandeFaite->get_result();
                      
                    foreach ($result as $row) {
                        $codeUV = $row["codeUV"];
                        $nom = ucfirst($row["nom"]);
                        $prenom = ucfirst($row["prenom"]);
                        $type = $row["type"];
                        $jours = array(
                            1 => 'Lundi',
                            2 => 'Mardi',
                            3 => 'Mercredi',
                            4 => 'Jeudi',
                            5 => 'Vendredi',
                            6 => 'Samedi',
                            7 => 'Dimanche'
                        );
                        $jour = $jours[$row["jour"]];
                        $hDeb = formaterHeure($row["horaireDebut"]);
                        $hFin = formaterHeure($row["horaireFin"]);
                        $salle = $row["salle"];
                        $semaine = $row["semaine"] !== "null" ? ' ' . $row["semaine"] : null;
                        $raison = $row["raison"];
                        $statut = $row["statut"];
                        if($statut == 0){
                            $statutClass = "demande_faite_button_attent";
                            $statutName = "Attente";
                        }else if($statut == 1){
                            $statutClass = "demande_faite_button_refus";
                            $statutName = "Refusée";
                        }else if($statut == 2){
                            $statutClass = "demande_faite_button_accept";
                            $statutName = "Acceptée";
                        }
                        ?>
                        <div class="demande_faite">
                            <div class="demande_faite_front">
                                <div class="demande_faite_header">
                                    <span class="demande_faite_titre"><h2><?= $codeUV ?></h2><h2>-</h2><h2><?= $type . $semaine ?></h2></span>
                                    <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                </div>
                                <span class="demande_faite_detail"><?= $jour . " " . $hDeb . "-" . $hFin . " " . $salle ?></span>
                                <span class="demande_faite_etudiant"><span class="demande_faite_etudiant_titre">Etudiant:</span><span><?= $nom . " " . $prenom?></span></span>
                            </div>
                            <div class="demande_faite_back">
                                <div class="demande_faite_header">
                                    <span class="demande_faite_titre"><h2><?= $codeUV ?></h2><h2>-</h2><h2><?= $type . $semaine ?></h2></span>
                                    <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                </div>
                                <span class="demande_faite_detail"><?= $jour . " " . $hDeb . "-" . $hFin . " " . $salle ?></span>
                                <span class="demande_faite_motif"><span class="demande_faite_motif_titre">Motif:</span><span><?= $raison ?></span></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div id="demandes_faites_fleches" class="demandes_profil_fleches">
                    <img src="../svg/fleche_gauche.svg" onclick="scrollToElement(false)" alt="">
                    <img src="../svg/fleche_droite.svg" onclick="scrollToElement(true)" alt="">
                </div>
            </div>
        </div>
        
        <div id="demandes_reçues_utilisateur" class="profil_pannel">
            <div id="demandes_reçues_header" class="demandes_profil_header">
                <span class="demandes_profil_titre"><span class="tictac"></span><h1 title="demandes que vous avez reçu d'un autre utilisateur">Demandes reçues</h1></span>
            </div>
            <div id="demandes_reçues_content" class="demandes_profil_content">
                <div id="demandes_reçues" class="demandes_profil">
                <?php 
                    $connect = DBCredential();
                    $sqlDemandeFaite = "SELECT s.statut, p.nom, p.prenom, d1.codeUV, d1.type, d1.jour, d1.horaireDebut, d1.horaireFin, d1.salle, d1.semaine, d1.raison FROM `swap` s JOIN demande d1 ON d1.idDemande = s.demandeur JOIN demande d2 ON d2.idDemande=s.idDemande JOIN personne p ON p.login = d1.login WHERE d2.login= ? AND s.statut <= 2 ORDER BY CASE statut WHEN 0 THEN 1 WHEN 2 THEN 2 WHEN 1 THEN 3 ELSE 4 END;";
                    $stmtDemandeFaite = $connect->prepare($sqlDemandeFaite);
                    $stmtDemandeFaite->bind_param("s", $login);
                    $stmtDemandeFaite->execute();
                    $result = $stmtDemandeFaite->get_result();
                      
                    foreach ($result as $row) {
                        $codeUV = $row["codeUV"];
                        $nom = ucfirst($row["nom"]);
                        $prenom = ucfirst($row["prenom"]);
                        $type = $row["type"];
                        $jours = array(
                            1 => 'Lundi',
                            2 => 'Mardi',
                            3 => 'Mercredi',
                            4 => 'Jeudi',
                            5 => 'Vendredi',
                            6 => 'Samedi',
                            7 => 'Dimanche'
                        );
                        $jour = $jours[$row["jour"]];
                        $hDeb = formaterHeure($row["horaireDebut"]);
                        $hFin = formaterHeure($row["horaireFin"]);
                        $salle = $row["salle"];
                        $semaine = $row["semaine"] !== "null" ? ' ' . $row["semaine"] : null;
                        $raison = $row["raison"];
                        $statut = $row["statut"];
                        if($statut == 0){
                            $statutClass = "demande_reçue_button_attent";
                            $statutName = "Attente";
                        }else if($statut == 1){
                            $statutClass = "demande_reçue_button_refus";
                            $statutName = "Refusée";
                        }else if($statut == 2){
                            $statutClass = "demande_reçue_button_accept";
                            $statutName = "Acceptée";
                        }
                        ?>
                        <div class="demande_reçue">
                            <div class="demande_reçue_front">
                                <div class="demande_reçue_header">
                                    <span class="demande_reçue_titre"><h2><?= $codeUV ?></h2><h2>-</h2><h2><?= $type . $semaine ?></h2></span>
                                    <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                </div>
                                <span class="demande_reçue_detail"><?= $jour . " " . $hDeb . "-" . $hFin . " " . $salle ?></span>
                                <span class="demande_reçue_etudiant"><span class="demande_reçue_etudiant_titre">Etudiant:</span><span><?= $nom . " " . $prenom?></span></span>
                            </div>
                            <div class="demande_reçue_back">
                                <div class="demande_reçue_header">
                                    <span class="demande_reçue_titre"><h2><?= $codeUV ?></h2><h2>-</h2><h2><?= $type . $semaine ?></h2></span>
                                    <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                </div>
                                <span class="demande_reçue_motif"><span class="demande_reçue_motif_titre">Motif:</span><span><?= $raison ?></span></span>
                                <div>
                                    <button class="demande_reçue_button_refus">Refuser</button>
                                    <button class="demande_reçue_button_accept">Accepter</button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
                </div>
                <div id="demandes_reçues_fleches" class="demandes_profil_fleches">
                    <img src="../svg/fleche_gauche.svg" onclick="scrollToElement(false)" alt="">
                    <img src="../svg/fleche_droite.svg" onclick="scrollToElement(true)" alt="">
                </div>
            </div>
        </div>
    </main>
    <div id="demande_attente_pannel">
        <div>
            <h1>Demande en attente</h1>
            <hr>
        </div>
        <div>
            
        </div>
        <div>
            <hr>
            <div>
                <button>Refuser</button>
                <button>Accepter</button>
            </div>
        </div>
    </div>
    <script src="../js/profil.js"></script>
</body>

</html>
