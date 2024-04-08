<?php
function redirect($url){
    echo "<script>window.location.href = '".$url."';</script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/profil.css">
    <link rel="stylesheet" href="../css/gestion.css">
    <link rel="stylesheet" href="../css/demande_content.css">
    <link rel="stylesheet" href="../css/mid_pannel.css">
    <title>Gestion - UT'Swap</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    <?php
    
    function updateSwapProf($choix, $demandeur, $idDemande, $connect){
        $sqlGetLogins = "SELECT d1.login, d2.login FROM swap s JOIN demande d1 ON d1.idDemande=s.idDemande JOIN demande d2 ON d2.idDemande=s.demandeur WHERE s.demandeur = ? AND s.idDemande = ?";
        $stmtGetLogins = $connect->prepare($sqlGetLogins);
        $stmtGetLogins->bind_param("ss", $demandeur, $idDemande);
        $stmtGetLogins->execute();
        $stmtGetLogins->store_result();
        $stmtGetLogins->bind_result($loginIdDemande, $loginDemandeur);
        $stmtGetLogins->fetch();
        sendNotifications($loginIdDemande, $idDemande, $demandeur, 2, $choix+3, $connect);
        sendNotifications($loginDemandeur, $idDemande, $demandeur, 2, $choix+3, $connect);
        $sqlUpdateSwap = "UPDATE swap SET statut = ? WHERE idDemande = ? AND demandeur = ?";
        $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
        $choix = $choix+3;
        $stmtUpdateSwap->bind_param("sss", $choix, $idDemande, $demandeur);         
        $stmtUpdateSwap->execute();
    }

    if (
        isset($_POST['choix'], $_POST['demandeur'], $_POST['idDemande']) &&
        (!empty($_POST['choix']) || validateInput($_POST['choix'],$connect) === "0") && !empty($_POST['demandeur']) && !empty($_POST['idDemande'])
    ){
        $connect = DBCredential();
        updateSwapProf($_POST['choix'], $_POST['demandeur'], $_POST['idDemande'], $connect);
    }
    function choixTouteDemande($codeUV, $choix){
        global $login;
        $connect = DBCredential();
        if($codeUV != null){
            $sqlSelectInfo = "SELECT d1.login as login1, d2.login as login2, s.idDemande, s.demandeur FROM swap s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur JOIN uv u ON u.codeUV = d1.codeUV WHERE s.statut = 2 AND u.responsable = ? AND u.codeUV = ?";
            $stmtSelectInfo = $connect->prepare($sqlSelectInfo);
            $stmtSelectInfo->bind_param("ss", $login, $codeUV);
        }else{
            $sqlSelectInfo = "SELECT d1.login as login1, d2.login as login2, s.idDemande, s.demandeur FROM swap s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur JOIN uv u ON u.codeUV = d1.codeUV WHERE s.statut = 2 AND u.responsable = ?";
            $stmtSelectInfo = $connect->prepare($sqlSelectInfo);
            $stmtSelectInfo->bind_param("s", $login);
        }
        
        $stmtSelectInfo->execute();
        $resultat = $stmtSelectInfo->get_result();
        
        if ($resultat->num_rows > 0) {
            foreach ($resultat as $row) {
                sendNotifications($row["login1"], $row["idDemande"], $row["demandeur"], 2, $choix, $connect);
                sendNotifications($row["login2"], $row["idDemande"], $row["demandeur"], 2, $choix, $connect);
            }
            if($codeUV != null){
                $sqlUpdateSwap = "UPDATE swap s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN uv u ON u.codeUV = d1.codeUV SET s.statut=? WHERE s.statut = 2 AND u.responsable = ? AND u.codeUV = ?";
                $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
                $stmtUpdateSwap->bind_param("sss", $choix, $login, $codeUV);
            }else{
                $sqlUpdateSwap = "UPDATE swap s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN uv u ON u.codeUV = d1.codeUV SET s.statut=? WHERE s.statut = 2 AND u.responsable = ?";
                $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
                $stmtUpdateSwap->bind_param("ss", $choix, $login);
            }
            $stmtUpdateSwap->execute();
            redirect("gestion.php");
        }
    }
    if(isset($_POST['codeUVChoix']) && !(empty($_POST['codeUVChoix']))){
        $codeUV = validateInput($_POST['codeUVChoix'],$connect);
        $choixChangement = isset($_POST['choixChangementUV']);
        if(strlen($codeUV) === 4){
            $sqlUpdateSwapUV = "UPDATE uv SET swap=? WHERE responsable = ? AND codeUV = ?";
            $stmtUpdateSwapUV = $connect->prepare($sqlUpdateSwapUV);
            $stmtUpdateSwapUV->bind_param("sss", $choixChangement, $login, $codeUV);
            $stmtUpdateSwapUV->execute();

            if (!$choixChangement) {
                choixTouteDemande($codeUV, 3);
            }
        }
    }

    if(isset($_POST['codeUV'], $_POST['choixSwap']) && !(empty($_POST['codeUV'])) && !(empty($_POST['choixSwap']))){
        $codeUV = validateInput($_POST['codeUV'],$connect);
        $choixSwap = (validateInput($_POST['choixSwap'],$connect) === "true") ? 4 : 3;
        if(strlen($codeUV) === 4){
            choixTouteDemande($codeUV, $choixSwap);
        }
    }

    if(isset($_POST['choixChangement']) && !(empty($_POST['choixChangement']))){
        $choixChangement = (validateInput($_POST['choixChangement'],$connect) === "true") ? 1 : 0;

        $sqlUpdateSwap = "UPDATE uv SET swap=? WHERE responsable = ?";
        $stmtUpdateSwap = $connect->prepare($sqlUpdateSwap);
        $stmtUpdateSwap->bind_param("ss", $choixChangement, $login);
        $stmtUpdateSwap->execute();

        if (!$choixChangement) {
            choixTouteDemande(null, 3);
        }
    }

 
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

    function getNbSwapProfil($type){
        global $login;
        $connect = DBCredential();
        if($type === "cours"){
            $sqlNbSwap = "SELECT COUNT(*) FROM swap s JOIN demande d ON s.idDemande = d.idDemande WHERE d.codeUV IN (SELECT codeUV FROM uv WHERE responsable = ?) AND s.statut=2";
        }else if($type === "accept"){
            $sqlNbSwap = "SELECT COUNT(*) FROM swap s JOIN demande d ON s.idDemande = d.idDemande WHERE d.codeUV IN (SELECT codeUV FROM uv WHERE responsable = ?) AND s.statut=4";
        }else if($type === "refus"){
            $sqlNbSwap = "SELECT COUNT(*) FROM swap s JOIN demande d ON s.idDemande = d.idDemande WHERE d.codeUV IN (SELECT codeUV FROM uv WHERE responsable = ?) AND s.statut=3";
        }
        $stmtNbSwap = $connect->prepare($sqlNbSwap);
        $stmtNbSwap->bind_param("s", $login);
        $stmtNbSwap->execute();
        $stmtNbSwap->store_result();
        if ($stmtNbSwap->num_rows !== 0) {
            $stmtNbSwap->bind_result($nbSwap);
            $stmtNbSwap->fetch();
            return $nbSwap;
        }
        return "0";
    }

    function getNbSwapUv($type, $codeUV){
        global $login;
        $connect = DBCredential();
        if($type === "cours"){
            $sqlNbSwap = "SELECT COUNT(*) FROM swap s JOIN demande d ON s.idDemande = d.idDemande WHERE d.codeUV IN (SELECT codeUV FROM uv WHERE responsable = ?) AND s.statut=2 AND d.codeUV = ?";
        }else if($type === "accept"){
            $sqlNbSwap = "SELECT COUNT(*) FROM swap s JOIN demande d ON s.idDemande = d.idDemande WHERE d.codeUV IN (SELECT codeUV FROM uv WHERE responsable = ?) AND s.statut=4 AND d.codeUV = ?";
        }else if($type === "refus"){
            $sqlNbSwap = "SELECT COUNT(*) FROM swap s JOIN demande d ON s.idDemande = d.idDemande WHERE d.codeUV IN (SELECT codeUV FROM uv WHERE responsable = ?) AND s.statut=3 AND d.codeUV = ?";
        }
        $stmtNbSwap = $connect->prepare($sqlNbSwap);
        $stmtNbSwap->bind_param("ss", $login, $codeUV);
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

    function isChecked($codeUV){
        global $login;
        $connect = DBCredential();
        if($codeUV !== null){
            $attributs = "name='choixChangementUV' id='checkboxChangementUV'";
            $sqlSelectSwap0 = "SELECT * FROM uv WHERE responsable = ? AND swap = 0 AND codeUV = ?";
            $stmtSelectSwap0 = $connect->prepare($sqlSelectSwap0);
            $stmtSelectSwap0->bind_param("ss", $login, $codeUV);
        }else{
            $attributs = "name='choixChangement' id='checkboxChangementGlobal'";
            $sqlSelectSwap0 = "SELECT * FROM uv WHERE responsable = ? AND swap = 0";
            $stmtSelectSwap0 = $connect->prepare($sqlSelectSwap0);
            $stmtSelectSwap0->bind_param("s", $login);
        }
        $stmtSelectSwap0->execute();
        $resultat = $stmtSelectSwap0->get_result();
        // Vérifier s'il y a des résultats
        if ($resultat->num_rows > 0) {
            ?>
            <input type="checkbox" <?= $attributs ?>>
            <?php
            return;
        }
        ?>
        <input type="checkbox" <?= $attributs ?> checked>
        <?php
    }
    ?>
    <main>
        <div class="arc-de-cercle"></div>
        <img src="../svg/profil.svg" id="profil_image">
        <div id="fond_profil_image"></div>
        <div id="profil_utilisateur" class="profil_pannel">
            <div class="profil_header">
                <span class="profil_titre"><span class="tictac"></span><h1>Mon profil</h1></span>
                <button>Se déconnecter</button>
            </div>
            <div id="profil_content">
                <div id="profil_texte">
                    <h1 id="profil_id"><?= getProfil(); ?></h1>
                    <div id="profil_uv_parent">
                        <h1>UV(s):
                            <div id="profil_uv"><?php 
                                $connect = DBCredential();
                                $sqlUvs = "SELECT codeUV FROM uv WHERE responsable = ?";
                                $stmtUvs = $connect->prepare($sqlUvs);
                                $stmtUvs->bind_param("s", $login);
                                $stmtUvs->execute();
                                $result = $stmtUvs->get_result();
                                if ($result->num_rows !== 0) {
                                    foreach ($result as $demande) {
                                        ?>
                                        <button onclick='gestionUv("<?= $demande["codeUV"] ?>")'><?= $demande["codeUV"] ?></button>
                                        <?php
                                    }
                                }
                    
                                ?>
                            </div>
                        </h1>
                        <div id="profil_choix_uv_parent">
                            <?php isChecked(null) ?><label for="checkboxChangementGlobal">Autoriser les changements de groupe au sein de mes UV(s)</label>
                        </div>
                    </div>
                </div>
                <div class="profil_demandes">
                    <div class="profil_demandes_parent">
                        <div id="profil_demande_cours" class="profil_demande">
                            <span class="profil_demande_header"><img src="../svg/demande_cours.svg" alt=""><span><?= getNbSwapProfil("cours"); ?></span></span>
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
        <div id="demandes_professeur_professeur" class="profil_pannel">
            <div id="demandes_professeur_header" class="profil_header">
                <span class="demandes_profil_titre"><span class="tictac"></span><h1>Mes demandes</h1></span>
            </div>
            <div id="demandes_professeur_content">
                <div id="demandes_professeur" class="demandes_profil">
                    <?php
                    $connect = DBCredential();
                    $sqlSwaps = "SELECT s.statut, s.idDemande, s.demandeur, d1.codeUV, d1.type, d1.jour as jour1, d1.horaireDebut as hDeb1, d1.horaireFin as hFin1, d2.jour as jour2, d2.horaireDebut as hDeb2, d2.horaireFin as hFin2, p1.nom as nom1, p1.prenom as prenom1, p2.nom as nom2,p2.prenom as prenom2, d1.raison as raison1, d2.raison as raison2, d1.semaine as semaine1, d2.semaine as semaine2 FROM swap s JOIN demande d1 ON d1.idDemande = s.idDemande JOIN demande d2 ON d2.idDemande = s.demandeur JOIN personne p1 ON p1.login = d1.login JOIN personne p2 ON p2.login = d2.login JOIN UV u ON u.codeUV = d1.codeUV WHERE u.responsable = ? AND s.statut >= 2 ORDER BY CASE statut WHEN 2 THEN 1 WHEN 4 THEN 2 WHEN 3 THEN 3 ELSE 4 END;";
                    $stmtSwaps = $connect->prepare($sqlSwaps);
                    $stmtSwaps->bind_param("s", $login);
                    $stmtSwaps->execute();
                    $result = $stmtSwaps->get_result();
                    if($result->num_rows > 0){
                        foreach ($result as $row) {
                            $codeUV = $row["codeUV"];
                            $type = $row["type"];
                            $idDemande = $row["idDemande"];
                            $demandeur = $row["demandeur"];
                            $statut = $row["statut"];
                            $jours = array(
                                1 => 'Lundi',
                                2 => 'Mardi',
                                3 => 'Mercredi',
                                4 => 'Jeudi',
                                5 => 'Vendredi',
                                6 => 'Samedi',
                                7 => 'Dimanche'
                            );

                            $nom1 = ucfirst($row["nom1"]);
                            $prenom1 = ucfirst($row["prenom1"]);
                            $jour1 = $jours[$row["jour1"]];
                            $hDeb1 = formaterHeure($row["hDeb1"]);
                            $hFin1 = formaterHeure($row["hFin1"]);
                            $semaine1 = $row["semaine1"];
                            $raison1 = $row["raison1"];
                            if($raison1 == null){
                                $raison1 = "non renseigné";
                            }
                            
                            $nom2 = ucfirst($row["nom2"]);
                            $prenom2 = ucfirst($row["prenom2"]);
                            $jour2 = $jours[$row["jour2"]];
                            $hDeb2 = formaterHeure($row["hDeb2"]);
                            $hFin2 = formaterHeure($row["hFin2"]);
                            $semaine2 = $row["semaine2"];
                            $raison2 = $row["raison2"];
                            if($raison2 == null){
                                $raison2 = "non renseigné";
                            }

                            if($semaine2 != $semaine1){
                                $semaine = $semaine1."/".$semaine2;
                            }else{
                                if($semaine1 != "null"){
                                    $semaine = " ".$semaine1;
                                }else{
                                    $semaine = "";
                                }
                            }

                            $demande= array(
                                "idDemande" => $idDemande,
                                "demandeur" => $demandeur
                            );
                            
                            $data_row = htmlspecialchars(base64_encode(json_encode($demande)), ENT_QUOTES , 'UTF-8');
                            ?>
                            <div class="demande_professeur" onclick="" data-row=<?= $data_row ?>>
                                <div class="gauche_container">
                                    <div class="rectangle_demande"></div>
                                    <div class="infos_uv">
                                        <h2><?= $codeUV ?> - <?= $type ?> <?= $semaine ?></h2>
                                    </div>
                                </div>
                                <div class="mid_container">
                                    <div class="infos_swap">
                                        <div class="infos_etudiant">
                                            <div class="nom_etudiant">
                                                <label class="grey_element">Etudiant:</label><label><?= $nom1 ?> <?= $prenom1 ?></label>
                                            </div>
                                            <div class="horaire_etudiant">
                                                <label class="grey_element">Horaire:</label><label><?= $jour1 ?> <?= $hDeb1 ?> - <?= $hFin1 ?></label>
                                            </div>
                                        </div>
                                        <div class="infos_etudiant_facultatif">
                                            <div class="branche_etudiant">
                                                <label class="grey_element">Branche:</label><label>TC</label>
                                            </div>
                                            <div class="motivation_etudiant">
                                                <label class="grey_element">Motivation:</label><label><?= $raison1 ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="swap_div_container">
                                        <img class="swap_icon" src="../svg/swap_icon.svg">
                                    </div>
                                    <div class="infos_swap">
                                        <div class="infos_etudiant">
                                            <div class="nom_etudiant">
                                                <label class="grey_element">Etudiant:</label><label><?= $nom2 ?> <?= $prenom2 ?></label>
                                            </div>
                                            <div class="horaire_etudiant">
                                                <label class="grey_element">Horaire:</label><label><?= $jour2 ?> <?= $hDeb2 ?> - <?= $hFin2 ?></label>
                                            </div>
                                        </div>
                                        <div class="infos_etudiant_facultatif">
                                            <div class="branche_etudiant">
                                                <label class="grey_element">Branche:</label><label>TC</label>
                                            </div>
                                            <div class="motivation_etudiant">
                                                <label class="grey_element">Motivation:</label><label><?= $raison2 ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if($statut == 2){
                                    ?>
                                    <div class="button_choix_etudiant">
                                        <button onclick="choixProfesseurSwap(true, this)"><img src="../svg/check_vert.svg"></button>
                                        <button onclick="choixProfesseurSwap(false, this)"><img src="../svg/croix_rouge.svg"></button>
                                    </div>
                                    <?php
                                }else if($statut == 3){
                                    ?>
                                    <div class="button_choix_etudiant">
                                        <button><img src="../svg/croix_rouge.svg"></button>
                                    </div>
                                    <?php
                                }else if($statut == 4){
                                    ?>
                                    <div class="button_choix_etudiant">
                                        <button><img src="../svg/check_vert.svg"></button>
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
            </div>
        </div>
        
    </main>
    <div>
    <div class="mid_titre">
            <hr>
        </div>
        <img src="../svg/croix.svg" class="croix">
        <div class="mid_content">
            
        </div>
        <div class="mid_button">
            <hr>
            <button id="choix_prof_button_refuser">Refuser</button>
            <button id="choix_prof_button_accepter">Accepter</button>
        </div>
    </div>
    <form id="uv_pannel" class="mid_pannel" method="post" action="gestion.php" style="display: none;">
        <div id="uv_header" class="mid_titre">
            <?php
                if(isset($_GET['codeUV']) && !(empty($_GET['codeUV']))){
                    $codeUV = validateInput($_GET['codeUV'],$connect);
                    echo "<h1>Gérer l'UV ".$codeUV."</h1>";
                    echo "<script>document.getElementById('uv_pannel').style.display='flex'</script>";
                }else{
                    echo "<h1>Gérer le(s) UV(s)</h1>";
                }
            ?>
            <hr>
        </div>
        <img src="../svg/croix.svg" class="croix">
        <div id="message_attention_prof" class="mid_content hidden">
             <?php
            if(isset($_GET['codeUV']) && !(empty($_GET['codeUV']))){
                $codeUV = validateInput($_GET['codeUV'],$connect);
                echo "<p>Vous vous apprétez à désactiver la possibilité de faire des échanges pour l'UV: ".$codeUV.". Si vous poursuivez toutes les demandes déjà réalisées seront et resteront refusées</p>";
            }else{
                echo "<p>Vous vous apprétez à désactiver la possibilité de faire des échanges pour toutes vos UVs. Si vous poursuivez toutes les demandes déjà réalisées seront et resteront refusées</p>";
            }
            ?>
        </div>
        <div id="uv_content" class="mid_content">
            <?php
                if(isset($_GET['codeUV']) && !(empty($_GET['codeUV']))){
                    $codeUV = validateInput($_GET['codeUV'],$connect);
                ?>
                
            <div class="profil_demandes">
                <div class="profil_demandes_parent">
                    <div id="uv_demande_cours" class="profil_demande">
                        <span class="profil_demande_header"><img src="../svg/demande_cours.svg" alt=""><span><?= getNbSwapUv("cours", $codeUV); ?></span></span>
                        <h2>Swaps en cours</h2>
                    </div>
                    <div id="uv_demande_accept" class="profil_demande">
                        <span class="profil_demande_header"><img src="../svg/dmd_accept.svg" alt=""><span><?= getNbSwapUv("accept", $codeUV); ?></span></span>
                        <h2>Swaps acceptés</h2>
                    </div>
                    <div id="uv_demande_refus" class="profil_demande">
                        <span class="profil_demande_header"><img src="../svg/dmd_refus.svg" alt=""><span><?= getNbSwapUv("refus", $codeUV); ?></span></span>
                        <h2>Swaps refusés</h2>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
            <div>
                <button onclick="gererUVChoix(false)">Refuser Tout</button>
                <button onclick="gererUVChoix(true)">Accepter Tout</button>
            </div>
            <div>
                
            <?php
                if(isset($_GET['codeUV']) && !(empty($_GET['codeUV']))){
                    echo '<input type="hidden" value="'.$codeUV.'" name="codeUVChoix">';
                    isChecked($codeUV);
                }
            ?>
                <label for="checkboxChangementUV">Autoriser les changements de groupe</label>
            </div>
        </div>
        <div class="mid_button">
            <hr>
            <button id="uv_button_enregistrer_none">Enregistrer</button>
            <button id="uv_button_enregistrer_global" class="hidden">Enregistrer</button>
            <div id="uv_boutons_parent">
                <button id="uv_button_retour" class="hidden">Retour</button>
                <button id="uv_button_enregistrer_submit" class="hidden">Enregistrer</button>
            </div>
        </div>
    </form>
    <script src="../js/gestion.js"></script>
    <script src="../js/profil.js"></script>
</body>

</html>
