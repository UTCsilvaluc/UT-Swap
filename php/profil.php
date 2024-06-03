<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/demande_content.css">
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="stylesheet" href="../css/mid_pannel.css">
    <title>Profil - UT'Swap</title>
    <link rel="icon" href="../img/logo_mini.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    <?php
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
        if($type === "cours"){
            $sqlNbSwap = "SELECT count(*) FROM `swap` s JOIN demande d1 ON d1.idDemande=s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur WHERE (d1.login = ? OR d2.login = ?) AND s.statut=2";
        }else if($type === "accept"){
            $sqlNbSwap = "SELECT count(*) FROM `swap` s JOIN demande d1 ON d1.idDemande=s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur WHERE (d1.login = ? OR d2.login = ?) AND s.statut=4";
        }else if($type === "refus"){
            $sqlNbSwap = "SELECT count(*) FROM `swap` s JOIN demande d1 ON d1.idDemande=s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur WHERE (d1.login = ? OR d2.login = ?) AND s.statut=3";
        }else if($type === "attente"){
            $sqlNbSwap = "SELECT count(*)FROM `demande` d WHERE d.login = ? AND idDemande NOT IN (SELECT s.idDemande FROM `swap` s JOIN demande d1 ON d1.idDemande=s.idDemande WHERE d1.login = ? AND s.statut>=2) AND idDemande NOT IN (SELECT s.demandeur FROM `swap` s JOIN demande d1 ON d1.idDemande=s.demandeur WHERE d1.login = ? AND s.statut>=2)";
        }
        $stmtNbSwap = $connect->prepare($sqlNbSwap);
        if($type === "attente"){
            $stmtNbSwap->bind_param("sss", $login, $login, $login);
        }else{
            $stmtNbSwap->bind_param("ss", $login, $login);
        }
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
            <div class="profil_header">
                <span class="profil_titre"><span class="tictac"></span><h1>Mon profil</h1></span>
                <button onclick="deconnexion()">Se déconnecter</button>
            </div>
            <div id="profil_content">
                <div id="profil_texte">
                    <h1><?= getProfil(); ?></h1>
                    <div id="profil_mail">
                        <span>e-mail: </span>
                        <span><?= getMail(); ?></span>
                    </div>
                </div>
                <div class="profil_demandes">
                    <div class="profil_demandes_parent">
                        <div class="profil_demandes_sous_parent">
                            <div id="profil_demande_attente" onclick="openSwapPannel('attente')" class="profil_demande">
                                <span class="profil_demande_header"><img src="../svg/dmd_attent.svg" alt=""><span><?= getNbSwapProfil("attente"); ?></span></span>
                                <h2>Swaps en attente</h2>
                            </div>
                            <div id="profil_demande_cours" onclick="openSwapPannel('cours')" class="profil_demande">
                                <span class="profil_demande_header"><img src="../svg/demande_cours.svg" alt=""><span><?= getNbSwapProfil("cours"); ?></span></span>
                                <h2>Swaps en cours</h2>
                            </div>
                        </div>
                        <div class="profil_demandes_sous_parent">
                            <div id="profil_demande_accept" onclick="openSwapPannel('accept')" class="profil_demande">
                                <span class="profil_demande_header"><img src="../svg/dmd_accept.svg" alt=""><span><?= getNbSwapProfil("accept"); ?></span></span>
                                <h2>Swaps acceptés</h2>
                            </div>
                            <div id="profil_demande_refus" onclick="openSwapPannel('refus')" class="profil_demande">
                                <span class="profil_demande_header"><img src="../svg/dmd_refus.svg" alt=""><span><?= getNbSwapProfil("refus"); ?></span></span>
                                <h2>Swaps refusés</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="demandes_faites_utilisateur" class="profil_pannel">
            <div id="demandes_faites_header" class="demandes_profil_header">
                <span class="demandes_profil_titre"><span class="tictac"></span><h1 title="demandes que vous avez faites à un autre utilisateur">Demandes faites</h1></span>
            </div>
            <div id="demandes_faites_content" class="demandes_profil_content">
                <div id="demandes_faites" class="demandes_profil">
                    <?php 
                    $connect = DBCredential();
                    $sqlDemandeFaite = "SELECT s.statut,s.idDemande, s.demandeur, p.nom, p.prenom, d1.codeUV as codeUV1, d1.type as type1, d1.jour as jour1, d1.horaireDebut as hdeb1, d1.horaireFin as hfin1, d1.salle as salle1, d1.semaine as semaine1, d2.raison as raison, d2.codeUV as codeUV2, d2.type as type2, d2.jour as jour2, d2.horaireDebut as hdeb2, d2.horaireFin as hfin2, d2.salle as salle2, d2.semaine as semaine2 FROM `swap` s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN demande d2 ON d2.idDemande=s.demandeur JOIN personne p ON p.login = d1.login WHERE d2.login= ? ORDER BY CASE statut WHEN 0 THEN 1 WHEN 2 THEN 2 WHEN 1 THEN 3 ELSE 4 END;";
                    $stmtDemandeFaite = $connect->prepare($sqlDemandeFaite);
                    $stmtDemandeFaite->bind_param("s", $login);
                    $stmtDemandeFaite->execute();
                    $result = $stmtDemandeFaite->get_result();
                    if($result->num_rows > 0){
                        foreach ($result as $row) {
                            $codeUV1 = $row["codeUV1"];
                            $nom = ucfirst($row["nom"]);
                            $prenom = ucfirst($row["prenom"]);
                            $type1 = $row["type1"];
                            $jours = array(
                                1 => 'Lundi',
                                2 => 'Mardi',
                                3 => 'Mercredi',
                                4 => 'Jeudi',
                                5 => 'Vendredi',
                                6 => 'Samedi',
                                7 => 'Dimanche'
                            );
                            $jour1 = $jours[$row["jour1"]];
                            $hDeb1 = formaterHeure($row["hdeb1"]);
                            $hFin1 = formaterHeure($row["hfin1"]);
                            $salle1 = $row["salle1"];
                            $semaine1 = $row["semaine1"] !== "null" ? ' ' . $row["semaine1"] : null;
                            $raison = $row["raison"];
                            
                            $codeUV2 = $row["codeUV2"];
                            $type2 = $row["type2"];
                            $jour2 = $jours[$row["jour2"]];
                            $hDeb2 = formaterHeure($row["hdeb2"]);
                            $hFin2 = formaterHeure($row["hfin2"]);
                            $salle2 = $row["salle2"];
                            $semaine2 = $row["semaine2"] !== "null" ? ' ' . $row["semaine2"] : null;
                            if($raison == null){
                                $raison = "non renseigné";
                            }
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
                            }else if($statut == 3){
                                $statutClass = "demande_faite_button_rejet";
                                $statutName = "Rejetée";
                            }else if($statut = 4){
                                $statutClass = "demande_faite_button_approuv";
                                $statutName = "Approuvée";
                            }
                            $idDemande = $row["idDemande"];
                            $demandeur = $row["demandeur"];
                            $demande= array(
                                "idDemande" => $idDemande,
                                "demandeur" => $demandeur
                            );
                            $data_row = htmlspecialchars(base64_encode(json_encode($demande)), ENT_QUOTES , 'UTF-8');
                            ?>
                            <div class="demande_faite" data-row=<?= $data_row; ?>>
                                <div class="demande_faite_front">
                                    <div class="demande_faite_header">
                                        <span class="demande_faite_titre"><h2><?= $codeUV1 ?></h2><h2>-</h2><h2><?= $type1 . $semaine1 ?></h2></span>
                                        <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                    </div>
                                    <span class="demande_faite_detail"><?= $jour1 . " " . $hDeb1 . "-" . $hFin1 . " " . $salle1 ?></span>
                                    <span class="demande_faite_etudiant"><span class="demande_faite_etudiant_titre">Etudiant:</span><span><?= $nom . " " . $prenom?></span></span>
                                </div>
                                <div class="demande_faite_back">
                                    <div class="demande_faite_header">
                                        <span class="demande_faite_titre"><h2><?= $codeUV2 ?></h2><h2>-</h2><h2><?= $type2 . $semaine2 ?></h2></span>
                                        
                            <?php
                            if($statut == 0){
                                ?>
                                        <button class="demande_trash_parent" onclick="cancelDemandeFaite(this)"><img class="demande_trash" src="../svg/TRASH_FILTRE.svg"></button>
                                    <?php
                            }else{
                                ?>
                                        <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                <?php
                            }
                                    ?>
                                    </div>
                                    <span class="demande_faite_detail"><?= $jour2 . " " . $hDeb2 . "-" . $hFin2 . " " . $salle2 ?></span>
                                    <span class="demande_faite_motif"><span class="demande_faite_motif_titre">Motif:</span><span><?= $raison ?></span></span>
                                </div>
                            </div>
                            <?php
                        }
                    }else{
                        ?>
                            <div class="message_aucune_demande">
                                <h1>Vous n'avez aucune demande de faite</h1>
                            </div>
                        <?php
                    }
                    
                    ?>
                </div>
                <div id="demandes_faites_fleches" class="demandes_profil_fleches">
                    <img src="../svg/fleche_gauche.svg" onclick="scrollToElement('fait',false)" alt="">
                    <img src="../svg/fleche_droite.svg" onclick="scrollToElement('fait',true)" alt="">
                </div>
            </div>
        </div>
        
        <div id="demandes_reçues_utilisateur" class="profil_pannel">
            <div id="demandes_reçues_header" class="demandes_profil_header">
                <span class="demandes_profil_titre"><span class="tictac"></span><h1 title="demandes que vous avez reçues d'un autre utilisateur">Demandes reçues</h1></span>
            </div>
            <div id="demandes_reçues_content" class="demandes_profil_content">
                <div id="demandes_reçues" class="demandes_profil">
                <?php 
                    $connect = DBCredential();
                    $sqlDemandeFaite = "SELECT s.statut, s.idDemande, s.demandeur, p.nom, p.prenom, d1.codeUV as codeUV1, d1.type as type1, d1.jour as jour1, d1.horaireDebut as hdeb1, d1.horaireFin as hfin1, d1.salle as salle1, d1.semaine as semaine1, d1.raison, d2.codeUV as codeUV2, d2.type as type2, d2.jour as jour2, d2.horaireDebut as hdeb2, d2.horaireFin as hfin2, d2.salle as salle2, d2.semaine as semaine2 FROM `swap` s JOIN demande d1 ON d1.idDemande = s.demandeur JOIN demande d2 ON d2.idDemande=s.idDemande JOIN personne p ON p.login = d1.login WHERE d2.login= ? ORDER BY CASE statut WHEN 0 THEN 1 WHEN 2 THEN 2 WHEN 1 THEN 3 ELSE 4 END;";
                    $stmtDemandeFaite = $connect->prepare($sqlDemandeFaite);
                    $stmtDemandeFaite->bind_param("s", $login);
                    $stmtDemandeFaite->execute();
                    $result = $stmtDemandeFaite->get_result();
                    if($result->num_rows > 0){
                        foreach ($result as $row) {
                            $codeUV1 = $row["codeUV1"];
                            $nom = ucfirst($row["nom"]);
                            $prenom = ucfirst($row["prenom"]);
                            $type1 = $row["type1"];
                            $jours = array(
                                1 => 'Lundi',
                                2 => 'Mardi',
                                3 => 'Mercredi',
                                4 => 'Jeudi',
                                5 => 'Vendredi',
                                6 => 'Samedi',
                                7 => 'Dimanche'
                            );
                            $jour1 = $jours[$row["jour1"]];
                            $hDeb1 = formaterHeure($row["hdeb1"]);
                            $hFin1 = formaterHeure($row["hfin1"]);
                            $salle1 = $row["salle1"];
                            $semaine1 = $row["semaine1"] !== "null" ? ' ' . $row["semaine1"] : null;
                            $raison = $row["raison"];
                            
                            $codeUV2 = $row["codeUV2"];
                            $type2 = $row["type2"];
                            $jour2 = $jours[$row["jour2"]];
                            $hDeb2 = formaterHeure($row["hdeb2"]);
                            $hFin2 = formaterHeure($row["hfin2"]);
                            $salle2 = $row["salle2"];
                            $semaine2 = $row["semaine2"] !== "null" ? ' ' . $row["semaine2"] : null;
                            if($raison == null){
                                $raison = "non renseigné";
                            }
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
                            }else if($statut == 3){
                                $statutClass = "demande_reçue_button_rejet";
                                $statutName = "Rejetée";
                            }else if($statut = 4){
                                $statutClass = "demande_reçue_button_approuv";
                                $statutName = "Approuvée";
                            }

                            $idDemande = $row["idDemande"];
                            $demandeur = $row["demandeur"];
                            $sqlGetNotif = "SELECT idNotif FROM `notifications` WHERE idDemande = ? AND demandeur = ? AND typeNotif = 1 AND viewed = 0 LIMIT 1;";
                            $stmtGetNotif = $connect->prepare($sqlGetNotif);
                            $stmtGetNotif->bind_param("ss", $idDemande, $demandeur);
                            $stmtGetNotif->execute();
                            $stmtGetNotif->store_result();
                            if ($stmtGetNotif->num_rows !== 0) {
                                $stmtGetNotif->bind_result($idNotif);
                                
                                $stmtGetNotif->fetch();
                                $idDemande = $row["idDemande"];
                                $demandeur = $row["demandeur"];
                                $demande= array(
                                    "idDemande" => $idDemande,
                                    "demandeur" => $demandeur,
                                    "id_notif" => $idNotif
                                );
                                $data_row = htmlspecialchars(base64_encode(json_encode($demande)), ENT_QUOTES , 'UTF-8');
                                ?>
                                <div class="demande_reçue" data-row=<?= $data_row; ?>>
                                <?php
                            } else{
                                ?>                         
                                <div class="demande_reçue">
                            <?php
                            }
                            ?>
                                    <div class="demande_reçue_front">
                                        <div class="demande_reçue_header">
                                            <span class="demande_reçue_titre"><h2><?= $codeUV1 ?></h2><h2>-</h2><h2><?= $type1 . $semaine1 ?></h2></span>
                                            <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                        </div>
                                        <span class="demande_reçue_detail"><?= $jour1 . " " . $hDeb1 . "-" . $hFin1 . " " . $salle1 ?></span>
                                        <span class="demande_reçue_etudiant"><span class="demande_reçue_etudiant_titre">Etudiant:</span><span><?= $nom . " " . $prenom?></span></span>
                                    </div>
                                    
                                    <?php

                                if($statut == 0){
                                    ?>
                                    <div class="demande_reçue_back">
                                        <div class="demande_reçue_header">
                                            <span class="demande_reçue_titre"><h2><?= $codeUV1 ?></h2><h2>-</h2><h2><?= $type1 . $semaine1 ?></h2></span>
                                            <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                        </div>
                                        <span class="demande_reçue_motif"><span class="demande_reçue_motif_titre">Motif:</span><span><?= $raison ?></span></span>
                                        <div>
                                            <button class="demande_reçue_button_refus" onclick="choixDemande(false, this)">Refuser</button>
                                            <button class="demande_reçue_button_accept" onclick="choixDemande(true, this)">Accepter</button>
                                        </div>
                                    </div>
                                    
                                    <?php
                                }else{
                                    ?>
                                    <div class="demande_reçue_back">
                                        <div class="demande_reçue_header">
                                            <span class="demande_reçue_titre"><h2><?= $codeUV2 ?></h2><h2>-</h2><h2><?= $type2 . $semaine2 ?></h2></span>
                                            <button class="<?= $statutClass ?>"><?= $statutName ?></button>
                                        </div>
                                        <span class="demande_reçue_detail"><?= $jour2 . " " . $hDeb2 . "-" . $hFin2 . " " . $salle2 ?></span>
                                        <span class="demande_reçue_motif"><span class="demande_reçue_motif_titre">Motif:</span><span><?= $raison ?></span></span>
                                    </div>
                                    <?php
                                }
                                    
                                    ?>
                                </div>
                                <?php
                        }
                    }else{
                        ?>
                            <div class="message_aucune_demande">
                                <h1>Vous n'avez aucune demande de reçue</h1>
                            </div>
                        <?php
                    }
                ?>
                </div>
                <div id="demandes_reçues_fleches" class="demandes_profil_fleches">
                    <img src="../svg/fleche_gauche.svg" onclick="scrollToElement('reçu',false)" alt="">
                    <img src="../svg/fleche_droite.svg" onclick="scrollToElement('reçu',true)" alt="">
                </div>
            </div>
        </div>
    </main>
    <?php include "footer.php" ?>
    <div id="swap_pannel" class="mid_pannel">
        <div id="swap_header" class="mid_titre">
            <?php
                if(isset($_GET['typeSwap']) && !(empty($_GET['typeSwap']))){
                    echo "<script>document.getElementById('swap_pannel').style.display='flex'</script>";
                    $typeSwap = validateInput($_GET['typeSwap'],$connect);
                    if($typeSwap == "cours"){
                        echo "<h1>Mes Swaps en cours</h1>";
                    }else if($typeSwap == "accept"){
                        echo "<h1>Mes Swaps acceptés</h1>";
                    }else if($typeSwap == "refus"){
                        echo "<h1>Mes Swaps refusés</h1>";
                    }else if($typeSwap == "attente"){
                        echo "<h1>Mes Swaps en attente</h1>";
                    }
                }
            ?>
            <hr>
        </div>
        <img src="../svg/croix.svg" class="croix">
        <div id="swap_content" class="mid_content">
            <div class="demande_container">
            <?php /* Sera à récupérer une fois que l'étudiant sera login. */
                // Supposons que $result soit votre tableau de résultats de la requête SQL
                $connect = DBCredential();
                if(isset($_GET['typeSwap']) && !(empty($_GET['typeSwap']))){
                    $typeSwap = validateInput($_GET['typeSwap'],$connect);
                    if($typeSwap == "cours"){
                        $stmt = $connect->prepare("SELECT d1.idDemande as demande, d1.login, d1.codeUV , d1.type , d1.jour , d1.horaireDebut , d1.horaireFin , d1.salle , d1.semaine, p.nom , p.prenom, (SELECT count(demandeur) FROM swap WHERE demandeur = d1.idDemande) AS nbDemandes FROM `swap` s JOIN demande d1 ON d1.idDemande = s.demandeur JOIN demande d2 ON d2.idDemande=s.idDemande JOIN personne p ON p.login=d1.login WHERE d2.login = ? AND s.statut=2 UNION SELECT d1.idDemande as demande, d1.login, d1.codeUV , d1.type , d1.jour , d1.horaireDebut , d1.horaireFin , d1.salle , d1.semaine, p.nom , p.prenom, (SELECT count(idDemande) FROM swap WHERE idDemande = d1.idDemande) AS nbDemandes FROM `swap` s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN demande d2 ON d2.idDemande=s.demandeur JOIN personne p ON p.login=d1.login WHERE d2.login = ? AND s.statut=2");
                        $stmt->bind_param("ss" , $login, $login);
                    }else if($typeSwap == "accept"){
                        $stmt = $connect->prepare("SELECT d1.idDemande as demande, d1.login, d1.codeUV , d1.type , d1.jour , d1.horaireDebut , d1.horaireFin , d1.salle , d1.semaine, p.nom , p.prenom, (SELECT count(demandeur) FROM swap WHERE demandeur = d1.idDemande) AS nbDemandes FROM `swap` s JOIN demande d1 ON d1.idDemande = s.demandeur JOIN demande d2 ON d2.idDemande=s.idDemande JOIN personne p ON p.login=d1.login WHERE d2.login = ? AND s.statut=4 UNION SELECT d1.idDemande as demande, d1.login, d1.codeUV , d1.type , d1.jour , d1.horaireDebut , d1.horaireFin , d1.salle , d1.semaine, p.nom , p.prenom, (SELECT count(idDemande) FROM swap WHERE idDemande = d1.idDemande) AS nbDemandes FROM `swap` s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN demande d2 ON d2.idDemande=s.demandeur JOIN personne p ON p.login=d1.login WHERE d2.login = ? AND s.statut=4");
                        $stmt->bind_param("ss" , $login, $login);
                    }else if($typeSwap == "refus"){
                        $stmt = $connect->prepare("SELECT d1.idDemande as demande, d1.login, d1.codeUV , d1.type , d1.jour , d1.horaireDebut , d1.horaireFin , d1.salle , d1.semaine, p.nom , p.prenom, (SELECT count(demandeur) FROM swap WHERE demandeur = d1.idDemande) AS nbDemandes FROM `swap` s JOIN demande d1 ON d1.idDemande = s.demandeur JOIN demande d2 ON d2.idDemande=s.idDemande JOIN personne p ON p.login=d1.login WHERE d2.login = ? AND s.statut=3 UNION SELECT d1.idDemande as demande, d1.login, d1.codeUV , d1.type , d1.jour , d1.horaireDebut , d1.horaireFin , d1.salle , d1.semaine, p.nom , p.prenom, (SELECT count(idDemande) FROM swap WHERE idDemande = d1.idDemande) AS nbDemandes FROM `swap` s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN demande d2 ON d2.idDemande=s.demandeur JOIN personne p ON p.login=d1.login WHERE d2.login = ? AND s.statut=3");
                        $stmt->bind_param("ss" , $login, $login);
                    }else if($typeSwap == "attente"){
                        $stmt = $connect->prepare("SELECT idDemande as demande, codeUV, type, jour, horaireDebut, horaireFin, salle, semaine FROM `demande` d WHERE d.login = ? AND idDemande NOT IN (SELECT s.idDemande FROM `swap` s JOIN demande d1 ON d1.idDemande=s.idDemande WHERE d1.login = ? AND s.statut>=2) AND idDemande NOT IN (SELECT s.demandeur FROM `swap` s JOIN demande d1 ON d1.idDemande=s.demandeur WHERE d1.login = ? AND s.statut>=2)");
                        $stmt->bind_param("sss" , $login, $login, $login);
                    }
                
                    $isAvailable = $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();
                    if($result->num_rows === 0){
                        echo "<h2>Vous n'avez aucun swap de ce type</h2><style>.demande_container{justify-content: center;}</style>";
                    }else{
                        foreach ($result as $demande) {
                            // Assignation des valeurs du tableau à des variables
                            $UV = $demande['codeUV'];
                            $type = $demande['type'];
                            $jour = $demande['jour'];
                            $hdebut = $demande['horaireDebut'];
                            $hfin = $demande['horaireFin'];
                            $salle = $demande['salle'];
                            $semaine = $demande['semaine'];
                            $jours = array(
                                1 => 'Lundi',
                                2 => 'Mardi',
                                3 => 'Mercredi',
                                4 => 'Jeudi',
                                5 => 'Vendredi',
                                6 => 'Samedi',
                                7 => 'Dimanche'
                            );
                            $hdebut = substr($hdebut , 0 , 5);
                            $hfin = substr($hfin , 0 , 5);
                        ?>
                        <div class="div_demande">
                            <div class="gauche_container">
                                <div class="rectangle_demande"></div>
                                <div class="infos_uv">
                                    <h2><?php echo ($semaine == "null") ? "$UV - $type" : "$UV - $type $semaine"; ?></h2>
                                    <h4><?php echo "$jours[$jour] $hdebut - $hfin | $salle"; ?></h4>
                                </div>
                            </div>
                            <div class="swap_div_container">
                                <img class="swap_icon" src="../svg/swap_icon.svg" alt="" onclick="copierLien(this)">
                            </div>
                        </div>
                <?php } } }?>
            </div>
        </div>
        <div class="mid_button">
            <hr>
            <button id="swap_button_retour">Retour</button>
        </div>
    </div>
    <script src="../js/profil.js"></script>
</body>

</html>
