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
                <h1>Optimisez votre emploi du temps avec UT’Swap ! </h1>
                <p>Échangez cours, TD, et TP beaucoup plus facilement et façonnez votre emploi du temps de la manière que vous voulez</p>
                <button>Commencer maintenant</button>
            </div>


            <div id="treemap"></div>
        </div>
        

        
    </main>


    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script>

    function randomNudeColor() {
        // Plage de valeurs pour la teinte (0-360)
        var hue = Math.random() * 360;
        // Plage de valeurs pour la saturation (20-70)
        var saturation = Math.random() * 50 + 50;
        // Plage de valeurs pour la luminosité (75-90)
        var lightness = Math.random() * 15 + 75;

        // Convertir les valeurs en format HSL (Teinte, Saturation, Luminosité)
        return 'hsl(' + hue + ',' + saturation + '%,' + lightness + '%)';
    }
    // Données JSON exemple
    var data = {
    name: "UVs",
    children: [
        { name: "MT02", value: 20 },
        { name: "MT23", value: 20 },
        { name: "NF16", value: 20 },
        { name: "NF05", value: 20 },
        { name: "NF02", value: 30 },
        { name: "NF93", value: 30 },
        { name: "NF92", value: 30 },
        { name: "SY01", value: 40 },
        { name: "SY02", value: 40 },
        { name: "SA12", value: 40 },
        // Ajoutez plus de données selon vos besoins
    ]
    };

    var width = 800;
    var height = 360;

    // Création de la treemap avec D3.js
    var treemap = d3.treemap()
    .size([width, height]) // Taille de la treemap
    .padding(5) // Espacement entre les cellules
    .round(true) // Arrondir les dimensions des cellules


    // Sélection de l'élément conteneur
    var svg = d3.select("#treemap")
    .append("svg")
    .attr("width", width)
    .attr("height", height)
    .style("background", "none");

    // Conversion des données en hiérarchie
    var root = d3.hierarchy(data)
    .sum(d => d.value)
    .sort((a, b) => b.value - a.value);


    // Génération de la treemap
    treemap(root);

    // Génération des couleurs aléatoires pour chaque élément de la treemap
    data.children.forEach(function(d) {
        d.color = randomNudeColor();
    });

    // Création des rectangles pour représenter les données
    svg.selectAll("rect")
    .data(root.leaves())
    .enter().append("rect")
        .attr("x", d => d.x0)
        .attr("y", d => d.y0)
        .attr("width", d => d.x1 - d.x0)
        .attr("height", d => d.y1 - d.y0)
        .attr("rx", 15) // Rayon horizontal
        .attr("ry", 15) // Rayon vertical
        .attr('fill', function(d) { return d.data.color; });

    svg.selectAll(".name-text")
        .data(root.leaves())
        .enter().append("text")
            .attr("class", "name-text")
            .attr("x", d => (d.x0 + d.x1) / 2)
            .attr("y", d => (d.y0 + d.y1) / 2 - 9)
            .attr("text-anchor", "middle")
            .attr("alignment-baseline", "middle")
            .text(d => d.data.name);

    svg.selectAll(".value-text")
        .data(root.leaves())
        .enter().append("text")
            .attr("class", "value-text")
            .attr("x", d => (d.x0 + d.x1) / 2)
            .attr("y", d => (d.y0 + d.y1) / 2 + 9)
            .attr("text-anchor", "middle")
            .attr("alignment-baseline", "middle")
            .attr("font-size", "13px")
            .text(function(d) { return d.data.value; });
    </script>
    <script src="../js/home.js"></script>
</body>

</html>
