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
    <link rel="stylesheet" href="../css/home.css">
    <title>Home - UT'Swap</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include "header.php" ?>
    
    <main>
        <svg viewBox="0 0 500 130" width="1500" id="home_bg" height="100%">
            <path id="path_bg" d="M 0 100 C 100 150 400 50 500 100 L 500 130 L 0 130" stroke="white"></path>
        </svg>
        <div id="main_container">
            <div id="text_intro">
                <h1>Optimisez votre emploi du temps avec UT'Swap ! </h1>
                <p>Échangez cours, TD, et TP beaucoup plus facilement et façonnez votre emploi du temps de la manière que vous voulez</p>
                <a href="emploiDuTemps.php">Commencer maintenant</a>
            </div>

            <div id="treemap_container">
                <h1>UVs les plus demandées:</h1>
                <div id="treemap"></div>
            </div>
            
        </div>
        

        
    </main>


    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>

    
    </script>
    <script src="../js/home.js"></script>
</body>

</html>
