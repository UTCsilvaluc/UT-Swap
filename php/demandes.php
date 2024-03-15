<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/demandes.css">
    <link rel="stylesheet" href="../css/emploiDuTemps.css">
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
                <div class="demandes_filtre">
                    <span class="demandes_rechercher">
                        <input class="filtreInput" type="text" name="codeUV" id="filtre_demandes_codeUV" placeholder="Chercher une UV" value="<?php echo $_GET['codeUV'] ?? ''; ?>" maxlength="4" minlength="4">
                        <img src="../svg/search.svg" class="researchLogoSvg" alt="" onclick="researchUV(event)">
                    </span>
                    <div>
                        <img class="svgFiltre" title="filtres" src="../svg/FILTRE_FILTRE.svg" id="filtre_edt" onclick="openFiltre(event)">
                    </div>
                </div>
                <div class="demande_container">
                    <?php
                    $login = "silvaluc"; /* Sera à récupérer une fois que l'étudiant sera login. */
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
                        $data_row = htmlspecialchars(base64_encode(json_encode($demande)), ENT_QUOTES , 'UTF-8');
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
            <div class="filtres" id="menuFiltre">
                <div class="filtre_titre">
                    <h1>Filtrer par</h1>
                    <div id="filtre_croix">
                        <img src="../svg/black_cross.svg" alt="" id="CloseOpenFiltreMenu" onclick="closeFiltre(event)">
                    </div>
                </div>
                <div class="conteneur_filtre" id="filterContainer1">
                    <div class="filtre_parent" id="police">
                        <h1 class="filtre_entete">Trier par</h1>
                        <span class="filtre_span">
                            <h3 class="checkElement" onclick="changeFilter(event)" id="mainFilter">Pertinence</h3>
                            <h3 class="uncheckElement" onclick="changeFilter(event))">Date</h3>
                            <h3 class="uncheckElement" onclick="changeFilter(event)">Demande</h3>
                            <h3 class="uncheckElement" onclick="changeFilter(event)">Auteur</h3>
                        </span>
                    </div>
                    <div class="filtre_parent" id="jours">
                        <h1 class="filtre_entete">Jour</h1>
                        <span class="filtre_span" id="spanJour">
                            <h3 class="check" onclick="changeJour(event)">Lundi</h3>
                            <h3 class="check" onclick="changeJour(event)">Mardi</h3>
                            <h3 class="check" onclick="changeJour(event)">Mercredi</h3>
                            <h3 class="check" onclick="changeJour(event)">Jeudi</h3>
                            <h3 class="check" onclick="changeJour(event)">Vendredi</h3>
                            <h3 class="check" onclick="changeJour(event)">Samedi</h3>
                    </div>
                    <div class="filtre_parent" id="type">
                        <h1 class="filtre_entete">Type</h1>
                        <span class="filtre_span" id="spanType">
                            <h3 class="check" onclick="changeType(event)">Cours</h3>
                            <h3 class="check" onclick="changeType(event)">TD</h3>
                            <h3 class="check" onclick="changeType(event)">TP</h3>
                    </div>
                    <div class="filtre_parent" id="jours">
                        <h1 class="filtre_entete">Semaine</h1>
                        <span class="filtre_span" id="spanSemaine">
                            <h3 class="check" onclick="changeType(event)">A</h3>
                            <h3 class="check" onclick="changeType(event)">B</h3>
                    </div>
                    <div class="filtre_parent" id="heures">
                        <h1>Horaires</h1>
                        <span class="filtre_span">
                            <div>
                                <input type="time" id="filtre-input-hdebut" name="hdebut" value="08:00" required onchange="filtreTime(event)">
                            </div>
                            <div>
                                <input type="time" id="filtre-input-hfin" name="hfin" value="20:00" required onchange="filtreTime(event)">
                            </div>
                        </span>
                    </div>
                    <div class="buttonFiltres">
                        <button class="filtreButton" id="appliquerFiltre" onclick="resetFilter(event)">Supprimer les filtres</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../js/demandes.js"></script>
</body>

</html>
