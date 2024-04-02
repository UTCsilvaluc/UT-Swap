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
    <link rel="stylesheet" href="../css/mid_pannel.css">
    <title>Gestion - UT'Swap</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    <?php
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
            <div id="profil_header">
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
        
    </main>
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
            <p>Vous vous apprétez à désactiver la possibilité de faire des échanges pour l'UV: <?php
            if(isset($_GET['codeUV']) && !(empty($_GET['codeUV']))){
                $codeUV = validateInput($_GET['codeUV'],$connect);
                echo $codeUV;
            }
            ?>. Si vous poursuivez toutes les demandes déjà réalisées seront et resteront refusées</p>
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
