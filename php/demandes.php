<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/demandes.css">
    <title>Demandes - UT'Swap</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    <main>
        <?php
        if (isset($_GET['codeUV'])){
            $UV = $_GET['codeUV'];
            echo $UV;
        } else {
        }
        ?>
        <div class="main_conteneur">
            <div class="demandesControl">
                <div class="filtres">
                    <form action="demandes.php" method="get" id="filterForm">
                    <span class="rechercher">
                        <input class="filtreInput" type="text" name="codeUV" id="codeUV" placeholder="Chercher une UV" value="<?php echo $_GET['codeUV'] ?? ''; ?>">
                        <img id="filterUV" class="svgFiltre" src="../svg/search.svg" alt="" onclick="filtrerUV(event)">
                    </span>
                        <span class="sort">

                        <button type="submit">Rechercher</button>

                        <img id="filterUV" class="svgFiltre" src="../svg/search.svg" alt="" onclick="filtrerUV(event)">
                    </span>
                        <span class="jour">

                    </span>
                        <span class="lieu">

                    </span>
                        <span class="type">

                    </span>
                        <span class="other">

                    </span>
                    </form>
                </div>
                <div class="demande_container">
                    <?php
                    $login = ""; /* Sera à récupérer une fois que l'étudiant sera login. */
                    // Supposons que $result soit votre tableau de résultats de la requête SQL
                    $connect = DBCredential();
                    $stmt = $connect->prepare("SELECT d.idDemande , d.login , d.codeUV , d.type , d.jour ,  d.horaireDebut , d.horaireFin , d.salle , d.semaine , e.login , p.nom , p.prenom , (SELECT count(idDemande) FROM swap WHERE idDemande = d.idDemande) AS nbDemandes FROM demande as d JOIN etudiant as e ON e.login = d.login JOIN personne as p ON p.login = e.login WHERE demande = 1 AND d.login != ?");
                    $stmt->bind_param("s" , $login);
                    $isAvailable = $stmt->execute();
                    $result = $stmt->get_result();
                    $stmt->close();

                    /* Récupérer les demandes effectuées par l'étudiant pour ne pas les afficher. */
                    $stmtGetSwap = $connect->prepare("SELECT s.idDemande FROM swap as s JOIN demande as d ON d.idDemande = s.demandeur WHERE d.login = ?");
                    $stmtGetSwap->bind_param("s" , $login);
                    $stmtGetSwap->execute();
                    $idList = $stmtGetSwap->get_result();
                    $listIdDemandeUser = array();
                    foreach ($idList as $item) {
                        $listIdDemandeUser[] = $item['idDemande'];
                    }
                    foreach ($result as $demande) {
                        // Assignation des valeurs du tableau à des variables
                        $idDemande = $demande['idDemande'];
                        $UV = $demande['codeUV'];
                        $type = $demande['type'];
                        $jour = $demande['jour'];
                        $hdebut = $demande['horaireDebut'];
                        $hfin = $demande['horaireFin'];
                        $salle = $demande['salle'];
                        $semaine = $demande['semaine'];
                        $nom = $demande['nom'];
                        $prenom = $demande['prenom'];
                        $login = $demande['login'];
                        $nbDemandes = $demande["nbDemandes"];
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
                        $data_row = htmlspecialchars(json_encode($demande) , ENT_QUOTES , 'UTF-8');
                        if (!(in_array($idDemande , $listIdDemandeUser))){

                        ?>
                        <div class="div_demande" onclick="clickDemande(this)" data-row=<?php echo "$data_row"; ?>>
                            <div class="gauche_container">
                                <img class="rectangle_demande" src="../svg/rectangle_demande.svg" alt="">
                                <div class="infos_uv">
                                    <h2><?php echo ($semaine == "null") ? "$UV - $type" : "$UV - $type $semaine"; ?></h2>
                                    <h4><?php echo "$jours[$jour] $hdebut - $hfin | $salle"; ?></h4>
                                </div>
                            </div>

                            <div class="infos_auteur">
                        <span class="span_auteur">
                            <p>Auteur:</p> <h5><?php echo "$nom $prenom"; ?></h5>
                        </span>
                                <span class="span_nb_demandes">
                            <p>Demande(s):</p> <h5><?php echo $nbDemandes; ?></h5>
                        </span>
                            </div>

                            <div class="swap_div_container">
                                <img class="swap_icon" src="../svg/swap_icon.svg" alt="" onclick="copierLien(this)">
                            </div>
                        </div>
                    <?php } } ?>
                </div>
            </div>
            <div class="misc_container">

            </div>
        </div>
    </main>

    <script src="../js/demandes.js"></script>
</body>

</html>
