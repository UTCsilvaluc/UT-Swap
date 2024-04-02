<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/demandes.css">
    <link rel="stylesheet" href="../css/demande_content.css">
    <link rel="stylesheet" href="../css/emploiDuTemps.css">
    <title>Demandes - UT'Swap</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    <main>
        <div class="demandes_filtre_parent">
            <div class="demandes_filtre">
                <input class="filtreInput" type="text" name="codeUV" id="filtre_demandes_codeUV" placeholder="Chercher une UV" value="<?= $_GET['codeUV'] ?? ''; ?>" maxlength="4" minlength="4">
                <input class="filtreInput" type="text" name="creneau" id="filtre_demandes_creneau" placeholder="Chercher un créneau" value="<?= $_GET['jour'] ?? ''; ?>">
                <img src="../svg/search.svg" class="researchLogoSvg">
                <div id="svg_filtre_parent" onclick="openFiltre(this)">
                    <img src="../svg/FILTRE_FILTRE.svg" id="svgFiltre">
                </div>
            </div>
        </div>
        <div class="main_conteneur">
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
                            <div class="filtre_parent_label"><label class="check" onclick="changeFilter(event)" id="mainFilter" for="filtre_pertinence">Pertinence</label><input type="checkbox" id="filtre_pertinence" checked></div>
                            <div class="filtre_parent_label"><label class="uncheck" onclick="changeFilter(event))" for="filtre_date">Date</label><input type="checkbox" id="filtre_date"></div>
                            <div class="filtre_parent_label"><label class="uncheck" onclick="changeFilter(event)" for="filtre_demande">Demande</label><input type="checkbox" id="filtre_demande"></div>
                            <div class="filtre_parent_label"><label class="uncheck" onclick="changeFilter(event)" for="filtre_auteur">Auteur</label><input type="checkbox" id="filtre_auteur"></div>
                        </span>
                    </div>
                    <div class="filtre_parent" id="jours">
                        <h1 class="filtre_entete">Jour</h1>
                        <span class="filtre_span" id="spanJour">
                            <div class="filtre_parent_label"><label class="check" for="filtre_lundi">Lundi</label><input type="checkbox" id="filtre_lundi" onclick="changeJour(event)" checked></div>
                            <div class="filtre_parent_label"><label class="check" for="filtre_mardi">Mardi</label><input type="checkbox" id="filtre_mardi" onclick="changeJour(event)" checked></div>
                            <div class="filtre_parent_label"><label class="check" for="filtre_mercredi">Mercredi</label><input type="checkbox" id="filtre_mercredi" onclick="changeJour(event)" checked></div>
                            <div class="filtre_parent_label"><label class="check" for="filtre_jeudi">Jeudi</label><input type="checkbox" id="filtre_jeudi" onclick="changeJour(event)" checked></div>
                            <div class="filtre_parent_label"><label class="check" for="filtre_vendredi">Vendredi</label><input type="checkbox" id="filtre_vendredi" onclick="changeJour(event)" checked></div>
                            <div class="filtre_parent_label"><label class="check" for="filtre_samedi">Samedi</label><input type="checkbox" id="filtre_samedi" onclick="changeJour(event)" checked></div>
                    </div>
                    <div class="filtre_parent" id="type">
                        <h1 class="filtre_entete">Type</h1>
                        <span class="filtre_span" id="spanType">
                            <div class="filtre_parent_label"><label class="check" for="filtre_cours">CM</label><input type="checkbox" id="filtre_cours" onclick="changeTypeFilter(event)" checked></div>
                            <div class="filtre_parent_label"><label class="check" for="filtre_td">TD</label><input type="checkbox" id="filtre_td" onclick="changeTypeFilter(event)" checked></div>
                            <div class="filtre_parent_label"><label class="check" for="filtre_tp">TP</label><input type="checkbox" id="filtre_tp" onclick="changeTypeFilter(event)" checked></div>
                    </div>
                    <div class="filtre_parent" id="jours">
                        <h1 class="filtre_entete">Semaine</h1>
                        <span class="filtre_span" id="spanSemaine">
                            <div class="filtre_parent_label"><label id="semaine-sA" class="check"  for="filtre_sA">Semaine A</label><input type="checkbox" id="filtre_sA" onclick="changeSemaine(event)" checked></div>
                            <div class="filtre_parent_label"><label id="semaine-sB" class="check" for="filtre_sB">Semaine B</label><input type="checkbox" id="filtre_sB"  onclick="changeSemaine(event)" checked></div>
                    </div>
                    <div class="filtre_parent" id="heures">
                        <h1>Horaires</h1>
                        <span class="filtre_span">
                            <div>
                                <input type="time" id="filtre-input-hdebut" name="hdebut" value="08:00" required onchange="canDisplayCourses(event)">
                            </div>
                            <div>
                                <input type="time" id="filtre-input-hfin" name="hfin" value="20:00" required onchange="canDisplayCourses(event)">
                            </div>
                        </span>
                    </div>
                    <div class="buttonFiltres">
                        <button class="filtreButton" id="appliquerFiltre" onclick="resetFilter(event)">Supprimer les filtres</button>
                    </div>
                </div>
            </div>
            <div class="demandesControl">
                <div class="demande_container" id="demande_container1">
                    <?php
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
                                <div class="rectangle_demande"></div>
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
            
        </div>
    </main>

    <script src="../js/demandes.js"></script>
</body>

</html>
