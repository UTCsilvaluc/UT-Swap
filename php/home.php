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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/home.css">
    <title>Home - UT'Swap</title>
    <link rel="icon" href="../img/logo_mini.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    
    <main>
        <div id="main_container">
            <svg viewBox="0 0 500 130" width="1500" id="home_bg" height="100%">
                <path id="path_bg" d="M 0 100 C 100 150 400 50 500 100 L 500 130 L 0 130" stroke="white"></path>
            </svg>
            
            <div id="text_intro">
                <h1>Optimisez votre emploi du temps avec UT'Swap ! </h1>
                <p>Échangez cours, TD, et TP beaucoup plus facilement et façonnez votre emploi du temps de la manière que vous voulez</p>
                <a href="emploiDuTemps.php">Commencer maintenant</a>
            </div>
            <div id="icon_slogan_parent">
                <div class="icon_slogan"><img src="../img/gain_temps.png" alt=""><h1>Gain de temps</h1></div>
                <div class="icon_slogan"><img src="../img/optimisation.png" alt=""><h1>Optimisation</h1></div>
                <div class="icon_slogan"><img src="../img/personalisation.png" alt=""><h1>Personalisation</h1></div>
                <div class="icon_slogan"><img src="../img/partage.png" alt=""><h1>Partage</h1></div>
            </div>

            
            
            <div id="treemap_container">
                <h1 class="home_title">UVs les plus demandées:</h1>
                <div id="treemap"></div>
            </div>

            <div id="input_home_parent">
                <div id="input_home_title">
                    <h1>Trouvez votre prochain swap:</h1>
                    <p>Recherchez votre swap parmi des demandes de TD, TP, cours magistraux et ce pour tous les jours de la semaine</p>
                </div>
                <div id="input_home">
                    <input type="text" id="codeUV_input_home" placeholder="Chercher une UV" maxlength='4'>
                    <select id="type_input_home">
                        <option value="all">Chercher un type</option>
                        <option value="TP">TP</option>
                        <option value="TD">TD</option>
                        <option value="CM">CM</option>
                    </select>
                    <button onclick="searchSwap()"><img src="../svg/search.svg"></button>
                </div>
            </div>

            <div class="home_pannel" id="tips_home_parent">
                <div>
                    <img src="../img/prof.png">
                    <p>UT'Swap fonctionne sur le principe de <span class="important_word">triple entente</span>, celle entre 2 étudiants et 1 responsable. Une <span class="important_word">demande</span> est la démarche d'un étudiant à un autre, même si celle-ci est acceptée le <span class="important_word">swap</span> ne l'est pas forcément. Il faudra attendre la <span class="important_word">réponse du reponsable d'UV</span></p>
                </div>
            </div>

            <div class="home_pannel hidden" id="video_home_parent">
                <h1 class="home_title">Vidéo tuto UT'Swap:</h1>
                <div>
                    <iframe src="https://www.youtube.com/embed/7btKqhqQ4yc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            

            <div id="swap_impossible" class="home_pannel">
                <div id="swap_impossible_header">
                    <h1 class="home_title">UVs possibilité swap:</h1>
                    <div>
                        <input type="text" maxlength="4" id="codeUV_input_swap" placeholder="Chercher une UV">
                        <img src="../svg/search.svg">
                    </div>
                </div>
                <div class="results">
                    <?php
                        $connect = DBCredential();
                        $sqlUvs = "SELECT codeUV, swap, nom, prenom FROM `uv` JOIN personne p ON p.login = uv.responsable ORDER BY codeUV;";
                        $result = $connect->query($sqlUvs);
                        if($result->num_rows > 0){
                            foreach ($result as $row) {
                                if($row["swap"] == 0){
                                    $image = '<img title="Le responsable n\'autorise pas les swaps pour cette uv" src="../svg/croix_rouge.svg">';
                                }else{
                                    $image = '<img title="Le responsable autorise les swaps pour cette uv" src="../svg/check_vert.svg">';
                                }
                                ?>
                                <div class="item_uv">
                                    <div class="item_top"><h2><?= $row["codeUV"] ?></h2><?= $image ?></div>
                                    <div class="item_bottom"><span>Resp:</span><span><?= ucfirst($row["nom"]) ?> <?= ucfirst($row["prenom"]) ?></span></div>
                                </div>
                                <?php
                            }
                        }
                    ?>
                    
                </div>
            </div>
        </div>
    </main>

    <?php include "footer.php" ?>

    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>var data = {
    name: "UVs",
    children: [
        <?php
            $connect = DBCredential();
            $sqlData = "SELECT codeUV, (SELECT COUNT(*) FROM swap s JOIN demande d ON d.idDemande = s.idDemande WHERE d.codeUV = uv.codeUV) AS swap_count FROM uv ORDER BY swap_count DESC LIMIT 10;";
            $result = $connect->query($sqlData);
            //dans la treemap les éléments seront dispaché en groupe de 3 pour un soucis visuel
            $groupValues = [40, 30, 20, 10];
            if($result->num_rows > 0){
                $counter = 0;
                foreach ($result as $row) {
                    $groupValue = $groupValues[floor($counter / 3)];
                    ?>
                    { name:'<?= $row["codeUV"] ?>', nbSwap: <?= $row["swap_count"] ?>, value: <?= $groupValue ?> },
                    <?php
                    $counter++;
                }
            }
        ?>
        // Ajoutez plus de données selon vos besoins
    ]
    };
    
    </script>
    <script src="../js/home.js"></script>
</body>

</html>
