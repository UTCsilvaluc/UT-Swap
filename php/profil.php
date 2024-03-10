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
            return $prenom . " " . $nom;
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
    ?>
    <main>
        <div class="arc-de-cercle"></div>
        <img src="../svg/profil.svg" id="profil_image">
        <div id="fond_profil_image"></div>
        <img src="../svg/logout.svg" id="logout_image">
        <div id="fond_logout_image"></div>
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
                            <span class="profil_demande_header"><img src="../svg/dmd_attent.svg" alt=""><span>0</span></span>
                            <h2>Demandes en cours</h2>
                        </div>
                        <div id="profil_demande_accept" class="profil_demande">
                            <span class="profil_demande_header"><img src="../svg/dmd_accept.svg" alt=""><span>0</span></span>
                            <h2>Demandes acceptées</h2>
                        </div>
                        <div id="profil_demande_refus" class="profil_demande">
                            <span class="profil_demande_header"><img src="../svg/dmd_refus.svg" alt=""><span>0</span></span>
                            <h2>Demandes refusées</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="demandes_faites_utilisateur" class="profil_pannel">
            <div id="demandes_faites_header" class="demandes_profil_header">
                <span class="demandes_profil_titre"><span class="tictac"></span><h1>Demandes faites</h1></span>
            </div>
            <div id="demandes_faites_content" class="demandes_profil_content">
                <div id="demandes_faites" class="demandes_profil">
                    <div class="demande_faite">
                        <div class="demande_faite_header">
                            <span class="demande_faite_titre"><h2>MT23</h2><h2>-</h2><h2>TD</h2></span>
                            <button class="demande_faite_button_accept">Acceptée</button>
                        </div>
                        <span class="demande_faite_detail">Lundi 12h15-14h15 FA415</span>
                        <span class="demande_faite_etudiant"><span class="demande_faite_etudiant_titre">Etudiant:</span><span>Nom Prénom</span></span>
                    </div>
                    <div class="demande_faite">
                        <div class="demande_faite_header">
                            <span class="demande_faite_titre"><h2>MT23</h2><h2>-</h2><h2>TD</h2></span>
                            <button class="demande_faite_button_refus">Refusée</button>
                        </div>
                        <span class="demande_faite_detail">Lundi 12h15-14h15 FA415</span>
                        <span class="demande_faite_etudiant"><span class="demande_faite_etudiant_titre">Etudiant:</span><span>Nom Prénom</span></span>
                    </div>
                    <div class="demande_faite">
                        <div class="demande_faite_header">
                            <span class="demande_faite_titre"><h2>MT23</h2><h2>-</h2><h2>TD</h2></span>
                            <button class="demande_faite_button_attent">Attente</button>
                        </div>
                        <span class="demande_faite_detail">Lundi 12h15-14h15 FA415</span>
                        <span class="demande_faite_etudiant"><span class="demande_faite_etudiant_titre">Etudiant:</span><span>Nom Prénom</span></span>
                    </div>
                    <div class="demande_faite">
                        <div class="demande_faite_header">
                            <span class="demande_faite_titre"><h2>MT23</h2><h2>-</h2><h2>TD</h2></span>
                            <button class="demande_faite_button_accept">Acceptée</button>
                        </div>
                        <span class="demande_faite_detail">Lundi 12h15-14h15 FA415</span>
                        <span class="demande_faite_etudiant"><span class="demande_faite_etudiant_titre">Etudiant:</span><span>Nom Prénom</span></span>
                    </div>
                    <div class="demande_faite">
                        <div class="demande_faite_header">
                            <span class="demande_faite_titre"><h2>MT23</h2><h2>-</h2><h2>TD</h2></span>
                            <button class="demande_faite_button_attent">Attente</button>
                        </div>
                        <span class="demande_faite_detail">Lundi 12h15-14h15 FA415</span>
                        <span class="demande_faite_etudiant"><span class="demande_faite_etudiant_titre">Etudiant:</span><span>Nom Prénom</span></span>
                    </div>
                </div>
                <div id="demandes_faites_fleches" class="demandes_profil_fleches">
                    <img src="../svg/fleche_gauche.svg" onclick="scrollToElement(false)" alt="">
                    <img src="../svg/fleche_droite.svg" onclick="scrollToElement(true)" alt="">
                </div>
            </div>
        </div>
        
        <div id="demandes_reçues_utilisateur" class="profil_pannel">
            <div id="demandes_reçues_header" class="demandes_profil_header">
                <span class="demandes_profil_titre"><span class="tictac"></span><h1>Demandes reçues</h1></span>
            </div>
            <div id="demandes_reçues_content" class="demandes_profil_content">
                <div id="demandes_reçues" class="demandes_profil">
                    <div class="demande_reçue">
                        <div class="demande_reçue_header">
                            <span class="demande_reçue_titre"><h2>MT23</h2><h2>-</h2><h2>TD</h2></span>
                            <button class="demande_reçue_button_accept">Acceptée</button>
                        </div>
                        <span class="demande_reçue_detail">Lundi 12h15-14h15 FA415</span>
                        <span class="demande_reçue_etudiant"><span class="demande_reçue_etudiant_titre">Etudiant:</span><span>Nom Prénom</span></span>
                    </div>
                    <div class="demande_reçue">
                        <div class="demande_reçue_header">
                            <span class="demande_reçue_titre"><h2>MT23</h2><h2>-</h2><h2>TD</h2></span>
                            <button class="demande_reçue_button_refus">Refusée</button>
                        </div>
                        <span class="demande_reçue_detail">Lundi 12h15-14h15 FA415</span>
                        <span class="demande_reçue_etudiant"><span class="demande_reçue_etudiant_titre">Etudiant:</span><span>Nom Prénom</span></span>
                    </div>
                    <div class="demande_reçue">
                        <div class="demande_reçue_header">
                            <span class="demande_reçue_titre"><h2>MT23</h2><h2>-</h2><h2>TD</h2></span>
                            <button class="demande_reçue_button_attent">Attente</button>
                        </div>
                        <span class="demande_reçue_detail">Lundi 12h15-14h15 FA415</span>
                        <span class="demande_reçue_etudiant"><span class="demande_reçue_etudiant_titre">Etudiant:</span><span>Nom Prénom</span></span>
                    </div>
                </div>
                <div id="demandes_reçues_fleches" class="demandes_profil_fleches">
                    <img src="../svg/fleche_gauche.svg" onclick="scrollToElement(false)" alt="">
                    <img src="../svg/fleche_droite.svg" onclick="scrollToElement(true)" alt="">
                </div>
            </div>
        </div>
    </main>

    <script src="../js/profil.js"></script>
</body>

</html>
