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
    <?php
    
    include "header.php"

    ?>
    <main>
        <div id="demandes_pannel">
            <?php
                $login = "bazileel";
                $connect = DBCredential();
                $sqlSelectDemandes = "SELECT d.idDemande, d.codeUV, d.type, d.semaine, d.jour, d.horaireDebut, d.horaireFin, d.salle, (SELECT COUNT(*) FROM swap s WHERE (s.idDemande = d.idDemande or s.demandeur = d.idDemande) and statut > 0 ) as nbDemande, p.nom, p.prenom FROM demande d JOIN etudiant e ON e.login = d.login JOIN personne p ON p.login = e.login WHERE d.idDemande != ? and d.demande=1;";
                $stmtSelectDemandes = $connect->prepare($sqlSelectDemandes);
                $stmtSelectDemandes->bind_param("s", $login);
                
                $stmtSelectDemandes->execute();
                // Obtenir le résultat
                $resultat = $stmtSelectDemandes->get_result();

                // Vérifier s'il y a des résultats
                if ($resultat->num_rows > 0) {
                    // Afficher les options du datalist
                    
                    while ($row = $resultat->fetch_assoc()) {
                        $personne= ucfirst($row["nom"])." ".ucfirst($row["prenom"]);
                        $row['semaine'] = ($row['semaine'] === 'null') ? null : $row['semaine'];
                        echo '<div class="demande" data-row="' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . '" onclick="clickDemande(this)">';
                        echo '<div class="demande_info1">';
                        echo '<img src="../svg/demande_thing.svg">';
                        echo '<div>';
                        echo '<h1>'.$row["codeUV"].' - '.$row["type"] .'</h1>';
                        echo '<h2>'.nombreEnJour($row["jour"]).'  '.date("H\hi", strtotime($row["horaireDebut"])).' - '.date("H\hi", strtotime($row["horaireFin"])).' | '.$row["salle"] .'</h2>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="demande_info2">';
                        echo '<div>';
                        echo '<p>Auteur: </p><p>'.$personne.'</p>';
                        echo '</div>';
                        echo '<div>';
                        echo '<p>Demande(s):</p><p>'.$row["nbDemande"] .'</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '<img src="../svg/swap_icon.svg" onclick="copierLien(this)">';
                        echo '</div>';

                    }
                }

            ?>
        </div>
        <div id="others_demandes">
            <div id="demandes_top">
                <div class="demandes_top">
                    <h1></h1>
                    <hr>
                </div>
                <div class="demandes_content">
                </div>
                <div class="demandes_bottom">
                    <button></button>
                </div>
            </div>
            <div id="demandes_bottom">
            </div>
        </div>
    </main>
    <script src="../js/demandes.js"></script>
</body>

</html>
